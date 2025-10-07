<?php
include "../PHPExcel_1.8.0/Classes/PHPExcel.php";

 function conv_num($num) {
	$number = (int)str_replace(',', '', $num);
	return $number;
}


// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'ini.php';    

require_once("../lib/mydb.php");
$pdo = db_connect();

$objPHPExcel = new PHPExcel();

$arr = array();

try{
		 $sql = "select * from mirae8440." . $tablename . " where id=? ";
		 $stmh = $pdo->prepare($sql);  
		 $stmh->bindValue(1, $id, PDO::PARAM_STR);      
		 $stmh->execute();            
		  
		 $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
		  
         // include 'rowDB.php';    
		 // phpExcel을 사용하려면 include rowDB 안됨 에러남 원인파악해야 함수를

			$id=$row["id"];	
			$parent_id=$row["parent_id"];	
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
			$regist_day=$row["regist_day"];
			$measureday=$row["measureday"];  
			$workday=$row["workday"];
			$worker=$row["worker"]; 
			$doneday=$row["doneday"];  // 납기일  

			$item_name=$row["item_name"];
			$item_spec=$row["item_spec"];
			$item_num=$row["item_num"];
			$item_unit=$row["item_unit"];
			$item_memo=$row["item_memo"];
			$item_note=$row["item_note"];

			$material7=$row["material7"];
			$material8=$row["material8"];
			$material9=$row["material9"];
			$material10=$row["material10"];

			$memo=$row["memo"];
			$memo2=$row["memo2"];
			$regist_day=$row["regist_day"];  
			$update_log=$row["update_log"];

			$demand=$row["demand"];   
			$et_writeday=$row["et_writeday"];    // 견적서 작성일 
			$et_wpname=$row["et_wpname"];   
			if($et_wpname==null)
				$et_wpname=$address;   // 현장명이 없을때는 주소로

			$et_schedule=$row["et_schedule"];
			$et_person=$row["et_person"];
			$et_content=$row["et_content"];	  
			$et_note=$row["et_note"];	  

			$send_company=$row["send_company"];	  
			$send_chargedman=$row["send_chargedman"];	  
			$send_tel=$row["send_tel"];	  
			$send_date=$row["send_date"];	  
			$send_deadline=$row["send_deadline"];	 		 
		  
		  include 'load_ordersheet.php';
		  

		 }catch (PDOException $Exception) {
		   print "오류: ".$Exception->getMessage();
	 }

$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(4);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(14);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(24);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(4);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(4);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("H") -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("I") -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("J") -> setWidth(10);

	
$objPHPExcel->getActiveSheet()->getStyle("A1:Z200")->getFont()->setName('Dotum')->setSize(10);	 // 전체 폰트 적용
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setName('Dotum')->setSize(28);	
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E2')->setCellValue('A1', "발 주 서");

$objPHPExcel->getActiveSheet()->getStyle ( "A1:J200" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
$objPHPExcel->getActiveSheet()->getStyle ( "A1:J200" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );  // 세로 가운데 정렬


$objPHPExcel->setActiveSheetIndex(0)-> setCellValue('F1', "작 성");
$objPHPExcel->setActiveSheetIndex(0)-> setCellValue('G1', "검 토");
$objPHPExcel->setActiveSheetIndex(0)-> setCellValue('H1', "검 토");
$objPHPExcel->setActiveSheetIndex(0)-> setCellValue('I1', "검 토");
$objPHPExcel->setActiveSheetIndex(0)-> setCellValue('J1', "승 인");

$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(14);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(2)->setRowHeight(35);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(3)->setRowHeight(5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(4)->setRowHeight(25);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(5)->setRowHeight(12.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(6)->setRowHeight(12.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(7)->setRowHeight(12.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(8)->setRowHeight(12.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(9)->setRowHeight(12.5);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(10)->setRowHeight(25);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(11)->setRowHeight(7);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(26)->setRowHeight(7);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(35)->setRowHeight(7);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(47)->setRowHeight(15);

//텍스트 줄바꿈 허용
$objPHPExcel->getActiveSheet()->getStyle('A4:A10')->getAlignment()->setWrapText(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:A10')->setCellValue('A4', "수" . chr(10) . chr(10) . "신" . chr(10). chr(10) . "처");

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "업 체 명");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B5:B6')->setCellValue('B5', "현 장 명");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B7:B8')->setCellValue('B7', "발 주 일");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:B10')->setCellValue('B9', "납 기 일");

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:J3');   // 공백줄
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A11:J11');   // 공백줄
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A26:J26');   // 공백줄
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A35:J35');   // 공백줄
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A47:J47');   // 마지막줄

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:D4') ->setCellValue('C4', $send_company);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:D6')->setCellValue('C5',  $workplacename);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C7:D8')->setCellValue('C7',  $send_date);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C9:D10')->setCellValue('C9',  $send_deadline . " 이내");

$objPHPExcel->getActiveSheet()->getStyle('E4:E10')->getAlignment()->setWrapText(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E4:E10')->setCellValue('E4', "발" . chr(10) . chr(10) . "주" . chr(10). chr(10) . "처");

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F4:J5')->setCellValue('F4', "(주) 우성스틸앤엘리베이터");
$objPHPExcel->getActiveSheet()->getStyle("F4")->getFont()->setName('Dotum')->setSize(16);	

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6', "전화번호");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F7', "팩스번호");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F8', "메일주소");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F9', "본점주소");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F10', "서울사무소");

$objPHPExcel->getActiveSheet()->getStyle ( "G6:J10" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );  // 가로 왼쪽 정렬
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G6:J6')->setCellValue('G6', "02)6339-6325");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G7:J7')->setCellValue('G7', "02)6404-5787");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G8:J8')->setCellValue('G8', "woosung6339@gmail.com");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G9:J9')->setCellValue('G9', "경기도 광주시 곤지암읍 내선길112-3");
$objPHPExcel->getActiveSheet()->getStyle('G10:J10')->getAlignment()->setWrapText(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G10:J10')->setCellValue('G10', "서울시 강서구 마곡중앙로59-11" . chr(10) . "엠비즈타워509,510호");


// 12~ 25까지 설정
for ($i=0;$i<13;$i++)
{
  $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(12+$i)->setRowHeight(15);  
}
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A12:B12')->setCellValue('A12', "품 명");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C12', "규 격");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D12', "수량");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E12', "단위");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F12:I12')->setCellValue('F12', "사 양");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J12', "비 고");


// 품명/규격/수량/단위 출력
for ($i=0;$i<13;$i++)
{
	$j=$i+13;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $j . ':B' . $j );		
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $j . ':I' . $j );
		
	if((int)$item_numArr[$i]>0)	
	{
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'  . $j , $item_nameArr[$i]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $j , $item_specArr[$i]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $j , $item_numArr[$i]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $j , $item_unitArr[$i]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $j, $item_memoArr[$i]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $j, $item_noteArr[$i]);
	}
}

for ($i=0;$i<8;$i++)
{
	$j=$i+27;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $j . ':D' . $j );		
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $j . ':J' . $j );
}
	
for ($i=0;$i<12;$i++)
{
	$j=$i+35;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $j . ':J' . $j );			
}


$objPHPExcel->getActiveSheet()->getStyle ( "A27:J46" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );  // 가로 왼쪽 정렬
	
// 	27 ROW 기술하기
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A27', '*사양');
$objPHPExcel -> getActiveSheet() -> getStyle("A27") -> getFont() -> setBold(true);

$memoArr=explode( chr(13),$memo );  //  ascii 13 코드로 구분됨을 확인했다.
$noteArr=explode( chr(13),$memo2 );  //  ascii 13 코드로 구분됨을 확인했다.

for ($i=0;$i<14;$i++)
{	
    // 개행문자 제거 PHP공식
	$memoArr[$i] = trim(preg_replace('/\r\n|\r|\n/','',$memoArr[$i]));	
    
	if($i<=6)
	  {
		$j = $i + 28;  // row 28
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $j , chr(65 +$i) . '. ' .  $memoArr[$i]);			
	  }
	  else
	  {
		$j = $i + 28 - 7;  // row 28
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $j ,  chr(65 +$i) . '. ' . $memoArr[$i]);			
	  }
}

// 	36 ROW 기술하기
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A36', '*기타사항');
$objPHPExcel -> getActiveSheet() -> getStyle("A36") -> getFont() -> setBold(true);

for ($i=0;$i<10;$i++)
{	
    // 개행문자 제거 PHP공식
	$noteArr[$i] = trim(preg_replace('/\r\n|\r|\n/','',$noteArr[$i]));	    
	$j = $i + 37;  // row 37
	switch($i)
	{
		case  0 :		
			$schar = '①';
			break;
		case  1	:
			$schar = '②';
			break;
		case 2  :
			$schar = '③';
			break;
		case 3	:	
			$schar = '④';
			break;
		case 4  :
			$schar = '⑥';
			break;
		case 5	:	
			$schar = '⑥';
			break;
		case 6	:
			$schar = '⑦';
			break;
		case 7	:
			$schar = '⑧';
			break;
		case 8	:
			$schar = '⑨';
			break;
		case 9	:
			$schar = '⑩';
			break;	
	}		
					
	
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $j , $schar . '. ' .  $noteArr[$i]);			  
	
}
	
// 마지막줄 우성스틸 표시
$objPHPExcel-> getActiveSheet()->getStyle ( "A47" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );  // 가로 오른쪽 정렬
$objPHPExcel-> setActiveSheetIndex(0)->setCellValue('A47', '(주)우성스틸앤엘리베이터');
$objPHPExcel -> getActiveSheet() -> getStyle("A47") -> getFont() -> setBold(true);
$objPHPExcel -> getActiveSheet() -> getStyle("A12:J12") -> getFont() -> setBold(true);

// for ($i=0;$i<23;$i++)
  // $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(15+$i)->setRowHeight(13.5);

// for ($i=0;$i<6;$i++)
  // $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(38+$i)->setRowHeight(18);

// $objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setName('Dotum')->setSize(15);	
// $objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A3", "㈜미래기업 貴中");
// $objPHPExcel -> getActiveSheet() -> getStyle("A3") -> getFont() -> setBold(true);

// $styleArray = array(
  // 'font' => array(
    // 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
  // )
// );

// $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);   // 밑줄치는 것

// // $objPHPExcel -> setActiveSheetIndex(0) -> setCellValue("B5", date("Y-m-d", time()));  // 날짜형식으로 보이기




// $objPHPExcel -> getActiveSheet() -> getStyle("A5:C9") -> getFont() -> setBold(true);

// $objPHPExcel->getActiveSheet()->getStyle ( "A5:G10" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
// $objPHPExcel->getActiveSheet()->getStyle ( "A5:G10" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );  // 세로 가운데 정렬

// // 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
// $objPHPExcel->getActiveSheet()->getStyle ( "A5:C9" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
// $objPHPExcel->getActiveSheet()->getStyle ( "A5:C9" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

// 테두리 두께 관련 기타 옵션
// PHPExcel_Style_Border::BORDER_MEDIUM : 일반 두께
// PHPExcel_Style_Border::BORDER_THIN : 얅은 두께
// 셀 테두리 종류 관련 옵션
// 바깥쪽 테두리 : 예제의 소스와 동일  ->getOutline()->
// 셀 전체 (바깥 + 안쪽) :  ->getAllBorders()->
// 안쪽 : ->getInside()->
// 세로선 : ->getVertical()->
// 가로선 : ->getHorizontal()->


// $objPHPExcel -> getActiveSheet() -> getStyle("E5:G10") -> getFont() -> setBold(true);

// 글씨 크기 넘어가는 것 자동조절
// $objPHPExcel->getActiveSheet()->getStyle("E8:F8")->getFont()->setName('Dotum')->setSize(7);	


// $objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A13", "=F40");
// $objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("B13", "원)-VAT.별도");
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C13:G13')->setCellValue('C13',"*현장별 잠설치공사 막판유/막판무/쪽잠 구분 청구");
// $objPHPExcel -> getActiveSheet() -> getStyle("C13") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("FFFF00");
 // // 폰트색상 변경
// $objPHPExcel ->getActiveSheet()->getStyle ( "C13" )->getFont ()->getColor ()->setRGB ( 'FF0000' );  // 빨간색
// $objPHPExcel->getActiveSheet()->getStyle("C13")->getFont()->setName('Dotum')->setSize(11);	
// $objPHPExcel -> getActiveSheet() -> getStyle("C13") -> getFont() -> setBold(true);

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "A1:J2" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );
$objPHPExcel->getActiveSheet()->getStyle ( "A1:J2" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );
$objPHPExcel->getActiveSheet()->getStyle ( "A4:J10" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "A4:J10" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );
$objPHPExcel->getActiveSheet()->getStyle ( "A12:J12" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );
$objPHPExcel->getActiveSheet()->getStyle ( "A12:J12" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );
$objPHPExcel->getActiveSheet()->getStyle ( "A12:J25" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "A12:J25"  )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );

$objPHPExcel->getActiveSheet()->getStyle ( "A27:J34"  )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );
$objPHPExcel->getActiveSheet()->getStyle ( "A36:J46"  )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_MEDIUM );


// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
// $objPHPExcel->setActiveSheetIndex(0);
// // Set the page layout view as page layout
// $objPHPExcel->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_LAYOUT);

// $objPHPExcel->getActiveSheet()->getStyle ( "B15:D40" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬

// // $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:D%s", $count)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// // $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:D%s", $count)) -> getBorders() -> getAllBorders() -> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// // $objPHPExcel -> getActiveSheet() -> getStyle("A1:D1") -> getFont() -> setBold(true);
// // $objPHPExcel -> getActiveSheet() -> getStyle("A1:D1") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("CECBCA");
// // $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:D%s", $count)) -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("F4F4F4");

// $objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A40", "합  계");
// $objPHPExcel->getActiveSheet()->getStyle ( "A40" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
// $objPHPExcel->getActiveSheet()->getStyle("A40")->getFont()->setName('Dotum')->setSize(13);	
// $objPHPExcel->getActiveSheet()->getStyle("B40:G40")->getFont()->setName('Dotum')->setSize(8);	
// $objPHPExcel -> getActiveSheet() -> getStyle("A40:A41") -> getFont() -> setBold(true);
// $objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A41", "* 특기사항");

// $objPHPExcel->getActiveSheet()->getStyle("A13")->getFont()->setName('Dotum')->setSize(14);	
// $objPHPExcel -> getActiveSheet() -> getStyle("A13") -> getFont() -> setBold(true);

// // getNumberFormat(), setFormatCode() 함수를 사용한다.
// // setFormatCode() 함수에 앞자리 0이 출력되게끔 문자열의 자리수 만큼 0을 입력한다.
// // $objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:A%s", $count)) -> getNumberFormat() -> setFormatCode("00000");

// $objPHPExcel->getActiveSheet()->getStyle("A15:A39")->getFont()->setName('Dotum')->setSize(7);	

$companyName = $send_company;
$objPHPExcel -> getActiveSheet() -> setTitle($companyName . " 발주서");
$objPHPExcel -> setActiveSheetIndex(0);
$filename = iconv("UTF-8", "EUC-KR", "발주서(" . $companyName . ") ");

header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$filename.".xls");
header("Cache-Control:max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
$objWriter -> save("php://output");
?>