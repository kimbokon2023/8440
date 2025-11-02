<?php
require_once __DIR__ . '/../common/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$is_local = ($httpHost === 'localhost' || strpos($httpHost, '127.0.0.1') !== false);
$base_url = $is_local ? 'http://8440.local' : 'http://8440.co.kr';

$DB = $_SESSION['DB'] ?? 'mirae8440';
$level = $_SESSION['level'] ?? '';
$user_name = $_SESSION['name'] ?? '';
$user_id = $_SESSION['userid'] ?? '';
$website = $_SESSION['WebSite'] ?? '';

$search = $_REQUEST['search'] ?? '';
$firstitem = $_REQUEST['firstitem'] ?? '';
$seconditem = $_REQUEST['seconditem'] ?? '';
$enterpress = $_REQUEST['enterpress'] ?? '';
$belong = $_REQUEST['belong'] ?? '';
$belongstrParam = $_REQUEST['belongstr'] ?? '';
$SelectWork = $_REQUEST['SelectWork'] ?? '';
$requestNum = $_REQUEST['num'] ?? '';
$page = $_REQUEST['page'] ?? '';
$mode = $_REQUEST['mode'] ?? '';
$tablename = 'phonebook';

$page = $page === '' ? 1 : max(1, (int)$page);
$scale = 10;
$pageScale = 10;
$offset = ($page - 1) * $scale;
$totalRow = 0;
$totalPage = 0;
$currentPage = 1;
$rows = [];
$singleMatchRow = [];
$errorMessage = '';

try {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();

    $searchTerm = trim($search);
    $whereClause = '';
    $params = [];

    if ($searchTerm !== '') {
        // PHP 7.3에서는 같은 파라미터를 두 번 사용하면 문제가 발생할 수 있으므로 별도로 분리
        $whereClause = 'WHERE phone_name LIKE :search1 OR phonenumber LIKE :search2';
        $params[':search1'] = '%' . $searchTerm . '%';
        $params[':search2'] = '%' . $searchTerm . '%';
    }

    // 디버그: SQL과 파라미터 출력
    $countSql = "SELECT COUNT(*) FROM {$DB}.{$tablename} {$whereClause}";
    if (isLocal()) {
        echo "<!-- DEBUG COUNT SQL: " . htmlspecialchars($countSql) . " -->\n";
        echo "<!-- DEBUG PARAMS: " . htmlspecialchars(print_r($params, true)) . " -->\n";
    }
    
    $countStmt = $pdo->prepare($countSql);
    foreach ($params as $name => $value) {
        $countStmt->bindValue($name, $value, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalRow = (int)$countStmt->fetchColumn();

    // LIMIT 절은 정수로 검증된 변수를 직접 사용 (SQL injection 위험 없음)
    $listSql = "SELECT num, phone_name, phonenumber, belongstr FROM {$DB}.{$tablename} {$whereClause} ORDER BY phone_name ASC, num DESC LIMIT {$offset}, {$scale}";
    if (isLocal()) {
        echo "<!-- DEBUG LIST SQL: " . htmlspecialchars($listSql) . " -->\n";
        echo "<!-- DEBUG OFFSET: {$offset}, SCALE: {$scale} -->\n";
    }
    
    $listStmt = $pdo->prepare($listSql);
    foreach ($params as $name => $value) {
        $listStmt->bindValue($name, $value, PDO::PARAM_STR);
    }
    $listStmt->execute();
    $rows = $listStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($totalRow === 1 && $enterpress === 'true') {
        $singleMatchRow = $rows[0] ?? [];
    }

    $totalPage = $totalRow > 0 ? (int)ceil($totalRow / $scale) : 0;
    $currentPage = $pageScale > 0 ? (int)ceil($page / $pageScale) : 1;
} catch (PDOException $exception) {
    $errorMessage = $exception->getMessage();
}

$startPage = ($currentPage - 1) * $pageScale + 1;
$endPage = $startPage + $pageScale - 1;

include getDocumentRoot() . '/load_header.php';
?>
<title> 연락처 검색 </title>
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
<?php if ($errorMessage !== '') { ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php } ?>
<?php if (!empty($singleMatchRow)) { ?>
    <script>
        window.onload = function () {
            maketext(
                <?= json_encode($singleMatchRow['phone_name'] ?? '') ?>,
                <?= json_encode($singleMatchRow['phonenumber'] ?? '') ?>,
                <?= json_encode($singleMatchRow['belongstr'] ?? '') ?>
            );
        };
    </script>
<?php } ?>
<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="SelectWork" name="SelectWork" value="<?= $SelectWork ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($requestNum, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="page" name="page" value="<?= $page ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
    <div class="container-fluid" style="width:580px;">
        <div class="card justify-content-center text-center mt-1">
            <div class="card-header">
                <span class="text-center fs-5"> 연락처 </span>
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
                    <button id="newBtn" type="button" class="btn btn-success btn-sm me-2"> 신규 </button>
                    <button id="closeBtn" type="button" class="btn btn-outline-dark btn-sm"> 창닫기 </button>
                </div>
                <div class="table-reponsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>번호</th>
                                <th>소속</th>
                                <th>성명</th>
                                <th>전화번호</th>
                                <th>수정/삭제</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $startNum = $totalRow - $offset;
                        foreach ($rows as $row) {
                            include __DIR__ . '/_row.php';
                            $safeNum = htmlspecialchars((string)$num, ENT_QUOTES, 'UTF-8');
                            $safeBelong = htmlspecialchars($belongstr, ENT_QUOTES, 'UTF-8');
                            $safeName = htmlspecialchars($phone_name, ENT_QUOTES, 'UTF-8');
                            $safePhone = htmlspecialchars($phonenumber, ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td><?= $startNum ?></td>
                                <td><a href="#" onclick="maketext('<?= $safeName ?>','<?= $safePhone ?>','<?= $safeBelong ?>');return false;" title="<?= $safeBelong ?>"> <?= $safeBelong ?> </a></td>
                                <td><a href="#" onclick="maketext('<?= $safeName ?>','<?= $safePhone ?>','<?= $safeBelong ?>');return false;" title="<?= $safeName ?>"> <?= $safeName ?> </a></td>
                                <td><a href="#" onclick="maketext('<?= $safeName ?>','<?= $safePhone ?>','<?= $safeBelong ?>');return false;" title="<?= $safePhone ?>"> <?= $safePhone ?> </a></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="updateFn('<?= $safeNum ?>')"> <i class="bi bi-pencil-square"></i> </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="delFn('<?= $safeNum ?>')"> <i class="bi bi-x-circle"></i> </button>
                                </td>
                            </tr>
                            <?php
                            $startNum--;
                        }
                        if (empty($rows)) {
                            echo '<tr><td colspan="5" class="text-center">조회된 연락처가 없습니다.</td></tr>';
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

                        for ($i = $startPage; $i <= $endPage && $i <= $totalPage; $i++) {
                            if ($page === $i) {
                                echo '<span class="text-secondary"> ' . $i . ' </span>';
                            } else {
                                echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="javascript:movetoPage(' . $i . ');"> ' . $i . ' </button> &nbsp;';
                            }
                        }

                        if ($page < $totalPage) {
                            $nextPage = $page + $pageScale;
                            $nextPage = $nextPage > $totalPage ? $totalPage : $nextPage;
                            echo '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="javascript:movetoPage(' . $nextPage . ');"> ▶ </button> &nbsp;';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function movetoPage(page) {
        $("#page").val(page);
        $("#board_form").submit();
    }

    document.querySelectorAll('.btnClear').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var input = btn.parentNode.querySelector('input');
            if (input) {
                input.value = '';
                input.focus();
            }
            e.preventDefault();
        });
    });

    $(document).ready(function () {
        $("#searchBtn").on("click", function () {
            $("#board_form").submit();
        });

        $("#search_directinput").on("click", function () {
            $("#custreg_search").hide();
        });

        $("#newBtn").on("click", function () {
            const belong = <?= json_encode($belong) ?>;
            const belongstr = <?= json_encode($belongstrParam) ?>;
            popupCenter('./write.php?search=' + $("#search").val() + '&belong=' + belong + '&belongstr=' + belongstr, '등록', 580, 300);
        });

        $("#closeBtn").on("click", function () {
            self.close();
        });
    });

    function enter() {
        $("#page").val('1');
        $("#board_form").submit();
    }

    $(document).keydown(function (e) {
        var code = e.keyCode || e.which;
        if (code === 27) {
            self.close();
        }
    });

    function maketext(firstitem, seconditem, belongstr) {
        const firstitemID = <?= json_encode($firstitem) ?>;
        const seconditemID = <?= json_encode($seconditem) ?>;
        const belongId = <?= json_encode($belong) ?>;
        if (firstitemID) {
            $("#" + firstitemID, opener.document).val(firstitem);
        }
        if (seconditemID) {
            $("#" + seconditemID, opener.document).val(seconditem);
        }
        if (window.opener && typeof window.opener.updateOptions === "function") {
            if (firstitemID) {
                window.opener.updateOptions("#" + firstitemID, firstitem);
            }
            if (seconditemID) {
                window.opener.updateOptions("#" + seconditemID, seconditem);
            }
            if (belongId) {
                window.opener.updateOptions("#" + belongId, belongstr);
            }
        }
        self.close();
    }

    function updateFn(num) {
        popupCenter('./write.php?num=' + num, '자료 수정', 580, 300);
    }

    function delFn(delfirstitem) {
        $("#SelectWork").val("delete");
        $("#num").val(delfirstitem);
        Swal.fire({
            title: '해당 DATA 삭제',
            text: " DATA 삭제는 신중하셔야 합니다. \n 정말 삭제 하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "process.php",
                    type: "post",
                    data: $("#board_form").serialize(),
                    success: function (data) {
                        const message = (data && data.trim() === 'success') ? '파일 삭제 완료!' : data;
                        Toastify({
                            text: message,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87"
                        }).showToast();
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (jqxhr, status, error) {
                        console.log(jqxhr, status, error);
                    }
                });
            }
        });
    }

    function reloadlist() {
        $("#board_form").submit();
    }
</script>
</body>
</html>
