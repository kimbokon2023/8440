<?php
require_once __DIR__ . '/../bootstrap.php';

// 첫 화면 표시 문구
$title_message = '출하검사서';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

include includePath('load_header.php');

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

$tablename = "p_inspection";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 요청 변수 안전하게 초기화
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';

// SQL 쿼리 생성 (변수명을 $selectSql로 변경하여 다른 파일과의 충돌 방지)
if ($mode == "search") {
    if (!$search) {
        $selectSql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
    } else {
        $selectSql = "SELECT * FROM mirae8440." . $tablename . " WHERE writer LIKE '%$search%' OR subject LIKE '%$search%' ORDER BY num DESC";
    }
} else {
    $selectSql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
}

$total_row = 0;

// 디버그 모드 (개발 환경에서만 활성화)
// $debug_mode = true; // true로 설정하면 디버그 정보 표시
$debug_mode = false; // true로 설정하면 디버그 정보 표시

?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES) ?></title>

<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
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
        }
        
        .card-body {
            padding: 0.75rem 0.5rem !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 제목 이미지 최적화 */
        img.form-control {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        /* 제목 최적화 */
        h4 {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.mb-2.px-5.px-lg-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .form-control {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 1rem !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
        }
        
        #search {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
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
        
        /* jQuery DataTables 컨트롤 숨기기 */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
        }
        
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: none !important;
        }
        
        /* 테이블을 카드 형식으로 변환 */
        #myTable_wrapper {
            overflow-x: hidden !important;
        }
        
        /* 모바일에서 테이블 숨기기 */
        #myTable_wrapper {
            display: none !important;
        }
        
        /* 모바일 카드 컨테이너 표시 */
        #mobile-cards-container {
            display: block !important;
            width: 100% !important;
        }
        
        /* 모바일 카드 스타일 */
        .mobile-card {
            background-color: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            padding: 0.75rem !important;
            margin-bottom: 1rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            cursor: pointer !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .mobile-card-item {
            display: flex !important;
            padding: 0.5rem 0 !important;
            border-bottom: 1px solid #f0f0f0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        .mobile-card-item:last-child {
            border-bottom: none !important;
        }
        
        .mobile-card-label {
            font-weight: bold !important;
            min-width: 80px !important;
            width: 30% !important;
            color: #495057 !important;
            flex-shrink: 0 !important;
        }
        
        .mobile-card-value {
            flex: 1 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
        }
        
        /* DataTables 페이징 최적화 */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem !important;
            text-align: center !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem !important;
            margin: 0.25rem !important;
            font-size: 0.875rem !important;
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
        
        .modal-body img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
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

<?php include includePath("myheader.php"); ?>

<?php

// COUNT 쿼리 생성 (행 수 계산용)
$countSql = "SELECT COUNT(*) as total FROM mirae8440." . $tablename;
if ($mode == "search" && $search) {
    $countSql .= " WHERE writer LIKE '%$search%' OR subject LIKE '%$search%'";
}

// 디버그 정보 출력
if ($debug_mode) {
    echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;">';
    echo '<h5>디버그 정보</h5>';
    echo '<p><strong>Mode:</strong> ' . htmlspecialchars($mode, ENT_QUOTES) . '</p>';
    echo '<p><strong>Search:</strong> ' . htmlspecialchars($search, ENT_QUOTES) . '</p>';
    echo '<p><strong>Tablename:</strong> ' . htmlspecialchars($tablename, ENT_QUOTES) . '</p>';
    echo '<p><strong>COUNT SQL:</strong> <code>' . htmlspecialchars($countSql, ENT_QUOTES) . '</code></p>';
    echo '<p><strong>SELECT SQL:</strong> <code>' . htmlspecialchars($selectSql, ENT_QUOTES) . '</code></p>';
    
    // PDO 설정 정보
    echo '<h6>PDO 설정:</h6>';
    echo '<p><strong>PDO 드라이버:</strong> ' . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . '</p>';
    echo '<p><strong>에뮬레이션 모드:</strong> ' . ($pdo->getAttribute(PDO::ATTR_EMULATE_PREPARES) ? 'ON' : 'OFF') . '</p>';
    echo '<p><strong>에러 모드:</strong> ' . $pdo->getAttribute(PDO::ATTR_ERRMODE) . '</p>';
    echo '<p><strong>서버 버전:</strong> ' . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . '</p>';
    echo '</div>';
}

try {
    // 총 행 수 계산
    if ($debug_mode) {
        echo '<div style="background: #e8f5e9; padding: 10px; margin: 10px; border: 1px solid #4caf50;">';
        echo '<p><strong>1. COUNT 쿼리 실행 중...</strong></p>';
    }
    
    $countStmh = $pdo->query($countSql);
    
    if ($debug_mode) {
        echo '<p>✓ COUNT 쿼리 실행 완료</p>';
    }
    
    $countRow = $countStmh->fetch(PDO::FETCH_ASSOC);
    
    if ($debug_mode) {
        echo '<p><strong>COUNT 결과:</strong> ';
        print_r($countRow);
        echo '</p>';
    }
    
    $total_row = (int)($countRow['total'] ?? 0);
    $start_num = $total_row;
    
    if ($debug_mode) {
        echo '<p><strong>Total Row:</strong> ' . $total_row . '</p>';
        echo '<p><strong>Start Num:</strong> ' . $start_num . '</p>';
        echo '</div>';
    }
    
    // 데이터 조회
    if ($debug_mode) {
        echo '<div style="background: #fff3e0; padding: 10px; margin: 10px; border: 1px solid #ff9800;">';
        echo '<p><strong>2. SELECT 쿼리 실행 중...</strong></p>';
        echo '<p><strong>실행 전 SQL 변수 확인:</strong> <code>' . htmlspecialchars($selectSql, ENT_QUOTES) . '</code></p>';
        echo '<p><strong>SQL 변수 타입:</strong> ' . gettype($selectSql) . '</p>';
        
        // $sql 변수가 존재하는지 확인 (다른 파일에서 설정했을 수 있음)
        if (isset($sql)) {
            echo '<p style="color: orange;"><strong>⚠ $sql 변수가 존재합니다:</strong> <code>' . htmlspecialchars($sql, ENT_QUOTES) . '</code></p>';
        }
    }
    
    $stmh = $pdo->query($selectSql);
    
    if ($debug_mode) {
        echo '<p>✓ SELECT 쿼리 실행 완료</p>';
        echo '<p><strong>Statement 객체:</strong> ' . (is_object($stmh) ? '생성됨' : '생성 실패') . '</p>';
        echo '</div>';
    }
    
} catch (PDOException $e) {
    if ($debug_mode) {
        echo '<div style="background: #ffebee; padding: 10px; margin: 10px; border: 1px solid #f44336;">';
        echo '<h5 style="color: red;">에러 발생!</h5>';
        echo '<p><strong>에러 메시지:</strong> ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        echo '<p><strong>에러 코드:</strong> ' . htmlspecialchars($e->getCode(), ENT_QUOTES) . '</p>';
        echo '<p><strong>파일:</strong> ' . htmlspecialchars($e->getFile(), ENT_QUOTES) . '</p>';
        echo '<p><strong>줄:</strong> ' . htmlspecialchars($e->getLine(), ENT_QUOTES) . '</p>';
        echo '<p><strong>SQL State:</strong> ' . htmlspecialchars($e->errorInfo[0] ?? 'N/A', ENT_QUOTES) . '</p>';
        echo '<p><strong>Driver Code:</strong> ' . htmlspecialchars($e->errorInfo[1] ?? 'N/A', ENT_QUOTES) . '</p>';
        echo '<p><strong>Driver Message:</strong> ' . htmlspecialchars($e->errorInfo[2] ?? 'N/A', ENT_QUOTES) . '</p>';
        echo '<pre>';
        echo htmlspecialchars(print_r($e->errorInfo, true), ENT_QUOTES);
        echo '</pre>';
        echo '</div>';
    }
    
    echo '<div class="alert alert-danger" role="alert">';
    echo '데이터베이스 오류: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    echo '</div>';
    $total_row = 0;
    $start_num = 0;
    $stmh = null;
}
?>


<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?= $search ?>">
    <input type="hidden" id="page" name="page" value="<?= $page ?>">

    <div class="container mb-5">
        <div class="card mt-2 mb-2">
            <div class="card-body">

                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <img src="<?= $base_url ?>/img/p_inspection.jpg" class="form-control">
                </div>

                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <h4><?= $title_message ?></h4>
                </div>

                <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                    ▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"><i class="bi bi-search"></i> 검색</button>
                    <button type="button" class="btn btn-dark btn-sm" id="writeBtn"><i class="bi bi-pencil"></i> 신규</button> &nbsp;&nbsp;&nbsp;
                </div>

                <div class="row d-flex">
                    <!-- 모바일 카드 컨테이너 -->
                    <div id="mobile-cards-container" style="display: none; width: 100%;"></div>
                    
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">번호</th>
                                <th class="text-center">현장명</th>
                                <th class="text-center">검사자</th>
                                <th class="text-center">검사일자</th>
                                <th class="text-center">검사결과</th>
                            </tr>
                        </thead>
                        <tbody>
<?php

// 데이터 출력
if ($stmh) {
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"] ?? '';
        $parentID = $row["parentID"] ?? '';
        $subject = $row["subject"] ?? '';
        $regist_day = $row["regist_day"] ?? '';
        $check0 = $row["check0"] ?? '';
        $check1 = $row["check1"] ?? '';
        $check2 = $row["check2"] ?? '';
        $check3 = $row["check3"] ?? '';
        $check4 = $row["check4"] ?? '';
        $check5 = $row["check5"] ?? '';
        $check6 = $row["check6"] ?? '';
        $check7 = $row["check7"] ?? '';
        $check8 = $row["check8"] ?? '';
        $check9 = $row["check9"] ?? '';
        $writer = $row["writer"] ?? '';

        $result = 0;
        for ($i = 0; $i <= 9; $i++) {
            if (${'check'.$i} != '' && ${'check'.$i} != '0000-00-00') {
                $result++;
            }
        }

        if ($result == 10) {
            $result = '검사완료';
        } else {
            $result = '미검사';
        }
?>
                            <tr onclick="redirectToView('<?= $num ?>','<?= $parentID ?>', '<?= $tablename ?>')" data-num="<?= $num ?>" data-parentid="<?= $parentID ?>" data-tablename="<?= $tablename ?>">
                                <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                <td class="text-start" data-label="현장명"><?= htmlspecialchars($subject, ENT_QUOTES) ?></td>
                                <td class="text-center" data-label="검사자"><?= htmlspecialchars($writer, ENT_QUOTES) ?></td>
                                <td class="text-center" data-label="검사일자"><?= htmlspecialchars($regist_day, ENT_QUOTES) ?></td>
                                <td class="text-center" data-label="검사결과"><?= htmlspecialchars($result, ENT_QUOTES) ?></td>
                            </tr>
<?php
        $start_num--;
    }
}
?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</form>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var outinspectionpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('outinspectionpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var outinspectionpageNumber = dataTable.page.info().page + 1;
        setCookie('outinspectionpageNumber', outinspectionpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('outinspectionpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 모바일에서 카드 형식으로 데이터 렌더링
    function renderMobileCards() {
        if (window.innerWidth <= 768) {
            var container = $('#mobile-cards-container');
            container.empty();
            
            // DataTables에서 현재 페이지의 행 노드 가져오기
            var rows = dataTable.rows({page: 'current'}).nodes();
            
            if (rows.length === 0) {
                console.log('No rows found in DataTables');
                return;
            }
            
            // 각 행을 순회하며 카드 생성
            $(rows).each(function(index) {
                var $row = $(this);
                var $card = $('<div class="mobile-card"></div>');
                
                // 각 셀의 데이터 추출 (data-label 속성 사용)
                var num = $row.find('td[data-label="번호"]').text().trim() || '';
                var subject = $row.find('td[data-label="현장명"]').text().trim() || '';
                var writer = $row.find('td[data-label="검사자"]').text().trim() || '';
                var regist_day = $row.find('td[data-label="검사일자"]').text().trim() || '';
                var result = $row.find('td[data-label="검사결과"]').text().trim() || '';
                
                // data 속성에서 정보 가져오기
                var dataNum = $row.attr('data-num') || num;
                var parentID = $row.attr('data-parentid') || '';
                var tablename = $row.attr('data-tablename') || '<?php echo $tablename; ?>';
                
                // 카드 내용 생성 (XSS 방지를 위해 텍스트만 사용)
                $card.append($('<div class="mobile-card-item"></div>')
                    .append($('<span class="mobile-card-label"></span>').text('번호:'))
                    .append($('<span class="mobile-card-value"></span>').text(num)));
                
                $card.append($('<div class="mobile-card-item"></div>')
                    .append($('<span class="mobile-card-label"></span>').text('현장명:'))
                    .append($('<span class="mobile-card-value"></span>').text(subject)));
                
                $card.append($('<div class="mobile-card-item"></div>')
                    .append($('<span class="mobile-card-label"></span>').text('검사자:'))
                    .append($('<span class="mobile-card-value"></span>').text(writer)));
                
                $card.append($('<div class="mobile-card-item"></div>')
                    .append($('<span class="mobile-card-label"></span>').text('검사일자:'))
                    .append($('<span class="mobile-card-value"></span>').text(regist_day)));
                
                $card.append($('<div class="mobile-card-item"></div>')
                    .append($('<span class="mobile-card-label"></span>').text('검사결과:'))
                    .append($('<span class="mobile-card-value"></span>').text(result)));
                
                // 클릭 이벤트 추가
                $card.on('click', function() {
                    if (dataNum) {
                        redirectToView(dataNum, parentID, tablename);
                    }
                });
                
                container.append($card);
            });
            
            // 모바일 카드 컨테이너 표시, 테이블 숨기기
            if (container.children().length > 0) {
                $('#mobile-cards-container').show();
                $('#myTable_wrapper').hide();
            } else {
                console.log('No cards created, showing table instead');
                $('#mobile-cards-container').hide();
                $('#myTable_wrapper').show();
            }
        } else {
            // PC에서는 테이블 표시, 카드 숨기기
            $('#mobile-cards-container').hide();
            $('#myTable_wrapper').show();
        }
    }
    
    // 모바일에서 DataTables 컨트롤 동적으로 숨기기/보이기
    function toggleDataTablesControls() {
        if (window.innerWidth <= 768) {
            $('.dataTables_length, .dataTables_filter').hide();
        } else {
            $('.dataTables_length, .dataTables_filter').show();
        }
    }
    
    // 초기 로드 시 및 창 크기 변경 시 실행 (약간의 지연을 두어 DataTables 초기화 완료 대기)
    setTimeout(function() {
        toggleDataTablesControls();
        renderMobileCards();
    }, 300);
    
    $(window).on('resize', function() {
        toggleDataTablesControls();
        setTimeout(function() {
            renderMobileCards();
        }, 100);
    });
    
    // DataTables 초기화 후에도 모바일 최적화 적용
    dataTable.on('draw.dt', function() {
        toggleDataTablesControls();
        setTimeout(function() {
            renderMobileCards();
        }, 100);
    });
    
    // 페이지 변경 시에도 카드 업데이트
    dataTable.on('page.dt', function() {
        setTimeout(function() {
            renderMobileCards();
        }, 200);
    });
    
    // 검색 시에도 카드 업데이트
    dataTable.on('search.dt', function() {
        setTimeout(function() {
            renderMobileCards();
        }, 200);
    });

    // 검색 버튼 클릭 이벤트
    $("#searchBtn").click(function() {
        $("#board_form").submit();
    });
    
    // 검색 입력 필드에서 Enter 키 처리
    $("#search").on('keydown', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            $("#board_form").submit();
        }
    });
    
    // 신규 버튼 클릭 이벤트
    $("#writeBtn").click(function() {
        var page = outinspectionpageNumber; // 현재 페이지 번호
        var tablename = '<?php echo $tablename; ?>';
        var url = "write_form.php?tablename=" + tablename;
        customPopup(url, '출하검사서', 1400, 900);
    });

    // 서버에 작업 기록
    saveLogData('출하 검사서');
});

function restorePageNumber() {
    var savedPageNumber = getCookie('outinspectionpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, parentID, tablename) {
    var page = outinspectionpageNumber; // 현재 페이지 번호
    var url = "view.php?num=" + num + "&parentID=" + parentID + "&tablename=" + tablename;
    customPopup(url, '출하검사서', 1400, 900);
}
</script>
</body>
</html>