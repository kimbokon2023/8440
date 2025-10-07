<?php
require_once getDocumentRoot() . '/session.php';
require_once(includePath('lib/mydb.php'));

$title_message = '거래처 상세보기';

// 거래처 번호 확인
$num = isset($_GET['num']) ? intval($_GET['num']) : 0;

if (!$num) {
    echo "<script>alert('잘못된 접근입니다.'); window.close();</script>";
    exit;
}

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php"); 
    exit;
}

include getDocumentRoot() . '/load_header.php';   
?>
<title> <?=$title_message?> </title>

<body>
<?php require_once(includePath('myheader.php')); ?>   

<?php
// 거래처 정보 조회
$pdo = db_connect();
$sql = "SELECT * FROM mirae8440.customer WHERE num = ? AND is_deleted = 'N'";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$num]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        echo "<script>alert('거래처 정보를 찾을 수 없습니다.'); window.close();</script>";
        exit;
    }
} catch (PDOException $e) {
    echo "<script>alert('데이터베이스 오류가 발생했습니다.'); window.close();</script>";
    exit;
}
?>

<style>
.customer-detail-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.detail-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.detail-header h2 {
    color: #495057;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.detail-header p {
    color: #6c757d;
    margin: 0;
}

.info-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #007bff;
}

.info-section h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
}

.info-value {
    color: #495057;
    font-size: 1rem;
    padding: 0.5rem;
    background: white;
    border-radius: 5px;
    border: 1px solid #e9ecef;
    min-height: 2.5rem;
    display: flex;
    align-items: center;
}

.info-value.empty {
    color: #adb5bd;
    font-style: italic;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.btn-edit {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    color: white;
}

.btn-close {
    background: #6c757d;
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
}

.btn-close:hover {
    background: #5a6268;
    color: white;
}

/* 모바일 최적화 */
@media (max-width: 768px) {
    .customer-detail-container {
        margin: 1rem;
        padding: 1rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-edit, .btn-delete, .btn-close {
        width: 100%;
    }
}
</style>

<div class="container-fluid">
    <div class="customer-detail-container">
        <div class="detail-header">
            <h2><i class="bi bi-building"></i> 거래처 상세정보</h2>
            <p>거래처 정보를 확인하고 관리할 수 있습니다</p>
        </div>

        <!-- 기본 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-info-circle"></i> 기본 정보</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">구분</span>
                    <div class="info-value">
                        <span class="badge <?php echo $customer['classification'] == '사업자' ? 'bg-primary' : 'bg-secondary'; ?>">
                            <?php echo htmlspecialchars($customer['classification']); ?>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">거래처명</span>
                    <div class="info-value"><?php echo htmlspecialchars($customer['company_name']); ?></div>
                </div>
                <div class="info-item">
                    <span class="info-label">상호(법인명)</span>
                    <div class="info-value <?php echo empty($customer['trade_name']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['trade_name']) ? '미입력' : htmlspecialchars($customer['trade_name']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">등록번호</span>
                    <div class="info-value <?php echo empty($customer['registration_number']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['registration_number']) ? '미입력' : htmlspecialchars($customer['registration_number']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">대표자명</span>
                    <div class="info-value <?php echo empty($customer['representative_name']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['representative_name']) ? '미입력' : htmlspecialchars($customer['representative_name']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">사업자번호</span>
                    <div class="info-value <?php echo empty($customer['business_registration_number']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['business_registration_number']) ? '미입력' : htmlspecialchars($customer['business_registration_number']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 연락처 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-telephone"></i> 연락처 정보</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">전화번호</span>
                    <div class="info-value <?php echo empty($customer['phone_number']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['phone_number']) ? '미입력' : htmlspecialchars($customer['phone_number']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">휴대폰번호</span>
                    <div class="info-value <?php echo empty($customer['mobile_number']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['mobile_number']) ? '미입력' : htmlspecialchars($customer['mobile_number']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">FAX번호</span>
                    <div class="info-value <?php echo empty($customer['fax_number']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['fax_number']) ? '미입력' : htmlspecialchars($customer['fax_number']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 사업 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-briefcase"></i> 사업 정보</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">업태</span>
                    <div class="info-value <?php echo empty($customer['business_type']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['business_type']) ? '미입력' : htmlspecialchars($customer['business_type']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">종목</span>
                    <div class="info-value <?php echo empty($customer['business_category']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['business_category']) ? '미입력' : htmlspecialchars($customer['business_category']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 주소 및 기타 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-geo-alt"></i> 주소 및 기타 정보</h5>
            <div class="info-grid">
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">주소</span>
                    <div class="info-value <?php echo empty($customer['address']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['address']) ? '미입력' : nl2br(htmlspecialchars($customer['address'])); ?>
                    </div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">적요</span>
                    <div class="info-value <?php echo empty($customer['remarks']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['remarks']) ? '미입력' : nl2br(htmlspecialchars($customer['remarks'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 그룹 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-people"></i> 그룹 정보</h5>
            <div class="info-grid">
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">거래처 그룹</span>
                    <div class="info-value">
                        <?php 
                        $groups = [];
                        if ($customer['is_sales_customer'] == 'Y') $groups[] = '<span class="badge bg-success">매출거래처</span>';
                        if ($customer['is_purchase_customer'] == 'Y') $groups[] = '<span class="badge bg-info">매입거래처</span>';
                        if ($customer['is_other_customer'] == 'Y') $groups[] = '<span class="badge bg-secondary">기타거래처</span>';
                        echo empty($groups) ? '미설정' : implode(' ', $groups);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 계좌 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-bank"></i> 계좌 정보</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">은행명</span>
                    <div class="info-value <?php echo empty($customer['bank_name']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['bank_name']) ? '미입력' : htmlspecialchars($customer['bank_name']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">계좌번호</span>
                    <div class="info-value <?php echo empty($customer['account_number']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['account_number']) ? '미입력' : htmlspecialchars($customer['account_number']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">예금주</span>
                    <div class="info-value <?php echo empty($customer['account_holder']) ? 'empty' : ''; ?>">
                        <?php echo empty($customer['account_holder']) ? '미입력' : htmlspecialchars($customer['account_holder']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 담당자 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-person-lines-fill"></i> 담당자 정보</h5>
            <?php if (!empty($contacts)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>이메일</th>
                            <th>직급/부서</th>
                            <th>계산서 담당자</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['contact_name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['contact_phone']); ?></td>
                            <td><?php echo htmlspecialchars($contact['contact_email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['position_department']); ?></td>
                            <td>
                                <?php if ($contact['is_invoice_contact'] == 'Y'): ?>
                                    <span class="badge bg-warning">계산서 담당자</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($contact['contact_remarks']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="info-value empty">등록된 담당자 정보가 없습니다.</div>
            <?php endif; ?>
        </div>

        <!-- 등록 정보 섹션 -->
        <div class="info-section">
            <h5><i class="bi bi-calendar"></i> 등록 정보</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">거래처등록일</span>
                    <div class="info-value">
                        <?php echo $customer['registration_date'] ? date('Y-m-d', strtotime($customer['registration_date'])) : '미입력'; ?>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">최종수정일</span>
                    <div class="info-value">
                        <?php echo date('Y-m-d H:i:s', strtotime($customer['last_modified_date'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 액션 버튼 -->
        <div class="action-buttons">
            <button type="button" class="btn btn-edit" onclick="editCustomer(<?php echo $customer['num']; ?>)">
                <i class="bi bi-pencil-square"></i> 수정
            </button>
            <button type="button" class="btn btn-delete" onclick="deleteCustomer(<?php echo $customer['num']; ?>)">
                <i class="bi bi-trash"></i> 삭제
            </button>
            <button type="button" class="btn btn-close" onclick="closeWindow()">
                <i class="bi bi-x-circle"></i> 닫기
            </button>
        </div>
    </div>
</div>

<script>
// 거래처 수정 함수
function editCustomer(num) {
    var url = "edit.php?num=" + num;
    window.open(url, '_blank', 'width=1000,height=700,scrollbars=yes,resizable=yes');
}

// 거래처 삭제 함수
function deleteCustomer(num) {
    if (confirm('정말로 이 거래처를 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) {
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { num: num },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('거래처가 성공적으로 삭제되었습니다.');
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
    }
}

// 창 닫기 함수
function closeWindow() {
    window.close();
}
</script>

<div class="container-fluid mt-3 mb-3">
    <? include '../footer_sub.php'; ?>
</div>
</body>
</html>
