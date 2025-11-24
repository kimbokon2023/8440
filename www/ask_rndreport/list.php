<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$chkMobile = $_SESSION["chkMobile"] ?? false;

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
$client->addScope(Google_Service_Drive::DRIVE);

// Google Drive 서비스 초기화
$service = new Google_Service_Drive($client);

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}

// Google Drive에서 파일 썸네일 검사 및 반환
function getThumbnail($fileId, $service) {
    try {
        $file = $service->files->get($fileId, ['fields' => 'thumbnailLink']);
        return $file->thumbnailLink ?? null;
    } catch (Exception $e) {
        error_log("썸네일 가져오기 실패: " . $e->getMessage());
        return null;
    }
}

$title_message = '연구개발보고서';
?>

<?php include getDocumentRoot() . '/load_header.php'; ?>

<title> <?=$title_message?> </title>

<style>
    #showextract {
        display: inline-block;
        position: relative;
    }

    #showextractframe {
        display: none;
        position: absolute;
        width: 800px;
        z-index: 1000;
        left: 50%;
        top: 110px;
        transform: translateX(-50%);
    }

    #autocomplete-list {
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        position: absolute;
        top: 87%;
        left: 65%;
        right: 30%;
        width: 10%;
        z-index: 99;
    }

    .autocomplete-item {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    .autocomplete-item:hover {
        background-color: #e9e9e9;
    }

    th {
        white-space: nowrap;
    }
    
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
        .d-flex.mb-3.mt-2.justify-content-center h4 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* 검색 UI 최적화 - 세로 배치 */
        .d-flex.mb-1.mt-1.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-1.mt-1.justify-content-center > *,
        .d-flex.justify-content-center.align-items-center > * {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        /* 날짜 입력 필드 최적화 */
        #fromdate,
        #todate {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            font-size: 1rem !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
        }
        
        /* 검색 영역 */
        .inputWrap {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            box-sizing: border-box !important;
        }

        #search {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 1rem !important;
            padding: 0.5rem !important;
            box-sizing: border-box !important;
            margin: 0 !important;
        }

        /* 버튼 모바일 최적화 */
        .btn {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 1rem !important;
            padding: 0.5rem !important;
            margin: 0.25rem 0 !important;
            box-sizing: border-box !important;
        }

        .btn-sm {
            font-size: 1rem !important;
            padding: 0.5rem !important;
        }

        #searchBtn,
        #writeBtn {
            width: 100% !important;
            max-width: 100% !important;
            font-size: 1rem !important;
            padding: 0.5rem !important;
            margin: 0.25rem 0 !important;
            box-sizing: border-box !important;
        }
        
        /* 기간 설정 프레임 최적화 */
        #showframe {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.5rem 0 !important;
        }
        
        #showframe .card-body {
            padding: 0.5rem !important;
        }
        
        #showframe .d-flex {
            flex-wrap: wrap !important;
            gap: 0.25rem !important;
        }
        
        #showframe button {
            flex: 1 1 auto !important;
            min-width: calc(50% - 0.125rem) !important;
            font-size: 0.875rem !important;
            padding: 0.5rem !important;
        }
        
        /* 테이블 숨기기 (모바일에서는 카드로 표시) */
        #myTable_wrapper {
            display: none !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-cards-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin: 0.5rem 0;
            padding: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: calc(100% - 1rem) !important;
            max-width: calc(100% - 1rem) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            cursor: pointer;
            transition: box-shadow 0.15s ease-in-out;
        }
        
        .mobile-card:active {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        
        .mobile-card-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0.25rem;
            box-sizing: border-box !important;
        }
        
        .mobile-card-label {
            font-weight: bold;
            font-size: 0.875rem;
            color: #495057;
            margin-bottom: 0.25rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-value {
            font-size: 1rem;
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
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
        
        /* jQuery DataTables 컨트롤 숨기기 */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
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
        
        /* 자동완성 리스트 최적화 */
        #autocomplete-list {
            width: calc(100% - 1rem) !important;
            left: 0.5rem !important;
            right: 0.5rem !important;
            top: auto !important;
            position: relative !important;
            margin-top: 0.5rem !important;
            box-sizing: border-box !important;
        }
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        /* 모바일 카드 숨기기 */
        #mobile-cards-container {
            display: none !important;
        }
    }
</style>
</head>
<body>

    <?php
    $tablename = 'eworks';

    require_once(includePath('myheader.php'));

    // 모바일이면 특정 CSS 적용 (기존 큰 폰트 제거, 모바일 최적화 CSS로 대체)

    // _request.php에서 정의될 변수들 초기화
    $page = 1;
    $scale = 50;
    $mode = '';
    $search = '';

    include getDocumentRoot() . '/eworks/_request.php';

    $pdo = db_connect();

    // 현재 날짜
    $currentDate = date("Y-m-d");

    // 요청 파라미터 초기화
    $fromdate = $_REQUEST['fromdate'] ?? '';
    $todate = $_REQUEST['todate'] ?? '';

    // fromdate 또는 todate가 빈 문자열이거나 null인 경우
    if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
        $fromdate = date("2024-01-01");
        $todate = $currentDate;
        $Transtodate = $todate;
    } else {
        // fromdate와 todate가 모두 설정된 경우
        $Transtodate = $todate;
    }

    $SettingDate = "indate";

    $Andis_deleted = " AND (is_deleted IS NULL or is_deleted='0') AND eworks_item='" . $title_message . "' ";
    $Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='0') AND eworks_item='" . $title_message . "' ";

    $common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . " ORDER BY ";

    $a = $common . " num DESC ";

    $sql = "select * from " . $DB . ".eworks " . $a;

    // 검색을 위해 모든 검색변수 공백제거
    $search = str_replace(' ', '', $search);

    if ($mode == "search") {
        if ($search == "") {
            $sql = "select * from {$DB}.eworks " . $a;
        } elseif ($search != "") {
            $sql = "select * from {$DB}.eworks where ((outdate like '%$search%') or (replace(outworkplace,' ','') like '%$search%' ) ";
            $sql .= "or (steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (first_writer like '%$search%') or (payment like '%$search%') or (supplier like '%$search%') or (request_comment like '%$search%') ) " . $Andis_deleted . " order by num desc ";
        }
    }
    if ($mode == "") {
        $sql = "select * from {$DB}.eworks " . $a;
    }

    $nowday = date("Y-m-d");
    $dateCon = " AND between date('$fromdate') and date('$Transtodate') ";

    // _row.php에서 사용될 변수 초기화
    $num = '';
    $indate = '';
    $author = '';
    $outworkplace = '';
    $store = '';
    $e_line = '';
    $e_confirm = '';

    try {
        $stmh = $pdo->query($sql);
        $total_row = $stmh->rowCount();
        ?>

        <form name="board_form" id="board_form" method="post" action="list.php?mode=search">

            <input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>">

            <div class="container-fluid">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex mb-3 mt-2 justify-content-center align-items-center">
                            <h4> <?=$title_message?> </h4>
                            <button type="button" class="btn btn-dark btn-sm mx-3" onclick='location.reload();' title="새로고침">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
                            <!-- 기간부터 검색까지 연결 묶음 start -->
                            <span id="showdate" class="btn btn-dark btn-sm mx-2"> 기간 </span>
                            <div id="showframe" class="card">
                                <div class="card-header" style="padding:2px;">
                                    <div class="d-flex justify-content-center align-items-center">
                                        기간 설정
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange" onclick='alldatesearch()'> 전체 </button>
                                        <button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange" onclick='pre_year()'> 전년도 </button>
                                        <button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='three_month_ago()'> M-3월 </button>
                                        <button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='prepre_month()'> 전전월 </button>
                                        <button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='pre_month()'> 전월 </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange" onclick='this_today()'> 오늘 </button>
                                        <button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='this_month()'> 당월 </button>
                                        <button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange" onclick='this_year()'> 당해년도 </button>
                                    </div>
                                </div>
                            </div>
                            <input type="date" id="fromdate" name="fromdate" size="12" class="form-control" style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일"> &nbsp; ~ &nbsp;
                            <input type="date" id="todate" name="todate" size="12" class="form-control" style="width:100px;" value="<?=$todate?>" placeholder="기간 끝"> &nbsp;
                            &nbsp;&nbsp;

                            <?php if ($chkMobile) { ?>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <?php } ?>

                            &nbsp;
                            <div class="inputWrap">
                                <input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off" class="form-control w-auto mx-1"> &nbsp;
                                <button class="btnClear"></button>
                            </div>
                            <div id="autocomplete-list">
                            </div>
                            &nbsp;
                            <button type="button" id="searchBtn" class="btn btn-dark btn-sm"> <i class="bi bi-search"></i> </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-dark btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규 </button>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="myTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" scope="col" style="width:5%;">번호</th>
                                        <th class="text-center" scope="col" style="width:120px;">작성일</th>
                                        <th class="text-center" scope="col"> 작성자 </th>
                                        <th class="text-center w-35" scope="col"> 제목 </th>
                                        <th class="text-center w-15" scope="col"> 검토 </th>
                                        <th class="text-center" scope="col"> 결재라인 </th>
                                        <th class="text-center" scope="col"> 결재내역 </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($page <= 1)
                                        $start_num = $total_row;
                                    else
                                        $start_num = $total_row - ($page - 1) * $scale;

                                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                        include getDocumentRoot() . '/eworks/_row.php';

                                        echo '<tr style="cursor:pointer;" data-id="' . $num . '" onclick="redirectToView(' . $num . ')">';
                                        ?>
                                        <td class="text-center" data-label="번호"><?= htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-center" data-label="작성일" data-order="<?= htmlspecialchars($indate, ENT_QUOTES, 'UTF-8') ?>"> <?= htmlspecialchars($indate, ENT_QUOTES, 'UTF-8') ?> </td>
                                        <td class="text-center" data-label="작성자"> <?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?> </td>
                                        <td class="text-start" data-label="제목"><?= htmlspecialchars($outworkplace, ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-start" data-label="검토"><?= htmlspecialchars($store, ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-start" data-label="결재라인"><?= htmlspecialchars(str_replace('!', ' → ', $e_line), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-start" data-label="결재내역"><?= htmlspecialchars(str_replace('!', ' → ', $e_confirm), ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                    <?php
                                    $start_num--;
                                    }
                                } catch (PDOException $ex) {
                                    error_log("연구개발보고서 목록 조회 오류: " . $ex->getMessage());
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- 모바일 카드 컨테이너 -->
                <div id="mobile-cards-container"></div>
            </div>

        </form>

    <div class="container-fluid">
        <?php include '../footer_sub.php'; ?>
    </div>

    <script>
        // ES5 호환 코드 (PHP 7.3 환경에서 권장)
        var dataTable; // DataTables 인스턴스 전역 변수
        var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

        // 모바일 카드 렌더링 함수
        function renderMobileCards() {
            if (window.innerWidth > 768) {
                return; // PC에서는 실행하지 않음
            }
            
            var $container = $('#mobile-cards-container');
            $container.empty();
            
            // DataTables에서 현재 표시된 행만 가져오기
            var visibleRows = dataTable.rows({search: 'applied', page: 'current'}).nodes();
            
            if (visibleRows.length === 0) {
                $container.html('<div class="mobile-card"><div class="mobile-card-value">데이터가 없습니다.</div></div>');
                return;
            }
            
            // 표시된 행들을 순회하며 카드 생성
            $(visibleRows).each(function() {
                var $row = $(this);
                
                // DataTables가 숨긴 행은 건너뛰기
                if ($row.hasClass('odd') || $row.hasClass('even')) {
                    var num = $row.find('td:eq(0)').text().trim();
                    var indate = $row.find('td:eq(1)').text().trim();
                    var author = $row.find('td:eq(2)').text().trim();
                    var outworkplace = $row.find('td:eq(3)').html() || '';
                    var store = $row.find('td:eq(4)').text().trim();
                    var e_line = $row.find('td:eq(5)').text().trim();
                    var e_confirm = $row.find('td:eq(6)').text().trim();
                    var onclickAttr = $row.attr('onclick');
                    var itemNum = '';
                    
                    // onclick에서 num 추출
                    if (onclickAttr) {
                        var match = onclickAttr.match(/redirectToView\((\d+)\)/);
                        if (match) {
                            itemNum = match[1];
                        }
                    }
                    
                    // HTML 이스케이프 처리
                    var escapeHtml = function(text) {
                        var map = {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        };
                        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
                    };
                    
                    var cardHtml = '<div class="mobile-card" onclick="redirectToView(' + escapeHtml(itemNum) + ')">';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">번호</span><span class="mobile-card-value">' + escapeHtml(num) + '</span></div>';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">작성일</span><span class="mobile-card-value">' + escapeHtml(indate) + '</span></div>';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">작성자</span><span class="mobile-card-value">' + escapeHtml(author) + '</span></div>';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">제목</span><span class="mobile-card-value">' + outworkplace + '</span></div>';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">검토</span><span class="mobile-card-value">' + escapeHtml(store) + '</span></div>';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">결재라인</span><span class="mobile-card-value">' + escapeHtml(e_line) + '</span></div>';
                    cardHtml += '<div class="mobile-card-item"><span class="mobile-card-label">결재내역</span><span class="mobile-card-value">' + escapeHtml(e_confirm) + '</span></div>';
                    cardHtml += '</div>';
                    
                    $container.append(cardHtml);
                }
            });
        }

        // 화면 크기 변경 시 카드 재렌더링
        $(window).on('resize', function() {
            if (window.innerWidth <= 768) {
                setTimeout(renderMobileCards, 100);
            }
        });

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
                "order": [[0, 'desc']],
                "drawCallback": function(settings) {
                    // DataTables 그리기 완료 후 모바일 카드 렌더링
                    if (window.innerWidth <= 768) {
                        setTimeout(renderMobileCards, 100);
                    }
                }
            });

            // 페이지 번호 복원 (초기 로드 시)
            var savedPageNumber = getCookie('requestetcpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }

            // 페이지 변경 이벤트 리스너
            dataTable.on('page.dt', function() {
                var requestetcpageNumber = dataTable.page.info().page + 1;
                setCookie('requestetcpageNumber', requestetcpageNumber, 10);
                if (window.innerWidth <= 768) {
                    setTimeout(renderMobileCards, 100);
                }
            });
            
            // 검색 이벤트 리스너
            dataTable.on('search.dt', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(renderMobileCards, 100);
                }
            });

            // 페이지 길이 셀렉트 박스 변경 이벤트 처리
            $('#myTable_length select').on('change', function() {
                var selectedValue = $(this).val();
                dataTable.page.len(selectedValue).draw();

                // 변경 후 현재 페이지 번호 복원
                var savedPageNumber = getCookie('requestetcpageNumber');
                if (savedPageNumber) {
                    dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
                }
            });
            
            // 초기 모바일 카드 렌더링
            if (window.innerWidth <= 768) {
                setTimeout(renderMobileCards, 300);
            }
        });

        function restorePageNumber() {
            var savedPageNumber = getCookie('requestetcpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
            }
        }

        function blinker() {
            $('.blinking').fadeOut(500);
            $('.blinking').fadeIn(500);
        }
        setInterval(blinker, 1000);

        $(document).ready(function() {
            // Event listener for keydown on #search
            $("#search").keydown(function(event) {
                // Check if the pressed key is 'Enter'
                if (event.key === "Enter" || event.keyCode === 13) {
                    // Prevent the default action to stop form submission
                    event.preventDefault();
                    // Trigger click event on #searchBtn
                    $("#searchBtn").click();
                }
            });
        });

        $(document).ready(function() {

            $("#writeBtn").click(function() {
                var tablename = $("#tablename").val();
                var url = "write_form.php?tablename=" + tablename;

                customPopup(url, '등록', 1300, 900);
            });

            $("#searchBtn").click(function() {
                // 페이지 번호를 1로 설정
                var currentpageNumber = 1;
                setCookie('currentpageNumber', currentpageNumber, 10);

                // Set dateRange to '전체' and trigger the change event
                $('#dateRange').val('전체').change();
                document.getElementById('board_form').submit();
            });

        });

        function redirectToView(num) {
            var tablename = $("#tablename").val();

            var url = "write_form.php?mode=view&num=" + num +
                "&tablename=" + tablename;
            customPopup(url, '', 1300, 900);
        }

        // 서버에 작업 기록
        $(document).ready(function() {
            saveLogData('<?=$title_message?>');
        });
    </script>
</body>
</html>