<?php
/**
 * Idea 제안 작성/수정 폼 페이지
 * 직원 제안사항을 작성하거나 수정합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once(includePath('session.php'));

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
$menu = $_REQUEST["menu"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$title_message = '개선을 위한 제안하기';
$tablename = 'idea';

// rowDB.php에서 사용될 변수들 사전 초기화
$place = '';
$occur = '';
$errortype = '';
$emember = '';
$content = '';
$method = '';
$filename = '';
$serverfilename = '';
$approve = '';
$payment = '';
$firstone = '';
$imgurl = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 레코드 조회 (수정 모드)
if (!empty($num)) {
    try {
        $sql = "SELECT * FROM {$DB}.idea WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include 'rowDB.php';
            $imgurl = './img/' . $serverfilename;
        }
        
        if ($count < 1) {
            $occur = date("Y-m-d");
        }
        
    } catch (PDOException $ex) {
        error_log("DB query error in idea/write_form.php: " . $ex->getMessage());
    }
} else {
    // 신규 작성 모드
    $occur = date("Y-m-d");
}

include getDocumentRoot() . '/load_header.php';
?>

<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

<style>
.table td,
.table th {
    vertical-align: middle !important;
}

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
        border-radius: 10px !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
        overflow-x: hidden !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* 테이블을 카드 형식으로 변환 */
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        display: block !important;
    }
    
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.table tbody tr {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.5rem 0 !important;
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
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    table.table tbody tr td:first-child {
        font-weight: bold !important;
        font-size: 0.875rem !important;
        color: #333 !important;
        background-color: #f8f9fa !important;
        margin-bottom: 0.25rem !important;
    }
    
    table.table tbody tr td:not(:first-child) {
        padding-left: 1rem !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: 2px solid #ddd !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* data-label이 있는 경우 레이블 표시 */
    table.table tbody tr td[data-label]:not(:first-child)::before {
        content: attr(data-label) ": " !important;
        font-weight: bold !important;
        font-size: 0.875rem !important;
        color: #666 !important;
        display: block !important;
        margin-bottom: 0.25rem !important;
    }
    
    /* 입력 필드 최적화 */
    input[type="text"],
    input[type="date"],
    textarea,
    select.form-control {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        font-size: 1rem !important;
        padding: 0.5rem !important;
        margin: 0.25rem 0 !important;
    }
    
    /* 체크박스 그룹 최적화 */
    .checkbox-inline {
        display: block !important;
        margin: 0.5rem 0 !important;
        width: 100% !important;
    }
    
    .checkbox-inline input[type="checkbox"] {
        margin-right: 0.5rem !important;
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
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.25rem 0 !important;
    }
    
    .d-flex.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    
    /* 제목 최적화 */
    .card-title,
    h4, h5 {
        font-size: 1.125rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        text-align: center !important;
    }
    
    /* 이미지 최적화 */
    img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        object-fit: contain !important;
    }
    
    .imagediv {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }
    
    /* 파일 입력 최적화 */
    input[type="file"] {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
    }
    
    /* d-flex 요소 최적화 */
    .d-flex {
        flex-wrap: wrap !important;
    }
    
    .d-flex.align-items-center {
        flex-direction: column !important;
        align-items: stretch !important;
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
    
    /* SweetAlert2 모달 최적화 */
    .swal2-popup {
        width: 90% !important;
        max-width: 90% !important;
        padding: 1rem !important;
        font-size: 0.875rem !important;
    }
    
    .swal2-title {
        font-size: 1.125rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-content {
        font-size: 0.875rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .swal2-actions {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
    
    .swal2-confirm,
    .swal2-cancel {
        width: 100% !important;
        margin: 0 !important;
    }
    
    /* '기간' 버튼 숨기기 */
    #showdate {
        display: none !important;
    }
    
    /* textarea 최적화 */
    textarea {
        min-height: 100px !important;
        resize: vertical !important;
    }
    
    /* select 최적화 */
    select.form-control {
        width: 100% !important;
        max-width: 100% !important;
    }
}

/* PC 환경 버튼 간격 최적화 */
@media (min-width: 769px) {
    .d-flex.justify-content-center .btn,
    .d-flex.justify-content-start .btn {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }
}
</style>
</head>
<body>

<?php require_once(includePath('common/modal.php')); ?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="mode" name="mode">
    <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    <input type="hidden" id="filedelete" name="filedelete">
    <input type="hidden" id="filename" name="filename" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="serverfilename" name="serverfilename" value="<?php echo htmlspecialchars($serverfilename, ENT_QUOTES, 'UTF-8'); ?>">
    
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 text-center">
                <div class="card align-middle" style="border-radius:20px;">
                    <div class="card">
                        <h4 class="card-title text-center mt-2 mb-2" style="color:#113366;">제안 하기</h4>
                    </div>
                    
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-center" data-label="제안 일시">제안 일시</td>
                                    <td class="text-center w120px" data-label="제안 일시">
                                        <input type="date" id="occur" name="occur" value="<?php echo htmlspecialchars($occur, ENT_QUOTES, 'UTF-8'); ?>" class="form-control w-auto">
                                    </td>
                                    <td class="text-center text-primary fw-bold w100px" data-label="제안 유형">제안 유형</td>
                                    <td class="text-center align-middle" colspan="5" data-label="제안 유형">
                                        <?php
                                        $idea_type_arr = array('비용절감', '작업시간 단축', '안전활동', '업무효율화', '수익증대');
                                        
                                        // 저장된 값이 문자열("!" 구분)인 경우 배열로 변환
                                        if (!empty($errortype)) {
                                            if (!is_array($errortype)) {
                                                $errortype = explode('!', $errortype);
                                            }
                                        } else {
                                            $errortype = array();
                                        }
                                        
                                        foreach ($idea_type_arr as $type):
                                            $checked = in_array($type, $errortype) ? 'checked' : '';
                                        ?>
                                            <label class="checkbox-inline" style="margin-right: 10px;">
                                                <input type="checkbox" name="errortype[]" value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $checked; ?>>
                                                <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-center fw-bold" data-label="제안명">제안명</td>
                                    <td class="text-start w500px" colspan="3" data-label="제안명">
                                        <input type="text" name="place" id="place" class="form-control" value="<?php echo htmlspecialchars($place, ENT_QUOTES, 'UTF-8'); ?>" autofocus>
                                    </td>
                                    <td class="text-center" data-label="최초 제안자">최초 제안자</td>
                                    <td class="text-center w80px" data-label="최초 제안자">
                                        <input type="text" id="firstone" name="firstone" class="form-control" value="<?php echo htmlspecialchars($firstone, ENT_QUOTES, 'UTF-8'); ?>" placeholder="최초 제안자">
                                    </td>
                                    <td class="text-center" data-label="참여자">참여자</td>
                                    <td data-label="참여자">
                                        <input type="text" id="emember" name="emember" class="form-control" value="<?php echo htmlspecialchars($emember, ENT_QUOTES, 'UTF-8'); ?>" placeholder="제안 참여자">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-center text-primary fw-bold align-middle" data-label="제안 내용 (기존 상태)">제안 내용<br>(기존 상태)</td>
                                    <td colspan="8" data-label="제안 내용 (기존 상태)">
                                        <textarea id="content" name="content" class="form-control" rows="3"><?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-center align-middle text-danger fw-bold" data-label="개선 효과">개선 효과<br>(비용절감,<br>수익증대,<br>작업시간 단축 등)</td>
                                    <td colspan="8" data-label="개선 효과">
                                        <textarea id="method" name="method" class="form-control" rows="7"><?php echo htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-center align-middle" data-label="첨부 이미지">첨부 이미지</td>
                                    <td colspan="8" data-label="첨부 이미지">
                                        <div class="d-flex align-items-center">
                                            <span class="text-success w300px"><?php echo $filename ? htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') : '이미지 없음'; ?></span>
                                            <input id="mainbefore" name="mainBefore" type="file" class="form-control ms-3" accept="image/*">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php
                    if (!empty($filename)) {
                        echo '<span class="form-control">';
                        echo '<br>';
                        echo '<div class="imagediv">';
                        echo '<img class="before_work" src="' . htmlspecialchars($imgurl, ENT_QUOTES, 'UTF-8') . '" style="max-width:100%; height:auto;">';
                        echo '</div>';
                        echo '</span>';
                        echo '<br>';
                    }
                    ?>
                    
                    <br>
                    <h5 class="form-signin-heading mt-1 mb-2">제안등급 부여</h5>
                    <div class="d-flex justify-content-center">
                        <?php
                        $isManager = (intval($level) == 1 || intval($level) == 2);
                        $disabledStyle = !$isManager ? 'pointer-events: none; background-color: #e9ecef;' : '';
                        ?>
                        <select id="approve" name="approve" class="form-control text-center" style="width:150px; <?php echo $disabledStyle; ?>">
                            <option value="" <?php echo empty($approve) ? 'selected' : ''; ?>>(선택)</option>
                            <option value="A등급" <?php echo $approve == 'A등급' ? 'selected' : ''; ?>>A등급</option>
                            <option value="B등급" <?php echo $approve == 'B등급' ? 'selected' : ''; ?>>B등급</option>
                            <option value="C등급" <?php echo $approve == 'C등급' ? 'selected' : ''; ?>>C등급</option>
                            <option value="등급보류" <?php echo $approve == '등급보류' ? 'selected' : ''; ?>>등급보류</option>
                        </select>
                    </div>
                    
                    <h5 class="form-signin-heading mt-1 mb-2">포상지급 여부</h5>
                    <div class="d-flex justify-content-center">
                        <select id="payment" name="payment" class="form-control text-center" style="width:150px; <?php echo $disabledStyle; ?>">
                            <option value="" <?php echo empty($payment) ? 'selected' : ''; ?>>(선택)</option>
                            <option value="지급완료" <?php echo $payment == '지급완료' ? 'selected' : ''; ?>>지급완료</option>
                        </select>
                    </div>
                    
                    <div class="d-flex mt-4 mb-5 justify-content-center">
                        <button type="button" id="closeBtn" class="btn btn-dark btn-sm me-1">
                            <ion-icon name="close-outline"></ion-icon> 창닫기
                        </button>
                        
                        <?php
                        // 권한 체크
                        $isAuthorized = (intval($level) == 1 || intval($level) == 2) || 
                                       (strpos($firstone, $user_name) !== false) || 
                                       (strpos($emember, $user_name) !== false);
                        ?>
                        
                        <button id="saveBtn" class="btn btn-dark btn-sm me-1" type="button">
                            <?php
                            if ((int)$num > 0) {
                                echo '<ion-icon name="color-wand-outline"></ion-icon> 결재상신(수정)';
                            } else {
                                echo '<ion-icon name="color-wand-outline"></ion-icon> 결재상신(등록)';
                            }
                            ?>
                        </button>
                        
                        <?php if ((int)$num > 0): ?>
                        <button id="delBtn" class="btn btn-danger btn-sm" type="button" <?php echo !$isAuthorized ? 'disabled style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                            <ion-icon name="trash-outline"></ion-icon> 삭제
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
(function() {
    'use strict';
    
    $(document).ready(function() {
        // 신청일 변경 시 종료일도 변경
        $('#occur').on('change', function() {
            $('#opendate').val($('#occur').val());
        });
        
        // 사진 등록
        $('#regpicBtn').on('click', function() {
            var num = $('#num').val();
            window.open('reg_pic.php?num=' + encodeURIComponent(num), '사진등록', 'width=1200,height=700,top=0,left=0,scrollbars=no');
        });
        
        // 파일 변경 시
        $('#mainbefore').on('change', function(e) {
            var isfile = $('#filename').val();
            var changefile = $('#mainbefore').val();
            
            if (changefile !== '') {
                $('#filename').val('');
            }
        });
        
        // 모달 닫기
        $('#closeModalBtn').on('click', function() {
            $('#myModal').modal('hide');
        });
        
        // 창 닫기
        $('#closeBtn').on('click', function() {
            window.close();
        });
        
        // 저장 버튼
        $('#saveBtn').on('click', function() {
            var num = $('#num').val();
            var approve = $('#approve').val();
            var user_name = $('#user_name').val();
            var reporter = $('#firstone').val();
            var place = $('#place').val();
            
            // 입력 검증
            if (!place || place.trim() === '') {
                Toastify({
                    text: '제안명을 입력해주세요.',
                    duration: 2000,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#ff5f5f'
                }).showToast();
                return;
            }
            
            if (Number(num) > 0) {
                $('#mode').val('modify');
            } else {
                $('#mode').val('insert');
                
                // 특정 사용자는 자동으로 1차결재
                if (user_name === '최장중') {
                    $('#approve').val('1차결재');
                }
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
                                window.opener.location.reload();
                            }
                            window.close();
                        }, 1000);
                    } else {
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
        });
        
        // 삭제 버튼
        $('#delBtn').on('click', function() {
            var num = $('#num').val();
            var reporter = $('#firstone').val();
            var admin = '<?php echo addslashes($admin); ?>';
            var user_name = $('#user_name').val();
            var approve = $('#approve').val();
            
            if ((reporter === user_name && approve === '결재상신') || 
                (admin === '1') || 
                user_name === '김보곤' || 
                user_name === '최장중') {
                
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
                        $('#mode').val('delete');
                        
                        $.ajax({
                            url: 'insert.php',
                            type: 'post',
                            data: $('#board_form').serialize(),
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                
                                if (data.success) {
                                    Toastify({
                                        text: '삭제 완료',
                                        duration: 2000,
                                        close: true,
                                        gravity: 'top',
                                        position: 'center',
                                        style: {
                                            background: 'linear-gradient(to right, #ff5f5f, #ff9999)'
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
                                        backgroundColor: '#ff5f5f'
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
            } else {
                Swal.fire({
                    title: '삭제불가',
                    text: '작성자와 관리자만 삭제가능합니다.',
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            }
        });
        
        // textarea 자동 높이 조절
        var adjustHeight = function(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        };
        
        var contentTextarea = document.getElementById('content');
        var methodTextarea = document.getElementById('method');
        
        if (contentTextarea) {
            contentTextarea.addEventListener('input', function() {
                adjustHeight(this);
            });
            adjustHeight(contentTextarea);
        }
        
        if (methodTextarea) {
            methodTextarea.addEventListener('input', function() {
                adjustHeight(this);
            });
            adjustHeight(methodTextarea);
        }
    });
    
    /**
     * 사진 삭제 함수
     * @param {string} delChoice - 삭제할 위치
     */
    window.delPic = function(delChoice) {
        if (delChoice === 'before') {
            $('#filedelete').val('before');
        }
        if (delChoice === 'after') {
            $('#filedelete').val('after');
        }
        
        document.getElementById('board_form').submit();
    };
    
    /**
     * 사진 삭제 함수 (개별)
     * @param {string} divID - div ID
     * @param {string} delChoice - 삭제할 파일명
     */
    window.delPicFn = function(divID, delChoice) {
        console.log(divID, delChoice);
        
        $.ajax({
            url: 'delpic.php?picname=' + encodeURIComponent(delChoice),
            type: 'post',
            data: $('mainFrm').serialize(),
            dataType: 'json'
        }).done(function(data) {
            var picname = data['picname'];
            console.log(data);
            
            $('#pic' + divID).remove();
            $('#delPic' + divID).remove();
            $('#pInput').val('');
        });
    };
})();
</script>

</body>
</html>
