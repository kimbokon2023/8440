<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 철강 재고 집계 API
 * 
 * 원자재별 입출고 집계 데이터를 JSON 형태로 반환
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// POST 요청 변수 초기화
$item = trim($_POST['item'] ?? '');
$spec = trim($_POST['spec'] ?? '');
$company = trim($_POST['company'] ?? '');

// 집계 배열 초기화
$sum_title = [];
$sum = [];
$company_arr = [];

// 기준목록 조회
try {
    $sql = "SELECT * FROM " . $DB . ".steelsource ORDER BY sortorder ASC, item ASC, spec ASC";
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $i = trim($row["item"] ?? '');
        $s = trim($row["spec"] ?? '');
        $c = trim($row["take"] ?? '');
        
        // 입력값이 있을 경우만 필터
        if (
            ($item && $item !== $i) ||
            ($spec && $spec !== $s) ||
            ($company && $company !== $c)
        ) continue;
        
        $sum_title[] = $i . $s . $c;
        $company_arr[] = $c;
    }
    
    $sum_title = array_unique($sum_title);
    sort($sum_title);
} catch (PDOException $e) {
    error_log("기준목록 조회 오류: " . $e->getMessage());
    echo json_encode([
        'error' => '기준목록 조회 중 오류가 발생했습니다.',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 입출고 집계
try {
    $sql = "SELECT * FROM " . $DB . ".steel ORDER BY outdate";
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $i = trim($row["item"] ?? '');
        $s = trim($row["spec"] ?? '');
        $c = trim($row["company"] ?? '');
        $which = $row["which"] ?? '';
        $num = (int)($row["steelnum"] ?? 0);
        
        $key = $i . $s . $c;
        foreach ($sum_title as $idx => $title) {
            if ($key === $title) {
                $sum[$idx] = ($sum[$idx] ?? 0) + ($which == '1' ? $num : -$num);
            }
        }
    }
} catch (PDOException $e) {
    error_log("입출고 집계 조회 오류: " . $e->getMessage());
    echo json_encode([
        'error' => '입출고 집계 조회 중 오류가 발생했습니다.',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 성공 응답
echo json_encode([
    'success' => true,
    'sum_title' => array_values($sum_title),
    'sum' => array_values($sum),
    'company_arr' => array_values($company_arr),
    'sumcount' => count($sum_title)
], JSON_UNESCAPED_UNICODE);
exit;

