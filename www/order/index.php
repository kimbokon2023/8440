<?php
require_once(includePath('session.php'));

if(!isset($_SESSION["level"]) || $level>5) {
    $_SESSION["url"]='https://8440.co.kr/order/index.php';
    sleep(1);
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

include getDocumentRoot() . "/common.php";
require_once(includePath('lib/mydb.php'));

// 첫 화면 표시 문구
$title_message = '구매발주서 관리';

$page = $_REQUEST["page"] ?? '';
$scale = $_REQUEST["scale"] ?? '';

include getDocumentRoot() . '/load_header.php';

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

// 페이지네이션 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// 검색 조건
$search_supplier = isset($_GET['search_supplier']) ? trim($_GET['search_supplier']) : '';
$search_date_from = isset($_GET['search_date_from']) ? $_GET['search_date_from'] : '';
$search_date_to = isset($_GET['search_date_to']) ? $_GET['search_date_to'] : '';

// WHERE 조건 구성
$where_conditions = ["is_deleted = 0"];
$params = [];

if ($search_supplier) {
    $where_conditions[] = "supplier_name LIKE :search_supplier";
    $params[':search_supplier'] = '%' . $search_supplier . '%';
}

if ($search_date_from) {
    $where_conditions[] = "issue_date >= :search_date_from";
    $params[':search_date_from'] = $search_date_from;
}

if ($search_date_to) {
    $where_conditions[] = "issue_date <= :search_date_to";
    $params[':search_date_to'] = $search_date_to;
}

$where_clause = implode(' AND ', $where_conditions);

// 전체 레코드 수 조회
$count_sql = "SELECT COUNT(*) FROM `order` WHERE $where_clause";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// 데이터 조회
$sql = "SELECT * FROM `order` WHERE $where_clause ORDER BY issue_date DESC, created_at DESC LIMIT :offset, :per_page";
$stmt = $pdo->prepare($sql);

// 파라미터 바인딩
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);

$stmt->execute();
$orders = $stmt->fetchAll();
?>

<title><?=$title_message?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* 전체 레이아웃 */
        body {
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            padding: 15px;
        }

        /* 헤더 스타일 */
        .header-section {
            background-color: white;
            padding: 10px 0;
            margin-bottom: 5px;
        }

        .page-title {
            font-size: 21px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .search-input {
            width: 200px;
            height: 36px;
            font-size: 16px;
            border: 1px solid #ccc;
        }

        .btn-search {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        /* 필터 섹션 */
        .filter-section {
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }

        .filter-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .filter-group label {
            font-size: 16px;
            margin: 0;
            white-space: nowrap;
        }

        .filter-group select {
            font-size: 16px;
            height: 36px;
            border: 1px solid #ccc;
            background-color: white;
        }

        .btn-new {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 7px 16px;
            font-size: 16px;
            border-radius: 3px;
            margin-left: auto;
        }

        /* 테이블 스타일 */
        .table-responsive {
            background-color: white;
            border: 1px solid #ddd;
            overflow: hidden;
        }

        .order-table {
            margin: 0;
            font-size: 14px;
            border-collapse: collapse;
        }

        .order-table th {
            background-color: #6c757d;
            color: white;
            text-align: center;
            vertical-align: middle;
            padding: 8px 6px;
            font-weight: bold;
            border-right: 1px solid #5a6268;
            font-size: 14px;
            white-space: nowrap;
        }

        .order-table td {
            vertical-align: middle;
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            border-right: 1px solid #eee;
            font-size: 14px;
        }

        .order-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .amount-cell {
            text-align: right;
            font-family: 'Consolas', 'Monaco', monospace;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            font-size: 13px;
            padding: 3px 8px;
            border-radius: 3px;
        }

        /* 체크박스 스타일 */
        .form-check-input {
            width: 18px;
            height: 18px;
        }

        /* 버튼 그룹 */
        .btn-group-sm .btn {
            padding: 6px 10px;
            font-size: 16px;
            min-width: 42px;
        }

        .btn-group-sm .btn i {
            font-size: 18px;
        }

        /* 작업 버튼들 간격 */
        .btn-group .btn:not(:last-child) {
            margin-right: 2px;
        }

        /* 스크린 리더 전용 텍스트 */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* 페이지네이션 스타일 */
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }

        .pagination .page-link {
            font-size: 16px;
            padding: 6px 10px;
        }

        /* 링크 스타일 */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .text-truncate {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<?php include getDocumentRoot() . '/myheader.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- 상단 헤더 - 심플한 디자인 -->
                <div class="header-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h3 class="page-title">발주서</h3>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteSelected()" style="margin-left: 15px; padding: 6px 12px; font-size: 14px;">선택 삭제</button>
                            <div class="filter-controls" style="margin-left: 20px;">
                                <select name="writer" style="margin-right: 10px; height: 36px; font-size: 16px;">
                                    <option>최근 1개월</option>
                                    <option>최근 3개월</option>
                                    <option>최근 6개월</option>
                                </select>
                                <select name="order_by" style="margin-right: 10px; height: 36px; font-size: 16px;">
                                    <option>작성일</option>
                                    <option>발행일</option>
                                </select>
                                <select name="company" style="margin-right: 10px; height: 36px; font-size: 16px;">
                                    <option>전체</option>
                                </select>
                            </div>
                        </div>
                        <div class="header-actions">
                            <input type="text" class="form-control search-input" placeholder="검색어를 입력하세요" style="height: 36px; width: 200px; font-size: 16px;">
                            <button class="btn-search" style="background: none; border: none; margin-left: 5px; font-size: 18px;">🔍</button>
                            <button class="btn-new" onclick="location.href='write_form.php'" style="margin-left: 10px; padding: 7px 16px; font-size: 16px;">새 발주서</button>
                        </div>
                    </div>
                </div>

                <!-- 발주서 목록 테이블 -->
                <div class="table-responsive" style="margin-top: 10px;">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th width="3%" style="text-align: center;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="8%">발행일</th>
                                <th width="15%">공급업체명</th>
                                <th width="30%">품목/규격</th>
                                <th width="8%">공급가액</th>
                                <th width="6%">세액</th>
                                <th width="8%">합계금액</th>
                                <th width="6%">상태</th>
                                <th width="8%">납기일자</th>
                                <th width="8%">최종 마감 발송일</th>
                                <th width="12%">포토앤드/작업코드</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    등록된 발주서가 없습니다.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input order-checkbox"
                                           value="<?php echo $order['id']; ?>">
                                </td>
                                <td class="text-center">
                                    <?php echo date('Y-m-d', strtotime($order['issue_date'])); ?>
                                </td>
                                <td>
                                    <a href="write_form.php?id=<?php echo $order['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($order['supplier_name']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    // JSON 데이터에서 품목 정보 추출
                                    $items_display = '';
                                    if (!empty($order['order_items'])) {
                                        $items = json_decode($order['order_items'], true);
                                        if (is_array($items)) {
                                            $item_names = array_filter(array_column($items, '품목'));
                                            $spec_names = array_filter(array_column($items, '규격'));
                                            $combined = array_merge($item_names, $spec_names);
                                            $items_display = implode(', ', array_slice($combined, 0, 3));
                                            if (count($combined) > 3) $items_display .= '...';
                                        }
                                    }
                                    echo htmlspecialchars($items_display ?: '품목 없음');
                                    ?>
                                </td>
                                <td class="amount-cell">
                                    <?php echo $order['subtotal'] ? number_format($order['subtotal']) : '0'; ?>
                                </td>
                                <td class="amount-cell">
                                    <?php echo $order['subtotal'] ? number_format($order['subtotal'] * 0.1) : '0'; ?>
                                </td>
                                <td class="amount-cell">
                                    <?php echo $order['subtotal'] ? number_format($order['subtotal'] * 1.1) : '0'; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $status_labels = [
                                        'draft' => '임시저장',
                                        'sent' => '발송완료',
                                        'completed' => '완료'
                                    ];
                                    echo $status_labels[$order['status']] ?? '알 수 없음';
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $order['delivery_date'] ? date('Y-m-d', strtotime($order['delivery_date'])) : ''; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $order['created_at'] ? date('Y-m-d H:i', strtotime($order['created_at'])) : ''; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($order['note'] ?? ''); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- 페이지네이션 -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="페이지 네비게이션">
                    <ul class="pagination justify-content-center">
                        <?php
                        // 현재 페이지 주변의 페이지만 표시
                        $start_page = max(1, $page - 5);
                        $end_page = min($total_pages, $page + 5);

                        // 이전 페이지
                        if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1<?php echo $search_supplier ? '&search_supplier=' . urlencode($search_supplier) : ''; ?><?php echo $search_date_from ? '&search_date_from=' . urlencode($search_date_from) : ''; ?><?php echo $search_date_to ? '&search_date_to=' . urlencode($search_date_to) : ''; ?>">첫 페이지</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $search_supplier ? '&search_supplier=' . urlencode($search_supplier) : ''; ?><?php echo $search_date_from ? '&search_date_from=' . urlencode($search_date_from) : ''; ?><?php echo $search_date_to ? '&search_date_to=' . urlencode($search_date_to) : ''; ?>">이전</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search_supplier ? '&search_supplier=' . urlencode($search_supplier) : ''; ?><?php echo $search_date_from ? '&search_date_from=' . urlencode($search_date_from) : ''; ?><?php echo $search_date_to ? '&search_date_to=' . urlencode($search_date_to) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $search_supplier ? '&search_supplier=' . urlencode($search_supplier) : ''; ?><?php echo $search_date_from ? '&search_date_from=' . urlencode($search_date_from) : ''; ?><?php echo $search_date_to ? '&search_date_to=' . urlencode($search_date_to) : ''; ?>">다음</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $total_pages; ?><?php echo $search_supplier ? '&search_supplier=' . urlencode($search_supplier) : ''; ?><?php echo $search_date_from ? '&search_date_from=' . urlencode($search_date_from) : ''; ?><?php echo $search_date_to ? '&search_date_to=' . urlencode($search_date_to) : ''; ?>">마지막 페이지</a>
        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 전체 선택/해제
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // 개별 체크박스 변경 시 전체 선택 체크박스 상태 업데이트
        document.querySelectorAll('.order-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.order-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
                const selectAll = document.getElementById('selectAll');

                if (checkedCheckboxes.length === 0) {
                    selectAll.indeterminate = false;
                    selectAll.checked = false;
                } else if (checkedCheckboxes.length === allCheckboxes.length) {
                    selectAll.indeterminate = false;
                    selectAll.checked = true;
                } else {
                    selectAll.indeterminate = true;
                    selectAll.checked = false;
                }
            });
        });

        // 발주서 삭제 함수
        function deleteOrder(id) {
            if (confirm('정말로 이 발주서를 삭제하시겠습니까?')) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('발주서가 삭제되었습니다.');
                        location.reload();
                    } else {
                        alert('삭제 중 오류가 발생했습니다: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('삭제 중 오류가 발생했습니다.');
                });
            }
        }

        // 선택된 발주서들 일괄 삭제 함수
        function deleteSelected() {
            const selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                alert('삭제할 발주서를 선택해주세요.');
                return;
            }

            const selectedIds = Array.from(selectedCheckboxes).map(cb => parseInt(cb.value));
            const count = selectedIds.length;

            if (!confirm(`선택된 ${count}개의 발주서를 정말로 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.`)) {
                return;
            }

            console.log('🗑️ [DEBUG] 일괄 삭제 요청 - IDs:', selectedIds);

            fetch('delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ids: selectedIds,
                    bulk: true
                })
            })
            .then(response => {
                console.log('📡 [DEBUG] 일괄 삭제 응답 상태:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('📥 [DEBUG] 일괄 삭제 응답:', data);

                if (data.success) {
                    alert(`${data.deleted_count || count}개의 발주서가 성공적으로 삭제되었습니다.`);
                    location.reload();
                } else {
                    alert('삭제 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
                }
            })
            .catch(error => {
                console.error('❌ [DEBUG] 일괄 삭제 오류:', error);
                alert('삭제 중 오류가 발생했습니다: ' + error.message);
            });
        }

        // 검색 폼 엔터키 이벤트
        document.querySelectorAll('.search-form input').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        });
    </script>
</body>
</html>