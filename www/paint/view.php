 <?php
 
 session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }
   
  $num=$_REQUEST["num"];					  
  
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

  if(isset($_REQUEST["orderdate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $orderdate=$_REQUEST["orderdate"];
  else
   $orderdate="";

  if(isset($_REQUEST["indate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $indate=$_REQUEST["indate"];
  else
   $indate="";

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];


  require_once("../lib/mydb.php");
  $pdo = db_connect();

    try{
      $sql = "select * from mirae8440.make where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

			  $num=$row["num"];
			  $orderdate=$row["orderdate"];
			  $indate=$row["indate"];
			  $company=$row["company"];
			  $text=$row["text"];  
	       }		 			  
      }
     catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
?>

<!DOCTYPE HTML>
 <html>
 <head>
<meta charset="UTF-8">



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>

<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>

<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>

<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>   
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="../css/partner.css" type="text/css" />
   </head>
 
   <body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://8440.co.kr/order/order.js"></script>
<script>   // 화면을 시간 지연 후 나타내 주기
function callit() {
setTimeout(function(){
 save_check();  //your code here
}, 500);
}
</script>	

	 <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>" > 
  <?php  } 
else  {
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>&callback=1"> 
  <?php
	}
  ?>	   
  <div class="row">  <<div class="col">
     <h1 class="display-5 font-center text-center"> &nbsp; &nbsp; 발주서 조회 </h1> </div>  </div>
<div class="row"> 		  
 <h1 class="display-5 ">
	 &nbsp; &nbsp;  발주번호:&nbsp; <?=$num?> &nbsp; &nbsp; </h1> </div>

	      <div class="clear"></div>	
<div class="row"> 		  
 <h1 class="display-5 ">
	   &nbsp; &nbsp;  발 주 일 :   <input disabled type="date" id="orderdate" name="orderdate" value="<?=$orderdate?>" size="14" placeholder="발주일" >  </h1> </div>

<div class="row"> 		
<h1 class="display-5 ">
	  &nbsp; &nbsp; 발주처 :  
	 <?php
	   if($company=="")
		    $company="유일기업";
		?>
	  <input disabled  type="text" id="company" name="company" value="<?=$company?>" size="20" placeholder="발주처" > </h1> </div>   
	 <div class="clear"></div>	
	 <div style="font-size:18px; color:red; margin-left:45px; font-weight:bold; "> 콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!  </div>

	  </div>
	 	 <div class="clear"></div>			 
<div id=spreadsheet> </div>	 

<script>
 var changed = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
}

var beforeChange = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
}

var insertedRow = function(instance) {
}

var insertedColumn = function(instance) {  
}

var deletedRow = function(instance) {
}

var deletedColumn = function(instance) {
}

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
}

var resizeColumn = function(instance, cell, width) {
}

var resizeRow = function(instance, cell, height) {
}

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);

}

var loaded = function(instance) {
}

var moveRow = function(instance, from, to) {
}

var moveColumn = function(instance, from, to) {
}

var blur = function(instance) {
}

var focus = function(instance) {
}

var data = [     [''],
 [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],
];

var table1 = jexcel(document.getElementById('spreadsheet'), {
    data:data,
   // csv:'http://8440.co.kr/test.csv',
  //	csvHeaders:false,
    tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    tableHeight: '600px' ,	
    columns: [
        { title: '구분', type: 'text', width:'50' },
        { title: '현장명', type: 'text', width:'200' },
        { title: '품목', type: 'text', width:'250' },
        { title: '수량', type: 'text', width:'70' },
        { title: '단위', type: 'text', width:'70' },
        { title: '단가', type: 'text', width:'100' },
        { title: '색상', type: 'text', width:'250' },
       // { type: 'calendar', width:'50' },
	    // tableWidth: '600px',
		
    ],
    onchange: changed,
    onbeforechange: beforeChange,
    oninsertrow: insertedRow,
    oninsertcolumn: insertedColumn,
    ondeleterow: deletedRow,
    ondeletecolumn: deletedColumn,
    onselection: selectionActive,
    onsort: sort,
    onresizerow: resizeRow,
    onresizecolumn: resizeColumn,
    onmoverow: moveRow,
    onmovecolumn: moveColumn,
    onload: loaded,
    onblur: blur,
    onfocus: focus,
});

</script>	 
	  
	 	 <div class="clear"></div> 

		<input type="hidden" id="text" name="text" value="<?=$text?>" size="5" > 		
	
     <div class="clear"></div> 
  			</form>       	 
<br><br>
<button type="button"  class="btn btn-dark btn-lg " onclick="location.href='index.php?keyword='"> 목록으로 돌아가기   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
   
				</div> <br>
  
	  </div> 


					   
			   </div>   <!-- php 실행을 위한 빈자리 -->   	 
	   </div>   <!-- end of wrap -->   

<script>
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
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트
    }
}
function Enter_CheckTel(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_searchTel();  // 실행할 이벤트
    }
}

function exe_search()
{
      var postData = changeUri(document.getElementById("outworkplace").value);
      var sendData = $(":input:radio[name=root]:checked").val();

      $("#displaysearch").show();
//	 if(sendData=='주일')
//         $("#displaysearch").load("./search.php?mode=search&search=" + postData);
//	 if(sendData=='경동') 
         $("#displaysearch").load("./searchkd.php?mode=search&search=" + postData);	  
}
function exe_searchTel()
{
	  var postData =  changeUri(document.getElementById("receiver").value);
      $("#displaysearchworker").show();
      $("#displaysearchworker").load("./workerlist.php?mode=search&search=" + postData);
}
function del(href) 
     {
		 save_check();
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          }
}

function sortall(){
 save_check();	
 var sort;
 sort=$("#sort").val();		
 if(sort=='1')
     $("#sort").val("2");
   else
	   $("#sort").val("1");
 $("#modify").val("1");			// 소팅할 것
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}

function saveit() {
// $("#modify").val("1");			// 이전화면 유지
 save_check();
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
}


function savetext() {
 var trlength =$('#spreadsheet tbody tr').length;	
 var tmp;
 tmp="";
 
 for(i=0;i<trlength;i++)
     tmp = tmp + table1.getRowData(i) + '|';	 

alert(trlength);
alert(tmp);
 $("#text").val(tmp);			// 테이블의 텍스트를 히든형태로 폼에 기록하기
 
// document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
}


function load(href) 
     {
		   save_check();
           document.location.href = href;  
}	
function movetowin(href) 
     {
		   save_check();
           document.location.href = href;  
}	


function save_check() {   // 체크된 것 저장하기
// $("#modify").val("1");			// 이전화면 유지
 //document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
 //var arr=new Array("<? echo $arr; ?>");   // php배열 가져오는 법
 
 var arr=<?php echo  json_encode($arr);?>;
 var name='<?php echo $user_name; ?>' ;
 var counter = '<?php echo $counter ;?>';
 var left_check = new Array();
 var mid_check = new Array();
 var right_check = new Array();
 var done_check = new Array();
 var remain_check = new Array();

var tmp; 
}


function send_alert() {   // 알림을 서버에 저장
 
var tmp; 				
		
	tmp="./save_alert.php";	
		
    $("#vacancy").load(tmp);      
	
    alertify.alert('발주서 전송 알림창', '<h1> 발주서가 전송되었습니다. </h1>'); 	

 }      
 
function click_it()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
	tmp="./load_alert.php";			
    $("#vacancy").load(tmp);     
	
 var alerts=$("#alerts").val();	 
if(name=='김동실' && alerts=='1') {
    $("#alerts").val('0');	
    alertify.alert('알림창', '<h1> 발주서가 접수되었습니다. 확인바랍니다. </h1>'); 
 	tmp="./save_alert.php?choice=2";	
		
    $("#vacancy").load(tmp);      
	$("#alerts").val('0');	
	
}
}

// 인터벌은 지연시간 후 계속 실행하는 부분입니다.
	var timer;
	timer=setInterval(function(){
		click_it();
	},2000); 
 
 
 function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function load_data() {
 var text='<?php echo $text; ?>' ;	
 arr=text.split('|');
for(i=0;i<arr.length;i++) {
	tmp=arr[i].split(',');
	table1.setRowData(i,[tmp[0],tmp[1],tmp[2],tmp[3],tmp[4],tmp[5],tmp[6]]);		
	}
}
 </script> 



	</body>
	
<script>
setTimeout(function() {
 load_data();
}, 500);
</script>	
	
	
	
 </html>
