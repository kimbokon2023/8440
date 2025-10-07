<?php
require_once(includePath('session.php'));  

$title_message = '포미스톤 수주'; 
$title_message_sub = '수 주 서 (포미스톤)' ; 
$tablename = 'phomi_order'; 
$item ='포미스톤 수주';   
$emailTitle ='수주서';   
$subTitle = '포미스톤 제품';
$payment_account = '중소기업은행 339-084210-01-012 ㈜ 미래기업';

?>
<?php include getDocumentRoot() . '/load_header.php'; ?> 

<title> <?=$title_message?>  </title>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
<link rel="stylesheet" href="css/style.css">
</head>		 
<body>

<?php
$pdo = db_connect();

// GET 파라미터 처리
$mode = $_REQUEST["mode"] ?? 'insert';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? 'phomi_order';

// JSON 데이터 파싱
$items = [];
$other_costs = [];
$discount_items = [];
$discount_other_costs = [];

// 복사 모드일 때는 num을 초기화 (새로운 수주서로 저장하기 위해)
if($mode == 'copy') {
    $original_num = $num; // 원본 num 보관
    $num = ''; // 새로운 수주서를 위해 num 초기화
}

// 견적서에서 전달된 estimate_data 처리
$estimate_data = null;
$estimate_data_error = null;

// POST와 GET 모두에서 estimate_data 확인
if (isset($_POST['estimate_data']) && !empty($_POST['estimate_data'])) {
    $estimate_data = json_decode($_POST['estimate_data'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $estimate_data_error = '견적서 데이터 파싱 오류: ' . json_last_error_msg();
        $estimate_data = null;
    }
} elseif (isset($_GET['estimate_data']) && !empty($_GET['estimate_data'])) {
    $estimate_data = json_decode($_GET['estimate_data'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $estimate_data_error = '견적서 데이터 파싱 오류: ' . json_last_error_msg();
        $estimate_data = null;
    }
}

// 견적서 데이터 유효성 검증
if ($estimate_data && !is_array($estimate_data)) {
    $estimate_data_error = '견적서 데이터 형식이 올바르지 않습니다.';
    $estimate_data = null;
}

$order_date = date('Y-m-d');

// 데이터 조회
$order_data = null;
if(($mode == 'view' || $mode == 'modify' || $mode == 'copy')) {
    $query_num = ($mode == 'copy') ? $original_num : $num;
    if(!empty($query_num)) {
        try {
            $sql = "SELECT * FROM {$DB}.phomi_order WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $query_num, PDO::PARAM_INT);
            $stmt->execute();
            $order_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // 기본값 설정
            $order_date = $order_data['order_date'] ?? date('Y-m-d');
            $recipient = $order_data['recipient'] ?? '';
            $division = $order_data['division'] ?? '';
            $site_name = $order_data['site_name'] ?? '';
            $signed_by = $order_data['signed_by'] ?? '소현철';
            $payment_account = $order_data['payment_account'] ?? '중소기업은행 339-084210-01-012 ㈜ 미래기업';

            // 작성자 정보 설정
            if(empty($order_data['author_id']) && $_SESSION["level"] < 6) {
                $author_id = $order_data['author_id'] ?? 'mirae';
                $author = $order_data['author'] ?? '소현철';  // 미래기업 직원일 경우는 사장님 이름이 표시되도록 함.
            }
            else
            {
                if(empty($order_data['author_id'])) {
                    $author_id = $_SESSION["userid"]; // 작성자 아이디
                    $author = $_SESSION["name"]; // 작성자 이름
                }
                else {
                    $author_id = $order_data['author_id']; // 작성자 아이디
                    $author = $order_data['author']; // 작성자 이름
                }
            }

            // 견적서 번호
            $estimate_num = $order_data['estimate_num'] ?? '';

            // 합계금액
            $total_supply = $order_data['total_supply'] ?? 0;
            $total_tax = $order_data['total_tax'] ?? 0;
            $total_ex_vat = $order_data['total_ex_vat'] ?? 0;
            $total_inc_vat = $order_data['total_inc_vat'] ?? 0;

            // 수주 관련 추가 필드들
            $order_confirm_date = $order_data['order_confirm_date'] ?? '';
            $delivery_due_date = $order_data['delivery_due_date'] ?? '';
            $delivery_date = $order_data['delivery_date'] ?? '';
            $order_close_date = $order_data['order_close_date'] ?? '';

            // 회계 처리 날짜
            $payment_date_head = $order_data['payment_date_head'] ?? '';
            $payment_date_dealer = $order_data['payment_date_dealer'] ?? '';
            $tax_invoice_date = $order_data['tax_invoice_date'] ?? '';
            $deposit_date = $order_data['deposit_date'] ?? '';

            // 회계 금액 정보
            $purchase_unit_price = $order_data['purchase_unit_price'] ?? 0;
            $purchase_total = $order_data['purchase_total'] ?? 0;
            $head_balance = $order_data['head_balance'] ?? 0;
            $dealer_unit_price = $order_data['dealer_unit_price'] ?? 0;
            $dealer_amount = $order_data['dealer_amount'] ?? 0;
            $dealer_total = $order_data['dealer_total'] ?? 0;
            $dealer_fee = $order_data['dealer_fee'] ?? 0;
            $company_unit_price = $order_data['company_unit_price'] ?? 0;
            $company_amount = $order_data['company_amount'] ?? 0;
            $tax_invoice_amount = $order_data['tax_invoice_amount'] ?? 0;
            $tax_diff = $order_data['tax_diff'] ?? 0;
            $is_paid = $order_data['is_paid'] ?? '';
            $note = $order_data['note'] ?? '';
            $recipient_name = $order_data['recipient_name'] ?? '';
            $recipient_phone = $order_data['recipient_phone'] ?? '';

            //할인 상품 내역
            $discount_items = $order_data['discount_items'] ?? [];
            $discount_other_costs = $order_data['discount_other_costs'] ?? [];

            // 체크박스 상태 변수
            $exclude_construction_cost = $order_data['exclude_construction_cost'] ?? '0';
            $exclude_molding = $order_data['exclude_molding'] ?? '0';
            $etc_autocheck = $order_data['etc_autocheck'] ?? '0';

            if($order_data) {
                if(!empty($order_data['items'])) {
                    $items = json_decode($order_data['items'], true) ?? [];
                }
                if(!empty($order_data['other_costs'])) {
                    $other_costs = json_decode($order_data['other_costs'], true) ?? [];
                }
                if(!empty($order_data['discount_items'])) {
                    $discount_items = json_decode($order_data['discount_items'], true) ?? [];
                }
                if(!empty($order_data['discount_other_costs'])) {
                    $discount_other_costs = json_decode($order_data['discount_other_costs'], true) ?? [];
                }
                // 시공비 제외 체크 및 몰딩 제외체크 가져오기
                $exclude_construction_cost = $order_data['exclude_construction_cost'] ?? '0';
                $exclude_molding = $order_data['exclude_molding'] ?? '0';
                $etc_autocheck = $order_data['etc_autocheck'] ?? '1';            
            }            

        } catch (PDOException $e) {
            echo "오류: " . $e->getMessage();
        }
    }
}
else {
    // insert 모드일 때 기본값 설정
    $order_date = date('Y-m-d');
    $recipient = '';
    $division = '';
    $site_name = '';
    $signed_by = $_SESSION["name"] ?? '소현철';
    $payment_account = '중소기업은행 339-084210-01-012 ㈜ 미래기업';
    

    if(empty(!$_SESSION["userid"]) && $_SESSION["level"] < 6) {
        $author_id = 'mirae';
        $author = '소현철';  // 미래기업 직원일 경우는 사장님 이름이 표시되도록 함.
        $hp = '010-3784-5438';
        $signer = '소현철'; // 공급자에 표시되는 이름
    }
    else
    {
        $author_id = $_SESSION["userid"]; // 작성자 아이디
        $author = $_SESSION["name"]; // 작성자 이름
        $hp = $_SESSION["hp"] ?? '010-3784-5438';
        $signer = $_SESSION["name"] ?? '소현철'; // 공급자에 표시되는 이름
    }    
    
    // 견적서에서 전달된 데이터가 있으면 기본값으로 설정
    if ($estimate_data && is_array($estimate_data)) {
        // 견적서 데이터를 수주서 데이터로 변환
        // $converted_data = EstimateDataProcessor::convertEstimateToOrder($estimate_data);        
        
        $recipient = $estimate_data['recipient'];
        $site_name = $estimate_data['site_name'];
        $signed_by = $estimate_data['signed_by'];
        $division = $estimate_data['division'];
        $note = $estimate_data['note'];
        $order_date = $estimate_data['order_date'];
        $payment_account = $estimate_data['payment_account'] ?? '중소기업은행 339-084210-01-012 ㈜ 미래기업';
        $etc_autocheck = $estimate_data['etc_autocheck'] ?? '1';  // 기타비용 자동산출 체크박스 기본은 체크
        // 작성자 정보
        $author_id = $estimate_data['author_id'] ?? 'mirae';
        $author = $estimate_data['author'] ?? '소현철';
        $hp = $estimate_data['hp'] ?? '010-3784-5438';
        $signer = $estimate_data['signer'] ?? '소현철'; // 공급자에 표시되는 이름

        // 견적서 번호
        $estimate_num = $estimate_data['estimate_num'] ?? '';        

    }

    // 수주 관련 추가 필드들
    $order_confirm_date = '';
    $delivery_due_date = '';
    $delivery_date = '';
    $order_close_date = '';

    // 회계 처리 날짜
    $payment_date_head = '';
    $payment_date_dealer = '';
    $tax_invoice_date = '';
    $deposit_date = '';

    // 회계 금액 정보
    $purchase_unit_price = 0;
    $purchase_total = 0;
    $head_balance = 0;
    $dealer_unit_price = 0;
    $dealer_amount = 0;
    $dealer_total = 0;
    $dealer_fee = 0;
    $company_unit_price = 0;
    $company_amount = 0;
    $tax_invoice_amount = 0;
    $tax_diff = 0;
    $is_paid = '';
    $note = '';

    // 체크박스 상태 변수
    $exclude_construction_cost = '0';
    $exclude_molding = '0';
    $etc_autocheck = '1'; // 기타비용 자동계산 체크

}

$isFromQuote = false; // 견적서에서 가져온 경우 1, 아닌 경우 0

if ($estimate_data) {
    // 견적서에서 전달된 데이터가 있으면 items와 other_costs 설정
    if(!empty($estimate_data['items'])) {
        $items = $estimate_data['items'];
    }
    if(!empty($estimate_data['other_costs'])) {
        $other_costs = $estimate_data['other_costs'];
        
        // 견적서에서 전달된 본드 가격 확인 및 저장
        foreach($other_costs as $cost) {
            if(isset($cost['item']) && strpos($cost['item'], '본드') !== false) {
                $estimate_bond_price = $cost['unit_price'] ?? 5000; // 견적서의 본드 가격 저장
                $estimate_bond_quantity = $cost['quantity'] ?? 1; // 견적서의 본드 수량 저장
                echo "<!-- 견적서에서 전달된 본드 가격: " . $estimate_bond_price . ", 수량: " . $estimate_bond_quantity . " -->";
                break;
            }
        }
    }
    if(!empty($estimate_data['discount_items'])) {
        $discount_items = json_decode($estimate_data['discount_items'], true) ?? [];
    }
    if(!empty($estimate_data['discount_other_costs'])) {
        $discount_other_costs = json_decode($estimate_data['discount_other_costs'], true) ?? [];
    }    
    // 시공비 제외 체크 및 몰딩 제외체크 가져오기
    $exclude_construction_cost = $estimate_data['exclude_construction_cost'] ?? '0';
    $exclude_molding = $estimate_data['exclude_molding'] ?? '0';
    $etc_autocheck = $estimate_data['etc_autocheck'] ?? '1';
    
    // 현장명, 수신처, 구분 수주일자 가져오기
    $site_name = $estimate_data['site_name'] ?? '';
    $recipient = $estimate_data['recipient'] ?? '';
    $division = $estimate_data['division'] ?? '';    
    $order_date = date('Y-m-d');

    // 견적서 번호
    $estimate_num = $estimate_data['estimate_num'] ?? '';
    // 견적서에서 가져온 경우 1, 아닌 경우 0
    $isFromQuote = true;    

    // 받는 분 연락처
    $recipient_phone = $estimate_data['recipient_phone'] ?? '';    

    // echo '$estimate_data recipient_phone: ' . $recipient_phone;
}

// 견적서에서 전달된 본드 가격을 JavaScript에서 사용할 수 있도록 설정
$estimate_bond_price = $estimate_bond_price ?? 5000; // 기본값 5000원
$estimate_bond_quantity = $estimate_bond_quantity ?? 1; // 기본값 1개

// echo '<pre>';
// print_r($order_data);
// echo '</pre>';

// echo '<pre>';
// print_r($estimate_data);
// echo '</pre>';

// echo '<pre>';
// print_r($exclude_construction_cost);
// print_r($exclude_molding);
// echo '</pre>';

// echo '<pre>';
// print_r($items);
// echo '</pre>';

// echo '<pre>';
// print_r($other_costs);
// echo '</pre>';

// 보기 모드에서 합계 미리 계산

// echo 'author: ' . $author . ' hp: ' . $hp;

?>

<form method="post" id="orderForm">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">    
    <input type="hidden" id="num" name="num" value="<?= $num ?>">    
    <input type="hidden" id="total_supply" name="total_supply" value="<?= $total_supply ?>">
    <input type="hidden" id="total_tax" name="total_tax" value="<?= $total_tax ?>">
    <input type="hidden" id="total_ex_vat" name="total_ex_vat" value="<?= $total_ex_vat ?>">
    <input type="hidden" id="total_inc_vat" name="total_inc_vat" value="<?= $total_inc_vat ?>">    
    <input type="hidden" id="isFromQuote" name="isFromQuote" value="<?= $isFromQuote ?>"> <!-- 견적서에서 가져온 경우 1, 아닌 경우 0 -->

<div class="container-fluid my-2">
    <div class="card shadow-sm ">
        <div class="card-body p-4">
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center">
                    <h4>
                        <?php 
                        if($mode == 'insert') echo '포미스톤 수주서 작성';
                        elseif($mode == 'modify') echo '포미스톤 수주서 수정';
                        elseif($mode == 'copy') echo '포미스톤 수주서 복사';
                        ?>
                    </h4>
                    <span class="ms-5 fs-6">작성자 :</span>
                    <input class="form-control form-control-sm ms-2 me-2 w100px fs-6 fw-bold" type="text" id="author" name="author" value="<?= htmlspecialchars($author) ?>" >                    
                    <span class="ms-1 fs-6">작성자ID :</span>
                    <input class="form-control form-control-sm ms-2 me-2 w150px fs-6 fw-bold" type="text" id="author_id" name="author_id" value="<?= htmlspecialchars($author_id) ?>" >                    
                    <span class="ms-1 fs-6">견적번호 :</span>
                    <input class="form-control form-control-sm ms-2 me-2 w50px fs-6 " type="text" id="estimate_num" name="estimate_num" value="<?= htmlspecialchars($estimate_num) ?>" >                    
                </div>
                <div>
                    <button type="button" id="saveBtn" class="btn btn-primary btn-sm me-1">저장</button>                    
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="generatePDF()">PDF 저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($mode == 'view'): ?>
                <input type="hidden" id="author_id" name="author_id" value="<?= $author_id ?>">
                <input type="hidden" id="author" name="author" value="<?= $author ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>포미스톤 수주서 보기  
                    <span class="ms-5 fs-6">작성자 : <?= htmlspecialchars($author) ?></span> 
                    <span class="ms-1 fs-6">작성자ID : <?= htmlspecialchars($author_id) ?></span>        
                    <span class="ms-1 fs-6">견적번호 : <?= htmlspecialchars($estimate_num) ?></span>        
                    <input type="hidden" id="estimate_num" name="estimate_num" value="<?= $estimate_num ?>">                            
                </h4>                
                <div>
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="editOrder()">수정</button>
                    <button type="button" class="btn btn-primary btn-sm me-1" onclick="copyOrder()">복사</button>
                    <button type="button" class="btn btn-danger btn-sm me-1" onclick="deleteBtn()">삭제</button>      
                    <button type="button" class="btn btn-warning btn-sm me-1" onclick="openEstimatePopup()">견적서 보기</button>
                    <button type="button" class="btn btn-info btn-sm me-1" onclick="convertToOutorder()">출고증 변환</button>                    
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="generatePDF()">PDF 저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($estimate_data_error): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>견적서 데이터 처리 오류:</strong> <?= htmlspecialchars($estimate_data_error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if($estimate_data && !$estimate_data_error): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>견적서 데이터가 성공적으로 로드되었습니다.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="content-to-print">

<div class="container-fluid my-3">
<div class="card shadow-sm mb-4 ">
<div class="card-body p-4">    
    <!-- 헤더 -->
    <div class="text-center mb-4">
        <h1 class="h2 fw-bold mb-2">수주서</h1>                
    </div>

    <!-- 수신 & 공급자 정보 -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="border rounded p-1 h-100">                        
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <p class="mb-1">
                        <label for="recipient">수신 : </label>
                        <input type="text" id="recipient" name="recipient" value="<?= htmlspecialchars($recipient) ?>" class="form-control form-control-sm" placeholder="수신처명">
                    </p>
                    <p class="mb-1">
                        <label for="division">구분 : </label>
                        <select id="division" name="division" class="form-select form-select-sm w-auto">
                            <option value="유통" <?= ($division == '유통' || empty($division)) ? 'selected' : '' ?>>유통</option>
                            <option value="소비자" <?= ($division == '소비자') ? 'selected' : '' ?>>소비자</option>
                        </select>
                    </p>
                    <p class="mb-1">현장명 : <input type="text" name="site_name" value="<?= htmlspecialchars($site_name) ?>" class="form-control form-control-sm" placeholder="현장명"></p>
                    <p class="mb-1">전화번호 : 010-3784-5438</p>
                    <p class="mb-1"><label for="order_date">수주일자 : </label>
                        <input type="date" id="order_date" name="order_date" value="<?= $order_date ?>" class="form-control form-control-sm w-auto">
                    </p>
                    <p class="mb-1">
                        <label for="recipient_name">물건 받는분 : </label>
                        <input type="text" id="recipient_name" name="recipient_name" value="<?= htmlspecialchars($recipient_name) ?>" class="form-control form-control-sm w-auto" placeholder="받는 분 성함">
                    </p>
                    <p class="mb-1">
                        <label for="recipient_phone">물건 받는분 전화번호 : </label>
                        <input type="text" id="recipient_phone" name="recipient_phone" value="<?= htmlspecialchars($recipient_phone) ?>" class="form-control form-control-sm w-auto" placeholder="받는 분 연락처">
                    </p>
                    
                    <p class="mb-0 mt-3 text-center"> <strong> 아래와 같이 수주합니다.</strong></p>                           

                <?php else: ?>
                    <input type="hidden" id="order_date" name="order_date" value="<?= $order_date ?>">
                    <!-- 수주서 보기 모드 -->
                    <p class="mb-1" style="font-size: 1.2em">수신 : <u id="recipient-text"><?= htmlspecialchars($recipient) ?></u> 귀하</p>
                    <p class="mb-1">구분 : <u id="division-text"><?= htmlspecialchars($division) ?></u></p>
                    <p class="mb-1">현장명 : <u id="site-name-text"><?= htmlspecialchars($site_name) ?></u></p>
                    <p class="mb-1">전화번호 : <u id="contact-text"></u>010-3784-5438</u></p>
                    <p class="mb-1">수주일자 : <u id="order-date-text"><?= date('Y년 m월 d일', strtotime($order_date)) ?></u></p>
                    <p class="mb-1">물건 받는분 : <u id="recipient-name-text"><?= htmlspecialchars($recipient_name) ?></u></p>
                    <p class="mb-1">물건 받는분 전화번호 : <u id="recipient-phone"><?= htmlspecialchars($recipient_phone) ?></u></p>

                    <p class="mb-0 mt-3 text-center"> <strong> 아래와 같이 수주합니다.</strong></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-8 mb-3">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size: 12px; border-collapse: collapse;">
                    <tr>
                        <td class="text-center align-middle bg-light" style="width: 30px; vertical-align: middle; border: 1px solid #000;" rowspan="5">
                            <strong>공급자</strong>
                        </td>
                        <td class="bg-light text-center" style="width: 70px; border: 1px solid #000;">상 호</td>
                        <td colspan="3" class="text-center" style="border: 1px solid #000;"> <strong>주식회사 미래기업</strong></td>                                
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">사업자번호</td>
                        <td class="text-center" style="border: 1px solid #000;">722-88-00035</td>
                        <td class="bg-light text-center" style="width: 80px; border: 1px solid #000;">대 표</td>
                        <td class="text-center" style="border: 1px solid #000;">소현철</td>
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">주 소</td>
                        <td colspan="3" class="text-start" style="border: 1px solid #000;">
                            본사 : 경기도 김포시 양촌읍 흥신로 220-27<br>
                            전시장 : 인천광역시 서구 중봉대로 393번길 16 홈씨씨2층 포미스톤
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">업 태</td>
                        <td class="text-center" style="border: 1px solid #000;">제조업</td>
                        <td class="bg-light text-center" style="border: 1px solid #000;">종 목</td>
                        <td class="text-center" style="border: 1px solid #000;">엘리베이터의장품</td>
                    </tr>
                    <tr>
                        <td class="bg-light text-center" style="border: 1px solid #000;">담당자</td>
                        <td class="text-center" style="border: 1px solid #000;">
                            <span class="fw-bold signer-text"><?= htmlspecialchars($author) ?></span>
                        </td>
                        <td class="bg-light text-center" style="border: 1px solid #000;">연락처</td>
                        <td class="text-center" style="border: 1px solid #000;">
                            <span class="fw-bold hp-text"><?= htmlspecialchars($hp) ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- 합계 테이블 -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered mb-0 align-middle" style="font-size: 12px; border-collapse: collapse;">
            <tr>
                <td class="text-center bg-light" style="width: 50%; border: 1px solid #000;">
                    <div class="fw-semibold">합계금액(부가세별도)</div>
                </td>
                <td class="text-center bg-light" style="border: 1px solid #000;">                            
                    <div class="d-flex justify-content-center align-items-center mt-1 fw-semibold">
                        <span class="total-ex-vat">(<?= number_format($total_ex_vat ?? 0) ?>)</span>
                    </div>
                </td>
                <td class="text-center bg-light" style="width: 50%; border: 1px solid #000;">
                    <div class="fw-semibold text-primary">합계금액(부가세포함)</div>
                </td>
                <td class="text-center bg-light" style="border: 1px solid #000;">                            
                    <div class="d-flex justify-content-center align-items-center mt-1 fw-semibold">
                        <span class="total-inc-vat text-primary">(<?= number_format($total_inc_vat ?? 0) ?>)</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>  

    <!-- 상품 내역 테이블 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold mb-0">상품 내역</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">No.</th>
                        <th scope="col" style="width: 28%;">상품명</th>
                        <th scope="col" style="width: 8%;">규격</th>
                        <th scope="col" style="width: 12%;">분류</th>
                        <th scope="col" style="width: 7%;">수량EA</th>
                        <th scope="col" style="width: 6%;">m²</th>
                        <th scope="col" style="width: 8%;">단가</th>
                        <th scope="col" style="width: 6%;">공급가액</th>
                        <th scope="col" style="width: 6%;">세액</th>
                        <th scope="col" style="width: 10%;">비고</th>
                        <th scope="col" style="width: 5%;">할인</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php 
                        $item_count = max(1, count($items));
                        for($i = 0; $i < $item_count; $i++): ?>
                        <tr class="item-row" data-row="<?= $i ?>">
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-2"><?= $i + 1 ?></span>
                                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addItemRowAfter(<?= $i ?>)" title="아래에 행 추가">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyItemRow(<?= $i ?>)" title="행 복사">
                                            <i class="bi bi-files"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteItemRow(<?= $i ?>)" title="행 삭제">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: left;">
                                <select name="items[<?= $i ?>][product_code]" class="form-select form-select-sm product-select" data-row="<?= $i ?>" style="text-align: left;">
                                    <option value="">상품을 선택하세요</option>
                                    <?php
                                    // 단가표에서 상품 목록 가져오기
                                    try {
                                        $product_sql = "SELECT prodcode, texture_eng, texture_kor, design_eng, design_kor, type, size, thickness, area, dist_price_per_m2, retail_price_per_m2 FROM mirae8440.phomi_unitprice ORDER BY prodcode ASC";
                                        $product_stmt = $pdo->prepare($product_sql);
                                        $product_stmt->execute();
                                        
                                        while($product = $product_stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($items[$i]['product_code'] ?? '') == $product['prodcode'] ? 'selected' : '';
                                            $display_name = $product['prodcode'] . ' - ' . $product['texture_kor'] . ' ' . $product['design_kor'] . ' (' . $product['texture_eng'] . ' ' . $product['design_eng'] . ')';
                                            echo '<option value="' . $product['prodcode'] . '" data-spec="' . htmlspecialchars($product['type']) . '" data-size="' . htmlspecialchars($product['size']) . '" data-thickness="' . htmlspecialchars($product['thickness']) . '" data-area="' . $product['area'] . '" data-unit-price="' . $product['dist_price_per_m2'] . '" ' . $selected . '>' . htmlspecialchars($display_name) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo "상품 목록 조회 오류: " . $e->getMessage();
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="text" name="items[<?= $i ?>][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="<?= htmlspecialchars($items[$i]['specification'] ?? '') ?>" readonly></td>
                            <td><input type="text" name="items[<?= $i ?>][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="<?= htmlspecialchars($items[$i]['size'] ?? '') ?>" readonly></td>
                            <td><input type="number" name="items[<?= $i ?>][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="<?= $items[$i]['quantity'] ?? '1' ?>"></td>
                            <td><input type="text" name="items[<?= $i ?>][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="<?= $items[$i]['area'] ?? '' ?>" readonly></td>
                            <td><input type="text" name="items[<?= $i ?>][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="<?= number_format($items[$i]['unit_price'] ?? 0) ?>" oninput="inputNumber(this)"></td>
                            <td class="text-end supply-amount">
                                <?php 
                                $supply_amount = 0;
                                if(isset($items[$i]['quantity']) && isset($items[$i]['unit_price'])) {
                                    $supply_amount = floatval(str_replace(',', '', $items[$i]['area'])) * floatval(str_replace(',', '', $items[$i]['unit_price']));
                                }
                                echo '' . number_format($supply_amount);
                                ?>
                            </td>
                            <td class="text-end tax-amount">
                                <?php 
                                $tax_amount = $supply_amount * 0.1;
                                echo '' . number_format($tax_amount);
                                ?>
                            </td>
                            <td><input type="text" name="items[<?= $i ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($items[$i]['remarks'] ?? '') ?>"></td>
                            <td>
                                <!-- 할인버튼 추가 -->
                                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountItemRowAfter(<?= $i ?>)" title="할인 행 추가">
                                    할인
                                </button>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php else: ?>
                    <?php
                    // view 모드에서 상품 목록 표시
                    $item_counter = 1;
                    $total_supply = 0;
                    $total_tax = 0;
                    foreach($items as $item): 
                        $supply_amount = floatval(str_replace(',', '', $item['area'])) * floatval(str_replace(',', '', $item['unit_price']));
                        $tax_amount = $supply_amount * 0.1;
                        
                        // 합계 계산
                        $total_supply += $supply_amount;
                        $total_tax += $tax_amount;
                        
                        // 상품명 표시
                        $display_product_name = '';
                        if(!empty($item['product_code'])) {
                            try {
                                $product_name_sql = "SELECT prodcode, texture_kor, design_kor FROM mirae8440.phomi_unitprice WHERE prodcode = :prodcode";
                                $product_name_stmt = $pdo->prepare($product_name_sql);
                                $product_name_stmt->bindParam(':prodcode', $item['product_code'], PDO::PARAM_STR);
                                $product_name_stmt->execute();
                                $product_info = $product_name_stmt->fetch(PDO::FETCH_ASSOC);
                                if($product_info) {
                                    $display_product_name = $product_info['texture_kor'] . ' ' . $product_info['design_kor'];
                                } else {
                                    $display_product_name = $item['product_name'] ?? '';
                                }
                            } catch (PDOException $e) {
                                $display_product_name = $item['product_name'] ?? '';
                            }
                        } else {
                            $display_product_name = $item['product_name'] ?? '';
                        }
                    ?>
                    <tr class="item-row-view">
                        <td><?= $item_counter ?></td>
                        <td class='product-code' style='display:none;'><?= $item['product_code'] ?></td>                        
                        <td class="text-start"><?= htmlspecialchars($display_product_name) ?></td>
                        <td><?= htmlspecialchars($item['specification'] ?? '') ?></td>
                        <td class="text-center"><?= htmlspecialchars($item['size'] ?? '') ?></td>
                        <td class="text-end quantity-input"><?= number_format($item['quantity'] ?? 0) ?></td>
                        <td class="text-end area-input"><?= number_format($item['area'] ?? 0, 2) ?></td>
                        <td class="text-end"><?= number_format(floatval(str_replace(',', '', $item['unit_price'] ?? 0))) ?></td>
                        <td class="text-end"><?= number_format($supply_amount) ?></td>
                        <td class="text-end"><?= number_format($tax_amount) ?></td>
                        <td><?= htmlspecialchars($item['remarks'] ?? '') ?></td>
                    </tr>
                    <?php 
                    $item_counter++;
                    endforeach; 
                    endif; ?>
                </tbody>
                <tfoot>     
                    <!-- 소계 행 -->                        
                    <tr class="table-secondary">
                        <td colspan="7" class="text-end fw-medium">소계</td>
                        <td class="text-end fw-bold" id="totalSupply"><?= number_format($total_supply) ?></td>
                        <td class="text-end fw-bold" id="totalTax"><?= number_format($total_tax) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <!-- 기타 비용 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold mb-0">기타 비용 (부자재 및 인건비 등)</h6>
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <div class="form-check ms-3 d-flex align-items-center">
                <input class="form-check-input ms-5" type="checkbox" id="etc_autocheck" name="etc_autocheck" <?= $etc_autocheck == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                <label class="form-check-label fs-6 ms-3 text-primary" for="etc_autocheck">
                    자동계산
                </label>
            </div>
            <div class="form-check ms-3 d-flex align-items-center">
                <input class="form-check-input ms-5" type="checkbox" id="exclude_construction_cost" name="exclude_construction_cost" <?= $exclude_construction_cost == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                <label class="form-check-label fs-6 ms-3 text-primary" for="exclude_construction_cost">
                    시공비 제외
                </label>
            </div>
            <div class="form-check ms-3 d-flex align-items-center">
                <input class="form-check-input ms-5" type="checkbox" id="exclude_molding" name="exclude_molding" <?= $exclude_molding == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                <label class="form-check-label fs-6 ms-3 text-primary" for="exclude_molding">
                    몰딩 제외
                </label>
            </div>
            <!-- <button type="button" class="btn btn-outline-warning btn-sm ms-3" id="recalculateOtherCostsBtn" title="기타비용 재계산">
                    <i class="bi bi-arrow-clockwise"></i> 재계산
            </button>                     -->
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="otherCostsTable">
                <thead class="table-light">
                    <tr>
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <th scope="col" style="width: 10%;">기능</th>
                        <?php endif; ?>
                            <th scope="col" style="width: 10%;">구분</th>
                            <th scope="col" style="width: 10%;">항목</th>
                            <th scope="col" style="width: 10%;">단위</th>
                            <th scope="col" style="width: 10%;">수량</th>
                            <th scope="col" style="width: 10%;">단가</th>
                            <th scope="col" style="width: 10%;">공급가액</th>
                            <th scope="col" style="width: 10%;">세액</th>
                            <th scope="col" style="width: 10%;">비고</th>
                            <th scope="col" style="width: 10%;">할인</th>
                    </tr>
                </thead>
                <tbody id="otherCostsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php                        
                        // 기존 데이터가 있으면 사용, 없으면 기본값 사용
                        $cost_count = max(1, count($other_costs));
                        for($c = 0; $c < $cost_count; $c++): 
                            $cost_data = $other_costs[$c] ?? $default_costs[$c] ?? $default_costs[0];
                        ?>
                        <tr class="cost-row" data-row="<?= $c ?>">
                            <td>
                                <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                                    <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(<?= $c ?>)" title="아래에 행 추가">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(<?= $c ?>)" title="행 복사">
                                        <i class="bi bi-files"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(<?= $c ?>)" title="행 삭제">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="<?= htmlspecialchars($cost_data['category'] ?? '') ?>">                                        
                            </td>
                            <td><input type="text" name="other_costs[<?= $c ?>][item]" class="form-control form-control-sm text-start" placeholder="항목" value="<?= htmlspecialchars($cost_data['item'] ?? '') ?>"></td>
                            <td><input type="text" name="other_costs[<?= $c ?>][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="<?= htmlspecialchars($cost_data['unit'] ?? '') ?>"></td>
                            <td><input type="number" name="other_costs[<?= $c ?>][quantity]" class="form-control form-control-sm text-end cost-quantity-input" placeholder="수량" step="1" value="<?= $cost_data['quantity'] ?? '' ?>"></td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][unit_price]" class="form-control form-control-sm text-end cost-unit-price-input" placeholder="단가" value="<?php
                                    $unit_price = $other_costs[$c]['unit_price'] ?? 0;
                                    echo is_numeric($unit_price) ? number_format($unit_price) : $unit_price;
                                ?>">
                            </td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][supply_amount]" class="form-control form-control-sm text-end cost-supply-amount" value="<?php
                                    $quantity = $other_costs[$c]['quantity'] ?? 0;
                                    $unit_price = $other_costs[$c]['unit_price'] ?? 0;
                                    $supply_amount = (is_numeric($quantity) && is_numeric($unit_price)) ? $quantity * $unit_price : 0;
                                    echo is_numeric($supply_amount) ? number_format($supply_amount) : $supply_amount;
                                ?>" readonly>
                            </td>
                            <td>
                                <input type="text" name="other_costs[<?= $c ?>][tax_amount]" class="form-control form-control-sm text-end cost-tax-amount" value="<?php
                                    $quantity = $other_costs[$c]['quantity'] ?? 0;
                                    $unit_price = $other_costs[$c]['unit_price'] ?? 0;
                                    $tax_amount = (is_numeric($quantity) && is_numeric($unit_price)) ? $quantity * $unit_price * 0.1 : 0;
                                    echo is_numeric($tax_amount) ? number_format($tax_amount) : $tax_amount;
                                ?>" readonly>
                            </td>
                            <td><input type="text" name="other_costs[<?= $c ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($cost_data['remarks'] ?? '') ?>"></td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px; font-size: 10px;" onclick="addDiscountCostRow(<?= $c ?>)" title="할인 행 추가">
                                    할인
                                </button>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php else: // $mode == 'view' 
                        ?>                            
                    <!-- 체크박스 숨김형태 -->                    
                    <input type="hidden" id="etc_autocheck" name="etc_autocheck" value="<?= $etc_autocheck ?>">
                    <input type="hidden" id="exclude_construction_cost" name="exclude_construction_cost" value="<?= $exclude_construction_cost ?>">
                    <input type="hidden" id="exclude_molding" name="exclude_molding" value="<?= $exclude_molding ?>">
                    <?php
                    // view 모드에서 기타비용 목록 표시 (최대 4개만)
                    if(!empty($other_costs)):
                    $view_cost_count = 0;
                    $total_supply_amount = 0;
                    $total_tax_amount = 0;
                    foreach($other_costs as $cost):
                        if($view_cost_count >= 4) break; // 최대 4개만 표시
                        $view_cost_count++;
                        $cost_supply_amount = 0;
                        $cost_tax_amount = 0;
                        
                        if(isset($cost['quantity']) && isset($cost['unit_price']) && 
                            is_numeric($cost['quantity']) && is_numeric($cost['unit_price'])) {
                            
                            if(isset($cost['unit']) && ($cost['unit'] === '㎡' || $cost['unit'] === 'm²')) {
                                if($cost['quantity'] > 28) {
                                    $cost_supply_amount = $cost['quantity'] * $cost['unit_price'];
                                } else {
                                    $cost_supply_amount = $cost['unit_price'];
                                }
                            } else {
                                $cost_supply_amount = $cost['quantity'] * $cost['unit_price'];
                            }
                            
                            $cost_tax_amount = $cost_supply_amount * 0.1;
                            $total_supply_amount += $cost_supply_amount;
                            $total_tax_amount += $cost_tax_amount;
                        }
                    ?>
                    <tr class="other-cost-row-view">
                        <td class="cost-category-input"><?= htmlspecialchars($cost['category'] ?? '') ?></td>
                        <td class="text-start cost-item-input"><?= htmlspecialchars($cost['item'] ?? '') ?></td>
                        <td class="text-center cost-unit-input"><?= htmlspecialchars($cost['unit'] ?? '') ?></td>                                
                        <td class="text-end cost-quantity-input"><?= ($cost['quantity'] ?? 0) > 0 ? $cost['quantity'] : '' ?></td>
                        <td class="text-end cost-unit-price-input"><?= ($cost['quantity'] ?? 0) > 0 ? '' . number_format($cost['unit_price'] ?? 0) : '' ?></td>
                        <td class="text-end "><?= ($cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_supply_amount) : '' ?></td>
                        <td class="text-end "><?= ($cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_tax_amount) : '' ?></td>
                        <td class="text-start cost-remarks-input"><?= htmlspecialchars($cost['remarks'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; 
                        endif; 
                        endif; 
                    ?>
                    
                </tbody>
                <tfoot>
                    <!-- 기타비용 소계 행 -->                            
                    <tr class="table-secondary">
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <td colspan="6" class="text-end fw-medium">소계</td>
                        <?php else: // $mode == 'view' ?>
                            <td colspan="5" class="text-end fw-medium">소계</td>
                        <?php endif; ?>
                        <td class="text-end fw-bold" id="totalOtherCostsSupply">
                            <?php if($mode == 'view'): ?>
                                <?= number_format($total_supply_amount) ?>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold" id="totalOtherCostsTax">
                            <?php if($mode == 'view'): ?>
                                <?= number_format($total_tax_amount) ?>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>            
   
    <!-- 할인 상품 내역 테이블 -처리 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold text-danger mb-0">할인 상품 내역(본사금액 차감 - 구매자 할인 적용)</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="discountItemsTable">
                <thead class="table-light table-danger">
                    <tr>
                        <th scope="col" style="width: 8%;">No.</th>
                        <th scope="col" style="width: 25%;">상품명</th>
                        <th scope="col" style="width: 8%;">규격(Size)</th>
                        <th scope="col" style="width: 12%;">분류</th>
                        <th scope="col" style="width: 6%;">수량(EA)</th>
                        <th scope="col" style="width: 6%;">m²</th>
                        <th scope="col" style="width: 7%;">단가</th>
                        <th scope="col" style="width: 7%;">공급가액</th>
                        <th scope="col" style="width: 7%;">세액</th>
                        <th scope="col" style="width: 10%;">비고</th>
                    </tr>
                </thead>
                <tbody id="discountItemsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php
                        $discount_item_count =  count($discount_items);
                        for($i = 0; $i < $discount_item_count; $i++): ?>
                        <tr class="discount-item-row" data-row="<?= $i ?>">
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-2"><?= $i + 1 ?></span>
                                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountItemRow(<?= $i ?>)" title="행 삭제">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: left;">
                                <input type="hidden" name="discount_items[<?= $i ?>][product_code]" class="form-control form-control-sm" data-row="<?= $i ?>" style="text-align: left;" value="<?= htmlspecialchars($discount_items[$i]['product_code'] ?? '') ?>" readonly>                                
                                <!-- 상품명 표시 -->
                                <input type="text" name="discount_items[<?= $i ?>][code_string]" class="form-control form-control-sm" data-row="<?= $i ?>" style="text-align: left;" value="<?= htmlspecialchars($discount_items[$i]['code_string'] ?? '') ?>" readonly>                                
                            </td>
                            <td><input type="text" name="discount_items[<?= $i ?>][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="<?= htmlspecialchars($discount_items[$i]['specification'] ?? '') ?>" readonly></td>
                            <td><input type="text" name="discount_items[<?= $i ?>][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="<?= htmlspecialchars($discount_items[$i]['size'] ?? '') ?>" readonly></td>
                            <td><input type="number" name="discount_items[<?= $i ?>][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="<?= $discount_items[$i]['quantity'] ?? '1' ?>" readonly></td>
                            <td><input type="text" name="discount_items[<?= $i ?>][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="<?= $discount_items[$i]['area'] ?? '' ?>" readonly></td>
                            <td><input type="text" name="discount_items[<?= $i ?>][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="<?= number_format($discount_items[$i]['unit_price'] ?? 0) ?>" readonly></td>
                            <td class="text-end">
                                <input type="text" name="discount_items[<?= $i ?>][supply_amount]" class="form-control form-control-sm text-end supply-amount-input discount-item-supply-amount" placeholder="공급가액" value="<?= number_format($discount_items[$i]['supply_amount'] ?? 0) ?>" readonly>                                                                
                            </td>
                            <td class="text-end ">
                                <input type="text" name="discount_items[<?= $i ?>][tax_amount]" class="form-control form-control-sm text-end tax-amount-input discount-item-tax-amount" placeholder="세액" value="<?= number_format($discount_items[$i]['tax_amount'] ?? 0) ?>" readonly>                                                                
                            </td>
                            <td> <input type="text" name="discount_items[<?= $i ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($discount_items[$i]['remarks'] ?? '') ?>" ></td>
                        </tr>
                    <?php endfor; ?>
                    <?php else: // $mode == 'view' ?>
                    <?php
                    // view 모드에서 상품 목록 표시
                    $item_counter = 1;
                    $total_supply = 0;
                    $total_tax = 0;
                    foreach($discount_items as $discount_item): 
                        $supply_amount = floatval(str_replace(',', '', $discount_item['area'])) * floatval(str_replace(',', '', $discount_item['unit_price']));
                        $tax_amount = $supply_amount * 0.1;
                        
                        // 소계 누적
                        $total_supply -= $supply_amount;
                        $total_tax -= $tax_amount;
                        
                        // 상품명 표시
                        $display_product_name = '';
                        if(!empty($discount_item['product_code'])) {
                            try {
                                $product_name_sql = "SELECT prodcode, texture_kor, design_kor FROM mirae8440.phomi_unitprice WHERE prodcode = :prodcode";
                                $product_name_stmt = $pdo->prepare($product_name_sql);
                                $product_name_stmt->bindParam(':prodcode', $discount_item['product_code'], PDO::PARAM_STR);
                                $product_name_stmt->execute();
                                $product_info = $product_name_stmt->fetch(PDO::FETCH_ASSOC);
                                if($product_info) {
                                    $display_product_name = $product_info['texture_kor'] . ' ' . $product_info['design_kor'];
                                } else {
                                    $display_product_name = $discount_item['product_name'] ?? '';
                                }
                            } catch (PDOException $e) {
                                $display_product_name = $discount_item['product_name'] ?? '';
                            }
                        } else {
                            $display_product_name = $discount_item['product_name'] ?? '';
                        }
                    ?>
                    <tr class="discount-item-row-view">
                        <td><?= $item_counter ?></td>
                        <td class="text-start"><?= htmlspecialchars($display_product_name) ?></td>
                        <td class="text-start"><?= htmlspecialchars($discount_item['specification'] ?? '') ?></td>
                        <td class="text-center"><?= htmlspecialchars($discount_item['size'] ?? '') ?></td>
                        <td class="text-end"><?= number_format($discount_item['quantity'] ?? 0) ?></td>
                        <td class="text-end area-input"><?= number_format($discount_item['area'] ?? 0, 2) ?></td>
                        <td class="text-end"><?= number_format(floatval(str_replace(',', '', $discount_item['unit_price'] ?? 0))) ?></td>
                        <td class="text-end"><?= number_format($supply_amount) ?></td>
                        <td class="text-end"><?= number_format($tax_amount) ?></td>
                        <td><?= htmlspecialchars($discount_item['remarks'] ?? '') ?></td>
                    </tr>
                    <?php 
                    $item_counter++;                            
                    endforeach; 
                    endif; 
                    ?>
                </tbody>                    
                    <!-- 할인상품 소계 행 -->    
                    <tfoot>
                    <tr class="table-secondary">
                        <td colspan="7" class="text-end text-danger fw-medium">할인 소계</td>
                        <td class="text-end fw-bold text-danger" id="discountTotalSupply"><?= number_format($total_supply) ?></td>
                        <td class="text-end fw-bold text-danger" id="discountTotalTax"><?= number_format($total_tax) ?></td>
                        <td></td>
                    </tr>
                    </tfoot>                    
            </table>
        </div>
    </div>

    <!-- 할인기타 비용 -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-start mb-2">
            <h6 class="fw-semibold text-danger mb-0">할인 기타 비용 (본사금액 차감 - 구매자 할인 적용)
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-2" id="addDiscountOtherCostRow">
                        할인추가
                    </button>
                <?php endif; ?>
            </h6>
            <script>
            // 할인 기타비용 행 추가 함수
            function addDiscountOtherCostRow() {
                // 현재 행 개수
                var rowCount = $('#discountOtherCostsTableBody tr.discount-cost-row').length;
                var newRowIdx = rowCount;

                // 새 행 HTML 생성
                var newRow = `
                <tr class="discount-cost-row" data-row="${newRowIdx}">
                    <td>
                        <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                            <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountCostRow(${newRowIdx})" title="행 삭제">
                                <i class="bi bi-dash"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="할인적용">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][item]" class="form-control form-control-sm ms-1" placeholder="항목" value="할인">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][unit]" class="form-control form-control-sm ms-1" placeholder="단위" value="">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][quantity]" class="form-control form-control-sm ms-1 discount-input text-end discount-cost-quantity-input" placeholder="수량" value="1">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][unit_price]" class="form-control form-control-sm ms-1 discount-input text-end discount-cost-unit-price-input" placeholder="단가" value="" oninput="inputNumber(this)" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][supply_amount]" class="form-control form-control-sm ms-1 text-end discount-cost-supply-amount" placeholder="공급가액" value="" readonly autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][tax_amount]" class="form-control form-control-sm ms-1 text-end discount-cost-tax-amount" placeholder="세액" value="" readonly autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="discount_other_costs[${newRowIdx}][remarks]" class="form-control form-control-sm ms-1" placeholder="비고" value="">
                    </td>
                </tr>
                `;
                $('#discountOtherCostsTableBody').append(newRow);
            }

           // 버튼 클릭 이벤트 바인딩
            $(document).on('click', '#addDiscountOtherCostRow', function() {
                addDiscountOtherCostRow();
            });

            // discount-input 입력시 콤마 제거, 공급가액/세액 자동계산, updateTotal 호출
            $(document).on('input', '.discount-input', function() {
                var $row = $(this).closest('tr.discount-cost-row');
                // 수량, 단가 값 가져오기 (콤마 제거)
                var quantity = $row.find('input[name*="[quantity]"]').val().replace(/,/g, '');
                var unitPrice = $row.find('input[name*="[unit_price]"]').val().replace(/,/g, '');

                // 숫자 변환
                var qty = parseFloat(quantity) || 0;
                var price = parseFloat(unitPrice) || 0;

                // 입력값에 콤마 제거 후 다시 입력 (숫자만)
                if ($(this).attr('name').includes('[quantity]')) {
                    $(this).val(quantity.replace(/[^0-9.]/g, ''));
                }
                if ($(this).attr('name').includes('[unit_price]')) {
                    $(this).val(unitPrice.replace(/[^0-9.]/g, ''));
                }

                // 공급가액, 세액 계산
                var supply = Math.round(qty * price);
                var tax = Math.round(supply * 0.1);

                // 표시
                $row.find('input[name*="[supply_amount]"]').val(supply ? supply.toLocaleString() : '');
                $row.find('input[name*="[tax_amount]"]').val(tax ? tax.toLocaleString() : '');

                // 합계 업데이트 함수 호출
                if (typeof updateTotals === 'function') {
                    updateTotals();
                }
            });
            </script>            
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="discountOtherCostsTable">
                <thead class="table-light table-danger">
                    <tr>
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <th scope="col" style="width: 10%;">기능</th>
                        <?php endif; ?>
                            <th scope="col" style="width: 10%;">구분</th>
                            <th scope="col" style="width: 10%;">항목</th>
                            <th scope="col" style="width: 10%;">단위</th>
                            <th scope="col" style="width: 10%;">수량</th>
                            <th scope="col" style="width: 10%;">단가</th>
                            <th scope="col" style="width: 10%;">공급가액</th>
                            <th scope="col" style="width: 10%;">세액</th>
                            <th scope="col" style="width: 10%;">비고</th>
                    </tr>
                </thead>
                <tbody id="discountOtherCostsTableBody">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        <?php
                        // 기타비용 5개 행 자동 생성 (부자재, 시공비 등)
                        $default_costs = [
                            ['category' => '부자재', 'item' => '본드', 'unit' => 'EA', 'quantity' => '', 'unit_price' => '', 'remarks' => ''],
                            ['category' => '부자재', 'item' => '몰딩', 'unit' => 'EA', 'quantity' => '', 'unit_price' => '', 'remarks' => ''],
                            ['category' => '', 'item' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '', 'remarks' => ''],
                            ['category' => '시공비', 'item' => '㎡당 시공비', 'unit' => '㎡', 'quantity' => '', 'unit_price' => '', 'remarks' => '최소 시공비 70만원 (28㎡)'],
                            ['category' => '운송비', 'item' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '', 'remarks' => '착불']
                        ];
                        
                        // 기존 데이터가 있으면 사용, 없으면 기본값 사용
                        $discount_cost_count = count($discount_other_costs);
                        for($c = 0; $c < $discount_cost_count; $c++): 
                            $discount_cost_data = $discount_other_costs[$c] ?? $default_costs[$c] ?? $default_costs[0];
                        ?>
                        <tr class="discount-cost-row" data-row="<?= $c ?>">
                            <td>
                                <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountCostRow(<?= $c ?>)" title="행 삭제">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="discount_other_costs[<?= $c ?>][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="<?= htmlspecialchars($discount_cost_data['category'] ?? '') ?>">                                        
                            </td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][item]" class="form-control form-control-sm text-start" placeholder="항목" value="<?= htmlspecialchars($discount_cost_data['item'] ?? '') ?>"></td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="<?= htmlspecialchars($discount_cost_data['unit'] ?? '') ?>"></td>
                            <td><input type="number" name="discount_other_costs[<?= $c ?>][quantity]" class="form-control form-control-sm text-end discount-input discount-cost-quantity-input" placeholder="수량" step="1" value="<?= $discount_cost_data['quantity'] ?? '' ?>"></td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][unit_price]" class="form-control form-control-sm text-end discount-input discount-cost-unit-price-input" placeholder="단가" value="<?= is_numeric($discount_cost_data['unit_price'] ?? '') ? number_format($discount_cost_data['unit_price']) : '' ?>" oninput="inputNumber(this)"></td>
                            <td class="text-end ">
                                <input type="text" name="discount_other_costs[<?= $c ?>][supply_amount]" class="form-control form-control-sm text-end discount-cost-supply-amount" placeholder="공급가액" value="<?= number_format($discount_cost_data['supply_amount'] ?? 0) ?>" readonly>
                            </td>
                            <td class="text-end ">
                                <input type="text" name="discount_other_costs[<?= $c ?>][tax_amount]" class="form-control form-control-sm text-end discount-cost-tax-amount" placeholder="세액" value="<?= number_format($discount_cost_data['tax_amount'] ?? 0) ?>" readonly>
                            </td>
                            <td><input type="text" name="discount_other_costs[<?= $c ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($cost_data['remarks'] ?? '') ?>"></td>
                        </tr>
                        <?php endfor; ?>
                    <?php else: // $mode == 'view' ?>
                    <?php                            
                    // view 모드에서 기타비용 목록 표시 (최대 4개만)
                    if(!empty($discount_other_costs))
                      {
                        $view_cost_count = 0;
                        $total_supply = 0;
                        $total_tax = 0;
                        foreach($discount_other_costs as $discount_cost):
                            if($view_cost_count >= 4) break; // 최대 4개만 표시
                            $view_cost_count++;
                            $cost_supply_amount = 0;
                            $cost_tax_amount = 0;
                            
                            if(isset($discount_cost['quantity']) && isset($discount_cost['unit_price']) && 
                            is_numeric($discount_cost['quantity']) && is_numeric($discount_cost['unit_price'])) {
                                
                                if(isset($discount_cost['unit']) && ($discount_cost['unit'] === '㎡' || $discount_cost['unit'] === 'm²')) {
                                    if($discount_cost['quantity'] > 28) {
                                        $cost_supply_amount = $discount_cost['quantity'] * $discount_cost['unit_price'];
                                    } else {
                                        $cost_supply_amount = $discount_cost['unit_price'];
                                    }
                                } else {
                                    $cost_supply_amount = $discount_cost['quantity'] * $discount_cost['unit_price'];
                                }
                                
                                $cost_tax_amount = $cost_supply_amount * 0.1;
                                $total_supply -= $cost_supply_amount;
                                $total_tax -= $cost_tax_amount;
                            }
                        ?>
                        <tr class="discount-cost-row-view">
                            <td><?= htmlspecialchars($discount_cost['category'] ?? '') ?></td>
                            <td class="text-start cost-item-input"><?= htmlspecialchars($discount_cost['item'] ?? '') ?></td>
                            <td class="text-center cost-unit-input"><?= htmlspecialchars($discount_cost['unit'] ?? '') ?></td>                                
                            <td class="text-end discount-cost-quantity-input"><?= ($discount_cost['quantity'] ?? 0) > 0 ? $discount_cost['quantity'] : '' ?></td>
                            <td class="text-end discount-cost-unit-price-input"><?= ($discount_cost['quantity'] ?? 0) > 0 ? '' . number_format($discount_cost['unit_price'] ?? 0) : '' ?></td>
                            <td class="text-end discount-cost-supply-amount"><?= ($discount_cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_supply_amount) : '' ?></td>
                            <td class="text-end discount-cost-tax-amount"><?= ($discount_cost['quantity'] ?? 0) > 0 ? '' . number_format($cost_tax_amount) : '' ?></td>
                            <td class="discount-cost-remarks-input"><?= htmlspecialchars($discount_cost['remarks'] ?? '') ?></td>
                        </tr>
                    <?php 
                        endforeach; 
                         }
                        endif; 
                    ?>
                </tbody>
                <tfoot>
                    <!-- 할인 기타비용 소계 행 -->                            
                    <tr class="table-secondary">
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <td colspan="6" class="text-end fw-medium text-danger"> 할인 소계</td>
                        <?php else: // $mode == 'view' ?>
                            <td colspan="5" class="text-end fw-medium text-danger">할인 소계</td>
                        <?php endif; ?>
                        <td class="text-end fw-bold text-danger" id="discountOtherCostsTotalSupply"><?= '' . number_format($total_supply) ?></td>
                        <td class="text-end fw-bold text-danger" id="discountOtherCostsTotalTax"><?= '' . number_format($total_tax) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 비고 -->
    <div class="mb-4">
        <label class="form-label">비고</label>
        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <textarea name="note" class="form-control" rows="3" placeholder="비고사항을 입력하세요"><?= htmlspecialchars($note) ?></textarea>
        <?php else: ?>
            <div class="form-control-plaintext"><?= nl2br(htmlspecialchars($note)) ?: '-' ?></div>
        <?php endif; ?>
    </div>

    <!-- 입금계좌정보 -->
    <div class="mb-4">                                             
        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <p class="mb-0"><h6><p class="text-center badge bg-primary">입금계좌정보 : <input type="text" name="payment_account" value="<?= htmlspecialchars($payment_account) ?>" class="form-control form-control-sm d-inline-block" style="width: 300px;" placeholder="입금계좌정보"></p></h6></p>
        <?php else: ?>
            <p class="mb-0"><h6><p class="text-center badge bg-primary">입금계좌정보 : <?= htmlspecialchars($payment_account) ?></p></h6></p>
        <?php endif; ?>
    </div>

    <!-- 수주 일정 및 회계 정보 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">수주 일정</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">수주 확정일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="order_confirm_date" value="<?= $order_confirm_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($order_confirm_date && $order_confirm_date != '0000-00-00') ? $order_confirm_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">출고 예정일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="delivery_due_date" value="<?= $delivery_due_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($delivery_due_date && $delivery_due_date != '0000-00-00') ? $delivery_due_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">실제 출고일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="delivery_date" value="<?= $delivery_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($delivery_date && $delivery_date != '0000-00-00') ? $delivery_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">수주 마감일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="order_close_date" value="<?= $order_close_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($order_close_date && $order_close_date != '0000-00-00') ? $order_close_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">회계 처리 날짜</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">본사 대금 지급일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="payment_date_head" value="<?= $payment_date_head ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($payment_date_head && $payment_date_head != '0000-00-00') ? $payment_date_head : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">대리점 수령일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="payment_date_dealer" value="<?= $payment_date_dealer ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($payment_date_dealer && $payment_date_dealer != '0000-00-00') ? $payment_date_dealer : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">세금계산서 발행일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="tax_invoice_date" value="<?= $tax_invoice_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($tax_invoice_date && $tax_invoice_date != '0000-00-00') ? $tax_invoice_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">업체 입금일</label>
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                <input type="date" name="deposit_date" value="<?= $deposit_date ?>" class="form-control form-control-sm">
                            <?php else: ?>
                                <div class="form-control-plaintext"><?= ($deposit_date && $deposit_date != '0000-00-00') ? $deposit_date : '-' ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- 회계 금액 정보 -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">회계 금액 정보</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">본사 매입 단가</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="purchase_unit_price" value="<?= $purchase_unit_price ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $purchase_unit_price ? number_format($purchase_unit_price) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">본사 총 매입금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="purchase_total" value="<?= $purchase_total ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $purchase_total ? number_format($purchase_total) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">본사 잔액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="head_balance" value="<?= $head_balance ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $head_balance ? number_format($head_balance) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 단가</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_unit_price" value="<?= $dealer_unit_price ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_unit_price ? number_format($dealer_unit_price) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_amount" value="<?= $dealer_amount ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_amount ? number_format($dealer_amount) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 총매출금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_total" value="<?= $dealer_total ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_total ? number_format($dealer_total) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">대리점 수수료</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="dealer_fee" value="<?= $dealer_fee ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $dealer_fee ? number_format($dealer_fee) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">업체 판매 단가</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="company_unit_price" value="<?= $company_unit_price ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $company_unit_price ? number_format($company_unit_price) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">업체 매출금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="company_amount" value="<?= $company_amount ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $company_amount ? number_format($company_amount) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">세금계산서 금액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="tax_invoice_amount" value="<?= $tax_invoice_amount ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $tax_invoice_amount ? number_format($tax_invoice_amount) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">계산서 차액</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <input type="number" name="tax_diff" value="<?= $tax_diff ?>" class="form-control form-control-sm text-end" placeholder="0">
                                    <?php else: ?>
                                        <div class="form-control-plaintext text-end"><?= $tax_diff ? number_format($tax_diff) : '' ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small">입금 여부</label>
                                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                        <select name="is_paid" class="form-select form-select-sm">
                                            <option value="">선택</option>
                                            <option value="Y" <?= ($is_paid == 'Y') ? 'selected' : '' ?>>입금완료</option>
                                            <option value="N" <?= ($is_paid == 'N') ? 'selected' : '' ?>>미입금</option>
                                        </select>
                                    <?php else: ?>
                                        <div class="form-control-plaintext"><?= ($is_paid == 'Y') ? '입금완료' : (($is_paid == 'N') ? '미입금' : '-') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
    </div>
    

    </div>
    </div>
</div>
</form>

<script>
    // 전역 함수들 (스코프 문제 해결)
let itemRowCount = <?= max(1, count($items)) ?>;
let costRowCount = <?= max(1, count($other_costs)) ?>;

// 견적서에서 전달된 본드 가격과 수량 (자동산출과 상관없이 사용)
let estimateBondPrice = <?= $estimate_bond_price ?? 5000 ?>;
let estimateBondQuantity = <?= $estimate_bond_quantity ?? 1 ?>;

// Select2 초기화 함수
function initializeSelect2(selector = '.product-select') {
    $(selector).select2({
        placeholder: '상품을 선택하세요',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "검색 결과가 없습니다.";
            },
            searching: function() {
                return "검색 중...";
            }
        }
    });
}

// 알파벳과 숫자가 혼합된 문자열을 자연스럽게 정렬하는 함수
function naturalSort(a, b) {
    // 문자열을 알파벳과 숫자 부분으로 분리
    function splitString(str) {
        return str.match(/[a-zA-Z]+|\d+/g) || [];
    }
    
    var aParts = splitString(a);
    var bParts = splitString(b);
    
    var maxLength = Math.max(aParts.length, bParts.length);
    
    for (var i = 0; i < maxLength; i++) {
        var aPart = aParts[i] || '';
        var bPart = bParts[i] || '';
        
        // 둘 다 숫자인 경우 숫자로 비교
        if (!isNaN(aPart) && !isNaN(bPart)) {
            var aNum = parseInt(aPart);
            var bNum = parseInt(bPart);
            if (aNum !== bNum) {
                return aNum - bNum;
            }
        } else {
            // 문자열 비교 (대소문자 구분 없이)
            var comparison = aPart.toLowerCase().localeCompare(bPart.toLowerCase());
            if (comparison !== 0) {
                return comparison;
            }
        }
    }    
    return 0;
}

// 상품 옵션을 동적으로 가져와서 select 요소에 채우는 함수
function populateProductOptions(selectElement, callback) {
    // 현재 선택된 값 저장
    const initialValue = selectElement.data('initial-value') || selectElement.val();
    
    $.ajax({
        url: 'get_products.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // 기존 옵션 제거 (첫 번째 옵션 제외)
            selectElement.find('option:not(:first)').remove();
            
            // 상품 데이터를 prodcode 기준으로 자연스럽게 정렬
            data.sort(function(a, b) {
                return naturalSort(a.prodcode, b.prodcode);
            });
            
            data.forEach(function(product) {
                var option = $('<option>')
                    .val(product.prodcode)
                    .attr('data-spec', product.type)
                    .attr('data-size', product.size)
                    .attr('data-thickness', product.thickness)
                    .attr('data-area', product.area)
                    .attr('data-unit-price', product.dist_price_per_m2)
                    .text(product.prodcode + ' - ' + product.texture_kor + ' ' + product.design_kor + ' (' + product.texture_eng + ' ' + product.design_eng + ')');
                selectElement.append(option);
            });
            
            // Select2 업데이트
            if (selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2('destroy');
            }
            
            // Select2 재초기화
            selectElement.select2({
                placeholder: '상품을 선택하세요',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "검색 결과가 없습니다.";
                    },
                    searching: function() {
                        return "검색 중...";
                    }
                }
            });
            
            // Select2 초기화 완료 후 이전에 선택된 값이 있으면 다시 설정
            if (initialValue) {
                // 약간의 지연을 두어 Select2가 완전히 초기화되도록 함
                setTimeout(function() {
                    selectElement.val(initialValue).trigger('change');
                    // 콜백 함수가 있으면 실행 (Select2 값 설정 후)
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                }, 50);
            }
            
            // 콜백 함수가 있으면 실행
            if (callback && typeof callback === 'function') {
                // If no initialValue, execute callback immediately
                if (!initialValue) { // Only call if initialValue was not handled by setTimeout
                    callback();
                }
            }
        },
        error: function() {
            console.error('Failed to load product options');
            // 에러 발생 시에도 콜백 실행
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
}

// 상품 행 추가 (특정 행 뒤에)
function addItemRowAfter(rowIndex) {
    // 현재 행들의 개수를 다시 계산
    const currentRowCount = $('.item-row').length;
    const newRowIndex = currentRowCount;
    
    const newRowHtml = `
        <tr class="item-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addItemRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyItemRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteItemRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td style="text-align: left;">
                <select name="items[${newRowIndex}][product_code]" class="form-select form-select-sm product-select" data-row="${newRowIndex}" style="text-align: left;">
                    <option value="">상품을 선택하세요</option>
                </select>
            </td>
            <td><input type="text" name="items[${newRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" readonly></td>
            <td><input type="number" name="items[${newRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="1"></td>
            <td><input type="text" name="items[${newRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" oninput="inputNumber(this)"></td>
            <td class="text-end supply-amount">0</td>
            <td class="text-end tax-amount">0</td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountItemRowAfter(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>                        
        </tr>
    `;
    
    // 지정된 행 뒤에 새 행 삽입
    const targetRow = $(`.item-row[data-row="${rowIndex}"]`);
    targetRow.after(newRowHtml);
    
    // 새로 추가된 행의 상품 옵션 채우기 (Select2 초기화 포함)
    const newSelectElement = targetRow.next().find('.product-select');
    const newRowElement = targetRow.next();
    
    // 새 행에 초기 로딩 플래그 설정 (새로 추가된 행이므로 상품 선택 시 단가표 기본값 사용)
    newRowElement.data('initial-load', false);
    
    populateProductOptions(newSelectElement);
    
    updateItemRowNumbers();
    autoResizeTableColumns();
    alertToast('상품 행 추가');
}

// 상품 행 복사
function copyItemRow(row) {
    var originalRow = $('.item-row[data-row="' + row + '"]');
    // 현재 행들의 개수를 다시 계산
    const currentRowCount = $('.item-row').length;
    var newRowIndex = currentRowCount;
    
    // 소스 행의 데이터 복사
    const productCode = originalRow.find('select[name*="[product_code]"]').val();
    const specification = originalRow.find('input[name*="[specification]"]').val();
    const size = originalRow.find('input[name*="[size]"]').val();
    const quantity = originalRow.find('input[name*="[quantity]"]').val();
    const area = originalRow.find('input[name*="[area]"]').val();
    const unitPrice = originalRow.find('input[name*="[unit_price]"]').val().replace(/,/g, '');
    const remarks = originalRow.find('input[name*="[remarks]"]').val();
    
    var newRowHtml = `
        <tr class="item-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addItemRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyItemRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteItemRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td style="text-align: left;">
                <select name="items[${newRowIndex}][product_code]" class="form-select form-select-sm product-select" data-row="${newRowIndex}" style="text-align: left;">
                    <option value="">상품을 선택하세요</option>
                </select>
            </td>
            <td><input type="text" name="items[${newRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="${specification}" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="${size}" readonly></td>
            <td><input type="number" name="items[${newRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${quantity}"></td>
            <td><input type="text" name="items[${newRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="${area}" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="${unitPrice && unitPrice !== '' ? Number(unitPrice).toLocaleString() : ''}" oninput="inputNumber(this)"></td>
            <td class="text-end supply-amount">0</td>
            <td class="text-end tax-amount">0</td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountItemRowAfter(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>                        
        </tr>
    `;
    
    originalRow.after(newRowHtml);
    
    // 새로 추가된 행의 상품 옵션 채우기 (Select2 초기화 포함)
    const newRowElement = originalRow.next();
    const newSelectElement = newRowElement.find('.product-select');
    
    // 복사된 행에 초기 로딩 플래그 설정 (복사된 행이므로 기존 단가 유지)
    newRowElement.data('initial-load', true);
    
    populateProductOptions(newSelectElement, function() {
        // 복사된 데이터 설정 (Select2 초기화 완료 후)
        if (productCode) {
            // 복사된 단가를 임시로 저장
            const copiedUnitPrice = unitPrice;
            
            newRowElement.find('.product-select').val(productCode).trigger('change');
            
            // 상품 선택 후 복사된 단가로 복원
            setTimeout(function() {
                if (copiedUnitPrice && copiedUnitPrice !== '') {
                    const cleanUnitPrice = copiedUnitPrice.toString().replace(/,/g, '');
                    const unitPriceVal = parseFloat(cleanUnitPrice) || 0;
                    newRowElement.find('.unit-price-input').val(unitPriceVal.toLocaleString());
                    
                    // 금액 재계산
                    const quantityVal = parseFloat(quantity) || 0;
                    const size = newRowElement.find('.size-input').val();
                    
                    // 실제 면적 계산
                    let actualArea = 0;
                    if (size && size.trim() !== '') {
                        if (size.includes('*')) {
                            const sizeParts = size.split('*');
                            if (sizeParts.length >= 2) {
                                const width = parseFloat(sizeParts[0]) || 0;
                                const height = parseFloat(sizeParts[1]) || 0;
                                actualArea = (width * height) / 1000000;
                            }
                        } else if (size.includes('×')) {
                            const sizeParts = size.split('×');
                            if (sizeParts.length >= 2) {
                                const width = parseFloat(sizeParts[0]) || 0;
                                const height = parseFloat(sizeParts[1]) || 0;
                                actualArea = (width * height) / 1000000;
                            }
                        } else {
                            const singleSize = parseFloat(size) || 0;
                            actualArea = (singleSize * singleSize) / 1000000;
                        }
                    }
                    
                    const totalArea = quantityVal * actualArea;
                    newRowElement.find('.area-input').val(totalArea.toFixed(2));
                    
                    const supplyAmount = totalArea * unitPriceVal;
                    const taxAmount = supplyAmount * 0.1;
                    
                    newRowElement.find('.supply-amount').text(supplyAmount.toLocaleString());
                    newRowElement.find('.tax-amount').text(taxAmount.toLocaleString());
                }
            }, 100);
        }
    });
    
    // 금액 계산 - NaN 방지
    const quantityVal = parseFloat(quantity) || 0;
    let unitPriceVal = 0;
    
    // unitPrice가 숫자 형식인지 확인하고 안전하게 변환
    if (unitPrice && unitPrice !== '') {
        // 쉼표 제거 후 숫자로 변환
        const cleanUnitPrice = unitPrice.toString().replace(/,/g, '');
        unitPriceVal = parseFloat(cleanUnitPrice) || 0;
    }
    
                    const supplyAmount = actualArea * unitPriceVal;
    const taxAmount = supplyAmount * 0.1;
    
    newRowElement.find('.supply-amount').text('' + supplyAmount.toLocaleString());
    newRowElement.find('.tax-amount').text('' + taxAmount.toLocaleString());
    
    updateItemRowNumbers();
    autoResizeTableColumns();
    alertToast('상품 행 복사');
}

// 상품 행 삭제
function deleteItemRow(row) {
    if($('.item-row').length > 1) {
        $('.item-row[data-row="' + row + '"]').remove();
        updateItemRowNumbers();
        autoResizeTableColumns();
    }
    alertToast('상품 행 삭제');
}

// 상품 행 번호 업데이트
function updateItemRowNumbers() {
    $('.item-row').each(function(index) {
        $(this).attr('data-row', index);
        $(this).find('td:first span').text(index + 1);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="addItemRowAfter"]').attr('onclick', `addItemRowAfter(${index})`);
        $(this).find('button[onclick*="copyItemRow"]').attr('onclick', `copyItemRow(${index})`);
        $(this).find('button[onclick*="deleteItemRow"]').attr('onclick', `deleteItemRow(${index})`);
        
        $(this).find('input, select').each(function() {
            var name = $(this).attr('name');
            if(name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            }
        });
    });
}

// 메인 상품 금액 계산
function calculateItemAmount(row) {
    console.clear();
    console.log('row:', row);
    console.log('calculateItemAmount 호출됨');
    console.log('row type:', typeof row, 'row value:', row);
    
    if (row === undefined || row === null) {
        console.error('Row is undefined or null, cannot calculate item amount');
        return;
    }
    
    var quantity = parseFloat($('input[name="items[' + row + '][quantity]"]').val()) || 0;
    var unitPrice = parseFloat($('input[name="items[' + row + '][unit_price]"]').val().replace(/,/g, '')) || 0;
    var size = $('input[name="items[' + row + '][size]"]').val();    
    console.log('size:', size);
    console.log('quantity:', quantity);
    console.log('unitPrice raw value:', $('input[name="items[' + row + '][unit_price]"]').val());
    console.log('unitPrice parsed:', unitPrice);
    
    // 실제 면적 계산 (규격에서 면적 추출)
    let actualArea = 0;
    if (size && size.trim() !== '') {
        if (size.includes('*')) {
            const sizeParts = size.split('*');
            if (sizeParts.length >= 2) {
                const width = parseFloat(sizeParts[0]) || 0;
                const height = parseFloat(sizeParts[1]) || 0;
                actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                console.log('면적 계산 - size:', size, 'width:', width, 'height:', height, 'actualArea:', actualArea);
            }
        } else if (size.includes('×')) {
            const sizeParts = size.split('×');
            if (sizeParts.length >= 2) {
                const width = parseFloat(sizeParts[0]) || 0;
                const height = parseFloat(sizeParts[1]) || 0;
                actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                console.log('면적 계산 - size:', size, 'width:', width, 'height:', height, 'actualArea:', actualArea);
            }
        } else {
            // 단일 숫자인 경우 (가정: 정사각형)
            const singleSize = parseFloat(size) || 0;
            actualArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
            console.log('면적 계산 - size:', size, 'singleSize:', singleSize, 'actualArea:', actualArea);
        }
    } else {
        console.log('면적 계산 - size 필드가 비어있음:', size);
    }
    
    // m² 열 업데이트 (수량 × 실제면적)
    const totalArea = quantity * actualArea;
    $('input[name="items[' + row + '][area]"]').val(totalArea.toFixed(2));
    
    var supplyAmount = totalArea * unitPrice;
    var taxAmount = supplyAmount * 0.1;

    console.log('size:', size, 'actualArea:', actualArea, 'quantity:', quantity, 'totalArea:', totalArea, 'unitPrice:', unitPrice, 'supplyAmount:', supplyAmount);
    console.log('supplyAmount calculated:', supplyAmount, 'taxAmount calculated:', taxAmount);
    
    $('.item-row[data-row="' + row + '"] .supply-amount').text('' + supplyAmount.toLocaleString());
    $('.item-row[data-row="' + row + '"] .tax-amount').text('' + taxAmount.toLocaleString());
    
    console.log('Updated supply amount element:', $('.item-row[data-row="' + row + '"] .supply-amount').text());
    console.log('Updated tax amount element:', $('.item-row[data-row="' + row + '"] .tax-amount').text());

    const etcAutoChecked = $('#etc_autocheck').is(':checked') || $('#etc_autocheck').val() === '1';
    if (etcAutoChecked) {
        console.log('자동계산이 체크되면 기타비용 계산해줌');
        calculateOtherCostsFromProducts(true);
    }

}

// 합계 업데이트 함수 updateTotals함수
function updateTotals() {
    console.log('updateTotals 최종합계 계산 호출됨');
    let totalSupply = 0;
    let totalTax = 0;
    let otherCostsSupply = 0;
    let otherCostsTax = 0;
    let discountTotalSupply = 0;
    let discountTotalTax = 0;
    let discountOtherCostsTotalSupply = 0;
    let discountOtherCostsTotalTax = 0;
    
    // 상품 합계 계산
    $('.item-row').each(function() {
        const supplyText = $(this).find('.supply-amount').text();
        const taxText = $(this).find('.tax-amount').text();
        totalSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        totalTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    console.log('상품 합계 - totalSupply:', totalSupply, 'totalTax:', totalTax);
    
    // 상품 소계 업데이트
    $('#totalSupply').text('' + totalSupply.toLocaleString());
    $('#totalTax').text('' + totalTax.toLocaleString());

    // 기타비용 합계 계산
    $('.cost-row').each(function() {
        const supplyText = $(this).find('.cost-supply-amount').val();
        const taxText = $(this).find('.cost-tax-amount').val();
        otherCostsSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        otherCostsTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    console.log('기타비용 합계 - otherCostsSupply:', otherCostsSupply, 'otherCostsTax:', otherCostsTax);
    
    // 기타비용 소계 업데이트    
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
    
    // 할인 상품 차감    
    $('.discount-item-row').each(function() {
        // input 값에서 콤마(,)를 제거하고 숫자로 변환
        const supplyText = ($(this).find('.discount-item-supply-amount').val() || '').replace(/,/g, '');
        const taxText = ($(this).find('.discount-item-tax-amount').val() || '').replace(/,/g, '');
        // NaN 방지: parseFloat 결과가 NaN이면 0 처리
        const supplyValue = parseFloat(supplyText);
        const taxValue = parseFloat(taxText);
        discountTotalSupply -= isNaN(supplyValue) ? 0 : supplyValue;
        discountTotalTax -= isNaN(taxValue) ? 0 : taxValue;
    });
    console.log('할인 상품 차감 - discountTotalSupply:', discountTotalSupply, 'discountTotalTax:', discountTotalTax);

    // 할인 기타비용 차감
    $('.discount-cost-row').each(function() {
        const supplyText = $(this).find('.discount-cost-supply-amount').val();
        const taxText = $(this).find('.discount-cost-tax-amount').val();
        discountOtherCostsTotalSupply -= parseFloat((supplyText || '').replace(/,/g, '')) || 0;
        discountOtherCostsTotalTax -= parseFloat((taxText || '').replace(/,/g, '')) || 0;
    });

    console.log('할인 기타비용 차감 - discountOtherCostsTotalSupply:', discountOtherCostsTotalSupply, 'discountOtherCostsTotalTax:', discountOtherCostsTotalTax);
    
    // 상품 소계 업데이트
    $('#totalSupply').text('' + totalSupply.toLocaleString());
    $('#totalTax').text('' + totalTax.toLocaleString());
    
    // 기타비용 소계 업데이트    
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
    
    // 할인 차감 소계 업데이트
    $('#discountTotalSupply').text('' + discountTotalSupply.toLocaleString());
    $('#discountTotalTax').text('' + discountTotalTax.toLocaleString());
    
    // 할인 기타비용 차감 소계 업데이트
    $('#discountOtherCostsTotalSupply').text('' + discountOtherCostsTotalSupply.toLocaleString());
    $('#discountOtherCostsTotalTax').text('' + discountOtherCostsTotalTax.toLocaleString());
    
    // 전체 합계 (상품 + 기타비용 - 할인 상품 - 할인 기타비용)
    const grandTotalSupply = totalSupply + otherCostsSupply + discountTotalSupply + discountOtherCostsTotalSupply;
    const grandTotalTax = totalTax + otherCostsTax + discountTotalTax + discountOtherCostsTotalTax;
    
    // hidden input 업데이트
    $('input[name="total_ex_vat"]').val(grandTotalSupply);
    $('input[name="total_inc_vat"]').val(grandTotalSupply + grandTotalTax);
    
    // 합계 테이블 업데이트
    $('.total-ex-vat').text('(' + grandTotalSupply.toLocaleString() + ')');
    $('.total-inc-vat').text('(' + (grandTotalSupply + grandTotalTax).toLocaleString() + ')');
    
    console.log('최종 합계 - grandTotalSupply:', grandTotalSupply, 'grandTotalTax:', grandTotalTax, 'total:', grandTotalSupply + grandTotalTax);
}


// 행 번호 업데이트 함수
function updateRowNumbers() {
        $('.item-row').each(function(index) {
            $(this).find('td:first span').text(index + 1);
            $(this).attr('data-row', index);
            
            // 버튼의 onclick 속성 업데이트
            const buttons = $(this).find('.btn-group button');
            buttons.eq(0).attr('onclick', `addRowAfter(${index})`);
            buttons.eq(1).attr('onclick', `copyRow(${index})`);
            buttons.eq(2).attr('onclick', `deleteRow(${index})`);
        });
    }
    
// 기타비용 행 추가 함수
function addCostRowAfter(rowIndex) {
    const newRowIndex = costRowCount;
    const newRow = `
        <tr class="cost-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">                   
                    <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>           
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>                                             
                    </div>
                </div>
            </td>
            <td><input type="text" name="other_costs[${newRowIndex}][category]" class="form-control form-control-sm" placeholder="구분"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][item]" class="form-control form-control-sm text-start" placeholder="항목"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위"></td>
            <td><input type="number" name="other_costs[${newRowIndex}][quantity]" class="form-control form-control-sm text-end cost-quantity-input" placeholder="수량" step="1"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end cost-unit-price-input" placeholder="단가" ></td>
            <td><input type="text" name="other_costs[${newRowIndex}][supply_amount]" class="form-control form-control-sm text-end cost-supply-amount" value="0" readonly></td>
            <td><input type="text" name="other_costs[${newRowIndex}][tax_amount]" class="form-control form-control-sm text-end cost-tax-amount" value="0" readonly></td>
            <td><input type="text" name="other_costs[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountCostRow(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>            
        </tr>
    `;
    
    // 지정된 행 뒤에 새 행 삽입
    const targetRow = $(`.cost-row[data-row="${rowIndex}"]`);
    targetRow.after(newRow);
    
    costRowCount++;
    updateCostRowNumbers();
    updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
    updateTotals();
}

// 기타비용 행 복사 함수
function copyCostRow(rowIndex) {
    const sourceRow = $(`.cost-row[data-row="${rowIndex}"]`);
    const newRowIndex = costRowCount;
    
    // 소스 행의 데이터 복사
    const category = sourceRow.find('input[name*="[category]"]').val();
    const item = sourceRow.find('input[name*="[item]"]').val();
    const unit = sourceRow.find('input[name*="[unit]"]').val();
    const quantity = sourceRow.find('.cost-quantity-input').val();
    const unitPrice = sourceRow.find('.cost-unit-price-input').val();
    const supplyAmount = sourceRow.find('.cost-supply-amount').val();
    const taxAmount = sourceRow.find('.cost-tax-amount').val();
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    const newRow = `
        <tr class="cost-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">                    
                    <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(${newRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td><input type="text" name="other_costs[${newRowIndex}][category]" class="form-control form-control-sm" placeholder="구분" value="${category}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][item]" class="form-control form-control-sm" placeholder="항목" value="${item}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="${unit}"></td>
            <td><input type="number" name="other_costs[${newRowIndex}][quantity]" class="form-control form-control-sm cost-quantity-input text-end" placeholder="수량" step="1" value="${quantity}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit_price]" class="form-control form-control-sm cost-unit-price-input text-end" placeholder="단가" value="${unitPrice}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][supply_amount]" class="form-control form-control-sm cost-supply-amount text-end" readonly value="${supplyAmount}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][tax_amount]" class="form-control form-control-sm cost-tax-amount text-end" readonly value="${taxAmount}"></td> 
            <td><input type="text" name="other_costs[${newRowIndex}][remarks]" class="form-control form-control-sm text-start" placeholder="비고" value="${remarks}"></td>
            <td>
                <!-- 할인버튼 추가 -->
                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountCostRow(${newRowIndex})" title="할인 행 추가">
                    할인
                </button>
            </td>              
        </tr>
    `;        
    // 소스 행 뒤에 새 행 삽입
    sourceRow.after(newRow);
    
    // 새로 추가된 행의 금액 계산
    const newRowElement = sourceRow.next();
    const quantityVal = parseFloat(quantity) || 0;
    const unitPriceVal = parseFloat(unitPrice.toString().replace(/,/g, '')) || 0;
    const supplyAmountVal = quantityVal * unitPriceVal;
    const taxAmountVal = supplyAmountVal * 0.1;
    
    newRowElement.find('.discount-cost-supply-amount').val('' + supplyAmountVal.toLocaleString());
    newRowElement.find('.discount-cost-tax-amount').val('' + taxAmountVal.toLocaleString());
    
    costRowCount++;
    updateCostRowNumbers();
    updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
    updateTotals();
}

// 기타비용 소계 즉시 업데이트 함수
function updateOtherCostsSubtotal() {
    console.log('기타비용 소계 만들기 updateOtherCostsSubtotal 실행');
    let otherCostsSupply = 0;
    let otherCostsTax = 0;
    
    // 기타비용 합계 계산
    $('.cost-row').each(function() {
        // 공급가액 input에서 값 가져오기
        const supplyText = ($(this).find('.cost-supply-amount').val() || '0').replace(/,/g, '');
        const taxText = ($(this).find('.cost-tax-amount').val() || '0').replace(/,/g, '');
        
        // 쉼표 제거 후 숫자로 변환하여 합계에 더하기
        otherCostsSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        otherCostsTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    
    // 기타비용 소계 업데이트
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
}

// 기타비용 행 삭제 함수
function deleteCostRow(rowIndex) {
    console.log('deleteCostRow', rowIndex);
    const row = $(`.cost-row[data-row="${rowIndex}"]`);
    if ($('.cost-row').length > 1) {
        row.remove();
        updateCostRowNumbers();
        updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
        updateTotals();
        
        // mainTable 변화 감지하여 기타비용 테이블 연동
        // setTimeout(function() {
        //     initializeOtherCostsTable();
        // }, 100);
    } else {
        alert('최소 1개의 행은 유지해야 합니다.');
    }
    alertToast('기타비용 행 삭제');
}

// 할인 상품 행 추가 (기존 상품에서 복사)
function addDiscountItemRowAfter(sourceRowIndex) {
    // view 모드에서는 실행하지 않음
    let mode = $('#mode').val();
    if(mode === 'view') return;
    
    // 소스 상품 행에서 데이터 가져오기
    const sourceRow = $('.item-row[data-row="' + sourceRowIndex + '"]');
    if (sourceRow.length === 0) {
        alertToast('원본 상품 행을 찾을 수 없습니다.');
        return;
    }
    
    // 소스 행의 데이터 복사
    const productCode = sourceRow.find('select[name*="[product_code]"]').val();
    const specification = sourceRow.find('input[name*="[specification]"]').val();
    const size = sourceRow.find('input[name*="[size]"]').val();
    const quantity = sourceRow.find('input[name*="[quantity]"]').val();
    const area = sourceRow.find('input[name*="[area]"]').val();
    const unitPrice = sourceRow.find('input[name*="[unit_price]"]').val().replace(/,/g, '');
    const supplyAmount = sourceRow.find('.supply-amount').text().replace(/,/g, '');
    const taxAmount = sourceRow.find('.tax-amount').text().replace(/,/g, '');
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    // 상품명 생성 (상품 코드 기반)
    let codeString = productCode;
    if (productCode) {
        // 상품 코드에서 상품명 추출 시도
        const selectedOption = sourceRow.find('select[name*="[product_code]"] option:selected');
        if (selectedOption.length > 0) {
            const optionText = selectedOption.text();
            codeString = productCode + ' - ' + optionText.split(' - ')[1].split(' (')[0];
        }
    }
    
    // 할인 상품 테이블에 새 행 추가
    const currentDiscountRowCount = $('.discount-item-row').length;
    const newDiscountRowIndex = currentDiscountRowCount;
    
    const newDiscountRowHtml = `
        <tr class="discount-item-row" data-row="${newDiscountRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newDiscountRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountItemRow(${newDiscountRowIndex})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td style="text-align: left;">
                <input type="hidden" name="discount_items[${newDiscountRowIndex}][product_code]" class="form-control form-control-sm" data-row="${newDiscountRowIndex}" style="text-align: left;" value="${productCode}" readonly>                                
                <input type="text" name="discount_items[${newDiscountRowIndex}][code_string]" class="form-control form-control-sm" data-row="${newDiscountRowIndex}" style="text-align: left;" value="${codeString}" readonly>                                
            </td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="${specification}" readonly></td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="${size}" readonly></td>
            <td><input type="number" name="discount_items[${newDiscountRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${quantity}" readonly></td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="${area}" readonly></td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="${unitPrice}" readonly></td>
            <td class="text-end supply-amount">
                <input type="text" name="discount_items[${newDiscountRowIndex}][supply_amount]" class="form-control form-control-sm text-end discount-item-supply-amount" placeholder="공급가액" value="${parseFloat(supplyAmount).toLocaleString()}" readonly>                                                                
            </td>
            <td class="text-end tax-amount">
                <input type="text" name="discount_items[${newDiscountRowIndex}][tax_amount]" class="form-control form-control-sm text-end discount-item-tax-amount" placeholder="세액" value="${parseFloat(taxAmount).toLocaleString()}" readonly>                                                                
            </td>
            <td><input type="text" name="discount_items[${newDiscountRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
        </tr>
    `;
    
    // 할인 상품 테이블의 tbody에 새 행 추가
    $('#discountItemsTableBody').append(newDiscountRowHtml);
    
    // 할인 상품 행 번호 업데이트
    updateDiscountItemRowNumbers();
    
    // 할인 상품 합계 계산
    calculateDiscountTotals();

    updateTotals();
    
    alertToast('할인 상품이 추가되었습니다.');
}

// 할인 상품 행 삭제
function deleteDiscountItemRow(row) {
    // view 모드에서는 실행하지 않음
    if('<?= $mode ?>' === 'view') return;
    
    $('.discount-item-row[data-row="' + row + '"]').remove();
    updateDiscountItemRowNumbers();
    calculateDiscountTotals();
    alertToast('할인 상품 행 삭제');
}

// 할인 기타비용 행 삭제
function deleteDiscountCostRow(row) {
    // view 모드에서는 실행하지 않음
    if('<?= $mode ?>' === 'view') return;
    
    $('.discount-cost-row[data-row="' + row + '"]').remove();
    updateDiscountCostRowNumbers();
    calculateDiscountCostTotals();
    alertToast('할인 기타비용 행 삭제');
}

// 할인 상품 행 번호 업데이트
function updateDiscountItemRowNumbers() {
    // view 모드에서는 버튼 업데이트를 하지 않음
    if($("#mode").val() === 'view') return;
    
    $('.discount-item-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="deleteDiscountItemRow"]').attr('onclick', `deleteDiscountItemRow(${index})`);
        
        // input 필드의 name 속성 업데이트
        $(this).find('input[name*="[product_code]"]').attr('name', `discount_items[${index}][product_code]`);
        $(this).find('input[name*="[code_string]"]').attr('name', `discount_items[${index}][code_string]`);
        $(this).find('input[name*="[specification]"]').attr('name', `discount_items[${index}][specification]`);
        $(this).find('input[name*="[size]"]').attr('name', `discount_items[${index}][size]`);
        $(this).find('input[name*="[quantity]"]').attr('name', `discount_items[${index}][quantity]`);
        $(this).find('input[name*="[area]"]').attr('name', `discount_items[${index}][area]`);
        $(this).find('input[name*="[unit_price]"]').attr('name', `discount_items[${index}][unit_price]`);
        $(this).find('input[name*="[supply_amount]"]').attr('name', `discount_items[${index}][supply_amount]`);
        $(this).find('input[name*="[tax_amount]"]').attr('name', `discount_items[${index}][tax_amount]`);
        $(this).find('input[name*="[remarks]"]').attr('name', `discount_items[${index}][remarks]`);
        
        // 행 번호 표시 업데이트
        $(this).find('td:first-child span').text(index + 1);
    });
}

// 할인 상품 합계 계산
function calculateDiscountTotals() {
    let totalSupply = 0;
    let totalTax = 0;
    
    $('.discount-item-row input.supply-amount-input').each(function() {
        const supplyAmount = parseFloat($(this).val().replace(/[,]/g, '')) || 0;
        totalSupply -= supplyAmount;
    });
    
    $('.discount-item-row input.tax-amount-input').each(function() {
        const taxAmount = parseFloat($(this).val().replace(/[,]/g, '')) || 0;
        totalTax -= taxAmount;
    });
    
    // 할인 상품 테이블의 소계 업데이트
    $('#discountTotalSupply').text('' + totalSupply.toLocaleString());
    $('#discountTotalTax').text('' + totalTax.toLocaleString());

    updateTotals();
}

// 할인 기타 비용 행자동계산 함수
function calculateDiscountCostAmount(row) {
    var quantity = parseFloat($('input[name="discount_other_costs[' + row + '][quantity]"]').val()) || 0;
    var unitPrice = parseFloat($('input[name="discount_other_costs[' + row + '][unit_price]"]').val().replace(/,/g, '')) || 0;
    
    var supplyAmount = quantity * unitPrice;
    var taxAmount = supplyAmount * 0.1;
    $('input[name="discount_other_costs[' + row + '][supply_amount]"]').val(supplyAmount.toLocaleString());
    $('input[name="discount_other_costs[' + row + '][tax_amount]"]').val(taxAmount.toLocaleString());
    
    // 합계 업데이트
    updateTotals();
}
// 할인 기타 비용 행 추가 (기존 기타 비용에서 복사)
function addDiscountCostRow(sourceRowIndex) {
    // view 모드에서는 실행하지 않음
    const mode = $('#mode').val();
    if(mode === 'view') return;

    console.log('addDiscountCostRow 할인 index', sourceRowIndex);
    
    // 소스 기타 비용 행에서 데이터 가져오기
    const sourceRow = $('.cost-row[data-row="' + sourceRowIndex + '"]');
    if (sourceRow.length === 0) {
        alertToast('원본 기타 비용 행을 찾을 수 없습니다.');
        return;
    }
    
    // 소스 행의 데이터 복사
    const category = sourceRow.find('input[name*="[category]"]').val();
    const item = sourceRow.find('input[name*="[item]"]').val();
    const unit = sourceRow.find('input[name*="[unit]"]').val();
    const quantity = sourceRow.find('input[name*="[quantity]"]').val();
    const unitPrice = sourceRow.find('input[name*="[unit_price]"]').val();
    const supplyAmount = sourceRow.find('input[name*="[supply_amount]"]').val();
    const taxAmount = sourceRow.find('input[name*="[tax_amount]"]').val();
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    // 할인 기타 비용 테이블에 새 행 추가
    const currentDiscountCostRowCount = $('.discount-cost-row').length;
    const newDiscountCostRowIndex = currentDiscountCostRowCount;
    
    const newDiscountCostRowHtml = `
        <tr class="discount-cost-row" data-row="${newDiscountCostRowIndex}">
            <td>
                <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteDiscountCostRow(${newDiscountCostRowIndex})" title="행 삭제">
                        <i class="bi bi-dash"></i>
                    </button>
                </div>
            </td>
            <td>
                <input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][category]" class="form-control form-control-sm ms-1" placeholder="구분" value="${category}" readonly>
            </td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][item]" class="form-control form-control-sm text-start" placeholder="항목" value="${item}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="${unit}" readonly></td>
            <td><input type="number" name="discount_other_costs[${newDiscountCostRowIndex}][quantity]" class="form-control form-control-sm text-end discount-cost-quantity-input" placeholder="수량" step="1" value="${quantity}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][unit_price]" class="form-control form-control-sm text-end discount-cost-unit-price-input" placeholder="단가" value="${unitPrice}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][supply_amount]" class="form-control form-control-sm text-end discount-cost-supply-amount" value="${supplyAmount}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][tax_amount]" class="form-control form-control-sm text-end discount-cost-tax-amount" value="${taxAmount}" readonly></td>
            <td><input type="text" name="discount_other_costs[${newDiscountCostRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
        </tr>
    `;
    
    // 할인 기타 비용 테이블의 tbody에 새 행 추가
    $('#discountOtherCostsTableBody').append(newDiscountCostRowHtml);
    
    // 할인 기타 비용 행 번호 업데이트
    updateDiscountCostRowNumbers();
    
    // 행에 대한 합계 계산
    calculateDiscountCostAmount(newDiscountCostRowIndex);
    // 할인 기타 비용 합계 계산
    calculateDiscountCostTotals();
    
    alertToast('할인 기타 비용이 추가되었습니다.');
}

// 할인 기타 비용 행 삭제
function deleteDiscountCostRow(row) {
    // view 모드에서는 실행하지 않음
    if('<?= $mode ?>' === 'view') return;
    
    $('.discount-cost-row[data-row="' + row + '"]').remove();
    updateDiscountCostRowNumbers();
    calculateDiscountCostTotals();
    updateTotals();
    alertToast('할인 기타 비용 행 삭제');
}

// 할인 기타 비용 행 번호 업데이트
function updateDiscountCostRowNumbers() {
    // view 모드에서는 버튼 업데이트를 하지 않음
    if($("#mode").val() === 'view') return;
    
    $('.discount-cost-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="deleteDiscountCostRow"]').attr('onclick', `deleteDiscountCostRow(${index})`);
        
        // input 필드의 name 속성 업데이트
        $(this).find('input[name*="[category]"]').attr('name', `discount_other_costs[${index}][category]`);
        $(this).find('input[name*="[item]"]').attr('name', `discount_other_costs[${index}][item]`);
        $(this).find('input[name*="[unit]"]').attr('name', `discount_other_costs[${index}][unit]`);
        $(this).find('input[name*="[quantity]"]').attr('name', `discount_other_costs[${index}][quantity]`);
        $(this).find('input[name*="[unit_price]"]').attr('name', `discount_other_costs[${index}][unit_price]`);
        $(this).find('input[name*="[remarks]"]').attr('name', `discount_other_costs[${index}][remarks]`);
    });
}

// 할인 기타 비용 합계 계산
function calculateDiscountCostTotals() {
    let totalSupply = 0;
    let totalTax = 0;
    
    $('.discount-cost-row').each(function() {
        const supplyAmountInput = $(this).find('.discount-cost-supply-amount');
        const taxAmountInput = $(this).find('.discount-cost-tax-amount');
        
        // input 요소가 존재하는지 확인
        if (supplyAmountInput.length && taxAmountInput.length) {
            const supplyAmountText = supplyAmountInput.val() || '0';
            const taxAmountText = taxAmountInput.val() || '0';
            
            const supplyAmount = parseFloat(supplyAmountText.replace(/[,]/g, '')) || 0;
            const taxAmount = parseFloat(taxAmountText.replace(/[,]/g, '')) || 0;
            
            totalSupply -= supplyAmount;
            totalTax -= taxAmount;
        }
    });
    
    // 할인 기타 비용 테이블의 소계 업데이트
    $('#discountOtherCostsTotalSupply').text('' + totalSupply.toLocaleString());
    $('#discountOtherCostsTotalTax').text('' + totalTax.toLocaleString());

    updateTotals();
}

// 기타비용 행 번호 업데이트
function updateCostRowNumbers() {
    // view 모드에서는 버튼 업데이트를 하지 않음
    if($("#mode").val() === 'view') return;

    $('.cost-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 이벤트에서 인덱스 업데이트
        $(this).find('button[onclick*="addCostRowAfter"]').attr('onclick', `addCostRowAfter(${index})`);
        $(this).find('button[onclick*="copyCostRow"]').attr('onclick', `copyCostRow(${index})`);
        $(this).find('button[onclick*="deleteCostRow"]').attr('onclick', `deleteCostRow(${index})`);
        
        $(this).find('input').each(function() {
            var name = $(this).attr('name');
            if(name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            }
        });
    });
}

// 숫자 입력 시 3자리마다 콤마로 자동 포맷팅 (정확한 숫자형 처리)
function inputNumber(input) {
    // 입력값에서 숫자와 소수점만 남김 (음수는 필요시 '-' 추가)
    let value = input.value.replace(/[^0-9.]/g, '');

    // 소수점이 여러 개 들어가는 경우 첫 번째만 남기고 제거
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }

    // 값이 없거나 숫자가 아니면 빈 문자열로 처리
    if (value === '' || isNaN(value)) {
        input.value = '';
        return;
    }

    // 정수와 소수점 분리
    let [intPart, decPart] = value.split('.');
    // 0으로 시작하는 경우 0 유지
    intPart = intPart.replace(/^0+(?=\d)/, '');

    // 3자리마다 콤마 추가
    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    if (typeof decPart !== 'undefined') {
        input.value = intPart + '.' + decPart;
    } else {
        input.value = intPart;
    }
}

// 기타비용 금액 계산
function calculateCostAmount(row) {
    console.log('calculateCostAmount 호출됨');    
    
    var $quantityInput = $('input[name="other_costs[' + row + '][quantity]"]');
    var $unitPriceInput = $('input[name="other_costs[' + row + '][unit_price]"]');
    var $supplyAmountCell = $('.cost-row[data-row="' + row + '"] .cost-supply-amount');
    var $taxAmountCell = $('.cost-row[data-row="' + row + '"] .cost-tax-amount');

    // 해당 요소가 모두 존재할 때만 계산
    if ($quantityInput.length && $unitPriceInput.length && $supplyAmountCell.length && $taxAmountCell.length) {
        var quantity = parseFloat($quantityInput.val()) || 0;
        var unitPrice = parseFloat($unitPriceInput.val().replace(/,/g, '')) || 0;

        var supplyAmount = quantity * unitPrice;
        var taxAmount = supplyAmount * 0.1;

        $supplyAmountCell.text('' + supplyAmount.toLocaleString());
        $taxAmountCell.text('' + taxAmount.toLocaleString());

        // 합계 업데이트
        updateTotals();
    }
}

// 할인 비용 금액 계산
function calculateDiscountItemAmount(row) {
    var $quantityInput = $('input[name="discount_other_costs[' + row + '][quantity]"]');
    var $unitPriceInput = $('input[name="discount_other_costs[' + row + '][unit_price]"]');
    var $supplyAmountCell = $('.discount-cost-row[data-row="' + row + '"] .discount-cost-supply-amount');
    var $taxAmountCell = $('.discount-cost-row[data-row="' + row + '"] .discount-cost-tax-amount');

    // 해당 요소가 모두 존재할 때만 계산
    if ($quantityInput.length && $unitPriceInput.length && $supplyAmountCell.length && $taxAmountCell.length) {
        var quantity = parseFloat($quantityInput.val()) || 0;
        var unitPrice = parseFloat($unitPriceInput.val().replace(/,/g, '')) || 0;

        var supplyAmount = quantity * unitPrice;
        var taxAmount = supplyAmount * 0.1;

        $supplyAmountCell.text('' + supplyAmount.toLocaleString());
        $taxAmountCell.text('' + taxAmount.toLocaleString());

        // 합계 업데이트
        updateTotals();
    }
}


// 상품 데이터 기반으로 기타비용 자동 계산
function calculateOtherCostsFromProducts(forceRecalculate = false) {
    console.clear();
    console.log(' 상품 데이터 기반으로 기타비용 자동 계산 calculateOtherCostsFromProducts 호출됨');    
    
    let totalArea = 0;
    let totalQuantity = 0;
    let bondQuantity = 0;
    
        // 상품 데이터 분석
    $('.item-row').each(function() {
        const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
        const area = parseFloat($(this).find('.area-input').val()) || 0;
        const size = $(this).find('.size-input').val() || '';
        const specification = $(this).find('.specification-input').val() || '';
        
        totalArea += area;
        totalQuantity += quantity;
        
        // 본드 수량 계산 (원래 로직 유지)
        let sizeMultiplier = 2;
        if (specification.includes('2400')) {
            sizeMultiplier = 2;
        } else if (specification.includes('2700') || specification.includes('3000')) {
            sizeMultiplier = 3;
        }
        
        if (size.replace(/\s/g,'') === 'M급' || size.replace(/\s/g,'') === 'L급') {
            sizeMultiplier = 3;
        }
        // console.log('size', sizeMultiplier);
        // console.log('sizeMultiplier', sizeMultiplier);
        bondQuantity += quantity * sizeMultiplier;
    });
    

    // 시공비 제외 체크박스 상태 확인
    const exclude_construction_cost = $('input[name="exclude_construction_cost"]').is(':checked');
    console.log('exclude_construction_cost', exclude_construction_cost);
    // 몰딩 제외 체크박스 상태 확인
    const exclude_molding = $('input[name="exclude_molding"]').is(':checked');
    console.log('exclude_molding', exclude_molding);
    // 기존 기타비용 행들을 모두 제거하고 새로 생성
    const costTableBody = $('#otherCostsTableBody');
    
    // 수동 수정 여부 확인 (강제 재계산이 아닌 경우에만)
    let hasManualModifications = false;
    const etc_autocheck = $('input[name="etc_autocheck"]').is(':checked');
    console.log('etc_autocheck', etc_autocheck);
    
    if (etc_autocheck) {
        console.log('기타비용 자동산출 체크박스 체크됨');
        hasManualModifications = false;
    } else {
        console.log('기타비용 자동산출 체크박스 체크되지 않음');
    }
    
    // 수정 모드에서 기존 데이터가 있는지 확인
    let hasExistingData = false;
    $('.cost-row').each(function() {
        const category = $(this).find('input[name*="[category]"]').val();
        const item = $(this).find('input[name*="[item]"]').val();
        const quantity = $(this).find('.cost-quantity-input').val();
        const unitPrice = $(this).find('.cost-unit-price-input').val();
        
        if (category || item || quantity || unitPrice) {
            hasExistingData = true;
            return false; // break
        }
    });        
       
        // 기존 데이터가 없는 경우에만 수동 수정 플래그 확인
        $('.cost-row').each(function() {            
            // 또는 기존 데이터가 있는지 확인 (카테고리와 품목이 모두 채워져 있고 수량이나 단가가 있는 경우)
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            const quantity = $(this).find('.cost-quantity-input').val();
            const unitPrice = $(this).find('.cost-unit-price-input').val();
            
            // 기존 데이터가 있고, 빈 값이 아닌 경우 수동 수정으로 간주
            if (category && item && (quantity || unitPrice)) {
                // 자동 계산된 기본값 패턴 확인
                const isAutoCalculated = 
                    // 본드: 부자재, 본드, 수량 있음, 단가 5,000
                    (category === '부자재' && item === '본드' && quantity && unitPrice === '5,000') ||
                    // 시공비: 시공비, ㎡당 시공비, 단가 25,000 또는 700,000 (시공비 제외가 아닐 때만)
                    (category === '시공비' && item === '㎡당 시공비' && (unitPrice === '25,000' || unitPrice === '700,000') && !exclude_construction_cost) ||
                    // 몰딩: 부자재, 몰딩, 빈 값들 (몰딩 제외가 아닐 때만)
                    (category === '부자재' && item === '몰딩' && !quantity && !unitPrice && !exclude_molding) ||
                    // 운송비: 운송비, 빈 항목, 비고에 '착불'
                    (category === '운송비' && !item && !quantity && !unitPrice);
                
                if (!isAutoCalculated) {
                    hasManualModifications = true;
                    return false; // break
                }
            }
        });


    console.log('hasManualModifications', hasManualModifications);
    
        // 수동 수정이 있고 강제 재계산이 아닌 경우 기존 데이터 보존
        if (hasManualModifications && !forceRecalculate) {
            console.log('기타비용 자동산출 체크박스 체크되지 않음 또는 수동 수정된 기타비용 데이터가 있어 자동 계산을 건너뜁니다.');
            window.isCalculatingOtherCosts = false;
            return;
        }
    
    // 기존 데이터 백업
    const existingData = [];
    $('.cost-row').each(function() {
        const rowData = {
            category: $(this).find('input[name*="[category]"]').val(),
            item: $(this).find('input[name*="[item]"]').val(),
            unit: $(this).find('input[name*="[unit]"]').val(),
            quantity: $(this).find('.cost-quantity-input').val(),
            unit_price: $(this).find('.cost-unit-price-input').val(),
            amount: $(this).find('.cost-supply-amount').val(),
            tax: $(this).find('.cost-tax-amount').val(),
            remarks: $(this).find('input[name*="[remarks]"]').val()
        };
        existingData.push(rowData);
    });
    
    // 시공비/몰딩 제외에 따라 기존 데이터 필터링
    const filteredExistingData = [];
    existingData.forEach((data, index) => {
        // 시공비 제외시 시공비 행 제외
        if (exclude_construction_cost && data.category === '시공비') {
            return;
        }
        // 몰딩 제외시 몰딩 행 제외
        if (exclude_molding && data.category === '부자재' && data.item === '몰딩') {
            return;
        }
        filteredExistingData.push(data);
    });
    
    // 시공비/몰딩 제외에 따라 행 수 조정 (자동계산 체크와 상관없이 항상 실행)
    let rowCount = 5; // 기본 5행 (본드, 몰딩, 빈행, 시공비, 운송비)
    
    if (exclude_construction_cost) {
        rowCount = 4; // 시공비 제외시 4행 (본드, 몰딩, 빈행, 운송비)
    }
    if (exclude_molding) {
        rowCount = Math.max(1, rowCount - 1); // 몰딩 제외시 1행 감소
    }
    
    // 기존 행 모두 제거
    $('#otherCostsTableBody .cost-row').remove();
    
    // 필요한 행 수만큼 생성
    for (let i = 0; i < rowCount; i++) {
        const newRow = `
            <tr class="cost-row" data-row="${i}">
                <td> 
                    <div class="btn-group btn-group-sm ms-1" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addCostRowAfter(${i})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyCostRow(${i})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>                                                
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteCostRow(${i})" title="행 삭제">
                            <i class="bi bi-dash"></i>
                        </button>                                                
                    </div>      
                </td>
                <td> <input type="text" name="other_costs[${i}][category]" class="form-control">
                <td><input type="text" name="other_costs[${i}][item]" class="form-control"></td>
                <td><input type="text" name="other_costs[${i}][unit]" class="form-control"></td>
                <td><input type="number" name="other_costs[${i}][quantity]" class="form-control cost-quantity-input text-end" step="1"></td>
                <td><input type="text" name="other_costs[${i}][unit_price]" class="form-control cost-unit-price-input text-end" ></td>
                <td><input type="text" name="other_costs[${i}][amount]" class="form-control cost-supply-amount text-end" readonly></td>
                <td><input type="text" name="other_costs[${i}][tax]" class="form-control cost-tax-amount text-end" readonly></td>
                <td><input type="text" name="other_costs[${i}][remarks]" class="form-control"></td>       
                <td> 
                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 30px;  font-size: 10px;" onclick="addDiscountCostRow(${i})" title="할인 행 추가">
                        할인
                    </button>
                </td>
            </tr>
        `;
        costTableBody.append(newRow);
    }
    
    // costRowCount 업데이트
    costRowCount = rowCount;

        
    // 기존 데이터 복원 (자동산출이 체크되지 않은 경우에만)
    if (!etc_autocheck && filteredExistingData.length > 0) {
        $('.cost-row').each(function(index) {
            if (filteredExistingData[index]) {
                const data = filteredExistingData[index];
                $(this).find('input[name*="[category]"]').val(data.category);
                $(this).find('input[name*="[item]"]').val(data.item);
                $(this).find('input[name*="[unit]"]').val(data.unit);
                $(this).find('.cost-quantity-input').val(data.quantity);
                $(this).find('.cost-unit-price-input').val(data.unit_price);
                $(this).find('.cost-supply-amount').val(data.amount);
                $(this).find('.cost-tax-amount').val(data.tax);
                $(this).find('input[name*="[remarks]"]').val(data.remarks);
                
                // 금액 계산
                calculateCostRow($(this));
            }
        });
    }
    
    // 새로 생성된 행들의 수동 수정 플래그 초기화
    $('.cost-row').data('manually-modified', false);

    // 기타비용 행 업데이트 - 기본 텍스트 라벨은 항상 설정
    const updatedCostRows = $('.cost-row');
    console.log('총 기타비용 행 수:', updatedCostRows.length);
    
    let rowIndex = 0;
    updatedCostRows.each(function(index) {
        // console.log(`행 ${index + 1} 업데이트 중...`);
        
        // 몰딩 제외시 2번째 행(몰딩)을 건너뛰기 위한 로직
        let actualRowIndex = rowIndex;
        if (exclude_molding && rowIndex === 1) {
            // 몰딩 제외시 2번째 행을 건너뛰고 다음 행으로
            rowIndex++;
            actualRowIndex = rowIndex;
        }
        
        if (actualRowIndex === 0) {
            // 1행: 부자재, 본드
            console.log('1행: 본드 설정');
            $(this).find('input[name*="[category]"]').val('부자재');
            $(this).find('input[name*="[item]"]').val('본드');
            $(this).find('input[name*="[unit]"]').val('EA');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                // 견적서에서 전달된 본드 가격 사용, 수량은 계산된 값 사용
                const bondPrice = estimateBondPrice || 5000; // 견적서 가격 우선, 없으면 기본값
                const bondQty = Math.round(bondQuantity); // 계산된 수량 사용 (자동계산 시)
                
                $(this).find('.cost-quantity-input').val(bondQty);
                $(this).find('.cost-unit-price-input').val(bondPrice.toLocaleString());
                $(this).find('.cost-supply-amount').val((bondPrice * bondQty).toLocaleString());
                $(this).find('.cost-tax-amount').val((bondPrice * bondQty * 0.1).toLocaleString());
                
                console.log('본드 설정 - 견적서 가격:', bondPrice, '계산된 수량:', bondQty);
            }
            $(this).find('input[name*="[remarks]"]').val('');
        } else if (actualRowIndex === 1 && !exclude_molding) {
            // 2행: 부자재, 몰딩 (몰딩 제외가 아닐 때만)
            console.log('2행: 몰딩 설정');
            $(this).find('input[name*="[category]"]').val('부자재');
            $(this).find('input[name*="[item]"]').val('몰딩');
            $(this).find('input[name*="[unit]"]').val('EA');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                $(this).find('.cost-quantity-input').val('');
                $(this).find('.cost-unit-price-input').val('');
                $(this).find('.cost-supply-amount').val('');
                $(this).find('.cost-tax-amount').val('');
            }
            $(this).find('input[name*="[remarks]"]').val('');
        } else if (actualRowIndex === 2 || (actualRowIndex === 1 && exclude_molding)) {
            // 3행: 빈 행 (몰딩 제외시 2행이 빈 행이 됨)
            console.log('3행: 빈 행 설정');
            $(this).find('input[name*="[category]"]').val('');
            $(this).find('input[name*="[item]"]').val('');
            $(this).find('input[name*="[unit]"]').val('');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                $(this).find('.cost-quantity-input').val('');
                $(this).find('.cost-unit-price-input').val('');
                $(this).find('.cost-supply-amount').val('');
                $(this).find('.cost-tax-amount').val('');
            }
            $(this).find('input[name*="[remarks]"]').val('');
        } else if (actualRowIndex === 3 || (actualRowIndex === 2 && exclude_molding)) {
            if (exclude_construction_cost) {
                // 시공비 제외시: 4행은 운송비
                console.log('4행: 운송비 설정 (시공비 제외)');
                $(this).find('input[name*="[category]"]').val('운송비');
                $(this).find('input[name*="[item]"]').val('');
                $(this).find('input[name*="[unit]"]').val('');
                
                // 자동계산이 체크된 경우에만 수치 값 설정
                if (etc_autocheck) {
                    $(this).find('.cost-quantity-input').val('');
                    $(this).find('.cost-unit-price-input').val('');
                    $(this).find('.cost-supply-amount').val('');
                    $(this).find('.cost-tax-amount').val('');
                }
                $(this).find('input[name*="[remarks]"]').val('착불');
            } else {
                // 시공비 포함시: 4행은 시공비
                console.log('4행: 시공비 설정');
                $(this).find('input[name*="[category]"]').val('시공비');
                $(this).find('input[name*="[item]"]').val('㎡당 시공비');
                $(this).find('input[name*="[unit]"]').val('㎡');
                
                // 자동계산이 체크된 경우에만 수치 값 설정
                if (etc_autocheck) {
                    $(this).find('.cost-quantity-input').val(totalArea.toFixed(2));
                    // 시공비는 헤베당 25000원 추가 기본 70만원 28헤베까지는 70만원 이상시 헤베당 25000원 추가
                    if (totalArea <= 28) {
                        $(this).find('.cost-unit-price-input').val('700,000');
                        $(this).find('.cost-supply-amount').val((700000).toLocaleString());
                        $(this).find('.cost-tax-amount').val((700000 * 0.1).toLocaleString());
                    } else {
                        $(this).find('.cost-unit-price-input').val('25,000');
                        $(this).find('.cost-supply-amount').val((25000 * totalArea).toLocaleString());
                        $(this).find('.cost-tax-amount').val((25000 * totalArea * 0.1).toLocaleString());
                    }
                    $(this).find('input[name*="[remarks]"]').val('최소 시공비 70만원 (28㎡)');
                }
            }
        } else if (actualRowIndex === 4 || (actualRowIndex === 3 && exclude_molding && !exclude_construction_cost)) {
            // 5행: 운송비 (시공비 포함시에만)
            console.log('5행: 운송비 설정');
            $(this).find('input[name*="[category]"]').val('운송비');
            $(this).find('input[name*="[item]"]').val('');
            $(this).find('input[name*="[unit]"]').val('');
            
            // 자동계산이 체크된 경우에만 수치 값 설정
            if (etc_autocheck) {
                $(this).find('.cost-quantity-input').val('');
                $(this).find('.cost-unit-price-input').val('');
                $(this).find('.cost-supply-amount').val('');
                $(this).find('.cost-tax-amount').val('');
            }
            $(this).find('input[name*="[remarks]"]').val('착불');
        }
        
        // 각 행의 입력값 확인
        const category = $(this).find('input[name*="[category]"]').val();
        const item = $(this).find('input[name*="[item]"]').val();
        console.log(`행 ${index + 1} 설정 완료 - 카테고리: ${category}, 품목: ${item}`);        
        rowIndex++;
    });
    // 기타비용 소계 업데이트
    updateOtherCostsSubtotal();
    // 총합계 업데이트
    updateTotals();
}

// 기타비용 행 계산 함수
function calculateCostRow(row) {
    console.log('기타비용 행 계산 함수 calculateCostRow 호출됨');
    
    // 기본 input 값 가져오기
    const quantity = parseFloat(row.find('.cost-quantity-input').val().replace(/,/g, '')) || 0;
    const unitPrice = row.find('.cost-unit-price-input').val().replace(/,/g, '') || '0';    
    const category = row.find('input[name*="[category]"]').val() || '';
    const item = row.find('input[name*="[item]"]').val() || '';

    let supplyAmount = 0;
    
    // 기본 계산 로직 - 수량 * 단가
    supplyAmount = quantity * unitPrice;

    console.log('quantity:', quantity, 'unitPrice:', unitPrice, 'supplyAmount:', supplyAmount, 'category:', category, 'item:', item);
    
    // 시공비 특별 계산 로직 - 기본 input 값 무시하고 규칙에 따라 계산
    if (category === '시공비' && item === '㎡당 시공비') {
        if (quantity <= 28) {
            supplyAmount = 700000; // 28헤베 이하는 70만원 고정
        } else {
            supplyAmount = 25000 * quantity; // 28헤베 초과시 헤베당 25,000원
        }
    }
    
    // 세액은 공급가액의 10%
    const taxAmount = supplyAmount * 0.1;
    
    // 계산 결과를 input에 설정 (천단위 구분기호 포함)
    row.find('.cost-supply-amount').val(supplyAmount.toLocaleString());
    row.find('.cost-tax-amount').val(taxAmount.toLocaleString());
}

$(document).ready(function() {
    var mode = $('#mode').val();
    
    // 초기 Select2 및 계산
    initializeSelect2();
    
    // 기존 상품 행들의 옵션 채우기
    $('.product-select').each(function() {
        // 기존 값이 있으면 data 속성에 저장
        const currentValue = $(this).val();
        if (currentValue) {
            $(this).data('initial-value', currentValue);
        }
        
        // 초기 로딩 플래그 설정 (이후 상품 변경 시 구분용)
        const itemRow = $(this).closest('.item-row');
        itemRow.data('initial-load', true);
        
        populateProductOptions($(this));
    });
    
    // 상품 선택 이벤트
    $(document).on('change', '.product-select', function() {
        var row = $(this).data('row');
        var selectedOption = $(this).find('option:selected');
        const itemRow = $(this).closest('.item-row');
        
        if (selectedOption.val()) {
            var spec = selectedOption.data('spec');
            var size = selectedOption.data('size');
            var area = selectedOption.data('area');
            var unitPrice = selectedOption.data('unit-price');
            
            itemRow.find('.specification-input').val(spec);
            itemRow.find('.size-input').val(size);
            
            // 실제 면적 계산 (규격에서 면적 추출)
            let actualArea = 0;
            if (size && size.trim() !== '') {
                if (size.includes('*')) {
                    const sizeParts = size.split('*');
                    if (sizeParts.length >= 2) {
                        const width = parseFloat(sizeParts[0]) || 0;
                        const height = parseFloat(sizeParts[1]) || 0;
                        actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                        console.log('상품 선택 - 면적 계산 - size:', size, 'width:', width, 'height:', height, 'actualArea:', actualArea);
                    }
                } else if (size.includes('×')) {
                    const sizeParts = size.split('×');
                    if (sizeParts.length >= 2) {
                        const width = parseFloat(sizeParts[0]) || 0;
                        const height = parseFloat(sizeParts[1]) || 0;
                        actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                        console.log('상품 선택 - 면적 계산 - size:', size, 'width:', width, 'height:', height, 'actualArea:', actualArea);
                    }
                } else {
                    // 단일 숫자인 경우 (가정: 정사각형)
                    const singleSize = parseFloat(size) || 0;
                    actualArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
                    console.log('상품 선택 - 면적 계산 - size:', size, 'singleSize:', singleSize, 'actualArea:', actualArea);
                }
            } else {
                console.log('상품 선택 - 면적 계산 - size 필드가 비어있음:', size);
            }
            
            // 상품명 변경 시 단가 처리 로직
            // 초기 로딩이 아닌 경우 (사용자가 상품을 변경한 경우)에는 단가표의 원래 단가 사용
            const isInitialLoad = itemRow.data('initial-load') === true;
            let unitPriceVal = 0;
            
            if (isInitialLoad) {
                // 초기 로딩 시에만 기존 수정된 단가 유지
                const existingUnitPrice = itemRow.find('.unit-price-input').val();
                if (existingUnitPrice && existingUnitPrice !== '' && existingUnitPrice !== '0') {
                    // 기존에 수정된 단가가 있으면 그 값을 유지
                    unitPriceVal = parseFloat(existingUnitPrice.replace(/,/g, '')) || 0;
                    console.log('초기 로딩 - 기존 수정된 단가 유지:', unitPriceVal);
                } else {
                    // 기존 단가가 없거나 0이면 단가표의 기본값 사용
                    if (unitPrice && unitPrice !== '' && !isNaN(unitPrice)) {
                        unitPriceVal = parseFloat(unitPrice) || 0;
                        itemRow.find('.unit-price-input').val(unitPriceVal.toLocaleString());
                        console.log('초기 로딩 - 단가표 기본값 사용:', unitPriceVal);
                    } else {
                        itemRow.find('.unit-price-input').val('');
                    }
                }
                // 초기 로딩 플래그 해제
                itemRow.data('initial-load', false);
            } else {
                // 상품명 변경 시에는 단가표의 원래 단가 사용
                if (unitPrice && unitPrice !== '' && !isNaN(unitPrice)) {
                    unitPriceVal = parseFloat(unitPrice) || 0;
                    itemRow.find('.unit-price-input').val(unitPriceVal.toLocaleString());
                    console.log('상품명 변경 - 단가표 원래 단가 사용:', unitPriceVal);
                } else {
                    itemRow.find('.unit-price-input').val('');
                }
            }
            
            // 기존 수량 확인 - 수정된 수량이 있으면 유지
            const existingQuantity = parseFloat(itemRow.find('.quantity-input').val()) || 0;
            const quantity = existingQuantity > 0 ? existingQuantity : 1;
            itemRow.find('.quantity-input').val(quantity);
            
            // m² 열 업데이트 (수량 × 실제면적)
            const totalArea = quantity * actualArea;
            itemRow.find('.area-input').val(totalArea.toFixed(2));
            
            calculateItemAmount(row);
        } else {
            // Clear fields if no product is selected
            itemRow.find('.specification-input').val('');
            itemRow.find('.size-input').val('');
            itemRow.find('.area-input').val('');
            itemRow.find('.unit-price-input').val('');
            calculateItemAmount(row);
        }
    });

    const etcAutoChecked = $('#etc_autocheck').is(':checked') || $('#etc_autocheck').val() === '1';
      
    // 조회모드가 아닐 때만 기타비용 자동 계산 초기화 insert일때 기타비용 초기형태 만들어줌
    if (mode !== 'view' && $('.item-row').length > 0) {
        // 수정 모드에서 기존 데이터가 있으면 자동 계산 방지
        let hasExistingData = false;
        $('.cost-row').each(function() {
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            const quantity = $(this).find('.cost-quantity-input').val();
            const unitPrice = $(this).find('.cost-unit-price-input').val();
            
            if (category || item || quantity || unitPrice) {
                hasExistingData = true;
                return false; // break
            }
        });
        
        if (hasExistingData) {
            console.log('수정 모드에서 기존 기타비용 데이터가 있어 자동 계산을 건너뜁니다.');
        } else {
            // 기존 데이터가 없을 때만 자동 계산 실행
            calculateOtherCostsFromProducts(false);
        }
    }  

    // 수량/단가 변경 이벤트 
    $(document).on('input', '.quantity-input, .cost-quantity-input, .cost-unit-price-input, .discount-item-quantity-input, .discount-item-unit-price-input, .discount-cost-quantity-input, .discount-cost-unit-price-input', function() {
        var row = $(this).closest('.item-row, .cost-row, .discount-item-row').data('row');
        var cost_row = $(this).closest('.cost-row').data('row');

        // cost-quantity-input 또는 cost-unit-price-input에서 입력이 일어나면 새로운 calculateCostRow 함수 호출
        if($(this).hasClass('cost-quantity-input') || $(this).hasClass('cost-unit-price-input')) {
            var $costRow = $(this).closest('.cost-row');
            console.log('기타비용 입력 발생, 해당 행 전달:', $costRow);
            calculateCostRow($costRow);
            updateTotals();
            // 기타비용 입력 시에는 calculateOtherCostsFromProducts 호출하지 않음 (포커스 유지)
            return;
        }

        if($(this).hasClass('quantity-input') || $(this).hasClass('unit-price-input')) {
            console.log('Calling calculateItemAmount for row:', row);
            calculateItemAmount(row);
            if (etcAutoChecked) {  
                console.log('자동계산이 체크되면 기타비용 계산해줌');
                calculateOtherCostsFromProducts(true);
                updateTotals();
            }   
        } else if($(this).hasClass('discount-cost-quantity-input') || $(this).hasClass('discount-cost-unit-price-input')) {
            // 할인 기타비용 입력 - calculateOtherCostsFromProducts 호출하지 않음 (포커스 유지)
            console.log('할인 기타비용 입력 발생');
            updateTotals();
            // 할인 기타비용에 대한 계산 함수가 있다면 여기서 호출
        } else if($(this).hasClass('discount-item-quantity-input') || $(this).hasClass('discount-item-unit-price-input')) {
            calculateDiscountItemAmount(row);
            updateTotals();
        }               
    });
    
    // 시공비/몰딩 제외 체크박스 이벤트 리스너
    $('#exclude_construction_cost, #exclude_molding').change(function() {
        console.log($(this).attr('id') === 'exclude_construction_cost' ? '시공비 제외' : '몰딩 제외' + ' 체크박스 변경:', $(this).is(':checked'));
        // 기타비용 테이블 재계산 (강제 재계산으로 설정하여 자동계산 체크와 상관없이 동작)
        calculateOtherCostsFromProducts(true);
        alertToast('시공비 등 재계산 ');
    });
       
    // 기타비용 행 입력 이벤트
    $(document).on('input', '.cost-quantity-input, .cost-unit-price-input', function() {
        const row = $(this).closest('.cost-row');
        
        // 수동 수정 플래그 설정
        row.data('manually-modified', true);
        
        // 새로운 calculateCostRow 함수 호출 (jQuery 객체 전달)
        calculateCostRow(row);
        
        // 기타비용 소계 즉시 업데이트
        updateOtherCostsSubtotal();        
    });
    
    // 기타비용 카테고리/품목 수동 수정 추적 및 재계산
    $(document).on('input', '.cost-row input[name*="[category]"], .cost-row input[name*="[item]"]', function() {
        const row = $(this).closest('.cost-row');
        row.data('manually-modified', true);
        
        // 카테고리나 품목이 변경되면 계산 함수 호출 (시공비 특별 계산 로직 적용을 위해)
        calculateCostRow(row);
        
        // 기타비용 소계 업데이트
        updateOtherCostsSubtotal();
        updateTotals();
    });
    
    // mainTable 변화 감지하여 기타비용 테이블 연동
    $(document).on('change', '.product-select', function() {
        setTimeout(function() {
            calculateOtherCostsFromProducts(false);
        }, 150);
    });
        
    // 단가 입력 필드에 대한 별도 이벤트 핸들러 추가
    $(document).on('input', '.unit-price-input', function() {
        console.log('Unit price input event triggered');
        console.log('Event target:', this);
        console.log('Event target classes:', $(this).attr('class'));
        const row = $(this).closest('.item-row').data('row');
        console.log('Unit price changed for row:', row);
        console.log('Closest item-row element:', $(this).closest('.item-row'));
        if (row !== undefined) {
            calculateItemAmount(row);
            updateTotals();
        } else {
            console.error('Could not find row for unit price input');
        }
    });

    // 단가 입력 필드에 대한 keyup 이벤트 핸들러 추가 (백업용)
    $(document).on('keyup', '.unit-price-input', function() {
        console.log('Unit price keyup event triggered');
        const row = $(this).closest('.item-row').data('row');
        console.log('Unit price keyup for row:', row);
        if (row !== undefined) {
            calculateItemAmount(row);
            updateTotals();
        }
    });

    // 테스트용 버튼 추가 (디버깅용)
    $(document).on('click', '.test-calc-btn', function() {
        const row = $(this).closest('.item-row').data('row');
        console.log('Test calculation button clicked for row:', row);
        if (row !== undefined) {
            calculateItemAmount(row);
            updateTotals();
        }
    });

    // 기존 기타비용 데이터 분석하여 수동 수정 여부 판단
    function analyzeExistingOtherCosts() {
        $('.cost-row').each(function() {
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            const quantity = $(this).find('.cost-quantity-input').val();
            const unitPrice = $(this).find('.cost-unit-price-input').val();
            
            // 기존 데이터가 있고, 자동 계산된 기본값과 다른 경우 수동 수정으로 간주
            if (category && item && (quantity || unitPrice)) {
                // 자동 계산된 기본값 패턴 확인
                const isAutoCalculated = 
                    // 본드: 부자재, 본드, 수량 있음, 단가 5,000
                    (category === '부자재' && item === '본드' && quantity && unitPrice === '5,000') ||
                    // 시공비: 시공비, ㎡당 시공비, 단가 25,000 또는 700,000
                    (category === '시공비' && item === '㎡당 시공비' && (unitPrice === '25,000' || unitPrice === '700,000')) ||
                    // 몰딩: 부자재, 몰딩, 빈 값들
                    (category === '부자재' && item === '몰딩' && !quantity && !unitPrice) ||
                    // 운송비: 운송비, 빈 항목, 비고에 '착불'
                    (category === '운송비' && !item && !quantity && !unitPrice);
                
                if (!isAutoCalculated) {
                    $(this).data('manually-modified', true);
                    console.log('기존 데이터에서 수동 수정 감지:', category, item, quantity, unitPrice);
                }
            }
        });
    }
    
    // 기존 데이터 분석 실행
    analyzeExistingOtherCosts();
    
    // 조회모드가 아닐 때만 기존 기타비용 데이터의 금액 계산
    if ($("#mode").val() !== 'view' && etcAutoChecked) {
        // 기존 기타비용 데이터의 금액 계산
        $('.cost-row').each(function() {
            const $row = $(this);
            const $quantityInput = $row.find('.cost-quantity-input');
            const $unitPriceInput = $row.find('.cost-unit-price-input');
            const $categoryInput = $row.find('input[name*="[category]"]');
            const $itemInput = $row.find('input[name*="[item]"]');
            
            const quantity = parseFloat($quantityInput.val()) || 0;
            const unitPriceText = $unitPriceInput.val();
            const category = $categoryInput.val();
            const item = $itemInput.val();
            
            if (quantity > 0 && unitPriceText) {
                let unitPrice = 0;
                let supplyAmount = 0;
                
                // 시공비 특별 계산 로직
                if (category === '시공비' && item === '㎡당 시공비') {
                    // 시공비는 헤베당 25000원 추가 기본 70만원 28헤베까지는 70만원 이상시 헤베당 25000원 추가
                    if (quantity <= 28) {
                        supplyAmount = 700000; // 70만원 고정
                    } else {
                        supplyAmount = 25000 * quantity; // ㎡당 25,000원
                    }
                } else {
                    // 일반 계산 로직
                    // 단가에서 쉼표 제거 후 숫자로 변환
                    if (unitPriceText && unitPriceText !== '') {
                        const cleanUnitPrice = unitPriceText.replace(/,/g, '');
                        unitPrice = parseFloat(cleanUnitPrice) || 0;
                    }
                    supplyAmount = quantity * unitPrice;
                }
                
                const taxAmount = supplyAmount * 0.1;
                
                $row.find('.cost-supply-amount').text('' + supplyAmount.toLocaleString());
                $row.find('.cost-tax-amount').text('' + taxAmount.toLocaleString());
                
                console.log('기존 기타비용 금액 계산 - category:', category, 'item:', item, 'quantity:', quantity, 'unitPrice:', unitPrice, 'supplyAmount:', supplyAmount);
            }
        });
    }
    
    // 초기 합계 계산 view가 아닐때 
    if($("#mode").val() !== 'view') {   
        updateTotals();
    }
    
});

// 테이블 열 너비 자동 조절 함수 (전역 스코프)
function autoResizeTableColumns() {
    const table = $('#itemsTable');
    const headers = table.find('thead th');
    
    // 임시로 사용할 span 요소를 body에 추가 (너비 계산용)
    // 한 번만 생성하고 재사용합니다.
    if ($('#tempSpan').length === 0) {
        $('<span id="tempSpan" style="position:absolute; top:-9999px; left:-9999px; white-space:nowrap; padding: 0 8px;"></span>').appendTo('body');
    }
    const tempSpan = $('#tempSpan');

    headers.each(function(index) {
        // 상품명 열(두 번째 열, index: 1)은 고정 너비로 처리
        if (index === 1) {
            $(this).css('width', '200px'); // Select2 UI를 고려한 고정 너비
            return; // 다음 열로 넘어감
        }

        // --- 나머지 열은 동적으로 너비 조절 ---
        let maxWidth = 0;
        
        // 1. 헤더 자체의 너비 계산
        tempSpan.css('font', $(this).css('font'));
        tempSpan.text($(this).text());
        maxWidth = tempSpan.prop('scrollWidth');
        
        // 2. 해당 열의 모든 셀(td) 내용 너비 계산
        table.find(`tbody tr`).each(function() {
            const cell = $(this).find('td').eq(index);
            const input = cell.find('input, select');
            let contentWidth = 0;

            if (input.length > 0) {
                // Select2의 경우, 선택된 텍스트를 가져와야 합니다.
                if (input.is('select') && input.hasClass('select2-hidden-accessible')) {
                    const selectedText = input.next('.select2-container').find('.select2-selection__rendered').text();
                    tempSpan.text(selectedText);
                } else {
                     tempSpan.text(input.val());
                }
                
                tempSpan.css('font', input.css('font'));
                contentWidth = tempSpan.prop('scrollWidth');
            } else {
                // 일반 텍스트 셀의 경우
                tempSpan.css('font', cell.css('font'));
                tempSpan.text(cell.text());
                contentWidth = tempSpan.prop('scrollWidth');
            }

            if (contentWidth > maxWidth) {
                maxWidth = contentWidth;
            }
        });
        
        // 3. 계산된 최대 너비에 여유 공간을 더해 th에 적용
        // input 테두리, select 화살표 등을 고려하여 30px 정도 여유를 줍니다.
        $(this).css('width', maxWidth + 30 + 'px');
    });
}

// 기타비용 테이블 열 너비 자동 조절 함수 (전역 스코프)
function autoResizeOtherCostsTableColumns() {
    const table = $('#otherCostsTable');
    const headers = table.find('thead th');
    
    // 임시로 사용할 span 요소를 body에 추가 (너비 계산용)
    if ($('#tempSpanOtherCosts').length === 0) {
        $('<span id="tempSpanOtherCosts" style="position:absolute; top:-9999px; left:-9999px; white-space:nowrap; padding: 0 8px;"></span>').appendTo('body');
    }
    const tempSpan = $('#tempSpanOtherCosts');

    headers.each(function(index) {
        // --- 모든 열을 동적으로 너비 조절 ---
        let maxWidth = 0;
        
        // 1. 헤더 자체의 너비 계산
        tempSpan.css('font', $(this).css('font'));
        tempSpan.text($(this).text());
        maxWidth = tempSpan.prop('scrollWidth');
        
        // 2. 해당 열의 모든 셀(td) 내용 너비 계산
        table.find(`tbody tr`).each(function() {
            const cell = $(this).find('td').eq(index);
            const input = cell.find('input, select');
            let contentWidth = 0;

            if (input.length > 0) {
                tempSpan.text(input.val());
                tempSpan.css('font', input.css('font'));
                contentWidth = tempSpan.prop('scrollWidth');
            } else {
                // 일반 텍스트 셀의 경우
                tempSpan.css('font', cell.css('font'));
                tempSpan.text(cell.text());
                contentWidth = tempSpan.prop('scrollWidth');
            }

            if (contentWidth > maxWidth) {
                maxWidth = contentWidth;
            }
        });
        
        // 3. 계산된 최대 너비에 여유 공간을 더해 th에 적용
        $(this).css('width', maxWidth + 30 + 'px');
    });
}

$(document).ready(function() {
    
    // 페이지 로드 시 테이블 너비 자동 조절
    autoResizeTableColumns();
    autoResizeOtherCostsTableColumns();
    
    // 입력 필드에 입력이 발생할 때마다 너비 조절 (실시간)
    $(document).on('input', '.item-row input', function() {
        autoResizeTableColumns();
    });

    // Select2 드롭다운 값이 변경될 때마다 너비 조절
    $(document).on('change', '.product-select', function() {
        autoResizeTableColumns();
    });
    
    // 기타비용 입력 필드에 입력이 발생할 때마다 너비 조절 (실시간)
    $(document).on('input', '.cost-row input', function() {
        autoResizeOtherCostsTableColumns();
    });
    
    // 저장 버튼 클릭 이벤트
    $("#saveBtn").off('click').on('click', function() {
        console.log('=== 저장 함수 호출 ===');
        
        try {
            // JSON 데이터 생성
            const items = [];
            $('.item-row').each(function() {
                const productCode = $(this).find('select[name*="[product_code]"]').val();
                const productName = $(this).find('select[name*="[product_code]"]').find('option:selected').text();
                const specification = $(this).find('.specification-input').val();
                const size = $(this).find('.size-input').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
                const area = parseFloat($(this).find('input[name*="[area]"]').val()) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;            
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                items.push({
                    product_code: productCode,
                    product_name: productName,
                    specification: specification,
                    size: size,
                    quantity: quantity,
                    area: area,
                    unit_price: unitPrice,
                    remarks: remarks
                });
            });
            
            const otherCosts = [];
            $('.cost-row').each(function() {
                const category = $(this).find('input[name*="[category]"]').val();
                const item = $(this).find('input[name*="[item]"]').val();
                const unit = $(this).find('input[name*="[unit]"]').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val().replace(/,/g, '')) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                otherCosts.push({
                    category: category,
                    item: item,
                    unit: unit,
                    quantity: quantity,
                    unit_price: unitPrice,
                    remarks: remarks
                });
            });
        
            // 할인 상품 데이터 생성
            const discountItems = [];
            $('.discount-item-row').each(function() {
                const productCode = $(this).find('input[name*="[product_code]"]').val();
                const codeString = $(this).find('input[name*="[code_string]"]').val();
                const specification = $(this).find('input[name*="[specification]"]').val();
                const size = $(this).find('input[name*="[size]"]').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
                const area = parseFloat($(this).find('input[name*="[area]"]').val()) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;
                const supplyAmount = parseFloat($(this).find('input[name*="[supply_amount]"]').val().replace(/,/g, '')) || 0;
                const taxAmount = parseFloat($(this).find('input[name*="[tax_amount]"]').val().replace(/,/g, '')) || 0;
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                discountItems.push({
                    product_code: productCode,
                    code_string: codeString,
                    specification: specification,
                    size: size,
                    quantity: quantity,
                    area: area,
                    unit_price: unitPrice,
                    supply_amount: supplyAmount,
                    tax_amount: taxAmount,
                    remarks: remarks
                });
            });

            // 할인 기타 비용 데이터 생성
            const discountOtherCosts = [];
            $('.discount-cost-row').each(function() {
                const category = $(this).find('input[name*="[category]"]').val();
                const item = $(this).find('input[name*="[item]"]').val();
                const unit = $(this).find('input[name*="[unit]"]').val();
                const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val().replace(/,/g, '')) || 0;
                const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val().replace(/,/g, '')) || 0;
                const supplyAmount = parseFloat($(this).find('input[name*="[supply_amount]"]').val().replace(/,/g, '')) || 0;
                const taxAmount = parseFloat($(this).find('input[name*="[tax_amount]"]').val().replace(/,/g, '')) || 0;
                const remarks = $(this).find('input[name*="[remarks]"]').val();

                discountOtherCosts.push({
                    category: category,
                    item: item,
                    unit: unit,
                    quantity: quantity,
                    unit_price: unitPrice,  
                    supply_amount: supplyAmount,
                    tax_amount: taxAmount,
                    remarks: remarks
                });
            });

            const notices = [];
            $('input[name="notices[]"]').each(function() {
                if ($(this).val().trim()) {
                    notices.push($(this).val());
                }
            });
            
            // 시공비 제외 체크박스 상태 추가
            const exclude_construction_cost = $('#exclude_construction_cost').is(':checked');
            // 몰딩 제외 체크박스 상태 추가
            const exclude_molding = $('#exclude_molding').is(':checked');
            
            // 폼 데이터 수집   
            const formData = new FormData($('#orderForm')[0]);
            // total_ex_vat input요소에 span class total_ex_vat 요소에서 원화표시 제거하고 넣기
            const total_ex_vat = $('span.total-ex-vat').text().replace(/[^\d]/g, '');
            const total_inc_vat = $('span.total-inc-vat').text().replace(/[^\d]/g, '');

            formData.append('total_ex_vat', total_ex_vat);
            formData.append('total_inc_vat', total_inc_vat);
            formData.append('items_json', JSON.stringify(items));
            formData.append('other_costs_json', JSON.stringify(otherCosts));
            formData.append('discount_items_json', JSON.stringify(discountItems));
            formData.append('discount_other_costs_json', JSON.stringify(discountOtherCosts));
            formData.append('notices_json', JSON.stringify(notices));
            formData.append('exclude_construction_cost', exclude_construction_cost ? '1' : '0');
            formData.append('exclude_molding', exclude_molding ? '1' : '0');
            formData.append('estimate_num', $('#estimate_num').val());
            
            // 디버그: 전송할 데이터 확인
            console.log('=== AJAX 전송 데이터 ===');       
            // console.log('items:', items);
            // console.log('otherCosts:', otherCosts);
            // console.log('discountItems:', discountItems);
            // console.log('discountOtherCosts:', discountOtherCosts);
            
            // AJAX 호출
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                timeout: 60000, // 30초 타임아웃
                beforeSend: function() {
                    console.log('=== AJAX 요청 시작 ===');
                    console.log('URL:', 'process.php');
                    console.log('Method:', 'POST');
                },
                success: function(response) {
                    console.log('=== AJAX 성공 응답 ===');
                    console.log('Response:', response);
                    
                    if (response.result === 'success') {
                        console.log('저장 성공 - mode:', '<?= $mode ?>', 'num:', response.num);
                        
                        Swal.fire({
                            icon: 'success',
                            title: '성공', 
                            text: response.message,
                            confirmButtonColor: '#3085d6',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    
                        setTimeout(function() {                    
                            // 성공 시 view 모드로 이동
                            const mode = '<?= $mode ?>';
                            const num = response.num;
                            console.log('리다이렉트 - mode:', mode, 'num:', num);                        
                            // 복사인 경우 view 모드로 이동 (새로 생성된 num 사용)
                            // 부모창 새로고침
                            if(window.opener) {
                                window.opener.location.reload();
                            }
                            window.location.href = 'write_form.php?mode=view&num=' + num + '&tablename=phomi_order';                        
                        }, 1500);
                    } else {
                        console.log('저장 실패 - message:', response.message);
                        alert('저장 중 오류가 발생했습니다: ' + response.message);
                        saveBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('=== AJAX 오류 ===');
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Status Code:', xhr.status);
                    
                    let errorMessage = '저장 중 오류가 발생했습니다.';
                    if (status === 'timeout') {
                        errorMessage = '요청 시간이 초과되었습니다.';
                    } else if (xhr.status === 0) {
                        errorMessage = '네트워크 연결을 확인해주세요.';
                    } else if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {
                            errorMessage = error || errorMessage;
                        }
                    }
                    
                    alert(errorMessage);
                    saveBtn.prop('disabled', false).text(originalText);
                }
            });
        } catch (error) {
            console.log('=== JavaScript 오류 ===');
            console.log('Error:', error);
            alert('JavaScript 오류가 발생했습니다: ' + error.message);
            saveBtn.prop('disabled', false).text(originalText);
        }    
    });
}); // end of $(document).ready(function() 

function editOrder() {
    var num = $('#num').val();
    console.log(num);
    window.location.href = 'write_form.php?mode=modify&num=' + num + '&tablename=phomi_order';
}

function copyOrder() {
    var num = $('#num').val();
    window.location.href = 'write_form.php?mode=copy&num=' + num + '&tablename=phomi_order';
}

// PDF 수주서 생성 함수
function generatePDF() {
        var recipient = '<?= htmlspecialchars($recipient) ?>';
        var site_name = '<?= htmlspecialchars($site_name) ?>';
        var quote_date = '<?= $quote_date ?>';
        
        // 파일명 생성
        var today = new Date();
        var formattedDate = "(" + String(today.getFullYear()).slice(-2) + "." + ("0" + (today.getMonth() + 1)).slice(-2) + "." + ("0" + today.getDate()).slice(-2) + ")";
        // 파일명에서 특수문자 제거
        var sanitizedRecipient = recipient.replace(/[\\/:*?"<>|]/g, '');
        var sanitizedSiteName = site_name.replace(/[\\/:*?"<>|]/g, '');
        var result = '포미스톤수주서_' + sanitizedRecipient + '_' + sanitizedSiteName + formattedDate + '.pdf';     
        
        var element = document.getElementById('content-to-print');
        
        // PDF 생성 전에 작은 글씨 크기 적용
        element.style.fontSize = '10px';
        element.querySelectorAll('.table th, .table td').forEach(function(el) {
            el.style.fontSize = '10px';
            el.style.padding = '2px 4px';
            el.style.border = '0.1px solid #000';
            el.style.borderCollapse = 'collapse';
            el.style.borderWidth = '0.1px';
        });
        element.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(function(el) {
            el.style.fontSize = '14px';
        });
        element.querySelectorAll('.small').forEach(function(el) {
            el.style.fontSize = '8px';
        });
        
        // 테이블 테두리 강제 적용
        element.querySelectorAll('.table').forEach(function(table) {
            table.style.borderCollapse = 'collapse';
            table.style.border = '0.1px solid #000';
            table.style.borderSpacing = '0';
            table.style.width = '100%';
            table.style.borderWidth = '0.1px';
        });
        
        // 모든 테이블 셀에 매우 얇은 테두리 적용
        element.querySelectorAll('.table th, .table td').forEach(function(el) {
            el.style.border = '0.1px solid #000';
            el.style.borderCollapse = 'collapse';
            el.style.borderSpacing = '0';
            el.style.borderWidth = '0.1px';
        });
        
        // rowspan 셀 테두리 강제 적용
        element.querySelectorAll('td[rowspan]').forEach(function(cell) {
            cell.style.border = '0.1px solid #000';
            cell.style.borderCollapse = 'collapse';
            cell.style.borderRight = '0.1px solid #000';
            cell.style.borderLeft = '0.1px solid #000';
            cell.style.borderTop = '0.1px solid #000';
            cell.style.borderBottom = '0.1px solid #000';
            cell.style.borderWidth = '0.1px';
            cell.style.verticalAlign = 'middle';
        });
        
        // rowspan이 있는 행의 모든 셀에 테두리 강제 적용
        element.querySelectorAll('tr').forEach(function(row) {
            if (row.querySelector('td[rowspan]')) {
                row.querySelectorAll('td').forEach(function(cell) {
                    cell.style.border = '0.1px solid #000';
                    cell.style.borderCollapse = 'collapse';
                    cell.style.borderWidth = '0.1px';
                });
            }
        });
        
        var opt = {
            margin: [8, 2, 10, 2], // 더 작은 여백
            filename: result,
            image: { type: 'jpeg', quality: 1 },
            html2canvas: {
                scale: 4,   // 해상도를 2로 낮춤 (더 작은 글씨)
                useCORS: true,
                scrollY: 0,
                scrollX: 0,
                windowWidth: document.body.scrollWidth,
                windowHeight: document.body.scrollHeight        
            }, 
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: {
                mode: ['css', 'legacy'],
                avoid: ['tr', '.avoid-break'] // 페이지 나누기 방지
            }
        };
        
        html2pdf().from(element).set(opt).save().then(function() {
            // PDF 생성 후 원래 스타일로 복원
            element.style.fontSize = '';
            element.querySelectorAll('.table th, .table td').forEach(function(el) {
                el.style.fontSize = '';
                el.style.padding = '';
                el.style.border = '';
                el.style.borderCollapse = '';
            });
            element.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(function(el) {
                el.style.fontSize = '';
            });
            element.querySelectorAll('.small').forEach(function(el) {
                el.style.fontSize = '';
            });
            
            // 테이블 테두리 스타일 복원
            element.querySelectorAll('.table').forEach(function(table) {
                table.style.borderCollapse = '';
                table.style.border = '';
                table.style.borderSpacing = '';
                table.style.width = '';
            });
            
            // 모든 테이블 셀 스타일 복원
            element.querySelectorAll('.table th, .table td').forEach(function(el) {
                el.style.border = '';
                el.style.borderCollapse = '';
                el.style.borderSpacing = '';
            });
            
            // rowspan 셀 테두리 스타일 복원
            element.querySelectorAll('td[rowspan]').forEach(function(cell) {
                cell.style.border = '';
                cell.style.borderCollapse = '';
                cell.style.borderRight = '';
                cell.style.borderLeft = '';
                cell.style.borderTop = '';
                cell.style.borderBottom = '';
                cell.style.verticalAlign = '';
            });
            
            // rowspan이 있는 행의 모든 셀 스타일 복원
            element.querySelectorAll('tr').forEach(function(row) {
                if (row.querySelector('td[rowspan]')) {
                    row.querySelectorAll('td').forEach(function(cell) {
                        cell.style.border = '';
                        cell.style.borderCollapse = '';
                    });
                }
            });
        });
}
 
function openEstimatePopup() {
    var estimateNum = "<?= htmlspecialchars($estimate_num) ?>";
    if (!estimateNum) {
        alert("견적번호가 없습니다.");
        return;
    }
    var url = "/phomi/ET_write_form.php?mode=view&num=" + encodeURIComponent(estimateNum);
    window.open(url, "estimatePopup", "width=1200,height=900,scrollbars=yes,resizable=yes");
} 

function alertToast(message) {
    // 기본 배경 색상 (초록)
    let backgroundColor = "linear-gradient(to right, #00b09b, #96c93d)";

    // 조건에 따라 색상 변경
    if (message.includes("추가")) {
        backgroundColor = "linear-gradient(to right, #2196F3, #21CBF3)"; // 파란 계열
    } else if (message.includes("삭제")) {
        backgroundColor = "linear-gradient(to right, #f44336, #e57373)"; // 빨간 계열
    } else if (message.includes("복사")) {
        backgroundColor = "linear-gradient(to right, #4CAF50, #81C784)"; // 녹색 계열
    }

    Toastify({
        text: message,
        duration: 2000,
        close: true,
        gravity: "top",
        position: "center",
        style: {
            background: backgroundColor
        },
    }).showToast();	
}

// 삭제 함수
function deleteBtn() {
    var num = $('#num').val();
    $('#mode').val('delete');

    if (!num) {
        Swal.fire({
            icon: 'error',
            title: '오류',
            text: '삭제할 수주서를 찾을 수 없습니다.'
        });
        return;
    }
    
    // 삭제 확인
    Swal.fire({
        title: '삭제 확인',
        text: '정말로 수주서를 삭제하시겠습니까?\n삭제된 수주서는 복구할 수 없습니다.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '삭제',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX로 삭제 요청
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: {
                    num: num,
                    mode: $('#mode').val(),
                    tablename: $('#tablename').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '삭제 완료',
                            text: '수주서가 성공적으로 삭제되었습니다.'
                        }).then(() => {
                            // 부모창 새로고침 후 현재창 닫기
                            if (window.opener) {
                                window.opener.location.reload();
                            }
                            window.close();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '삭제 실패',
                            text: '삭제 중 오류가 발생했습니다: ' + response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('삭제 요청 오류:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '오류',
                        text: '삭제 중 오류가 발생했습니다.'
                    });
                }
            });
        }
    });
}   

  
// 수주서에서 출고증으로 변환하는 함수
function convertToOutorder() {
    // 현재 수주서 데이터 수집
    var orderData = {
        quote_date: $('#order_date').val() || '',
        recipient: $('#recipient-text').text() || '미래기업',
        site_name: $('#site-name-text').text() || '',
        signer: $('.signer-text').text() || '소현철',
        hp: $('.hp-text').text() || '010-3784-5438',
        order_date: $('#order_date').val() || '',
        order_num: $('#num').val() || '',
        items: [],
        other_costs: [],
        //받는분, 받는분 전화번호 추가
        recipient_name: $('#recipient-name-text').text() || '',
        recipient_phone: $('#recipient-phone').text() || '',
    };
    
    // 아이템 데이터 수집
    $('.item-row-view').each(function() {
        var row = $(this);
        var item = {
            prodcode: row.find('.product-code').text(),
            quantity: parseFloat(row.find('.quantity-input').text()) || 0,                
            note: row.find('.remarks-input').text() || ''
        };
        orderData.items.push(item);
    });
    
    // 출고증 페이지로 데이터 전송
    var outorderUrl = 'OR_write_form.php?mode=insert&tablename=phomi_outorder';
    var form = $('<form>', {
        'method': 'POST',
        'action': outorderUrl
    });
    
    // 기타 비용 데이터 수집
    console.log('기타비용 행 개수:', $('.other-cost-row-view').length);
    
    $('.other-cost-row-view').each(function(index) {
        var row = $(this);
        var category = row.find('.cost-category-input').text();
        var item = row.find('.cost-item-input').text();
        var quantityText = row.find('.cost-quantity-input').text();
        var unitPriceText = row.find('.cost-unit-price-input').text();
        var prodcode = '';
        
        console.log('기타비용 행 ' + index + ' 원본 데이터:', {
            category: category,
            item: item,
            quantityText: quantityText,
            unitPriceText: unitPriceText
        });
        
        // 본드와 몰딩에 대한 prodcode 설정
        if (item.indexOf('본드') !== -1) {
            prodcode = 'BOND';
        }
        if (item.indexOf('몰딩') !== -1) {
            prodcode = 'MOLDING';
        }
        
        var costItem = {
            prodcode: prodcode,
            category: category,
            item: item,
            unit: row.find('.cost-unit-input').text(),
            quantity: parseFloat(quantityText) || 0,                
            unit_price: parseFloat(unitPriceText.replace(/,/g, '')) || 0,
            remarks: row.find('.cost-remarks-input').text() || ''
        };
        
        // 디버깅: 수집된 기타비용 데이터 로그
        console.log('수집된 기타비용 데이터 ' + index + ':', costItem);
        
        // 빈 데이터가 아닌 경우에만 추가
        if (category || item || costItem.quantity > 0 || costItem.unit_price > 0) {
            orderData.other_costs.push(costItem);
            console.log('기타비용 데이터 추가됨:', costItem);
        } else {
            console.log('기타비용 데이터 제외됨 (빈 데이터):', costItem);
        }
    });
    
    // 본드가 없으면 강제로 추가 (기존 로직)
    var hasBond = false;
    var bondQuantity = 1; // 기본 수량
    var bondUnitPrice = 5000; // 기본 단가
    
    orderData.other_costs.forEach(function(cost) {
        if (cost.item && cost.item.indexOf('본드') !== -1) {
            hasBond = true;
            // 기존 본드의 수량과 단가를 사용
            bondQuantity = cost.quantity || 1;
            bondUnitPrice = cost.unit_price || 5000;
            console.log('기존 본드 발견 - 수량:', bondQuantity, '단가:', bondUnitPrice);
        }
    });
    
    if (!hasBond) {
        console.log('본드가 없어서 강제로 추가, 수량:', bondQuantity, '단가:', bondUnitPrice);
        orderData.other_costs.push({
            prodcode: 'BOND',
            category: '부자재',
            item: '본드',
            unit: 'EA',
            quantity: bondQuantity,
            unit_price: bondUnitPrice,
            remarks: '자동 추가'
        });
    } else {
        console.log('본드가 이미 존재함, 수량:', bondQuantity, '단가:', bondUnitPrice);
    }
    
    // 데이터를 hidden input으로 추가
    form.append($('<input>', {
        'type': 'hidden',
        'name': 'order_data',
        'value': JSON.stringify(orderData)
    }));
                    
    // 폼을 body에 추가하고 제출 
    $('body').append(form);
    console.log('orderData', orderData);      

    form.submit();
}


</script>
</body>
</html>