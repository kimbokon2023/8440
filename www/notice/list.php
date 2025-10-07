<?php
require_once __DIR__ . '/../common/functions.php';
?>
﻿<?php require_once(includePath('session.php'));
   
// 첫 화면 표시 문구
$title_message = '공지사항';

?>
<?php include getDocumentRoot() . '/load_header.php' ?> 
  
<title>  <?=$title_message?>  </title> 
<style>
	.table-hover tbody tr:hover {
		cursor: pointer;
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

$tablename = "notice";
  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
	 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";

       if(isset($_REQUEST["search"]))   // search 쿼리스트링 값 할당 체크
         $search=$_REQUEST["search"];
       else 
         $search="";     
     
   if($mode=="search"){
         if(!$search) {
				$sql ="select * from mirae8440." . $tablename . " order  by num desc "; 				
             }
              $sql="select * from mirae8440." . $tablename . " where name like '%$search%' or subject like '%$search%'  or nick like '%$search%'  or regist_day like '%$search%'   or searchtext like '%$search%'  order by num desc ";              
       } else {
              $sql="select * from mirae8440." . $tablename . " order  by num desc";              
       }

try{  

$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
$total_row=$stmh->rowCount();  

   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  	 	 
try{  
	$stmh = $pdo->query($sql); 
	  
?>
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>">
   
<div class="container justify-content-center">  
	<div class="card mt-2 mb-4">  
	<div class="card-body">  
		<div class="d-flex mt-3 mb-2 justify-content-center">  
			<h5>  <?=$title_message?> </h5> 
			<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  			
		</div>	
		<div class="d-flex mt-3 mb-1 justify-content-center  align-items-center">   				
			▷  <?= $total_row ?> &nbsp; 			
			<div class="inputWrap">
				<input type="text" id="search" class="form-control mx-1" style="width:150px;" name="search" autocomplete="off" value="<?=$search?>" placeholder="검색어" onkeydown="JavaScript:SearchEnter();" >
				<button class="btnClear"></button>
			</div>				
			<button id="searchBtn" type="button" class="btn btn-dark btn-sm mx-1"><i class="bi bi-search"></i> 검색 </button>
			<button type="button" class="btn btn-dark  btn-sm mx-1" id="writeBtn"> <i class="bi bi-pencil"></i>  신규  </button> 			   
		</div>		
	   	   
	 <div class="row d-flex"  >
	 <table class="table table-hover" id="myTable">
	   <thead class="table-primary" >
			<tr>
				 <th class="text-center" > 번호    </th>
				 <th class="text-center" > 글제목   </th>
				 <th class="text-center" > 작성자   </th>
				 <th class="text-center" > 등록일자 </th>   
				 <th class="text-center" > 조회수   </th>   
				 </tr>
		   </thead>
		<tbody>  
  
<?php    
  $start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번  			 
 while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
  $item_num=$row["num"];
  $item_id=$row["id"];
  $item_name=$row["name"];
  $item_nick=$row["nick"];
  $item_hit=$row["hit"];
  $item_date=$row["regist_day"];
  $item_date=substr($item_date, 0, 10);
  $item_subject=str_replace(" ", "&nbsp;", $row["subject"]);
   
  $sql="select * from mirae8440.notice_ripple where parent=$item_num";
  $stmh1 = $pdo->query($sql); 
  $num_ripple=$stmh1->rowCount(); 
 ?>
 
   <tr onclick="redirectToView('<?=$item_num?>', '<?=$tablename?>')">
  
				  <td class="text-center" >  <?= $start_num ?>      </td>
				  <td>  <?= $item_subject ?>                     
				<?php
				   if($num_ripple>0)
					    echo '<span class="badge bg-primary "> '.$num_ripple.' </span> ';
				?>
				  </td>
				  <td class="text-center" >  <?= $item_nick ?>      </td>
				  <td class="text-center" >  <?= $item_date ?>      </td>     
				  <td class="text-center" >  <?= $item_hit ?>       </td>    
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
   </div> <!--card-body-->
   </div> <!--card -->
   </div> <!--container-->
	</form>   
	
<script>
var dataTable; // DataTables 인스턴스 전역 변수
var noticepageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('noticepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var noticepageNumber = dataTable.page.info().page + 1;
        setCookie('noticepageNumber', noticepageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('noticepageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('noticepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = noticepageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?num=" + num + "&tablename=" + tablename;          

	customPopup(url, '공지사항', 1200, 900); 		    
}

$(document).ready(function(){
	
	$("#writeBtn").click(function(){ 
		var page = noticepageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '공지사항', 1300, 850); 	
	 });			 
		
});	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('공지사항'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>