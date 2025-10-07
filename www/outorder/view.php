<?php
require_once getDocumentRoot() . '/load_GoogleDrive.php'; // 세션 등 여러가지 포함됨 파일 포함

if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }  
   
include getDocumentRoot() . "/common.php";
 
    // 첫 화면 표시 문구
 $title_message = '외주발주 수주내역'; 
 
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
   
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $tablename=$_REQUEST["tablename"] ?? 'outorder' ;
	 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
 
 try{
     $sql = "select * from {$DB}.{$tablename} where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
     
	 include '_row.php';          
				  
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
  
  $deliverynum=$row["deliverynum"];  
  $confirm=$row["confirm"];
  $submemo=$row["submemo"];
  $pdffile_name = $row["pdffile_name"];
  $copied_file = $row["copied_file"];	
	
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 

// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;  
$author_id = $item_id  ;
  
require_once getDocumentRoot() . '/load_GoogleDriveSecond.php'; // attached, image에 대한 정보 불러오기  
?> 

<form  name="board_form" id="board_form"  method="post" enctype="multipart/form-data"  onkeydown="return captureReturnKey(event)">   

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
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->				  
    <input id="session_level" name="session_level" type="hidden" value='<?=$_SESSION["level"]?>'>		   
	<input type="hidden" id="strtmp" name="strtmp" value="<?=$strtmp?>">

<?php include getDocumentRoot() . '/common/modal.php'; ?>

<div class="container-fluid">	  
<div class="card p-1 m-1">	
<div class="card-body">	
	<div class="d-flex justify-content-center mt-3 mb-1"> 		
	   <h4>  <?=$title_message?>  </h4>
	</div>		
  	<div class="d-flex justify-content-start mt-2 mb-2 ">	
		<button class="btn btn-dark btn-sm me-1" id="closeBtn" > <ion-icon name="close-outline"></ion-icon> 창닫기 </button>
		<a href="write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1&check_draw=<?=$check_draw?>">
		<button type="button" class="btn btn-dark btn-sm me-1" ><ion-icon name="color-wand-outline"></ion-icon> 수정</button> 						
		</a>
		<? if($user_name !== '덴크리' && $user_name !== '서한컴퍼니' && $user_name !== '다온텍' )  { ?>				
		<button type="button" class="btn btn-dark btn-sm me-1" onclick="window.location.href='write_form.php';"> <ion-icon name="pencil-outline"></ion-icon> 신규 </button>
		
		<button type="button" class="btn btn-primary btn-sm me-1" onclick="window.location.href='copy_data.php?mode=copy&num=<?=$num?>'"> <ion-icon name="copy-outline"></ion-icon> 데이터 복사 </button> 						
		<button id="changeBtn" type="button" class="btn btn-danger btn-sm me-1" > <ion-icon name="transgender-outline"></ion-icon> 발주서변경 전달 </button> 								
		<button type="button" class="btn btn-danger btn-sm me-1" id="delBtn" > <ion-icon name="trash-outline"></ion-icon>  삭제 </button> 
		<? } ?>		 
		 <button type="button" class="btn btn-success btn-sm me-1" onclick="popupCenter('transform.php?num=<?=$num?>',' 발주서 인쇄', 1200,800 );"> <ion-icon name="print-outline"></ion-icon> 발주서 </button> 		 		 
			<?php if( $level <=5 )
				   print  '<button id=confirmBtn type="button" class="btn btn-warning btn-sm me-1" disabled > <ion-icon name="checkmark-done-outline"></ion-icon> (업체용) 발주서변경 확인완료 </button> '; 		   
				else
				  print  '<button id=confirmBtn type="button" class="btn btn-warning btn-sm me-1" > <ion-icon name="checkmark-done-outline"></ion-icon> (업체용) 발주서변경 확인완료 </button> '; 
			 ?>
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
				<?php
				  if ($copied_file!='')
				  {
					  ?>
					  도면 등 첨부파일(pdf, jpg) 다운로드
				<a style="font-weight:bold;font-size:12px;" href="./attachedfile/<?=$copied_file?>" download="<?=$pdffile_name?>" > 
				
				<button type="button" class="btn btn-primary btn-sm" > <?=iconv_substr($pdffile_name,0,25,"utf-8")?>   </button> 
				  </a>
				  <?php }  ?>
			  
			  </td>
			</tr>
		  
		  <tr>
			<td colspan="6">        
			 <span class="text-danger font-weight-bold">  추가첨부파일 	 </span>		
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
		   <input type="text" id="delivery"  name="delivery"  class="form-control"  value="<?=$delivery?>" placeholder="내역"   > 
		</td>

		<td > 운임</td>
		<td>
		   <input type="text" id="delipay"  name="delipay"  class="form-control"  value="<?=$delipay?>" placeholder="(있을시) 금액 " onkeyup="inputNumberFormat(this)" >
		</td>
	  
		<td >  경동택배 송장 </td>
		<td>
			 <input type="text" id="deliverynum" name="deliverynum"  class="form-control"  value="<?=$deliverynum?>" size="20" placeholder="번호"  >    
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
		  <input type="text" name="su" value="<?=$su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
		</td>

		<td>L/C 수량</td>
		<td>
		   <input type="text" name="lc_su" value="<?=$lc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
	     </td>
	  
		<td >기타 수량</td>
		<td>
		  <input type="text" name="etc_su" value="<?=$etc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')">
		</td>    
	  </tr>	
  
	  <tr>
		<td colspan="2">
		  <?php print "등록 : " . $first_writer; ?>		  
		  </td>

		<td colspan="4">
			<?php
				$update_log = preg_replace('/(\d{4})/', "\n$1", $update_log);
				$update_log = str_replace('<br />', "\n", $update_log);
				$update_log = htmlspecialchars($update_log);
			?>
			<textarea class="form-control" readonly name="update_log"><?= $update_log ?></textarea>
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
</div>
	
<div class="container">	
	<div class="card p-2 m-2"  style=" border: 1px solid #392f31;">	
	<div class="card-body">			
	<div class="table-reponsive table-sm"> 		
	  <table  class="table table-bordered">
	   <thead>
		 <tr>
		   <th class="text-center"> 번호 </th>
		   <th class="text-center"> 타입 </th>
		   <th class="text-center"> 인승 </th>
		   <th class="text-center text-danger"> inside </th>
		   <th class="text-center"> 품목 </th>
		   <th class="text-center" style="width:250px;"> 규격 </th>
		   <th class="text-center"> 수량 </th>
		   <th class="text-center"> 단위 </th>
		   <th class="text-center"> 비고 </th>
		 </tr>  
		</thead>
		<tbody>
	<?php if ($firstord == '덴크리' or $whichcompany == '덴크리') { ?>

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
		  ?>
			<tr>
			  <td class="text-center"><?= $i+1 ?></td>
			  <td class="text-center"><input type="text" name="type<?= $i+1 ?>" value="<?= $type ?>" class="form-control text-center" style="width:100px;" ></td>          
			  <td class="text-center"><input type="text" name="inseung<?= $i+1 ?>" value="<?= $inseung ?>"  class="form-control text-center" style="width:50px;" ></td>          
			  <td class="text-center"><input type="text" name="car_inside<?= $i+1 ?>" value="<?= $car_inside ?>"  class="form-control text-center" style="width:80px;" ></td>
			  <td class="text-center"><input type="text" name="<?= $itemNames[$i] ?>_item1" value="<?= $itemName ?>"  class="form-control text-center" style="width:100px;" ></td>          
			  <td class="text-center"><input type="text" name="<?= $itemNames[$i] ?>_item2" value="<?= $itemSpec ?>"  class="form-control text-center" style="width:250px;" ></td>          
			  <td class="text-center"><input type="text" name="<?= $itemNames[$i] ?>_item3" value="<?= $itemQuantity ?>"  class="form-control text-center" style="width:50px;" ></td>
			  <td class="text-center"><input type="text" name="<?= $itemNames[$i] ?>_item4" value="<?= $itemUnit ?>"  class="form-control text-center" style="width:50px;" ></td>
			  <td class="text-center"><input type="text" name="comment<?= $i+1 ?>" value="<?= $comment ?>"  class="form-control text-center" style="width:300px;" ></td>
			</tr>
		  <?php } ?>
	<?php } ?>

	<?php 
	if ($firstord == '서한컴퍼니' || $whichcompany == '서한컴퍼니' || $firstord == '다온텍' || $whichcompany == '다온텍') {
	  $arr = ['','LED_BAR','SMPS','프로파일','광학산PC','아크릴커버','유백색아크릴'];
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

		echo "<tr>";
		echo "<td style='text-align:right; width: 40px;'>" . ($i + 1) . "</td>";
		echo "<td><input type='text' name='type" . ($i + 1) . "' value='$type' size='8'></td>";		
		echo "<td><input type='text' name='inseung" . ($i + 1) . "' value='$inseung' size='2'></td>";		
		echo "<td><input type='text' name='car_inside" . ($i + 1) . "' value='$car_inside' size='8'></td>";		
		echo "<td><select name='item" . ($i + 1) . "_1'>";
		foreach ($arr as $option) {
		  echo "<option " . (($itemName == $option) ? "selected" : "") . " value='$option'>$option</option>";
		}
		echo "</select></td>";		
		echo "<td><input type='text' name='item" . ($i + 1) . "_2' value='$itemSpec' size='30' ></td>";		
		echo "<td><input type='text' name='item" . ($i + 1) . "_3' value='$itemQuantity' size='2'></td>";		
		echo "<td><input type='text' name='item" . ($i + 1) . "_4' value='$itemUnit' size='2'></td>";		
		echo "<td><input type='text' name='comment" . ($i + 1) . "' value='$comment' size='50'></td>";
		echo "</tr>";
	  }

	}
	?>

				</tbody>
			  </table>
			 </div>		
		  </div>		
	</div>			 
</div> 	
</form>

 <script> 
 // ajax 중복처리를 위한 구문
var ajaxRequest = null;
 
 $(document).ready(function(){
	 
	 	$('#closeBtn').click(function() {		
			window.close(); // 현재 창 닫기
			});
	 
	 	$('#delBtn').click(function() {
				
		var level = Number($('#session_level').val());
		if (level > 2) {
			Swal.fire({
				title: '관리자 권한 필요',
				text: "삭제하려면 관리자에게 문의해 주세요.",
				icon: 'error',
				confirmButtonText: '확인'
			});
		} else {
			Swal.fire({
				title: '자료 삭제',
				text: "삭제는 신중! 정말 삭제하시겠습니까?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '삭제',
				cancelButtonText: '취소'
			}).then((result) => {
				if (result.isConfirmed) {

					if (ajaxRequest !== null) {
						ajaxRequest.abort();
					}					 			 
					ajaxRequest = $.ajax({
						url:'delete.php',
						type:'post',
						data: $("#board_form").serialize(),
						dataType: 'json',
						success : function(data){						  
							console.log(data);	
							// 삭제 후 처리
							Toastify({
								text: "파일 삭제완료 ",
								duration: 2000,
								close: true,
								gravity: "top",
								position: "center",
								style: {
									background: "linear-gradient(to right, #00b09b, #96c93d)"
								},
							}).showToast();
							setTimeout(function() {
									if (window.opener && !window.opener.closed) {
										window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
										window.opener.location.reload(); // 부모 창 새로고침
									}
									window.close(); // 현재 창 닫기	
								}, 1000);							
							
						},
						error : function( jqxhr , status , error ){
							console.log( jqxhr , status , error );
									} 

					  });
				}
			});
		}	

		
	});
   	 
	 // $("div *").disable();
   $("div *").find("input,textarea").prop("readonly",true);	  // disabled는 값 전달 안됨. readonly 사용
   $("div *").find("select").prop("disabled",true);	  // disabled는 값 전달 안됨. readonly 사용
   
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});	   
   
   // 발주서 변경버튼 클릭시
   $("#changeBtn").click(function(){  
     // confirm내용 변경해서 전달함
     $("#strtmp").val('발주서변경');
		$.ajax({
			url: "writeDB.php",
			type: "post",		
			data: $("#board_form").serialize(),
			dataType:"json",
			success : function( data ){
				console.log( data);
				// opener.location.reload();
				// window.close();	
			  tmp='발주서 변경이 접수되었습니다.';		
			  $('#alertmsg').html(tmp); 		  
			  $('#myModal').modal('show');  
			  
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });			
   });	
   
   // 발주서 변경서 확인완료 버튼 클릭시
   $("#confirmBtn").click(function(){  
     // confirm내용 변경해서 전달함
     $("#strtmp").val('재확인완료');
   
	if (ajaxRequest !== null) {
		ajaxRequest.abort();
	}

	 // ajax 요청 생성
	 ajaxRequest = $.ajax({
		url: "writeDB.php",
		type: "post",		
		data: $("#board_form").serialize(),
		dataType:"json",
		success : function( data ){
			console.log( data);
		    // opener.location.reload();
		    // window.close();			
          tmp='미리기업 발주서 변경내용을 확인했습니다.';		
		  $('#alertmsg').html(tmp); 		  
		  $('#myModal').modal('show');  			
			
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });			
   });		   
 }); // end of ready document
 
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

function displayoutputlist(){	 
   $("#displayoutput").show(); 
   $("#displayoutput").load("./outputlist.php");	 	 		 
}

function captureReturnKey(e) 
{
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
} 	 
</script>



<script>
$(document).ready(function(){

	 displayFileLoad();				 
	 displayImageLoad();	

}); // end of ready document
 
// 기존 있는 이미지 화면에 보여주기
function displayImageLoad() { 
	$('#displayImage').show();	
	var saveimagename_arr = <?php echo json_encode($saveimagename_arr);?> ;	

    $("#displayImage").html('');
    saveimagename_arr.forEach(function(pic, index) {
        var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
		const realName = pic.realname || '다운로드 파일';
        var link = pic.link || '#';
        var fileId = pic.fileId || null;

        if (!fileId) {
            console.error("fileId가 누락되었습니다. index: " + index, pic);
            return; // fileId가 없으면 해당 항목 건너뛰기
        }

		$("#displayImage").append(
			"<div class='row mt-2 mb-1'>" +
				"<div class='d-flex justify-content-center mt-1 mb-1'>" +
					"<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
						"<img id='pic" + index + "' src='" + thumbnail + "' style='width:300px; height:auto;'>" +
					"</a>" +
				"</div>" +
			"</div>"
		);

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

			// 파일 정보 행 추가
			$("#displayFile").append(
				"<div class='row mb-3'>" +
					"<div id='file" + i + "' class='col d-flex align-items-center justify-content-center'>" +
						"<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" +
							realName +
						"</a> &nbsp; &nbsp; " +
					"</div>" +
				"</div>"
			);

        });
    } else {
        $("#displayFile").append(
            "<div class='text-center text-muted'>No attached files</div>"
        );
    }
}

</script>

</body>
</html>    