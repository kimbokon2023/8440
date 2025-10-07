<?php\nrequire_once __DIR__ . '/../common/functions.php';
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
 
$title_message = '포미스톤 출고 요청서';      
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
$tablename = 'phomi_outorder';
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
			  
$SettingDate = "out_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . " ORDER BY ";

$a = $common . " num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_outorder " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
if($mode=="search"){
	  if($search==""){
				$sql="select * from {$DB}.phomi_outorder " . $a; 										
			   }
		 elseif($search!="") { 			    
			  $sql ="select * from {$DB}.phomi_outorder where ((out_date like '%$search%')  or (customer like '%$search%' ) ";
			  $sql .="or (manager like '%$search%') or (address like '%$search%') or (contact like '%$search%') )  " . $Andis_deleted . " order by num desc  ";										 								
			}
	   }
if($mode=="") {
   $sql="select * from {$DB}.phomi_outorder " . $a; 						                         
}						            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
?>

<form name="board_form" id="board_form"  method="post" action="list_outorder.php?mode=search">  
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
					<button type="button" class="btn btn-primary btn-sm mx-1" onclick="location.href='list_estimate.php';" title="견적 관리">
						<i class="bi bi-file-earmark-text"></i> 견적서로 이동
					</button>
					<!-- <button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button> -->
					<button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>						
				</div>	
				<div class="d-flex justify-content-center align-items-center"> 
					<div class="mx-2" style="width: 100%; max-width: 650px;">
						<div class="card shadow border-0" style="background: linear-gradient(90deg, #ffdde1 0%, #ee9ca7 100%);">
							<div class="card-body p-3">
								<div class="d-flex align-items-center mb-2">
									<i class="bi bi-exclamation-triangle-fill text-danger fs-3 me-2"></i>
									<h5 class="mb-0 fw-bold text-danger">출고요청 안내</h5>
								</div>
								<ul class="mb-2 ps-4" style="font-size:1.05em;">
									<li>출고요청서는 <span class="fw-bold text-primary">수주리스트</span>에서 생성합니다.</li>
									<li>출고일 <span class="fw-bold text-danger">1~2일 전</span>에는 요청서 전달을 꼭 부탁드립니다.</li>
									<li>출고일자를 지정하고 저장하면, <span class="fw-bold text-success">수주리스트에 자동 적용</span>됩니다.</li>
									<li>요청서 전달 시 <span class="fw-bold text-primary">제품명, 규격</span>을 반드시 확인해 주세요.</li>
									<li>
										<span class="fw-bold text-dark">진행 순서:</span>
										<span class="badge bg-secondary mx-1">견적</span>
										<i class="bi bi-arrow-right"></i>
										<span class="badge bg-primary mx-1">수주</span>
										<i class="bi bi-arrow-right"></i>
										<span class="badge bg-success mx-1">출고요청서</span>
									</li>
								</ul>
								<div class="mt-2 p-2 rounded" style="background:rgba(255,255,255,0.7);">
									<i class="bi bi-info-circle text-info"></i>
									<span class="fw-bold">Code 안내:</span>
									<span class="badge bg-dark mx-1">A-</span>1200×2400
									<span class="badge bg-dark mx-1">B-</span>1200×2700(2800)
									<span class="badge bg-dark mx-1">C-</span>1200×3000
									<span class="badge bg-dark mx-1">Z-</span>1200×600
								</div>
							</div>
						</div>
					</div>
					<div class="card shadow-sm border-success mb-2" style="max-width: 600px;">
						<div class="card-header bg-success text-white d-flex align-items-center">
							<i class="bi bi-geo-alt-fill me-2"></i>
							<strong>창고 정보</strong>
						</div>
						<div class="card-body bg-light">
							<p class="mb-1">
								<i class="bi bi-geo-alt text-success"></i>
								<strong>주소:</strong> 경기도 군포시 번영로 82-27 N동(동관) 7층
							</p>
							<p class="mb-1">
								<i class="bi bi-person-badge text-primary"></i>
								<strong>출고담당자:</strong> 권대홍 대리
							</p>
							<p class="mb-0">
								<i class="bi bi-telephone text-info"></i>
								<strong>연락처:</strong> 010-4277-0858
							</p>
						</div>
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
				<?php } ?>&nbsp;				
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control w-auto mx-1" > &nbsp;			
				<button class="btnClear"></button>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;												   			   
				<button type="button" id="searchBtn" class="btn btn-dark  btn-sm"> <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
				<!-- <button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	    			  -->
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
        <th class="text-start"  style="width:100px;">출고일자</th>		
        <th class="text-center text-primary" scope="col" style="width:100px;">발주처</th>		
        <th class="text-center text-success" scope="col" style="width:100px;">담당자</th>		
        <th class="text-center w300px" > 주소 </th>
        <th class="text-center w100px" > 받는 분 Tel</th>
        <th class="text-center w120px" > 배차</th>    
		<th class="text-center w120px" > 시공면적(㎡)</th>    
		<th class="text-center w120px" > 시공</th>    
		<th class="text-center w300px" > 비고</th>    
      </tr>
    </thead>	
    <tbody>
      <?php      
			$start_num = $total_row; // 페이지당 표시되는 첫번째 글순번      
			while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$num = $row['num'];
				$out_date = $row['out_date'];
				$customer = $row['customer'];
				$manager = $row['manager'];
				$address = $row['address'];
				$contact = $row['recipient_phone']; // 받는 분 Tel
				$dispatch_type = $row['dispatch_type'];
				$area_sqm = $row['area_sqm'];
				$construction_done = $row['construction_done'];
				$createdAt = $row['creatAt'];
				$updatedAt = $row['updateAt'];
				$note = $row['note'];

				
				// JSON 데이터 파싱
				$items = [];
				$total_quantity = 0;
				
				// 상품 데이터 파싱
				if(!empty($row['items'])) {
					$items = json_decode($row['items'], true) ?? [];
					foreach($items as $item) {
						$total_quantity += $item['quantity'] ?? 0;
					}
				}
				
				// 주소 길이 제한 (모바일 대응)
				$display_address = $address;
				// if (strlen($display_address) > 30) {
				// 	$display_address = substr($display_address, 0, 30) . '...';
				// }
					
				echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
				?>
					<td class="text-center"><?= $start_num ?></td>
					
					<td class="text-start" data-order="<?= $out_date ?>"> <?=$out_date?> </td>	  
					<td class="text-start"
						data-order="<?= $customer ?>">
						<?= $customer ?>
					</td>  <!-- 발주처 -->
					<td class="text-center"
						data-order="<?= $manager ?>">
						<?= $manager ?>
					</td>  <!-- 담당자 -->
					<td class="text-start" title="<?= $address ?>"> <?= $display_address ?> </td>          
					<td class="text-start text-primary"><?= $contact ?></td>
					<td class="text-center"><?= $dispatch_type ?></td>
					<td class="text-center"><?= $area_sqm ?></td>
					<td class="text-center"><?= $construction_done ?></td>
					<td class="text-start"><?= $note ?></td>
					</tr>
		<?php
			$start_num--;  
			 } 
		  } catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
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
		var url = "OR_write_form.php?tablename=" + tablename ; 
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
	
    var url = "OR_write_form.php?mode=view&num=" + num         
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
