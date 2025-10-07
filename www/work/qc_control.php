<meta charset="utf-8">
 
 <?php
 session_start(); 
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"];
 
 $file_dir = '../uploads/'; 
 
 
 include 'request.php';
	 
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
  $doneday=$row["doneday"];
  $designer=$row["designer"];  // 설계자
  $dwglocation=$row["dwglocation"];  // dwg위치
  
  $material1=$row["material1"];
  $material2=$row["material2"];
  $material3=$row["material3"];
  $material4=$row["material4"];
  $material5=$row["material5"];
  $material6=$row["material6"];
  $widejamb=$row["widejamb"];
  $normaljamb=$row["normaljamb"];
  $smalljamb=$row["smalljamb"];
  
  $widejambworkfee=$row["widejambworkfee"];
  $normaljambworkfee=$row["normaljambworkfee"];
  $smalljambworkfee=$row["smalljambworkfee"];
  $workfeedate=$row["workfeedate"];      // 시공비 처리일
    
  $memo=$row["memo"];
  $memo2=$row["memo2"];
  $regist_day=$row["regist_day"];
  $update_day=$row["update_day"];
  
  $delivery=$row["delivery"];
  $delicar=$row["delicar"];
  $delicompany=$row["delicompany"];
  $delipay=$row["delipay"];
  $delimethod=$row["delimethod"];
  $demand=$row["demand"];
  $startday=$row["startday"];
  $testday=$row["testday"];
  $hpi=$row["hpi"];  
  $first_writer=$row["first_writer"];
  $update_log=$row["update_log"];
  $filename1=$row["filename1"];
  $filename2=$row["filename2"];
  $imgurl1="../imgwork/" . $filename1;
  $imgurl2="../imgwork/" . $filename2;
  $checkhold=$row["checkhold"];  
  $attachment=$row["attachment"];  

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

   
 ?>
 <!DOCTYPE HTML>
 <html>
 <head> 
 <meta charset="utf-8">

 <title> QC Control </title>
  </head>
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	
    <script src="http://8440.co.kr/order/order.js"></script>
    <script src="http://8440.co.kr/common.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>	
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 	
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>	   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js" integrity="sha512-79j1YQOJuI8mLseq9icSQKT6bLlLtWknKwj1OpJZMdPt2pFBry3vQTt+NZuJw7NSd1pHhZlu0s12Ngqfa371EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- jqeury Zoom -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.6.1/jquery.zoom.min.js" integrity="sha512-xhvWWTTHpLC+d+TEOSX2N0V4Se1989D03qp9ByRsiQsYcdKmQhQ8fsSTX3KLlzs0jF4dPmq0nIzvEc3jdYqKkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/work.css">   
 
 <!-- 최초화면에서 보여주는 상단메뉴 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.css" integrity="sha512-VSD3lcSci0foeRFRHWdYX4FaLvec89irh5+QAGc00j5AOdow2r5MFPhoPEYBUQdyarXwbzyJEO7Iko7+PnPuBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <body>
  <style>
  
    .rotated {
  transform: rotate(90deg);
  -ms-transform: rotate(90deg); /* IE 9 */
  -moz-transform: rotate(90deg); /* Firefox */
  -webkit-transform: rotate(90deg); /* Safari and Chrome */
  -o-transform: rotate(90deg); /* Opera */
}


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

fieldset.groupbox-border {
border: 1px groove #ddd !important; 
padding: 3 3 3 3 !important; 
margin: 3 3 3 3 !important; 
box-shadow: 0px 0px 0px 0px #000; 
} 

legend.groupbox-border { 
    background-color: #F0F0F0;
    color: #000;
    padding: 3px 6px;
font-size: 1.0em !important; 
font-weight: bold !important; 
text-align: left !important; 
border-bottom:none; 
}  

fieldset.groupbox1-border {
border: 1px groove #ddd !important; 
padding: 3 3 3 3 !important; 
margin: 3 3 3 3 !important; 
} 

legend.groupbox1-border { 
    background-color: #F0F0F0;
    color: #000;
    padding: 9px 9px;
font-size: 1.0em !important; 
font-weight: bold !important; 
text-align: left !important; 
border-bottom:none; 
}   


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

</style>


<div class="container-fluid">  
<div class="d-flex mb-1 justify-content-center">    



</div>   

<div id="wrap" > 
   
<div class='bigPictureWrapper'>
	<div class='bigPicture'>
	</div>	   
</div>


<div class="container-fluid">
			<!--Extra Full Modal -->
				<div  id="workboard_form_write" >				
						<div class="modal-content" >
							<div class="modal-header">
								<h2 class="modal-title" > QC공정표   <span id="workboardwindow_msg"> </span>  
								</h2>
								<button type="button"  id="closeBtn2"  aria-label="Close">
									<i data-feather="x"></i>
								</button>
							</div>
							<div class="modal-body" >								
								
							<div class="card">
                               <!--  <div class="card-header">
                                    	<!-- <h4 class="card-title">기존 주문서 복사 후 작성하기</h4> 
                                </div> -->
                                <div class="card-content" >
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-3 mb-1">
                                                <div class="input-group mb-1" >
                                                    <span class="input-group-text" >관리번호</span>
                                                    <input type="text" class="form-control" id="qc_num"  name="qc_num" value="QC-Jamb-<?=$num?>" readonly  >                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-3 mb-1">
                                                <div class="input-group mb-1" >
                                                    <span class="input-group-text" style="width:110px;"  >발주처</span>													
														<input type="text" class="form-control " id="secondord" name="secondord" value="<?=$secondord?>"  readonly  >																										                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-2 mb-1"> 
												<div class="input-group mb-1" >
                                                    <span class="input-group-text" style="width:110px;"  >담당자</span>													
														<input type="text" class="form-control " id="secondordman" name="secondordman" value="<?=$secondordman?>"  readonly  >																										                                                    
                                                </div>
                                            </div>   
                                            <div class="col-lg-2 mb-1"> 
												<div class="input-group mb-1" >
                                                    <span class="input-group-text" style="width:110px;"  >연락처</span>													
														<input type="text" class="form-control " id="secondordmantel" name="secondordmantel" value="<?=$secondordmantel?>"  readonly  >																										                                                    
                                                </div>
                                            </div>               
											
                                        </div>
										
                                        <div class="row">
                                            <div class="col-lg-3 mb-1">
                                                <div class="input-group mb-1">
                                                    <span class="input-group-text" >품명</span>
                                                    <input type="text" class="form-control" id="workName" name="workName"  readonly value="<?=$workName?>"  >                                                                                                        
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-1">
												   <div class="input-group mb-1">                                                   
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-1">
                                                <div class="input-group mb-1">
                                                </div>
                                            </div>
                                        </div>
                              								
						
						
                                        <div class="row">
                                            <div class="col-lg-12 mb-1">
                                               <table class="table table-striped table-bordered">
													<thead>
														<tr>
															<th scope="col" class="text-center">공정No</th>
															<th scope="col" class="text-center">Flow Chart</th>
															<th scope="col" class="text-center">공정명</th>
															<th scope="col" class="text-center">설비명</th>															
															<th scope="col" class="text-center">관리점(점검항목)</th>
															<th scope="col" class="text-center">규격</th>
															<th scope="col" class="text-center">주기</th>
															<th scope="col" class="text-center">기록</th>
															<th scope="col" class="text-center">담당</th>
															<th scope="col" class="text-center">비고</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>1</td>
															<td>예시</td>
															<td>예시</td>
															<td>예시</td>
															<td>예시</td>
															<td>예시</td>
															<td>예시</td>
															<td>예시</td>
															<td>근무자</td>
															<td></td>
														</tr>
														<!-- 이곳에 추가 행을 넣으시면 됩니다. -->
													</tbody>
												</table>
                                            </div>											
										</div>		
										
                                    </div>
                                </div>
                            </div>																
								
							</div>
							<div class="modal-footer justify-content-start">
								<button type="button" class="btn btn-light-secondary"  id="closeBtn"  >
									<i class="bx bx-x d-block d-sm-none"></i>
									<span class="d-none d-sm-block">Close</span>
								</button>
								<button type="button" id="workboardSaveBtn"  class="btn btn-primary ml-1">
									<i class="bx bx-check d-block d-sm-none"></i>
									<span class="d-none d-sm-block"> 저장 </span>
								</button>								
								
								</div>
							</div>
						</div>				
</div>

		
<div class="container">  
	
     
      
	
		      
				<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
				<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 						
                <div id="vacancy" style="display:none">  </div>

				
                <div class="d-flex justify-content-center align-items-center">	  
					<fieldset class="groupbox-border"> 
					
					   <legend class="groupbox-border justify-content-center "> QC &nbsp;&nbsp;&nbsp;&nbsp;
   
						<button class="btn btn-secondary btn-sm" onclick="location.href='list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$check?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&scale=<?=$scale?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&scale=<?=$scale?>&buttonval=<?=$buttonval?>&stable=1';" > 목록 </button>&nbsp;
						<button class="btn btn-secondary btn-sm" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&scale=<?=$scale?>&buttonval=<?=$buttonval?>&stable=1';" > 수정 </button>&nbsp;						
						<button class="btn btn-danger btn-sm" onclick="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>&check=<?=$check?>&scale=<?=$scale?>&buttonval=<?=$buttonval?>')">  삭제 </button>&nbsp;						

                        <button class="btn btn-dark btn-sm" onclick="location.href='write_form.php';" > 글쓰기 </button>&nbsp;
                        <button class="btn btn-primary btn-sm" onclick="location.href='copy_data.php?mode=copy&num=<?=$num?>&page=<?=$page?>&scale=<?=$scale?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&scale=<?=$scale?>&buttonval=<?=$buttonval?>&stable=1';" > 데이터 복사 </button>&nbsp;
                        <button class="btn btn-success btn-sm" onclick="window.open('transform.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>','출고증 인쇄','left=100,top=100, scrollbars=yes, toolbars=no,width=1500,height=700');" > 출고증 인쇄 </button>&nbsp;&nbsp;   											   
                        <button id="QCBtn"  type="button" onclick="viewQC();"  class="btn btn-outline-danger btn-sm"  > QC 공정표 </button>&nbsp;&nbsp;   											   
					   
					   </legend> 	
		</fieldset>
		</div>
 </body>
</html>    

 <script language="javascript">
 
 $(document).ready(function(){
 		
	$("#closeBtn").click(function(){    // 저장하고 창닫기	
	   self.close();
	});		
	$("#closeBtn2").click(function(){    // 저장하고 창닫기	
	   self.close();
	});	


});	


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

</script>