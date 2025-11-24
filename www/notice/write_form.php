<?php
/**
 * 공지사항 작성/수정 폼 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/load_GoogleDrive.php';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $website = $_SESSION["WebSite"] ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    header("Location: {$website}login/login_form.php");
    exit;
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 기본 변수 초기화
$title_message = '공지사항';

// 요청 변수 초기화
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$fileorimage = isset($_REQUEST["fileorimage"]) ? $_REQUEST["fileorimage"] : '';
$item = isset($_REQUEST["item"]) ? $_REQUEST["item"] : '';
$upfilename = isset($_REQUEST["upfilename"]) ? $_REQUEST["upfilename"] : '';
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : 'notice';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? "";
$is_html = $_REQUEST["is_html"] ?? "";
$noticecheck = $_REQUEST["noticecheck"] ?? "y";

// 수정 모드 시 데이터 변수 초기화
$item_subject = '';
$content = '';
$searchtext = '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count < 1) {
            error_log("게시글 수정 실패: 게시글을 찾을 수 없음 (num: {$num})");
            echo "<script>alert('검색결과가 없습니다.'); window.close();</script>";
            exit;
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            $item_subject = $row["subject"];
            $is_html = $row["is_html"];
            $content = $row["content"];
            $noticecheck = $row["noticecheck"];
        }
    } catch (PDOException $ex) {
        error_log("게시글 조회 오류 (num: {$num}): " . $ex->getMessage());
        echo "<script>alert('오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "'); window.close();</script>";
        exit;
    }
}

// 초기 프로그램은 $num 사용 이후 $id로 수정중임
$id = $num;

// Google Drive 파일 정보 불러오기 전에 변수 초기화
$timekey = time();
$savefilename_arr = [];
$saveimagename_arr = [];

require_once getDocumentRoot() . '/load_GoogleDriveSecond.php';

include getDocumentRoot() . '/load_header.php';
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
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center h5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        /* 작성자/체크박스 영역 최적화 */
        .d-flex.mt-2.mb-1.justify-content-center.align-items-center .card {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .d-flex.mt-2.mb-1.justify-content-center.align-items-center .row {
            width: 100% !important;
            margin: 0 !important;
            flex-direction: column !important;
        }
        
        .d-flex.mt-2.mb-1.justify-content-center.align-items-center .col-3-sm,
        .d-flex.mt-2.mb-1.justify-content-center.align-items-center .col-5-sm {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            margin: 0.25rem 0 !important;
        }
        
        .d-flex.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.justify-content-center.align-items-center > * {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        .d-flex.mt-2.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        /* 체크박스와 라벨 최적화 */
        input[type="checkbox"] {
            margin: 0.25rem 0.5rem !important;
            transform: scale(1.2) !important;
        }
        
        label {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 제목 입력 필드 최적화 */
        .d-flex.mt-2.justify-content-center.align-items-center input#subject {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.mt-1.mb-1.justify-content-start.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-1.mb-1.justify-content-start.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* Summernote 에디터 최적화 */
        .note-editor {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .note-editable {
            width: 100% !important;
            max-width: 100% !important;
            min-height: 300px !important;
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .note-toolbar {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.25rem !important;
        }
        
        .note-toolbar .note-btn-group {
            margin: 0.125rem !important;
        }
        
        .note-toolbar .note-btn {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
        }
        
        /* 파일/이미지 업로드 버튼 최적화 */
        .d-flex.mt-1.mb-1.justify-content-center,
        .d-flex.mt-3.mb-1.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-1.mb-1.justify-content-center label,
        .d-flex.mt-3.mb-1.justify-content-center label {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            text-align: center !important;
        }
        
        /* 이미지/파일 표시 영역 최적화 */
        #displayImage,
        #displayFile {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
        }
        
        #displayImage img {
            max-width: 100% !important;
            width: auto !important;
            height: auto !important;
        }
        
        #displayImage .row,
        #displayFile .row {
            margin: 0.5rem 0 !important;
            width: 100% !important;
        }
        
        #displayImage .d-flex,
        #displayFile .d-flex {
            flex-direction: column !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        #displayImage a,
        #displayFile a {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 100% !important;
        }
        
        #displayImage button,
        #displayFile button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* textarea 최적화 */
        textarea {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th, input, textarea {
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

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <!-- 전달함수 설정 input hidden -->
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?= htmlspecialchars($timekey, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="searchtext" name="searchtext" value="<?= htmlspecialchars($searchtext, ENT_QUOTES, 'UTF-8') ?>">
    
    <div class="container">
        <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">
            <h5>공지사항</h5>
        </div>
        
        <div class="d-flex mt-2 mb-1 justify-content-center align-items-center">
            <div class="card mt-2" style="width:60%;">
                <div class="card-body">
                    <div class="d-flex mt-2 mb-1 justify-content-center align-items-center">
                        <div class="row">
                            <div class="col-3-sm">
                                <div class="d-flex justify-content-center align-items-center">
                                    작성자 : &nbsp; <?= htmlspecialchars($_SESSION["nick"], ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="is_html" value="y" <?php if ($is_html == 'y') echo 'checked'; ?>>&nbsp; HTML 쓰기 &nbsp;
                                    <input type="checkbox" name="noticecheck" value="y" <?php if ($noticecheck == 'y') echo 'checked'; ?>> &nbsp; 전체공지
                                </div>
                            </div>
                            <div class="col-5-sm">
                                <div class="d-flex mt-2 justify-content-center align-items-center">
                                    제목 &nbsp;
                                    <input id="subject" name="subject" type="text" required class="form-control" autocomplete="off" style="width:500px;" <?php if ($mode == "modify") { ?> value="<?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?>" <?php } ?>>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex mt-1 mb-1 justify-content-start align-items-center">
            <button class="btn btn-dark btn-sm me-1" onclick="self.close();">
                <ion-icon name="close-outline"></ion-icon> 창닫기
            </button>
            <button type="button" class="btn btn-dark btn-sm" id="saveBtn">
                <ion-icon name="save-outline"></ion-icon> 저장
            </button>
        </div>
        
        <div class="d-flex justify-content-start align-items-center">
            <textarea id="summernote" name="content" rows="20" required><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        
        <div class="d-flex mt-1 mb-1 justify-content-center">
            <label for="upfileimage" class="input-group-text btn btn-outline-dark btn-sm">사진 첨부</label>
            <input id="upfileimage" name="upfileimage[]" type="file" onchange="this.value" multiple accept=".gif, .jpg, .png" style="display:none">
        </div>
        <div id="displayImage" style="display:none;"></div>
        
        <div class="d-flex mt-3 mb-1 justify-content-center">
            <label for="upfile" class="input-group-text btn btn-outline-primary btn-sm">파일(10M 이하) pdf파일 첨부</label>
            <input id="upfile" name="upfile[]" type="file" onchange="this.value" multiple style="display:none">
        </div>
        
        <div id="displayFile" style="display:none;"></div>
    </div>
</form>

<script type="text/javascript">
(function() {
    'use strict';
    
    var ajaxRequest = null;
    var savefilenameArr = <?php echo json_encode($savefilename_arr, JSON_UNESCAPED_UNICODE); ?>;
    var saveimagenameArr = <?php echo json_encode($saveimagename_arr, JSON_UNESCAPED_UNICODE); ?>;
    
    $(document).ready(function() {
        // 모바일/PC 환경 감지
        var isMobile = window.innerWidth <= 768;
        var editorWidth = isMobile ? '100%' : 1200;
        var editorHeight = isMobile ? 300 : 450;
        var toolbarConfig = isMobile ? [
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ] : [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ];
        
        // Summernote 초기화
        $('#summernote').summernote({
            placeholder: '내용 작성',
            maximumImageFileSize: 1920 * 5000,
            tabsize: 2,
            height: editorHeight,
            width: editorWidth,
            toolbar: toolbarConfig,
            callbacks: {
                onImageUpload: function(files) {
                    if (files.length > 0) {
                        var file = files[0];
                        resizeImage(file, function(resizedImage) {
                            $('#summernote').summernote('insertImage', resizedImage);
                        });
                    }
                }
            }
        });
        
        // 창 크기 변경 시 에디터 크기 조정
        $(window).on('resize', function() {
            var currentIsMobile = window.innerWidth <= 768;
            if (currentIsMobile !== isMobile) {
                isMobile = currentIsMobile;
                var newWidth = isMobile ? '100%' : 1200;
                var newHeight = isMobile ? 300 : 450;
                $('#summernote').summernote('destroy');
                $('#summernote').summernote({
                    placeholder: '내용 작성',
                    maximumImageFileSize: 1920 * 5000,
                    tabsize: 2,
                    height: newHeight,
                    width: newWidth,
                    toolbar: isMobile ? [
                        ['font', ['bold', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']],
                        ['view', ['codeview']]
                    ] : [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onImageUpload: function(files) {
                            if (files.length > 0) {
                                var file = files[0];
                                resizeImage(file, function(resizedImage) {
                                    $('#summernote').summernote('insertImage', resizedImage);
                                });
                            }
                        }
                    }
                });
            }
        });
        
        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });
        
        $("#closeBtn1").click(function() {
            $("#closeBtn").click();
        });
        
        $("#closeBtn").click(function() {
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
            }
            self.close();
        });
        
        $("#saveBtn").click(function() {
            Fninsert();
        });
        
        displayFileLoad();
        displayImageLoad();
        
        // 첨부파일 업로드 처리
        $("#upfile").change(function(e) {
            if (this.files.length === 0) {
                console.warn("파일이 선택되지 않았습니다.");
                return;
            }
            
            var form = $('#board_form')[0];
            var data = new FormData(form);
            
            data.append("tablename", $("#tablename").val());
            data.append("item", "attached");
            data.append("upfilename", "upfile");
            data.append("folderPath", "미래기업/uploads");
            data.append("DBtable", "picuploads");
            
            if (typeof showMsgModal === 'function') {
                showMsgModal(2);
            }
            
            $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "/filedrive/fileprocess.php",
                type: "POST",
                data: data,
                success: function(response) {
                    console.log("응답 데이터:", response);
                    
                    var successCount = 0;
                    var errorCount = 0;
                    var errorMessages = [];
                    
                    response.forEach(function(item) {
                        if (item.status === "success") {
                            successCount++;
                        } else if (item.status === "error") {
                            errorCount++;
                            errorMessages.push("파일: " + item.file + ", 메시지: " + item.message);
                        }
                    });
                    
                    if (successCount > 0) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: successCount + "개의 파일이 성공적으로 업로드되었습니다.",
                                duration: 2000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#4fbe87"
                            }).showToast();
                        }
                    }
                    
                    if (errorCount > 0) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: "오류 발생: " + errorCount + "개의 파일 업로드 실패\n상세 오류: " + errorMessages.join("\n"),
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#f44336"
                            }).showToast();
                        }
                    }
                    
                    setTimeout(function() {
                        displayFile();
                        if (typeof hideMsgModal === 'function') {
                            hideMsgModal();
                        }
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.error("업로드 실패:", jqxhr, status, error);
                }
            });
        });
        
        // 첨부 이미지 업로드 처리
        $("#upfileimage").change(function(e) {
            if (this.files.length === 0) {
                console.warn("파일이 선택되지 않았습니다.");
                return;
            }
            
            var form = $('#board_form')[0];
            var data = new FormData(form);
            
            data.append("tablename", $("#tablename").val());
            data.append("item", "image");
            data.append("upfilename", "upfileimage");
            data.append("folderPath", "미래기업/uploads");
            data.append("DBtable", "picuploads");
            
            if (typeof showMsgModal === 'function') {
                showMsgModal(1);
            }
            
            $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "/filedrive/fileprocess.php",
                type: "POST",
                data: data,
                success: function(response) {
                    console.log("응답 데이터:", response);
                    
                    var successCount = 0;
                    var errorCount = 0;
                    var errorMessages = [];
                    
                    response.forEach(function(item) {
                        if (item.status === "success") {
                            successCount++;
                        } else if (item.status === "error") {
                            errorCount++;
                            errorMessages.push("파일: " + item.file + ", 메시지: " + item.message);
                        }
                    });
                    
                    if (successCount > 0) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: successCount + "개의 파일이 성공적으로 업로드되었습니다.",
                                duration: 2000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#4fbe87"
                            }).showToast();
                        }
                    }
                    
                    if (errorCount > 0) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: "오류 발생: " + errorCount + "개의 파일 업로드 실패\n상세 오류: " + errorMessages.join("\n"),
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#f44336"
                            }).showToast();
                        }
                    }
                    
                    setTimeout(function() {
                        displayImage();
                        if (typeof hideMsgModal === 'function') {
                            hideMsgModal();
                        }
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.error("업로드 실패:", jqxhr, status, error);
                }
            });
        });
    });
    
    /**
     * 이미지 리사이즈 함수
     */
    function resizeImage(file, callback) {
        var reader = new FileReader();
        reader.onloadend = function(e) {
            var tempImg = new Image();
            tempImg.src = reader.result;
            tempImg.onload = function() {
                var MAX_WIDTH = 800;
                var MAX_HEIGHT = 500;
                var tempW = tempImg.width;
                var tempH = tempImg.height;
                
                if (tempW > tempH) {
                    if (tempW > MAX_WIDTH) {
                        tempH *= MAX_WIDTH / tempW;
                        tempW = MAX_WIDTH;
                    }
                } else {
                    if (tempH > MAX_HEIGHT) {
                        tempW *= MAX_HEIGHT / tempH;
                        tempH = MAX_HEIGHT;
                    }
                }
                
                var canvas = document.createElement('canvas');
                canvas.width = tempW;
                canvas.height = tempH;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(tempImg, 0, 0, tempW, tempH);
                var dataURL = canvas.toDataURL("image/jpeg");
                
                callback(dataURL);
            };
        };
        reader.readAsDataURL(file);
    }
    
    /**
     * 자료의 삽입/수정하는 모듈
     */
    window.Fninsert = function() {
        console.log($("#mode").val());
        
        // Summernote 초기화 후
        var content = $('#summernote').summernote('code');
        
        // HTML 문자열을 DOM 요소로 변환
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        
        var elements = tempDiv.querySelectorAll('p, b');
        var extractedTexts = [];
        
        elements.forEach(function(element) {
            extractedTexts.push(element.textContent);
        });
        
        console.log(extractedTexts.join(','));
        
        var extractedText = extractedTexts.join(',');
        console.log('extractedTexts');
        console.log(extractedTexts);
        $("#searchtext").val(extractedText);
        
        var form = $('#board_form')[0];
        var data = new FormData(form);
        
        if (typeof showMsgModal === 'function') {
            showMsgModal(2);
        }
        
        if (ajaxRequest !== null) {
            ajaxRequest.abort();
        }
        
        ajaxRequest = $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "insert.php",
            type: "post",
            data: data,
            dataType: "json",
            success: function(data) {
                setTimeout(function() {
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: "파일 저장완료",
                            duration: 1500,
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
                            window.opener.location.reload();
                        }
                    }, 1000);
                    
                    var num = data["num"];
                    var tablename = data["tablename"];
                    
                    setTimeout(function() {
                        if (typeof hideMsgModal === 'function') {
                            hideMsgModal();
                        }
                        location.href = 'view.php?page=1&num=' + encodeURIComponent(num) + "&tablename=" + encodeURIComponent(tablename);
                    }, 1000);
                }, 1000);
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    };
    
    /**
     * 마지막 문자 제거하는 함수
     */
    window.deleteLastchar = function(str) {
        return str.substr(0, str.length - 1);
    };
    
    /**
     * 화면에서 저장한 첨부된 파일 불러오기
     */
    function displayFile() {
        $('#displayFile').show();
        var params = $("#timekey").val() ? $("#timekey").val() : $("#num").val();
        
        if (!params) {
            console.error("ID 값이 없습니다. 파일을 불러올 수 없습니다.");
            alert("ID 값이 유효하지 않습니다. 다시 시도해주세요.");
            return;
        }
        
        console.log("요청 ID:", params);
        
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'GET',
            data: {
                num: params,
                tablename: $("#tablename").val(),
                item: 'attached',
                folderPath: '미래기업/uploads'
            },
            dataType: 'json'
        }).done(function(data) {
            console.log("파일 데이터:", data);
            
            $("#displayFile").html('');
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(fileData, index) {
                    var realName = fileData.realname || '다운로드 파일';
                    var link = fileData.link || '#';
                    var fileId = fileData.fileId || null;
                    
                    if (!fileId) {
                        console.error("fileId가 누락되었습니다. index: " + index, fileData);
                        $("#displayFile").append(
                            "<div class='text-danger'>파일 ID가 누락되었습니다.</div>"
                        );
                        return;
                    }
                    
                    $("#displayFile").append(
                        "<div class='row mt-1 mb-2'>" +
                            "<div class='d-flex align-items-center justify-content-center'>" +
                                "<span id='file" + index + "'>" +
                                    "<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" + realName + "</a>" +
                                "</span> &nbsp;&nbsp;" +
                                "<button type='button' class='btn btn-danger btn-sm' id='delFile" + index + "' onclick=\"delFileFn('" + index + "', '" + fileId + "')\">" +
                                    "<i class='bi bi-trash'></i>" +
                                "</button>" +
                            "</div>" +
                        "</div>"
                    );
                });
            } else {
                $("#displayFile").append(
                    "<div class='text-center text-muted'>No files</div>"
                );
            }
        }).fail(function(error) {
            console.error("파일 불러오기 오류:", error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "파일 불러오기 실패",
                    text: "파일을 불러오는 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인"
                });
            }
        });
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
                        "<div class='d-flex mb-3 align-items-center justify-content-center'>" +
                            "<span id='file" + i + "'>" +
                                "<a href='#' onclick=\"popupCenter('" + link + "', 'filePopup', 800, 600); return false;\">" + realName + "</a>" +
                            "</span> &nbsp;&nbsp;" +
                            "<button type='button' class='btn btn-danger btn-sm' id='delFile" + i + "' onclick=\"delFileFn('" + i + "', '" + fileId + "')\">" +
                                "<i class='bi bi-trash'></i>" +
                            "</button>" +
                        "</div>" +
                    "</div>"
                );
            });
        } else {
            $("#displayFile").append(
                "<div class='text-center text-muted'>No files</div>"
            );
        }
    }
    
    /**
     * 파일 삭제 처리 함수
     */
    window.delFileFn = function(divID, fileId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: "파일 삭제 확인",
                text: "정말 삭제하시겠습니까?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "삭제",
                cancelButtonText: "취소",
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    deleteFile(divID, fileId);
                }
            });
        } else {
            if (confirm('정말 삭제하시겠습니까?')) {
                deleteFile(divID, fileId);
            }
        }
    };
    
    function deleteFile(divID, fileId) {
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'DELETE',
            data: JSON.stringify({
                fileId: fileId,
                tablename: $("#tablename").val(),
                item: "attached",
                folderPath: "미래기업/uploads",
                DBtable: "picuploads"
            }),
            contentType: "application/json",
            dataType: 'json'
        }).done(function(response) {
            if (response.status === 'success') {
                console.log("삭제 완료:", response);
                $("#file" + divID).remove();
                $("#delFile" + divID).remove();
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "삭제 완료",
                        text: "파일이 성공적으로 삭제되었습니다.",
                        icon: "success",
                        confirmButtonText: "확인"
                    });
                } else {
                    alert('삭제되었습니다.');
                }
            } else {
                console.log(response.message);
            }
        }).fail(function(error) {
            console.error("삭제 중 오류:", error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "삭제 실패",
                    text: "파일 삭제 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인"
                });
            } else {
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    }
    
    /**
     * 첨부된 이미지 불러오기
     */
    function displayImage() {
        $('#displayImage').show();
        var params = $("#timekey").val() ? $("#timekey").val() : $("#num").val();
        
        if (!params) {
            console.error("ID 값이 없습니다. 파일을 불러올 수 없습니다.");
            alert("ID 값이 유효하지 않습니다. 다시 시도해주세요.");
            return;
        }
        
        console.log("요청 ID:", params);
        
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'GET',
            data: {
                num: params,
                tablename: $("#tablename").val(),
                item: 'image',
                folderPath: '미래기업/uploads'
            },
            dataType: 'json'
        }).done(function(data) {
            console.log("파일 데이터:", data);
            
            $("#displayImage").html('');
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(fileData, index) {
                    var realName = fileData.realname || '다운로드 파일';
                    var thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
                    var link = fileData.link || '#';
                    var fileId = fileData.fileId || null;
                    
                    if (!fileId) {
                        console.error("fileId가 누락되었습니다. index: " + index, fileData);
                        $("#displayImage").append(
                            "<div class='text-danger'>파일 ID가 누락되었습니다.</div>"
                        );
                        return;
                    }
                    
                    var imgStyle = window.innerWidth <= 768 
                        ? "max-width:100%; width:100%; height:auto; display:block; margin:0 auto;"
                        : "width:150px; height:auto;";
                    
                    $("#displayImage").append(
                        "<div class='row mb-3'>" +
                            "<div class='col d-flex align-items-center justify-content-center'>" +
                                "<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
                                    "<img id='image" + index + "' src='" + thumbnail + "' style='" + imgStyle + "' class='img-fluid' alt='Image'>" +
                                "</a> &nbsp;&nbsp;" +
                                "<button type='button' class='btn btn-danger btn-sm' id='delImage" + index + "' onclick=\"delImageFn('" + index + "', '" + fileId + "')\">" +
                                    "<i class='bi bi-trash'></i>" +
                                "</button>" +
                            "</div>" +
                        "</div>"
                    );
                });
            } else {
                $("#displayImage").append(
                    "<div class='text-center text-muted'>No files</div>"
                );
            }
        }).fail(function(error) {
            console.error("파일 불러오기 오류:", error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "파일 불러오기 실패",
                    text: "파일을 불러오는 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인"
                });
            }
        });
    }
    
    /**
     * 기존 이미지 불러오기 (Google Drive에서 가져오기)
     */
    function displayImageLoad() {
        $('#displayImage').show();
        
        $("#displayImage").html('');
        
        if (Array.isArray(saveimagenameArr) && saveimagenameArr.length > 0) {
            saveimagenameArr.forEach(function(fileData, i) {
                var realName = fileData.realname || '다운로드 파일';
                var thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
                var link = fileData.link || '#';
                var fileId = fileData.fileId || null;
                
                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + i, fileData);
                    return;
                }
                
                var imgStyle = window.innerWidth <= 768 
                    ? "max-width:100%; width:100%; height:auto; display:block; margin:0 auto;"
                    : "width:150px; height:auto;";
                
                $("#displayImage").append(
                    "<div class='row mb-3'>" +
                        "<div class='col d-flex align-items-center justify-content-center'>" +
                            "<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
                                "<img id='image" + i + "' src='" + thumbnail + "' style='" + imgStyle + "' class='img-fluid' alt='Image'>" +
                            "</a> &nbsp;&nbsp;" +
                            "<button type='button' class='btn btn-danger btn-sm' id='delImage" + i + "' onclick=\"delImageFn('" + i + "', '" + fileId + "')\">" +
                                "<i class='bi bi-trash'></i>" +
                            "</button>" +
                        "</div>" +
                    "</div>"
                );
            });
        } else {
            $("#displayImage").append(
                "<div class='text-center text-muted'>No files</div>"
            );
        }
    }
    
    /**
     * 이미지 삭제 처리 함수
     */
    window.delImageFn = function(divID, fileId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: "이미지 삭제 확인",
                text: "정말 삭제하시겠습니까?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "삭제",
                cancelButtonText: "취소",
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    deleteImage(divID, fileId);
                }
            });
        } else {
            if (confirm('정말 삭제하시겠습니까?')) {
                deleteImage(divID, fileId);
            }
        }
    };
    
    function deleteImage(divID, fileId) {
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'DELETE',
            data: JSON.stringify({
                fileId: fileId,
                tablename: $("#tablename").val(),
                item: "image",
                folderPath: "미래기업/uploads",
                DBtable: "picuploads"
            }),
            contentType: "application/json",
            dataType: 'json'
        }).done(function(response) {
            if (response.status === 'success') {
                console.log("삭제 완료:", response);
                $("#image" + divID).remove();
                $("#delImage" + divID).remove();
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "삭제 완료",
                        text: "파일이 성공적으로 삭제되었습니다.",
                        icon: "success",
                        confirmButtonText: "확인"
                    });
                } else {
                    alert('삭제되었습니다.');
                }
            } else {
                console.log(response.message);
            }
        }).fail(function(error) {
            console.error("삭제 중 오류:", error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "삭제 실패",
                    text: "파일 삭제 중 문제가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인"
                });
            } else {
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    }
    
})();
</script>

</body>
</html>
