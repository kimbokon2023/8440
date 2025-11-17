<?php
/**
 * 거래처 Excel 파일 임포트
 * corp/거래처리스트.xls 파일을 읽어서 데이터베이스에 저장
 */

require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/login/login_form.php");
    } else {
        header("Location: " . $WebSite . "login/login_form.php");
    }
    exit;
}

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// PHPExcel 라이브러리 로드
require_once("../PHPExcel_1.8.0/Classes/PHPExcel.php");
require_once("../PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php");

$filename = __DIR__ . '/거래처리스트.xls';

// 엑셀 파일 존재 확인
if (!file_exists($filename)) {
    die("<div class='alert alert-danger'>오류: 엑셀 파일을 찾을 수 없습니다. ({$filename})</div>");
}

$success_count = 0;
$error_count = 0;
$skip_count = 0;
$maxRow = 0;

// 기존 데이터 삭제 여부 확인 (GET 파라미터로 제어)
$clear_existing = isset($_GET['clear']) && $_GET['clear'] === '1';

// 기존 데이터 삭제
if ($clear_existing) {
    try {
        // 논리적 삭제가 아닌 물리적 삭제
        // 외래키 제약조건으로 인해 customer_contact를 먼저 삭제
        $pdo->exec("DELETE FROM {$DB}.customer_contact");
        $pdo->exec("DELETE FROM {$DB}.customer");
        echo "<div class='alert alert-warning'><strong>기존 데이터 삭제 완료:</strong> 모든 거래처 데이터가 삭제되었습니다.</div>";
    } catch (PDOException $ex) {
        die("<div class='alert alert-danger'>오류: 기존 데이터 삭제 실패 - " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>");
    }
}

// Excel 파일의 컬럼 매핑 (실제 Excel 파일 구조에 맞게 수정 필요)
// 예상 구조: A=구분, B=거래처명, C=상호, D=등록번호, E=대표자, F=전화번호, G=휴대폰, H=FAX, I=업태, J=종목, K=적요, L=주소, M=사업자번호, N=등록일, O=매출, P=매입, Q=기타, R=은행, S=계좌번호, T=예금주

try {
    // 엑셀 파일 읽기
    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
    
    // 읽기 전용 설정 (PHPExcel 라이브러리 메서드)
    // PHPExcel Reader는 실제로 setReadDataOnly 메서드를 가지고 있지만, 인터페이스에 정의되지 않아 linter 경고가 발생함
    // 실제 실행에는 문제가 없으므로 오류를 무시함
    $setReadDataOnlyMethod = 'setReadDataOnly';
    if (method_exists($objReader, $setReadDataOnlyMethod)) {
        call_user_func(array($objReader, $setReadDataOnlyMethod), true);
    }
    
    $objExcel = $objReader->load($filename);
    
    // 첫번째 시트 선택
    $objExcel->setActiveSheetIndex(0);
    $objWorksheet = $objExcel->getActiveSheet();
    
    $maxRow = $objWorksheet->getHighestRow();
    $maxCol = $objWorksheet->getHighestColumn();
    
    // 컬럼 인덱스를 숫자로 변환 (A=0, B=1, ..., Z=25, AA=26, ...)
    // PHPExcel_Cell::columnIndexFromString는 실제로 존재하는 메서드이지만 linter가 인식하지 못함
    // @phan-suppress-next-line PhanUndeclaredStaticMethod
    $maxColIndex = PHPExcel_Cell::columnIndexFromString($maxCol);
    
    // 숫자를 Excel 컬럼 문자로 변환하는 함수 (A, B, C, ..., Z, AA, AB, ...)
    function getColumnLetter($index) {
        $letters = '';
        while ($index >= 0) {
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = intval($index / 26) - 1;
        }
        return $letters;
    }
    
    echo "<div class='alert alert-info'><strong>엑셀 파일 읽기 완료:</strong> 총 {$maxRow}행, {$maxColIndex}열 발견</div>";
    
    // 첫 번째 행(헤더) 읽어서 출력 (실제 컬럼 구조 확인용)
    echo "<div class='alert alert-secondary'><strong>Excel 파일 헤더 (첫 번째 행):</strong><br>";
    echo "<table class='table table-sm table-bordered mt-2' style='font-size: 11px;'>";
    echo "<thead><tr>";
    for ($col = 0; $col < $maxColIndex; $col++) {
        $colLetter = getColumnLetter($col);
        echo "<th style='background-color: #f0f0f0; padding: 5px;'>" . $colLetter . "</th>";
    }
    echo "</tr></thead><tbody><tr>";
    for ($col = 0; $col < $maxColIndex; $col++) {
        $colLetter = getColumnLetter($col);
        $headerValue = trim($objWorksheet->getCell($colLetter . '1')->getValue());
        echo "<td style='word-break: break-all; padding: 5px;'>" . htmlspecialchars($headerValue ?: '(비어있음)', ENT_QUOTES, 'UTF-8') . "</td>";
    }
    echo "</tr></tbody></table>";
    
    // 두 번째 행도 출력 (실제 데이터 구조 확인용)
    if ($maxRow >= 2) {
        echo "<br><strong>Excel 파일 두 번째 행 (샘플 데이터):</strong><br>";
        echo "<table class='table table-sm table-bordered mt-2' style='font-size: 11px;'>";
        echo "<thead><tr>";
        for ($col = 0; $col < $maxColIndex; $col++) {
            $colLetter = getColumnLetter($col);
            echo "<th style='background-color: #f0f0f0; padding: 5px;'>" . $colLetter . "</th>";
        }
        echo "</tr></thead><tbody><tr>";
        for ($col = 0; $col < $maxColIndex; $col++) {
            $colLetter = getColumnLetter($col);
            $sampleValue = trim($objWorksheet->getCell($colLetter . '2')->getValue());
            echo "<td style='word-break: break-all; padding: 5px;'>" . htmlspecialchars($sampleValue ?: '(비어있음)', ENT_QUOTES, 'UTF-8') . "</td>";
        }
        echo "</tr></tbody></table>";
    }
    echo "</div>";
    
    // 헤더 행에서 컬럼 매핑 찾기
    $columnMapping = array();
    for ($col = 0; $col < $maxColIndex; $col++) {
        $colLetter = getColumnLetter($col);
        $headerValue = trim($objWorksheet->getCell($colLetter . '1')->getValue());
        $headerLower = mb_strtolower($headerValue, 'UTF-8');
        
        // 헤더 값으로 컬럼 매핑 (우선순위 순서로 체크)
        
        // 1. 상호(법인명) - 거래처명도 이 컬럼 사용 (A)
        if (strpos($headerLower, '상호') !== false || (strpos($headerLower, '법인명') !== false && strpos($headerLower, '등록일') === false)) {
            $columnMapping['trade_name'] = $colLetter;
            // 거래처명도 상호(법인명)과 같은 컬럼 사용
            if (!isset($columnMapping['company_name'])) {
                $columnMapping['company_name'] = $colLetter;
            }
        }
        // 2. 거래처명 - 상호가 아닌 경우만 (거래처등록일 제외)
        elseif ((strpos($headerLower, '거래처명') !== false || strpos($headerLower, '거래처') !== false || strpos($headerLower, '업체명') !== false) 
                && strpos($headerLower, '등록일') === false && !isset($columnMapping['company_name'])) {
            $columnMapping['company_name'] = $colLetter;
        }
        // 3. 구분
        elseif (strpos($headerLower, '구분') !== false || strpos($headerLower, '분류') !== false) {
            $columnMapping['classification'] = $colLetter;
        }
        // 4. 사업자번호 - 정확히 일치하는 경우만 (C)
        elseif (strpos($headerLower, '사업자번호') !== false || ($colLetter === 'C' && strpos($headerLower, '사업자') !== false)) {
            $columnMapping['business_registration_number'] = $colLetter;
        }
        // 5. 등록번호 (사업자번호 제외)
        elseif (strpos($headerLower, '등록번호') !== false && strpos($headerLower, '사업자') === false) {
            $columnMapping['registration_number'] = $colLetter;
        }
        // 6. 대표자명 - 대표전화 제외 (D)
        elseif (($colLetter === 'D' && strpos($headerLower, '대표') !== false) ||
                (strpos($headerLower, '대표자명') !== false || strpos($headerLower, '대표이사') !== false || 
                 (strpos($headerLower, '대표') !== false && strpos($headerLower, '전화') === false && strpos($headerLower, '번호') === false))) {
            $columnMapping['representative_name'] = $colLetter;
        }
        // 7. 전화번호 - 명시적으로 E (회사 전화번호)
        elseif ($colLetter === 'E') {
            $columnMapping['phone_number'] = $colLetter;
        }
        // 8. 휴대폰번호 - 명시적으로 N (바뀜)
        elseif ($colLetter === 'N' || strpos($headerLower, '휴대폰') !== false || strpos($headerLower, '휴대') !== false || strpos($headerLower, '모바일') !== false) {
            $columnMapping['mobile_number'] = $colLetter;
        }
        // 9. FAX번호
        elseif (strpos($headerLower, '팩스') !== false || strpos($headerLower, 'fax') !== false) {
            $columnMapping['fax_number'] = $colLetter;
        }
        // 10. 업태
        elseif (strpos($headerLower, '업태') !== false || strpos($headerLower, '업종') !== false) {
            $columnMapping['business_type'] = $colLetter;
        }
        // 11. 종목 - 정확히 일치하는 경우만 (I, 사업장주소 제외)
        elseif (($colLetter === 'I' && strpos($headerLower, '종목') !== false) ||
                (strpos($headerLower, '종목') !== false && strpos($headerLower, '주소') === false)) {
            $columnMapping['business_category'] = $colLetter;
        }
        // 12. 주소 - 사업장주소 포함 (K)
        elseif (($colLetter === 'K' && strpos($headerLower, '주소') !== false) ||
                strpos($headerLower, '사업장주소') !== false || 
                (strpos($headerLower, '주소') !== false && strpos($headerLower, '소재지') !== false)) {
            $columnMapping['address'] = $colLetter;
        }
        // 13. 적요/비고 (J열)
        elseif ($colLetter === 'J' || strpos($headerLower, '적요') !== false || strpos($headerLower, '비고') !== false || strpos($headerLower, '메모') !== false) {
            $columnMapping['remarks'] = $colLetter;
        }
        // 14. 등록일 - 거래처등록일 포함 (S)
        elseif (($colLetter === 'S' && strpos($headerLower, '등록일') !== false) ||
                strpos($headerLower, '거래처등록일') !== false ||
                (strpos($headerLower, '등록일') !== false && strpos($headerLower, '번호') === false)) {
            $columnMapping['registration_date'] = $colLetter;
        }
        // 15. 거래처그룹 (L) - 매출, 매입, 기타 정보가 콤마로 구분되어 있음
        elseif ($colLetter === 'L' || strpos($headerLower, '거래처그룹') !== false || strpos($headerLower, '그룹') !== false) {
            $columnMapping['customer_group'] = $colLetter;
        }
        // 16. 매출거래처 (개별 컬럼인 경우)
        elseif (strpos($headerLower, '매출') !== false || strpos($headerLower, '판매') !== false) {
            $columnMapping['is_sales_customer'] = $colLetter;
        }
        // 17. 매입거래처 (개별 컬럼인 경우)
        elseif (strpos($headerLower, '매입') !== false || strpos($headerLower, '구매') !== false) {
            $columnMapping['is_purchase_customer'] = $colLetter;
        }
        // 18. 기타거래처 (개별 컬럼인 경우)
        elseif (strpos($headerLower, '기타') !== false) {
            $columnMapping['is_other_customer'] = $colLetter;
        }
        // 19. 은행명
        elseif (strpos($headerLower, '은행') !== false || strpos($headerLower, 'bank') !== false) {
            $columnMapping['bank_name'] = $colLetter;
        }
        // 20. 계좌번호
        elseif (strpos($headerLower, '계좌번호') !== false || (strpos($headerLower, '계좌') !== false && strpos($headerLower, '주') === false)) {
            $columnMapping['account_number'] = $colLetter;
        }
        // 21. 예금주
        elseif (strpos($headerLower, '예금주') !== false || strpos($headerLower, '계좌주') !== false) {
            $columnMapping['account_holder'] = $colLetter;
        }
        // 22. 이메일
        elseif (strpos($headerLower, '이메일') !== false || strpos($headerLower, 'email') !== false || strpos($headerLower, 'e-mail') !== false || strpos($headerLower, '메일') !== false) {
            $columnMapping['email'] = $colLetter;
        }
        // 23. 계산서 담당자 (Q열)
        elseif ($colLetter === 'Q' || strpos($headerLower, '계산서') !== false || strpos($headerLower, '담당자') !== false) {
            $columnMapping['is_invoice_contact'] = $colLetter;
        }
        // 24. 직급/부서 (P열)
        elseif ($colLetter === 'P' || strpos($headerLower, '직급') !== false || strpos($headerLower, '부서') !== false || strpos($headerLower, 'position') !== false || strpos($headerLower, 'department') !== false) {
            $columnMapping['position_department'] = $colLetter;
        }
    }
    
    // 사용자가 지정한 고정 매핑 적용 (우선순위)
    if (isset($columnMapping['trade_name']) && $columnMapping['trade_name'] === 'A') {
        $columnMapping['company_name'] = 'A';  // 거래처명 = 상호(법인명) = A
    }
    
    // 명시적 매핑 강제 적용 (사용자 지정)
    $forcedMappings = array(
        'company_name' => 'A',              // 거래처명
        'business_registration_number' => 'C',  // 사업자번호
        'representative_name' => 'D',       // 대표자명
        'phone_number' => 'E',              // 전화번호 (회사 전화번호)
        'mobile_number' => 'N',             // 휴대폰번호
        'business_category' => 'I',         // 종목
        'remarks' => 'J',                   // 비고
        'address' => 'K',                   // 주소
        'customer_group' => 'L',            // 거래처그룹
        'position_department' => 'P',       // 직급/부서
        'registration_date' => 'S'          // 등록일
    );
    
    // 강제 매핑 적용 (해당 컬럼이 존재하는 경우만)
    foreach ($forcedMappings as $field => $forcedCol) {
        $forcedColIndex = PHPExcel_Cell::columnIndexFromString($forcedCol) - 1;
        if ($forcedColIndex < $maxColIndex) {
            $columnMapping[$field] = $forcedCol;
        }
    }
    
    // 컬럼 매핑 결과 출력
    echo "<div class='alert alert-info'><strong>컬럼 매핑 결과:</strong><br>";
    echo "<table class='table table-sm table-bordered mt-2' style='font-size: 11px;'>";
    echo "<thead><tr><th>필드명</th><th>Excel 컬럼</th><th>헤더 내용</th></tr></thead><tbody>";
    $fieldNames = array(
        'classification' => '구분',
        'company_name' => '거래처명',
        'trade_name' => '상호(법인명)',
        'business_registration_number' => '사업자번호',
        'registration_number' => '등록번호',
        'representative_name' => '대표자명',
        'phone_number' => '전화번호',
        'mobile_number' => '휴대폰번호',
        'fax_number' => 'FAX번호',
        'business_type' => '업태',
        'business_category' => '종목',
        'address' => '주소',
        'remarks' => '적요/비고',
        'registration_date' => '등록일',
        'is_sales_customer' => '매출거래처',
        'is_purchase_customer' => '매입거래처',
        'is_other_customer' => '기타거래처',
        'bank_name' => '은행명',
        'account_number' => '계좌번호',
        'account_holder' => '예금주',
        'email' => '이메일',
        'customer_group' => '거래처그룹',
        'is_invoice_contact' => '계산서 담당자',
        'position_department' => '직급/부서'
    );
    foreach ($fieldNames as $field => $fieldLabel) {
        $colLetter = isset($columnMapping[$field]) ? $columnMapping[$field] : '(매핑 안됨)';
        $headerValue = isset($columnMapping[$field]) ? trim($objWorksheet->getCell($colLetter . '1')->getValue()) : '';
        $color = isset($columnMapping[$field]) ? '' : 'style="background-color: #ffeeee;"';
        echo "<tr {$color}><td>{$fieldLabel}</td><td>{$colLetter}</td><td>" . htmlspecialchars($headerValue, ENT_QUOTES, 'UTF-8') . "</td></tr>";
    }
    echo "</tbody></table></div>";
    
    // 필수 필드 확인 (거래처명 또는 상호가 필요)
    if (!isset($columnMapping['company_name']) && !isset($columnMapping['trade_name'])) {
        die("<div class='alert alert-danger'>오류: '거래처명' 또는 '상호(법인명)' 컬럼을 찾을 수 없습니다. Excel 파일의 첫 번째 행에 헤더가 있어야 합니다.</div>");
    }
    
    // 모든 행 처리 (2번째 행부터 시작, 첫 번째 행은 헤더로 가정)
    $startRow = 2;
    for ($i = $startRow; $i <= $maxRow; $i++) {
        // 엑셀 셀 데이터 읽기 (매핑된 컬럼 사용)
        $classification = isset($columnMapping['classification']) ? trim($objWorksheet->getCell($columnMapping['classification'] . $i)->getValue()) : '';
        $company_name = trim($objWorksheet->getCell($columnMapping['company_name'] . $i)->getValue()); // 거래처명 (필수)
        $trade_name = isset($columnMapping['trade_name']) ? trim($objWorksheet->getCell($columnMapping['trade_name'] . $i)->getValue()) : '';
        $business_registration_number = isset($columnMapping['business_registration_number']) ? trim($objWorksheet->getCell($columnMapping['business_registration_number'] . $i)->getValue()) : '';
        $registration_number = isset($columnMapping['registration_number']) ? trim($objWorksheet->getCell($columnMapping['registration_number'] . $i)->getValue()) : '';
        $representative_name = isset($columnMapping['representative_name']) ? trim($objWorksheet->getCell($columnMapping['representative_name'] . $i)->getValue()) : '';
        $phone_number = isset($columnMapping['phone_number']) ? trim($objWorksheet->getCell($columnMapping['phone_number'] . $i)->getValue()) : '';
        $mobile_number = isset($columnMapping['mobile_number']) ? trim($objWorksheet->getCell($columnMapping['mobile_number'] . $i)->getValue()) : '';
        $fax_number = isset($columnMapping['fax_number']) ? trim($objWorksheet->getCell($columnMapping['fax_number'] . $i)->getValue()) : '';
        $business_type = isset($columnMapping['business_type']) ? trim($objWorksheet->getCell($columnMapping['business_type'] . $i)->getValue()) : '';
        $business_category = isset($columnMapping['business_category']) ? trim($objWorksheet->getCell($columnMapping['business_category'] . $i)->getValue()) : '';
        $remarks = isset($columnMapping['remarks']) ? trim($objWorksheet->getCell($columnMapping['remarks'] . $i)->getValue()) : '';
        $address = isset($columnMapping['address']) ? trim($objWorksheet->getCell($columnMapping['address'] . $i)->getValue()) : '';
        $registration_date_str = isset($columnMapping['registration_date']) ? trim($objWorksheet->getCell($columnMapping['registration_date'] . $i)->getValue()) : '';
        $customer_group = isset($columnMapping['customer_group']) ? trim($objWorksheet->getCell($columnMapping['customer_group'] . $i)->getValue()) : '';
        $is_sales_customer = isset($columnMapping['is_sales_customer']) ? trim($objWorksheet->getCell($columnMapping['is_sales_customer'] . $i)->getValue()) : '';
        $is_purchase_customer = isset($columnMapping['is_purchase_customer']) ? trim($objWorksheet->getCell($columnMapping['is_purchase_customer'] . $i)->getValue()) : '';
        $is_other_customer = isset($columnMapping['is_other_customer']) ? trim($objWorksheet->getCell($columnMapping['is_other_customer'] . $i)->getValue()) : '';
        $bank_name = isset($columnMapping['bank_name']) ? trim($objWorksheet->getCell($columnMapping['bank_name'] . $i)->getValue()) : '';
        $account_number = isset($columnMapping['account_number']) ? trim($objWorksheet->getCell($columnMapping['account_number'] . $i)->getValue()) : '';
        $account_holder = isset($columnMapping['account_holder']) ? trim($objWorksheet->getCell($columnMapping['account_holder'] . $i)->getValue()) : '';
        $email = isset($columnMapping['email']) ? trim($objWorksheet->getCell($columnMapping['email'] . $i)->getValue()) : '';
        $is_invoice_contact = isset($columnMapping['is_invoice_contact']) ? trim($objWorksheet->getCell($columnMapping['is_invoice_contact'] . $i)->getValue()) : '';
        $position_department = isset($columnMapping['position_department']) ? trim($objWorksheet->getCell($columnMapping['position_department'] . $i)->getValue()) : '';
        
        // 거래처명 설정: 상호(법인명)이 있으면 거래처명과 같게 설정
        if (!empty($trade_name)) {
            $company_name = $trade_name;
        }
        
        // 거래처명이 없으면 건너뛰기
        if (empty($company_name)) {
            $skip_count++;
            echo "<div class='alert alert-warning'>행 {$i}: 거래처명이 없어 건너뜁니다.</div>";
            continue;
        }
        
        // 거래처명과 상호(법인명)이 같도록 설정
        if (empty($trade_name)) {
            $trade_name = $company_name;
        } else {
            // 거래처명을 상호(법인명)과 같게 설정
            $company_name = $trade_name;
        }
        
        // 구분 기본값 설정
        if (empty($classification) || !in_array($classification, ['사업자', '개인'])) {
            $classification = '사업자';
        }
        
        // 날짜 형식 변환
        $registration_date = null;
        if (!empty($registration_date_str)) {
            // Excel 날짜 형식 변환 시도
            if (is_numeric($registration_date_str)) {
                // Excel 날짜 숫자 형식 (1900-01-01 기준)
                try {
                    $excelDate = (float)$registration_date_str;
                    // PHPExcel_Shared_Date::ExcelToPHPObject는 float도 받을 수 있지만, 타입 정의상 long을 기대함
                    // 실제 실행에는 문제가 없으므로 경고를 무시함
                    // PHP에서 int와 long은 동일하지만, 타입 정의가 long으로 되어 있어 경고 발생
                    $excelDateInt = (int)$excelDate;
                    // @phan-suppress-next-line PhanTypeMismatchArgument
                    $phpDate = PHPExcel_Shared_Date::ExcelToPHPObject($excelDateInt);
                    if ($phpDate) {
                        $registration_date = $phpDate->format('Y-m-d');
                    }
                } catch (Exception $e) {
                    // Excel 날짜 변환 실패 시 문자열 날짜로 처리
                    $date_timestamp = strtotime($registration_date_str);
                    if ($date_timestamp !== false) {
                        $registration_date = date('Y-m-d', $date_timestamp);
                    }
                }
            } else {
                // 문자열 날짜 형식
                $date_timestamp = strtotime($registration_date_str);
                if ($date_timestamp !== false) {
                    $registration_date = date('Y-m-d', $date_timestamp);
                }
            }
        }
        
        // 거래처그룹 파싱 (L열: '매출', '매입,매출' 등)
        // 거래처그룹이 있으면 개별 컬럼 값보다 우선
        if (!empty($customer_group)) {
            // 콤마로 구분된 값들 파싱
            $groups = explode(',', $customer_group);
            $is_sales_customer = 'N';
            $is_purchase_customer = 'N';
            $is_other_customer = 'N';
            
            foreach ($groups as $group) {
                $group_trimmed = trim($group);
                $group_lower = mb_strtolower($group_trimmed, 'UTF-8');
                
                if (strpos($group_lower, '매출') !== false || strpos($group_lower, '판매') !== false) {
                    $is_sales_customer = 'Y';
                }
                if (strpos($group_lower, '매입') !== false || strpos($group_lower, '구매') !== false) {
                    $is_purchase_customer = 'Y';
                }
                if (strpos($group_lower, '기타') !== false || strpos($group_lower, 'other') !== false) {
                    $is_other_customer = 'Y';
                }
            }
        } else {
            // 개별 컬럼이 있는 경우 Y/N 값 정규화
            $is_sales_customer = (!empty($is_sales_customer) && (strtoupper($is_sales_customer) === 'Y' || $is_sales_customer === '1' || strtolower($is_sales_customer) === '예' || strtolower($is_sales_customer) === 'yes')) ? 'Y' : 'N';
            $is_purchase_customer = (!empty($is_purchase_customer) && (strtoupper($is_purchase_customer) === 'Y' || $is_purchase_customer === '1' || strtolower($is_purchase_customer) === '예' || strtolower($is_purchase_customer) === 'yes')) ? 'Y' : 'N';
            $is_other_customer = (!empty($is_other_customer) && (strtoupper($is_other_customer) === 'Y' || $is_other_customer === '1' || strtolower($is_other_customer) === '예' || strtolower($is_other_customer) === 'yes')) ? 'Y' : 'N';
        }
        
        try {
            $pdo->beginTransaction();
            
            // 중복 체크 (거래처명과 등록번호로)
            $checkSql = "SELECT num FROM {$DB}.customer WHERE company_name = :company_name AND is_deleted = 'N'";
            if (!empty($registration_number)) {
                $checkSql .= " OR registration_number = :registration_number";
            }
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->bindValue(':company_name', $company_name, PDO::PARAM_STR);
            if (!empty($registration_number)) {
                $checkStmt->bindValue(':registration_number', $registration_number, PDO::PARAM_STR);
            }
            $checkStmt->execute();
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // 기존 거래처 업데이트
                // last_modified_date는 등록일과 동일하게 설정 (등록일이 없으면 현재 시간)
                // 등록일이 DATE 형식이면 TIMESTAMP 형식으로 변환 (시간 부분 추가)
                if (!empty($registration_date)) {
                    // DATE 형식(YYYY-MM-DD)이면 시간 부분 추가
                    if (strlen($registration_date) == 10) {
                        $last_modified_date_value = $registration_date . ' 00:00:00';
                    } else {
                        $last_modified_date_value = $registration_date;
                    }
                } else {
                    $last_modified_date_value = date('Y-m-d H:i:s');
                }
                
                $sql = "UPDATE {$DB}.customer SET 
                    classification = :classification,
                    trade_name = :trade_name,
                    registration_number = :registration_number,
                    representative_name = :representative_name,
                    phone_number = :phone_number,
                    mobile_number = :mobile_number,
                    fax_number = :fax_number,
                    business_type = :business_type,
                    business_category = :business_category,
                    remarks = :remarks,
                    address = :address,
                    business_registration_number = :business_registration_number,
                    registration_date = :registration_date,
                    is_sales_customer = :is_sales_customer,
                    is_purchase_customer = :is_purchase_customer,
                    is_other_customer = :is_other_customer,
                    bank_name = :bank_name,
                    account_number = :account_number,
                    account_holder = :account_holder,
                    last_modified_date = :last_modified_date
                    WHERE num = :num";
                
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':num', $existing['num'], PDO::PARAM_INT);
                $stmh->bindValue(':classification', $classification, PDO::PARAM_STR);
                $stmh->bindValue(':trade_name', empty($trade_name) ? null : $trade_name, PDO::PARAM_STR);
                $stmh->bindValue(':registration_number', empty($registration_number) ? null : $registration_number, PDO::PARAM_STR);
                $stmh->bindValue(':representative_name', empty($representative_name) ? null : $representative_name, PDO::PARAM_STR);
                $stmh->bindValue(':phone_number', empty($phone_number) ? null : $phone_number, PDO::PARAM_STR);
                $stmh->bindValue(':mobile_number', empty($mobile_number) ? null : $mobile_number, PDO::PARAM_STR);
                $stmh->bindValue(':fax_number', empty($fax_number) ? null : $fax_number, PDO::PARAM_STR);
                $stmh->bindValue(':business_type', empty($business_type) ? null : $business_type, PDO::PARAM_STR);
                $stmh->bindValue(':business_category', empty($business_category) ? null : $business_category, PDO::PARAM_STR);
                $stmh->bindValue(':remarks', empty($remarks) ? null : $remarks, PDO::PARAM_STR);
                $stmh->bindValue(':address', empty($address) ? null : $address, PDO::PARAM_STR);
                $stmh->bindValue(':business_registration_number', empty($business_registration_number) ? null : $business_registration_number, PDO::PARAM_STR);
                $stmh->bindValue(':registration_date', empty($registration_date) ? null : $registration_date, PDO::PARAM_STR);
                $stmh->bindValue(':is_sales_customer', $is_sales_customer, PDO::PARAM_STR);
                $stmh->bindValue(':is_purchase_customer', $is_purchase_customer, PDO::PARAM_STR);
                $stmh->bindValue(':is_other_customer', $is_other_customer, PDO::PARAM_STR);
                $stmh->bindValue(':bank_name', empty($bank_name) ? null : $bank_name, PDO::PARAM_STR);
                $stmh->bindValue(':account_number', empty($account_number) ? null : $account_number, PDO::PARAM_STR);
                $stmh->bindValue(':account_holder', empty($account_holder) ? null : $account_holder, PDO::PARAM_STR);
                $stmh->bindValue(':last_modified_date', $last_modified_date_value, PDO::PARAM_STR);
                
                $stmh->execute();
                $customer_id = $existing['num'];
                
                // 담당자 정보 업데이트 또는 생성 (이메일이 있거나 전화번호가 있거나 담당자명이 있는 경우)
                if ($customer_id && (!empty($email) || !empty($mobile_number) || !empty($representative_name))) {
                    // 계산서 담당자 체크 (Q열의 Y/N 값)
                    $invoice_contact = (!empty($is_invoice_contact) && (strtoupper($is_invoice_contact) === 'Y' || $is_invoice_contact === '1' || strtolower($is_invoice_contact) === '예' || strtolower($is_invoice_contact) === 'yes')) ? 'Y' : 'N';
                    
                    // 기존 담당자 정보 확인
                    $contactCheckSql = "SELECT num FROM {$DB}.customer_contact WHERE customer_id = :customer_id AND is_deleted = 'N' LIMIT 1";
                    $contactCheckStmt = $pdo->prepare($contactCheckSql);
                    $contactCheckStmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
                    $contactCheckStmt->execute();
                    $existingContact = $contactCheckStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($existingContact) {
                        // 기존 담당자 정보 업데이트
                        $contactUpdateSql = "UPDATE {$DB}.customer_contact SET 
                            contact_email = :contact_email,
                            contact_phone = :contact_phone,
                            contact_name = COALESCE(:contact_name, contact_name),
                            is_invoice_contact = :is_invoice_contact,
                            position_department = :position_department
                            WHERE num = :num";
                        $contactUpdateStmt = $pdo->prepare($contactUpdateSql);
                        $contactUpdateStmt->bindValue(':num', $existingContact['num'], PDO::PARAM_INT);
                        $contactUpdateStmt->bindValue(':contact_email', empty($email) ? null : $email, PDO::PARAM_STR);
                        $contactUpdateStmt->bindValue(':contact_phone', empty($mobile_number) ? null : $mobile_number, PDO::PARAM_STR); // N열 전화번호
                        $contactUpdateStmt->bindValue(':contact_name', empty($representative_name) ? null : $representative_name, PDO::PARAM_STR);
                        $contactUpdateStmt->bindValue(':is_invoice_contact', $invoice_contact, PDO::PARAM_STR);
                        $contactUpdateStmt->bindValue(':position_department', empty($position_department) ? null : $position_department, PDO::PARAM_STR); // P열 직급/부서
                        $contactUpdateStmt->execute();
                    } else {
                        // 새 담당자 정보 생성
                        $contactSql = "INSERT INTO {$DB}.customer_contact (
                            customer_id, contact_name, contact_email, contact_phone, is_invoice_contact, position_department, is_deleted
                        ) VALUES (
                            :customer_id, :contact_name, :contact_email, :contact_phone, :is_invoice_contact, :position_department, 'N'
                        )";
                        $contactStmt = $pdo->prepare($contactSql);
                        $contactStmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
                        $contactStmt->bindValue(':contact_name', !empty($representative_name) ? $representative_name : $company_name, PDO::PARAM_STR);
                        $contactStmt->bindValue(':contact_email', empty($email) ? null : $email, PDO::PARAM_STR);
                        $contactStmt->bindValue(':contact_phone', empty($mobile_number) ? null : $mobile_number, PDO::PARAM_STR); // N열 전화번호
                        $contactStmt->bindValue(':is_invoice_contact', $invoice_contact, PDO::PARAM_STR);
                        $contactStmt->bindValue(':position_department', empty($position_department) ? null : $position_department, PDO::PARAM_STR); // P열 직급/부서
                        $contactStmt->execute();
                    }
                }
                
                $pdo->commit();
                
                $success_count++;
                echo "<div class='alert alert-success'>행 {$i} ({$company_name}) - 업데이트 성공</div>";
            } else {
                // 새 거래처 삽입
                // last_modified_date는 등록일과 동일하게 설정 (등록일이 없으면 현재 시간)
                // 등록일이 DATE 형식이면 TIMESTAMP 형식으로 변환 (시간 부분 추가)
                if (!empty($registration_date)) {
                    // DATE 형식(YYYY-MM-DD)이면 시간 부분 추가
                    if (strlen($registration_date) == 10) {
                        $last_modified_date_value = $registration_date . ' 00:00:00';
                    } else {
                        $last_modified_date_value = $registration_date;
                    }
                } else {
                    $last_modified_date_value = date('Y-m-d H:i:s');
                }
                
                $sql = "INSERT INTO {$DB}.customer (
                    classification, trade_name, company_name, registration_number, representative_name,
                    phone_number, mobile_number, fax_number, business_type, business_category,
                    remarks, address, business_registration_number, registration_date,
                    is_sales_customer, is_purchase_customer, is_other_customer,
                    bank_name, account_number, account_holder, last_modified_date, is_deleted
                ) VALUES (
                    :classification, :trade_name, :company_name, :registration_number, :representative_name,
                    :phone_number, :mobile_number, :fax_number, :business_type, :business_category,
                    :remarks, :address, :business_registration_number, :registration_date,
                    :is_sales_customer, :is_purchase_customer, :is_other_customer,
                    :bank_name, :account_number, :account_holder, :last_modified_date, 'N'
                )";
                
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':classification', $classification, PDO::PARAM_STR);
                $stmh->bindValue(':trade_name', empty($trade_name) ? null : $trade_name, PDO::PARAM_STR);
                $stmh->bindValue(':company_name', $company_name, PDO::PARAM_STR);
                $stmh->bindValue(':registration_number', empty($registration_number) ? null : $registration_number, PDO::PARAM_STR);
                $stmh->bindValue(':representative_name', empty($representative_name) ? null : $representative_name, PDO::PARAM_STR);
                $stmh->bindValue(':phone_number', empty($phone_number) ? null : $phone_number, PDO::PARAM_STR);
                $stmh->bindValue(':mobile_number', empty($mobile_number) ? null : $mobile_number, PDO::PARAM_STR);
                $stmh->bindValue(':fax_number', empty($fax_number) ? null : $fax_number, PDO::PARAM_STR);
                $stmh->bindValue(':business_type', empty($business_type) ? null : $business_type, PDO::PARAM_STR);
                $stmh->bindValue(':business_category', empty($business_category) ? null : $business_category, PDO::PARAM_STR);
                $stmh->bindValue(':remarks', empty($remarks) ? null : $remarks, PDO::PARAM_STR);
                $stmh->bindValue(':address', empty($address) ? null : $address, PDO::PARAM_STR);
                $stmh->bindValue(':business_registration_number', empty($business_registration_number) ? null : $business_registration_number, PDO::PARAM_STR);
                $stmh->bindValue(':registration_date', empty($registration_date) ? null : $registration_date, PDO::PARAM_STR);
                $stmh->bindValue(':is_sales_customer', $is_sales_customer, PDO::PARAM_STR);
                $stmh->bindValue(':is_purchase_customer', $is_purchase_customer, PDO::PARAM_STR);
                $stmh->bindValue(':is_other_customer', $is_other_customer, PDO::PARAM_STR);
                $stmh->bindValue(':bank_name', empty($bank_name) ? null : $bank_name, PDO::PARAM_STR);
                $stmh->bindValue(':account_number', empty($account_number) ? null : $account_number, PDO::PARAM_STR);
                $stmh->bindValue(':account_holder', empty($account_holder) ? null : $account_holder, PDO::PARAM_STR);
                $stmh->bindValue(':last_modified_date', $last_modified_date_value, PDO::PARAM_STR);
                
                $stmh->execute();
                $customer_id = $pdo->lastInsertId();
                
                // 담당자 정보 생성 (이메일이 있거나 전화번호가 있거나 담당자명이 있는 경우)
                if ($customer_id && (!empty($email) || !empty($mobile_number) || !empty($representative_name))) {
                    // 계산서 담당자 체크 (Q열의 Y/N 값)
                    $invoice_contact = (!empty($is_invoice_contact) && (strtoupper($is_invoice_contact) === 'Y' || $is_invoice_contact === '1' || strtolower($is_invoice_contact) === '예' || strtolower($is_invoice_contact) === 'yes')) ? 'Y' : 'N';
                    
                    $contactSql = "INSERT INTO {$DB}.customer_contact (
                        customer_id, contact_name, contact_email, contact_phone, is_invoice_contact, position_department, is_deleted
                    ) VALUES (
                        :customer_id, :contact_name, :contact_email, :contact_phone, :is_invoice_contact, :position_department, 'N'
                    )";
                    $contactStmt = $pdo->prepare($contactSql);
                    $contactStmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
                    $contactStmt->bindValue(':contact_name', !empty($representative_name) ? $representative_name : $company_name, PDO::PARAM_STR);
                    $contactStmt->bindValue(':contact_email', empty($email) ? null : $email, PDO::PARAM_STR);
                    $contactStmt->bindValue(':contact_phone', empty($mobile_number) ? null : $mobile_number, PDO::PARAM_STR); // N열 전화번호
                    $contactStmt->bindValue(':is_invoice_contact', $invoice_contact, PDO::PARAM_STR);
                    $contactStmt->bindValue(':position_department', empty($position_department) ? null : $position_department, PDO::PARAM_STR); // P열 직급/부서
                    $contactStmt->execute();
                }
                
                $pdo->commit();
                
                $success_count++;
                echo "<div class='alert alert-success'>행 {$i} ({$company_name}) - 등록 성공</div>";
            }
        } catch (PDOException $ex) {
            $pdo->rollBack();
            error_log("엑셀 데이터 처리 오류 (행: {$i}): " . $ex->getMessage());
            echo "<div class='alert alert-danger'>행 {$i} ({$company_name}) - 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
            $error_count++;
        }
    }
    
    echo "<div class='alert alert-info'><strong>처리 완료:</strong> 성공 {$success_count}건, 실패 {$error_count}건, 건너뜀 {$skip_count}건</div>";
    echo "<div class='alert alert-success'><a href='index.php' class='btn btn-primary'>거래처 목록으로 돌아가기</a></div>";
    
} catch (Exception $ex) {
    error_log("엑셀 파일 읽기 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>엑셀파일을 읽는 도중 오류가 발생하였습니다.<br>" . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>거래처 Excel 임포트</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    
    <style>
        body {
            padding: 20px;
            background-color: #f5f5f5;
        }
        .alert {
            margin: 5px 0;
        }
        .container {
            max-width: 1200px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="bi bi-file-earmark-excel"></i> 거래처 Excel 파일 임포트</h2>
    <hr>
    
    <div class="alert alert-warning">
        <strong>참고:</strong> Excel 파일의 첫 번째 행은 헤더로 간주되며, 2번째 행부터 데이터를 읽습니다.<br>
        컬럼 순서: A=구분, B=거래처명, C=상호, D=등록번호, E=대표자, F=전화번호, G=휴대폰, H=FAX, I=업태, J=종목, K=적요, L=주소, M=사업자번호, N=등록일, O=매출, P=매입, Q=기타, R=은행, S=계좌번호, T=예금주
    </div>
    
    <!-- 결과는 PHP 코드에서 출력됨 -->
    
</div>

</body>
</html>

