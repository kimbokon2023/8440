<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? "";
$num = $_REQUEST["num"] ?? "";
$search = $_REQUEST["search"] ?? "";
$find = $_REQUEST["find"] ?? "";
$process = $_REQUEST["process"] ?? "전체";
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";

// request.php에서 사용될 변수들 초기화
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? "";
$year = $_REQUEST["year"] ?? "";
$separate_date = $_REQUEST["separate_date"] ?? "";
$outputnum = $_REQUEST["outputnum"] ?? "";

// request.php 포함
include 'request.php';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
     
if ($mode == "modify") {
    // 수정 로그 생성
    $data = date("Y-m-d H:i:s") . " - " . $user_name . "  ";
    $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea
    
    try {
        $sql = "SELECT * FROM {$DB}.cost WHERE num = ?";  // get target record
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log("원자재 발주 조회 오류 (수정): " . $ex->getMessage());
        $pdo->rollBack();
    }
    
    // data 수정 update
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.cost 
                SET which = ?, outdate = ?, indate = ?, outworkplace = ?, item = ?, spec = ?, 
                    steelnum = ?, company = ?, comment = ?, model = ?, first_writer = ?, 
                    update_log = ?, supplier = ?, requestdate = ?, suppliercost = ? 
                WHERE num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $which, PDO::PARAM_STR);
        $stmh->bindValue(2, $outdate, PDO::PARAM_STR);
        $stmh->bindValue(3, $indate, PDO::PARAM_STR);
        $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);
        $stmh->bindValue(5, $item, PDO::PARAM_STR);
        $stmh->bindValue(6, $spec, PDO::PARAM_STR);
        $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);
        $stmh->bindValue(8, $company, PDO::PARAM_STR);
        $stmh->bindValue(9, $comment, PDO::PARAM_STR);
        $stmh->bindValue(10, $model, PDO::PARAM_STR);
        $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(12, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(13, $supplier, PDO::PARAM_STR);
        $stmh->bindValue(14, $requestdate, PDO::PARAM_STR);
        $stmh->bindValue(15, $suppliercost, PDO::PARAM_STR);
        $stmh->bindValue(16, $num, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("원자재 발주 수정 오류: " . $ex->getMessage());
    }                         
       
 } else {
    // 데이터 신규 등록하는 구간
    $first_writer = $user_name . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록
    
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.cost 
                (which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, 
                 model, first_writer, update_log, supplier, requestdate, suppliercost) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $which, PDO::PARAM_STR);
        $stmh->bindValue(2, $outdate, PDO::PARAM_STR);
        $stmh->bindValue(3, $indate, PDO::PARAM_STR);
        $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);
        $stmh->bindValue(5, $item, PDO::PARAM_STR);
        $stmh->bindValue(6, $spec, PDO::PARAM_STR);
        $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);
        $stmh->bindValue(8, $company, PDO::PARAM_STR);
        $stmh->bindValue(9, $comment, PDO::PARAM_STR);
        $stmh->bindValue(10, $model, PDO::PARAM_STR);
        $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(12, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(13, $supplier, PDO::PARAM_STR);
        $stmh->bindValue(14, $requestdate, PDO::PARAM_STR);
        $stmh->bindValue(15, $suppliercost, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("원자재 발주 등록 오류: " . $ex->getMessage());
    }
}

// 로컬/서버 환경에 따른 동적 리다이렉션
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$redirect_params = http_build_query(array(
    'num' => $num,
    'outputnum' => $outputnum,
    'page' => $page,
    'search' => $search,
    'find' => $find,
    'process' => $process,
    'yearcheckbox' => $yearcheckbox,
    'year' => $year,
    'fromdate' => $fromdate,
    'todate' => $todate,
    'separate_date' => $separate_date
));

if ($mode == "not") {
    // 신규가입일때는 read_DB로 이동
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://{$host}/cost/read_DB.php?{$redirect_params}");
    } else {
        header("Location: http://8440.co.kr/cost/read_DB.php?{$redirect_params}");
    }
} else {
    // 수정일때는 view로 이동
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://{$host}/cost/view.php?{$redirect_params}");
    } else {
        header("Location: http://8440.co.kr/cost/view.php?{$redirect_params}");
    }
}

?>