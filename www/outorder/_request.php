<?php
if(isset($_REQUEST["search"]))   
 $search=$_REQUEST["search"];
if(isset($_REQUEST["list"]))   
 $list=$_REQUEST["list"];
else
	  $list=0;
  
if(isset($_REQUEST["scale"]))   
 $scale=$_REQUEST["scale"];
else
	  $scale=50;	  	  

if(isset($_REQUEST["check_draw"])) 
 $check_draw=$_REQUEST["check_draw"];   // 도면 미설계List
   else
	 $check_draw=$_POST["check_draw"]; 

if(isset($_REQUEST["notorder"])) 
 $notorder=$_REQUEST["notorder"];   // 미발주 부품 계List
   else
	 $notorder=$_POST["notorder"]; 		 
 
if(isset($_REQUEST["check"]))        // 미출고 List
 $check=$_REQUEST["check"];
else
 $check=$_POST["check"]; 

if(isset($_REQUEST["plan_output_check"])) 
 $plan_output_check=$_REQUEST["plan_output_check"]; // 출고예정`
else
if(isset($_POST["plan_output_check"]))   
	 $plan_output_check=$_POST["plan_output_check"]; // 출고예정  
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
 $measure_check=$_REQUEST["measure_check"]; // 본천장설계
else
if(isset($_POST["measure_check"]))   
	 $measure_check=$_POST["measure_check"]; // 본천장설계
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

if(isset($_REQUEST["cursort"])) 
 $cursort=$_REQUEST["cursort"]; // 미실측리스트
else
if(isset($_POST["cursort"]))   
	 $cursort=$_POST["cursort"]; // 미실측리스트
 else
	 $cursort='0';		  


if(isset($_REQUEST["sortof"])) 
 $sortof=$_REQUEST["sortof"]; // 미실측리스트
else
if(isset($_POST["sortof"]))   
	 $sortof=$_POST["sortof"]; // 미실측리스트
 else
	 $sortof='0';		 
 
if(isset($_REQUEST["stable"])) 
 $stable=$_REQUEST["stable"]; // 미실측리스트
else
if(isset($_POST["stable"]))   
	 $stable=$_POST["stable"]; // 미실측리스트
 else
	 $stable='0';		 
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
if($sortof==3 and $stable==0) {     //발주일 클릭되었을때
	
 if($cursort!=5)
	$cursort=5;
  else
	 $cursort=6;			
   }	   	   
if($sortof==4 and $stable==0) {     //도면작성일 클릭되었을때
	
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

  
require_once("../lib/mydb.php");
$pdo = db_connect();	

?>