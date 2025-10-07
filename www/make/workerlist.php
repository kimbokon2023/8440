<?php
 session_start();
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
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
 <link rel="stylesheet" type="text/css" href="../css/work.css">
 <link rel="stylesheet" type="text/css" href="../css/special.css">
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
 
$a="  order by num desc limit $first_num, $scale";  
$b="  order by num desc";


					  $sql ="select * from mirae8440.workerlist where ((name like '%$search%' ) or (comment1 like '%$search%' ) or  (comment2 like '%$search%' ) or (comment3 like '%$search%' ) or (comment4 like '%$search%' ) or";
					  $sql .=" (comment5 like '%$search%' ) or (company like '%$search%' )) " . $a;
					  
					  $sqlcon ="select * from mirae8440.workerlist where ((name like '%$search%' ) or (comment1 like '%$search%' ) or  (comment2 like '%$search%' ) or (comment3 like '%$search%' ) or (comment4 like '%$search%' ) or";
					  $sqlcon .=" (comment5 like '%$search%' ) or (company like '%$search%' )) " . $b;
   
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
	
<body onload="_no_Back();" onpageshow="if(event.persisted)_no_Back();">
 <div id="wrap">
   <div id="content">	
   <form name="board_form" method="post" action="search.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>">  
  <div id="col2">     
	 <div id="list_search">
        <div id="list_search1">▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다. 검색어 : <?= $search ?> 
		
<?php
    if($total_row==0)
	{     
   		   print "<button type='button' id='directinput' class='button button2' > 직접입력 </button>"; 	 
           	}
     ?>
		
		</div>
		 
</div> <!-- end of list_search3 -->

      <br>
      <div id="outlist_top_title">
      <ul>
         <li id="outlist_title1">번호</li>
         <li id="outlist_title2">수신처(반장) 이름</li>    
         <li id="outlist_title3">연락처</li>
         <li id="outlist_title4">분야</li>
         <li id="outlist_title5">회사</li>
         <li id="outlist_title6">비고1</li>
         <li id="outlist_title7">비고2</li>
         <li id="outlist_title8">비고3</li>
      </ul>
      </div> <!-- end of list_top_title -->
<br><br>
      <div id="list_content">
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  $con_num=$row["con_num"];
			  $name=$row["name"];
			  $tel=$row["tel"];
			  $item=$row["item"];
			  $company=$row["company"];
			  $comment1=$row["comment1"];
			  $comment2=$row["comment2"];
			  $comment3=$row["comment3"];

//              $item_subject=mb_substr($item_subject,0,20,'utf-8');
	///		  if(mb_strlen($item_subject,'utf-8')>=20)
		//					$item_subject=$item_subject . "...";   // 글자수가 초과하면 ...으로 표기됨			  
			  
					  
			 ?>
				<div id="sub_item">
			  <div id="sub_item1"><?= $start_num ?>&nbsp;</div>
				<div id="sub_item2"> <a href="#" onclick="intovaltel('<?=$tel?>','<?=$name?>'); return false;" style="font-size=10px;" ><?= $name ?>&nbsp;</a>	
				</div>
				<div id="sub_item3"><?=substr($tel,0,25)?>&nbsp;</div>
				<div id="sub_item4"><?=substr($item,0,25)?>&nbsp;</div>
				<div id="sub_item5"><?= $company?>&nbsp;</div>
				<div id="sub_item6"><?= $comment1?>&nbsp;</div>
				<div id="sub_item7"><?= $comment2?>&nbsp;</div>
				<div id="sub_item8"><?= $comment3?>&nbsp;</div>

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
        print "<a href=workerlist.php?page=$prev_page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=workerlist.php?page=$i&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year>[$i]</a>";
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=workerlist.php?page=$next_page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year> ▶</a><p>";
      }
 ?>			
        </div>
     </div>

     </div>

    </div> <!-- end of col2 -->
	</form>	
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->
<script>
  	  
 function intovaltel(tel,name)
		  {
			        $("#displaysearchworker").hide();
			    document.getElementById("phone").value = tel;
			    document.getElementById("receiver").value = name;
		  }

	// 자재 출고 직접입력버튼 클릭
    $("#directinput").on("click", function() {
     $("#displaysearchworker").hide();
	  // alert("dkajflasd");
    });	

</script>
  </body>
  </html>