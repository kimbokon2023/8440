<?php
// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// POST 변수 초기화
$con_num = isset($_POST["num"]) ? $_POST["num"] : 0;

// 변수 초기화
$font = "#000000"; // 기본 폰트 색상

try {
    // SQL 쿼리
    $sql = "select * from mirae8440.output where con_num = " . intval($con_num);
    
    $stmh = $pdo->query($sql);
    $temp = $stmh->rowCount();
    
?>

<div class="aa">
    <div class="aa1"> 공사번호 </div>
    <div class="aa2"> 출고일자 </div>
    <div class="aa3"> 접 수 일 </div>
    <div class="aa4"> 내역/코멘트 </div>
</div>
<br><br>

<?php
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // 데이터 추출
        $item_num = $row["num"];
        $con_num = $row["con_num"];
        $outdate = $row["outdate"];
        $item_indate = $row["indate"];
        $item_orderman = $row["orderman"];
        $item_outworkplace = $row["outworkplace"];
        $item_outputplace = $row["outputplace"];
        $item_receiver = $row["receiver"];
        $item_phone = $row["phone"];
        $item_comment = $row["comment"];
        
        // 출고일자에 요일 추가
        if ($outdate != "") {
            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
            $outdate = $outdate . $week[date('w', strtotime($outdate))];
        }
        
?>
        <div id="bb">
            <a href="outputview.php?num=<?=$item_num?>" target="_blank">
                <div id="bb1">
                    <b><?=$con_num?></b>
                </div>
                <div id="bb2" style="color:<?=$font?>;">
                    <b><?=substr($outdate, 0, 15)?></b>
                </div>
                <div id="bb3">
                    <b><?=substr($item_indate, 0, 10)?></b>
                </div>
                <div id="bb4">
                    <b><?=substr($item_comment, 0, 100)?></b>
                </div>
            </a>
            <br><br>
        </div>
<?php
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>
