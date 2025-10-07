<?php\nrequire_once __DIR__ . '/../common/functions.php';
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';	

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
$client->addScope(\Google\Service\Drive::DRIVE);

// Google Drive 서비스 초기화
$service = new \Google\Service\Drive($client);

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}

// Google Drive에서 파일 썸네일 검사 및 반환
function getThumbnail($fileId, $service) {
    try {
        $file = $service->files->get($fileId, ['fields' => 'thumbnailLink']);
        return $file->thumbnailLink ?? null; // 썸네일 URL이 있으면 반환, 없으면 null
    } catch (Exception $e) {
        error_log("썸네일 가져오기 실패: " . $e->getMessage());
        return null; // 실패 시 null 반환
    }
}
 
$title_message = '포미스톤 견적서';      
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? ''; 
?> 
 
<?php include getDocumentRoot() . '/load_header.php'; ?> 
 
<title> <?=$title_message?>  </title>  
 
<style>
#showextract {
	display: inline-block;
	position: relative;
}
		
.showextractframe {
    display: none;
    position: absolute;
    width: 800px;
    z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */
    top: 110px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
#autocomplete-list {
	border: 1px solid #d4d4d4;
	border-bottom: none;
	border-top: none;
	position: absolute;
	top: 87%;
	left: 65%;
	right: 30%;
	width : 10%;
	z-index: 99;
}
.autocomplete-item {
	padding: 10px;
	cursor: pointer;
	background-color: #fff;
	border-bottom: 1px solid #d4d4d4;
}
.autocomplete-item:hover {
	background-color: #e9e9e9;
}
</style>   
</head>		 
<body>

<?php
$tablename = 'phomi_estimate';
if(!$chkMobile) 
{ 	
	require_once(includePath('myheader.php')); 
}

// 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        table th, table td, h4, .form-control, span {
            font-size: 22px;
        }
         h4 {
            font-size: 40px; 
        }
		.btn-sm {
        font-size: 30px;
		}
    </style>';
} 
 
$pdo = db_connect();

// 현재 날짜
$currentDate = date("Y-m-d");

$fromdate = $_REQUEST['fromdate'] ?? '';
$todate = $_REQUEST['todate'] ?? '';

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 3개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
$SettingDate = "quote_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

// level이 20(데리점)인 경우 author_id 제한 추가
$author_limit = "";
if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
    $author_id = addslashes($_SESSION['userid']);
    $author_limit = " AND author_id = '{$author_id}' ";
    $Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') AND author_id = '{$author_id}' ";
}

// 기본 쿼리 조건
$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . $author_limit . " ORDER BY ";

$a = $common . " quote_date DESC, num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_estimate " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    

if($mode=="search"){
    if($search==""){			  
        $sql="select * from {$DB}.phomi_estimate " . $a; 										
    }
    elseif($search!="") { 
        // level 20이면 author_id 제한 추가
        if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
            $author_id = addslashes($_SESSION['userid']);
            $sql ="select * from {$DB}.phomi_estimate where ((quote_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signer like '%$search%') )  " . $Andis_deleted . " AND author_id = '{$author_id}' order by num desc  ";	
        } else {
            $sql ="select * from {$DB}.phomi_estimate where ((quote_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signer like '%$search%') )  " . $Andis_deleted . " order by num desc  ";	
        }
    }
}
if($mode=="") {
   $sql="select * from {$DB}.phomi_estimate " . $a; 						                         
}
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;
   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
?>

<form name="board_form" id="board_form"  method="post" action="list_estimate.php?mode=search">  
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >							
<div class="container-fluid">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
					<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 			
					<button type="button" class="btn btn-danger btn-sm mx-1" onclick="location.href='list.php';" title="수주 관리">
						<i class="bi bi-file-earmark-text"></i> 수주서로 이동
					</button>
					<button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button>
					<button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>					
				</div>	
				<div class="d-flex justify-content-center align-items-center"> 
					<div class="alert alert-primary p-2" role="alert">
						포미스톤 견적서 관리 시스템입니다. 견적 -> 수주 -> 출고요청서 순으로 이동합니다.
					</div>		 
				</div>					
			<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  													   
			<!-- 기간부터 검색까지 연결 묶음 start -->
			<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 		
			<div id="showframe" class="card showextractframe" style="width:500px;">
				<div class="card-header " style="padding:2px;">
					<div class="d-flex justify-content-center align-items-center">  
						기간 설정
					</div>
				</div>
				<div class="card-body ">
					<div class="d-flex justify-content-center align-items-center">  	
						<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
						<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange" onclick='pre_year()'> 전년도 </button>  
						<button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='three_month_ago()'> M-3월 </button>
						<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='prepre_month()'> 전전월 </button>	
						<button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='pre_month()'> 전월 </button> 						
						<button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange "  onclick='this_today()'> 오늘 </button>
						<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_month()'> 당월 </button>
						<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_year()'> 당해년도 </button> 
					</div>
				</div>
			</div>		
			   <input type="date" id="fromdate" name="fromdate" size="12"  class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate" size="12"   class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
			   &nbsp;&nbsp;
				   
				<?php if($chkMobile) { ?>
						</div>
					<div class="d-flex justify-content-center align-items-center">  	
				<?php } ?>	&nbsp;				
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control w-auto mx-1" > &nbsp;			
				<button class="btnClear"></button>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;												   			   
				<button type="button" id="searchBtn" class="btn btn-dark  btn-sm"> <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
				<button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	    			 
		</div>
	</div>
  </div>	
<style>
th {
    white-space: nowrap;
}
</style>		  
<div class="card mb-2">
<div class="card-body">	  	  
   <div class="table-responsive"> 	
   <table class="table table-hover " id="myTable">
    <thead class="table-primary">
      <tr>
        <th class="text-start"  style="width:5%;">번호</th>
        <th class="text-start"  style="width:100px;">견적일자</th>		
        <th class="text-center text-primary" scope="col" style="width:100px;">수신</th>		
        <th class="text-center text-success" scope="col" style="width:100px;">구분</th>		
        <th class="text-center w300px" > 현장명 </th>
        <th class="text-center w100px" > 작성자</th>
        <th class="text-center w120px" > 합계(VAT별도)</th>    
		<th class="text-center w120px" >합계(VAT포함)</th>    
      </tr>
    </thead>
    <tfoot>
      <tr class="table-info fw-bold">	
        <td colspan="6" class="text-end fw-medium">소계</td>
        <td class="text-end fw-medium" id="total-ex-vat">0</td>
        <td class="text-end fw-medium" id="total-inc-vat">0</td>
      </tr>
    </tfoot>
    <tbody>
      <?php      
			$start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
			$total_sum_ex_vat = 0; // 전체 합계(부가세별도)
			$total_sum_inc_vat = 0; // 전체 합계(부가세포함)
			
			while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$num = $row['num'];
				$quote_date = $row['quote_date'];
				$recipient = $row['recipient'];
				$division = $row['division'];
				$site_name = $row['site_name'];
				$signer = $row['signer'];
				$author = $row['author'];
				$author_id = $row['author_id'];
				$createdAt = $row['createdAt'];
				$updatedAt = $row['updatedAt'];
				
				// JSON 데이터 파싱 및 합계 계산
				$items = [];
				$other_costs = [];
				$total_supply = 0;
				$total_tax = 0;
				$other_costs_supply = 0;
				$other_costs_tax = 0;
				
							
				// 최종 합계 계산
				$total_ex_vat = $row['total_ex_vat'];
				$total_inc_vat = $row['total_inc_vat'];
				
				// 전체 합계에 추가
				$total_sum_ex_vat += $total_ex_vat;
				$total_sum_inc_vat += $total_inc_vat;
				
				$total_ex_vat = number_format($total_ex_vat);
				$total_inc_vat = number_format($total_inc_vat);
					
				echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
				?>
					<td class="text-center"><?= $start_num ?></td>
					
					<td class="text-start" data-order="<?= $quote_date ?>"> <?=$quote_date?> </td>	  
					<td class="text-start"
						data-order="<?= $recipient ?>">
						<?= $recipient ?>
					</td>  <!-- 수신 -->
					<td class="text-center"
						data-order="<?= $division ?>">
						<?= $division ?>
					</td>  <!-- 구분 -->
					<td class="text-start"> <?= $site_name ?> </td>          
					<td class="text-center text-primary"><?= $author ?></td>
					<td class="text-end"><?= $total_ex_vat ?></td>
					<td class="text-end"><?= $total_inc_vat ?></td>
					</tr>
		<?php
			$start_num--;  
			 } 
			 
			 // 전체 합계 포맷팅
			 $total_sum_ex_vat_formatted = number_format($total_sum_ex_vat);
			 $total_sum_inc_vat_formatted = number_format($total_sum_inc_vat);
			 
		  } catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
		  }   
		?>
		
		<script>
		// PHP에서 계산된 합계를 JavaScript로 전달
		var totalSumExVat = <?= $total_sum_ex_vat ?? 0 ?>;
		var totalSumIncVat = <?= $total_sum_inc_vat ?? 0 ?>;
		
		// 페이지 로드 시 소계 업데이트
		$(document).ready(function() {
			$('#total-ex-vat').text(totalSumExVat.toLocaleString());
			$('#total-inc-vat').text(totalSumIncVat.toLocaleString());
		});
		</script>
    </tbody>
  </table>
</div>

</div>   
</div>   
</div>  

</form>	 
      
<script>
var dataTable; // DataTables 인스턴스 전역 변수
var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 페이지 로딩
$(document).ready(function(){	
    var loader = document.getElementById('loadingOverlay');
	if(loader)
		loader.style.display = 'none';
});

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('requestetcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var requestetcpageNumber = dataTable.page.info().page + 1;
        setCookie('requestetcpageNumber', requestetcpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('requestetcpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('requestetcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

$(document).ready(function() {
    // Event listener for keydown on #search
    $("#search").keydown(function(event) {
        // Check if the pressed key is 'Enter'
        if (event.key === "Enter" || event.keyCode === 13) {
            // Prevent the default action to stop form submission
            event.preventDefault();
            // Trigger click event on #searchBtn
            $("#searchBtn").click();
        }
    });	
});

$(document).ready(function() { 
	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "ET_write_form.php?tablename=" + tablename ; 
		customPopup(url, '등록', 1200, 950); 		
	 });	 
	$("#searchBtn").click(function() { 
		// 페이지 번호를 1로 설정
		currentpageNumber = 1;
		setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

		// Set dateRange to '전체' and trigger the change event
		$('#dateRange').val('전체').change();
		document.getElementById('board_form').submit();
	});
}); 


function redirectToView(num) {    
    var tablename = $("#tablename").val();    	
	
    var url = "ET_write_form.php?mode=view&num=" + num         
        + "&tablename=" + tablename;   
	customPopup(url, '', 1200, 950); 			
}

function restorePageNumber() {    
    location.reload();
}

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('<?=$title_message?>'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 

</body>
</html>
