<meta charset="utf-8">
 
 <?php
 session_start(); 
  
 $num=$_REQUEST["num"]; 
 $parent=$num;
 
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
<script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="../css/partner.css" type="text/css" />

<style>
:root {
    --primary-blue: #0288d1;
    --secondary-blue: #0277bd;
    --light-blue: #b3e5fc;
    --glass-bg: rgba(224, 242, 254, 0.3);
    --glass-border: rgba(176, 230, 247, 0.5);
    --shadow: 0 2px 12px rgba(2, 136, 209, 0.08);
    --text-primary: #01579b;
    --text-secondary: #0277bd;
}

body {
    background: white;
    overflow-x: hidden;
}

.container-fluid {
    max-width: 100%;
    overflow-x: hidden;
    padding: 0.9rem;
}

.glass-container {
    background: linear-gradient(135deg, #e0f2fe 0%, #f1f8fe 100%);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.section-header {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1rem;
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
}

.section-title {
    color: white;
    font-weight: 600;
    margin: 0;
}

.section-subtitle {
    color: var(--text-primary);
    font-weight: 500;
    margin: 1rem 0;
}

.btn-custom {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(2, 136, 209, 0.2);
}

.form-control-custom {
    border: 2px solid var(--glass-border);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.form-control-custom:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(2, 136, 209, 0.25);
    background: white;
}

.user-info {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .glass-container {
        padding: 1rem;
    }
}
</style>

<title> 쟘공사 현장 코멘트 등록하기 </title>
</head>
  <body>

<div class="container-fluid">

<div class="glass-container">
<div class="section-header">
	<h3 class="section-title text-center">현장 협의사항 등록/수정</h3>
</div>

<div class="user-info">
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <div class="text-center">
          	<a href="../login/login_form.php" class="btn-custom">로그인</a>
          	<a href="../member/insertForm.php" class="btn-custom">회원가입</a>
          </div>
<?php
	}
	else
	 {
?>
		<div class="text-center">
			<h5 class="section-subtitle">
				<?=$_SESSION["name"]?> 님 환영합니다
			</h5>
			<a href="../login/logout.php" class="btn btn-outline-secondary btn-sm">로그아웃</a>
			<a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>" class="btn btn-outline-secondary btn-sm">정보수정</a>
		</div>
<?php
	 }
?>
</div>

<div class="text-center mb-3">
	<input type="button" class="btn btn-outline-secondary" value="이전화면으로 돌아가기" onclick="history.back(-1);">
</div>
<form id="board_form" name="board_form" method="post" action="voc_insert.php">
	<input type="hidden" id="childnum" name="childnum" value="<?=$childnum?>">
	<input type="hidden" id="parent" name="parent" value="<?=$parent?>">
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>">
	<input type="hidden" id="workplacename" name="workplacename" value="<?=$workplacename?>">

	<div class="row mb-4">
		<div class="col-12">
			<h4 class="section-subtitle">현장명: <?=$workplacename?></h4>
		</div>
	</div>

	<div class="row mb-4">
		<div class="col-12">
			<label for="content" class="form-label section-subtitle">협의사항 등록/수정:</label>
			<textarea
				id="content"
				name="content"
				class="form-control form-control-custom"
				rows="8"
				placeholder="협의 사항 내용을 입력해주세요"
			><?=$content?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-12 text-center">
			<input type="button" class="btn-custom btn-lg" value="수정/저장하기" onclick="javascript:pro_submit()">
		</div>
	</div>

</form>

</div>
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

var park = document.getElementsByName("asfee");

document.getElementById("ashistory").value  = document.getElementById("ashistory").value + document.getElementById("asday").value + " " + document.getElementById("aswriter").value+ " " + document.getElementById("asorderman").value + " ";
document.getElementById("ashistory").value  = document.getElementById("ashistory").value  + document.getElementById("asordermantel").value + " " ;
     if(park[1].checked) {
        document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 유상 " + document.getElementById("asfee").value + " ";		
	 }		 
	   else
	   {
	    document.getElementById("ashistory").value  = document.getElementById("ashistory").value +" 무상 "+ document.getElementById("asfee").value + " ";				   
	   }
	   
document.getElementById("ashistory").value  += document.getElementById("asfee_estimate").value + " " + document.getElementById("aslist").value+ " " + document.getElementById("as_refer").value + " ";	
document.getElementById("ashistory").value  += document.getElementById("asproday").value + " " + document.getElementById("setdate").value+ " " + document.getElementById("asman").value + " ";	
document.getElementById("ashistory").value  += document.getElementById("asendday").value + " " + document.getElementById("asresult").value+ "        ";
//    = text1.concat(" ", text2," ", text3, " ",  text4);
// document.getElementById("asday").value . document.getElementById("aswriter").value;
	//+ document.getElementById("aswriter").value ;   // 콤마를 계산해 주고 다시 붙여주고붙여주고
   // document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고붙여주고
   
}  

function pro_submit()
     {
           $('#board_form').submit();	

     }

</script>
