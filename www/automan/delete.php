<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;

// 권한 체크
if (!isset($_SESSION["level"]) || $level >= 8) {
    echo "<script> alert('관리자 승인이 필요합니다.') </script>";
    sleep(2);
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/login/logout.php");
    } else {
        header("Location: http://8440.co.kr/login/logout.php");
    }
    exit;
}

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    $sql = "delete from mirae8440.automan where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();

    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/automan/list.php");
    } else {
        header("Location: https://8440.co.kr/automan/list.php");
    }
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("Automan 삭제 오류: " . $ex->getMessage());
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/automan/list.php?error=1");
    } else {
        header("Location: https://8440.co.kr/automan/list.php?error=1");
    }
}
?>