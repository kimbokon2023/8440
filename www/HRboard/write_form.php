<?php
/**
 * HRboard 인사교육총무 작성/수정 폼 페이지
 * HRboard 게시글을 작성하거나 수정합니다.
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
$user_nick = $_SESSION["nick"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$id = $_REQUEST["id"] ?? '';
$fileorimage = $_REQUEST["fileorimage"] ?? '';
$item = $_REQUEST["item"] ?? '';
$upfilename = $_REQUEST["upfilename"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$savetitle = $_REQUEST["savetitle"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$title_message = '인사/교육/총무';
$timekey = '';

// 게시글 데이터 변수 초기화
$item_subject = '';
$is_html = '';
$content = '';
$qnacheck = '';
$division = '';
$searchtext = '';

// Google Drive 관련 변수 초기화
$saveimagename_arr = array();
$savefilename_arr = array();

// 입력 검증
if (empty($tablename)) {
    ?>
    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="utf-8">
        <title>오류</title>
    </head>
    <body>
        <script>
            alert('잘못된 접근입니다. (tablename 누락)');
            window.close();
        </script>
    </body>
    </html>
    <?php
    exit;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode === "modify") {
    if (empty($num)) {
        ?>
        <!DOCTYPE html>
        <html lang="ko">
        <head>
            <meta charset="utf-8">
            <title>오류</title>
        </head>
        <body>
            <script>
                alert('잘못된 접근입니다. (num 누락)');
                window.close();
            </script>
        </body>
        </html>
        <?php
        exit;
    }
    
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count < 1) {
            ?>
            <!DOCTYPE html>
            <html lang="ko">
            <head>
                <meta charset="utf-8">
                <title>오류</title>
            </head>
            <body>
                <script>
                    alert('게시글을 찾을 수 없습니다.');
                    window.close();
                </script>
            </body>
            </html>
            <?php
            exit;
        } else {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            $item_subject = $row["subject"] ?? '';
            $is_html = $row["is_html"] ?? '';
            $content = $row["content"] ?? '';
            $qnacheck = $row["qnacheck"] ?? '';
            $division = $row["division"] ?? '';
            $searchtext = $row["searchtext"] ?? '';
        }
        
    } catch (PDOException $ex) {
        error_log("DB query error in HRboard/write_form.php: " . $ex->getMessage());
        ?>
        <!DOCTYPE html>
        <html lang="ko">
        <head>
            <meta charset="utf-8">
            <title>오류</title>
        </head>
        <body>
            <script>
                alert('게시글 조회 중 오류가 발생했습니다.');
                window.close();
            </script>
        </body>
        </html>
        <?php
        exit;
    }
}

// ID 설정
$id = $num;

// Google Drive 첨부파일 정보 로드
require_once getDocumentRoot() . '/load_GoogleDriveSecond.php';

include getDocumentRoot() . '/load_header.php';
?>

<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

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
        
        .d-flex.mt-3.mb-1.justify-content-center.align-items-center .badge {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* 작성자/구분/제목 영역 최적화 */
        .d-flex.mt-2.mb-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-2.mb-1.justify-content-center.align-items-center .card {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .card-body .row {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        .card-body .d-flex.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem 0 !important;
        }
        
        .card-body .d-flex.mt-2.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem 0 !important;
        }
        
        .card-body .d-flex.mt-2.justify-content-center.align-items-center span,
        .card-body .d-flex.mt-2.justify-content-center.align-items-center input {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
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
        
        .note-editor.note-frame {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .note-editor .note-editing-area {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .note-editor .note-editable {
            width: 100% !important;
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .note-toolbar {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
        }
        
        .note-toolbar .note-btn-group {
            flex-wrap: wrap !important;
        }
        
        /* Summernote 초기화 시 모바일 크기 조정 */
        #summernote {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* 파일/이미지 업로드 버튼 최적화 */
        .d-flex.mt-3.mb-1.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-1.justify-content-center label,
        .d-flex.mt-1.mb-1.justify-content-center label {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            text-align: center !important;
        }
        
        /* 이미지 표시 영역 최적화 */
        #displayImage {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        #displayImage .row {
            margin: 0.5rem 0 !important;
        }
        
        #displayImage .col {
            width: 100% !important;
            max-width: 100% !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }
        
        #displayImage img {
            max-width: 100% !important;
            width: auto !important;
            height: auto !important;
        }
        
        #displayImage button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        /* 파일 표시 영역 최적화 */
        #displayFile {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        #displayFile .row {
            margin: 0.5rem 0 !important;
        }
        
        #displayFile .d-flex {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        
        #displayFile a {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 100% !important;
        }
        
        #displayFile button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th, input, textarea, select {
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

<?php include getDocumentRoot() . '/common/modal.php'; ?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <!-- 전달함수 설정 input hidden -->
    <input type="hidden" id="tablename" name="tablename" value="<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="item" name="item" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="mode" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="timekey" name="timekey" value="<?php echo htmlspecialchars($timekey, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="searchtext" name="searchtext" value="<?php echo htmlspecialchars($searchtext, ENT_QUOTES, 'UTF-8'); ?>">
    
    <div class="container">
        <div class="d-flex mt-3 mb-1 justify-content-center align-items-center">
            <span class="badge bg-success text-white fs-5">&nbsp;&nbsp;<?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?>&nbsp;&nbsp;</span>
        </div>
        
        <div class="d-flex mt-2 mb-1 justify-content-center align-items-center">
            <div class="card mt-2" style="width:60%;">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex justify-content-center align-items-center">
                            작성자 : &nbsp; <?php echo htmlspecialchars($user_nick, ENT_QUOTES, 'UTF-8'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        
                        <div class="d-flex mt-2 justify-content-center align-items-center">
                            <span class="form-control me-2" style="width: 50px;border:0px;">구분</span>
                            <input id="division" name="division" type="text" class="form-control me-2" style="width:200px;" autocomplete="off" value="<?php echo htmlspecialchars($division, ENT_QUOTES, 'UTF-8'); ?>">&nbsp;
                            <span class="form-control me-2" style="width: 50px;border:0px;">제목</span>
                            <input id="subject" name="subject" type="text" required class="form-control" style="width:500px;" autocomplete="off" value="<?php echo htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8'); ?>">&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex mt-1 mb-1 justify-content-start align-items-center">
            <button type="button" class="btn btn-dark btn-sm me-1" onclick="self.close();">
                &times; 닫기
            </button>
            <button type="button" class="btn btn-dark btn-sm" id="saveBtn">
                <i class="bi bi-floppy-fill"></i> 저장
            </button>
        </div>
        
        <div class="d-flex mt-3 mb-1 justify-content-center">
            <textarea id="summernote" name="content" rows="20"><?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        
        <div class="d-flex mt-3 mb-1 justify-content-center">
            <label for="upfile" class="input-group-text btn btn-outline-primary btn-sm">파일(10M 이하) pdf파일 첨부</label>
            <input id="upfile" name="upfile[]" type="file" onchange="this.value" multiple style="display:none">
        </div>
        
        <div id="displayFile" class="d-flex mt-1 mb-1 justify-content-center" style="display:none;"></div>
        <div id="displayImage" class="row d-flex mt-1 mb-1 justify-content-center" style="display:none;"></div>
        
        <div class="d-flex mt-1 mb-1 justify-content-center">
            <label for="upfileimage" class="input-group-text btn btn-outline-dark btn-sm">사진 첨부</label>
            <input id="upfileimage" name="upfileimage[]" type="file" onchange="this.value" multiple accept=".gif, .jpg, .png" style="display:none">
        </div>
    </div>
</form>

<script>
(function() {
    'use strict';
    
    $(document).ready(function() {
        // 모바일 환경 감지
        var isMobile = window.innerWidth <= 768;
        
        // Summernote 초기화
        var summernoteConfig = {
            placeholder: '내용 작성',
            maximumImageFileSize: 1920 * 5000,
            tabsize: 2,
            height: isMobile ? 300 : 500,
            width: isMobile ? '100%' : 1200,
            toolbar: isMobile ? [
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']]
            ] : [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        };
        
        $('#summernote').summernote(summernoteConfig);
        
        // 창 크기 변경 시 Summernote 크기 조정
        $(window).on('resize', function() {
            var isMobileNow = window.innerWidth <= 768;
            if (isMobileNow !== isMobile) {
                isMobile = isMobileNow;
                $('#summernote').summernote('destroy');
                summernoteConfig.height = isMobile ? 300 : 500;
                summernoteConfig.width = isMobile ? '100%' : 1200;
                summernoteConfig.toolbar = isMobile ? [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']]
                ] : [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ];
                $('#summernote').summernote(summernoteConfig);
            }
        });
        
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
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
            }
            self.close();
        });
        
        // 저장 버튼
        $('#saveBtn').on('click', function() {
            Fninsert();
        });
        
        // 첨부파일 및 이미지 로드
        displayFileLoad();
        displayImageLoad();
    });
    
    /**
     * 자료 삽입/수정 함수
     */
    window.Fninsert = function() {
        console.log($('#mode').val());
        
        // Summernote에서 내용 가져오기
        var content = $('#summernote').summernote('code');
        
        // HTML 문자열을 DOM 요소로 변환
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        
        // 텍스트 추출
        var elements = tempDiv.querySelectorAll('p, b');
        var extractedTexts = [];
        
        for (var i = 0; i < elements.length; i++) {
            extractedTexts.push(elements[i].textContent);
        }
        
        var extractedText = extractedTexts.join(',');
        
        console.log('extractedTexts:', extractedTexts);
        $('#searchtext').val(extractedText);
        
        // 입력 검증
        var subject = $('#subject').val();
        if (!subject || subject.trim() === '') {
            Toastify({
                text: '제목을 입력해주세요.',
                duration: 2000,
                close: true,
                gravity: 'top',
                position: 'center',
                backgroundColor: '#ff5f5f'
            }).showToast();
            return;
        }
        
        if (typeof showMsgModal === 'function') {
            showMsgModal(2); // 파일저장중
        }
        
        var form = $('#board_form')[0];
        var data = new FormData(form);
        
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: 'insert.php',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                
                if (data.success) {
                    Toastify({
                        text: '저장완료',
                        duration: 1500,
                        close: true,
                        gravity: 'top',
                        position: 'center',
                        style: {
                            background: 'linear-gradient(to right, #00b09b, #96c93d)'
                        }
                    }).showToast();
                    
                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            window.opener.location.reload();
                        }
                    }, 1000);
                    
                    var num = data['num'];
                    var tablename = data['tablename'];
                    
                    setTimeout(function() {
                        if (typeof hideMsgModal === 'function') {
                            hideMsgModal();
                        }
                        location.href = 'view.php?page=1&num=' + encodeURIComponent(num) + 
                                      '&tablename=' + encodeURIComponent(tablename);
                    }, 1000);
                } else {
                    if (typeof hideMsgModal === 'function') {
                        hideMsgModal();
                    }
                    Toastify({
                        text: data.message || '저장 중 오류가 발생했습니다.',
                        duration: 3000,
                        close: true,
                        gravity: 'top',
                        position: 'center',
                        backgroundColor: '#ff5f5f'
                    }).showToast();
                }
            },
            error: function(jqxhr, status, error) {
                console.error(jqxhr, status, error);
                if (typeof hideMsgModal === 'function') {
                    hideMsgModal();
                }
                Toastify({
                    text: '저장 중 오류가 발생했습니다.',
                    duration: 3000,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#ff5f5f'
                }).showToast();
            }
        });
    };
    
    /**
     * 마지막 문자 제거 함수
     * @param {string} str - 문자열
     * @return {string} 마지막 문자가 제거된 문자열
     */
    window.deleteLastchar = function(str) {
        return str.substr(0, str.length - 1);
    };
    
    // 첨부파일 업로드 처리
    $('#upfile').on('change', function(e) {
        if (this.files.length === 0) {
            console.warn('파일이 선택되지 않았습니다.');
            return;
        }
        
        var form = $('#board_form')[0];
        var data = new FormData(form);
        
        data.append('tablename', $('#tablename').val());
        data.append('item', 'attached');
        data.append('upfilename', 'upfile');
        data.append('folderPath', '미래기업/uploads');
        data.append('DBtable', 'picuploads');
        
        if (typeof showMsgModal === 'function') {
            showMsgModal(2); // 파일저장중
        }
        
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: '/filedrive/fileprocess.php',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log('응답 데이터:', response);
                
                var successCount = 0;
                var errorCount = 0;
                var errorMessages = [];
                
                if (Array.isArray(response)) {
                    response.forEach(function(item) {
                        if (item.status === 'success') {
                            successCount++;
                        } else if (item.status === 'error') {
                            errorCount++;
                            errorMessages.push('파일: ' + item.file + ', 메시지: ' + item.message);
                        }
                    });
                }
                
                if (successCount > 0) {
                    Toastify({
                        text: successCount + '개의 파일이 성공적으로 업로드되었습니다.',
                        duration: 2000,
                        close: true,
                        gravity: 'top',
                        position: 'center',
                        backgroundColor: '#4fbe87'
                    }).showToast();
                }
                
                if (errorCount > 0) {
                    Toastify({
                        text: '오류 발생: ' + errorCount + '개의 파일 업로드 실패\n상세 오류: ' + errorMessages.join('\n'),
                        duration: 5000,
                        close: true,
                        gravity: 'top',
                        position: 'center',
                        backgroundColor: '#f44336'
                    }).showToast();
                }
                
                setTimeout(function() {
                    displayFile();
                    if (typeof hideMsgModal === 'function') {
                        hideMsgModal();
                    }
                }, 1000);
            },
            error: function(jqxhr, status, error) {
                console.error('업로드 실패:', jqxhr, status, error);
                if (typeof hideMsgModal === 'function') {
                    hideMsgModal();
                }
            }
        });
    });
    
    // 첨부 이미지 업로드 처리
    $('#upfileimage').on('change', function(e) {
        if (this.files.length === 0) {
            console.warn('파일이 선택되지 않았습니다.');
            return;
        }
        
        var form = $('#board_form')[0];
        var data = new FormData(form);
        
        data.append('tablename', $('#tablename').val());
        data.append('item', 'image');
        data.append('upfilename', 'upfileimage');
        data.append('folderPath', '미래기업/uploads');
        data.append('DBtable', 'picuploads');
        
        if (typeof showMsgModal === 'function') {
            showMsgModal(1); // 이미지저장중
        }
        
        $.ajax({
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: '/filedrive/fileprocess.php',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log('응답 데이터:', response);
                
                var successCount = 0;
                var errorCount = 0;
                var errorMessages = [];
                
                if (Array.isArray(response)) {
                    response.forEach(function(item) {
                        if (item.status === 'success') {
                            successCount++;
                        } else if (item.status === 'error') {
                            errorCount++;
                            errorMessages.push('파일: ' + item.file + ', 메시지: ' + item.message);
                        }
                    });
                }
                
                if (successCount > 0) {
                    Toastify({
                        text: successCount + '개의 파일이 성공적으로 업로드되었습니다.',
                        duration: 2000,
                        close: true,
                        gravity: 'top',
                        position: 'center',
                        backgroundColor: '#4fbe87'
                    }).showToast();
                }
                
                if (errorCount > 0) {
                    Toastify({
                        text: '오류 발생: ' + errorCount + '개의 파일 업로드 실패\n상세 오류: ' + errorMessages.join('\n'),
                        duration: 5000,
                        close: true,
                        gravity: 'top',
                        position: 'center',
                        backgroundColor: '#f44336'
                    }).showToast();
                }
                
                setTimeout(function() {
                    displayImage();
                    if (typeof hideMsgModal === 'function') {
                        hideMsgModal();
                    }
                }, 1000);
            },
            error: function(jqxhr, status, error) {
                console.error('업로드 실패:', jqxhr, status, error);
                if (typeof hideMsgModal === 'function') {
                    hideMsgModal();
                }
            }
        });
    });
    
    /**
     * 화면에서 저장한 첨부 파일 불러오기
     */
    window.displayFile = function() {
        $('#displayFile').show();
        var params = $('#timekey').val() ? $('#timekey').val() : $('#num').val();
        
        if (!params) {
            console.error('ID 값이 없습니다. 파일을 불러올 수 없습니다.');
            alert('ID 값이 유효하지 않습니다. 다시 시도해주세요.');
            return;
        }
        
        console.log('요청 ID:', params);
        
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'GET',
            data: {
                num: params,
                tablename: $('#tablename').val(),
                item: 'attached',
                folderPath: '미래기업/uploads'
            },
            dataType: 'json'
        }).done(function(data) {
            console.log('파일 데이터:', data);
            
            $('#displayFile').html('');
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(fileData, index) {
                    var realName = fileData.realname || '다운로드 파일';
                    var link = fileData.link || '#';
                    var fileId = fileData.fileId || null;
                    
                    if (!fileId) {
                        console.error('fileId가 누락되었습니다. index: ' + index, fileData);
                        $('#displayFile').append(
                            '<div class="text-danger">파일 ID가 누락되었습니다.</div>'
                        );
                        return;
                    }
                    
                    var fileStyle = window.innerWidth <= 768 
                        ? "width:100%; max-width:100%; word-wrap:break-word; overflow-wrap:break-word; padding:0.5rem;"
                        : "";
                    var buttonStyle = window.innerWidth <= 768 
                        ? "width:100%; max-width:100%; margin:0.5rem 0;"
                        : "";
                    
                    $('#displayFile').append(
                        '<div class="row mt-1 mb-2">' +
                            '<div class="d-flex align-items-center justify-content-center" style="flex-direction: ' + (window.innerWidth <= 768 ? 'column' : 'row') + '; gap: 0.5rem; ' + fileStyle + '">' +
                                '<span id="file' + index + '" style="word-wrap:break-word; overflow-wrap:break-word; max-width:100%;">' +
                                    '<a href="#" onclick="popupCenter(\'' + link + '\', \'filePopup\', 800, 600); return false;" style="word-wrap:break-word; overflow-wrap:break-word; max-width:100%;">' + realName + '</a>' +
                                '</span>' +
                                '<button type="button" class="btn btn-danger btn-sm" id="delFile' + index + '" onclick="delFileFn(\'' + index + '\', \'' + fileId + '\')" style="' + buttonStyle + '">' +
                                    '<i class="bi bi-trash"></i> 삭제' +
                                '</button>' +
                            '</div>' +
                        '</div>'
                    );
                });
            } else {
                $('#displayFile').append(
                    '<div class="text-center text-muted">No files</div>'
                );
            }
        }).fail(function(error) {
            console.error('파일 불러오기 오류:', error);
            Swal.fire({
                title: '파일 불러오기 실패',
                text: '파일을 불러오는 중 문제가 발생했습니다.',
                icon: 'error',
                confirmButtonText: '확인'
            });
        });
    };
    
    /**
     * 기존 파일 불러오기 (Google Drive)
     */
    window.displayFileLoad = function() {
        $('#displayFile').show();
        var data = <?php echo json_encode($savefilename_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        $('#displayFile').html('');
        
        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function(fileData, i) {
                var realName = fileData.realname || '다운로드 파일';
                var link = fileData.link || '#';
                var fileId = fileData.fileId || null;
                
                if (!fileId) {
                    console.error('fileId가 누락되었습니다. index: ' + i, fileData);
                    return;
                }
                
                var fileStyle = window.innerWidth <= 768 
                    ? "width:100%; max-width:100%; word-wrap:break-word; overflow-wrap:break-word; padding:0.5rem;"
                    : "";
                var buttonStyle = window.innerWidth <= 768 
                    ? "width:100%; max-width:100%; margin:0.5rem 0;"
                    : "";
                
                $('#displayFile').append(
                    '<div class="row mb-3">' +
                        '<div class="d-flex mb-3 align-items-center justify-content-center" style="flex-direction: ' + (window.innerWidth <= 768 ? 'column' : 'row') + '; gap: 0.5rem; ' + fileStyle + '">' +
                            '<span id="file' + i + '" style="word-wrap:break-word; overflow-wrap:break-word; max-width:100%;">' +
                                '<a href="#" onclick="popupCenter(\'' + link + '\', \'filePopup\', 800, 600); return false;" style="word-wrap:break-word; overflow-wrap:break-word; max-width:100%;">' + realName + '</a>' +
                            '</span>' +
                            '<button type="button" class="btn btn-danger btn-sm" id="delFile' + i + '" onclick="delFileFn(\'' + i + '\', \'' + fileId + '\')" style="' + buttonStyle + '">' +
                                '<i class="bi bi-trash"></i> 삭제' +
                            '</button>' +
                        '</div>' +
                    '</div>'
                );
            });
        } else {
            $('#displayFile').append(
                '<div class="text-center text-muted">No files</div>'
            );
        }
    };
    
    /**
     * 파일 삭제 함수
     * @param {string} divID - div ID
     * @param {string} fileId - Google Drive 파일 ID
     */
    window.delFileFn = function(divID, fileId) {
        Swal.fire({
            title: '파일 삭제 확인',
            text: '정말 삭제하시겠습니까?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '삭제',
            cancelButtonText: '취소',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/filedrive/fileprocess.php',
                    type: 'DELETE',
                    data: JSON.stringify({
                        fileId: fileId,
                        tablename: $('#tablename').val(),
                        item: 'attached',
                        folderPath: '미래기업/uploads',
                        DBtable: 'picuploads'
                    }),
                    contentType: 'application/json',
                    dataType: 'json'
                }).done(function(response) {
                    if (response.status === 'success') {
                        console.log('삭제 완료:', response);
                        $('#file' + divID).remove();
                        $('#delFile' + divID).remove();
                        
                        Swal.fire({
                            title: '삭제 완료',
                            text: '파일이 성공적으로 삭제되었습니다.',
                            icon: 'success',
                            confirmButtonText: '확인'
                        });
                    } else {
                        console.log(response.message);
                    }
                }).fail(function(error) {
                    console.error('삭제 중 오류:', error);
                    Swal.fire({
                        title: '삭제 실패',
                        text: '파일 삭제 중 문제가 발생했습니다.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                });
            }
        });
    };
    
    /**
     * 첨부된 이미지 불러오기
     */
    window.displayImage = function() {
        $('#displayImage').show();
        var params = $('#timekey').val() ? $('#timekey').val() : $('#num').val();
        
        if (!params) {
            console.error('ID 값이 없습니다. 파일을 불러올 수 없습니다.');
            alert('ID 값이 유효하지 않습니다. 다시 시도해주세요.');
            return;
        }
        
        console.log('요청 ID:', params);
        
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'GET',
            data: {
                num: params,
                tablename: $('#tablename').val(),
                item: 'image',
                folderPath: '미래기업/uploads'
            },
            dataType: 'json'
        }).done(function(data) {
            console.log('파일 데이터:', data);
            
            $('#displayImage').html('');
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(fileData, index) {
                    var realName = fileData.realname || '다운로드 파일';
                    var thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
                    var link = fileData.link || '#';
                    var fileId = fileData.fileId || null;
                    
                    if (!fileId) {
                        console.error('fileId가 누락되었습니다. index: ' + index, fileData);
                        $('#displayImage').append(
                            '<div class="text-danger">파일 ID가 누락되었습니다.</div>'
                        );
                        return;
                    }
                    
                    var imgStyle = window.innerWidth <= 768 
                        ? "max-width:100%; width:100%; height:auto; display:block; margin:0 auto;"
                        : "width:150px; height:auto;";
                    var buttonStyle = window.innerWidth <= 768 
                        ? "width:100%; max-width:100%; margin:0.5rem 0;"
                        : "";
                    
                    $('#displayImage').append(
                        '<div class="row mb-3">' +
                            '<div class="col d-flex align-items-center justify-content-center" style="flex-direction: ' + (window.innerWidth <= 768 ? 'column' : 'row') + '; gap: 0.5rem;">' +
                                '<a href="#" onclick="popupCenter(\'' + link + '\', \'imagePopup\', 800, 600); return false;">' +
                                    '<img id="image' + index + '" src="' + thumbnail + '" style="' + imgStyle + '" class="img-fluid" alt="Image">' +
                                '</a>' +
                                '<button type="button" class="btn btn-danger btn-sm" id="delImage' + index + '" onclick="delImageFn(\'' + index + '\', \'' + fileId + '\')" style="' + buttonStyle + '">' +
                                    '<i class="bi bi-trash"></i> 삭제' +
                                '</button>' +
                            '</div>' +
                        '</div>'
                    );
                });
            } else {
                $('#displayImage').append(
                    '<div class="text-center text-muted">No files</div>'
                );
            }
        }).fail(function(error) {
            console.error('파일 불러오기 오류:', error);
            Swal.fire({
                title: '파일 불러오기 실패',
                text: '파일을 불러오는 중 문제가 발생했습니다.',
                icon: 'error',
                confirmButtonText: '확인'
            });
        });
    };
    
    /**
     * 기존 이미지 불러오기 (Google Drive)
     */
    window.displayImageLoad = function() {
        $('#displayImage').show();
        var data = <?php echo json_encode($saveimagename_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        $('#displayImage').html('');
        
        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function(fileData, i) {
                var realName = fileData.realname || '다운로드 파일';
                var thumbnail = fileData.thumbnail || '/assets/default-thumbnail.png';
                var link = fileData.link || '#';
                var fileId = fileData.fileId || null;
                
                if (!fileId) {
                    console.error('fileId가 누락되었습니다. index: ' + i, fileData);
                    return;
                }
                
                var imgStyle = window.innerWidth <= 768 
                    ? "max-width:100%; width:100%; height:auto; display:block; margin:0 auto;"
                    : "width:150px; height:auto;";
                var buttonStyle = window.innerWidth <= 768 
                    ? "width:100%; max-width:100%; margin:0.5rem 0;"
                    : "";
                
                $('#displayImage').append(
                    '<div class="row mb-3">' +
                        '<div class="col d-flex align-items-center justify-content-center" style="flex-direction: ' + (window.innerWidth <= 768 ? 'column' : 'row') + '; gap: 0.5rem;">' +
                            '<a href="#" onclick="popupCenter(\'' + link + '\', \'imagePopup\', 800, 600); return false;">' +
                                '<img id="image' + i + '" src="' + thumbnail + '" style="' + imgStyle + '" class="img-fluid" alt="Image">' +
                            '</a>' +
                            '<button type="button" class="btn btn-danger btn-sm" id="delImage' + i + '" onclick="delImageFn(\'' + i + '\', \'' + fileId + '\')" style="' + buttonStyle + '">' +
                                '<i class="bi bi-trash"></i> 삭제' +
                            '</button>' +
                        '</div>' +
                    '</div>'
                );
            });
        } else {
            $('#displayImage').append(
                '<div class="text-center text-muted">No files</div>'
            );
        }
    };
    
    /**
     * 이미지 삭제 함수
     * @param {string} divID - div ID
     * @param {string} fileId - Google Drive 파일 ID
     */
    window.delImageFn = function(divID, fileId) {
        Swal.fire({
            title: '이미지 삭제 확인',
            text: '정말 삭제하시겠습니까?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '삭제',
            cancelButtonText: '취소',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/filedrive/fileprocess.php',
                    type: 'DELETE',
                    data: JSON.stringify({
                        fileId: fileId,
                        tablename: $('#tablename').val(),
                        item: 'image',
                        folderPath: '미래기업/uploads',
                        DBtable: 'picuploads'
                    }),
                    contentType: 'application/json',
                    dataType: 'json'
                }).done(function(response) {
                    if (response.status === 'success') {
                        console.log('삭제 완료:', response);
                        $('#image' + divID).remove();
                        $('#delImage' + divID).remove();
                        
                        Swal.fire({
                            title: '삭제 완료',
                            text: '파일이 성공적으로 삭제되었습니다.',
                            icon: 'success',
                            confirmButtonText: '확인'
                        });
                    } else {
                        console.log(response.message);
                    }
                }).fail(function(error) {
                    console.error('삭제 중 오류:', error);
                    Swal.fire({
                        title: '삭제 실패',
                        text: '파일 삭제 중 문제가 발생했습니다.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                });
            }
        });
    };
})();
</script>

</body>
</html>
