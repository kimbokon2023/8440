<?php
require_once __DIR__ . '/../bootstrap.php';

include includePath('load_header.php');
?>

<!-- Link to consolidated dashboard style -->
<link rel="stylesheet" href="../css/dashboard-style.css">

<title> 미래기업 jamb 소장 VOC </title>

<style>
/* Light & Subtle theme customizations for VOC list page */

body {
    background: white;
    overflow-x: hidden;
}

/* Use modern-management-card from dashboard-style.css */
.glass-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    padding: 1.5rem;
    margin-bottom: 2rem;
    transition: all 0.2s ease;
}

.glass-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.section-header {
    background: var(--dashboard-secondary);
    color: #000;
    border-radius: 12px 12px 0 0;
    padding: 0.25rem;
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
    text-align: center;
    font-size: 0.9rem;
    font-weight: 500;
}

.section-title {
    color: #000 !important;
    font-weight: 500;
    margin: 0;
}

.btn-custom {
    background: var(--gradient-accent);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    font-weight: 400;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1);
}

.btn-custom:hover {
    background: var(--gradient-accent);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(51, 65, 85, 0.15);
    opacity: 0.9;
}

.table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border-collapse: collapse;
    margin: 0;
}

.table-primary {
    background: #f0fbff;
}

.table-primary th {
    background: #f0fbff;
    color: var(--dashboard-text);
    border: none;
    font-weight: 500;
    font-size: 0.8rem;
    padding: 0.5rem;
    text-align: center;
}

.table td {
    padding: 0.5rem;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--dashboard-border);
    transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover {
    cursor: pointer;
    background-color: var(--dashboard-hover);
    transition: background-color 0.2s ease;
}

.form-control {
    border: 2px solid var(--dashboard-border);
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.9);
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
    background: white;
}

.btn-outline-primary {
    border-color: var(--dashboard-accent);
    color: var(--dashboard-accent) !important;
    font-weight: 500;
    background-color: rgba(255, 255, 255, 0.9);
    border-width: 2px;
}

.btn-outline-primary:hover {
    background-color: var(--dashboard-accent);
    border-color: var(--dashboard-accent);
    color: var(--btn-hover-text) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(51, 65, 85, 0.15);
}

.btn-outline-primary:focus {
    border-color: var(--dashboard-accent);
    color: var(--dashboard-accent) !important;
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
}

.btn-outline-secondary {
    border-color: var(--dashboard-accent);
    color: var(--dashboard-accent) !important;
    font-weight: 500;
    background-color: rgba(255, 255, 255, 0.8);
}

.btn-outline-secondary:hover {
    background-color: var(--dashboard-accent);
    border-color: var(--dashboard-accent);
    color: var(--btn-hover-text) !important;
    transform: translateY(-1px);
}

.btn-outline-secondary:focus {
    border-color: var(--dashboard-accent);
    color: var(--dashboard-accent) !important;
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
}

.btn-dark {
    background: var(--gradient-accent);
    border: none;
    color: var(--btn-text-on-accent) !important;
    font-weight: 500;
    box-shadow: 0 1px 3px rgba(51, 65, 85, 0.2);
}

.btn-dark:hover {
    background: var(--gradient-accent);
    opacity: 0.9;
    color: var(--btn-hover-text) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(51, 65, 85, 0.25);
}

.btn-dark:focus {
    color: var(--btn-text-on-accent) !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
}

.text-secondary {
    color: var(--dashboard-text) !important;
    font-weight: 600;
    background-color: var(--dashboard-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid var(--dashboard-border);
}

.blinking {
    animation: blink 1.2s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

.pagination-container {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    box-shadow: var(--dashboard-shadow);
}

/* Enhanced pagination button visibility */
.pagination-container .btn {
    margin: 0.1rem;
    min-width: 2.5rem;
}

.pagination-container .text-secondary {
    background-color: var(--dashboard-accent);
    color: var(--btn-text-on-accent) !important;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    display: inline-block;
    min-width: 2.5rem;
    text-align: center;
}

/* Clear button styling for better visibility */
.btnClear {
    background: var(--dashboard-border);
    border: 1px solid var(--dashboard-accent);
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    margin-left: 0.25rem;
    color: var(--dashboard-text);
    cursor: pointer;
    transition: all 0.2s ease;
}

.btnClear:hover {
    background: var(--dashboard-accent);
    color: var(--btn-text-on-accent);
    transform: translateY(-1px);
}

/* Input wrapper improvements */
.inputWrap {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Enhanced button spacing and visibility */
.d-flex .btn {
    margin: 0.1rem 0.2rem;
}

/* Status text improvements */
.blinking {
    font-weight: 600;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    background-color: rgba(239, 68, 68, 0.1);
}

@media (max-width: 768px) {
    .glass-container {
        padding: 1rem;
    }

    .section-header {
        padding: 0.5rem;
        font-size: 0.8rem;
    }

    .btn-custom {
        display: block;
        width: 100%;
        margin: 0.25rem 0;
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }

    .table th,
    .table td {
        padding: 0.3rem;
        font-size: 0.75rem;
    }
}
</style>

 </head>

<body>

<?php require_once(includePath('myheader.php')); ?>

<form id="board_form" name="board_form" method="post" action="list.php">

<div class="container">

<div class="glass-container modern-management-card">
<div class="section-header modern-dashboard-header">
	<h4 class="section-title text-center">jamb 시공소장 VOC</h4>
</div>
<?php

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
$base_url = getBaseUrl();

// Request 변수 안전하게 초기화
$page = $_REQUEST["page"] ?? 1;
$choice = $_REQUEST["choice"] ?? 1;
$mode = $_REQUEST["mode"] ?? "";
$search = $_REQUEST["search"] ?? "";
$find = $_REQUEST["find"] ?? "";

// Hidden input 변수 초기화
$voc_alert = "";
$ma_alert = "";
$order_alert = "";

// 페이지 설정
$scale = 50;       // 한 페이지에 보여질 게시글 수
$page_scale = 10;   // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

if ($mode == "search") {
    if (!$search) {
        $sql = "SELECT * FROM mirae8440.voc WHERE content <> '' ORDER BY num DESC";
        $sqlcon = "SELECT * FROM mirae8440.voc WHERE content <> '' ORDER BY num DESC";
    } else {
        $sql = "SELECT * FROM mirae8440.voc WHERE (content <> '') AND ((content LIKE '%$search%') OR (name LIKE '%$search%') OR (subject LIKE '%$search%')) ORDER BY num DESC LIMIT $first_num, $scale";
        $sqlcon = "SELECT * FROM mirae8440.voc WHERE (content <> '') AND ((content LIKE '%$search%') OR (name LIKE '%$search%') OR (subject LIKE '%$search%')) ORDER BY num DESC";
    }
} else {
    $sql = "SELECT * FROM mirae8440.voc WHERE content <> '' ORDER BY num DESC LIMIT $first_num, $scale";
    $sqlcon = "SELECT * FROM mirae8440.voc WHERE content <> '' ORDER BY num DESC";
}

switch ($choice) {
    case '2':
        $sql = "SELECT * FROM mirae8440.voc WHERE content <> '' AND is_html = '1' ORDER BY num DESC LIMIT $first_num, $scale";
        $sqlcon = "SELECT * FROM mirae8440.voc WHERE content <> '' AND is_html = '1' ORDER BY num DESC";
        break;
    case '3':
        $sql = "SELECT * FROM mirae8440.voc WHERE content <> '' AND is_html = '2' ORDER BY num DESC LIMIT $first_num, $scale";
        $sqlcon = "SELECT * FROM mirae8440.voc WHERE content <> '' AND is_html = '2' ORDER BY num DESC";
        break;
}

// 전체 레코드수를 파악한다.
try {
    $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
    $temp2 = $allstmh->rowCount();
    $stmh = $pdo->query($sql);               // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();

    $total_row = $temp2;     // 전체 글수

    $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
    $current_page = ceil($page / $page_scale); //현재 페이지 블록 위치계산

?>
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?= $voc_alert ?>" size="5">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?= $ma_alert ?>" size="5">
    <input type="hidden" id="order_alert" name="order_alert" value="<?= $order_alert ?>" size="5">
    <div id="vacancy" style="display:none"></div>

    <input type="hidden" id="page" name="page" value="<?= $page ?>">
    <input type="hidden" id="choice" name="choice" value="<?= $choice ?>">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="search" name="search" value="<?= $search ?>">
    <input type="hidden" id="find" name="find" value="<?= $find ?>">

    <div class="d-flex mt-2 mb-2 justify-content-center">
        ▷ 총 <?= $total_row ?> 개 자료.   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        <button type="button" class="btn btn-dark btn-sm modern-toolbar-btn" onclick="location.href='list.php?choice=1'">전체</button> &nbsp;
        <button type="button" class="btn btn-dark btn-sm modern-toolbar-btn" onclick="location.href='list.php?choice=2'">접수중</button> &nbsp;
        <button type="button" class="btn btn-dark btn-sm modern-toolbar-btn" onclick="location.href='list.php?choice=3'">확인완료</button> &nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="inputWrap me-1">
            <input type="text" id="search" name="search" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" class="form-control" style="width:150px;"> &nbsp;
            <button class="btnClear" onclick="document.getElementById('search').value=''; document.getElementById('board_form').submit();" title="검색어 지우기">✕</button>
        </div>
        &nbsp;
        <button id="searchBtn" type="button" class="btn btn-dark btn-sm modern-toolbar-btn"><i class="bi bi-search"></i> 검색</button>
    </div>

    <div class="d-flex justify-content-center">
        <table class="table table-hover modern-dashboard-table">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">번호</th>
                    <th class="text-center">현장명</th>
                    <th class="text-center">협의 내용</th>
                    <th class="text-center">작성자</th>
                    <th class="text-center">등록일</th>
                    <th class="text-center">처리상황</th>
                </tr>
            </thead>
            <tbody>


<?php
    if ($page == 1) {
        $start_num = $total_row;    // 페이지당 표시되는 첫번째 글순번
    } else {
        $start_num = $total_row - ($page - 1) * $scale;
    }

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $item_num = $row["num"];
        $item_id = $row["id"];
        $item_name = $row["name"];
        $is_html = $row["is_html"];
        $item_date = $row["regist_day"];
        $item_date = substr($item_date, 0, 10);
        $item_subject = iconv_substr($row["subject"], 0, 30, "utf-8");
        $item_content = iconv_substr($row["content"], 0, 30, "utf-8");

        require_once("../lib/mydb.php");
        $pdo = db_connect();

        $sql = "SELECT * FROM mirae8440.voc_ripple WHERE parent = $item_num";
        $stmh1 = $pdo->query($sql);
        $num_ripple = $stmh1->rowCount();

?>

                <tr onclick="redirectToView('<?= $item_num ?>', '<?= $page ?>')" class="clickable-row">
                    <td class="text-center"><?= $start_num ?></td>
                    <td class="text-center"><?= $item_subject ?></td>
                    <td><?= $item_content ?></td>
                    <td class="text-center"><?= $item_name ?></td>
                    <td class="text-center"><?= $item_date ?></td>
                    <td class="text-center">
                        <?php
                        if ($is_html == '1') {
                            print '<span class="blinking" style="color:red">접수 중</span>';
                        } else {
                            print "확인완료";
                        }
                        ?>
                    </td>
                </tr>

<?php
        $start_num--;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
$start_page = ($current_page - 1) * $page_scale + 1;
// 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
$end_page = $start_page + $page_scale - 1;

?>
            </tbody>
        </table>
    </div>
    <div class="pagination-container mt-4">
<?php
if ($page != 1 && $page > $page_scale) {
    $prev_page = $page - $page_scale;
    // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
    if ($prev_page <= 0) {
        $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
    }
    print '<button class="btn btn-outline-secondary btn-sm" type="button" id="previousListBtn" onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;';
}
for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
    if ($page == $i) { // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
        print '<span class="text-secondary">  ' . $i . '  </span>';
    } else {
        print '<button class="btn btn-outline-secondary btn-sm" type="button" id="moveListBtn" onclick="javascript:movetoPage(' . $i . ')">' . $i . '</button> &nbsp;';
    }
}

if ($page < $total_page) {
    $next_page = $page + $page_scale;
    if ($next_page > $total_page) {
        $next_page = $total_page;
    }
    // next_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
    print '<button class="btn btn-outline-secondary btn-sm" type="button" id="nextListBtn" onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;';
}
?>
    </div>


</div>
</div> <!-- end of glass-container -->
</div> <!-- end of container -->
</form> <!-- end of form -->
</body>
</html>

<script>
var base_url = "<?= $base_url ?>";

function redirectToView(num, page) {
    popupCenter(base_url + "/work_voc/view.php?num=" + num, '현장소장 VOC', 1400, 800);
}

function blinker() {
    $('.blinking').fadeOut(700);
    $('.blinking').fadeIn(700);
}
setInterval(blinker, 1200);

function SearchEnter() {
    if (event.keyCode == 13) {
        performSearch();
    }
}

function movetoPage(page) {
    // 현재 파라미터들을 가져와서 URL 구성
    var choice = $("#choice").val() || "1";
    var mode = $("#mode").val() || "";
    var search = $("#search").val() || "";
    var find = $("#find").val() || "";
    
    // URL 구성
    var url = "list.php?page=" + page + "&choice=" + choice;
    if (mode) url += "&mode=" + mode;
    if (search) url += "&search=" + encodeURIComponent(search);
    if (find) url += "&find=" + encodeURIComponent(find);
    
    location.href = url;
}

function performSearch() {
    var search = $("#search").val() || "";
    var choice = $("#choice").val() || "1";
    
    var url = "list.php?page=1&choice=" + choice;
    if (search) {
        url += "&mode=search&search=" + encodeURIComponent(search);
    }
    
    location.href = url;
}

$(document).ready(function() {
    $("#searchBtn").click(function() {
        performSearch();
    });

    saveLogData('jamb 시공소장 VOC'); // 다른 페이지에 맞는 menuName을 전달
});
</script>