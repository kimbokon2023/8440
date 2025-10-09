<?php
// 소장들 보는 모바일 화면 구성 - 로컬/서버 환경 호환
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 99;
$id_name = $_SESSION["name"] ?? '';
$user_name = $_SESSION["name"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    sleep(2);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

// 작업자명 처리
$workername = $_REQUEST["workername"] ?? '';

if (!empty($workername)) {
    $id_name = $workername;
    $user_name = $workername;
} else {
    $level = $_SESSION["level"] ?? 99;
    $id_name = $_SESSION["name"] ?? '';
    $user_name = $_SESSION["name"] ?? '';
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

<style>
.container { padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; } 

@media (min-width: 768px) { .container { width: 750px; } }
@media (min-width: 992px) { .container { width: 970px; } } 
@media (min-width: 1200px) { .container { width: 1170px; } } 
.container-fluid { padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; }
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, 
.col-lg-12 { position: relative; min-height: 1px; padding-right: 6px; padding-left: 6px; } .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 { float: left; } .col-xs-12 { width: 100%; } .col-xs-11 { width: 91.66666667%; } .col-xs-10 { width: 83.33333333%; } /* 생략 */ @media (min-width: 768px) { .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; } .col-sm-12 { width: 100%; } /* 생략 */ @media (min-width: 992px) { .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 { float: left; } .col-md-12 { width: 100%; } .col-md-11 { width: 91.66666667%; } .col-md-10 { width: 83.33333333%; }


.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11,  .col-xs-12 
{ position: relative; min-height: 1px; padding-right: 3px; padding-left: 3px; float:left; } 

</style>

 <title> 미래기업 쟘공사 관리시스템 </title>
 </head>
 <style>
      html,
      body {
        height: 100%;
      }
      .inputcontainer {
        display: flex;
        flex-direction: column;
        width: 100% ;
        height: 100% ;
        align-items: left;
        justify-content: left;
      }
      .input {
        width: 450px;
        margin: 5px 0;
      }
      .input:focus {
        animation-name: border-focus;
        animation-duration: 1s;
        animation-fill-mode: forwards;
      }
      @keyframes border-focus {
        from {
          border-radius: 0;
        }
        to {
          border-radius: 15px;
        }
      }
	    
</style>
 
 <?php
// 입력값 초기화
$search = $_REQUEST["search"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? 0;
$mode = $_REQUEST["mode"] ?? '';

// 데이터베이스 연결
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        if (isLocal()) {
            die("데이터베이스 연결 실패: " . $e->getMessage());
        } else {
            error_log("Database connection failed in index1.php: " . $e->getMessage());
            die("데이터베이스 연결에 실패했습니다. 관리자에게 문의하세요.");
        }
    }
}

// 배열 초기화
$sum = array(0, 0, 0, 0);

// 조회 조건 설정
switch($check) {  
  case '1' :  // 출고예정 체크된 경우
				$attached=" and (date(endworkday)>=date(now()))  ";
				$orderby=" order by endworkday asc ";						
				break;		
   case '2' :  // 시공완료 체크된 경우 (사진여부)
				$attached=" and (filename2<>'')  ";				
                $orderby=" order by workday desc  ";							
				break;
   case '3' :  // 미시공 체크된 경우
 	         	$attached=" and ((doneday='') or (doneday='0000-00-00'))  ";
			    $orderby=" order by orderday desc  ";											
				break;
   case '4' :  // 미실측 체크된 경우
				$attached=" and ((measureday='') or (measureday='0000-00-00')) ";
			    $orderby=" order by num asc  ";											
				break;				
	default:	
	  	    $orderby=" order by num desc  ";															
		}		 
	 
$a= " " . $orderby . " ";  
$b=  " " . $orderby;

		  if($search==""){
			    if($check=='1')
				{
					 $sql="select * from mirae8440.work where   ( worker='$id_name') " . $attached . $orderby; 					
				}	
		    elseif($check=='2')
				{
				$sql="select * from mirae8440.work where   ( worker='$id_name') " . $attached . $orderby ; 					
				}
		    elseif($check=='3')
				{
					 $sql="select * from mirae8440.work where worker='$id_name' " . $attached . $orderby ; 					
				}				
		    elseif($check=='4')
				{
				$sql="select * from mirae8440.work where   ( worker='$id_name') " . $attached . $orderby ; 				 
				}				
				else
					{
					 $sql="select * from mirae8440.work where worker='$id_name' " . $a; 					
	                 $sqlcon = "select * from mirae8440.work where worker='$id_name' "  . $b;   // 전체 레코드수를 파악하기 위함.					 
				}
		  }
			else						 
		  			 
        { // 필드별 검색하기
					  $sql ="select * from mirae8440.work where ((workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) ) and (worker='$id_name') " . $a;
					  
                      $sqlcon ="select * from mirae8440.work where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ))  and (worker='$id_name') " . $b;
				  } 

	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
				$total_row = $temp1;     // 전체 글수	   
			 
$message = '';			 
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
          <a href="<?= getBaseUrl() ?>/login/login_form.php">로그인</a> | <a href="<?= getBaseUrl() ?>/member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>
			<div class="row">
           <div class="col-6"> 
		         <h3 class="display-5 font-center text-left"> 
	<?= htmlspecialchars($user_name) ?> | 
		<a href="<?= getBaseUrl() ?>/login/logout.php">로그아웃</a> | <a href="<?= getBaseUrl() ?>/member/updateForm.php?id=<?= htmlspecialchars($_SESSION["userid"]) ?>">정보수정</a>
		
<?php
	 }
?>
</h3>
</div> </div> 
<br>
<form id="board_form" name="board_form" method="get" action="<?= getBaseUrl() ?>/p/index1.php?mode=search&search=<?= urlencode($search) ?>&check=<?= htmlspecialchars($check) ?>">  
	<div class="row">
   <H1> &nbsp;&nbsp;  쟘(Jamb) 현장 </H1> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;  
   <button type="button"  class="btn btn-outline-primary btn-lg" onclick="window.open('<?= getBaseUrl() ?>/p/request.php','VB 자재 협조 요청사항','top=10, left=10, height=600, width=800, menubar=no, toolbar=no,');">  (바이브)VB 자재사용시 요청사항 </button>  &nbsp;  
   <button type="button"  class="btn btn-outline-success btn-lg" onclick="location.href='<?= getBaseUrl() ?>/p/hpi.php';">  HPI(타공) </button>  </div>
	 
	<?php if($message!=='') { ?>
		<div class="d-flex">
		  <div class="p-2 flex-fill ">			  
		  <h2 class="display-4  bg-dark text-light ">공지</h2></div>
		  <div class="p-2 flex-fill ">			  
		  <?php print '<h2 class="display-4  bg-dark text-light">' . $message . ' </h2></div>' ; ?>
		  
	 </div> 
	<? } ?>
			
			<br>
			<div class="row">

<div class="col-6">
      <h4 class="display-4 font-center text-center">    <div class="inputcontainer">    <input type="text" id="search" name="search" value="<?=$search?>" size="30"  class="input" placeholder="검색어">		</div>  </h4> 	  
        </div>  		
<div class="col-5 text-left">

<button type="button"  class="btn btn-dark btn-lg" onclick="document.getElementById('board_form').submit();"> 검색   </button>
        </div>  		
  </div>
		<br> <br>
		<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<button type="button" id="showall" class="btn btn-dark btn-lg " onclick="location.href='<?= getBaseUrl() ?>/p/index1.php?mode=search&search=<?= urlencode($search) ?>&check=0'"> 전체   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button id="showNomeasure"  type="button" class="btn btn-success btn-lg btn-lg " onclick="location.href='<?= getBaseUrl() ?>/p/index1.php?mode=search&search=<?= urlencode($search) ?>&check=4'"> 미실측  </button> &nbsp;&nbsp;&nbsp;&nbsp;
<button id="showNowork" type="button" class="btn btn-info btn-lg " onclick="location.href='<?= getBaseUrl() ?>/p/index1.php?mode=search&search=<?= urlencode($search) ?>&check=3'"> 미시공   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" id="outputplan" class="btn btn-danger btn-lg " onclick="location.href='<?= getBaseUrl() ?>/p/index1.php?mode=search&search=<?= urlencode($search) ?>&check=1'"> 출고예정   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" id="outputplan2" class="btn btn-outline-primary " onclick="location.href='<?= getBaseUrl() ?>/p/index1.php?mode=search&search=<?= urlencode($search) ?>&check=2'"> 시공후 사진등록완료   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="btn btn-secondary btn-lg" onclick="location.href='<?= getBaseUrl() ?>/p/workfee.php?mode=search&search=<?= urlencode($search) ?>&worker=<?= urlencode($user_name) ?>'"> 시공내역 </button>
		</div>
	<br>	
		<input type="hidden" id="workername" name="workername" value="<?= htmlspecialchars($workername) ?>"   > 						
		<input type="hidden" id="check" name="check" value="<?= htmlspecialchars($check) ?>"   > 						
		<input type="hidden" id="sqltext" name="sqltext" value="<?= htmlspecialchars($sqltext ?? '') ?>" > 			
		<input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert ?? '') ?>"   > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert ?? '') ?>"   > 	
         <div id="vacancy" style="display:none">  </div>
			                
		<?php
			?>
        <div id="list_search4"></div>

        <div id="list_search5"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
 <div id="list_search11">		
		    
      </div> <!-- end of list_search11  -->
	  
<div id="list_search12">			  	
	  
      </div> <!-- end of list_search12  -->
	  
	  <?php 
	    if($check==3) // 미시공 클릭
		{
			
		print '		        <div class="row">        
        <h1 class="display-5 font-center text-center text-danger"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 미시공리스트는 (시공완료버튼)을 누르시면 없어집니다. </h1>
        </div> ';			
		}
		
	  ?>
	  
	  

	        <div class="row">
        <div class="col-1">
        <h4 class="display-5 font-center text-center"> No </h4>
        </div>
		<?php
		  switch($check) {
			  case '1' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center text-dark "> 착공 </h4> </div> ';
							print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center text-danger "> 출고예정 </h4> ';
							break;
			  case '2' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  "> 출고 </h4> ';
							break;							
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 접수</h4> ';
							break;														
		  }	  			?>							
      </div>
	  
	  		<?php
		  switch($check) {
			  case '0' :
			   			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center  text-danger "> 출고 </h4> ';
							break;
			  default : 
			     print ' <div class="col-sm-1">
							<h4 class="display-5 font-center text-center"> 검사</h4> ';
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
      <h4 class="display-5 font-center text-center text-danger "  > 시공 완료일 </h4>
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
			  $designer=$row["designer"];
			  
			  $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") 
			  {
			      $draw_done = "OK";	
					if($designer!='')
						 $draw_done = $designer ;
			  }
			  
			  $workday=$row["workday"];
			  $doneday=$row["doneday"];
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
			  
			  $filename1 = $row["filename1"] ?? '';
			  $filename2 = $row["filename2"] ?? '';
			  
			  // 환경별 이미지 경로 설정
			  if (isLocal()) {
			      $imgurl1 = !empty($filename1) ? "../imgwork/" . $filename1 : '';
			      $imgurl2 = !empty($filename2) ? "../imgwork/" . $filename2 : '';
			  } else {
			      $imgurl1 = !empty($filename1) ? asset("imgwork/" . $filename1) : '';
			      $imgurl2 = !empty($filename2) ? asset("imgwork/" . $filename2) : '';
			  }			  
			  
			  
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
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";		  
			  	  				  
			  $state_work=0;
			  if(isset($row["checkbox"]) && $row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
	 
              $measure_done="     ";			  
			  if(substr($row["measureday"],0,2)=="20") $measure_done = "OK";		    			  
              
			  if(substr($row["testday"],0,2)=="20")  $testday = iconv_substr($testday,5,5,"utf-8");
			            else $testday="    ";	              
			  if(substr($row["doneday"],0,2)=="20")  $doneday = iconv_substr($doneday,5,5,"utf-8");
			            else $doneday="    ";			  
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
							print ' <h4 class="display-5 font-center text-center text-dark "> ' . iconv_substr($startday,5,5,"utf-8") . '&nbsp;</h4> </div>';
							print ' <div class="col-sm-1">  <h4 class="display-5 font-center text-center text-danger "> ' . iconv_substr($endworkday,5,5,"utf-8") . '&nbsp;</h4> ';
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
          <h4 class="display-5 font-center text-center text-secondary"> <?=$draw_done?> &nbsp;</h4>
        </div>
        <div class="col-sm-1">
          <h4 class="display-5 font-center text-center text-secondary"> <?=$doneday?> &nbsp;</h4>
        </div>		
        <div class="col-sm-1">
          <h4 class="display-5 font-center text-center text-secondary"> <?=$pic_done?> &nbsp;</h4>
        </div>				
        <div class="col-sm-4">
          <h3 class="display-5 font-center text-left"><a href="<?= getBaseUrl() ?>/p/view.php?num=<?= htmlspecialchars($num) ?>&check=<?= htmlspecialchars($check) ?>&workername=<?= urlencode($workername) ?>"  > <?= htmlspecialchars($workplacename) ?> </a>&nbsp; </h3>
        </div>				
      </div>	 
				  
</a>
            <div class="clear" > </div>
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
      if (isLocal()) {
          print "오류: " . $Exception->getMessage();
      } else {
          error_log("Database error in index1.php: " . $Exception->getMessage());
          print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
      }
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

  </body>    
  </html>