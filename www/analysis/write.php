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

  $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호
  $upnum=$_REQUEST["upnum"]; 	     // 발주서 번호
  $tempnum=$_REQUEST["upnum"]; 	     // 발주서 번호 비교를 위해 임시변수 만듦
  $num=$_REQUEST["num"]; 	     // 발주서 번호 비교를 위해 임시변수 만듦
  $outputnum=$_REQUEST["outputnum"]; 	     // 출고고유번호

  $datanum=$_REQUEST["datanum"];  //절곡데이터 번호 기억
  $callback=$_REQUEST["callback"];  // 출고현황에서 넘어온 자료인지 체크
	   
  $sort=$_REQUEST["sort"]; 
  $recallcal=$_REQUEST["recallcal"]; 
  $total_text=$_REQUEST["total_text"]; 
  $modify=$_REQUEST["modify"]; 

  if($modify=="update") {
		 $steeltype=$_REQUEST["steeltype"]; 
		 $tmp_steeltype=$steeltype; 
		 
		 $steel_alias=$_REQUEST["steel_alias"]; 
		 $tmp_steel_alias=$steel_alias;  
       $copied_file_name=$_REQUEST["copied_file_name"]; 
	      $tmp_copied_file_name=$copied_file_name;
       $uploaded_file=$_REQUEST["uploaded_file"]; 
	      $tmp_uploaded_file=$uploaded_file;

			 $tmp_length1=$_REQUEST["length1"]; 
			 $tmp_length2=$_REQUEST["length2"]; 
			 $tmp_length3=$_REQUEST["length3"]; 
			 $tmp_length4=$_REQUEST["length4"]; 
			 $tmp_length5=$_REQUEST["length5"]; 
			 $tmp_amount1=$_REQUEST["amount1"]; 
			 $tmp_amount2=$_REQUEST["amount2"]; 
			 $tmp_amount3=$_REQUEST["amount3"]; 
			 $tmp_amount4=$_REQUEST["amount4"]; 
			 $tmp_amount5=$_REQUEST["amount5"]; 
			 $tmp_material1=$_REQUEST["material1"]; 
			 $tmp_material2=$_REQUEST["material2"]; 
			 $tmp_material3=$_REQUEST["material3"]; 
			 $tmp_material4=$_REQUEST["material4"]; 
			 $tmp_material5=$_REQUEST["material5"]; 
			 $tmp_material6=$_REQUEST["material6"]; 
			 $tmp_material7=$_REQUEST["material7"]; 
			 $tmp_material8=$_REQUEST["material8"]; 
			 
			 
					 require_once("../lib/mydb.php");
					 $pdo = db_connect();			  	
					 
					 $sql="select * from chandj.atbending  where num='$datanum' order by num desc";	 // 처음 내림차순
					
						 try{  
						  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
						  $counter=0;
						  $sum=0;
							   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {				   
								   

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
		
									 $tmp_sum1=$arr_sum["a_sum"];
									 $tmp_sum2=$arr_sum["b_sum"];
									 $tmp_sum3=$arr_sum["c_sum"];
									 $tmp_sum4=$arr_sum["d_sum"];
									 $tmp_sum5=$arr_sum["e_sum"];
									 $tmp_sum6=$arr_sum["f_sum"];
									 $tmp_sum7=$arr_sum["g_sum"];
									 $tmp_sum8=$arr_sum["h_sum"];
		
						   }			 
					  } catch (PDOException $Exception) {
					  print "오류: ".$Exception->getMessage();
					  }  
		  
  }
  
 
  $ordercompany=$_REQUEST["ordercompany"];  
  
if($sort=='' ||  $sort=='0')
	 $sort='1';

 require_once("../lib/mydb.php");
 $pdo = db_connect();			  	

if($sort=='1') 
		   	 $sql="select * from chandj.bending_write  where upnum='$parentnum' order by num desc";	 // 처음 내림차순
   else
		   	 $sql="select * from chandj.bending_write  where upnum='$parentnum' order by num asc";	 // 처음 오름차순 
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $sum=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row["upnum"];			  
if((int)$upnum==(int)$parentnum)
      {	
			  $counter++;
			 $sum+=(int)$row["sum1"];
   			 }
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

if($modify!="update") 
      $modify="0";  // 신규등록을 위한 설정

  
?>

<?php		
  $page=1;	 
 
  $scale = 30;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.

if($sort=='1') 
		   	 $sql="select * from chandj.bending_write  where upnum='$upnum' order by num desc";	 // 처음 내림차순
   else
		   	 $sql="select * from chandj.bending_write  where upnum='$upnum' order by num asc";	 // 처음 오름차순
			
			 
$nowday=date("Y-m-d");   // 현재일자 변수지정   
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp=$stmh->rowCount();
	      
	  $total_row = $temp;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 

		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
  
 
$steel_arr = array();
$steel_arr_sum = array();
$arr_count=0; 
	 for($j=1;$j<=9;$j++)    // 합계 배열 초기화
		  $steel_arr_sum[$j]=0;

$display_text="";
		  
	  ?>
		

 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 
 <title> 주일기업 통합정보시스템 </title> 
 </head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="http://5130.co.kr/order/order.js"></script>
  <script src="http://5130.co.kr/analysis/make.js"></script>
  <script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->
 <link rel="stylesheet" type="text/css" href="../css/bending_list.css">
<style media="screen">
      *{
        margin: 0; padding: 0;
      }
      .slide{
        width: 400px;
        height: 400px;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
      }
      .slide ul{
        width: 11600px;
        position: absolute;
        top:0;
        left:0;
        font-size: 0;
      }
      .slide ul li{
        display: inline-block;
      }
      #back{
        position: absolute;
        top: 0;
        left:0 ;
        cursor: pointer;
        z-index: 1;
      }
      #next{
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
        z-index: 1;
      }
     </style>
 

   <body>
  <div id="wrap">  
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
   </div>  
  <div id="content">
   <br><br><br>
<h6> <b>절곡원가 계산 </b>&nbsp;&nbsp; 절곡발주 접수번호 : &nbsp; <?=$upnum?> , &nbsp;&nbsp; 레코드 수 : &nbsp; <?=$total_row?> </h6>

<form name="board_form" id="board_form"  method="post"  action="insert.php">
<div id="company1"> 발주처(현장명) : </div>
<div id="company2"> <input id="ordercompany" name="ordercompany"  type="text" size="60" placeholder="발주처,현장명" value="<?=$ordercompany?>" readonly>  </div>
<div class="clear"> </div>
<div id="r5"> 품목 :  </div>



<div id="r6">
						  <select name="steeltype" id="steeltype">
						  <option value=""></option> 
					<?php 
					
					  $options = array(); 
					  $options[1] = '가이드레일'; 
					  $options[2] = '포스트'; 
					  $options[3] = '셔터박스'; 
					  $options[4] = '마구리'; 
					  $options[5] = '린텔'; 
					  $options[6] = '절단판'; 
					  $options[7] = '알몰딩'; 
					  $options[8] = '알케이스'; 
					  $options[9] = '보강'; 
					  $options[10] = '삼각쫄대'; 
					  $options[11]= '짜부가스켓'; 
					  $options[12] = '하장바'; 
					  $options[13] = '엘바'; 
					  $options[14] = '평철'; 
					  $options[15] = '갈바앵글'; 
					  $options[16] = '기타절곡물'; 
					
				
			  if (! empty($options)) { 
			foreach ($options as $key => $val) { 
			$sel="";
			if($steeltype==$val)
				$sel="selected";
			?> 
			<option value="<?php echo $val;?>" <?php echo $sel;?> > <?php echo $val;?></option> 
			<?php 
			} 
			} 
			?> 
			</select> 


  </div>


<div id="r2">
			<?php 			
			if($modify=="update") {
                 print '<input id="search_word" name="search_word" type="text" size="30"  onkeyup="Enter_Check();" placeholder="세부검색어 입력" value="' . $steel_alias . '">';				
				 print '<script> exe_search(); </script>';
			} 
			 else
				 print '<input id="search_word" name="search_word" type="text" size="30"  onkeyup="Enter_Check();" placeholder="세부검색어 입력" >';				
			?> 

  </div>
<button type="button" id="searchitem" class="button button2" > 검색 </button> 	 

<div class="clear"> </div>

     <div id="input_field" style="display:none">    
     <br>
	    <div id="title_length1"> 길이 </div> <div id="title_length2"> 수량 </div> <div id="title_length3"> 길이 </div> <div id="title_length2"> 수량 </div>
		<div id="title_length3"> 길이 </div> <div id="title_length2"> 수량 </div><div id="title_length3"> 길이 </div> <div id="title_length2"> 수량 </div>
		<div id="title_length3"> 길이 </div> <div id="title_length2"> 수량 </div>
		
        <div class="clear"> </div>		
        <div id="dis_steeltype"> </div>
        <div id="dis_steel_alias"> </div>
        <div id="length1"> <input type="text" id="dis_length1" name="length1" size="10" style="width:50px;"> </div>		
        <div id="amount1"> <input type="text" id="dis_amount1" name="amount1" size="10" style="width:30px;"> </div>		
        <div id="length2"> <input type="text" id="dis_length2" name="length2" size="10" style="width:50px;"> </div>		
        <div id="amount2"> <input type="text" id="dis_amount2" name="amount2" size="10" style="width:30px;"> </div>				
        <div id="length3"> <input type="text" id="dis_length3" name="length3" size="10" style="width:50px;"> </div>		
        <div id="amount3"> <input type="text" id="dis_amount3" name="amount3" size="10" style="width:30px;"> </div>				
        <div id="length4"> <input type="text" id="dis_length4" name="length4" size="10" style="width:50px;"> </div>		
        <div id="amount4"> <input type="text" id="dis_amount4" name="amount4" size="10" style="width:30px;"> </div>				
        <div id="length5"> <input type="text" id="dis_length5" name="length5" size="10" style="width:50px;"> </div>		
        <div id="amount5"> <input type="text" id="dis_amount5" name="amount5" size="10" style="width:30px;"> </div>				
        <div class="clear"> </div>
        
		<div id="display_img"> </div>
		
		<div id="display_rightside">  	<!-- 이미지 옆면 공간 1200*400 화면설계-->             
	    <br>
		<?php 
		  for($i=1;$i<=8;$i++) {
			  if($i==1)
		          print '<div class="firstcol"> <input type="button" id="applyit" onclick="apply_below();" value="전체적용" > </div>';
			  else 
				  print '<div class="firstcol"> </div>';
		  print '<div id="col4" > 재질 : </div>';
          print '<div id="col5" > <select name="material' . $i . '" id="material' . $i . '" >';
		 // print '  <option value="EGI1.6T">EGI1.6T</option>  ';
										
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
					  
			if (!empty($options)) { 
			foreach ($options as $key => $val) { 
			$sel="";
			if($copy_steeltype==$val)
				$sel="selected";
			
			  print ' <option value="' . $val . '" ' . $sel . '> ' . $val . '</option>'; 

			              } 
			  print ' </select>  </div>';						  
			  }
              print '<div class="space" id="space' . $i . '">' . $i . '행 합 : </div>';
              print '<div class="sums" id="sum' . $i . '"></div>';
			  print '<div class="clear"> </div>';
		  }
		  
		  
			?> 		
  <br>
	&nbsp;&nbsp; <input type="button" onclick="cal_exe();" value= "절곡면적 산출">
	&nbsp;&nbsp; <input type="button" onclick="addline();" value= "행 추가" >
	&nbsp;&nbsp; <input type="button" onclick="update();" value="수정&저장" > 
	   <div class="clear"> </div>		
		</div>

		</div>
<!-- 일부분 부분-->
	   <div class="clear"> </div>
<br>
  &nbsp;&nbsp; <button type="button" onclick="partShot();"> 이미지 저장 </button>
  &nbsp;&nbsp;
   <button  type="button" onclick="javascript:del('delete.php?mode=all&upnum=<?=$upnum?>&parentnum=<?=$parentnum?>')" > DATA 전체삭제  </button>
  &nbsp;&nbsp;
   <button  type="button" onclick="javascript:sorting('write.php?num=<?=$num?>&upnum=<?=$upnum?>&outputnum=<?=$outputnum?>&parentnum=<?=$parentnum?>&ordercompany=<?=$ordercompany?>');" > 정렬변경  </button>   
  &nbsp;&nbsp;
   <button  type="button" onclick="javascript:move('write_form.php?num=<?=$upnum?>&mode=modify&upnum=<?=$upnum?>&parentnum=<?=$parentnum?>&callback=1')" > 이전화면  </button>  
  &nbsp;&nbsp;
   <button  type="button" onclick="javascript:move('write.php?upnum=<?=$upnum?>&ordercompany=<?=$ordercompany?>')" > 신규입력  </button>      
   
  &nbsp;&nbsp;
   <button  type="button" onclick="javascript:move('list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>')" > 목록  </button>     
   
   <div class="clear"> </div>	
	 </div>
     <div id="displaysearch" style="display:none">     </div>
	 
 	<!-- php 전달을 위해 변수를 히든형식으로 저장한다. --> 

                           <input id="sort" name="sort" type="hidden" size="3" value="<?=$sort?>" >  	<!-- 정렬방식 변경 --> 
                           <input id="outputnum" name="outputnum" type="hidden" size="3" value="<?=$outputnum?>" >  	<!-- 발주서 상위번호--> 
                           <input id="upnum" name="upnum" type="hidden" size="3" value="<?=$upnum?>" >  	<!-- 발주서 상위번호--> 
                           <input id="parentnum" name="parentnum" type="hidden" size="3" value="<?=$parentnum?>" >  	
                           <input id="num" name="num" type="hidden" size="3" value="<?=$num?>" >  	<!-- 발주서 --> 
                           
						   <input id="modify" name="modify" type="hidden" size="3" value="<?=$modify?>" >  	<!-- 수정여부 --> 
						   <input id="copied_file_name" name="copied_file_name" type="hidden" size="3" value="<?=$copied_file_name?>" >  
						   <input id="uploaded_file" name="uploaded_file" type="hidden" size="3" value="<?=$uploaded_file?>" >  		   
						   <input id="steel_alias" name="steel_alias" type="hidden" size="3" value="<?=$steel_alias?>" >  		   
						   						   
                           <input name="total_text" type="hidden" size="3" value="<?=$total_text?>" >  	<!-- 텍스트기록 --> 
                           <input id="datanum" name="datanum" type="hidden" size="3" value="<?=$datanum?>" >  	<!-- 절곡데이터번호 기억 --> 
						   
                           <input name="sum1" type="hidden" size="3" value="<?=$sum1?>" >  	<!--  --> 
                           <input name="sum2" type="hidden" size="3" value="<?=$sum2?>" >  	<!--  --> 
                           <input name="sum3" type="hidden" size="3" value="<?=$sum3?>" >  	<!--  --> 
                           <input name="sum4" type="hidden" size="3" value="<?=$sum4?>" >  	<!--  --> 
                           <input name="sum5" type="hidden" size="3" value="<?=$sum5?>" >  	<!--  --> 
                           <input name="sum6" type="hidden" size="3" value="<?=$sum6?>" >  	<!--  --> 
                           <input name="sum7" type="hidden" size="3" value="<?=$sum7?>" >  	<!--  --> 
                           <input name="sum8" type="hidden" size="3" value="<?=$sum8?>" >  	<!--  --> 
<div class="clear"> </div>
  
</form>
</div>

<div id="containers" >	
	<div id="result_text" >	</div>
       <div id="display_result">	
	   <div id="display_text"> </div>
	   <div id="res1"> 번호 </div>
	   <div id="res2"> 품명 </div>
	   <div id="res3"> 세부사항 </div>
	   <div id="res4"> 절곡면적 내역 </div>
       <div class="clear"> </div>	   	
  		<?php
		   $counter=0;

           $display_text="";		   
		   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		   $upnum=$row["upnum"];
if((int)$upnum==(int)$tempnum)
      {	
			  $counter++;

			  $num=$row["num"];

			 $steeltype=$row["steeltype"]; 	     
			 $steel_alias=$row["steel_alias"]; 	     

			// print $steeltype;
			// print $upnum . " " . $tempnum;
			 
			 $parentnum=$row["parentnum"]; 	     // 리스트번호  
			 $num=$row["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
			 $outputnum=$row["outputnum"]; 
			 $length1=$row["length1"]; 
			 $length2=$row["length2"]; 
			 $length3=$row["length3"]; 
			 $length4=$row["length4"]; 
			 $length5=$row["length5"]; 
			 $amount1=$row["amount1"]; 
			 $amount2=$row["amount2"]; 
			 $amount3=$row["amount3"]; 
			 $amount4=$row["amount4"]; 
			 $amount5=$row["amount5"]; 
			 $material1=$row["material1"]; 
			 $material2=$row["material2"]; 
			 $material3=$row["material3"]; 
			 $material4=$row["material4"]; 
			 $material5=$row["material5"]; 
			 $material6=$row["material6"]; 
			 $material7=$row["material7"]; 
			 $material8=$row["material8"]; 
			 $sum1=$row["sum1"]; 
			 $sum2=$row["sum2"]; 
			 $sum3=$row["sum3"]; 
			 $sum4=$row["sum4"]; 
			 $sum5=$row["sum5"]; 
			 $sum6=$row["sum6"]; 
			 $sum7=$row["sum7"]; 
			 $sum8=$row["sum8"]; 
			 $total_text=$row["total_text"]; 
			 $datanum=$row["datanum"]; 
   
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
	 

	 
	 //print "--->"  . (count($jbexplode)-1);
   
		// echo '<script type="text/javascript">    changeUri();      </script>';	 
			  ?> 
	   

	   <div id="dis1"> <a class="mylink" href="javascript:del('delete.php?num=<?=$num?>&upnum=<?=$upnum?>&ordercompany=<?=$ordercompany?>&callname=<?=$callname?>&outputnum=<?=$outputnum?>&parentnum=<?=$parentnum?>')"> <?=$counter?> </a> </div>
	   <div id="dis2"> <a class="mylink" href="javascript:load('load.php?num=<?=$num?>&ordercompany=<?=$ordercompany?>&upnum=<?=$upnum?>&sort=<?=$sort?>&recallcal=1&parentnum=<?=$parentnum?>')"> <?=$steeltype?> </a> </div>
	   <div id="dis3">   <?=$steel_alias?> 
	   </div>
	   <div id="dis4">    	&nbsp; <?=$total_text?>  	   </div>
       <div class="clear"> </div>	   

	  
	 <?php
			$start_num--;
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
	 
	       
	   </div> <!-- end of containers -->  
	 </div> 
	   
</div>		
		<br>    
	   </div> 
   
	 <?php
	 for($j=1;$j<=9;$j++)    // 합계 배열 초기화
	 {
//		 print  " " . $steel_arr_sum[$j] . " ";
    if($steel_arr_sum[$j]!=0) {
	    $display_text = $display_text . $options[$j] . ' ==> ' . $steel_arr_sum[$j] . '(㎡),   ';
	                     }
   }	 
	// print '<div class="clear"> </div>';
   //  print $display_text;
?>	
<script>   // 화면을 시간 지연 후 나타내 주기
setTimeout(function(){
/* 	var exititem = "<? echo $exititem; ?>";  // php변수를 자바스크립트에서 사용하는 방법 echo 이용
   if(exititem=='0')	
             $("#exityesno").hide();	 
		else	 
	         $("#exityesno").show();		 */
  cal_display();  //your code here
}, 500);

</script>		   
	   
</body>

<script>
// 셀렉트박스 변경시 이벤트 
$("#steeltype").change( function() {
$("#search_word").val("");  // 검색어 지워주기
});

// Enter키 누를시 submit을 안하도록 제이쿼리로 설정하기
$('input[type="text"]').keydown(function() {
  if (event.keyCode === 13) {
    event.preventDefault();
  };
});

function cal_exe() {  // 절곡면적 계산

var length1= Number($("#dis_length1").val());	
var length2= Number($("#dis_length2").val());	
var length3= Number($("#dis_length3").val());	
var length4= Number($("#dis_length4").val());	
var length5= Number($("#dis_length5").val());	
var amount1= Number($("#dis_amount1").val());	
var amount2= Number($("#dis_amount2").val());	
var amount3= Number($("#dis_amount3").val());	
var amount4= Number($("#dis_amount4").val());	
var amount5= Number($("#dis_amount5").val());	
var total_amount=length1*amount1 + length2*amount2 + length3*amount3 + length4*amount4 + length5*amount5;

var options = new Array();
var sum = new Array();
for(i=1;i<=9;i++) {
		sum[i] = 0 ;
} 

					  options[1] = 'EGI1.6T'; 
					  options[2] = 'EGI1.2T'; 
					  options[3] = 'EGI0.8T'; 
					  options[4] = 'SUS H/L 1.5T'; 
					  options[5] = 'SUS H/L 1.2T'; 					
					  options[6] = 'GI0.45T'; 					
					  options[7] = 'GI0.8T'; 					
					  options[8] = 'Mirror 1.5'; 					
					  options[9] = 'Mirror 1.2'; 	

for(i=1;i<=8;i++) {  // 행합산
	 tmp = $("select[name=material" + i +"]").val();	
	for(j=1;j<=9;j++) { // 옵션을 검색해서 합산
	if(tmp==options[j])
		sum[i] += Math.floor(Number($("#sum" + i).text()) * total_amount /1000000 *100)/100;
	}
} 

for(i=1;i<=8;i++) {
	 tmp = $("select[name=material" + i +"]").val();	
	for(j=i+1;j<=8;j++) { 
	 	 comtmp = $("select[name=material" + j +"]").val();	
	if(tmp==comtmp)
	{
		sum[i] = Math.floor((sum[i] + sum[j])*100)/100;
		sum[j] = 0;
	}
	}	
  $("input[name=sum" + i + "]").val(sum[i]);
} 


txt = "     총길이합계(길이*수량) : " + total_amount + "mm,   ";
record_text="";

for(i=1;i<=8;i++) {
	if(sum[i]>0) {
		txt = txt + $("select[name=material" + i +"]").val() + " =>  ";
		txt = txt + String(sum[i]) + "㎡, " ;
	  record_text = record_text + 	$("select[name=material" + i +"]").val() + "=";
	  record_text = record_text + 	String(sum[i]) + "," ;
	}		
}

$("#result_text").text(txt);
$("input[name=total_text]").val(record_text);

var tmp1 ="?>php echo $modify; ?>";
var tmp2 ="?>php echo $steeltype; ?>";

if(tmp1=="update") {
$("select[name=steeltype]").val(tmp2).prop("selected", true); 	       
}


}  // end of function
   
  
function addline(){
 cal_exe();
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}
function update(){
 cal_exe();
 $("#modify").val("1");			// 수정할 부분 연결
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}

function sorting(href){
 var sort;
 var sort_result;
 sort=$("#sort").val();		
 if(sort=='1') {
	   $("#sort").val("2");
	   sort_result='2';
     }
   else
         {
	   $("#sort").val("1");
	   sort_result='1';	   
		 }
 document.location.href = href + "&sort=" + sort_result;
}

function del(href) 
     {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          }
}
function move(href) 
{
           document.location.href = href;  
		   
}	 

function load(href) 
     {
           document.location.href = href;  
}	



function partShot() {
//특정부분 스크린샷
html2canvas(document.getElementById("containers"))  
//id container 부분만 스크린샷
.then(function (canvas) {
//jpg 결과값
drawImg(canvas.toDataURL('image/jpeg'));
//이미지 저장
saveAs(canvas.toDataURL(), 'make.jpg');
}).catch(function (err) {
console.log(err);
});
}

function drawImg(imgData) {
console.log(imgData);
//imgData의 결과값을 console 로그롤 보실 수 있습니다.
return new Promise(function reslove() {
//내가 결과 값을 그릴 canvas 부분 설정
var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');
//canvas의 뿌려진 부분 초기화
ctx.clearRect(0, 0, canvas.width, canvas.height);

var imageObj = new Image();
imageObj.onload = function () {
ctx.drawImage(imageObj, 10, 10);
//canvas img를 그리겠다.
};
imageObj.src = imgData;
//그릴 image데이터를 넣어준다.

}, function reject() { });

}
function saveAs(uri, filename) {
var link = document.createElement('a');
if (typeof link.download === 'string') {
link.href = uri;
link.download = filename;
document.body.appendChild(link);
link.click();
document.body.removeChild(link);
} else {
window.open(uri);
}
}	 

function submit_form(frm) { 
frm.action='delete.php'; 
frm.submit(); 
return true; 
}

function info() {
	return;
}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}

function Enter_Check(){
    // 엔터키의 코드는 13입니다.	
    if(event.keyCode == 13) {
      exe_search();  // 실행할 이벤트
    }
	return false;
}

function exe_search()
{
      var steeltype = $("select[name=steeltype]").val();
      var search_word = $("#search_word").val();
	  
	  items = new Array(); 
	  items[1] = '가이드레일'; 
	  items[2] = '포스트'; 
	  items[3] = '셔터박스'; 
	  items[4] = '마구리'; 
	  items[5] = '린텔'; 
	  items[6] = '절단판'; 
	  items[7] = '알몰딩'; 
	  items[8] = '알케이스'; 
	  items[9] = '보강'; 
	  items[10] = '삼각쫄대'; 
	  items[11]= '짜부가스켓'; 
	  items[12] = '하장바'; 
	  items[13] = '엘바'; 
	  items[14] = '평철'; 
	  items[15] = '갈바앵글'; 
	  items[16] = '기타절곡물'; 	  
	  
	 var options = new Array(); 
	  options[1] = 'EGI1.6T'; 
	  options[2] = 'EGI1.2T'; 
	  options[3] = 'EGI0.8T'; 
	  options[4] = 'SUS H/L 1.5T'; 
	  options[5] = 'SUS H/L 1.2T'; 					
	  options[6] = 'GI0.45T'; 					
	  options[7] = 'GI0.8T'; 					
	  options[8] = 'Mirror 1.5'; 					
	  options[9] = 'Mirror 1.2'; 	  
					  
      $("#displaysearch").show();
      $("#input_field").hide();
      $("#displaysearch").load("./search.php?mode=search&steeltype=" + steeltype + "&search_word=" + search_word);
	  

	  if(steeltype==items[1]) {  // 가이드레일 선택시
		       $("#material1").val(options[4]).prop("selected", true);
		       $("#material2").val(options[2]).prop("selected", true);
		       $("#material3").val(options[2]).prop("selected", true);
		       $("#material4").val(options[2]).prop("selected", true);
		       $("#material5").val(options[2]).prop("selected", true);
	  }	  
	  if(steeltype==items[2]) {  // 포스트 선택시
		       $("#material1").val(options[5]).prop("selected", true);
		       $("#material2").val(options[2]).prop("selected", true);
		       $("#material3").val(options[2]).prop("selected", true);
		       $("#material4").val(options[2]).prop("selected", true);
		       $("#material5").val(options[2]).prop("selected", true);
	  }	 
	  if(steeltype==items[3]) {  // 셔터박스 선택시
		       $("#material1").val(options[1]).prop("selected", true);
		       $("#material2").val(options[2]).prop("selected", true);
		       $("#material3").val(options[2]).prop("selected", true);
		       $("#material4").val(options[2]).prop("selected", true);
		       $("#material5").val(options[2]).prop("selected", true);
		       $("#material6").val(options[2]).prop("selected", true);
		       $("#material7").val(options[2]).prop("selected", true);
		       $("#material8").val(options[2]).prop("selected", true);
	  }	 	  
	  if(steeltype==items[4]) {  // 마구리 선택시
		       $("#material1").val(options[2]).prop("selected", true);
	  }
	  if(steeltype==items[5]) {  // 린텔 선택시
		       $("#material1").val(options[5]).prop("selected", true);
		       $("#material2").val(options[5]).prop("selected", true);
	  }	  
	  if(steeltype==items[6]) {  // 절단판 선택시
		       $("#material1").val(options[2]).prop("selected", true);
		       $("#material2").val(options[2]).prop("selected", true);
	  }	  	  
	  if(steeltype==items[7]) {  // 알몰딩 선택시
		       $("#material1").val(options[2]).prop("selected", true);
	  }	  
	  if(steeltype==items[8]) {  // 알케이스  선택시
		       $("#material1").val(options[6]).prop("selected", true);
	  }	 	  
	   if(steeltype==items[9]) {  // 보강  선택시
		       $("#material1").val(options[2]).prop("selected", true);
	  }	   
	  
	  if(steeltype==items[10]) {  // 삼각쫄대  선택시
		       $("#material1").val(options[2]).prop("selected", true);
	  }	  
	  if(steeltype==items[11]) {  // 짜부가스켓  선택시
		       $("#material1").val(options[3]).prop("selected", true);
	  }	  	 
	  if(steeltype==items[12]) {  // 하장바  선택시
		       $("#material1").val(options[5]).prop("selected", true);
	  }	  	  
	  if(steeltype==items[13]) {  // 엘바  선택시
		       $("#material1").val(options[1]).prop("selected", true);
	  }	  	  
	  if(steeltype==items[14]) {  // 평철  선택시
		       $("#material1").val(options[2]).prop("selected", true);
	  }	  	  
	  if(steeltype==items[15]) {  // 갈바앵글  선택시
		       $("#material1").val(options[2]).prop("selected", true);
	  }	 
	  if(steeltype==items[16]) {  // 기타절곡  선택시
		       $("#material1").val(options[2]).prop("selected", true);
		       $("#material2").val(options[2]).prop("selected", true);
		       $("#material3").val(options[2]).prop("selected", true);
		       $("#material4").val(options[2]).prop("selected", true);
		       $("#material5").val(options[2]).prop("selected", true);
		       $("#material6").val(options[2]).prop("selected", true);
		       $("#material7").val(options[2]).prop("selected", true);
		       $("#material8").val(options[2]).prop("selected", true);
	  }	 	  
}

function apply_below() // 전체적용을 클릭했을 때
{
	 var tmp = $("select[name=material1]").val();
	 for(i=2;i<=8;i++){
     $("#material" + i).val(tmp).prop("selected", true);
     }
}

function cal_display() {  // 화면용 절곡면적 계산
    var tmp="<? echo $display_text; ?>";  // php변수 불러오는 방법
    var tmp1="<? echo $modify; ?>";  // php변수 불러오는 방법
    var tmp2="<? echo $tmp_steeltype; ?>";  // php변수 불러오는 방법
    var tmp3="<? echo $tmp_steel_alias; ?>";  // php변수 불러오는 방법
    var tmp4="<? echo $tmp_copied_file_name; ?>";  // php변수 불러오는 방법
    var tmp5="<? echo $tmp_uploaded_file; ?>";    // php변수 불러오는 방법
    var tmp_length1="<? echo $tmp_length1; ?>";    // php변수 불러오는 방법
    var tmp_length2="<? echo $tmp_length2; ?>";    // php변수 불러오는 방법
    var tmp_length3="<? echo $tmp_length3; ?>";    // php변수 불러오는 방법
    var tmp_length4="<? echo $tmp_length4; ?>";    // php변수 불러오는 방법
    var tmp_length5="<? echo $tmp_length5; ?>";    // php변수 불러오는 방법
    var tmp_amount1="<? echo $tmp_amount1; ?>";    // php변수 불러오는 방법
    var tmp_amount2="<? echo $tmp_amount2; ?>";    // php변수 불러오는 방법
    var tmp_amount3="<? echo $tmp_amount3; ?>";    // php변수 불러오는 방법
    var tmp_amount4="<? echo $tmp_amount4; ?>";    // php변수 불러오는 방법
    var tmp_amount5="<? echo $tmp_amount5; ?>";    // php변수 불러오는 방법
    var tmp_material1="<? echo $tmp_material1; ?>";    // php변수 불러오는 방법
    var tmp_material2="<? echo $tmp_material2; ?>";    // php변수 불러오는 방법
    var tmp_material3="<? echo $tmp_material3; ?>";    // php변수 불러오는 방법
    var tmp_material4="<? echo $tmp_material4; ?>";    // php변수 불러오는 방법
    var tmp_material5="<? echo $tmp_material5; ?>";    // php변수 불러오는 방법
    var tmp_material6="<? echo $tmp_material6; ?>";    // php변수 불러오는 방법
    var tmp_material7="<? echo $tmp_material7; ?>";    // php변수 불러오는 방법
    var tmp_material8="<? echo $tmp_material8; ?>";    // php변수 불러오는 방법
    var tmp_sum1="<? echo $tmp_sum1; ?>";    // php변수 불러오는 방법
    var tmp_sum2="<? echo $tmp_sum2; ?>";    // php변수 불러오는 방법
    var tmp_sum3="<? echo $tmp_sum3; ?>";    // php변수 불러오는 방법
    var tmp_sum4="<? echo $tmp_sum4; ?>";    // php변수 불러오는 방법
    var tmp_sum5="<? echo $tmp_sum5; ?>";    // php변수 불러오는 방법
    var tmp_sum6="<? echo $tmp_sum6; ?>";    // php변수 불러오는 방법
    var tmp_sum7="<? echo $tmp_sum7; ?>";    // php변수 불러오는 방법
    var tmp_sum8="<? echo $tmp_sum8; ?>";    // php변수 불러오는 방법
	
      $("#display_text").text(tmp);
	if(tmp1=='update') {
      $("#input_field").show();
      $("#dis_steeltype").text(tmp2);	  
      $("#dis_steel_alias").text(tmp3);	  	  
	  $("#display_img").html("<img src='" + tmp5 + "'>");	  
 
    $("input[name=length1]").val(tmp_length1); 
    $("input[name=length2]").val(tmp_length2); 
    $("input[name=length3]").val(tmp_length3); 
    $("input[name=length4]").val(tmp_length4); 
    $("input[name=length5]").val(tmp_length5); 
    $("input[name=amount1]").val(tmp_amount1); 
    $("input[name=amount2]").val(tmp_amount2); 
    $("input[name=amount3]").val(tmp_amount3); 
    $("input[name=amount4]").val(tmp_amount4); 
    $("input[name=amount5]").val(tmp_amount5); 	
    $("select[name=material1]").val(tmp_material1); 
    $("select[name=material2]").val(tmp_material2); 
    $("select[name=material3]").val(tmp_material3); 
    $("select[name=material4]").val(tmp_material4); 
    $("select[name=material5]").val(tmp_material5); 	
    $("select[name=material6]").val(tmp_material6); 	
    $("select[name=material7]").val(tmp_material7); 	
    $("select[name=material8]").val(tmp_material8); 	
    $("#sum1").text(tmp_sum1); 
    $("#sum2").text(tmp_sum2); 
    $("#sum3").text(tmp_sum3); 
    $("#sum4").text(tmp_sum4); 
    $("#sum5").text(tmp_sum5); 	
    $("#sum6").text(tmp_sum6); 	
    $("#sum7").text(tmp_sum7); 	
    $("#sum8").text(tmp_sum8); 		
	}
	  
}  // end of function

</script>  


</html>