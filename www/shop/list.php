<?php
 session_start();

 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }
  
// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//header("Refresh:0");  // reload refresh   

							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	

function conv_num($num) {
$number = (float)str_replace(',', '', $num);
return $number;
}
 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css"> 

 
 <title> 미래기업 금속작품 관리화면 </title> 
 </head>
 
 <?php

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

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
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
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

 				
$sql="select * from mirae8440.shopitem order by num desc" ; 					
$sqlcon = "select * from mirae8440.shopitem order by num desc" ;   // 전체 레코드수를 파악하기 위함.					
					
try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $catagory=$row["catagory"];			  
			  		  
			  $item=$row["item"];			  
			  $itemdes=$row["itemdes"];
			  $sale=$row["sale"];			  
			  $price=$row["price"];
			  $saleprice=$row["saleprice"];
			  $filename1=$row["filename1"];	 	
			  $youtube1=$row["youtube1"];	 	
			  $youtube2=$row["youtube2"];	 	
			  
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
  <form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
   <div id="col2">   
   
					<input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
					<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
					<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
				    <input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 					
					
	                <div id="vacancy" style="display:none">  </div>
   <div id="display_board" style="margin-top:20px; height:60px;font-size:20px;color:blue; display:none"> 


</div>
	
     <div class="clear"></div> 	
	 
  <div class="clear"></div>	
  
	 <div id="grid" class="board" style="display:none"  >

  <ul class="list">

  </ul>
  
  </div>

			 
   <div class="clear"></div>	

   <div class="clear"></div>	

							 
	    

	<input id="Call_More" type=hidden value="0" >		 	
	
	
	
<div id=list_board >    
	 
      <div class="clear"></div>	 
 <!-- <div id="title2">
 <div id class="blink"  style="white-space:nowrap">  <font color=red> *****  AS 진행 현황 ***** </font> </div>
	  </div>  -->

        <div id="list_search">
        <div id="list_search1" style="font-size:12px;"> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ▷ 총 <?= $total_row ?> 
		&nbsp;&nbsp;&nbsp;		
		<button type="button" class="btn btn-dark " onclick="location.href='./write_form.php';" > 글쓰기  </button> &nbsp;&nbsp;&nbsp;
		
		</div>
		
		<?php
   if(isset($_SESSION["userid"]))
   {
  ?>
        
  <?php
   }
  ?> 
		
       
		</div> <!-- end of list_search3 -->

        

  
      </div> <!-- end of list_search -->
      <div class="clear"></div>
	  
      <div class="limit">
        <ul class="list-group">
          <li class="list-row list-row--header">
            <div class="list-cell list-cell--50">번호</div>
            <div class="list-cell list-cell--100">Catagory</div>
            <div class="list-cell list-cell--100">DP순서</div>
            <div class="list-cell list-cell--150">아이템</div>
            <div class="list-cell list-cell--100">사진</div>
            <div class="list-cell list-cell--80">유튜브1</div>
            <div class="list-cell list-cell--80">유튜브2</div>
            <div class="list-cell list-cell--300">상세설명</div>
            <div class="list-cell list-cell--50">Sale</div>
            <div class="list-cell list-cell--100">최초가격</div>
            <div class="list-cell list-cell--100">결정가격</div>
          </li>	       
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $catagory=$row["catagory"];			  
 			  $dporder=$row["dporder"];			  
			  		  
			  $item=$row["item"];			  
			  $itemdes=$row["itemdes"];
			  $sale=$row["sale"];			  
			  $price=$row["price"];
			  $saleprice=$row["saleprice"];
			  $filename1=$row["filename1"];		
			  $youtube1=$row["youtube1"];	 	
			  $youtube2=$row["youtube2"];				  
                						  

				?>
		    <li class="list-row">
		     <a class="list-link" style="text-decoration:none;" href="view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>">
             <div class="list-cell list-cell--50"><?=$start_num?>				</div>
            <div class="list-cell list-cell--100">	 <?=$catagory?>		</div>				
            <div class="list-cell list-cell--100">	 <?=$dporder?>		</div>				
            <div class="list-cell list-cell--150">	 <?=$item?> 			</div>
            <div class="list-cell list-cell--100">	
             <?
			    if($filename1!='')
					print '<img src="' . $filename1 . '" style="width:100px; height:100px;">';
				?>   
			</div>
            <div class="list-cell list-cell--80">   <?=$youtube1?>				</div>
            <div class="list-cell list-cell--80">   <?=$youtube2?>				</div>
            <div class="list-cell list-cell--300">   <?=$itemdes?>				</div>
            <div class="list-cell list-cell--50 color-red">	  <?=$sale?>		</div>
            <div class="list-cell list-cell--100 color-blue">	 <?=$price ==0 ? '' : number_format($price)	?>	</div>            
            <div class="list-cell list-cell--100 color-brown text-right">	<?=$saleprice ==0 ? '' : number_format($saleprice)  ?>			</div>						
            
			</a>
          </li>			
			    
				
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
       </ul>
	   </div>
 	<div style="float:left; width:100%;text-align : center; font-size:25px;"> 	 
         <?php
            if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-secondary" type="button" id=previousListBtn  onclick="movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  [' . $i . ']  </span>'; 
              else 
                   print '<button class="btn btn-secondary" type="button" id=moveListBtn onclick="movetoPage(' . $i . ')"> [' . $i . ']</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-secondary" type="button" id=nextListBtn onclick="movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
            }
            ?>              
    </div>	
		<br><br><br><br><br>
     </div>

<div id="write_button">    

  <br><br><br>
      </div>
     </div>   
 
	</form>

<form name="settingsFrm" id="settingsFrm"  method="post" action="settings.php">  	
</form>	
	 </div>   
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
   </div> 	   
  </div> <!-- end of wrap -->
<script>
$(document).ready(function(){

	movetoPage = function(page){ 	  
	  $("#page").val(page); 
      var echo="<?php echo $partOpt; ?>"; 
      var searchOpt="<?php echo $searchOpt; ?>"; 
      var search="<?php echo $search; ?>"; 

     $("#partOpt").val(partOpt);
     $("#searchOpt").val(searchOpt);
     $("#search").val(search);
	 $("#mainFrm").submit();  
	}	
});

function saveAsFile(str, filename) {  // text파일로 저장하기
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:attachment/text,' + encodeURI(str);
    hiddenElement.target = '_blank';
    hiddenElement.download = filename;
    hiddenElement.click();
}

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}


</script>
  </body>
  </html>