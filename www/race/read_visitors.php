<?php
// read_visitors.php

session_start(); 

$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];

require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/mydb.php");
$pdo = db_connect();

$waitlist = array();

try {
    $sql = "SELECT * FROM mirae8440.visitors WHERE TIMESTAMPDIFF(MINUTE, visit_time, NOW()) <= 5";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $waitlist[] = $row["visit_name"];
    }
} catch (PDOException $Exception) {
    echo json_encode(array("error" => "오류: " . $Exception->getMessage()));
    exit;
}

header('Content-Type: application/json');

$waitlist = array_unique($waitlist);

$data = [
            'waitlist' => $waitlist
        ];

echo(json_encode($data, JSON_UNESCAPED_UNICODE));   // 한국어를 사용할때는 이렇게 선언해야 한다. echo json_encode($data);의 차이점은 json_encode 함수에 제공되는 옵션에 있습니다.
?>
