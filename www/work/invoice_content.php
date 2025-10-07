<?php
require_once(includePath('session.php'));

$title_message = '공 사 완 료 확 인 서';
$tablename = 'work';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recordIds'])) {
    $recordIds = $_POST['recordIds'];
} else {
    die('Invalid request.');
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
?>

<?php include getDocumentRoot() . '/load_header.php'; ?>

<title> <?=$title_message?> </title>

<style>
    th, td {
        border: 1px solid #ccc !important;
        font-size: 25px !important;
        padding: 10px;
    }
    @media print {
        body {
            width: 210mm;
            height: 297mm;
            margin: 5mm;
            font-size: 10pt;
        }
        .table th, .table td {
            padding: 1px;
        }
        .text-center {
            text-align: center;
        }
    }
    .pagebreak { page-break-before: always; }
</style>
</head>
<body>

<div class="container mt-2">
    <div class="d-flex align-items-center justify-content-end mt-1 m-2">
        <button class="btn btn-dark btn-sm me-1" onclick="generatePDF()"> PDF 저장 </button>
        <button class="btn btn-secondary btn-sm" onclick="self.close();"> <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;
    </div>
</div>

<div id="content-to-print">
    <?php
    foreach ($recordIds as $num) {
        try {
            $sql = "SELECT * FROM $DB.$tablename WHERE num = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $num, PDO::PARAM_STR);
            $stmh->execute();
            $count = $stmh->rowCount();
            if ($count < 1) {
                print "검색결과가 없습니다.<br>";
            } else {
                $row = $stmh->fetch(PDO::FETCH_ASSOC);
                include getDocumentRoot() . '/work/_row.php';
				
			

                $customer_data = $row["customer"];
                $customer_object = json_decode($customer_data, true);				
				
				preg_match('/\(([A-Za-z][^)]*)\)/', $workplacename, $matches);
				$pjnum = isset($matches[1]) ? $matches[1] : '';

				// totalsu 계산				
					$widejamb = isset($row['widejamb']) ? $row['widejamb'] : 0;
					$normaljamb = isset($row['normaljamb']) ? $row['normaljamb'] : 0;
					$smalljamb = isset($row['smalljamb']) ? $row['smalljamb'] : 0;
					$totalsu = (intval($widejamb) + intval($normaljamb) + intval($smalljamb)) . ' SET';			
	
                if ($customer_object === null || empty($customer_object['image_url'])) {
                    // 서명이 없는 경우
                    continue;
                } else {
                    $customer_date = $customer_object['customer_date'];
                    $ordercompany = $customer_object['ordercompany'];                    
                    $workname = $customer_object['workname'];                                        
                    $customer_name = $customer_object['customer_name'];
                    $image_url = $customer_object['image_url'];
                }
                ?>

                <div class="container mt-3">
                    <div class="d-flex align-items-center justify-content-center mt-2 mb-2">
                        <h1> <?=$title_message?> </h1>
                    </div>

                    <div class="d-flex align-items-center justify-content-center mt-5 mb-1">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="text-center" style="width:30%;"> 공사시행사</td>
                                    <td class="text-start" style="width:200px;"> <?=$ordercompany?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 현장명</td>
                                    <td class="text-start"> <?=$workplacename?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">공사명</td>
                                    <td class="text-start" style="width:110px;"><?=$workname?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">JOB NO</td>
                                    <td class="text-start"><?=$pjnum?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">수량</td>
                                    <td class="text-start"><?=$totalsu?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">시공소장</td>
                                    <td class="text-start"><?=$worker?></td>
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
                <div class="container pagebreak"> </div>
                <?php
            }
        } catch (PDOException $Exception) {
            print "오류: " . $Exception->getMessage();
        }
    }
    ?>
</div>

</body>
</html>

<script>
function generatePDF() {
    var d = new Date();
    var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
    var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
    var result = '공사완료확인서_' + currentDate + currentTime + '.pdf';

    var element = document.getElementById('content-to-print');
    var opt = {
        margin: 0,
        filename: result,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().from(element).set(opt).save();
}
</script>
