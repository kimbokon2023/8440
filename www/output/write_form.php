 <?php
  session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=8) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
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

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];

  if(isset($_REQUEST["regist_state"]))  // 등록하면 1로 설정 접수상태
   $regist_state=$_REQUEST["regist_state"];
  else
   $regist_state="1";

 $year=$_REQUEST["year"];   // 년도 체크박스
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="modify"){
    try{
      $sql = "select * from chandj.output where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

			  $con_num=$row["con_num"];
			  $outdate=$row["outdate"];
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
	  
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];
      $item_file_2 = $row["file_name_2"];
      $item_file_3 = $row["file_name_3"];
      $item_file_4 = $row["file_name_4"];
      $copied_file_0 = "../uploads/output/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/output/". $row["file_copied_1"];
      $copied_file_2 = "../uploads/output/". $row["file_copied_2"];	  
      $copied_file_3 = "../uploads/output/". $row["file_copied_3"];	  
      $copied_file_4 = "../uploads/output/". $row["file_copied_4"];	  
	  
			 if($item_indate!="0000-00-00") $item_indate = date("Y-m-d", strtotime( $item_indate) );
					else $item_indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 					
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }

if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  $outdate=date("Y-m-d");
			  $item_indate=date("Y-m-d");
			  $item_orderman=$_SESSION["name"];
			  $item_outworkplace=null;
			  $item_outputplace=null;
			  $item_receiver=null;
			  $item_phone=null;
			  $item_comment=null;
			  $con_num=null;
			  $root=null;	  
			  $steel=null;	  
			  $motor=null;	  			
			  $delivery=null;	  			
			  $regist_state="1";	  		
			  
 if($_SESSION["name"] =="안미희") 
         	  $root="경동";	  
  } 
?>

   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 주일기업 통합정보시스템 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/output.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/radio.css"> 
   </head>
 
   <body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://5130.co.kr/order/order.js"></script>
   
   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" enctype="multipart/form-data"> 
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
    <br><br>
    <div id="work_col3"> <h2> &nbsp; &nbsp; 자재 출고 등록  </h2>
  	 <?php
	 
	    if($_SESSION["name"] =="김보곤" && $regist_state=="1") 	 // 김보곤일때는 접수로 자동 체크
		      $regist_state="2";
		  
	 		 $aryreg=array();
	     switch ($regist_state) {
			case   "1"             : $aryreg[0] = "checked" ; break;
			case   "2"             :$aryreg[1] =  "checked" ; break;
			case   "3"             :$aryreg[2] =  "checked"; break;
			default: break;
		}		 
			
		 if($_SESSION["name"] =="김보곤"||$_SESSION["name"] =="황규선") 	 
		 {	 
	   ?>
	   	   <div id="regist_state">   
	         <b> 접수상태 : &nbsp;   등록          <input  type="radio" <?=$aryreg[0]?> name=regist_state value="1">
			    &nbsp;   접수         <input  type="radio" <?=$aryreg[1]?>  name=regist_state value="2">
		        &nbsp;   완료       <input  type="radio" <?=$aryreg[2]?> name=regist_state value="3">	  </b>
	         </div>  
	 <?php
		 }
		 ?>
	      <div class="clear"></div>	
	 <div id="sero1"> 출 고 일 :  </div>	 
	 <div id="sero2"> <input type="date" id="outdate" name="outdate" value="<?=$outdate?>" size="14" placeholder="출 고 일" > </div> 
	 <div id="sero_new1"> &nbsp; &nbsp;   회사구분 :  &nbsp; &nbsp; </div>	
	 <div id="sero_new2"> 
	  <?php
	    if($root==Null) 			 
			$root="주일";
			   ?>	  
	 <?php
	    if($root=="주일") {
			 ?>
			주일               <input type="radio" checked name=root id=root value="주일"  >
			&nbsp;   경동<input type="radio" name=root  id=root value="경동"  >	  
			<?php
             		}    ?>
	 <?php
	    if($root=="경동") {
			 ?>
			주일               <input type="radio"  name=root id=root value="주일"  >
			&nbsp;   경동<input type="radio" checked name=root id=root value="경동"  >	  
			<?php
             		}    ?>					
	              </div>
     <div class="clear"></div>	
	 <div id="sero3"> 접 수 일 :  </div>
	 <div id="sero4"> <input type="date" id="indate" name="indate" value="<?=$item_indate?>" size="14" placeholder="접수일" > </div> 
 <div id="sero_new1">  &nbsp; &nbsp;   발주표시 :  &nbsp; &nbsp; </div>	
	 <div id="sero_new3">  	   
				 <?php
			if($steel=="1") {
				 ?>
				 <input type="checkbox" name=steel checked value="1" > 절곡발주  
				<?php
						}    ?>	   
		 <?php
			if($steel!="1") {
				 ?>
				 <input type="checkbox" name=steel value="1"  > 절곡발주   
				<?php
						}    ?>	  	
	              </div>    
	 <div id="sero_new3">  	   
				 <?php
			if($motor=="1") {
				 ?>
				 <input type="checkbox" name=motor checked value="1" > 모터발주  
				<?php
						}    ?>	   
		 <?php
			if($motor!="1") {
				 ?>
				 <input type="checkbox" name=motor value="1"  > 모터발주   
				<?php
						}    ?>	  	
	              </div>    
				  
	<div class="clear"></div>
	 <div id="sero3"> 현 장 명 :  </div>
	 <div id="sero5"> <input type="text" id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$item_outworkplace?>" size="40" placeholder="주일은 현장명 경동은 업체명"> 	 
     <button type="button" id="searchplace" class="button button2" > 검색 </button> 	 	 
	 </div> <div id="sero555" style="color:red"> (주일공사는 현장명 검색 후 선택해야 출고가 연계됨) </div>
	 <div id="displaysearch" style="display:none"> 
	 
	 </div>
     <div class="clear"></div>
	 

	 <div id="sero31"> 발주자 :  </div>	 
	 <div id="sero6"> <input type="text"  id="orderman" name="orderman" value="<?=$item_orderman?>" size="14" placeholder="발주자(주일/경동 직원)" > </div> 
    
	<div class="clear"></div>
	 <div id="sero32"> 수신처(작업반장 or 업체) :  </div>
	 <div id="sero7"> <input type="text" id="receiver"  name="receiver" value="<?=$item_receiver?>" size="20" placeholder="수신처 (작업반장 or 업체)" onkeydown="JavaScript:Enter_CheckTel();" > 
     <button type="button" id="searchtel" class="button button2" > 전화번호검색 </button> 	 	 	 
	 </div>      
	 
	 <div id="displaysearchworker" style="display:none"> 	 
	 </div>	 
	 <div class="clear"></div>
	 <div id="sero3"> 수신처주소 :  </div>
	 <div id="sero8"> <input type="text" id="outputplace" name="outputplace" value="<?=$item_outputplace?>" size="60" placeholder="현장주소 또는 납품처 주소 or 화물 영업소" > </div> 
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
			default: break;
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
	 <div id="sero9"> <input type="text" id="phone" name="phone" value="<?=$item_phone?>" size="20" placeholder="작업반장 또는 업체 연락처"> </div> 
	 <div id="sero11"> 공사번호 :  </div>
	 <div id="sero12"> <input type="text" id="con_num" name="con_num" value="<?=$con_num?>" size="5" placeholder="공사번호"> </div> 
     <div class="clear"></div>
	 <div id="sero3"> 비 고 :  </div>
	 <div id="sero10"> <textarea rows="4" cols="100" name="comment" placeholder="기타 코멘트 남겨주세요."
                ><?=$item_comment?></textarea></div>
     <div class="clear"></div>     

   	 <div class="serofile"><input type="file" name="upfile[]" ></div> 
					<?php 	if ($mode=="modify" && $item_file_0)
							 {
				  ?>
						<div class="delete_ok">
					    	<br> <br> 
							 <a href="<?=$copied_file_0?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$item_file_0?> </b> </a>
							 등록
							<input type="checkbox" name="del_file[]" value="0"> 삭제 </div>
				  <?php  	} ?>
     <div class="clear"></div>  
	   
       		<div class="serofile"><input type="file" name="upfile[]"  ></div> 
					<?php 	if ($mode=="modify" && $item_file_1)
							 {
				  ?>
						<div class="delete_ok">
					    	<br> <br> 
							<a href="<?=$copied_file_1?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$item_file_1?> </b> </a>
							등록
							<input type="checkbox" name="del_file[]" value="1"> 삭제 </div>
				  <?php  	} ?>
     <div class="clear"></div>       
		    
            		<div class="serofile"><input type="file" name="upfile[]" ></div> 
					<?php 	if ($mode=="modify" && $item_file_2)
							 {
				  ?>
						<div class="delete_ok">
					    	<br> <br> 
							<a href="<?=$copied_file_2?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$item_file_2?> </b> </a>
						   등록
							<input type="checkbox" name="del_file[]" value="2"> 삭제 </div>
				  <?php  	} ?>
     <div class="clear"></div>  
	 
            		<div class="serofile"><input type="file" name="upfile[]" ></div> 
					<?php 	if ($mode=="modify" && $item_file_3)
							 {
				  ?>
						<div class="delete_ok">
					    	<br> <br> 
							<a href="<?=$copied_file_3?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$item_file_3?> </b> </a>
						   등록
							<input type="checkbox" name="del_file[]" value="3"> 삭제 </div>
				  <?php  	} ?>
     <div class="clear"></div>  
	 
	             		<div class="serofile"><input type="file" name="upfile[]" ></div> 
					<?php 	if ($mode=="modify" && $item_file_4)
							 {
				  ?>
						<div class="delete_ok">
					    	<br> <br> 
							<a href="<?=$copied_file_4?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$item_file_4?> </b> </a>
						   등록
							<input type="checkbox" name="del_file[]" value="4"> 삭제 </div>
				  <?php  	} ?>
     	 
<br><br><br><br><br>
  <div id="write_button_renew" style="float:center;" ><input type="image" src="../img/ok.png">&nbsp;&nbsp;&nbsp;&nbsp;
	   <a href="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"><img src="../img/list.png"></a>
				</div> <br>
	 
	  </div> 
	   </div>  
		
	</form>
 </div>

<script>
$(function () {
            $("fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
			
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

/* function exe_search()
{
	  var ua = window.navigator.userAgent;
      var postData; 	 
	  var text1= document.getElementById("outworkplace").value;
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(text1);
            } else {
                postData = text1;
            }

      $("#displaysearch").show();
      $("#displaysearch").load("./search.php?mode=search&search=" + postData);
} */


function exe_search()
{
      var postData = changeUri(document.getElementById("outworkplace").value);
      var sendData = $(":input:radio[name=root]:checked").val();

      $("#displaysearch").show();
	 if(sendData=='주일')
         $("#displaysearch").load("./search.php?mode=search&search=" + postData);
	 if(sendData=='경동') 
         $("#displaysearch").load("./searchkd.php?mode=search&search=" + postData);	  
}
function exe_searchTel()
{
	  var postData =  changeUri(document.getElementById("receiver").value);
      $("#displaysearchworker").show();
      $("#displaysearchworker").load("./workerlist.php?mode=search&search=" + postData);
}
</script> 
	</body>
 </html>
