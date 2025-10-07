<?php
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json; charset=utf-8');

$pdo = db_connect();

try {
    $mode = $_POST['mode'] ?? '';
    $tablename = $_POST['tablename'] ?? 'phomi_order';
    $num = $_POST['num'] ?? '';
    
    // 기본 데이터 수집
    $order_date = $_POST['order_date'] ?? date('Y-m-d');
    $recipient = $_POST['recipient'] ?? '';
    $division = $_POST['division'] ?? '';
    $site_name = $_POST['site_name'] ?? '';
    $signed_by = $_POST['signed_by'] ?? '소현철';
    $payment_account = $_POST['payment_account'] ?? '';
    
    // 작성자 정보 추가
    $author = $_POST['author'] ?? '';
    $author_id = $_POST['author_id'] ?? '';
    
    // 수주 관련 추가 필드들
    $order_confirm_date = $_POST['order_confirm_date'] ?? null;
    $delivery_due_date = $_POST['delivery_due_date'] ?? null;
    $delivery_date = $_POST['delivery_date'] ?? null;
    $order_close_date = $_POST['order_close_date'] ?? null;
    
    // 회계 처리 날짜
    $payment_date_head = $_POST['payment_date_head'] ?? null;
    $payment_date_dealer = $_POST['payment_date_dealer'] ?? null;
    $tax_invoice_date = $_POST['tax_invoice_date'] ?? null;
    $deposit_date = $_POST['deposit_date'] ?? null;
    
    // 회계 금액 정보
    $purchase_unit_price = $_POST['purchase_unit_price'] ?? 0; // 본사 매입 단가
    $purchase_total = $_POST['purchase_total'] ?? 0; // 본사 매입 총액
    $head_balance = $_POST['head_balance'] ?? 0; // 헤드 잔액
    $dealer_unit_price = $_POST['dealer_unit_price'] ?? 0; // 딜러 매입 단가
    $dealer_amount = $_POST['dealer_amount'] ?? 0; // 딜러 매입 총액
    $dealer_total = $_POST['dealer_total'] ?? 0; // 딜러 매입 총액
    $dealer_fee = $_POST['dealer_fee'] ?? 0; // 딜러 수수료
    $company_unit_price = $_POST['company_unit_price'] ?? 0; // 회사 매입 단가
    $company_amount = $_POST['company_amount'] ?? 0; // 회사 매입 총액
    $tax_invoice_amount = $_POST['tax_invoice_amount'] ?? 0; // 세액 발행 총액
    $tax_diff = $_POST['tax_diff'] ?? 0; // 계산서 차액
    $is_paid = $_POST['is_paid'] ?? ''; // 입금 여부
    $note = $_POST['note'] ?? ''; // 비고
    $recipient_name = $_POST['recipient_name'] ?? ''; // 물건 받는분
    $recipient_phone = $_POST['recipient_phone'] ?? ''; // 물건 받는분 전화번호
    
    // 체크박스 상태 변수
    $exclude_construction_cost = $_POST['exclude_construction_cost'] ?? '0';
    $exclude_molding = $_POST['exclude_molding'] ?? '0';
    $etc_autocheck = $_POST['etc_autocheck'] ?? '0';

    // 합계금액 VAT포함, 미포함
    $total_inc_vat = $_POST['total_inc_vat'] ?? 0;
    $total_ex_vat = $_POST['total_ex_vat'] ?? 0;

    // 견적서 번호
    $estimate_num = $_POST['estimate_num'] ?? '';
    
    // JSON 데이터 처리
    $items = [];
    $other_costs = [];
    $discount_items = [];
    $discount_other_costs = [];
    
    // 상품 데이터 수집
    if(isset($_POST['items']) && is_array($_POST['items'])) {
        foreach($_POST['items'] as $item) {
            if(!empty($item['product_code'])) {
                $items[] = [
                    'product_code' => $item['product_code'],
                    'specification' => $item['specification'] ?? '',
                    'size' => $item['size'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'area' => $item['area'] ?? '',
                    'unit_price' => str_replace(',', '', $item['unit_price'] ?? 0),
                    'supply_amount' => str_replace(',', '', $item['supply_amount'] ?? 0),
                    'tax_amount' => str_replace(',', '', $item['tax_amount'] ?? 0),
                    'remarks' => $item['remarks'] ?? ''
                ];
            }
        }
    }
    
    // 기타비용 데이터 수집
    if(isset($_POST['other_costs']) && is_array($_POST['other_costs'])) {
        foreach($_POST['other_costs'] as $cost) {
            if(!empty($cost['category']) || !empty($cost['item'])) {
                $other_costs[] = [
                    'category' => $cost['category'] ?? '',
                    'item' => $cost['item'] ?? '',
                    'unit' => $cost['unit'] ?? '',
                    'quantity' => $cost['quantity'] ?? 0,
                    'unit_price' => str_replace(',', '', $cost['unit_price'] ?? 0),
                    'supply_amount' => str_replace(',', '', $cost['supply_amount'] ?? 0),
                    'tax_amount' => str_replace(',', '', $cost['tax_amount'] ?? 0),
                    'remarks' => $cost['remarks'] ?? ''
                ];
            }
        }
    }
    
    // 할인 상품 데이터 수집
    if(isset($_POST['discount_items']) && is_array($_POST['discount_items'])) {
        foreach($_POST['discount_items'] as $item) {
            if(!empty($item['product_code'])) {
                $discount_items[] = [
                    'product_code' => $item['product_code'],
                    'code_string' => $item['code_string'] ?? '',
                    'specification' => $item['specification'] ?? '',
                    'size' => $item['size'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'area' => $item['area'] ?? '',
                    'unit_price' => str_replace(',', '', $item['unit_price'] ?? 0),
                    'supply_amount' => str_replace(['₩', ','], '', $item['supply_amount'] ?? 0),
                    'tax_amount' => str_replace(['₩', ','], '', $item['tax_amount'] ?? 0),
                    'remarks' => $item['remarks'] ?? ''
                ];
            }
        }
    }
    
    // 할인 기타비용 데이터 수집
    if(isset($_POST['discount_other_costs']) && is_array($_POST['discount_other_costs'])) {
        foreach($_POST['discount_other_costs'] as $cost) {
            if(!empty($cost['category']) || !empty($cost['item'])) {
                $discount_other_costs[] = [
                    'category' => $cost['category'] ?? '',
                    'item' => $cost['item'] ?? '',
                    'unit' => $cost['unit'] ?? '',
                    'quantity' => $cost['quantity'] ?? 0,
                    'unit_price' => str_replace(',', '', $cost['unit_price'] ?? 0),
                    'supply_amount' => str_replace(['₩', ','], '', $cost['supply_amount'] ?? 0),
                    'tax_amount' => str_replace(['₩', ','], '', $cost['tax_amount'] ?? 0),
                    'remarks' => $cost['remarks'] ?? ''
                ];
            }
        }
    }

    
    $items_json = json_encode($items, JSON_UNESCAPED_UNICODE);
    $other_costs_json = json_encode($other_costs, JSON_UNESCAPED_UNICODE);
    $discount_items_json = json_encode($discount_items, JSON_UNESCAPED_UNICODE);
    $discount_other_costs_json = json_encode($discount_other_costs, JSON_UNESCAPED_UNICODE);
    
    switch($mode) {
        case 'insert':
            // 새 수주서 등록
            $sql = "INSERT INTO {$DB}.phomi_order (
                order_date, recipient, division, site_name, signed_by, payment_account,
                order_confirm_date, delivery_due_date, delivery_date, order_close_date,
                payment_date_head, payment_date_dealer, tax_invoice_date, deposit_date,
                purchase_unit_price, purchase_total, head_balance,
                dealer_unit_price, dealer_amount, dealer_total, dealer_fee,
                company_unit_price, company_amount, tax_invoice_amount, tax_diff,
                is_paid, note, items, other_costs, discount_items, discount_other_costs, exclude_construction_cost,
                exclude_molding, etc_autocheck, total_inc_vat, total_ex_vat, author, author_id, estimate_num, recipient_name, recipient_phone, createdAt, updatedAt, is_deleted 
            ) VALUES (
                :order_date, :recipient, :division, :site_name, :signed_by, :payment_account,
                :order_confirm_date, :delivery_due_date, :delivery_date, :order_close_date,
                :payment_date_head, :payment_date_dealer, :tax_invoice_date, :deposit_date,
                :purchase_unit_price, :purchase_total, :head_balance,
                :dealer_unit_price, :dealer_amount, :dealer_total, :dealer_fee,
                :company_unit_price, :company_amount, :tax_invoice_amount, :tax_diff,
                :is_paid, :note, :items, :other_costs, :discount_items, :discount_other_costs, :exclude_construction_cost,
                :exclude_molding, :etc_autocheck, :total_inc_vat, :total_ex_vat, :author, :author_id, :estimate_num, :recipient_name, :recipient_phone, NOW(), NOW(), 'N'
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':order_date', $order_date);
            $stmt->bindParam(':recipient', $recipient);
            $stmt->bindParam(':division', $division);
            $stmt->bindParam(':site_name', $site_name);
            $stmt->bindParam(':signed_by', $signed_by);
            $stmt->bindParam(':payment_account', $payment_account);
            $stmt->bindParam(':order_confirm_date', $order_confirm_date);
            $stmt->bindParam(':delivery_due_date', $delivery_due_date);
            $stmt->bindParam(':delivery_date', $delivery_date);
            $stmt->bindParam(':order_close_date', $order_close_date);
            $stmt->bindParam(':payment_date_head', $payment_date_head);
            $stmt->bindParam(':payment_date_dealer', $payment_date_dealer);
            $stmt->bindParam(':tax_invoice_date', $tax_invoice_date);
            $stmt->bindParam(':deposit_date', $deposit_date);
            $stmt->bindParam(':purchase_unit_price', $purchase_unit_price);
            $stmt->bindParam(':purchase_total', $purchase_total);
            $stmt->bindParam(':head_balance', $head_balance);
            $stmt->bindParam(':dealer_unit_price', $dealer_unit_price);
            $stmt->bindParam(':dealer_amount', $dealer_amount);
            $stmt->bindParam(':dealer_total', $dealer_total);
            $stmt->bindParam(':dealer_fee', $dealer_fee);
            $stmt->bindParam(':company_unit_price', $company_unit_price);
            $stmt->bindParam(':company_amount', $company_amount);
            $stmt->bindParam(':tax_invoice_amount', $tax_invoice_amount);
            $stmt->bindParam(':tax_diff', $tax_diff);
            $stmt->bindParam(':is_paid', $is_paid);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':items', $items_json);
            $stmt->bindParam(':other_costs', $other_costs_json);
            $stmt->bindParam(':discount_items', $discount_items_json);
            $stmt->bindParam(':discount_other_costs', $discount_other_costs_json);
            $stmt->bindParam(':exclude_construction_cost', $exclude_construction_cost);
            $stmt->bindParam(':exclude_molding', $exclude_molding);
            $stmt->bindParam(':etc_autocheck', $etc_autocheck);
            $stmt->bindParam(':total_inc_vat', $total_inc_vat);
            $stmt->bindParam(':total_ex_vat', $total_ex_vat);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':author_id', $author_id);
            $stmt->bindParam(':estimate_num', $estimate_num);
            $stmt->bindParam(':recipient_name', $recipient_name);
            $stmt->bindParam(':recipient_phone', $recipient_phone);
            $stmt->execute();
            $new_num = $pdo->lastInsertId();
            
            echo json_encode([
                'result' => 'success',
                'success' => true,
                'message' => '수주서가 성공적으로 등록되었습니다.',
                'num' => $new_num
            ]);
            break;
            
        case 'modify':
            // 수주서 수정
            if(empty($num)) {
                throw new Exception('수정할 수주서 번호가 없습니다.');
            }
            
            $sql = "UPDATE {$DB}.phomi_order SET 
                order_date = :order_date,
                recipient = :recipient,
                division = :division,
                site_name = :site_name,
                signed_by = :signed_by,
                payment_account = :payment_account,
                order_confirm_date = :order_confirm_date,
                delivery_due_date = :delivery_due_date,
                delivery_date = :delivery_date,
                order_close_date = :order_close_date,
                payment_date_head = :payment_date_head,
                payment_date_dealer = :payment_date_dealer,
                tax_invoice_date = :tax_invoice_date,
                deposit_date = :deposit_date,
                purchase_unit_price = :purchase_unit_price,
                purchase_total = :purchase_total,
                head_balance = :head_balance,
                dealer_unit_price = :dealer_unit_price,
                dealer_amount = :dealer_amount,
                dealer_total = :dealer_total,
                dealer_fee = :dealer_fee,
                company_unit_price = :company_unit_price,
                company_amount = :company_amount,
                tax_invoice_amount = :tax_invoice_amount,
                tax_diff = :tax_diff,
                is_paid = :is_paid,
                note = :note,
                items = :items,
                other_costs = :other_costs,
                discount_items = :discount_items,
                discount_other_costs = :discount_other_costs,
                exclude_construction_cost = :exclude_construction_cost,
                exclude_molding = :exclude_molding,
                etc_autocheck = :etc_autocheck,
                total_inc_vat = :total_inc_vat,
                total_ex_vat = :total_ex_vat,
                author = :author,
                author_id = :author_id,
                estimate_num = :estimate_num,
                recipient_name = :recipient_name,
                recipient_phone = :recipient_phone,
                updatedAt = NOW()
                WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->bindParam(':order_date', $order_date);
            $stmt->bindParam(':recipient', $recipient);
            $stmt->bindParam(':division', $division);
            $stmt->bindParam(':site_name', $site_name);
            $stmt->bindParam(':signed_by', $signed_by);
            $stmt->bindParam(':payment_account', $payment_account);
            $stmt->bindParam(':order_confirm_date', $order_confirm_date);
            $stmt->bindParam(':delivery_due_date', $delivery_due_date);
            $stmt->bindParam(':delivery_date', $delivery_date);
            $stmt->bindParam(':order_close_date', $order_close_date);
            $stmt->bindParam(':payment_date_head', $payment_date_head);
            $stmt->bindParam(':payment_date_dealer', $payment_date_dealer);
            $stmt->bindParam(':tax_invoice_date', $tax_invoice_date);
            $stmt->bindParam(':deposit_date', $deposit_date);
            $stmt->bindParam(':purchase_unit_price', $purchase_unit_price);
            $stmt->bindParam(':purchase_total', $purchase_total);
            $stmt->bindParam(':head_balance', $head_balance);
            $stmt->bindParam(':dealer_unit_price', $dealer_unit_price);
            $stmt->bindParam(':dealer_amount', $dealer_amount);
            $stmt->bindParam(':dealer_total', $dealer_total);
            $stmt->bindParam(':dealer_fee', $dealer_fee);
            $stmt->bindParam(':company_unit_price', $company_unit_price);
            $stmt->bindParam(':company_amount', $company_amount);
            $stmt->bindParam(':tax_invoice_amount', $tax_invoice_amount);
            $stmt->bindParam(':tax_diff', $tax_diff);
            $stmt->bindParam(':is_paid', $is_paid);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':items', $items_json);
            $stmt->bindParam(':other_costs', $other_costs_json);
            $stmt->bindParam(':discount_items', $discount_items_json);
            $stmt->bindParam(':discount_other_costs', $discount_other_costs_json);
            $stmt->bindParam(':exclude_construction_cost', $exclude_construction_cost);
            $stmt->bindParam(':exclude_molding', $exclude_molding);
            $stmt->bindParam(':etc_autocheck', $etc_autocheck);
            $stmt->bindParam(':total_inc_vat', $total_inc_vat);
            $stmt->bindParam(':total_ex_vat', $total_ex_vat);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':author_id', $author_id);
            $stmt->bindParam(':estimate_num', $estimate_num);
            $stmt->bindParam(':recipient_name', $recipient_name);
            $stmt->bindParam(':recipient_phone', $recipient_phone);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'message' => '수주서가 성공적으로 수정되었습니다.',
                    'num' => $num
                ]);
            } else {
                echo json_encode([
                    'result' => 'error',
                    'success' => false,
                    'message' => '수정할 수주서를 찾을 수 없습니다.'
                ]);
            }
            break;
            
        case 'delete':
            // 수주서 삭제 (논리 삭제)
            if(empty($num)) {
                throw new Exception('삭제할 수주서 번호가 없습니다.');
            }
            
            $sql = "UPDATE {$DB}.phomi_order SET is_deleted = 'Y', updatedAt = NOW() WHERE num = :num";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'result' => 'success',
                    'success' => true,
                    'message' => '수주서가 성공적으로 삭제되었습니다.'
                ]);
            } else {
                echo json_encode([
                    'result' => 'error',
                    'success' => false,
                    'message' => '삭제할 수주서를 찾을 수 없습니다.'
                ]);
            }
            break;
            
        case 'copy':
            // 수주서 복사 (새로운 수주서로 저장)
            $sql = "INSERT INTO {$DB}.phomi_order (
                order_date, recipient, division, site_name, signed_by, payment_account,
                order_confirm_date, delivery_due_date, delivery_date, order_close_date,
                payment_date_head, payment_date_dealer, tax_invoice_date, deposit_date,
                purchase_unit_price, purchase_total, head_balance,
                dealer_unit_price, dealer_amount, dealer_total, dealer_fee,
                company_unit_price, company_amount, tax_invoice_amount, tax_diff,
                is_paid, note, items, other_costs, discount_items, discount_other_costs, exclude_construction_cost, 
                exclude_molding, etc_autocheck, total_inc_vat, total_ex_vat, author, author_id, estimate_num, recipient_name, recipient_phone, createdAt, updatedAt, is_deleted
            ) VALUES (
                :order_date, :recipient, :division, :site_name, :signed_by, :payment_account,
                :order_confirm_date, :delivery_due_date, :delivery_date, :order_close_date,
                :payment_date_head, :payment_date_dealer, :tax_invoice_date, :deposit_date,
                :purchase_unit_price, :purchase_total, :head_balance,
                :dealer_unit_price, :dealer_amount, :dealer_total, :dealer_fee,
                :company_unit_price, :company_amount, :tax_invoice_amount, :tax_diff,
                :is_paid, :note, :items, :other_costs, :discount_items, :discount_other_costs, :exclude_construction_cost,
                :exclude_molding, :etc_autocheck, :total_inc_vat, :total_ex_vat, :author, :author_id, :estimate_num, :recipient_name, :recipient_phone, NOW(), NOW(), 'N'
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':order_date', $order_date);
            $stmt->bindParam(':recipient', $recipient);
            $stmt->bindParam(':division', $division);
            $stmt->bindParam(':site_name', $site_name);
            $stmt->bindParam(':signed_by', $signed_by);
            $stmt->bindParam(':payment_account', $payment_account);
            $stmt->bindParam(':order_confirm_date', $order_confirm_date);
            $stmt->bindParam(':delivery_due_date', $delivery_due_date);
            $stmt->bindParam(':delivery_date', $delivery_date);
            $stmt->bindParam(':order_close_date', $order_close_date);
            $stmt->bindParam(':payment_date_head', $payment_date_head);
            $stmt->bindParam(':payment_date_dealer', $payment_date_dealer);
            $stmt->bindParam(':tax_invoice_date', $tax_invoice_date);
            $stmt->bindParam(':deposit_date', $deposit_date);
            $stmt->bindParam(':purchase_unit_price', $purchase_unit_price);
            $stmt->bindParam(':purchase_total', $purchase_total);
            $stmt->bindParam(':head_balance', $head_balance);
            $stmt->bindParam(':dealer_unit_price', $dealer_unit_price);
            $stmt->bindParam(':dealer_amount', $dealer_amount);
            $stmt->bindParam(':dealer_total', $dealer_total);
            $stmt->bindParam(':dealer_fee', $dealer_fee);
            $stmt->bindParam(':company_unit_price', $company_unit_price);
            $stmt->bindParam(':company_amount', $company_amount);
            $stmt->bindParam(':tax_invoice_amount', $tax_invoice_amount);
            $stmt->bindParam(':tax_diff', $tax_diff);
            $stmt->bindParam(':is_paid', $is_paid);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':items', $items_json);
            $stmt->bindParam(':other_costs', $other_costs_json);
            $stmt->bindParam(':discount_items', $discount_items_json);
            $stmt->bindParam(':discount_other_costs', $discount_other_costs_json);
            $stmt->bindParam(':exclude_construction_cost', $exclude_construction_cost);
            $stmt->bindParam(':exclude_molding', $exclude_molding);
            $stmt->bindParam(':etc_autocheck', $etc_autocheck);
            $stmt->bindParam(':total_inc_vat', $total_inc_vat);
            $stmt->bindParam(':total_ex_vat', $total_ex_vat);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':author_id', $author_id);
            $stmt->bindParam(':estimate_num', $estimate_num);
            $stmt->bindParam(':recipient_name', $recipient_name);
            $stmt->bindParam(':recipient_phone', $recipient_phone);
            $stmt->execute();
            $new_num = $pdo->lastInsertId();
            
            echo json_encode([
                'result' => 'success',
                'success' => true,
                'message' => '수주서가 성공적으로 복사되었습니다.',
                'num' => $new_num
            ]);
            break;
            
        default:
            throw new Exception('잘못된 모드입니다.');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'result' => 'error',
        'success' => false,
        'message' => '오류가 발생했습니다: ' . $e->getMessage()
    ]);
}
?>
