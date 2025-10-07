<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

header('Content-Type: application/json');

try {
    $pdo = db_connect();
    
    // 단가표에서 상품 목록 가져오기
    $product_sql = "SELECT prodcode, texture_eng, texture_kor, design_eng, design_kor, type, size, thickness, area, dist_price_per_m2, retail_price_per_m2 FROM mirae8440.phomi_unitprice ORDER BY prodcode ASC";
    $product_stmt = $pdo->prepare($product_sql);
    $product_stmt->execute();
    
    $products = [];
    while($product = $product_stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = [
            'prodcode' => $product['prodcode'],
            'texture_kor' => $product['texture_kor'],
            'design_kor' => $product['design_kor'],
            'texture_eng' => $product['texture_eng'],
            'design_eng' => $product['design_eng'],
            'type' => $product['type'],
            'size' => $product['size'],
            'thickness' => $product['thickness'],
            'area' => $product['area'],
            'dist_price_per_m2' => $product['dist_price_per_m2'],
            'retail_price_per_m2' => $product['retail_price_per_m2']
        ];
    }
    
    echo json_encode($products);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '상품 목록 조회 오류: ' . $e->getMessage()]);
}
?> 