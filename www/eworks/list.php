<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));
require_once __DIR__ . '/helpers.php';

// 세션 변수 초기화
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 데이터베이스 연결
$pdo = db_connect();

// 요청 파라미터 초기화
$eworksPage = $_REQUEST["eworksPage"] ?? 1;
$EworksSearch = $_REQUEST["EworksSearch"] ?? '';
$eworksel = $_REQUEST["eworksel"] ?? 'draft';
$author_id = $_REQUEST["author_id"] ?? '';

// 디버깅
error_log("=== list.php 호출 ===");
error_log("eworksel: " . $eworksel);
error_log("user_id: " . $user_id);
error_log("eworksPage: " . $eworksPage);
error_log("EworksSearch: " . $EworksSearch);

// 페이지 번호 검증
if ((int)$eworksPage < 1) {
    $eworksPage = 1;
}

// 페이징 설정
$scale = 8;         // 한 페이지에 보여질 게시글 수
$page_scale = 15;   // 한 페이지당 표시될 페이지 수
$first_num = ($eworksPage - 1) * $scale;

// 기타 변수 초기화
$now = date("Y-m-d");
$where = "";
$andwhere = "";
$Eworks_record_num = 0;

// _row.php에서 사용되는 변수 초기화
$e_num = '';
$eworks_item = '';
$registdate = '';
$author = '';
$status = '';
$e_title = '';
$e_line = '';
$e_line_id = '';
$e_confirm = '';
$e_confirm_id = '';
$r_line = '';
$e_viewexcept_id = '';

?>

<div id="eworks_list" style="height:520px;" class="mb-1">
    <table class="table table-hover table-sm" id="myEworks_Table">

<?php
// 멤버 정보 배열 초기화
$eworks_level_arr = array();
$part_arr = array();
$position_arr = array();
$name_arr = array();
$id_arr = array();

// 멤버 정보 조회 (제조파트, 지원파트)
try {
    $sql = "SELECT * FROM {$DB}.member";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        if ($row["part"] === '제조파트' || $row["part"] === '지원파트') {
            array_push($name_arr, $row["name"]);
            array_push($id_arr, $row["id"]);
            array_push($eworks_level_arr, $row["eworks_level"]);
            array_push($part_arr, $row["part"]);
            array_push($position_arr, $row["position"]);
        }
    }
} catch (PDOException $ex) {
    error_log("멤버 정보 조회 오류: " . $ex->getMessage());
}

// 결재권자 배열 생성
$firstStep = array();
$firstStepID = array();

for ($i = 0; $i < count($eworks_level_arr); $i++) {
    $eworks_level_value = (int)$eworks_level_arr[$i];
    
    if ($eworks_level_value === 2 || $eworks_level_value === 1) {
        array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);
        array_push($firstStepID, $id_arr[$i]);
    }
}

// 결재권자 여부 확인
$admin = 0;

for ($i = 0; $i < count($firstStepID); $i++) {
    if ($user_id === $firstStepID[$i]) {
        $admin = 1;
        break;
    }
}

// 숨김 조건 설정
$viewcon = " AND CONCAT('!', COALESCE(e_viewexcept_id, ''), '!') NOT LIKE '%!{$user_id}!%' ";
$viewconNone = " AND CONCAT('!', COALESCE(e_viewexcept_id, ''), '!') LIKE '%!{$user_id}!%' ";

if($admin)
{
// 결재권자인 경우
// 상태별 조건 설정
switch($eworksel) {		
	case 'draft':
		$where = "WHERE author_id = '$user_id' AND status = '$eworksel' AND is_deleted IS NULL" . $viewcon ;
		$andwhere = "AND author_id = '$user_id' AND status = '$eworksel' AND is_deleted IS NULL" . $viewcon ;
		break;			
	case 'send':
		// 첫 번째 결재권자이며, 문서 상태가 '상신'인 경우
		$all = "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
			   "AND CONCAT('!', e_confirm_id, '!') = '!'" . // 아직 아무도 결재하지 않았음
			   "AND LOCATE('{$user_id}', e_line_id) = 1 " . // e_line_id의 첫 번째 결재권자임
			   "AND status = '상신' " . // 문서 상태가 '상신'
			   "AND is_deleted IS NULL " . $viewcon;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		break;
	case 'noend': // 미결인 경우
		$pendingIds = fetchPendingApprovalIds($pdo, $DB, $user_id);
		if (empty($pendingIds)) {
			$where = "WHERE 0";
			$andwhere = "AND 0";
		} else {
			$idList = implode(',', array_map('intval', $pendingIds));
			$condition = "num IN ({$idList})";
			$where = "WHERE {$condition}";
			$andwhere = "AND {$condition}";
		}
		break;

	case 'ing': // 진행중인 경우
		$all = "(author_id = '$user_id' OR CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%') " .
			   "AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' " .
			   "AND is_deleted IS NULL AND status != 'end' AND status != 'reject' AND status != 'wait' AND status != 'noend'" . $viewcon;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		break;

	case 'end': // 결재완료인 경우
		$all = "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND is_deleted IS NULL AND status = 'end'" . $viewcon ;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		error_log("결재완료(end) 조건: " . $all);
		break;

	case 'reject': // 반려인 경우
		$all = "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'reject' AND is_deleted IS NULL" . $viewcon ;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		break;

	case 'wait': // 보류인 경우
		$all = "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'wait' AND is_deleted IS NULL" . $viewcon ;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		break;

	case 'refer': // 참조인 경우
		$all = "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'refer' AND is_deleted IS NULL" . $viewcon ;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		break;

	case 'trash': // trash 
		$all = " is_deleted IS NULL" . $viewconNone ;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
		break;
// 기타 다른 상태들을 여기에 추가할 수 있습니다.
}

}
else 
{
	// 결재권자가 아닌경우
	// 상태별 조건 설정
	switch($eworksel) {		
		case 'draft':
			$where = "WHERE author_id = '$user_id' AND status = '$eworksel' AND is_deleted IS NULL" . $viewcon ;
			$andwhere = "AND author_id = '$user_id' AND status = '$eworksel' AND is_deleted IS NULL" . $viewcon ;
			break;			
		case 'send':
			// 첫 번째 결재권자이며, 문서 상태가 '상신'인 경우
			$all = "CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%' " .				   
				   "AND status = '상신' " . // 문서 상태가 '상신'
				   "AND is_deleted IS NULL " . $viewcon;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'noend': // 미결인 경우
			// 첫 번째 결재권자에 대해 '상신' 상태를 '미결'로 처리
			// 그리고 나머지 결재권자에 대해서는 다음 결재자가 되는 경우를 처리
			$all = "CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%' " .
				   "AND ( " .
				   "    (CONCAT('!', e_confirm_id, '!') = '!!' AND LOCATE('{$user_id}', author_id) = 1 AND status = 'send') " .
				   "    OR " .
				   "    (CONCAT('!', e_confirm_id, '!') NOT LIKE '%!{$user_id}!%' AND INSTR(CONCAT('!', author_id, '!'), CONCAT('!', SUBSTRING_INDEX(e_confirm_id, '!', -1), '!', '{$user_id}', '!')) > 0 AND status != 'send')" .
				   ") " .
				   "AND is_deleted IS NULL AND status != 'end' AND status != 'reject' AND status != 'wait'" . $viewcon;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'ing': // 진행중인 경우
			$all = "(author_id = '$user_id' OR CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%') " .				 
				   "AND is_deleted IS NULL AND status != 'end' AND status != 'reject' AND status != 'wait' AND status != 'noend'" . $viewcon;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'end': // 결재완료인 경우
			$all = "CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%' AND is_deleted IS NULL AND status = 'end'" . $viewcon ;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'reject': // 반려인 경우
			$all = "CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%' AND status = 'reject' AND is_deleted IS NULL" . $viewcon ;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'wait': // 보류인 경우
			$all = "CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%' AND status = 'wait' AND is_deleted IS NULL" . $viewcon ;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'refer': // 참조인 경우
			$all = "CONCAT('!', author_id, '!') LIKE '%!{$user_id}!%' AND status = 'refer' AND is_deleted IS NULL" . $viewcon ;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;

		case 'trash': // trash 
			$all = " is_deleted IS NULL" . $viewconNone ;
			$where = "WHERE " . $all;
			$andwhere = "AND " . $all;
			break;
	// 기타 다른 상태들을 여기에 추가할 수 있습니다.
	}
	
}

// 결재자인 경우는 결재가 진행된 것 완료된 것등 구분해서 표시해야 한다.
	$orderby=" order by registdate desc ";				
		 
	$a= " " . $orderby . " limit $first_num, $scale";  
	$b=  " " . $orderby ;
						
// $total_row 계산
$total_row = 0;

// SQL Injection 방어
$EworksSearch_safe = str_replace("'", "''", $EworksSearch);

if ($EworksSearch == "") {
    $sqlcon = "SELECT * FROM {$DB}.eworks " . $where;
} else {
    $sqlcon = "SELECT * FROM {$DB}.eworks WHERE " .
              "((e_title LIKE '%{$EworksSearch_safe}%') OR " .
              "(contents LIKE '%{$EworksSearch_safe}%') OR " .
              "(e_line LIKE '%{$EworksSearch_safe}%') OR " .
              "(r_line LIKE '%{$EworksSearch_safe}%')) " . $andwhere;
}

try {
    $allstmh = $pdo->query($sqlcon);
    $total_row = $allstmh->rowCount();
} catch (PDOException $ex) {
    error_log("전자결재 개수 조회 오류: " . $ex->getMessage());
}

	// 페이지 계산 로직
	if ($total_row <= $scale) {
		$eworksPage = 1;
	} else {
		if ($total_row < ($eworksPage - 1) * $scale) {
			$eworksPage = 1;
		}
	}

	$first_num = ($eworksPage - 1) * $scale;

// SQL 쿼리 문장 구성
$a = " " . $orderby . " LIMIT $first_num, $scale";

if ($EworksSearch == "") {
    $sql = "SELECT * FROM {$DB}.eworks " . $where . $a;
} else {
    $sql = "SELECT * FROM {$DB}.eworks WHERE " .
           "((e_title LIKE '%{$EworksSearch_safe}%') OR " .
           "(contents LIKE '%{$EworksSearch_safe}%') OR " .
           "(e_line LIKE '%{$EworksSearch_safe}%') OR " .
           "(r_line LIKE '%{$EworksSearch_safe}%')) " . $andwhere . $a;
}

try {
    error_log("실행할 COUNT SQL: " . $sqlcon);
    $allstmh = $pdo->query($sqlcon);
    $total_row = $allstmh->rowCount();
    error_log("total_row: " . $total_row);
    
    error_log("실행할 LIST SQL: " . $sql);
    $stmh = $pdo->query($sql);
    $count = $stmh->rowCount();
    error_log("count: " . $count);
    
    // 페이지 계산
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($eworksPage / $page_scale);
    
    if ($eworksPage < 1) {
        $start_num = $total_row;
    } else {
        $start_num = $total_row - ($eworksPage - 1) * $scale;
    }
    
    if ($count < 1) {
        echo '<div class="row d-flex mt-3 p-1 mb-1 justify-content-center">자료가 없습니다.</div>';
    } else {
        $start_num = 0;
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include getDocumentRoot() . "/eworks/_row.php";
            
            // 상태 문자열 변환
            switch ($status) {
                case 'draft':
                    $statusStr = "작성";
                    break;
                case 'send':
                    $statusStr = "상신";
                    break;
                case 'noend':
                    $statusStr = "미결";
                    break;
                case 'ing':
                    $statusStr = "진행";
                    break;
                case 'end':
                    $statusStr = "결재완료";
                    break;
                case 'reject':
                    $statusStr = "반려";
                    break;
                case 'wait':
                    $statusStr = "보류";
                    break;
                case 'refer':
                    $statusStr = "참조";
                    break;
                default:
                    $statusStr = "";
            }
            
            // 결재 진행 상태 문자열 생성
            $prograssStr = '';
            $arr = explode("!", $e_line_id);
            $arr_str = explode("!", $e_line);
            $approval_time = explode("!", $e_confirm_id);
            $approval_str = explode("!", $e_confirm);
            
            for ($i = 0; $i < count($arr); $i++) {
                if (isset($approval_time[$i]) && $approval_time[$i] !== '' && $approval_time[$i] !== null) {
                    $prograssStr .= (isset($approval_str[$i]) ? $approval_str[$i] : '') . '<br>';
                } else {
                    $prograssStr .= (isset($arr_str[$i]) ? $arr_str[$i] : '') . " " . '<br>';
                }
            }
            
            // 숨김 여부 확인
            $e_viewexcept_id_value = explode("!", $e_viewexcept_id);
            $e_viewexcept_exist = in_array($user_id, $e_viewexcept_id_value) ? 1 : 0;
            
            // 테이블 헤더 (첫 행에만 출력)
            if ($start_num < 1) {
?>
        <thead class="table-primary">
            <!-- PC용 헤더 (한 줄) -->
            <tr class="d-none d-md-table-row">
                <th class="text-center" style="width:5%;">
                    <input type="checkbox" id="checkAll" class="master-checkbox" />
                    <label for="checkAll" class="checkbox-numbered-label"></label>
                </th>
                <th class="text-center" style="width:10%;">구분</th>
                <th class="text-center" style="width:10%;">작성일시</th>
                <th class="text-center" style="width:5%;">작성자</th>
                <th class="text-center" style="width:7%;">현재상태</th>
                <th class="text-center" style="width:15%;">결재진행</th>
                <th class="text-center" style="width:10%;">참조자</th>
                <th class="text-center" style="width:25%;">제목</th>
                
                <?php if ($e_viewexcept_exist) { ?>
                    <th class="text-center align-items-center" style="width:10%;">
                        <i class="bi bi-skip-backward"></i> 복구
                    </th>
                <?php } elseif ($status === 'end') { ?>
                    <th class="text-center align-items-center" style="width:10%;">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="selectedDeleteBtn" 
                                onclick="deleteSelectedEworks()">
                            <i class="bi bi-trash"></i> 삭제
                        </button>
                    </th>
                <?php } elseif ($admin && $status === 'ing') { ?>
                    <th class="text-center align-items-center" style="width:10%;">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="approvalselectedBtn" 
                                onclick="approvalSelectedEworks()">
                            <i class="bi bi-duffle"></i> 결재
                        </button>
                    </th>
                <?php } else { ?>
                    <th class="text-center align-items-center" style="width:10%;">
                        <i class="bi bi-briefcase"></i> 결재
                    </th>
                <?php } ?>
            </tr>
            
            <!-- 모바일용 헤더 (세 줄) -->
            <tr class="d-md-none">
                <!-- 첫 번째 행 -->
                <th class="text-center" rowspan="3" style="width:5%; vertical-align: middle; padding: 8px 4px;">
                    <input type="checkbox" id="checkAllMobile" class="master-checkbox" />
                    <label for="checkAllMobile" class="checkbox-numbered-label"></label>
                </th>
                <th class="text-center" style="width:30%; padding: 8px 4px;">구분</th>
                <th class="text-center" style="width:32%; padding: 8px 4px;">작성일시</th>
                <th class="text-center" style="width:33%; padding: 8px 4px;">작성자</th>
            </tr>
            <tr class="d-md-none">
                <!-- 두 번째 행 -->
                <th class="text-center" style="width:30%; padding: 8px 4px;">현재상태</th>
                <th class="text-center" style="width:32%; padding: 8px 4px;">참조자</th>
                <th class="text-center" style="width:33%; padding: 8px 4px;">결재진행</th>
            </tr>
            <tr class="d-md-none">
                <!-- 세 번째 행 -->
                <th class="text-center" colspan="2" style="width:62%; padding: 8px 4px;">제목</th>
                <th class="text-center" style="width:33%; padding: 8px 4px;">
                    <?php if ($e_viewexcept_exist) { ?>
                        <i class="bi bi-skip-backward"></i> 복구
                    <?php } elseif ($status === 'end') { ?>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-sm-mobile" id="selectedDeleteBtnMobile" 
                                onclick="deleteSelectedEworks()">
                            <i class="bi bi-trash"></i> 삭제
                        </button>
                    <?php } elseif ($admin && $status === 'ing') { ?>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-sm-mobile" id="approvalselectedBtnMobile" 
                                onclick="approvalSelectedEworks()">
                            <i class="bi bi-duffle"></i> 결재
                        </button>
                    <?php } else { ?>
                        <i class="bi bi-briefcase"></i> 결재
                    <?php } ?>
                </th>
            </tr>
        </thead>
        <tbody>
<?php } ?>

            <!-- PC용 데이터 행 (한 줄) -->
            <tr class="d-none d-md-table-row">
                <td class="text-center">
                    <input type="checkbox" class="checkItem" style="width:5%;" 
                           id="checkItem<?= $Eworks_record_num + 1 ?>" 
                           data-id="<?= htmlspecialchars($e_num) ?>" />
                    <label for="checkItem<?= ($Eworks_record_num + 1) ?>" class="checkbox-numbered-label">
                        <?= ($Eworks_record_num + 1) ?>
                    </label>
                </td>
                
                <td class="text-center" style="width:10%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($eworks_item) ?>
                </td>
                
                <td class="text-center" style="width:10%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($registdate) ?>
                </td>
                
                <td class="text-center" style="width:5%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($author) ?>
                </td>
                
                <td class="text-center" style="width:7%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($statusStr) ?>
                </td>
                
                <td class="text-center" style="width:15%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= $prograssStr ?>
                </td>
                
                <?php
                // 참조자 문자열 길이 제한
                $display_text = mb_strlen($r_line) > 10 ? mb_substr($r_line, 0, 8) . '...' : $r_line;
                ?>
                <td class="text-start" style="width:10%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');" 
                    title="<?= htmlspecialchars($r_line, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($display_text, ENT_QUOTES, 'UTF-8') ?>
                </td>
                
                <td class="text-start" style="width:25%;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars(iconv_substr($e_title, 0, 30, "utf-8")) ?>
                </td>
                
                <?php if ($e_viewexcept_exist) { ?>
                    <td class="text-center" style="width:10%;">
                        <button type="button" class="btn btn-outline-dark btn-sm" id="eworks_restoreBtn" 
                                onclick="restore('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-skip-backward"></i>
                        </button>
                    </td>
                <?php } elseif ($status === 'end') { ?>
                    <td class="text-center" style="width:10%;">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="eworks_viewExceptBtn" 
                                onclick="viewExcept('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                <?php } elseif ($admin && $status === 'ing') { ?>
                    <td class="text-center" style="width:10%;">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="eworks_approvalviewExceptBtn" 
                                onclick="approvalviewExcept('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-duffle"></i>
                        </button>
                    </td>
                <?php } ?>
            </tr>
            
            <!-- 모바일용 데이터 행 (세 줄) -->
            <tr class="d-md-none">
                <!-- 첫 번째 행 -->
                <td class="text-center" rowspan="3" style="width:5%; vertical-align: middle; padding: 8px 4px;">
                    <input type="checkbox" class="checkItem" 
                           id="checkItemMobile<?= $Eworks_record_num + 1 ?>" 
                           data-id="<?= htmlspecialchars($e_num) ?>" />
                    <label for="checkItemMobile<?= ($Eworks_record_num + 1) ?>" class="checkbox-numbered-label">
                        <?= ($Eworks_record_num + 1) ?>
                    </label>
                </td>
                
                <td class="text-center" style="width:30%; padding: 8px 4px; font-size: 13px;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($eworks_item) ?>
                </td>
                
                <td class="text-center" style="width:32%; padding: 8px 4px; font-size: 12px;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($registdate) ?>
                </td>
                
                <td class="text-center" style="width:33%; padding: 8px 4px; font-size: 13px;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($author) ?>
                </td>
            </tr>
            <tr class="d-md-none">
                <!-- 두 번째 행 -->
                <td class="text-center" style="width:30%; padding: 8px 4px; font-size: 13px;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars($statusStr) ?>
                </td>
                
                <?php
                // 참조자 문자열 길이 제한
                $display_text = mb_strlen($r_line) > 15 ? mb_substr($r_line, 0, 13) . '...' : $r_line;
                ?>
                <td class="text-start" style="width:32%; padding: 8px 4px; font-size: 12px;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');" 
                    title="<?= htmlspecialchars($r_line, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($display_text, ENT_QUOTES, 'UTF-8') ?>
                </td>
                
                <td class="text-center" style="width:33%; padding: 8px 4px; font-size: 12px;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= $prograssStr ?>
                </td>
            </tr>
            <tr class="d-md-none">
                <!-- 세 번째 행 -->
                <td class="text-start" colspan="2" style="width:62%; padding: 8px 4px; font-size: 13px; word-break: break-word;" 
                    onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                    <?= htmlspecialchars(iconv_substr($e_title, 0, 50, "utf-8")) ?>
                </td>
                
                <td class="text-center" style="width:33%; padding: 8px 4px;">
                    <?php if ($e_viewexcept_exist) { ?>
                        <button type="button" class="btn btn-outline-dark btn-sm btn-sm-mobile" 
                                onclick="restore('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-skip-backward"></i>
                        </button>
                    <?php } elseif ($status === 'end') { ?>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-sm-mobile" 
                                onclick="viewExcept('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-trash"></i>
                        </button>
                    <?php } elseif ($admin && $status === 'ing') { ?>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-sm-mobile" 
                                onclick="approvalviewExcept('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-duffle"></i>
                        </button>
                    <?php } else { ?>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-sm-mobile" 
                                onclick="viewEworks_detail('<?= htmlspecialchars($e_num, ENT_QUOTES) ?>','<?= $eworksPage ?>');">
                            <i class="bi bi-briefcase"></i>
                        </button>
                    <?php } ?>
                </td>
            </tr>
<?php
            $Eworks_record_num++;
            $start_num++;
        }
    }
} catch (PDOException $ex) {
    error_log("전자결재 리스트 조회 오류: " . $ex->getMessage());
}

// 페이지 구분 블럭 계산
$start_page = ($current_page - 1) * $page_scale + 1;
$end_page = $start_page + $page_scale - 1;
?>
        </tbody>
    </table>
    
    <!-- 페이징 -->
    <div class="row row-cols-auto mt-1 mb-2 justify-content-center align-items-center">
        <?php
        // 이전 페이지 블록
        if ($eworksPage != 1 && $eworksPage > $page_scale) {
            $prev_page = $eworksPage - $page_scale;
            
            if ($prev_page <= 0) {
                $prev_page = 1;
            }
?>
            <button class="btn btn-outline-secondary btn-sm" type="button" 
                    onclick="eworks_movetoPage(<?= $prev_page ?>);">◀</button> &nbsp;
<?php
        }
        
        // 페이지 번호 목록
        for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
            if ($eworksPage == $i) {
?>
                <span class="text-secondary">&nbsp;<?= $i ?>&nbsp;</span>
<?php
            } else {
?>
                <button class="btn btn-outline-secondary btn-sm" type="button" 
                        onclick="eworks_movetoPage(<?= $i ?>);"><?= $i ?></button> &nbsp;
<?php
            }
        }
        
        // 다음 페이지 블록
        if ($eworksPage < $total_page) {
            $next_page = $eworksPage + $page_scale;
            
            if ($next_page > $total_page) {
                $next_page = $total_page;
            }
?>
            <button class="btn btn-outline-secondary btn-sm" type="button" 
                    onclick="eworks_movetoPage(<?= $next_page ?>);">▶</button> &nbsp;
<?php
        }
        ?>
    </div>
</div>

