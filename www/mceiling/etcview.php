<meta charset="utf-8">
  <style> 
#panel, #flip {
  padding: 3px;
  text-align: left;
  color:brown;
  border: solid 1px #c3c3c3;
}

#panel {
  padding: 40px;
  display: none;
}

#addpanel, #addflip {
  padding: 3px;
  text-align: center;
  color:white;
  background-color:grey;
  border: solid 1px #c3c3c3;
}

#addpanel {
  padding: 30px;
  display: none;
}

</style>
 <?php
 session_start(); 
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"];
 
 $file_dir = '../uploads/'; 
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 

 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용  
 
	 
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
  
   if(isset($_REQUEST["scale"]))   
	 $scale=$_REQUEST["scale"];
    else
		  $scale=30;	  
  
if(isset($_REQUEST["cursort"])) 
	 $cursort=$_REQUEST["cursort"]; // 미실측리스트
   else
	    $cursort=$_POST["cursort"]; // 미실측리스트
		
if($cursort=='')		
	  $cursort='0';		  


if(isset($_REQUEST["sortof"])) 
	 $sortof=$_REQUEST["sortof"]; // 미실측리스트
   else
	if(isset($_POST["sortof"]))   
         $sortof=$_POST["sortof"]; // 미실측리스트
	 else
		 $sortof='0';		 
	 
 if(isset($_REQUEST["stable"])) 
	 $stable=$_REQUEST["stable"]; // 미실측리스트
   else
	if(isset($_POST["stable"]))   
         $stable=$_POST["stable"]; // 미실측리스트
	 else
		 $stable='0';	
	 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.ceiling where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
    $num=$row["num"];

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
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $delicompany=$row["delicompany"];
			  $delivery=$row["delivery"];
			  $delipay=$row["delipay"];
			  
			  $workday=$row["workday"];
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];

			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
			  
			  $type=$row["type"];			  
			  $inseung=$row["inseung"];			  
			  $su=$row["su"];			  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			  $air_su=$row["air_su"];			  
			  $car_insize=$row["car_insize"];			  
			  $order_com1=$row["order_com1"];			  
			  $order_text1=$row["order_text1"];			  
			  $order_com2=$row["order_com2"];			  
			  $order_text2=$row["order_text2"];			  
			  $order_com3=$row["order_com3"];			  
			  $order_text3=$row["order_text3"];			  
			  $order_com4=$row["order_com4"];			  
			  $order_text4=$row["order_text4"];			  
			  $lc_draw=$row["lc_draw"];			  
			  $lclaser_com=$row["lclaser_com"];			  
			  $lclaser_date=$row["lclaser_date"];			  
			  $lcbending_date=$row["lcbending_date"];			  
			  $lcwelding_date=$row["lcwelding_date"];			  
			  $lcpainting_date=$row["lcpainting_date"];			  
			  $lcassembly_date=$row["lcassembly_date"];			  
			  $main_draw=$row["main_draw"];			  
			  $eunsung_make_date=$row["eunsung_make_date"];			  
			  $etclaser_date=$row["etclaser_date"];			  
			  $mainbending_date=$row["mainbending_date"];			  
			  $mainwelding_date=$row["mainwelding_date"];			  
			  $mainpainting_date=$row["mainpainting_date"];			  
			  $mainassembly_date=$row["mainassembly_date"];	

			  $etclaser_date=$row["etclaser_date"];			  
			  $etcbending_date=$row["etcbending_date"];			  
			  $etcwelding_date=$row["etcwelding_date"];			  
			  $etcpainting_date=$row["etcpainting_date"];			  
			  $etcassembly_date=$row["etcassembly_date"];	

			  
			  $memo2=$row["memo2"];		
			  
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$orderday) {
                            $date_font="red";
						}
			  $date_font1="black";  //  납기일자 색상으로 표기
			  if($nowday==$workday) {
                            $date_font1="blue";
						}
					
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
  
  			   $main_draw_arr="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,0,10,"utf-8");		    
			     elseif($bon_su<1) $main_draw_arr= "X";		    
   
   		        $lc_draw_arr="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,0,10,"utf-8") ;
			     elseif($lc_su<1) $lc_draw_arr = "X";	
			  if($type=='011'||$type=='012'|| $type=='013D'||$type=='025'||$type=='017'||$type=='014')
			                         $lc_draw_arr = "X";	
  
   
   if((int)$etc_su>0) {
        $etclaser_date =iconv_substr($etclaser_date,5,5,"utf-8");
        $etcbending_date =iconv_substr($etcbending_date,5,5,"utf-8");
        $etcwelding_date =iconv_substr($etcwelding_date,5,5,"utf-8");
        $etcpainting_date =iconv_substr($etcpainting_date,5,5,"utf-8");
        $etcassembly_date =iconv_substr($etcassembly_date,5,5,"utf-8");		
				}
	  else
	  {
	    $etclaser_date ="X";
        $etcbending_date = "X";
        $etcwelding_date = "X";
        $etcpainting_date ="X";
        $etcassembly_date ="X";
	  }
		
			  $workplacename = "(".$secondord .")". $workplacename;			
   
 ?>
 <!DOCTYPE HTML>
 <html>
 <head> 
 <meta charset="utf-8">

 <title> 미래기업 통합정보시스템 기타(판넬 등) 조회/수정 </title>
  </head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>

<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>

<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>

<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>   
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="../css/partner.css" type="text/css" />
 
		 
<body >
 <div  class="container-fluid">
 <div id="top-menu">
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
			 <div id="vacancy" style="display:none">  </div>
			<div class="row">
           <div class="col-6"> 
		         <h3 class="display-5 font-center text-left"> 
	<?=$_SESSION["name"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>
</h3>
</div> </div> 
  <br>
	  <button  type="button" class="btn btn-primary btn-lg btn-lg " onclick="location.href='../mceiling/etclist.php?cursort=<?=$cursort?>';"> 이전화면 이동  </button>&nbsp;&nbsp;&nbsp;
      <div class="clear"></div>
  <br>  
  
			<div class="row">
		         <h2 class="display-3 text-left"  > 		   
				 <div id="flip"> &nbsp;&nbsp;&nbsp; 기타(판넬 등) 조회/수정 
				 		 <?php  
		             {
		              //  print   ' <button type="button" class="btn btn-Link btn-lg btn-lg" onclick="dodata_all(' . "" . ');"> 전체입력  </button>  &nbsp;&nbsp;&nbsp;&nbsp;';
		              //  print   ' <button type="button" class="btn btn-Light btn-lg btn-lg"  onclick="dodatadel_all(' . "" . ');"> 전체삭제  </button> ';
					 }
						?>
				 </div> 				
               			  </h2>
</div>	  
		      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
	   <input type="hidden" id="check_draw" name="check_draw" value="<?=$check_draw?>" size="1" > 	
	   <input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="1" > 	
	   
	
		       <div class="row">   <h1 class="display-5 font-center text-center"> <span style="color:grey;"> 현장명 :     </span>     <?=$workplacename?>	 </h1>      </div>	  	
		       <div class="row">   <h1 class="display-5 font-center text-center"> <span style="color:grey;"> 타입 :   </span>     <?=$type?>	 </h1>      </div>	  	
		       <div class="row">   <h1 class="display-5 font-center text-center"> <span style="color:grey;"> 메모1 :   </span>     <?=$memo?>	 </h1>      </div>	  	
		       <div class="row">   <h1 class="display-5 font-center text-center"> <span style="color:grey;"> 발주접수일 :     </span>     <?=$orderday?>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </h1>      
									<h1 class="display-5 font-center text-center"> <span style="color:red;"> 납기일 :     </span>   <span style="color:grey;">  <?=$deadline?>	</span> </h1>      </div>	  	
		       <div class="row">   <h1 class="display-5 font-center text-center"> <span style="color:grey;"> 본천장설계 :     </span>     <?=$main_draw_arr?>   &nbsp;&nbsp;&nbsp; </h1>      
									<h1 class="display-5 font-center text-center"> <span style="color:grey;"> LC설계 :     </span>    <?=$lc_draw_arr?>	</h1>      </div>	  	

			       <div class="row">   <h1 class="display-5 font-center text-center"> <span style="color:grey;"> 결합단위(SET)     </span>     <?=$su?>   &nbsp;&nbsp;&nbsp; </h1>      
							<?php
							  if((int)$bon_su>0)
							    print '							<h1 class="display-5 font-center text-center"> <span style="color:grey;">   본천장 수량 :    </span> ' .   $bon_su	. ' &nbsp;&nbsp;&nbsp;</h1>  ';
                              if((int)$lc_su>0)								
								print '	  <h1 class="display-5 font-center text-center"> <span style="color:grey;"> L/C수량 :     </span>' . $lc_su .'	</h1>    &nbsp;&nbsp;&nbsp;     ';
                              if((int)$etc_su>0)																
								print '	<h1 class="display-5 font-center text-center"> <span style="color:grey;"> 기타 수량 :      </span>' . $etc_su . '	</h1>    &nbsp;&nbsp;&nbsp;    ';
                              if((int)$air_su>0)																								
								print '	<h1 class="display-5 font-center text-center"> <span style="color:grey;"> 공기청정기 :    </span>' . $air_su . '	</h1>  &nbsp;&nbsp;&nbsp;    ';
									?>
									</div>   
									
		<div class="row">  &nbsp;&nbsp;&nbsp; </div>	
		<div class="row">  &nbsp;&nbsp;&nbsp; </div>	
								   
	    <div class="row">   <h1 class="display-3 font-center text-center"> <span style="color:green;"> 기타(판넬 등) 제조현황     </span>  </h1>      </div>	 
		
		 <div class="row">   <h2 class="display-4 font-center text-center"> 기타 laser완료 : <input type="text" name=etclaser_date  id=etclaser_date  value="<?=$etclaser_date?>" size="7">  </h2>  &nbsp;&nbsp;&nbsp;
		 <?php  if($etclaser_date!='X') 
		             {
		                print   ' <button type="button" class="btn btn-primary btn-lg btn-lg" onclick="dodata(' . "'etclaser_date'" . ');"> 완료  </button>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                print   ' <button type="button" class="btn btn-danger btn-lg btn-lg" onclick="dodatadel(' . "'etclaser_date'" . ');"> 삭제  </button> ';
					 }
						?>
		 
		 
		 </div> 		        					
		 <div class="row">   <h1 class="display-4 font-center text-center"> 기타 절곡완료 : <input type="text" name=etcbending_date  id=etcbending_date  value="<?=$etcbending_date?>" size="7">  </h1>  &nbsp;&nbsp;&nbsp;
		 <?php  if($etcbending_date!='X') 
		             {
		                print   ' <button type="button" class="btn btn-primary btn-lg btn-lg" onclick="dodata(' . "'etcbending_date'" . ');"> 완료  </button>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                print   ' <button type="button" class="btn btn-danger btn-lg btn-lg" onclick="dodatadel(' . "'etcbending_date'" . ');"> 삭제  </button>  ';
					 }
						?>
		 
		 
		 </div> 		        						        					
		 <div class="row">   <h1 class="display-4 font-center text-center"> 기타 제관완료 : <input type="text" name=etcwelding_date  id=etcwelding_date  value="<?=$etcwelding_date?>" size="7">  </h1>  &nbsp;&nbsp;&nbsp;
		 <?php  if($etcwelding_date!='X') 
		             {
		                print   ' <button type="button" class="btn btn-primary btn-lg btn-lg" onclick="dodata(' . "'etcwelding_date'" . ');"> 완료  </button>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                print   ' <button type="button" class="btn btn-danger btn-lg btn-lg"  onclick="dodatadel(' . "'etcwelding_date'" . ');"> 삭제  </button> ';
					 }
						?>
		 
		 
		 </div> 		        						        					
		 <div class="row">   <h1 class="display-4 font-center text-center"> 기타 도장완료 : <input type="text" name=etcpainting_date  id=etcpainting_date  value="<?=$etcpainting_date?>" size="7">  </h1>  &nbsp;&nbsp;&nbsp;
		 <?php  if($etcpainting_date!='X') 
		             {
		                print   ' <button type="button" class="btn btn-primary btn-lg btn-lg" onclick="dodata(' . "'etcpainting_date'" . ');"> 완료  </button>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                print   ' <button type="button" class="btn btn-danger btn-lg btn-lg" onclick="dodatadel(' . "'etcpainting_date'" . ');"> 삭제  </button> ';
					 }
						?>
		 
		 
		 </div> 		        						        					
		 <div class="row">   <h1 class="display-4 font-center text-center"> 기타 조립완료 : <input type="text" name=etcassembly_date  id=etcassembly_date  value="<?=$etcassembly_date?>" size="7">  </h1>  &nbsp;&nbsp;&nbsp;
		 <?php  if($etcassembly_date!='X') 
		             {
		                print   ' <button type="button" class="btn btn-primary btn-lg btn-lg" onclick="dodata(' . "'etcassembly_date'" . ');"> 완료  </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                print   ' <button type="button" class="btn btn-danger btn-lg btn-lg"  onclick="dodatadel(' . "'etcassembly_date'" . ');"> 삭제  </button> ';
					 }
						?>
		 
		 
		 </div> 		        				

		 	       <div class="row">  &nbsp;&nbsp;&nbsp; </div>	

		          						 
									
	       <div class="row">  &nbsp;&nbsp;&nbsp; </div>	
	   <div class="row">    <h2 class="display-3 font-center text-center">    <div id="addflip"> 추가정보 보기 </h2>      </div>	</div>
	  <div id="addpanel"> 
	       <div class="row">   <h2 class="display-5 font-center text-center">          현장주소 : <?=$address?>   </h2>      </div>	
	       <div class="row">  <h2 class="display-5 font-center text-center">               제품출고일 : <?=$workday?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               원  청 : <?=$firstord?>  </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               담당 : <?=$firstordman?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               연락처 :<?=$firstordmantel?>   </h2>      </div>	<br>
	       <div class="row">   <h2 class="display-5 font-center text-center">               발주처 : <?=$secondord?>  </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               담당 : <?=$secondordman?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               연락처 :<?=$secondordmantel?>   </h2>      </div>	<br>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               운반비내역 :<?=$delivery?> <?=$delipay?>   </h2>      </div>	<br>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               담당 : <?=$chargedman?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               연락처 :<?=$chargedmantel?>   </h2>      </div>	<br>	
	       <div class="row">   <h2 class="display-5 font-center text-center">               타입 :<?=$type?>    &nbsp;&nbsp;&nbsp; 인승 : <?=$inseung?>  </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">                car insize  :<?=$car_insize?>    &nbsp;&nbsp;&nbsp;   </h2>      </div>	


	       <div class="row">   <h2 class="display-5 font-center text-center">            발주1 : <?=$order_com1?>    &nbsp;&nbsp;&nbsp;   <?=$order_text1?>    &nbsp;&nbsp;&nbsp; <?=$order_date1?>    &nbsp;&nbsp;&nbsp; <?=$order_input_date1?>    &nbsp;&nbsp;&nbsp; </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">            발주2 : <?=$order_com2?>    &nbsp;&nbsp;&nbsp;   <?=$order_text2?>    &nbsp;&nbsp;&nbsp; <?=$order_date2?>    &nbsp;&nbsp;&nbsp; <?=$order_input_date2?>    &nbsp;&nbsp;&nbsp; </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">            발주3 : <?=$order_com3?>    &nbsp;&nbsp;&nbsp;   <?=$order_text3?>    &nbsp;&nbsp;&nbsp; <?=$order_date3?>    &nbsp;&nbsp;&nbsp; <?=$order_input_date3?>    &nbsp;&nbsp;&nbsp; </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">            발주4 : <?=$order_com4?>    &nbsp;&nbsp;&nbsp;   <?=$order_text4?>    &nbsp;&nbsp;&nbsp; <?=$order_date4?>    &nbsp;&nbsp;&nbsp; <?=$order_input_date4?>    &nbsp;&nbsp;&nbsp; </h2>      </div>	

	       <div class="row">   <h2 class="display-5 font-center text-center">            재질1 : <?=$material2?>    &nbsp;&nbsp;&nbsp;   <?=$material1?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">            재질2 : <?=$material4?>    &nbsp;&nbsp;&nbsp;   <?=$material3?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">            재질3 : <?=$material6?>    &nbsp;&nbsp;&nbsp;   <?=$material5?>   </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">             비고1 :  <?=$memo?>    &nbsp;&nbsp;&nbsp;  </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">             비고2 :  <?=$memo2?>    &nbsp;&nbsp;&nbsp;  </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">            청구일자 :   <?=$demand?>    &nbsp;&nbsp;&nbsp;  </h2>      </div>	
	
	       <div class="row">  &nbsp;&nbsp;&nbsp; </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">          자료 최초등록자 :   <?=$first_writer?>    &nbsp;&nbsp;&nbsp;  </h2>      </div>	
	       <div class="row">   <h2 class="display-5 font-center text-center">          자료 수정기록 :  <?=$update_log?>    &nbsp;&nbsp;&nbsp;  </h2>      </div>	
	 
	
		   	</div>	 <!-- end of addpanel-->
	   </div>	    		
   
	   </div> 
	
 </body>
</html>    

 <script language="javascript">
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

function del_below()
     {
     if(confirm("초기화한 자료는 복구할 방법이 없습니다.\n\n정말 초기화 하시겠습니까?")) {
		document.getElementById("asday").value = "" ;
		document.getElementById("aswriter").value = "" ;

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
	 
 function displayoutputlist(){
	 alert("dkdkdkd");
   $("#displayoutput").show(); 
   $("#displayoutput").load("./outputetclist.php");	 	 
		 
	 }
function check_alert()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
			tmp="../load_alert.php";			
			$("#vacancy").load(tmp);     
		    var voc_alert=$("#voc_alert").val();	 
		    var ma_alert=$("#ma_alert").val();	 
 		if(name=='김진억' && voc_alert=='1') {			
			alertify.alert('<H1> 현장VOC 도착 알림</H1>', '<h1> 김진억 이사님 <br> <br> 현장VOC가 접수되었습니다. 확인 후 조치바랍니다. </h1>'); 			
			tmp="../save_alert.php?voc_alert=0" + "&ma_alert=" + ma_alert;	
			$("#voc_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}
 		if(name=='조경임' && ma_alert=='1') {			
			alertify.alert('<h1> 발주서 접수 알림 </h1>', '<h1> 조과장님 <br> <br> 발주서가 접수되었습니다. 내역 확인 후 발주해 주세요. </h1>'); 			
			tmp="../save_alert.php?ma_alert=0" + "&voc_alert=" + voc_alert;	
			$("#ma_alert").val('0');				
			$("#vacancy").load(tmp);   			
											}											
}


// 5초마다 알람상황을 체크합니다.
	var timer;
	timer=setInterval(function(){
		check_alert();
	},5000); 
	
  $(document).ready(function(){
  
	$("#addflip").click(function(){
    $("#addpanel").slideToggle();
  });  
  
  $("#addpanel").click(function(){
    $("#addpanel").slideUp("slow");
  });  
});


function dodata(anyone) {
	
	var anyone;
    var id="#" + anyone ;	
    var num = <?php echo $num; ?>; 
	var tmp="./insert.php?num="+ num +"&data=" + anyone ;	
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();


		if(dd<10) {
			dd='0'+dd;
		} 

		if(mm<10) {
			mm='0'+mm;
		} 
today = yyyy+'-'+mm+'-'+dd;
			$("#vacancy").load(tmp);     
		    $(id).val(today);
}


function dodatadel(anyone) {
	
	var anyone;
    var id="#" + anyone ;	
    var num = <?php echo $num; ?>; 
	var tmp="./insert.php?num="+ num +"&deldata=" + anyone ;	
	
			$("#vacancy").load(tmp);     
		    $(id).val('');
}

function dodata_all(anyone) {	
	var anyone;
    var id;	
    var num = <?php echo $num; ?>; 
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
    var tmp;	
	var yyyy = today.getFullYear();
	var arr=[];
	
		if(dd<10) {
			dd='0'+dd;
		} 

		if(mm<10) {
			mm='0'+mm;
		}        
	 today = yyyy+'-'+mm+'-'+dd;
	 
	//	arr[0]='etclaser_date';  임시로 만든것

 for(i=0;i<5;i++) {
	        tmp="./insert.php?num="+ num +"&data=" + arr[i] ;	
			$("#vacancy").load(tmp);     
			id="#" + arr[i];	
		    $(id).val(today);
	   }
}


function dodatadel_all(anyone) {

}
 	
 	 
</script>
