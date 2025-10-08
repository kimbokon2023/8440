<?php require_once __DIR__ . '/../bootstrap.php';

if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

require_once(includePath('session.php'));

require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
?>

<div id="eworks_list" style="height:520px;" class="mb-1">		
  <!-- 로딩 인디케이터 -->
<div id="loadingIndicator" style="display: none;">
    <div class="loader"></div>
</div>

	<table class="table table-hover table-sm" id="myEworks_Table" >	

<?php
if (!isset($pdo) || !$pdo) {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();
}

$pdo = db_connect();	
	// 결재라인을 잡으려면 배열저장
	$eworks_level_arr = array();
	$part_arr = array();
	$position_arr = array();
	$name_arr = array();
	$id_arr = array();

	try{
		$sql="select * from mirae8440.member ";
		$stmh=$pdo->prepare($sql);    
		$stmh->execute();
		
		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
			if($row["part"] ==='제조파트' || $row["part"] ==='지원파트')
			{
				array_push($name_arr, $row["name"]);	
				array_push($id_arr, $row["id"]);	
				array_push($eworks_level_arr, $row["eworks_level"]);	
				array_push($part_arr, $row["part"]);	
				array_push($position_arr, $row["position"]);	
			}
	   }
	} catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();

	}
	
// var_dump($eworks_level_arr);

	// 결재권자 배열 넣기
	$firstStep = array();
	$firstStepID = array();
	for($i=0;$i<count($eworks_level_arr);$i++)
	{
		if((int)$eworks_level_arr[$i] === 2 or (int)$eworks_level_arr[$i] === 1  )
		{
			array_push($firstStep, $name_arr[$i] . " " . $position_arr[$i] );	
			array_push($firstStepID, $id_arr[$i] );	
		}
		
	}
	
	isset($_REQUEST["eworksPage"])  ? $eworksPage=$_REQUEST["eworksPage"] :   $eworksPage=''; 
	isset($_REQUEST["EworksSearch"])  ? $EworksSearch=$_REQUEST["EworksSearch"] :   $EworksSearch=''; 
	isset($_REQUEST["eworksel"])  ? $eworksel=$_REQUEST["eworksel"] :   $eworksel='draft'; 
	isset($_REQUEST["author_id"])  ? $author_id=$_REQUEST["author_id"] :   $author_id=''; 
			 	
	isset($_REQUEST["eworksPage"])  ? $eworksPage=$_REQUEST["eworksPage"] :   $eworksPage=1; 

	if((int)$eworksPage<1) $eworksPage=1;

	  $scale = 8;       // 한 페이지에 보여질 게시글 수
	  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
	  $first_num = ($eworksPage-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.  	   

	$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분

	$where = " ";
	$andwhere = " "; 

	 // 결재 이름이 있는 경우 
	$admin = 0; // 1차 결재권자

	for($i = 0; $i < count($firstStepID); $i++) {
		if($user_id === $firstStepID[$i]) {
			$admin = 1;
			break; // 일치하는 경우가 발견되면 루프를 종료
		}
	}
	
// 조건을 만들어서 결재권자 올리는 분들의 구분이 있어야 한다.
	
// view가 보이지 않는 설정 찾기
$viewcon = " AND CONCAT('!', e_viewexcept_id, '!') NOT LIKE '%!{$user_id}!%' " ;
// view 설정 찾기
$viewconNone = " AND CONCAT('!', e_viewexcept_id, '!') LIKE '%!{$user_id}!%' " ;

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
		// 첫 번째 결재권자에 대해 '상신' 상태를 '미결'로 처리
		// 그리고 나머지 결재권자에 대해서는 다음 결재자가 되는 경우를 처리
		$all = "CONCAT('!', e_line_id, '!') LIKE '%!{$user_id}!%' " .
			   "AND ( " .
			   "    (CONCAT('!', e_confirm_id, '!') = '!!' AND LOCATE('{$user_id}', e_line_id) = 1 AND status = 'send') " .
			   "    OR " .
			   "    (CONCAT('!', e_confirm_id, '!') NOT LIKE '%!{$user_id}!%' AND INSTR(CONCAT('!', e_line_id, '!'), CONCAT('!', SUBSTRING_INDEX(e_confirm_id, '!', -1), '!', '{$user_id}', '!')) > 0 AND status != 'send')" .
			   ") " .
			   "AND is_deleted IS NULL AND status != 'end' AND status != 'reject' AND status != 'wait'" . $viewcon;
		$where = "WHERE " . $all;
		$andwhere = "AND " . $all;
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
	
	if ($EworksSearch == "") {
		$sqlcon = "select * from mirae8440.eworks " . $where;
	} elseif ($EworksSearch != "") {
		$sqlcon = "select * from mirae8440.eworks where ((e_title like '%$EworksSearch%') or (contents like '%$EworksSearch%') or (e_line like '%$EworksSearch%') or (r_line like '%$EworksSearch%')) " . $andwhere;
	}

	try {
		$allstmh = $pdo->query($sqlcon);
		$total_row = $allstmh->rowCount();
	} catch (PDOException $Exception) {
		print "오류: " . $Exception->getMessage();
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
	$a= " " . $orderby . " limit $first_num, $scale";  

	if ($EworksSearch == "") {
		$sql = "select * from mirae8440.eworks " . $where . $a;
	} elseif ($EworksSearch != "") {
			$sql = "select * from mirae8440.eworks where ((e_title like '%$EworksSearch%') or (contents like '%$EworksSearch%') or (e_line like '%$EworksSearch%') or (r_line like '%$EworksSearch%')) " . $andwhere . $a;
		}

			   
		try{  
			$allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
			$temp2=$allstmh->rowCount();  
			$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
			$temp1=$stmh->rowCount();

			$total_row = $temp2;     // 전체 글수	 
					  
			// 페이지가 넘어간 경우는 1페이지로 만들어주는 로직
			if($total_row < ($eworksPage-1) * $scale)
				$eworksPage = 1;			  

	$total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	$current_page = ceil($eworksPage/$page_scale); //현재 페이지 블록 위치계산	

	  if ($eworksPage<1)  
				$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
			  else 
				$start_num=$total_row-($eworksPage-1) * $scale;
	 
		$count = $stmh->rowCount();       

		if($count<1){  
			print '<div class="row d-flex mt-3 p-1 mb-1 justify-content-center" >	자료가 없습니다. </div>';
		 }   else    
				{  
				$start_num = 0;	
				while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

					include getDocumentRoot() . "/eworks/_row.php";						
						
				switch ($status) {					
					   case 'draft' :
						  $statusStr = "작성";
					   break;					
					   case 'send' :
						  $statusStr = "상신";
					   break;					   
					   case 'noend' :					      
						  $statusStr = "미결";						  
					   break;							
					   case 'ing' :					      
						  $statusStr = "진행";						  
					   break;									
					   case 'end' :
						  $statusStr = "결재완료";
					   break;
					   case 'reject' :
						  $statusStr = "반려";
					   break;
					   case 'wait' :
						  $statusStr = "보류";
					   break;
					   case 'refer' :
						  $statusStr = "참조";
					   break;
					}								
					
					$prograssStr = ''; 
					
					//print $e_line;
					$arr = explode("!",$e_line_id);		
					$arr_str = explode("!",$e_line);		

					// 결재시간 추출해서 조합하기
					$approval_time = explode("!",$e_confirm_id);	
					$approval_str = explode("!",$e_confirm);						
					
					for($i=0;$i<count($arr);$i++) 
						if($approval_time[$i] !== '' && $approval_time[$i] !== null)
							  $prograssStr .=  $approval_str[$i] .  '<br>';
							else
								$prograssStr .=  $arr_str[$i] . " "  . '<br>';
					// print count($approval_time);
					
					$e_viewexcept_id_value = explode("!",$e_viewexcept_id);	
					$e_viewexcept_exist = 0;
					if (in_array($user_id, $e_viewexcept_id_value)) 
						  $e_viewexcept_exist = 1;									
				  
 // print $sql;			  
if($start_num<1)
{		
?>		

	<thead class="table-primary">
	   <tr>
        <th class="text-center" style="width:5%;">
            <input type="checkbox" id="checkAll" class="master-checkbox" />
            <label for="checkAll" class="checkbox-numbered-label"></label>
        </th>
		<th class="text-center" style="width:10%;"> 구분 </th>
		<th class="text-center" style="width:10%;"> 작성일시 </th>
		<th class="text-center" style="width:5%;"> 작성자 </th>
		<th class="text-center" style="width:7%;"> 현재상태</th>
		<th class="text-center" style="width:15%;"> 결재진행</th>				
		<th class="text-center" style="width:10%;"> 참조자</th>
		<th class="text-center" style="width:25%;"> 제목</th>
		<?php if($e_viewexcept_exist) { ?>									
				<th class="text-center align-items-center" style="width:10%;">   <i class="bi bi-skip-backward"></i> 복구 </th>													
		<?php } else if($status === 'end') { ?>
				<th class="text-center align-items-center" style="width:10%;">
				   <button type="button" class="btn btn-outline-danger btn-sm" id="selectedDeleteBtn" onclick="deleteSelectedEworks()">
						<i class="bi bi-trash"></i> 삭제 
					</button>
			     </th>		
		<?php } else if($admin && $status === 'ing') { ?>
				<th class="text-center align-items-center" style="width:10%;">
				   <button type="button" class="btn btn-outline-primary btn-sm" id="approvalselectedBtn"  onclick="approvalSelectedEworks()">
						<i class="bi bi-duffle"></i> 결재
					</button>
			     </th>
		<?php }  ?>												
		
	  </tr>	  
	</thead>	
	<tbody>									 
<?php } ?>							 
							 
<tr>
        <td class="text-center">
			<input type="checkbox" class="checkItem"  style="width:5%;" id="checkItem<?= $Eworks_record_num + 1 ?>" data-id="<?= $e_num ?>" />
            <label for="checkItem<?=($Eworks_record_num+1)?>" class="checkbox-numbered-label"> <?=($Eworks_record_num+1)?></label>
        </td>													
				<td class="text-center" style="width:10%;" onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');"><?=$eworks_item?> </td>							     			
				<td class="text-center" style="width:10%;" onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');"><?=$registdate?></td>							     			
				<td class="text-center" style="width:5%;"  onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');"><?=$author?></td>							     			
				<td class="text-center" style="width:7%;"  onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');"><?=$statusStr?></td>							     								
				<td class="text-center" style="width:15%;" onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');"><?=$prograssStr?></td>							     			
				<?php                  
				// 표시할 문자열 길이를 제한   
				$display_text = mb_strlen($r_line) > 10 ? mb_substr($r_line, 0, 8) . '...' : $r_line;
				?>
				<td class="text-start" style="width:10%;" onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');" title="<?= htmlspecialchars($r_line, ENT_QUOTES, 'UTF-8'); ?>">
					<?= htmlspecialchars($display_text, ENT_QUOTES, 'UTF-8'); ?>
				</td>
									
				<td class="text-start" style="width:25%;" onclick="javascript:viewEworks_detail('<?=$e_num?>','<?=$eworksPage?>');" ><?=iconv_substr($e_title,0,30,"utf-8")?> </td>	
				<?php if($e_viewexcept_exist) { ?>									
						<td class="text-center" style="width:10%;"> 
							<button type="button" class="btn btn-outline-dark btn-sm"  id="eworks_restoreBtn" onclick="restore('<?=$e_num?>','<?=$eworksPage?>');">  <i class="bi bi-skip-backward"></i> 
							</button>
						</td>							     			
				<?php } else if($status === 'end') { ?>
						<td class="text-center" style="width:10%;">  
							<button type="button" class="btn btn-outline-danger btn-sm"  id="eworks_viewExceptBtn" onclick="viewExcept('<?=$e_num?>','<?=$eworksPage?>');">  <i class="bi bi-trash"></i> 
							</button>
						</td>							     			
				<?php } else if($admin && $status === 'ing') { ?>					
					   <td class="text-center" style="width:10%;"> 
							<button type="button" class="btn btn-outline-primary btn-sm" id="eworks_approvalviewExceptBtn" onclick="approvalviewExcept('<?=$e_num?>','<?=$eworksPage?>');"> <i class="bi bi-duffle"></i>  
							</button>
						</td>		
				<?php }  ?>										
			</tr>								
					<?php
						$Eworks_record_num++;
						$start_num++;
					  } 						
					}
				  } catch (PDOException $Exception) {
				  print "오류: ".$Exception->getMessage();
				  }  
				  
				   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)				   
					  $start_page = ($current_page - 1) * $page_scale + 1;
				   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
					  $end_page = $start_page + $page_scale - 1;  
				 ?>					
						</tbody>
					</table>
					
				<div class="row row-cols-auto mt-1 mb-2 justify-content-center align-items-center">  
				 <?php
					if($eworksPage!=1 && $eworksPage>$page_scale){
					  $prev_page = $eworksPage - $page_scale;    
					  // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
					  if($prev_page <= 0) 
								$prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
							print '<button class="btn btn-outline-secondary btn-sm" type="button"  onclick="javascript:eworks_movetoPage(' . $prev_page . ');"> ◀ </button> &nbsp;' ;              
					}
					for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
					  if($eworksPage==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
						print '<span class="text-secondary" >  ' . $i . '  </span>'; 
					  else 
						   print '<button class="btn btn-outline-secondary btn-sm" type="button"  onclick="javascript:eworks_movetoPage(' . $i . ');"> ' . $i . '</button> &nbsp;' ;     			
					}

					if($eworksPage<$total_page){
					  $next_page = $eworksPage + $page_scale;
					  if($next_page > $total_page) 
							 $next_page = $total_page;						
							print '<button class="btn btn-outline-secondary btn-sm" type="button"  onclick="javascript:eworks_movetoPage(' . $next_page . ');"> ▶ </button> &nbsp;' ; 
					}
					?>              
				</div>			
</div>	
