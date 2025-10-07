<meta charset="utf-8">
 
 <?php
 session_start(); 
  
 $num=$_REQUEST["num"]; 
 $parent=$num;
 
 $workername = $_REQUEST["workername"];
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.work where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
     $workplacename=$row["workplacename"];
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }  
	 
 try{
     $sql = "select * from mirae8440.voc where parent=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
     $content=$row["content"];
     $childnum=$row["num"];
	 
	 if($childnum!=0)
		   $mode="modify";
	    else
		     $mode="insert";
		 
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }  
   
 ?>
 <!DOCTYPE HTML>
 <html>
 <head> 
 <meta charset="utf-8">
 <head>
 <meta charset="UTF-8">
<script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="../css/partner.css" type="text/css" />

 <title> 쟘공사 현장 코멘트 등록하기 </title>
 </head>
  <body>
<div id="top-menu">
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>
			<div class="row">           
			<div class="col">           
		         <h1 class="display-5 font-center text-left"> <br>
	<?=$_SESSION["name"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>
</h1>
</div>
</div>
<br> 
			<div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<h1 class="display-1  text-left">
  <input type="button" class="btn btn-secondary btn-lg " value="이전화면으로 돌아가기" onclick="history.back(-1);"> </h1>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
<br>
<br>
<form id="board_form" name="board_form" method="post" action="voc_insert.php?num=<?=$num?>">  
     
	 <input type="hidden" id=childnum name=childnum value="<?=$childnum?>" >
	 <input type="hidden" id=parent name=parent value="<?=$parent?>" >
	 <input type="hidden" id=num name=num value="<?=$num?>" >
	 <input type="hidden" id=mode name=mode value="<?=$mode?>" >
	 <input type="hidden" id=workplacename name=workplacename value="<?=$workplacename?>" >
	 <input type="hidden" id=workername name=workername value="<?=$workername?>" >
	 <div  class="container">
			<div class="row">

	 <H1  class="display-4 font-center text-center" > 현장 협의사항 등록/수정 </H1> 
 </div>
			<br>
			<div class="row">

		         <h1 class="display-5 font-center text-left"> 		   
       현장명 :   <?=$workplacename?> 	   
<br>
<br>
   협의사항 등록/수정 : <br> <textarea rows="9" cols="28" name="content" placeholder="협의 사항 내용입력" ><?=$content?></textarea>
       <br>
		   
	   </div>	    		
	   			<div class="row">
			<h1 class="display-1  text-left">
  <input type="button" class="btn btn-primary btn-lg " value="수정/저장하기" onclick="javascript:pro_submit()" > </h1>

	   </div>	    		
   
	   </div> 

 </div>
 </form>
 	 <?=$childnum?>
	 <br>
 	 <?=$parent?>
     <br>	 
 	 <?=$mode?>	 
	 
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
           $('#board_form').submit();	

     }

</script>
