<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 및 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 8) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => '권한이 없습니다.']);
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$mode = $_REQUEST['mode'] ?? '';
$item = $_REQUEST['item'] ?? '';
$spec = $_REQUEST['spec'] ?? '';
$company = $_REQUEST['company'] ?? '';

header('Content-Type: application/json; charset=utf-8');

if ($mode === 'exact') {
    // 정확한 매칭으로 재고 조회
    if (!$item || !$spec) {
        echo json_encode(['stock' => 0, 'message' => '필수 파라미터 누락']);
        exit;
    }

    try {
        // 입고(1) - 출고(2) 계산
        $sql = "SELECT 
                    SUM(CASE WHEN which = '1' THEN steelnum ELSE 0 END) as in_qty,
                    SUM(CASE WHEN which = '2' THEN steelnum ELSE 0 END) as out_qty
                FROM mirae8440.steel 
                WHERE item = :item AND spec = :spec";
        
        if ($company) {
            $sql .= " AND company = :company";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':item', $item);
        $stmt->bindValue(':spec', $spec);
        if ($company) {
            $stmt->bindValue(':company', $company);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $stock = ($row['in_qty'] ?? 0) - ($row['out_qty'] ?? 0);
        
        echo json_encode(['stock' => $stock]);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($mode === 'similar') {
    // 유사 재고 조회 (같은 품목의 다른 규격/업체 재고)
    if (!$item) {
        echo json_encode(['list' => [], 'message' => '품목 파라미터 누락']);
        exit;
    }

    try {
        // 품목이 포함된 모든 재고 조회 및 그룹화
        // 원래 로직은 item이 포함되는지 체크했음 (temptext.includes(a))
        // 여기서는 item이 정확히 일치하거나 포함되는 것을 찾도록 LIKE 사용 가능하지만,
        // 보통 '유사'는 같은 'item' 카테고리 내의 다른 spec을 의미하는 경우가 많음.
        // 기존 로직: if(temptext.includes(a)) -> temptext는 item.spec.company
        // 따라서 item 파라미터가 '철판'이면 '철판'이 들어간 모든 것을 찾음.
        
        $sql = "SELECT item, spec, company,
                    SUM(CASE WHEN which = '1' THEN steelnum ELSE -steelnum END) as stock
                FROM mirae8440.steel 
                WHERE item LIKE :item
                GROUP BY item, spec, company
                HAVING stock > 0
                ORDER BY item, spec, company";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':item', '%' . $item . '%');
        $stmt->execute();
        
        $list = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $list[] = [
                'text' => "{$row['item']} {$row['spec']} {$row['company']}",
                'stock' => $row['stock']
            ];
        }

        echo json_encode(['list' => $list]);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

} else {
    echo json_encode(['error' => '잘못된 모드입니다.']);
}
?>
