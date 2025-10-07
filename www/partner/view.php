<meta charset="utf-8">
 
 <?php
 session_start(); 
 $file_dir = '../uploads/'; 
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
  $year=$_REQUEST["year"];   // 년도 체크박스


 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
	 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.work where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
  $checkstep=$row["checkstep"];
  $workplacename=$row["workplacename"];
  $address=$row["address"];
  $firstord=$row["firstord"];
  $firstordman=$row["firstordman"];
  $firstordmantel=$row["firstordmantel"];
  $secondord=$row["secondord"];
  $secondordman=$row["secondordman"];
  $secondordmantel=$row["secondordmantel"];
  $chargedman=$row["chargedman"];
  $chargedmantel=$row["chargedmantel"];
  $orderday=$row["orderday"];
  $measureday=$row["measureday"];
  $drawday=$row["drawday"];
  $deadline=$row["deadline"];
  $workday=$row["workday"];
  $worker=$row["worker"];
  $endworkday=$row["endworkday"];
  $material1=$row["material1"];
  $material2=$row["material2"];
  $material3=$row["material3"];
  $material4=$row["material4"];
  $material5=$row["material5"];
  $material6=$row["material6"];
  $widejamb=$row["widejamb"];
  $normaljamb=$row["normaljamb"];
  $smalljamb=$row["smalljamb"];
  $memo=$row["memo"];
  $regist_day=$row["regist_day"];
  $update_day=$row["update_day"];
  
  $delivery=$row["delivery"];
  $delicar=$row["delicar"];
  $delicompany=$row["delicompany"];
  $delipay=$row["delipay"];
  $delimethod=$row["delimethod"];
  $demand=$row["demand"];
  $startday=$row["startday"];
  $testday=$row["testday"];
  $hpi=$row["hpi"];  
  $filename1=$row["filename1"];
  $filename2=$row["filename2"];
  $imgurl1="../imgwork/" . $filename1;
  $imgurl2="../imgwork/" . $filename2;   

    $designer=$row["designer"];  
                $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") 
			  {
			      $draw_done = "OK";	
					if($designer!='')
						 $draw_done = $designer ;
			  }  
  

		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";		      
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";		
		      if($startday!="0000-00-00" and $startday!="1970-01-01" and $startday!="")  $startday = date("Y-m-d", strtotime( $startday) );
					else $startday="";	
		      if($testday!="0000-00-00" and $testday!="1970-01-01" and $testday!="")  $testday = date("Y-m-d", strtotime( $testday) );
					else $testday="";						
					
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

 <title> 미래기업 쟘공사 관리시스템 </title>
 </head>
  <body>
   <style>
    .rotated {
  transform: rotate(90deg);
  -ms-transform: rotate(90deg); /* IE 9 */
  -moz-transform: rotate(90deg); /* Firefox */
  -webkit-transform: rotate(90deg); /* Safari and Chrome */
  -o-transform: rotate(90deg); /* Opera */
}
</style>
       <div  class="container-fluid">
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
	<?=$_SESSION["nick"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>
</h1> 
</div>
</div>
</div>
<br> 
			<div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<h1 class="display-2  text-left">
  <input type="button" class="btn btn-secondary btn-lg " value="목록으로 이동" onclick="javascript:move_url('index.php?check=<?=$check?>');"> </h1>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  </div>  
<br>
<br>
			  <div  class="container">
			<div class="row">

	 <H1  class="display-4 font-center text-center" > 미래기업 쟘(Jamb) 현장 </H1> 
 </div>
			<br>
			
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 		
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 	
			<div class="row">

		         <h1 class="display-5 font-center text-left"> 		   


       현장명 :       <?=$workplacename?>
     <br>
     <br>
	 	<span style="color:blue;font-weight:bold;"> 출고예정일(이미래 대리 협의) : </span> <span style="color:red;font-weight:bold;">  <?=$endworkday?>  </span>
<br> 	
<br> 	
 막판 HPI 형태 : <span style="color:brown;font-weight:bold;"> <?=$hpi?></span>
     <br> <br>
	 	
	재질 1 : 	<input disabled  type="text" value="<?=$material1?> <?=$material2?> " size="25"  >  <br>
	
	<?php
    $sum_mat1 = $material3 . $material4 ;	
	if($sum_mat1!="") 
        print ' 재질 2 : 	<input disabled  type="text"  value="' . $sum_mat1 . ' " size="25">  <br>' ;
    $sum_mat2 = $material5 . $material6 ;	
	if($sum_mat2!="") 	
        print ' 재질 3 : 	<input disabled  type="text"  value="' . $sum_mat2 . ' " size="25">  <br>' ;
	?>
	<br>
	 <div style="color:red; font-weight:bold;" >< 총 설치 수량> </div> <br>
	 
	 <?php
	  if($widejamb>0) 
           print '<div class="alert alert-info" role="alert">막판 : '. $widejamb . ' </div>';
	  if($normaljamb>0) 	   
           print '<div class="alert alert-warning" role="alert">막판(無) : ' . $normaljamb . '</div>';
	  if($smalljamb>0) 	   	   
           print '<div class="alert alert-danger" role="alert">쪽쟘 : ' . $smalljamb . '</div>';
	   ?>
			<br> 
	 
         현장주소 : <?=$address?>
<br>		 
<br>		 
		  원  청 :  <?=$firstord?>
<br>		 
원청담당(PM) : <?=$firstordman?>
<br>		 
		  연락처 : <a href="tel:<?=$firstordmantel?>">   <?=$firstordmantel?> </a>
<br>		 
<br>		 
	     발주처 : <?=$secondord?>
<br>		 		 
	    발주처담당 : <?=$secondordman?>
<br>		 		 		
	    연락처 : <a href="tel:<?=$secondordmantel?>"> <?=$secondordmantel?> </a>
<br>		 		 				
<br>		 		 				
     현장소장 : <?=$chargedman?>
	 <br>
     현장소장연락처 : <a href="tel:<?=$chargedmantel?>">  <?=$chargedmantel?> </a>
<br>		 		 				
<br>		 		 				
		<span style="color:blue;font-weight:bold;font-size:50px;"> 미래기업 소장 : <?=$worker?>  </span>	
<br>
<br>
  

        발주접수일: <input disabled  type="text" name="orderday" id="orderday" value="<?=$orderday?>" size="10" placeholder="발주접수일"  > 
		<br>
 실측일 : <input  value="<?=$measureday?>" size="10" placeholder="실측일" >	
 <br>
	     도면설계완료일 :   <input value="<?=$drawday?>" size="10" placeholder="도면설계일"> 		
		 <br>	   
		 도면설계자 :   <input value="<?=$draw_done?>" size="10" placeholder="도면설계자"> 		
		 <br>
		 <br>	 
        제품출고일: <?=$workday?>
		<br> 		 
<br>
 착공일:      <?=$startday?> <br>
 검사일 :   <input value=" <?=$testday?>" size="10" style="color:red;"> 
<br>
			
	<br>
<br>
  추가 메모(기타 사항) :<br> <textarea disabled rows="3" cols="38" name="memo" placeholder="추가적으로 기록할 내역" style="color:blue;" ><?=$memo?></textarea>
       <br>
		   
<br>

				<?php

					try{
					 $sql = "select * from mirae8440.voc where parent=?";
					 $stmh = $pdo->prepare($sql);  
					 $stmh->bindValue(1, $num, PDO::PARAM_STR);      
					 $stmh->execute();            
					  
					 $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
				  
					 $content=$row["content"];

					 }catch (PDOException $Exception) {
					   print "오류: ".$Exception->getMessage();
					 }  
				   
				 ?>



  협의사항 기록 : <br> <textarea disabled rows="10" cols="38" name="memo" placeholder="협의사항 기록 내역" style="color:brown;" ><?=$content?></textarea>
       <br>		   
		   
		   
	   </div>	    		
   
 	      <div class="row"> 	 <H1  class="display-4 font-center text-center" style="color:blue;margin-top:30px;" > 시공 전 사진 </H1>  </div>
	          <div class="clear"> </div> 
		  <div class='imagediv' >
	<?php
	     if($imgurl1!="") 
		    print '<img class="before_work" src="' . $imgurl1 . '" >';
	   ?>
	   </div>
	          <div class="clear"> </div> <br> <br> <div class="row"> </div>
   <div class="row"> 	 <H1  class="display-4 font-center text-center"  style="color:red;margin-top:30px;" > 시공 후 사진 </H1>  </div>
	          <div class="clear"> </div> 
    <div class='imagediv' >
		<?php
	     if($imgurl2!="") 
		  print '<img class="after_work" src="' . $imgurl2 . '" >';
	   ?>
	          <div class="clear"> </div> 
			  <br><br><br>       
      <span style="color:red;font-size:30px;margin-top:30px;"> &nbsp; </span>			  
	   </div>  
	          <div class="clear"> </div> 
		   
	   </div>	    		
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

function input_measureday(href)
     {
     if(confirm("실측일을 전송합니다.\n\n 정말 본사 전산에 입력 하시겠습니까?")) {
     document.location.href = href;		 
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
	 
function input_message(href)
{
     document.location.href = href;		 
}

function move_url(href)
{
     document.location.href = href;		 
}


// 사진 회전하기
function rotate_image()
{	
 var box = $('.imagediv');
 box.css('width','600px');
 box.css('height','850px');
 box.css('margin-top','200px');

		$('.before_work').addClass('rotated');
		$('.after_work').addClass('rotated');	

}

setTimeout(function() {
 // console.log('Works!');
 rotate_image();
}, 1500);
</script>

