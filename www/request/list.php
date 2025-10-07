<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

 if(!isset($_SESSION["level"]) || $level>5) {
	     $_SESSION["url"]='https://8440.co.kr/request/list.php' ; 		   
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");         
         exit;
   }   

include $_SERVER['DOCUMENT_ROOT'] . "/common.php";
 
$page = $_REQUEST["page"] ?? '';
$scale = $_REQUEST["scale"] ?? '';
?> 
 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ; ?>  

<title> 원자재 구매&입출고 </title> 

<style>
#showextract {
	display: inline-block;
	position: relative;
}
		
#showextractframe {
    display: none;
    position: absolute;
    width: 800px;
    z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */
    top: 110px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
#autocomplete-list {
	border: 1px solid #d4d4d4;
	border-bottom: none;
	border-top: none;
	position: absolute;
	top: 87%;
	left: 65%;
	right: 30%;
	width : 10%;
	z-index: 99;
}
.autocomplete-item {
	padding: 10px;
	cursor: pointer;
	background-color: #fff;
	border-bottom: 1px solid #d4d4d4;
}
.autocomplete-item:hover {
	background-color: #e9e9e9;
}

th {
    white-space: nowrap;
}
</style> 
</head>
<body>		 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; ?>    
<?php 
include "_request.php"; 	  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

$Bigsearch = isset($_REQUEST["Bigsearch"]) ? $_REQUEST["Bigsearch"] : '';
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';

// 철판종류에 대한 추출부분
$sql="select * from ".$DB.".steelsource order by sortorder asc, item desc ";

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $item_counter=0;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();
   $steelsource_item_yes=array();
   $steelsource_spec_yes=array();
   $spec_arr=array();
   $last_item="";
   $last_spec="";
   $pass='0';
   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=trim($row["item"]);
 			  $steelsource_spec[$counter]=trim($row["spec"]);
			  $company=trim($row["take"]);			    
			  
			  if($row["take"]=='미래기업') $company='';	// 일반매입처리
			  if($row["take"]=='윤스틸') $company='';		// 일반매입처리	  
			  if($row["take"]=='현진스텐') $company='';		// 일반매입처리	  
		      $steelsource_take[$counter]= $company ;  
			  
		    if($steelsource_item[$counter]!=$last_item)
			{
				$last_item= $steelsource_item[$counter];
				$steelsource_item_yes[$item_counter]=$last_item;
				$item_counter++;
			}			 
	    $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

array_push($steelsource_item_yes," ");
$steelsource_item_yes = array_unique($steelsource_item_yes);
sort($steelsource_item_yes);

// var_dump("fromdate " . $fromdate);
// var_dump($Transtodate);   

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 6개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
		  
$find=$_REQUEST["find"] ?? ''; 
// var_dump($Transtodate);
$sql="select * from ".$DB.".steelsource"; 					
 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();   
   
  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	$counter++;	   
		$steelsource_num[$counter]=$row["num"];			  
		$steelsource_item[$counter]=$row["item"];
		$steelsource_spec[$counter]=$row["spec"];
		$steelsource_take[$counter]=$row["take"];   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

if($separate_date=="1") $SettingDate="registdate ";
    else
  	   $SettingDate="indate ";

// print $SettingDate;	 
// 완료일 기준
$SettingDate="outdate ";

$Andmywrite  = "";

if($mywrite ==='1')
{	
	$Andmywrite  = " And first_writer like '%$user_name%' ";	
}

$Andis_deleted =  " AND is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;
$Whereis_deleted =  " Where is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;	 
	 
if($done_check_val==='1')	 
	$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') and (which = '1' or which = '2') " . $Andis_deleted  . " order by " ;
  else
	$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') " . $Andis_deleted  . "  order by " ;

$a= $common . " num desc ";    //내림차순
  
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum=array();
$num_arr = array();
$item_arr = array();
$supplier_arr = array();
$company_arr = array();

$sql="select * from ".$DB.".eworks " . $a; 	
 
$recount = 0 ;

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

		include '_row.php';

		$tmp=$item . $spec;

		$num_arr[$recount] = $num;
		$item_arr[$recount] = $item;
		$supplier_arr[$recount] = $supplier;
		$company_arr[$recount] = $company;

		$recount++;
	
        for($i=1;$i<=$rowNum;$i++) {
	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
			// $sum[$i]=(float)-1;				
		    }
       }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

// print $sql;

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
                  
if(trim($Bigsearch)==='' && $find==="전체")
		{		
				 $sql="select * from ".$DB.".eworks " . $a ; 														 
		}
		else {	 
		
				 $sql="select * from ".$DB.".eworks " . $a ; 														 
		
			  if($find=='전체') {
				  $sql ="select * from ".$DB.".eworks where (steel_item like '%$Bigsearch%') " . $Andis_deleted . "  order by  num desc ";									  
							}
		
			  if($find=='입고') {
				  $sql ="select * from ".$DB.".eworks where (steel_item like '%$Bigsearch%')  and (which = '3' ) " . $Andis_deleted . " order by num desc ";
						}
			  if($find=='출고') {
				  $sql ="select * from ".$DB.".eworks where (steel_item like '%$Bigsearch%')  and ( which = '2' ) " . $Andis_deleted . " order by num desc ";
						}										
						
						
				}
							
if($mode==="search" && $search!=="" && $find!=="전체") { // 각 필드별로 검색어가 있는지 쿼리주는 부분	
	 if(trim($Bigsearch)==='')   // Bigsearch가 없는 경우
		  {
			 if($find==='입고') {
				 
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and (which = '3' ) and  ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%'))  ";
				  $sql .=" " . $Andis_deleted . " order by num desc ";
				  
					}
				 if($find==='출고') { // 출고인 경우								  								
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and (which = '2' ) and  ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) ";
				  $sql .=" " . $Andis_deleted . " order by  num desc ";

					}
				 if($find==='공급처') { // 공급처 경우								  								
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate'))  and (supplier like '%$search%') " ;   
				  $sql .=" " . $Andis_deleted . " order by num desc ";
					}
		   }
			  else {   // bigsearch 있는 경우
						 if($find==='입고') {
								// 철판종류도 지정하고 검색어도 있는 경우
							  $sql ="select * from ".$DB.".eworks where  (steel_item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
							  $sql .="or (  steel_item like '%$search%')  or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) and (which = '3' ) " . $Andis_deleted . "  order by num desc ";
								}
							else { // 출고인 경우								  
								// 철판종류도 지정하고 검색어도 있는 경우
								  $sql ="select * from ".$DB.".eworks where  (steel_item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
								  $sql .="or (  steel_item like '%$search%') or  (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) and (which = '2' ) " . $Andis_deleted . "  order by num desc ";												  
								}
					   }			
		}						   
 if($search!=="" && $find==="전체") { // 각 필드별로 검색어가 있는지 쿼리주는 부분	
		// 필드 선택없고 눌렀을때 Bigsearch -> steel_item값이 있을 경우 검색
		if(trim($Bigsearch)!=='')						
		{ 
			  // 철판종류도 지정하고 검색어도 있는 경우
			  $sql ="select * from ".$DB.".eworks where  (steel_item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' )  or (replace(supplier,' ','') like '%$search%' ) ";
			  $sql .="or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%')) " . $Andis_deleted . "  order by num desc ";							  
		  }  
		  else
			  {							  						
				  $sql ="select * from ".$DB.".eworks where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate') ) and ( (outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' )  or (replace(supplier,' ','') like '%$search%' ) " ;
				  $sql .="or (  steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (request_comment like '%$search%') ) " . $Andis_deleted . "  order by num desc ";
			  }
		}
               

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;

// var_dump($done_check_val);
// print $fromdate;
// print $todate;
// print $sortof;		
// print $cursort;		

// print $sql;		

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
		 
			  $date_font="text-dark";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="text-success";
						}
						
				$font="text-gray";
			
			switch ($regist_state) {
				case   "1"     :  $regist_word="등록"; break;
				case   "2"     :  $regist_word="접수"; break;	
				case   "3"     :  $regist_word="완료"; break;	
				default:  $regist_word="등록"; break;
			}								
							  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}	
			
?>

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search">  

	<input type="hidden" id="done_check_val" name="done_check_val" value="<?=$done_check_val?>" >
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>"  > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>"  > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"  > 		
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>"  > 	
	<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>"  > 	
	<input type="hidden" id="year" name="year" value="<?=$year?>"  > 	
	<input type="hidden" id="check" name="check" value="<?=$check?>"  > 	
	<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>"  > 	
	<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>"  > 	
	<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>"  > 	
	<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>"  > 	
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>"  > 	
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>"  > 	
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>"  > 	
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 			
	<input type="hidden" id="mywrite" name="mywrite" value="<?=$mywrite?>" > 
	<input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>"  > 				

<div class="container-fluid justify-content-center align-items-center">
<div class="card mt-2 "> 
<div class="card-body">
    <div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
		<h5> 원자재 구매 & 입출고</h5>  
		<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  
	</div>	
	<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  		
		<!-- Replace the checkbox code with the Bootstrap-styled button -->					
			<?php
				print '▷ ' .  $total_row . '&nbsp;&nbsp; ' ;					
					if($done_check_val==='0')   
						   print '<button class="btn btn-dark  btn-sm  checktask " type="button"> <i class="bi bi-search"></i> 입고 제외 </button>  &nbsp;&nbsp;';
						else
							print '<button class="btn btn-outline-dark  btn-sm  checktask " type="button"   >  <i class="bi bi-search"></i> 입고 포함 </button>  &nbsp;&nbsp;';								  

					
			?>		   
				<!-- 기간부터 검색까지 연결 묶음 start -->
				<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 

				<select name="dateRange" id="dateRange" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
					<?php
					$dateRangeArray = array('최근3개월','최근6개월', '최근1년', '최근2년','직접설정','전체');
					$savedDateRange = $_COOKIE['dateRange'] ?? ''; // 쿠키에서 dateRange 값 읽기

					foreach ($dateRangeArray as $range) {
						$selected = ($savedDateRange == $range) ? 'selected' : '';
						echo "<option $selected value='$range'>$range</option>";
					}
					?>
				</select>			
				
				<div id="showframe" class="card">
					<div class="card-header " style="padding:2px;">
						<div class="d-flex justify-content-center align-items-center">  
							기간 설정
						</div>
					</div>
					<div class="card-body">
						<div class="d-flex justify-content-center align-items-center">  	
							<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
							<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
							<button type="button" id="three_month" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='three_month_ago()' > M-3월 </button>
							<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='prepre_month()' > 전전월 </button>	
							<button type="button" id="premonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='pre_month()' > 전월 </button> 						
							<button type="button" class="btn btn-outline-danger btn-sm me-1  change_dateRange"  onclick='this_today()' > 오늘 </button>
							<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_month()' > 당월 </button>
							<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_year()' > 당해년도 </button> 
						</div>
					</div>
				</div>		

			   <input type="date" id="fromdate" name="fromdate" class="form-control"   style="width:100px;" value="<?=$fromdate?>" >  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate"  class="form-control"   style="width:100px;" value="<?=$todate?>" >  &nbsp;     </span> 
			   &nbsp;&nbsp;
			   <button type="button" id="mywriteBtn" class="btn btn-dark  btn-sm" > 내글 </button>	
				<select id="find" name="find" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
				   <?php	

				$findarr=array('전체','입고','출고','공급처');

				   for($i=0;$i<count($findarr);$i++) {
						 if($find==$findarr[$i]) 
									print "<option selected value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
							 else   
					   print "<option value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
				   } 		   
		      	?>				   
				</select>
		   <select name="Bigsearch" id="Bigsearch" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
				<?php
				for($i=0;$i<count($steelsource_item_yes);$i++) {
					if($Bigsearch==$steelsource_item_yes[$i])
						print "<option selected value='" . $steelsource_item_yes[$i] . "'> " . $steelsource_item_yes[$i] .   "</option>";
					else
						print "<option value='" . $steelsource_item_yes[$i] . "'> " . $steelsource_item_yes[$i] .   "</option>";
				}
				?>
			</select>		   			
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control" style="width:150px;" > &nbsp;			
				<button class="btnClear"></button>
			</div>				
				<button type="button" id="searchBtn" class="btn btn-dark  btn-sm mx-2 "> <i class="bi bi-search"></i> 검색  </button>	&nbsp;&nbsp;
			<div id="autocomplete-list">
			</div>
					<span id="showextract" class="btn btn-primary btn-sm" > <i class="bi bi-tools"></i>  </span>	
					<div id="showextractframe" class="card">
						<div class="card-header text-center " style="padding:2px;">
							자주사용하는 사이즈
						</div>					
							<div class="card-body">
									<div class="p-1 m-1" >
									 <button type="button" class="btn btn-primary btn-sm" onclick="HL304_list_click();" > 304 HL </button>	&nbsp;   
									 <button type="button" class="btn btn-success btn-sm" onclick="MR304_list_click();" > 304 MR </button>	&nbsp;    			 
									 <button type="button" class="btn btn-secondary btn-sm" onclick="VB_list_click();" > VB </button>	&nbsp;    
									 <button type="button" class="btn btn-warning btn-sm" onclick="EGI_list_click();" > EGI </button>	&nbsp;    
									 <button type="button" class="btn btn-danger btn-sm" onclick="PO_list_click();" > PO </button>	&nbsp;    
									 <button type="button" class="btn btn-dark btn-sm" onclick="CR_list_click();" > CR </button>	&nbsp;  
									 <button type="button" class="btn btn-success btn-sm" onclick="MR201_list_click();" > 201 2B MR </button>	&nbsp;  
								     </div>	
									  <div class="p-1 m-1" >
									  <span class="text-success "> <strong> 쟘 1.2T &nbsp; </strong> </span>	
										<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_1950_list_click();"> 1000x1950  </button> &nbsp;
										<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_2150_list_click();"> 1000x2150  </button> &nbsp;				   
										<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size42150_list_click();">  4'X2150 </button> &nbsp;
										<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size1000_8_list_click();"> 1000x8' </button> &nbsp; 
									  </div>	
									  <div class="p-1 m-1" >
									 &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <button type="button"   class="btn btn-outline-success btn-sm"  onclick="size4_8_list_click();"> 4'x8' </button> &nbsp;
									  <button type="button"  class="btn btn-outline-success btn-sm"  onclick="size1000_2700_list_click();"> 1000x2700 </button> &nbsp;
									   <button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_2700_list_click();"> 4'x2700 </button> &nbsp;
									   <button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_3200_list_click();"> 4'x3200  </button> &nbsp;
									   <button type="button" class="btn btn-outline-success btn-sm"   onclick="size4_4000_list_click();"> 4'x4000 </button> &nbsp;	   			  
									  </div>			  
									  <div class="p-1 m-1" >
									  <span class="text-success "> <strong> 신규쟘 1.5T(HL) &nbsp; </strong> </span>	
									   <button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_2150_list_click();"> 4'x2150 </button> &nbsp;				
									   <button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_8_list_click();"> 4'x8' </button> &nbsp;								  
									  <span class="text-success "> <strong> 신규쟘 2.0T(EGI) &nbsp; </strong> </span>	
									   <button type="button" class="btn btn-outline-success btn-sm" onclick="size20_4_8_list_click();"> 4'x8'  </button> &nbsp;
									  </div>			
									<div class=" p-1 m-1" >	   
									   천장 1.2T(CR)  </button> &nbsp; 
									  <button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1680_list_click();"> 4'x1680 </button> &nbsp;
									  <button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1950_list_click();"> 4'x1950 </button> &nbsp;
									  <button type="button"  class="btn btn-outline-danger btn-sm"  onclick="size12_4_8_list_click();"> 4'x8' </button> &nbsp;
									  </div>			  
								    <div class=" p-1 m-1" >			  				   
									  천장 1.6T(CR)   &nbsp; 	  
									  <button type="button"  class="btn btn-outline-primary btn-sm" onclick="size16_4_1680_list_click();"> 4'x1680 </button> &nbsp;
									  <button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_1950_list_click();"> 4'x1950 </button> &nbsp;
									  <button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_8_list_click();"> 4'x8' </button> &nbsp;		   		   
								    </div>
								    <div class=" p-1 m-1" >	
									   천장 2.3T(PO)  &nbsp; 	  
									   <button type="button" class="btn btn-outline-secondary btn-sm" onclick="size23_4_1680_list_click();"> 4'x1680 </button> &nbsp;
									   <button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_1950_list_click();"> 4'x1950 </button> &nbsp;
									   <button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_8_list_click();"> 4'x8'  </button> &nbsp;					  
									   천장 3.2T(PO)  &nbsp; 	  
									   <button type="button" class="btn btn-outline-secondary btn-sm" onclick="size32_4_1680_list_click();"> 4'x1680 </button> &nbsp;									   
								    </div>
								   
							</div>					
					</div>			
				
			   
			<?php
		   if(isset($_SESSION["userid"]))
		   {
		  ?>			
		    <button type="button" class="btn btn-dark  btn-sm mx-2" id="writeBtn"> <i class="bi bi-pencil"></i>  신규  </button> 			     
		    <!-- <button type="button" class="btn btn-dark  btn-sm" onclick="window.open('extract_db.php','자재미입고 추출','left=100,top=100, scrollbars=yes, toolbars=no,width=1500,height=800');">  자재 미입고 </button>		&nbsp;		   			 -->
		    <button type="button" class="btn btn-dark  btn-sm" id="showCost" >  단가 추적 </button>		&nbsp;		   			
		  <?php
		   }
		  ?>
			 
      </div>
      </div>
      </div>
	  
   <div class="row d-flex justify-content-center align-items-center"> 			  
		 <table class="table table-hover table-border w-100" id="myTable">
		   <thead class="table-primary">
		   <tr>
            <th class=" text-center" style="width:4%;" >번호</th>
            <th class=" text-center" style="width:6%;" > 접수 </th>    
            <th class=" text-center" style="width:5%;"> 납기</th>   
            <th class=" text-center" style="width:5%;"> 완료 </th>     <!-- 완료일 -->
            <th class=" text-center" style="width:5%;"> 요청인 </th>     
            <th class=" text-center" style="width:5%;"> 진행상태 </th>     
            <th class=" text-center" style="width:5%;"> 이관 </th>     
            <th class=" text-center" style="width:5%;"> 결재 </th>     
            <th class=" text-center" style="width:10%;" > 현 장 명 </th>     
            <th class=" text-center" style="width:4%;" >  모 델 명 </th>     
            <th class=" text-primary text-center" style="width:9%;"> 철판종류 </th>    
            <th class=" text-center"> 규격    </th>   
            <th class=" text-danger text-center" >  수량 </th>   
            <th class=" text-center"> 사급여부 </th> 
            <th class=" text-center"  style="width:6%;"> 공급처 </th>  
            <th class=" text-center"  > 공급가액 </th>  
            <th class=" text-center" style="width:15%;"> 비고 </th>  
		  </tr>
		</thead>
	  <tbody>    

 <?php	 
	  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			   
                 include '_row.php' ;

			 $requestdate = NullCheckDate($requestdate);
			 $indate = NullCheckDate($indate);
			 $outdate = NullCheckDate($outdate);		
	 if($outdate!="") {
		$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
		$outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
	}

?>		  
	  
 <tr  onclick="redirectToView('<?=$num?>', '<?=$find?>', '<?=$search?>', '<?=$Bigsearch?>', '<?=$yearcheckbox?>', '<?=$year?>', '<?=$fromdate?>', '<?=$todate?>', '<?=$separate_date?>', '<?=$scale?>','<?=$mywrite?>')">
            <td class="   text-center">   <?=$start_num?>				</td>
            <td class="  <?=$date_font?>  text-center"  data-order="<?= $outdate ?>">	  <?=iconv_substr($outdate,0,15, "utf-8")?> 	</td>
            <td class="  <?=$date_font?>  text-center"  data-order="<?= $requestdate ?>"> <?=iconv_substr($requestdate,5,9, "utf-8")?> 	</td>
		
            <td class="  text-center" data-order="<?= $indate ?>"> 	   <?=iconv_substr($indate,5,9, "utf-8")?>  	</td>
			   <?php
							
					 if($which=='') $which='1';
					 switch ($which) {
						case   "1"             :      
								$tmp_word="요청";
								$font_state="text-primary"; break;			      
						case   "2"             :
							   $tmp_word="발주보냄";
							   $font_state="text-danger";	 break;
						case   "3"             :
							   $tmp_word="입고완료";
							   $font_state="text-secondary";	 break;			
						default: break;
					}							
				?>
          <td class="text-center">
		   <?php
				$pattern = "/^[가-힣]+/";
				
				preg_match($pattern, $first_writer, $matches);
				$tmpStr = $matches[0]; // '이미래' 출력
		    ?>			
          <?= $tmpStr ?> </td>			
			
				
            <td class="  <?=$font_state?> text-center ">	 <?=$tmp_word?> 			</td>					
			<td class=" text-center">	 <?=$inventory?> &nbsp;			</td>
				<?php
				   switch($status) {
					   
					   case 'send':
						  $statusstr = '상신';
						  break;
					   case 'ing':
						  $statusstr = '진행';
						  break;
					   case 'end':
						  $statusstr = '완료';
						  break;
					   default:
						  $statusstr = '';
						  break;
				   }	
				?>				
			
			<td class=" text-center">	 <?=$statusstr?> &nbsp;			</td>
            <td class="" >	 <?=$outworkplace?> </td>            
            <td class="  text-center">	 <?=iconv_substr($model,0,8,"utf-8")?> 			</td>
            <td class="  color-blue text-center">	<?=$steel_item?>			</td>
            <td class="  color-brown text-center">	 <?=$spec?>			</td>
            <td class="  color-red text-center">	<?=$steelnum?>		</td>
            <td class="  color-green text-center">	<?=$company?> 		</td>						            
            <td class="  text-center">	 <?=$supplier?>		</td>						
            <td class="  text-center">	
			<?php 
			$number = (int)str_replace(',', '', $suppliercost);  // Convert the string to an integer after removing commas
             
			if($number>0)
				 print '<span class="badge bg-success" >' . $suppliercost . ' </span>';
               else
				   print $suppliercost ;
				?>
			 </td>						
            <td class="">	<?=$request_comment?>			</td>            
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

</form>	

<div class="container-fluid">
<? include '../footer_sub.php'; ?>
</div>
  
     </body>
  </html>
  
<script>

var dataTable; // DataTables 인스턴스 전역 변수
var requestpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('requestpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var requestpageNumber = dataTable.page.info().page + 1;
        setCookie('requestpageNumber', requestpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('requestpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('requestpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function saveSearch() {
    let searchInput = document.getElementById('search');
    let searchValue = searchInput.value;

    // console.log('searchValue ' + searchValue);

    if (searchValue === "") {              
		// 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

		// 폼 제출
		document.getElementById('board_form').submit();
    } else {
        let now = new Date();
        let timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        let searches = getSearches();
        // 기존에 동일한 검색어가 있는 경우 제거
        searches = searches.filter(search => search.keyword !== searchValue);
        // 새로운 검색 정보 추가
        searches.unshift({ keyword: searchValue, time: timestamp });
        searches = searches.slice(0, 50);

        document.cookie = "searches=" + JSON.stringify(searches) + "; max-age=31536000";
        // 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장
		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();		
        document.getElementById('board_form').submit();
    }
}

// 검색창에 쿠키를 이용해서 저장하고 화면에 보여주는 코드 묶음
$(document).ready(function() {
    const searchInput = document.getElementById('search');
    const autocompleteList = document.getElementById('autocomplete-list');  

    searchInput.addEventListener('input', function() {
	const val = this.value;
	let searches = getSearches();
	let matches = searches.filter(s => {
		if (typeof s.keyword === 'string') {
			return s.keyword.toLowerCase().includes(val.toLowerCase());
		}
		return false;
	});				
		renderAutocomplete(matches);               
    });
	     
    searchInput.addEventListener('focus', function() {
        let searches = getSearches();
        renderAutocomplete(searches);   

       // console.log(searches);				
    });
			
});

    var isMouseOverSearch = false;
    var isMouseOverAutocomplete = false;

    document.getElementById('search').addEventListener('focus', function() {
        isMouseOverSearch = true;
        showAutocomplete();
    });

	document.getElementById('search').addEventListener('blur', function() {        
		setTimeout(function() {
			if (!isMouseOverAutocomplete) {
				hideAutocomplete();
			}
		}, 100); // Delay of 100 milliseconds
	});

    function hideAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'none';
    }

    function showAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'block';
    }

function renderAutocomplete(matches) {
    const autocompleteList = document.getElementById('autocomplete-list');    

    // Remove all .autocomplete-item elements
    const items = autocompleteList.getElementsByClassName('autocomplete-item');
    while(items.length > 0){
        items[0].parentNode.removeChild(items[0]);
    }

    matches.forEach(function(match) {
			let div = document.createElement('div') ;
			div.className = 'autocomplete-item' ;
			div.innerHTML =  '<span class="text-primary">' + match.keyword + ' </span>';
			div.addEventListener('click', function() {
			document.getElementById('search').value = match.keyword;
			autocompleteList.innerHTML = '';
						
			// console.log(match.keyword);
			document.getElementById('board_form').submit();    
        });
        autocompleteList.appendChild(div);
    });
	
}

function getSearches() {
	let cookies = document.cookie.split('; ');
	// console.log('cookies ' + cookies);	
	for(let cookie of cookies) {
		if(cookie.startsWith('searches=')) {
			return JSON.parse(cookie.substring(9));
		}
	}
	return [];
}


function redirectToView(num, find, search, Bigsearch, yearcheckbox, year, fromdate, todate, separate_date, scale, mywrite) {
    var page = requestpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?menu=no&num=" + num         
        + "&find=" + find 
        + "&search=" + search 
        + "&Bigsearch=" + Bigsearch 
        + "&yearcheckbox=" + yearcheckbox 
        + "&year=" + year 
        + "&fromdate=" + fromdate 
        + "&todate=" + todate 
        + "&separate_date=" + separate_date 
        + "&scale=" + scale
        + "&mywrite=" + mywrite;       

	customPopup(url, '원자재 구매', 1400, 800); 		    
}


function SearchEnter()
{

    if(event.keyCode === 13){
	  saveSearch(); 
    }
}


$(document).ready(function() { 

		$(".checktask").click(function() {	  
		  // 체크박스가 선택되어 있으면 페이지 리로드
		  $("#page").val('1');	  
		  $("#search").val('');
		  
		  var check = $("#done_check_val").val();		 
		  
		  if(check === '1')
			$("#done_check_val").val('0');		 
			else
				$("#done_check_val").val('1');		 
			
		  $("#board_form").submit();
	  });
	  

	$("#writeBtn").click(function(){ 
		var page = requestpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
			
		var url = "write_form.php"; 

		customPopup(url, '원자재 구매 등록', 1400, 800); 	
	 });		
		 		 
	$("#showCost").click(function(){ 
		var url = "../cost/list.php?menu=no" ; 

		customPopup(url, '단가추이',1800,800);
	 });		
		 

	$("#searchBtn").click(function(){ 		 
		 saveSearch(); 
	 });		
		 
	$("#mywriteBtn").click(function(){ 			  
		 $("#mywrite").val('1');  // 내글
		 document.getElementById('board_form').submit();    
	 
	 });	 


});


$(document).ready(function() {

    // 쿠키에서 dateRange 값을 읽어와 셀렉트 박스에 반영
    var savedDateRange = getCookie('dateRange');
    if (savedDateRange) {
        $('#dateRange').val(savedDateRange);
    }

    // dateRange 셀렉트 박스 변경 이벤트 처리
    $('#dateRange').on('change', function() {
        var selectedRange = $(this).val();
        var currentDate = new Date(); // 현재 날짜
        var fromDate, toDate;

        switch(selectedRange) {
            case '최근3개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 3));
                break;
            case '최근6개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 6));
                break;
            case '최근1년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;
            case '최근2년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 2));
                break;
            case '직접설정':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;   
            case '전체':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 20));
                break;            
            default:
                // 기본 값 또는 예외 처리
                break;
        }

        // 날짜 형식을 YYYY-MM-DD로 변환
        toDate = formatDate(new Date()); // 오늘 날짜
        fromDate = formatDate(fromDate); // 계산된 시작 날짜

        // input 필드 값 설정
        $('#fromdate').val(fromDate);
        $('#todate').val(toDate);
		
		var selectedDateRange = $(this).val();
       // 쿠키에 저장된 값과 현재 선택된 값이 다른 경우에만 페이지 새로고침
        if (savedDateRange !== selectedDateRange) {
            setCookie('dateRange', selectedDateRange, 30); // 쿠키에 dateRange 저장
			document.getElementById('board_form').submit();      
        }		
		
		
    });
});

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

</script>

<script>
	$(document).ready(function(){
		saveLogData('원자재 구매'); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 