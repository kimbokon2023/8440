<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");

$WebSite = "http://8440.co.kr/";
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

// 데이터베이스 연결 및 기존 완료일 데이터 가져오기
$pdo = db_connect();
$stmt = $pdo->prepare("SELECT eunsung_laser_date, lclaser_date, update_log FROM mirae8440.ceiling WHERE num = ? ");
$stmt->execute([$num]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$eunsungLaserDate = $row['eunsung_laser_date'] ?? '';
$lclaserDate = $row['lclaser_date'] ?? '';
$update_log = $row['update_log'] ?? '';

// 현재 날짜 가져오기
$currentDate = date("Y-m-d");

$eunsungLaserDate = ($eunsungLaserDate === '0000-00-00') ? '' : $eunsungLaserDate;
$lclaserDate = ($lclaserDate === '0000-00-00') ? '' : $lclaserDate;	
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>레이저 작업완료 기록</title>

    <!-- 외부 라이브러리 CSS -->
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-pagination/latest/tui-pagination.css" />
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

    <!-- 외부 라이브러리 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://uicdn.toast.com/tui-pagination/latest/tui-pagination.js"></script>
    <script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-center">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">현재 재고 수량 알림</h4>
            </div>
            <div class="modal-body">
                <div class="row gx-4 gx-lg-4 align-items-center">
                    <div id="alertmsg" class="fs-3"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closeModalBtn" type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- Main Section -->
<section class="d-flex flex-column align-items-left flex-md-row p-1">
    <div class="p-2 pt-md-3 pb-md-3 text-left" style="width:100%;">	  
        <form id="mainFrm" method="post" enctype="multipart/form-data">
            <input type="hidden" id="num" name="num" value="<?= $num ?>">
            <input type="hidden" id="update_log" name="update_log" value="<?= $update_log ?>">
            <div class="card-header">                                
                <span class="text-primary">본천장 Laser 작업일: <?= !empty($eunsungLaserDate) ? $eunsungLaserDate : '' ?></span>
                <br>
                <span class="text-danger">LC Laser 작업일: <?= !empty($lclaserDate) ? $lclaserDate : '' ?></span>
            </div>
            <div class="card-header">
                <div class="input-group p-2 mb-1">							 
                    <label><input type="checkbox" name="checkList" value="1" <?= !empty($eunsungLaserDate) ? 'checked' : '' ?>> 본천장 완료 &nbsp;&nbsp;</label>
                    <label><input type="checkbox" name="checkList" value="2" <?= !empty($lclaserDate) ? 'checked' : '' ?>> LC 완료 &nbsp;&nbsp;</label>
                    <label><input type="checkbox" name="checkList" value="3"> 인테리어 완료 &nbsp;&nbsp;</label>
                </div>
            </div>
            <div class="input-group p-2 mb-1">							 
                <button type="button" id="saveBtn" class="btn btn-secondary">기록하기</button> &nbsp;								    						
                <button type="button" id="closeBtn" class="btn btn-outline-secondary">창닫기</button> &nbsp;								    						
            </div>
            <div id="tmpdiv"></div>
        </form>
    </div>
</section>

<script> 
$(document).ready(function() {
    // 기록하기 버튼 클릭 시
    $("#saveBtn").click(function() {  
        var checkarr = [];
        var bonDoneDate = "<?= $eunsungLaserDate ?>";  // 본천장 작업일
        var lcDoneDate = "<?= $lclaserDate ?>";        // LC 작업일

        // 체크된 항목 배열 생성
        $('input:checkbox[name=checkList]:checked').each(function() {
            checkarr.push($(this).val());
        });

        // 부모창에 값 전달
        if (window.opener) {
            if ($('input:checkbox[name=checkList][value="1"]').is(":checked")) {
                window.opener.document.getElementById("bonDone").value = bonDoneDate || "<?= $currentDate ?>";
            }
			else {            
                window.opener.document.getElementById("bonDone").value = '' ;
            }
			
            if ($('input:checkbox[name=checkList][value="2"]').is(":checked")) {
                window.opener.document.getElementById("lcDone").value = lcDoneDate || "<?= $currentDate ?>";
            }			
			else {            
                window.opener.document.getElementById("lcDone").value = '' ;
            }
        }

        // 서버에 데이터 전송
        $.ajax({
            url: "record_process.php",
            type: "post",
            data: {
                num: $('#num').val(),
                update_log: $('#update_log').val(),
                checkarr: checkarr
            },
            success: function(data) {
                console.log(data);
                alertmodal('자료를 저장합니다.', 1500);
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }     
        });
    });

    // 창닫기 버튼 클릭 시
    $("#closeBtn").click(function() {  
        window.close();
    });

    // 모달 알림 함수
    function alertmodal(message, delay) {	
        $('#alertmsg').html(message); 			  
        $('#myModal').modal('show'); 	
        setTimeout(function() {
            $('#myModal').modal('hide');  
            window.close();
        }, delay);	
    }	
});
</script>

</body>
</html>
