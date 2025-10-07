<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
$menu=$_REQUEST["menu"] ?? '';
?>  
<?php include getDocumentRoot() . '/load_header.php' ?>
    
<title> 품질분임조 </title> 

<style>
.modal .modal-full {
    max-width: 94%
}

.modal .white {
    color: #fff
}

.modal .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center
}

.modal .modal-header .modal-title {
    font-size: 1.1rem
}

.modal .modal-header .close {
    padding: 7px 10px;
    border-radius: 50%;
    background: none;
    border: none
}

.modal .modal-header .close:hover {
    background: #dee2e6
}

.modal .modal-header i,.modal .modal-header svg {
    font-size: 12px;
    height: 12px;
    width: 12px
}

.modal .modal-footer {
    padding: 1rem
}

.modal.modal-borderless .modal-header {
    border-bottom: 0
}

.modal.modal-borderless .modal-footer {
    border-top: 0
}
</style>  
   
</head>
 
 <body>   
<?php require_once(includePath('common/modal.php')); ?>   
<?php   if( $menu!=='no') 
	require_once(includePath('myheader.php')); ?>   

<?php	   
if(!isset($_SESSION["level"]) ) {	          
		 $_SESSION["url"]='https://8440.co.kr/errormeeting/index.php?user_name=' . $user_name; 	
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
} 
   
if($user_name=='소현철' ||$user_name=='김보곤' ||$user_name=='최장중' ||$user_name=='이경묵')
	  $admin = 1;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

$ip_address = $_SERVER["REMOTE_ADDR"];

$ip_address = 'ip_(불량보고) : '.$ip_address;  

// 접속 ip 기록
$data=date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] . '  ' . $ip_address ;	 
$pdo->beginTransaction();
$sql = "insert into {$DB}.logdata(data) values(?) " ;
$stmh = $pdo->prepare($sql); 
$stmh->bindValue(1, $data, PDO::PARAM_STR);   
$stmh->execute();
$pdo->commit(); 
 
$tablename = 'emeeting';
 
// 결재권자 결재정보 보기 			
if($admin==1)
{	
	$sql="select * from {$DB}.emeeting where approve<>'처리완료' " ; 					
	$approvalwait = 0 ;

	try{  
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				  include "rowDB.php";
				  $approvalwait += 1;
				}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
}
 
// 서버의 정보를 읽어와 랜덤으로 메인화면 꾸미기
 				
$sql="select * from {$DB}.emeeting order by num desc " ; 					

$numarr = array();

try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
              include "rowDB.php";
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>

<form name="board_form" id="board_form"  method="post" action="./index.php" >  

	<input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"  > 					
	<input type="hidden" id="page" name="page" value="<?=$page?>"  > 	
	
<div class="container mb-5">  
<div class="modal fade" id="notice_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div class="modal-header text-center">
			  <h2 > 알림</h2>							
		</div>
      </div>
      <div class="modal-body">
			<img src="../img/norice_errorreport1.png">		
      </div>
      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"> &times; 닫기</button>
      </div>
    </div>
  </div>
  </div>	
		
 <div class="card mt-2 mb-2">  
	<div class="card-body">  
	  
 <div class="d-flex mt-3 mb-1 justify-content-center">  
    <img class="form-control" src="./title.jpg" >
  </div>	 
        
 <?php 
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

  require_once("../lib/mydb.php");
  $pdo = db_connect();	
	 
if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
		
if($mode=="search" || $mode==""){
	  if($search==""){
				$sql="select * from {$DB}.emeeting order by num desc   " ; 									
			         }
	    if($search=="결재상신 1차결재"){
				$sql="select * from {$DB}.emeeting where approve = '결재상신' or  approve = '1차결재'  order by num desc   " ; 									
				$search = null;
			         }
		 elseif($search!="") {
									  $sql ="select * from {$DB}.emeeting where (emember like '%$search%') or (place like '%$search%')  or (content like '%$search%')  or (method like '%$search%')  or  (approve like '%$search%') ";
									  $sql .=" order by   occur desc    ";									  
							}				
}  
							
try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include "rowDB.php"; 				  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
   
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    					 
 ?>    		  
	<div class="d-flex mb-2 px-5 px-lg-2 mt-2  justify-content-center align-items-center">                
		▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		<input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off" >
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"> <i class="bi bi-search"></i> 검색 </button>			
		<button type="button" class="btn btn-dark btn-sm" id="writeBtn" >  <i class="bi bi-pencil"></i>  신규 </button> &nbsp;&nbsp;&nbsp;				
	</div>			
			
<div class="row d-flex"  >
 <style>
th {
    white-space: nowrap;
}
</style>

<table class="table table-hover" id="myTable">
	<thead class="table-primary" >
	    <tr>
			 <th class="text-center" > 번호    </th>
			 <th class="text-center" > 회의일시   </th>
			 <th class="text-center" > 승인상태   </th>
			 <th class="text-center" > 현장명(품명) </th>   
			 <th class="text-center" > 부적합 현상 및 불량내용   </th>   
			 <th class="text-center" > 개선대책   </th>   
			 <th class="text-center" > 참석자   </th>   
		 </tr>
	</thead>
	<tbody>  
  <?php
	$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			include "rowDB.php";			  
					?>
			<tr onclick="redirectToView('<?=$num?>', '<?=$tablename?>')">  
				  <td class="text-center" >  <?=$start_num?>	</td>       
				  <td class="text-center" >  <?=$occur?>	</td>  
				  <td class="text-center" >  <?=$approve?> </td>			  
				  <td class="text-start" >  <?=$place?> </td>             
				  <td class="text-start" >  <?=$content?> </td>  
				  <td class="text-start" >  <?=$method?> </td>  
				  <td class="text-start" >  <?=$emember?> </td>           
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
</div>
	<!-- Footer-->
<? include "footer.php" ?>  

<!-- Core theme JS-->
</form>

<script src="js/scripts.js"></script>

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var emeetingpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('emeetingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var emeetingpageNumber = dataTable.page.info().page + 1;
        setCookie('emeetingpageNumber', emeetingpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('emeetingpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('emeetingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num, tablename) {
    var page = emeetingpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)    	
    var url = "write_form.php?num=" + num + "&tablename=" + tablename;          
	customPopup(url, '품질분임조', 1100, 900); 		    
}

$(document).ready(function(){
	
	$("#writeBtn").click(function(){ 
		var page = emeetingpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '품질분임조', 1100, 850); 	
	 });	

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});


	$("#adminprocess").click(function(){  
	   $('#search').val('결재상신 1차결재');
	   document.getElementById('board_form').submit();   
	});		

	$("#searchNoinputBtn").click(function(){  
	   $('#search').val('');
	   document.getElementById('board_form').submit();   
	});		
	
});	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('부적합개선 품질분임조'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>