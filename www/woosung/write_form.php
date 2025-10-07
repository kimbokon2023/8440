<?php 

// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'ini.php';    

require_once("../lib/mydb.php");
$pdo = db_connect();

$today=date("Y-m-d");  // 현재일 저장   


// 삭제시 하위발주가 있으면 삭제안되게 하는 루틴 만들기
// parent_id를 가지고 있는 것이 있는지 검색해서 변수에 저장한다.

$is_child = 0;

try{
	$sql = "select * from mirae8440." . $tablename . " where parent_id=? ";
	$stmh = $pdo->prepare($sql);
	// 부모 정보를 가져온다.
	$stmh->bindValue(1, $id, PDO::PARAM_STR);      
	$stmh->execute();            
	$row = $stmh->fetch(PDO::FETCH_ASSOC); 		
	// 자식node가 있는지 검색한다.
	if($row["parent_id"]==$id)
		$is_child++;
}catch (PDOException $Exception) {
print "오류: ".$Exception->getMessage();
}
// print $is_child;


// 시공사진 처리부분 
if($id!=null && $id!=0)
{	
	 // 사진전 사진 이미 있는 것 불러오기 
	$picData=array(); 
	$item = 'before';

	$sql=" select * from mirae8440." . $table_picuploads . " where tablename ='$tablename' and item ='$item' and parentid ='$id' ";	

	 try{  
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				array_push($picData, $row["picname"]);			
			}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
	$picid=count($picData);

	// 시공 중간 사진 이미 있는 것 불러오기 
	$MidpicData=array(); 
	$item = 'mid';

	$sql=" select * from mirae8440." . $table_picuploads . " where tablename ='$tablename' and item ='$item' and parentid ='$id' ";	

	 try{  
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				array_push($MidpicData, $row["picname"]);			
			}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
	$Midpicid=count($MidpicData);
	  
	// 시공 후 사진 이미 있는 것 불러오기 
	$AfterpicData=array(); 
	$item = 'after';

	$sql=" select * from mirae8440." . $table_picuploads . " where tablename ='$tablename' and item ='$item' and parentid ='$id' ";	

	 try{  
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				array_push($AfterpicData, $row["picname"]);			
			}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
	$Afterpicid=count($AfterpicData);
	  
	try{
		 $sql = "select * from mirae8440." . $tablename . " where id=? ";
		 $stmh = $pdo->prepare($sql);  
		 $stmh->bindValue(1, $id, PDO::PARAM_STR);      
		 $stmh->execute();            
		  
		 $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
		  
		 include 'rowDB.php';  

		  if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
				else $measureday="";
		  if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
				else $workday="";			      
		  if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
				else $demand="";			
		  if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
				else $doneday="";			
		  if($regist_day!="0000-00-00" and $regist_day!="1970-01-01" and $regist_day!="")  $regist_day = date("Y-m-d", strtotime( $regist_day) );
				else $regist_day="";	
  
						
		 }catch (PDOException $Exception) {
		   print "오류: ".$Exception->getMessage();
		 }
}
else   // 신규자료 등록일 경우
{     
	// 부모코드가 있는 경우는 부모코드의 일반 자료를 가져온다.   
	// 신규인 경우는 아래 parent_id가 있는 경우는 다른 실행 구현  makesub -> 하위발주 만들기
	if( ($mode=='new' || $mode=='makesub') && (int)$parent_id !=0  )		
	{   
		try{
				 $sql = "select * from mirae8440." . $tablename . " where id=? ";
				 $stmh = $pdo->prepare($sql);
				 // 부모 정보를 가져온다.
				 $stmh->bindValue(1, $parent_id, PDO::PARAM_STR);      				 
				 $stmh->execute();            
				  
				 $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
				  
				$tmp =  $parent_id; // 부모코드를 유지하기 위해서 임시 저장후 돌려준다 
				 include 'rowDB.php';  
								 
				$parent_id = $tmp;
				$id='';
				$item_name='';
				$item_spec='';
				$item_num='';
				$item_unit='';
				$item_memo='';
				$item_note='';		

				$memo='';
				$memo2='';							

				$et_writeday='';
				$et_wpname=''; 
				if($et_wpname==null)
				$et_wpname='';  // 현장명이 없을때는 주소로

				$et_schedule='';
				$et_person='';
				$et_content='';  
				$et_note='';	  
				
				$send_date=$today;	
				$send_deadline = $doneday;

				 }catch (PDOException $Exception) {
				   print "오류: ".$Exception->getMessage();
				 }
	}
	
	else
	{	
	$regist_day=$today;
	
	}
}

// print 'parent' . $parent_id;
// print '<br>';
// print 'id ' . $id;

if($mode=='copy')
	$copystr='(복사됨)';
      

// 첨부 이미지에 대한 부분
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 // 이미지 이미 있는 것 불러오기 
$picData=array(); 
$tablename='woosung';
$imagecolumn = 'image';

$sql=" select * from mirae8440.woosungpic where tablename ='$tablename' and item ='$imagecolumn' and parentid ='$id' ";	

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			array_push($picData, $row["picname"]);			
        }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
  }  
$picNum=count($picData);     
   
   
// ctrlv 붙여넣기 첨부 이미지에 대한 부분
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 // 이미지 이미 있는 것 불러오기 
$ctrlvpicData=array(); 
$tablename='woosung';
$imagecolumn = 'ctrlvimg';

$sql=" select * from mirae8440.woosungpic where tablename ='$tablename' and item ='$imagecolumn' and parentid ='$id' ";	

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			array_push($ctrlvpicData, $row["picname"]);			
        }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
  }  
   
   
 ?>
 
<!DOCTYPE html>
<html lang="ko">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
	<!-- 화면 이미지 저장을 위한 JS -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>  
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
	    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>	    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script> 
	
	<script src="http://8440.co.kr/js/repeater.js"></script>
	<script src="http://8440.co.kr/common.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>

<title> 우성 발주관리 </title>    
</head>

<style>

/* 스크립 캡쳐 후 반영을 위한 부분 */
.output{float:left;padding:10px;width:94%;height:300px;text-align:center;overflow:hidden;border:1px solid #ccc;background-color:#EBFBFF;}

.output::before{content:" 화면 캡쳐 후 붙여넣기 (win키+shift+s -> Ctrl+V) ";display:inline-block;margin-top:5%;}

.output.paste{height:auto;}

.output.paste::before{display:none}

.output img{max-width:100%;}

#output2.output{float:right;}

#output2.output::before{content:"[blob] 화면 캡쳐 후 붙여넣기 (win키+shift+s -> Ctrl+V) "}

</style>


<body>

<div class="container-fluid"> 

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
		     결재가 진행중입니다. <br> 
		   <br> 
		  수정사항이 있으면 결재권자에게 말씀해 주세요.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>
			
    <div class="row d-flex justify-content-center align-items-center h-10">	        
			<div class="card align-middle" style="width:43rem; border-radius:20px;">		
			
				<div class="card" style="padding:10px;margin:10px;">
					
					<h6 class="display-5 card-title text-center" style="color:#113366;"> 
						<?=$copystr?>

                        <? if($parent_id==0)
							 print '(발주처) 발주';
                           else
							 print '발주 &nbsp';
						 ?>
						<button type="button" id=closeBtn class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="전체 리스트에 발주서변동 내역을 적용합니다." > <i class="bi bi-box-arrow-left"></i> 적용후 </button>					
						<button id="saveBtn" class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="데이터를 서버에 저장합니다." > <i class="bi bi-hdd-fill"></i>저장 </button>						
					  <? if($mode!='new' and $mode!='makesub') { ?>	
						 <button type="button" class="btn btn-outline-primary btn-sm"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="하위 발주내용을 생성합니다." onclick="location.href='write_form.php?mode=makesub&parent_id=<?=$id?>&check=<?=$check?>';"> <i class="bi bi-plus-square"></i> 하위발주  </button>
						  <?php if($parent_id!=0) { ?>   
                         <button id="showjpgBtn" class="btn btn-outline-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="발주서를 PDF형식으로 보여줍니다."  type="button">발주서PDF</button>						                          
						 <button id="ExcelexportBtn" class="btn btn-outline-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="발주서를 Excel형식으로 Export합니다."  type="button" onclick="location.href='excelform.php?id=<?=$id?>'">Excel</button>						 
						  <?php } ?>   
                         <button id="copyBtn" class="btn btn-outline-dark btn-sm" type="button" onclick="location.href='write_form.php?mode=copy&id=<?=$id?>&parent_id=<?=$parent_id?>&check=<?=$check?>';"> <i class="bi bi-clipboard-plus-fill"></i>복사</button>						 
					  <? } ?>
					    
					  <? if($mode!='new' and $mode!='makesub') { ?>	
						 <button type="button" id=delBtn data-bs-toggle="tooltip" data-bs-placement="bottom" title="데이터 삭제는 신중히 진행해주세요. 서버에서 사라집니다."  class="btn btn-outline-danger btn-sm" ><i class="bi bi-trash3"></i>삭제</button>						
					  <? } ?>						
					  </h6>
				</div>	
				<div class="card-body text-center">
				<form id="board_form" name="board_form" method="post" enctype="multipart/form-data"   >
					<input type="hidden" id="mode" name="mode" value="<?=$mode?>">
					<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
					<input type="hidden" id="parent_id" name="parent_id" value="<?=$parent_id?>" >			  								
					<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" > 					
					<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  > 					
					<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>"  > 					
					<input type="hidden" id="item" name="item" value="<?=$item?>"  > 					
					<input type="hidden" id="item_name" name="item_name" value="<?=$item_name?>"  > 					
					<input type="hidden" id="item_spec" name="item_spec" value="<?=$item_spec?>"  > 					
					<input type="hidden" id="item_num" name="item_num" value="<?=$item_num?>"  > 					
					<input type="hidden" id="item_unit" name="item_unit" value="<?=$item_unit?>"  > 					
					<input type="hidden" id="item_memo" name="item_memo" value="<?=$item_memo?>"  > 					
					<input type="hidden" id="item_note" name="item_note" value="<?=$item_note?>"  > 					
					<input type="hidden" id="imagecolumn" name="imagecolumn" value="<?=$imagecolumn?>"  > 					
					<input type="hidden" id="imagesource" name="imagesource[]"   > 					
			  
				<span class="form-control">
				    <div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text">현장명</span>
						  </div>
						<input type="text" class="form-control"  type="text" name="workplacename" id="workplacename" value="<?=$workplacename?>" required >
						
					</div>
				
				    <div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text">주소</span>
						  </div>
						<input type="text" class="form-control"  type="text" name="address" id="address" value="<?=$address?>" >						
					</div>
				
				    <div class="input-group input-group-sm">
					   <div class="input-group-prepend">
							<span class="input-group-text">접수</span>
						  </div>
					       <input type="date" class="form-control" name="regist_day" id="regist_day" value="<?=$regist_day?>"  > 					
						<div class="input-group-append">
							<span class="input-group-text">납기</span>
							</div>
					   	  <input type="date" class="form-control" name="doneday" id="doneday" value="<?=$doneday?>"  > 					
					</div>				
						
				    <div class="input-group input-group-sm " >	
						&nbsp;	
					</div>	
				    <div class="input-group input-group-sm">
					   <div class="input-group-prepend">
							<span class="input-group-text">원청</span>
						  </div>						  
							<input type="text"  class="form-control" name="firstord" id="firstord" value="<?=$firstord?>"  > 						       
						<div class="input-group-append">
							<span class="input-group-text">담당</span>																					
						  </div>
							<input type="text" class="form-control" name="firstordman" id="firstordman" value="<?=$firstordman?>"  > 											  
					
					</div>							
				    <div class="input-group input-group-sm">								
						<div class="input-group-append">
							<span class="input-group-text"> <i class="bi bi-telephone-fill"></i>&nbsp; 연락처 </span>							
						  </div>
							<input type="text"  class="form-control" name="firstordmantel" id="firstordmantel" value="<?=$firstordmantel?>"  > 																		  
					</div>			
					
				    <div class="input-group input-group-sm " >	
						&nbsp;	
					</div>				
					
				    <div class="input-group input-group-sm">	
					   <div class="input-group-prepend">
							<span class="input-group-text text-danger">발주처</span>
						  </div>						  
							<input type="text"  class="form-control" name="secondord" id="secondord" value="<?=$secondord?>"  > 					
						<div class="input-group-append input-group-sm">
							<span class="input-group-text text-danger">담당</span>																					
						  </div>
							<input type="text"  class="form-control" name="secondordman" id="secondordman" size= 11 value="<?=$secondordman?>"  > 					
					</div>			
					
				    <div class="input-group input-group-sm">								
						<div class="input-group-append input-group-sm">
							<span class="input-group-text text-danger"> <i class="bi bi-telephone-fill"></i> &nbsp; 연락처</span>							
						  </div>
							<input type="text"  class="form-control" name="secondordmantel" id="secondordmantel" size=11 value="<?=$secondordmantel?>"  > 					
					</div>			
					
				    <div class="input-group input-group-sm">	
					   <div class="input-group-prepend">
							<span class="input-group-text">현장소장</span>
						  </div>						  
							<input type="text"  class="form-control" name="chargedman" id="chargedman" value="<?=$chargedman?>"  > 					

						<div class="input-group-append">
							<span class="input-group-text"> <i class="bi bi-telephone-fill"></i>&nbsp; 연락처 </span>							
						  </div>
							<input type="text"  class="form-control" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>"  > 							
					</div>		
					
				    <div class="input-group">
                        <br>					
					</div>		
					
					<?
					   // 발주 수신처가 있는 경우는 만드는 구간
					   if((int)$parent_id>0)
					   {   ?>			   
					 
					<label for="basic-url" class="form-label text-primary"> 수신처 정보 </label>
				    <div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text text-primary" id="basic-url" >발주수신처</span>
						  </div>						  
							<input type="text" class="form-control"  id=send_company  name=send_company  value="<?=$send_company?>"  >  
					</div>		
				      <div class="input-group ">
					   <div class="input-group-prepend">
							<span class="input-group-text text-primary">담당</span>
						  </div>						  
							<input type="text" class="form-control"  id=send_chargedman  name=send_chargedman  value="<?=$send_chargedman?>"  >  
				
					  <div class="input-group-append">
							<span class="input-group-text text-primary"><i class="bi bi-telephone-fill"></i>&nbsp; 연락처</span>
						  </div>						  
							<input type="text" class="form-control"  id=send_tel  name=send_tel value="<?=$send_tel?>"  >      						
					</div>						
				    <div class="input-group input-group-sm">
					   <div class="input-group-prepend">
							<span class="input-group-text text-primary">발주일</span>
						  </div>
					       <input type="date" class="form-control" name="send_date" id="send_date" value="<?=$send_date?>"  > 					
						<div class="input-group-append">
							<span class="input-group-text text-primary">납기일</span>
							</div>
					   	  <input type="date" class="form-control" name="send_deadline" id="send_deadline" value="<?=$send_deadline?>"  > 					
					</div>		
					   <div class="input-group">
							<br>					
						</div>	
					
					   <? } ?>
					   
					   
     <?php if($parent_id!=0) { ?>                 					   
	    <div id="repeater">  
                    <div class="repeater-heading">
					   <div class="input-group p-2 justify-content-left">					   	     		
							<button type="button" class="btn btn-primary repeater-add-btn"> <i class="bi bi-plus-square-fill"></i></button>	&nbsp;								
						<!--	<button type="button" class="btn btn-outline-primary repeater-copyadd-btn"> <i class="bi bi-plus-square-fill">내용 복사 줄 삽입 </i></button>		-->
					   </div>					   
                    </div>                       
					<div class="items" data-group="item">		
					<div class="clearfix"></div>					
						<div class="input-group">
						   <div class="input-group-prepend">
								<span class="input-group-text">품명</span>
							  </div>						  
								<input type="text" class="form-control"  id=_name  name=_name  data-name="_name" required >  
						</div>		
						<div class="input-group">
						   <div class="input-group-prepend">
								<span class="input-group-text">규격</span>
							  </div>						  
								<input type="text" class="form-control"  id=_spec  name=_spec   data-name="_spec" >  
						</div>		
						<div class="input-group">
						   <div class="input-group-prepend">
								<span class="input-group-text">수량</span>
							  </div>						  
								<input type="text" class="form-control"  id=_num  name=_num   data-name="_num" >      
							<div class="input-group-append">
								<span class="input-group-text"> 단위 </span>							
							  </div>							
							<input type="text" class="form-control"  id=_unit  name=_unit   data-name="_unit" >  	  
						</div>		
						<div class="input-group">
						   <div class="input-group-prepend">
								<span class="input-group-text">사양</span>
							  </div>						  
								<input type="text" class="form-control"  id=_memo  name=_memo     data-name="_memo">  				
						</div>		
						<div class="input-group">
						   <div class="input-group-prepend">
								<span class="input-group-text">비고</span>
							  </div>						  
								<input type="text" class="form-control"  id=_note  name=_note   data-name="_note" >  				
						</div>	


				   <div class="clearfix"></div>
				   
					<!-- Repeater Remove Btn -->
					   <div class="input-group p-1 justify-content-left">						        
						<!--	<button type="button" id="addbtn" class="btn btn-primary"> <i class="bi bi-plus-square-fill"></i></button>
							&nbsp; -->
							<div class="repeater-remove-btn">
								<button class="btn btn-danger remove-btn">
									<i class="bi bi-dash-circle"></i>
								</button>
							</div>
					    </div>				   
					
				</div>		<!-- end of dataitem -->  					
				
		</div>	<!-- end of repeater -->   

     <?php }  // end of if parent_id!=0 ?>   			

				
				
				    <div class="input-group">
                        <br>					
					</div>	
				<?php if($parent_id!=0) { ?>  					
					<div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text">세부사양</span>
						  </div>						  
							<textarea class="form-control"  id=memo  name=memo rows=12><?=$memo?></textarea>
					</div>				
				
					<div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text">기타사항</span>
						  </div>						  
							<textarea class="form-control"  id=memo2  name=memo2 rows=12><?=$memo2?></textarea>
					</div>			
				<?php }  // end of if parent_id!=0 ?>   
					<div class="input-group">					   							
                             <label  for="upfilepdf" class="input-group-text btn btn-primary btn-sm"> 파일(10M 이하) pdf파일 첨부 </label>						  
							 				
							 <input id="upfilepdf"  name="upfilepdf[]"  class="form-control"  type="file" onchange="this.value" multiple  style="display:none" >
					</div>	
					<div class="input-group">
						    <label  for="upfile" class="input-group-text btn btn-dark btn-sm ">  사진 첨부 </label>	
							 <input id="upfile"  name="upfile[]"  class="form-control"  type="file" onchange="this.value" multiple accept=".gif, .jpg, .png" style="display:none">
					</div>	         
   		   					
					
				<br> 	  
				<div class="card" style="padding:10px;margin:10px;">
					
					<h6 class="display-5 card-title text-center" style="color:#113366;"> 
						<?=$copystr?> 발주서 &nbsp;
						<button type="button" id=closeBtn1 class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="전체 리스트에 발주서변동 내역을 적용합니다." > <i class="bi bi-box-arrow-left"></i> 적용후 </button>					
						<button id="saveBtn1" class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="데이터를 서버에 저장합니다." > <i class="bi bi-hdd-fill"></i>저장 </button>						
					  <? if($mode!='new' and $mode!='makesub') { ?>	
						 <button type="button" class="btn btn-outline-primary btn-sm"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="하위 발주내용을 생성합니다." onclick="location.href='write_form.php?mode=makesub&parent_id=<?=$id?>&check=<?=$check?>';"> <i class="bi bi-plus-square"></i> 하위발주  </button>
						 <?php if($parent_id!=0) { ?>   						 
                         <button id="showjpgBtn1" class="btn btn-outline-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="발주서를 PDF형식으로 보여줍니다."  type="button">발주서PDF</button>						 
                         <button id="ExcelexportBtn1" class="btn btn-outline-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="발주서를 Excel형식으로 Export합니다."  type="button" onclick="location.href='excelform.php?id=<?=$id?>'">Excel</button>						 
						 <?php } ?>   						 
                         <button id="copyBtn1" class="btn btn-outline-dark btn-sm" type="button" onclick="location.href='write_form.php?mode=copy&id=<?=$id?>&parent_id=<?=$parent_id?>&check=<?=$check?>';"> <i class="bi bi-clipboard-plus-fill"></i>복사</button>						 
					  <? } ?>
					    
					  <? if($mode!='new' and $mode!='makesub') { ?>	
						 <button type="button" id=delBtn1 data-bs-toggle="tooltip" data-bs-placement="bottom" title="데이터 삭제는 신중히 진행해주세요. 서버에서 사라집니다."  class="btn btn-outline-danger btn-sm" ><i class="bi bi-trash3"></i>삭제</button>						
					  <? } ?>						
					  </h6>
				</div>	
				
				</form>			  
				</div>
				
				<div class="input-group justify-content-center align-items-center">					
						<h4> 스크린샷(win+shift+s) 후 붙여넣기(ctrl+v) </h4>    
						<div id="output" class="output"></div>							
				</div>	 
					<br>
       	 			
				<div class="input-group justify-content-center align-items-center">
					<div class="input-group-prepend mt-1 mb-0">
					 <div id = "displayPicture" style="display:none;" >  </div>   					
					 </div>
				</div>																
					<input id="pInput" name="pInput" type=hidden value="0" >	
					<input id="ctrlvimg" name="ctrlvimg" type=hidden value="0" >						
				</div>

			</div>
		</div>		
				
     </div>
  
		
  </body>
</html>    
 
 
 <script >
 
$(document).ready(function(){

// 버튼복사
$("#showjpgBtn1").click(function(){   
	$("#showjpgBtn").click();
});

// PDF파일 만들기 버튼	 
$("#showjpgBtn").click(function(){    // jpg보여주기 그리고 pdf파일 생성	
	var id = '<?php echo $id; ?>' ; 
	var link ;
	link = 'showjpg.php?id=' + id;
	window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=10,width=1250,height=900");
});	
   
	/* Create Repeater */
	$("#repeater").createRepeater({
		// showFirstItemToDefault:  true,
		showFirstItemToDefault:  false,
	});
	
	$("#estimateBtn").click(function(){    // 견적서 클릭하면 이동하기
	    popupCenter('estimate.php?id=' + $("#id").val(), '견적서', 700, 900);
	 });					
	 
	$("#pInput").val('50'); // 최초화면 사진파일 보여주기
	
 let timer3 = setInterval(() => {  // 2초 간격으로 사진업데이트 체크한다.
	      if($("#pInput").val()=='100')   // 사진이 등록된 경우
		  {
	             displayPicture();  
				 // console.log(100);
		  }	      
		  if($("#pInput").val()=='50')   // 사진이 등록된 경우
		  {
	             displayPictureLoad();				 
		  }
	     
		   
	 }, 2000);	
	 


  
delPicFn = function(divID, delChoice) {
	console.log(divID, delChoice);

	$.ajax({
		url:'delpic.php?picname=' + delChoice ,
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
	  	 
	 
// 시공전 사진 멀티업로드	
$("#upfile").change(function(e) {	    
	    $("#imagecolumn").val('image');
	    var item = $("#item").val();
		FileProcess(item);	
});	 
	
function FileProcess(item) {
	//do whatever you want here
	id = $("#id").val();
		
	if(Number(id)==0) {
		 if(confirm("사진을 저장하려면 자료를 생성해야 합니다.\n\n 지금 자료를 등록하시겠습니까?")) {
			 
			$("#mode").val('insert');  // 삽입모드로 변경함.
			// 자료 삽입/수정하는 모듈		  
			Fninsert();				 
			if($("#mode").val()=='insert')  
				   {					      
						  location.href='write_form.php?id=' + data ;	// 실행되면 수정모드가 됨.		
				   }
		   }
	   }
	  // 사진 서버에 저장하는 구간	
	  // 사진 서버에 저장하는 구간	
			//tablename 설정
		   $("#tablename").val('woosung');  
			// 폼데이터 전송시 사용함 Get form         
			var form = $('#board_form')[0];  	    
			// Create an FormData object          
			var data = new FormData(form); 

			tmp='사진을 저장중입니다. 잠시만 기다려주세요.';		
			$('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show'); 

            console.log($('#upfile').val());			

			$.ajax({
				enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
				processData: false,    
				contentType: false,      
				cache: false,           
				timeout: 600000, 			
				url: "pic_insert.php",
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

// 하단복사 버튼
$("#closeBtn1").click(function(){ 
   $("#closeBtn").click();
})
	
$("#closeBtn").click(function(){    // 저장하고 창닫기	
    opener.location.reload();	
	self.close();
});	
// 하단복사 버튼
$("#saveBtn1").click(function(){ 
   $("#saveBtn").click();
})
			
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
	var copystr = '<?php echo $copystr; ?>' ; 

	var id = $("#id").val();  	
	   	   
// 결재상신이 아닌경우 수정안됨	 
if(Number(id)>0 ) 
    {
		if(copystr=='(복사됨)')
			  $("#mode").val('copy');     
			else
		      $("#mode").val('modify');     
		   
	}
		  else
			  $("#mode").val('insert');     		  
		  
	// 자료 삽입/수정하는 모듈		  
	Fninsert();	
		
}); 	
		
$("#saveBtn2").click(function(){ 
	$("#saveBtn").click();   
		
}); 
 

// 자료의 삽입/수정하는 모듈 
function Fninsert() {	 
		   
	console.log($("#mode").val());    
	
// item을 다중으로 입력하기 위한 루틴
	var data2 = $('#board_form').serializeArray();
	// var data3 = $('#board_form').serialize();
	
	var item_name='';
	var item_spec='';
	var item_num='';
	var item_unit='';
	var item_memo='';
	var item_note='';
	
	for(i=0; i < data2.length; i++)
	{
    	console.log(data2[i].name + '\n'); 
		if(data2[i].name.includes('[_name]'))
			item_name += data2[i].value + '|' ;
		if(data2[i].name.includes('[_spec]'))
			item_spec += data2[i].value + '|' ;
		if(data2[i].name.includes('[_num]'))
			item_num += data2[i].value + '|' ;
		if(data2[i].name.includes('[_unit]'))
			item_unit += data2[i].value + '|' ;
		if(data2[i].name.includes('[_memo]'))
			item_memo += data2[i].value + '|' ;
		if(data2[i].name.includes('[_note]'))
			item_note += data2[i].value + '|' ;
	}
	
	console.log(item_name);
	console.log(item_spec);
	console.log(item_num);
	console.log(item_unit);
	console.log(item_memo);
	console.log(item_note);
	
	$('#item_name').val(item_name);
	$('#item_spec').val(item_spec);
	$('#item_num').val(item_num);
	$('#item_unit').val(item_unit);
	$('#item_memo').val(item_memo);
	$('#item_note').val(item_note);
	
	// var arr = {};
		// Object.keys(data2).forEach(d => {    
			// arr[data2[d]] = d;
		// });
		// console.log(arr);

	// 폼데이터 전송시 사용함 Get form         
	var form = $('#board_form')[0];  	    
	// Create an FormData object          
	var data = new FormData(form); 
  
	tmp='파일을 저장중입니다. 잠시만 기다려주세요.';		
	$('#alertmsg').html(tmp); 			  
	$('#myModal').modal('show'); 	    

	$.ajax({
		enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
		processData: false,    
		contentType: false,      
		cache: false,           
		timeout: 600000, 			
		url: "insert.php",
		type: "post",		
		data: data,			
		// dataType:"text",  // text형태로 보냄
		success : function(data){
			
				console.log(data);

				// opener.location.reload();
				// window.close();	
				setTimeout(function() {
					$('#myModal').modal('hide');  
					}, 1000);
				
				if($("#mode").val()=='insert' || $("#mode").val()=='copy' || $("#mode").val()=='makesub')  // 삽입인 경우는 목록으로 이동
				   {					   
									   
						  //var str=data;
						  var words = data.split('|');				  
						  console.log(words);
						  location.href='write_form.php?id=' + words[0] +'&parent_id=' + words[1] ;							  
				   }

		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
					} 			      		
	   });		

		
 }
 
 
// 하단복사 버튼
$("#delBtn1").click(function(){ 
   $("#delBtn").click();
}) 
		 
$("#delBtn").click(function(){      // del
	var id = $("#id").val();    
	var reporter = $("#reporter").val();    
	var approve = $("#approve").val();  
	var user_name = $("#user_name").val();  
	var is_child='<?php echo $is_child;?>';

if(Number(is_child)>0)
{
	tmp='하위 발주가 있습니다. 하위발주 삭제 후 삭제할 수 있습니다.';		
	$('#alertmsg').html(tmp); 			  
	$('#myModal').modal('show');  
}
else
  {
	if( (user_name=='우성test') || user_name=='김보곤') {   
	
	 if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	   $("#mode").val('delete');     
		  
		$.ajax({
			url: "insert.php",
			type: "post",		
			data: $("#board_form").serialize(),
			dataType:"text",  // text형태로 보냄
			success : function( data ){
				opener.location.reload();	
				self.close();				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });	
		 }		   
		} // end of if
		else
		  {
	      tmp='삭제권한이 없습니다.';		
		  $('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show');  
		  }
    } // end of is_child
			
 }); // end of function
 
 // //윈도우 창을 닫을때 jpg 파일 삭제함  - 이것때문에 계속 오류가 발생한 것임... 창을 닫는 다는 것이 새로운 창을 띄운것과 같이 되므로...
	// $(window).bind("beforeunload", function (e){	
		// opener.location.reload();		
	// });	
	
}); // end of ready document
 

// 첨부된 이미지 불러오기
function displayPicture() {       
	$('#displayPicture').show();
	params = $("#id").val();	
	$("#tablename").val('woosung');
	$("#imagecolumn").val('image');	
	
    var tablename = $("#tablename").val();    
    var imagecolumn = $("#imagecolumn").val();	
	
	$.ajax({
		url:'load_pic.php?id=' + params + '&tablename=' + tablename + '&imagecolumn=' + imagecolumn ,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recid = data["recid"];		   
		   console.log(data);
		   $("#displayPicture").html('');
		   for(i=0;i<recid;i++) {			   
			   $("#displayPicture").append("<img id=pic" + i + " src ='./uploads/" + data["img_arr"][i] + "' style='width:100%;'  > <br> " );			   
         	   $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  data["img_arr"][i] + "')> 삭제 </button>&nbsp; <br>");					   
		      }		   
			    $("#pInput").val('');			
    });	
}

// 시공전 기존 있는 이미지 화면에 보여주기
function displayPictureLoad() {    
	$('#displayPicture').show();	
	var picData = <?php echo json_encode($picData);?> ;	
	
	console.log(picData.length);
	console.log(picData);
    for(i=0;i<picData.length;i++) {
       $("#displayPicture").append("<img id=pic" + i + " src ='./uploads/" + picData[i] + "' style='width:100%; ' > " );			
	   $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + picData[i] + "')> 삭제 </button>&nbsp;<br>");			   
	  }		   
		$("#pInput").val('');	
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

function load_init() {
    // 삭제할 부분과 남길부분
	$('#board_form').find('input').each(function(){ $(this).val(''); });
	$('#board_form').find('textarea').each(function(){ $(this).val(''); });

	$('#workplacename').val("<? echo $workplacename; ?>");
	$('#address').val("<? echo $address; ?>");
	$('#regist_day').val("<? echo $regist_day; ?>");
	$('#doneday').val("<? echo $doneday; ?>");
	$('#firstord').val("<? echo $firstord; ?>");
	$('#firstordman').val("<? echo $firstordman; ?>");
	$('#firstordmantel').val("<? echo $firstordmantel; ?>");
	$('#secondord').val("<? echo $secondord; ?>");
	$('#secondordman').val("<? echo $secondordman; ?>");
	$('#secondordmantel').val("<? echo $secondordmantel; ?>");
	$('#chargedman').val("<? echo $chargedman; ?>");
	$('#chargedmantel').val("<? echo $chargedmantel; ?>");
	$('#send_date').val(getToday());		
	$('#send_deadline').val($('#doneday').val());		
	
	$('#mode').val("<? echo $mode; ?>");
	$('#id').val("<? echo $id; ?>");
	$('#parent_id').val("<? echo $parent_id; ?>");
	
}

function load_item() {
	
// 다중input의 초기 로드 부분
	var item_name = "<? echo $item_name; ?>";
	var item_spec = "<? echo $item_spec; ?>";
	var item_num = "<? echo $item_num; ?>";
	var item_unit = "<? echo $item_unit; ?>";
	var item_memo = "<? echo $item_memo; ?>";
	var item_note = "<? echo $item_note; ?>";
	
	
	var mode = "<? echo $mode; ?>";
	
	// 마지막 파이프 문자 제거	
	item_name = deleteLastchar(item_name);
	item_spec = deleteLastchar(item_spec);
	item_num = deleteLastchar(item_num);
	item_unit = deleteLastchar(item_unit);
	item_memo = deleteLastchar(item_memo);
	item_note = deleteLastchar(item_note);
	
	nameArr = item_name.split('|');
	specArr = item_spec.split('|');
	numArr = item_num.split('|');
	unitArr = item_unit.split('|');
	memoArr = item_memo.split('|');
	noteArr = item_note.split('|');
	
	console.log(item_name);
	console.log(item_spec);
	console.log(item_num);
	console.log(item_unit);
	console.log(item_memo);
	console.log(item_note);	
	
if(mode!='new' && nameArr[0]!='' ) 	
	for(i=0;i<nameArr.length;i++)	
	{		
	 // if(i>0)  // 한줄이 넘어가면 열을 추가해준다.
		$('.repeater-add-btn').click();		  
	$('#item_' + i + '__name').val(nameArr[i]);	
	$('#item_' + i + '__spec').val(specArr[i]);	
	$('#item_' + i + '__num').val(numArr[i]);	
	$('#item_' + i + '__unit').val(unitArr[i]);	
	$('#item_' + i + '__memo').val(memoArr[i]);	
	$('#item_' + i + '__note').val(noteArr[i]);		

	}
	
}

function deleteLastchar(str)
// 마지막 문자 제거하는 함수
{
  return str = str.substr(0, str.length - 1);		
}
 
// 하위발주 시작하면 초기입력창 지워주기
setTimeout(function() {  
if($("#mode").val()=='makesub')
			load_init();		
			load_item();		
}, 500);

// 스크립캡쳐를 위한 구문임
$(function(){

	var $output=document.querySelector("#output");
	
// ctrlv 기존 이미지가 있으면 화면에 보여주기 	
	var ctrlvpicData = <?php echo json_encode($ctrlvpicData);?> ;	
	var fileNo ;
	
// 기존 이미지가 존재한다면 불러주기	
if(	ctrlvpicData.length>0)
{
	$output.className="output paste";					

	console.log('ctrlvpicData.length');
	console.log(ctrlvpicData.length);
	console.log(ctrlvpicData);
    for(i=0;i<ctrlvpicData.length;i++) {
		
		var imgsrc = "<img id=imgID" + (i+1) + " src ='./uploads/" + ctrlvpicData[i] + "' > ";
		
		$('#output').append(imgsrc);						
       
		$('#output').append("<button type='button' id=ctrldelPicBtn" + (i+1) + " onclick=deleteFile('" + (i+1) + "','" + ctrlvpicData[i] + "') > <span style='background-color:white; color:red;' > <i class='bi bi-dash-circle'></i> </span> </button> " );	
	   		
	    fileNo= i + 2;	  // ID 이미지 카운트를 만들기 위한 것	   
     }		   
} // end of if statement


	// base64 encode 되어 브라우저가 읽어 들임	

	$output.onpaste = function(event){

		var items = (event.clipboardData || event.originalEvent.clipboardData).items;

		//console.log(JSON.stringify(items)); // will give you the mime types

		for (index in items) {

			var item = items[index];

			if (item.kind === 'file') {

				var blob = item.getAsFile();

				var reader = new FileReader();

				// data url!

				reader.readAsDataURL(blob);

				reader.onload = function(event){

					$output.className="output paste";					

					var img=new Image();

					img.src=event.target.result;
					img.id= 'imgID' + fileNo;


					$output.appendChild(img);
					
					// 파일로 저장							
					console.log(img);					
					$("#imagesource").val(img);  							
			 
				// 카카오톡 이미지등 ctrl v 붙여넣기 파일 처리구문
				$("#imagecolumn").val('ctrlvimg');	    	
						
				console.log(document.querySelector("#output"));	    	
				
				   $("#tablename").val('woosung');  
					// 폼데이터 전송시 사용함 Get form         
					var form = $('#board_form')[0];  	    
					// Create an FormData object          
					var data = new FormData(form); 					

				// imgID를 생성해서 하나하나 이미지를 삭제하고 수정할때 사용한다.				
				var imgtag = 'img[id="imgID' + fileNo  + '"]';
				
				console.log('imgtag' + imgtag);				            
				html2canvas(document.querySelector(imgtag)).then(function(canvas) {

						var myImg = canvas.toDataURL("image/png");
						myImg = myImg.replace("data:image/png;base64,", "");

						$.ajax({
						type: "POST",
						data: {
							"imgSrc": myImg
						},
						dataType: "text",
						url: "save_process_image.php",
						success: function(getdata) {
						
							console.log(getdata);	
							
                            var tmpimgname = getdata;						

								$.ajax({
									enctype: 'multipart/form-data',  // file을 서버에 전송하려면 이렇게 해야 함 주의
									processData: false,    
									contentType: false,      
									cache: false,           
									timeout: 600000, 			
									url: "pic_insert_image.php?filename=" + tmpimgname,
									type: "post",		
									data: data,						
									success : function(getdata){
										
										// 사진이 등록되었으면 100 입력됨
										 $("#ctrlvimg").val('100');	
										 
										 let htmlData = '';

                                       // 삭제버튼(-) 생성해 주기										 									   
											htmlData += "<button type='button' id=ctrldelPicBtn" + fileNo + " onclick=deleteFile('" + fileNo + "','" + tmpimgname.trim() + "') > <span style='background-color:white; color:red;' > <i class='bi bi-dash-circle'></i> </span> ";
											htmlData += '</button>';
											$('#output').append(htmlData);
											fileNo++;

									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
												} 			      		
								   });								
							
						},
						error: function(a, b, c) {
							alert("error");
						}
						});
						
				 });  // end of html2canvas					

				}; 

			}

		}
		

	}  // end of function
	

deleteFile = function(divID, delChoice)
 {
	console.log(divID, delChoice);
	
	$.ajax({
		url:'delpic.php?picname=' + delChoice,
		type:'post',
		data: $("board_form").serialize(),
		dataType: 'json',
		}).done(function(data){								   
		  // 시공전사진 삭제 
		    console.log('delete 후 data');
		    console.log(data);
			$("img[id='imgID" + divID + "']").remove();  // 그림요소 삭제
			$("#ctrldelPicBtn" + divID).remove();  // 그림버튼 삭제
			$("#ctrlInput").val('');	
		});		

} 						
	

});
   
									
  
   
</script>

