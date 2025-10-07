<?php
require_once __DIR__ . '/../common/functions.php';
?>

<?php
// =================================================================================
// 3. unit_price_process.php - 데이터 처리 (백엔드)
// =================================================================================
?>
<?php require_once(includePath('session.php'));

// --- 권한 확인 ---
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    echo "<script>alert('권한이 없습니다.');</script>";
    exit;
}

// --- 전달된 값 확인 ---
$tablename = isset($_POST["tablename"]) ? $_POST["tablename"] : "";
$mode = isset($_POST["mode"]) ? $_POST["mode"] : "";
$num = isset($_POST["num"]) ? $_POST["num"] : "";

if (empty($tablename) || empty($mode)) {
    echo "<script>alert('잘못된 접근입니다.');</script>";
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    if ($mode == "delete") {
        // --- 삭제 처리 ---
        $sql = "DELETE FROM mirae8440.{$tablename} WHERE num = :num";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':num', $num, PDO::PARAM_INT);
        $stmh->execute();

    } else {
        // --- 삽입 또는 수정에 사용될 값들 ---
        $params = [
            ':prodcode' => $_POST['prodcode'] ?? null,
            ':texture_eng' => $_POST['texture_eng'] ?? null,
            ':texture_kor' => $_POST['texture_kor'] ?? null,
            ':design_eng' => $_POST['design_eng'] ?? null,
            ':design_kor' => $_POST['design_kor'] ?? null,
            ':type' => $_POST['type'] ?? null,
            ':size' => $_POST['size'] ?? null,
            ':thickness' => empty($_POST['thickness']) ? null : $_POST['thickness'],
            ':area' => empty($_POST['area']) ? null : $_POST['area'],
            ':dist_price_per_m2' => empty($_POST['dist_price_per_m2']) ? null : $_POST['dist_price_per_m2'],
            ':dist_price_total' => empty($_POST['dist_price_total']) ? null : $_POST['dist_price_total'],
            ':retail_price_per_m2' => empty($_POST['retail_price_per_m2']) ? null : $_POST['retail_price_per_m2'],
            ':retail_price_total' => empty($_POST['retail_price_total']) ? null : $_POST['retail_price_total'],
            ':image_url' => $_POST['image_url'] ?? null,
            ':price_agent' => empty($_POST['price_agent']) ? null : $_POST['price_agent'],
            ':price_per_m2' => empty($_POST['price_per_m2']) ? null : $_POST['price_per_m2']
        ];

        if ($mode == "edit") {
            // --- 수정 처리 ---
            $sql = "UPDATE mirae8440.{$tablename} SET 
                        prodcode = :prodcode,
                        texture_eng = :texture_eng, texture_kor = :texture_kor,
                        design_eng = :design_eng, design_kor = :design_kor,
                        type = :type, size = :size, thickness = :thickness, area = :area,
                        dist_price_per_m2 = :dist_price_per_m2, dist_price_total = :dist_price_total,
                        retail_price_per_m2 = :retail_price_per_m2, retail_price_total = :retail_price_total,
                        image_url = :image_url,
                        price_agent = :price_agent,
                        price_per_m2 = :price_per_m2 
                    WHERE num = :num";
            
            $params[':num'] = $num;
            $stmh = $pdo->prepare($sql);
            $stmh->execute($params);

        } else { // mode == "new"
            // --- 신규 등록 처리 ---
            $sql = "INSERT INTO mirae8440.{$tablename} 
                        (prodcode, texture_eng, texture_kor, design_eng, design_kor, type, size, thickness, area, dist_price_per_m2, dist_price_total, retail_price_per_m2, retail_price_total, image_url, price_agent, price_per_m2) 
                    VALUES 
                        (:prodcode, :texture_eng, :texture_kor, :design_eng, :design_kor, :type, :size, :thickness, :area, :dist_price_per_m2, :dist_price_total, :retail_price_per_m2, :retail_price_total, :image_url, :price_agent, :price_per_m2)";
            
            $stmh = $pdo->prepare($sql);
            $stmh->execute($params);
        }
    }
} catch (PDOException $e) {
    // 오류 발생 시 스크립트 중단 및 메시지 출력
    die("데이터베이스 오류: " . $e->getMessage());
}

// --- 처리 완료 후 팝업 닫고 부모창 새로고침 ---
echo "
<script>
    try {
        if (window.opener && !window.opener.closed) {
            window.opener.location.reload();
        }
    } catch (e) {
        console.error(e);
    }
    window.close();
</script>
";
?>