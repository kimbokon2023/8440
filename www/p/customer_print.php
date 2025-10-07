<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

$title_message = '공 사 완 료 확 인 서'; 
$tablename = 'work'; 
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; ?>

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

$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : '';  

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
try {
    $sql = "SELECT * FROM $DB.$tablename WHERE num = ? ";
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_STR); 
    $stmh->execute();
    $count = $stmh->rowCount();              
    if ($count < 1) {  
        print "검색결과가 없습니다.<br>";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);  
		include $_SERVER['DOCUMENT_ROOT'] . '/work/_row.php'; 
        
        // customer 필드 가져오기 (Json형태의 값)
        $customer_data = $row["customer"];
        // JSON 데이터를 PHP 객체로 디코딩
        $customer_object = json_decode($customer_data, true);
        if ($customer_object === null) {
            echo "<h1> 서명이 존재하지 않습니다. </h1>";
        } else {
            // 디코딩된 데이터를 각 변수에 할당
            $customer_date = $customer_object['customer_date'];
            $ordercompany = $customer_object['ordercompany'];
            $workplacename = $customer_object['workplacename'];
            $workname = $customer_object['workname'];
            $pjnum = $customer_object['pjnum'];
            $totalsu = $customer_object['totalsu'];
            $worker = $customer_object['worker'];
            $customer_name = $customer_object['customer_name'];
            $image_url = $customer_object['image_url'];
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
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
                <td class="text-start  "  style="width:200px;"> <?=$ordercompany?></td>
            </tr>                
			<tr>
                <td class="text-center  " > 현장명</td>
                <td class="text-start  "> <?=$workplacename?></td>
            </tr>                
			<tr>				
                <td class="text-center  ">공사명</td>
                <td class="text-start  " style="width:110px;" ><?=$workname?></td>
            </tr>                
			<tr>
                <td class="text-center  ">JOB NO</td>
                <td class="text-start  "><?=$pjnum?></td>
            </tr>                
			<tr>				
                <td class="text-center  ">수량</td>
                <td class="text-start  " ><?=$totalsu?></td>
            </tr>
            <tr>
                <td class="text-center  ">시공소장</td>
                <td class="text-start  "><?=$worker?></td>
            </tr>                            
        </tbody>
    </table>
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-3">	
			상기 현장의 JAMB CLADDING 공사가 완료 되었음을 확인함. 
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-2">	
			 <?=$customer_date?>
	</div>	
	<div class="d-flex align-items-center justify-content-center mt-5 mb-5 fs-2">	
			위 확인자 : &nbsp; <span class="text-center me-2 "><?=$customer_name?></span>  <span class="text-center fs-5 ">  (서명) </span>  
			  <?php if (!empty($image_url)) { ?>
                        <img src="../work/<?=$image_url?>" style="width:20%;">
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
    loader.style.display = 'none';
});

function generatePDF() {
    var workplace = '<?php echo $workplacename; ?>';
    var d = new Date();
    var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
    var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
    var result = 'jamb 공사완료확인서(' + workplace +')' + currentDate + currentTime + '.pdf';    
    
    var element = document.getElementById('content-to-print');
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
