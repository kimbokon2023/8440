<?php
/**
 * 회원 정보 관리 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$WebSite = $protocol . "://" . $host;

// 세션 변수 안전하게 가져오기
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 확인
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location: {$WebSite}/login/logout.php");
    exit;
}

// 요청 변수 초기화
$page = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : '';
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : '';
$sort_field = isset($_REQUEST["sort_field"]) ? $_REQUEST["sort_field"] : 'id';
$sort_order = isset($_REQUEST["sort_order"]) ? $_REQUEST["sort_order"] : 'desc';

// 허용된 정렬 필드 (SQL Injection 방지)
$allowed_sort_fields = ['id', 'name', 'division', 'part', 'position', 'hp', 'level', 'numorder', 'eworks_level'];
if (!in_array($sort_field, $allowed_sort_fields)) {
    $sort_field = 'id';
}

// 허용된 정렬 순서
$allowed_sort_orders = ['asc', 'desc'];
if (!in_array(strtolower($sort_order), $allowed_sort_orders)) {
    $sort_order = 'desc';
}

// 페이지 설정
$scale = 50;
$page_scale = 10;
$first_num = ($page - 1) * $scale;

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 테이블명
$tablename = "member";

// SQL 쿼리 생성
$order_clause = " ORDER BY {$sort_field} {$sort_order}";

$sql = "";
$sqlcon = "";
$params = [];
$params_con = [];

if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM {$DB}.{$tablename} 
            WHERE id LIKE ? OR name LIKE ? OR nick LIKE ? 
            {$order_clause} 
            LIMIT ?, ?";
    $sqlcon = "SELECT * FROM {$DB}.{$tablename} 
               WHERE id LIKE ? OR name LIKE ? OR nick LIKE ? 
               {$order_clause}";
    $searchParam = "%{$search}%";
    $params = [$searchParam, $searchParam, $searchParam, $first_num, $scale];
    $params_con = [$searchParam, $searchParam, $searchParam];
} else {
    $sql = "SELECT * FROM {$DB}.{$tablename} {$order_clause} LIMIT ?, ?";
    $sqlcon = "SELECT * FROM {$DB}.{$tablename} {$order_clause}";
    $params = [$first_num, $scale];
    $params_con = [];
}

// 변수 초기화
$member_list = [];
$total_row = 0;
$result_count = 0;

// 쿼리 실행
try {
    // 디버깅: SQL 쿼리 확인
    error_log("DEBUG - SQL: " . $sql);
    error_log("DEBUG - Params: " . print_r($params, true));
    error_log("DEBUG - DB: " . $DB);
    error_log("DEBUG - Table: " . $tablename);
    
    // === 임시 디버깅: 화면에 출력 ===
    echo "<!-- DEBUG INFO -->";
    echo "<!-- DB: {$DB} -->";
    echo "<!-- Table: {$tablename} -->";
    echo "<!-- Mode: {$mode} -->";
    echo "<!-- Search: {$search} -->";
    
    // 전체 레코드 수 (COUNT 사용)
    if ($mode == "search" && !empty($search)) {
        $count_sql = "SELECT COUNT(*) as total FROM {$DB}.{$tablename} 
                      WHERE id LIKE ? OR name LIKE ? OR nick LIKE ?";
        echo "<!-- COUNT SQL (SEARCH): {$count_sql} -->";
        $count_stmh = $pdo->prepare($count_sql);
        $searchParam = "%{$search}%";
        $count_stmh->execute([$searchParam, $searchParam, $searchParam]);
    } else {
        $count_sql = "SELECT COUNT(*) as total FROM {$DB}.{$tablename}";
        echo "<!-- COUNT SQL: {$count_sql} -->";
        $count_stmh = $pdo->query($count_sql);
    }
    $count_result = $count_stmh->fetch(PDO::FETCH_ASSOC);
    echo "<!-- COUNT RESULT: " . print_r($count_result, true) . " -->";
    $total_row = (int)($count_result['total'] ?? 0);
    
    echo "<!-- TOTAL ROW: {$total_row} -->";
    error_log("DEBUG - Total rows (COUNT): " . $total_row);
    
    // 페이지별 레코드 조회 (배열로 받기)
    if ($mode == "search" && !empty($search)) {
        $searchParam = "%{$search}%";
        $sql_final = "SELECT * FROM {$DB}.{$tablename} 
                      WHERE id LIKE ? OR name LIKE ? OR nick LIKE ? 
                      {$order_clause} 
                      LIMIT ?, ?";
        $stmh = $pdo->prepare($sql_final);
        $stmh->bindValue(1, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(4, $first_num, PDO::PARAM_INT);
        $stmh->bindValue(5, $scale, PDO::PARAM_INT);
        $stmh->execute();
        echo "<!-- SEARCH SQL: {$sql_final} -->";
    } else {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $first_num, PDO::PARAM_INT);
        $stmh->bindValue(2, $scale, PDO::PARAM_INT);
        $stmh->execute();
        echo "<!-- NORMAL SQL: {$sql} -->";
    }
    
    // 배열로 전부 받기 - 이것이 핵심!
    $member_list = $stmh->fetchAll(PDO::FETCH_ASSOC);
    $result_count = count($member_list);
    
    echo "<!-- FETCHED ROWS: {$result_count} -->";
    echo "<!-- FIRST MEMBER: " . print_r($member_list[0] ?? 'EMPTY', true) . " -->";
    error_log("DEBUG - Result count: " . $result_count);
    
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
    
} catch (PDOException $ex) {
    error_log("회원 목록 조회 오류: " . $ex->getMessage());
    error_log("DEBUG - SQL that failed: " . $sql);
    echo "<div class='container mt-5'>";
    echo "<div class='alert alert-danger'>";
    echo "<h4>오류: 데이터를 불러오는 중 문제가 발생했습니다.</h4>";
    echo "<p>상세: " . htmlspecialchars($ex->getMessage()) . "</p>";
    echo "<hr>";
    echo "<p class='small'>SQL: " . htmlspecialchars($sql) . "</p>";
    echo "<p><a href='test_query.php' class='btn btn-primary'>테스트 페이지에서 확인하기</a></p>";
    echo "</div></div>";
    exit;
}

include getDocumentRoot() . '/load_header.php';

?>

<title>미래기업 회원관리</title>

<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
    }
    .sortable-header {
        cursor: pointer;
        user-select: none;
        position: relative;
    }
    .sortable-header:hover {
        background-color: #dee2e6;
    }
    .sort-icon {
        margin-left: 5px;
        opacity: 0.5;
    }
    .sort-icon.active {
        opacity: 1;
        color: #0d6efd;
    }
</style>
</head>
<body>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="sort_field" id="sort_field" value="<?= htmlspecialchars($sort_field, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="sort_order" id="sort_order" value="<?= htmlspecialchars($sort_order, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">
    
    <div class="container justify-content-center">
        <div class="d-flex mt-2 mb-1 justify-content-center">
            <span class="text-secondary fs-5">&nbsp;&nbsp; 회원 정보관리 &nbsp;&nbsp;</span>
            <button type="button" class="btn btn-dark btn-sm mx-3" onclick='location.reload();' title="새로고침">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        
        <div class="d-flex mt-1 mb-1 justify-content-center">
            <div class="input-group p-2 mb-2 justify-content-center align-items-center">
                <span class="badge bg-secondary me-2">총 <?= $total_row ?>명</span>
                <button type="button" class="btn btn-dark btn-sm me-2" onclick="popupCenter('write_form.php?id=null', '회원 등록', 800, 500);return false;">등록</button>
                <button type="button" class="btn btn-dark btn-sm me-2" onclick="popupCenter('setline.php?id=null', '결재라인 등록', 600, 400);return false;">결재라인 등록</button>
                <input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" size="30" autocomplete="off" onkeydown="SearchEnter();" placeholder="검색어 입력">
                <button type="button" id="searchBtn" class="btn btn-dark"><i class="bi bi-search"></i></button>
            </div>
        </div>
        
        <div class="row d-flex">
            <table class="table table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th class="text-center">번호</th>
                        <th class="text-center sortable-header" onclick="sortTable('division')">
                            division
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'division' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'division' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('name')">
                            이름
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'name' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'name' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('part')">
                            파트
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'part' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'part' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('position')">
                            position
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'position' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'position' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('id')">
                            ID
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'id' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'id' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center">P/W</th>
                        <th class="text-center sortable-header" onclick="sortTable('hp')">
                            전번
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'hp' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'hp' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('level')">
                            레벨
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'level' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'level' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('numorder')">
                            numorder
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'numorder' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'numorder' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                        <th class="text-center sortable-header" onclick="sortTable('eworks_level')">
                            eworks_level
                            <i class="bi bi-arrow-up sort-icon <?= ($sort_field == 'eworks_level' && $sort_order == 'asc') ? 'active' : '' ?>"></i>
                            <i class="bi bi-arrow-down sort-icon <?= ($sort_field == 'eworks_level' && $sort_order == 'desc') ? 'active' : '' ?>"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $start_num = ($page == 1) ? $total_row : $total_row - ($page - 1) * $scale;
                    $row_count = 0;
                    
                    // 배열로 받은 데이터를 foreach로 처리
                    foreach ($member_list as $row) {
                        $row_count++;
                        
                        // _row.php의 변수 할당을 직접 여기서 처리
                        $id = $row["id"] ?? '';
                        $pass = $row["pass"] ?? '';
                        $name = $row["name"] ?? '';
                        $level = $row["level"] ?? '';
                        $part = $row["part"] ?? '';
                        $hp = $row["hp"] ?? '';
                        $numorder = $row["numorder"] ?? '';
                        $position = $row["position"] ?? '';
                        $eworks_level = $row["eworks_level"] ?? '';
                        $division = $row["division"] ?? '';
                        ?>
                        <tr onclick="redirectToView('<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>')">
                            <td class="text-center"><?= $start_num ?></td>
                            <td class="text-center"><?= htmlspecialchars($division, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($part, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($position, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center">
                                <input type="password" name="password" value="<?= htmlspecialchars($pass, ENT_QUOTES, 'UTF-8') ?>" disabled>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($hp, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($level, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($numorder, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center"><?= htmlspecialchars($eworks_level, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <?php
                        $start_num--;
                    }
                    
                    // 데이터가 없을 때 메시지 표시
                    if ($row_count == 0) {
                        ?>
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-3">표시할 데이터가 없습니다.</p>
                                    <?php if ($mode == "search" && !empty($search)) { ?>
                                        <p class="small">검색어: <strong><?= htmlspecialchars($search) ?></strong></p>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.href='list.php'">전체 목록 보기</button>
                                    <?php } else { ?>
                                        <p class="small">등록 버튼을 눌러 회원을 추가하세요.</p>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="row row-cols-auto mt-5 justify-content-center align-items-center">
            <?php
            $start_page = ($current_page - 1) * $page_scale + 1;
            $end_page = $start_page + $page_scale - 1;
            
            if ($page != 1 && $page > $page_scale) {
                $prev_page = $page - $page_scale;
                if ($prev_page <= 0) $prev_page = 1;
                echo '<button class="btn btn-outline-secondary btn-sm" type="button" id="previousListBtn" onclick="movetoPage(' . $prev_page . ')">◀</button>&nbsp;';
            }
            
            for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                if ($page == $i) {
                    echo '<span class="text-secondary">' . $i . '</span>&nbsp;';
                } else {
                    echo '<button class="btn btn-outline-secondary btn-sm" type="button" id="moveListBtn" onclick="movetoPage(' . $i . ')">' . $i . '</button>&nbsp;';
                }
            }
            
            if ($page < $total_page) {
                $next_page = $page + $page_scale;
                if ($next_page > $total_page) $next_page = $total_page;
                echo '<button class="btn btn-outline-secondary btn-sm" type="button" id="nextListBtn" onclick="movetoPage(' . $next_page . ')">▶</button>&nbsp;';
            }
            ?>
        </div>
    </div>
</form>

<script type="text/javascript">
(function() {
    'use strict';
    
    window.redirectToView = function(id) {
        if (typeof popupCenter !== 'undefined') {
            popupCenter('write_form.php?id=' + encodeURIComponent(id), '회원정보 수정', 800, 550);
        } else {
            window.location.href = 'write_form.php?id=' + encodeURIComponent(id);
        }
    };
    
    window.sortTable = function(field) {
        var currentSortField = $("#sort_field").val();
        var currentSortOrder = $("#sort_order").val();
        
        var newOrder;
        if (currentSortField === field) {
            newOrder = (currentSortOrder === 'asc') ? 'desc' : 'asc';
        } else {
            newOrder = 'desc';
        }
        
        $("#sort_field").val(field);
        $("#sort_order").val(newOrder);
        $("#page").val('1');
        
        $("#board_form").submit();
    };
    
    window.movetoPage = function(page) {
        $("#page").val(page);
        $("#sort_field").val(<?= json_encode($sort_field, JSON_UNESCAPED_UNICODE) ?>);
        $("#sort_order").val(<?= json_encode($sort_order, JSON_UNESCAPED_UNICODE) ?>);
        $("#board_form").submit();
    };
    
    window.SearchEnter = function() {
        if (event.keyCode == 13) {
            $("#page").val('1');
            document.getElementById('board_form').submit();
        }
    };
    
    $(document).ready(function() {
        $("#searchBtn").click(function() {
            $("#page").val('1');
            document.getElementById('board_form').submit();
        });
    });
    
})();
</script>
</body>
</html>
