<?php
require_once getDocumentRoot() . '/session.php';
header("Content-Type: application/json");

$num = $_REQUEST["num"] ?? '';
$store = $_REQUEST["store"] ?? '';  // store가 검토자 역할을 한다.
$firstTime = $_REQUEST["firstTime"] ?? '';  
$secondTime = $_REQUEST["secondTime"] ?? ''; 

$e_confirm = "최장중 이사 " . $firstTime . "!소현철 대표 " . $secondTime ;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
	$pdo->beginTransaction();
	$sql = "UPDATE {$DB}.eworks SET                     
				e_confirm = ?,
				store = ?
			WHERE num = ? LIMIT 1";

	$stmh = $pdo->prepare($sql);
	$stmh->execute([
		$e_confirm, $store, $num
	]);
	$pdo->commit();
} catch (PDOException $e) {
	$pdo->rollBack();
	echo json_encode(["error" => $e->getMessage()]);
	exit;
}

echo json_encode(["num" => $num, "mode" => $mode], JSON_UNESCAPED_UNICODE);
