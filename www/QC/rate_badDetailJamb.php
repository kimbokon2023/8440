<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$options = ["설계", "레이져", "V컷", "절곡", "운반중", "업체", "기타", "개발품"]; // '소장' 제거

// 전체 출고 면적 및 전체 불량 면적 계산 (소장 제외)
$sql_total_area = "
    SELECT 
        SUM(CASE WHEN which = '2' AND model ='쟘' THEN steelnum ELSE 0 END) AS total_area,
        SUM(CASE WHEN which = '2' AND bad_choice != '해당없음' AND bad_choice != '소장'   AND bad_choice != '기타'   AND bad_choice != '소재'  AND bad_choice != '업체'  AND model ='쟘'  THEN steelnum ELSE 0 END) AS total_bad_area
    FROM mirae8440.steel
";

try {
    $stmh_total = $pdo->query($sql_total_area);
    $total_area_data = $stmh_total->fetch(PDO::FETCH_ASSOC);
    $total_area = $total_area_data['total_area'];
    $total_bad_area = $total_area_data['total_bad_area'];

    $bad_data = [];

    // 각 불량 유형별로 불량율과 점유율 계산 (소장 제외)
    foreach ($options as $bad_choice) {
        $sql_bad_area = "
            SELECT 
                SUM(CASE WHEN which = '2' AND bad_choice = :bad_choice  AND model ='쟘'  THEN steelnum ELSE 0 END) AS bad_area
            FROM mirae8440.steel
        ";

        $stmh_bad = $pdo->prepare($sql_bad_area);
        $stmh_bad->bindParam(':bad_choice', $bad_choice);
        $stmh_bad->execute();

        $bad_area_data = $stmh_bad->fetch(PDO::FETCH_ASSOC);
        $bad_area = $bad_area_data['bad_area'];

        $bad_rate = ($total_area > 0) ? ($bad_area / $total_area) * 100 : 0; // 불량율 계산
        $share_rate = ($total_bad_area > 0) ? ($bad_area / $total_bad_area) * 100 : 0; // 점유율 계산

        $bad_data[] = [
            'type' => $bad_choice,
            'bad_rate' => $bad_rate,
            'share_rate' => $share_rate
        ];
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>

<div class="card">
    <div class="card-header text-center" style="background-color:#f8d7da;">
        <h5>소장불량 제외 불량 유형별 세부 정보</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-danger">
                <tr>
                    <th class="text-center">불량유형</th>
                    <th class="text-center">불량율 (%)</th>
                    <th class="text-center">점유율 (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bad_data as $data): ?>
                    <tr>
                        <td class="text-center"><?= $data['type'] ?></td>
                        <td class="text-center text-danger"><?= number_format($data['bad_rate'], 2) ?>%</td>
                        <td class="text-center"><?= number_format($data['share_rate'], 2) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
