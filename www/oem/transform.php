<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>

<?php

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:http://8440.co.kr/login/logout.php"); 
         exit;
   }   

//header("Refresh:0");  // reload refresh   
 ?>


<meta charset="utf-8">
 <?php

  function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}
  
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
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

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 
 if(isset($_REQUEST["output_check"])) 
	 $output_check=$_REQUEST["output_check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
	if(isset($_POST["output_check"]))   
         $output_check=$_POST["output_check"]; // 미출고 리스트 POST사용  
	 else
		 $output_check='0';
	 
 if(isset($_REQUEST["team_check"])) 
	 $team_check=$_REQUEST["team_check"]; // 시공팀미지정
   else
	if(isset($_POST["team_check"]))   
         $team_check=$_POST["team_check"]; // 시공팀미지정
	 else
		 $team_check='0';	
	 
   if(isset($_REQUEST["plan_output_check"])) 
	 $plan_output_check=$_REQUEST["plan_output_check"]; // 출고예정`
   else
	if(isset($_POST["plan_output_check"]))   
         $plan_output_check=$_POST["plan_output_check"]; // 출고예정  
	 else
		 $plan_output_check='0';

 $yearcheckbox=$_REQUEST["yearcheckbox"];   // 년도 체크박스
 $year=$_REQUEST["year"];   // 년도 체크박스
 
  if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
	 
if(isset($_REQUEST["cursort"])) 
	 $cursort=$_REQUEST["cursort"]; // 미실측리스트
   else
	if(isset($_POST["cursort"]))   
         $cursort=$_POST["cursort"]; // 미실측리스트
	 else
		 $cursort='0';		  


if(isset($_REQUEST["sortof"])) 
	 $sortof=$_REQUEST["sortof"]; // 미실측리스트
   else
	if(isset($_POST["sortof"]))   
         $sortof=$_POST["sortof"]; // 미실측리스트
	 else
		 $sortof='0';		 
	 
 if(isset($_REQUEST["stable"])) 
	 $stable=$_REQUEST["stable"]; // 미실측리스트
   else
	if(isset($_POST["stable"]))   
         $stable=$_POST["stable"]; // 미실측리스트
	 else
		 $stable='0';	
 
$outputdate = date("Y-m-d", time());

if($outputdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
} 
 
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  try{
      $sql = "select * from mirae8440.oem where num = ? ";
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

              $num=$row["num"];

			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $worker=$row["worker"];
			  $secondord=$row["secondord"];
			  $firstord=$row["firstord"];
			  $startday=$row["startday"];
			  $chargedman=$row["chargedman"];
			  $chargedmantel=$row["chargedmantel"];			  
			  $memo=$row["memo"];			  
			  $deadline=$row["deadline"];			  
			  $delivery=$row["delivery"];			  
			  $delipay=$row["delipay"];			  
			  
			  $delitext = $delivery . ' ' . $delipay;			  
			  
			  $type1=$row["type1"];			  
			  $inseung1=$row["inseung1"];			  
			  $car_insize1=$row["car_insize1"];				  
			  $su=(int)$row["su"];			  
			  $lc_su=(int)$row["lc_su"];			  
			  $etc_su=(int)$row["etc_su"];			  
			  $air_su=(int)$row["air_su"];		
			  
			  $first_item1=$row["first_item1"];
			  $first_item2=$row["first_item2"];
			  $first_item3=$row["first_item3"];
			  $first_item4=$row["first_item4"];
			  $second_item1=$row["second_item1"];
			  $second_item2=$row["second_item2"];
			  $second_item3=$row["second_item3"];
			  $second_item4=$row["second_item4"];  			 

			  $third_item1=$row["third_item1"];	
			  $third_item2=$row["third_item2"];	
			  $third_item3=$row["third_item3"];	
			  $third_item4=$row["third_item4"];	
			  
			  $forth_item1=$row["forth_item1"];	
			  $forth_item2=$row["forth_item2"];	
			  $forth_item3=$row["forth_item3"];	
			  $forth_item4=$row["forth_item4"];			  
			  
			  $fifth_item1=$row["fifth_item1"];	
			  $fifth_item2=$row["fifth_item2"];	
			  $fifth_item3=$row["fifth_item3"];	
			  $fifth_item4=$row["fifth_item4"];	
			  
			  $sixth_item1=$row["sixth_item1"];	
			  $sixth_item2=$row["sixth_item2"];	
			  $sixth_item3=$row["sixth_item3"];	
			  $sixth_item4=$row["sixth_item4"];			  

			  $seventh_item1=$row["seventh_item1"];	
			  $seventh_item2=$row["seventh_item2"];	
			  $seventh_item3=$row["seventh_item3"];	
			  $seventh_item4=$row["seventh_item4"];	
			  
			  $eighth_item1=$row["eighth_item1"];	
			  $eighth_item2=$row["eighth_item2"];	
			  $eighth_item3=$row["eighth_item3"];	
			  $eighth_item4=$row["eighth_item4"];			  

			  $ninth_item1=$row["ninth_item1"];	
			  $ninth_item2=$row["ninth_item2"];	
			  $ninth_item3=$row["ninth_item3"];	
			  $ninth_item4=$row["ninth_item4"];	
			  
			  $tenth_item1=$row["tenth_item1"];	
			  $tenth_item2=$row["tenth_item2"];	
			  $tenth_item3=$row["tenth_item3"];	
			  $tenth_item4=$row["tenth_item4"];		

			$type2=$row["type2"];
			$type3=$row["type3"];
			$type4=$row["type4"];
			$type5=$row["type5"];
			$type6=$row["type6"];
			$type7=$row["type7"];
			$type8=$row["type8"];
			$type9=$row["type9"];
			$type10=$row["type10"];			  
			$inseung2=$row["inseung2"];
			$inseung3=$row["inseung3"];
			$inseung4=$row["inseung4"];
			$inseung5=$row["inseung5"];
			$inseung6=$row["inseung6"];
			$inseung7=$row["inseung7"];
			$inseung8=$row["inseung8"];
			$inseung9=$row["inseung9"];
			$inseung10=$row["inseung10"];
			$car_insize2=$row["car_insize2"];
			$car_insize3=$row["car_insize3"];
			$car_insize4=$row["car_insize4"];
			$car_insize5=$row["car_insize5"];
			$car_insize6=$row["car_insize6"];
			$car_insize7=$row["car_insize7"];
			$car_insize8=$row["car_insize8"];
			$car_insize9=$row["car_insize9"];
			$car_insize10=$row["car_insize10"];  	
			$comment1=$row["comment1"];
			$comment2=$row["comment2"];
			$comment3=$row["comment3"];
			$comment4=$row["comment4"];
			$comment5=$row["comment5"];
			$comment6=$row["comment6"];
			$comment7=$row["comment7"];
			$comment8=$row["comment8"];
			$comment9=$row["comment9"];
			$comment10=$row["comment10"]; 			
		  
              $workday=trans_date($workday);
              $startday=trans_date($startday);
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
	
		
	$text=array();			
	$item=array();			
	$spec=array();			
	$carsize=array();			
	$item_memo=array();			
	$textnum=array();	
	$textset=array();	

    $text[0] = $type1;	
    $carsize[0] = $car_insize1;	
    $textnum[0] = $lc_su;	
    $item_memo[0] = $memo;
    $textset[0] = 'SET';	

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
?>


   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 출고증 자료 입력화면 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css">
   <link  rel="stylesheet" type="text/css" href="../css/work.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    
   </head>
 
   <body>
   <div id="wrap">
	<form  name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>"enctype="multipart/form-data"> 
 
  <div id="content">
 	   
<div id="work_col2"> 

<div id="estimate_text2" style="width:980px;"> 

       <input type="hidden" id="delitext" name="delitext" value="<?=$delitext?>"  >
       <input type="hidden" id="deadline" name="deadline" value="<?=$deadline?>"  >       
       <input type="hidden" id="startday" name="startday" value="<?=$startday?>"  >       
         
		<div class="sero1" style="width:500px;"> <H1> 외주발주(서한 컴퍼니) 출고증 </div>

       <div id="write_button_renew"><input type="image" src="../img/print.png">&nbsp;&nbsp;&nbsp;&nbsp;
	   <button  onclick="window.close();" value="창 닫기" > 창 닫기 </button>
				</div> <br>		
		
		<br>
		<br>
		<br>
      <div class="sero1" style="color:blue;"> (귀중) : </div> 
         <div class="sero2"><input type="text" name="firstord" value="<?=$firstord?>" size="30" placeholder="(귀중)" >	</div>	  	
     <div class="clear"> </div> 				
      <div class="sero1" style="color:red;"> 하차일시 : </div> 
         <div class="sero2"><input type="text" name="outputdate" value="<?=$outputdate?>" size="30" placeholder="하차일시" required>	</div>	  	
     <div class="clear"> </div> 		
      <div class="sero1"> 발주(업체) : </div> 
         <div class="sero2"><input type="text" name="secondord" value="<?=$secondord?>" size="50" placeholder="발주처(업체명)" required>	</div>	  			 
     <div class="clear"> </div> 		
      <div class="sero1"> 현장명 : </div> 
         <div class="sero2"><input type="text" name="workplacename" value="<?=$workplacename?>" size="50" placeholder="현장명" required>	</div>	  	
     <div class="clear"> </div> 
         <div class="sero1"> 현장주소 : </div> 
         <div class="sero2"><input type="text" name="address" value="<?=$address?>" size="50" placeholder="현장주소" ></div>
		 <div class="clear"> </div> 
		
        <div class="sero1">  받으실분 : </div>   
	    <div class="sero2"> <input type="text" name="chargedman" value="<?=$chargedman?>" size="10" placeholder="담당자" > </div>
		<div class="sero1" style="width:130px;" >  받으실분 연락처 : </div> 
	    <div class="sero2"> <input type="text" name="chargedmantel"  id="chargedmantel"  value="<?=$chargedmantel?>" size="10"  placeholder="담당자 연락처"></div> 
     
        <div class="clear"> </div> 	
        <div class="space"> </div> 	
        <div class="clear"> </div> 	
    <?php		
    print  '   <div class="sero2" style="width:370px;color:red;">  </div>';
    print  '   <div class="clear"> </div>';

	for($i=0; $i<=9; $i=$i+1)
	{
	 	
     
     print	'	<div class="sero1"> ' . ($i+1) . '번째줄 : </div> ';
     print  '   <div class="sero2" style="width:100px;"> <input type="text" name="text[]" value="' . $text[$i] . '" size="10" placeholder="타입(Type)">  </div>';
     print  '   <div class="sero2" style="width:100px;margin-left:10px"> <input type="text" name="item[]" value="' . $item[$i] . '" size="10" placeholder="품목">  </div>';
     print  '   <div class="sero2" style="width:100px;margin-left:10px"> <input type="text" name="spec[]" value="' . $spec[$i] . '" size="10" placeholder="규격">  </div>';
     print  '   <div class="sero2" style="margin-left:30px;width:20px;"><input type="text" name="textnum[]" value="' . $textnum[$i]  . '" size="1" placeholder="수량">  </div>';
     print  '   <div class="sero2" style="margin-left:30px;width:20px;"><input type="text" name="textset[]" value="' . $textset[$i]  . '" size="1"  placeholder="단위">  </div>';
     print  '   <div class="sero2" style="margin-left:40px;width:100px;"><input type="text" name="carsize[]" value="' . $carsize[$i]  . '" size="10"  placeholder="Car insize">  </div>';
     print  '   <div class="sero2" style="margin-left:10px;width:150px;"><input type="text" name="item_memo[]" value="' . $item_memo[$i]  . '" size="30"  placeholder="비고">  </div>';
     print  '   <div class="clear"> </div> 	';
	}
?>		
		
        <div class="clear"> </div> 					   
	   </div>	    		   
	   </div> 		
	</form>
 </div>

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
