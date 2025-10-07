<!doctype html>
<?php
 session_start(); 
$img_name="http://5130.co.kr/img/aslist.jpg";
  
$num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
 $asprocess=$_REQUEST["asprocess"];   // as진행현황

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
$total_bill=number_format($total_bill);
$total_deposit=number_format($total_deposit);


		     if($workday!="0000-00-00") $workday = date("Y-m-d", strtotime( $workday) );     //  date형태로 저장된 것들만 이렇게 표시함.
					else $workday="";
			 if($cableday!="0000-00-00") $cableday = date("Y-m-d", strtotime( $cableday) );
					else $cableday="";	 
			  if($asday!="0000-00-00") $asday = date("Y-m-d", strtotime( $asday) );
					else $asday="";  
			  if($condate!="0000-00-00") $condate = date("Y-m-d", strtotime($condate) );
						else $condate="";
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
						
			 if($asproday!="0000-00-00") $asproday = date("Y-m-d", strtotime($asproday) );
						else $asproday="";
			 if($asendday!="0000-00-00") $asendday = date("Y-m-d", strtotime($asendday) );
						else $asendday="";						
			 if($endworkday!="0000-00-00")  $endworkday = date("Y-m-d", strtotime($endworkday) );     
						else $endworkday="";
			 if($endcableday!="0000-00-00") $endcableday = date("Y-m-d", strtotime($endcableday) );
						else $endcableday="";	 
 		
$asrequiredperson=$asorderman . " " . $asordermantel;
?>


<html lang="ko">
  <head>
   
    <meta charset="utf-8">

 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/work.css">	
 <link  rel="stylesheet" type="text/css" href="../css/as.css">	
 <link rel="stylesheet" type="text/css" media="print" href="../css/print.css">
 
    <title>AS 작업지시서 출력</title>
  </head>
<body onload="pagePrintPreview();" >
 <!-- <input type="button" value="인쇄하기" onclick="print();"> -->
<div id="print">  
    <div class="img">      
    <div class="content">
	    <div class="ex-layout">
			<div class="menu">
				<br> <br>  <br> <br> <br>  <br> 
				<div class="print1"> <?=$workplacename?> </div>
				<div class="print2"> &nbsp; <?=$secondord?> </div>				
				<div class="print3"> <?=$workplacename?> </div>
				<div class="print2"> &nbsp; <?=$secondord?> </div>	
				<div class="clear"> </div>	
				<div class="print4"> <?=$asday?> </div>
				<div class="print5"> &nbsp; <?=$asproday?> </div>				
				<div class="print6"> <?=$asday?> </div>
				<div class="print5"> &nbsp; <?=$asproday?> </div>	
				<div class="clear"> </div>	
				<div class="print7"> <?=$address?> </div>
				<div class="print8"> &nbsp; <?=$address?> </div>	
				<div class="clear"> </div>	
				
				<div class="print9"> <?=$asrequiredperson?> </div>
				<div class="print10"> &nbsp; <?=$chargedperson?> </div>				
				<div class="print11"> <?=$asrequiredperson?> </div>
				<div class="print10"> &nbsp; <?=$chargedperson?> </div>	
				<div class="clear"> </div>	
				
				<div class="print9"> <?=$motormaker?> </div>
				<div class="print10"> &nbsp; 시공일부터 2년</div>				
				<div class="print11"> &nbsp; 시공일부터 2년 </div>
				<div class="print10"> &nbsp; <?=$asman?> </div>	
				<div class="clear"> </div>					
				
				<div class="print9"> <?=$power?> </div>
				<div class="print10"> &nbsp;  <?=$workday?></div>				
				<div class="print12"> &nbsp;  <?=$motormaker?>  </div>
				<div class="print13"> &nbsp; <?=$power?> </div>	
				<div class="print14"> &nbsp; <?=$workday?> </div>	
				<div class="clear"> </div>	
                     </div>  <!-- end of menu -->

		<div class="main">
             		<div class="left_area">
						<div class="print1"> <?=$aslist?></div>				                    
						<div class="clear"> </div>	
						<div class="print2"> <?=$asman?></div>			
                        <div class="clear"> </div>	

                          </div>			<!-- end of left-area -->			 	
             		<div class="right_area">
						<div class="print1"> <?=$aslist?></div>				                    
						<div class="clear"> </div>	

                          </div>			<!-- end of right-area -->								  
                     </div>			<!-- end of main -->			 					 
               </div>  <!-- end of ex-layout -->
        </div>  <!-- end of content -->
 
  <!--      <div class="img-cover"></div> -->
    </div>
</div>

 <?php
	
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>  

</body>
<script>

function pagePrintPreview(){
          var browser = navigator.userAgent.toLowerCase();
          if ( -1 != browser.indexOf('chrome') ){
                     window.print();
          }else if ( -1 != browser.indexOf('trident') ){
                     try{
                              //참고로 IE 5.5 이상에서만 동작함
  
                              //웹 브라우저 컨트롤 생성
                              var webBrowser = '아래코드';
  
                              //웹 페이지에 객체 삽입
                              document.body.insertAdjacentHTML('beforeEnd', webBrowser);
  
                              //ExexWB 메쏘드 실행 (7 : 미리보기 , 8 : 페이지 설정 , 6 : 인쇄하기(대화상자))
                              previewWeb.ExecWB(7, 1);
  
                              //객체 해제
                              previewWeb.outerHTML = "";
                     }catch (e) {
                              alert("오류처리 참조");
                     }
          }
}

function print_ok() {
		window.print();
        /* window.open("http://5130.co.kr/as/print_area.php"); */
}

function preview_print(){
   var OLECMDID = 7;
   var PROMPT = 1;
   var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
   document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
   WebBrowser1.ExecWB( OLECMDID, PROMPT);
}

/* window.onload = function(){
    if( navigator.userAgent.indexOf("MSIE") > 0 ){
        preview_print();
   } else if( navigator.userAgent.indexOf("Chrome") > 0){
        window.print();
    } */

};

function printDiv ()
{
	if (document.all && window.print)
	 {
	window.onbeforeprint = beforeDivs;
	window.onafterprint = afterDivs;
	window.print();
	}
}
function beforeDivs ()
{
	if (document.all)
	{
	objContents.style.display = 'none';
	objSelection.innerHTML = document.all['d1'].innerHTML;
	}
}
function afterDivs ()
{
		if (document.all)
		{
		objContents.style.display = 'block';
		objSelection.innerHTML = "";
}
}

function printPage()
{
 document.getElementsByName('aaa')[0].style.display = 'none';
 document.getElementsByName('bbb')[0].style.display = 'none';
 window.print();
 document.getElementsByName('aaa')[0].style.display = '';
 document.getElementsByName('bbb')[0].style.display = '';
}
</script>	

</html>


