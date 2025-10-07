<?php include getDocumentRoot() . '/session.php';   

// 첫 화면 표시 문구
$title_message = 'JAMB 수주'; 
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
		  header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
}   
include getDocumentRoot() . '/load_header.php';   
 ?>
 
<title> <?=$title_message?> </title>
<link rel="stylesheet" href="<?$root_dir?>/work/css/style.css">

<?php require_once(includePath('myheader.php')); ?>   

<?php include getDocumentRoot() . '/work_voc/load_workvoc.php'; ?>   

<?php 
include "request.php"; 	 
$Twosearchword = array();	 	 
if(strpos($search,',') !== false){	 
  $Twosearchword = explode(',',$search);	   
}
     
// 철판종류에 대한 추출부분  
$sql="select * from ".$DB.".steelsource order by sortorder asc, item desc "; 	
   
try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    $counter = 0;
    $item_counter = 0;
    $steelsource_num = [];
    $steelsource_item = [];
    $steelsource_spec = [];
    $steelsource_take = [];
    $steelsource_item_yes = [];
    $last_item = "";
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $steelsource_num[$counter] = $row["num"];
        $steelsource_item[$counter] = trim($row["item"]);
        $steelsource_spec[$counter] = trim($row["spec"]);
        $company = trim($row["take"]);
        
        if (in_array($row["take"], ['미래기업', '윤스틸', '현진스텐'])) {
            $company = '';
        }
        
        $steelsource_take[$counter] = $company;
        
        if ($steelsource_item[$counter] != $last_item) {
            $last_item = $steelsource_item[$counter];
            $steelsource_item_yes[$item_counter++] = $last_item;
        }
        
        $counter++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

array_push($steelsource_item_yes, " ");
$steelsource_item_yes = array_unique($steelsource_item_yes);
sort($steelsource_item_yes);

$sum = [];

$claddingornew = $_REQUEST["claddingornew"] ?? "전체";
$company1 = $_REQUEST["company1"] ?? null;
$company2 = $_REQUEST["company2"] ?? null;
$workersel = $_REQUEST["workersel"] ?? null;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$now = date("Y-m-d");

$attached = '';
$whereattached = '';
$orderby = "order by orderday desc ";

switch ($check) {
    case '0':
        // '0'에 대한 처리가 필요하면 여기에 추가
        break;
    case '1':
        $attached = " and (worker='') ";
        $whereattached = " where worker='' ";
        break;
    case '2':
        $attached = " and (measureday='') ";
        $whereattached = " where measureday='' ";
        break;
    case '3':
        $attached = " and (((workday='') or (workday='0000-00-00')) and checkhold IS NULL) ";
        $whereattached = " where (((workday='') or (workday='0000-00-00')) and checkhold IS NULL ";
        break;
    case '4':
        $attached = " and (date(endworkday)>=date(now())) ";
        $whereattached = " where date(endworkday)>=date(now()) ";
        $orderby = "order by endworkday asc ";
        break;
    case '5':
        $attached = " and ((workday!='') and (workday!='0000-00-00')) ";
        $whereattached = " where workday!='' ";
        break;
    case '6':
        $attached = " and (outsourcing!='') ";
        $whereattached = " where outsourcing!='' ";
        break;
    case '7':
        $attached = " and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00'))) ";
        $whereattached = " where workday!='' and demand='' ";
        $orderby = "order by workday desc ";
        break;
    case '8':
        $attached = " and ((checkmat1!='') or (checkmat2!='') or (checkmat3!='')) ";
        $whereattached = " where ((checkmat1!='') or (checkmat2!='') or (checkmat3!='')) ";
        $orderby = "order by num desc ";
        break;
    // 기타 케이스에 대한 처리를 추가할 수 있습니다.
}
   
	
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : null;
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : null;

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-6 months", strtotime($currentDate))); 
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
				
// 완료일 기준
$SettingDate=" orderday ";

$Andis_deleted =  " AND is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;
$Whereis_deleted =  " Where is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;	 
	 
$common= $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
		
$andPhrase= " and " . $common  . $orderby ;
$wherePhrase= " where " . $common  . $orderby ;

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);

if($search=="" && $claddingornew==="전체")
	  {
	               if($whereattached!=='')
						$sql="select * from ".$DB.".work " . $whereattached . $andPhrase; 	
					else
						$sql="select * from ".$DB.".work " . $wherePhrase ;					                 			 
			 
	   }
elseif($search!="" && $find!="all" && $claddingornew==="전체")
	{
				$sql="select * from ".$DB.".work where ($find like '%$search%') " . $attached . $andPhrase;				
	 }     				 
elseif($claddingornew!=="전체") 
	 {
	// 공사유형이 전체가 아닐때
	// 검색어가 2개일경우
	if($claddingornew=='덧방')
	   $searchstr = '';
	else
	   $searchstr = '신규';

	if(count($Twosearchword)>1)
	{				   				  
	  $search1 = $Twosearchword[0];
	  $sql ="select * from ".$DB.".work where ( (replace(workplacename,' ','') like '%$search1%' )  or (address like '%$search1%' ) or (firstordman like '%$search1%' )   or  (firstordmantel like '%$search1%' )  or (secondordman like '%$search1%' )   or (secondordmantel like '%$search1%' )  or (chargedman like '%$search1%' )  or (chargedmantel like '%$search1%' )  or (designer like '%$search1%' )  ";
	  $sql .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  and ( checkstep='$searchstr' ) " ;
	  
	  $search2 = $Twosearchword[1];
	  $sql .= " and ( (replace(workplacename,' ','') like '%$search2%' )  or (address like '%$search2%' ) or (firstordman like '%$search2%' )  or (firstordmantel like '%$search2%' )   or (secondordman like '%$search2%' )  or (secondordmantel like '%$search2%' )   or (chargedman like '%$search2%' ) or (chargedmantel like '%$search2%' )  or (designer like '%$search2%' )  ";
	  $sql .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))   and ( checkstep='$searchstr' ) " . $attached . $andPhrase;
	}   // end of Twosearchword searching
		else {   // end of one word
				  $sql ="select * from ".$DB.".work where ((replace(workplacename,' ','')  like '%$search%' )  or (address like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )   or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' ) or (chargedmantel like '%$search%' ) or (designer like '%$search%' ) ";
				  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstordman like '%$search%' )  or (firstordmantel like '%$search%' )  or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  )   and ( checkstep='$searchstr' ) "  . $attached . $andPhrase;						  
			  

		}   // end of Twosearchword searching
}	  	

elseif($search!="" && $find=="all") 
{ // 필드별 검색하기
		   // 검색어가 2개일경우
			if(count($Twosearchword)>1)
			   {		
					  $search1 = $Twosearchword[0];
					  $sql ="select * from ".$DB.".work where ( (replace(workplacename,' ','') like '%$search1%' ) or (address like '%$search1%' )  or (firstordman like '%$search1%' )   or  (firstordmantel like '%$search1%' )  or (secondordman like '%$search1%' )  or (secondordmantel like '%$search1%' )  or (chargedman like '%$search1%' ) ";
					  $sql .="or (delicompany like '%$search1%') or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ) or (designer like '%$search1%' ) )  " ;
					  $search2 = $Twosearchword[1];
					  $sql .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (address like '%$search1%' ) or (firstordman like '%$search2%' ) or (firstordmantel like '%$search2%' )   or (secondordman like '%$search2%' )  or (secondordmantel like '%$search2%' )   or (chargedman like '%$search2%' ) or (chargedmantel like '%$search2%' )";
					  $sql .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' )  or (designer like '%$search2%' )  )  " . $attached . $andPhrase;
					  
				}   // end of Twosearchword searching
			  else {   // end of one word
						  $sql ="select * from ".$DB.".work where ((replace(workplacename,' ','')  like '%$search%' ) or (address like '%$search%' ) or (firstordmantel like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )  or (chargedmantel like '%$search%' )  or (designer like '%$search%' )  " ;
						  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $andPhrase ;					
	}   // end of Twosearchword searching
}				
			  

$current_condition = $check; 
 

switch ($check) {  
    case '3':
        $sql= " select * from mirae8440.work where (workday='0000-00-00' or workday='' or workday IS NULL ) order by orderday desc, num desc ";        
        break;
}
 
 // print 'check : ' . $check;
//  print $sql;
 
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    					 
?>	 
			 
<form id="board_form" name="board_form" method="post" action="list.php?mode=search">  
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>"   > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>"   > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"   > 	
	<input type="hidden" id="page" name="page" value="<?=$page?>"   > 	
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>"   > 			
	<input type="hidden" id="check" name="check" value="<?=$check?>"   > 	
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>"   > 	
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>"   > 	
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>"   > 	
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
	<input type="hidden" id="list" name="list" value="<?=$list?>" > 								
	<input type="hidden" id="buttonval" name="buttonval" value="<?=$buttonval?>" > 								
	<input type="hidden" id="claddingornew" name="claddingornew" value="<?=$claddingornew?>" > 								

<div class="container-fluid">  		
	<div class="row">  
	<div class="card mb-2 mt-2">  
	<div class="card-body">  	
	<div class="d-flex justify-content-center align-items-center my-1">
		<div class="w-100" style="max-width: 1000px;">
			<div class="shadow-lg rounded-4 d-flex align-items-center px-3 py-1" style="background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); min-height:70px; border: 2px solid #0369a1;">
				<i class="bi bi-megaphone-fill text-white me-3" style="font-size:2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
				<div class="flex-grow-1">
					<div class="text-white fw-bold mb-1" style="font-size:1.2rem; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
						한산에 도장을 맡길때는 <span class="badge bg-white text-cyan px-3 py-2 mx-2" style="font-size:1.1rem; font-weight:700; box-shadow: 0 2px 4px rgba(0,0,0,0.2); color: #0369a1 !important;">도장홀</span>을 각 4개씩 타공해야 도장이 가능합니다. <span class="text-warning" style="font-weight:700;">주의하세요!</span>
					</div>
				</div>
				<img src="../img/notice-ceiling.svg" alt="Notice" class="ms-3 d-none d-md-block" style="height:40px; filter: brightness(0) invert(1);">
			</div>
		</div>
	</div>

	<div class="d-flex  p-1 m-1 mt-1 mb-2 justify-content-center align-items-center "> 
		<a href="list.php">   <h5>  <?=$title_message?> <span class="text-danger"> (<?=$claddingornew?>)</span> </h5> </a>	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<?php
		switch ($check) {  
			case '3':
            print '<span class="badge bg-primary fs-5 me-5" > 전체 미출고 List </span>';
        break;
			}
		
		?>
	 		   
			   <span id="showalign" class="btn btn-dark btn-sm " > <ion-icon name="grid-outline"></ion-icon> 정렬 </span>	
				<div id="showalignframe" class="card">
					<div class="card-header text-center " style="padding:2px;">
							화면정렬
					</div>					
						<div class="card-body">		
							<?php							
								function printCheckbox($id, $value, $label, $checkedValue) {
									$isChecked = ($value == $checkedValue) ? "checked" : "";                                    
									echo "<input type='checkbox' class='search-condition' $isChecked id=$id value='$value'>&nbsp; <span class='badge bg-dark' style='font-size:13px;'> $label </span>  &nbsp;&nbsp;";
								}                                                     
									printCheckbox('all', '0', '전체', $current_condition);                                
									printCheckbox('team', '1', '시공팀미지정', $current_condition);                                
									printCheckbox('notmeasure', '2', '미실측', $current_condition);
									printCheckbox('without', '3', '미출고', $current_condition);
									printCheckbox('plan_outputlist', '4', '생산예정', $current_condition);
									printCheckbox('outputlist', '5', '출고완료', $current_condition);
									printCheckbox('outsourcingCheckbox', '6', '외주가공', $current_condition);
									printCheckbox('outputBtn', '7', '출고완료 미청구', $current_condition);	
									printCheckbox('22', '8', '사급', $current_condition);	
						?>									
		
						</div>
					</div>				
				&nbsp; 
			   <span id="showextract" class="btn btn-dark btn-sm " > <i class="bi bi-card-list"></i> 부가기능 </span>	&nbsp; 
				<div id="showextractframe" class="card">
					<div class="card-header text-center " style="padding:2px;">
						부가기능
					</div>					
						<div class="card-body">
							<button type="button" class="btn btn-dark  btn-sm" onclick="popupCenter('test_list.php','검사일기준 추출',1750,800);">  검사일 </button>  							
							<button type="button" class="btn btn-dark btn-sm" onclick="popupCenter('delivery_fee.php','배송비',1900,850);">  <i class="bi bi-truck"></i> 배송비 </button> 
							<?
							if($user_name=='김보곤' or $user_name=='이미래'  or $user_name=='소현철'    or  $user_name=='이경묵'   or  $user_name=='조경임'  or  $user_name=='이소정')
							{
							?>	 
							<button type="button" class="btn btn-dark  btn-sm" onclick="popupCenter('batch.php','시공소장 결산자료',1910,900);"><i class="bi bi-kanban"></i>  일괄처리 </button>&nbsp;
							<?   }     ?> 
							<button type="button" class="btn btn-dark  btn-sm" onclick="popupCenter('registration.php','우성(OTIS)발주 일괄등록',1890,1060);">  우성(OTIS) </button> 
							<button type="button" class="btn btn-dark  btn-sm" onclick="popupCenter('registration_tke.php','TKE 발주 일괄등록',1890,1060);">  TKE </button> 							
							<button type="button" class="btn btn-danger  btn-sm" onclick="popupCenter('No_demandlist.php','출고완료분 중 미청구',1800,850);"> 출고완료 미청구(새창) </button> 			 
							<button type="button" id="choiceworkerBtn" class="btn btn-primary btn-sm text-center" > 소장 </button>		
							<button type="button" id="outsourcingBtn" class="btn btn-success btn-sm" >  외주단가 </button>			 
						</div>
					</div>		
							<button type="button" class="btn btn-dark btn-sm me-5" onclick="popupCenter('plan_making.php','생산예정',1820,900);">  <i class="bi bi-calendar-check"></i> 생산예정 </button>    			
		            <!-- 결재안되는 곳 표시 
					<span class="badge bg-danger fs-6"> ※ 한진엘리베이터 결재시까지 출고 안됨 </span>		-->			
					
			</div>  		  
	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center"> 
	<ion-icon name="play"></ion-icon>  <?= $total_row ?>     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	

			<!-- 기간부터 검색까지 연결 묶음 start -->
				<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
				
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

			   <input type="date" id="fromdate" name="fromdate"   class="form-control"   style="width:100px;" value="<?=$fromdate?>" >  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate"  class="form-control"   style="width:100px;" value="<?=$todate?>" >  &nbsp;     </span> 
			   &nbsp;&nbsp;		
				<select name="find" id="find" class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
				   <?php
					  $options = array(
						 'all' => '전체',
						 'workplacename' => '현장명',
						 'firstord' => '원청',
						 'secondord' => '발주처',
						 'worker' => '미래시공팀',
						 'designer' => '설계자'
					  );

					  foreach ($options as $value => $label) {
						 $selected = ($find == $value) ? 'selected' : '';
						 echo "<option value='$value' $selected>$label</option>";
					  }
				   ?>
				</select>
			&nbsp;
			<?php
				?>
			<div class="inputWrap me-1">
				<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control" style="width:150px;" > &nbsp;			
				<button class="btnClear"></button>
			</div>				
			<button id="searchBtn" type="button" class="btn btn-dark  btn-sm mx-2"  ><i class="bi bi-search"></i> 검색 </button> &nbsp;&nbsp;				
			<div id="autocomplete-list">				
			</div>		
					<span id="showsearchtool" class="btn btn-primary btn-sm me-2" > <i class="bi bi-tools"></i> </span>	&nbsp; 
					<div id="showsearchtoolframe" class="card" style="width:650px;">
						<div class="card-header text-center " style="padding:2px;">
							검색
						</div>					
							<div class="card-body">
							<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center"> 
							<span class="text-primary fs-6" > 구분   </span> &nbsp;
							<select name="checkwork" id="checkwork" class="bg-primary text-white form-select form-select-sm w-auto mx-1" >
								   <?php		 
								   
								   $worktype_arr = array();
								   array_push($worktype_arr,'전체','덧방','신규');

									for($i=0;$i<count($worktype_arr);$i++) {
										 if($claddingornew==$worktype_arr[$i])
													print "<option selected value='" . $worktype_arr[$i] . "'> " . $worktype_arr[$i] .   "</option>";
											 else   
									   print "<option value='" . $worktype_arr[$i] . "'> " . $worktype_arr[$i] .   "</option>";
									} 		   
								   

										?>	  
							</select>
								 &nbsp;&nbsp;
							<span style="color:grey;"> 원청  </span> &nbsp;
							<select name="company1" id="company1"  class="form-select form-select-sm w-auto mx-1" >
								   <?php		 
								   
								   $com1_arr = array();
								   array_push($com1_arr,' ','OTIS','TKEK');

									for($i=0;$i<count($com1_arr);$i++) {
										 if($company1==$com1_arr[$i])
													print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
											 else   
									   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
									} 		   
								   

										?>	  
								</select>
							   
						  &nbsp;&nbsp;
								<span style="color:grey;"> 발주처  </span> &nbsp;
								<select name="company2" id="company2" class="form-select form-select-sm w-auto mx-1" >
								   <?php		 
								   
								   $com1_arr = array();
								   array_push($com1_arr,' ','한산','우성스틸','제일특수강');

									for($i=0;$i<count($com1_arr);$i++) {
										 if($company2==$com1_arr[$i])
													print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
											 else   
									   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
									} 		   
								   

										?>	  
								</select>
							  &nbsp;&nbsp;
								<span style="color:grey;"> 작업소장  </span> &nbsp;
								<select name="workersel" id="workersel"  class="form-select form-select-sm w-auto mx-1" >
								   <?php		 
								   
								   $com1_arr = array();
								   array_push($com1_arr,' ','추영덕','이만희','김운호','김상훈','유영','손상민','조장우','박철우', '이인종','김진섭');

									for($i=0;$i<count($com1_arr);$i++) {
										 if($workersel==$com1_arr[$i])
													print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
											 else   
									   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
									} 		   
								   

							   ?>	  
							   
							 </select>
						  &nbsp;&nbsp;	  &nbsp;&nbsp;	 	
								   
							</div>
						</div>
						</div>
					
			
		<?php
	   if(isset($_SESSION["userid"]))
	   {
	  ?> 
	  <button type="button" class="btn btn-dark  btn-sm" id="writeBtn" > <ion-icon name="pencil-outline"></ion-icon> 신규  </button> 
	   
	  <?php
	   }
	  ?>    
	  
	  <style>
	 .card-header, .card-body {
			padding: 4px;
		}
	  </style>
	</div>
	
	 <div id="myDiv" style="display:none;" >		 
	 </div> 	
	 
		  </div> <!-- end of 2단계 list_search1  -->
</div> <!--card-body-->
			 
<div class="row p-1 mt-1 mb-1 justify-content-center align-items-center">     
<table class="table table-hover" id="myTable">
    <thead class="table-primary">
      <tr>
        <th class="text-center" style="width:3%;" >구분</th>
        <th class="text-center" style="width:3%;" ><span class="badge bg-success">외주</span></th>
        <th class="text-center color-gray " style="width:5%;" >접수</th>
		<th class="text-center text-danger" style="width:3%;" ><span class="text-danger"> 검사 </span></th>
		<th class="text-center"  style="width:3%;" ><span class="text-secondary"> 배정 </span></th>        
        <th class="text-center"  style="width:3%;" ><span class="text-success"> 예정 </span></th>                
        <th class="text-center" style="width:5%;"> 설계</th>                
        <th class="text-center" style="width:3%;"> 출고</th>
        <th class="text-center" style="width:3%;">시공</th>
        <th class="text-center" style="color:blue"> 전 <ion-icon name="image-outline"></ion-icon></th>
        <th class="text-center" style="color:red"> 후 <ion-icon name="image-outline"></ion-icon></th>
        <th class="text-center">청구 </th>
        <th class="text-center">현장명 </th>
        <th class="text-center">재질(소재) 사급-청색 </th>
        <th class="text-center">원청 </th>
        <th class="text-center">발주처 </th>
        <th class="text-center" style="width:4%;">시공 </th>
        <th class="text-center" style="width:5%;">설치수량 </th>
        <th class="text-center">HPI</th>
        <th class="text-center">비고</th>
      </tr>
    </thead>
    <tbody>
		<?php  

		$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번		

  // print 'search : ' . $search . '<br>' ;   
  // print $sql;   		
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			   include "_row.php";	  
			  
			  if($filename1!=Null)
			       $filename1='등록';
				   else
				      $filename1=Null;
					  
			  if($filename2!=Null)
			       $filename2='등록';
				   else
				      $filename2=Null;					  
			  
			  $sum[0] = $sum[0] + (int)$widejamb;
			  $sum[1] += (int)$normaljamb;
			  $sum[2] += (int)$smalljamb;
			  $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;			  		  

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
		      if($assigndate!="0000-00-00" and $assigndate!="1970-01-01" and $assigndate!="")  $assigndate = date("Y-m-d", strtotime( $assigndate) );
					else $assigndate="";		  
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
              $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") 
			  {
			      $draw_done = "OK";	
					if($designer!='')
						 $draw_done = $designer ;
			  }		  
?>
			 
<tr onclick="redirectToView('<?=$num?>', '<?=$tablename?>')">   
  <td>
    <?php
      if($checkstep == '신규') {
        print '<span class="badge bg-danger">' . $checkstep . '</span>';
        $workplacename = "[신규] " . $workplacename;
      } else {
        print $checkstep;
      }
    ?>
  </td>
  <td class="text-center"><span class="badge bg-success"><?=$outsourcing?></span></td>
  <td class="text-center"  data-order="<?= $orderday ?>"><?=$orderday?></td>
  <td class="text-center text-danger"  data-order="<?= $testday ?>"><?=echo_null(iconv_substr($testday, 5, 5, "utf-8"))?></td>
  <td class="text-center text-secondary"  data-order="<?= $assigndate ?>"><?=echo_null(iconv_substr($assigndate, 5, 5, "utf-8"))?></td>  
  <td class="text-center text-success"  data-order="<?= $endworkday ?>"><?=echo_null(iconv_substr($endworkday, 5, 5, "utf-8"))?></td>  
  <td class="text-center"  data-order="<?= $draw_done ?>"><?=echo_null($draw_done)?></td>  
  <td class="text-center"  data-order="<?= $workday ?>"><?=echo_null(iconv_substr($workday, 5, 5, "utf-8"))?></td>
  <td class="text-center"  data-order="<?= $doneday ?>"><?=echo_null(iconv_substr($doneday, 5, 5, "utf-8"))?></td>
  <td class="text-center"><?=echo_null($filename1)?></td>
  <td class="text-center "><?=echo_null($filename2)?></td>
  <td class="text-center "  data-order="<?= $demand ?>"><?=echo_null(iconv_substr($demand, 5, 5, "utf-8"))?></td>
  <td><?=echo_null($workplacename)?></td>
  <?php
  
  if($checkmat1 == 'checked')
	   $material2 = '(사급)' . $material2;
  if($checkmat2 == 'checked')
	   $material4 = '(사급)' . $material4;
  if($checkmat3 == 'checked')
	   $material6 = '(사급)' . $material6;
  
    $materials = "";
    $materials1 = $material1 . " " . $material2;
    $materials2 = $material3 . " " . $material4;
    $materials3 = $material5 . " " . $material6;

    if (!empty(trim($material3))) {
      $materials .= "\r\n" . $material3;
    }

    if (!empty(trim($material4))) {
      $materials .=  " " . $material4;
    }

    if (!empty(trim($material5))) {
      $materials .= $material5;
    }

    if (!empty(trim($material6))) {
      $materials .= " " . $material6;
    } else {
      $materials = rtrim($materials);
    }

    $workitem = "";
    if ($widejamb != "")
      $workitem = "막" . $widejamb;
    if ($normaljamb != "")
      $workitem .= "멍" . $normaljamb;
    if ($smalljamb != "")
      $workitem .= "쪽" . $smalljamb;
  
$spanClass1 = (strpos($materials1, '사급') !== false) ? 'text-primary' : '';
$spanClass2 = (strpos($materials2, '사급') !== false) ? 'text-primary' : '';
$spanClass3 = (strpos($materials3, '사급') !== false) ? 'text-primary' : '';

$brTag2 = (!empty(trim($materials2)) !== false) ? '<br>' : '';
$brTag3 = (!empty(trim($materials3)) !== false) ? '<br>' : '';

echo '<td class="text-center"><span class="'.$spanClass1.'">'.$materials1.'</span> '.$brTag2.' <span class="'.$spanClass2.'">'.$materials2.'</span>'.$brTag3.' <span class="'.$spanClass3.'">'.$materials3.'</span></td>';
?>

  <td class="text-center"><?=echo_null(iconv_substr($firstord, 0, 5, "utf-8"))?></td>
  <td class="text-center"><?=echo_null($secondord)?></td>
  <td class="text-center"><?=echo_null($worker)?></td>
  <td class="text-center"><?=echo_null($workitem)?></td>
  <td class="text-center text-success"><?=echo_null(iconv_substr($hpi, 0, 10, "utf-8"))?></td>
  <td><?=echo_null(iconv_substr($memo, 0, 10, "utf-8"))?></td>
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
  
 
<? include '../footer_sub.php'; ?>
</div>
</div>


</form>
   
</body>
</html>
  
<script>

var dataTable; // DataTables 인스턴스 전역 변수
var jambpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 100,
        "lengthMenu": [50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        }
       //  "order": [[2, 'desc']] // 이것이 첫화면의 정렬을 정하는 부분이다.
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('jambpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var jambpageNumber = dataTable.page.info().page + 1;
        setCookie('jambpageNumber', jambpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('jambpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('jambpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {	
    var url = "view.php?num=" + num ;          
	customPopup(url, 'jamb 수주내역', 1850, 900); 		    
}

$(document).ready(function(){	
	$("#writeBtn").click(function(){ 				
		var url = "write_form.php"; 				
		customPopup(url, 'jamb 수주내역', 1850, 900); 	
	 });			 
		
});	

function saveSearch() {
    let searchInput = document.getElementById('search');
    let searchValue = searchInput.value;

    // console.log('searchValue ' + searchValue);

    if (searchValue === "") {
        document.getElementById('board_form').submit();
    } else {
        let now = new Date();
        let timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        let searches = getSearches();
        // 기존에 동일한 검색어가 있는 경우 제거
        searches = searches.filter(search => search.keyword !== searchValue);
        // 새로운 검색 정보 추가
        searches.unshift({ keyword: searchValue, time: timestamp });
        searches = searches.slice(0, 30);

        document.cookie = "searches=" + JSON.stringify(searches) + "; max-age=31536000";
		
		var jambpageNumber = 1;
		setCookie('jambpageNumber', jambpageNumber, 10); // 쿠키에 페이지 번호 저장	
        
		// Set dateRange to '전체' and trigger the change event
        $('#dateRange').val('전체').change();
		 document.getElementById('board_form').submit();
    }
}

// 검색창에 쿠키를 이용해서 저장하고 화면에 보여주는 코드 묶음
	document.addEventListener('DOMContentLoaded', function() {
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
		}, 300); // Delay of 100 milliseconds
	});


    function hideAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'none';
    }

    function showAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'block';
    }
///////////////////// input 필드 값 옆에 X 마크 띄우기 
///////////////////// input 필드 값 옆에 X 마크 띄우기 

var btnClear = document.querySelectorAll('.btnClear');
btnClear.forEach(function(btn){
	btn.addEventListener('click', function(e){
		btn.parentNode.querySelector('input').value = "";			
		e.preventDefault(); // 기본 이벤트 동작 막기
	  // 포커스 얻기
	  btn.parentNode.querySelector('input').focus();
	})
})	

function renderAutocomplete(matches) {
    const autocompleteList = document.getElementById('autocomplete-list');    

    // Remove all .autocomplete-item elements
    const items = autocompleteList.getElementsByClassName('autocomplete-item');
    while(items.length > 0){
        items[0].parentNode.removeChild(items[0]);
    }

    matches.forEach(function(match) {
        let div = document.createElement('div');
        div.className = 'autocomplete-item';
        div.innerHTML =  '<span class="text-primary">' + match.keyword + ' </span>';
        div.addEventListener('click', function() {
            document.getElementById('search').value = match.keyword;
            autocompleteList.innerHTML = '';
            // page 1로 초기화 해야함
            $("#page").val('1');
            $("#stable").val('1');	
			// console.log(match.keyword);
            document.getElementById('board_form').submit();    
        });
        autocompleteList.appendChild(div);
    });
}


function getSearches() {
    let cookies = document.cookie.split('; ');
    for (let cookie of cookies) {
        if (cookie.startsWith('searches=')) {
            try {
                let searches = JSON.parse(cookie.substring(9));
                // 배열이 50개 이상의 요소를 포함하는 경우 처음 50개만 반환
                if (searches.length > 30) {
                    return searches.slice(0, 30);
                }
                return searches;
            } catch (e) {
                console.error('Error parsing JSON from cookies', e);
                return []; // 오류가 발생하면 빈 배열 반환
            }
        }
    }
    return []; // 'searches' 쿠키가 없는 경우 빈 배열 반환
}


function SearchEnter(){

    if(event.keyCode == 13){		
		saveSearch();
    }
}
	
function button_condition(con)
{	
	$("#buttonval").val(con);							
	$("#sortof").val(con);								
	$('#board_form').submit();		// 검색버튼 효과				
}	

	  
$(document).ready(function(){
	$("#choiceworkerBtn").click(function(){ 	
		customPopup('choiceworker.php', '소장선택', 1100, 300);      
	 });	
		
	$("#outsourcingBtn").click(function(){ 	
		customPopup('../work_outcost/list.php', '외주단가 설정', 1400, 900);      
	 });	
		
		
	$("#searchBtn").click(function(){ 	
		  saveSearch(); 
	 });		


// 공사유형 변경시 작동함
$("#checkwork").change(function(){			 
	// $("#find").val('checkstep');	
	$("#claddingornew").val($(this).val());	
	$('#board_form').submit();		// 검색버튼 효과

});	
		
// 원청 변경시 작동함
  $("#company1").change(function(){
	$('#sortof').val('0');	  
		 $("#company2").val('');
		 $("#workersel").val('');
         List_name($(this).val())

});	
		
// 발주처 변경시 작동함
  $("#company2").change(function(){
	$('#sortof').val('0');	  
		$("#company1").val('');
		 $("#workersel").val('');	  
         List_name($(this).val())
});	

// 소장 변경시 작동함
  $("#workersel").change(function(){
		$("#company1").val('');
		$("#company2").val('');
         List_name($(this).val())
});	
	
	
}); // end of document ready	
	
function List_name(worker)
{	
	var worker; 				
	var name='<?php echo $user_name; ?>' ;

	$("#search").val(worker);	
	$('#board_form').submit();		// 검색버튼 효과
}

$(document).ready(function() {
    $('.search-condition').change(function() {
        // 모든 체크박스의 선택을 해제합니다.
        $('.search-condition').not(this).prop('checked', false);

        // 선택된 체크박스의 값으로 `check` 필드를 업데이트합니다.
        var condition = $(this).is(":checked") ? $(this).val() : '';
        $("#check").val(condition);

        // 검색 입력란을 비우고 폼을 제출합니다.
        // $("#search").val('');                                                      
        $('#board_form').submit();  
    });
});

$(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   // title = '품질방침/품질목표';
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	

</script>