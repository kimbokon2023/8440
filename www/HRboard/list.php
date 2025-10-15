<?php
/**
 * HRboard 인사교육총무 목록 페이지
 * HRboard 게시글 목록을 표시하고 검색 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$page = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? '';

// 변수 초기화
$title_message = '인사교육총무';
$tablename = 'HRboard';
$total_row = 0;

// 헤더 포함
include getDocumentRoot() . '/load_header.php';
?>

<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>

<?php
require_once(includePath('myheader.php'));
require_once(includePath('common/modal.php'));

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성 (Prepared Statement 사용)
if ($mode === "search" && !empty($search)) {
    // SQL Injection 방지를 위한 Prepared Statement
    $sql = "SELECT * FROM {$DB}.{$tablename} 
            WHERE name LIKE ? OR subject LIKE ? OR nick LIKE ? 
            OR searchtext LIKE ? OR regist_day LIKE ? 
            ORDER BY num DESC";
    $searchParam = '%' . $search . '%';
} else {
    $sql = "SELECT * FROM {$DB}.{$tablename} ORDER BY num DESC";
}

try {
    if ($mode === "search" && !empty($search)) {
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
    
    $total_row = $stmh->rowCount();
?>

<form name="board_form" id="board_form" method="post" action="list.php?mode=search&search=<?php echo urlencode($search); ?>">
    <input type="hidden" id="page" name="page" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">
    
    <div class="container justify-content-center">
        <div class="card justify-content-center">
            <div class="card-body">
                <div class="d-flex mt-3 mb-3 justify-content-center">
                    <span class="badge bg-success text-white fs-5">&nbsp;&nbsp;<?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?>&nbsp;&nbsp;</span>
                </div>
                
                <div class="d-flex mb-2 px-5 px-lg-2 mt-2 justify-content-center align-items-center">
                    ▷ <?php echo htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2">
                        <i class="bi bi-search"></i> 검색
                    </button>
                    <button type="button" class="btn btn-dark btn-sm" id="writeBtn">
                        <i class="bi bi-pencil"></i> 신규
                    </button> &nbsp;&nbsp;&nbsp;
                </div>
                
                <div class="row d-flex">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-success">
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
                                $item_num = $row["num"];
                                $item_id = $row["id"] ?? '';
                                $item_name = $row["name"] ?? '';
                                $item_nick = $row["nick"] ?? '';
                                $item_hit = $row["hit"] ?? 0;
                                $item_date = $row["regist_day"] ?? '';
                                $item_date = substr($item_date, 0, 10);
                                $item_subject = str_replace(" ", "&nbsp;", $row["subject"] ?? '');
                                $division = $row["division"] ?? '';
                            ?>
                            <tr onclick="redirectToView('<?php echo htmlspecialchars($item_num, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>')">
                                <td class="text-center"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($division, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $item_subject; ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8'); ?></td>
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
    error_log("DB query error in HRboard/list.php: " . $ex->getMessage());
    ?>
    <div class="container">
        <div class="alert alert-danger" role="alert">
            데이터 조회 중 오류가 발생했습니다.
        </div>
    </div>
    <?php
}
?>

<script>
(function() {
    'use strict';
    
    var dataTable; // DataTables 인스턴스 전역 변수
    var HRpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수
    
    $(document).ready(function() {
        // DataTables 초기 설정
        dataTable = $('#myTable').DataTable({
            paging: true,
            ordering: true,
            searching: true,
            pageLength: 50,
            lengthMenu: [25, 50, 100, 200, 500, 1000],
            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: 'Live Search:'
            },
            order: [[0, 'desc']]
        });
        
        // 페이지 번호 복원 (초기 로드 시)
        var savedPageNumber = getCookie('HRpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber, 10) - 1).draw(false);
        }
        
        // 페이지 변경 이벤트 리스너
        dataTable.on('page.dt', function() {
            HRpageNumber = dataTable.page.info().page + 1;
            setCookie('HRpageNumber', HRpageNumber, 10); // 쿠키에 페이지 번호 저장
        });
        
        // 페이지 길이 셀렉트 박스 변경 이벤트 처리
        $('#myTable_length select').on('change', function() {
            var selectedValue = $(this).val();
            dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경
            
            // 변경 후 현재 페이지 번호 복원
            savedPageNumber = getCookie('HRpageNumber');
            if (savedPageNumber) {
                dataTable.page(parseInt(savedPageNumber, 10) - 1).draw(false);
            }
        });
        
        // 신규 버튼 클릭
        $('#writeBtn').on('click', function() {
            HRpageNumber = dataTable.page.info().page + 1;
            var tablename = '<?php echo addslashes($tablename); ?>';
            var url = 'write_form.php?tablename=' + encodeURIComponent(tablename);
            
            if (typeof customPopup === 'function') {
                customPopup(url, '인사교육총무', 1300, 850);
            } else {
                window.open(url, '인사교육총무', 'width=1300,height=850');
            }
        });
        
        // 검색 버튼 클릭
        $('#searchBtn').on('click', function() {
            $('#board_form').submit();
        });
    });
    
    /**
     * 페이지 번호 복원 함수
     */
    window.restorePageNumber = function() {
        var savedPageNumber = getCookie('HRpageNumber');
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
            HRpageNumber = dataTable.page.info().page + 1;
        }
        
        var url = 'view.php?num=' + encodeURIComponent(num) + '&tablename=' + encodeURIComponent(tablename);
        
        if (typeof customPopup === 'function') {
            customPopup(url, '인사교육총무', 1200, 900);
        } else {
            window.open(url, '인사교육총무', 'width=1200,height=900');
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
