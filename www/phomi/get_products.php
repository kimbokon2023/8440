<?php
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

header('Content-Type: application/json');

try {
    $product_sql = "SELECT prodcode, texture_eng, texture_kor, design_eng, design_kor, type, size, thickness, area, dist_price_per_m2, retail_price_per_m2 FROM mirae8440.phomi_unitprice ORDER BY prodcode ASC";
    $product_stmt = $pdo->prepare($product_sql);
    $product_stmt->execute();
    
    $products = array();
    while($product = $product_stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = array(
            'prodcode' => $product['prodcode'],
            'texture_eng' => $product['texture_eng'],
            'texture_kor' => $product['texture_kor'],
            'design_eng' => $product['design_eng'],
            'design_kor' => $product['design_kor'],
            'type' => $product['type'],
            'size' => $product['size'],
            'thickness' => $product['thickness'],
            'area' => $product['area'],
            'dist_price_per_m2' => $product['dist_price_per_m2'],
            'retail_price_per_m2' => $product['retail_price_per_m2']
        );
    }
    
    // 본드와 몰딩 코드 추가
    $products[] = array(
        'prodcode' => 'BOND',
        'texture_eng' => 'Bond',
        'texture_kor' => '본드',
        'design_eng' => '',
        'design_kor' => '',
        'type' => '',
        'size' => '',
        'thickness' => '',
        'area' => '',
        'dist_price_per_m2' => 0,
        'retail_price_per_m2' => 0
    );
    
    $products[] = array(
        'prodcode' => 'MOLDING',
        'texture_eng' => 'Molding',
        'texture_kor' => '몰딩',
        'design_eng' => '',
        'design_kor' => '',
        'type' => '',
        'size' => '',
        'thickness' => '',
        'area' => '',
        'dist_price_per_m2' => 0,
        'retail_price_per_m2' => 0
    );
    
    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Database error'));
}
?> 