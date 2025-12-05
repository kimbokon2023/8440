<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$e_num = $_REQUEST["e_num"] ?? '';
$status = $_REQUEST["status"] ?? 'draft';
$done = $_REQUEST["done"] ?? '';

// 데이터베이스 연결
$pdo = db_connect();

// _row.php에서 사용되는 변수 초기화
$author_id = '';
$e_line_id = '';
$e_confirm_id = '';

/**
 * 문자열 내 부분 문자열 포함 여부 확인
 * 
 * @param string $string 검색 대상 문자열
 * @param string $substring 찾을 부분 문자열
 * @return bool 포함 여부
 */
function contains($string, $substring) {
    return strpos($string, $substring) !== false;
}

?>

<!-- 댓글 목록 -->
<div class="row p-1 mt-1 mb-1 justify-content-center">
    <div class="d-flex justify-content-center mb-2" id="comments-container">
        <?php
        try {
            $sql_ripple = "SELECT * FROM {$DB}.eworks_ripple WHERE parent = ? AND is_deleted IS NULL ORDER BY num ASC";
            $stmh = $pdo->prepare($sql_ripple);
            $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
            $stmh->execute();
            
            while ($row_ripple = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $ripple_num = $row_ripple["num"] ?? '';
                $ripple_id = $row_ripple["author_id"] ?? '';
                $ripple_nick = $row_ripple["author"] ?? '';
                $ripple_content = $row_ripple["content"] ?? '';
                $ripple_date = $row_ripple["regist_day"] ?? '';
                
                // 내용 포맷팅
                $ripple_content = str_replace("\n", "", $ripple_content);
                $ripple_content = str_replace(" ", "&nbsp;", $ripple_content);
        ?>
                <div class="card ripple-item" id="ripple-<?= htmlspecialchars($ripple_num) ?>" style="width:80%">
                    <div class="row justify-content-center">
                        <div class="card-body">
                            <span class="mt-1 mb-2">
                                ▶&nbsp;&nbsp;<?= htmlspecialchars($ripple_content) ?>
                                ✔&nbsp;&nbsp;작성자: <?= htmlspecialchars($ripple_nick) ?> | 
                                <?= htmlspecialchars($ripple_date) ?>
                                
                                <?php
                                // 삭제 권한 체크 (관리자, 작성자, level 1)
                                if ($userid == "admin" || $userid == $ripple_id || $level === 1) {
                                ?>
                                    <a href="#" class="text-danger" 
                                       onclick="eworks_delete_ripple('<?= htmlspecialchars($ripple_num, ENT_QUOTES) ?>'); return false;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
                </div>
        <?php
            }
        } catch (PDOException $ex) {
            error_log("댓글 조회 오류: " . $ex->getMessage());
        }
        ?>
    </div>
    
    <!-- 댓글 작성 -->
    <div class="row p-1 mb-1 justify-content-center">
        <div class="card justify-content-center" style="width:80%">
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="row d-flex mt-3 mb-1 justify-content-center">
                        <div class="d-flex">
                            <span class="form-control badge bg-dark text-center fs-6" style="width:10%;">
                                <i class="bi bi-chat-dots"></i> 의견
                            </span>
                            &nbsp;
                            <textarea rows="1" class="form-control" id="ripple_content" name="ripple_content" required></textarea>
                            &nbsp;
                            <button type="button" class="form-control btn btn-dark btm-sm" style="width:10%;" 
                                    onclick="eworks_insert_ripple('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>')">
                                <i class="bi bi-floppy-fill"></i> 저장
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 버튼 영역 -->
<div class="d-flex justify-content-end">

<?php
$myTurn = false;

try {
    $sql = "SELECT * FROM {$DB}.eworks WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        include getDocumentRoot() . "/eworks/_row.php";
        
        $arr = explode("!", $e_line_id);
        $approval_time = explode("!", $e_confirm_id);
        $last_approved_id = end($approval_time);
        
        // 나의 결재 차례인지 확인
        if ($status !== 'reject' && $status !== 'end') {
            if (empty($e_confirm_id)) {
                // 첫 번째 결재자가 현재 차례인지 확인
                $myTurn = (isset($arr[0]) && $arr[0] == $user_id);
            } else {
                $index = array_search($last_approved_id, $arr);
                
                if ($index !== false && isset($arr[$index + 1]) && $arr[$index + 1] == $user_id) {
                    $myTurn = true;
                }
            }
        }
    }
} catch (PDOException $ex) {
    error_log("전자결재 정보 조회 오류: " . $ex->getMessage());
}

// 상태별 버튼 표시
if ($myTurn && $done !== 'done') {
    // 내 결재 차례인 경우
?>
    <button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2">
        <i class="bi bi-window-dock"></i> 승인
    </button>
    <button id="eworks_rejectBtn" class="btn btn-danger btn-sm me-2">
        <i class="bi bi-arrow-counterclockwise"></i> 반려
    </button>
    <button id="eworks_waitBtn" class="btn btn-secondary btn-sm me-2">
        <i class="bi bi-hourglass"></i> 보류
    </button>
<?php
} elseif ($status === 'draft' || $status === '' || $status === null) {
    // 작성 중인 경우
?>
    <button id="eworks_saveBtn" class="btn btn-dark btn-sm me-2">
        <i class="bi bi-floppy-fill"></i> 저장
    </button>
    <button id="eworks_delBtn" class="btn btn-danger btn-sm me-2">
        <i class="bi bi-trash"></i> 삭제
    </button>
    <button id="eworks_sendBtn" class="btn btn-primary btn-sm me-2">
        <i class="bi bi-window-dock"></i> 상신
    </button>
<?php
} elseif ($status === 'reject') {
    // 반려된 경우
?>
    <button id="eworks_saveBtn" class="btn btn-dark btn-sm me-2">
        <i class="bi bi-floppy-fill"></i> 저장
    </button>
    <button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2">
        <i class="bi bi-credit-card-2-front-fill"></i> 재승인요청
    </button>
    <button id="eworks_delBtn" class="btn btn-danger btn-sm me-2">
        <i class="bi bi-trash"></i> 삭제
    </button>
<?php
} elseif ($status === 'wait') {
    // 보류 중인 경우
?>
    <button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2">
        <i class="bi bi-window-dock"></i> 승인
    </button>
    <button id="eworks_delBtn" class="btn btn-danger btn-sm me-2">
        <i class="bi bi-trash"></i> 삭제
    </button>
<?php
} elseif ($status === 'ing' && $user_id == $author_id) {
    // 진행 중이고 작성자인 경우 (버튼 없음)
} elseif ($status === 'send') {
    // 상신된 경우
    if ($user_id !== $author_id) {
?>
        <button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2">
            <i class="bi bi-credit-card-2-front-fill"></i> 승인
        </button>
<?php
    } else {
?>
        <button id="eworks_recallBtn" class="btn btn-dark btn-sm me-2">
            <i class="bi bi-arrow-counterclockwise"></i> 회수
        </button>
<?php
    }
}
?>

</div>


