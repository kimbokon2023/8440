<?php
/**
 * 작업표준서 목록 페이지
 * 작업표준서 게시판의 목록을 표시
 */
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 첫 화면 표시 문구
$title_message = '작업표준서';

 ?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title>  <?=$title_message?>  </title>

    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
        
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
            
            /* 제목 이미지 최적화 */
            .card-body img {
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
            
            /* 검색 UI 최적화 */
            .d-flex.justify-content-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                flex-wrap: wrap !important;
            }
            
            .d-flex.justify-content-center .form-control,
            .d-flex.justify-content-center .btn {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
                box-sizing: border-box !important;
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
            
            .modal-body img {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }
            
            .modal-footer {
                padding: 0.75rem 0.5rem !important;
                flex-shrink: 0 !important;
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

<?php

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	/*   alert("관리자 승인이 필요합니다."); */
	sleep(1);
	header("Location:".$_SESSION["WebSite"]."login/login_form.php");
	exit;
}

$tablename = "p_workstandard";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 요청 변수 안전하게 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';

if($mode=="search"){
	if(!$search) {
		$sql ="select * from mirae8440." . $tablename . " order  by num desc   ";
	}
	$sql="select * from mirae8440." . $tablename . " where name like '%$search%' or subject like '%$search%'  or nick like '%$search%'  or regist_day like '%$search%'  order by num desc   ";
} else {
	$sql="select * from mirae8440." . $tablename . " order  by num desc  ";
}


// 전체 레코드수를 파악한다.
try{
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();

 ?>

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>">
  <input type="hidden" id="page" name="page" value="<?=$page ?? ''?>"  >

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
<table class="table table-hover" id="myTable">
		<thead class="table-primary" >
			<tr>
				 <th class="text-center" > 번호  </td>
				 <th class="text-center" > 작업표준명  </td>
				 <th class="text-center" > 등록인 </td>
				 <th class="text-center" > 등록일자 </td>
				 <th class="text-center" > 조회수 </td>
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
  $item_hit = $row["hit"] ?? 0;
  $item_date = $row["regist_day"] ?? '';
  $item_date = substr($item_date, 0, 10);
  $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
 ?>
    <tr onclick="redirectToView('<?=$item_num?>','<?=$tablename?>')">

				  <td class="text-center" data-label="번호">  <?= $start_num ?>  </td>
				  <td class="text-start" data-label="작업표준명">  <?= $item_subject ?>  </td>
				  <td class="text-center" data-label="등록인">  <?= $item_nick ?> </td>
				  <td class="text-center" data-label="등록일자">  <?= $item_date ?> </td>
				  <td class="text-center" data-label="조회수">  <?= $item_hit ?> </td>
			    </tr>

 <?php
	$start_num--;
    }
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
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
var workstandpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('workstandpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var workstandpageNumber = dataTable.page.info().page + 1;
        setCookie('workstandpageNumber', workstandpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('workstandpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('workstandpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num, tablename) {
    var page = workstandpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)

    var url = "view.php?num=" + num + "&tablename=" + tablename;

	customPopup(url, '작업표준서', 1200, 900);
}

$(document).ready(function(){

	$("#writeBtn").click(function(){
		var page = workstandpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
		var tablename = '<?php echo $tablename; ?>';
		var url = "write_form.php?tablename=" + tablename;
		customPopup(url, '작업표준서', 1300, 850);
	 });

});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('작업표준서 조회'); // 다른 페이지에 맞는 menuName을 전달
});
</script>
</body>
</html>
