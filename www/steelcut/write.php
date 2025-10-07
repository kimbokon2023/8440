<?php

session_start();

   $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>10) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh   
  $upnum=$_REQUEST["upnum"]; 	     // 발주서 번호
  $tempnum=$_REQUEST["upnum"]; 	     // 발주서 번호 비교를 위해 임시변수 만듦
  $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호
  $num=$_REQUEST["num"]; 	     // 발주서 번호 비교를 위해 임시변수 만듦
  $callname=$_REQUEST["callname"]; 
  $cutwidth=$_REQUEST["cutwidth"]; 
  $cutheight=$_REQUEST["cutheight"]; 
  $number=$_REQUEST["number"]; 	
  $exititem=$_REQUEST["exititem"];  

 if(!isset($_REQUEST["exitinterval"]) )  
   $exitinterval='900';
      else
	       $exitinterval=$_REQUEST["exitinterval"]; 	  
	
 if(!isset($_REQUEST["cover"]) ) 
   $cover='1200';
      else
	       $cover=$_REQUEST["cover"]; 
	   
  $tempexit=$_REQUEST["exititem"];  
  $sort=$_REQUEST["sort"]; 
  $recallcal=$_REQUEST["recallcal"]; 
 
 if(!isset($_REQUEST["ordercompany"]) || $ordercompany=="" ) 
   $ordercompany=$_REQUEST["outworkplace"];   // 현장명이 없을때는 부모파일 현장명 가져오기


  
if($sort=='' ||  $sort=='0')
	 $sort='1';

  $draw=$_REQUEST["draw"];  
  $drawbottom1="";   //좌측 공백
  $drawbottom2="";  //우측 공백

  $memo=$_REQUEST["memo"];  
  $text2=$_REQUEST["text2"];  
  $hinge=$_REQUEST["hinge"];  
   
 if(!isset($_REQUEST["cutwidth"]) || $cutwidth=="" ) 
   $cutwidth="5000";
      else
	       $cutwidth=$_REQUEST["cutwidth"]; 	  
	 
 if(!isset($_REQUEST["cutheight"]) || $cutheight=="" ) 
    $cutheight="3000";	 
	 else
           $cutheight=$_REQUEST["cutheight"]; 
	   
 if(!isset($_REQUEST["number"])) 
   $number="1";
      else
	       $number=$_REQUEST["number"]; 	   
	   
if(!isset($_REQUEST["printside"])) 
    $printside="0";	 
	 else
           $printside=$_REQUEST["printside"]; 	   	   

if(!isset($_REQUEST["direction"])) 
    $direction="0";	 
	 else
           $direction=$_REQUEST["direction"]; 	   
	   
 if(!isset($_REQUEST["exititem"])) 
    $exititem="0";	 
	 else
           $exititem=$_REQUEST["exititem"]; 	 
  if(!isset($_REQUEST["intervalnum"]) ) 
   $intervalnum="없음";
      else
	       $intervalnum=$_REQUEST["intervalnum"]; 	
  if(!isset($_REQUEST["intervalnumsecond"])) 
   $intervalnumsecond="없음";
      else
	       $intervalnumsecond=$_REQUEST["intervalnumsecond"]; 	
 
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();			  
	
?>

	<?php
 
if($sort=='1') 
		   	 $sql="select * from chandj.egimake  where upnum='$parentnum' order by num desc";	 // 처음 내림차순
   else
		   	 $sql="select * from chandj.egimake  where upnum='$parentnum' order by num asc";	 // 처음 오름차순 
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $sum=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row["upnum"];			  
if((int)$upnum==(int)$parentnum)
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

<?php	
	
	
	
  $page=1;	 
  
 
  $scale = 30;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.

if($sort=='1') 
		   	 $sql="select * from chandj.egimake  where upnum='$upnum' order by num desc";	 // 처음 내림차순
   else
		   	 $sql="select * from chandj.egimake  where upnum='$upnum' order by num asc";	 // 처음 오름차순
			
			 
$nowday=date("Y-m-d");   // 현재일자 변수지정   
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp=$stmh->rowCount();
	      
	  $total_row = $temp;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 

		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	  
if($callname=="") $callname="FST";
if($memo=="") $memo="EGI1.6T";
     
	  ?>
		

 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">


 <title> 주일기업 통합정보시스템 </title> 
 </head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="http://5130.co.kr/order/order.js"></script>
  <script src="http://5130.co.kr/make/make.js"></script>
  <script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->
 <link rel="stylesheet" type="text/css" href="../css/egilist.css">
<style media="screen">
      *{
        margin: 0; padding: 0;
      }
      .slide{
        width: 400px;
        height: 400px;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
      }
      .slide ul{
        width: 11600px;
        position: absolute;
        top:0;
        left:0;
        font-size: 0;
      }
      .slide ul li{
        display: inline-block;
      }
      #back{
        position: absolute;
        top: 0;
        left:0 ;
        cursor: pointer;
        z-index: 1;
      }
      #next{
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
        z-index: 1;
      }
     </style>
 

   <body>
  <div id="wrap">  
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
   </div>  
  <div id="content">
    <div id="work_col2">     <br>
<h6> 철재스라트 제작 &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; 발주서번호 : &nbsp;&nbsp; <?=$upnum?>&nbsp;&nbsp; 셔터 수량합계 : &nbsp; <?=$sum?> </h6>

<form name="board_form" id="board_form"  method="post"  action="insert.php?mode=modify&text1=<?=$callname?>&text2=<?=$text2?>&parentnum=<?=$parentnum?>" >
<div id="exitcontent">
<div id="company1"> 발주처(현장명) : </div>
<div id="company2"> <input id="ordercompany" name="ordercompany"  type="text" size="60" placeholder="발주처,현장명" value="<?=$ordercompany?>" readonly>  </div>
<div class="clear"> </div>
<div id="r5"> 부호 </div>
<div id="r6"> <input id="callname" name="callname"  type="text" size="16" placeholder="부호" value="<?=$callname?>" >  </div>

<div id="r0"> 제작size </div>

<div id="r1"> 가로(W) :  </div> 
<div id="r2"> <input id="cutwidth" name="cutwidth" type="text" size="5" placeholder="width" value="<?=$cutwidth?>" required > </div>
<div id="r3"> 세로(H) :  </div> 
<div id="r4"> <input id="cutheight" name="cutheight" type="text" size="5" value="<?=$cutheight?>" required placeholder="height"> </div>
<div id="r33"> 수량 :  </div> 
  
  <div id="r8"> <input id="number" name="number" type="text" size="3" placeholder="수량" value="<?=$number?>" required > </div>
<div id="r7"> 틀, </div> 
 	

<div id="exitpos"> 비상문 위치 :  </div>
<div id="exitpos1">
	   <?php
	     if($exititem=='0') {
			print "  
		   <select id='exititem' name='exititem'>
           <option value='0'           selected >없음                           </option>
           <option value='1'                    >중앙                              </option>
           <option value='2'                    >좌측                              </option>
           <option value='3'                    >우측                              </option>		  
		   </select>  ";
		 }
	     if($exititem=='1') {
			print "  
		   <select id='exititem' name='exititem'>
           <option value='0'                    >없음                           </option>
           <option value='1'           selected >중앙                              </option>
           <option value='2'                    >좌측                              </option>
           <option value='3'                    >우측                              </option>	
		   </select>  ";
		 }		
	     if($exititem=='2') {
			print "  
		   <select id='exititem' name='exititem'>
           <option value='0'                    >없음                           </option>
           <option value='1'                    >중앙                              </option>
           <option value='2'           selected >좌측                              </option>
           <option value='3'                    >우측                              </option>		  
		   </select>  ";
		 }
	     if($exititem=='3') {
			print "  
		   <select id='exititem' name='exititem'>
           <option value='0'                    >없음                           </option>
           <option value='1'                    >중앙                              </option>
           <option value='2'                    >좌측                              </option>
           <option value='3'           selected >우측                              </option>   
		   </select>  ";
		 }

?>		 
		   
		   
</div>


<div id="exityesno" >
<div id="excol1"> 띄울치수 : </div>  
<div id="excol2">  <input id="intervalnum" name="intervalnum"  type="text" size="5" value="<?=$intervalnum?>" > </div>
<div id="excol3">  힌지종류 : </div>
<?php
    if($hinge=='승리' || $hinge=='')
	{
 	   print "<select id='hinge' name='hinge'>
           <option value='승리'           selected >승리                           </option>
           <option value='태영'                    >태영                           </option>
           <option value='굴비'                    >굴비                           </option>
           <option value='대신'                    >대신                           </option>
           </select> " ;
	}
    if($hinge=='태영' )
	{
 	   print "<select id='hinge' name='hinge'>
           <option value='승리'                   >승리                           </option>
           <option value='태영'            selected        >태영                           </option>
           <option value='굴비'                    >굴비                           </option>
           <option value='대신'                    >대신                           </option>
           </select> " ;
	}	
    if($hinge=='굴비' )
	{
 	   print "<select id='hinge' name='hinge'>
           <option value='승리'                   >승리                           </option>
           <option value='태영'                   >태영                           </option>
           <option value='굴비'           selected          >굴비                           </option>
           <option value='대신'                    >대신                           </option>
           </select> " ;
	}	
    if($hinge=='대신' )
	{
 	   print "<select id='hinge' name='hinge'>
           <option value='승리'                    >승리                           </option>
           <option value='태영'                    >태영                           </option>
           <option value='굴비'                    >굴비                           </option>
           <option value='대신'         selected   >대신                           </option>
           </select> " ;
	}		
	?>
		  <input type="text" id="hingenum"  name="hingenum" size="4" style="color:blue;font-weight:bold;" >
</div>
<div class="clear"> </div>
<div id="excol6"> 재질 : </div>  
<div id="excol7">  
<?php
      if($memo=='EGI1.6T' || $memo=='' )
	{
 	   print "<select id='memo' name='memo'>
           <option value='EGI1.6T'        selected    >EGI1.6T                           </option>
           <option value='EGI1.2T'                    >EGI1.2T                           </option>
           </select> " ;
	}		
      if($memo=='EGI1.2T')
	{
 	   print "<select id='memo' name='memo'>
           <option value='EGI1.6T'                            >EGI1.6T                           </option>
           <option value='EGI1.2T'        selected            >EGI1.2T                           </option>
           </select> " ;
	}			
	?>
  </div>
<div id="result1" name="result1" >
<textarea rows="2" cols="120" name="text2" id="text2" value="<?=$text2?>" > </textarea>
  </div>
 	<!-- 화면에 도면 보여주기--> 
  
<div id="drawimg"> </div>  <input id="draw" name="draw" type="hidden" size="3" value="<?=$draw?>" >
                           <input id="sort" name="sort" type="hidden" size="3" value="<?=$sort?>" >  	<!-- 정렬방식 변경 --> 
                           <input id="upnum" name="upnum" type="hidden" size="3" value="<?=$upnum?>" >  	<!-- 발주서 상위번호--> 
                           <input id="num" name="num" type="hidden" size="3" value="<?=$num?>" >  	<!-- 발주서 --> 
                           <input id="modify" name="modify" type="hidden" size="3" value="<?=$modify?>" >  	<!-- 수정여부 --> 
<div class="clear"> </div>
  
</div>


</form>

</div>

   <div class="clear"> </div>
	&nbsp;&nbsp; <button id="calsize_exe" onclick="calsize_exe();"> 철재스라트제작size 산출 </button>
	&nbsp;&nbsp; <button id="addline" onclick="addline();" > 행 추가 </button>
	&nbsp;&nbsp; <button id="update" onclick="update();" > 수정&저장 </button>
	<!-- 일부분 부분-->
  &nbsp;&nbsp; <button onclick="partShot();"> 이미지 저장 </button>
  &nbsp;&nbsp;
   <button  onclick="javascript:del('delete.php?mode=all&upnum=<?=$upnum?>&parentnum=<?=$parentnum?>')" > DATA 전체삭제  </button>
  &nbsp;&nbsp;
   <button  onclick="sorting();" > 정렬변경  </button>   
  &nbsp;&nbsp;
   <button  onclick="javascript:move('write_form.php?num=<?=$upnum?>&mode=modify&upnum=<?=$upnum?>&parentnum=<?=$parentnum?>')" > 이전화면  </button>      
   <div class="clear"> </div><br><br>

<div id="containers" >	
	<div id="display_result" >	
	   <div id="ares1"> 번호 </div>
	   <div id="ares2"> 부호 </div>
	   <div id="ares3"> 철재스라트 재질,  제작치수 폭(W) x 수량(매수) , 힌지사용여부 </div>
       <div class="clear"> </div>
	
  		<?php
		   $counter=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row["upnum"];
if((int)$upnum==(int)$tempnum)
      {	
			  $counter++;

			  $num=$row["num"];
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
			 $drawbottom3=$row["drawbottom3"];  
			 $cover=$row["cover"];  
			 $exitinterval=$row["exitinterval"];  
	 
		// echo '<script type="text/javascript">    changeUri();      </script>';	 
			  ?> 
	   <div id="res1"> <a href="javascript:del('delete.php?num=<?=$num?>&upnum=<?=$upnum?>&ordercompany=<?=$ordercompany?>&callname=<?=$callname?>&cutheight=<?=$cutheight?>&cutwidth=<?=$cutwidth?>&number=<?=$number?>&comment=<?=$comment?>&printside=<?=$printside?>&direction=<?=$direction?>&exititem=<?=$exititem?>&intervalnum=<?=$intervalnum?>&intervalnumsecond=<?=$intervalnumsecond?>&memo=<?=$memo?>&parentnum=<?=$parentnum?>')"> <?=$counter?> </a> </div>
	   <div id="res2"> <a href="javascript:load('load.php?num=<?=$num?>&sort=<?=$sort?>&recallcal=1&parentnum=<?=$parentnum?>')"> <?=$text1?> </a> </div>
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
	 </div> 
	   
</div>		
		<br>    
	   </div> 
<script>   // 화면을 시간 지연 후 나타내 주기
setTimeout(function(){
/* 	var exititem = "<? echo $exititem; ?>";  // php변수를 자바스크립트에서 사용하는 방법 echo 이용
   if(exititem=='0')	
             $("#exityesno").hide();	 
		else	 
	         $("#exityesno").show();		 */
		 
if($("#exititem").val()!='0') {
	  	$("#exityesno").show();	    
  }	  
check_hinge();
calsize_exe();  //your code here
}, 500);

</script>	   
	   
</body>
<script>
$(function(){		 
	$("#exityesno").hide();	    
    $("#exititem").change(function(){
			$("#exityesno").hide();	  		
        if ( this.value == '0' ) {
			$("#exityesno").hide();	  
  			$("#hinge").val("없음");				
			$("#hingenum").val("");		                      
          		}
        if ( this.value == '1' ) {
                        exitcenter();						
          		}				
        if ( this.value == '2' ) {
                        exitleft();						
          		}	
        if ( this.value == '3' ) {
                        exitright();						
          		}								
	});	

	
  $("#hinge").change(function(){
 
        if ( this.value == '승리' ) {
                   $("#hingenum").val("85");	        
          		}			
        if ( this.value == '태영' ) {
                   $("#hingenum").val("70");	        
          		}							
        if ( this.value == '굴비' ) {
                   $("#hingenum").val("60");	        
          		}											
         if ( this.value == '대신' ) {
                   $("#hingenum").val("50");	        
          		}         							
	   });		
});	
	
function check_hinge() {
if(	$("#exititem").val()!='0')
{
	    if ( $("#hinge").val() == '승리' ) {
                   $("#hingenum").val("85");	        
          		}			
        if ( $("#hinge").val() == '태영' ) {
                   $("#hingenum").val("70");	        
          		}							
        if ( $("#hinge").val() == '굴비' ) {
                   $("#hingenum").val("60");	        
          		}											
         if ( $("#hinge").val() == '대신' ) {
                   $("#hingenum").val("50");	        
          		}  
}
	
}	
	
function calsize_exe() {
  var a = 0;
  var b = 0;
  var c = 0;
  var d = 0;
  var e = 0;
  var f = 0;
  var g = 0;
  var h = 0;
  var i = 0;               // 쪽바계산용
  var amount;
  var hinge;
  var hingenum;
  
 
  if($("#intervalnum").val()=='' && $("#exititem").val()!='없음') {
	    alert("띄울 치수를 입력해 주세요.");
		return false;
  }
   
  a=Math.ceil($("#cutheight").val() / 71) ;
  b=$("#cutwidth").val() ;
  e=$("#cutheight").val() ;
  f=Number($("#hingenum").val());  // 힌지는 f
  g=$("#intervalnum").val() ;
  amount=$("#number").val() ;
  hinge=$("#hinge").val() ;
  hingenum=$("#hingenum").val() ;
  memo=$("#memo").val() ;
  
  c= a-29;   // 상바계산
  d= Math.floor((b-(900+f))/2);                // 비상문이 중앙일때 쪽바 폭 계산
  h= 29*2;                // 비상문이 중앙일때 쪽바 매수

  if($("#exititem").val()=='0') {
  b=$("#cutwidth").val() ;
  $("#text2").text(memo +"  " + " 절단 : " + b + "(mm) X  " + a + "(매), " + "수량: " + amount + "틀");
  }
  if($("#exititem").val()=='1') {
  b=$("#cutwidth").val();
  $("#text2").text(memo +"  " +"상바 : " + b + "(mm) X " + c + "(매),  쪽바 : " + d + "(mm) X " + h + "(매),  비상문바 : 900(mm) x 29(매), " + "수량: " + amount + "틀" + " (" + hinge + "_" + hingenum +")");
  }  
if($("#exititem").val()=='2' || $("#exititem").val()=='3' ) {
  i=b-f-g-900; 
  b=$("#cutwidth").val() ;
  $("#text2").text(memo +"  " +"상바 : " + b + "(mm) X " + c + "(매),  1번 쪽바 : " + g + "(mm) X 29(매), 2번 쪽바 : " + i + "(mm) X 29(매),  비상문바 : 900(mm) x 29(매), " + "수량: " + amount + "틀" + " (" + hinge + "_" + hingenum  +")" );
  }    
  
  
}
  // end of function
  
 function exitcenter() {
  			$("#exityesno").show();	  
			$("#hinge").val("승리");				
			$("#hingenum").val("85");				
            $("#intervalnum").val("없음");			
  }
  function exitleft() {
  			$("#exityesno").show();	  
		    $("#hinge").val("승리");				
			$("#hingenum").val("85");	
            $("#intervalnum").val("");				
  }
  function exitright() {
  			$("#exityesno").show();	
		    $("#hinge").val("승리");				
			$("#hingenum").val("85");	
            $("#intervalnum").val("");							
  }  

  
function addline(){
 calsize_exe();
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}
function update(){
 calsize_exe();
 $("#modify").val("1");			// 수정할 부분 연결
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}

function sorting(){
 var sort;
 sort=$("#sort").val();		
 if(sort=='1')
     $("#sort").val("2");
   else
	   $("#sort").val("1");
 $("#modify").val("2");			// 소팅할 것
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}

function del(href) 
     {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          }
}
function move(href) 
     {
           document.location.href = href;  
}	 

function load(href) 
     {
           document.location.href = href;  
}	



function partShot() {
//특정부분 스크린샷
html2canvas(document.getElementById("containers"))  
//id container 부분만 스크린샷
.then(function (canvas) {
//jpg 결과값
drawImg(canvas.toDataURL('image/jpeg'));
//이미지 저장
saveAs(canvas.toDataURL(), 'make.jpg');
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

function submit_form(frm) { 
frm.action='delete.php'; 
frm.submit(); 
return true; 
}

function info() {
	return;
}
</script>  


</html>



