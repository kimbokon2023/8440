<?php
/**
 * Concert 게시판 목록 페이지
 * 게시글 목록을 표시하고 검색 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$user_id = $_SESSION['userid'] ?? '';
$DB = $_SESSION['DB'] ?? 'phptest1';

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';

// 변수 초기화
$count = 0;
$sql = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 검색 모드 처리
if ($mode == "search") {
    if (empty($search)) {
        ?>
        <script>
            alert('검색할 단어를 입력해 주세요!');
            history.back();
        </script>
        <?php
        exit;
    }
    
    // SQL Injection 방지를 위한 Prepared Statement 사용
    $search_safe = str_replace("'", "''", $search);
    $allowed_fields = array('subject', 'content', 'nick', 'name');
    
    if (!in_array($find, $allowed_fields)) {
        $find = 'subject'; // 기본값
    }
    
    $sql = "SELECT * FROM {$DB}.concert WHERE {$find} LIKE ? ORDER BY num DESC";
} else {
    $sql = "SELECT * FROM {$DB}.concert ORDER BY num DESC";
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/concert.css">
    <title>Concert 게시판</title>
</head>
<body>
    <div id="wrap">
        <div id="header">
            <?php include "../lib/top_login2.php"; ?>
        </div>
        
        <div id="menu">
            <?php include "../lib/top_menu2.php"; ?>
        </div>
        
        <div id="content">
            <div id="col1">
                <div id="left_menu">
                    <?php include "../lib/left_menu.php"; ?>
                </div>
            </div> <!-- end of col1 -->
            
            <div id="col2">
                <div id="title">
                    <img src="../img/title_concert.gif" alt="Concert 게시판">
                </div>
                
                <?php
                try {
                    // 쿼리 실행
                    if ($mode == "search") {
                        $stmh = $pdo->prepare($sql);
                        $stmh->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
                        $stmh->execute();
                    } else {
                        $stmh = $pdo->query($sql);
                    }
                    
                    $count = $stmh->rowCount();
                ?>
                
                <form name="board_form" method="post" action="list.php?mode=search">
                    <div id="list_search">
                        <div id="list_search1">
                            ▷ 총 <?php echo htmlspecialchars($count, ENT_QUOTES, 'UTF-8'); ?> 개의 게시물이 있습니다.
                        </div>
                        <div id="list_search2">
                            <img src="../img/select_search.gif" alt="검색">
                        </div>
                        <div id="list_search3">
                            <select name="find">
                                <option value='subject' <?php echo ($find == 'subject') ? 'selected' : ''; ?>>제목</option>
                                <option value='content' <?php echo ($find == 'content') ? 'selected' : ''; ?>>내용</option>
                                <option value='nick' <?php echo ($find == 'nick') ? 'selected' : ''; ?>>닉네임</option>
                                <option value='name' <?php echo ($find == 'name') ? 'selected' : ''; ?>>이름</option>
                            </select>
                        </div> <!-- end of list_search3 -->
                        <div id="list_search4">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div id="list_search5">
                            <input type="image" src="../img/list_search_button.gif" alt="검색">
                        </div>
                    </div> <!-- end of list_search -->
                </form>
                
                <div class="clear"></div>
                
                <div id="list_top_title">
                    <ul>
                        <li id="list_title1"><img src="../img/list_title1.gif" alt="번호"></li>
                        <li id="list_title2"><img src="../img/list_title2.gif" alt="제목"></li>
                        <li id="list_title3"><img src="../img/list_title3.gif" alt="글쓴이"></li>
                        <li id="list_title4"><img src="../img/list_title4.gif" alt="날짜"></li>
                        <li id="list_title5"><img src="../img/list_title5.gif" alt="조회"></li>
                    </ul>
                </div> <!-- end of list_top_title -->
                
                <div id="list_content">
                    <?php
                    // 글 목록 출력
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        $item_num = $row["num"];
                        $item_id = $row["id"];
                        $item_name = $row["name"];
                        $item_nick = $row["nick"];
                        $item_hit = $row["hit"];
                        $item_date = $row["regist_day"];
                        $item_date = substr($item_date, 0, 10);
                        $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
                    ?>
                    <div id="list_item">
                        <div id="list_item1"><?php echo htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div id="list_item2">
                            <a href="view.php?num=<?php echo urlencode($item_num); ?>">
                                <?php echo htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </div>
                        <div id="list_item3"><?php echo htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div id="list_item4"><?php echo htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div id="list_item5"><?php echo htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div> <!-- end of list_item -->
                    <?php
                    }
                    ?>
                </div> <!-- end of list_content -->
                
                <?php
                } catch (PDOException $ex) {
                    error_log("DB query error in list.php: " . $ex->getMessage());
                    echo "<p>데이터 조회 오류가 발생했습니다.</p>";
                }
                ?>
                
                <div id="write_button">
                    <a href="list.php"><img src="../img/list.png" alt="목록"></a>&nbsp;
                    <?php
                    if (!empty($user_id)) {
                    ?>
                    <a href="write_form.php"><img src="../img/write.png" alt="글쓰기"></a>
                    <?php
                    }
                    ?>
                </div>
            </div> <!-- end of col2 -->
        </div> <!-- end of content -->
    </div> <!-- end of wrap -->
</body>
</html>
