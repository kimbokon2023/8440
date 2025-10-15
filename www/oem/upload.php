<!DOCTYPE html>
<?php
/**
 * 출고증 자료 입력화면
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

/**
 * 날짜 변환 함수
 */
function trans_date($tdate) {
    if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
        $tdate = date("Y-m-d", strtotime($tdate));
    } else {
        $tdate = "";
    }
    return $tdate;
}

// 요청 변수 초기화 (?? '' 형태)
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';

require_once("../lib/mydb.php");
$pdo = db_connect();

// 변수 초기화
$checkstep = '';
$workplacename = '';
$address = '';
$firstord = '';
$firstordman = '';
$firstordmantel = '';
$secondord = '';
$secondordman = '';
$secondordmantel = '';
$chargedman = '';
$chargedmantel = '';
$orderday = '';
$measureday = '';
$drawday = '';
$deadline = '';
$workday = '';
$worker = '';
$endworkday = '';
$material1 = '';
$material2 = '';
$material3 = '';
$material4 = '';
$material5 = '';
$material6 = '';
$widejamb = '';
$normaljamb = '';
$smalljamb = '';
$memo = '';
$regist_day = '';
$update_day = '';
$delivery = '';
$delicar = '';
$delicompany = '';
$delipay = '';
$delimethod = '';
$item_file_0 = '';
$item_file_1 = '';
$copied_file_0 = '';
$copied_file_1 = '';

if ($mode == "modify") {
    try {
        $sql = "SELECT * FROM mirae8440.work WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count < 1) {
            echo "<div class='alert alert-warning'>검색결과가 없습니다.</div>";
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            
            $item_file_0 = $row["file_name_0"];
            $item_file_1 = $row["file_name_1"];
            $copied_file_0 = "../uploads/" . $row["file_copied_0"];
            $copied_file_1 = "../uploads/" . $row["file_copied_1"];
            
            $checkstep = $row["checkstep"];
            $workplacename = $row["workplacename"];
            $address = $row["address"];
            $firstord = $row["firstord"];
            $firstordman = $row["firstordman"];
            $firstordmantel = $row["firstordmantel"];
            $secondord = $row["secondord"];
            $secondordman = $row["secondordman"];
            $secondordmantel = $row["secondordmantel"];
            $chargedman = $row["chargedman"];
            $orderday = $row["orderday"];
            $measureday = $row["measureday"];
            $drawday = $row["drawday"];
            $deadline = $row["deadline"];
            $workday = $row["workday"];
            $worker = $row["worker"];
            $endworkday = $row["endworkday"];
            $material1 = $row["material1"];
            $material2 = $row["material2"];
            $material3 = $row["material3"];
            $material4 = $row["material4"];
            $material5 = $row["material5"];
            $material6 = $row["material6"];
            $widejamb = $row["widejamb"];
            $normaljamb = $row["normaljamb"];
            $smalljamb = $row["smalljamb"];
            $memo = $row["memo"];
            $regist_day = $row["regist_day"];
            $update_day = $row["update_day"];
            $delivery = $row["delivery"];
            $delicar = $row["delicar"];
            $delicompany = $row["delicompany"];
            $delipay = $row["delipay"];
            $delimethod = $row["delimethod"];
            
            // 날짜 변환
            if ($orderday != "0000-00-00" && $orderday != "1970-01-01") {
                $orderday = date("Y-m-d", strtotime($orderday));
            } else {
                $orderday = "";
            }
            
            if ($measureday != "0000-00-00" && $measureday != "1970-01-01") {
                $measureday = date("Y-m-d", strtotime($measureday));
            } else {
                $measureday = "";
            }
            
            if ($drawday != "0000-00-00" && $drawday != "1970-01-01") {
                $drawday = date("Y-m-d", strtotime($drawday));
            } else {
                $drawday = "";
            }
            
            if ($deadline != "0000-00-00" && $deadline != "1970-01-01") {
                $deadline = date("Y-m-d", strtotime($deadline));
            } else {
                $deadline = "";
            }
            
            if ($workday != "0000-00-00" && $workday != "1970-01-01") {
                $workday = date("Y-m-d", strtotime($workday));
            } else {
                $workday = "";
            }
            
            if ($endworkday != "0000-00-00" && $endworkday != "1970-01-01") {
                $endworkday = date("Y-m-d", strtotime($endworkday));
            } else {
                $endworkday = "";
            }
        }
    } catch (PDOException $ex) {
        error_log("출고증 데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
        echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
    }
} else {
    // 신규 등록 모드
    $orderday = date("Y-m-d");
}

// 출고일 생성
$outputdate = date("Y-m-d", time());
if ($outputdate != "") {
    $week = ["(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)"];
    $outputdate = $outputdate . $week[date('w', strtotime($outputdate))];
}

// 배송방식 라디오 버튼 배열
$aryreg = [];
if ($delivery == "") {
    $delivery = "직접배송(수령)";
}
switch ($delivery) {
    case "직접배송(수령)":
        $aryreg[0] = "checked";
        break;
    case "상차(선불)":
        $aryreg[1] = "checked";
        break;
    case "상차(착불)":
        $aryreg[2] = "checked";
        break;
    case "경동화물(지점)":
        $aryreg[3] = "checked";
        break;
    case "경동택배":
        $aryreg[4] = "checked";
        break;
    default:
        break;
}
?>

<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 통합정보시스템</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/work.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
</head>
<body>

<input type="button" value="save csv to mysql" onclick="Process();" />
<div class="clear"></div>
<div id="spreadsheet"></div>

<div id="wrap">
    <?php if ($mode == "modify") { ?>
        <form name="board_form" method="post" action="insert.php?mode=modify&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <?php } else { ?>
        <form name="board_form" method="post" action="insert.php?mode=not" enctype="multipart/form-data">
    <?php } ?>
    
    <div id="content">
        <div id="work_col2">
            <div id="estimate_text2">
                <!-- 공사진행현황 -->
                <div class="sero1"><img src="../img/work_title.png" alt="Title"></div>
                
                <div id="write_button_renew">
                    <input type="image" src="../img/ok.png" alt="Submit">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="list.php?&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"><img src="../img/list.png" alt="List"></a>
                </div>
                <br>
                
                <div id="title1">
                    요청사항 선택 : &nbsp;
                    <?php
                    if ($checkstep == null) {
                        $checkstep = "없음";
                    }
                    
                    if ($checkstep == "없음") {
                    ?>
                        없음 <input type="radio" checked name="checkstep" value="없음">
                        &nbsp; 방문요청<input type="radio" name="checkstep" value="방문요청">
                        &nbsp; 실측요청<input type="radio" name="checkstep" value="실측요청">
                        &nbsp; 발주요청<input type="radio" name="checkstep" value="발주요청">
                    <?php } ?>
                    
                    <?php if ($checkstep == "방문요청") { ?>
                        없음 <input type="radio" name="checkstep" value="없음">
                        &nbsp; 방문요청<input type="radio" checked name="checkstep" value="방문요청">
                        &nbsp; 실측요청<input type="radio" name="checkstep" value="실측요청">
                        &nbsp; 발주요청<input type="radio" name="checkstep" value="발주요청">
                    <?php } ?>
                    
                    <?php if ($checkstep == "실측요청") { ?>
                        없음 <input type="radio" name="checkstep" value="없음">
                        &nbsp; 방문요청<input type="radio" name="checkstep" value="방문요청">
                        &nbsp; 실측요청<input type="radio" checked name="checkstep" value="실측요청">
                        &nbsp; 발주요청<input type="radio" name="checkstep" value="발주요청">
                    <?php } ?>
                    
                    <?php if ($checkstep == "발주요청") { ?>
                        없음 <input type="radio" name="checkstep" value="없음">
                        &nbsp; 방문요청<input type="radio" name="checkstep" value="방문요청">
                        &nbsp; 실측요청<input type="radio" name="checkstep" value="실측요청">
                        &nbsp; 발주요청<input type="radio" checked name="checkstep" value="발주요청">
                    <?php } ?>
                </div>
                
                <br>
                
                <div class="sero1">현장명 :</div>
                <div class="sero2">
                    <input type="text" id="workplacename" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장명" required>
                </div>
                <div class="clear"></div>
                
                <div class="sero1">현장주소 :</div>
                <div class="sero2">
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>" size="50" placeholder="현장주소">
                </div>
                <div class="clear"></div>
                
                <div class="sero1">원  청 :</div>
                <div class="sero2">
                    <input type="text" id="firstord" name="firstord" value="<?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="원청">
                </div>
                <div class="sero1">담당 :</div>
                <div class="sero2">
                    <input type="text" id="firstordman" name="firstordman" value="<?= htmlspecialchars($firstordman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="원청담당">
                </div>
                <div class="sero1">연락처 :</div>
                <div class="sero2">
                    <input type="text" id="firstordmantel" name="firstordmantel" value="<?= htmlspecialchars($firstordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호">
                </div>
                <div class="clear"></div>
                
                <div class="sero1">발주처 :</div>
                <div class="sero2">
                    <input type="text" id="secondord" name="secondord" value="<?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="발주처">
                </div>
                <div class="sero1">담당 :</div>
                <div class="sero2">
                    <input type="text" id="secondordman" name="secondordman" value="<?= htmlspecialchars($secondordman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="발주처 담당자">
                </div>
                <div class="sero1">연락처 :</div>
                <div class="sero2">
                    <input type="text" id="secondordmantel" name="secondordmantel" value="<?= htmlspecialchars($secondordmantel, ENT_QUOTES, 'UTF-8') ?>" size="14" placeholder="연락번호">
                </div>
                <div class="clear"></div>
                
                <div class="sero1">현장소장 :</div>
                <div class="sero2">
                    <input type="text" id="chargedman" name="chargedman" value="<?= htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="현장담당자">
                </div>
                <div class="sero1">연락처 :</div>
                <div class="sero2">
                    <input type="text" id="chargedmantel" name="chargedmantel" value="<?= htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="현장담당전화">
                </div>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div class="sero1">발주접수일:</div>
                <div class="sero2">
                    <input type="text" name="orderday" id="orderday" value="<?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="발주접수일">
                </div>
                <div class="sero6" style="text-align:right; width:50px;">실측일 :</div>
                <div class="sero2">
                    <input type="text" name="measureday" id="measureday" value="<?= htmlspecialchars($measureday, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="실측일">
                </div>
                <div class="sero7" style="width:90px;">도면설계완료일 :</div>
                <div class="sero2">
                    <input type="text" name="drawday" id="drawday" value="<?= htmlspecialchars($drawday, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="도면설계일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')" onkeyup="date_mask(this.form.name, this.name)"/>
                </div>
                <div class="sero7" style="width:90px; color:red; font-weight:bold; text-align:right;">납기일 :</div>
                <div class="sero2">
                    <input type="text" name="deadline" id="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="납기일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')" onkeyup="date_mask(this.form.name, this.name)"/>
                </div>
                <div class="clear"></div>
                
                <div class="sero1">시공투입일:</div>
                <div class="sero2">
                    <input type="text" name="workday" id="workday" value="<?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="투입일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')" onkeyup="date_mask(this.form.name, this.name)"/>
                </div>
                <div class="sero6">미래기업 시공팀 :</div>
                <div class="sero2">
                    <input type="text" name="worker" id="worker" value="<?= htmlspecialchars($worker, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="시공팀">
                </div>
                <div class="sero7">시공완료일 :</div>
                <div class="sero2">
                    <input type="text" name="endworkday" id="endworkday" value="<?= htmlspecialchars($endworkday, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="시공완료일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')" onkeyup="date_mask(this.form.name, this.name)"/>
                </div>
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div id="delivery_col">배송방식 :
                    <input type="radio" <?= $aryreg[0] ?? '' ?> name="delivery" value="직접배송(수령)"> 직접배송(수령) &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[1] ?? '' ?> name="delivery" value="상차(선불)"> 상차(선불) &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[2] ?? '' ?> name="delivery" value="상차(착불)"> 상차(착불) &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[3] ?? '' ?> name="delivery" value="경동화물(지점)"> 경동화물(지점) &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[4] ?? '' ?> name="delivery" value="경동택배"> 경동택배 &nbsp;&nbsp;&nbsp;
                </div>
                
                <?php
                $aryreg = [];
                if ($delicar == "") {
                    $delicar = "경동화물";
                }
                switch ($delicar) {
                    case "경동화물":
                        $aryreg[0] = "checked";
                        break;
                    case "라보":
                        $aryreg[1] = "checked";
                        break;
                    case "다마스":
                        $aryreg[2] = "checked";
                        break;
                    case "1t":
                        $aryreg[3] = "checked";
                        break;
                    case "1.2t":
                        $aryreg[4] = "checked";
                        break;
                    case "2.5t":
                        $aryreg[5] = "checked";
                        break;
                    case "5t":
                        $aryreg[6] = "checked";
                        break;
                    case "9t":
                        $aryreg[7] = "checked";
                        break;
                    default:
                        break;
                }
                ?>
                
                <div id="delivery_col" style="color:blue;">화물차종 :
                    <input type="radio" <?= $aryreg[0] ?? '' ?> name="delicar" value="없음"> 없음 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[1] ?? '' ?> name="delicar" value="라보"> 라보 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[2] ?? '' ?> name="delicar" value="다마스"> 다마스 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[3] ?? '' ?> name="delicar" value="1t"> 1t &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[4] ?? '' ?> name="delicar" value="1.2t"> 1.2t &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[5] ?? '' ?> name="delicar" value="2.5t"> 2.5t &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[6] ?? '' ?> name="delicar" value="5t"> 5t &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[7] ?? '' ?> name="delicar" value="9t"> 9t &nbsp;&nbsp;&nbsp;
                </div>
                
                <?php
                $aryreg = [];
                if ($delicompany == "") {
                    $delicompany = "없음";
                }
                switch ($delicompany) {
                    case "없음":
                        $aryreg[0] = "checked";
                        break;
                    case "스카이":
                        $aryreg[1] = "checked";
                        break;
                    case "김재명":
                        $aryreg[2] = "checked";
                        break;
                    case "유광식":
                        $aryreg[3] = "checked";
                        break;
                    case "천안":
                        $aryreg[4] = "checked";
                        break;
                    case "전국특송(합바)":
                        $aryreg[5] = "checked";
                        break;
                    default:
                        break;
                }
                ?>
                
                <div id="delivery_col" style="color:brown;">운송업체 :
                    <input type="radio" <?= $aryreg[0] ?? '' ?> name="delicompany" value="없음"> 없음 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[1] ?? '' ?> name="delicompany" value="스카이"> 스카이 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[2] ?? '' ?> name="delicompany" value="김재명"> 김재명 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[3] ?? '' ?> name="delicompany" value="유광식"> 유광식 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[4] ?? '' ?> name="delicompany" value="천안"> 천안 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[5] ?? '' ?> name="delicompany" value="전국특송(합바)"> 전국특송(합바) &nbsp;&nbsp;&nbsp;
                </div>
                
                <?php
                $aryreg = [];
                if ($delimethod == "") {
                    $delimethod = "본사자체";
                }
                switch ($delimethod) {
                    case "본사자체":
                        $aryreg[0] = "checked";
                        break;
                    case "추후청구":
                        $aryreg[1] = "checked";
                        break;
                    default:
                        break;
                }
                ?>
                
                <div id="delivery_col" style="color:red;">운임(있을시 기록) :
                    <input type="text" name="delipay" value="<?= htmlspecialchars($delipay, ENT_QUOTES, 'UTF-8') ?>" placeholder="운임금액" onkeyup="inputNumberFormat(this)">
                    &nbsp;&nbsp;&nbsp; 청구방식 : &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[0] ?? '' ?> name="delimethod" value="본사자체"> 본사자체 &nbsp;&nbsp;&nbsp;
                    <input type="radio" <?= $aryreg[1] ?? '' ?> name="delimethod" value="추후청구"> 추후청구 &nbsp;&nbsp;&nbsp;
                </div>
                
                <div class="clear"></div>
                <div class="space"></div>
                <div class="clear"></div>
                
                <div class="sero10">층별재질1 :</div>
                <div class="sero9">
                    <input type="text" name="material1" id="material1" value="<?= htmlspecialchars($material1, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="기타 재질">
                </div>
                <div class="sero8">
                    <?php
                    $aryreg = [];
                    if ($material2 == "") {
                        $material2 = "재질 미정";
                    }
                    switch ($material2) {
                        case "재질 미정":
                            $aryreg[0] = "selected";
                            break;
                        case "304 Hair Line 1.2T":
                            $aryreg[1] = "selected";
                            break;
                        case "304 Mirror 1.2T'":
                            $aryreg[2] = "selected";
                            break;
                        case "304 Mirror VB 1.2T":
                            $aryreg[3] = "selected";
                            break;
                        case "304 Mirror Bronze 1.2T":
                            $aryreg[4] = "selected";
                            break;
                        case "304 Mirror VB Ti-Bronze 1.2T":
                            $aryreg[5] = "selected";
                            break;
                        case "304 Hair Line Black 1.2T":
                            $aryreg[6] = "selected";
                            break;
                        case "SPCC 1.2T(도장)":
                            $aryreg[7] = "selected";
                            break;
                        case "EGI 1.2T(도장)":
                            $aryreg[8] = "selected";
                            break;
                        case "HTM (신우)":
                            $aryreg[9] = "selected";
                            break;
                        case "기타":
                            $aryreg[10] = "selected";
                            break;
                        default:
                            break;
                    }
                    ?>
                    
                    <select name="material2" id="material2" style="margin-top:1px;">
                        <option value='재질 미정' <?= $aryreg[0] ?? '' ?>>재질 미정</option>
                        <option value='304 Hair Line 1.2T' <?= $aryreg[1] ?? '' ?>>304 Hair Line 1.2T</option>
                        <option value='304 Mirror 1.2T' <?= $aryreg[2] ?? '' ?>>304 Mirror 1.2T</option>
                        <option value='304 Mirror VB 1.2T' <?= $aryreg[3] ?? '' ?>>304 Mirror VB 1.2T</option>
                        <option value='304 Mirror Bronze 1.2T' <?= $aryreg[4] ?? '' ?>>304 Mirror Bronze 1.2T</option>
                        <option value='304 Mirror VB Ti-Bronze 1.2T' <?= $aryreg[5] ?? '' ?>>304 Mirror VB Ti-Bronze 1.2T</option>
                        <option value='304 Hair Line Black 1.2T' <?= $aryreg[6] ?? '' ?>>304 Hair Line Black 1.2T</option>
                        <option value='SPCC 1.2T(도장)' <?= $aryreg[7] ?? '' ?>>SPCC 1.2T(도장)</option>
                        <option value='EGI 1.2T(도장)' <?= $aryreg[8] ?? '' ?>>EGI 1.2T(도장)</option>
                        <option value='HTM (신우)' <?= $aryreg[9] ?? '' ?>>HTM (신우)</option>
                        <option value='기타' <?= $aryreg[10] ?? '' ?>>기타</option>
                    </select>
                </div>
                <div class="clear"></div>
                
                <div class="sero10">층별재질2 :</div>
                <div class="sero9">
                    <input type="text" name="material3" id="material3" value="<?= htmlspecialchars($material3, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="기타 재질">
                </div>
                <div class="sero8">
                    <?php
                    $aryreg = [];
                    if ($material4 == "") {
                        $material4 = "재질 미정";
                    }
                    switch ($material4) {
                        case "재질 미정":
                            $aryreg[0] = "selected";
                            break;
                        case "304 Hair Line 1.2T":
                            $aryreg[1] = "selected";
                            break;
                        case "304 Mirror 1.2T'":
                            $aryreg[2] = "selected";
                            break;
                        case "304 Mirror VB 1.2T":
                            $aryreg[3] = "selected";
                            break;
                        case "304 Mirror Bronze 1.2T":
                            $aryreg[4] = "selected";
                            break;
                        case "304 Mirror VB Ti-Bronze 1.2T":
                            $aryreg[5] = "selected";
                            break;
                        case "304 Hair Line Black 1.2T":
                            $aryreg[6] = "selected";
                            break;
                        case "SPCC 1.2T(도장)":
                            $aryreg[7] = "selected";
                            break;
                        case "EGI 1.2T(도장)":
                            $aryreg[8] = "selected";
                            break;
                        case "HTM (신우)":
                            $aryreg[9] = "selected";
                            break;
                        case "기타":
                            $aryreg[10] = "selected";
                            break;
                        default:
                            break;
                    }
                    ?>
                    
                    <select name="material4" id="material4" style="margin-top:1px;">
                        <option value='재질 미정' <?= $aryreg[0] ?? '' ?>>재질 미정</option>
                        <option value='304 Hair Line 1.2T' <?= $aryreg[1] ?? '' ?>>304 Hair Line 1.2T</option>
                        <option value='304 Mirror 1.2T' <?= $aryreg[2] ?? '' ?>>304 Mirror 1.2T</option>
                        <option value='304 Mirror VB 1.2T' <?= $aryreg[3] ?? '' ?>>304 Mirror VB 1.2T</option>
                        <option value='304 Mirror Bronze 1.2T' <?= $aryreg[4] ?? '' ?>>304 Mirror Bronze 1.2T</option>
                        <option value='304 Mirror VB Ti-Bronze 1.2T' <?= $aryreg[5] ?? '' ?>>304 Mirror VB Ti-Bronze 1.2T</option>
                        <option value='304 Hair Line Black 1.2T' <?= $aryreg[6] ?? '' ?>>304 Hair Line Black 1.2T</option>
                        <option value='SPCC 1.2T(도장)' <?= $aryreg[7] ?? '' ?>>SPCC 1.2T(도장)</option>
                        <option value='EGI 1.2T(도장)' <?= $aryreg[8] ?? '' ?>>EGI 1.2T(도장)</option>
                        <option value='HTM (신우)' <?= $aryreg[9] ?? '' ?>>HTM (신우)</option>
                        <option value='기타' <?= $aryreg[10] ?? '' ?>>기타</option>
                    </select>
                </div>
                <div class="clear"></div>
                
                <div class="sero10">층별재질3 :</div>
                <div class="sero9">
                    <input type="text" name="material5" id="material5" value="<?= htmlspecialchars($material5, ENT_QUOTES, 'UTF-8') ?>" size="15" placeholder="기타 재질">
                </div>
                <div class="sero8">
                    <?php
                    $aryreg = [];
                    if ($material6 == "") {
                        $material6 = "재질 미정";
                    }
                    switch ($material6) {
                        case "재질 미정":
                            $aryreg[0] = "selected";
                            break;
                        case "304 Hair Line 1.2T":
                            $aryreg[1] = "selected";
                            break;
                        case "304 Mirror 1.2T'":
                            $aryreg[2] = "selected";
                            break;
                        case "304 Mirror VB 1.2T":
                            $aryreg[3] = "selected";
                            break;
                        case "304 Mirror Bronze 1.2T":
                            $aryreg[4] = "selected";
                            break;
                        case "304 Mirror VB Ti-Bronze 1.2T":
                            $aryreg[5] = "selected";
                            break;
                        case "304 Hair Line Black 1.2T":
                            $aryreg[6] = "selected";
                            break;
                        case "SPCC 1.2T(도장)":
                            $aryreg[7] = "selected";
                            break;
                        case "EGI 1.2T(도장)":
                            $aryreg[8] = "selected";
                            break;
                        case "HTM (신우)":
                            $aryreg[9] = "selected";
                            break;
                        case "기타":
                            $aryreg[10] = "selected";
                            break;
                        default:
                            break;
                    }
                    ?>
                    
                    <select name="material6" id="material6" style="margin-top:1px;">
                        <option value='재질 미정' <?= $aryreg[0] ?? '' ?>>재질 미정</option>
                        <option value='304 Hair Line 1.2T' <?= $aryreg[1] ?? '' ?>>304 Hair Line 1.2T</option>
                        <option value='304 Mirror 1.2T' <?= $aryreg[2] ?? '' ?>>304 Mirror 1.2T</option>
                        <option value='304 Mirror VB 1.2T' <?= $aryreg[3] ?? '' ?>>304 Mirror VB 1.2T</option>
                        <option value='304 Mirror Bronze 1.2T' <?= $aryreg[4] ?? '' ?>>304 Mirror Bronze 1.2T</option>
                        <option value='304 Mirror VB Ti-Bronze 1.2T' <?= $aryreg[5] ?? '' ?>>304 Mirror VB Ti-Bronze 1.2T</option>
                        <option value='304 Hair Line Black 1.2T' <?= $aryreg[6] ?? '' ?>>304 Hair Line Black 1.2T</option>
                        <option value='SPCC 1.2T(도장)' <?= $aryreg[7] ?? '' ?>>SPCC 1.2T(도장)</option>
                        <option value='EGI 1.2T(도장)' <?= $aryreg[8] ?? '' ?>>EGI 1.2T(도장)</option>
                        <option value='HTM (신우)' <?= $aryreg[9] ?? '' ?>>HTM (신우)</option>
                        <option value='기타' <?= $aryreg[10] ?? '' ?>>기타</option>
                    </select>
                </div>
                <div class="clear"></div>
                
                <div class="sero1">와이드쟘 :</div>
                <div class="sero2">
                    <input type="text" name="widejamb" id="widejamb" value="<?= htmlspecialchars($widejamb, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="와이드쟘 수량" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
                </div>
                <div class="sero1">멍텅구리 :</div>
                <div class="sero4">
                    <input type="text" name="normaljamb" id="normaljamb" value="<?= htmlspecialchars($normaljamb, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="멍텅구리 수량" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
                </div>
                <div class="sero1">쪽쟘 :</div>
                <div class="sero4">
                    <input type="text" name="smalljamb" id="smalljamb" value="<?= htmlspecialchars($smalljamb, ENT_QUOTES, 'UTF-8') ?>" size="10" placeholder="쪽쟘 수량" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
                </div>
                <div class="clear"></div>
                
                <div class="sero6">추가 내역 :</div>
                <div class="sero5">
                    <textarea rows="5" cols="80" name="memo" id="memo" placeholder="추가적으로 기록할 내역"><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <br><br><br><br>
            </div>
        </div>
    </div>
    </form>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    // jExcel 초기화
    var options = {
        minDimensions: [16, 266],
        tableOverflow: true
    };
    
    if (typeof $ !== 'undefined') {
        $('#spreadsheet').jexcel(options);
    }
    
    // Datepicker 초기화
    $(function() {
        $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#orderday").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#measureday").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#drawday").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#workday").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#deadline").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#endworkday").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#promiseday").datepicker({ dateFormat: 'yy-mm-dd' });
    });
    
    /**
     * 숫자 포맷팅
     */
    window.inputNumberFormat = function(obj) {
        if (obj && obj.value) {
            obj.value = comma(uncomma(obj.value));
        }
    };
    
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    
    /**
     * 날짜 입력 마스크 (eval() 제거하여 보안 개선)
     */
    window.date_mask = function(formd, textid) {
        var form = document[formd];
        if (!form) return;
        
        var text = form[textid];
        if (!text) return;
        
        var textlength = text.value.length;
        
        if (textlength == 4) {
            text.value = text.value + "-";
        } else if (textlength == 7) {
            text.value = text.value + "-";
        } else if (textlength > 9) {
            var chk_date = checkdate(text);
            if (chk_date == false) {
                return;
            }
        }
    };
    
    /**
     * 날짜 유효성 검사
     */
    function checkdate(input) {
        var validformat = /^\d{4}\-\d{2}\-\d{2}$/;
        var returnval = false;
        
        if (!validformat.test(input.value)) {
            alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
        } else {
            var yearfield = input.value.split("-")[0];
            var monthfield = input.value.split("-")[1];
            var dayfield = input.value.split("-")[2];
            var dayobj = new Date(yearfield, monthfield - 1, dayfield);
            
            if ((dayobj.getMonth() + 1 != monthfield) ||
                (dayobj.getDate() != dayfield) ||
                (dayobj.getFullYear() != yearfield)) {
                alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            } else {
                returnval = true;
            }
        }
        
        if (returnval == false) {
            input.select();
        }
        return returnval;
    }
    
    /**
     * 텍스트 입력 및 클립보드 복사
     */
    window.input_Text = function() {
        var testElement = document.getElementById("test");
        if (testElement) {
            testElement.value = comma(Math.floor(uncomma(testElement.value) * 1.1));
            testElement.select();
            document.execCommand("Copy");
        }
    };
    
    /**
     * CSV 데이터를 폼에 입력
     */
    window.Process = function() {
        if (typeof $ === 'undefined') return;
        
        for (var i = 1; i <= 1; i++) {
            var orderday = $('tr:eq(' + i + ')>td:eq(1)').html();
            var firstord = $('tr:eq(' + i + ')>td:eq(2)').html();
            var secondord = $('tr:eq(' + i + ')>td:eq(3)').html();
            var workplacename = $('tr:eq(' + i + ')>td:eq(4)').html();
            var address = $('tr:eq(' + i + ')>td:eq(5)').html();
            var material1 = $('tr:eq(' + i + ')>td:eq(6)').html();
            var widejamb = $('tr:eq(' + i + ')>td:eq(7)').html();
            var smalljamb = $('tr:eq(' + i + ')>td:eq(8)').html();
            var measureday = $('tr:eq(' + i + ')>td:eq(9)').html();
            var deadline = $('tr:eq(' + i + ')>td:eq(10)').html();
            var secondordman = $('tr:eq(' + i + ')>td:eq(11)').html();
            var secondordmantel = $('tr:eq(' + i + ')>td:eq(12)').html();
            var worker = $('tr:eq(' + i + ')>td:eq(13)').html();
            var delipay = $('tr:eq(' + i + ')>td:eq(14)').html();
            var delimethod = $('tr:eq(' + i + ')>td:eq(15)').html();
            var memo = $('tr:eq(' + i + ')>td:eq(16)').html();
            
            $("#orderday").val(orderday);
            $("#firstord").val(firstord);
            $("#secondord").val(secondord);
            $("#workplacename").val(workplacename);
            $("#address").val(address);
            $("#material1").val(material1);
            $("#widejamb").val(widejamb);
            $("#smalljamb").val(smalljamb);
            $("#measureday").val(measureday);
            $("#deadline").val(deadline);
            $("#secondordman").val(secondordman);
            $("#secondordmantel").val(secondordmantel);
            $("#worker").val(worker);
            $("#delipay").val(delipay);
            $("#delimethod").val(delimethod);
            $("#memo").val(memo);
        }
    };
    
})();
</script>

</body>
</html>
