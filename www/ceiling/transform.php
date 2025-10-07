<?php\nrequire_once __DIR__ . '/../common/functions.php';
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 $WebSite = "http://8440.co.kr/";
 
 $title_message = '본천장&조명천장 출고증';

?>

<?php include getDocumentRoot() . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:" . $WebSite . "login/login_form.php");
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

  try{
      $sql = "select * from mirae8440.ceiling where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];

      $copied_file_0 = "../uploads/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/". $row["file_copied_1"];
	 }

		include '_rowDB.php';		

			// 하차일시
			
			if($workday == null)
			{
				$outputdate = date("Y-m-d", time());

				if($outputdate!="") {
					$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
					$outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
				} 
			}
			else
			{
				$outputdate = $workday;

				if($outputdate!="") {
					$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
					$outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
				} 
			}				
	  
			  $su=(int)$row["su"];			  
			  $bon_su=(int)$row["bon_su"];			  
			  $lc_su=(int)$row["lc_su"];			  
			  $etc_su=(int)$row["etc_su"];			  
			  $air_su=(int)$row["air_su"];			  

			  
              $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lclbending_date=trans_date($lclbending_date);
		      $lclwelding_date=trans_date($lclwelding_date);
		      $lcpainting_date=trans_date($lcpainting_date);
		      $lcassembly_date=trans_date($lcassembly_date);
		      $main_draw=trans_date($main_draw);			
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);										

		      $order_date1=trans_date($order_date1);					   
		      $order_date2=trans_date($order_date2);					   
		      $order_date3=trans_date($order_date3);					   
		      $order_date4=trans_date($order_date4);					   
		      $order_input_date1=trans_date($order_input_date1);					   
		      $order_input_date2=trans_date($order_input_date2);					   
		      $order_input_date3=trans_date($order_input_date3);					   
		      $order_input_date4=trans_date($order_input_date4);		
			  
		  		      						
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

			default: break;
		}	
		
	$text=array();			
	$textnum=array();	
	$textset=array();	
	$textmemo=array();	
	
	$text[3]="";
	$text[4]="";
	
    $i=0;
	
	if($su!==0 && $bon_su!='') 	{
		 $text[$i]="본천장: " . $bon_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="SET";
	                  }
	if($su!==0 && $lc_su!='') 	{		
	     $text[$i] .= "LC: " . $lc_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="SET";
	                  }	
	if($su!==0 && $etc_su!='') 	{		
	     $text[$i] .= "기타: " . $etc_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="EA";
	                  }					  
	if($su!==0 && $lc_su!==0 && $bon_su!==0  && $etc_su!==0 ) 	{
	     $text[$i]="본천장: " . $bon_su . " " .  "LC: " . $lc_su . " "  . "기타: " . $etc_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="SET";
	                  }			  
  
					  		
       $text[$i] = $type . "  " .  $text[$i] . " " . $car_insize;		 ;

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  
  
?>

<title>  <?=$title_message?> </title>
  
<form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>" enctype="multipart/form-data">

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
        <input type="text" name="address" id="address" value="<?=$address?>" class="form-control" placeholder="현장주소" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >발주처 </div>
        <input type="text" name="secondord" id="secondord" value="<?=$secondord?>" class="form-control" placeholder="발주처">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >받으실분 </div>
        <input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" placeholder="담당자">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width:150px;">받으실분 연락처 </div>
        <input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control" placeholder="담당자 연락처">
    </div>
</div>


    <?php
    for ($i = 0; $i <= 11; $i = $i + 1) {
        ?>
	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center" style="width:150px;"><?=$i+1?>번째줄 </span>
            <input type="text" name="text[]" id="text<?=$i?>" value="<?=$text[$i]?>"  class="input-group-text text-left" style="text-align:left; width: 700px;"  >            
            <input type="text" name="textnum[]" value="<?=$textnum[$i]?>" size=2 class="text-center" >            
            <input type="text" name="textset[]" value="<?=$textset[$i]?>" size=2  class="text-center" >
			<input type="text" name="textmemo[]" value="<?=$textmemo[$i]?>" size="10"  class="text-center" >
			
			</div>
		</div>	
    <?php } ?>
</div>
</div>            
</div>            
</form>
    
</body>
</html>

<script>
  $(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});
$(function () {
	/*            
            $("#demand").datepicker({ dateFormat: 'yy-mm-dd'});
			*/
			
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
