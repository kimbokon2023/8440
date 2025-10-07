<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

 if(!isset($_SESSION["level"]) || $level>=5) {
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");
         exit;
   }   
  
$title_message = "원자재 구매"  ;
  
?>
  
  
<?php include getDocumentRoot() . '/load_header.php' ?>


<title> <?=$title_message?> </title>

<style>
input {
    border: 1px solid #f5f5f5;
}

th, td {
    vertical-align: middle !important;
}

</style>

</head>

<body>
<?php include getDocumentRoot() . '/common/modal.php'; ?>

<?php

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
  
include '_request.php';	


 // 철판종류에 대한 추출부분
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
  
// JSON 파일 읽기
$jsonData = file_get_contents("../steelsourcejson.json");

// JSON을 PHP 배열로 변환
$data = json_decode($jsonData, true);

// 데이터 배열에서 각각의 부분을 추출
$steelsource_num = $data["steelsource_num"];
$steelsource_item = $data["steelsource_item"];
$steelsource_spec = $data["steelsource_spec"];
$steelsource_take = $data["steelsource_take"];
$steelitem_arr = $data["steelitem_arr"];  

$steelsource_item = array_values(array_filter($steelsource_item, 'strlen'));
$steelsource_item = array_values(array_unique($steelsource_item));
sort($steelsource_item);
$sumcount = count($steelsource_item);
// array_unshift($steelsource_item, " "); // 앞에 공백을 추가하는 공식
// var_dump($steelsource_item);


     try{
      $sql = "select * from ".$DB.".eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

		include '_row.php';	  
	  
	  
			 if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					else $indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 		
					
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }

?>

<form  id="board_form"  name="board_form" method="post" onkeydown="return captureReturnKey(event)" >

	<input type="hidden" id="num" name="num" value="<?=$num?>" >
	<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>" >
	<input type="hidden" id="steelitem" name="steelitem" >
	<input type="hidden" id="steelspec" name="steelspec" >
	<input type="hidden" id="steeltake" name="steeltake" >		
	
    <div class="container-fluid mt-2 mb-2"  >
    
    <div class="card">
       <div class="card-body ">
       <div class="row">
			<div class="col-sm-9">
				<div class="d-flex mb-5 mt-5 justify-content-center align-items-center fs-4">
					원자재 구매
				</div>
			</div>
			
	   <div class="col-sm-3">		
	<?php
				//var_dump($al_part);			

				$al_part=='지원파트';
               if($e_confirm ==='' || $e_confirm === null) 
			   {
					$formattedDate = date("m/d", strtotime($registdate)); // 월/일 형식으로 변환
					// echo $formattedDate; // 출력
					
					if($al_part=='제조파트')
					{
						$approvals = array(
							array("name" => "공장장 이경묵", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
					if($al_part=='지원파트')
					{
						$approvals = array(
							array("name" => "이사 최장중", "date" =>  $formattedDate),
							array("name" => "대표 소현철", "date" =>  $formattedDate),
							// 더 많은 결재권자가 있을 수 있음...
						);	
					}
			   }
			   else
			   {			
					$approver_ids = explode('!', $e_confirm_id);
					$approver_details = explode('!', $e_confirm);

					$approvals = array();

					foreach($approver_ids as $index => $id) {
						if (isset($approver_details[$index])) {
							// Use regex to match the pattern (name title date time)
							// The pattern looks for any character until it hits a series of digits that resemble a date followed by a time
							preg_match("/^(.+ \d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})$/", $approver_details[$index], $matches);

							// Ensure that the full pattern and the two capturing groups are present
							if (count($matches) === 3) {
								$nameWithTitle = $matches[1]; // This is the name and title
								$time = $matches[2]; // This is the time
								$date = substr($nameWithTitle, -10); // Extract date from the end of the 'nameWithTitle' string
								$nameWithTitle = trim(str_replace($date, '', $nameWithTitle)); // Remove the date from the 'nameWithTitle' to get just the name and title
								$formattedDate = date("m/d H:i:s", strtotime("$date $time")); // Combining date and time

								$approvals[] = array("name" => $nameWithTitle, "date" => $formattedDate);
							}
						}
					}


					// // Now $approvals contains the necessary details
					// foreach ($approvals as $approval) {
						// echo "Approver: " . $approval['name'] . ", Date: " . $approval['date'] . "<br>";
					// }
			   }					
				
				if($status === 'end' and ($e_confirm !=='' && $e_confirm !== null) )
				  {
				?>				
				
					<div class="container mb-2">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th colspan="<?php echo count($approvals); ?>" class="text-center fs-5">결재</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($approvals as $approval) { ?>
										<td class="text-center fs-5" style="height: 60px;"><?php echo $approval["name"]; ?></td>
									<?php } ?>
								</tr>
								<tr>
									<?php foreach ($approvals as $approval) { ?>
										<td class="text-center"><?php echo $approval["date"]; ?></td>
									<?php } ?>
								</tr>
							</tbody>
						</table>
					</div>			  
				  
				  <?  } 
						 else
						 {
				   ?>
							<div class="container mb-2">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th colspan="<?php echo count($approvals); ?>" class="text-center fs-5">결재 진행 전</th>
								</tr>
							</thead>
							<tbody>
								<tr>								
								</tr>
							</tbody>
						</table>
					</div>	
			  <?  }   ?>
				
				
		 </div> 			
	 </div> 
 

       <div class="row">
		   <div class="col-sm-9">		   
				<div class="d-flex  mb-1 justify-content-start"> 					
						
						<button type="button"   class="btn btn-dark btn-sm" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>'" > <i class="bi bi-pencil-square"></i>  수정 </button> &nbsp;
						<button type="button"   class="btn btn-danger btn-sm" onclick="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>')" > <i class="bi bi-trash"></i>  삭제 </button>	 &nbsp;
						<button type="button"   class="btn btn-dark btn-sm" onclick="location.href='write_form.php'" > <i class="bi bi-pencil"></i>  신규 </button>		&nbsp;										
						<button type="button"   class="btn btn-primary btn-sm" onclick="location.href='write_form.php?mode=copy&num=<?=$num?>'" > <i class="bi bi-copy"></i> 복사 </button>	&nbsp;
						
						<? 	 if($inventory!=='이관')  { ?>
						 <button type="button"   id="inputdoneAndMove"  class="btn btn-success btn-sm"> <i class="bi bi-kanban"></i> 당일입고 & 원자재등록 </button>	&nbsp;
						<? } ?>			 
						<? 	 if($which=='3')  { ?>
							<button type="button"  id="inputSheetBtn"  class="btn btn-dark btn-sm"> <i class="bi bi-chat-square-dots-fill"></i> 수입 검사서  </button>  &nbsp; 
							<button type="button"  id="PrintinspectionBtn"  class="btn btn-dark btn-sm"> <i class="bi bi-chat-square-dots-fill"></i> 자체 검사서 </button>
								<? } ?>
				 </div> 			
			 </div> 			
	   <div class="col-sm-3">	
				<div class="d-flex  mb-1 justify-content-end"> 	
				   <button class="btn btn-secondary btn-sm" id="closeBtn" > <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;					
				</div> 			
		 </div> 			
	 </div> 
 


 <div class="row mt-3 mb-3">
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-7 " >';
	  else
		  echo '<div class="col-lg-12" >';
	  ?>
	 <div class="card" >
		<div class="card-body mt-2 mb-2" >
	 
      <table class="table table-bordered">        
        <tr>
		<td class="text-center fw-bold " >
            <label>진행상태</label>
          </td>
          <td>
				<?php	 		  
			 $aryreg=array();
			 if($which=='') $which='2';
			 switch ($which) {
				case   "1"             : $aryreg[0] = "checked" ; break;
				case   "2"             :$aryreg[1] =  "checked" ; break;
				case   "3"             :$aryreg[2] =  "checked" ; break;
				default: break;
			}		 
		   ?>		  			  
				   <span class="text-primary">  요청  </span> &nbsp;      <input  type="radio" <?=$aryreg[0]?> name=which value="1"> &nbsp;&nbsp;
					&nbsp;   <span class="text-danger">  발주보냄  </span> &nbsp;            <input  type="radio" <?=$aryreg[1]?>  name=which value="2">   &nbsp;&nbsp; 
					&nbsp;  <span class="text-dark">  입고완료  </span> &nbsp;           <input  type="radio" <?=$aryreg[2]?>  name=which value="3">   &nbsp;&nbsp;			
          </td>
        </tr>
        <tr>
		<td class="text-center fw-bold " >
            <label class="text-danger" >재고이관</label>
          </td>
          <td>
			 <input type="text" id="inventory" name="inventory" class="text-start text-danger" value="<?=$inventory?>" readonly placeholder="재고이관시 표시됨" >
          </td>
        </tr>		
        <tr>
            <td class="text-center fw-bold " >
            <label  for="outdate">접수일</label>
          </td>
          <td>
		     <input    type="date" id="outdate" name="outdate" value="<?=$outdate?>" >
			 
          </td>
        </tr>
        <tr>
           <td class="text-center fw-bold " >
            <label for="requestdate">납기(필요일)  </label>
          </td>
          <td>             
			<input type="date" id="requestdate" name="requestdate" value="<?=$requestdate?>"  > 
          </td>
        </tr>
        <tr>
           <td class="text-center fw-bold " >
            <label for="indate">완료</label>
          </td>
          <td>
            <input    type="date" id="indate" name="indate" value="<?=$indate?>"  >&nbsp;      							
          </td>
        </tr>
        <tr>
            <td class="text-center fw-bold " >
            <label for="outworkplace">현장명</label>
          </td>
          <td>
            
			<input type="text"  id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$outworkplace?>" size="50" placeholder="현장명" autocomplete="off"> 	 &nbsp;			
	 
			 <div id="displaysearch" style="display:none"> 	 
			 </div>
          </td>
        </tr>
        <tr>
            <td class="text-center fw-bold " >
            <label for="model">모델</label>
          </td>
          <td>
            <input type="text" id="model" name="model" value="<?=$model?>" size="20" placeholder="모델명" />	 &nbsp;
          </td>
        </tr>
        <tr>		  
          <td colspan="2" class="text-danger text-center fw-bold">
             [주의] 미래기업 구매 자재는 '사급자재'가 아님. 업체 제공 자재만 '사급'으로 구분.
          </td>
        </tr>
        <tr>
            <td class="text-center fw-bold " >
            <label for="company">사급업체</label>
          </td>
          <td>				
			<input name="company" id="company" value="<?=$company?>">
			  
			
          </td>
        </tr>
        <tr>
           <td class="text-center fw-bold " >
            <label for="supplier">공급(제조사)</label>
          </td>
          <td>				
			<input name="supplier" id="supplier" value="<?=$supplier?>">
          </td>
        </tr>		
      </table>
		</div>
		</div>
    </div>
  
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-5" >';
	  else
		  echo '<div class="col-lg-12" >';
	  ?>
	  <div class="card" >
		<div class="card-body mt-2 mb-2" >
      <table class="table table-bordered">
        <tr>
            <td class="text-center fw-bold " >
            <label for="steel_item">종류</label>
          </td>
          <td>
			<input name="steel_item" id="steel_item" class="form-control"  value="<?=$steel_item?>">			   
          </td>
        </tr>
        <tr>
            <td class="text-center fw-bold " >
            <label for="spec">규격</label>
          </td>
          <td>
           <input name="spec" id="spec"  class="form-control"   value="<?=$spec?>">	            	
          </td>
        </tr>     
        <tr>
            <td class="text-center fw-bold " >
            <label for="steelnum">수량</label>
          </td>
          <td>
            <input type="text" id="steelnum" name="steelnum" class="form-control text-center" style="width:150px;"  value="<?=$steelnum?>"placeholder="수량" autocomplete="off">
          </td>
        </tr>	
        <tr>
            <td class="text-center fw-bold " >
            <label for="suppliercost">공급가액(경리부)</label>
          </td>
          <td>
            <input type="text" id="suppliercost" name="suppliercost"  class="form-control text-center" style="width:150px;" value="<?=$suppliercost?>" placeholder="명세표 경리부 입력" autocomplete="off">
          </td>
        </tr>		
        <tr>
            <td class="text-center fw-bold " >
            <label for="request_comment">비고</label>
          </td>
          <td>
            <textarea class="form-control" rows="4" id="request_comment" name="request_comment" placeholder="비고내용"><?=$request_comment?></textarea>
          </td>
        </tr>		
        <tr>
		    <td colspan="2" class="text-center fw-bold " >
					등록 : <?=$first_writer?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					  <?php
						      $update_log_extract = substr($update_log, 0, 31);  // 이미래....
					  ?>
					<br> 최종수정 : <?=$update_log_extract?> &nbsp;&nbsp;&nbsp;					
					<button type="button" class="btn btn-outline-dark btn-sm" id="showlogBtn"   >								
						Log 기록
					</button>	
          </td> 
        </tr>
      </table>
	  
    </div>
    </div>
    </div>
  </div>	
	
		</div>
  </form>
  	
 </div>

  

<script>

$(document).ready(function(){	

	$('#closeBtn').click(function() {
		window.close(); // 현재 창 닫기
	});


    // Select input, textarea, and radio elements
    $("input, textarea, input[type='radio']").each(function() {

        $(this).prop('readonly', true);

        $(this).css('background-color', '#f5f5f5');
    });

			// Log 파일보기
		$("#showlogBtn").click( function() {     	
		    var num = '<?php echo $num; ?>' 
			// table 이름을 넣어야 함
		    var workitem =  'eworks' ;
			// 버튼 비활성화
			var btn = $(this);						
			    popupCenter("../Showlog.php?num=" + num + "&workitem=" + workitem , '로그기록 보기', 500, 500);									 
			btn.prop('disabled', false);					 					 
		});		
	
	
    // 모달창 닫기 버튼
	$("#closeModalBtn").click(function(){ 	
		$('#myModal').modal('hide');
	});		
    // 수입검사서 버튼
	$("#inputSheetBtn").click(function(){ 
	    var num = <?php echo $num; ?> ; 
		var link ;
		link = 'http://8440.co.kr/request/input_inspection.php?num=' + num;
        window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1600,height=900");
	});		
    // 검사서 인쇄 버튼
	$("#PrintinspectionBtn").click(function(){ 
	    var num = <?php echo $num; ?> ; 
		var link ;
		link = 'http://8440.co.kr/request/inspection.php?num=' + num;
        window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1600,height=900");
	});			
			
   // 당일입고처리 및 DB이관
	$("#inputdoneAndMove").click(function(){   	
		var num = <?php echo $num; ?> ; 
		  $('#num').val(num);
		   
         // 자재 이관시 steelsource를 갱신해줘야 재고파악할때 리스트가 나타난다.
		 // 이관시 반드시 처리하자.
			 
			$.ajax({
				url: "../request/update_steelsource.php",  // request의 함수 사용하기
				type: "post",        
				data: $("#board_form").serialize(),
				dataType:"json",
				success : function( data ){
					console.log( data);	
					$.ajax({
						url: "movetosteel.php",
						type: "post",		
						data: $("#board_form").serialize(),
						dataType:"json",
						success : function( data ){
							console.log( data);	
							Toastify({
							text: "입고처리 및 이관 완료 ",
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
							
						},
						error : function( jqxhr , status , error ){
							console.log( jqxhr , status , error );
						} 			      		
					   });	// end of ajax				   			
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				}                     
			}); // end of ajax		 		
			   
		 });	// end of button click
	
});

// 현재날짜 구하기
function getCurrentDate()
{
	var date = new Date();
	var year = date.getFullYear().toString();

	var month = date.getMonth() + 1;
	month = month < 10 ? '0' + month.toString() : month.toString();

	var day = date.getDate();
	day = day < 10 ? '0' + day.toString() : day.toString();

   // return year + month + day ;
   return year + '-'+ month + '-'+ day ;
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


function del(href) {    
    var user_id  = '<?php echo  $user_id ; ?>' ;
    var author_id  = '<?php echo  $author_id ; ?>' ;
    var admin  = '<?php echo  $admin ; ?>' ;
	if( user_id !== author_id && admin !== '1' )
	{
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
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
				$.ajax({
					url:'delete.php',
					type:'post',
					data: $("#board_form").serialize(),
					dataType: 'json',
					}).done(function(data){		
						Toastify({
							text: "파일 삭제완료 ",
							duration: 2000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								// 부모 창에 restorePageNumber 함수가 있는지 확인
								if (typeof window.opener.restorePageNumber === 'function') {
									window.opener.restorePageNumber(); // 함수가 있으면 실행
								}
								window.opener.location.reload(); // 부모 창 새로고침
							}							
							 $('#closeBtn').click();
						}, 1000);
			
					  
					});
            }
        });
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
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트
    }
}

function exe_search()
{
      var postData = changeUri(document.getElementById("outworkplace").value);
      var sendData = $(":input:radio[name=root]:checked").val();

      $("#displaysearch").show();
	 if(sendData=='주일')
         $("#displaysearch").load("./search.php?mode=search&search=" + postData);
	 if(sendData=='경동') 
         $("#displaysearch").load("./searchkd.php?mode=search&search=" + postData);	  
}

function send_alert() {   // 알림을 서버에 저장
 
var tmp; 				
		
	tmp="../save_alert.php?ma_alert=1";	
		
    $("#vacancy").load(tmp);      
	
    alertify.alert('발주서 전송 알림창', '<h1> 발주서가 전송되었습니다. </h1>'); 	

 }      



</script> 
	</body>
 </html>
