 <?php
  session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }   
   
 include "../load_company.php";
 $companycount = count($suply_company_arr);   
 // var_dump($suply_company_arr);
 // 납품업체 숫자 넘겨줌 
  
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
 
 
//  철판리스트 뽑기 
   
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  

// 구매처 읽어오기  
     
// 철판종류에 대한 추출부분
  
   $sql="select * from mirae8440.steelsource order by sortorder asc, item desc "; 					

	 try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $counter=0;
   $item_counter=1;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();
   $steelsource_item_yes=array();
   $steelsource_spec_yes=array();
   $spec_arr=array();
   $company_arr=array();
   $title_arr=array();
   $last_item="";
   $last_spec="";
   $pass='0';
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

	   
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=trim($row["item"]);
 			  $steelsource_spec[$counter]=trim($row["spec"]);
		      $steelsource_take[$counter]=trim($row["take"]);   
			  
			  if($steelsource_item[$counter]!=$last_item)
			  {
				 $last_item= $steelsource_item[$counter];
			     $steelsource_item_yes[$item_counter]=$last_item;
				 $item_counter++;
			  }
			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

array_push($steelsource_item_yes," ");
$steelsource_item_yes = array_unique($steelsource_item_yes);
sort($steelsource_item_yes);

$sumcount = count($steelsource_item_yes);
	
	
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
		  
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];
 
$sql="select * from mirae8440.steelsource order by sortorder asc, item asc, spec asc"; 	// 정렬순서 정함.				

$sum_title=array(); 
$sum= array();

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=trim($row["item"]);
 			  $steelsource_spec[$counter]=trim($row["spec"]);
		      $steelsource_take[$counter]=trim($row["take"]);  
              array_push($sum_title, $steelsource_item[$counter] . $steelsource_spec[$counter]. $steelsource_take[$counter]) ;
              array_push($company_arr, $steelsource_take[$counter]) ;
	   $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$sum_title = array_unique($sum_title);  // 고유번호이름만 살리기
sort($sum_title);  // 고유번호이름만 살리기

 // 전체합계(입고부분)를 산출하는 부분 
$sql="select * from mirae8440.steel order by outdate";
 
$tmpsum = 0; 

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			  $outdate=$row["outdate"];			  
			  $item=trim($row["item"]);			  
			  $spec=trim($row["spec"]);
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  
        // 일반매입처리
        if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
            $company = '';
        }
			  
			  $tmp=$item . $spec . $company;
	
        for($i=0;$i<count($sum_title) ; $i++) {  	          
			  if($which=='1' and $tmp==$sum_title[$i])
				     $sum[$i]= $sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
			  if($which=='2' and $tmp==$sum_title[$i])
				    $sum[$i] =  $sum[$i] - (int)$steelnum;
		           }
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
      
// 철판 종류 불러오기
$sql="select * from mirae8440.steelitem"; 					

	 try{  

   $stmh = $pdo->query($sql);            
   $rowNum = $stmh->rowCount();     
   $steelitem_arr=array();

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
 			 array_push($steelitem_arr, trim($row["item"]));
			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    
   $item_counter=count($steelitem_arr);
   
$steelitem_arr = array_unique($steelitem_arr); 
sort($steelitem_arr);  // 오름차순으로 배열 정렬      
   
// 철판 규격 불러오기
$sql="select * from mirae8440.steelspec"; 					

	 try{  

   $stmh = $pdo->query($sql);            
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $spec_arr=array();

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
 			  $spec_arr[$counter]=trim($row["spec"]);
			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

   $spec_counter=count($spec_arr);
   sort($spec_arr);  // 오름차순으로 배열 정렬
   
    
  if ($mode=="modify"){
    try{
      $sql = "select * from mirae8440.cost where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "결과가 없습니다.<br>";
     }else{
		  include '_row.php';		  
	  
			 if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					else $indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 					
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  }
    
  if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  $outdate=date("Y-m-d");
			  $requestdate=null;
			  $indate=null;
			  $outworkplace=$row["outworkplace"];
			  
			  $model=null;			  
			  $item=null;			  
			  $spec=null;
			  $steelnum=null;			  
			  $company="";
			  $supplier="";
			  $comment=null;
			  $which="1";	 			   // 요청 
  } 

// print '$item';
// var_dump($item);
// var_dump($steelitem_arr);

     $titlemsg	= '원자재 단가 등록';	

?> 
 <?php include getDocumentRoot() . '/load_header.php' ?>
  
<title> 원자재 발주 </title>

</head>

   
<style>

.show {display:block} /*보여주기*/

.hide {display:none} /*숨기기*/

</style>
 
<body>
   
   
<?php include "../common/modal.php"; ?>

   
   <div class="container">
	   <?php
    if($mode=="modify"){
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" > 
  <?php  } else {
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"> 
  <?php
	}
  ?>	   
	
    <div class="card mt-2 mb-3">
       <div class="card-body">
	       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
           <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
	
	
			   
	<div class="d-flex mb-5 mt-5 justify-content-center align-items-center"> 	
		<h4> <?=$titlemsg?> </h4> 
	</div>	
	         
	<div class="d-flex mt-3 justify-content-start align-items-center"> 	
  	   <button id="closeBtn" type="button" onclick="self.close();" class="btn btn-dark  btn-sm me-2"  > <ion-icon name="close-outline"></ion-icon> 창닫기  </button> 	   
  	   <button id="saveBtn" type="button" class="btn btn-dark  btn-sm me-2"  > <ion-icon name="save-outline"></ion-icon> 저장  </button> 
		   
	</div>		
	
   	
  <div class="row mt-3 mb-3">
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-7 " >';
	  else
		  echo '<div class="col-lg-12" >';
	  ?>
	 <div class="card" >
		<div class="card-body mt-2 mb-2" >
	 
      <table class="table table-bordered">        
        <tr>
          <td>
            <label>진행상태</label>
          </td>
          <td>
				<?php	 		  
			 $aryreg=array();
			 if($which=='') $which='2';
			 switch ($which) {
				case   "1"             : $aryreg[0] = "checked" ; break;
				case   "2"             :$aryreg[1] =  "checked" ; break;
				case   "3"             :$aryreg[2] =  "checked" ; break;
				default: break;
			}		 
		   ?>		  			  
				   <span class="text-primary">  요청  </span> &nbsp;      <input  type="radio" <?=$aryreg[0]?> name=which value="1"> &nbsp;&nbsp;
					&nbsp;   <span class="text-danger">  발주보냄  </span> &nbsp;            <input  type="radio" <?=$aryreg[1]?>  name=which value="2">   &nbsp;&nbsp; 
					&nbsp;  <span class="text-dark">  입고완료  </span> &nbsp;           <input  type="radio" <?=$aryreg[2]?>  name=which value="3">   &nbsp;&nbsp;			
          </td>
        </tr>
        <tr>
          <td>
            <label  for="outdate">접수일</label>
          </td>
          <td>
		     <input    type="date" id="outdate" name="outdate" value="<?=$outdate?>" >
			 
          </td>
        </tr>
        <tr>
          <td>
            <label for="requestdate">납기(필요일)  </label>
          </td>
          <td>             
				<input    type="date" id="requestdate" name="requestdate" value="<?=$requestdate?>"  > &nbsp; 
          </td>
        </tr>
        <tr>
          <td>
            <label for="indate">완료일</label>
          </td>
          <td>
            <input    type="date" id="indate" name="indate" value="<?=$indate?>"  >&nbsp;      			
				  <button  type="button"  class="btn btn-outline-dark btn-sm"  onclick="deldate();" > 일자 초기화 </button> &nbsp; 
          </td>
        </tr>
        <tr>
          <td>
            <label for="outworkplace">현장명</label>
          </td>
          <td>
            
			<input type="text"  id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$outworkplace?>" size="50" placeholder="현장명" autocomplete="off"> 	 &nbsp;
			<button type="button" id="searchplace" class="btn btn-dark  btn-sm" onclick="exe_search();" > 검색 </button> 	 	 
	 
			 <div id="displaysearch" style="display:none"> 	 
			 </div>
          </td>
        </tr>
        <tr>
          <td>
            <label for="model">모델</label>
          </td>
          <td>
            <input type="text" name="model" value="<?=$model?>" size="20" placeholder="모델명" />	 &nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="2" class="text-danger">
             [주의] 미래기업 구매 자재는 '사급자재'가 아님. 업체 제공 자재만 '사급'으로 구분.
          </td>
        </tr>
        <tr>
          <td>
            <label for="company">사급여부</label>
          </td>
          <td>				
			<select name="company" id="company" >
			   <?php		 
					for($i=0;$i<count($suply_company_arr);$i++) {
						 if(trim($company) == $suply_company_arr[$i])
									print "<option selected value='" . $suply_company_arr[$i] . "'> " . $suply_company_arr[$i] .   "</option>";
							 else   
									print "<option value='" . $suply_company_arr[$i] . "'> " . $suply_company_arr[$i] .   "</option>";
					} 		   
					?>	  
			</select> 		
			&nbsp; (미래기업) or (사급업체명) &nbsp;	
			<button  type="button" id="registcompanyBtn"  class="btn btn-outline-primary btn-sm"  > 사급업체 관리 </button> &nbsp;		
			
          </td>
        </tr>
        <tr>
          <td>
            <label for="company">공급(제조사)</label>
          </td>
          <td>				
			 <select name="supplier" id="supplier" style="font-size:16px;" >
           <?php	
			   for($i=0;$i<count($supplier_arr);$i++) {
					 if(trim($supplier) == $supplier_arr[$i])
								print "<option selected value='" . $supplier_arr[$i] . "'> " . $supplier_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $supplier_arr[$i] . "'> " . $supplier_arr[$i] .   "</option>";
			   } 		   
					?>	  
			</select> 		
			&nbsp; 
			<button  type="button" id="registsupplierBtn"  class="btn btn-outline-primary btn-sm"  > 공급처 관리 </button> &nbsp;				
			
			<button  type="button" id="registsteelitem"  class="btn btn-outline-primary btn-sm"  > 원자재 종류 관리 </button> &nbsp;
			<button  type="button" id="registspecBtn"  class="btn btn-outline-primary btn-sm"  > 규격 관리 </button> 
			
          </td>
        </tr>		
      </table>
		</div>
		</div>
    </div>
  
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-5" >';
	  else
		  echo '<div class="col-lg-12" >';
	  ?>
	  <div class="card" >
		<div class="card-body mt-2 mb-2" >
      <table class="table table-bordered">
        <tr>
          <td>
            <label for="item">종류</label>
          </td>
          <td>
			<select name="item" id="item"  >
			   <?php
					for ($i = 0; $i < count($steelitem_arr); $i++) {
						$currentItem = trim($steelitem_arr[$i]); // 공백 제거

						if (trim($item) == $currentItem) {
							print "<option selected value='" . $currentItem . "'> " . $currentItem . "</option>";
						} else {
							print "<option value='" . $currentItem . "'> " . $currentItem . "</option>";
						}
					}		   
					?>	  
			</select> 
          </td>
        </tr>
        <tr>
          <td>
            <label for="spec">규격</label>
          </td>
          <td>
           <select name="spec" id="spec" >
           <?php		 

		   for($i=0;$i<$spec_counter;$i++) {
			       if(trim($spec) == $spec_arr[$i])
					       print "<option selected value='" . $spec_arr[$i] . "'> " . $spec_arr[$i] .   "</option>";
					   else
							print "<option value='" . $spec_arr[$i] . "'> " . $spec_arr[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select>
            			 
				   &nbsp; <button  type="button" id="searchStockBtn"  class="btn btn-outline-dark btn-sm" > <ion-icon name="search"></ion-icon>  </button> &nbsp; 
				  
				 재고량 &nbsp; 
				 <input disabled type="text" name="stock" id="stock" value="<?=$stock?>" size="2"  /> 
          </td>
        </tr>
        <tr>
          <td colspan="2">
			<button type="button" class="btn btn-outline-success  btn-sm" onclick="size42150_click();searchStock();">1.2t 4'*2150</button>&nbsp;
			<button type="button" class="btn btn-outline-success  btn-sm" onclick="size4_8_click();searchStock();"> 1.2t 4'*8'</button>&nbsp;
			<button type="button"  class="btn btn-outline-success  btn-sm"  onclick="size4_2600_click();searchStock();">1.2t 4'*2600 </button>&nbsp;
			<button type="button" class="btn btn-outline-success  btn-sm" onclick="size4_2700_click();searchStock();">1.2t 4'*2700  </button>&nbsp;
			<button type="button" class="btn btn-outline-success  btn-sm" onclick="size4_3000_click();searchStock();">1.2t 4'*3000  </button>&nbsp;
			<button type="button"  class="btn btn-outline-success  btn-sm" onclick="size4_3200_click();searchStock();"> 1.2t 4'*3200</button>&nbsp;
			<button type="button"  class="btn btn-outline-success  btn-sm" onclick="size4_4000_click();searchStock();"> 1.2t 4'*4000</button>&nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="2">
				<button  type="button" id="searchSimilarStockBtn"  class="btn btn-outline-primary btn-sm" > 유사 재고 검색 </button> &nbsp; 
				<button  type="button" id="calTonBtn"  class="btn btn-outline-danger btn-sm" > 종류/규격 선택 후 톤 계산기(영무주임 사용) </button> &nbsp; 
          </td>
        </tr>
        <tr>
          <td>
            <label for="steelnum">수량</label>
          </td>
          <td>
            <input type="text" class="form-control" id="steelnum" name="steelnum" value="<?=$steelnum?>" size="5" placeholder="수량" autocomplete="off">
          </td>
        </tr>
		<tr>
		  <td>
			<label for="suppliercost">공급가액(경리부)</label>
		  </td>
		  <td>
			<input type="text" class="form-control" id="suppliercost" name="suppliercost" value="<?=$suppliercost?>" size="5" placeholder="거래명세표 수령 후 경리부 입력" autocomplete="off" oninput="formatInput(this)">
		  </td>
		</tr>		
        <tr>
          <td>
            <label for="comment">비고</label>
          </td>
          <td>
            <textarea class="form-control" rows="4" id="comment" name="comment" placeholder="기타사항 입력"><?=$comment?></textarea>
          </td>
        </tr>
      </table>
	  
    </div>
    </div>
    </div>
  </div>	
	
	     
		
	</form>
	
  <form id=Form1 name="Form1">
    <input type=hidden id="steelitem" name="steelitem" >
    <input type=hidden id="steelspec" name="steelspec" >
    <input type=hidden id="steeltake" name="steeltake" >
  </form>  	
	
  </div>
 </div>

<script>
function formatInput(input) {
    let value = input.value;
    value = value.replace(/,/g, ""); // Remove all existing commas
    value = value.replace(/[^\d]/g, ""); // Remove all non-digit characters
    input.value = numberWithCommas(value); // Add commas and update the value
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


//toggle 이벤트로 기능 부여 버튼글씨도 변환 펼치기 닫기
$(document).ready(function(){
	
	// 원자재현황 클릭시
$("#rawmaterialBtn").click(function(){ 
        
	 popupCenter('../steel/rawmaterial.php?menu=no'  , '원자재현황보기', 1050, 950);	
});


$("#saveBtn").click(function(){ 
    // 필요한 값 설정
    $('#steelitem').val($('#steel_item').val());
    $('#steelspec').val($('#spec').val());
    $('#steeltake').val($('#company').val());

    // 조건 확인
    if($("#outworkplace").val() === '' || $("#steelnum").val()  === '' ) {
        showWarningModal();
    } else {
        saveData();
    }
});

	function showWarningModal() {
		Swal.fire({                                    
			title: '등록 오류 알림',
			text: '현장명, 수량은 필수입력 요소입니다.',
			icon: 'warning',
			// ... 기타 설정 ...
		}).then(result => {
			if (result.isConfirmed) { 
				return; // 사용자가 확인 버튼을 누르면 아무것도 하지 않고 종료
			} else {
				saveData(); // 그렇지 않으면 데이터 저장 함수 실행
			}               
		});
	}

	function saveData() {
		document.getElementById('board_form').submit();   
	}	

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});			
     // 톤계산기 띄우기
	 $("#calTonBtn").click(function(){   	 
		  var a = $('#item').val();
		  var b = $('#spec').val();		  
        href = 'calTon.php?item=' + a + '&spec=' + b;		
		popupCenter(href, '톤/수량계산기', 1000, 650);
	 });		
     // 원자재 종류 관리 등록수정삭제 
	 $("#registsteelitem").click(function(){
        href = '../standard_material/list.php';				         
		popupCenter(href, '원자재 종류 관리', 600, 700);
	 });		
     // 규격 등록 수정 삭제 
	 $("#registspecBtn").click(function(){   	 
        href = '../standard/list.php';		
		popupCenter(href, '규격(spec) 관리', 600, 700);
	 });		
	 
     // 업체등록 수정 삭제 
	 $("#registcompanyBtn").click(function(){   
        href = '../standard_outsourcing/list.php';				
		popupCenter(href, '납품회사 등록', 600, 700);
	 });	
	 
     // 공급처 수정 삭제 
	 $("#registsupplierBtn").click(function(){   
        // 업체 숫자를 넘겨줘서 수정시 반환 받는다.		
        href = '../standard_supplier/list.php';				
		popupCenter(href, '공급처 등록', 600, 700);
	 });		
		
     
	 $("#searchSimilarStockBtn").click(function(){   
	    searchSimilarStock();
      }); 
	  
	 $("#searchStockBtn").click(function(){   
	    searchStock();
      });
	  
     // 자제 종류를 누르면 재고 나오게 만듬
     $("#item").bind( "change", function() {		
		 searchSimilarStock();
		 });	
		 
	 // 자제사이즈를 누르면 수량 나오게 만듬
     $("#spec").bind( "change", function() {		
		 searchStock();
		 });	

      btn = $('#btn'); //버튼 아이디 변수 선언

      layer = $('#layer'); //레이어 아이디 변수 선언

      btn.click(function(){
         layer.toggle(
           function(){			   	
			   layer.addClass('hide')			   
		   }, //한 번 더 클릭하면 hide클래스가 숨기기
		   function(){
			   layer.addClass('show')		   
		   } //클릭하면 show클래스 적용되서 보이기
          );
       if($(this).html() == '원자재현황 닫기' ) {
			  $(this).html('원자재현황 펼치기');
			}
			else {
			  $(this).html('원자재현황 닫기');
			}    
		});			
});			


// 
function searchSimilarStock(){
	
	   // 한글,소문자대문자영어,숫자만 읽는 정규식
	   // const regex = /^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|]+$/;
	   // 영어와 숫자만 읽는 정규식	   
	   
	   var arr1 = <?php echo json_encode($sum_title);?> ;
	   var arr2 = <?php echo json_encode($sum);?> ;	   
	   var arr3 = <?php echo $sumcount; ?> ; 
	   var arr4 = <?php echo json_encode($company_arr);?> ;
	   
	   console.log(arr3);
	   
		  var a = $('#item').val();
		  var b = $('#spec').val();
		  var c = $('#company').val();
		  
		console.log(a);
		console.log(b);
		console.log(arr1);
		console.log(arr2);
		console.log(arr3);
		console.log(arr4);
		
		let tmp = '';
		let temptext='';
		// console.clear();
		for(i=0;i<arr1.length;i++)
		{
			temptext = arr1[i];		
			if(temptext.includes(a))
			{
			   
			   if(arr2[i] > 0)
			   {
			     tmp += arr1[i] + '     수량 ' + arr2[i] + "<br>";
			     console.log(temptext.includes(a));				
			   }
			}
		}
		  if(tmp=='')
		     {
                 tmp='자재 없음';
				 $('#stock').val(0);  
			 }
		  $('#alertmsg').html(tmp); 
		  
		  $('#myModal').modal('show'); 
}

function searchStock(){
	 // 한글,소문자대문자영어,숫자만 읽는 정규식
	   // const regex = /^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|]+$/;
	   // 영어와 숫자만 읽는 정규식	   
	   var arr1 = <?php echo json_encode($sum_title);?> ;
	   var arr2 = <?php echo json_encode($sum);?> ;	   
	   var arr3 = <?php echo $sumcount; ?> ; 
       var company;	   
	   
	   console.log('원자재 Full name '  + arr1);
	   console.log(arr3);
	   
		  var a = $('#item').val();
		  var b = $('#spec').val();
		  var c = $('#company').val();
		  
		var title = a + b + c;
		let tmp = '';
		let temptext='';
		for(i=0;i<arr1.length;i++)
		{
			temptext = arr1[i];			
			// console.log(temptext);
			// console.log(title);
			// temptext = temptext.replace("기타업체","");
			// temptext = temptext.replace("한산엘테크","");
			// temptext = temptext.replace("윤스틸","");
			// temptext = temptext.replace("신우","");
			// temptext = temptext.replace("한ST","");
			// temptext = temptext.replace("바세라","");
			// temptext = temptext.replace("엘리브","");			
			//temptext = temptext.replace(/^[a-z|A-Z|0-9]/gi,"");			
			// temptext = temptext.replace("기타업체","");
				if(temptext==title)
				{
				   console.clear();
				   if(arr2[i] > 0)
				   {
					 company = temptext.replace(a, '');
					 company = company.replace(b, '');
					 tmp += a + ' ' + b + ' ' + company + ' 수량 ' + arr2[i] + "<br>";
					 console.log(company);
					$('#stock').val(arr2[i]);  
				   }
				}
		}
		  if(tmp=='')
		     {
                 tmp='자재 없음';
				 $('#stock').val(0);  
			 }
		  $('#alertmsg').html(tmp); 
		  
		  $('#myModal').modal('show'); 
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

// 일자초기화
function deldate(){	
document.getElementById("requestdate").value  = "";  // 필요일자
document.getElementById("indate").value  = "";
var _today = new Date();   

// document.getElementById("orderday").value  = today;   
/*
let year = today.getFullYear(); // 년도
let month = today.getMonth();  // 월
let date = today.getDate();  // 날짜
let day = today.getDay(); 
printday = year + "-" + month + "-" + day;  */

printday=_today.format('yyyy-MM-dd');   
document.getElementById("outdate").value  = printday;
$("input[name='which']:radio[value='1']").prop("checked", true);
}  
function exe_search()
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
} 

// _today.format   사용하려면 아래 내용이 함께 포함되어야 합니다.

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


// 새로운 사급업체가 추가된 것을 update함
function updateOptions(item, newValue) {
	var select = document.getElementById(item);

	var option = document.createElement("option");
	option.value = newValue;
	option.text = newValue;

	select.add(option);
	select.value = newValue; // 선택된 옵션을 새 값으로 설정
}

</script> 
	</body>
 </html>
