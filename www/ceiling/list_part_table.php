<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

ini_set('display_errors', '1');

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title> 조명천장 부자재 </title>

<style>
/* 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 컨테이너 및 카드 최적화 */
    .container {
        padding: 0.75rem 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .card {
        margin: 0.5rem auto !important;
        border-radius: 0.5rem !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
    }
    
    .card-header {
        padding: 0.75rem 0.5rem !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
    }
    
    /* 제목 최적화 */
    .fs-6, h4, h5, h6 {
        font-size: 1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        text-align: center !important;
        margin-bottom: 0.75rem !important;
        padding: 0 0.5rem !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* d-flex 컨테이너 안의 버튼은 자동 크기 */
    .d-flex .btn,
    .d-flex.justify-content-center .btn,
    .d-flex.align-items-center .btn {
        width: auto !important;
        max-width: none !important;
        margin: 0.25rem !important;
        flex-shrink: 0 !important;
    }
    
    .btn-sm {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
        min-height: 36px !important;
    }
    
    /* 입력 그룹 안의 버튼 */
    .input-group .btn {
        width: auto !important;
        max-width: none !important;
        margin: 0.25rem !important;
    }
    
    /* 카드 헤더/바디 안의 버튼 */
    .card-header .btn,
    .card-body .btn {
        width: auto !important;
        max-width: none !important;
    }
    
    /* Grid 숨기기 및 카드 컨테이너 표시 */
    #grid {
        display: none !important;
    }
    
    #mobile-grid-cards {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 0.25rem !important;
    }
    
    .mobile-grid-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 0.5rem auto 0.75rem auto !important;
        padding: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        overflow-x: hidden;
        box-sizing: border-box;
        cursor: pointer;
    }
    
    .mobile-grid-card-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
        width: 100%;
        box-sizing: border-box;
    }
    
    .mobile-grid-card-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .mobile-grid-card-label {
        font-weight: bold;
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 0.25rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    .mobile-grid-card-value {
        font-size: 0.9rem;
        color: #333;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* jQuery DataTable 숨기기 */
    .dataTables_length,
    .dataTables_filter {
        display: none !important;
    }
    
    /* HTML 테이블을 카드 형식으로 변환 */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.table tbody tr {
        display: block !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        margin: 0.5rem auto 0.75rem auto !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        padding: 0.75rem !important;
        box-sizing: border-box !important;
        cursor: pointer;
    }
    
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    table.table thead {
        display: none !important;
    }
    
    table.table tbody tr td::before {
        content: attr(data-label) !important;
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-right: 0.5rem !important;
        min-width: 80px !important;
        flex-shrink: 0 !important;
    }
    
    /* 입력 그룹 최적화 */
    .input-group {
        width: 100% !important;
        max-width: 100% !important;
        flex-wrap: wrap !important;
        box-sizing: border-box !important;
        gap: 0.5rem !important;
        justify-content: center !important;
        align-items: center !important;
    }
    
    .input-group > * {
        flex: 0 0 auto !important;
        width: auto !important;
        max-width: none !important;
    }
    
    /* d-flex justify-content-center 최적화 */
    .d-flex.justify-content-center {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .d-flex.justify-content-center .btn {
        width: auto !important;
        max-width: none !important;
        margin: 0.25rem !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
}

/* PC 화면 */
@media (min-width: 769px) {
    #mobile-grid-cards {
        display: none !important;
    }
    
    #grid {
        display: block !important;
    }
}

/* 모달 최적화 */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem !important;
        max-width: calc(100% - 1rem) !important;
    }
    
    .modal-dialog.modal-lg {
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    .modal-content {
        border-radius: 0.5rem !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        min-height: 50px !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-title {
        font-size: 1rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
    }
    
    .modal-header .btn-close {
        margin: 0 !important;
        padding: 0.5rem !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.9rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-body .fs-3 {
        font-size: 1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.25rem !important;
    }
}
</style>

</head>

<body>

<?php
// 메뉴 표시 여부 확인
$menu = isset($_REQUEST['menu']) ? $_REQUEST['menu'] : '';
if ($menu !== 'no') {
    include '../myheader.php';
}
?>

<?php
// _request.php에서 정의되는 변수들 include
include "_request.php";

// 추가 변수 초기화 (hidden input에서 사용)
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
$BigsearchTag = isset($_REQUEST["BigsearchTag"]) ? $_REQUEST["BigsearchTag"] : "";
$voc_alert = isset($_REQUEST["voc_alert"]) ? $_REQUEST["voc_alert"] : "";
$ma_alert = isset($_REQUEST["ma_alert"]) ? $_REQUEST["ma_alert"] : "";
$order_alert = isset($_REQUEST["order_alert"]) ? $_REQUEST["order_alert"] : "";
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$scale = isset($_REQUEST["scale"]) ? $_REQUEST["scale"] : 50;
$yearcheckbox = isset($_REQUEST["yearcheckbox"]) ? $_REQUEST["yearcheckbox"] : "";
$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : "";
$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : 0;
$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : 0;
$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : 0;
$sqltext = isset($_REQUEST["sqltext"]) ? $_REQUEST["sqltext"] : "";
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : "";
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : "";
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : "";
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : "";
$up_fromdate = isset($_REQUEST["up_fromdate"]) ? $_REQUEST["up_fromdate"] : "";
$up_todate = isset($_REQUEST["up_todate"]) ? $_REQUEST["up_todate"] : "";
$separate_date = isset($_REQUEST["separate_date"]) ? $_REQUEST["separate_date"] : "";
$process = isset($_REQUEST["process"]) ? $_REQUEST["process"] : "";
$asprocess = isset($_REQUEST["asprocess"]) ? $_REQUEST["asprocess"] : "";

// 모바일 체크 변수 초기화
$chkMobile = isset($chkMobile) ? $chkMobile : false;

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 부품 타이틀 배열 정의
$title_arr = array(
    "",
    "일반휀",
    "휠터휀 (LH용)",
    "판넬고정구 (금성아크릴)",
    "비상구 스위치 (건흥KH-9015)",
    "비상등",
    "할로겐(7W -6500K)",
    "T5 일반 5W(300)",
    "T5 일반 11W(600)",
    "T5 일반 15W(900)",
    "T5 일반 20W(1200)",
    "T5 KS 6W(300)",
    "T5 KS 10W(600)",
    "T5 KS 15W(900)",
    "T5 KS 20W(1200)",
    "직관등 600mm",
    "직관등 800mm",
    "직관등 1000mm",
    "직관등 1200mm",
    "할로겐(7W -6500K KS)"  // part20 추가 (2023년 7월 20일 김부장님 요청)
);

$itemCount = count($title_arr);

// 배열 초기화
$num_arr = array();
$partin_arr = array_fill(0, $itemCount, 0);   // 입고 합계
$partout_arr = array_fill(0, $itemCount, 0);  // 출고 합계
$partremain_arr = array_fill(0, $itemCount, 0);  // 재고

// 전체 입고 합계 계산
$sql = "select * from mirae8440.part";
$tmpsum = 0;

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        for ($i = 0; $i < $itemCount; $i++) {
            $tmp = 'part' . (string)($i + 1);  // part1~part20 생성
            // 입고 누적
            $partin_arr[$i] = intval($partin_arr[$i]) + intval($row[$tmp]);
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 전체 출고 합계 계산
$sql = "select * from mirae8440.ceiling";

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // 2010년 이후 날짜인지 확인
        $mainAssemblyDate = $row["mainassembly_date"];
        $lcAssemblyDate = $row["lcassembly_date"];
        $basicdate = '';
        
        if ($mainAssemblyDate >= "2010-01-01" || $lcAssemblyDate >= "2010-01-01") {
            if ($mainAssemblyDate >= "2010-01-01") {
                $basicdate = $mainAssemblyDate;
            }
            if ($lcAssemblyDate >= "2010-01-01") {
                $basicdate = $lcAssemblyDate;
            }
            
            // 출고 누적
            for ($i = 0; $i < $itemCount; $i++) {
                $tmp = 'part' . (string)($i + 1);  // part1~part20 생성
                $partout_arr[$i] = intval($partout_arr[$i]) + intval($row[$tmp]);
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 재고량 계산 (입고 - 출고)
for ($i = 0; $i < $itemCount; $i++) {
    $partremain_arr[$i] = (int)$partin_arr[$i] - (int)$partout_arr[$i];
}

// 검색어 공백 제거
$search = str_replace(' ', '', $search);

// 부품 리스트 조회 SQL
$sql = "select * from mirae8440.part order by inputdate desc";
  

?>

<!-- 모달: 원자재 부족 알림 -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-center">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">원자재 현황 부족(마이너스) 상태 알림</h4>
            </div>
            <div class="modal-body">
                <div class="row gx-4 gx-lg-4 align-items-center">
                    <br>
                    <div id="alertmsg" class="fs-3"></div>
                    <br>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closeModalBtn" type="button" class="btn btn-default btn-sm" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- 메인 폼 -->
<form name="board_form" id="board_form" method="post" action="list_part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">
    <!-- Hidden Input Fields -->
    <input type="hidden" id="username" name="username" value="<?=$user_name?>" size="5">
    <input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>" size="5">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5">
    <input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5">
    <input type="hidden" id="page" name="page" value="<?=$page?>" size="5">
    <input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5">
    <input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5">
    <input type="hidden" id="year" name="year" value="<?=$year?>" size="5">
    <input type="hidden" id="check" name="check" value="<?=$check?>" size="5">
    <input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5">
    <input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5">
    <input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5">
    <input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5">
    <input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5">
    <input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5">
    <input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5">
    <input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>">
    <input type="hidden" id="list" name="list" value="<?=$list?>">
    
    <div id="vacancy" style="display:none"></div>
    
    <div class="container mt-2 mb-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
                    <span class="fs-6">조명/천장 주요 부품 리스트 &nbsp;</span>
                    <button type="button" class="btn btn-dark btn-sm mx-2" onclick="location.reload();">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">                
                <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
                    <?php
                    if ($chkMobile === false) { // PC 접속일 때
                    ?>
                        <div id="grid" class="board"></div>
                        <div id="mobile-grid-cards" style="display: none; width: 100%;"></div>
                    <?php
                    } else { // 모바일인 경우
                    ?>
                        <table class="table table-hover table-bordered" id="myTable_top">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">부품명</th>
                                    <th class="text-center">입고</th>
                                    <th class="text-center">출고</th>
                                    <th class="text-center">재고</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $inputlist = "";  // inputlist 초기화
                                
                                // 부품 리스트 출력
                                for ($i = 0; $i < $itemCount; $i++) {
                                    $tmp = "part" . (int)($i + 1);
                                    
                                    if (isset($row[$tmp]) && $row[$tmp] != '') {
                                        $inputlist .= $title_arr[$i] . " : " . $row[$tmp] . "EA,  ";
                                    }
                                ?>
                                    <tr onclick="popupCenter('part_view.php?num=<?=($i+1)?>','부품 이력조회', 800, 500);">
                                        <td class="text-center" data-label="번호"><?=$i+1?></td>
                                        <td class="text-center" data-label="부품명"><?=$title_arr[$i]?></td>
                                        <td class="text-center" data-label="입고"><?=$partin_arr[$i]?></td>
                                        <td class="text-center" data-label="출고"><?=$partout_arr[$i]?></td>
                                        <td class="text-center" data-label="재고"><?=$partremain_arr[$i]?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
            
                <?php
                // 부품 입고 내역 조회
                try {
                    $stmh = $pdo->query($sql);
                    $total_row = $stmh->rowCount();
                ?>
                    
                    <div class="d-flex justify-content-center align-items-center mt-5 flex-wrap">
                        <span class="me-2 mb-2">▷ 총 <?= $total_row ?>건</span>
                        <button type="button" class="btn btn-dark btn-sm mb-2" onclick="popupCenter('part_write_form.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','주요부품 입력',900,500);">
                            <i class="bi bi-pencil"></i> 입고등록
                        </button>
                    </div>
            </div>
            </div>
            <div class="card-body">            
            <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">
                <table class="table table-hover" id="myTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center" style="width:80px;">번호</th>
                            <th class="text-center" style="width:150px;">입고일</th>
                            <th class="text-center">입고 부품 리스트</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 시작 번호 설정
                        if ($page <= 1) {
                            $start_num = $total_row;
                        } else {
                            $start_num = $total_row - ($page - 1) * $scale;
                        }
                        
                        // 부품 입고 내역 출력
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            $inputlist = "";
                            $inputdate = $row["inputdate"];
                            $num = $row["num"];
                            
                            // 입고 리스트 작성
                            for ($i = 0; $i < $itemCount; $i++) {
                                $tmp = "part" . (int)($i + 1);
                                
                                if (isset($row[$tmp]) && $row[$tmp] != '') {
                                    $inputlist .= $title_arr[$i] . " : " . $row[$tmp] . "EA,  ";
                                }
                            }
                            
                            // 날짜에 요일 추가
                            if ($inputdate != "") {
                                $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                                $inputdate = $inputdate . $week[date('w', strtotime($inputdate))];
                            }
                        ?>
                            <tr class="table-hover" onclick="handleRowClick('<?=$num?>')">
                                <td class="text-center" style="cursor:pointer;" data-label="번호"><?=$start_num?></td>
                                <td class="text-center" style="cursor:pointer;" data-label="입고일"><?=$inputdate?></td>
                                <td style="cursor:pointer;" data-label="입고 부품 리스트"><?=$inputlist?></td>
                            </tr>
                        <?php
                            $start_num--;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
    ?>
</form>


</body>
</html>

<script>
// 전역 변수
var dataTable; // DataTables 인스턴스
var ceilingpartpageNumber; // 현재 페이지 번호 저장

// DataTables 초기화
$(document).ready(function() {
    // 모바일 여부 확인
    var isMobile = window.innerWidth <= 768;
    
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": !isMobile, // 모바일에서는 검색 숨김
        "pageLength": isMobile ? 10 : 50,
        "lengthMenu": isMobile ? [10, 25, 50] : [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']],
        "responsive": true
    });
    
    // 모바일에서 Show entries와 Live Search 숨기기
    function handleDataTablesMobile() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    }
    
    // 초기 실행 및 리사이즈 이벤트
    handleDataTablesMobile();
    $(window).on('resize', function() {
        handleDataTablesMobile();
    });
    
    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('ceilingpartpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }
    
    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var ceilingpartpageNumber = dataTable.page.info().page + 1;
        setCookie('ceilingpartpageNumber', ceilingpartpageNumber, 10);
    });
    
    // 페이지 길이 변경 이벤트
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw();
        
        // 변경 후 현재 페이지 번호 복원
        var savedPageNumber = getCookie('ceilingpartpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

// 페이지 번호 복원 함수
function restorePageNumber() {
    var savedPageNumber = getCookie('ceilingpartpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

// 행 클릭 핸들러
function handleRowClick(num) {
    popupCenter('part_write_form.php?mode=modify&num=' + num, '주요부품 입력', 900, 500);
}


// 모달 닫기 버튼 이벤트
$(document).ready(function() {
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
});

// 검색 버튼 이벤트
$(document).ready(function() {
    $("#searchBtn").click(function() {
        // BigsearchTag 설정
        var str = '<?php echo $BigsearchTag; ?>';
        
        $("#BigsearchTag").val(str.replace(' ', '|'));
        $("#stable").val('1');
        $("#page").val('1');
        $("#list").val('1');
        $("#board_form").submit();
    });
});

// TUI Grid 설정 (PC용)
$(document).ready(function() {
    var numcopy = new Array();
    var title = <?php echo json_encode($title_arr); ?>;
    var arr1 = <?php echo json_encode($partin_arr); ?>;
    var arr2 = <?php echo json_encode($partout_arr); ?>;
    var arr3 = <?php echo json_encode($partremain_arr); ?>;
    
    console.log(title);
    
    var rowNum = title.length;
    var count = 0;
    const COL_COUNT = 4;
    const data = [];
    const columns = [];
    
    // 데이터 생성
    for (var i = 1; i < rowNum; i++) {
        const row = { name: count };
        
        for (let j = 0; j < COL_COUNT; j++) {
            row['col1'] = title[i];
            row['col2'] = arr1[i];
            row['col3'] = arr2[i];
            row['col4'] = arr3[i];
        }
        
        // '중국산휀' 제외
        if (title[i] !== '중국산휀') {
            numcopy.push(i + 1);
            data.push(row);
        }
    }
    
    // 모바일 여부 확인
    var isMobile = window.innerWidth <= 768;
    var bodyHeight = isMobile ? 400 : 330;
    
    // grid를 전역 변수로 선언
    window.grid = new tui.Grid({
        el: document.getElementById('grid'),
        data: data,
        bodyHeight: bodyHeight,
        columns: [
            {
                header: '부품명',
                name: 'col1',
                sortingType: 'desc',
                sortable: true,
                width: 350,
                align: 'center'
            },
            {
                header: '입고',
                name: 'col2',
                width: 100,
                align: 'center'
            },
            {
                header: '출고',
                name: 'col3',
                width: 100,
                align: 'center'
            },
            {
                header: '재고',
                name: 'col4',
                width: 100,
                align: 'center'
            }
        ],
        columnOptions: {
            resizable: true
        },
        rowHeaders: ['rowNum']
    });
    
    // TUI Grid 테마 적용
    var Grid = tui.Grid;
    Grid.applyTheme('striped', {
        selection: {
            background: '#4555f9',
            border: '#004082'
        },
        scrollbar: {
            background: '#f5f5f5',
            thumb: '#d9d9d9',
            active: '#c1c1c1'
        },
        row: {
            even: {
                background: '#0000'
            },
            hover: {
                background: '#cfe2ff'
            }
        },
        cell: {
            normal: {
                background: '#fbfbfb',
                border: '#e0e0e0',
                showVerticalBorder: true
            },
            header: {
                background: '#cfe2ff',
                border: '#ccc',
                showVerticalBorder: true
            },
            rowHeader: {
                border: '#ccc',
                showVerticalBorder: true
            },
            editable: {
                background: '#fbfbfb'
            },
            selectedHeader: {
                background: '#d8d8d8'
            },
            focused: {
                border: '#418ed4'
            },
            disabled: {
                text: '#b0b0b0'
            }
        }
    });
    
    // 더블클릭 이벤트
    window.grid.on('dblclick', (e) => {
        var link = 'https://8440.co.kr/ceiling/part_view.php?num=' + numcopy[e.rowKey];
        if (numcopy[e.rowKey] > 0) {
            popupCenter(link, '주요 부품 추적', 800, 900);
        }
        console.log(e.rowKey);
    });
    
    // 모바일 카드 렌더링 함수
    function renderMobileGridCards() {
        if (!window.grid) return;
        
        var isMobile = window.innerWidth <= 768;
        var cardsContainer = document.getElementById('mobile-grid-cards');
        var gridContainer = document.getElementById('grid');
        
        if (!isMobile) {
            // PC 화면: Grid 표시, 카드 숨김
            if (gridContainer) {
                gridContainer.style.display = '';
            }
            if (cardsContainer) {
                cardsContainer.style.display = 'none';
            }
            return;
        }
        
        // 모바일 화면: Grid 숨김, 카드 표시
        if (gridContainer) {
            gridContainer.style.display = 'none';
        }
        if (!cardsContainer) return;
        
        cardsContainer.style.display = 'block';
        cardsContainer.innerHTML = '';
        
        try {
            var gridData = window.grid.getData();
            
            if (!gridData || gridData.length === 0) {
                cardsContainer.innerHTML = '<div class="text-center py-4 text-muted">데이터가 없습니다.</div>';
                return;
            }
            
            // 컬럼 매핑
            var columnMap = [
                { name: 'col1', label: '부품명' },
                { name: 'col2', label: '입고' },
                { name: 'col3', label: '출고' },
                { name: 'col4', label: '재고' }
            ];
            
            gridData.forEach(function(rowData, index) {
                var card = document.createElement('div');
                card.className = 'mobile-grid-card';
                
                // 클릭 이벤트 추가 (PC의 더블클릭과 동일한 기능)
                var rowKey = index;
                var partNum = numcopy[rowKey];
                if (partNum > 0) {
                    card.style.cursor = 'pointer';
                    card.addEventListener('click', function() {
                        var link = 'https://8440.co.kr/ceiling/part_view.php?num=' + partNum;
                        popupCenter(link, '주요 부품 추적', 800, 900);
                    });
                }
                
                var cardHtml = '';
                
                columnMap.forEach(function(colInfo) {
                    var value = rowData[colInfo.name];
                    if (value === null || value === undefined || value === '') {
                        value = '0';
                    }
                    
                    var displayValue = value;
                    
                    cardHtml += '<div class="mobile-grid-card-item">';
                    cardHtml += '<div class="mobile-grid-card-label">' + colInfo.label + '</div>';
                    cardHtml += '<div class="mobile-grid-card-value">' + displayValue + '</div>';
                    cardHtml += '</div>';
                });
                
                if (cardHtml === '') {
                    cardHtml = '<div class="text-muted">데이터 없음</div>';
                }
                
                card.innerHTML = cardHtml;
                cardsContainer.appendChild(card);
            });
        } catch (error) {
            console.error('모바일 카드 렌더링 오류:', error);
            cardsContainer.innerHTML = '<div class="text-center py-4 text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
        }
    }
    
    // 화면 크기 변경 시 카드/그리드 전환
    function updateGridDisplay() {
        renderMobileGridCards();
    }
    
    // Grid 렌더링 완료 후 모바일 카드 렌더링
    setTimeout(function() {
        updateGridDisplay();
    }, 300);
    
    // 리사이즈 이벤트
    window.addEventListener('resize', function() {
        updateGridDisplay();
    });
    
    // 페이지 로드 완료 후에도 한 번 더 확인
    window.addEventListener('load', function() {
        setTimeout(function() {
            updateGridDisplay();
        }, 500);
    });
});

// 서버에 작업 기록
$(document).ready(function() {
    saveLogData('조명/천장 주요 부품 리스트');
});
</script>

</body>
</html>