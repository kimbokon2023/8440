<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 로그인 확인
if (!isset($_SESSION["level"])) {
    $baseUrl = getBaseUrl();
    $_SESSION["url"] = $baseUrl . '/idea/index.php?user_name=' . urlencode($user_name);
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$menu = $_REQUEST["menu"] ?? '';
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';
$voc_alert = $_REQUEST["voc_alert"] ?? '';
$ma_alert = $_REQUEST["ma_alert"] ?? '';
$order_alert = $_REQUEST["order_alert"] ?? '';
$page = $_REQUEST["page"] ?? 1;

// 변수 초기화
$title_message = '직원 제안제도 운영';
$tablename = 'idea';
$admin = 0;
$total_row = 0;
$approvalwait = 0;
$Transtodate = '';

// rowDB.php에서 사용될 변수들 사전 초기화
$num = '';
$place = '';
$occur = '';
$errortype = '';
$emember = '';
$content = '';
$method = '';
$filename = '';
$serverfilename = '';
$approve = '';
$payment = '';
$firstone = '';

// 관리자 권한 확인
if (in_array($user_name, array('소현철', '김보곤', '최장중', '이경묵'))) {
    $admin = 1;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// IP 주소 기록
$ip_address = $_SERVER["REMOTE_ADDR"] ?? 'unknown';
$ip_address = 'ip_(아이디어) : ' . $ip_address;

// 접속 로그 기록
try {
    $data = date("Y-m-d H:i:s") . " - " . $user_id . " - " . $user_name . '  ' . $ip_address;
    
    $pdo->beginTransaction();
    $sql = "INSERT INTO {$DB}.logdata(data) VALUES(?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
    
} catch (PDOException $ex) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Log insert error in idea/index.php: " . $ex->getMessage());
    // 로그 실패는 계속 진행
}

// 결재권자 결재정보 보기
if ($admin == 1) {
    try {
        $sql = "SELECT * FROM {$DB}.idea WHERE approve <> '처리완료'";
        $stmh = $pdo->query($sql);
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include "rowDB.php";
            $approvalwait += 1;
        }
        
    } catch (PDOException $ex) {
        error_log("Approval query error in idea/index.php: " . $ex->getMessage());
    }
}

// 기간 설정
if (empty($fromdate)) {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if (empty($todate)) {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . ' +1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

// SQL 쿼리 생성
if ($mode === "search" || empty($mode)) {
    if (empty($search)) {
        $sql = "SELECT * FROM {$DB}.idea ORDER BY num DESC";
    } elseif ($search === "결재상신 1차결재") {
        $sql = "SELECT * FROM {$DB}.idea WHERE approve = '결재상신' OR approve = '1차결재' ORDER BY num DESC";
        $search = null;
    } else {
        // SQL Injection 방지를 위한 Prepared Statement 사용
        $sql = "SELECT * FROM {$DB}.idea 
                WHERE emember LIKE ? OR place LIKE ? OR content LIKE ? 
                OR method LIKE ? OR approve LIKE ? 
                ORDER BY occur DESC";
        $searchParam = '%' . $search . '%';
        $usesPrepared = true;
    }
} else {
    $sql = "SELECT * FROM {$DB}.idea ORDER BY num DESC";
}

// 데이터 조회 및 저장
$dataRows = array();

try {
    if (isset($usesPrepared) && $usesPrepared === true) {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(2, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(3, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(4, $searchParam, PDO::PARAM_STR);
        $stmh->bindValue(5, $searchParam, PDO::PARAM_STR);
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sql);
    }
    
    // 모든 결과를 배열에 저장
    if ($stmh) {
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $dataRows[] = $row;
        }
    }
    
    // 전체 레코드 수 계산
    $total_row = count($dataRows);
    
} catch (PDOException $ex) {
    error_log("DB query error in idea/index.php: " . $ex->getMessage());
    $dataRows = array();
    $total_row = 0;
}

include getDocumentRoot() . '/load_header.php';
?>

<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

<style>
.modal .modal-full {
    max-width: 94%;
}

.modal .white {
    color: #fff;
}

.modal .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal .modal-header .modal-title {
    font-size: 1.1rem;
}

.modal .modal-header .close {
    padding: 7px 10px;
    border-radius: 50%;
    background: none;
    border: none;
}

.modal .modal-header .close:hover {
    background: #dee2e6;
}

.modal .modal-header i,
.modal .modal-header svg {
    font-size: 12px;
    height: 12px;
    width: 12px;
}

.modal .modal-footer {
    padding: 1rem;
}

.modal.modal-borderless .modal-header {
    border-bottom: 0;
}

.modal.modal-borderless .modal-footer {
    border-top: 0;
}

th {
    white-space: nowrap;
}
</style>
</head>

<body>

<?php 
require_once(includePath('common/modal.php'));

if ($menu !== 'no') {
    require_once(includePath('myheader.php'));
}


// echo '<pre>';
// echo print_r($dataRows);
// echo '</pre>';

?>

<form name="board_form" id="board_form" method="post" action="./index.php">
    <input id="view_table" name="view_table" type="hidden" value="<?php echo htmlspecialchars($view_table, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="voc_alert" name="voc_alert" value="<?php echo htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    <input type="hidden" id="ma_alert" name="ma_alert" value="<?php echo htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    <input type="hidden" id="order_alert" name="order_alert" value="<?php echo htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>" size="5">
    
    <div class="container-fluid mb-5">
        <!-- 알림 모달 -->
        <div class="modal fade" id="notice_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-header text-center">
                            <h2>알림</h2>
                        </div>
                    </div>
                    <div class="modal-body">
                        <img src="../img/norice_errorreport1.png" alt="알림 이미지">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-2 mb-2">
            <div class="card-body">
                <div class="d-flex mt-3 mb-1 justify-content-center">
                    <span class="fs-5"><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                
                <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                    ▷ 전체: <?php echo htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8'); ?>건 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" class="form-control mx-1" style="width:150px;" name="search" id="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" onkeydown="JavaScript:SearchEnter();" placeholder="검색어">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                        <i class="bi bi-search"></i> 검색
                    </button>
                    <button type="button" class="btn btn-dark btn-sm mx-1" id="writeBtn">
                        <i class="bi bi-pencil"></i> 신규
                    </button>
                </div>
                
                <div class="row d-flex">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">번호</th>
                                <th class="text-center w100px">등록일</th>
                                <th class="text-center text-danger fw-bold">제안등급</th>
                                <th class="text-center w130px">종류</th>
                                <th class="text-center">제안명</th>
                                <th class="text-center">개선 내용</th>
                                <th class="text-center">개선 효과</th>
                                <th class="text-center">최초 제안자</th>
                                <th class="text-center">제안 참여자</th>
                                <th class="text-center">포상지급여부</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 디버깅: total_row 확인
                            error_log("DEBUG idea/index.php - total_row: " . $total_row . ", dataRows count: " . count($dataRows));

                            $start_num = 1;

                            foreach ($dataRows as $row) {
                                include "rowDB.php";                                
                            ?>
                            <tr onclick="redirectToView('<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>')">
                                <td class="text-center"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($occur, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($approve, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars(str_replace('!', ',', $errortype), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-start"><?php echo htmlspecialchars($place, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-start"><?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-start"><?php echo htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($firstone, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-start"><?php echo htmlspecialchars($emember, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($payment, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <?php
                                $start_num++;
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

<script src="js/scripts.js"></script>

<script>
(function() {
    'use strict';
    
    var dataTable; // DataTables 인스턴스 전역 변수
    var ideapageNumber; // 현재 페이지 번호 저장을 위한 전역 변수
    
    $(document).ready(function() {
        // DataTables 초기 설정
        dataTable = $('#myTable').DataTable({
            paging: true,
            ordering: true,
            searching: true,
            pageLength: 50,
            lengthMenu: [25, 50, 100, 200, 500, 1000],
            columnDefs: [
                { orderable: false, targets: 0 } // 번호 컬럼 정렬 비활성화
            ],
            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: 'Live Search:',
                emptyTable: '등록된 제안이 없습니다.',
                zeroRecords: '검색 결과가 없습니다.',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                infoFiltered: '(filtered from _MAX_ total entries)'
            },
            order: [[1, 'desc']] // 등록일 기준 내림차순
        });
        
        // 페이지 번호 복원 (초기 로드 시)
        var savedPageNumber = getCookie('ideapageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber, 10) - 1).draw(false);
        }
        
        // 페이지 변경 이벤트 리스너
        dataTable.on('page.dt', function() {
            ideapageNumber = dataTable.page.info().page + 1;
            setCookie('ideapageNumber', ideapageNumber, 10);
        });
        
        // 페이지 길이 셀렉트 박스 변경 이벤트 처리
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw();
            
            savedPageNumber = getCookie('ideapageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber, 10) - 1).draw(false);
            }
        });
        
        // 신규 버튼 클릭
        $('#writeBtn').on('click', function() {
            ideapageNumber = dataTable.page.info().page + 1;
            var tablename = '<?php echo addslashes($tablename); ?>';
            var url = 'write_form.php?tablename=' + encodeURIComponent(tablename);
            
            if (typeof customPopup === 'function') {
                customPopup(url, '직원 제안제도', 1100, 850);
            } else {
                window.open(url, '직원 제안제도', 'width=1100,height=850');
            }
        });
        
        // 모달 닫기 버튼
        $('#closeModalBtn').on('click', function() {
            $('#myModal').modal('hide');
        });
        
        // 관리자 결재 대기 버튼
        $('#adminprocess').on('click', function() {
            $('#search').val('결재상신 1차결재');
            document.getElementById('board_form').submit();
        });
        
        // 검색 초기화 버튼
        $('#searchNoinputBtn').on('click', function() {
            $('#search').val('');
            document.getElementById('board_form').submit();
        });
        
        // 검색 버튼
        $('#searchBtn').on('click', function() {
            $('#board_form').submit();
        });
        
        // 서버에 작업 기록
        if (typeof saveLogData === 'function') {
            saveLogData('직원 제안제도 운영');
        }
    });
    
    /**
     * 페이지 번호 복원 함수
     */
    window.restorePageNumber = function() {
        var savedPageNumber = getCookie('ideapageNumber');
        if (savedPageNumber && dataTable) {
            dataTable.page(parseInt(savedPageNumber, 10) - 1).draw('page');
        }
    };
    
    /**
     * 상세보기 페이지로 이동
     * @param {string} num - 게시글 번호
     * @param {string} tablename - 테이블명
     */
    window.redirectToView = function(num, tablename) {
        if (dataTable) {
            ideapageNumber = dataTable.page.info().page + 1;
        }
        
        var url = 'write_form.php?num=' + encodeURIComponent(num) + 
                  '&tablename=' + encodeURIComponent(tablename);
        
        if (typeof customPopup === 'function') {
            customPopup(url, '직원 제안제도', 1100, 900);
        } else {
            window.open(url, '직원 제안제도', 'width=1100,height=900');
        }
    };
    
    /**
     * Enter 키 검색 처리
     */
    window.SearchEnter = function() {
        if (window.event && window.event.keyCode === 13) {
            $('#searchBtn').click();
            return false;
        }
        return true;
    };
    
    /**
     * 쿠키 설정 함수 (존재하지 않을 경우 대비)
     */
    if (typeof setCookie !== 'function') {
        window.setCookie = function(name, value, days) {
            var expires = '';
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        };
    }
    
    /**
     * 쿠키 가져오기 함수 (존재하지 않을 경우 대비)
     */
    if (typeof getCookie !== 'function') {
        window.getCookie = function(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1, c.length);
                }
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        };
    }
})();
</script>

</body>
</html>
