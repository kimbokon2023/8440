<?php
/**
 * 게시글 등록/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $website = $_SESSION["WebSite"] ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    header("Location: {$website}login/login_form.php");
    exit;
}

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$userid = $_SESSION["userid"] ?? '';
$username = $_SESSION["name"] ?? '';
$usernick = $_SESSION["nick"] ?? '';

// 요청 변수 초기화
$timekey = isset($_REQUEST["timekey"]) ? $_REQUEST["timekey"] : '';
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : '';
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$is_html = isset($_REQUEST["is_html"]) ? $_REQUEST["is_html"] : '';
$noticecheck = isset($_REQUEST["noticecheck"]) ? $_REQUEST["noticecheck"] : '';
$subject = isset($_REQUEST["subject"]) ? $_REQUEST["subject"] : '';
$content = isset($_REQUEST["content"]) ? $_REQUEST["content"] : '';
$searchtext = isset($_REQUEST["searchtext"]) ? $_REQUEST["searchtext"] : '';

// 파일 관련 변수 초기화
$upfile_name = ['', '', ''];
$copied_file_name = ['', '', ''];

// 필수 파라미터 검증
if (empty($tablename)) {
    error_log("게시글 등록/수정 실패: tablename이 비어있음");
    echo json_encode([
        'success' => false,
        'message' => '테이블명이 지정되지 않았습니다.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($subject)) {
    error_log("게시글 등록/수정 실패: subject가 비어있음");
    echo json_encode([
        'success' => false,
        'message' => '제목을 입력해주세요.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($content)) {
    error_log("게시글 등록/수정 실패: content가 비어있음");
    echo json_encode([
        'success' => false,
        'message' => '내용을 입력해주세요.',
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once("../lib/mydb.php");
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        // 기존 데이터 조회
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            error_log("게시글 수정 실패: 게시글을 찾을 수 없음 (num: {$num})");
            echo json_encode([
                'success' => false,
                'message' => '수정할 게시글을 찾을 수 없습니다.',
                'num' => $num
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    } catch (PDOException $ex) {
        error_log("게시글 조회 오류 (num: {$num}): " . $ex->getMessage());
        echo json_encode([
            'success' => false,
            'message' => '게시글 조회 중 오류가 발생했습니다.',
            'error' => $ex->getMessage(),
            'num' => $num
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} SET subject = ?, content = ?, is_html = ?, noticecheck = ?, searchtext = ? WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $noticecheck, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(6, $num, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => '게시글이 성공적으로 수정되었습니다.',
            'num' => $num,
            'tablename' => $tablename
        ], JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("게시글 수정 오류 (num: {$num}): " . $ex->getMessage());
        echo json_encode([
            'success' => false,
            'message' => '게시글 수정 중 오류가 발생했습니다.',
            'error' => $ex->getMessage(),
            'num' => $num
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 신규 등록 모드
else {
    // HTML 이스케이프 처리
    if ($is_html == "y") {
        $content = htmlspecialchars($content);
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.{$tablename} (id, name, nick, subject, content, regist_day, hit, is_html, ";
        $sql .= "file_name_0, file_name_1, file_name_2, file_copied_0, file_copied_1, file_copied_2, noticecheck, searchtext) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, NOW(), 0, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $userid, PDO::PARAM_STR);
        $stmh->bindValue(2, $username, PDO::PARAM_STR);
        $stmh->bindValue(3, $usernick, PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $upfile_name[0], PDO::PARAM_STR);
        $stmh->bindValue(8, $upfile_name[1], PDO::PARAM_STR);
        $stmh->bindValue(9, $upfile_name[2], PDO::PARAM_STR);
        $stmh->bindValue(10, $copied_file_name[0], PDO::PARAM_STR);
        $stmh->bindValue(11, $copied_file_name[1], PDO::PARAM_STR);
        $stmh->bindValue(12, $copied_file_name[2], PDO::PARAM_STR);
        $stmh->bindValue(13, $noticecheck, PDO::PARAM_STR);
        $stmh->bindValue(14, $searchtext, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("게시글 등록 오류: " . $ex->getMessage());
        echo json_encode([
            'success' => false,
            'message' => '게시글 등록 중 오류가 발생했습니다.',
            'error' => $ex->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 신규 게시글 번호 추출
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename} ORDER BY num DESC LIMIT 1";
        $stmh = $pdo->query($sql);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"];
        
    } catch (PDOException $ex) {
        error_log("게시글 번호 조회 오류: " . $ex->getMessage());
        echo json_encode([
            'success' => false,
            'message' => '게시글 번호 조회 중 오류가 발생했습니다.',
            'error' => $ex->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 임시 첨부파일을 실제 게시글에 연결
    if (!empty($timekey)) {
        try {
            $pdo->beginTransaction();
            
            $sql = "UPDATE {$DB}.picuploads SET parentnum = ? WHERE parentnum = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $num, PDO::PARAM_STR);
            $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
            $stmh->execute();
            
            $pdo->commit();
            
        } catch (PDOException $ex) {
            $pdo->rollBack();
            error_log("첨부파일 연결 오류 (num: {$num}, timekey: {$timekey}): " . $ex->getMessage());
            // 첨부파일 연결 실패해도 게시글은 등록됨
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => '게시글이 성공적으로 등록되었습니다.',
        'num' => $num,
        'tablename' => $tablename
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
