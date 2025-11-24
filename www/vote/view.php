<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$admin = $_SESSION["level"] === 1 ? '1' : '0';
$WebSite = $_SESSION["WebSite"] ?? getBaseUrl() . '/';

// 권한 체크
if($level > 8) {          
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}

// 첫 화면 표시 문구
$title_message = '우리의 당당한 투표!'; 

include includePath('load_header.php');
?> 
  
<title> <?=$title_message?> </title> 

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
            .d-flex.mt-3.mb-2.justify-content-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-3.mb-2.justify-content-center h3 {
                font-size: 1.25rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                text-align: center !important;
                margin: 0.5rem 0 !important;
            }
            
            /* 버튼 그룹 최적화 */
            .row.d-flex.m-1.mt-2.mb-2.justify-content-left.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .row.d-flex.m-1.mt-2.mb-2.justify-content-left.align-items-center button {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0.25rem 0 !important;
                padding: 0.5rem !important;
                font-size: 1rem !important;
            }
            
            /* 정보 테이블 최적화 */
            .row.d-flex.p-1.m-1.mt-2.mb-2.justify-content-left.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0.5rem !important;
            }
            
            .table-responsive {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: auto !important;
            }
            
            .table-bordered {
                font-size: 0.875rem !important;
            }
            
            .table-bordered td {
                padding: 0.5rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            /* 본문 내용 최적화 */
            .row.p-2.m-2.mt-1.mb-1.align-items-center {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0.5rem !important;
            }
            
            .row.p-2.m-2.mt-1.mb-1.align-items-center .col-sm-8,
            .row.p-2.m-2.mt-1.mb-1.align-items-center .col-sm-4 {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0.5rem !important;
            }
            
            .row.p-2.m-2.mt-1.mb-1.align-items-center span {
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
            }
            
            /* 차트 최적화 */
            .vote-chart-canvas {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }
            
            /* 투표 테이블 최적화 */
            .row.d-flex.mt-3.mb-1.justify-content-center {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0.5rem !important;
            }
            
            .row.d-flex.mt-3.mb-1.justify-content-center .card {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .d-flex.mt-2.mb-2.justify-content-center {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .d-flex.mt-2.mb-2.justify-content-center span {
                width: 100% !important;
                max-width: 100% !important;
                text-align: center !important;
                margin: 0.25rem 0 !important;
            }
            
            #dynamicTable {
                font-size: 0.875rem !important;
            }
            
            #dynamicTable th,
            #dynamicTable td {
                padding: 0.5rem !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            #dynamicTable input,
            #dynamicTable button {
                width: 100% !important;
                max-width: 100% !important;
                font-size: 0.875rem !important;
            }
            
            #dynamicTable .progress {
                width: 100% !important;
                max-width: 100% !important;
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
    </style> 
	
 </head> 
 
<body>
<?php include includePath('common/modal.php'); ?>
	 
 <?php
 
$file_dir = includePath('uploads/'); 
  
// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$tablename = $_REQUEST["tablename"] ?? 'vote';
$search = $_REQUEST["search"] ?? '';
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$find = $_REQUEST["find"] ?? '';
$year = $_REQUEST["year"] ?? '';
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';

// 데이터 검증
if(empty($num)) {
    echo "<script>alert('게시글 번호가 없습니다.'); window.close();</script>";
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
try {
    $sql = "SELECT * FROM " . $DB . "." . $tablename . " WHERE num = ?";
    $stmh = $pdo->prepare($sql);  
    $stmh->bindValue(1, $num, PDO::PARAM_INT);      
    $stmh->execute();            
      
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if($row) {
        $item_num = $row["num"] ?? '';
        $item_id = $row["id"] ?? '';
        $item_name = $row["name"] ?? '';
        $item_nick = $row["nick"] ?? '';
        $item_hit = $row["hit"] ?? 0;
        $item_date = $row["regist_day"] ?? '';
        $item_date = substr($item_date, 0, 10);
        $item_subject = $row["subject"] ?? '';
        $item_content = $row["content"] ?? '';
        $is_html = $row["is_html"] ?? '';
        $noticecheck = $row["noticecheck"] ?? '';
        $status = $row["status"] ?? '';
        $deadline = $row["deadline"] ?? '';
        $votelist = $row["votelist"] ?? '{}'; // votelist 값이 없을 경우의 기본값	 
	 
        
        if($noticecheck == 'y') {
            $noticecheck_memo = '(전체공지)';
        } else {
            $noticecheck_memo = '';
        }
        
        if($is_html == 'y') {
            $item_content = htmlspecialchars_decode($item_content);     	
        }	
        
        $item_content = str_replace("\r", "<br>", $item_content);

        // 조회수 증가
        $new_hit = $item_hit + 1;
        try {
            $pdo->beginTransaction(); 
            $sql = "UPDATE " . $DB . "." . $tablename . " SET hit = ? WHERE num = ?";
            $stmh = $pdo->prepare($sql);  
            $stmh->bindValue(1, $new_hit, PDO::PARAM_INT);      
            $stmh->bindValue(2, $num, PDO::PARAM_INT);           
            $stmh->execute();
            $pdo->commit(); 
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            error_log("조회수 증가 오류: " . $Exception->getMessage());
        }	  
    } else {
        echo "<script>alert('게시글을 찾을 수 없습니다.'); window.close();</script>";
        exit;
    }
    
    // 초기 프로그램은 $num사용 이후 $id로 수정중임  
    $id = $num;
    $author_id = $item_id;
    
} catch (PDOException $Exception) {
    error_log("데이터 조회 오류: " . $Exception->getMessage());
    echo "<script>alert('데이터 조회 중 오류가 발생했습니다.'); window.close();</script>";
    exit;
}
?>

  
<style>

.vote-chart-canvas {
	width: 100%;  /* 부모 컨테이너의 폭에 맞춥니다 */
	max-width: 400px; /* 최대 가로 폭을 제한합니다 */		
	/*  aspect-ratio: 1 / 1; 가로 세로 비율을 1:1로 유지합니다 */
}

.form-control-scrollable {
	overflow: auto; /* 내용이 넘칠 때 스크롤바 표시 */
	height: auto;
	white-space: nowrap; /* 수평 스크롤을 위해 내용을 한 줄로 유지 */
}
	
</style>
 
</head>

<?php
// Hidden input 변수 초기화
$parentid = $_REQUEST["parentid"] ?? '';
$fileorimage = $_REQUEST["fileorimage"] ?? '';
$item = $_REQUEST["item"] ?? '';
$upfilename = $_REQUEST["upfilename"] ?? '';
$savetitle = $_REQUEST["savetitle"] ?? '';
$pInput = $_REQUEST["pInput"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
?>

<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?= htmlspecialchars($parentid, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?= htmlspecialchars($fileorimage, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="item" name="item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?= htmlspecialchars($upfilename, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?= htmlspecialchars($savetitle, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?= htmlspecialchars($pInput, ENT_QUOTES, 'UTF-8') ?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>" >				
	<input type="hidden" id="status" name="status" value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" >				
 
<?php if ($chkMobile): ?>
    <!-- 모바일 환경일 때 보이는 버튼 -->	  
    <div class="container-fluid p-2 m-1">  	
<?php else: ?>
    <!-- PC 환경일 때 보이는 버튼 -->	
	<div class="container justify-content-center mb-5">  
<?php endif; ?>		

	<div class="card mt-2 mb-5">  
	<div class="card-body">  
		<div class="d-flex mt-3 mb-2 justify-content-center">  
			<h3>  <?=$title_message?> </h3> 
		</div>	
	 <div class="row d-flex m-1 mt-2 mb-2 justify-content-left  align-items-center">  				
	<div class="col-sm-10">  				
    <span class="me-1"> </span>					
	<button type="button" id="closeBtn"  class="btn btn-dark btn-sm me-1" >  &times; 닫기 </button>
	<?php
	// 삭제 수정은 관리자와 글쓴이만 가능토록 함
	if(isset($_SESSION["userid"])) {
		if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" || intval($_SESSION["level"]) === 1) {
			$modifyParams = http_build_query(array(
				'tablename' => $tablename,
				'mode' => 'modify',
				'num' => $num,
				'page' => $page,
				'search' => $search,
				'Bigsearch' => $Bigsearch,
				'find' => $find,
				'year' => $year,
				'process' => $process,
				'asprocess' => $asprocess,
				'fromdate' => $fromdate,
				'todate' => $todate,
				'separate_date' => $separate_date
			));
	?>
			<button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?<?= htmlspecialchars($modifyParams, ENT_QUOTES, 'UTF-8') ?>'" >  <i class="bi bi-pencil-square"></i>  수정  </button>			
			<button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'" >  <i class="bi bi-pencil"></i>  신규 </button>			
			<button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php')" > <i class="bi bi-trash"></i>  삭제   </button>
	<?php
		}
	}
	?>				
	</div>  
	</div>  
	 
	 <div class="row d-flex p-1 m-1 mt-2 mb-2 justify-content-left  align-items-center">  				
	 <div class="table-responsive">  				
		<table class="table table-bordered">
		   <tbody>
		   <tr>
		     <td class="text-center bg-secondary text-white">
			   진행상태 
			</td>
			<td class="text-center">	<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>  </td>
			<td class="text-center bg-secondary text-white">	작성일 </td>
			<td class="text-center">	<?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?>   </td>			   
			<td class="text-center bg-secondary text-white">	마감일 </td>
			<td class="text-center">	<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>   </td>			   
			<td class="text-center bg-secondary text-white">  글제목  </td>
			<td class="text-center">  <?= htmlspecialchars($item_subject, ENT_QUOTES, 'UTF-8') ?> </td>			  
			<td class="text-center bg-secondary text-white">작성자 </td>
			<td class="text-center"> <?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?> </td>
			</tr>
			</tbody>
		</table>
		  	 
	  </div>
	  </div>
	   <div class="row p-2 m-2 mt-1 mb-1 align-items-center"> 
			 <div class="col-sm-8">             	   
			 <span > <?=$item_content ?> </span>
			</div>
			 <div class="col-sm-4 ">     
			    <div class="card">     
					<div class="card-body">     
						<canvas id="voteChart" class="vote-chart-canvas"></canvas>
					</div>					
				</div>
			</div>
		</div>

		 <div class="row d-flex mt-3 mb-1 justify-content-center">  	
				<div class="card p-2"> 
				<div class="card-body">  
					<div class="d-flex mt-2 mb-2 justify-content-center">    
						 <span class=" fs-5 text-center mb-2 me-3"> 투표하기 </span>		
						 <span id="voteNum" class="fs-5 text-center mb-2"> </span>						 
					</div>	 
					<div class="table-responsive">					
						<table class="table table-bordered" id="dynamicTable">
							<thead class="table-primary">
								<tr>
									<th style="width:5%;" class="text-center" >번호</th>
									<th style="width:30%;" class="text-center" >투표항목</th>
									<th style="width:15%;" class="text-center" >결과</th>
									<th style="width:12%;" class="text-center" >선택</th>
									<th style="width:38%;" class="text-center" >투표자</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
				</div>
			</div>
			</div>
		 </div>
		 
			</div>
		</div>
	</div> 
</form>

  <form id="Form1" name="Form1">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>" >
  </form>  

<script> 
$(document).on('click', '.vote-button', function() {
    var user_name = <?php echo json_encode($user_name, JSON_UNESCAPED_UNICODE); ?>;
    var itemIndex = $(this).data('item-index');

    updateVote(user_name, itemIndex);
});

function updateTotalVoters() {
    var totalVoters = 0;

    $('#dynamicTable tbody tr').each(function() {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        totalVoters += voters.length;
    });

    $('#voteNum').text('(' + totalVoters + '명 투표)');
}

function updateVote(user_name, itemIndex) {
	
	var status = <?php echo json_encode($status, JSON_UNESCAPED_UNICODE); ?>;
	
	if(status === '진행중')
	{	
	
    var alreadyVotedIndex = -1;

    // 기존 투표 확인 및 새 투표 업데이트
    $('#dynamicTable tbody tr').each(function(index) {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ');

        if (voters.includes(user_name)) {
            alreadyVotedIndex = index; // 이미 투표한 항목 인덱스 저장
            voters = voters.filter(voter => voter !== user_name); // 기존 투표 제거
            $(this).find('input[name="col3[]"]').val(voters.join(', '));
        }
    });

    // 새로운 투표 추가 (이전 투표와 다른 경우에만)
    if (alreadyVotedIndex !== itemIndex) {
        var newVoters = $('#dynamicTable tbody').find('tr').eq(itemIndex).find('input[name="col3[]"]').val().split(', ');
        newVoters.push(user_name);
        $('#dynamicTable tbody').find('tr').eq(itemIndex).find('input[name="col3[]"]').val(newVoters.join(', '));
    }
	
    let columns = {
        col1: [],
        col2: [],
        col3: []
    };	

	$('#dynamicTable tbody tr').each(function(index) {
		var col1 = $(this).find('input[name="col1[]"]').val();
		var col2 = $(this).find('input[name="col2[]"]').val();
		var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);

		if (index === itemIndex && !voters.includes(user_name)) {
			voters.push(user_name); // 중복되지 않는 경우에만 추가
		} else if (index !== itemIndex) {
			voters = voters.filter(voter => voter !== user_name); // 다른 행에서는 제거
		}

		// 업데이트된 참여자 목록으로 col3 값을 설정
		$(this).find('input[name="col3[]"]').val(voters.join(', '));

		if (col1 && col2) {
			columns.col1.push(col1);
			columns.col2.push(col2);
			columns.col3.push(voters.join(', '));
		}
	});

    const dataToSend = {
        num: <?php echo json_encode($num, JSON_UNESCAPED_UNICODE); ?>,
        data: columns
    };

		// AJAX 요청 보내기
		  $.ajax({
			url: 'makejsonlist.php',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(dataToSend),
			success: function(response) {
				console.log(response);
				Swal.fire({
					title: '투표 성공',
					text: '투표가 성공적으로 되었습니다.',
					icon: 'success'
				}).then((result) => {
					if (result.isConfirmed) {
						location.reload(); // 여기서 페이지를 리로드합니다.
					}
				});
			},
			error: function(error) {
				console.error('투표 실패: ', error);
				Swal.fire(
					'투표 실패',
					'투표 중 오류가 발생했습니다. 다시 시도해주세요.',
					'error'
				);        
			}
		});			
		updateResults(); // 결과 업데이트
		updateTotalVoters(); // 총 투표자 수 업데이트		
	}
}

function updateResults() {
    var totalVotes = 0;

    // 전체 투표 수 계산
    $('#dynamicTable tbody tr').each(function() {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        totalVotes += voters.length;
    });

    // 각 항목의 투표 결과 업데이트
    $('#dynamicTable tbody tr').each(function() {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        var voteCount = voters.length;
        var votePercentage = totalVotes > 0 ? (voteCount / totalVotes) * 100 : 0;
        
        $(this).find('.resultVote').css('width', votePercentage + '%').attr('aria-valuenow', votePercentage).text(voteCount + ' 표');
    });
}


$(document).ready(function() {
    var i = 1;

    // '-' 버튼 이벤트 핸들러: 현재 행 삭제
    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });

    var piclistObj = {};
    try {
        piclistObj = <?php echo $votelist; ?>;
    } catch (e) {
        console.error("JSON 파싱 오류: ", e);
    }

    // Row_COUNT를 piclistObj의 col2 배열 길이에 따라 동적으로 설정
    const Row_COUNT = piclistObj.col2 ? piclistObj.col2.length : 0;
    const COL_NAMES = 3; // 이제 열의 수는 3개입니다.
    const column = Array.from({ length: COL_NAMES }, function(_, i) { return 'col' + (i+1); });

    // 데이터가 없는 경우에만 초기 행 추가
    if (!piclistObj.col2 || piclistObj.col2.length === 0) {
        $('#dynamicTable tbody').append('<tr id="row1">' +
            '<td><input type="text" name="col1[]" class="form-control text-center"  value="1" /></td>' +
            '<td><input type="text" name="col2[]" class="form-control" readonly /></td>' +
            '<td> </td>' +
            '<td class="text-center"><button type="button" class="btn btn-success btn-sm text-center vote-button" data-item-index="0">투표하기</button></td>' +
            '<td class="text-center"><input type="text" name="col3[]" class="form-control form-control-scrollable" readonly /></td>' + // 참여자 목록을 추가합니다.
        '</tr>');
    }

    const data = Array.from({ length: Row_COUNT }, function(_, i) {
        var row = {};
        column.forEach(function(col, index) {
            row[col] = (piclistObj[col] && piclistObj[col][i] ? piclistObj[col][i] : '');
        });
        return row;
    });

    data.forEach(function(row, index) {
        // col2에 값이 있는 경우에만 행을 추가합니다.
        if (row.col2) {
            $('#dynamicTable tbody').append('<tr>' +
                '<td><input type="text" name="col1[]" class="form-control text-center" value="' + (index + 1) + '" /></td>' +
                '<td> <input type="text" name="col2[]" class="form-control" readonly value="' + row.col2 + '" /></td>' +
                '<td>   <div class="progress">  <div class="progress-bar resultVote" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>  </div>    </td>' +
                '<td class="text-center"><button type="button" class="btn btn-success btn-sm text-center vote-button" data-item-index="' + index + '">투표하기</button></td>' +
                '<td class="text-center"><input type="text" name="col3[]" class="form-control form-control-scrollable" value="' + (row.col3 || '') + '" readonly /></td>' + // 참여자 목록을 추가합니다.
            '</tr>');
        }
    });
	
    updateResults(); // 결과 업데이트
    updateTotalVoters(); // 총 투표자 수 업데이트
	
	// 투표결과를 차트로 보여주기	
    var ctx = document.getElementById('voteChart').getContext('2d');
    var voteLabels = []; // 투표 항목의 이름을 저장할 배열
    var voteData = []; // 각 항목에 대한 투표 수를 저장할 배열

    // 테이블에서 투표 데이터 수집
    $('#dynamicTable tbody tr').each(function() {
		var item = $(this).find('td:nth-child(2) input').val(); // 투표 항목 이름
        console.log(item);
        
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        var voteCount = voters.length; // 투표 수

        voteLabels.push(item);
        voteData.push(voteCount);
    });

    // 모바일 환경 감지
    var isMobile = window.innerWidth <= 768;
    
    // 파이 차트 생성
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: voteLabels,
            datasets: [{
                label: '투표 결과',
                data: voteData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    // 추가 색상 필요 시 여기에 추가
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    // 추가 색상 필요 시 여기에 추가
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: isMobile ? 1 : 1,
            plugins: {
                legend: {
                    display: true,
                    position: isMobile ? 'bottom' : 'right',
                    labels: {
                        font: {
                            size: isMobile ? 12 : 14
                        },
                        padding: isMobile ? 10 : 15
                    }
                },
                datalabels: {
                    color: '#000000', // 라벨 색상
                    font: {
                        size: isMobile ? 10 : 12
                    },
                    formatter: function(value, context) {
                        return context.chart.data.labels[context.dataIndex] + ': ' + value;
                    }
                }
            }
        }    
    });  // end of chart function
    
    // 창 크기 변경 시 차트 크기 조정
    $(window).on('resize', function() {
        var isMobileNow = window.innerWidth <= 768;
        if (isMobileNow !== isMobile) {
            isMobile = isMobileNow;
            myChart.options.plugins.legend.position = isMobile ? 'bottom' : 'right';
            myChart.options.plugins.legend.labels.font.size = isMobile ? 12 : 14;
            myChart.options.plugins.legend.labels.padding = isMobile ? 10 : 15;
            myChart.options.plugins.datalabels.font.size = isMobile ? 10 : 12;
            myChart.options.aspectRatio = isMobile ? 1 : 1;
            myChart.resize();
        }
    });
	

});

function del(href) {    
    var user_id = <?php echo json_encode($user_id, JSON_UNESCAPED_UNICODE); ?>;
    var item_id = <?php echo json_encode($item_id, JSON_UNESCAPED_UNICODE); ?>;
    var admin = <?php echo json_encode($admin, JSON_UNESCAPED_UNICODE); ?>;
	if( user_id !== item_id && admin !== '1' )
	{
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
        Swal.fire({
            title: '자료 삭제',
            text: "삭제는 신중! 정말 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
				$.ajax({
					url:'delete.php',
					type:'post',
					data: $("#Form1").serialize(),
					dataType: 'json',
					}).done(function(data){		
						Toastify({
							text: "파일 삭제완료 ",
							duration: 2000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								// window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
								 $('#closeBtn').click();
							}							
							
						}, 1000);
			
					  
					});
            }
        });
    }
}

$(document).ready(function(){	

	$('#closeBtn').click(function() {
		window.close(); // 현재 창 닫기
	});
});
   
</script>

</body>
</html>