<?php
/**
 * s_board 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;

// 첫 화면 표시 문구
$title_message = '안전보건';

?>
<?php include getDocumentRoot() . '/load_header.php' ?>

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
        
        /* 이미지 최적화 */
        img {
            max-width: 100% !important;
            height: auto !important;
            width: 100% !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.mb-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-2 > * {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        #search {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
        }
        
        #searchBtn,
        #writeBtn {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 통계 정보 텍스트 최적화 */
        .d-flex.mb-2 > span.d-none.d-md-inline {
            display: block !important;
            width: 100%;
            text-align: center;
            margin-bottom: 0.5rem;
            font-weight: bold;
            font-size: 1rem;
        }
        
        /* 테이블 숨기기 (모바일에서는 카드로 표시) */
        #myTable_wrapper {
            display: none !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-cards-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin: 0.5rem 0;
            padding: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: calc(100% - 1rem) !important;
            max-width: calc(100% - 1rem) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            cursor: pointer;
            transition: box-shadow 0.15s ease-in-out;
        }
        
        .mobile-card:active {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        
        .mobile-card-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0.25rem;
            box-sizing: border-box !important;
        }
        
        .mobile-card-label {
            font-weight: bold;
            font-size: 0.875rem;
            color: #495057;
            margin-bottom: 0.25rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-value {
            font-size: 1rem;
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
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
        
        /* jQuery DataTables 컨트롤 숨기기 */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        /* 모바일 카드 숨기기 */
        #mobile-cards-container {
            display: none !important;
        }
    }
</style>

</head>

<body>

<?php require_once(includePath('myheader.php')); ?>

<?php

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    /*   alert("관리자 승인이 필요합니다."); */
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화
$tablename = "s_board";
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$page = $_REQUEST["page"] ?? 1;  
 
// 검색 모드 처리
if ($mode == "search" && !empty($search)) {
    $sql = "select * from " . $DB . "." . $tablename . " where name like '%" . $search . "%' or subject like '%" . $search . "%' or nick like '%" . $search . "%' or regist_day like '%" . $search . "%' order by num desc";
} else {
    $sql = "select * from " . $DB . "." . $tablename . " order by num desc";
}


// 전체 레코드수를 파악한다.
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();    		
			 
 ?>
 
	 
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>">

<div class="container justify-content-center">  


<div class="card mt-2">
<div class="card-body">

  <input type="hidden" id="page" name="page" value="<?=$page?>"  > 
  
  
 <div class="d-flex mt-3 mb-1 justify-content-center">  
    <img src="../img/safetylogo.jpg" style="width:100%;">
  </div>	 
   
<div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center" data-total="<?= $total_row ?>">                
	<span class="d-none d-md-inline">▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?= htmlspecialchars($search) ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off" >
	<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"> <i class="bi bi-search"></i> 검색 </button>			
	<button type="button" class="btn btn-dark btn-sm" id="writeBtn" >  <i class="bi bi-pencil"></i>  신규 </button> &nbsp;&nbsp;&nbsp;				
</div>	
 
<div class="row d-flex"  >
<table class="table table-hover" id="myTable">
   <thead class="table-primary" >
	    <tr>
			 <th class="text-center" > 번호    </th>
			 <th class="text-center" > 구분   </th>
			 <th class="text-center" > 글제목   </th>
			 <th class="text-center" > 작성   </th>
			 <th class="text-center" > 등록일 </th>   			 
			 </tr>
       </thead>
	<tbody>  	   
 
<?php  
$start_num=$total_row;  
$table_data = []; // 모바일 카드 렌더링을 위한 데이터 배열
			 
 while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
  $item_num=$row["num"];
  $item_id = $row["id"] ?? '';
  $item_name = $row["name"] ?? '';
  $item_nick = $row["nick"] ?? '';
  $item_hit = $row["hit"] ?? 0;
  $item_date = $row["regist_day"] ?? '';
  $item_date = substr($item_date, 0, 10);
  $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
  $division = $row["division"] ?? '';
  
  // 모바일 카드용 데이터 저장
  $table_data[] = [
      'num' => $item_num,
      'division' => $division,
      'subject' => $item_subject,
      'nick' => $item_nick,
      'date' => $item_date,
      'start_num' => $start_num
  ];
 ?>
 
	<tr onclick="redirectToView('<?=$item_num?>', '<?=$tablename?>')">  
	  <td class="text-center" data-label="번호">  <?= htmlspecialchars($start_num) ?>      </td>
	  <td class="text-center" data-label="구분">   <?= htmlspecialchars($division) ?>    </td>
	  <td data-label="글제목">  <?= $item_subject ?>   </td>
	  <td class="text-center" data-label="작성">  <?= htmlspecialchars($item_nick) ?>      </td>
	  <td class="text-center" data-label="등록일">  <?= htmlspecialchars($item_date) ?>      </td>     	  
	</tr>     
	
 <?php
    $start_num--;
    }
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
    $total_row = 0;
}  
 
 ?>
  	  </tbody>
		  </table>  
</div>

<!-- 모바일 카드 컨테이너 -->
<div id="mobile-cards-container"></div>

   
</div>
</div>
</div>
   
</form>   

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var researchPageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 모바일 카드 렌더링 함수
function renderMobileCards() {
    if (window.innerWidth > 768) {
        return; // PC에서는 실행하지 않음
    }
    
    var $container = $('#mobile-cards-container');
    $container.empty();
    
    // DataTables에서 현재 표시된 행만 가져오기
    var visibleRows = dataTable.rows({search: 'applied', page: 'current'}).nodes();
    
    if (visibleRows.length === 0) {
        $container.html('<div class="mobile-card"><div class="mobile-card-value">데이터가 없습니다.</div></div>');
        return;
    }
    
    // 표시된 행들을 순회하며 카드 생성
    $(visibleRows).each(function() {
        var $row = $(this);
        
        // DataTables가 숨긴 행은 건너뛰기
        if ($row.hasClass('odd') || $row.hasClass('even')) {
            var num = $row.find('td:eq(0)').text().trim();
            var division = $row.find('td:eq(1)').text().trim();
            var subject = $row.find('td:eq(2)').html() || '';
            var nick = $row.find('td:eq(3)').text().trim();
            var date = $row.find('td:eq(4)').text().trim();
            var onclickAttr = $row.attr('onclick');
            var itemNum = '';
            var tablename = '<?= $tablename ?>';
            
            // onclick에서 num 추출
            if (onclickAttr) {
                var match = onclickAttr.match(/redirectToView\('(\d+)'/);
                if (match) {
                    itemNum = match[1];
                }
            }
            
            // HTML 이스케이프 처리
            var escapeHtml = function(text) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            };
            
            var cardHtml = '<div class="mobile-card" onclick="redirectToView(\'' + escapeHtml(itemNum) + '\', \'' + escapeHtml(tablename) + '\')">';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">번호</span><span class="mobile-card-value">' + escapeHtml(num) + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">구분</span><span class="mobile-card-value">' + escapeHtml(division) + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">글제목</span><span class="mobile-card-value">' + subject + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">작성</span><span class="mobile-card-value">' + escapeHtml(nick) + '</span></div>';
            cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">등록일</span><span class="mobile-card-value">' + escapeHtml(date) + '</span></div>';
            cardHtml += '</div>';
            
            $container.append(cardHtml);
        }
    });
}

// 화면 크기 변경 시 카드 재렌더링
$(window).on('resize', function() {
    if (window.innerWidth <= 768) {
        setTimeout(renderMobileCards, 100);
    }
});

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
        "order": [[0, 'desc']],
        "drawCallback": function(settings) {
            // DataTables 그리기 완료 후 모바일 카드 렌더링
            if (window.innerWidth <= 768) {
                setTimeout(renderMobileCards, 100);
            }
        }
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('researchPageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var researchPageNumber = dataTable.page.info().page + 1;
        setCookie('researchPageNumber', researchPageNumber, 10); // 쿠키에 페이지 번호 저장
        if (window.innerWidth <= 768) {
            setTimeout(renderMobileCards, 100);
        }
    });
    
    // 검색 이벤트 리스너
    dataTable.on('search.dt', function() {
        if (window.innerWidth <= 768) {
            setTimeout(renderMobileCards, 100);
        }
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('researchPageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 초기 모바일 카드 렌더링
    if (window.innerWidth <= 768) {
        setTimeout(renderMobileCards, 300);
    }
});

function restorePageNumber() {
    var savedPageNumber = getCookie('researchPageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num, tablename) {
    var page = researchPageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?num=" + num + "&tablename=" + tablename;          

	customPopup(url, '안전보건', 1200, 900); 		    
}

// 검색 엔터키 처리 함수
function SearchEnter() {
    if (event.keyCode === 13) {
        event.preventDefault();
        performSearch();
    }
}

// 검색 수행 함수
function performSearch() {
    var searchValue = $('#search').val();
    $('#page').val(1); // 검색 시 첫 페이지로 이동
    $('#board_form').submit();
}

$(document).ready(function(){
	// 검색 버튼 클릭 이벤트
	$("#searchBtn").click(function() {
		performSearch();
	});
	
	$("#writeBtn").click(function(){ 
		var page = researchPageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '안전보건', 1300, 850); 	
	 });			 
		
});	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('안전 보건'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>

