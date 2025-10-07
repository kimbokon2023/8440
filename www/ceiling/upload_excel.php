 <!DOCTYPE HTML>  

  <?php

function db_connect(){  //DB연결을 함수로 정의
    $db_user ="mirae8440";    //추가한 계정이름(사용자명)
    $db_pass ="dnjstksfl1!!";     //비밀번호
    $db_host ="localhost";  
    $db_name ="mirae8440";
    $db_type ="mysql";
    $dsn ="$db_type:host=$db_host;db_name=$db_name;charset=utf8";

    try{ 
        $pdo=new PDO($dsn,$db_user,$db_pass);  
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,FALSE);
        
        //print "데이터베이스에 접속하였습니다.<br>";  접속할 때마다 프린트 할 이유 없기 때문에 주석처리
        
    } catch (PDOException $Exception) {  
        die('오류:'.$Exception->getMessage());
    }
    return $pdo;
}

$pdo = db_connect();  

require_once "../PHPExcel_1.8.0/Classes/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

$objPHPExcel = new PHPExcel();

require_once "../PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

$filename = './uploadexcel.xls'; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.

try {

  // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.

    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);

    // 읽기전용으로 설정

    $objReader->setReadDataOnly(true);

    // 엑셀파일을 읽는다

    $objExcel = $objReader->load($filename);

    // 첫번째 시트를 선택

    $objExcel->setActiveSheetIndex(0);

    $objWorksheet = $objExcel->getActiveSheet();

    $rowIterator = $objWorksheet->getRowIterator();

    foreach ($rowIterator as $row) { // 모든 행에 대해서

               $cellIterator = $row->getCellIterator();

               $cellIterator->setIterateOnlyExistingCells(false); 

    }

    $maxRow = $objWorksheet->getHighestRow();
	
	print "최대행 " . $maxRow;

    for ($i = 1 ; $i <= $maxRow ; $i++) {   // 2번째 줄부터 읽기 $i=2

               $add1 = $objWorksheet->getCell('A' . $i)->getValue(); // A열
			   $add1 = PHPExcel_Style_NumberFormat::toFormattedString($add1, 'YYYY-MM-DD'); 
			   
               $add2 = $objWorksheet->getCell('B' . $i)->getValue(); // B열

               $add3 = $objWorksheet->getCell('C' . $i)->getValue(); // C열

               $add4 = $objWorksheet->getCell('D' . $i)->getValue(); // D열

               $add5 = $objWorksheet->getCell('E' . $i)->getValue(); // E열
			   $add5 = PHPExcel_Style_NumberFormat::toFormattedString($add5, 'YYYY-MM-DD'); 
			   
               $add6 = $objWorksheet->getCell('F' . $i)->getValue(); // F열
               $add7 = $objWorksheet->getCell('G' . $i)->getValue(); // G열
               $add8 = $objWorksheet->getCell('H' . $i)->getValue(); // H열
               $add9 = $objWorksheet->getCell('I' . $i)->getValue(); // I열
			   
               $add10= $objWorksheet->getCell('J' . $i)->getValue(); // J열
			   
               $add11= $objWorksheet->getCell('K' . $i)->getValue(); // K열
               $add12= $objWorksheet->getCell('L' . $i)->getValue(); // L열
               $add13= $objWorksheet->getCell('M' . $i)->getValue(); // M열
               $add14= $objWorksheet->getCell('N' . $i)->getValue(); // N열
               $add15= $objWorksheet->getCell('O' . $i)->getValue(); // O열
               $add16= $objWorksheet->getCell('P' . $i)->getValue(); // P열
               $add17= $objWorksheet->getCell('Q' . $i)->getValue(); // Q열
               $add18= $objWorksheet->getCell('R' . $i)->getValue(); // R열
               $add19= $objWorksheet->getCell('S' . $i)->getValue(); // S열
               $add20= $objWorksheet->getCell('T' . $i)->getValue(); // T열
               $add21= $objWorksheet->getCell('U' . $i)->getValue(); // U열
               $add22= $objWorksheet->getCell('V' . $i)->getValue(); // V열
			   $add22 = PHPExcel_Style_NumberFormat::toFormattedString($add22, 'YYYY-MM-DD'); 
               $add23= $objWorksheet->getCell('W' . $i)->getValue(); // W열
               $add24= $objWorksheet->getCell('X' . $i)->getValue(); // X열
			   $add24 = PHPExcel_Style_NumberFormat::toFormattedString($add24, 'YYYY-MM-DD'); 
               $add25= $objWorksheet->getCell('Y' . $i)->getValue(); // Y열			   
			   $add25 = PHPExcel_Style_NumberFormat::toFormattedString($add25, 'YYYY-MM-DD'); 			   
               $add26= $objWorksheet->getCell('Z' . $i)->getValue(); // Z열
			   $add26 = PHPExcel_Style_NumberFormat::toFormattedString($add26, 'YYYY-MM-DD'); 
               $add27= $objWorksheet->getCell('AA' . $i)->getValue(); // AA열
			   $add27 = PHPExcel_Style_NumberFormat::toFormattedString($add27, 'YYYY-MM-DD'); 			   
               $add28= $objWorksheet->getCell('AB' . $i)->getValue(); // AB열
			   $add28 = PHPExcel_Style_NumberFormat::toFormattedString($add28, 'YYYY-MM-DD'); 		
               $add29= $objWorksheet->getCell('AC' . $i)->getValue(); // AC열
			   $add29 = PHPExcel_Style_NumberFormat::toFormattedString($add29, 'YYYY-MM-DD'); 	
               $add30= $objWorksheet->getCell('AD' . $i)->getValue(); // AD열
			   $add30 = PHPExcel_Style_NumberFormat::toFormattedString($add30, 'YYYY-MM-DD'); 	
               $add31= $objWorksheet->getCell('AE' . $i)->getValue(); // AE열
			   $add31 = PHPExcel_Style_NumberFormat::toFormattedString($add31, 'YYYY-MM-DD'); 	
               $add32= $objWorksheet->getCell('AF' . $i)->getValue(); // AF열
			   $add32 = PHPExcel_Style_NumberFormat::toFormattedString($add32, 'YYYY-MM-DD'); 	
               $add33= $objWorksheet->getCell('AG' . $i)->getValue(); // AG열
			   $add33 = PHPExcel_Style_NumberFormat::toFormattedString($add33, 'YYYY-MM-DD'); 	
               $add34= $objWorksheet->getCell('AH' . $i)->getValue(); // AH열
			   $add34 = PHPExcel_Style_NumberFormat::toFormattedString($add34, 'YYYY-MM-DD'); 	
               $add35= $objWorksheet->getCell('AI' . $i)->getValue(); // AI열
			   $add35 = PHPExcel_Style_NumberFormat::toFormattedString($add35, 'YYYY-MM-DD'); 	
               $add36= $objWorksheet->getCell('AJ' . $i)->getValue(); // AJ열
			   
               $add37= $objWorksheet->getCell('AK' . $i)->getValue(); // AK열
			   $add37 = PHPExcel_Style_NumberFormat::toFormattedString($add37, 'YYYY-MM-DD'); 	
               $add38= $objWorksheet->getCell('AL' . $i)->getValue(); // AL열
			   $add38 = PHPExcel_Style_NumberFormat::toFormattedString($add38, 'YYYY-MM-DD'); 	
               $add39= $objWorksheet->getCell('AM' . $i)->getValue(); // AM열

               $add40= $objWorksheet->getCell('AN' . $i)->getValue(); // AN열


		   
			   

try{
				 $pdo->beginTransaction();
						 $sql = "insert into mirae8440.ceiling(orderday, firstord, secondord, workplacename, deadline, type, inseung, su, bon_su, lc_su,  "; //10
						 $sql .= "etc_su, air_su, car_insize, order_com1, order_text1, order_com2, order_text2, order_com3, order_text3, order_com4, order_text4, "; //11
						 $sql .= "lc_draw, lclaser_com, lclaser_date, lcbending_date, lcwelding_date, lcpainting_date, lcassembly_date, main_draw, eunsung_make_date, eunsung_laser_date, ";  //10
						 $sql .= "mainbending_date, mainwelding_date, mainpainting_date, mainassembly_date, delivery, workday, demand, memo, memo2) ";  //9  총 40개
						 $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";// 총 40개 레코드 추가

						   
						 $stmh = $pdo->prepare($sql); 
						 $stmh->bindValue(1, $add1, PDO::PARAM_STR);  
						 $stmh->bindValue(2, $add2, PDO::PARAM_STR);  
						 $stmh->bindValue(3, $add3, PDO::PARAM_STR);  
						 $stmh->bindValue(4, $add4, PDO::PARAM_STR);  
						 $stmh->bindValue(5, $add5, PDO::PARAM_STR);  
						 $stmh->bindValue(6, $add6, PDO::PARAM_STR);  
						 $stmh->bindValue(7, $add7, PDO::PARAM_STR);  
						 $stmh->bindValue(8, $add8, PDO::PARAM_STR);  
						 $stmh->bindValue(9, $add9, PDO::PARAM_STR);  
						 $stmh->bindValue(10, $add10, PDO::PARAM_STR);  
						 $stmh->bindValue(11, $add11, PDO::PARAM_STR);  
						 $stmh->bindValue(12, $add12, PDO::PARAM_STR);  
						 $stmh->bindValue(13, $add13, PDO::PARAM_STR);  
						 $stmh->bindValue(14, $add14, PDO::PARAM_STR);  
						 $stmh->bindValue(15, $add15, PDO::PARAM_STR);  
						 $stmh->bindValue(16, $add16, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(17, $add17, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(18, $add18, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(19, $add19, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(20, $add20, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(21, $add21, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(22, $add22, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(23, $add23, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(24, $add24, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(25, $add25, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(26, $add26, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(27, $add27, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(28, $add28, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(29, $add29, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(30, $add30, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(31, $add31, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(32, $add32, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(33, $add33, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(34, $add34, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(35, $add35, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(36, $add36, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(37, $add37, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(38, $add38, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(39, $add39, PDO::PARAM_STR);  						 						 
						 $stmh->bindValue(40, $add40, PDO::PARAM_STR);  			
								 
						 $stmh->execute();
						 $pdo->commit(); 
						 } catch (PDOException $Exception) {
							  $pdo->rollBack();
						   print "오류: ".$Exception->getMessage();
						 }   
						 print "기록번호 " . $i . " " ;
					
} 


}

 catch (exception $e) {

    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';

}
?>	
