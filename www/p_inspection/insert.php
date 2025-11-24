<?php
// 에러 리포팅 설정 (개발 환경에서만)
error_reporting(E_ALL);
ini_set('display_errors', 0); // JSON 응답을 위해 화면 출력 비활성화

// JSON 응답을 위해 출력 버퍼링 시작
ob_start();

try {
    require_once __DIR__ . '/../bootstrap.php';

    // 세션 변수 안전하게 초기화
    $DB = $_SESSION["DB"] ?? 'mirae8440';
    $level = $_SESSION["level"] ?? 0;
    $user_name = $_SESSION["name"] ?? 'Unknown';
    $user_id = $_SESSION["userid"] ?? '';

    // 권한 확인
    if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
        ob_clean();
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['error' => '권한이 없습니다.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // common.php는 필요하지 않을 수 있으므로 주석 처리
    // common.php가 출력을 생성하거나 에러를 발생시킬 수 있습니다.
    // if (file_exists(getDocumentRoot() . '/common.php')) {
    //     include getDocumentRoot() . '/common.php';
    // }

    // 요청 변수 안전하게 초기화
    $timekey = $_REQUEST["timekey"] ?? '';   // 신규데이터에 생성할때 임시저장키
    $page = $_REQUEST["page"] ?? 1;
    $mode = $_REQUEST["mode"] ?? '';
    $tablename = $_REQUEST["tablename"] ?? 'p_inspection';

    // 기본 항목 불러옴
    if (file_exists(__DIR__ . '/_request.php')) {
        include __DIR__ . '/_request.php';
    } else {
        throw new Exception('_request.php 파일을 찾을 수 없습니다.');
    }

    // 변수 초기화 (include 후에도 확인)
    $num = $_REQUEST["num"] ?? '';
    $parentID = $parentID ?? $_REQUEST["parentID"] ?? '';
    $subject = $subject ?? $_REQUEST["subject"] ?? '';
    $regist_day = $regist_day ?? $_REQUEST["regist_day"] ?? '';
    $check0 = $check0 ?? $_REQUEST["check0"] ?? '';
    $check1 = $check1 ?? $_REQUEST["check1"] ?? '';
    $check2 = $check2 ?? $_REQUEST["check2"] ?? '';
    $check3 = $check3 ?? $_REQUEST["check3"] ?? '';
    $check4 = $check4 ?? $_REQUEST["check4"] ?? '';
    $check5 = $check5 ?? $_REQUEST["check5"] ?? '';
    $check6 = $check6 ?? $_REQUEST["check6"] ?? '';
    $check7 = $check7 ?? $_REQUEST["check7"] ?? '';
    $check8 = $check8 ?? $_REQUEST["check8"] ?? '';
    $check9 = $check9 ?? $_REQUEST["check9"] ?? '';
    $writer = $writer ?? $_REQUEST["writer"] ?? '';

    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 변수 초기화
    $row = null;

// 수정 모드
if ($mode == "modify") {
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE mirae8440." . $tablename . " SET parentID=?, subject=?, regist_day=?, check0=?, check1=?, check2=?, check3=?, check4=?, check5=?, check6=?, check7=?, check8=?, check9=?, writer=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $parentID, PDO::PARAM_STR);
        $stmh->bindValue(2, $subject, PDO::PARAM_STR);
        $stmh->bindValue(3, $regist_day, PDO::PARAM_STR);
        $stmh->bindValue(4, $check0, PDO::PARAM_STR);
        $stmh->bindValue(5, $check1, PDO::PARAM_STR);
        $stmh->bindValue(6, $check2, PDO::PARAM_STR);
        $stmh->bindValue(7, $check3, PDO::PARAM_STR);
        $stmh->bindValue(8, $check4, PDO::PARAM_STR);
        $stmh->bindValue(9, $check5, PDO::PARAM_STR);
        $stmh->bindValue(10, $check6, PDO::PARAM_STR);
        $stmh->bindValue(11, $check7, PDO::PARAM_STR);
        $stmh->bindValue(12, $check8, PDO::PARAM_STR);
        $stmh->bindValue(13, $check9, PDO::PARAM_STR);
        $stmh->bindValue(14, $writer, PDO::PARAM_STR);
        $stmh->bindValue(15, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
        
        // 수정된 데이터 조회
        $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        ob_clean();
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['error' => '수정 중 오류가 발생했습니다: ' . $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    // 신규 등록 모드
    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO mirae8440." . $tablename . " (parentID, subject, regist_day, check0, check1, check2, check3, check4, check5, check6, check7, check8, check9, writer) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $parentID, PDO::PARAM_STR);
        $stmh->bindValue(2, $subject, PDO::PARAM_STR);
        $stmh->bindValue(3, $regist_day, PDO::PARAM_STR);
        $stmh->bindValue(4, $check0, PDO::PARAM_STR);
        $stmh->bindValue(5, $check1, PDO::PARAM_STR);
        $stmh->bindValue(6, $check2, PDO::PARAM_STR);
        $stmh->bindValue(7, $check3, PDO::PARAM_STR);
        $stmh->bindValue(8, $check4, PDO::PARAM_STR);
        $stmh->bindValue(9, $check5, PDO::PARAM_STR);
        $stmh->bindValue(10, $check6, PDO::PARAM_STR);
        $stmh->bindValue(11, $check7, PDO::PARAM_STR);
        $stmh->bindValue(12, $check8, PDO::PARAM_STR);
        $stmh->bindValue(13, $check9, PDO::PARAM_STR);
        $stmh->bindValue(14, $writer, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        ob_clean();
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['error' => '등록 중 오류가 발생했습니다: ' . $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 신규데이터인 경우 num을 추출한 후 view로 보여주기
    $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";

    try {
        $stmh = $pdo->query($sql);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"] ?? '';
    } catch (PDOException $Exception) {
        ob_clean();
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['error' => '데이터 조회 중 오류가 발생했습니다: ' . $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

    // 출력 버퍼 정리 및 JSON 응답 반환
    ob_clean();
    header("Content-Type: application/json; charset=UTF-8");

    $data = [
        'num' => $num,
        'row' => $row
    ];

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $e) {
    // 모든 예외를 잡아서 JSON으로 반환
    ob_clean();
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(500);
    echo json_encode([
        'error' => '서버 오류가 발생했습니다.',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_UNESCAPED_UNICODE);
    exit;
} catch (Error $e) {
    // PHP 7+ Fatal Error 처리
    ob_clean();
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(500);
    echo json_encode([
        'error' => '치명적 오류가 발생했습니다.',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
