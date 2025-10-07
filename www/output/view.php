<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>


 <?php

 $file_dir = '../uploads/output';
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];
  if(isset($_REQUEST["separate_date"]))
        $separate_date=$_REQUEST["separate_date"];    // 현재 정렬모드 지정 
	     else
		        $separate_date="";    // 현재 정렬모드 지정  
  if(isset($_REQUEST["mode"]))
        $mode=$_REQUEST["mode"];    // 현재 정렬모드 지정 
	     else
		        $mode="";    // 현재 정렬모드 지정  			
 $year=$_REQUEST["year"];   // 년도 체크박스

 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from id11707003_chandj.output where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
	 
			  $item_num=$row["num"];
			  $con_num=$row["con_num"];
			  $item_outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];
			  $root=$row["root"];	  
			  $steel=$row["steel"];	  
			  $motor=$row["motor"];	 			  
			  $delivery=$row["delivery"];	
			  $regist_state=$row["regist_state"];	 			  
			  
 
     $image_name[0]   = $row["file_name_0"];
     $image_name[1]   = $row["file_name_1"];
     $image_name[2]   = $row["file_name_2"];
     $image_name[3]   = $row["file_name_3"];
     $image_name[4]   = $row["file_name_4"];
 
     $image_copied[0] = $row["file_copied_0"];
     $image_copied[1] = $row["file_copied_1"];
     $image_copied[2] = $row["file_copied_2"];
     $image_copied[3] = $row["file_copied_3"];
     $image_copied[4] = $row["file_copied_4"];
            
	     $img_name0 = $image_name[0];
	     $img_copied0 = "../uploads/output/".$image_copied[0]; 	
	     $img_name1 = $image_name[1];
	     $img_copied1 = "../uploads/output/".$image_copied[1]; 	
	     $img_name2 = $image_name[2];
	     $img_copied2 = "../uploads/output/".$image_copied[2]; 		
	     $img_name3 = $image_name[3];
	     $img_copied3 = "../uploads/output/".$image_copied[3]; 	
	     $img_name4 = $image_name[4];
	     $img_copied4 = "../uploads/output/".$image_copied[4]; 			 
     
 ?>
 <!DOCTYPE HTML>
 <html><head> 
 <meta charset="utf-8">
   <title> 통합 정보 시스템 </title> 
   <link  rel="stylesheet" type="text/css" href="../css/common.css">
   <link  rel="stylesheet" type="text/css" href="../css/output.css">

   
   </head>
 
   <body>
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  
   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&num=<?=$num?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" enctype="multipart/form-data"> 
  <?php
	}
  ?>	   
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
   </div>  
  <div id="containers">
  <br><br>
  <div id="work_col3"> <h2> &nbsp; &nbsp; 자재 출고 등록  </h2>
  	 <?php
	 		 $aryreg=array();
	     switch ($regist_state) {
			case   "1"             : $aryreg[0] = "checked" ; break;
			case   "2"             :$aryreg[1] =  "checked" ; break;
			case   "3"             :$aryreg[2] =  "checked"; break;
			default: break;
		}	
			 ?>
			 
			 	 <div id="regist_state">   
	<b> 접수상태 : &nbsp;   등록          <input  type="radio" <?=$aryreg[0]?> name=regist_state value="1">
			    &nbsp;   접수         <input  type="radio" <?=$aryreg[1]?>  name=regist_state value="2">
		        &nbsp;   완료       <input  type="radio" <?=$aryreg[2]?> name=regist_state value="3">	  </b>
	 </div>
	      <div class="clear"></div>	
	 <div id="sero1"> 출 고 일 :  </div>	 
	 <div id="sero2"> <input type="date" id="outdate" name="outdate" value="<?=$item_outdate?>" size="14" disabled placeholder="출 고 일" > </div> 
	 <div id="sero_new1"> &nbsp; &nbsp;   회사구분 :  &nbsp; &nbsp; </div>	
	 <div id="sero_new2"> 
	  <?php
	    if($root==Null) 			 
			$root="주일";
			   ?>	  
	 <?php
	    if($root=="주일") {
			 ?>
			주일               <input type="radio"  disabled checked name=root id=root value="주일"  >
			&nbsp;   경동<input type="radio" name=root  disabled  id=root value="경동"  >	  
			<?php
             		}    ?>
	 <?php
	    if($root=="경동") {
			 ?>
			주일               <input type="radio"  disabled  name=root id=root value="주일"  >
			&nbsp;   경동<input type="radio" checked  disabled name=root id=root value="경동"  >	  
			<?php
             		}    ?>					
	              </div>
     <div class="clear"></div>	
	 <div id="sero3"> 접 수 일 :  </div>
	 <div id="sero4"> <input type="date" id="indate" name="indate" value="<?=$item_indate?>"  disabled size="14" placeholder="접수일" > </div> 
 <div id="sero_new1">  &nbsp; &nbsp;   발주표시 :  &nbsp; &nbsp; </div>	
	 <div id="sero_new3">  	   
				 <?php
			if($steel=="1") {
				 ?>
				 <input type="checkbox" name=steel  disabled checked value="1" > 절곡발주  
				<?php
						}    ?>	   
		 <?php
			if($steel!="1") {
				 ?>
				 <input type="checkbox" name=steel  disabled value="1"  > 절곡발주   
				<?php
						}    ?>	  	
	              </div>    
	 <div id="sero_new3">  	   
				 <?php
			if($motor=="1") {
				 ?>
				 <input type="checkbox" name=motor disabled  checked value="1" > 모터발주  
				<?php
						}    ?>	   
		 <?php
			if($motor!="1") {
				 ?>
				 <input type="checkbox" name=motor  disabled value="1"  > 모터발주   
				<?php
						}    ?>	  	
	              </div>    
				  
	<div class="clear"></div>
	 <div id="sero3"> 현 장 명 :  </div>
	 <div id="sero5"> <input type="text" id="outworkplace"  disabled name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$item_outworkplace?>" size="40" placeholder="주일은 현장명 경동은 업체명"> 	 
     <button type="button" id="searchplace" class="button button2" > 검색 </button> 	 	 
	 </div> 
	 <div id="sero333">  <a href="../make/write_form.php?mode=take&outputnum=<?=$item_num?>&outdate=<?=$item_outdate?>&item_indate=<?=$item_indate?>&item_outworkplace=<?=$item_outworkplace?>&item_receiver=<?=$item_receiver?>&item_phone=<?=$item_phone?>&item_outputplace=<?=$item_outputplace?>&item_comment=<?=$item_comment?>&item_orderman=<?=$item_orderman?>&delivery=<?=$delivery?>&callback=0"><img src="../img/movetoscreenmake.png"></a> 
     &nbsp;&nbsp;
     <a href="../analysis/write_form.php?mode=take&outputnum=<?=$item_num?>&outdate=<?=$item_outdate?>&item_indate=<?=$item_indate?>&item_outworkplace=<?=$item_outworkplace?>&item_receiver=<?=$item_receiver?>&item_phone=<?=$item_phone?>&item_outputplace=<?=$item_outputplace?>&item_comment=<?=$item_comment?>&item_orderman=<?=$item_orderman?>&delivery=<?=$delivery?>&callback=0"><img src="../img/costanalysis.jpg"></a> 

	 </div>	 
	 <div id="displaysearch" style="display:none"> 
	 
	 </div>
     <div class="clear"></div>
	 

	 <div id="sero31"> 발주자 :  </div>	 
	 <div id="sero6"> <input type="text" name="orderman" value="<?=$item_orderman?>"  disabled size="14" placeholder="발주자(주일/경동 직원)" > </div> 
	 <div id="sero33">  <a href="../egimake/write_form.php?mode=take&outputnum=<?=$item_num?>&outdate=<?=$item_outdate?>&item_indate=<?=$item_indate?>&item_outworkplace=<?=$item_outworkplace?>&item_receiver=<?=$item_receiver?>&item_phone=<?=$item_phone?>&item_outputplace=<?=$item_outputplace?>&item_comment=<?=$item_comment?>&item_orderman=<?=$item_orderman?>"><img src="../img/movetoegimake.png"></a>  	    
	       &nbsp;&nbsp; <a href="../motor/write_form.php?outputnum=<?=$item_num?>"><img src="../img/movetomotor.jpg"></a>  </div>	    

	 
	<div class="clear"></div>
	 <div id="sero32"> 수신처(작업반장 or 업체) :  </div>
	 <div id="sero7"> <input type="text" id="receiver"  name="receiver" value="<?=$item_receiver?>"  disabled size="20" placeholder="수신처 (작업반장 or 업체)" onkeydown="JavaScript:Enter_CheckTel();" > 
     <button type="button" id="searchtel" class="button button2" > 전화번호검색 </button> 	 	 	 
	 </div>      
	 
	 <div id="displaysearchworker" style="display:none"> 	 
	 </div>	 
	 <div class="clear"></div>
	 <div id="sero3"> 수신처주소 :  </div>
	 <div id="sero8"> <input type="text" id="outputplace" name="outputplace" value="<?=$item_outputplace?>"  disabled size="60" placeholder="현장주소 또는 납품처 주소 or 화물 영업소" > </div> 
     <div class="clear"></div>
	
	   <?php
	    if($delivery==null) 			 
			$delivery="상차(선불)";
			   ?>	  
	 <?php
	 
 
	 $ary=array();
	    switch ($delivery) {
			case   "상차(선불)"             : $ary[0] = "checked" ; break;
			case   "상차(착불)"              :$ary[1] =  "checked" ; break;
			case   "경동화물(선불)"          :$ary[2] =  "checked"; break;
			case   "경동화물(착불)"          :$ary[3] =  "checked"; break;
			case   "경동택배(선불)"          :$ary[4] =  "checked"; break;
			case   "경동택배(착불)"          :$ary[5] =  "checked"; break;
			case  "직접배차"                 :$ary[6] =  "checked"; break;
			case  "직접수령"                 :$ary[7] =  "checked"; break;
			case  "대신화물(선불)"           :$ary[8] =  "checked"; break;
			case  "대신화물(착불)"           :$ary[9] =  "checked"; break;
			case  "대신택배(선불)"           :$ary[10] =  "checked"; break;
			case  "대신택배(착불)"           :$ary[11] = "checked" ; break;
		}	 
			 ?>
			 <div id="serodelivery1"> 
			 	 배송방식 : 
			&nbsp;   상차(선불)           <input  type="radio" <?=$ary[0]?> name=delivery value="상차(선불)"  >
			&nbsp;   상차(착불)           <input  type="radio" <?=$ary[1]?>  name=delivery value="상차(착불)"  >
			&nbsp;   경동화물(선불)       <input  type="radio" <?=$ary[2]?> name=delivery value="경동화물(선불)"  >	  
			&nbsp;   경동화물(착불)       <input  type="radio" <?=$ary[3]?> name=delivery value="경동화물(착불)"  >	  
			&nbsp;   경동택배(선불)       <input  type="radio" <?=$ary[4]?> name=delivery value="경동택배(선불)"  >	  
			&nbsp;   경동택배(착불)       <input  type="radio" <?=$ary[5]?> name=delivery value="경동택배(착불)"  >	
            </div>			
			 <div id="serodelivery2"> 
			&nbsp;   직접배차             <input  type="radio" <?=$ary[6]?> name=delivery value="직접배차"  >	  
			&nbsp;   직접수령             <input  type="radio" <?=$ary[7]?> name=delivery value="직접수령"  >	  
			&nbsp;   대신화물(선불)       <input  type="radio" <?=$ary[8]?> name=delivery value="대신화물(선불)"  >	  
			&nbsp;   대신화물(착불)       <input  type="radio" <?=$ary[9]?> name=delivery value="대신화물(착불)"  >	  
			&nbsp;   대신택배(선불)       <input  type="radio" <?=$ary[10]?> name=delivery value="대신택배(선불)"  >	  
			&nbsp;   대신택배(착불)       <input  type="radio" <?=$ary[11]?> name=delivery value="대신택배(착불)"  >  
            </div>	
	     <div class="clear"></div>	 
	 <div id="sero3"> 연락처 :  </div>
	 <div id="sero9"> <input type="text" name="phone" value="<?=$item_phone?>" size="20" placeholder="작업반장 또는 업체 연락처"  disabled> </div> 
	 <div id="sero11"> 공사번호 :  </div>
	 <div id="sero12"> <input type="text" id="con_num" name="con_num" value="<?=$con_num?>" size="5" placeholder="공사번호" disabled>   <a href="http://5130.co.kr/work/view.php?num=<?=$con_num?>" target="_blank" >    <?=$con_num?></a> </div>
	 <div class="clear"></div>
	 <div id="sero3"> 비 고 :  </div>
	 <div id="sero10"> <textarea  class="autosize" rows="3" cols="100" name="comment" placeholder="기타 코멘트 남겨주세요." disabled style="overflow:hidden;  height:auto;"
                ><?=$item_comment?></textarea></div>
     <div class="clear"></div>      
   	 <div class="serofileview">	첨부파일 1: &nbsp; <a href="<?=$img_copied0?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name0?> </b> </a> </div>
     <div class="clear"></div> 
	<div class="serofileview">	첨부파일 2: &nbsp; <a href="<?=$img_copied1?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name1?> </b> </a> </div>
     <div class="clear"></div>  
   	 <div class="serofileview">	첨부파일 3: &nbsp; <a href="<?=$img_copied2?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name2?> </b> </a> </div>
     <div class="clear"></div>  
	 <div class="serofileview">	첨부파일 4: &nbsp; <a href="<?=$img_copied3?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name3?> </b> </a> </div>
     <div class="clear"></div>  
   	 <div class="serofileview">	발주서출력파일: &nbsp; <a href="<?=$img_copied4?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name4?> </b> </a> </div>
     <div class="clear"></div>  	 

            <div class="clear"></div>      <div class="clear"></div>      <br><br><br>	 
						   <div id="write_button_renew2">    
						   <a href="list.php?&mode=search&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&asprocess=<?=$asprocess?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"> <img src="../img/list.png"></a>&nbsp;   

					  <?php
						if(isset($_SESSION["userid"])) {
						if($_SESSION["level"]>=1 )
					/* 	if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" ||
							   $_SESSION["level"]==1 )	 */	   
							{
					  ?>
						<a href="write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"><img src="../img/modify.png"></a>&nbsp;
						<a href="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>')"><img src="../img/delete.png"></a>&nbsp;

		 <?php  	}
					 ?>
						<a href="write_form.php"><img src="../img/write.png"></a>&nbsp;
						 <a href="#" onclick="window.open('print.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>','발주내용 인쇄','left=20,top=20, scrollbars=yes, toolbars=no,width=1300,height=1450');" border="0" "> <img src="../img/printorder.png"></a>&nbsp;						

     			<?php
	     			if($_SESSION["name"] =="김보곤"||$_SESSION["name"] =="황규선" || $_SESSION["name"] =="서재호" || $_SESSION["name"] =="정근영"|| $_SESSION["name"] =="안미희") 
                        print '
						&nbsp; <a href="process_DB.php?code=1&num=' . $num . '&search=' . $search . '&find=' . $find . '&fromdate=' . $fromdate . '&todate=' . $todate . '&separate_date=' . $separate_date . '"><img src="../img/register.png"></a> 
						&nbsp; <a href="process_DB.php?code=2&num=' . $num . '&search=' . $search . '&find=' . $find . '&fromdate=' . $fromdate . '&todate=' . $todate . '&separate_date=' . $separate_date . '"><img src="../img/done.png"></a> ';					
						?>												
						</div>	
						
<!-- 일부분 부분-->
 <input type="button" onclick="partShot();" value="이미지 프린트">  						
      </div>	 <!-- end of col3 -->			

	   	</div>		 <!-- end of containers  스크린샷 구간 -->		
	     
 <?php
	}
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>  
	

   
 <!-- 이하 생략 -->

	<div class="clear"></div>
<!-- 결과화면을 그려줄 canvas -->
<canvas id="canvas" width="700" height="600"style="border:1px solid #d3d3d3; display:none;"></canvas>	

  </div> <!-- end of content -->
 </div> <!-- end of wrap -->
 </body>

 <script language="javascript">

$(function () {
            $("fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
			
});

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

function del(href) 
     {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          }
     }
	 
function partShot() {
//특정부분 스크린샷
html2canvas(document.getElementById("containers"))
//id container 부분만 스크린샷
.then(function (canvas) {
//jpg 결과값
drawImg(canvas.toDataURL('image/jpeg'));
//이미지 저장
saveAs(canvas.toDataURL(), 'output.jpg');
}).catch(function (err) {
console.log(err);
});
}

function drawImg(imgData) {
console.log(imgData);
//imgData의 결과값을 console 로그롤 보실 수 있습니다.
return new Promise(function reslove() {
//내가 결과 값을 그릴 canvas 부분 설정
var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');
//canvas의 뿌려진 부분 초기화
ctx.clearRect(0, 0, canvas.width, canvas.height);

var imageObj = new Image();
imageObj.onload = function () {
ctx.drawImage(imageObj, 10, 10);
//canvas img를 그리겠다.
};
imageObj.src = imgData;
//그릴 image데이터를 넣어준다.

}, function reject() { });

}
function saveAs(uri, filename) {
var link = document.createElement('a');
if (typeof link.download === 'string') {
link.href = uri;
link.download = filename;
document.body.appendChild(link);
link.click();
document.body.removeChild(link);
} else {
window.open(uri);
}
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


$("textarea.autosize").on('keydown keyup', function () {    // textarea 높이 자동조절 
  $(this).height(1).height( $(this).prop('scrollHeight')+12 );	
});

</script>
</html>    
