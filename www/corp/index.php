<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$WebSite = $_SESSION["WebSite"] ?? '';

// 첫 화면 표시 문구
$title_message = '거래처 조회';

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
<!-- Tabulator CSS and JS -->
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<body>
    <?php require_once(includePath('myheader.php')); ?>   

<style>
/* Light mode styles */
body {
  background-color: #ffffff;
  color: #000000;
  overflow-x: auto; /* 데스크톱에서 가로 스크롤 허용 */
}

/* 모바일 전용 스타일 */
@media (max-width: 768px) {
  body {
    font-size: 16px; /* iOS 줌 방지 */
    overflow-x: hidden; /* 모바일에서만 가로 스크롤 방지 */
  }

  /* 컨테이너 패딩 조정 */
  .container-fluid {
    padding-left: 8px;
    padding-right: 8px;
  }

  /* 카드 마진 줄이기 */
  .card {
    margin-bottom: 0.5rem;
    border-radius: 8px;
  }

  .card-body {
    padding: 0.75rem;
  }

  /* 버튼 그룹 스택 방식 */
  .d-flex.align-items-center {
    flex-wrap: wrap;
    gap: 0.25rem;
  }

  /* 버튼 크기 조정 */
  .btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    min-height: 44px; /* 터치 친화적 크기 */
    min-width: 44px;
    margin: 0.125rem; /* 버튼 간 공간 */
  }

  /* 대형 버튼들 */
  .btn:not(.btn-sm) {
    min-height: 48px;
    padding: 0.75rem 1rem;
    font-weight: 500;
  }

  /* 검색 영역 수직 스택 */
  .d-flex.justify-content-center.align-items-center {
    flex-direction: column;
    gap: 0.5rem;
  }

  .d-flex.justify-content-center.align-items-center > * {
    margin: 0.125rem;
  }

  /* 검색 드롭다운과 입력필드 */
  #find, #search {
    width: 100% !important;
    max-width: 200px;
  }

  /* 검색 영역 모바일 최적화 */
  .form-control, .form-select {
    font-size: 16px; /* iOS 줌 방지 */
    min-height: 44px;
  }

  /* 아이콘 크기 조정 */
  .bi {
    font-size: 1.1em;
  }

  /* 타이틀 항용 */
  h5 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0.5rem 0;
  }
}

/* Tabulator 통합 폰트 크기 설정 */
.tabulator {
    font-size: 1.03em;
}

/* 테이블 뷰 가로 스크롤 설정 */
.table-view {
    width: 100%;
    overflow-x: auto;
}

/* 데스크톱에서만 가로 스크롤 활성화 */
@media (min-width: 769px) {
    .table-view, #tabulator-table {
        overflow-x: auto !important;
    }
}

/* 거래처 조회 전용 스타일 */
.customer-header {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.customer-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.customer-header p {
    font-size: 1.1rem;
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.search-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.filter-buttons .btn {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    border: 1px solid #dee2e6;
    background: white;
    color: #495057;
    transition: all 0.3s ease;
}

.filter-buttons .btn:hover {
    background: #2196f3;
    color: white;
    border-color: #2196f3;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
}

.filter-buttons .btn.active {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
    border-color: #2196f3;
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
}

.search-input-group {
    position: relative;
    max-width: 400px;
}

.search-input-group .form-control {
    border-radius: 25px;
    padding-left: 2.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-input-group .form-control:focus {
    border-color: #2196f3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

.search-input-group .search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 3;
}

.add-customer-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.add-customer-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    color: white;
}

/* 테이블 스타일 개선 */
.tabulator .tabulator-header {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
    font-weight: 600;
}

.tabulator .tabulator-header .tabulator-col {
    background: transparent;
    border-right: 1px solid rgba(255,255,255,0.2);
}

.tabulator .tabulator-header .tabulator-col:hover {
    background: rgba(255,255,255,0.1);
}

.tabulator .tabulator-row {
    transition: all 0.2s ease;
    cursor: pointer;
}

.tabulator .tabulator-row:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.tabulator .tabulator-row:active {
    background-color: #e9ecef !important;
    transform: translateY(0);
}

.tabulator .tabulator-row.tabulator-selectable:hover {
    background: #e3f2fd;
}

.tabulator .tabulator-row-even {
    background-color: #fafafa;
}

.tabulator .tabulator-row-even:hover {
    background-color: #f5f9ff !important;
}

/* 상태 배지 스타일 */
.status-new {
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

/* 페이지네이션 스타일 */
.pagination-info {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-controls select {
    border-radius: 5px;
    border: 1px solid #ced4da;
    padding: 0.25rem 0.5rem;
}

.pagination-controls .btn {
    border-radius: 5px;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* 다크 모드 지원 */
[data-theme="dark"] body {
    background-color: #1a1a1a;
    color: #ffffff;
}

[data-theme="dark"] .search-section {
    background: #2d3748;
    color: white;
}

[data-theme="dark"] .filter-buttons .btn {
    background: #4a5568;
    color: #e2e8f0;
    border-color: #4a5568;
}

[data-theme="dark"] .filter-buttons .btn:hover {
    background: #2196f3;
    color: white;
}

[data-theme="dark"] .filter-buttons .btn.active {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
}

[data-theme="dark"] .tabulator .tabulator-header {
    background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
}

[data-theme="dark"] .tabulator .tabulator-row:hover {
    background: #4a5568;
}

[data-theme="dark"] .pagination-info {
    background: #2d3748;
    color: white;
}
</style>

<div class="container-fluid">
    <!-- 헤더 섹션 (1/2 크기) -->
    <div class="customer-header text-center" style="font-size:1.25rem; padding: 0.5rem 0; background:none; color:#000;">
        <div class="container" style="max-width:600px;">
            <h1 style="font-size:1.5rem; margin-bottom:0.25rem; color:#000;"><i class="bi bi-building"></i> 거래처 조회</h1>
            <p style="font-size:0.9rem; margin-bottom:0; color:#000;">거래처 정보를 조회하고 관리할 수 있습니다</p>
        </div>
    </div>

    <!-- 검색 및 필터 섹션 -->
    <div class="search-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="filter-buttons">
                    <button type="button" class="btn active" data-filter="all">
                        <i class="bi bi-people"></i> 전체 그룹
                    </button>
                    <button type="button" class="btn" data-filter="group1">
                        <i class="bi bi-trash"></i> 삭제
                    </button>
                    <button type="button" class="btn" data-filter="date">
                        <i class="bi bi-calendar"></i> 최종수정일 <i class="bi bi-arrow-down"></i>
                    </button>
                    <button type="button" class="btn" data-filter="memo">
                        <i class="bi bi-sticky"></i> 전체 메모
                    </button>
                </div>
                
                <div class="search-input-group">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="검색어를 입력하세요">
                </div>
            </div>
            
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-outline-primary me-2" onclick="openColumnSettings()" style="border-radius: 25px; padding: 0.75rem 1.5rem;">
                    <i class="bi bi-gear"></i> 컬럼 설정
                </button>
                <button type="button" class="btn add-customer-btn" onclick="addCustomer()">
                    <i class="bi bi-plus-circle"></i> + 거래처 등록
                </button>
            </div>
        </div>
    </div>

    <!-- 테이블 섹션 -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-view">
                <div id="tabulator-table"></div>
            </div>
        </div>
    </div>

    <!-- 페이지네이션 정보 -->
    <div class="pagination-info">
        <div class="pagination-controls">
            <label for="pageSize">페이지당 항목:</label>
            <select id="pageSize" onchange="changePageSize()">
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <div class="pagination-controls">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToFirstPage()">
                <i class="bi bi-chevron-double-left"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToPrevPage()">
                <i class="bi bi-chevron-left"></i>
            </button>
            <span id="pageInfo" class="mx-2">1 / 1</span>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToNextPage()">
                <i class="bi bi-chevron-right"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToLastPage()">
                <i class="bi bi-chevron-double-right"></i>
            </button>
        </div>

        <div class="pagination-controls">
            <span id="totalCount">총 0건</span>
        </div>
    </div>

    <!-- 컬럼 설정 모달 -->
    <div class="modal fade" id="columnSettingsModal" tabindex="-1" aria-labelledby="columnSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white;">
                    <h5 class="modal-title" id="columnSettingsModalLabel">
                        <i class="bi bi-gear"></i> 컬럼 표시 설정
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">표시할 컬럼을 선택하세요. 설정은 자동으로 저장됩니다.</p>
                    <div id="columnCheckboxes" class="row">
                        <!-- JavaScript로 동적 생성 -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="resetColumnSettings()">
                        <i class="bi bi-arrow-clockwise"></i> 초기화
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check-lg"></i> 확인
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php
    // 데이터베이스 연결
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 거래처 테이블이 없으면 생성
    $createTableSQL = "
CREATE TABLE IF NOT EXISTS mirae8440.customer (
    num INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    classification ENUM('사업자', '개인') DEFAULT '사업자' COMMENT '구분',
    trade_name VARCHAR(100) DEFAULT NULL COMMENT '상호(법인명)',
    company_name VARCHAR(100) NOT NULL COMMENT '거래처명',
    registration_number VARCHAR(20) DEFAULT NULL COMMENT '등록번호',
    representative_name VARCHAR(50) DEFAULT NULL COMMENT '대표자명',
    phone_number VARCHAR(20) DEFAULT NULL COMMENT '전화번호',
    mobile_number VARCHAR(20) DEFAULT NULL COMMENT '휴대폰번호',
    fax_number VARCHAR(20) DEFAULT NULL COMMENT 'FAX번호',
    business_type VARCHAR(50) DEFAULT NULL COMMENT '업태',
    business_category VARCHAR(50) DEFAULT NULL COMMENT '종목',
    remarks TEXT DEFAULT NULL COMMENT '적요',
    address TEXT DEFAULT NULL COMMENT '주소',
    business_registration_number VARCHAR(20) DEFAULT NULL COMMENT '사업자번호',
    registration_date DATE DEFAULT NULL COMMENT '거래처등록일',
    last_modified_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '최종수정일',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
    is_deleted CHAR(1) DEFAULT 'N' COMMENT '삭제여부',
    -- 그룹 정보
    is_sales_customer CHAR(1) DEFAULT 'N' COMMENT '매출거래처',
    is_purchase_customer CHAR(1) DEFAULT 'N' COMMENT '매입거래처',
    is_other_customer CHAR(1) DEFAULT 'N' COMMENT '기타거래처',
    -- 계좌 정보
    bank_name VARCHAR(50) DEFAULT NULL COMMENT '은행명',
    account_number VARCHAR(50) DEFAULT NULL COMMENT '계좌번호',
    account_holder VARCHAR(50) DEFAULT NULL COMMENT '예금주',
    -- 내 계좌 정보
    my_account_id INT DEFAULT NULL COMMENT '내 계좌 ID',
    -- 첨부파일
    attached_files TEXT DEFAULT NULL COMMENT '첨부파일 정보 (JSON)',
    INDEX idx_company_name (company_name),
    INDEX idx_registration_number (registration_number),
    INDEX idx_representative_name (representative_name),
    INDEX idx_classification (classification)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='거래처 정보 테이블'
";

// 담당자 정보 테이블 생성
$createContactTableSQL = "
CREATE TABLE IF NOT EXISTS mirae8440.customer_contact (
    num INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL COMMENT '거래처 ID',
    contact_name VARCHAR(50) NOT NULL COMMENT '담당자명',
    contact_phone VARCHAR(20) DEFAULT NULL COMMENT '연락처',
    contact_email VARCHAR(100) DEFAULT NULL COMMENT '이메일',
    contact_remarks TEXT DEFAULT NULL COMMENT '비고',
    is_invoice_contact CHAR(1) DEFAULT 'N' COMMENT '계산서 담당자',
    position_department VARCHAR(100) DEFAULT NULL COMMENT '직급/부서',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
    is_deleted CHAR(1) DEFAULT 'N' COMMENT '삭제여부',
    INDEX idx_customer_id (customer_id),
    FOREIGN KEY (customer_id) REFERENCES mirae8440.customer(num) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='거래처 담당자 정보 테이블'
";

try {
    $pdo->exec($createTableSQL);
    $pdo->exec($createContactTableSQL);
    
    // 기존 테이블에 새로운 컬럼들 추가 (테이블이 이미 존재하는 경우)
    $alterColumns = [
        "ALTER TABLE mirae8440.customer ADD COLUMN classification ENUM('사업자', '개인') DEFAULT '사업자' COMMENT '구분'",
        "ALTER TABLE mirae8440.customer ADD COLUMN trade_name VARCHAR(100) DEFAULT NULL COMMENT '상호(법인명)'",
        "ALTER TABLE mirae8440.customer ADD COLUMN is_sales_customer CHAR(1) DEFAULT 'N' COMMENT '매출거래처'",
        "ALTER TABLE mirae8440.customer ADD COLUMN is_purchase_customer CHAR(1) DEFAULT 'N' COMMENT '매입거래처'",
        "ALTER TABLE mirae8440.customer ADD COLUMN is_other_customer CHAR(1) DEFAULT 'N' COMMENT '기타거래처'",
        "ALTER TABLE mirae8440.customer ADD COLUMN bank_name VARCHAR(50) DEFAULT NULL COMMENT '은행명'",
        "ALTER TABLE mirae8440.customer ADD COLUMN account_number VARCHAR(50) DEFAULT NULL COMMENT '계좌번호'",
        "ALTER TABLE mirae8440.customer ADD COLUMN account_holder VARCHAR(50) DEFAULT NULL COMMENT '예금주'",
        "ALTER TABLE mirae8440.customer ADD COLUMN my_account_id INT DEFAULT NULL COMMENT '내 계좌 ID'",
        "ALTER TABLE mirae8440.customer ADD COLUMN attached_files TEXT DEFAULT NULL COMMENT '첨부파일 정보 (JSON)'"
    ];
    
    for ($i = 0; $i < count($alterColumns); $i++) {
        $sql = $alterColumns[$i];
        try {
            $pdo->exec($sql);
        } catch (PDOException $ex) {
            // 컬럼이 이미 존재하는 경우 무시
            if (strpos($ex->getMessage(), 'Duplicate column name') === false &&
                strpos($ex->getMessage(), 'already exists') === false) {
                error_log("테이블 구조 업데이트 오류: " . $ex->getMessage());
            }
        }
    }

    // 인덱스 추가
    try {
        $pdo->exec("ALTER TABLE mirae8440.customer ADD INDEX idx_classification (classification)");
    } catch (PDOException $ex) {
        // 인덱스가 이미 존재하는 경우 무시
        if (strpos($ex->getMessage(), 'Duplicate key name') === false) {
            error_log("인덱스 추가 오류: " . $ex->getMessage());
        }
    }
    
    // 샘플 데이터 삽입 (테이블이 비어있을 때만)
    $countSQL = "SELECT COUNT(*) as count FROM mirae8440.customer";
    $countResult = $pdo->query($countSQL);
    $countRow = $countResult->fetch(PDO::FETCH_ASSOC);
    $count = $countRow ? $countRow['count'] : 0;

    if ($count == 0) {
        $sampleData = array(
            array('사업자', '(주)한산엘테크', '(주)한산엘테크', '136-81-19428', '이세원', '', '031-981-6108', '', '제조', '금속표면처리', '엘리베이터', '', '경기도 김포시 하성면 원산리 603-3', '', '2018-05-03', 'Y', 'Y', 'N', '기업은행', '123-456789-01-012', '이세원'),
            array('사업자', '(주)일해이엔지', '(주)일해이엔지', '121-81-40915', '권영창', '031-3667-5058', '', '031-366-7509', '제조', '부동산', '엘리베이터', '', '경기도 화성시 마도면 마도로574-116', '', '2018-05-03', 'Y', 'N', 'Y', '신한은행', '110-456-789012', '권영창'),
            array('사업자', '태광기전', '태광기전', '113-81-66495', '최승범', '02-2101-3060', '', '02-2101-3063', '도소매', '전기용품', '전기부품', '', '서울시 구로구 구로동 중앙유통단지', '', '2019-12-01', 'N', 'Y', 'N', '국민은행', '123456-78-901234', '최승범'),
            array('사업자', '대한전기', '대한전기', '123-45-67890', '김대한', '02-1234-5678', '010-1234-5678', '02-1234-5679', '제조', '전기기기', '전기부품', '우수거래처', '서울시 강남구 테헤란로 123', '123-45-67890', '2020-01-15', 'Y', 'Y', 'N', '우리은행', '1002-123-456789', '김대한'),
            array('사업자', '미래건설', '미래건설', '234-56-78901', '박미래', '031-234-5678', '010-2345-6789', '031-234-5679', '건설', '건축', '건설자재', '장기거래', '경기도 성남시 분당구 판교로 456', '234-56-78901', '2020-03-20', 'N', 'N', 'Y', '하나은행', '123-456789-12345', '박미래')
        );

        $insertSQL = "INSERT INTO mirae8440.customer (classification, trade_name, company_name, registration_number, representative_name, phone_number, mobile_number, fax_number, business_type, business_category, remarks, address, business_registration_number, registration_date, is_sales_customer, is_purchase_customer, is_other_customer, bank_name, account_number, account_holder) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertSQL);

        for ($j = 0; $j < count($sampleData); $j++) {
            $data = $sampleData[$j];
            $stmt->execute($data);
        }
    }
} catch (PDOException $ex) {
    error_log("거래처 테이블 생성 오류: " . $ex->getMessage());
}

// 거래처 데이터 조회
$sql = "SELECT * FROM mirae8440.customer WHERE is_deleted = 'N' ORDER BY num DESC";
$table_data = array();

try {
    $stmh = $pdo->query($sql);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $js_data = array(
            'num' => $row['num'],
            'classification' => $row['classification'],
            'trade_name' => $row['trade_name'],
            'company_name' => $row['company_name'],
            'registration_number' => $row['registration_number'],
            'representative_name' => $row['representative_name'],
            'phone_number' => $row['phone_number'],
            'mobile_number' => $row['mobile_number'],
            'fax_number' => $row['fax_number'],
            'business_type' => $row['business_type'],
            'business_category' => $row['business_category'],
            'remarks' => $row['remarks'],
            'address' => $row['address'],
            'business_registration_number' => $row['business_registration_number'],
            'registration_date' => $row['registration_date'],
            'last_modified_date' => $row['last_modified_date'],
            'is_sales_customer' => $row['is_sales_customer'],
            'is_purchase_customer' => $row['is_purchase_customer'],
            'is_other_customer' => $row['is_other_customer'],
            'bank_name' => $row['bank_name'],
            'account_number' => $row['account_number'],
            'account_holder' => $row['account_holder']
        );
        $table_data[] = $js_data;
    }
} catch (PDOException $ex) {
    error_log("거래처 데이터 조회 오류: " . $ex->getMessage());
}
?>

    <script>
        // PHP에서 전달된 테이블 데이터
        var phpTableData = <?php echo json_encode($table_data); ?>;

        var table; // Tabulator 인스턴스 전역 변수

        $(document).ready(function() {
            // PHP에서 전달받은 데이터 사용
            var tableData = phpTableData || [];
    
    // Tabulator 컬럼 정의
    var columns = [
        {
            title: "번호",
            field: "num",
            width: 60,
            hozAlign: "center",
            visible: false
        },
        {
            title: "선택",
            field: "select",
            width: 60,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                return '<input type="checkbox" class="form-check-input">';
            }
        },
        {
            title: "구분",
            field: "classification",
            width: 80,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                var badgeClass = value === '사업자' ? 'bg-primary' : 'bg-secondary';
                return '<span class="badge ' + badgeClass + '">' + value + '</span>';
            }
        },
        {
            title: "거래처명",
            field: "company_name",
            width: 200,
            hozAlign: "left",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                return '<i class="bi bi-building me-2"></i>' + value;
            }
        },
        {
            title: "상호(법인명)",
            field: "trade_name",
            width: 180,
            hozAlign: "left"
        },
        {
            title: "등록번호",
            field: "registration_number",
            width: 120,
            hozAlign: "center"
        },
        {
            title: "대표자명",
            field: "representative_name",
            width: 100,
            hozAlign: "center"
        },
        {
            title: "전화번호",
            field: "phone_number",
            width: 120,
            hozAlign: "center"
        },
        {
            title: "휴대폰번호",
            field: "mobile_number",
            width: 120,
            hozAlign: "center"
        },
        {
            title: "FAX번호",
            field: "fax_number",
            width: 120,
            hozAlign: "center"
        },
        {
            title: "최종수정일",
            field: "last_modified_date",
            width: 120,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value) {
                    var date = new Date(value);
                    var formattedDate = date.toISOString().split('T')[0].substring(5); // MM-DD 형식
                    return formattedDate + ' <span class="status-new">N</span>';
                }
                return '';
            }
        },
        {
            title: "업태",
            field: "business_type",
            width: 80,
            hozAlign: "center"
        },
        {
            title: "종목",
            field: "business_category",
            width: 100,
            hozAlign: "center"
        },
        {
            title: "적요",
            field: "remarks",
            width: 120,
            hozAlign: "left"
        },
        {
            title: "주소",
            field: "address",
            width: 200,
            hozAlign: "left"
        },
        {
            title: "사업자번호",
            field: "business_registration_number",
            width: 120,
            hozAlign: "center"
        },
        {
            title: "거래처등록일",
            field: "registration_date",
            width: 120,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var value = cell.getValue();
                if (value) {
                    return value.substring(5); // MM-DD 형식
                }
                return '';
            }
        },
        {
            title: "그룹",
            field: "groups",
            width: 120,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var rowData = cell.getRow().getData();
                var groups = [];
                if (rowData.is_sales_customer === 'Y') groups.push('<span class="badge bg-success">매출</span>');
                if (rowData.is_purchase_customer === 'Y') groups.push('<span class="badge bg-info">매입</span>');
                if (rowData.is_other_customer === 'Y') groups.push('<span class="badge bg-secondary">기타</span>');
                return groups.join(' ');
            }
        },
        {
            title: "계좌정보",
            field: "account_info",
            width: 150,
            hozAlign: "center",
            formatter: function(cell, formatterParams) {
                var rowData = cell.getRow().getData();
                if (rowData.bank_name && rowData.account_number) {
                    return rowData.bank_name + '<br>' + rowData.account_number;
                }
                return '';
            }
        }
    ];
    
    // Tabulator 초기화
    table = new Tabulator("#tabulator-table", {
        data: tableData,
        columns: columns,
        layout: "fitColumns",
        responsiveLayout: false,
        tooltips: true,
        addRowPos: "top",
        history: true,
        pagination: "local",
        paginationSize: 20,
        paginationSizeSelector: [20, 50, 100, 200],
        movableColumns: true,
        resizableRows: true,
        selectable: true,
        initialSort: [
            {column: "num", dir: "desc"}
        ],
            rowClick: function(e, row) {
                var rowData = row.getData();
                var num = rowData.num;

                if (num && num > 0) {
                    var baseUrl = getBaseUrl();
                    var url = baseUrl + "/corp/edit.php?num=" + encodeURIComponent(num);
                    var newWindow = window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
                    if (!newWindow) {
                        alert('팝업이 차단되었습니다. 팝업 차단을 해제하고 다시 시도해주세요.');
                    }
                } else {
                    alert('거래처 번호를 찾을 수 없습니다.');
                }
            },
            rowDblClick: function(e, row) {
                var rowData = row.getData();
                var num = rowData.num;

                if (num && num > 0) {
                    var baseUrl = getBaseUrl();
                    var url = baseUrl + "/corp/edit.php?num=" + encodeURIComponent(num);
                    var newWindow = window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
                    if (!newWindow) {
                        alert('팝업이 차단되었습니다. 팝업 차단을 해제하고 다시 시도해주세요.');
                    }
                } else {
                    alert('거래처 번호를 찾을 수 없습니다.');
                }
            },
        locale: "ko-kr",
        langs: {
            "ko-kr": {
                "pagination": {
                    "page_size": "페이지당 항목",
                    "page_title": "페이지 표시",
                    "first": "첫 페이지",
                    "first_title": "첫 페이지",
                    "last": "마지막 페이지",
                    "last_title": "마지막 페이지",
                    "prev": "이전 페이지",
                    "prev_title": "이전 페이지",
                    "next": "다음 페이지",
                    "next_title": "다음 페이지",
                    "all": "전체",
                    "counter": {
                        "showing": "표시 중",
                        "of": "/",
                        "rows": "행",
                        "pages": "페이지"
                    }
                }
            }
        }
        });

            // 테이블 초기화 완료 후 페이지네이션 정보 업데이트 및 컬럼 설정 로드
            setTimeout(function() {
                if (table && typeof table.getDataCount === 'function') {
                    updatePaginationInfo();
                }

                // 저장된 컬럼 설정 불러오기
                loadColumnSettings();

                // 행 클릭 가능함을 사용자에게 알리는 툴팁 추가
                $('#tabulator-table .tabulator-row').attr('title', '클릭하여 거래처 정보를 수정합니다');
            }, 200);

            // 테이블 데이터 변경 시 페이지네이션 정보 업데이트
            table.on("dataLoaded", function(data) {
                setTimeout(function() {
                    if (table && typeof table.getDataCount === 'function') {
                        updatePaginationInfo();
                    }
                    $('#tabulator-table .tabulator-row').attr('title', '클릭하여 거래처 정보를 수정합니다');
                }, 100);
            });

            table.on("pageLoaded", function(pageno) {
                setTimeout(function() {
                    if (table && typeof table.getDataCount === 'function') {
                        updatePaginationInfo();
                    }
                }, 100);
            });
        });

        // BaseURL 가져오기 함수
        function getBaseUrl() {
            var host = window.location.hostname;
            var protocol = window.location.protocol;
            var port = window.location.port;

            if (host === 'localhost' || host === '127.0.0.1') {
                return protocol + '//' + host + (port ? ':' + port : '');
            } else {
                return protocol + '//' + host;
            }
        }

        // 검색 기능
        $('#searchInput').on('input', function() {
            var searchValue = $(this).val();
            table.setFilter([
                {field: "company_name", type: "like", value: searchValue},
                {field: "trade_name", type: "like", value: searchValue},
                {field: "representative_name", type: "like", value: searchValue},
                {field: "registration_number", type: "like", value: searchValue},
                {field: "phone_number", type: "like", value: searchValue},
                {field: "business_type", type: "like", value: searchValue},
                {field: "business_category", type: "like", value: searchValue},
                {field: "bank_name", type: "like", value: searchValue},
                {field: "account_number", type: "like", value: searchValue}
            ], "or");
        });

        // 필터 버튼 클릭 이벤트
        $('.filter-buttons .btn').on('click', function() {
            $('.filter-buttons .btn').removeClass('active');
            $(this).addClass('active');

            var filter = $(this).data('filter');

            switch (filter) {
                case 'all':
                    table.clearFilter();
                    break;
                case 'group1':
                    // 매출거래처만 필터
                    table.setFilter("is_sales_customer", "=", "Y");
                    break;
                case 'date':
                    // 최종수정일 기준 정렬
                    table.setSort([
                        {column: "last_modified_date", dir: "desc"}
                    ]);
                    break;
                case 'memo':
                    // 메모가 있는 항목만 필터
                    table.setFilter("remarks", "!=", "");
                    break;
            }
        });

        // 페이지네이션 정보 업데이트 함수
        function updatePaginationInfo() {
            if (table) {
                try {
                    // Tabulator 버전에 따라 다른 함수명 사용
                    var pageInfo = null;
                    var totalRows = 0;

                    // 다양한 방법으로 페이지 정보 가져오기 시도
                    if (typeof table.getPageInfo === 'function') {
                        pageInfo = table.getPageInfo();
                    } else if (typeof table.getPage === 'function') {
                        var currentPage = table.getPage();
                        var pageSize = table.getPageSize();
                        var totalData = table.getDataCount();
                        var totalPages = Math.ceil(totalData / pageSize);
                        pageInfo = {
                            page: currentPage,
                            pages: totalPages
                        };
                    }

                    // 총 행 수 가져오기
                    if (typeof table.getDataCount === 'function') {
                        totalRows = table.getDataCount();
                    } else if (typeof table.getData === 'function') {
                        totalRows = table.getData().length;
                    }

                    // 페이지 정보 표시
                    if (pageInfo && pageInfo.page && pageInfo.pages) {
                        $('#pageInfo').text(pageInfo.page + ' / ' + pageInfo.pages);
                    }

                    // 총 개수 표시
                    if (totalRows !== undefined && totalRows >= 0) {
                        $('#totalCount').text('총 ' + totalRows + '건');
                    }
                } catch (error) {
                    // 기본값 설정
                    $('#pageInfo').text('1 / 1');
                    $('#totalCount').text('총 0건');
                }
            }
        }

        // 페이지 크기 변경
        function changePageSize() {
            var pageSize = $('#pageSize').val();
            table.setPageSize(parseInt(pageSize));
        }

        // 페이지네이션 컨트롤 함수들
        function goToFirstPage() {
            table.setPage(1);
        }

        function goToPrevPage() {
            var currentPage = table.getPage();
            if (currentPage > 1) {
                table.setPage(currentPage - 1);
            }
        }

        function goToNextPage() {
            if (table) {
                try {
                    var currentPage = table.getPage();
                    var pageSize = table.getPageSize();
                    var totalData = table.getDataCount();
                    var totalPages = Math.ceil(totalData / pageSize);

                    if (currentPage < totalPages) {
                        table.setPage(currentPage + 1);
                    }
                } catch (error) {
                    // 오류 무시
                }
            }
        }

        function goToLastPage() {
            if (table) {
                try {
                    var pageSize = table.getPageSize();
                    var totalData = table.getDataCount();
                    var totalPages = Math.ceil(totalData / pageSize);

                    if (totalPages > 0) {
                        table.setPage(totalPages);
                    }
                } catch (error) {
                    // 오류 무시
                }
            }
        }

        // 거래처 등록 함수
        function addCustomer() {
            var baseUrl = getBaseUrl();
            var url = baseUrl + "/corp/add.php";
            window.open(url, '_blank', 'width=1200,height=900,scrollbars=yes,resizable=yes');
        }

        // 컬럼 설정 관련 함수들

        // 쿠키 저장 함수
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        // 쿠키 읽기 함수
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // 컬럼 설정 모달 열기
        function openColumnSettings() {
            var columns = table.getColumns();
            var checkboxContainer = $('#columnCheckboxes');
            checkboxContainer.empty();

            // 각 컬럼에 대한 체크박스 생성
            columns.forEach(function(column) {
                var definition = column.getDefinition();
                var field = definition.field;
                var title = definition.title;

                // 선택 컬럼과 번호 컬럼은 제외
                if (field === 'select' || field === 'num') {
                    return;
                }

                var isVisible = column.isVisible();
                var checkboxHtml =
                    '<div class="col-md-6 mb-2">' +
                    '  <div class="form-check">' +
                    '    <input class="form-check-input" type="checkbox" value="' + field + '" ' +
                    '           id="col_' + field + '" ' + (isVisible ? 'checked' : '') +
                    '           onchange="toggleColumn(\'' + field + '\')">' +
                    '    <label class="form-check-label" for="col_' + field + '">' +
                    '      ' + title +
                    '    </label>' +
                    '  </div>' +
                    '</div>';

                checkboxContainer.append(checkboxHtml);
            });

            // 모달 표시
            var modal = new bootstrap.Modal(document.getElementById('columnSettingsModal'));
            modal.show();
        }

        // 컬럼 표시/숨김 토글
        function toggleColumn(field) {
            var checkbox = $('#col_' + field);
            var isChecked = checkbox.is(':checked');

            if (isChecked) {
                table.showColumn(field);
            } else {
                table.hideColumn(field);
            }

            // 설정 저장
            saveColumnSettings();
        }

        // 컬럼 설정 저장
        function saveColumnSettings() {
            var columns = table.getColumns();
            var settings = {};

            columns.forEach(function(column) {
                var definition = column.getDefinition();
                var field = definition.field;

                // 선택 컬럼과 번호 컬럼은 제외
                if (field === 'select' || field === 'num') {
                    return;
                }

                settings[field] = column.isVisible();
            });

            // JSON 문자열로 변환하여 쿠키에 저장 (365일 유지)
            setCookie('corp_table_columns', JSON.stringify(settings), 365);
        }

        // 컬럼 설정 불러오기
        function loadColumnSettings() {
            var settingsStr = getCookie('corp_table_columns');

            if (!settingsStr) {
                return; // 저장된 설정이 없으면 기본값 사용
            }

            try {
                var settings = JSON.parse(settingsStr);

                // 각 컬럼의 표시 여부 적용
                Object.keys(settings).forEach(function(field) {
                    var isVisible = settings[field];

                    if (isVisible) {
                        table.showColumn(field);
                    } else {
                        table.hideColumn(field);
                    }
                });
            } catch (e) {
                console.error('컬럼 설정 로드 실패:', e);
            }
        }

        // 컬럼 설정 초기화
        function resetColumnSettings() {
            // 쿠키 삭제
            setCookie('corp_table_columns', '', -1);

            // 모든 컬럼 표시 (선택, 번호 제외)
            var columns = table.getColumns();
            columns.forEach(function(column) {
                var definition = column.getDefinition();
                var field = definition.field;

                if (field !== 'select' && field !== 'num') {
                    table.showColumn(field);
                }
            });

            // 모달 닫기
            var modal = bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'));
            if (modal) {
                modal.hide();
            }

            // 모달 재오픈 (체크박스 상태 업데이트 위해)
            setTimeout(function() {
                openColumnSettings();
            }, 300);
        }
    </script>

    <div class="container-fluid mt-3 mb-3">
        <?php include '../footer_sub.php'; ?>
    </div>
</body>

</html>
