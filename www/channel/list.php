<?php
require_once __DIR__ . '/../bootstrap.php';
include includePath('session.php');

// Session-based access control
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 첫 화면 표시 문구
$title_message = '핫 유튜브 정보 분석';
?>

<?php include getDocumentRoot() . '/load_header.php'; ?>

<title><?php echo htmlspecialchars($title_message); ?></title>

<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
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
        .container,
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
        .d-flex.mt-2.mb-3.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-2.mb-3.justify-content-center h4 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0 !important;
        }
        
        .d-flex.mt-2.mb-3.justify-content-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 검색 UI 최적화 */
        .d-flex.mb-2.px-5.px-lg-2.mt-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2 > * {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2 input#search {
            width: 100% !important;
            max-width: 100% !important;
            height: 40px !important;
            font-size: 1rem !important;
        }
        
        .d-flex.mb-2.px-5.px-lg-2.mt-2 button {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            margin: 0.25rem 0 !important;
        }
        
        /* DataTables 컨트롤 숨기기 */
        #myTable_wrapper .dataTables_length,
        #myTable_wrapper .dataTables_filter {
            display: none !important;
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
            padding: 0.5rem !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .mobile-card-item {
            margin-bottom: 0.5rem;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-label {
            font-weight: bold;
            color: #495057;
            margin-right: 0.5rem;
            display: inline-block;
            min-width: 60px;
        }
        
        .mobile-card-value {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
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
    }
    
    /* PC 환경에서는 모바일 카드 숨기기 */
    @media (min-width: 769px) {
        #mobile-cards-container {
            display: none !important;
        }
    }
</style>

</head>

<body>

<?php require_once(includePath('myheader.php')); ?>

<?php
// Initialize request variables
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';
$page = $_REQUEST["page"] ?? '1';

// Initialize pagination variables
$current_page = 1;
$page_scale = 10;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$tablename = "channel";

// Build SQL query
if ($mode == "search") {
    if (!$search) {
        $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
    } else {
        $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE name LIKE '%$search%' OR subject LIKE '%$search%' OR nick LIKE '%$search%' OR regist_day LIKE '%$search%' ORDER BY num DESC";
    }
} else {
    $sql = "SELECT * FROM mirae8440." . $tablename . " ORDER BY num DESC";
}


// 전체 레코드수를 파악한다.
try {
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();
?>

    <form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?php echo urlencode($search); ?>">

        <div class="container justify-content-center">
            <div class="card mt-2">
                <div class="card-body">

                    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page); ?>">

                    <div class="d-flex mt-2 mb-3 justify-content-center">
                        <h4><?php echo htmlspecialchars($title_message); ?></h4>
                        <button type="button" class="btn btn-dark btn-sm mx-3" onclick="location.reload();" title="새로고침">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>

                    <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                        ▷ <?php echo $total_row; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                        <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                            <i class="bi bi-search"></i> 검색
                        </button>
                        <button type="button" class="btn btn-dark btn-sm" id="writeBtn">
                            <i class="bi bi-pencil"></i> 신규
                        </button> &nbsp;&nbsp;&nbsp;
                    </div>

                    <div class="row d-flex">
                        <table class="table table-hover" id="myTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">구분</th>
                                    <th class="text-center">글제목</th>
                                    <th class="text-center">작성</th>
                                    <th class="text-center">등록일</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $start_num = $total_row;

                                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                    $item_num = $row["num"] ?? '';
                                    $item_id = $row["id"] ?? '';
                                    $item_name = $row["name"] ?? '';
                                    $item_nick = $row["nick"] ?? '';
                                    $item_hit = $row["hit"] ?? '';
                                    $item_date = $row["regist_day"] ?? '';
                                    $item_date = substr($item_date, 0, 10);
                                    $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
                                    $division = $row["division"] ?? '';
                                ?>
                                    <tr onclick="redirectToView('<?php echo htmlspecialchars($item_num); ?>', '<?php echo htmlspecialchars($tablename); ?>')" 
                                        data-num="<?php echo htmlspecialchars($item_num); ?>"
                                        data-division="<?php echo htmlspecialchars($division); ?>"
                                        data-subject="<?php echo htmlspecialchars(strip_tags($item_subject)); ?>"
                                        data-nick="<?php echo htmlspecialchars($item_nick); ?>"
                                        data-date="<?php echo htmlspecialchars($item_date); ?>"
                                        data-tablename="<?php echo htmlspecialchars($tablename); ?>">
                                        <td class="text-center" data-label="번호"><?php echo $start_num; ?></td>
                                        <td class="text-center" data-label="구분"><?php echo htmlspecialchars($division); ?></td>
                                        <td data-label="글제목"><?php echo $item_subject; ?></td>
                                        <td class="text-center" data-label="작성"><?php echo htmlspecialchars($item_nick); ?></td>
                                        <td class="text-center" data-label="등록일"><?php echo htmlspecialchars($item_date); ?></td>
                                    </tr>

                                <?php
                                    $start_num--;
                                }
                            } catch (PDOException $Exception) {
                                print "오류: " . $Exception->getMessage();
                            }

                            // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
                            $start_page = ($current_page - 1) * $page_scale + 1;
                            // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
                            $end_page = $start_page + $page_scale - 1;
                                ?>

                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 모바일 카드 컨테이너 -->
                    <div id="mobile-cards-container"></div>

                </div>
            </div>
        </div>

    </form>

    <script>
        var dataTable; // DataTables 인스턴스 전역 변수
        var RnDlistpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

        // 모바일 카드 렌더링 함수
        function renderMobileCards() {
            if (window.innerWidth > 768) {
                $('#mobile-cards-container').html('');
                return; // PC 환경에서는 실행하지 않음
            }
            
            // DataTables가 초기화되지 않았으면 리턴
            if (!dataTable || !dataTable.rows) {
                console.log('DataTables not initialized yet');
                return;
            }
            
            var $container = $('#mobile-cards-container');
            $container.html(''); // 기존 내용 초기화
            
            try {
                // DataTables에서 현재 표시된 행 가져오기
                var rows = dataTable.rows({ filter: 'applied', page: 'current' }).nodes();
                
                if (!rows || rows.length === 0) {
                    console.log('No rows found in DataTables');
                    return;
                }
                
                $(rows).each(function() {
                    var $row = $(this);
                    var num = $row.find('td:eq(0)').text().trim();
                    var division = $row.find('td:eq(1)').text().trim();
                    var subject = $row.find('td:eq(2)').text().trim();
                    var nick = $row.find('td:eq(3)').text().trim();
                    var date = $row.find('td:eq(4)').text().trim();
                    var itemNum = $row.attr('data-num');
                    var tablename = $row.attr('data-tablename');
                    
                    // data 속성이 없으면 td에서 직접 가져오기 시도
                    if (!itemNum) {
                        itemNum = $row.closest('tr').attr('data-num');
                    }
                    if (!tablename) {
                        tablename = $row.closest('tr').attr('data-tablename');
                    }
                    
                    // 여전히 없으면 기본값 사용
                    if (!itemNum || !tablename) {
                        console.log('Missing data attributes, skipping row');
                        return;
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
                        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
                    };
                    
                    var cardHtml = '<div class="mobile-card" onclick="redirectToView(\'' + 
                        escapeHtml(itemNum) + '\', \'' + escapeHtml(tablename) + '\')">' +
                        '<div class="mobile-card-item">' +
                        '<span class="mobile-card-label">번호:</span>' +
                        '<span class="mobile-card-value">' + escapeHtml(num) + '</span>' +
                        '</div>' +
                        '<div class="mobile-card-item">' +
                        '<span class="mobile-card-label">구분:</span>' +
                        '<span class="mobile-card-value">' + escapeHtml(division) + '</span>' +
                        '</div>' +
                        '<div class="mobile-card-item">' +
                        '<span class="mobile-card-label">글제목:</span>' +
                        '<span class="mobile-card-value">' + escapeHtml(subject) + '</span>' +
                        '</div>' +
                        '<div class="mobile-card-item">' +
                        '<span class="mobile-card-label">작성:</span>' +
                        '<span class="mobile-card-value">' + escapeHtml(nick) + '</span>' +
                        '</div>' +
                        '<div class="mobile-card-item">' +
                        '<span class="mobile-card-label">등록일:</span>' +
                        '<span class="mobile-card-value">' + escapeHtml(date) + '</span>' +
                        '</div>' +
                        '</div>';
                    
                    $container.append(cardHtml);
                });
                
                console.log('Mobile cards rendered: ' + $(rows).length);
            } catch (error) {
                console.error('Error rendering mobile cards:', error);
            }
        }

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
                    // 모바일 환경에서 카드 렌더링 (약간의 지연을 두어 완전히 렌더링된 후 실행)
                    if (window.innerWidth <= 768) {
                        setTimeout(function() {
                            renderMobileCards();
                        }, 100);
                    } else {
                        $('#mobile-cards-container').html('');
                    }
                },
                "initComplete": function(settings, json) {
                    // 초기화 완료 후 모바일 카드 렌더링
                    if (window.innerWidth <= 768) {
                        setTimeout(function() {
                            renderMobileCards();
                        }, 200);
                    }
                }
            });
            
            // 화면 크기 변경 시 카드 재렌더링
            var resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth <= 768) {
                        renderMobileCards();
                    } else {
                        $('#mobile-cards-container').html('');
                    }
                }, 250);
            });

            // 페이지 번호 복원 (초기 로드 시)
            var savedPageNumber = getCookie('RnDlistpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }

            // 페이지 변경 이벤트 리스너
            dataTable.on('page.dt', function() {
                var RnDlistpageNumber = dataTable.page.info().page + 1;
                setCookie('RnDlistpageNumber', RnDlistpageNumber, 10); // 쿠키에 페이지 번호 저장
            });

            // 페이지 길이 셀렉트 박스 변경 이벤트 처리
            $('#myTable_length select').on('change', function() {
                var selectedValue = $(this).val();
                dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

                // 변경 후 현재 페이지 번호 복원
                savedPageNumber = getCookie('RnDlistpageNumber');
                if (savedPageNumber) {
                    dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
                }
            });

            // 신규 작성 버튼 클릭
            $("#writeBtn").click(function() {
                var page = RnDlistpageNumber; // 현재 페이지 번호
                var tablename = '<?php echo htmlspecialchars($tablename); ?>';
                var url = "write_form.php?tablename=" + encodeURIComponent(tablename);
                customPopup(url, '<?php echo htmlspecialchars($title_message); ?>', 1300, 850);
            });
            
            // 검색 버튼 클릭
            $("#searchBtn").click(function() {
                SearchEnter();
            });

            // 서버에 작업 기록
            var title_message = '<?php echo htmlspecialchars($title_message); ?>';
            saveLogData(title_message);
        });

        function restorePageNumber() {
            var savedPageNumber = getCookie('RnDlistpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
            }
        }

        function redirectToView(num, tablename) {
            var page = RnDlistpageNumber; // 현재 페이지 번호
            var url = "view.php?num=" + encodeURIComponent(num) + "&tablename=" + encodeURIComponent(tablename);
            customPopup(url, '', 1200, 900);
        }
        
        function SearchEnter() {
            if (event.keyCode === 13 || event.type === 'click') {
                var search = $('#search').val();
                location.href = 'list.php?mode=search&search=' + encodeURIComponent(search);
            }
        }
    </script>
</body>
</html>

