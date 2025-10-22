<?php
require_once __DIR__ . '/../bootstrap.php';
require_once includePath('load_GoogleDrive.php');

/**
 * RnD 게시글 상세보기
 * 
 * 연구개발 게시글 상세 내용을 표시하고 첨부파일 다운로드 제공
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$admin = $_SESSION["level"] === 1 ? '1' : '0';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

// 권한 체크
if ($level > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$tablename = $_REQUEST["tablename"] ?? 'RnDlist';
$item = $_REQUEST["item"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$timekey = $_REQUEST["timekey"] ?? '';
$search = $_REQUEST["search"] ?? '';
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';

// 데이터 검증
if (empty($num)) {
    echo "<script>alert('게시글 번호가 없습니다.'); window.close();</script>";
    exit;
}

// 첫 화면 표시 문구
$title_message = '개발진행';

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>

</head>

<body>

<?php include includePath('common/modal.php'); ?>	


<?php
// 게시글 정보 조회
$item_num = '';
$item_id = '';
$item_name = '';
$item_nick = '';
$item_subject = '';
$item_content = '';
$item_date = '';
$item_hit = 0;
$is_html = '';
$division = '';
$searchtext = '';
$noticecheck_memo = '';

try {
    $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $item_num = $row["num"] ?? '';
        $item_id = $row["id"] ?? '';
        $item_name = $row["name"] ?? '';
        $item_nick = $row["nick"] ?? '';
        $item_subject = $row["subject"] ?? '';
        $item_content = $row["content"] ?? '';
        $item_date = $row["regist_day"] ?? '';
        $item_date = substr($item_date, 0, 10);
        $item_hit = $row["hit"] ?? 0;
        $is_html = $row["is_html"] ?? '';
        $division = $row["division"] ?? '';
        $searchtext = $row["searchtext"] ?? '';
    } else {
        echo "<script>alert('게시글을 찾을 수 없습니다.'); window.close();</script>";
        exit;
    }
} catch (PDOException $Exception) {
    error_log("게시글 조회 오류: " . $Exception->getMessage());
    echo "<script>alert('게시글 조회 중 오류가 발생했습니다.'); window.close();</script>";
    exit;
}

// 조회수 증가
$new_hit = $item_hit + 1;
try {
    $pdo->beginTransaction();
    $sql = "update " . $DB . "." . $tablename . " set hit = ? where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $new_hit, PDO::PARAM_INT);
    $stmh->bindValue(2, $num, PDO::PARAM_INT);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    error_log("조회수 증가 오류: " . $Exception->getMessage());
}

// 초기 프로그램은 $num 사용 이후 $id로 수정중임
$id = $num;
$author_id = $item_id;

require_once includePath('load_GoogleDriveSecond.php'); // attached, image에 대한 정보 불러오기
?>



<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?= htmlspecialchars($timekey, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="searchtext" name="searchtext" value="<?= htmlspecialchars($searchtext, ENT_QUOTES, 'UTF-8') ?>">
</form>

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="d-flex mt-3 mb-4 justify-content-center">
                <h5><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></h5>
            </div>
            <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-left align-items-center">
                <button type="button" id="closeBtn" class="btn btn-dark btn-sm me-1">&times; 닫기</button>
                <?php
                // 삭제 수정은 관리자와 글쓴이만 가능토록 함
                if ($user_id == $item_id || $user_id == "admin" || $level === 1) {
                    // URL 파라미터를 배열로 만들어서 안전하게 처리
                    $modifyParams = http_build_query(array(
                        'tablename' => $tablename,
                        'mode' => 'modify',
                        'num' => $num,
                        'page' => $page,
                        'search' => $search,
                        'Bigsearch' => $Bigsearch,
                        'find' => $find,
                        'year' => $year,
                        'process' => $process,
                        'asprocess' => $asprocess,
                        'fromdate' => $fromdate,
                        'todate' => $todate,
                        'separate_date' => $separate_date
                    ));
                ?>
                <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?<?= htmlspecialchars($modifyParams, ENT_QUOTES, 'UTF-8') ?>'">
                    <i class="bi bi-pencil-square"></i> 수정
                </button>
                <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'">
                    <i class="bi bi-pencil"></i> 신규
                </button>
                <button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>')">
                    <i class="bi bi-trash"></i> 삭제
                </button>
                <?php } ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white align-items-center">
                        <div class="col-7 text-start fw-bold fs-6">구분: <?= htmlspecialchars($division, ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="col-5 text-end"><?= htmlspecialchars($noticecheck_memo, ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?> | 조회: <?= $item_hit ?> | <?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    
                    <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-left">
                        <?= $item_content ?>
                    </div>
                </div>
            </div>
            <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-left">
                <div id="displayImage" class="row d-flex mt-1 mb-1 justify-content-center" style="display:none;"></div>
                <div id="displayFile" class="d-flex mt-1 mb-1 justify-content-center" style="display:none;"></div>
            </div>
        </div>
    </div>
</div> 

<script>
$(document).ready(function() {
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    // 하단복사 버튼
    $("#closeBtn1").click(function() {
        $("#closeBtn").click();
    });
    
    $("#closeBtn").click(function() {
        self.close();
    });
});

function del(href) {
    var user_id = '<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>';
    var author_id = '<?php echo htmlspecialchars($author_id, ENT_QUOTES, 'UTF-8'); ?>';
    var admin = '<?php echo htmlspecialchars($admin, ENT_QUOTES, 'UTF-8'); ?>';
    
    if (user_id !== author_id && admin !== '1') {
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
        Swal.fire({
            title: '자료 삭제',
            text: "삭제는 신중! 정말 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "delete.php",
                    type: "post",
                    data: $("#board_form").serialize(),
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
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
                        
                        setTimeout(function() {
                            if (window.opener && !window.opener.closed) {
                                window.opener.restorePageNumber();
                                window.opener.location.reload();
                                window.close();
                            }
                        }, 1000);
                    },
                    error: function(jqxhr, status, error) {
                        console.log(jqxhr, status, error);
                    }
                });
            }
        });
    }
}
</script>

<script>
$(document).ready(function() {
    displayFileLoad();
    displayImageLoad();
});

// 기존 있는 이미지 화면에 보여주기
function displayImageLoad() {
    $('#displayImage').show();
    var saveimagename_arr = <?php echo json_encode($saveimagename_arr ?? []); ?>;
    
    $("#displayImage").html('');
    saveimagename_arr.forEach(function(pic, index) {
        var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
        const realName = pic.realname || '다운로드 파일';
        var link = pic.link || '#';
        var fileId = pic.fileId || null;
        
        if (!fileId) {
            console.error("fileId가 누락되었습니다. index: " + index, pic);
            return;
        }
        
        $("#displayImage").append(
            "<div class='row mt-2 mb-1'>" +
                "<div class='d-flex justify-content-center mt-1 mb-1'>" +
                    "<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
                        "<img id='pic" + index + "' src='" + thumbnail + "' style='width:300px; height:auto;'>" +
                    "</a>" +
                "</div>" +
            "</div>"
        );
    });
}

// 기존 파일 불러오기 (Google Drive에서 가져오기)
function displayFileLoad() {
    $('#displayFile').show();
    var data = <?php echo json_encode($savefilename_arr ?? []); ?>;
    
    $("#displayFile").html('');
    
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(function(fileData, i) {
            const realName = fileData.realname || '다운로드 파일';
            const link = fileData.link || '#';
            const fileId = fileData.fileId || null;
            
            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + i, fileData);
                return;
            }
            
            // 파일 정보 행 추가
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
</script>

</body>
</html>
    