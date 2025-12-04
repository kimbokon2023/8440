<?php
/**
 * 견적서 상세보기 페이지 (Phomi 스타일 적용)
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";
$WebSite = $base_url . '/';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    echo "<script>alert('권한이 없습니다.'); location.href='{$WebSite}login/logout.php';</script>";
    exit;
}

include getDocumentRoot() . '/load_header.php';

// ID 확인
$id = $_REQUEST["id"] ?? 0;
$id = (int)$id;

if ($id <= 0) {
    echo "<script>alert('잘못된 요청입니다.'); location.href='index.php';</script>";
    exit;
}

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

// 견적서 데이터 조회
$stmt = $pdo->prepare("SELECT * FROM `estimates` WHERE id = :id AND is_deleted = 0");
$stmt->execute([':id' => $id]);
$estimate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estimate) {
    echo "<script>alert('존재하지 않는 견적서입니다.'); location.href='index.php';</script>";
    exit;
}

// 기본값 설정
$quote_date = $estimate['issue_date'] ?? '';
$recipient = $estimate['supplier_name'] ?? ''; // 수신자
$site_name = $estimate['project_site'] ?? '';
$recipient_phone = $estimate['supplier_phone'] ?? '';

// 공급자 정보 (우리 회사 - 미래기업)
$supplier_company = "주식회사 미래기업";
$supplier_ceo = "소현철";
$supplier_biz_no = "722-88-00035";
$supplier_address = "경기도 김포시 양촌읍 흥신로 220-27";
$supplier_tel = "031-985-8440";
$supplier_fax = "031-985-8441";
$supplier_email = "mirae8440@naver.com";

// 작성자 정보
// $author = $estimate['contact_name'] ?? '관리자'; // DB에 작성자 정보가 있다면 사용
$author = '관리자'; // 임시

// 아이템 데이터 파싱
$items = json_decode($estimate['estimate_items'] ?? '[]', true) ?? [];

// 합계 계산
$total_supply = 0;
$total_tax = 0;
foreach ($items as $item) {
    $s_amt = isset($item['견적가액']) ? str_replace(',', '', $item['견적가액']) : ($item['supply_amount'] ?? 0);
    $t_amt = isset($item['세액']) ? str_replace(',', '', $item['세액']) : ($item['tax_amount'] ?? 0);
    
    $total_supply += (float)$s_amt;
    $total_tax += (float)$t_amt;
}
$total_amount = $total_supply + $total_tax;

?>

<!-- 스타일 (Phomi 스타일 가져옴) -->
<style>
    /* 기본 스타일 */
    body { font-family: 'Noto Sans KR', sans-serif; background-color: #f5f7fa; }
    .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .table th { background-color: #f8f9fa; font-weight: 600; text-align: center; vertical-align: middle; }
    .table td { vertical-align: middle; }
    .btn-sm { font-size: 0.8rem; }
    
    /* 인쇄/PDF용 스타일 */
    @media print {
        body { background-color: white; }
        .no-print { display: none !important; }
        .card { box-shadow: none; border: 1px solid #ddd; }
    }

    /* 공급자 정보 테이블 스타일 */
    .supplier-table td { padding: 5px 10px; font-size: 0.9rem; }
    .supplier-table .label-cell { background-color: #f8f9fa; text-align: center; font-weight: bold; width: 80px; }
</style>

<div class="container-fluid my-4">
    <div class="card">
        <div class="card-body p-4">
            <!-- 상단 버튼 그룹 -->
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <h4 class="mb-0 fw-bold">견적서 상세보기 #<?php echo $id; ?></h4>
                <div>
                    <a href="index.php" class="btn btn-secondary me-2">목록</a>
                    <a href="write_form.php?id=<?php echo $id; ?>" class="btn btn-primary me-2">수정</a>
                    <a href="write_form.php?id=<?php echo $id; ?>&mode=copy" class="btn btn-info me-2">복사</a>
                    <button type="button" class="btn btn-danger" onclick="deleteEstimate()">삭제</button>
                    <button type="button" class="btn btn-dark ms-2" onclick="window.print()">인쇄</button>
                </div>
            </div>

            <!-- 견적서 헤더 (수신/공급자) -->
            <div class="row mb-4">
                <!-- 수신자 정보 -->
                <div class="col-md-6">
                    <div class="card h-100 bg-light border">
                        <div class="card-body">
                            <h6 class="card-title fw-bold border-bottom pb-2 mb-3">수신 (Customer)</h6>
                            <div class="mb-2 row">
                                <label class="col-sm-3 fw-bold">상호(성명)</label>
                                <div class="col-sm-9"><?php echo htmlspecialchars($recipient); ?></div>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-3 fw-bold">현장명</label>
                                <div class="col-sm-9"><?php echo htmlspecialchars($site_name); ?></div>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-3 fw-bold">전화번호</label>
                                <div class="col-sm-9"><?php echo htmlspecialchars($recipient_phone); ?></div>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-3 fw-bold">견적일자</label>
                                <div class="col-sm-9"><?php echo $quote_date; ?></div>
                            </div>
                            <div class="text-center mt-3 fw-bold text-primary">
                                아래와 같이 견적합니다.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 공급자 정보 -->
                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0 h-100 supplier-table">
                                <tr>
                                    <td rowspan="5" class="label-cell" style="vertical-align: middle; width: 40px;">공<br>급<br>자</td>
                                    <td class="label-cell">등록번호</td>
                                    <td colspan="3" class="fw-bold text-center"><?php echo $supplier_biz_no; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">상 호</td>
                                    <td><?php echo $supplier_company; ?></td>
                                    <td class="label-cell">대 표</td>
                                    <td><?php echo $supplier_ceo; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">주 소</td>
                                    <td colspan="3"><?php echo $supplier_address; ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">업 태</td>
                                    <td>제조업</td>
                                    <td class="label-cell">종 목</td>
                                    <td>엘리베이터의장품</td>
                                </tr>
                                <tr>
                                    <td class="label-cell">담 당</td>
                                    <td><?php echo $author; ?></td>
                                    <td class="label-cell">연락처</td>
                                    <td><?php echo $supplier_tel; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 합계 요약 -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-secondary d-flex justify-content-between align-items-center py-2">
                        <div class="fs-5 fw-bold">합계금액 (VAT 포함)</div>
                        <div class="fs-4 fw-bold text-danger">
                            ₩ <?php echo number_format($total_amount); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 아이템 테이블 -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>품목 (Item)</th>
                            <th style="width: 15%;">규격 (Spec)</th>
                            <th style="width: 8%;">수량</th>
                            <th style="width: 8%;">단위</th>
                            <th style="width: 12%;">단가</th>
                            <th style="width: 12%;">견적가액</th>
                            <th style="width: 10%;">세액</th>
                            <th style="width: 15%;">비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $idx = 1;
                        foreach ($items as $item): 
                            $p_name = $item['product_name'] ?? $item['품목'] ?? '';
                            $spec = $item['spec'] ?? $item['규격'] ?? '';
                            $qty = $item['quantity'] ?? $item['수량'] ?? 0;
                            $unit = $item['unit'] ?? $item['단위'] ?? 'EA';
                            $price = $item['unit_price'] ?? $item['단가'] ?? 0;
                            $amt = $item['supply_amount'] ?? $item['견적가액'] ?? 0;
                            $tax = $item['tax_amount'] ?? $item['세액'] ?? 0;
                            $rem = $item['remarks'] ?? $item['비고'] ?? '';
                        ?>
                        <tr>
                            <td><?php echo $idx++; ?></td>
                            <td class="text-start"><?php echo htmlspecialchars($p_name); ?></td>
                            <td><?php echo htmlspecialchars($spec); ?></td>
                            <td class="text-end"><?php echo number_format($qty); ?></td>
                            <td><?php echo htmlspecialchars($unit); ?></td>
                            <td class="text-end"><?php echo number_format($price); ?></td>
                            <td class="text-end"><?php echo number_format($amt); ?></td>
                            <td class="text-end"><?php echo number_format($tax); ?></td>
                            <td class="text-start"><?php echo htmlspecialchars($rem); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-3">등록된 품목이 없습니다.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="6" class="text-end">소계</td>
                            <td class="text-end"><?php echo number_format($total_supply); ?></td>
                            <td class="text-end"><?php echo number_format($total_tax); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    function deleteEstimate() {
        if (confirm('정말로 이 견적서를 삭제하시겠습니까?\n\n삭제된 견적서는 복구할 수 없습니다.')) {
            fetch('delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: <?php echo $id; ?> })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('삭제되었습니다.');
                    location.href = 'index.php';
                } else {
                    alert('삭제 실패: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('오류가 발생했습니다.');
            });
        }
    }
</script>