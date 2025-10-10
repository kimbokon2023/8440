<?php
// 세션 시작
if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화
$DB = isset($_SESSION["DB"]) ? $_SESSION["DB"] : "";
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

// 페이지 제목
$title_message = '조명천장 검사서';

// 헤더 로드
include getDocumentRoot() . '/load_header.php';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// REQUEST 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

// 검사 일자 초기화
$inspectiondate = date("Y-m-d", time());

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 변수 초기화
$item_file_0 = "";
$item_file_1 = "";
$copied_file_0 = "";
$copied_file_1 = "";
$checkstep = "";
$workplacename = "";
$secondord = "";
$address = "";
$worker = "";
$chargedman = "";
$chargedmantel = "";
$type = "";
$inseung = "";
$su = 0;
$bon_su = 0;
$lc_su = 0;
$etc_su = 0;
$air_su = 0;
$text = '조명천장';

// 데이터 조회
try {
    $sql = "select * from mirae8440.ceiling where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        print "검색결과가 없습니다.<br>";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        // 파일 정보
        $item_file_0 = isset($row["file_name_0"]) ? $row["file_name_0"] : "";
        $item_file_1 = isset($row["file_name_1"]) ? $row["file_name_1"] : "";
        $copied_file_0 = "../uploads/" . (isset($row["file_copied_0"]) ? $row["file_copied_0"] : "");
        $copied_file_1 = "../uploads/" . (isset($row["file_copied_1"]) ? $row["file_copied_1"] : "");
        
        // 기본 정보
        $num = $row["num"];
        $checkstep = $row["checkstep"];
        $workplacename = $row["workplacename"];
        $secondord = $row["secondord"];
        $address = $row["address"];
        $worker = $row["worker"];
        $chargedman = $row["chargedman"];
        $chargedmantel = $row["chargedmantel"];
        
        // 제품 정보
        $type = $row["type"];
        $inseung = $row["inseung"];
        $su = (int)$row["su"];
        $bon_su = (int)$row["bon_su"];
        $lc_su = (int)$row["lc_su"];
        $etc_su = (int)$row["etc_su"];
        $air_su = (int)$row["air_su"];
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

?>

<title><?=$title_message?></title>

</head>
<body>

<form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>" enctype="multipart/form-data">
    <input type="hidden" id="num" name="num" value="<?=$num?>">
    
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-center mb-1 mt-1">
                    <h4 class="card-title"><?=$title_message?></h4>
                </div>
            </div>
            
            <div class="card-body">
                <div class="d-flex justify-content-start mb-2">
                    <button onclick="window.close();" type="button" class="btn btn-dark btn-sm me-1">
                        <ion-icon name="close-outline"></ion-icon> 창닫기
                    </button>
                    <button type="button" id="printBtn" class="btn btn-success btn-sm">
                        <ion-icon name="print-outline"></ion-icon> 인쇄
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:100px;">검사일시 :</span>
                            <input type="date" class="form-control" id="inspectiondate" name="inspectiondate" value="<?=$inspectiondate?>">
                        </div>
                    </div>
                    <div class="col-lg-7 mb-1"></div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:100px;">현장명 :</span>
                            <input type="text" class="form-control" name="workplacename" value="<?=$workplacename?>">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1"></div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:100px;">현장주소 :</span>
                            <input type="text" class="form-control" name="address" value="<?=$address?>">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1"></div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:100px;">발주처 :</span>
                            <input type="text" class="form-control" name="secondord" value="<?=$secondord?>">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1"></div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 mb-1">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:100px;">품목 :</span>
                            <input type="text" class="form-control" name="text" value="<?=$text?>">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// 숫자 포맷팅 함수
function inputNumberFormat(obj) {
    obj.value = comma(uncomma(obj.value));
}

function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

// 엔터키 이벤트 핸들러
function Enter_Check() {
    // 엔터키의 코드는 13
    if (event.keyCode == 13) {
        exe_search();  // 담당자 연락처 찾기
    }
}

function Enter_firstCheck() {
    if (event.keyCode == 13) {
        exe_firstordman();  // 원청 담당자 전번 가져오기
    }
}

function Enter_chargedman_Check() {
    if (event.keyCode == 13) {
        exe_chargedman();  // 현장소장 전번 가져오기
    }
}

// 발주처 담당자 검색 함수
function exe_search() {
    var tmp = $('#secondordman').val();
    switch (tmp) {
        case '김관':
            $("#secondordmantel").val("010-2648-0225");
            $("#secondordman").val("김관부장");
            $("#secondord").val("한산");
            break;
    }
}

// 원청 담당자 검색 함수
function exe_firstordman() {
    var tmp = $('#firstordman').val();
    switch (tmp) {
        case '고범섭':
            $("#firstordman").val("고범섭소장");
            $("#firstordmantel").val("010-6774-6211");
            $("#firstord").val("오티스");
            $("#secondord").val("우성");
            break;
    }
}

// 현장소장 검색 함수
function exe_chargedman() {
    // 필요시 구현
}

// 엔터키 제어 함수
function captureReturnKey(e) {
    if (e.keyCode == 13 && e.srcElement.type != 'textarea') {
        return false;
    }
}

function recaptureReturnKey(e) {
    if (e.keyCode == 13) {
        exe_search();
    }
}

// 문서 준비 이벤트
$(document).ready(function() {
    // 인쇄 버튼 클릭 이벤트
    $("#printBtn").click(function() {
        const inspectiondate = $("#inspectiondate").val();
        const num = $("#num").val();
        
        popupCenter('inspection_print.php?num=' + num + '&inspectiondate=' + inspectiondate, '검사서', 1600, 950);
    });
});
</script>

</body>
</html>
