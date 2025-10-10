<?php
/**
 * saveDeadline.php
 * 납기일(deadline)을 일괄 업데이트하는 AJAX 처리 파일
 */

session_start();

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// REQUEST 변수 안전하게 초기화
$num_arr = isset($_REQUEST["num_arr"]) ? $_REQUEST["num_arr"] : '';
$recordDate_arr = isset($_REQUEST["recordDate_arr"]) ? $_REQUEST["recordDate_arr"] : '';

// 배열이 비어있는지 확인
if (empty($num_arr) || empty($recordDate_arr)) {
    echo json_encode(array(
        "success" => false,
        "message" => "필수 파라미터가 누락되었습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 콤마로 구분된 문자열을 배열로 변환
$num_tmp = array();
$date_tmp = array();

if (is_array($num_arr) && isset($num_arr[0])) {
    $num_tmp = explode(",", $num_arr[0]);
}

if (is_array($recordDate_arr) && isset($recordDate_arr[0])) {
    $date_tmp = explode(",", $recordDate_arr[0]);
}

// 배열 크기가 다른 경우 에러 처리
if (count($num_tmp) != count($date_tmp)) {
    echo json_encode(array(
        "success" => false,
        "message" => "데이터 개수가 일치하지 않습니다."
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 업데이트 카운터
$success_count = 0;
$error_count = 0;

// 각 레코드의 납기일 업데이트
for ($i = 0; $i < count($num_tmp); $i++) {
    try {
        $pdo->beginTransaction();
        
        // SQL 쿼리 준비
        $sql = "UPDATE mirae8440.ceiling SET deadline = ? WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmh->bindValue(1, $date_tmp[$i], PDO::PARAM_STR); // 납기일
        $stmh->bindValue(2, $num_tmp[$i], PDO::PARAM_STR);  // 레코드 번호
        
        // 쿼리 실행
        $stmh->execute();
        $pdo->commit();
        
        $success_count++;
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        $error_count++;
        error_log("오류: " . $Exception->getMessage());
    }
}

// JSON 응답 데이터 구성
$data = array(
    "success" => true,
    "num_arr" => $num_tmp,
    "recordDate_arr" => $date_tmp,
    "success_count" => $success_count,
    "error_count" => $error_count,
    "total_count" => count($num_tmp)
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>