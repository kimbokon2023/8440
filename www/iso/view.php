<?php
/**
 * ISO 상세보기 페이지
 * ISO 게시글의 상세 내용을 표시합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// Google Drive 로드 (세션 등 포함)
require_once getDocumentRoot() . '/load_GoogleDrive.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$admin = $_SESSION["admin"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';

// 변수 초기화
$title_message = 'ISO 9001/14001';
$item = '';
$mode = '';
$timekey = '';
$searchtext = '';

// 게시글 데이터 변수 초기화
$item_num = '';
$item_id = '';
$item_name = '';
$item_nick = '';
$item_subject = '';
$item_content = '';
$item_date = '';
$item_hit = 0;
$is_html = '';
$noticecheck_memo = '';

// Google Drive 관련 변수 초기화
$saveimagename_arr = array();
$savefilename_arr = array();

// 입력 검증
if (empty($num)) {
    ?>
    <script>
        alert('잘못된 접근입니다. (num 누락)');
        window.close();
    </script>
    <?php
    exit;
}

if (empty($tablename)) {
    ?>
    <script>
        alert('잘못된 접근입니다. (tablename 누락)');
        window.close();
    </script>
    <?php
    exit;
}

include getDocumentRoot() . '/load_header.php';
?>

<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

<style>
/* 모바일 환경 최적화 */
@media (max-width: 768px) {
    /* 컨테이너 최적화 */
    .container,
    .container-fluid {
        padding: 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 최적화 */
    .card {
        margin: 0.5rem auto !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 버튼 그룹 최적화 */
    .d-flex.justify-content-left,
    .d-flex.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
        flex-wrap: wrap !important;
    }
    
    .d-flex.justify-content-left .btn,
    .d-flex.justify-content-center .btn {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 제목 및 정보 영역 최적화 */
    .row.d-flex {
        flex-direction: column !important;
        margin: 0.5rem 0 !important;
    }
    
    .row.d-flex .col-7,
    .row.d-flex .col-5 {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem !important;
        text-align: left !important;
    }
    
    .row.d-flex .col-5 {
        margin-top: 0.5rem !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 모든 텍스트 요소 강제 줄바꿈 */
    p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        display: inline !important;
        overflow: visible !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 이미지 최적화 */
    img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        object-fit: contain !important;
    }
    
    #displayImage img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
    }
    
    /* 모달 최적화 */
    .modal {
        padding: 0 !important;
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
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        overflow-y: auto !important;
        flex: 1 1 auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-shrink: 0 !important;
    }
    
    /* '기간' 버튼 숨기기 */
    #showdate {
        display: none !important;
    }
    
    /* jQuery DataTable 숨기기 */
    .dataTables_length,
    .dataTables_filter {
        display: none !important;
    }
    
    /* 테이블을 카드 형식으로 변환 (혹시 있을 경우를 대비) */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.table thead {
        display: none !important;
    }
    
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.table tbody tr {
        display: block !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        margin: 0.5rem auto 0.75rem auto !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        padding: 0.75rem !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    table.table tbody tr td::before {
        content: attr(data-label) !important;
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-right: 0.5rem !important;
        min-width: 80px !important;
        flex-shrink: 0 !important;
    }
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
    .d-flex.justify-content-left .btn,
    .d-flex.justify-content-center .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
}
</style>

</head>
<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php
// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 게시글 조회
try {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        ?>
        <script>
            alert('게시글을 찾을 수 없습니다.');
            window.close();
        </script>
        <?php
        exit;
    }
    
    $item_num = $row["num"];
    $item_id = $row["id"] ?? '';
    $item_name = $row["name"] ?? '';
    $item_nick = $row["nick"] ?? '';
    $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
    $item_content = $row["content"] ?? '';
    $item_date = $row["regist_day"] ?? '';
    $item_date = substr($item_date, 0, 10);
    $item_hit = $row["hit"] ?? 0;
    $is_html = $row["is_html"] ?? '';
    
} catch (PDOException $ex) {
    error_log("DB query error in iso/view.php: " . $ex->getMessage());
    ?>
    <script>
        alert('게시글 조회 중 오류가 발생했습니다.');
        window.close();
    </script>
    <?php
    exit;
}

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
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Hit update error in iso/view.php: " . $ex->getMessage());
    // 조회수 증가 실패는 계속 진행
}

// ID 설정
$id = $num;
$author_id = $item_id;

// Google Drive 첨부파일 정보 로드
require_once getDocumentRoot() . '/load_GoogleDriveSecond.php';
?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="tablename" name="tablename" value="<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="item" name="item" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="mode" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?php echo htmlspecialchars($timekey, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="searchtext" name="searchtext" value="<?php echo htmlspecialchars($searchtext, ENT_QUOTES, 'UTF-8'); ?>">
</form>

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="d-flex mt-3 mb-4 justify-content-center">
                <h5><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></h5>
            </div>
            
            <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-left align-items-center">
                <button type="button" id="closeBtn" class="btn btn-dark btn-sm me-1">
                    &times; 닫기
                </button>
                
                <?php
                // 삭제 수정은 관리자와 글쓴이만 가능
                if ($user_id === $item_id || $user_id === "admin" || $level === 1) {
                    $modifyUrl = 'write_form.php?' . http_build_query(array(
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
                    
                    $newUrl = 'write_form.php?' . http_build_query(array('tablename' => $tablename));
                    $deleteUrl = 'delete.php?' . http_build_query(array('tablename' => $tablename, 'num' => $num, 'page' => $page));
                ?>
                <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='<?php echo htmlspecialchars($modifyUrl, ENT_QUOTES, 'UTF-8'); ?>'">
                    <i class="bi bi-pencil-square"></i> 수정
                </button>
                <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='<?php echo htmlspecialchars($newUrl, ENT_QUOTES, 'UTF-8'); ?>'">
                    <i class="bi bi-pencil"></i> 신규
                </button>
                <button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('<?php echo htmlspecialchars($deleteUrl, ENT_QUOTES, 'UTF-8'); ?>')">
                    <i class="bi bi-trash"></i> 삭제
                </button>
                <?php } ?>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white align-items-center">
                        <div class="col-7 text-start fw-bold fs-6" style="word-wrap: break-word; overflow-wrap: break-word;"><?php echo $item_subject; ?></div>
                        <div class="col-5 text-end" style="word-wrap: break-word; overflow-wrap: break-word;">
                            <?php echo htmlspecialchars($noticecheck_memo, ENT_QUOTES, 'UTF-8'); ?> | 
                            <?php echo htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8'); ?> | 
                            조회 : <?php echo htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8'); ?> | 
                            <?php echo htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                    
                    <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-left">
                        <?php echo $item_content; ?>
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
(function() {
    'use strict';
    
    $(document).ready(function() {
        // Summernote 초기화 및 비활성화
        if (typeof $('#summernote').summernote === 'function') {
            $('#summernote').summernote({
                placeholder: '게시글 내용을 작성하세요!',
                tabsize: 2,
                height: 800
            });
            $('#summernote').summernote('disable');
        }
        
        // 모달 닫기 버튼
        $('#closeModalBtn').on('click', function() {
            $('#myModal').modal('hide');
        });
        
        // 하단 복사 버튼
        $('#closeBtn1').on('click', function() {
            $('#closeBtn').click();
        });
        
        // 창닫기 버튼
        $('#closeBtn').on('click', function() {
            self.close();
        });
        
        // 첨부파일 및 이미지 로드
        displayFileLoad();
        displayImageLoad();
        
        // 모바일 환경에서 '기간' 버튼 숨기기
        if (window.innerWidth <= 768) {
            $('#showdate').hide();
        }
        
        // 창 크기 변경 시 '기간' 버튼 표시/숨김 처리
        $(window).resize(function() {
            if (window.innerWidth <= 768) {
                $('#showdate').hide();
            } else {
                $('#showdate').show();
            }
        });
        
        // 모바일 환경에서 jQuery DataTable 컨트롤 숨기기
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        }
        
        // 창 크기 변경 시 DataTable 컨트롤 표시/숨김 처리
        $(window).resize(function() {
            if (window.innerWidth <= 768) {
                $('.dataTables_length, .dataTables_filter').hide();
            } else {
                $('.dataTables_length, .dataTables_filter').show();
            }
        });
    });
    
    /**
     * 삭제 함수
     * @param {string} href - 삭제 URL
     */
    window.del = function(href) {
        var user_id = '<?php echo addslashes($user_id); ?>';
        var author_id = '<?php echo addslashes($author_id); ?>';
        var admin = '<?php echo addslashes($admin); ?>';
        
        if (user_id !== author_id && admin !== '1') {
            Swal.fire({
                title: '삭제불가',
                text: '작성자와 관리자만 삭제가능합니다.',
                icon: 'error',
                confirmButtonText: '확인'
            });
        } else {
            Swal.fire({
                title: '자료 삭제',
                text: '삭제는 신중! 정말 삭제하시겠습니까?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '삭제',
                cancelButtonText: '취소'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete.php',
                        type: 'post',
                        data: $('#board_form').serialize(),
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            
                            if (data.success) {
                                Toastify({
                                    text: '파일 삭제완료',
                                    duration: 2000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    style: {
                                        background: 'linear-gradient(to right, #00b09b, #96c93d)'
                                    }
                                }).showToast();
                                
                                setTimeout(function() {
                                    if (window.opener && !window.opener.closed) {
                                        if (typeof window.opener.restorePageNumber === 'function') {
                                            window.opener.restorePageNumber();
                                        }
                                        window.opener.location.reload();
                                    }
                                    window.close();
                                }, 1000);
                            } else {
                                Toastify({
                                    text: data.message || '삭제 중 오류가 발생했습니다.',
                                    duration: 3000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    style: {
                                        background: 'linear-gradient(to right, #ff5f5f, #ff9999)'
                                    }
                                }).showToast();
                            }
                        },
                        error: function(jqxhr, status, error) {
                            console.error(jqxhr, status, error);
                            Toastify({
                                text: '삭제 중 오류가 발생했습니다.',
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                        }
                    });
                }
            });
        }
    };
    
    /**
     * 기존 이미지 화면에 보여주기
     */
    window.displayImageLoad = function() {
        $('#displayImage').show();
        var saveimagename_arr = <?php echo json_encode($saveimagename_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        $('#displayImage').html('');
        
        if (Array.isArray(saveimagename_arr) && saveimagename_arr.length > 0) {
            for (var index = 0; index < saveimagename_arr.length; index++) {
                var pic = saveimagename_arr[index];
                var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
                var realName = pic.realname || '다운로드 파일';
                var link = pic.link || '#';
                var fileId = pic.fileId || null;
                
                if (!fileId) {
                    console.error('fileId가 누락되었습니다. index: ' + index, pic);
                    continue;
                }
                
                $('#displayImage').append(
                    '<div class="row mt-2 mb-1">' +
                        '<div class="d-flex justify-content-center mt-1 mb-1">' +
                            '<a href="#" onclick="popupCenter(\'' + link + '\', \'imagePopup\', 800, 600); return false;">' +
                                '<img id="pic' + index + '" src="' + thumbnail + '" style="width:300px; height:auto;">' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
            }
        }
    };
    
    /**
     * 기존 파일 불러오기 (Google Drive)
     */
    window.displayFileLoad = function() {
        $('#displayFile').show();
        var data = <?php echo json_encode($savefilename_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        $('#displayFile').html('');
        
        if (Array.isArray(data) && data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var fileData = data[i];
                var realName = fileData.realname || '다운로드 파일';
                var link = fileData.link || '#';
                var fileId = fileData.fileId || null;
                
                if (!fileId) {
                    console.error('fileId가 누락되었습니다. index: ' + i, fileData);
                    continue;
                }
                
                $('#displayFile').append(
                    '<div class="row mb-3">' +
                        '<div id="file' + i + '" class="col d-flex align-items-center justify-content-center">' +
                            '<a href="#" onclick="popupCenter(\'' + link + '\', \'filePopup\', 800, 600); return false;">' +
                                realName +
                            '</a> &nbsp; &nbsp; ' +
                        '</div>' +
                    '</div>'
                );
            }
        } else {
            $('#displayFile').append(
                '<div class="text-center text-muted">No attached files</div>'
            );
        }
    };
})();
</script>

</body>
</html>
