<?php
// SQL 쿼리를 작성하여 출고된 자재의 전체 생산 면적, 양산품 면적, 불량품 면적을 계산 (소장 제외)/기타/소재 제외
$sql_total_area = "
    SELECT 
        SUM(CASE WHEN which = '2' AND model ='천장' THEN steelnum ELSE 0 END) AS total_area,
        SUM(CASE WHEN which = '2' AND bad_choice != '해당없음' AND bad_choice != '소장'  AND bad_choice != '소재'  AND bad_choice != '업체' AND bad_choice != '기타'  AND model ='천장' THEN steelnum ELSE 0 END) AS defect_area
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

<div class="card">
	<div class="card-header text-center" style="background-color:#cfe2ff;">            
	<h5>출고 자재 불량율</h5>
	</div>
	<div class="card-body">
		<table class="table table-bordered">
			<thead class="table-primary">
				<tr>
					<th class="text-center">전체생산 면적</th>
					<th class="text-center">양산품 면적</th>
					<th class="text-center">불량품 면적</th>
					<th class="text-center">불량율 (%)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-center"><?= number_format($total_area, 2) ?> ㎡</td>
					<td class="text-center"><?= number_format($good_area, 2) ?> ㎡</td>
					<td class="text-center"><?= number_format($defect_area, 2) ?> ㎡</td>
					<td class="text-center text-danger"><?= number_format($defect_rate, 2) ?>%</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
