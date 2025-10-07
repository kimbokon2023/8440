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
   $mode="not";
$outputnum=$_REQUEST["outputnum"];  // 출고현황에서 넘어온 출고번호
  $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호
if((int)$outputnum>=1)
 {
  require_once("../lib/mydb.php");
  $pdo = db_connect();
											  
								 try{  
							  $sql = "select * from chandj.egiorderlist where outputnum = ? ";
							  $stmh = $pdo->prepare($sql); 

							  $stmh->bindValue(1,$outputnum,PDO::PARAM_STR); 
							  $stmh->execute();
							  $counter = $stmh->rowCount();            
							  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.

	                              if($counter>=1) {
									  	 $mode="modify";  //수정모드로 전환
										 $num=$row["num"];
										 print "번호를 찾았습니다.<br>" . $num;
								  }
									      
							  } catch (PDOException $Exception) {
							  print "오류: ".$Exception->getMessage();
							  }  
 }				  
	  else
	  {
				  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크 출고현황에서 부르지 않았을때 
				   $num=$_REQUEST["num"];
				  else
				       $num=1;
  
	  }			  
  
  
   $upnum=$num;  // 번호를 부모번호로 넣음		

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

  if(isset($_REQUEST["sort"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $sort=$_REQUEST["sort"];
  else
   $sort="1";

  if(isset($_REQUEST["outdate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $outdate=$_REQUEST["outdate"];
  else
   $outdate="";

  if(isset($_REQUEST["item_indate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_indate=$_REQUEST["item_indate"];
  else
   $item_indate="";

  if(isset($_REQUEST["item_outworkplace"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_outworkplace=$_REQUEST["item_outworkplace"];
  else
   $item_outworkplace="";

  if(isset($_REQUEST["item_receiver"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_receiver=$_REQUEST["item_receiver"];
  else
   $item_receiver="";

  if(isset($_REQUEST["item_orderman"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_orderman=$_REQUEST["item_orderman"];
  else
   $item_orderman="";

  if(isset($_REQUEST["item_outputplace"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_outputplace=$_REQUEST["item_outputplace"];
  else
   $item_outputplace="";

  if(isset($_REQUEST["item_phone"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_phone=$_REQUEST["item_phone"];
  else
   $item_phone="";

  if(isset($_REQUEST["item_comment"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $item_comment=$_REQUEST["item_comment"];
  else
   $item_comment="";


$modify="0";  // 정렬모드 초기화

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];
$outputnumint=(int)$outputnum;
     
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="modify"){
    try{
      $sql = "select * from chandj.egiorderlist where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

			  $outputnum=$row["outputnum"];
			  $outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];
	  
			 if($item_indate!="0000-00-00") $item_indate = date("Y-m-d", strtotime( $item_indate) );
					else $item_indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 					
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }

if ($mode=="not"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  $outdate=date("Y-m-d");
			  $outputnum=null;
			  $item_indate=date("Y-m-d");
			  $item_orderman=$_SESSION["name"];
			  $item_outworkplace=null;
			  $item_outputplace=null;
			  $item_receiver=null;
			  $item_phone=null;
			  $item_comment=null;
	  			
 if($_SESSION["name"] =="안미희") 
         	  $root="경동";	  
  }
 
?>

<?php
 
  if($sort=='1')
	     $sql="select * from chandj.egimake  where upnum='$upnum' order by num asc";	 // 처음 오름차순	 
     else
           $sql="select * from chandj.egimake  where upnum='$upnum' order by num desc";	 // 처음 내림차순	   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $sum=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row["upnum"];			  
if((int)$upnum==(int)$num)
      {	
			  $counter++;
			 $number=$row["number"];  
			 $sum+=(int)$number;
   			 }
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

?>

   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 주일기업 통합정보시스템 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css"> 

   <link  rel="stylesheet" type="text/css" href="../css/radio.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/screenmake.css"> 
    <link rel="stylesheet" type="text/css" href="../css/screenlist.css">   
    <link rel="stylesheet" type="text/css" href="../css/egilist.css">   
   </head> 
   <body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://5130.co.kr/order/order.js"></script>
   
   <div id="wrap">
	  
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
    </div>  
    <div id="content">
    <br><br>
	 <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insertlist.php?mode=modify&num=<?=$num?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>" > 
  <?php  } 
else  {
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insertlist.php?mode=not&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>"> 
  <?php
	}
  ?>	   
    <div id="work_col3"> <h2> &nbsp; &nbsp; 철재스라트발주서 등록  &nbsp; &nbsp;  발주번호:&nbsp; <?=$upnum?> &nbsp; &nbsp;  출고 고유번호:&nbsp; <?=$outputnum?> &nbsp; &nbsp;  셔터수량 : <?=$sum?> </h2>

	      <div class="clear"></div>	
	 <div id="sero1"> 출 고 일 :  </div>	 
	 <div id="sero2"> <input type="date" id="outdate" name="outdate" value="<?=$outdate?>" size="14" placeholder="출 고 일" > </div> 
	 <div id="sero3"> 접 수 일 :  </div>
	 <div id="sero4"> <input type="date" id="indate" name="indate" value="<?=$item_indate?>" size="14" placeholder="접수일" > </div> 
	 <div id="sero3"> 발주처현장 :  </div>
	 <div id="sero5"> <input type="text" id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$item_outworkplace?>" size="40" placeholder="발주처명"> 	 
     <button type="button" id="searchplace" class="button button2" > 검색 </button> 	 	 
	 </div> 
	 <div class="clear"></div> 
	 	 <div id="displaysearch" style="display:none"> 	 
	 </div>
	 	 <div class="clear"></div> 
	 <div id="sero32"> 수신처 :  </div>
	 <div id="sero7"> <input type="text" id="receiver"  name="receiver" value="<?=$item_receiver?>" size="20" placeholder="발주처" onkeydown="JavaScript:Enter_CheckTel();" > 
     <button type="button" id="searchtel" class="button button2" > 전화번호검색 </button> 	 	 	 
	 </div>  
				  
	<div class="clear"></div>

	 <div id="sero31"> 발주자 :  </div>	 
	 <div id="sero6"> <input type="text"  id="orderman" name="orderman" value="<?=$item_orderman?>" size="14" placeholder="발주자(주일/경동 직원)" > </div> 
    
	<div class="clear"></div>
    
	 
	 <div id="displaysearchworker" style="display:none"> 	 
	 </div>	 
	 <div class="clear"></div>
	 <div id="sero3"> 수신처주소 :  </div>
	 <div id="sero8"> <input type="text" id="outputplace" name="outputplace" value="<?=$item_outputplace?>" size="60" placeholder="현장주소 또는 납품처 주소 or 화물 영업소" > </div> 
     <div class="clear"></div>
	
	 <div class="clear"></div>
	 <div id="sero3"> 연락처 :  </div>
	 <div id="sero9"> <input type="text" id="phone" name="phone" value="<?=$item_phone?>" size="20" placeholder="작업반장 또는 업체 연락처"> </div> 
     <div class="clear"></div>
	 <div id="sero3"> 비 고 :  </div>
	 <div id="sero10"> <textarea rows="4" cols="100" name="comment" placeholder="기타 코멘트 남겨주세요."
                ><?=$item_comment?></textarea></div>
		<input type="hidden" id="upnum" name="upnum" value="<?=$upnum?>" size="5" > 		
		<input type="hidden" id="sort" name="sort" value="<?=$sort?>" size="5" > 		
		<input type="hidden" id="modify" name="modify" value="<?=$modify?>" size="5" > 		
		<input type="hidden" id="outputnum" name="outputnum" value="<?=$outputnum?>" size="5" > 		
     <div class="clear"></div> 
  			</form>       	 
<br><br>
  <div id="write_button_renew" style="float:center;" ><input type="image" src="../img/egiwrite.png">&nbsp;&nbsp;&nbsp;
    <a href="#" onclick="window.open('print.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>','인쇄하기','left=20,top=20, scrollbars=yes, toolbars=no,width=1300,height=1450');" border="0" "> <img src="../img/print.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="#" onclick="window.open('savefile.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&receiver=<?=$receiver?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>','파일저장','left=20,top=20, scrollbars=yes, toolbars=no,width=900,height=1450');" border="0" "> <img src="../img/savefile.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;
         <button  onclick="saveit();" > 편집저장  </button>          &nbsp;&nbsp;&nbsp;&nbsp;  
         <button  onclick="sortall();" > 정렬변경  </button>          &nbsp;&nbsp;&nbsp;&nbsp;  		 
	 <a href="javascript:del('deletelist.php?num=<?=$num?>&page=<?=$page?>&parentnum=<?=$parentnum?>')"><img src="../img/delete.png"></a>&nbsp;&nbsp;&nbsp;
	    <a href="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"><img src="../img/list.png"></a>
				</div> <br> 
	  </div> 
 
<div id="containers" >	
	<div id="display_result" >	
	   <div id="ares1"> 번호 </div>
	   <div id="ares2"> 부호 </div>
	   <div id="ares3">  철재스라트 재질,  제작치수 폭(W) x 수량(매수) , 힌지사용여부  </div>
       <div class="clear"> </div>
	
  		<?php
  if($sort=='1')
	     $sql="select * from chandj.egimake  where upnum='$upnum' order by num asc";	 // 처음 오름차순	 
     else
           $sql="select * from chandj.egimake  where upnum='$upnum' order by num desc";	 // 처음 내림차순
  
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $sum=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row["upnum"];			  
if((int)$upnum==(int)$num)
      {	
			  $counter++;
			  $text1=$row["text1"];
			  $text2=$row["text2"];
			  $text3=$row["text3"];
			  $text4=$row["text4"];
			  $hinge=$row["hinge"];
			 $ordercompany=$row["ordercompany"];  
			 $callname=$row["callname"];  
			 $cutwidth=$row["cutwidth"];  
			 $cutheight=$row["cutheight"];  
			 $number=$row["number"];  
			 $printside=$row["printside"];  
			 $direction=$row["direction"];  
			 $exititem=$row["exititem"];  
			 $intervalnum=$row["intervalnum"];  
			 $intervalnumsecond=$row["intervalnumsecond"];  
			 $memo=$row["memo"];  
			 $draw=$row["draw"];  
			 $drawbottom1=$row["drawbottom1"];  
			 $drawbottom2=$row["drawbottom2"];  
			 $sum.=(int)$number;
	 
		// echo '<script type="text/javascript">    changeUri();      </script>';	 
			  ?> 
	   <div id="res1"> <?=$counter?>  </div>
	   <div id="res2">  <?=$text1?> </div>
       <div id="fres1"> <?=$text2?> </div>

       <div class="clear"> </div>
	  
	 <?php
			$start_num--;
			 }
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
	  
 ?>
	 
	       
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  
         </div>	 <!-- end of col3 -->  
	   </div>   <!-- end of wrap -->   
	   
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
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          }
}

function sortall(){
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
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
}

</script> 
	</body>
 </html>
