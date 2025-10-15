<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'chandj';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 요청 파라미터 초기화
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";

// 기간을 정하는 구간
if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

// 날짜 변수
$nowday = date("Y-m-d");   // 현재일자 변수지정

// 검색 조건 설정
$find1 = "경동";
$find2 = "대신";

// SQL 쿼리 구성
$ksql = "SELECT * FROM {$DB}.output 
         WHERE (delivery LIKE '%{$find1}%') 
           AND (outdate BETWEEN DATE('{$fromdate}') AND DATE('{$Transtodate}')) 
           AND (regist_state = '2') 
         ORDER BY outdate DESC";

$dsql = "SELECT * FROM {$DB}.output 
         WHERE (delivery LIKE '%{$find2}%') 
           AND (outdate BETWEEN DATE('{$fromdate}') AND DATE('{$Transtodate}')) 
           AND (regist_state = '2') 
         ORDER BY outdate DESC";
?>

<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>화물 당일 출고 리스트</title>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/output.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print.css">
</head>

<body onload="pagePrintPreview();">

    <div id="print">
        <div class="img">
            <div class="menu">
                <div class="print1"><?= htmlspecialchars($Transtodate, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="clear"></div>
                
                <?php
                // 변수 초기화
                $total_row = 0;
                
                // 경동화물 처리
                try {
                    $stmh = $pdo->query($ksql);
                    $temp = $stmh->rowCount();
                    $total_row = $temp;
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $num = $row["num"];
                        $outdate = $row["outdate"];
                        $indate = $row["indate"];
                        $orderman = $row["orderman"];
                        $outworkplace = $row["outworkplace"];
                        $outputplace = $row["outputplace"];
                        $receiver = $row["receiver"];
                        $phone = $row["phone"];
                        $comment = $row["comment"];
                        $root = $row["root"];
                        $steel = $row["steel"];
                        $motor = $row["motor"];
                        $delivery = $row["delivery"];
                        ?>
                        
                        <div class="print1_1">경동</div>
                        <div class="print2"><?= htmlspecialchars($receiver, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print3"><?= htmlspecialchars(substr($comment, 0, 90), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print4"><?= htmlspecialchars(substr($outputplace, 0, 90), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print5"><?= htmlspecialchars(substr($phone, 0, 13), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print6"><?= htmlspecialchars($delivery, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="clear"></div>
                        
                        <?php
                    }  // end of while
                } catch (PDOException $ex) {
                    error_log("경동화물 출고 리스트 조회 오류: " . $ex->getMessage());
                }
                ?>  
                <!-- 중간에 공백한칸 만들기 -->
                <div class="print1_1"></div>
                <div class="print2"></div>
                <div class="print3"></div>
                <div class="print4"></div>
                <div class="print5"></div>
                <div class="print6"></div>
                <div class="clear"></div>
                
                <?php
                // 대신화물 처리부분
                try {
                    $stmh = $pdo->query($dsql);
                    $temp = $stmh->rowCount();
                    $total_row = $temp;
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $num = $row["num"];
                        $outdate = $row["outdate"];
                        $indate = $row["indate"];
                        $orderman = $row["orderman"];
                        $outworkplace = $row["outworkplace"];
                        $outputplace = $row["outputplace"];
                        $receiver = $row["receiver"];
                        $phone = $row["phone"];
                        $comment = $row["comment"];
                        $root = $row["root"];
                        $steel = $row["steel"];
                        $motor = $row["motor"];
                        $delivery = $row["delivery"];
                        ?>
                        
                        <div class="print1_1">대신</div>
                        <div class="print2"><?= htmlspecialchars($receiver, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print3"><?= htmlspecialchars(substr($comment, 0, 95), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print4"><?= htmlspecialchars(substr($outputplace, 0, 90), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print5"><?= htmlspecialchars(substr($phone, 0, 13), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="print6"><?= htmlspecialchars($delivery, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="clear"></div>
                        
                        <?php
                    }  // end of while
                } catch (PDOException $ex) {
                    error_log("대신화물 출고 리스트 조회 오류: " . $ex->getMessage());
                }
                ?>
            </div>  <!-- end of menu -->
        </div>
    </div>
</body>

</html>


