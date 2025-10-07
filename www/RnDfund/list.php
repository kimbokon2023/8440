<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session_header.php'); 

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}   

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
  
// 첫 화면 표시 문구
$title_message = '연구전담부서 운영비';  
$tablename ='RnDfund';
 ?>

 <title> <?=$title_message ?> </title> 
 </head>
 <body>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

 <?php
if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
 $search=$_REQUEST["search"];
if(isset($_REQUEST["separate_date"]))   //출고일 접수일
 $separate_date=$_REQUEST["separate_date"];	 
 
if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
 $list=$_REQUEST["list"];
else
	  $list=0;
  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	
  
 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 
  if($separate_date=="") $separate_date="1";
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "2021-01-01";
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
		  
$process = "전체";  // 기본 전체로 정한다.   
	
$SettingDate = "proDate";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc ";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
$sum_title=array(); 
$input_sum = 0;
$output_sum = 0;
 
 // 수입, 지출 처리하는 부분  
$sql="select * from ".$DB."." . $tablename . " where proDate between date('2010-01-01') and date('$todate') order by  proDate " ; 
try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			  $num=$row["num"];
 			  $proDate=$row["proDate"];			  			  
 			  $writer=$row["writer"];			  			  
 			  $amount=$row["amount"];			  			  
 			  $memo=$row["memo"];
			  $which=$row["which"];
			  
		if($which=='1') 	  
	          $input_sum += (int)conv_num($amount);
		     else
      		    $output_sum += (int)conv_num($amount);

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
$remain_sum = $input_sum - $output_sum ;

$resultText = "총지출(" . number_format($output_sum) . "원) ";
 
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from ".$DB."." . $tablename . " " . $a; 						                         
			       }
	             else { // 각 필드별로 검색어가 있는지 쿼리주는 부분	                                                      							   
							  $sql ="select * from ".$DB.".fund where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and ( (memo like '%$search%') or (writer like '%$search%') ) ";
							  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  
						      }
               }
if($mode=="") {
							 $sql="select * from ".$DB."." . $tablename . " " . $a; 						                         
                }		
				
$nowday=date("Y-m-d");   // 현재일자 변수지정    		   

	 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
               include '_row.php';
			   
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}     
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();


		if($regist_state==null)
			 $regist_state="1";
		 
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$proDate) {
                            $date_font="red";
						}
												
								$font="black";							
												
							  
 if($proDate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $proDate = $proDate . $week[ date('w',  strtotime($proDate)  ) ] ;
}	
			 
			?>
		 
	 
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
		<input type="hidden" id="page" name="page" value="<?=$page?>" >
		<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >

<div class="container">  			

<div class="card mt-2 mb-1">  			
<div class="card-header">  			
	<div class="d-flex mb-4 mt-4 fs-6 justify-content-center">  
	   <?=$title_message?> 
	</div>    			
	<div class="d-flex mb-4 mt-4 fs-6 justify-content-center">  
	   <?=$resultText?> 
	</div>   

    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  			
		▷ <?= $total_row ?> &nbsp;&nbsp;
		<!-- 기간설정 칸 -->
		 <?php include $_SERVER['DOCUMENT_ROOT'] . '/setdate.php' ?>		
		<?php
		   if(isset($_SESSION["userid"]) &&  ( $user_name==='소현철' ||  $user_name==='소민지' ||  $user_name==='김보곤')   )
		   {
		  ?>
            &nbsp;&nbsp;
			<button type="button" id="writeBtn" class="btn btn-dark btn-sm" > <i class="bi bi-pencil"></i>  신규 </button>
		  <?php
			}
		  ?> 
		  
		</div>
	</div>   
</div>
	
<div class="card">  			
<div class="card-body justify-content-center align-items-center">  		
  <div class="row"> 
	<div class="col-sm-1 mb-1 mt-1 justify-content-center align-items-center">  </div>   	   
	<div class="col-sm-10 mb-1 mt-1 justify-content-center align-items-center">     	   
      <table class="table table-hover " id="myTable">
	     <thead class="table-primary">
		   <tr>
				<th class=" text-center">번호</th>
				<th class=" text-center">일자</th>            				
				<th class=" text-center">작성자</th>								
				<th class=" text-center">품목</th>
				<th class=" text-center">내역</th>
				<th class=" text-center">금액</th>
				<th class=" text-center">비고</th>
			</tr>
          </thead>
		  <tbody>
	 <?php
		  
		$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번		  
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

                  include '_row.php';		  	

				 if($proDate!="") {
				$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
				$proDate = $proDate . $week[ date('w',  strtotime($proDate)  ) ] ;
			          }
											
					 if($which=='1')
						   {
						   $tmp_word="수입";
						   $font_state="black";
						   }
						   else
						   {
							   $tmp_word="지출";
							   $font_state="red";				   
						   }			  					   					
		?>		   			
			<tr onclick="redirectToView('<?=$num?>')">
				<td class=" text-center"><?=$start_num?></td>
				<td class=" text-center"><?=$proDate?></td>
				<td class="text-center"><?=$writer?></td>
				<td class="text-start"><?=$item?></td>
				<td class="text-start"><?=$memo?></td>
				<td class="text-end"><?=$amount?></td>				
				<td class="text-end"><?=$comment?></td>						
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
 <div class="col-sm-1 mb-1 mt-1 justify-content-center align-items-center">  </div>   	   
  </div>   	   
 

     </div>   
     </div>   
     </div>  
 
	</form>	 
	
<div class="container-fluid">
	<? require_once($_SERVER['DOCUMENT_ROOT'] . '/footer_sub.php'); ?>
</div>
	 
<script> 

var dataTable; // DataTables 인스턴스 전역 변수
var fundpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('fundpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var fundpageNumber = dataTable.page.info().page + 1;
        setCookie('fundpageNumber', fundpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('fundpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('fundpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num) {    	
    var url = "view.php?tablename=" + $("#tablename").val() + "&num=" + num ;          
	customPopup(url, '<?=$title_message ?>', 600, 500); 		    
}

$(document).ready(function(){		
	$("#writeBtn").click(function(){ 		
			var url = "write_form.php?tablename=" + $("#tablename").val();				        
			popupCenter(url, '<?=$title_message ?>', 600, 500); 	
	 });	
});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('연구전담부서 운영비'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>
