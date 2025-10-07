<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   } 
 ?>
 
 <?php include getDocumentRoot() . '/load_header.php' ?>
 
 <title> 시공소장 시공비 결산자료 </title> 
 </head>

 <?php 


 if(isset($_REQUEST["recordDate"])) 
	 $recordDate=$_REQUEST["recordDate"];
   else
     $recordDate=date("Y-m-d");
 
 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 
  if(isset($_REQUEST["plan_output_check"])) 
	 $plan_output_check=$_REQUEST["plan_output_check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
	if(isset($_POST["plan_output_check"]))   
         $plan_output_check=$_POST["plan_output_check"]; // 미출고 리스트 POST사용  
	 else
		 $plan_output_check='0';
 
 if(isset($_REQUEST["output_check"])) 
	 $output_check=$_REQUEST["output_check"]; // 출고완료
   else
	if(isset($_POST["output_check"]))   
         $output_check=$_POST["output_check"]; // 출고완료
	 else
		 $output_check='0';
	 
 if(isset($_REQUEST["team_check"])) 
	 $team_check=$_REQUEST["team_check"]; // 시공팀미지정
   else
	if(isset($_POST["team_check"]))   
         $team_check=$_POST["team_check"]; // 시공팀미지정
	 else
		 $team_check='0';	 
	 
 if(isset($_REQUEST["measure_check"])) 
	 $measure_check=$_REQUEST["measure_check"]; // 미실측리스트
   else
	if(isset($_POST["measure_check"]))   
         $measure_check=$_POST["measure_check"]; // 미실측리스트
	 else
		 $measure_check='0';		 
  
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
  
// print $output_check;
  
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 $sortof=$_REQUEST["sortof"];  // 클릭해서 넘겨준 값
 $stable=$_REQUEST["stable"];    // 정렬모드 변경할지 안할지 결정  
  
  $sum=array(); 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";        
 
 if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
 $find=$_REQUEST["find"];
 
  
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

// 올해를 날자기간으로 설정
/*
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
 */
 // 당월을 날짜 기간으로 설정
 
 if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,7) ;
	$fromdate=$fromdate . "-01";
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
 
  if(isset($_REQUEST["search"]))   //
 $search=$_REQUEST["search"];

 $orderby=" order by doneday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분		

$sql="select * from mirae8440.work  ";
  
if($mode=="search"){
		  if($search==""){
					 $sql="select * from mirae8440.work where doneday between date('$fromdate') and date('$Transtodate')" . $orderby;  			
			       }
			 elseif($search!="")
			    { 
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )) and ( doneday between date('$fromdate') and date('$Transtodate'))" . $orderby;				  		  		   
			     }    
}

	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

   $counter=0;
   $workday_arr=array();
   $workplacename_arr=array();
   $firstord_arr=array();
   $secondord_arr=array();
   $worker_arr=array();
   $workfeedate_arr=array();
   $material_arr=array();
   $demand_arr=array();
   $visitfee_arr=array();
   $totalfee_arr=array();
   
   $wide_arr=array();
   $normal_arr=array();
   $narrow_arr=array();
   $widefee_arr=array();
   $normalfee_arr=array();
   $narrowfee_arr=array();
   $etc_arr=array();
   $etcfee_arr=array();  
   
   $wideunit_arr=array();
   $normalunit_arr=array();
   $narrowunit_arr=array();   
   $etcunit_arr=array();  
   
   $add_arr=array();   // 추가로 재료분리대/ 쫄대등 들어가는 현장
   
   $num_arr=array();  // 일괄처리를 위한 번호 저장


 try{  
 
   // $sql="select * from mirae8440.work"; 		 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
			  $num=$row["num"];
			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $firstord=$row["firstord"];
			  $firstordman=$row["firstordman"];
			  $firstordmantel=$row["firstordmantel"];
			  $secondord=$row["secondord"];
			  $secondordman=$row["secondordman"];
			  $secondordmantel=$row["secondordmantel"];
			  $chargedman=$row["chargedman"];
			  $chargedmantel=$row["chargedmantel"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $workday=$row["workday"];
			  $doneday=$row["doneday"];  // 시공완료일
			  $workfeedate=$row["workfeedate"];  // 시공비지급일
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  
			  $widejambworkfee=$row["widejambworkfee"];
			  $normaljambworkfee=$row["normaljambworkfee"];
			  $smalljambworkfee=$row["smalljambworkfee"];
			  $workfeedate=$row["workfeedate"];      // 시공비 처리일			  
			  
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  
			  $demand=$row["demand"];  	   
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $hpi=$row["hpi"];	   
			  $delicompany=$row["delicompany"];	   
			  $delipay=$row["delipay"];	   
			  $attachment=$row["attachment"];	   
			  
			  $searchitem = $workplacename . $memo . $attachment;
			  
			  // 재료분리대 등 존재여부 찾기
			  if (strpos($searchitem,"재료") or strpos($searchitem,"끼임") or strpos($searchitem,"쫄대")  or strpos($searchitem,"갭커버")  or strpos($searchitem,"갭카바") )  
				$add_arr[$counter]='유';
			   else
					$add_arr[$counter]='';
			  
	
		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";		      
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";						
		      if($startday!="0000-00-00" and $startday!="1970-01-01" and $startday!="")  $startday = date("Y-m-d", strtotime( $startday) );
					else $startday="";	
		      if($testday!="0000-00-00" and $testday!="1970-01-01" and $testday!="")  $testday = date("Y-m-d", strtotime( $testday) );
					else $testday="";		
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";	
		      if($workfeedate!="0000-00-00" and $workfeedate!="1970-01-01" and $workfeedate!="")  $workfeedate = date("Y-m-d", strtotime( $workfeedate) );
					else $workfeedate="";	
		      if($recordDate!="0000-00-00" and $recordDate!="1970-01-01" and $recordDate!="")  $recordDate = date("Y-m-d", strtotime( $recordDate) );
					else $recordDate="";						
	   
		   $doneday_arr[$counter]=$doneday;
		   $workfeedate_arr[$counter]=$workfeedate;
		   $workplacename_arr[$counter]=$workplacename;
		   $address_arr[$counter]=$address;
		   $secondord_arr[$counter]=$secondord;   
		   $firstord_arr[$counter]=$firstord;   
		   $worker_arr[$counter]=$worker;   
		   $demand_arr[$counter]=$demand;   
		   $num_arr[$counter]=$num;   
		   
		   // 판매'란 단어 있으면 실측비 제외		   
		   $findstr = '판매';
		   $pos = stripos($workplacename, $findstr);			   
		   
		if( trim($secondord) =='우성' or trim($secondord) == '한산' or $pos>0 )
			$visitfee_arr[$counter]= 0;
		    else
				$visitfee_arr[$counter]= 100000 ;				 

			  $materials="";
			  $materials=$material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;		
		   
		   $material_arr[$counter]=$materials;   		   
							   	   
		   $wide_arr[$counter] = 0;
		   $widefee_arr[$counter] = 0;
		   $normal_arr[$counter] = 0;
		   $normalfee_arr[$counter] = 0;
		   $narrow_arr[$counter] = 0;
		   $narrowfee_arr[$counter] = 0;
		   $etc_arr[$counter] = 0;
		   $etcfee_arr[$counter] = 0;
		   
		   $wideunit_arr[$counter] = 0;
		   $normalunit_arr[$counter] = 0;
		   $narrowunit_arr[$counter] = 0;
		   $etcunit_arr[$counter] = 0;		   
   
   				 $workitem="";
				 if($widejamb!="")   {
						$wide_arr[$counter] = (int)$widejamb;
								  
							   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr = '불량';
							   $pos = stripos($workplacename, $findstr);							   
							   //판매란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr2 = '판매';
							   $pos2 = stripos($workplacename, $findstr2);
							   if($pos==0 and $pos2==0)
									 if((int)$widejambworkfee > 0)
								        $wideunit_arr[$counter] = conv_num($widejambworkfee) ;  
									    else if( trim($secondord) =='우성' and (trim(strtoupper($firstord)) =='OTIS' or trim($firstord)=='오티스') )
											     $wideunit_arr[$counter] = 105000;  
												   else
													  $wideunit_arr[$counter] = 80000;  								   								 
							 
									$widefee_arr[$counter]= (int)$widejamb * $wideunit_arr[$counter];  	  							   
								   
									}
				 if($normaljamb!="")   {
						$normal_arr[$counter] = (int)$normaljamb;				
							 
							   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr = '불량';
							   $pos = stripos($workplacename, $findstr);							   
							   //판매란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr2 = '판매';
							   $pos2 = stripos($workplacename, $findstr2);
							   if($pos==0 and $pos2==0)
										if((int)$normaljambworkfee > 0)
											 $normalunit_arr[$counter] = conv_num($normaljambworkfee) ;  								   
											else
												$normalunit_arr[$counter] = 70000 ;								   
							   

								     
						
						$normalfee_arr[$counter]=  (int)$normaljamb * $normalunit_arr[$counter];  						
						}
				 if($smalljamb!="") {
						$narrow_arr[$counter] = (int)$smalljamb;	
						

								 
							   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr = '불량';
							   $pos = stripos($workplacename, $findstr);							   
							   //판매란 단어가 들어가 있는 수량은 제외한다.		   
							   $findstr2 = '판매';
							   $pos2 = stripos($workplacename, $findstr2);
							   if($pos==0 and $pos2==0)
								   	if((int)$smalljambworkfee > 0)
								         $narrowunit_arr[$counter] = conv_num($smalljambworkfee) ; 	
									 else
										$narrowunit_arr[$counter] = 20000 ;	

						
						$narrowfee_arr[$counter]= (int)$smalljamb * $narrowunit_arr[$counter];  												
						}		   	   
	 
		        $totalfee_arr[$counter] = $widefee_arr[$counter] + $normalfee_arr[$counter]+ $narrowfee_arr[$counter] + $etcfee_arr[$counter] ;  
			   $counter++;	
		   } // end of 판매 / 불량		   		   	   
     // }   	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>		 
<body >

<div class="container-fluid">  	
	<div class="card mt-3 mb-5">    
	
	<div class="card-header">    
	 <h3> &nbsp; 시공소장 시공비 결산자료(시공완료일 기준자료임) 	 &nbsp; &nbsp; 	
	 <button  type="button" class="btn btn-secondary" id="refresh"> 새로고침 </button>	 &nbsp;			 
	 <button  type="button" class="btn btn-secondary" id="downloadcsvBtn"> CSV 엑셀 다운로드 </button>	 &nbsp;
	 <button  type="button" class="btn btn-secondary" id="downloadlistBtn" onclick="javascript:move_url('excelform.php?fromdate=<?=$fromdate?>&todate=<?=$todate?>&search=<?=$search?>')"> 소장별 거래명세표(엑셀) 다운로드 </button>	 &nbsp;									 
	 </h3>
	</div> 
   <div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 		 
   <form name="board_form" id="board_form"  method="post" action="popupworkfee.php?mode=search&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
   
   
   <span style="color:blue;"> 시공비 청구일자 일괄처리 </span>
	 <input type="date" id="recordDate" name="recordDate" size="12" value="<?=$recordDate?>" placeholder=""> 선택체크	
	 <button type="button" id="saveBtn"  class="btn btn-secondary"> 일괄적용&저장 </button>	&nbsp;		
	 <button type="button" id="clearBtn"  class="btn btn-outline-danger"> 선택 Clear </button>	&nbsp;		
   
   
	<span style="margin-left:70px;color:grey;"> 작업소장 :  </span>
	    <button type="button" class="btn btn-dark  btn-sm" onclick="List_name('')"> ALL </button> 				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('추영덕')"> 추영덕 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('이만희')"> 이만희 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('김운호')"> 김운호 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('김상훈')"> 김상훈 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('유영')"> 유영 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('손상민')"> 손상민 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('조장우')"> 조장우 </button> 	   	  				
       <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="List_name('박철우')"> 박철우 </button> 	   
		
    <div class="input-group p-2 mb-2">
		<button type="button" id="preyear" class="btn btn-secondary"   onclick='pre_year()' > 전년도 </button>  &nbsp;  	
		<button type="button" id="three_month" class="btn btn-secondary"  onclick='three_month_ago()' > M-3월 </button> &nbsp;  	
		<button type="button" id="prepremonth" class="btn btn-secondary"  onclick='prepre_month()' > 전전월 </button>	&nbsp;  
		<button type="button" id="premonth" class="btn btn-secondary"  onclick='pre_month()' > 전월 </button>  &nbsp; 	
		<button type="button" id="thismonth" class="btn btn-dark"  onclick='this_month()' > 당월 </button>	&nbsp;  	   
		<button type="button" id="thisyear" class="btn btn-dark"  onclick='this_year()' > 당해년도 </button> &nbsp;  			
       <span class='input-group-text align-items-center' style='width:400 px;'>  
       <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp; 부터 &nbsp;  
       <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">  &nbsp;  까지    </span>  &nbsp;
	   <input type="text" name="search" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();"> 
		<button type="button" id="searchBtn" class="btn btn-dark"  > 검색 </button>	
		<span style="margin-left:20px;font-size:20px;color:blue;"> ※실측비는 1,2,3차 등 차수 및 여건에 따라 달라질 수 있음 </span>
       </div>
      
	 <div id="grid" style="width:1880px;">  
	 </div>     
	 
	 </form>
	 </div>   
	 </div>   
   </div> 	   
  
  
  <form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]" >
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
  </form>  
  
<script>

$(document).ready(function(){
	
$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
	
 var arr1 = <?php echo json_encode($doneday_arr);?> ;
 var arr2 = <?php echo json_encode($workfeedate_arr);?> ;  
 var arr3 = <?php echo json_encode($worker_arr);?> ; 
 var arr4 = <?php echo json_encode($workplacename_arr);?> ;
 var arr5 = <?php echo json_encode($material_arr);?> ;
 var arr6 = <?php echo json_encode($firstord_arr);?> ;  
 var arr7 = <?php echo json_encode($secondord_arr);?> ; 
 var arr8 = <?php echo json_encode($demand_arr);?> ;
 var arr9 = <?php echo json_encode($visitfee_arr);?> ;
 var arr10= <?php echo json_encode($wide_arr);?> ;
 var arr11= <?php echo json_encode($wideunit_arr);?> ;
 var arr12= <?php echo json_encode($widefee_arr);?> ;
 var arr13= <?php echo json_encode($normal_arr);?> ;
 var arr14= <?php echo json_encode($normalunit_arr);?> ;
 var arr15= <?php echo json_encode($normalfee_arr);?> ;
 var arr16= <?php echo json_encode($narrow_arr);?> ;
 var arr17= <?php echo json_encode($narrowunit_arr);?> ;
 var arr18= <?php echo json_encode($narrowfee_arr);?> ;
 var arr19 = <?php echo json_encode($etc_arr);?> ;
 var arr20 = <?php echo json_encode($etcfee_arr);?> ;
 var arr21 = <?php echo json_encode($totalfee_arr);?> ;
 var arr22 = <?php echo json_encode($num_arr);?> ;
 var arr23 = <?php echo json_encode($add_arr);?> ;  // 재료분리대 여부 검색해서 알려줌 추가부분
 
var num = <?php echo json_encode($num_arr);?> ;
var numcopy = new Array(); ;
 
 var total_sum=0; 
 var count=0;  // 전체줄수 카운트 
  
 var rowNum = "<? echo $counter; ?>" ; 
 
 const data = [];
 const columns = [];	
 const COL_COUNT = 22;

 for(i=0;i<rowNum;i++) {
			 total_sum = total_sum + Number(uncomma(arr21[i]));
		 row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = arr1[i] ;						 						
				row[`col2`] = arr2[i] ;						 						
				row[`col3`] = arr3[i] ;						 						
				row[`col4`] = arr4[i] ;						 											 						
				row[`col5`] = arr5[i] ;						 											 						
				row[`col6`] = arr6[i] ;						 											 						
				row[`col7`] = arr7[i] ;						 											 						
				row[`col8`] = arr8[i] ;						 											 						
				row[`col9`] =  (arr9[i] == 0) ? "" : comma(arr9[i]);						 						
				row[`col10`] = (arr10[i] == 0) ? "" : comma(arr10[i]);					 						
				row[`col11`] = (arr11[i] == 0) ? "" : comma(arr11[i]);							 						
				row[`col12`] = (arr12[i] == 0) ? "" : comma(arr12[i]);							 						
				row[`col13`] = (arr13[i] == 0) ? "" : comma(arr13[i]);							 						
				row[`col14`] = (arr14[i] == 0) ? "" : comma(arr14[i]);							 						
				row[`col15`] = (arr15[i] == 0) ? "" : comma(arr15[i]);							 						
				row[`col16`] = (arr16[i] == 0) ? "" : comma(arr16[i]);							 						
				row[`col17`] = (arr17[i] == 0) ? "" : comma(arr17[i]);							 						
				row[`col18`] = (arr18[i] == 0) ? "" : comma(arr18[i]);							 						
				row[`col19`] = (arr19[i] == 0) ? "" : comma(arr19[i]);							 						
				row[`col20`] = (arr20[i] == 0) ? "" : comma(arr20[i]);							 						
				row[`col21`] = (arr21[i] == 0) ? "" : comma(arr21[i]);							 						
				row[`col22`] = arr22[i] ;						 											 						
				row[`col23`] = arr23[i] ;						 											 						
						}
				data.push(row); 	 
                numcopy[count] = num[i] ; 			 
			    count++;					
 }
   // 마지막에 한줄 추가해서 합계내역 넣음
	i++;		
	row = { name: i };		 
		 for (let k = 0; k < COL_COUNT; k++ ) {				
				row[`col1`] = '' ;						 						
				row[`col2`] = '' ;						 						
				row[`col3`] = '' ;						 						
				row[`col4`] = '' ;						 						
				row[`col5`] = '' ;
				row[`col6`] = '' ;						 						
				row[`col7`] = '' ;						 						
				row[`col8`] = '' ;
				row[`col9`] = '' ;					 						
				row[`col10`] ='' ;
				row[`col11`] ='' ;
				row[`col12`] ='' ;
				row[`col13`] ='' ;
				row[`col14`] ='' ;
				row[`col15`] ='' ;
				row[`col16`] ='' ;
				row[`col17`] ='' ;
				row[`col18`] ='' ;
				row[`col19`] ='' ;
				row[`col20`] = '합계';
				row[`col21`] = comma(total_sum)  ;	
                row[`col22`] ='' ;				
			}
		 data.push(row); 	
		 numcopy[count] = 0 ;
		 count++;	

class CustomTextEditor {
	  constructor(props) {
		const el = document.createElement('input');
		const { maxLength } = props.columnInfo.editor.options;

		el.type = 'text';
		el.maxLength = maxLength;
		el.value = String(props.value);

		this.el = el;
	  }

	  getElement() {
		return this.el;
	  }

	  getValue() {
		return this.el.value;
	  }

	  mounted() {
		this.el.select();
	  }
	}	

const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 1000,					  					
	  columns: [ 				   
		{
		  header: '시공완료일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,	
		  align: 'center'
		},				   
		{
		  header: '시공비 청구',
		  name: 'col2',
		  sortingType: 'desc',
		  sortable: true,
		  width:90,		
		  align: 'center'
		},			
		{
		  header: '시공소장',
		  name: 'col3',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:60, 		
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col4',
		  width:250,	
		  align: 'left'
		},
		{
		  header: '재질',
		  name: 'col5',
		  width:150,	
		  align: 'left'
		},
		{
		  header: '쫄대,재료분리대',
		  name: 'col23',
		  width:100,	
		  align: 'center'
		},		
		{
		  header: '원청',
		  name: 'col6',
		  width: 80,		
		  align: 'center'
		},
		{
		  header: '발주처',
		  name: 'col7',
		  width: 80,	
		  align: 'center'
		},

		{
		  header: '발주처 청구',
		  name: 'col8',
		  width:80, 		
		  align: 'center'
		},
		{
		  header: '실측비',
		  name: 'col9',
		  width:55, 		
		  align: 'center'
		},
			
		{
		  header: '막판',
		  name: 'col10',
		  width:30, 		
		  align: 'center'
		},		
		{
		  header: '단가',
		  name: 'col11',
		  width:60, 		
		  align: 'right'
		},		
		{
		  header: '금액',
		  name: 'col12',
		  width:70,	
		  align: 'right'
		},			
		{
		  header: '막판무',
		  name: 'col13',
		  width:30,	 		
		  align: 'center'
		},			
		{
		  header: '단가',
		  name: 'col14',
		  width:60,	 		
		  align: 'right'
		},				
		{
		  header: '금액',
		  name: 'col15',
		  width:70,	 		
		  align: 'right'
		},			
		{
		  header: '쪽쟘',
		  name: 'col16',
		  width:30,	 		
		  align: 'center'
		},			
		{
		  header: '단가',
		  name: 'col17',
		  width:50,	 		
		  align: 'right'
		},				
		{
		  header: '금액',
		  name: 'col18',
		  width:70,	 		
		  align: 'right'
		},			
		{
		  header: '기타',
		  name: 'col19',
		  width:30,	 		
		  align: 'center'
		},		
		{
		  header: '금액',
		  name: 'col20',
		  width:70,	 		
		  align: 'right'
		},					
		{
		  header: '청구비합',
		  name: 'col21',
		  width:80,	 		
		  align: 'right'
		},					
		{
		  header: 'N',
		  name: 'col22',
		  width:5,	 		
		  align: 'right'
		}			
	  ],
	columnOptions: {
			resizable: true
		  },
	rowHeaders: ['rowNum','checkbox'],   // checkbox 형성
	  
	});
	
grid.hideColumn('col22');  // 숨기기
	
var Grid = tui.Grid; // or require('tui-grid')
Grid.applyTheme('default', {
			  cell: {
				normal: {
				  background: '#fbfbfb',
				  border: '#e0e0e0',
				  showVerticalBorder: true
				},
				header: {
				  background: '#eee',
				  border: '#ccc',
				  showVerticalBorder: true
				},
				rowHeader: {
				  border: '#ccc',
				  showVerticalBorder: true
				},
				editable: {
				  background: '#fbfbfb'
				},
				selectedHeader: {
				  background: '#d8d8d8'
				},
				focused: {
				  border: '#418ed4'
				},
				disabled: {
				  text: '#b0b0b0'
				}
			  }	
	});	

	$("#saveBtn").click(function(){    	
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col2',$("#recordDate").val())  // ; appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
		  //console.log($("#recordDate").val());
		  //console.log(e);
		});			
	   savegrid();
	});		
	
	$("#clearBtn").click(function(){  		
	    
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		  grid.setValue(e, 'col2','')  
		});	
	   savegrid();
	});	
	

// grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
function savegrid() {		
		let num_arr               =  new Array();  
		let recordDate_arr        = new Array(); 		

		const MAXcount=grid.getRowCount();  				     
		let pushcount=0;
		for(i=0;i<MAXcount;i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.    
				num_arr.push(grid.getValue(i, 'col22'));
				recordDate_arr.push(grid.getValue(i, 'col2'));
			 }	
			$('#num_arr').val(num_arr);				 
			$('#recordDate_arr').val(recordDate_arr);	
        // console.log(num_arr);	
        // console.log(recordDate_arr);	
        // console.log($("#Form1").serialize());	
	    $.ajax({
			url: "SaveDemand.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
   }
   
		
grid.on('dblclick', (e) => {
	
    var link = 'http://8440.co.kr/work/view.php?num=' + numcopy[e.rowKey] ;
   //  window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
	
   //  window.location.replace(link);     // 이전 페이지로 못돌아감
   //  window.open(link);  	
   if(numcopy[e.rowKey]>0)
       window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=100,width=1600,height=900");
	
   console.log(e.rowKey);
});	   
   
$("#refresh").click(function(){  location.reload();   });	          // refresh     

$("#downloadcsvBtn").click(function(){  Do_gridexport();   });	          // CSV파일 클릭	
  
//////////////////// saveCSV	
Do_gridexport = function () { 	
		  //  const data = grid.getData();		
			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.								
			// header 넣기
			   let row = "";			  
			   row += '번호' + ',' ;
			   row += '시공완료일 ,' ;
			   row += '시공비지급 ,' ;
			   row += '시공소장 ,' ;			   
			   row += '현장명 ,' ;
			   row += '원청 ,' ;
			   row += '발주처 ,' ;
			   row += '재질 ,' ;
			   row += '청구여부 ,' ;
			   row += '실측비 ,' ;
			   row += '막판 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '막판무 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '쪽쟘 ,' ;
			   row += '단가 ,' ;
			   row += '금액 ,' ;
			   row += '기타 ,' ;
			   row += '금액 ,' ;			   
			   row += '청구합 ' ;
			  				
			   csvContent += row + "\r\n";
			   console.log(rowNum);
			const COLNUM = 21;   
			for (let i = 0; i <grid.getRowCount(); i++) {
			   let row = "";			  
			   row += (i+1) + ',' ;
			   for(let j=1; j<=COLNUM ; j++) {
				  let tmp = String(grid.getValue(i, 'col'+j));
				  tmp = tmp.replace(/undefined/gi, "") ;
				  tmp = tmp.replace(/#/gi, " ") ;
				  row +=  tmp.replace(/,/gi, "") + ',' ;
			   }

			   csvContent += row + "\r\n";
			}		 		  
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "시공소장 시공비내역.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
			
	
});

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}

function dis_text()
{  
		var dis_text = '<?php echo $jamb_total; ?>';
		$("#dis_text").val(dis_text);
}	

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

function List_name(worker)
{	
		var worker; 				
		var name='<?php echo $user_name; ?>' ;
		 
			$("#search").val(worker);	
			$('#board_form').submit();		// 검색버튼 효과
}

function move_url(href)
{
	 var  search = "<? echo $search;  ?>" ; 
	 if(search!='')
        document.location.href = href;		 
	   else
		  alert('소장을 선택해 주세요');   
	   
}

</script>

  </html>

</body>