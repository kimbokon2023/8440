<?php require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 현재 연도 계산
$currentYear = date("Y");
$fromdate = "$currentYear-01-01";
$todate = "$currentYear-12-31";

// 해당 연도에 속한 데이터만 선택
$sql = "SELECT * FROM mirae8440.emeeting WHERE DATE(occur) >= :fromdate AND DATE(occur) <= :todate ORDER BY num DESC";

try {
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':fromdate', $fromdate, PDO::PARAM_STR);
    $stmh->bindValue(':todate', $todate, PDO::PARAM_STR);
    $stmh->execute();
    $total_row = $stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>

<div class="table-responsive">
    <table class="table table-hover" id="FixNGTable">
        <thead class="table-primary " style="vertical-align: middle;" >
            <tr>
                <th class="text-center w60px">번호</th>
                <th class="text-center">회의일시</th>                
                <th class="text-center">현장명(품명)</th>
                <th class="text-center">부적합 현상 및 불량내용</th>
                <th class="text-center">개선대책</th>
                <th class="text-center">참석자</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $start_num = $total_row;
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td class="text-center"><?= $start_num ?></td>
                    <td class="text-center"><?= $row['occur'] ?></td>                    
                    <td class="text-start"><?= $row['place'] ?></td>
                    <td class="text-start"><?= $row['content'] ?></td>
                    <td class="text-start"><?= $row['method'] ?></td>
                    <td class="text-start"><?= $row['emember'] ?></td>
                </tr>
                <?php
                $start_num--;
            }
            ?>
        </tbody>
    </table>
</div>
