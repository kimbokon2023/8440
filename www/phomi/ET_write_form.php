<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  
$title_message = '포미스톤 견적서'; 
$title_message_sub = '견 적 서 (포미스톤)' ; 
$tablename = 'phomi_estimate'; 
$item ='포미스톤 견적서';   
$emailTitle ='견적서';   
$subTitle = '포미스톤 제품';
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
$tablename = $_REQUEST["tablename"] ?? 'phomi_estimate';

// 복사 모드일 때는 num을 초기화 (새로운 견적서로 저장하기 위해)
if($mode == 'copy') {
    $original_num = $num; // 원본 num 보관
    $num = ''; // 새로운 견적서를 위해 num 초기화
}

// 데이터 조회
$estimate_data = null;
if(($mode == 'view' || $mode == 'modify' || $mode == 'copy')) {
    $query_num = ($mode == 'copy') ? $original_num : $num;
    if(!empty($query_num)) {
        try {
            $sql = "SELECT * FROM {$DB}.phomi_estimate WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $query_num, PDO::PARAM_INT);
            $stmt->execute();
            $estimate_data = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "오류: " . $e->getMessage();
        }
    }
}

// 기본값 설정
$quote_date = $estimate_data['quote_date'] ?? date('Y-m-d');
$recipient = $estimate_data['recipient'] ?? '';
$division = $estimate_data['division'] ?? '';
$site_name = $estimate_data['site_name'] ?? '';
$head_office_address = $estimate_data['head_office_address'] ?? '경기도 김포시 양촌읍 흥신로 220-27';
$showroom_address = $estimate_data['showroom_address'] ?? '인천광역시 서구 중봉대로 393번길 16 홈씨씨2층 포미스톤';
$total_ex_vat = $estimate_data['total_ex_vat'] ?? 0;
$total_inc_vat = $estimate_data['total_inc_vat'] ?? 0;
$payment_account = $estimate_data['payment_account'] ?? '중소기업은행 339-084210-01-012 ㈜ 미래기업';
$exclude_construction_cost = $estimate_data['exclude_construction_cost'] ?? '0';
$exclude_molding = $estimate_data['exclude_molding'] ?? '0';
$etc_autocheck = $estimate_data['etc_autocheck'] ?? '1';  // 기타비용 자동산출 체크박스 기본은 체크
$recipient_phone = $estimate_data['recipient_phone'] ?? '';

if(!empty($estimate_data)) {
    $author_id = $estimate_data['author_id'] ?? 'mirae';
    $author = $estimate_data['author'] ?? '소현철';  // 미래기업 직원일 경우는 사장님 이름이 표시되도록 함.
    $hp = $estimate_data['hp'] ?? '010-3784-5438';
    $signer = $estimate_data['signer'] ?? '소현철'; // 공급자에 표시되는 이름
}
else
{
    if($_SESSION["level"] < 6) {
        $author_id = 'mirae';
        $author = '소현철';
        $hp =  '010-3784-5438';
        $signer = '소현철'; // 공급자에 표시되는 이름
    }
    else {
        $author_id = $_SESSION["userid"]; // 작성자 아이디
        $author = $_SESSION["name"]; // 작성자 이름
        $hp = $_SESSION["hp"] ?? '010-3784-5438';
        $signer = $_SESSION["name"] ?? '소현철'; // 공급자에 표시되는 이름
    }
}


// echo $author_id . ' ' . $author . ' ' . $hp . ' ' . $signer;

// JSON 데이터 파싱
$items = [];
$other_costs = [];
$notices = [];

if($estimate_data) {
    if(!empty($estimate_data['items'])) {
        $items = json_decode($estimate_data['items'], true) ?? [];
    }
    if(!empty($estimate_data['other_costs'])) {
        $other_costs = json_decode($estimate_data['other_costs'], true) ?? [];
    }
    if(!empty($estimate_data['notices'])) {
        $notices = json_decode($estimate_data['notices'], true) ?? [];
    }
}

// 보기 모드에서 합계 미리 계산
if($mode == 'view') {
    $total_supply = 0;
    $total_tax = 0;
    $other_costs_supply = 0;
    $other_costs_tax = 0;
    
    // 상품 합계 계산
    foreach($items as $item) {
        $supply_amount = $item['area'] * $item['unit_price'];
        $tax_amount = $supply_amount * 0.1;
        $total_supply += $supply_amount;
        $total_tax += $tax_amount;
    }
    
    // 기타 비용 합계 계산
    if(!empty($other_costs)) {
        foreach($other_costs as $cost) {
            // 28헤베 이상일 때는 단가 적용, 아닐때는 70만원 최소시공비 적용, 단가, 금액 강제 조정
            if($cost['unit'] === '㎡' || $cost['unit'] === 'm²') {
                if($cost['quantity'] > 28) {
                    $cost_supply = ($cost['quantity'] ?? 0) * ($cost['unit_price'] ?? 0);
                } else {
                    $cost_supply = $cost['unit_price'] ;
                }
            } else {
                $cost_supply = ($cost['quantity'] ?? 0) * ($cost['unit_price'] ?? 0);
            }
            $cost_tax = $cost_supply * 0.1;
            $other_costs_supply += $cost_supply;
            $other_costs_tax += $cost_tax;
        }
    }
    
    // 최종 합계 계산
    $total_ex_vat = $total_supply + $other_costs_supply;
    $total_inc_vat = $total_ex_vat + $total_tax + $other_costs_tax;
}

// echo $hp . ' ' . $signer;
?>

<form method="post" id="estimateForm">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">    
    <input type="hidden" id="num" name="num" value="<?= $num ?>">
    <input type="hidden" id="total_ex_vat" name="total_ex_vat" value="<?= $total_ex_vat ?>">
    <input type="hidden" id="total_inc_vat" name="total_inc_vat" value="<?= $total_inc_vat ?>">

<div class="container-fluid my-3">
    <div class="card shadow-sm mb-4 ">
        <div class="card-body p-4">
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3 fs-4">
                <div class="d-flex align-items-center">
                    <?php 
                    if($mode == 'insert') echo '포미스톤 견적서 작성';
                    elseif($mode == 'modify') echo '포미스톤 견적서 수정';
                    elseif($mode == 'copy') echo '포미스톤 견적서 복사';
                    ?>                
                    <span class="ms-5 fs-6">작성자 : </span>
                    <input class="form-control form-control-sm ms-2 me-2 w100px fs-6 fw-bold" id="author" name="author"  type="text" value="<?= htmlspecialchars($author) ?>" >                    
                    <span class="ms-1 fs-6">작성자ID :</span>
                    <input class="form-control form-control-sm ms-2 me-2 w150px fs-6 fw-bold" id="author_id" name="author_id"   type="text" value="<?= htmlspecialchars($author_id) ?>" >                    
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" id="saveBtn" class="btn btn-primary btn-sm me-2">저장</button>
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="generatePDF()">PDF 저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($mode == 'view'): ?>
                <input type="hidden" id="author_id" name="author_id" value="<?= $author_id ?>">
                <input type="hidden" id="author" name="author" value="<?= $author ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                <h4>포미스톤 견적서 보기  
                <span class="ms-5 fs-6">작성자 : <?= htmlspecialchars($author) ?></span> 
                <span class="ms-1 fs-6">작성자ID : <?= htmlspecialchars($author_id) ?></span>                                    
                </h4>                
                </div>
                <div>
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="editEstimate()">수정</button>
                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="copyEstimate()">복사</button>
                    <button type="button" class="btn btn-danger btn-sm me-2" onclick="deleteEstimate()">삭제</button>      
                    <button type="button" class="btn btn-info btn-sm me-2" onclick="convertToOutorder()">수주서로 변환</button>
                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="openEstimatePDF()">PDF 저장</button>
                    <!-- <button type="button" class="btn btn-dark me-2" onclick="generatePDF()">PDF 저장</button> -->
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
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
                <h1 class="h2 fw-bold mb-2">견적서</h1>                
            </div>
            <!-- 수신 & 공급자 정보 -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-1 h-100">                        
                        <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                            <p class="mb-1">
                                <label for="recipient">수신 : </label>
                                <input type="text" id="recipient" name="recipient" value="<?= htmlspecialchars($recipient) ?>" class="form-control form-control-sm" placeholder="수신처명" autocomplete="off">
                            </p>
                            <p class="mb-1">
                                <label for="division">구분 : </label>
                                <select id="division" name="division" class="form-select form-select-sm w-auto">
                                    <option value="유통" <?= ($division == '유통' || empty($division)) ? 'selected' : '' ?>>유통</option>
                                    <option value="소비자" <?= ($division == '소비자') ? 'selected' : '' ?>>소비자</option>
                                </select>
                            </p>
                            <p class="mb-1">현장명 : <input type="text" name="site_name" value="<?= htmlspecialchars($site_name) ?>" class="form-control form-control-sm" placeholder="현장명" autocomplete="off"></p>
                            <p class="mb-1">전화번호 : <input type="text" id="recipient_phone" name="recipient_phone" value="<?= htmlspecialchars($recipient_phone) ?>" class="form-control form-control-sm w-auto" placeholder="전화번호" autocomplete="off" ></p>
                            <p class="mb-1"><label for="quote_date">견적일자 : </label>
                                <input type="date" id="quote_date" name="quote_date" value="<?= $quote_date ?>" class="form-control form-control-sm w-auto">
                            </p>
                            <p class="mb-0 mt-3 text-center"> <strong> 아래와 같이 견적합니다.</strong></p>                           

                        <?php else: ?>
                            <!-- 견적서 보기 모드 -->
                            <p class="mb-1" style="font-size: 1.2em">수신 : <u id="recipient-text"><?= htmlspecialchars($recipient) ?></u> 귀하</p>
                            <p class="mb-1">구분 : <u id="division-text"><?= htmlspecialchars($division) ?></u></p>
                            <p class="mb-1">현장명 : <u id="site-name-text"><?= htmlspecialchars($site_name) ?></u></p>
                            <p class="mb-1">전화번호 : <u id="contact-text" class="recipient_phone"><?= htmlspecialchars($recipient_phone) ?></u></p>
                            <p class="mb-1">견적일자 : <u id="quote-date-text"><?= date('Y년 m월 d일', strtotime($quote_date)) ?></u></p>
                            <p class="mb-0 mt-3 text-center"> <strong> 아래와 같이 견적합니다.</strong></p>
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
                                    본사 : <?= htmlspecialchars($head_office_address) ?><br>
                                    전시장 : <?= htmlspecialchars($showroom_address) ?>
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
                                    <span class="fw-bold signer-text"><?= htmlspecialchars($signer) ?></span>
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

            <!-- 견적 상품명별 내역 테이블 -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="mainTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%;">No.</th>
                            <th scope="col" style="width: 32%;">상품명</th>
                            <th scope="col" style="width: 8%;">규격(Size)</th>
                            <th scope="col" style="width: 6%;">분류</th>
                            <th scope="col" style="width: 7%;">수량(EA)</th>
                            <th scope="col" style="width: 6%;">m²</th>
                            <th scope="col" style="width: 7%;">단가</th>
                            <th scope="col" style="width: 7%;">공급가액</th>
                            <th scope="col" style="width: 7%;">세액</th>
                            <th scope="col" style="width: 10%;">비고</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <?php 
                        $item_counter = 1;
                        $total_supply = 0;
                        $total_tax = 0;
                        
                        if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): 
                            // 저장된 데이터가 있으면 모든 행을 생성, 없으면 첫 번째 행만 생성
                            $item_count = max(1, count($items));
                            for($i = 0; $i < $item_count; $i++): ?>
                            <tr class="item-row" data-row="<?= $i ?>">
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="me-2"><?= $i + 1 ?></span>
                                        <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                            <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addRowAfter(<?= $i ?>)" title="아래에 행 추가">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyRow(<?= $i ?>)" title="행 복사">
                                                <i class="bi bi-files"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteRow(<?= $i ?>)" title="행 삭제">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: left;">
                                    <select name="items[<?= $i ?>][product_code]" class="form-select form-select-sm product-select" data-row="<?= $i ?>" style="text-align: left;" data-initial-value="<?= htmlspecialchars($items[$i]['product_code'] ?? '') ?>">
                                        <option value="">상품을 선택하세요</option>
                                    </select>
                                </td>
                                <td><input type="text" name="items[<?= $i ?>][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)" value="<?= htmlspecialchars($items[$i]['specification'] ?? '') ?>" readonly></td>
                                <td><input type="text" name="items[<?= $i ?>][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" value="<?= htmlspecialchars($items[$i]['size'] ?? '') ?>" readonly></td>
                                <td><input type="number" name="items[<?= $i ?>][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="<?= $items[$i]['quantity'] ?? '1' ?>"></td>
                                <td><input type="text" name="items[<?= $i ?>][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="<?= $items[$i]['area'] ?? '' ?>" readonly></td>
                                <td><input type="text" name="items[<?= $i ?>][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="<?= number_format($items[$i]['unit_price'] ?? 0) ?>" ></td>
                                <td class="text-end supply-amount">0</td>
                                <td class="text-end tax-amount">0</td>
                                <td><input type="text" name="items[<?= $i ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($items[$i]['remarks'] ?? '') ?>"></td>
                                <?php
                                $supply_amount = floatval(str_replace(',', '', $items[$i]['area'])) * floatval(str_replace(',', '', $items[$i]['unit_price']));
                                $tax_amount = $supply_amount * 0.1;
                                    $total_supply += $supply_amount;
                                    $total_tax += $tax_amount;
                                ?>
                            </tr>
                            <?php endfor; ?>
                        <?php else:
                        // view 모드에서 상품 목록 가져오기
                        $total_supply = 0;
                        $total_tax = 0;
                        // echo '<pre>';
                        // echo print_r($items);
                        // echo '</pre>';

                        foreach($items as $item): 
                            $supply_amount = floatval(str_replace(',', '', $item['area'])) * floatval(str_replace(',', '', $item['unit_price']));
                            $tax_amount = $supply_amount * 0.1; // 10% 부가세

                            $total_supply += $supply_amount;
                            $total_tax += $tax_amount;
                            
                            // 상품명 표시 (상품코드가 있으면 상품코드 기반으로, 없으면 기존 product_name 사용)
                            $display_product_name = '';
                            if(!empty($item['product_code'])) {
                                try {
                                    $product_name_sql = "SELECT prodcode, texture_kor, design_kor FROM mirae8440.phomi_unitprice WHERE prodcode = :prodcode";
                                    $product_name_stmt = $pdo->prepare($product_name_sql);
                                    $product_name_stmt->bindParam(':prodcode', $item['product_code'], PDO::PARAM_STR);
                                    $product_name_stmt->execute();
                                    $product_info = $product_name_stmt->fetch(PDO::FETCH_ASSOC);
                                    if($product_info) {
                                        $display_product_name =  $product_info['texture_kor'] . ' ' . $product_info['design_kor'];
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
                            <td class="text-end"><?= number_format(floatval(str_replace(',', '', $supply_amount))) ?></td>
                            <td class="text-end"><?= number_format(floatval(str_replace(',', '', $tax_amount))) ?></td>
                            <td class="remarks-input"><?= htmlspecialchars($item['remarks'] ?? '') ?></td>
                        </tr>
                        <?php 
                        $item_counter++;
                        endforeach; 
                        endif; ?>
                        
                    </tbody>
                    <tfoot class="text-end">
                        <!-- 소계 행 -->                        
                        <tr class="table-secondary">
                            <td colspan="7" class="text-end fw-medium">소계</td>
                            <td class="text-end fw-bold" id="totalSupply"><?= number_format($total_supply ?? 0) ?></td>
                            <td class="text-end fw-bold" id="totalTax"><?= number_format($total_tax ?? 0) ?></td>
                            <td></td>
                        </tr>                        
                        <!-- 합계 행 제거 - 상단에 별도 테이블로 표시 -->
                    </tfoot>
                </table>                
            </div>

            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                <!-- 기타 비용 -->
            <div class="mb-0">
                <div class="d-flex align-items-center justify-content-start">                    
                    <div class="alert alert-primary p-1" role="alert">
                        자동산출 체크박스를 선택시 자동으로 본드 및 시공비가 계산됩니다. 체크박스 해제시에는 수동으로 입력가능합니다.
                    </div>
                </div>                
            </div>
            <?php endif; ?>

            <!-- 기타 비용 -->
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-start mb-2">
                    <h6 class="fw-semibold mb-0">기타 비용 (부자재 및 인건비 등)</h6>
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <div class="form-check ms-3 d-flex align-items-center">
                        <input class="form-check-input ms-5" type="checkbox" id="etc_autocheck" name="etc_autocheck" <?= $etc_autocheck == '1' ? 'checked' : '' ?> style="transform: scale(1.5);">
                        <label class="form-check-label fs-6 ms-3 text-primary" for="etc_autocheck">
                            자동산출
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
                    </button> -->
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
                                <th scope="col" style="width: 20%;">비고</th>
                            </tr>
                        </thead>
                        <tbody id="otherCostsTableBody">
                            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): 
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
                                    <td><input type="text" name="other_costs[<?= $c ?>][item]" class="form-control form-control-sm text-start" placeholder="항목" value="<?= htmlspecialchars($other_costs[$c]['item'] ?? '') ?>"></td>
                                    <td><input type="text" name="other_costs[<?= $c ?>][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="<?= htmlspecialchars($other_costs[$c]['unit'] ?? '') ?>"></td>
                                    <td><input type="number" name="other_costs[<?= $c ?>][quantity]" class="form-control form-control-sm text-end cost-quantity-input" placeholder="수량" step="1" value="<?= $other_costs[$c]['quantity'] ?? '' ?>"></td>
                                    <td><input type="text" name="other_costs[<?= $c ?>][unit_price]" class="form-control form-control-sm text-end cost-unit-price-input" placeholder="단가" value="<?= number_format($other_costs[$c]['unit_price'] ?? 0) ?>"></td>
                                    <td><input type="text" name="other_costs[<?= $c ?>][supply_amount]" class="form-control form-control-sm text-end cost-supply-amount" value="<?= number_format(($other_costs[$c]['quantity'] ?? 0) * ($other_costs[$c]['unit_price'] ?? 0)) ?>" readonly></td>
                                    <td><input type="text" name="other_costs[<?= $c ?>][tax_amount]" class="form-control form-control-sm text-end cost-tax-amount" value="<?= number_format(($other_costs[$c]['quantity'] ?? 0) * ($other_costs[$c]['unit_price'] ?? 0) * 0.1) ?>" readonly></td>
                                    <td><input type="text" name="other_costs[<?= $c ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($other_costs[$c]['remarks'] ?? '') ?>"></td>
                                </tr>
                                <?php endfor; ?>
                            <?php else:
                            // view 모드에서 기타비용 목록 가져오기
                            ?>
                            <!-- 체크박스 숨김형태 -->
                            <input type="hidden" id="etc_autocheck" name="etc_autocheck" value="<?= $etc_autocheck ?>">
                            <input type="hidden" id="exclude_construction_cost" name="exclude_construction_cost" value="<?= $exclude_construction_cost ?>">
                            <input type="hidden" id="exclude_molding" name="exclude_molding" value="<?= $exclude_molding ?>">
                            <?php
                            if(!empty($other_costs)):
                                // echo "<pre>";
                                // print_r($other_costs);
                                // echo "</pre>";

                            foreach($other_costs as $cost): 
                                if($cost['unit'] === '㎡' || $cost['unit'] === 'm²') {
                                    if($cost['quantity'] > 28) {
                                        $cost_supply_amount = ($cost['quantity'] ?? 0) * ($cost['unit_price'] ?? 0);
                                    } else {
                                        $cost_supply_amount = $cost['unit_price'] ;
                                    }
                                } else {
                                    $cost_supply_amount = ($cost['quantity'] ?? 0) * ($cost['unit_price'] ?? 0);
                                }   

                                $cost_tax_amount = $cost_supply_amount * 0.1;
                            ?>

                            <tr class="other-cost-row-view">
                                <td class="cost-category-input"><?= htmlspecialchars($cost['category'] ?? '') ?></td>
                                <td class="cost-item-input text-start"><?= htmlspecialchars($cost['item'] ?? '') ?></td>
                                <td class="cost-unit-input text-center"><?= htmlspecialchars($cost['unit'] ?? '') ?></td>                                
                                <td class="cost-quantity-input text-end"><?= ($cost['quantity'] ?? 0) > 0 ? $cost['quantity'] : '' ?></td>
                                <td class="cost-unit-price-input text-end"><?= ($cost['quantity'] ?? 0) > 0 ?  number_format($cost['unit_price'] ?? 0) : '' ?></td>
                                <td class="cost-supply-amount text-end"><?= ($cost['quantity'] ?? 0) > 0 ? number_format($cost_supply_amount) : '' ?></td>
                                <td class="cost-tax-amount text-end"><?= ($cost['quantity'] ?? 0) > 0 ?  number_format($cost_tax_amount) : '' ?></td>
                                <td class="cost-remarks-input"><?= htmlspecialchars($cost['remarks'] ?? '') ?></td>
                            </tr>
                            <?php endforeach; endif; endif; ?>
                            
                        </tbody>
                        <tfoot class="text-end">
                            <!-- 기타비용 소계 행 -->                            
                            <tr class="table-secondary">
                                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                    <td colspan="6" class="text-end fw-medium">소계</td>
                                <?php else: ?>
                                    <td colspan="5" class="text-end fw-medium">소계</td>
                                <?php endif; ?>
                                <td class="text-end fw-bold" id="totalOtherCostsSupply"><?= number_format($other_costs_supply ?? 0) ?></td>
                                <td class="text-end fw-bold" id="totalOtherCostsTax"><?= number_format($other_costs_tax ?? 0) ?></td>
                                <td></td>                                
                            </tr>               
                        </tfoot>             
                    </table>
                </div>
            </div>

            <!-- 주요 안내 -->
            <div class="mb-4">
                <h6 class="fw-semibold mb-2">주요 안내</h6>
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <div id="noticesContainer">
                        <?php if(!empty($notices)): ?>
                            <?php foreach($notices as $index => $notice): ?>
                            <div class="mb-2 d-flex align-items-center notice-row">
                                <button type="button" class="btn btn-outline-danger btn-sm me-2 remove-notice" title="안내사항 삭제" style="width:28px;min-width:28px;padding:0 0.5rem;">-</button>
                                <input type="text" name="notices[]" class="form-control form-control-sm" placeholder="주요 안내 사항을 입력하세요" value="<?= htmlspecialchars($notice) ?>">
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="mb-2 d-flex align-items-center notice-row">
                                <button type="button" class="btn btn-outline-danger btn-sm me-2 remove-notice" title="안내사항 삭제" style="width:28px;min-width:28px;padding:0 0.5rem;">-</button>
                                <input type="text" name="notices[]" class="form-control form-control-sm" placeholder="주요 안내 사항을 입력하세요">
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-info" id="addNotice">+ 안내사항 추가</button>
                    <script>
                    $(document).on('click', '.remove-notice', function() {
                        // 안내사항 행이 1개만 남았을 때는 빈 input만 남기고 삭제하지 않음
                        var $container = $('#noticesContainer');
                        if ($container.find('.notice-row').length > 1) {
                            $(this).closest('.notice-row').remove();
                            alertToast('안내사항 삭제');
                        } else {
                            // 마지막 1개 남았을 때는 input만 비움
                            $(this).closest('.notice-row').find('input[type="text"]').val('');
                            alertToast('1개는 삭제가 안되므로 남겨둡니다.');
                        }
                    });
                    </script>
                <?php else:
                if(!empty($notices)): ?>
                    <ol class="ps-1 mb-0 fw-bold text-dark">
                        <?php foreach($notices as $notice): ?>
                        <li><?= htmlspecialchars($notice) ?></li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; endif; ?>
            </div>
            <!-- 입금계좌정보 -->
            <div class="mb-1">                                             
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <p class="mb-0"><h6><p class="text-center badge bg-primary">입금계좌정보 : <input type="text" name="payment_account" value="<?= htmlspecialchars($payment_account) ?>" class="form-control form-control-sm d-inline-block" style="width: 300px;" placeholder="입금계좌정보"></p></h6></p>
                <?php else: ?>
                    <p class="mb-0"><h6><p class="text-center badge bg-primary">입금계좌정보 : <?= htmlspecialchars($payment_account) ?></p></h6></p>
                <?php endif; ?>
            </div>

            <!-- 면책 조항 -->
            <div class="small text-muted mb-2">
                <p>1. 상기 견적의 금액은 이후 확정 시 금액이 변동될 수 있습니다.</p>
                <p>2. 제품 현장 도착 후 즉시 현장 검수를 원칙으로 하며, 반품·교환 시 추가 운송비가 발생할 수 있습니다.</p>
                <p>3. 견적서 내역 검토는 구매자의 의무이며, 미검토로 인한 배송 오류에 대한 책임은 구매자에게 있습니다.</p>
                <p>4. 양중 시 찍힘이 발생할 수 있으니 취급에 주의하시기 바랍니다.</p>
                <p>5. 본 견적서로 계약서를 갈음하며, 납기 확정 시 견적 내용에 동의하는 것으로 간주합니다.</p>
            </div>
            
        </div>
    </div>
</div>
</div>
</form>

<script>
$(document).ready(function() {
    let mode = $("#mode").val();
    // Select2 초기화
    function initializeSelect2() {
        $('.product-select').select2({
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

    // 초기 Select2 설정
    initializeSelect2();
    
    // 조회모드가 아닐 때만 상품 데이터 로딩 처리
    if (mode !== 'view') {
        // 기존 product-select 요소들에 상품 옵션 채우기 (기존 데이터 처리 포함)
        let processedCount = 0;
        const totalSelects = $('.product-select').length;
        
        $('.product-select').each(function() {
            const selectElement = $(this);
            
            // populateProductOptions 함수가 상품 옵션을 채우고, 기존 값이 있다면 설정까지 처리
            populateProductOptions(selectElement, function() {
                // populateProductOptions가 완료된 후 이 콜백이 실행됨
                // 이제 해당 행의 데이터를 처리
                processRowData(selectElement);
                
                processedCount++;
                if (processedCount === totalSelects) {
                    // 모든 상품 데이터 처리 완료 후 기타비용 계산 및 전체 합계 업데이트
                    setTimeout(function() {
                        calculateOtherCostsFromProducts(true);
                            // 상품 데이터 로딩 완료 후 전체 합계 업데이트
                            setTimeout(function() {
                                updateTotals();
                            }, 100); // 100ms 더 지연
                        }, 100);                                        
                }
            });
        });
    }
    
    // 조회모드가 아닐 때만 추가 안전장치 실행
    if (mode !== 'view') {
        // 추가 안전장치: 모든 Select2 초기화 완료 후 한 번 더 확인
        setTimeout(function() {
            $('.product-select').each(function() {
                const selectElement = $(this);
                const selectedValue = selectElement.val();
                const itemRow = selectElement.closest('.item-row');
                
                // 값이 있지만 관련 필드가 비어있는 경우 재처리
                if (selectedValue && itemRow.find('.specification-input').val() === '') {
                    console.log('추가 확인: 상품 데이터 재처리', selectedValue);
                    processRowData(selectElement);
                }
            });
            
            // 상품이 없는 경우에도 전체 합계 업데이트
            if ($('.product-select').length === 0) {
                updateTotals();
            }
        }, 600); // Keep this safety net for now, it's a good fallback.
    }

    // 개별 행 데이터 처리 함수
    function processRowData(selectElement) {
        const selectedValue = selectElement.val();
        if (selectedValue) {
            const selectedOption = selectElement.find('option[value="' + selectedValue + '"]');
            if (selectedOption.length > 0) {
                const itemRow = selectElement.closest('.item-row');
                const spec = selectedOption.data('spec');
                const size = selectedOption.data('size');
                const area = selectedOption.data('area');
                const unitPrice = selectedOption.data('unit-price');
                
                // 데이터가 유효한지 확인
                if (spec && size && unitPrice) {
                    itemRow.find('.specification-input').val(size);
                    itemRow.find('.size-input').val(spec);
                    
                    // 초기 로딩 플래그 설정 (이후 상품 변경 시 구분용)
                    itemRow.data('initial-load', true);
                    
                    // 기존 저장된 단가 확인 - 수정된 단가가 있으면 유지
                    const savedUnitPrice = itemRow.find('.unit-price-input').val();
                    let unitPriceVal = 0;
                    
                    if (savedUnitPrice && savedUnitPrice !== '' && savedUnitPrice !== '0') {
                        // 기존에 수정된 단가가 있으면 그 값을 유지
                        unitPriceVal = parseFloat(savedUnitPrice.replace(/,/g, '')) || 0;
                        console.log('초기 로딩 - 기존 수정된 단가 유지:', unitPriceVal);
                    } else {
                        // 기존 단가가 없거나 0이면 단가표의 기본값 사용
                        unitPriceVal = parseFloat(unitPrice.toString().replace(/,/g, '')) || 0;
                        itemRow.find('.unit-price-input').val(unitPriceVal.toLocaleString());
                        console.log('초기 로딩 - 단가표 기본값 사용:', unitPriceVal);
                    }
                    
                    // 기존 저장된 수량과 면적이 있으면 그 값을 사용, 없으면 기본값 계산
                    const savedQuantity = parseFloat(itemRow.find('.quantity-input').val()) || 1;
                    const savedArea = parseFloat(itemRow.find('.area-input').val()) || 0;
                    
                    // 실제 면적 계산 (규격에서 면적 추출)
                    let actualArea = 0;
                    if (size.includes('*')) {
                        const sizeParts = size.split('*');
                        if (sizeParts.length >= 2) {
                            const width = parseFloat(sizeParts[0]) || 0;
                            const height = parseFloat(sizeParts[1]) || 0;
                            actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                        }
                    } else if (size.includes('×')) {
                        const sizeParts = size.split('×');
                        if (sizeParts.length >= 2) {
                            const width = parseFloat(sizeParts[0]) || 0;
                            const height = parseFloat(sizeParts[1]) || 0;
                            actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                        }
                    } else {
                        // 단일 숫자인 경우 (가정: 정사각형)
                        const singleSize = parseFloat(size) || 0;
                        actualArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
                    }
                    
                    // 저장된 면적이 있으면 그 값을 사용, 없으면 계산된 값 사용
                    const finalArea = savedArea > 0 ? savedArea : (savedQuantity * actualArea);
                    itemRow.find('.area-input').val(finalArea.toFixed(2));
                    
                    // 금액 계산 (수량 * 헤베)
                    const supplyAmount = finalArea * unitPriceVal;
                    const taxAmount = supplyAmount * 0.1;
                    
                    itemRow.find('.supply-amount').text('' + supplyAmount.toLocaleString());
                    itemRow.find('.tax-amount').text('' + taxAmount.toLocaleString());
                    
                    console.log('기존 데이터 로딩 - size:', size, 'actualArea:', actualArea, 'savedQuantity:', savedQuantity, 'savedArea:', savedArea, 'finalArea:', finalArea, 'unitPriceVal:', unitPriceVal);
                    
                    // 개별 행 계산 후 소계 업데이트
                    updateItemSubtotals();
                    updateTotals();
                } else {
                    console.warn('상품 데이터가 불완전합니다:', { selectedValue, spec, size, unitPrice });
                }
            } else {
                console.warn('선택된 상품 옵션을 찾을 수 없습니다:', selectedValue);
            }
        } else {
            // 상품이 선택되지 않은 경우에도 기존 데이터가 있으면 금액 계산
            const itemRow = selectElement.closest('.item-row');
            const savedQuantity = parseFloat(itemRow.find('.quantity-input').val()) || 0;
            const savedArea = parseFloat(itemRow.find('.area-input').val()) || 0;
            const unitPriceText = itemRow.find('.unit-price-input').val();
            
            if (savedQuantity > 0 && savedArea > 0 && unitPriceText) {
                const unitPriceVal = parseFloat(unitPriceText.replace(/,/g, '')) || 0;
                const supplyAmount = savedArea * unitPriceVal;
                const taxAmount = supplyAmount * 0.1;
                
                itemRow.find('.supply-amount').text('' + supplyAmount.toLocaleString());
                itemRow.find('.tax-amount').text('' + taxAmount.toLocaleString());
                
                console.log('기존 데이터 금액 계산 - savedQuantity:', savedQuantity, 'savedArea:', savedArea, 'unitPriceVal:', unitPriceVal, 'supplyAmount:', supplyAmount);
                
                // 개별 행 계산 후 소계 업데이트
                updateItemSubtotals();
                updateTotals();
            }
        }
    }
        
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
    if (mode !== 'view') {
        // 기존 기타비용 데이터의 금액 계산
        $('.cost-row').each(function() {
            const quantity = parseFloat($(this).find('.cost-quantity-input').val()) || 0;
            const unitPriceText = $(this).find('.cost-unit-price-input').val();
            const category = $(this).find('input[name*="[category]"]').val();
            const item = $(this).find('input[name*="[item]"]').val();
            
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
                
                $(this).find('.cost-supply-amount').val( supplyAmount.toLocaleString());
                $(this).find('.cost-tax-amount').val(taxAmount.toLocaleString());
                
                console.log('기존 기타비용 금액 계산 - category:', category, 'item:', item, 'quantity:', quantity, 'unitPrice:', unitPrice, 'supplyAmount:', supplyAmount);
            }
        });
    }
    
    
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
    
    // 행 추가 함수 (기존 버튼용)
    $('#addItemRow').click(function() {
        addRowAfter($('.item-row').length - 1);
        alertToast('행 추가');
    });

    // 기타비용 행 추가 함수 (기존 버튼용)
    $('#addCostRow').click(function() {
        addCostRowAfter($('.cost-row').length - 1);
        alertToast('기타비용 행 추가');
    });
    
    // 저장 버튼 이벤트 리스너
    $('#saveBtn').click(function() {
        saveEstimate();
    });
    
    // 기타비용 자동산출 체크박스 이벤트 리스너
    $('#etc_autocheck').change(function() {
        console.log('기타비용 자동산출 체크박스 변경:', $(this).is(':checked'));
        // 기타비용 테이블 재계산 (강제 재계산)
        calculateOtherCostsFromProducts(true);
    });
    
    // 시공비 제외 체크박스 이벤트 리스너
    $('#exclude_construction_cost').change(function() {
        console.log('시공비 제외 체크박스 변경:', $(this).is(':checked'));
        // 기타비용 테이블 재계산 (강제 재계산)
        calculateOtherCostsFromProducts(true);
    });
    
    // 몰딩 제외 체크박스 이벤트 리스너
    $('#exclude_molding').change(function() {
        console.log('몰딩 제외 체크박스 변경:', $(this).is(':checked'));
        // 기타비용 테이블 재계산 (강제 재계산)
        calculateOtherCostsFromProducts(true);
    });
    
    // 기타비용 재계산 버튼 이벤트 리스너
    // $('#recalculateOtherCostsBtn').click(function() {
    //     if (confirm('기타비용을 재계산하시겠습니까? 수동으로 수정한 데이터가 모두 초기화됩니다.')) {
    //         console.log('기타비용 재계산 버튼 클릭');
    //         // 모든 수동 수정 플래그 초기화
    //         $('.cost-row').data('manually-modified', false);
    //         // 강제 재계산 실행
    //         calculateOtherCostsFromProducts(true);
    //         alertToast('기타비용이 재계산되었습니다.');
    //     }
    // });
    
    
    // 기타비용 행 입력 이벤트
    $(document).on('input', '.cost-quantity-input, .cost-unit-price-input', function(e) {
        const row = $(this).closest('.cost-row');

        // 프로그래밍 방식으로 값이 변경된 경우인지 확인
        const isProgrammaticChange = $(this).data('programmatic-change') === true;

        if (!isProgrammaticChange) {
            // 수동 수정 플래그 설정
            row.data('manually-modified', true);

            // 기타 비용의 수량을 수동으로 입력하면 자동산출 체크박스 해제
            if ($(this).hasClass('cost-quantity-input')) {
                const etcAutoCheckbox = $('#etc_autocheck');
                if (etcAutoCheckbox.is(':checked')) {
                    etcAutoCheckbox.prop('checked', false);
                    console.log('기타비용 수량 수동 입력으로 인해 자동산출 체크박스 해제됨 (모드: ' + mode + ')');
                    alertToast('자동산출 해제');
                }
            }
        } else {
            // 프로그래밍 방식 변경 플래그 제거
            $(this).removeData('programmatic-change');
            console.log('프로그래밍 방식으로 값이 변경되어 자동산출 체크박스 유지됨');
        }

        calculateCostRow(row);

        // 기타비용 소계 즉시 업데이트
        updateOtherCostsSubtotal();

        // 전체 합계 업데이트
        updateTotals();
    });
    
    // 기타비용 카테고리/품목 수동 수정 추적
    $(document).on('input', '.cost-row input[name*="[category]"], .cost-row input[name*="[item]"]', function() {
        const row = $(this).closest('.cost-row');
        row.data('manually-modified', true);
    });
    
    // 상품 선택 시 기타비용 자동 계산 - 무한루프 방지를 위해 제거
    // $(document).on('select2:select select2:unselect', '.product-select', function() {
    //     setTimeout(function() {
    //         initializeOtherCostsTable();
    //     }, 100);
    // });

    // 안내사항 추가
    let noticeCount = <?= count($notices) ?>;
    $('#addNotice').click(function() {
        const newNotice = `
            <div class="mb-2">
                <input type="text" name="notices[]" class="form-control form-control-sm" placeholder="주요 안내 사항을 입력하세요">
            </div>
        `;
        $('#noticesContainer').append(newNotice);
        noticeCount++;
    });

    // 상품 선택 시 관련 정보 자동 채우기
    $(document).on('select2:select select2:unselect', '.product-select', function() {
        const row = $(this).data('row');
        const selectedProductCode = $(this).val();
        const selectedOption = $(this).find('option:selected');
        const itemRow = $(this).closest('.item-row');

        if (selectedProductCode) {
            const spec = selectedOption.data('spec');
            const size = selectedOption.data('size');
            const thickness = selectedOption.data('thickness');
            const area = selectedOption.data('area');
            const unitPrice = selectedOption.data('unit-price');

            itemRow.find('.specification-input').val(size);
            itemRow.find('.size-input').val(spec);
            
            // 실제 면적 계산 (규격에서 면적 추출)
            let actualArea = 0;
            if (size.includes('*')) {
                const sizeParts = size.split('*');
                if (sizeParts.length >= 2) {
                    const width = parseFloat(sizeParts[0]) || 0;
                    const height = parseFloat(sizeParts[1]) || 0;
                    actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                }
            } else if (size.includes('×')) {
                const sizeParts = size.split('×');
                if (sizeParts.length >= 2) {
                    const width = parseFloat(sizeParts[0]) || 0;
                    const height = parseFloat(sizeParts[1]) || 0;
                    actualArea = (width * height) / 1000000; // mm²를 m²로 변환
                }
            } else {
                // 단일 숫자인 경우 (가정: 정사각형)
                const singleSize = parseFloat(size) || 0;
                actualArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
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
            
            // 금액 계산
            const supplyAmount = totalArea * unitPriceVal;
            const taxAmount = supplyAmount * 0.1;

            itemRow.find('.supply-amount').text('' + supplyAmount.toLocaleString());
            itemRow.find('.tax-amount').text('' + taxAmount.toLocaleString());
            
            console.log('상품 선택 - size:', size, 'actualArea:', actualArea, 'quantity:', quantity, 'totalArea:', totalArea, 'unitPriceVal:', unitPriceVal);

            // 각 행의 기본값 설정
            calculateOtherCostsFromProducts(false); // 상품 선택 시에는 일반 재계산    
            
            // 기타비용 테이블 연동 - 무한루프 방지를 위해 제거
            // setTimeout(function() {
            //     initializeOtherCostsTable();
            // }, 100);
        } else {
            itemRow.find('.specification-input').val('');
            itemRow.find('.size-input').val('');
            itemRow.find('.area-input').val('');
            itemRow.find('.unit-price-input').val('');
            itemRow.find('.supply-amount').text('0');
            itemRow.find('.tax-amount').text('0');
            
            // 기타비용 테이블 연동 - 무한루프 방지를 위해 제거
            // setTimeout(function() {
            //     initializeOtherCostsTable();
            // }, 100);
        }
    });

    // 수량을 변경시 금액 계산
    $(document).on('input', '.quantity-input, .unit-price-input', function() {
        const row = $(this).closest('tr');
        const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
        
        // 단가 안전하게 추출 - NaN 방지
        let unitPrice = 0;
        const unitPriceText = row.find('.unit-price-input').val();
        if (unitPriceText && unitPriceText !== '') {
            const cleanUnitPrice = unitPriceText.replace(/,/g, '');
            unitPrice = parseFloat(cleanUnitPrice) || 0;
        }
        const specification = row.find('.specification-input').val() || '';
        
        // 실제 면적 계산 (규격에서 면적 추출)
        let actualArea = 0;
        if (specification.includes('*')) {
            const sizeParts = specification.split('*');
            if (sizeParts.length >= 2) {
                const width = parseFloat(sizeParts[0]) || 0;
                const height = parseFloat(sizeParts[1]) || 0;
                actualArea = (width * height) / 1000000; // mm²를 m²로 변환
            }
        } else if (specification.includes('×')) {
            const sizeParts = specification.split('×');
            if (sizeParts.length >= 2) {
                const width = parseFloat(sizeParts[0]) || 0;
                const height = parseFloat(sizeParts[1]) || 0;
                actualArea = (width * height) / 1000000; // mm²를 m²로 변환
            }
        } else {
            // 단일 숫자인 경우 (가정: 정사각형)
            const singleSize = parseFloat(specification) || 0;
            actualArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
        }
        
        // m² 열 업데이트 (수량 × 실제면적)
        let totalArea = quantity * actualArea;
        totalArea = totalArea.toFixed(2);
        row.find('.area-input').val(totalArea);
        
        // 공급가액변경, 세액변경   
        unitPrice = parseFloat(row.find('.unit-price-input').val().replace(/,/g, '')) || 0;
        const supplyAmount = totalArea * unitPrice;
        const taxAmount = supplyAmount * 0.1;
        
        row.find('.supply-amount').text('' + supplyAmount.toLocaleString());
        row.find('.tax-amount').text('' + taxAmount.toLocaleString());
        
        console.log('수량 변경 - specification:', specification, 'actualArea:', actualArea, 'quantity:', quantity, 'totalArea:', totalArea, 'supplyAmount:', supplyAmount, 'taxAmount:', taxAmount);
        // 기타비용 테이블 재계산
        calculateOtherCostsFromProducts(true); // 수량 변경 시에는 일반 재계산                
    });
    
    // 기존 데이터 처리는 이제 초기 로딩 시 처리됨 (위의 populateProductOptions 콜백에서 처리)
    
    // 행 추가 함수 (기존 버튼용)
    $('#addItemRow').click(function() {
        addRowAfter($('.item-row').length - 1);
        alertToast('행 추가');
    });

    // 기타비용 행 추가 함수 (기존 버튼용)
    $('#addCostRow').click(function() {
        addCostRowAfter($('.cost-row').length - 1);
        alertToast('기타비용 행 추가');
    });

});

// 전역 함수들 (스코프 문제 해결)
let itemRowCount = <?= max(1, count($items)) ?>;
let costRowCount = <?= max(1, count($other_costs)) ?>;

// 저장 함수
function saveEstimate() {
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
        
        const notices = [];
        $('input[name="notices[]"]').each(function() {
            if ($(this).val().trim()) {
                notices.push($(this).val());
            }
        });

        // 기타비용 자동산출 체크박스 상태 추가
        const etc_autocheck = $('#etc_autocheck').is(':checked');

        // 시공비 제외 체크박스 상태 추가
        const exclude_construction_cost = $('#exclude_construction_cost').is(':checked');
        // 몰딩 제외 체크박스 상태 추가
        const exclude_molding = $('#exclude_molding').is(':checked');
        
        // 폼 데이터 수집
        const formData = new FormData($('#estimateForm')[0]);
        // total_ex_vat input요소에 span class total_ex_vat 요소에서 원화표시 제거하고 넣기
        const total_ex_vat = $('span.total-ex-vat').text().replace(/[^\d]/g, '');
        const total_inc_vat = $('span.total-inc-vat').text().replace(/[^\d]/g, '');

        formData.append('total_ex_vat', total_ex_vat);
        formData.append('total_inc_vat', total_inc_vat);
        formData.append('items_json', JSON.stringify(items));
        formData.append('other_costs_json', JSON.stringify(otherCosts));
        formData.append('notices_json', JSON.stringify(notices));
        formData.append('etc_autocheck', etc_autocheck ? '1' : '0');
        formData.append('exclude_construction_cost', exclude_construction_cost ? '1' : '0');
        formData.append('exclude_molding', exclude_molding ? '1' : '0');
          
        // AJAX 호출
        $.ajax({
            url: 'ET_process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 30000, // 30초 타임아웃
            beforeSend: function() {
                console.log('=== AJAX 요청 시작 ===');
                console.log('URL:', 'ET_process.php');
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
                        window.location.href = 'ET_write_form.php?mode=view&num=' + num + '&tablename=phomi_estimate';                        
                    }, 1500);
                } else {
                    console.log('저장 실패 - message:', response.message);
                    alert('저장 중 오류가 발생했습니다: ' + response.message);
                    submitBtn.prop('disabled', false).text(originalText);
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
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    } catch (error) {
        console.log('=== JavaScript 오류 ===');
        console.log('Error:', error);
        alert('JavaScript 오류가 발생했습니다: ' + error.message);
        submitBtn.prop('disabled', false).text(originalText);
    }
}

// 상품 소계 업데이트 함수
function updateItemSubtotals() {
    let mode = $("#mode").val();
    // 조회모드에서는 소계 계산하지 않음
    if (mode === 'view') {
        console.log('조회모드에서는 소계 계산을 건너뜁니다.');
        return;
    }

    let totalSupply = 0;
    let totalTax = 0;
    
    // 상품 합계 계산
    $('.item-row').each(function() {
        const supplyText = $(this).find('.supply-amount').text();
        const taxText = $(this).find('.tax-amount').text();
        if (supplyText && supplyText !== '') {
            totalSupply += parseFloat(supplyText.replace('', '').replace(/,/g, '')) || 0;
        }
        if (taxText && taxText !== '') {
            totalTax += parseFloat(taxText.replace('', '').replace(/,/g, '')) || 0;
        }
    });
    
    // 상품 소계 업데이트
    $('#totalSupply').text('' + totalSupply.toLocaleString());
    $('#totalTax').text('' + totalTax.toLocaleString());
    
    console.log('상품 소계 업데이트 - totalSupply:', totalSupply, 'totalTax:', totalTax);
}

// 합계 업데이트 함수
function updateTotals() {
    const mode = $("#mode").val();
    // 조회모드에서는 합계 계산하지 않음
    if (mode === 'view') {
        console.log('조회모드에서는 합계 계산을 건너뜁니다.');
        return;
    }
    
    // 무한루프 방지를 위한 플래그
    // if (window.isUpdatingTotals) {
    //     return;
    // }

    console.log('합계를 나타내는 (상품+기타비용) 합계 업데이트 updateTotals 호출됨');
    window.isUpdatingTotals = true;
    
    let otherCostsSupply = 0;
    let otherCostsTax = 0;
    
    // 상품 소계 업데이트
    updateItemSubtotals();
    
    // 상품 소계 값 가져오기
    const totalSupplyText = $('#totalSupply').text();
    const totalTaxText = $('#totalTax').text();
    const totalSupply = parseFloat(totalSupplyText.replace('', '').replace(/,/g, '')) || 0;
    const totalTax = parseFloat(totalTaxText.replace('', '').replace(/,/g, '')) || 0;

    // 기타비용 합계 계산
    $('.cost-row').each(function() {
        const supplyText = $(this).find('.cost-supply-amount').val();
        const taxText = $(this).find('.cost-tax-amount').val();
        if (supplyText && supplyText !== '') {
            otherCostsSupply += parseFloat(supplyText.replace('', '').replace(/,/g, '')) || 0;
        }
        if (taxText && taxText !== '') {
            otherCostsTax += parseFloat(taxText.replace('', '').replace(/,/g, '')) || 0;
        }
    });
    
    // 기타비용 소계 업데이트    
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
    
    // 전체 합계 (상품 + 기타비용)
    const grandTotalSupply = totalSupply + otherCostsSupply;
    const grandTotalTax = totalTax + otherCostsTax;
    
    $('input[name="total_ex_vat"]').val(grandTotalSupply);
    $('input[name="total_inc_vat"]').val(grandTotalSupply + grandTotalTax);
    
    // 합계 테이블 업데이트
    $('.total-ex-vat').text('(' + grandTotalSupply.toLocaleString() + ')');
    $('.total-inc-vat').text('(' + (grandTotalSupply + grandTotalTax).toLocaleString() + ')');
    
    // 플래그 해제
    // setTimeout(() => {
    //     window.isUpdatingTotals = false;
    // }, 50);
}

// 상품 데이터 기반으로 기타비용 자동 계산
function calculateOtherCostsFromProducts(forceRecalculate = false) {
    console.clear();
    console.log('calculateOtherCostsFromProducts 호출됨');
    
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
                    // 시공비: 시공비, ㎡당 시공비, 단가 25,000 또는 700,000
                    (category === '시공비' && item === '㎡당 시공비' && (unitPrice === '25,000' || unitPrice === '700,000')) ||
                    // 몰딩: 부자재, 몰딩, 빈 값들
                    (category === '부자재' && item === '몰딩' && !quantity && !unitPrice) ||
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
        if (hasManualModifications) {
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
    
    // 자동산출 체크 여부와 상관없이 시공비/몰딩 제외에 따라 행 수 조정
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
                <td><input type="text" name="other_costs[${i}][category]" class="form-control"></td>
                <td><input type="text" name="other_costs[${i}][item]" class="form-control"></td>
                <td><input type="text" name="other_costs[${i}][unit]" class="form-control"></td>
                <td><input type="number" name="other_costs[${i}][quantity]" class="form-control cost-quantity-input text-end" step="1"></td>
                <td><input type="text" name="other_costs[${i}][unit_price]" class="form-control cost-unit-price-input text-end" ></td>
                <td><input type="text" name="other_costs[${i}][amount]" class="form-control cost-supply-amount text-end" readonly></td>
                <td><input type="text" name="other_costs[${i}][tax]" class="form-control cost-tax-amount text-end" readonly></td>
                <td><input type="text" name="other_costs[${i}][remarks]" class="form-control"></td>                
            </tr>
        `;
        costTableBody.append(newRow);
    }
    
    // 기존 데이터 복원 (자동산출이 체크되지 않은 경우에만)
    if (!etc_autocheck && existingData.length > 0) {
        $('.cost-row').each(function(index) {
            if (existingData[index]) {
                const data = existingData[index];
                $(this).find('input[name*="[category]"]').val(data.category);
                $(this).find('input[name*="[item]"]').val(data.item);
                $(this).find('input[name*="[unit]"]').val(data.unit);
                // 프로그래밍 방식으로 값 설정임을 표시
                const quantityInput = $(this).find('.cost-quantity-input');
                quantityInput.data('programmatic-change', true);
                quantityInput.val(data.quantity);
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
        
        // 자동산출 체크된 경우에만 데이터 자동 설정
        if (etc_autocheck) {
            // 기타비용 행 업데이트
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
                    // 프로그래밍 방식으로 값 설정임을 표시
                    const quantityInput = $(this).find('.cost-quantity-input');
                    quantityInput.data('programmatic-change', true);
                    quantityInput.val(Math.round(bondQuantity));
                    $(this).find('.cost-unit-price-input').val('5,000');
                    $(this).find('.cost-supply-amount').val((5000 * Math.round(bondQuantity)).toLocaleString());
                    $(this).find('.cost-tax-amount').val((5000 * Math.round(bondQuantity) * 0.1).toLocaleString());
                    $(this).find('input[name*="[remarks]"]').val('');
                } else if (actualRowIndex === 1 && !exclude_molding) {
                    // 2행: 부자재, 몰딩 (몰딩 제외가 아닐 때만)
                    console.log('2행: 몰딩 설정');
                    $(this).find('input[name*="[category]"]').val('부자재');
                    $(this).find('input[name*="[item]"]').val('몰딩');
                    $(this).find('input[name*="[unit]"]').val('EA');
                    $(this).find('.cost-quantity-input').val('');
                    $(this).find('.cost-unit-price-input').val('');
                    $(this).find('.cost-supply-amount').val('');
                    $(this).find('.cost-tax-amount').val('');
                    $(this).find('input[name*="[remarks]"]').val('');
                } else if (actualRowIndex === 2 || (actualRowIndex === 1 && exclude_molding)) {
                    // 3행: 빈 행 (몰딩 제외시 2행이 빈 행이 됨)
                    console.log('3행: 빈 행 설정');
                    $(this).find('input[name*="[category]"]').val('');
                    $(this).find('input[name*="[item]"]').val('');
                    $(this).find('input[name*="[unit]"]').val('');
                    $(this).find('.cost-quantity-input').val('');
                    $(this).find('.cost-unit-price-input').val('');
                    $(this).find('.cost-supply-amount').val('');
                    $(this).find('.cost-tax-amount').val('');
                    $(this).find('input[name*="[remarks]"]').val('');
                } else if (actualRowIndex === 3 || (actualRowIndex === 2 && exclude_molding)) {
                    if (exclude_construction_cost) {
                        // 시공비 제외시: 4행은 운송비
                        console.log('4행: 운송비 설정 (시공비 제외)');
                        $(this).find('input[name*="[category]"]').val('운송비');
                        $(this).find('input[name*="[item]"]').val('');
                        $(this).find('input[name*="[unit]"]').val('');
                        $(this).find('.cost-quantity-input').val('');
                        $(this).find('.cost-unit-price-input').val('');
                        $(this).find('.cost-supply-amount').val('');
                        $(this).find('.cost-tax-amount').val('');
                        $(this).find('input[name*="[remarks]"]').val('착불');
                    } else {
                        // 시공비 포함시: 4행은 시공비
                        console.log('4행: 시공비 설정');
                        $(this).find('input[name*="[category]"]').val('시공비');
                        $(this).find('input[name*="[item]"]').val('㎡당 시공비');
                        $(this).find('input[name*="[unit]"]').val('㎡');
                        // 프로그래밍 방식으로 값 설정임을 표시
                        const quantityInput = $(this).find('.cost-quantity-input');
                        quantityInput.data('programmatic-change', true);
                        quantityInput.val(totalArea.toFixed(2));
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
                } else if (actualRowIndex === 4 || (actualRowIndex === 3 && exclude_molding && !exclude_construction_cost)) {
                    // 5행: 운송비 (시공비 포함시에만)
                    console.log('5행: 운송비 설정');
                    $(this).find('input[name*="[category]"]').val('운송비');
                    $(this).find('input[name*="[item]"]').val('');
                    $(this).find('input[name*="[unit]"]').val('');
                    $(this).find('.cost-quantity-input').val('');
                    $(this).find('.cost-unit-price-input').val('');
                    $(this).find('.cost-supply-amount').val('');
                    $(this).find('.cost-tax-amount').val('');
                    $(this).find('input[name*="[remarks]"]').val('착불');
                }
                
                // 각 행의 입력값 확인
                const category = $(this).find('input[name*="[category]"]').val();
                const item = $(this).find('input[name*="[item]"]').val();
                console.log(`행 ${index + 1} 설정 완료 - 카테고리: ${category}, 품목: ${item}`);
                
                calculateCostRow($(this));
                rowIndex++;
            });        
    }
      
    // 기타비용 소계 업데이트
    updateOtherCostsSubtotal();

    updateTotals();   

}

// 기타비용 테이블 초기화 함수
function initializeOtherCostsTable() {
    console.log('initializeOtherCostsTable 호출됨');
    
    // 무한루프 방지를 위한 플래그
    if (window.isInitializingOtherCosts) {
        console.log('initializeOtherCostsTable 중복 호출 방지');
        return;
    }
    window.isInitializingOtherCosts = true;
    
    // mainTable에 실제 상품 데이터가 있는지 확인
    let hasProductData = false;
    $('.item-row').each(function() {
        const productCode = $(this).find('select[name*="[product_code]"]').val();
        if (productCode) {
            hasProductData = true;
            return false; // break
        }
    });
    
    // 상품 데이터가 있으면 5행 생성, 없으면 1행만 유지
    if (hasProductData) {
        // 기존 행들 제거 (첫 번째 행 제외)
        $('.cost-row:not(:first)').remove();
        
        // 5행까지 생성
        for (let i = 1; i < 5; i++) {
            addCostRowAfter(i - 1);
        }
        
        // 각 행의 기본값 설정
        calculateOtherCostsFromProducts();
    } else {
        // 상품 데이터가 없으면 첫 번째 행만 빈 상태로 유지
        $('.cost-row:not(:first)').remove();
        $('.cost-row:first').find('input').val('');
        $('.cost-row:first').find('.cost-supply-amount, .cost-tax-amount').val('');
    }
    
    // 플래그 해제
    setTimeout(() => {
        window.isInitializingOtherCosts = false;
        console.log('initializeOtherCostsTable 완료');
    }, 100);
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

// 기타비용 소계 즉시 업데이트 함수
function updateOtherCostsSubtotal() {
    console.log('기타비용 소계 만들기 updateOtherCostsSubtotal 실행');
    let otherCostsSupply = 0;
    let otherCostsTax = 0;
    
    // 기타비용 합계 계산
    $('.cost-row').each(function() {
        // 공급가액 input에서 값 가져오기
        const supplyText = $(this).find('.cost-supply-amount').val() || '0';
        const taxText = $(this).find('.cost-tax-amount').val() || '0';
        
        // 쉼표 제거 후 숫자로 변환하여 합계에 더하기
        otherCostsSupply += parseFloat(supplyText.replace(/,/g, '')) || 0;
        otherCostsTax += parseFloat(taxText.replace(/,/g, '')) || 0;
    });
    
    // 기타비용 소계 업데이트
    $('#totalOtherCostsSupply').text('' + otherCostsSupply.toLocaleString());
    $('#totalOtherCostsTax').text('' + otherCostsTax.toLocaleString());
}

// 상품테이블 특정 행 뒤에 새 행 추가
function addRowAfter(rowIndex) {
    const newRowIndex = itemRowCount;
    const newRow = `
        <tr class="item-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteRow(${newRowIndex})" title="행 삭제">
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
            <td><input type="text" name="items[${newRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격(Size)"></td>
            <td><input type="text" name="items[${newRowIndex}][size]" class="form-control form-control-sm text-center size-input" placeholder="분류" ></td>
            <td><input type="number" name="items[${newRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="1"></td>
            <td><input type="text" name="items[${newRowIndex}][area]" class="form-control form-control-sm text-end area-input" placeholder="m²" value="0.00" ></td>
            <td><input type="text" name="items[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input" placeholder="단가" value="${Number(0).toLocaleString()}"></td>
            <td class="text-end supply-amount">0</td>
            <td class="text-end tax-amount">0</td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고"></td>
        </tr>
    `;
    
    // 지정된 행 뒤에 새 행 삽입
    const targetRow = $(`.item-row[data-row="${rowIndex}"]`);
    targetRow.after(newRow);
    
    // 새로 추가된 행의 상품 옵션 채우기 (Select2 초기화 포함)
    const newSelectElement = targetRow.next().find('.product-select');
    const newRowElement = targetRow.next();
    
    // 새 행에 초기 로딩 플래그 설정 (새로 추가된 행이므로 상품 선택 시 단가표 기본값 사용)
    newRowElement.data('initial-load', false);
    
    populateProductOptions(newSelectElement);
    
    itemRowCount++;
    updateRowNumbers();
    updateTotals();
}

// 상품테이블 행 복사 함수
function copyRow(rowIndex) {
    const sourceRow = $(`.item-row[data-row="${rowIndex}"]`);
    const newRowIndex = itemRowCount;
    
    // 소스 행의 데이터 복사
    const productCode = sourceRow.find('select[name*="[product_code]"]').val();
    const specification = sourceRow.find('input[name*="[specification]"]').val();
    const size = sourceRow.find('input[name*="[size]"]').val();
    const quantity = sourceRow.find('input[name*="[quantity]"]').val();
    const area = sourceRow.find('input[name*="[area]"]').val();
    const unitPrice = sourceRow.find('input[name*="[unit_price]"]').val().replace(/,/g, '');
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    const newRow = `
        <tr class="item-row" data-row="${newRowIndex}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="me-2">${newRowIndex + 1}</span>
                    <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                        <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addRowAfter(${newRowIndex})" title="아래에 행 추가">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyRow(${newRowIndex})" title="행 복사">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteRow(${newRowIndex})" title="행 삭제">
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
            <td><input type="text" name="items[${newRowIndex}][unit_price]" class="form-control form-control-sm text-end unit-price-input text-end" placeholder="단가" value="${unitPrice && unitPrice !== '' ? Number(unitPrice).toLocaleString() : ''}" ></td>
            <td class="text-end supply-amount">0</td>
            <td class="text-end tax-amount">0</td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${remarks}"></td>
        </tr>
    `;
    
    // 소스 행 뒤에 새 행 삽입
    sourceRow.after(newRow);
    
    // 새로 추가된 행의 상품 옵션 채우기 (Select2 초기화 포함)
    const newRowElement = sourceRow.next();
    const newSelectElement = newRowElement.find('.product-select');
    
    // 복사된 행에 초기 로딩 플래그 설정 (복사된 행이므로 상품 선택 시 단가표 기본값 사용)
    newRowElement.data('initial-load', false);
    
    populateProductOptions(newSelectElement, function() {
        // 복사된 데이터 설정 (Select2 초기화 완료 후)
        if (productCode) {
            newRowElement.find('.product-select').val(productCode).trigger('change');
        }
    });
    
    // 금액 계산 - NaN 방지
    let quantityVal = parseFloat(quantity) || 0;
    let unitPriceVal = 0;
    
    // unitPrice가 숫자 형식인지 확인하고 안전하게 변환
    if (unitPrice && unitPrice !== '') {
        // 쉼표 제거 후 숫자로 변환
        const cleanUnitPrice = unitPrice.toString().replace(/,/g, '');
        unitPriceVal = parseFloat(cleanUnitPrice) || 0;
    }
        
    // 제곱미터 재계산 (복사된 행의 경우)
    const copiedSpecification = newRowElement.find('.specification-input').val() || '';
    let copiedActualArea = 0;
    if (copiedSpecification.includes('*')) {
        const sizeParts = copiedSpecification.split('*');
        if (sizeParts.length >= 2) {
            const width = parseFloat(sizeParts[0]) || 0;
            const height = parseFloat(sizeParts[1]) || 0;
            copiedActualArea = (width * height) / 1000000; // mm²를 m²로 변환
        }
    } else if (copiedSpecification.includes('×')) {
        const sizeParts = copiedSpecification.split('×');
        if (sizeParts.length >= 2) {
            const width = parseFloat(sizeParts[0]) || 0;
            const height = parseFloat(sizeParts[1]) || 0;
            copiedActualArea = (width * height) / 1000000; // mm²를 m²로 변환
        }
    } else {
        // 단일 숫자인 경우 (가정: 정사각형)
        const singleSize = parseFloat(copiedSpecification) || 0;
        copiedActualArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
    }
    
    // 복사된 행의 제곱미터 업데이트 (수량 × 실제면적)
    const copiedTotalArea = quantityVal * copiedActualArea;
    newRowElement.find('.area-input').val(copiedTotalArea.toFixed(2));

    // 공급가액변경, 세액변경   
    let currentUnitPrice = parseFloat(newRowElement.find('.unit-price-input').val().replace(/,/g, '')) || 0;
    const supplyAmount = copiedTotalArea * currentUnitPrice;
    const taxAmount = supplyAmount * 0.1;
    
    newRowElement.find('.supply-amount').text('' + supplyAmount.toLocaleString());
    newRowElement.find('.tax-amount').text('' + taxAmount.toLocaleString());
    
    console.log('행 복사 - specification:', copiedSpecification, 'actualArea:', copiedActualArea, 'quantityVal:', quantityVal, 'totalArea:', copiedTotalArea, 'supplyAmount:', supplyAmount, 'taxAmount:', taxAmount);
    
    itemRowCount++;
    updateRowNumbers();
    updateTotals();
    
    // 기타비용 테이블 연동
    // setTimeout(function() {
    //     initializeOtherCostsTable();
    // }, 100);
}

// 상품테이블 행 삭제 함수
function deleteRow(rowIndex) {
    const row = $(`.item-row[data-row="${rowIndex}"]`);
    if ($('.item-row').length > 1) {
        row.remove();
        updateRowNumbers();
        updateTotals();
        
        // 기타비용 테이블 연동
        // setTimeout(function() {
        //     initializeOtherCostsTable();
        // }, 100);
    } else {
        alert('최소 1개의 행은 유지해야 합니다.');
    }
    alertToast('행 삭제');
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
            <td><input type="number" name="other_costs[${newRowIndex}][quantity]" class="form-control form-control-sm cost-quantity-input text-end" placeholder="수량" step="0.01" value="${quantity}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][unit_price]" class="form-control form-control-sm cost-unit-price-input text-end" placeholder="단가" step="0.01" value="${unitPrice}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][supply_amount]" class="form-control form-control-sm cost-supply-amount text-end" readonly value="${supplyAmount}"></td>
            <td><input type="text" name="other_costs[${newRowIndex}][tax_amount]" class="form-control form-control-sm cost-tax-amount text-end" readonly value="${taxAmount}"></td> 
            <td><input type="text" name="other_costs[${newRowIndex}][remarks]" class="form-control form-control-sm text-start" placeholder="비고" value="${remarks}"></td>
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
    
    newRowElement.find('.cost-supply-amount').val('' + supplyAmountVal.toLocaleString());
    newRowElement.find('.cost-tax-amount').val('' + taxAmountVal.toLocaleString());
    
    costRowCount++;
    updateCostRowNumbers();
    updateOtherCostsSubtotal(); // 기타비용 소계 업데이트
    updateTotals();
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

// 기타비용 행 번호 업데이트 함수
function updateCostRowNumbers() {
    $('.cost-row').each(function(index) {
        $(this).attr('data-row', index);
        
        // 버튼의 onclick 속성 업데이트
        const buttons = $(this).find('.btn-group button');
        buttons.eq(0).attr('onclick', `addCostRowAfter(${index})`);
        buttons.eq(1).attr('onclick', `copyCostRow(${index})`);
        buttons.eq(2).attr('onclick', `deleteCostRow(${index})`);
    });
}

// PDF 생성 함수
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
    var result = '포미스톤견적서_' + sanitizedRecipient + '_' + sanitizedSiteName + formattedDate + '.pdf';        
    
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

// 알파벳과 숫자가 혼합된 문자열을 자연스럽게 정렬하는 함수 (전역 함수)
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

// 상품 옵션을 동적으로 가져와서 select 요소에 채우는 함수 (전역 함수)
function populateProductOptions(selectElement, callback) {
    // 현재 선택된 값 저장
    const initialValue = selectElement.data('initial-value'); // data-initial-value에서 초기값 읽기
    
    $.ajax({
        url: 'get_products.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // 기존 옵션 제거 (첫 번째 옵션 제외)
            selectElement.find('option:not(:first)').remove();
            
            // 데이터를 자연 정렬로 정렬
            data.sort(function(a, b) {
                return naturalSort(a.prodcode, b.prodcode);
            });
            
            // 정렬된 데이터로 옵션 추가
            data.forEach(function(product) {
                const displayName = product.prodcode + ' - ' + product.texture_kor + ' ' + product.design_kor + ' (' + product.texture_eng + ' ' + product.design_eng + ')';
                const option = $('<option>', {
                    value: product.prodcode,
                    text: displayName,
                    'data-spec': product.type,
                    'data-size': product.size,
                    'data-thickness': product.thickness,
                    'data-area': product.area,
                    'data-unit-price': product.dist_price_per_m2
                });
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
        error: function(xhr, status, error) {
            console.error('상품 데이터 로드 오류:', error);
            // 에러 발생 시에도 콜백 실행
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
}

// 수정 함수
function editEstimate() {
    var num = '<?= $num ?>';
    if (num) {
        window.location.href = 'ET_write_form.php?mode=modify&num=' + num + '&tablename=phomi_estimate';
    } else {
        alert('수정할 견적서를 찾을 수 없습니다.');
    }
}

// 복사 함수
function copyEstimate() {
    var num = '<?= $num ?>';
    if (num) {
        window.location.href = 'ET_write_form.php?mode=copy&num=' + num + '&tablename=phomi_estimate';
    } else {
        alert('복사할 견적서를 찾을 수 없습니다.');
    }
}

// 삭제 함수
function deleteEstimate() {
    var num = '<?= $num ?>';
    if (!num) {
        Swal.fire({
            icon: 'error',
            title: '오류',
            text: '삭제할 견적서를 찾을 수 없습니다.'
        });
        return;
    }
    
    // 삭제 확인
    Swal.fire({
        title: '삭제 확인',
        text: '정말로 이 견적서를 삭제하시겠습니까?\n삭제된 견적서는 복구할 수 없습니다.',
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
                url: 'delete.php',
                type: 'POST',
                data: {
                    num: num,
                    tablename: 'phomi_estimate'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '삭제 완료',
                            text: '견적서가 성공적으로 삭제되었습니다.'
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
// 견적서에서 수주서로 변환하는 함수
function convertToOutorder() {
    // 현재 수주서 데이터 수집
    var estimateData = {
        quote_date: $('#quote-date-text').text(),
        recipient: $('#recipient-text').text() || '미래기업',
        site_name: $('#site-name-text').text() || '',
        signer: $('.signer-text').text() || '소현철',
        hp: $('.hp-text').text() || '010-3784-5438',
        order_date: $('#quote-date-text').text(),
        author: $('#author').val() || '소현철',
        author_id: $('#author_id').val() || 'mirae',
        etc_autocheck: $('#etc_autocheck').val() || '',
        estimate_num: $('#num').val() || '', // 견적번호
        items: [],
        other_costs: [],
        // 받는 분 연락처
        recipient_phone: $('.recipient_phone').text() || ''
    };
    
    // 아이템 데이터 수집
    $('.item-row-view').each(function() {
        var row = $(this);
        var item = {
            product_code: row.find('.product-code').text(),
            quantity: parseFloat(row.find('.quantity-input').text()) || 0,        
            unit_price: parseFloat(row.find('.unit-price-input').text().replace(/[,]/g, '')) || 0,
            area: parseFloat(row.find('.area-input').text()) || 0,      
            remarks: row.find('.remarks-input').text() || ''
        };
        estimateData.items.push(item);
    });
    
    // 수주서 페이지로 데이터 전송
    var outorderUrl = 'write_form.php?mode=insert&tablename=phomi_order';
    var form = $('<form>', {
        'method': 'POST',
        'action': outorderUrl
    });
    
    // 기타 비용 데이터 수집
    $('.other-cost-row-view').each(function() {
        var row = $(this);
        var category = row.find('.cost-category-input').text();
        var item = row.find('.cost-item-input').text();
        var prodcode = '';
        
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
            quantity: parseFloat(row.find('.cost-quantity-input').text()) || 0,                
            unit_price: parseFloat(row.find('.cost-unit-price-input').text().replace(/[,]/g, '')) || 0,
            remarks: row.find('.cost-remarks-input').text() || ''
        };
        
        // 디버깅: 수집된 기타비용 데이터 로그
        console.log('견적서에서 수집된 기타비용 데이터:', costItem);
        
        // 빈 데이터가 아닌 경우에만 추가
        if (category || item || costItem.quantity > 0 || costItem.unit_price > 0) {
            estimateData.other_costs.push(costItem);
            console.log('견적서 기타비용 데이터 추가됨:', costItem);
        } else {
            console.log('견적서 기타비용 데이터 제외됨 (빈 데이터):', costItem);
        }
    });

    // 시공비 제외 체크, 몰딩 제외 체크 전달
    var exclude_construction_cost = $('#exclude_construction_cost').val();
    var exclude_molding = $('#exclude_molding').val();

    estimateData.exclude_construction_cost = exclude_construction_cost;
    estimateData.exclude_molding = exclude_molding;
            
    // 데이터를 hidden input으로 추가
    form.append($('<input>', {
        'type': 'hidden',
        'name': 'estimate_data',
        'value': JSON.stringify(estimateData)
    }));
                    
    // 폼을 body에 추가하고 제출
    $('body').append(form);

    console.log('estimateData', estimateData);      

    form.submit();
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

// 이 함수는 팝업창에서 실행될 때, 다운로드가 부모창이 아닌 현재(팝업)창에서 이루어지도록 location.href를 사용해야 합니다.
// window.open은 새 창을 열기 때문에, 팝업에서 또 새 창이 열려버립니다.
// 팝업창에서 바로 다운로드가 되게 하려면 아래와 같이 수정하세요.

function openEstimatePDF() {
  var num = document.getElementById('num').value;
  // 팝업창(현재창)에서 바로 다운로드가 되도록 location.href 사용
  location.href = '/pdf/estimate_pdf.php?num=' + encodeURIComponent(num) + '&download=1';
}
</script>
</body>
</html>  