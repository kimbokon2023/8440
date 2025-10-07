 <?php

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/work/estimate.ini",false);	
	
$sql="select * from mirae8440.work WHERE workday = CURDATE() - INTERVAL 1 DAY " ;

$WJ_HL = 0;
$WJ = 0;
$NJ_HL = 0;
$NJ = 0;
$SJ_HL = 0;
$SJ = 0;

try {
  $allstmh = $pdo->query($sql);

  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {
    $material1 = $row["material1"];
    $material2 = $row["material2"];
    $material3 = $row["material3"];
    $material4 = $row["material4"];
    $material5 = $row["material5"];
    $material6 = $row["material6"];
    $widejamb = intval($row["widejamb"]);
    $normaljamb = intval($row["normaljamb"]);
    $smalljamb = intval($row["smalljamb"]);

    $combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;

    // $WJ_HL 계산
    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $WJ_HL += $widejamb;
    }

    // $WJ 계산
    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $WJ += $widejamb;
    }

    // $NJ_HL 계산
    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $NJ_HL += $normaljamb;
    }

    // $NJ 계산
    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $NJ += $normaljamb;
    }

    // $SJ_HL 계산
    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $SJ_HL += $smalljamb;
    }

    // $SJ 계산
    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $SJ += $smalljamb;
    }
  }
} catch (PDOException $Exception) {
  print "오류: " . $Exception->getMessage();
}

// 합계표 만들기
$WJ_amount = str_replace(',', '', $WJ) * intval(str_replace(',', '', $readIni["WJ"]));
$WJ_HL_amount = str_replace(',', '', $WJ_HL) * intval(str_replace(',', '', $readIni["WJ_HL"]));
$WJ_total = $WJ_amount + $WJ_HL_amount ;
$WJ_num = $WJ + $WJ_HL;

// 막판무
$NJ_amount = str_replace(',', '', $NJ) * intval(str_replace(',', '', $readIni["NJ"]));
$NJ_HL_amount = str_replace(',', '', $NJ_HL) * intval(str_replace(',', '', $readIni["NJ_HL"]));
$NJ_total = $NJ_amount + $NJ_HL_amount ;
$NJ_num = $NJ + $NJ_HL;

// 쪽쟘
$SJ_amount = str_replace(',', '', $SJ) * intval(str_replace(',', '', $readIni["SJ"]));
$SJ_HL_amount = str_replace(',', '', $SJ_HL) * intval(str_replace(',', '', $readIni["SJ_HL"]));
$SJ_total = $SJ_amount + $SJ_HL_amount ;
$SJ_num = $SJ + $SJ_HL;

$jambearning = $WJ_total + $NJ_total + $SJ_total ; 	  


// 전일 접수된 금액계산


$sql="select * from mirae8440.work WHERE orderday = CURDATE() - INTERVAL 1 DAY " ;

$WJ_HL = 0;
$WJ = 0;
$NJ_HL = 0;
$NJ = 0;
$SJ_HL = 0;
$SJ = 0;

try {
  $allstmh = $pdo->query($sql);

  while ($row = $allstmh->fetch(PDO::FETCH_ASSOC)) {
    $material1 = $row["material1"];
    $material2 = $row["material2"];
    $material3 = $row["material3"];
    $material4 = $row["material4"];
    $material5 = $row["material5"];
    $material6 = $row["material6"];
    $widejamb = intval($row["widejamb"]);
    $normaljamb = intval($row["normaljamb"]);
    $smalljamb = intval($row["smalljamb"]);

    $combinedMaterials = $material1 . $material2 . $material3 . $material4 . $material5 . $material6;

    // $WJ_HL 계산
    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $WJ_HL += $widejamb;
    }

    // $WJ 계산
    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $WJ += $widejamb;
    }

    // $NJ_HL 계산
    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $NJ_HL += $normaljamb;
    }

    // $NJ 계산
    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $NJ += $normaljamb;
    }

    // $SJ_HL 계산
    if (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false) {
      $SJ_HL += $smalljamb;
    }

    // $SJ 계산
    if (stripos($combinedMaterials, 'HL') === false && stripos($combinedMaterials, 'H/L') === false) {
      $SJ += $smalljamb;
    }
  }
} catch (PDOException $Exception) {
  print "오류: " . $Exception->getMessage();
}

// 합계표 만들기
$WJ_amount = str_replace(',', '', $WJ) * intval(str_replace(',', '', $readIni["WJ"]));
$WJ_HL_amount = str_replace(',', '', $WJ_HL) * intval(str_replace(',', '', $readIni["WJ_HL"]));
$WJ_total = $WJ_amount + $WJ_HL_amount ;
$WJ_num = $WJ + $WJ_HL;

// 막판무
$NJ_amount = str_replace(',', '', $NJ) * intval(str_replace(',', '', $readIni["NJ"]));
$NJ_HL_amount = str_replace(',', '', $NJ_HL) * intval(str_replace(',', '', $readIni["NJ_HL"]));
$NJ_total = $NJ_amount + $NJ_HL_amount ;
$NJ_num = $NJ + $NJ_HL;

// 쪽쟘
$SJ_amount = str_replace(',', '', $SJ) * intval(str_replace(',', '', $readIni["SJ"]));
$SJ_HL_amount = str_replace(',', '', $SJ_HL) * intval(str_replace(',', '', $readIni["SJ_HL"]));
$SJ_total = $SJ_amount + $SJ_HL_amount ;
$SJ_num = $SJ + $SJ_HL;

$beforedayjamb = $WJ_total + $NJ_total + $SJ_total ; 	  


?>
		