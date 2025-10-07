<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

if(!isset($_SESSION["level"]) || $level>5) {
    $_SESSION["url"]='https://8440.co.kr/order/view.php';
    sleep(1);
    header("Location:" . $WebSite . "login/logout.php");
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . "/common.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';

// ID 확인
$id = isset($_REQUEST["id"]) ? (int)$_REQUEST["id"] : 0;
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

// 발주서 데이터 조회
$stmt = $pdo->prepare("SELECT * FROM `order` WHERE id = :id AND is_deleted = 0");
$stmt->execute([':id' => $id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<script>alert('존재하지 않는 발주서입니다.'); location.href='index.php';</script>";
    exit;
}

$title_message = '구매발주서 상세보기';
?>

<title><?=$title_message?></title>
    <style>
        .info-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .section-title {
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.2em;
            font-weight: bold;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .info-value {
            color: #495057;
            padding: 8px 12px;
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            min-height: 38px;
            display: flex;
            align-items: center;
        }
        .amount-value {
            text-align: right;
            font-weight: bold;
            color: #007bff;
        }
        .status-badge {
            font-size: 0.9em;
        }
        .btn-section {
            text-align: center;
            margin-top: 30px;
        }
        .metadata {
            font-size: 0.9em;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
            margin-top: 20px;
        }
        .long-text {
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?php echo $title_message; ?> #<?php echo $order['id']; ?></h2>
                    <div>
                        <a href="index.php" class="btn btn-secondary me-2">
                            <i class="fas fa-list"></i> 목록
                        </a>
                        <a href="write_form.php?id=<?php echo $order['id']; ?>" class="btn btn-primary me-2">
                            <i class="fas fa-edit"></i> 수정
                        </a>
                        <a href="write_form.php?id=<?php echo $order['id']; ?>&mode=copy" class="btn btn-info me-2">
                            <i class="fas fa-copy"></i> 복사
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deleteOrder()">
                            <i class="fas fa-trash"></i> 삭제
                        </button>
                    </div>
                </div>

                <!-- 기본 정보 섹션 -->
                <div class="info-section">
                    <h4 class="section-title">기본 정보</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">발행일</div>
                                <div class="info-value">
                                    <?php echo date('Y년 m월 d일', strtotime($order['issue_date'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">상태</div>
                                <div class="info-value">
                                    <?php
                                    $status_labels = [
                                        'draft' => '<span class="badge bg-secondary status-badge">임시저장</span>',
                                        'sent' => '<span class="badge bg-primary status-badge">발송완료</span>',
                                        'completed' => '<span class="badge bg-success status-badge">완료</span>'
                                    ];
                                    echo $status_labels[$order['status']] ?? '<span class="badge bg-warning status-badge">알 수 없음</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">담당업체</div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($order['responsible_company'] ?: '미지정'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 공급업체 정보 섹션 -->
                <div class="info-section">
                    <h4 class="section-title">공급업체 정보</h4>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="info-row">
                                <div class="info-label">공급업체명</div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($order['supplier_name']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">포토앤드/작업코드</div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($order['photo_work_code'] ?: '없음'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 품목 정보 섹션 -->
                <div class="info-section">
                    <h4 class="section-title">품목 정보</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="info-row">
                                <div class="info-label">품목/규격</div>
                                <div class="info-value long-text">
                                    <?php echo htmlspecialchars($order['item_specification']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 금액 정보 섹션 -->
                <div class="info-section">
                    <h4 class="section-title">금액 정보</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">공급가액</div>
                                <div class="info-value amount-value">
                                    <?php echo number_format($order['supply_price']); ?>원
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">세액 (부가세)</div>
                                <div class="info-value amount-value">
                                    <?php echo number_format($order['tax_amount']); ?>원
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">합계금액</div>
                                <div class="info-value amount-value" style="font-size: 1.1em; background-color: #e3f2fd;">
                                    <?php echo number_format($order['total_amount']); ?>원
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 발송 정보 섹션 -->
                <div class="info-section">
                    <h4 class="section-title">발송 정보</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">최종 마감 발송일</div>
                                <div class="info-value">
                                    <?php echo $order['final_send_date'] ? date('Y년 m월 d일 H:i', strtotime($order['final_send_date'])) : '미설정'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">최종 팩스 발송일</div>
                                <div class="info-value">
                                    <?php echo $order['final_pass_send_date'] ? date('Y년 m월 d일 H:i', strtotime($order['final_pass_send_date'])) : '미설정'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 메타데이터 -->
                <div class="info-section">
                    <div class="metadata">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>최초 등록일:</strong> <?php echo date('Y년 m월 d일 H:i:s', strtotime($order['created_at'])); ?>
                            </div>
                            <div class="col-md-6">
                                <strong>마지막 수정일:</strong> <?php echo date('Y년 m월 d일 H:i:s', strtotime($order['updated_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 버튼 섹션 -->
                <div class="btn-section">
                    <a href="write_form.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-edit"></i> 수정
                    </a>
                    <a href="write_form.php?id=<?php echo $order['id']; ?>&mode=copy" class="btn btn-info btn-lg me-3">
                        <i class="fas fa-copy"></i> 복사하여 새로 작성
                    </a>
                    <a href="index.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-list"></i> 목록으로
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script>
        // 발주서 삭제 함수
        function deleteOrder() {
            if (confirm('정말로 이 발주서를 삭제하시겠습니까?\n\n삭제된 발주서는 복구할 수 없습니다.')) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: <?php echo $order['id']; ?> })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('발주서가 삭제되었습니다.');
                        location.href = 'index.php';
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

        // 인쇄 기능 (추가 기능)
        function printOrder() {
            window.print();
        }

        // 키보드 단축키
        document.addEventListener('keydown', function(e) {
            // Ctrl + E: 수정
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                location.href = 'write_form.php?id=<?php echo $order['id']; ?>';
            }
            // Ctrl + Shift + C: 복사
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                location.href = 'write_form.php?id=<?php echo $order['id']; ?>&mode=copy';
            }
            // ESC: 목록으로
            if (e.key === 'Escape') {
                location.href = 'index.php';
            }
        });
    </script>
</body>
</html>