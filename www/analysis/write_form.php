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
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호

if((int)$outputnum>=1)
 {
  require_once("../lib/mydb.php");
  $pdo = db_connect();
											  
								 try{  
							  $sql = "select * from chandj.bendinglist where outputnum = ? ";
							  $stmh = $pdo->prepare($sql); 

							  $stmh->bindValue(1,$outputnum,PDO::PARAM_STR); 
							  $stmh->execute();
							  $counter = $stmh->rowCount();            
							  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.

	                              if($counter>=1) {
									  	 $mode="modify";  //수정모드로 전환
										 $num=$row["num"];
										// print "번호를 찾았습니다.<br>" . $num;
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

  if(isset($_REQUEST["item_comment"]))  
   $item_comment=$_REQUEST["item_comment"];
  else
   $item_comment="";

  if(isset($_REQUEST["delivery"])) 
   $item_delivery=$_REQUEST["delivery"];
  else
   $item_delivery="";

$modify="0";  // 정렬모드 초기화

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];
$outputnumint=(int)$outputnum;
     
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="modify"){
    try{
      $sql = "select * from chandj.bendinglist where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

 	 if($callback==1) {
			  $outputnum=$row["outputnum"];
			  $outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];
			  $item_delivery=$row["delivery"];  

	  
			 if($item_indate!="0000-00-00") $item_indate = date("Y-m-d", strtotime( $item_indate) );
					else $item_indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";				
	       }		 
			  
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
			  $item_delivery=null;
	  			
 if($_SESSION["name"] =="안미희") 
         	  $root="경동";	  
  }
 
?>

<?php
 
  if($sort=='1')
	     $sql="select * from chandj.bending_write  where upnum='$upnum' order by num asc";	 // 처음 오름차순	 
     else
           $sql="select * from chandj.bending_write  where upnum='$upnum' order by num desc";	 // 처음 내림차순
  
	 try{  
	  $m_array=array();
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $m2=0;
	  $sum=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row["upnum"];			  
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
 $m2=number_format((float)$m2, 2, '.', '');
?>

   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 주일기업 통합정보시스템 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css"> 

   <link  rel="stylesheet" type="text/css" href="../css/bendingmake.css"> 
   <link rel="stylesheet" type="text/css" href="../css/bending_write_form.css">   
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
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insertlist.php?mode=not&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&upnum=<?=$upnum?>&parentnum=<?=$upnum?>&callback=1"> 
  <?php
	}
  ?>	   
    <div id="work_col3"> <h2> &nbsp; &nbsp; 절곡원가분석  &nbsp; &nbsp;  발주번호:&nbsp; <?=$upnum?> &nbsp; &nbsp;  출고 고유번호:&nbsp; <?=$outputnum?> &nbsp; &nbsp; </h2>

	      <div class="clear"></div>	
	 <div id="sero1"> 출 고 일 :  </div>	 
	 <div id="sero2"> <input type="date" id="outdate" name="outdate" value="<?=$outdate?>" size="14" placeholder="출 고 일" > </div> 
	 <div id="sero3"> 접 수 일 :  </div>
	 <div id="sero4"> <input type="date" id="indate" name="indate" value="<?=$item_indate?>" size="14" placeholder="접수일" > </div> 
	 <div id="sero3"> 발주처현장 :  </div>
	 <div id="sero5"> <input type="text" id="outworkplace" name="outworkplace" value="<?=$item_outworkplace?>" size="40" placeholder="발주처명"> 	  
	 </div> 
	 <div class="clear"></div> 
	 	 <div id="displaysearch" style="display:none"> 	 
	 </div>
	 	 <div class="clear"></div> 
	 <div id="sero32"> 수신처 :  </div>	 
	 <div id="sero7"> <input type="text" id="receiver"  name="receiver" value="<?=$item_receiver?>" size="20" placeholder="발주처" >  	 	 
	 </div>  

	 <div id="sero31"> 발주자 :  </div>	 
	 <div id="sero6"> <input type="text"  id="orderman" name="orderman" value="<?=$item_orderman?>" size="14" placeholder="발주자(주일/경동 직원)" > </div> 
    
	<div class="clear"></div>
    
	 
	 <div id="displaysearchworker" style="display:none"> 	 
	 </div>	 
	 <div class="clear"></div>
	 <div id="sero3"> 수신처주소 :  </div>
	 <div id="sero8"> <input type="text" id="outputplace" name="outputplace" value="<?=$item_outputplace?>" size="60" placeholder="현장주소 또는 납품처 주소 or 화물 영업소" > 
	 </div> <div id="sero88" >  <input type="text"  id="delivery" name="delivery" value="<?=$item_delivery?>" size="15" placeholder="배송방식" > </div> 

     <div id="sero3"> 연락처 :  </div>
	 <div id="sero9"> <input type="text" id="phone" name="phone" value="<?=$item_phone?>" size="20" placeholder="작업반장 또는 업체 연락처"> </div> 
     <div class="clear"></div>
	 <div id="sero3"> 비 고 :  </div>
	 <div id="sero10"> <textarea rows="2" cols="130" name="comment" placeholder="기타 코멘트 남겨주세요."
                ><?=$item_comment?></textarea></div>
		<input type="hidden" id="upnum" name="upnum" value="<?=$upnum?>" size="5" > 		
		<input type="hidden" id="sort" name="sort" value="<?=$sort?>" size="5" > 		
		<input type="hidden" id="modify" name="modify" value="<?=$modify?>" size="5" > 		
		<input type="hidden" id="outputnum" name="outputnum" value="<?=$outputnum?>" size="5" > 		
		<input type="hidden" id="ordercompany" name="ordercompany" value="<?=$item_outworkplace?>" size="5" > 		
     <div class="clear"></div> 
  			</form>       	 

  <div id="write_button_renew" style="float:center;" ><input type="image" src="../img/bending_write.jpg">&nbsp;&nbsp;&nbsp;

	 <a href="javascript:del('deletelist.php?num=<?=$num?>&page=<?=$page?>')"><img src="../img/delete.png"></a>&nbsp;&nbsp;&nbsp;
	    <a href="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"><img src="../img/list.png"></a>
				</div> 
 
	  </div> 
	
<div id="containers" >	
       <div id="display_result">	
	   <div id="display_text"> </div>
	   <div id="res1"> 번호 </div>
	   <div id="res2"> 품명 </div>
	   <div id="res3"> 세부사항 </div>
	   <div id="res4"> 절곡면적 내역 </div>
       <div class="clear"> </div>	   	
  		<?php
		
require_once("../lib/mydb.php");
 $pdo = db_connect();			  	

if($sort=='1') 
		   	 $sql="select * from chandj.bending_write  where upnum='$num' order by num desc";	 // 처음 내림차순
   else
		   	 $sql="select * from chandj.bending_write  where upnum='$num' order by num asc";	 // 처음 오름차순 
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh

	       $sum=0;
  
		   $counter=0;

           $display_text="";	

					  $options = array(); 
					  $options[1] = 'EGI1.6T'; 
					  $options[2] = 'EGI1.2T'; 
					  $options[3] = 'EGI0.8T'; 
					  $options[4] = 'SUS H/L 1.5T'; 
					  $options[5] = 'SUS H/L 1.2T'; 					
					  $options[6] = 'GI0.45T'; 					
					  $options[7] = 'GI0.8T'; 					
					  $options[8] = 'Mirror 1.5'; 					
					  $options[9] = 'Mirror 1.2'; 	
		   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		   $upnum=$row["upnum"];
  		   $counter++;

			  $num=$row["num"];

			 $steeltype=$row["steeltype"]; 	     
			 $steel_alias=$row["steel_alias"]; 	     

			// print $steeltype;
			// print $upnum . " " . $tempnum;
			 
			 $parentnum=$row["parentnum"]; 	     // 리스트번호  
			 $num=$row["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
			 $outputnum=$row["outputnum"]; 
			 $length1=$row["length1"]; 
			 $length2=$row["length2"]; 
			 $length3=$row["length3"]; 
			 $length4=$row["length4"]; 
			 $length5=$row["length5"]; 
			 $amount1=$row["amount1"]; 
			 $amount2=$row["amount2"]; 
			 $amount3=$row["amount3"]; 
			 $amount4=$row["amount4"]; 
			 $amount5=$row["amount5"]; 
			 $material1=$row["material1"]; 
			 $material2=$row["material2"]; 
			 $material3=$row["material3"]; 
			 $material4=$row["material4"]; 
			 $material5=$row["material5"]; 
			 $material6=$row["material6"]; 
			 $material7=$row["material7"]; 
			 $material8=$row["material8"]; 
			 $sum1=$row["sum1"]; 
			 $sum2=$row["sum2"]; 
			 $sum3=$row["sum3"]; 
			 $sum4=$row["sum4"]; 
			 $sum5=$row["sum5"]; 
			 $sum6=$row["sum6"]; 
			 $sum7=$row["sum7"]; 
			 $sum8=$row["sum8"]; 
			 $total_text=$row["total_text"]; 
			 $datanum=$row["datanum"]; 
   
     $arr_count++; 

     $jb = explode( ',', $total_text );	 
//	 print $jb[0] . "   " . $jb[1];   // 분할된 것 화면에 보여주기 테스트용
	 $total_count=count($jb);            // 배열의 수를 파악해서 반복문을 만든다.
	 
  
	 for($i=1;$i<=$total_count;$i++)
	 {
		      $tmp = explode( '=', $jb[$i-1] );	 
		 	 for($j=1;$j<=9;$j++)    // 합계 배열에 누적 	  
			 {
				 if($tmp[0]==$options[$j]) {					  
			  		  $steel_arr_sum[$j] = $steel_arr_sum[$j] + (float)$tmp[1];	 					  
				 }
				 
			 }
			 
	 }
	 	 
	 //print "--->"  . (count($jbexplode)-1);
   
		// echo '<script type="text/javascript">    changeUri();      </script>';	 
	
	?> 
	   

	   <div id="dis1">  <?=$counter?>  </div>
	   <div id="dis2">  <?=$steeltype?> </div>
	   <div id="dis3">  <?=$steel_alias?> 
	   </div>
	   <div id="dis4">    	&nbsp; <?=$total_text?>  	   </div>
       <div class="clear"> </div>	   

	  
	 <?php
			$start_num--;			 
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
	 
	       
	   </div> 
	 </div> <!-- end of containers -->  
         </div>	 <!-- end of col3 -->  
	   </div>   <!-- end of wrap -->   
	

	 <?php
	 for($j=1;$j<=9;$j++)    // 합계 배열 초기화
	 {
//		 print  " " . $steel_arr_sum[$j] . " ";
    if($steel_arr_sum[$j]!=0) {
	    $display_text = $display_text . $options[$j] . ' ==> ' . $steel_arr_sum[$j] . '(㎡),   ';
	                     }
   }	 
	// print '<div class="clear"> </div>';
   //  print $display_text;
?>	
<script>
setTimeout(function(){
/* 	var exititem = "<? echo $exititem; ?>";  // php변수를 자바스크립트에서 사용하는 방법 echo 이용
   if(exititem=='0')	
             $("#exityesno").hide();	 
		else	 
	         $("#exityesno").show();		 */
  cal_display();  //your code here
}, 500);
</script>

	</body>
   
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

function cal_display() {  // 화면용 절곡면적 계산
    var tmp="<? echo $display_text; ?>";  // php변수 불러오는 방법	
      $("#display_text").text(tmp);	  
}  // end of function

</script> 	
 </html>

