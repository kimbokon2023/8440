<style>
	.c-table { border-collapse: collapse; text-align: center;}
    .c-table th { background:gray; height:40px; color:white; border:1px solid black;}
	.c-table td { border:1px solid grey; }
</style>

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
   
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}   
   
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
 
 <title> 미래기업 천장/LC 포장 제작 List </title>
 
 </head>
 
 <style> 
#panel, #flip {
  padding: 5px;
  text-align: center;
  color:white;
  background-color:blue;
  border: solid 1px #c3c3c3;
}

#panel {
  padding: 30px;
  display: none;
}
</style>
 
 <?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	 
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
 
if(isset($_REQUEST["scale"])) // $_REQUEST["scale"]값이 없을 때에는 20로 지정 
 {
    $scale=$_REQUEST["scale"];  // 페이지 번호
 }
  else
  {
    $scale=50;	   // 한 페이지에 보여질 게시글 수
  }   


  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // List에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
if(isset($_REQUEST["cursort"])) 
	 $cursort=$_REQUEST["cursort"];
   else
	    $cursort=$_POST["cursort"];
		
if($cursort=='')		
	  $cursort='8';	
  
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "2010-01-01";
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

 $SettingDate="orderday ";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
$c= $common . " asc, num desc limit $first_num, $scale";    //오름차순
$d= $common . " asc, num desc ";    //오름차순 전체

$where=" where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
$all=" limit $first_num, $scale";
  
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from mirae8440.ceiling " . $a; 					
	                         $sqlcon = "select * from mirae8440.ceiling " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.ceiling where (orderday like '%$search%')  or (type like '%$search%')   or (inseung like '%$search%') ";
							  $sql .=" or (deadline like '%$search%') or (workplacename like '%$search%') ";
							  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from mirae8440.ceiling where (orderday like '%$search%')   or (type like '%$search%')   or (inseung like '%$search%') ";
							  $sqlcon .="or (deadline like '%$search%') or (workplacename like '%$search%') ";
							  $sqlcon .=" order by " . $SettingDate . " desc, num desc";
						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.ceiling " . $a; 					
	                         $sqlcon = "select * from mirae8440.ceiling " . $b;   // 전체 레코드수를 파악하기 위함.					
                }			   
 if($search=='') {
	 
		   if($cursort==1||$cursort==0||$cursort=='') // 납기일 기준 선택시
		{	
			  $common=" where  (deadline='0000-00-00' or date(deadline)>=date(now())) and ((bon_su>0 ) or  (lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 		                          
		}              
		   if($cursort==2) // 전체 기준 선택시
		{	
					 $sql="select * from mirae8440.ceiling order by orderday desc limit $first_num, $scale" ; 					
					 $sqlcon = "select * from mirae8440.ceiling  order by orderday desc" ;   // 전체 레코드수를 파악하기 위함.	                          
		}  
		   if($cursort==3) // 레이져 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((eunsung_laser_date='0000-00-00' and bon_su>0 ) or  (lclaser_date ='0000-00-00' and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 	                          
		}  
		   if($cursort==4) // 절곡 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((mainbending_date='0000-00-00' and bon_su>0 ) or  (lcbending_date ='0000-00-00' and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 	                         
		}  	
		   if($cursort==5) // 제관 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((mainwelding_date='0000-00-00' and bon_su>0 ) or  (lcwelding_date ='0000-00-00' and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 	                          
		}  
		   if($cursort==6) // 도장 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((mainpainting_date='0000-00-00' and bon_su>0 ) or  (lcpainting_date ='0000-00-00' and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 	      
		} 
		   if($cursort==7) // 조립 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((mainassembly_date='0000-00-00' and bon_su>0 ) or  (lcassembly_date ='0000-00-00' and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 	                          
		} 	
		   if($cursort==8) // 미제작List 선택시
		{	
			  $common=" where  (date(deadline)>=date(now())) and ((mainassembly_date='0000-00-00' and bon_su>0 ) or  (lcassembly_date ='0000-00-00' and lc_su>0 and (type!='011' and type!='012' and type!='013D' and type!='025' and type!='017' and type!='014'))) order by deadline asc, num desc ";  
			  $sql = "select * from mirae8440.ceiling " . $common; 	                          
			  $sqlcon = "select * from mirae8440.ceiling " . $common; 	                         
			  
		}  		

	
 }
$nowday=date("Y-m-d");   // 현재일자 변수지정   
   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
   //   print "$page&nbsp;$total_page&nbsp;$current_page&nbsp;$search&nbsp;$mode";
   		 
			?>
		 
<body >
 <div  class="container-fluid">
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
  <form name="board_form" id="board_form"  method="post" action="packinglist.php?mode=search&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=50">  
  
  <br>
  	  <button  type="button" class="btn btn-danger btn-lg btn-lg " onclick="location.href='./list.php';"> 이전화면  </button>&nbsp;&nbsp;&nbsp;&nbsp;
      <div class="clear"></div>
  <br>
  <br>
  			<div class="row">
		         <h4 class="display-4 text-left">
				<span style="color:green;">	&nbsp;&nbsp; 천장&L/C 포장 제작용 자료 List &nbsp;&nbsp;	&nbsp;&nbsp; 	 </span> 
				<button type="button" class="btn btn-Dark btn-lg btn-lg" onclick="show_list(2);"> 전체  </button>
				<button type="button" class="btn btn-success btn-lg btn-lg" onclick="show_list(1);"> 납품예정 </button>
				<button type="button" class="btn btn-danger btn-lg btn-lg" onclick="show_list(8);"> 미제작 </button> 
				  </h4> </div> 	 <br> 		

       <div class="row">  <h2 class="display-5 font-center text-center"> ▷ 총 <?= $total_row ?> 개의 자료 파일 &nbsp;&nbsp;	&nbsp;&nbsp;    <선택:   
	   <?php
	      switch($cursort)  {
			  case  1 :
		        print ' 납품예정 List ';
				break;			  
			  case  2 :
		        print ' 발주된 전체 List ';
				break;			  
			  case  3 :
		        print ' 레이져 미가공 List ';
				break;
			  case  4 :
		        print ' 절곡 미가공 List ';	
				break;				
			  case  5 :
		        print ' 제관 미가공 List ';		
				break;
			  case  6 :
		        print ' 미도장 List ';	
	            break;
			  case  7 :
		        print ' 미조립 List ';	
                break;			
			  case  8 :
		        print ' 미제작 List ';	
                break;					
		  }
	    ?>  >
	   
	     </h2>
      </div> <!-- end of list_search -->
         <div class="row">   <div class="col"> <h2 class="display-5 font-center text-center">
       <input type="text" name="search" id="search" value="<?=$search?>" size="12" >
		
			<input type="hidden" id="alerts" name="alerts" value="<?=$alerts?>" size="3" > 	
			<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="3" > 	
			<input type="hidden" id="page" name="page" value="<?=$page?>" size="3" > 	

   <button  type="button" class="btn btn-primary btn-lg btn-lg" onclick="process_list();"> 검색  </button>
	 
    </h2>   </div>   </div> <!-- end of list_search -->
	
             <SECTION>                                            
                    <TABLE class="c-table">	  					
		<div class="row">
			<tr>
      <div class="col-1"> <th> <h3 class="display-5 font-center text-center"> &nbsp; &nbsp;&nbsp;접수일 </h3> </th> </div>
      <div class="col-1"> <th> <h3 class="display-5 font-center text-center"> &nbsp; &nbsp;&nbsp;납기일 </h3> </th> </div>
      <div class="col-3"> <th> <h3 class="display-5 font-center text-center">   현 장 명 </h3> </th> </div>
	   
	   <?php 
	   
	     			print  '<div class="col-1"> <th> <h3 class="display-5 font-center text-center">   타입</h3> </th> </div>	   
						   <div class="col-1"> <th> <h3 class="display-5 font-center text-center">  인승 </h3> </th> </div>
						   <div class="col-1"> <th> <h3 class="display-5 font-center text-center">  수량</h3> </th> </div>
						   <div class="col-1"> <th> <h3 class="display-5 font-center text-center">  본천장</h3> </th> </div>
						   <div class="col-1"> <th> <h3 class="display-5 font-center text-center">  L/C</h3> </th> </div>
						   <div class="col-1"> <th> <h3 class="display-5 font-center text-center">  기타</h3> </th> </div>
						   <div class="col-1"> <th> <h3 class="display-5 font-center text-center">  공기청정기</h3> </th> </div>
						   <div class="col-2"> <th> <h3 class="display-5 font-center text-center">  Car Insize </h3> </th> </div>
						   <div class="col-3"> <th> <h3 class="display-5 font-center text-center">  비      고 </h3> </th> </div>'  ;
			?>

			</tr>
			
	  </div>	  
	  
	  <div class="row"> </div>

			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
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
			  $delivery=$row["delivery"];
			  $delipay=$row["delipay"];
			  
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

			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  $car_insize=$row["car_insize"];			  
			  $order_com1=$row["order_com1"];			  
			  $order_text1=$row["order_text1"];			  
			  $order_com2=$row["order_com2"];			  
			  $order_text2=$row["order_text2"];			  
			  $order_com3=$row["order_com3"];			  
			  $order_text3=$row["order_text3"];			  
			  $order_com4=$row["order_com4"];			  
			  $order_text4=$row["order_text4"];			  
			  $lc_draw=$row["lc_draw"];			  
			  $lclaser_com=$row["lclaser_com"];			  
			  $lclaser_date=$row["lclaser_date"];			  
			  $lcbending_date=$row["lcbending_date"];			  
			  $lcwelding_date=$row["lcwelding_date"];			  
			  $lcpainting_date=$row["lcpainting_date"];			  
			  $lcassembly_date=$row["lcassembly_date"];			  
			  $main_draw=$row["main_draw"];			  
			  $eunsung_make_date=$row["eunsung_make_date"];			  
			  $eunsung_laser_date=$row["eunsung_laser_date"];			  
			  $mainbending_date=$row["mainbending_date"];			  
			  $mainwelding_date=$row["mainwelding_date"];			  
			  $mainpainting_date=$row["mainpainting_date"];			  
			  $mainassembly_date=$row["mainassembly_date"];			  
			  $memo2=$row["memo2"];		
			  
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


   if((int)$lc_su>0&&$type!='011'&&$type!='012'&& $type!='013D'&&$type!='025'&&$type!='017'&&$type!='014') {
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
			  
							  
 if($orderday!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $orderday =iconv_substr($orderday,5,5,"utf-8") . $week[ date('w',  strtotime($orderday)  ) ] ;
 } 

if($deadline!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $deadline = iconv_substr($deadline,5,5,"utf-8") .$week[ date('w',  strtotime($deadline)  ) ] ;
	
}		  
			  $workplacename = "(".$secondord .")". $workplacename;

			 ?>
			 
				 <div class="row">
					<tr>
				   <div class="col-1"> <td> 
					<h3 class="display-5 font-center text-center" style="color:<?=$date_font?>;">
					 <?=$orderday?>  </h3> 
					</td> </div>

		   <div class="col-1"> <td> 
					<h3 class="display-5 font-center text-center" style="color:<?=$date1_font?>;">
					 <?=$deadline?>  </h3>
					</td> </div>
					
  		   <div class="col-3"> <td> 
				 		<h2 class="display-5 ">				   	 			        
				   	 <?=$workplacename?>				        						  
						  </h2>
					</td> </div>			
	<?php				
	                 $memo = $memo . $memo2;
		
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $type . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $inseung . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $su . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $bon_su . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $lc_su . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $etc_su . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $air_su . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $car_insize . ' </h2> </td> </div> ';
					  print '<div class="col-1"> <td > <h2 class="display-5" > '. $memo . ' </h2> </td> </div> ';  			  
        
				?>					
				 </tr>            
				 </div>						
			
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
 
      </TABLE>    
                </SECTION>  
				
	   <div id="vacancy" style="display:none">  </div>
						    <div class="row"> &nbsp; </div>
						    <div class="row"> &nbsp; </div>
       <div id="page_button">
	   
					
					
    <div class="row"> <div class="col"> <h5 class="display-3 font-center text-center">
 <?php
      if($page!=1 && $page>$page_scale)
      {
        $prev_page = $page - $page_scale;    
        // 이전 페이지값은 해당 페이지 수에서 List에 표시될 페이지수 만큼 감소
        if($prev_page <= 0) 
            $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
        print "<a href=list.php?page=$prev_page&mode=search&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&cursort=$cursort>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=list.php?page=$i&mode=search&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&cursort=$cursort>[$i]</a>";
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=list.php?page=$next_page&mode=search&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&cursort=$cursort> ▶</a><p>";
      }
 ?>			
 </h5>
        </div>
     </div>
				 
     </div>
	</form>
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->

<script>    

$(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});  

$(function () {
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});			
});
 
function pre_year(){   // 전년도 추출
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 
if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
yyyy=yyyy-1;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
}  

function pre_month(){
	document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10) {
    dd='0'+dd;
} 

mm=mm-1;
if(mm<1) {
    mm='12';
} 
if(mm<10) {
    mm='0'+mm;
} 
if(mm>=12) {
    yyyy=yyyy-1;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_year(){   // 당해년도
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 
function this_month(){   // 당해월
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function From_tomorrow(){   // 익일 이후
var today = new Date();
var dd = today.getDate()+1;  // 하루를 더해준다. 익일
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 
frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-12-31';	
    document.getElementById("fromdate").value = frompreyear;   
    document.getElementById("todate").value = topreyear;       
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 



function Fromthis_today(){   // 금일이후
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_today(){   // 금일
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_tomorrow(){   // 익일

document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate()+1;
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  
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

</script>
<?php
if($mode==""&&$fromdate==null)  
{
  echo ("<script language=javascript> this_year();</script>");  // 당해년도 화면에 초기세팅하기
}
?>
</body>
<script> 
/*
  $(document).ready(function(){
  $("#flip").click(function(){
    $("#panel").slideToggle();
  });
});

$(document).ready(function(){
  $("#panel").click(function(){
    $("#panel").slideUp("slow");
  }); 
});  */

</script>  
</html>