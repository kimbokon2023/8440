<?php
require_once __DIR__ . '/../common/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$is_local = ($httpHost === 'localhost' || strpos($httpHost, '127.0.0.1') !== false);
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

$DB = $_SESSION['DB'] ?? '';
$level = $_SESSION['level'] ?? '';
$user_name = $_SESSION['name'] ?? '';
$user_id = $_SESSION['userid'] ?? '';
$website = $_SESSION['WebSite'] ?? '';

if ($level === '' || (is_numeric($level) && (int)$level > 5)) {
    $loginPath = $website !== '' ? rtrim($website, '/') . '/login/login_form.php' : '/login/login_form.php';
    header('Location:' . $loginPath);
    exit;
}

$search = $_REQUEST['search'] ?? '';
$tablename = 'phonebook';
$page = $_REQUEST['page'] ?? '';
$num = $_REQUEST['num'] ?? '';
$mode = $_REQUEST['mode'] ?? '';
$belongstr = $_REQUEST['belongstr'] ?? '';
$belong = $_REQUEST['belong'] ?? '';

$page = $page === '' ? 1 : max(1, (int)$page);
$numValue = $num === '' ? 0 : (int)$num;
$SelectWork = 'insert';
$phone_name = $search !== '' ? $search : '';
$phonenumber = '010-';
$belongstr = $belongstr !== '' ? $belongstr : '';
$errorMessage = '';

try {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();

    if ($numValue > 0) {
        $SelectWork = 'update';
        $sql = "SELECT num, phone_name, phonenumber, belongstr FROM {$DB}.{$tablename} WHERE num = :num LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':num', $numValue, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        if (!empty($row)) {
            include __DIR__ . '/_row.php';
        }
    }
} catch (PDOException $exception) {
    $errorMessage = $exception->getMessage();
}

include getDocumentRoot() . '/load_header.php';
?>
<title> 연락처 등록/수정 </title>
<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<body>
<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="SelectWork" name="SelectWork" value="<?= $SelectWork ?>">
    <input type="hidden" id="num" name="num" value="<?= $numValue ?>">
    <input type="hidden" id="page" name="page" value="<?= $page ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
    <input type="hidden" id="belong" name="belong" value="<?= htmlspecialchars($belong, ENT_QUOTES, 'UTF-8') ?>">

    <div class="container-fluid" style="width:530px;">
        <div class="card justify-content-center text-center mt-4 mb-3">
            <div class="card-header">
                <span class="text-center fs-5"> 연락처 </span>
            </div>
            <div class="card-body">
                <div class="row justify-content-center text-center">
                    <div class="d-flex row">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:130px;"> 성명 </span>
                            <input type="text" class="form-control" id="phone_name" name="phone_name" value="<?= htmlspecialchars($phone_name, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                    <div class="d-flex row">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:130px;"> 전화번호 </span>
                            <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="<?= htmlspecialchars($phonenumber, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                    <div class="d-flex row">
                        <div class="input-group mb-1">
                            <span class="input-group-text" style="width:130px;"> 소속 </span>
                            <input type="text" class="form-control" id="belongstr" name="belongstr" value="<?= htmlspecialchars($belongstr, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer justify-content-start">
                <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm me-2">
                    <ion-icon name="close-circle-outline"></ion-icon> Close
                </button>
                <button type="button" id="saveBtn" class="btn btn-dark btn-sm">
                    <ion-icon name="save-outline"></ion-icon> 저장
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#closeBtn").on("click", function () {
            self.close();
        });

        $("#saveBtn").on("click", function () {
            $("#SelectWork").val("<?= $SelectWork ?>");
            $.ajax({
                url: "process.php",
                type: "post",
                data: $("#board_form").serialize(),
                success: function (data) {
                    var msg = (data && data.trim() === 'success') ? '저장완료' : data;
                    Toastify({
                        text: msg,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#4fbe87"
                    }).showToast();
                    $("#search", opener.document).val($("#phone_name").val());
                    $(opener.location).attr("href", "javascript:reloadlist();");
                    setTimeout(function () {
                        self.close();
                    }, 500);
                },
                error: function (jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                    Toastify({
                        text: '저장 중 오류가 발생했습니다.',
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#ff5f5f"
                    }).showToast();
                }
            });
        });
    });
</script>
</body>
</html>
