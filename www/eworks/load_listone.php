<?php
// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 변수 초기화
$e_num = $_REQUEST["e_num"] ?? "";
$DB = $_SESSION['DB'] ?? 'mirae8440';
$pdo = null;

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// _row.php에서 사용되는 변수들 초기화
$eworks_item = '일반';
$e_title = '';
$contents = '';
$registdate = '';
$status = '';
$e_line = '';
$e_line_id = '';
$e_confirm = '';
$e_confirm_id = '';
$r_line = '';
$r_line_id = '';
$recordtime = '';
$author = '';
$author_id = '';
$done = '';

try {
    // SQL Injection 방지
    $e_num_safe = str_replace("'", "''", $e_num);
    
    $sql = "SELECT * FROM {$DB}.eworks WHERE num = '{$e_num_safe}' AND is_deleted IS NULL";
    $stmh = $pdo->prepare($sql);       
    $stmh->execute();
    $count = $stmh->rowCount();         	  
    
    if ($count < 1) {  
        // 검색결과가 없습니다.
        $eworks_item = '일반';
    } else {      
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include includePath('eworks/_row.php');		
            if ($eworks_item === '연차') {
                $contents = urldecode($contents);
            }
        }
    }
} catch (PDOException $ex) {
    error_log("Database error in load_listone.php: " . $ex->getMessage());
    
    // 오류 발생 시 기본값 설정
    $eworks_item = '일반';
    $e_title = '';
    $contents = '';
    $registdate = '';
    $status = '';
    $e_line = '';
    $e_line_id = '';
    $e_confirm = '';
    $e_confirm_id = '';
    $r_line = '';
    $r_line_id = '';
    $recordtime = '';
    $author = '';
    $author_id = '';
    $done = '';
}
 
// 각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "e_num" => $e_num, 
    "eworks_item" => $eworks_item, 
    "e_title" => $e_title, 
    "contents" => $contents, 
    "registdate" => $registdate, 
    "status" => $status, 
    "e_line" => $e_line, 
    "e_line_id" => $e_line_id, 
    "e_confirm" => $e_confirm, 
    "e_confirm_id" => $e_confirm_id, 
    "r_line" => $r_line, 
    "r_line_id" => $r_line_id, 
    "recordtime" => $recordtime, 
    "author" => $author, 	
    "author_id" => $author_id, 	
    "done" => $done,
    "success" => true,
    "found" => ($count > 0)
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
