<?php
/**
 * Fund 공동자금 작성/수정 폼 페이지
 * 수입/지출 내역을 작성하거나 수정합니다.
 */

// 로컬과 서버 호환성을 위한 설정
require_once __DIR__ . '/../bootstrap.php';

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

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$callback = $_REQUEST["callback"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$which = $_REQUEST["which"] ?? '2';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$regist_state = $_REQUEST["regist_state"] ?? '1';
$year = $_REQUEST["year"] ?? '';

// 변수 초기화
$proDate = '';
$writer = '';
$amount = '';
$memo = '';
$first_writer = '';
$update_log = '';
$aryreg = array('', '');
$aryitem = array();

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    if (empty($num)) {
        ?>
        <script>
            alert('잘못된 접근입니다.');
            window.close();
        </script>
        <?php
        exit;
    }
    
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
        error_log("DB query error in fund/write_form.php: " . $ex->getMessage());
        ?>
        <script>
            alert('데이터 조회 오류가 발생했습니다.');
            window.close();
        </script>
        <?php
        exit;
    }
} else {
    // 신규 작성 모드 - 변수 초기화
    $proDate = date("Y-m-d");
    $writer = $user_name;
    $amount = "";
    $memo = "";
    $which = "2";
}

// which 라디오 버튼 체크 설정
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

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body,
        .card-header {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .card-header.text-center {
            padding: 0.75rem !important;
        }
        
        .card-header.text-center .d-flex.justify-content-between.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        .card-header.text-center .fs-5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            width: 100% !important;
        }
        
        .card-header.text-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.mb-1.mt-3.justify-content-start {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-1.mt-3.justify-content-start button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 테이블 최적화 */
        .d-flex.mb-1.mt-3.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.5rem !important;
        }
        
        .table-bordered {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 0.875rem !important;
        }
        
        .table-bordered td {
            padding: 0.5rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .table-bordered input {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 0.875rem !important;
        }
        
        /* 라디오 버튼 최적화 */
        .table-bordered input[type="radio"] {
            width: auto !important;
            max-width: auto !important;
            margin: 0.25rem !important;
        }
        
        .table-bordered h6 {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>

</head>
<body>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="first_writer" name="first_writer" value="<?php echo htmlspecialchars($first_writer, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="update_log" name="update_log" value="<?php echo htmlspecialchars($update_log, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="mode" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5">공동자금</span>
                    <button type="button" class="btn btn-dark btn-sm" onclick="self.close();">
                        &times; 닫기
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="d-flex mb-1 mt-3 justify-content-start">
                    <button type="button" id="saveBtn" class="btn btn-dark btn-sm me-5">
                        <i class="bi bi-floppy-fill"></i> 저장
                    </button>
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
    
    /**
     * 저장 버튼 클릭 이벤트
     */
    $("#saveBtn").click(function() {
        Fninsert();
    });
});

/**
 * 자료 삽입/수정 함수
 */
function Fninsert() {
    var form = $('#board_form')[0];
    var data = new FormData(form);
    
    $.ajax({
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        url: "insert.php",
        type: "post",
        data: data,
        dataType: "json",
        success: function(data) {
            console.log(data);
            
            Toastify({
                text: "저장완료",
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
                }
                
                var num = data["num"];
                if (num) {
                    location.href = 'view.php?num=' + num;
                } else {
                    window.close();
                }
            }, 1000);
        },
        error: function(jqxhr, status, error) {
            console.error(jqxhr, status, error);
            Swal.fire({
                title: '오류',
                text: '저장 중 오류가 발생했습니다.',
                icon: 'error',
                confirmButtonText: '확인'
            });
        }
    });
}

/**
 * 숫자 포맷팅 함수
 * @param {HTMLInputElement} obj - input 요소
 */
function inputNumberFormat(obj) {
    if (typeof comma === 'function' && typeof uncomma === 'function') {
        obj.value = comma(uncomma(obj.value));
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
