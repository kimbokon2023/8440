<?php\nrequire_once __DIR__ . '/../common/functions.php';
// 수정작업 2023년 7월 14일 UI 개선
// 설계자 자동입력 수정 23/09/14
    if(!isset($_SESSION)) 
    { 
        session_start(); 
		
$user_name = $_SESSION["name"];

 
 if($user_name==='이미래' || $user_name==='김보곤')
	 $isAuthorizedUser = true;
    else
		$isAuthorizedUser = false;

// print_r($user_name);
		
  } 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:http://8440.co.kr/login/logout.php"); 
         exit;
   }   

//header("Refresh:0");  // reload refresh   
 ?>


 <?php include getDocumentRoot() . '/load_header.php' ?>
 
 
 <title> 미래기업 Jamb 수주 </title>

</head>


  <style>
  

.bigPictureWrapper {
	position: absolute;
	display: none;
	justify-content: center;
	align-items: center;
	top:0%;
	width:100%;
	height:100%;
	background-color: gray; 
	z-index: 100;
	background:rgba(255,255,255,0.5);
}
.bigPicture {
	position: absolute;
	display:flex;
	justify-content: center;
	align-items: center;
}

.bigPicture img {
	height:100%; /*새로기준으로 꽉차게 보이기 */
}

@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");

.rotated {
  transform: rotate(90deg);
  -ms-transform: rotate(90deg); /* IE 9 */
  -moz-transform: rotate(90deg); /* Firefox */
  -webkit-transform: rotate(90deg); /* Safari and Chrome */
  -o-transform: rotate(90deg); /* Opera */
}
  
a {
text-decoration:none;
  }	    
  
  
	.table, td, input {
		font-size: 14px !important;
		vertical-align: middle;
		padding: 1px; /* 셀 내부 여백을 5px로 설정 */
		border-spacing: 1px; /* 셀 간 간격을 5px로 설정 */
		text-align:center;
	}
	
 
 tbody, td, tfoot, th, thead, tr {
	  border-style : none !important;
 }
 
   .table-fixed {
    table-layout: fixed;
    width: 100%;
  }
  
  .table td {
      padding: 1px;
    }
  
	  
  .table td input.form-control, textarea.form-control  , select.form-control  {    
    border: 1px solid #392f31; /* 테두리 스타일 추가 */
    border-radius: 4px; /* 테두리 라운드 처리 */	  
	font-size:13px;
  } 
  
  .table td input.form-control,  select.form-control  {    
    height: 30px;
  }
  
  input[type="checkbox"] {
    transform: scale(1.8); /* 크기를 1.5배로 확대 */
    margin-right: 10px;   /* 확대 후의 여백 조정 */
}
  
</style> 
 

<body>

  <?php require_once(includePath('myheader.php')); ?>   


 <?php
 
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

 $yearcheckbox=$_REQUEST["yearcheckbox"];   // 년도 체크박스
 $year=$_REQUEST["year"];   // 년도 체크박스
      
  require_once("../lib/mydb.php");
  $pdo = db_connect();

  if ($mode=="copy"){
    try{
      $sql = "select * from mirae8440.work where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $item_file_0 = $row["file_name_0"];
      $item_file_1 = $row["file_name_1"];

      $copied_file_0 = "../uploads/". $row["file_copied_0"];
      $copied_file_1 = "../uploads/". $row["file_copied_1"];
	 }
	 
  include '_row.php'; 	 
	 
  $orderday=date("Y-m-d");      // 현재일자 저장
  $measureday=null;
  $drawday=null;
  $deadline=null;
  $workday=null;
  $worker=$row["worker"];
  $endworkday=null;
  // 시공소장이 선정되어있으면 배정일 현재일로 기록
  if($worker!=='')
	$assigndate=date("Y-m-d"); 	
  else
	  $assigndate=null; 	

  $delicar="없음";
  $delipay="";
  $delimethod="없음";
  $demand=null;
   $work_order="";  
  $outsourcing=null ;


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
		      // if($workfeedate!="0000-00-00" and $workfeedate!="1970-01-01" and $workfeedate!="")  $workfeedate = date("Y-m-d", strtotime( $workfeedate) );
					// else $workfeedate="";						

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }
$mode="";

  require_once("../lib/mydb.php");
  $pdo = db_connect();
  
   $sql="select * from mirae8440.steelsource"; 					

	 try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=1;
   $item_counter=1;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();
   $material_arr=array();
   $designer_arr=array();
   $steelsource_spec_yes=array();
   $spec_arr=array();
   $last_item="";
   $last_spec="";
   $pass='0';
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=$row["item"] . " 1.2T";
 			  $steelsource_spec[$counter]=$row["spec"];
		      $steelsource_take[$counter]=$row["take"];   
			  
			  if($steelsource_item[$counter]!=$last_item)
			  {
				 $last_item= $steelsource_item[$counter];
			     $material_arr[$item_counter]=$last_item;
				 $item_counter++;
			  }
			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    


array_push($material_arr,'','304 Hair Line 1.2T','304 HL 1.2T','304 Mirror 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)',  '기타', '304 HL 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 VB 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)' );
$material_arr = array_unique($material_arr);
sort($material_arr);
?>

  <?php
if($mode=="modify"){
  ?>
	<form  name="board_form" id="frm"  method="post" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&scale=<?=$scale?>&stable=1" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  name="board_form" id="frm"  method="post" action="insert.php?mode=not&scale=<?=$scale?>" enctype="multipart/form-data"> 
  <?php
	}
  ?>	  

	      
				<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
				<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
				<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
				<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
				<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
				<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
				<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
				<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
				<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
				<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
				<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
				<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
				<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
				<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
				<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
				<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
				<input type="hidden" id="list" name="list" value="<?=$list?>" > 								
				<input type="hidden" id="buttonval" name="buttonval" value="<?=$buttonval?>" > 		
			  
			   <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
			   <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
			     



<div class="container-fluid">	  
<div class="card">	  

   
<div class='bigPictureWrapper'>
	<div class='bigPicture'>
	</div>	   
</div>   
   	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title"> 알림 </h4>
        </div>
        <div class="modal-body fs-5">
		
		<input type="hidden" name="saleprice_val" id="saleprice_val" > 
		<input type="hidden" name="cartsave" id="cartsave" > 
		
		 <input type="text" id="insertword" name="insertword" size="70" class="text-primary" value=""> 		 
		 
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
			<br>
			도면저장 폴더위치가 복사되었습니다. <br>
		    탐색기에 'Ctrl+v'로 붙여넣기 하세요!
		  <br>
			</div>		 
		 
		  			
                    </div>			  
        <div class="modal-footer">
          <button id=closeModalBtn type="button" class="btn btn-default" >닫기</button>
        </div>
      </div>
      
    </div>
  </div>
	  
	<div class="card-header mt-1 rounded-pill justify-content-start">		
<div class="d-flex justify-content-start mb-1 mt-1"> 		
	   <h3>   Jamb 수주 내역 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </h3>
						
           <button type="button" class="btn btn-dark btn-sm" onclick="document.getElementById('frm').submit();"> 완료 </button> &nbsp;  	   
           <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='list.php';"> 목록 </button> &nbsp;  	   
	   
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; 
					등록 : <?=$first_writer?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					  <?php
						      $update_log_extract = substr($update_log, 0, 31);  // 이미래....
					  ?>
					최종수정 : <?=$update_log_extract?> &nbsp;&nbsp;&nbsp;
					
				<button type="button" class="btn btn-outline-dark btn-sm" id="showlogBtn"   >								
					Log 기록
				</button>					
					
	</div>
	</div>
		 
<?php if ($chkMobile): ?>
	<div class="col-sm-12 rounded"   style=" border: 2px solid #392f31; " > 
<?php else: ?>

     <div class="d-flex justify-content-center mb-1 mt-1"> 	
	<div class="col-sm-7 p-1  rounded"   style=" border: 2px solid #392f31; " > 
<?php endif; ?>	

 <table class="table table-bordered" >
  <tbody>
    <tr  style="border: none;">
      <td class="col"  >현장명</td>
      <td colspan="3" >
        <input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" class="form-control" style="text-align:left; " required>
      </td>	
	 <td colspan="2" >
		   공사유형 : &nbsp;		
				 <?php
				 
					 $aryreg=array();
					 switch ($checkstep) {
						case   ""         : $aryreg[0] =  "checked" ; break;
						case   "신규"             : $aryreg[1] =  "checked" ; break;	
						default: break;
					}		 
							?>	
	 
		  <input type="radio" <?=$aryreg[0]?> name=checkstep value="" >   덧방   &nbsp;&nbsp;&nbsp;  
		  <input type="radio" <?=$aryreg[1]?> name=checkstep value="신규" > 신규 	 
		  </td>	
		  <td colspan="2" >
		  <h5> <span class="badge bg-success ">외주가공</span>    &nbsp;		 
			<input type="checkbox" 
				   id="outsourcing" 
				   name="outsourcing" 
				   value="외주"
				   onclick="updateWorkOrder();"
				   <?php echo ($outsourcing === '외주') ? 'checked' : ''; ?>>

			</h5>
		  </td>			  
		</tr>
		<tr>		 
	<tr>
      <td class="col">주소</td>
      <td colspan="3"><input type="text" name="address" value="<?=$address?>" class="form-control" style="text-align:left;">
      </td>		
      
    </tr>
<tr>
      <td class="col" style="width:10%;" > 원청</td>
      <td class="col" style="width:18%;" >   <input type="text" id="firstord" name="firstord" value="<?=$firstord?>" class="form-control"></td>
      <td class="col" style="width:10%;" > 담당</td>
      <td class="col" style="width:18%;" >   
		  <input type="text" id="firstordman" name="firstordman" size="16" value="<?=$firstordman?>" onkeydown="if(event.keyCode == 13) { phonebookBtn('firstordman', 'firstordmantel','true'); }">
		  <button type="button" class="btn btn-dark-outline btn-sm" onclick="phonebookBtn('firstordman','firstordmantel','false');">  <ion-icon name="settings-outline"></ion-icon> </button>
	  </td>
      <td class="col" style="width:10%;" > Tel</td>
      <td class="col" style="width:18%;" >   <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control"></td>
	  <td> </td>
    </tr>
    <tr>
      <td class="col">발주처</td>
      <td class="col"><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
      <td class="col">담당</td>
      <td class="col">	  
          <input type="text" id="secondordman" name="secondordman" size="16" value="<?=$secondordman?>" onkeydown="if(event.keyCode == 13) { phonebookBtn('secondordman', 'secondordmantel','true'); }">
		  <button type="button" class="btn btn-dark-outline btn-sm" onclick="phonebookBtn('secondordman','secondordmantel','false');">  <ion-icon name="settings-outline"></ion-icon> </button>
	  </td>
      <td class="col">Tel</td>
      <td class="col"><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
	  <td> </td>
    </tr>
    <tr>
      <td class="col"></td>
      <td class="col"></td>
      <td class="col">현장담당</td>
      <td class="col">	  
          <input type="text" id="chargedman" name="chargedman" size="16" value="<?=$chargedman?>" onkeydown="if(event.keyCode == 13) { phonebookBtn('chargedman', 'chargedmantel','true'); }">
		  <button type="button" class="btn btn-dark-outline btn-sm" onclick="phonebookBtn('chargedman','chargedmantel','false');">  <ion-icon name="settings-outline"></ion-icon> </button>
	  </td>	  
      <td class="col">Tel</td>
      <td class="col"><input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control"></td>
	  <td> </td>	  
    </tr>
  </tbody>
</table>

	<table class="table table-responsive  table-fixed">
  <tbody>
    <tr>
      <td class="col" style="width:8%;"> 접수</td>
      <td class="col" style="width:16%;"> <input type="date" name="orderday" id="orderday" value="<?=$orderday?>" class="form-control"> </td>
      <td class="col"  style="width:8%;"> 실측</td>
      <td class="col" style="width:16%;"> <input type="date" name="measureday" id="measureday" value="<?=$measureday?>" placeholder="실측" class="form-control"></td>
      <td class="col"  style="width:8%;"> 설계</td>      
      <td class="col" style="width:16%;">
		   <select name="designer" id="designer" class="form-control" >
           <?php		 
		   
           array_push($designer_arr,' ','이미래','이소정','김은비');

           $mat_count = sizeof($designer_arr);
		   
	   for($i=0;$i<$mat_count;$i++) {
			     if($designer==$designer_arr[$i])
							print "<option selected value='" . $designer_arr[$i] . "'> " . $designer_arr[$i] .   "</option>";
					 else   
			   print "<option value='" . $designer_arr[$i] . "'> " . $designer_arr[$i] .   "</option>";
		   } 	
		   ?>
      </td>
      <td class="col"  style="width:8%;"> 도면<br>완료</td>
      <td class="col" style="width:16%;"> <input type="date" name="drawday" id="drawday" value="<?=$drawday?>" placeholder="도면설계" class="form-control"></td>
      <td class="col"  style="width:12%;">  도면폴더</td>
      <td class="col" style="width:16%;"> <input type="text" name="dwglocation" id="dwglocation" value="<?=$dwglocation?>" class="form-control"  placeholder="Nasdual 위치" ></td>
    </tr>
    <tr>
      <td class="col" style="color: blue;">출고</td>
      <td class="col"><input type="date" name="workday" id="workday" value="<?=$workday?>" placeholder="출고" class="form-control"></td>
      <td class="col" style="margin-left: 55px;">시공</td>
		<td class="col" style="position: relative;">
			<input type="text" id="worker" name="worker" value="<?=$worker?>" placeholder="시공팀" class="form-control" autocomplete="off" >
			<div id="worker-list" class="list-group" style="position: absolute; display: none;"></div>
		</td>
      <td class="col">순서</td>
      <td class="col"><input type="text" name="work_order" id="work_order" value="<?=$work_order?>" class="form-control"></td>
      <td class="col" style="color: brown;">생산<br>예정</td>
      <td class="col"><input type="date" name="endworkday" id="endworkday" value="<?=$endworkday?>"  class="form-control"></td>
      <td class="col" style="width:16%; color: red;">생산<br>완료</td>
      <td class="col"><input type="date" name="deadline" id="deadline" value="<?=$deadline?>" class="form-control"></td>
    </tr>
    <tr>
      <td class="col" style="color: black;">착공</td>
      <td class="col"><input type="date" name="startday" id="startday" value="<?=$startday?>" placeholder="착공일" class="form-control"></td>
      <td class="col text-success">배정일</td>
      <td class="col"><input type="date" name="assigndate" id="assigndate" value="<?=$assigndate?>"  class="form-control"></td>
      <td class="col" style="color: red;">검사</td>
      <td class="col"><input type="date" name="testday" id="testday" value="<?=$testday?>" placeholder="검사일" class="form-control"></td>
      <td class="col" style="color: green;">시공<br> 완료</td>
      <td class="col"><input type="date" name="doneday" id="doneday" value="<?=$doneday?>" class="form-control"></td>
        <td class="col" colspan="2" ><button type="button" class="btn btn-secondary btn-sm" id="cleardate" > 날짜 및 기초자료 초기화 </button>  </td>
    </tr>	   
    <tr>
      <td class="text-danger" >시공비<br>처리일</td>
      <td><input type="date" name="workfeedate" id="workfeedate" class="form-control" ></td>      
      <td class="text-danger" >청구</td>
      <td><input type="date" name="demand" id="demand"   class="form-control"  ></td>

    </tr>
  </tbody>
</table>
 
  
  <table class="table table-responsive table-fixed">
    <tbody>
      <tr>
        <td class="col" style="width:10%;">재질1</td>
        <td class="col" style="width:23%;">   
			<input type="checkbox" name="checkmat1" id="checkmat1" value="checked" <?=$checkmat1?>  style="font-size:13px;" > 사급
			<input type="text" name="material1" id="material1" value="<?=$material1?>" size="20" style="font-size:13px;" >
				<select name="material2" id="material2" class="form-control" style="font-size:13px;">
				<?php		 
				$mat_count = sizeof($material_arr);

				for($i=0;$i<$mat_count;$i++) {
				 if($material2==$material_arr[$i])
							print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] .   "</option>";
					 else   
				print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] .   "</option>";
				} 		   		   
				?>	  

				</select> 			

        </td>          
		<td class="col" style="width:10%;">재질1</td>
        <td class="col" style="width:23%;">   
			<input type="checkbox" name="checkmat2" id="checkmat2" value="checked"  <?=$checkmat2?>  style="font-size:13px;" > 사급
			<input type="text" name="material3" id="material3" value="<?=$material3?>" size="20" style="font-size:13px;" >
				<select name="material4" id="material4" class="form-control" style="font-size:13px;">
				<?php		 
				$mat_count = sizeof($material_arr);

				for($i=0;$i<$mat_count;$i++) {
				 if($material4==$material_arr[$i])
							print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] .   "</option>";
					 else   
				print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] .   "</option>";
				} 		   		   
				?>	  

				</select> 			

        </td>             
		<td class="col" style="width:10%;">재질1</td>
        <td class="col" style="width:23%;">   
			<input type="checkbox" name="checkmat3" id="checkmat3" value="checked"  <?=$checkmat3?>  style="font-size:13px;" > 사급
			<input type="text" name="material5" id="material5" value="<?=$material5?>" size="20" style="font-size:13px;" >
				<select name="material6" id="material6" class="form-control" style="font-size:13px;">
				<?php		 
				$mat_count = sizeof($material_arr);

				for($i=0;$i<$mat_count;$i++) {
				 if($material6==$material_arr[$i])
							print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] .   "</option>";
					 else   
				print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] .   "</option>";
				} 		   		   
				?>	  

				</select> 			

        </td>              
	  </tr>
    </tbody>
  </table>

  
  <table class="table table-responsive table-fixed">
  <tbody>
    <tr>	   
      <td style="width:8%;"> 막판 <input type="text" id="widejamb" name="widejamb" value="<?=$widejamb?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
      <td style="width:8%;"> 막판(無) <input type="text" id="normaljamb" name="normaljamb" value="<?=$normaljamb?>"  class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
      <td style="width:8%;"> 쪽쟘<input type="text" id="smalljamb" name="smalljamb" value="<?=$smalljamb?>"  class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
      <td style="width:8%;"> 갭커버<input type="text" id="gapcover" name="gapcover" value="<?=$gapcover?>"  class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
	<td style="width:26%;"> HPI<input type="text" id="hpi" name="hpi" value="<?=$hpi?>"  class="form-control text-danger"></td>    
	<td style="width:45%;"> 부속자재 <input type="text" name="attachment" id="attachment" value="<?=$attachment?>"   class="form-control text-primary"  > </td>
    </tr>    
  </tbody>
</table>  	  
  
 <table class="table table-responsive table-fixed">
  <tbody>
    <tr>	   
      <td <?php if (!$isAuthorizedUser)
					echo ' style="width:50%;"'; 
	             else
					 echo ' style="width:40%;"'; 
	  
	  
	  ?> class="text-primary" >함께 보는 메모 </td>	  
      <td class="text-danger" 
       <?php if (!$isAuthorizedUser) 
					echo ' style="width:50%;"'; 
	             else
					 echo ' style="width:40%;"'; 	  
	  
	  ?>  > 소장 Voc   </td>
	  
	  <?php if ($isAuthorizedUser) { ?>
		<td style="width:30%;" class="text-dark" > 
		<div id="user_display1" <?php if (!$isAuthorizedUser) echo 'style="display:none;"'; ?>>
		(이미래) 메모 
		</div>
		</td>	  		  
	  <?php } ?>
    </tr>	
    <tr>
       <td>
	   <textarea rows="3" name="memo" id="memo"  class="form-control text-primary"  ><?=$memo?></textarea>
	   </td>  
	   <td> 
			<textarea disabled   rows="3" id="content"  name="content"  class="form-control text-danger" ><?=$content?></textarea>
		</td>
	   <td>
		<div id="user_display2" <?php if (!$isAuthorizedUser) echo 'style="display:none;"'; ?>>
				<textarea rows="3" name="mymemo" id="mymemo"  class="form-control text-dark"  ><?=$mymemo?></textarea>  
			</div>
		</td>			
   </tr>  
   <tr>	   	   
      <td > 추가정산  </td>
	  <td colspan="2">
	  <input class="form-control text-danger" id="memo2" name="memo2" value="<?=$memo2?>" style="text-align:left;" > </td>
    </tr>   
  </tbody>
</table>  	
   
    <table class="table table-responsive">
 
 <thead>
    <tr>
      <td colspan="3" class="text-success text-center" > (비표준) 막판 시공비 </td>
	  <td colspan="3" class="text-success text-center" >  (비표준) 막판(無)시공비  </td>
	  <td colspan="4" class="text-success text-center" > (비표준) 쪽쟘 시공비 </td>
	</tr>
</thead>
 <tbody> 
	<tr>
	  <td colspan="3" class="text-success text-center" > <input type="text" id="widejambworkfee" name="widejambworkfee" value="<?=$widejambworkfee?>"  class="form-control text-success text-center fw-bold"></td>    
	  <td colspan="3" class="text-success text-center" > <input type="text" id="normaljambworkfee" name="normaljambworkfee" value="<?=$normaljambworkfee?>"  class="form-control text-success fw-bold" ></td>    
	  <td colspan="4" class="text-success text-center" > <input type="text" id="smalljambworkfee" name="smalljambworkfee" value="<?=$smalljambworkfee?>"  class="form-control text-success fw-bold" ></td>    
	</tr>      
    <tr>
      <td> 검색 보류 설정</td>
      <td>
        <?php
          if ($checkhold == '보류') {
            echo "<input type='checkbox' name='checkhold' id='checkhold' value='보류' checked> 보류 (미출고 검색안됨)";
          } else {
            echo "<input type='checkbox' name='checkhold' id='checkhold' value='보류'> 보류 (미출고 검색안됨)";
          }
        ?>
      </td>
    </tr>
  </tbody>
</table>  
  
  
  
  
</div>


<?php if ($chkMobile): ?>
	<div class="col-sm-12 rounded"   style=" border: 2px solid #392f31; " > 
<?php else: ?>
    <div class="col-sm-5 p-1  rounded"   style=" border: 2px solid #392f31; " > 
<?php endif; ?>	

		
</div> 	
  
</div>
</div>
	
	
	
</div>
</form>
		
</body>
</html>    

<script>


// 예시 시공소장 리스트
var com1_arr = [' ', '추영덕', '이만희', '김운호', '김상훈', '유영', '손상민', '조장우', '박철우', '이인종', '김진섭'];
var workerInput = document.getElementById('worker');
var workerList = document.getElementById('worker-list');

// worker 입력 필드에 focus 이벤트 리스너 설정
workerInput.addEventListener('focus', function () {
    workerList.style.display = 'block';
    workerList.style.top = (workerInput.offsetHeight + workerInput.nextElementSibling.offsetTop - 20) + 'px';
    workerList.style.left = workerInput.offsetLeft + 'px';

    workerList.innerHTML = '';
    com1_arr.forEach(function (item) {
        var listItem = document.createElement('div');
        listItem.className = 'list-group-item list-group-item-action';
        listItem.textContent = item;

        listItem.addEventListener('click', function () {
            workerInput.value = item;
            workerList.style.display = 'none';

            // worker 필드 값 변경 시 assigndate 업데이트
            var currentDate = new Date().toISOString().slice(0, 10);
            document.getElementById('assigndate').value = currentDate;
        });

        workerList.appendChild(listItem);
    });
});

// 전역 클릭 이벤트 리스너 설정
document.addEventListener('click', function (event) {
    if (event.target !== workerInput && event.target !== workerList) {
        workerList.style.display = 'none';
    }
});

$(document).ready(function() {
    // worker 필드에 대한 change 이벤트 리스너 설정
    $('#worker').change(function() {
        // worker 필드 값 가져오기
        var workerValue = $(this).val().trim();

        // assigndate 필드 선택
        var assigndateInput = $('#assigndate');

        // worker 필드의 값이 비어있지 않은 경우
        if (workerValue) {
            // 현재 날짜를 YYYY-MM-DD 형식으로 가져오기
            var currentDate = new Date().toISOString().slice(0, 10);
            assigndateInput.val(currentDate);
        } else {
            // worker 필드가 비어있다면, assigndate 필드 초기화
            assigndateInput.val('');
        }
    });
});




document.getElementById('drawday').addEventListener('change', function() {
	user_name = '<?php echo $user_name;?>';
    document.getElementById('designer').value = user_name;
});

function updateWorkOrder() {
    const outsourcingCheckbox = document.getElementById('outsourcing');
    const workOrderElement = document.getElementsByName('work_order')[0]; // 첫 번째 'work_order' 이름을 가진 요소를 선택

    if (outsourcingCheckbox.checked) {
        workOrderElement.value = '외주';
    } else {
        workOrderElement.value = '';
    }
}

$(document).ready(function(){
	
// 출고일자 넣으면 생산완료일자 자동 넣어줌
// Get the elements for workday and deadline inputs
var workdayInput = document.getElementById("workday");
var deadlineInput = document.getElementById("deadline");

// Add an event listener to the workday input
workdayInput.addEventListener("change", function() {
  // Get the value of the workday input
  var workday = workdayInput.value;
  
  // Set the workday as the value of the deadline input
  deadlineInput.value = workday;
});	
	
		// Log 파일보기
		$("#showlogBtn").click( function() {     	
		    var num = '<?php echo $num; ?>' 
			// table 이름을 넣어야 함
		    var workitem =  'work' ;

			// 버튼 비활성화
			var btn = $(this);			
			
			    popupCenter("../Showlog.php?num=" + num + "&workitem=" + workitem , '로그기록 보기', 500, 500);		
							 
			btn.prop('disabled', false);					 
					 
		});	
		

    $("#cleardate").click(function(){      // workday가 바뀌면 생산예정일 날짜 바꿈
		$("#measureday").val("");
		$("#drawday").val("");
		$("#workday").val("");
		$("#deadline").val("");
		$("#endworkday").val("");
		$("#startday").val("");
		$("#testday").val("");
		$("#doneday").val("");
		$("#designer").val("");
		$("#dwglocation").val("");
		$("#worker").val("");
		$("#work_order").val("");
		$("#assigndate").val("");

    });		
	
    $("#workday").change(function(){      // workday가 바뀌면 생산예정일 날짜 바꿈, 출고일이 바뀌면 동작하는 것       
	    var deadline = $('#deadline').val();
	    var endworkday = $('#endworkday').val();
		
		if(endworkday !== null)
            $('#deadline').val(endworkday);

    });		

	$("input:text[id='widejambworkfee'], input:text[id='normaljambworkfee'], input:text[id='smalljambworkfee']").on("keyup", function() {
		$(this).val(formatNumber($(this).val()));
	});	
	
	
		
});	 // end of ready document

function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
  var copyText = document.getElementById("test");   // 클립보드 복사 
  copyText.select();
  document.execCommand("Copy");
}  

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;
	
    }
}

function phonebookBtn(firstitem,seconditem,enterpress)
{	
    var search = $("#" + firstitem).val();
	
    href = '../phonebook/list.php?search=' + search + '&firstitem=' + firstitem  + '&seconditem=' + seconditem  + '&enterpress=' + enterpress ;				
	popupCenter(href, '전화번호 검색', 600, 770);

}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}


function ordertoday() {
var _today = new Date(); 
printday=_today.format('yyyy-MM-dd');   
document.getElementById("orderday").value  = printday;

}  

// 재질 초기화
 if(confirm("재질을 초기화할 수 있습니다.\n\n재질 초기화 하시겠습니까?")) {
		document.getElementById("material1").value = "" ;	
		document.getElementById("material2").value = "" ;	
		document.getElementById("material3").value = "" ;	
		document.getElementById("material4").value = "" ;	
		document.getElementById("material5").value = "" ;	
		document.getElementById("material5").value = "" ;	
		
  // Initialize 재질1 checkbox as unchecked
  $('#checkmat1').prop('checked', false);
  
  // Initialize 재질2 checkbox as unchecked
  $('#checkmat2').prop('checked', false);
  
  // Initialize 재질3 checkbox as unchecked
  $('#checkmat3').prop('checked', false);		
		
		  // Initialize widejamb input
		  $('#widejamb').val('');

		  // Initialize normaljamb input
		  $('#normaljamb').val('');

		  // Initialize smalljamb input
		  $('#smalljamb').val('');

		  // Initialize hpi input
		  $('#hpi').val('');

		  // Initialize attachment input
		  $('#attachment').val('');

		  // Initialize memo textarea
		  $('#memo').val('');

		  // Initialize content textarea
		  $('#content').val('');

		  // Initialize memo2 input
		  $('#memo2').val('');

		  // Initialize widejambworkfee input
		  $('#widejambworkfee').val('');

		  // Initialize normaljambworkfee input
		  $('#normaljambworkfee').val('');

		  // Initialize smalljambworkfee input
		  $('#smalljambworkfee').val('');

		  // Initialize checkhold checkbox
		  $('#checkhold').prop('checked', false);
		
}


// 시공비 초기화	
document.getElementById("widejambworkfee").value = "" ;	
document.getElementById("normaljambworkfee").value = "" ;	
document.getElementById("smalljambworkfee").value = "" ;	

	
			
	function addComma(data) {
		return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	// 숫자가 아닌 문자를 제거한 후 콤마 추가
	function formatNumber(value) {
		return addComma(value.replace(/[^0-9]/g, ""));
	}

//모든 콤마 제거 방법
function removeCommas(data) {
    if(!data || data.length == 0){
    	return "";
    }else{
    	return x.split(",").join("");
    }
}	
	
	
</script>

