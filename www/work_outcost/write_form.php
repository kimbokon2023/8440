 <?php
  session_start(); 
  
 $level= $_SESSION["level"];
   
 include "../load_company.php";
 $companycount = count($suply_company_arr);   
 // var_dump($suply_company_arr);
 // 납품업체 숫자 넘겨줌 
  
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";

  if(isset($_REQUEST["which"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $which=$_REQUEST["which"];
  else
   $which="2";
  
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

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];


  if(isset($_REQUEST["regist_state"]))  // 등록하면 1로 설정 접수상태
   $regist_state=$_REQUEST["regist_state"];
  else
   $regist_state="1";

 $year=$_REQUEST["year"];   // 년도 체크박스
 
 
//  철판리스트 뽑기 
   
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  
	
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
		  
 
  if ($mode=="modify"){
    try{
      $sql = "select * from mirae8440.work_outcost where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "결과가 없습니다.<br>";
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
  }
    
  if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  $registerdate=date("Y-m-d");
			  $widejamb_unitprice=null;
			  $normaljamb_unitprice=null;
			  $narrowjamb_unitprice=null;
			  $mode = 'insert';

  } 

// print '$item';
// var_dump($item);
// var_dump($steelitem_arr);

?> 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
  
<title> 외주단가 자료등록 </title>

</head>

   
<style>

.show {display:block} /*보여주기*/

.hide {display:none} /*숨기기*/

</style>
 
<body>
   

<?php include "../common/modal.php"; ?>



<form  id="board_form" name="board_form" method="post" > 

	       <input type="hidden" id="num" name="num" value="<?=$num?>"  >
	       <input type="hidden" id="mode" name="mode" value="<?=$mode?>"  >
           <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
   
   <div class="container">

	
    <div class="card mt-2 mb-3">
       <div class="card-body">

	<div class="d-flex mb-5 mt-5 justify-content-center align-items-center"> 	
    <h3> 쟘 외주 단가 </h3> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	   &nbsp;&nbsp;
  	   <button id="doneBtn" type="button" class="btn btn-dark btn-sm "  > 저장  </button> &nbsp;
  	   <button id="delBtn" type="button" class="btn btn-danger btn-sm"  > 삭제  </button> &nbsp;
  	   <button id="showlogBtn" type="button" class="btn btn-info btn-sm"  title="log 기록" > <ion-icon name="clipboard-outline"></ion-icon>  </button> &nbsp;
       <button type="button" class="btn btn-secondary btn-sm" onclick="self.close();" > 닫기 </button> 
	</div>	
	
	
   	
  <div class="row mt-3 mb-3">
  
	 <div class="card" >
		<div class="card-body mt-2 mb-2" >
	 
      <table class="table table-bordered">        

		<tr>
		  <td>
			<label for="registerdate">등록일  </label>
		  </td>
		  <td>             
			<input type="date" id="registerdate" name="registerdate" value="<?=$registerdate?>"  > &nbsp; 
		  </td>
		</tr>
		<tr>
		  <td>
			<label for="widejamb_unitprice">와이드쟘(막판유) 단가  </label>
		  </td>
		  <td>             
			<input type="text" id="widejamb_unitprice" name="widejamb_unitprice" value="<?=$widejamb_unitprice?>" size="10" onkeyup="formatWithComma(this)" oninput="this.value=this.value.replace(/[^0-9]/g,'');" style="text-align: right;"> 원&nbsp; 
		  </td>
		</tr>
		<tr>
		  <td>
			<label for="normaljamb_unitprice">멍텅구리(막판무) 단가  </label>
		  </td>
		  <td>             
			<input type="text" id="normaljamb_unitprice" name="normaljamb_unitprice" value="<?=$normaljamb_unitprice?>" size="10" onkeyup="formatWithComma(this)" oninput="this.value=this.value.replace(/[^0-9]/g,'');" style="text-align: right;"> 원&nbsp; 
		  </td>
		</tr>
		<tr>
		  <td>
			<label for="narrowljamb_unitprice">쪽쟘 단가  </label>
		  </td>
		  <td>             
			<input type="text" id="narrowjamb_unitprice" name="narrowjamb_unitprice" value="<?=$narrowjamb_unitprice?>" size="10" onkeyup="formatWithComma(this)" oninput="this.value=this.value.replace(/[^0-9]/g,'');" style="text-align: right;"> 원&nbsp;
		  </td>
		</tr>
  
     
      </table>
		</div>
		</div>
    </div>
    </div>
    </div>
    </div>
	    
		
	</form>
	
<script>

function formatWithComma(inputElement) {
    var num = inputElement.value.replace(/,/g, ''); // 콤마 제거
    inputElement.value = Number(num).toLocaleString('en'); // 콤마 추가
}

function formatInput(input) {
    let value = input.value;
    value = value.replace(/,/g, ""); // Remove all existing commas
    value = value.replace(/[^\d]/g, ""); // Remove all non-digit characters
    input.value = numberWithCommas(value); // Add commas and update the value
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


//toggle 이벤트로 기능 부여 버튼글씨도 변환 펼치기 닫기
$(document).ready(function(){
	
		// Log 파일보기
	$("#showlogBtn").click( function() {     	
		var num = '<?php echo $num; ?>' 
		// table 이름을 넣어야 함
		var workitem =  'work_outcost' ;
		// 버튼 비활성화
		var btn = $(this);						
			popupCenter("../Showlog.php?num=" + num + "&workitem=" + workitem , '로그기록 보기', 500, 500);									 
		btn.prop('disabled', false);					 					 
	});	

	$("#doneBtn").click(function(){ 
		console.log($("#board_form").serialize());
		$.ajax({
			url: "insert.php",
			type: "post",       
			data: $("#board_form").serialize(),  
			dataType: "json",
			success: function(data) {
				console.log(data);

				// opener를 재로드
				if (window.opener && !window.opener.closed) {
					window.opener.location.reload();
				}

				// 현재 창 닫기
				window.close();
			},
			error: function(jqxhr, status, error) {
				console.log(jqxhr, status, error);
			}                       
		});  // end of ajax
	});

	$("#delBtn").click(function(){      // del
			var num = $("#num").val();    		
			var user_name = $("#user_name").val();  
			   
			// 결재상신이 아닌경우 수정안됨	
			   $("#mode").val('delete');     
			   

				// DATA 삭제버튼 클릭시
					Swal.fire({ 
						   title: 'DATA 삭제', 
						   text: " DATA 삭제 신중! '\n 정말 삭제 하시겠습니까?", 
						   icon: 'warning', 
						   showCancelButton: true, 
						   confirmButtonColor: '#3085d6', 
						   cancelButtonColor: '#d33', 
						   confirmButtonText: '삭제', 
						   cancelButtonText: '취소' })
						   .then((result) => { if (result.isConfirmed) { 
								$.ajax({
										url: "insert.php",
										type: "post",		
										data: $("#board_form").serialize(),
										dataType:"json",
										success : function( data ){
										// console.log( data);
										if (opener) {
											opener.location.reload();
										}								
										
										setTimeout(function() {												        
												 window.close();	
											   }, 1000);		
									},
										error : function( jqxhr , status , error ){
											console.log( jqxhr , status , error );
										} 			      		
								   });	
						   
						   } });		   
				  
					
		 }); 	 
		 

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});			
   
     	  
		
});			 




</script> 

	</body>
 </html>
