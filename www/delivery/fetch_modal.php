<?php
require_once(includePath('session.php'));  

$mode = $_POST['mode'] ?? '';
$num = $_POST['num'] ?? '';

$tablename = 'delivery';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$title_message = ($mode === 'update') ? '경동화물/택배 정보 수정' : (($mode === 'copy') ? '경동화물/택배 정보 복사' : '경동화물/택배 정보 신규 등록');

if ($mode === 'update' && $num) {
    try {
        $sql = "SELECT * FROM ". $DB . "." . $tablename . " WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);      
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        include '_row.php';
    } catch (PDOException $Exception) {
        echo "오류: ".$Exception->getMessage();
        exit;
    }
}
else if ($mode === 'copy' && $num) {
    try {
        $sql = "SELECT * FROM ". $DB . "." . $tablename . " WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);      
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        include '_row.php';
        $mode = 'copy';    
        $num = null;
    } catch (PDOException $Exception) {
        echo "오류: ".$Exception->getMessage();
        exit;
    }
}
else {
    include '_request.php';
    $mode = 'insert';    
    $registedate = date('Y-m-d');
}
?>

<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>">

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center">
        <div class="card w-100" >
            <div class="card-header text-center align-items-center">
				<div class="d-flex align-items-center justify-content-center m-2">
					<span class="text-center fs-5 mx-3"><?=$title_message?></span>
						<button type="button"  data-num="<?=$num?>" class="btn btn-outline-dark btn-sm me-5" id="showlogBtn"   >								
						     Log 기록
						</button>
				</div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center text-center">
                    <div class="d-flex align-items-center justify-content-center m-2">
                        <table class="table table-bordered" id="listTable" >
                            <tbody>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">등록일자</td>
                                    <td class="text-center" colspan="3">
                                        <input type="date" class="form-control fs-6 noborder-input w120px" id="registedate"   autocomplete="off"  name="registedate" style="width:130px;" value="<?=$registedate?>">
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">받을 분</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" id="receiver"   autocomplete="off" name="receiver" value="<?=$receiver?>">
                                    </td>
                                    <td class="text-center fs-6 fw-bold">연락처</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" id="receiver_tel"   autocomplete="off" name="receiver_tel" value="<?=$receiver_tel?>">
                                    </td>
								</tr>
                                <tr>									
                                    <td class="text-center fs-6 fw-bold">도착지 영업소 또는 받을 분 주소</td>
                                    <td class="text-center" colspan="3">
                                        <input type="text" class="form-control fs-6 noborder-input" id="address"   autocomplete="off" name="address" value="<?=$address?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">보내는 사람</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" id="sender"  autocomplete="off"  name="sender" value="<?=$sender?>">
                                    </td>
                                    <td class="text-center fs-6 fw-bold">품명/현장명</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input" id="item_name"   autocomplete="off" name="item_name" value="<?=$item_name?>">
                                    </td>
                                </tr>
                                <tr>
									<td class="text-center fs-6 fw-bold">포장</td>
									<td class="text-center">
										<select id="unit" name="unit" class="form-select fs-6 noborder-input w120px" style="font-size: 0.8rem; height: 32px;">
											<?php 
											$options = ['(선택)', '박스', '파렛트'];
											foreach ($options as $option): 
											?>
												<option value="<?= $option ?>" <?= ($unit === $option) ? 'selected' : '' ?>>
													<?= $option ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>
                                    <td class="text-center fs-6 fw-bold">수량</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6 noborder-input w50px text-end" id="surang" name="surang" value="<?=$surang?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">운임</td>
									<td class="text-center">
										<input type="text" class="form-control fs-6 noborder-input w120px text-end"  autocomplete="off" 
											   id="fee" name="fee" 
											   value="<?= number_format((int)str_replace(',', '', $fee)) ?>"
											   oninput="inputNumberFormat(this)">
									</td>

									<td class="text-center fs-6 fw-bold">운임구분</td>
									<td class="text-center">
										<select id="fee_type" name="fee_type" class="form-select fs-6 noborder-input w120px" style="font-size: 0.8rem; height: 32px;">
											<?php 
											$options = ['(선택)','현택', '착택', '현화', '착화'];
											foreach ($options as $option): 
											?>
												<option value="<?= $option ?>" <?= ($fee_type === $option) ? 'selected' : '' ?>>
													<?= $option ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>

                                </tr>
                                <tr>
                                    <td class="text-center fs-6 fw-bold">물품가액</td>
									<td class="text-center" colspan="3">
										<input type="text" class="form-control fs-6 noborder-input w120px text-start"  autocomplete="off" 
											   id="goods_price" name="goods_price" 
											   value="<?=$goods_price?>" >
									</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" id="saveBtn" class="btn btn-dark btn-sm me-3">
                        <i class="bi bi-floppy-fill"></i> 저장
                    </button>
                    <?php if($mode != 'insert' && $mode != 'copy') { ?>
                    <button type="button" id="copyBtn" class="btn btn-primary btn-sm me-3">
                        <i class="bi bi-copy"> </i> 복사 
                    </button>					
                    <button type="button" id="deleteBtn" class="btn btn-danger btn-sm me-3">
                       <i class="bi bi-trash"></i>  삭제 
                    </button>
                    <?php } ?>
                    <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
                        &times; 닫기
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
