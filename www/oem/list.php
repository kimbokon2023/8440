<?
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
if(!isset($_SESSION["level"]) ) {	          		 
		 sleep(1);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
}   
?>
  
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
 <link rel="stylesheet" type="text/css" href="../css/oem.css">

<title> 서한 컴퍼니 </title> 

 </head>
 
<body>

  <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

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
		  $scale=30;	  	  
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	

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
  else 
  {
     $sortof=0;     
	 $cursort=0;
  }
  
  
  $sum=array();
 
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
   
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"];
   
switch($cursort)
{
   case 1 :
   $orderby="order by orderday desc, num desc  ";
   break;   
   case 2 :
   $orderby="order by orderday asc, num desc  ";
   break;      
   case 3 :
   $orderby="order by startday desc, num desc  ";
   break;   
   case 4 :
   $orderby="order by startday asc, num desc  ";
   break;      	
   case 5 :
    $orderby="order by startday desc, num desc  ";
   break;   
   case 6 :
   $orderby="order by startday asc, num desc  ";
   break;      		
   case 7:
   $orderby="order by deadline desc, num desc  ";
   break;   
   case 8 :
   $orderby="order by deadline asc, num desc  ";
   break;
   case 9 :
   $orderby="order by workday desc, num desc  ";
   break;   
   case 10:
   $orderby="order by workday asc, num desc  ";
   break;    
   case 11 :
   $orderby="order by demand asc, orderday desc, num desc  ";
   break;   
   case 12:
   $orderby="order by demand desc, orderday desc, num desc  ";
   break;     
   case 13 :
   $orderby="order by testday asc, num desc  ";
   break;   
   case 14:
   $orderby="order by testday desc, num desc  ";
   break;     
default:
   $orderby="order by orderday desc, num desc ";
break;
}
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분

if ($check=='1')  // 미출고 리스트 체크된 경우
		{
				$attached=" and ((workday='') or (workday='0000-00-00')) ";
				$whereattached=" where workday='' ";
		}

if ($check_draw=='1')  // 미설계List
		{
				$attached=" and ((main_draw='') or (main_draw='0000-00-00') or (lc_draw='') or (lc_draw='0000-00-00') )  ";
				$whereattached=" where ((main_draw='') and (bon_su>'0')) or ((lc_draw='') and (lc_su>'0')) ";				
		}		
		
if ($notorder=='1')  // 미발주 부품List
		{
				$attached=" and (((order_com1<>'') and (order_date1='')) or  ((order_com2<>'') and (order_date2='')) or ((order_com3<>'') and (order_date3=''))) ";
				$whereattached=" where ((order_com1<>'') and (order_date1='')) or  ((order_com2<>'') and (order_date2='')) or ((order_com3<>'') and (order_date3=''))";				
		}				

if ($plan_output_check=='1')  // 납품예정이 체크된 경우
		{
				$attached=" and (date(deadline)>=date(now()))  ";
				$whereattached=" where date(deadline)>=date(now()) ";
                $orderby="order by deadline asc ";				
		}
		
if ($notorder=='1'&&$plan_output_check=='1')  // 미발주 부품List && 납품예정이 체크된 경우
		{
				$attached=" and (((order_com1<>'') and (order_date1='')) or  ((order_com2<>'') and (order_date2='')) or ((order_com3<>'') and (order_date3=''))  and (date(deadline)>=date(now()))  ) ";
				$whereattached=" where (((order_com1<>'') and (order_date1='')) or  ((order_com2<>'') and (order_date2='')) or ((order_com3<>'') and (order_date3=''))) and date(deadline)>=date(now())";				
		}			

if ($notorder=='1'&&$check=='1')  // 미발주 부품List && 미출고 리스트가 체크된 경우
		{
				$attached=" and (((order_com1<>'') and (order_date1='')) or  ((order_com2<>'') and (order_date2='')) or ((order_com3<>'') and (order_date3=''))  and (workday='') ) ";
				$whereattached=" where (((order_com1<>'') and (order_date1='')) or  ((order_com2<>'') and (order_date2='')) or ((order_com3<>'') and (order_date3=''))) and (workday='') ";				
		}					
	
	
if ($output_check=='1')  // 출고완료 체크된 경우
		{
				$attached=" and ((workday!='') and (workday!='0000-00-00')) ";
				$whereattached=" where workday!='' ";
		}	
	 
$a= " " . $orderby . " limit $first_num, $scale";  
$b=  " " . $orderby;
  

		  if($search==""){
					 $sql="select * from mirae8440.oem " . $whereattached . $a; 					
	                 $sqlcon = "select * from mirae8440.oem "  . $whereattached .  $b;   // 전체 레코드수를 파악하기 위함.
			       }
			 elseif($search!=""&&$find!="all")
			    {
         			$sql="select * from mirae8440.oem where ($find like '%$search%') " . $attached . $a;
         			$sqlcon="select * from mirae8440.oem where ($find like '%$search%')  "  . $attached . $b;
                 }     				 
             elseif($search!=""&&$find=="all") { // 필드별 검색하기
	              if($check!='1') {		 
					  $sql ="select * from mirae8440.oem where (workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (type1 like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize1 like '%$search%' ) or (memo like '%$search%' ) or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  " . $a;
					  
                      $sqlcon ="select * from mirae8440.oem where (workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .="or (delicompany like '%$search%' ) or (type1 like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize1 like '%$search%' ) or (memo like '%$search%' )   or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  " . $b;
				  }
				  
             if($check=='1' || $output_check=='1' || $measure_check=='1' || $plan_output_check=='1' || $team_check=='1') {			  
					  $sql ="select * from mirae8440.oem where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (type1 like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize1 like '%$search%' ) or (memo like '%$search%' )   or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  ) "  . $attached . $a;
					  
                      $sqlcon ="select * from mirae8440.oem where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .="or (delicompany like '%$search%' ) or (type1 like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize1 like '%$search%' ) or (memo like '%$search%' )  or (memo2 like '%$search%' )  or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  ) "  . $attached . $b;
				  }				  
		   
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
			?>
			
<div class="container-fluid">  
   
 <div id="content">	
   
   <form id="board_form" name="board_form" method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=<?=$stable?>">  
		
				<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
				<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
				<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
				
  <div id="vacancy" style="display:none">  </div>	

  <div id="col2">     
  <div id="title_top" style="width:350px;margin-top:5px;color:grey;" > <h3> 서한컴퍼니 외주발주 </h3> </div> <div id="dis_board"> <input type="text" id="dis_text" size="100" style="font-size:17px;"> </div>
  <div class="clear"> </div>
	<div id="dis_board2" style="width:1500px;height:60px;margin-left:-5px;"> 
	<span >  25일 마감 파주시 파주읍 백석리 1-2 / 성광 조대리 010-7225-9608 </span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 설계 강형구부장 010-6244-4561
 
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:grey;"> 정스틸/010-2214-8030/이천용이사  </span>   

	 </div>
<br>
      <div id="list_search">
        <div id="list_search1" style="font-size:12px;">  총 <?= $total_row ?> 개의 자료 </div>
		<div id="list_search2" style='width:350px;">		
		<br>
		  <?php
		    if($check=='1')
				print "<input type='checkbox' checked id=without value='1'>	미출고 리스트 &nbsp;&nbsp;&nbsp;	";
			   else
				print "<input type='checkbox' id=without value='1'>	미출고 리스트 &nbsp;&nbsp;&nbsp;	";	
		    if($plan_output_check=='1')
				print "<input type='checkbox' checked id=plan_outputlist value='1'>	납품예정 &nbsp;&nbsp;&nbsp;	";
			  else
				print "<input type='checkbox' id=plan_outputlist value='1'>	납품예정&nbsp;&nbsp;&nbsp;	";				  		

		    if($output_check=='1')
				print "<input type='checkbox' checked id=outputlist value='1'>	출고완료&nbsp;&nbsp;&nbsp;	";
			  else
				print "<input type='checkbox' id=outputlist value='1'>	출고완료&nbsp;&nbsp;&nbsp;	";				  
			?>			

		</div>				 

		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 		
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 				
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 				
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 				
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 				
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
		<input type="hidden" id="check_draw" name="check_draw" value="<?=$check_draw?>" size="1" > 	
		<input type="hidden" id="notorder" name="notorder" value="<?=$notorder?>" size="1" > 	
		<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="1" > 	
				
        <div id="list_search3" ><img src="../img/select_search.gif"></div>
        <div id="list_search4">
        <select name="find">
           <?php		  
		      if($find=="")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   		   

		   <?php
			  } ?>		
		  <?php		  
		      if($find=="all")
			  {			?>	  
           <option value='all' selected>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   		   

		   <?php
			  } ?>
		  <?php
		      if($find=="workplacename")
			  {			?>	  
           <option value='all' >전체</option>
           <option value='workplacename' selected>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   
		   <?php
			  } ?>			  
		  <?php
		      if($find=="firstord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord' selected>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   		   

		   <?php
			  } ?>			  
		  <?php
		      if($find=="secondord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord' selected>발주처</option>
           <option value='type' >타입</option>		   
		   
		   <?php
			  } ?>	  			  
		  		  
		  <?php
		      if($find=="type")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord' >발주처</option>
           <option value='type' selected>타입</option>
		   
		   <?php
			  } ?>	  				  			  
				  
        </select>
				
		</div> <!-- end of list_search3 -->
		<?php
			?>
        <div id="list_search5"><input type="text" id="search" name="search" value="<?=$search?>"></div>

        <div id="list_search6"><input type="image" src="../img/list_search_button.gif"></div> &nbsp;

	<?php if( $level < 5 )    {     ?> 
          <button type="button" class="btn btn-secondary  btn-sm" onclick="window.open('batchDB.php','청구 일괄처리','left=10,top=50, scrollbars=yes, toolbars=no,width=1700,height=850');"> 청구 일괄처리 </button>    
	<?php   }  ?>		
		
    
           <button type="button" class="btn btn-secondary  btn-sm" onclick="window.open('plan_making.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','납품일정 List DB','left=50,top=50, scrollbars=yes, toolbars=no,width=1600,height=800');" border="0">납품예정 </button>    
           <button type="button" class="btn btn-secondary  btn-sm" onclick="window.open('No_demandlist.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','출고완료 미청구 List DB','left=50,top=50, scrollbars=yes, toolbars=no,width=1600,height=800');" border="0">  출고완료 미청구 </button>    
	<?php if( $level < 5 )    {     ?> 
           <button type="button" class="btn btn-secondary  btn-sm" onclick="window.open('call_csv.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&list=1&sortof=6&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>$plan_output_check=<?=$plan_output_check?>&check=<?=$check?>','CSV 파일추출','left=100,top=100, scrollbars=yes, toolbars=no,width=1600,height=500');"> 엑셀CSV저장  </button>    
	<?php   }  ?>			   


 <div id="list_search7">		
		    
	<?php
   if( $level < 5 )
   {
  ?> 
   <a href="write_form.php"> <img src="../img/write.png"></a>
  <?php
   }
  ?>
  	  
      </div> <!-- end of list_search12  -->
         </div> <!-- end of list_search -->
		 
      <div class="clear"></div>
      <div id="list_top_title"> 
      <ul>
         <li id="list_title1" style="margin-left:-35px;"> 번호 </li>
         <li id="list_title6" style="margin-left:15px;"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=1&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&check=<?=$check?>">접수일 </a> </li>   
         <li id="list_title6" style="margin-left:75px;"> <a style="color:green" href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=3&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&check=<?=$check?>">&nbsp;발주일 &nbsp;</a> </li>	
         <li id="list_title6" > <a style="color:red" href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=4&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&check=<?=$check?>">&nbsp;납기일 &nbsp;</a> </li>	
         <li id="list_title13" style="margin-left:15px;"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=5&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&check=<?=$check?>">출고일</a></li>			 
         <li id="list_title14"><a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&sortof=6&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&check=<?=$check?>">청구 </a></li>			 	 
         <li id="list_title4">현장명</li>
         <li id="list_title9"  style="margin-left:180px;">발주처</li>
         <li id="list_title10" style="margin-left:50px;">타입</li>
         <li id="list_title10">인승</li>
         <li id="list_title10"  style="margin-left:50px;">Car insize</li>
         <li id="list_title11">L/C</li>
         <li id="list_title11">기타</li>
         <li id="list_title11">운반비 내역</li>
         <li id="list_title12">비고</li>
		 
      </ul>
      </div> <!-- end of list_top_title -->
	  
      <div id="list_content">
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
			  
			  $type1=$row["type1"];			  
			  $inseung1=$row["inseung1"];			  
			  $car_insize1=$row["car_insize1"];		  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  
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

			$type2=$row["type2"];
			$type3=$row["type3"];
			$type4=$row["type4"];
			$type5=$row["type5"];
			$type6=$row["type6"];
			$type7=$row["type7"];
			$type8=$row["type8"];
			$type9=$row["type9"];
			$type10=$row["type10"];			  
			$inseung2=$row["inseung2"];
			$inseung3=$row["inseung3"];
			$inseung4=$row["inseung4"];
			$inseung5=$row["inseung5"];
			$inseung6=$row["inseung6"];
			$inseung7=$row["inseung7"];
			$inseung8=$row["inseung8"];
			$inseung9=$row["inseung9"];
			$inseung10=$row["inseung10"];
			$car_insize2=$row["car_insize2"];
			$car_insize3=$row["car_insize3"];
			$car_insize4=$row["car_insize4"];
			$car_insize5=$row["car_insize5"];
			$car_insize6=$row["car_insize6"];
			$car_insize7=$row["car_insize7"];
			$car_insize8=$row["car_insize8"];
			$car_insize9=$row["car_insize9"];
			$car_insize10=$row["car_insize10"];  
			
			$comment1=$row["comment1"];
			$comment2=$row["comment2"];
			$comment3=$row["comment3"];
			$comment4=$row["comment4"];
			$comment5=$row["comment5"];
			$comment6=$row["comment6"];
			$comment7=$row["comment7"];
			$comment8=$row["comment8"];
			$comment9=$row["comment9"];
			$comment10=$row["comment10"]; 								  			
            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];				  
			  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
			  
			  $dis_text = " (종류별 합계)    결합단위 : " . $sum[0] . " (SET),  L/C : "  . $sum[2] . "  (EA), 기타 : "  . $sum[3] . "  (EA)"; 			   			  

		      $startday=trans_date($startday);
		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lclbending_date=trans_date($lclbending_date);
		      $lclwelding_date=trans_date($lclwelding_date);
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
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
       $typeAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='type' . $i;
		 if($i>1 && $$tmp!='' )
      			 $typeAll .= '/' . $$tmp;   
		     else
				  $typeAll .= $$tmp;   
	   }      
	   $car_insizeAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='car_insize' . $i;
		 if($i>1 && $$tmp!='' )
      			 $car_insizeAll .= '/' . $$tmp;   
		     else
				  $car_insizeAll .= $$tmp;   
	   }
	   // print_r($typeAll);
				 
			 ?>
				<div id="subject_item" > <a href="view.php?num=<?=$num?>&page=<?=$page?>&scale=<?=$scale?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=<?=$stable?>&check=<?=$check?>&notorder=<?=$notorder?>" style="font-size:13px" >
			    <div id="subject_item1" style="margin-left:-5px;"><?=$start_num ?> &nbsp;</div>
				<div id="subject_item2"> <?=$orderday ?> &nbsp;</div>				
				<div id="subject_item3" ><?=iconv_substr($startday,5,5,"utf-8")?>&nbsp;</div>				

				
				 <?php						    // 접수일이 당일이면 깜빡이는 효과부여				
				 
				if($deadline==date("Y-m-d",time()))  // 납품예정
			        {
  				print '<div id="subject_item6" class="blink" style="color:red;">';
								}
								else
								{
									print '<div id="subject_item6">';
								}
				?>					
				
				&nbsp;<?=iconv_substr($deadline,5,5,"utf-8")?>&nbsp;</div>		
				
		     <div id="subject_item6"> &nbsp;<?=iconv_substr($workday,5,5,"utf-8")?>&nbsp;</div>								
				
				<div id="subject_item14" style="color:purple;">&nbsp;<?=iconv_substr($demand,5,5,"utf-8")?>&nbsp;</div>				
				<div id="subject_item4"><?=$workplacename?>&nbsp;</div>
				<?php if($secondord=='성광')
					   print '<div id="subject_item9" style="color:grey;" >';
					 else
						print '<div id="subject_item9" style="color:brown;" >';
					?>										
					<?=iconv_substr($secondord,0,15,"utf-8")?>&nbsp;</div>				
				<div id="subject_item14" style="overflow: auto;color:blue;width:80px;">&nbsp;<?=$typeAll?>&nbsp;</div>					
				<div id="subject_item22" style="overflow: auto;width:80px;">&nbsp;<?=$inseung1?>&nbsp;</div>

				<div id="subject_item14" style="overflow: auto;color:red;width:100px;">&nbsp;<?=$car_insizeAll?>&nbsp;</div>
		
				<?php
				 $workitem="";
				 
				 if($su!="")
					    $workitem= $su . " , "; 
				 if($bon_su!="")
					    $workitem .="본 " . $bon_su . ", "; 					
				 if($lc_su!="")
					    $workitem .="L/C " . $lc_su . ", "; 											
				 if($etc_su!="")
					    $workitem .="기타 "  . $etc_su . ", "; 																	
				 if($air_su!="")
					    $workitem .="공기청정기 "  . $air_su . " "; 																							
						
				 $part="";
				 if($order_com1!="")
					    $part= $order_com1 . "," ; 
				 if($order_com2!="")
					    $part .= $order_com2 . ", " ; 						
				 if($order_com3!="")
					    $part .= $order_com3 . ", " ; 												
				 if($order_com4!="")
					    $part .= $order_com4 . ", " ; 
						
                 $deli_text="";
				 if($delivery!="" || $delipay!=0)
				 		  $deli_text = $delivery . " " . $delipay ;  

				   ?>
				   
				<div id="subject_item21" style="color:black;"><?=$lc_su?>&nbsp;</div>
				<div id="subject_item22" style="color:grey;"><?=$etc_su?>&nbsp;</div>								
				
				<div id="subject_item17" style="color:purple"><?=$deli_text?>&nbsp;</div>							
				
				<div id="subject_item12"><?=$memo?></div>				
				<!-- <div id="subject_item7"><div id class="blink"  style="white-space:nowrap"> &nbsp;</div></div> -->
			  </div> 
</a>


            <div class="clear" > </div>
			
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
        print "<a href=list.php?page=$prev_page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year&sortof=$sortof&cursort=$cursort&stable=1&check=$check&output_check=$output_check&team_check=$team_check&measure_check=$measure_check>◀ </a>";

      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=list.php?page=$i&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year&sortof=$sortof&cursort=$cursort&stable=1&check=$check&output_check=$output_check&team_check=$team_check&measure_check=$measure_check>[$i]</a>";
		
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=list.php?page=$next_page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year&sortof=$sortof&cursort=$cursort&stable=1&check=$check&output_check=$output_check&team_check=$team_check&measure_check=$measure_check> ▶</a><p>";
				
      }
 ?>			
        </div>
		        <div class="clear" > </div>
			<br><br><br><br><br><br>
     </div>


    
     </div>

    </div> <!-- end of col2 -->
	
	<br><br><br>
	</form>	
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->
<script>
function check_level()
			  {
				window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
			  }
$(document).ready(function(){
    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
           // $('#search').val('');			 // search 입력란 비우기			
            $('#board_form').submit();	
        }else{
            $('#check').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
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
          //  $('#search').val('');			 // search 입력란 비우기						
		  // $("input[type=checkbox]").prop("checked",false);  // 체크박스 전체해제
            $('#board_form').submit();
			
        }else{
            $('#plan_output_check').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#team").change(function(){
        if($("#team").is(":checked")){
            $('#team_check').val('1');
          //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#team_check').val('');
          //  $('#search').val('');			 // search 입력란 비우기						
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
});		

function dis_text()
{  
		var dis_text = '<?php echo $dis_text; ?>';
		$("#dis_text").val(dis_text);
}	





</script>
  </body>
  
<script> 

function search_condition(con)
{	
				var con;
 				var notorder='<?php echo $notorder; ?>' ;  
				var check_draw='<?php echo $check_draw; ?>' ;

			if(con=='draw') {		
				if(check_draw=='0'||check_draw=='') {		 
					 check_draw='1';
					 $("#check_draw").val('1');	
										}
				   else  {
						check_draw='0';		
						$("#check_draw").val('0');					   
				   }
					   
					$("#check_draw").val(check_draw);	
					$("#scale").val('200');	
					
					$('#board_form').submit();		// 검색버튼 효과
					
				}
			if(con=='notorder') {
				if(notorder=='0'||notorder=='') {		 
					 notorder='1';
					 $("#notorder").val('1');	
										}
				   else  {
						notorder='0';		
						$("#notorder").val('0');					   
				   }
					   
					$("#notorder").val(notorder);	
					$("#scale").val('200');	
					
					$('#board_form').submit();		// 검색버튼 효과

			}		
		
	
}

function check_alert()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
			tmp="../load_alert.php";			
			$("#vacancy").load(tmp);     
		    var voc_alert=$("#voc_alert").val();	 
		    var ma_alert=$("#ma_alert").val();	 
		    var order_alert=$("#order_alert").val();	 
			
 		if(name=='김진억' && voc_alert=='1') {			
			alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>'); 			
			tmp="../save_alert.php?voc_alert=0" + "&ma_alert=" + ma_alert +  "&order_alert=" + order_alert;	
			$("#voc_alert").val('0');				
			$("#vacancy").load(tmp);   						
											}
											
 		if(name=='김진억' && order_alert=='1') {			
			alertify.alert('<H1> 쟘 발주서 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 이메일을 확인해 주세요. 발주서가 접수되었습니다. </h1>'); 			
			tmp="../save_alert.php?order_alert=0" + "&ma_alert=" + ma_alert +  "&voc_alert=" + voc_alert;	
			$("#order_alert").val('0');				
			$("#vacancy").load(tmp);   						
											}											
											
 		if(name=='조경임' && ma_alert=='1') {			
			alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조과장님 <br> <br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>'); 			
			tmp="../save_alert.php?ma_alert=0" + "&voc_alert=" + voc_alert + "&order_alert=" + order_alert;	
			$("#ma_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}											
}

// 5초마다 알람상황을 체크합니다.

var timer;
timer=setInterval(function(){
	check_alert();
}, 3000); 
	
function send_alert() {   // 알림을 서버에 저장 
var voc_alert=$("#voc_alert").val();	 
var ma_alert=$("#ma_alert").val();	 
var order_alert=$("#order_alert").val();	
var tmp; 						
tmp = "../save_alert.php?order_alert=1" + "&ma_alert=" + ma_alert +  "&voc_alert=" + voc_alert;	
    $("#vacancy").load(tmp);      	
    alertify.alert('발주서 등록 알림', '<h1> 발주서가 접수되었습니다. 이메일을 확인해 주세요. </h1>'); 		
 }      	
 
  // 쟘 합계 화면에 출력하기
setTimeout(function() {
 // console.log('Works!');
 dis_text();
}, 2000);

</script>
  
  </html>