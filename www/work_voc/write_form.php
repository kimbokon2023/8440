<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }

ini_set('display_errors','1');  // 화면에 warning 없애기   

?>
 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/common.php' ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

  
<title> 현장소장 Voc </title> 
 
 </head>
 
<body>

<?php include "../common/modal.php"; ?>  

<?php

  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";
    

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

if($mode=='modify')
{
 
 try{
     $sql = "select * from mirae8440.voc where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
 	
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];
     $item_hit     = $row["hit"];
     $parent       = $row["parent"];
 
     $image_name[0]   = $row["file_name_0"];
     $image_name[1]   = $row["file_name_1"];
     $image_name[2]   = $row["file_name_2"];
 
     $image_copied[0] = $row["file_copied_0"];
     $image_copied[1] = $row["file_copied_1"];
     $image_copied[2] = $row["file_copied_2"];
 
     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date,0,10);
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $item_content = $row["content"];
     $is_html      = $row["is_html"];
} catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
      }	
}
	
?>
     
   
  <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" action="insert.php?mode=modify&num=<?=$num?>" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  name="board_form" method="post" action="insert.php" enctype="multipart/form-data"> 
  <?php
	}
  ?>   
   
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		



<div class="container">  

<div class="card justify-content-center" > 	   
   <div class="card-body"> 
	   
	 <div class="d-flex mt-3 mb-3 justify-content-center">  
	  <h3> 현장소장 VOC </h3>  
	 </div>	 
 <div class="d-flex  p-1 m-1 mt-2 mb-2 justify-content-left  align-items-center">  					
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type="button"   class="btn btn-outline-dark  btn-sm" onclick="location.href='list.php'" > <ion-icon name="list-outline"></ion-icon> 목록(List) </button>	&nbsp;	
</div> 
 
  <div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white"> 		
   
	<div class="col-7" onclick="location.href='../work/view.php?num=<?= $parent ?>';"> 
    글제목 : &nbsp; <?= $item_subject ?> 
</div>
		  <?php 
     if($is_html=='1') $result= "접수 중";
	    else
			 $result= "확인완료";
		?>
		  <div class="col-5" > <?= $noticecheck_memo ?> |<?= $item_nick ?> | &nbsp;&nbsp; 상태 : <?= $result ?> &nbsp;&nbsp; | 조회 : <?= $item_hit ?> | <?= $item_date ?>   </div>
   
  </div>
   <div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-left "> 	  
	  <?=$item_content ?>
    </div>
    </div>
    </div>
    </div>
 
 </form>
 
 </body>
 </html>    
 
 
<script> 

$(document).ready(function(){	

	$("#pInput").val('50'); // 최초화면 사진파일 보여주기
	
let timer3 = setInterval(() => {  // 2초 간격으로 사진업데이트 체크한다.
	      if($("#pInput").val()=='100')   // 사진이 등록된 경우
		  {
	             displayfile();  
	             displayimage();  
				 // console.log(100);
		  }	      
		  if($("#pInput").val()=='50')   // 사진이 등록된 경우
		  {
	             displayfileLoad();				 
	             displayimage();				 
		  }		   
	 }, 500);	
	 
  
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
	    
delPicimageFn = function(divID, delChoice) {
	console.log(divID, delChoice);

	$.ajax({
		url:'../file/del_file.php?savename=' + delChoice ,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const savename = data["savename"];		   
		   
		  // 시공전사진 삭제 
			$("#image" + divID).remove();  // 그림요소 삭제
			$("#delPicimage" + divID).remove();  // 그림요소 삭제
		    $("#pInput").val('');					
			
        });		

}
	  	 

// 첨부된 이미지 불러오기
function displayimage() {       
	$('#displayimage').show();
	params = $("#id").val();	
	
    var tablename = 'notice';    
    var item = 'image';
	
	$.ajax({
		url:'../file/load_file.php?id=' + params + '&tablename=' + tablename + '&item=' + item ,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recid = data["recid"];		   
		   console.log(data);
		   $("#displayimage").html('');
		   for(i=0;i<recid;i++) {	
			   $("#displayimage").append("<img id='image" + i + "' src='../uploads/" + data['file_arr'][i] + "' style='width:80%;' > &nbsp; <br> &nbsp;  " );			   
         	  // $("#displayimage").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delPicimage" + i + "' onclick=delPicimageFn('" + i + "','" + data["file_arr"][i] + "')> 삭제 </button>&nbsp; <br>");					   
		      }		   
			    $("#pInput").val('');			
    });	
}

// 기존 있는 이미지 화면에 보여주기
function displayimageLoad() {    

	$('#displayimage').show();			
	var saveimagename_arr = <?php echo json_encode($saveimagename_arr);?> ;	
	
    for(i=0;i<saveimagename_arr.length;i++) {
			   $("#displayimage").append("<img id='image" + i + "'src='../uploads/" + saveimagename_arr[i] + "' style='width:80%;' >&nbsp;  <br> &nbsp; " );			   
         	  // $("#displayimage").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delPicimage" + i + "' onclick=delPicimageFn('" + i + "','" +  savefilename_arr[i] + "')> 삭제 </button>&nbsp; <br>");					   
	  }		   
		$("#pInput").val('');	
}
	 
 
 	 	 
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


}); // end of ready document
 

// 첨부된 파일 불러오기
function displayfile() {       
	$('#displayfile').show();
	params = $("#id").val();	
	
    var tablename = 'notice';    
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
			   $("#displayfile").append("<div id=file" + i + ">  <a href='../uploads/" + data["file_arr"][i] + "' download='" +  data["realfile_arr"][i]+ "'>" +  data["realfile_arr"][i] + "</div> &nbsp;&nbsp;&nbsp;&nbsp;  " );			   
         	 //  $("#displayfile").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + data["file_arr"][i] + "')> 삭제 </button>&nbsp; <br>");					   
		      }		   
    });	
}

// 기존 있는 파일 화면에 보여주기
function displayfileLoad() {    
	$('#displayfile').show();	
	var savefilename_arr = <?php echo json_encode($savefilename_arr);?> ;	
	var realname_arr = <?php echo json_encode($realname_arr);?> ;	
	
    for(i=0;i<savefilename_arr.length;i++) {
			   $("#displayfile").append("<div id=file" + i + ">  <a href='../uploads/" + savefilename_arr[i] + "' download='" + realname_arr[i] + "'>" +  realname_arr[i] + "</div> &nbsp;&nbsp;&nbsp;&nbsp;  " );			   
         	 //  $("#displayfile").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  savefilename_arr[i] + "')> 삭제 </button>&nbsp; <br>");					   
	  }	   
		
}
	
     function del(href) 
     {
		 var level=Number($('#session_level').val());
		 if(level>2)
		     alert("삭제하려면 관리자에게 문의해 주세요");
		 else {
         if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          } 
		 }

     }
   
</script>


 
  
