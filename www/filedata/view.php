<?php
/**
 * Concert 게시글 상세보기 페이지
 * 게시글 내용과 첨부 이미지를 표시합니다.
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
$user_level = $_SESSION['level'] ?? '';
$DB = $_SESSION['DB'] ?? 'phptest1';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$item_num = '';
$item_id = '';
$item_name = '';
$item_nick = '';
$item_hit = 0;
$item_date = '';
$item_subject = '';
$item_content = '';
$is_html = '';
$new_hit = 0;

$image_name = array('', '', '');
$image_copied = array('', '', '');
$image_width = array(0, 0, 0);
$image_height = array(0, 0, 0);
$image_type = array(0, 0, 0);

// 파일 디렉토리 설정 (환경에 따라 동적 설정)
$file_dir = __DIR__ . '/../data/';
if (!is_dir($file_dir)) {
    $file_dir = 'C:/xampp/htdocs/data/';
}

// 입력 검증
if (empty($num)) {
    ?>
    <script>
        alert('잘못된 접근입니다.');
        history.back();
    </script>
    <?php
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    // 게시글 정보 조회
    $sql = "SELECT * FROM {$DB}.concert WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        ?>
        <script>
            alert('해당 게시글을 찾을 수 없습니다.');
            location.href = 'list.php';
        </script>
        <?php
        exit;
    }
    
    // 데이터 할당
    $item_num = $row["num"];
    $item_id = $row["id"];
    $item_name = $row["name"];
    $item_nick = $row["nick"];
    $item_hit = $row["hit"];
    
    $image_name[0] = $row["file_name_0"] ?? '';
    $image_name[1] = $row["file_name_1"] ?? '';
    $image_name[2] = $row["file_name_2"] ?? '';
    
    $image_copied[0] = $row["file_copied_0"] ?? '';
    $image_copied[1] = $row["file_copied_1"] ?? '';
    $image_copied[2] = $row["file_copied_2"] ?? '';
    
    $item_date = $row["regist_day"];
    $item_date = substr($item_date, 0, 10);
    $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
    $item_content = $row["content"];
    $is_html = $row["is_html"] ?? '';
    
    // HTML이 아닌 경우 포맷팅
    if ($is_html != "y") {
        $item_content = str_replace(" ", "&nbsp;", $item_content);
        $item_content = str_replace("\n", "<br>", $item_content);
    }
    
    // 조회수 증가
    $new_hit = $item_hit + 1;
    
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE {$DB}.concert SET hit = ? WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $new_hit, PDO::PARAM_INT);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("Hit count update error in view.php: " . $ex->getMessage());
    }
    
} catch (PDOException $ex) {
    error_log("DB query error in view.php: " . $ex->getMessage());
    ?>
    <script>
        alert('데이터 조회 오류가 발생했습니다.');
        location.href = 'list.php';
    </script>
    <?php
    exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/concert.css">
    <title>Concert 게시판 - <?php echo htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8'); ?></title>
    <script>
        function del(href) {
            if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                document.location.href = href;
            }
        }
    </script>
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
                
                <div id="view_comment">&nbsp;</div>
                
                <div id="view_title">
                    <div id="view_title1">
                        <?php echo htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div id="view_title2">
                        <?php echo htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8'); ?> | 
                        조회: <?php echo htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8'); ?> | 
                        <?php echo htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
                
                <div id="view_content">
                    <?php
                    // 첨부 이미지 표시
                    for ($i = 0; $i < 3; $i++) {
                        if (!empty($image_copied[$i])) {
                            $img_path = $file_dir . $image_copied[$i];
                            
                            if (file_exists($img_path)) {
                                $imageinfo = @getimagesize($img_path);
                                
                                if ($imageinfo !== false) {
                                    $image_width[$i] = $imageinfo[0];
                                    $image_height[$i] = $imageinfo[1];
                                    $image_type[$i] = $imageinfo[2];
                                    
                                    $img_name = "../data/" . $image_copied[$i];
                                    
                                    if ($image_width[$i] > 785) {
                                        $image_width[$i] = 785;
                                    }
                                    
                                    // image 타입: 1=gif, 2=jpg, 3=png
                                    if ($image_type[$i] == 1 || $image_type[$i] == 2 || $image_type[$i] == 3) {
                                        echo "<img src='" . htmlspecialchars($img_name, ENT_QUOTES, 'UTF-8') . "' width='{$image_width[$i]}' alt='" . htmlspecialchars($image_name[$i], ENT_QUOTES, 'UTF-8') . "'><br><br>";
                                    }
                                }
                            }
                        }
                    }
                    
                    // 게시글 내용 출력
                    echo $item_content;
                    ?>
                </div>
                
                <div id="view_button">
                    <a href="list.php"><img src="../img/list.png" alt="목록"></a>&nbsp;
                    <?php
                    if (!empty($user_id)) {
                        if ($user_id == $item_id || $user_id == "admin" || $user_level == 1) {
                    ?>
                    <a href="write_form.php?mode=modify&num=<?php echo urlencode($num); ?>">
                        <img src="../img/modify.png" alt="수정">
                    </a>&nbsp;
                    <a href="javascript:del('delete.php?num=<?php echo urlencode($num); ?>')">
                        <img src="../img/delete.png" alt="삭제">
                    </a>&nbsp;
                    <?php
                        }
                    ?>
                    <a href="write_form.php"><img src="../img/write.png" alt="글쓰기"></a>
                    <?php
                    }
                    ?>
                </div>
                
                <div class="clear"></div>
            </div> <!-- end of col2 -->
        </div> <!-- end of content -->
    </div> <!-- end of wrap -->
</body>
</html>
