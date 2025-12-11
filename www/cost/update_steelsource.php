<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// JSON 헤더 설정
header("Content-Type: application/json");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$steelitem = $_REQUEST["steelitem"] ?? '';
$steelspec = $_REQUEST["steelspec"] ?? '';
$steeltake = $_REQUEST["steeltake"] ?? '';  // take는 사급자재업체명 기록필드

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'steelitem' => $steelitem,
    'steelspec' => $steelspec,
    'steeltake' => $steeltake,
    'sortorder' => 9
);

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 기존 자료 배열 저장
$saved_title = array();

// 원자재 현재 상태 읽기
$sql = "SELECT * FROM {$DB}.steelsource ORDER BY sortorder ASC, item DESC";

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($saved_title, trim($row["item"]) . trim($row["spec"]) . $row["take"]);
    }
} catch (PDOException $ex) {
    error_log("철판 소스 조회 오류: " . $ex->getMessage());
}

$saved_title = array_unique($saved_title);  // 고유한 배열만 정리하는 함수
sort($saved_title);

// 전달된 DATA가 기존의 배열에 없으면 추가하기
$checkinsert = true;
$search_key = trim($steelitem) . trim($steelspec) . trim($steeltake);

for ($i = 0; $i < count($saved_title); $i++) {
    // DB에 이미 존재하면 추가하지 않음
    if ($saved_title[$i] == $search_key) {
        $checkinsert = false;
        break;
    }
}

// 정렬 순서 결정 (철판 종류에 따라)
$sortorder = 9;  // 기본값

if (strpos($steelitem, 'CR') !== false) {
    $sortorder = 1;
} elseif (strpos($steelitem, 'PO') !== false) {
    $sortorder = 2;
} elseif (strpos($steelitem, 'EGI') !== false) {
    $sortorder = 3;
} elseif (strpos($steelitem, '304 HL NSP') !== false) {
    $sortorder = 4;
} elseif (strpos($steelitem, '304 HL') !== false) {
    $sortorder = 4;
} elseif (strpos($steelitem, '304 MR') !== false) {
    $sortorder = 4;
} elseif (strpos($steelitem, '304 BA') !== false) {
    $sortorder = 4;
}

$response['sortorder'] = $sortorder;

if ($checkinsert == true) {
    // 데이터 추가하는 구간
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.steelsource (sortorder, item, spec, take) 
                VALUES (?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $sortorder, PDO::PARAM_STR);
        $stmh->bindValue(2, $steelitem, PDO::PARAM_STR);
        $stmh->bindValue(3, $steelspec, PDO::PARAM_STR);
        $stmh->bindValue(4, $steeltake, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        // 성공 응답
        $response['success'] = true;
        $response['message'] = '철판 소스가 추가되었습니다.';
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("철판 소스 추가 오류: " . $ex->getMessage());
        
        $response['success'] = false;
        $response['message'] = '철판 소스 추가 중 오류가 발생했습니다.';
    }
} else {
    // 이미 존재하는 경우
    $response['success'] = true;
    $response['message'] = '이미 존재하는 철판 소스입니다.';
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>

