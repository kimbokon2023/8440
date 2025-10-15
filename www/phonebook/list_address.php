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
$page = $_REQUEST['page'] ?? '';
$mode = $_REQUEST['mode'] ?? '';
$SelectWork = $_REQUEST['SelectWork'] ?? '';
$num = $_REQUEST['num'] ?? '';
$enterpress = $_REQUEST['enterpress'] ?? '';
$tablename = 'ceiling';

$page = $page === '' ? 1 : max(1, (int)$page);
$scale = 1000;
$pageScale = 10;
$offset = ($page - 1) * $scale;
$totalRow = 0;
$totalPage = 0;
$currentPage = 1;
$rows = [];
$singleMatchAddress = '';
$errorMessage = '';

try {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();

    $searchTerm = trim($search);
    $conditions = ["TRIM(address) <> ''"];
    $params = [];

    if ($searchTerm !== '') {
        $conditions[] = 'address LIKE :search';
        $params[':search'] = '%' . $searchTerm . '%';
    }

    $whereClause = 'WHERE ' . implode(' AND ', $conditions);

    $countSql = "SELECT COUNT(*) FROM (SELECT DISTINCT address FROM {$DB}.{$tablename} {$whereClause}) AS address_list";
    $countStmt = $pdo->prepare($countSql);
    foreach ($params as $name => $value) {
        $countStmt->bindValue($name, $value, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalRow = (int)$countStmt->fetchColumn();

    $listSql = "SELECT DISTINCT address FROM {$DB}.{$tablename} {$whereClause} ORDER BY address ASC LIMIT :offset, :limit";
    $listStmt = $pdo->prepare($listSql);
    foreach ($params as $name => $value) {
        $listStmt->bindValue($name, $value, PDO::PARAM_STR);
    }
    $listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $listStmt->bindValue(':limit', $scale, PDO::PARAM_INT);
    $listStmt->execute();
    $rows = $listStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($totalRow === 1 && $enterpress === 'true') {
        $singleMatchAddress = $rows[0]['address'] ?? '';
    }

    $totalPage = $totalRow > 0 ? (int)ceil($totalRow / $scale) : 0;
    $currentPage = $pageScale > 0 ? (int)ceil($page / $pageScale) : 1;
} catch (PDOException $exception) {
    $errorMessage = $exception->getMessage();
}

include getDocumentRoot() . '/load_header.php';
?>
<title> 주소 검색 </title>
<style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");

    fieldset.groupbox-border {
        border: 1px groove #ddd !important;
        padding: 3 3 3 3 !important;
        margin: 3 3 3 3 !important;
        box-shadow: 0px 0px 0px 0px #000;
    }

    legend.groupbox-border {
        background-color: #F0F0F0;
        color: #000;
        padding: 3px 6px;
        font-size: 1.0em !important;
        font-weight: bold !important;
        text-align: left !important;
        border-bottom: none;
    }

    fieldset.groupbox1-border {
        border: 1px groove #ddd !important;
        padding: 3 3 3 3 !important;
        margin: 3 3 3 3 !important;
    }

    legend.groupbox1-border {
        background-color: #F0F0F0;
        color: #000;
        padding: 9px 9px;
        font-size: 1.0em !important;
        font-weight: bold !important;
        text-align: left !important;
        border-bottom: none;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1;
        color: #212529;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: #dddddd;
    }

    footer.btnBox_todayClose {
        padding: 0.5rem 0 0.7rem;
        display: flex;
    }

    form {
        padding-right: 2rem;
    }

    input#chkday {
        vertical-align: middle;
    }

    label {
        vertical-align: middle;
    }
</style>
<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($singleMatchAddress !== ''): ?>
    <script>
        window.onload = function () {
            maketext(<?= json_encode($singleMatchAddress) ?>);
        };
    </script>
<?php endif; ?>
<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="SelectWork" name="SelectWork" value="<?= $SelectWork ?>">
    <input type="hidden" id="num" name="num" value="<?= $num ?>">
    <input type="hidden" id="page" name="page" value="<?= $page ?>">
    <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
    <div class="container-fluid" style="width:580px;">
        <div class="card justify-content-center text-center mt-1">
            <div class="card-header">
                <span class="text-center fs-5"> 주소 </span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center text-center align-items-center mb-2">
                    ▷ <?= $totalRow ?> &nbsp;
                    <div class="inputWrap30">
                        <input type="text" id="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" onkeypress="if (event.keyCode === 13) { enter(); }">
                        <button class="btnClear"></button>
                    </div>
                    &nbsp;&nbsp;
                    <button class="btn btn-outline-dark btn-sm" type="button" id="searchBtn"> 검색 </button> &nbsp;&nbsp;&nbsp;&nbsp;
                    <button id="closeBtn" type="button" class="btn btn-outline-dark btn-sm"> 창닫기 </button>
                </div>
                <div class="table-reponsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>번호</th>
                                <th>주소</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $startNum = $totalRow - $offset;
                        foreach ($rows as $row) {
                            $address = $row['address'] ?? '';
                            ?>
                            <tr>
                                <td><?= $startNum ?></td>
                                <td><a href="#" onclick="maketext('<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>');return false;" title="<?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>"> <?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?> </a></td>
                            </tr>
                            <?php
                            $startNum--;
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="row row-cols-auto mt-1 justify-content-center align-items-center">
                        <?php
                        if ($page !== 1 && $page > $pageScale) {
                            $prevPage = $page - $pageScale;
                            $prevPage = $prevPage <= 0 ? 1 : $prevPage;
                            echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="javascript:movetoPage(' . $prevPage . ');"> ◀ </button> &nbsp;';
                        }

          
