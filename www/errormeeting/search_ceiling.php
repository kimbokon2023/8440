<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('lib/mydb.php'));

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';

// 페이징 설정
$scale = 100;       // 한 페이지에 보여질 게시글 수
$page_scale = 10;   // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;

// 데이터베이스 연결
$pdo = db_connect();
$DB = 'mirae8440';

// 변수 초기화
$numCeiling = '';
$workplacename = '';
$worker = '';
$total_row = 0;
$total_page = 0;
$current_page = 0;
$start_num = 0;
$start_page = 1;
$end_page = 1;

// SQL Injection 방어
$search_safe = str_replace("'", "''", $search);

// SQL 쿼리 생성
$sql = "SELECT * FROM {$DB}.ceiling WHERE " .
       "(workplacename LIKE '%{$search_safe}%') OR " .
       "(firstordman LIKE '%{$search_safe}%') OR " .
       "(secondordman LIKE '%{$search_safe}%') OR " .
       "(chargedman LIKE '%{$search_safe}%') OR " .
       "(delicompany LIKE '%{$search_safe}%') OR " .
       "(hpi LIKE '%{$search_safe}%') OR " .
       "(firstord LIKE '%{$search_safe}%') OR " .
       "(secondord LIKE '%{$search_safe}%') OR " .
       "(worker LIKE '%{$search_safe}%') OR " .
       "(memo LIKE '%{$search_safe}%') " .
       "ORDER BY num DESC LIMIT {$first_num}, {$scale}";

$sqlcon = "SELECT * FROM {$DB}.ceiling WHERE " .
          "(workplacename LIKE '%{$search_safe}%') OR " .
          "(firstordman LIKE '%{$search_safe}%') OR " .
          "(secondordman LIKE '%{$search_safe}%') OR " .
          "(chargedman LIKE '%{$search_safe}%') OR " .
          "(delicompany LIKE '%{$search_safe}%') OR " .
          "(hpi LIKE '%{$search_safe}%') OR " .
          "(firstord LIKE '%{$search_safe}%') OR " .
          "(secondord LIKE '%{$search_safe}%') OR " .
          "(worker LIKE '%{$search_safe}%') OR " .
          "(memo LIKE '%{$search_safe}%') " .
          "ORDER BY num DESC";

try {
    // 전체 개수 조회
    $allstmh = $pdo->query($sqlcon);
    $total_row = $allstmh->rowCount();
    
    // 페이징된 결과 조회
    $stmh = $pdo->query($sql);
    
    // 페이징 계산
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
    
    // 시작 번호 계산
    if ($page <= 1) {
        $start_num = $total_row;
    } else {
        $start_num = $total_row - ($page - 1) * $scale;
    }
} catch (PDOException $ex) {
    error_log("천정 검색 오류: " . $ex->getMessage());
    $total_row = 0;
}

?>

<div class="input-group p-2 mb-1">
    <span class="input-group-text">
        ▷ 총 <?= $total_row ?> 개. &nbsp; &nbsp;검색어 : <?= htmlspecialchars($search) ?>
    </span>
    
    <?php if ($total_row == 0) { ?>
        &nbsp; <button type="button" id="search_directinput" class="button button2">직접입력</button>
    <?php } ?>
</div>

<div class="input-group p-1 mb-1">
    <span class="input-group-text align-items-center" style="width:50px;">번호</span>
    <span class="input-group-text align-text-center" style="width:300px;">현장명</span>
    <span class="input-group-text text-center" style="width:110px;">미래소장</span>
</div>

<?php
try {
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $numCeiling = $row["num"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $worker = $row["worker"] ?? '';
?>
        <div class="input-group p-1 mb-0">
            <span class="input-group-text align-items-center" style="width:50px;">
                <?= $start_num ?>
            </span>
            <span class="input-group-text align-text-center" style="width:300px;">
                <a href="#" onclick="intoval('<?= htmlspecialchars($workplacename, ENT_QUOTES) ?>'); return false;" style="font-size:10px;">
                    <?= htmlspecialchars(iconv_substr($workplacename, 0, 20, "utf-8")) ?>&nbsp;
                </a>
            </span>
            <span class="input-group-text text-center" style="width:110px;">
                <?= htmlspecialchars(substr($worker, 0, 10)) ?>&nbsp;
            </span>
        </div>
<?php
        $start_num--;
    }
} catch (PDOException $ex) {
    error_log("레코드 조회 오류: " . $ex->getMessage());
}

// 페이지 구분 블럭 계산
$start_page = ($current_page - 1) * $page_scale + 1;
$end_page = $start_page + $page_scale - 1;
?>

<div id="page_button">
    <div id="page_num">
        <?php
        // URL 파라미터 생성
        $params = array(
            'search' => $search,
            'find' => $find,
            'list' => 1,
            'process' => $process,
            'yearcheckbox' => $yearcheckbox,
            'year' => $year
        );
        
        // 이전 페이지 블록
        if ($page != 1 && $page > $page_scale) {
            $prev_page = $page - $page_scale;
            if ($prev_page <= 0) {
                $prev_page = 1;
            }
            
            $params['page'] = $prev_page;
            $url = 'search_ceiling.php?' . http_build_query($params);
            echo '<a href="' . htmlspecialchars($url) . '">◀</a> ';
        }
        
        // 페이지 번호 목록
        for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
            $params['page'] = $i;
            $url = 'search_ceiling.php?' . http_build_query($params);
            
            if ($page == $i) {
                echo '<span style="color:red;"><b>[' . $i . ']</b></span> ';
            } else {
                echo '<a href="' . htmlspecialchars($url) . '">[' . $i . ']</a> ';
            }
        }
        
        // 다음 페이지 블록
        if ($page < $total_page) {
            $next_page = $page + $page_scale;
            if ($next_page > $total_page) {
                $next_page = $total_page;
            }
            
            $params['page'] = $next_page;
            $url = 'search_ceiling.php?' . http_build_query($params);
            echo '<a href="' . htmlspecialchars($url) . '">▶</a>';
        }
        ?>
    </div>
</div>

<script>
// ES5 호환 JavaScript
function intoval(name) {
    if (window.opener && window.opener.document.getElementById("place")) {
        window.opener.document.getElementById("place").value = name;
    }
    window.close();
}
</script>