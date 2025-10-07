<?php\nrequire_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';   

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          
		 sleep(1);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
 }    
?>
 
<?php include getDocumentRoot() . '/load_header.php' ?>

<title> 전산실장 정산 </title> 
</head>
<body>
 
<?php require_once(includePath('myheader.php')); ?>   
 
<?php 
 $search=$_REQUEST["search"] ?? '';
 $separate_date=$_REQUEST["separate_date"] ?? '';	 
 
 $list=$_REQUEST["list"] ?? 0 ;
  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  
 
 $page = $_REQUEST["page"] ?? 1;  // 페이지 번호
  
  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  $mode=$_REQUEST["mode"] ?? '' ;
 
 $cursort=$_REQUEST["cursort"] ?? '';    // 현재 정렬모드 지정
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"] ?? '';	 
$todate=$_REQUEST["todate"] ?? '';	 

if(empty($fromdate) or empty($todate))
{
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "2021-01-01";
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
$sql="select * from mirae8440.automan where proDate between date('2010-01-01') and date('$todate') order by  proDate " ; 

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
			 $sql="select * from mirae8440.automan " . $a; 					
			 $sqlcon = "select * from mirae8440.automan " . $b;   // 전체 레코드수를 파악하기 위함.					
	}
	else { // 각 필드별로 검색어가 있는지 쿼리주는 부분	                                                      							   
			  $sql ="select * from mirae8440.automan where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and ( (memo like '%$search%') or (writer like '%$search%') ) ";
			  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
			  $sqlcon ="select * from mirae8440.automan where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate'))  and ( (memo like '%$search%') or (writer like '%$search%') ) ";
			  $sqlcon .="  order by " . $SettingDate . " desc, num desc "; 
			  }
	}
if($mode=="") {
				 $sql="select * from mirae8440.automan " . $a; 					
				 $sqlcon = "select * from mirae8440.automan " . $b;   // 전체 레코드수를 파악하기 위함.					
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

<div class="container">  			
	 
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>">  
  
	<input type="hidden" id="page" name="page" value="<?=$page?>"  > 	
		
<div class="row">  			
<div class="d-flex justify-content-center">  			
<div class="card mt-2 mb-1 w-75 justify-content-center">  			
<div class="card-header">
	 <div class="d-flex mb-2 mt-2 fs-5 justify-content-center">  	  
	  <span class="badge bg-dark"> 계약회사 : 신규쟘 자동작도 (주)다완테크 : 1천만원(계약금 0, 미수금 : 1천만원) </span>
	</div> 
	 <div class="d-flex mb-2 mt-2 fs-5 justify-content-center">  	  
	  <span class="badge bg-info"> 계약회사 : 판넬 자동작도 (주)일해이엔지 : 1천5백만원(계약금 5백만원, 미수금 : 1천만원) </span>
	</div> 
    <!--	
	 <div class="d-flex mb-2 mt-2 fs-5 justify-content-center">  	  
	  <span class="badge bg-primary"> 계약회사 : 웹사이트 (주) 대한 : 2천만원(계약금 : 6백만원 , 2차 수금: 7백만원, 미수금 : 7백만원) </span>
	</div>   				
	<div class="d-flex mb-2 mt-2 fs-5 justify-content-center">  
	  <span class="badge bg-secondary"> 계약회사 : 웹사이트 (주)경동기업 : 5천만원 (계약금 : 천만원 , 1,2,3,4차수금 : 4천만원, 미수금 : 1천만원) </span> 
     </div>
	-->
	<div class="d-flex mb-4 mt-4 fs-5 justify-content-center">  
	   <?=$resultText?> 
	</div>   
</div>   		
    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">   
	    ▷ 총 <?= $total_row ?> 개  &nbsp;&nbsp;&nbsp;&nbsp;	
    </div>
    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  

	   <span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
			<div id="showframe" class="card">
				<div class="card-header " style="padding:2px;">
					기간 검색
				</div>
				<div class="card-body">
					<button type="button" id="preyear" class="btn btn-primary btn-sm "   onclick='pre_year()' > 전년도 </button>  
					<button type="button" id="three_month" class="btn btn-dark btn-sm "  onclick='three_month_ago()' > M-3월 </button>
					<button type="button" id="prepremonth" class="btn btn-dark btn-sm "  onclick='prepre_month()' > 전전월 </button>	
					<button type="button" id="premonth" class="btn btn-dark btn-sm "  onclick='pre_month()' > 전월 </button> 						
					<button type="button" class="btn btn-danger btn-sm "  onclick='this_today()' > 오늘 </button>
					<button type="button" id="thismonth" class="btn btn-dark btn-sm "  onclick='this_month()' > 당월 </button>
					<button type="button" id="thisyear" class="btn btn-dark btn-sm "  onclick='this_year()' > 당해년도 </button> 
				</div>
			</div>

		   <input type="date" id="fromdate" name="fromdate" size="12"  class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
		   <input type="date" id="todate" name="todate" size="12"   class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
		   &nbsp;&nbsp;
		
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control" style="width:150px;" > &nbsp;			
				<button class="btnClear"></button>
			</div>	
			&nbsp;
			<button type="button" id="searchBtn" class="btn btn-dark  btn-sm "  > <i class="bi bi-search"></i> 검색  </button>	&nbsp;&nbsp;					
		<?php
		   if(isset($_SESSION["userid"]) && ( $user_name==='김보곤'  ||  $user_name==='소현철' ) )
		   {
		  ?>
				<button type="button" id="writebtn" class="btn btn-dark  btn-sm" > <i class="bi bi-pencil"></i>  신규 </button>  &nbsp;						
		  <?php
			}
		  ?> 
		  
		</div>
	</div>   
</div>
<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  
<div class="card">  			
<div class="card-body justify-content-center align-items-center">  		
  <div class="row"> 	
	<div class="col-sm-12 mb-1 mt-1 justify-content-center align-items-center">     	   
      <table class="table table-bordered table-hover ">
	     <thead class="table-dark">
		   <tr>
				<th class=" text-center">번호</th>
				<th class=" text-center">내역발생일</th>            
				<th class=" text-center">수입/지출</th>
				<th class=" text-center">작성자</th>
				<th class=" text-center">금액</th>
				<th class=" text-center">내역</th>
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
						   
					$url = "view.php?num={$num}&page={$page}&find={$find}&search={$search}&process={$process}&asprocess={$asprocess}&yearcheckbox={$yearcheckbox}&year={$year}&fromdate={$fromdate}&todate={$todate}";						   

				?>		   
				<tr onclick="location.href='<?= $url; ?>';" style="cursor: pointer;">
				<td class=" text-center"><?=$start_num?></td>
				<td class=" text-center"><?=$proDate?></td>
				<?php
				if($tmp_word=='수입') {
					echo '<td class="text-primary text-center">' . $tmp_word . '</td>';
				} else {
					echo '<td class="text-danger text-center">' . $tmp_word . '</td>';
				}
				?>
				<td class="text-center"><?=$writer?></td>
				<td class="text-end"><?=$amount?></td>
				<td class="text-start"><?=$memo?></td>
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
</div>   
</div>   

</form>	 

<br>
<br>
<div class="container-fluid">
<? include '../footer_sub.php'; ?>
</div>
	 
<script>

$(document).ready(function(){	
	
  $('a').children().css('textDecoration','none');  // a tag 전체 밑줄없앰.	
  $('a').parent().css('textDecoration','none');
		
	$("#writebtn").click(function() { 
		location.href="write_form.php";
	});
    	
	$("#saveBtn").click(function() { 
		document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  
	});
		
});

function blinker() {
	$('.blinking').fadeOut(700);
	$('.blinking').fadeIn(700);
}
setInterval(blinker, 1500);

 
function process_list(){   // 접수일 출고일 라디오버튼 클릭시

document.getElementById('search').value=null; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 


function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
	tmp = Number(str.replace(/[^\d]+/g, ''));
    return tmp; 
}

</script>

<?php
if($mode==""&&$fromdate==null)  
{
  echo ("<script language=javascript> this_year();</script>");  // 당해년도 화면에 초기세팅하기
}

?>

<script> 

function check_alert()
{	
										
}

// 5초마다 알람상황을 체크합니다.
	var timer;
	timer=setInterval(function(){
		check_alert();
	},3000); 


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


	
function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }	
}	

</script>

<script>
	$(document).ready(function(){
		saveLogData('전산실장 정산'); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 
</body>
  </html>