<?php\nrequire_once __DIR__ . '/../common/functions.php';
if(!isset($_SESSION))      
	session_start(); 
if(isset($_SESSION["DB"]))
	$DB = $_SESSION["DB"] ;	
	$level= $_SESSION["level"];
	$user_name= $_SESSION["name"];
	$user_id= $_SESSION["userid"];	

if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }  
   
    // 첫 화면 표시 문구
 $title_message = '(데이터 복사) 외주발주 관리 수주내역'; 
 
 ?>

 <?php include getDocumentRoot() . '/load_header.php' ?>

<title> <?=$title_message?> </title>

<style>
    .table, td, input {      
	  vertical-align: middle;	    	    
	  text-align:center;
    }
	
  .table td input.form-control {
    border: 1px solid #ced4da; /* 테두리 스타일 추가 */
    border-radius: 2px; /* 테두리 라운드 처리 */
  }	
   	  
</style> 
 
</head>

<body>

 <?php
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";


  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $find=$_REQUEST["find"];
  else
  $find="";

      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="copy"){
		try{
		  $sql = "select * from mirae8440.outorder where num = ? ";
		  $stmh = $pdo->prepare($sql); 

		$stmh->bindValue(1,$num,PDO::PARAM_STR); 
		  $stmh->execute();
		  $count = $stmh->rowCount();              
		if($count<1){  
		  print "검색결과가 없습니다.<br>";
		 }else{
		  $row = $stmh->fetch(PDO::FETCH_ASSOC);

		 }
      
	  include '_row.php';

	  $measureday=null;
	  $drawday=null;
	  $deadline=null;
	  $workday=null;
	  $worker=$row["worker"];
	  $endworkday=null;
	  $delicar="없음";
	  $delipay="";
	  $delimethod="없음";
	  $demand=null;
	  $first_writer='';
	  $update_log='';	  
			
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

  // 덴크리 직접작업을 위한 설계내용 5개 항목추가
  
  $deliverynum="";  
  $confirm="";
  $submemo="";
  $pdffile_name = "";
  $copied_file = "";				  

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }
$mode="";

// $mode를 이용해서 copy_data.php에서 기존 데이터를 사용한다.
	 
$material_arr = array('','304 Hair Line 1.2T','304 HL 1.2T','304 Mirror 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)',  '기타' );
  
  
// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;  
  
// 첨부 파일에 대한 읽어오는 부분
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
 
// 첨부파일 있는 것 불러오기 
$savefilename_arr=array(); 
$realname_arr=array(); 
$tablename='outorder';
$item = 'attached';

$sql=" select * from mirae8440.fileuploads where tablename ='$tablename' and item ='$item' and parentid ='$id' ";	

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			array_push($realname_arr, $row["realname"]);			
			array_push($savefilename_arr, $row["savename"]);			
        }		 
   } catch (PDOException $Exception) { 
    print "오류: ".$Exception->getMessage();
  }   
		
		 
// 신규데이터 작성시 키값지정 parentid값이 없으면 데이터 저장안됨
$timekey = date("Y_m_d_H_i_s");				  
  
?>

<form id="board_form" name="board_form" method="post"  onkeydown="return captureReturnKey(event)"  >		   

<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
<input type="hidden" id="copied_file" name="copied_file" value="<?=$copied_file?>"  >
<input type="hidden" id="confirm" name="confirm" value="<?=$confirm?>"  >

  <!-- 파일저장 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >	
	<input type="hidden" id="num" name="num" value="<?=$num?>" >		  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->

<div class="container-fluid">	  
<div class="card p-2 m-2">	

 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-3 mb-5 justify-content-center" >
		     품목, 비고는 그대로 유지된 후 복사됩니다. <br> 
		   <br> 
		     발주사항 꼼꼼히 확인해 주세요!
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>
      
    	
			
	 <div class="d-flex justify-content-center mt-3 mb-1"> 		
	   <h4> <?=$title_message ?> </h4>
	</div>		
  	<div class="d-flex justify-content-start mt-2 mb-2 ">	
		<button class="btn btn-dark btn-sm" id="closeBtn" > <ion-icon name="close-outline"></ion-icon> 창닫기 </button>&nbsp;		
		<button type="button"  id="saveBtn"  class="btn btn-dark btn-sm" > <ion-icon name="save-outline"></ion-icon> 저장</button> 						
					   
	</div>
 
<div class="d-flex justify-content-center mb-1 mt-1"> 	
	<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 
		<table class="table table-bordered">		 
		  <tbody>
			<tr>
			  <td colspan="1" style="width:70px;" >현장명</td>
			  <td colspan="5">
				<input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" class="form-control" style="text-align: left;" required>
			  </td>
			</tr>
			<tr>
			  <td>주소</td>
			  <td colspan="5"><input type="text" name="address" value="<?=$address?>" class="form-control"  style="text-align: left;" ></td>
			</tr>
			<tr>
			  <td>매입처</td>
				  <td> 
					  
					<select name="firstord" id="firstord" class="form-control">
				   <?php		 
				   
				   $optarr = array('서한컴퍼니','덴크리','다온텍');

				   for($i=0;$i<count($optarr);$i++) {
						 if($firstord==$optarr[$i] || $whichcompany==$optarr[$i] )
									print "<option selected value='" . $optarr[$i] . "'> " . $optarr[$i] .   "</option>";
							 else   
					   print "<option value='" . $optarr[$i] . "'> " . $optarr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 		 
				  
				  
				  </td>
               <td  style="width:70px;"  >담당</td>				  
				  <td> <input type="text" id="firstordman" name="firstordman" value="<?=$firstordman?>" class="form-control" onkeydown="JavaScript:Enter_firstCheck();"> </td>
				<td>Tel</td>				  				  
				  <td> <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control" > </td>				
			  </td>
			</tr>
			<tr>
			  <td>발주처</td>
			  <td><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
			  <td>담당</td>
			  <td><input type="text" id="secondordman" name="secondordman" value="<?=$secondordman?>" class="form-control" onkeydown="JavaScript:Enter_Check();"></td>
			  <td>Tel</td>
			  <td><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
			</tr>
			<tr>
			  <td>담당자</td>
			  <td><input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" onkeydown="JavaScript:Enter_chargedman_Check();"></td>
			  <td>Tel</td>
			  <td><input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control"></td>
			  <td colspan="2">

			   
				<label  for="pdf_file" class="btn btn-primary btn-sm"> 첨부(pdf)</label> &nbsp;&nbsp;
				<input id="pdf_file" type="file" name="pdf_file" style="display:none;" accept=".pdf" />	
				
					<button id="delattachedfileBtn" type="button" class="btn btn-outline-danger btn-sm"  > 첨부삭제  </button> 
					<input type="text" id="pdffile_name" name="pdffile_name" value="<?=$pdffile_name?>" style="width:200px;"  >    
		
			
			  
			  </td>
			</tr>
		  
		  <tr>
			<td colspan="6">        
				<label for="upfile" class="input-group-text btn btn-outline-primary btn-sm"> 추가첨부 </label>						  										
				<input id="upfile"  name="upfile[]" type="file" onchange="this.value" multiple  style="display:none" >	
				 <div id ="displayfile" style="display:none; text-align:left; width:500px; ">  				
				 </div>					
			</td>	  
		  </tr>	
			<tr>  
				<td>비고</td>
					<td colspan="6">
						<textarea  id="memo"  name="memo" class="form-control"><?=$memo?></textarea>
					  </td>
				</tr>
	
		  </tbody>
		</table>
	</div>			  

<div class="col-sm-7 p-1  rounded"   style=" border: 1px solid #392f31; " > 	

  <table id="partlist" class="table table-bordered ">
    <tbody>	  
    	<!-- New Table Rows -->
		<tr>
		  <td>접수일</td>
		  <td><input type="date" name="orderday" id="orderday" value="<?=$orderday?>" class="form-control"></td>

		  <td>발주일</td>
		  <td><input type="date" name="startday" id="startday" value="<?=$startday?>" class="form-control"></td>
		  
		  <td style="color:red;">납기일</td>
		  <td><input type="date" name="deadline" id="deadline" value="<?=$deadline?>" class="form-control"></td>    

		</tr>
		<tr>
		  <td style="color:blue;">출고일</td>
		  <td><input type="date" name="workday" id="workday" value="<?=$workday?>" class="form-control"></td>    
		  
		  <td class="text-danger"> 청구일</td>		   
		  <td>
				<input type="date" name="demand" id="demand" value="<?=$demand?>"  class="form-control" > 
		   </td>		  
		</tr>
	  <tr>
		<td > 운송비 기록 </td>
		<td>
		   <input type="text" id="delivery"  name="delivery" value="<?=$delivery?>" placeholder="내역"   > 
		</td>

		<td > 운임</td>
		<td>
		   <input type="text" id="delipay"  name="delipay" value="<?=$delipay?>" placeholder="(있을시) 금액 " onkeyup="inputNumberFormat(this)" >
		</td>
	  
		<td >  경동택배 송장 </td>
		<td>
			 <input type="text" id="deliverynum" name="deliverynum" value="<?=$deliverynum?>" size="20" placeholder="번호"  >    
		</tr>	
		  <tr>
				<td>업체 메모 </td>		  
					<td colspan="6">		         
						<textarea  id="memo2"  name="memo2" class="form-control"  placeholder="업체기록 전달내용"><?=$submemo?></textarea>
					  </td>
				  
		  </tr>
	  <tr>
		<td>결합단위(SET) </td>
		<td>
		  <input type="text" id="su"  name="su"  value="<?=$su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
		</td>

		<td>L/C 수량</td>
		<td>
		   <input type="text"  id="lc_su"  name="lc_su" value="<?=$lc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
	     </td>
	  
		<td >기타 수량</td>
		<td>
		  <input type="text"  id="etc_su"  name="etc_su" value="<?=$etc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
		</td>    
	  </tr>	
    	  
	  
	  <tr>
		<td colspan="2">
		  <?php print "등록 : " . $first_writer; ?>		  
		  </td>

		<td colspan="4">
		  <?php
			  $update_log = preg_replace('/(\d{4})/', "\n$1", $update_log);
			  ?>
			<textarea class="form-control " readonly name="update_log"><?=$update_log?></textarea>
		   </td>
	  
	  </tr>	      

		
    </tbody>
  </table>
		
</div>  

</div>
</div>
			
	<div class="card mt-1 mb-5" style=" border: 1px solid #392f31; ">			
	  <div class="card-body">			
		
  <table id="itemlist" class="table table-bordered ">
    <tbody>	  		
		
<div class="itemlist">	
<?php if ($firstord == '덴크리' or $whichcompany == '덴크리') { ?>
  <table id="itemlist" class="table table-bordered">
    <tbody>
      <?php
        $itemNames = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];
        for ($i = 0; $i < 10; $i++) {
            $type = ${'type'.($i+1)};
            $inseung = ${'inseung'.($i+1)};
            $car_inside = ${'car_inside'.($i+1)};
            $itemName = ${$itemNames[$i].'_item1'};
            $itemSpec = ${$itemNames[$i].'_item2'};
            $itemQuantity = ${$itemNames[$i].'_item3'};
            $itemUnit = ${$itemNames[$i].'_item4'};
            $comment = ${'comment'.($i+1)};
			$cal_both = "cal_both" . ($i + 1) ;
			$cal_frame = "cal_frame" . ($i + 1) ;
      ?>
        <tr>
          <td style="text-align:right; width: 40px;"><?= $i+1 ?></td>
          <td><input type="text" name="type<?= $i+1 ?>" value="<?= $type ?>" size="10"></td>

          <td style="text-align:right; width: 50px; color: blue;">인승</td>
          <td><input type="text" name="inseung<?= $i+1 ?>" value="<?= $inseung ?>" size="2"></td>

          <td style="text-align:right; width: 70px; color: red;">C inside</td>
          <td><input type="text" name="car_inside<?= $i+1 ?>" value="<?= $car_inside ?>" size="8"></td>

		<td><button type='button' class='btn btn-primary btn-sm' id="<?=$cal_both?>" >프레임/중판</button></td>
		<td><button type='button' class='btn btn-secondary btn-sm' id="<?=$cal_frame?>" >프레임/중판X</button></td>

          <td style="text-align:right; width: 50px;">품목</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item1" value="<?= $itemName ?>" size="12"></td>

          <td style="text-align:right; width: 50px; color: blue;">규격</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item2" value="<?= $itemSpec ?>" size="20"></td>

          <td style="text-align:right; width: 70px; color: red;">수량:</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item3" value="<?= $itemQuantity ?>" size="2"></td>

          <td style="text-align:left; width: 50px;">단위</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item4" value="<?= $itemUnit ?>" size="2"></td>

          <td style="text-align:left; width: 50px;">비고</td>
          <td><input type="text" name="comment<?= $i+1 ?>" value="<?= $comment ?>" size="50"></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php } ?>
<?php 
if ($firstord == '서한컴퍼니' || $whichcompany == '서한컴퍼니' || $firstord == '다온텍' || $whichcompany == '다온텍') {
  $arr = ['','LED_BAR','SMPS','프로파일','광학산PC','아크릴커버','유백색아크릴'];
  $itemNames = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];

  echo "<table id='itemlist' class='table table-bordered'>";
  echo "<tbody>";

  for ($i = 0; $i < 10; $i++) {
    $type = ${'type'.($i+1)};
    $inseung = ${'inseung'.($i+1)};
    $car_inside = ${'car_inside'.($i+1)};
    $itemName = ${$itemNames[$i].'_item1'};
    $itemSpec = ${$itemNames[$i].'_item2'};
    $itemQuantity = ${$itemNames[$i].'_item3'};
    $itemUnit = ${$itemNames[$i].'_item4'};
    $comment = ${'comment'.($i+1)};

    echo "<tr>";
    echo "<td style='text-align:right; width: 40px;'>" . ($i + 1) . "</td>";
    echo "<td><input type='text' name='type" . ($i + 1) . "' value='$type' size='8'></td>";
    echo "<td style='text-align:right; width: 50px; color: blue;'>인승</td>";
    echo "<td><input type='text' name='inseung" . ($i + 1) . "' value='$inseung' size='2'></td>";
    echo "<td style='text-align:right; width: 70px; color: red;'>C inside</td>";
    echo "<td><input type='text' name='car_inside" . ($i + 1) . "' value='$car_inside' size='8'></td>";
    echo "<td style='text-align:right; width: 50px;'>품목</td>";
    echo "<td><select name='" . $itemNames[$i] . "_item1'>";
    foreach ($arr as $option) {
      echo "<option " . (($itemName == $option) ? "selected" : "") . " value='$option'>$option</option>";
    }
    echo "</select></td>";
    echo "<td style='text-align:center; width: 50px; color: blue;'>규격</td>";
    echo "<td><input type='text' name='" . $itemNames[$i] . "_item2' value='$itemSpec' size='20'></td>";
    echo "<td style='text-align:right; width: 50px; color: red;'>수량</td>";
    echo "<td><input type='text' name='" . $itemNames[$i] . "_item3' value='$itemQuantity' size='2'></td>";
    echo "<td style='text-align:left; width: 50px;'>단위</td>";
   echo "<td><input type='text' name='" . $itemNames[$i] . "_item4' value='$itemUnit' size='2'></td>";
    echo "<td style='text-align:left; width: 50px;'>비고</td>";
    echo "<td><input type='text' name='comment" . ($i+1) . "' value='$comment' size='50'></td>";
    echo "</tr>";
  }

  echo "</tbody>";
  echo "</table>";
}
?>



	     </div>		

	  </div>
		
	</div>
			 
</div> 

		
</form>		

<script>

$(document).ready(function() {
    for (let i = 2; i <= 10; i++) {
        $(`#type${i}`).click(function() {
            $(`input[name=type${i}]`).val($(`input[name=type${i - 1}]`).val());
            $(`input[name=inseung${i}]`).val($(`input[name=inseung${i - 1}]`).val());
            $(`input[name=car_inside${i}]`).val($(`input[name=car_inside${i - 1}]`).val());
        });
    }
});

$(document).ready(function() {
    for (let i = 1; i <= 10; i++) {
        $(`#cal_both${i}`).click(function() {
            let nextItem = (i === 10) ? 'tenth_item' : `${itemNumberToWord(i+1)}_item`;
            calculateBoth(i, `${itemNumberToWord(i)}_item`, nextItem);
        });
    }
});

function itemNumberToWord(number) {
    const items = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];
    return items[number - 1];
}


function calculateInseung(index) {
    const inside = $(`input[name=car_inside${index}]`).val();
    const wide_inside = inside.split('*');
    const wide = Number(wide_inside[0]);
    const depth = Number(wide_inside[1]);
    $(`input[name=inseung${index}]`).val(calinseung(wide, depth));
}

$(document).ready(function() {
    for (let i = 1; i <= 10; i++) {
        $(`input[name=car_inside${i}]`).focusout(function() {
            calculateInseung(i);
        });
    }
});

$(document).ready(function() {
    for (let i = 1; i <= 10; i++) {
        $(`#cal_frame${i}`).click(function() {
            calculateFrame(i, `${itemNumberToWord(i)}_item`);
        });
    }
});


$(document).ready(function(){	

	$('#closeBtn').click(function() {
		// if (window.opener && !window.opener.closed) {
			// window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
			// window.opener.location.reload(); // 부모 창 새로고침
		// }
		window.close(); // 현재 창 닫기
	});


// 덴크리,서한컴퍼니 사용자 권한 제한을 위한 명령어
 const user_name ="<?php echo $user_name; ?>";
 const mode ="<?php echo $mode; ?>";
  
	$("#pInput").val('50'); // 최초화면 사진파일 보여주기
	
let timer3 = setInterval(() => {  // 2초 간격으로 사진업데이트 체크한다.
	      if($("#pInput").val()=='100')   // 사진이 등록된 경우
		  {
	             displayfile();  
				$("#pInput").val('0');				 
		  }	      
		  if($("#pInput").val()=='50')   // 사진이 등록된 경우
		  {
	             displayfileLoad();	
				$("#pInput").val('100');
		  }		   
	 }, 2000);	
	

	
	 $("#saveBtn").click(function(){ 		
		// 조건 확인
		if($("#workplacename").val() === '' || $("#su").val()  === '' ) {
			showWarningModal();
		} else {
			
			Toastify({
				text: "저장중...",
				duration: 1000,
				close:true,
				gravity:"top",
				position: "center",
				style: {
					background: "linear-gradient(to right, #00b09b, #96c93d)"
				},
			}).showToast();	
			setTimeout(function(){
					 saveData();
			}, 1000);
		  
		}
	});

	function showWarningModal() {
		Swal.fire({                                    
			title: '등록 오류 알림',
			text: '현장명, 결합단위 수량은 필수입력 요소입니다.',
			icon: 'warning',
			// ... 기타 설정 ...
		}).then(result => {
			if (result.isConfirmed) { 
				return; // 사용자가 확인 버튼을 누르면 아무것도 하지 않고 종료
			}         
		});
	}

	function saveData() {
		
		var num = $("#num").val();  		
		
		$("#mode").val('insert');     			  			
			
		//  console.log($("#mode").val());    
		// 폼데이터 전송시 사용함 Get form         
		var form = $('#board_form')[0];  	    	
		var datasource = new FormData(form); 

		// console.log(data);
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}		 
		ajaxRequest = $.ajax({
			enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 1000000, 			
			url: "insert.php",
			type: "post",		
			data: datasource,			
			dataType: "json", 
			success : function(data){
				  // console.log('data :' , data);
				setTimeout(function(){	
				  Swal.fire(
					  '자료등록 완료',
					  '자료를 업데이트 중입니다. 잠시 기다려주세요.',
					  'success'
					);
				setTimeout(function(){									
							if (window.opener && !window.opener.closed) {
								window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
							}	
						setTimeout(function(){															
								 location.href = "view.php?num=" + data["num"];
						}, 1000);	
				}, 1000);		
			}, 1000);		
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		
			
	}	 
  
delPicFn = function(divID, delChoice) {
	console.log(divID, delChoice);

	$.ajax({
		url:'../file/del_file.php?savename=' + delChoice ,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const savename = data["savename"];		   
		   
		  // 시공전사진 삭제 
			$("#file" + divID).remove();  // 그림요소 삭제
			$("#delPic" + divID).remove();  // 그림요소 삭제
		    $("#pInput").val('');					
			
        });		

}
	    
 	 
	 
// 첨부파일 멀티업로드	
$("#upfile").change(function(e) {	    
	    $("#id").val('<?php echo $id;?>');
	    $("#parentid").val('<?php echo $id;?>');
	    $("#fileorimage").val('file');
	    $("#item").val('attached');
	    $("#upfilename").val('upfile');
	    $("#tablename").val('outorder');
	    $("#savetitle").val('첨부파일');		
	
		// 임시번호 부여 id-> parentid 시간초까지 나타내는 변수로 저장 후 저장하지 않으면 삭제함	
	 if(Number($("#id").val()) == 0) 
	      $("#id").val($("#timekey").val());   // 임시번호 부여 id-> parentid
	  
	  // 파일 서버에 저장하는 구간	
			// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 			
	

			$.ajax({
				enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
				processData: false,    
				contentType: false,      
				cache: false,           
				timeout: 600000, 			
				url: "../file/file_insert.php",
				type: "post",		
				data: data,						
				success : function(data){

					// 사진이 등록되었으면 100 입력됨
					 $("#pInput").val('100');						

				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
							} 			      		
			   });	

});		   
 
 	
  
// 매입처를 덴크리/서한컴퍼니 변경하면 동작하도록 구성	
$("#firstord").change(function(){
    Do_changeBtn()
});	
	
// 매입처를 덴크리/서한컴퍼니 변경하면 동작하도록 구성	 실제 실행코드
function Do_changeBtn() {
	if(mode !='modify')
	{
		if($('#firstord').val()==='서한컴퍼니')
		{
		// 서한컴퍼니 선택의 경우
			location.href = 'write_form.php?whichcompany=서한컴퍼니';
		}
		else if($('#firstord').val()==='덴크리')
		{
		// 덴크리 선택의 경우		  
			location.href = 'write_form.php?whichcompany=덴크리';		
		} 
		else if($('#firstord').val()==='다온텍')
		{		
			location.href = 'write_form.php?whichcompany=다온텍';		
		} 

	}
}
 
 console.log(user_name);
 if(user_name == '덴크리' || user_name == '서한컴퍼니'   || user_name == '다온텍'   )  // 김재현 주임 전번뒷자리 // 서한 컴퍼니 역시 수정권한 제한
   {	 
   $("div *").find("input,textarea,button,select").prop("readonly",true);	 
   $("#workday").prop("readonly",false);	 
   $("#update_log").prop("readonly",false);	 
   $("#delivery").prop("readonly",false);	 
   $("#deliverynum").prop("readonly",false);	 
   $("#delipay").prop("readonly",false);	 
   $("#submemo").prop("readonly",false);	 
   
   $("#write_button_renew").find("button").prop("readonly",false);	 
   }

 $("#pdf_file").change(function(){   
	//input file 태그.
	var file = document.getElementById('pdf_file');
	//파일 경로.
	var filePath = file.value;
	//전체경로를 \ 나눔.
	var filePathSplit = filePath.split('\\'); 
	//전체경로를 \로 나눈 길이.
	var filePathLength = filePathSplit.length;
	//마지막 경로를 .으로 나눔.
	var fileNameSplit = filePathSplit[filePathLength-1].split('.');
	//파일명 : .으로 나눈 앞부분
	var fileName = fileNameSplit[0];
	//파일 확장자 : .으로 나눈 뒷부분
	var fileExt = fileNameSplit[1];
	//파일 크기
	var fileSize = file.files[0].size;

	$("#pdffile_name").val(fileName);
	// $("#mainFrm").submit();
	
	// copied_file의 값을 변경해서 파일저장시 수정할지 여부 판단
	
	$("#copied_file").val('');
 });


$("#delattachedfileBtn").click(function(){  
  $("#pdffile_name").val('');
  $("#copied_file").val('');
});


calculateBoth = function(NUM, name1, name2) {
		const type = $("input[name=type" + NUM +"]" ).val();
		const inside = $("input[name=car_inside" + NUM +"]" ).val();
		const lc_su = $("input[name=lc_su]" ).val();		
	    const first_name = name1;
	    const second_name = name2;
		let nextNUM = NUM + 1;
		let result;
		let jungSu;
		let divider;		

		const wide_inside = inside.split('*');
		const wide = Number(wide_inside[0]);
        const depth=Number(wide_inside[1]);		

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
		$("input[name=" + first_name + "3]").val(lc_su);
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
				  else if (depth>=1000)
					  result_depth=(depth-60)/2 ;
				  else 
					   result_depth=(depth-60) ;
				  
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
		$("input[name=" + second_name + "3]").val(jungSu*lc_su);
		$("input[name=" + second_name + "4]").val('EA');	

}	   


calculateFrame = function(NUM, name1) {
		const type = $("input[name=type" + NUM +"]" ).val();
		const inside = $("input[name=car_inside" + NUM +"]" ).val();
		const lc_su = $("input[name=lc_su]" ).val();		
	    const first_name = name1;
		let result;
		let jungSu;

		const wide_inside = inside.split('*');
		const wide = Number(wide_inside[0]);
        const depth=Number(wide_inside[1]);			

		if(type=='011' || type=='012' ||type=='025' ||type=='017' ||type=='014') {
				   result=depth - 50;	
				   }
				else if(type=='013')
					{
						result=depth - 20;	
					}					

		$("input[name=" + first_name + "1]").val('프레임/중판X');
		$("input[name=" + first_name + "2]").val(result);
		$("input[name=" + first_name + "3]").val(lc_su);
		$("input[name=" + first_name + "4]").val('SET');
}	           
	


// 첨부된 파일 불러오기
function displayfile() {       
	$('#displayfile').show();
	params = $("#id").val();	
	
    var tablename = 'outorder';    
    var item = 'attached';
	
	$.ajax({
		url:'../file/load_file.php?id=' + params + '&tablename=' + tablename + '&item=' + item ,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recid = data["recid"];		   
		   console.log(data);
		   $("#displayfile").html('');
		   for(i=0;i<recid;i++) {	
			   $("#displayfile").append("<span id=file" + i + ">  <a href='../uploads/" + data["file_arr"][i] + "' download='" +  data["realfile_arr"][i]+ "'>" +  data["realfile_arr"][i] + "</span> &nbsp;&nbsp;&nbsp;&nbsp;  " );			   
         	   $("#displayfile").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + data["file_arr"][i] + "')> <i class='bi bi-dash-circle'></i> </button> </button>&nbsp;");					   
		      }		   
    });	
}

// 기존 있는 파일 화면에 보여주기
function displayfileLoad() {    
	$('#displayfile').show();	
	var savefilename_arr = <?php echo json_encode($savefilename_arr);?> ;	
	var realname_arr = <?php echo json_encode($realname_arr);?> ;	
	
    for(i=0;i<savefilename_arr.length;i++) {
			   $("#displayfile").append("<span id=file" + i + ">  <a href='../uploads/" + savefilename_arr[i] + "' download='" + realname_arr[i] + "'>" +  realname_arr[i] + "</span> &nbsp;&nbsp;&nbsp;&nbsp;  " );			   
         	   $("#displayfile").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  savefilename_arr[i] + "')><i class='bi bi-dash-circle'></i> </button> </button>&nbsp; ");					   
	  }	   
		
}
	
	
});  // end of ready

  
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
	const data1 = "outorder";
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
      // var postData = changeUri(document.getElementById("outworkplace").value);
      // var sendData = $(":input:radio[name=root]:checked").val();
     var tmp=$('#secondordman').val();
	 switch (tmp) {
		 case '김관' :
         $("#secondordmantel").val("010-2648-0225");		 
         $("#secondordman").val("김관부장");		 
         $("#secondord").val("한산");		 
		 break;		  		 
	 }
}

function exe_firstordman()
{
     var tmp=$('#firstordman').val();
	 switch (tmp) {
		 case '손시락' :
         $("#firstordman").val("손시락소장");		 		 
         $("#firstordmantel").val("010-6774-9253");		 
         $("#firstord").val("오티스");			 		 		 
         $("#secondord").val("우성");			 
		 break;		 		 	 
	 }
}


function exe_chargedman()
{
     var tmp=$('#chargedman').val();
	 switch (tmp) {
		 case '서재길' :
         $("#chargedmantel").val("010-3797-2665");		 		 
         $("#chargedman").val("서재길소장");
		 break;	
		
	 }
}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}


function load_init() {

    // 삭제할 부분과 남길부분
	// $('#board_form').find('input').each(function(){ $(this).val(''); });
	// $('#board_form').find('textarea').each(function(){ $(this).val(''); });
	// $('#board_form').find('select').each(function(){ $(this).val(''); });

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
	$('input[name=first_item1]').val("<? echo $first_item1; ?>");
	$('input[name=second_item1]').val("<? echo $second_item1; ?>");
	$('input[name=third_item1]').val("<? echo $third_item1; ?>");
	$('input[name=fourth_item1]').val("<? echo $fourth_item1; ?>");
	$('input[name=fifth_item1]').val("<? echo $fifth_item1; ?>");
	$('input[name=sixth_item1]').val("<? echo $sixth_item1; ?>");
	$('input[name=seventh_item1]').val("<? echo $seventh_item1; ?>");
	$('input[name=eighth_item1]').val("<? echo $eighth_item1; ?>");
	$('input[name=ninth_item1]').val("<? echo $ninth_item1; ?>");
	$('input[name=tenth_item1]').val("<? echo $tenth_item1; ?>");
	
	$('input[name=comment1]').val("<? echo $comment1; ?>");
	$('input[name=comment2]').val("<? echo $comment2; ?>");
	$('input[name=comment3]').val("<? echo $comment3; ?>");
	$('input[name=comment4]').val("<? echo $comment4; ?>");
	$('input[name=comment5]').val("<? echo $comment5; ?>");
	$('input[name=comment6]').val("<? echo $comment6; ?>");
	$('input[name=comment7]').val("<? echo $comment7; ?>");
	$('input[name=comment8]').val("<? echo $comment8; ?>");
	$('input[name=comment9]').val("<? echo $comment9; ?>");
	$('input[name=comment10]').val("<? echo $comment10; ?>");
	
	// 초기화 해주는 부분
	$('#orderday').val(getToday());
	$('#startday').val(getToday());
	$('#deadline').val('');
	$('#workday').val('');
	$('#workday').val('');
	$('#demand').val('');
	$('#dispaly_log').val('');
	$('#first_writer').val('');
}


// 타임함수 시간지나면 처리함
setTimeout(function() {
 load_init();
}, 3000);

$('#myModal').modal('show');		   
  
setTimeout(function() { 
	 $('#myModal').modal('hide');	      
}, 2500);	

 
function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

</script>

<!-- 다온텍의 수량의 입력시 결합단위 및 기타에 수량이 합산되는 로직 -->
    <script>
        $(document).on('input change', '.surang, #firstord', function() {
            if ($('#firstord').val() === '다온텍') {
                let total = 0;
                $('.surang').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#su').val(total);
                $('#etc_su').val(total);
            }
        });
    </script>


</body>
</html>
