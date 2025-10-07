<?php\nrequire_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';   

 if(!isset($_SESSION["level"]) || $level>=5) {
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");
         exit;
   }   
	
$titlemsg	= '원자재(철판) 발주';	
?>
  
<?php include getDocumentRoot() . '/load_header.php' ; ?>

<title> <?=$titlemsg?> </title>

</head>
   
<style>

.show {display:block} /*보여주기*/

.hide {display:none} /*숨기기*/

th, td {
    vertical-align: middle !important;
}
</style>
 
<body>
   
<?php include getDocumentRoot() . '/common/modal.php'; ?>


<?php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();   
   
 include "../load_company.php";
 $companycount = count($suply_company_arr);   
 // var_dump($suply_company_arr);
 // 납품업체 숫자 넘겨줌 
 
include '_request.php';
  
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

 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	

if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
 $find=$_REQUEST["find"]; 

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
		   
$sql="select * from mirae8440.steelsource order by sortorder asc, item asc, spec asc"; 	// 정렬순서 정함.				

$sum_title=array(); 
$sum= array();
$company_arr = array();

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

$steelsource_item = array_values(array_filter($steelsource_item, 'strlen'));
$steelsource_item = array_values(array_unique($steelsource_item));
sort($steelsource_item);
$sumcount = count($steelsource_item);

 // 전체합계(입고/출고)를 산출하는 부분 
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
   $counter=0;
   $steelitem_arr=array();

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
 			  array_push($steelitem_arr,trim($row["item"]));			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    
 array_push($steelitem_arr,'304 Mirror 1.2T');
$item_counter=count($steelitem_arr);
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
      $sql = "select * from mirae8440.eworks where num = ?  ";
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
	$steel_item=null;			  
	$spec=null;
	$steelnum=null;			  
	$company="";
	$supplier="";
	$request_comment=null;
	$inventory=null;
	$which="1";	 			   // 요청 		  
  } 
  
  if ($mode=="copy"){
		try{
		  $sql = "select * from mirae8440.eworks where num = ?  ";
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
		 
		 $update_log='';  // 로그기록 초기화	

		$outdate = date("Y-m-d",time());
		
		// 사업업체가 있는 경우는 날짜만 현재일로 수정하고 나머지는 수정하지 않는다.
		if(!empty($company))
		{
			$which='3';  // 입고완료로 수정			
			$indate=$outdate; // 입고일자 초기화
			$requestdate=$outdate; // 필요일자 초기화
			$suppliercost=''; // 공금가액 초기화
			$steelnum=''; // 수량
			$inventory=''; // 이관 초기화				
			
		}
		else
		{
			$which='1';  // 요청으로 수정
			$indate=''; // 입고일자 초기화
			$requestdate=''; // 필요일자 초기화
			$suppliercost=''; // 공금가액 초기화
			$steelnum=''; // 수량 
			$inventory=''; // 이관 초기화	
		}

     $titlemsg	= '(데이터 복사) 원자재(철판) 발주';			
	 $num='';	 
	 $id = $num;  
	 $parentid = $num;   				 
  }    

// print '$item';
// var_dump($item);
// var_dump($steelitem_arr);

?>   
 
<form id="board_form" name="board_form" method="post"  onkeydown="return captureReturnKey(event)"  >	    
	<!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >		
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >  <!-- 신규데이터 작성시 parentid key값으로 사용 -->	
	<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
	<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >	
	<input type="hidden" id="steelitem" name="steelitem" >
	<input type="hidden" id="steelspec" name="steelspec" >
	<input type="hidden" id="steeltake" name="steeltake" >	
  
<div class="container">	
	<div class="card mt-2 mb-3">
	<div class="card-body">
        
	<div class="d-flex mb-5 mt-5 justify-content-center align-items-center"> 	
		<h4> <?=$titlemsg?> </h4> 
	</div>	
	       
       <div class="row">
		   <div class="col-sm-9">		   
				<div class="d-flex  mb-1 justify-content-start  align-items-center"> 			   	
				   <button id="saveBtn" type="button" class="btn btn-dark  btn-sm me-2"  > <i class="bi bi-floppy"></i> 저장  </button> 
				   <?php 
					   if(intval($num)>0)
					   {
					?>			   
						<button id="backBtn" type="button" class="btn btn-dark  btn-sm me-2"  > <i class="bi bi-arrow-left"></i> 뒤로 이동  </button> 			
					<?php 
					   }
					?>			   			
				   <button  type="button" id="rawmaterialBtn"  class="btn btn-primary btn-sm me-2" > 원자재 현황 </button>
				</div> 			
		 </div> 	   
	
	   <div class="col-sm-3">	
				<div class="d-flex  mb-1 justify-content-end"> 	
				   <button class="btn btn-secondary btn-sm" onclick="self.close();"  > <i class="bi bi-x-lg"></i> 닫기 </button>&nbsp;					
				</div> 			
		 </div> 			
	 </div> 	
	
   	
  <div class="row mt-1 ">
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-7 " >';
	  else
		  echo '<div class="col-sm-12" >';
	  ?>
	 <div class="card" >
		<div class="card-body mt-2 "" >
	 
      <table class="table table-bordered">        
        <tr>
          <td class="text-center fw-bold " >
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
          <td class="text-center fw-bold " >
            <label class="text-danger" >재고이관</label>
          </td>
          <td>
			 <input type="text" id="inventory" name="inventory" class="form-control text-start text-danger" style="width:100px;" value="<?=$inventory?>" readonly placeholder="재고이관 표시" >
          </td>
        </tr>
        <tr>
          <td class="text-center fw-bold " >
            <label for="outdate">접수일</label>
          </td>
          <td>
		     <input type="date" id="outdate" name="outdate" class="form-control" style="width:100px;" value="<?=$outdate?>" >
			 
          </td>
        </tr>
        <tr>
          <td class="text-center fw-bold " >
            <label for="requestdate"> 납기(필요일)  </label>
          </td>
          <td>   
            <div class="d-flex align-items-center">		          
				<input type="date" id="requestdate" name="requestdate"  class="form-control" style="width:100px;" value="<?=$requestdate?>"  > 
			</div>
          </td>
        </tr>
        <tr>
          <td class="text-center fw-bold " >
            <label for="indate">완료일</label>
          </td>
          <td>
		      <div class="d-flex align-items-center">	
				<input type="date" id="indate" name="indate"  class="form-control me-4" style="width:100px;"  value="<?=$indate?>"  >&nbsp;      			
				  <button  type="button"  class="btn btn-secondary btn-sm"  onclick="deldate();" > 일자 초기화 </button> &nbsp; 
		    </div>
          </td>
        </tr>
        <tr>
          <td class="text-center fw-bold " >
            <label for="outworkplace">현장명</label>
          </td>
          <td>
              
				<div class="d-flex mb-1 mt-2 justify-content-start align-items-center">  		  
				
				<?php
						// 검색 선택 쟘 or 천장
				if($search_opt=='') $search_opt='1';
					 switch ($search_opt) {
						case   "1"             : $aryitem[0] = "checked" ; break;
						case   "2"             :$aryitem[1] =  "checked" ; break;
						default: break;
					}		
				   ?>				
				   				
				
					쟘(jamb)    &nbsp;     <input  type="radio" <?=$aryitem[0]?> name="search_opt" value="1"> &nbsp; &nbsp; 
					천장   &nbsp;       <input  type="radio"  <?=$aryitem[1]?>  name="search_opt" value="2"> &nbsp; &nbsp; 
				</div>	
				<div class="d-flex align-items-center">	
				<input type="text" id="outworkplace" name="outworkplace"  class="form-control" style="width:400px;"  onkeydown="JavaScript:Enter_Check();" value="<?=$outworkplace?>" placeholder="현장명" autocomplete="off"> 	 &nbsp;
				<button type="button" class="btn btn-dark btn-sm" onclick="JavaScript:Choice_search();">   <i class="bi bi-search"></i> </button> 
			  </div>
			  </div>
			  
				<div id="displaysearch" style="display:none"> 	 
				 </div>

          </td>
        </tr>
        <tr>
         <td class="text-center fw-bold " >
            <label for="model">모델</label>
          </td>
          <td>
			<div class="d-flex align-items-center">	
				<input type="text" id="model" name="model" value="<?=$model?>"  class="form-control" style="width:200px;"   placeholder="모델명" />	 &nbsp;
			</div>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="text-center fw-bold text-danger " > 
             [주의] 미래기업 구매 자재는 '사급자재'가 아님. 업체 제공 자재만 '사급'으로 구분.
          </td>
        </tr>
        <tr>
         <td class="text-center fw-bold " >
            <label for="company">사급업체</label>
          </td>
          <td>		
              <div class="d-flex align-items-center">			  
				<select name="company" id="company"  class="form-control me-1" style="width:150px;" >
				   <?php		 
						for($i=0;$i<count($suply_company_arr);$i++) {
							 if(trim($company) == $suply_company_arr[$i])
										print "<option selected value='" . $suply_company_arr[$i] . "'> " . $suply_company_arr[$i] .   "</option>";
								 else   
										print "<option value='" . $suply_company_arr[$i] . "'> " . $suply_company_arr[$i] .   "</option>";
						} 		   
						?>	  
				</select> 		
				<button  type="button" id="registcompanyBtn"  class="btn btn-outline-dark btn-sm"  > <i class="bi bi-gear"></i> </button> &nbsp;		
			</div>
          </td>
        </tr>
        <tr>
         <td class="text-center fw-bold " >
            <label for="supplier">공급(제조사)</label>
          </td>
          <td>		
            <div class="d-flex align-items-center">		  
				 <select name="supplier" id="supplier"  class="form-control me-1" style="width:150px;" >
			   <?php	
				   for($i=0;$i<count($supplier_arr);$i++) {
						 if(trim($supplier) == $supplier_arr[$i])
									print "<option selected value='" . $supplier_arr[$i] . "'> " . $supplier_arr[$i] .   "</option>";
							 else   
					   print "<option value='" . $supplier_arr[$i] . "'> " . $supplier_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select> 		
				<button  type="button" id="registsupplierBtn"  class="btn btn-outline-dark btn-sm"  > <i class="bi bi-gear"></i>  </button> 					

			</div>
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
         <td class="text-center fw-bold " style="width:150px;" >		          
            <label for="steel_item">종류</label>
          </td>
          <td>
		    <div class="d-flex align-items-center">
				<select name="steel_item" id="steel_item"  class="form-control me-1" style="width:70%;" >
				   <?php
						for ($i = 0; $i < count($steelitem_arr); $i++) {
							$currentItem = trim($steelitem_arr[$i]); // 공백 제거

							if (trim($steel_item) == $currentItem) {
								print "<option selected value='" . $currentItem . "'> " . $currentItem . "</option>";
							} else {
								print "<option value='" . $currentItem . "'> " . $currentItem . "</option>";
							}
						}		   
						?>	  
				</select> 
				<button  type="button" id="registsteelitem"  class="btn btn-outline-dark btn-sm"  > <i class="bi bi-gear"></i> </button> 
			</div>
			
          </td>
        </tr>
        <tr>
         <td class="text-center fw-bold " >
            <label for="spec">규격</label>
          </td>
          <td>
            <div class="d-flex align-items-center">			  
				   <select name="spec" id="spec"  class="form-control me-1" style="width:120px;" >
				   <?php		 

				   for($i=0;$i<$spec_counter;$i++) {
						   if(trim($spec) == $spec_arr[$i])
								   print "<option selected value='" . $spec_arr[$i] . "'> " . $spec_arr[$i] .   "</option>";
							   else
									print "<option value='" . $spec_arr[$i] . "'> " . $spec_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select>											
					<button  type="button" id="registspecBtn"  class="btn btn-outline-dark btn-sm me-1"  > <i class="bi bi-gear"></i> </button> 	 				    
					재고량 &nbsp; 
				 <input disabled type="text" name="stock" id="stock" value="<?=$stock?>"  class="form-control me-1" style="width:50px;"   /> 
				 <button  type="button" id="searchStockBtn"  class="btn btn-outline-dark btn-sm me-1" > <i class="bi bi-search"></i> </button>
			 </div>
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
         <td colspan="2" class="text-center fw-bold " >		          
				<button  type="button" id="searchSimilarStockBtn"  class="btn btn-outline-primary btn-sm" >  <ion-icon name="search-outline"></ion-icon> 유사 재고  </button> &nbsp; 
				<button  type="button" id="calTonBtn"  class="btn btn-outline-danger btn-sm" > 종류/규격 선택 후 톤 계산기(<span class="fw-bold badge bg-primary"> 샤집 </span> 사용) </button> &nbsp; 
          </td>
        </tr>
        <tr>
         <td class="text-center fw-bold " >
            <label for="steelnum">수량</label>
          </td>
          <td>
            <input type="text" class="form-control text-center" id="steelnum" name="steelnum" value="<?=$steelnum?>"  style="width:150px;"  placeholder="수량" autocomplete="off">
          </td>
        </tr>
		<tr>
         <td class="text-center fw-bold " >
			<label for="suppliercost">공급가액(경리부)</label>
		  </td>
		  <td>
			<input type="text" class="form-control text-center" id="suppliercost" name="suppliercost" value="<?=$suppliercost?>" style="width:150px;"   placeholder="명세표 경리부 입력" autocomplete="off" oninput="formatInput(this)">
		  </td>
		</tr>		
        <tr>
         <td class="text-center fw-bold " >
            <label for="request_comment">비고</label>
          </td>
          <td>
            <textarea class="form-control" rows="4" id="request_comment" name="request_comment" placeholder="기타사항 입력"><?=$request_comment?></textarea>
          </td>
        </tr>
      </table>
	  
    </div>
    </div>
    </div>
  </div>	
	
  </div>
 </div>
 </div>
 </div>
 </div>
		
</form>
<script>

if($("#backBtn").length>0)	
    document.getElementById('backBtn').addEventListener('click', function() {
        window.history.back();
    });

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
	
 // document.getElementById('outworkplace').reset(); 	
 
 $("#saveBtn").click(function(){ 
    // 필요한 값 설정
    $('#steelitem').val($('#steel_item').val());
    $('#steelspec').val($('#spec').val());
    $('#steeltake').val($('#company').val());    
	
    // 조건 확인
    if($("#outworkplace").val() === '' || $("#steelnum").val()  === ''  || $("#requestdate").val()  === ''   ) {
        showWarningModal();
    } else {
		
		Toastify({
			text: "변경사항 저장중...",
			duration: 2000,
			close:true,
			gravity:"top",
			position: "center",
			style: {
				background: "linear-gradient(to right, #00b09b, #96c93d)"
			},
		}).showToast();	
		setTimeout(function(){
		         saveData();
		}, 1000);
      
    }
});

	function showWarningModal() {
		Swal.fire({                                    
			title: '등록 오류 알림',
			text: '현장명, 납기일, 수량은 필수입력 요소입니다.',
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
	
		$.ajax({
			url: "update_steelsource.php",
			type: "post",        
			data: $("#board_form").serialize(),
			dataType:"json",
			success : function( data ){
				console.log( data);	
				saveData1();
				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			}                     
		}); // end of ajax
	}	

	function saveData1() {
		
		var num = $("#num").val();  
		
	   showMsgModal(2); // 파일저장중
		
		// 결재상신이 아닌경우 수정안됨     
		if(Number(num) < 1) 				
				$("#mode").val('insert');     			  			
			
		//  console.log($("#mode").val());    
		// 폼데이터 전송시 사용함 Get form         
		var form = $('#board_form')[0];  	    	
		var datasource = new FormData(form); 

		// console.log(data);
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}		 
		ajaxRequest = $.ajax({
			enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
			processData: false,    
			contentType: false,      
			cache: false,           
			timeout: 600000, 			
			url: "insert.php",
			type: "post",		
			data: datasource,			
			dataType: "json", 
			success : function(data){
				  // console.log('data :' , data);
				  // Swal.fire(
					  // '자료등록 완료',
					  // '데이터가 성공적으로 등록되었습니다.',
					  // 'success'
					// );
				setTimeout(function(){									
							if (window.opener && !window.opener.closed) {
								// 부모 창에 restorePageNumber 함수가 있는지 확인
								if (typeof window.opener.restorePageNumber === 'function') {
									window.opener.restorePageNumber(); // 함수가 있으면 실행
								}
							}							
				}, 1000);		
				
				setTimeout(function(){											
					hideMsgModal();								
					location.href = "view.php?num=" + data["num"];
				}, 1000);					
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
						} 			      		
		   });		
			
	}	
	

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});			
     // 톤계산기 띄우기
	 $("#calTonBtn").click(function(){   	 
		  var a = $('#steel_item').val();
		  var b = $('#spec').val();		  
        href = 'calTon.php?steel_item=' + a + '&spec=' + b;		
		popupCenter(href, '톤/수량계산기', 1000, 650);
	 });		
     // 원자재 종류 관리 등록수정삭제 
	 $("#registsteelitem").click(function(){
        href = '../standard_material/list.php?item=steel_item';				         
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
     $("#steel_item").bind( "change", function() {		
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
	   
		  var a = $('#steel_item').val();
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
	   
		  var a = $('#steel_item').val();
		  var b = $('#spec').val();
		  var c = $('#company').val();
		  
		var title = a + b + c;
		let tmp = '';
		let temptext='';
		
		console.log('a+b+c title ', title);
		
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

  
function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function Enter_Check(){
var tmp = $('input[name=search_opt]:checked').val();	
	
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13 && tmp== 1 )
      search_jamb();  // 잠 현장검색
	  
    if(event.keyCode == 13 && tmp== 2 )
      search_ceiling();  // 천장 현장 검색	      
}

function Choice_search() {
var tmp = $('input[name=search_opt]:checked').val();	
	if(tmp =='1' )
      search_jamb();  // 잠 현장검색	  
    if(tmp == '2' )
      search_ceiling();  // 천장 현장 검색	      
  
 // alert(tmp);
  }
  
// 일자초기화
function deldate(){	
document.getElementById("requestdate").value  = "";  // 필요일자
document.getElementById("indate").value  = "";
var _today = new Date();   

printday=_today.format('yyyy-MM-dd');   
document.getElementById("outdate").value  = printday;
$("input[name='which']:radio[value='1']").prop("checked", true);
}  

function search_jamb()
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

function search_ceiling()
{
	  var ua = window.navigator.userAgent;
      var postData; 	 
	  var text1= document.getElementById("outworkplace").value;
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(text1);
            } else {
                postData = text1;
            }
			
			
	  $("#displaycode").show();			

      $("#displaysearch").show();
      $("#displaysearch").load("../steel/search_ceiling.php?readonly=readonly&mode=search&search=" + postData);
} 


// 새로운 사급업체가 추가된 것을 update함
function updateOptions(steel_item, newValue) {
	var select = document.getElementById(steel_item);

	var option = document.createElement("option");
	option.value = newValue;
	option.text = newValue;

	select.add(option);
	select.value = newValue; // 선택된 옵션을 새 값으로 설정
}

$(document).ready(function() {
    // '사급여부' select 요소의 변경을 감지
    $('#company').change(function() {
        // 선택된 값이 ''이 아닌 경우
        if ($(this).val() !== '') {
            // '진행상태'를 '입고완료'로 설정 (3번째 라디오 버튼 체크)
            $('input[name=which][value="3"]').prop('checked', true);

            // '접수일'의 날짜를 가져와서 '납기(필요일)'과 '완료일'에 설정
            var outdateVal = $('#outdate').val();
            $('#requestdate').val(outdateVal);
            $('#indate').val(outdateVal);
        }
    });
});


</script> 
	</body>
 </html>
