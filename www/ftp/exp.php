<?php
/**
 * FTP 파일 탐색 및 검색 페이지
 * FTP 서버의 파일을 검색하고 목록을 표시합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 요청 파라미터 초기화
$s01 = $_REQUEST['s01'] ?? ''; // 검색파일이름 지정
$s02 = $_REQUEST['s02'] ?? ''; // 검색파일이름 지정

// 변수 초기화
$path = "./"; // 검색할 폴더 지정
$msg = ""; // 화면 메시지 변수
$list = array(); // select 박스에 파일 목록 리스트
$ftp = null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,target-densitydpi=medium-dpi">
    <title>FTP 파일 탐색</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <form action="_FileList.php" method="post">
        <table>
            <tr>
                <td>
                    <input type="text" name="s01" size="18" value="<?php echo htmlspecialchars($s01, ENT_QUOTES, 'UTF-8'); ?>" placeholder="검색어 1">
                    <input type="text" name="s02" size="18" value="<?php echo htmlspecialchars($s02, ENT_QUOTES, 'UTF-8'); ?>" placeholder="검색어 2">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="검색">
                </td>
            </tr>
        </table>
    </form>

<?php
/**
 * FTP 파일 검색 함수
 * @param resource $ftp_connection FTP 연결 리소스
 * @param string $path 검색할 경로
 * @param string $search1 첫 번째 검색어
 * @param string $search2 두 번째 검색어
 * @return array 검색된 파일 목록
 */
function get_files($ftp_connection, $path = "./", $search1 = "", $search2 = "") {
    $invalid_char = array("\\", "*", "+", "/", "\"", "?", "|");
    $list_result = array();
    
    // 검색어 길이 검증
    if (strlen($search1) <= 2) {
        echo '검색 문자열이 너무 짧습니다. (01)<br>';
        return $list_result;
    }
    
    if (strlen($search1) >= 30) {
        echo '검색 문자열이 너무 깁니다. (01)<br>';
        return $list_result;
    }
    
    if (strlen($search2) > 0) {
        if (strlen($search2) < 2) {
            echo '검색 문자열이 너무 짧습니다. (02)<br>';
            return $list_result;
        }
        
        if (strlen($search2) >= 30) {
            echo '검색 문자열이 너무 깁니다. (02)<br>';
            return $list_result;
        }
    }
    
    // invalid_char에 해당하는 검색어는 잘못된 파일 형식이므로 제거
    $search1 = str_replace($invalid_char, "", $search1);
    $search2 = str_replace($invalid_char, "", $search2);
    
    // 패턴 검사를 위해 특수문자 이스케이프
    $escape = array(
        "(" => "\\(",
        ")" => "\\)",
        "[" => "\\[",
        "]" => "\\]"
    );
    
    $dh = opendir($path);
    if ($dh === false) {
        error_log("Failed to open directory: {$path}");
        return $list_result;
    }
    
    while (($read = readdir($dh)) !== false) {
        if ($read == '.' || $read == '..') {
            continue;
        }
        
        $fullPath = $path . $read . '/';
        
        if (is_dir($fullPath)) {
            // 재귀적으로 하위 디렉토리 검색
            $subList = get_files($ftp_connection, $fullPath, $search1, $search2);
            $list_result = array_merge($list_result, $subList);
        } else {
            // 대소문자 구분없이 파일명에 검색어가 있는지 비교 (stripos 사용)
            $pattern1 = str_replace(array_keys($escape), array_values($escape), $search1);
            
            if (stripos($read, $search1) !== false) {
                if (strlen($search2) > 0) {
                    $pattern2 = str_replace(array_keys($escape), array_values($escape), $search2);
                    
                    if (stripos($read, $search2) !== false) {
                        $ext = strtolower(substr($read, (strrpos($read, '.') + 1)));
                        $read_utf8 = iconv('euckr', 'utf-8', $read);
                        $filesize = @filesize($path . $read);
                        $list_result[] = $read_utf8 . " : " . $filesize;
                    }
                } else {
                    $ext = strtolower(substr($read, (strrpos($read, '.') + 1)));
                    $read_utf8 = iconv('euckr', 'utf-8', $read);
                    $filesize = @filesize($path . $read);
                    $list_result[] = $read_utf8 . " : " . $filesize;
                }
            }
        }
    }
    
    closedir($dh);
    return $list_result;
}

try {
    // FTP 서버 연결
    $ip = gethostbyname('mirae8440.ipdisk.co.kr');
    $ftp_server = $ip;
    $ftp_port = 7700;
    $ftp_user = "mirae8440";
    $ftp_pass = "mirae8441";
    
    $ftp = ftp_connect($ftp_server, $ftp_port);
    if ($ftp === false) {
        throw new Exception("FTP 서버에 연결할 수 없습니다: {$ftp_server}");
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
            
            // FTP의 모든 파일 삭제 타입
            if ($post_type === "all_delete") {
                // FTP의 모든 파일 삭제
                if (function_exists('delete_all_ftp')) {
                    delete_all_ftp($ftp);
                    $msg = "All file was deleted.";
                }
            // FTP 서버에 파일을 업로드
            } else if ($post_type === "upload") {
                // input type=file에 multiple를 추가하면 배열 형식으로 데이터가 온다.
                if (isset($_FILES["upload"]) && isset($_FILES["upload"]["name"])) {
                    $count = count($_FILES["upload"]["name"]);
                    
                    // 배열의 개수만큼 Iterate
                    for ($i = 0; $i < $count; $i++) {
                        $upload_name = $_FILES["upload"]["name"][$i] ?? '';
                        $upload_tmp = $_FILES["upload"]["tmp_name"][$i] ?? '';
                        
                        if (!empty($upload_name) && !empty($upload_tmp)) {
                            // 업로드 호출 (파일 이름의 공백 제거)
                            if (function_exists('upload_ftp')) {
                                upload_ftp($ftp, str_replace(' ', '', $upload_name), $upload_tmp);
                                $msg .= "파일 업로드 완료 - " . htmlspecialchars($upload_name, ENT_QUOTES, 'UTF-8') . "<br>";
                            }
                        }
                    }
                }
            // FTP 서버에서 파일을 다운로드
            } else if ($post_type === "download") {
                $download_file = $_POST["download"] ?? '';
                if (!empty($download_file) && function_exists('download_ftp')) {
                    download_ftp($ftp, $download_file);
                }
            // FTP 서버 디렉토리 읽기
            } else if ($post_type === "directory") {
                if (function_exists('listFolderFiles')) {
                    listFolderFiles($ftp);
                }
            }
        }
        
        // FTP 파일 목록 가져오기
        if (function_exists('search_ftp')) {
            $list = search_ftp($ftp);
        } else {
            $ftp_list = ftp_nlist($ftp, ".");
            $list = $ftp_list !== false ? $ftp_list : array();
        }
        
        sort($list);
        
        echo '현재 Dir: ' . htmlspecialchars(ftp_pwd($ftp), ENT_QUOTES, 'UTF-8') . '<br>';
        
        foreach ($list as $val) {
            echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . '<br>';
        }
        
    } else {
        // 로그인 실패
        $msg = "FTP 로그인에 실패했습니다.";
        echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    }
    
} catch (Exception $ex) {
    error_log("FTP connection error: " . $ex->getMessage());
    echo "FTP 연결 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8');
} finally {
    // FTP 연결 종료
    if ($ftp !== null && is_resource($ftp)) {
        ftp_close($ftp);
    }
}
?>

<h3>검색 결과</h3>
<p>
    <?php echo htmlspecialchars($s01, ENT_QUOTES, 'UTF-8'); ?>, 
    <?php echo htmlspecialchars($s02, ENT_QUOTES, 'UTF-8'); ?>로 검색하여 
    총 <?php echo count($list); ?>개를 찾았습니다.
</p>

<?php
if (!empty($list)) {
    echo "<table border='1'>";
    foreach ($list as $value) {
        $temp = explode(':', $value);
        $filename = $temp[0] ?? '';
        $filesize = $temp[1] ?? '0';
        
        echo "<tr>";
        echo "<td width='300'>" . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td width='100' align='right'>" . htmlspecialchars($filesize, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>검색 결과가 없습니다.</p>";
}
?>

</body>
</html>
