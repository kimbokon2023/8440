
<?php

 session_start();

   $level= $_SESSION["level"];
   $id_name= $_SESSION["name"];   
 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }  
   
if($id_name=='소현철' || $id_name=='김보곤')
{
	     header ("Location:http://8440.co.kr/index.php");
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

</style> 
 
 </head>

 <?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
   if(isset($_REQUEST["scale"]))   //목록표에 제목,이름 등 나오는 부분
	 $scale=$_REQUEST["scale"];
    else
		  $scale=1000;	  	  
	  	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	


 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; //  POST사용 

if($check==null) $check=0;	 
    
  switch($check) {  
  case '1' :  // 출고예정 체크된 경우
				$attached="  (date(endworkday)>=date(now()))  ";
				$orderby=" order by endworkday asc ";						
				break;		
   case '2' :  // 시공완료 체크된 경우 (사진여부)
				$attached="  (filename2<>'')  ";				
                $orderby=" order by workday desc  ";							
				break;
   case '3' :  // 미시공 체크된 경우
 	         	$attached="  ((workday='') or (workday='0000-00-00'))  ";
			    $orderby=" order by orderday desc  ";											
				break;
   case '4' :  // 미실측 체크된 경우
				$attached=" ((measureday='') or (measureday='0000-00-00')) ";
			    $orderby=" order by num asc  ";											
				break;				
	default:	
	  	    $orderby=" order by num desc  ";															
		}		
  
$a= " " . $orderby . " ";  
$b=  " " . $orderby;
  
  $sum=array(); 

  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
   
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"];
   

		  if($search==""){
			    if($check=='1')
				{
					 $sql="select * from mirae8440.work where   " . $attached . $orderby; 					
				}	
		    elseif($check=='2')
				{
				$sql="select * from mirae8440.work where  " . $attached . $orderby ; 					
				}
		    elseif($check=='3')
				{
					 $sql="select * from mirae8440.work where  " . $attached . $orderby ; 					
				}				
		    elseif($check=='4')
				{
				$sql="select * from mirae8440.work where   " . $attached . $orderby ; 				 
				}				
				else
					{
					 $sql="select * from mirae8440.work " . $a; 					
	                 $sqlcon = "select * from mirae8440.work "  . $b;   // 전체 레코드수를 파악하기 위함.					 
				}
		  }
			else						 
		  			 
        { // 필드별 검색하기
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ))  " . $a;
					  
                      $sqlcon ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ))  " . $b;
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
<form id="board_form" name="board_form" method="get" action="index.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=<?=$stable?>&scale=10000">  

	 <H1> 미래기업 쟘(Jamb) 공사관리System </H1> 
			
			<br>
			<div class="row">
			  <!--	<button id="btn-1" type="button" class="btn btn-default" >Open</button>
					  <!-- Modal -->
				  <div class="modal fade" id="myModal" role="dialog">
					<div class="modal-dialog">
					
					  <!-- Modal content-->
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal">&times;</button>
						  <h4 class="modal-title">미래기업 공지사항</h4>
						</div>
						<div class="modal-body">
						  <p>요즘 쟘 출고량이 많습니다. 출고일이 지연될 수 있음을 양해 부탁드립니다.</p>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
						</div>
					  </div>
					  
					</div>
				  </div> 
				  </div>
			<div class="row">
           <div class="col-5">
		         <h3 class="display-5 text-left"> 		   
             총 <?= $total_row ?> 개의 자료 파일이 있습니다.   			  </h3>
        </div>  
<div class="col">
      <h4 class="display-4 font-center text-center">       <input type="text" id="search" name="search" value="<?=$search?>" size="20">		  </h4> 	  
        </div>  		
<div class="col">

      <button type="button"  class="btn btn-dark btn-lg" onclick="document.getElementById('board_form').submit();"> 검색   </button>
        </div>   		
  </div>
  <br>
  		<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button type="button" class="btn btn-primary btn-lg " onclick="location.href='index.php?mode=search&search=추영덕'"> 추영덕   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-secondary btn-lg " onclick="location.href='index.php?mode=search&search=이만희'"> 이만희   </button>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-success btn-lg " onclick="location.href='index.php?mode=search&search=김상훈'">김상훈   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-danger btn-lg " onclick="location.href='index.php?mode=search&search=김운호'"> 김운호   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-warning btn-lg " onclick="location.href='index.php?mode=search&search=이용휘'"> 이용휘   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-info btn-lg " onclick="location.href='index.php?mode=search&search=손상민'"> 손상민   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
<br>
  		<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<button  type="button" class="btn btn-light btn-lg " onclick="location.href='index.php?mode=search&search=김진섭'"> 김진섭   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-dark btn-lg " onclick="location.href='index.php?mode=search&search=유영'"> 유영   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-primary btn-lg " onclick="location.href='index.php?mode=search&search=지영복'">  지영복   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-secondary btn-lg " onclick="location.href='index.php?mode=search&search=김한준'">  김한준   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-success btn-lg "  onclick="location.href='index.php?mode=search&search=민경채'">  민경채   </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		</div>
  
		<br> <br>
		<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<button type="button" id="showall" class="btn btn-dark btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=0'"> 전체   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" id="outputplan" class="btn btn-danger btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=1'"> 출고예정   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" id="outputplan" class="btn btn-primary btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=2'"> 시공후 사진등록완료   </button>  &nbsp;&nbsp;&nbsp;&nbsp;

<button id="showNowork" type="button" class="btn btn-info btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=3'"> 미시공   </button>
&nbsp;&nbsp;&nbsp;&nbsp;
<button id="showNomeasure"  type="button" class="btn btn-success btn-lg btn-lg " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=4'"> 미실측  </button>


		</div>
<br>		
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 		
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 				
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 				
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 				
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 				
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
	                
		<?php
			?>
        <div id="list_search4"></div>

        <div id="list_search5"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <div id="list_search11">		
		    
      </div> <!-- end of list_search11  -->
<div id="list_search12">			  	
	  
      </div> <!-- end of list_search12  -->

	    <div class="row">
        <div class="col-1">
        <h4 class="display-5 font-center text-center"> No </h4>
        </div>
		<?php
		  switch($check) {
			  case '1' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center text-danger "> 출고예정일 </h4> ';
							break;
			  case '2' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  "> 출고일 </h4> ';
							break;							
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 접수일</h4> ';
							break;														
		  }	  			?>							
      </div>
	  
	  		<?php
		  switch($check) {
			  case '0' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  text-danger "> 출고일 </h4> ';
							break;
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 검사일</h4> ';
							break;														
		  }	  			?>
	  </div>     
	  
        <div class="col-sm-1">
      <h4 class="display-5 font-center text-center" > 실측 </h4>
        </div>
        <div class="col-sm-1">
      <h4 class="display-5 font-center text-center"  > 도면 </h4>
        </div>		
        <div class="col-sm-1">
      <h4 class="display-5 font-center text-center text-primary "  > 후사진 </h4>
        </div>				
        <div class="col-sm">
      <h4 class="display-5 font-center text-center"> 현장명</h4>
        </div>		
      </div>
    
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
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
              $draw_done="     ";			  
			  if(substr($row["drawday"],0,2)=="20") $draw_done = "OK";		 
              $measure_done="     ";			  
			  if(substr($row["measureday"],0,2)=="20") $measure_done = "OK";		    			  
              
			  if(substr($row["testday"],0,2)=="20")  $testday = iconv_substr($testday,5,5,"utf-8");
			            else $testday="    ";			  
              if($filename2!="") $pic_done="등록";
			     else
				    $pic_done='';	 
    			  

			 ?>
			 
	<div class="row">
        <div class="col-1">
		  
          <h4 class="display-5 font-center text-center"> <?=$start_num?> </h4>
        </div>
        <div class="col-sm-1"> 
		<?php
		  switch($check) {
			  case '1' :
							print ' <h4 class="display-5 font-center text-center text-danger "> ' . iconv_substr($endworkday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;
			  case '2' :
			   			    print ' <h4 class="display-5 font-center text-center"> ' .  iconv_substr($workday,5,5,"utf-8")  . '&nbsp;</h4> ';
							break;							
			  default : 
							print ' <h4 class="display-5 font-center text-center"> ' . iconv_substr($orderday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;														
				}					
			?>		
			
        </div>		
        <div class="col-sm-1"> 
        <?php
		  switch($check) {
			  case '0' :
							print ' <h4 class="display-5 font-center text-center text-danger "> ' . iconv_substr($workday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;
			  default : 
							print ' <h4 class="display-5 font-center text-center"> ' . iconv_substr($testday,5,5,"utf-8") . '&nbsp;</h4> ';
							break;														
				}					
			?>		
			
        </div>				
        <div class="col-sm-1">
          <h4 class="display-5 font-center text-center text-success"> <?=$measure_done?>&nbsp; </h4>
        </div>
        <div class="col-sm-1">
          <h4 class="display-5 font-center text-center text-danger"> <?=$draw_done?> &nbsp;</h4>
        </div>		
        <div class="col-sm-1">
          <h4 class="display-5 font-center text-center text-secondary"> <?=$pic_done?> &nbsp;</h4>
        </div>				
        <div class="col-6">
          <h3 class="display-5 font-center text-left"><a href="view.php?num=<?=$num?>&check=<?=$check?>"  > <?=$workplacename?> </a>&nbsp; </h3>
        </div>				
      </div>	  
				  
</a>
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
	<div class="row">
        <div class="col-11">
          <h3 class="display-6 font-center text-center">   
		
 </h3>
        </div>
     </div>
	</form>	
         </div> <!-- end of  container -->     
  
<script>
function check_level()
			  {
				window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
			  }
$(document).ready(function(){
	
		  //   $("#myModal").modal();
	
    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
            $('#search').val('');			 // search 입력란 비우기			
            $('#board_form').submit();	
        }else{
            $('#check').val('');
            $('#search').val('');			 // search 입력란 비우기						
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
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#plan_output_check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#team").change(function(){
        if($("#team").is(":checked")){
            $('#team_check').val('1');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#team_check').val('');
            $('#search').val('');			 // search 입력란 비우기						
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
	/*
  $('#showall').click(function(){
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();		  
    });		
	*/


});		

$("#btn-1").click(function(){
   $("#myModal").modal();
});
</script>

  </body>

  
  </html>