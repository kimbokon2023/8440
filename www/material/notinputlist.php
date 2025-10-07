
<?php
   session_start();
   $level= $_SESSION["level"];
   $id_name= $_SESSION["name"];   
 if(!isset($_SESSION["level"]) || $level>7) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }  
   
    if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
if($check==null)
		$check='1';

 ?>
 <!DOCTYPE HTML>
 <html>
 <head>
<meta charset="UTF-8">



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
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
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="../css/partner.css" type="text/css" />
 <title> 미래기업 원자재 관리시스템 </title>
 </head>
 <style> 
#panel, #flip {
  padding: 5px;
  text-align: center;
  background-color: #e5eecc;
  border: solid 1px #c3c3c3;
}

#panel {
  padding: 50px;
  display: none;
}
</style>
 <?php
  
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
  if(isset($_REQUEST["separate_date"]))   //출고일 완료일
	 $separate_date=$_REQUEST["separate_date"];	 
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  
 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 
 $sql="select * from mirae8440.request order by num desc "; 	 
   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();   			
			 
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
	<?=$_SESSION["name"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>
</h3>
</div> </div> 
<br>

<button  type="button" class="btn btn-success btn-lg btn-lg " onclick="location.href='../steel/list.php';"> PC화면으로 이동  </button>&nbsp;&nbsp;&nbsp;	
<button  type="button" class="btn btn-secondary btn-lg btn-lg " onclick="location.href='./index.php?check=1';"> 전체 원자재 List  </button>&nbsp;&nbsp;&nbsp;	
 
<form id="board_form" name="board_form" method="get" action="index.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>">  
<br>
	  <H1 class="display-3 font-center text-center" style="color:red;">  미입고 자재 List  </H1> <br>
	<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 

		</div>	
	
<br>			  

<div class="row">
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 요청일 </h5>
        </div>
        <div class="col-3">
      <h5 class="display-5 font-center text-center"> 현장명 </h5>
        </div>
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 철판종류</h5>
        </div>				
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 규격</h5>
        </div>						
        <div class="col-1">
      <h5 class="display-5 font-center text-center"> 수량 </h5>	  
        </div>								
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 납품업체 </h5>	  
        </div>										

</div>			
<br>
<?php			
        while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  
			  $item=$row["item"];
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"]; 
			  $comment=$row["comment"]; 
			  $which=$row["which"];	 	
			  $model=$row["model"];	 	

			 if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					else $indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";				  
	
	 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
	
         if($which=='2'||$which=='1'){			 
         $real_count++;           
    // print substr($item,0,6);
			?>	
	<div class="row">
				<div class="col-2"> 
					 <h5 class="display-5 font-center text-center" style="color:<?=$date_font?>;"> 
				 <?=iconv_substr($outdate,0,10, "utf-8")?> <h5> </div>	  
			        <div class="col-3">				  
				  <h5 class="display-5 font-center text-center">  <?=iconv_substr($outworkplace,0,20,"utf-8")?>  </h5> </div>
			        <div class="col-2">				  				  
				  <h5 class="display-5 font-center text-center">  <?=iconv_substr($item,0,20,"utf-8")?>   </h5> </div>
			        <div class="col-2">				  				  
				  <h5 class="display-5 font-center text-center">  <?=iconv_substr($spec,0,15,"utf-8")?>   </h5> </div>				  
			        <div class="col-1">				  				  
  				<h5 class="display-5 font-center text-center">  <?=iconv_substr($steelnum,0,3,"utf-8")?>   </h5> </div>				  
			        <div class="col-2">				  				  
  				<h5 class="display-5 font-center text-center">  <?=iconv_substr($company,0,5,"utf-8")?>   </h5> </div>				  
			  </div>	
			  
			   <?php			}			   			   
			        
							}
						}
	 }
         catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
} 						
						
  ?>		
  
			
			
			<br>
			<br>

		
		
  </div>

	</form>	
         </div> <!-- end of  container -->     
  
    </body>
  
  
  </html>