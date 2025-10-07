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

$filename = './uploadexcel2.xls'; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.

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

               $add6 = $objWorksheet->getCell('F' . $i)->getValue(); // F열
               $add7 = $objWorksheet->getCell('G' . $i)->getValue(); // G열
               $add8 = $objWorksheet->getCell('H' . $i)->getValue(); // H열
               $add9 = $objWorksheet->getCell('I' . $i)->getValue(); // I열
			   $add9 = PHPExcel_Style_NumberFormat::toFormattedString($add9, 'YYYY-MM-DD'); 			   
			   
               $add10= $objWorksheet->getCell('J' . $i)->getValue(); // J열
			   $add10 = PHPExcel_Style_NumberFormat::toFormattedString($add10, 'YYYY-MM-DD'); 	
			   
               $add11= $objWorksheet->getCell('K' . $i)->getValue(); // K열
               $add12= $objWorksheet->getCell('L' . $i)->getValue(); // L열
               $add13= $objWorksheet->getCell('M' . $i)->getValue(); // M열
               $add14= $objWorksheet->getCell('N' . $i)->getValue(); // N열
               $add15= $objWorksheet->getCell('O' . $i)->getValue(); // O열
               $add16= $objWorksheet->getCell('P' . $i)->getValue(); // P열
			   /*
			   $add16 = PHPExcel_Style_NumberFormat::toFormattedString($add16, 'YYYY-MM-DD'); 
               $add17= $objWorksheet->getCell('Q' . $i)->getValue(); // Q열
               $add18= $objWorksheet->getCell('R' . $i)->getValue(); // R열
               $add19= $objWorksheet->getCell('S' . $i)->getValue(); // S열
			   $add19 = PHPExcel_Style_NumberFormat::toFormattedString($add19, 'YYYY-MM-DD'); 
               $add20= $objWorksheet->getCell('T' . $i)->getValue(); // T열
			   $add20 = PHPExcel_Style_NumberFormat::toFormattedString($add20, 'YYYY-MM-DD'); 
               $add21= $objWorksheet->getCell('U' . $i)->getValue(); // U열
               $add22= $objWorksheet->getCell('V' . $i)->getValue(); // V열
			   $add22 = PHPExcel_Style_NumberFormat::toFormattedString($add22, 'YYYY-MM-DD'); 
               $add23= $objWorksheet->getCell('W' . $i)->getValue(); // W열
               $add24= $objWorksheet->getCell('X' . $i)->getValue(); // X열
			   $add24 = PHPExcel_Style_NumberFormat::toFormattedString($add24, 'YYYY-MM-DD'); 
               $add25= $objWorksheet->getCell('Y' . $i)->getValue(); // Y열			   
               $add26= $objWorksheet->getCell('Z' . $i)->getValue(); // Z열
			   $add26 = PHPExcel_Style_NumberFormat::toFormattedString($add26, 'YYYY-MM-DD'); 
			   $num=$i+1;
			   */

//                $reg_date = PHPExcel_Style_NumberFormat::toFormattedString($reg_date, 'YYYY-MM-DD'); // 날짜 형태의 셀을 읽을때는 toFormattedString를 사용한다. 
try{
				 $pdo->beginTransaction();
						 $sql = "insert into mirae8440.work(orderday, firstord, secondord, workplacename, address, material1, "; //6
						 $sql .= "widejamb, smalljamb, measureday, deadline, secondordman, secondordmantel, worker, "; //7
						 $sql .= "delipay, delimethod, memo) ";  //3  총 16개
						 $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";// 총 16개 레코드 추가

						   
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
