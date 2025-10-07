
<?php

 session_start();

   $level= $_SESSION["level"];
   $id_name= $_SESSION["name"];   
 if(!isset($_SESSION["level"]) || $level>10) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
   }  

 ?>
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/> 
  
<link rel="stylesheet" href="../css/partner.css" type="text/css" />

 <title> 미래기업 쟘공사 관리시스템 </title>
 
 
 <style>
.container { padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; } 

@media (min-width: 768px) { .container { width: 750px; } }
@media (min-width: 992px) { .container { width: 970px; } } 
@media (min-width: 1200px) { .container { width: 1170px; } } 
.container-fluid { padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; }
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, 
.col-lg-12 { position: relative; min-height: 1px; padding-right: 6px; padding-left: 6px; } .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 { float: left; } .col-xs-12 { width: 100%; } .col-xs-11 { width: 91.66666667%; } .col-xs-10 { width: 83.33333333%; } /* 생략 */ @media (min-width: 768px) { .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; } .col-sm-12 { width: 100%; } /* 생략 */ @media (min-width: 992px) { .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 { float: left; } .col-md-12 { width: 100%; } .col-md-11 { width: 91.66666667%; } .col-md-10 { width: 83.33333333%; }


.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11,  .col-xs-12, 
{ position: relative; min-height: 1px; padding-right: 3px; padding-left: 3px; float:left; } 

.blink {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}



</style> 
 
 
 </head>

 <?php
 
// 10일 후의 날짜 산출 
 $aftertenday = strtotime("+10 days");

 
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	


 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; //  POST사용 

if($check==null) $check=5;	 // 초기화면 검사일 기준으로 자료 보기
    
  $sum=array();

	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";      
 
 $orderby=" order by endworkday desc ";	// 기본세팅 출고예정일
   
 if ($check=='1')  // 출고예정 체크된 경우
		{
				$attached=" and (date(endworkday)>=date(now()))  ";
				$whereattached=" where date(endworkday)>=date(now()) ";
                $orderby=" order by endworkday asc ";						
		}
 if ($check=='2')  // 시공완료 체크된 경우 (사진여부)
		{
				$attached=" and (filename2<>'')  ";
				$whereattached=" where filename2<>'' ";
                $orderby=" order by workday desc  ";							
		}
if ($check=='3')  // 미시공 체크된 경우
		{
 	         	$attached=" and ((workday!='') and (workday!='0000-00-00')) ";
				$whereattached=" where workday!='' ";
                $orderby=" order by workday desc  ";											
		}			
if ($check=='4')   // 미실측 체크된 경우
		{
				$attached=" and ((measureday!='') and (measureday!='0000-00-00')) ";
				$whereattached=" where measureday!='' ";
                $orderby=" order by num desc  ";											
		}				
if ($check=='5')  // 검사일 체크된 경우
		{
				$attached=" and (date(testday)>=date(now()))  ";
				$whereattached=" where date(testday)>=date(now()) ";
                $orderby=" order by testday asc ";											
		}		 
	 
$a= " " . $orderby . " ";  
$b=  " " . $orderby;
  

		  if($search==""){
			    if($check=='1')
				{
					 $sql="select * from mirae8440.work where   ( secondord='$id_name') " . $attached . " order by endworkday asc "; 								
				}	
		    elseif($check=='2')
				{
					 $sql="select * from mirae8440.work where   ( secondord='$id_name') " . $attached . $orderby ; 			
				}
		    elseif($check=='3')
				{
  					$sql="select * from mirae8440.work where secondord='$id_name' and ((workday='') or (workday='0000-00-00')) " . $a; 					
	                 $sqlcon = "select * from mirae8440.work where secondord='$id_name'  and ((workday='') or (workday='0000-00-00')) "  . $b;   // 전체 레코드수를 파악하기 위함.					 
				}				
		    elseif($check=='4')
				{
					 $sql="select * from mirae8440.work where secondord='$id_name' and ((measureday='') or (measureday='0000-00-00')) " . $a; 					
	                 $sqlcon = "select * from mirae8440.work where secondord='$id_name'  and ((measureday='') or (measureday='0000-00-00')) "  . $b;   // 전체 레코드수를 파악하기 위함.					 
				}	
			elseif($check=='5')
				{
					 $sql="select * from mirae8440.work where   ( secondord='$id_name') " . $attached . " order by testday asc "; 								
				}				
				else
					{
					 $sql="select * from mirae8440.work where secondord='$id_name' " . $a; 					
	                 $sqlcon = "select * from mirae8440.work where secondord='$id_name' "  . $b;   // 전체 레코드수를 파악하기 위함.					 
				}
		  }
			else				 
		  			 
        { // 필드별 검색하기
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) ) and (secondord='$id_name') " . $a;
					  
                      $sqlcon ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ))  and (secondord='$id_name') " . $b;
				  } 
			     
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
				$total_row = $temp1;     // 전체 글수	  
			 
			?>
		 
<body>

     <div  class="container-fluid">
	 <br>
	 <br>
<div id="top-menu">
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>
			<div class="row">
           <div class="col-6"> 
		         <h3 class="display-5 font-center text-left"> 
	<?=$_SESSION["nick"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>
</h3>
</div> </div> 
<br>
<form id="board_form" name="board_form" method="get" action="index.php?mode=search&search=<?=$search?>&check=<?=$check?>">  

	 <H1> 미래기업 쟘(Jamb) 공사 </H1> 	 		
			
			<br>
					    <div class="d-flex">
			  <div class="p-2 flex-fill ">			  
			  <h2 class="display-5 text-light bg-secondary">'검사일' 누르면 오늘날짜부터 검사일로 정렬됨</h2></div> </div>
					    <div class="d-flex">
			  <div class="p-2 flex-fill ">			  
			  <h2 class="display-5 text-light bg-dark">'검사일' 10일 전까지 현장소장 연락처 확인 부탁드립니다.</h2></div> </div>
			
			<div class="row">
           <div class="col-5">
		         <h3 class="display-5 text-left"> 		   
             총 <?= $total_row ?> 개  </h3>
        </div>  
<div class="col">
      <h4 class="display-4 font-center text-center">       <input type="text" id="search" name="search" value="<?=$search?>" size="20">		  </h4> 	  
        </div>  		
<div class="col">

      <button type="button"  class="btn btn-dark btn-lg" onclick="document.getElementById('board_form').submit();"> 검색   </button>
        </div>  		
  </div>
		<br> <br>
		<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<button type="button" id="showall" class="btn btn-dark btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=0'"> 전체   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="btn btn-warning btn-lg  " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=5'"> 검사일   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" id="outputplan" class="btn btn-danger btn-lg  " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=1'"> 출고예정   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" id="outputplan" class="btn btn-primary  btn-lg  " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=2'"> 사진등록완료   </button>  &nbsp;&nbsp;&nbsp;&nbsp;

<button id="showNowork" type="button" class="btn btn-info  btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=3'"> 미시공   </button>
&nbsp;&nbsp;&nbsp;&nbsp;
<button id="showNomeasure"  type="button" class="btn btn-success btn-lg  " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=4'"> 미실측  </button>

		</div>
<br>		
 <div class="row">

		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 		
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 						
		</div>
	         	
	  
     <div class="row">
	 
        <div class="col-1">
        <h4 class="display-5 font-center text-center"> No </h4>
        </div>
		<?php
		  switch($check) {
			  case '1' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center text-danger "> 출고예정일 </h4> </div>';
							break;
			  case '2' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  "> 출고일 </h4> </div>';
							break;
			  case '5' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  text-primary "> 검사일 </h4> </div>';
							break;							
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 접수일</h4> </div>';
							break;														
					}	 
		  
		  switch($check) {
			  case '0' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  text-danger "> 출고일 </h4> </div>';
							break;
			  case '5' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  "> 실측일 </h4> </div>';
							break;
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 검사일</h4> </div>';
							break;														
		  }	  	
		  
		  switch($check) {   // 3번째 칸
			  case '5' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  "> 발주현장소장 </h4> </div>';
							break;							
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 실측</h4> </div>';
							break;														
					}	 
		  
		  switch($check) { // 4번째 칸			  
			  case '5' :
			   			     print ' <div class="col-sm-1">
							<h3 class="display-5 font-center text-center  "> 소장전번 </h3> </div>';
							break;
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 도면</h4> </div>';
							break;														
		  }	  
		  switch($check) { // 5번째 칸
			  case '5' :
			   			     print ' <div class="col-sm-1">
							<h3 class="display-5 font-center text-center  "> 미래소장 </h3> </div>';
							break;
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 후사진 </h4> </div>';
							break;														
		  }	  			?>

        <div class="col-sm">
      <h4 class="display-5 font-center text-center"> 현장명</h4>
        </div>		
      </div>
	    <div class="row"><br>	</div>
	  
    <?php  
	
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
			
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
			  $delicompany=$row["delicompany"];
			  
			  $workday=$row["workday"];
			  $startday=$row["startday"];
			  $testday=$row["testday"];
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
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
			  
			  $filename1=$row["filename1"];
			  $filename2=$row["filename2"];
			  $imgurl1="../imgwork/" . $filename1;
			  $imgurl2="../imgwork/" . $filename2;			  
			  
			  
			  $sum[0] = $sum[0] + (int)$widejamb;
			  $sum[1] += (int)$normaljamb;
			  $sum[2] += (int)$smalljamb;
			  $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
			  
			  $dis_text = "막판 : " . $sum[0] . " 세트, 막판(無) : " . $sum[1] . " 세트, 쪽쟘 : "  . $sum[2] . " 세트, 합계 : " . $sum[3] . " 세트" ; 

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

			  $designer=$row["designer"];
			  
			  $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") 
			  {
			      $draw_done = "OK";	
					if($designer!='')
						 $draw_done = $designer ;
			  }					
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
              $measure_done="     ";			  
			  if(substr($row["measureday"],0,2)=="20") $measure_done = "OK";		    			  
              
			  if(substr($row["measureday"],0,2)=="20")  $measureday = iconv_substr($measureday,5,5,"utf-8");
			            else $measureday="    ";	           
			  if(substr($row["testday"],0,2)=="20")  $testday = iconv_substr($testday,5,5,"utf-8");
			            else $testday="    ";			  
              if($filename2!="") $pic_done="등록";
			     else
				    $pic_done='';
			 ?>
			 
			  <div class="clear" > </div>
			
	<div class="row">	
	
        <div class="col-1">

          <h4 class="display-5 font-center text-center"> <?=$start_num?> </h4>
        </div>		 
        <div class="col-sm-1"> 
		
		<?php
		// 2번칸
		  switch($check) {
			  case '1' :
							print ' <h4 class="display-5 font-center text-center text-danger "> ' . iconv_substr($endworkday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;
			  case '2' :
			   			    print ' <h4 class="display-5 font-center text-center"> ' .  iconv_substr($workday,5,5,"utf-8")  . '&nbsp;</h4> ';
							break;	
			  case '5' :  // 검사일기준
			   if($aftertenday >= $testday and $chargedman=='')  // blink 기능 넣어주기
			   			    print ' <h4 class="blink display-5 font-center text-center text-danger"> ' .  $testday  . '&nbsp;</h4> ';
						else
						   print ' <h4 class="display-5 font-center text-center text-primary"> ' .  $testday  . '&nbsp;</h4> ';
							break;							
			  default : 
							print ' <h4 class="display-5 font-center text-center"> ' . iconv_substr($orderday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;														
				}					
			?>		
			
        </div>		
        <div class="col-sm-1"> 
        <?php
		// 3번칸
		  switch($check) {
			  case '0' :
							print ' <h4 class="display-5 font-center text-center text-danger "> ' . iconv_substr($workday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;
			  case '5' :
							print ' <h4 class="display-5 font-center text-center text-secondary "> ' . $measureday . '&nbsp;</h4> ';
							break;
			  default : 
							print ' <h4 class="display-5 font-center text-center text-primary"> ' . $testday . '&nbsp;</h4> ';
							break;														
				}					
			?>		
			
        </div>				
        <div class="col-sm-1">
        <?php
		// 4번째 칸
		  switch($check) {
			  case '5' :
							print ' <h5 class="display-6 font-center text-center text-success "> ' . $chargedman . '&nbsp;</h5> ';
							break;
			  default : 
							print ' <h4 class="display-5 font-center text-center "> ' . $measureday . '&nbsp;</h4> ';
							break;														
				}					
			?>					
        </div>       
        <div class="col-sm-1">
        <?php
		  switch($check) {
			  case '5' :
							print ' <h4 class="display-5 font-center text-center  text-success "> ' . $chargedmantel . '&nbsp;</h4> ';
							break;
			  default : 
							print ' <h5 class="display-5 font-center text-center text-success"> ' . $draw_done . '&nbsp;</h5> ';
							break;														
				}					
			?>					
        </div>		
        <div class="col-sm-1">
        <?php
		  switch($check) {			  
			  case '5' :
			        if($aftertenday >= $testday and $chargedman=='')  // blink 기능 넣어주기
						print ' <h5 class="blink display-6 font-center text-center text-danger "> ' . $worker . '&nbsp;</h5> ';
							else
								print ' <h5 class="display-6 font-center text-center text-secondary "> ' . $worker . '&nbsp;</h5> ';
							break;
			  default : 
							print ' <h4 class="display-5 font-center text-center text-primary"> ' . $pic_done . '&nbsp;</h4> ';
							break;														
				}					
			?>							          
        </div>				
        <div class="col-sm-6">	
        <?php
		  switch($check) {			  
			  case '5' :
			        if($aftertenday >= $testday and $chargedman=='')  // blink 기능 넣어주기
								print ' <h4 class="display-5 font-center text-left text-danger"> <a href="view.php?num=' . $num . '&check=' .  $check . '">  ' . $workplacename . '  </a>&nbsp;  </h4> ';
							else
								print ' <h4 class="display-5 font-center text-left"> <a href="view.php?num=' . $num . '&check=' .  $check . '">  ' . $workplacename . '  </a>&nbsp;  </h4> ';
							break;
			  default : 
							print ' <h4 class="display-5 font-center text-left"> <a href="view.php?num=' . $num . '&check=' .  $check . '">  ' . $workplacename . '  </a>&nbsp;  </h4> ';
							break;														
				}					
			?>          
        </div>				
      </div>	       
		
            <div class="clear" > </div>
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

 ?>
 <br>
 <br>
	
	</form>	
         </div> <!-- end of  container -->     

  </body>  

  
  </html>