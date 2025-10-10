<?php
/**
 * rowDB.php
 * 데이터베이스 레코드($row)에서 개별 변수로 추출하는 공통 파일
 * 다른 PHP 파일에서 include하여 사용
 */

// 날짜 변환 함수 (trans_date가 정의되지 않은 경우를 대비)
if (!function_exists('trans_date')) {
    function trans_date($tdate)
    {
        if ($tdate != "0000-00-00" && $tdate != "1900-01-01" && $tdate != "") {
            $tdate = date("Y-m-d", strtotime($tdate));
        } else {
            $tdate = "";
        }
        return $tdate;
    }
}

// 기본 정보
$num = $row["num"];
$checkstep = $row["checkstep"];
$workplacename = $row["workplacename"];
$address = $row["address"];

// 발주처 정보
$firstord = $row["firstord"];
$firstordman = $row["firstordman"];
$firstordmantel = $row["firstordmantel"];
$secondord = $row["secondord"];
$secondordman = $row["secondordman"];
$secondordmantel = $row["secondordmantel"];
$chargedman = $row["chargedman"];
$chargedmantel = $row["chargedmantel"];

// 날짜 정보 (원본)
$orderday = $row["orderday"];
$measureday = $row["measureday"];
$drawday = $row["drawday"];
$deadline = $row["deadline"];
$workday = $row["workday"];
$endworkday = $row["endworkday"];
$demand = $row["demand"];
$startday = $row["startday"];
$testday = $row["testday"];

// 시공 정보
$worker = $row["worker"];

// 재질 정보
$material1 = $row["material1"];
$material2 = $row["material2"];
$material3 = $row["material3"];
$material4 = $row["material4"];
$material5 = $row["material5"];
$material6 = $row["material6"];

// 제품 수량 정보
$widejamb = $row["widejamb"];
$normaljamb = $row["normaljamb"];
$smalljamb = $row["smalljamb"];

// 메모 및 이력
$memo = $row["memo"];
$memo2 = $row["memo2"];
$regist_day = $row["regist_day"];
$update_day = $row["update_day"];
$first_writer = $row["first_writer"];
$update_log = $row["update_log"];

// 배송 정보
$delivery = $row["delivery"];
$delicar = $row["delicar"];
$delicompany = $row["delicompany"];
$delipay = $row["delipay"];
$delimethod = $row["delimethod"];

// 기타 정보
$hpi = $row["hpi"];

// 천장 타입 및 수량 정보
$type = $row["type"];
$inseung = $row["inseung"];
$su = $row["su"];
$bon_su = $row["bon_su"];
$lc_su = $row["lc_su"];
$etc_su = $row["etc_su"];
$air_su = $row["air_su"];
$car_insize = $row["car_insize"];

// 발주 정보
$order_com1 = $row["order_com1"];
$order_text1 = $row["order_text1"];
$order_date1 = $row["order_date1"];
$order_input_date1 = $row["order_input_date1"];

$order_com2 = $row["order_com2"];
$order_text2 = $row["order_text2"];
$order_date2 = $row["order_date2"];
$order_input_date2 = $row["order_input_date2"];

$order_com3 = $row["order_com3"];
$order_text3 = $row["order_text3"];
$order_date3 = $row["order_date3"];
$order_input_date3 = $row["order_input_date3"];

$order_com4 = $row["order_com4"];
$order_text4 = $row["order_text4"];
$order_date4 = $row["order_date4"];
$order_input_date4 = $row["order_input_date4"];

// L/C 천장 관련 일정
$lc_draw = $row["lc_draw"];
$lclaser_com = $row["lclaser_com"];
$lclaser_date = $row["lclaser_date"];
$lcbending_date = $row["lcbending_date"];
$lcwelding_date = $row["lcwelding_date"];
$lcpainting_date = $row["lcpainting_date"];
$lcassembly_date = $row["lcassembly_date"];

// 본천장 관련 일정
$main_draw = $row["main_draw"];
$eunsung_make_date = $row["eunsung_make_date"];
$eunsung_laser_date = $row["eunsung_laser_date"];
$mainbending_date = $row["mainbending_date"];
$mainwelding_date = $row["mainwelding_date"];
$mainpainting_date = $row["mainpainting_date"];
$mainassembly_date = $row["mainassembly_date"];

// 기타 제품 관련 일정
$etclaser_date = $row["etclaser_date"];
$etcbending_date = $row["etcbending_date"];
$etcwelding_date = $row["etcwelding_date"];
$etcpainting_date = $row["etcpainting_date"];
$etcassembly_date = $row["etcassembly_date"];

// 도면 정보
$dwglocation = $row["dwglocation"];
$designer = $row["designer"];	


// 날짜 변환 (유효한 날짜만 포맷팅)
$workday = trans_date($workday);
$demand = trans_date($demand);
$orderday = trans_date($orderday);
$deadline = trans_date($deadline);
$testday = trans_date($testday);

// L/C 천장 날짜 변환
$lc_draw = trans_date($lc_draw);
$lclaser_date = trans_date($lclaser_date);
$lcbending_date = trans_date($lcbending_date);
$lcwelding_date = trans_date($lcwelding_date);
$lcpainting_date = trans_date($lcpainting_date);
$lcassembly_date = trans_date($lcassembly_date);

// 본천장 날짜 변환
$main_draw = trans_date($main_draw);
$eunsung_make_date = trans_date($eunsung_make_date);
$eunsung_laser_date = trans_date($eunsung_laser_date);
$mainbending_date = trans_date($mainbending_date);
$mainwelding_date = trans_date($mainwelding_date);
$mainpainting_date = trans_date($mainpainting_date);
$mainassembly_date = trans_date($mainassembly_date);

// 기타 제품 날짜 변환
$etclaser_date = trans_date($etclaser_date);
$etcbending_date = trans_date($etcbending_date);
$etcwelding_date = trans_date($etcwelding_date);
$etcpainting_date = trans_date($etcpainting_date);
$etcassembly_date = trans_date($etcassembly_date);

// 발주 날짜 변환
$order_date1 = trans_date($order_date1);
$order_date2 = trans_date($order_date2);
$order_date3 = trans_date($order_date3);
$order_date4 = trans_date($order_date4);

// 발주 입력 날짜 변환
$order_input_date1 = trans_date($order_input_date1);
$order_input_date2 = trans_date($order_input_date2);
$order_input_date3 = trans_date($order_input_date3);
$order_input_date4 = trans_date($order_input_date4);

?>