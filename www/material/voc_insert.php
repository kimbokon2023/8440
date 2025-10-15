<?php
/**
 * VOC(협의사항) 등록/수정 처리 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 로그인 체크
if (!isset($_SESSION["userid"])) {
    echo '<script>
        alert("로그인 후 이용해 주세요.");
        history.back();
    </script>';
    exit;
}

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    } elseif (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return $default;
}

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$WebSite = $protocol . "://" . $host;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 알람 설정 업데이트
$a = 1;
$alerts = 1;

try {
    $pdo->beginTransaction();
    $sql = "UPDATE mirae8440.alert SET alert = ? WHERE num = ? LIMIT 1";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $alerts, PDO::PARAM_INT);
    $stmh->bindValue(2, $a, PDO::PARAM_INT);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("알람 설정 오류: " . $ex->getMessage());
    echo "오류: 알람 설정 중 문제가 발생했습니다.";
}

// 요청 변수 초기화
$page = getRequestValue("page", 1);
$mode = getRequestValue("mode", '');
$childnum = getRequestValue("childnum", '');
$parent = getRequestValue("parent", '');
$html_ok = getRequestValue("html_ok", '');
$subject = getRequestValue("workplacename", '');
$content = getRequestValue("content", '');

// 파일 관련 변수 초기화 (파일 업로드 기능이 있을 경우를 대비)
$upfile_name = ['', '', ''];
$copied_file_name = ['', '', ''];

// 파일 업로드 처리가 있다면 여기에 추가
// 예: if (isset($_FILES['upfile'])) { ... }

// 수정 모드
if ($mode == "modify") {
    $is_html = "1";
    
    // 기존 데이터 조회
    try {
        $sql = "SELECT * FROM mirae8440.voc WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $childnum, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        error_log("VOC 조회 오류 (num: {$childnum}): " . $ex->getMessage());
        echo "오류: 데이터를 조회하는 중 문제가 발생했습니다.";
        exit;
    }
    
    // 데이터 업데이트
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE mirae8440.voc SET subject = ?, content = ?, is_html = ? WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $childnum, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("VOC 수정 오류 (num: {$childnum}): " . $ex->getMessage());
        echo "오류: 데이터를 수정하는 중 문제가 발생했습니다.";
        exit;
    }
    
} else {
    // 신규 등록 모드
    $is_html = "1";
    
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO mirae8440.voc(
                    id, name, nick, subject, content, regist_day, hit, is_html, 
                    file_name_0, file_name_1, file_name_2, 
                    file_copied_0, file_copied_1, file_copied_2, parent
                ) VALUES (?, ?, ?, ?, ?, NOW(), 0, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $_SESSION["userid"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(2, $_SESSION["name"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(3, $_SESSION["nick"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $upfile_name[0], PDO::PARAM_STR);
        $stmh->bindValue(8, $upfile_name[1], PDO::PARAM_STR);
        $stmh->bindValue(9, $upfile_name[2], PDO::PARAM_STR);
        $stmh->bindValue(10, $copied_file_name[0], PDO::PARAM_STR);
        $stmh->bindValue(11, $copied_file_name[1], PDO::PARAM_STR);
        $stmh->bindValue(12, $copied_file_name[2], PDO::PARAM_STR);
        $stmh->bindValue(13, $parent, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("VOC 등록 오류 (parent: {$parent}): " . $ex->getMessage());
        echo "오류: 데이터를 등록하는 중 문제가 발생했습니다.";
        exit;
    }
}

// 안전한 리다이렉션
$redirect_url = $WebSite . "/p/view.php?num=" . urlencode($parent);
header("Location: " . $redirect_url);
exit;
?>
