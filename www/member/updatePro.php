<?php
/**
 * 사용자 정보 수정 처리 페이지
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
$regist_day = date("Y-m-d H:i:s");

// 입력값 검증
if (empty($id) || empty($pass)) {
    echo "<script>
        alert('필수 항목을 입력해주세요.');
        history.back();
    </script>";
    exit;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 회원 정보 수정
try {
    $pdo->beginTransaction();
    
    $sql = "UPDATE mirae8440.member 
            SET pass = ?, name = ?, regist_day = ? 
            WHERE id = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $pass, PDO::PARAM_STR);
    $stmh->bindValue(2, $name, PDO::PARAM_STR);
    $stmh->bindValue(3, $regist_day, PDO::PARAM_STR);
    $stmh->bindValue(4, $id, PDO::PARAM_STR);
    
    $stmh->execute();
    $pdo->commit();
    
    // 성공 메시지 및 리다이렉션
    echo "<script>
        alert('회원 정보가 수정되었습니다.');
        location.href = '{$WebSite}/index.php';
    </script>";
    exit;
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("회원 정보 수정 오류 (id: {$id}): " . $ex->getMessage());
    
    echo "<script>
        alert('회원 정보 수정 중 오류가 발생했습니다.');
        history.back();
    </script>";
    exit;
}
?>
