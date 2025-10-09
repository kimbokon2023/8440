<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

$title_message = 'jamb 출고증';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

include includePath('load_header.php');
?>

</head>

<body>

<?php

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 입력 검증
if (empty($num)) {
    die('필수 매개변수(num)가 누락되었습니다.');
}

 
// 출고일자 생성 (요일 포함)
$outputdate = date("Y-m-d", time());
if ($outputdate != "") {
    $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
    $outputdate = $outputdate . $week[date('w', strtotime($outputdate))];
} 
 
// 데이터베이스에서 작업 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.work WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        print "검색결과가 없습니다.<br>";
        exit;
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $item_file_0 = $row["file_name_0"] ?? '';
        $item_file_1 = $row["file_name_1"] ?? '';
        $copied_file_0 = "../uploads/" . ($row["file_copied_0"] ?? '');
        $copied_file_1 = "../uploads/" . ($row["file_copied_1"] ?? '');
    }

    // 데이터베이스 결과를 변수에 할당 (안전하게)
    $checkstep = $row["checkstep"] ?? '';
    $workplacename = $row["workplacename"] ?? '';
    $address = $row["address"] ?? '';
    $firstord = $row["firstord"] ?? '';
    $firstordman = $row["firstordman"] ?? '';
    $firstordmantel = $row["firstordmantel"] ?? '';
    $secondord = $row["secondord"] ?? '';
    $secondordman = $row["secondordman"] ?? '';
    $secondordmantel = $row["secondordmantel"] ?? '';
    $chargedman = $row["chargedman"] ?? '';
    $chargedmantel = $row["chargedmantel"] ?? '';
    $orderday = $row["orderday"] ?? '';
    $measureday = $row["measureday"] ?? '';
    $drawday = $row["drawday"] ?? '';
    $deadline = $row["deadline"] ?? '';
    $workday = $row["workday"] ?? '';
    $worker = $row["worker"] ?? '';
    $endworkday = $row["endworkday"] ?? '';
    $material1 = $row["material1"] ?? '';
    $material2 = $row["material2"] ?? '';
    $material3 = $row["material3"] ?? '';
    $material4 = $row["material4"] ?? '';
    $material5 = $row["material5"] ?? '';
    $material6 = $row["material6"] ?? '';
    $widejamb = $row["widejamb"] ?? '';
    $normaljamb = $row["normaljamb"] ?? '';
    $smalljamb = $row["smalljamb"] ?? '';
    $memo = $row["memo"] ?? '';
    $regist_day = $row["regist_day"] ?? '';
    $update_day = $row["update_day"] ?? '';
    
    $delivery = $row["delivery"] ?? '';
    $delicar = $row["delicar"] ?? '';
    $delicompany = $row["delicompany"] ?? '';
    $delipay = $row["delipay"] ?? '';
    $delimethod = $row["delimethod"] ?? '';
    $demand = $row["demand"] ?? '';
    $startday = $row["startday"] ?? '';
    $testday = $row["testday"] ?? '';
    $hpi = $row["hpi"] ?? '';
    $attachment = $row["attachment"] ?? '';


    // 날짜 포맷팅 함수
    function formatDate($date) {
        if (!$date || $date == "0000-00-00" || $date == "1970-01-01" || $date == "") {
            return "";
        }
        $timestamp = strtotime($date);
        return $timestamp ? date("Y-m-d", $timestamp) : "";
    }

    // 날짜 포맷팅 적용
    $orderday = formatDate($orderday);
    $measureday = formatDate($measureday);
    $drawday = formatDate($drawday);
    $deadline = formatDate($deadline);
    $workday = formatDate($workday);
    $endworkday = formatDate($endworkday);
    $demand = formatDate($demand);
    $startday = formatDate($startday);
    $testday = formatDate($testday);
		  		      						
    // 시공소장 전화번호 매핑
    $workertel = '';
    switch ($worker) {
        case "김운호":   $workertel = "010-9322-7626"; break;
        case "김상훈":   $workertel = "010-6622-2200"; break;
        case "이만희":   $workertel = "010-6866-5030"; break;
        case "유영":     $workertel = "010-5838-5948"; break;
        case "추영덕":   $workertel = "010-6325-4280"; break;
        case "김지암":   $workertel = "010-3235-5850"; break;
        case "손상민":   $workertel = "010-4052-8930"; break;
        case "지영복":   $workertel = "010-6338-9718"; break;
        case "김한준":   $workertel = "010-4445-7515"; break;
        case "민경채":   $workertel = "010-2078-7238"; break;
        case "이용휘":   $workertel = "010-9453-8612"; break;
        case "박경호":   $workertel = "010-3405-6669"; break;
        case "조형영":   $workertel = "010-2419-2574"; break;
        case "김진섭":   $workertel = "010-6524-3325"; break;
        case "최양원":   $workertel = "010-5426-3475"; break;
        case "임형주":   $workertel = "010-8976-9777"; break;
        case "박철우":   $workertel = "010-4857-7022"; break;
        case "조장우":   $workertel = "010-5355-9709"; break;
        case "백석묵":   $workertel = "010-5635-0821"; break;
        case "이인종":   $workertel = "010-5237-0771"; break;
        default: break;
    }	
	
    // 출고 항목 배열 초기화
    $text = array();
    $textnum1 = array();
    $textnum2 = array();
    $textnum3 = array();
    $textmemo = array();
    
    $text[3] = "";
    $text[4] = "";
    
    $j = 0;
    
    // 출고 항목 구성
    if ($widejamb >= 1) {
        $text[$j] = "와이드쟘 막판(유)";
        $textnum1[$j] = $widejamb;
        $textmemo[$j] = "";
        $j++;
    }
    
    if ($normaljamb >= 1) {
        $text[$j] = "와이드쟘 막판(무)";
        $textnum2[$j] = $normaljamb;
        $textmemo[$j] = "";
        $j++;
    }
    
    if ($smalljamb >= 1) {
        $text[$j] = "쪽쟘";
        $textnum3[$j] = $smalljamb;
        $textmemo[$j] = "";
        $j++;
    }
    
    if ($attachment != null && ($attachment != "x" && $attachment != "X")) {
        $text[$j] = "부속자재 : " . $attachment;
        $textnum[$j] = '';
        $textmemo[$j] = '';
        $j++;
    }

    // 시공소장 이름에 '소장' 추가
    if ($worker != '') {
        $worker .= ' 소장';
    }

} catch (PDOException $Exception) {
    if (isLocal()) {
        error_log("Database error in transform.php: " . $Exception->getMessage());
        die("데이터베이스 오류: " . $Exception->getMessage());
    } else {
        error_log("Database error in transform.php: " . $Exception->getMessage());
        die("데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.");
    }
}
  

?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES) ?></title>
  
<form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?= htmlspecialchars($num, ENT_QUOTES) ?>" enctype="multipart/form-data">

	<input type="hidden" name="workday" id="workday" value="<?= htmlspecialchars($workday ?? '', ENT_QUOTES) ?>">
	
<div class="container">	
<div class="card">
<div class="card-body">        
<div class="d-flex justify-content-center mb-1 mt-1">  
    <h4 class="mt-1 mb-1">
        <?= htmlspecialchars($title_message, ENT_QUOTES) ?>
	</h4>			
</div>
<div class="d-flex justify-content-start mb-1 mt-1">  
	<button onclick="window.close();" type="button" class="btn btn-dark btn-sm me-1">
	    <ion-icon name="close-outline"></ion-icon> 창닫기
	</button>
	<button onclick="form.submit();" type="button" class="btn btn-success btn-sm">
	    <ion-icon name="print-outline"></ion-icon> 인쇄
	</button>        
</div>
<div class="d-flex flex-column">
    <div class="mb-1 input-group">
        <div class="input-group-text" style="color:red; width: 150px;">하차일시 </div>
        <input type="text" name="outputdate" id="outputdate" value="<?= htmlspecialchars($outputdate ?? '', ENT_QUOTES) ?>" class="form-control fs-6" placeholder="일시" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;">현장명</div>
        <input type="text" name="workplacename" id="workplacename" value="<?= htmlspecialchars($workplacename ?? '', ENT_QUOTES) ?>" class="form-control fs-6" placeholder="현장명" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;">현장주소</div>
        <input type="text" name="address" id="address" value="<?= htmlspecialchars($address ?? '', ENT_QUOTES) ?>" class="form-control fs-6" placeholder="현장주소" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;">발주처</div>
        <input type="text" name="secondord" id="secondord" value="<?= htmlspecialchars($secondord ?? '', ENT_QUOTES) ?>" class="form-control fs-6" placeholder="발주처">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;">받으실분</div>
        <input type="text" name="worker" id="worker" value="<?= htmlspecialchars($worker ?? '', ENT_QUOTES) ?>" class="form-control fs-6" placeholder="담당자">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width:150px;">받으실분 연락처</div>
        <input type="text" name="workertel" id="workertel" value="<?= htmlspecialchars($workertel ?? '', ENT_QUOTES) ?>" class="form-control fs-6" placeholder="담당자 연락처">
    </div>
    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="margin-left:150px;width:350px;"> (신규쟘 한산) ->(채규철과장 010-7105-7060) </div>
    </div>
</div>

	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center" style="width:150px;"> 번호 </span>
            <span class="input-group-text text-center" style="width:500px;">  내역  </span>
            <span class="input-group-text text-center" style="width:100px;"> 막판 </span>
            <span class="input-group-text text-center" style="width:100px;"> 막판무 </span>
            <span class="input-group-text text-center" style="width:100px;"> 쪽쟘 </span>
		</div>
	</div>	

    <?php
    for ($i = 0; $i <= 9; $i = $i + 1) {
        ?>
	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center" style="width:150px;"><?=$i+1?>번째줄 </span>
            <input type="text" name="text[]" id="text<?=$i?>" value="<?= htmlspecialchars($text[$i] ?? '', ENT_QUOTES) ?>" class="input-group-text text-left" style="text-align:left; width: 500px;">            
            <input type="text" name="textnum1[]" value="<?= htmlspecialchars($textnum1[$i] ?? '', ENT_QUOTES) ?>" style="width:100px;" class="text-center fs-6">            
            <input type="text" name="textnum2[]" value="<?= htmlspecialchars($textnum2[$i] ?? '', ENT_QUOTES) ?>" style="width:100px;" class="text-center fs-6">
			<input type="text" name="textnum3[]" value="<?= htmlspecialchars($textnum3[$i] ?? '', ENT_QUOTES) ?>" style="width:100px;" class="text-center fs-6">			
		</div>
		</div>	
    <?php } ?>
</div>
</div>            
</div>            
</form>
    
</body>
</html>

<script>

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
</script>
</body>
</html>
