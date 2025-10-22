<!DOCTYPE HTML>
<html>

<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 작업(쟘) 검색 페이지
 * 
 * 현장명, 발주처, 재질 등으로 검색 가능
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 변수 초기화
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';
$page = isset($_REQUEST["page"]) ? intval($_REQUEST["page"]) : 1;
$mode = $_REQUEST["mode"] ?? '';

// 페이지네이션 설정
$scale = 1000;       // 한 페이지에 보여질 게시글 수
$page_scale = 10;    // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

// 검색어가 비어있으면 종료
if (empty($search)) {
    echo "<p>검색어를 입력해주세요.</p>";
    exit;
}

// SQL 쿼리 준비 (SQL 인젝션 방지를 위해 prepared statement 사용)
$searchParam = '%' . $search . '%';

$sql = "select * from " . $DB . ".work 
        where (workplacename like ? 
            or firstordman like ? 
            or secondordman like ? 
            or chargedman like ? 
            or delicompany like ? 
            or hpi like ? 
            or firstord like ? 
            or secondord like ? 
            or worker like ? 
            or memo like ?)
        order by num desc 
        limit ?, ?";

$sqlcon = "select * from " . $DB . ".work 
           where (workplacename like ? 
               or firstordman like ? 
               or secondordman like ? 
               or chargedman like ? 
               or delicompany like ? 
               or hpi like ? 
               or firstord like ? 
               or secondord like ? 
               or worker like ? 
               or memo like ?)
           order by num desc";

try {
    // 전체 개수 조회
    $allstmh = $pdo->prepare($sqlcon);
    for ($i = 1; $i <= 10; $i++) {
        $allstmh->bindValue($i, $searchParam, PDO::PARAM_STR);
    }
    $allstmh->execute();
    $temp2 = $allstmh->rowCount();
    
    // 페이지별 데이터 조회
    $stmh = $pdo->prepare($sql);
    for ($i = 1; $i <= 10; $i++) {
        $stmh->bindValue($i, $searchParam, PDO::PARAM_STR);
    }
    $stmh->bindValue(11, $first_num, PDO::PARAM_INT);
    $stmh->bindValue(12, $scale, PDO::PARAM_INT);
    $stmh->execute();
    $temp1 = $stmh->rowCount();
    
    // 전체 글수 및 페이지 계산
    $total_row = $temp2;
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
?>

<form name="board_form" method="post" action="search.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>&find=<?= htmlspecialchars($find, ENT_QUOTES, 'UTF-8') ?>&process=<?= htmlspecialchars($process, ENT_QUOTES, 'UTF-8') ?>&yearcheckbox=<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>&year=<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>">

<div class="container">
    <div class="d-flex">
        <div id="list_search1" style="width:500px;">
            ▷ <?= $total_row ?> 자료. &nbsp; &nbsp;검색어 : <?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>
            <?php if ($total_row == 0) { ?>
                <button type="button" id="search_directinput" class="btn btn-dark btn-sm">직접입력</button>
            <?php } ?>
        </div>
    </div> <!-- end of list_search1 -->

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th class="text-center" style="width:50px;">번호</th>
                    <th class="text-center" style="width:200px;">현장명</th>
                    <th class="text-center" style="width:100px;">발주처</th>
                    <th class="text-center" style="width:100px;">재질</th>
                </tr>
            </thead>
            <tbody>

<?php
    // 시작 번호 계산
    if ($page <= 1) {
        $start_num = $total_row;
    } else {
        $start_num = $total_row - ($page - 1) * $scale;
    }

    // 검색 결과 출력
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '../work/_row.php';

        // 재질 정보 조합
        $materials1 = ($material1 ?? '') . " " . ($material2 ?? '');
        $materials2 = ($material3 ?? '') . " " . ($material4 ?? '');
        $materials3 = ($material5 ?? '') . " " . ($material6 ?? '');
        $materials = trim($materials1) . trim($materials2) . trim($materials3);
?>
                <tr onclick="intoval('<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>','<?= htmlspecialchars($worker, ENT_QUOTES, 'UTF-8') ?>'); return false;">
                    <td class="text-center"><?= $start_num ?></td>
                    <td class="text-start"><?= htmlspecialchars(substr($workplacename, 0, 80), ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-center"><?= htmlspecialchars(substr($secondord, 0, 35), ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-center"><?= htmlspecialchars($materials, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
<?php
        $start_num--;
    }
} catch (PDOException $Exception) {
    error_log("검색 오류: " . $Exception->getMessage());
    echo "<tr><td colspan='4' class='text-center text-danger'>검색 중 오류가 발생했습니다.</td></tr>";
}
?>

            </tbody>
        </table>
    </div>
</div>
</form>

<script>
// 검색 결과 선택 시 폼에 값 입력
function intoval(name, worker) {
    $("#displaysearch").hide();
    document.getElementById("outworkplace").value = name;
    document.getElementById("model").value = '쟘';
    document.getElementById("comment").value = worker + ' 소장,';
}

// 직접입력 버튼 클릭 시
$("#search_directinput").on("click", function() {
    $("#displaysearch").hide();
});
</script>

</body>
</html>