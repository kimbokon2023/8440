<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>8) {
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
 		<script>
		window.history.forward(0);
		history.navigationMode = 'compatible'; // 오페라, 사파리 뒤로가기 막기
		function _no_Back(){
		window.history.forward(0);
		}
		</script>
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
 
  $scale = 23;       // 한 페이지에 보여질 게시글 수
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
$common="   where outdate between date('$fromdate') and date('$Transtodate') order by outdate ";
$a= $common . " desc limit $first_num, $scale";    //내림차순
$b= $common . " desc ";    //내림차순 전체
$c= $common . " asc limit $first_num, $scale";    //오름차순
$d= $common . " asc ";    //오름차순 전체

$where=" where  outdate between date('$fromdate') and date('$Transtodate') ";
$all=" limit $first_num, $scale";
  
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from chandj.output " . $a; 					
	                         $sqlcon = "select * from chandj.output " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from chandj.output where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sql .="or (orderman like '%$search%') or (outputplace like '%$search%') or (receiver like '%$search%') or";
							  $sql .=" (phone like '%$search%') or (comment like '%$search%') order by outdate desc limit $first_num, $scale ";
							  $sqlcon ="select * from chandj.output where (outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sqlcon .="or (orderman like '%$search%') or (outputplace like '%$search%') or (receiver like '%$search%') or";
							  $sqlcon .=" (phone like '%$search%') or (comment like '%$search%') order by outdate desc";							  
						}

               }
  if($mode=="") {
							 $sql="select * from chandj.output " . $a; 					
	                         $sqlcon = "select * from chandj.output " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
	
		   
if($cursort==1)
{	
          					 $sql="select * from chandj.output  " . $c;          
          					 $sqlcon="select * from chandj.output  " . $d;          
}  
 
if($cursort==2)
{	
                             $sql="select * from chandj.output   " . $a;           
          					 $sqlcon="select * from chandj.output " . $b;         
}  
if($cursort==3) // 접수일 클릭시 정렬
{	
                             $sql="select * from chandj.output " . $where . " order by indate desc  " . $all;           
          					 $sqlcon="select * from chandj.output " . $where . " order by indate desc  " ;            
}  
if($cursort==4) // 접수일 클릭시 정렬
{	
                             $sql="select * from chandj.output " . $where . " order by indate asc  " . $all;           
          					 $sqlcon="select * from chandj.output " . $where . " order by indate asc  " ;             
}  
if($cursort==5) // 구분 클릭시 주일/경동 내림 정렬
{	
                             $sql="select * from chandj.output " . $where . " order by root desc, outdate desc  " . $all;           
          					 $sqlcon="select * from chandj.output " . $where . " order by root desc, outdate desc  " ;         
}     

if($cursort==6) // 구분 클릭시 주일/경동 오름차순 정렬
{	
                             $sql="select * from chandj.output" . $where . " order by root asc, outdate desc  " . $all;           
          					 $sqlcon="select * from chandj.output" . $where . " order by root asc, outdate desc  " ;       
}        
if($cursort==7) // 절곡 클릭시 내림 정렬
{	
                             $sql="select * from chandj.output" . $where . " order by steel desc, outdate desc  " . $all;           
          					 $sqlcon="select * from chandj.output" . $where . " order by steel desc, outdate desc  ";         
}        
if($cursort==8) // 절곡 클릭시 오름차순 정렬
{	
                             $sql="select * from chandj.output" . $where . " order by steel asc, outdate desc  " . $all;           
          					 $sqlcon="select * from chandj.output" . $where . " order by steel asc, outdate desc  ";     
}           
if($cursort==9) // 모터 클릭시 내림 정렬
{	
                             $sql="select * from chandj.output" . $where . " order by motor desc, outdate desc  " . $all;           
          					 $sqlcon="select * from chandj.output" . $where . " order by motor desc, outdate desc  " ;          
}        
if($cursort==10) // 모터 클릭시 오름차순 정렬
{	
                             $sql="select * from chandj.output" . $where . " order by motor asc, outdate desc  " . $all;           
          					 $sqlcon="select * from chandj.output" . $where . " order by motor asc, outdate desc  " ;       
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

   <div id="content">			 
<div id="col2">
  <form name="board_form" id="board_form"  method="post" >  

<div id="list_search">
        <div id="list_search1">▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다. 검색어 : <?= $search ?> 
			
<?php
    if($total_row==0)
	{     
   		   print "<button type='button' id='search_directinput' class='button button2' > 직접입력 </button>"; 	 
           	}
     ?>
		
		</div>	 
</div> <!-- end of list_search3 -->
        <br><br><br>
      <div id="output_top_title">
      <div id="output_title1"> 번호 </div>
      <div id="output_title2"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=1&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 출고일자 </a> </div>     <!-- 출고일자 -->
      <div id="output_title3"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=2&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 접 수 일 </a> </div>     <!-- 접수일 -->
      <div id="output_title4">  <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=3&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">구분 </a> </div>         <!-- 주일/경동 -->
      <div id="output_title5"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=4&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>">절곡발주 </a> </div>       <!-- 절곡발주 -->
      <div id="output_title6"><a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=5&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>"> 모터발주 </a> </div>     
      <div id="output_title7"> 현 장 명 </div>     
      <div id="output_title8"> 수신처 </div>     
      <div id="output_title13"> 운송방식 </div>     
      <div id="output_title9"> 수신 주소 </div>     
      <div id="output_title10"> 수신연락처   </div>      
      <div id="output_title11"> 발주담당  </div> 
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
   
												
								$font="black";
							switch ($delivery) {
								case   "상차(선불)"             :  $font="black"; break;
								case   "상차(착불)"              :$font="grey" ; break;
								case   "경동화물(선불)"          :$font="brown"; break;
								case   "경동화물(착불)"          :$font="brown"; break;
								case   "경동택배(선불)"          :$font="brown"; break;
								case   "경동택배(착불)"          :$font="brown"; break;
								case  "직접수령"                 :$font="red"; break;
								case  "대신화물(선불)"           :$font="blue"; break; 
								case  "대신화물(착불)"           :$font="blue"; break;
								case  "대신택배(선불)"           :$font="blue"; break;
								case  "대신택배(착불)"           :$font="blue"; break;
							}	
							  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}  
			  
			 ?>
				<div id="outlist_item" > 
			    <div id="outlist_item1">   <?=$start_num ?></div>			
			    <div id="outlist_item2" style="color:<?=$date_font?>;">
				<b> <?=substr($outdate,0,15)?></b></div>

				 <?php						    // 접수일이 당일이면 깜빡이는 효과부여
				
				if($item_indate==date("Y-m-d",time()))  // 보라색 굵은 글씨체로 당일 해당 접수된 것...
			        {
     				print '<div id="outlist_item3" style="font-weight: bold; color:purple;">';
								}
								else
								{
									print '<div id="outlist_item3">';
								}
				?>
			     <?=substr($item_indate,0,10)?>
				
					 </div>
				<div id="outlist_item4"style="color:<?=$root_font?>;" ><b> <?=$root?></b> </div>
				<div id="outlist_item5" style="color:green"><b> <?=$steel?></b></div>				
				<div id="outlist_item6" style="color:purple"><b> <?=$motor?> </b></div>				
				<div id="outlist_item7"> <?=substr($item_outworkplace,0,36)?> </div>
				<div id="outlist_item8"> <a href="#" onclick="intovalkd('<?=$item_outworkplace?>','<?=$item_receiver?>','<?=$delivery?>','<?=$item_phone?>','<?=$item_outputplace?>');"> <?=substr($item_receiver,0,25)?> &nbsp;</a>	 </div>
				<div id="outlist_item13" style="color:<?=$font?>;" ><?=substr($delivery,0,20)?></div>				
				<div id="outlist_item9"><?=substr($item_outputplace,0,46)?></div>
				<div id="outlist_item10"><?=substr($item_phone,0,25)?></div>
				<div id="outlist_item11"><?=substr($item_orderman,0,25)?></div>
				<div id="outlist_item12"><?=substr($item_comment,0,60)?></a></div>
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
    </div> <!-- end of col2 -->
    </div> <!-- end of col2 -->

<script>
function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

function intovalkd(name,receiver,delivery,phone,address)
	  {
			$("#displaysearch").hide();
			document.getElementById("outworkplace").value = name;			
			document.getElementById("outputplace").value = address;
			document.getElementById("receiver").value = receiver;
			document.getElementById("phone").value = phone;
			// 라디오버튼 값을 바꾸는 방법 예시로 사용할 것
		$("input:radio[name='delivery']:radio[value='" + delivery + "']").prop("checked",true);
	  }
    $("#search_directinput").on("click", function() {
    $("#displaysearch").hide();
    });		  
</script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
if($mode==""&&$fromdate==null)  
{
  echo ("<script language=javascript> this_year();</script>");  // 당해년도 화면에 초기세팅하기
}

?>
  </body>
  </html>