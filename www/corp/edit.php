<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? '';

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

<body>
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

.account-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.bank-select {
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
                           value="<?php echo htmlspecialchars($customer['business_type']); ?>" placeholder="업태" style="min-width:100px;">
                    <span style="margin: 0 0.5rem;">/</span>
                    <input type="text" class="form-control w100px" name="business_category" 
                           value="<?php echo htmlspecialchars($customer['business_category']); ?>" placeholder="종목" style="min-width:100px;">
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
                <div class="account-input-group">
                    <select class="form-select bank-select" name="bank_name" style="font-size:0.7rem;">
                        <option value="">은행 선택</option>
                        <option value="기업은행" <?php echo $customer['bank_name'] == '기업은행' ? 'selected' : ''; ?>>기업은행</option>
                        <option value="신한은행" <?php echo $customer['bank_name'] == '신한은행' ? 'selected' : ''; ?>>신한은행</option>
                        <option value="국민은행" <?php echo $customer['bank_name'] == '국민은행' ? 'selected' : ''; ?>>국민은행</option>
                        <option value="우리은행" <?php echo $customer['bank_name'] == '우리은행' ? 'selected' : ''; ?>>우리은행</option>
                        <option value="하나은행" <?php echo $customer['bank_name'] == '하나은행' ? 'selected' : ''; ?>>하나은행</option>
                        <option value="농협은행" <?php echo $customer['bank_name'] == '농협은행' ? 'selected' : ''; ?>>농협은행</option>
                        <option value="새마을금고" <?php echo $customer['bank_name'] == '새마을금고' ? 'selected' : ''; ?>>새마을금고</option>
                        <option value="신협" <?php echo $customer['bank_name'] == '신협' ? 'selected' : ''; ?>>신협</option>
                    </select>
                    <input type="text" class="form-control account-input" name="account_number" 
                           value="<?php echo htmlspecialchars($customer['account_number']); ?>" placeholder="계좌번호를 입력하세요">
                    <button type="button" class="btn btn-outline-primary btn-sm">예금주조회</button>
                    <a href="#" class="file-attach-link">통장사본 첨부</a>
                    <button type="button" class="add-button" onclick="addAccount()">+</button>
                </div>
            </div>

            <!-- 내 계좌정보 -->
            <div class="form-row" style="align-items: center;">
                <label class="form-label" style="margin-bottom:0;">내 계좌정보</label>
                <div class="form-input-group" style="flex-direction: row; align-items: center; gap: 1rem;">
                    <select class="form-select w250px" name="my_account_id" style="min-width:150px; font-size:0.7rem;">
                        <option value="">내 계좌 선택</option>
                        <option value="1" <?php echo $customer['my_account_id'] == 1 ? 'selected' : ''; ?>>기업은행 123-456789-01-012 (미래건설)</option>
                        <option value="2" <?php echo $customer['my_account_id'] == 2 ? 'selected' : ''; ?>>신한은행 110-456-789012 (미래건설)</option>
                    </select>
                    <div class="form-note" style="margin-bottom:0; white-space:nowrap;">거래처로부터 입금 또는 출금할 나의 기본 계좌를 설정합니다</div>
                </div>
            </div>

            <!-- 문서첨부 -->
            <div class="form-row">
                <label class="form-label">문서첨부</label>
                <div class="form-input-group">
                    <a href="#" class="file-attach-link">파일첨부 (파일당 최대 5M)</a>
                    <div class="form-note">※ 사업자등록증, 계약서 등 거래처 관련 문서를 첨부합니다</div>
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
        $(document).ready(function() {
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
                            // 부모 창 새로고침
                            if (window.opener) {
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
            window.close();
        }
    </script>

</body>

</html>