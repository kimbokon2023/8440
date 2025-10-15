<?php
/**
 * 장비 (주간, 정기) 점검표
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';

if (!isset($_SESSION)) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? null;
$user_name = $_SESSION["name"] ?? null;
$user_id = $_SESSION["userid"] ?? null;

// 요청 변수 초기화
$mcno = $_REQUEST["mcno"] ?? $_REQUEST["mcname"] ?? '';
$selnum = $_REQUEST["selnum"] ?? 1;
$page = $_REQUEST["page"] ?? 1;
$scale = $_REQUEST["scale"] ?? 10;
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$WebSite = "{$protocol}://{$host}/";

// 권한 체크
if (!isset($level) || $level > 8) {
    $_SESSION["url"] = $WebSite . "mymachine/laser.php?mcno=" . urlencode($mcno);
    header("Location: " . $WebSite . "login/login_form.php");
    exit;
}

// 모바일 체크 (load_header.php에서 정의됨)
$chkMobile = false;

// 기타 변수 초기화
$alerts = '';
$mcmain = '';
$mcsub = '';
$title_message = '장비 (주간,정기) 점검표';

// 날짜 설정
if (empty($fromdate)) {
    $fromdate = "2010-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

include getDocumentRoot() . '/load_header.php';
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php
// 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        body, table th, table td, .form-control, span {
            font-size: 32px;
        }
        h4 {
            font-size: 40px;
        }
        .btn-sm {
            font-size: 26px;
        }
        .spantitle {
            font-size: 40px;
        }
    </style>';
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";

// 페이지네이션 설정
$page_scale = 10;
$first_num = ($page - 1) * $scale;

// 정렬 기준
$a = " ORDER BY checkdate DESC";
$b = " ORDER BY checkdate DESC";

$nowday = date("Y-m-d");

// SQL 쿼리 생성 (Prepared Statement 사용)
$sql = '';
$params = [];
$selnum_int = intval($selnum);

if ($selnum_int == 1) {
    // 전체
    $sql = "SELECT * FROM mirae8440.mymclist WHERE item = ? " . $a;
    $params = [$mcno];
} elseif ($selnum_int == 2) {
    // 주간
    $sql = "SELECT * FROM mirae8440.mymclist WHERE item = ? AND term = '주간' " . $a;
    $params = [$mcno];
} elseif ($selnum_int == 3) {
    // 1개월
    $sql = "SELECT * FROM mirae8440.mymclist WHERE item = ? AND term = '1개월' " . $a;
    $params = [$mcno];
} elseif ($selnum_int == 4) {
    // 2개월
    $sql = "SELECT * FROM mirae8440.mymclist WHERE item = ? AND term = '2개월' " . $a;
    $params = [$mcno];
} elseif ($selnum_int == 5) {
    // 6개월
    $sql = "SELECT * FROM mirae8440.mymclist WHERE item = ? AND term = '6개월' " . $a;
    $params = [$mcno];
} elseif ($selnum_int == 6) {
    // 미점검 리스트
    $arrtmp = [];
    for ($j = 0; $j < count($mcno_arr); $j++) {
        if ($mcno == $mcno_arr[$j]) {
            $arrtmp = explode(",", $questionstep_arr[$j]);
            break;
        }
    }
    
    $period = ["주간", "1개월", "2개월", "6개월"];
    $sqladd = "";
    
    for ($k = 0; $k < count($period); $k++) {
        $sqladd .= " (item = ? AND term = ?) AND ( ";
        $params[] = $mcno;
        $params[] = $period[$k];
        
        for ($j = 1; $j <= (int)$arrtmp[$k]; $j++) {
            if ($j != 1) {
                $sqladd .= " OR ";
            }
            $sqladd .= " check" . $j . " IS NULL ";
        }
        
        if ($k == count($period) - 1) {
            $sqladd .= " ) ";
        } else {
            $sqladd .= " ) OR ";
        }
    }
    
    $sql = "SELECT * FROM mirae8440.mymclist WHERE " . $sqladd . $a;
} elseif ($selnum_int == 7) {
    // 점검완료 리스트
    $arrtmp = [];
    for ($j = 0; $j < count($mcno_arr); $j++) {
        if ($mcno == $mcno_arr[$j]) {
            $arrtmp = explode(",", $questionstep_arr[$j]);
            break;
        }
    }
    
    $period = ["주간", "1개월", "2개월", "6개월"];
    $sqladd = "";
    
    for ($k = 0; $k < count($period); $k++) {
        $sqladd .= " (item = ? AND term = ?) AND ( ";
        $params[] = $mcno;
        $params[] = $period[$k];
        
        for ($j = 1; $j <= (int)$arrtmp[$k]; $j++) {
            if ($j != 1) {
                $sqladd .= " AND ";
            }
            $sqladd .= " check" . $j . " IS NOT NULL ";
        }
        
        if ($k == count($period) - 1) {
            $sqladd .= " ) ";
        } else {
            $sqladd .= " ) OR ";
        }
    }
    
    $sql = "SELECT * FROM mirae8440.mymclist WHERE " . $sqladd . $a;
}

// 기본 쿼리 (selnum이 유효하지 않은 경우)
if (empty($sql)) {
    $sql = "SELECT * FROM mirae8440.mymclist WHERE item = ? " . $a;
    $params = [$mcno];
}

// 전체 레코드수 파악
$total_row = 0;
try {
    $stmh = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $index => $param) {
        $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
    }
    
    $stmh->execute();
    $total_row = $stmh->rowCount();
?>

<form name="board_form" id="board_form" method="post" action="laser.php">
    <input type="hidden" id="alerts" name="alerts" value="<?= htmlspecialchars($alerts, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="selnum" name="selnum" value="<?= htmlspecialchars($selnum, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mcmain" name="mcmain" value="<?= htmlspecialchars($mcmain, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mcsub" name="mcsub" value="<?= htmlspecialchars($mcsub, ENT_QUOTES, 'UTF-8') ?>">
    
    <?php if ($chkMobile) { ?>
        <div class="container-fluid mt-2 mb-2">
    <?php } else { ?>
        <div class="container mt-2 mb-2">
    <?php } ?>
    
        <div class="card mt-2 mb-4">
            <div class="card-body">
                <div class="d-flex mt-3 mb-1 justify-content-start">
                    <h3 class="spantitle">장비 점검</h3>
                </div>
                
                <div class="d-flex mt-3 mb-1 justify-content-end">
                    <?php if (!isset($_SESSION["userid"])) { ?>
                        <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
                    <?php } else { ?>
                        <?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8') ?> |
                        <a href="../login/logout.php">로그아웃</a> |
                        <a href="../member/updateForm.php?id=<?= htmlspecialchars($_SESSION["userid"], ENT_QUOTES, 'UTF-8') ?>">정보수정</a>
                    <?php } ?>
                </div>
                
                <div class="d-flex align-items-center mt-4 mb-3 justify-content-start">
                    <?php if ($chkMobile) { ?>
                        <select class="form-control me-2" name="mcno" id="mcno" style="width:25%;">
                    <?php } else { ?>
                        <select class="form-control me-2" name="mcno" id="mcno" style="width:12%;">
                    <?php } ?>
                        <?php
                        for ($i = 0; $i < count($mcno_arr); $i++) {
                            $selected = ($mcno == $mcno_arr[$i]) ? 'selected' : '';
                            echo '<option ' . $selected . ' value="' . htmlspecialchars($mcno_arr[$i], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($mcno_arr[$i], ENT_QUOTES, 'UTF-8') . '</option>';
                        }
                        ?>
                    </select>
                    
                    <?php if ($chkMobile) { ?>
                        <span class="badge bg-secondary form-control me-2 fs-5" style="width:35%;border:0px;" readonly>
                    <?php } else { ?>
                        <span class="badge bg-secondary form-control me-2 fs-5" style="width:20%;border:0px;" readonly>
                    <?php } ?>
                        (정) <?= htmlspecialchars($mcmain, ENT_QUOTES, 'UTF-8') ?>, (부) <?= htmlspecialchars($mcsub, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                    
                    <button type="button" class="btn btn-danger btn-sm me-2" onclick="show_list(6);">미점검</button>
                    <button type="button" class="btn btn-success btn-sm me-2" onclick="show_list(7);">점검완료</button>
                </div>
                
                <div class="d-flex align-items-center mt-3 mb-1 justify-content-start">
                    <button type="button" id="closeBtn" class="btn btn-outline-dark btn-sm">창닫기</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-outline-dark btn-sm" onclick="show_list(1);">전체</button>&nbsp;
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="show_list(2);">주간</button>&nbsp;
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="show_list(3);">1개월</button>&nbsp;
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="show_list(4);">2개월</button>&nbsp;
                    <button type="button" class="btn btn-outline-dark btn-sm" onclick="show_list(5);">6개월</button>
                </div>
                
                <div class="d-flex align-items-center mt-3 mb-1 justify-content-start">
                    (주간 점검) 매주 금요일 작업종료 30분전,<br>
                    (월간 점검) 매월 넷째주 금요일 작업종료 30분전,<br>
                    (2개월 점검) 짝수달 넷째주 금요일 작업종료 30분전,<br>
                    (6개월 점검) 6월,12월 넷째주 금요일 작업종료 30분전<br>
                </div>
                
                <div class="d-flex align-items-center mt-3 mb-1 justify-content-start">
                    ▷ <?= htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8') ?> 개 &nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                
                <div class="row d-flex">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">점검일</th>
                                <th class="text-center">주간점검</th>
                                <th class="text-center">1개월점검</th>
                                <th class="text-center">2개월점검</th>
                                <th class="text-center">6개월점검</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start_num = $total_row;
                            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                include "rowDB.php";
                                
                                // 점검 여부 판단
                                $arrtmp = [];
                                for ($j = 0; $j < count($mcno_arr); $j++) {
                                    if ($mcno == $mcno_arr[$j]) {
                                        $arrtmp = explode(",", $questionstep_arr[$j]);
                                        break;
                                    }
                                }
                                
                                // 주간점검 상태
                                $weektermstr = '완료';
                                for ($j = 1; $j <= (int)$arrtmp[0]; $j++) {
                                    $checkstr = 'check' . $j;
                                    if (!isset($$checkstr) || $$checkstr == null) {
                                        $weektermstr = '미점검';
                                        break;
                                    }
                                }
                                
                                // 1개월 점검 상태
                                $monthtermstr = '완료';
                                for ($j = 1; $j <= (int)$arrtmp[1]; $j++) {
                                    $checkstr = 'check' . $j;
                                    if (!isset($$checkstr) || $$checkstr == null) {
                                        $monthtermstr = '미점검';
                                        break;
                                    }
                                }
                                
                                // 2개월 점검 상태
                                $twomonthtermstr = '완료';
                                for ($j = 1; $j <= (int)$arrtmp[2]; $j++) {
                                    $checkstr = 'check' . $j;
                                    if (!isset($$checkstr) || $$checkstr == null) {
                                        $twomonthtermstr = '미점검';
                                        break;
                                    }
                                }
                                
                                // 6개월 점검 상태
                                $sixmonthtermstr = '완료';
                                for ($j = 1; $j <= (int)$arrtmp[3]; $j++) {
                                    $checkstr = 'check' . $j;
                                    if (!isset($$checkstr) || $$checkstr == null) {
                                        $sixmonthtermstr = '미점검';
                                        break;
                                    }
                                }
                            ?>
                            
                            <tr onclick="redirectToView('<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>')">
                                <td class="text-center">
                                    <span class="text-center"><?= htmlspecialchars($checkdate, ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="text-center">
                                        <?php if ($term == '주간') echo htmlspecialchars($weektermstr, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-center">
                                        <?php if ($term == '1개월') echo htmlspecialchars($monthtermstr, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-center">
                                        <?php if ($term == '2개월') echo htmlspecialchars($twomonthtermstr, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-center">
                                        <?php if ($term == '6개월') echo htmlspecialchars($sixmonthtermstr, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                            </tr>
                            
                            <?php
                                $start_num--;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
} catch (PDOException $ex) {
    error_log("장비 점검 리스트 조회 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>";
}
?>

<script type="text/javascript">
(function() {
    'use strict';
    
    var dataTable;
    var mcpageNumber;
    
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
        var savedPageNumber = getCookie('mcpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
        
        // 페이지 변경 이벤트 리스너
        dataTable.on('page.dt', function() {
            var mcpageNumber = dataTable.page.info().page + 1;
            setCookie('mcpageNumber', mcpageNumber, 10);
        });
        
        // 페이지 길이 셀렉트 박스 변경 이벤트 처리
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw();
            
            savedPageNumber = getCookie('mcpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });
        
        // 장비 선택 변경 이벤트
        $("#mcno").on("change", function() {
            $("#board_form").submit();
        });
        
        // 창 닫기 버튼
        $("#closeBtn").click(function() {
            window.close();
        });
    });
    
    window.restorePageNumber = function() {
        var savedPageNumber = getCookie('mcpageNumber');
        if (savedPageNumber && dataTable) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    };
    
    window.redirectToView = function(num) {
        var url = "view.php?num=" + encodeURIComponent(num);
        if (typeof customPopup === 'function') {
            customPopup(url, '장비 점검', 1300, 800);
        } else {
            window.open(url, '장비 점검', 'width=1300,height=800');
        }
    };
    
    window.show_list = function(insu) {
        $("#selnum").val(insu);
        $("#page").val('1');
        $("#board_form").submit();
    };
    
})();
</script>

</body>
</html>
