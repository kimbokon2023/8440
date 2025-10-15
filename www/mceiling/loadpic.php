<?php
/**
 * 천장 포장 사진 목록 조회 (AJAX)
 * 로컬 및 서버 환경 모두 지원
 */

header("Content-Type: application/json; charset=utf-8");

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';

// 응답 데이터 초기화
$response = [
    'success' => false,
    'message' => '',
    'recnum' => 0,
    'num_arr' => [],
    'parentnum_arr' => [],
    'img_arr' => []
];

// 입력값 검증
if (empty($num)) {
    $response['message'] = '자료 번호가 지정되지 않았습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 날짜/시간 (필요시 사용)
$now = date("Y-m-d");
$nowtime = date("H:i:s");

// 사진 정보 조회
try {
    $sql = "SELECT num, parentnum, picname FROM mirae8440.ceilpicfile WHERE parentnum = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $num_arr = [];
    $parentnum_arr = [];
    $img_arr = [];
    $i = 0;
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num_arr[$i] = $row["num"] ?? '';
        $parentnum_arr[$i] = $row["parentnum"] ?? '';
        $img_arr[$i] = $row["picname"] ?? '';
        $i++;
    }
    
    $recnum = $i;
    
    // 성공 응답
    $response['success'] = true;
    $response['recnum'] = $recnum;
    $response['num_arr'] = $num_arr;
    $response['parentnum_arr'] = $parentnum_arr;
    $response['img_arr'] = $img_arr;
    $response['message'] = "{$recnum}개의 사진을 찾았습니다.";
    
} catch (PDOException $ex) {
    error_log("사진 목록 조회 오류 (parentnum: {$num}): " . $ex->getMessage());
    $response['message'] = '사진 목록을 불러오는 중 오류가 발생했습니다.';
    $response['error'] = $ex->getMessage();
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
