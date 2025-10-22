<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재(철판) 종류 및 규격 관리
 * 
 * 새로운 종류 + 규격이 생길 때 여기서 생성한 자료를 기반으로 재고를 파악합니다.
 * 이 과정이 없으면 속도가 엄청 느리게 되는 현상이 발생합니다.
 * 재고 이관 시 반드시 등록을 하고 진행해야 합니다.
 */

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 변수 초기화
$steelitem = $_REQUEST["steelitem"] ?? '';
$steelspec = $_REQUEST["steelspec"] ?? '';
$steeltake = $_REQUEST["steeltake"] ?? '';  // 사급자재업체명 기록필드

// 기존의 자료에 없으면 추가해주기
// 원자재 현재 상태 읽기
$sql = "select * from " . $DB . ".steelsource order by sortorder asc, item desc";

try {
    $stmh = $pdo->query($sql);
    $j = 0;
    $item_counter = 1;
    $title = array();
    $saved_title = array();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $item = $row["item"] ?? '';
        $spec = $row["spec"] ?? '';
        $take = $row["take"] ?? '';
        array_push($saved_title, trim($item) . trim($spec) . $take);
    }
} catch (PDOException $Exception) {
    echo json_encode(array(
        "error" => "데이터 조회 오류: " . $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 고유한 배열만 정리
$saved_title = array_unique($saved_title);
sort($saved_title);

// 전달된 데이터가 기존 배열에 없으면 추가하기
$checkinsert = true;
for ($i = 0; $i < count($saved_title); $i++) {
    // DB에 이미 존재하는지 확인
    if ($saved_title[$i] == trim($steelitem) . trim($steelspec) . trim($steeltake)) {
        $checkinsert = false;
        break;
    }
}

// 정렬 순서 결정 (철판 종류에 따라)
$sortorder = 9;  // 기본값

if (strpos($steelitem, '304 BA') !== false) {
    $sortorder = 4;
}
if (strpos($steelitem, '304 MR') !== false) {
    $sortorder = 4;
}
if (strpos($steelitem, '304 HL NSP') !== false) {
    $sortorder = 4;
}
if (strpos($steelitem, '304 HL') !== false) {
    $sortorder = 4;
}
if (strpos($steelitem, 'EGI') !== false) {
    $sortorder = 3;
}
if (strpos($steelitem, 'PO') !== false) {
    $sortorder = 2;
}
if (strpos($steelitem, 'CR') !== false) {
    $sortorder = 1;
}


// 데이터 추가 (중복이 아닌 경우)
if ($checkinsert == true) {
    try {
        $pdo->beginTransaction();

        $sql = "insert into " . $DB . ".steelsource(sortorder, item, spec, take) ";
        $sql .= "values(?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $sortorder, PDO::PARAM_INT);
        $stmh->bindValue(2, $steelitem, PDO::PARAM_STR);
        $stmh->bindValue(3, $steelspec, PDO::PARAM_STR);
        $stmh->bindValue(4, $steeltake, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        echo json_encode(array(
            "error" => "데이터 추가 오류: " . $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
}          

// JSON 파일로 저장할 때마다 갱신
$sql = "select * from " . $DB . ".steelsource";

try {
    $stmh = $pdo->query($sql);
    $counter = 0;
    $steelsource_num = array();
    $steelsource_item = array();
    $steelsource_spec = array();
    $steelsource_take = array();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $steelsource_num[$counter] = $row["num"] ?? '';
        array_push($steelsource_item, $row["item"] ?? '');
        $steelsource_spec[$counter] = $row["spec"] ?? '';
        $steelsource_take[$counter] = $row["take"] ?? '';
        $counter++;
    }
} catch (PDOException $Exception) {
    echo json_encode(array(
        "error" => "데이터 조회 오류: " . $Exception->getMessage()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 고유한 아이템 목록 생성
array_push($steelsource_item, " ");
$steelitem_arr = array_unique($steelsource_item);
sort($steelitem_arr);


// 추출한 데이터를 배열로 조합
$datatmp = array(
    "steelsource_num" => $steelsource_num,
    "steelsource_item" => $steelsource_item,
    "steelsource_spec" => $steelsource_spec,
    "steelsource_take" => $steelsource_take,
    "steelitem_arr" => $steelitem_arr
);

// JSON 형식으로 인코딩
$jsonData = json_encode($datatmp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// 파일에 저장
$jsonFilePath = __DIR__ . '/../steelsourcejson.json';
if (file_put_contents($jsonFilePath, $jsonData) === false) {
    echo json_encode(array(
        "error" => "JSON 파일 저장 실패"
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 응답 데이터 생성
$data = array(
    "steelitem" => $steelitem,
    "steelspec" => $steelspec,
    "steeltake" => $steeltake,
    "sortorder" => $sortorder,
    "success" => true,
    "message" => "원자재 정보가 성공적으로 저장되었습니다."
);

// JSON 응답 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

