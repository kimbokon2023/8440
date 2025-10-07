<?php
require_once(includePath('session.php'));  

$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
$num = isset($_POST['num']) ? $_POST['num'] : '';
$seldate = isset($_POST['seldate']) ? $_POST['seldate'] :  date("Y-m-d", time());

$tablename = 'todos';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$orderdate = $seldate;
$deadline = '';
$towhom = '';
$reply = '';
$work_status = '작성';
$title = '';
$first_writer = $user_name;

if ($mode === 'update' && $num !=='undefined' ) {
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
    $mode = 'insert';	    
}

$title_message = ($mode === 'update') ? '할 일 수정' : '할 일 신규 등록';
?>

<input type="hidden" id="update_log" name="update_log" value="<?= isset($update_log) ? $update_log : '' ?>">   
<input type="hidden" id="num" name="num" value="<?= isset($num) ? $num : '' ?>">   
<input type="hidden" id="searchtag" name="searchtag" value="<?= isset($searchtag) ? $searchtag : '' ?>">   
<input type="hidden" id="user_name" name="user_name" value="<?= isset($user_name) ? $user_name : '' ?>">   
<input type="hidden" id="first_writer" name="first_writer" value="<?= isset($first_writer) ? $first_writer : '' ?>">   
<input type="hidden" id="towhom" name="towhom" value="<?= $towhom ?>">   

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center">
        <div class="card justify-content-center">
            <div class="card-header text-center">
                <span class="text-center fs-5"><?= $title_message ?></span>
            </div>
            <div class="card-body">
                <div class="row justify-content-center text-center">
                    <div class="d-flex align-items-center justify-content-center m-2">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-center  fw-bold" style="width:150px;">요청일</td>
                                    <td class="text-center" style="width:200px;">
                                        <input type="date" class="form-control " id="orderdate" name="orderdate" style="width:130px;" value="<?= $orderdate ?>">
                                    </td>
                                    <td class="text-center  fw-bold" style="width:150px;">요청인</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control " id="first_writer" name="first_writer" value="<?= $first_writer ?>" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center  fw-bold" style="width:150px;">요청받는 분 
									<br>
										(체크x)
										<br>
										전체공지
									</td>
                                    <td class="text-center" colspan="3">
                                        <ul id="approverList" class="list-group" style="width:80%;">
                                            <?php
                                            // Fetch member list from the database
                                            $sql = "SELECT id, name, position FROM " . $DB . ".member WHERE part='지원파트' AND position !='대표' AND position !='퇴사'  AND  numorder IS not NULL  ORDER BY numorder ASC";
                                            $stmh = $pdo->prepare($sql);
                                            $stmh->execute();
                                            while ($member = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<li class="list-group-item">';
                                                echo '<input type="checkbox" class="approver-checkbox" data-user-id="' . $member['id'] . '" data-user-name="' . htmlspecialchars($member['name']) . '"> ';
                                                echo htmlspecialchars($member['name'] . ' ' . $member['position']);
                                                echo '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center  fw-bold" style="width:150px;">요청인 내용</td>
                                    <td class="text-center" colspan="3">
                                        <input type="text" class="form-control " id="title" name="title" value="<?= $title ?>" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center  fw-bold" style="width:150px;">실행하실분 메모</td>
                                    <td class="text-center" colspan="3">
                                        <input type="text" class="form-control " id="reply" name="reply" value="<?= $reply ?>" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center  fw-bold" style="width:150px;">진행상태</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control " id="work_status" name="work_status" value="<?= $work_status ?>" autocomplete="off">
                                    </td>
                                    <td class="text-center  fw-bold" style="width:150px;">요청처리일</td>
                                    <td class="text-center">
                                        <input type="date" class="form-control " id="deadline" name="deadline" style="width:130px;" value="<?= $deadline ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" id="saveBtn" class="btn btn-dark btn-sm me-3">
                        <ion-icon name="save-outline"></ion-icon> 저장
                    </button>
                    <?php if ($mode !== 'insert') { ?>
                    <button type="button" id="deleteBtn" class="btn btn-danger btn-sm me-3">
                        <ion-icon name="trash-outline"></ion-icon> 삭제
                    </button>					

					<button type="button" class="btn btn-outline-dark btn-sm me-3" id="showlogBtn"   > H
					</button>						
                    <?php } ?>
										
                    <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
                        <ion-icon name="close-circle-outline"></ion-icon> 닫기
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

