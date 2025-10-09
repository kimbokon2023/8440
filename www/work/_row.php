<?php  
$num = isset($row["num"]) ? $row["num"] : '';
$checkstep = isset($row["checkstep"]) ? $row["checkstep"] : '';
$workplacename = isset($row["workplacename"]) ? $row["workplacename"] : '';
$address = isset($row["address"]) ? $row["address"] : '';
$firstord = isset($row["firstord"]) ? $row["firstord"] : '';
$firstordman = isset($row["firstordman"]) ? $row["firstordman"] : '';
$firstordmantel = isset($row["firstordmantel"]) ? $row["firstordmantel"] : '';
$secondord = isset($row["secondord"]) ? $row["secondord"] : '';
$secondordman = isset($row["secondordman"]) ? $row["secondordman"] : '';
$secondordmantel = isset($row["secondordmantel"]) ? $row["secondordmantel"] : '';
$chargedman = isset($row["chargedman"]) ? $row["chargedman"] : '';
$chargedmantel = isset($row["chargedmantel"]) ? $row["chargedmantel"] : '';
$orderday = isset($row["orderday"]) ? $row["orderday"] : '';
$measureday = isset($row["measureday"]) ? $row["measureday"] : '';
$drawday = isset($row["drawday"]) ? $row["drawday"] : '';
$deadline = isset($row["deadline"]) ? $row["deadline"] : '';
$workday = isset($row["workday"]) ? $row["workday"] : '';
$worker = isset($row["worker"]) ? $row["worker"] : '';
$endworkday = isset($row["endworkday"]) ? $row["endworkday"] : '';
$doneday = isset($row["doneday"]) ? $row["doneday"] : ''; //시공완료일
$designer = isset($row["designer"]) ? $row["designer"] : '';  // 설계자
$dwglocation = isset($row["dwglocation"]) ? $row["dwglocation"] : '';  // 서버도면 저장위치
$work_order = isset($row["work_order"]) ? $row["work_order"] : '';  // 작업순서 저장

$material1 = isset($row["material1"]) ? $row["material1"] : '';
$material2 = isset($row["material2"]) ? $row["material2"] : '';
$material3 = isset($row["material3"]) ? $row["material3"] : '';
$material4 = isset($row["material4"]) ? $row["material4"] : '';
$material5 = isset($row["material5"]) ? $row["material5"] : '';
$material6 = isset($row["material6"]) ? $row["material6"] : '';
$widejamb = isset($row["widejamb"]) ? $row["widejamb"] : '';
$normaljamb = isset($row["normaljamb"]) ? $row["normaljamb"] : '';
$smalljamb = isset($row["smalljamb"]) ? $row["smalljamb"] : '';
$widejambworkfee = isset($row["widejambworkfee"]) ? $row["widejambworkfee"] : '';
$normaljambworkfee = isset($row["normaljambworkfee"]) ? $row["normaljambworkfee"] : '';
$smalljambworkfee = isset($row["smalljambworkfee"]) ? $row["smalljambworkfee"] : '';
$workfeedate = isset($row["workfeedate"]) ? $row["workfeedate"] : '';
$memo = isset($row["memo"]) ? $row["memo"] : '';
$memo2 = isset($row["memo2"]) ? $row["memo2"] : '';
$regist_day = isset($row["regist_day"]) ? $row["regist_day"] : '';

$delivery = isset($row["delivery"]) ? $row["delivery"] : '';

// print $delivery;

$delicar = isset($row["delicar"]) ? $row["delicar"] : '';
$delicompany = isset($row["delicompany"]) ? $row["delicompany"] : '';
$delipay = isset($row["delipay"]) ? $row["delipay"] : '';
$delimethod = isset($row["delimethod"]) ? $row["delimethod"] : '';
$demand = isset($row["demand"]) ? $row["demand"] : '';
$startday = isset($row["startday"]) ? $row["startday"] : '';
$testday = isset($row["testday"]) ? $row["testday"] : '';
$hpi = isset($row["hpi"]) ? $row["hpi"] : '';
$first_writer = isset($row["first_writer"]) ? $row["first_writer"] : '';  
$update_log = isset($row["update_log"]) ? $row["update_log"] : '';
$checkhold = isset($row["checkhold"]) ? $row["checkhold"] : '';
$attachment = isset($row["attachment"]) ? $row["attachment"] : '';

$checkmat1 = isset($row["checkmat1"]) ? $row["checkmat1"] : '';   // 재질 사급여부 체크하기
$checkmat2 = isset($row["checkmat2"]) ? $row["checkmat2"] : '';   // 재질 사급여부 체크하기
$checkmat3 = isset($row["checkmat3"]) ? $row["checkmat3"] : '';   // 재질 사급여부 체크하기  
$outsourcing = isset($row["outsourcing"]) ? $row["outsourcing"] : '';  
$madeconfirm = isset($row["madeconfirm"]) ? $row["madeconfirm"] : '';  
$mymemo = isset($row["mymemo"]) ? $row["mymemo"] : '';      // 나의 메모 남기기 이미래대리만 보는 메모
$assigndate = isset($row["assigndate"]) ? $row["assigndate"] : '';      // 배정일
$gapcover = isset($row["gapcover"]) ? $row["gapcover"] : '';      // 갭커버
$checkbox = isset($row["checkbox"]) ? $row["checkbox"] : 0;      // checkbox 필드

// 2024년6월 12일 공사완료확인서 정보 json형태로 저장
$customer = isset($row["customer"]) ? $row["customer"] : '';   
$filename1 = isset($row["filename1"]) ? $row["filename1"] : '';   
$filename2 = isset($row["filename2"]) ? $row["filename2"] : '';   

 
?>