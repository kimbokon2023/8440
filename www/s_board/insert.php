<?php
/**
 * s_board 게시글 삽입/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';
$userid = $_SESSION["userid"] ?? '';
$name = $_SESSION["name"] ?? '';
$nick = $_SESSION["nick"] ?? '';

// 요청 변수 초기화
$timekey = $_REQUEST["timekey"] ?? '';   // 신규데이터에 생성할때 임시저장키
$mode = $_REQUEST["mode"] ?? '';  // modify_form에서 호출할 경우
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$is_html = $_REQUEST["is_html"] ?? '';  // checkbox는 체크해야 변수명 전달됨
$noticecheck = $_REQUEST["noticecheck"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';
$division = $_REQUEST["division"] ?? '';
$searchtext = $_REQUEST["searchtext"] ?? '';
    
// 수정 모드
if ($mode == "modify") {
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        error_log("게시글 조회 오류: " . $Exception->getMessage());
        
        echo json_encode(array(
            "success" => false,
            "message" => "게시글 조회 중 오류가 발생했습니다."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }

    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . "." . $tablename . " set subject = ?, content = ?, is_html = ?, division = ?, searchtext = ? where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $division, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchtext, PDO::PARAM_STR);
        $stmh->bindValue(6, $num, PDO::PARAM_INT);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        
        error_log("게시글 수정 오류: " . $Exception->getMessage());
        
        echo json_encode(array(
            "success" => false,
            "message" => "게시글 수정 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }                         

} else {
    // 신규 등록 모드
    if ($is_html == "y") {
        $content = htmlspecialchars($content);
    }
    
    try {
        $pdo->beginTransaction();
        $sql = "insert into " . $DB . "." . $tablename . " (id, name, nick, subject, content, regist_day, hit, is_html, division, searchtext) ";
        $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $userid, PDO::PARAM_STR);
        $stmh->bindValue(2, $name, PDO::PARAM_STR);
        $stmh->bindValue(3, $nick, PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $division, PDO::PARAM_STR);
        $stmh->bindValue(8, $searchtext, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        
        error_log("게시글 등록 오류: " . $Exception->getMessage());
        
        echo json_encode(array(
            "success" => false,
            "message" => "게시글 등록 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
}   
 

// 신규 등록인 경우 마지막 번호 추출
if ($mode !== "modify") {
    $sql = "select * from " . $DB . "." . $tablename . " order by num desc limit 1";
    
    try {
        $stmh = $pdo->query($sql);
        $rowNum = $stmh->rowCount();
        
        if ($rowNum > 0) {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            $num = $row["num"] ?? '';
        }
    } catch (PDOException $Exception) {
        error_log("마지막 번호 조회 오류: " . $Exception->getMessage());
    }

    // 첨부파일의 parentnum 업데이트
    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . ".picuploads set parentnum = ? where parentnum = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("첨부파일 업데이트 오류: " . $Exception->getMessage());
    }
}

// 성공 응답
$data = array(
    'success' => true,
    'num' => $num,
    'tablename' => $tablename,
    'message' => ($mode == "modify") ? "게시글이 수정되었습니다." : "게시글이 등록되었습니다."
);

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>


