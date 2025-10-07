<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
 } 
?>
<?php include getDocumentRoot() . '/load_header.php' ?>
<title> 전산실장 정산 </title> 
</head>
<body>
<?php require_once(includePath('myheader.php')); ?>   

<?php


$callback=$_REQUEST["callback"] ?? '';  // 출고현황에서 체크번호
  
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

$fromdate=$_REQUEST["fromdate"] ?? '';	 
$todate=$_REQUEST["todate"] ?? '';	 


  if(isset($_REQUEST["regist_state"]))  // 등록하면 1로 설정 접수상태
   $regist_state=$_REQUEST["regist_state"];
  else
   $regist_state="1";

 $year=$_REQUEST["year"] ?? '';   // 년도 체크박스
       
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  
  
  if ($mode=="modify"){
    try{
      $sql = "select * from mirae8440.automan where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
              $num=$row["num"];
 			  $proDate=$row["proDate"];			  			  
 			  $writer=$row["writer"];			  			  
 			  $amount=$row["amount"];			  			  
 			  $memo=$row["memo"];
			  $which=$row["which"];							
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  } 
  
  if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.          
			  $proDate=date("Y-m-d");
 			  $writer=$_SESSION["name"];			  			  
 			  $amount="";			  			  	  			  
 			  $memo="";			  			  
			  $which="2";
  } 
  
?>

<div class="container">   
<div class="card mt-2 mb-1">  			
<div class="card-header">  			
  <div class="d-flex mb-4 mt-4 fs-4 justify-content-center">  
  <?php
    if($mode=="modify"){
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>" > 
  <?php  } else {
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>"> 
  <?php
	}
  ?>	   
  
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
       <input type="hidden" id="page" name="page" value="<?=$page?>"  >
       <input type="hidden" id="num" name="num" value="<?=$num?>"  >
       <input type="hidden" id="mode" name="mode" value="<?=$mode?>"  >
	

	  <h4> 전산실장 정산내용 등록/수정 	&nbsp;	&nbsp;	&nbsp;	&nbsp;
	  <button type="button" id="saveBtn"  class="btn btn-primary"> DATA 저장 </button>	&nbsp;       
	  <button type="button" id="gotoList"  class="btn btn-secondary" onclick="location.href='list.php?mode=search&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>'" > 목록(List) </button>	&nbsp;       	   						
	</h4> 
	</div>
	</div>
  	
<div class="card-body justify-content-center align-items-center">  		
	<div class="d-flex mb-1 mt-2 justify-content-center">       
		  
 <?php	 		  
	 	 $aryreg=array();
	 	 $aryitem=array();
		 if($which=='') $which='2';
	     switch ($which) {
					case   "1"             : $aryreg[0] = "checked" ; break;
					case   "2"             :$aryreg[1] =  "checked" ; break;
					default: break;
				}		
	   ?>		  
	   구분 :  &nbsp; &nbsp;  <span class="text-primary"> 수입   </span>  &nbsp; 
			<input  type="radio" <?=$aryreg[0]?> name=which value="1"> &nbsp; &nbsp; 
			&nbsp;  <span class="text-danger"> 지출   </span>  &nbsp;  
			<input  type="radio" <?=$aryreg[1]?>  name=which value="2">  	   
</div>
<div class="d-flex p-3 m-4 mb-2 mt-2 justify-content-center">   		           		  
	 기록일 :  &nbsp;
	 <input type="date" id="proDate" name="proDate" value="<?=$proDate?>" size="14" > &nbsp;
	작성자 :  &nbsp;
	 <input type="text" id="writer" name="writer" value="<?=$writer?>" size="14" > &nbsp;
</div>
<div class="d-flex p-3 m-4 mb-2 mt-2 justify-content-center">   		           		   		           		  
	  &nbsp; 내 역 :  &nbsp;	 
	 <input type="text"  id="memo" name="memo" value="<?=$memo?>" size="45" placeholder="내역"> 	 
</div>        
<div class="d-flex p-3 m-4 mb-2 mt-2 justify-content-center">   		           		   		           		  
	금 액 : &nbsp;	 
     <input type="text" name="amount" id="amount" value="<?=$amount?>"  onkeyup="inputNumberFormat(this)" size="10" style="text-align:right;" placeholder="비용" />	 	 </div>
	
	</div>
		</form>
	 
	  </div> 
	  </div> 
	  
	  
<script>
$(document).ready(function(){
		$("#saveBtn").click(function(){   	
		   // grid 배열 form에 전달하기						    						    
		  $("#board_form").submit(); 								 
		 });	
	
});

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
  var copyText = document.getElementById("test");   // 클립보드 복사 
  copyText.select();
  document.execCommand("Copy");
}  

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
function Enter_Check(){
var tmp = $('input[name=search_opt]:checked').val();	
	
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13 && tmp== 1 )
      search_jamb();  // 잠 현장검색
	  
    if(event.keyCode == 13 && tmp== 2 )
      search_ceiling();  // 천장 현장 검색	      
}

function Choice_search() {
var tmp = $('input[name=search_opt]:checked').val();	
	if(tmp =='1' )
      search_jamb();  // 잠 현장검색	  
    if(tmp == '2' )
      search_ceiling();  // 천장 현장 검색	      
  
 // alert(tmp);
  }
  
function Enter_CheckTel(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_searchTel();  // 실행할 이벤트
    }
}

function deldate(){	

document.getElementById("indate").value  = "";
var _today = new Date();   


printday=_today.format('yyyy-MM-dd');   
document.getElementById("proDate").value  = printday;
$("input[name='which']:radio[value='1']").attr("checked", true) ;

}  

// _today.format   사용하려면 아래 내용이 함께 포함되어야 합니다.

Date.prototype.format = function (f) {

    if (!this.valueOf()) return " ";

    var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];

    var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];

    var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var d = this;
	
    return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function ($1) {

        switch ($1) {

            case "yyyy": return d.getFullYear(); // 년 (4자리)

            case "yy": return (d.getFullYear() % 1000).zf(2); // 년 (2자리)

            case "MM": return (d.getMonth() + 1).zf(2); // 월 (2자리)

            case "dd": return d.getDate().zf(2); // 일 (2자리)

            case "KS": return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)

            case "KL": return weekKorName[d.getDay()]; // 요일 (긴 한글)

            case "ES": return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)

            case "EL": return weekEngName[d.getDay()]; // 요일 (긴 영어)

            case "HH": return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)

            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)

            case "mm": return d.getMinutes().zf(2); // 분 (2자리)

            case "ss": return d.getSeconds().zf(2); // 초 (2자리)

            case "a/p": return d.getHours() < 12 ? "오전" : "오후"; // 오전/오후 구분

            default: return $1;

        }

    });

};

String.prototype.string = function (len) { var s = '', i = 0; while (i++ < len) { s += this; } return s; };

String.prototype.zf = function (len) { return "0".string(len - this.length) + this; };

Number.prototype.zf = function (len) { return this.toString().zf(len); };

</script> 
	</body>
 </html>
