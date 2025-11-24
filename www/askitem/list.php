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

$title_message = '품의서';
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

    /* 모바일 최적화 */
    @media (max-width: 768px) {
        /* body와 html의 width 제한 */
        html, body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            font-size: 16px !important;
        }

        /* 컨테이너 모바일 최적화 */
        .container,
        .container-fluid {
            max-width: 100vw !important;
            padding: 5px !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* 카드 모바일 최적화 */
        .card {
            margin: 0.25rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        .card-body {
            padding: 0.4rem 0.3rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        .card-header {
            padding: 0.4rem 0.3rem !important;
            font-size: 0.9rem !important;
        }

        .card-header h4,
        .card-header h5 {
            font-size: 0.9rem !important;
            margin: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 기간 설정 영역 모바일 최적화 */
        .d-flex.justify-content-center {
            flex-wrap: wrap !important;
            gap: 0.25rem !important;
            justify-content: flex-start !important;
            align-items: center !important;
            padding: 0.3rem 0.25rem !important;
            margin: 0.25rem 0 !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 기간 설정 버튼 */
        #showdate {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            margin-right: 0.3rem !important;
            box-sizing: border-box !important;
            min-width: 50px !important;
            max-width: 100% !important;
        }

        /* 기간 설정 프레임 */
        #showframe {
            position: absolute !important;
            z-index: 1000 !important;
            display: none !important;
        }

        /* 기간 설정 카드 내부 버튼 */
        #showframe .card-body .d-flex {
            flex-wrap: wrap !important;
            gap: 0.25rem !important;
            justify-content: center !important;
        }

        #showframe .card-body .btn {
            font-size: 0.65rem !important;
            padding: 0.25rem 0.4rem !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            max-width: 100% !important;
        }

        /* 날짜 입력 필드 */
        #fromdate,
        #todate {
            width: auto !important;
            min-width: 90px !important;
            max-width: calc(50% - 0.5rem) !important;
            font-size: 0.7rem !important;
            padding: 0.3rem 0.35rem !important;
            flex: 0 0 auto !important;
            margin: 0 0.15rem !important;
            box-sizing: border-box !important;
        }

        /* 검색 영역 */
        .inputWrap {
            flex: 1 1 auto !important;
            min-width: 120px !important;
            max-width: calc(100% - 100px) !important;
            margin-right: 0.3rem !important;
            box-sizing: border-box !important;
        }

        #search {
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            font-size: 0.7rem !important;
            padding: 0.3rem 0.35rem !important;
            box-sizing: border-box !important;
        }

        /* 버튼 모바일 최적화 */
        .btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.4rem !important;
            white-space: nowrap !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            flex: 0 0 auto !important;
            margin: 0.1rem !important;
        }

        .btn-sm {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.35rem !important;
        }

        #searchBtn,
        #writeBtn {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            min-width: 50px !important;
            max-width: 100% !important;
        }

        /* 테이블 모바일 최적화 - 카드 형식으로 변환 */
        .table-responsive {
            overflow-x: hidden !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* DataTables 모바일 최적화 - 카드 형식 */
        .dataTables_wrapper {
            font-size: 0.75rem !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* 테이블 헤더 숨기기 (모바일) */
        .dataTables_wrapper thead {
            display: none !important;
        }

        /* 모바일에서 DataTables 행을 카드로 표시 */
        .dataTables_wrapper tbody tr {
            display: block !important;
            width: 100% !important;
            margin-bottom: 0.4rem !important;
            background: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.075) !important;
            padding: 0.4rem !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
        }

        .dataTables_wrapper tbody tr:hover {
            background: #f8f9fa !important;
            box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1) !important;
        }

        .dataTables_wrapper tbody tr td {
            display: flex !important;
            width: 100% !important;
            padding: 0.3rem 0.4rem !important;
            text-align: left !important;
            border: none !important;
            border-bottom: 1px solid #f0f0f0 !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            flex-wrap: wrap !important;
        }

        .dataTables_wrapper tbody tr td:last-child {
            border-bottom: none !important;
        }

        .dataTables_wrapper tbody tr td .mobile-label {
            font-weight: bold !important;
            display: inline-block !important;
            min-width: 30% !important;
            margin-right: 0.5rem !important;
            color: #495057 !important;
            font-size: 0.75rem !important;
            flex-shrink: 0 !important;
        }

        .dataTables_wrapper tbody tr td .mobile-value {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            font-size: 0.75rem !important;
            color: #212529 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* DataTables 상단 컨트롤 숨기기 (모바일) */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: none !important;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.7rem !important;
            margin-bottom: 0.5rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.4rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 0.25rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
            margin: 0 0.1rem !important;
            box-sizing: border-box !important;
            max-width: 100% !important;
        }

        /* 모든 요소가 카드 내부에 머물도록 */
        .card *,
        .container *,
        .container-fluid * {
            box-sizing: border-box !important;
            max-width: 100% !important;
        }

        .card button,
        .card .btn,
        .card span,
        .card input,
        .container button,
        .container .btn,
        .container span,
        .container input,
        .card-body *,
        .card-header * {
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }

        /* 카드 내부 모든 요소가 넘치지 않도록 */
        .card {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        .card-body {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        /* 폼 요소 모바일 최적화 */
        form {
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        form * {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 제목 영역 모바일 최적화 */
        h4 {
            font-size: 0.9rem !important;
            margin: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 자동완성 리스트 모바일 최적화 */
        #autocomplete-list {
            width: 100% !important;
            max-width: 100% !important;
            left: 0 !important;
            right: 0 !important;
            box-sizing: border-box !important;
        }
    }
</style>
</head>
<body>

    <?php
    $tablename = 'eworks';

    if (!$chkMobile) {
        require_once(includePath('myheader.php'));
    }

    // 모바일이면 특정 CSS 적용
    if ($chkMobile) {
        echo '<style>
        table th, table td, h4, .form-control, span {
            font-size: 22px;
        }
        h4 {
            font-size: 40px;
        }
        .btn-sm {
            font-size: 30px;
        }
    </style>';
    }

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
        // 오늘 날짜 기준 1년 전부터 오늘까지
        $fromdate = date("Y-m-d", strtotime("-1 year", strtotime($currentDate)));
        $todate = $currentDate;
        $Transtodate = $todate;
    } else {
        // fromdate와 todate가 모두 설정된 경우
        $Transtodate = $todate;
    }

    $SettingDate = "indate";

    $Andis_deleted = " AND (is_deleted IS NULL or is_deleted='0') AND eworks_item='품의서' ";
    $Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='0') AND eworks_item='품의서' ";

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
    $al_content = '';
    $request_comment = '';
    $suppliercost = '';

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
                            <span id="showdate" class="btn btn-dark btn-sm"> 기간 </span>&nbsp;
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
                            <?php } ?>&nbsp;
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
                                        <th class="text-center" scope="col"> 기안인 </th>
                                        <th class="text-center" scope="col"> 제목</th>
                                        <th class="text-center" scope="col"> 구매처</th>
                                        <th class="text-center" scope="col"> 품의 내역</th>
                                        <th class="text-center" scope="col"> 품의 사유</th>
                                        <th class="text-center" scope="col"> 예상 비용 금액</th>
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
                                        <td class="text-center"><?= $start_num ?></td>
                                        <td class="text-center" data-order="<?= $indate ?>"> <?=$indate?> </td>
                                        <td class="text-center"> <?= $author ?> </td>
                                        <td><?= $outworkplace ?></td>
                                        <td><?= $store ?></td>
                                        <td><?= $al_content ?></td>
                                        <td><?= $request_comment ?></td>
                                        <td class="text-end"><?= $suppliercost ?></td>
                                        </tr>
                                        <?php
                                        $start_num--;
                                    }
                                } catch (PDOException $ex) {
                                    error_log("품의서 목록 조회 오류: " . $ex->getMessage());
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    <div class="container-fluid">
        <?php include '../footer_sub.php'; ?>
    </div>

    <script>
        var dataTable; // DataTables 인스턴스 전역 변수
        var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
                    // 모바일에서 카드 형식으로 표시하기 위해 라벨과 값 분리
                    var isMobile = window.innerWidth <= 768;
                    var api = this.api();
                    var columns = ['번호', '작성일', '기안인', '제목', '구매처', '품의 내역', '품의 사유', '예상 비용 금액'];
                    
                    api.rows().every(function() {
                        var row = this.node();
                        var cells = $(row).find('td');
                        cells.each(function(index) {
                            if (columns[index]) {
                                var $cell = $(this);
                                
                                if (isMobile) {
                                    // 모바일: 라벨과 값으로 분리
                                    var cellText = $cell.text().trim();
                                    
                                    // 이미 변환된 경우 스킵
                                    if ($cell.find('.mobile-label').length === 0) {
                                        // 라벨과 값으로 분리
                                        $cell.html(
                                            '<span class="mobile-label">' + columns[index] + '</span>' +
                                            '<span class="mobile-value">' + cellText + '</span>'
                                        );
                                    }
                                } else {
                                    // PC: 원래 형식 유지 (라벨 제거)
                                    if ($cell.find('.mobile-label').length > 0) {
                                        $cell.html($cell.find('.mobile-value').text());
                                    }
                                }
                            }
                        });
                    });
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
            });

            // 페이지 길이 셀렉트 박스 변경 이벤트 처리
            $('#myTable_length select').on('change', function() {
                var selectedValue = $(this).val();
                dataTable.page.len(selectedValue).draw();

                // 변경 후 현재 페이지 번호 복원
                savedPageNumber = getCookie('requestetcpageNumber');
                if (savedPageNumber) {
                    dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
                }
            });

            // 초기 로드 시 모바일 카드 형식 적용
            if (window.innerWidth <= 768) {
                dataTable.draw(false);
            }

            // 윈도우 리사이즈 이벤트 처리 (모바일/PC 전환 시)
            var resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (dataTable) {
                        dataTable.draw(false);
                    }
                }, 250);
            });

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

            // 자재현황 클릭시
            $("#rawmaterialBtn").click(function() {
                popupCenter('/ceiling/list_part_table.php?menu=no', '부자재현황보기', 1050, 950);
            });

            $("#writeBtn").click(function() {
                var tablename = $("#tablename").val();
                var url = "write_form.php?tablename=" + tablename;
                customPopup(url, '등록', 800, 800);
            });

            $("#searchBtn").click(function() {
                // 페이지 번호를 1로 설정
                currentpageNumber = 1;
                setCookie('currentpageNumber', currentpageNumber, 10);

                // Set dateRange to '전체' and trigger the change event
                $('#dateRange').val('전체').change();
                document.getElementById('board_form').submit();
            });

            // 서버에 작업 기록
            saveLogData('<?=$title_message?>');
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

        function redirectToView(num) {
            var tablename = $("#tablename").val();

            var url = "write_form.php?mode=view&num=" + num + "&tablename=" + tablename;
            customPopup(url, '', 800, 800);
        }
    </script>

    </body>
    </html>