<?php

// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';

$measureday = date("Y-m-d");

require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    $sql = "update mirae8440.work set measureday=? where num=? LIMIT 1";

    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $measureday, PDO::PARAM_STR);
    $stmh->bindValue(2, $num, PDO::PARAM_STR);  // 고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문

    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류: " . $Exception->getMessage();
}

// echo '<script>alert("실측일이 입력되었습니다."");</script>';

header("Location: {$base_url}/p/view.php?num={$num}");
?>
