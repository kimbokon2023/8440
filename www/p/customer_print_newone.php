<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");
$title_message = '설치·검수 완료 확인서';
$tablename = 'work';
include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
?>
<title><?= $title_message ?></title>
<style>
    * {
        font-family: "맑은 고딕", sans-serif;
    }
    .print-area {
        width: 800px;        
        border: 1px solid #000;
        padding: 20px;
    }
    
    .statement {
        text-align: center;
        font-size: 22px;
        margin: 50px 0;
    }
    .comparison {
        display: flex;
        justify-content: space-between;
        margin: 40px 0;
    }
    .comparison-box {
        width: 40%;
        height: auto;        
        font-size: 20px;
        text-align: center;
        line-height: 100px;
    }
    .signature-area {
        display: flex;
        justify-content: flex-end;
        margin-top: 60px;
    }
    .signature-text {
        font-size: 20px;
        text-align: right;
        margin-right: 30px;
    }
    .signature-img {
        width: 120px;
        height: auto;
        border: 1px solid #000;
    }  
</style>

<body>
<?php
$num = $_REQUEST['num'] ?? '';
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

try {
    $sql = "SELECT * FROM $DB.$tablename WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();

    if ($count < 1) {
        echo "검색결과가 없습니다.";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        include $_SERVER['DOCUMENT_ROOT'] . '/work/_row.php';

        $customer = json_decode($row['customer'], true);
        $jobno = $customer['pjnum'] ?? '';
        $site = $customer['workplacename'] ?? '';
        $content = $customer['workname'] ?? '';
        $qty = $customer['totalsu'] ?? '';
        $company = $customer['ordercompany'] ?? '';
        $name = $customer['customer_name'] ?? '';
        $date = $customer['customer_date'] ?? '';
        $sign = $customer['image_url'] ?? '';
        
        // 작업전/작업후 사진 정보 추가
        $filename1 = $row["filename1"];  // 작업전 사진
        $filename2 = $row["filename2"];  // 작업후 사진
        $imgurl1 = "../imgwork/" . $filename1;
        $imgurl2 = "../imgwork/" . $filename2;
    }
} catch (PDOException $e) {
    echo "오류: " . $e->getMessage();
}
?>

<!-- 상단 버튼 영역 -->
<div class="container mt-3 ">
    <div class="d-flex justify-content-end">
        <button class="btn btn-dark btn-sm me-2" onclick="generatePDF()">📄 PDF 저장</button>
        <button class="btn btn-secondary btn-sm" onclick="window.close();">❌ 닫기</button>
    </div>
</div>

<div id="content-to-print">    
<br>
   <div class="container mt-3 print-area">
    <div class="row">
        <table class="table table-bordered">
    <tbody>
        <tr style="border:1px solid #000;">
            <div class="title" style="text-align: center; font-size: 24px; font-weight: bold;"><?= $title_message ?></div>
            <hr>
        </tr>
        <tr>
            <div class="d-flex justify-content-start">
                <table class="table table-bordered" style="font-size: 20px; width: 100%;">
                    <tr>
                        <th style="width: 20%;">JOB NO</th>
                        <td><?= htmlspecialchars($jobno) ?></td>
                    </tr>
                    <tr>
                        <th>현장명</th>
                        <td><?= htmlspecialchars($site) ?></td>
                    </tr>
                    <tr>
                        <th>작업내용</th>
                        <td><?= htmlspecialchars($content) ?></td>
                    </tr>
                    <tr>
                        <th>작업수량</th>
                        <td><?= htmlspecialchars($qty) ?> </td>
                    </tr>
                </table>
            </div>           
            <div class="statement"> 상기 현장에 설치. 검수인도가 완료하였기에 확인서를 제출합니다. </div>
            <div class="d-flex justify-content-end fs-6"> 작업업체명: ㈜미 래 기 업 </div>
        </tr>

        <div class="comparison">
            <div class="comparison-box">
                <div style="font-size: 16px; margin-bottom: 10px;">작업전</div>
                <?php if($filename1 != "") { ?>
                    <img src="<?= $imgurl1 ?>" style="max-width: 100%; max-height: 200px; object-fit: contain;">
                <?php } else { ?>
                    <div style="color: #999; font-size: 14px;">사진 없음</div>
                <?php } ?>
            </div>
            <div class="comparison-box">
                <div style="font-size: 16px; margin-bottom: 10px;">작업후</div>
                <?php if($filename2 != "") { ?>
                    <img src="<?= $imgurl2 ?>" style="max-width: 100%; max-height: 200px; object-fit: contain;">
                <?php } else { ?>
                    <div style="color: #999; font-size: 14px;">사진 없음</div>
                <?php } ?>
            </div>
        </div>
        <hr>
        <h5 class="text-start">현장 담당자 확인</h5>
        <div class="d-flex justify-content-end text-end   fs-5"> <?= date('Y年 m月 d日', strtotime(htmlspecialchars($date))) ?> <span style="margin-right: 180px;"></span></div>
        <div class="signature-area">
            <div class="signature-text">                
        
                담 당 자 : <?= htmlspecialchars($name) ?> (인)
            </div>
            <?php if (!empty($sign)) { ?>
            <div>
                <img src="../work/<?= $sign ?>" class="signature-img">
            </div>
            <?php } ?>
        </div>
    </div>
    </tbody>
  </table>    
</div>

<script>

function generatePDF() {
    var workplace = '<?php echo $workplacename; ?>';
    var d = new Date();
    var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
    var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
    var result = '설치검수확인서_(' + workplace +')' + currentDate + currentTime + '.pdf';    
    
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
</body>
</html>
