<?php
/**
 * ISO 9001/14001 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 권한 체크 (레벨 5 이하만 접근 가능)
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location: " . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 첫 화면 표시 문구
$title_message = 'ISO 9001/14001';

// 요청 변수 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$tablename = "iso";

// SQL 쿼리 생성 (Prepared Statement 사용)
$sql = '';
$params = [];

if ($mode == "search" && !empty($search)) {
    $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE name LIKE ? OR subject LIKE ? OR nick LIKE ? OR regist_day LIKE ? ORDER BY num DESC";
    $searchTerm = "%{$search}%";
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
} else {
    $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
    $params = [];
}

// 데이터 조회
$dataList = [];
$total_row = 0;

try {
    $stmh = $pdo->prepare($sql);
    foreach ($params as $index => $param) {
        $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
    }
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $dataList[] = $row;
    }
    
    $total_row = count($dataList);
} catch (PDOException $ex) {
    error_log("ISO 목록 조회 오류: " . $ex->getMessage());
    $dataList = [];
    $total_row = 0;
}

include getDocumentRoot() . '/load_header.php';
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>

<style>
/* 모바일 환경 최적화 */
@media (max-width: 768px) {
    /* 컨테이너 최적화 */
    .container,
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 최적화 */
    .card {
        margin: 0.5rem auto !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 검색 UI 최적화 */
    .d-flex.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
        flex-wrap: wrap !important;
    }
    
    .input-group {
        flex-direction: column !important;
        width: 100% !important;
    }
    
    .input-group .form-control,
    .input-group .btn {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 버튼 그룹 최적화 */
    .d-flex.justify-content-center .btn {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 이미지 최적화 */
    img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        object-fit: contain !important;
    }
    
    /* jQuery DataTable 숨기기 */
    .dataTables_length,
    .dataTables_filter {
        display: none !important;
    }
    
    /* 테이블을 카드 형식으로 변환 */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.table thead {
        display: none !important;
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
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        cursor: pointer !important;
    }
    
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: none !important;
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
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모달 최적화 */
    .modal {
        padding: 0 !important;
    }
    
    .modal-dialog {
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        height: 100vh !important;
        max-height: 100vh !important;
    }
    
    .modal-content {
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        height: 100vh !important;
        max-height: 100vh !important;
        border-radius: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        overflow-y: auto !important;
        flex: 1 1 auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    /* '기간' 버튼 숨기기 */
    #showdate {
        display: none !important;
    }
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
}
</style>

</head>

<body>

<?php require_once(includePath('myheader.php')); ?>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">

<div class="container">
<div class="card mt-1 mb-1">
<div class="card-body">

 <div class="d-flex mt-1 mb-1 justify-content-center">
    <img src="../img/isologo.jpg" style="width:100%;" alt="ISO Logo">
  </div>
  
 <div class="d-flex mt-2 mb-2 justify-content-center">
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href='./QEMM/view1.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'">품질/환경경영매뉴얼</button>&nbsp;&nbsp;
    <button type="button" class="btn btn-success btn-sm" onclick="location.href='./QEMM/view2.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'">품질/환경 절차서</button>&nbsp;&nbsp;
    <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='./QEMM/view3.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'">환경절차서 제,개정 이력</button>&nbsp;&nbsp;
  </div>

  
 <div class="d-flex mt-3 mb-2 justify-content-center">
  <h5>ISO 9001/14001</h5>
  </div>
  
 <div class="d-flex mt-3 mb-1 justify-content-center">
    <div class="input-group p-2 mb-2 justify-content-center">
        <input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" class="form-control w150px mx-1" onkeydown="JavaScript:SearchEnter();" placeholder="검색어 입력">
        <button type="button" id="searchBtn" class="btn btn-dark mx-1"><i class="bi bi-search"></i> 검색</button>
        <button type="button" class="btn btn-dark btn-sm me-2" id="writeBtn"><i class="bi bi-pencil"></i> 신규</button>
    </div>
   </div>
   
<script>
function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}
</script>

<div class="row d-flex">
<table class="table table-hover" id="myTable">
   <thead class="table-primary">
        <tr>
             <th class="text-center">번호</th>
             <th class="text-center">자료명</th>
             <th class="text-center">등록인</th>
             <th class="text-center">등록일자</th>
             <th class="text-center">조회수</th>
         </tr>
       </thead>
    <tbody>

<?php
$start_num = $total_row;

foreach ($dataList as $row) {
    $item_num = $row["num"] ?? '';
    $item_id = $row["id"] ?? '';
    $item_name = $row["name"] ?? '';
    $item_nick = $row["nick"] ?? '';
    $item_hit = $row["hit"] ?? 0;
    $item_date = $row["regist_day"] ?? '';
    $item_date = substr($item_date, 0, 10);
    $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
?>
    <tr onclick="redirectToView('<?= htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>')" style="cursor: pointer;">
      <td class="text-center" data-label="번호"><?= $start_num ?></td>
      <td class="text-start" data-label="자료명"><?= $item_subject ?></td>
      <td class="text-center" data-label="등록인"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
      <td class="text-center" data-label="등록일자"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
      <td class="text-center" data-label="조회수"><?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?></td>
    </tr>

<?php
    $start_num--;
}

if (empty($dataList)) {
    echo '<tr><td colspan="5" class="text-center">조회된 데이터가 없습니다.</td></tr>';
}
?>

      </tbody>
      </table>
    </div>
       
    </div>
    </div>
    </div>
    
</form>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var isopageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {
    // 모바일 환경에서 '기간' 버튼 숨기기
    if (window.innerWidth <= 768) {
        $('#showdate').hide();
    }
    
    // 창 크기 변경 시 '기간' 버튼 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('#showdate').hide();
        } else {
            $('#showdate').show();
        }
    });
    
    // 모바일 환경에서 jQuery DataTable 컨트롤 숨기기
    if (window.innerWidth <= 768) {
        $('.dataTables_length, .dataTables_filter').hide();
    }
    
    // 창 크기 변경 시 DataTable 컨트롤 표시/숨김 처리
    $(window).resize(function() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    });
    
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
        "order": [[0, 'desc']],
        "responsive": true
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('isopageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var isopageNumber = dataTable.page.info().page + 1;
        setCookie('isopageNumber', isopageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('isopageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('isopageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = isopageNumber; // 현재 페이지 번호
    var url = "view.php?num=" + encodeURIComponent(num) + "&tablename=" + encodeURIComponent(tablename);
    customPopup(url, 'ISO 9001/14001', 1200, 900);
}

$(document).ready(function(){
    $("#writeBtn").click(function(){
        var page = isopageNumber; // 현재 페이지 번호
        var tablename = <?php echo json_encode($tablename, JSON_UNESCAPED_UNICODE); ?>;
        var url = "write_form.php?tablename=" + encodeURIComponent(tablename);
        customPopup(url, 'ISO 9001/14001', 1300, 850);
     });
});

// 서버에 작업 기록
$(document).ready(function(){
    saveLogData('ISO 9001/14001'); // 다른 페이지에 맞는 menuName을 전달
});
</script>
</body>
</html>
