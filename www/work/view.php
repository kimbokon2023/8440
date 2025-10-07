<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
	
$title_message = 'jamb 수주내역';  
?>    
 
<?php 
include getDocumentRoot() . '/load_header.php';

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
		header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
   }    
?> 
<title> <?=$title_message?> </title>
<body>
</head>
<?php include getDocumentRoot() . '/common/modal.php'; ?>
<?php
  
 if($user_name==='이미래' || $user_name==='김보곤'  || $user_name==='최장중' )
	 $isAuthorizedUser = true;
    else
		$isAuthorizedUser = false;
	
//	var_dump($isAuthorizedUser);
 
 include 'request.php';
 
$file_dir = getDocumentRoot() . '/uploads/'; 
 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 외주단가 가져오기
    $sql = "select * from ".$DB.".work_outcost order by num desc LIMIT 1";
    $stmh = $pdo->prepare($sql);  
    $stmh->execute();     
    $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.    

	$widejamb_unitprice=$row["widejamb_unitprice"];	
	$normaljamb_unitprice=$row["normaljamb_unitprice"];	
	$narrowjamb_unitprice=$row["narrowjamb_unitprice"];	

} catch(PDOException $e) {
    print "오류: " . $e->getMessage();
}

   
// 소장 VOC 가져오기
	try{
     $sql = "select * from ".$DB.".voc where parent=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
  
     $content=$row["content"];

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }  
   
   $sql="select * from ".$DB.".steelsource"; 					
   
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

array_push($material_arr,'','304 HL 1.2T','304 MR 1.2T','VB 1.2T','2B VB 1.2T','304 VB 1.2T', 'SPCC 1.2T(도장)', 'EGI 1.2T(도장)', 'HTM (신우)' );

$material_arr = array_unique($material_arr);
sort($material_arr);
		 
 try{
     $sql = "select * from ".$DB.".work where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
 include '_row.php';
 
   $filename1=$row["filename1"];
  $filename2=$row["filename2"];
  $imgurl1="../imgwork/" . $filename1;
  $imgurl2="../imgwork/" . $filename2;
  $checkhold=$row["checkhold"];  
  $attachment=$row["attachment"];  
    

// customer 필드 가져오기 (Json형태의 값)
$customer_data = isset($row["customer"]) ? $row["customer"] : '{}';
// JSON 데이터를 PHP 객체로 디코딩
$customer_object = json_decode($customer_data);

// 디코딩된 데이터를 각 변수에 할당
$customer_date = isset($customer_object->customer_date) ? $customer_object->customer_date : date('Y-m-d');
$ordercompany = isset($customer_object->ordercompany) ? $customer_object->ordercompany : '미래기업';
$workplacename_customer = isset($customer_object->workplacename) ? $customer_object->workplacename : '';
$workname = isset($customer_object->workname) ? $customer_object->workname : 'JAMB CLADDING';
$pjnum = isset($customer_object->pjnum) ? $customer_object->pjnum : '';
$totalsu = isset($customer_object->totalsu) ? $customer_object->totalsu : '';
$worker_customer = isset($customer_object->worker) ? $customer_object->worker : '';
$customer_name = isset($customer_object->customer_name) ? $customer_object->customer_name : '';
$image_url = isset($customer_object->image_url) ? $customer_object->image_url : '';


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
	  if($workfeedate!="0000-00-00" and $workfeedate!="1970-01-01" and $workfeedate!="")  $workfeedate = date("Y-m-d", strtotime( $workfeedate) );
			else $workfeedate="";		



			
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }

$URLsave = "https://8440.co.kr/loadpic.php?num=" . $num;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
 // 실측서 이미지 이미 있는 것 불러오기 
$picData=array(); 
$tablename='work';
$item = 'measure';

$sql=" select * from ".$DB.".picuploads where tablename ='$tablename' and item ='$item' and parentnum ='$num' ";	

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
	     if(intval($row["parentnum"])>0 && $row["parentnum"] !=='undefined' )
			array_push($picData, $row["picname"]);			
        }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
  }  
$picNum=count($picData);   

// 초기 회전각도 0으로 지정
$after_work_rotate = 90;
$before_work_rotate = 90;

$sum1 = intval($widejamb) + intval($normaljamb) + intval($smalljamb) ;

if( intval($widejamb) > 0)
	$widejamb_unitprice_cleaned = intval(str_replace(",", "", $widejamb_unitprice));
else
{
	$widejamb_unitprice = '';
	$widejamb_unitprice_cleaned = 0 ;
}
if( intval($normaljamb) > 0)	
	$normaljamb_unitprice_cleaned = intval(str_replace(",", "", $normaljamb_unitprice));
else
{	
	$normaljamb_unitprice = '' ;
	$normaljamb_unitprice_cleaned = 0 ;
}

if( intval($smalljamb) > 0)	
	$narrowjamb_unitprice_cleaned = intval(str_replace(",", "", $narrowjamb_unitprice));
else
{	
	$narrowjamb_unitprice = '' ;
	$narrowjamb_unitprice_cleaned = 0 ;
}

$widejamb_amount  =  $widejamb_unitprice_cleaned * intval($widejamb) ;
$normaljamb_amount = $normaljamb_unitprice_cleaned * intval($normaljamb) ;
$narrowjamb_amount = $narrowjamb_unitprice_cleaned * intval($smalljamb) ;

$sum2 = $widejamb_amount + $normaljamb_amount + $narrowjamb_amount ;



// 서명관련 추출			
$confirmsig = '-';

// Define the pattern to match the desired substring
$pattern = '/\(([A-Za-z][^)]*)\)/';

// Execute the regular expression
if (preg_match($pattern, $workplacename_customer, $matches)) {
	$pjnum = $matches[1];
	$confirmsig = '';
} else {
	$pjnum = ''; // Default value if no match is found				
}			
   // 	var_dump($customer_object);
 ?>

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

.table, td, input {
	font-size: 13px !important;
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
  
    .table td input.form-control {
      height: 28px;
    }

  input[type="checkbox"] {
    transform: scale(1.6); /* 크기를 1.5배로 확대 */
    margin-right: 10px;   /* 확대 후의 여백 조정 */
}	  
	
  #outsourcingBtn:hover {
    cursor : pointer;
}	  

	#outsourcingBtn {
		display: inline-block;
		position: relative;
	}
			
	#showframe {
		display: none;
		position: absolute;
		width: 500px;
		z-index: 1000;
	}
	  
</style> 

<div class="container-fluid">	 

<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start" id="offcanvas">
  <div class="offcanvas-header">
    <h2 class="offcanvas-title">외주가공(디에스레이져)</h2>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
     <img src="../img/dslaser.jpg" style="width:95%;">
  </div>
</div>

 
<div class="card mt-1">	     
<div class="card-body">	     
	<div class='bigPictureWrapper'>
		<div class='bigPicture'>
		</div>	   
	</div>      	

<div class="row mt-1 align-items-center"> 		
<div class="col-sm-1"> 		
</div> 		
<div class="col-sm-10"> 		
<div class="d-flex justify-content-start align-items-center mt-3 mb-2 ">		
	<span class="fs-6 me-5">   <?=$title_message?>  </span>	
	<button class="btn btn-dark btn-sm" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>';" >  <i class="bi bi-pencil-square"></i>  수정 </button>&nbsp;						
	<button class="btn btn-danger btn-sm" onclick="javascript:del('delete.php?num=<?=$num?>')">  <i class="bi bi-trash"></i> 삭제  </button>&nbsp;						
	<button class="btn btn-primary btn-sm" onclick="location.href='write_form.php?mode=copy&num=<?=$num?>';" > <i class="bi bi-copy"></i> 복사 </button>&nbsp;
	<button class="btn btn-dark btn-sm" onclick="location.href='write_form.php';" > <i class="bi bi-pencil"></i>  신규 </button>&nbsp;	
	<button class="btn btn-success btn-sm" onclick="popupCenter('transform.php?num=<?=$num?>','출고증',1500,900);" > <i class="bi bi-printer"></i>  출고증  </button>&nbsp;
	<button id="QCBtn"  type="button" onclick="viewOI();" class="btn btn-success btn-sm"  > <i class="bi bi-printer"></i> 출하검사서 </button>&nbsp;	
	<?php if(!empty($pjnum)) { ?>
		<button id="tkeconfirmBtn"  type="button" class="btn btn-success btn-sm" >  TKE 완료확인서 </button>&nbsp;			
	<?php } ?>

<?php if ($chkMobile): ?>
     </div>
	<div class="d-flex justify-content-start mb-3 mt-3  align-items-center"> 		
<?php else: ?>     
<?php endif; ?>	
	<span class="me-4"> 
	등록 : <?=$first_writer?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	  <?php
			  $update_log_extract = substr($update_log, 0, 31);  // 이미래....
	  ?>
	최종수정 : <?=$update_log_extract?> &nbsp;&nbsp;&nbsp;
	</span> &nbsp;
	
			<button type="button" class="btn btn-outline-dark btn-sm" id="showlogBtn"   >								
				H
			</button>					
					
	</div>
	</div>
<div class="col-sm-1 text-end"> 	
	<button class="btn btn-secondary btn-sm" onclick="self.close();" > <i class="bi bi-x-lg"></i> 창닫기 </button>&nbsp;	
</div> 			 
</div> 			 
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >

<?php if ($chkMobile): ?>
	<div class="col-sm-12 rounded"   style=" border: 1px solid #392f31; " > 
<?php else: ?>
     <div class="d-flex justify-content-center mb-1 mt-1"> 	
	<div class="col-sm-7 p-1  rounded"   style=" border: 1px solid #392f31; " > 
<?php endif; ?>	

 <table class="table table-bordered" >
  <tbody>
    <tr  style="border: none;">
      <td class="col"  >현장명</td>
      <td colspan="3" >
        <input type="text" id="workplacename" name="workplacename" value="<?=$workplacename?>" class="form-control" style="text-align:left; " required>
      </td>	
	  <td colspan="2" >
	        <div class="d-flex justify-content-center align-items-center fs-5"> 				  
				   공사유형 &nbsp;&nbsp;  		  
				  <span class="badge <?php 
				    if($checkstep === '신규') echo 'bg-primary';
				    else if($checkstep === '덧씌우기') echo 'bg-dark'; 
				    else if($checkstep === '부대공사') echo 'bg-success';
				  ?>" >  <?= $checkstep ; ?>	       </span>				  
		    </div>
      </td>		
      <td colspan="2" >
     	 
	   <?php
	      if($outsourcing === '외주')
		  {
			    print '<h5> <span id="outsourcingBtn" class="badge bg-success ">외주가공</span>    &nbsp;	';
				print '<input type="checkbox" checked id="outsourcing" name="outsourcing" onclick="return false;" value="외주" >  ';				
		  }		
			?>
<div id="showframe" class="card">
    <div class="card-header" style="padding:2px;">
        외주 단가 산출표
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">구분</th>
                    <th scope="col">수량</th>
                    <th scope="col">단가</th>
                    <th scope="col">금액</th>
                </tr>
            </thead>
		<tbody>
			<tr>
				<td>와이드쟘(막판유)</td>
				<td><?= $widejamb ? number_format($widejamb) : $widejamb ?></td>
				<td><?= $widejamb_unitprice ? $widejamb_unitprice : '' ?></td>
				<td><?= $widejamb_amount ? number_format($widejamb_amount) : $widejamb_amount ?></td>
			</tr>
			<tr>
				<td>멍텅구리(막판무)</td>
				<td><?= $normaljamb ? number_format($normaljamb) : $normaljamb ?></td>
				<td><?= $normaljamb_unitprice ? $normaljamb_unitprice :'' ?></td>
				<td><?= $normaljamb_amount ? number_format($normaljamb_amount) : $normaljamb_amount ?></td>
			</tr>
			<tr>
				<td>쪽쟘</td>
				<td><?= $smalljamb ? number_format($smalljamb) : $smalljamb ?></td>
				<td><?= $narrowjamb_unitprice ? $narrowjamb_unitprice : '' ?></td>
				<td><?= $narrowjamb_amount ? number_format($narrowjamb_amount) : $narrowjamb_amount ?></td>
			</tr>
			<tr>
				<td class="text-primary" > <strong> 합계 </strong> </td>
				<td class="text-primary" > <strong> <?= $sum1 ? number_format($sum1) : $sum1 ?> </strong> </td>
				<td></td>
				<td class="text-primary" > <strong> <?= $sum2 ? number_format($sum2) : $sum2 ?> </strong> </td>
			</tr>
		</tbody>
		
        </table>
    </div>      
</div>			
		</h5>
      </td>		  
    </tr>
    <tr>
      <td class="col">주소</td>
      <td colspan="3"><input type="text" name="address" value="<?=$address?>" class="form-control" style="text-align:left;">
      </td>		      
    </tr>
    <tr>
      <td class="col" style="width:10%;" > 원청</td>
      <td class="col" style="width:18%;" >   <input type="text" id="firstord" name="firstord" value="<?=$firstord?>" class="form-control"></td>
      <td class="col" style="width:10%;" > 담당</td>
      <td class="col" style="width:18%;" >   <input type="text" id="firstordman" name="firstordman" value="<?=$firstordman?>" class="form-control" onkeydown="JavaScript:Enter_firstCheck();"></td>
      <td class="col" style="width:10%;" >Tel</td>
      <td class="col" style="width:18%;" >   <input type="text" id="firstordmantel" name="firstordmantel" value="<?=$firstordmantel?>" class="form-control"></td>
	  <td> </td>
    </tr>
    <tr>
      <td class="col">발주처</td>
      <td class="col"><input type="text" id="secondord" name="secondord" value="<?=$secondord?>" class="form-control"></td>
      <td class="col">담당</td>
      <td class="col"><input type="text" id="secondordman" name="secondordman" value="<?=$secondordman?>" class="form-control" onkeydown="JavaScript:Enter_Check();"></td>
      <td class="col">Tel</td>
      <td class="col"><input type="text" id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>" class="form-control"></td>
	  <td> </td>
    </tr>
    <tr>
      <td class="col"></td>
      <td class="col">
	  
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
    echo '<a href="javascript:void(0);" onclick="openPopup()"> https://customer.tkek.co.kr/stop/_enter </a>';    	
	echo '업체코드:50001672, 소장이름 <br>';	
	echo '반드시 선택은 <b> <span style="color:red;"> 조달->박일우 </span></b>';	
}

?>

<script>
function openPopup() {
    // Modify the window features as needed (width, height, etc.)
    var popup = window.open("https://customer.tkek.co.kr/stop/_enter", "TKE 등록화면", "width=1400,height=900");
    // Focus the popup window
    if (window.focus) {
        popup.focus();
    }
}
</script>	  
	  </td>
      <td class="col">현장담당</td>
      <td class="col"><input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control" onkeydown="JavaScript:Enter_chargedman_Check();"></td>
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
      <td class="col" style="width:16%;"> <input disabled type="text" name="designer" value="<?=$designer?>" class="form-control"></td>
      <td class="col"  style="width:8%;"> 도면<br>완료</td>
      <td class="col" style="width:16%;"> <input type="date" name="drawday" id="drawday" value="<?=$drawday?>" placeholder="도면설계" class="form-control"></td>
      <td class="col"  style="width:12%;">  도면폴더</td>
      <td class="col" style="width:16%;"> <a id="dwgclick" href="#" class="text-decoration-none"><?= mb_strlen($dwglocation, 'UTF-8') > 14 ? mb_substr($dwglocation, 0, 8, 'UTF-8') . '...' : $dwglocation ?></a></td>
    </tr>
    <tr>
      <td class="col" style="color: blue;">출고</td>
      <td class="col"><input type="date" name="workday" id="workday" value="<?=$workday?>" placeholder="출고" class="form-control"></td>
      <td class="col" style="margin-left: 55px;">시공</td>
      <td class="col"><input type="text" id="worker" name="worker" value="<?=$worker?>" placeholder="시공팀" class="form-control"></td>
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
	   <td colspan="2"> 
	   <?php  if($madeconfirm === '1') { ?>
	   <h5> <span class="badge bg-primary " > 
	   제작완료 확인함  </span> <h5> 
	   <?php  } ?>
	   </td>
    </tr>	
    <tr>
      <td class="text-danger" >시공비<br>처리일</td>
      <td><input type="date" name="workfeedate" id="workfeedate" value="<?=$workfeedate?>"  class="form-control" ></td>      
      <td class="text-danger" >청구</td>
      <td><input type="date" name="demand" id="demand" value="<?=$demand?>"  class="form-control"  ></td>

    </tr>
  </tbody>
</table>
 
  <table class="table table-responsive table-fixed">
    <tbody>
      <tr>
        <td class="col" style="width:10%;">재질1</td>
        <td class="col" style="width:23%;">   
		<?php if($checkmat1 ==='checked') { ?>
			<input  type="checkbox" name="checkmat1" id="checkmat1" value="checked" <?=$checkmat1?>   > 사급
		   <?php } ?>	
		   <?php if(!empty($material1)){ ?>		   
				<input  type="text" name="material1" id="material1" class="form-control"  value="<?=$material1?>"  >
			<?php } if(!empty($material2)){ ?>		   								
				<input  type="text" name="material2" id="material2" class="form-control"  value="<?=$material2?>" >	
			<?php } ?>		   										

        </td>          
		<td class="col" style="width:10%;">재질2</td>
        <td class="col" style="width:23%;">   
		   <?php if($checkmat2 ==='checked') { ?>
			<input  type="checkbox" name="checkmat2" id="checkmat2" value="checked"  <?=$checkmat2?>  > 사급
		   <?php } ?>
			<?php if(!empty($material3)){ ?>		   
				<input type="text" name="material3" id="material3" class="form-control" value="<?=$material3?>"  >
			<?php } if(!empty($material4)){ ?>		   								
				<input type="text" name="material4" id="material4" class="form-control"  value="<?=$material4?>" >			
			<?php } ?>				   
        </td>             
		<td class="col" style="width:10%;">재질3</td>
        <td class="col" style="width:23%;">   
		<?php if($checkmat3 ==='checked') { ?>		
			<input  type="checkbox" name="checkmat3" id="checkmat3" value="checked"  <?=$checkmat3?>  > 사급
		<?php } ?>					
			<?php if(!empty($material5)){ ?>		   
				<input  type="text" name="material5" id="material5" class="form-control" value="<?=$material5?>" >
			<?php } if(!empty($material6)){ ?>		   								
				<input  type="text" name="material6" id="material6" class="form-control" value="<?=$material6?>" >
			<?php } ?>				   		
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
      <td colspan="3" class="text-start"><h6> <span class="badge bg-primary"> (비표준) 막판 시공비     </span></h6> </td>
	  <td colspan="3" class="text-start"><h6> <span class="badge bg-primary"> (비표준) 막판(無)시공비  </span></h6> </td>
	  <td colspan="4" class="text-start"><h6> <span class="badge bg-primary"> (비표준) 쪽쟘 시공비     </span></h6> </td>
	  <td colspan="4" class="text-center"><h6>  검색 보류 설정     </td>
	</tr>
</thead>
 <tbody> 
	<tr>
	  <td colspan="3" class="text-center" > <input type="text" id="widejambworkfee" name="widejambworkfee" value="<?=$widejambworkfee?>"  class="form-control text-success text-center w80px"></td>    
	  <td colspan="3" class="text-center" > <input type="text" id="normaljambworkfee" name="normaljambworkfee" value="<?=$normaljambworkfee?>"  class="form-control text-success w80px" ></td>    
	  <td colspan="4" class="text-center" > <input type="text" id="smalljambworkfee" name="smalljambworkfee" value="<?=$smalljambworkfee?>"  class="form-control text-success w80px" ></td>    
	  <td colspan="4" class="text-center" > 
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
	<div class="col-sm-12 rounded"   style=" border: 1px solid #392f31; " > 
<?php else: ?>
	<div class="col-sm-5 p-1  rounded"   style=" border: 1px solid #392f31; " > 
<?php endif; ?>	

<div id="measureimgBtn" >

		<span class="text-center text-primary ">			
		   <h5>  실측서 이미지 </h5>
		</span>		
		<style>
		  .table td input.form-control {
			height: 28px;
		  }
		</style>
		
	    <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">  				 				  
			<input type="hidden" id="id" name="id" value="<?=$num?>" >
			<input type="hidden" id="num" name="num" value="<?=$num?>" >				 
			<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >	 	 
			<input type="hidden" id="item" name="item" value="<?=$item?>" >	 
			<input type="hidden" id="insertword" name="insertword" value="<?=$insertword?>" >	 

			<input type="hidden" id="before_work_rotate"  name="before_work_rotate" value="<?=$before_work_rotate?>" >	 
			<input type="hidden" id="after_work_rotate"  name="after_work_rotate" value="<?=$after_work_rotate?>" >	 				 

			<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>"  > 				 	 
			<input id="pInput" name="pInput" type="hidden" value="0"> 
				<span class="form-control text-center">
				<span style="color:gray"> 실측서 이미지파일 </span>
				   <input id="upfile"  name="upfile[]" class="input" type="file" onchange="this.value" multiple accept=".gif, .jpg, .png">
				</span>			
			<div  class="container">	   
				<div class="card-body text-center">			
					<div id = "displayPicture" class="mb-2" style="display:none;" >  </div>   
					<br>	
				</div>									
			</div>			
			</form>		
		</div>	
		
<div id="signature" > 
	<div class="card ">	     
		<div class="card-body">	 
			<div class="d-flex  justify-content-center align-items-center"> 
				<span class="badge bg-success me-2 fs-6">			
					  TKE 시공확인서 
				</span>					
				<span class="text-center text-dark me-1 ">			
					<h5>  확인자: </h5> 
				</span>					
				<span class="text-center text-success me-4 ">			
					<h5>  <?=$customer_name?>  </h5> 
				</span>				
				
					<?php if(!empty($customer_name)) { 					   
					   print ' <img src="./' . $image_url . '" style="width:12%;">';					   
					} ?>
				
			</div>			
		</div>			
	</div>			
</div>			

<div id="pic_display">	   		
			
            <div class="d-flex  mt-3 mb-4 justify-content-center align-items-center"> 
                <button type="button" id="urlsave" class="btn btn-primary btn-sm"  > 시공URL <i class="bi bi-copy"></i> </button>  &nbsp;  	
                <input type="text" name="URL" id="URL"  class="form-control" style="width:280px;" value="<?=$URLsave?>">
                &nbsp; &nbsp; &nbsp;
                <button type="button" id="passpic" class="btn btn-dark btn-sm" > 사진등록 패스</button>  &nbsp; 		   
                <button type="button" id="copypic" class="btn btn-dark btn-sm" > 빈 사진칸 복사넣기 </button> 
            </div>
	<div class="d-flex  mt-3 mb-4 justify-content-center align-items-center"> 
        <div class="col-lg-6" style="border:1px solid">			            
			<h5 class="text-secondary text-center mt-3" > 시공 전  </h5>   
				
            <div class="d-flex  mt-3 mb-4 justify-content-center align-items-center"> 
						회전 &nbsp;
                <button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('before_work',0)"> 
                                    90                           </button>	&nbsp;&nbsp;
                <button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('before_work',270)"> 
                                    180                          </button>	&nbsp;&nbsp;
                <button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('before_work',180)"> 
                                    270                           </button>	&nbsp;&nbsp;
                <button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('before_work',90)"> 
                                    0                             </button>	&nbsp;&nbsp;          
            </div> 
            <div class="d-flex mt-3 mb-4 justify-content-center align-items-center"> 
                <?php
                    if($filename1=='pass') {
                        print " 사진등록 패스 현장임<br><br><br>";
                    } else if($filename1!="") {
                        print "<div class='card'>";
                        print "<div class='card-body'>";
						print "<div class='d-flex justify-content-center align-items-center'>";
                        print "<div class='imagediv ' > ";
                        print '<img id="before_work" src="' . $imgurl1 . '" style="margin-left:20%;width:60%;" >';
                        print '   </div> </div> </div> </div>  '; 
                    }
                ?>	
            </div> 
          </div> 
           
		  <div class="col-md-6" style="border:1px solid" class="justify-content-center">	
				
				<h5 class="text-primary text-center mt-3" > 시공 후   </h5>
					
				<div class="d-flex  mt-3 mb-4 justify-content-center align-items-center"> 
				    회전 &nbsp;
					<button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('after_work',0)"> 
										90                           </button>	&nbsp;&nbsp;
					<button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('after_work',270)"> 
										180                           </button>	&nbsp;&nbsp;
					<button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('after_work',180)"> 
										270                           </button>	&nbsp;&nbsp;
					<button type="button" class="btn btn-outline-dark btn-sm "  onclick="rotateimage('after_work',90)"> 
										0                          </button>	&nbsp;&nbsp;          
				</div> 
				<div class="d-flex  mt-3 mb-4 justify-content-center align-items-center"> 
					<?php
						if($filename2=='pass') {
							print " 사진등록 패스 현장임 <br><br><br>";
						} else if($filename2!="")  {
							print "<div class='card'>";
							print "<div class='card-body'>";
							print "<div class='d-flex justify-content-center align-items-center'>";
							print "<div class='imagediv justify-content-center  ' > ";
							print '<img id="after_work" src="' . $imgurl2 . '" style="margin-left:20%;width:60%;" >';
							print '   </div> </div> </div> </div>  '; 														
						}
					?>
				</div> 
			</div>
		</div> 		
	

  </div> 

	<?php
	// 시공사진 파일 없으면 안보이게 하기
	if ($filename1 == "" && $filename2 == "") {
	  echo '<script>
		var picDisplay = document.getElementById("pic_display");
		picDisplay.style.display = "none";
	  </script>';
	}
	?>
		
</div> 	  
</div>	
</div>		
</div>		
</div>		
</div>		
</div>	
</body>
</html>    

<script>

document.addEventListener("DOMContentLoaded", function() {
 // showdate 요소를 이용해서 기간검색창 위에 뜨기
var showdate = document.getElementById('outsourcingBtn');
var showframe = document.getElementById('showframe');
var hideTimeout; // 프레임을 숨기기 위한 타이머 변수

if(showdate && showframe)
{
	showdate.addEventListener('mouseenter', function(event) {
		clearTimeout(hideTimeout);  // 이미 설정된 타이머가 있다면 취소
		showframe.style.top = (showdate.offsetTop + showdate.offsetHeight) + 'px';
		showframe.style.left = showdate.offsetLeft + 'px';
		showframe.style.display = 'block';
	});

	showdate.addEventListener('mouseleave', startHideTimer);

	showframe.addEventListener('mouseenter', function() {
		clearTimeout(hideTimeout);  // 이미 설정된 타이머가 있다면 취소
	});

	showframe.addEventListener('mouseleave', startHideTimer);

	function startHideTimer() {
		hideTimeout = setTimeout(function() {
			showframe.style.display = 'none';
		}, 300);  // 300ms 후에 프레임을 숨깁니다.
	}
}
  
});  

 
// JavaScript
window.onload = function() {
    let inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(function(input) {
        input.setAttribute('readonly', 'true');
        input.style.backgroundColor = '#f5f5f5'; // Lighter gray color
        input.style.border = '1px solid #ddd'; // Light gray border
    });
}

$(document).ready(function(){	
// DS레이져 문구 클릭시
      $("#outsourcingBtn").click(function () {
        $("#offcanvas").offcanvas('show');
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
		
	// 주문서 모달 뒷배경 눌렀을때 닫히지 않게 하기
	$('#qc_form_write').modal( {
		backdrop: 'static', keyboard : false });	
	
	// 모달창 닫기	 	 
	$("#qc_form_write").click(function(){ 
		$('#qc_form_write').modal('hide');
	});	
	
// 출하검사서 버튼클릭
viewOI = function () {
	
	const num = '<?php echo $num; ?>';
	myalert("자료를 읽고 있습니다. 잠시만 기다려주세요!");
	 
	 var custmsg = "?menu=no&parentID=" + num ;

	
	 popupCenter("../p_inspection/view.php" + custmsg , '출하검사서', 1800, 900);		
	
}   
	
	
// 전체화면에 꽉찬 이미지 보여주는 루틴
		$(document).on("click","img",function(){
			var path = $(this).attr('src')
			var srcID = $(this).attr('id')
			showImage(path, srcID);
			
			console.log('srcID');
			console.log(srcID);
		});//end click event
		
			
		function showImage(fileCallPath, srcID){
		  var elementId = srcID.includes('before') ? 'before_work' : 'after_work';
		  var rotationValue = elementId === 'before_work' ? $("#before_work_rotate").val() : $("#after_work_rotate").val();
		  var transformValue = 'rotate(' + rotationValue + 'deg)';

		  $(".bigPictureWrapper").css("display","flex").show();
		  
		  $(".bigPicture")
		  .html("<img src='"+fileCallPath+"' style='transform:"+transformValue+";' >")
		  .animate({width:'100%', height: '100%'}, 1000);
		}//end fileCallPath
		  
		$(".bigPictureWrapper").on("click", function(e){
		    $(".bigPicture").animate({width:'0%', height: '0%'}, 1000);
		    setTimeout(function(){
		      $('.bigPictureWrapper').hide();
		    }, 1000);
		  });//end bigWrapperClick event
		  
// end of 이미지 꽉찬 화면 보여주기
	
// 매초 검사해서 이미지가 있으면 보여주기
$("#pInput").val('50'); // 최초화면 사진파일 보여주기
displayPicture();  
	
let timer3 = setInterval(() => {  // 2초 간격으로 사진업데이트 체크한다.
	      if($("#pInput").val()=='100')   // 사진이 등록된 경우
		  {
	             displayPicture();  
				 // console.log(100);
		  }	      
		  if($("#pInput").val()=='50')   // 사진이 등록된 경우
		  {
	             displayPictureLoad();		
				$("#pInput").val(''); // 최초화면 사진파일 보여주기 
		  }	     
		   
	 }, 1000);	
	 
  
delPicFn = function(divID, delChoice) {
	console.log(divID, delChoice);
if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	$.ajax({
		url:'../p/old_delpic.php?picname=' + delChoice ,
		type:'post',
		data: $("board_form").serialize(),
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
	  	 	 
// 시공전 사진 멀티업로드	
$("#upfile").change(function(e) {	    
        // 실측서 이미지 선택	    
	    $("#item").val('measure');
	    var item = $("#item").val();
		FileProcess(item);	
});	 
	
function FileProcess(item) {
		//do whatever you want here
		num = $("#num").val();

		  // 사진 서버에 저장하는 구간	
		  // 사진 서버에 저장하는 구간	
        //tablename 설정
       $("#tablename").val('work');  
		// 폼데이터 전송시 사용함 Get form         
		var form = $('#board_form')[0];  	    
		// Create an FormData object          
		var data = new FormData(form); 

		tmp='사진을 저장중입니다. 잠시만 기다려주세요.';		
		$('#insertword').val(''); 			  
		$('#alertmsg').html(tmp); 			  
		$('#myModal').modal('show'); 	

		$.ajax({
			enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 			
			url: "../p/old_mspic_insert.php?num=" + num ,
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
 
 
	 	 
$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
		
$("#closeBtn").click(function(){    // 저장하고 창닫기	
	 });	
	
	
// 도면폴더 클릭시 실행
$("#dwgclick").click(function() {
    var Urls = '<?php echo $dwglocation; ?>';          
    Urls = "\\\\nas2dual\\잠완료\\" + Urls.trim();  
   
    var tmp = "<br> 도면저장 폴더위치가 복사되었습니다. <br> 탐색기에 'Ctrl+v'로 붙여넣기 하세요! <br>";               
    $('#alertmsg').html(tmp); 
    $('#myModal').modal('show');           
	
	copyTextToClipboard(Urls);   
});

		
	$("#closeModalBtn").click(function(){  
	  	    $('#myModal').modal('hide');		
	});
	
	
	$("#copypic").click(function(){  
	
	$("#copyfilename").val('copy');
	
	    $.ajax({
			url: "passpic.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
		    setTimeout(function() { 
               location.reload();   // refresh
               }, 1000);
		  
		   
	});		
	
	$("#passpic").click(function(){  
	
	    $.ajax({
			url: "passpic.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
				console.log( data);
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
		    setTimeout(function() { 
               location.reload();   // refresh
               }, 1000);
		  
		   
	});	
		
	$("#tkeconfirmBtn").click(function(){  
		const num = $("#num").val();
		popupCenter('../p/customer_print.php?num=' + num , '공 사 완 료 확 인 서', 1200, 900);   
		   
	});	
	
		
	$("#urlsave").click(function() {
		var tmp = "<br> URL이 복사되었습니다. <br> 크롬 브라우저 주소창에 'Ctrl+v'로 붙여넣기 하세요! <br>";               
		$('#alertmsg').html(tmp); 
		$('#myModal').modal('show');  		
		copyTextToClipboard($("#URL").val());
	});
	
}); // end of ready

function fallbackCopyTextToClipboard(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand("copy");
    var msg = successful ? "successful" : "unsuccessful";
    console.log("Fallback: Copying text command was " + msg);
  } catch (err) {
    console.error("Fallback: Oops, unable to copy", err);
  }

  document.body.removeChild(textArea);
}

function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text).then(
    function() {
      console.log("Async: Copying to clipboard was successful!");
    },
    function(err) {
      console.error("Async: Could not copy text: ", err);
    }
  );
}

// 실측서 이미지 불러오기
function displayPicture() {       
	$('#displayPicture').show();
	params = $("#num").val();	
	$("#tablename").val('work');
	$("#item").val('measure');	
	
    var tablename = $("#tablename").val();    
    var item = $("#item").val();	
	
	$.ajax({
		url:'../p/old_load_pic.php?num=' + params + '&tablename=' + tablename + '&item=' + item ,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recnum = data["recnum"];		   
		   console.log(data);
		   $("#displayPicture").html('');
		   for(i=0;i<recnum;i++) {			   
			   $("#displayPicture").append("<img id=pic" + i + " src ='../uploads/" + data["img_arr"][i] + "' style='width:100%; '  > <br> " );			   
         	   $("#displayPicture").append("&nbsp;<button type='button' class='mt-2 btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  data["img_arr"][i] + "')> 삭제 </button>&nbsp; <br>");					   
		      }		   
			    $("#pInput").val('');			
    });	
}

// 실측서 이미지 기존거 불러오기
function displayPictureLoad() {    
	$('#displayPicture').show();
	var picNum = "<? echo $picNum; ?>"; 					
	var picData = <?php echo json_encode($picData);?> ;	
	console.log(picNum);
	console.log(picData);
    for(i=0;i<picNum;i++) {
       $("#displayPicture").append("<img id=pic" + i + " src ='../uploads/" + picData[i] + "' style='width:100%;' > <br>" );			
	   // $("#displayPicture").zoom();
	   $("#displayPicture").append("&nbsp;<button type='button' class='mt-2 btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + picData[i] + "')> 삭제 </button>&nbsp;<br>");			   
	  }		   
		$("#pInput").val('');	
}
	

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

	 
 function displayoutputlist(){

   $("#displayoutput").show(); 
   $("#displayoutput").load("./outputlist.php");	 	 
		 
	 }
	 
 // 사진 회전하기
function rotate_image() {
  var box = $('.imagediv');
  var imgObj = new Image();
  var imgObj2 = new Image();
  imgObj.src = "<? echo $imgurl1; ?>";
  imgObj2.src = "<? echo $imgurl2; ?>";
  var maxWidth = 300; // 최대 가로 너비
  var maxHeight = 500; // 최대 세로 높이
  
  // 이미지 로드가 완료된 후에 실행
  imgObj.onload = function() {
    imgObj2.onload = function() {
      var width1 = imgObj.width;
      var height1 = imgObj.height;
      var width2 = imgObj2.width;
      var height2 = imgObj2.height;
      
      var scale1 = Math.min(maxWidth / width1, maxHeight / height1);
      var scale2 = Math.min(maxWidth / width2, maxHeight / height2);
      var scale = Math.min(scale1, scale2);
      
      // 이미지 크기에 따라 화면 사이즈 조정
      var newWidth = width1 * scale;
      var newHeight = height1 * scale;
      
      box.css('width', newWidth + 'px');
      box.css('height', newHeight + 'px');
      box.css('margin-top', '30px');
      
      if (width1 > height1 || width2 > height2) {
        $('#before_work').addClass('rotated');
        $('#after_work').addClass('rotated');
      }
    };
  };
}

function rotateimage(elementId, degrees) {
  var element = document.getElementById(elementId);
  var transformValue = 'rotate(' + degrees + 'deg)';
  element.style.transform = transformValue;

  if(elementId === 'before_work')
     $("#before_work_rotate").val(degrees);
   if(elementId === 'after_work')
     $("#after_work_rotate").val(degrees); // 이 부분을 수정했습니다.
}


setTimeout(function() {
  rotate_image();
}, 500);

function myalert(str) {

 Toastify({
		text: str,
		duration: 3000,
		close:true,
		gravity:"top",
		position: "center",
		backgroundColor: "#4fbe87",
	}).showToast();	
	
	setTimeout(function() {
		// 시간지연
		}, 1000);
	
}	


function clearKey() {
	//임시키를 초기화 함
	$("#tmpKey").val('');  	 							 
}

// 6자리 문자열의 임시키만들어준다.	
function generateRandomKey() {
  const length = 6;
  const chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  let result = '';
  for (let i = 0; i < length; i++) {
    result += chars[Math.floor(Math.random() * chars.length)];
  }
  return result;
}	

// 문자, 공백, 혹은 false, 아니면 true 반환
function isNumValid(num) {
  if (!isNaN(num) && num > 0) {
    return true; // 숫자이고 0보다 큰 값인 경우
  } else {
    return false; // 그 외의 경우
  }
}
	
// xml이미지를 img tag 형태로 변환하는 함수
function displayimageTag(str) {
  // return '<img style="width:100%;height:100%;object-fit: cover;" src="../uploads/' + str + '" >';
  // 셀에 맞춰 이미지 나오게 하는 코드
 return '<img style="max-width: 100%; max-height: 30px; vertical-align: middle;" src="../uploads/' + str + '" >';
  
}

function del(href) {    
	var user_name = '<?php echo $user_name; ?>';
	var first_writer = '<?php echo $first_writer; ?>';
	var admin = '<?php echo $admin; ?>';

if (!first_writer.includes(user_name) && admin !== '1') 
   {	
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
        Swal.fire({
            title: '자료 삭제',
            text: "삭제는 신중! 정말 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
				$.ajax({
					url: "delete.php",
					type: "post",		
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){
						console.log(data);
						Toastify({
							text: "파일 삭제완료 ",
							duration: 2000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
								window.close();
							}							
							
						}, 1000);	
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					} 			      		
				   });	
                    

            }
        });
    }
}
 
 
document.addEventListener("DOMContentLoaded", function() {
  const textarea = document.getElementById("memo");
  const textarea_aslist = document.getElementById("content");

  // textarea 높이를 조절하는 함수
  function adjustHeight(el) {
    el.style.height = "auto";             // 먼저 높이를 초기화
    el.style.height = el.scrollHeight + "px"; // 내용에 맞춰 높이 재설정
  }

  // 입력 이벤트 발생 시 높이 조절
  textarea.addEventListener("input", function() {
    adjustHeight(this);
  });

  // 페이지 로드시, 이미 입력된 내용이 있다면 높이 조절
  adjustHeight(textarea);
  
  // 입력 이벤트 발생 시 높이 조절
  textarea_aslist.addEventListener("input", function() {
    adjustHeight(this);
  });

  // 페이지 로드시, 이미 입력된 내용이 있다면 높이 조절
  adjustHeight(textarea_aslist);
}); 
 
</script>
