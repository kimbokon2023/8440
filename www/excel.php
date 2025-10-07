<?php
include "./PHPExcel_1.8.0/Classes/PHPExcel.php";

$objPHPExcel = new PHPExcel();

$arrVelvet = array();

// 셀병합방법  $objPHPExcel->setActiveSheetIndex(0)		->mergeCells('A1:D1')		->setCellValue('A1', "Col병합");$objPHPExcel->setActiveSheetIndex(0)		->mergeCells('A2:A5')		->setCellValue('A2', "Row병합");



// 앞자리가 0으로 끝나는 숫자값을 number에 담는다.

$tmp = "김보곤";

// $arrVelvet[1] = array("number" => "00001", "name" => $tmp, "position" => "센터, 리더, 메인래퍼", "birthday" => "03월 29일");
// $arrVelvet[2] = array("number" => "00002", "name" => "슬기", "position" => "리드보컬, 메인댄서", "birthday" => "02월 10일");
// $arrVelvet[3] = array("number" => "00003", "name" => "웬디", "position" => "메인보컬", "birthday" => "02월 21일");
// $arrVelvet[4] = array("number" => "00004", "name" => "조이", "position" => "리드래퍼, 서브보컬", "birthday" => "09월 03일");
// $arrVelvet[5] = array("number" => "00005", "name" => "예리", "position" => "서브래퍼, 서브보컬", "birthday" => "03월 05일");

// $objPHPExcel -> setActiveSheetIndex(0)
    // -> setCellValue("A1", "NO.")
    // -> setCellValue("B1", "이름")
    // -> setCellValue("C1", "포지션")
    // -> setCellValue("D1", "생일");
// foreach($arrVelvet as $key => $val) {
    // $num = 1 + $key;
    // $objPHPExcel -> setActiveSheetIndex(0)
    // -> setCellValue(sprintf("A%s", $num), $val['number'])
    // -> setCellValue(sprintf("B%s", $num), $val['name'])
    // -> setCellValueExplicit(sprintf("C%s", $num), $val['position'])
    // -> setCellValue(sprintf("D%s", $num), $val['birthday']);
    // $count++;
// }	
	
	
$objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFont()->setName('Dotum')->setSize(28);	
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1')->setCellValue('A1', "去 來 明 細 表");
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(35.25);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2')->setCellValue('A2', "");
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(2)->setRowHeight(25.25);

$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(3)->setRowHeight(19.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(4)->setRowHeight(14.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(5)->setRowHeight(20.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(6)->setRowHeight(16.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(7)->setRowHeight(16.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(8)->setRowHeight(16.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(9)->setRowHeight(16.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(10)->setRowHeight(16.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(11)->setRowHeight(10);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(12)->setRowHeight(10);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(13)->setRowHeight(24.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(14)->setRowHeight(24.5);
for ($i=0;$i<23;$i++)
  $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(15+$i)->setRowHeight(13.5);

for ($i=0;$i<6;$i++)
  $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(38+$i)->setRowHeight(18);

$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setName('Dotum')->setSize(15);	
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A3", "㈜미래기업 貴中");
$objPHPExcel -> getActiveSheet() -> getStyle("A3") -> getFont() -> setBold(true);

$styleArray = array(
  'font' => array(
    'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
  )
);

$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);   // 밑줄치는 것



// 미래기업 납선관련 기록 

$writeDay = '2022-07-31';

$objPHPExcel->getActiveSheet()->getStyle("A5:G50")->getFont()->setName('Dotum')->setSize(10);	

$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A5", "작 성 일");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B5:C5')->setCellValue('B5', $writeDay);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:A7')->setCellValue('A6', "납    선");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A8:A9')->setCellValue('A8', "이메일");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B6:C7')->setCellValue('B6', "㈜미래기업");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:C9')->setCellValue('B8', "mirae@8440.co.kr");

$objPHPExcel -> getActiveSheet() -> getStyle("A5:C9") -> getFont() -> setBold(true);

$objPHPExcel->getActiveSheet()->getStyle ( "A5:G10" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
$objPHPExcel->getActiveSheet()->getStyle ( "A5:G10" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );  // 세로 가운데 정렬

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "A5:C9" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "A5:C9" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

// 테두리 두께 관련 기타 옵션
// PHPExcel_Style_Border::BORDER_MEDIUM : 일반 두께
// PHPExcel_Style_Border::BORDER_THIN : 얅은 두께
// 셀 테두리 종류 관련 옵션
// 바깥쪽 테두리 : 예제의 소스와 동일  ->getOutline()->
// 셀 전체 (바깥 + 안쪽) :  ->getAllBorders()->
// 안쪽 : ->getInside()->
// 세로선 : ->getVertical()->
// 가로선 : ->getHorizontal()->

// 사업자관련 사항 기록

$companyName = '영일테크';
$CEO = '김상훈';
$registerNumber = '297-02-01719';
$companyAddress = '인천광역시 서구 검단로 777번길 34, 304호';
$companyItem1 = '건설업';
$companyItem2 = '승강기 인테리어';
$companyEmail = 'shoon821@naver.com' ;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E5:G5')->setCellValue('E5', $companyName);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F6:G6')->setCellValue('F6', $CEO);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F7:G7')->setCellValue('F7', $registerNumber);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F8:G8')->setCellValue('F8', $companyAddress);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F10:G10')->setCellValue('F10', $companyEmail);
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("E6", "대표이사");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("E7", "등록번호");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("E8", "사업장 주소");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("E9", "업  태");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("E10", "이메일");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("F9", $companyItem1);
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("G9", $companyItem2);

$objPHPExcel -> getActiveSheet() -> getStyle("E5:G10") -> getFont() -> setBold(true);

$objPHPExcel->getActiveSheet()->getStyle ( "A1:G10" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
$objPHPExcel->getActiveSheet()->getStyle ( "A1:G55" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );  // 세로 가운데 정렬

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "E5:G10" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "E5:G10" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

// 글씨 크기 넘어가는 것 자동조절
$objPHPExcel->getActiveSheet()->getStyle("E8:F8")->getFont()->setName('Dotum')->setSize(7);	


$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A13", "=F50");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("B13", "원)-VAT.별도");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C13:G13')->setCellValue('C13',"*현장별 잠설치공사 막판유/막판무/쪽잠 구분 청구");
$objPHPExcel -> getActiveSheet() -> getStyle("C13") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("FFFF00");
 // 폰트색상 변경
$objPHPExcel ->getActiveSheet()->getStyle ( "C13" )->getFont ()->getColor ()->setRGB ( 'FF0000' );  // 빨간색
$objPHPExcel->getActiveSheet()->getStyle("C13")->getFont()->setName('Dotum')->setSize(11);	
$objPHPExcel -> getActiveSheet() -> getStyle("C13") -> getFont() -> setBold(true);

$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A14", "품    명");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("B14", "규  격");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C14:D14')->setCellValue('C14',"수량");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("E14", "단가");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("F14", "금액");
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("G14", "비  고");
$objPHPExcel -> getActiveSheet() -> getStyle("A14:G14") -> getFont() -> setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A14:G14")->getFont()->setName('Dotum')->setSize(12);	

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "A14:G14" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "A14:G14" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );
$objPHPExcel->getActiveSheet()->getStyle ( "A14:G14" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬


$count = 1;

$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(11.75);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(6.88);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(6.88);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(8.5);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(9.38);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(24.88);

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "A15:G44" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "A15:G44" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "A41:G44" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "A41:G44" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

// $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:D%s", $count)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:D%s", $count)) -> getBorders() -> getAllBorders() -> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// $objPHPExcel -> getActiveSheet() -> getStyle("A1:D1") -> getFont() -> setBold(true);
// $objPHPExcel -> getActiveSheet() -> getStyle("A1:D1") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("CECBCA");
// $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:D%s", $count)) -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("F4F4F4");


// getNumberFormat(), setFormatCode() 함수를 사용한다.
// setFormatCode() 함수에 앞자리 0이 출력되게끔 문자열의 자리수 만큼 0을 입력한다.
// $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:A%s", $count)) -> getNumberFormat() -> setFormatCode("00000");


$objPHPExcel -> getActiveSheet() -> setTitle("시공내역");
$objPHPExcel -> setActiveSheetIndex(0);
$filename = iconv("UTF-8", "EUC-KR", "미래소장 거래명세표");

header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$filename.".xls");
header("Cache-Control:max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
$objWriter -> save("php://output");
?>