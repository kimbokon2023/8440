<?php
/**
 * 협력업체 평가표 - 데이터 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';

// PHP warning 안나오게 설정
ini_set('display_errors', 'Off');

// DB 이름 설정
$DB = "mirae8440.p_evaluation";

include 'common.php';

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? '';

if ((int)$num == 0) {
    $SelectWork = "insert";
}

require_once includePath('lib/mydb.php');
$pdo = db_connect();

include "_request.php";



// UPDATE 처리
if ($SelectWork == "update") {
    try {
        $pdo->beginTransaction();

        // UPDATE 쿼리 (where 구문 포함)
        $sql = "update " . $DB . " set txt1=?, txt2=?, txt3=?, txt4=?, txt5=?, txt6=?, txt7=?, txt8=?, txt9=?, txt10=?, txt11=?, txt12=?, txt13=?, txt14=?, txt15=?, txt16=?, txt17=?, txt18=?, txt19=?, txt20=?, txt21=?, txt22=?, txt23=? ";
        $sql .= " where num=? LIMIT 1";

		$stmh = $pdo->prepare($sql);

		$stmh->bindValue(1, $txt1, PDO::PARAM_STR);
		$stmh->bindValue(2, $txt2, PDO::PARAM_STR);
		$stmh->bindValue(3, $txt3, PDO::PARAM_STR);
		$stmh->bindValue(4, $txt4, PDO::PARAM_STR);
		$stmh->bindValue(5, $txt5, PDO::PARAM_STR);
		$stmh->bindValue(6, $txt6, PDO::PARAM_STR);
		$stmh->bindValue(7, $txt7, PDO::PARAM_STR);
		$stmh->bindValue(8, $txt8, PDO::PARAM_STR);
		$stmh->bindValue(9, $txt9, PDO::PARAM_STR);
		$stmh->bindValue(10, $txt10, PDO::PARAM_STR);
		$stmh->bindValue(11, $txt11, PDO::PARAM_STR);
		$stmh->bindValue(12, $txt12, PDO::PARAM_STR);
		$stmh->bindValue(13, $txt13, PDO::PARAM_STR);
		$stmh->bindValue(14, $txt14, PDO::PARAM_STR);
		$stmh->bindValue(15, $txt15, PDO::PARAM_STR);
		$stmh->bindValue(16, $txt16, PDO::PARAM_STR);
		$stmh->bindValue(17, $txt17, PDO::PARAM_STR);
		$stmh->bindValue(18, $txt18, PDO::PARAM_STR);
		$stmh->bindValue(19, $txt19, PDO::PARAM_STR);
		$stmh->bindValue(20, $txt20, PDO::PARAM_STR);
		$stmh->bindValue(21, $txt21, PDO::PARAM_STR);
		$stmh->bindValue(22, $txt22, PDO::PARAM_STR);
		$stmh->bindValue(23, $txt23, PDO::PARAM_STR);
		$stmh->bindValue(24, $num, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

// INSERT 처리
if ($SelectWork == "insert") {
    // 데이터 신규 등록

    try {
    $pdo->beginTransaction();

    $sql = "insert into " . $DB . " (txt1, txt2, txt3, txt4, txt5, txt6, txt7, txt8, txt9, txt10, txt11, txt12, txt13, txt14, txt15, txt16, txt17, txt18, txt19, txt20, txt21, txt22, txt23) ";
    $sql .= "values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmh = $pdo->prepare($sql);

    $stmh->bindValue(1, $txt1, PDO::PARAM_STR);
    $stmh->bindValue(2, $txt2, PDO::PARAM_STR);
    $stmh->bindValue(3, $txt3, PDO::PARAM_STR);
    $stmh->bindValue(4, $txt4, PDO::PARAM_STR);
    $stmh->bindValue(5, $txt5, PDO::PARAM_STR);
    $stmh->bindValue(6, $txt6, PDO::PARAM_STR);
    $stmh->bindValue(7, $txt7, PDO::PARAM_STR);
    $stmh->bindValue(8, $txt8, PDO::PARAM_STR);
    $stmh->bindValue(9, $txt9, PDO::PARAM_STR);
    $stmh->bindValue(10, $txt10, PDO::PARAM_STR);
    $stmh->bindValue(11, $txt11, PDO::PARAM_STR);
    $stmh->bindValue(12, $txt12, PDO::PARAM_STR);
    $stmh->bindValue(13, $txt13, PDO::PARAM_STR);
    $stmh->bindValue(14, $txt14, PDO::PARAM_STR);
    $stmh->bindValue(15, $txt15, PDO::PARAM_STR);
    $stmh->bindValue(16, $txt16, PDO::PARAM_STR);
    $stmh->bindValue(17, $txt17, PDO::PARAM_STR);
    $stmh->bindValue(18, $txt18, PDO::PARAM_STR);
    $stmh->bindValue(19, $txt19, PDO::PARAM_STR);
    $stmh->bindValue(20, $txt20, PDO::PARAM_STR);
    $stmh->bindValue(21, $txt21, PDO::PARAM_STR);
    $stmh->bindValue(22, $txt22, PDO::PARAM_STR);
    $stmh->bindValue(23, $txt23, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

    // parentKey 추출
    $sql = "select * from " . $DB . " order by num desc";
    try {
        $stmh = $pdo->query($sql);
        $temp = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"] ?? '';
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
}

// DELETE 처리
if ($SelectWork == "delete") {
    try {
        $pdo->beginTransaction();
        $sql = "delete from " . $DB . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

$data = [
    'num' => $num,
    'dump' => $txt1 ?? ''
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);



?>

