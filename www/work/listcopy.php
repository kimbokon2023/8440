 <!-- Work List -->
<?php
 session_start();
 
   // 첫 화면 표시 문구
 $title_message = 'jamb 수주현황';
 
 
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header ("Expires: 0"); // rfc2616 - Section 14.21   

//header("Refresh:0");  // reload refresh   

ini_set('display_errors','0');  // 화면에 warning 없애기	

// null 이 아니면 값을 echo해주기
function echo_null($str) {
	
	$strval = ($str == "") ? "&nbsp;&nbsp;&nbsp;" : $str ;

	return $strval;	
	
}
 ?>
 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
<title>  <?=$title_message?>  </title>
</head>
 
<style>
 @charset "utf-8";

.typing-txt{display: none;}
.typeing-txt ul{list-style:none;}
.typing {  
  display: inline-block; 
  animation-name: cursor; 
  animation-duration: 0.3s; 
  animation-iteration-count: infinite; 
} 
@keyframes cursor{ 
  0%{border-right: 1px solid #fff} 
  50%{border-right: 1px solid #000} 
  100%{border-right: 1px solid #fff} 
}

a {
	text-decoration:none;
	color:gray;
}

  .table-hover tbody tr:hover {
    background-color: #000000; /* 원하는 배경색으로 변경하세요 */
	 cursor: pointer; /* 원하는 커서 스타일로 변경하세요 */
  }


  .custom-table-striped tbody tr:nth-of-type(odd) {
    background-color: #f5f5f5; /* 홀수 줄 배경색 변경 */
  }
  .custom-table-striped tbody tr:nth-of-type(even) {
    background-color: #fsfsfs; /* 짝수 줄 배경색 변경 */
  }
    #autocomplete-list {
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        position: absolute;
        top: 100%;
        left: 45%;
        right: 30%;
		width : 25%;
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

</style>

 <?php

include "request.php"; 
	 
$Twosearchword = array();	 
	 
if(strpos($search,',') !== false){	 
  $Twosearchword = explode(',',$search);	   
}
	 
	 
 if($sortof!='0')
    {

	if($sortof==1 and $stable==0) {      //접수일 클릭되었을때
		
	 if($cursort!=1)
	    $cursort=1;
      else
	     $cursort=2;
	    } 
	if($sortof==2 and $stable==0) {     //착공일 클릭되었을때
		
	 if($cursort!=3)
	    $cursort=3;
      else
		 $cursort=4;			
	   }	   
	if($sortof==3 and $stable==0) {     //실측일 클릭되었을때
		
	 if($cursort!=5)
	    $cursort=5;
      else
		 $cursort=6;			
	   }	   	   
	if($sortof==4 and $stable==0) {     //예정일 클릭되었을때
		
	 if($cursort!=7)
	    $cursort=7;
      else
		 $cursort=8;			
	   }	   
	if($sortof==5 and $stable==0) {     //출고일 클릭되었을때
		
	 if($cursort!=9)
	    $cursort=9;
      else
		 $cursort=10;			
	   }		   
	if($sortof==6 and $stable==0) {     //청구 클릭되었을때
		
	 if($cursort!=11)
	    $cursort=11;
      else
		 $cursort=12;			
	   }		   
	if($sortof==7 and $stable==0) {     //검사일 클릭되었을때
		
	 if($cursort!=13)
	    $cursort=13;
      else
		 $cursort=14;			
	   }		   
	}	   
    
  $sum=array();
 
  $page_scale = 20;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	    
   if(isset($_REQUEST["findstr"]))   
   $findstr=$_REQUEST["findstr"];
      else
          $findstr="덧방";  // 초기설정을 덧방으로 해놓는다.
	  
      
   if(isset($_REQUEST["company1"]))   
   $company1=$_REQUEST["company1"];
      
   if(isset($_REQUEST["company2"]))   
   $company2=$_REQUEST["company2"];
         
   if(isset($_REQUEST["workersel"]))   
   $workersel=$_REQUEST["workersel"];
            
  	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
   
switch($cursort)
{
   case 1 :
   $orderby="order by orderday desc, num desc  ";
   break;   
   case 2 :
   $orderby="order by orderday asc, num desc  ";
   break;      
   case 3 :
   $orderby="order by doneday desc, num desc  ";
   break;   
   case 4 :
   $orderby="order by doneday asc, num desc  ";
   break;      	
   case 5 :
   $orderby="order by measureday desc, num desc  ";
   break;   
   case 6 :
   $orderby="order by measureday asc, num desc  ";
   break;      		
   case 7:
   $orderby="order by endworkday desc, num desc  ";
   break;   
   case 8 :
   $orderby="order by endworkday asc, num desc  ";
   break;
   case 9 :
   $orderby="order by workday desc, num desc  ";
   break;   
   case 10:
   $orderby="order by workday asc, num desc  ";
   break;    
   case 11 :
   $orderby="order by demand desc, num desc";
   break;   
   case 12:
   $orderby="order by demand asc, num desc";
   break;     
   case 13 :
   $orderby="order by testday asc, num desc  ";  // 검사일
   break;   
   case 14:
   $orderby="order by testday desc, num desc  "; // 검사일
   break;     
default:
   $orderby="order by orderday desc, num desc ";
break;
}

if ($checkoutsourcing=='1')    // 외주가공 체크된 경우
	{
			$attached=" and (outsourcing!='') ";
			$whereattached=" where outsourcing!='' ";
	}

if ($check=='1')  // 미출고 리스트 체크된 경우
{
		$attached=" and (((workday='') or (workday='0000-00-00')) and checkhold IS NULL ) ";  // 보류는 제외
		// $whereattached=" where workday='0000-00-00' and checkhold!='보류' ";
		$whereattached=" where workday='0000-00-00' and checkhold IS NULL ";
}

	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분

if ($plan_output_check=='1')  // 생산예정이 체크된 경우
	{
			$attached=" and (date(endworkday)>=date(now()))  ";
			$whereattached=" where date(endworkday)>=date(now()) ";
			$orderby="order by endworkday asc ";				
	}

	
if ($output_check=='1')  // 출고완료 체크된 경우
{
		$attached=" and ((workday!='') and (workday!='0000-00-00')) ";
		$whereattached=" where workday!='' ";
}	
	
if ($output_check=='1' && $cursort=='12'  && $buttonval=='10'  )  // 출고완료 미청구 list 정렬
{
		$attached=" and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))    ";
		$whereattached=" where workday!='' and demand='' ";				
		$orderby="order by workday desc ";		
}

if ($team_check=='1')  // 시공팀미지정 체크된 경우
		{
				$attached=" and (worker='') ";
				$whereattached=" where worker='' ";
		}

if ($measure_check=='1')  // 미실측리스트 체크된 경우
		{
				$attached=" and (measureday='') ";
				$whereattached=" where measureday='' ";
		}	
	 
$a= " " . $orderby . " limit $first_num, $scale";  
$b=  " " . $orderby;
  
// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);

// function buildSQL($base, $conditions, $suffix = '') {
    // $whereClause = implode(' OR ', $conditions);
    // if ($whereClause) {
        // $whereClause = ' WHERE ' . $whereClause;
    // }
    // return $base . $whereClause . $suffix;
// }

// $searchConditions = [];
// if ($search != "") {
    // $fields = [
        // 'workplacename', 'firstordman', 'firstordmantel', 'secondordman', 'secondordmantel',
        // 'chargedman','chargedmantel', 'delicompany', 'attachment', 'hpi', 'firstord', 'secondord',
        // 'worker', 'memo', 'material1', 'material2', 'material3', 'material4', 'material5', 'material6'
    // ];
    
    // foreach ($fields as $field) {
        // $searchConditions[] = "(replace($field, ' ', '') LIKE '%$search%')";
    // }
// }

// if ($findstr !== "전체") {
    // $searchstr = ($findstr == '덧방') ? '' : '신규';
    // $searchConditions[] = "(checkstep = '$searchstr')";
// }

// if($check=='1' || $output_check=='1' || $measure_check=='1' || $plan_output_check=='1' || $team_check=='1') {			  
      // $sql ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordmantel like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )  or (chargedmantel like '%$search%' ) ";
      // $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $a;

      // $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordmantel like '%$search%' )   or (firstordman like '%$search%' )  or (secondordmantel like '%$search%' )   or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) or (chargedmantel like '%$search%' ) ";
      // $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $b;
  // }	

// $sqlBase = "SELECT * FROM mirae8440.work";
// $sql = buildSQL($sqlBase, $searchConditions, $a);
// $sqlcon = buildSQL($sqlBase, $searchConditions, $b);


	  if($search=="" && $findstr==="전체")
		  {
			 $sql="select * from mirae8440.work " . $whereattached . $a; 					
			 $sqlcon = "select * from mirae8440.work "  . $whereattached .  $b;   // 전체 레코드수를 파악하기 위함.
		   }
		 elseif($search!="" && $find!="all" && $findstr==="전체")
			{
				$sql="select * from mirae8440.work where ($find like '%$search%') " . $attached . $a;
				$sqlcon="select * from mirae8440.work where ($find like '%$search%')  "  . $attached . $b;
			 }     				 
		 elseif($findstr!=="전체") 
		     {
				// 공사유형이 전체가 아닐때
				// 검색어가 2개일경우
		    if($findstr=='덧방')
				   $searchstr = '';
			   else
				   $searchstr = '신규';
			   
			if(count($Twosearchword)>1)
			   {				   
					  if($check!='1') 
						  $attached = '';
					  
						  $search1 = $Twosearchword[0];
						  $sql ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )   or  (firstordmantel like '%$search1%' )  or (secondordman like '%$search1%' )   or (secondordmantel like '%$search1%' )  or (chargedman like '%$search1%' )  or (chargedmantel like '%$search1%' )  ";
						  $sql .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  and ( checkstep='$searchstr' )  " ;
						  
						  $search2 = $Twosearchword[1];
						  $sql .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )  or (firstordmantel like '%$search2%' )   or (secondordman like '%$search2%' )  or (secondordmantel like '%$search2%' )   or (chargedman like '%$search2%' ) or (chargedmantel like '%$search2%' ) ";
						  $sql .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))   and ( checkstep='$searchstr' )  " . $attached . $a;
						  
						  $sqlcon ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )   or  (firstordmantel like '%$search1%' )  or (secondordman like '%$search1%' )  or (secondordmantel like '%$search1%' )  or (chargedman like '%$search1%' )  or (chargedmantel like '%$search1%' ) ";
						  $sqlcon .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))   and ( checkstep='$searchstr' )  " ;							  
						  
						  $sqlcon .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )   or (secondordman like '%$search2%' )  or (secondordmantel like '%$search2%' )   or (chargedman like '%$search2%' ) or (chargedmantel like '%$search2%' )  ";
						  $sqlcon .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))   and ( checkstep='$searchstr' )  " . $attached . $b;						  
					 
	   
				}   // end of Twosearchword searching
			  else {   // end of one word
					  if($check!='1') {		 
						  $sql ="select * from mirae8440.work where ((replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (firstordmantel like '%$search%' )  or (secondordman like '%$search%' )   or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )   or (chargedmantel like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' ))   and ( checkstep='$searchstr' )   " . $a;
						  
						  $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','') like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )   or (chargedmantel like '%$search%' )  ";
						  $sqlcon .="or (delicompany like '%$search%' )  or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' ))   and ( checkstep='$searchstr' )   " . $b;
					  }
					  
				 if($checkoutsourcing=='1' || $check=='1' || $output_check=='1' || $measure_check=='1' || $plan_output_check=='1' || $team_check=='1') {			  
						  $sql ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )   or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )   or (chargedmantel like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstordman like '%$search%' )  or (firstordmantel like '%$search%' )  or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  )   and ( checkstep='$searchstr' ) "  . $attached . $a;
						  
						  $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordman like '%$search%' ) or (firstordmantel like '%$search%' )  or (secondordmantel like '%$search%' )   or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' )  or (chargedmantel like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  )   and ( checkstep='$searchstr' ) "  . $attached . $b;
					  }				  
	   
				}   // end of Twosearchword searching
			  }	  	
			  
		 elseif($search!="" && $find=="all") { // 필드별 검색하기
		   // 검색어가 2개일경우
			if(count($Twosearchword)>1)
			   {				   
					  if($check!='1') 
						  $attached = '';
					  
						  $search1 = $Twosearchword[0];
						  $sql ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )   or  (firstordmantel like '%$search1%' )  or (secondordman like '%$search1%' )  or (secondordmantel like '%$search1%' )  or (chargedman like '%$search1%' ) ";
						  $sql .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  " ;
						  $search2 = $Twosearchword[1];
						  $sql .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' ) or (firstordmantel like '%$search2%' )   or (secondordman like '%$search2%' )  or (secondordmantel like '%$search2%' )   or (chargedman like '%$search2%' ) or (chargedmantel like '%$search2%' )";
						  $sql .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))  " . $attached . $a;
						  
						  $sqlcon ="select * from mirae8440.work where ( (replace(workplacename,' ','') like '%$search1%' ) or (firstordman like '%$search1%' )   or  (firstordmantel like '%$search1%' )  or (secondordman like '%$search1%' )  or (chargedman like '%$search1%' ) ";
						  $sqlcon .="or (delicompany like '%$search1%' ) or (attachment like '%$search1%' )  or (hpi like '%$search1%' ) or (firstord like '%$search1%' ) or (secondord like '%$search1%' ) or (worker like '%$search1%' ) or (memo like '%$search1%' ) or (material1 like '%$search1%' ) or (material2 like '%$search1%' ) or (material3 like '%$search1%' ) or (material4 like '%$search1%' ) or (material5 like '%$search1%' ) or (material6 like '%$search1%' ))  " ;							  
						  $sqlcon .= " and ( (replace(workplacename,' ','') like '%$search2%' ) or (firstordman like '%$search2%' )   or (secondordman like '%$search2%' )  or (secondordmantel like '%$search2%' )   or (chargedman like '%$search2%' ) or (chargedmantel like '%$search2%' )";
						  $sqlcon .= "or (delicompany like '%$search2%' ) or (attachment like '%$search2%' )  or (hpi like '%$search2%' ) or (firstord like '%$search2%' ) or (secondord like '%$search2%' ) or (worker like '%$search2%' ) or (memo like '%$search2%' ) or (material1 like '%$search2%' ) or (material2 like '%$search2%' ) or (material3 like '%$search2%' ) or (material4 like '%$search2%' ) or (material5 like '%$search2%' ) or (material6 like '%$search2%' ))  " . $attached . $b;						  					 	   
				}   // end of Twosearchword searching
			  else {   // end of one word
					  if($check!='1') {		 
						  $sql ="select * from mirae8440.work where (replace(workplacename,' ','') like '%$search%' ) or (firstordmantel like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )  or (chargedmantel like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  " . $a;
						  
						  $sqlcon ="select * from mirae8440.work where (replace(workplacename,' ','') like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' ) or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )   or (chargedmantel like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' )  or (attachment like '%$search%' )  or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  " . $b;
					  }
					  
				 if($checkoutsourcing=='1' || $check=='1' || $output_check=='1' || $measure_check=='1' || $plan_output_check=='1' || $team_check=='1') {			  
						  $sql ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordmantel like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (secondordmantel like '%$search%' )  or (chargedman like '%$search%' )  or (chargedmantel like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $a;
						  
						  $sqlcon ="select * from mirae8440.work where ((replace(workplacename,' ','')  like '%$search%' )  or (firstordmantel like '%$search%' )   or (firstordman like '%$search%' )  or (secondordmantel like '%$search%' )   or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) or (chargedmantel like '%$search%' ) ";
						  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (material6 like '%$search%' )  ) "  . $attached . $b;
					  }				  
	   
				}   // end of Twosearchword searching
			  }					
 // // print 'search : ' . $search . '<br>' ;   
 // // print $sql;   

   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	 $total_row = $temp2;     // 전체 글수	  		 					 
	 $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 	  
			 
?>
		 
<body>

<? include '../myheader.php'; ?>   

<?  include '../work_voc/load_workvoc.php'; ?>   
		
<form id="board_form" name="board_form" method="post" action="listcopy.php?mode=search">  

<div class="container-fluid">  	
	
				<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>"   > 	
				<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>"   > 	
				<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"   > 	
				<input type="hidden" id="page" name="page" value="<?=$page?>"   > 	
				<input type="hidden" id="scale" name="scale" value="<?=$scale?>"   > 	
				<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>"   > 	
				<input type="hidden" id="year" name="year" value="<?=$year?>"   > 	
				
				<input type="hidden" id="check" name="check" value="<?=$check?>"   > 	
				<input type="hidden" id="checkoutsourcing" name="checkoutsourcing" value="<?=$checkoutsourcing?>"   > 	
				<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>"   > 	
				<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>"   > 	
				<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>"   > 	
				<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>"   > 	
				<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>"   > 	
				<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>"   > 	
				<input type="hidden" id="stable" name="stable" value="<?=$stable?>"   > 	
				<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
				<input type="hidden" id="list" name="list" value="<?=$list?>" > 								
				<input type="hidden" id="buttonval" name="buttonval" value="<?=$buttonval?>" > 								
				<input type="hidden" id="findstr" name="findstr" value="<?=$findstr?>" > 								
				
                <div id="vacancy" style="display:none">  </div>	
				
	<div class="card mt-1 mb-1">  
	<div class="card-body">  	 				

	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center "> 

		<a href="listcopy.php">   <h4>  <?=$title_message?> </h4> </a>	 &nbsp;&nbsp;&nbsp;
		   <div id="dis_text" class="input-group-text text-primary" title="301:멍텅구리, 311(테둘림유 막판),310(테둘림무 막판)" > </div>&nbsp; &nbsp; 
			  &nbsp;&nbsp;&nbsp;
	 <span class="text-primary fs-5" > 공사구분 :  </span> &nbsp;
		   <select name="checkwork" id="checkwork" class="bg-primary text-white">
			   <?php		 
			   
			   $worktype_arr = array();
			   array_push($worktype_arr,'전체','덧방','신규');

				for($i=0;$i<count($worktype_arr);$i++) {
					 if($findstr==$worktype_arr[$i])
								print "<option selected value='" . $worktype_arr[$i] . "'> " . $worktype_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $worktype_arr[$i] . "'> " . $worktype_arr[$i] .   "</option>";
				} 		   
			   

					?>	  
			</select>
			 &nbsp;&nbsp;
	 <span style="color:grey;"> 원청 :  </span> &nbsp;
		   <select name="company1" id="company1" >
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
			<span style="color:grey;"> 발주처 :  </span> &nbsp;
			<select name="company2" id="company2" >
			   <?php		 
			   
			   $com1_arr = array();
			   array_push($com1_arr,' ','한산','우성','제일특수강');

				for($i=0;$i<count($com1_arr);$i++) {
					 if($company2==$com1_arr[$i])
								print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
				} 		   
			   

					?>	  
			</select>
		  &nbsp;&nbsp;
					<span style="color:grey;"> 작업소장 :  </span> &nbsp;
			<select name="workersel" id="workersel" >
			   <?php		 
			   
			   $com1_arr = array();
			   array_push($com1_arr,' ','추영덕','이만희','김운호','김상훈','유영','손상민','조장우','박철우', '이인종');

				for($i=0;$i<count($com1_arr);$i++) {
					 if($workersel==$com1_arr[$i])
								print "<option selected value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $com1_arr[$i] . "'> " . $com1_arr[$i] .   "</option>";
				} 		   
			   

					?>	  
			</select>
		   &nbsp;&nbsp;	 		 

	</div>

	<div class="d-flex mt-2 mb-1 justify-content-center align-items-center "> 
			자료 ▷  <?= $total_row ?>     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			<?php
				function printCheckbox($id, $value, $label, $checkedValue) {
					$isChecked = ($value == '1') ? "checked" : "";
					if($label==='외주가공')
						  echo "<input type='checkbox' $isChecked id=$id value='1'>&nbsp; <span class='badge bg-success '> $label </span> &nbsp;&nbsp;";
						else
						echo "<input type='checkbox' $isChecked id=$id value='1'> $label &nbsp;&nbsp;";
				}

				printCheckbox('outsourcingCheckbox', $checkoutsourcing, '외주가공', '1');
				printCheckbox('without', $check, '미출고', '1');
				printCheckbox('plan_outputlist', $plan_output_check, '생산예정', '1');
				printCheckbox('outputlist', $output_check, '출고완료', '1');
				printCheckbox('team', $team_check, '시공팀미지정', '1');
				printCheckbox('notmeasure', $measure_check, '미실측', '1');
			?>
				
	&nbsp; 

	 <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="window.open('test_listcopy.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&list=1&sortof=6&cursort=<?=$cursort?>&stable=0&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>$plan_output_check=<?=$plan_output_check?>&check=<?=$check?>','검사일기준 미실측DB 추출','left=100,top=100, scrollbars=yes, toolbars=no,width=1750,height=800');"> 검사일 DB </button>  &nbsp;
	 <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="window.open('No_demandlistcopy.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&list=1&sortof=6&cursort=<?=$cursort?>&stable=0&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>$plan_output_check=<?=$plan_output_check?>&check=<?=$check?>','출고완료분 중 미청구DB 추출','left=100,top=100, scrollbars=yes, toolbars=no,width=1600,height=800');"> 출고완료 미청구 </button> &nbsp;
	 


	<button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.open('delivery_fee.php?mode=search&search=&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&list=1&sortof=6&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>$plan_output_check=<?=$plan_output_check?>&check=<?=$check?>','배송비추출','left=0,top=50, scrollbars=yes, toolbars=no,width=1900,height=850');"> 배송비 </button> &nbsp;
	<?
	 if($user_name=='김보곤' or $user_name=='이미래'  or $user_name=='소현철'    or  $user_name=='이경묵'   or  $user_name=='조경임'  or  $user_name=='김은비')
	   {
	?>	 
	 <button type="button" class="btn btn-outline-secondary  btn-sm" onclick="window.open('batch.php?mode=search&search=&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&list=1&sortof=6&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>$plan_output_check=<?=$plan_output_check?>&check=<?=$check?>','시공소장 결산자료','left=0,top=30, scrollbars=yes, toolbars=no,width=1910,height=900');"> 일괄처리 </button>&nbsp;
	 <button type="button" id="choiceworkerBtn" class="btn btn-success btn-sm" > 소장화면 </button>&nbsp;
	 <?   }     ?> 
	 
			
	</div>  <!-- end of 1단계 list_search1  -->
		  
		  
	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center"> 
	   <span class="text-primary" > 목록개수 &nbsp; </span>
	   <select name="scaleval" id="scaleval" >
		   <?php		 
					
		   $scalearr = array();
		   array_push($scalearr,'100','200','300','400','500');
		   
		   for($i=0; $i<count($scalearr); $i++) {
					 if($scale==$scalearr[$i])
								print "<option selected value='" . $$scalearr[$i] . "'> " . $scalearr[$i] .   "</option>";
						 else   
				   print "<option value='" . $scalearr[$i] . "'> " . $scalearr[$i] .   "</option>";
			   } 		   
			   

					?>	  
			</select> 
	 &nbsp;&nbsp;
	 
	 <button type="button" id="outputBtn" class="btn btn-outline-danger  btn-sm"> 출고완료 미청구 list 정렬 </button> &nbsp;
	 <button type="button" class="btn btn-secondary  btn-sm" onclick="window.open('demandit.php','시공완료일 미입력 / 사진 미등록','left=10,top=10, scrollbars=yes, toolbars=no,width=1890,height=1060');"> 시공완료 미입력/사진 미등록 </button> &nbsp;&nbsp;&nbsp;&nbsp;
	 
		<select name="find" id="find" >
			   <?php		  
				  if($find=="")
				  {			?>	  
			   <option value='all'>전체</option>
			   <option value='workplacename'>현장명</option>
			   <option value='firstord'>원청</option>
			   <option value='secondord'>발주처</option>
			   <option value='worker' >미래시공팀</option>		   		   
			   <option value='designer' >설계자</option>		   		   

			   <?php
				  } ?>		
			  <?php		  
				  if($find=="all")
				  {			?>	  
			   <option value='all' selected>전체</option>
			   <option value='workplacename'>현장명</option>
			   <option value='firstord'>원청</option>
			   <option value='secondord'>발주처</option>
			   <option value='worker' >미래시공팀</option>	
				<option value='designer' >설계자</option>			   

			   <?php
				  } ?>
			  <?php
				  if($find=="workplacename")
				  {			?>	  
			   <option value='all' >전체</option>
			   <option value='workplacename' selected>현장명</option>
			   <option value='firstord'>원청</option>
			   <option value='secondord'>발주처</option>
			   <option value='worker' >미래시공팀</option>	
				<option value='designer' >설계자</option>			   
			   <?php
				  } ?>			  
			  <?php
				  if($find=="firstord")
				  {			?>	  
			   <option value='all'>전체</option>
			   <option value='workplacename'>현장명</option>
			   <option value='firstord' selected>원청</option>
			   <option value='secondord'>발주처</option>
			   <option value='worker' >미래시공팀</option>		
				<option value='designer' >설계자</option>	   		   

			   <?php
				  } ?>			  
			  <?php
				  if($find=="secondord")
				  {			?>	  
			   <option value='all'>전체</option>
			   <option value='workplacename'>현장명</option>
			   <option value='firstord'>원청</option>
			   <option value='secondord' selected>발주처</option>
			   <option value='worker' >미래시공팀</option>		
				<option value='designer' >설계자</option>			   
			   
			   <?php
				  } ?>	  			  
					  
			  <?php
				  if($find=="worker")
				  {			?>	  
			   <option value='all'>전체</option>
			   <option value='workplacename'>현장명</option>
			   <option value='firstord'>원청</option>
			   <option value='secondord' >발주처</option>
			   <option value='worker' selected>미래시공팀</option>
				<option value='designer' >설계자</option>	
			   
			   <?php
				  } ?>	  		  		  
			  <?php
				  if($find=="designer")
				  {			?>	  
			   <option value='all'>전체</option>
			   <option value='workplacename'>현장명</option>
			   <option value='firstord'>원청</option>
			   <option value='secondord' >발주처</option>
			   <option value='worker' >미래시공팀</option>
				<option value='designer' selected>설계자</option>	
			   
			   <?php   }   ?>	  				  			  
					  
			</select>
				&nbsp;
			<?php
				?>
			<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  > &nbsp;			
			<div id="autocomplete-list"></div>			
			<button id="searchBtn" type="button" class="btn btn-dark  btn-sm"  > <ion-icon name="search"></ion-icon> </button> &nbsp;&nbsp;&nbsp;&nbsp;
						
		<?php
	   if(isset($_SESSION["userid"]))
	   {
	  ?> 
	  <button type="button" class="btn btn-dark  btn-sm" onclick="location.href='../work/write_form.php';" > 글쓰기  </button> &nbsp;
	   <button type="button" class="btn btn-secondary  btn-sm" onclick="window.open('plan_making.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','생산일정(생산완료제외) DB','left=50,top=50, scrollbars=yes, toolbars=no,width=1850,height=970');"> 생산일정 </button> &nbsp;
	  <?php
	   }
	  ?>    
	 
	 
	 <button type="button" class="btn btn-dark  btn-sm" onclick="window.open('registration.php','우성(OTIS)발주 일괄등록','left=10,top=10, scrollbars=yes, toolbars=no,width=1890,height=1060');"> 우성(OTIS)발주 일괄등록 </button> &nbsp;
		  
		  
		  </div> <!-- end of 2단계 list_search1  -->
   </div> <!--card-body-->
   </div> <!--card -->
			 
<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center"> 
    
  <table class="table table-hover ">
    <thead class="table-secondary">
      <tr>
        <th class="text-center" style="width:3%;" >구분</th>
        <th class="text-center" style="width:3%;" ><span class="badge bg-success">외주</span></th>
        <th class="text-center color-gray " style="width:5%;" ><a href="#" onclick="button_condition('1')">접수</a></th>
		<th class="text-center text-danger" style="width:3%;" ><a href="#" onclick="button_condition('7')"><span class="text-danger"> 검사 </span></a></th>
        <th class="text-center"  style="width:3%;" ><a href="#" onclick="button_condition('4')"><span class="text-success"> 예정 </span></a></th>        
        <th class="text-center" style="width:3%;" >설계</th>                
        <th class="text-center" style="width:3%;"><a href="#" onclick="button_condition('5')">출고</a></th>
        <th class="text-center" style="width:3%;"><a href="#" style="color:blue" onclick="button_condition('2')">시공</a></th>
        <th class="text-center"><img src="../img/beforework.gif"></th>
        <th class="text-center"><img src="../img/afterwork.gif"></th>
        <th class="text-center"><a href="#" onclick="button_condition('6')">청구</a></th>
        <th class="text-center">현장명</th>
        <th class="text-center">재질(소재) 사급-청색</th>
        <th class="text-center">원청</th>
        <th class="text-center">발주처</th>
        <th class="text-center" style="width:4%;">시공팀</th>
        <th class="text-center" style="width:5%;">설치수량</th>
        <th class="text-center">HPI</th>
        <th class="text-center">비고</th>
      </tr>
    </thead>
    <tbody>



		<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
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
			  
			  $dis_text = "총 " . $sum[3] . " (SET),  (막판 : " . $sum[0] . " ,  막판(無) : " . $sum[1] . " ,  쪽쟘 : "  . $sum[2] . " )" ; 			  

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
			 
			<style>			 
			a {
				text-decoration:none;
				color:gray;
			}
			
			tr {
				font-size : 13px;
			}
			th {
				font-size : 15px;
			}
			</style>
					
		 <tr onclick="window.location.href='view.php?num=<?=$num?>&page=<?=$page?>&scale=<?=$scale?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=<?=$stable?>&check=<?=$check?>&buttonval=<?=$buttonval?>'">
  <td>
    <?php
      if($checkstep == '신규') {
        print '<span class="btn btn-danger btn-sm">' . $checkstep . '</span>';
        $workplacename = "[신규] " . $workplacename;
      } else {
        print $checkstep;
      }
    ?>
  </td>
  <td class="text-center"><span class="badge bg-success"><?=$outsourcing?></span></td>
  <td class="text-center"><?=$orderday?></td>
  <td class="text-center text-danger"><?=echo_null(iconv_substr($testday, 5, 5, "utf-8"))?></td>
  <td class="text-center text-success"><?=echo_null(iconv_substr($endworkday, 5, 5, "utf-8"))?></td>  
  <td class="text-center "><?=echo_null($draw_done)?></td>  
  <td class="text-center"><?=echo_null(iconv_substr($workday, 5, 5, "utf-8"))?></td>
  <td class="text-center"><?=echo_null(iconv_substr($doneday, 5, 5, "utf-8"))?></td>
  <td class="text-center"><?=echo_null($filename1)?></td>
  <td class="text-center "><?=echo_null($filename2)?></td>
  <td class="text-center "><?=echo_null(iconv_substr($demand, 5, 5, "utf-8"))?></td>
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
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
 
    </tbody>
  </table>
</div>
  
 
  <div class="row row-cols-auto mt-4 justify-content-center align-items-center"> 
 <?php
	if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-outline-secondary btn-sm" type="button" id=previousListBtn  onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  ' . $i . '  </span>'; 
              else 
                   print '<button class="btn btn-outline-secondary btn-sm" type="button" id=moveListBtn onclick="javascript:movetoPage(' . $i . ')">' . $i . '</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-outline-secondary btn-sm" type="button" id=nextListBtn onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
            }
			
 
// print $search;
// print 'cursort ' . $cursort . '<br>';
// print 'sortof ' . $sortof . '<br>';
// print 'find ' . $find . '<br>';
// print 'sql ' . $sql;			
			
			
            ?>         
   </div>
	
	
    
<br>
<br>
<div class="container-fluid">
<? include '../footer_sub.php'; ?>
</div>

   </div> <!--container-->

   </form>
   
</body>
</html>
  
<script>

   document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const autocompleteList = document.getElementById('autocomplete-list');

        searchInput.addEventListener('input', function() {
            const val = this.value;
            if (!val) {
                autocompleteList.innerHTML = '';
                return;
            }
            
            let searches = getSearches();
            let matches = searches.filter(s => s.keyword.toLowerCase().includes(val.toLowerCase()));
            
            renderAutocomplete(matches);
        });
        
        searchInput.addEventListener('focus', function() {
            let searches = getSearches();
            renderAutocomplete(searches);
        });
    });

    function saveSearch() {
        let searchInput = document.getElementById('search');
        let searchValue = searchInput.value;
        if(searchValue === "") return;

        let now = new Date();
        let timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        let searches = getSearches();
        searches.unshift({ keyword: searchValue, time: timestamp });
        searches = searches.slice(0, 100);

        document.cookie = "searches=" + JSON.stringify(searches) + "; max-age=31536000";    
		// page 1로 초기화 해야함
		 $("#page").val('1');
		 $("#stable").val('1');	
		 document.getElementById('board_form').submit();   		
        
    }

	function renderAutocomplete(matches) {
		const autocompleteList = document.getElementById('autocomplete-list');
		autocompleteList.innerHTML = '';
		
		matches.forEach(function(match) {
			let div = document.createElement('div');
			div.className = 'autocomplete-item';
			// 백틱 대신에 일반적인 문자열 연결 사용
			div.innerHTML = '<span>' + match.time + ' </span> -> ' +  '<span class="text-primary">' + match.keyword + ' </span>' ;
			div.addEventListener('click', function() {
				document.getElementById('search').value = match.keyword;
				autocompleteList.innerHTML = '';
				// page 1로 초기화 해야함
				 $("#page").val('1');
				 $("#stable").val('1');	
				 document.getElementById('board_form').submit();    
			});
			autocompleteList.appendChild(div);
		});
	}


    function getSearches() {
        let cookies = document.cookie.split('; ');
        for(let cookie of cookies) {
            if(cookie.startsWith('searches=')) {
                return JSON.parse(cookie.substring(9));
            }
        }
        return [];
    }


function SearchEnter(){

    if(event.keyCode == 13){		
		saveSearch();
    }
}
	
function movetoPage(page){ 	  
	 $("#page").val(page); 
	 $("#stable").val(1);  // cursort 변경없음 강제입력기능
	 $("#board_form").submit();  
	}	
	

function button_condition(con)
{	
			
				$("#buttonval").val(con);							
				$("#sortof").val(con);							
				$("#page").val('1');							
				$("#stable").val('0');							
				$('#board_form').submit();		// 검색버튼 효과
				
}	

function check_level()
{
window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
}
			  
$(document).ready(function(){
	

$("#choiceworkerBtn").click(function(){ 	
	    popupCenter('choiceworker.php', '소장선택', 900, 500);     
 
 });	
	
	dis_text(); // 화면에 합계문구 보여주기

$("#searchBtn").click(function(){ 	
	  saveSearch(); 
 });		

// 출고완료 미청구 리스트 정렬	
$("#outputBtn").click(function(){	
	$('#buttonval').val('10');            // 강제로 설정함
	$('#output_check').val('1');           
	$('#cursort').val('12');    // 청구 내림차순 정렬
	$('#sortof').val('0');
	$('#scale').val('100');    // 청구 내림차순 정렬
		
	$('#board_form').submit();		
});		
	
// 공사유형 변경시 작동함
$("#checkwork").change(function(){			 
	// $("#find").val('checkstep');	
	$("#findstr").val($(this).val());	
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
	

    $("#outsourcingCheckbox").change(function(){
        if($("#outsourcingCheckbox").is(":checked")){
            $('#checkoutsourcing').val('1');
           // $('#search').val('');			 // search 입력란 비우기			
            $('#board_form').submit();	
        }else{
            $('#checkoutsourcing').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();						
        }
    });
	
    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
           // $('#search').val('');			 // search 입력란 비우기			
            $('#board_form').submit();	
        }else{
            $('#check').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();						
        }
    });
	
    $("#outputlist").change(function(){
        if($("#outputlist").is(":checked")){
            $('#output_check').val('1');
           //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#output_check').val('');
          //   $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#plan_outputlist").change(function(){
        if($("#plan_outputlist").is(":checked")){
            $('#plan_output_check').val('1');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#plan_output_check').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#team").change(function(){
        if($("#team").is(":checked")){
            $('#team_check').val('1');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#team_check').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });		
    $("#notmeasure").change(function(){        // 미실측리스트 클릭시 동작	
        if($("#notmeasure").is(":checked")){
            $('#measure_check').val('1');		
            $('#board_form').submit();
			
        }else{
            $('#measure_check').val('');			
            $('#board_form').submit();			
        }
    });	

$("#scaleval").on("change", function(){
    //selected value
    $("#scale").val($(this).val());
	// 화면고정
	$('#page').val('1');      
	$('#stable').val('1');      
	$('#sortof').val('0');	
	$('#board_form').submit();			
    
});	
	
}); // end of document ready	
	
	




function dis_text()
{  
		var dis_text = '<?php echo $dis_text; ?>';
		$("#dis_text").text(dis_text);
}	

function List_name(worker)
{	
		var worker; 				
		var name='<?php echo $user_name; ?>' ;
		 
			$("#search").val(worker);	
			$('#board_form').submit();		// 검색버튼 효과
}


function check_alert()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
			tmp="../load_alert.php";			
			$("#vacancy").load(tmp);     
		    var voc_alert=$("#voc_alert").val();	 
		    var ma_alert=$("#ma_alert").val();	 
		    var order_alert=$("#order_alert").val();	 
			
 		if(name=='이미래' && voc_alert=='1') {			
			alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 이미래 대리님 <br> <br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>'); 			
			tmp="../save_alert.php?voc_alert=0" + "&ma_alert=" + ma_alert +  "&order_alert=" + order_alert;	
			$("#voc_alert").val('0');				
			$("#vacancy").load(tmp);   						
											}
											
 		if(name=='이미래' && order_alert=='1') {			
			alertify.alert('<H1> 쟘 발주서 도착 알림</H1>', '<h1> 이미래 대리님 <br> <br> 이메일을 확인해 주세요. 발주서가 접수되었습니다. </h1>'); 			
			tmp="../save_alert.php?order_alert=0" + "&ma_alert=" + ma_alert +  "&voc_alert=" + voc_alert;	
			$("#order_alert").val('0');				
			$("#vacancy").load(tmp);   						
											}											
											
 		if(name=='조경임' && ma_alert=='1') {			
			alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조차장님 <br> <br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>'); 			
			tmp="../save_alert.php?ma_alert=0" + "&voc_alert=" + voc_alert + "&order_alert=" + order_alert;	
			$("#ma_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}											
}


	
function send_alert() {   // 알림을 서버에 저장 
	var voc_alert=$("#voc_alert").val();	 
	var ma_alert=$("#ma_alert").val();	 
	var order_alert=$("#order_alert").val();	

	var tmp; 				
			
	tmp = "../save_alert.php?order_alert=1" + "&ma_alert=" + ma_alert +  "&voc_alert=" + voc_alert;	
			
		$("#vacancy").load(tmp);      
		
		alertify.alert('발주서 등록 알림', '<h1> 발주서가 접수되었습니다. 이메일을 확인해 주세요. </h1>'); 	

 }      	
 

// 5초마다 알람상황을 체크합니다.
var timer;
timer=setInterval(function(){
	check_alert();
},5000);  
 

	
	
</script>
   