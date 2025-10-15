<?php
/**
 * 사용자 정보 수정 폼 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 요청 변수 초기화
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';

// 변수 초기화
$name = '';
$hp = array('', '', '');
$hp2 = '';
$hp3 = '';
$email = array('', '');
$email1 = '';
$email2 = '';

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 회원 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.member WHERE id = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $id, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        echo "<script>
            alert('검색결과가 없습니다.');
            history.back();
        </script>";
        exit;
    }
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $id = $row["id"] ?? '';
        $name = $row["name"] ?? '';
        
        // 전화번호 분리 (안전하게)
        $hp_full = $row["hp"] ?? '';
        if (!empty($hp_full)) {
            $hp = explode("-", $hp_full);
            $hp2 = $hp[1] ?? '';
            $hp3 = $hp[2] ?? '';
        }
        
        // 이메일 분리 (안전하게)
        $email_full = $row["email"] ?? '';
        if (!empty($email_full)) {
            $email = explode("@", $email_full);
            $email1 = $email[0] ?? '';
            $email2 = $email[1] ?? '';
        }
    }
    
} catch (PDOException $ex) {
    error_log("회원 정보 조회 오류 (id: {$id}): " . $ex->getMessage());
    echo "<script>
        alert('회원 정보를 불러오는 중 오류가 발생했습니다.');
        history.back();
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 정보 수정</title>
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex mt-5 mb-5 justify-content-center">
            <div class="card align-middle justify-content-center align-items-center" style="width:25rem; border-radius:20px;">
                <div class="card-title" style="margin-top:30px;">
                    <h3 class="card-title text-center" style="color:#113366;">사용자 정보 수정</h3>
                </div>
                
                <div class="card-body">
                    <form class="form-signin" name="member_form" method="get" action="../member/updatePro.php?id=<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
                        <h5 class="form-signin-heading">수정할 비밀번호를 입력하세요</h5>
                        <h4 class="form-signin-heading">사용자 ID: <?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></h4>
                        <h4 class="form-signin-heading">사용자 이름: <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h4>
                        
                        <label for="inputPassword" class="sr-only">수정할 Password</label>
                        <input type="password" name="pass" class="form-control" placeholder="수정할 Password" required autofocus>
                        <br>
                        
                        <label for="inputPassword" class="sr-only">Password 확인</label>
                        <input type="password" name="pass_confirm" class="form-control" placeholder="Password 확인" required>
                        <br>
                        
                        <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" size="5">
                        <input type="hidden" id="name" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" size="5">
                        
                        <button type="button" id="btn-Yes" class="form-control mt-2 mb-2 btn btn-primary btn-block" onclick="check_input();">정보수정 완료</button>
                        <button type="button" class="form-control btn btn-secondary btn-block" onclick="history.back(-1);">이전화면으로 돌아가기</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
(function() {
    'use strict';
    
    /**
     * 아이디 중복 확인
     */
    window.check_id = function() {
        var form = document.member_form;
        if (!form || !form.id) return;
        
        var id = form.id.value;
        window.open(
            "check_id.php?id=" + encodeURIComponent(id),
            "IDcheck",
            "left=200, top=200, width=250, height=80, scrollbars=no, resizable=yes"
        );
    };
    
    /**
     * 닉네임 중복 확인
     */
    window.check_nick = function() {
        var form = document.member_form;
        if (!form || !form.nick) return;
        
        var nick = form.nick.value;
        window.open(
            "check_nick.php?nick=" + encodeURIComponent(nick),
            "NICKcheck",
            "left=200, top=200, width=250, height=80, scrollbars=no, resizable=yes"
        );
    };
    
    /**
     * 입력값 검증
     */
    window.check_input = function() {
        var form = document.member_form;
        if (!form) return;
        
        if (form.pass.value == '') {
            alert("수정할 password가 비어있습니다.\n다시 입력해주세요.");
            form.pass.focus();
            form.pass.select();
            return;
        }
        
        if (form.pass.value != form.pass_confirm.value) {
            alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");
            form.pass.focus();
            form.pass.select();
            return;
        }
        
        form.submit();
    };
    
})();
</script>
</html>
