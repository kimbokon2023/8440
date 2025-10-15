<?php
/**
 * Concert 게시글 작성/수정 폼 페이지
 * 새 게시글을 작성하거나 기존 게시글을 수정합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$user_id = $_SESSION['userid'] ?? '';
$user_nick = $_SESSION['nick'] ?? '';
$DB = $_SESSION['DB'] ?? 'phptest1';

// 로그인 확인
if (empty($user_id)) {
    ?>
    <script>
        alert('로그인 후 이용해 주세요.');
        location.href = 'list.php';
    </script>
    <?php
    exit;
}

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$item_subject = '';
$item_content = '';
$item_file_0 = '';
$item_file_1 = '';
$item_file_2 = '';
$copied_file_0 = '';
$copied_file_1 = '';
$copied_file_2 = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드인 경우 기존 데이터 조회
if ($mode == "modify") {
    if (empty($num)) {
        ?>
        <script>
            alert('잘못된 접근입니다.');
            location.href = 'list.php';
        </script>
        <?php
        exit;
    }
    
    try {
        $sql = "SELECT * FROM {$DB}.concert WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count < 1) {
            ?>
            <script>
                alert('해당 게시글을 찾을 수 없습니다.');
                location.href = 'list.php';
            </script>
            <?php
            exit;
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            $item_subject = $row["subject"];
            $item_content = $row["content"];
            $item_file_0 = $row["file_name_0"] ?? '';
            $item_file_1 = $row["file_name_1"] ?? '';
            $item_file_2 = $row["file_name_2"] ?? '';
            $copied_file_0 = $row["file_copied_0"] ?? '';
            $copied_file_1 = $row["file_copied_1"] ?? '';
            $copied_file_2 = $row["file_copied_2"] ?? '';
        }
    } catch (PDOException $ex) {
        error_log("DB query error in write_form.php: " . $ex->getMessage());
        ?>
        <script>
            alert('데이터 조회 오류가 발생했습니다.');
            location.href = 'list.php';
        </script>
        <?php
        exit;
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/concert.css">
    <title>Concert 게시판 - <?php echo ($mode == "modify") ? "글수정" : "글쓰기"; ?></title>
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
            </div>
            
            <div id="col2">
                <div id="title">
                    <img src="../img/title_concert.gif" alt="Concert 게시판">
                </div>
                
                <div class="clear"></div>
                
                <div id="write_form_title">
                    <img src="../img/write_form_title.gif" alt="글쓰기">
                </div>
                
                <div class="clear"></div>
                
                <?php
                if ($mode == "modify") {
                ?>
                <form name="board_form" method="post" action="insert.php?mode=modify&num=<?php echo urlencode($num); ?>" enctype="multipart/form-data">
                <?php
                } else {
                ?>
                <form name="board_form" method="post" action="insert.php" enctype="multipart/form-data">
                <?php
                }
                ?>
                    <div id="write_form">
                        <div class="write_line"></div>
                        
                        <div id="write_row1">
                            <div class="col1">별명</div>
                            <div class="col2">
                                <?php echo htmlspecialchars($user_nick, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <div class="col3">
                                <input type="checkbox" name="html_ok" value="y"> HTML 쓰기
                            </div>
                        </div>
                        
                        <div class="write_line"></div>
                        
                        <div id="write_row2">
                            <div class="col1">제목</div>
                            <div class="col2">
                                <input type="text" name="subject" value="<?php echo htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        
                        <div class="write_line"></div>
                        
                        <div id="write_row3">
                            <div class="col1">내용</div>
                            <div class="col2">
                                <textarea rows="15" cols="79" name="content" required><?php echo htmlspecialchars($item_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="write_line"></div>
                        
                        <div id="write_row4">
                            <div class="col1">이미지파일1</div>
                            <div class="col2">
                                <input type="file" name="upfile[]" accept="image/*">
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <?php
                        if ($mode == "modify" && !empty($item_file_0)) {
                        ?>
                        <div class="delete_ok">
                            <?php echo htmlspecialchars($item_file_0, ENT_QUOTES, 'UTF-8'); ?> 파일이 등록되어 있습니다.
                            <input type="checkbox" name="del_file[]" value="0"> 삭제
                        </div>
                        <div class="clear"></div>
                        <?php
                        }
                        ?>
                        
                        <div class="write_line"></div>
                        
                        <div id="write_row5">
                            <div class="col1">이미지파일2</div>
                            <div class="col2">
                                <input type="file" name="upfile[]" accept="image/*">
                            </div>
                        </div>
                        
                        <?php
                        if ($mode == "modify" && !empty($item_file_1)) {
                        ?>
                        <div class="delete_ok">
                            <?php echo htmlspecialchars($item_file_1, ENT_QUOTES, 'UTF-8'); ?> 파일이 등록되어 있습니다.
                            <input type="checkbox" name="del_file[]" value="1"> 삭제
                        </div>
                        <div class="clear"></div>
                        <?php
                        }
                        ?>
                        
                        <div class="write_line"></div>
                        <div class="clear"></div>
                        
                        <div id="write_row6">
                            <div class="col1">이미지파일3</div>
                            <div class="col2">
                                <input type="file" name="upfile[]" accept="image/*">
                            </div>
                        </div>
                        
                        <?php
                        if ($mode == "modify" && !empty($item_file_2)) {
                        ?>
                        <div class="delete_ok">
                            <?php echo htmlspecialchars($item_file_2, ENT_QUOTES, 'UTF-8'); ?> 파일이 등록되어 있습니다.
                            <input type="checkbox" name="del_file[]" value="2"> 삭제
                        </div>
                        <div class="clear"></div>
                        <?php
                        }
                        ?>
                        
                        <div class="write_line"></div>
                        <div class="clear"></div>
                    </div>
                    
                    <div id="write_button">
                        <input type="image" src="../img/ok.png" alt="확인">&nbsp;
                        <a href="list.php"><img src="../img/list.png" alt="목록"></a>
                    </div>
                </form>
            </div> <!-- end of col2 -->
        </div> <!-- end of content -->
    </div> <!-- end of wrap -->
</body>
</html>
