<?php
/**
 * 파일 목록 조회 API
 * 특정 테이블의 레코드에 연결된 파일 목록을 JSON으로 반환합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 파라미터 초기화
$id = $_REQUEST["id"] ?? '';
$fileorimage = $_REQUEST["fileorimage"] ?? ''; // file or image
$item = $_REQUEST["item"] ?? '';
$upfilename = $_REQUEST["upfilename"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$savetitle = $_REQUEST["savetitle"] ?? ''; // 로그 기록 저장 타이틀

// 배열 초기화
$id_arr = array();
$parentid_arr = array();
$realfile_arr = array();
$file_arr = array();
$recid = 0;

// 입력 검증
if (empty($tablename)) {
    echo json_encode(array(
        'success' => false,
        'message' => '테이블명이 지정되지 않았습니다.',
        'recid' => 0,
        'id_arr' => array(),
        'parentid_arr' => array(),
        'file_arr' => array(),
        'realfile_arr' => array()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // SQL Injection 방지를 위한 Prepared Statement 사용
    $sql = "SELECT * FROM {$DB}.fileuploads WHERE tablename = ? AND item = ? AND parentid = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $tablename, PDO::PARAM_STR);
    $stmh->bindValue(2, $item, PDO::PARAM_STR);
    $stmh->bindValue(3, $id, PDO::PARAM_STR);
    $stmh->execute();
    
    $i = 0;
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $id_arr[$i] = $row["id"];
        $parentid_arr[$i] = $row["parentid"];
        $realfile_arr[$i] = $row["realname"];
        $file_arr[$i] = $row["savename"];
        
        $i++;
    }
    
    $recid = $i;
    
} catch (PDOException $ex) {
    error_log("DB query error in load_file.php: " . $ex->getMessage());
    
    // 오류 발생 시 빈 배열 반환
    echo json_encode(array(
        'success' => false,
        'message' => 'DB 조회 오류',
        'recid' => 0,
        'id_arr' => array(),
        'parentid_arr' => array(),
        'file_arr' => array(),
        'realfile_arr' => array()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// JSON 응답 생성
$data = array(
    'success' => true,
    'recid' => $recid,
    'id_arr' => $id_arr,
    'parentid_arr' => $parentid_arr,
    'file_arr' => $file_arr,
    'realfile_arr' => $realfile_arr
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>