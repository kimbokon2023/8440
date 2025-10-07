 <?php

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/ceiling/estimate.ini",false);	
	
$sql="select * from mirae8440.ceiling WHERE workday = CURDATE() - INTERVAL 1 DAY " ;

$lc_total_12 = 0;
$bon_total_12 = 0;
$lc_total_13to17 = 0;
$bon_total_13to17 = 0;
$lc_total_18 = 0;
$bon_total_18 = 0;

try {
	
	$allstmh = $pdo->query($sql);
	
  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {		
    $inseung = $row["inseung"];			  		
    $bon_su = $row["bon_su"];			  
    $lc_su = $row["lc_su"];
    
    $inseung_num = intval($inseung);
    $lc_su_num = intval($lc_su);
    $bon_su_num = intval($bon_su);
    
    if ($inseung_num <= 12 && $lc_su_num >= 1) {
      $lc_total_12 += $lc_su_num ;
    }
    if ($inseung_num >= 13 && $inseung_num <= 17 && $lc_su_num >= 1) {
      $lc_total_13to17 += $lc_su_num;
    }
    if ($inseung_num >= 18 && $lc_su_num >= 1) {
      $lc_total_18 += $lc_su_num;
    }
    
    if ($inseung_num <= 12 && $bon_su_num >= 1) {
      $bon_total_12 += $bon_su_num;
    }
    if ($inseung_num >= 13 && $inseung_num <= 17 && $bon_su_num >= 1) {
      $bon_total_13to17 +=  $bon_su_num;
    }
    if ($inseung_num >= 18 && $bon_su_num >= 1) {
      $bon_total_18 +=  $bon_su_num;
    }
  }
} catch (PDOException $Exception) {
  print "오류: " . $Exception->getMessage();
}


$sum_12 = $lc_total_12 + $bon_total_12; 
$sum_13to17 = $lc_total_13to17 + $bon_total_13to17; 
$sum_18 = $lc_total_18 + $bon_total_18; 

// 합계표 만들기

// bon_total_12 12인승 이하
$bon_total_12_amount = str_replace(',', '', $bon_total_12) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
$lc_total_12_amount = str_replace(',', '', $lc_total_12) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
$bon_total_12_total = $bon_total_12_amount + $lc_total_12_amount ;
$bon_total_12_num = $bon_total_12 + $lc_total_12;

// 막판무
$bon_total_13to17_amount = str_replace(',', '', $bon_total_13to17) * intval(str_replace(',', '', $readIni["bon_unit_13to17"]));
$lc_total_13to17_amount = str_replace(',', '', $lc_total_13to17) * intval(str_replace(',', '', $readIni["lc_unit_13to17"]));
$bon_total_13to17_total = $bon_total_13to17_amount + $lc_total_13to17_amount ;
$bon_total_13to17_num = $bon_total_13to17 + $lc_total_13to17;
// 쪽쟘
$bon_total_18_amount = str_replace(',', '', $bon_total_18) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
$lc_total_18_amount = str_replace(',', '', $lc_total_18) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
$bon_total_18_total = $bon_total_18_amount + $lc_total_18_amount ;
$bon_total_18_num = $bon_total_18 + $lc_total_18;

$lcearning = $bon_total_12_total + $bon_total_13to17_total + $bon_total_18_total ;      


// 전일 접수 금액 산출

$sql="select * from mirae8440.ceiling WHERE orderday = CURDATE() - INTERVAL 1 DAY " ;

$lc_total_12 = 0;
$bon_total_12 = 0;
$lc_total_13to17 = 0;
$bon_total_13to17 = 0;
$lc_total_18 = 0;
$bon_total_18 = 0;

try {
	
	$allstmh = $pdo->query($sql);
	
  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {		
    $inseung = $row["inseung"];			  		
    $bon_su = $row["bon_su"];			  
    $lc_su = $row["lc_su"];
    
    $inseung_num = intval($inseung);
    $lc_su_num = intval($lc_su);
    $bon_su_num = intval($bon_su);
    
    if ($inseung_num <= 12 && $lc_su_num >= 1) {
      $lc_total_12 += $lc_su_num ;
    }
    if ($inseung_num >= 13 && $inseung_num <= 17 && $lc_su_num >= 1) {
      $lc_total_13to17 += $lc_su_num;
    }
    if ($inseung_num >= 18 && $lc_su_num >= 1) {
      $lc_total_18 += $lc_su_num;
    }
    
    if ($inseung_num <= 12 && $bon_su_num >= 1) {
      $bon_total_12 += $bon_su_num;
    }
    if ($inseung_num >= 13 && $inseung_num <= 17 && $bon_su_num >= 1) {
      $bon_total_13to17 +=  $bon_su_num;
    }
    if ($inseung_num >= 18 && $bon_su_num >= 1) {
      $bon_total_18 +=  $bon_su_num;
    }
  }
} catch (PDOException $Exception) {
  print "오류: " . $Exception->getMessage();
}


$sum_12 = $lc_total_12 + $bon_total_12; 
$sum_13to17 = $lc_total_13to17 + $bon_total_13to17; 
$sum_18 = $lc_total_18 + $bon_total_18; 

// 합계표 만들기

// bon_total_12 12인승 이하
$bon_total_12_amount = str_replace(',', '', $bon_total_12) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
$lc_total_12_amount = str_replace(',', '', $lc_total_12) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
$bon_total_12_total = $bon_total_12_amount + $lc_total_12_amount ;
$bon_total_12_num = $bon_total_12 + $lc_total_12;

// 막판무
$bon_total_13to17_amount = str_replace(',', '', $bon_total_13to17) * intval(str_replace(',', '', $readIni["bon_unit_13to17"]));
$lc_total_13to17_amount = str_replace(',', '', $lc_total_13to17) * intval(str_replace(',', '', $readIni["lc_unit_13to17"]));
$bon_total_13to17_total = $bon_total_13to17_amount + $lc_total_13to17_amount ;
$bon_total_13to17_num = $bon_total_13to17 + $lc_total_13to17;
// 쪽쟘
$bon_total_18_amount = str_replace(',', '', $bon_total_18) * intval(str_replace(',', '', $readIni["bon_unit_12"]));
$lc_total_18_amount = str_replace(',', '', $lc_total_18) * intval(str_replace(',', '', $readIni["lc_unit_12"]));
$bon_total_18_total = $bon_total_18_amount + $lc_total_18_amount ;
$bon_total_18_num = $bon_total_18 + $lc_total_18;

$beforedayceiling = $bon_total_12_total + $bon_total_13to17_total + $bon_total_18_total ;      

?>
		