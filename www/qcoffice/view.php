<?php
session_start(); 
	$user_name= $_SESSION["name"];
	$user_id= $_SESSION["userid"];		
	
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 

      	
  // 첫 화면 표시 문구
 $title_message = '사무실 점검';     
   
?>
   
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
<title> <?=$title_message?> </title>   

<?php
require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";


$nowday=date("Y-m-d");   // 현재일자 변수지정   
try{  
	 $sql="select * from mirae8440.myarealist  where num=? ";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 
			 $checkdate = $row["checkdate"];		
			 $item = $row["item"];		
			 $term = $row["term"];		
			 $check1 = $row["check1"];		
			 $check2 = $row["check2"];		
			 $check3 = $row["check3"];		
			 $check4 = $row["check4"];		
			 $check5 = $row["check5"];		
			 $check6 = $row["check6"];		
			 $check7 = $row["check7"];		
			 $check8 = $row["check8"];		
			 $check9 = $row["check9"];		
			 $check10 = $row["check10"];		
			 $trouble = $row["trouble"];		
			 $fixdata = $row["fixdata"];	
			 $writer = $row["writer"];	
			 $writer2 = $row["writer2"];	
			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
// 작성자가 없을때는 작성자 생성  
if($writer==null)       
    $writer=$user_name;	
// print $writer;
// print $writer2;
// print $user_name;
  
$question = array();  
  
if(($item=='section1' || $item=='section2' ||  $item=='section3' ||  $item=='section4' ||  $item=='section5' ||  $item=='section6' ||  $item=='section7' )  && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "책상 위에 있는 먼지나 쓰레기를 청소했나요?");
array_push($question, "책상 서랍이나 선반에 있는 쓰레기를 비웠나요?");
array_push($question, "필요 없는 문구류나 소품들은 제거했나요?");

}  

if(($item=='section1' || $item=='section2' ||  $item=='section3' ||  $item=='section4' ||  $item=='section5' ||  $item=='section6' ||  $item=='section7' ) && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "책상 위에 있는 먼지나 쓰레기를 청소했나요?");
array_push($question, "책상 서랍이나 선반에 있는 쓰레기를 비웠나요?");
array_push($question, "필요 없는 문구류나 소품들은 제거했나요?");
}
      	
if(($item=='section1' || $item=='section2' ||  $item=='section3' ||  $item=='section4' ||  $item=='section5' ||  $item=='section6' ||  $item=='section7' ) && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "책상 위에 있는 먼지나 쓰레기를 청소했나요?");
array_push($question, "책상 서랍이나 선반에 있는 쓰레기를 비웠나요?");
array_push($question, "필요 없는 문구류나 소품들은 제거했나요?");
}
      	
if(($item=='section1' || $item=='section2' ||  $item=='section3' ||  $item=='section4' ||  $item=='section5' ||  $item=='section6' ||  $item=='section7' ) && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "책상 위에 있는 먼지나 쓰레기를 청소했나요?");
array_push($question, "책상 서랍이나 선반에 있는 쓰레기를 비웠나요?");
array_push($question, "필요 없는 문구류나 소품들은 제거했나요?");
}
      	
		
if($item=='comsection1' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "바닥 청소: 통로 바닥에 먼지와 쓰레기를 청소했어요?");
array_push($question, "문고리 청소: 문고리에 먼지와 오염물질을 청소했어요?");
array_push($question, "조명 청소: 통로에 설치된 조명 등에 먼지가 묻어있지는 않나요? 청소했어요?");

}  

if($item=='comsection1' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "바닥 청소: 통로 바닥에 먼지와 쓰레기를 청소했어요?");
array_push($question, "문고리 청소: 문고리에 먼지와 오염물질을 청소했어요?");
array_push($question, "조명 청소: 통로에 설치된 조명 등에 먼지가 묻어있지는 않나요? 청소했어요?");
}
      	
if($item=='comsection1' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "바닥 청소: 통로 바닥에 먼지와 쓰레기를 청소했어요?");
array_push($question, "문고리 청소: 문고리에 먼지와 오염물질을 청소했어요?");
array_push($question, "조명 청소: 통로에 설치된 조명 등에 먼지가 묻어있지는 않나요? 청소했어요?");
}
      	
if($item=='comsection1' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "바닥 청소: 통로 바닥에 먼지와 쓰레기를 청소했어요?");
array_push($question, "문고리 청소: 문고리에 먼지와 오염물질을 청소했어요?");
array_push($question, "조명 청소: 통로에 설치된 조명 등에 먼지가 묻어있지는 않나요? 청소했어요?");
}
      	
		
		
if($item=='comsection2' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "가구위 : 위에 먼지는 잘 제거했어요?");
array_push($question, "가구위에 쓰레기를 잘 치웠나요?");
array_push($question, "가구 주변의 먼지와 이물질을 잘 제거하고 치우셨나요?");

}  

if($item=='comsection2' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "가구위 : 위에 먼지는 잘 제거했어요?");
array_push($question, "가구위에 쓰레기를 잘 치웠나요?");
array_push($question, "가구 주변의 먼지와 이물질을 잘 제거하고 치우셨나요?");
}
      	
if($item=='comsection2' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "가구위 : 위에 먼지는 잘 제거했어요?");
array_push($question, "가구위에 쓰레기를 잘 치웠나요?");
array_push($question, "가구 주변의 먼지와 이물질을 잘 제거하고 치우셨나요?");
}
      	
if($item=='comsection2' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "가구위 : 위에 먼지는 잘 제거했어요?");
array_push($question, "가구위에 쓰레기를 잘 치웠나요?");
array_push($question, "가구 주변의 먼지와 이물질을 잘 제거하고 치우셨나요?");
}
      	
		
		
if($item=='comsection3' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "책상 청소: 책상 위에 있는 문구류와 컴퓨터, 전화기 등을 청소했어요?");
array_push($question, "창문 청소: 창문과 코튼, 블라인드 등의 유리류를 깨끗하게 닦았나요?");
array_push($question, "책장 정리: 필요한 문서와 문구류를 정리하고, 필요 없는 것은 제거했어요?");

}  

if($item=='comsection3' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "책상 청소: 책상 위에 있는 문구류와 컴퓨터, 전화기 등을 청소했어요?");
array_push($question, "창문 청소: 창문과 코튼, 블라인드 등의 유리류를 깨끗하게 닦았나요?");
array_push($question, "책장 정리: 필요한 문서와 문구류를 정리하고, 필요 없는 것은 제거했어요?");
}
      	
if($item=='comsection3' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "책상 청소: 책상 위에 있는 문구류와 컴퓨터, 전화기 등을 청소했어요?");
array_push($question, "창문 청소: 창문과 코튼, 블라인드 등의 유리류를 깨끗하게 닦았나요?");
array_push($question, "책장 정리: 필요한 문서와 문구류를 정리하고, 필요 없는 것은 제거했어요?");
}
      	
if($item=='comsection3' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "책상 청소: 책상 위에 있는 문구류와 컴퓨터, 전화기 등을 청소했어요?");
array_push($question, "창문 청소: 창문과 코튼, 블라인드 등의 유리류를 깨끗하게 닦았나요?");
array_push($question, "책장 정리: 필요한 문서와 문구류를 정리하고, 필요 없는 것은 제거했어요?");
}
      	
		

$questionNum = count($question);

switch ($item){
  case 'section1' :   
  case 'section2' :   
  case 'section3' :   
  case 'section4' :   
  case 'section5' :   
  case 'section6' :   
  case 'section7' :   
	$itemstr = '내책상';
	break;
  case 'comsection1' :   
	$itemstr = '사무실공용통로';
	break;
  case 'comsection2' :   
	$itemstr = '사무실 가구 및 집기류';
	break;
  case 'comsection3' :   
	$itemstr = '사장실내부';
	break;
}
					
   
   ?>
   

<body>


<form  id="board_form" name="board_form" method="post"  > 	

<input type="hidden" name="check1" id="check1" value="<?=$check1?>" > 
<input type="hidden" name="check2" id="check2" value="<?=$check2?>" > 
<input type="hidden" name="check3" id="check3" value="<?=$check3?>" > 
<input type="hidden" name="check4" id="check4" value="<?=$check4?>" > 
<input type="hidden" name="check5" id="check5" value="<?=$check5?>" > 
<input type="hidden" name="check6" id="check6" value="<?=$check6?>" > 
<input type="hidden" name="check7" id="check7" value="<?=$check7?>" > 
<input type="hidden" name="check8" id="check8" value="<?=$check8?>" > 
<input type="hidden" name="check9" id="check9" value="<?=$check9?>" > 
<input type="hidden" name="check10" id="check10" value="<?=$check10?>" > 
		

<div class="container-fluid">  
	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
		  특이사항이 저장되었습니다. <br> 
		   <br> 
		  귀하의 노고에 감사드립니다.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<div class="card mt-3">    		
	<div class="card-body">    		
		<div class="row gx-1 gx-lg-1 align-items-center">                      
				<div class="fs-4 mb-1" id="leftchar">
					  <label class="form-check-label text-primary" for="leftchar">
							 &nbsp;&nbsp; ' <?=$itemstr?> ' &nbsp;
					  </label>		
							 담당 (정) <?=$writer?> , (부) <?=$writer2?> &nbsp;&nbsp; 	&nbsp;&nbsp; 	
					  <button type="button" id="closeBtn" class="btn btn-dark btn-sm"> <ion-icon name="close-outline"> </ion-icon> 창닫기 </button>
						<?php 
							if($user_name=='김보곤')
								print '<button type="button" id="passBtn" class="btn btn-primary btn-sm"  >  <ion-icon name="color-wand-outline"></ion-icon> pass </button>';
						?>						 								
				</div>			
				</div>    
		</div>   
</div>					
		
	
<div class="card mt-3">    		
	<div class="card-body">   	
<!-- 체크리스트 구현 section-->
<section class="h-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-12">
        <div class="card" style="border-radius: 10px;">		  
          <div class="card-header px-0 py-0 text-center">
            <h3 class="text-muted mb-3"> <?=$term?> 
			<span style="color: #a8729a;">점검</span>!</h3>
          </div>
		  <!-- 대분류 시작 -->	
         <?
            for ($i=0;$i<count($question);$i++)
			{
				$checktmp = 'check' . (string)($i+1) ;
           ?>				
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #a8729a;"> 
			 <?=$question[$i]?>
			&nbsp;&nbsp;&nbsp;&nbsp; 	<span id="ckname<?=$i+1?>" style="color:gray;">
			<? if($$checktmp !=null)
				print $$checktmp . ", (점검자) " . $writer ;
			  else  { ?>
			  </span>
			<button type="button" id="ckbtn<?=$i+1?>" class="btn btn-secondary btn-sm  check-btn"  onclick="checklist('<?=$num?>','<?=$i+1?>');"> 점검완료 </button>				  
			  <? } ?>
			  </p>              
            </div>
			</div>
			<? } ?>
			
		  <!-- 대분류 끝 -->		  
		  
		  <!-- 대분류 시작 -->
		  <div class="card-header px-0 py-0 text-center">
            <h3 class="text-muted mb-3"> '점검 후 특이사항' 기록</h3>
          </div>
          <div class="card-body p-4">		   
            <div class="d-flex justify-content-center align-items-center mb-4">      
			   <textarea class="form-control" style="width:500px;" rows="3" id="trouble" name="trouble" placeholder="특이사항 있을시 기록" ><?=$trouble?></textarea>			   
			    &nbsp; 
			   <p class="fw-normal mb-0" style="color: #a8729a;"> 
					<button type="button" class="btn btn-secondary btn-sm"  onclick="write_memo('<?=$num?>');">  기록 저장 </button>
			  </p> 
           </div>			  
		  <!-- 대분류 끝 -->
		  
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
  </div>
</section>
      </div>
    </div>
  </div>		 
</form>
	<!-- ajax 전송으로 DB 수정 -->
	<? include "../formload.php"; ?>	
	
	<div id=dummy > </div>
		
		
</body>
	
</html>

<script>

$(document).ready(function(){
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});		
	
	$("#closeBtn").click(function(){ 
	   opener.location.reload();
	   window.close();		
	});		
		
	$("#passBtn").click(function(){ 
		document.querySelectorAll('.check-btn').forEach(function(button) {
			button.click();
		});	
	});		
		
// 일시로 만든 함수 각 장비의 체크리스트 자료 db 생성을 위해서		
	$("#doBtn").click(function(){ 
	//  $("#dummy").load("../dumproDB.php");
		
	// console.log(getDayOfWeek('2022-03-26'));
	// const startDate = '2022-03-26';
	// const lastDate = '2022-10-31';
	// var datestr = getDatesStartToLast(startDate, lastDate);
	// // console.log(datestr);
    // for(i=0;i<datestr.length;i++)
	// {
		// if(getDayOfWeek(datestr[i])=='금')
		 // {
        // // DB 추가	
		       // $("#table").val('myarealist');
		       // //$("#command").val('update');
		        // $("#command").val('insert');
		       // // $("#command").val('delete');  // insert, delete, update
		       // $("#field").val('checkdate');
		       // $("#strtmp").val(datestr[i]);
		       // // $("#recnum").val(num);
		       // // $("#arr").val('free');
		   
				   // // data저장을 위한 ajax처리구문
					// $.ajax({
						// url: "../proDB.php",
						// type: "post",		
						// data: $("#Form1").serialize(),
						// dataType:"json",
						// success : function( data ){
							// console.log( data);
						// },
						// error : function( jqxhr , status , error ){
							// console.log( jqxhr , status , error );
						// } 			      		
					   // });	
		     	// }  // end of if			   
			// } // end of for
			
			
			
		});		

	
// 브라우저 강제로 닫을때 이벤트
$(window).bind("beforeunload", function (e){	opener.location.reload();  });
	
	// // order 버튼 클릭시
// $("#orderBtn").click(function(){  

});

// 점검후 특이사항 기록하기
function write_memo(num)
{ 
        
        // DB 수정 		
		       $("#table").val('myarealist');
		       $("#command").val('update');
		       // $("#command").val('insert');
		       // $("#command").val('delete');  // insert, delete, update
		       $("#field").val('trouble');			   
		       $("#strtmp").val($("#trouble").val());
		       $("#recnum").val(num);
		       // $("#arr").val('free');
		   
		   // data저장을 위한 ajax처리구문
			$.ajax({
				url: "../proDB.php",
				type: "post",		
				data: $("#Form1").serialize(),
				dataType:"json",
				success : function( data ){
					console.log( data);
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				} 			      		
			   });		

		  $('#myModal').modal('show'); 			   
}


function checklist(num, whichone)
 { 
         var writer = '<?php echo $writer; ?>' ;
         var writer2 = '<?php echo $writer2;?>';
         var user_name = '<?php echo $user_name; ?>';
         var question = '<?php echo $questionNum; ?>';  
		
		console.log(writer);
		console.log(user_name); 
		console.log(question);
		
  if(writer == user_name ||  writer2 == user_name || user_name ==='김보곤' ) // 로그인 이름과 같을때는 기록한다.
	{
        // DB 수정 		
		   $("#table").val('myarealist');
		   $("#command").val('update');
		   // $("#command").val('insert');
		   // $("#command").val('delete');  // insert, delete, update
		   $("#field").val('check'+ whichone);			   
		   $("#strtmp").val(getToday());
		   $("#recnum").val(num);
		   $("#arr").val('free');
		   
		   // check값 form의 변수에 넣어주기
		   $('#check'+ whichone).val(getToday());			   
		   
		   // data저장을 위한 ajax처리구문
			$.ajax({
				url: "../proDB.php",
				type: "post",		
				data: $("#Form1").serialize(),
				dataType:"json",
				success : function( data ){
					console.log( data);
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				} 			      		
			   });		
			   
// 각 주간점검/1개월 점검등 문항을 전부 check했을 경우 완료 done 처리하기			   
// 조건 문항수에 맞는 check가 되었는지 확인한다
// 10개 문항을 기준으로 검색해서 처리한다.
   var sum = 0;
   for (i=1; i<=10 ; i++ )
   { 
      if($('#check'+ i).val() != '' )
	        sum += 1;
   }
   console.log('질문수 '  + question);
   console.log('답변수 '  + sum);
   if(question == sum)
   {
	    // 체크문항과 같으면 DB 완료로 수정하기
		   $("#table").val('myarealist');
		   $("#command").val('update');
		   // $("#command").val('insert');
		   // $("#command").val('delete');  // insert, delete, update
		   $("#field").val('done');			   
		   $("#strtmp").val('1');
		   $("#recnum").val(num);
		   $("#arr").val('free');
		   		   
		   // data저장을 위한 ajax처리구문
			$.ajax({
				url: "../proDB.php",
				type: "post",		
				data: $("#Form1").serialize(),
				dataType:"json",
				success : function( data ){
					console.log( data);
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				} 			      		
			   });		   
      }
	   	   
			   // 화면 변경하기 
			  $("#ckname" + whichone).html(getToday() + ' ' + '(작성자) '+ user_name); 
			  // 버튼삭제
			  $("#ckbtn" + whichone).remove();			  
	}
	
  else
  {
	      tmp='점검자와 이름이 다릅니다. 확인바랍니다.';
		
		  $('#alertmsg').html(tmp); 
		  
		  $('#myModal').modal('show');  
  }

				
}

</script>
