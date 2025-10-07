<?php 
require_once(includePath('session.php'));
   // 첫 화면 표시 문구
 $title_message = '투표'; 
 ?>
 
<?php 
include getDocumentRoot() . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 $_SESSION["url"]='https://8440.co.kr/vote/list.php'; 	 
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
				$sql ="select * from mirae8440." . $tablename . " order  by num desc   "; 				
             }
              $sql="select * from mirae8440." . $tablename . " where name like '%$search%' or subject like '%$search%'  or nick like '%$search%'  or regist_day like '%$search%'   or searchtext like '%$search%'  order by num desc   ";             
       } else {
              $sql="select * from mirae8440." . $tablename . " order  by num desc  ";
       }

  // 전체 레코드수를 파악한다.
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

  <input type="hidden" id="page" name="page" value="<?=$page?>"  > 
  <input type="hidden" id="scale" name="scale" value="<?=$scale?>"  >   


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
		<button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form.php?tablename=<?=$tablename?>', '투표', 1300, 900);return false;" > <i class="bi bi-pencil"></i>  신규 </button>
	<input type="text" name="search" id="search" value="<?=$search?>" size="30" onkeydown="JavaScript:SearchEnter();" placeholder="검색어 입력"> 
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
  $item_num=$row["num"];
  $item_id=$row["id"];
  $item_name=$row["name"];
  $item_nick=$row["nick"];
  $item_hit=$row["hit"];
  $item_date=$row["regist_day"];
  $deadline=$row["deadline"];
  $item_date=substr($item_date, 0, 10);
  $item_subject=str_replace(" ", "&nbsp;", $row["subject"]);
  $status=$row["status"];
   
  $sql="select * from mirae8440.vote_ripple where parent=$item_num";
  $stmh1 = $pdo->query($sql); 
  $num_ripple=$stmh1->rowCount(); 
 ?>
 
   <tr onclick="redirectToView('<?=$item_num?>', '<?=$page?>', '<?=$tablename?>')">

	<td class="text-center" style="width:6%;" >  <?= $start_num ?>      </td>
	<td class="text-center" style="width:10%;" >  <?= $item_date ?>      </td>     
	<td class="text-center" style="width:10%;" >  <?= $deadline ?>      </td>     
	<td class="text-center"  style="width:10%;">  <?= $status ?>      </td>
	<td>  <?= $item_subject ?> 

	<?php
	if($num_ripple>0)
		echo '<span class="badge bg-primary "> '.$num_ripple.' </span> ';
	?>
	
	</td>
		<td class="text-center" >  <?= $item_nick ?>      </td>				  
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
   </div> <!--container-->   
   
	</form>   

</body> 
</html>
   

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
});

function restorePageNumber() {
    var savedPageNumber = getCookie('votepageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

$(document).ready(function(){	
	$("#writeBtn").click(function(){ 
		var page = votepageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '인사교육총무', 1300, 850); 	
	 });			 
		
});	
	
	
function redirectToView(num, page, tablename) {	
	var url = "view.php?num=" + num + "&page=" + page + "&tablename=" + tablename;	
	popupCenter(url , '투표', 1250, 900);	
}
   
</script>