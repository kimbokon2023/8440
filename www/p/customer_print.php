<?php
// 공사완료 확인서 출력 (JAMB CLADDING용) - 로컬/서버 환경 호환
require_once __DIR__ . '/../bootstrap.php';

$title_message = '공 사 완 료 확 인 서'; 
$tablename = 'work';

include includePath('load_header.php');
?>

<title> <?=$title_message?> </title>

<style>

th, td {
    border: 1px solid #ccc !important; /* 가늘고 옅은 회색 테두리 */
    font-size: 25px !important;
    padding: 10px;
}


</style>
</head>

<body>  

<html lang="ko">

<?php
// 입력값 검증 및 초기화
$num = $_REQUEST['num'] ?? '';

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
            error_log("Database connection failed in customer_print.php: " . $e->getMessage());
            die("데이터베이스 연결에 실패했습니다. 관리자에게 문의하세요.");
        }
    }
}

try {
    $sql = "SELECT * FROM $DB.$tablename WHERE num = ?";
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_INT); 
    $stmh->execute();
    $count = $stmh->rowCount();              
    
    if ($count < 1) {  
        die("검색결과가 없습니다.");
    }
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);  
    include getDocumentRoot() . '/work/_row.php'; 
    
    // customer 필드 가져오기 (Json형태의 값)
    $customer_data = $row["customer"] ?? '{}';
    $customer_object = json_decode($customer_data, true);
    
    // 초기값 설정
    $customer_date = '';
    $ordercompany = '미래기업';
    $workplacename = '';
    $workname = 'JAMB CLADDING';
    $pjnum = '';
    $totalsu = '';
    $worker = '';
    $customer_name = '';
    $image_url = '';
    
    if ($customer_object !== null && !empty($customer_object)) {
        // 디코딩된 데이터를 각 변수에 할당
        $customer_date = $customer_object['customer_date'] ?? '';
        $ordercompany = $customer_object['ordercompany'] ?? '미래기업';
        $workplacename = $customer_object['workplacename'] ?? '';
        $workname = $customer_object['workname'] ?? 'JAMB CLADDING';
        $pjnum = $customer_object['pjnum'] ?? '';
        $totalsu = $customer_object['totalsu'] ?? '';
        $worker = $customer_object['worker'] ?? '';
        $customer_name = $customer_object['customer_name'] ?? '';
        $image_url = $customer_object['image_url'] ?? '';
    }
    
    // 환경별 이미지 경로 설정
    $signurl = '';
    if (!empty($image_url)) {
        $signurl = isLocal() ? '../work/' . $image_url : asset('work/' . $image_url);
    }
    
} catch (PDOException $Exception) {
    if (isLocal()) {
        die("오류: " . $Exception->getMessage());
    } else {
        error_log("Database error in customer_print.php: " . $Exception->getMessage());
        die("데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.");
    }
}
?>

<div class="container mt-2">
    <div class="d-flex align-items-center justify-content-end mt-1 m-2">
        <button class="btn btn-dark btn-sm me-1" onclick="generatePDF()"> PDF 저장 </button>
        <button class="btn btn-secondary btn-sm" onclick="self.close();"> <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;    
    </div>
</div>

<div id="content-to-print">    
<br>
<div class="container mt-3">
<div class="d-flex align-items-center justify-content-center mt-2 mb-2 ">
    <h1 > <?=$title_message?> </h1>
</div>

<div class="d-flex align-items-center justify-content-center mt-5 mb-1">
    <table class="table ">
        <tbody>
            <tr>
                <td class="text-center  " style="width:30%;"> 공사시행사</td>
                <td class="text-start  "  style="width:200px;"> <?= htmlspecialchars($ordercompany) ?></td>
            </tr>                
			<tr>
                <td class="text-center  " > 현장명</td>
                <td class="text-start  "> <?= htmlspecialchars($workplacename) ?></td>
            </tr>                
			<tr>				
                <td class="text-center  ">공사명</td>
                <td class="text-start  " style="width:110px;" ><?= htmlspecialchars($workname) ?></td>
            </tr>                
			<tr>
                <td class="text-center  ">JOB NO</td>
                <td class="text-start  "><?= htmlspecialchars($pjnum) ?></td>
            </tr>                
			<tr>				
                <td class="text-center  ">수량</td>
                <td class="text-start  " ><?= htmlspecialchars($totalsu) ?></td>
            </tr>
            <tr>
                <td class="text-center  ">시공소장</td>
                <td class="text-start  "><?= htmlspecialchars($worker) ?></td>
            </tr>                            
        </tbody>
    </table>
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-3">	
			상기 현장의 JAMB CLADDING 공사가 완료 되었음을 확인함. 
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-2">	
			 <?= htmlspecialchars($customer_date) ?>
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-2">	
			위 확인자 : &nbsp; <span class="text-center me-2 "><?= htmlspecialchars($customer_name) ?></span>  <span class="text-center fs-5 ">  (서명) </span>  
			  <?php if (!empty($image_url) && !empty($signurl)) { ?>
                        <img src="<?= htmlspecialchars($signurl) ?>" style="width:20%;" alt="서명">
                    <?php } ?>
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-2">	
			FAX : 031.982.8449
	</div>		
	
                              
</div>
</div>
</div>    <!-- end of content-to-print --> 

</body>

</html>

<!-- 페이지로딩 -->
<script>
// 페이지 로딩
$(document).ready(function(){    
    var loader = document.getElementById('loadingOverlay');
    if (loader) {
        loader.style.display = 'none';
    }
});

function generatePDF() {
    var workplace = '<?php echo htmlspecialchars($workplacename, ENT_QUOTES); ?>';
    var d = new Date();
    var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
    var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
    var result = 'jamb 공사완료확인서(' + workplace +')' + currentDate + currentTime + '.pdf';    
    
    var element = document.getElementById('content-to-print');
    
    if (typeof html2pdf === 'undefined') {
        alert('PDF 생성 라이브러리가 로드되지 않았습니다.');
        return;
    }
    
    var opt = {
        margin:       0,
        filename:     result,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    html2pdf().from(element).set(opt).save();
}
</script>
