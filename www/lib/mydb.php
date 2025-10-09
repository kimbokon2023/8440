<?php
/**
 * 데이터베이스 연결 함수
 * 환경별 설정을 자동으로 적용하여 DB 연결
 */

require_once __DIR__ . '/../config/environment.php';

function db_connect(?string $db_name = null): PDO
{
    // 환경별 데이터베이스 설정 가져오기
    $config = getDatabaseConfig();
    
    // 세션 DB 이름 우선 사용(없으면 환경별 기본값)
    if ($db_name === null && session_status() === PHP_SESSION_ACTIVE) {
        $db_name = $_SESSION['DB'] ?? $config['name'];
    }
    if ($db_name === null) {
        $db_name = $config['name'];
    }

    // 환경별 설정 적용
    $db_user = $config['user'];
    $db_pass = $config['pass'];
    $db_host = $config['host'];

    // NOTE: dbname (O), db_name (X), 권장: utf8mb4
    $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";

    try {
        return new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // 화면 출력 금지! (PDF 전송 깨짐 방지)
        error_log('[DB CONNECT ERROR] ' . $e->getMessage());
        // 상위에서 처리하도록 예외 전달(또는 null 반환 후 호출부에서 500 처리)
        throw $e;
    }
}
