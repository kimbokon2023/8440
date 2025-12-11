<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 페이징 설정
$scale = 100;       // 한 페이지에 보여질 게시글 수
$page_scale = 10;   // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;

// SQL 조건 구성
$a = " ORDER BY num DESC LIMIT {$first_num}, {$scale}";
$b = " ORDER BY num DESC";

// 기본 SQL Injection 방어
$search_safe = str_replace("'", "''", $search);

// SQL 쿼리 생성 (페이징 적용) - work 테이블 검색
$sql = "SELECT * FROM {$DB}.work WHERE " .
       "(workplacename LIKE '%{$search_safe}%') OR " .
       "(firstordman LIKE '%{$search_safe}%') OR " .
       "(secondordman LIKE '%{$search_safe}%') OR " .
       "(chargedman LIKE '%{$search_safe}%') OR " .
       "(delicompany LIKE '%{$search_safe}%') OR " .
       "(hpi LIKE '%{$search_safe}%') OR " .
       "(firstord LIKE '%{$search_safe}%') OR " .
       "(secondord LIKE '%{$search_safe}%') OR " .
       "(worker LIKE '%{$search_safe}%') OR " .
       "(memo LIKE '%{$search_safe}%')" . $a;

// SQL 쿼리 생성 (전체 개수 확인용)
$sqlcon = "SELECT * FROM {$DB}.work WHERE " .
          "(workplacename LIKE '%{$search_safe}%') OR " .
          "(firstordman LIKE '%{$search_safe}%') OR " .
          "(secondordman LIKE '%{$search_safe}%') OR " .
          "(chargedman LIKE '%{$search_safe}%') OR " .
          "(delicompany LIKE '%{$search_safe}%') OR " .
          "(hpi LIKE '%{$search_safe}%') OR " .
          "(firstord LIKE '%{$search_safe}%') OR " .
          "(secondord LIKE '%{$search_safe}%') OR " .
          "(worker LIKE '%{$search_safe}%') OR " .
          "(memo LIKE '%{$search_safe}%')" . $b;

// 데이터 조회
$total_row = 0;
$total_page = 0;
$current_page = 1;

try {
    // 전체 개수 확인
    $allstmh = $pdo->query($sqlcon);
    $total_row = $allstmh->rowCount();
    
    // 페이징된 데이터 조회
    $stmh = $pdo->query($sql);
    
    // 페이지 계산
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
} catch (PDOException $ex) {
    error_log("현장 검색 오류 (work 테이블): " . $ex->getMessage());
}
?>

<div class="input-group p-2 mb-1">
    <span class="input-group-text">▷ 총 <?= $total_row ?> 개. &nbsp; &nbsp;검색어 : <?= htmlspecialchars($search) ?></span>
    
    <?php
    if ($total_row == 0) {
        echo "&nbsp; <button type='button' id='search_directinput' class='button button2'>직접입력</button>";
    }
    ?>
</div>

<div class="input-group p-1 mb-1">
    <span class="input-group-text align-items-center" style="width:50px;">번호</span>
    <span class="input-group-text align-text-center" style="width:300px;">현장명</span>
    <span class="input-group-text text-center" style="width:110px;">미래소장</span>
</div>

<?php
// 시작 번호 계산
if ($page <= 1) {
    $start_num = $total_row;
} else {
    $start_num = $total_row - ($page - 1) * $scale;
}

// 검색 결과 출력
try {
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $workplacename = $row["workplacename"] ?? '';
        $worker = $row["worker"] ?? '';
        ?>
        <div class="input-group p-1 mb-0">
            <span class="input-group-text align-items-center" style="width:50px;"><?= $start_num ?></span>
            <span class="input-group-text align-text-center" style="width:300px;">
                <a href="#" onclick="javascript:intoval('<?= htmlspecialchars($workplacename, ENT_QUOTES) ?>'); return false;" style="font-size:10px;">
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
    error_log("검색 결과 출력 오류: " . $ex->getMessage());
}

// 페이지 구분 블록 계산
$start_page = ($current_page - 1) * $page_scale + 1;
$end_page = $start_page + $page_scale - 1;
?>

<div id="page_button">
    <div id="page_num">
        <?php
        // 이전 페이지 링크
        if ($page != 1 && $page > $page_scale) {
            $prev_page = $page - $page_scale;
            
            if ($prev_page <= 0) {
                $prev_page = 1;
            }
            
            $params = http_build_query(array(
                'page' => $prev_page,
                'search' => $search,
                'find' => $find,
                'list' => 1,
                'process' => $process,
                'yearcheckbox' => $yearcheckbox,
                'year' => $year
            ));
            
            echo "<a href='search_jamb.php?{$params}'>◀ </a>";
        }
        
        // 페이지 번호 목록
        for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
            if ($page == $i) {
                echo "<font color='red'><b>[{$i}]</b></font>";
            } else {
                $params = http_build_query(array(
                    'page' => $i,
                    'search' => $search,
                    'find' => $find,
                    'list' => 1,
                    'process' => $process,
                    'yearcheckbox' => $yearcheckbox,
                    'year' => $year
                ));
                
                echo "<a href='search_jamb.php?{$params}'>[{$i}]</a>";
            }
        }
        
        // 다음 페이지 링크
        if ($page < $total_page) {
            $next_page = $page + $page_scale;
            
            if ($next_page > $total_page) {
                $next_page = $total_page;
            }
            
            $params = http_build_query(array(
                'page' => $next_page,
                'search' => $search,
                'find' => $find,
                'list' => 1,
                'process' => $process,
                'yearcheckbox' => $yearcheckbox,
                'year' => $year
            ));
            
            echo "<a href='search_jamb.php?{$params}'>▶</a><p>";
        }
        ?>
    </div>
</div>

<script>
// ES5 호환 JavaScript
function intoval(name) {
    opener.document.getElementById("place").value = name;
    window.close();
}
</script>