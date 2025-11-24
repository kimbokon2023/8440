<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$user_id = $_SESSION["userid"] ?? '';

// 첫 화면 표시 문구
$title_message = '출하 검사서';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

// 요청 변수 안전하게 초기화
$id = $_REQUEST["id"] ?? '';
$fileorimage = $_REQUEST["fileorimage"] ?? '';
$item = $_REQUEST["item"] ?? '';
$upfilename = $_REQUEST["upfilename"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$savetitle = $_REQUEST["savetitle"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 변수 초기화 (신규 생성 모드 대응)
$parentID = '';
$subject = '';
$regist_day = '';
$check0 = '';
$check1 = '';
$check2 = '';
$check3 = '';
$check4 = '';
$check5 = '';
$check6 = '';
$check7 = '';
$check8 = '';
$check9 = '';
$writer = '';
$page = '';

// 수정/뷰 모드일 경우 데이터 조회
if ($mode == "modify" || $mode == 'view') {
    try {
        $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count < 1) {
            print "검색결과가 없습니다.<br>";
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            include '_row.php';
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
}

// 초기 프로그램은 $num사용 이후 $id로 수정중임
$id = $num;

$todate = date("Y-m-d");   // 현재일자 변수지정
$nowday = date("Y-m-d");   // 현재일자 변수지정

$img_arr = array();
$questionstep_arr = array();
$check_arr = array();

// 체크리스트 배열 및 이미지 URL 생성 (로컬/서버 환경 자동 대응)
for ($i = 0; $i < 10; $i++) {
    $checktmp = 'check' . (string)($i);
    array_push($check_arr, $$checktmp);
    array_push($img_arr, $base_url . '/p_inspection/img/' . ($i + 1) . '.jpg');
}

if (!is_string_valid($writer))
    $writer = $user_name;

if (!NullCheckDate($regist_day))
    $regist_day = $todate;

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES) ?></title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100% - 1rem) !important;
            max-width: calc(100% - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .card-body {
            padding: 0.75rem 0.5rem !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 제목 최적화 */
        h3 {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* 테이블을 카드 형식으로 변환 */
        table.table-bordered {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        table.table-bordered tbody tr {
            display: block !important;
            width: 100% !important;
            margin-bottom: 1rem !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            padding: 0.75rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            background-color: #fff !important;
            box-sizing: border-box !important;
        }
        
        table.table-bordered tbody tr td {
            display: block !important;
            width: 100% !important;
            padding: 0.5rem 0 !important;
            border: none !important;
            text-align: left !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
        }
        
        table.table-bordered tbody tr td::before {
            content: attr(data-label) !important;
            font-weight: bold !important;
            display: inline-block !important;
            width: 30% !important;
            min-width: 80px !important;
            margin-right: 0.5rem !important;
            color: #495057 !important;
            vertical-align: top !important;
        }
        
        table.table-bordered tbody tr td[colspan] {
            display: block !important;
        }
        
        table.table-bordered tbody tr td[rowspan] {
            display: block !important;
        }
        
        /* 입력 필드 최적화 */
        .form-control {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 1rem !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
        }
        
        input[type="text"],
        input[type="date"] {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        #subject {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* 버튼 최적화 */
        .btn {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.align-items-center,
        .d-flex.justify-content-start {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        /* 체크리스트 카드 최적화 */
        .row.d-flex.mt-3.mb-1.justify-content-center .card {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.5rem 0 !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center .card .table {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center .card .table tbody tr {
            display: flex !important;
            flex-direction: column !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center .card .table tbody tr td {
            width: 100% !important;
            display: block !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center .card .table tbody tr td table tbody tr {
            display: flex !important;
            flex-direction: column !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center .card .table tbody tr td table tbody tr td {
            width: 100% !important;
            display: block !important;
        }
        
        /* 이미지 최적화 */
        img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline !important;
            overflow: visible !important;
        }
        
        /* Footer 최적화 */
        .card-footer {
            padding: 1rem 0.5rem !important;
        }
        
        .card-footer h2,
        .card-footer h3 {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
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
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
        }
        
        .modal-body {
            padding: 0.75rem 0.5rem !important;
            overflow-y: auto !important;
            flex: 1 1 auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-body img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
        }
        
        /* SweetAlert2 모달 최적화 */
        .swal2-popup {
            width: 90% !important;
            max-width: 90% !important;
            padding: 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .swal2-title {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-content {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-actions {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .swal2-confirm,
        .swal2-cancel {
            width: 100% !important;
            margin: 0 !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
    }
    
    /* PC 환경 버튼 간격 최적화 */
    @media (min-width: 769px) {
        .d-flex.justify-content-center .btn,
        .d-flex.justify-content-start .btn {
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }
    }
</style>

</head>

<body>

<?php include includePath("common/modal.php"); ?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">

    <!-- 전달함수 설정 input hidden -->
    <input type="hidden" id="id" name="id" value="<?= $id ?>">
    <input type="hidden" id="num" name="num" value="<?= $num ?>">
    <input type="hidden" id="page" name="page" value="<?= $page ?>">
    <input type="hidden" id="parentID" name="parentID" value="<?= $parentID ?>">
    <input type="hidden" id="fileorimage" name="fileorimage" value="<?= $fileorimage ?>">
    <input type="hidden" id="item" name="item" value="<?= $item ?>">
    <input type="hidden" id="upfilename" name="upfilename" value="<?= $upfilename ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
    <input type="hidden" id="savetitle" name="savetitle" value="<?= $savetitle ?>">
    <input type="hidden" id="pInput" name="pInput" value="<?= $pInput ?>">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?= $timekey ?>">  <!-- 신규데이터 작성시 parentID key값으로 사용 -->

    <div class="container">
        <div class="card mt-2 mb-4">
            <div class="card-body">
                
                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <h3><?= $title_message ?></h3>
                </div>

                <div class="row d-flex mt-3 mb-1 justify-content-center">
                    <table class="table table-bordered" style="width:80%;">
                        <tbody>
                            <tr>
                                <td class="text-center align-middle" data-label="검사자">검사자</td>
                                <td class="text-center align-middle" data-label="검사자 입력">
                                    <input type="text" id="writer" class="form-control" name="writer" value="<?= htmlspecialchars($writer, ENT_QUOTES) ?>">
                                </td>
                                <td class="text-center align-middle" data-label="검사일">검사일</td>
                                <td class="text-center align-middle" data-label="검사일 입력">
                                    <input type="date" id="regist_day" class="form-control" name="regist_day" value="<?= htmlspecialchars($regist_day, ENT_QUOTES) ?>">
                                </td>
                                <td class="text-center align-middle" data-label="점검 여부">
                                    <div class="d-flex align-items-center">
                                        <span id="allcheck">10개 체크리스트 점검 여부 &nbsp;</span>
                                        <input id="done_check" class="form-control" name="done_check" type="text" style="width:35px;" value="<?= htmlspecialchars($done_check ?? '', ENT_QUOTES) ?>">&nbsp;
                                    </div>
                                </td>
                                <td class="text-center align-middle" data-label="완료 상태">
                                    <span id="check_complete" style="font-size:12px;color:blue; display:none;">점검완료</span>
                                </td>
                                <td rowspan="2" class="text-center align-middle" data-label="작업">
                                    <div class="d-flex flex-column">
                                        <button class="btn btn-dark btn-sm mb-1" onclick="self.close();">&times; 닫기</button>
                                        <button type="button" id="saveBtn" class="btn btn-dark btn-sm"><i class="bi bi-floppy-fill"></i> 저장</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle" data-label="현장명">현장명</td>
                                <td colspan="5" class="text-center align-middle" data-label="현장명 입력">
                                    <div class="d-flex justify-content-start">
                                        <input id="subject" class="form-control text-start" name="subject" type="text" onkeypress="enterCheck(event);" style="width:400px;" value="<?= htmlspecialchars($subject, ENT_QUOTES) ?>">
                                        <button type="button" class="btn btn-dark btn-sm mx-1" onclick="Choice_search();"><i class="bi bi-search"></i> jamb</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row d-flex mt-3 mb-1 justify-content-center">
                    <?php for ($i = 0; $i < 10; $i++): ?>
                        <?php $checktmp = 'check' . (string)($i); ?>
                        <div class="card mt-1 mb-1 justify-content-center">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">
                                    <table class="table table-bordered" style="width:70%;">
                                        <tbody>
                                            <tr>
                                                <td class="text-center align-middle" style="width:40%;">
                                                    <img id="ckname<?= $i ?>" src="<?= $img_arr[$i] ?>" style="width:70%;">
                                                </td>
                                                <td class="text-center align-middle" style="width:30%;">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <input type="date" id="check<?= $i ?>" name="check<?= $i ?>" class="form-control">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <span id="writer_text<?= $i ?>" class="form-control" style="display:none;">점검자 : <?= $writer ?></span>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <button type="button" id="ckbtn<?= $i ?>" class="btn btn-dark btn-sm">미점검</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- footer -->
                <div class="card-footer border-0 px-5 py-4" style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                    <h2 class="d-flex align-items-center justify-content-center text-white mb-0">
                        안전을 최우선으로 생각하는 미래기업
                    </h2>
                    <h3 class="d-flex align-items-center justify-content-center text-center text-white mb-0">
                        고객만족 품질경영
                    </h3>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>

<script>
$(document).ready(function() {

    var check_arr = <?php echo json_encode($check_arr); ?>;

    // 기존 체크리스트 값 복원
    for (var i = 0; i < 10; i++) {
        if (check_arr[i] !== '' && check_arr[i] !== '0000-00-00') {
            $('#check' + i).val(check_arr[i]);
            $('#writer_text' + i).show();
            $('#ckbtn' + i).text('점검');
        }
    }

    // check0부터 check9까지의 버튼에 클릭 이벤트를 추가합니다.
    for (let i = 0; i < 10; i++) {
        $('#ckbtn' + i).click(function() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
            const day = ('0' + currentDate.getDate()).slice(-2);
            const formattedDate = `${year}-${month}-${day}`;

            const $checkInput = $('#check' + i);
            const $button = $(this);
            const isChecked = $checkInput.val() !== '';

            if (isChecked) {
                // 이미 점검된 상태일 경우, 날짜를 지우고 미점검 상태로 되돌립니다.
                $checkInput.val('');
                $button.text('미점검');
                $('#writer_text' + i).hide();
            } else {
                // 미점검 상태일 경우, 현재 날짜를 설정하고 점검 상태로 바꿉니다.
                $checkInput.val(formattedDate);
                $button.text('점검');
                $('#writer_text' + i).show();
            }
            
            // updateDoneCheck 함수 호출 (debounced)
            debouncedUpdateDoneCheck();
        });
    }

    // updateDoneCheck 함수를 debounce 처리하여 너무 자주 호출되지 않도록 합니다.
    var updateDoneCheckTimeout;
    function debouncedUpdateDoneCheck() {
        clearTimeout(updateDoneCheckTimeout);
        updateDoneCheckTimeout = setTimeout(function() {
            updateDoneCheck();
        }, 300);
    }

    // 화면 로딩 후 체크된 날짜의 개수를 업데이트합니다.
    updateDoneCheck();

    // 날짜가 선택되면 updateDoneCheck 함수를 호출합니다. (중복 바인딩 방지)
    $('input[type="date"]').off('change.updateDoneCheck').on('change.updateDoneCheck', function() {
        debouncedUpdateDoneCheck();
    });

    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    // 하단복사 버튼
    $("#closeBtn1").click(function() {
        $("#closeBtn").click();
    });

    $("#closeBtn").click(function() {    // 저장하고 창닫기
        opener.location.reload();
        self.close();
    });

    // 자료의 삽입/수정하는 모듈
    $("#saveBtn").click(function() {    // 저장하고 창닫기
        // 폼데이터 전송시 사용함 Get form
        var form = $('#board_form')[0];
        // Create an FormData object
        var data = new FormData(form);

        tmp = '파일을 저장중입니다. 잠시만 기다려주세요.';
        $('#alertmsg').html(tmp);
        $('#myModal').modal('show');

        $.ajax({
            enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "./insert.php",
            type: "post",
            data: data,
            dataType: "json",
            success: function(data) {
                if (data && data.num) {
                    $('#num').val(data.num);
                    setTimeout(function() {
                        $('#myModal').modal('hide');
                        if (window.opener && !window.opener.closed) {
                            window.opener.location.reload();
                        }
                    }, 1000);
                } else {
                    $('#myModal').modal('hide');
                    alert('저장 중 오류가 발생했습니다.');
                }
            },
            error: function(jqxhr, status, error) {
                $('#myModal').modal('hide');
                var errorMsg = '저장 중 오류가 발생했습니다.';
                console.log('AJAX Error:', {
                    status: status,
                    error: error,
                    statusCode: jqxhr.status,
                    responseText: jqxhr.responseText
                });
                
                if (jqxhr.responseText) {
                    try {
                        var errorData = JSON.parse(jqxhr.responseText);
                        if (errorData.error) {
                            errorMsg = errorData.error;
                        } else if (errorData.message) {
                            errorMsg = errorData.message;
                        }
                        if (errorData.file && errorData.line) {
                            errorMsg += '\n파일: ' + errorData.file + '\n줄: ' + errorData.line;
                        }
                    } catch (e) {
                        // JSON 파싱 실패 시 responseText를 직접 표시
                        if (jqxhr.responseText.length < 500) {
                            errorMsg = '서버 응답: ' + jqxhr.responseText;
                        }
                    }
                }
                alert(errorMsg);
            }
        });
    });

    // 텍스트 문자를 클릭하면 전체 체크하도록 만든다.
    $("#allcheck").click(function() {
        for (var i = 0; i < 10; i++) {
            $("#check" + i).val(new Date().toISOString().substring(0, 10));
            $("#ckbtn" + i).text("점검완료");
            $("#writer_text" + i).show();
        }
        $("#check_complete").show();
        debouncedUpdateDoneCheck();
    });

    // 목록
    $("#listBtn").click(function() {
        var page = $("#page").val();
        location.href = 'list.php?page=' + page;
    });

}); // end of ready document

// 마지막 문자 제거하는 함수
function deleteLastchar(str) {
    return str = str.substr(0, str.length - 1);
}

function enterCheck(e) {
    if (e.key === 'Enter') {
        search_jamb();  // 잠 현장검색
    }
}

function Choice_search() {
    search_jamb();  // 잠 현장검색
}

function search_jamb() {
    var ua = window.navigator.userAgent;
    var postData;
    var text1 = document.getElementById("subject").value;

    if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
        postData = encodeURI(text1);
    } else {
        postData = text1;
    }

    popupCenter("./search.php?mode=search&search=" + postData, '잠현장 검색', 1200, 800);
}

function updateDoneCheck() {
    var doneCheck = 0;

    for (var i = 0; i < 10; i++) {
        var dateValue = $('#check' + i).val();
        if (dateValue !== '' && dateValue !== '0000-00-00' && dateValue !== null) {
            doneCheck++;
        }
    }

    $('#done_check').val(doneCheck);
    if (doneCheck === 10) {
        $('#check_complete').show();
    } else {
        $('#check_complete').hide();
    }
}
</script>
