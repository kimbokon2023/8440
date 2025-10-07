<meta charset="utf-8">
 
 <?php
 session_start(); 
 $file_dir = '../uploads/output';
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황

 $yearcheckbox=$_REQUEST["yearcheckbox"];   // 년도 체크박스
 $year=$_REQUEST["year"];   // 년도 체크박스

 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from chandj.output where num=?";
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
   <title> 주일기업 통합정보시스템 </title> 
   <link  rel="stylesheet" type="text/css" href="../css/common.css">
   <link  rel="stylesheet" type="text/css" href="../css/output.css">
   
   </head>
 
   <body>
   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  name="board_form" method="post" action="insert.php?mode=not" enctype="multipart/form-data"> 
  <?php
	}
  ?>	   
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
   </div>  
  <div id="content">
  <br><br><br><br>
   <div id="work_col3">    <h1> &nbsp; &nbsp; 자재 출고 등록  </h1>
  
	 <div id="sero1"> 출 고 일 :  </div>	 
	 <div id="sero2"> <input type="date" id="outdate" name="outdate" value="<?=$item_outdate?>" size="14" disabled  > </div> 
	 <div id="sero_new1">  &nbsp; &nbsp;   회사구분 :  &nbsp; &nbsp; </div>	
	 <div id="sero_new2">  	   
	  <?php
	    if($root==Null) 			 
			$root="주일";
			   ?>	  
	 <?php
	    if($root=="주일") {
			 ?>
			주일               <input type="radio" checked name=root value="주일"   disabled >
			&nbsp;   경동<input type="radio" name=root value="경동"  disabled >	  
			<?php
             		}    ?>
	 <?php
	    if($root=="경동") {
			 ?>
			주일               <input type="radio"  name=root value="주일"  disabled >
			&nbsp;   경동<input type="radio" checked name=root value="경동"  disabled >	  
			<?php
             		}    ?>					
	              </div>
     <div class="clear"></div>	
	 <div id="sero3"> 접 수 일 :  </div>
	 <div id="sero4"> <input type="date" id="indate" name="indate" value="<?=$item_indate?>" size="14" placeholder="접수일"  disabled > </div> 
 <div id="sero_new1">  &nbsp; &nbsp;   발주표시 :  &nbsp; &nbsp; </div>	
	 <div id="sero_new3">  	   
				 <?php
			if($steel=="1") {
				 ?>
				 <input type="checkbox" name=steel checked value="1" disabled > 절곡발주  
				<?php
						}    ?>	   
		 <?php
			if($steel!="1") {
				 ?>
				 <input type="checkbox" name=steel value="1"  disabled > 절곡발주   
				<?php
						}    ?>	  	
	              </div>    
	 <div id="sero_new3">  	   
				 <?php
			if($motor=="1") {
				 ?>
				 <input type="checkbox" name=motor checked value="1" disabled > 모터발주  
				<?php
						}    ?>	   
		 <?php
			if($motor!="1") {
				 ?>
				 <input type="checkbox" name=motor value="1"  disabled > 모터발주   
				<?php
						}    ?>	  	
	              </div>    
     <div class="clear"></div>				  
	 <div id="sero3"> 현 장 명 :  </div>
	 <div id="sero5"> <input type="text" name="outworkplace" value="<?=$item_outworkplace?>" size="40" placeholder="현장명"  disabled> </div> 
     <div class="clear"></div>
	 <div id="sero3"> 발주자 :  </div>
	 <div id="sero6"> <input type="text" name="orderman" value="<?=$item_orderman?>" size="14" placeholder="발주자(주일/경동 직원)"  disabled> </div> 
     <div class="clear"></div>
	 <div id="sero3"> 수신처(작업반장 or 업체) :  </div>
	 <div id="sero7"> <input type="text" name="receiver" value="<?=$item_receiver?>" size="20" placeholder="수신처 (작업반장 또는 업체)"  disabled> </div> 
     <div class="clear"></div>
	 <div id="sero3"> 수신처주소 :  </div>
	 <div id="sero8"> <input type="text" name="outputplace" value="<?=$item_outputplace?>" size="60" placeholder="현장주소 또는 납품처 주소"   disabled> </div> 
     <div class="clear"></div>
	   <?php
	    if($delivery==null) 			 
			$delivery="상차(선불)";
 
	 $ary=array();
	    switch ($delivery) {
			case   "상차(선불)"             :  $ary[0] = "checked" ; break;
			case   "상차(착불)"              :$ary[1] =  "checked" ; break;
			case   "경동화물(선불)"          :$ary[2] =  "checked"; break;
			case   "경동화물(착불)"          :$ary[3] =  "checked"; break;
			case   "경동택배(선불)"          :$ary[4] =  "checked"; break;
			case   "경동택배(착불)"          :$ary[5] =  "checked"; break;
			case  "직접수령"                 :$ary[6] =  "checked"; break;
			case  "대신화물(선불)"           :$ary[7] =  "checked"; break;
			case  "대신화물(착불)"           :$ary[8] =  "checked"; break;
			case  "대신택배(선불)"           :$ary[9] =  "checked"; break;
			case  "대신택배(착불)"           :$ary[10] = "checked" ; break;
		}	 
			 ?>
			 <div id="serodelivery1"> 
			 	 배송방식 : 
			&nbsp;   상차(선불)       <input type="radio" <?=$ary[0]?> name=delivery value="상차(선불)"  >
			&nbsp;   상차(착불)       <input type="radio" <?=$ary[1]?>        name=delivery value="상차(착불)"  >
			&nbsp;   경동화물(선불)       <input type="radio" <?=$ary[2]?> name=delivery value="경동화물(선불)"  >	  
			&nbsp;   경동화물(착불)       <input type="radio" <?=$ary[3]?> name=delivery value="경동화물(착불)"  >	  
			&nbsp;   경동택배(선불)       <input type="radio" <?=$ary[4]?> name=delivery value="경동택배(선불)"  >	  
			&nbsp;   경동택배(착불)       <input type="radio" <?=$ary[5]?> name=delivery value="경동택배(착불)"  >	
            </div>			
			 <div id="serodelivery2"> 
			&nbsp;   직접수령       <input type="radio" <?=$ary[6]?> name=delivery value="직접수령"  >	  
			&nbsp;   대신화물(선불)       <input type="radio" <?=$ary[7]?> name=delivery value="대신화물(선불)"  >	  
			&nbsp;   대신화물(착불)       <input type="radio" <?=$ary[8]?> name=delivery value="대신화물(착불)"  >	  
			&nbsp;   대신택배(선불)       <input type="radio" <?=$ary[9]?> name=delivery value="대신택배(선불)"  >	  
			&nbsp;   대신택배(착불)       <input type="radio" <?=$ary[10]?> name=delivery value="대신택배(착불)"  >  
            </div>	
	     <div class="clear"></div>	 
	 <div id="sero3"> 연락처 :  </div>
	 <div id="sero9"> <input type="text" name="phone" value="<?=$item_phone?>" size="20" placeholder="작업반장 또는 업체 연락처"  disabled> </div> 
	 <div id="sero11"> 공사번호 :  </div>
	 <div id="sero12"> <input type="text" id="con_num" name="con_num" value="<?=$con_num?>" size="5" placeholder="공사번호" disabled> </div>      
	 <div class="clear"></div>
	 <div id="sero3"> 비 고 :  </div>
	 <div id="sero10"> <textarea rows="4" cols="60" name="comment" placeholder="기타 코멘트 남겨주세요." disabled
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
   	 <div class="serofileview">	첨부파일 5: &nbsp; <a href="<?=$img_copied4?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name4?> </b> </a> </div>
     <div class="clear"></div>  	 
	   
         <div class="clear"></div>      <div class="clear"></div>      <br><br><br>	 
						   <div id="write_button_renew2">    
						   <input type='button' onclick='javascript()' value='화면닫기'/>

					  <?php
						if(isset($_SESSION["userid"])) {
						if($_SESSION["level"]>=1 )
					/* 	if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" ||
							   $_SESSION["level"]==1 )	 */	   
							{
					  ?>
												
					 <?php  	}
					 ?>
						</div>	   
	
	   
	   </div>	 
	     
 <?php
	}
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>  
	
 
   
 <!-- 이하 생략 -->

	<div class="clear"></div>
     </div> <!-- end of col2 -->
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
function javascript(){
    self.close();   //자기자신창을 닫습니다.
}

</script>
</html>    