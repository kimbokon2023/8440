<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 변수 초기화
$e_num = $_REQUEST["e_num"] ?? "";
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
    
    error_log("=== load_listone.php 실행 ===");
    error_log("e_num: " . $e_num);
    error_log("SQL: " . $sql);
    
    $stmh = $pdo->prepare($sql);       
    $stmh->execute();
    $count = $stmh->rowCount();    
    
    error_log("조회된 행 수: " . $count);
    
    if ($count < 1) {  
        // 검색결과가 없습니다.
        $eworks_item = '일반';
        error_log("검색결과 없음");
    } else {      
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include includePath('eworks/_row.php');		
            
            error_log("DB에서 가져온 값:");
            error_log("- eworks_item: " . $eworks_item);
            error_log("- author: " . $author);
            error_log("- author_id: " . $author_id);
            error_log("- e_line: " . $e_line);
            error_log("- e_line_id: " . $e_line_id);
            error_log("- registdate: " . $registdate);
            
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
