<?php
// 현장 협의사항 등록/수정 페이지 - 로컬/서버 환경 호환
require_once __DIR__ . '/../bootstrap.php';
include includePath('load_header.php');

// 입력값 검증 및 초기화
$num = $_REQUEST["num"] ?? '';
$parent = $num;
$workername = $_REQUEST["workername"] ?? '';

// 입력값 유효성 검사
if (empty($num) || !is_numeric($num)) {
    die("유효하지 않은 번호입니다.");
}

// 로컬 환경에서 디버그 정보 표시 (필요시에만 주석 해제)
// if (isLocal()) {
//     debug(getEnvironmentInfo(), 'ENVIRONMENT INFO');
// }

// Database connection is already available from bootstrap.php
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        die("데이터베이스 연결에 실패했습니다.");
    }
}
 
 try{
     $sql = "select * from mirae8440.work where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_INT);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
     $workplacename = $row["workplacename"] ?? '';
					
     }catch (PDOException $Exception) {
       if (isLocal()) {
           print "오류: ".$Exception->getMessage();
       } else {
           error_log("Database error in voc.php: " . $Exception->getMessage());
           print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
       }
     }  
	 
 try{
     $sql = "select * from mirae8440.voc where parent=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_INT);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
     $content = $row["content"] ?? '';
     $childnum = $row["num"] ?? 0;
	 
	 if($childnum != 0)
		   $mode = "modify";
	    else
		     $mode = "insert";
		 
     }catch (PDOException $Exception) {
       if (isLocal()) {
           print "오류: ".$Exception->getMessage();
       } else {
           error_log("Database error in voc.php: " . $Exception->getMessage());
           print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
       }
  }  
   
 ?>

<title> 쟘공사 현장 코멘트 등록하기 </title>
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    textarea {
        width: 100%;
        min-height: 150px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        resize: vertical;
    }
    
    .btn {
        margin: 5px;
    }
    
    .display-4, .display-5, .display-1 {
        margin: 10px 0;
    }
    
    #top-menu {
        background-color: #f8f9fa;
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
    }
</style>
</head>
<body>
<div id="top-menu">
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="<?= getBaseUrl() ?>/login/login_form.php">로그인</a> | <a href="<?= getBaseUrl() ?>/member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>
			<div class="row">           
			<div class="col">           
		         <h1 class="display-5 font-center text-left"> <br>
	<?=$_SESSION["name"]?> | 
		<a href="<?= getBaseUrl() ?>/login/logout.php">로그아웃</a> | <a href="<?= getBaseUrl() ?>/member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
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
<form id="board_form" name="board_form" method="post" action="<?= getBaseUrl() ?>/p/voc_insert.php?num=<?=$num?>">  
     
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
// 환경별 baseUrl 설정
window.baseUrl = '<?= getBaseUrl() ?>';

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

var form = document.forms[formd] || document[formd];
var text = form ? form[textid] : null;

if (!text || !text.value) return;

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
           // 폼 유효성 검사
           var content = document.querySelector('textarea[name="content"]');
           if (!content || !content.value.trim()) {
               alert('협의사항 내용을 입력해주세요.');
               if (content) content.focus();
               return;
           }
           
           // 폼 제출
           var form = document.getElementById('board_form');
           if (form) {
               form.submit();
           } else {
               alert('폼을 찾을 수 없습니다.');
           }
     }

</script>
