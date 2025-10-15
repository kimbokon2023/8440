<?php
/**
 * 회원가입 처리 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$WebSite = $protocol . "://" . $host;

// 요청 변수 안전하게 초기화
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
$pass = isset($_REQUEST["pass"]) ? $_REQUEST["pass"] : '';
$name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
$nick = isset($_REQUEST["nick"]) ? $_REQUEST["nick"] : '';

// 입력값 검증
if (empty($id) || empty($pass) || empty($name) || empty($nick)) {
    echo "<script>
        alert('모든 필수 항목을 입력해주세요.');
        history.back();
    </script>";
    exit;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 회원가입 처리
try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO mirae8440.member(id, pass, name, nick, regist_day, level) 
            VALUES (?, ?, ?, ?, NOW(), 9)";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $id, PDO::PARAM_STR);
    $stmh->bindValue(2, $pass, PDO::PARAM_STR);
    $stmh->bindValue(3, $name, PDO::PARAM_STR);
    $stmh->bindValue(4, $nick, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    
    // 성공 메시지 및 리다이렉션
    echo "<script>
        alert('회원가입이 완료되었습니다.\\n로그인 페이지로 이동합니다.');
        location.href = '{$WebSite}/login/login_form.php';
    </script>";
    exit;
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("회원가입 오류 (id: {$id}): " . $ex->getMessage());
    
    // 에러 메시지 표시
    echo "<script>
        alert('회원가입 중 오류가 발생했습니다.\\n이미 존재하는 아이디이거나 서버 오류일 수 있습니다.');
        history.back();
    </script>";
    exit;
}
?>
