<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php"); 
    exit;
}   

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';

$title_message = '사업자등록번호 진위여부 확인';
   
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title> <?= $title_message ?> </title>    
</head>
<body>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

    <div class="container">
        <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">       
            <span class="text-center fs-5"> <?= $title_message ?> </span>
            <button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  
            <small class="ms-5 text-muted"> 사업자 등록번호를 입력하고 정상 사업자번호인지 검색합니다. </small>  
        </div>
        <form name="frm1" method="post">
            <div class="form-group row">                
                <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">       
                    <label for="code1" class="col-form-label fs-5 me-2">사업자등록번호</label>                    
                    <input type="text" name="code1" id="code1" class="form-control d-inline-block w-auto mx-1 fs-5" value="" size="3" maxlength="3" alt="사업자등록번호1" oninput="validateNumber(this)">
                    -
                    <input type="text" name="code2" id="code2" class="form-control d-inline-block w-auto mx-1 fs-5" value="" size="2" maxlength="2" alt="사업자등록번호2" oninput="validateNumber(this)">
                    -
                    <input type="text" name="code3" id="code3" class="form-control d-inline-block w-auto mx-1 fs-5" value="" size="5" maxlength="5" alt="사업자등록번호3" oninput="validateNumber(this)">                
                    <input type="hidden" name="code" value="">
                    <input type="hidden" name="overlap_code_ok" value="">
                    <button type="button" class="btn btn-primary btn-sm" onclick="code_check();">확인</button>                    
                </div>            
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">사업자등록번호 진위여부 확인 결과</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>                    
                </div>
                <div class="modal-body" id="resultContent">
                    <!-- 결과 내용이 여기에 표시됩니다 -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="badge bg-dark btn-lg close" data-dismiss="modal">&times;</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">오류</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="errorContent">
                    <!-- 오류 내용이 여기에 표시됩니다 -->
                </div>
                <div class="modal-footer">
                   <button type="button" class="badge bg-dark btn-lg close" data-dismiss="modal">&times;</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        function checkInput_null(form, fields) {
            var fieldArr = fields.split(',');
            for (var i = 0; i < fieldArr.length; i++) {
                var field = form[fieldArr[i]];
                if (field.value.trim() === "") {
                    alert(field.alt + " 필드를 채워주세요.");
                    field.focus();
                    return false;
                }
            }
            return true;
        }

        function code_check() {
            var frm = document.frm1;
            if (!checkInput_null(frm, 'code1,code2,code3')) {
                frm.overlap_code_ok.value = "";
            } else if (frm.code1.value.length !== 3 || frm.code2.value.length !== 2 || frm.code3.value.length !== 5) {
                displayError('사업자 번호 형식이 맞지 않습니다. 다시 입력해주세요.');
            } else {
                frm.code.value = frm.code1.value + frm.code2.value + frm.code3.value;
                var data = {
                    "b_no": [frm.code.value]
                };
                $.ajax({
                    url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=EFI7Fchltxh8LNyMu%2BUE9GSklj4ZsJqpL1UAYP6S0ci9D7fqJA98RRdxJos8KxwwEr6L9GAuAEB6E9IA1v1j2Q%3D%3D",
                    type: "POST",
                    data: JSON.stringify(data),
                    dataType: "JSON",
                    traditional: true,
                    contentType: "application/json; charset=UTF-8",
                    accept: "application/json",
                    success: function(result) {
                        console.log(result);
                        if (result.match_cnt == "1") {
                            console.log("success");
                            displayResult(result.data[0]);
                        } else {
                            console.log("fail");
                            displayError(result.data[0]["tax_type"]);
                        }
                    },
                    error: function(result) {
                        console.log("error");
                        displayError('오류가 발생했습니다. 다시 시도해주세요.');
                    }
                });
            }
        }

        function displayResult(data) {
            var content = '<h4>사업자번호: ' + data.b_no + '</h4> <br>';
            content += '<h4>상태: ' + data.b_stt + '</h4><br>';
            content += '<h4>부가가치세 유형: ' + data.tax_type + '</h4>';
            $('#resultContent').html(content);
            $('#resultModal').modal('show');
        }

        function displayError(message) {
            $('#errorContent').html('<h4>' + message + '</h4>');
            $('#errorModal').modal('show');
        }

        $(document).ready(function() {
            var loader = document.getElementById('loadingOverlay');
            loader.style.display = 'none';
        });

        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $("#resultModal").modal("hide");
            $("#errorModal").modal("hide");
        });
    </script>
</body>
</html>
