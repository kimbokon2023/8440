<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함

if(!isset($_SESSION["level"]) || $level>6) {
	  /*   alert("관리자 승인이 필요합니다."); */
	 sleep(1);
		  header("Location:" . $WebSite . "login/login_form.php"); 
	 exit;
}        
?>   
 
<?php include getDocumentRoot() . '/load_header.php' ?> 
<link rel="stylesheet" type="text/css" href="../css/dashboard-style.css">


<title> 미래기업 공정 관리 </title>  
</head> 
<style>
.table td, .table th {
    vertical-align: middle;	
	font-size:14px;
}

/* 외주 메모 툴팁 스타일 */
.outsourcing-tooltip {
    position: relative;
    display: inline-block;
    cursor: help;
}

.outsourcing-tooltip .tooltip-content {
    visibility: hidden;
    width: 320px;
    max-width: 90vw;
    background: linear-gradient(135deg, rgb(86, 193, 219) 0%, rgb(35, 173, 197) 100%);
    color: white;
    text-align: left;
    border-radius: 12px;
    padding: 15px;
    position: absolute;
    z-index: 1000;
    bottom: 125%;
    left: 50%;
    margin-left: -160px;
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    font-size: 13px;
    line-height: 1.5;
    transform: translateY(10px);
}

.outsourcing-tooltip .tooltip-content::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -8px;
    border-width: 8px;
    border-style: solid;
    border-color: rgb(35, 173, 197) transparent transparent transparent;
}

.outsourcing-tooltip:hover .tooltip-content {
    visibility: visible;
    opacity: 1;
    transform: translateY(0);
}

.tooltip-title {
    font-weight: 700;
    margin-bottom: 10px;
    color: white;
    border-bottom: 2px solid rgba(255, 255, 255, 0.4);
    padding-bottom: 8px;
    font-size: 14px;
}

.tooltip-memo {
    color: rgba(255, 255, 255, 0.95);
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 200px;
    overflow-y: auto;
}

/* 반응형 디자인 */
@media (max-width: 768px) {
    .outsourcing-tooltip .tooltip-content {
        width: 280px;
        margin-left: -140px;
        font-size: 12px;
    }
}
</style>
 
 <?php
// 값이 'X'인지 아닌지 판단하는 함수
function is_valid_etc_value($val) {
    return isset($val) && $val !== '' && $val !== '0000-00-00' && strtolower($val) !== 'null' && $val !== 'X';
}
 
$search = $_REQUEST["search"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$mode = $_REQUEST["mode"] ?? '';

$cursort = $_REQUEST["cursort"] ?? ($_POST["cursort"] ?? '');
if ($cursort == '') $cursort = '8';

// 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

$now = date("Y-m-d",time());

if($fromdate=="")
{
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	// $fromdate=$fromdate . "2018-01-01";
	$fromdate= date("Y-m-d",time());
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
		  
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];
 
$process="전체";  // 기본 전체로 정한다

$SettingDate="deadline ";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " asc   ";    //내림차순
$b= $common . " asc  ";    //내림차순 전체

$where=" where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ";

$all="  ";
  
if($mode=="search"){		  
		 if($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
				  $sql ="select * from mirae8440.ceiling where ((orderday like '%$search%')  or (type like '%$search%')   or (inseung like '%$search%')  ";
				  $sql .=" or (deadline like '%$search%') or (workplacename like '%$search%') ) and ( " . $SettingDate . " between date('$fromdate') and date('$Transtodate') )  ";
				  $sql .=" order by " . $SettingDate . " asc, num desc  ";				  
						}

		   }
	   
 if($search=='') {	 
		   if($cursort==1||$cursort==0||$cursort=='') // 납기일 기준 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((bon_su>0 or etc_su>0 or lc_su>0) and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'  and type!='037'  and type!='038' ))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			 
		}              
		   if($cursort==2) // 전체 기준 선택시
		{	
					 $sql="select * from mirae8440.ceiling " . $where ;
		}  
		   if($cursort==3) // 레이져 선택시
		{	
			  $common=" where  date(deadline)>=date(now()) and ( etc_su > 0 or ( (eunsung_laser_date  IS NULL or eunsung_laser_date ='0000-00-00')  and bon_su>0 ) or  (  (lclaser_date IS NULL  or lclaser_date ='0000-00-00')  and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014' and type!='037'  and type!='038' ))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  
		}  
		   if($cursort==4) // 절곡 선택시
		{	
			  $common=" where   (  date(deadline)>=date(now()))  and ( etc_su > 0 or (  (mainbending_date  IS NULL or mainbending_date='0000-00-00') and bon_su>0 ) or  ( (lcbending_date IS NULL or  lcbending_date ='0000-00-00') and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014' and type!='037'  and type!='038' ))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			 
		}  	
		   if($cursort==5) // 제관 선택시
		{	
			  $common=" where   (  date(deadline)>=date(now()))  and ( etc_su > 0 or ( (mainwelding_date  IS NULL or  mainwelding_date='0000-00-00') and bon_su>0 ) or  ( (lcwelding_date IS NULL or lcwelding_date ='0000-00-00') and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014' and type!='037'  and type!='038' ))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          			                           
		}  
		   if($cursort==6) // 도장 선택시
		{	
			  $common=" where   (  date(deadline)>=date(now()))  and ( etc_su > 0 or ( (mainpainting_date  IS NULL or mainpainting_date='0000-00-00')  and bon_su>0 ) or  ((lcpainting_date IS NULL or lcpainting_date ='0000-00-00') and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014' and type!='037'  and type!='038' ))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          			 
		} 
		   if($cursort==7) // 조립 선택시
		{	
			  $common=" where    (  date(deadline)>=date(now()))  and ( etc_su > 0 or ( (mainassembly_date IS NULL  or  mainassembly_date='0000-00-00')  and bon_su>0 ) or  ( (lcassembly_date IS NULL or lcassembly_date ='0000-00-00') and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014' and type!='037'  and type!='038' ))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          			  
		} 	
		   if($cursort==8) // 미제작List 선택시
		{	
			$common=" where    (  date(deadline)>=date(now()))  and ( ( (mainassembly_date IS NULL  or  mainassembly_date='0000-00-00')  and bon_su>0 ) or " . 
			" ( (lcassembly_date IS NULL or lcassembly_date ='0000-00-00') and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014' and type!='037'  and type!='038' )) " . 
			" or  ((etcassembly_date IS NULL or etcassembly_date ='0000-00-00') and etc_su>0 ) ) order by deadline asc, num desc ";  			
			$sql = "SELECT * FROM mirae8440.ceiling " . $common;			
			  
		}  	
		if($cursort == 9) // 7일 전부터 앞으로 2달 후까지
		{
			$common = " WHERE date(deadline) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() + INTERVAL 2 MONTH
			
						ORDER BY deadline ASC, num DESC";
			$sql = "SELECT * FROM mirae8440.ceiling " . $common;			
		}

			
 }

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$sqlMain = $sql;
?>
<form id="board_form" name="board_form" method="post" action="list.php?mode=search">  		
<div class="container-fluid">
<div  class="row d-flex">
<?php include getDocumentRoot() . '/ceiling/chart_page.php' ?> 

<div class="col-sm-8 mt-1 mb-2">
<div  class="card">
	<div  class="card-body">

	<div class="d-flex mt-5 mb-5 fs-5 justify-content-center gap-2">
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-light px-4 py-2" onclick="location.href='./etclist.php';">
			(판넬,발보호판)
		</button>
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-info px-4 py-2" onclick="location.href='../paint/index.php';">
			도장발주
		</button>
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-success px-4 py-2" onclick="location.href='./packinglist.php';">
			포장상자
		</button>
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-outline-dark px-4 py-2 me-4" onclick="popupCenter('../ceiling/list_part_table.php?menu=no','주요부품표',1000,950);">
			주요부품
		</button>
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-dark px-4 py-2" onclick="self.close();">
			&times; 닫기
		</button>
	</div>

	<div class="d-flex justify-content-center gap-3 align-items-center">
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-light px-4 py-2" onclick="show_list(2);">
			전체
		</button>
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-info px-4 py-2" onclick="show_list(9);">
			7일전
		</button>
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-danger px-4 py-2" onclick="show_list(8);">
			미제작
		</button>
		<div class="inputWrap me-1">
			<input type="text" name="search" id="search" value="<?=$search?>" class="form-control px-3 py-1" onkeydown="JavaScript:SearchEnter(event);" style="width:200px;" autocomplete="off">
			<button class="btnClear"></button>
		</div>
		<input type="hidden" id="alerts" name="alerts" value="<?=$alerts?>" size="3">
		<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="3">
		<button type="button" class="modern-management-card modern-dashboard-header btn btn-dark btn-sm px-3 py-2" onclick="process_list();">
			<i class="bi bi-search"></i> 검색
		</button>
	</div>
	</div>
</div>
</div>
</div>
<!-- 
<button type="button" class="btn btn-secondary  fs-5" onclick="show_list(4);"> 레이져/절곡  </button>&nbsp; 				
			<button type="button" class="btn btn-danger  fs-5" onclick="show_list(6);"> 제관도장  </button>&nbsp; 	
			<button type="button" class="btn btn-success  fs-5" onclick="show_list(7);"> 조립  </button>&nbsp; 							 -->

<div class="row d-flex mt-3 mb-2 justify-content-center">  
	<table class="table table-hover" id="myTable">
	  <thead class="table-primary">
		<tr>
		<th class="col text-center" style="width:100px;"> 납기일   </th>  
		<th class="col text-center" style="width:100px;"> inside   </th>     
		<th class="col text-center text-success" style="width:60px;"> 박스<br>포장   </th>  		  						  
		<th class="col text-center" style="width:350px;"> [발주처]현장명   </th>  
		<th class="col text-center" style="width:120px;"> Type   </th>  
		<th class="col text-center" style="width:80px;"> 결합수   </th>  
		<th class="col text-center" style="width:150px;"> 비고   </th>  
		<th class="col text-center" style="width:200px;"> 설계(본/LC/기타)   </th>  
		<th class="col text-center text-success " style="width:80px;"> 외주   </th>  
		<th class="text-center text-white bg-primary " style="width:80px;">본LB</th> 				
		<th class="text-center text-white bg-primary " style="width:80px;">제관</th> 
		<th class="text-center text-white bg-primary " style="width:80px;">도장</th> 
		<th class="text-center text-white bg-primary " style="width:80px;">조립</th> 				 			
		<th class="text-center text-white bg-success " style="width:100px;">LC_LB<br>기타_LB</th> 
		<th class="text-center text-white bg-success " style="width:80px;">제관<br>절곡</th> 
		<th class="text-center text-white bg-success " style="width:80px;">도장<br>제관</th> 
		<th class="text-center text-white bg-success " style="width:80px;">결선<br>도장</th> 
		<th class="text-center text-white bg-success " style="width:80px;">포장<br>조립</th> 			
		<th class="col text-center  text-white bg-warning "   style="width:50px;" > <i class="bi bi-images"></i>  </th>  			
	  </tr>
	</thead>
  <tbody>	  
<?php  				
$start_num=$total_row;  
function processDate($date, $condition, $types, $type) {
	if (substr($date, 0, 2) == "20") {
		return iconv_substr($date, 5, 5, "utf-8"); // 날짜 추출
	} elseif ($condition < 1 || in_array($type, $types)) {
		return "X"; // 조건에 따라 "X" 반환
	}
}
		
 try{   
	  $stmh = $pdo->query($sqlMain);          
      $total_row=$stmh->rowCount();	      

	  $titlemsg = '';
	   
	      switch($cursort)  {
			  case  1 :
		        $titlemsg = ' 납품예정 List ';
				break;			  
			  case  2 :
		        $titlemsg = ' 발주된 전체 List ';
				break;			  
			  case  3 :
		        $titlemsg = ' 레이져 미가공 List ';
				break;
			  case  4 :
		        $titlemsg = ' 절곡 미가공 List ';	
				break;				
			  case  5 :
		        $titlemsg = ' 제관 미가공 List ';		
				break;
			  case  6 :
		        $titlemsg = ' 미도장 List ';	
	            break;
			  case  7 :
		        $titlemsg = ' 미조립 List ';	
                break;			
			  case  8 :
		        $titlemsg = ' 미제작 List ';	
                break;					
		  }
					
				   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
					  
					include getDocumentRoot() . '/ceiling/_row.php';
					  
					  $date_font="black";  // 현재일자 Red 색상으로 표기
					  if($nowday==$orderday) {
									$date_font="red";
								}
					  $date_font1="black";  //  납기일자 색상으로 표기
					  if($nowday==$workday) {
									$date_font1="blue";
								}
							
					  $workday=trans_date($workday);
					  $startday=trans_date($startday);
					  $demand=trans_date($demand);
					  $orderday=trans_date($orderday);
					  $deadline=trans_date($deadline);
					  $testday=trans_date($testday);
					  $lc_draw=trans_date($lc_draw);
					  $lclaser_date=trans_date($lclaser_date);
					  $lcbending_date=trans_date($lcbending_date);
					  $lcwelding_date=trans_date($lcwelding_date);
					  $lcpainting_date=trans_date($lcpainting_date);
					  $lcassembly_date=trans_date($lcassembly_date);
					  $main_draw=trans_date($main_draw);			
					  $eunsung_make_date=trans_date($eunsung_make_date);			
					  $eunsung_laser_date=trans_date($eunsung_laser_date);			
					  $mainbending_date=trans_date($mainbending_date);			
					  $mainwelding_date=trans_date($mainwelding_date);			
					  $mainpainting_date=trans_date($mainpainting_date);			
					  $mainassembly_date=trans_date($mainassembly_date);		
					  $etclaser_date=trans_date($etclaser_date);			
					  $etcbending_date=trans_date($etcbending_date);			
					  $etcwelding_date=trans_date($etcwelding_date);			
					  $etcpainting_date=trans_date($etcpainting_date);			
					  $etcassembly_date=trans_date($etcassembly_date);						  
					  
					  $order_date1=trans_date($order_date1);					   
					  $order_date2=trans_date($order_date2);					   
					  $order_date3=trans_date($order_date3);					   
					  $order_date4=trans_date($order_date4);					   
					  $order_input_date1=trans_date($order_input_date1);					   
					  $order_input_date2=trans_date($order_input_date2);					   
					  $order_input_date3=trans_date($order_input_date3);					   
					  $order_input_date4=trans_date($order_input_date4);				

		   if((int)$bon_su>0) {
				$eunsung_laser_date =iconv_substr($eunsung_laser_date,5,5,"utf-8");
				$mainbending_date =iconv_substr($mainbending_date,5,5,"utf-8");
				$mainwelding_date =iconv_substr($mainwelding_date,5,5,"utf-8");
				$mainpainting_date =iconv_substr($mainpainting_date,5,5,"utf-8");
				$mainassembly_date =iconv_substr($mainassembly_date,5,5,"utf-8");		
						}
			  else
			  {
				$eunsung_laser_date ="X";
				$mainbending_date = "X";
				$mainwelding_date = "X";
				$mainpainting_date ="X";
				$mainassembly_date ="X";
			  }


		   if((int)$lc_su>0 && $type!='011' && $type!='012' && $type!='013D' && $type!='025' && $type!='017' && $type!='014'  && $type!='037'  && $type!='038'  ) {
				$lclaser_date =iconv_substr($lclaser_date,5,5,"utf-8");
				$lcbending_date =iconv_substr($lcbending_date,5,5,"utf-8");
				$lcwelding_date =iconv_substr($lcwelding_date,5,5,"utf-8");
				$lcpainting_date =iconv_substr($lcpainting_date,5,5,"utf-8");
				$lcassembly_date =iconv_substr($lcassembly_date,5,5,"utf-8");		
						}
			  else
			  {
				$lclaser_date ="X";
				$lcbending_date = "X";
				$lcwelding_date = "X";
				$lcpainting_date ="X";
				$lcassembly_date ="X";
			  }
					  
			  if((int)$etc_su>0) {
				$etclaser_date =iconv_substr($etclaser_date,5,5,"utf-8");
				$etcwelding_date =iconv_substr($etcwelding_date,5,5,"utf-8");
				$etcpainting_date =iconv_substr($etcpainting_date,5,5,"utf-8");
				$etcassembly_date =iconv_substr($etcassembly_date,5,5,"utf-8");		
				$etcbending_date =iconv_substr($etcbending_date,5,5,"utf-8");		
			}
			  else
			  {
				$etclaser_date ="X";
				$etcwelding_date = "X";
				$etcpainting_date = "X";
				$etcassembly_date ="X";
				$etcbending_date ="X";
			  }


									  
		 if($orderday!="") {
			$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
			$orderday =iconv_substr($orderday,5,5,"utf-8") . $week[ date('w',  strtotime($orderday)  ) ] ;
		 } 

		if($deadline!="") {
			$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
			$deadlineStr = iconv_substr($deadline,5,5,"utf-8") .$week[ date('w',  strtotime($deadline)  ) ] ;
			
		}		  
			  $workplacename = "[".$secondord ."]". $workplacename;

		// 사진등록 여부 확인			  
		$sqltmp=" select * from mirae8440.picuploads where item='ceilingwrap' AND parentnum ='$num'";	
		$tmpmsg = "";
		 try{  
		// 레코드 전체 sql 설정
		   $stmhtmp = $pdo->query($sqltmp);    
		   
		   while($rowtmp = $stmhtmp->fetch(PDO::FETCH_ASSOC)) {
					$tmpmsg = '<i class="bi bi-images"></i>' ;
				}		 
		   } catch (PDOException $Exception) {
			print "오류: ".$Exception->getMessage(); 
		}  		

	// str_replace 함수를 사용하여 '포장'이라는 단어를 빈 문자열로 대체합니다.
	$boxwrap = str_replace('포장', '', $boxwrap);
	
	// 설계처리여부 판단
		// 함수: 특정 조건에서 날짜 추출 또는 "X" 반환

		// 초기화 및 타입 배열
		$typesForX = ['011', '012', '013D', '025', '017', '014', '037', '038'];

		// 데이터 처리
		$main_draw_arr = processDate($main_draw, $bon_su, [], '');
		$lc_draw_arr = processDate($lc_draw, $lc_su, $typesForX, $type);
		$etc_draw_arr = processDate($etc_draw, $etc_su, [], '');
		$mainassembly_arr = processDate($mainassembly_date, $bon_su, [], '');
		$lcassembly_arr = processDate($lcassembly_date, $lc_su, $typesForX, $type);
		$etcassembly_arr = processDate($etcassembly_date, $etc_su, [], '');		

		// 기타는 아래의 컬럼이 있다.
		// etclaser_date
		// etcbending_date
		// etcwelding_date
		// etcpainting_date
		// etcassembly_date
	 ?>
			  
	<tr onclick="navigateToLink(event, 'view.php?num=<?=$num?>')">	
		<td class="text-center" data-order="<?=$deadline?>"><?= $deadlineStr ?></td> 
		<td class="text-center"><?=$car_insize?></td>					
		<td class="text-center  text-danger"><?=$boxwrap?></td>		
		<td>
			<?php
				$display_workplacename = (mb_strlen($workplacename, 'UTF-8') > 15) ? mb_substr($workplacename, 0, 15, 'UTF-8') . '..' : $workplacename;
			?>
			<span title="<?=htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8')?>"><?=$display_workplacename?></span>
		</td>
		<td class="text-center"><?=$type?> </td>		
		<td class="text-center"><?=$su?> </td>		
		<?php
			$display_memo = (mb_strlen($memo, 'UTF-8') > 10) ? mb_substr($memo, 0, 10, 'UTF-8') . '..' : $memo;
		?>
		<td class="text-start">
			<span title="<?=htmlspecialchars($memo, ENT_QUOTES, 'UTF-8')?>"><?=$display_memo?></span>
		</td>
		<?php
			$main_draw_display = empty($main_draw_arr) ? '<span class="badge bg-warning"> 설NO </span>' : $main_draw_arr;
			$lc_draw_display = empty($lc_draw_arr) ? '<span class="badge bg-warning">  설NO </span>' : $lc_draw_arr;
			$etc_draw_display = empty($etc_draw_arr) ? '<span class="badge bg-warning">  설NO </span>' : $etc_draw_arr;
		?>
		<td class="text-center" data-order="<?= $main_draw_arr ?>"><?= $main_draw_display ?>/<?= $lc_draw_display ?>/<?= $etc_draw_display ?></td>		
		<td class="text-center text-success">
			<?php if (!empty($outsourcing)) : ?>
				<div class="outsourcing-tooltip">
					<span class="badge bg-success"><?= $outsourcing ?></span>
					<?php if (!empty($outsourcing_memo)) : ?>
						<div class="tooltip-content">
							<div class="tooltip-title">외주가공 메모</div>
							<div class="tooltip-memo"><?= htmlspecialchars($outsourcing_memo, ENT_QUOTES, 'UTF-8') ?></div>
						</div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<?= $outsourcing ?>
			<?php endif; ?>
		</td>
		<?php		
			$mainlaser_display = empty($eunsung_laser_date) ? '<span class="badge bg-danger"> NO </span>' : $eunsung_laser_date;
			$mainwelding_display = empty($mainwelding_date) ? '<span class="badge bg-danger"> NO </span>' : $mainwelding_date;
			$mainpainting_display = empty($mainpainting_date) ? '<span class="badge bg-danger"> NO </span>' : $mainpainting_date;
			$mainassembly_display = empty($mainassembly_date) ? '<span class="badge bg-danger"> NO </span>' : $mainassembly_date;						
			
			$lclaser_display = empty($lclaser_date) ? '<span class="badge bg-danger"> NO </span>' : $lclaser_date;
			$lcwelding_display = empty($lcwelding_date) ? '<span class="badge bg-danger"> NO </span>' : $lcwelding_date;
			$lcpainting_display = empty($lcpainting_date) ? '<span class="badge bg-danger"> NO </span>' : $lcpainting_date;
			$lccabledone_display = empty($cabledone) ? '<span class="badge bg-danger"> NO </span>' : $cabledone;
			$lcassembly_display = empty($lcassembly_date) ? '<span class="badge bg-danger"> NO </span>' : $lcassembly_date;

			$etclaser_display = empty($etclaser_date) ? '<span class="badge bg-danger"> NO </span>' : $etclaser_date;
			$etcbending_display = empty($etcbending_date) ? '<span class="badge bg-danger"> NO </span>' : $etcbending_date;
			$etcwelding_display = empty($etcwelding_date) ? '<span class="badge bg-danger"> NO </span>' : $etcwelding_date;
			$etcpainting_display = empty($etcpainting_date) ? '<span class="badge bg-danger"> NO </span>' : $etcpainting_date;
			$etcassembly_display = empty($etcassembly_date) ? '<span class="badge bg-danger"> NO </span>' : $etcassembly_date;
			
		?>
		
		<td class="text-center text-primary"><?= $mainlaser_display ?></td>
		<td class="text-center text-primary"><?= $mainwelding_display ?></td>
		<td class="text-center text-primary"><?= $mainpainting_display ?></td>
		<td class="text-center text-primary"><?= $mainassembly_display ?></td>

		<?php
			// etc 값이 있는지 확인
			// echo $etc_laser_date;
			// echo $etc_welding_date;
			// echo $etc_painting_date;
			// echo $etcassembly_date;
			// 0000-00-00, null, '' 모두 없는 것으로 처리
			// X는 값이 없는 것이므로, X가 아닌 값이 하나라도 있으면 true
			// LC와 ETC 각각 X가 아닌 값이 있는지 검사
			$has_lc_values = (
				is_valid_etc_value($lclaser_date) ||
				is_valid_etc_value($lcbending_date) ||
				is_valid_etc_value($lcwelding_date) ||
				is_valid_etc_value($lcpainting_date) ||
				is_valid_etc_value($lcassembly_date)
			);

			$has_etc_values = (
				is_valid_etc_value($etclaser_date) ||
				is_valid_etc_value($etcbending_date) ||
				is_valid_etc_value($etcwelding_date) ||
				is_valid_etc_value($etcpainting_date) ||
				is_valid_etc_value($etcassembly_date)
			);

			// LC와 ETC 값을 결합하여 표시 (둘 다 값이 있으면 <br>로 두 줄, 하나만 있으면 한 줄)
			$lclaser_combined = '';
			$lcwelding_combined = '';
			$lcpainting_combined = '';
			$lccabledone_combined = '';
			$lcassembly_combined = '';

			// 레이저
			if ($has_lc_values && $has_etc_values) {
				$lclaser_combined = $lclaser_display . '<br>' . $etclaser_display;
				$lcwelding_combined = $lcwelding_display . '<br>' . $etcbending_display;
				$lcpainting_combined = $lcpainting_display . '<br>' . $etcwelding_display;
				$lccabledone_combined = $lccabledone_display . '<br>' . $etcpainting_display;
				$lcassembly_combined = $lcassembly_display . '<br>' . $etcassembly_display;
			} elseif ($has_lc_values) {
				$lclaser_combined = $lclaser_display;
				$lcwelding_combined = $lcwelding_display;
				$lcpainting_combined = $lcpainting_display;
				$lccabledone_combined = $lccabledone_display;
				$lcassembly_combined = $lcassembly_display;
			} elseif ($has_etc_values) {
				$lclaser_combined = $etclaser_display;
				$lcwelding_combined = $etcwelding_display;
				$lcpainting_combined = $etcpainting_display;
				$lccabledone_combined = ''; // etc cable 관련 변수 없음
				$lcassembly_combined = $etcassembly_display;
			} else {
				// 둘 다 값이 없으면 원래 LC 값(기존대로)
				$lclaser_combined = $lclaser_display;
				$lcwelding_combined = $lcwelding_display;
				$lcpainting_combined = $lcpainting_display;
				$lccabledone_combined = $lccabledone_display;
				$lcassembly_combined = $lcassembly_display;
			}
		?>
		<td class="text-center text-success"><?= $lclaser_combined ?> </td>
		<td class="text-center text-success"><?= $lcwelding_combined ?></td>
		<td class="text-center text-success"><?= $lcpainting_combined ?></td>
		<td class="text-center text-success"><?= $lccabledone_combined ?></td>
		<td class="text-center text-success"><?= $lcassembly_combined ?></td>
						
		<td    class="text-center"><?=$tmpmsg?></td>	
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
	   
</div> <!-- end of container -->

</form>

</body>
</html>
<script>    

var dataTable; // DataTables 인스턴스 전역 변수
var mceilingpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 200,
        "lengthMenu": [200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'asc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('mceilingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var mceilingpageNumber = dataTable.page.info().page + 1;
        setCookie('mceilingpageNumber', mceilingpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('mceilingpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('mceilingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function navigateToLink(event, url) {
    // 이벤트의 대상이 <a> 태그가 아닐 경우에만 링크를 따라갑니다.
    if (event.target.tagName !== 'A') {        
		customPopup(url, '세부 내역', 1400, 900); 			
		
    }
}

function process_list(){   // 접수일 발주일 라디오버튼 클릭시
	// document.getElementById('search').value=null; 
	document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  
} 

function show_list(insu){   
	var insu;
	document.getElementById('search').value=null; 
	document.getElementById('cursort').value=insu; 
	document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  
}    

function SearchEnter(event) {
    if (event.keyCode == 13) {		
        document.getElementById('board_form').submit();
    }
}

// 외주 툴팁 관련 JavaScript
$(document).ready(function() {
    // 툴팁이 화면 밖으로 나가지 않도록 조정
    $('.outsourcing-tooltip').each(function() {
        $(this).on('mouseenter', function() {
            const tooltip = $(this).find('.tooltip-content');
            const tooltipRect = tooltip[0].getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            
            // 화면 오른쪽으로 나가는 경우 위치 조정
            if (tooltipRect.right > viewportWidth) {
                tooltip.css('left', 'auto').css('right', '0').css('margin-left', '0');
            } else {
                tooltip.css('left', '50%').css('right', 'auto').css('margin-left', '-160px');
            }
        });
    });
});
</script>