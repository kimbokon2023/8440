<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
$num = isset($_POST['num']) ? $_POST['num'] : '';

$tablename = 'holiday';
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

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
} else {
    include '_request.php';
    $mode = 'insert';	
    $registedate = date('Y-m-d');	
}

$title_message = ($mode === 'update') ? '휴무 수정' : '휴무 신규 등록';
?>

<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>">   
<input type="hidden" id="registedate" name="registedate" value="<?=$registedate?>">      

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center">
        <div class="card justify-content-center">
            <div class="card-header text-center">
                <span class="text-center fs-5 "><?=$title_message?></span>
            </div>
            <div class="card-body">
                <div class="row justify-content-center text-center">
                    <div class="d-flex align-items-center justify-content-center m-2">
                        <table class="table table-bordered">
                            <tbody>                                
								<tr>
									<td class="text-center fs-6 fw-bold" style="width:150px;">기간 설정</td>
									<td class="text-start"  style="width:450px;">										
										<input type="checkbox" id="periodcheck" name="periodcheck" value="1" <?= $periodcheck ? 'checked' : '' ?> >
										<span>
											<input type="date" class="form-control d-inline fs-6" id="startdate" name="startdate" style="width:130px;" value="<?=$startdate?>">
											<span id="enddateWrapper" style="<?= $periodcheck ? '' : 'display:none;' ?>">
												~
												<input type="date" class="form-control d-inline fs-6" id="enddate" name="enddate" style="width:130px;" value="<?=$enddate?>">
											</span>
										</span>
									</td>
								</tr>

                                <tr>
                                    <td class="text-center fs-6 fw-bold" style="width:150px;">비고</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fs-6" id="comment" name="comment" value="<?=$comment?>" autocomplete="off" >
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
                    <button type="button" id="deleteBtn" class="btn btn-danger btn-sm me-3">
                       <i class="bi bi-trash"></i>  삭제 
                    </button>                    
                    <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
                        &times; 닫기
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('periodcheck').addEventListener('change', function() {
    const periodFields = document.getElementById('periodFields');
    periodFields.style.display = this.checked ? 'inline' : 'none';
});
</script>
