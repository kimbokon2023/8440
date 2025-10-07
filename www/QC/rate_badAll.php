<?php

// SQL 쿼리를 작성하여 출고된 자재의 전체 생산 면적, 양산품 면적, 불량품 면적을 계산
$sql_total_area = "
    SELECT 
        SUM(CASE WHEN which = '2' THEN steelnum ELSE 0 END) AS total_area,
        SUM(CASE WHEN which = '2' AND bad_choice != '해당없음' THEN steelnum ELSE 0 END) AS defect_area
    FROM mirae8440.steel
";

try {
    $stmh_area = $pdo->query($sql_total_area);
    $area_data = $stmh_area->fetch(PDO::FETCH_ASSOC);

    $total_area = $area_data['total_area'];
    $defect_area = $area_data['defect_area'];
    $good_area = $total_area - $defect_area;

    // 불량율 계산
    $defect_rate = ($total_area > 0) ? ($defect_area / $total_area) * 100 : 0;
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

?>

<style>
/* CSS Variables for Quality Dashboard */
:root {
	--dashboard-primary: #e0f2fe;
	--dashboard-secondary: #b3e5fc;
	--dashboard-accent: #0288d1;
	--dashboard-text: #01579b;
	--dashboard-text-secondary: #0277bd;
	--dashboard-border: #b0e6f7;
	--dashboard-hover: #f1f8fe;
	--dashboard-shadow: 0 2px 12px rgba(2, 136, 209, 0.08);
}

.modern-quality-card {
	background: linear-gradient(135deg, rgba(224, 242, 254, 0.1), rgba(255, 255, 255, 0.05));
	backdrop-filter: blur(10px);
	border: 1px solid var(--dashboard-border);
	border-radius: 12px;
	margin-bottom: 1rem;
	box-shadow: var(--dashboard-shadow);
	transition: all 0.2s ease;
}

.modern-quality-card:hover {
	transform: translateY(-1px);
	box-shadow: 0 6px 20px rgba(2, 136, 209, 0.12);
}

.modern-quality-header {
	background: linear-gradient(135deg, var(--dashboard-accent), #0277bd);
	color: white;
	padding: 0.5rem;
	text-align: center;
	font-size: 0.8rem;
	font-weight: 600;
	letter-spacing: 0.3px;
	border-radius: 12px 12px 0 0;
}

.modern-quality-table {
	width: 100%;
	border-collapse: collapse;
	margin: 0;
	background: white;
}

.modern-quality-table th {
	background: var(--dashboard-secondary);
	color: var(--dashboard-text);
	padding: 0.4rem 0.3rem;
	font-size: 0.7rem;
	font-weight: 600;
	text-align: center;
	border-bottom: 1px solid var(--dashboard-border);
}

.modern-quality-table td {
	padding: 0.4rem 0.3rem;
	font-size: 0.75rem;
	text-align: center;
	border-bottom: 1px solid #f0f9ff;
	transition: background-color 0.2s ease;
}

.modern-quality-table tr:hover td {
	background-color: var(--dashboard-hover);
}

.modern-quality-danger {
	color: #dc3545;
	font-weight: 600;
}
</style>

<div class="modern-quality-card">
	<?php if($option =='option') : ?>
	<?php else: ?>
	<div class="modern-quality-header">
	전체 자재 불량율
	</div>
	<?php endif; ?>
	<?php if($option =='option') : ?>
		<div style="padding: 0.5rem;">
	<?php else: ?>
		<div style="padding: 1rem;">
	<?php endif; ?>
		<table class="modern-quality-table">
			<thead>
				<tr>
					<th>전체생산(㎡)</th>
					<th>양산품(㎡)</th>
					<th>불량품(㎡)</th>
					<th class="modern-quality-danger">불량(%)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?= number_format($total_area, 2) ?></td>
					<td><?= number_format($good_area, 2) ?></td>
					<td><?= number_format($defect_area, 2) ?></td>
					<td class="modern-quality-danger"><?= number_format($defect_rate, 2) ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
 