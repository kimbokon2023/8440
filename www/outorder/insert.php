<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
	
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}

 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

 if(isset($_REQUEST["tablename"]))
    $tablename=$_REQUEST["tablename"];
 else 
    $tablename="";

 if(isset($_REQUEST["timekey"]))
    $timekey=$_REQUEST["timekey"];
 else 
    $timekey="";
	
 if(isset($_REQUEST["check_draw"])) 
	 $check_draw=$_REQUEST["check_draw"];   // 도면 미설계List
	   else
		 $check_draw=$_POST["check_draw"];    	


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

 if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
     $check=$_POST["check"]; // 미출고 리스트 POST사용 
 
 if(isset($_REQUEST["output_check"])) 
	 $output_check=$_REQUEST["output_check"]; // 미출고 리스트 request 사용 페이지 이동버튼 누를시`
   else
	if(isset($_POST["output_check"]))   
         $output_check=$_POST["output_check"]; // 미출고 리스트 POST사용  
	 else
		 $output_check='0';
	 
 if(isset($_REQUEST["team_check"])) 
	 $team_check=$_REQUEST["team_check"]; // 시공팀미지정
   else
	if(isset($_POST["team_check"]))   
         $team_check=$_POST["team_check"]; // 시공팀미지정
	 else
		 $team_check='0';	
 if(isset($_REQUEST["measure_check"])) 
	 $measure_check=$_REQUEST["measure_check"]; // 미실측리스트
   else
	if(isset($_POST["measure_check"]))   
         $measure_check=$_POST["measure_check"]; // 미실측리스트
	 else
		 $measure_check='0';	
  
    if(isset($_REQUEST["plan_output_check"])) 
	 $plan_output_check=$_REQUEST["plan_output_check"]; // 출고예정`
   else
	if(isset($_POST["plan_output_check"]))   
         $plan_output_check=$_POST["plan_output_check"]; // 출고예정  
	 else
		 $plan_output_check='0';

if(isset($_REQUEST["cursort"])) 
	 $cursort=$_REQUEST["cursort"]; // 미실측리스트
   else
	if(isset($_POST["cursort"]))   
         $cursort=$_POST["cursort"]; // 미실측리스트
	 else
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
 
 include '__request.php';

$workday=trans_date($workday);
$demand=trans_date($demand);
$orderday=trans_date($orderday);
$deadline=trans_date($deadline);
$testday=trans_date($testday);
$lc_draw=trans_date($lc_draw);
$lclaser_date=trans_date($lclaser_date);
$lclbending_date=trans_date($lclbending_date);
$lclwelding_date=trans_date($lclwelding_date);
$lcpainting_date=trans_date($lcpainting_date);
$lcassembly_date=trans_date($lcassembly_date);
$main_draw=trans_date($main_draw);			
$eunsung_make_date=trans_date($eunsung_make_date);			
$eunsung_laser_date=trans_date($eunsung_laser_date);			
$mainbending_date=trans_date($mainbending_date);			
$mainwelding_date=trans_date($mainwelding_date);			
$mainpainting_date=trans_date($mainpainting_date);			
$mainassembly_date=trans_date($mainassembly_date);	  

$order_date1=trans_date($order_date1);					   
$order_date2=trans_date($order_date2);					   
$order_date3=trans_date($order_date3);					   
$order_date4=trans_date($order_date4);					   
$order_input_date1=trans_date($order_input_date1);					   
$order_input_date2=trans_date($order_input_date2);					   
$order_input_date3=trans_date($order_input_date3);					   
$order_input_date4=trans_date($order_input_date4);		


// 첨부파일에 대한 처리방법 SQL에 넣을때는 보이는 파일명과 실제 저장될 파일명 구분해서 저장함.

$copied_file = $_REQUEST["copied_file"];  
$pdffile_name = $_REQUEST["pdffile_name"];  
	
if($copied_file=='' && $pdffile_name!='' )
{	
// pdf파일 대한 정보						
	$pdffile_name = $_FILES['pdf_file']['name'];			

			$files = $_FILES["pdf_file"];    //첨부파일	

			$upload_dir = './attachedfile/';   // sub폴더 물리적 저장위치   

	if (isset($_FILES)) {
		$name = $_FILES["pdf_file"]["name"];
		$type = $_FILES["pdf_file"]["type"];
		$size = $_FILES["pdf_file"]["size"];
		$tmp_name = $_FILES["pdf_file"]["tmp_name"];
		$error = $_FILES["pdf_file"]["error"];
		
        // 서버에 저장할때는 한글로 저장하면 깨지는 현상이 있으니 임시 이름을 지어서 저장한다.			
		// 파일 확장자를 MIME 타입에 따라 설정
		switch($type) {
			case 'application/pdf':
				$file_ext = 'pdf';
				break;
			case 'image/jpeg':
				$file_ext = 'jpg';
				break;
			case 'image/png':
				$file_ext = 'png';
				break;
			default:
				// 처리되지 않은 파일 타입일 경우 오류 메시지를 출력하고 스크립트를 종료
				echo "Invalid file type";
				exit;
		}
			   
			$new_file_name = date("Y_m_d_H_i_s");
			$copied_file_name = $new_file_name.".".$file_ext; 

		//서버에 임시로 저장된 파일은 스크립트가 종료되면 사라지므로 파일을 이동해야함.
		$upload_result = move_uploaded_file($tmp_name, $upload_dir . $copied_file_name);

		if($upload_result){
			$result = "파일 업로드 성공 경로 - " . $upload_dir;
			$copied_file = $copied_file_name;
		}
	}
}

// 덴크리인 경우 $user_name='덴크리'

  if ($user_name=="덴크리" || $user_name=="서한컴퍼니"){  // 덴크리, 서한컴퍼니인 경우는 update기록과 출하일자만 변경함  
  
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  "  ;	
	$update_log = $data . "<br />" .  $update_log . " ";  // 개행문자 Textarea
	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.outorder set ";
    $sql .="workday=?, delivery=?, deliverynum=?, delipay=?, submemo=?, update_log=? "; 	
	$sql .= " where num=? LIMIT 1" ;          //1  총 121 + 27 = 158EA
	   
     $stmh = $pdo->prepare($sql); 
         
     $stmh->bindValue(1, $workday, PDO::PARAM_STR);    
     $stmh->bindValue(2, $delivery, PDO::PARAM_STR);            
     $stmh->bindValue(3, $deliverynum, PDO::PARAM_STR);            
     $stmh->bindValue(4, $delipay, PDO::PARAM_STR);  
     $stmh->bindValue(5, $submemo, PDO::PARAM_STR);  
     $stmh->bindValue(6, $update_log, PDO::PARAM_STR);  	 
	 $stmh->bindValue(7, $num, PDO::PARAM_STR);	 
     $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                                
 }  
// not 덴크리인 경우 $user_name='덴크리' 아닌경우
 else {  
    
// 수정할때 	
 if ($mode=="modify"){
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea
	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.outorder set ";
    $sql .="checkstep=?, workplacename=?, address=?, "; //3
    $sql .="firstord=?, firstordman=?, firstordmantel=?, secondord=?, secondordman=?, secondordmantel=?, chargedman=?, chargedmantel=?, ";  // 8
    $sql .="orderday=?, measureday=?, drawday=?, deadline=?, workday=?, worker=?, endworkday=?, material1=?, material2=?, material3=?, ";   // 10
    $sql .="material4=?, material5=?, material6=?, widejamb=?, normaljamb=?, smalljamb=?, memo=?,  ";  // 7
    $sql .="regist_day=?, update_day=?, delivery=?, delicar=?, delicompany=?, delipay=?, delimethod=?,  demand=?, startday=?, testday=?, hpi=?, first_writer=?, update_log=?, "; // 13
    $sql .="type1=?, inseung1=?, su=?, bon_su=?, lc_su=?, etc_su=?, air_su=?, car_inside1=?, order_com1=?, order_text1=?,  order_com2=?, order_text2=?,  order_com3=?, order_text3=?,  order_com4=?, order_text4=?, ";  //16
	$sql .="lc_draw=?, lclaser_com=?, lclaser_date=?, lcbending_date=?, lcwelding_date=?, lcpainting_date=?, lcassembly_date=?, main_draw=?, eunsung_make_date=?, ";  //9
	$sql .="eunsung_laser_date=?, mainbending_date=?, mainwelding_date=?, mainpainting_date=?, mainassembly_date=?, memo2=?, "; //6
	$sql .=" order_date1=?,  order_date2=?,  order_date3=?,  order_date4=?, order_input_date1=?, order_input_date2=?, order_input_date3=?, order_input_date4=?, ";   //8
	$sql .=" first_item1=?,  first_item2=?,  first_item3=?,  first_item4=?, second_item1=?, second_item2=?, second_item3=?, second_item4=?, ";   //8
	$sql .=" third_item1=?, third_item2=?, third_item3=?, third_item4=?, fourth_item1=?, fourth_item2=?, fourth_item3=?, fourth_item4=?, fifth_item1=?, fifth_item2=?, fifth_item3=?, fifth_item4=?,  ";   //12
	$sql .=" sixth_item1=?, sixth_item2=?, sixth_item3=?, sixth_item4=?, seventh_item1=?, seventh_item2=?, seventh_item3=?, seventh_item4=?,  ";   //8
	$sql .=" eighth_item1=?, eighth_item2=?, eighth_item3=?, eighth_item4=?, ninth_item1=?, ninth_item2=?, ninth_item3=?, ninth_item4=?,  ";   //8
	$sql .=" tenth_item1=?, tenth_item2=?, tenth_item3=?, tenth_item4=?, ";   //4
	$sql .=" type2=?, type3=?, type4=?, type5=?, type6=?, type7=?, type8=?, type9=?, type10=?,  ";   
	$sql .=" inseung2=?, inseung3=?, inseung4=?, inseung5=?, inseung6=?, inseung7=?, inseung8=?, inseung9=?, inseung10=?,  ";   
	$sql .=" car_inside2=?, car_inside3=?, car_inside4=?, car_inside5=?, car_inside6=?, car_inside7=?, car_inside8=?, car_inside9=?, car_inside10=?,  ";   
	$sql .=" comment1=?, comment2=?, comment3=?, comment4=?, comment5=?, comment6=?, comment7=?, comment8=?, comment9=?, comment10=?,  ";   
	$sql .=" pdffile_name=?, copied_file=?, confirm=?, submemo=?, deliverynum=? ";   
	
	$sql .= " where num=? LIMIT 1" ;          //1  총 121 + 27 = 158EA
	   
     $stmh = $pdo->prepare($sql); 

     $stmh->bindValue(1, $checkstep, PDO::PARAM_STR);             
     $stmh->bindValue(2, $workplacename, PDO::PARAM_STR);             
     $stmh->bindValue(3, $address, PDO::PARAM_STR);             
     $stmh->bindValue(4, $firstord, PDO::PARAM_STR);             
     $stmh->bindValue(5, $firstordman, PDO::PARAM_STR);             
     $stmh->bindValue(6, $firstordmantel, PDO::PARAM_STR);             
     $stmh->bindValue(7, $secondord, PDO::PARAM_STR);             
     $stmh->bindValue(8, $secondordman, PDO::PARAM_STR);             
     $stmh->bindValue(9, $secondordmantel, PDO::PARAM_STR);             
     $stmh->bindValue(10, $chargedman, PDO::PARAM_STR);             
     $stmh->bindValue(11, $chargedmantel, PDO::PARAM_STR);             
     $stmh->bindValue(12, $orderday, PDO::PARAM_STR);             
     $stmh->bindValue(13, $measureday, PDO::PARAM_STR);             
     $stmh->bindValue(14, $drawday, PDO::PARAM_STR);             
     $stmh->bindValue(15, $deadline, PDO::PARAM_STR);             
     $stmh->bindValue(16, $workday, PDO::PARAM_STR);             
     $stmh->bindValue(17, $worker, PDO::PARAM_STR);             
     $stmh->bindValue(18, $endworkday, PDO::PARAM_STR);             
     $stmh->bindValue(19, $material1, PDO::PARAM_STR);             
     $stmh->bindValue(20, $material2, PDO::PARAM_STR);             
     $stmh->bindValue(21, $material3, PDO::PARAM_STR);             
     $stmh->bindValue(22, $material4, PDO::PARAM_STR);             
     $stmh->bindValue(23, $material5, PDO::PARAM_STR);             
     $stmh->bindValue(24, $material6, PDO::PARAM_STR);             
     $stmh->bindValue(25, $widejamb, PDO::PARAM_STR);             
     $stmh->bindValue(26, $normaljamb, PDO::PARAM_STR);             
     $stmh->bindValue(27, $smalljamb, PDO::PARAM_STR);             
     $stmh->bindValue(28, $memo, PDO::PARAM_STR);       	          
     $stmh->bindValue(29, $regist_day, PDO::PARAM_STR);             	 

     $update_day=date("Y-m-d"); // 현재날짜 2020-01-20 형태로 지정	 
     $stmh->bindValue(30, $update_day, PDO::PARAM_STR); 	 
     $stmh->bindValue(31, $delivery, PDO::PARAM_STR);             	 
     $stmh->bindValue(32, $delicar, PDO::PARAM_STR);             	 
     $stmh->bindValue(33, $delicompany, PDO::PARAM_STR);             	 
     $stmh->bindValue(34, $delipay, PDO::PARAM_STR);             	 
     $stmh->bindValue(35, $delimethod, PDO::PARAM_STR);             	 
     $stmh->bindValue(36, $demand, PDO::PARAM_STR);             	 
     $stmh->bindValue(37, $startday, PDO::PARAM_STR);             	 
     $stmh->bindValue(38, $testday, PDO::PARAM_STR);             	 
     $stmh->bindValue(39, $hpi, PDO::PARAM_STR); 
     $stmh->bindValue(40, $first_writer, PDO::PARAM_STR);	 
     $stmh->bindValue(41, $update_log, PDO::PARAM_STR);
     $stmh->bindValue(42, $type1, PDO::PARAM_STR);
     $stmh->bindValue(43, $inseung1, PDO::PARAM_STR);
     $stmh->bindValue(44, $su, PDO::PARAM_STR);
     $stmh->bindValue(45, $bon_su, PDO::PARAM_STR);
     $stmh->bindValue(46, $lc_su, PDO::PARAM_STR);
     $stmh->bindValue(47, $etc_su, PDO::PARAM_STR);
     $stmh->bindValue(48, $air_su, PDO::PARAM_STR);
     $stmh->bindValue(49, $car_inside1, PDO::PARAM_STR);
     $stmh->bindValue(50, $order_com1, PDO::PARAM_STR);
     $stmh->bindValue(51, $order_text1, PDO::PARAM_STR);
     $stmh->bindValue(52, $order_com2, PDO::PARAM_STR);
     $stmh->bindValue(53, $order_text2, PDO::PARAM_STR);
     $stmh->bindValue(54, $order_com3, PDO::PARAM_STR);
     $stmh->bindValue(55, $order_text3, PDO::PARAM_STR);
     $stmh->bindValue(56, $order_com4, PDO::PARAM_STR);
     $stmh->bindValue(57, $order_text4, PDO::PARAM_STR);
     $stmh->bindValue(58, $lc_draw, PDO::PARAM_STR);
     $stmh->bindValue(59, $lclaser_com, PDO::PARAM_STR);
     $stmh->bindValue(60, $lclaser_date, PDO::PARAM_STR);
     $stmh->bindValue(61, $lcbending_date, PDO::PARAM_STR);
     $stmh->bindValue(62, $lcwelding_date, PDO::PARAM_STR);
     $stmh->bindValue(63, $lcpainting_date, PDO::PARAM_STR);
     $stmh->bindValue(64, $lcassembly_date, PDO::PARAM_STR);
     $stmh->bindValue(65, $main_draw, PDO::PARAM_STR);
     $stmh->bindValue(66, $eunsung_make_date, PDO::PARAM_STR);
     $stmh->bindValue(67, $eunsung_laser_date, PDO::PARAM_STR);
     $stmh->bindValue(68, $mainbending_date, PDO::PARAM_STR);
     $stmh->bindValue(69, $mainwelding_date, PDO::PARAM_STR);
     $stmh->bindValue(70, $mainpainting_date, PDO::PARAM_STR);
     $stmh->bindValue(71, $mainassembly_date, PDO::PARAM_STR);
     $stmh->bindValue(72, $memo2, PDO::PARAM_STR);	 
     $stmh->bindValue(73, $order_date1, PDO::PARAM_STR);	 
     $stmh->bindValue(74, $order_date2, PDO::PARAM_STR);	 
     $stmh->bindValue(75, $order_date3, PDO::PARAM_STR);	 
     $stmh->bindValue(76, $order_date4, PDO::PARAM_STR);	 
     $stmh->bindValue(77, $order_input_date1, PDO::PARAM_STR);	 
     $stmh->bindValue(78, $order_input_date2, PDO::PARAM_STR);	 
     $stmh->bindValue(79, $order_input_date3, PDO::PARAM_STR);	 
     $stmh->bindValue(80, $order_input_date4, PDO::PARAM_STR);	 
	 
     $stmh->bindValue(81, $first_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(82, $first_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(83, $first_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(84, $first_item4, PDO::PARAM_STR);	 
     $stmh->bindValue(85, $second_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(86, $second_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(87, $second_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(88, $second_item4, PDO::PARAM_STR);	 	 

     $stmh->bindValue(89, $third_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(90, $third_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(91, $third_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(92, $third_item4, PDO::PARAM_STR);	 	 	 
     $stmh->bindValue(93, $fourth_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(94, $fourth_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(95, $fourth_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(96, $fourth_item4, PDO::PARAM_STR);	 	 	 
     $stmh->bindValue(97, $fifth_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(98, $fifth_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(99, $fifth_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(100, $fifth_item4, PDO::PARAM_STR);	 	 	 
     $stmh->bindValue(101, $sixth_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(102, $sixth_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(103, $sixth_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(104, $sixth_item4, PDO::PARAM_STR);	 	 
     $stmh->bindValue(105, $seventh_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(106, $seventh_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(107, $seventh_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(108, $seventh_item4, PDO::PARAM_STR);	 	 	 
     $stmh->bindValue(109, $eighth_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(110, $eighth_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(111, $eighth_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(112, $eighth_item4, PDO::PARAM_STR);	 	 	 
     $stmh->bindValue(113, $ninth_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(114, $ninth_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(115, $ninth_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(116, $ninth_item4, PDO::PARAM_STR);	 	 	 
     $stmh->bindValue(117, $tenth_item1, PDO::PARAM_STR);	 
     $stmh->bindValue(118, $tenth_item2, PDO::PARAM_STR);	 
     $stmh->bindValue(119, $tenth_item3, PDO::PARAM_STR);	 
     $stmh->bindValue(120, $tenth_item4, PDO::PARAM_STR);		 
	$stmh->bindValue(121, $type2, PDO::PARAM_STR);
	$stmh->bindValue(122, $type3, PDO::PARAM_STR);
	$stmh->bindValue(123, $type4, PDO::PARAM_STR);
	$stmh->bindValue(124, $type5, PDO::PARAM_STR);
	$stmh->bindValue(125, $type6, PDO::PARAM_STR);
	$stmh->bindValue(126, $type7, PDO::PARAM_STR);
	$stmh->bindValue(127, $type8, PDO::PARAM_STR);
	$stmh->bindValue(128, $type9, PDO::PARAM_STR);
	$stmh->bindValue(129, $type10, PDO::PARAM_STR);
	$stmh->bindValue(130, $inseung2, PDO::PARAM_STR);
	$stmh->bindValue(131, $inseung3, PDO::PARAM_STR);
	$stmh->bindValue(132, $inseung4, PDO::PARAM_STR);
	$stmh->bindValue(133, $inseung5, PDO::PARAM_STR);
	$stmh->bindValue(134, $inseung6, PDO::PARAM_STR);
	$stmh->bindValue(135, $inseung7, PDO::PARAM_STR);
	$stmh->bindValue(136, $inseung8, PDO::PARAM_STR);
	$stmh->bindValue(137, $inseung9, PDO::PARAM_STR);
	$stmh->bindValue(138, $inseung10, PDO::PARAM_STR);
	$stmh->bindValue(139, $car_inside2, PDO::PARAM_STR);
	$stmh->bindValue(140, $car_inside3, PDO::PARAM_STR);
	$stmh->bindValue(141, $car_inside4, PDO::PARAM_STR);
	$stmh->bindValue(142, $car_inside5, PDO::PARAM_STR);
	$stmh->bindValue(143, $car_inside6, PDO::PARAM_STR);
	$stmh->bindValue(144, $car_inside7, PDO::PARAM_STR);
	$stmh->bindValue(145, $car_inside8, PDO::PARAM_STR);
	$stmh->bindValue(146, $car_inside9, PDO::PARAM_STR);
	$stmh->bindValue(147, $car_inside10, PDO::PARAM_STR);	
	$stmh->bindValue(148, $comment1, PDO::PARAM_STR);
	$stmh->bindValue(149, $comment2, PDO::PARAM_STR);
	$stmh->bindValue(150, $comment3, PDO::PARAM_STR);
	$stmh->bindValue(151, $comment4, PDO::PARAM_STR);
	$stmh->bindValue(152, $comment5, PDO::PARAM_STR);
	$stmh->bindValue(153, $comment6, PDO::PARAM_STR);
	$stmh->bindValue(154, $comment7, PDO::PARAM_STR);
	$stmh->bindValue(155, $comment8, PDO::PARAM_STR);
	$stmh->bindValue(156, $comment9, PDO::PARAM_STR);
	$stmh->bindValue(157, $comment10, PDO::PARAM_STR);
	$stmh->bindValue(158, $pdffile_name, PDO::PARAM_STR);	
	$stmh->bindValue(159, $copied_file, PDO::PARAM_STR);	
	$stmh->bindValue(160, $confirm, PDO::PARAM_STR);	
	$stmh->bindValue(161, $submemo, PDO::PARAM_STR);	
	$stmh->bindValue(162, $deliverynum, PDO::PARAM_STR);		
	$stmh->bindValue(163, $num, PDO::PARAM_STR);	 
     $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                                
  } 
  
 else {
	 // insert인 경우
	 // 데이터 신규 등록하는 구간
	 $first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
	try{
		$pdo->beginTransaction();

		$sql = "insert into mirae8440.outorder(" ;
		$sql .="checkstep, workplacename, address, ";
		$sql .="firstord, firstordman, firstordmantel, secondord, secondordman, secondordmantel, chargedman, chargedmantel, ";  //  11
		$sql .="orderday, measureday, drawday, deadline, workday, worker, endworkday, material1, material2, material3, ";  // 10
		$sql .="material4, material5, material6, widejamb, normaljamb, smalljamb, memo, ";  // 7
		$sql .="regist_day, update_day, delivery, delicar, delicompany, delipay, delimethod, demand, startday, testday, hpi, first_writer,";     // 10
		$sql .="type1, inseung1, su, bon_su, lc_su, etc_su, air_su, car_inside1, order_com1, order_text1,  order_com2, order_text2,  order_com3, order_text3,  order_com4, order_text4, ";  //16
		$sql .="lc_draw, lclaser_com, lclaser_date, lcbending_date, lcwelding_date, lcpainting_date, lcassembly_date, main_draw, eunsung_make_date, ";  //9
		$sql .="eunsung_laser_date, mainbending_date, mainwelding_date, mainpainting_date, mainassembly_date, memo2, "; //6 

		$sql .=" order_date1,  order_date2,  order_date3,  order_date4, order_input_date1, order_input_date2, order_input_date3, order_input_date4, ";   //8
		$sql .=" first_item1,  first_item2,  first_item3,  first_item4, second_item1, second_item2, second_item3, second_item4,  ";   //8
		$sql .=" third_item1,  third_item2,  third_item3,  third_item4, fourth_item1, fourth_item2, fourth_item3, fourth_item4, ";   //8
		$sql .=" fifth_item1,  fifth_item2,  fifth_item3,  fifth_item4, sixth_item1, sixth_item2, sixth_item3, sixth_item4,  ";   //8
		$sql .=" seventh_item1,  seventh_item2,  seventh_item3,  seventh_item4, eighth_item1, eighth_item2, eighth_item3, eighth_item4,  ";   //8
		$sql .=" ninth_item1,  ninth_item2,  ninth_item3,  ninth_item4, tenth_item1, tenth_item2, tenth_item3, tenth_item4,  ";   //8	
		$sql .=" type2, type3, type4, type5, type6, type7, type8, type9, type10,  ";   
		$sql .=" inseung2, inseung3, inseung4, inseung5, inseung6, inseung7, inseung8, inseung9, inseung10,  ";   
		$sql .=" car_inside2, car_inside3, car_inside4, car_inside5, car_inside6, car_inside7, car_inside8, car_inside9, car_inside10,  ";   	
		$sql .=" comment1, comment2, comment3, comment4, comment5, comment6, comment7, comment8, comment9, comment10,  ";   	
		$sql .=" pdffile_name, copied_file, confirm, submemo, deliverynum ";   	// 5개 추가
		$sql .= ") ";	 

		 $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "; // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 9
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 9
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 9
		 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";  // 총 10
		 $sql .=        "?, ?, ?, ?, ?,";  // 총 5
		 $sql .=        " ?)";    // 1  
		 
		 $stmh = $pdo->prepare($sql); 
		 $stmh->bindValue(1, $checkstep, PDO::PARAM_STR);             
		 $stmh->bindValue(2, $workplacename, PDO::PARAM_STR);             
		 $stmh->bindValue(3, $address, PDO::PARAM_STR);             
		 $stmh->bindValue(4, $firstord, PDO::PARAM_STR);             
		 $stmh->bindValue(5, $firstordman, PDO::PARAM_STR);             
		 $stmh->bindValue(6, $firstordmantel, PDO::PARAM_STR);             
		 $stmh->bindValue(7, $secondord, PDO::PARAM_STR);             
		 $stmh->bindValue(8, $secondordman, PDO::PARAM_STR);             
		 $stmh->bindValue(9, $secondordmantel, PDO::PARAM_STR);             
		 $stmh->bindValue(10, $chargedman, PDO::PARAM_STR);             
		 $stmh->bindValue(11, $chargedmantel, PDO::PARAM_STR);             
		 $stmh->bindValue(12, $orderday, PDO::PARAM_STR);             
		 $stmh->bindValue(13, $measureday, PDO::PARAM_STR);             
		 $stmh->bindValue(14, $drawday, PDO::PARAM_STR);             
		 $stmh->bindValue(15, $deadline, PDO::PARAM_STR);             
		 $stmh->bindValue(16, $workday, PDO::PARAM_STR);             
		 $stmh->bindValue(17, $worker, PDO::PARAM_STR);             
		 $stmh->bindValue(18, $endworkday, PDO::PARAM_STR);             
		 $stmh->bindValue(19, $material1, PDO::PARAM_STR);             
		 $stmh->bindValue(20, $material2, PDO::PARAM_STR);             
		 $stmh->bindValue(21, $material3, PDO::PARAM_STR);             
		 $stmh->bindValue(22, $material4, PDO::PARAM_STR);             
		 $stmh->bindValue(23, $material5, PDO::PARAM_STR);             
		 $stmh->bindValue(24, $material6, PDO::PARAM_STR);             
		 $stmh->bindValue(25, $widejamb, PDO::PARAM_STR);             
		 $stmh->bindValue(26, $normaljamb, PDO::PARAM_STR);             
		 $stmh->bindValue(27, $smalljamb, PDO::PARAM_STR);             
		 $stmh->bindValue(28, $memo, PDO::PARAM_STR);             
				  
		 $stmh->bindValue(29, $regist_day, PDO::PARAM_STR);             	 
		 $update_day=date("Y-m-d"); // 현재날짜 2020-01-20 형태로 지정	 
		 $stmh->bindValue(30, $update_day, PDO::PARAM_STR); 
		 
		 $stmh->bindValue(31, $delivery, PDO::PARAM_STR);             	 
		 $stmh->bindValue(32, $delicar, PDO::PARAM_STR);             	 
		 $stmh->bindValue(33, $delicompany, PDO::PARAM_STR);             	 
		 $stmh->bindValue(34, $delipay, PDO::PARAM_STR);             	 
		 $stmh->bindValue(35, $delimethod, PDO::PARAM_STR);    
		 $stmh->bindValue(36, $demand, PDO::PARAM_STR);    
		 $stmh->bindValue(37, $startday, PDO::PARAM_STR);             	 
		 $stmh->bindValue(38, $testday, PDO::PARAM_STR);             	 
		 $stmh->bindValue(39, $hpi, PDO::PARAM_STR);   	 
		 $stmh->bindValue(40, $first_writer, PDO::PARAM_STR);   	 
		
		 $stmh->bindValue(41, $type1, PDO::PARAM_STR);
		 $stmh->bindValue(42, $inseung1, PDO::PARAM_STR);
		 $stmh->bindValue(43, $su, PDO::PARAM_STR);
		 $stmh->bindValue(44, $bon_su, PDO::PARAM_STR);
		 $stmh->bindValue(45, $lc_su, PDO::PARAM_STR);
		 $stmh->bindValue(46, $etc_su, PDO::PARAM_STR);
		 $stmh->bindValue(47, $air_su, PDO::PARAM_STR);
		 $stmh->bindValue(48, $car_inside1, PDO::PARAM_STR);
		 $stmh->bindValue(49, $order_com1, PDO::PARAM_STR);
		 $stmh->bindValue(50, $order_text1, PDO::PARAM_STR);
		 $stmh->bindValue(51, $order_com2, PDO::PARAM_STR);
		 $stmh->bindValue(52, $order_text2, PDO::PARAM_STR);
		 $stmh->bindValue(53, $order_com3, PDO::PARAM_STR);
		 $stmh->bindValue(54, $order_text3, PDO::PARAM_STR);
		 $stmh->bindValue(55, $order_com4, PDO::PARAM_STR);
		 $stmh->bindValue(56, $order_text4, PDO::PARAM_STR);
		 $stmh->bindValue(57, $lc_draw, PDO::PARAM_STR);
		 $stmh->bindValue(58, $lclaser_com, PDO::PARAM_STR);
		 $stmh->bindValue(59, $lclaser_date, PDO::PARAM_STR);
		 $stmh->bindValue(60, $lcbending_date, PDO::PARAM_STR);
		 $stmh->bindValue(61, $lcwelding_date, PDO::PARAM_STR);
		 $stmh->bindValue(62, $lcpainting_date, PDO::PARAM_STR);
		 $stmh->bindValue(63, $lcassembly_date, PDO::PARAM_STR);
		 $stmh->bindValue(64, $main_draw, PDO::PARAM_STR);
		 $stmh->bindValue(65, $eunsung_make_date, PDO::PARAM_STR);
		 $stmh->bindValue(66, $eunsung_laser_date, PDO::PARAM_STR);
		 $stmh->bindValue(67, $mainbending_date, PDO::PARAM_STR);
		 $stmh->bindValue(68, $mainwelding_date, PDO::PARAM_STR);
		 $stmh->bindValue(69, $mainpainting_date, PDO::PARAM_STR);
		 $stmh->bindValue(70, $mainassembly_date, PDO::PARAM_STR);
		 $stmh->bindValue(71, $memo2, PDO::PARAM_STR);
		 $stmh->bindValue(72, $order_date1, PDO::PARAM_STR);	 
		 $stmh->bindValue(73, $order_date2, PDO::PARAM_STR);	 
		 $stmh->bindValue(74, $order_date3, PDO::PARAM_STR);	 
		 $stmh->bindValue(75, $order_date4, PDO::PARAM_STR);	 
		 $stmh->bindValue(76, $order_input_date1, PDO::PARAM_STR);	 
		 $stmh->bindValue(77, $order_input_date2, PDO::PARAM_STR);	 
		 $stmh->bindValue(78, $order_input_date3, PDO::PARAM_STR);	 
		 $stmh->bindValue(79, $order_input_date4, PDO::PARAM_STR);	 
		 $stmh->bindValue(80, $first_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(81, $first_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(82, $first_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(83, $first_item4, PDO::PARAM_STR);	 
		 $stmh->bindValue(84, $second_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(85, $second_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(86, $second_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(87, $second_item4, PDO::PARAM_STR);	
		 
		 $stmh->bindValue(88, $third_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(89, $third_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(90, $third_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(91, $third_item4, PDO::PARAM_STR);	 	 	 
		 $stmh->bindValue(92, $fourth_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(93, $fourth_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(94, $fourth_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(95, $fourth_item4, PDO::PARAM_STR);	 	 	 
		 $stmh->bindValue(96, $fifth_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(97, $fifth_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(98, $fifth_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(99, $fifth_item4, PDO::PARAM_STR);	 	 	 
		 $stmh->bindValue(100, $sixth_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(101, $sixth_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(102, $sixth_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(103, $sixth_item4, PDO::PARAM_STR);	 	 
		 $stmh->bindValue(104, $seventh_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(105, $seventh_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(106, $seventh_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(107, $seventh_item4, PDO::PARAM_STR);	 	 	 
		 $stmh->bindValue(108, $eighth_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(109, $eighth_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(110, $eighth_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(111, $eighth_item4, PDO::PARAM_STR);	 	 	 
		 $stmh->bindValue(112, $ninth_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(113, $ninth_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(114, $ninth_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(115, $ninth_item4, PDO::PARAM_STR);	 	 	 
		 $stmh->bindValue(116, $tenth_item1, PDO::PARAM_STR);	 
		 $stmh->bindValue(117, $tenth_item2, PDO::PARAM_STR);	 
		 $stmh->bindValue(118, $tenth_item3, PDO::PARAM_STR);	 
		 $stmh->bindValue(119, $tenth_item4, PDO::PARAM_STR);		 	 
		$stmh->bindValue(120, $type2, PDO::PARAM_STR);
		$stmh->bindValue(121, $type3, PDO::PARAM_STR);
		$stmh->bindValue(122, $type4, PDO::PARAM_STR);
		$stmh->bindValue(123, $type5, PDO::PARAM_STR);
		$stmh->bindValue(124, $type6, PDO::PARAM_STR);
		$stmh->bindValue(125, $type7, PDO::PARAM_STR);
		$stmh->bindValue(126, $type8, PDO::PARAM_STR);
		$stmh->bindValue(127, $type9, PDO::PARAM_STR);
		$stmh->bindValue(128, $type10, PDO::PARAM_STR);
		$stmh->bindValue(129, $inseung2, PDO::PARAM_STR);
		$stmh->bindValue(130, $inseung3, PDO::PARAM_STR);
		$stmh->bindValue(131, $inseung4, PDO::PARAM_STR);
		$stmh->bindValue(132, $inseung5, PDO::PARAM_STR);
		$stmh->bindValue(133, $inseung6, PDO::PARAM_STR);
		$stmh->bindValue(134, $inseung7, PDO::PARAM_STR);
		$stmh->bindValue(135, $inseung8, PDO::PARAM_STR);
		$stmh->bindValue(136, $inseung9, PDO::PARAM_STR);
		$stmh->bindValue(137, $inseung10, PDO::PARAM_STR);
		$stmh->bindValue(138, $car_inside2, PDO::PARAM_STR);
		$stmh->bindValue(139, $car_inside3, PDO::PARAM_STR);
		$stmh->bindValue(140, $car_inside4, PDO::PARAM_STR);
		$stmh->bindValue(141, $car_inside5, PDO::PARAM_STR);
		$stmh->bindValue(142, $car_inside6, PDO::PARAM_STR);
		$stmh->bindValue(143, $car_inside7, PDO::PARAM_STR);
		$stmh->bindValue(144, $car_inside8, PDO::PARAM_STR);
		$stmh->bindValue(145, $car_inside9, PDO::PARAM_STR);
		$stmh->bindValue(146, $car_inside10, PDO::PARAM_STR);
		$stmh->bindValue(147, $comment1, PDO::PARAM_STR);
		$stmh->bindValue(148, $comment2, PDO::PARAM_STR);
		$stmh->bindValue(149, $comment3, PDO::PARAM_STR);
		$stmh->bindValue(150, $comment4, PDO::PARAM_STR);
		$stmh->bindValue(151, $comment5, PDO::PARAM_STR);
		$stmh->bindValue(152, $comment6, PDO::PARAM_STR);
		$stmh->bindValue(153, $comment7, PDO::PARAM_STR);
		$stmh->bindValue(154, $comment8, PDO::PARAM_STR);
		$stmh->bindValue(155, $comment9, PDO::PARAM_STR);
		$stmh->bindValue(156, $comment10, PDO::PARAM_STR);	
		$stmh->bindValue(157, $pdffile_name, PDO::PARAM_STR);	
		$stmh->bindValue(158, $copied_file, PDO::PARAM_STR);	
		$stmh->bindValue(159, $confirm, PDO::PARAM_STR);	
		$stmh->bindValue(160, $submemo, PDO::PARAM_STR);	
		$stmh->bindValue(161, $deliverynum, PDO::PARAM_STR);	
		
		$sql .=" , , ,  "; 

		$stmh->execute();
		$pdo->commit(); 
		} catch (PDOException $Exception) {
		  $pdo->rollBack();
		print "오류: ".$Exception->getMessage();
		}   


	
	
	// num마지막 추출하기
	try{
      $sql = "select * from mirae8440.outorder order by num desc ";
      $stmh = $pdo->prepare($sql); 
      $stmh->execute();         
      $row = $stmh->fetch(PDO::FETCH_ASSOC);	
	  $num = $row["num"];
	 } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       } 

// 	임시키를 정식 번호로 변경해준다. 

// 신규데이터인 경우 첨부파일/첨부이미지 추가한 것이 있으면 parentid 변경해줌
// 신규데이터인경우 num을 추출한 후 view로 보여주기
  
  try{
        $pdo->beginTransaction();   
        $sql = "update ".$DB.".picuploads set parentnum=? where parentnum=? ";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $num, PDO::PARAM_STR);          
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);   
        $stmh->execute();
        $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }
	   
	
	}
 }  // end of (서한, 덴크리) 아니냐?

 $data = array(
	"num" =>  $num
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
  
 
 ?>