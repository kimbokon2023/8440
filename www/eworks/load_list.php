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

// 배열 초기화
$num_arr = array();
$e_title_arr = array();
$contents_arr = array();
$registdate_arr = array();
$status_arr = array();
$e_line_arr = array();
$e_line_id_arr = array();
$e_confirm_arr = array();
$r_line_arr = array();
$r_line_id_arr = array();
$recordtime_arr = array();
$author_arr = array();
$author_id_arr = array();
$done_arr = array();

// _row.php에서 사용되는 변수들 초기화
$e_num = '';
$e_title = '';
$contents = '';
$registdate = '';
$status = '';
$e_line = '';
$e_line_id = '';
$e_confirm = '';
$r_line = '';
$r_line_id = '';
$recordtime = '';
$author = '';
$author_id = '';
$done = '';
	
try {
    $sql = "SELECT * FROM {$DB}.eworks WHERE is_deleted IS NULL";
    $stmh = $pdo->prepare($sql);       
    $stmh->execute();
    $count = $stmh->rowCount();              
    
    if ($count < 1) {  
        // 검색결과가 없습니다.
    } else {      
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include includePath('eworks/_row.php');
            
            array_push($num_arr, $e_num);
            array_push($e_title_arr, $e_title);
            array_push($contents_arr, $contents);
            array_push($registdate_arr, $registdate);
            array_push($status_arr, $status);
            array_push($e_line_arr, $e_line);
            array_push($e_line_id_arr, $e_line_id);
            array_push($e_confirm_arr, $e_confirm);
            array_push($r_line_arr, $r_line);
            array_push($r_line_id_arr, $r_line_id);
            array_push($recordtime_arr, $recordtime);
            array_push($author_arr, $author);
            array_push($author_id_arr, $author_id);
            array_push($done_arr, $done);
        }
    }
} catch (PDOException $ex) {
    error_log("Database error in load_list.php: " . $ex->getMessage());
    
    // 오류 발생 시 빈 배열로 초기화
    $num_arr = array();
    $e_title_arr = array();
    $contents_arr = array();
    $registdate_arr = array();
    $status_arr = array();
    $e_line_arr = array();
    $e_line_id_arr = array();
    $e_confirm_arr = array();
    $r_line_arr = array();
    $r_line_id_arr = array();
    $recordtime_arr = array();
    $author_arr = array();
    $author_id_arr = array();
    $done_arr = array();
}
 
 
// 각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
    "num_arr" => $num_arr, 
    "e_title_arr" => $e_title_arr, 
    "contents_arr" => $contents_arr, 
    "registdate_arr" => $registdate_arr, 
    "status_arr" => $status_arr, 
    "e_line_arr" => $e_line_arr, 
    "e_line_id_arr" => $e_line_id_arr, 
    "e_confirm_arr" => $e_confirm_arr, 
    "r_line_arr" => $r_line_arr, 
    "r_line_id_arr" => $r_line_id_arr, 
    "recordtime_arr" => $recordtime_arr, 
    "author_arr" => $author_arr, 	
    "author_id_arr" => $author_id_arr, 	
    "done_arr" => $done_arr,
    "success" => true,
    "count" => count($num_arr)
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
