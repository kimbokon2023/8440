<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 $WebSite = "http://8440.co.kr/";
 
 $title_message = '본천장&조명천장 출고증 일괄';

?>

<?php include getDocumentRoot() . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:" . $WebSite . "login/login_form.php");
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
 
if (isset($_REQUEST["array"])) {
    $arrayString = $_REQUEST["array"]; // array 파라미터 값을 받아옵니다.
    $numarr = explode(",", urldecode($arrayString)); // URL 디코딩 후, 콤마로 구분된 문자열을 배열로 변환합니다.
   //  print_r($numarr); // 배열 내용을 출력하거나 필요한 작업을 수행합니다.
}
   
$outputdate = date("Y-m-d", time());

if($outputdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outputdate = $outputdate . $week[ date('w',  strtotime($outputdate)  ) ] ;
} 
$text=array();			
$textnum=array();	
$textset=array();	
$textmemo=array();	

// 클릭하면 대표 배열을 불러오기 위함
$workplacename_arr =array();	
$address_arr =array();	
$secondord_arr =array();	
$chargedman_arr =array();	
$chargedmantel_arr =array();	
$type =array();	

for($i=0;$i<count($numarr);$i++) 
{

  try{
      $sql = "select * from mirae8440.ceiling where num = ? ";
      $stmh = $pdo->prepare($sql); 

    $stmh->bindValue(1,$numarr[$i],PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
	 }
  
			  include '_rowDB.php';				
		
			  $su=(int)$row["su"];			  
			  $bon_su=(int)$row["bon_su"];			  
			  $lc_su=(int)$row["lc_su"];			  
			  $etc_su=(int)$row["etc_su"];			  
			  $air_su=(int)$row["air_su"];		

		$workplacename_arr[] = $workplacename;
		$address_arr[] = $address;
		$secondord_arr[] = $secondord;	
		$chargedman_arr[] = $chargedman;
		$chargedmantel_arr[] = $chargedmantel;			  

						
			  
  $workday=trans_date($workday);
				
	if($su!==0 && $bon_su!='') 	{
		 $text[$i]="본천장: " . $bon_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="SET";
	                  }
	if($su!==0 && $lc_su!='') 	{		
	     $text[$i] .= "LC: " . $lc_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="SET";
	                  }	
	if($su!==0 && $etc_su!='') 	{		
	     $text[$i] .= "기타: " . $etc_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="EA";
	                  }					  
	if($su!==0 && $lc_su!==0 && $bon_su!==0  && $etc_su!==0 ) 	{
	     $text[$i]="본천장: " . $bon_su . " " .  "LC: " . $lc_su . " "  . "기타: " . $etc_su . " " ;
		 $textnum[$i]=$su;
		 $textset[$i]="SET";
	                  }			  
  
					  		
       $text[$i] =  $workplacename . "  " . $type . "  " .  $text[$i] . " " . $car_insize;		 ;

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
}
  
if(count($numarr) > 1)
	$workplacename =  $workplacename . ' 외 ' . (count($numarr)-1) . '건' ;

?>

<title>  <?=$title_message?> </title>

<style> 

 .checkrow {
	 cursor : pointer;
 }
</style>   
  
   </head>
 <body>
<form id="board_form"  name="board_form" onkeydown="return captureReturnKey(event)" method="post" action="invoice.php?num=<?=$num?>" enctype="multipart/form-data">
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
        <input type="text" name="chargedman" id="chargedman" value="<?=$chargedman?>" class="form-control fs-6" placeholder="담당자">
    </div>

    <div class="mb-1 input-group">
        <div class="input-group-text text-center" style="width:150px;">받으실분 연락처 </div>
        <input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control fs-6" placeholder="담당자 연락처">
    </div>
</div>


    <?php
    for ($i = 0; $i <= 11; $i = $i + 1) {
        ?>
	<div class="row"> 
        <div class="d-flex mb-1">
            <span class="input-group-text text-center checkrow text-primary"  style="width:150px;"><?=$i+1?> 주소 선택 </span>
            <input type="text" name="text[]" id="text<?=$i?>" value="<?=$text[$i]?>"  class="input-group-text text-left" style="text-align:left; width: 700px;"  >            
            <input type="text" name="textnum[]" value="<?=$textnum[$i]?>" size=2 class="text-center fs-6" >            
            <input type="text" name="textset[]" value="<?=$textset[$i]?>" size=2  class="text-center fs-6" >
            <input type="text" name="textmemo[]" value="<?=$textmemo[$i]?>" size="10"  class="text-center fs-6" >
			
			</div>
		</div>	
    <?php } ?>
</div>


</div>       

			 
</form>
    
</body>
</html>

<script>

$(document).ready(function(){
	
	 $('.checkrow').on('click', function() {
		var workplacenameArr = <?php echo json_encode($workplacename_arr); ?>;
		var addressArr = <?php echo json_encode($address_arr); ?>;
		var secondordArr = <?php echo json_encode($secondord_arr); ?>;
		var chargedmanArr = <?php echo json_encode($chargedman_arr); ?>;
		var chargedmantelArr = <?php echo json_encode($chargedmantel_arr); ?>;		 
		 
			// 클릭된 요소의 인덱스 가져오기
			var index = $('.checkrow').index($(this));

			// 해당 인덱스의 배열 값으로 입력 필드 설정
			$('#workplacename').val(workplacenameArr[index]);
			$('#address').val(addressArr[index]);
			$('#secondord').val(secondordArr[index]);
			$('#chargedman').val(chargedmanArr[index]);
			$('#chargedmantel').val(chargedmantelArr[index]);
		});		
		
		// Log 파일보기
	$("#PrintBtn").click( function() {     	
		    var num = '<?php echo $num; ?>' 
			
		$("#board_form").submit();  

	});	
});	


function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트 담당자 연락처 찾기
    }
}
function Enter_firstCheck(){
    if(event.keyCode == 13){
      exe_firstordman();  // 원청 담당자 전번 가져오기
    }
}

function Enter_chargedman_Check(){
    if(event.keyCode == 13){
      exe_chargedman();  // 현장소장 전번 가져오기
    }
}

function exe_search()
{

     var tmp=$('#secondordman').val();
	 switch (tmp) {
		 case '김관' :
         $("#secondordmantel").val("010-2648-0225");		 
         $("#secondordman").val("김관부장");		 
         $("#secondord").val("한산");		 
		 break;		
	 }
}
function exe_firstordman()
{
     var tmp=$('#firstordman').val();
	 switch (tmp) {
		 case '고범섭' :
         $("#firstordman").val("고범섭소장");		 		 
         $("#firstordmantel").val("010-6774-6211");		 
         $("#firstord").val("오티스");			 		 		 
         $("#secondord").val("우성");			 
		 break;		 
	 }
}

function exe_chargedman()
{
}

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
