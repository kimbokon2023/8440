<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    
    // 동적 리다이렉트 (로컬/서버 환경)
    $isLocal = (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
    $baseUrl = $isLocal ? 'http://127.0.0.1:8000' : 'http://8440.co.kr';
    
    header("Location: {$baseUrl}/login/logout.php");
    exit;
}

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? '';
$num = $_REQUEST["num"] ?? '';

// 페이징 설정
$scale = 10;        // 한 페이지에 보여질 게시글 수
$page_scale = 10;   // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성
$sql = "SELECT * FROM {$DB}.{$tablename} ORDER BY num DESC";

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title>일용직 이름검색</title>

</head>

<body>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="SelectWork" name="SelectWork" value="<?= htmlspecialchars($SelectWork) ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num) ?>">
    <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page) ?>">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode) ?>">
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename) ?>">
				
    <div class="container" style="width:500px;">
        <div class="card justify-content-center text-center mt-1">
            <div class="card-header align-items-center">
                <span class="text-center fs-5 me">이름 찾기</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-start mb-2">
                    <button id="closeBtn" type="button" onclick="self.close();" class="btn btn-dark btn-sm me-2">
                        <ion-icon name="close-outline"></ion-icon> 창닫기
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>번호</th>
                                <th>성명</th>
                                <th>비고</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmh = $pdo->query($sql);
                                $temp1 = $stmh->rowCount();
                                $temp2 = $temp1;  // 전체 레코드 수
                                
                                $total_row = $temp2;  // 전체 글수
                                
                                $total_page = ceil($total_row / $scale);  // 검색 전체 페이지 블록 수
                                $current_page = ceil($page / $page_scale);  // 현재 페이지 블록 위치계산
                                
                                $unique_combinations = array();  // 중복 확인을 위한 배열
                                
                                if ($page <= 1) {
                                    $start_num = $total_row;
                                } else {
                                    $start_num = $total_row - ($page - 1) * $scale;
                                }
                                
                                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                    $labor_name = $row["labor_name"] ?? '';
                                    $part = $row["part"] ?? '';
                                    
                                    $combination = $labor_name . '_' . $part;  // 조합 생성
                                    
                                    // 중복 검사
                                    if (!in_array($combination, $unique_combinations)) {
                                        $unique_combinations[] = $combination;  // 배열에 추가
                            ?>
                                        <tr onclick="maketext('<?= htmlspecialchars($labor_name) ?>'); return false;" style="cursor: pointer;">
                                            <td class="text-center"><?= $start_num ?></td>
                                            <td class="text-center"><?= htmlspecialchars($labor_name) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($part) ?></td>
                                        </tr>
                            <?php
                                        $start_num--;
                                    }
                                }
                            } catch (PDOException $ex) {
                                error_log("일용직 이름 조회 오류: " . $ex->getMessage());
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
// 페이지 이동
function movetoPage(page) {
    $("#page").val(page);
    $("#board_form").submit();
}

// input 필드 값 옆에 X 마크 띄우기 (ES5 호환)
(function() {
    var btnClear = document.querySelectorAll('.btnClear');
    
    for (var i = 0; i < btnClear.length; i++) {
        btnClear[i].addEventListener('click', function(e) {
            var input = this.parentNode.querySelector('input');
            if (input) {
                input.value = "";
                input.focus();
            }
            e.preventDefault();
        });
    }
})();

// ESC 키 누를시 팝업 닫기
$(document).keydown(function(e) {
    // keyCode 구 브라우저, which 현재 브라우저
    var code = e.keyCode || e.which;
    
    if (code == 27) {  // 27은 ESC 키번호
        self.close();
    }
});

// 클릭시 부모 창에 정보 전달
function maketext(firstitem) {
    $("#labor_name", opener.document).val(firstitem);
    
    if (window.opener && typeof window.opener.updateOptions === "function") {
        window.opener.updateOptions("#labor_name", firstitem);
    }
    
    self.close();
}

// 창닫기 버튼
$("#closeBtn").on("click", function() {
    self.close();
});
</script>
</body>
</html>
