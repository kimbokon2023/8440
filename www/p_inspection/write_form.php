<?php

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

  // 첫 화면 표시 문구
 $title_message = '출하 검사서';     
   
?>
   
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

 
<title> <?=$title_message?> </title>  
 
</head> 

<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/modal.php"; ?>
 
<?php

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
	          header("Location:https://8440.co.kr/login/login_form.php"); 
         exit;
   } 
   

isset($_REQUEST["id"])  ? $id=$_REQUEST["id"] :   $id=''; 
isset($_REQUEST["fileorimage"])  ? $fileorimage=$_REQUEST["fileorimage"] :   $fileorimage=''; // file or image
isset($_REQUEST["item"])  ? $item=$_REQUEST["item"] :   $item=''; 
isset($_REQUEST["upfilename"])  ? $upfilename=$_REQUEST["upfilename"] :   $upfilename=''; 
isset($_REQUEST["tablename"])  ? $tablename=$_REQUEST["tablename"] :  $tablename=''; 
isset($_REQUEST["savetitle"])  ? $savetitle=$_REQUEST["savetitle"] :  $savetitle='';   // log기록 저장 타이틀

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
    
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

          
  if ($mode=="modify" || $mode=='view' ){
    try{
      $sql = "select * from mirae8440." . $tablename . " where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
	  
	  include '_row.php';
	  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }

// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num; 
   
$todate=date("Y-m-d");   // 현재일자 변수지정   

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$img_arr=array();

$questionstep_arr=array();
$check_arr=array();
		
for($i=0;$i<10;$i++)	
{	
     $checktmp = 'check' . (string)($i); 
     array_push($check_arr, $$checktmp );	
     array_push($img_arr, 'https://8440.co.kr/p_inspection/img/' . ($i+1) . '.jpg' );	
	 
}		

if(!is_string_valid($writer))
	$writer = $user_name;

if(!NullCheckDate($regist_day))
	$regist_day = $todate;

// var_dump($regist_day );
// var_dump($check0 );
// var_dump($check_arr );	 
  
?>

<form  id="board_form" name="board_form" method="post"  enctype="multipart/form-data"> 
  
<!-- 전달함수 설정 input hidden -->
<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
<input type="hidden" id="page" name="page" value="<?=$page?>" >			  								
<input type="hidden" id="parentID" name="parentID" value="<?=$parentID?>" >			  								
<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  								
<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentID key값으로 사용 -->				
<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" > 	

<div class="container">  
	<div class="card mt-2 mb-4">  
	<div class="card-body">   
 <div class="d-flex mt-3 mb-1 justify-content-center">  
  <h3>  <?=$title_message?> </h3>  </div>	  
  	  
	 <div class="row d-flex mt-3 mb-1 justify-content-center">  
	<table class="table table-bordered" style="width:80%;">
		<tbody>       
			<tr>
				 <td class="text-center align-middle"> 검사자 </td>
				 <td class="text-center align-middle"> <input type="text" id="writer" class="form-control" name="writer" value="<?=$writer?>"> </td>
				 <td class="text-center align-middle"> 검사일 </td>
				 <td class="text-center align-middle"> <input type="date" id="regist_day" class="form-control" name="regist_day" value="<?=$regist_day?>"> </td>                   
				 <td class="text-center align-middle"> 
						<div class="d-flex align-items-center">  
							<span id="allcheck"> 10개 체크리스트 점검 여부 &nbsp; </span> <input id="done_check" class="form-control" name="done_check" type="text" style="width:35px;" value="<?=$done_check?>">&nbsp;    
						</div>
					 </td>
				 <td class="text-center align-middle"> <span id="check_complete" style="font-size:12px;color:blue; display:none;"> 점검완료 </span></td>                   
				 <td rowspan="2" class="text-center align-middle"> 
					<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기 </button>
					<button type="button" id="saveBtn" class="btn btn-dark btn-sm"> <i class="bi bi-floppy-fill"></i> 저장 </button>&nbsp;                                        					
				 
				 </td>                   
			</tr>
			<tr>
				 <td class="text-center align-middle"> 현장명 </td>
				 <td colspan="5" class="text-center align-middle"> 	
				 <div class="d-flex justify-content-start">  
					 <input id="subject" class="form-control text-start" name="subject" type="text" onkeypress="enterCheck(event);" style="width:400px;" value="<?=$subject?>"> 				
					 <button type="button" class="btn btn-dark btn-sm mx-1" onclick="Choice_search();"> <i class="bi bi-search"></i> jamb  </button> 				 
				 </div>	 
				 </td>
			</tr>
		</tbody>
	</table>
    </div>
		


    <div class="row d-flex mt-3 mb-1 justify-content-center">  
	<?php for ($i = 0; $i < 10; $i++): ?>
		<?php $checktmp = 'check' . (string)($i); ?>		
		<div class="card mt-1 mb-1 justify-content-center">  
		<div class="card-body">  
			<div class="d-flex justify-content-center">  
			<table class="table table-bordered " style="width:70%;">
				<tbody>       
					<tr>
					  <td class="text-center align-middle"  style="width:40%;"> 
						<img id="ckname<?= $i ?>" src="<?= $img_arr[$i] ?>" style="width:70%;">
					  </td>
					  <td class="text-center align-middle"  style="width:30%;">
							<table class="table table-bordered" >
								<tbody>       
									<tr>
										 <td class="text-center align-middle"> <input type="date" id="check<?= $i ?>" name="check<?= $i ?>" class="form-control"> </td>
										 <td class="text-center align-middle"> <span id="writer_text<?= $i ?>"  class="form-control" style="display:none;">점검자 : <?= $writer ?></span> </td>
										 <td class="text-center align-middle"> <button type="button" id="ckbtn<?= $i ?>" class="btn btn-dark  btn-sm"> 미점검 </button> </td>					  
					  					</tr>
								</tbody>
							</table>
					  </td>	
					</tr>
				</tbody>
			</table>
			</div>
		</div>
		</div>
	<?php endfor; ?>	
	 </div>		
		<!-- footer -->
	    <div class="card-footer border-0 px-5 py-4"
            style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <h2 class="d-flex align-items-center justify-content-center text-white mb-0"> 
			안전을 최우선으로 생각하는 미래기업
			</h2>
			  <h3 class="d-flex align-items-center justify-content-center text-center text-white mb-0"> 			
			  고객만족 품질경영</h3>			  
		</div>	
		</div>	
		</div>	
		</div>	
	</form> 
 </body>
 </html>
 
<script> 


$(document).ready(function(){	

	var check_arr = <?php echo json_encode($check_arr); ?>;

	for (var i = 0; i < 10; i++) {
		if (check_arr[i] !== '' && check_arr[i] !== '0000-00-00') {
			$('#check' + i).val(check_arr[i]);
			$('#writer_text' + i).show();
			$('#ckbtn' + i).text('점검');
		}
	}

 // check0부터 check9까지의 버튼에 클릭 이벤트를 추가합니다.
    for (let i = 0; i < 10; i++) {
      $('#ckbtn' + i).click(function() {
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
        const day = ('0' + currentDate.getDate()).slice(-2);
        const formattedDate = `${year}-${month}-${day}`;

        const $checkInput = $('#check' + i);
        const $button = $(this);
        const isChecked = $checkInput.val() !== '';

        if (isChecked) {
          // 이미 점검된 상태일 경우, 날짜를 지우고 미점검 상태로 되돌립니다.
          $checkInput.val('');
          $button.text('미점검');
          // 점검자를 숨깁니다.
          $('#writer_text' + i).hide();
        } else {
          // 미점검 상태일 경우, 현재 날짜를 설정하고 점검 상태로 바꿉니다.
          $checkInput.val(formattedDate);
          $button.text('점검');
          // 점검자를 표시합니다.
          $('#writer_text' + i).show();
        }
      });
    }

            // 화면 로딩 후 체크된 날짜의 개수를 업데이트합니다.
            updateDoneCheck();

            // 날짜가 선택되면 updateDoneCheck 함수를 호출합니다.
            $('input[type="date"]').on('change', function() {
                updateDoneCheck();
            });
	 
            // 점검/미점검 버튼을 클릭하면 updateDoneCheck 함수를 호출합니다.
            $('.btn').on('click', function() {
                updateDoneCheck();
            });	 
	 
				 
		$("#closeModalBtn").click(function(){ 
			$('#myModal').modal('hide');
		}); 	 

		// 하단복사 버튼
		$("#closeBtn1").click(function(){ 
		   $("#closeBtn").click();
		})
			
		$("#closeBtn").click(function(){    // 저장하고 창닫기	
			opener.location.reload();	
			self.close();
		});	

					
		// 자료의 삽입/수정하는 모듈 
		$("#saveBtn").click(function(){    // 저장하고 창닫기	 
				   
			console.log($("#mode").val());    
			
		// item을 다중으로 입력하기 위한 루틴
			var data2 = $('#board_form').serializeArray();
			// var data3 = $('#board_form').serialize();
			
			// var arr = {};
				// Object.keys(data2).forEach(d => {    
					// arr[data2[d]] = d;
				// });
				// console.log(arr);

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
				url: "./insert.php",
				type: "post",		
				data: data,			
				// dataType:"text",  // text형태로 보냄
				success : function(data){
					
						console.log(data);
						
						$('#num').val(data["num"]); 

						// opener.location.reload();
						// window.close();	
						setTimeout(function() {
							$('#myModal').modal('hide');  
							}, 1000);
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
							} 			      		
			   });		

				
		 });
 
  // 텍스트 문자를 클릭하면 전체 체크하도록 만든다.
   $("#allcheck").click(function() {
    for (var i = 0; i < 10; i++) {
      $("#check" + i).val(new Date().toISOString().substring(0, 10));
      $("#ckbtn" + i).text("점검완료");
      $("#writer_text" + i).show();
    }
    $("#check_complete").show();
	 updateDoneCheck();
  });
 
	// 목록 
	$("#listBtn").click(function(){   			
		var page =  $("#page").val();
		location.href = 'list.php?page=' + page;							
	 });
 
 	
}); // end of ready document
 
function deleteLastchar(str)
// 마지막 문자 제거하는 함수
{
  return str = str.substr(0, str.length - 1);		
}

function enterCheck(e) {
  if (e.key === 'Enter') {
    search_jamb();  // 잠 현장검색  
  }
} 


function Choice_search() {
      search_jamb();  // 잠 현장검색	      
  }
  
  
function search_jamb()
{
	
	 var ua = window.navigator.userAgent;
      var postData; 	 
	  var text1= document.getElementById("subject").value;
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(text1);
            } else {
                postData = text1;
            }

      	popupCenter("./search.php?mode=search&search=" + postData , '잠현장 검색', 1200, 800);		
} 
  
  
function updateDoneCheck() {
	var doneCheck = 0;
	
	console.log('update');

	for (var i = 0; i < 10; i++) {
		var dateValue = $('#check' + i).val();
			console.log(dateValue);
		if (dateValue !== '' && dateValue !== '0000-00-00' && dateValue !== null) {
			doneCheck++;
		}
	}

	$('#done_check').val(doneCheck);
	if (doneCheck === 10) {
		$('#check_complete').show();
	} else {
		$('#check_complete').hide();
	}	
	
}
   
</script>

