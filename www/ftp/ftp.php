<?php
/**
 * FTP 파일 관리 페이지
 * FTP 서버의 파일 업로드/다운로드/삭제 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 변수 초기화
$view_ext = array("php", "db", "html", "js", "css"); // 검색제외항목 확장자
$msg = ""; // 화면 메시지 변수
$list = array(); // select 박스에 파일 목록 리스트
$ftp = null;

/**
 * 리스트 정보에서 <DIR>이 포함되어 있는지 확인하는 함수
 * @param string $str 파일/디렉토리 정보 문자열
 * @return int|false 위치 또는 false
 */
function is_directory($str) {
    return stripos($str, '<DIR>');
}

/**
 * 리스트 정보에서 파일/디렉토리 이름을 취득하는 함수
 * @param string $str 파일/디렉토리 정보 문자열
 * @return string 파일/디렉토리 이름
 */
function get_name($str) {
    // 문자열 뒤에서 콜론을 찾는다.
    $pos = strripos($str, ':');
    if ($pos === false) {
        return trim($str);
    }
    // 콜론에서부터 끝까지 문자열을 자른다.
    return substr($str, $pos + 4);
}

/**
 * FTP 서버의 모든 디렉토리, 파일을 삭제하는 함수
 * @param resource $ftp FTP 연결 리소스
 * @param string $cwd 현재 작업 디렉토리
 */
function delete_all_ftp($ftp, $cwd = "") {
    // FTP서버의 리스트를 취득한다.(DETAIL)
    $list = ftp_rawlist($ftp, $cwd);
    
    if ($list === false) {
        error_log("Failed to get FTP list for: {$cwd}");
        return;
    }
    
    // 리스트를 Iteration 방식으로 데이터를 받는다.
    foreach ($list as $val) {
        // 디렉토리인지 확인
        if (is_directory($val)) {
            // 디렉토리라면 재귀적 방법으로 하위 디렉토리의 파일을 삭제한다.
            delete_all_ftp($ftp, $cwd . get_name($val) . "/");
        } else {
            // 파일이면 삭제한다.
            $fileName = $cwd . get_name($val);
            if (!ftp_delete($ftp, $fileName)) {
                error_log("Failed to delete FTP file: {$fileName}");
            }
        }
    }
    
    // 디렉토리 삭제
    if ($cwd != "") {
        if (!ftp_rmdir($ftp, $cwd)) {
            error_log("Failed to remove FTP directory: {$cwd}");
        }
    }
}

/**
 * FTP 서버에 파일을 업로드하는 함수
 * @param resource $ftp FTP 연결 리소스
 * @param string $name 저장할 파일명
 * @param string $path 로컬 파일 경로
 */
function upload_ftp($ftp, $name, $path) {
    // HDD1/test 디렉토리로 이동
    if (!@ftp_chdir($ftp, 'HDD1/test')) {
        error_log("Failed to change FTP directory to HDD1/test");
        return;
    }
    
    $fp = null;
    
    try {
        // 파일 커넥션을 만든다.
        $fp = fopen($path, "r");
        if ($fp === false) {
            throw new Exception("Failed to open file: {$path}");
        }
        
        // 파일을 ftp 서버로 업로드한다.
        if (!ftp_fput($ftp, $name, $fp, FTP_BINARY)) {
            throw new Exception("Failed to upload file: {$name}");
        }
    } catch (Exception $ex) {
        error_log("FTP upload error: " . $ex->getMessage());
    } finally {
        // 파일 커넥션 닫는다.
        if ($fp !== null && is_resource($fp)) {
            fclose($fp);
        }
    }
    
    // ftp 접속 디렉토리를 root로 이동한다.
    @ftp_chdir($ftp, "/");
}

/**
 * FTP에서 파일 리스트를 탐색하는 함수
 * @param resource $ftp FTP 연결 리소스
 * @param string $cwd 현재 작업 디렉토리
 * @param array $ret 결과 배열
 * @return array 파일 목록
 */
function search_ftp($ftp, $cwd = "", $ret = array()) {
    // FTP서버의 리스트를 취득한다.(DETAIL)
    $list = ftp_rawlist($ftp, $cwd);
    
    if ($list === false) {
        error_log("Failed to get FTP list for: {$cwd}");
        return $ret;
    }
    
    // 리스트를 Iteration 방식으로 데이터를 받는다.
    foreach ($list as $val) {
        // 디렉토리인지 확인
        if (is_directory($val)) {
            // 디렉토리라면 재귀적 방법으로 하위 디렉토리의 파일을 탐색한다.
            $ret = search_ftp($ftp, $cwd . get_name(trim($val)) . "/", $ret);
        } else {
            // 파일이라면 결과 리스트에 파일명을 추가한다.
            array_push($ret, $cwd . get_name(trim($val)));
        }
    }
    
    // 결과 리스트 반환
    return $ret;
}

/**
 * FTP에서 파일을 다운로드하는 함수
 * @param resource $ftp FTP 연결 리소스
 * @param string $path FTP 파일 경로
 */
function download_ftp($ftp, $path) {
    // ftp에서 파일을 다운로드하여 임시로 파일을 생성하는 경로
    $temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('ftp_download_');
    $fp = null;
    
    try {
        // 파일 커넥션을 만든다.
        $fp = fopen($temp, "w");
        if ($fp === false) {
            throw new Exception("Failed to create temp file");
        }
        
        // ftp 서버로부터 파일을 생성한다.
        if (!ftp_fget($ftp, $fp, $path, FTP_BINARY, 0)) {
            throw new Exception("Failed to download file from FTP");
        }
        
    } catch (Exception $ex) {
        error_log("FTP download error: " . $ex->getMessage());
        if ($fp !== null && is_resource($fp)) {
            fclose($fp);
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        die("파일 다운로드 중 오류가 발생했습니다.");
    } finally {
        // 파일 커넥션 닫는다.
        if ($fp !== null && is_resource($fp)) {
            fclose($fp);
        }
    }
    
    // 디렉토리 경로를 제외한 파일명을 변환
    $pos = strripos($path, '/');
    $name = substr($path, $pos + 1);
    
    // 응답 헤더 재설정
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($name) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($temp));
    
    // 바이너리 body에 출력
    readfile($temp);
    
    // 임시 파일 삭제
    unlink($temp);
    
    // 응답
    exit;
}

/**
 * 디렉토리 파일 목록을 출력하는 함수
 * @param string $dir 디렉토리 경로
 */
function listFolderFiles($dir) {
    $ffs = @scandir($dir);
    
    if ($ffs === false) {
        error_log("Failed to scan directory: {$dir}");
        return;
    }
    
    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);
    
    // 디렉토리가 비어있는지 확인
    if (count($ffs) < 1) {
        return;
    }
    
    echo '<ol>';
    foreach ($ffs as $ff) {
        echo '<li>' . htmlspecialchars($ff, ENT_QUOTES, 'UTF-8');
        
        // 재귀함수: 파일이 아닌 디렉토리가 있으면, 스스로 자신을 호출
        if (is_dir($dir . '/' . $ff)) {
            listFolderFiles($dir . '/' . $ff);
        }
        echo '</li>';
    }
    echo '</ol>';
}

// FTP 연결 및 처리
try {
    // FTP 서버 정보
    $ftp_host = 'mirae8440.ipdisk.co.kr';
    $ftp_port = 7700;
    $ftp_user = "mirae8440";
    $ftp_pass = "mirae8441";
    
    // FTP 서버 IP 조회
    $ip = gethostbyname($ftp_host);
    
    // FTP 연결
    $ftp = ftp_connect($ip, $ftp_port);
    if ($ftp === false) {
        throw new Exception("FTP 서버에 연결할 수 없습니다: {$ip}");
    }
    
    // 로그인
    if (ftp_login($ftp, $ftp_user, $ftp_pass)) {
        // 디렉토리 변경
        if (!ftp_chdir($ftp, 'HDD1/test')) {
            error_log("Failed to change FTP directory to HDD1/test");
            $msg = "디렉토리 변경 실패";
        }
        
        // Web 요청 method가 POST라면
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $post_type = $_POST["type"] ?? '';
            
            // FTP의 모든 파일 삭제
            if ($post_type === "all_delete") {
                delete_all_ftp($ftp);
                $msg = "모든 파일이 삭제되었습니다.";
                
            // FTP 서버에 파일을 업로드
            } else if ($post_type === "upload") {
                if (isset($_FILES["upload"]) && isset($_FILES["upload"]["name"])) {
                    $count = count($_FILES["upload"]["name"]);
                    
                    for ($i = 0; $i < $count; $i++) {
                        $upload_name = $_FILES["upload"]["name"][$i] ?? '';
                        $upload_tmp = $_FILES["upload"]["tmp_name"][$i] ?? '';
                        
                        if (!empty($upload_name) && !empty($upload_tmp)) {
                            // 공백 제거하여 업로드
                            upload_ftp($ftp, str_replace(' ', '', $upload_name), $upload_tmp);
                            $msg .= "파일 업로드 완료 - " . htmlspecialchars($upload_name, ENT_QUOTES, 'UTF-8') . "<br>";
                        }
                    }
                }
                
            // FTP 서버에서 파일을 다운로드
            } else if ($post_type === "download") {
                $download_file = $_POST["download"] ?? '';
                if (!empty($download_file)) {
                    download_ftp($ftp, $download_file);
                }
                
            // FTP 서버 디렉토리 읽기
            } else if ($post_type === "directory") {
                listFolderFiles($ftp);
            }
        }
        
        // FTP 파일 목록 가져오기
        $list = search_ftp($ftp);
        
        echo '현재 Dir: ' . htmlspecialchars(ftp_pwd($ftp), ENT_QUOTES, 'UTF-8') . '<br>';
        
        foreach ($list as $val) {
            echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . '<br>';
        }
        
    } else {
        // 로그인 실패
        $msg = "FTP 로그인에 실패했습니다.";
    }
    
} catch (Exception $ex) {
    error_log("FTP connection error: " . $ex->getMessage());
    $msg = "FTP 연결 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8');
} finally {
    // FTP 연결 종료
    if ($ftp !== null && is_resource($ftp)) {
        ftp_close($ftp);
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTP 파일 관리</title>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        body {
            padding: 20px;
        }
        
        .form-section {
            margin-bottom: 20px;
        }
        
        .file-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
    
    <script type='text/javascript'>
        var maxSize = 2048; // 1MB = 1024KB
        
        /**
         * 파일 정보 확인 함수
         */
        function fileCheck() {
            var file = document.getElementById('fileInput');
            
            if (!file || !file.files || file.files.length === 0) {
                alert('파일을 선택해주세요.');
                return;
            }
            
            var filePath = file.value;
            var filePathSplit = filePath.split('\\');
            var filePathLength = filePathSplit.length;
            var fileNameSplit = filePathSplit[filePathLength - 1].split('.');
            var fileName = fileNameSplit[0];
            var fileExt = fileNameSplit[1] || '';
            var fileSize = file.files[0].size;
            var fileSizeKB = (fileSize / 1024).toFixed(2);
            
            console.log('파일 경로: ' + filePath);
            console.log('파일명: ' + fileName);
            console.log('파일 확장자: ' + fileExt);
            console.log('파일 크기: ' + fileSize + ' bytes (' + fileSizeKB + ' KB)');
            
            // 크기 제한 체크
            if (fileSize / 1024 > maxSize) {
                alert('파일 크기가 ' + maxSize + 'KB를 초과합니다.\n현재 크기: ' + fileSizeKB + 'KB');
                return;
            }
            
            alert('파일 정보:\n파일명: ' + fileName + '\n확장자: ' + fileExt + '\n크기: ' + fileSizeKB + ' KB');
        }
        
        /**
         * 이미지 변경 시 미리보기
         */
        function imageChange() {
            var input = document.getElementById("fileInput");
            
            if (!input || !input.files || input.files.length === 0) {
                return;
            }
            
            var fReader = new FileReader();
            
            fReader.onloadend = function(event) {
                var img = document.getElementById("yourImgTag");
                if (img) {
                    img.src = event.target.result;
                    console.log('이미지 로드 완료');
                }
            };
            
            fReader.onerror = function(event) {
                console.error('파일 읽기 오류:', event);
                alert('파일을 읽는 중 오류가 발생했습니다.');
            };
            
            fReader.readAsDataURL(input.files[0]);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>FTP 파일 관리</h2>
        
        <!-- 결과 메시지 표시 -->
        <?php if (!empty($msg)) { ?>
        <div class="alert alert-info">
            <?php echo $msg; ?>
        </div>
        <?php } ?>
        
        <!-- FTP 관리 폼 -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="type" name="type">
            
            <div class="card form-section">
                <div class="card-header">파일 업로드</div>
                <div class="card-body">
                    <input type="file" name="upload[]" class="form-control-file" multiple>
                    <button type="button" class="btn btn-primary mt-2" onclick="$('form #type').val('upload'); $('form').attr('enctype','multipart/form-data'); $('form').submit();">
                        업로드
                    </button>
                </div>
            </div>
            
            <div class="card form-section">
                <div class="card-header">파일 다운로드</div>
                <div class="card-body">
                    <select name="download" class="form-control">
                        <?php foreach ($list as $item) { ?>
                        <option value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <?php } ?>
                        <?php if (count($list) < 1) { ?>
                        <option value="">파일 없음</option>
                        <?php } ?>
                    </select>
                    <button type="button" class="btn btn-success mt-2" onclick="$('form #type').val('download'); $('form').attr('enctype',''); $('form').submit();">
                        다운로드
                    </button>
                </div>
            </div>
            
            <div class="card form-section">
                <div class="card-header">관리</div>
                <div class="card-body">
                    <button type="button" class="btn btn-info" onclick="$('form #type').val('directory'); $('form').attr('enctype',''); $('form').submit();">
                        디렉토리 목록
                    </button>
                    <button type="button" class="btn btn-danger" onclick="if(confirm('모든 파일을 삭제하시겠습니까?')) { $('form #type').val('all_delete'); $('form').attr('enctype',''); $('form').submit(); }">
                        모든 파일 삭제
                    </button>
                </div>
            </div>
        </form>
        
        <!-- 파일 정보 확인 -->
        <div class="card form-section">
            <div class="card-header">파일 정보 확인</div>
            <div class="card-body">
                <input type='file' id='fileInput' class="form-control-file" onchange="imageChange()">
                <button type='button' class="btn btn-primary mt-2" onclick='fileCheck()'>파일 정보 확인</button>
                <div id="imagePreview" class="mt-3" style="display: none;">
                    <img id="yourImgTag" src="" alt="이미지 미리보기" style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // 이미지 로드 시 미리보기 영역 표시
        $('#yourImgTag').on('load', function() {
            if (this.src && this.src !== window.location.href) {
                $('#imagePreview').show();
            }
        });
    </script>
</body>
</html>
