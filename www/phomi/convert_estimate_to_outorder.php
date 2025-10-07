<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

try {
    $estimate_num = $_POST['estimate_num'] ?? null;
    
    if (!$estimate_num) {
        throw new Exception('견적서 번호가 제공되지 않았습니다.');
    }
    
    // 견적서 데이터 가져오기
    $sql = "SELECT * FROM {$DB}.phomi_estimate WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $estimate_num, PDO::PARAM_INT);
    $stmt->execute();
    $estimate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$estimate) {
        throw new Exception('해당 견적서를 찾을 수 없습니다.');
    }
    
    // 견적서의 아이템 데이터 파싱
    $estimate_items = json_decode($estimate['items'], true) ?? [];
    
    // 기타비용 데이터 파싱
    $other_costs = json_decode($estimate['other_costs'], true) ?? [];
    
    // 시공비 포함 여부 확인
    $construction_included = '미포함';
    foreach ($other_costs as $cost) {
        if (isset($cost['item']) && strpos($cost['item'], '시공비') !== false) {
            $construction_included = '포함';
            break;
        }
    }
    
    // 출고증용 데이터로 변환
    $outorder_data = [
        'out_date' => date('Y-m-d'), // 오늘 날짜
        'customer' => $estimate['recipient'] ?? '', // 수신처를 발주처로
        'manager' => $estimate['signer'] ?? '', // 담당자
        'address' => $estimate['site_name'] ?? '', // 현장명을 주소로
        'contact' => '010-3784-5438', // 기본 연락처
        'dispatch_type' => '포함', // 기본값
        'area_sqm' => '', // 자동 계산될 예정
        'construction_done' => $construction_included, // 시공비 포함 여부에 따라 설정
        'note' => $estimate['notices'] ?? '', // 주요안건을 비고로 전달
        'items' => []
    ];
    
    // 아이템 데이터 변환 - 코드, 수량, 비고만 전달
    foreach ($estimate_items as $item) {
        $outorder_item = [
            'code' => $item['prodcode'] ?? '', // 제품 코드
            'quantity' => $item['quantity'] ?? 0, // 수량
            'remarks' => $item['note'] ?? '' // 비고
        ];
        
        $outorder_data['items'][] = $outorder_item;
    }
    
    // 자재 예상 면적 계산
    $total_area = 0;
    foreach ($estimate_items as $item) {
        $specification = $item['specification'] ?? '';
        $quantity = floatval($item['quantity'] ?? 0);
        
        if ($specification && $quantity > 0) {
            $item_area = 0;
            
            // 규격에서 면적 계산 (mm²를 m²로 변환)
            if (strpos($specification, '*') !== false) {
                $size_parts = explode('*', $specification);
                if (count($size_parts) >= 2) {
                    $width = floatval($size_parts[0]) ?: 0;
                    $height = floatval($size_parts[1]) ?: 0;
                    $item_area = ($width * $height) / 1000000; // mm²를 m²로 변환
                }
            } elseif (strpos($specification, '×') !== false) {
                $size_parts = explode('×', $specification);
                if (count($size_parts) >= 2) {
                    $width = floatval($size_parts[0]) ?: 0;
                    $height = floatval($size_parts[1]) ?: 0;
                    $item_area = ($width * $height) / 1000000; // mm²를 m²로 변환
                }
            } else {
                // 단일 숫자인 경우 (가정: 정사각형)
                $single_size = floatval($specification) ?: 0;
                $item_area = ($single_size * $single_size) / 1000000; // mm²를 m²로 변환
            }
            
            $total_area += ($item_area * $quantity);
        }
    }
    
    $outorder_data['area_sqm'] = number_format($total_area, 2);
    
    echo json_encode([
        'success' => true,
        'data' => $outorder_data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 