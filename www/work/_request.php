<?php
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$checkstep = isset($_REQUEST["checkstep"]) ? $_REQUEST["checkstep"] : '';
$workplacename = isset($_REQUEST["workplacename"]) ? $_REQUEST["workplacename"] : '';
$address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : '';
$firstord = isset($_REQUEST["firstord"]) ? $_REQUEST["firstord"] : '';
$firstordman = isset($_REQUEST["firstordman"]) ? $_REQUEST["firstordman"] : '';
$firstordmantel = isset($_REQUEST["firstordmantel"]) ? $_REQUEST["firstordmantel"] : '';
$secondord = isset($_REQUEST["secondord"]) ? $_REQUEST["secondord"] : '';
$secondordman = isset($_REQUEST["secondordman"]) ? $_REQUEST["secondordman"] : '';
$secondordmantel = isset($_REQUEST["secondordmantel"]) ? $_REQUEST["secondordmantel"] : '';
$chargedman = isset($_REQUEST["chargedman"]) ? $_REQUEST["chargedman"] : '';
$chargedmantel = isset($_REQUEST["chargedmantel"]) ? $_REQUEST["chargedmantel"] : '';
$orderday = isset($_REQUEST["orderday"]) ? $_REQUEST["orderday"] : '';
$measureday = isset($_REQUEST["measureday"]) ? $_REQUEST["measureday"] : '';
$drawday = isset($_REQUEST["drawday"]) ? $_REQUEST["drawday"] : '';
$deadline = isset($_REQUEST["deadline"]) ? $_REQUEST["deadline"] : '';
$workday = isset($_REQUEST["workday"]) ? $_REQUEST["workday"] : '';
$worker = isset($_REQUEST["worker"]) ? $_REQUEST["worker"] : '';
$endworkday = isset($_REQUEST["endworkday"]) ? $_REQUEST["endworkday"] : '';
$doneday = isset($_REQUEST["doneday"]) ? $_REQUEST["doneday"] : ''; //시공완료일
$designer = isset($_REQUEST["designer"]) ? $_REQUEST["designer"] : '';  // 설계자
$dwglocation = isset($_REQUEST["dwglocation"]) ? $_REQUEST["dwglocation"] : '';  // 서버도면 저장위치
$work_order = isset($_REQUEST["work_order"]) ? $_REQUEST["work_order"] : '';  // 작업순서 저장

$material1 = isset($_REQUEST["material1"]) ? $_REQUEST["material1"] : '';
$material2 = isset($_REQUEST["material2"]) ? $_REQUEST["material2"] : '';
$material3 = isset($_REQUEST["material3"]) ? $_REQUEST["material3"] : '';
$material4 = isset($_REQUEST["material4"]) ? $_REQUEST["material4"] : '';
$material5 = isset($_REQUEST["material5"]) ? $_REQUEST["material5"] : '';
$material6 = isset($_REQUEST["material6"]) ? $_REQUEST["material6"] : '';
$widejamb = isset($_REQUEST["widejamb"]) ? $_REQUEST["widejamb"] : '';
$normaljamb = isset($_REQUEST["normaljamb"]) ? $_REQUEST["normaljamb"] : '';
$smalljamb = isset($_REQUEST["smalljamb"]) ? $_REQUEST["smalljamb"] : '';
$widejambworkfee = isset($_REQUEST["widejambworkfee"]) ? $_REQUEST["widejambworkfee"] : '';
$normaljambworkfee = isset($_REQUEST["normaljambworkfee"]) ? $_REQUEST["normaljambworkfee"] : '';
$smalljambworkfee = isset($_REQUEST["smalljambworkfee"]) ? $_REQUEST["smalljambworkfee"] : '';
$workfeedate = isset($_REQUEST["workfeedate"]) ? $_REQUEST["workfeedate"] : '';
$memo = isset($_REQUEST["memo"]) ? $_REQUEST["memo"] : '';
$memo2 = isset($_REQUEST["memo2"]) ? $_REQUEST["memo2"] : '';
$regist_day = isset($_REQUEST["regist_day"]) ? $_REQUEST["regist_day"] : '';

$delivery = isset($_REQUEST["delivery"]) ? $_REQUEST["delivery"] : '';

// print $delivery;

$delicar = isset($_REQUEST["delicar"]) ? $_REQUEST["delicar"] : '';
$delicompany = isset($_REQUEST["delicompany"]) ? $_REQUEST["delicompany"] : '';
$delipay = isset($_REQUEST["delipay"]) ? $_REQUEST["delipay"] : '';
$delimethod = isset($_REQUEST["delimethod"]) ? $_REQUEST["delimethod"] : '';
$demand = isset($_REQUEST["demand"]) ? $_REQUEST["demand"] : '';
$startday = isset($_REQUEST["startday"]) ? $_REQUEST["startday"] : '';
$testday = isset($_REQUEST["testday"]) ? $_REQUEST["testday"] : '';
$hpi = isset($_REQUEST["hpi"]) ? $_REQUEST["hpi"] : '';
$first_writer = isset($_REQUEST["first_writer"]) ? $_REQUEST["first_writer"] : '';  
$update_log = isset($_REQUEST["update_log"]) ? $_REQUEST["update_log"] : '';
$checkhold = isset($_REQUEST["checkhold"]) ? $_REQUEST["checkhold"] : '';
$attachment = isset($_REQUEST["attachment"]) ? $_REQUEST["attachment"] : '';

$checkmat1 = isset($_REQUEST["checkmat1"]) ? $_REQUEST["checkmat1"] : '';   // 재질 사급여부 체크하기
$checkmat2 = isset($_REQUEST["checkmat2"]) ? $_REQUEST["checkmat2"] : '';   // 재질 사급여부 체크하기
$checkmat3 = isset($_REQUEST["checkmat3"]) ? $_REQUEST["checkmat3"] : '';   // 재질 사급여부 체크하기  
$outsourcing = isset($_REQUEST["outsourcing"]) ? $_REQUEST["outsourcing"] : '';  
$madeconfirm = isset($_REQUEST["madeconfirm"]) ? $_REQUEST["madeconfirm"] : '';  
$mymemo = isset($_REQUEST["mymemo"]) ? $_REQUEST["mymemo"] : '';      // 나의 메모 남기기 이미래대리만 보는 메모
$assigndate = isset($_REQUEST["assigndate"]) ? $_REQUEST["assigndate"] : '';      // 배정일
$gapcover = isset($_REQUEST["gapcover"]) ? $_REQUEST["gapcover"] : '';      // 갭커버

// 2024년6월 12일 공사완료확인서 정보 json형태로 저장
$customer = isset($_REQUEST["customer"]) ? $_REQUEST["customer"] : '';   
$filename1 = isset($_REQUEST["filename1"]) ? $_REQUEST["filename1"] : '';   
$filename2 = isset($_REQUEST["filename2"]) ? $_REQUEST["filename2"] : '';   

   
?>