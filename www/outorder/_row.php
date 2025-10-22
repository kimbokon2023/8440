<?php
/**
 * 외주 주문 데이터 행 변수 할당
 * 로컬 및 서버 환경 모두 지원
 * $row 배열에서 모든 필드를 개별 변수로 추출
 */

// 기본 정보
$num = $row["num"] ?? '';
$checkstep = $row["checkstep"] ?? '';
$checkbox = $row["checkbox"] ?? 0;
$whichcompany = $row["whichcompany"] ?? '';
$workplacename = $row["workplacename"] ?? '';
$address = $row["address"] ?? '';

// 발주처 정보
$firstord = $row["firstord"] ?? '';
$firstordman = $row["firstordman"] ?? '';
$firstordmantel = $row["firstordmantel"] ?? '';
$secondord = $row["secondord"] ?? '';
$secondordman = $row["secondordman"] ?? '';
$secondordmantel = $row["secondordmantel"] ?? '';

// 담당자 정보
$chargedman = $row["chargedman"] ?? '';
$chargedmantel = $row["chargedmantel"] ?? '';
$worker = $row["worker"] ?? '';

// 날짜 정보
$orderday = $row["orderday"] ?? '';
$measureday = $row["measureday"] ?? '';
$drawday = $row["drawday"] ?? '';
$deadline = $row["deadline"] ?? '';
$workday = $row["workday"] ?? '';
$endworkday = $row["endworkday"] ?? '';
$startday = $row["startday"] ?? '';
$testday = $row["testday"] ?? '';
$demand = $row["demand"] ?? '';
$regist_day = $row["regist_day"] ?? '';
$update_day = $row["update_day"] ?? '';

// 자재 정보
$material1 = $row["material1"] ?? '';
$material2 = $row["material2"] ?? '';
$material3 = $row["material3"] ?? '';
$material4 = $row["material4"] ?? '';
$material5 = $row["material5"] ?? '';
$material6 = $row["material6"] ?? '';

// 잼 정보
$widejamb = $row["widejamb"] ?? '';
$normaljamb = $row["normaljamb"] ?? '';
$smalljamb = $row["smalljamb"] ?? '';

// 메모 및 기타
$memo = $row["memo"] ?? '';
$memo2 = $row["memo2"] ?? '';
$submemo = $row["submemo"] ?? '';
$hpi = $row["hpi"] ?? '';
$first_writer = $row["first_writer"] ?? '';
$update_log = $row["update_log"] ?? '';
$deliverynum = $row["deliverynum"] ?? '';
$confirm = $row["confirm"] ?? '';
$pdffile_name = $row["pdffile_name"] ?? '';
$copied_file = $row["copied_file"] ?? '';

// 배송 정보
$delivery = $row["delivery"] ?? '';
$delicar = $row["delicar"] ?? '';
$delicompany = $row["delicompany"] ?? '';
$delipay = $row["delipay"] ?? '';
$delimethod = $row["delimethod"] ?? '';

// 타입 및 인승 정보 (1~10)
$type1 = $row["type1"] ?? '';
$type2 = $row["type2"] ?? '';
$type3 = $row["type3"] ?? '';
$type4 = $row["type4"] ?? '';
$type5 = $row["type5"] ?? '';
$type6 = $row["type6"] ?? '';
$type7 = $row["type7"] ?? '';
$type8 = $row["type8"] ?? '';
$type9 = $row["type9"] ?? '';
$type10 = $row["type10"] ?? '';

$inseung1 = $row["inseung1"] ?? '';
$inseung2 = $row["inseung2"] ?? '';
$inseung3 = $row["inseung3"] ?? '';
$inseung4 = $row["inseung4"] ?? '';
$inseung5 = $row["inseung5"] ?? '';
$inseung6 = $row["inseung6"] ?? '';
$inseung7 = $row["inseung7"] ?? '';
$inseung8 = $row["inseung8"] ?? '';
$inseung9 = $row["inseung9"] ?? '';
$inseung10 = $row["inseung10"] ?? '';

$car_inside1 = $row["car_inside1"] ?? '';
$car_inside2 = $row["car_inside2"] ?? '';
$car_inside3 = $row["car_inside3"] ?? '';
$car_inside4 = $row["car_inside4"] ?? '';
$car_inside5 = $row["car_inside5"] ?? '';
$car_inside6 = $row["car_inside6"] ?? '';
$car_inside7 = $row["car_inside7"] ?? '';
$car_inside8 = $row["car_inside8"] ?? '';
$car_inside9 = $row["car_inside9"] ?? '';
$car_inside10 = $row["car_inside10"] ?? '';

// 수량 정보
$su = $row["su"] ?? '';
$bon_su = $row["bon_su"] ?? '';
$lc_su = $row["lc_su"] ?? '';
$etc_su = $row["etc_su"] ?? '';
$air_su = $row["air_su"] ?? '';

// 발주 정보 (1~4)
$order_com1 = $row["order_com1"] ?? '';
$order_com2 = $row["order_com2"] ?? '';
$order_com3 = $row["order_com3"] ?? '';
$order_com4 = $row["order_com4"] ?? '';

$order_text1 = $row["order_text1"] ?? '';
$order_text2 = $row["order_text2"] ?? '';
$order_text3 = $row["order_text3"] ?? '';
$order_text4 = $row["order_text4"] ?? '';

$order_date1 = $row["order_date1"] ?? '';
$order_date2 = $row["order_date2"] ?? '';
$order_date3 = $row["order_date3"] ?? '';
$order_date4 = $row["order_date4"] ?? '';

$order_input_date1 = $row["order_input_date1"] ?? '';
$order_input_date2 = $row["order_input_date2"] ?? '';
$order_input_date3 = $row["order_input_date3"] ?? '';
$order_input_date4 = $row["order_input_date4"] ?? '';

// L/C 작업 정보
$lc_draw = $row["lc_draw"] ?? '';
$lclaser_com = $row["lclaser_com"] ?? '';
$lclaser_date = $row["lclaser_date"] ?? '';
$lcbending_date = $row["lcbending_date"] ?? '';
$lcwelding_date = $row["lcwelding_date"] ?? '';
$lcpainting_date = $row["lcpainting_date"] ?? '';
$lcassembly_date = $row["lcassembly_date"] ?? '';

// 본체 작업 정보
$main_draw = $row["main_draw"] ?? '';
$eunsung_make_date = $row["eunsung_make_date"] ?? '';
$eunsung_laser_date = $row["eunsung_laser_date"] ?? '';
$mainbending_date = $row["mainbending_date"] ?? '';
$mainwelding_date = $row["mainwelding_date"] ?? '';
$mainpainting_date = $row["mainpainting_date"] ?? '';
$mainassembly_date = $row["mainassembly_date"] ?? '';

// 항목 정보 (first ~ tenth, 1~4)
$first_item1 = $row["first_item1"] ?? '';
$first_item2 = $row["first_item2"] ?? '';
$first_item3 = $row["first_item3"] ?? '';
$first_item4 = $row["first_item4"] ?? '';

$second_item1 = $row["second_item1"] ?? '';
$second_item2 = $row["second_item2"] ?? '';
$second_item3 = $row["second_item3"] ?? '';
$second_item4 = $row["second_item4"] ?? '';

$third_item1 = $row["third_item1"] ?? '';
$third_item2 = $row["third_item2"] ?? '';
$third_item3 = $row["third_item3"] ?? '';
$third_item4 = $row["third_item4"] ?? '';

$fourth_item1 = $row["fourth_item1"] ?? '';
$fourth_item2 = $row["fourth_item2"] ?? '';
$fourth_item3 = $row["fourth_item3"] ?? '';
$fourth_item4 = $row["fourth_item4"] ?? '';

$fifth_item1 = $row["fifth_item1"] ?? '';
$fifth_item2 = $row["fifth_item2"] ?? '';
$fifth_item3 = $row["fifth_item3"] ?? '';
$fifth_item4 = $row["fifth_item4"] ?? '';

$sixth_item1 = $row["sixth_item1"] ?? '';
$sixth_item2 = $row["sixth_item2"] ?? '';
$sixth_item3 = $row["sixth_item3"] ?? '';
$sixth_item4 = $row["sixth_item4"] ?? '';

$seventh_item1 = $row["seventh_item1"] ?? '';
$seventh_item2 = $row["seventh_item2"] ?? '';
$seventh_item3 = $row["seventh_item3"] ?? '';
$seventh_item4 = $row["seventh_item4"] ?? '';

$eighth_item1 = $row["eighth_item1"] ?? '';
$eighth_item2 = $row["eighth_item2"] ?? '';
$eighth_item3 = $row["eighth_item3"] ?? '';
$eighth_item4 = $row["eighth_item4"] ?? '';

$ninth_item1 = $row["ninth_item1"] ?? '';
$ninth_item2 = $row["ninth_item2"] ?? '';
$ninth_item3 = $row["ninth_item3"] ?? '';
$ninth_item4 = $row["ninth_item4"] ?? '';

$tenth_item1 = $row["tenth_item1"] ?? '';
$tenth_item2 = $row["tenth_item2"] ?? '';
$tenth_item3 = $row["tenth_item3"] ?? '';
$tenth_item4 = $row["tenth_item4"] ?? '';

// 코멘트 정보 (1~10)
$comment1 = $row["comment1"] ?? '';
$comment2 = $row["comment2"] ?? '';
$comment3 = $row["comment3"] ?? '';
$comment4 = $row["comment4"] ?? '';
$comment5 = $row["comment5"] ?? '';
$comment6 = $row["comment6"] ?? '';
$comment7 = $row["comment7"] ?? '';
$comment8 = $row["comment8"] ?? '';
$comment9 = $row["comment9"] ?? '';
$comment10 = $row["comment10"] ?? '';

?>