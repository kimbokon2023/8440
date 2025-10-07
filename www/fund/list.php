<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}    
 ?>

 <title> 공동자금 </title> 
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
  
 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
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
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
$sum_title=array(); 
$input_sum = 0;
$output_sum = 0;
 
 // 수입, 지출 처리하는 부분  
$sql="select * from ".$DB.".fund where proDate between date('2010-01-01') and date('$todate') order by  proDate " ; 
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

$resultText = "총수입(" . number_format($input_sum) . "원) -  총지출(" . number_format($output_sum) . "원) = 보유 잔액(" . number_format($remain_sum) ."원)" ;
 
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from ".$DB.".fund " . $a; 					
	                         $sqlcon = "select * from ".$DB.".fund " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
	             else { // 각 필드별로 검색어가 있는지 쿼리주는 부분	                                                      							   
							  $sql ="select * from ".$DB.".fund where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and ( (memo like '%$search%') or (writer like '%$search%') ) ";
							  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from ".$DB.".fund where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate'))  and ( (memo like '%$search%') or (writer like '%$search%') ) ";
							  $sqlcon .="  order by " . $SettingDate . " desc, num desc "; 
						      }
               }
if($mode=="") {
							 $sql="select * from ".$DB.".fund " . $a; 					
	                         $sqlcon = "select * from ".$DB.".fund " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
				
$nowday=date("Y-m-d");   // 현재일자 변수지정    		   

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
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}     
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
   //   print "$page&nbsp;$total_page&nbsp;$current_page&nbsp;$search&nbsp;$mode";
		
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

<div class="container">  			

		<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	

<div class="card mt-2 mb-1">  			
<div class="card-header">  			
	<div class="d-flex mb-4 mt-4 fs-6 justify-content-center align-items-center">  
	   <?=$resultText?> 
		<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  		 
	</div>   

    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  			
		▷ <?= $total_row ?> &nbsp;&nbsp;
		<!-- 기간설정 칸 -->
		 <?php include $_SERVER['DOCUMENT_ROOT'] . '/setdate.php' ?>		
		<?php
		   if(isset($_SESSION["userid"]) &&  $user_name==='조경임' ||  $user_name==='김보곤'  ||  $user_name==='소민지'  )
		   {
		  ?>
            &nbsp;&nbsp;
			<button type="button" id="writeBtn" class="btn btn-dark btn-sm" ><i class="bi bi-pencil"></i>  신규 </button>
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
				<th class=" text-center">작성일</th>            
				<th class=" text-center">수입</th>
				<th class=" text-center">지출</th>				
				<th class=" text-center">금액</th>
				<th class=" text-center">내역</th>
				<th class=" text-center">작성자</th>
			</tr>
          </thead>
		  <tbody>
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $proDate=$row["proDate"];			  			  
 			  $writer=$row["writer"];			  			  
 			  $amount=$row["amount"];			  			  
 			  $memo=$row["memo"];
			  $which=$row["which"];				  	

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
				
				<?php
				if($tmp_word=='수입') {
					echo '<td class="text-primary text-center">' . $tmp_word . '</td>';
				} else {
					echo '<td class="text-danger text-center"> </td>';
				}
				?>
				
				<?php
				if($tmp_word!=='수입') {
					echo '<td class="text-danger text-center">' . $tmp_word . '</td>';
				} else {
					echo '<td class="text-primary text-center"> </td>';					
				}
				?>
				
				<td class="text-end"><?=$amount?></td>
				<td class="text-start"><?=$memo?></td>
				<td class="text-center"><?=$writer?></td>
			</tr>
			    
		<?php
			$start_num--;  
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
   </tbody>
   </table>
</div>
 <div class="col-sm-1 mb-1 mt-1 justify-content-center align-items-center">  </div>   	   
  </div>   	   
 
<div class="row row-cols-auto mt-3 mb-5 justify-content-center align-items-center"> 
 <?php
 
 	if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-outline-secondary  btn-sm" type="button" id=previousListBtn  onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  ' . $i . '  </span>'; 
              else 
                   print '<button class="btn btn-outline-secondary btn-sm" type="button" id=moveListBtn onclick="javascript:movetoPage(' . $i . ')"> ' . $i . '</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-outline-secondary  btn-sm" type="button" id=nextListBtn onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
            }
            ?>         
</div>

     </div>   
     </div>   
     </div>  
 
	</form>	 
	
<div class="container-fluid">
	<? include '../footer_sub.php'; ?>
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
    var url = "view.php?num=" + num ;          
	customPopup(url, '공동자금', 600, 500); 		    
}

$(document).ready(function(){	
	
	$("#writeBtn").click(function(){ 		
			var url = "write_form.php";				        
			popupCenter(url, '공동자금', 600, 500); 	
	 });	
});

function movetoPage(page){ 	  
	  $("#page").val(page); 
      // var echo="<?php echo $partOpt; ?>"; 
      // var searchOpt="<?php echo $searchOpt; ?>"; 
      // var search="<?php echo $search; ?>"; 

     // $("#partOpt").val(partOpt);
     // $("#searchOpt").val(searchOpt);
     // $("#search").val(search);
	 $("#board_form").submit();  
	}		

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('공동자금 조회'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>