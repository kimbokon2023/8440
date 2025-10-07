<meta charset="utf-8">
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

  if ($mode=="modify"){
    try{
      $sql = "select * from mirae8440.work where num = ? ";
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


		      if($orderday!="0000-00-00" and $orderday!="1970-01-01") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01")  $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";		      

										

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }

if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
$orderday=date("Y-m-d");
  
  $checkstep="";
  $workplacename="";
  $address="";
  $firstord="";
  $firstordman="";
  $firstordmantel="";
  $secondord="";
  $secondordman="";
  $secondordmantel="";
  $chargedman="";
  $measureday="";
  $drawday="";
  $deadline="";
  $workday="";
  $worker="";
  $endworkday="";
  $material1="";
  $material2="";
  $material3="";
  $material4="";
  $material5="";
  $material6="";
  $widejamb="";
  $normaljamb="";
  $smalljamb="";
  $memo="";  
  $update_day="";
  $regist_day="";
  
  $delivery="";
  $delicar="";
  $delicompany="";
  $delipay="";
  $delimethod="";
  }
?>


   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 미래기업 통합정보시스템 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css">
   <link  rel="stylesheet" type="text/css" href="../css/work.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />


   </head>
 
   <body>
   
   <input type="button" value="save csv to mysql" onclick="javascript:Process();" />
   <div class=clear>  </div>
<div id="spreadsheet"></div>
<script>
var options = {
    minDimensions:[16,266],
    tableOverflow:true,
}

$('#spreadsheet').jexcel(options); 









</script>


   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  name="board_form" method="post" action="insert.php?mode=not" enctype="multipart/form-data"> 
  <?php
	}
  ?>	  

  <div id="content">
 	   
<div id="work_col2"> 

<div id="estimate_text2"> 
	      	<!-- 공사진행현황 -->
	   <div class="sero1"> <img src="../img/work_title.png">  </div>
		      

       <div id="write_button_renew"><input type="image" src="../img/ok.png">&nbsp;&nbsp;&nbsp;&nbsp;
	   <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>"><img src="../img/list.png"></a>
				</div> <br>
	  <div id="title1">
	  요청사항 선택 : &nbsp;
	  <?php
	    if($checkstep==Null) 			 
			$checkstep="없음";
			   ?>	  
	 <?php
	    if($checkstep=="없음") {
			 ?>
			없음  <input type="radio" checked name=checkstep value="없음"  >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청"  >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청"  >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청"  >	  
 
			<?php
             		}    ?>

	 <?php
	    if($checkstep=="방문요청") {
			 ?>
			없음  <input type="radio"name=checkstep value="없음"  >
			&nbsp;    방문요청<input type="radio" checked  name=checkstep value="방문요청"  >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청"  >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청"  >	  			
  
  			
			<?php
             		}    ?>
	 <?php
	    if($checkstep=="실측요청") {
			 ?>
			없음  <input type="radio"name=checkstep value="없음"  >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청"  >
			&nbsp;   실측요청<input type="radio" checked  name=checkstep value="실측요청"  >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청"  >	  			
  			
			<?php
             		}    ?>
	 <?php
	    if($checkstep=="발주요청") {
			 ?>
			없음  <input type="radio"name=checkstep value="없음"  >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청"  >
			&nbsp;   실측요청<input type="radio"  name=checkstep value="실측요청"  >	  
			&nbsp;   발주요청<input type="radio" checked  name=checkstep value="발주요청"  >	  			
  			
			<?php
             		}    ?>		
	  
	  </div>   <!-- 화면상단 요청사항 나타내기    	 <div class="clear"> </div> -->				
		
		<br>
      <div class="sero1"> 현장명 : </div> 
         <div class="sero2"><input type="text" id=workplacename name="workplacename" value="<?=$workplacename?>" size="50" placeholder="현장명" required>	</div>	  	
     <div class="clear"> </div> 
         <div class="sero1"> 현장주소 : </div> 
         <div class="sero2"><input type="text" id=address name="address" value="<?=$address?>" size="50" placeholder="현장주소" ></div>
		 <div class="clear"> </div> 
         <div class="sero1"> 원  청 : </div> 
         <div class="sero2"><input type="text" id=firsord name="firstord" value="<?=$firstord?>" size="15" placeholder="원청" > </div>
	    <div class="sero1">  담당 : </div> 
	    <div class="sero2"> <input type="text" id=firstordman name="firstordman" value="<?=$firstordman?>" size="10" placeholder="원청담당"></div>
		    <div class="sero1">  연락처 : </div> 
	    <div class="sero2"> <input type="text" id=firstordmantel name="firstordmantel" value="<?=$firstordmantel?>" size="14" placeholder="연락번호"></div>
        <div class="clear"> </div> 		
	    <div class="sero1"> 발주처 : </div> 
         <div class="sero2"><input type="text" id=secondord name="secondord" value="<?=$secondord?>" size="15" placeholder="발주처" > </div>
	    <div class="sero1">  담당 : </div> 
	    <div class="sero2"> <input type="text" id=secondordman name="secondordman" value="<?=$secondordman?>" size="10" placeholder="발주처 담당자"></div>
	    <div class="sero1">  연락처 : </div> 
	    <div class="sero2"> <input type="text" id=secondordmantel name="secondordmantel" value="<?=$secondordmantel?>" size="14" placeholder="연락번호" ></div>
  		<div class="clear"> </div> 
		
        <div class="sero1">  현장소장 : </div>   
	    <div class="sero2"> <input type="text" id=chargedman name="chargedman" value="<?=$chargedman?>" size="10" placeholder="현장담당자"> </div>
		<div class="sero1">  연락처 : </div> 
	    <div class="sero2"> <input type="text" id=chargedmantel name="chargedmantel" value="<?=$chargedmantel?>" size="10"  placeholder="현장담당전화"></div> 
     
        <div class="clear"> </div> 	
        <div class="space"> </div> 	
        <div class="clear"> </div> 	

        <div class="sero1"> 발주접수일: </div> 
         <div class="sero2"><input type="text" name="orderday" id="orderday" value="<?=$orderday?>" size="10" placeholder="발주접수일"  > </div>
	    <div class="sero6" style="text-align:right; width:50px;">  실측일 : </div> 
	    <div class="sero2"> <input type="text" name="measureday" id="measureday" value="<?=$measureday?>" size="10" placeholder="실측일" ></div>	
	    <div class="sero7" style="width:90px;">  도면설계완료일 : </div>
         <div class="sero2"><input type="text" name="drawday" id="drawday" value="<?=$drawday?>" size="10" placeholder="도면설계일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>		
	    <div class="sero7" style="width:90px; color:red; font-weight:bold; text-align:right;">  납기일 : </div>
         <div class="sero2"><input type="text" name="deadline" id="deadline" value="<?=$deadline?>" size="10" placeholder="납기일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>				 
            <div class="clear"> </div> 	 
	   <div class="sero1"> 시공투입일: </div> 
         <div class="sero2"><input type="text" name="workday" id="workday" value="<?=$workday?>" size="10" placeholder="투입일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/ > </div>
	    <div class="sero6">  미래기업 시공팀 : </div> 
	    <div class="sero2"> <input type="text" name="worker" id=worker  value="<?=$worker?>" size="10" placeholder="시공팀" ></div>	
	    <div class="sero7">  시공완료일 : </div>
         <div class="sero2"><input type="text" name="endworkday" id="endworkday" value="<?=$endworkday?>" size="10" placeholder="시공완료일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>		
		 
        <div class="clear"> </div> 	
        <div class="space"> </div> 	
        <div class="clear"> </div> 	
		
	 <?php
	 
		 $aryreg=array();
		 if($delivery=="")
			   $delivery="직접배송(수령)";
	     switch ($delivery) {
			case   "직접배송(수령)"         : $aryreg[0] =  "checked" ; break;
			case   "상차(선불)"             : $aryreg[1] =  "checked" ; break;
			case   "상차(착불)"             : $aryreg[2] =  "checked" ; break;
			case   "경동화물(지점)"         : $aryreg[3] =  "checked" ; break;
			case   "경동택배"               : $aryreg[4] =  "checked" ; break;			
			default: break;
		}		 
				?>		

			<div id="delivery_col"> 배송방식 : 
			  <input type="radio" <?=$aryreg[0]?> name=delivery value="직접배송(수령)" > 직접배송(수령)   &nbsp;&nbsp;&nbsp;  
			  <input type="radio" <?=$aryreg[1]?> name=delivery value="상차(선불)" > 상차(선불)   &nbsp;&nbsp;&nbsp;  
			  <input type="radio" <?=$aryreg[2]?>  name=delivery value="상차(착불)"> 상차(착불) &nbsp;&nbsp;&nbsp; 
			  <input type="radio" <?=$aryreg[3]?> name=delivery value="경동화물(지점)" > 경동화물(지점)  &nbsp;&nbsp;&nbsp;  
			  <input type="radio" <?=$aryreg[4]?> name=delivery value="경동택배"> 경동택배 &nbsp;&nbsp;&nbsp; 
		      </div>
		 <?php
	 
		 $aryreg=array();
		 if($delicar=="")
			   $delicar="경동화물";
	     switch ($delicar) {
			case   "경동화물"           : $aryreg[0] =  "checked" ; break;
			case   "라보"               : $aryreg[1] =  "checked" ; break;
			case   "다마스"             : $aryreg[2] =  "checked" ; break;
			case   "1t"                 : $aryreg[3] =  "checked" ; break;
			case   "1.2t"               : $aryreg[4] =  "checked" ; break;			
			case   "2.5t"               : $aryreg[5] =  "checked" ; break;			
			case   "5t"                 : $aryreg[6] =  "checked" ; break;			
			case   "9t"                 : $aryreg[7] =  "checked" ; break;			
			default: break;
		}		 
				?>		
				
				<div id="delivery_col" style="color:blue;"> 화물차종 : <input type="radio" <?=$aryreg[0]?> name=delicar value="없음" >	 없음  &nbsp;&nbsp;&nbsp;  
				                       <input type="radio" <?=$aryreg[1]?>  name=delicar value="라보">  	 라보 &nbsp;&nbsp;&nbsp; 
									   <input type="radio" <input type="radio" <?=$aryreg[2]?> name=delicar value="다마스" >  다마스 &nbsp;&nbsp;&nbsp;  
									   <input type="radio" <input type="radio" <?=$aryreg[3]?> name=delicar value="1t"> 1t &nbsp;&nbsp;&nbsp; 
									   <input type="radio" <input type="radio" <?=$aryreg[4]?> name=delicar value="1.2t">	 1.2t &nbsp;&nbsp;&nbsp; 
									   <input type="radio" <input type="radio" <?=$aryreg[5]?> name=delicar value="2.5t">	 2.5t &nbsp;&nbsp;&nbsp; 
									   <input type="radio" <input type="radio" <?=$aryreg[6]?> name=delicar value="5t">	 5t &nbsp;&nbsp;&nbsp; 
									   <input type="radio" <input type="radio" <?=$aryreg[7]?> name=delicar value="9t">	 9t &nbsp;&nbsp;&nbsp; 
		      </div>		  
			 <?php
			 
		 $aryreg=array();
		 if($delicompany=="")
			   $delicompany="없음";
	     switch ($delicompany) {
			case   "없음"             : $aryreg[0] =  "checked" ; break;
			case   "스카이"           : $aryreg[1] =  "checked" ; break;
			case   "김재명"           : $aryreg[2] =  "checked" ; break;
			case   "유광식"           : $aryreg[3] =  "checked" ; break;
			case   "천안"             : $aryreg[4] =  "checked" ; break;
			case   "전국특송(합바)"   : $aryreg[5] =  "checked" ; break;

			default: break;
		}		 
				?>						
				<div id="delivery_col" style="color:brown;"> 운송업체 : 
				                           <input type="radio" <?=$aryreg[0]?> name=delicompany value="없음" >	 없음  &nbsp;&nbsp;&nbsp;  
				                           <input type="radio" <?=$aryreg[1]?> name=delicompany value="스카이" >	 스카이  &nbsp;&nbsp;&nbsp;  
				                           <input type="radio" <?=$aryreg[2]?> name=delicompany value="김재명">  	 김재명 &nbsp;&nbsp;&nbsp; 
									       <input type="radio" <?=$aryreg[3]?> name=delicompany value="유광식" > 유광식 &nbsp;&nbsp;&nbsp;  
									       <input type="radio" <?=$aryreg[4]?> name=delicompany value="천안">      천안 &nbsp;&nbsp;&nbsp; 
									       <input type="radio" <?=$aryreg[5]?> name=delicompany value="전국특송(합바)">     전국특송(합바) &nbsp;&nbsp;&nbsp; 

		      </div>		
			  
				 <?php
			 
		 $aryreg=array();
		 if($delimethod=="")
			   $delimethod="본사자체";
	     switch ($delimethod) {
			case   "본사자체"           : $aryreg[0] =  "checked" ; break;
			case   "추후청구"           : $aryreg[1] =  "checked" ; break;

			default: break;
		}		 
				?>			  

	<div id="delivery_col" style="color:red;" > 운임(있을시 기록) : <input type="text" name=delipay value="<?=$delipay?>" placeholder="운임금액" onkeyup="inputNumberFormat(this)"  >	 &nbsp;&nbsp;&nbsp;  청구방식 : &nbsp;&nbsp;&nbsp;  
	                                       <input type="radio" <?=$aryreg[0]?> name=delimethod value="본사자체" >	 본사자체  &nbsp;&nbsp;&nbsp;  
				                           <input type="radio" <?=$aryreg[1]?>  name=delimethod value="추후청구">  	 추후청구 &nbsp;&nbsp;&nbsp; 

	
	
	   </div>  
			  
	    <div class="clear"> </div> 	
        <div class="space"> </div> 	
        <div class="clear"> </div> 		

		<div class="sero10"  > 층별재질1 : </div> 
		
	        <div class="sero9">		
		<input type="text" name="material1" id="material1" value="<?=$material1?>" size="15" placeholder="기타 재질" > </div>		
		<div class="sero8">
		
			 <?php
			 
		 $aryreg=array();
		 if($material2=="")
			   $material2="재질 미정";
	     switch ($material2) {
			case   "재질 미정"                     	     : $aryreg[0] =  "selected" ; break;
			case   "304 Hair Line 1.2T"          		 : $aryreg[1] =  "selected" ; break;
			case   "304 Mirror 1.2T'"          			 : $aryreg[2] =  "selected" ; break;
			case   "304 Mirror VB 1.2T"           	     : $aryreg[3] =  "selected" ; break;
			case   "304 Mirror Bronze 1.2T"              : $aryreg[4] =  "selected" ; break;
			case   "304 Mirror VB Ti-Bronze 1.2T"        : $aryreg[5] =  "selected" ; break;
			case   "304 Hair Line Black 1.2T"            : $aryreg[6] =  "selected" ; break;
			case   "SPCC 1.2T(도장)"                 	  : $aryreg[7] =  "selected" ; break;
			case   "EGI 1.2T(도장)"          			  : $aryreg[8] =  "selected" ; break;
			case   "HTM (신우)"         	 		      : $aryreg[9] =  "selected" ; break;
			case   "기타"         	 		              :  $aryreg[10] =  "selected" ; break;

			default: break;
		}		 
		// print $material2;
				?>			
		
		     <select name="material2" id="material2" style="margin-top:1px;">
	           <option value='재질 미정' <?=$aryreg[0]?> >재질 미정 </option>
	           <option value='304 Hair Line 1.2T' <?=$aryreg[1]?> >304 Hair Line 1.2T </option>
	           <option value='304 Mirror 1.2T' <?=$aryreg[2]?> >304 Mirror 1.2T </option>
	           <option value='304 Mirror VB 1.2T' <?=$aryreg[3]?> >304 Mirror VB 1.2T </option>
	           <option value='304 Mirror Bronze 1.2T' <?=$aryreg[4]?> >304 Mirror Bronze 1.2T </option>
	           <option value='304 Mirror VB Ti-Bronze 1.2T' <?=$aryreg[5]?> >304 Mirror VB Ti-Bronze 1.2T </option>
	           <option value='304 Hair Line Black 1.2T' <?=$aryreg[6]?> >304 Hair Line Black 1.2T </option>
	           <option value='SPCC 1.2T(도장)' <?=$aryreg[7]?> >SPCC 1.2T(도장) </option>
	           <option value='EGI 1.2T(도장)' <?=$aryreg[8]?> >EGI 1.2T(도장) </option>
	           <option value='HTM (신우)' <?=$aryreg[9]?> >HTM (신우) </option>
	           <option value='기타' <?=$aryreg[10]?> >기타 </option>               
	         </select>		
		</div>
	
	        <div class="clear"> </div> 		

		<div class="sero10"> 층별재질2 : </div> 
		
	        <div class="sero9">		
		<input type="text" name="material3" id="material3" value="<?=$material3?>" size="15" placeholder="기타 재질" > </div>		
		<div class="sero8">
	<?php			 
		 $aryreg=array();
		 if($material4=="")
			   $material4="재질 미정";
	     switch ($material4) {
			case   "재질 미정"                     	     : $aryreg[0] =  "selected" ; break;
			case   "304 Hair Line 1.2T"          		 : $aryreg[1] =  "selected" ; break;
			case   "304 Mirror 1.2T'"          			 : $aryreg[2] =  "selected" ; break;
			case   "304 Mirror VB 1.2T"           	     : $aryreg[3] =  "selected" ; break;
			case   "304 Mirror Bronze 1.2T"              : $aryreg[4] =  "selected" ; break;
			case   "304 Mirror VB Ti-Bronze 1.2T"        : $aryreg[5] =  "selected" ; break;
			case   "304 Hair Line Black 1.2T"            : $aryreg[6] =  "selected" ; break;
			case   "SPCC 1.2T(도장)"                 	  : $aryreg[7] =  "selected" ; break;
			case   "EGI 1.2T(도장)"          			  : $aryreg[8] =  "selected" ; break;
			case   "HTM (신우)"         	 		      : $aryreg[9] =  "selected" ; break;
			case   "기타"         	 		              :  $aryreg[10] =  "selected" ; break;

			default: break;
		}		 
				?>				
		     <select name="material4" id="material4" style="margin-top:1px;">
	           <option value='재질 미정' <?=$aryreg[0]?> >재질 미정 </option>
	           <option value='304 Hair Line 1.2T' <?=$aryreg[1]?> >304 Hair Line 1.2T </option>
	           <option value='304 Mirror 1.2T'<?=$aryreg[2]?> >304 Mirror 1.2T </option>
	           <option value='304 Mirror VB 1.2T'<?=$aryreg[3]?> >304 Mirror VB 1.2T </option>
	           <option value='304 Mirror Bronze 1.2T'<?=$aryreg[4]?> >304 Mirror Bronze 1.2T </option>
	           <option value='304 Mirror VB Ti-Bronze 1.2T'<?=$aryreg[5]?> >304 Mirror VB Ti-Bronze 1.2T </option>
	           <option value='304 Hair Line Black 1.2T'<?=$aryreg[6]?> >304 Hair Line Black 1.2T </option>
	           <option value='SPCC 1.2T(도장)'<?=$aryreg[7]?> >SPCC 1.2T(도장) </option>
	           <option value='EGI 1.2T(도장)'<?=$aryreg[8]?> >EGI 1.2T(도장) </option>
	           <option value='HTM (신우)'<?=$aryreg[9]?> >HTM (신우) </option>
	           <option value='기타'<?=$aryreg[10]?> >기타 </option>               
	         </select>	
		</div>
	
	        <div class="clear"> </div> 	

		<div class="sero10"> 층별재질3 : </div> 
		
	        <div class="sero9">		
		<input type="text" name="material5" id="material5" value="<?=$material5?>" size="15" placeholder="기타 재질" > </div>		
		<div class="sero8">
		
	<?php			 
		 $aryreg=array();
		 if($material6=="")
			   $material6="재질 미정";
	     switch ($material6) {
			case   "재질 미정"                     	     : $aryreg[0] =  "selected" ; break;
			case   "304 Hair Line 1.2T"          		 : $aryreg[1] =  "selected" ; break;
			case   "304 Mirror 1.2T'"          			 : $aryreg[2] =  "selected" ; break;
			case   "304 Mirror VB 1.2T"           	     : $aryreg[3] =  "selected" ; break;
			case   "304 Mirror Bronze 1.2T"              : $aryreg[4] =  "selected" ; break;
			case   "304 Mirror VB Ti-Bronze 1.2T"        : $aryreg[5] =  "selected" ; break;
			case   "304 Hair Line Black 1.2T"            : $aryreg[6] =  "selected" ; break;
			case   "SPCC 1.2T(도장)"                 	  : $aryreg[7] =  "selected" ; break;
			case   "EGI 1.2T(도장)"          			  : $aryreg[8] =  "selected" ; break;
			case   "HTM (신우)"         	 		      : $aryreg[9] =  "selected" ; break;
			case   "기타"         	 		              :  $aryreg[10] =  "selected" ; break;

			default: break;
		}		 
				?>					
		
		     <select name="material6" id="material6" style="margin-top:1px;">
	           <option value='재질 미정' <?=$aryreg[0]?> >재질 미정 </option>
	           <option value='304 Hair Line 1.2T' <?=$aryreg[1]?> >304 Hair Line 1.2T </option>
	           <option value='304 Mirror 1.2T'<?=$aryreg[2]?> >304 Mirror 1.2T </option>
	           <option value='304 Mirror VB 1.2T'<?=$aryreg[3]?> >304 Mirror VB 1.2T </option>
	           <option value='304 Mirror Bronze 1.2T'<?=$aryreg[4]?> >304 Mirror Bronze 1.2T </option>
	           <option value='304 Mirror VB Ti-Bronze 1.2T'<?=$aryreg[5]?> >304 Mirror VB Ti-Bronze 1.2T </option>
	           <option value='304 Hair Line Black 1.2T'<?=$aryreg[6]?> >304 Hair Line Black 1.2T </option>
	           <option value='SPCC 1.2T(도장)'<?=$aryreg[7]?> >SPCC 1.2T(도장) </option>
	           <option value='EGI 1.2T(도장)'<?=$aryreg[8]?> >EGI 1.2T(도장) </option>
	           <option value='HTM (신우)'<?=$aryreg[9]?> >HTM (신우) </option>
	           <option value='기타'<?=$aryreg[10]?> >기타 </option>               
	         </select>		
		</div>
	
	        <div class="clear"> </div> 				
			
		<div class="sero1"> 와이드쟘 : </div> 
        <div class="sero2"><input type="text" name="widejamb" id=widejamb value="<?=$widejamb?>" size="10" placeholder="와이드쟘 수량" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  > </div>
	    <div class="sero1">  멍텅구리 : </div> 
	    <div class="sero4"> <input type="text" name="normaljamb" id=normaljamb value="<?=$normaljamb?>" size="10" placeholder="멍텅구리 수량" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></div>	
	    <div class="sero1">  쪽쟘 : </div> 
	    <div class="sero4"> <input type="text" name="smalljamb" id=samlljamb value="<?=$smalljamb?>" size="10" placeholder="쪽쟘 수량" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></div>			
        <div class="clear"> </div> 			

	   <div class="sero6"> 추가 내역 : </div>    
	   <div class="sero5"> <textarea rows="5" cols="80" name="memo" id=memo placeholder="추가적으로 기록할 내역" ><?=$memo?></textarea></div> <br><br><br><br>
	   

		   
	   </div>	    		
   
	   </div> 
		
	</form>
 </div>

<script>
  $(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});
$(function () {
            $("#orderday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#measureday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#drawday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#workday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#deadline").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#endworkday").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#promiseday").datepicker({ dateFormat: 'yy-mm-dd'});
			
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


function Process(){
	
	for (i=1;i<=1;i++) {
		    var orderday = $('tr:eq(' + i + ')>td:eq(1)').html();
		    var firstord = $('tr:eq(' + i + ')>td:eq(2)').html();
			
		    var secondord =  $('tr:eq(' + i + ')>td:eq(3)').html();
		    var workplacename =  $('tr:eq(' + i + ')>td:eq(4)').html();
		    var address =  $('tr:eq(' + i + ')>td:eq(5)').html();
		    var material1 =  $('tr:eq(' + i + ')>td:eq(6)').html();
		    var widejamb =  $('tr:eq(' + i + ')>td:eq(7)').html();
		    var smalljamb =  $('tr:eq(' + i + ')>td:eq(8)').html();
		    var measureday =  $('tr:eq(' + i + ')>td:eq(9)').html();
		    var deadline =  $('tr:eq(' + i + ')>td:eq(10)').html();
		    var secondordman =  $('tr:eq(' + i + ')>td:eq(11)').html();
		    var secondordmantel =  $('tr:eq(' + i + ')>td:eq(12)').html();
		    var worker =  $('tr:eq(' + i + ')>td:eq(13)').html();
		    var delipay =  $('tr:eq(' + i + ')>td:eq(14)').html();
		    var delimethod =  $('tr:eq(' + i + ')>td:eq(15)').html();
		    var memo =  $('tr:eq(' + i + ')>td:eq(16)').html();
			
  $("#orderday").val(orderday);
  $("#firstord").val(firstord);
  $("#secondord").val(secondord);
  $("#workplacename").val(workplacename);
  $("#address").val(address);
  $("#material1").val(material1);
  $("#widejamb").val(widejamb);
  $("#smalljamb").val(smalljamb);
  $("#measureday").val(measureday);
  $("#deadline").val(deadline);
  $("#secondordman").val(secondordman);
  $("#secondordmantel").val(secondordmantel);
  $("#worker").val(worker);
  $("#delipay").val(delipay);
  $("#delimethod").val(delimethod);
  $("#memo").val(memo);

	}
	// alert(worker);
	
}



</script>
	</body>
 </html>
