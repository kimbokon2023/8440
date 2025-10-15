<?php
/**
 * 외주 주문 요청 파라미터 처리
 * 로컬 및 서버 환경 모두 지원
 * 모든 변수 ?? '' 형태로 초기화
 */

// 기본 정보
$checkstep = $_REQUEST["checkstep"] ?? '';
$workplacename = $_REQUEST["workplacename"] ?? '';
$address = $_REQUEST["address"] ?? '';

// 발주처 정보
$firstord = $_REQUEST["firstord"] ?? '';
$firstordman = $_REQUEST["firstordman"] ?? '';
$firstordmantel = $_REQUEST["firstordmantel"] ?? '';
$secondord = $_REQUEST["secondord"] ?? '';
$secondordman = $_REQUEST["secondordman"] ?? '';
$secondordmantel = $_REQUEST["secondordmantel"] ?? '';

// 담당자 정보
$chargedman = $_REQUEST["chargedman"] ?? '';
$chargedmantel = $_REQUEST["chargedmantel"] ?? '';
$worker = $_REQUEST["worker"] ?? '';

// 날짜 정보
$orderday = $_REQUEST["orderday"] ?? '';
$measureday = $_REQUEST["measureday"] ?? '';
$drawday = $_REQUEST["drawday"] ?? '';
$deadline = $_REQUEST["deadline"] ?? '';
$workday = $_REQUEST["workday"] ?? '';
$endworkday = $_REQUEST["endworkday"] ?? '';
$startday = $_REQUEST["startday"] ?? '';
$testday = $_REQUEST["testday"] ?? '';
$demand = $_REQUEST["demand"] ?? '';
$regist_day = $_REQUEST["regist_day"] ?? '';

// 자재 정보
$material1 = $_REQUEST["material1"] ?? '';
$material2 = $_REQUEST["material2"] ?? '';
$material3 = $_REQUEST["material3"] ?? '';
$material4 = $_REQUEST["material4"] ?? '';
$material5 = $_REQUEST["material5"] ?? '';
$material6 = $_REQUEST["material6"] ?? '';

// 잼 정보
$widejamb = $_REQUEST["widejamb"] ?? '';
$normaljamb = $_REQUEST["normaljamb"] ?? '';
$smalljamb = $_REQUEST["smalljamb"] ?? '';

// 메모 및 기타
$memo = $_REQUEST["memo"] ?? '';
$hpi = $_REQUEST["hpi"] ?? '';
$first_writer = $_REQUEST["first_writer"] ?? '';
$update_log = $_REQUEST["update_log"] ?? '';

// 배송 정보
$delivery = $_REQUEST["delivery"] ?? '';
$delicar = $_REQUEST["delicar"] ?? '';
$delicompany = $_REQUEST["delicompany"] ?? '';
$delipay = $_REQUEST["delipay"] ?? '';
$delimethod = $_REQUEST["delimethod"] ?? '';

// 덴크리 관련 추가 정보
$deliverynum = $_REQUEST["deliverynum"] ?? '';
$confirm = $_REQUEST["confirm"] ?? '';
$submemo = $_REQUEST["submemo"] ?? '';

// 항목 정보 (first ~ tenth, 1~4)
$first_item1 = $_REQUEST["first_item1"] ?? '';
$first_item2 = $_REQUEST["first_item2"] ?? '';
$first_item3 = $_REQUEST["first_item3"] ?? '';
$first_item4 = $_REQUEST["first_item4"] ?? '';

$second_item1 = $_REQUEST["second_item1"] ?? '';
$second_item2 = $_REQUEST["second_item2"] ?? '';
$second_item3 = $_REQUEST["second_item3"] ?? '';
$second_item4 = $_REQUEST["second_item4"] ?? '';

$third_item1 = $_REQUEST["third_item1"] ?? '';
$third_item2 = $_REQUEST["third_item2"] ?? '';
$third_item3 = $_REQUEST["third_item3"] ?? '';
$third_item4 = $_REQUEST["third_item4"] ?? '';

$fourth_item1 = $_REQUEST["fourth_item1"] ?? '';
$fourth_item2 = $_REQUEST["fourth_item2"] ?? '';
$fourth_item3 = $_REQUEST["fourth_item3"] ?? '';
$fourth_item4 = $_REQUEST["fourth_item4"] ?? '';

$fifth_item1 = $_REQUEST["fifth_item1"] ?? '';
$fifth_item2 = $_REQUEST["fifth_item2"] ?? '';
$fifth_item3 = $_REQUEST["fifth_item3"] ?? '';
$fifth_item4 = $_REQUEST["fifth_item4"] ?? '';

$sixth_item1 = $_REQUEST["sixth_item1"] ?? '';
$sixth_item2 = $_REQUEST["sixth_item2"] ?? '';
$sixth_item3 = $_REQUEST["sixth_item3"] ?? '';
$sixth_item4 = $_REQUEST["sixth_item4"] ?? '';

$seventh_item1 = $_REQUEST["seventh_item1"] ?? '';
$seventh_item2 = $_REQUEST["seventh_item2"] ?? '';
$seventh_item3 = $_REQUEST["seventh_item3"] ?? '';
$seventh_item4 = $_REQUEST["seventh_item4"] ?? '';

$eighth_item1 = $_REQUEST["eighth_item1"] ?? '';
$eighth_item2 = $_REQUEST["eighth_item2"] ?? '';
$eighth_item3 = $_REQUEST["eighth_item3"] ?? '';
$eighth_item4 = $_REQUEST["eighth_item4"] ?? '';

$ninth_item1 = $_REQUEST["ninth_item1"] ?? '';
$ninth_item2 = $_REQUEST["ninth_item2"] ?? '';
$ninth_item3 = $_REQUEST["ninth_item3"] ?? '';
$ninth_item4 = $_REQUEST["ninth_item4"] ?? '';

$tenth_item1 = $_REQUEST["tenth_item1"] ?? '';
$tenth_item2 = $_REQUEST["tenth_item2"] ?? '';
$tenth_item3 = $_REQUEST["tenth_item3"] ?? '';
$tenth_item4 = $_REQUEST["tenth_item4"] ?? '';

// 타입 및 인승 정보 (1~10)
$type1 = $_REQUEST["type1"] ?? '';
$type2 = $_REQUEST["type2"] ?? '';
$type3 = $_REQUEST["type3"] ?? '';
$type4 = $_REQUEST["type4"] ?? '';
$type5 = $_REQUEST["type5"] ?? '';
$type6 = $_REQUEST["type6"] ?? '';
$type7 = $_REQUEST["type7"] ?? '';
$type8 = $_REQUEST["type8"] ?? '';
$type9 = $_REQUEST["type9"] ?? '';
$type10 = $_REQUEST["type10"] ?? '';

$inseung1 = $_REQUEST["inseung1"] ?? '';
$inseung2 = $_REQUEST["inseung2"] ?? '';
$inseung3 = $_REQUEST["inseung3"] ?? '';
$inseung4 = $_REQUEST["inseung4"] ?? '';
$inseung5 = $_REQUEST["inseung5"] ?? '';
$inseung6 = $_REQUEST["inseung6"] ?? '';
$inseung7 = $_REQUEST["inseung7"] ?? '';
$inseung8 = $_REQUEST["inseung8"] ?? '';
$inseung9 = $_REQUEST["inseung9"] ?? '';
$inseung10 = $_REQUEST["inseung10"] ?? '';

$car_inside1 = $_REQUEST["car_inside1"] ?? '';
$car_inside2 = $_REQUEST["car_inside2"] ?? '';
$car_inside3 = $_REQUEST["car_inside3"] ?? '';
$car_inside4 = $_REQUEST["car_inside4"] ?? '';
$car_inside5 = $_REQUEST["car_inside5"] ?? '';
$car_inside6 = $_REQUEST["car_inside6"] ?? '';
$car_inside7 = $_REQUEST["car_inside7"] ?? '';
$car_inside8 = $_REQUEST["car_inside8"] ?? '';
$car_inside9 = $_REQUEST["car_inside9"] ?? '';
$car_inside10 = $_REQUEST["car_inside10"] ?? '';

// 수량 정보
$su = $_REQUEST["su"] ?? '';
$bon_su = $_REQUEST["bon_su"] ?? '';
$lc_su = $_REQUEST["lc_su"] ?? '';
$etc_su = $_REQUEST["etc_su"] ?? '';
$air_su = $_REQUEST["air_su"] ?? '';

// 발주 정보 (1~4)
$order_com1 = $_REQUEST["order_com1"] ?? '';
$order_com2 = $_REQUEST["order_com2"] ?? '';
$order_com3 = $_REQUEST["order_com3"] ?? '';
$order_com4 = $_REQUEST["order_com4"] ?? '';

$order_text1 = $_REQUEST["order_text1"] ?? '';
$order_text2 = $_REQUEST["order_text2"] ?? '';
$order_text3 = $_REQUEST["order_text3"] ?? '';
$order_text4 = $_REQUEST["order_text4"] ?? '';

// L/C 작업 정보
$lc_draw = $_REQUEST["lc_draw"] ?? '';
$lclaser_com = $_REQUEST["lclaser_com"] ?? '';
$lclaser_date = $_REQUEST["lclaser_date"] ?? '';
$lcbending_date = $_REQUEST["lcbending_date"] ?? '';
$lcwelding_date = $_REQUEST["lcwelding_date"] ?? '';
$lcpainting_date = $_REQUEST["lcpainting_date"] ?? '';
$lcassembly_date = $_REQUEST["lcassembly_date"] ?? '';

// 본체 작업 정보
$main_draw = $_REQUEST["main_draw"] ?? '';
$eunsung_make_date = $_REQUEST["eunsung_make_date"] ?? '';
$eunsung_laser_date = $_REQUEST["eunsung_laser_date"] ?? '';
$mainbending_date = $_REQUEST["mainbending_date"] ?? '';
$mainwelding_date = $_REQUEST["mainwelding_date"] ?? '';
$mainpainting_date = $_REQUEST["mainpainting_date"] ?? '';
$mainassembly_date = $_REQUEST["mainassembly_date"] ?? '';
$memo2 = $_REQUEST["memo2"] ?? '';

// 주문 날짜 정보
$order_date1 = $_REQUEST["order_date1"] ?? '';
$order_date2 = $_REQUEST["order_date2"] ?? '';
$order_date3 = $_REQUEST["order_date3"] ?? '';
$order_date4 = $_REQUEST["order_date4"] ?? '';

$order_input_date1 = $_REQUEST["order_input_date1"] ?? '';
$order_input_date2 = $_REQUEST["order_input_date2"] ?? '';
$order_input_date3 = $_REQUEST["order_input_date3"] ?? '';
$order_input_date4 = $_REQUEST["order_input_date4"] ?? '';

// 코멘트 정보 (1~10)
$comment1 = $_REQUEST["comment1"] ?? '';
$comment2 = $_REQUEST["comment2"] ?? '';
$comment3 = $_REQUEST["comment3"] ?? '';
$comment4 = $_REQUEST["comment4"] ?? '';
$comment5 = $_REQUEST["comment5"] ?? '';
$comment6 = $_REQUEST["comment6"] ?? '';
$comment7 = $_REQUEST["comment7"] ?? '';
$comment8 = $_REQUEST["comment8"] ?? '';
$comment9 = $_REQUEST["comment9"] ?? '';
$comment10 = $_REQUEST["comment10"] ?? '';
