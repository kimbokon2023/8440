<?php
require_once includePath('QC/func_badAllexcept.php');
?>

<?php $option = isset($option) ? $option : ''; ?>

<div class="card">         
	<?php if($option =='option') : ?>	
	<div class="d-flex justify-content-center">
		<h6> <span class='text-center mt-2 badge bg-danger'> 미래기업(내부) 자체 불량율</span> <h6>
	</div>
	<?php else: ?>
	<div class="card-header text-center" >   	
	<h5> 미래기업(내부) 자체 불량율</h5>
	</div>
	<?php endif; ?>	
	<?php if($option =='option') : ?>
		<div class="card-body" style="padding:1!important;">
	<?php else: ?>				
		<div class="card-body">
	<?php endif; ?>	
		<table class="table table-bordered">
			<thead class="table-secondary">
				<tr>
					<th class="text-center">전체생산(㎡)</th>
					<th class="text-center">양산품(㎡)</th>
					<th class="text-center">불량품(㎡)</th>
					<th class="text-center text-danger">불량(%)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-center"><?= number_format($total_area, 2) ?> </td>
					<td class="text-center"><?= number_format($good_area, 2) ?> </td>
					<td class="text-center"><?= number_format($defect_area, 2) ?> </td>
					<td class="text-center text-danger"><?= number_format($defect_rate, 2) ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
