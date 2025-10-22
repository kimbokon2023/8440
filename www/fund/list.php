<?php
/**
 * Fund 공동자금 목록 페이지
 * 수입/지출 내역을 표시하고 검색 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

require_once getDocumentRoot() . '/session.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '1';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$cursort = $_REQUEST["cursort"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$year = $_REQUEST["year"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

// 변수 초기화
$scale = 50; // 한 페이지에 보여질 게시글 수
$page_scale = 15; // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;
$SettingDate = "proDate";
$input_sum = 0;
$output_sum = 0;
$remain_sum = 0;
$resultText = '';
$total_row = 0;
$total_page = 0;
$current_page = 0;
$start_num = 0;
$nowday = date("Y-m-d");
$regist_state = null;

// 날짜 설정
if (empty($fromdate)) {
    $fromdate = "2021-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
}

// Transtodate는 항상 todate + 1일로 설정
$Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수입/지출 합계 계산
try {
    $sql_sum = "SELECT * FROM {$DB}.fund WHERE proDate BETWEEN DATE('2010-01-01') AND DATE(?) ORDER BY proDate";
    $stmh_sum = $pdo->prepare($sql_sum);
    $stmh_sum->bindValue(1, $todate, PDO::PARAM_STR);
    $stmh_sum->execute();
    $sum_data = $stmh_sum->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($sum_data as $row) {
        $amount = $row["amount"];
        $which = $row["which"];
        
        if ($which == '1') {
            $input_sum += (int)conv_num($amount);
        } else {
            $output_sum += (int)conv_num($amount);
        }
    }
} catch (PDOException $ex) {
    error_log("Sum calculation error: " . $ex->getMessage());
}

$remain_sum = $input_sum - $output_sum;
$resultText = "총수입(" . number_format($input_sum) . "원) - 총지출(" . number_format($output_sum) . "원) = 보유 잔액(" . number_format($remain_sum) . "원)";

// SQL 쿼리 생성
if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM {$DB}.fund WHERE ({$SettingDate} BETWEEN ? AND ?) AND (memo LIKE ? OR writer LIKE ?) ORDER BY {$SettingDate} DESC, num DESC LIMIT ?, ?";
} else {
    $sql = "SELECT * FROM {$DB}.fund WHERE {$SettingDate} BETWEEN '{$fromdate}' AND '{$Transtodate}' ORDER BY {$SettingDate} DESC, num DESC LIMIT {$first_num}, {$scale}";
}

// 전체 레코드 수 조회
try {
    $count_sql = "SELECT COUNT(*) as cnt FROM {$DB}.fund WHERE proDate BETWEEN '{$fromdate}' AND '{$Transtodate}'";
    $count_result = $pdo->query($count_sql);
    if ($count_result) {
        $count_row = $count_result->fetch(PDO::FETCH_ASSOC);
        $total_row = (int)($count_row['cnt'] ?? 0);
    }
} catch (Exception $e) {
    error_log("Count error: " . $e->getMessage());
}

$total_page = ceil($total_row / $scale);
$current_page = ceil($page / $page_scale);

// 페이지별 데이터 조회
try {
    if ($mode == "search" && !empty($search)) {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
        $stmh->bindValue(3, '%' . $search . '%', PDO::PARAM_STR);
        $stmh->bindValue(4, '%' . $search . '%', PDO::PARAM_STR);
        $stmh->bindValue(5, $first_num, PDO::PARAM_INT);
        $stmh->bindValue(6, $scale, PDO::PARAM_INT);
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sql);
    }
} catch (PDOException $ex) {
    error_log("List query error: " . $ex->getMessage());
}

include getDocumentRoot() . '/load_header.php';
?>

<title>공동자금</title>
</head>
<body>

<?php require_once(includePath('myheader.php')); ?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="find" value="<?php echo htmlspecialchars($find, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="year" value="<?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="process" value="<?php echo htmlspecialchars($process, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="asprocess" value="<?php echo htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="fromdate" value="<?php echo htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="todate" value="<?php echo htmlspecialchars($todate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="up_fromdate" value="<?php echo htmlspecialchars($up_fromdate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="up_todate" value="<?php echo htmlspecialchars($up_todate, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="separate_date" value="<?php echo htmlspecialchars($separate_date, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="view_table" value="<?php echo htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="container">
        <div class="card mt-2 mb-1">
            <div class="card-header">
                <div class="d-flex mb-4 mt-4 fs-6 justify-content-center align-items-center">
                    <?php echo htmlspecialchars($resultText, ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn btn-dark btn-sm mx-3" onclick='location.reload();' title="새로고침">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                
                <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
                    ▷ 총 <?php echo htmlspecialchars(count($sum_data), ENT_QUOTES, 'UTF-8'); ?>건 &nbsp;&nbsp;
                    
                    <!-- 기간설정 -->
                    <?php include getDocumentRoot() . '/setdate.php'; ?>
                    
                    <?php if (isset($_SESSION["userid"]) && in_array($user_name, array('조경임', '김보곤', '소민지'))) { ?>
                    &nbsp;&nbsp;
                    <button type="button" id="writeBtn" class="btn btn-dark btn-sm">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body justify-content-center align-items-center">
                <div class="row">
                    <div class="col-sm-1 mb-1 mt-1"></div>
                    <div class="col-sm-10 mb-1 mt-1">
                        <table class="table table-hover" id="myTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">작성일</th>
                                    <th class="text-center">수입</th>
                                    <th class="text-center">지출</th>
                                    <th class="text-center">금액</th>
                                    <th class="text-center">내역</th>
                                    <th class="text-center">작성자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($page <= 1) {
                                    $start_num = $total_row;
                                } else {
                                    $start_num = $total_row - ($page - 1) * $scale;
                                }
                                
                                try {
                                    if (!isset($sum_data)) {
                                        echo '<tr><td colspan="7" class="text-center text-danger">데이터베이스 쿼리 오류가 발생했습니다.</td></tr>';
                                    } else {
                                        $row_count = 0;
                                        foreach ($sum_data as $row) {
                                            $row_count++;
                                            $num = $row["num"] ?? '';
                                            $proDate = $row["proDate"] ?? '';
                                            $writer = $row["writer"] ?? '';
                                            $amount = $row["amount"] ?? '';
                                            $memo = $row["memo"] ?? '';
                                            $which = $row["which"] ?? '';
                                        
                                        // 요일 추가
                                        if (!empty($proDate)) {
                                            $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                                            $proDate = $proDate . $week[date('w', strtotime($proDate))];
                                        }
                                        
                                        // 수입/지출 구분
                                        if ($which == '1') {
                                            $tmp_word = "수입";
                                        } else {
                                            $tmp_word = "지출";
                                        }
                                ?>
                                <tr onclick="redirectToView('<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>')" style="cursor: pointer;">
                                    <td class="text-center"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($proDate, ENT_QUOTES, 'UTF-8'); ?></td>
                                    
                                    <?php if ($tmp_word == '수입') { ?>
                                        <td class="text-primary text-center"><?php echo htmlspecialchars($tmp_word, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <?php } else { ?>
                                        <td class="text-danger text-center"></td>
                                    <?php } ?>
                                    
                                    <?php if ($tmp_word !== '수입') { ?>
                                        <td class="text-danger text-center"><?php echo htmlspecialchars($tmp_word, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <?php } else { ?>
                                        <td class="text-primary text-center"></td>
                                    <?php } ?>
                                    
                                    <td class="text-end"><?php echo htmlspecialchars($amount, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-start"><?php echo htmlspecialchars($memo, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($writer, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <?php
                                        $start_num--;
                                    }

                                    if ($row_count == 0) {
                                        echo '<tr><td colspan="7" class="text-center">조회된 데이터가 없습니다.</td></tr>';
                                    }
                                }
                                } catch (PDOException $ex) {
                                    error_log("List fetch error: " . $ex->getMessage());
                                    echo '<tr><td colspan="7" class="text-center text-danger">데이터 조회 중 오류가 발생했습니다.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-1 mb-1 mt-1"></div>
                </div>
                
                <!-- 페이징 -->
                <div class="row row-cols-auto mt-3 mb-5 justify-content-center align-items-center">
                    <?php
                    $start_page = ($current_page - 1) * $page_scale + 1;
                    $end_page = $start_page + $page_scale - 1;
                    
                    if ($page != 1 && $page > $page_scale) {
                        $prev_page = $page - $page_scale;
                        if ($prev_page <= 0) {
                            $prev_page = 1;
                        }
                        echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;';
                    }
                    
                    for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                        if ($page == $i) {
                            echo '<span class="text-secondary"> ' . $i . ' </span>';
                        } else {
                            echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="movetoPage(' . $i . ')"> ' . $i . '</button> &nbsp;';
                        }
                    }
                    
                    if ($page < $total_page) {
                        $next_page = $page + $page_scale;
                        if ($next_page > $total_page) {
                            $next_page = $total_page;
                        }
                        echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="container-fluid">
    <?php include '../footer_sub.php'; ?>
</div>

<script>
var dataTable;
var fundpageNumber;

$(document).ready(function() {
    'use strict';
    
    var hasData = $('#myTable tbody tr').length > 0 && !$('#myTable tbody tr td[colspan]').length;
    
    if (hasData) {
        dataTable = $('#myTable').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 50,
            "lengthMenu": [25, 50, 100, 200, 500, 1000],
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "search": "Live Search:"
            },
            "order": [[0, 'desc']]
        });
        
        var savedPageNumber = getCookie('fundpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
        
        dataTable.on('page.dt', function() {
            fundpageNumber = dataTable.page.info().page + 1;
            setCookie('fundpageNumber', fundpageNumber, 10);
        });
        
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw();
            
            savedPageNumber = getCookie('fundpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });
    }
    
    $("#writeBtn").click(function() {
        var url = "write_form.php";
        popupCenter(url, '공동자금', 600, 500);
    });
    
    saveLogData('공동자금 조회');
});

function restorePageNumber() {
    if (dataTable) {
        var savedPageNumber = getCookie('fundpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    }
}

function redirectToView(num) {
    var url = "view.php?num=" + num;
    customPopup(url, '공동자금', 600, 500);
}

function movetoPage(page) {
    $("#page").val(page);
    $("#board_form").submit();
}
</script>
</body>
</html>
