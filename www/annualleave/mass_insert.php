<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$session_userid = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';

header("Content-Type: application/json");

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$registdate = $_REQUEST["registdate"] ?? '';
$item = $_REQUEST["item"] ?? '';
$askdatefrom = $_REQUEST["askdatefrom"] ?? '';
$askdateto = $_REQUEST["askdateto"] ?? '';
$usedday = $_REQUEST["usedday"] ?? '';
$content = $_REQUEST["content"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 기본정보 불러옴
include "load_DB.php";

// load_DB.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
$basic_name_arr = $basic_name_arr ?? array();
$basic_id_arr = $basic_id_arr ?? array();
$basic_part_arr = $basic_part_arr ?? array();

// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기
// 2022년 2023년 등 자료의 유일한 값을 위주로 대량생산함 array_unique
$state = '처리완료';

for ($i = 0; $i < count(array_unique($basic_name_arr)); $i++) {
    $id = $basic_id_arr[$i];
    $name = $basic_name_arr[$i];
    $part = $basic_part_arr[$i];

    if ($mode == "insert") {
        try {
            $pdo->beginTransaction();

            $sql = "insert into mirae8440.al(id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part)";
            $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $id, PDO::PARAM_STR);
            $stmh->bindValue(2, $name, PDO::PARAM_STR);
            $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
            $stmh->bindValue(4, $item, PDO::PARAM_STR);
            $stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);
            $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);
            $stmh->bindValue(7, $usedday, PDO::PARAM_STR);
            $stmh->bindValue(8, $content, PDO::PARAM_STR);
            $stmh->bindValue(9, $state, PDO::PARAM_STR);
            $stmh->bindValue(10, $part, PDO::PARAM_STR);

            $stmh->execute();
            $pdo->commit();
        } catch (PDOException $ex) {
            $pdo->rollBack();
            error_log("연차 대량등록 오류: " . $ex->getMessage());
        }
    }
}

// JSON 응답 데이터 생성
$data = array(
    "registdate" => $registdate,
    "state" => $state,
);

// JSON 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
