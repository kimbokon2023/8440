<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
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
 <link rel="stylesheet" type="text/css" href="../css/divide.css">
 <link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>

<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 미래기업 통합정보시스템 </title> 
 </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

 <script>
   $(document).ready(function() { 
	$("input:radio[name=separate_date]").click(function() { 
	process_list(); 
	}) 
   }); 
   
   $(document).ready(function(){

	    $('.more').click(function(){
    if($('.more').hasClass('more')){
       $('.more').addClass('close').removeClass('more');
       $('.board').css('visibility', 'visible');
	    $('.board').show(); 
    }else if($('.close').hasClass('close')){
       $('.close').addClass('more').removeClass('close');  
       $('.board').css('visibility', 'hidden');
	   $('.board').hide(); 
    }
  });

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
	if($sortof==1) {      //접수일 클릭되었을때
		
	 if($cursort!=1)   
	    $cursort=1;
      else
	     $cursort=2;
	    } 
	if($sortof==2) {   
		
	 if($cursort!=3)
	    $cursort=3;
      else
		 $cursort=4;			
	   }	   
	if($sortof==3) {    
		
	 if($cursort!=5)
	    $cursort=5;
      else
		 $cursort=6;			
	   }	   	   
	if($sortof==4) {    
		
	 if($cursort!=7)
	    $cursort=7;
      else
		 $cursort=8;			
	   }	   
	if($sortof==5) {    
		
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

	 $sql="select * from mirae8440.dividesource"; 					

	 try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $divide_num=array();
   $divide_item=array();
   $divide_holes=array();
   $divide_spec=array();
   $divide_steelnum=array();
   $divide_item=array();
   $divide_spec=array();
   $spec_arr=array();
   $last_item="";
   $last_spec="";
   $pass='0';
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

	   
 			  $divide_num[$counter]=$row["num"];			  
 			  $divide_item[$counter]=$row["item"];
 			  $divide_spec[$counter]=$row["spec"];
		      $divide_holes[$counter]=$row["holes"];   			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
 // 전체합계(입고부분)를 산출하는 부분 

$sum=array();

$sql="select * from mirae8440.divide " . $b; 	
 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $holes=$row["holes"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  $tmp=$item . $holes . $spec;
	
        for($i=0;$i<=$rowNum;$i++) {  // 입고부분 수량 합계
			 			  
 			  
	          $sum_tmp=$divide_item[$i] . $divide_holes[$i] . $divide_spec[$i];
			  if($which=='1' and $tmp==$sum_tmp)
				    $sum[$i]=$sum[$i] + (int)$steelnum;			
		           }
	
			  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$sql="select * from mirae8440.divide " . $b; 	 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  $holes=$row["holes"];			  
			  
			  $tmp=$item . $holes . $spec;
	
        for($i=0;$i<=$rowNum;$i++) {
			 			  
 			  
	          $sum_tmp=$divide_item[$i] . $divide_holes[$i] . $divide_spec[$i];
			  if($which=='2' and $tmp==$sum_tmp)
				    $sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  



/*
  
         for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	print $sum_title[$i] . "  수량 : " ;
	print $sum[$i];
	
		           }
	 
  */
  
  
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from mirae8440.divide " . $a; 					
	                         $sqlcon = "select * from mirae8440.divide " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.divide where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sql .="or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from mirae8440.divide where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sqlcon .="or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') order by " . $SettingDate . " desc, num desc ";
						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.divide " . $a; 					
	                         $sqlcon = "select * from mirae8440.divide " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   



 		   
if($cursort==1)
{	
          					 $sql="select * from mirae8440.divide order by outdate desc limit $first_num, $scale" ;
          					 $sqlcon="select * from mirae8440.divide  order by outdate desc " ;          
}  
 
if($cursort==2)
{	
          					 $sql="select * from mirae8440.divide order by outdate asc limit $first_num, $scale" ;
          					 $sqlcon="select * from mirae8440.divide  order by outdate asc " ;            
}  
if($cursort==3) // 접수일 클릭시 정렬
{	
          					 $sql="select * from mirae8440.divide order by indate desc limit $first_num, $scale" ;
          					 $sqlcon="select * from mirae8440.divide  order by indate desc " ;           
}  
if($cursort==4) // 접수일 클릭시 정렬
{	
          					 $sql="select * from mirae8440.divide order by indate asc limit $first_num, $scale" ;
          					 $sqlcon="select * from mirae8440.divide  order by indate asc " ;           
}  
if($cursort==5) 
{	
                             $sql="select * from mirae8440.divide " . $where . " order by which desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from mirae8440.divide " . $where . " order by which desc, " . $SettingDate . "  desc, num desc  " ;         
}     
if($cursort==6) 
{	
                             $sql="select * from mirae8440.divide " . $where . " order by which asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from mirae8440.divide " . $where . " order by which asc, " . $SettingDate . "  desc, num desc  " ;         
}     

if($cursort==7) 
{	
                             $sql="select * from mirae8440.divide " . $where . " order by outworkplace desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from mirae8440.divide " . $where . " order by outworkplace desc, " . $SettingDate . "  desc, num desc  " ;         
}     
if($cursort==8) 
{	
                             $sql="select * from mirae8440.divide " . $where . " order by outworkplace asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from mirae8440.divide " . $where . " order by outworkplace asc, " . $SettingDate . "  desc, num desc  " ;         
}       
 if($cursort==9) 
{	
                             $sql="select * from mirae8440.divide " . $where . " order by item desc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from mirae8440.divide " . $where . " order by item desc, " . $SettingDate . "  desc, num desc  " ;         
}   
 if($cursort==10) 
{	
                             $sql="select * from mirae8440.divide " . $where . " order by item asc, " . $SettingDate . "  desc, num desc  " . $all;           
          					 $sqlcon="select * from mirae8440.divide " . $where . " order by item asc, " . $SettingDate . "  desc, num desc  " ;         
}  

  
  


	 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
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
			  $holes=$row["holes"];			  
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
   //   print "$page&nbsp;$total_page&nbsp;$current_page&nbsp;$search&nbsp;$mode";
		
		if($regist_state==null)
			 $regist_state="1";
		 
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="red";
						}
												
								$font="black";
							
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
		 
<body >

 <div id="wrap">
   <div id="header">
	 <?php include "../lib/top_login2.php"; ?>
   </div>
   <div id="menu">
	 <?php include "../lib/top_menu2.php"; ?>
   </div>
   <div id="content">			 
  <form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
  <div id="col2">    
  
       <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
	   
<div id=display_board class=background name=display_board > 
	
     <div class="clear"></div> 	

 <span class="more">
  <span class="blind"></span>
</span> <div id=up_display1>  총 합계: </div> <div id=up_display2>   </div>
	 <div id="spreadsheet" class="board" style="display:none"  >

  <ul class="list">

  </ul>
  
  </div>

<script>



 var changed = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
}

var beforeChange = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
}

var insertedRow = function(instance) {
}

var insertedColumn = function(instance) {  
}

var deletedRow = function(instance) {
}

var deletedColumn = function(instance) {
}

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
}

var resizeColumn = function(instance, cell, width) {
}

var resizeRow = function(instance, cell, height) {
}

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);

}

var loaded = function(instance) {
}

var moveRow = function(instance, from, to) {
}

var moveColumn = function(instance, from, to) {
}

var blur = function(instance) {
}

var focus = function(instance) {
}

var data = [    [''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],

 
];

var table1 = jexcel(document.getElementById('spreadsheet'), {
    data:data,
   // csv:'http://8440.co.kr/test.csv',
  //	csvHeaders:false,
    tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    tableHeight: '500px' ,	
    columns: [
        { title: '알루미늄/줄 구분', type: 'text', width:'150' },
        { title: '홀 개수', type: 'text', width:'200' },
        { title: '규격(길이) ', type: 'text', width:'150' },
        { title: '수량', type: 'text', width:'70' },
       // { type: 'calendar', width:'50' },
	    // tableWidth: '600px',
		
    ],
    onchange: changed,
    onbeforechange: beforeChange,
    oninsertrow: insertedRow,
    oninsertcolumn: insertedColumn,
    ondeleterow: deletedRow,
    ondeletecolumn: deletedColumn,
    onselection: selectionActive,
    onsort: sort,
    onresizerow: resizeRow,
    onresizecolumn: resizeColumn,
    onmoverow: moveRow,
    onmovecolumn: moveColumn,
    onload: loaded,
    onblur: blur,
    onfocus: focus,
});
   
   
   </script>



   <div class="clear"></div>	

     <div class="clear"></div>		
	<!--   <div id="order11"> 수량 : </div> -->
  <div id="order2">
	   
	 </div> 

     <div class="clear"></div>


    <div class="clear"></div>	 
   
   
<div id=list_board >    
	 <div id="title" style="width:400px" ><h1> 재료분리대 입출고 현황 </h1> </div>	 
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
			&nbsp; 입출고일 <input type="radio" checked name="separate_date" value="1">
			&nbsp; 접수일 <input type="radio" name="separate_date" value="2">
			<?php
             		}    ?>	 
			 <?php
	    if($separate_date=="2") {
			 ?>
			&nbsp; 입출고일 <input type="radio"  name="separate_date" value="1">
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

        <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<?php
   if(isset($_SESSION["userid"]))
   {
  ?>
        <div id="list_search6"> <a href="write_form.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>"> <img src="../img/write.png"></a> </div>
  <?php
   }
  ?>
      </div> <!-- end of list_search -->
      <div class="clear"></div>
      <div id="output_top_title">
      <div id="output_title1"> 번호 </div>
      <div id="output_title2"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=1&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 입출고일 </a> </div>     <!-- 출고일자 -->
      <div id="output_title3"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=2&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 접 수 일 </a> </div>     <!-- 접수일 -->
      <div id="output_title4"><a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=3&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">  입출고 </a> </div>         <!-- 접수상태 표시 -->
      <div id="output_title5"><a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=4&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">  현 장 명</a> </div>     
      <div id="output_title11"><a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=5&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">  종류 구분 </a> </div>     
      <div id="output_title6"> 홀 수 </div>     
      <div id="output_title7"> 규격(길이)    </div>      
      <div id="output_title8"> 수량    </div>      
      <div id="output_title10"> 비고    </div>      
      </div>
      <div id="list_content">
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
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
			  $holes=$row["holes"];			  
	
	 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}

?>
				<div id="outlist_item" > 
			    <div id="outlist_item1"><a href="view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" >
				<?=$start_num ?></div>			
			    <div id="outlist_item2" style="color:<?=$date_font?>;">
				<b> <?=iconv_substr($outdate,0,15, "utf-8")?></b></div>

				 <?php						       //    접수일이 당일이면 깜빡이는 효과부여
				
				if($indate==date("Y-m-d",time()))  //    보라색 굵은 글씨체로 당일 해당 접수된 것 ......
			        {
  			//	print '<div id class="blink" style="white-space:nowrap; color:green;" >';
  				print '<div id="outlist_item3" style=" color:red;">';
								}
								else
								{
									print '<div id="outlist_item3">';
								}
								
	     if($which=='1')
		       {
               $tmp_word="입고";
			   $font_state="black";
			   }
               else
			   {
	               $tmp_word="출고";
			       $font_state="red";				   
			   }
								
				?>
			     <?=$indate?>
				
					 </div>
				<div id="outlist_item4" style="color:<?=$font_state?>;" > <?=$tmp_word?> </div>
				<div id="outlist_item5" > <?=iconv_substr($outworkplace,0,15,"utf-8")?> </div>
				<div id="outlist_item6" > <?=$item?> </div>
				<div id="outlist_item11" style="color:green;" > <?=iconv_substr($holes,0,8,"utf-8")?> </div>
				<div id="outlist_item7" > <?=$spec?> </div>
				<div id="outlist_item8" > <?=$steelnum?> </div>
				<div id="outlist_item10" > <?=iconv_substr($comment,0,20,"utf-8")?> </div> 
				</a>
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

<div id="write_button">    

  <br><br><br>
      </div>
     </div>   
 
	</form>
	 </div>   
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
   </div> 	   
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
            $("#up_fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_todate").datepicker({ dateFormat: 'yy-mm-dd'});			
			
});
 
 function up_pre_year(){   // 윗쪽 전년도 추출
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

document.getElementById("up_fromdate").value = frompreyear;
document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
}  
 
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

function up_pre_month(){    // 윗쪽 전월
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

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 
 
function pre_month(){    // 전월
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

function up_this_year(){   // 윗쪽 당해년도
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

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
document.getElementById('view_table').value="search"; 
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_year(){   // 아래쪽 당해년도
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

function up_this_month(){   // 윗쪽 당해월
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

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
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

function up_this_today(){   // 윗쪽 날짜 입력란 금일
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

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
	
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

function exe_view_table(){   // 출고현황 검색을 클릭시 실행

document.getElementById('view_table').value="search"; 

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

function load_data (){   

 var arr1 = <?php echo json_encode($divide_item);?> ;
 var arr2 = <?php echo json_encode($divide_holes);?> ; 
 var arr3 = <?php echo json_encode($divide_spec);?> ;
 var arr4 = <?php echo json_encode($sum);?> ;
 var tmp;
 var weight;
 var price;
 var total_sum=0;
  
 var rowNum = <?php echo $rowNum; ?>; 
 
 for(i=0;i<=rowNum;i++) {
	 
	 if(Number(arr4[i])>0) 
	 {
		     tmp=Number(arr4[i]);
             table1.setRowData(i,[arr1[i],arr2[i],arr3[i],tmp]);	 
			 total_sum += tmp;
	 }
	    else 
		{
             table1.setRowData(i,[arr1[i],arr2[i],arr3[i]]);	 
		}
    }
  // alert(total_sum);
 $("#up_display2").text(comma(total_sum) + 'EA');
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
  /*
var total_sum = '<?php echo $total_sum ;?>';
var total_m2 = '<?php echo $total_m2 ;?>';
$("#top_board2").text(total_sum);
$("#top_board4").text(total_m2);

*/

setTimeout(function() {
 // console.log('Works!');
 load_data();
}, 300);

</script>

  </html>