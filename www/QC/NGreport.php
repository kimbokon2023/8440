<?php
include includePath('session.php');

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $_SESSION["url"] = $_SESSION["WebSite"] . 'error/index.php?user_name=' . $_SESSION["name"];
    header("Location:" . $_SESSION["WebSite"] . "login/login_form.php");
    exit;
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 연도 계산
$currentYear = date("Y");
$fromdate = "$currentYear-01-01";
$todate = "$currentYear-12-31";

// 해당 연도에 속한 데이터만 선택
$sql = "SELECT * FROM mirae8440.error WHERE occur >= :fromdate AND occur <= :todate ORDER BY num DESC";

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
    <table class="table table-hover" id="NGreportTable">
        <thead class="table-primary">
            <tr>
                <th class="text-center" style="width:5%;">번호</th>
                <th class="text-center" style="width:7%;">확인일</th>
                <th class="text-center" style="width:7%;">승인상태</th>
                <th class="text-center" style="width:15%;">현장명(품명)</th>
                <th class="text-center" style="width:5%;">보고자</th>
                <th class="text-center" style="width:30%;">발생원인(분석)</th>
                <th class="text-center">처리방안(개선사항)</th>
                <th class="text-center" style="width:7%;">관련 직원</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $start_num = $total_row;
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td class="text-center"><?= $start_num ?></td>
                    <td class="text-center"><?= $row['occurconfirm'] ?></td>
                    <td class="text-center"><?= $row['approve'] ?></td>
                    <td class=""><?= $row['place'] ?></td>
                    <td class="text-center"><?= $row['reporter'] ?></td>
                    <td class=""><?= $row['content'] ?></td>
                    <td class=""><?= $row['method'] ?></td>
                    <td class="text-center"><?= $row['involved'] ?></td>
                </tr>
                <?php
                $start_num--;
            }
            ?>
        </tbody>
    </table>
</div>
