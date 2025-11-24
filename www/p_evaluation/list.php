<?php
require_once __DIR__ . '/../common/functions.php';
?>
﻿<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
  
   // 첫 화면 표시 문구   
$title_message = '협력업체 평가표';

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
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            .card-body {
                padding: 0.75rem 0.5rem !important;
                overflow-x: hidden !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            
            /* 제목 이미지 최적화 */
            img.form-control {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                object-fit: contain !important;
            }
            
            /* 제목 최적화 */
            h3 {
                font-size: 1.25rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                text-align: center !important;
            }
            
            /* d-flex 요소 최적화 */
            .d-flex {
                flex-wrap: wrap !important;
            }
            
            .d-flex.justify-content-center,
            .d-flex.justify-content-start,
            .d-flex.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            
            /* 검색 UI 최적화 */
            .d-flex.mb-2.px-5.px-lg-2 {
                padding: 0.5rem !important;
            }
            
            input.form-control {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
                font-size: 1rem !important;
                box-sizing: border-box !important;
            }
            
            /* 버튼 최적화 */
            .btn {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
                font-size: 0.875rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                box-sizing: border-box !important;
            }
            
            .btn-sm {
                font-size: 0.875rem !important;
                padding: 0.5rem !important;
            }
            
            /* 테이블을 카드 형식으로 변환 */
            #myTable_wrapper {
                display: none !important;
            }
            
            #myTable {
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
            }
            
            .mobile-card-item {
                display: flex;
                flex-direction: column;
                margin-bottom: 0.5rem;
                padding: 0.5rem;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .mobile-card-item:last-child {
                border-bottom: none;
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
                font-size: 0.875rem;
                color: #212529;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                word-break: break-word !important;
                white-space: normal !important;
            }
            
            /* jQuery DataTables 컨트롤 숨기기 */
            .dataTables_length,
            .dataTables_filter {
                display: none !important;
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
            
            .modal-body {
                padding: 0.75rem 0.5rem !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                flex: 1 1 auto !important;
                -webkit-overflow-scrolling: touch !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            .modal-footer {
                padding: 0.75rem 0.5rem !important;
                flex-shrink: 0 !important;
            }
            
            .modal-footer .btn {
                width: 100% !important;
                margin: 0.25rem 0 !important;
            }
            
            /* SweetAlert2 모달 최적화 */
            .swal2-popup {
                width: 90% !important;
                max-width: 90% !important;
                padding: 1rem !important;
                font-size: 0.875rem !important;
            }
            
            .swal2-title {
                font-size: 1.125rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            .swal2-content {
                font-size: 0.875rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            .swal2-actions {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            
            .swal2-confirm,
            .swal2-cancel {
                width: 100% !important;
                margin: 0 !important;
            }
            
            /* '기간' 버튼 숨기기 */
            #showdate {
                display: none !important;
            }
        }
        
        /* PC 환경 최적화 */
        @media (min-width: 769px) {
            .d-flex.justify-content-center .btn,
            .d-flex.justify-content-start .btn,
            .d-flex.align-items-center .btn {
                margin-left: 0.25rem !important;
                margin-right: 0.25rem !important;
            }
            
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
    sleep(1);
    header("Location:" . ($_SESSION["WebSite"] ?? '') . "login/login_form.php");
    exit;
}

$tablename = "p_evaluation";

require_once includePath('lib/mydb.php');
$pdo = db_connect();

// 요청 변수 초기화 (?? '' 형태)
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
     
 
$sqlAll = "select * from mirae8440.p_evaluation where txt1 like '%$search%' or txt2 like '%$search%' or txt3 like '%$search%' or txt4 like '%$search%' or txt5 like '%$search%' or txt6 like '%$search%'";

if ($mode == "search") {
    if (!$search) {
        $sql = "select * from mirae8440.p_evaluation order by txt6 desc";
    } else {
        $sql = $sqlAll . " order by txt6 desc";
    }
} else {
    $sql = "select * from mirae8440.p_evaluation order by txt6 desc";
}


// 전체 레코드수 파악
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

try {
    $stmh = $pdo->query($sql); 
		 
			 
?>
  
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>">  
  
<div class="container">  
 <div class="card mt-2 mb-2">  
	<div class="card-body">    	
  
 <div class="d-flex mt-3 mb-1 justify-content-center">  
    <img src="../img/standards.jpg" class="form-control" >
  </div>	 
 <div class="d-flex mt-3 mb-1 justify-content-center">  
  <h3>   <?=$title_message?>  </h3>  
  </div>	 
  
<div class="d-flex mb-2 px-5 px-lg-2 mt-2  justify-content-center align-items-center">                
	▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off" >
	<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"> <i class="bi bi-search"></i> 검색 </button>			
	<button type="button" class="btn btn-dark btn-sm" id="writeBtn" >  <i class="bi bi-pencil"></i>  신규 </button> &nbsp;&nbsp;&nbsp;				
</div>	
	   
<div class="row d-flex"  >
	<!-- 모바일 카드 컨테이너 -->
	<div id="mobile-cards-container"></div>
	
	<table class="table table-hover" id="myTable">
		<thead class="table-primary" >
			<tr>
				 <th class="text-center" data-label="번호">번호  </th>
				 <th class="text-center" data-label="작성일">작성일 </th>
				 <th class="text-center" data-label="업체명">업체명 </th>
				 <th class="text-center" data-label="대표자">대표자 </th>
				 <th class="text-center" data-label="품명">품명 </th>     
				 <th class="text-center" data-label="전년구입실적">전년구입실적 </th>  
				 <th class="text-center" data-label="평가점수">평가점수 </th>     
				 <th class="text-center" data-label="판정결과">판정결과 </th>     
			 </tr>
		</thead>
		<tbody> 
 
 
<?php
    $start_num = $total_row;

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include '_row.php';

        $item_date = isset($item_date) ? substr($item_date, 0, 10) : '';
        $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');

        $txt30 = '';
        if ((int)$txt17 >= 60) {
            $txt30 = '합격';
        }
    ?>
 
			<tr onclick="redirectToView('<?=$num?>', '<?=$tablename?>')" data-num="<?= htmlspecialchars($num) ?>" data-tablename="<?= htmlspecialchars($tablename) ?>">  
				  <td class="text-center" data-label="번호"> <?= htmlspecialchars($start_num) ?> </td>
				  <td class="text-start" data-label="작성일">  <?= htmlspecialchars($txt6) ?>  </td>  
				  <td class="text-center" data-label="업체명"> <?= htmlspecialchars($txt1) ?> </td>			
				  <td class="text-center" data-label="대표자"> <?= htmlspecialchars($txt3) ?> </td> 
				  <td class="text-center" data-label="품명"> <?= htmlspecialchars($txt5) ?> </td>      
				  <td class="text-center" data-label="전년구입실적"> <?= htmlspecialchars($txt4) ?> </td>       
				  <td class="text-center" data-label="평가점수"> <?= htmlspecialchars($txt17) ?> </td>     
				  <td class="text-center" data-label="판정결과"> <?= htmlspecialchars($txt30) ?>  </td>        
			</tr>

    <?php
        $start_num--;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
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
var partnerpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 모바일 카드 렌더링 함수
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-cards-container').empty();
        return;
    }
    
    var container = $('#mobile-cards-container');
    container.empty();
    
    if (typeof dataTable !== 'undefined' && dataTable) {
        // DataTables에서 현재 페이지의 데이터 가져오기
        var rows = dataTable.rows({page: 'current'}).nodes();
        
        $(rows).each(function() {
            var $row = $(this);
            var num = $row.data('num') || '';
            var tablename = $row.data('tablename') || '<?= htmlspecialchars($tablename) ?>';
            var card = $('<div class="mobile-card"></div>');
            
            // 각 셀을 카드 아이템으로 변환
            $row.find('td').each(function() {
                var $cell = $(this);
                var label = $cell.data('label') || $cell.closest('table').find('th').eq($cell.index()).data('label') || '';
                var value = $cell.text().trim();
                
                if (value) {
                    var item = $('<div class="mobile-card-item"></div>');
                    item.append($('<div class="mobile-card-label">' + htmlspecialchars(label) + '</div>'));
                    item.append($('<div class="mobile-card-value">' + htmlspecialchars(value) + '</div>'));
                    card.append(item);
                }
            });
            
            // 클릭 이벤트 추가
            card.on('click', function() {
                redirectToView(num, tablename);
            });
            
            container.append(card);
        });
    } else {
        // DataTables가 없을 경우 일반 테이블에서 데이터 추출
        $('#myTable tbody tr').each(function() {
            var $row = $(this);
            var num = $row.data('num') || '';
            var tablename = $row.data('tablename') || '<?= htmlspecialchars($tablename) ?>';
            var card = $('<div class="mobile-card"></div>');
            
            $row.find('td').each(function() {
                var $cell = $(this);
                var label = $cell.data('label') || $('#myTable thead th').eq($cell.index()).data('label') || '';
                var value = $cell.text().trim();
                
                if (value) {
                    var item = $('<div class="mobile-card-item"></div>');
                    item.append($('<div class="mobile-card-label">' + htmlspecialchars(label) + '</div>'));
                    item.append($('<div class="mobile-card-value">' + htmlspecialchars(value) + '</div>'));
                    card.append(item);
                }
            });
            
            card.on('click', function() {
                redirectToView(num, tablename);
            });
            
            container.append(card);
        });
    }
}

// HTML 특수문자 이스케이프 함수
function htmlspecialchars(str) {
    if (typeof str !== 'string') return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return str.replace(/[&<>"']/g, function(m) { return map[m]; });
}

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
    var savedPageNumber = getCookie('partnerpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var partnerpageNumber = dataTable.page.info().page + 1;
        setCookie('partnerpageNumber', partnerpageNumber, 10); // 쿠키에 페이지 번호 저장
        renderMobileCards(); // 모바일 카드 다시 렌더링
    });
    
    // 검색 이벤트 리스너
    dataTable.on('search.dt', function() {
        renderMobileCards(); // 모바일 카드 다시 렌더링
    });
    
    // 그리기 이벤트 리스너
    dataTable.on('draw.dt', function() {
        renderMobileCards(); // 모바일 카드 다시 렌더링
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('partnerpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 초기 모바일 카드 렌더링
    renderMobileCards();
    
    // 리사이즈 이벤트
    $(window).on('resize', function() {
        renderMobileCards();
    });
    
    // jQuery DataTables 컨트롤 숨기기/보이기
    function toggleDataTablesControls() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    }
    
    toggleDataTablesControls();
    $(window).on('resize', function() {
        toggleDataTablesControls();
    });
    
    // 검색 버튼 클릭 이벤트
    $("#searchBtn").click(function() {
        $("#board_form").submit();
    });
    
    // Enter 키 검색
    function SearchEnter() {
        if (event.keyCode === 13) {
            $("#board_form").submit();
        }
    }
    window.SearchEnter = SearchEnter;
});

function restorePageNumber() {
    var savedPageNumber = getCookie('partnerpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num, tablename) {
    var page = partnerpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "write_form.php?num=" + num + "&tablename=" + tablename;          

	customPopup(url, '협력업체 평가표', 1200, 900); 		    
}

$(document).ready(function(){
	
	$("#writeBtn").click(function(){ 
		var page = partnerpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '협력업체 평가표', 1300, 850); 	
	 });			 
		
});	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('협력업체 평가표'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>
