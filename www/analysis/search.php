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
 <link  rel="stylesheet" type="text/css" href="../css/bending_write_search.css"> 
 
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 주일기업 통합정보시스템 </title> 
 </head>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

 <?php
 
  if(isset($_REQUEST["search"]))   
	 $search=$_REQUEST["search"];

  if(isset($_REQUEST["mode"]))   
	 $mode=$_REQUEST["mode"];

  if(isset($_REQUEST["steeltype"])) 
	 $steeltype=$_REQUEST["steeltype"];	 

   if(isset($_REQUEST["search_word"]))
	 $search_word=$_REQUEST["search_word"];
	 
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
 
  $scale = 500;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.	

$find=$search_word;
$search=$search_word;
 
$process="전체";  // 기본 전체로 정한다.

$common="   order by  indate ";
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
$c= $common . " asc, num desc limit $first_num, $scale";    //오름차순
$d= $common . " asc, num desc ";    //오름차순 전체

$where="";
$all=" limit $first_num, $scale";
  
  if($mode=="search"){
		  if($steeltype==""){
							 $sql="select * from chandj.atbending " . $a; 					
	                         $sqlcon = "select * from chandj.atbending " . $b;   // 전체 레코드수를 파악하기 위함.					
			       }
             elseif($search_word=="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from chandj.atbending where  steeltype ='$steeltype'  order by indate desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from chandj.atbending where steeltype ='$steeltype' order by indate desc, num desc ";
						}
             elseif($search_word!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from chandj.atbending where  (steeltype ='$steeltype')  and (steel_alias like '%$search%')  order by indate desc, num desc limit $first_num, $scale ";
							  $sqlcon ="select * from chandj.atbending where (steeltype ='$steeltype')  and (steel_alias like '%$search%')  order by indate desc, num desc ";
						}						
						
               }
  if($mode=="") {
							 $sql="select * from chandj.atbending " . $a; 					
	                         $sqlcon = "select * from chandj.atbending " . $b;   // 전체 레코드수를 파악하기 위함.					
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
		 
  <form name="board_form" id="board_form"  method="post" action="bendingdatalist.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>">  
     <div class="clear"></div>

        <div id="list_search">
        <div id="list_search1"> <br> ▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다.</div>

      </div> <!-- end of list_search -->	  
      <div class="clear"></div>	  
      <div id="top_title">
	  
      <div id="title1"> 번호  </div>
      <div id="title22"> 이미지 </div>  
      <div id="title2">  절곡 품명  </div>  
      <div id="title12">  별칭(상세내역)  </div>  
      <div id="title3">  등록(수정)일   </div>    

      <div id="title4"> 1행합 </div>     
      <div id="title5"> 2행합 </div>     
      <div id="title6"> 3행합 </div>     
      <div id="title7"> 4행합 </div>     
      <div id="title8"> 5행합 </div>     
      <div id="title8"> 6행합 </div>     
      <div id="title9"> 7행합 </div>     
      <div id="title10"> 8행합 </div>     
      <div id="title11"> 총합  </div>     
    
      </div>
      <div id="list_content">
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  $indate=$row["indate"];
	  
$steeltype=$row["steeltype"];
$steel_alias=$row["steel_alias"];		  
$copied_file_name=$row["copied_file_name"]; 
$uploaded_file=$row["uploaded_file"]; 

$a1=$row["a1"];
$a2=$row["a2"];
$a3=$row["a3"];
$a4=$row["a4"];
$a5=$row["a5"];
$a6=$row["a6"];
$a7=$row["a7"];
$a8=$row["a8"];
$a9=$row["a9"];
$a10=$row["a10"];
$a11=$row["a11"];
$a12=$row["a12"];
$a13=$row["a13"];
$a14=$row["a14"];
$a15=$row["a15"];
$a16=$row["a16"];
$a17=$row["a17"];
$a18=$row["a18"];
$a19=$row["a19"];
$a20=$row["a20"];
$a21=$row["a21"];
$a22=$row["a22"];
$a23=$row["a23"];
$a24=$row["a24"];
$a25=$row["a25"];
$b1=$row["b1"];
$b2=$row["b2"];
$b3=$row["b3"];
$b4=$row["b4"];
$b5=$row["b5"];
$b6=$row["b6"];
$b7=$row["b7"];
$b8=$row["b8"];
$b9=$row["b9"];
$b10=$row["b10"];
$b11=$row["b11"];
$b12=$row["b12"];
$b13=$row["b13"];
$b14=$row["b14"];
$b15=$row["b15"];
$b16=$row["b16"];
$b17=$row["b17"];
$b18=$row["b18"];
$b19=$row["b19"];
$b20=$row["b20"];
$b21=$row["b21"];
$b22=$row["b22"];
$b23=$row["b23"];
$b24=$row["b24"];
$b25=$row["b25"];
$c1=$row["c1"];
$c2=$row["c2"];
$c3=$row["c3"];
$c4=$row["c4"];
$c5=$row["c5"];
$c6=$row["c6"];
$c7=$row["c7"];
$c8=$row["c8"];
$c9=$row["c9"];
$c10=$row["c10"];
$c11=$row["c11"];
$c12=$row["c12"];
$c13=$row["c13"];
$c14=$row["c14"];
$c15=$row["c15"];
$c16=$row["c16"];
$c17=$row["c17"];
$c18=$row["c18"];
$c19=$row["c19"];
$c20=$row["c20"];
$c21=$row["c21"];
$c22=$row["c22"];
$c23=$row["c23"];
$c24=$row["c24"];
$c25=$row["c25"];
$d1=$row["d1"];
$d2=$row["d2"];
$d3=$row["d3"];
$d4=$row["d4"];
$d5=$row["d5"];
$d6=$row["d6"];
$d7=$row["d7"];
$d8=$row["d8"];
$d9=$row["d9"];
$d10=$row["d10"];
$d11=$row["d11"];
$d12=$row["d12"];
$d13=$row["d13"];
$d14=$row["d14"];
$d15=$row["d15"];
$d16=$row["d16"];
$d17=$row["d17"];
$d18=$row["d18"];
$d19=$row["d19"];
$d20=$row["d20"];
$d21=$row["d21"];
$d22=$row["d22"];
$d23=$row["d23"];
$d24=$row["d24"];
$d25=$row["d25"];
$e1=$row["e1"];
$e2=$row["e2"];
$e3=$row["e3"];
$e4=$row["e4"];
$e5=$row["e5"];
$e6=$row["e6"];
$e7=$row["e7"];
$e8=$row["e8"];
$e9=$row["e9"];
$e10=$row["e10"];
$e11=$row["e11"];
$e12=$row["e12"];
$e13=$row["e13"];
$e14=$row["e14"];
$e15=$row["e15"];
$e16=$row["e16"];
$e17=$row["e17"];
$e18=$row["e18"];
$e19=$row["e19"];
$e20=$row["e20"];
$e21=$row["e21"];
$e22=$row["e22"];
$e23=$row["e23"];
$e24=$row["e24"];
$e25=$row["e25"];
$f1=$row["f1"];
$f2=$row["f2"];
$f3=$row["f3"];
$f4=$row["f4"];
$f5=$row["f5"];
$f6=$row["f6"];
$f7=$row["f7"];
$f8=$row["f8"];
$f9=$row["f9"];
$f10=$row["f10"];
$f11=$row["f11"];
$f12=$row["f12"];
$f13=$row["f13"];
$f14=$row["f14"];
$f15=$row["f15"];
$f16=$row["f16"];
$f17=$row["f17"];
$f18=$row["f18"];
$f19=$row["f19"];
$f20=$row["f20"];
$f21=$row["f21"];
$f22=$row["f22"];
$f23=$row["f23"];
$f24=$row["f24"];
$f25=$row["f25"];
$g1=$row["g1"];
$g2=$row["g2"];
$g3=$row["g3"];
$g4=$row["g4"];
$g5=$row["g5"];
$g6=$row["g6"];
$g7=$row["g7"];
$g8=$row["g8"];
$g9=$row["g9"];
$g10=$row["g10"];
$g11=$row["g11"];
$g12=$row["g12"];
$g13=$row["g13"];
$g14=$row["g14"];
$g15=$row["g15"];
$g16=$row["g16"];
$g17=$row["g17"];
$g18=$row["g18"];
$g19=$row["g19"];
$g20=$row["g20"];
$g21=$row["g21"];
$g22=$row["g22"];
$g23=$row["g23"];
$g24=$row["g24"];
$g25=$row["g25"];
$h1=$row["h1"];
$h2=$row["h2"];
$h3=$row["h3"];
$h4=$row["h4"];
$h5=$row["h5"];
$h6=$row["h6"];
$h7=$row["h7"];
$h8=$row["h8"];
$h9=$row["h9"];
$h10=$row["h10"];
$h11=$row["h11"];
$h12=$row["h12"];
$h13=$row["h13"];
$h14=$row["h14"];
$h15=$row["h15"];
$h16=$row["h16"];
$h17=$row["h17"];
$h18=$row["h18"];
$h19=$row["h19"];
$h20=$row["h20"];
$h21=$row["h21"];
$h22=$row["h22"];
$h23=$row["h23"];
$h24=$row["h24"];
$h25=$row["h25"];
					
 $arr_sum=compact("a_sum","b_sum","c_sum","d_sum","e_sum","f_sum","g_sum","h_sum","total_sum");
	for ($j=0;$j<8;$j++) {
		 
	   switch ($j) {
			 case 0:
			   $str='A';
			   break;
			 case 1:
			   $str='B';
			   break;			   
			 case 2:
			   $str='C';
			   break;			   
			 case 3:
			   $str='D';
			   break;			   
			 case 4:
			   $str='E';
			   break;			   
			 case 5:
			   $str='F';
			   break;			   
			 case 6:
			   $str='G';
			   break;			   
			 case 7:
			   $str='H';	
			   break;			   
		 }	
		 
   	    $str=strtolower($str);
	    $arr=compact($str."1",$str."2",$str."3",$str."4",$str."5",$str."6",$str."7",$str."8",$str."9",$str."10",$str."11",$str."12",$str."13",$str."14",$str."15",$str."16",$str."17",$str."18",$str."19",$str."20",$str."21",$str."21",$str."22",$str."23",$str."24",$str."25");
	    $arr_row_tmp=$str."_sum";

	    for($i=0;$i<25;$i++) {
		 $temp= $str . ($i+1);
		 $cc=($j*25)+$i+4;
		 $arr_sum[$arr_row_tmp] += $arr[$temp];
		//  print $arr_sum[$arr_row_tmp];
 		}
		
		 $arr_sum["total_sum"] += $arr_sum[$arr_row_tmp];
		
		 } 	  				


			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="red";
						}				
							  
 if($indate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $indate = $indate . $week[ date('w',  strtotime($indate)  ) ] ;
}  
			  
			 ?>
				<div id="list_item" > 
			    <div id="list_item1">				
				 <a href="#" onclick="intoval('<?=$uploaded_file?>','<?=$steeltype?>','<?=$steel_alias?>','<?=$arr_sum["a_sum"]?>','<?=$arr_sum["b_sum"]?>','<?=$arr_sum["c_sum"]?>','<?=$arr_sum["d_sum"]?>','<?=$arr_sum["e_sum"]?>','<?=$arr_sum["f_sum"]?>','<?=$arr_sum["g_sum"]?>','<?=$arr_sum["h_sum"]?>','<?=$num?>'); return false;" style="font-size=10px;" ><?=$num ?>
				</div>			
			    <div id="list_item22" > <img src="<?=$uploaded_file?>" class="maxsmall">  </div>
			    <div id="list_item2" style="color:<?=$date_font?>;">
				<b> <?=$steeltype?></b></div>
                <div id="list_item13" >	&nbsp; <?=iconv_substr($steel_alias,0,28,"utf-8")?></div>						
			    <div id="list_item3" >
				<b> <?=substr($indate,0,15)?></b></div>
			    <div id="list_item4" >	<?=$arr_sum["a_sum"]?></div>		
			    <div id="list_item5" >	<?=$arr_sum["b_sum"]?> </div>		
			    <div id="list_item6" >	<?=$arr_sum["c_sum"]?> </div>		
			    <div id="list_item7" >	<?=$arr_sum["d_sum"]?> </div>		
			    <div id="list_item8" >	<?=$arr_sum["e_sum"]?> </div>		
			    <div id="list_item9" >	<?=$arr_sum["f_sum"]?></div>		
			    <div id="list_item10" >	<?=$arr_sum["g_sum"]?></div>		
			    <div id="list_item11" >	<?=$arr_sum["h_sum"]?> </div>		
			    <div id="list_item12" >	<?=$arr_sum["total_sum"]?> </div>							
		        <div class="clear"> </div>
			
				</a>
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
 
      

     </div>
	</form>
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->

<script>

function intoval(uploaded_file, steeltype, steel_alias, a_sum, b_sum, c_sum, d_sum, e_sum, f_sum, g_sum, h_sum, datanum)
	  {
		    $("#displaysearch").hide();
		    $("#input_field").show();
			
			$("#display_img").html("<img src='" + uploaded_file + "'>");
			$("#dis_steeltype").text(steeltype);
			$("#dis_steel_alias").text(steel_alias);
			$("#uploaded_file").val(uploaded_file);
			$("#search_word").val(steel_alias);
			$("#steel_alias").val(steel_alias);
			$("#sum1").text(a_sum);
			$("#sum2").text(b_sum);
			$("#sum3").text(c_sum);
			$("#sum4").text(d_sum);
			$("#sum5").text(e_sum);
			$("#sum6").text(f_sum);
			$("#sum7").text(g_sum);
			$("#sum8").text(h_sum);
			$("#datanum").val(datanum);

				// $("#searchtel").trigger("click");    // 제이쿼리 클릭 이벤트 발생   전화번호 클릭한 효과발생하기
	  }

    $("#search_directinput").on("click", function() {
    $("#displaysearch").hide();
});	

</script>
</body>



</html>