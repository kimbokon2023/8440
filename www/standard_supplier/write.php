<?php
require_once __DIR__ . '/../bootstrap.php';

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$WebSite = $is_local ? 'http://localhost/' : 'http://8440.co.kr/';

// Initialize session variables with null safety
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

$menu = $_REQUEST["menu"] ?? '';
$title_message = '공급업체';

// 권한 체크
if(!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// Initialize request variables with null safety
$search = $_REQUEST["search"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$text1 = $_REQUEST["text1"] ?? '';
$text2 = $_REQUEST["text2"] ?? '';
$text3 = $_REQUEST["text3"] ?? '';
$company = $_REQUEST["company"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

require_once("../lib/mydb.php");
$pdo = db_connect();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title_message?> 관리</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .main-container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
            border: none;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h4 i {
            font-size: 1.5rem;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-group-custom {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .input-group-custom {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .required-mark {
            color: #dc3545;
            margin-left: 4px;
        }

        /* 반응형 디자인 */
        @media (max-width: 576px) {
            .card-body {
                padding: 20px;
            }

            .btn-group-custom {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="bi bi-building"></i>
                    <?=$title_message?> 관리
                </h4>
            </div>

            <div class="card-body">
                <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
                    <!-- Hidden Fields -->
                    <input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">
                    <input type="hidden" id="num" name="num" value="<?=$num?>">
                    <input type="hidden" id="page" name="page" value="<?=$page?>">
                    <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
                    <input type="hidden" id="text1" name="text1" value="<?=$text1?>">
                    <input type="hidden" id="text2" name="text2" value="<?=$text2?>">
                    <input type="hidden" id="text3" name="text3" value="<?=$text3?>">

                    <!-- 공급업체명 입력 -->
                    <div class="mb-4">
                        <label for="company" class="form-label">
                            <?=$title_message?>명
                            <span class="required-mark">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="company"
                            name="company"
                            value="<?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?>"
                            placeholder="<?=$title_message?>명을 입력하세요"
                            required
                            autofocus
                        >
                        <div class="invalid-feedback">
                            <?=$title_message?>명을 입력해주세요.
                        </div>
                    </div>

                    <!-- 버튼 그룹 -->
                    <div class="btn-group-custom">
                        <button type="button" id="saveBtn" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-circle"></i>
                            저장
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="self.close();">
                            <i class="bi bi-x-circle"></i>
                            닫기
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
// ESC 키로 팝업 닫기
$(document).keydown(function(e) {
    var code = e.keyCode || e.which;
    if (code == 27) { // ESC 키
        self.close();
    }
});

$(document).ready(function() {

    // 폼 검증
    const form = document.getElementById('board_form');
    const companyInput = document.getElementById('company');

    // 저장 버튼 클릭
    $("#saveBtn").on("click", function() {
        // 입력값 검증
        if (!companyInput.value.trim()) {
            companyInput.classList.add('is-invalid');
            companyInput.focus();

            Toastify({
                text: "<?=$title_message?>명을 입력해주세요.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "#dc3545",
            }).showToast();

            return;
        }

        companyInput.classList.remove('is-invalid');
        $("#SelectWork").val("insert");

        // 저장 중 버튼 비활성화
        $("#saveBtn").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>저장 중...');

        $.ajax({
            url: "process.php",
            type: "post",
            data: $("#board_form").serialize(),
            success: function(data) {
                console.log(data);

                Toastify({
                    text: "✓ 저장이 완료되었습니다.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();

                // 부모창 업데이트
                if (opener && !opener.closed) {
                    $("#search", opener.document).val($("#company").val());
                    $(opener.location).attr("href", "javascript:reloadlist();");
                }

                // 1.5초 후 창 닫기
                setTimeout(function() {
                    self.close();
                }, 1500);
            },
            error: function(jqxhr, status, error) {
                console.error(jqxhr, status, error);

                // 버튼 다시 활성화
                $("#saveBtn").prop('disabled', false).html('<i class="bi bi-check-circle"></i> 저장');

                Toastify({
                    text: "✗ 저장 중 오류가 발생했습니다.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#dc3545",
                }).showToast();
            }
        });
    });

    // Enter 키로 저장
    $("#company").on("keypress", function(e) {
        if (e.which == 13) { // Enter 키
            e.preventDefault();
            $("#saveBtn").click();
        }
    });

    // 입력 시 에러 표시 제거
    $("#company").on("input", function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
</body>
</html>
