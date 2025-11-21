<?php
require_once __DIR__ . '/bootstrap.php';

// 이미 로그인된 경우 바로 관리자 페이지로 이동
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header("Location: case_manager.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['uid'] ?? '';
    $upw = $_POST['upw'] ?? '';

    if (empty($uid) || empty($upw)) {
        $error = '아이디와 비밀번호를 입력해주세요.';
    } else {
        require_once(includePath('lib/mydb.php'));
        $pdo = db_connect();
        $DB = $_SESSION['DB'] ?? 'mirae8440';
        
        try {
            $sql = "SELECT * FROM {$DB}.member WHERE id = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $uid, PDO::PARAM_STR);
            $stmh->execute();
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            
            if ($row && $upw === $row['pass']) {
                // 로그인 성공 - 관리자 권한 체크 (level 5 이하만 접근 가능)
                if (isset($row['level']) && $row['level'] <= 5) {
                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_userid'] = $row['id'];
                    $_SESSION['manager_name'] = $row['name'];
                    $_SESSION['manager_level'] = $row['level'];
                    header("Location: case_manager.php");
                    exit;
                } else {
                    $error = '관리자 권한이 없습니다.';
                }
            } else {
                $error = '아이디 또는 비밀번호가 일치하지 않습니다.';
            }
        } catch (PDOException $e) {
            error_log("로그인 오류: " . $e->getMessage());
            $error = '로그인 처리 중 오류가 발생했습니다.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>시공사례 관리자 로그인</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3 class="login-title">시공사례 관리자</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="uid" class="form-label">아이디</label>
                <input type="text" class="form-control" id="uid" name="uid" required autofocus>
            </div>
            <div class="mb-3">
                <label for="upw" class="form-label">비밀번호</label>
                <input type="password" class="form-control" id="upw" name="upw" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">로그인</button>
                <a href="index.php" class="btn btn-outline-secondary">메인으로 돌아가기</a>
            </div>
        </form>
    </div>
</body>
</html>
