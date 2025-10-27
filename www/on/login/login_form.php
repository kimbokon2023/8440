<?php
session_start();

// 이미 로그인한 경우 메인 페이지로 리다이렉트
if (isset($_SESSION['daon_userid']) && !empty($_SESSION['daon_userid'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">

    <title>다온텍 로그인</title>

    <style>
        html, body {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 15px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 15px;
        }

        .btn-login {
            border-radius: 10px;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            font-weight: 600;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card login-card">
                    <div class="login-header text-center">
                        <h3>🏭 다온텍 발주 시스템</h3>
                        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Ordering Management System</p>
                    </div>
                    <div class="card-body">
                        <form class="form-signin" method="POST" action="login_result.php">
                            <h5 class="text-center mb-4" style="color: #333;">로그인 정보를 입력하세요</h5>

                            <label for="uid" class="sr-only">아이디</label>
                            <input type="text" id="uid" name="uid" class="form-control" placeholder="아이디" required autofocus>

                            <label for="upw" class="sr-only">비밀번호</label>
                            <input type="password" id="upw" name="upw" class="form-control" placeholder="비밀번호" required>

                            <button class="btn btn-lg btn-primary btn-block btn-login" type="submit">
                                🔐 로그인
                            </button>

                            <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php
                                if ($_GET['error'] == 'invalid') {
                                    echo '아이디 또는 비밀번호가 올바르지 않습니다.';
                                } elseif ($_GET['error'] == 'inactive') {
                                    echo '비활성화된 계정입니다. 관리자에게 문의하세요.';
                                } else {
                                    echo '로그인에 실패했습니다.';
                                }
                                ?>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="card-footer text-center text-muted" style="background: transparent; border: none;">
                        <small>© 2025 다온텍. All rights reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
