<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>

<?php

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header("Location:http://order119.000webhostapp.com/login/logout.php"); 
         exit;
   }   

//header("Refresh:0");  // reload refresh   
 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/output.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 통합 정보 시스템 </title> 
 </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
 <script>
   $(document).ready(function() { 
	$("input:radio[name=separate_date]").click(function() { 
	process_list(); 
	}) 
   }); 
		</script>
 <?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	 else
		 	 $search="";
 
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
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
  if(isset($_REQUEST["cursort"]))
         $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 if(isset($_REQUEST["sortof"]))
    {
     $sortof=$_REQUEST["sortof"];  // 클릭해서 넘겨준 값
	if($sortof==1) {
		
	 if($cursort!=1)
	    $cursort=1;
      else
	     $cursort=2;
	    } 
	if($sortof==2) {     //접수일 클릭되었을때
		
	 if($cursort!=3)
	    $cursort=3;
      else
		 $cursort=4;			
	   }	   
	if($sortof==3) {     //구분 클릭되었을때
		
	 if($cursort!=5)
	    $cursort=5;
      else
		 $cursort=6;			
	   }	   	   
	if($sortof==4) {     //절곡 클릭되었을때
		
	 if($cursort!=7)
	    $cursort=7;
      else
		 $cursort=8;			
	   }	   
	if($sortof==5) {     //모터 클릭되었을때
		
	 if($cursort!=9)
	    $cursort=9;
      else
		 $cursort=10;			
	   }		   
	}   
  else 
  {
     $sortof=0;     
	 $cursort=0;
  }
 
  if(isset($_REQUEST["separate_date"]))
        $separate_date=$_REQUEST["separate_date"];    // 현재 정렬모드 지정 
	     else
		        $separate_date="";    // 현재 정렬모드 지정  
  if(isset($_REQUEST["find"]))
        $find=$_REQUEST["find"];    // 현재 정렬모드 지정 
	     else
		        $find="";    // 현재 정렬모드 지정  	
  if(isset($_REQUEST["yearcheckbox"]))
        $yearcheckbox=$_REQUEST["yearcheckbox"];    // 현재 정렬모드 지정 
	     else
		        $yearcheckbox="";    // 현재 정렬모드 지정  
  if(isset($_REQUEST["year"]))
        $year=$_REQUEST["year"];    // 현재 정렬모드 지정 
	     else
		        $year="";    // 현재 정렬모드 지정  
  if(isset($_REQUEST["asprocess"]))
        $asprocess=$_REQUEST["asprocess"];    // 현재 정렬모드 지정 
	     else
		        $asprocess="";    // 현재 정렬모드 지정  			
			
 
 // 기간을 정하는 구간
 
  if(isset($_REQUEST["fromdate"]))
        $fromdate=$_REQUEST["fromdate"];    
	else
		$fromdate="";
  if(isset($_REQUEST["todate"]))
        $todate=$_REQUEST["todate"];   	
	else
		$todate="";		
 
if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
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
 
$process="전체";  // 기본 전체로 정한다.
/*  
$a=" order by outdate desc limit $first_num, $scale";  
$b=" order by outdate desc"; */

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";


$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
$c= $common . " asc, num desc limit $first_num, $scale";    //오름차순
$d= $common . " asc, num desc ";    //오름차순 전체

$where=" where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
$all=" limit $first_num, $scale";
  
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from id11707003_chandj.output " . $a; 					
	                         $sqlcon = "select * from id11707003_chandj.output " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from id11707003_chandj.output where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sql .="or (orderman like '%$search%') or (outputplace like '%$search%') or (receiver like '%$search%') or";
							  $sql .=" (phone like '%$search%') or (comment like '%$search%') order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from id11707003_chandj.output where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sqlcon .="or (orderman like '%$search%') or (outputplace like '%$search%') or (receiver like '%$search%') or";
							  $sqlcon .=" (phone like '%$search%') or (comment like '%$search%') order by " . $SettingDate . "  desc, num desc ";							  
						}

               }
  if($mode=="") {
							 $sql="select * from id11707003_chandj.output " . $a; 					
	                         $sqlcon = "select * from id11707003_chandj.output " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
	
		   
if($cursort==1)
{	
          					 $sql="select * from id11707003_chandj.output  " . $c;          
          					 $sqlcon="select * from id11707003_chandj.output  " . $d;          
}  
 
if($cursort==2)
{	
                             $sql="select * from id11707003_chandj.output   " . $a;           
          					 $sqlcon="select * from id11707003_chandj.output " . $b;         
}  
if($cursort==3) // 접수일 클릭시 정렬
{	
                             $sql="select * from id11707003_chandj.output " . $where . " order by indate desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output " . $where . " order by indate desc, num desc  " ;            
}  
if($cursort==4) // 접수일 클릭시 정렬
{	
                             $sql="select * from id11707003_chandj.output " . $where . " order by indate asc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output " . $where . " order by indate asc, num desc  " ;             
}  
if($cursort==5) // 구분 클릭시 주일/경동 내림 정렬
{	
                             $sql="select * from id11707003_chandj.output " . $where . " order by root desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output " . $where . " order by root desc, " . $SettingDate . "  desc, num desc  " ;         
}     

if($cursort==6) // 구분 클릭시 주일/경동 오름차순 정렬
{	
                             $sql="select * from id11707003_chandj.output" . $where . " order by root asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output" . $where . " order by root asc, " . $SettingDate . "  desc , num desc " ;       
}        
if($cursort==7) // 절곡 클릭시 내림 정렬
{	
                             $sql="select * from id11707003_chandj.output" . $where . " order by steel desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output" . $where . " order by steel desc, " . $SettingDate . "  desc, num desc  ";         
}        
if($cursort==8) // 절곡 클릭시 오름차순 정렬
{	
                             $sql="select * from id11707003_chandj.output" . $where . " order by steel asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output" . $where . " order by steel asc, " . $SettingDate . "  desc, num desc  ";     
}           
if($cursort==9) // 모터 클릭시 내림 정렬
{	
                             $sql="select * from id11707003_chandj.output" . $where . " order by motor desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output" . $where . " order by motor desc, " . $SettingDate . "  desc, num desc  " ;          
}        
if($cursort==10) // 모터 클릭시 오름차순 정렬
{	
                             $sql="select * from id11707003_chandj.output" . $where . " order by motor asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from id11707003_chandj.output" . $where . " order by motor asc, " . $SettingDate . "  desc, num desc  " ;       
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
			 
			 
$total_sum=0;
$total_m2=0;			 
			?>
		 
<body >
 <div id="wrap">
   <div id="header">
	 <?php include "../lib/top_login2.php"; ?>
   </div>
   <div id="menu">
	 <?php include "../lib/top_menu2.php"; ?>
   </div>
   <div id="content">			 
<div id="col2">
  <form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=10000">  
  <div id="col2">     
	 <div id="title"><img src="../img/title_output.png"> </div>	 <div id="top_board1"> 스크린 총 틀수 :  </div>  <div id="top_board2"> <?=$total_sum?>  </div> <div id="top_board3"> 스크린 면적(㎡) 합 :  </div> <div id="top_board4"> <?=$total_m2?> </div> <div id="top_board5">
	 	<?php
   if(isset($_SESSION["userid"]))
   {
  ?>
   <a href="write_form.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"> <img src="../img/write.png"></a>
  <?php
   }
  ?>
	 </div>
	 
      <div class="clear"></div>	 
 <!-- <div id="title2">
 <div id class="blink"  style="white-space:nowrap">  <font color=red> *****  AS 진행 현황 ***** </font> </div>
	  </div>  -->

        <div id="list_search">
        <div id="list_search1"> <br> ▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다.</div>
        <div id="list_search111"> 
			 <?php
	    if($separate_date=="1") {
			 ?>
			&nbsp; 출고일 <input type="radio" checked name="separate_date" value="1">
			&nbsp; 접수일 <input type="radio" name="separate_date" value="2">
			<?php
             		}    ?>	 
			 <?php
	    if($separate_date=="2") {
			 ?>
			&nbsp; 출고일 <input type="radio"  name="separate_date" value="1">
			&nbsp; 접수일 <input type="radio" checked name="separate_date" value="2">
			<?php
             		}    ?>	 		

       <input id="preyear" type='button' onclick='pre_year()' value='전년도'>	 
	   <input id ="premonth" type='button' onclick='pre_month()' value='전월'>	 
       <input type="text" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">부터	   
       <input type="text" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">까지
  
	   <input id ="thistoday" type='button' onclick='this_today()' value='금일'>
	   <input id ="Fromthistoday" type='button' onclick='Fromthis_today()' value='금일이후~'>	   
	   <input id ="tomorrow" type='button' onclick='this_tomorrow()' value='익일'>
	   <input id ="Fromtomorrow" type='button' onclick='From_tomorrow()' value='익일이후~'>	   
	   <input id ="thismonth" type='button' onclick='this_month()' value='당월'>
<!--      <input id ="thisyear" type='button' onclick='this_year()' value='당해년도'>		 						-->
       </div>		
        <div id="list_search2"> <img src="../img/select_search.gif"></div>
        <div id="list_search3">
        <select name="find">
           <?php		  
		      if($find=="")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='outworkplace'>현장명</option>
           <option value='firstord'>발주처</option>

		   <?php
			  } ?>		
		  <?php		  
		      if($find=="all")
			  {			?>	  
           <option value='all' selected>전체</option>
           <option value='outworkplace'>현장명</option>

		   <?php
			  } ?>
		  <?php
		      if($find=="outworkplace")
			  {			?>	  
           <option value='all' >전체</option>
           <option value='outworkplace' selected>현장명</option>
		   <?php
			  } ?>			  		  			  
        </select>
				
		</div> <!-- end of list_search3 -->

        <div id="list_search4"><input type="text" name="search" id="search" value="<?=$search?>"> </div>

        <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div>

    <a href="#" onclick="window.open('print_list.php??mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>','출고리스트','left=20,top=20, scrollbars=yes, toolbars=no,width=1300,height=870');" border="0"> 
    &nbsp;&nbsp; 	경동/대신 출고List</a>    &nbsp;
    <a href="#" onclick="window.open('print_list_all.php??mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>','출고리스트','left=20,top=20, scrollbars=yes, toolbars=no,width=1300,height=870');" border="0"> 
    &nbsp; ListAll</a>	    &nbsp;
   <a href="#" onclick="window.open('print_exceptdone.php??mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>','출고리스트','left=20,top=20, scrollbars=yes, toolbars=no,width=1300,height=870');" border="0"> 
    &nbsp; 완료제외</a>	
      </div> <!-- end of list_search -->
      <div class="clear"></div>
      <div id="output_top_title">
      <div id="output_title1"> 번호 </div>
      <div id="output_title2"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=1&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 출고일자 </a> </div>     <!-- 출고일자 -->
      <div id="output_title3"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=2&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 접 수 일 </a> </div>     <!-- 접수일 -->
      <div id="output_title33"> 진행 </div>         <!-- 접수상태 표시 -->
      <div id="output_title4">  <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=3&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">구분 </a> </div>         <!-- 주일/경동 -->
      <div id="output_title5"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=4&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">절곡 </a> </div>       <!-- 절곡발주 -->
      <div id="output_title6"><a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=5&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 모터 </a> </div>     
      <div id="output_title7"> 현 장 명 </div>     
      <div id="output_title8"> 수신처 </div>     
      <div id="output_title13"> 운송방식 </div>     
      <div id="output_title9"> 수신 주소 </div>     
      <div id="output_title10"> 수신연락처   </div>      
      <div id="output_title11"> 담당  </div> 
      <div id="output_title16"> 스크린틀수 </div> 
      <div id="output_title15"> 스크린면적(㎡)  </div> 
      <div id="output_title12"> 비 고    </div>      
      </div>
      <div id="list_content">
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $item_num=$row["num"];
			  $outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];	  
			  $root=$row["root"];	  
			  $steel=$row["steel"];	  
			  $motor=$row["motor"];	  
			  $delivery=$row["delivery"];	  
			  $regist_state=$row["regist_state"];	 
		if($regist_state==null)
			 $regist_state="1";
		 
		if($steel=="1")
              	$steel="절곡";			
        if($motor=="1")
                $motor="모터";			
          
		   if($root=="주일") 
                            $root_font="black";
				else			
						    $root_font="blue";						

			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="red";
						}

        /*        $state_as=0;    // AS 색상 등 표현하는 계산 
			  if(substr($row["asday"],0,2)=="20") $state_as=1;
			  if(substr($row["asproday"],0,2)=="20") $state_as=2;
			  if(substr($row["setdate"],0,2)=="20") $state_as=3;
			  if(substr($row["asendday"],0,2)=="20") $state_as=4;		

              if($asday=='0000-00-00') $asday=""; 			  
              if($asproday=='0000-00-00') $asproday=""; 			  
              if($setdate=='0000-00-00') $setdate=""; 			  
			  
			  $font_as="black";
			  switch ($state_as) {
							case 1: $state_astext="접수중"; $font_as="blue"; break;
							case 2: $state_astext="처리예약"; $font_as="grey"; break;
							case 3: $state_astext="세팅예약"; $font_as="green"; break;
							case 4: $state_astext="처리완료"; $font_as="red"; break;							
							default: $state_astext="미접수"; 
						}
						      */
												
								$font="black";
							switch ($delivery) {
								case   "상차(선불)"             :  $font="black"; break;
								case   "상차(착불)"              :$font="grey" ; break;
								case   "경동화물(선불)"          :$font="brown"; break;
								case   "경동화물(착불)"          :$font="brown"; break;
								case   "경동택배(선불)"          :$font="brown"; break;
								case   "경동택배(착불)"          :$font="brown"; break;
								case  "직접배차"                 :$font="purple"; break;
								case  "직접수령"                 :$font="red"; break;
								case  "대신화물(선불)"           :$font="blue"; break; 
								case  "대신화물(착불)"           :$font="blue"; break;
								case  "대신택배(선불)"           :$font="blue"; break;
								case  "대신택배(착불)"           :$font="blue"; break;
							}	
							
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
		

		
			 ?>
				<div id="outlist_item" > 
			    <div id="outlist_item1"><a href="view.php?num=<?=$item_num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" >
				<?=$start_num ?></div>			
			    <div id="outlist_item2" style="color:<?=$date_font?>;">
				<b> <?=iconv_substr($outdate,0,15, "utf-8")?></b></div>

				 <?php						    // 접수일이 당일이면 깜빡이는 효과부여
				
				if($item_indate==date("Y-m-d",time()))  // 보라색 굵은 글씨체로 당일 해당 접수된 것...
			        {
  			//	print '<div id class="blink" style="white-space:nowrap; color:green;" >';
  				print '<div id="outlist_item3" style=" color:red;">';
								}
								else
								{
									print '<div id="outlist_item3">';
								}
				?>
			     <?=iconv_substr($item_indate,0,10, "utf-8")?>
				
					 </div> 
				<div id="outlist_item33"style="color:<?=$font_state?>;" > 				
			<?php 
                			if($regist_word=="등록") 
								  print '<div id class="blink"  style="white-space:nowrap"> ' . $regist_word . ' </div>';
				              else
								  print  $regist_word;
				?>
                        </div> 
				<div id="outlist_item4"style="color:<?=$root_font?>;" ><b> <?=$root?></b> </div>
				<div id="outlist_item5" style="color:green"><b> <?=$steel?></b></div>				
				<div id="outlist_item6" style="color:purple"><b> <?=$motor?> </b></div>				
				<div id="outlist_item7"> 
				
				<?=iconv_substr($item_outworkplace,0,14, "utf-8")?> </div>
				<div id="outlist_item8"><?=iconv_substr($item_receiver,0,12, "utf-8")?></div>
				<div id="outlist_item13" style="color:<?=$font?>;" ><?=iconv_substr($delivery,0,20, "utf-8")?></div>				
				<div id="outlist_item9"><?=iconv_substr($item_outputplace,0,23, "utf-8")?></div>
				<div id="outlist_item10"><?=iconv_substr($item_phone,0,12, "utf-8")?></div>
				<div id="outlist_item11"><?=iconv_substr($item_orderman,0,4, "utf-8")?></div> 
		<?php
		
		
// 면적구하는 부분
// 출고번호를 추적한다. 
$upnum=$item_num;
$sqlTemp_orderlist="select * from id11707003_chandj.orderlist  where outputnum='$item_num' limit 1";	 // 처음 내림차순

  
  try{  
	  $stmh_temp = $pdo->query($sqlTemp_orderlist);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $m2=0;
	  $sum=0;
	       while($row_temp = $stmh_temp->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row_temp["num"];			  
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
 $sqlTemp="select * from id11707003_chandj.make  where upnum='$upnum' order by num desc";	 // 처음 내림차순 
 $parentnum=$upnum; 
	 try{  
	  $m_array=array();
	  $stmh_temp = $pdo->query($sqlTemp);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $m2=0;
	  $sum=0;
	       while($row_temp = $stmh_temp->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row_temp["upnum"];			  
if((int)$upnum==(int)$parentnum)
      {	
			  $counter++;
			 $number=$row_temp["number"];  
			 $x=(int)$row_temp["cutwidth"];  
			 $y=(int)$row_temp["cutheight"];  
			 $z=(int)$row_temp["number"];  
			 $m_array[$counter]=$x/1000 * $y/1000 * $z;
			 $m2 += $m_array[$counter];
			 $sum+=(int)$number;
   			 }
	   }		
     $total_sum+=$sum;   // 화면누계작성
     $total_m2+=$m2;
     $total_m2=number_format((float)$total_m2, 2, '.', '');	 
	   
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
 $m2=number_format((float)$m2, 2, '.', '');
			if($sum>0) 
				print '<div id="outlist_item16">' . iconv_substr($sum,0,3, "utf-8") . '</div>';
				else
					print '<div id="outlist_item16">  &nbsp </div>'; 

			if($m2>0) 
				print '<div id="outlist_item15">' . iconv_substr($m2,0,7, "utf-8") . '</div>';
				else
					print '<div id="outlist_item15">  &nbsp </div>';
				
				?> 
				
				<div id="outlist_item12"><?=iconv_substr($item_comment,0,30, "utf-8")?></div> </a>
		        <div class="clear"> </div>
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
 
       <div id="page_button">
	<div id="page_num">  
 <?php
      if($page!=1 && $page>$page_scale)
      {
        $prev_page = $page - $page_scale;    
        // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
        if($prev_page <= 0) 
            $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
        print "<a href=list.php?page=$prev_page&mode=search&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=list.php?page=$i&mode=search&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date>[$i]</a>";
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=list.php?page=$next_page&mode=search&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date> ▶</a><p>";
      }
 ?>			
        </div>
     </div>


  <br><br><br>

     </div>
	</form>
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->
<script>



function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);


/* $(document).ready(function() { 
	$("input:radio[name=separate_date]").click(function() { 
	process_list(); 
	}) 
); */

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

function process_list(){   // 접수일 출고일 라디오버튼 클릭시

document.getElementById('search').value=null; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
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
  // 스크린합 구한값 화면에 출력하기
var total_sum = '<?php echo $total_sum ;?>';
var total_m2 = '<?php echo $total_m2 ;?>';
$("#top_board2").text(total_sum);
$("#top_board4").text(total_m2);
</script>
  </html>