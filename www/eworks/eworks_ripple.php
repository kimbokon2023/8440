<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$e_num = $_REQUEST["e_num"] ?? '';
$page = $_REQUEST["page"] ?? 1;

// 데이터베이스 연결
$pdo = db_connect();

?>

<div class="row p-1 m-1 mt-1 mb-1 justify-content-center">
    <?php
    try {
        $sql_ripple = "SELECT * FROM {$DB}.eworks_ripple WHERE parent = ? AND is_deleted IS NULL ORDER BY num ASC";
        $stmh = $pdo->prepare($sql_ripple);
        $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh->execute();
        
        while ($row_ripple = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $ripple_num = $row_ripple["num"] ?? '';
            $ripple_id = $row_ripple["author_id"] ?? '';
            $ripple_nick = $row_ripple["author"] ?? '';
            $ripple_content = $row_ripple["content"] ?? '';
            $ripple_date = $row_ripple["regist_day"] ?? '';
            
            // 내용 포맷팅
            $ripple_content = str_replace("\n", "", $ripple_content);
            $ripple_content = str_replace(" ", "&nbsp;", $ripple_content);
    ?>
            <div class="card" style="width:80%">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <span class="mt-1 mb-2">
                            ▶&nbsp;&nbsp;<?= htmlspecialchars($ripple_content) ?>
                            ✔&nbsp;&nbsp;작성자: <?= htmlspecialchars($ripple_nick) ?> | 
                            <?= htmlspecialchars($ripple_date) ?>
                            
                            <?php
                            // 삭제 권한 체크 (관리자, 작성자, level 1)
                            if ($userid == "admin" || $userid == $ripple_id || $level == 1) {
                            ?>
                                <a href="#" class="text-danger" 
                                   onclick="eworks_delete_ripple('<?= htmlspecialchars($ripple_num, ENT_QUOTES) ?>'); return false;">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>
    <?php
        }
    } catch (PDOException $ex) {
        error_log("댓글 조회 오류: " . $ex->getMessage());
    }
    ?>
</div>
