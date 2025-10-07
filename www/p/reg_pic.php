<meta charset="utf-8">
 
 <?php
 session_start(); 
  
 $num=$_REQUEST["num"]; 
 $parent=$num;
 
 $workername = $_REQUEST["workername"];
 
  if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
	 
 try{
	$sql = "select * from mirae8440.work where num=?";
	$stmh = $pdo->prepare($sql);  
	$stmh->bindValue(1, $num, PDO::PARAM_STR);      
	$stmh->execute();            

	$row = $stmh->fetch(PDO::FETCH_ASSOC); 	 

	$content=$row["content"];
	$childnum=$row["num"];
	$workplacename=$row["workplacename"];
	$filename1=$row["filename1"];
	$filename2=$row["filename2"];
	$imgurl1="../imgwork/" . $filename1;
	$imgurl2="../imgwork/" . $filename2;	 
	 
	 if($childnum!=0)
		   $mode="modify";
	    else
		     $mode="insert";
		 
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }  
   
 ?>

<?php include getDocumentRoot() . '/load_header.php' ?>

 <title> 시공전후 사진등록/수정/삭제 </title>
 </head>
<body>
<div class="container">
    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-center">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id="alertmsg" class="fs-1 mb-5 justify-content-center">
		     결재가 진행중입니다.<br>수정사항이 있으면 결재권자에게 말씀해 주세요.
		   </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>
<div id="top-menu">
    <?php if (!isset($_SESSION["userid"])): ?>
        <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <h2 class="fs-4 font-center text-left"><br>
                    <?= $_SESSION["name"] ?> |
                    <a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?= $_SESSION["userid"] ?>">정보수정</a>
                </h2>
            </div>
        </div>
    <?php endif; ?>
</div>

<br> 
			<div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<h1 class="display-1  text-left">
  <input type="button" class="btn btn-secondary btn-lg " value="이전화면으로 돌아가기" onclick="location.href='./view.php?num=<?=$num?>&check=<?=$check?>&workername=<?=$workername ?>'"> </h1> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
<br>
<br>
<form id="board_form" name="board_form" method="post" action="pic_insert.php?workername=<?=$workername?>"   enctype="multipart/form-data">  
     
	 <input type="hidden" id=childnum name=childnum value="<?=$childnum?>" >
	 <input type="hidden" id=check name=check value="<?=$check?>" >
	 <input type="hidden" id=parent name=parent value="<?=$parent?>" >
	 <input type="hidden" id=num name=num value="<?=$num?>" >
	 <input type="hidden" id=mode name=mode value="<?=$mode?>" >
	 <input type="hidden" id=workplacename name=workplacename value="<?=$workplacename?>" >
	 <input type="hidden" id=filedelete name=filedelete >
	 
	 
    <div class="container">
        <div class="row d-flex mb-5">
            <h2 class="fs-2 text-center mb-2"> 시공전후 사진</h2>
        </div>	 	 
 
 			<div class="row">				
			   <div id=progressbar class="blink" style="display:none;">
			 <!--  <div id=progressbar style="display:none;" class=blinking > -->
			   <div class="row">				 </div> <br>
					<h2 class="fs-3  text-left"> 사진등록을 서버에 저장중입니다. <br> (잠시만 기다려주세요.) </h2>
					<div class="progress">
					  <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span id="current-progress"></span>
					  </div>
				   	</div>			
								<div class="row">				 </div> <br>
				</div>
			</div>
			
			<br>
        <div class="d-flex p-2 justify-content-center">
            <span class="fs-4 badge bg-secondary ">현장명 : <?= $workplacename ?> </span>
        </div>
		<div class="row mt-2">			
			<span class="form-control fs-4 text-left text-primary">		
				 시공전 사진 : </span> <br>      
		<?php 
			if($filename1!=null) {				
			  print "기존 업로드 파일 있음 " . $filename1 ;  
			  print " <br> " ;  
			  print "<button type='button' class='btn btn-secondary btn-lg ' id='delPicBefore' onclick=delPic('before')> 삭제 </button> <br>";		  			  
			  print " <br> " ;  			  
			  print "<div class='imagediv' > ";
			  echo "<img class='before_work' src='". $imgurl1  . "'>";			  			  
			  print "</div> <br> ";
			  
              
			  }
		?>
		</div>   
		
  <div class="row mt-2">
  <input name="mainBefore" class="input" type="file" onchange="this.value" id="mainBefore"  required />
     
		   
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
	   	   							
		<div class="row mt-2">			
			<span class="form-control fs-4 text-left text-danger">		
				 시공후 사진 : </span> <br> 
		<?php 
			if($filename2!=null) {
			  print "기존 업로드 파일 있음 " . $filename2 ; 
			  print " <br> " ;  
			  print "<button type='button' class='btn btn-secondary btn-lg ' id='delPicAfter' onclick=delPic('after')> 삭제 </button> <br>";		  			  
			  print " <br> " ; 			  
			   print "<div class='imagediv' > ";
			  echo "<img class='after_work' src='". $imgurl2  . "'>";
			  print "</div>  <br> ";
			  }
		?> 		
		</div>
		<div class="row">
		
  <input name="mainAfter" class="input" type="file"  id="mainAfter"  onchange="this.value" required />
       <br>
       <br>
		   
	   </div>	    					
	   <div class="row"> <div class="col">&nbsp;  <H1  class="display-2 font-center text-center" > </H1> </div></div>			
	   
 			<div class="row">				
			   <div id=progressbar2 class="blink" style="display:none;">
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
			<h1 class="display-1  text-left">
			<input type="button" class="btn btn-primary btn-lg " value="서버에 저장하기" onclick="javascript:pro_submit()" > </h1>
	   </div>	    	
	   
	   </div> 

 </div> 
 </form>
	 
 </body>
</html>    

 <script language="javascript">
 

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


function date_mask(formd, textid) {

/*
input onkeyup에서
formd == this.form.name
textid == this.name
*/

var form = eval("document."+formd);
var text = eval("form."+textid);

var textlength = text.value.length;

if (textlength == 4) {
text.value = text.value + "-";
} else if (textlength == 7) {
text.value = text.value + "-";
} else if (textlength > 9) {
//날짜 수동 입력 Validation 체크
var chk_date = checkdate(text);

if (chk_date == false) {
return;
}
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
}  

function copy_below(){	   
}  

function pro_submit()
     {
		 
           $('#progressbar').show();	
           $('#progressbar1').show();	
           $('#progressbar2').show();	
           $('#board_form').submit();	

     }

// 사진 회전하기
function rotate_image()
{	
 var box = $('.imagediv');
 var imgObj = new Image();
 var imgObj2 = new Image();
 imgObj.src = "<? echo $imgurl1; ?>" ; 
 imgObj2.src = "<? echo $imgurl2; ?>" ; 
 box.css('width','800px');
 box.css('height','1000px');
 box.css('margin-top','200px');
 
 if( imgObj.width > imgObj.height  ||  imgObj2.width > imgObj2.height)
   {
		$('.before_work').addClass('rotated');
		$('.after_work').addClass('rotated');		
   }

}
setTimeout(function() {
 // console.log('Works!');
 rotate_image();
}, 3000);

	  
function delPic(delChoice) {
    if (!confirm("정말로 삭제하시겠습니까?")) return;

    if (delChoice == 'before') {
        $("#filedelete").val('before');
    }
    if (delChoice == 'after') {
        $("#filedelete").val('after');
    }

    Toastify({
        text: `사진 삭제완료`,
        duration: 2000,
        close: true,
        gravity: "top", // 알림 위치
        position: "center", // 알림 중앙 정렬
        backgroundColor: "#4fbe87", // 성공 메시지 배경색
    }).showToast();

    // 2초 후에 폼 제출
    setTimeout(() => {
        document.getElementById('board_form').submit();
    }, 2000);
}


</script>
