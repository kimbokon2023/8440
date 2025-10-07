<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh   
 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/output.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 주일기업 통합정보시스템 </title> 
 </head>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
  if(isset($_REQUEST["separate_date"]))   //출고일 접수일
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
 
  $scale = 20;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
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
  
  if($separate_date=="") $separate_date="1";
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

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
							 $sql="select * from chandj.bendinglist " . $a; 					
	                         $sqlcon = "select * from chandj.bendinglist " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from chandj.bendinglist where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sql .="or (orderman like '%$search%') or (outputplace like '%$search%') or (receiver like '%$search%') or";
							  $sql .=" (phone like '%$search%') or (comment like '%$search%') order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from chandj.bendinglist where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sqlcon .="or (orderman like '%$search%') or (outputplace like '%$search%') or (receiver like '%$search%') or";
							  $sqlcon .=" (phone like '%$search%') or (comment like '%$search%') order by " . $SettingDate . "  desc, num desc ";							  
						}

               }
  if($mode=="") {
							 $sql="select * from chandj.bendinglist " . $a; 					
	                         $sqlcon = "select * from chandj.bendinglist " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
	
		   
if($cursort==1)
{	
          					 $sql="select * from chandj.bendinglist  " . $c;          
          					 $sqlcon="select * from chandj.bendinglist  " . $d;          
}  
 
if($cursort==2)
{	
                             $sql="select * from chandj.bendinglist   " . $a;           
          					 $sqlcon="select * from chandj.bendinglist " . $b;         
}  
if($cursort==3) // 접수일 클릭시 정렬
{	
                             $sql="select * from chandj.bendinglist " . $where . " order by indate desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist " . $where . " order by indate desc, num desc  " ;            
}  
if($cursort==4) // 접수일 클릭시 정렬
{	
                             $sql="select * from chandj.bendinglist " . $where . " order by indate asc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist " . $where . " order by indate asc, num desc  " ;             
}  
if($cursort==5) // 구분 클릭시 주일/경동 내림 정렬
{	
                             $sql="select * from chandj.bendinglist " . $where . " order by root desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist " . $where . " order by root desc, " . $SettingDate . "  desc, num desc  " ;         
}     

if($cursort==6) // 구분 클릭시 주일/경동 오름차순 정렬
{	
                             $sql="select * from chandj.bendinglist" . $where . " order by root asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist" . $where . " order by root asc, " . $SettingDate . "  desc , num desc " ;       
}        
if($cursort==7) // 절곡 클릭시 내림 정렬
{	
                             $sql="select * from chandj.bendinglist" . $where . " order by steel desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist" . $where . " order by steel desc, " . $SettingDate . "  desc, num desc  ";         
}        
if($cursort==8) // 절곡 클릭시 오름차순 정렬
{	
                             $sql="select * from chandj.bendinglist" . $where . " order by steel asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist" . $where . " order by steel asc, " . $SettingDate . "  desc, num desc  ";     
}           
if($cursort==9) // 모터 클릭시 내림 정렬
{	
                             $sql="select * from chandj.bendinglist" . $where . " order by motor desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist" . $where . " order by motor desc, " . $SettingDate . "  desc, num desc  " ;          
}        
if($cursort==10) // 모터 클릭시 오름차순 정렬
{	
                             $sql="select * from chandj.bendinglist" . $where . " order by motor asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from chandj.bendinglist" . $where . " order by motor asc, " . $SettingDate . "  desc, num desc  " ;       
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

             $total_arr_sum=array();
			 
		 	 for($j=1;$j<=9;$j++)    // 총합계 배열 초기화
			 {
			  		  $total_arr_sum[$j] = null;	 					  
				 }				 
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
  <form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>">  
  <div id="col2">     
	 <div id="title"> <h1> 절곡원가 분석자료 </h1> </div>	 <div id="top_board_all" >  </div>
	 <!--    <div id="top_board0" > <a href="bendingdatalist.php" target="_blank" > <img src="../img/movetosteeldata.jpg"> </a>  </div>  -->
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
	   <input id ="tomorrow" type='button' onclick='this_tomorrow()' value='익일'>
	   <input id ="Fromthistoday" type='button' onclick='Fromthis_today()' value='금일이후~'>
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

      </div> <!-- end of list_search -->
      <div class="clear"></div>
      <div id="make_output_top_title">
      <div id="make_output_title1"> 번호 </div>
      <div id="make_output_title2"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=1&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 출고일자 </a> </div>     <!-- 출고일자 -->
      <div id="make_output_title3"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=2&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 접 수 일 </a> </div>     <!-- 접수일 -->

      <div id="make_output_title7"> 현 장 명 </div>     
      <div id="make_output_title8"> 수신처 </div>     
      <div id="make_output_title9"> 수신 주소 </div>     
      <div id="make_output_title10"> 수신연락처   </div>      
      <div id="make_output_title11"> 발주담당  </div> 
      <div id="make_output_title21">  EGI1.6T(㎡)  </div> 
      <div id="make_output_title22">  EGI1.2T(㎡)  </div> 
      <div id="make_output_title23">  EGI0.8T(㎡)  </div> 
      <div id="make_output_title24">  H/L1.5T(㎡)  </div> 
      <div id="make_output_title25">  H/L1.2T(㎡)   </div> 
      <div id="make_output_title26">  GI0.45T(㎡)  </div> 
      <div id="make_output_title27">   GI0.8T(㎡)  </div> 

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
					

			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="red";
						}

					
							  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}  

// 면적구하는 부분

$parentnum=$item_num;
$sqlTemp="select * from chandj.bending_write  where upnum='$item_num' order by num desc";	 // 처음 내림차순
  
	 try{  
	  $m_array=array();
	  $stmh_temp = $pdo->query($sqlTemp);            // 검색조건에 맞는글 stmh
	       $sum=0;
  
		   $counter=0;

           $display_text="";	

					  $options = array(); 
					  $options[1] = 'EGI1.6T'; 
					  $options[2] = 'EGI1.2T'; 
					  $options[3] = 'EGI0.8T'; 
					  $options[4] = 'SUS H/L 1.5T'; 
					  $options[5] = 'SUS H/L 1.2T'; 					
					  $options[6] = 'GI0.45T'; 					
					  $options[7] = 'GI0.8T'; 					
					  $options[8] = 'Mirror 1.5'; 					
					  $options[9] = 'Mirror 1.2'; 	
					  
	     while($row_temp = $stmh_temp->fetch(PDO::FETCH_ASSOC)) {          

  		   $counter++;
     	   $total_text=$row_temp["total_text"]; 

   
     $arr_count++; 

     $jb = explode( ',', $total_text );	 
//	 print $jb[0] . "   " . $jb[1];   // 분할된 것 화면에 보여주기 테스트용
	 $total_count=count($jb);            // 배열의 수를 파악해서 반복문을 만든다.
	 
  
	 for($i=1;$i<=$total_count;$i++)
	 {
		      $tmp = explode( '=', $jb[$i-1] );	 
		 	 for($j=1;$j<=9;$j++)    // 합계 배열에 누적 	  
			 {
				 if($tmp[0]==$options[$j]) {					  
			  		  $steel_arr_sum[$j] = $steel_arr_sum[$j] + (float)$tmp[1];	 					  
				 }
				 
			 }
			 
	 }
	 	 
	
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }      	  

 // $m2=number_format((float)$m2, 2, '.', '');
			  
			 ?>
				<div id="make_outlist_item" > 
			    <div id="make_outlist_item1"><a href="write_form.php?mode=modify&num=<?=$item_num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&callback=1" >
				<?=$start_num ?></div>			
			    <div id="make_outlist_item2" style="color:<?=$date_font?>;">
				<b> <?=substr($outdate,0,15)?></b></div>

				 <?php						    // 접수일이 당일이면 깜빡이는 효과부여
				
				if($item_indate==date("Y-m-d",time()))  // 보라색 굵은 글씨체로 당일 해당 접수된 것...
			        {
  			//	print '<div id class="blink" style="white-space:nowrap; color:green;" >';
  				print '<div id="make_outlist_item3" style=" color:red;">';
								}
								else
								{
									print '<div id="make_outlist_item3">';
								}
				?>
			     <?=substr($item_indate,0,10)?>
				
					 </div>
		
				<div id="make_outlist_item7"> 
				
				<?=iconv_substr($item_outworkplace,0,14,"utf-8")?> </div>
				<div id="make_outlist_item8"><?=iconv_substr($item_receiver,0,15,"utf-8")?></div>
		
				<div id="make_outlist_item9"><?=iconv_substr($item_outputplace,0,22,"utf-8")?></div>
				<div id="make_outlist_item10"><?=substr($item_phone,0,25)?></div>
				<div id="make_outlist_item11"><?=substr($item_orderman,0,10)?></div> 
				<div id="make_outlist_item21"><?=$steel_arr_sum[1]?></div> 
				<div id="make_outlist_item22"><?=$steel_arr_sum[2]?></div> 
				<div id="make_outlist_item23"><?=$steel_arr_sum[3]?></div> 
				<div id="make_outlist_item24"><?=$steel_arr_sum[4]?></div> 
				<div id="make_outlist_item25"><?=$steel_arr_sum[5]?></div> 
				<div id="make_outlist_item26"><?=$steel_arr_sum[6]?></div> 
				<div id="make_outlist_item27"><?=$steel_arr_sum[7]?></div> 				
			    
				</a>
		        <div class="clear"> </div>
				</div>
			<?php
			$start_num--;
		 	 for($j=1;$j<=9;$j++)    // 합계 배열 초기화			 
			 {
			  		  $total_arr_sum[$j] += $steel_arr_sum[$j];	 					  
			  		  $steel_arr_sum[$j] = null;	 					  
				 }			
			
			
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
        print "<a href=list.php?page=$prev_page&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=list.php?page=$i&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year>[$i]</a>";
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=list.php?page=$next_page&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year> ▶</a><p>";
      }
 ?>			
        </div>
     </div>

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
</script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>    
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

</script>
<?php
if($mode==""&&$fromdate==null)  
{
  echo ("<script language=javascript> this_year();</script>");  // 당해년도 화면에 초기세팅하기
}
?>
</body>
<script> 
var options = new Array();
options[1] = 'EGI1.6T'; 
options[2] = 'EGI1.2T'; 
options[3] = 'EGI0.8T'; 
options[4] = 'H/L1.5T'; 
options[5] = 'H/L1.2T'; 					
options[6] = 'GI0.45T'; 					
options[7] = 'GI0.8T'; 					
options[8] = 'Mirror 1.5'; 					
options[9] = 'Mirror 1.2'; 	  
  
var total_sum = new Array();
total_sum[1] = '<?php echo $total_arr_sum[1] ;?>';
total_sum[2] = '<?php echo $total_arr_sum[2] ;?>';
total_sum[3] = '<?php echo $total_arr_sum[3] ;?>';
total_sum[4] = '<?php echo $total_arr_sum[4] ;?>';
total_sum[5] = '<?php echo $total_arr_sum[5] ;?>';
total_sum[6] = '<?php echo $total_arr_sum[6] ;?>';
total_sum[7] = '<?php echo $total_arr_sum[7] ;?>';
var tmp="";

for(var i=1;i<=7;i++)
{
	 if(Number(total_sum[i])==0) 
	 {
		  total_sum[i]="";	  
	 }
	 else
	 {
		 tmp=tmp + options[i] +": " + total_sum[i] + "(㎡), ";
	 }
	 
} 

$("#top_board_all").text(tmp);
</script>  
</html>