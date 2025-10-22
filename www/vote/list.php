<?php
require_once __DIR__ . '/../common/functions.php';
?>
<?php 
require_once(includePath('session.php'));
   // 첫 화면 표시 문구
 $title_message = '투표'; 
 ?>
 
<?php 
include includePath('load_header.php');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

if($level > 5) {
	$_SESSION["url"] = getBaseUrl() . '/vote/list.php'; 	 
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}    
?>
 
<title>  <?=$title_message?>  </title> 

<style>
	.table-hover tbody tr:hover {
		cursor: pointer;
	}
</style>  
</head> 

<body>
<?php if ($chkMobile): ?>
    <!-- 모바일 환경일 때 보이는 버튼 -->	         
<?php else: ?>
    <!-- PC 환경일 때 보이는 버튼 -->	
		<?php require_once(includePath('myheader.php')); ?>   
<?php endif; ?>		

<?php 
$tablename = "vote";
  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

// 요청 변수 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$scale = $_REQUEST["scale"] ?? 50;

// SQL 쿼리 준비 (Prepared Statement 사용)
if($mode == "search" && !empty($search)) {
	$searchParam = '%' . $search . '%';
	$sql = "SELECT * FROM " . $DB . "." . $tablename . " 
			WHERE name LIKE ? 
			   OR subject LIKE ? 
			   OR nick LIKE ? 
			   OR regist_day LIKE ? 
			   OR searchtext LIKE ? 
			ORDER BY num DESC";
	$params = array($searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
} else {
	$sql = "SELECT * FROM " . $DB . "." . $tablename . " ORDER BY num DESC";
	$params = array();
}

// 전체 레코드수를 파악한다.
$total_row = 0;
try {
	if(!empty($params)) {
		$stmh = $pdo->prepare($sql);
		$stmh->execute($params);
	} else {
		$stmh = $pdo->query($sql);
	}
	$total_row = $stmh->rowCount();
} catch (PDOException $Exception) {
	error_log("데이터 조회 오류: " . $Exception->getMessage());
}

// 데이터 조회
try {
	if(!empty($params)) {
		$stmh = $pdo->prepare($sql);
		$stmh->execute($params);
	} else {
		$stmh = $pdo->query($sql);
	} 	 
			 
 ?>		 


<form name="board_form" id="board_form"  method="post" action="list.php">

  <input type="hidden" id="mode" name="mode" value="search"> 
  <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>"  > 
  <input type="hidden" id="scale" name="scale" value="<?= htmlspecialchars($scale, ENT_QUOTES, 'UTF-8') ?>"  >   


<?php if ($chkMobile): ?>
    <!-- 모바일 환경일 때 보이는 버튼 -->	  
    <div class="container-fluid p-2 m-1">  	
<?php else: ?>
    <!-- PC 환경일 때 보이는 버튼 -->	
	<div class="container justify-content-center">  
<?php endif; ?>		
  
	<div class="card mt-2 mb-4">  
	<div class="card-body">  
		<div class="d-flex mt-3 mb-2 justify-content-center align-items-center">  
			<h4 class="me-4">  <?=$title_message?> </h4> <img src="../img/QR/vote_QR.png" style="width:5%;">
		</div>	
  
 <div class="d-flex mt-3 mb-1 justify-content-center">  
 
    <div class="input-group p-2 mb-2 justify-content-center">	  
		<button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>', '투표', 1300, 900);return false;" > <i class="bi bi-pencil"></i>  신규 </button>
	<input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" size="30" onkeydown="JavaScript:SearchEnter();" placeholder="검색어 입력"> 
		<button type="button" id="searchBtn" class="btn btn-dark"  > <i class="bi bi-search"></i> 검색 </button>			
	</div>
</div>	   
	   
 <div class="table-responsive"  >
 <table class="table table-hover" id="myTable">
   <thead class="table-primary" >
	    <tr>
			 <th class="text-center" > 번호    </th>
			<th class="text-center" >  등록 </th>   			 
			<th class="text-center" >  마감 </th>   			 
			 <th class="text-center" > 진행상태    </th>
			 <th class="text-center" > 글제목   </th>
			 <th class="text-center" > 작성자   </th>			 
			 </tr>
       </thead>
	<tbody>  
  
<?php  
$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
 while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
  $item_num = $row["num"] ?? '';
  $item_id = $row["id"] ?? '';
  $item_name = $row["name"] ?? '';
  $item_nick = $row["nick"] ?? '';
  $item_hit = $row["hit"] ?? '';
  $item_date = $row["regist_day"] ?? '';
  $deadline = $row["deadline"] ?? '';
  $item_date = substr($item_date, 0, 10);
  $item_subject = $row["subject"] ?? '';
  $status = $row["status"] ?? '';
   
  // 댓글 수 조회 (Prepared Statement 사용)
  $sql_ripple = "SELECT * FROM " . $DB . ".vote_ripple WHERE parent = ?";
  $stmh1 = $pdo->prepare($sql_ripple);
  $stmh1->bindValue(1, $item_num, PDO::PARAM_INT);
  $stmh1->execute();
  $num_ripple = $stmh1->rowCount(); 
 ?>
 
   <tr class="vote-row" data-num="<?= htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8') ?>" data-page="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>" data-tablename="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">

	<td class="text-center" style="width:6%;" >  <?= $start_num ?>      </td>
	<td class="text-center" style="width:10%;" >  <?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?>      </td>     
	<td class="text-center" style="width:10%;" >  <?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>      </td>     
	<td class="text-center"  style="width:10%;">  <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>      </td>
	<td>  <?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?> 

	<?php
	if($num_ripple > 0)
		echo '<span class="badge bg-primary"> ' . $num_ripple . ' </span> ';
	?>
	
	</td>
		<td class="text-center" >  <?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?>      </td>				  
	</tr>
 
 <?php
	$start_num--;
    }
  } catch (PDOException $Exception) {
  error_log("데이터 출력 오류: " . $Exception->getMessage());
  echo "<tr><td colspan='6' class='text-center text-danger'>데이터를 불러오는 중 오류가 발생했습니다.</td></tr>";
  }  
  
 ?>

  	  </tbody>
		  </table>  
</div>
  
   </div> <!--card-body-->
   </div> <!--card -->
   </div> <!--container-->   
   </div> <!--container-->   
   
</form>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var votepageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('votepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var votepageNumber = dataTable.page.info().page + 1;
        setCookie('votepageNumber', votepageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('votepageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 검색 버튼 클릭
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });
    
    // 테이블 행 클릭 이벤트
    $(document).on('click', '.vote-row', function() {
        var num = $(this).data('num');
        var page = $(this).data('page');
        var tablename = $(this).data('tablename');
        redirectToView(num, page, tablename);
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('votepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, page, tablename) {	
    var url = "view.php?num=" + encodeURIComponent(num) + "&page=" + encodeURIComponent(page) + "&tablename=" + encodeURIComponent(tablename);	
    popupCenter(url, '투표', 1250, 900);	
}

function SearchEnter() {
    if (event.keyCode == 13) {
        event.preventDefault();
        document.getElementById('searchBtn').click();
    }
}
</script>

</body> 
</html>