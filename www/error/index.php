<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php')); ?> 
   
<?php include getDocumentRoot() . '/load_header.php' ?>
		
<title> 부적합 품질경영 </title> <div id="cookiedisplay"> </div>
	    
</head>			

<?php include getDocumentRoot() . '/myheader.php'; ?>  
<?php   
if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
		  $_SESSION["url"]=$_SESSION["WebSite"] . 'error/index.php?user_name=' . $user_name; 	
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }      
  
if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='최장중' || $user_name=='이경묵' || $user_name=='조경임')
  $admin = 1;

$tablename = 'error';

require_once("../lib/mydb.php");
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
 
 
// 결재권자 결재정보 보기 			
if($admin==1)
{	
	$sql="select * from {$DB}.{$tablename} where approve<>'처리완료' " ; 					
	$approvalwait = 0 ;

	try{  
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				  include "_row.php";
				  $approvalwait += 1;
				}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
}
 
// 서버의 정보를 읽어와 랜덤으로 메인화면 꾸미기
 				
$sql="select * from {$DB}.error order by num desc " ; 					

$numarr = array();

try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
              include "_row.php";
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
// 랜던하게 유튜브 주소자료 추출 

$youtube_arr = array();

array_push($youtube_arr, "https://www.youtube.com/embed/VPwhUEc84pg");
array_push($youtube_arr, "https://www.youtube.com/embed/NcFf9JhcHDQ");
array_push($youtube_arr, "https://www.youtube.com/embed/aXB5XNmG-TE");
array_push($youtube_arr, "https://www.youtube.com/embed/5ulG8-brBng");

$youtubeURL = $youtube_arr[rand(0,count($youtube_arr)-1)];  
    	
?>


<form name="board_form" id="board_form"  method="post"  >  
<div class="container">

 <div class="card">			
	  <div class="card-body">   
            <div class="row">
				<div class="col-sm-12">
				 <div class="card">			
					<div class="card-body">   				
						<div class="d-flex justify-content-center mt-2 mb-2">
							<!-- 품질불량 관리기법-->
							<div id="Materialshow" >
							<h4 class="text-center"> 품질불량 관리기법 
							<img src="../img/click.gif" width="5%" height="5%">
							</h4>
							</div>											
					
							&nbsp;	
							<button  id="adminprocess" class="btn btn-outline-dark" type="button" >
							   <i class="bi bi-credit-card-2-back"></i>
								결재중
								<span  class="badge bg-dark text-white ms-1 rounded-pill"><?=$approvalwait?> </span>
							</button> 
							&nbsp;						
											
						
							</div>                    	
						   <div class="d-flex justify-content-center mt-2 mb-2">
							<div id="Material" style="display:none;" >
								<section class="page-section" >
									<div class="container">
									<!--
										<div class="text-center">
											<h2 class="section-heading text-uppercase">최고급 스테인리스 304 사용</h2>
											<h3 class="section-subheading text-muted">SUS/STS 304 Stainless 제품</h3>
										</div>
										-->
										
										<div class="row text-center">
										 <?php include '8d.php'; ?>
										</div>
										<div class="row text-left">
										 <?php include 'fmea.php'; ?>
										<img src="../img/qm1.jpg">
										<img src="../img/qm2.jpg">
										<img src="../img/qm3.jpg">
										<img src="../img/qm4.jpg">
										<img src="../img/qm5.jpg">
										<img src="../img/qm6.jpg">										 
										</div>
									</div>
								</section>		
							</div>	
							</div>	
							<div class="d-flex px-1 px-lg-1 mt-1 justify-content-center">
								<h5 class="mb-1 text-secondary"> 지속적 관심/분석/개선이 불량감소에 큰 도움이 됩니다. </h5>			
							</div>												
							 <div class="d-flex  mt-3 justify-content-center">				
								  <div class="typing-txt"> 						
									  승인상태 : 결재상신 -> 1차결재 -> 처리완료 						
								  </div> 
									<p class="typing"></p> 	 					  					
							</div>					        
				</div>	
				</div>	
				</div>					
			</div>			
			</div>	
			</div>	
			</div>	
<div class="container-fluid">
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
		$sql="select * from {$DB}.error order by num desc " ; 									
			 }
	if($search=="결재상신 1차결재"){
		$sql="select * from {$DB}.error where approve = '결재상신' or  approve = '1차결재'  order by num desc  " ; 									
		$search = null;
			 }
	elseif($search!="") {
							  $sql ="select * from {$DB}.error where (reporter like '%$search%') or (place like '%$search%')  or (content like '%$search%')  or (method like '%$search%')  or (involved like '%$search%')  or (approve like '%$search%') ";
							  $sql .=" order by   occur desc  ";									  
					}				
}  
					
try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			include "_row.php"; 				  
		}		 
	 } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
	   
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    						 
 ?> 
 
	<input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 								

	<div class="d-flex mb-2 px-5 px-lg-2 mt-2  justify-content-center align-items-center">                
		▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		<input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off" >
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"> <i class="bi bi-search"></i> 검색 </button>			
		<button type="button" class="btn btn-dark btn-sm" id="writeBtn" >  <i class="bi bi-pencil"></i>  신규 </button> &nbsp;&nbsp;&nbsp;				
	</div>	
	
	<div class="d-flex mt-3 mb-1 justify-content-center align-items-center">  
	  <table class="table table-hover" id="myTable">
		<thead class="table-primary">
		  <tr class="middle-align">
			<th class="text-center align-middle w30px"> 번호</th>
			<th class="text-center align-middle w80px"> 확인일</th>
			<th class="text-center align-middle w80px"> 승인상태</th>
			<th class="text-center align-middle w80px"> 불량유형</th>
			<th class="text-center align-middle w200px"> 현장명(품명)</th>
			<th class="text-center align-middle w50px"> 보고자</th>
			<th class="text-center align-middle w300px"> 발생원인(분석)</th>
			<th class="text-center align-middle w300px"> 처리방안(개선사항)</th>
			<th class="text-center align-middle"> 관련 직원</th>
			<th class="text-center align-middle"> 자재비</th>
			<th class="text-center align-middle"> 운송비</th>
			<th class="text-center align-middle"> 시공비</th>
			<th class="text-center align-middle"> 기타비용</th>
			<th class="text-center align-middle"> 비용합계</th>
		  </tr>
		</thead>
		<tbody>				
	 <?php
	$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번	    
	while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		  include "_row.php";	
	  ?>
	<tr style="cursor: pointer;" onclick="popupCenter('write_form.php?num=<?=$num?>', '부적합 보고서', 1000, 920); return false;">
		<td class="text-center"><?=$start_num?></td>
		<td class="text-center"><?=$occurconfirm?></td>
		<td class="text-center"><?=$approve?></td>
		<td class="text-center"><?=$errortype?></td>
		<td class=""><?=$place?></td>
		<td class="text-center"><?=$reporter?></td>
		<td class=""><?=$content?></td>
		<td class=""><?=$method?></td>
		<td class="text-center"><?=$involved?></td>
		<td class="text-end"><?= is_numeric($materialFee) ? number_format($materialFee) : '' ?></td>
		<td class="text-end"><?= is_numeric($deliveryFee) ? number_format($deliveryFee) : '' ?></td>
		<td class="text-end"><?= is_numeric($workFee) ? number_format($workFee) : '' ?></td>
		<td class="text-end"><?= is_numeric($etcFee) ? number_format($etcFee) : '' ?></td>
		<td class="text-end"><?= is_numeric($totalFee) ? number_format($totalFee) : '' ?></td>
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
		
<!-- Footer-->
<? include "footer.php" ?>  		
</div>
 
</form>

<form name="settingsFrm" id="settingsFrm"  method="post" action="settings.php">  	
</form>	
    <!-- Core theme JS-->
	<script src="js/scripts.js"></script>
</body>
</html>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var errorpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('errorpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var errorpageNumber = dataTable.page.info().page + 1;
        setCookie('errorpageNumber', errorpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('errorpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('errorpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = errorpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?num=" + num + "&tablename=" + tablename;          

	customPopup(url, '개발관련 Notice & 자료실', 1200, 900); 		    
}

$(document).ready(function(){	
	$("#writeBtn").click(function(){ 
		var page = errorpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '개발관련 Notice & 자료실', 1300, 850); 	
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
	saveLogData('부적합 보고'); // 다른 페이지에 맞는 menuName을 전달
});

</script> 
</body>
</html>


