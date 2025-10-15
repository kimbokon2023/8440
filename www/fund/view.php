<?php
/**
 * Fund 공동자금 상세보기 페이지
 * 수입/지출 내역의 상세 정보를 표시합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$admin = $_SESSION["admin"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$year = $_REQUEST["year"] ?? '';
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';

// 변수 초기화
$proDate = '';
$writer = '';
$amount = '';
$memo = '';
$which = '';
$first_writer = '';
$update_log = '';
$aryreg = array('', '');

// 입력 검증
if (empty($num)) {
    ?>
    <script>
        alert('잘못된 접근입니다.');
        window.close();
    </script>
    <?php
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $sql = "SELECT * FROM {$DB}.fund WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        ?>
        <script>
            alert('해당 레코드를 찾을 수 없습니다.');
            window.close();
        </script>
        <?php
        exit;
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"];
        $proDate = $row["proDate"];
        $writer = $row["writer"];
        $amount = $row["amount"];
        $memo = $row["memo"];
        $which = $row["which"];
    }
    
} catch (PDOException $ex) {
    error_log("DB query error in fund/view.php: " . $ex->getMessage());
    ?>
    <script>
        alert('데이터 조회 오류가 발생했습니다.');
        window.close();
    </script>
    <?php
    exit;
}

// which 기본값 설정 및 라디오 버튼 체크
if (empty($which)) {
    $which = '2';
}

switch ($which) {
    case "1":
        $aryreg[0] = "checked";
        break;
    case "2":
        $aryreg[1] = "checked";
        break;
    default:
        break;
}

include getDocumentRoot() . '/load_header.php';
?>

<title>공동자금</title>
</head>
<body>

<form id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)">
    <input type="hidden" id="first_writer" name="first_writer" value="<?php echo htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="update_log" name="update_log" value="<?php echo htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <span class="fs-4">공동자금</span>
            </div>
            
            <div class="card-body">
                <div class="d-flex mb-1 mt-3 justify-content-start">
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="self.close();">
                        <ion-icon name="close-outline"></ion-icon> 창닫기
                    </button>
                    
                    <?php
                    if (isset($_SESSION["userid"]) && in_array($user_name, array('조경임', '김보곤', '소민지'))) {
                        $modifyUrl = 'write_form.php?' . http_build_query(array(
                            'tablename' => $tablename,
                            'mode' => 'modify',
                            'num' => $num,
                            'page' => $page,
                            'search' => $search,
                            'Bigsearch' => $Bigsearch,
                            'find' => $find,
                            'year' => $year,
                            'process' => $process,
                            'asprocess' => $asprocess,
                            'fromdate' => $fromdate,
                            'todate' => $todate,
                            'separate_date' => $separate_date
                        ));
                        
                        $newUrl = 'write_form.php?' . http_build_query(array('tablename' => $tablename));
                        
                        $deleteUrl = 'delete.php?' . http_build_query(array(
                            'tablename' => $tablename,
                            'num' => $num,
                            'page' => $page
                        ));
                    ?>
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='<?php echo htmlspecialchars($modifyUrl, ENT_QUOTES, 'UTF-8'); ?>'">
                        <ion-icon name="color-wand-outline"></ion-icon> 수정
                    </button>
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='<?php echo htmlspecialchars($newUrl, ENT_QUOTES, 'UTF-8'); ?>'">
                        <ion-icon name="create-outline"></ion-icon> 신규
                    </button>
                    <button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('<?php echo htmlspecialchars($deleteUrl, ENT_QUOTES, 'UTF-8'); ?>')">
                        <ion-icon name="trash-outline"></ion-icon> 삭제
                    </button>
                    <?php
                    }
                    ?>
                </div>
                
                <div class="d-flex mb-1 mt-3 justify-content-center">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center mt-3">
                                    <h6>
                                        구분:
                                        <span class="text-primary">수입</span>
                                        <input type="radio" <?php echo $aryreg[0]; ?> name="which" value="1">
                                        <span class="text-danger">지출</span>
                                        <input type="radio" <?php echo $aryreg[1]; ?> name="which" value="2">
                                    </h6>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="text-center">기록일</td>
                                <td>
                                    <input type="date" id="proDate" name="proDate" class="form-control text-end" style="width:100px;" value="<?php echo htmlspecialchars($proDate, ENT_QUOTES, 'UTF-8'); ?>" size="14">
                                </td>
                                <td class="text-center">작성자</td>
                                <td>
                                    <input type="text" id="writer" name="writer" value="<?php echo htmlspecialchars($writer, ENT_QUOTES, 'UTF-8'); ?>" class="form-control text-center" style="width:100px;">
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="text-center">내역</td>
                                <td colspan="3">
                                    <input type="text" id="memo" name="memo" value="<?php echo htmlspecialchars($memo, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="내역">
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="text-center">금액</td>
                                <td colspan="3">
                                    <input type="text" name="amount" id="amount" value="<?php echo htmlspecialchars($amount, ENT_QUOTES, 'UTF-8'); ?>" onkeyup="inputNumberFormat(this)" class="form-control text-end" style="width:100px;" placeholder="금액">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    'use strict';
    
    // 모든 입력 요소 비활성화 (읽기 전용)
    $("div *").find("input,textarea").prop("disabled", true);
});

/**
 * 삭제 함수
 * @param {string} href - 삭제 URL
 */
function del(href) {
    var user_name = '<?php echo addslashes($user_name); ?>';
    var writer = '<?php echo addslashes($writer); ?>';
    var admin = '<?php echo addslashes($admin); ?>';
    
    if (user_name !== writer && admin !== '1') {
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
        Swal.fire({
            title: '자료 삭제',
            text: "삭제는 신중! 정말 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete.php',
                    type: 'post',
                    data: $("#board_form").serialize(),
                    dataType: 'json',
                    success: function(data) {
                        Toastify({
                            text: "파일 삭제완료",
                            duration: 2000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                            }
                        }).showToast();
                        
                        setTimeout(function() {
                            if (window.opener && !window.opener.closed) {
                                window.opener.location.reload();
                                window.close();
                            }
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        console.error('삭제 오류:', error);
                        Swal.fire({
                            title: '오류',
                            text: '삭제 중 오류가 발생했습니다.',
                            icon: 'error',
                            confirmButtonText: '확인'
                        });
                    }
                });
            }
        });
    }
}

/**
 * Enter 키 캡처 함수 (textarea 제외)
 * @param {Event} e - 이벤트 객체
 * @return {boolean} false면 기본 동작 방지
 */
function captureReturnKey(e) {
    if (e.keyCode == 13 && e.srcElement.type != 'textarea') {
        return false;
    }
    return true;
}

/**
 * Enter 키 캡처 후 검색 실행
 * @param {Event} e - 이벤트 객체
 */
function recaptureReturnKey(e) {
    if (e.keyCode == 13) {
        if (typeof exe_search === 'function') {
            exe_search();
        }
    }
}
</script>
</body>
</html>
