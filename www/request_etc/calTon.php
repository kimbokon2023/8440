<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 톤(ton) → 매수 수량 계산기
 * 
 * 철판의 무게(톤)를 입력하면 필요한 매수를 자동 계산
 */

// 요청 변수 초기화
$item = $_REQUEST["item"] ?? '';
$spec = $_REQUEST["spec"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$calculate = $_REQUEST["calculate"] ?? '';
$expected = $_REQUEST["expected"] ?? '';
$steelnum = $_REQUEST["steelnum"] ?? '';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>톤으로 수량산출 계산기</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
    </style>
</head>

<body>

<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<section class="d-flex flex-column align-items-left flex-md-row p-1">
    <div class="p-2 pt-md-3 pb-md-3 text-left" style="width:100%;">
        <form id="mainFrm" method="post" enctype="multipart/form-data">
            <input type="hidden" id="SelectWork" name="SelectWork">
            <input type="hidden" id="vacancy" name="vacancy">
            <input type="hidden" id="num" name="num" value="<?= $num ?>">
            <input type="hidden" id="page" name="page" value="<?= $page ?>">
            <input type="hidden" id="calculate" name="calculate" value="<?= $calculate ?>">
            
            <div class="card-header">
                <div class="input-group p-2 mb-1">
                    종 류 : &nbsp; <input name="item" id="item" class="form-control" style="width:200px;" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>"> &nbsp; &nbsp;
                    규 격 : &nbsp; <input name="spec" id="spec" class="form-control" style="width:200px;" value="<?= htmlspecialchars($spec, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="input-group p-2 mb-1">
                    주문톤수(ton) : &nbsp; <input name="expected" id="expected" class="form-control" style="width:150px;" value="<?= htmlspecialchars($expected, ENT_QUOTES, 'UTF-8') ?>"> &nbsp; &nbsp;
                </div>
                
                <div class="input-group p-2 mb-1">
                    계산된 매수 : &nbsp; <input name="steelnum" id="steelnum" class="form-control" style="width:150px;" value="<?= htmlspecialchars($steelnum, ENT_QUOTES, 'UTF-8') ?>" readonly> &nbsp; &nbsp;
                </div>
                
                <div class="input-group p-2 mb-1">
                    <button type="button" id="calBtn" class="btn btn-secondary">계산하기</button> &nbsp;
                    <button type="button" id="returnBtn" class="btn btn-outline-danger">적용하기</button> &nbsp;
                </div>
                
                <div class="input-group justify-content-center p-5 mb-5" id="loading" style="display:none;">
                    <img id="loading-image" src="/img/loading.gif" alt="Loading...">
                </div>
            </div>
            
            <div id="grid"></div>
            <div id="tui-pagination-container" class="tui-pagination"></div>
            <div id="tmpdiv"></div>
        </form>
        
        <form id="Form1" name="Form1">
            <input type="hidden" id="steelcompany" name="steelcompany[]">
        </form>
    </div>
</section>

<script>
$(document).ready(function() {
    // 계산하기 버튼 클릭
    $("#calBtn").click(function() {
        var expected = $('#expected').val();
        var spec = $('#spec').val();
        
        // 입력 값 검증
        if (!expected || !spec) {
            alert('주문톤수와 규격을 입력해주세요.');
            return;
        }
        
        // 규격 분리 (예: 1000*2000*1.2)
        var arr = spec.split('*');
        
        if (arr.length !== 3) {
            alert('규격은 "가로*세로*두께" 형식으로 입력해주세요.\n예: 1000*2000*1.2');
            return;
        }
        
        console.log('규격:', arr);
        
        // 단위 무게 계산 (kg)
        // 철판 밀도 7.93 g/cm³ = 7.93 kg/dm³
        var unit = (Number(arr[0]) * Number(arr[1]) * Number(arr[2]) * 7.93) / 1000000;
        
        // 매수 계산
        var sheet = parseInt(Number(expected) * 1000 / unit);
        
        $("#steelnum").val(sheet);
        
        console.log('단위 무게(kg):', unit);
        console.log('계산된 매수:', sheet);
        
        // 부모창에 자동 적용
        if (window.opener) {
            $("input[name=steelnum]", opener.document).val(sheet);
            $("#comment", opener.document).val(expected + '톤 주문해 주세요');
            
            alert(sheet + '매가 필요합니다.\n부모창에 자동으로 적용되었습니다.');
            window.close();
        }
    });
    
    // 적용하기 버튼 클릭
    $("#returnBtn").click(function() {
        var steelnum = $('#steelnum').val();
        
        if (!steelnum) {
            alert('먼저 계산하기 버튼을 클릭해주세요.');
            return;
        }
        
        // 부모창에 적용
        if (window.opener) {
            $("input[name=steelnum]", opener.document).val(steelnum);
            window.close();
        } else {
            alert('부모창을 찾을 수 없습니다.');
        }
    });
});
</script>

</body>
</html>


