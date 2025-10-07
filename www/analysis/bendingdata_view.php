 <?php
  session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
   
     if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="not";
$num=$_REQUEST["num"];  // 출고현황에서 넘어온 출고번호
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호

if((int)$num==0)
 {
  require_once("../lib/mydb.php");
  $pdo = db_connect();
											  
								 try{  
							  $sql = "select * from chandj.atbending order by num desc limit 1";
							  $stmh = $pdo->prepare($sql); 

							  $stmh->bindValue(1,$num,PDO::PARAM_STR); 
							  $stmh->execute();
							  $counter = $stmh->rowCount();            
							  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.

							   $num=$row["num"];

									      
							  } catch (PDOException $Exception) {
							  print "오류: ".$Exception->getMessage();
							  }  
 }				  
	  else
	  {
				  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크 출고현황에서 부르지 않았을때 
				   $num=$_REQUEST["num"];  
	  }
							  
  
   if(isset($_REQUEST["page"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $page=$_REQUEST["page"];
  else
   $page=1;   

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $find=$_REQUEST["find"];
  else
   $find="";

  if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $process=$_REQUEST["process"];
  else
   $process="전체";

  if(isset($_REQUEST["sort"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $sort=$_REQUEST["sort"];
  else
   $sort="1";


$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];
     
  require_once("../lib/mydb.php");
  $pdo = db_connect();

    try{
      $sql = "select * from chandj.atbending where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

 
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
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 
?>


   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 주일기업 통합정보시스템 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css"> 

   <link  rel="stylesheet" type="text/css" href="../css/radio.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/bending_write.css"> 

   </head>
 
   <body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://5130.co.kr/order/order.js"></script>
   
   <div id="wrap">
	  
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
    </div>  
    <div id="content">
    <br><br>
	 <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post"   action="bendingdata_insert.php?mode=modify&num=<?=$num?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>" enctype="multipart/form-data"> 
  <?php  } 
else  {
  ?>
	<form  name="board_form" method="post" action="bendingdata_insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>&callback=1" enctype="multipart/form-data"> 
  <?php
	}
  ?>	   
    <div id="work_col3"> <h2> &nbsp; &nbsp; 절곡데이터 등록  &nbsp; &nbsp;  데이터 고유번호:&nbsp; <?=$num?> </h2>

	      <div class="clear"></div>	
	 <div id="sero1"> 절곡구분 :  </div>	 
	 <div id="sero2">  <?=$steeltype?> 

	 </div> 
	 <div id="sero7"> <input type="text" id="steel_alias" name="steel_alias" size="47" value="<?=$steel_alias?>" disabled > </div>	 
	 <div id="sero3"> 등록이미지 :  </div>
	 <div id="sero4">   <?=$copied_file_name?>
	 </div>
	 	 <div class="clear"></div> 
 <div id="work_col_left">	 
	 <div id="preview"> <img src="<?=$uploaded_file?>"> </div>
 </div>

	 <div id="work_col_right">	 
	 <br><br>
	 <?php 
	 for($j=0;$j<=7;$j++) {
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
	 print '<div class="a_col"> </div>';  // 첫 타이틀
	    for($i=1;$i<=25;$i++) {
			print '<div class="a_col">' . $str . $i . '</div>';
		}
		    print ' <div class="clear"></div> ';
			print '<div class="a_col">' .  ($j+1) . '행</div>';	
	    for($i=0;$i<25;$i++) {
		    $temp= $str . ($i+1);
			if((int)$arr[$temp]>0)
				   $altertmp=$arr[$temp];
			   else
				   $altertmp="";
			   
			print '<input type="text"  name="' . $str . ($i+1) . '" id="'  . $str . ($i+1) .'" style="width:36px;height:15px;color:blue;font-size:17px;" disabled value="' . $altertmp . '">';
		}		
	       print ' <div class="clear"></div> ';	
	 }
?>		
	 	   
  
		<input type="hidden" id="upnum" name="upnum" value="<?=$upnum?>" size="5" > 		
	
     <div class="clear"></div> 
	 </div>	
  			</form>       
		
<br><br>
 <div id="write_button">
     <a href="bending_write_form.php?mode=not&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>"> <img src="../img/write.png"></a>
     <a href="bending_write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"><img src="../img/modify.png"></a>&nbsp;
	 
    <a href="bendingdatalist.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"><img src="../img/list.png"></a>
    <a href="bendingdata_copy.php?num=<?=$num?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>">데이터 복사(신규) </a>
				</div> <br>
				</div>
 
	  </div> 

 

         </div>	 <!-- end of col3 -->  
	   </div>   <!-- end of wrap -->   

	</body>
 </html>
