<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? '';
$iframe_mode = isset($_GET['iframe']) && $_GET['iframe'] === '1';

$title_message = '거래처 등록/수정';

// 거래처 번호 확인
$num = 0;
if (isset($_GET['num'])) {
    $num = intval($_GET['num']);
} elseif (isset($_POST['num'])) {
    $num = intval($_POST['num']);
}

// 서버 변수 초기화
$REQUEST_URI = $_SERVER['REQUEST_URI'] ?? '';

// URL에서 직접 파라미터 추출 시도
if (!$num && $REQUEST_URI) {
    $url_parts = parse_url($REQUEST_URI);
    if (isset($url_parts['query'])) {
        parse_str($url_parts['query'], $query_params);
        if (isset($query_params['num'])) {
            $num = intval($query_params['num']);
        }
    }
}

if (!$num) {
    echo "<script>alert('잘못된 접근입니다. (num 파라미터가 없거나 0입니다)'); window.close();</script>";
    exit;
}

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/login/login_form.php");
    } else {
        header("Location: " . $WebSite . "login/login_form.php");
    }
    exit;
}

include getDocumentRoot() . '/load_header.php';
?>
<title> <?=$title_message?> </title>

<body<?php if ($iframe_mode) echo ' style="background:#f5f7fb; overflow-y: auto; height: 100vh; margin: 0; padding: 0;"'; ?>>
    <?php if (!$iframe_mode): ?>
        <?php require_once(includePath('myheader.php')); ?>   
    <?php endif; ?>
    <?php
    // 거래처 정보 조회
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    $sql = "SELECT * FROM {$DB}.customer WHERE num = ? AND is_deleted = 'N'";

    // 변수 초기화
    $customer = array();
    $contacts = array();

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($num));
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            // 거래처 정보가 없으면 기본값으로 생성
            $customer = array(
                'num' => $num,
                'classification' => '사업자',
                'trade_name' => '',
                'company_name' => '새 거래처',
                'registration_number' => '',
                'representative_name' => '',
                'phone_number' => '',
                'mobile_number' => '',
                'fax_number' => '',
                'business_type' => '',
                'business_category' => '',
                'remarks' => '',
                'address' => '',
                'business_registration_number' => '',
                'registration_date' => date('Y-m-d'),
                'is_sales_customer' => 'N',
                'is_purchase_customer' => 'N',
                'is_other_customer' => 'N',
                'bank_name' => '',
                'account_number' => '',
                'account_holder' => '',
                'my_account_id' => null,
                'attached_files' => null
            );
        }

        // 담당자 정보 조회
        $contactSQL = "SELECT * FROM {$DB}.customer_contact WHERE customer_id = ? AND is_deleted = 'N' ORDER BY num";
        $contactStmt = $pdo->prepare($contactSQL);
        $contactStmt->execute(array($num));
        $contacts = $contactStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log("거래처 정보 조회 오류: " . $ex->getMessage());
        echo "<script>alert('데이터베이스 오류가 발생했습니다.'); window.close();</script>";
        exit;
    }
    ?>

<style>
.customer-form-container {
    max-width: 1200px;
    margin: 0.5rem auto;
    padding: 0.8rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-header {
    text-align: left;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.form-header h2 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0;
    font-size: 1.5rem;
}

.form-row {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    min-width: 120px;
    padding-top: 0.5rem;
    font-size: 0.9rem;
}

.form-input-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-control {
    border-radius: 4px;
    border: 1px solid #ced4da;
    padding: 0.3rem;
    transition: all 0.3s ease;    
    font-size: 0.8rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.form-note {
    font-size: 0.8rem;
    color: #6c757d;
    font-style: italic;
    line-height: 1.3;
}

.form-links {
    margin-top: 0.25rem;
}

.radio-group {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.radio-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.checkbox-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.phone-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center; 
    flex-wrap: wrap;
}
 
.country-select {
    width: 100px;
    font-size: 0.8rem;
}

.phone-input {
    flex: 1;
    min-width: 200px; 
}

.add-button {
    background: #007bff;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-button:hover {
    background: #0056b3;
    transform: scale(1.1);
}

.address-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.address-input {
    flex: 1;
    min-width: 200px;
}

.address-input:first-of-type {
    flex: 2;
    min-width: 400px;
}

.account-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.bank-select {
    width: 120px;
}

.bank-custom-input {
    width: 120px;
}

.account-input {
    flex: 1;
    min-width: 150px;
}

.contact-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.contact-table th,
.contact-table td {
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    text-align: left;
}

.contact-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
    font-size: 0.85rem;
}

.contact-table input,
.contact-table select {
    width: 100%;
    border: 1px solid #ced4da;
    border-radius: 3px;
    padding: 0.3rem;
    font-size: 0.85rem;
}

.contact-table .checkbox-cell {
    text-align: center;
}

.remove-button {
    background: #dc3545;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-button:hover {
    background: #c82333;
    transform: scale(1.1);
}

.file-attach-link {
    color: #007bff;
    text-decoration: none;
    font-size: 0.85rem;
}

.file-attach-link:hover {
    text-decoration: underline;
}

/* 업로드 영역 스타일 */
.upload-area {
    border: 2px dashed #ced4da;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.upload-area:hover {
    border-color: #007bff;
    background: #e7f3ff;
}

.upload-area.dragover {
    border-color: #007bff;
    background: rgba(0, 123, 255, 0.1);
    transform: scale(1.02);
}

.upload-icon {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.upload-text {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.5;
}

.upload-text strong {
    color: #007bff;
}

/* 파일 미리보기 영역 */
.file-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.file-preview-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.file-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.file-preview-item .file-info {
    padding: 0.5rem;
    font-size: 0.8rem;
    color: #495057;
    word-break: break-all;
}

.file-preview-item .file-remove {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.file-preview-item .file-remove:hover {
    background: #c82333;
    transform: scale(1.1);
}

/* 파일 미리보기 호버 효과 */
.file-preview-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

.file-image-wrapper,
.file-icon-wrapper {
    transition: all 0.2s ease;
}

.file-image-wrapper:hover,
.file-icon-wrapper:hover {
    opacity: 0.9;
}

.file-overlay {
    transition: all 0.2s ease;
}

.file-info {
    transition: color 0.2s ease;
}

.file-info:hover {
    color: #007bff;
}

.btn-group {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.btn-save {
    background: #007bff;
    border: none;
    border-radius: 4px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-save:hover {
    background: #0056b3;
    color: white;
}

.btn-cancel {
    background: #6c757d;
    border: none;
    border-radius: 4px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-cancel:hover {
    background: #5a6268;
    color: white;
}

.required {
    color: #dc3545;
}

/* 모바일 최적화 */
@media (max-width: 768px) {
    .customer-form-container {
        margin: 0.5rem;
        padding: 1rem;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-label {
        min-width: auto;
        padding-top: 0;
    }
    
    .phone-input-group,
    .address-input-group,
    .account-input-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .country-select,
    .bank-select {
        width: 100%;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-save, .btn-cancel {
        width: 100%;
    }
    
    .contact-table {
        font-size: 0.8rem;
    }
    
    .contact-table th,
    .contact-table td {
        padding: 0.3rem;
    }
    
    .contact-table input,
    .contact-table select {
        font-size: 0.8rem;
        padding: 0.2rem;
    }
}
</style>

<div class="container-fluid">
    <div class="customer-form-container">
        <div class="form-header">
            <h2>거래처 수정</h2>
        </div>

        <form id="customerForm" method="POST" action="update.php">
            <input type="hidden" name="num" value="<?php echo $customer['num']; ?>">
            
            <!-- 구분 -->
            <div class="form-row">
                <label class="form-label">구분</label>
                <div class="radio-group">
                    <div class="radio-item">
                        <input type="radio" id="classification_business" name="classification" value="사업자" <?php echo $customer['classification'] == '사업자' ? 'checked' : ''; ?>>
                        <label for="classification_business">사업자</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="classification_individual" name="classification" value="개인" <?php echo $customer['classification'] == '개인' ? 'checked' : ''; ?>>
                        <label for="classification_individual">개인</label>
                    </div>
                </div>
            </div>

            <!-- 상호(법인명) -->
            <div class="form-row" style="align-items: center;">
                <label class="form-label" style="margin-bottom:0;">상호(법인명)</label>
                <div class="form-input-group" style="flex-direction: row; align-items: center; gap: 5rem;">
                    <input type="text" class="form-control " id="trade_name" name="trade_name" 
                           value="<?php echo htmlspecialchars($customer['trade_name']); ?>" placeholder="상호 또는 법인명을 입력하세요" style="flex:1; width:150px!important;">
                    <span class="form-note" style="white-space:nowrap; font-size:0.92em; color:#888;">
                        ※ 사업자등록증에 기재된 상호 또는 법인명을 입력합니다. (세금계산서 및 증빙/영수증에 사용함)
                    </span>
                </div>
            </div>

            <!-- 거래처명 -->
            <div class="form-row" style="align-items: center;">
                <label class="form-label" style="margin-bottom:0;">거래처명</label>
                <div class="form-input-group" style="flex-direction: row; align-items: center; gap: 12rem;">
                    <input type="text" class="form-control" id="company_name" name="company_name" 
                           value="<?php echo htmlspecialchars($customer['company_name']); ?>" placeholder="거래처명을 입력하세요" required style="flex:1;">
                    <span class="form-note" style="white-space:nowrap; font-size:0.92em; color:#888;">
                        ※ 거래처 관리를 쉽게 하기 위해 통상적으로 사용하는 호칭을 입력 합니다.
                    </span>
                </div>
            </div>

            <!-- 거래처 등록일 및 최종수정일 -->
            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label class="form-label">거래처 등록일</label>
                    <div class="form-input-group">
                        <?php
                        $regDate = '';
                        if (!empty($customer['registration_date'])) {
                            // 날짜 형식 변환 (YYYY-MM-DD 또는 YYYY-MM-DD HH:MM:SS)
                            $dateStr = $customer['registration_date'];
                            if (strlen($dateStr) >= 10) {
                                $regDate = substr($dateStr, 0, 10); // YYYY-MM-DD 부분만 추출
                            } else {
                                $regDate = $dateStr;
                            }
                        } else {
                            $regDate = date('Y-m-d');
                        }
                        ?>
                        <input type="date" class="form-control w200px" id="registration_date" name="registration_date" 
                               value="<?php echo htmlspecialchars($regDate); ?>" placeholder="등록일을 선택하세요">
                    </div>
                </div>
                <div>
                    <label class="form-label">최종수정일</label>
                    <div class="form-input-group">
                        <?php
                        $lastModDate = '';
                        if (!empty($customer['last_modified_date'])) {
                            // 날짜 형식 변환 (YYYY-MM-DD 또는 YYYY-MM-DD HH:MM:SS)
                            $dateStr = $customer['last_modified_date'];
                            if (strlen($dateStr) >= 10) {
                                $lastModDate = substr($dateStr, 0, 10); // YYYY-MM-DD 부분만 추출
                            } else {
                                $lastModDate = $dateStr;
                            }
                        } else {
                            $lastModDate = date('Y-m-d');
                        }
                        ?>
                        <input type="date" class="form-control w200px" id="last_modified_date" name="last_modified_date" 
                               value="<?php echo htmlspecialchars($lastModDate); ?>" placeholder="최종수정일을 선택하세요">
                    </div>
                </div>
            </div>

            <!-- 등록번호 -->
            <div class="form-row">
                <label class="form-label">등록번호</label>
                <div class="form-input-group">
                    <input type="text" class="form-control w200px" id="registration_number" name="registration_number" 
                           value="<?php echo htmlspecialchars($customer['registration_number']); ?>" placeholder="사업자번호 입력(숫자 10자리)">
                </div>
            </div>

            <!-- 대표자 -->
            <div class="form-row">
                <label class="form-label">대표자</label>
                <input type="text" class="form-control w100px" id="representative_name" name="representative_name" 
                       value="<?php echo htmlspecialchars($customer['representative_name']); ?>" placeholder="대표자명을 입력하세요">
            </div>

            <!-- 회사전화번호 -->
            <div class="form-row">
                <label class="form-label">회사전화번호</label>
                <div class="phone-input-group">
                    <select class="form-control country-select" name="country_code">
                        <option value="+82">🇰🇷 +82</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+81">🇯🇵 +81</option>
                        <option value="+86">🇨🇳 +86</option>
                    </select>
                    <input type="text" class="form-control phone-input" name="phone_number" 
                           value="<?php echo htmlspecialchars($customer['phone_number']); ?>" placeholder="전화번호를 입력하세요">
                    <!-- <button type="button" class="add-button" onclick="addPhoneNumber()">+</button> -->
                </div>
            </div>

            <!-- 휴대폰번호 -->
            <div class="form-row">
                <label class="form-label">휴대폰번호</label>
                <div class="phone-input-group">
                    <select class="form-control country-select" name="mobile_country_code">
                        <option value="+82">🇰🇷 +82</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+81">🇯🇵 +81</option>
                        <option value="+86">🇨🇳 +86</option>
                    </select>
                    <input type="text" class="form-control phone-input" name="mobile_number" 
                           value="<?php echo htmlspecialchars($customer['mobile_number']); ?>" placeholder="휴대폰번호를 입력하세요">
                </div>
            </div>

            <!-- 주소 -->
            <div class="form-row">
                <label class="form-label">주소</label>
                <div class="address-input-group">
                    <input type="text" class="form-control address-input" name="address" 
                           value="<?php echo htmlspecialchars($customer['address']); ?>" placeholder="대표주소">
                    <input type="text" class="form-control address-input" name="address2" placeholder="상세주소">
                    <!-- <button type="button" class="add-button" onclick="addAddress()">+</button> -->
                </div>
            </div>

            <!-- 업태/종목 -->
            <div class="form-row" style="align-items: center;">
                <label class="form-label" style="margin-bottom:0;">업태/종목</label>
                <div class="form-input-group" style="flex-direction: row; align-items: center; gap: 1rem;">
                    <input type="text" class="form-control w100px" name="business_type" 
                           value="<?php echo htmlspecialchars($customer['business_type']); ?>" placeholder="업태" style="min-width:200px; width:200px;">
                    <span style="margin: 0 0.5rem;">/</span>
                    <input type="text" class="form-control w100px" name="business_category" 
                           value="<?php echo htmlspecialchars($customer['business_category']); ?>" placeholder="종목" style="min-width:200px; width:200px;">
                </div>
            </div>

            <!-- 적요 -->
            <div class="form-row">
                <label class="form-label">적요</label>
                <div class="form-input-group">
                    <textarea class="form-control" name="remarks" rows="2" placeholder="거래처에 대한 정보를 자유롭게 입력해주세요. (200자 이내)"><?php echo htmlspecialchars($customer['remarks']); ?></textarea>
                </div>
            </div>

            <!-- 그룹 -->
            <div class="form-row">
                <label class="form-label">그룹</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_sales_customer" name="is_sales_customer" value="Y" <?php echo $customer['is_sales_customer'] == 'Y' ? 'checked' : ''; ?>>
                        <label for="is_sales_customer">매출거래처</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_purchase_customer" name="is_purchase_customer" value="Y" <?php echo $customer['is_purchase_customer'] == 'Y' ? 'checked' : ''; ?>>
                        <label for="is_purchase_customer">매입거래처</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_other_customer" name="is_other_customer" value="Y" <?php echo $customer['is_other_customer'] == 'Y' ? 'checked' : ''; ?>>
                        <label for="is_other_customer">기타거래처</label>
                    </div>
                </div>
            </div>

            <!-- 계좌 정보 -->
            <div class="form-row">
                <label class="form-label">계좌 정보</label>
                <div class="account-input-group" id="accountInputGroup">
                    <?php
                    // 기본 은행 목록
                    $defaultBanks = array('기업은행', '신한은행', '국민은행', '우리은행', '하나은행', '농협은행', '새마을금고', '신협');
                    $currentBankName = $customer['bank_name'] ?? '';
                    $isCustomBank = !empty($currentBankName) && !in_array($currentBankName, $defaultBanks);
                    ?>
                    <select class="form-select bank-select" name="bank_name" id="bankSelect" style="font-size:0.7rem;">
                        <option value="">은행 선택</option>
                        <?php foreach ($defaultBanks as $bank): ?>
                            <option value="<?php echo htmlspecialchars($bank); ?>" <?php echo $currentBankName == $bank ? 'selected' : ''; ?>><?php echo htmlspecialchars($bank); ?></option>
                        <?php endforeach; ?>
                        <?php if ($isCustomBank): ?>
                            <option value="<?php echo htmlspecialchars($currentBankName); ?>" selected><?php echo htmlspecialchars($currentBankName); ?></option>
                        <?php endif; ?>
                        <option value="__CUSTOM__">직접입력</option>
                    </select>
                    <input type="text" class="form-control account-input" name="account_number" 
                           value="<?php echo htmlspecialchars($customer['account_number']); ?>" placeholder="계좌번호를 입력하세요">
                    <input type="text" class="form-control bank-custom-input" id="bankCustomInput" 
                           name="bank_custom_name" placeholder="은행명을 직접 입력하세요" 
                           style="display: none; width: 120px;" 
                           value="<?php echo $isCustomBank ? htmlspecialchars($currentBankName) : ''; ?>">
                </div>
            </div>

            <!-- 문서첨부 -->
            <div class="form-row">
                <label class="form-label">문서첨부</label>
                <div class="form-input-group">
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon">📎</div>
                        <div class="upload-text">
                            <strong>클릭</strong>하거나 <strong>드래그앤드롭</strong>으로<br>
                            파일을 업로드하세요 (여러 개 선택 가능)
                        </div>
                        <input type="file" id="fileInput" name="attached_files[]" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" multiple style="display: none;">
                    </div>
                    <div class="file-preview" id="filePreview"></div>
                    <div class="form-note">※ 사업자등록증, 계약서, 통장사본 등 거래처 관련 문서를 첨부합니다 (파일당 최대 10M)</div>
                </div>
            </div>

            <!-- 업로드 진행 중 모달 -->
            <div class="modal fade" id="uploadProgressModal" tabindex="-1" aria-labelledby="uploadProgressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center py-4">
                            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="mb-2">업로드 중입니다.</h5>
                            <p class="text-muted mb-0">잠시 기다려주세요...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 담당자 정보 -->
            <div class="form-row">
                <label class="form-label">담당자 정보</label>
                <div class="form-input-group">
                    <table class="contact-table">
                    <thead>
                        <tr>
                            <th style="width:80px;">이름</th>
                            <th style="width:120px;">연락처</th>
                            <th style="width:150px;">메일</th>
                            <th style="width:100px;">비고</th>
                            <th class="checkbox-cell" style="width:100px;">계산서 담당자</th>
                            <th style="width:100px;">직급/부서</th>
                            <th style="width:100px;">관리</th>
                        </tr>
                    </thead>
                    <tbody id="contactTableBody">
                        <?php if (empty($contacts)): ?>
                        <tr>
                            <td><input type="text" name="contact_name[]" placeholder="담당자명"></td>
                            <td><input type="text" name="contact_phone[]" placeholder="연락처"></td>
                            <td><input type="email" name="contact_email[]" placeholder="이메일"></td>
                            <td><input type="text" name="contact_remarks[]" placeholder="비고"></td>
                            <td class="checkbox-cell"><input type="checkbox" name="is_invoice_contact[]" value="Y"></td>
                            <td><input type="text" name="position_department[]" placeholder="직급/부서"></td>
                            <td>
                                <button type="button" class="remove-button" onclick="removeContactRow(this)">-</button>
                                <button type="button" class="add-button" onclick="addContactRow()">+</button>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><input type="text" name="contact_name[]" value="<?php echo htmlspecialchars($contact['contact_name']); ?>" placeholder="담당자명"></td>
                            <td><input type="text" name="contact_phone[]" value="<?php echo htmlspecialchars($contact['contact_phone']); ?>" placeholder="연락처"></td>
                            <td><input type="email" name="contact_email[]" value="<?php echo htmlspecialchars($contact['contact_email']); ?>" placeholder="이메일"></td>
                            <td><input type="text" name="contact_remarks[]" value="<?php echo htmlspecialchars($contact['contact_remarks']); ?>" placeholder="비고"></td>
                            <td class="checkbox-cell"><input type="checkbox" name="is_invoice_contact[]" value="Y" <?php echo $contact['is_invoice_contact'] == 'Y' ? 'checked' : ''; ?>></td>
                            <td><input type="text" name="position_department[]" value="<?php echo htmlspecialchars($contact['position_department']); ?>" placeholder="직급/부서"></td>
                            <td>
                                <button type="button" class="remove-button" onclick="removeContactRow(this)">-</button>
                                <button type="button" class="add-button" onclick="addContactRow()">+</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>

            <!-- 버튼 그룹 -->
            <div class="btn-group">
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle"></i> 수정
                </button>
                <button type="button" class="btn btn-cancel" onclick="closeWindow()">
                    <i class="bi bi-x-circle"></i> 취소
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
        var isIframeMode = <?php echo $iframe_mode ? 'true' : 'false'; ?>;

        $(document).ready(function() {
            // 은행 선택 직접입력 처리
            var $bankSelect = $('#bankSelect');
            var $bankCustomInput = $('#bankCustomInput');
            
            // 은행 선택 변경 이벤트
            $bankSelect.on('change', function() {
                var selectedValue = $(this).val();
                
                if (selectedValue === '__CUSTOM__') {
                    // 직접입력 선택 시
                    $bankCustomInput.show();
                    $bankCustomInput.focus();
                    $bankSelect.attr('name', ''); // 기존 select의 name 제거
                    $bankCustomInput.attr('name', 'bank_name'); // 직접입력 필드에 name 부여
                } else {
                    // 일반 은행 선택 시
                    $bankCustomInput.hide();
                    $bankSelect.attr('name', 'bank_name'); // select의 name 복원
                    $bankCustomInput.attr('name', 'bank_custom_name'); // 직접입력 필드의 name 제거
                }
            });
            
            // 직접입력 필드에서 값 입력 시 select에 옵션 추가
            $bankCustomInput.on('blur', function() {
                var customValue = $(this).val().trim();
                if (customValue && $bankSelect.find('option[value="' + customValue + '"]').length === 0) {
                    // 직접입력한 값이 select에 없으면 옵션으로 추가
                    var $customOption = $('<option>', {
                        value: customValue,
                        text: customValue
                    });
                    // "직접입력" 옵션 앞에 추가
                    $bankSelect.find('option[value="__CUSTOM__"]').before($customOption);
                    $bankSelect.val(customValue);
                    $bankSelect.attr('name', 'bank_name');
                    $bankCustomInput.attr('name', 'bank_custom_name');
                    $bankCustomInput.hide();
                }
            });
            
            // 페이지 로드 시 기존 은행명이 select 옵션에 없으면 직접입력 모드로 설정
            var currentBankValue = $bankSelect.val();
            var customInputValue = $bankCustomInput.val();
            
            if (customInputValue && !$bankSelect.find('option[value="' + customInputValue + '"]').length) {
                // 직접입력 필드에 값이 있고 select에 해당 옵션이 없는 경우
                $bankSelect.val('__CUSTOM__').trigger('change');
            } else if (currentBankValue && currentBankValue !== '__CUSTOM__' && !$bankSelect.find('option[value="' + currentBankValue + '"]').length) {
                // select에 선택된 값이 있지만 해당 옵션이 없는 경우 (PHP에서 동적으로 추가된 경우)
                // 이미 옵션이 추가되어 있으므로 그대로 유지
            }
            
            // 폼 제출 이벤트
            $('#customerForm').on('submit', function(e) {
                e.preventDefault();

                // 필수 필드 검증
                var companyName = $('#company_name').val().trim();
                if (!companyName) {
                    alert('거래처명을 입력해주세요.');
                    $('#company_name').focus();
                    return false;
                }

                // 폼 데이터 수집
                var formData = new FormData(this);

                // AJAX로 데이터 전송
                $.ajax({
                    url: 'update.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('거래처가 성공적으로 수정되었습니다.');
                            if (isIframeMode && window.parent) {
                                window.parent.postMessage({ scope: 'customerModule', type: 'customerUpdated' }, '*');
                            } else if (window.opener) {
                                window.opener.location.reload();
                            }
                            closeWindow();
                        } else {
                            alert('오류가 발생했습니다: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('서버 오류가 발생했습니다: ' + error);
                    }
                });
            });

            // 전화번호 자동 포맷팅
            $('input[name="phone_number"]').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                if (value.length >= 10) {
                    if (value.length === 10) {
                        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
                    } else if (value.length === 11) {
                        value = value.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
                    }
                }
                $(this).val(value);
            });

            // 휴대폰번호 자동 포맷팅
            $('input[name="mobile_number"]').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                if (value.length >= 10) {
                    if (value.length === 10) {
                        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
                    } else if (value.length === 11) {
                        value = value.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
                    }
                }
                $(this).val(value);
            });

            // 사업자번호 자동 포맷팅
            $('#registration_number').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                if (value.length === 10) {
                    value = value.replace(/(\d{3})(\d{2})(\d{5})/, '$1-$2-$3');
                }
                $(this).val(value);
            });
        });

        // 전화번호 추가
        function addPhoneNumber() {
            var phoneGroup = $('.phone-input-group').first();
            var newGroup = phoneGroup.clone();
            newGroup.find('input').val('');
            phoneGroup.after(newGroup);
        }

        // 주소 추가
        function addAddress() {
            var addressGroup = $('.address-input-group').first();
            var newGroup = addressGroup.clone();
            newGroup.find('input').val('');
            addressGroup.after(newGroup);
        }

        // 계좌 추가
        function addAccount() {
            var accountGroup = $('.address-input-group').last();
            var newGroup = accountGroup.clone();
            newGroup.find('input').val('');
            newGroup.find('select').val('');
            accountGroup.after(newGroup);
        }

        // 담당자 행 추가
        function addContactRow() {
            var tbody = $('#contactTableBody');
            var newRow = tbody.find('tr').first().clone();
            newRow.find('input').val('');
            newRow.find('input[type="checkbox"]').prop('checked', false);
            tbody.append(newRow);
        }

        // 담당자 행 삭제
        function removeContactRow(button) {
            var tbody = $('#contactTableBody');
            if (tbody.find('tr').length > 1) {
                $(button).closest('tr').remove();
            } else {
                alert('최소 하나의 담당자 정보는 필요합니다.');
            }
        }

        // 창 닫기 함수
        function closeWindow() {
            if (isIframeMode && window.parent) {
                window.parent.postMessage({ scope: 'customerModule', type: 'editClosed' }, '*');
            } else {
                window.close();
            }
        }

        // ==================== 파일 업로드 (드래그앤드롭) ====================
        var uploadedFiles = []; // 업로드된 파일 목록 저장
        
        // 업로드 영역 클릭 시 파일 선택
        $('#uploadArea').on('click', function(e) {
            // fileInput 자체를 클릭한 경우는 무시 (무한 루프 방지)
            if (e.target === document.getElementById('fileInput')) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            $('#fileInput').click();
        });

        // 드래그앤드롭 이벤트
        $('#uploadArea').on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        $('#uploadArea').on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        $('#uploadArea').on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
            
            var files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                handleFileUpload(files);
            }
        });

        // 파일 선택 시
        $('#fileInput').on('change', function(e) {
            e.stopPropagation(); // 이벤트 버블링 방지
            if (this.files.length > 0) {
                handleFileUpload(this.files);
            }
        });
        
        // fileInput 클릭 이벤트가 버블링되지 않도록 처리
        $('#fileInput').on('click', function(e) {
            e.stopPropagation();
        });

        // 업로드 진행 모달 표시
        function showUploadModal() {
            var modalElement = document.getElementById('uploadProgressModal');
            if (!modalElement) {
                return;
            }
            
            // Bootstrap 모달 사용
            if (typeof bootstrap !== 'undefined') {
                var uploadModal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                uploadModal.show();
                // 모달 인스턴스를 전역 변수에 저장하여 나중에 닫을 수 있게 함
                window.uploadProgressModal = uploadModal;
            } else {
                // Bootstrap이 없으면 직접 표시
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'uploadModalBackdrop';
                document.body.appendChild(backdrop);
            }
        }

        // 업로드 진행 모달 숨김
        function hideUploadModal() {
            var modalElement = document.getElementById('uploadProgressModal');
            if (!modalElement) {
                return;
            }
            
            // Bootstrap 모달 사용
            if (window.uploadProgressModal) {
                window.uploadProgressModal.hide();
                window.uploadProgressModal = null;
            } else if (typeof bootstrap !== 'undefined') {
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
            
            // Bootstrap이 없거나 인스턴스를 찾을 수 없는 경우 직접 숨김
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            var backdrop = document.getElementById('uploadModalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        // 파일 업로드 처리
        function handleFileUpload(files) {
            // 파일 검증
            var validFiles = [];
            var maxSize = 10 * 1024 * 1024; // 10MB
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                
                // 파일 크기 검증
                if (file.size > maxSize) {
                    alert(file.name + ' 파일 크기는 10MB를 초과할 수 없습니다.');
                    continue;
                }
                
                validFiles.push(file);
            }
            
            if (validFiles.length === 0) {
                return;
            }
            
            // 업로드 진행 모달 표시
            showUploadModal();
            
            // FormData 생성
            var formData = new FormData();
            
            for (var i = 0; i < validFiles.length; i++) {
                formData.append('attached_files[]', validFiles[i]);
            }
            
            // 추가 데이터 설정
            formData.append('tablename', 'customer');
            formData.append('item', 'attached');
            formData.append('upfilename', 'attached_files');
            formData.append('folderPath', '미래기업/uploads/customer');
            formData.append('DBtable', 'picuploads');
            formData.append('num', <?php echo $num; ?>);
            
            // 로딩 표시
            console.log('업로드 시작: ' + validFiles.length + '개 파일');
            
            // AJAX 요청 (Google Drive API)
            $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: '/filedrive/fileprocess.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('업로드 응답 데이터:', response);
                    
                    var successCount = 0;
                    var errorCount = 0;
                    var errorMessages = [];
                    
                    if (Array.isArray(response)) {
                        response.forEach(function(item) {
                            if (item.status === 'success') {
                                successCount++;
                            } else if (item.status === 'error') {
                                errorCount++;
                                errorMessages.push('파일: ' + (item.file || 'Unknown') + ', 메시지: ' + (item.message || 'Unknown error'));
                            }
                        });
                    }
                    
                    // 업로드 모달 숨김
                    hideUploadModal();
                    
                    if (successCount > 0) {
                        // 업로드 완료 후 실제 파일 정보를 GET 요청으로 다시 조회
                        // 약간의 지연을 두어 DB 반영 시간 확보
                        setTimeout(function() {
                            loadExistingFiles();
                        }, 500);
                        
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: successCount + '개의 파일이 성공적으로 업로드되었습니다.',
                                duration: 2000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#4fbe87'
                            }).showToast();
                        } else {
                            alert(successCount + '개의 파일이 성공적으로 업로드되었습니다.');
                        }
                    }
                    
                    if (errorCount > 0) {
                        var errorMsg = '오류 발생: ' + errorCount + '개의 파일 업로드 실패';
                        if (errorMessages.length > 0) {
                            errorMsg += '\n상세 오류: ' + errorMessages.join('\n');
                        }
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: errorMsg,
                                duration: 5000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#f44336'
                            }).showToast();
                        } else {
                            alert(errorMsg);
                        }
                    }
                    
                    // 파일 입력 초기화
                    $('#fileInput').val('');
                },
                error: function(jqxhr, status, error) {
                    console.error('업로드 실패:', jqxhr, status, error);
                    
                    // 업로드 모달 숨김
                    hideUploadModal();
                    
                    alert('파일 업로드 중 오류가 발생했습니다.');
                }
            });
        }

        // 업로드된 파일 표시
        function displayFiles() {
            var preview = $('#filePreview');
            preview.html('');
            
            if (uploadedFiles.length === 0) {
                preview.hide();
                return;
            }
            
            preview.show();
            
            uploadedFiles.forEach(function(file, index) {
                // 파일 정보 확인 및 기본값 설정
                var fileId = file.fileId || '';
                var realname = file.realname || 'Unknown';
                var link = file.link || '';
                var thumbnail = file.thumbnail || file.link || '';
                
                // link가 없으면 fileId로 생성
                if (!link && fileId) {
                    link = '/filedrive/fileprocess.php?action=download&fileId=' + encodeURIComponent(fileId);
                }
                
                // thumbnail이 없으면 link 사용 (이미지인 경우)
                if (!thumbnail && link) {
                    thumbnail = link;
                }
                
                // 이미지 여부 확인
                var isImage = false;
                if (realname) {
                    isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(realname);
                }
                if (!isImage && thumbnail) {
                    isImage = thumbnail.match(/\.(jpg|jpeg|png|gif|webp)$/i) || thumbnail.startsWith('http');
                }
                
                var itemHtml = '<div class="file-preview-item" data-index="' + index + '" data-file-id="' + fileId + '">';
                
                // 파일 클릭 가능하게 만들기
                var fileClickHandler = 'viewFile(' + index + ')';
                var cursorStyle = 'cursor: pointer;';
                
                if (isImage) {
                    itemHtml += '<div class="file-image-wrapper" style="position:relative; ' + cursorStyle + '" onclick="' + fileClickHandler + '">';
                    itemHtml += '<img src="' + thumbnail + '" alt="' + realname + '" onerror="this.src=\'/assets/default-thumbnail.png\'" style="width:100%; height:150px; object-fit:cover;">';
                    itemHtml += '<div class="file-overlay" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.3); display:none; align-items:center; justify-content:center; color:white; font-size:1.2rem;"><i class="bi bi-eye"></i></div>';
                    itemHtml += '</div>';
                } else {
                    itemHtml += '<div class="file-icon-wrapper" style="' + cursorStyle + '" onclick="' + fileClickHandler + '">';
                    itemHtml += '<div style="width:100%; height:150px; background:#f8f9fa; display:flex; align-items:center; justify-content:center; font-size:3rem; position:relative;">';
                    itemHtml += '📄';
                    itemHtml += '<div class="file-overlay" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.3); display:none; align-items:center; justify-content:center; color:white; font-size:1.2rem;"><i class="bi bi-download"></i></div>';
                    itemHtml += '</div>';
                    itemHtml += '</div>';
                }
                
                itemHtml += '<div class="file-info" style="cursor:pointer;" onclick="' + fileClickHandler + '">' + realname + '</div>';
                itemHtml += '<button type="button" class="file-remove" onclick="event.stopPropagation(); removeFile(' + index + ', \'' + fileId + '\')" title="삭제">×</button>';
                itemHtml += '</div>';
                
                var $item = $(itemHtml);
                
                // 호버 효과 추가
                $item.find('.file-image-wrapper, .file-icon-wrapper, .file-info').hover(
                    function() {
                        $(this).find('.file-overlay').show();
                    },
                    function() {
                        $(this).find('.file-overlay').hide();
                    }
                );
                
                preview.append($item);
            });
        }

        // 파일 보기/다운로드 함수
        function viewFile(index) {
            if (!uploadedFiles[index]) {
                console.error('파일 정보를 찾을 수 없습니다. index:', index);
                return;
            }
            
            var file = uploadedFiles[index];
            console.log('파일 정보:', file);
            
            // link가 없는 경우 fileId로 다운로드 링크 생성
            if (!file.link && file.fileId) {
                file.link = '/filedrive/fileprocess.php?action=download&fileId=' + encodeURIComponent(file.fileId);
            }
            
            // link가 여전히 없으면 오류
            if (!file.link) {
                alert('파일 링크를 가져올 수 없습니다. 잠시 후 다시 시도해주세요.');
                console.error('파일 링크 없음:', file);
                return;
            }
            
            var isImage = false;
            
            // 파일 확장자로 이미지 여부 확인
            if (file.realname) {
                isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(file.realname);
            }
            
            // thumbnail이 있으면 이미지로 간주
            if (!isImage && file.thumbnail) {
                isImage = file.thumbnail.match(/\.(jpg|jpeg|png|gif|webp)$/i) || file.thumbnail.startsWith('http');
            }
            
            if (isImage) {
                // 이미지인 경우: 팝업으로 확대 보기
                if (typeof popupCenter === 'function') {
                    popupCenter(file.link, 'imageViewer', 1000, 700);
                } else {
                    // popupCenter 함수가 없으면 기본 window.open 사용
                    var width = 1000;
                    var height = 700;
                    var left = (window.innerWidth / 2) - (width / 2) + window.screenX;
                    var top = (window.innerHeight / 2) - (height / 2) + window.screenY;
                    window.open(file.link, 'imageViewer_' + Date.now(), 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top + ', scrollbars=yes, resizable=yes');
                }
            } else {
                // 이미지가 아닌 경우: 다운로드 또는 새 창에서 열기
                var isPdf = file.realname && file.realname.toLowerCase().match(/\.pdf$/i);
                var isViewable = isPdf || (file.realname && file.realname.toLowerCase().match(/\.(jpg|jpeg|png|gif|webp)$/i));
                
                if (isViewable) {
                    // PDF나 이미지는 새 창에서 열기
                    if (typeof popupCenter === 'function') {
                        popupCenter(file.link, 'fileViewer', 900, 700);
                    } else {
                        var width = 900;
                        var height = 700;
                        var left = (window.innerWidth / 2) - (width / 2) + window.screenX;
                        var top = (window.innerHeight / 2) - (height / 2) + window.screenY;
                        window.open(file.link, 'fileViewer_' + Date.now(), 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top + ', scrollbars=yes, resizable=yes');
                    }
                } else {
                    // 다운로드 가능한 파일은 다운로드 링크 생성
                    var a = document.createElement('a');
                    a.href = file.link;
                    a.download = file.realname || 'download';
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            }
        }

        // 파일 삭제
        function removeFile(index, fileId) {
            if (!confirm('이 파일을 삭제하시겠습니까?')) {
                return;
            }
            
            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'DELETE',
                data: JSON.stringify({
                    fileId: fileId,
                    tablename: 'customer',
                    item: 'attached',
                    folderPath: '미래기업/uploads/customer',
                    DBtable: 'picuploads'
                }),
                contentType: 'application/json',
                dataType: 'json'
            }).done(function(response) {
                if (response.status === 'success') {
                    uploadedFiles.splice(index, 1);
                    displayFiles();
                    
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: '파일이 삭제되었습니다.',
                            duration: 2000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#4fbe87'
                        }).showToast();
                    } else {
                        alert('파일이 삭제되었습니다.');
                    }
                } else {
                    alert(response.message || '파일 삭제 중 오류가 발생했습니다.');
                }
            }).fail(function(error) {
                console.error('삭제 중 오류:', error);
                alert('파일 삭제 중 오류가 발생했습니다.');
            });
        }

        // 기존 파일 불러오기
        function loadExistingFiles() {
            var num = <?php echo $num; ?>;
            if (!num) {
                console.warn('num 값이 없어 파일을 불러올 수 없습니다.');
                return;
            }
            
            console.log('파일 목록 조회 시작, num:', num);
            
            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'GET',
                data: {
                    num: num,
                    tablename: 'customer',
                    item: 'attached',
                    folderPath: '미래기업/uploads/customer'
                },
                dataType: 'json'
            }).done(function(data) {
                console.log('파일 목록 조회 결과:', data);
                
                if (Array.isArray(data) && data.length > 0) {
                    uploadedFiles = data.map(function(file) {
                        // 응답 데이터 구조 확인 및 정규화
                        var fileInfo = {
                            fileId: file.fileId || file.picname || '',
                            realname: file.realname || 'Unknown',
                            link: file.link || file.webViewLink || '',
                            thumbnail: file.thumbnail || file.thumbnailLink || file.link || ''
                        };
                        
                        // link가 없으면 Google Drive 링크 생성
                        if (!fileInfo.link && fileInfo.fileId) {
                            fileInfo.link = 'https://drive.google.com/file/d/' + fileInfo.fileId + '/view';
                        }
                        
                        // thumbnail이 없으면 link 사용 (이미지인 경우)
                        if (!fileInfo.thumbnail && fileInfo.link) {
                            // 이미지 파일인 경우 Google Drive 썸네일 링크 사용
                            if (fileInfo.realname && /\.(jpg|jpeg|png|gif|webp)$/i.test(fileInfo.realname)) {
                                fileInfo.thumbnail = 'https://drive.google.com/uc?id=' + fileInfo.fileId;
                            } else {
                                fileInfo.thumbnail = fileInfo.link;
                            }
                        }
                        
                        console.log('파일 정보 처리:', fileInfo);
                        return fileInfo;
                    });
                    
                    console.log('최종 uploadedFiles:', uploadedFiles);
                    displayFiles();
                } else {
                    console.log('파일 목록이 비어있습니다.');
                    uploadedFiles = [];
                    displayFiles();
                }
            }).fail(function(jqxhr, status, error) {
                console.error('파일 불러오기 오류:', jqxhr, status, error);
                console.error('응답 내용:', jqxhr.responseText);
            });
        }

        // 페이지 로드 시 기존 파일 불러오기
        loadExistingFiles();
    </script>

</body>

</html>