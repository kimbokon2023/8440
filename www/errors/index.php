<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 관리자 권한 설정
$admin = 0;
$admin_names = array('소현철', '김보곤', '최장중', '이경묵', '조경임');
if (in_array($user_name, $admin_names)) {
    $admin = 1;
}

$tablename = 'error';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 접속 IP 기록
$ip_address = $_SERVER["REMOTE_ADDR"] ?? '';
$ip_address = 'ip_(불량보고) : ' . $ip_address;

$data = date("Y-m-d H:i:s") . " - " . $user_id . " - " . $user_name . '  ' . $ip_address;

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.logdata (data) VALUES (?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("접속 로그 기록 오류: " . $ex->getMessage());
}

// _row.php에서 사용되는 변수 초기화
$num = '';
$occur = '';
$occurconfirm = '';
$approve = '';
$errortype = '';
$place = '';
$reporter = '';
$content = '';
$method = '';
$involved = '';
$materialFee = 0;
$deliveryFee = 0;
$workFee = 0;
$etcFee = 0;
$totalFee = 0;

// 결재권자 결재정보 보기
$approvalwait = 0;

if ($admin == 1) {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE approve <> '처리완료'";
    
    try {
        $stmh = $pdo->query($sql);
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include "_row.php";
            $approvalwait += 1;
        }
    } catch (PDOException $ex) {
        error_log("결재 대기 건 조회 오류: " . $ex->getMessage());
    }
}

// 서버의 정보를 읽어와 메인화면 꾸미기
$sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";

$numarr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include "_row.php";
    }
} catch (PDOException $ex) {
    error_log("부적합 리스트 조회 오류: " . $ex->getMessage());
}

// 랜덤하게 유튜브 주소 추출
$youtube_arr = array();
array_push($youtube_arr, "https://www.youtube.com/embed/VPwhUEc84pg");
array_push($youtube_arr, "https://www.youtube.com/embed/NcFf9JhcHDQ");
array_push($youtube_arr, "https://www.youtube.com/embed/aXB5XNmG-TE");
array_push($youtube_arr, "https://www.youtube.com/embed/5ulG8-brBng");

$youtubeURL = $youtube_arr[rand(0, count($youtube_arr) - 1)];

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';

include getDocumentRoot() . '/load_header.php';

?>

<title>부적합 품질경영</title> 
</head>

<body>

<?php include includePath('myheader.php') ?>

<form name="board_form" id="board_form" method="post">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center mt-2 mb-2">
                                    <!-- 품질불량 관리기법 -->
                                    <div id="Materialshow">
                                        <h4 class="text-center">
                                            품질불량 관리기법
                                            <img src="<?= asset('img/click.gif') ?>" width="5%" height="5%" alt="클릭">
                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-center mt-2 mb-2">
                                    <div id="Material" style="display:none;">
                                        <section class="page-section">                                            
                                                <div class="row text-center">
                                                    <?php include '8d.php'; ?>
                                                </div>
                                                <div class="row text-left">
                                                    <?php include 'fmea.php'; ?>
                                                    <img src="<?= asset('img/qm1.jpg') ?>" alt="품질경영 1">
                                                    <img src="<?= asset('img/qm2.jpg') ?>" alt="품질경영 2">
                                                    <img src="<?= asset('img/qm3.jpg') ?>" alt="품질경영 3">
                                                    <img src="<?= asset('img/qm4.jpg') ?>" alt="품질경영 4">
                                                    <img src="<?= asset('img/qm5.jpg') ?>" alt="품질경영 5">
                                                    <img src="<?= asset('img/qm6.jpg') ?>" alt="품질경영 6">
                                                </div>
                                        </section>
                                    </div>
                                </div>
                                
                                <div class="d-flex px-1 px-lg-1 mt-1 justify-content-center">
                                    <h5 class="mb-1 text-secondary">지속적 관심/분석/개선이 불량감소에 큰 도움이 됩니다.</h5>
                                </div>
                                
                                <div class="d-flex mt-3 justify-content-center">
                                    <div class="typing-txt">
                                        승인상태 : 결재상신 -> 1차결재 -> 처리완료
                                    </div>
                                    <p class="typing"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
        <?php
        // 기간을 정하는 구간
        if ($fromdate == "") {
            $fromdate = substr(date("Y-m-d", time()), 0, 4) . "-01-01";
        }
        
        if ($todate == "") {
            $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
            $Transtodate = strtotime($todate . '+1 days');
            $Transtodate = date("Y-m-d", $Transtodate);
        } else {
            $Transtodate = strtotime($todate);
            $Transtodate = date("Y-m-d", $Transtodate);
        }
        
        // SQL 쿼리 생성
        if ($mode == "search" || $mode == "") {
            if ($search == "") {
                $sql = "SELECT * FROM {$DB}.error ORDER BY num DESC";
            } elseif ($search == "결재상신 1차결재") {
                $sql = "SELECT * FROM {$DB}.error WHERE approve = '결재상신' OR approve = '1차결재' ORDER BY num DESC";
                $search = null;
            } else {
                // 기본 SQL Injection 방어
                $search_safe = str_replace("'", "''", $search);
                $sql = "SELECT * FROM {$DB}.error WHERE " .
                       "(reporter LIKE '%{$search_safe}%') OR " .
                       "(place LIKE '%{$search_safe}%') OR " .
                       "(content LIKE '%{$search_safe}%') OR " .
                       "(method LIKE '%{$search_safe}%') OR " .
                       "(involved LIKE '%{$search_safe}%') OR " .
                       "(approve LIKE '%{$search_safe}%') " .
                       "ORDER BY occur DESC";
            }
        }
        
        // 레코드 조회
        try {
            $stmh = $pdo->query($sql);
            
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include "_row.php";
            }
        } catch (PDOException $ex) {
            error_log("부적합 검색 오류: " . $ex->getMessage());
        }
        
        // 전체 레코드 수 파악
        $total_row = 0;
        
        try {
            $stmh = $pdo->query($sql);
            $total_row = $stmh->rowCount();
        } catch (PDOException $ex) {
            error_log("레코드 수 조회 오류: " . $ex->getMessage());
        }
        ?>
        
        <input id="view_table" name="view_table" type="hidden" value="<?= htmlspecialchars($view_table) ?>">
        <input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert) ?>" size="5">
        <input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert) ?>" size="5">
        <input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert) ?>" size="5">
        
        <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
            ▷ <?= $total_row ?> 건 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" class="form-control me-2" style="width:150px;height:32px;" 
                   name="search" id="search" value="<?= htmlspecialchars($search) ?>" 
                   onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
            <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                <i class="bi bi-search"></i> 검색
            </button>
            <button type="button" class="btn btn-dark btn-sm" id="writeBtn">
                <i class="bi bi-pencil"></i> 신규
            </button>
            &nbsp;&nbsp;&nbsp;
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover w-100" id="myTable">
                    <thead class="table-primary">
                    <tr class="middle-align">
                        <th class="text-center align-middle w30px">번호</th>
                        <th class="text-center align-middle w80px">확인일</th>
                        <th class="text-center align-middle w80px">승인상태</th>
                        <th class="text-center align-middle w80px">불량유형</th>
                        <th class="text-center align-middle w200px">현장명(품명)</th>
                        <th class="text-center align-middle w50px">보고자</th>
                        <th class="text-center align-middle w300px">발생원인(분석)</th>
                        <th class="text-center align-middle w300px">처리방안(개선사항)</th>
                        <th class="text-center align-middle">관련 직원</th>
                        <th class="text-center align-middle">자재비</th>
                        <th class="text-center align-middle">운송비</th>
                        <th class="text-center align-middle">시공비</th>
                        <th class="text-center align-middle">기타비용</th>
                        <th class="text-center align-middle">비용합계</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $start_num = $total_row;
                    
                    try {
                        $stmh = $pdo->query($sql);
                        
                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            include "_row.php";
                    ?>
                            <tr style="cursor: pointer;" onclick="popupCenter('write_form.php?num=<?= $num ?>', '부적합 보고서', 1000, 920); return false;">
                                <td class="text-center"><?= $start_num ?></td>
                                <td class="text-center"><?= htmlspecialchars($occurconfirm) ?></td>
                                <td class="text-center"><?= htmlspecialchars($approve) ?></td>
                                <td class="text-center"><?= htmlspecialchars($errortype) ?></td>
                                <td><?= htmlspecialchars($place) ?></td>
                                <td class="text-center"><?= htmlspecialchars($reporter) ?></td>
                                <td><?= htmlspecialchars($content) ?></td>
                                <td><?= htmlspecialchars($method) ?></td>
                                <td class="text-center"><?= htmlspecialchars($involved) ?></td>
                                <td class="text-end"><?= is_numeric($materialFee) ? number_format($materialFee) : '' ?></td>
                                <td class="text-end"><?= is_numeric($deliveryFee) ? number_format($deliveryFee) : '' ?></td>
                                <td class="text-end"><?= is_numeric($workFee) ? number_format($workFee) : '' ?></td>
                                <td class="text-end"><?= is_numeric($etcFee) ? number_format($etcFee) : '' ?></td>
                                <td class="text-end"><?= is_numeric($totalFee) ? number_format($totalFee) : '' ?></td>
                            </tr>
                    <?php
                            $start_num--;
                        }
                    } catch (PDOException $ex) {
                        error_log("테이블 출력 오류: " . $ex->getMessage());
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        </div>
    
    <!-- Footer -->
    <?php include "footer.php"; ?>
</form>

<form name="settingsFrm" id="settingsFrm" method="post" action="settings.php">
</form>

<!-- Core theme JS -->
<script src="<?= asset('error/js/scripts.js') ?>"></script>

<script>
// ES5 호환 JavaScript
var dataTable; // DataTables 인스턴스 전역 변수
var errorpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('errorpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        errorpageNumber = dataTable.page.info().page + 1;
        setCookie('errorpageNumber', errorpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('errorpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('errorpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {
    var page = errorpageNumber;
    var url = "view.php?num=" + num + "&tablename=" + tablename;
    customPopup(url, '', 1200, 900);
}

$(document).ready(function() {
    $("#writeBtn").click(function() {
        var page = errorpageNumber;
        var tablename = '<?php echo $tablename; ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '', 1300, 850);
    });

    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    $("#adminprocess").click(function() {
        $('#search').val('결재상신 1차결재');
        document.getElementById('board_form').submit();
    });

    $("#searchNoinputBtn").click(function() {
        $('#search').val('');
        document.getElementById('board_form').submit();
    });

    // 서버에 작업 기록
    saveLogData('부적합 보고');
});
</script>

</body>
</html>


