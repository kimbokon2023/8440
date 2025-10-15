<?php
/**
 * 협력업체 평가표 - 등록/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

session_start();
?>
<meta charset="utf-8">
<?php
if (!isset($_SESSION["userid"])) {
    ?>
    <script>
        alert('로그인 후 이용해 주세요.');
        history.back();
    </script>
    <?php
    exit;
}

// 요청 변수 초기화 (?? '' 형태)
$timekey = $_REQUEST["timekey"] ?? ''; // 신규데이터 생성 시 임시저장키
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$is_html = $_REQUEST["is_html"] ?? '';
$noticecheck = $_REQUEST["noticecheck"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';

require_once "../lib/mydb.php";
$pdo = db_connect();

if ($mode == "modify") {
    $num_checked = isset($_REQUEST['del_file']) ? count($_REQUEST['del_file']) : 0;
    $position = $_REQUEST['del_file'] ?? [];
    $del_ok = [];

    for ($i = 0; $i < $num_checked; $i++) {
        $index = $position[$i];
        $del_ok[$index] = "y";
    }

    try {
        $sql = "select * from mirae8440.p_evaluation where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440.p_evaluation set subject=?, content=?, is_html=?, noticecheck=? where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(4, $noticecheck, PDO::PARAM_STR);
        $stmh->bindValue(5, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

} else {
    if ($is_html == "y") {
        $content = htmlspecialchars($content);
    }

    $upfile_name = ['', '', ''];
    $copied_file_name = ['', '', ''];

    try {
        $pdo->beginTransaction();
        $sql = "insert into mirae8440.p_evaluation(id, name, nick, subject, content, regist_day, hit, is_html, ";
        $sql .= " file_name_0, file_name_1, file_name_2, file_copied_0, file_copied_1, file_copied_2, noticecheck) ";
        $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $_SESSION["userid"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(2, $_SESSION["name"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(3, $_SESSION["nick"] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $upfile_name[0], PDO::PARAM_STR);
        $stmh->bindValue(8, $upfile_name[1], PDO::PARAM_STR);
        $stmh->bindValue(9, $upfile_name[2], PDO::PARAM_STR);
        $stmh->bindValue(10, $copied_file_name[0], PDO::PARAM_STR);
        $stmh->bindValue(11, $copied_file_name[1], PDO::PARAM_STR);
        $stmh->bindValue(12, $copied_file_name[2], PDO::PARAM_STR);
        $stmh->bindValue(13, $noticecheck, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

if ($mode == "modify") {
    header("Location:http://8440.co.kr/notice/view.php?num=$num&page=$page");
} else {
    // 신규데이터인 경우 num 추출 후 view로 이동
    $sql = "select * from mirae8440.p_evaluation order by num asc";

    try {
        $stmh = $pdo->query($sql);
        $rowNum = $stmh->rowCount();
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $num = $row["num"];
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }

    // 신규데이터인 경우 첨부파일/첨부이미지 추가한 것이 있으면 parentid 변경
    $id = $num;

    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440.fileuploads set parentid=? where parentid=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

    header("Location:http://8440.co.kr/notice/view.php?num=$num&page=$page");
}
?>
