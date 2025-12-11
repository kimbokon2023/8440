<?php
/**
 * 공지사항 상세 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once includePath('load_GoogleDrive.php');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$admin = ($_SESSION["level"] ?? 999) === 1 ? '1' : '0';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $WebSite = "{$protocol}://{$host}/";
    header("Location: {$WebSite}login/login_form.php");
    exit;
}

// 기본 변수 초기화
$title_message = '공지사항';

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '1';
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : '';
$menu = isset($_REQUEST["menu"]) ? $_REQUEST["menu"] : '';

// 필수 파라미터 검증
if (empty($num)) {
    error_log("게시글 조회 실패: num이 비어있음");
    die("오류: 게시글 번호가 지정되지 않았습니다.");
}

if (empty($tablename)) {
    error_log("게시글 조회 실패: tablename이 비어있음");
    die("오류: 테이블명이 지정되지 않았습니다.");
}

// 기타 변수 초기화
$id = $num;
$item = '';
$mode = '';
$timekey = time();

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mt-3.mb-2.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-2.justify-content-center h5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.p-1.m-1.mt-2.mb-2.justify-content-left.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.p-1.m-1.mt-2.mb-2.justify-content-left.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 제목/정보 영역 최적화 */
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.75rem !important;
            margin: 0.5rem 0 !important;
        }
        
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary .col-7,
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary .col-5 {
            width: 100% !important;
            max-width: 100% !important;
            text-align: left !important;
            margin: 0.25rem 0 !important;
            padding: 0.25rem 0 !important;
        }
        
        /* 본문 내용 최적화 */
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-left {
            padding: 0.75rem !important;
            margin: 0.5rem 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 이미지 최적화 */
        #displayImage img {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
        }
        
        #displayImage .row {
            margin: 0.5rem 0 !important;
        }
        
        #displayImage .d-flex {
            justify-content: center !important;
        }
        
        /* 댓글 카드 최적화 */
        .row.p-1.m-1.mt-1.mb-1.justify-content-center.align-items-center .card {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.5rem 0 !important;
        }
        
        .row.p-1.m-1.mt-1.mb-1.justify-content-center.align-items-center .card-body {
            padding: 0.75rem !important;
        }
        
        .row.p-1.m-1.mt-1.mb-1.justify-content-center.align-items-center .card-body span {
            display: block !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 댓글 입력 폼 최적화 */
        .row.p-1.m-1.mt-1.mb-1.justify-content-center .card {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center.align-items-center .d-flex {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            width: 100% !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center.align-items-center .badge {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            padding: 0.5rem !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center.align-items-center textarea {
            width: 100% !important;
            max-width: 100% !important;
            min-height: 80px !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .row.d-flex.mt-3.mb-1.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>

</head>
<body>

<?php include includePath('common/modal.php'); ?>

<?php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 게시글 정보 조회 및 초기화
$item_id = '';
$item_name = '';
$item_nick = '';
$item_hit = 0;
$item_date = '';
$item_subject = '';
$item_content = '';
$is_html = '';
$noticecheck = '';
$noticecheck_memo = '';
$author_id = '';

try {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $num = $row["num"];
        $item_id = $row["id"];
        $item_name = $row["name"];
        $item_nick = $row["nick"];
        $item_hit = $row["hit"];
        $item_date = $row["regist_day"];
        $item_date = substr($item_date, 0, 10);
        $item_subject = $row["subject"];
        $item_content = $row["content"];
        $is_html = $row["is_html"];
        $noticecheck = $row["noticecheck"];
        
        if ($noticecheck == 'y') {
            $noticecheck_memo = '(전체공지)';
        } else {
            $noticecheck_memo = '';
        }
        
        if ($is_html == 'y') {
            $item_content = htmlspecialchars_decode($item_content);
        }
        
        $item_content = str_replace("\r", "<br>", $item_content);
        
        // 조회수 증가
        $new_hit = $item_hit + 1;
        try {
            $pdo->beginTransaction();
            $sql = "UPDATE {$DB}.{$tablename} SET hit = ? WHERE num = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $new_hit, PDO::PARAM_INT);
            $stmh->bindValue(2, $num, PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
        } catch (PDOException $ex) {
            $pdo->rollBack();
            error_log("조회수 증가 오류 (num: {$num}): " . $ex->getMessage());
        }
    } else {
        error_log("게시글을 찾을 수 없음 (num: {$num})");
        die("오류: 게시글을 찾을 수 없습니다.");
    }
        
        $author_id = $item_id;
        
        // Google Drive 파일 정보 불러오기
        require_once includePath('load_GoogleDriveSecond.php');
    
} catch (PDOException $ex) {
    error_log("게시글 조회 오류 (num: {$num}): " . $ex->getMessage());
    die("오류: 게시글을 불러오는 중 문제가 발생했습니다.");
}
?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?= htmlspecialchars($timekey, ENT_QUOTES, 'UTF-8') ?>">
</form>

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="d-flex mt-3 mb-2 justify-content-center">
                <h5><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></h5>
            </div>
            
            <div class="d-flex p-1 m-1 mt-2 mb-2 justify-content-left align-items-center">
                <button type="button" id="closeBtn" class="btn btn-dark btn-sm me-1">&times; 닫기</button>
                <?php
                // 삭제 수정은 관리자와 글쓴이만 가능토록 함
                if (isset($_SESSION["userid"])) {
                    if ($_SESSION["userid"] == $item_id || $_SESSION["userid"] == "admin" || $_SESSION["level"] === 1) {
                ?>
                <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&mode=modify&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>'">
                    <i class="bi bi-pencil-square"></i> 수정
                </button>
                <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'">
                    <i class="bi bi-pencil"></i> 신규
                </button>
                <button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>')">
                    <i class="bi bi-trash"></i> 삭제
                </button>
                <?php
                    }
                }
                ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white align-items-center">
                        <div class="col-7 text-start fw-bold fs-6"><?= $item_subject ?></div>
                        <div class="col-5 text-end">
                            <?= htmlspecialchars($noticecheck_memo, ENT_QUOTES, 'UTF-8') ?> |
                            <?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?> |
                            조회 : <?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?> |
                            <?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    </div>
                    
                    <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-left">
                        <?= $item_content ?>
                    </div>
                    
                    <div id="displayImage"></div>
                </div>
            </div>
            
            <div class="card" id="displayFile" style="display:none;"></div>
        </div>
        
        <div class="row p-1 m-1 mt-1 mb-1 justify-content-center align-items-center">
            <?php
            try {
                $sql = "SELECT * FROM {$DB}.notice_ripple WHERE parent = ?";
                $stmh1 = $pdo->prepare($sql);
                $stmh1->bindValue(1, $num, PDO::PARAM_STR);
                $stmh1->execute();
                
                while ($row_ripple = $stmh1->fetch(PDO::FETCH_ASSOC)) {
                    $ripple_num = $row_ripple["num"];
                    $ripple_id = $row_ripple["id"];
                    $ripple_nick = $row_ripple["nick"];
                    $ripple_content = str_replace("\n", "", $row_ripple["content"]);
                    $ripple_date = $row_ripple["regist_day"];
            ?>
            <div class="card" style="width:80%">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <span class="mt-1 mb-2">
                            ▶ &nbsp;&nbsp; <?= htmlspecialchars($ripple_content, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?> ✔&nbsp;&nbsp;
                            작성자 : <?= htmlspecialchars($ripple_nick, ENT_QUOTES, 'UTF-8') ?> |
                            <?= htmlspecialchars($ripple_date, ENT_QUOTES, 'UTF-8') ?>
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            <?php
                            if (isset($_SESSION["userid"])) {
                                if ($_SESSION["userid"] == "admin" || $_SESSION["userid"] == $ripple_id || $_SESSION["level"] === 1) {
                                    echo "<button type='button' class='btn btn-danger btn-sm' onclick='rippledelete(\"" . htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($num, ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($ripple_num, ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') . "\")'> <i class='bi bi-trash'></i> </button>";
                                }
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php
                }
            } catch (PDOException $ex) {
                error_log("댓글 조회 오류 (num: {$num}): " . $ex->getMessage());
                echo "<div class='alert alert-danger'>오류: 댓글을 불러오는 중 문제가 발생했습니다.</div>";
            }
            ?>
        </div>
        
        <form id="ripple_form" name="ripple_form" method="post" action="insert_ripple.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">
            <div class="row p-1 m-1 mt-1 mb-1 justify-content-center">
                <div class="card" style="width:80%">
                    <div class="row">
                        <div class="card-body">
                            <div class="row d-flex mt-3 mb-1 justify-content-center align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary text-center fs-6 me-1" style="width:10%;">
                                        <i class="bi bi-reply-fill"></i> 댓글
                                    </span>
                                    <textarea rows="1" class="form-control me-1" name="ripple_content" required></textarea>
                                    <button type="button" class="btn btn-dark btn-sm" style="width:15%;" onclick="document.getElementById('ripple_form').submit();">
                                        <i class="bi bi-floppy-fill"></i> 댓글 저장
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var userId = <?php echo json_encode($user_id, JSON_UNESCAPED_UNICODE); ?>;
    var authorId = <?php echo json_encode($author_id, JSON_UNESCAPED_UNICODE); ?>;
    var admin = <?php echo json_encode($admin, JSON_UNESCAPED_UNICODE); ?>;
    var saveimagenameArr = <?php echo json_encode($saveimagename_arr, JSON_UNESCAPED_UNICODE); ?>;
    var savefilenameArr = <?php echo json_encode($savefilename_arr, JSON_UNESCAPED_UNICODE); ?>;
    
    $(document).ready(function() {
        $("#closeBtn1").click(function() {
            $("#closeBtn").click();
        });
        
        $("#closeBtn").click(function() {
            self.close();
        });
        
        displayFileLoad();
        displayImageLoad();
    });
    
    /**
     * 삭제 함수
     */
    window.del = function(href) {
        if (userId !== authorId && admin !== '1') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '삭제불가',
                    text: "작성자와 관리자만 삭제가능합니다.",
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            } else {
                alert('작성자와 관리자만 삭제가능합니다.');
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '자료 삭제',
                    text: "삭제는 신중! 정말 삭제하시겠습니까?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '삭제',
                    cancelButtonText: '취소'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "delete.php",
                            type: "post",
                            data: $("#board_form").serialize(),
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                if (typeof Toastify !== 'undefined') {
                                    Toastify({
                                        text: "파일 삭제완료",
                                        duration: 2000,
                                        close: true,
                                        gravity: "top",
                                        position: "center",
                                        style: {
                                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                                        }
                                    }).showToast();
                                }
                                setTimeout(function() {
                                    if (window.opener && !window.opener.closed) {
                                        if (typeof window.opener.restorePageNumber === 'function') {
                                            window.opener.restorePageNumber();
                                        }
                                        window.opener.location.reload();
                                        window.close();
                                    }
                                }, 1000);
                            },
                            error: function(jqxhr, status, error) {
                                console.error(status, error);
                            }
                        });
                    }
                });
            } else {
                if (confirm('정말 삭제하시겠습니까?')) {
                    $.ajax({
                        url: "delete.php",
                        type: "post",
                        data: $("#board_form").serialize(),
                        dataType: "json",
                        success: function(data) {
                            alert('삭제되었습니다.');
                            if (window.opener && !window.opener.closed) {
                                window.opener.location.reload();
                                window.close();
                            }
                        },
                        error: function(jqxhr, status, error) {
                            console.error(status, error);
                        }
                    });
                }
            }
        }
    };
    
    /**
     * 댓글 삭제 함수
     */
    window.rippledelete = function(tablename, num, rippleNum, page) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '댓글 삭제',
                text: "정말 삭제하시겠습니까?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '삭제',
                cancelButtonText: '취소'
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = "delete_ripple.php?tablename=" + encodeURIComponent(tablename) +
                        "&num=" + encodeURIComponent(num) +
                        "&ripple_num=" + encodeURIComponent(rippleNum) +
                        "&page=" + encodeURIComponent(page);
                }
            });
        } else {
            if (confirm('정말 삭제하시겠습니까?')) {
                window.location.href = "delete_ripple.php?tablename=" + encodeURIComponent(tablename) +
                    "&num=" + encodeURIComponent(num) +
                    "&ripple_num=" + encodeURIComponent(rippleNum) +
                    "&page=" + encodeURIComponent(page);
            }
        }
    };
    
    /**
     * 기존 이미지 화면에 보여주기
     */
    function displayImageLoad() {
        $('#displayImage').show();
        
        $("#displayImage").html('');
        
        if (Array.isArray(saveimagenameArr) && saveimagenameArr.length > 0) {
            saveimagenameArr.forEach(function(pic, index) {
                var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
                var realName = pic.realname || '다운로드 파일';
                var link = pic.link || '#';
                var fileId = pic.fileId || null;
                
                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, pic);
                    return;
                }
                
                var imgStyle = window.innerWidth <= 768 
                    ? "max-width:100%; width:100%; height:auto; display:block; margin:0 auto;"
                    : "width:300px; height:auto;";
                
                $("#displayImage").append(
                    "<div class='row mt-2 mb-1'>" +
                        "<div class='d-flex justify-content-center mt-1 mb-1'>" +
                            "<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
                                "<img id='pic" + index + "' src='" + thumbnail + "' style='" + imgStyle + "' class='img-fluid' alt='Image'>" +
                            "</a>" +
                        "</div>" +
                    "</div>"
                );
            });
        }
    }
    
    /**
     * 기존 파일 불러오기 (Google Drive에서 가져오기)
     */
    function displayFileLoad() {
        $('#displayFile').show();
        
        $("#displayFile").html('');
        
        if (Array.isArray(savefilenameArr) && savefilenameArr.length > 0) {
            savefilenameArr.forEach(function(fileData, i) {
                var realName = fileData.realname || '다운로드 파일';
                var link = fileData.link || '#';
                var fileId = fileData.fileId || null;
                
                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + i, fileData);
                    return;
                }
                
                $("#displayFile").append(
                    "<div class='row mb-3'>" +
                        "<div id='file" + i + "' class='col d-flex align-items-center justify-content-center'>" +
                            "<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" +
                                realName +
                            "</a> &nbsp; &nbsp; " +
                        "</div>" +
                    "</div>"
                );
            });
        } else {
            $("#displayFile").append(
                "<div class='text-center text-muted'>No attached files</div>"
            );
        }
    }
    
})();
</script>

</body>
</html>
