<?php
// 실측서 이미지 등록/수정/삭제 - 로컬/서버 환경 호환

// 1. vendor autoload를 가장 먼저 (namespace 때문에)
require_once __DIR__ . '/../vendor/autoload.php';

// Google API 클래스들이 로드됨 (IDE 인식용 주석)
// @see Google_Client
// @see Google_Service_Drive

// 2. 공통 함수 로드
require_once __DIR__ . '/../common/functions.php';

// 3. 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once includePath('session.php');

// 4. 데이터베이스 연결
require_once includePath('lib/mydb.php');

// 5. 에러 리포팅 설정
if (function_exists('setupErrorReporting')) {
    setupErrorReporting();
}

// 입력값 검증 및 초기화
$num = $_REQUEST["num"] ?? '';
$workername = $_REQUEST["workername"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '0';

// 입력값 유효성 검사
if (empty($num) || !is_numeric($num)) {
    die("유효하지 않은 번호입니다.");
}

// 데이터베이스 연결
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        if (isLocal()) {
            die("데이터베이스 연결 실패: " . $e->getMessage());
        } else {
            error_log("Database connection failed in reg_ms.php: " . $e->getMessage());
            die("데이터베이스 연결에 실패했습니다. 관리자에게 문의하세요.");
        }
    }
}

// Google Drive 클라이언트 설정
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';

try {
    $client = new Google_Client();
    $client->setAuthConfig($serviceAccountKeyFile);
    $client->addScope(Google_Service_Drive::DRIVE);
    
    $service = new Google_Service_Drive($client);
} catch (Exception $e) {
    if (isLocal()) {
        die("Google Drive 초기화 실패: " . $e->getMessage());
    } else {
        error_log("Google Drive 초기화 실패 in reg_ms.php: " . $e->getMessage());
        die("Google Drive 연결에 실패했습니다. 관리자에게 문의하세요.");
    }
}

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    try {
        $response = $service->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)'
        ]); 
        
        return count($response->files) > 0 ? $response->files[0]->id : null;
    } catch (Exception $e) {
        error_log("getFolderId 실패: " . $e->getMessage());
        return null;
    }
}

// '미래기업/uploads' 폴더의 ID 가져오기
$miraeFolderId = getFolderId($service, '미래기업');
$uploadsFolderId = getFolderId($service, 'uploads', $miraeFolderId);

// 현장 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.work WHERE num=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $workplacename = $row["workplacename"] ?? '';
} catch (PDOException $Exception) {
    if (isLocal()) {
        print "오류: " . $Exception->getMessage();
    } else {
        error_log("Database error in reg_ms.php (select work): " . $Exception->getMessage());
        print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
    }
    exit;
}

// Google Drive 및 데이터베이스에서 파일 정보 가져오기
$picData = [];
$tablename = 'work';
$item = 'measure';

// 파일 확인 및 Google Drive 정보 가져오기
$sql = "SELECT * FROM mirae8440.picuploads WHERE tablename = ? AND item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$tablename, $item, $num]);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];
        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            // Google Drive 파일 ID
            $fileId = $picname;

            try {
                // Google Drive 파일 정보 가져오기
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                $webViewLink = $file->webViewLink;
                $picData[] = [
                    'thumbnail' => $thumbnailUrl, 
                    'link' => $webViewLink,
                    'id' => $fileId
                ];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $picData[] = [
                    'thumbnail' => "https://drive.google.com/uc?id=$fileId", 
                    'link' => null,
                    'id' => $fileId
                ];
            }
        } else {
            // 파일 ID가 아닌 경우 로컬 파일로 처리
            $picData[] = [
                'thumbnail' => asset("uploads/" . $picname), 
                'link' => null,
                'id' => $picname
            ];
        }
    }
} catch (PDOException $Exception) {
    if (isLocal()) {
        print "오류: " . $Exception->getMessage();
    } else {
        error_log("Database error in reg_ms.php (select picuploads): " . $Exception->getMessage());
        print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
    }
}

$picNum = count($picData);
?>

<?php include includePath('load_header.php'); ?>

<title> 실측서 이미지 등록/수정/삭제 </title>

<style>
    .thumbnail {
        width: 150px; /* 썸네일 너비 */
        height: auto; /* 비율 유지 */
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
    }
    
    #top-menu {
        background-color: #f8f9fa;
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }
</style>

</head>
<body>
<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-center">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">          
                    <h4 class="modal-title">알림</h4>
                </div>
                <div class="modal-body">		
                    <div id="alertmsg" class="fs-1 mb-5 justify-content-center">
                        결재가 진행중입니다.<br>수정사항이 있으면 결재권자에게 말씀해 주세요.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>

    <div id="top-menu">
        <?php if (!isset($_SESSION["userid"])): ?>
            <a href="<?= getBaseUrl() ?>/login/login_form.php">로그인</a> | 
            <a href="<?= getBaseUrl() ?>/member/insertForm.php">회원가입</a>
        <?php else: ?>
            <div class="row">
                <div class="col">
                    <h2 class="display-5 font-center text-left"><br>
                        <?= $_SESSION["name"] ?> |
                        <a href="<?= getBaseUrl() ?>/login/logout.php">로그아웃</a> | 
                        <a href="<?= getBaseUrl() ?>/member/updateForm.php?id=<?= $_SESSION["userid"] ?>">정보수정</a>
                    </h2>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <br>
    <div class="row">
        <h1 class="display-1 text-left">
            <input type="button" class="btn btn-secondary btn-lg" value="이전화면으로 돌아가기" 
                   onclick="location.href='<?= getBaseUrl() ?>/p/view.php?num=<?= $num ?>&check=<?= $check ?>&workername=<?= $workername ?>'">
        </h1>
    </div>
    <br><br>

    <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
        <input type="hidden" id="check" name="check" value="<?= $check ?>">
        <input type="hidden" id="num" name="num" value="<?= $num ?>">
        <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
        <input type="hidden" id="item" name="item" value="<?= $item ?>">
        <input id="pInput" name="pInput" type="hidden" value="0">		
        
        <div class="container">
            <div class="row d-flex mb-5">
                <h2 class="fs-2 text-center mb-2">실측서 이미지 등록/수정/삭제</h2>
            </div>
            <div class="row">            
                <h2 class="fs-4 text-danger">설계자에게 전달할 추가 내용이 있으면 등록합니다.</h2>
            </div>
            <br>
            <div class="d-flex p-2 justify-content-center">
                <span class="fs-4 badge bg-secondary">현장명 : <?= htmlspecialchars($workplacename) ?> </span>
            </div>
            <div class="row mt-2">			
                <span class="form-control fs-4 text-left text-secondary">
                    <span style="color:gray">실측서 이미지파일 </span>	                  
                    <input id="upfile" name="upfile[]" class="input" type="file" multiple accept=".gif, .jpg, .png">
                </span>	
            </div>    				
            <div class="row d-flex mt-3 mb-3">
                <span class="fs-3 text-left text-secondary">실측서 이미지 </span>
                <div id="displayPicture" class="mb-2">
                    <?php foreach ($picData as $pic): ?>
                        <div class="d-inline-block m-2 text-center">
                            <img src="<?= htmlspecialchars($pic['thumbnail']) ?>" 
                                 class="thumbnail" 
                                 alt="실측서 이미지"
                                 onclick="<?= !empty($pic['link']) ? "window.open('" . htmlspecialchars($pic['link']) . "', '_blank')" : "" ?>">
                            <br>
                            <button type="button" class="btn btn-danger mt-2 btn-sm" 
                                    onclick="deleteFile('<?= htmlspecialchars($pic['id']) ?>')">삭제</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// 환경별 baseUrl 설정
window.baseUrl = '<?= getBaseUrl() ?>';

$(document).ready(function() {
    // 이미지 업로드 이벤트 처리
    $("#upfile").change(function() {
        uploadFile();
    });

    // 파일 업로드 처리
    function uploadFile() {
        var form = $('#board_form')[0];
        var data = new FormData(form);

        $('#alertmsg').text('사진을 저장 중입니다...');
        $('#myModal').modal('show');

        $.ajax({
            url: window.baseUrl + "/p/mspic_insert.php",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log("서버 응답:", response);
                
                // 각 파일에 대한 응답 처리
                let successCount = 0;
                let errorCount = 0;
                let errorMessages = [];

                if (Array.isArray(response)) {
                    response.forEach(item => {
                        if (item.status === 'success') {
                            successCount++;
                        } else if (item.status === 'error') {
                            errorCount++;
                            errorMessages.push(`파일: ${item.file}, 메시지: ${item.message}`);
                        }
                    });
                }

                // 결과에 따라 사용자에게 알림 표시
                if (successCount > 0) {
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: `${successCount}개의 파일이 성공적으로 업로드되었습니다.`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast();
                    } else {
                        alert(`${successCount}개의 파일이 성공적으로 업로드되었습니다.`);
                    }
                }

                if (errorCount > 0) {
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: `오류 발생: ${errorCount}개의 파일이 업로드되지 않았습니다.\n상세 오류:\n${errorMessages.join('\n')}`,
                            duration: 5000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#f44336",
                        }).showToast();
                    } else {
                        alert(`오류 발생: ${errorCount}개의 파일이 업로드되지 않았습니다.\n상세 오류:\n${errorMessages.join('\n')}`);
                    }
                }

                // 업로드 후 디스플레이 업데이트
                $('#myModal').modal('hide');
                displayPictureLoad();
            },
            error: function(jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
                $('#myModal').modal('hide');
                alert("업로드 중 서버 오류가 발생했습니다. 콘솔을 확인하세요.");
            }
        });
    }

    // 기존 이미지 로드
    function displayPictureLoad() {
        let num = $("#num").val();
        let tablename = $("#tablename").val();
        let item = $("#item").val();

        $.ajax({
            url: window.baseUrl + `/p/load_pic.php?num=${num}&tablename=${tablename}&item=${item}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#displayPicture").html('');
                if (data.img_arr && Array.isArray(data.img_arr)) {
                    data.img_arr.forEach((imgData, index) => {
                        let thumbnail = imgData.thumbnail || '/assets/default-thumbnail.png';
                        let link = imgData.link || '#';
                        let id = imgData.id || '';

                        let clickHandler = link !== '#' ? `onclick="window.open('${link}', '_blank')"` : '';

                        $("#displayPicture").append(`
                            <div class="d-inline-block m-2 text-center">                            
                                <img id="pic${index}" src="${thumbnail}" class="thumbnail" 
                                     style="width:150px; height:auto;" ${clickHandler} alt="실측서 이미지">                            
                                <br>
                                <button type="button" class="btn btn-danger mt-2 btn-sm" 
                                        onclick="deleteFile('${id}')">삭제</button>
                            </div>
                        `);
                    });
                }
            },
            error: function(error) {
                console.error("이미지 로드 오류:", error);
                // 오류 발생 시에도 계속 진행
            }
        });
    }

    // 파일 삭제
    window.deleteFile = function(fileId) {
        if (!confirm("정말로 삭제하시겠습니까?")) return;

        $.ajax({
            url: window.baseUrl + `/p/delpic.php?picname=${fileId}`,
            type: "POST",
            dataType: 'json',
            success: function(response) {
                console.log("삭제 응답:", response);
                if (response.status === "success") {
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: "파일이 성공적으로 삭제되었습니다.",
                            duration: 2000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast();
                    } else {
                        alert("파일이 성공적으로 삭제되었습니다.");
                    }
                    displayPictureLoad();
                } else {
                    alert(`삭제 실패: ${response.message || '알 수 없는 오류'}`);
                }
            },
            error: function(error) {
                console.error("삭제 중 오류:", error);
                alert("파일 삭제 중 문제가 발생했습니다. 콘솔을 확인하세요.");
            }
        });
    };

    // 페이지 로드 시 기존 이미지 표시
    displayPictureLoad();
});
</script>

</body>
</html>
