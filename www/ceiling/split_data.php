<?php

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
   
?>

 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
 
<script src="../js/calinseung.js"></script>    


<title> 천장&조명 수주내역 </title>

</head>

<body>

<?  if($navibar!='1') include '../myheader.php'; ?>   


  <style>
    .table, td, input {
      font-size: 14px !important;
	  vertical-align: middle;
	    padding: 2px; /* 셀 내부 여백을 5px로 설정 */
	    border-spacing: 2px; /* 셀 간 간격을 5px로 설정 */
		text-align:center;
    }
	  
  .table td input.form-control, textarea.form-control  {
    height: 25px;
    border: 1px solid #392f31; /* 테두리 스타일 추가 */
    border-radius: 4px; /* 테두리 라운드 처리 */
  }	
  
    input[type="checkbox"] {
    transform: scale(1.6); /* 크기를 1.5배로 확대 */
    margin-right: 10px;   /* 확대 후의 여백 조정 */  
	  	  
  </style> 
  

   
<?php
//header("Refresh:0");  // reload refresh   

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
      



// 첨부 이미지에 대한 부분
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	
 
 // 이미지 이미 있는 것 불러오기 
$picData=array(); 
$tablename='ceiling';
$item = 'ceilingrendering';

$sql=" select * from mirae8440.picuploads where tablename ='$tablename' and item ='$item' and parentnum ='$num' ";	

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			array_push($picData, $row["picname"]);			
        }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
  }  
$picNum=count($picData);    
  
$URLsave = "http://8440.co.kr/ceilingloadpic.php?num=" . $num;  

 
// 첨부파일 있는 것 불러오기 
$savefilename_arr=array(); 
$realname_arr=array(); 
$attach_arr=array(); 
$tablename='ceiling';
$item = 'ceiling';

$sql=" select * from mirae8440.fileuploads where tablename ='$tablename' and item ='$item' and parentid ='$num' ";	

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			array_push($realname_arr, $row["realname"]);			
			array_push($savefilename_arr, $row["savename"]);			
			array_push($attach_arr, $row["parentid"]);			
        }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
  }   
  
  if ($mode=="copy"){
    try{
      $sql = "select * from mirae8440.ceiling where num = ? ";
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
    
	include '_rowDB.php';
            
			  $order_date1=$row["order_date1"];	
			  $order_date2=$row["order_date2"];	
			  $order_date3=$row["order_date3"];	
			  $order_date4=$row["order_date4"];	
			  $order_input_date1=$row["order_input_date1"];	
			  $order_input_date2=$row["order_input_date2"];	
			  $order_input_date3=$row["order_input_date3"];	
			  $order_input_date4=$row["order_input_date4"];	
  				
		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lcbending_date=trans_date($lcbending_date);
		      $lcwelding_date=trans_date($lcwelding_date);
		      $lcpainting_date=trans_date($lcpainting_date);
		      $lcassembly_date=trans_date($lcassembly_date);
		      $main_draw=trans_date($main_draw);			
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);	
		      $etclaser_date=trans_date($etclaser_date);			
		      $etcbending_date=trans_date($etcbending_date);			
		      $etcwelding_date=trans_date($etcwelding_date);			
		      $etcpainting_date=trans_date($etcpainting_date);			
		      $etcassembly_date=trans_date($etcassembly_date);		
			  
		      $order_date1=trans_date($order_date1);					   
		      $order_date2=trans_date($order_date2);					   
		      $order_date3=trans_date($order_date3);					   
		      $order_date4=trans_date($order_date4);					   
		      $order_input_date1=trans_date($order_input_date1);					   
		      $order_input_date2=trans_date($order_input_date2);					   
		      $order_input_date3=trans_date($order_input_date3);					   
		      $order_input_date4=trans_date($order_input_date4);					  

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }
$mode="";

// $mode를 이용해서 copy_data.php에서 기존 데이터를 사용한다.
	 
$material_arr = array('','304 Hair Line 1.2T','304 HL 1.2T','304 Mirror 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 Mirror VB 1.2T', '304 Mirror Bronze 1.2T', '304 Mirror VB Ti-Bronze 1.2T', '304 Hair Line Black 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)',  '기타' );
  
?>

<?php 
    if($mode=="modify"){
  ?>
	<form  id="board_form" name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1" enctype="multipart/form-data"> 
  <?php  } else {
  ?>
	<form  id="board_form" name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="insert.php?mode=copy" enctype="multipart/form-data"> 
  <?php
	}
  ?>  
  
    <input type="hidden" id="num" name="num" value="<?=$num?>"  >  			
	<input type="hidden" name="saleprice_val" id="saleprice_val" > 
	<input type="hidden" name="cartsave" id="cartsave" > 

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
	  
	<div class="card-header text-center mt-1 rounded-pill">			
	   <h3> 본천장/조명천장 내역 (데이터분할&복사) </h3>
	</div>
		      
    <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
    <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
	<div class="d-flex justify-content-center mt-2 mb-1"> 

    <!-- 이 부분 조회 화면과 다름 -->   
    <button type="button" class="btn btn-dark btn-sm" onclick='javascript:form.submit();' > 완료  </button> &nbsp;&nbsp;&nbsp;	   
		<a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>"> 
		<button type="button" class="btn btn-secondary btn-sm" > 목록 </button> </a>	   
	   
	</div>
<div class="d-flex justify-content-center mb-1 mt-1"> 	
	<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 	
		<table class="table table-reponsive">
		 
		  <tbody>
			<tr>
			  <td colspan="1">현장명</td>
			  <td colspan="5"><input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" class="form-control"   style="text-align: left;" required></td>
			</tr>
			<tr>
			  <td>주소</td>
			  <td colspan="5"><input type="text" id="address" name="address" value="<?=$address?>" class="form-control"  style="text-align: left;" ></td>
			</tr>
			<tr>
			  <td>원청</td>
				  <td> <input type="text" id="firstord" name="firstord" value="<?=$firstord?>" class="form-control"></td>
               <td>담당</td>				  
				  <td> <input type="text" id="firstordman" name="firstordman" value="<?=$firstordman?>" class="form-control" onkeydown="JavaScript:Enter_firstCheck();"> </td>
				<td>Tel</td>				  				  
				  <td> <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control" > </td>				
			  </td>
			</tr>
			<tr>
			  <td>발주처</td>
			  <td><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
			  <td>담당</td>
			  <td><input type="text" id="secondordman" name="secondordman" value="<?=$secondordman?>" class="form-control" onkeydown="JavaScript:Enter_Check();"></td>
			  <td>Tel</td>
			  <td><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
			</tr>


			<tr>
			  <td>현장담당</td>
			  <td><input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" onkeydown="JavaScript:Enter_chargedman_Check();"></td>
			  <td>Tel</td>
			  <td><input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control"></td>
			  <td>설계자</td>
			  <td><input type="text" name="designer" id="designer" value="<?=$designer?>" class="form-control"></td>			  
			</tr>
		<!-- New Table Rows -->
		<tr>
		  <td>접수일</td>
		  <td><input type="date" name="orderday" id="orderday" value="<?=$orderday?>" class="form-control"></td>

		  <td>본천장 설계</td>
		  <td><input type="date" name="main_draw" id="main_draw" value="<?=$main_draw?>" class="form-control"></td>

		  <td>LC설계</td>
		  <td><input type="date" name="lc_draw" id="lc_draw" value="<?=$lc_draw?>" class="form-control"></td>
		</tr>
		<tr>
		  <td style="color:red;">납기일</td>
		  <td><input type="date" name="deadline" id="deadline" value="<?=$deadline?>" class="form-control"></td>    
		  <td style="color:blue;">출고일</td>
		  <td><input type="date" name="workday" id="workday" value="<?=$workday?>" class="form-control"></td>    
		  
		  <td class="text-danger"> 청구일</td>		   
		  <td>
				<input type="date" name="demand" id="demand" value="<?=$demand?>"  class="form-control" > 
		   </td>		  
		</tr>
		<tr>
		<td>미래 시공팀</td>
		  <td><input type="text" name="worker" value="<?=$worker?>" class="form-control"></td>
		  <td>서버도면폴더</td>
		  <td colspan="3" > <input type="text" name="dwglocation" value="<?=$dwglocation?>" class="form-control" placeholder="Nas2dual 저장위치 폴더명 \천장완료\ 다음위치" > </td>
		</tr>      
				
		<tr>
		  <td>운송비 </td>
		  <td>
			<input type="text" name="delivery" value="<?=$delivery?>" class="form-control">
		  </td>	
		  <td>
			<span style="color:red;">운임비 : </span>
		  </td>	
		  <td>
			<input type="text" name="delipay" value="<?=$delipay?>" class="form-control" onkeyup="inputNumberFormat(this)">
		  </td>
			<td colspan="2" class="text-success fs-6"> <b>박스포장 &nbsp;
				<input type="checkbox" id="boxwrap"  name="boxwrap"  value="박스포장" <?php echo ($boxwrap === '박스포장' ? 'checked' : ''); ?>>
			</td>
		  
		</tr>
	
 <tr>
    <td>타입(Type)</td>
    <td><input type="text" id="type" name="type" value="<?=$type?>" class="form-control"></td>

    <td style="color:red;">Car insize</td>
    <td><input type="text" id="car_insize" name="car_insize" value="<?=$car_insize?>" class="form-control"></td>

    <td style="color:blue;">인승</td>
    <td><input type="text" id="inseung" name="inseung" value="<?=$inseung?>" class="form-control"></td>

  </tr>
  <tr>
    <td>결합단위(SET)
      <input type="text" id="su"  name="su" value="<?=$su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>

    <td>본천장 수량
       <input type="text" id="bon_su" name="bon_su" value="<?=$bon_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>

    <td>L/C 수량
       <input type="text" id="lc_su"  name="lc_su" value="<?=$lc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
  
    <td>기타 수량
      <input type="text" id="etc_su" name="etc_su" value="<?=$etc_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>

    <td>공기청정기
       <input type="text" id="air_su"  name="air_su" value="<?=$air_su?>" class="form-control" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"></td>
    <td class="text-primary" >제품가격
       <input type="text" id="price" name="price" value="<?=$price?>" class="form-control" oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',')">
  </tr>	
	
<tr>
	<td>  
		하우징 소재  
	</td>
  <td>        
      <input type="text" name="material1" id="material1" value="<?=$material1?>" class="form-control">    
  </td>
  <td>    
      <select name="material2" id="material2" class="form-control">
        <?php		 
        $mat_count = sizeof($material_arr);
        for($i=0; $i<$mat_count; $i++) {
          if($material2==$material_arr[$i])
            print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
          else   
            print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
        } 		   
        ?>	  
      </select>     
  </td>
  <td>
     중판 소재
    </td>			
	<td>
      <input type="text" name="material3" id="material3" value="<?=$material3?>" class="form-control">    
  </td>
  <td>    
      <select name="material4" id="material4" class="form-control">
        <?php		 
        $mat_count = sizeof($material_arr);
        for($i=0; $i<$mat_count; $i++) {
          if($material4==$material_arr[$i])
            print "<option selected value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
          else   
            print "<option value='" . $material_arr[$i] . "'> " . $material_arr[$i] . "</option>";
        } 		   
        ?>	  
      </select>     
  </td>
</tr>
<tr>  
    <td>비고1</td>
		<td colspan="6">
            <textarea  id="memo"  name="memo" class="form-control"></textarea>
          </td>
	</tr>
	<tr>
	<td>비고2 </td>		  
        <td colspan="6">
            <textarea  id="memo2"  name="memo2" class="form-control"></textarea>
          </td>
          
</tr>



		  </tbody>
		</table>

	<span class="text-center text-danger ">			
	   <h5>  <협력사 부품발주> </h5>
	</span>

<style>
  .table td {
    padding: 2px;
  }
  
   .table td input.form-control {
    height: 25px;
  }
</style>

<div class="table-responsive">
  <table class="table">
    <thead class="text-primary">
      <tr class="text-center">
        <th>구분</th>
        <th>업체</th>
        <th>내역</th>
        <th>발주일</th>
        <th>입고일</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td  id='type1'  title="클릭시 트윈테크 자동계산" >발주1</td>
        <td>
          <input type="text" name="order_com1" id="order_com1" value="<?=$order_com1?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text1" id="order_text1" value="<?=$order_text1?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date1" id="order_date1" value="<?=$order_date1?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date1" id="order_input_date1" value="<?=$order_input_date1?>" size="10" class="form-control">
        </td>
      </tr>
      <tr>
        <td  id='type2'  title="클릭시 트윈테크 자동계산">발주2</td>
        <td>
          <input type="text" name="order_com2" id="order_com2" value="<?=$order_com2?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text2" id="order_text2" value="<?=$order_text2?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date2" id="order_date2" value="<?=$order_date2?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date2" id="order_input_date2" value="<?=$order_input_date2?>" size="10" class="form-control">
        </td>
      </tr>
      <tr>
        <td  id='type3'  title="클릭시 트윈테크 자동계산">발주3</td>
        <td>
          <input type="text" name="order_com3" id="order_com3" value="<?=$order_com3?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text3" id="order_text3" value="<?=$order_text3?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date3" id="order_date3" value="<?=$order_date3?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date3" id="order_input_date3" value="<?=$order_input_date3?>" size="10" class="form-control">
        </td>
      </tr>
      <tr>
        <td id='type4'  title="클릭시 트윈테크 자동계산">발주4</td>
        <td>
          <input type="text" name="order_com4" id="order_com4" value="<?=$order_com4?>" size="10" class="form-control">
        </td>
        <td>
          <input type="text" name="order_text4" id="order_text4" value="<?=$order_text4?>" size="15" class="form-control">
        </td>
        <td>
          <input type="date" name="order_date4" id="order_date4" value="<?=$order_date4?>" size="10" class="form-control">
        </td>
        <td>
          <input type="date" name="order_input_date4" id="order_input_date4" value="<?=$order_input_date4?>" size="10" class="form-control">
        </td>
      </tr>
    </tbody>
  </table>
</div>

		
		
</div>

<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 	

	<span class="text-center text-primary ">			
	   <h4>  천장용 자재 사용량 입력 </h4>
	</span>		   
			   
			   
<style>
  .table td {
    padding: 2px;
    width: 16.66%; /* 6개의 요소를 가로로 배치하기 위해 1/6(16.66%)의 너비 설정 */
  }

  .table td input.form-control {
    height: 25px;
  }
</style>
<table id="partlist" class="table table-bordered table-striped">
    <thead>
      <tr class="text-center" style="vertical-align:center;">        
        <th style="width:5%;">일반휀</th>
        <th style="width:5%;">휠터휀(LH용)</th>
        <th style="width:5%;">판넬고정구(금성아크릴)</th>
        <th style="width:5%;">비상구 스위치(건흥KH-9015)</th>
        <th style="width:5%;">비상등</th>
        <th style="width:5%;">할로겐(7W -6500K)</th>
        <th style="width:5%;">할로겐(7W -6500K KS)</th>
      </tr>
	</thead>
    <tbody>	  
      <tr>        
        <td>
		 <input id="part1" type="hidden" name="part1" size="2" style="text-align: center;" value="<?=$part1?>" >
          <input id="part2" name="part2" size="2" style="text-align: center;" value="<?=$part2?>">
        </td>
        <td>
          <input id="part3" name="part3" size="2" style="text-align: center;" value="<?=$part3?>" >
        </td>
        <td>
          <input id="part4" name="part4" size="2" style="text-align: center;" value="<?=$part4?>" >
        </td>
        <td>
          <input id="part5" name="part5" size="2" style="text-align: center;" value="<?=$part5?>" >
        </td>
        <td>
          <input id="part6" name="part6" size="2" style="text-align: center;" value="<?=$part6?>" >
        </td>      
        <td>
          <input id="part7" name="part7" size="2" style="text-align: center;" value="<?=$part7?>" >
        </td>      
        <td>
          <input id="part20" name="part20" size="2" style="text-align: center;" value="<?=$part20?>" >
        </td>
		</tr>
		
    </tbody>
  </table>
		
			
	<div class="table-responsive">
	  <table class="table">		
		<tbody>
		  <tr>
			<td style="color: blue;">T5 (일반)</td>
			<td class="text-center" style="color: blue;">
			  5W(300) 
			  <input id="part8" name="part8" size="2" style="text-align: center; color: blue;" value="<?=$part8?>" class="form-control">
			</td>		  			
			<td  class="text-center" style="color: blue;">
			  11W(600) <input id="part9" name="part9" size="2" style="text-align: center; color: blue;" value="<?=$part9?>" class="form-control">
			</td>
		    	<td  class="text-center" style="color: blue;">
			  15W(900) <input id="part10" name="part10" size="2" style="text-align: center; color: blue;" value="<?=$part10?>" class="form-control">
			</td>		  
			<td  class="text-center" style="color: blue;">
			  20W(1200) <input id="part11" name="part11" size="2" style="text-align: center; color: blue;" value="<?=$part11?>" class="form-control">
			</td>
		  </tr>
		  <tr>
			<td style="color: red;">T5 (KS)</td>		
			<td  class="text-center" style="color: red;"> 
			  6W(300) <input id="part12" name="part12" size="2" style="text-align: center; color: red;" value="<?=$part12?>" class="form-control">
			</td>			
			<td  class="text-center" style="color: red;">
			  10W(600) <input id="part13" name="part13" size="2" style="text-align: center; color: red;" value="<?=$part13?>" class="form-control">
			</td>		  			
			<td  class="text-center" style="color: red;">
			  15W(900) <input id="part14" name="part14" size="2" style="text-align: center; color: red;" value="<?=$part14?>" class="form-control">
			</td>			
			<td  class="text-center" style="color: red;">
			  20W(1200) <input id="part15" name="part15" size="2" style="text-align: center; color: red;" value="<?=$part15?>" class="form-control">
			</td>
		  </tr>
		  <tr>
			<td style="color: brown;">직관등</td>
			<td  class="text-center" style="color: brown;">
			  600mm <input id="part16" name="part16" size="2" style="text-align: center; color: brown;" value="<?=$part16?>" class="form-control">
			</td>
			<td  class="text-center" style="color: brown;">
			  800mm <input id="part17" name="part17" size="2" style="text-align: center; color: brown;" value="<?=$part17?>" class="form-control">
			</td>
			<td  class="text-center" style="color: brown;">
			  1000mm <input id="part18" name="part18" size="2" style="text-align: center; color: brown;" value="<?=$part18?>" class="form-control">
			</td>		  			
			<td  class="text-center" style="color: brown;">
			  1200mm <input id="part19" name="part19" size="2" style="text-align: center; color: brown;" value="<?=$part19?>" class="form-control">
			</td>
		  </tr>
		</tbody>
	  </table>
	</div>  
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr >
        <th colspan="6"><h4 class="text-secondary" > L/C 제조 완료 현황</h4></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>업체</td>
        <td>
          <input type="text" name="lclaser_com" id="lclaser_com" value="<?=$lclaser_com?>" class="form-control" >
        </td>      
        <td>레이져</td>
        <td>
          <input type="date" name="lclaser_date" id="lclaser_date" value="<?=$lclaser_date?>" class="form-control" >
        </td>
        <td>절곡</td>
        <td>
          <input type="date" name="lcbending_date" id="lcbending_date" value="<?=$lcbending_date?>" class="form-control" >
        </td>      
		</tr>
		<tr>
        <td>제관</td>
        <td>
          <input type="date" name="lcwelding_date" id="lcwelding_date" value="<?=$lcwelding_date?>" class="form-control" >
        </td>
        <td >도장</td>
        <td>
          <input type="date" name="lcpainting_date" id="lcpainting_date" value="<?=$lcpainting_date?>" class="form-control" >
        </td>      
        <td >조립</td>
        <td>
          <input type="date" name="lcassembly_date" id="lcassembly_date" value="<?=$lcassembly_date?>" class="form-control" >
        </td>
      </tr>
    </tbody>
  </table>
</div>
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th colspan="6"><h4 class="text-success" > 본천장 제조 완료 현황</h4></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td >은성레이져 외주</td>
        <td>
          <input type="date" name="eunsung_make_date" id="eunsung_make_date" value="<?=$eunsung_make_date?>" class="form-control">
        </td>
        <td>  레이져 </td>
        <td>
          <input type="date" name="eunsung_laser_date" id="eunsung_laser_date" value="<?=$eunsung_laser_date?>" class="form-control">
        </td>

        <td>  절곡 </td>
        <td>
          <input type="date" name="mainbending_date" id="mainbending_date" value="<?=$mainbending_date?>" class="form-control">
        </td>
      </tr>
      <tr>		
        <td>  제관 </td>
        <td>
          <input type="date" name="mainwelding_date" id="mainwelding_date" value="<?=$mainwelding_date?>" class="form-control">
        </td>
        <td>  도장 </td>
        <td>
          <input type="date" name="mainpainting_date" id="mainpainting_date" value="<?=$mainpainting_date?>" class="form-control">
        </td>
        <td>  조립 </td>
        <td>
          <input type="date" name="mainassembly_date" id="mainassembly_date" value="<?=$mainassembly_date?>" class="form-control">
        </td>
      </tr>
    </tbody>
  </table>
</div>


<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th colspan="6" > <h4 class="text-primary" > 기타 (중판, 인테리어판넬, 발보호판 등) 제조 완료 현황 </h4> </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>  레이져 </td>
        <td>
          <input type="date" name="etclaser_date" id="etclaser_date" value="<?=$etclaser_date?>" class="form-control">
        </td>
        <td>  절곡 </td>
        <td>
          <input type="date" name="etcbending_date" id="etcbending_date" value="<?=$etcbending_date?>" class="form-control">
        </td>
        <td>  제관 </td>
        <td>
          <input type="date" name="etcwelding_date" id="etcwelding_date" value="<?=$etcwelding_date?>" class="form-control">
        </td>
      </tr>
      <tr>		
        <td>  도장 </td>
        <td>
          <input type="date" name="etcpainting_date" id="etcpainting_date" value="<?=$etcpainting_date?>" class="form-control">
        </td>
        <td>  조립 </td>
        <td>
          <input type="date" name="etcassembly_date" id="etcassembly_date" value="<?=$etcassembly_date?>" class="form-control">
        </td>
      </tr>
    </tbody>
  </table>
</div>


		
</div> 	

<div class="col-sm-2 p-1  rounded"   style=" border: 1px solid #392f31; " > 		


</div> 	
	   
</div>
</div>
			
	<div class="card mt-1 mb-5" style=" border: 1px solid #392f31; ">			
	  <div class="card-header">			
		<h2 class="text-center">포장 완료사진</h2>
	  </div>
	  <div id="displayPicture" style="display: none;" class="row">
	  </div> 		
	</div>
			 
</div> 
</form>
</body>
</html>    


<script>

function goToListPage() {
    // PHP 변수 값을 JavaScript 변수로 가져오기
    var page = '<?=$page?>';
    var search = '<?=$search?>';
    var find = '<?=$find?>';
    var process = '<?=$process?>';
    var yearcheckbox = '<?=$yearcheckbox?>';
    var check = '<?=$check?>';
    var output_check = '<?=$output_check?>';
    var team_check = '<?=$team_check?>';
    var measure_check = '<?=$measure_check?>';
    var plan_output_check = '<?=$plan_output_check?>';
    var scale = '<?=$scale?>';
    var cursort = '<?=$cursort?>';
    var sortof = '<?=$sortof?>';

    // 목록 페이지 URL 생성
    var url = 'list.php?' +
              '&page=' + page +
              '&search=' + search +
              '&find=' + find +
              '&list=1' +
              '&process=' + process +
              '&yearcheckbox=' + yearcheckbox +
              '&year=' + check +
              '&check=' + check +
              '&output_check=' + output_check +
              '&team_check=' + team_check +
              '&measure_check=' + measure_check +
              '&plan_output_check=' + plan_output_check +
              '&page=' + page +
              '&scale=' + scale +
              '&cursort=' + cursort +
              '&sortof=' + sortof +
              '&stable=1';

    // 페이지 이동
    window.location.href = url;
}

document.getElementById('main_draw').addEventListener('change', function() {
	user_name = '<?php echo $user_name;?>';
    document.getElementById('designer').value = user_name;
});

document.getElementById('lc_draw').addEventListener('change', function() {
	user_name = '<?php echo $user_name;?>';
    document.getElementById('designer').value = user_name;
});

$(document).ready(function(){
	
	// data 초기화
	Swal.fire({
	  title: '데이터 분할&복사',
	  text: "기존 데이터를 여러개로 나눌때 사용합니다. \n\r 기존의 모든 데이터를 그대로 복사하는 개념입니다. ",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: '네 합시다!'
	}).then((result) => {
	  if (result.isConfirmed) {
		  // 실제 코드입력

		Swal.fire(
		  '처리되었습니다.',
		  '데이터가 성공적으로 복사되었습니다.',
		  'success'
		)
	  }
	  
	});
	
// 전체화면에 꽉찬 이미지 보여주는 루틴
		$(document).on("click","img",function(){
			var path = $(this).attr('src')
			showImage(path);
		});//end click event
		
		function showImage(fileCallPath){
		    
		    $(".bigPictureWrapper").css("display","flex").show();
		    
		    $(".bigPicture")
		    .html("<img src='"+fileCallPath+"' >")
		    .animate({width:'100%', height: '100%'}, 1000);
		    
		  }//end fileCallPath
		  
		$(".bigPictureWrapper").on("click", function(e){
		    $(".bigPicture").animate({width:'0%', height: '0%'}, 1000);
		    setTimeout(function(){
		      $('.bigPictureWrapper').hide();
		    }, 1000);
		  });//end bigWrapperClick event	
		  

	// 매초 검사해서 이미지가 있으면 보여주기
	$("#pInput").val('50'); // 최초화면 사진파일 보여주기
		
	let timer3 = setInterval(() => {  // 2초 간격으로 사진업데이트 체크한다.
			  if($("#pInput").val()=='100')   // 사진이 등록된 경우
			  {
					 displayfile(); 
					 displayPicture();  
					 // console.log(100);
			  }	      
			  if($("#pInput").val()=='50')   // 사진이 등록된 경우
			  {
					 displayfileLoad();				 				  
					 displayPictureLoad();				 
			  }	     
			   
		 }, 2000);	
		 
  
delFileFn = function(divID, delChoice) {
	console.log(divID, delChoice);
	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	$.ajax({
		url:'../file/del_file.php?savename=' + delChoice ,
		type:'post',
		data: $("#board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const savename = data["savename"];		   
		   
		  // 시공전사진 삭제 
			$("#file" + divID).remove();  // 그림요소 삭제
			$("#delFile" + divID).remove();  // 그림요소 삭제
		    $("#pInput").val('');					
			
        });	
	}		

}		 
		 
  
	delPicFn = function(divID, delChoice) {
		console.log(divID, delChoice);
	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
		$.ajax({
			url:'../p/delpic.php?picname=' + delChoice ,
			type:'post',
			data: $("#board_form").serialize(),
			dataType: 'json',
			}).done(function(data){						
			   const picname = data["picname"];		   
			   
			  // 시공전사진 삭제 
				$("#pic" + divID).remove();  // 그림요소 삭제
				$("#delPic" + divID).remove();  // 그림요소 삭제
				$("#pInput").val('');						
				
			});		
		}
	}
	  		 
			 
	// 천장 렌더링 이미지 불러오기
	function displayPicture() {       
		$('#displayrender').show();
		params = $("#num").val();	
		$("#tablename").val('ceiling');
		$("#item").val('ceilingrendering');	
		
		var tablename = $("#tablename").val();    
		var item = $("#item").val();	
		
		$.ajax({
			url:'../p/load_pic.php?num=' + params + '&tablename=' + tablename + '&item=' + item ,
			type:'post',
			data: $("#board_form").serialize(),
			dataType: 'json',
			}).done(function(data){						
			   const recnum = data["recnum"];		   
			   console.log(data);
			   $("#displayrender").html('');
			   for(i=0;i<recnum;i++) {			   
				   $("#displayrender").append("<img id=pic" + i + " src ='../uploads/" + data["img_arr"][i] + "' style='width:100%; '  > <br> " );			   
				   $("#displayrender").append("&nbsp;<button type='button' class='mt-2 btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  data["img_arr"][i] + "')> 삭제 </button>&nbsp; <br>");					   
				  }		   
					$("#pInput").val('');			
		});	
	}
		 
		 
WrapdisplayPictureLoad();

function WrapdisplayPictureLoad() {
  $('#displayPicture').show();
  var picNum = "<?php echo $WrappicNum; ?>";
  var picData = <?php echo json_encode($WrappicData); ?>;
  
  for (var i = 0; i < picNum; i++) {
    var image = $("<img>", {
      id: "pic" + i,
      class: "mt-4 img-fluid rounded border",
      src: "../imgceiling/" + picData[i]
    });
    var colDiv = $("<div>", { class: "col-md-4" });
    colDiv.append(image);
    
    $("#displayPicture").append(colDiv);
  }
  
  $("#displayPicture").append("<br><br><br>");
}



// 기존 ,fpsej있는 이미지 화면에 보여주기
function displayPictureLoad() {    
	$('#displayrender').show();
	var picNum = "<? echo $picNum; ?>"; 					
	var picData = <?php echo json_encode($picData);?> ;	
	console.log(picNum);
	console.log(picData);
	for(i=0;i<picNum;i++) {
	   $("#displayrender").append("<img id=pic" + i + " src ='../uploads/" + picData[i] + "' style='width:100%;' > <br>" );			
	   // $("#displayPicture").zoom();
	   $("#displayrender").append("&nbsp;<button type='button' class='mt-2 btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + picData[i] + "')> 삭제 </button>&nbsp;<br>");			   
	  }		   
		$("#pInput").val('');	
}
	


	
	// 도면폴더 클릭시 실행
	$("#dwgclick").click(function() {
		// Link = "file://nas2dual/%EC%9E%A0%EC%99%84%EB%A3%8C/"; 
		
		var Urls = '<?php echo $dwglocation; ?>';	
					
		Urls = "\\\\nas2dual\\천장완료\\" + Urls;	
		
	   $('#insertword').val(Urls)
	   
	 // var obj = document.getElementById('insertword'); 
	 // obj.select();  //인풋 컨트롤의 내용 전체 선택  
	 // document.execCommand("copy");  //복사    
	 
		tmp = "<br> 도면저장 폴더위치가 복사되었습니다. <br> 탐색기에 'Ctrl+v'로 붙여넣기 하세요! <br>";				
		$('#alertmsg').html(tmp); 
	   $('#myModal').modal('show');		   
		  
	   clipboardCopy('insertword');		
		   
		setTimeout(function() { 
	  var obj = document.getElementById('insertword'); 
	  obj.select();  //인풋 컨트롤의 내용 전체 선택  
	  document.execCommand("copy");  //복사        
		}, 500);	   	   
		
		setTimeout(function() { 
			 $('#myModal').modal('hide');	      
		}, 1500);	   
	   
	   // clipboardCopy('insertword');		
	
	});
	

	// rendering 사진 멀티업로드	
	$("#upfile").change(function(e) {	    
			// 실측서 이미지 선택
			$("#item").val('ceilingrendering');
			var item = $("#item").val();
			FileProcess(item);	
			
			
	});	 	
		
	function FileProcess(item) {
	//do whatever you want here
	num = $("#num").val();

	  // 사진 서버에 저장하는 구간	
	  // 사진 서버에 저장하는 구간	
			//tablename 설정
		   $("#tablename").val('ceiling');  
			// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 
			
			console.log(form);
			console.log(data);

			tmp='사진을 저장중입니다. 잠시만 기다려주세요.';					
			$('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show'); 	

			$.ajax({
				enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
				processData: false,    
				contentType: false,      
				cache: false,           
				timeout: 600000, 			
				url: "../p/mspic_insert.php?num=" + num ,
				type: "post",		
				data: data,						
				success : function(data){
					console.log(data);
					// opener.location.reload();
					// window.close();	
					setTimeout(function() {
						$('#myModal').modal('hide');  
						}, 1000);	
					// 사진이 등록되었으면 100 입력됨
					 $("#pInput").val('100');							

				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
							} 			      		
			   });	

	}		   
 


// 첨부파일(excel) 멀티업로드	
$("#upfileattached").change(function(e) {	    
	    $("#id").val('<?php echo $num;?>');
	    $("#parentid").val('<?php echo $num;?>');
	    $("#fileorimage").val('file');
	    $("#item").val('ceiling');
	    $("#upfilename").val('upfileattached');
	    $("#tablename").val('ceiling');
	    $("#savetitle").val('비규격 엑셀파일');			
		  
	  // 파일 서버에 저장하는 구간	
			// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 		

             console.log($("#board_form").serialize());			
             console.log(data);			

			tmp='파일을 저장중입니다. 잠시만 기다려주세요.';		
			$('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show'); 		

			$.ajax({
				enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
				processData: false,    
				contentType: false,      
				cache: false,           
				timeout: 600000, 			
				url: "../file/file_insert.php",
				type: "post",		
				data: data,						
				success : function(data){
					console.log(data);
					// opener.location.reload();
					// window.close();	
					setTimeout(function() {
						$('#myModal').modal('hide');  
						}, 1000);	
					// 사진이 등록되었으면 100 입력됨
					 $("#pInput").val('100');						

				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
							} 			      		
			   });	

});		   
 

// 첨부된 파일 불러오기
function displayfile() {       
	$('#displayfile').show();
	params = $("#id").val();	
	
    var tablename = 'ceiling';    
    var item = 'ceiling';
	
	$.ajax({
		url:'../file/load_file.php?id=' + params + '&tablename=' + tablename + '&item=' + item ,
		type:'post',
		data: $("#board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recid = data["recid"];		   
		   console.log(data);
		   $("#displayfile").html('');
		   for(i=0;i<recid;i++) {	
			   $("#displayfile").append("<div id=file" + i + ">  <a href='../uploads/" + data["file_arr"][i] + "' download='" +  data["realfile_arr"][i]+ "'>" +  data["realfile_arr"][i] + "</div> &nbsp;&nbsp;&nbsp;&nbsp;  " );			   
         	   $("#displayfile").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delFile" + i + "' onclick=delFileFn('" + i + "','" + data["file_arr"][i] + "')> 삭제 </button>&nbsp; <br>");					   
		      }		   
    });	
}

// 기존 있는 파일 화면에 보여주기
function displayfileLoad() {    
	$('#displayfile').show();	
	var savefilename_arr = <?php echo json_encode($savefilename_arr);?> ;	
	var realname_arr = <?php echo json_encode($realname_arr);?> ;	
	
    for(i=0;i<savefilename_arr.length;i++) {
			   $("#displayfile").append("<div id=file" + i + ">  <a href='../uploads/" + savefilename_arr[i] + "' download='" + realname_arr[i] + "'>" +  realname_arr[i] + "</div> &nbsp;&nbsp;&nbsp;&nbsp;  " );			   
         	   $("#displayfile").append("&nbsp;<button type='button' class='btn btn-outline-danger btn-sm' id='delFile" + i + "' onclick=delFileFn('" + i + "','" +  savefilename_arr[i] + "')> 삭제 </button>&nbsp; <br>");					   
	  }	   
		
}
	 	 	  	

  $("#car_insize").focusout(function(){

//    $("#inseung").css("background-color", "silver");
		const insize = $("input[name=car_insize]" ).val();
		const wide_insize = insize.split('*');
		const wide = Number(wide_insize[0]);
        const depth = Number(wide_insize[1]);	
  
   $("#inseung").val(calinseung( wide, depth ) );

  });
	
	
//타입을 입력하면 회사가 바뀐다.
$("#type").keydown(function(e) {
	  changeType();
});


  $("#type").focusout(function(){
     changeType();
  });	
	
	
function changeType() {

		const type = $("input[name=type]" ).val();

		   $("input[name=order_com1]").val('');
		   $("input[name=order_com2]").val('');		
		   $("input[name=order_com3]").val('');		
		   $("input[name=order_com4]").val('');		
		   $("input[name=order_com5]").val('');		

		if(type=='바리솔' )
		  {
		   $("input[name=order_com1]").val('투엘비');
		   $("input[name=order_com2]").val('서한');		   
		  }	
		if(type=='011' || type=='012' || type=='017' || type=='013D' )
		  {
		   $("input[name=order_com1]").val('덴크리');
		   $("input[name=order_com2]").val('트윈테크');		   
		  }	
		if(type=='N20')
		  {
		   $("input[name=order_com1]").val('덴크리');
		   $("input[name=order_com2]").val('알루스');
		   $("input[name=order_com3]").val('트윈테크');
		  }	
		if(type=='N21')
		  {
		   $("input[name=order_com1]").val('덴크리');
		   $("input[name=order_com2]").val('서한');
		  }		
		if(type=='N23')
		  {
		   $("input[name=order_com1]").val('트윈테크');
		   $("input[name=order_com2]").val('덴크리');
		  }	
		if(type=='신N20')
		  {
		   $("input[name=order_com1]").val('서한');
		   $("input[name=order_com2]").val('알루스');
		   $("input[name=order_com3]").val('트윈테크');
		  }	
		if(type=='N20변형')
		  {
		   $("input[name=order_com1]").val('서한');
		   $("input[name=order_com2]").val('트윈테크');
		  }	
		if(type=='027' || type=='015' || type=='013' || type=='033' || type=='033변형' || type=='035' || type=='036')
		  {
		   $("input[name=order_com1]").val('서한');
		  }	

		if(type=='032' || type=='034' )
		  {
		   $("input[name=order_com1]").val('알루스');
		   $("input[name=order_com2]").val('서한');
		  }	
		if(type=='037' ||  type=='038')
		  {
		   $("input[name=order_com1]").val('청디자인');
		  }	
		if(type=='026')
		  {
		   $("input[name=order_com1]").val('덴크리');		   
		   $("input[name=order_com2]").val('트윈테크');
		  }	
		if(type=='026변형')
		  {
		   $("input[name=order_com1]").val('서한');		   
		   $("input[name=order_com2]").val('트윈테크');
		  }		
		if(type=='027')
		  {
		   $("input[name=order_com1]").val('금성');
		  }		
		if(type=='031')
		  {
		   $("input[name=order_com1]").val('서한');
		   $("input[name=order_com2]").val('트윈테크');
		   $("input[name=order_com3]").val('알루스');
		  }			
}		  
	

	$("#type1").click(function(){
		$("input[name=order_com1]").val('트윈테크');
        calculateBoth('1');
	});		
	$("#type2").click(function(){
		$("input[name=order_com2]").val('트윈테크');
        calculateBoth('2');
	});		
	$("#type3").click(function(){
		$("input[name=order_com3]").val('트윈테크');
        calculateBoth('3');
	});		
	$("#type4").click(function(){
		$("input[name=order_com4]").val('트윈테크');
        calculateBoth('4');
	});	
	

	calculateBoth = function(NUM) {
		const type = $("input[name=type]" ).val();
		const insize = $("input[name=car_insize]" ).val();
		const lc_su = $("input[name=lc_su]" ).val();
	    const first_name = "order_text" + NUM; 
		
		if(Number(lc_su)<1) 
		   {
			 alert('L/C 수량을 입력해 주세요');			 
		   }
		
		let result;
		let jungSu;
		let divider;

	
		const wide_insize = insize.split('*');
		const wide = Number(wide_insize[0]);
        const depth=Number(wide_insize[1]);
		
		let result_wide=0;

		switch(type) {
			case '011' :
			   result_wide=wide-730;
			   break;
			case '012' :
			   result_wide=wide-750;
			   break;
			case '013D' :
			   result_wide=wide-705;
			   break;
			case '014' :
			   result_wide=wide/2-143;
			   break;
			case '017' :
			   result_wide=wide-810;
			   break;
			case '017S' :
			case '017s' :
			   result_wide=wide-410;
			   break;
			case '017m' :
			case '017M' :
			   result_wide=wide-610;
			   break;
			case 'N20' :
			   result_wide=wide-705;
			   break;
			case '026' :
			   result_wide=wide-670;
			   break;			   
			default:
				 break;
		}

		if(depth<1000)
	     	{
			   jungSu=1;
			   divider = 1;
	     		}
			else if(depth>=1800)
			   {
				 jungSu = 3;
				 divider = 3;
			   }
			   else
			      {
					jungSu = 2;
					divider = 2;
				  }
				
		let result_depth=0;

		switch(type) {
			case '011' :
			   result_depth= (depth-54)/divider-11  ;
			   break;
			case '012' :
			   result_depth=(depth-54)/divider -11 ;
			   break;
			case '013D' :
			   result_depth=(depth-20)/divider-11 ;
			   break;
			case '014' :
			   result_depth=(depth-54) -11 ;
			   break;
			case '017' :
			   if(depth>=1800)
					  result_depth=(depth-60)/3 -11;
				  else
					  result_depth=(depth-60)/2 -11;
			   break;
			case '017S' :
			case '017s' :
			   result_depth=(depth-60)/divider -10;
			   break;
			case '017m' :
			case '017M' :
			   result_depth=(depth-60)/divider -11;
			   break;
			case 'N20' :
			   result_depth=(depth-56)/divider -10;
			   break;
			case '026' :
			   result_depth=(depth-58)/divider -11;
			   break;			   
			default:
				 break;
		}					
		let tmp="";
		tmp = '중판:' + result_wide + "*" + Math.floor(result_depth) + "*" + jungSu*lc_su + "EA" ;
		$("input[name=" + first_name + "]").val(tmp);
}


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

function deldate(){	

document.getElementById("measureday").value  = "";
document.getElementById("drawday").value  = "";
document.getElementById("workday").value  = "";
document.getElementById("deadline").value  = "";
document.getElementById("endworkday").value  = "";
document.getElementById("startday").value  = "";
document.getElementById("testday").value  = "";   
var _today = new Date();   

// document.getElementById("orderday").value  = today;   
/*
let year = today.getFullYear(); // 년도
let month = today.getMonth();  // 월
let date = today.getDate();  // 날짜
let day = today.getDay(); 
printday = year + "-" + month + "-" + day;  */

printday=_today.format('yyyy-MM-dd');   
document.getElementById("orderday").value  = printday;

}  

Date.prototype.format = function (f) {

    if (!this.valueOf()) return " ";



    var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];

    var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];

    var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var d = this;



    return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function ($1) {

        switch ($1) {

            case "yyyy": return d.getFullYear(); // 년 (4자리)

            case "yy": return (d.getFullYear() % 1000).zf(2); // 년 (2자리)

            case "MM": return (d.getMonth() + 1).zf(2); // 월 (2자리)

            case "dd": return d.getDate().zf(2); // 일 (2자리)

            case "KS": return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)

            case "KL": return weekKorName[d.getDay()]; // 요일 (긴 한글)

            case "ES": return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)

            case "EL": return weekEngName[d.getDay()]; // 요일 (긴 영어)

            case "HH": return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)

            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)

            case "mm": return d.getMinutes().zf(2); // 분 (2자리)

            case "ss": return d.getSeconds().zf(2); // 초 (2자리)

            case "a/p": return d.getHours() < 12 ? "오전" : "오후"; // 오전/오후 구분

            default: return $1;

        }

    });

};



String.prototype.string = function (len) { var s = '', i = 0; while (i++ < len) { s += this; } return s; };

String.prototype.zf = function (len) { return "0".string(len - this.length) + this; };

Number.prototype.zf = function (len) { return this.toString().zf(len); };


function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;
	
    }
}


function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트 담당자 연락처 찾기
    }
}
function Enter_firstCheck(){
    if(event.keyCode == 13){
      exe_firstordman();  // 원청 담당자 전번 가져오기
    }
}

function Enter_chargedman_Check(){
	const data1 = "ceiling";
	const data2 = "chargedman";
	const data3 = "chargedmantel";	
	const search = $("#" + data2).val();
    if(event.keyCode == 13){     
     window.open('load_tel.php?search=' + search +'&data1=' + data1 + '&data2=' + data2 + '&data3=' + data3,'전번 조회','top=0, left=0, width=1500px, height=600px, scrollbars=yes');	  
    }
}

function exe_search()
{
      // var postData = changeUri(document.getElementById("outworkplace").value);
      // var sendData = $(":input:radio[name=root]:checked").val();
     var tmp=$('#secondordman').val();
	 switch (tmp) {
		 case '김관' :
         $("#secondordmantel").val("010-2648-0225");		 
         $("#secondordman").val("김관부장");		 
         $("#secondord").val("한산");		 
		 break;
		 case '정재훈' :
         $("#secondordmantel").val("010-2102-4561");	
         $("#secondordman").val("정재훈이사");			 
		 break;		 
		 case '고규천' :
         $("#secondordmantel").val("010-6687-9535");		 
         $("#secondordman").val("고규천이사");			 
		 break;			
		 case '조윤기' :
         $("#secondordmantel").val("010-6400-4893");		 
         $("#secondordman").val("조윤기주임");			 
		 break;	
		 case '서달원' :
         $("#secondordmantel").val("010-5462-7098");		 
         $("#secondordman").val("서달원부대표");		 
         $("#secondord").val("다원엘리베이터");		 
         $("#chargedmantel").val("010-3405-6669");		 		 
         $("#chargedman").val("박경호소장");		 
		 break;		 
	 }
}
function exe_firstordman()
{    
}


function exe_chargedman()
{    
}



function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}

function getToday(){   // 2021-01-28 형태리턴
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.
    var date = now.getDate();

    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month + "-" + date; 
}


// function load_init() {

  // // 실제 코드입력
			// $('#board_form').find('input').each(function(){ $(this).val(''); });
			// $('#board_form').find('textarea').each(function(){ $(this).val(''); });

			// $('#workplacename').val("<? echo $workplacename; ?>");
			// $('#address').val("<? echo $address; ?>");
			// $('#firstord').val("<? echo $firstord; ?>");
			// $('#firstordman').val("<? echo $firstordman; ?>");
			// $('#firstordmantel').val("<? echo $firstordmantel; ?>");
			// $('#secondord').val("<? echo $secondord; ?>");
			// $('#secondordman').val("<? echo $secondordman; ?>");
			// $('#secondordmantel').val("<? echo $secondordmantel; ?>");
			// $('#chargedman').val("<? echo $chargedman; ?>");
			// $('#chargedmantel').val("<? echo $chargedmantel; ?>");
			// $('#orderday').val(getToday());


// }

// // 타임함수 시간지나면 처리함
// setTimeout(function() {
 // load_init();
// }, 1000);

</script>
	</body>
 </html>
