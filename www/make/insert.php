<?php
/**
 * 발주서 등록/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 함수 로드
require_once __DIR__ . '/../common/functions.php';

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 변수 초기화
$mode = getRequestValue("mode", '');
$num = getRequestValue("num", '');
$page = getRequestValue("page", '');
$scale = getRequestValue("scale", '');
$orderdate = getRequestValue("orderdate", '');
$indate = getRequestValue("indate", '');
$text = getRequestValue("textsave", '');
$company = getRequestValue("company", '');

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 성공 여부 플래그
$success = false;

// 수정 모드
if ($mode == 'modify') {
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE mirae8440.make 
                SET orderdate = ?, indate = ?, company = ?, text = ? 
                WHERE num = ? 
                LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $orderdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $indate, PDO::PARAM_STR);
        $stmh->bindValue(3, $company, PDO::PARAM_STR);
        $stmh->bindValue(4, $text, PDO::PARAM_STR);
        $stmh->bindValue(5, $num, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        $success = true;
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("발주서 수정 오류 (num: {$num}): " . $ex->getMessage());
        die("오류: 발주서 수정 중 문제가 발생했습니다.");
    }
}
// 신규 등록 모드
else {
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO mirae8440.make (orderdate, indate, company, text) 
                VALUES (?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $orderdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $indate, PDO::PARAM_STR);
        $stmh->bindValue(3, $company, PDO::PARAM_STR);
        $stmh->bindValue(4, $text, PDO::PARAM_STR);
        
        $stmh->execute();
        
        // 신규 등록 시 생성된 ID 가져오기
        if (empty($num)) {
            $num = $pdo->lastInsertId();
        }
        
        $pdo->commit();
        
        $success = true;
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("발주서 등록 오류: " . $ex->getMessage());
        die("오류: 발주서 등록 중 문제가 발생했습니다.");
    }
}

// 리다이렉트 처리 (로컬/서버 환경 모두 지원)
if ($success) {
    // 안전한 URL 파라미터 생성
    $params = http_build_query([
        'num' => $num,
        'scale' => $scale,
        'page' => $page
    ], '', '&', PHP_QUERY_RFC3986);
    
    // 현재 호스트 기반으로 리다이렉트 URL 생성
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base_path = '/make/';
    
    if ($mode != "modify") {
        // 신규 등록: 상세 페이지로 이동
        $redirect_url = "{$protocol}://{$host}{$base_path}read_DB.php?{$params}";
    } else {
        // 수정: 상세 페이지로 이동
        $redirect_url = "{$protocol}://{$host}{$base_path}view.php?{$params}";
    }
    
    header("Location: {$redirect_url}");
    exit;
}
?>