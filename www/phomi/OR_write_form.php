<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  
$title_message = '포미스톤 출고증'; 
$title_message_sub = '출 고 요 청 서 (포미스톤)' ; 
$tablename = 'phomi_outorder'; 
$item ='포미스톤 출고증';   
$emailTitle ='출고증';   
$subTitle = '포미스톤 제품';
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; ?> 

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
$tablename = $_REQUEST["tablename"] ?? 'phomi_outorder';

// 복사 모드일 때는 num을 초기화 (새로운 출고증으로 저장하기 위해)
if($mode == 'copy') {
    $original_num = $num; // 원본 num 보관
    $num = ''; // 새로운 출고증을 위해 num 초기화
}

// 데이터 조회
$outorder_data = null;
if(($mode == 'view' || $mode == 'modify' || $mode == 'copy')) {
    $query_num = ($mode == 'copy') ? $original_num : $num;
    if(!empty($query_num)) {
        try {
            $sql = "SELECT * FROM {$DB}.phomi_outorder WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':num', $query_num, PDO::PARAM_INT);
            $stmt->execute();
            $outorder_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // 기본값 설정
            $out_date = $outorder_data['out_date'] ?? date('Y-m-d');
            $customer = '미래기업';
            $manager = $outorder_data['manager'] ?? ($order_data['signer'] ?? '소현철');
            $address = $outorder_data['address'] ?? ($order_data['site_name'] ?? '');
            $contact = $outorder_data['contact'] ?? '010-3784-5438';
            $dispatch_type = $outorder_data['dispatch_type'] ?? '포함';
            $area_sqm = $outorder_data['area_sqm'] ?? '';
            $construction_done = $outorder_data['construction_done'] ?? '미포함';
            $note = $outorder_data['note'] ?? '';   
            // 받는분 정보
            $recipient_name = $outorder_data['recipient_name'] ?? '';
            $recipient_phone = $outorder_data['recipient_phone'] ?? '';
            // 수주일 가져옴
            $order_date = $outorder_data['order_date'] ?? '';
            $order_num = $outorder_data['order_num'] ?? '';
        } catch (PDOException $e) {
            echo "오류: " . $e->getMessage();
        }
    }
}
else
{
    // 수주서에서 전달된 order_data 처리
    $order_data = null;
    $order_data_error = null;

    // POST와 GET 모두에서 order_data 확인
    if (isset($_POST['order_data']) && !empty($_POST['order_data'])) {
        $order_data = json_decode($_POST['order_data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $order_data_error = '견적서 데이터 파싱 오류: ' . json_last_error_msg();
            $order_data = null;
        }
    } elseif (isset($_GET['order_data']) && !empty($_GET['order_data'])) {
        $order_data = json_decode($_GET['order_data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $order_data_error = '견적서 데이터 파싱 오류: ' . json_last_error_msg();
            $order_data = null;
        }
    }

    // 견적서 데이터 유효성 검증
    if ($order_data && !is_array($order_data)) {
        $order_data_error = '견적서 데이터 형식이 올바르지 않습니다.';
        $order_data = null;
    }


    // 기본값 설정
    $out_date = date('Y-m-d');
    $customer =  '미래기업';
    $manager = '소현철';
    $address = '';
    $contact = '010-3784-5438';
    $dispatch_type = '미포함';
    $area_sqm =  '';
    $construction_done = '미포함';
    $note = $outorder_data['note'] ?? '';             

    // 견적서에서 전송된 데이터 처리 (order_data가 이미 위에서 처리되었으므로 추가 처리)
    if ($order_data) {
        $order_date = $order_data['order_date'] ?? '';
        $order_num = $order_data['order_num'] ?? '';
        $customer = '미래기업';
        $manager = $order_data['signer'] ?? '소현철' ;        
        $contact = $order_data['hp'] ?? '010-3784-5438';    
        $address = $order_data['site_name'] ?? '';      // 현장명이 주소가 됨
        $recipient_name = $order_data['recipient_name'] ?? '';
        $recipient_phone = $order_data['recipient_phone'] ?? '';    
    }
        // 디버깅: order_data 확인 (개발 시에만 사용)
        // if ($order_data) {
        //     echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;">';
        //     echo '<h4>전달된 order_data 디버깅:</h4>';
        //     echo '<pre>' . print_r($order_data, true) . '</pre>';
        //     if (isset($order_data['other_costs'])) {
        //         echo '<h5>other_costs 상세:</h5>';
        //         echo '<pre>' . print_r($order_data['other_costs'], true) . '</pre>';
        //     }
        //     echo '</div>';
        // }
}

// JSON 데이터 파싱
$items = [];

if($outorder_data) {
    if(!empty($outorder_data['items'])) {
        $items = json_decode($outorder_data['items'], true) ?? [];
    }
} 
?>

<form method="post" id="outorderForm">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">    
    <input type="hidden" id="num" name="num" value="<?= $num ?>">

<div class="container-fluid my-3">
    <div class="card shadow-sm mb-4 ">
        <div class="card-body p-4">
            <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>
                    <?php 
                    if($mode == 'insert') echo '포미스톤 출고 요청서 작성';
                    elseif($mode == 'modify') echo '포미스톤 출고 요청서 수정';
                    elseif($mode == 'copy') echo '포미스톤 출고 요청서 복사';
                    ?>
                </h4>

                <span class="text-muted fs-6">
                    (수주일 : <input type="date" name="order_date" id="order_date" class="form-control form-control-sm d-inline-block" style="width: 110px;" value="<?= $order_date ?>">)
                    &nbsp;
                    (수주번호 : <input type="text" name="order_num" id="order_num" class="form-control form-control-sm d-inline-block" style="width: 60px;" value="<?= $order_num ?>">)
                </span>
                <div>
                    <button type="button" id="saveBtn" class="btn btn-primary btn-sm me-2">저장</button>                    
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="generatePDF()">PDF 저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($mode == 'view'): ?>
                <input type="hidden" id="order_date" name="order_date" value="<?= $order_date ?>">
                <input type="hidden" id="order_num" name="order_num" value="<?= $order_num ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>포미스톤 출고 요청서 보기</h4>
                <span class="text-muted fs-6">(수주일 : <?= $order_date ?>) &nbsp; (수주번호 : <?= $order_num ?>)</span>
                <div>
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="editOutorder()">수정</button>
                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="copyOutorder()">복사</button>     
                    <button type="button" class="btn btn-danger btn-sm me-2" onclick="deleteorder()">삭제</button>               
                    <button type="button" class="btn btn-dark btn-sm me-2" onclick="generatePDF()">PDF 저장</button>
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
                <h1 class="h2 fw-bold mb-2">포미스톤 출고요청서</h1>                
            </div>

            <!-- 기본 정보 -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="border rounded p-3">                        
                        <div class="row">
                            <div class="col-md-8">
                                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-12">
                                            <div class="d-flex mb-2 align-items-center">
                                                <strong>출고일 :</strong>      &nbsp;                                  
                                                <input type="date" id="out_date" name="out_date" value="<?= $out_date ?>" class="form-control form-control-sm w130px" >
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-12">
                                            <div class="d-flex mb-2 align-items-center">
                                                <strong>주&nbsp;&nbsp;&nbsp;  소 :</strong>      &nbsp;                                  
                                                <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" class="form-control form-control-sm w420px" placeholder="주소" >
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-12">
                                            <div class="d-flex mb-2 align-items-center">
                                                <strong> 받는 분 :</strong>      &nbsp;                                  
                                                <input type="text" name="recipient_name" value="<?= htmlspecialchars($recipient_name) ?>" class="form-control form-control-sm w80px" placeholder="받는 분" >
                                                &nbsp;&nbsp;
                                                <strong> 받는 분 연락처 :</strong>      &nbsp;                                  
                                                <input type="text" name="recipient_phone" value="<?= htmlspecialchars($recipient_phone) ?>" class="form-control form-control-sm w120px" placeholder="연락처" >
                                            </div>  
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-2"><strong>출고일 :</strong></div>
                                        <div class="col-md-10"><?= $out_date ?></div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-2"><strong>주소 :</strong></div>
                                        <div class="col-md-10"><?= htmlspecialchars($address) ?></div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-2"><strong>받는분 / 전화번호 :</strong></div>
                                        <div class="col-md-10"><?= htmlspecialchars($recipient_name . ' / ' . $recipient_phone) ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-3">&nbsp;</div>
                                        <div class="col-md-9">&nbsp;</div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-12">
                                            <div class="d-flex mb-2 align-items-center">
                                                <strong>발주처 :</strong>      &nbsp;                                  
                                                <input type="text" id="customer" name="customer" value="<?= htmlspecialchars($customer) ?>" class="form-control form-control-sm w250px" placeholder="발주처" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex mb-2 align-items-center">
                                                <strong>담당자 :</strong>      &nbsp;                                  
                                                <input type="text" id="manager" name="manager" value="<?= htmlspecialchars($manager) ?>" class="form-control form-control-sm w90px" placeholder="담당자" >
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6">
                                            <div class="d-flex mb-2 align-items-center">
                                                <strong>연락처 :</strong>      &nbsp;                                  
                                                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($contact) ?>" class="form-control form-control-sm w100px" placeholder="연락처" >    
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- view 모드 -->
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-3">&nbsp;</div>
                                        <div class="col-md-9">&nbsp;</div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-3"><strong>발주처 :</strong></div>
                                        <div class="col-md-9"><?= htmlspecialchars($customer) ?></div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-3"><strong>담당자 :</strong></div>
                                        <div class="col-md-9"><?= htmlspecialchars($manager) ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- 출고 항목 테이블 -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle text-center small" style="border-collapse: collapse;" id="mainTable">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col" style="width: 5%;">No.</th>
                            <th scope="col" style="width: 15%;">Code</th>
                            <th scope="col" style="width: 15%;">품명(Texture)</th>
                            <th scope="col" style="width: 20%;">디자인명</th>
                            <th scope="col" style="width: 10%;">규격 (Size)</th>
                            <th scope="col" style="width: 6%;">단위</th>
                            <th scope="col" style="width: 6%;">수량</th>
                            <th scope="col" style="width: 15%;">비고</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <?php 
                        $item_counter = 1;
                        
                        if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): 
                            // 저장된 데이터가 있으면 모든 행을 생성, 없으면 첫 번째 행만 생성
                            // code가 없어도 다른 데이터가 있는 항목들을 포함하여 행 수 계산
                            $item_count = 1; // 최소 1개
                            if (!empty($items)) {
                                $item_count = count($items);
                            }
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        <select name="items[<?= $i ?>][code]" class="form-select form-select-sm product-select" data-row="<?= $i ?>" style="text-align: left;" data-initial-value="<?= htmlspecialchars($items[$i]['code'] ?? '') ?>">
                                            <option value="">코드를 선택하세요</option>
                                        </select>
                                    </div>
                                </td>
                                <td><input type="text" name="items[<?= $i ?>][texture]" class="form-control form-control-sm texture-input" placeholder="품명(Texture)" value="<?= htmlspecialchars($items[$i]['texture'] ?? '') ?>" autocomplete="off"></td>
                                <td><input type="text" name="items[<?= $i ?>][design]" class="form-control form-control-sm design-input" placeholder="디자인" value="<?= htmlspecialchars($items[$i]['design'] ?? '') ?>" readonly></td>
                                <td><input type="text" name="items[<?= $i ?>][specification]" class="form-control form-control-sm specification-input" placeholder="규격" value="<?= htmlspecialchars($items[$i]['specification'] ?? '') ?>" readonly></td>
                                <td><input type="text" name="items[<?= $i ?>][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="<?= htmlspecialchars($items[$i]['unit'] ?? 'EA') ?>"></td>
                                <td><input type="number" name="items[<?= $i ?>][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="<?= $items[$i]['quantity'] ?? '0' ?>"></td>
                                <td><input type="text" name="items[<?= $i ?>][remarks]" class="form-control form-control-sm" placeholder="비고" value="<?= htmlspecialchars($items[$i]['remarks'] ?? '') ?>"></td>
                            </tr>
                            <?php endfor; ?>
                        <?php else:
                        foreach($items as $item): ?>
                        <tr>
                            <td><?= $item_counter ?></td>
                            <td class="text-start"><?= htmlspecialchars($item['code'] ?? '') ?></td>
                            <td class="text-start"><?= htmlspecialchars($item['texture'] ?? '') ?></td>
                            <td class="text-start"><?= htmlspecialchars($item['design'] ?? '') ?></td>
                            <td class="text-center"><?= htmlspecialchars($item['specification'] ?? '') ?></td>
                            <td class="text-center"><?= htmlspecialchars($item['unit'] ?? 'EA') ?></td>
                            <td class="text-end"><?= number_format($item['quantity'] ?? 0) ?></td>
                            <td><?= htmlspecialchars($item['remarks'] ?? '') ?></td>
                        </tr>
                        <?php 
                        $item_counter++;
                        endforeach; 
                        endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 배차 및 시공 정보 -->
            <div class="row text-center border-top border-bottom py-2 fw-semibold mb-3 align-middle">
                <div class="col-md-3 align-middle">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        배차구분 : 
                        <select name="dispatch_type" class="form-select form-select-sm d-inline-block w-auto" >
                            <option value="선불" <?= ($dispatch_type == '선불') ? 'selected' : '' ?>>선불</option>
                            <option value="착불" <?= ($dispatch_type == '착불') ? 'selected' : '' ?>>착불</option>
                            <option value="직접" <?= ($dispatch_type == '직접') ? 'selected' : '' ?>>직접</option>                            
                        </select>
                    <?php else: ?>
                        배차구분 : <?= $dispatch_type ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 mt-1 align-middle">                      
                       자재 면적(㎡) : &nbsp; <span class="text-primary" id="sqrl_material_span"> </span>
                </div>
                <div class="col-md-3 align-middle">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        시공면적(㎡) : <input type="text" name="area_sqm" value="<?= htmlspecialchars($area_sqm) ?>" class="form-control form-control-sm d-inline-block" style="width: 80px;">
                    <?php else: ?>
                        시공면적(㎡) : <?= $area_sqm ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 align-middle">
                    <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                        시공여부 : 
                        <select name="construction_done" class="form-select form-select-sm d-inline-block w-auto" >
                            <option value="포함" <?= ($construction_done == '포함') ? 'selected' : '' ?>>포함</option>
                            <option value="미포함" <?= ($construction_done == '미포함') ? 'selected' : '' ?>>미포함</option>
                        </select>
                    <?php else: ?>
                        시공여부 : <?= $construction_done ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 비고 -->
            <div class="mt-3">
                <strong>비고:</strong>
                <?php if($mode == 'insert' || $mode == 'modify' || $mode == 'copy'): ?>
                    <textarea name="note" class="form-control mt-2" style="min-height: 80px;" placeholder="비고사항을 입력하세요"><?= htmlspecialchars($note) ?></textarea>
                <?php else: ?>
                    <div class="border p-3 mt-2" style="min-height: 80px;">
                        <?= nl2br(htmlspecialchars($note)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

</div>

</form>
<script>

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
                const displayName = product.prodcode + ' - ' + product.texture_kor + ' ' + product.design_kor + '' + product.texture_eng + ' ' + product.design_eng + ')';                
                const option = $('<option>', {
                    value: product.prodcode,
                    text: displayName,
                    'data-texture-kor': product.texture_kor,
                    'data-design-kor': product.design_kor,
                    'data-texture-eng': product.texture_eng,
                    'data-design-eng': product.design_eng,
                    'data-spec': product.type,
                    'data-size': product.size,
                    'data-thickness': product.thickness,
                    'data-area': product.area
                });
                selectElement.append(option);
            });
            
            // Select2 업데이트
            if (selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2('destroy');
            }
            selectElement.select2({
                placeholder: '코드를 선택하세요',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "검색 결과가 없습니다.";
                    },
                    searching: function() {
                        return "검색 중...";
                    }
                },
                templateResult: function(data) {
                    // 드롭다운에서 보여줄 템플릿 (전체 정보)
                    if (!data.id) return data.text;
                    return $(`<span>${data.text}</span>`);
                },
                templateSelection: function(data) {
                    // 선택 후 보여줄 템플릿 (코드만)
                    if (!data.id) return data.text;
                    return $(`<span>${data.id}</span>`);
                },
                dropdownAutoWidth: true // 자동열 조절함수
            });
            
            // Select2 초기화 완료 후 이전에 선택된 값이 있으면 다시 설정
            if (initialValue) {
                setTimeout(function() {
                    selectElement.val(initialValue).trigger('change');
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                }, 50);
            }
            
            // 콜백 함수가 있으면 실행
            if (callback && typeof callback === 'function') {
                if (!initialValue) { // Only call if initialValue was not handled by setTimeout
                    callback();
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('상품 데이터 로드 오류:', error);
        }
    });
}

$(document).ready(function() {
    // 기존 product-select 요소들에 상품 옵션 채우기 및 초기화
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
                // 모든 처리 완료 후 기타비용 계산
                calculateTotalMaterialArea();
            }
        });
    });
    
    // 추가 안전장치: 모든 Select2 초기화 완료 후 한 번 더 확인
    setTimeout(function() {
        $('.product-select').each(function() {
            const selectElement = $(this);
            const initialValue = selectElement.data('initial-value');
            if (initialValue && !selectElement.val()) {
                selectElement.val(initialValue).trigger('change');
            }
        });
    }, 1000);
    
    // 행 추가 함수 (기존 버튼용)
    $('#addItemRow').click(function() {
        addRowAfter($('.item-row').length - 1);
    });
    
    // 저장 버튼 이벤트 리스너
    $('#saveBtn').click(function() {
        saveOutorder();
    });
        
    // 상품 선택 시 관련 정보 자동 채우기
    $(document).on('change', '.product-select', function() {
        const selectedProductCode = $(this).val();
        const selectedOption = $(this).find('option:selected');
        const itemRow = $(this).closest('.item-row');

        if (selectedProductCode) {
            const textureKor = selectedOption.data('texture-kor');
            const designKor = selectedOption.data('design-kor');
            const textureEng = selectedOption.data('texture-eng');
            const designEng = selectedOption.data('design-eng');
            const spec = selectedOption.data('spec');
            const size = selectedOption.data('size');

            // 품명(Texture) 설정 - texture_kor(texture_eng) 표시
            //if(selectedProductCode !== 'BOND' && selectedProductCode !== 'MOLDING') {
            let texture = (textureKor + '(' + textureEng + ')').trim();            
            itemRow.find('.texture-input').val(texture);
            
            // 품명(한글/영문) 설정 - 한글이름(영문이름) 형태
            const koreanName = (designKor).trim();
            const englishName = (designEng).trim();
            let combinedName = `${koreanName}(${englishName})`;
            if(combinedName === '()') {
                combinedName = '';
            }

            itemRow.find('.design-input').val(combinedName);
            
            // 규격 설정
            itemRow.find('.specification-input').val(size);
            
            console.log('상품 선택 - code:', selectedProductCode, 'textureKor:', textureKor, 'combinedName:', combinedName, 'size:', size);
        } else {
            itemRow.find('.texture-input').val('');
            itemRow.find('.design-input').val('');
            itemRow.find('.specification-input').val('');
        }
    });
});

// 행 데이터 처리 함수
function processRowData(selectElement) {
    const selectedProductCode = selectElement.val();
    const selectedOption = selectElement.find('option:selected');
    const itemRow = selectElement.closest('.item-row');

    if (selectedProductCode && selectedOption.length > 0) {
        const textureKor = selectedOption.data('texture-kor');
        const designKor = selectedOption.data('design-kor');
        const textureEng = selectedOption.data('texture-eng');
        const designEng = selectedOption.data('design-eng');
        const spec = selectedOption.data('spec');
        const size = selectedOption.data('size');

        // 품명(Texture) 설정 - texture_kor(texture_eng) 표시
        const texture = (textureKor + '(' + textureEng + ')').trim();
        itemRow.find('.texture-input').val(texture);
        
        // 품명(한글/영문) 설정 - 한글이름(영문이름) 형태
        const koreanName = (designKor).trim();
        const englishName = (designEng).trim();
        const combinedName = `${koreanName}(${englishName})`;
        itemRow.find('.design-input').val(combinedName);
        
        // 규격 설정
        itemRow.find('.specification-input').val(size);
        
        console.log('상품 선택 - code:', selectedProductCode, 'textureKor:', textureKor, 'combinedName:', combinedName, 'size:', size);
    } else {
        itemRow.find('.texture-input').val('');
        itemRow.find('.design-input').val('');
        itemRow.find('.specification-input').val('');
    }
}

// 전역 함수들
let itemRowCount = <?= max(1, count($items)) ?>;

// 저장 함수
function saveOutorder() {
    console.log('=== 저장 함수 호출 ===');
   
    try {
        // JSON 데이터 생성
        const items = [];
        $('.item-row').each(function() {
            const code = $(this).find('select[name*="[code]"]').val();
            const texture = $(this).find('input[name*="[texture]"]').val(); // 품명(Texture)
            const design = $(this).find('input[name*="[design]"]').val(); // 품명(한글/영문)
            const specification = $(this).find('input[name*="[specification]"]').val();
            const unit = $(this).find('input[name*="[unit]"]').val();
            const quantity = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
            const remarks = $(this).find('input[name*="[remarks]"]').val();

            items.push({
                code: code,
                texture: texture,
                design: design,
                specification: specification,
                unit: unit,
                quantity: quantity,
                remarks: remarks
            });
        });
        
        // 폼 데이터 수집
        const formData = new FormData($('#outorderForm')[0]);
        formData.append('items_json', JSON.stringify(items));
        
        // AJAX 호출
        $.ajax({
            url: 'OR_process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 30000,
            beforeSend: function() {
                console.log('=== AJAX 요청 시작 ===');
            },
            success: function(response) {
                console.log('=== AJAX 성공 응답 ===');
                console.log('Response:', response);
                
                if (response.result === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '성공', 
                        text: response.message,
                        confirmButtonColor: '#3085d6',
                        timer: 1500,
                        showConfirmButton: false
                    });
                
                    setTimeout(function() {                    
                        const mode = '<?= $mode ?>';
                        const num = response.num;
                        window.location.href = 'OR_write_form.php?mode=view&num=' + num + '&tablename=phomi_outorder';                        
                    }, 1500);
                } else {
                    alert('저장 중 오류가 발생했습니다: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('=== AJAX 오류 ===');
                console.log('Status:', status);
                console.log('Error:', error);
                
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
            }
        });
    } catch (error) {
        console.log('=== JavaScript 오류 ===');
        console.log('Error:', error);
        alert('JavaScript 오류가 발생했습니다: ' + error.message);
    }
}

// 특정 행 뒤에 새 행 추가
function addRowAfter(rowIndex, originalData = null) {
    const newRowIndex = itemRowCount;
    
    // 원본 데이터가 있으면 해당 데이터로 초기값 설정, 없으면 기본값 사용
    const defaultQuantity = originalData && originalData.quantity ? originalData.quantity : '1';
    const defaultUnit = originalData && originalData.unit ? originalData.unit : 'EA';
    const defaultTexture = originalData && originalData.texture ? originalData.texture : '';
    const defaultDesign = originalData && originalData.design ? originalData.design : '';
    const defaultSpecification = originalData && originalData.specification ? originalData.specification : '';
    const defaultRemarks = originalData && originalData.remarks ? originalData.remarks : '';
    const defaultCode = originalData && originalData.code ? originalData.code : '';
    
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
            <td>
                <div class="d-flex align-items-center">
                    <select name="items[${newRowIndex}][code]" class="form-select form-select-sm product-select" data-row="${newRowIndex}" style="text-align: left;" data-initial-value="${defaultCode}">
                        <option value="">코드를 선택하세요</option>
                    </select>
                </div>
            </td>
            <td><input type="text" name="items[${newRowIndex}][texture]" class="form-control form-control-sm texture-input" placeholder="품명(Texture)" value="${defaultTexture}"></td>
            <td><input type="text" name="items[${newRowIndex}][design]" class="form-control form-control-sm design-input" placeholder="디자인" value="${defaultDesign}" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][specification]" class="form-control form-control-sm specification-input" placeholder="규격" value="${defaultSpecification}" readonly></td>
            <td><input type="text" name="items[${newRowIndex}][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="${defaultUnit}"></td>
            <td><input type="number" name="items[${newRowIndex}][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${defaultQuantity}"></td>
            <td><input type="text" name="items[${newRowIndex}][remarks]" class="form-control form-control-sm" placeholder="비고" value="${defaultRemarks}"></td>
        </tr>
    `;
    
    // 지정된 행 뒤에 새 행 삽입
    const targetRow = $(`.item-row[data-row="${rowIndex}"]`);
    targetRow.after(newRow);
    
    // 새로 추가된 행의 Select2 초기화 및 상품 옵션 채우기
    const newSelectElement = targetRow.next().find('.product-select');
    populateProductOptions(newSelectElement);
    
    itemRowCount++;
    updateRowNumbers();
    autoResizeTableColumns(); // 자동열 조절함수
    calculateTotalMaterialArea(); // 자재 예상 면적 재계산
}



// 행 복사 함수
function copyRow(rowIndex) {
    const sourceRow = $(`.item-row[data-row="${rowIndex}"]`);
    
    // 소스 행의 데이터 복사
    const code = sourceRow.find('select[name*="[code]"]').val();
    const texture = sourceRow.find('input[name*="[texture]"]').val();
    const design = sourceRow.find('input[name*="[design]"]').val();
    const specification = sourceRow.find('input[name*="[specification]"]').val();
    const unit = sourceRow.find('input[name*="[unit]"]').val();
    const quantity = sourceRow.find('input[name*="[quantity]"]').val();
    const remarks = sourceRow.find('input[name*="[remarks]"]').val();
    
    // 복사할 데이터 객체 생성
    const copyData = {
        code: code,
        texture: texture,
        design: design,
        specification: specification,
        unit: unit,
        quantity: quantity,
        remarks: remarks
    };
    
    console.log('복사할 데이터:', copyData);
    
    // addRowAfter 함수를 사용하여 새 행 추가 (원본 데이터 전달)
    addRowAfter(rowIndex, copyData);
}

// 행 삭제 함수
function deleteRow(rowIndex) {
    const row = $(`.item-row[data-row="${rowIndex}"]`);
    if ($('.item-row').length > 1) {
        row.remove();
        updateRowNumbers();
        autoResizeTableColumns(); // 자동열 조절함수
        calculateTotalMaterialArea(); // 자재 예상 면적 재계산
    } else {
        alert('최소 1개의 행은 유지해야 합니다.');
    }
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

// PDF 생성 함수
function generatePDF() {
    var customer = '<?= htmlspecialchars($customer) ?>';
    var out_date = '<?= $out_date ?>';
    
    // 파일명 생성
    var today = new Date();
    var formattedDate = "(" + String(today.getFullYear()).slice(-2) + "." + ("0" + (today.getMonth() + 1)).slice(-2) + "." + ("0" + today.getDate()).slice(-2) + ")";
    var result = '포미스톤출고증_' + customer + '_' + out_date + formattedDate + '.pdf';
    
    var element = document.getElementById('content-to-print');
    
    var opt = {
        margin: [8, 2, 10, 2],
        filename: result,
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
            scale: 4,
            useCORS: true,
            scrollY: 0,
            scrollX: 0,
            windowWidth: document.body.scrollWidth,
            windowHeight: document.body.scrollHeight        
        }, 
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: {
            mode: ['css', 'legacy'],
            avoid: ['tr', '.avoid-break']
        }
    };
    
    html2pdf().from(element).set(opt).save();
}

// 수정 함수
function editOutorder() {
    var num = $('#num').val();
    if (num) {
        window.location.href = 'OR_write_form.php?mode=modify&num=' + num + '&tablename=phomi_outorder';
    } else {
        alert('수정할 출고증을 찾을 수 없습니다.');
    }
}

// 복사 함수
function copyOutorder() {
    var num = $('#num').val();
    if (num) {
        window.location.href = 'OR_write_form.php?mode=copy&num=' + num + '&tablename=phomi_outorder';
    } else {
        alert('복사할 출고증을 찾을 수 없습니다.');
    }
}


// 이 코드를 기존 스크립트의 맨 아래에 추가하세요.

/**
 * 테이블의 모든 열 너비를 내용에 맞게 업데이트하는 함수
 */
function autoResizeTableColumns() {
    const table = $('#mainTable');
    const headers = table.find('thead th');
    
    // 임시로 사용할 span 요소를 body에 추가 (너비 계산용)
    // 한 번만 생성하고 재사용합니다.
    if ($('#tempSpan').length === 0) {
        $('<span id="tempSpan" style="position:absolute; top:-9999px; left:-9999px; white-space:nowrap; padding: 0 8px;"></span>').appendTo('body');
    }
    const tempSpan = $('#tempSpan');

    headers.each(function(index) {

        // Code 열(두 번째 열, index: 1)은 고정 너비로 처리
        if (index === 1) {
            $(this).css('width', '90px'); // 3자리 코드와 Select2 UI를 고려한 고정 너비입니다. (원하는 크기로 조절 가능)
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


// 자재 예상 면적 계산 함수
function calculateTotalMaterialArea() {
    let totalArea = 0;
    
    // 편집 모드: input 필드에서 데이터 가져오기
    $('.item-row').each(function() {
        const specification = $(this).find('.specification-input').val();
        const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
        
        if (specification && quantity > 0) {
            let itemArea = calculateItemArea(specification);
            totalArea += (itemArea * quantity);
        }
    });
    
    // 조회 모드: 테이블 셀에서 데이터 가져오기 (편집 모드가 아닐 때)
    if ($('.item-row').length === 0) {
        $('#mainTable tbody tr').each(function() {
            const cells = $(this).find('td');
            if (cells.length >= 7) { // 규격은 5번째, 수량은 7번째 셀
                const specification = $(cells[4]).text().trim(); // 규격 (5번째 셀, 0-based index 4)
                const quantityText = $(cells[6]).text().trim(); // 수량 (7번째 셀, 0-based index 6)
                const quantity = parseFloat(quantityText.replace(/,/g, '')) || 0; // 쉼표 제거 후 파싱
                
                if (specification && quantity > 0) {
                    let itemArea = calculateItemArea(specification);
                    totalArea += (itemArea * quantity);
                }
            }
        });
    }
    
    // 결과를 span에 표시
    $('#sqrl_material_span').text(totalArea.toFixed(2) + ' ㎡');
    
    console.log('자재 예상 면적 계산 완료:', totalArea.toFixed(2) + ' ㎡');
}

// 개별 아이템 면적 계산 함수
function calculateItemArea(specification) {
    let itemArea = 0;
    
    // 규격에서 면적 계산 (mm²를 m²로 변환)
    if (specification.includes('*')) {
        const sizeParts = specification.split('*');
        if (sizeParts.length >= 2) {
            const width = parseFloat(sizeParts[0]) || 0;
            const height = parseFloat(sizeParts[1]) || 0;
            itemArea = (width * height) / 1000000; // mm²를 m²로 변환
        }
    } else if (specification.includes('×')) {
        const sizeParts = specification.split('×');
        if (sizeParts.length >= 2) {
            const width = parseFloat(sizeParts[0]) || 0;
            const height = parseFloat(sizeParts[1]) || 0;
            itemArea = (width * height) / 1000000; // mm²를 m²로 변환
        }
    } else {
        // 단일 숫자인 경우 (가정: 정사각형)
        const singleSize = parseFloat(specification) || 0;
        itemArea = (singleSize * singleSize) / 1000000; // mm²를 m²로 변환
    }
    
    return itemArea;
}

// 페이지 로드 시, 그리고 행 추가/복사/삭제 시에도 너비 조절 함수를 호출해야 합니다.
$(document).ready(function() {
    // 기존 ready 함수 내용은 그대로 두고, 아래 내용들을 추가합니다.
    
    // 페이지가 처음 로드될 때 너비 조절
    autoResizeTableColumns();
    
    // 페이지 로드 시 자재 예상 면적 계산
    calculateTotalMaterialArea();

    // 입력 필드에 입력이 발생할 때마다 너비 조절 (실시간)
    $(document).on('input', '.item-row input', function() {
        autoResizeTableColumns();
    });

    // Select2 드롭다운 값이 변경될 때마다 너비 조절
    $(document).on('change', '.product-select', function() {
        autoResizeTableColumns();
    });
    
    // 수량이나 규격이 변경될 때 자재 예상 면적 재계산
    $(document).on('input', '.quantity-input, .specification-input', function() {
        calculateTotalMaterialArea();
    });
    
    // 상품 선택 시 자재 예상 면적 재계산 (규격이 자동으로 채워지므로)
    $(document).on('change', '.product-select', function() {
        setTimeout(function() {
            calculateTotalMaterialArea();
        }, 100);
    });

    // 기존 함수들 수정: 행이 변경될 때마다 너비 조절 함수 호출
    // addRowAfter, copyRow, deleteRow 함수의 마지막에 autoResizeTableColumns(); 호출을 추가합니다.
});

// 견적서에서 출고증으로 변환하는 함수
function convertFromorder() {
    // 견적서 목록을 보여주는 모달 창 열기
    Swal.fire({
        title: '견적서 선택',
        text: '변환할 견적서를 선택해주세요.',
        input: 'select',
        inputOptions: new Promise((resolve) => {
            // 견적서 목록을 가져오는 AJAX 요청
            $.ajax({
                url: 'get_order_list.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        let options = {};
                        data.orders.forEach(function(order) {
                            options[order.num] = `${order.quote_date} - ${order.recipient} (${order.site_name})`;
                        });
                        resolve(options);
                    } else {
                        resolve({});
                    }
                },
                error: function() {
                    resolve({});
                }
            });
        }),
        inputPlaceholder: '견적서를 선택하세요',
        showCancelButton: true,
        confirmButtonText: '변환',
        cancelButtonText: '취소',
        showLoaderOnConfirm: true,
        preConfirm: (orderNum) => {
            if (!orderNum) {
                Swal.showValidationMessage('견적서를 선택해주세요.');
                return false;
            }
            
            // 선택된 견적서 데이터를 가져와서 출고증으로 변환
            return $.ajax({
                url: 'convert_order_to_outorder.php',
                type: 'POST',
                data: {
                    order_num: orderNum
                },
                dataType: 'json'
            }).then(response => {
                if (response.success) {
                    return response;
                } else {
                    throw new Error(response.message || '변환 중 오류가 발생했습니다.');
                }
            }).catch(error => {
                Swal.showValidationMessage(`요청 실패: ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // 변환된 데이터로 폼 필드들을 채움
            const data = result.value.data;
            
            // 기본 정보 채우기
            $('#out_date').val(data.out_date);
            $('input[name="customer"]').val(data.customer);
            $('input[name="manager"]').val(data.manager);
            $('input[name="address"]').val(data.address);
            $('input[name="contact"]').val(data.contact);
            $('select[name="dispatch_type"]').val(data.dispatch_type);
            $('input[name="area_sqm"]').val(data.area_sqm);
            $('select[name="construction_done"]').val(data.construction_done);
            $('textarea[name="note"]').val(data.note);
            
                            // 아이템 행들을 채우기
                if (data.items && data.items.length > 0) {
                    // 기존 행들 제거
                    $('.item-row').remove();
                    
                    // 새로운 행들 추가
                    data.items.forEach(function(item, index) {
                        if (index === 0) {
                            // 첫 번째 행 생성
                            const firstRow = `
                                <tr class="item-row" data-row="0">
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="me-2">1</span>
                                            <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                                <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addRowAfter(0)" title="아래에 행 추가">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyRow(0)" title="행 복사">
                                                    <i class="bi bi-files"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteRow(0)" title="행 삭제">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <select name="items[0][code]" class="form-select form-select-sm product-select" data-row="0" style="text-align: left;" data-initial-value="${item.code || ''}">
                                                <option value="">코드를 선택하세요</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td><input type="text" name="items[0][texture]" class="form-control form-control-sm texture-input" placeholder="품명(Texture)" value="${item.texture || ''}"></td>
                                    <td><input type="text" name="items[0][design]" class="form-control form-control-sm design-input" placeholder="디자인" value="${item.design || ''}" readonly></td>
                                    <td><input type="text" name="items[0][specification]" class="form-control form-control-sm specification-input" placeholder="규격" value="${item.specification || ''}" readonly></td>
                                    <td><input type="text" name="items[0][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="${item.unit || 'EA'}"></td>
                                    <td><input type="number" name="items[0][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${item.quantity || '0'}"></td>
                                    <td><input type="text" name="items[0][remarks]" class="form-control form-control-sm" placeholder="비고" value="${item.remarks || ''}"></td>
                                </tr>
                            `;
                            $('#itemsTableBody').append(firstRow);
                            
                            // 첫 번째 행의 Select2 초기화
                            const firstSelect = $('#itemsTableBody').find('.product-select').first();
                            populateProductOptions(firstSelect);
                        } else {
                            // 두 번째 항목부터는 새 행 추가 (원본 데이터 전달)
                            addRowAfter($('.item-row').length - 1, item);
                        }
                    });
                    
                    // 행 번호 업데이트
                    updateRowNumbers();
                    
                    // 자재 예상 면적 재계산
                    calculateTotalMaterialArea();
                    
                    // 테이블 너비 조절
                    autoResizeTableColumns();
                }
            
            Swal.fire({
                title: '변환 완료!',
                text: '견적서가 출고증으로 성공적으로 변환되었습니다.',
                icon: 'success',
                confirmButtonText: '확인'
            });
        }
    });
}

// 견적서에서 불러온 json 데이터를 동적으로 행을 추가하는 함수
$(document).ready(function() {
    var order_data = <?= json_encode($order_data) ?>;
    var existing_items = <?= json_encode($items) ?>; // PHP에서 전달된 기존 items 데이터
    console.log('견적서에서 불러온 데이터 json ', order_data);
    console.log('기존 items 데이터 ', existing_items);
    
    // 수정모드에서 기존 items 데이터가 있는 경우 처리
    if (existing_items && existing_items.length > 0) {
        console.log('기존 items 데이터 처리 시작');
        
        // 기존 데이터가 있으면 PHP에서 이미 모든 행이 생성되었으므로 추가 처리 불필요
        // Select2 초기화만 확인하면 됨
        console.log('PHP에서 이미 모든 행이 생성됨, 추가 처리 불필요');
    }
    // order_data가 있고 items 배열이 존재하는 경우 동적으로 행 추가
    else if (order_data && order_data.items && order_data.items.length > 0) {
        // 기존 행들 제거
        $('.item-row').remove();
        
        // items 배열의 각 항목에 대해 행 추가
        order_data.items.forEach(function(item, index) {
            if (index === 0) {
                // 첫 번째 행 생성
                const firstRow = `
                    <tr class="item-row" data-row="0">
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="me-2">1</span>
                                <div class="btn-group btn-group-sm" role="group" style="gap: 1px;">
                                    <button type="button" class="btn btn-outline-primary btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="addRowAfter(0)" title="아래에 행 추가">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="copyRow(0)" title="행 복사">
                                        <i class="bi bi-files"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm p-0" style="width: 20px; height: 20px; font-size: 12px;" onclick="deleteRow(0)" title="행 삭제">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <select name="items[0][code]" class="form-select form-select-sm product-select" data-row="0" style="text-align: left;" data-initial-value="${item.prodcode || ''}">
                                    <option value="">코드를 선택하세요</option>
                                </select>
                            </div>
                        </td>
                        <td><input type="text" name="items[0][texture]" class="form-control form-control-sm texture-input" placeholder="품명(Texture)" value=""></td>
                        <td><input type="text" name="items[0][design]" class="form-control form-control-sm design-input" placeholder="디자인" value="" readonly></td>
                        <td><input type="text" name="items[0][specification]" class="form-control form-control-sm specification-input" placeholder="규격" value="" readonly></td>
                        <td><input type="text" name="items[0][unit]" class="form-control form-control-sm text-center" placeholder="단위" value="EA"></td>
                        <td><input type="number" name="items[0][quantity]" class="form-control form-control-sm text-end quantity-input" placeholder="수량" step="1" value="${item.quantity || '0'}"></td>
                        <td><input type="text" name="items[0][remarks]" class="form-control form-control-sm" placeholder="비고" value="${item.note || ''}"></td>
                    </tr>
                `;
                $('#itemsTableBody').append(firstRow);
                
                // 첫 번째 행의 Select2 초기화
                const firstSelect = $('#itemsTableBody').find('.product-select').first();
                populateProductOptions(firstSelect);
            } else {
                // 두 번째 항목부터는 새 행 추가 (원본 데이터 전달)
                const orderItemData = {
                    code: item.prodcode,
                    texture: '',
                    design: '',
                    specification: '',
                    unit: 'EA',
                    quantity: item.quantity,
                    remarks: item.note
                };
                addRowAfter($('.item-row').length - 1, orderItemData);
            }
        });

        // 기타비용에 본드가 있으면 본드와 수량을 추가합니다.
        if (order_data && order_data.other_costs && order_data.other_costs.length > 0) {
            order_data.other_costs.forEach(function(cost, index) {
                if (cost.item && cost.item.indexOf('본드') !== -1) {
                    // 본드 데이터 객체 생성
                    const bondData = {
                        code: cost.prodcode,
                        texture: '본드',
                        design: '',
                        specification: '',
                        unit: 'EA',
                        quantity: cost.quantity,
                        remarks: ''
                    };
                    
                    addRowAfter($('.item-row').length - 1, bondData);
                }
                if (cost.item && cost.item.indexOf('몰딩') !== -1) {
                    // 몰딩 데이터 객체 생성
                    const moldingData = {
                        code: cost.prodcode,
                        texture: '몰딩',
                        design: '',
                        specification: '',
                        unit: 'EA',
                        quantity: cost.quantity,
                        remarks: ''
                    };
                    
                    addRowAfter($('.item-row').length - 1, moldingData);
                }
            });
        }
        
        // 행 번호 업데이트
        updateRowNumbers();
        
        // 자재 예상 면적 재계산
        calculateTotalMaterialArea();
        
        // 테이블 너비 조절
        autoResizeTableColumns();
    }
    // 기존 items 데이터나 order_data가 없는 경우 기본 처리
    else {
        console.log('기존 데이터가 없어 기본 처리');
    }
    
    // 기타 비용의 시공비가 있는 경우 시공여부를 '포함'으로 설정
    if (order_data && order_data.other_costs && order_data.other_costs.length > 0) {
        let hasConstructionCost = false;
        
        // other_costs 배열에서 시공비가 있는지 확인
        order_data.other_costs.forEach(function(cost) {
            if (cost.item && cost.item.indexOf('시공비') !== -1) {
                hasConstructionCost = true;
            }
        });
        
        // 시공비가 있으면 시공여부를 '포함'으로 설정
        if (hasConstructionCost) {
            $('select[name="construction_done"]').val('포함').trigger('change');
        }
    }
});

// 삭제 함수
function deleteorder() {
        var num = $('#num').val();
        if (!num) {
            Swal.fire({
                icon: 'error',
                title: '오류',
                text: '삭제할 파일을 찾을 수 없습니다.'
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
                        tablename: 'phomi_outorder'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '삭제 완료',
                                text: '성공적으로 삭제되었습니다.'
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


</script>

</body>
</html>
