<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함

 $eworks_level= $_SESSION["eworks_level"];	
 
isset($_REQUEST["selnum"]) ? $selnum=$_REQUEST["selnum"] : $selnum='';  

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

// 결재라인을 잡으려면 배열저장
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

// var_dump($eworks_level_arr);

// 결재권자 여부를 확인하기 전에 $foundUser1을 초기화
$foundUser1 = 0;

// 결재권자 배열 넣기
$firstStep = array();
$firstStepID = array();
for($i = 0; $i < count($eworks_level_arr); $i++) {
    if((int)$eworks_level_arr[$i] == 2 or (int)$eworks_level_arr[$i] == 1) {
        array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i]);    
        array_push($firstStepID, $id_arr[$i]);
        // 현재 사용자가 결재권자 목록에 있으면 $foundUser1을 1로 설정
        if ($user_id === $id_arr[$i]) {
            $foundUser1 = 1;
        }
    }
}

$status_arr = array();

function countEworksStatus($pdo, $user_id, $viewCondition, $isApprover) {
	
    $counts = array("draft" => 0, "send" => 0, "noend" => 0, "ing" => 0, "end" => 0, "reject" => 0, "wait" => 0, "refer" => 0, "deleted" => 0);

    $sqlBase = $isApprover ? "SELECT * FROM mirae8440.eworks WHERE CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' AND is_deleted IS NULL" : "SELECT * FROM mirae8440.eworks WHERE author_id='$user_id' AND is_deleted IS NULL";
    $sql = $sqlBase . $viewCondition;

    try {
        $stmh = $pdo->prepare($sql);
        $stmh->execute();

        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            include $_SERVER['DOCUMENT_ROOT'] . "/eworks/_row.php";
            
			 if($isApprover) 
			  {
                $arr = explode("!", $e_line_id);
                $approval_time = explode("!", $e_confirm_id);
                $last_user_id = end($arr); // e_line_id의 마지막 사용자 ID
                $last_approved_id = end($approval_time); // e_confirm_id의 마지막 결재 ID

                foreach ($arr as $id) {
                    if ($id == $user_id) 
					  {
						if ($status !== 'reject' && $status !== 'wait' && $status !== 'refer' && $status !== 'end') 
						{
							if ($id == $last_user_id) { // 마지막 사용자인 경우
								if ($last_approved_id == $id) {
									$status = 'end'; // '결재완료'
								} else {
									if($status !=='send')
										$status = 'noend'; // '미결'
									 else
										 $status = ''; 
								}
							} else { // 마지막 사용자가 아닌 경우
								if (in_array($id, $approval_time)) {
									$status = 'ing'; // '진행중'
								} else {
										 $status = 'noend'; // '미결'
										 // echo $num;
									}
								}
						}
					  }
					} // end of for					
				}  // end of if			

            if (isset($counts[$status])) {
                $counts[$status]++;
            }
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }

    return $counts;
}

// 함수 호출
$viewconVisible = " AND CONCAT('!', e_viewexcept_id, '!') NOT LIKE '%!{$user_id}!%' ";
$visibleCounts = countEworksStatus($pdo, $user_id, $viewconVisible, $foundUser1);

$viewconDeleted = " AND CONCAT('!', e_viewexcept_id, '!') LIKE '%!{$user_id}!%' ";
$deletedCounts = countEworksStatus($pdo, $user_id, $viewconDeleted, $foundUser1);

// 각 상태별 카운트 할당
$data = array(
    "val1" => $visibleCounts["draft"],
    "val2" => $visibleCounts["send"],
    "val3" => $visibleCounts["noend"],
    "val4" => $visibleCounts["ing"],
    "val5" => $visibleCounts["end"],
    "val6" => $visibleCounts["reject"],
    "val7" => $visibleCounts["wait"],
    "val8" => $visibleCounts["refer"],
    "val9" => $deletedCounts["deleted"]
);
	
	// 탭 데이터 설정
	$tabs = array(
		array("작성", 1, "bi-pencil-square", $data["val1"]),
		array("상신", 2, "bi-cloud-arrow-up", $data["val2"]),
		array("미결", 3, "bi-patch-minus", $data["val3"]),		
		array("진행", 4, "bi-arrow-right-circle", $data["val4"]),
		array("결재", 5, "bi-journal-check", $data["val5"]),
		array("반려", 6, "bi-slash-circle", $data["val6"]),
		array("보류", 7, "bi-hourglass", $data["val7"]),
		array("참조", 8, "bi-info-circle", $data["val8"]),
		array("삭제", 9, "bi-trash", $data["val9"])
	);
// echo '<pre>';
// print_r($tabs);
// echo '</pre>';		
?>				
		
<ul class="nav nav-tabs justify-content-center">
	<?php foreach ($tabs as $tab) {
		$label = $tab[0];
		$tabId = $tab[1];
		$iconClass = $tab[2];
		$count = $tab[3];
		$active = '';
		if($selnum == $tabId)
			$active = 'active';
		// if($eworks_level && ($tabId>=3) )	
		if(!$eworks_level && ($tabId>0) || $eworks_level && ($tabId>=3))	
		{	
	?>
		<li class="nav-item">			
			<div class="nav-link text-dark <?php echo $active;?> " id="navtab<?php echo $tabId; ?>" onclick="seltab(<?php echo $tabId; ?>);">
				<i class="bi <?php echo $iconClass; ?>"></i> <?php echo $label; ?>&nbsp;
				<?php if ($count > 0) { ?>
					<span class="badge bg-primary"><?php echo $count; ?></span>
				<?php } ?>
			</div>
		</li>
	<?php 
	   } 
			}	
	?>
</ul>
