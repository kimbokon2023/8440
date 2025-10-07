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
 
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/outorder.css">

 <title> 미래기업 서한컴퍼니 </title>
  </head>
 
<body>
<div class="container-fluid">  
<div class="d-flex mb-1 justify-content-center">    
  <a href="../index.php"><img src="../img/toplogo.jpg" style="width:100%;" ></a>	
</div>

  <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

</div>  

<?php

 $file_dir = '../uploads/'; 
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
  $year=$_REQUEST["year"];   // 년도 체크박스


 if(isset($_REQUEST["check_draw"])) 
	 $check_draw=$_REQUEST["check_draw"];   // 도면 미설계List
	   else
		 $check_draw=$_POST["check_draw"]; 

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 
 if(isset($_REQUEST["output_check"])) 
	 $output_check=$_REQUEST["output_check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
	if(isset($_POST["output_check"]))   
         $output_check=$_POST["output_check"]; // 미출고 리스트 POST사용  
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
	 $measure_check=$_REQUEST["measure_check"]; // 미실측리스트
   else
	if(isset($_POST["measure_check"]))   
         $measure_check=$_POST["measure_check"]; // 미실측리스트
	 else
		 $measure_check='0';	
	 
  if(isset($_REQUEST["plan_output_check"])) 
	 $plan_output_check=$_REQUEST["plan_output_check"]; // 출고예정`
   else
	if(isset($_POST["plan_output_check"]))   
         $plan_output_check=$_POST["plan_output_check"]; // 출고예정  
	 else
		 $plan_output_check='0';
	 
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
  
   if(isset($_REQUEST["scale"]))   
	 $scale=$_REQUEST["scale"];
    else
		  $scale=30;	  
  
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
	 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.oem where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
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
  $chargedmantel=$row["chargedmantel"];
  $orderday=$row["orderday"];
  $measureday=$row["measureday"];
  $drawday=$row["drawday"];
  $deadline=$row["deadline"];
  $workday=$row["workday"];
  $worker=$row["worker"];
  $endworkday=$row["endworkday"];
  $material1=$row["material1"];
  $material2=$row["material2"];
  $material3=$row["material3"];
  $material4=$row["material4"];
  $material5=$row["material5"];
  $material6=$row["material6"];
  $widejamb=$row["widejamb"];
  $normaljamb=$row["normaljamb"];
  $smalljamb=$row["smalljamb"];
  $memo=$row["memo"];
  $regist_day=$row["regist_day"];
  $update_day=$row["update_day"];
  
  $delivery=$row["delivery"];
  $delicar=$row["delicar"];
  $delicompany=$row["delicompany"];
  $delipay=$row["delipay"];
  $delimethod=$row["delimethod"];
  $demand=$row["demand"];
  $startday=$row["startday"];
  $testday=$row["testday"];
  $hpi=$row["hpi"];  
  $first_writer=$row["first_writer"];
  $update_log=$row["update_log"];
  $filename1=$row["filename1"];
  $filename2=$row["filename2"];
  $imgurl1="../imgwork/" . $filename1;
  $imgurl2="../imgwork/" . $filename2;
  
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
            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];	
			  
		      $first_item1=$row["first_item1"];	
			  $first_item2=$row["first_item2"];	
			  $first_item3=$row["first_item3"];	
			  $first_item4=$row["first_item4"];	
			  $second_item1=$row["second_item1"];	
			  $second_item2=$row["second_item2"];	
			  $second_item3=$row["second_item3"];	
			  $second_item4=$row["second_item4"];			

			  $third_item1=$row["third_item1"];	
			  $third_item2=$row["third_item2"];	
			  $third_item3=$row["third_item3"];	
			  $third_item4=$row["third_item4"];	
			  
			  $forth_item1=$row["forth_item1"];	
			  $forth_item2=$row["forth_item2"];	
			  $forth_item3=$row["forth_item3"];	
			  $forth_item4=$row["forth_item4"];			  
			  
			  $fifth_item1=$row["fifth_item1"];	
			  $fifth_item2=$row["fifth_item2"];	
			  $fifth_item3=$row["fifth_item3"];	
			  $fifth_item4=$row["fifth_item4"];	
			  
			  $sixth_item1=$row["sixth_item1"];	
			  $sixth_item2=$row["sixth_item2"];	
			  $sixth_item3=$row["sixth_item3"];	
			  $sixth_item4=$row["sixth_item4"];			  

			  $seventh_item1=$row["seventh_item1"];	
			  $seventh_item2=$row["seventh_item2"];	
			  $seventh_item3=$row["seventh_item3"];	
			  $seventh_item4=$row["seventh_item4"];	
			  
			  $eighth_item1=$row["eighth_item1"];	
			  $eighth_item2=$row["eighth_item2"];	
			  $eighth_item3=$row["eighth_item3"];	
			  $eighth_item4=$row["eighth_item4"];			  

			  $ninth_item1=$row["ninth_item1"];	
			  $ninth_item2=$row["ninth_item2"];	
			  $ninth_item3=$row["ninth_item3"];	
			  $ninth_item4=$row["ninth_item4"];	
			  
			  $tenth_item1=$row["tenth_item1"];	
			  $tenth_item2=$row["tenth_item2"];	
			  $tenth_item3=$row["tenth_item3"];	
			  $tenth_item4=$row["tenth_item4"];					  

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
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 ?>
 
<div id="container">

<div class="d-flex mb-1 justify-content-center"> 
	  
<div id="estimate_text2" style="width:840px;height:650px;font-size:14px;"> 
			
	   <div class="sero1" style="width:400px;">  <h3> 서한 컴퍼니 발주 </h3> </div>
		      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
	   
       <button type="button"   class="btn btn-secondary btn-sm" onclick="location.href='list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$check?>&check=<?=$check?>&check_draw=<?=$check_draw?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&scale=<?=$scale?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1'"> 목록 </button>
       <button type="button"   class="btn btn-secondary btn-sm" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1&check_draw=<?=$check_draw?>'">  수정 </button>
	<?php if( $level < 5 )    {     ?> 
       <button type="button"   class="btn btn-secondary btn-sm" onclick="location.href='javascript:del(`delete.php?num=<?=$num?>&page=<?=$page?>&check=<?=$check?>`)'">  삭제 </button>
       <button type="button"   class="btn btn-secondary btn-sm" onclick="location.href='write_form.php'">  글쓰기 </button>					
       <button type="button"   class="btn btn-secondary btn-sm" onclick="location.href='copy_data.php?mode=copy&num=<?=$num?>&page=<?=$page?>&scale=<?=$scale?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1'">  데이터복사 </button>	   
       <button type="button"   class="btn btn-secondary btn-sm" onclick="window.open(`transform.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>`,`출고증 인쇄`,`left=50,top=50, scrollbars=yes, toolbars=no,width=1200,height=800`);">  발주서인쇄 </button>	   
	<?php   }  ?>		           	   
	<div class="clear"> </div> 	   
	  <br>
      <div class="sero1"> 현장명 : </div> 
         <div class="sero2"><input type="text" name="workplacename" value="<?=$workplacename?>" size="50" placeholder="현장명" required   >	</div>	  			
	<div class="clear"> </div> 
         <div class="sero1"> 현장주소 : </div> 
         <div class="sero2"><input type="text" name="address" value="<?=$address?>" size="50" placeholder="현장주소"   ></div>	  	 
		 
		 <div class="clear"> </div> 
         <div class="sero1"> 매입처 : </div> 
         <div class="sero2"><input type="text" id="firstord" name="firstord" value="<?=$firstord?>" size="15" placeholder="매입처"   > </div>
	    <div class="sero1"  style="text-align:right;">  담당 : </div> 
	    <div class="sero2"> <input type="text" id="firstordman" name="firstordman" value="<?=$firstordman?>" size="10" onkeydown="JavaScript:Enter_firstCheck();"  placeholder="원청담당"   ></div>
		    <div class="sero1">  연락처 : </div> 
	    <div class="sero2"> <input type="text" id="firstordmantel"  name="firstordmantel" value="<?=$firstordmantel?>" size="14" placeholder="연락번호"   ></div>
        <div class="clear"> </div> 		
	    <div class="sero1"> 발주처 : </div> 
         <div class="sero2"><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" size="15" placeholder="발주처"   > </div>
	    <div class="sero1" style="text-align:right;">  담당 : </div> 
	    <div class="sero2"> <input type="text" id="secondordman" name="secondordman" value="<?=$secondordman?>" size="10" placeholder="발주처 담당자" onkeydown="JavaScript:Enter_Check();"   > </div>
	    <div class="sero1">  연락처 : </div> 
	    <div class="sero2"> <input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" size="14" placeholder="연락번호"   ></div> 
	
		<div class="clear"> </div> 
		
        <div class="sero1">  담당자 : </div>   
	    <div class="sero2"> <input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" size="15" placeholder="현장담당자"  onkeydown="JavaScript:Enter_chargedman_Check();"    > </div>
		<div class="sero1"  style="text-align:right;">  연락처 : </div> 
	    <div class="sero2"> <input type="text" name="chargedmantel"  id="chargedmantel"  value="<?=$chargedmantel?>" size="14"  placeholder="현장담당전화"  ></div> 
        <div class="clear"> </div> 	
        <div class="space"> </div> 	
        <div class="clear"> </div> 			
        <span> 접수일 : 
         <input type="date" name="orderday" id="orderday" value="<?=$orderday?>"     > 
		 
           발주일 : 
         <input type="date" name="startday" id="startday" value="<?=$startday?>"   > 	</span>
		 <span style="color:red;">
	     납기일 : 
         <input type="date" name="deadline" id="deadline" value="<?=$deadline?>"   >  </span>
		 <span style="color:blue;">
		 출고일 :<input type="date" name="workday" id="workday" value="<?=$workday?>"   >  </span>
        <div class="clear"> </div> 	
        <div class="space"> </div> 	
        <div class="clear"> </div> 		 
		<div class="sero1" > 타입(Type) </div> 
        <div class="sero4"><input type="text" name="type1" value="<?=$type1?>" size="5" placeholder="타입"   > </div>				
		<div class="sero1"  > 인승 </div> 
        <div class="sero4"><input type="text" name="inseung1" value="<?=$inseung1?>" size="5" placeholder="인승"   > </div>	
		<div class="sero1" > car insize </div> 
        <div class="sero4"><input type="text" name="car_insize1" value="<?=$car_insize1?>" size="8" placeholder="Car insize"   > </div>	
       <div class="clear"> </div> 	 		        
	    <div class="sero1" >  L/C수량: </div> 
	    <div class="sero4"> <input type="text" name="lc_su" value="<?=$lc_su?>" size="2" placeholder="수량"   ></div>	
	    <div class="sero1" >  기타 수량: </div> 
	    <div class="sero4"> <input type="text" name="etc_su" value="<?=$etc_su?>" size="2" placeholder="수량"    ></div>			        
        <div class="clear"> </div> 	 
        <div class="space"> </div> 	
        <div class="clear"> </div> 		
  	   <div id="delivery_col" > 운송비 : <input type="text" name=delivery value="<?=$delivery?>" placeholder="운반비내역"   >	             	
	   <span style="color:red;" > 운임(있을시 기록) : </span> <input type="text" name=delipay value="<?=$delipay?>" placeholder="운임금액" onkeyup="inputNumberFormat(this)"    >	  		
	   
        <div class="clear"> </div> 	 
        <div class="space"> </div> 	
        <div class="clear"> </div> 		



<div class="box1" >  
 <div class="box2 box_col1" style="width:500px;"  >  	
	   <div class="sero6" style="width:200px;" > 비고(마구리/도장) </div>    
	   <div class="clear"> </div> 	
	   <div class="sero5"> <textarea rows="3" cols="65" name="memo" placeholder="비고1"   ><?=$memo?></textarea></div> <br><br><br><br><br>
       <div class="clear"> </div> 
	 
	   <div class="sero1" style="font-size:18px;color:red;width:400;"> 청구일자 : <input type="date" name="demand" id="demand" value="<?=$demand?>" size="15" placeholder="청구일, 계산서발행"    >  </div> 
	   
	   	</div> <!-- end of box_col1-->	

	   <div class="box1 box_col2"  >  
	   
	   <?php  print "최초등록자 : " . $first_writer ; ?> <br><br>
	   <?php  print "수정기록 : "; ?> <br> <textarea rows="3" cols="32"  name="dispaly_log" > <?=$update_log?> </textarea>
	   	</div> <!-- end of box_col3-->

	
		   	</div>	 <!-- end of box1-->
	
       <div class="clear"> </div> 	   	
	   </div>	    		
   
	   </div> 
		
		
	</form>
 </div>
 </div>
 </body>
</html>    

 <script language="javascript">
 
 $(document).ready(function(){  // 조회화면 구현시 적용함 input을 모두 disabled 하기
	 // $("div *").disable();
   $("div *").find("input,textarea").prop("disabled",true);	
 });
 
/* function new(){
 window.open("viewimg.php","첨부이미지 보기", "width=300, height=200, left=30, top=30, scrollbars=no,titlebar=no,status=no,resizable=no,fullscreen=no");
} */
var imgObj = new Image();
function showImgWin(imgName) {
imgObj.src = imgName;
setTimeout("createImgWin(imgObj)", 100);
}
function createImgWin(imgObj) {
if (! imgObj.complete) {
setTimeout("createImgWin(imgObj)", 100);
return;
}
imageWin = window.open("", "imageWin",
"width=" + imgObj.width + ",height=" + imgObj.height);
}

   function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function date_mask(formd, textid) {

/*
input onkeyup에서
formd == this.form.name
textid == this.name
*/

var form = eval("document."+formd);
var text = eval("form."+textid);

var textlength = text.value.length;

if (textlength == 4) {
text.value = text.value + "-";
} else if (textlength == 7) {
text.value = text.value + "-";
} else if (textlength > 9) {
//날짜 수동 입력 Validation 체크
var chk_date = checkdate(text);

if (chk_date == false) {
return;
}
}
}

function checkdate(input) {
   var validformat = /^\d{4}\-\d{2}\-\d{2}$/; //Basic check for format validity 
   var returnval = false;

   if (!validformat.test(input.value)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else { //Detailed check for valid date ranges 
    var yearfield = input.value.split("-")[0];
    var monthfield = input.value.split("-")[1];
    var dayfield = input.value.split("-")[2];
    var dayobj = new Date(yearfield, monthfield - 1, dayfield);
   }

   if ((dayobj.getMonth() + 1 != monthfield)
     || (dayobj.getDate() != dayfield)
     || (dayobj.getFullYear() != yearfield)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else {
    //alert ('Correct date'); 
    returnval = true;
   }
   if (returnval == false) {
    input.select();
   }
   return returnval;
  }
  
function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
}  

function copy_below(){	
   
}  

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;

    }
}
     function del(href) 
     {
		 var level=Number($('#session_level').val());
		 if(level>2)
		     alert("삭제하려면 관리자에게 문의해 주세요");
		 else {
         if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          } 
		 }

     }
	 
 function displayoutputlist(){	 	 
		 
	 }

 	 
</script>
