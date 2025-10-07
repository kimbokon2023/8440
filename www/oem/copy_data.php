<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>

<?php

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:http://8440.co.kr/login/logout.php"); 
         exit;
   }   

 ?>

 <?php

  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

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

 $yearcheckbox=$_REQUEST["yearcheckbox"];   // 년도 체크박스
 $year=$_REQUEST["year"];   // 년도 체크박스
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="copy"){
    try{
      $sql = "select * from mirae8440.oem where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];

      $copied_file_0 = "../uploads/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/". $row["file_copied_1"];
	 }

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
  $measureday=null;
  $drawday=null;
  $deadline=null;
  $workday=null;
  $worker=$row["worker"];
  $endworkday=null;
  
  $widejamb=$row["widejamb"];
  $normaljamb=$row["normaljamb"];
  $smalljamb=$row["smalljamb"];
  $memo=$row["memo"];
  $regist_day=$row["regist_day"];
  $update_day=$row["update_day"];
  $measureday=$row["measureday"];
  $drawday=$row["drawday"];
  $workday=$row["workday"];
  $deadline=$row["deadline"];
  $endworkday=$row["endworkday"];  

  $delicar="없음";
  $delipay="";
  $delimethod="없음";
  $demand=null;
  $startday=$row["startday"];
  $testday=$row["testday"];
  $hpi=$row["hpi"]; 
  $first_writer=$row["first_writer"];
  $update_log=$row["update_log"];  


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
  }
$mode="";

// $mode를 이용해서 copy_data.php에서 기존 데이터를 사용한다.
	 
$material_arr = array('','304 Hair Line 1.2T','304 HL 1.2T','304 Mirror 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)',  '기타' );
  
?>

   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 미래기업 서한컴퍼니 발주 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css">
   <link  rel="stylesheet" type="text/css" href="../css/outorder.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>   
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>  

<!-- 최초화면에서 보여주는 상단메뉴 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<link  rel="stylesheet" type="text/css" href="../css/common.css">
<title> 서한 컴퍼니 </title> 
   </head>
 
<body>

<? include '../myheader.php'; ?>   

   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  id=board_form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1&check_draw=<?=$check_draw?>" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  id=board_form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=not" enctype="multipart/form-data"> 
  <?php
	}
  ?>	   
 
<div id="container">

<div class="d-flex mb-1 justify-content-center"> 

   <div id="estimate_text2" style="width:840px;height:650px;font-size:14px;"> 
			
	   <div class="sero1" style="width:500px;">  <h3> 서한 컴퍼니 발주 </h3> </div>
	   
		      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
       
	  <button type="button" id="saveBtn"  class="btn btn-secondary"> DATA 저장 </button>	&nbsp;       
	  <button type="button" id="gotoList"  class="btn btn-secondary" onclick="location.href='list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>'" > 목록(List) </button>	&nbsp;       	   							   
		<div class="clear"> </div>    
	  <br>
      <div class="sero1"> 현장명 : </div> 
         <div class="sero2"><input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" size="50" placeholder="현장명" required   >	</div>	  			
	<div class="clear"> </div> 
         <div class="sero1"> 현장주소 : </div> 
         <div class="sero2"><input type="text" id="address" name="address" value="<?=$address?>" size="50" placeholder="현장주소"   ></div>	  	 
		 
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
        <div class="sero4"> 
		<select name="type1" >
           <?php		            
		   $arrSel = array("NP50","NP60","NP70","NP80","기타");		   
		   for($i=0;$i<count($arrSel);$i++) {
			     if($type1==$arrSel[$i])
							print "<option selected value='" . $arrSel[$i] . "'> " . $arrSel[$i] .   "</option>";
					 else   
			   print "<option value='" . $arrSel[$i] . "'> " . $arrSel[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select> </div>
		<div class="sero1" > 인승 </div> 
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
		
	   </div> 		
		
	
 </div>
</form>
</div>
<script>

$(document).ready(function(){	
		$("#saveBtn").click(function(){   	
		   // grid 배열 form에 전달하기						    						    
		  $("#board_form").submit(); 								 
		 });
// data 초기화
	Swal.fire({
	  title: '데이터 복사',
	  text: "기본사항을 제외하고 초기화 하실래요?",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: '네 그렇게 합시다!'
	}).then((result) => {
	  if (result.isConfirmed) {
		  // 실제 코드입력
			$('#board_form').find('input').each(function(){ $(this).val(''); });
			$('#board_form').find('select').each(function(){ $(this).val(''); });
			$('#board_form').find('textarea').each(function(){ $(this).val(''); });

			$('#workplacename').val("<? echo $workplacename; ?>");
			$('#address').val("<? echo $address; ?>");
			$('#firstord').val("<? echo $firstord; ?>");
			$('#firstordman').val("<? echo $firstordman; ?>");
			$('#firstordmantel').val("<? echo $firstordmantel; ?>");
			$('#secondord').val("<? echo $secondord; ?>");
			$('#secondordman').val("<? echo $secondordman; ?>");
			$('#secondordmantel').val("<? echo $secondordmantel; ?>");
			$('#chargedman').val("<? echo $chargedman; ?>");
			$('#chargedmantel').val("<? echo $chargedmantel; ?>");
			$('#orderday').val(getToday());
			$('#startday').val(getToday());		  		  
		Swal.fire(
		  '처리되었습니다.',
		  '데이터가 성공적으로 복사되었습니다.',
		  'success'
		)
	  }
	 	  // 확인이 아닌 경우 
	  else
	  {  
  			$('#main_draw').val('');
  			$('#lc_draw').val('');
  			$('#deadline').val('');
  			$('#workday').val('');
  			$('#startday').val(getToday());	
  			$('#demand').val('');  			
			$('#orderday').val(getToday());		

	  }	  
	  
	  
	  
	})
	
 // if(confirm("기본사항을 제외한 내용을 복사합니다.\n\n 기본사항을 제외한 초기화 하시겠습니까?")) {	 	
    // }	
	
	
	$("#types2").click(function(){
		$("input[name=type2]").val($("input[name=type1]").val());
		$("input[name=inseung2]").val($("input[name=inseung1]").val());
		$("input[name=car_insize2]").val($("input[name=car_insize1]").val());
	});	
	$("#types3").click(function(){
		$("input[name=type3]").val($("input[name=type2]").val());
		$("input[name=inseung3]").val($("input[name=inseung2]").val());
		$("input[name=car_insize3]").val($("input[name=car_insize2]").val());
	});	
	$("#types4").click(function(){
		$("input[name=type4]").val($("input[name=type3]").val());
		$("input[name=inseung4]").val($("input[name=inseung3]").val());
		$("input[name=car_insize4]").val($("input[name=car_insize3]").val());
	});	
	$("#types5").click(function(){
		$("input[name=type5]").val($("input[name=type4]").val());
		$("input[name=inseung5]").val($("input[name=inseung4]").val());
		$("input[name=car_insize5]").val($("input[name=car_insize4]").val());
	});	
	$("#types6").click(function(){
		$("input[name=type6]").val($("input[name=type5]").val());
		$("input[name=inseung6]").val($("input[name=inseung5]").val());
		$("input[name=car_insize6]").val($("input[name=car_insize5]").val());
	});	
	$("#types7").click(function(){
		$("input[name=type7]").val($("input[name=type6]").val());
		$("input[name=inseung7]").val($("input[name=inseung6]").val());
		$("input[name=car_insize7]").val($("input[name=car_insize6]").val());
	});	
	$("#types8").click(function(){
		$("input[name=type8]").val($("input[name=type7]").val());
		$("input[name=inseung8]").val($("input[name=inseung7]").val());
		$("input[name=car_insize8]").val($("input[name=car_insize7]").val());
	});	
	$("#types9").click(function(){
		$("input[name=type9]").val($("input[name=type8]").val());
		$("input[name=inseung9]").val($("input[name=inseung8]").val());
		$("input[name=car_insize9]").val($("input[name=car_insize8]").val());
	});	
	$("#types10").click(function(){
		$("input[name=type10]").val($("input[name=type9]").val());
		$("input[name=inseung10]").val($("input[name=inseung9]").val());
		$("input[name=car_insize10]").val($("input[name=car_insize9]").val());
	});	
	
		

	$("#cal_both1").click(function(){
		calculateBoth(1,'first_item','second_item');
	});	
	$("#cal_both2").click(function(){
		calculateBoth(2,'second_item','third_item');
	});	
	$("#cal_both3").click(function(){
		calculateBoth(3,'third_item','forth_item');
	});	
	$("#cal_both4").click(function(){
		calculateBoth(4,'forth_item','fifth_item');
	});	
	$("#cal_both5").click(function(){
		calculateBoth(5,'fifth_item','sixth_item');
	});	
	$("#cal_both6").click(function(){
		calculateBoth(6,'sixth_item','seventh_item');
	});	
	$("#cal_both7").click(function(){
		calculateBoth(7,'seventh_item','eighth_item');
	});	
	$("#cal_both8").click(function(){
		calculateBoth(8,'eighth_item','ninth_item');
	});	
	$("#cal_both9").click(function(){
		calculateBoth(9,'ninth_item','tenth_item');
	});	
	$("#cal_both10").click(function(){
		calculateBoth(10,'tenth_item','tenth_item');
	});		

calculateBoth = function(NUM, name1, name2) {
		const type = $("input[name=type" + NUM +"]" ).val();
		const insize = $("input[name=car_insize" + NUM +"]" ).val();
		const lc_su = $("input[name=lc_su]" ).val();		
	    const first_name = name1;
	    const second_name = name2;
		let nextNUM = NUM + 1;
		let result;
		let jungSu;
		let divider;		

		const wide_insize = insize.split('*');
		const wide = Number(wide_insize[0]);
        const depth=Number(wide_insize[1]);		

		//console.log(wide);


		if(type=='011' || type=='012' ||type=='025' ||type=='017' ||type=='014') {
				   result=depth - 50;	
				   }
				else if(type=='013')
					{
						result=depth - 20;	
					}					

		$("input[name=" + first_name + "1]").val('프레임');
		$("input[name=" + first_name + "2]").val(result);
		$("input[name=" + first_name + "3]").val(1);
		$("input[name=" + first_name + "4]").val('SET');

		let result_wide=0;

		switch(type) {
			case '011' :
			   result_wide=wide-730;
			   break;
			case '012' :
			   result_wide=wide-750;
			   break;
			case '013' :
			   result_wide=wide-705;
			   break;
			case '014' :
			   result_wide=wide/2-143;
			   break;
			case '017' :
			   result_wide=wide-810;
			   break;
			case '017S' :
			case '017s' :
			   result_wide=wide-410;
			   break;
			case '017m' :
			case '017M' :
			   result_wide=wide-610;
			   break;
			case 'N20' :
			   result_wide=wide-705;
			   break;
			case '026' :
			   result_wide=wide-670;
			   break;			   
			default:
				 break;
		}

		if(depth<1000)
	     	{
			   jungSu=1;
			   divider = 1;			   
	     		}
			else if(depth>=1800)
			   {
				 jungSu = 3;
				 divider = 3;
			   }
			   else
			      {
					jungSu = 2;
					divider = 2;
				  }
				
		let result_depth=0;

		switch(type) {
			case '011' :
			   result_depth= (depth-54)/divider  ;
			   break;
			case '012' :
			   result_depth=(depth-54)/divider ;
			   break;
			case '013' :
			   result_depth=(depth-20)/divider ;
			   break;
			case '014' :
			   result_depth=(depth-54)  ;
			   break;
			case '017' :
			   if(depth>=1800)
					  result_depth=(depth-60)/3 ;
				  else
					  result_depth=(depth-60)/2 ;
			   break;
			case '017S' :
			case '017s' :
			   result_depth=(depth-60)/divider ;
			   break;
			case '017m' :
			case '017M' :
			   result_depth=(depth-60)/divider ;
			   break;
			case 'N20' :
			   result_depth=(depth-56)/divider ;
			   break;
			case '026' :
			   result_depth=(depth-58)/divider ;
			   break;			   
			default:
				 break;
		}					
				
		$("input[name=" + second_name + "1]").val('중판');
		$("input[name=" + second_name + "2]").val(result_wide + "*" + Math.floor(result_depth));
		$("input[name=" + second_name + "3]").val(jungSu);
		$("input[name=" + second_name + "4]").val('EA');	

}	   

	$("#cal_frame1").click(function(){
		calculateFrame(1,'first_item');
	});	
	$("#cal_frame2").click(function(){
		calculateFrame(2,'second_item');
	});	
	$("#cal_frame3").click(function(){
		calculateFrame(3,'third_item');
	});	
	$("#cal_frame4").click(function(){
		calculateFrame(4,'forth_item');
	});	
	$("#cal_frame5").click(function(){
		calculateFrame(5,'fifth_item');
	});	
	$("#cal_frame6").click(function(){
		calculateFrame(6,'sixth_item');
	});	
	$("#cal_frame7").click(function(){
		calculateFrame(7,'seventh_item');
	});	
	$("#cal_frame8").click(function(){
		calculateFrame(8,'eighth_item');
	});	
	$("#cal_frame9").click(function(){
		calculateFrame(9,'ninth_item');
	});	
	$("#cal_frame10").click(function(){
		calculateFrame(10,'tenth_item');
	});	


	calculateFrame = function(NUM, name1) {
		const type = $("input[name=type" + NUM +"]" ).val();
		const insize = $("input[name=car_insize" + NUM +"]" ).val();
	    const first_name = name1;
		let result;
		let jungSu;

		const wide_insize = insize.split('*');
		const wide = Number(wide_insize[0]);
        const depth=Number(wide_insize[1]);			

		if(type=='011' || type=='012' ||type=='025' ||type=='017' ||type=='014') {
				   result=depth - 50;	
				   }
				else if(type=='013')
					{
						result=depth - 20;	
					}					

		$("input[name=" + first_name + "1]").val('프레임/중판X');
		$("input[name=" + first_name + "2]").val(result);
		$("input[name=" + first_name + "3]").val(1);
		$("input[name=" + first_name + "4]").val('SET');
}	           
        	           
        

	
});

  $(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});
$(function () {
	/*
            $("#orderday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#measureday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#drawday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#workday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#deadline").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#endworkday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#promiseday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#demand").datepicker({ dateFormat: 'yy-mm-dd'});
			*/
			
});

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
  var copyText = document.getElementById("test");   // 클립보드 복사 
  copyText.select();
  document.execCommand("Copy");
}  

function copy_below(){	

var park = document.getElementsByName("asfee");

document.getElementById("ashistory").value  = document.getElementById("ashistory").value + document.getElementById("asday").value + " " + document.getElementById("aswriter").value+ " " + document.getElementById("asorderman").value + " ";
document.getElementById("ashistory").value  = document.getElementById("ashistory").value  + document.getElementById("asordermantel").value + " " ;
     if(park[1].checked) {
        document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 유상 " + document.getElementById("asfee").value + " ";		
	 }		 
	   else
	   {
	    document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 무상 "+ document.getElementById("asfee").value + " ";				   
	   }
	   
document.getElementById("ashistory").value  += document.getElementById("asfee_estimate").value + " " + document.getElementById("aslist").value+ " " + document.getElementById("as_refer").value + " ";	
document.getElementById("ashistory").value  += document.getElementById("asproday").value + " " + document.getElementById("setdate").value+ " " + document.getElementById("asman").value + " ";	
document.getElementById("ashistory").value  += document.getElementById("asendday").value + " " + document.getElementById("asresult").value+ "        ";
//    = text1.concat(" ", text2," ", text3, " ",  text4);
// document.getElementById("asday").value . document.getElementById("aswriter").value;
	//+ document.getElementById("aswriter").value ;   // 콤마를 계산해 주고 다시 붙여주고붙여주고
   // document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고붙여주고
   
}  

function deldate(){	

document.getElementById("measureday").value  = "";
document.getElementById("drawday").value  = "";
document.getElementById("workday").value  = "";
document.getElementById("deadline").value  = "";
document.getElementById("endworkday").value  = "";
document.getElementById("startday").value  = "";
document.getElementById("testday").value  = "";   
var _today = new Date();   

// document.getElementById("orderday").value  = today;   
/*
let year = today.getFullYear(); // 년도
let month = today.getMonth();  // 월
let date = today.getDate();  // 날짜
let day = today.getDay(); 
printday = year + "-" + month + "-" + day;  */

printday=_today.format('yyyy-MM-dd');   
document.getElementById("orderday").value  = printday;

}  
Date.prototype.format = function (f) {

    if (!this.valueOf()) return " ";



    var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];

    var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];

    var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var d = this;



    return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function ($1) {

        switch ($1) {

            case "yyyy": return d.getFullYear(); // 년 (4자리)

            case "yy": return (d.getFullYear() % 1000).zf(2); // 년 (2자리)

            case "MM": return (d.getMonth() + 1).zf(2); // 월 (2자리)

            case "dd": return d.getDate().zf(2); // 일 (2자리)

            case "KS": return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)

            case "KL": return weekKorName[d.getDay()]; // 요일 (긴 한글)

            case "ES": return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)

            case "EL": return weekEngName[d.getDay()]; // 요일 (긴 영어)

            case "HH": return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)

            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)

            case "mm": return d.getMinutes().zf(2); // 분 (2자리)

            case "ss": return d.getSeconds().zf(2); // 초 (2자리)

            case "a/p": return d.getHours() < 12 ? "오전" : "오후"; // 오전/오후 구분

            default: return $1;

        }

    });

};



String.prototype.string = function (len) { var s = '', i = 0; while (i++ < len) { s += this; } return s; };

String.prototype.zf = function (len) { return "0".string(len - this.length) + this; };

Number.prototype.zf = function (len) { return this.toString().zf(len); };


function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;
	
    }
}


function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트 담당자 연락처 찾기
    }
}
function Enter_firstCheck(){
    if(event.keyCode == 13){
      exe_firstordman();  // 원청 담당자 전번 가져오기
    }
}

function Enter_chargedman_Check(){
    if(event.keyCode == 13){
	const data1 = "oem";
	const data2 = "chargedman";
	const data3 = "chargedmantel";	
	const search = $("#" + data2).val();
    if(event.keyCode == 13){     
     window.open('../ceiling/load_tel.php?search=' + search +'&data1=' + data1 + '&data2=' + data2 + '&data3=' + data3,'전번 조회','top=0, left=0, width=1500px, height=600px, scrollbars=yes');	  
    
      }
    }
}

function exe_search()
{     
}

function exe_firstordman()
{    
}


function exe_chargedman()
{   
}



function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}

function getToday(){   // 2021-01-28 형태리턴
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.
    var date = now.getDate();

    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month + "-" + date; 
}

</script>
	</body>
 </html>
