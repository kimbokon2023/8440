<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// JSON 헤더 설정
header('Content-Type: application/json');

// 요청 파라미터 초기화
$item = $_POST['item'] ?? '';

// 검색 조건 변수 초기화
$searchspec = '';
$searchsupplier = '';
$default_condition = false;

// 철판 종류별 기본 조건 설정
if ($item == '304 HL') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '현진스텐';
    $default_condition = true;
} elseif ($item == '201 2B MR') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '윤스틸';
    $default_condition = true;
} elseif ($item == '201 HL' || $item == '201 VB') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '우성스틸';
    $default_condition = true;
} elseif ($item == '201 MR BEAD' || $item == '2B VB' || $item == 'VB') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '한산엘테크';
    $default_condition = true;
} elseif ($item == 'CR') {
    $searchspec = '1.2*1219*1950';
    $searchsupplier = '용민철강';
    $default_condition = true;
} elseif ($item == 'PO') {
    $searchspec = '1.2*1219*1950';
    $searchsupplier = '용민철강';
    $default_condition = true;
} elseif ($item == 'EGI') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '용민철강';
    $default_condition = true;
}

// 결과 변수 초기화
$unitprice = 0;
$data = array();

try {
    // 최근 24개월간의 데이터 조회
    for ($i = 23; $i >= 0; $i--) {
        $target_month = date('Y-m-01', strtotime("-$i months"));
        $next_month = date('Y-m-01', strtotime("$target_month +1 month"));
        
        $sql = "SELECT steel_item, spec, supplier, suppliercost, steelnum 
                FROM {$DB}.eworks 
                WHERE outdate >= :target_month 
                  AND outdate < :next_month 
                  AND eworks_item = '원자재구매' 
                  AND (is_deleted IS NULL OR is_deleted = '') ";
        
        if ($default_condition) {
            $sql .= " AND spec = :spec AND supplier LIKE :supplier";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':target_month', $target_month);
        $stmt->bindParam(':next_month', $next_month);
        
        if ($default_condition) {
            $stmt->bindValue(':spec', $searchspec);
            $stmt->bindValue(':supplier', "%$searchsupplier%");
        }
        
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $suppliercost = (int)str_replace(',', '', $row['suppliercost']);
            $spec_parts = explode('*', $row['spec']);
            
            // 배열 안전 접근
            $val1 = (float)preg_replace('/[^0-9.]/', '', isset($spec_parts[0]) ? $spec_parts[0] : '0');
            $val2 = (float)preg_replace('/[^0-9.]/', '', isset($spec_parts[1]) ? $spec_parts[1] : '0');
            $val3 = (float)preg_replace('/[^0-9.]/', '', isset($spec_parts[2]) ? $spec_parts[2] : '0');
            
            // 중량 계산 (밀도 7.93 적용)
            $weight = ($val1 * $val2 * $val3 * 7.93 * (int)$row['steelnum']) / 1000000;
            $weight = $weight > 0 ? $weight : 1;
            
            // kg당 단가 계산
            $unit_weight = floor($suppliercost / $weight);
            $month = substr($target_month, 0, 7);
            $data[$month] = $unit_weight;
        }
    }
    
    // 최근 값 추출 (가장 최신 월부터 역순으로 검색)
    if (!empty($data)) {
        krsort($data);
        foreach ($data as $price) {
            if ($price > 0) {
                $unitprice = $price;
                break;
            }
        }
    }
    
    // 성공 응답
    echo json_encode(
        array(
            "success" => true,
            "unitprice" => $unitprice
        ),
        JSON_UNESCAPED_UNICODE
    );
} catch (PDOException $ex) {
    // 에러 로깅
    error_log("단가 조회 오류 (item: {$item}): " . $ex->getMessage());
    
    // 에러 응답
    echo json_encode(
        array(
            "success" => false,
            "error" => "단가 조회 중 오류가 발생했습니다.",
            "unitprice" => 0
        ),
        JSON_UNESCAPED_UNICODE
    );
    exit;
}
?>
