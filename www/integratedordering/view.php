<?php
require_once __DIR__ . '/../bootstrap.php';

$num = $_REQUEST["num"] ?? '';
if (!$num) {
    echo "<script>alert('잘못된 접근입니다.'); window.close();</script>";
    exit;
}

$DB = $_SESSION["DB"] ?? 'mirae8440';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $sql = "SELECT * FROM {$DB}.eworks WHERE num = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$num]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        echo "<script>alert('데이터가 없습니다.'); window.close();</script>";
        exit;
    }
    
    include '_row.php';
    
    $type = ($eworks_item === '부자재구매') ? 'aux' : 'main';
    $page_title = ($type === 'aux') ? '부자재 상세' : '원자재 상세';
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<?php include getDocumentRoot() . '/load_header.php'; ?>
<title><?= $page_title ?></title>
<style>
    th { background-color: #f8f9fa; width: 120px; }
    td { padding: 10px; }
</style>
<body>
<?php include getDocumentRoot() . '/common/modal.php'; ?>

<div class="container mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $page_title ?> (No. <?= $num ?>)</h5>
            <div>
                <button class="btn btn-primary btn-sm" onclick="openModify()">수정</button>
                <button class="btn btn-secondary btn-sm" onclick="window.close()">닫기</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>진행상태</th>
                    <td>
                        <?php
                        if ($which == '1') echo '<span class="badge bg-primary">요청</span>';
                        elseif ($which == '2') echo '<span class="badge bg-danger">발주</span>';
                        elseif ($which == '3') echo '<span class="badge bg-secondary">완료</span>';
                        ?>
                    </td>
                    <th>접수일</th>
                    <td><?= $outdate ?></td>
                </tr>
                <tr>
                    <th>납기일</th>
                    <td><?= $requestdate ?></td>
                    <th>완료일</th>
                    <td><?= $indate ?></td>
                </tr>
                
                <?php if ($type === 'main'): ?>
                <tr>
                    <th>현장명</th>
                    <td colspan="3" class="fw-bold text-primary"><?= $outworkplace ?></td>
                </tr>
                <tr>
                    <th>모델</th>
                    <td><?= $model ?></td>
                    <th>사급업체</th>
                    <td><?= $company ?></td>
                </tr>
                <tr>
                    <th>철판종류</th>
                    <td><?= $steel_item ?></td>
                    <th>규격</th>
                    <td><?= $spec ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <th>물품명</th>
                    <td colspan="3" class="fw-bold text-primary"><?= $outworkplace ?></td>
                </tr>
                <tr>
                    <th>결제방식</th>
                    <td><?= $payment ?></td>
                    <th>규격</th>
                    <td><?= $spec ?></td>
                </tr>
                <?php endif; ?>
                
                <tr>
                    <th>수량</th>
                    <td class="text-danger fw-bold"><?= $steelnum ?></td>
                    <th>공급처</th>
                    <td><?= $supplier ?></td>
                </tr>
                <tr>
                    <th>비고</th>
                    <td colspan="3"><?= nl2br($request_comment) ?></td>
                </tr>
                <tr>
                    <th>등록정보</th>
                    <td colspan="3">
                        작성자: <?= $author ?> / 등록일: <?= $registdate ?>
                    </td>
                </tr>
            </table>
            
            <?php if ($update_log): ?>
            <div class="mt-3">
                <h6>수정 로그</h6>
                <pre class="bg-light p-2 border"><?= $update_log ?></pre>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function openModify() {
        location.href = 'write_form.php?mode=modify&num=<?= $num ?>&type=<?= $type ?>';
    }
</script>
</body>
</html>
