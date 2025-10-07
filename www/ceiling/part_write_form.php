<?php\nrequire_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';   
 
 if(!isset($_SESSION["level"]) || $level>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }

ini_set('display_errors','1');  // 화면에 warning 없애기   

?> 

<?php include getDocumentRoot() . '/load_header.php' ?>
 
<title> 조명천장 부자재 </title> 
</head>
 
<body >

<?php   
  // 추가 / 수정 구분을 위해 작성
isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode="";   
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num="";   

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();   

// 부품명 배열 저장
$title_arr = array("중국산휀","일반휀","휠터휀 (LH용)","판넬고정구 (금성아크릴)","비상구 스위치 (건흥KH-9015)","비상등","할로겐(7W -6500K)","T5 일반 5W(300)","T5 일반 11W(600)","T5 일반 15W(900)","T5 일반 20W(1200)","T5 KS 6W(300)","T5 KS 10W(600)","T5 KS 15W(900)","T5 KS 20W(1200)","직관등 600mm","직관등 800mm","직관등 1000mm","직관등 1200mm","할로겐(7W -6500K KS)");
$itemCount = count($title_arr) ;

// print $itemCount;

if ($mode=="modify"){
    try{
      $sql = "select * from mirae8440.part where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();   

    if($count<1){  
		print "검색결과가 없습니다.<br>";
     }else{
		 
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
	  
	  // var_dump($row);
	  
	     for ($i=0;$i<$itemCount;$i++)
			{
				$tmp = "part" . (int)($i+1) ; 
				$$tmp = $row[$tmp];  // part1~part18 = ''
				// print  $$tmp . '   ';				
			}			
		$inputdate= $row["inputdate"];
	 }
				   

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }

if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
	$inputdate=date("Y-m-d");
		
	for ($i=0;$i<$itemCount;$i++)
		{
			$tmp = "part" . (int)($i+1) ; 
			$$tmp = '';  // part1~part18 = ''
			
		}
}

?>

<form id="board_form" name="board_form" method="post"  onkeydown="return captureReturnKey(event)"  >	
	
       <input type="hidden" id="page" name="page" value="<?=$page?>"  >
       <input type="hidden" id="mode" name="mode" value="<?=$mode?>"  >
       <input type="hidden" id="num"  name="num" value="<?=$num?>"  >

<div class="container-fluid">    
<div class="card">    
<div class="card-header">
	<div class="d-flex mb-2 mt-2 justify-content-center align-items-center"> 	
		<h4> 조명/천장 주요 부품</h4> 
	</div>		
	</div>		
<div class="card-body">	
	<div class="d-flex mt-3 mb-1 justify-content-start align-items-center"> 	
		<button id="closeBtn" type="button" onclick="self.close();" class="btn btn-dark  btn-sm me-2"  > &times; 닫기 </button> 	   
		<button type="button" id="saveBtn" class="btn btn-primary btn-sm"> <i class="bi bi-floppy-fill"></i> 저장 </button> &nbsp;   		
		<button type="button" id="delBtn" class="btn btn-danger btn-sm me-5"> <i class="bi bi-trash"></i> 삭제 </button> &nbsp;       		
		입고일자  &nbsp; <input type="date" class="form-control" style="width:120px;" id="inputdate" name="inputdate"  value="<?=$inputdate?>">			
	</div>			
<div class="card">    
<div class="card-body">

     <div class="input-group p-1 mb-1 justify-content-center align-items-center">			   
	   <div class="sero2" style="width:150px;text-align:right;" >  <input id="part1" name="part1" size="1" type="hidden" style="text-align:center;" 					value="<?=$part1?>"></div> 
	   <div class="sero2" style="width:150px;text-align:right;" > 일반휀 <input id="part2" name="part2" size="1" style="text-align:center;" 					value="<?=$part2?>"></div> 
	   <div class="sero2" style="width:150px;text-align:right;" > 휠터휀(LH용) <input id="part3" name="part3" size="1" style="text-align:center;"  			value="<?=$part3?>"></div>
	   <div class="sero2" style="width:250px;text-align:right;" > 판넬고정구(금성아크릴) <input id="part4" name="part4" size="1" style="text-align:center;"  		value="<?=$part4?>"></div>
       </div> 	 
		<div class="input-group p-1 mb-1 justify-content-center align-items-center">			   
	   <div class="sero2" style="width:300px;text-align:right;" > 비상구 스위치(건흥KH-9015) <input id="part5" name="part5" size="1" style="text-align:center;" 	value="<?=$part5?>"> </div> 
	   <div class="sero2" style="width:150px;text-align:right;" > 비상등    <input id="part6" name="part6" size="1" style="text-align:center;"  				value="<?=$part6?>"> </div>
	   <div class="sero2" style="width:250px;text-align:right;" > 할로겐(7W -6500K)<input id="part7" name="part7" size="1" style="text-align:center;"  		value="<?=$part7?>"> </div>
       </div> 	 
		<div class="input-group p-1 mb-1 justify-content-center align-items-center">			   	   
			<div class="sero2" style="width:700px;text-align:right;" > 할로겐(7W -6500K KS)<input id="part20" name="part20" size="1" style="text-align:center;"  		value="<?=$part20?>"> </div>
       </div> 	 
		<div class="input-group p-1 mb-1 justify-content-center align-items-center">			   
	   <div class="sero2" style="width:90px;text-align:left;font-size:15px;font-weight:bold;color:blue;" > T5 (일반) </div> 
	   <div class="sero2" style="width:160px;text-align:right;color:blue;" > 5W(300)    <input id="part8" name="part8" size="1" style="text-align:center;color:blue;"  value="<?=$part8?>"></div>
	   <div class="sero2" style="width:150px;text-align:right;color:blue;" > 11W(600)<input id="part9" name="part9" size="1" style="text-align:center;color:blue;"     value="<?=$part9?>"></div>
	   <div class="sero2" style="width:150px;text-align:right;color:blue;" > 15W(900)<input id="part10" name="part10" size="1" style="text-align:center;color:blue;"     value="<?=$part10?>"></div>
	   <div class="sero2" style="width:150px;text-align:right;color:blue;" > 20W(1200)<input id="part11" name="part11" size="1" style="text-align:center;color:blue;"  value="<?=$part11?>"> </div>
       </div> 	 
		<div class="input-group p-1 mb-1 justify-content-center align-items-center">			   
	   <div class="sero2" style="width:90px;text-align:left;font-size:15px;font-weight:bold;color:red;" > T5 (KS) </div> 
	   <div class="sero2" style="width:160px;text-align:right;color:red;" > 6W(300)    <input id="part12" name="part12" size="1" style="text-align:center;color:red;"  value="<?=$part12?>"> </div>
	   <div class="sero2" style="width:150px;text-align:right;color:red;" > 10W(600)<input id="part13" name="part13" size="1" style="text-align:center;color:red;"     value="<?=$part13?>"> </div>
	   <div class="sero2" style="width:150px;text-align:right;color:red;" > 15W(900)<input id="part14" name="part14" size="1" style="text-align:center;color:red;"     value="<?=$part14?>"> </div>
	   <div class="sero2" style="width:150px;text-align:right;color:red;" > 20W(1200)<input id="part15" name="part15" size="1" style="text-align:center;color:red;"    value="<?=$part15?>"> </div>
       </div> 	 
		<div class="input-group p-1 mb-1 justify-content-center align-items-center">			   
 
	   <div class="sero2" style="width:90px;text-align:left;font-size:15px;font-weight:bold;color:brown;" > 직관등 </div> 
	   <div class="sero2" style="width:160px;text-align:right;color:brown;" > 600mm    <input id="part16" name="part16" size="1" style="text-align:center;color:brown;" value="<?=$part16?>"> </div>
	   <div class="sero2" style="width:150px;text-align:right;color:brown;" > 800mm    <input id="part17" name="part17" size="1" style="text-align:center;color:brown;" value="<?=$part17?>"> </div>
	   <div class="sero2" style="width:150px;text-align:right;color:brown;" > 1000mm   <input id="part18" name="part18" size="1" style="text-align:center;color:brown;" value="<?=$part18?>"> </div>
	   <div class="sero2" style="width:150px;text-align:right;color:brown;" > 1200mm   <input id="part19" name="part19" size="1" style="text-align:center;color:brown;" value="<?=$part19?>"> </div>
       </div> 	 		


		</div>
		</div>

		</div>
			
		</div>
		</div>
		</form>
	
	</body>
 </html>	  
<script>

$(document).ready(function(){
		
	$("#delBtn").click(function(){      // 삭제 버튼 누름
    var level = Number($('#session_level').val());
    if (level > 2) {
        // 관리자 권한이 필요한 경우
        Swal.fire({
            title: '관리자 권한 필요',
            text: "삭제하려면 관리자에게 문의해 주세요.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
        // 삭제 확인
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
		         var num = $("#num").val();  	
				 $("#mode").val('delete');    
				  
				$.ajax({
					url: "part_insert.php?num=" + num + "&mode=delete" ,
					type: "post",		
					data: '',
					dataType: "text",  // text형태로 보냄
					success : function( data ){	
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
								window.opener.location.reload(); // 부모 창 새로고침
							}
							window.close();	
						}, 1000);
					
					
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					} 			      		
				   });	
		     	} // end of isConfirmed
                  
                });
         }
});
	
		
	$("#saveBtn").click(function(){      // DATA 저장버튼 누름
		var num = $("#num").val();  	
		
					// 결재상신이 아닌경우 수정안됨	 
					if(Number(num)>0) 
							   $("#mode").val('modify');     
							  else
								  $("#mode").val('insert');     
							  
					// 자료 삽입/수정하는 모듈		  
					Fninsert();						
	 }); 
		 

	// 삽입/수정하는 모듈 
	function Fninsert() {	 
		   
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
			url: "part_insert.php",
			type: "post",		
			data: data,			
			// dataType:"text",  // text형태로 보냄
			success : function(data){
				console.log(data);
				opener.location.reload();
				window.close();	
				  // 메시지 창 띄우기  문구, 해당초				

			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		

		// else
		  // {
		  // tmp='보고자만 결재상신 상태가 아닌 경우 수정이 가능합니다.';		
		  // $('#alertmsg').html(tmp); 			  
			// $('#myModal').modal('show');  
		  // }
	 }
		 	
}); // end of ready

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}


</script> 