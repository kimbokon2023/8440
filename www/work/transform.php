<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
 $title_message = 'jamb 출고증';

?>

<?php include getDocumentRoot() . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
 ?>

</head>

<body>

<?php
 
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
	$num=$_REQUEST["num"];
  else
   $num="";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

 
$outputdate = date("Y-m-d", time());

if($outputdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
} 
 
  try{
      $sql = "select * from mirae8440.work where num = ? ";
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
  $material1=$row["material1"];
  $material2=$row["material2"];
  $material3=$row["material3"];
  $material4=$row["material4"];
  $material5=$row["material5"];
  $material6=$row["material6"];
  $widejamb=$row["widejamb"];
  $normaljamb=$row["normaljamb"];
  $smalljamb=$row["smalljamb"];
  $memo=$row["memo"];
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
		  		      						
	     switch ($worker) {
			case   "김운호"      : $workertel =  "010-9322-7626" ; break;
			case   "김상훈"      : $workertel =  "010-6622-2200" ; break;
			case   "이만희"      : $workertel =  "010-6866-5030" ; break;
			case   "유영"        : $workertel =  "010-5838-5948" ; break;
			case   "추영덕"      : $workertel =  "010-6325-4280" ; break;
			case   "김지암"      : $workertel =  "010-3235-5850" ; break;
			case   "손상민"      : $workertel =  "010-4052-8930" ; break;
			case   "지영복"      : $workertel =  "010-6338-9718" ; break;
			case   "김한준"      : $workertel =  "010-4445-7515" ; break;
			case   "민경채"      : $workertel =  "010-2078-7238" ; break;
			case   "이용휘"      : $workertel =  "010-9453-8612" ; break;
			case   "박경호"      : $workertel =  "010-3405-6669" ; break;
			case   "조형영"      : $workertel =  "010-2419-2574" ; break;
			case   "김진섭"     : $workertel =  "010-6524-3325" ; break;
			case   "최양원"     : $workertel =  "010-5426-3475" ; break;
			case   "임형주"     : $workertel =  "010-8976-9777" ; break;
			case   "박철우"     : $workertel =  "010-4857-7022" ; break;
			case   "조장우"     : $workertel =  "010-5355-9709" ; break;
			case   "백석묵"     : $workertel =  "010-5635-0821" ; break;
			case   "이인종"     : $workertel =  "010-5237-0771" ; break;
			
			default: break;
		}	
	
	$text=array();			
	$textnum1=array();	
	$textnum2=array();	
	$textnum3=array();	
	$textmemo=array();	
	
	$text[3]="";
	$text[4]="";
	
    $j=0;
	if($widejamb>=1) 	{
	     $text[$j]="와이드쟘 막판(유) ";		
		 $textnum1[$j]=$widejamb;
		 $textmemo[$j]="";
		 $j++;
	                  }
	if($normaljamb>=1) 	{
	     $text[$j]="와이드쟘 막판(무) ";		
		 $textnum2[$j]=$normaljamb;
		 $textmemo[$j]="";		 
		 $j++;
	                  }					  
	if($smalljamb>=1) 	{
	     $text[$j]="쪽쟘 ";		
		 $textnum3[$j]=$smalljamb;
		 $textmemo[$j]="";	
		 $j++;
	                  }		
					  
	// if($hpi != null) 	{
	     // $text[$j]="HPI 브라켓 : " . $hpi ;		
		 // $textnum[$j]=$widejamb;
		 // $textmemo[$j]="EA";		
		 // $j++;		 
	                  // }	
	// if($hpi != null and ($hpi != "없음" or $hpi != "무") ) 	{
	     // $text[$j]="HPI 브라켓 (" . $hpi . ")" ;		
		 // $textnum[$j]= $widejamb;
		 // $textmemo[$j]='EA';		 
		 // $j++;
	                  // }		
					  
    if($attachment != null and ($attachment != "x" or $attachment != "X") ) 	{
	     $text[$j]="부속자재 : " . $attachment ;		
		 $textnum[$j]= '';
		 $textmemo[$j]='';		 
		 $j++;
	                  }			

  if($worker!=='')
         $worker .= ' 소장';
		
		// echo $i."출력 <br />";

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  

?>

<title>  <?=$title_message?> </title>
  
<form name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>" enctype="multipart/form-data">

	<input type="hidden" name="workday" id="workday" value="<?=$workday?>" >
	
<div class="container">	
<div class="card">
<div class="card-body">        
<div class="d-flex justify-content-center mb-1 mt-1">  
    <h4 class="mt-1 mb-1">
        <?=$title_message?>
	</h4>			
</div>
<div class="d-flex justify-content-start mb-1 mt-1">  
	<button onclick="window.close();" type="button" class="btn btn-dark btn-sm me-1">    <ion-icon name="close-outline"></ion-icon> 창닫기  </button>  </button>
	<button onclick="form.submit();"  type="button" class="btn btn-success btn-sm"> <ion-icon name="print-outline"></ion-icon> 인쇄 </button>        
    
</div>
<div class="d-flex flex-column">
    <div class="mb-1 input-group">
        <div class="input-group-text" style="color:red; width: 150px;">하차일시 </div>
        <input type="text" name="outputdate" id="outputdate" value="<?=$outputdate?>" class="form-control fs-6" placeholder="일시" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >현장명 </div>
        <input type="text" name="workplacename" id="workplacename" value="<?=$workplacename?>" class="form-control fs-6" placeholder="현장명" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >현장주소 </div>
        <input type="text" name="address" id="address" value="<?=$address?>" class="form-control fs-6" placeholder="현장주소" required>
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >발주처 </div>
        <input type="text" name="secondord" id="secondord" value="<?=$secondord?>" class="form-control fs-6" placeholder="발주처">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width: 150px;" >받으실분 </div>
        <input type="text" name="worker" id="worker" value="<?=$worker?>" class="form-control fs-6" placeholder="담당자">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width:150px;">받으실분 연락처 </div>
        <input type="text" name="workertel" id="workertel" value="<?=$workertel?>" class="form-control fs-6" placeholder="담당자 연락처">
    </div>
    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="margin-left:150px;width:350px;"> (신규쟘 한산) ->(채규철과장 010-7105-7060) </div>
    </div>
</div>

	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center" style="width:150px;"> 번호 </span>
            <span class="input-group-text text-center" style="width:500px;">  내역  </span>
            <span class="input-group-text text-center" style="width:100px;"> 막판 </span>
            <span class="input-group-text text-center" style="width:100px;"> 막판무 </span>
            <span class="input-group-text text-center" style="width:100px;"> 쪽쟘 </span>
		</div>
	</div>	

    <?php
    for ($i = 0; $i <= 9; $i = $i + 1) {
        ?>
	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center" style="width:150px;"><?=$i+1?>번째줄 </span>
            <input type="text" name="text[]" id="text<?=$i?>" value="<?=$text[$i]?>"  class="input-group-text text-left" style="text-align:left; width: 500px;"  >            
            <input type="text" name="textnum1[]" value="<?=$textnum1[$i]?>" style="width:100px;" class="text-center fs-6" >            
            <input type="text" name="textnum2[]" value="<?=$textnum2[$i]?>" style="width:100px;" class="text-center fs-6" >
			<input type="text" name="textnum3[]" value="<?=$textnum3[$i]?>" style="width:100px;" class="text-center fs-6" >			
		</div>
		</div>	
    <?php } ?>
</div>
</div>            
</div>            
</form>
    
</body>
</html>

<script>

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
</script>
	</body>
 </html>
