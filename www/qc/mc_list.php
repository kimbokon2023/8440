<?php
/**
 * QC 장비 관리 목록 페이지
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;

// 권한 확인 (관리자만 접근 가능하게 하거나, QC 담당자만 접근 가능하게 설정)
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 8) {
    echo "<script>alert('권한이 없습니다.'); history.back();</script>";
    exit;
}

$title_message = '장비 정보 관리';
$tablename = 'mymc';

include includePath('load_header.php');
?>
<title><?= $title_message ?></title>
<style>
    /* 공통 스타일 적용을 위한 간단한 오버라이드 */
    .table td, .table th {
        vertical-align: middle;
        text-align: center;
    }
</style>
</head>
<body>

<?php
require_once(includePath('myheader.php'));
require_once(includePath('common/modal.php'));
require_once(includePath('lib/mydb.php'));

$pdo = db_connect();

// 리스트 조회
$sql = "SELECT * FROM {$DB}.{$tablename} ORDER BY num ASC";
$result = $pdo->query($sql);
$total_row = $result->rowCount();
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title fw-bold m-0"><i class="bi bi-gear-fill"></i> <?= $title_message ?></h4>
                <div>
                    <button type="button" class="btn btn-outline-info me-2" onclick="openHelpModal()">
                        <i class="bi bi-question-circle"></i> 도움말
                    </button>
                    <button type="button" class="btn btn-secondary me-2" onclick="location.href='menu.php'">
                        <i class="bi bi-list"></i> 메뉴로
                    </button>
                    <button type="button" class="btn btn-primary" onclick="location.href='mc_write.php'">
                        <i class="bi bi-plus-lg"></i> 신규 장비 등록
                    </button>
                </div>
            </div>
            
            <div class="alert alert-info py-2">
                <i class="bi bi-info-circle"></i> 총 <strong><?= $total_row ?></strong>대의 장비가 등록되어 있습니다.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">관리번호</th>
                            <th width="20%">장비명</th>
                            <th width="15%">규격</th>
                            <th width="10%">제조사</th>
                            <th width="10%">담당(정)</th>
                            <th width="10%">담당(부)</th>
                            <th width="10%">QR코드</th>
                            <th width="10%">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_row > 0) {
                            $i = 1;
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $num = $row['num'];
                                $mcno = htmlspecialchars($row['mcno'] ?? '');
                                $mcname = htmlspecialchars($row['mcname'] ?? '');
                                $mcspec = htmlspecialchars($row['mcspec'] ?? '');
                                $mcmaker = htmlspecialchars($row['mcmaker'] ?? '');
                                $mcmain = htmlspecialchars($row['mcmain'] ?? '');
                                $mcsub = htmlspecialchars($row['mcsub'] ?? '');
                                $qrcode = htmlspecialchars($row['qrcode'] ?? '');
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $mcno ?></td>
                            <td class="text-start fw-bold"><?= $mcname ?></td>
                            <td><?= $mcspec ?></td>
                            <td><?= $mcmaker ?></td>
                            <td><?= $mcmain ?></td>
                            <td><?= $mcsub ?></td>
                            <td>
                                <?php if($qrcode): ?>
                                    <span class="badge bg-success">등록됨</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">미등록</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="mc_write.php?num=<?= $num ?>" class="btn btn-sm btn-outline-primary">수정</a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteitem('<?= $num ?>')">삭제</button>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center py-4'>등록된 장비가 없습니다.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include includePath('shop/footer.php'); ?>

<script>
function deleteitem(num) {
    if (confirm("정말 이 장비 정보를 삭제하시겠습니까?")) {
        location.href = 'mc_process.php?mode=delete&num=' + num;
    }
}
function openHelpModal() {
    var myModal = new bootstrap.Modal(document.getElementById('helpModal'), {
        keyboard: true
    });
    myModal.show();
}
</script>

<!-- 도움말 모달 -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white py-3">
                <h5 class="modal-title fs-5" id="helpModalLabel">
                    <i class="bi bi-info-circle"></i> 장비 점검 메뉴 사용법
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 1.15rem;">
                <div class="p-2">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-check2-square"></i> 장비 선택 및 점검</h6>
                    <p class="text-muted mb-4">
                        화면에 표시된 <strong>장비 카드</strong>를 클릭하면 해당 장비의 <strong>점검표(체크리스트)</strong> 화면으로 이동합니다.<br>
                        주간, 월간, 등 주기에 맞는 점검을 수행할 수 있습니다.
                    </p>

                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-gear"></i> 장비 관리</h6>
                    <p class="text-muted mb-4">
                        우측 상단의 <strong>'장비 관리'</strong> 버튼을 클릭하여 새로운 장비를 등록하거나<br>
                        기존 장비의 정보를 수정/삭제할 수 있습니다. (관리자 권한 필요)
                    </p>

                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-qr-code"></i> QR 코드 활용</h6>
                    <p class="text-muted mb-4">
                        장비에 부착된 <strong>QR 코드</strong>를 스마트폰으로 스캔하면<br>
                        해당 장비의 점검 화면으로 즉시 연결됩니다.
                    </p>
                    
                    <h6 class="fw-bold text-info mb-2"><i class="bi bi-person-badge"></i> 담당자 확인</h6>
                    <p class="text-muted mb-0">
                        각 장비 카드에는 <strong>(정), (부) 담당자</strong>가 표시되어 있습니다.<br>
                        점검 책임자를 쉽게 확인할 수 있습니다.
                    </p>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
