<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
		$user_name= $_SESSION["name"];
    } 

	if($_SESSION["part"]=='es') 
	{
   header ("Location:../es/index.php");
	exit;  
	}	
?>

<?php
// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$readIni = array();   // steel 환경파일 불러오기 정면에 철판사용에 대한 그래프 띄우기
$readIni = parse_ini_file("./steel/steelinfo.ini",false);	

$total = $readIni['yesterdaytotal'] +$readIni['yesterdayused'];
$used = $readIni['yesterdayused'];
$saved = $readIni['yesterdaysaved'];
$used_rate = round($used / 10000000 *1000) / 10;
$saved_rate = round($saved / 500000 *1000) / 10; 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }   
// echo("<meta http-equiv='refresh' content='20'>");  // 1초후 새로고침
 ?>

 
 <!DOCTYPE html>
 <html>
 <head>
 <meta charset="UTF-8">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script>
<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
<link rel="stylesheet" href="./css/style.css"/>
<link rel="stylesheet" type="text/css" href="./css/index.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
 <title> 미래기업 통합정보시스템 </title>
 </head>
<body>
 <div id="wrap">
 <div id="header"> 
   <?php include "./lib/top_login2.php"; ?>   
 </div> <!-- end of header --> 
 <div id="menu">
   <?php include "./lib/top_menu1.php"; ?>
     <br><br>
 </div> <!-- end of menu -->
  <div id="content">
    	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	    <input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 			
                                                <div id="vacancy" style="display:none">  </div>
  
  
  		    <div class="card-header"> 	
                <div class="input-group p-2 mb-0">	
                     <span class="input-group-text" style="width:450px;">				
							전일 원자재 사용금액 : <?=number_format($used)?> (원) /천만원기준 &nbsp;&nbsp;	</span>		 &nbsp;&nbsp;			
							<div class="progress" style="height: 40px; width:660px;">
							<div class="progress-bar" role="progressbar" style="width: <?=$used_rate?>%;" aria-valuenow="<?=$used_rate?>" aria-valuemin="0" aria-valuemax="100"><?=$used_rate?>%</div>
							</div>
							</div>
                <div class="input-group p-2 mb-1">								
                     <span class="input-group-text" style="width:450px;">											
							전일 잔재활용 절감금액 : <?=number_format($saved)?> (원) /오십만원기준 &nbsp;&nbsp;	</span>		 &nbsp;&nbsp;			
							<div class="progress" style="height: 40px; width:660px;">														
							  <div class="progress-bar bg-info" role="progressbar" style="width:  <?=$saved_rate?>%" aria-valuenow="<?=$saved_rate?>" aria-valuemin="0" aria-valuemax="100"><?=$saved_rate?>%</div>
							</div>	
						</div>			
				 </div>

 <?php  
   require_once("./lib/mydb.php");
   $pdo = db_connect();   
   $now = date("Y-m-d",time()) ;  
   $a="   where endworkday='$now' order by num desc ";  
   $sql="select * from mirae8440.work " . $a; 					
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0) 
       include "./load_jamb.php";

   $a = " where deadline='$now' order by num desc ";    
   $sql="select * from mirae8440.ceiling " . $a; 					
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0) 
        include "./load_ceiling.php";

   $sql="select * from mirae8440.request where indate='' order by outdate desc" ; 		// 미입고 원자재
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0) 
      include "./load_request.php";

   $sql="select * from mirae8440.steel order by num desc "; 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $total_row=$stmh->rowCount();
   if($total_row>0) 
       include "./load_steel.php";
   
// 쟘 금일 출고 내역 추출

require_once("./lib/mydb.php");
  $pdo = db_connect();	

$now = date("Y-m-d",time()) ;
  
		$a="   where endworkday='$now' order by num desc ";  
  
	   $sql="select * from mirae8440.work " . $a; 	
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
				$total_row = $temp1;     // 전체 글수						 
			
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	       $group=array();
	       $item=array();
	       $man=array();
	       $sumStr=array();
	       $firstordArr=array();
	       $secondordArr=array();
	       $memoArr=array();
		   $counter=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		   $counter++;
			$group[$counter]=" 금일 쟘(jamb) 출고 " ;   
			$item[$counter]="현장명 : " . $row["workplacename"];   
			$man[$counter]=$row["worker"] . " 소장" ;   
			   
			  $firstordArr[$counter]="원청 : " . $row["firstord"];

			  $secondordArr[$counter]="발주처 : " .$row["secondord"];

			  $chargedman=$row["chargedman"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $delicompany=$row["delicompany"];			  

			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];

			  $memoArr[$counter]="비고 : " . $row["memo"];

			  
			  $sum[0] = $sum[0] + (int)$widejamb;
			  $sum[1] += (int)$normaljamb;
			  $sum[2] += (int)$smalljamb;
			  $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
			  
			  $workitem="";
				 if($widejamb!="")
					    $workitem="막판" . $widejamb . " "; 
				 if($normaljamb!="")
					    $workitem .="막(無)" . $normaljamb . " "; 					
				 if($smalljamb!="")
					    $workitem .="쪽쟘" . $smalljamb . " "; 												   
			   $sumStr[$counter]="설치 수량 : " . $workitem . " (SET)";  	 
			  
			  $dis_text = "막판 : " . $sum[0] . " 세트, 막판(無) : " . $sum[1] . " 세트, 쪽쟘 : "  . $sum[2] . " 세트, 합계 : " . $sum[3] . " 세트" ; 

		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";	
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";						
		      if($startday!="0000-00-00" and $startday!="1970-01-01" and $startday!="")  $startday = date("Y-m-d", strtotime( $startday) );
					else $startday="";		  			  	  
		      if($testday!="0000-00-00" and $testday!="1970-01-01" and $testday!="")  $testday = date("Y-m-d", strtotime( $testday) );
					else $testday="";		  
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
              $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") $draw_done = "OK";		 
              $measure_done="";			  
			  if(substr($row["measureday"],0,2)=="20") $measure_done = "OK";		    			  

			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }     
      
// 쟘 금일 출고 내역 추출

require_once("./lib/mydb.php");
  $pdo = db_connect();	

$now = date("Y-m-d",time()) ;
  
		$a="   where deadline='$now' order by num desc ";  
  
	   $sql="select * from mirae8440.ceiling " . $a; 	
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
				$total_row = $temp1;     // 전체 글수						 
			
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	  
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		   $counter++;
			$group[$counter]=" 금일 조명천장(Ceiling&LC) 출고 " ;   
			$item[$counter]="현장명 : " . $row["workplacename"];   
			$man[$counter]="";
			   
			  $firstordArr[$counter]="원청 : " . $row["firstord"];

			  $secondordArr[$counter]="발주처 : " .$row["secondord"];			
			  
			  $memoArr[$counter]="비고 : " . $row["memo"];			  

			  $demand=$row["demand"];
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  $car_insize=$row["car_insize"];			  
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

            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];				  
			  
			  $workitem="";
				if((int)$bon_su>0)
					    $workitem="본천장 " . $bon_su . "  "; 
				if((int)$lc_su>0)
				        $workitem .="L/C " . $lc_su . "  "; 					
				if((int)$etc_su>0)
					    $workitem .="기타 " . $etc_su . "  "; 	
				if((int)$air_su>0)
					    $workitem .="기타 " . $air_su . "  "; 	
					
			   $sumStr[$counter]="출고 수량 : " . $workitem . " (SET)";  	 			  
			
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }     
        
   
   
	?>
	      <div class="clear"></div>	  		  
		  
 <!--  <div id="main_img"><img src="./img/main_img.gif"></div><!-- end of content -->
 </div> 
  <!-- <button type="button" onclick="click_it();return false;" value="클릭"> 알림창 </button> -->

 </div> <!-- end of wrap -->  
 
</body>


</html>


<script>
 var rowNum = <?php echo $counter; ?>; 
 var dataCount=0;

$(document).ready(function(){
			
setTimeout(function() {
        load();
     },10000);
});		


 function sleep (delay) {
   var start = new Date().getTime();
   while (new Date().getTime() < start + delay);
}

function load (){   

 var arr0 = <?php echo json_encode($group);?> ;
 var arr1 = <?php echo json_encode($item);?> ;
 var arr2 = <?php echo json_encode($man);?> ;
 var arr3 = <?php echo json_encode($sumStr);?> ;
 var arr4 = <?php echo json_encode($firstordArr);?> ;
 var arr5 = <?php echo json_encode($secondordArr);?> ;
 var arr6 = <?php echo json_encode($memoArr);?> ;

 dataCount++;  
 if(dataCount>rowNum)  {
	          dataCount=1;
						setTimeout(function(){
				location.reload();
				},3000); // 3000밀리초 = 3초  
      }

		alertify.alert("<h2>" + arr0[dataCount] + "  " + "<br><br>" + arr2[dataCount] +"</h2>",
		"<h3><br>  <div class='animate__animated animate__heartBeat' style='color:grey'>" 
		+ arr4[dataCount] + "</div> 		</h3> <br> <h3> <div class='animate__animated animate__heartBeat'  style='color:grey' >" 
		+ arr5[dataCount] + "</div> 		</h3> <br> <h3> <div class='animate__animated animate__heartBeat' >"
		+ arr1[dataCount] + "</div> 		</h3> <br> <h3><br>  <div  class='animate__animated animate__heartBeat' style='color:blue;' >   "  + arr3[dataCount] + "</div> </h3>"
		+ " <h4><br>  <div  class='animate__animated animate__heartBeat' style='color:brown;' >   "  + arr6[dataCount] + "</div> 		</h4>");

setTimeout("load()", 5000);

}

</script>