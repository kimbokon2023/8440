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
      <td class="text-center"><?= $start_num ?></td>
      <td class="text-start"><?= $item_subject ?></td>
      <td class="text-center"><?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?></td>
      <td class="text-center"><?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></td>
      <td class="text-center"><?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?></td>
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
