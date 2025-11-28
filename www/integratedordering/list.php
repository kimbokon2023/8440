<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 통합 발주 목록 (원자재 + 부자재)
 * 탭 없이 리스트로 통합, 구분 컬럼 추가
 */

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    $_SESSION["url"] = getBaseUrl() . '/integratedordering/list.php';
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/logout.php");
    exit;
}

// 페이지네이션 및 검색 변수
$page = $_REQUEST["page"] ?? '';
$scale = $_REQUEST["scale"] ?? '';
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 날짜 설정
$currentDate = date("Y-m-d");
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

if ($fromdate === "" || $todate === "") {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate)));
    $todate = $currentDate;
}
$Transtodate = $todate;

// 날짜 설정
$currentDate = date("Y-m-d");
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

if ($fromdate === "" || $todate === "") {
    // 기본 1년으로 설정 (데이터 확인용)
    $fromdate = date("Y-m-d", strtotime("-1 year", strtotime($currentDate)));
    $todate = $currentDate;
}
$Transtodate = $todate;

// SQL 기본 조건
// 원자재는 outdate, 부자재는 registdate 기준
$Andis_deleted = " AND (is_deleted IS NULL OR is_deleted='0')";

// 검색 쿼리 구성
$sql_common = " FROM " . $DB . ".eworks WHERE (
    (eworks_item='원자재구매' AND outdate BETWEEN '$fromdate' AND '$Transtodate')
    OR 
    (eworks_item='부자재구매' AND DATE(registdate) BETWEEN '$fromdate' AND '$Transtodate')
) " . $Andis_deleted;

if ($mode === "search" && $search !== "") {
    $search = str_replace(' ', '', $search);
    $sql_search = " AND (";
    $sql_search .= "replace(outworkplace,' ','') like '%$search%' ";
    $sql_search .= "OR steel_item like '%$search%' ";
    $sql_search .= "OR spec like '%$search%' ";
    $sql_search .= "OR company like '%$search%' ";
    $sql_search .= "OR supplier like '%$search%' ";
    $sql_search .= "OR request_comment like '%$search%' ";
    $sql_search .= "OR model like '%$search%' ";
    $sql_search .= ")";
    $sql_common .= $sql_search;
}

// 정렬
$sql_order = " ORDER BY num DESC";

// 데이터 조회
$sql = "SELECT * " . $sql_common . $sql_order;

try {
    $stmh = $pdo->query($sql);
    $order_total_row = $stmh->rowCount();
} catch (PDOException $e) {
    $order_total_row = 0;
    error_log($e->getMessage());
}

print $sql;

?>
<?php include getDocumentRoot() . '/load_header.php'; ?>
<title>통합 발주 관리</title>
<style>
    /* 테이블 스타일 */
    th { white-space: nowrap; text-align: center; background-color: #f8f9fa; }
    td { vertical-align: middle; }
    
    /* 구분 배지 스타일 */
    .badge-main { background-color: #0d6efd; } /* 원자재 */
    .badge-aux { background-color: #198754; } /* 부자재 */

    /* 모바일 반응형 */
    @media (max-width: 768px) {
        .container-fluid { padding: 5px; }
        .table-responsive { border: 0; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        
        /* 카드형 리스트 스타일 (모바일) */
        .mobile-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .mobile-card-body div {
            margin-bottom: 3px;
        }
        .mobile-card-label {
            font-weight: 600;
            color: #6c757d;
            margin-right: 5px;
        }
        
        /* PC 테이블 숨기기 */
        .pc-table { display: none; }
    }
    
    @media (min-width: 769px) {
        .mobile-list { display: none; }
    }
</style>

<body>
<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="container-fluid mt-3">
    
    <!-- 상단 컨트롤 (검색, 날짜, 버튼) -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="get" action="list.php" class="row g-2 align-items-center">
                <input type="hidden" name="mode" value="search">
                
                <div class="col-auto">
                    <span class="fw-bold">기간:</span>
                </div>
                <div class="col-auto">
                    <input type="date" name="fromdate" value="<?= $fromdate ?>" class="form-control form-control-sm">
                </div>
                <div class="col-auto">~</div>
                <div class="col-auto">
                    <input type="date" name="todate" value="<?= $todate ?>" class="form-control form-control-sm">
                </div>
                
                <div class="col-auto ms-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="검색어 입력" value="<?= $search ?>">
                        <button class="btn btn-outline-secondary" type="button" onclick="this.form.submit()"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                
                <div class="col-auto">
                    <button type="button" class="btn btn-primary btn-sm" onclick="openWriteForm()">
                        <i class="bi bi-pencil-square"></i> 신규등록
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 목록 (PC 테이블) -->
    <div class="table-responsive pc-table">
        <table class="table table-bordered table-hover table-sm" style="font-size: 0.9rem;">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="8%">구분</th>
                    <th width="8%">진행상태</th>
                    <th width="8%">접수일</th>
                    <th width="15%">현장명/물품명</th>
                    <th width="10%">모델</th>
                    <th width="10%">종류</th>
                    <th width="10%">규격</th>
                    <th width="5%">수량</th>
                    <th width="10%">공급처</th>
                    <th width="8%">납기일</th>
                    <th width="8%">완료일</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($order_total_row > 0) {
                    $stmh = $pdo->query($sql);
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        include '_row.php';
                        
                        // 상태 배지
                        $status_badge = '';
                        if ($which == '1') $status_badge = '<span class="badge bg-primary">요청</span>';
                        elseif ($which == '2') $status_badge = '<span class="badge bg-danger">발주</span>';
                        elseif ($which == '3') $status_badge = '<span class="badge bg-secondary">완료</span>';
                        
                        // 구분 배지
                        $type_badge = '';
                        if ($eworks_item === '원자재구매') $type_badge = '<span class="badge badge-main">원자재</span>';
                        elseif ($eworks_item === '부자재구매') $type_badge = '<span class="badge badge-aux">부자재</span>';
                        
                        echo "<tr onclick=\"viewDetail('$num')\" style='cursor:pointer;'>";
                        echo "<td class='text-center'>$num</td>";
                        echo "<td class='text-center'>$type_badge</td>";
                        echo "<td class='text-center'>$status_badge</td>";
                        echo "<td class='text-center'>$outdate</td>";
                        
                        echo "<td>$outworkplace</td>"; // 현장명 또는 물품명
                        echo "<td>$model</td>";
                        echo "<td>$steel_item</td>";
                        echo "<td>$spec</td>";
                        
                        echo "<td class='text-center text-danger fw-bold'>$steelnum</td>";
                        echo "<td class='text-center'>$supplier</td>";
                        echo "<td class='text-center'>$requestdate</td>";
                        echo "<td class='text-center'>$indate</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center py-4'>데이터가 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- 목록 (모바일 카드) -->
    <div class="mobile-list">
        <?php
        if ($order_total_row > 0) {
            $stmh = $pdo->query($sql);
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                include '_row.php';
                
                $status_text = '';
                $status_class = '';
                if ($which == '1') { $status_text = '요청'; $status_class = 'text-primary'; }
                elseif ($which == '2') { $status_text = '발주'; $status_class = 'text-danger'; }
                elseif ($which == '3') { $status_text = '완료'; $status_class = 'text-secondary'; }
                
                $type_text = ($eworks_item === '원자재구매') ? '원자재' : '부자재';
                $type_class = ($eworks_item === '원자재구매') ? 'text-primary' : 'text-success';
                
                echo "<div class='mobile-card' onclick=\"viewDetail('$num')\">";
                echo "<div class='mobile-card-header'>";
                echo "<span>No. $num <span class='$type_class'>[$type_text]</span></span>";
                echo "<span class='$status_class'>$status_text</span>";
                echo "</div>";
                echo "<div class='mobile-card-body'>";
                
                echo "<div><span class='mobile-card-label'>명칭:</span><span class='fw-bold text-primary'>$outworkplace</span></div>";
                if ($model) echo "<div><span class='mobile-card-label'>모델:</span>$model</div>";
                if ($steel_item) echo "<div><span class='mobile-card-label'>자재:</span>$steel_item</div>";
                echo "<div><span class='mobile-card-label'>규격:</span>$spec</div>";
                
                echo "<div><span class='mobile-card-label'>수량:</span><span class='text-danger fw-bold'>$steelnum</span></div>";
                echo "<div><span class='mobile-card-label'>공급처:</span>$supplier</div>";
                echo "<div><span class='mobile-card-label'>납기일:</span>$requestdate</div>";
                echo "</div>"; // body
                echo "</div>"; // card
            }
        } else {
            echo "<div class='text-center py-4'>데이터가 없습니다.</div>";
        }
        ?>
    </div>

</div>

<script>
    function openWriteForm() {
        var url = 'write_form.php?mode=insert';
        var name = 'write_popup';
        var option = 'width=1000,height=800,scrollbars=yes';
        window.open(url, name, option);
    }

    function viewDetail(num) {
        var url = 'view.php?num=' + num;
        var name = 'view_popup';
        var option = 'width=1000,height=800,scrollbars=yes';
        window.open(url, name, option);
    }
    
    // 자식 창에서 호출할 함수 (새로고침)
    function restorePageNumber() {
        location.reload();
    }
</script>
</body>
</html>
