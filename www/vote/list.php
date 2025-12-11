<?php
require_once __DIR__ . '/../bootstrap.php';
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
	
	/* 모바일 환경 최적화 */
	@media (max-width: 768px) {
		/* body와 html 오버플로우 방지 */
		html, body {
			overflow-x: hidden !important;
			max-width: 100vw !important;
			width: 100% !important;
			box-sizing: border-box !important;
		}
		
		* {
			max-width: 100vw !important;
			box-sizing: border-box !important;
		}
		
		/* 컨테이너 최적화 */
		.container,
		.container-fluid {
			padding: 0.5rem !important;
			max-width: 100vw !important;
			width: 100% !important;
			box-sizing: border-box !important;
			margin: 0 auto !important;
			overflow-x: hidden !important;
		}
		
		/* 카드 최적화 */
		.card {
			margin: 0.5rem auto !important;
			width: calc(100vw - 1rem) !important;
			max-width: calc(100vw - 1rem) !important;
			box-sizing: border-box !important;
			overflow-x: hidden !important;
		}
		
		.card-body {
			padding: 0.75rem !important;
			overflow-x: hidden !important;
		}
		
		/* 제목 영역 최적화 */
		.d-flex.mt-3.mb-2.justify-content-center.align-items-center {
			flex-direction: column !important;
			align-items: stretch !important;
			gap: 0.5rem !important;
			padding: 0.5rem !important;
		}
		
		.d-flex.mt-3.mb-2.justify-content-center.align-items-center h4 {
			font-size: 1.25rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			text-align: center !important;
			margin: 0.5rem 0 !important;
		}
		
		.d-flex.mt-3.mb-2.justify-content-center.align-items-center img {
			max-width: 80px !important;
			width: 80px !important;
			height: auto !important;
		}
		
		/* 검색 UI 최적화 */
		.d-flex.mt-3.mb-1.justify-content-center {
			flex-direction: column !important;
			align-items: stretch !important;
			gap: 0.5rem !important;
			padding: 0.5rem !important;
		}
		
		.input-group {
			flex-direction: column !important;
			align-items: stretch !important;
			gap: 0.5rem !important;
			width: 100% !important;
			max-width: 100% !important;
		}
		
		.input-group input[type="text"] {
			width: 100% !important;
			max-width: 100% !important;
			margin: 0.25rem 0 !important;
			padding: 0.5rem !important;
			font-size: 1rem !important;
			height: 40px !important;
		}
		
		.input-group button {
			width: 100% !important;
			max-width: 100% !important;
			margin: 0.25rem 0 !important;
			padding: 0.5rem !important;
			font-size: 1rem !important;
		}
		
		/* DataTables 컨트롤 숨기기 */
		#myTable_wrapper .dataTables_length {
			display: none !important;
		}
		
		#myTable_wrapper .dataTables_filter {
			display: none !important;
		}
		
		/* 테이블 숨기기 (데이터는 읽을 수 있도록) */
		#myTable {
			visibility: hidden !important;
			position: absolute !important;
			left: -9999px !important;
		}
		
		/* 모바일 카드 컨테이너 */
		#mobile-card-container {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
			padding: 0.5rem !important;
		}
		
		.mobile-card {
			background: #fff;
			border: 1px solid #dee2e6;
			border-radius: 0.375rem;
			padding: 1rem;
			margin-bottom: 1rem;
			box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
			cursor: pointer;
			transition: box-shadow 0.15s ease-in-out;
			width: 100% !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
		}
		
		.mobile-card:hover {
			box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
		}
		
		.mobile-card-title {
			font-size: 1.1rem;
			font-weight: bold;
			margin-bottom: 0.75rem;
			color: #212529;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		.mobile-card-item {
			padding: 0.5rem 0;
			border-bottom: 1px solid #f0f0f0;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		.mobile-card-item:last-child {
			border-bottom: none;
		}
		
		.mobile-card-label {
			font-weight: 600;
			color: #6c757d;
			display: inline-block;
			min-width: 80px;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		.mobile-card-value {
			color: #212529;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		/* 텍스트 오버플로우 방지 */
		* {
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			box-sizing: border-box !important;
		}
		
		/* 모든 텍스트 요소 강제 줄바꿈 */
		p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			word-break: break-word !important;
			white-space: normal !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
		}
		
		/* span 요소 줄바꿈 처리 */
		span {
			display: inline-block !important;
			overflow: visible !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
		}
		
		/* 모든 div 요소 오버플로우 방지 */
		div {
			max-width: 100vw !important;
			overflow-x: hidden !important;
			box-sizing: border-box !important;
		}
		
		/* '기간' 버튼 숨기기 */
		#showdate {
			display: none !important;
		}
		
		/* 모달 최적화 */
		.modal {
			padding: 0 !important;
			overflow: hidden !important;
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
			box-sizing: border-box !important;
		}
		
		.modal-header {
			padding: 0.75rem 0.5rem !important;
			flex-shrink: 0 !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		.modal-title {
			font-size: 1rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		.modal-body {
			flex: 1 !important;
			overflow-y: auto !important;
			overflow-x: hidden !important;
			padding: 0.75rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			-webkit-overflow-scrolling: touch !important;
		}
		
		.modal-footer {
			padding: 0.75rem 0.5rem !important;
			flex-shrink: 0 !important;
			flex-direction: column !important;
			gap: 0.5rem !important;
		}
		
		.modal-footer button {
			width: 100% !important;
			max-width: 100% !important;
			margin: 0 !important;
			padding: 0.5rem !important;
			font-size: 1rem !important;
		}
	}
	
	/* PC 환경에서 모바일 카드 컨테이너 숨기기 */
	@media (min-width: 769px) {
		#mobile-card-container {
			display: none !important;
		}
	}
</style>  
</head> 

<body>

<?php require_once(includePath('myheader.php')); ?>   

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
	   
 <!-- 모바일 카드 컨테이너 -->
 <div id="mobile-card-container" style="display: none;"></div>
 
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
        "searching": false, // 모바일에서 수동 검색 사용
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']],
        "initComplete": function() {
            // 모바일에서 카드 렌더링
            if (window.innerWidth <= 768) {
                setTimeout(function() {
                    renderMobileCards();
                }, 500);
            }
        },
        "drawCallback": function() {
            // 모바일에서 카드 재렌더링
            if (window.innerWidth <= 768) {
                setTimeout(function() {
                    renderMobileCards();
                }, 300);
            }
        }
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
    
    // 모바일 카드 클릭 이벤트
    $(document).on('click', '.mobile-card', function() {
        var num = $(this).data('num');
        var page = $(this).data('page');
        var tablename = $(this).data('tablename');
        redirectToView(num, page, tablename);
    });
    
    // 창 크기 변경 시 카드/테이블 전환
    $(window).on('resize', function() {
        if (window.innerWidth <= 768) {
            renderMobileCards();
        }
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

/**
 * 모바일에서 테이블을 카드 형식으로 렌더링
 */
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-card-container').hide();
        return;
    }
    
    $('#mobile-card-container').show();
    $('#mobile-card-container').empty();
    
    // 원본 테이블에서 데이터 읽기
    var rows = $('#myTable tbody tr.vote-row');
    
    if (rows.length === 0) {
        $('#mobile-card-container').html('<div class="text-center text-muted p-3">데이터가 없습니다.</div>');
        return;
    }
    
    rows.each(function() {
        var $row = $(this);
        var num = $row.data('num');
        var page = $row.data('page');
        var tablename = $row.data('tablename');
        
        var tds = $row.find('td');
        if (tds.length < 6) return;
        
        var 번호 = escapeHtml($(tds[0]).text().trim());
        var 등록 = escapeHtml($(tds[1]).text().trim());
        var 마감 = escapeHtml($(tds[2]).text().trim());
        var 진행상태 = escapeHtml($(tds[3]).text().trim());
        var 글제목 = escapeHtml($(tds[4]).text().trim());
        var 작성자 = escapeHtml($(tds[5]).text().trim());
        
        // 댓글 배지 추출
        var badgeHtml = '';
        var badge = $row.find('td:eq(4) .badge');
        if (badge.length > 0) {
            var badgeText = badge.text().trim();
            badgeHtml = '<span class="badge bg-primary ms-2">' + escapeHtml(badgeText) + '</span>';
        }
        
        // 유효성 검사
        if (!번호 || 번호 === '' || 번호 === 'No data available in table') {
            return;
        }
        
        var cardHtml = '<div class="mobile-card" data-num="' + escapeHtml(num) + '" data-page="' + escapeHtml(page) + '" data-tablename="' + escapeHtml(tablename) + '">' +
            '<div class="mobile-card-title">' + 글제목 + badgeHtml + '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + 번호 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">등록일:</span>' +
                '<span class="mobile-card-value">' + 등록 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">마감일:</span>' +
                '<span class="mobile-card-value">' + 마감 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">진행상태:</span>' +
                '<span class="mobile-card-value">' + 진행상태 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작성자:</span>' +
                '<span class="mobile-card-value">' + 작성자 + '</span>' +
            '</div>' +
        '</div>';
        
        $('#mobile-card-container').append(cardHtml);
    });
}

/**
 * HTML 이스케이프 함수
 */
function escapeHtml(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>

</body> 
</html>