<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$menu = $_REQUEST["menu"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    // 로컬/서버 환경에 따른 동적 리디렉션
    $loginUrl = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
        ? 'http://localhost/mirae8440/login/login_form.php'
        : 'https://8440.co.kr/login/login_form.php';
    
    sleep(1);
    header("Location:" . $loginUrl);
    exit;
}

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

?>

<?php include getDocumentRoot() . '/load_header.php'; ?>

<title>부적합 개선을 위한 품질분임조</title>

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
    
    table.table tbody tr th {
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
        font-weight: bold !important;
        font-size: 0.875rem !important;
        color: #333 !important;
        background-color: #f8f9fa !important;
        margin-bottom: 0.25rem !important;
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
        padding-left: 1rem !important;
    }
    
    table.table tbody tr td:last-child {
        border-bottom: 2px solid #ddd !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* 입력 필드 최적화 */
    input[type="text"],
    input[type="datetime-local"],
    textarea,
    select.form-control {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        font-size: 1rem !important;
        padding: 0.5rem !important;
        margin: 0.25rem 0 !important;
    }
    
    /* d-flex 요소 최적화 */
    .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
        flex-wrap: wrap !important;
    }
    
    .d-flex.align-items-center {
        flex-direction: column !important;
        align-items: stretch !important;
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
    
    /* select 최적화 */
    select.form-control {
        width: 100% !important;
        max-width: 100% !important;
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
    
    /* label 최적화 */
    label {
        display: block !important;
        margin-bottom: 0.25rem !important;
        width: 100% !important;
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

<?php
// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// rowDB.php에서 사용되는 변수 초기화
$place = '';
$emember = '';
$errortype = '';
$occur = '';
$content = '';
$method = '';
$filename = '';
$serverfilename = '';
$approve = '';
$imgurl = '';

// 부적합 유형 배열 불러오기
$sql = "SELECT * FROM {$DB}.errortype";
$errortype_arr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($errortype_arr, $row["errortype"]);
    }
} catch (PDOException $ex) {
    error_log("부적합 유형 조회 오류: " . $ex->getMessage());
}

$errortype_arr = array_unique($errortype_arr);
sort($errortype_arr);

// 기존 데이터 조회
if ($num != '') {
    try {
        $sql = "SELECT * FROM {$DB}.emeeting WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include 'rowDB.php';
            $imgurl = './img/' . $serverfilename;
        }
    } catch (PDOException $ex) {
        error_log("회의록 조회 오류: " . $ex->getMessage());
    }
}

?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="mode" name="mode">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num) ?>">
    <input type="hidden" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name) ?>">
    <input type="hidden" id="filedelete" name="filedelete">
    <input type="hidden" id="filename" name="filename" value="<?= htmlspecialchars($filename) ?>">
    <input type="hidden" id="serverfilename" name="serverfilename" value="<?= htmlspecialchars($serverfilename) ?>">
    
    <div class="container h-30">
        <div class="row d-flex justify-content-center align-items-center h-30">
            <div class="col-12 text-center">
                <div class="card align-middle" style="border-radius:20px;">
                    <div class="card" style="padding:6px; margin:7px;">
                        <h4 class="card-title text-center" style="color:#113366;">개선을 위한 품질분임조 활동기록</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-center align-middle w-25">현장명</th>
                                    <td class="d-flex align-items-center">
                                        <input type="text" name="place" id="place" class="form-control" 
                                               value="<?= htmlspecialchars($place) ?>" autofocus>
                                        <button type="button" id="searchPlaceBtn" class="btn btn-outline-dark btn-sm ms-2">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">참석자</th>
                                    <td>
                                        <input type="text" id="emember" name="emember" class="form-control" 
                                               value="<?= htmlspecialchars($emember) ?>" placeholder="회의 참석자">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">부적합 유형</th>
                                    <td class="d-flex align-items-center">
                                        <select name="errortype" id="errortype" class="form-control w120px">
                                            <?php for ($i = 0; $i < count($errortype_arr); $i++): ?>
                                                <option value="<?= htmlspecialchars($errortype_arr[$i]) ?>" 
                                                        <?= $errortype == $errortype_arr[$i] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($errortype_arr[$i]) ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                        <button type="button" id="registerrortypeBtn" class="btn btn-outline-primary btn-sm ms-2">
                                            부적합유형 등록
                                        </button>
                                        <div class="ms-4 d-flex align-items-center">
                                            <label for="occur" class="me-2">회의 일시</label>
                                            <input type="datetime-local" id="occur" name="occur" 
                                                   value="<?= htmlspecialchars($occur) ?>" class="form-control w-auto">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">첨부 이미지</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-success w300px"><?= $filename ?: '이미지 없음' ?></span>
                                            <input id="mainbefore" name="mainBefore" type="file" class="form-control ms-3">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center text-primary fw-bold align-middle">부적합/개선 현상 및 내용</th>
                                    <td>
                                        <textarea id="content" name="content" class="form-control" rows="7"><?= htmlspecialchars($content) ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle text-danger fw-bold">개선대책/향후 계획 등</th>
                                    <td>
                                        <textarea id="method" name="method" class="form-control" rows="7"><?= htmlspecialchars($method) ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php
                    if ($filename != null && $imgurl != '') {
                        echo '<span class="form-control">';
                        echo '<br>';
                        echo '<div class="imagediv">';
                        echo '<img class="before_work" src="' . htmlspecialchars($imgurl) . '" style="width:100%;height:100%" alt="첨부이미지">';
                        echo '</div></span><br>';
                    }
                    ?>
                    
                    <br>
                    <h5 class="form-signin-heading mt-1 mb-2">결재 상태</h5>
                    <div class="d-flex justify-content-center">
                        <input type="text" id="approve" name="approve" style="width:150px;" 
                               class="form-control text-center" readonly value="<?= htmlspecialchars($approve) ?>">
                    </div>
                    
                    <div class="d-flex mt-4 mb-4 justify-content-center">
                        <button id="saveBtn" class="btn btn-dark btn-sm me-1" type="button">
                            <?php
                            if ((int)$num > 0) {
                                echo '<i class="bi bi-pencil-square"></i> 결재상신(수정)';
                            } else {
                                echo '<i class="bi bi-floppy-fill"></i> 결재상신(등록)';
                            }
                            ?>
                        </button>
                        
                        <?php if ((int)$num > 0) { ?>
                            <button id="delBtn" class="btn btn-danger btn-sm" type="button">
                                <i class="bi bi-trash"></i> 삭제
                            </button>
                            <button type="button" id="closeBtn" class="btn btn-dark btn-sm ms-5">
                                &times; 닫기
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</body>
</html>

<script>
// ES5 호환 JavaScript

function displayPicture() {
    $('#displayPicture').show();
    var params = $("#num").val();
    
    $.ajax({
        url: 'loadpic.php?num=' + params,
        type: 'post',
        data: $("mainFrm").serialize(),
        dataType: 'json'
    }).done(function(data) {
        var recnum = data["recnum"];
        $("#displayPicture").html('');
        
        for (var i = 0; i < recnum; i++) {
            $("#displayPicture").append("<img id='pic" + i + "' src='img/" + data["img_arr"][i] + "'>");
            $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + 
                "' onclick='delPicFn(\"" + i + "\",\"" + data["img_arr"][i] + "\")'>삭제</button>&nbsp;");
        }
        
        $("#pInput").val('');
    });
}

function displayPictureLoad() {
    $('#displayPicture').show();
    var picNum = <?php echo isset($picNum) ? (int)$picNum : 0; ?>;
    var picData = <?php echo isset($picData) ? json_encode($picData, JSON_UNESCAPED_UNICODE) : '[]'; ?>;
	   
    for (var i = 0; i < picNum; i++) {
        $("#displayPicture").append("<img id='pic" + i + "' src='img/" + picData[i] + "'>");
        $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + 
            "' onclick='delPicFn(\"" + i + "\",\"" + picData[i] + "\")'>삭제</button>&nbsp;");
    }
    
    $("#pInput").val('');
}

function delPic(delChoice) {
    if (delChoice == 'before') {
        $("#filedelete").val('before');
    }
    if (delChoice == 'after') {
        $("#filedelete").val('after');
    }
    
    document.getElementById('board_form').submit();
}

var delPicFn = function(divID, delChoice) {
    $.ajax({
        url: 'delpic.php?picname=' + delChoice,
        type: 'post',
        data: $("mainFrm").serialize(),
        dataType: 'json'
    }).done(function(data) {
        var picname = data["picname"];
        $("#pic" + divID).remove();
        $("#delPic" + divID).remove();
        $("#pInput").val('');
    });
};

$(document).ready(function() {
    var approve = $('#approve').val();
    
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    $("#closeBtn").click(function() {
        window.close();
    });
    
    // 회의 일시 변경 시
    $("#occur").change(function() {
        $('#opendate').val($("#occur").val());
    });
    
    // 부적합 유형 등록
    $("#registerrortypeBtn").click(function() {
        var href = '../registerrortype.php';
        popupCenter(href, '부적합 유형 등록', 600, 600);
    });
    
    // 현장 검색
    $("#searchPlaceBtn").click(function() {
        var num = $("#num").val();
        var href = 'search.php?num=' + num;
        popupCenter(href, '현장 검색', 600, 600);
    });
    
    // 사진 등록
    $("#regpicBtn").click(function() {
        var num = $("#num").val();
        window.open('reg_pic.php?num=' + num, "사진등록", "width=1200,height=700,top=0,left=0,scrollbars=no");
    });
    
    // 파일 변경
    $("#mainbefore").change(function(e) {
        var isfile = $("#filename").val();
        var changefile = $("#mainbefore").val();
        
        if (changefile != '') {
            $("#filename").val('');
        }
    });
    
    // 저장 버튼
    $("#saveBtn").click(function() {
        var num = $("#num").val();
        var part = $("#part").val();
        var approve = $("#approve").val();
        var user_name = $("#user_name").val();
        var reporter = $("#reporter").val();
        
        var allowedUsers = ['김보곤', '소현철'];
        
        if ((reporter == user_name && approve == '결재상신') || allowedUsers.indexOf(user_name) !== -1) {
            if (Number(num) > 0) {
                $("#mode").val('modify');
            } else {
                $("#mode").val('insert');
                if (user_name == '김진억') {
                    $("#approve").val('1차결재');
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
                url: "insert.php",
                type: "post",
                data: data,
                success: function(data) {
                    Toastify({
                        text: "파일 저장완료",
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
                            window.opener.location.reload();
                        }
                        window.close();
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.error('저장 오류:', status, error);
                    alert('저장 중 오류가 발생했습니다.');
                }
            });
        } else {
            var tmp = '보고자만 결재상신 상태가 아닌 경우 수정이 가능합니다.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
        }
    });
    
    // 삭제 버튼
    $("#delBtn").click(function() {
        var num = $("#num").val();
        var reporter = $("#reporter").val();
        var admin = '<?php echo $admin; ?>';
        var user_name = $("#user_name").val();
        
        if ((reporter == user_name && approve == '결재상신') || (admin == '1') || user_name == '김보곤') {
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
                    $("#mode").val('delete');
                    
                    $.ajax({
                        url: "insert.php",
                        type: "post",
                        data: $("#board_form").serialize(),
                        dataType: "text",
                        success: function(data) {
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
                                    if (typeof window.opener.restorePageNumber === 'function') {
                                        window.opener.restorePageNumber();
                                    }
                                    window.opener.location.reload();
                                }
                                window.close();
                            }, 1000);
                        },
                        error: function(jqxhr, status, error) {
                            console.error('삭제 오류:', status, error);
                            alert('삭제 중 오류가 발생했습니다.');
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: '삭제불가',
                text: "작성자와 관리자만 삭제가능합니다.",
                icon: 'error',
                confirmButtonText: '확인'
            });
        }
    });
    
}); // end of ready

</script>