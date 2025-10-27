<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/login/check_login.php';

// 관리자만 접근 가능하도록 체크
if ($_SESSION['daon_userid'] !== 'admin' && $_SESSION['daon_userid'] !== 'daon') {
    die('권한이 없습니다. 관리자만 접근 가능합니다.');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>중복 데이터 정리</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #667eea; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #667eea; color: white; }
        .duplicate { background: #ffebee; }
        .keep { background: #e8f5e9; font-weight: bold; }
        .btn { padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; margin: 10px 5px; }
        .btn-danger { background: #f44336; color: white; }
        .btn-primary { background: #667eea; color: white; }
        .btn-secondary { background: #999; color: white; }
        .btn:hover { opacity: 0.9; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .success { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧹 중복 발주 데이터 정리</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'cleanup') {
                try {
                    $pdo->beginTransaction();

                    // 중복된 발주번호 찾기
                    $sql = "SELECT order_number, MIN(id) as keep_id, COUNT(*) as cnt
                            FROM daon_orders
                            GROUP BY order_number
                            HAVING COUNT(*) > 1";
                    $stmt = $pdo->query($sql);
                    $duplicates = $stmt->fetchAll();

                    $totalDeleted = 0;

                    foreach ($duplicates as $dup) {
                        // 가장 오래된 것(MIN ID)만 남기고 나머지 삭제
                        $deleteSql = "DELETE FROM daon_orders
                                     WHERE order_number = :order_number
                                     AND id != :keep_id";
                        $deleteStmt = $pdo->prepare($deleteSql);
                        $deleteStmt->execute([
                            ':order_number' => $dup['order_number'],
                            ':keep_id' => $dup['keep_id']
                        ]);

                        $totalDeleted += $deleteStmt->rowCount();
                    }

                    $pdo->commit();

                    echo "<div class='success'>";
                    echo "<strong>✅ 정리 완료!</strong><br>";
                    echo "총 <strong>{$totalDeleted}건</strong>의 중복 데이터가 삭제되었습니다.<br>";
                    echo "각 발주번호별로 가장 오래된 데이터만 남겼습니다.";
                    echo "</div>";

                    echo "<a href='index.php' class='btn btn-primary'>메인으로 돌아가기</a>";

                } catch (PDOException $e) {
                    $pdo->rollBack();
                    echo "<div style='color:red; padding:15px; background:#ffebee; border-radius:5px;'>";
                    echo "❌ 오류 발생: " . htmlspecialchars($e->getMessage());
                    echo "</div>";
                }
            }
        } else {
            // 중복 데이터 미리보기
            try {
                // 중복된 발주번호 찾기
                $sql = "SELECT order_number, COUNT(*) as cnt
                        FROM daon_orders
                        GROUP BY order_number
                        HAVING COUNT(*) > 1";
                $stmt = $pdo->query($sql);
                $duplicates = $stmt->fetchAll();

                if (count($duplicates) === 0) {
                    echo "<div class='success'>";
                    echo "<strong>✅ 중복 데이터가 없습니다!</strong><br>";
                    echo "모든 발주 데이터가 정상입니다.";
                    echo "</div>";
                    echo "<a href='index.php' class='btn btn-primary'>메인으로 돌아가기</a>";
                } else {
                    echo "<div class='warning'>";
                    echo "<strong>⚠️ 중복된 발주번호 발견!</strong><br>";
                    echo "총 <strong>" . count($duplicates) . "개</strong>의 발주번호에 중복이 있습니다.<br>";
                    echo "각 발주번호별로 가장 오래된 데이터만 남기고 나머지를 삭제합니다.";
                    echo "</div>";

                    echo "<div class='info'>";
                    echo "<strong>📋 중복 발주 목록:</strong><br>";
                    foreach ($duplicates as $dup) {
                        echo "- " . htmlspecialchars($dup['order_number']) . " : " . $dup['cnt'] . "건<br>";
                    }
                    echo "</div>";

                    // 상세 내역 표시
                    foreach ($duplicates as $dup) {
                        echo "<h3>발주번호: " . htmlspecialchars($dup['order_number']) . "</h3>";

                        $detailSql = "SELECT
                                        id,
                                        order_number,
                                        order_date,
                                        product_name,
                                        quantity,
                                        unit,
                                        total_price,
                                        created_at
                                    FROM daon_orders
                                    WHERE order_number = :order_number
                                    ORDER BY id ASC";
                        $detailStmt = $pdo->prepare($detailSql);
                        $detailStmt->execute([':order_number' => $dup['order_number']]);
                        $details = $detailStmt->fetchAll();

                        echo "<table>";
                        echo "<tr>";
                        echo "<th>상태</th>";
                        echo "<th>ID</th>";
                        echo "<th>발주일</th>";
                        echo "<th>제품명</th>";
                        echo "<th>수량</th>";
                        echo "<th>금액</th>";
                        echo "<th>등록일시</th>";
                        echo "</tr>";

                        $isFirst = true;
                        foreach ($details as $detail) {
                            $rowClass = $isFirst ? 'keep' : 'duplicate';
                            $status = $isFirst ? '✅ 보관' : '❌ 삭제';

                            echo "<tr class='$rowClass'>";
                            echo "<td>$status</td>";
                            echo "<td>" . $detail['id'] . "</td>";
                            echo "<td>" . $detail['order_date'] . "</td>";
                            echo "<td>" . htmlspecialchars($detail['product_name']) . "</td>";
                            echo "<td>" . number_format($detail['quantity']) . " " . htmlspecialchars($detail['unit']) . "</td>";
                            echo "<td>" . number_format($detail['total_price']) . "원</td>";
                            echo "<td>" . $detail['created_at'] . "</td>";
                            echo "</tr>";

                            $isFirst = false;
                        }

                        echo "</table>";
                    }

                    echo "<form method='POST' onsubmit='return confirm(\"정말로 중복 데이터를 삭제하시겠습니까?\");'>";
                    echo "<input type='hidden' name='action' value='cleanup'>";
                    echo "<button type='submit' class='btn btn-danger'>🗑️ 중복 데이터 삭제</button>";
                    echo "<a href='index.php' class='btn btn-secondary'>취소</a>";
                    echo "</form>";
                }

            } catch (PDOException $e) {
                echo "<div style='color:red;'>오류: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
        ?>
    </div>
</body>
</html>
