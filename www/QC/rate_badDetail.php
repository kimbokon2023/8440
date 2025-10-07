<?php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$options = ["설계", "레이져", "V컷", "절곡", "운반중", "소장", "업체", "기타", "개발품", "소재"];

// 전체 출고 면적 및 전체 불량 면적 계산
$sql_total_area = "
    SELECT 
        SUM(CASE WHEN which = '2' THEN steelnum ELSE 0 END) AS total_area,
        SUM(CASE WHEN which = '2' AND bad_choice != '해당없음' THEN steelnum ELSE 0 END) AS total_bad_area
    FROM mirae8440.steel
";

try {
    $stmh_total = $pdo->query($sql_total_area);
    $total_area_data = $stmh_total->fetch(PDO::FETCH_ASSOC);
    $total_area = $total_area_data['total_area'];
    $total_bad_area = $total_area_data['total_bad_area'];

    $bad_data = [];

    // 각 불량 유형별로 불량율과 점유율 계산
    foreach ($options as $bad_choice) {
        $sql_bad_area = "
            SELECT 
                SUM(CASE WHEN which = '2' AND bad_choice = :bad_choice THEN steelnum ELSE 0 END) AS bad_area
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

<style>
/* Modern Quality Detail Styling */
.modern-quality-detail-card {
	background: linear-gradient(135deg, rgba(224, 242, 254, 0.1), rgba(255, 255, 255, 0.05));
	backdrop-filter: blur(10px);
	border: 1px solid var(--dashboard-border);
	border-radius: 12px;
	margin-bottom: 1rem;
	box-shadow: var(--dashboard-shadow);
	transition: all 0.2s ease;
}

.modern-quality-detail-card:hover {
	transform: translateY(-1px);
	box-shadow: 0 6px 20px rgba(2, 136, 209, 0.12);
}

.modern-quality-detail-header {
	background: linear-gradient(135deg, #dc3545, #c82333);
	color: white;
	padding: 0.5rem;
	text-align: center;
	font-size: 0.8rem;
	font-weight: 600;
	letter-spacing: 0.3px;
	border-radius: 12px 12px 0 0;
} 

.modern-quality-detail-table {
	width: 100%;
	border-collapse: collapse;
	margin: 0;
	background: white;
}

.modern-quality-detail-table th {
	background: #f8d7da;
	color: #721c24;
	padding: 0.4rem 0.3rem;
	font-size: 0.7rem;
	font-weight: 600;
	text-align: center;
	border-bottom: 1px solid #f5c6cb;
}

.modern-quality-detail-table td {
	padding: 0.4rem 0.3rem;
	font-size: 0.75rem;
	text-align: center;
	border-bottom: 1px solid #f0f9ff;
	transition: background-color 0.2s ease;
}

.modern-quality-detail-table tr:hover td {
	background-color: #fff5f5;
}

.modern-detail-danger {
	color: #dc3545;
	font-weight: 600;
}
</style>

<div class="modern-quality-detail-card">
    <div class="modern-quality-detail-header">
	<?php if($option =='option') : ?>
		전체 불량 유형별 세부 정보
	<?php else: ?>
		전체 불량 유형별 세부 정보
	<?php endif; ?>
	</div>
	<?php if($option =='option') : ?>
		<div style="padding: 0.5rem;">
	<?php else: ?>
		<div style="padding: 1rem;">
	<?php endif; ?>
        <table class="modern-quality-detail-table">
            <thead>
                <tr>
                    <th>불량유형</th>
                    <th>불량율 (%)</th>
                    <th>점유율 (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bad_data as $data): ?>
                    <tr>
                        <td><?= $data['type'] ?></td>
                        <td class="modern-detail-danger"><?= number_format($data['bad_rate'], 2) ?>%</td>
                        <td><?= number_format($data['share_rate'], 2) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
