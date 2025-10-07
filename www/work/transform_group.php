<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
 $title_message = 'jamb 출고증 일괄';

?>

<?php include getDocumentRoot() . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
 ?>

</head>

<body>

<?php
 
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
	$num=$_REQUEST["num"];
  else
   $num="";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
if (isset($_REQUEST["array"])) {
    $arrayString = $_REQUEST["array"]; // array 파라미터 값을 받아옵니다.
    $numarr = explode(",", urldecode($arrayString)); // URL 디코딩 후, 콤마로 구분된 문자열을 배열로 변환합니다.
   //  print_r($numarr); // 배열 내용을 출력하거나 필요한 작업을 수행합니다.
}
   
$outputdate = date("Y-m-d", time());

if($outputdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
} 
 
require_once("../lib/mydb.php");
$pdo = db_connect();
  
$text=array();			
$textnum1=array();	
$textnum2=array();	
$textnum3=array();	
$textmemo=array();	
$textset=array();	

// 클릭하면 대표 배열을 불러오기 위함
$workplacename_arr =array();	
$address_arr =array();	
$secondord_arr =array();	
$worker_arr =array();	
$workertel_arr =array();	

for($i=0;$i<count($numarr);$i++) 
{

  try{
      $sql = "select * from mirae8440.work where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$numarr[$i],PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
		  print "검색결과가 없습니다.<br>";
		 }else{
		  $row = $stmh->fetch(PDO::FETCH_ASSOC);
	 }	 
	  $num=$row["num"];

	  $checkstep=$row["checkstep"];
	  $workplacename=$row["workplacename"];
	  $secondord=$row["secondord"];
	  $address=$row["address"];
	  $worker=$row["worker"];
	  $workday=$row["workday"];	

	  $widejamb=$row["widejamb"];
	  $normaljamb=$row["normaljamb"];
	  $smalljamb=$row["smalljamb"];			  
					
	// $jambTypes = [
		// ['condition' => $widejamb, 'text' => "막판(유)"],
		// ['condition' => $normaljamb, 'text' => "막판(무)"],
		// ['condition' => $smalljamb, 'text' => "쪽쟘"]
	// ];

	// $textgroup = '';

			// foreach ($jambTypes as $jamb) {
				// if ( intval($jamb['condition']) >= 1) {
					// $textgroup .= $jamb['text'] . " , ";
				// }
			// }
					  		
       $text[$i] =  $workplacename  ;       
	      
	
		 $textnum1[]=$widejamb;	
		 $textnum2[]=$normaljamb;		 
		 $textnum3[]=$smalljamb;
		 $textmemo[]="";	
		 
					  		
	     switch ($worker) {
			case   "김운호"      : $workertel =  "010-9322-7626" ; break;
			case   "김상훈"      : $workertel =  "010-6622-2200" ; break;
			case   "이만희"      : $workertel =  "010-6866-5030" ; break;
			case   "유영"        : $workertel =  "010-5838-5948" ; break;
			case   "추영덕"      : $workertel =  "010-6325-4280" ; break;
			case   "김지암"      : $workertel =  "010-3235-5850" ; break;
			case   "손상민"      : $workertel =  "010-4052-8930" ; break;
			case   "지영복"      : $workertel =  "010-6338-9718" ; break;
			case   "김한준"      : $workertel =  "010-4445-7515" ; break;
			case   "민경채"      : $workertel =  "010-2078-7238" ; break;
			case   "이용휘"      : $workertel =  "010-9453-8612" ; break;
			case   "박경호"      : $workertel =  "010-3405-6669" ; break;
			case   "조형영"      : $workertel =  "010-2419-2574" ; break;
			case   "김진섭"     : $workertel =  "010-6524-3325" ; break;
			case   "최양원"     : $workertel =  "010-5426-3475" ; break;
			case   "임형주"     : $workertel =  "010-8976-9777" ; break;
			case   "박철우"     : $workertel =  "010-4857-7022" ; break;
			case   "조장우"     : $workertel =  "010-5355-9709" ; break;
			case   "백석묵"     : $workertel =  "010-5635-0821" ; break;
			case   "이인종"     : $workertel =  "010-5237-0771" ; break;
			
			default: break;
		}		

$workplacename_arr[] = $workplacename;
$address_arr[] = $address;
$secondord_arr[] = $secondord;	
$worker_arr[] = $worker;
$workertel_arr[] = $workertel;

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
}
  

if(count($numarr) > 1)
	$workplacename =  $workplacename . ' 외 ' . (count($numarr)-1) . '건' ;

?>

<title>  <?=$title_message?> </title>
   
<style> 

 .checkrow {
	 cursor : pointer;
 }
</style> 
  
   </head>
 <body>
<form id="board_form"  name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>" enctype="multipart/form-data">
	<input type="hidden" name="workday" id="workday" value="<?=$workday?>" >
	
<div class="container">	
<div class="card">
<div class="card-body">        
	<div class="d-flex justify-content-center mb-1 mt-1">  
		<h4 class="mt-1 mb-1">
			<?=$title_message?>
		</h4>			
	</div>
<div class="d-flex justify-content-start mb-1 mt-1">  
	<button onclick="window.close();" type="button" class="btn btn-dark btn-sm me-1">    <ion-icon name="close-outline"></ion-icon> 창닫기  </button>  </button>
	<button onclick="form.submit();"  type="button" class="btn btn-success btn-sm"> <ion-icon name="print-outline"></ion-icon> 인쇄 </button>        
    
</div>
<div class="d-flex flex-column">
    <div class="mb-1 input-group">
        <div class="input-group-text" style="color:red; width: 150px;">하차일시 </div>
        <input type="text" name="outputdate" id="outputdate" value="<?=$outputdate?>" class="form-control" placeholder="일시" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >현장명 </div>
        <input type="text" name="workplacename" id="workplacename" value="<?=$workplacename?>" class="form-control" placeholder="현장명" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >현장주소 </div>
        <input type="text" name="address" id="address" value="<?=$address?>" class="form-control" placeholder="받으실 주소" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >발주처 </div>
        <input type="text" name="secondord" id="secondord" value="<?=$secondord?>" class="form-control" placeholder="발주처">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >받으실분 </div>
        <input type="text" name="worker" id="worker" value="<?=$worker?>" class="form-control" placeholder="소장">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width:150px;">받으실분 연락처 </div>
        <input type="text" name="workertel" id="workertel" value="<?=$workertel?>" class="form-control" placeholder="소장연락처">
    </div>
</div>
	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center" style="margin-left:920px;width:400px;"> 막판(유) 막판(무) 쪽쟘  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 비고 </span>
            
			
			</div>
		</div>

    <?php
    for ($i = 0; $i <= 9; $i = $i + 1) {
        ?>
	
	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center checkrow text-primary"  style="width:120px;"><?=$i+1?> 주소 선택 </span>
            <input type="text" name="text[]" id="text<?=$i?>" value="<?=$text[$i]?>"  class="input-group-text text-left" style="text-align:left; width: 850px;"  >            
            <input type="text" name="textnum1[]" value="<?=$textnum1[$i]?>" size=3 class="text-center" >            
            <input type="text" name="textnum2[]" value="<?=$textnum2[$i]?>" size=3 class="text-center" >            
            <input type="text" name="textnum3[]" value="<?=$textnum3[$i]?>" size=3 class="text-center" >            
            <input type="text" name="textmemo[]" value="<?=$textmemo[$i]?>" size=20  class="text-center" >
            
			
			</div>
		</div>	
    <?php } ?>
</div>


             </div>       

			 
</form>
    
</body>
</html>

<script>


$(document).ready(function(){
		
		// Log 파일보기
	$("#PrintBtn").click( function() {     	
		    var num = '<?php echo $num; ?>' 
			
		$("#board_form").submit();  

	});	
	
	 $('.checkrow').on('click', function() {
		var workplacenameArr = <?php echo json_encode($workplacename_arr); ?>;
		var addressArr = <?php echo json_encode($address_arr); ?>;
		var secondordArr = <?php echo json_encode($secondord_arr); ?>;
		var workerArr = <?php echo json_encode($worker_arr); ?>;
		var workertelArr = <?php echo json_encode($workertel_arr); ?>;		 
		 
			// 클릭된 요소의 인덱스 가져오기
			var index = $('.checkrow').index($(this));

			// 해당 인덱스의 배열 값으로 입력 필드 설정
			$('#workplacename').val(workplacenameArr[index]);
			$('#address').val(addressArr[index]);
			$('#secondord').val(secondordArr[index]);
			$('#worker').val(workerArr[index]);
			$('#workertel').val(workertelArr[index]);
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
//  날짜 수동 입력 Validation 체크
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
   
}  

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;
	
    }
}

function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트 담당자 연락처 찾기
    }
}
function Enter_firstCheck(){
    if(event.keyCode == 13){
      exe_firstordman();  // 원청 담당자 전번 가져오기
    }
}

function Enter_chargedman_Check(){
    if(event.keyCode == 13){
      exe_chargedman();  // 현장소장 전번 가져오기
    }
}

function exe_search()
{

     var tmp=$('#secondordman').val();
	 switch (tmp) {
		 case '김관' :
         $("#secondordmantel").val("010-2648-0225");		 
         $("#secondordman").val("김관부장");		 
         $("#secondord").val("한산");		 
		 break;		
	 }
}
function exe_firstordman()
{
     var tmp=$('#firstordman').val();
	 switch (tmp) {
		 case '고범섭' :
         $("#firstordman").val("고범섭소장");		 		 
         $("#firstordmantel").val("010-6774-6211");		 
         $("#firstord").val("오티스");			 		 		 
         $("#secondord").val("우성");			 
		 break;		 
	 }
}

function exe_chargedman()
{
}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
</script>
	</body>
 </html>
