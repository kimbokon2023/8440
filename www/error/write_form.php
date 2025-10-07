<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session.php'); ?>
<?php
$title_message = '부적합 보고 및 대책';
$admin_name= $_SESSION["name"];
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
<title> <?=$title_message?> </title> 
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'; ?>

<?php
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
 }    

$option = $_REQUEST["option"] ?? '' ;   // approval 결재인지 확인함
// 조부장님 권한 드림
// if($user_name == '조경임') {
// 	$option = 'approval';
// }

$mypart_arr = array();
array_push($mypart_arr, "제조파트","지원파트");
										  
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
require_once("../lib/mydb.php");
$pdo = db_connect();	

// select문의 배열 불러오기
$sql="select * from {$DB}.errortype "; 					

$errortype_arr=array();   
try{  
   $stmh = $pdo->query($sql);           
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {   
 			  array_push($errortype_arr, $row["errortype"]);
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

$errortype_arr = array_unique($errortype_arr);
sort($errortype_arr);

 try{
	  $sql = "select * from {$DB}.error where num = ? ";
	  $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.		
		 
	  include '_row.php';
	  
	  $imgurl = './img/' . $serverfilename ;
	  
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
 }
 // end of if	

// 지원부서 이름으로 불러오기 배열로 기본정보 불러옴
include "../annualleave/load_DB.php";
	
if($num=='')
{
// 신규데이터인 경우			
$reporter = $user_name;
$occur = date("Y-m-d",time()); 
$occurconfirm = date("Y-m-d",time()); 

$approve='결재상신';
$name= $_SESSION["name"];	

// DB에서 part 찾아서 넣어주기

	// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
	for($i=0;$i<count($basic_name_arr);$i++)  
	{	
	  if(trim($basic_name_arr[$i]) == trim($name))   
	  {
				$part = $basic_mypart_arr[$i];
				break;
	  }
	}

}
 
?>  

<form id="board_form" name="board_form" method="post"  enctype="multipart/form-data">
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="5" > 
	<input type="hidden" id=filedelete name=filedelete >
	<input type="hidden" id=filename name=filename value="<?=$filename?>"  >
	<input type="hidden" id=serverfilename name=serverfilename value="<?=$serverfilename?>"  >
	<input type="hidden" id="admin_name" name="admin_name" value="<?=$admin_name?>" >	

<div class="container h-30">
    <div class="row d-flex justify-content-center align-items-center h-30">	
        <div class="col-12 text-center">
			<div class="card align-middle" style="width:58rem; border-radius:20px;">
				<div class="card" style="padding:6px;margin:7px;">
					<h4 class="card-title text-danger text-center" > 품질불량(부적합) 원인분석 및 개선 대책 보고서 </h4>
				</div>	
				<div class="card-body text-center">
		  
				<div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
				성명	&nbsp;
				<input type="text" id="reporter" name="reporter"  class="form-control text-center" style="width:100px;" value="<?=$reporter?>" >
				&nbsp;&nbsp; 부서	&nbsp;
				
				<select name="part" id="part"  class="form-select form-select-sm w-auto text-start" >
				   <?php		               

				   for($i=0;$i<count($mypart_arr);$i++) {
						 if($part==$mypart_arr[$i])
									print "<option selected value='" . $mypart_arr[$i] . "'> " . $mypart_arr[$i] .   "</option>";
							 else   
									print "<option value='" . $mypart_arr[$i] . "'> " . $mypart_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 						
				
				&nbsp;&nbsp; 현장명&nbsp; 
                  <input type="text" name="place"  id="place"  class="form-control text-start" style="width:400px;" value="<?=$place?>" autofocus >
				<button  type="button" id="searchPlaceBtn"  class="btn btn-primary btn-sm ms-1" style="padding:3px;"> <i class="bi bi-search"></i>  </button> &nbsp;		
				</div>
				
				<div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
				&nbsp;&nbsp; 부적합 유형 &nbsp;&nbsp;
				<select name="errortype" id="errortype"  class="form-select form-select-sm w-auto text-start" >
				   <?php		 

				   for($i=0;$i<count($errortype_arr);$i++) {
						 if($errortype==$errortype_arr[$i])
									print "<option selected value='" . $errortype_arr[$i] . "'> " . $errortype_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $errortype_arr[$i] . "'> " . $errortype_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 					
				 &nbsp;
				<button  type="button" id="registerrortypeBtn"  class="btn btn-outline-primary btn-sm"  > 부적합유형 등록 </button> &nbsp;		
				&nbsp;&nbsp; 발생일 &nbsp; <input type="date" id="occur" name="occur"  value="<?=$occur?>"  class="form-control text-start" style="width:100px;">
				
				&nbsp;&nbsp; 불량확인일 &nbsp;
				<input type="date" id="occurconfirm"   name="occurconfirm"  value="<?=$occurconfirm?>"  class="form-control text-start" style="width:100px;" >
				</div>
				<div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
				<span style="color:gray">도면 저장위치  &nbsp;</span>				
				<input type="text"   id="saveurl"   class="form-control text-start w500px"  name="saveurl"   class="text-left" value="<?=$saveurl?>" placeholder="nas2dual 도면 저장위치" >				
				</div>
				<div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
				<span style="color:gray">&nbsp;&nbsp; 관련직원  &nbsp;</span>				
				<input type="text"   id="involved"   name="involved"    class="form-control text-start" style="width:300px;"  value="<?=$involved?>" placeholder="관련 직원" >								
				</div>
				<div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
				<span style="color:green">첨부파일(이미지) &nbsp; </span>	
                  <?php if($filename!=null) print $filename ?>	&nbsp;&nbsp;&nbsp;&nbsp;			   
                  <input  id="mainbefore"  name="mainBefore"  type="file" >					  
				</div>
				<div class="d-flex mt-3 justify-content-center">
					<span class="text-primary fs-5"> 불량 발생 원인 및 분석 </span>	
				</div>														
					<textarea type="text"   id="content"  class="form-control"  rows="4" name="content"  class="text-left" ><?=$content?></textarea>				
				<div class="d-flex mt-2 justify-content-center">
					<span class="text-danger fs-5"> 처리방안 및 개선사항 </span>	
				</div>
				<textarea type="text"   id="method"  class="form-control text-start"  rows="4" name="method"   ><?=$method?></textarea>				
				<h5 class="form-signin-heading text-success mt-2"> 원자재 및 자재 소요량 내역 </h5>													
				<textarea type="text"   id="steelrequirement"  class="form-control"  rows="2" name="steelrequirement"  class="text-left" ><?=$steelrequirement?></textarea>
				<input type="text" id="materialRaw" name="materialRaw"  class="form-control text-start"  value="<?=$materialRaw?>" readonly placeholder="자재내역 가져온 자료" >								
				<div class="table-responsive mt-3">
				  <table class="table table-bordered"  id="myTable">
					<thead class="table-secondary">
					  <tr class="middle-align">      
						<th class="text-center align-middle"> 자재 비용
							<button  type="button" class="btn btn-primary btn-sm ms-1 searchmaterialFee" style="padding:3px;"> <i class="bi bi-search"></i>  </button> 
						</th>
						<th class="text-center align-middle"> 운송 비용</th>
						<th class="text-center align-middle"> 시공 비용</th>
						<th class="text-center align-middle"> 기타 비용</th>
						<th class="text-center align-middle"> 비용 합계</th>
					  </tr>
					</thead>
					<tbody>        
					  <tr>
						<td><input type="text" id="materialFee" name="materialFee" class="form-control text-end number-input" value="<?= ($materialFee !== '') ? number_format($materialFee) : '' ?>"></td>
						<td><input type="text" id="deliveryFee" name="deliveryFee" class="form-control text-end number-input" value="<?= ($deliveryFee !== '') ? number_format($deliveryFee) : '' ?>"></td>
						<td><input type="text" id="workFee" name="workFee" class="form-control text-end number-input" value="<?= ($workFee !== '') ? number_format($workFee) : '' ?>"></td>
						<td><input type="text" id="etcFee" name="etcFee" class="form-control text-end number-input" value="<?= ($etcFee !== '') ? number_format($etcFee) : '' ?>"></td>
						<td><input type="text" id="totalFee" name="totalFee" class="form-control text-end" value="<?= ($totalFee !== '') ? number_format($totalFee) : '' ?>" readonly></td>

					  </tr>            
					</tbody>
				  </table>
				</div>						
	  <?php 
			if($filename!=null) {	
			  print " <span class='form-control'> ";
			  print '<br>';
			  print "<div class='imagediv' > ";
			  echo "<img class='before_work' src='". $imgurl  . "' style='width:100%;height:100%' >";			  			  
			  print "</div> </span> <br> ";
			  }
		?>				
		<h5 class="form-signin-heading mt-4 mb-1">결재 상태</h5>	
			 <div class="d-flex justify-content-center">				
				<input type="text"   id="approve" name="approve" style="width:150px;" class="form-control text-center bg-secondary text-white" readonly value="<?=$approve?>" >						
			 </div>
			 <div class="d-flex mt-4 mb-2 justify-content-center">		
             <?php if($option !=='approval' ) : ?>			 
				<button id="saveBtn" class="btn btn-dark btn-sm mx-2" type="button">
				<? if((int)$num>0) print ' <i class="bi bi-pencil-square"></i>  결재상신(수정)';  else print '<i class="bi bi-floppy-fill"></i>  결재상신(등록)'; ?></button>
				<?php else : ?>
				<button id="approvalBtn" class="btn btn-dark btn-sm mx-2" type="button">
				<?php  print '<i class="bi bi-floppy-fill"></i>   승인';  ?></button>
				<?php endif; ?>				
				<? if((intval($num)>0 or intval($level) === 1) and $option !=='approval' ) {  ?>				
				<button id="delBtn" class="btn btn-danger btn-sm" type="button"> <i class="bi bi-trash"></i>  삭제  </button>
				<? } ?>	
				<button type="button" id="closeBtn"  class="btn btn-dark btn-sm ms-3" > &times; 닫기 </button>				 
			 </div>
		  
			</div>
		</div>
		</div>			
	  </div>
	</div>	
	  </form>			  
<script>
// 전역 함수 정의
function formatNumberWithCommas(value) {
	value = value.replace(/,/g, ""); // 기존 콤마 제거
	if (!value) return "";
	return parseInt(value, 10).toLocaleString();
}

function getNumericValue(value) {
	return value.replace(/,/g, ""); // 콤마 제거 후 반환
}

function updateTotalFee() {
	let total = 0;
	const inputs = document.querySelectorAll(".number-input");
	inputs.forEach(input => {
		total += parseInt(getNumericValue(input.value)) || 0;
	});
	const totalFeeInput = document.getElementById("totalFee");
	if (totalFeeInput) {
		totalFeeInput.value = formatNumberWithCommas(total.toString());
	}
}

// 초기화 함수 (입력 필드에 이벤트 바인딩)
function initNumberInputFormatter() {
	const inputs = document.querySelectorAll(".number-input");

	inputs.forEach(input => {
		input.addEventListener("input", function () {
			this.value = formatNumberWithCommas(this.value);
			updateTotalFee();
		});
	});

	updateTotalFee(); // 페이지 로드 시 총합 업데이트
}

// 전역으로 등록
window.formatNumberWithCommas = formatNumberWithCommas;
window.getNumericValue = getNumericValue;
window.updateTotalFee = updateTotalFee;
window.initNumberInputFormatter = initNumberInputFormatter;

// 초기화 실행
document.addEventListener("DOMContentLoaded", function () {
	initNumberInputFormatter();
});

function displayPicture() {        // 첨부파일 형식으로 사진 불러오기
	$('#displayPicture').show();
	params = $("#num").val();	
	console.log($("#num").val());
	$.ajax({
		url:'loadpic.php?num='+params ,
		type:'post',
		data: $("mainFrm").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recnum = data["recnum"];		   
		   console.log(data);
		   $("#displayPicture").html('');
		   for(i=0;i<recnum;i++) {			   
			   $("#displayPicture").append("<img id=pic" + i + " src ='img/" + data["img_arr"][i] + "'> " );			   
         	   $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  data["img_arr"][i] + "')> 삭제 </button>&nbsp;");					   
		      }		   
			    $("#pInput").val('');			
    });	
}

function displayPictureLoad() {    // 이미 있는 파일 불러오기
	$('#displayPicture').show();
	var picNum = "<? echo $picNum; ?>"; 					
	var picData = '<?php echo json_encode($picData);?>' ;	
    for(i=0;i<picNum;i++) {
       $("#displayPicture").append("<img id=pic" + i + " src ='img/" + picData[i] + "'> " );			
	   $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + picData[i] + "')> 삭제 </button>&nbsp;");		
	   
	  }		   
		$("#pInput").val('');	
}
		
function delPic(delChoice)
{
if(delChoice=='before')
    $("#filedelete").val('before');
if(delChoice=='after')
    $("#filedelete").val('after');   
	document.getElementById('board_form').submit();	
}	

$(document).ready(function(){		
// 신청일 변경시 종료일도 변경함
$("#occur").change(function(){   
   $('#occurconfirm').val($("#occur").val());            
});	
	
// 에러타입등록 수정 삭제 
$("#registerrortypeBtn").click(function(){           
	href = '../registerrortype.php' ;				
	popupCenter(href, '부적합 유형 등록', 600, 600);
});			

$("#searchPlaceBtn").click(function(){    // 현장 검색
	const num = $("#num").val();
	href = 'search.php?num=' + num ;				
	popupCenter(href, '현장 검색', 600, 600);  
});	

$("#regpicBtn").click(function(){    // 사진등록
  const num = $("#num").val();
  window.open('reg_pic.php?num=' + num ,"사진등록","width=1200, height=700, top=0,left=0,scrollbars=no");	
});

$("#mainbefore").change(function(e) {
    //do whatever you want here
  	isfile = $("#filename").val();
	changefile = $("#mainbefore").val();
	
	if(changefile!='')
		$("#filename").val('');
	
  // $('#displaypic').show(); 	
  // $('#displaypic').load('pic_insert.php?file=' + fileData ); 
  
});
 
	 	  
delPicFn = function(divID, delChoice) {
	console.log(divID, delChoice);

	$.ajax({
		url:'delpic.php?picname=' + delChoice ,
		type:'post',
		data: $("mainFrm").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const picname = data["picname"];		   
		   console.log(data);
			$("#pic" + divID).remove();  // 그림요소 삭제
			$("#delPic" + divID).remove();  // 그림요소 삭제

		   $("#pInput").val('');			
        });		
	};			
	
var approve =  $('#approve').val();  	
// 처리완료인 경우는 수정하기 못하게 한다.

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
	
$("#closeBtn").click(function(){    // 저장하고 창닫기	    
	window.close();
});	
				
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var num = $("#num").val();  
	var part = $("#part").val();  
    var approve = $("#approve").val();  
    var user_name = $("#user_name").val(); 
	var reporter = $("#reporter").val();   
	   	   
// 결재상신이 아닌경우 수정안됨	
 if( (reporter==user_name && approve=='결재상신') || user_name=='김보곤'|| user_name=='소현철' || user_name=='조경임'  || user_name=='최장중'  ) {   
	   if(Number(num)>0) 
		   $("#mode").val('modify');     
		  else
		  {
			  $("#mode").val('insert');     
			if (user_name=='최장중') {
				$("#approve").val('1차결재'); 
			}			
		  }
		  
	console.log($("#mode").val());    

		// 폼데이터 전송시 사용함 Get form         
		var form = $('#board_form')[0];  	    
		// Create an FormData object          
		var data = new FormData(form); 
			
		$.ajax({
			enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 			
			url: "insert.php",
			type: "post",		
			data: data,
			// dataType:"text",  // text형태로 보냄
			success : function( data){
						console.log(data);				
						Toastify({
							text: "파일 저장완료 ",
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
								opener.location.reload();
							}		
						}, 1000);															
				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		
		} // end of if
		
		else
		  {
			  tmp='보고자만 결재상신 상태가 아닌 경우 수정이 가능합니다.';		
			  $('#alertmsg').html(tmp); 			  
				$('#myModal').modal('show');  
		  }		
 }); 
		
// 승인버튼			
$("#approvalBtn").click(function(){  
	var num = $("#num").val();  
	var part = $("#part").val();  
    var approve = $("#approve").val();  
    var user_name = $("#user_name").val(); 
	var reporter = $("#reporter").val();   	
    var admin_name = $("#admin_name").val();	
	var resultOK = 0;	
		
	if((admin_name == '소현철' || admin_name =='김보곤') && approve =='1차결재') {     
		$("#approve").val('처리완료');	
		resultOK = 1;
		} // end of if
		
	if((admin_name=='이경묵' || admin_name=='최장중' || admin_name=='김보곤') && approve=='결재상신') {     
		$("#approve").val('1차결재');	
		resultOK = 1;
		} // end of if	
		
    console.log('변경후 approve ' + approve);	
	
	if(resultOK == 1)	   {  // 결과가 성공하면 	
		   $("#mode").val('modify');    
			console.log($("#mode").val());    

			// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 

			tmp='파일을 저장중입니다. 잠시만 기다려주세요.';		
			$('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show'); 				
				
				$.ajax({
					enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
					processData: false,    
					contentType: false,      
					cache: false,           
					timeout: 600000, 			
					url: "insert.php",
					type: "post",		
					data: data,
					// dataType:"text",  // text형태로 보냄
					success : function( data){
						console.log(data);
						opener.location.reload();
						window.close();			
						
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
								} 			      		
				   });		
		} // end of if
		
			else
			  {
			  tmp='보고자만 결재가 가능합니다.';		
			  $('#alertmsg').html(tmp); 			  
				$('#myModal').modal('show');  
			  }
		
 }); 

	
$("#delBtn").click(function(){      // del
	var num = $("#num").val();    
	var reporter = $("#reporter").val();    
	var approve = $("#approve").val();  
	var admin = '<?php echo $admin; ?>';
	var user_name = $("#user_name").val();  
	
	if( (reporter==user_name && approve=='결재상신') || (admin=='1') || user_name=='김보곤') 
	 {  
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
						$("#mode").val('delete');  
						$.ajax({
							url: "insert.php",
							type: "post",		
							data: $("#board_form").serialize(),
							dataType:"text",  // text형태로 보냄
							success : function( data ){
								console.log(data);
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
										window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
										window.opener.location.reload(); // 부모 창 새로고침
										window.close();
									}							
									
								}, 1000);	
							},
							error : function( jqxhr , status , error ){
								console.log( jqxhr , status , error );
							} 			      		
						   });	
							

					}
				});		

		} // end of if
		else
		  {
			Swal.fire({
				title: '삭제불가',
				text: "작성자와 관리자만 삭제가능합니다.",
				icon: 'error',
				confirmButtonText: '확인'
			});
		  }
			
	 }); // end of function

	$(".searchmaterialFee").click(function(){      // del
		var option = 'parent';
		var materialRaw = $("#materialRaw").val();
		popupCenter('/cost/calamount.php?menu=no&option=' + option + '&materialRaw=' + materialRaw , '', 1000, 850); 
	}); // end of click
	
}); // end of ready document

</script>

</body>
</html>
