<meta charset="utf-8">
 
 <?php
 session_start(); 
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"]; 
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();	 
?>	 
	 

 
 <!DOCTYPE HTML>
 <html>
 <head> 
 <meta charset="utf-8">
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/work.css">

 <title> 미래기업 통합정보시스템 </title>
  </head>
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://8440.co.kr/order/order.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
   <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>	
  <body>
   <div id="wrap">
	  
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
   </div>  
  <div id="content">
	   
	<span style="color:blue;font-size:30px;margin-left:100px;margin-top:30px;"> 시공전후 사진 갤러리 (최근20개 현장)  </span> <br>
			      
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
	<div id="vacancy" style="display:none">  </div>	 	   


<?php
 
 try{
     $sql = "select * from mirae8440.work order by workday desc";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
     $counter=0; 
     while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
 
		  $workplacename=$row["workplacename"];
		  $workday=$row["workday"];  // 제품 출고일
		  $worker=$row["worker"];
		  
		  $filename1=$row["filename1"];
		  $filename2=$row["filename2"];
		  $imgurl1="../imgwork/" . $filename1;
		  $imgurl2="../imgwork/" . $filename2;	
  
  if($filename1!='' && $counter<20) {   // 화면 표시 개수 제한

?>


	    파일명(전,후) : <?=$filename1?>  ,  <?=$filename1?>  
           <div class="clear"> </div>    <br>
        <span style="color:black;font-size:30px;margin-top:30px;"> <?=$workplacename?> ,   <?=$worker?>  </span> <br>		   <br>		   <br>		   
		   
      <span style="color:blue;font-size:30px;margin-top:30px;"> 시공전 사진  </span>
	          <div class="clear"> </div> 
	   <img src="<?=$imgurl1?>"> 
	          <div class="clear"> </div> 
      <span style="color:red;font-size:30px;margin-top:30px;"> 시공후 사진  </span>
	          <div class="clear"> </div> 
	   <img src="<?=$imgurl2?>">  
	          <div class="clear"> </div> 
			  <br><br><br>	       
      <span style="color:red;font-size:30px;margin-top:30px;"> &nbsp; </span>			  
	   
	 <?php
            $counter++;		
		      }	     
	   	}				
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }   
 ?>
	   

 
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

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;

    }
}
     function del(href) 
     {
		 var level=Number($('#session_level').val());
		 if(level>2)
		     alert("삭제하려면 관리자에게 문의해 주세요");
		 else {
         if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          } 
		 }

     }
	 
 function displayoutputlist(){
	 alert("dkdkdkd");
   $("#displayoutput").show(); 
   $("#displayoutput").load("./outputlist.php");	 	 
		 
	 }
function check_alert()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
			tmp="../load_alert.php";			
			$("#vacancy").load(tmp);     
		    var voc_alert=$("#voc_alert").val();	 
		    var ma_alert=$("#ma_alert").val();	 
 		if(name=='김진억' && voc_alert=='1') {			
			alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>'); 			
			tmp="../save_alert.php?voc_alert=0" + "&ma_alert=" + ma_alert;	
			$("#voc_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}
 		if(name=='조경임' && ma_alert=='1') {			
			alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조과장님 <br> <br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>'); 			
			tmp="../save_alert.php?ma_alert=0" + "&voc_alert=" + voc_alert;	
			$("#ma_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}											
}


// 5초마다 알람상황을 체크합니다.
	var timer;
	timer=setInterval(function(){
		check_alert();
	},5000); 
 	 
</script>
