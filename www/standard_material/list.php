<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재 종류 관리
 * 
 * 원자재 종류 목록 표시 및 관리
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

// 권한 체크
if ($level > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화
$menu = $_REQUEST["menu"] ?? '';
$search = $_REQUEST["search"] ?? '';  // 목록표에 제목,이름 등 나오는 부분
$item = $_REQUEST["item"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$param = $_REQUEST["param"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 첫 화면 표시 문구
$title_message = '원자재 종류';

include getDocumentRoot() . '/load_header.php';
?>

<?php include getDocumentRoot() . '/common/modal.php'; ?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>
</head>

<body>

<?php
// Null 체크 함수
function checkNull($strtmp) {
    if ($strtmp === null || trim($strtmp) === '') {
        return false;
    } else {
        return true;
    }
}

ini_set('display_errors', '0');  // 화면에 warning 없애기	

// SQL 쿼리 준비
if (checkNull($search)) {
    // 🔒 SQL 인젝션 방지: Prepared Statement 사용
    $searchParam = '%' . $search . '%';
    $sql = "select * from " . $DB . ".steelitem where item like ? order by item asc, num desc";
    $params = array($searchParam);
} else {
    $sql = "select * from " . $DB . ".steelitem order by item asc, num desc";
    $params = array();
}

// 전체 레코드수 파악
$total_row = 0;
try {
    if (!empty($params)) {
        $stmh = $pdo->prepare($sql);
        $stmh->execute($params);
    } else {
        $stmh = $pdo->query($sql);
    }
    $total_row = $stmh->rowCount();
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
}

// 데이터 조회
try {
    if (!empty($params)) {
        $stmh = $pdo->prepare($sql);
        $stmh->execute($params);
    } else {
        $stmh = $pdo->query($sql);
    }
?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="SelectWork" name="SelectWork" value="<?= htmlspecialchars($SelectWork, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">

<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="text-center"><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></h4>
            
            <div class="d-flex p-2 mb-2 mt-4 justify-content-center align-items-center">
                ▷ <?= $total_row ?> &nbsp;
                <div class="inputWrap30">
                    <input type="text" id="search" name="search" class="form-control me-1" style="width:150px;" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" onKeyPress="if (event.keyCode==13){ enter(); }">
                    <button class="btnClear"></button>
                </div>
                <button class="btn btn-dark btn-sm me-1" type="button" id="searchBtn"><i class="bi bi-search"></i></button>
                &nbsp;
                <button id="newBtn" type="button" class="btn btn-dark btn-sm me-4"><i class="bi bi-pencil-fill"></i> 신규</button>
                <button class="btn btn-secondary btn-sm me-1" onclick="self.close();"><i class="bi bi-x-lg"></i> 창닫기</button>
            </div>
            
            <div class="card mb-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="myTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" scope="col" style="width:15%;">번호</th>
                                    <th class="text-center" scope="col">원자재명</th>
                                    <th class="text-center" scope="col">삭제</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                            $start_num = $total_row;    // 페이지당 표시되는 첫번째 글순번
                            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                include '_row.php';
                            ?>
                                <tr onclick="maketext('<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>');return false;">
                                    <td class="text-center"><?= $start_num ?></td>
                                    <td class="text-start"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="event.stopPropagation(); delFn('<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>')"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php
                                $start_num--;
                            }
                            } catch (PDOException $Exception) {
                                error_log("데이터 출력 오류: " . $Exception->getMessage());
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
</body>
</html>


<script>
var dataTable; // DataTables 인스턴스 전역 변수
var standard_materialpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": false,
        "ordering": true,
        "searching": false,
        "pageLength": 50,
        "lengthMenu": [50, 100, 200, 500, 1000],
        "order": [[0, 'desc']]
    });
    
    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('standard_materialpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }
    
    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var standard_materialpageNumber = dataTable.page.info().page + 1;
        setCookie('standard_materialpageNumber', standard_materialpageNumber, 10); // 쿠키에 페이지 번호 저장
    });
    
    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경
        
        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('standard_materialpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    const startstr = '<?php echo htmlspecialchars($param, ENT_QUOTES, 'UTF-8'); ?>';
    
    if (startstr == '') {
        // 전체 화면에 출력
        $("#param").val('');
        displaytext();
    }
    
    // 검색 버튼 클릭
    $("#searchBtn").on("click", function() {
        $("#board_form").submit();
    });
    
    // 신규 버튼 클릭
    $("#newBtn").on("click", function() {
        popupCenter('./write.php', '등록', 580, 450);
    });
    
    // 창닫기 버튼 클릭
    $("#closeBtn").on("click", function() {
        self.close();
    });
    
    /* ESC 키 누를시 팝업 닫기 */
    $(document).keydown(function(e) {
        // keyCode 구 브라우저, which 현재 브라우저
        var code = e.keyCode || e.which;
        
        if (code == 27) { // 27은 ESC 키번호
            self.close();
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('standard_materialpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

// Enterkey 동작
function enter() {
    $("#board_form").submit();
}

// 클릭시 화면에 정보 보여줌, 코드명, 거래처
function maketext(str) {
    var item = $("#item").val();
    
    $("#" + item, opener.document).val(str);
    
    // 자식 창에서 새로운 spec_arr 값 추가 예시
    var newValue = str; // 실제로 추가하려는 값을 사용하세요
    
    if (window.opener && typeof window.opener.updateOptions === "function") {
        window.opener.updateOptions(item, newValue);
    }
    self.close();
}

// 규격전체 화면에 찍어주기
function displaytext() {
    $("#result").val($("#text1").val() + $("#text2").val() + $("#text3").val());
}

// 삭제 함수
function delFn(delChoice) {
    console.log(delChoice);
    $("#SelectWork").val("delete");
    $("#num").val(delChoice);
    
    // DATA 삭제버튼 클릭시
    Swal.fire({
        title: '해당 DATA 삭제',
        text: " DATA 삭제는 신중하셔야 합니다. '\n 정말 삭제 하시겠습니까?",
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
                success: function(data) {
                    Toastify({
                        text: "파일 삭제 완료!",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#4fbe87"
                    }).showToast();
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        }
    });
}

// 자식창에서 돌아와서 이걸 실행한다
function reloadlist() {
    const search = $("#search").val();
    $("#board_form").submit();
}
</script>

