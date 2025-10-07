 <?php
session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }   
   
   ?>
   
   <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
<title> 미래기업 원자재 입출고 자료복사 </title> 
 
 </head>
 
 <body>
   
  <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

<?php	
   
      
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

  
if(isset($_REQUEST["scale"])) 
 {
    $scale=$_REQUEST["scale"];  
 }
  else
  {
    $scale=10;	 
  }

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
  
   $sql="select * from mirae8440.steelsource "; 					

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
 			  array_push($steelsource_item, $row["item"]);
 			  $steelsource_spec[$counter]=$row["spec"];
 			  $company = $row["take"];
			  
			  
			  if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
					$company = '';
				}
			  
		      $steelsource_take[$counter] = $company;  
			  			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

// var_dump($steelsource_item);

array_push($steelsource_item," ");
$steelsource_item_yes = array_unique($steelsource_item);
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
 
$process="전체";  // 기본 전체로 정한다.   

	 $sql="select * from mirae8440.steelsource order by sortorder asc, item asc, spec asc"; 	// 정렬순서 정함.				

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
 			  $steelsource_item[$counter]=$row["item"];
 			  $steelsource_spec[$counter]=$row["spec"];
 			  $company = $row["take"];
			  
			  
			  if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
					$company = '';
				}
			  
		      $steelsource_take[$counter] = $company;   		  
	   $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum= array();

$sql="select * from mirae8440.steel order by outdate";
 
$tmpsum = 0; 

	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
			  $outdate=$row["outdate"];			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 		

				// 일반매입처리
				if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
					$company = '';
				}		  

			  $tmp=$item . $spec . $company;	        
		      $tmpsum +=(int)$steelnum;		// 입고숫자 더해주기 합계표								 
		           
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$sql="select * from mirae8440.steel order by " . $SettingDate;

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			  $outdate=$row["outdate"];			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
				if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
					$company = '';
				}					

			  $tmp=$item . $spec . $company;
	
        for($i=0;$i<=$counter;$i++) {  
	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i] . $steelsource_take[$i] ;	          
			  if($which=='1' and $tmp==$sum_title[$i])
				     $sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표		 
     
		           }
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

 // 전체합계(출고부분)를 처리하는 부분 
$sql="select * from mirae8440.steel order by outdate " ; 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  
			  $outdate=$row["outdate"];				  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	

				// 일반매입처리
				if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
					$company = '';
				}					
			  
			  $tmp= $item . $spec . $company; 	          
			  
        for($i=0;$i<$counter;$i++) {			 			  

	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i] . $steelsource_take[$i] ;			  
              $company_arr[$i] = $steelsource_take[$i] ;	
			  if($which=='2' and $tmp==$sum_title[$i])
				    $sum[$i]= $sum[$i] - (int)$steelnum;						
		           }	
				   
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

// var_dump( $sum);
      
// 철판 종류 불러오기
$sql="select * from mirae8440.steelitem"; 					

	 try{  

   $stmh = $pdo->query($sql);            
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $steelitem_arr=array();

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
 			  $steelitem_arr[$counter]=$row["item"];
			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    
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
	   
 			  $spec_arr[$counter]=$row["spec"];
			 
			  $counter++;
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    
   $spec_counter=count($spec_arr);
   sort($spec_arr);  // 오름차순으로 배열 정렬
  

 try{
      $sql = "select * from mirae8440.steel where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
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
   
$mode="";

// $mode를 이용해서 copy_data.php에서 기존 데이터를 사용한다.
	
?>
    
<div class="container">
   
   
	   <?php
    if($mode=="modify"){
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>" > 
  <?php  } else {
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>"> 
  <?php
	}
  ?>	   

	
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">현재 재고 수량 알림</h4>
        </div>
        <div class="modal-body">		
           <div class="row gx-4 gx-lg-4 align-items-center">		  
				   <br>
				   <br>
				   <div id="alertmsg" class="fs-3" > </div> <br>
				  <br>		  									
				  <br>		  									
				</div>
			</div>		  
        <div class="modal-footer">
          <button id="closeModalBtn" type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
		</div>
		</div>
      </div>     	

    <div class="card mt-2 mb-3">
       <div class="card-body">
 	    <div class="d-flex mb-1 mt-2 justify-content-center align-items-center fs-3">        
	      원자재 입출고 &nbsp;       &nbsp;       
		   <button type="button" id="saveBtn"  class="btn btn-primary btn-sm"> DATA 저장 </button>	&nbsp;       
			  <button type="button" id="gotoList"  class="btn btn-secondary btn-sm" onclick="location.href='list.php?mode=search&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>'" > 목록(List) </button>	&nbsp;       	   							
		</div>  
		
	<input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
    <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >	
	
	
   	
  <div class="row mt-3 mb-3">
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-6 " >';
	  else
		  echo '<div class="col-lg-12" >';
	  ?>
	 <div class="card" >
		<div class="card-body mt-2 mb-2" >
	 
      <table class="table table-bordered">        
        <tr>
          <td style="width:22%;">
            <label>구분</label>
          </td>
          <td>
			 <?php	 		  
					 $aryreg=array();
					 $aryitem=array();
					 if($which=='') $which='2';
					 switch ($which) {
						case   "1"             : $aryreg[0] = "checked" ; break;
						case   "2"             :$aryreg[1] =  "checked" ; break;
						default: break;
					}		 
						// 검색 선택 쟘 or 천장
					 if($search_opt=='') $search_opt='1';
					 switch ($search_opt) {
						case   "1"             : $aryitem[0] = "checked" ; break;
						case   "2"             :$aryitem[1] =  "checked" ; break;
						default: break;
					}		
				   ?>		  
					  
				 <span class="text-primary">  입고 </span> &nbsp;  <input  type="radio" <?=$aryreg[0]?> name=which value="1"> </span>  
				&nbsp; &nbsp;  <span class="text-danger">  출고         <input  type="radio" <?=$aryreg[1]?>  name=which value="2">  </span>  
          </td>
        </tr>
        <tr>
          <td>
            <label  for="indate">접수일</label>
          </td>
          <td>
		     <input    type="date" id="indate" name="indate" value="<?=$indate?>" >
			 
          </td>
        </tr>        
        <tr>
          <td>
            <label for="outdate">입출고일</label>
          </td>
          <td>
             <input type="date" id="outdate" name="outdate" value="<?=$outdate?>"  > &nbsp;&nbsp;
      
				
          </td>
        </tr> 
        <tr>
          <td>
            <label for="outworkplace">현장명</label>
          </td>
          <td>             
			쟘(jamb)    &nbsp;     <input  type="radio" <?=$aryitem[0]?> name=search_opt value="1"> &nbsp; &nbsp; 
			  천장   &nbsp;       <input  type="radio"  <?=$aryitem[1]?>  name=search_opt value="2"> &nbsp; &nbsp; 
			 <input type="text" id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?=$outworkplace?>" size="55" placeholder="현장명"> 	 &nbsp; 
			 <button type="button" class="btn btn-dark btn-sm" onclick="JavaScript:Choice_search();">  검색 </button> 
				<div id="displaycode" style="display:none"> 	 
				  &nbsp;&nbsp;  천장 Code   <input type="text" id="ceilingcode" name="ceilingcode" size=7 value="<?=$ceilingcode?>" >	  
			</div>	 
			<div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
			 <div id="displaysearch" style="display:none"> 	 
			 </div>	 	 
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
             [주의] 미래기업 구매 자재는 '사급자재'가 아님. 업체 제공 자재만 '사급'.
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
			&nbsp; (미래기업)or(사급업체)&nbsp;	
			<button  type="button" id="registcompanyBtn"  class="btn btn-outline-primary btn-sm"  > 업체관리 </button> &nbsp;		
			
          </td>
        </tr>
        <tr>
          <td>
            <label for="supplier">공급(제조사)</label>
          </td>
          <td>				
			 <input name="supplier" id="supplier"  value="<?=$supplier?>" size="8" placeholder="입고시 기록"  >	
				<button  type="button" id="registsteelitem"  class="btn btn-outline-primary btn-sm"  > 원자재 종류 </button> &nbsp;
				<button  type="button" id="registspecBtn"  class="btn btn-outline-primary btn-sm"  > 규격 </button> 
          </td>
        </tr>	
        <tr>
          <td>
            <label for="comment">샤링여부</label>
          </td>
          <td>
			<select name="method" id="method" >
			   <?php	
                    $method_arr = array('','샤링');
					for($i=0;$i<count($method_arr);$i++) {
						 if(trim($method) == $method_arr[$i])
									print "<option selected value='" . $method_arr[$i] . "'> " . $method_arr[$i] .   "</option>";
							 else   
									print "<option value='" . $method_arr[$i] . "'> " . $method_arr[$i] .   "</option>";
					} 		   
					?>	  
			</select> 	
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
        <tr>
          <td class=" align-middle text-center">
			 <span class="text-danger" > 불량 구분   </span> 
		 </td>					 
		 <td>		
				<div class="d-flex mb-2 mt-3 justify-content-center align-items-center">   	  			
					   <?php			 
							 $arybad=array();
							 if($bad_choice=="")
								   $bad_choice="해당없음";
							 switch ($bad_choice) {
								case   "해당없음"       : $arybad[0] =  "checked" ; break;
								case   "설계"           : $arybad[1] =  "checked" ; break;
								case   "레이져"     : $arybad[2] =  "checked" ; break;
								case   "V컷"           : $arybad[3] =  "checked" ; break;
								case   "절곡"           : $arybad[4] =  "checked" ; break;
								case   "운반중"           : $arybad[5] =  "checked" ; break;
								case   "소장"           : $arybad[6] =  "checked" ; break;
								case   "업체"           : $arybad[7] =  "checked" ; break;
								case   "기타"           : $arybad[8] =  "checked" ; break;
								default: break;
							}		 
						?>			
								
					   <input type="radio" <?=$arybad[0]?> name="bad_choice" value="해당없음"    >	해당없음  &nbsp;&nbsp;
					   <input type="radio" <?=$arybad[1]?> name="bad_choice" value="설계"    >	설계  &nbsp;&nbsp;
					   <input type="radio" <?=$arybad[2]?> name="bad_choice" value="레이져"   > 레이져 &nbsp;&nbsp;
					   <input type="radio" <?=$arybad[3]?> name="bad_choice" value="V컷"    >  V컷 &nbsp;&nbsp;
					</div>  
					<div class="d-flex mb-3 mt-1 justify-content-center align-items-center">  
					   <input type="radio" <?=$arybad[4]?> name="bad_choice" value="절곡"    > 절곡 &nbsp;&nbsp;
					   <input type="radio" <?=$arybad[5]?> name="bad_choice" value="운반중"   > 운반중 &nbsp;&nbsp;
					   <input type="radio" <?=$arybad[6]?> name="bad_choice" value="소장"    > 소장 &nbsp;&nbsp;				  
					   <input type="radio" <?=$arybad[7]?> name="bad_choice" value="업체"    > 업체 &nbsp;&nbsp;
					   <input type="radio" <?=$arybad[8]?> name="bad_choice" value="기타"    > 기타 &nbsp;&nbsp;
								
					</div>    
				  
          </td>
        </tr>

		
      </table>

		</div>
		</div>
    </div>
  
  <?php if($chkMobile===false)  
       echo '<div class="col-sm-6" >';
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
            			 
				   &nbsp; <button  type="button" id="searchStockBtn"  class="btn btn-outline-dark btn-sm" > 재고량 검색 </button> &nbsp; 
				  
				 재고량 &nbsp; 
				 <input disabled type="text" name="stock" id="stock" value="<?=$stock?>" size="2"  /> 
          </td>
        </tr>
        <tr>
          <td>
            <label for="steelnum">수량</label>
          </td>
          <td>
            <input type="text" id="steelnum" name="steelnum" value="<?=$steelnum?>" size="3" placeholder="수량" autocomplete="off">
			&nbsp;    <button type="button" id="saveBtn2"  class="btn btn-primary btn-sm"> DATA 저장 </button>	&nbsp;    
          </td>
        </tr>		
		
        <tr>
          <td colspan="2">
				  <div class="p-1 m-1" >
				 <button type="button" class="btn btn-primary btn-sm" onclick="HL304_click();" > 304 HL </button>	&nbsp;   
				 <button type="button" class="btn btn-success btn-sm" onclick="MR304_click();" > 304 MR </button>	&nbsp;    			 
				 <button type="button" class="btn btn-secondary btn-sm" onclick="VB_click();" > VB </button>	&nbsp;    
				 <button type="button" class="btn btn-warning btn-sm" onclick="EGI_click();" > EGI </button>	&nbsp;    
				 <button type="button" class="btn btn-danger btn-sm" onclick="PO_click();" > PO </button>	&nbsp;    
				 <button type="button" class="btn btn-dark btn-sm" onclick="CR_click();" > CR </button>	&nbsp;  
				 <button type="button" class="btn btn-success btn-sm" onclick="MR201_click();" > 201 2B MR </button>	&nbsp;  
				   </div>	
				  <div class="p-1 m-1" >
				  <span class="text-success "> <strong> 쟘 1.2T &nbsp; </strong> </span>	
					<button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_1950_click();"> 1000x1950  </button> &nbsp;
				    <button type="button" class="btn btn-outline-success btn-sm" onclick="size1000_2150_click();"> 1000x2150  </button> &nbsp;				   
					<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size42150_click();">  4'X2150 </button> &nbsp;
					<button type="button"  class="btn btn-outline-success btn-sm"   onclick="size1000_8_click();"> 1000x8' </button> &nbsp; 
				  </div>	
				  <div class="p-1 m-1" >
				 &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <button type="button"   class="btn btn-outline-success btn-sm"  onclick="size4_8_click();"> 4'x8' </button> &nbsp;
				  <button type="button"  class="btn btn-outline-success btn-sm"  onclick="size1000_2700_click();"> 1000x2700 </button> &nbsp;
				   <button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_2700_click();"> 4'x2700 </button> &nbsp;
				   <button type="button" class="btn btn-outline-success btn-sm"  onclick="size4_3200_click();"> 4'x3200  </button> &nbsp;
				   <button type="button" class="btn btn-outline-success btn-sm"   onclick="size4_4000_click();"> 4'x4000 </button> &nbsp;	   			  
				  </div>			  
				  <div class="p-1 m-1" >
				  <span class="text-success "> <strong> 신규쟘 1.5T(HL) &nbsp; </strong> </span>	
				   <button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_2150_click();"> 4'x2150 </button> &nbsp;				
				   <button type="button" class="btn btn-outline-success btn-sm" onclick="size15_4_8_click();"> 4'x8' </button> &nbsp;								  
				  <span class="text-success "> <strong> 신규쟘 2.0T(EGI) &nbsp; </strong> </span>	
				   <button type="button" class="btn btn-outline-success btn-sm" onclick="size20_4_8_click();"> 4'x8'  </button> &nbsp;
					
				  </div>			  

				<div class=" p-1 m-1" >	   
				   천장 1.2T(CR)  </button> &nbsp; 
				  <button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1680_click();"> 4'x1680 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-danger btn-sm" onclick="size12_4_1950_click();"> 4'x1950 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-danger btn-sm"  onclick="size12_4_8_click();"> 4'x8' </button> &nbsp;
				  </div>			  
				  <div class=" p-1 m-1" >			  				   
				  천장 1.6T(CR)   &nbsp; 	  
				  <button type="button"  class="btn btn-outline-primary btn-sm" onclick="size16_4_1680_click();"> 4'x1680 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_1950_click();"> 4'x1950 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-primary btn-sm"  onclick="size16_4_8_click();"> 4'x8' </button> &nbsp;		   		   
				  </div>
				  <div class=" p-1 m-1" >	
				   천장 2.3T(PO)  &nbsp; 	  
				   <button type="button" class="btn btn-outline-secondary btn-sm" onclick="size23_4_1680_click();"> 4'x1680 </button> &nbsp;
				   <button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_1950_click();"> 4'x1950 </button> &nbsp;
				   <button type="button" class="btn btn-outline-secondary btn-sm"  onclick="size23_4_8_click();"> 4'x8'  </button> &nbsp;					  
				   천장 3.2T(PO)  &nbsp; 	  
				   <button type="button" class="btn btn-outline-secondary btn-sm" onclick="size32_4_1680_click();"> 4'x1680 </button> &nbsp;
				   
				  </div>
			   
          </td>
        </tr>
        <tr>
          <td colspan="2">
           	      
		  <div class="row mb-1 mt-2 justify-content-center align-items-center">  				 		
		   <div class="input-group p-1">
				<span class="input-group-text text-primary">
	      	       잔재1
		        </span>
					<span class="input-group-text">
					   세로(폭) x 가로(길이)  :&nbsp;
					     <input type="number" id="used_width_1" name="used_width_1"   value="<?=$used_width_1?>"  min="0" max="9999" >&nbsp;  x &nbsp; 
					     <input type="number" id="used_length_1" name="used_length_1"   value="<?=$used_length_1?>"  min="0" max="9999" >
						 &nbsp;&nbsp;  수량&nbsp;&nbsp; 
						  <input type="number" id="used_num_1" name="used_num_1"   value="<?=$used_num_1?>"  min="0" max="99" >						 
					</span>
		  </div>
		   <div class="input-group p-1">
				<span class="input-group-text text-primary">
	      	       잔재2
		        </span>
					<span class="input-group-text">
					   세로(폭) x 가로(길이)  :&nbsp;
					     <input type="number" id="used_width_2" name="used_width_2"   value="<?=$used_width_2?>"  min="0" max="9999" >&nbsp;  x &nbsp; 
					     <input type="number" id="used_length_2" name="used_length_2"   value="<?=$used_length_2?>"  min="0" max="9999" >
						 &nbsp;&nbsp;  수량&nbsp;&nbsp; 
						  <input type="number" id="used_num_2" name="used_num_2"   value="<?=$used_num_2?>"  min="0" max="99" >						 
					</span>
		  </div>
		   <div class="input-group p-1">
				<span class="input-group-text text-primary">
	      	       잔재3
		        </span>
					<span class="input-group-text">
					   세로(폭) x 가로(길이)  :&nbsp;
					     <input type="number" id="used_width_3" name="used_width_3"   value="<?=$used_width_3?>"  min="0" max="9999" >&nbsp;  x &nbsp; 
					     <input type="number" id="used_length_3" name="used_length_3"   value="<?=$used_length_3?>"  min="0" max="9999" >
						 &nbsp;&nbsp;  수량&nbsp;&nbsp; 
						  <input type="number" id="used_num_3" name="used_num_3"   value="<?=$used_num_3?>"  min="0" max="99" >						 
					</span>
		  </div>
		   <div class="input-group p-1">
				<span class="input-group-text text-primary">
	      	       잔재4
		        </span>
					<span class="input-group-text">
					   세로(폭) x 가로(길이)  :&nbsp;
					     <input type="number" id="used_width_4" name="used_width_4"   value="<?=$used_width_4?>"  min="0" max="9999" >&nbsp;  x &nbsp; 
					     <input type="number" id="used_length_4" name="used_length_4"   value="<?=$used_length_4?>"  min="0" max="9999" >
						 &nbsp;&nbsp;  수량&nbsp;&nbsp; 
						  <input type="number" id="used_num_4" name="used_num_4"   value="<?=$used_num_4?>"  min="0" max="99" >						 
					</span>
		  </div>
		   <div class="input-group p-1">
				<span class="input-group-text text-primary">
	      	       잔재5
		        </span>
					<span class="input-group-text">
					   세로(폭) x 가로(길이)  :&nbsp;
					     <input type="number" id="used_width_5" name="used_width_5"   value="<?=$used_width_5?>"  min="0" max="9999" >&nbsp;  x &nbsp; 
					     <input type="number" id="used_length_5" name="used_length_5"   value="<?=$used_length_5?>"  min="0" max="9999" >
						 &nbsp;&nbsp;  수량&nbsp;&nbsp; 
						  <input type="number" id="used_num_5" name="used_num_5"   value="<?=$used_num_5?>"  min="0" max="99" >						 
					</span>
		  </div>
		  
		</div>				 

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

<script>
$(document).ready(function(){
	
    function buttonClicked(event) {
      // 버튼 클릭 시 실행할 코드 작성
       searchStock();
    }

    // 모든 버튼 요소를 가져옴
    var buttons = document.querySelectorAll("button");

    // 각 버튼에 클릭 이벤트 리스너를 추가
    buttons.forEach(function(button) {
      button.addEventListener("click", buttonClicked);
    });
	
		
	$("#saveBtn").click(function(){   	
 // 원자재 steelsource에 없는 항목이면 생성해 주는 부분
   // data저장을 위한 ajax처리구문
   $('#steelitem').val($('#item').val());
   $('#steelspec').val($('#spec').val());
   $('#steeltake').val($('#company').val());
   
   console.log($("#Form1").serialize());
   
	$.ajax({
		url: "../request/update_steelsource.php",  // request의 함수 사용하기
		type: "post",		
		data: $("#Form1").serialize(),
		dataType:"json",
		success : function( data ){
			console.log( data);			
			   // grid 배열 form에 전달하기						    						    
			  $("#board_form").submit(); 									 
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });	// end of ajax
	
	 });	 
	
	$("#saveBtn2").click(function(){   					    						    
	  $("#saveBtn").click(); 				 
	 });
		 
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
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
			
		
     
	 $("#searchSimilarStockBtn").click(function(){   
	    searchSimilarStock();
      }); 
	  
	 $("#searchStockBtn").click(function(){   
	    searchStock();
      });
	  
     // // 자제 종류를 누르면 재고 나오게 만듬
     // $("#item").bind( "change", function() {		
		 // searchSimilarStock();
		 // });	
		 
	 // 자제사이즈를 누르면 수량 나오게 만듬
     $("#spec").bind( "change", function() {		
		 searchStock();
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
	  	   
		  var a = $('#item').val();
		  var b = $('#spec').val();
		  var c = $('#company').val();
		  
		console.log(a);
		
		let tmp = '';
		let temptext='';		
		for(i=0;i<arr3;i++)
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
	   var arr4 = <?php echo json_encode($company_arr);?> ;
	   
	  // console.log('원자재 Full name '  + arr1);
	   console.log(arr3);
	   
		  var a = $('#item').val();
		  var b = $('#spec').val();
		  var c = $('#company').val();
		  
		var title = a + b + c;
		let tmp = '';
		let temptext='';
		 console.log('arr1.length '  + arr1.length);
			
		// 제거할 단어 목록
		var wordsToRemove = ['미래기업', '윤스틸', '현진스텐'];		 
		 
		 
		for(i=0;i<arr1.length;i++)
		{
			temptext = arr1[i];			


			// 제거할 단어를 제외한 문자열 생성 함수
			function removeWordsFromString(text, wordsToRemove) {
			  var result = text;
			  for (var i = 0; i < wordsToRemove.length; i++) {
				var wordToRemove = wordsToRemove[i];
				result = result.replace(wordToRemove, '');
			  }
			  return result.trim();
			}

			// 문자열에서 단어 제거 후 비교
			var removedTitle = removeWordsFromString(title, wordsToRemove);
			var removedTempText = removeWordsFromString(temptext, wordsToRemove);

			// 수정된 문자열 비교
			if (removedTempText === removedTitle) {
					
				console.log('removedTempText '  + removedTempText);
			    console.log('removedTitle '  + removedTitle);
			    console.log('sum '  + arr2[i]);
					
				   if(arr2[i] > 0)
				   {
					 tmp += a + ' ' + b + ' ' + arr4[i] + ' 수량 ' + arr2[i] + "<br>";
					 console.log(arr4[i]);
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
		  
		  // steel에는 모달창 뜨지 않게 하기
		  // steel에는 모달창 뜨지 않게 하기
		  // $('#myModal').modal('show'); 
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
  
function Enter_CheckTel(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_searchTel();  // 실행할 이벤트
    }
}

function deldate(){	

document.getElementById("indate").value  = "";
var _today = new Date();   


printday=_today.format('yyyy-MM-dd');   
document.getElementById("outdate").value  = printday;
$("input[name='which']:radio[value='1']").attr("checked", true) ;

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
      $("#displaysearch").load("./search_ceiling.php?mode=search&search=" + postData);
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
