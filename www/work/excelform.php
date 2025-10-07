<?php

 function conv_num($num) {
$number = (int)str_replace(',', '', $num);
return $number;
}


// 23개 이상의 데이터도 처리토록 수정함

$arr = array();
   
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

 // 당월을 날짜 기간으로 설정
 
 if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,7) ;
	$fromdate=$fromdate . "-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}
 
  if(isset($_REQUEST["search"]))   //
 $search=$_REQUEST["search"];

 $orderby=" order by doneday desc "; 
	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 생산예정으로 구분		
$sql="select * from mirae8440.work where (doneday between date('$fromdate') and date('$Transtodate')) and (worker  like '%$search%') " . $orderby;  			
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	  		  

$counter=0;
$workday_arr=array();
$workplacename_arr=array();
$firstord_arr=array();
$secondord_arr=array();
$worker_arr=array();
$material_arr=array();
$demand_arr=array();
$visitfee_arr=array();
$totalfee_arr=array();

$wide_arr=array();
$normal_arr=array();
$narrow_arr=array();
$widefee_arr=array();
$normalfee_arr=array();
$narrowfee_arr=array();
$etc_arr=array();
$etcfee_arr=array();  

$wideunit_arr=array();
$normalunit_arr=array();
$narrowunit_arr=array();   
$etcunit_arr=array();   

 try{   
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	      
	//   print_r($row);   
			  $checkstep=$row["checkstep"];
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
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $workday=$row["workday"];
			  $doneday=$row["doneday"];  // 시공완료일
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  
			  $widejambworkfee=$row["widejambworkfee"];
			  $normaljambworkfee=$row["normaljambworkfee"];
			  $smalljambworkfee=$row["smalljambworkfee"];
			  $workfeedate=$row["workfeedate"];      // 시공비 처리일	
			  
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  
			  $demand=$row["demand"];  	   
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $hpi=$row["hpi"];	   
			  $delicompany=$row["delicompany"];	   
			  $delipay=$row["delipay"];	   
	
		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";		      
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";						
		      if($startday!="0000-00-00" and $startday!="1970-01-01" and $startday!="")  $startday = date("Y-m-d", strtotime( $startday) );
					else $startday="";	
		      if($testday!="0000-00-00" and $testday!="1970-01-01" and $testday!="")  $testday = date("Y-m-d", strtotime( $testday) );
					else $testday="";		
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";			
		      if($workfeedate!="0000-00-00" and $workfeedate!="1970-01-01" and $workfeedate!="")  $workfeedate = date("Y-m-d", strtotime( $workfeedate) );
					else $workfeedate="";	
					
	   
		   $doneday_arr[]=$doneday;
		   $workplacename_arr[]=$workplacename;
		   $address_arr[]=$address;
		   $secondord_arr[]=$secondord;   
		   $firstord_arr[]=$firstord;   
		   $worker_arr[]=$worker;   
		   $demand_arr[]=$demand;   
		   
		   // 판매'란 단어 있으면 실측비 제외		   
		   $findstr = '판매';
		   $pos = stripos($workplacename, $findstr);			   
		   
		if( trim($secondord) =='우성' or trim($secondord) == '한산' or $pos>0 )
			$visitfee_arr[]= 0;
		    else
				$visitfee_arr[]= 100000 ;
				 

		$materials="";
		$materials=$material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;		

		$material_arr[]=$materials;   		   
		   
		$wide_arr[] = 0;
		$widefee_arr[] = 0;
		$normal_arr[] = 0;
		$normalfee_arr[] = 0;
		$narrow_arr[] = 0;
		$narrowfee_arr[] = 0;
		$etc_arr[] = 0;
		$etcfee_arr[] = 0;

		$wideunit_arr[] = 0;
		$normalunit_arr[] = 0;
		$narrowunit_arr[] = 0;
		$etcunit_arr[] = 0;		   

		// 불량이란 단어가 들어가 있는 수량은 제외한다.		   
		$findstr = '불량';

		$pos = stripos($workplacename, $findstr);

		// 판매란 단어가 들어가 있는 수량은 제외한다.		   
		$findstr2 = '판매';

		$pos2 = stripos($workplacename, $findstr2);

		if($pos==0 and $pos2==0) {    
		$workitem="";
		if($widejamb!="")   {
		 $counter++;		
			$su = (int)$widejamb;						

				   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
				   $findstr = '불량';
				   $pos = stripos($workplacename, $findstr);							   
				   //판매란 단어가 들어가 있는 수량은 제외한다.		   
				   $findstr2 = '판매';
				   $pos2 = stripos($workplacename, $findstr2);
				   if($pos==0 and $pos2==0)
						 if((int)$widejambworkfee > 0)
							$unitfee = conv_num($widejambworkfee) ;  
							else if( trim($secondord) =='우성' and (trim(strtoupper($firstord)) =='OTIS' or trim($firstord)=='오티스') )
									 $unitfee = 105000;  
									   else
										  $unitfee = 80000;  	
									  
					$arr[$counter] = array("item" => $workplacename, "spec" => "막판", "num" => $su, "unitfee" => $unitfee, "memo" => $secondord);									
					   
						}
		if($normaljamb!="")   {
		 $counter++;		
			$su  = (int)$normaljamb;				
				   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
				   $findstr = '불량';
				   $pos = stripos($workplacename, $findstr);							   
				   //판매란 단어가 들어가 있는 수량은 제외한다.		   
				   $findstr2 = '판매';
				   $pos2 = stripos($workplacename, $findstr2);
				   if($pos==0 and $pos2==0)
							if((int)$normaljambworkfee > 0)
								 $unitfee = conv_num($normaljambworkfee) ;  								   
								else
									$unitfee = 70000 ;							
			
				$arr[] = array("item" => $workplacename, "spec" => "막판무", "num" => $su, "unitfee" => $unitfee, "memo" => $secondord);									
			}
		if($smalljamb!="") {
		 $counter++;		
			$su  = (int)$smalljamb;	
				   //불량이란 단어가 들어가 있는 수량은 제외한다.		   
				   $findstr = '불량';
				   $pos = stripos($workplacename, $findstr);							   
				   //판매란 단어가 들어가 있는 수량은 제외한다.		   
				   $findstr2 = '판매';
				   $pos2 = stripos($workplacename, $findstr2);
				   if($pos==0 and $pos2==0)
						if((int)$smalljambworkfee > 0)
							 $unitfee = conv_num($smalljambworkfee) ; 	
						 else
							$unitfee = 20000 ;								
			
			$arr[] = array("item" => $workplacename, "spec" => "쪽쟘", "num" => $su, "unitfee" => $unitfee, "memo" => $secondord);									
			}


			   
		   }	   
     }   	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


// 셀병합방법  $objPHPExcel->setActiveSheetIndex(0)		->mergeCells('A1:D1')		->setCellValue('A1', "Col병합");$objPHPExcel->setActiveSheetIndex(0)		->mergeCells('A2:A5')		->setCellValue('A2', "Row병합");


// print_r($sql);

include "../PHPExcel_1.8.0/Classes/PHPExcel.php";
$objPHPExcel = new PHPExcel();

// 앞자리가 0으로 끝나는 숫자값을 number에 담는다.

$tmp = "김보곤";
$count = 1 ;
foreach($arr as $key => $val) {
    $num = 14 + $key;
	$tmp = "=" . sprintf("D%s", $num) . "*" . sprintf("E%s", $num) ;
    $objPHPExcel -> setActiveSheetIndex(0)
    -> setCellValue(sprintf("A%s", $num), $val['item'])
    -> setCellValue(sprintf("B%s", $num), $val['spec'])
    -> setCellValue(sprintf("D%s", $num), (int) $val['num'])
    -> setCellValue(sprintf("E%s", $num), $val['unitfee']) 
    -> setCellValue(sprintf("F%s", $num), sprintf("=PRODUCT(D%s,E%s)", $num,$num) )
    -> setCellValue(sprintf("G%s", $num), $val['memo'] );
    $count++;
}	

if($count<=25)
{
	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("F40", sprintf("=SUM(F15:F%s)", (14+$count) ));		
    $objPHPExcel->getActiveSheet()->getStyle("E15:F40")->getNumberFormat()->setFormatCode('#,##0');
}
 else
 {
	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue( sprintf("F%s", (14+$count)) , sprintf("=SUM(F15:F%s)", (13+$count) ));	
	$objPHPExcel->getActiveSheet()->getStyle(sprintf("E15:F%s", (14+$count) ) )->getNumberFormat()->setFormatCode('#,##0');
 }	
	
$objPHPExcel->getActiveSheet()->getStyle("A13")->getNumberFormat()->setFormatCode('#,##0');
	
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

if($count<=25)
{
  for ($i=0;$i<23;$i++)
	$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(15+$i)->setRowHeight(13.5);
}
 else
 {
  for ($i=0;$i<$counter;$i++)
	$objPHPExcel->setActiveSheetIndex(0)->getRowDimension(15+$i)->setRowHeight(13.5);	
 }	 
 
if($count<=25)
{
	for ($i=0;$i<6;$i++)
	  $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(38+$i)->setRowHeight(18);
}
 else
 {
  for ($i=0;$i<6;$i++)
	  $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($count+13+$i)->setRowHeight(18);
 }


$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setName('Dotum')->setSize(15);	
$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A3", "㈜미래기업 貴中");
$objPHPExcel -> getActiveSheet() -> getStyle("A3") -> getFont() -> setBold(true);

$styleArray = array(
  'font' => array(
    'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
  )
);

$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);   // 밑줄치는 것

// $objPHPExcel -> setActiveSheetIndex(0) -> setCellValue("B5", date("Y-m-d", time()));  // 날짜형식으로 보이기



// 미래기업 납선관련 기록 

$writeDay = '2022-07-31';

$objPHPExcel->getActiveSheet()->getStyle("A5:G50")->getFont()->setName('Dotum')->setSize(10);	

$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A5", "작 성 일");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B5:C5')->setCellValue('B5', date("Y-m-d", time()));
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
if(trim($search)=='김상훈')
{
$companyName = '영일테크';
$CEO = '김상훈';
$registerNumber = '297-02-01719';
$companyAddress = '인천광역시 서구 검단로 777번길 34, 304호';
$companyItem1 = '건설업';
$companyItem2 = '승강기 인테리어';
$companyEmail = 'shoon821@naver.com' ;
}
if(trim($search)=='김운호')
{
$companyName = '지유테크';
$CEO = '김운호';
$registerNumber = '134-30-96975';
$companyAddress = '경기 안산시 천문2길 56';
$companyItem1 = '제조';
$companyItem2 = '조명설치,인테리어';
$companyEmail = 'ds7626@naver.com' ;
}
if(trim($search)=='유영')
{
$companyName = '스피드케어';
$CEO = '유영';
$registerNumber = '111-39-33460';
$companyAddress = '경기도군포시군포로471번길6';
$companyItem1 = '건설업';
$companyItem2 = '스텐설치';
$companyEmail = 'yy5948@naver.com' ;
}
if(trim($search)=='추영덕')
{
$companyName = '와이디테크';
$CEO = '추영덕';
$registerNumber = '105-35-03982';
$companyAddress = '인천광역시 남동구 장아산로158';
$companyItem1 = '제조업';
$companyItem2 = '승강기유지보수';
$companyEmail = '0303ydchoo@hanmail.net' ;
}
if(trim($search)=='손상민')
{
$companyName = '한금산업';
$CEO = '손상민';
$registerNumber = '661-37-00117';
$companyAddress = '경남 김해시 활천로 166번길 9, 3층 308호';
$companyItem1 = '도.소매';
$companyItem2 = '종목 : 승강기 부품';
$companyEmail = 'hankum3220@daum.net' ;
}
if(trim($search)=='박철우')
{
$companyName = '남경엘리베이터';
$CEO = '박철우';
$registerNumber = '396-24-01217';
$companyAddress = '경기도 안산시 단원구 선부로 217';
$companyItem1 = '제조업';
$companyItem2 = '승강기제작설치';
$companyEmail = 'pcw379@naver.com' ;
}
if(trim($search)=='조장우')
{
$companyName = '모아테크';
$CEO = '조장우';
$registerNumber = '137-07-55878';
$companyAddress = '인천광역시 미추홀구 한나루로526번길 55, 1층102호';
$companyItem1 = '제조업';
$companyItem2 = '일반철물';
$companyEmail = 'jwmoa@naver.com' ;
}
if(trim($search)=='이만희')
{
$companyName = '선진기술';
$CEO = '이만희';
$registerNumber = '105-53-00183';
$companyAddress = '경기도 고양시 덕양구 화중로219,101동703호';
$companyItem1 = '서비스업';
$companyItem2 = '엘리베이터설치및A/S';
$companyEmail = 'manylee0522@gmail.com' ;
}

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

// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
$objPHPExcel->getActiveSheet()->getStyle ( "E5:G10" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
$objPHPExcel->getActiveSheet()->getStyle ( "E5:G10" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

// 글씨 크기 넘어가는 것 자동조절
$objPHPExcel->getActiveSheet()->getStyle("E8:F8")->getFont()->setName('Dotum')->setSize(7);	


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

$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(11.75);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(6.88);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(6.88);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(8.5);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(9.38);
$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(24.88);


$objPHPExcel->getActiveSheet()->getStyle("A13")->getFont()->setName('Dotum')->setSize(14);	
$objPHPExcel -> getActiveSheet() -> getStyle("A13") -> getFont() -> setBold(true);

// 기준표 범위안 일 경우
if($count<=25)
{
	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A13", "=F40");    
	// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
	$objPHPExcel->getActiveSheet()->getStyle ( "A15:G40" )->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
	$objPHPExcel->getActiveSheet()->getStyle ( "A15:G40" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

	// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
	$objPHPExcel->getActiveSheet()->getStyle ( "A41:G44" )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

	$objPHPExcel->getActiveSheet()->getStyle ( "B15:D40" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬

	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A40", "합  계");
	$objPHPExcel->getActiveSheet()->getStyle ( "A40" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
	$objPHPExcel->getActiveSheet()->getStyle("A40")->getFont()->setName('Dotum')->setSize(12);	
	$objPHPExcel->getActiveSheet()->getStyle("B40:G40")->getFont()->setName('Dotum')->setSize(8);	
	$objPHPExcel -> getActiveSheet() -> getStyle("A40:A41") -> getFont() -> setBold(true);
	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A41", "* 특기사항");

	$objPHPExcel->getActiveSheet()->getStyle("A15:A39")->getFont()->setName('Dotum')->setSize(7);	
	$objPHPExcel->getActiveSheet()->getStyle ( "A1:G55" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );  // 세로 가운데 정렬
}
 else   // 기준표를 벗어난 경우
{
	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue("A13", sprintf("=F%s", (14+$count) ));
    
	// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
	$objPHPExcel->getActiveSheet()->getStyle ( sprintf("A15:G%s",  (14 + $count)))->getBorders ()->getInside () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
	$objPHPExcel->getActiveSheet()->getStyle ( sprintf("A15:G%s",  (14 + $count)) )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

	// 셀의 테두리 지정 (바깥쪽 테두리 - 진하게) 4각 테두리
	$objPHPExcel->getActiveSheet()->getStyle ( sprintf("A%s:G%s", (15 + $count), (18 + $count)) )->getBorders ()->getOutline () ->setBorderStyle ( PHPExcel_Style_Border::BORDER_THICK );

	$objPHPExcel->getActiveSheet()->getStyle ( sprintf("B15:D%s",  (14 + $count)) )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬

	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue( sprintf("A%s", (14+$count) ), "합  계");
	$objPHPExcel->getActiveSheet()->getStyle (  sprintf("A%s", (14+$count) ) )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );  // 가로 가운데 정렬
	$objPHPExcel->getActiveSheet()->getStyle( sprintf("A%s", (14+$count) ))->getFont()->setName('Dotum')->setSize(12);	
	$objPHPExcel->getActiveSheet()->getStyle( sprintf("B%s:G%s", (14 + $count), (14 + $count)))->getFont()->setName('Dotum')->setSize(8);	
	$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A%s:A%s", (14 + $count), (15 + $count))) -> getFont() -> setBold(true);
	$objPHPExcel -> setActiveSheetIndex(0)-> setCellValue(sprintf("A%s", (15+$count) ), "* 특기사항");
	
	$objPHPExcel->getActiveSheet()->getStyle( sprintf("A15:A%s", (13+$count) ))->getFont()->setName('Dotum')->setSize(7);	
	$objPHPExcel->getActiveSheet()->getStyle ( "A1:G100" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );  // 세로 가운데 정렬
}





$objPHPExcel -> getActiveSheet() -> setTitle($companyName . " 시공내역");
$objPHPExcel -> setActiveSheetIndex(0);
$filename = iconv("UTF-8", "EUC-KR", "미래소장(" . $companyName . ") 거래명세표");

header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$filename.".xls");
header("Cache-Control:max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
$objWriter -> save("php://output");


?>