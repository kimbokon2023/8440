<?php
session_start();

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost' : 'http://8440.co.kr';

// Initialize session variables with null safety
$level = $_SESSION["level"] ?? null;
$user_name = $_SESSION["name"] ?? '';

if (!isset($_SESSION["level"]) || $level > 5) {
    /*   alert("관리자 승인이 필요합니다."); */
    sleep(2);
    header("Location: {$base_url}/login/login_form.php");
    exit;
}
  
// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//header("Refresh:0");  // reload refresh   

							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	

function conv_num($num) {
$number = (float)str_replace(',', '', $num);
return $number;
}
 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Tabulator CSS & JS -->
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css"> 

 
 <title> 미래기업 금속작품 관리화면 </title> 
 </head>
 
 <?php

// Request variables with null safety
$search = $_REQUEST["search"] ?? '';  // 목록표에 제목,이름 등 나오는 부분

  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  
  
// Page number with default value
$page = $_REQUEST["page"] ?? 1;  // 페이지 번호, 기본값 1
 
  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
// Mode parameter
$mode = $_REQUEST["mode"] ?? '';     
 
// 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

if ($fromdate == '') {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if ($todate == '') {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

 				
$sql="select * from mirae8440.shopitem order by num desc" ; 					
$sqlcon = "select * from mirae8440.shopitem order by num desc" ;   // 전체 레코드수를 파악하기 위함.					
					
$tableData = array(); // Tabulator용 데이터 배열

try{  
// 레코드 전체 sql 설정
   $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
   $temp2=$allstmh->rowCount();  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $temp1=$stmh->rowCount();
      
   $total_row = $temp2;     // 전체 글수	  		
         					 
   $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
   $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
      $tableData[] = array(
         'num' => $row["num"],
         'catagory' => $row["catagory"],
         'dporder' => $row["dporder"],
         'item' => $row["item"],
         'itemdes' => $row["itemdes"],
         'sale' => $row["sale"],
         'price' => $row["price"],
         'saleprice' => $row["saleprice"],
         'filename1' => $row["filename1"],
         'youtube1' => $row["youtube1"],
         'youtube2' => $row["youtube2"]
      );
   }		 
} catch (PDOException $Exception) {
   print "오류: ".$Exception->getMessage();
}			 
?>		 

<body>
 <div id="wrap">   
   <div id="content">			 
  <form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
   <div id="col2">   
   
					<input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
					<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
					<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
				    <input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 					
					
            <div id="vacancy" style="display:none">  </div>
        <div id="display_board" style="margin-top:20px; height:60px;font-size:20px;color:blue; display:none"> 
      </div>	

			 
   <div class="clear"></div>	

   <div class="clear"></div>	

							 
	    

	<input id="Call_More" type=hidden value="0" >		 	
	
	
	
<div id=list_board >    
	 
      <div class="clear"></div>	 
 <!-- <div id="title2">
 <div id class="blink"  style="white-space:nowrap">  <font color=red> *****  AS 진행 현황 ***** </font> </div>
	  </div>  -->

        <div id="list_search">
        <div id="list_search1" style="font-size:12px;"> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ▷ 총 <?= $total_row ?> 건
		&nbsp;&nbsp;&nbsp;		
		<button type="button" class="btn btn-dark" onclick="openWriteModal()"> <i class="bi bi-pencil-square"></i> 글쓰기  </button> &nbsp;&nbsp;&nbsp;
		
		</div>
		</div> <!-- end of list_search -->
      <div class="clear"></div>
	  
      <!-- Tabulator 테이블 -->
      <div id="shop-table" style="margin: 20px 0;"></div>	
		<br><br><br><br><br>
     </div>

<div id="write_button">    

  <br><br><br>
      </div>
     </div>   
 
	</form>

<form name="settingsFrm" id="settingsFrm"  method="post" action="settings.php">  	
</form>	
	 </div>   
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
   </div> 	   
  </div> <!-- end of wrap -->

<!-- 상세보기 모달 -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">작품 상세정보</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalContent">
        <div class="text-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
        <button type="button" class="btn btn-primary" id="editBtn"><i class="bi bi-pencil"></i> 수정</button>
        <button type="button" class="btn btn-danger" id="deleteBtn"><i class="bi bi-trash"></i> 삭제</button>
      </div>
    </div>
  </div>
</div>

<!-- 글쓰기/수정 모달 -->
<div class="modal fade" id="writeModal" tabindex="-1" aria-labelledby="writeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="writeModalLabel">작품 등록/수정</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <iframe id="writeFrame" src="" style="width:100%; height:100%; border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// PHP에서 전달된 데이터
var tableData = <?php echo json_encode($tableData); ?>;

console.log("=== Tabulator Initialization ===");
console.log("Table data count:", tableData.length);
console.log("First row sample:", tableData[0]);

// Tabulator 초기화
var table = new Tabulator("#shop-table", {
    data: tableData,
    layout: "fitColumns",
    pagination: "local",
    paginationSize: 50,
    paginationSizeSelector: [10, 25, 50, 100],
    movableColumns: true,
    resizableRows: false,
    initialSort: [
        {column: "num", dir: "desc"}
    ],
    columns: [
        {title: "번호", field: "num", width: 80, sorter: "number", headerFilter: "input"},
        {title: "카테고리", field: "catagory", width: 120, headerFilter: "input"},
        {title: "DP순서", field: "dporder", width: 100, sorter: "number"},
        {title: "아이템", field: "item", width: 200, headerFilter: "input"},
        {
            title: "사진", 
            field: "filename1", 
            width: 120,
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if(value && value !== '') {
                    return '<img src="' + value + '" style="width:80px; height:80px; object-fit:cover;">';
                }
                return '';
            }
        },
        {title: "유튜브1", field: "youtube1", width: 100, visible: false},
        {title: "유튜브2", field: "youtube2", width: 100, visible: false},
        {title: "상세설명", field: "itemdes", width: 650, formatter: "textarea"},
        {
            title: "Sale", 
            field: "sale", 
            width: 80,
            formatter: function(cell) {
                return '<span style="color:red;">' + cell.getValue() + '</span>';
            }
        },
        {
            title: "최초가격", 
            field: "price", 
            width: 120,
            sorter: "number",
            formatter: function(cell) {
                var value = cell.getValue();
                if(value && value != 0) {
                    return '<span style="color:blue;">' + Number(value).toLocaleString() + '원</span>';
                }
                return '';
            }
        },
        {
            title: "판매가격", 
            field: "saleprice", 
            width: 120,
            sorter: "number",
            formatter: function(cell) {
                var value = cell.getValue();
                if(value && value != 0) {
                    return '<span style="color:brown; font-weight:bold;">' + Number(value).toLocaleString() + '원</span>';
                }
                return '';
            }
        }
    ],
    rowClick: function(e, row) {
        console.log("=== Row Click Event Triggered ===");
        var data = row.getData();
        var num = data.num;
        console.log("Row Data:", data);
        console.log("Item Num:", num);

        if(num) {
            alert("행 클릭됨! 번호: " + num);
            showDetailModal(num);
        }
    }
});

console.log("=== Tabulator Instance Created ===");
console.log("Table object:", table);

// Tabulator가 완전히 렌더링된 후
table.on("tableBuilt", function(){
    console.log("=== Table Built Successfully ===");
    console.log("Row count:", table.getDataCount());
});

table.on("dataLoaded", function(data){
    console.log("=== Data Loaded ===");
    console.log("Loaded rows:", data.length);
});

// 상세보기 모달 표시
function showDetailModal(num) {
    console.log("=== showDetailModal Function Called ===");
    console.log("Received num:", num);

    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
    console.log("Modal instance created:", myModal);

    $('#modalContent').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');

    myModal.show();
    console.log("Modal show() called");

    // AJAX로 상세 정보 로드
    $.ajax({
        url: 'read_DB.php',
        type: 'GET',
        data: { num: num },
        beforeSend: function() {
            console.log("AJAX request started for num:", num);
        },
        success: function(response) {
            console.log("AJAX success. Response length:", response.length);
            console.log("Response preview:", response.substring(0, 200));
            $('#modalContent').html(response);

            // 수정/삭제 버튼에 num 저장
            $('#editBtn').data('num', num);
            $('#deleteBtn').data('num', num);
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
            console.error("XHR:", xhr);
            $('#modalContent').html('<div class="alert alert-danger">데이터를 불러오는데 실패했습니다.</div>');
        }
    });
}

// 글쓰기 모달 열기
function openWriteModal(num) {
    var writeModal = new bootstrap.Modal(document.getElementById('writeModal'));
    var url = 'write_form.php?';
    
    if(num) {
        url += 'mode=modify&num=' + num + '&page=<?=$page?>';
        $('#writeModalLabel').text('작품 수정');
    } else {
        url += 'mode=new&page=<?=$page?>';
        $('#writeModalLabel').text('작품 등록');
    }
    
    $('#writeFrame').attr('src', url);
    writeModal.show();
    
    // 상세보기 모달이 열려있으면 닫기
    var detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
    if(detailModal) {
        detailModal.hide();
    }
}

// 모달에서 저장 완료 후 페이지 리로드를 위한 메시지 리스너
window.addEventListener('message', function(event) {
    if(event.data === 'reloadPage') {
        location.reload();
    } else if(event.data === 'closeModal') {
        // 글쓰기 모달 닫기
        var writeModal = bootstrap.Modal.getInstance(document.getElementById('writeModal'));
        if(writeModal) {
            writeModal.hide();
        }
    }
});

// 수정 버튼 클릭
$('#editBtn').click(function() {
    var num = $(this).data('num');
    if(num) {
        openWriteModal(num);
    }
});

// 삭제 버튼 클릭
$('#deleteBtn').click(function() {
    var num = $(this).data('num');
    if(num && confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?')) {
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { num: num, page: <?=$page?> },
            success: function(response) {
                alert('삭제되었습니다.');
                location.reload();
            },
            error: function() {
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    }
});

$(document).ready(function(){
	movetoPage = function(page){ 	  
	  $("#page").val(page); 
      var echo="<?php echo $partOpt ?? ''; ?>"; 
      var searchOpt="<?php echo $searchOpt ?? ''; ?>"; 
      var search="<?php echo $search; ?>"; 

     $("#partOpt").val(partOpt);
     $("#searchOpt").val(searchOpt);
     $("#search").val(search);
	 $("#mainFrm").submit();  
	}	
});

function saveAsFile(str, filename) {  // text파일로 저장하기
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:attachment/text,' + encodeURI(str);
    hiddenElement.target = '_blank';
    hiddenElement.download = filename;
    hiddenElement.click();
}

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}
</script>
  </body>
  </html>