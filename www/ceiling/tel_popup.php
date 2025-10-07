 <?php
 session_start(); 
  
 $chargedperson=$_REQUEST["chargedperson"]; 
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.ceiling where chargedman=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $chargedperson, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
     $chargedman = $row["chargedman"];
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }  	 
?>

<!DOCTYPE HTML>
<html>
<head> 
<meta charset="utf-8"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<style>

.progress {
  margin: 10px;
  width: 700px;
}

.blink {
	-webkit-animation: blink 1.05s linear infinite;
	-moz-animation: blink 1.05s linear infinite;
	-ms-animation: blink 1.05s linear infinite;
	-o-animation: blink 1.05s linear infinite;
	 animation: blink 1.05s linear infinite;
}
@-webkit-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-moz-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-ms-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-o-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
</style>

 <title> 사진등록/수정 </title>
 </head>
  <body>
<div id="top-menu">

</div>
</div>
<br> 
			<div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<h1 class="display-1  text-left">
  <input type="button" class="btn btn-secondary btn-lg " value="닫기" onclick="self.close();"> </h1> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
<br>
<br>
<form id="board_form" name="board_form" method="post" action="pic_insert.php"   enctype="multipart/form-data">  
     
	 <input type="hidden" id=childnum name=childnum value="<?=$childnum?>" >
	 <input type="hidden" id=check name=check value="<?=$check?>" >

	 <div  class="container">
			<div class="row">

	 <H1  class="display-5 font-center text-center" > 사진 등록/수정 </H1> 
 </div>
 
 			<div class="row">				
			   <div id=progressbar class="blink" style="display:none;">
			 <!--  <div id=progressbar style="display:none;" class=blinking > -->
			   <div class="row">				 </div> <br>
					<h1 class="display-1  text-left"> 사진등록을 서버에 저장중입니다. <br> (잠시만 기다려주세요.) </h1>
					<div class="progress">
					  <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span id="current-progress"></span>
					  </div>
				   	</div>			
								<div class="row">				 </div> <br>
				</div>
			</div>
			
			<br>
			<div class="row">
		     <h1 class="display-5 font-center text-left"> 		   
					품명(규격) :   <?=$PROD_DES?> 	   
			<br> 
			<br> 
		<?php 
			if($filename1!=null) {
			  print "기존 업로드 파일 있음 " . $filename1 ;  
			  echo "<img src='". $imgurl1  . "'";
			  			  print " </div> <br> <br>";
			  }
			?>
		  <div class="row">
		  <input id="upfile"  name="upfile[]" class="input" type="file" onchange="this.value" multiple >
		  
	   </div>	    	

 			<div class="row">				
			   <div id=progressbar1 class="blink" style="display:none;">
			 <!--  <div id=progressbar style="display:none;" class=blinking > -->
			   <div class="row">				 </div> <br>
					<h1 class="display-1  text-left"> 사진등록을 서버에 저장중입니다. <br> (잠시만 기다려주세요.) </h1>
					<div class="progress">
					  <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span id="current-progress"></span>
					  </div>
				   	</div>			
					
								<div class="row">				 </div> <br>
				</div>
			</div>   	   
	   	   							
		<div class="row">

		         <h1 class="display-5 font-center text-left"> 		   
       
						<br>
  
			<div class="row">							
			<h1 class="display-1  text-left">
  <input type="button" class="btn btn-primary btn-lg " value="서버에 저장하기" onclick="javascript:pro_submit()" > </h1>

	   </div>	    		
   
	   </div> 

 </div> 
 </form>
	 
 </body>
</html>    

<script> 

/* function new(){
 window.open("viewimg.php","첨부이미지 보기", "width=300, height=200, left=30, top=30, scrollbars=no,titlebar=no,status=no,resizable=no,fullscreen=no");
} */

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

function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
}  

function copy_below(){	   
}  

function pro_submit()
     {
	 /* echo "<script> opener.document.getElementById('pInput').value='100'; </script>";   // 부모창에 100 기록해보기
	/ echo "<script> self.close(); </script>";   // 창닫기		 */	
	
	//	opener.document.getElementById('pInput').value='100'; 
	//	self.close();
	   $('#progressbar').show();	
	   $('#progressbar1').show();	
	   $('#progressbar2').show();	
	   $('#board_form').submit();	

     }

</script>
