<?php
require_once getDocumentRoot() . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 8) {
    sleep(1);
    header("Location: {$WebSite}login/login_form.php"); 
    exit;
}

$title_message = '외주발주 관리 등록/수정';

include getDocumentRoot() . '/load_header.php';
?>

<title> <?= $title_message ?> </title>

<style>
    .table, td, input {      
        vertical-align: middle;	    	    
        text-align:center;
    }
    .table td input.form-control {
        border: 1px solid #ced4da;
        border-radius: 2px;
    }    
</style> 
 
</head>
<body>

<?php 

$pdo = db_connect();

$whichcompany = $_REQUEST["whichcompany"] ?? "덴크리";
$mode = $_REQUEST["mode"] ?? "";
$num = $_REQUEST["num"] ?? "";
isset($_REQUEST["tablename"]) ? $tablename=$_REQUEST["tablename"] :  $tablename='outorder'; 

if ($mode == "modify") {
    try {
        $sql = "SELECT * FROM mirae8440.outorder WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        include '_row.php';

        $dates = ['workday', 'demand', 'orderday', 'deadline', 'testday', 'lc_draw', 'lclaser_date', 'lclbending_date', 'lclwelding_date', 'lcpainting_date', 'lcassembly_date', 'main_draw', 'eunsung_make_date', 'eunsung_laser_date', 'mainbending_date', 'mainwelding_date', 'mainpainting_date', 'mainassembly_date', 'order_date1', 'order_date2', 'order_date3', 'order_date4', 'order_input_date1', 'order_input_date2', 'order_input_date3', 'order_input_date4'];
        
        foreach ($dates as $date) {
            $$date = trans_date($$date);
        }
        
        $deliverynum = $row["deliverynum"];
        $confirm = $row["confirm"];
        $submemo = $row["submemo"];
        $pdffile_name = $row["pdffile_name"];
        $copied_file = $row["copied_file"];
    } catch (PDOException $Exception) {
        echo "오류: " . $Exception->getMessage();
    }
} else {
    $orderday = $startday = date("Y-m-d");
    $fields = ['checkstep', 'workplacename', 'address', 'firstord', 'firstordman', 'firstordmantel', 'secondord', 'secondordman', 'secondordmantel', 'chargedman', 'measureday', 'drawday', 'deadline', 'testday', 'hpi', 'workday', 'worker', 'endworkday', 'material1', 'material2', 'material3', 'material4', 'material5', 'material6', 'widejamb', 'normaljamb', 'smalljamb', 'memo', 'update_day', 'regist_day', 'delivery', 'delicar', 'delicompany', 'delipay', 'delimethod', 'demand', 'update_log', 'type', 'inseung', 'su', 'bon_su', 'lc_su', 'etc_su', 'air_su', 'car_inside', 'order_com1', 'order_text1', 'order_com2', 'order_text2', 'order_com3', 'order_text3', 'order_com4', 'order_text4', 'lc_draw', 'lclaser_com', 'lclaser_date', 'lcbending_date', 'lcwelding_date', 'lcpainting_date', 'lcassembly_date', 'main_draw', 'eunsung_make_date', 'eunsung_laser_date', 'mainbending_date', 'mainwelding_date', 'mainpainting_date', 'mainassembly_date', 'memo2', 'order_date1', 'order_date2', 'order_date3', 'order_date4', 'order_input_date1', 'order_input_date2', 'order_input_date3', 'order_input_date4', 'first_item1', 'first_item2', 'first_item3', 'first_item4', 'second_item1', 'second_item2', 'second_item3', 'second_item4', 'deliverynum', 'confirm', 'submemo', 'pdffile_name', 'copied_file'];
    
    foreach ($fields as $field) {
        $$field = "";
    }
}

$material_arr = ['', '304 Hair Line 1.2T', '304 HL 1.2T', '304 Mirror 1.2T', '304 MR 1.2T', 'VB 1.2T', '2B VB 1.2T', '304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)', '기타'];

if (!empty($firstord) && $whichcompany != $firstord) {
    $whichcompany = $firstord;
}

// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;    
require_once getDocumentRoot() . '/load_GoogleDriveSecond.php'; // attached, image에 대한 정보 불러오기  
?>
 
   
<form id="board_form" name="board_form" method="post" enctype="multipart/form-data" onkeydown="return captureReturnKey(event)"  >	   
 	  
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
	<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
	<input type="hidden" id="copied_file" name="copied_file" value="<?=$copied_file?>"  >
	<input type="hidden" id="confirm" name="confirm" value="<?=$confirm?>"  >	
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  									
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  										
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->				
	<input type="hidden" id="searchtext" name="searchtext" value="<?=$searchtext?>" >  <!-- summernote text저장 -->		
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								

	
<?php include getDocumentRoot() . '/common/modal.php'; ?>	
	
  <!-- 전화번호 Modal -->
  <div class="modal fade" id="Modal_tel" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">전화번호 검색</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-2 mb-5 justify-content-center" >
		  특이사항이 저장되었습니다. <br> 
		   <br> 
		  귀하의 노고에 감사드립니다.
			</div>
        </div>
		   <div class="modal-footer">
				<button type="button" id="closeModal_telBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
          </div>
        </div>
      
       </div>
     </div> 
	
<div class="container-fluid">	
<div class="card p-2 m-2">	
<div class="card-body">	 	
			
	 <div class="d-flex justify-content-center mt-3 mb-1"> 		
	   <h4> <?=$title_message ?> </h4>
	</div>		

  	<div class="d-flex justify-content-start mt-2 mb-2 ">	
		<button type="button" id="saveBtn" class="btn btn-dark btn-sm me-2" > <ion-icon name="save-outline"></ion-icon> 저장</button> 											   
		<button class="btn btn-dark btn-sm mx-2" id="closeBtn" > <ion-icon name="close-outline"></ion-icon> 창닫기 </button>
	</div>
 
<div class="row justify-content-center mb-1 mt-1"> 	
<?php if($chkMobile) { ?>	
	<div class="col-sm-12 p-1  rounded"   style=" border: 1px solid #392f31; " > 
<?php } if(!$chkMobile) { ?>	
	<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 
<?php  } ?>	

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
				<td style="width:70px;"  >담당</td>				  
				  <td> <input type="text" id="firstordman" name="firstordman" value="<?=$firstordman?>" class="form-control" > </td>
				<td>Tel</td>				  				  
				  <td> <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control" > </td>				
			  </td>
			</tr>
			<tr>
			  <td>발주처</td>
			  <td><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
			  <td>담당</td>
			  <td><input type="text" id="secondordman" name="secondordman" value="<?=$secondordman?>" class="form-control" ></td>
			  <td>Tel</td>
			  <td><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
			</tr>
			<tr>
			  <td>담당자</td>
			  <td><input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" onkeydown="Enter_chargedman_Check(event);"></td>
			  <td>Tel</td>
			  <td><input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control"></td>
			  <td colspan="2">
			  
				<label  for="pdf_file" class="btn btn-primary btn-sm mb-1"> 첨부파일(jpg,pdf)</label> &nbsp;&nbsp;
				<input id="pdf_file" type="file" name="pdf_file" style="display:none;" accept=".pdf,.jpg,.jpeg,.png" />
				<button id="delattachedfileBtn" type="button" class="btn btn-outline-danger btn-sm"  > 첨부삭제  </button> 
				<input type="text" id="pdffile_name" name="pdffile_name" class="form-control" value="<?=$pdffile_name?>" >    
			  
			  </td>
			</tr>
		  
		  <tr>
			<td colspan="6">        
				<label for="upfile" class="input-group-text btn btn-outline-primary btn-sm"> 추가첨부 </label>						  										
				<input id="upfile"  name="upfile[]" type="file" onchange="this.value" multiple  style="display:none" >	
				 <div id ="displayFile" style="display:none; text-align:left; width:500px; ">  				
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
	
<?php if($chkMobile) { ?>	
	<div class="col-sm-12 p-1  rounded"   style=" border: 1px solid #392f31; " > 
<?php } if(!$chkMobile) { ?>	
	<div class="col-sm-7 p-1  rounded"   style=" border: 1px solid #392f31; " > 
<?php  } ?>		
	

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
		   <input type="text" id="delivery"  name="delivery"  class="form-control" value="<?=$delivery?>" placeholder="내역"   > 
		</td>

		<td > 운임</td>
		<td>
		   <input type="text" id="delipay"  name="delipay"  class="form-control" value="<?=$delipay?>" placeholder="(있을시) 금액 " onkeyup="inputNumberFormat(this)" >
		</td>
	  
		<td >  경동택배 송장 </td>
		<td>
			 <input type="text" id="deliverynum" name="deliverynum" value="<?=$deliverynum?>" class="form-control"  placeholder="번호"  >    
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
          <td style="text-align:right; width: 30px;"><?= $i+1 ?></td>
          <td><input type="text" name="type<?= $i+1 ?>" value="<?= $type ?>" size="10"></td>

          <td style="text-align:right; width: 30px; color: blue;">인승</td>
          <td><input type="text" name="inseung<?= $i+1 ?>" value="<?= $inseung ?>" size="2"></td> 

          <td style="text-align:right; width: 50px; color: red;">inside</td>
          <td><input type="text" name="car_inside<?= $i+1 ?>" value="<?= $car_inside ?>" size="8"></td>

		<td style="width:170px;"><button type='button' class='btn btn-primary btn-sm' id="<?=$cal_both?>" >프레임/중판</button></td>
		<td style="width:220px;"><button type='button' class='btn btn-secondary btn-sm' id="<?=$cal_frame?>" >프레임/중판X</button></td>

          <td style="text-align:right; width: 30px;">품목</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item1" value="<?= $itemName ?>" size="12"></td>

          <td style="text-align:right; width: 30px; color: blue;">규격</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item2" value="<?= $itemSpec ?>" size="35"></td>

          <td style="text-align:right; width: 30px; color: red;">수량 </td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item3" value="<?= $itemQuantity ?>"style="width:35px;"  ></td>

          <td style="text-align:left; width: 30px;">단위</td>
          <td><input type="text" name="<?= $itemNames[$i] ?>_item4" value="<?= $itemUnit ?>" style="width:40px;" ></td>

          <td style="text-align:left; width: 30px;">비고</td>
          <td><input type="text" name="comment<?= $i+1 ?>" value="<?= $comment ?>" style="width:400px;"></td>
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
    echo "<td><input type='text' name='" . $itemNames[$i] . "_item2' value='$itemSpec' size='35'></td>";
    echo "<td style='text-align:right; width: 50px; color: red;'>수량</td>";
    echo "<td><input type='text' name='" . $itemNames[$i] . "_item3' class='surang' value='$itemQuantity' size='2'></td>";
    echo "<td style='text-align:left; width: 50px;'>단위</td>";
   echo "<td><input type='text' name='" . $itemNames[$i] . "_item4' value='$itemUnit' size='2'></td>";
    echo "<td style='text-align:left; width: 50px;'>비고</td>";
    echo "<td><input type='text' name='comment" . ($i+1) . "' value='$comment' size='60'></td>";
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
		window.close(); // 현재 창 닫기
	});

	$('#closeModalBtn').click(function() {		
		$('#myModal').modal('hide');
	});

	$('#closeModal_telBtn').click(function() {		
		$('#Modal_tel').modal('hide');
	});


// 덴크리,서한컴퍼니 사용자 권한 제한을 위한 명령어
 const user_name ="<?php echo $user_name; ?>";
 const mode ="<?php echo $mode; ?>";
  		
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
		
		// 결재상신이 아닌경우 수정안됨     
		if(Number(num) < 1) 				
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
								// 부모 창에 restorePageNumber 함수가 있는지 확인
								if (typeof window.opener.restorePageNumber === 'function') {
									window.opener.restorePageNumber(); // 함수가 있으면 실행
								} 
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
		
});  // end of ready


function Enter_chargedman_Check(event){    
    if(event.keyCode == 13){
      event.preventDefault(); // 폼 제출 방지
	const data1 = "outorder";
	const data2 = "chargedman";
	const data3 = "chargedmantel";	
	const search = $("#" + data2).val();
	// $('#Modal_tel').modal('show');
		if(event.keyCode == 13){             
			popupCenter('../ceiling/load_tel.php?search=' + search +'&data1=' + data1 + '&data2=' + data2 + '&data3=' + data3,'담당자 전번 조회',1500, 600);	  
		
		  }
	return false; // 이벤트 전파 방지
    }
return false; // 이벤트 전파 방지
}

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


<script>
$(document).ready(function () {
	displayFileLoad();	// 기존파일 업로드 보이기			 
	displayImageLoad();	// 기존이미지 업로드 보이기			 
	
    // 첨부파일 업로드 처리
    $("#upfile").change(function (e) {
		if (this.files.length === 0) {
			// 파일이 선택되지 않았을 때
			console.warn("파일이 선택되지 않았습니다.");
			return;
		}		
		
        const form = $('#board_form')[0];
        const data = new FormData(form);

        // 추가 데이터 설정
        data.append("tablename", $("#tablename").val() );
        data.append("item", "attached");
        data.append("upfilename", "upfile"); // upfile 파일 name
        data.append("folderPath", "미래기업/uploads");
		data.append("DBtable", "picuploads");        

		showMsgModal(2); // 파일저장중

        // AJAX 요청 (Google Drive API)
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "/filedrive/fileprocess.php",
            type: "POST",
            data: data,
            success: function (response) {
                 console.log("응답 데이터:", response);

                let successCount = 0;
                let errorCount = 0;
                let errorMessages = [];

                response.forEach((item) => {
                    if (item.status === "success") {
                        successCount++;
                    } else if (item.status === "error") {
                        errorCount++;
                        errorMessages.push(`파일: ${item.file}, 메시지: ${item.message}`);
                    }
                });

                if (successCount > 0) {
                    Toastify({
                        text: `${successCount}개의 파일이 성공적으로 업로드되었습니다.`,
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                }

                if (errorCount > 0) {
                    Toastify({
                        text: `오류 발생: ${errorCount}개의 파일 업로드 실패\n상세 오류: ${errorMessages.join("\n")}`,
                        duration: 5000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#f44336",
                    }).showToast();
                }

                setTimeout(function () {                    
					displayFile();
					hideMsgModal();	
                }, 1000);
                
            },
            error: function (jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
            },
        });
    });
	
    // 첨부 이미지 업로드 처리
    $("#upfileimage").change(function (e) {
		if (this.files.length === 0) {
			// 파일이 선택되지 않았을 때
			console.warn("파일이 선택되지 않았습니다.");
			return;
		}	
		
        const form = $('#board_form')[0];
        const data = new FormData(form);
		
        // 추가 데이터 설정
        data.append("tablename", $("#tablename").val() );
        data.append("item", "image");
        data.append("upfilename", "upfileimage"); // upfile 파일 name
        data.append("folderPath", "미래기업/uploads");
        data.append("DBtable", "picuploads");

		showMsgModal(1); // 이미지저장중

        // AJAX 요청 (Google Drive API)
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "/filedrive/fileprocess.php",
            type: "POST",
            data: data,
            success: function (response) {
                console.log("응답 데이터:", response);

                let successCount = 0;
                let errorCount = 0;
                let errorMessages = [];

                response.forEach((item) => {
                    if (item.status === "success") {
                        successCount++;
                    } else if (item.status === "error") {
                        errorCount++;
                        errorMessages.push(`파일: ${item.file}, 메시지: ${item.message}`);
                    }
                });

                if (successCount > 0) {
                    Toastify({
                        text: `${successCount}개의 파일이 성공적으로 업로드되었습니다.`,
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#4fbe87",
                    }).showToast();
                }

                if (errorCount > 0) {
                    Toastify({
                        text: `오류 발생: ${errorCount}개의 파일 업로드 실패\n상세 오류: ${errorMessages.join("\n")}`,
                        duration: 5000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#f44336",
                    }).showToast();
                }

                setTimeout(function () {
					displayImage();
					hideMsgModal();						
                }, 1000);
                
            },
            error: function (jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
            },
        });
    });


});

// 화면에서 저장한 첨부된 파일 불러오기
function displayFile() {
    $('#displayFile').show();
    const params = $("#timekey").val() ? $("#timekey").val() : $("#num").val();

    if (!params) {
        console.error("ID 값이 없습니다. 파일을 불러올 수 없습니다.");
        alert("ID 값이 유효하지 않습니다. 다시 시도해주세요.");
        return;
    }

    console.log("요청 ID:", params); // 요청 전 ID 확인

    $.ajax({
        url: '/filedrive/fileprocess.php',
        type: 'GET',
        data: {
            num: params,
			tablename: $("#tablename").val(),
            item: 'attached',
            folderPath: '미래기업/uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("파일 데이터:", data);

        $("#displayFile").html(''); // 기존 내용 초기화

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (fileData, index) {
                const realName = fileData.realname || '다운로드 파일';
                const link = fileData.link || '#';
                const fileId = fileData.fileId || null;

                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, fileData);
                    $("#displayFile").append(
                        "<div class='text-danger'>파일 ID가 누락되었습니다.</div>"
                    );
                    return;
                }

				$("#displayFile").append(
					"<div class='row mt-1 mb-2'>" +
						"<div class='d-flex align-items-center justify-content-center'>" +
							"<span id='file" + index + "'>" +
								"<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" + realName + "</a>" +
							"</span> &nbsp;&nbsp;" +
							"<button type='button' class='btn btn-danger btn-sm' id='delFile" + index + "' onclick=\"delFileFn('" + index + "', '" + fileId + "')\">" +
								"<ion-icon name='trash-outline'></ion-icon>" +
							"</button>" +
						"</div>" +
					"</div>"
				);


            });
        } else {
            $("#displayFile").append(
                "<div class='text-center text-muted'>No files</div>"
            );
        }
    }).fail(function (error) {
        console.error("파일 불러오기 오류:", error);
        Swal.fire({
            title: "파일 불러오기 실패",
            text: "파일을 불러오는 중 문제가 발생했습니다.",
            icon: "error",
            confirmButtonText: "확인",
        });
    });
}

// 기존 파일 불러오기 (Google Drive에서 가져오기)
function displayFileLoad() {
    $('#displayFile').show();
    var data = <?php echo json_encode($savefilename_arr); ?>;

    $("#displayFile").html(''); // 기존 내용 초기화

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function (fileData, i) {
            const realName = fileData.realname || '다운로드 파일';
            const link = fileData.link || '#';
            const fileId = fileData.fileId || null;

            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + i, fileData);
                return;
            }

			$("#displayFile").append(
				"<div class='row mb-3'>" +
					"<div class='d-flex mb-3 align-items-center justify-content-center'>" +
						"<span id='file" + i + "'>" +
							"<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" + realName + "</a>" +
						"</span> &nbsp;&nbsp;" +
						"<button type='button' class='btn btn-danger btn-sm' id='delFile" + i + "' onclick=\"delFileFn('" + i + "', '" + fileId + "')\">" +
							"<ion-icon name='trash-outline'></ion-icon>" +
						"</button>" +
					"</div>" +
				"</div>"
			);

        });
    } else {
        $("#displayFile").append(
            "<div class='text-center text-muted'>No files</div>"
        );
    }
}

// 파일 삭제 처리 함수
function delFileFn(divID, fileId) {
    Swal.fire({
        title: "파일 삭제 확인",
        text: "정말 삭제하시겠습니까?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "삭제",
        cancelButtonText: "취소",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'DELETE',
                data: JSON.stringify({
                    fileId: fileId,
                    tablename: $("#tablename").val(),
                    item: "attached",
                    folderPath: "미래기업/uploads",
                    DBtable: "picuploads",
                }),
                contentType: "application/json",
                dataType: 'json',
            }).done(function (response) {
                if (response.status === 'success') {
                    console.log("삭제 완료:", response);
                    $("#file" + divID).remove();
                    $("#delFile" + divID).remove();

                    Swal.fire({
                        title: "삭제 완료",
                        text: "파일이 성공적으로 삭제되었습니다.",
                        icon: "success",
                        confirmButtonText: "확인",
                    });
                } else {
                    console.log(response.message);
                }
            }).fail(function (error) {
                console.error("삭제 중 오류:", error);
                Swal.fire({
                    title: "삭제 실패",
                    text: "파일 삭제 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인",
                });
            });
        }
    });
}

// 첨부된 이미지 불러오기
function displayImage() {
    $('#displayImage').show();
    const params = $("#timekey").val() ? $("#timekey").val() : $("#num").val();

    if (!params) {
        console.error("ID 값이 없습니다. 파일을 불러올 수 없습니다.");
        alert("ID 값이 유효하지 않습니다. 다시 시도해주세요.");
        return;
    }

    console.log("요청 ID:", params); // 요청 전 ID 확인

    $.ajax({
        url: '/filedrive/fileprocess.php',
        type: 'GET',
        data: {
            num: params,
            tablename: $("#tablename").val(),
            item: 'image',
            folderPath: '미래기업/uploads',
        },
        dataType: 'json',
    }).done(function (data) {
        console.log("파일 데이터:", data);

        $("#displayImage").html(''); // 기존 내용 초기화

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function (fileData, index) {
                const realName = fileData.realname || '다운로드 파일';
                const thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
                const link = fileData.link || '#';
                const fileId = fileData.fileId || null;

                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, fileData);
                    $("#displayImage").append(
                        "<div class='text-danger'>파일 ID가 누락되었습니다.</div>"
                    );
                    return;
                }

				$("#displayImage").append(
					"<div class='row mb-3'>" +
						"<div class='col d-flex align-items-center justify-content-center'>" +
							"<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
								"<img id='image" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
							"</a> &nbsp;&nbsp;" +
							"<button type='button' class='btn btn-danger btn-sm' id='delImage" + index + "' onclick=\"delImageFn('" + index + "', '" + fileId + "')\">" +
								"<ion-icon name='trash-outline'></ion-icon>" +
							"</button>" +
						"</div>" +
					"</div>"
				);

            });
        } else {
            $("#displayImage").append(
                "<div class='text-center text-muted'>No files</div>"
            );
        }
    }).fail(function (error) {
        console.error("파일 불러오기 오류:", error);
        Swal.fire({
            title: "파일 불러오기 실패",
            text: "파일을 불러오는 중 문제가 발생했습니다.",
            icon: "error",
            confirmButtonText: "확인",
        });
    });
}

// 기존 이미지 불러오기 (Google Drive에서 가져오기)
function displayImageLoad() {
    $('#displayImage').show();
    var data = <?php echo json_encode($saveimagename_arr); ?>;

    $("#displayImage").html(''); // 기존 내용 초기화

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function (fileData, i) {
            const realName = fileData.realname || '다운로드 파일';
            const thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
            const link = fileData.link || '#';
            const fileId = fileData.fileId || null;

            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + i, fileData);
                return;
            }

			$("#displayImage").append(
				"<div class='row mb-3'>" +
					"<div class='col d-flex align-items-center justify-content-center'>" +
						"<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
							"<img id='image" + i + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
						"</a> &nbsp;&nbsp;" +
						"<button type='button' class='btn btn-danger btn-sm' id='delImage" + i + "' onclick=\"delImageFn('" + i + "', '" + fileId + "')\">" +
							"<ion-icon name='trash-outline'></ion-icon>" +
						"</button>" +
					"</div>" +
				"</div>"
			);

        });
    } else {
        $("#displayImage").append(
            "<div class='text-center text-muted'>No files</div>"
        );
    }
}

// 이미지 삭제 처리 함수
function delImageFn(divID, fileId) {
    Swal.fire({
        title: "이미지 삭제 확인",
        text: "정말 삭제하시겠습니까?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "삭제",
        cancelButtonText: "취소",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'DELETE',
                data: JSON.stringify({
                    fileId: fileId,
                    tablename: $("#tablename").val(),
                    item: "image",
                    folderPath: "미래기업/uploads",
                    DBtable: "picuploads",
                }),
                contentType: "application/json",
                dataType: 'json',
            }).done(function (response) {
                if (response.status === 'success') {
                    console.log("삭제 완료:", response);
                    $("#image" + divID).remove();
                    $("#delImage" + divID).remove();

                    Swal.fire({
                        title: "삭제 완료",
                        text: "파일이 성공적으로 삭제되었습니다.",
                        icon: "success",
                        confirmButtonText: "확인",
                    });
                } else {
                    console.log(response.message);
                }
            }).fail(function (error) {
                console.error("삭제 중 오류:", error);
                Swal.fire({
                    title: "삭제 실패",
                    text: "파일 삭제 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인",
                });
            });
        }
    });
}

$(document).ready(function () {
    $('#workday').on('change', function () {
        var workdayValue = $(this).val(); // workday의 선택된 날짜
        var deadlineValue = $('#deadline').val(); // deadline의 현재 날짜

        if (workdayValue) {
            // workday와 deadline 값을 Date 객체로 변환
            var workdayDate = new Date(workdayValue);
            var deadlineDate = new Date(deadlineValue);

            // deadline이 workday 이전 날짜인지 확인
            if (deadlineValue && deadlineDate < workdayDate) {
                // deadline을 workday와 같은 날짜로 수정
                $('#deadline').val(workdayValue);
                // alert('납기일이 출고일 이전 날짜이므로 출고일과 동일하게 변경되었습니다.');
                console.log('납기일이 출고일 이전 날짜이므로 출고일과 동일하게 변경되었습니다.');
            }
        }
    });
});
</script>

</body>
</html>