 <?php
  session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }   
  
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";

  if(isset($_REQUEST["which"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $which=$_REQUEST["which"];
  else
   $which="2";
  
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
  
   $sql="select * from mirae8440.dividesource"; 					

	 try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=1;
   $item_counter=1;

   $divide_num=array();
   $dividesource_item=array();
   $dividesource_holes=array();
   $dividesource_spec=array();
   $dividesource_take=array();
   $dividesource_item=array();
   $dividesource_spec=array();
   $spec_arr=array();   
   $divide_item=array();
   $divide_holes=array();
   $divide_spec=array();
   $divide_take=array();
   $divide_item=array();
   $divide_spec=array();
   $spec_arr=array();
   $last_item="";
   $last_spec="";
   $pass='0';
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

	   
 			  $dividesource_num[$counter]=$row["num"];			  
 			  $dividesource_item[$counter]=$row["item"];
 			  $dividesource_spec[$counter]=$row["spec"];
		      $dividesource_take[$counter]=$row["holes"];   
		      $dividesource_take[$counter]=$row["take"]; 			  
		 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    



			 $divide_item[]='알루미늄';
			 $divide_item[]='5줄';
			 $divide_item[]='8줄';
			 $divide_item[]='E편한세상';
			 $divide_item[]='이형';
			 
		     $item_counter=count($divide_item);
			 
			 $divide_holes[]='1홀';
			 $divide_holes[]='2홀';
			 $divide_holes[]='3홀';
			 $divide_holes[]='홀없음';
			 
		     $holes_counter=count($divide_holes);			 
			 
			 $divide_spec[]='900';
			 $divide_spec[]='900W';
			 $divide_spec[]='1000';
			 $divide_spec[]='1000W';
			 $divide_spec[]='1100';
			 $divide_spec[]='1100W';
			 $divide_spec[]='이형';
			 
		     $spec_counter=count($divide_spec);				 
				
  
  
  if ($mode=="copy"){
    try{
      $sql = "select * from mirae8440.divide where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{


              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	  
			  $model=$row["model"];	 	  
			  $holes=$row["holes"];	 	  
	  
			 if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					else $indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 					
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
$mode="";

// $mode를 이용해서 copy_data.php에서 기존 데이터를 사용한다.
	 
  }
  

?>

   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 미래기업 통합정보시스템 </title>
   <meta charset="utf-8">
   <link  rel="stylesheet" type="text/css" href="../css/common.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/divide.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/radio.css"> 
   </head>
 
   <body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://8440.co.kr/order/order.js"></script>
   
   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" > 
  <?php  } else {
  ?>
	<form  name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"> 
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
    <div id="work_col3"> <div id=top_title style="width:400px; height:50px;color:brown;"> <h2> &nbsp; &nbsp; 재료분리대 입출고 등록/수정 화면 </h2> </div>
  	
	      <div class="clear"></div>	
		  
 <?php	 		  
	 	 $aryreg=array();
		 if($which=='') $which='2';
	     switch ($which) {
			case   "1"             : $aryreg[0] = "checked" ; break;
			case   "2"             :$aryreg[1] =  "checked" ; break;
			default: break;
		}		 
			
 
	   ?>		  
		  
	   	   <div id="which">   
	            <div id="which_1">  구분 :  </div>   <div id=which_a > 입고        <input  type="radio" <?=$aryreg[0]?> name=which value="1"> </div>  
			    &nbsp;   <div id=which_b > 출고         <input  type="radio" <?=$aryreg[1]?>  name=which value="2">  </div>  
	         </div>  		  
     <div class="clear"></div>			  
	 <div id="sero1"> 입출고일 :  </div>	 
	 <div id="sero2"> <input type="date" id="outdate" name="outdate" value="<?=$outdate?>" size="14" placeholder="출고일" > </div> 

	 <div id="sero3"> 접 수 일 :  </div>
	 <div id="sero2"> <input type="date" id="indate" name="indate" value="<?=$indate?>" size="14" placeholder="접수일" > </div> 
	      <div class="clear"></div>	
		  


	 <div id="sero3_1"> 현 장 명 :  </div>
	 <div id="sero5" style="width:700px;height:40px;"> <input type="text" style="height:30px;" id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$outworkplace?>" size="60" placeholder="현장명"> 	 
     <button type="button" id="searchplace" class="button button2" > 검색 </button> 	 	 
	 </div>
	 <div id="displaysearch" style="display:none"> 
	 
	 </div>	 
     <div class="clear"></div>	 

	 
	 <div id="displaysearchworker" style="display:none"> 	 
	 </div>	 
	 <div class="clear"></div>
	 <div id="sero1000" style="margin-left:50px; height:60px;"> <h3> 입출고 자재내역 및 수량입력 </h3></div> 
     <div class="clear"></div>	 
	 <div id="sero3" style="height:40px; width:80px; margin-top:15px; margin-left:35px;margin-right:-10px;"> 종 류 :  </div>
	    <div id="item1"> <select name="item" id="sel1"  >
           <?php		 

		   for($i=0;$i<$item_counter;$i++) {
			     if($item==$divide_item[$i])
							print "<option selected value='" . $divide_item[$i] . "'> " . $divide_item[$i] .   "</option>";
					 else   
			   print "<option value='" . $divide_item[$i] . "'> " . $divide_item[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select> </div>
	 <div id="sero44" > 홀 수 :  </div>		
	    <div id="item2"> <select name="holes" id="sel2" >
           <?php		 

		   for($i=0;$i<$holes_counter;$i++) {
			       if($holes==$divide_holes[$i])
					       print "<option selected value='" . $divide_holes[$i] . "'> " . $divide_holes[$i] .   "</option>";
					   else
							print "<option value='" . $divide_holes[$i] . "'> " . $divide_holes[$i] .   "</option>";
		   } 		   
		      	?>	   
	    </select>
            </div>	 
	 <div id="sero44" > 규격 :  </div>		
	    <div id="item3"> <select name="spec" id="sel3" >
           <?php		 

		   for($i=0;$i<$spec_counter;$i++) {
			       if($spec==$divide_spec[$i])
					       print "<option selected value='" . $divide_spec[$i] . "'> " . $divide_spec[$i] .   "</option>";
					   else
							print "<option value='" . $divide_spec[$i] . "'> " . $divide_spec[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select>
            </div>	 			
				 <div id="sero55" > 수 량 :  </div>		
				 
								 <input type="number" name="steelnum" id="steelnum" value="<?=$steelnum?>" size="8" placeholder="수량"  />
				 
			 
				 
	 <div class="clear"></div>
	 <div id="sero3" style="height:40px; margin-top:15px;"> 비 고 :  </div>
	 <div id="sero100" style="height:40px; margin-top:15px;"> <textarea rows="5" cols="80" name="comment" placeholder="기타사항 입력"
                ><?=$comment?></textarea></div>
     <div class="clear"></div>    	 

     <div class="clear"></div>   
	 


			 
     <div class="clear"></div> 	   
	 
<div id=display_board class=background name=display_board > 

 <div id="list_search">
        <div id="list_search1"> </div>
        <div id="list_search1_1"> 			 
       </div>		
       
        <div id="list_search5"></div>

      </div> <!-- end of list_search -->
 
     

     <div class="clear"></div>		 
	 	<!--   <div id="order11"> 수량 : </div>	   -->
 <div id="order2">
   
 
	 </div>	 		 
   <div class="clear"></div>	

     <div class="clear"></div>		
	<!--   <div id="order11"> 수량 : </div> -->
  <div id="order2">
	
  
	 </div> 

     <div class="clear"></div>
   	<!-- 	 


    <div class="clear"></div>	 
   	<!--   <div id="order5"> 수량 :  </div>		 --> 	 
 
	
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
