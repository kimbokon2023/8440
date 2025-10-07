<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  
$title_message = '(스크린) 견적 산출내역서 '; 
$title_message_sub = '견 적 서 (스크린)' ; 
$tablename = 'estimate'; 
$item ='(스크린) 견적 산출내역서 ';   
$emailTitle ='견적서';   
$subTitle = '자동방화셔터 스크린인정제품';
?>
<?php include getDocumentRoot() . '/' . $tablename . '/common/estimate_head.php'; ?> <!-- head 정보 불러오기  -->
	
<?php
// 전체 SET 내역 표시 일련번호, 종류, 부호, 제품명, 오픈사이즈 등 전체금액 나오는 부분
if(True) {
	$data = [];
	$counter = 0;    
	$index = 0;    
	$sums = [];  // 각 행의 합을 저장할 배열
			
// 전체 반복 찾기
foreach ($decodedEstimateList as $item) {
    if (isset($item['col5']) && !empty($item['col5'])) {        
        // 각 행별 합계 재계산
		// 검사비 10만원 처음 들어감
        $sums[$counter] = 100000 ; 
		
		$su = floatval($item['col14']);        
		$itemTitle = floatval($item['col5']);        
 
		if($itemTitle == '실리카')
			 $itemTitle  = '스크린';
		 else
			 $itemTitle  = '와이어';
		// 행의 나머지 데이터를 설정
        $row = [];
        $row['col1'] = $counter + 1;
        $row['col2'] = $itemTitle ;
        $row['col3'] = $item['col3'] ?? '';   // col2는 item의 col3 값을 사용
        $row['col4'] = $item['col4'] ?? '';   // col3은 item의 col4 값을 사용
        $row['col5'] = $item['col8'];         // 필요한 값으로 설정
        $row['col6'] = $item['col9'];
        $row['col7'] = $item['col14'];        // 수량
        $row['col8'] = 'SET';                 // 빈 값으로 재계산 (필요에 따라 채움)        

        // $data 배열에 행을 추가합니다.
        $data[] = $row;
		$counter++;
    }        
}
	
    echo '<div class="d-flex align-items-center justify-content-center ">';
    echo '<table id="tableDetail" class="table" style="border-collapse: collapse;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th rowspan="2" class="text-center">일련번호</th>';
    echo '<th rowspan="2" class="text-center">종류</th>';
    echo '<th rowspan="2" class="text-center">부호</th>';
    echo '<th rowspan="2" class="text-center">제품명</th>';
    echo '<th colspan="2" class="text-center">오픈사이즈</th>';
    echo '<th rowspan="2" class="text-center">수량</th>';
    echo '<th rowspan="2" class="text-center">단위</th>';
    echo '<th rowspan="2" class="text-center">단가</th>';
    echo '<th class="text-center">합계</th>';
    echo '</tr>';
    echo '<tr>';    
    echo '<th class="text-center">가로</th>';
    echo '<th class="text-center">세로</th>';
    echo '<th class="text-center">금액</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    $col7_sum = 0; // col7 수량 합계    
    $col89_sum = 0; // col8 + col9 합계
    $col10_sum = 0; 
	$row_count = 0;
	
foreach ($data as $row) {		    
    echo '<tr class="calculation-row  calculation-firstrow" data-row="' . $row_count . '" data-serial="' . $row_count . '">';
    for ($i = 1; $i <= 10; $i++) {
        switch ($i) {
            case 1:
                echo '<td class="text-center ">' . $row['col1'] . '</td>';
                break;
            case 4:
                echo '<td class="text-center yellowBold ">' . htmlspecialchars($row['col' . $i]) . '</td>';
                break;
            case 6:
                echo '<td class="text-center text-primary fw-bold ">' . htmlspecialchars($row['col' . $i]) . '</td>';
                break;    
            case 7:
                echo '<td class="text-center text-primary fw-bold total-su-input" data-serial="' . $row_count . '">' . htmlspecialchars($row['col' . $i]) . '</td>';
                break;                
            case 9:        
                echo '<td class="text-end text-dark total-unit-price" data-serial="' . $row_count . '" ></td>'; // 단가가 들어가는 셀
                break;
            case 10:                    
                echo '<td class="text-end text-primary fw-bold subtotal-cell" data-serial="' . $row_count . '" ></td>'; // 합계 금액이 들어가는 셀
                break;
            default:
                echo '<td class="text-center ">' . htmlspecialchars($row['col' . $i]) . '</td>';
                break;
        }
    }

    echo '</tr>';
    $row_count++;
	$col7_sum += intval($row['col7']);
}

    // 소계 행 추가
    echo '<tr>';
    echo '<td class="text-center" colspan="6">소계</td>';
    echo '<td class="text-center  text-primary fw-bold">' . $col7_sum. '</td>';
    echo '<td class="text-center" colspan="2"></td>';
    echo '<td id="subtotal" class="text-end text-primary fw-bold grand-total"></td>';
    echo '</tr>';

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// 세부 산출내역서
	echo '<br>';
	echo '<div class="Estimateview">';
	echo '<div class="d-flex align-items-center justify-content-center mt-2 mb-2">';
	echo '<h3> 세부 산출내역서 </h3></div>';
	echo '<div id="shutterboxMsg" class="d-flex align-items-center justify-content-center mt-2 mb-2" style="display:none;">';
	echo '</div>';
	echo '<div class="d-flex align-items-center justify-content-center ">';
	echo '<table id="detailTable" class="table p-3" style="border-collapse: collapse;">';
	echo '<thead>';
	echo '<tr>';
	echo '<th class="text-center lightgray w50px">일련번호</th>';
	echo '<th class="text-center lightgray w300px">항목</th>';
	echo '<th class="text-center lightgray w50px">수량</th>';
	echo '<th class="text-center lightgray w50px">단위</th>';
	echo '<th class="text-center lightgray w250px">산출식</th>';
	echo '<th class="text-center lightgray w80px">면적(㎡) <br> 길이(㎜)</th>';
	echo '<th class="text-center lightgray w100px">면적(㎡) <br> 길이(㎜) 단가</th>';
	echo '<th class="text-center lightgray w100px">단가</th>';
	echo '<th class="text-center lightgray w120px">합계</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

$row_count = 0;
$total_sum = 0; // 전체 합계를 계산할 변수

$counter = 0;    
$index = 0;    

$subtotal_array = [] ;	// 소계 누계	
$subtotal_surang_array = [] ;	// 수량 누계	
$row_array = [] ;	// 각 행별 행카운트
		
// 전체 반복 찾기
foreach ($decodedEstimateList as $column) {

$subtotal = 0; // 각 일련번호별 소계	
$rowCount = 0; // 행의 수
	
if (isset($column['col5']) && !empty($column['col5'])) {   
			
$su = floatval($column['col14']);  // 수량		

// 데이터베이스 테이블 이름 설정 (셔터박스)
$tablename = 'bendingfee';
try {    
    $sql = "SELECT * FROM " . $DB . "." . $tablename;
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        print "검색결과가 없습니다.<br>";
    } else {
        // 셔터박스 단가 정보를 저장할 배열
        $shutterBoxprices = [];
									
		// 정규표현식 패턴 (숫자 형식만 매칭)
		$pattern = '/\d{3}\*\d{3}/';
		
		// echo '<pre>';
        // print_r($row);
        // echo '</pre>';
		
		// 전체 데이터를 반복 처리
		while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			$firstitem = isset($row['firstitem']) ? $row['firstitem'] : '';
			$seconditem = isset($row['seconditem']) ? $row['seconditem'] : '';
			$proditem = isset($row['proditem']) ? $row['proditem'] : ''; // 셔터박스 데이터 (단순 문자열)

			// 첫 번째 아이템이 '케이스'인 경우
			if ($seconditem == '케이스') {
				// $proditem에서 개행 문자를 제거하고 정리
				$proditem_clean = preg_replace("/\r|\n/", '', $proditem);
				
				// 정규식 패턴을 이용해 숫자 추출 (예: '500*350')
				if (preg_match($pattern, $proditem_clean, $matches)) {
					$extract = $matches[0]; // '500*350' 형식의 패턴 추출
                    // echo '$extract' . $extract;

					// $row['unitprice'] 값이 존재하고, 콤마 제거 후 유효한지 확인
					if (isset($row['unitprice']) && $row['unitprice'] !== '') {
						$unitprice_clean = preg_replace('/[^0-9.]/', '', $row['unitprice']); // 단가에서 숫자와 소수점만 남기기

						// floatval()로 변환된 값이 0보다 큰지 확인
						if (floatval($unitprice_clean) > 0) {
							// 변환된 값으로 배열에 저장
							$shutterBoxprices[$extract] = floatval($unitprice_clean);
						}
					}
				}
			}
		}
		
		// // 결과 출력 (확인용)
        // echo '<pre>';
        // print_r($shutterBoxprices);
        // echo '</pre>';
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 각 행별 합계 재계산
// 검사비 10만원 처음 들어감
$inspectionFee = 100000 ; 

// 주자재 계산
$tablename = 'price_raw_materials';
$query = "SELECT itemList FROM {$DB}.$tablename WHERE is_deleted IS NULL OR is_deleted = 0 ";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);  // 모든 행을 가져옴

$price_raw_materials = 0;
$price_jointbar = 0;

// 모든 행을 순회하면서 원하는 데이터를 찾음
foreach ($rows as $row) {
    $itemList = json_decode($row['itemList'], true);
    $price_raw_materials = slatPrice($itemList, '스크린', '실리카');
	if(!empty($price_raw_materials) ) 
		break;
}
// col12도 숫자형으로 변환
$area = floatval($column['col12']);
// 견적서 엑셀을 보면 스크린일때 높이를 900 더해준다. 기준 산출기준서에는 +350인데, 견적서는 +900적용 주의
// 그러므로, 여기서는 다시 계산해야 한다. ( +550 더해준다.)
// 견적서 및 발주서의 금액은 세로 + 900 적용함
$calculateHeight = floatval($column['col9']) + 900 ;
$area = floatval($column['col10']) * $calculateHeight / 1000000  ;        

// 모터가격 계산
$tablename = 'price_motor';             

$query = "SELECT itemList FROM {$DB}.$tablename where ( is_deleted IS NULL or is_deleted = 0 ) ORDER BY NUM LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);        

$itemList = json_decode($row['itemList'], true);

$motorSpec =calculateMotorSpec($column , $column['col13'] ,$column['col58']);
// print_r($motorSpec);     
$price = getPriceForMotor($motorSpec, $itemList);  
$motorUnit_price = $price;      

// 매립 연동제어기 가격 계산        
$price1 = calculateControllerSpec($column['col15'], $itemList, '매립형');                
$sums[$counter] += $price; // 매립 연동제어기 가격 합산

// 노출 연동제어기 가격 계산        
$price2 = calculateControllerSpec($column['col16'], $itemList, '노출형');                
$sums[$counter] += $price; // 노출 연동제어기 가격 합산

$price_controller = $price1 + $price2;

// 뒷박스 계산
$price = calculateControllerSpec($column['col17'], $itemList, '뒷박스');                
// echo "뒷박스 가격: " . $price . "\n";                        

$price_controller += $price;

// 모터 받침용 앵글 가계
$tablename = 'price_angle';             

$query = "SELECT itemList FROM {$DB}.$tablename where is_deleted IS NULL or is_deleted = 0 ORDER BY NUM LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);        

$itemList = json_decode($row['itemList'], true);

// 스크린용 앵글 가격가져오기
$price_angle = calculateAngle($column['col14'], $itemList, '스크린용');    // 수량, 리스트, 스크린용
// print_r($itemList );
// print_r($price_angle );
// 4인치 3T (스크린용) 철재는 4T
$mainangle_price = calculateMainAngle($column['col14'], $itemList, '앵글3T' , '2.5' );
$mainangle_surang = intval(str_replace(',', '', $column['col71'])); // 앵글 수량

// BOM에서 제품코드별로 가져와야 합니다.
$tablename = 'bendingfee';             

$query = "SELECT * FROM {$DB}.$tablename WHERE is_deleted IS NULL OR is_deleted = 0 ORDER BY NUM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// 가이드레일 단가 
$guidrail_price  = 0 ;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $firstitem = $row['firstitem'];
    $seconditem = $row['seconditem'];
    $prodcode = $row['prodcode'];
    $proditem = $row['proditem'];
    $material = $row['material'];
    $itemcode = $row['itemcode'];    
    $unit = $row['unit'];    
    $unitprice = $row['unitprice'];

    // '마감'이라는 단어 제거 후 비교
    $col7_clean = str_replace('마감', '', $column['col7']);

    // 조건에 맞는 경우 guidrail_price 설정 코드, 가이드레일, 형태(벽면,측면,혼합), 마감재질
    if ($prodcode == $column['col4'] && $seconditem == '가이드레일' && $proditem == $column['col6'] && $col7_clean == $material ) {  // col6은 벽면형 측면형
        $guidrail_price = floatval(str_replace(',', '', $unitprice)); 
    }
}
	if($guidrail_price < 1 )
	{
		$shutterboxMsg .= '가이드레일 단가가 정의되지 않았습니다. 단가를 생성해주세요. [절곡BOM] 메뉴 이용! ';		
		$shutterboxMsg .=  '$prodcode : ' . $prodcode . ' ';
		$shutterboxMsg .=  '$seconditem : ' . $seconditem . ' ';
		$shutterboxMsg .=  '$material : ' . $material . ' ';
		$shutterboxMsg .=  '$proditem : ' . $proditem . ' ';
		$shutterboxMsg .=  '$col7_clean :' . $col7_clean . ' ';		
	}
	
// echo '<pre>';
// print_r($guidrail_price);
// echo '</pre>';

// 셔터박스 크기 		
if ($column['col36'] === 'custom') {
	$dimension = $column['col36_custom'];   // 사용자 제작 사이즈
} else {
	$dimension = $column['col36'];          // 케이스 500*380
}

// echo '<pre>';
// print_r($dimension);
// echo '</pre>';

// 단가 계산
if (array_key_exists($dimension, $shutterBoxprices)) {
	$price =  ($shutterBoxprices[$dimension] / 1000) ;			
} else {
	$price = 0; // 조건이 맞지 않는 경우 0으로 설정
}
	
// 데이터베이스 테이블 이름 설정 (하단마감재 3가지 합계 하장바+L바+평철)
try {    
	$sqltmp = "SELECT * FROM {$DB}.bendingfee ";
	$stmhtmp = $pdo->prepare($sqltmp);
	$stmhtmp->execute();
	$count = $stmhtmp->rowCount();
	
	if ($count > 0) {						
		$bottomBarPrices = 0;		
		// 전체 데이터를 반복 처리
		$itemsep = substr($column['col4'], 0, 2) === 'KS' ? '스크린' : '철재';   // E8은 'KS'로 시작하는지 확인
		$ETprocode = $column['col4'];
		$itemfinal = str_replace('마감', '', $column['col7']);			
		$bottomBarPrices = 0;  
		$LBarPrices = 0;  
		$bottomPlatePrices = 0;  

		while ($rowtmp = $stmhtmp->fetch(PDO::FETCH_ASSOC)) {
			$firstitem = $rowtmp['firstitem'] ?? '';
			$seconditem = $rowtmp['seconditem'] ?? '';
			$proditem = $rowtmp['proditem'] ?? '';
			$prodcode = $rowtmp['prodcode'] ?? '';
			$material = $rowtmp['material'] ?? ''; 
			$memo = $rowtmp['memo'] ?? ''; 
			
			// '마감'이라는 단어 제거 후 비교
			$col7_clean = str_replace('마감', '', $column['col7']);	
			
			if ($prodcode == $column['col4'] && $proditem == '하장바' && $col7_clean == $material) {
				// $proditem에서 개행 문자를 제거하고 정리
				$proditem_clean = preg_replace("/\r|\n/", '', $proditem);
				if (isset($rowtmp['unitprice']) && $rowtmp['unitprice'] !== '') {
					$unitprice_clean = preg_replace('/[^0-9.]/', '', $rowtmp['unitprice']);
					if (floatval($unitprice_clean) > 0) {
						$bottomBarPrices = floatval($unitprice_clean);            
					}
				}
			}

			if ($proditem == 'L바' && strpos($memo, $column['col4']) !== true) {
				$proditem_clean = preg_replace("/\r|\n/", '', $proditem);
				if (isset($rowtmp['unitprice']) && $rowtmp['unitprice'] !== '') {
					$unitprice_clean = preg_replace('/[^0-9.]/', '', $rowtmp['unitprice']);
					if (floatval($unitprice_clean) > 0) {
						$LBarPrices = floatval($unitprice_clean);            
					}
				}
			}

			if ($proditem == '보강평철' && strpos($memo, $column['col4']) !== true) {
				$proditem_clean = preg_replace("/\r|\n/", '', $proditem);
				if (isset($rowtmp['unitprice']) && $rowtmp['unitprice'] !== '') {
					$unitprice_clean = preg_replace('/[^0-9.]/', '', $rowtmp['unitprice']);
					if (floatval($unitprice_clean) > 0) {
						$bottomPlatePrices = floatval($unitprice_clean);            
					}
				}
			}

			if ($proditem == '가이드레일용 연기차단재') {
				if (isset($rowtmp['unitprice']) && $rowtmp['unitprice'] !== '') {
					$unitprice_clean = preg_replace('/[^0-9.]/', '', $rowtmp['unitprice']);
					if (floatval($unitprice_clean) > 0) {
						$guiderailSmokeBanPrices = floatval($unitprice_clean);           
					}
				}
			}

			if ($proditem == '셔터박스용 연기차단재') {
				if (isset($rowtmp['unitprice']) && $rowtmp['unitprice'] !== '') {
					$unitprice_clean = preg_replace('/[^0-9.]/', '', $rowtmp['unitprice']);
					if (floatval($unitprice_clean) > 0) {
						$boxSmokeBanPrices = floatval($unitprice_clean);           
					}
				}
			}

			if ($proditem == '마구리 ' . $column['col45']) { // 품목명 + 마구리외형 크기 (예시 : 마구리 785*655)
				if (isset($rowtmp['unitprice']) && $rowtmp['unitprice'] !== '') {
					$unitprice_clean = preg_replace('/[^0-9.]/', '', $rowtmp['unitprice']);
					if (floatval($unitprice_clean) > 0) {
						$maguriPrices = floatval($unitprice_clean);           
					}
				}
			}
		}
	}
} catch (PDOException $Exception) {
	print "오류: " . $Exception->getMessage();
}
		
// $data 배열에 행을 추가합니다.
$data[] = $row;
$rowCount = 1;

// 일련번호, 검사비
$subtotal += $inspectionFee * $su;	

if($inspectionFee > 0 )
{
    // calculation-row 클래스와 일련번호(serial)를 가진 tr 태그 추가
    echo '<tr class="calculation-row" data-serial="' . $counter . '">'; 
    echo '<td class="text-center" id="dynamicRowspan-' . $counter . '">' . $column['col1'] . '</td>';
    echo '<td class="text-center"> 검사비 </td>';

    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';

    // 단위 및 기타 필드
    echo '<td class="text-center"> SET </td>';

    // 산출식 입력 필드 (예: 수량 * 단가 등으로 계산하는 필드)
    echo '<td class="text-center"><input type="text" class="text-left noborder-input calc-formula-input" value="" data-row="' . $counter . '" data-type="formula" /></td>';

    // 면적(㎡) 길이(㎜) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';

    // 면적(㎡) 길이(㎜) 단가 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-price-input number-input" value="" data-row="' . $counter . '" data-type="area_price" oninput="inputNumber(this)" /></td>';

    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($inspectionFee) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';

    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($inspectionFee * $su) . '</td>';

    echo '</tr>';
    $rowCount++;
}

// 주자재(스크린) 가격
$screen_price = floatval($price_raw_materials * round($area,2)) ;
$subtotal += $screen_price * $su;
if($screen_price > 0 )
{
    // calculation-row 클래스와 일련번호(serial)를 가진 tr 태그 추가
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';  
    echo '<td class="text-center">주자재(스크린)</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';

    // 단위 및 기타 필드
    echo '<td class="text-center"> SET </td>';

    // 산출식 입력 필드 (예: 수량 * 단가 등으로 계산하는 필드)
    echo '<td class="text-center"><input type="text" class="text-left noborder-input calc-formula-input" value="" data-row="' . $counter . '" data-type="formula" /></td>';

    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($area, 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';

    // 원자재 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input  number-input" value="' . number_format($price_raw_materials) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';

    // 스크린 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($screen_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';

    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($screen_price * $su,2) . '</td>';

    echo '</tr>';
    $rowCount++;
}

// 모터    
$subtotal += $motorUnit_price * $su;
if ($motorUnit_price > 0) {
	echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center">모터</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input이 아님)
    echo '<td class="text-center">SET</td>';
    
    // 산출식 입력 필드 (예: 수량 * 단가 등으로 계산하는 필드)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input"  value="' . $motorSpec . 'KG"  data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드 (없으면 빈 입력)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (없으면 빈 입력)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 모터 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($motorUnit_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($motorUnit_price * $su) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 연동제어기
$controller_price = $price_controller;
$subtotal += $controller_price * $su;

if ($controller_price > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center">매립/노출 연동제어기(뒷박스 있는 경우 포함)</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input이 아님)
    echo '<td class="text-center">SET</td>';
    
    // 산출식 입력 필드 (예: 수량 * 단가 등으로 계산하는 필드)
    echo '<td class="text-center"><input type="text" class="noborder-input text-left calc-formula-input" value="" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드 (없으면 빈 입력)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (없으면 빈 입력)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 연동제어기 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($controller_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($controller_price * $su) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 셔터박스
if ($column['col36'] === 'custom') {
    $shutterBox = $column['col36_custom'];   // 사용자 제작 사이즈
} else {
    $shutterBox = $column['col36'];          // 케이스 500*380
}
$shutter_price = round($shutterBoxprices[$shutterBox],2);
$basicbox_price = round($shutterBoxprices['500*380'],2);  // 기본 500*380 가격;
list($boxwidth, $boxheight) = explode('*', $shutterBox);

// echo '<br>';
// echo '<pre>';
// print_r($shutterBoxprices);
// echo '</pre>';

if ($shutter_price < 1) {
	$basicbox_pricePermeter = $basicbox_price/(500*380/1000); 
	$shutter_price = $basicbox_pricePermeter  * floatval($boxwidth) * floatval($boxheight)  / 1000;
	if($basicbox_price>$shutter_price )
		$shutter_price = $basicbox_price ; 
    $shutterboxMsg = $shutterBox . ' 셔터박스 크기 정의 없음,  m당 기본사이즈 500*380 가격 (크기가 작아도 최소가격은 표준가격임) : ' . number_format($basicbox_price) . '(원)' ;
    $shutterboxMsg .= '  m당 단가 : ' . number_format($basicbox_pricePermeter) . '(원)' ;
	$shutterboxMsg .= '  m단위로 설정된 단가 : ' . number_format($shutter_price) . '(원)' ;
	
	// echo $shutterBox . ' 셔터박스 크기에 대한 단가가 정의되지 않았습니다. 단가를 생성해주세요. [절곡BOM] 메뉴 이용! <br> '. ' 1m당 기본사이즈 500*380 가격 : ' . number_format($basicbox_price) . '(원)' ;
    // echo '<br> m당 단가 : ' . number_format($basicbox_pricePermeter) . '(원)' ;
	// echo '<br> m단위로 설정된 단가 : ' . number_format($shutter_price) . '(원)' ;
}

$total_length = round(floatval($column['col37']) / 1000,2);

// print_r($total_length);
// echo '<br>';
// print_r($shutter_price * $total_length);
$subtotal += $shutter_price * $total_length ;

if($total_length > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center">셔터박스</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input이 아님)
    echo '<td class="text-center">SET</td>';
    
    // 산출식 입력 필드 (예: 수량 * 단가 등으로 계산하는 필드)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input"  value="' . $dimension . '"   data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length, 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="' . number_format($shutter_price) . '" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 셔터박스 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($shutter_price * $total_length) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($shutter_price * $total_length) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 셔터박스 연기차단재  1식으로 표현함 수량과 관계없이 연산해야 함.
$boxSmokeBanPrices = ceil($boxSmokeBanPrices);
$total_ea = intval($column['col47']);	
$total_length = round(floatval($column['col37']) / 1000, 2);	
$subtotal += $boxSmokeBanPrices * $total_length  ;

if($total_length > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center">셔터박스용 연기차단재</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input"  value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> 식 </td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="W80" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length , 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="' . number_format($boxSmokeBanPrices) . '" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($boxSmokeBanPrices ) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($boxSmokeBanPrices * $total_length ) . '</td>';
    
    echo '</tr>';	
    $rowCount++;
}

//마구리 	
$subtotal += $maguriPrices * $su;		
if($maguriPrices > 0 )
{	
    echo '<tr class="calculation-row" data-serial="' . $counter . '">'; 
    echo '<td class="text-center">셔터박스 마구리</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center">SET</td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $column['col45'] . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($maguriPrices) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($maguriPrices * $su) . '</td>';
    
    echo '</tr>';	
    $rowCount++;	
}

// 모터 받침용 앵글
$price_angle = ceil($price_angle);
$angle_price = $price_angle;
$subtotal += $angle_price * $su * 4;

if($angle_price > 0 )
{	
	echo '<tr class="calculation-row" data-serial="' . $counter . '">';   
    echo '<td class="text-center">모터 받침용 앵글</td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su * 4) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (EA는 input에서 제외)
    echo '<td class="text-center">EA</td>';	
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $column['col22'] . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (빈 값)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($angle_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($angle_price * $su * 4) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 가이드레일					
// 혼합형이 아니면 1SET는 *2를 적용 혼합형은 예외임 단가는 이미 2개를 1세트로 단가표가 구성되어 있으니 유의할 것 절곡BOM참조
$total_length = round(floatval($column['col23']) / 1000,2);
$guidrail_price = ceil($guidrail_price);
$subtotal += $guidrail_price * $total_length;

// print_r($guidrail_price * $total_length);

if ($total_length  > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center"> 가이드레일 </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> SET </td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $column['col6'] . ' ' . $column['col7'] . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length, 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (빈 값)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($guidrail_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($guidrail_price * $total_length) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

//가이드레일용 연기차단재 레일 높이 + 250 적용해서 만드는 것임
$total_length = round(floatval($column['col23']) / 1000, 2);
$guiderailSmokeBanPrices = ceil($guiderailSmokeBanPrices);
$subtotal += $guiderailSmokeBanPrices * $total_length * 2 ;

if ($total_length  > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center"> 레일용 연기차단재 </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su * 2) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> SET </td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="W50" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length  , 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (빈 값)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input"  value="' . number_format($guiderailSmokeBanPrices) . '" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($guiderailSmokeBanPrices * $total_length) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($guiderailSmokeBanPrices * $total_length * 2) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 하단마감재(하장바)
// 혼합형이 아니면 1SET는 *2를 적용
$total_length = intval($column['col48']) * $su;
$total_length = round($total_length / 1000,2);
$bottomBarPrices = ceil($bottomBarPrices);
$subtotal += ceil($bottomBarPrices * $total_length);

if ($total_length  > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center"> 하장바 </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> SET </td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="'.  $column['col7'] . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length, 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (빈 값)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($bottomBarPrices) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format(ceil($bottomBarPrices * $total_length)) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 하단마감재(L바)
// 혼합형이 아니면 1SET는 *2를 적용
$total_length = intval($column['col51']) * $su; // 2개가 1SET , 하지만 단가가 2개가 1세트로 계산된 단가임 주의요함
$total_length = $total_length / 1000;
$subtotal += $LBarPrices * $total_length;

if ($total_length  > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center"> L바 </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> SET </td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="2개가 1SET 단가로 적용" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length, 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (빈 값)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($LBarPrices) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($LBarPrices * $total_length) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 하단마감재(보강평철)
// 조건에 따른 가격 계산
$total_length = intval($column['col54']) * $su;
$total_length = $total_length / 1000;
$subtotal += $bottomPlatePrices * $total_length * $su;

if ($total_length  > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center"> 보강평철 </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> SET </td>';
    
    // 산출식 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-end calc-formula-input" value="2000" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="' . number_format($total_length, 2) . '" data-row="' . $counter . '" data-type="area_length" oninput="inputNumber(this)" /></td>';
    
    // 원자재 단가 입력 필드 (빈 값)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" oninput="inputNumber(this)" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($bottomPlatePrices) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($bottomPlatePrices * $total_length * $su) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 감기샤프트
$tablename = 'price_shaft';             
$query = "SELECT itemList FROM {$DB}.$tablename WHERE is_deleted IS NULL OR is_deleted = 0 ORDER BY NUM LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);        
$itemList = json_decode($row['itemList'], true);
$sum_shaft_price = 0;
$shaft_counts = []; // 각 사이즈별 카운트를 저장할 배열

// 각각의 샤프트 계산
addShaftPrice($column['col59'], $itemList, '3', '300', $sum_shaft_price, $shaft_counts);
addShaftPrice($column['col60'], $itemList, '4', '3000', $sum_shaft_price, $shaft_counts);
addShaftPrice($column['col61'], $itemList, '4', '4500', $sum_shaft_price, $shaft_counts);
addShaftPrice($column['col62'], $itemList, '4', '6000', $sum_shaft_price, $shaft_counts);
addShaftPrice($column['col63'], $itemList, '5', '6000', $sum_shaft_price, $shaft_counts);
addShaftPrice($column['col64'], $itemList, '5', '7000', $sum_shaft_price, $shaft_counts);
addShaftPrice($column['col65'], $itemList, '5', '8200', $sum_shaft_price, $shaft_counts);

// 샤프트 수량을 텍스트로 변환
$shaft_count_text = [];
foreach ($shaft_counts as $length => $count) {
    $countstr = $count * $su;
    $shaft_count_text[] = "{$length}x{$countstr}EA";
}
$shaft_count_text = implode(', ', $shaft_count_text);

$subtotal += $sum_shaft_price;

if ($sum_shaft_price > 0 ) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    echo '<td class="text-center"> 감기샤프트 </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($su) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> 식 </td>';
    
    // 산출식 입력 필드 (샤프트 수량 텍스트)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $shaft_count_text . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" /></td>';
    
    // 원자재 단가 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($sum_shaft_price/$su) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($sum_shaft_price) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 무게평철12T (2000)    
// 무게평철 기준 단가 (2000mm당 12000원)
$basePrice = 12000;
$size = intval(str_replace(',', '', $column['col8'])) * 2; // 스크린 길이 * 2
$itemsep = (substr($column['col4'], 0, 2) === 'KS' || substr($column['col4'], 0, 2) === 'KW') ? '스크린' : '철재';

// 단계별 가격 계산 및 수량 저장을 위한 배열
$weight_plate_counts = [];
$priceFactor = 0;

// 스크린 하단 무게평철의 단계별 가격 계산 및 수량 저장
if ($itemsep == '스크린') {
    if ($size <= 2000) {
        $priceFactor = 1;
    } elseif ($size <= 4000) {
        $priceFactor = 2;
    } elseif ($size <= 6000) {
        $priceFactor = 3;
    } elseif ($size <= 8000) {
        $priceFactor = 4;
    } elseif ($size <= 10000) {
        $priceFactor = 5;
    } elseif ($size <= 12000) {
        $priceFactor = 6;
    } elseif ($size <= 14000) {
        $priceFactor = 7;
    } elseif ($size <= 16000) {
        $priceFactor = 8;
    } elseif ($size <= 18000) {
        $priceFactor = 9;
    } elseif ($size <= 20000) {
        $priceFactor = 10;
    }

    $priceFactor *= $su;
    // 단계별 개수 저장
    if ($priceFactor > 0) {
        $weight_plate_counts[2000] = $priceFactor ; // 2000, 2000 x 1, ...,형식으로 저장
    }
}

// 최종 단가 계산
$weight_plate_price = $basePrice * $priceFactor;

// 무게평철 수량을 텍스트로 변환
$weight_plate_count_text = [];
foreach ($weight_plate_counts as $length => $count) {
    $weight_plate_count_text[] = "{$length}x{$count}EA";
}
$weight_plate_count_text = implode(', ', $weight_plate_count_text);

$total_length = intval(str_replace(',', '', $column['col57']));

$subtotal += $weight_plate_price ;

if ($weight_plate_price > 0) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    
    // 항목 이름
    echo '<td class="text-center"> 무게평철12T (2000) </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($total_length) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> EA </td>';
    
    // 산출식 입력 필드 (무게평철 수량 텍스트)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $weight_plate_count_text . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" /></td>';
    
    // 원자재 단가 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($weight_plate_price / $total_length) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($weight_plate_price) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 환봉(3000) 기준 단가 (3000mm당 2000원)
$round_bar_price = 2000;
$round_bar_surang = intval(str_replace(',', '', $column['col70']));

// 3000mm 길이의 환봉 개수 저장
$round_bar_counts = [];
if ($round_bar_surang > 0) {
    $round_bar_counts[3000] = $round_bar_surang;
}

// 환봉 수량을 텍스트로 변환
$round_bar_count_text = [];
foreach ($round_bar_counts as $length => $count) {
    $round_bar_count_text[] = "{$length}x{$count}EA";
}
$round_bar_count_text = implode(', ', $round_bar_count_text);    


if ($round_bar_surang < 1) {
    $round_bar_price = 0;
}

$subtotal += $round_bar_price * $round_bar_surang;

if ($round_bar_surang > 0) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    
    // 항목 이름
    echo '<td class="text-center"> 환봉(3000) </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($round_bar_surang) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (SET은 input에서 제외)
    echo '<td class="text-center"> EA </td>';
    
    // 산출식 입력 필드 (환봉 수량 텍스트)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $round_bar_count_text . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" /></td>';
    
    // 원자재 단가 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($round_bar_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($round_bar_price * $round_bar_surang) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 각파이프 3000 계산
$tablename = 'price_pipe';
$query = "SELECT itemList FROM {$DB}.$tablename WHERE is_deleted IS NULL OR is_deleted = 0 ORDER BY NUM LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$itemList = json_decode($row['itemList'], true);

// 각 파이프 가격 계산
$pipe_price_3000 = calculatePipe( $itemList, '1.4', '3000');
$pipe_price_6000 = calculatePipe( $itemList, '1.4', '6000');

// 각 파이프 수량 계산
$pipe_price_3000_surang = intval(str_replace(',', '', $column['col68']));
$pipe_price_6000_surang = intval(str_replace(',', '', $column['col69']));

// 각 파이프 수량을 텍스트로 변환
$pipe_counts = [];
if ($pipe_price_3000_surang > 0) {
    $pipe_counts[] = "3000x{$pipe_price_3000_surang}EA";
}

$pipe_count_text = implode(', ', $pipe_counts);

if ($pipe_price_3000_surang < 1) {
    $pipe_price_3000 = 0;
}

if ($pipe_price_3000_surang > 0) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    
    // 항목 이름
    echo '<td class="text-center"> 각파이프(3000) </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($pipe_price_3000_surang) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (EA)
    echo '<td class="text-center"> EA </td>';
    
    // 산출식 입력 필드 (각파이프 수량 텍스트)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $pipe_count_text . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" /></td>';
    
    // 원자재 단가 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($pipe_price_3000) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($pipe_price_3000 * $pipe_price_3000_surang) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

// 각 파이프 수량을 텍스트로 변환
$pipe_counts = [];

if ($pipe_price_6000_surang > 0) {
    $pipe_counts[] = "6000x{$pipe_price_6000_surang}EA";
}

$pipe_count_text = implode(', ', $pipe_counts);

if ($pipe_price_6000_surang < 1) {
    $pipe_price_6000 = 0;
}

$subtotal += ($pipe_price_3000 * $pipe_price_3000_surang + $pipe_price_6000 * $pipe_price_6000_surang);

if ($pipe_price_6000_surang > 0) {
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    
    // 항목 이름
    echo '<td class="text-center"> 각파이프(6000) </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($pipe_price_6000_surang) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (EA)
    echo '<td class="text-center"> EA </td>';
    
    // 산출식 입력 필드 (각파이프 수량 텍스트)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $pipe_count_text . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" /></td>';
    
    // 원자재 단가 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($pipe_price_6000) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($pipe_price_6000 * $pipe_price_6000_surang) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

$tablename = 'price_angle';

$query = "SELECT itemList FROM {$DB}.$tablename WHERE is_deleted IS NULL OR is_deleted = 0 ORDER BY NUM LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$itemList = json_decode($row['itemList'], true);

// 4인치 3T (스크린용), 철재는 4T
$mainangle_price = calculateMainAngle(1, $itemList, '앵글3T', '2.5');
$mainangle_surang = intval(str_replace(',', '', $column['col71'])); // 수량

// 앵글 수량을 텍스트로 변환 
$angle_counts = [];
if ($mainangle_surang > 0) {
    $angle_counts[] = "2500x{$mainangle_surang}EA";
}
$angle_count_text = implode(', ', $angle_counts);

if ($mainangle_surang < 1) {
    $mainangle_price = 0;
}

// 앵글    
$subtotal += $mainangle_price * $mainangle_surang;

if ($mainangle_surang > 0) {
	
    echo '<tr class="calculation-row" data-serial="' . $counter . '">';
    
    // 항목 이름
    echo '<td class="text-center"> 앵글3T (2500) </td>';
    
    // 수량 입력 필드
    echo '<td class="text-center"><input type="text" class="noborder-input text-center su-input number-input" value="' . number_format($mainangle_surang) . '" data-row="' . $counter . '" data-type="su" oninput="inputNumber(this)" /></td>';
    
    // 단위 필드 (EA)
    echo '<td class="text-center"> EA </td>';
    
    // 산출식 입력 필드 (앵글 수량 텍스트)
    echo '<td class="text-end"><input type="text" class="noborder-input text-end calc-formula-input" value="' . $angle_count_text . '" data-row="' . $counter . '" data-type="formula" /></td>';
    
    // 면적(㎡) 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-center area-length-input number-input" value="" data-row="' . $counter . '" data-type="area_length" /></td>';
    
    // 원자재 단가 필드 (빈 값)
    echo '<td class="text-center"><input type="text" class="noborder-input text-end area-price-input number-input" value="" data-row="' . $counter . '" data-type="raw_materials_price" /></td>';
    
    // 단가 입력 필드
    echo '<td class="text-end"><input type="text" class="noborder-input text-end unit-price-input number-input" value="' . number_format($mainangle_price) . '" data-row="' . $counter . '" data-type="unit_price" oninput="inputNumber(this)" /></td>';
    
    // 합계 필드 (자동 계산, 입력 불가)
    echo '<td class="text-end total-price" id="total-price-' . $counter . '">' . number_format($mainangle_price * $mainangle_surang) . '</td>';
    
    echo '</tr>';
    $rowCount++;
}

echo '<tr data-serial="' . $counter . '" class="subtotal-row">';
echo '<td class="text-center" colspan="7"><strong>소계</strong></td>';
echo '<td class="text-end fw-bold subtotal-cell" data-serial="' . $counter . '">  </td>';
echo '</tr>';
array_push($subtotal_array, round($subtotal,2)) ;
array_push($subtotal_surang_array, $su) ;
array_push($row_array, $rowCount) ;
// 전체 합계에 소계를 더함
$total_sum += round($subtotal,2);
$rowCount++;
}
   $counter++ ; // 전체 행카운트
}

echo '</tbody>';
echo '<tfoot>';
echo '<tr class="grand-total-row">';
echo '<td class="text-center lightgray" colspan="8"><strong>전체 합계</strong></td>';
echo '<td class="text-end lightgray fw-bold grand-total"> </td>';
echo '</tr>';
echo '</tfoot>';
echo '</table>';
echo '</div>';
echo '</div>';

$row_array_json = json_encode($row_array);

$korean_total_sum = number_to_korean($total_sum);	

// PHP 배열을 JSON으로 변환
$subtotal_json = json_encode($subtotal_array);
$subtotal_surang_json = json_encode($subtotal_surang_array);

// 세부내역서 ####################################################################################
// 세부내역서 ####################################################################################
// 세부내역서 ####################################################################################
if (True) {
    $data = [];
    $counter = 0;
    foreach ($decodedEstimateList as $item) {
        if (isset($item['col5']) && !empty($item['col5'])) {
            $counter++;
            // 각 col 값을 배열에 추가합니다.
            $row = [];
            $row['col1'] = '2.1 <br> 본체(스크린)';
            $row['col2'] = $item['col3'] ?? '';    // 부호 
            $row['col3'] = $item['col5'] ?? '';    // 실리카
            $row['col4'] = $item['col7'] ?? '';    // SUS
            $row['col5'] = $item['col6'];          // 벽마감표시
            $row['col6'] = $item['col8'];          // 오픈 가로
            $row['col7'] = $item['col9'];          // 오픈 세로
            $row['col8'] = $item['col10'];         // 제작 가로
            $row['col9'] = $item['col11'];         // 제작 세로
            $row['col10'] = $item['col14'];         // 수량
			if ($item['col36'] === 'custom') {
				$row['col11'] = $item['col36_custom'];   // 사용자 제작 사이즈
			} else {
				$row['col11'] = $item['col36'];          // 케이스 500*380
			}


            // $data 배열에 행을 추가합니다.
            $data[] = $row;
        }
    }
	
    echo '<br>';
    echo '<div class="listview">';    
	echo '<div class=" d-flex align-items-center justify-content-center ">';
    echo '<h3> 소요자재 내역 </h3> </div>';
    echo '<div class=" d-flex align-items-center justify-content-center ">';
    echo '<table class="table" style="border-collapse: collapse;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th rowspan="2" class="text-center">구분</th>';
    echo '<th rowspan="2" class="text-center">부호</th>';
    echo '<th rowspan="2" class="text-center">종류</th>';
    echo '<th colspan="2" class="text-center">가이드레일</th>';
    echo '<th colspan="2" class="text-center">오픈사이즈</th>';
    echo '<th colspan="2" class="text-center yellowredBold">제작사이즈</th>';
    echo '<th rowspan="2" class="text-center ">수량</th>';
    echo '<th rowspan="2" class="text-center blueBold ">케이스</th>';
    echo '</tr>';
    echo '<tr>';    
    echo '<th class="text-center">마감유형</th>';
    echo '<th class="text-center">설치유형</th>';
    echo '<th class="text-center">가로</th>';
    echo '<th class="text-center">세로</th>';
    echo '<th class="text-center yellowredBold">가로</th>';
    echo '<th class="text-center yellowredBold">세로</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    $col7_sum = 0; // col7 합계
    $col10_sum = 0; // col10 합계
    $col89_sum = 0; // col8 + col9 합계

    $rowspan_applied = false; // rowspan이 적용되었는지 확인하는 플래그
    $row_count = count($data); // 총 행 수

    foreach ($data as $row) {
        echo '<tr>';

        // 첫 번째 열에만 rowspan 적용
        if (!$rowspan_applied) {
            echo '<td class="text-center  fw-bold" rowspan="' . $row_count . '">' . $row['col1'] . '</td>';
            $rowspan_applied = true;
        }

        // 나머지 열 출력 (색상을 부여하는 코드)
			for ($i = 2; $i <= 11; $i++) { // col1은 이미 출력했으므로 col2부터 시작
				switch ($i) {
					case 9:
					case 8:
						// col3에 yellowBold 클래스를 적용하여 출력
						echo '<td class="text-center text-primary yellowBold">' . htmlspecialchars($row['col' . $i]) . '</td>';
						break;
					case 11:
						// col5에 blueBold 클래스를 적용하여 출력 (예시로 추가)
						echo '<td class="text-center text-primary greenredBold">' . htmlspecialchars($row['col' . $i]) . '</td>';
						break;
					default:
						// 기본적으로 fw-bold 클래스를 적용하여 출력
						echo '<td class="text-center text-primary fw-bold">' . htmlspecialchars($row['col' . $i]) . '</td>';
						break;
				}
			}


        // 쉼표를 제거하고 숫자형으로 변환하여 합계 계산
        $col7_sum += floatval(str_replace(',', '', $row['col7']));
        $col10_sum += floatval(str_replace(',', '', $row['col10']));
        $col89_sum += floatval(str_replace(',', '', $row['col8'])) + floatval(str_replace(',', '', $row['col9']));
        
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// 모터 부분
if(True) {

	// 함수 호출을 통해 계산된 값을 가져옴
	$specifications = calculateMotorSpecifications($decodedEstimateList);  

	// 각 합계 값을 변수에 저장
	$col1_sum = $specifications['col1_sum'];
	$col2_sum = $specifications['col2_sum'];
	$col3_sum = $specifications['col3_sum'];
	$col4_sum = $specifications['col4_sum'];
	$col5_sum = $specifications['col5_sum'];
	$col6_sum = $specifications['col6_sum'];
	$col7_sum = $specifications['col7_sum'];
	$col8_sum = $specifications['col8_sum'];
	$col9_sum = $specifications['col9_sum'];
	$col10_sum = $specifications['col10_sum'];
	$col11_sum = $specifications['col11_sum'];
	$col12_sum = $specifications['col12_sum'];
	
	echo ' <div class="d-flex align-items-center justify-content-center " >';		
	echo '<table class="table" style="border-collapse: collapse;">';
	echo '<thead>';
	echo '<tr>';
	echo '<th rowspan="3" class="text-center"> 2.2 <br> 모터 </th>';
	echo '<th colspan="7" class="text-center"> 모터종류(KG)</th>';
	echo '<th colspan="3" class="text-center"> 연동제어기 </th>';
	echo '<th class="text-center">브라켓트</th>';
	echo '<th class="text-center">앵글</th>';
	echo '</tr>';
	echo '<tr>';    
	echo '<th class="text-center">150</th>';
	echo '<th class="text-center">300</th>';
	echo '<th class="text-center">400</th>';
	echo '<th class="text-center">500</th>';
	echo '<th class="text-center">600</th>';
	echo '<th class="text-center">800</th>';
	echo '<th class="text-center">1000</th>';
	echo '<th class="text-center">매립</th>';
	echo '<th class="text-center">노출</th>';
	echo '<th class="text-center">뒷박스</th>';
	echo '<th class="text-center">수량</th>';
	echo '<th class="text-center">수량</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th class="text-center text-primary fw-bold">' . ($col1_sum ?: '') . '</th>';
	echo '<th class="text-center text-primary fw-bold">' . ($col2_sum ?: '') . '</th>';
	echo '<th class="text-center text-primary fw-bold">' . ($col3_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col4_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col5_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col6_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col7_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col8_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col9_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col10_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col11_sum ?: '') . '</th>';    
	echo '<th class="text-center text-primary fw-bold">' . ($col12_sum ?: '') . '</th>';    
	echo '</tr>';
	echo '</thead>';
	echo '</table>';
	echo '</div>';
}

// 절곡 부분
if (True) {	
	$data = [];	
		// 누적 합계를 저장할 변수들
		$row3_1_sum = 0;
		$row3_2_sum = 0;
		$row3_3_sum = 0;
		$row3_4_sum = 0;
		$row3_5_sum = 0;
		// 누적 합계를 저장할 변수들
		$row5_1_sum = 0;
		$row5_2_sum = 0;
		$row5_3_sum = 0;
		$row5_4_sum = 0;
		$row5_5_sum = 0;
		$row5_6_sum = 0;
		$row5_7_sum = 0; // col39의 합
		$row5_8_sum = 0; // col41의 합
		$row7_1_sum = 0; // 하단마감재
		$row7_2_sum = 0; // 
		$row7_3_sum = 0; // 
		$row8_1_sum = 0; // 하단마감재
		$row8_2_sum = 0; // 
		$row8_3_sum = 0; // 
		$row10_1_sum = 0; // 
		$row10_2_sum = 0; // 

    foreach ($decodedEstimateList as $item) {
        $ValidLength = floatval($item['col23']); // 가이드레일의 제작 길이
		$surang = floatval($item['col14']); // 수량

        // 가이드레일 
        $row3_1_sum += floatval($item['col24']);
        $row3_2_sum += floatval($item['col25']);
        $row3_3_sum += floatval($item['col26']);
        $row3_4_sum += floatval($item['col27']);
        $row3_5_sum += floatval($item['col28']);
		
        // 케이스
        $row5_1_sum += floatval($item['col38']);
        $row5_2_sum += floatval($item['col39']);
        $row5_3_sum += floatval($item['col40']);
        $row5_4_sum += floatval($item['col41']);
        $row5_5_sum += floatval($item['col42']);
        $row5_6_sum += floatval($item['col43']);			

        // 5열 7행 상부덮개, 마구리
        $row5_7_sum += floatval($item['col44']);
        $row5_8_sum += floatval($item['col46']);
		
		// 레일용 연기차단재/ 케이스용 연기차단재
        $row10_1_sum += floatval($item['col31']);  // 레일용 연기차단제 5종류 나열
        $row10_2_sum += floatval($item['col32']); 
        $row10_3_sum += floatval($item['col33']); 
        $row10_4_sum += floatval($item['col34']); 
        $row10_5_sum += floatval($item['col35']); 
		
        $row10_6_sum += floatval($item['col47']);  // 케이스용 연기차단제

		
		$row7_1_sum += floatval($item['col44']);
		$row8_1_sum += floatval($item['col45']);
		$row7_2_sum += floatval($item['col47']);
		$row8_2_sum += floatval($item['col48']);
		$row7_3_sum += floatval($item['col50']);
		$row8_3_sum += floatval($item['col51']);		
    }
	
// 테이블 출력
echo '<div class="d-flex align-items-center justify-content-center">';
echo '<table class="table" style="border-collapse: collapse;">';
echo '<thead>';
echo '<tr>';
echo '<th rowspan="10" class="text-center"> 2.3 <br> 절곡 </th>';
echo '<th colspan="2" class="text-center"> (1) 가이드레일 <br> (EGI 1.6T/ SUS 1.2T) </th>';
echo '<th colspan="2" class="text-center"> (2) 케이스 <br> (EGI 1.6T) </th>';
echo '<th colspan="3" class="text-center"> (3) 하단마감재 <br> (SUS 1.2T) </th>';
echo '<th colspan="3" class="text-center"> (4) 연기차단재 </th>';
echo '</tr>';
echo '<tr>';    // 2행
echo '<th class="text-center">사이즈</th>';
echo '<th class="text-center">수량</th>';
echo '<th class="text-center">사이즈</th>';
echo '<th class="text-center">수량</th>';
echo '<th class="text-center">분류</th>';
echo '<th class="text-center">3000</th>';
echo '<th class="text-center">4000</th>';
echo '<th class="text-center">분류</th>';
echo '<th class="text-center">사이즈</th>';
echo '<th class="text-center">수량</th>';
echo '</tr>';
echo '<tr>';    // 3행
    echo '<th class="text-center">2438</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row3_1_sum ?: '') . '</th>';
    echo '<th class="text-center">1219</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_1_sum ?: '') . '</th>';
    echo '<th rowspan="3" class="text-center"> 하단마감재 <br> (SUS 1.2T) </th>';
    echo '<th rowspan="3" class="text-center text-primary fw-bold">' . ($row7_1_sum ?: '') . '</th>';
    echo '<th rowspan="3" class="text-center text-primary fw-bold">' . ($row8_1_sum ?: '') . '</th>';
    echo '<th rowspan="5" class="text-center">레일용</th>';
    echo '<th rowspan="1" class="text-center text-dark fw-bold">2438 </th>';
    echo '<th rowspan="1" class="text-center text-primary fw-bold">' . ($row10_1_sum ?: '') . '</th>';
echo '</tr>';    
echo '<tr>';    // 4행
    echo '<th class="text-center">3000</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row3_2_sum ?: '') . '</th>';
    echo '<th class="text-center">2438</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_2_sum ?: '') . '</th>';
	echo '<th rowspan="1" class="text-center text-dark fw-bold"> 3000 </th>';
    echo '<th rowspan="1" class="text-center text-primary fw-bold">' . ($row10_2_sum ?: '') . '</th>';	
echo '</tr>';
echo '<tr>'; // 5행
    echo '<th class="text-center">3500</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row3_3_sum ?: '') . '</th>';                    
    echo '<th class="text-center">3000</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_3_sum ?: '') . '</th>';   
	echo '<th rowspan="1" class="text-center text-dark fw-bold"> 3500 </th>';	
    echo '<th rowspan="1" class="text-center text-primary fw-bold">' . ($row10_3_sum ?: '') . '</th>';	
echo '</tr>';
echo '<tr>';  // 6행
    echo '<th class="text-center">4000</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row3_4_sum ?: '') . '</th>';                    
    echo '<th class="text-center">3500</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_4_sum ?: '') . '</th>';
    echo '<th rowspan="3" class="text-center"> 하단보강엘바 <br> (EGI 1.2T) </th>';
    echo '<th rowspan="3" class="text-center text-primary fw-bold">' . ($row7_2_sum ?: '') . '</th>';
    echo '<th rowspan="3" class="text-center text-primary fw-bold">' . ($row8_2_sum ?: '') . '</th>';
	echo '<th rowspan="1" class="text-center text-dark fw-bold"> 4000 </th>';	
    echo '<th rowspan="1" class="text-center text-primary fw-bold">' . ($row10_4_sum ?: '') . '</th>';
echo '</tr>';
echo '<tr>';  // 7행
    echo '<th class="text-center">4300</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row3_5_sum ?: '') . '</th>';    
    echo '<th class="text-center">4000</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_5_sum ?: '') . '</th>'; 
	echo '<th rowspan="1" class="text-center text-dark fw-bold"> 4300 </th>';		
	echo '<th rowspan="1" class="text-center text-primary fw-bold">' . ($row10_5_sum ?: '') . '</th>';
echo '</tr>';
echo '<tr>';  // 8행
    echo '<th class="text-center" rowspan="3" colspan="2" ></th>';
    echo '<th class="text-center">4150</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_6_sum ?: '') . '</th>';    
    echo '<th rowspan="1" class="text-center">케이스용</th>';	
    echo '<th rowspan="1" class="text-center">3000</th>';	
    echo '<th rowspan="1" class="text-center text-primary fw-bold">' . ($row10_6_sum ?: '') . '</th>';	
echo '</tr>';
echo '<tr>';
    
    echo '<th class="text-center">상부덮개</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_7_sum ?: '') . '</th>';       
    echo '<th rowspan="2" class="text-center"> 하단보강평철 </th>';
    echo '<th rowspan="2" class="text-center text-primary fw-bold">' . ($row7_3_sum ?: '') . '</th>';        
    echo '<th rowspan="2" class="text-center text-primary fw-bold">' . ($row8_3_sum ?: '') . '</th>';        
echo '</tr>';
echo '<tr>';
    
    echo '<th class="text-center">마구리</th>';
    echo '<th class="text-center text-primary fw-bold">' . ($row5_8_sum ?: '') . '</th>';                           
echo '</tr>';    
echo '</thead>';
echo '</table>';
echo '</div>';
}

// 부자재 (감기샤프트 등) 부분
if (True) {	
    $data = [];	
    $row2_4_sum = 0;
    $row3_4_sum = 0;
    $row4_4_sum = 0;
    $row5_4_sum = 0;
    $row6_4_sum = 0;
    $row7_4_sum = 0;
    $row8_4_sum = 0;
    $row9_4_sum = 0;
    $row10_4_sum = 0;
    $row11_4_sum = 0;
    $row12_4_sum = 0;
    $row13_4_sum = 0;
    $row2_7_sum = 0;
    $row4_7_sum = 0;
    $row5_7_sum = 0;
    $row6_7_sum = 0;
    $row7_7_sum = 0;
    $row8_7_sum = 0;
   
    foreach ($decodedEstimateList as $item) {
		
        // 누적 합계 계산 2인치 500 없어서 숫자 맞지 않음
		$row2_4_sum  +=  floatval($item['col59']); // 300 샤프트
		$row3_4_sum  +=  floatval($item['col60']); // 3000 샤프트
		$row4_4_sum  +=  floatval($item['col61']); // 4500 샤프트
		$row5_4_sum  +=  floatval($item['col62']); // 6000 샤프트
		$row6_4_sum  +=  floatval($item['col63']); // 6000 샤프트
		$row7_4_sum  +=  floatval($item['col64']); // 7000 샤프트
		$row8_4_sum  +=  floatval($item['col65']); // 8200 샤프트
		
		$row2_7_sum  +=  floatval($item['col57']); // 2열 7행 무게평철
		$row4_7_sum  +=  floatval($item['col70']); // 4열 7행 환봉
		$row6_7_sum  +=  floatval($item['col68']); // 6열 7행 각파이프 3000
		$row7_7_sum  +=  floatval($item['col69']); // 7열 7행 각파이프 6000
		$row8_7_sum  +=  floatval($item['col71']); // 8열 7행 앵글 2500
     }
	
echo '<div class=" d-flex align-items-center justify-content-center">';
echo '<table class="table" style="border-collapse: collapse;">';
echo '<thead>';
echo '<tr>';
echo '<th rowspan="7" class="text-center"> 2.4 <br> 부자재 </th>';
echo '<th colspan="12" class="text-center"> 감기샤프트  </th>';
echo '</tr>';
echo '<tr>';    // 2행
echo '<th colspan="2" class="text-center">2인치(보조)</th>';
echo '<th colspan="3" class="text-center">4인치</th>';
echo '<th colspan="3" class="text-center">5인치</th>';
echo '<th colspan="4" class="text-center">6인치</th>';
echo '</tr>';
echo '<tr>';    // 3행	
echo '<th class="text-center">300</th>';
echo '<th class="text-center">500</th>';
echo '<th class="text-center">3000</th>';
echo '<th class="text-center">4500</th>';
echo '<th class="text-center">6000</th>';
echo '<th class="text-center">6000</th>';
echo '<th class="text-center">7000</th>';
echo '<th class="text-center">8200</th>';
echo '<th class="text-center">3000</th>';
echo '<th class="text-center">6000</th>';
echo '<th class="text-center">7000</th>';
echo '<th class="text-center">8000</th>';
echo '</tr>';
echo '<tr>';    // 4행	    
echo '<th class="text-center text-primary fw-bold">' . ($row2_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row3_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row4_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row5_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row6_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row7_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row8_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row9_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row10_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row11_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row12_4_sum ?: '') . '</th>';
echo '<th class="text-center text-primary fw-bold">' . ($row13_4_sum ?: '') . '</th>';
echo '</tr>';	
echo '<tr>';    // 5행
echo '<th colspan="2" class="text-center"> 무게평철 <br> (50*12T) </th>';
echo '<th colspan="2" class="text-center"> 환봉 </th>';
echo '<th colspan="2" class="text-center"> 각파이프 </th>';
echo '<th colspan="2" class="text-center"> 앵글 </th>';
echo '</tr>';
echo '<tr>';   // 6행
echo '<th colspan="2" class="text-center"> 2000 </th>';
echo '<th colspan="2" class="text-center"> 3000 </th>';
echo '<th class="text-center"> 3000 </th>';
echo '<th class="text-center"> 6000 </th>';
echo '<th colspan="2" class="text-center"> 2500 </th>';
echo '</tr>';
echo '<tr>';   // 7행
echo '<th colspan="2" class="text-center text-primary fw-bold">' . ($row2_7_sum ?: '') . '</th>';
echo '<th colspan="2" class="text-center text-primary fw-bold">' . ($row4_7_sum ?: '') . '</th>';
echo '<th colspan="1" class="text-center text-primary fw-bold">' . ($row6_7_sum ?: '') . '</th>';
echo '<th colspan="1" class="text-center text-primary fw-bold">' . ($row7_7_sum ?: '') . '</th>';
echo '<th colspan="2" class="text-center text-primary fw-bold">' . ($row8_7_sum ?: '') . '</th>';
echo '</tr>';
echo '</thead>';
echo '</table>';
echo '</div>';

}

// 비고
if($option == 'option') {	    	
    echo '<div class="d-flex align-items-center justify-content-center mt-1 mb-5 ">';
    echo '<table class="table" style="border-collapse: collapse;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="text-center"> 비고 </th>';
    echo '<th colspan="5" class="text-start"> 
		★ 해당 견적서의 유효기간은 <span class="text-danger" style="text-decoration: underline;"> 발행일 기준 1개월 </span> 입니다. <br>
		★ 견적금액의 50%를 입금하시면 발주가 진행됩니다.
	</th>';
    echo '</tr>';
    echo '<tr>';    // 2행
    echo '<th class="text-center">결제방법</th>';
    echo '<th class="text-center">계좌이체</th>';
    echo '<th colspan="2" class="text-center">계좌정보</th>';
    echo '<th colspan="2" class="text-center">국민은행 796801-00-039630</th>';    
    echo '</tr>';    
    echo '<tr>';    // 3행
    echo '<th class="text-center">담당자</th>';
    echo '<th class="text-center"> ' . $orderman . ' ' . $position . '</th>';
    echo '<th colspan="1" class="text-center">연락처</th>';
    echo '<th colspan="1" class="text-center">070-4351-5275</th>';
    echo '<th colspan="1" class="text-center">E-mail</th>';    
    echo '<th colspan="1" class="text-center"> <span class="text-primary" >  kd5130@naver.com </span> </th>';    
    echo '</tr>';    
    echo '</thead>';
    echo '</table>';
    echo '</div>';	
}
?>

</div> <!-- end of container -->
<div class="container mb-5 mt-2">
	<div class="d-flex align-items-center justify-content-center mb-5">
	</div>
</div>


<?php include getDocumentRoot() . '/estimate/common/lastJS.php'; ?> <!--마지막에 추가되는 견적관련 JS  -->

</body>
</html>


<!-- lastJS.php 참고 -->

<!-- 페이지 로딩 -->
<script>
var ajaxRequest_write = null;
$(document).ready(function() {
    $('#loadingOverlay').hide(); // 로딩 오버레이 숨기기
    
    var dataList = <?php echo json_encode($detailJson ?? []); ?>;
    
    // JSON 데이터를 처리하기 전에 유효성 검사
    if (dataList && typeof dataList === 'string') {
        try {
            dataList = JSON.parse(dataList); // JSON 문자열을 객체로 변환
        } catch (e) {
            console.error('JSON parsing error: ', e);
            dataList = []; // 오류 발생 시 빈 배열로 초기화
        } 
    }

    // 배열인지 확인
    // console.log('dataList after JSON.parse check: ', dataList);
    if (!Array.isArray(dataList)) {
        dataList = [];
    }
	else
	{
		$("#updateText").text('견적수정됨');
	}

    // 테이블에 데이터 로드
    loadTableData('#detailTable', dataList);
	
	// 셔터박스 오류메시지 화면에 표시
	var shutterboxMsg = <?= json_encode($shutterboxMsg) ?>;

	// JavaScript로 처리
	if (shutterboxMsg) {
		var shutterboxDiv = document.getElementById("shutterboxMsg");
		shutterboxDiv.style.display = "block"; // 보이게 설정
		shutterboxDiv.innerHTML = shutterboxMsg; // 내용 설정
	}	
});

function loadTableData(tableBodySelector, dataList) {
     console.log('loadTableData data: ', dataList); // 여기서 데이터 확인
	
    var tableBody = $(tableBodySelector); // 테이블 본문 선택
    
    // 데이터를 반복하면서 테이블에 행을 업데이트
	var count = 1;
    dataList.forEach(function(rowData, index) {
        var row = tableBody.find('tr').eq(index + count); // index에 맞는 tr을 가져옴
        if (row.length) {
            updateRowData(row, rowData, index);
        }
        // console.log('index data: ', index); // 여기서 데이터 확인
        // console.log('rowData data: ', rowData); // 여기서 데이터 확인
		// count++;
    });
}

// 기존 행을 수정하는 함수
function updateRowData(row, rowData, rowIndex) {	       
    // 수정해야 할 td 요소들을 선택하여 해당 값을 업데이트
    row.find('.su-input').val(rowData[0]); // 수량
    row.find('.area-length-input').val(rowData[2]); // 길이
    row.find('.area-price-input').val(rowData[3]); // 면적단가
    row.find('.unit-price-input').val(rowData[4]); // 단가 

    // 수정된 행에 동적 계산 함수 호출
    calculateRowTotal(row); // 필요 시 계산 함수 호출
}


function inputNumber(input) {
    const value = input.value.replace(/,/g, ''); // 콤마 제거
    input.value = parseInt(value, 10).toLocaleString(); // 다시 콤마 추가
    calculateRowTotal($(input).closest('tr')); // 행의 합계 다시 계산
}

// 이벤트 리스너 설정 및 계산 함수 호출
function setupEventListeners() {
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function () {
            const row = input.closest('tr');
            calculateRowTotal(row);
            calculateAllSubtotals();
            calculateGrandTotal();
			calculateRowTotalFirstTable();
        });
    });
}

// 페이지 로드 시 초기 계산 및 이벤트 리스너 설정
window.onload = function () {
    calculateAllSubtotals();
    calculateGrandTotal();
    setupEventListeners();
	calculateRowTotalFirstTable();
};

    
$(document).ready(function () {
    var rowArray = <?= isset($row_array_json) ? $row_array_json : '[]' ?>;

    if (Array.isArray(rowArray) && rowArray.length > 0) {
        rowArray.forEach(function (rowspanValue, index) {
            var cell = document.getElementById('dynamicRowspan-' + index);
            console.log('index', index);
            if (cell && rowspanValue > 0) {
                cell.setAttribute('rowspan', rowspanValue);
            }
        });
    }
});


// 숫자 포맷팅 함수 (콤마 추가 및 소수점 둘째자리에서 반올림)
function formatNumber(value) {    
    const roundedValue = value;    
    // 콤마 추가 포맷팅
    return new Intl.NumberFormat().format(roundedValue);
}

// 숫자에서 콤마를 제거하는 함수
function cleanNumber(value) {
    // value가 null 또는 undefined인 경우 0을 반환하도록 처리
    if (!value) return 0;
    return parseFloat(value.replace(/,/g, '')) || 0;
}

// 입력 필드에서 숫자를 포맷팅하는 함수
function inputNumber(input) {
    const cursorPosition = input.selectionStart; // 현재 커서 위치를 저장
    const value = input.value.replace(/,/g, ''); // 입력값에서 숫자만 남기고 제거
    const formattedValue = Number(value).toLocaleString(); // 천 단위 콤마 추가
    input.value = formattedValue; // 포맷팅된 값으로 설정
    input.setSelectionRange(cursorPosition, cursorPosition); // 커서 위치 유지
}

// 세부내역 행별 합계 계산
function calculateRowTotal(row) {
    // row를 jQuery 객체로 변환
    row = $(row);

    // jQuery 객체에서 값을 가져옴
    const itemNameInput = row.find('.item-name'); 
    const suInput = row.find('.su-input'); 
    const areaLengthInput = row.find('.area-length-input');
    const areaPriceInput = row.find('.area-price-input');
    const unitPriceInput = row.find('.unit-price-input');

    const su = suInput.length ? cleanNumber(suInput.val()) : 1;
    const areaLength = areaLengthInput.length ? cleanNumber(areaLengthInput.val()) : 1;
    const areaPrice = areaPriceInput.length ? cleanNumber(areaPriceInput.val()) : 1;
    let unitPrice = unitPriceInput.length ? cleanNumber(unitPriceInput.val()) : 1;

    const roundedAreaPrice = parseFloat(areaPrice);

    if (roundedAreaPrice > 0) {
        unitPrice = Math.round(Math.round(areaLength * roundedAreaPrice )); // 소수점 첫째자리 반올림
        unitPriceInput.val(formatNumber(unitPrice)); // 단가 업데이트
    }

    let totalPrice = 0;
    if (!areaLength && !areaPrice) {
        totalPrice = Math.round(Math.round((su * unitPrice) ) ); 
    } else if (areaLength && !areaPrice) {
        totalPrice = Math.round(Math.round((areaLength * unitPrice * su) ) );
    } else {    
        totalPrice = Math.round(Math.round((su * unitPrice) ) ); 
    }
    
    const totalCell = row.find('.total-price');
    if (totalCell.length) {
        if(totalPrice>200)
            totalCell.text(formatNumber(totalPrice));
    }

    console.log('totalPrice', totalPrice);
    console.log('itemNameInput', itemNameInput.text());
    console.log('suInput', suInput.val());
    console.log('areaLengthInput', areaLengthInput.val());
    console.log('areaPriceInput', areaPriceInput.val());
    console.log('unitPriceInput', unitPriceInput.val());    

    // return Math.round( parseInt(totalCell.text().replace(/,/g, '')) );
    return totalPrice;
}


// 일련번호별 소계 계산 (jQuery 방식)
function calculateSubtotalBySerial(serialNumber) {
    let subtotal = 0; // 첫행은 0부터 시작이고 1로 시작한다. 그래서 -1로 시작한다.
    const rows = $(`.calculation-row[data-serial="${serialNumber}"]`);

    console.log('calculateSubtotalBySerial rows', rows);
    
    rows.each(function() {
        // Math.round()는 소수점 이하를 반올림 (예: 1.4 -> 1, 1.5 -> 2)
        // Math.ceil()은 소수점 이하를 무조건 올림 (예: 1.1 -> 2, 1.9 -> 2) 
        // Math.floor()는 소수점 이하를 무조건 버림 (예: 1.1 -> 1, 1.9 -> 1)
        const rowTotal = calculateRowTotal($(this));  // 300 이하는 일련번호로 취급한다. 안전장치이다.
        if (rowTotal > 300) {
            subtotal += rowTotal;
        }
        console.log(' calculateSubtotalBySerial subtotal', subtotal);
    });

    const subtotalCells = $(`.subtotal-cell[data-serial="${serialNumber}"]`);
    if (subtotalCells.length > 0) {
        subtotalCells.each(function() {
            $(this).text(formatNumber(subtotal));
            console.log(' subtotalCells 계산 : ', subtotal);
        });
    } else {
        console.error(`소계 셀을 찾을 수 없습니다. 일련번호: ${serialNumber}`);
    }

    return subtotal;
}

// 모든 일련번호별 소계 및 총합계 계산 (jQuery 방식)
function calculateAllSubtotals() {
    let grandTotal = 0;
    const uniqueSerials = new Set();
    $('.calculation-row').each(function() {
        uniqueSerials.add($(this).data('serial'));
    });

    uniqueSerials.forEach(function(serialNumber) {
        grandTotal += calculateSubtotalBySerial(serialNumber);
    });

    return grandTotal;
}

// 총합계 계산 (jQuery 방식)
function calculateGrandTotal() {
    const grandTotal = calculateAllSubtotals();
    const grandTotalCells = $('.grand-total');
    
    if (grandTotalCells.length > 0) {
        grandTotalCells.each(function() {
            $(this).text(formatNumber(grandTotal));
            console.log('각 grandTotal', grandTotal);
        });
        $('#totalsum').text(formatNumber(grandTotal));
		var EstimateFirstSum = $("#EstimateFirstSum").val();
		var EstimateUpdatetSum = $("#EstimateUpdatetSum").val();		
        $('#koreantotalsum').text(KoreanNumber(Math.round(grandTotal)));
		// 총금액에서 최초금액 추출하기
		if(grandTotal > 0 && EstimateFirstSum < 1)
			$("#EstimateFirstSum").val(formatNumber(grandTotal));
		  else
			  $("#EstimateUpdatetSum").val(formatNumber(grandTotal));
		// 차액계산
		if(cleanNumber($("#EstimateUpdatetSum").val())> 0)
			$("#EstimateDiffer").val(formatNumber(cleanNumber($("#EstimateUpdatetSum").val()) - cleanNumber($("#EstimateFirstSum").val())));
		else
			$("#EstimateDiffer").val(0);
		
        // console.log('grand total : ', grandTotal);
    } else {
        console.error("전체 합계 셀을 찾을 수 없습니다. '.grand-total'이라는 클래스가 올바르게 설정되었는지 확인해주세요.");
    }
}

// 첫 번째 테이블: 행별 합계 계산 (jQuery 방식)
function calculateRowTotalFirstTable() {
    const rows = $('.calculation-firstrow');

    rows.each(function() {
        const suInput = $(this).find('.total-su-input'); // 수량 입력 필드
        const subtotalCell = $(this).find('.subtotal-cell'); // 소계 셀
        const unitPriceCell = $(this).find('.total-unit-price-input'); // 단가 셀

        const su = suInput ? cleanNumber(suInput.text()) : 1;
        const subtotal = subtotalCell ? cleanNumber(subtotalCell.text()) : 0;

        let unitPrice = 0;
        if (su > 0) {
            unitPrice = subtotal / su; // 소계를 수량으로 나눠서 단가 계산
        }

        if (unitPriceCell.length) {
            unitPriceCell.text(formatNumber(unitPrice)); // 단가 셀에 표시
        }
    });
}

function KoreanNumber(number) {
    const koreanNumbers = ['', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구'];
    const koreanUnits = ['', '십', '백', '천'];
    const bigUnits = ['', '만', '억', '조'];

    let result = '';
    let unitIndex = 0;
    let numberStr = String(number);

    // 숫자가 0인 경우 '영원'을 반환
    if (number == 0) return '영원';

    // 뒤에서부터 4자리씩 끊어서 처리
    while (numberStr.length > 0) {
        let chunk = numberStr.slice(-4); // 마지막 4자리
        numberStr = numberStr.slice(0, -4); // 나머지 숫자

        let chunkResult = '';
        for (let i = 0; i < chunk.length; i++) {
            const digit = parseInt(chunk[i]);
            if (digit > 0) {
                chunkResult += koreanNumbers[digit] + koreanUnits[chunk.length - i - 1];
            }
        }

        if (chunkResult) {
            result = chunkResult + bigUnits[unitIndex] + result;
        }
        unitIndex++;
    }

    // 불필요한 '일십', '일백', '일천' 등의 단위를 제거하고 '원'을 붙여 반환
    result = result.replace(/일(?=십|백|천)/g, '').trim();

    return result + '';
}

function removeAllButLastOccurrence(string, target) {
	// 마지막 '만'의 위치를 찾습니다
	const lastPos = string.lastIndexOf(target);

	// 마지막 '만'이 없으면 원래 문자열을 반환합니다
	if (lastPos === -1) {
		return string;
	}

	// 마지막 '만'을 제외한 모든 '만'을 제거합니다
	const beforeLastPos = string.slice(0, lastPos);
	const afterLastPos = string.slice(lastPos);

	// '만'을 빈 문자열로 대체합니다
	const result = beforeLastPos.replace(new RegExp(target, 'g'), '') + afterLastPos;

	return result;
}	

$(document).ready(function() {
    // 저장 버튼 클릭 시 saveData 함수 호출
    $(".saveBtn").click(function() {
        saveData();
    });
});

function saveData() {
    const myform = document.getElementById('board_form');
    let allValid = true;

    if (!allValid) return;

    var num = $("#num").val();
    $("#overlay").show();
    $("button").prop("disabled", true);

    // 모드 설정 (insert 또는 modify)
    if ($("#mode").val() !== 'copy') {
        if (Number(num) < 1) {
            $("#mode").val('insert');
        } else {
            $("#mode").val('modify');
        }
    } else {
        $("#mode").val('insert');
    }

	// 데이터 수집 (input 요소만 저장)
	let formData = [];
	$('#detailTable tbody tr').each(function() {
		let rowData = [];
		// 각 tr의 input 요소 순서대로 처리
		$(this).find('input, select').each(function() {
			let value = $(this).val();
			rowData.push(value); // input 값을 배열에 순서대로 추가
		});
		formData.push(rowData); // 각 행의 input 데이터를 배열에 추가
	});

	// formData는 이제 각 행의 input 값들만 포함하는 배열입니다.
	console.log('formData:', formData);


    // JSON 문자열로 변환하여 form input에 설정
    let jsonString = JSON.stringify(formData);
    $('#detailJson').val(jsonString);

    // console.log('detailJson', jsonString);
	
	$("#estimateSurang").val('<?= $estimateSurang ?>');  // 견적수량 저장
	$("#estimateTotal").val($("#subtotal").text());  // 견적총액 저장
	// console.log('$("#estimateSurang").val($("#subtotal").text())' , $("#estimateSurang").val());	
	// console.log('$("#estimateTotal").val($("#subtotal").text())' , $("#estimateTotal").val());	

    var form = $('#board_form')[0];
    var datasource = new FormData(form);

    if (ajaxRequest_write !== null) {
        ajaxRequest_write.abort();
    }
	
	showMsgModal(2); // 파일저장중	

    // Ajax 요청으로 서버에 데이터 전송
    ajaxRequest_write = $.ajax({
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        url: "/estimate/insert_detail.php", // 산출내역 저장
        type: "post",
        data: datasource,
        dataType: "json",
        success: function(data) {
            // console.log(data);			
			setTimeout(function() {						               				
				$(opener.location).attr("href", "javascript:restorePageNumber();");  				
				setTimeout(function() {						               
					hideMsgModal();				
					hideOverlay();					
				}, 1500);					
			}, 1000);	

        },
        error: function(jqxhr, status, error) {
            console.log(jqxhr, status, error);
        }
    });
}

function generatePDF() {
    var title_message = '<?php echo $title_message; ?>';
    var workplace = '<?php echo $outworkplace; ?>';
    var deadline = '<?php echo $indate; ?>';
    var deadlineDate = new Date(deadline);
    var formattedDate = "(" + String(deadlineDate.getFullYear()).slice(-2) + "." + ("0" + (deadlineDate.getMonth() + 1)).slice(-2) + "." + ("0" + deadlineDate.getDate()).slice(-2) + ")";
    var result = 'KD' + title_message + '(' + workplace +')' + formattedDate + '.pdf';
    
    var element = document.getElementById('content-to-print');
    var opt = {
        margin: [10, 3, 12, 3], // Top, right, bottom, left margins
        filename: result,
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
                scale: 3,   // ★★★ 해상도(기본은 1) → 2~4 정도로 높이면 선명해짐 4는 용량이 매우 큽니다. 보통 3M 이상
                useCORS: true,
                scrollY: 0,
                scrollX: 0,
                windowWidth: document.body.scrollWidth,
                windowHeight: document.body.scrollHeight        
         }, 
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: {
            mode: ['css', 'legacy'],
            avoid: ['tr', '.avoid-break'] // 핵심
        }
    };
    html2pdf().from(element).set(opt).save();
}

function generatePDF_server(callback) {
    var workplace = '<?php echo $title_message; ?>';
    var item = '<?php echo $emailTitle; ?>';
    var today = new Date();
    var formattedDate = "(" + String(today.getFullYear()).slice(-2) + "." + ("0" + (today.getMonth() + 1)).slice(-2) + "." + ("0" + today.getDate()).slice(-2) + ")";
    var result = 'KD' + item +'(' + workplace + ')' + formattedDate + '.pdf';

    var element = document.getElementById('content-to-print');
    var opt = {
        margin: [10, 3, 12, 3], // Top, right, bottom, left margins
        filename: result,
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 3 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: [''] }
    };

    html2pdf().from(element).set(opt).output('datauristring').then(function (pdfDataUri) {
        var pdfBase64 = pdfDataUri.split(',')[1]; // Base64 인코딩된 PDF 데이터 추출
        var formData = new FormData();
        formData.append('pdf', pdfBase64);
        formData.append('filename', result);

        $.ajax({
            type: 'POST',
            url: '/email/save_pdf.php', // PDF 파일을 저장하는 PHP 파일
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json', // JSON 응답을 기대함
            success: function (response) {
                console.log('PDF save response:', response);
                
                if (response.success && callback) {
                    callback(response.filename); // 서버에 저장된 파일 경로를 콜백으로 전달
                } else {
                    Swal.fire('Error', response.error || 'PDF 저장에 실패했습니다.', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('PDF save error:', xhr.responseText);
                Swal.fire('Error', 'PDF 저장에 실패했습니다.', 'error');
            }
        });
    });
}

var ajaxRequest = null;

function sendmail() {
    var secondordnum = '<?php echo $secondordnum; ?>'; // 서버에서 가져온 값
    var item = '<?php echo $emailTitle; ?>'; 

    console.log('secondordnum : ', secondordnum);

	if (!secondordnum) {
		Swal.fire({
			icon: 'warning',
			title: '오류 알림',
			text: '발주처 코드가 없습니다.'
		});
		return; // 함수 종료
	}	

    if (typeof ajaxRequest !== 'undefined' && ajaxRequest !== null) {
        ajaxRequest.abort();
    }
    
    ajaxRequest = $.ajax({
        type: 'POST',
        url: '/email/get_companyCode.php', 
        data: { secondordnum: secondordnum },
        dataType: 'json',
        success: function(response) {
            console.log('response : ', response);
            if (response.error) {
                Swal.fire('Error', response.error, 'error');
            } else {
                var email = response.email;
                var vendorName = response.vendor_name;

                Swal.fire({
                    title: 'E메일 보내기',
                    text: vendorName + ' Email 주소확인',
                    icon: 'warning',
                    input: 'text', // input 창을 텍스트 필드로 설정
                    inputLabel: 'Email 주소 수정 가능',
                    inputValue: email, // 기존 이메일 주소를 기본값으로 설정
                    showCancelButton: true,
                    confirmButtonText: '보내기',
                    cancelButtonText: '취소',
                    reverseButtons: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return '이메일 주소를 입력해주세요!';
                        }
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(value)) {
                            return '올바른 이메일 형식을 입력해주세요!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const updatedEmail = result.value; // 입력된 이메일 주소 가져오기
                        generatePDF_server(function(filename) {
                            sendEmail(updatedEmail, vendorName, item, filename);
                        });
                    }
                });

            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', '전송중 오류가 발생했습니다.', 'error');
        }
    });
}

function sendEmail(recipientEmail, vendorName, item, filename) {
    if (typeof ajaxRequest !== 'undefined' && ajaxRequest !== null) {
        ajaxRequest.abort();
    }
    var today = new Date();
    var formattedDate = "(" + String(today.getFullYear()).slice(-2) + "." + ("0" + (today.getMonth() + 1)).slice(-2) + "." + ("0" + today.getDate()).slice(-2) + ")";
    
    ajaxRequest = $.ajax({
        type: 'POST',
        url: '/email/send_email_alternative.php', // 대안 이메일 전송 파일 사용
        data: { email: recipientEmail, vendorName: vendorName, filename: filename, item: item, formattedDate: formattedDate },
        dataType: 'json', // JSON 응답을 기대함
        success: function(response) {
            console.log('Email response:', response);
            
            if (response.success) {
                Swal.fire('Success', response.message || '정상적으로 전송되었습니다.', 'success'); 
            } else {
                // 앱 비밀번호가 필요한 경우 안내
                if (response.error && response.error.includes('앱 비밀번호')) {
                    Swal.fire({
                        icon: 'warning',
                        title: '앱 비밀번호 필요',
                        text: '네이버에서 앱 비밀번호를 요구하고 있습니다. 설정 가이드를 확인해주세요.',
                        confirmButtonText: '가이드 보기',
                        showCancelButton: true,
                        cancelButtonText: '취소'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('/email/naver_app_password_guide.php', '_blank');
                        }
                    });
                } else {
                    Swal.fire('Error', response.error || '전송에 실패했습니다.', 'error'); 
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Email send error:', xhr.responseText);
            Swal.fire('Error', '전송에 실패했습니다. 확인바랍니다.', 'error'); 
        }
    });
}

$(document).ready(function() {
    // 재계산 버튼 클릭 이벤트
    $('.initialBtn').on('click', function() {
        // 재계산 확인 알림
        Swal.fire({
            title: '견적데이터 재계산',
            text: "견적 데이터를 재계산 하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '예, 재계산합니다',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
				
				$("#estimateTotal").val(0);  // 견적총액 저장
				$("#EstimateFirstSum").val(0);  // 견적초기금액
				$("#EstimateUpdatetSum").val(0);  // 수정금액
				$("#EstimateDiffer").val(0);  // 차액금액
				var form = $('#board_form')[0];
				var datasource = new FormData(form);				
                
                const initialData = JSON.stringify([]); // 빈 배열로 재계산
                                
                $('#detailJson').val(initialData);
                
                // 재계산된 데이터 저장 요청
                $.ajax({                    
					enctype: 'multipart/form-data',
					processData: false,
					contentType: false,
					cache: false,
					timeout: 600000,
					url: "/estimate/insert_detail.php",
					type: "post",
					data: datasource,
					dataType: "json",					
                    success: function(response) {
                        Swal.fire({
                            title: '재계산 완료',
                            text: "모든 데이터가 재계산되었습니다.",
                            icon: 'success',
                            confirmButtonText: '확인'
                        }).then(() => {
							hideMsgModal();	
                            // 페이지 새로고침
                            location.reload();
                        });
                    },
                    error: function(jqxhr, status, error) {
                        Swal.fire({
                            title: '오류',
                            text: "재계산 중 오류가 발생했습니다.",
                            icon: 'error',
                            confirmButtonText: '확인'
                        });
                        console.log("AJAX Error: ", status, error);
                    }
                });
            }
        });
    });
});

$(document).ready(function() {
    // 산출내역서 보이기/숨기기
    function toggleEstimateView() {
        $('#showEstimateCheckbox').is(':checked')
            ? $('.Estimateview').show()
            : $('.Estimateview').hide();
    }

    // 소요자재 보이기/숨기기 (vendor-send 토글 로직 제거)
    function toggleListView() {
        $('#showlistCheckbox').is(':checked')
            ? $('.listview, .vendor-send, .Novendor-send').show()
            : $('.listview, .vendor-send, .Novendor-send').hide();
    }

    // 업체발송용 영역 토글
    function toggleVendorDiv() {
            // 업체발송용 클릭시 나머지 두개의 체크박스를 해제한다.
            $('#showEstimateCheckbox').prop('checked', false);
            $('#showlistCheckbox').prop('checked', false);
            toggleEstimateView();
            toggleListView();
            if ($('#showVendorCheckbox').is(':checked')) {
                $('.vendor-send').show();
                $('.Novendor-send').hide();
            } else {
                $('.vendor-send').hide();
                    $('.Novendor-send').show();
                }
        }

    var option = $('#option').val(); // option은 견적서 화면에서 업체발송용으로 강제 지정

    if (option === 'option') {
        // 초기 상태 반영
        toggleEstimateView();
        toggleListView();
        toggleVendorDiv();
    }

    // 산출내역서 체크박스 이벤트
    $('#showEstimateCheckbox').on('change', toggleEstimateView);

    // 소요자재 체크박스 이벤트
    $('#showlistCheckbox').on('change', function() {
        toggleListView();

        // 소요자재 클릭 시 "업체발송용" 체크 해제 + 영역 토글
        $('#showVendorCheckbox').prop('checked', false);
    });

    // 업체발송용 체크박스 이벤트
    $('#showVendorCheckbox').on('change', toggleVendorDiv);
});


</script>


<!-- estimate_head.php 참고 -->

<?php include getDocumentRoot() . '/load_header.php';   ?>
<title> <?=$title_message?> </title>
<link rel="stylesheet" href="css/style.css?v=<?=time()?>">
</head>
<body>	
<?php
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : '';  
$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : '';   // 견적서와 산출서의 다른점을 표현하는 것
require_once(includePath('lib/mydb.php'));
require_once(includePath('estimate/fetch_unitprice.php'));
$pdo = db_connect();
	try {
		$sql = "select * from {$DB}.{$tablename} where num = ? ";
		$stmh = $pdo->prepare($sql);
		$stmh->bindValue(1, $num, PDO::PARAM_STR);
		$stmh->execute();
		$count = $stmh->rowCount();
		if ($count < 1) { 
			print "검색결과가 없습니다.<br>";
		} else {
			$row = $stmh->fetch(PDO::FETCH_ASSOC);
			include "_row.php";

			// $korean = number_to_korean($totalprice);
		}
	} catch (PDOException $Exception) {
		print "오류: " . $Exception->getMessage();
	}
	// JSON 문자열을 PHP 배열로 디코딩합니다.
	if($major_category == '스크린')
		$decodedEstimateList = json_decode($estimateList, true);
	else
		$decodedEstimateList = json_decode($estimateSlatList, true);
	

// 디코딩된 데이터가 배열인지 확인합니다.
if (!is_array($decodedEstimateList)) {
    echo "데이터가 정상적이지 않습니다. 확인바랍니다.";
    exit;
}

// detailJson 변수가 제대로 설정되었는지 확인
if (!isset($detailJson) || empty($detailJson)) {
    $detailJson = [];
}

echo '<script>';
echo 'var detailJsonData = ' . json_encode($detailJson) . ';';
echo '</script>';

$shutterboxMsg ='';

?>
  
<form id="board_form"  name="board_form" method="post" enctype="multipart/form-data">
	<input type="hidden" id="mode" name="mode" value="<?= isset($mode) ? $mode : '' ?>">
	<input type="hidden" id="num" name="num" value="<?= isset($num) ? $num : '' ?>">
	<input type="hidden" id="user_name" name="user_name" value="<?= isset($user_name) ? $user_name : '' ?>">
	<input type="hidden" id="update_log" name="update_log" value="<?= isset($update_log) ? $update_log : null ?>">
	<input type="hidden" id="tablename" name="tablename" value="<?= isset($tablename) ? $tablename : '' ?>">
	<input type="hidden" id="header" name="header" value="<?= isset($header) ? $header : '' ?>">	
	<input type="hidden" id="secondordnum" name="secondordnum" value="<?= isset($secondordnum) ? $secondordnum : '' ?>">	
	<input type="hidden" id="detailJson" name="detailJson"> <!-- 라디오버튼 저장하려면 두개의 변수가 필요하다.  -->	
	<input type="hidden" id="estimateSurang" name="estimateSurang"> <!-- 견적가 수량  -->	
	<input type="hidden" id="estimateTotal" name="estimateTotal"> <!-- 견적가 총액  -->	
	<input type="hidden" id="option" name="option" value="<?= isset($option) ? $option : '' ?>">

<div class="container mt-2">
    <div class="d-flex align-items-center justify-content-end mt-3 m-1 mb-4">
	     <span class="badge bg-secondary me-5 fs-5"> ( <?=$major_category?> ) </span>
        <input type="checkbox" id="showlistCheckbox" <?php echo ($option == 'option') ? '' : 'checked'; ?>>
        <label for="showlistCheckbox" class="me-3">소요자재</label>		

		<input type="checkbox" id="showEstimateCheckbox" <?php echo ($option == 'option') ? '' : 'checked'; ?>>
		<label for="showEstimateCheckbox" class="me-3">산출내역서</label>		

		<input type="checkbox" id="showVendorCheckbox" <?php echo ($option == 'option') ? 'checked' : ''; ?>>
		<label for="showVendorCheckbox" class="me-3">업체발송용</label>
		
		<button type="button" class="btn btn-danger btn-sm mx-1 initialBtn" >  <i class="bi bi-arrow-clockwise"></i>  재계산 </button>
		<button  type="button" class="btn btn-dark btn-sm mx-1 saveBtn" > <i class="bi bi-floppy"></i> 산출내역 저장 </button>		
        <button type="button" class="btn btn-dark btn-sm  mx-1" onclick="generatePDF()"> PDF 저장 </button>
		<button type="button" class="btn btn-dark btn-sm mx-1" onclick="sendmail();"> <i class="bi bi-envelope-arrow-up"></i> 전송 </button> 
        <button type="button" class="btn btn-secondary btn-sm  ms-5" onclick="self.close();"> &times; 닫기 </button>&nbsp;
    </div>
	<br>    
     <div class="d-flex align-items-center justify-content-center mt-2 mb-0">
		 <div class="alert alert-primary mb-2" role="alert">
			검사비는 제주도를 제외하고, 5만원으로 수정, '산출내역 저장'버튼을 누른 후 저장해야 금액이 확정됩니다.
		</div>
	</div>
	<div class="d-flex align-items-center justify-content-end mt-0">		
		<table class="table table-bordered mt-0 text-end w-auto">
		<thead >
			<tr>
				<th colspan="5" class="text-end">
					<span class="text-danger me-5"> 💬 수정금액이 있으면 자동금액보다 수정금액이 우선됨.</span>
					<span class="text-secondary">공급가액 기준, VAT별도</span>	
				</th>
			</tr>
			<tr class="table-secondary">
				<th class="text-center">자동금액</th>
				<th class="text-center">수정 금액</th>
				<th class="text-center">차액</th>								
			</tr>
		</thead>						
			<tbody>
				<tr>
					<td>
						<input type="text" id="EstimateFirstSum" name="EstimateFirstSum" class="form-control text-end" readonly 
							value="<?= isset($EstimateFirstSum) && $EstimateFirstSum != 0 ? number_format($EstimateFirstSum) : $EstimateFirstSum ?>">
					</td>
					<td>
						<input type="text" id="EstimateUpdatetSum" name="EstimateUpdatetSum" class="form-control text-end text-primary" readonly 
							value="<?= isset($EstimateUpdatetSum) && $EstimateUpdatetSum != 0 ? number_format($EstimateUpdatetSum) : $EstimateUpdatetSum ?>">
					</td>
					<td>
						<input type="text" id="EstimateDiffer" name="EstimateDiffer" class="form-control text-end text-danger" readonly 
							value="<?= isset($EstimateDiffer) && $EstimateDiffer != 0 ? number_format($EstimateDiffer) : $EstimateDiffer ?>">
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</form>

<div id="content-to-print">	
<br>
<div class="container mt-1">
<div class="d-flex align-items-center justify-content-center ">
    <table class="table table-sm" style="border-collapse: collapse;">
        <tbody>
            <tr>
                <td class="text-center fw-bold" style="width:8%;" >작성일자</td>                
                <td rowspan="2"  class="text-center align-middle fw-bold fs-4" style="width:60%; border-top:none; border-bottom:none;" > 
				<?php 
				if($option!=='option')
					echo '<span class="badge bg-primary"> ' . $title_message . ' </span>  </td>'; // <span id="updateText" class="text-danger"> </span> 
                   else
					echo '<span class="text-dark"> ' . $title_message_sub . ' </span> </td>';
				?>
								
                <td class="text-center fw-bold"  style="width:10%;" >견적번호</td>
				</tr>
				<tr>
				<td class="text-center" >  <?=$indate?> </td>				
                <td class="text-center fw-bold text-primary" >  <?=$pjnum?>  </td>
            </tr>            
        </tbody>
    </table>   
</div>


<div class="d-flex align-items-center justify-content-center ">
    <table class="table" style="border-collapse: collapse;">
        <tbody>
            <tr>
                <td class="text-center fw-bold yellowBold " style="width:10%;">업체명</td>
                <td class="text-center yellowBold " style="width:40%;"> <?=$secondord?> (귀하) </td>		
                <td rowspan="5" class="text-center align-middle fw-bold" style="width:5%; border-top:none; border-bottom:none;" >공 급 자</td>
                <td class="text-center fw-bold lightgray " > 상호 </td>
                <td class="text-center fw-bold" colspan="3" >㈜ 경동기업 </td>             
                
            </tr>
            <tr>
                <td class="text-center fw-bold">제품명</td>
                <td class="text-center" > <?=$subTitle?>   </td>
				<td class="text-center fw-bold lightgray "  style="width:10%;" >등록번호</td>
                <td class="text-center" style="width:10%;"> 139-87-00333 </td>
                <td class="text-center fw-bold lightgray " >대표자</td>
                <td class="text-center fw-bold">
					<div class="d-flex align-items-center justify-content-center ">
						이 경 호 &nbsp;
						<!-- <img src="../img/daehanstamp.png" alt="도장" style="width:45px; height:45px;"> -->
					</div>
                </td>				
			<tr>
                <td class="text-center fw-bold">현장명</td>
                <td class="text-center fw-bold" > <?=$outworkplace?></td>
                <td class="text-center fw-bold lightgray " > 사업장주소 </td>
                <td colspan="3" class="text-center"> 경기도 김포시 통진읍 옹정로 45-22</td>				
            </tr>
			<tr>				
				<td class="text-center fw-bold">담당자</td>
                <td class="text-center"><?=$secondordman?></td>
                <td class="text-center fw-bold lightgray " > 업 태 </td>
                <td class="text-center" > 제조업 </td>
                <td class="text-center fw-bold lightgray " >종목</td>
                <td class="text-center" > 방화셔터, 금속창호 </td>				
                
            </tr>
			<tr>
                <td  class="text-center fw-bold">연락처</td>
                <td  class="text-center"><?=$secondordmantel?></td>
                <td class="text-center fw-bold lightgray " > TEL. </td>
                <td class="text-center" > 031-983-5130</td>
                <td class="text-center fw-bold lightgray " > FAX </td>
                <td class="text-center" > 02-6911-6315 </td>		                
            </tr>            
        </tbody>
    </table>   
	</div>
	<div class="d-flex align-items-center justify-content-center ">	
		<table class="table" style="border-collapse: collapse;">
			<tbody>
				<tr>
					<!--
					<td class="text-center fw-bold" style="width:250px;" >
					합계금액(부가세 별도)  <br>
					아래와 같이 견적합니다				
					<span class="text-danger"> (VAT 별도) </span>
					</td>
					<td class="text-center  align-middle fs-6 fw-bold" style="width:50px;">  금 </td>				
					<td rowspan="5" class="text-end align-middle fw-bold fs-6" style="width:500px;" > <span id="koreantotalsum"> </span> </td>                
					<td class="text-center fw-bold  align-middle fs-6"  style="width:50px;" > 원 </td>
					-->
				<td colspan="3" class="align-middle text-end fs-6 fw-bold" style="width:250px;"> ( ￦ <span id="totalsum"> </span> )
					    <span class="text-danger"> (VAT 별도) </span>
				</td>
				</tr>
			</tbody>
		</table>
	</div>	

