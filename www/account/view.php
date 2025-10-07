 <meta charset="utf-8">
 
 <?php
 session_start(); 
 $file_dir = '../uploads/'; 
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
 $yearcheckbox=$_REQUEST["yearcheckbox"];   // 년도 체크박스
 $year=$_REQUEST["year"];   // 년도 체크박스
if(isset($_REQUEST["asprocess"]))
	 $asprocess=$_REQUEST["asprocess"];	 
	      else 
	          $asprocess="전체";	

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 
  
if(isset($_REQUEST["scale"])) 
     $scale=$_REQUEST["scale"];  // 화면표시 목록수 
  
 $all_check=$_REQUEST["all_check"];	 // 시공완료일 무시 전체선택 
  
require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from chandj.work where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
 	
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];
     $item_hit     = $row["hit"];
 
     $image_name[0]   = $row["file_name_0"];
     $image_name[1]   = $row["file_name_1"];
     $image_name[2]   = $row["file_name_2"];
 
     $image_copied[0] = $row["file_copied_0"];
     $image_copied[1] = $row["file_copied_1"];
     $image_copied[2] = $row["file_copied_2"];
 
     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date,0,10);
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $item_content = $row["content"];
     $is_html      = $row["is_html"];

  $content="";
  $sum=[];
  $condate=$row["condate"];
  $condate1=$row["condate1"];
  $condate2=$row["condate2"];  
  $estimate1=$row["estimate1"];
  $estimate2=$row["estimate2"];
  $estimate3=$row["estimate3"];
  $estimate4=$row["estimate4"];
  $sum[0]=$row["estimate1"];
  $sum[1]=$row["estimate2"];
  $sum[2]=$row["estimate3"];
  $sum[3]=$row["estimate4"];  

for($i=0;$i<=3;$i++)
{
 if($sum[$i]!="") $sumhap=preg_replace("/[^0-9]*/s","",$sum[$i]);
}   
  
  $bill1=$row["bill1"];
  $bill2=$row["bill2"];
  $bill3=$row["bill3"];
  $bill4=$row["bill4"];
  $bill5=$row["bill5"];
  $bill6=$row["bill6"];
  $billdate1=$row["billdate1"];
  $billdate2=$row["billdate2"];
  $billdate3=$row["billdate3"];
  $billdate4=$row["billdate4"];
  $billdate5=$row["billdate5"];
  $billdate6=$row["billdate6"];
  $deposit1=$row["deposit1"];
  $deposit2=$row["deposit2"];
  $deposit3=$row["deposit3"];
  $deposit4=$row["deposit4"];
  $deposit5=$row["deposit5"];
  $deposit6=$row["deposit6"];
  $depositdate1=$row["depositdate1"];
  $depositdate2=$row["depositdate2"];
  $depositdate3=$row["depositdate3"];
  $depositdate4=$row["depositdate4"];
  $depositdate5=$row["depositdate5"];
  $depositdate6=$row["depositdate6"];  
  
  $claimamount1=$row["claimamount1"];
  $claimamount2=$row["claimamount2"];
  $claimamount3=$row["claimamount3"];
  $claimamount4=$row["claimamount4"];
  $claimamount5=$row["claimamount5"];
  $claimamount6=$row["claimamount6"];
  $claimamount7=$row["claimamount7"];
  
  $claimfix1=$row["claimfix1"];
  $claimfix2=$row["claimfix2"];
  $claimfix3=$row["claimfix3"];
  $claimfix4=$row["claimfix4"];
  $claimfix5=$row["claimfix5"];
  $claimfix6=$row["claimfix6"];
  $claimfix7=$row["claimfix7"];
  
  $claimdate1=$row["claimdate1"];
  $claimdate2=$row["claimdate2"];
  $claimdate3=$row["claimdate3"];
  $claimdate4=$row["claimdate4"];
  $claimdate5=$row["claimdate5"];
  $claimdate6=$row["claimdate6"];
  
  $claimbalance1=$row["claimbalance1"];
  $claimbalance2=$row["claimbalance2"];
  $claimbalance3=$row["claimbalance3"];
  $claimbalance4=$row["claimbalance4"];
  $claimbalance5=$row["claimbalance5"];
  $claimbalance6=$row["claimbalance6"];  
  $claimbalance7=$row["claimbalance7"];  
  $claimperson=$row["claimperson"];  
  $claimtel=$row["claimtel"];  
  
  $receivable=$row["receivable"];
  $totalbill=$row["totalbill"];
  $asman=$row["asman"];
  $asendday=$row["asendday"];
  $asproday=$row["asproday"];
  $accountnote =$row["accountnote"];

  $workplacename=$row["workplacename"];
  $chargedperson=$row["chargedperson"];
  $address=$row["address"];
  $firstord=$row["firstord"];
  $firstordman=$row["firstordman"];
  $firstordmantel=$row["firstordmantel"];
  $secondord=$row["secondord"];
  $secondordman=$row["secondordman"];
  $secondordmantel=$row["secondordmantel"];
  $worklist=$row["worklist"];
  $motormaker=$row["motormaker"];
  $power=$row["power"];
  $workday=$row["workday"];
  $worker=$row["worker"];
  $endworkday=$row["endworkday"];
  $cableday=$row["cableday"];
  $cablestaff=$row["cablestaff"];
  $endcableday=$row["endcableday"];
  $asday=$row["asday"];
  $asorderman=$row["asorderman"];
  $asordermantel=$row["asordermantel"];
  $aslist=$row["aslist"];
  $asresult=$row["asresult"];
  $ashistory=$row["ashistory"];
  $comment=$row["comment"];
  $work_state=$row["work_state"];
  $as_refer=$row["as_refer"];
  $change_worklist=$row["change_worklist"];
  $checkbox=$row["checkbox"];
  $checkstep=$row["checkstep"];
  $asfee=$row["asfee"];
  $asfee_estimate=$row["asfee_estimate"];
  $promiseday=$row["promiseday"];
  $as_check=$row["as_check"];
  $outputmemo=$row["outputmemo"];
  $aswriter=$row["aswriter"];
  $setdate=$row["setdate"];  
   
  $subject=$workplacename;	 	
  
  $sumbill=[];
  $sumdeposit=[];
  $sumbill[0]=preg_replace("/[^0-9]*/s","",$bill1); 
  $sumbill[1]=preg_replace("/[^0-9]*/s","",$bill2);
  $sumbill[2]=preg_replace("/[^0-9]*/s","",$bill3);
  $sumbill[3]=preg_replace("/[^0-9]*/s","",$bill4);
  $sumbill[4]=preg_replace("/[^0-9]*/s","",$bill5);
  $sumbill[5]=preg_replace("/[^0-9]*/s","",$bill6);
  $sumdeposit[0]=preg_replace("/[^0-9]*/s","",$deposit1);
  $sumdeposit[1]=preg_replace("/[^0-9]*/s","",$deposit2);
  $sumdeposit[2]=preg_replace("/[^0-9]*/s","",$deposit3);
  $sumdeposit[3]=preg_replace("/[^0-9]*/s","",$deposit4);
  $sumdeposit[4]=preg_replace("/[^0-9]*/s","",$deposit5);
  $sumdeposit[5]=preg_replace("/[^0-9]*/s","",$deposit6); 
  
  $total_bill=0;
  $total_deposit=0;
  for($i=0;$i<=5;$i++)
  {
	  $total_bill +=$sumbill[$i];
	  $total_deposit +=$sumdeposit[$i];
  }
$total_receivable=$sumhap-$total_deposit;  
$total_receivable=number_format($total_receivable);
//$vat_total_bill=$total_bill+$total_bill*0.1;
$total_bill=number_format($total_bill);
//$vat_total_deposit=$total_deposit+$total_deposit*0.1;
$total_deposit=number_format($total_deposit);


		  if($workday!="0000-00-00") $workday = date("Y-m-d", strtotime( $workday) );     //  date형태로 저장된 것들만 이렇게 표시함.
					else $workday="";
			 if($cableday!="0000-00-00") $cableday = date("Y-m-d", strtotime( $cableday) );
					else $cableday="";	 
			  if($asday!="0000-00-00") $asday = date("Y-m-d", strtotime( $asday) );
					else $asday="";  
			  if($condate!="0000-00-00") $condate = date("Y-m-d", strtotime($condate) );
						else $condate="";
		 if($condate1!="0000-00-00") $condate1 = date("Y-m-d", strtotime($condate1));
					else $condate1="";
			if($condate2!="0000-00-00") $condate2 = date("Y-m-d", strtotime($condate2));
						else $condate2="";						
			  if($billdate1!="0000-00-00") $billdate1 = date("Y-m-d", strtotime($billdate1) );
						else $billdate1="";
			 if($billdate2!="0000-00-00") $billdate2 = date("Y-m-d", strtotime($billdate2) );
						else $billdate2="";
			 if($billdate3!="0000-00-00") $billdate3 = date("Y-m-d", strtotime($billdate3) );
						else $billdate3="";
			 if($billdate4!="0000-00-00") $billdate4 = date("Y-m-d", strtotime($billdate4) );
						else $billdate4="";
			 if($billdate5!="0000-00-00") $billdate5 = date("Y-m-d", strtotime($billdate5) );
						else $billdate5="";
			 if($billdate6!="0000-00-00") $billdate6 = date("Y-m-d", strtotime($billdate6) );
						else $billdate6="";
			 if($endworkday!="0000-00-00")  $endworkday = date("Y-m-d", strtotime($endworkday) );     
						else $endworkday="";
			if($endcableday!="0000-00-00") $endcableday = date("Y-m-d", strtotime($endcableday) );
						else $endcableday="";

			 if($depositdate1!="0000-00-00") $depositdate1 = date("Y-m-d", strtotime($depositdate1) );
						else $depositdate1="";
			 if($depositdate2!="0000-00-00") $depositdate2 = date("Y-m-d", strtotime($depositdate2) );
						else $depositdate2="";
			 if($depositdate3!="0000-00-00") $depositdate3 = date("Y-m-d", strtotime($depositdate3) );
						else $depositdate3="";
			 if($depositdate4!="0000-00-00")  $depositdate4 = date("Y-m-d", strtotime($depositdate4) );
						else $depositdate4="";
			 if($depositdate5!="0000-00-00") $depositdate5 = date("Y-m-d", strtotime($depositdate5) );
						else $depositdate5="";
			 if($depositdate6!="0000-00-00") $depositdate6 = date("Y-m-d", strtotime($depositdate6) );
						else $depositdate6="";
			 if($asproday!="0000-00-00") $asproday = date("Y-m-d", strtotime($asproday) );
						else $asproday="";
			 if($asendday!="0000-00-00") $asendday = date("Y-m-d", strtotime($asendday) );
						else $asendday="";

             if($claimdate1!="0000-00-00")$claimdate1 = date("Y-m-d", strtotime($claimdate1) );
						else $claimdate1="";
			 if($claimdate2!="0000-00-00") $claimdate2 = date("Y-m-d", strtotime($claimdate2) );
						else $claimdate2="";
			 if($claimdate3!="0000-00-00") $claimdate3 = date("Y-m-d", strtotime($claimdate3) );
						else $claimdate3="";
			 if($claimdate4!="0000-00-00") $claimdate4 = date("Y-m-d", strtotime($claimdate4) );
						else $claimdate4="";
			 if($claimdate5!="0000-00-00") $claimdate5 = date("Y-m-d", strtotime($claimdate5) );
						else $claimdate5="";
						
			 if($claimdate6!="0000-00-00") $claimdate6 = date("Y-m-d", strtotime($claimdate6) );
						else $claimdate6="";						
			 if($promiseday!="0000-00-00") $promiseday = date("Y-m-d", strtotime($promiseday));
						else $promiseday="";	
			 if($setdate!="0000-00-00") $setdate = date("Y-m-d", strtotime($setdate));
						else $setdate="";						

      
     if ($is_html!="y"){
	$item_content = str_replace(" ", "&nbsp;", $item_content);
     	$item_content = str_replace("\n", "<br>", $item_content);
     }	
 
     $new_hit = $item_hit + 1;
     try{
       $pdo->beginTransaction(); 
       $sql = "update chandj.work set hit=? where num=?";   // 조회수 증가
       $stmh = $pdo->prepare($sql);  
       $stmh->bindValue(1, $new_hit, PDO::PARAM_STR);      
       $stmh->bindValue(2, $num, PDO::PARAM_STR);           
       $stmh->execute();
       $pdo->commit(); 
       } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
      }
	  	 $img_name0 = $image_name[0];
	     $img_copied0 = "../uploads/".$image_copied[0]; 	
	     $img_name1 = $image_name[1];
	     $img_copied1 = "../uploads/".$image_copied[1]; 	
	     $img_name2 = $image_name[2];
	     $img_copied2 = "../uploads/".$image_copied[2]; 
 ?>
 <!DOCTYPE HTML>
 <html><head> 
 <meta charset="utf-8">
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/board4.css">
 <link  rel="stylesheet" type="text/css" href="../css/work.css">

 <title> 주일기업 통합정보시스템 </title>
 </head>
 <body>
 <div id="wrap">
  <div id="header"><?php include "../lib/top_login2.php"; ?></div> 
  <div id="menu"><?php include "../lib/top_menu2.php"; ?></div> 
  <div id="content">
 <div id="work_col2">
  <div id="work_title"><img src="../img/title_work.gif"></div>      
        <div id="estimate_title">
           <img src="../img/estimate_title.png">
	</div>  
	<input id=session_level name=session_level type="hidden" value='<?=$_SESSION["level"]?>'>  
	  
	<div id="estimate_text1"> 
		 <div class="sero1"> 견 적 일  :</div> 
         <div class="sero2"><input type="text" name="condate" value="<?=$condate?>" size="14" placeholder="계약 or 견적일자"  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div> <br> <br>
	     <div class="sero1"> 견적 금액 : </div> 
         <div class="sero2"><input type="text" name="estimate1" value="<?=$estimate1?>" size="8" placeholder="최초금액" onkeyup="inputNumberFormat(this)" disabled /></div> <br> <br>   
         <div class="sero4444" >   
			 <a href="<?=$img_copied0?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name0?> </b> </a>
			 </a> </div> <br><br>
		 <div class="sero1"> 1차 변경일: </div> 
         <div class="sero2"><input type="text" name="condate1" value="<?=$condate1?>" size="14" placeholder="1차변경일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div><br> <br>		 
         <div class="sero1"> 1차 변경금액: </div> 
         <div class="sero2"><input type="text" name="estimate2" value="<?=$estimate2?>" size="8" placeholder="1차변경금액" onkeyup="inputNumberFormat(this)" disabled /></div><br> <br>
         <div class="sero4444" >   
			 <a href="<?=$img_copied1?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name1?> </b> </a>
			 </a> </div> <br><br>        
		<div class="sero1"> 2차 변경일: </div> 
         <div class="sero2"><input type="text" name="condate2" value="<?=$condate2?>" size="14" placeholder="2차변경일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div><br> <br>		          
		 <div class="sero1"> 2차 변경금액: </div> 
         <div class="sero2"><input type="text" name="estimate3" value="<?=$estimate3?>" size="8" placeholder="2차변경금액" onkeyup="inputNumberFormat(this)" disabled /></div><br> <br>         
           <div class="sero4444" >   
			 <a href="<?=$img_copied2?>" onclick="window.open(this.href,'파일보기',width=800,height=600); return false;" style="color:blue;"> <b> <?=$img_name2?> </b> </a>
			 </a> </div> <br><br>     
	   <br> 
	  <img src="../img/bill_title.png">	<br>  <br>   <!-- 세금계산서 -->
	  
      <div class="sero8"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;계산서발행일&nbsp;&nbsp;발행금액 &nbsp;&nbsp; 입금일자&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;입금액 </div> <br>
         <div class="sero13"> 1차 : </div> 
		 <div class="sero2"><input type="text" name="billdate1" value="<?=$billdate1?>" style="font-size:10px" size="8" placeholder="1차발행일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
         <div class="sero2"><input type="text" name="bill1" value="<?=$bill1?>" size="8" style="font-size:10px" placeholder="1차계산서" onkeyup="inputNumberFormat(this)"/ disabled ></div> 
		 <div class="sero2"><input type="text" name="depositdate1" value="<?=$depositdate1?>" style="font-size:10px" size="8" placeholder="1차입금일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"  disabled /></div>
         <div class="sero2"><input type="text" name="deposit1" value="<?=$deposit1?>" size="8" style="font-size:10px" placeholder="1차입금액" onkeyup="inputNumberFormat(this)"/ disabled ></div> <br><br>

         <div class="sero13"> 2차 : </div> 
		 <div class="sero2"><input type="text" name="billdate2" value="<?=$billdate2?>" size="8" style="font-size:10px" placeholder="2차발행일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
         <div class="sero2"><input type="text" name="bill2" value="<?=$bill2?>" size="8"  style="font-size:10px" placeholder="2차계산서" onkeyup="inputNumberFormat(this)"/ disabled ></div> 
		 <div class="sero2"><input type="text" name="depositdate2" value="<?=$depositdate2?>" size="8" style="font-size:10px" placeholder="2차입금일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
         <div class="sero2"><input type="text" name="deposit2" value="<?=$deposit2?>" size="8"  style="font-size:10px" placeholder="2차입금액" onkeyup="inputNumberFormat(this)"/ disabled ></div> <br> <br>

         <div class="sero13"> 3차 : </div> 
         <div class="sero2"><input type="text" name="billdate3" value="<?=$billdate3?>" size="8"  style="font-size:10px" placeholder="3차발행일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>	
         <div class="sero2"><input type="text" name="bill3" value="<?=$bill3?>" size="8"  style="font-size:10px" placeholder="3차계산서" onkeyup="inputNumberFormat(this)"/ disabled ></div> 
         <div class="sero2"><input type="text" name="depositdate3" value="<?=$depositdate3?>" size="8"  style="font-size:10px" placeholder="3차입금일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>	
         <div class="sero2"><input type="text" name="deposit3" value="<?=$deposit3?>" size="8"  style="font-size:10px" placeholder="3차입금액" onkeyup="inputNumberFormat(this)" disabled /></div> <br><br>

		 <div class="sero13"> 4차 : </div> 
         <div class="sero2"><input type="text" name="billdate4" value="<?=$billdate4?>" size="8"  style="font-size:10px" placeholder="4차발행일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
         <div class="sero2"><input type="text" name="bill4" value="<?=$bill4?>" size="8"  style="font-size:10px" placeholder="4차계산서" onkeyup="inputNumberFormat(this)" disabled /></div>	
         <div class="sero2"><input type="text" name="depositdate4" value="<?=$depositdate4?>" size="8"  style="font-size:10px" placeholder="4차입금일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
         <div class="sero2"><input type="text" name="deposit4" value="<?=$deposit4?>" size="8"  style="font-size:10px" placeholder="4차입금액" onkeyup="inputNumberFormat(this)" disabled /></div>		 <br><br> 		 		 

         <div class="sero13"> 5차 : </div>   
         <div class="sero2"><input type="text" name="billdate5" value="<?=$billdate5?>" size="8"  style="font-size:10px" placeholder="5차발행일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
		 <div class="sero2"><input type="text" name="bill5" value="<?=$bill5?>" size="8"  style="font-size:10px" placeholder="5차계산서" onkeyup="inputNumberFormat(this)" disabled /></div> 
         <div class="sero2"><input type="text" name="depositdate5" value="<?=$depositdate5?>" size="8"  style="font-size:10px" placeholder="5차입금일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>
		 <div class="sero2"><input type="text" name="deposit5" value="<?=$deposit5?>" size="8"  style="font-size:10px" placeholder="5차입금액" onkeyup="inputNumberFormat(this)" disabled /></div> <br><br> 		          

         <div class="sero13"> 6차 : </div>   
         <div class="sero2"><input type="text" name="billdate6" value="<?=$billdate6?>" size="8"  style="font-size:10px" placeholder="6차발행일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>	 
         <div class="sero2"><input type="text" name="bill6" value="<?=$bill6?>" size="8"  style="font-size:10px" placeholder="6차계산서" onkeyup="inputNumberFormat(this)" disabled /></div> 
         <div class="sero2"><input type="text" name="depositdate6" value="<?=$depositdate6?>" size="8"  style="font-size:10px" placeholder="6차입금일" oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" disabled /></div>	 
         <div class="sero2"><input type="text" name="deposit6" value="<?=$deposit6?>" size="8"  style="font-size:10px" placeholder="6차입금액" onkeyup="inputNumberFormat(this)" disabled /></div> <br><br> 	
         <div class="sero1"> 발행합계   : </div>   
         <div class="sero2"><input type="text" name="totalbill" value="<?=$total_bill?>" size="12" placeholder="계산서 합계" onkeyup="inputNumberFormat(this)"/ disabled ></div> <br><br>
		 <div class="sero1"> 입금합계 : </div>   
         <div class="sero2"><input type="text" name="totaldeposit" value="<?=$total_deposit?>" size="12" placeholder="입금 합계" onkeyup="inputNumberFormat(this)"/ disabled ></div><br><br> 			
		 <div class="sero1"> 미수금 : </div>   
         <div class="sero2"style="color:red; font-size:15px";><b> <?=$total_receivable?></b> </div><br><br> 			

         <br>
		 <div class="sero11" style="color:blue"> <b> 입금약속일 : </b> </div> 
		 <div class="sero111"><input type="text" name="promiseday" value="<?=$promiseday?>" size="14" placeholder="입금약속일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" /></div> <br> <br>  	 
		 
		 <div class="sero222"> 경리부 메모 : </div> <br>  
		 <div class="memo"> <textarea rows="12" cols="32" name="accountnote" placeholder="경리부 메모/노트" disabled
                ><?=$accountnote?></textarea></div> 
	  </div> 
	   </div>
	   
<div id="estimate_text2"> 
	      	<!-- 공사진행현황 -->
	   <div class="sero1"> <img src="../img/work_title.png">  </div> 
	   <div class="sero111"> 
	 <?php
	    if($checkbox!="1") {
			 ?>
			 <input type="checkbox" name=checkbox value="1" disabled > 계약전  </div>
			<?php
             		}    ?>	   
	 <?php
	    if($checkbox=="1") {
			 ?>
			 <input type="checkbox" name=checkbox checked value="1"  disabled > 계약전  </div>
			<?php
             		}    ?>	   					
<div id="write_button_renew"> <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&scale=<?=$scale?>&all_check=<?=$all_check?>"> <img src="../img/list.png"></a>&nbsp;   
					  <?php
						if(isset($_SESSION["userid"])) {
						if($_SESSION["level"]>=1 )
					/* 	if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" ||
							   $_SESSION["level"]==1 )	 */	   
							{
					  ?>
						<a href="write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&scale=<?=$scale?>&all_check=<?=$all_check?>"><img src="../img/modify.png"></a>&nbsp;
						<a href="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>&scale=<?=$scale?>')"><img src="../img/delete.png"></a>&nbsp;
					 <?php  	}
					 ?>
						<a href="write_form.php"><img src="../img/write.png"></a> </div>
	    <br>
	  <div id="title1">		
	  요청사항 선택 : &nbsp;	 
	  <?php
	    if($checkstep==Null) 			 
			$checkstep="없음";
			   ?>
	 <?php
	    if($checkstep=="없음") {
			 ?>
			없음                       <input type="radio" checked name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청" disabled >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청" disabled >	  
			&nbsp;   결선요청<input type="radio" name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" name=checkstep value="견적요청" disabled >	  
			<?php
             		}    ?>
	 <?php
	    if($checkstep=="상담요청") {
			 ?>
			없음                       <input type="radio"  name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" checked name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청" disabled >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청" disabled >	  			
			&nbsp;   결선요청<input type="radio" name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" name=checkstep value="견적요청" disabled >	  			
			<?php
             		}    ?>
	 <?php
	    if($checkstep=="방문요청") {
			 ?>
			없음                       <input type="radio"name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" checked  name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청" disabled >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청" disabled >	  			
			&nbsp;   결선요청<input type="radio" name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" name=checkstep value="견적요청" disabled >	  			
			<?php
             		}    ?>
	 <?php
	    if($checkstep=="실측요청") {
			 ?>
			없음                       <input type="radio"name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio" checked  name=checkstep value="실측요청" disabled >	  
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청" disabled >	  			
			&nbsp;   결선요청<input type="radio" name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" name=checkstep value="견적요청" disabled >	  			
			<?php
             		}    ?>
	 <?php
	    if($checkstep=="발주요청") {
			 ?>
			없음                       <input type="radio"name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio"  name=checkstep value="실측요청" disabled >	  
			&nbsp;   발주요청<input type="radio" checked  name=checkstep value="발주요청" disabled >	  			
			&nbsp;   결선요청<input type="radio" name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" name=checkstep value="견적요청" disabled >	  			
			<?php
             		}    ?>					
	 <?php
	    if($checkstep=="결선요청") {
			 ?>
			없음                       <input type="radio" name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청" disabled >	
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청" disabled >	
			&nbsp;   결선요청<input type="radio" checked name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" name=checkstep value="견적요청" disabled >	  			
			<?php
             		}    ?>		
	 <?php
	    if($checkstep=="견적요청") {
			 ?>
			없음                       <input type="radio" name=checkstep value="없음" disabled >
			&nbsp;   상담요청<input type="radio" name=checkstep value="상담요청" disabled >
			&nbsp;    방문요청<input type="radio" name=checkstep value="방문요청" disabled >
			&nbsp;   실측요청<input type="radio" name=checkstep value="실측요청" disabled >	
			&nbsp;   발주요청<input type="radio" name=checkstep value="발주요청" disabled >				
			&nbsp;   결선요청<input type="radio" name=checkstep value="결선요청" disabled >	  
			&nbsp;   견적요청<input type="radio" checked name=checkstep value="견적요청" disabled >	
			<?php
             		}    ?>						
	  
	  </div>   <!-- 화면상단 요청사항 나타내기 -->				
		
		<br>		
		
       <div class="sero1"> 현장명 : </div> 
         <div class="sero2"><input type="text" name="workplacename" value="<?=$workplacename?>" size="40" placeholder="현장명" required disabled >	</div>
	  <div class="sero11"> 공사담당 : </div> 
         <div class="sero12"><input type="text" name="chargedperson" value="<?=$chargedperson?>" size="10" placeholder="공사담당" disabled >	</div>
	
	<br> <br>   
         <div class="sero1"> 현장주소 : </div> 
         <div class="sero2"><input type="text" name="address" value="<?=$address?>" size="50" placeholder="현장주소" disabled  ></div><br> <br>   <br>
         <div class="sero1"> 건 설 사 : </div> 
         <div class="sero2"><input type="text" name="firstord" value="<?=$firstord?>" size="15" placeholder="건설사" disabled  > </div>
	    <div class="sero33">  담당 : </div> 
	    <div class="sero4"> <input type="text" name="firstordman" value="<?=$firstordman?>" size="10" placeholder="원청담당" disabled ></div>
		    <div class="sero5">  연락처 : </div> 
	    <div class="sero6"> <input type="text" name="firstordmantel" value="<?=$firstordmantel?>" size="14" placeholder="연락번호" disabled ></div> <br> <br>        
	 <div class="sero1"> 발주처 : </div> 
         <div class="sero2"><input type="text" name="secondord" value="<?=$secondord?>" size="15" placeholder="발주처" disabled  > </div>
	    <div class="sero33">  담당 : </div> 
	    <div class="sero4"> <input type="text" name="secondordman" value="<?=$secondordman?>" size="10" placeholder="발주처 담당자" disabled ></div>
		    <div class="sero5">  연락처 : </div> 
	    <div class="sero6"> <input type="text" name="secondordmantel" value="<?=$secondordmantel?>" size="14" placeholder="연락번호"  disabled ></div> <br> <br>   
     <div class="sero13">  발주처 결재 공무담당 : </div>   
	    <div class="sero4"> <input type="text" name="claimperson" value="<?=$claimperson?>" size="10" placeholder="공무담당" disabled ></div>
		    <div class="sero5">  연락처 : </div> 
	    <div class="sero6"> <input type="text" name="claimtel" value="<?=$claimtel?>" size="14"  placeholder="공무전화" disabled ></div> <br> <br>   		     
	    <br> 
					<img src="../img/claim_title.png">	<br>  <br>   <!-- 기성청구 -->
							 <div class="sero8_1"> <b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;청구일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 청구금액 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;확정금액 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;잔 액</b> </div> <br>
							 <div class="sero14"> 1차 : </div> 
							 <div class="sero22"><input type="text" name="claimdate1" value="<?=$claimdate1?>" style="font-size:12px" size="14" placeholder="1차청구일"  disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/></div>
							 <div class="sero22"><input type="text" name="claimamount1" value="<?=$claimamount1?>" size="14" style="font-size:12px" placeholder="1차청구액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimfix1" value="<?=$claimfix1?>" size="14" style="font-size:12px" placeholder="1차확정액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimbalance1" value="<?=$claimbalance1?>" size="14" style="font-size:12px" placeholder="1차잔액"  disabled onkeyup="inputNumberFormat(this)"/></div> <br><br>

							 <div class="sero14"> 2차 : </div> 
							 <div class="sero22"><input type="text" name="claimdate2" value="<?=$claimdate2?>" size="14" style="font-size:12px" placeholder="2차청구일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/></div>
							 <div class="sero22"><input type="text" name="claimamount2" value="<?=$claimamount2?>" size="14"  style="font-size:12px" placeholder="2차청구액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimfix2" value="<?=$claimfix2?>" size="14"  style="font-size:12px" placeholder="2차확정액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimbalance2" value="<?=$claimbalance2?>" size="14"  style="font-size:12px" placeholder="2차잔액"  disabled onkeyup="inputNumberFormat(this)"/></div> <br> <br>

							 <div class="sero14"> 3차 : </div> 
							 <div class="sero22"><input type="text" name="claimdate3" value="<?=$claimdate3?>" size="14"  style="font-size:12px" placeholder="3차청구일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/></div>	
							 <div class="sero22"><input type="text" name="claimamount3" value="<?=$claimamount3?>" size="14"  style="font-size:12px" placeholder="3차청구액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimfix3" value="<?=$claimfix3?>" size="14"  style="font-size:12px" placeholder="3차확정액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimbalance3" value="<?=$claimbalance3?>" size="14"  style="font-size:12px" placeholder="3차잔액"  disabled onkeyup="inputNumberFormat(this)"/></div> <br><br>

							 <div class="sero14"> 4차 : </div> 
							 <div class="sero22"><input type="text" name="claimdate4" value="<?=$claimdate4?>" size="14"  style="font-size:12px" placeholder="4차청구일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/></div>
							 <div class="sero22"><input type="text" name="claimamount4" value="<?=$claimamount4?>" size="14"  style="font-size:12px" placeholder="4차청구액"  disabled onkeyup="inputNumberFormat(this)"/></div>	
							 <div class="sero22"><input type="text" name="claimfix4" value="<?=$claimfix4?>" size="14"  style="font-size:12px" placeholder="4차확정액"  disabled onkeyup="inputNumberFormat(this)"/></div>	
							 <div class="sero22"><input type="text" name="claimbalance4" value="<?=$claimbalance4?>" size="14"  style="font-size:12px" placeholder="4차잔액"  disabled onkeyup="inputNumberFormat(this)"/></div>		 <br><br> 		 		 

							 <div class="sero14"> 5차 : </div>   
							 <div class="sero22"><input type="text" name="claimdate5" value="<?=$claimdate5?>" size="14"  style="font-size:12px" placeholder="5차청구일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/></div>
							 <div class="sero22"><input type="text" name="claimamount5" value="<?=$claimamount5?>" size="14"  style="font-size:12px" placeholder="5차청구액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimfix5" value="<?=$claimfix5?>" size="14"  style="font-size:12px" placeholder="5차확정액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimbalance5" value="<?=$claimbalance5?>" size="14"  style="font-size:12px" placeholder="5차잔액" disabled onkeyup="inputNumberFormat(this)"/></div> <br><br> 		          
							 
							 <div class="sero14"> 6차 : </div>   
							 <div class="sero22"><input type="text" name="claimdate6" value="<?=$claimdate6?>" size="14"  style="font-size:12px" placeholder="6차청구일"   disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/></div>
							 <div class="sero22"><input type="text" name="claimamount6" value="<?=$claimamount6?>" size="14"  style="font-size:12px" placeholder="6차청구액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimfix6" value="<?=$claimfix6?>" size="14"  style="font-size:12px" placeholder="6차확정액"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimbalance6" value="<?=$claimbalance6?>" size="14"  style="font-size:12px" placeholder="6차잔액" disabled onkeyup="inputNumberFormat(this)"/></div> <br><br> 		          							   
							   
							   -----------------------------------------------------------------------  <br><br>

							 <div class="sero14"> 누적계 : </div>   
							 <div class="sero22">&nbsp;</div>	 
							 <div class="sero22"><input type="text" name="claimamount7" value="<?=$claimamount7?>" size="14"  style="font-size:12px" placeholder="청구금누계"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimfix7" value="<?=$claimfix7?>" size="14"  style="font-size:12px" placeholder="확정액누계"  disabled onkeyup="inputNumberFormat(this)"/></div> 
							 <div class="sero22"><input type="text" name="claimbalance7" value="<?=$claimbalance7?>" size="14"  style="font-size:12px" placeholder="잔액누계"  disabled onkeyup="inputNumberFormat(this)"/></div> <br> 	

                             <b> <p style="color:#FF0000";>&nbsp;&nbsp;&nbsp;&nbsp;기성청구 잔액 및 누적합계는 자동계산됩니다. </p> </b> 		<br>	
		
					  

        <div class="sero99"> 시공투입일: </div> 
         <div class="sero22"><input type="text" name="workday" value="<?=$workday?>" size="9" placeholder="투입일" disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/ > </div>
	    <div class="sero999">  시공팀 : </div> 
	    <div class="sero22"> <input type="text" name="worker" value="<?=$worker?>" size="9" placeholder="시공팀" disabled  ></div>	
	    <div class="sero99">  시공완료일 : </div>
         <div class="sero22"><input type="text" name="endworkday" value="<?=$endworkday?>" size="9" placeholder="시공완료일" disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>		
	    <br><br>			
	        <div class="sero99"> 결선작업일: </div> 
         <div class="sero22"><input type="text" name="cableday" value="<?=$cableday?>" size="9" placeholder="투입일" disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)" /> </div>
	    <div class="sero999">  결선팀 : </div> 
	    <div class="sero22"> <input type="text" name="cablestaff" value="<?=$cablestaff?>" size="9" placeholder="결선팀" disabled  ></div>	
	    <div class="sero99">  결선완료일 : </div>
         <div class="sero22"><input type="text" name="endcableday" value="<?=$endcableday?>" size="9" placeholder="결선완료일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>			
	    <br><br>	<br><br>	
		 <div class="sero9"> 모터회사/용량 : </div> 
         <div class="sero2"><input type="text" name="motormaker" value="<?=$motormaker?>" size="15" placeholder="경동/인성/KST 등" disabled  > </div>
	    <div class="sero10">  전원사양 : </div> 
	    <div class="sero4"> <input type="text" name="power" value="<?=$power?>" size="8" placeholder="220v,380v"  disabled ></div>	
	    <br><br>
	   <div class="sero7"> 시공내역 및 수량 : </div>    
	   <div class="sero8"> <textarea rows="2" cols="62" name="worklist" placeholder="셔터종류,수량 등 구체적 기술" disabled  ><?=$worklist?></textarea></div> <br><br><br><br>
	   <div class="sero7" style="color:red" ><b> 설계변경 내역 :</b> </div>    
	   <div class="sero8"> <textarea rows="2" cols="62" name="change_worklist" placeholder="설계변경 내역 기록"  disabled ><?=$change_worklist?></textarea></div> <br><br><br>		   
	   <img src="../img/comment_title.png">  <br><br>
	  <div class="sero8"> <textarea rows="10" cols="75" name="comment" placeholder="공사관련 메모를 남겨주세요." disabled 
									><?=$comment?></textarea></div> <br><br><br><br><br><br><br><br><br><br><br><br><br>
	   <img src="../img/title_outputmemo.png">  <br><br>
	  <div class="sero8"> <textarea rows="8" cols="75" name="outputmemo" placeholder="자재 출고내역 정리(황규선차장님)" disabled 
									><?=$outputmemo?></textarea></div> <br><br><br><br><br><br><br><br><br><br><br>

					
         <div class="sero1"> <img src="../img/as_title.png"> </div>
		 	  <div class="sero1111">
				 <?php
			if($as_check!="1") {
				 ?>
				 <input type="checkbox" name=as_check value="1" disabled  > AS 진행현황만 표시  </div>
				<?php
						}    ?>	   
		 <?php
			if($as_check=="1") {
				 ?>
				 <input type="checkbox" name=as_check checked  disabled value="1"  > AS 진행현황만 표시   </div>
				<?php
						}    ?>	  
	   <div class="sero1_2"> <input type="button" onclick='copy_below()' disabled value="AS기록으로 복사 이관" > </div> <div class="sero1_3"> <input type="button" onclick='del_below()' disabled value="입력란 초기화" ></div>
	    <br><br><br><br>	
	  <div class="sero1"> AS 접수일 : </div> 
      <div class="sero2"><input type="text" name="asday" value="<?=$asday?>" size="10" placeholder="AS 접수일" disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>
      <div class="sero3">  접수자 : </div> 
	    <div class="sero4"> <input type="text" name="aswriter" value="<?=$aswriter?>" size="10"  disabled placeholder="AS접수자"></div>
      <div class="sero3">  요청인 : </div> 
	  <div class="sero4"> <input type="text" name="asorderman" value="<?=$asorderman?>" size="10" disabled  placeholder="AS의뢰자"></div><br> <br> 
	  <div class="sero5_1">  연락처 : </div> 
	  <div class="sero6"> <input type="text" name="asordermantel" value="<?=$asordermantel?>" size="14"  disabled placeholder="요청인 연락처"></div> <br> <br> <br> 
      <div class="sero6">	 
	 <?php
	    if($asfee==0) {
			 ?>
			무상                  <input type="radio" checked name=asfee value="0" disabled >
			&nbsp;&nbsp;   유상 <input type="radio" name=asfee value="1" disabled style="color:red"> 
			<?php
             		}    ?>	
	 <?php
	    if($asfee==1) {
			 ?>
			무상                  <input type="radio" name=asfee value="0" disabled >
			&nbsp;&nbsp;   유상 <input type="radio" checked name=asfee value="1" disabled  style="color:red"> 
			<?php
             		}    ?>	
       &nbsp;&nbsp; 유상일 경우 견적금액 : </div> <div class="sero66"> <input type="text" name="asfee_estimate"  disabled value="<?=$asfee_estimate?>" size="10" onkeyup="inputNumberFormat(this)" placeholder="유상 금액"></div>
      <br><br> <br> 
	 <div class="sero7"> AS 증상(구체적) : </div> 
	 <div class="sero8"> <textarea rows="2" cols="63" name="aslist"  disabled placeholder="구체적 증상 설명"
                ><?=$aslist?></textarea></div> <br><br><br><br>
	 <div class="sero7"> 작업자 참조사항 : </div> 				
	 <div class="sero8"> <textarea rows="2" cols="63" name="as_refer"  disabled placeholder="작업자 참조사항 기록"
                ><?=$as_refer?></textarea></div> <br><br><br><br>				
	<div class="sero1"> 처리 예정일 : </div> 
         <div class="sero2"><input type="text" name="asproday" value="<?=$asproday?>" size="10" placeholder="AS 예정일" disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>
	<div class="sero1_1"> 세팅 예정일 : </div> 
         <div class="sero2_1"><input type="text" name="setdate" value="<?=$setdate?>" size="10" placeholder="세팅 예정일" disabled  oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>	    
		<div class="sero3">  AS담당 : </div> 
	    <div class="sero4"> <input type="text" name="asman" value="<?=$asman?>" size="10" placeholder="AS처리담당" disabled ></div><br> <br> <br> 				
     <div class="sero1"> 최종AS완료: </div>  
     <div class="sero2"><input type="text" name="asendday" value="<?=$asendday?>" size="10" placeholder="최종AS완료일"  disabled oninput="this.value = this.value.replace(/[^0-9\-]/g,'')"  onkeyup="date_mask(this.form.name, this.name)"/> </div>	   
	 <div class="sero77"> 처 리 결 과  : </div> 
	 <div class="sero8"> <textarea rows="2" cols="44" name="asresult" placeholder="처리결과 기록" disabled 
                ><?=$asresult?></textarea></div> <br><br><br><br>
	   
	 <div class="sero7_1"> AS 기록 : </div> 
	 <div class="sero8"> <textarea rows="11" cols="71" name="ashistory" placeholder="지속적으로 기록을 남겨 보관" disabled 
                ><?=$ashistory?></textarea></div> <br><br><br>
	
	   
	   </div>	
	<div id="work_view_content">   	<!-- 파일첨부 보여주기 --> 
	 
 <?php
	for ($i=0; $i<3; $i++)
	{
	   if ($image_copied[$i]) 
            {
	     $imageinfo = getimagesize($file_dir.$image_copied[$i]);
       $image_width[$i] = $imageinfo[0];
	     $image_height[$i] = $imageinfo[1];
	     $image_type[$i]  = $imageinfo[2];
	     $img_name = $image_copied[$i];
	     $img_name = "../uploads/".$img_name; 
 
 	      if ($image_width[$i] > 785)
	          $image_width[$i] = 785;
               
              // image 타입 1은 gif 2는 jpg 3은 png
             if($image_type[$i]==1 || $image_type[$i]==2   
                    || $image_type[$i]==3){ ?>
					
	         <a href="#" onclick="window.open('viewimg.php?img_name=<?=$img_name?>','첨부보기','left=100,top=100, scrollbars=yes, toolbars=no,width=900,height=800');" border="0" style="font-size:10px"><?=$img_name?></a>
			<?php
                    }
            }
        }
  ?>

		           </div>		 
	     
 <?php
	}
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>  
	
 
    <div id="ripple_work"> 
<?php
try{
   $sql = "select * from chandj.work_ripple where parent='$item_num'";
   $stmh1 = $pdo->query($sql);   // ripple PDOStatement 변수명을 다르게      
 } catch (PDOException $Exception) {
   print "오류: ".$Exception->getMessage();
 }
    while ($row_ripple = $stmh1->fetch(PDO::FETCH_ASSOC)) {
	   $ripple_num     = $row_ripple["num"];
	   $ripple_id      = $row_ripple["id"];
	   $ripple_nick    = $row_ripple["nick"];
	   $ripple_content = str_replace("\n", "<br>", $row_ripple["content"]);
	   $ripple_content = str_replace(" ", "&nbsp;", $ripple_content);
	   $ripple_date    = $row_ripple["regist_day"];
		 ?>
		  <div id="ripple_work_writer_title">
				<ul>
				  <li id="writer_title1"><?=$ripple_nick?></li>
				  <li id="writer_title2"><?=$ripple_date?></li>
				  &nbsp; &nbsp;
		 <?php
			  if(isset($_SESSION["userid"])){
			if($_SESSION["userid"]=="admin" || $_SESSION["userid"]==$ripple_id)
				print "<a href=delete_ripple.php?num=$item_num&ripple_num=$ripple_num&page=$page>[삭제]</a>"; 
			  }
		 ?>
				  
				</ul>
				</div>
				<div id="ripple_work_content"><?=$ripple_content?></div>
				<div class="hor_work_line_ripple"></div>
		 <?php
    } // while문의 끝
 ?>
  <form name="ripple_form" method="post" action="a.php?num=<?=$item_num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>"> 
  
        <div id="ripple_box">
        <div id="ripple_box1"><img src="../img/title_comment.gif"></div>
        <div id="ripple_box2"><textarea rows="5" cols="65" name="ripple_content" required></textarea>
        </div>
        <div id="ripple_box3"><input type="image" src="../img/ok_ripple.gif"></a></div>
        </div>
        </form>
 </div> <!-- end of ripple -->
 <!-- 이하 생략 -->

	<div class="clear"></div>
     </div> <!-- end of col2 -->
  </div> <!-- end of content -->
 </div> <!-- end of wrap -->
 </body>

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
			document.getElementById("asorderman").value  = "" ;
			document.getElementById("asordermantel").value ="";
			document.getElementById("asfee_estimate").value = "";
			document.getElementById("aslist").value = "";
			document.getElementById("as_refer").value ="";
			document.getElementById("asproday").value ="";
			document.getElementById("setdate").value =""
			document.getElementById("asman").value = "";				
			document.getElementById("asendday").value = "";
			document.getElementById("asresult").value = "";        
    }
}
     function del(href) 
     {
		 var level=Number($('#session_level').val());
		 if(level>1)
		     alert("삭제하려면 관리자에게 문의해 주세요");
		 else {
         if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          } 
		 }

     }
</script>
</html>    