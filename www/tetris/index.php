<!-- 미래기업 테트리스 게임 -->
<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"])) {
    sleep(2);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

// 캐시 방지 헤더
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21

include getDocumentRoot() . '/load_header.php';

// 요청 변수 초기화
$new = $_REQUEST["new"] ?? '';
?>
    <title>미래기업 추억의 테트리스</title>    
    
    <!-- Common JS & CSS -->
    <script src="../js/common.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include getDocumentRoot() . '/myheader.php' ?>
<input id="score" name="score" type="hidden">
<input id="restartVal" name="restartVal" type="hidden">
<input id="user_name" name="user_name" type="hidden" value="<?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?>">

<div class="total">
    <!-- 왼쪽 랭킹 섹션 -->
    <div class="total leftsection">
        <div class="input-group p-0 mb-2">
            <span class="input-group-text align-items-center" style="width:50px;">순위</span>
            <span class="input-group-text align-items-center" style="width:100px;">성명</span>
            <span class="input-group-text align-items-center" style="width:100px;">점수</span>
            <span class="input-group-text align-items-center" style="width:200px;">기록일시</span>
            <span class="input-group-text align-items-center" style="width:250px;">랭킹 기록 (일시정지 ESC키)</span>
        </div>

<?php
// 랭킹 TOP 30 조회
$sql = "select * from " . $DB . ".tetris order by score desc";
$start_num = 1;

try {
    $stmh = $pdo->query($sql);
    $temp = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        if ($start_num <= 30) {
            $num = $row["num"] ?? '';
            $rec_date = $row["rec_date"] ?? '';
            $name = $row["name"] ?? '';
            $score = $row["score"] ?? 0;
?>
        <div class="input-group p-0 mb-0">
            <span class="input-group-text align-items-center" style="width:50px;"><?= $start_num ?></span>
            <span class="input-group-text align-items-center" style="width:100px;"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></span>
            <span class="input-group-text align-items-center" style="width:100px;"><?= $score ?></span>
            <span class="input-group-text align-items-center" style="width:200px;"><?= $rec_date ?></span>
        </div>
<?php
        }
        $start_num++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>
    </div>
    <!-- 게임 화면 섹션 -->
    <div class="total wrapper">
        <div class="game-text">
            <div class="input-group p-0 mb-2">
                <span class="input-group-text align-items-center" style="width:50px;">순위</span>
                <span class="input-group-text align-items-center" style="width:100px;">성명</span>
                <span class="input-group-text align-items-center" style="width:100px;">점수</span>
                <span class="input-group-text align-items-center" style="width:200px;">기록일시</span>
            </div>

<?php
// 게임 화면 랭킹 TOP 10 조회
$sql2 = "select * from " . $DB . ".tetris order by score desc";
$start_num2 = 1;

try {
    $stmh2 = $pdo->query($sql2);
    $temp2 = $stmh2->rowCount();

    while ($row2 = $stmh2->fetch(PDO::FETCH_ASSOC)) {
        if ($start_num2 <= 10) {
            $num2 = $row2["num"] ?? '';
            $rec_date2 = $row2["rec_date"] ?? '';
            $name2 = $row2["name"] ?? '';
            $score2 = $row2["score"] ?? 0;
?>
            <div class="input-group p-0 mb-0">
                <span class="input-group-text align-items-center" style="width:50px;"><?= $start_num2 ?></span>
                <span class="input-group-text align-items-center" style="width:100px;"><?= htmlspecialchars($name2, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="input-group-text align-items-center" style="width:100px;"><?= $score2 ?></span>
                <span class="input-group-text align-items-center" style="width:200px;"><?= $rec_date2 ?></span>
            </div>
<?php
        }
        $start_num2++;
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>

            <span>게임종료</span>
            <button id="reStartBtn">다시시작</button>
        </div>
        
        <div class="score">0</div>
        <div class="playground">
            <ul></ul>
        </div>
    </div>
</div>

<div id="vacancy" style="display:none;"></div>    	

<script src="js/tetris.js" type="module"></script>

<script>
$(document).ready(function() {
    $("#reStartBtn").click(function() {
        $("#restartVal").val('1');
    });
});
</script>

</body>
</html>