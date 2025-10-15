<?php
/**
 * 도장 발주 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 함수 및 세션 로드
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level >= 5) {
    sleep(1);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $WebSite = "{$protocol}://{$host}/";
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

// 페이지 설정
$title_message = '도장 발주';

// _request.php에서 사용될 변수들 미리 선언
require_once "_request.php";

// 추가 변수 초기화 (hidden input용)
$voc_alert = getRequestValue("voc_alert", '');
$ma_alert = getRequestValue("ma_alert", '');
$order_alert = getRequestValue("order_alert", '');
$yearcheckbox = getRequestValue("yearcheckbox", '');
$year = getRequestValue("year", '');
$check = getRequestValue("check", '');
$output_check = getRequestValue("output_check", '');
$plan_output_check = getRequestValue("plan_output_check", '');
$team_check = getRequestValue("team_check", '');
$measure_check = getRequestValue("measure_check", '');
$cursort = getRequestValue("cursort", '');
$sortof = getRequestValue("sortof", '');
$stable = getRequestValue("stable", '');
$sqltext = getRequestValue("sqltext", '');
$asprocess = getRequestValue("asprocess", '');
$separate_date = getRequestValue("separate_date", '');
$first_num = getRequestValue("first_num", 0);
$find = getRequestValue("find", '');

// 날짜 범위 설정
if (empty($fromdate)) {
    $fromdate = "2020-01-01";
}

if (empty($todate)) {
    $todate = date("Y") . "-12-31";
    $Transtodate = date("Y-m-d", strtotime($todate . ' +1 days'));
} else {
    $Transtodate = date("Y-m-d", strtotime($todate));
}

// 기본 설정
$process = "전체";

// 날짜 기준 설정
$SettingDate = ($separate_date == "1") ? "orderdate" : "indate";

// SQL 쿼리 생성 (Prepared Statement 사용)
$nowday = date("Y-m-d");

try {
    if ($mode == "search" && !empty($search)) {
        // 검색 모드 (Prepared Statement로 SQL 인젝션 방지)
        $sql = "SELECT * FROM mirae8440.make 
                WHERE (orderdate LIKE ? OR text LIKE ? OR indate LIKE ? OR company LIKE ?) 
                ORDER BY {$SettingDate} DESC, num DESC 
                LIMIT ?, ?";
        
        $stmh = $pdo->prepare($sql);
        $searchTerm = "%{$search}%";
        $stmh->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(4, $searchTerm, PDO::PARAM_STR);
        $stmh->bindValue(5, (int)$first_num, PDO::PARAM_INT);
        $stmh->bindValue(6, (int)$scale, PDO::PARAM_INT);
        $stmh->execute();
    } else {
        // 일반 목록
        $sql = "SELECT * FROM mirae8440.make 
                WHERE {$SettingDate} BETWEEN ? AND ? 
                ORDER BY num DESC";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $fromdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $Transtodate, PDO::PARAM_STR);
        $stmh->execute();
    }
    
    $total_row = $stmh->rowCount();
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?></title>

</head>
<body>

<?php require_once(includePath('myheader.php')); ?>

<?php
// 안전한 form action URL 생성
$form_params = http_build_query([
    'mode' => 'search',
    'search' => $search,
    'find' => $find,
    'year' => $year,
    'process' => $process,
    'asprocess' => $asprocess,
    'fromdate' => $fromdate,
    'todate' => $todate,
    'separate_date' => $separate_date,
    'scale' => 10000
], '', '&', PHP_QUERY_RFC3986);
?>

<form name="board_form" id="board_form" method="post" action="list.php?<?=$form_params?>">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?=htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?=htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="order_alert" name="order_alert" value="<?=htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="page" name="page" value="<?=htmlspecialchars($page, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="scale" name="scale" value="<?=htmlspecialchars($scale, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="year" name="year" value="<?=htmlspecialchars($year, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="check" name="check" value="<?=htmlspecialchars($check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="output_check" name="output_check" value="<?=htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="team_check" name="team_check" value="<?=htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="measure_check" name="measure_check" value="<?=htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="cursort" name="cursort" value="<?=htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="sortof" name="sortof" value="<?=htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="stable" name="stable" value="<?=htmlspecialchars($stable, ENT_QUOTES, 'UTF-8')?>">
    <input type="hidden" id="sqltext" name="sqltext" value="<?=htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8')?>"> 

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-header mb-1 justify-content-center align-items-center">
            <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                <h4 class="me-4"><?=htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8')?></h4>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="location.href='../paint/index.php';">
                    도장발주 모바일버전으로 화면보기
                </button>
            </div>
        </div>
        
        <div class="card-body justify-content-center">
            <div class="row">
                <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                    <!-- 기간설정 칸 -->
                    <?php include getDocumentRoot() . '/setdate.php' ?>
                    &nbsp;
                    <button type="button" class="btn btn-dark btn-sm me-2" id="writeBtn">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                </div>
            </div>
            
            <div class="d-flex justify-content-center align-items-center">
                <table class="table table-hover" id="myTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center" style="width:50px;">번호</th>
                            <th class="text-center" style="width:120px;">접수</th>
                            <th class="text-center" style="width:100px;">발주</th>
                            <th class="text-center" style="width:120px;">발주처</th>
                            <th class="text-center">(현장명 등) 발주내용</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                        $start_num = $total_row;
                        
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            $num = $row["num"] ?? '';
                            $orderdate = $row["orderdate"] ?? '';
                            $indate = $row["indate"] ?? '';
                            $company = $row["company"] ?? '';
                            $text = $row["text"] ?? '';
                            
                            // 텍스트 정리
                            $text = str_replace(",", " ", $text);
                            $text = str_replace("|", " ", $text);
                            $sumStr = $text;
                            
                            // 현재 날짜 강조
                            $date_font = "color-black";
                            if ($nowday == $orderdate) {
                                $date_font = "color-red";
                            }
                            
                            // 요일 추가
                            if (!empty($orderdate)) {
                                $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                                $orderdate = $orderdate . $week[date('w', strtotime($orderdate))];
                            }
                            
                            // 안전한 JavaScript 파라미터 생성
                            $onclick_params = [
                                $num,
                                htmlspecialchars($page, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($find, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($search, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($process, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($asprocess, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($year, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($todate, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($separate_date, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($scale, ENT_QUOTES, 'UTF-8')
                            ];
                            
                            $onclick = "redirectToView(" . implode(", ", array_map(function($p) {
                                return is_numeric($p) ? $p : "'{$p}'";
                            }, $onclick_params)) . ")";
                            ?>
                            <tr style="cursor:pointer;" onclick="<?=$onclick?>">
                                <td class="text-center"><?=htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8')?></td>
                                <td class="<?=$date_font?> text-center"><?=htmlspecialchars($orderdate, ENT_QUOTES, 'UTF-8')?></td>
                                <td class="<?=$date_font?> text-center"><?=htmlspecialchars($indate, ENT_QUOTES, 'UTF-8')?></td>
                                <td class="text-center"><?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?></td>
                                <td class="color-gray"><?=htmlspecialchars($sumStr, ENT_QUOTES, 'UTF-8')?></td>
                            </tr>
                            <?php
                            $start_num--;
                        }
                    } catch (PDOException $ex) {
                        error_log("목록 조회 오류: " . $ex->getMessage());
                        echo "<tr><td colspan='5' class='text-center'>데이터 조회 중 오류가 발생했습니다.</td></tr>";
                    }
   
 ?>
                    </tbody>
                </table>
            </div>
        </div> <!--card-body-->
    </div> <!--card-->
</div> <!--container-->

</form>

<div class="container-fluid">
    <?php include '../footer_sub.php'; ?>
</div>

<script>
'use strict';    
var dataTable; // DataTables 인스턴스 전역 변수
var paintpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
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

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('paintpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var paintpageNumber = dataTable.page.info().page + 1;
        setCookie('paintpageNumber', paintpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('paintpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
	
	$("#writeBtn").click(function(){ 
		var page = paintpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)			
		var url = "write_form.php"; 
		customPopup(url, '신규 등록', 1400, 800); 	
	 });			
});

function restorePageNumber() {
    var savedPageNumber = getCookie('paintpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

// 상세 페이지로 이동
function redirectToView(num, page, find, search, process, asprocess, yearcheckbox, year, fromdate, todate, separate_date, scale) {
    var params = {
        menu: 'no',
        num: num,
        page: paintpageNumber || page || '',
        find: find || '',
        search: search || '',
        process: process || '',
        asprocess: asprocess || '',
        yearcheckbox: yearcheckbox || '',
        year: year || '',
        fromdate: fromdate || '',
        todate: todate || '',
        separate_date: separate_date || '',
        scale: scale || ''
    };
    
    var queryString = Object.keys(params)
        .filter(function(key) { return params[key] !== ''; })
        .map(function(key) { return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]); })
        .join('&');
    
    var url = 'view.php?' + queryString;
    
    if (typeof customPopup === 'function') {
        customPopup(url, '도장 발주', 1400, 800);
    } else {
        window.open(url, '도장 발주', 'width=1400,height=800');
    }
}
</script>

<script>
$(document).ready(function() {
    if (typeof saveLogData === 'function') {
        saveLogData('도장 발주');
    }
});
</script>

</body>
</html>