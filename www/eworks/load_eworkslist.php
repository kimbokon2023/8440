<?php require_once __DIR__ . '/../bootstrap.php';

if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

require_once(includePath('session.php'));

// 메인화면 우측에 플로팅되어 있는 숫자를 추출하는 부분
// 메인화면 우측에 플로팅되어 있는 숫자를 추출하는 부분

if (!isset($pdo) || !$pdo) {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();
}

$pdo = db_connect();  

// 결재라인을 설정하기 위해 사용자 정보를 배열에 저장
$eworks_level_arr = array(); 
$part_arr = array(); 
$position_arr = array();
$name_arr = array();
$id_arr = array();

try{
    $sql="select * from mirae8440.member WHERE part IN ('제조파트', '지원파트') ";
    $stmh=$pdo->prepare($sql);    
    $stmh->execute();
    
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {  
        array_push($name_arr, $row["name"]);  
        array_push($id_arr, $row["id"]);  
        array_push($eworks_level_arr, $row["eworks_level"]);  
        array_push($part_arr, $row["part"]);  
        array_push($position_arr, $row["position"]);          
   }
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}

// 결재권자 여부를 확인
$workLevel = 0; // 1차 결재권자 여부

$firstStep = array();
$firstStepID = array();
for($i = 0; $i < count($eworks_level_arr); $i++) {
    if((int)$eworks_level_arr[$i] == 2 or (int)$eworks_level_arr[$i] == 1) {
        array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);    
        array_push($firstStepID, $id_arr[$i]);
        if ($user_id === $id_arr[$i]) {
            $workLevel = 1;
        }
    }
}

// 각 상태별 문서 개수를 카운트하는 함수
function countEworksStatus($pdo, $user_id, $status, $workLevel) {	
	// view 설정
	$viewcon = " AND CONCAT('!', e_viewexcept_id, '!') NOT LIKE '%!{$user_id}!%' ";
	$viewconNone = " AND CONCAT('!', e_viewexcept_id, '!') LIKE '%!{$user_id}!%' ";

    $count = 0;
    $sql = "";

    if (!$workLevel) { // 일반 사용자의 경우 자신이 작성한 문서만 카운트
		// 상신인 경우는 send 상신인 경우도 미결도 함께 숫자표시
		if($status=='noend') 
			$sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE author_id = '$user_id' AND status = 'send' AND is_deleted IS NULL " . $viewcon;
		else
			$sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE author_id = '$user_id' AND status = '$status' AND is_deleted IS NULL " . $viewcon;
    } else { // 결재권자의 경우 다양한 상태의 문서를 카운트
        switch ($status) {
            case 'draft':
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE author_id = '$user_id' AND status = 'draft' AND is_deleted IS NULL"  . $viewcon;
                break;
            case 'send':
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE  author_id = '$user_id' AND CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'send' AND is_deleted IS NULL" . $viewcon;
                break;
			case 'noend':
				// '미결' 상태: 사용자가 결재해야 하는 문서 카운트
				// 첫 번째 결재권자에 대해 '상신' 상태를 '미결'로 처리
				// 그리고 나머지 결재권자에 대해서는 다음 결재자가 되는 경우를 처리
				$sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
					   " AND ( " .
					   "    (CONCAT('!', e_confirm_id, '!') = '!!' AND LOCATE('{$user_id}', e_line_id) = 1 AND status = 'send') " .
					   "    OR " .
					   "    (CONCAT('!', e_confirm_id, '!') NOT LIKE '%!{$user_id}!%' AND INSTR(CONCAT('!', e_line_id, '!'), CONCAT('!', SUBSTRING_INDEX(e_confirm_id, '!', -1), '!', '{$user_id}', '!')) > 0 AND status IN ('send', 'noend', 'ing')) " .
					   ") AND is_deleted IS NULL" . $viewcon;
				break;
            case 'ing':
                // '진행중' 상태: 사용자가 결재 중인 문서 카운트
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND CONCAT('!', e_confirm_id, '!') LIKE '%!{$user_id}!%' AND status IN ('send', 'ing') AND is_deleted IS NULL" . $viewcon;
                break;
            case 'end':
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'end' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'reject':
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'reject' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'wait':
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'wait' AND is_deleted IS NULL" . $viewcon;
                break;
            case 'refer':
                $sql = "SELECT COUNT(*) FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND status = 'refer' AND is_deleted IS NULL" . $viewcon;
                break;
        }
    }

    try {
        $stmh = $pdo->query($sql);
        $count = $stmh->fetchColumn();
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
     
	if($status!=='sql') 
		return $count;
	else
		return $sql;
}

// 각 상태별 문서 개수 카운트
// $statuses = ['draft', 'send', 'noend', 'ing', 'end', 'reject', 'wait', 'refer', 'sql'];
$statuses = ['draft', 'send', 'noend', 'ing', 'end', 'reject', 'wait', 'refer'];
$data = [];

foreach ($statuses as $status) {
    $data['val'.array_search($status, $statuses)] = countEworksStatus($pdo, $user_id, $status, $workLevel);
}
?>
