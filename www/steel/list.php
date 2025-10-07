<?php
require_once(includePath('session.php'));

 if(!isset($_SESSION["level"]) || $level>8) {
	     $_SESSION["url"]='https://8440.co.kr/steel/list.php' ; 		   
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");         
         exit;
}  
$title_message = '원자재 입출고';      
 ?>  
 <?php include getDocumentRoot() . '/load_header.php';
 ?>  
<title> <?=$title_message?>  </title>  					                	 
<style>
#showextract {
	display: inline-block;
	position: relative;
}		
#showextractframe {
    display: none;
    position: absolute;
    width: 550px;
    z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */
    top: 110px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
</style> 
	
<body>   

<?php require_once(includePath('myheader.php')); ?>   

<?php 
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	

function check_in_range($start_date, $end_date, $user_date)
{  
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($user_date);
  
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}	

// include "../subload_notice.php";  //공지사항 불러오기
include "_request.php";
 
if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	$find=$_REQUEST["find"]; 
 
if($find=='')
	$find = "전체";
 
 // 철판종류에 대한 추출부분
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

// laser todolist 배열
$todolist=array();
$todolistlink=array();
		
$sql="select * from mirae8440.ceiling " ;

// 5일전까지 검색해서 찾아주는 루틴임
$today = date('Y-m-d');
$aftersixeday = date("Y-m-d", strtotime("+6 day", strtotime($today)));
$aftertenday = date("Y-m-d", strtotime("+6 day", strtotime($today)));
$end_date = $aftersixeday;
$end_date_draw = $aftertenday;

$start_date = $today ;

function isEligibleForProcessing($draw_date, $laser_date, $make_date) {
    // 설계일자가 '0000-00-00'이거나 비어있는 경우 제외
    if ($draw_date === '0000-00-00' || empty($draw_date)) {
        return false;
    }
    // 레이저 가공 날짜와 제작 날짜가 둘 다 유효하지 않을 경우
    return empty($laser_date) && empty($make_date);
}

// 데이터베이스 쿼리 실행
try {  
    $stmh_sub = $pdo->query($sql);
    while ($row = $stmh_sub->fetch(PDO::FETCH_ASSOC)) {
        include '../ceiling/_rowDB.php';

        $main_draw_arr = (substr($main_draw, 0, 2) == "20") ? iconv_substr($main_draw, 5, 5, "utf-8") : ($bon_su < 1 ? "X" : "");
        $lc_draw_arr = (substr($lc_draw, 0, 2) == "20") ? iconv_substr($lc_draw, 5, 5, "utf-8") : ($lc_su < 1 ? "X" : "");
        if (in_array($type, ['011', '012', '013D', '025', '017', '014', '037', '038'])) {
            $lc_draw_arr = "X";
        }
        
        $maincondition = isEligibleForProcessing($main_draw, $eunsung_laser_date, $eunsung_make_date);
        $lccondition = isEligibleForProcessing($lc_draw, $lclaser_date, '');

        if (check_in_range($start_date, $end_date, $deadline) && ($maincondition || $lccondition)) {
            array_push($todolist, $workplacename . '(' . $secondord .')');
            array_push($todolistlink, $num);
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
  
// steel 테이블에서 item만 가져와 중복 제거
try {
    $sql = "SELECT DISTINCT TRIM(item) as item FROM mirae8440.steel WHERE item IS NOT NULL AND item != ''";
    $stmh = $pdo->query($sql);

    $steelsource_item = [];
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $steelsource_item[] = $row['item'];
    }
	
	$steelsource_item[] = '';
    // 정렬
    sort($steelsource_item);

} catch (PDOException $Exception) {
    echo "오류: " . $Exception->getMessage();
}


// 현재 날짜
$currentDate = date("Y-m-d");

 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"] ?? '';	 
$todate=$_REQUEST["todate"] ?? '';	 

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-1 weeks", strtotime($currentDate))); // 1주일 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  	 
// 입출고일 기준
$SettingDate="outdate ";    

$common = "   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
$a= $common . " order by outdate desc, num desc ";    //내림차순
$b= $common . " order by outdate desc, num desc ";    //내림차순 전체
  
// 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum= array();
$yday_sum=array();
$ydaysaved_sum=array();

// 상세내역 전달을 위한 것
$title_arr=array(); 
$titleurl_arr=array(); 

$yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-86400);

$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
// if($week[ date('w', strtotime($yesterday)) ] == '(토)')
   // $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-172800);

	if($week[ date('w', strtotime($yesterday)) ] == '(일)')
       $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-172800);		
	//   $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-259200);
// print_r($week[ date('w', strtotime($yesterday)) ]);
// print $yesterday;

$sql_yesterday="select * from mirae8440.steel where outdate between date('$yesterday') and date('$yesterday') order by outdate";
 
$tmpsum = 0; 

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql_yesterday);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
		$num=$row["num"];

		$outdate=$row["outdate"];			  
		$item=trim($row["item"]);			  
		$spec=trim($row["spec"]);
		$steelnum=$row["steelnum"];			  
		$company=trim($row["company"]);
		$comment=$row["comment"];
		$which=$row["which"];	 	

		// if($company=='미래기업') $company='';	// 일반매입처리
		// if($company=='윤스틸') $company='';		// 일반매입처리	  
		// if($company=='현진스텐') $company='';		// 일반매입처리	  
		$tmp=$item . $spec . $company;	        
		$tmpsum +=(int)$steelnum;		// 입고숫자 더해주기 합계표								 
		           
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

if($tmpsum<1)
	$yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-159200);
 
// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search); 

$sql="select * from mirae8440.steel where outdate between date('$fromdate') and date('$Transtodate') order by num desc";
 
 
  if($mode=="search"){
		  if(empty($search)){			               
							if(trim($Bigsearch)=='' && $find=="전체")
							{		
									 $sql="select * from mirae8440.steel where outdate between date('$fromdate') and date('$Transtodate') order by outdate desc, num desc " ;
							}
							else {	 
							
									 $sql="select * from mirae8440.steel where outdate between date('$fromdate') and date('$Transtodate') order by outdate desc, num desc " ;
							
								  if($find=='전체') {
									  $sql ="select * from mirae8440.steel where (item like '%$Bigsearch%')  and  outdate between date('$fromdate') and date('$Transtodate')  order by " . $SettingDate . " desc, num desc   ";									  
												}							
								  if($find=='입고') {
									  $sql ="select * from mirae8440.steel where (item like '%$Bigsearch%')  and (which = '1' ) and  outdate between date('$fromdate') and date('$Transtodate') order by " . $SettingDate . " desc, num desc   ";									  
											}
								  if($find=='출고') {
									  $sql ="select * from mirae8440.steel where (item like '%$Bigsearch%')  and ( which = '2' ) and  outdate between date('$fromdate') and date('$Transtodate') order by " . $SettingDate . " desc, num desc   ";
											}										
											
											
									}
						}
						
	             elseif($search!="" && $find!="전체") { // 각 필드별로 검색어가 있는지 쿼리주는 부분	
				     if(trim($Bigsearch)=='')   // Bigsearch가 없는 경우
					      {
							 if($find=='입고') {
								 
								  $sql ="select * from mirae8440.steel where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and (which = '1' ) and  ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) or (  item like '%$search%') or (bad_choice like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%'))  ";
								  $sql .=" order by " . $SettingDate . " desc, num desc   ";
								  
									}
								else { // 출고인 경우								  								
								  $sql ="select * from mirae8440.steel where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and (which = '2' ) and  ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) or (  item like '%$search%') or (bad_choice like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) ";
								  $sql .=" order by " . $SettingDate . " desc, num desc   ";
									}
					       }
							  else {   // bigsearch 있는 경우
										 if($find=='입고') {
												// 철판종류도 지정하고 검색어도 있는 경우
											  $sql ="select * from mirae8440.steel where  (item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
											  $sql .="or (  item like '%$search%') or (bad_choice like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) and (which = '1' )  and  outdate between date('$yesterday') and date('$yesterday') order by " . $SettingDate . " desc, num desc   ";
												}
											else { // 출고인 경우								  
												// 철판종류도 지정하고 검색어도 있는 경우
												  $sql ="select * from mirae8440.steel where  (item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
												  $sql .="or (  item like '%$search%') or (bad_choice like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) and (which = '2' )  and  outdate between date('$yesterday') and date('$yesterday') order by " . $SettingDate . " desc, num desc   ";
												}
									   }			
						}						   
             elseif($search!="" && $find=="전체") { // 각 필드별로 검색어가 있는지 쿼리주는 부분	
                        // 필드 선택없고 눌렀을때 Bigsearch -> item값이 있을 경우 검색
			            if(trim($Bigsearch)!='')						
						{ 
					          // 철판종류도 지정하고 검색어도 있는 경우
							  $sql ="select * from mirae8440.steel where  (item like '%$Bigsearch%') and ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
							  $sql .="or (  item like '%$search%') or (bad_choice like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%') or (method like '%$search%')  or (comment like '%$search%'))   order by " . $SettingDate . " desc, num desc   ";
						  }  
						  else
							  {							  						
								  $sql ="select * from mirae8440.steel where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate') ) and ( (outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
								  $sql .="or (  item like '%$search%') or (bad_choice like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')   or (method like '%$search%')  or (comment like '%$search%') )  order by " . $SettingDate . " desc, num desc   ";
							  }
						}

               }
	
  if(!empty($bad_choice) && $bad_choice!=="전체") {
		$sql ="select * from mirae8440.steel where (bad_choice like '%$bad_choice%')  AND outdate between date('$fromdate') and date('$Transtodate')  order by " . $SettingDate . " desc, num desc   ";
	}
      				

// 검색어가 없을경우는 50개만 보여준다.				
// if($search=="")
	// $sql .= " limit 0, 50 ";
  
try{ 
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();
	  
		if($regist_state==null)
			$regist_state="1";					
		
			$font="black";

			switch ($regist_state) {
				case   "1"     :  $font_state="black"; $regist_word="등록"; break;
				case   "2"     :  $font_state="red"  ; $regist_word="접수"; break;	
				case   "3"     :  $font_state="blue"  ; $regist_word="완료"; break;	
				default:  $regist_word="등록"; break;
			}								
					  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}	

$findarr=array('전체','입고','출고');

// print $sql;
 
?>   
   
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">  
  
 <div class="container-fluid" >  
		<input type="hidden" id="username" name="username" value="<?=$user_name?>" size="5" > 					
		<input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>" size="5" > 
		<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
		<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
		<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
		<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
		<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
		<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
		<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
		<input type="hidden" id="list" name="list" value="<?=$list?>" > 				
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" > 	
		<input type="hidden" id="bad_choice_hidden" name="bad_choice_hidden" value="<?=$bad_choice_hidden?>" > 	
		
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-3 text-center" style="display:none"> 
		</div>     
	</div>	
	<div class="d-flex mb-3 mt-4 justify-content-center align-items-center"> 		 
		<H5>
			 <?=$title_message?> 
			 <button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  
		</H5>		 
	</div>	
	
		<!-- <button type="button" class="btn btn-secondary  btn-sm "  onclick="LoadBadDb();"> DB  </button>&nbsp; -->		
		
	 <div class="row d-flex justify-content-center  align-items-center mb-2" >  	
		<div  class="col-sm-6" >
			<div id="display_list"  class="card justify-content-center align-items-center " >							
					<div  class="card-body" >
						<table  class="table table-hover">
							<tbody>
								<tr>
									<td class="col">				 
										<div class="d-flex  justify-content-center align-items-center mb-3">	
											<span class="badge bg-primary fs-6"> 본천장/조명천장 (설계미완료 제외) 출하일기준 6일전 laser 미가공 현장 (샤집 천장 작업해요)</span>                                                          
										</div>			 
										<div class="d-flex  justify-content-center align-items-center">	
											<span id="todolist" class="form-control"> 천장 Todo List </span>                                                          
										</div>
									</td>	
								</tr>
							</tbody>
						</table>
					</div>
			 </div>
		 </div>		
	 </div>		

	<div class="d-flex mb-2 justify-content-center align-items-center"> 			
	
		▷ <?= $total_row ?>  &nbsp; &nbsp; 
						<!-- 기간부터 검색까지 연결 묶음 start -->	
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
						<button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='three_month_ago()' > M-3월 </button>
						<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='prepre_month()' > 전전월 </button>	
						<button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='pre_month()' > 전월 </button> 						
						<button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange "  onclick='this_today()' > 오늘 </button>
						<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_month()' > 당월 </button>
						<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_year()' > 당해년도 </button> 
					</div>
				</div>
			</div>		
			   <input type="date" id="fromdate" name="fromdate" size="12"  class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate" size="12"   class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
			   &nbsp;&nbsp;
		</div>
	<div class="d-flex justify-content-center align-items-center" > 	 	 
	  &nbsp;  <span class="text-danger"> 불량유형 </span> &nbsp; 
		<?php
			$options = ["전체", "설계", "레이져", "V컷", "절곡", "운반중", "소장", "업체", "기타", "개발품", "소재"];
			
			if ($bad_choice == "") {
				$bad_choice = "해당없음";
			}
		?>
		<select name="bad_choice" id="bad_choice" onchange="changeType(this)" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			<?php foreach ($options as $key => $value): ?>
				<option <?= $bad_choice == $value ? "selected" : "" ?> value="<?= $value ?>"><?= $value ?></option>
			<?php endforeach; ?>
		</select>  		
 	    <select name="find" id="find"  class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
           <?php			   
		   for($i=0;$i<count($findarr);$i++) {
			     if($find==$findarr[$i]) 
							print "<option selected value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
					 else   
			   print "<option value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select>
<style>
    #Bigsearch {
        width: 220px; /* 원하는 크기로 조정하세요 */
    }
</style>

<select name="Bigsearch" id="Bigsearch" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
    <?php
    for($i=0;$i<count($steelsource_item);$i++) {
        if($Bigsearch==$steelsource_item[$i])
            print "<option selected value='" . $steelsource_item[$i] . "'> " . $steelsource_item[$i] .   "</option>";
        else
            print "<option value='" . $steelsource_item[$i] . "'> " . $steelsource_item[$i] .   "</option>";
    }
    ?>
</select>		   				   
	   &nbsp;
	   	   
<input type="text" id="search" name="search" class="form-control" style="width:150px; height:32px;"  value="<?=$search?>" autocomplete="off" onkeydown="JavaScript:SearchEnter();" placeholder="일반 검색시" >
  &nbsp;
			<button type="button" id="searchBtn" class="btn btn-dark btn-sm ms-1 me-3"  > <i class="bi bi-search"></i> 검색 </button>			
				<span id="showextract" class="btn btn-primary btn-sm me-4 " > <i class="bi bi-tools"></i>  </span>	&nbsp; 
				<div id="showextractframe" class="card">
					<div class="card-header text-center " style="padding:2px;">
						사이즈 검색
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
     	 <button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	     
		 <button  type="button" id="rawmaterialBtn"  class="btn btn-dark btn-sm" > <i class="bi bi-list"></i> 재고 </button> &nbsp;		  			
       </div>		       	
  <div class="row mt-3 mb-1 p-1 m-1" >
     <table class="table table-hover " id="myTable">
	   <thead class="table-primary">
		  <tr>
            <th class="text-center" style="width:60px;" >번호</th>
            <th class="text-center" style="width:100px;" >일자</th>
            <th class="text-center" style="width:100px;" >입출고</th>
            <th class="text-center" style="width:250px;" >현장명</th>
            <th class="text-center" style="width:100px;" >모델</th>
            <!-- <th class="text-center" style="width:100px;" >본가공/<br>LC가공</th> -->
            <th class="text-center" style="width:100px;" >철판종류</th>
            <th class="text-center" style="width:100px;" >규격</th>
            <th class="text-center" style="width:50px;" >수량</th>
            <th class="text-center text-success" style="width:80px;" >잔재(Kg)</th>
            <th class="text-center text-danger" style="width:80px;"  > 절감(원)</th>
            <th class="text-center" style="width:100px;" >사급사 </th>
            <th class="text-center" style="width:100px;" >공급사 </th>
            <th class="text-center" style="width:80px;" >샤링여부</th>
            <th class="text-center" style="width:100px;" >불량유형</th>
            <th class="text-center" style="width:150px;" >비고</th>
		  </tr>
		</thead>
	  <tbody>
	 <?php		  
		$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

             include '_row.php';
			  	
		    	$temp_arr = explode("*", $spec);
			
                $saved_weight = 0.0;					
				$saved_weight += ($temp_arr[0] * $used_width_1 * $used_length_1 * 7.93 * (int)$used_num_1)/1000000 ;
				$saved_weight += ($temp_arr[0] * $used_width_2 * $used_length_2 * 7.93 * (int)$used_num_2)/1000000 ;
				$saved_weight += ($temp_arr[0] * $used_width_3 * $used_length_3 * 7.93 * (int)$used_num_3)/1000000 ;
				$saved_weight += ($temp_arr[0] * $used_width_4 * $used_length_4 * 7.93 * (int)$used_num_4)/1000000 ;
				$saved_weight += ($temp_arr[0] * $used_width_5 * $used_length_5 * 7.93 * (int)$used_num_5)/1000000 ;
	
			    $saved_weight = sprintf('%0.1f', $saved_weight);  // 소수점 한자리 표현
				
				switch ($item) {
					case 'CR' :
				          $saved_fee = (float)$saved_weight * conv_num($readIni['CR']);   
						  break;
					case 'PO' :
				          $saved_fee = $saved_weight * conv_num($readIni['PO']);   					
						  break;
					case 'EGI' :
				          $saved_fee = $saved_weight * conv_num($readIni['EGI']);   					
						  break;
					case '201 HL' :
				          $saved_fee = $saved_weight * conv_num($readIni['HL201']) ;   									
						  break;
					case '201 MR' :
					case '201 2B MR' :
				          $saved_fee = $saved_weight * conv_num($readIni['MR201']);   					
						  break;
					case '304 HL' :
				          $saved_fee = $saved_weight * conv_num($readIni['HL304']) ;   									
						  break;
					case '304 MR' :
				          $saved_fee = $saved_weight * conv_num($readIni['MR304']);   					
						  break;
					default:
					      $saved_fee = $saved_weight * conv_num($readIni['etcsteel']);   					
					      break;					
				}


				 if($outdate!="") {
				$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
				$outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
			          }
											
					 if($which=='1')
						   {
						   $tmp_word="입고";
						   $font_state="black";
						   }
						   else
						   {
							   $tmp_word="출고";
							   $font_state="red";				   
						   }
					   		
				     if($bad_choice=='해당없음') 	
							      $bad_choice='' ;			

                 						  

				?>
		    <tr onclick="redirectToView('<?=$num?>')">

            <td class="text-center" > <?=$start_num?>				</td>
            <td class="text-center" >	 <?=$outdate?>		</td>			
			   <?
			      if($tmp_word=='입고') 
                        print '<td class="text-center text-primary" >' .  $tmp_word . '	</td>';
					else
                         print '<td class="text-center text-danger" >' .  $tmp_word . '	</td>';
					?>
            <td                     > <?=$outworkplace?> 			</td>
            <td class="text-center" > <?=$model?>			</td>
			<?php
			// 날짜 값이 '0000-00-00'인 경우 공백으로, 값이 있으면 'm-d' 형식으로 변환
			$bonDone = ($bonDone === '0000-00-00' || empty($bonDone)) ? '' : (new DateTime($bonDone))->format('m-d');
			$lcDone = ($lcDone === '0000-00-00' || empty($lcDone)) ? '' : (new DateTime($lcDone))->format('m-d');
			
			// <td class="text-center">  $bonDone  $lcDone </td>
			?>

			

            <td class="text-center text-primary" > <?=$item?> </td>
            <td class="text-center" > <?=$spec?>			</td>
            <td class="text-center" > <?=$steelnum?>		</td>
            <td class="text-center text-success" > <?=$saved_weight ==0 ? '' : $saved_weight  ?>			</td>						
            <td class="text-center text-danger" > <?=$saved_fee ==0 ? '' : number_format($saved_fee)  ?>			</td>						
            <td class="text-center" >  <?=$company?>			</td>						
            <td class="text-center" >  <?=$supplier?>			</td>						
            <td class="text-center" >  <?=$method?>			</td>
            <td class="text-center" >  <?=$bad_choice?>			</td>
            <td class="text-center"><?= mb_substr($comment, 0, 12) ?></td>					
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
   
 <!-- <video muted autoplay loop>
  <source src="../es/images/header_lasersnijden_1920x480.mp4" type="video/mp4">
  <strong>Your browser does not support the video tag.</strong>
</video>  -->
 
</div>

</form>
<div class="container-fluid">
	<? include '../footer_sub.php'; ?>
</div>
 
<script>

function redirectToView(num) {
    var url = "view.php?num=" + num ;
	customPopup(url, '원자재 출고', 1400, 960); 	
}

$(document).ready(function(){	
	// 원자재현황 클릭시
	$("#rawmaterialBtn").click(function(){ 
			
		 popupCenter('./rawmaterial.php?menu=no'  , '원자재현황보기', 1050, 950);	
	});

	// 원자재 가격테이블 클릭시
	$("#showmaterialfeeBtn").click(function(){ 
			
		 popupCenter('./settings.php'  , '원자재현황보기', 800, 500);	
	});


	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});

	$("#searchBtn").click(function(){ 
		
		  // $BigsearchTag  설정
		  var str = '<?php echo $BigsearchTag; ?>' ;
		  
		 $("#BigsearchTag").val(str.replace(' ','|'));		 
		 
	  // 페이지 번호를 1로 설정
		steelpageNumber = 1;
		setCookie('steelpageNumber', steelpageNumber, 10); // 쿠키에 페이지 번호 저장

		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();	 	 
		$("#board_form").submit();   
	 });		
		
});


function LoadBadDb() {	
	var listVar = $('input[name=bad_choice]:checked').val();	
	console.log(listVar);	
	popupCenter('bad_extract_data.php?bad_choice=' + listVar  , 'DB', 1800, 900);	
}

function changeType(obj) {
    var tmpType = $(obj).val();    
    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과
}


function saveAsFile(str, filename) {  // text파일로 저장하기
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:attachment/text,' + encodeURI(str);
    hiddenElement.target = '_blank';
    hiddenElement.download = filename;
    hiddenElement.click();
}

function SearchEnter(){	
	
    if(event.keyCode == 13){
		
	  // $BigsearchTag  설정
	  var str = $("#Bigsearch").val();
	  
     $("#BigsearchTag").val(str.replace(' ','|'));
		var steelpageNumber = 1;
		setCookie('steelpageNumber', steelpageNumber, 10); // 쿠키에 페이지 번호 저장
	 $("#board_form").submit();  
    }
}

var dataTable; // DataTables 인스턴스 전역 변수
var steelpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('steelpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var steelpageNumber = dataTable.page.info().page + 1;
        setCookie('steelpageNumber', steelpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('steelpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('steelpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

$(document).ready(function() {
    // Event listener for keydown on #search
    $("#search").keydown(function(event) {
        // Check if the pressed key is 'Enter'
        if (event.key === "Enter" || event.keyCode === 13) {
            // Prevent the default action to stop form submission
            event.preventDefault();
            // Trigger click event on #searchBtn
            $("#searchBtn").click();
        }
    });

});


$(document).ready(function() { 

	$("#writeBtn").click(function(){ 
		var page = steelpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
			
		var url = "write_form.php"; 

		customPopup(url, '원자재 출고', 1400, 960); 	
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
            case '최근1개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 1));
                break;
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
</script>
<script>
$(document).ready(function() {
		var todolist = <?php echo json_encode($todolist); ?>;
		var link = <?php echo json_encode($todolistlink); ?>;

		var htmlstr = '';

		for(i=0;i<todolist.length;i++)
		{
			if(i%2==0)
				htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'../ceiling/view.php?num=' + link[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >   <span style="background-color:#46D2D2"> ' + todolist[i] + '</span> </a>  &nbsp; ';
			else
				htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'../ceiling/view.php?num=' + link[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >    ' + todolist[i] + ' </a> &nbsp;&nbsp;';
		}

		if(todolist.length === 0) {
			// $('#deadline_laser').html('<img src="../img/medal.jpg" style="width:7%;" alt="Medal" />  [레이져가공] 없음');
			$('#todolist').hide();
		} else {
			$('#todolist').html(htmlstr);    
			$('#todolist').show();						
		}
});

</script>

<script>
	$(document).ready(function(){
		saveLogData('원자재 출고(철판) '); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 

</body>
</html>