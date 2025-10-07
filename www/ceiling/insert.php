 <?php  
session_start(); 

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문 받는측에서 필요한 정보임 ajax로 보내는 쪽에서 type : json

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
	 
  include '_requestDB.php';
  
  if (empty($price)) {
  $price = ''; // 빈 문자열인 경우 숫자 0으로 초기화
}
 
	$workday=trans_date($workday);
	$demand=trans_date($demand);
	$orderday=trans_date($orderday);
	$deadline=trans_date($deadline);
	$testday=trans_date($testday);
	$lc_draw=trans_date($lc_draw);
	$etc_draw=trans_date($etc_draw);
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

$oldnum = $num;	

	// 검색 테그 초기화
	$searchtag = '';

	// 조건에 따라 $searchtag에 문자열 추가
	if (!empty($part1)) {
		$searchtag .= '중국산휀, ';
	}
	if (!empty($part2)) {
		$searchtag .= '일반휀, ';
	}
	if (!empty($part3)) {
		$searchtag .= '휠터펜(LH용), ';
	}
	if (!empty($part4)) {
		$searchtag .= '판넬고정구(금성아크릴), ';
	}
	if (!empty($part5)) {
		$searchtag .= '비상구 스위치(건흥KH-9015), ';
	}
	if (!empty($part6)) {
		$searchtag .= '비상등, ';
	}
	if (!empty($part7)) {
		$searchtag .= '할로겐(7W-6500K), ';
	}
	if (!empty($part8)) {
		$searchtag .= 'T5(일반) 5W(300), ';
	}
	if (!empty($part9)) {
		$searchtag .= 'T5(일반) 11W(600), ';
	}
	if (!empty($part10)) {
		$searchtag .= 'T5(일반) 15W(900), ';
	}
	if (!empty($part11)) {
		$searchtag .= 'T5(일반) 20W(1200), ';
	}
	if (!empty($part12)) {
		$searchtag .= 'T5(KS) 6W(300), ';
	}
	if (!empty($part13)) {
		$searchtag .= 'T5(KS) 10W(600), ';
	}
	if (!empty($part14)) {
		$searchtag .= 'T5(KS) 15W(900), ';
	}
	if (!empty($part15)) {
		$searchtag .= 'T5(KS) 20W(1200), ';
	}
	if (!empty($part16)) {
		$searchtag .= '직관등 600mm, ';
	}
	if (!empty($part17)) {
		$searchtag .= '직관등 800mm, ';
	}
	if (!empty($part18)) {
		$searchtag .= '직관등 1000mm, ';
	}
	if (!empty($part19)) {
		$searchtag .= '직관등 1200mm, ';
	}
	if (!empty($part19)) {
		$searchtag .= '할로겐(7W -6500K KS), ';
	}

	// 마지막 쉼표와 공백 제거
	$searchtag = rtrim($searchtag, ', ');
  
 require_once("../lib/mydb.php");
 $pdo = db_connect();
    
 if ($mode=="modify"){
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea
	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.ceiling set ";
    $sql .="checkstep=?, workplacename=?, address=?, "; //3
    $sql .="firstord=?, firstordman=?, firstordmantel=?, secondord=?, secondordman=?, secondordmantel=?, chargedman=?, chargedmantel=?, ";  // 8
    $sql .="orderday=?, measureday=?, drawday=?, deadline=?, workday=?, worker=?, endworkday=?, material1=?, material2=?, material3=?, ";   // 10
    $sql .="material4=?, material5=?, material6=?, widejamb=?, normaljamb=?, smalljamb=?, memo=?,  ";  // 7
    $sql .="regist_day=?, update_day=?, delivery=?, delicar=?, delicompany=?, delipay=?, delimethod=?,  demand=?, startday=?, testday=?, hpi=?, first_writer=?, update_log=?, "; // 13
    $sql .="type=?, inseung=?, su=?, bon_su=?, lc_su=?, etc_su=?, air_su=?, car_insize=?, order_com1=?, order_text1=?,  order_com2=?, order_text2=?,  order_com3=?, order_text3=?,  order_com4=?, order_text4=?, ";  //16
	$sql .="lc_draw=?, lclaser_com=?, lclaser_date=?, lcbending_date=?, lcwelding_date=?, lcpainting_date=?, lcassembly_date=?, main_draw=?, eunsung_make_date=?, ";  //9
	$sql .="eunsung_laser_date=?, mainbending_date=?, mainwelding_date=?, mainpainting_date=?, mainassembly_date=?, memo2=?, "; //6
	$sql .=" order_date1=?,  order_date2=?,  order_date3=?,  order_date4=?, order_input_date1=?, order_input_date2=?, order_input_date3=?, order_input_date4=?,  ";   //8
    $sql .=" etclaser_date=?, etcbending_date=?, etcwelding_date=?, etcpainting_date=?, etcassembly_date=?, dwglocation=? ,  "; //6
	$sql .=" part1=?,part2=?,part3=?,part4=?,part5=?,part6=?,part7=?,part8=?,part9=?,part10=?, ";   //10
	$sql .=" part11=?, part12=?,part13=?,part14=?,part15=?,part16=?,part17=?,part18=?, part19=?, part20=?, searchtag=?, price=?, designer=? , boxwrap=?, outsourcing=?, work_order=?, laserdueday=?, cabledone=?, etc_draw=?, outsourcing_memo=? ";  
	$sql .=" where num=? LIMIT 1" ;        
	   
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
     $stmh->bindValue(42, $type, PDO::PARAM_STR);
     $stmh->bindValue(43, $inseung, PDO::PARAM_STR);
     $stmh->bindValue(44, $su, PDO::PARAM_STR);
     $stmh->bindValue(45, $bon_su, PDO::PARAM_STR);
     $stmh->bindValue(46, $lc_su, PDO::PARAM_STR);
     $stmh->bindValue(47, $etc_su, PDO::PARAM_STR);
     $stmh->bindValue(48, $air_su, PDO::PARAM_STR);
     $stmh->bindValue(49, $car_insize, PDO::PARAM_STR);
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
     $stmh->bindValue(81, $etclaser_date, PDO::PARAM_STR);
     $stmh->bindValue(82, $etcbending_date, PDO::PARAM_STR);
     $stmh->bindValue(83, $etcwelding_date, PDO::PARAM_STR);
     $stmh->bindValue(84, $etcpainting_date, PDO::PARAM_STR);
     $stmh->bindValue(85, $etcassembly_date, PDO::PARAM_STR);	 
     $stmh->bindValue(86, $dwglocation, PDO::PARAM_STR);	 
     $stmh->bindValue(87, $part1, PDO::PARAM_STR);	 
     $stmh->bindValue(88, $part2, PDO::PARAM_STR);	 
     $stmh->bindValue(89, $part3, PDO::PARAM_STR);	 
     $stmh->bindValue(90, $part4, PDO::PARAM_STR);	 
     $stmh->bindValue(91, $part5, PDO::PARAM_STR);	 
     $stmh->bindValue(92, $part6, PDO::PARAM_STR);	 
     $stmh->bindValue(93, $part7, PDO::PARAM_STR);	 
     $stmh->bindValue(94, $part8, PDO::PARAM_STR);	 
     $stmh->bindValue(95, $part9, PDO::PARAM_STR);	 
     $stmh->bindValue(96, $part10, PDO::PARAM_STR);	 
     $stmh->bindValue(97, $part11, PDO::PARAM_STR);	 
     $stmh->bindValue(98, $part12, PDO::PARAM_STR);	 
     $stmh->bindValue(99, $part13, PDO::PARAM_STR);	 
     $stmh->bindValue(100, $part14, PDO::PARAM_STR);	 
     $stmh->bindValue(101, $part15, PDO::PARAM_STR);	 
     $stmh->bindValue(102, $part16, PDO::PARAM_STR);	 
     $stmh->bindValue(103, $part17, PDO::PARAM_STR);	 
     $stmh->bindValue(104, $part18, PDO::PARAM_STR);	
     $stmh->bindValue(105, $part19, PDO::PARAM_STR);	
     $stmh->bindValue(106, $part20, PDO::PARAM_STR);	
     $stmh->bindValue(107, $searchtag, PDO::PARAM_STR);	
     $stmh->bindValue(108, $price, PDO::PARAM_STR);	
     $stmh->bindValue(109, $designer, PDO::PARAM_STR);	
     $stmh->bindValue(110, $boxwrap, PDO::PARAM_STR);	
     $stmh->bindValue(111, $outsourcing, PDO::PARAM_STR);	
     $stmh->bindValue(112, $work_order, PDO::PARAM_STR);	
	  $stmh->bindValue(113, $laserdueday, PDO::PARAM_STR);
	  $stmh->bindValue(114, $cabledone, PDO::PARAM_STR);
     $stmh->bindValue(115, $etc_draw, PDO::PARAM_STR);
     $stmh->bindValue(116, $outsourcing_memo, PDO::PARAM_STR);
	  $stmh->bindValue(117, $num, PDO::PARAM_STR);

     $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } 
 
 
 else	{
	 
	 // 데이터 신규 등록하는 구간
	 $first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
	 // 복사인 경우는 update_log도 함께 넣어준다.
	 if($mode!='copy')
		    $update_log='';
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.ceiling(" ;
     $sql .="checkstep, workplacename, address, ";
     $sql .="firstord, firstordman, firstordmantel, secondord, secondordman, secondordmantel, chargedman, chargedmantel, ";  //  11
     $sql .="orderday, measureday, drawday, deadline, workday, worker, endworkday, material1, material2, material3, ";  // 10
     $sql .="material4, material5, material6, widejamb, normaljamb, smalljamb, memo, ";  // 7
     $sql .="regist_day, update_day, delivery, delicar, delicompany, delipay, delimethod, demand, startday, testday, hpi, first_writer,";     // 10
	 $sql .="type, inseung, su, bon_su, lc_su, etc_su, air_su, car_insize, order_com1, order_text1,  order_com2, order_text2,  order_com3, order_text3,  order_com4, order_text4, ";  //16
	$sql .="lc_draw, lclaser_com, lclaser_date, lcbending_date, lcwelding_date, lcpainting_date, lcassembly_date, main_draw, eunsung_make_date, ";  //9
	$sql .="eunsung_laser_date, mainbending_date, mainwelding_date, mainpainting_date, mainassembly_date, memo2, "; //6 		
	$sql .=" order_date1,  order_date2,  order_date3,  order_date4, order_input_date1, order_input_date2, order_input_date3, order_input_date4,  ";   //8
	$sql .=" etclaser_date, etcbending_date, etcwelding_date, etcpainting_date, etcassembly_date, dwglocation, update_log, ";   //6
	$sql .=" part1,part2,part3,part4,part5,part6,part7,part8,part9,part10, ";   //10
	$sql .=" part11, part12,part13,part14,part15,part16,part17,part18, part19,  part20,  searchtag, price, designer, boxwrap, outsourcing, work_order , laserdueday, cabledone, etc_draw, outsourcing_memo "; 
	
     $sql .= ") ";
	 

     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "; // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ";  // 총 8
     $sql .=        "?, ?, ?, ?, ?,  ";  // 총 5
	 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )" ; 
	   
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
	
 $stmh->bindValue(41, $type, PDO::PARAM_STR);
     $stmh->bindValue(42, $inseung, PDO::PARAM_STR);
     $stmh->bindValue(43, $su, PDO::PARAM_STR);
     $stmh->bindValue(44, $bon_su, PDO::PARAM_STR);
     $stmh->bindValue(45, $lc_su, PDO::PARAM_STR);
     $stmh->bindValue(46, $etc_su, PDO::PARAM_STR);
     $stmh->bindValue(47, $air_su, PDO::PARAM_STR);
     $stmh->bindValue(48, $car_insize, PDO::PARAM_STR);
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
     $stmh->bindValue(80, $etclaser_date, PDO::PARAM_STR);
     $stmh->bindValue(81, $etcbending_date, PDO::PARAM_STR);
     $stmh->bindValue(82, $etcwelding_date, PDO::PARAM_STR);
     $stmh->bindValue(83, $etcpainting_date, PDO::PARAM_STR);
     $stmh->bindValue(84, $etcassembly_date, PDO::PARAM_STR);
     $stmh->bindValue(85, $dwglocation, PDO::PARAM_STR);
     $stmh->bindValue(86, $update_log, PDO::PARAM_STR);
     $stmh->bindValue(87, $part1, PDO::PARAM_STR);	 
     $stmh->bindValue(88, $part2, PDO::PARAM_STR);	 
     $stmh->bindValue(89, $part3, PDO::PARAM_STR);	 
     $stmh->bindValue(90, $part4, PDO::PARAM_STR);	 
     $stmh->bindValue(91, $part5, PDO::PARAM_STR);	 
     $stmh->bindValue(92, $part6, PDO::PARAM_STR);	 
     $stmh->bindValue(93, $part7, PDO::PARAM_STR);	 
     $stmh->bindValue(94, $part8, PDO::PARAM_STR);	 
     $stmh->bindValue(95, $part9, PDO::PARAM_STR);	 
     $stmh->bindValue(96, $part10, PDO::PARAM_STR);	 
     $stmh->bindValue(97, $part11, PDO::PARAM_STR);	 
     $stmh->bindValue(98, $part12, PDO::PARAM_STR);	 
     $stmh->bindValue(99, $part13, PDO::PARAM_STR);	 
     $stmh->bindValue(100, $part14, PDO::PARAM_STR);	 
     $stmh->bindValue(101, $part15, PDO::PARAM_STR);	 
     $stmh->bindValue(102, $part16, PDO::PARAM_STR);	 
     $stmh->bindValue(103, $part17, PDO::PARAM_STR);	 
     $stmh->bindValue(104, $part18, PDO::PARAM_STR);	
     $stmh->bindValue(105, $part19, PDO::PARAM_STR);	
     $stmh->bindValue(106, $part20, PDO::PARAM_STR);	
     $stmh->bindValue(107, $searchtag, PDO::PARAM_STR);	
     $stmh->bindValue(108, $price, PDO::PARAM_STR);	
     $stmh->bindValue(109, $designer, PDO::PARAM_STR);	
     $stmh->bindValue(110, $boxwrap, PDO::PARAM_STR);	
     $stmh->bindValue(111, $outsourcing, PDO::PARAM_STR);	
     $stmh->bindValue(112, $work_order, PDO::PARAM_STR);	
     $stmh->bindValue(113, $laserdueday, PDO::PARAM_STR);	
     $stmh->bindValue(114, $cabledone, PDO::PARAM_STR);	
     $stmh->bindValue(115, $etc_draw, PDO::PARAM_STR);   
     $stmh->bindValue(116, $outsourcing_memo, PDO::PARAM_STR);
	
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
   

   
// 파일복사(분리복사)일 경우 사진데이터/엑셀데이터/랜더링 데이터를 함께 복사해주는 루틴제작
if($mode=="copy")
{
	
// 과거 DATA num   => $oldnum   

 require_once("../lib/mydb.php");
 $pdo = db_connect();

// 레코드의 최신것 하나
$sql = "select * from mirae8440.ceiling ORDER BY num DESC";

try{  
		$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh    
		$row = $stmh->fetch(PDO::FETCH_ASSOC); 
        $num = $row["num"];
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	
	
	
// 특수타입 렌더링 이미지 복사

$tablename="ceiling";	 
$item="ceilingrendering";	  
 
	$sql=" select * from mirae8440.picuploads where  tablename ='$tablename' and item ='$item' and  parentnum ='$oldnum'";
	$WrappicNum=0; 
	$WrappicData=array(); 
    $uploads_dir = '../uploads'; //업로드 폴더 상위 uploads 폴더선택

	 try{  
	// 레코드 전체 sql 설정
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
	   $WrappicNum= 0;    
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		        //  123412411234.jpg => 123412411234_copy.jpg 로 파일명 수정
				array_push($WrappicData, str_replace (".", "_copy.", $row["picname"]) ) ;	
				 $source_url = $uploads_dir.'/'.$row["picname"];     //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
				 $destination_url = $uploads_dir.'/'.$WrappicData[$WrappicNum];     //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
				 // 서버에서 파일복사
                copy($source_url,$destination_url);
				$WrappicNum++;
			}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  

// 특수타입 렌더링 이미지 복사
$tablename="ceiling";	 
$item="ceilingrendering";	  
 
for($i=0;$i<count($WrappicData);$i++) { 
// insert
try{		 
	$pdo->beginTransaction();   
	$sql = "insert into mirae8440.picuploads ";
	$sql .=" (tablename, item, parentnum, picname) " ;        
	$sql .=" values(?, ?, ?, ?) " ;        
	   
	 $stmh = $pdo->prepare($sql); 
	 
	 $stmh->bindValue(1, $tablename, PDO::PARAM_STR);   
	 $stmh->bindValue(2, $item, PDO::PARAM_STR);   
	 $stmh->bindValue(3, $num, PDO::PARAM_STR);             
	 $stmh->bindValue(4, $WrappicData[$i], PDO::PARAM_STR);   
	 
	 
	 $stmh->execute();
	 $pdo->commit(); 
		} catch (PDOException $Exception) {
		   $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
	  } 
	}// end of for	
	

// 첨부파일 엑셀 자료도 복사해서 넣어줌

// 첨부 파일(엑셀파일) 에 대한 읽어오는 부분
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();	 
	 
	$sql=" select * from mirae8440.fileuploads where parentid ='$oldnum'";
	
	// 첨부파일 있는 것 불러오기 
	$savefilename_arr=array(); 
	$realname_arr=array(); 
	$attach_arr=array(); 
	$tablename='ceiling';
	$item = 'ceiling';
	$WrappicNum=0; 
	$WrappicData=array(); 
    $uploads_dir = '../uploads'; //업로드 폴더 상위 uploads 폴더선택

	 try{  
	// 레코드 전체 sql 설정
	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
	   $WrappicNum= 0;    
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				array_push($realname_arr, $row["realname"]);			
				array_push($savefilename_arr, $row["savename"]);			
				array_push($attach_arr, $row["parentid"]);		   
		   
		        //  123412411234.jpg => 123412411234_copy.jpg 로 파일명 수정
				array_push($WrappicData, str_replace (".", "_copy.", $row["savename"]) ) ;	
				 $source_url = $uploads_dir.'/'.$row["savename"];     //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
				 $destination_url = $uploads_dir.'/'.$WrappicData[$WrappicNum];     //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
				 // 서버에서 파일복사
                copy($source_url,$destination_url);
				$WrappicNum++;
			}		 
	   } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  

$id = $num;
for($i=0;$i<count($WrappicData);$i++) { 
	 
	try{		 
		$pdo->beginTransaction();   
		$sql = "insert into mirae8440.fileuploads ";
		$sql .=" (tablename, item, parentid, realname, savename) " ;        
		$sql .=" values(?, ?, ?, ?, ?) " ;        
		   
		 $stmh = $pdo->prepare($sql); 
		 
		 $stmh->bindValue(1, $tablename, PDO::PARAM_STR);   
		 $stmh->bindValue(2, $item, PDO::PARAM_STR);   
		 $stmh->bindValue(3, $id, PDO::PARAM_STR);             
		 $stmh->bindValue(4, $realname_arr[$i], PDO::PARAM_STR);   
		 $stmh->bindValue(5, $WrappicData[$i], PDO::PARAM_STR);   

	 
	 $stmh->execute();
	 $pdo->commit(); 
		} catch (PDOException $Exception) {
		   $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
	  } 
	}// end of for

}


  if ($mode!="modify"){
	  
	 require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
	$pdo = db_connect();
 
	 try{
		 $sql = "select * from mirae8440.ceiling order by num desc limit 1";
		 $stmh = $pdo->prepare($sql);  
		 $stmh->execute();                  
		 $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
		 $num=$row["num"];	 
		
		}
	   catch (PDOException $Exception) {
		   print "오류: ".$Exception->getMessage();
	  } 
	  
  }
 
  $data = [   
 'num' => $num
 ]; 
 
 echo json_encode($data, JSON_UNESCAPED_UNICODE);

 ?>
