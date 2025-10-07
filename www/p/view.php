 <?php
 session_start(); 
 
 ?>
 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
 <?php
 $file_dir = '../uploads/'; 
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 
 
$workername = $_REQUEST["workername"];

if($workername !== '' and  $workername !== null )
{
	$id_name=$workername;	 
	$user_name=$workername;	
}
 else
 {
   $level= $_SESSION["level"];
   $id_name= $_SESSION["name"];   
   $user_name= $_SESSION["name"];  
 }
 
 

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
 
 if(isset($_REQUEST["page"]))
 {
    $page=$_REQUEST["page"]; 
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
	  $doneday=$row["doneday"];  // 시공완료일  
	  $attachment=$row["attachment"];   
	  
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
	  $update_log=$row["update_log"];
	  $outsourcing=$row["outsourcing"];
	  
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
	  $madeconfirm=$row["madeconfirm"];  
	  $assigndate=$row["assigndate"];  
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
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";	
		      if($assigndate!="0000-00-00" and $assigndate!="1970-01-01" and $assigndate!="")  $assigndate = date("Y-m-d", strtotime( $assigndate) );
					else $assigndate="";									
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }  
   
	$todate=date("Y-m-d")  // 현재일 저장      
?>
 
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
<div class="container-fluid">        
  <div class="card">
	<div class="card-body">
	
	<div class="d-flex  p-1 m-1 mt-5 mb-5 justify-content-start align-items-center ">   
	  <h3>
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
			<?=$user_name?> | 
			<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>		
		<?php
			 }
		?>
		</h3>
	</div>

<div class="d-flex  mt-2 mb-3 justify-content-center align-items-center ">   	 
		  <button type="button" class="btn btn-secondary " onclick="javascript:move_url('index.php?check=<?=$check?>&workername=<?=$workername ?>');"> 
		  목록 </button> &nbsp;			
		<?php
			if($workername!='서영선')
		{
		?>		  
		  <button type="button" class="btn btn-danger  " onclick="javascript:move_url('voc.php?num=<?=$num?>&check=<?=$check?>&workername=<?=$workername ?>');"> 
		  협의사항 </button> &nbsp;
		  <button type="button" class="btn btn-primary  " onclick="javascript:move_url('process_DB.php?num=<?=$num?>&check=<?=$check?>&measureday=<?=$todate?>&workername =<?=$workername ?>');"> 
		  실측완료 </button> &nbsp;			
		  <button type="button" class="btn btn-success   " onclick="javascript:move_url('process_done.php?num=<?=$num?>&check=<?=$check?>&doneday=<?=$todate?>&workername =<?=$workername ?>');">
		  시공완료 </button> &nbsp;
		  <button type="button" class="btn btn-secondary   " onclick="javascript:move_url('reg_pic.php?num=<?=$num?>&check=<?=$check?>&workername=<?=$workername ?>');"> 
		  전후 사진 </button> &nbsp;
		  <button type="button" class="btn btn-dark  " onclick="javascript:move_url('reg_ms.php?num=<?=$num?>&check=<?=$check?>&workername=<?=$workername ?>');"> 
		  실측서 이미지		 </button> &nbsp;	  	
		  <button type="button" class="btn btn-danger  fw-bold " onclick="javascript:popup('customer_input.php?num=<?=$num?>');"> 
		  TKE 공사완료 확인서 </button> 
		  <button type="button" class="btn btn-outline-dark mx-1 " onclick="self.close();"> 
		  닫기 </button> 
		</div>
		<?php
		} else {
			// 서영선 소장일때
		?>
		 <button type="button" class="btn btn-success   " onclick="javascript:move_url('process_done.php?num=<?=$num?>&check=<?=$check?>&doneday=<?=$todate?>&workername =<?=$workername ?>');">시공완료 </button> &nbsp;
		  <button type="button" class="btn btn-secondary   " onclick="javascript:move_url('reg_pic.php?num=<?=$num?>&check=<?=$check?>&workername=<?=$workername ?>');"> 전후 사진 </button> &nbsp;	
		  <button type="button" class="btn btn-danger  fw-bold " onclick="javascript:popup('customer_input_newone.php?num=<?=$num?>');">  공사완료 확인서 </button> 
		  <button type="button" class="btn btn-outline-dark mx-1 " onclick="self.close();"> 닫기 </button> 
		<?php
		}
		?>
</div>
<?php
	if($workername!='서영선')
		{
	?>	
	<div class="d-flex  p-1 m-1 mt-5 mb-1 align-items-center ">   
		<h5>  (실측)이 끝나면 상단의 "실측완료" 클릭, </h5> 
	</div>
	<div class="d-flex  p-1 m-1 mt-1 mb-1 align-items-center ">   
		<h5>  (시공)이 끝나면 "시공완료" 클릭, </h5> 
	</div>
	<div class="d-flex  p-1 m-1 mt-1 mb-5 align-items-center ">   
		<h5>  (시공 전/후 사진등록) "전후 사진등록" 클릭 </h5> 
	</div>	 
	<?php
		} // end of 서영선 소장이 아닐때 일때
	?>

 <?php   if($outsourcing ==='외주')	 { ?>
	 
	<div class="d-flex  justify-content-center  align-items-center ">  
		<span class="badge  bg-success fs-3" > <?=$outsourcing?> 가공 (DS레이져)  </span>
	</div>	
 <?php } ?>		
	
	<div class="d-flex   justify-content-center  p-1 m-1 mt-3 mb-3 align-items-center ">   
     <span class="badge bg-secondary fs-4" >		          
       현장명 :       <?=$workplacename?>
	   </span>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">   		 
		착공일:      <?=$startday?> 
 	</div>	
<?php
	if($workername!='서영선')
		{
	?>		
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">     	
		검사일 :   <span style="color:red;font-weight:bold;"> <?=$testday?> </span>
	</div>
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">    	
		실측일(소장님 입력) : 
	<input type="date" class="btn btn-secondary btn-lg fs-4" name=measureday id=measureday value="<?=$measureday?>"> &nbsp;
	<input type="button" class="btn btn-secondary btn-lg fs-4" value="수정" onclick="javascript:input_measureday('process_DB.php?num=<?=$num?>&check=<?=$check?>');"> 
 	</div>
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">     	
	     도면설계완료일 :  <?=$drawday?>
	</div>
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">    	
		 도면설계자 :   <?=$draw_done?>
	</div>
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">   	
	 	<span style="color:blue;font-weight:bold;" class="mx-1" > 출고예정일(최장중이사 협의) : </span> 
		<span style="color:red;font-weight:bold;" class="mx-1">  <?=$endworkday?>  </span>
	</div>	
	
	<?php
	   if($madeconfirm === '1')
	   {
		 ?>
				<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">   						
					<span class="badge bg-primary" >  제작완료 확인함  </span>
				</div>	
	<?php   }  ?>
	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
        제품출고일: <?=$workday?>
	</div>	
	<?php
		}
		?>

	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
        시공완료일(소장님 입력):<input type="date" class="btn btn-secondary btn-lg fs-4 " name=doneday id=doneday value="<?=$doneday?>">
		&nbsp;
		<input type="button" class="btn btn-secondary btn-lg fs-4 " value="수정" onclick="javascript:input_doneday('process_done.php?num=<?=$num?>&check=<?=$check?>');"> 
	</div>
<?php
if($workername!='서영선')
	{
	?>		
	<div class="d-flex p-1 m-1 mt-2 mb-2 align-items-center fs-4">   	
	
		막판 HPI 형태 : <span style="color:brown;font-weight:bold;"> <?=$hpi?></span>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
	 	
	재질 1 : 	<input disabled  type="text" value="<?=$material1?> <?=$material2?> "    size="40" > 
	
	<?php
    $sum_mat1 = $material3 . $material4 ;	
	if($sum_mat1!="") 
	{
        print ' </div> <div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4"> ' ;
        print ' 재질 2 : 	<input disabled  type="text"  value="' . $sum_mat1 . ' " size="40">  ' ;        
	}
    $sum_mat2 = $material5 . $material6 ;	
	if($sum_mat2!="") 
	{		
	    print ' </div> <div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4"> ' ;
        print ' 재질 3 : 	<input disabled  type="text"  value="' . $sum_mat2 . ' " size="40"> ' ;
	}
	?>
	</div>	
	<div class="d-flex p-1 m-1 mt-5 mb-3 align-items-center fs-4">  
	 <div style="color:red; font-weight:bold;" >< 총 설치 수량> </div>
	</div>	
	<?php if($outsourcing ==='외주')	 { ?>
		
		<div class="d-flex p-1 m-1 mt-5 mb-3 align-items-center ">  
			<span class="badge  bg-success fs-4" > <?=$outsourcing?> 가공 (DS레이져)  </span>
		</div>	
	<?php } ?>	
 
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  	 
   <?php
	  if($widejamb>0) 
           print '<div class="alert alert-info" role="alert">막판 : '. $widejamb . ' </div>';
	  if($normaljamb>0) 	   
           print '<div class="alert alert-warning" role="alert">막판(無) : ' . $normaljamb . '</div>';
	  if($smalljamb>0) 	   	   
           print '<div class="alert alert-danger" role="alert">쪽쟘 : ' . $smalljamb . '</div>';
	   	  if($attachment!="") 
           print '<div class="alert alert-info" role="alert"> 부속자재 : '. $attachment . ' </div>';
          ?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
		<span style="color:blue;font-weight:bold;"> 미래기업 쟘설치소장 : <?=$worker?>  </span>	
	</div>	
	<?php
	 } // end of 서영선 소장이 아닐때 일때
	?>	

	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  	 
         현장주소 : <?=$address?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  	 
		  원  청 :  <?=$firstord?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  	 
		원청담당(PM) : <?=$firstordman?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  		 
		  연락처 : <a href="tel:<?=$firstordmantel?>">   <?=$firstordmantel?> </a>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  	 
	     발주처 : <?=$secondord?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  		 		 
	    발주처담당 : <?=$secondordman?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  		 		 		
	    연락처 : <a href="tel:<?=$secondordmantel?>"> <?=$secondordmantel?> </a>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  		 		 		

<?php

$keywords = array('tk', '티케', '티센');

// Check if any of the keywords are present in the variables
$showLink = false;
foreach ($keywords as $keyword) {
    if (stripos($firstord, $keyword) !== false || stripos($secondord, $keyword) !== false) {
        $showLink = true;
        break;
    }
}

// Output the link with a popup if the condition is met
if ($showLink) {
    echo '  <div class="card" style="border-width: 4px;"> ';
    echo '  <div class="card-body"> ';    	
    echo '(TKE 협력사 현장 출입 관리 시스템) ';
	echo '</div>';
    echo '<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  ';	
    echo '<a href="javascript:void(0);" onclick="openPopup()"> https://customer.tkek.co.kr/stop/_enter </a>';    
    echo '</div>';
    echo '<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  ';
	echo '업체코드:50001672 본인성명 /시공날짜 당일로 지정  예)7/4~7/4 ';
    echo '</div>';
    echo '<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  ';	
	echo '** 반드시 선택은 &nbsp; <span style="color:red;"> 조달->박일우 </span> &nbsp; 매우 중요함';	
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  ';
}
?>
		 		 				
     현장소장 : <?=$chargedman?>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
     현장소장연락처 : <a href="tel:<?=$chargedmantel?>">  <?=$chargedmantel?> </a>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
        접수일: <input disabled  type="text" name="assigndate" id="assigndate" value="<?=$assigndate?>" size="10"  > 
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
		추가 메모(기타 사항) : 
  	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
		<textarea disabled rows="10" cols="35" name="memo" placeholder="추가적으로 기록할 내역" ><?=$memo?></textarea>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  

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

  협의사항 기록 :
  	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
  <textarea disabled rows="10" cols="35" name="memo" placeholder="협의사항 기록 내역" style="color:brown;" ><?=$content?></textarea>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3  justify-content-center align-items-center fs-4">  
		     시공 전 사진 
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  
	   <div class='imagediv' >
		<?php
			 if($filename1!="") 
				print '<img class="before_work" src="' . $imgurl1 . '" >';
		   ?>
		   </div>
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3  justify-content-center align-items-center fs-4">  
		시공 후 사진 
	</div>	
	<div class="d-flex p-1 m-1 mt-2 mb-3 align-items-center fs-4">  	          
    <div class='imagediv' >
		<?php
	     if($filename2!="") 
		  print '<img class="after_work" src="' . $imgurl2 . '" >';
	   ?>	                
	   </div>  	        
		   
	   </div>	    		   
	   </div> 
	</div>
 </div>
  </body>
</html>    
 
 
 <script language="javascript">
 
 function openPopup() {
    // Modify the window features as needed (width, height, etc.)
    var popup = window.open("https://customer.tkek.co.kr/stop/_enter", "TKE 등록화면", "width=700,height=800");
    // Focus the popup window
    if (window.focus) {
        popup.focus();
    }
}
 
 
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

function input_measureday_btn(href)
     {
     if(confirm("현재일로 실측일을 전송합니다.\n\n 정말 본사 전산에 입력 하시겠습니까?")) {		 
         document.location.href = href ;		 
    }
}

function input_doneday_btn(href)
     {
     if(confirm("현재일로 시공완료일을 전송합니다.\n\n 전산에 기록 하시겠습니까?")) {
         document.location.href = href ;	 
    }
}
function input_measureday(href)
     {
     if(confirm("수정된 실측일을 전송합니다.\n\n 정말 본사 전산에 입력 하시겠습니까?")) {
		 var measureday = $("#measureday").val() ;
         document.location.href = href + "&measureday=" + measureday;		 
    }
}

function input_doneday(href)
     {
     if(confirm("수정된 시공완료일을 전송합니다.\n\n 전산에 기록 하시겠습니까?")) {
		 var doneday = $("#doneday").val() ;		 
         document.location.href = href + "&doneday=" + doneday;	 
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

function popup(href)
{
	  popupCenter(href,'공사완료확인서', 1000, 1000); 
}

// 사진 회전하기
function rotate_image()
{	
 var box = $('.imagediv');
 var imgObj = new Image();
 var imgObj2 = new Image();
 imgObj.src = "<? echo $imgurl1; ?>" ; 
 imgObj2.src = "<? echo $imgurl2; ?>" ; 
 box.css('width','700px');
 box.css('height','1000px');
 box.css('margin-top','200px');
 
 if( imgObj.width > imgObj.height  ||  imgObj2.width > imgObj2.height)
   {
		$('.before_work').addClass('rotated');
		$('.after_work').addClass('rotated');		
   }

}

setTimeout(function() {
 // console.log('Works!');
 rotate_image();
}, 1000);



</script>
