<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';	

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
 
$title_message = '포미스톤 수주';      
$mode = $_REQUEST["mode"] ?? '';
$search = $_REQUEST["search"] ?? ''; 
?> 
 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; ?> 
 
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
$tablename = 'phomi_order';
 if(!$chkMobile) 
{ 	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); 
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
			  
$SettingDate = "order_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

// level이 20(데리점)인 경우 author_id 제한 추가
$author_limit = "";
if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
    $author_id = addslashes($_SESSION['userid']);
    $author_limit = " AND author_id = '{$author_id}' ";
    $Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') AND author_id = '{$author_id}' ";
}

$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . $author_limit . " ORDER BY ";

$a = $common . " num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_order " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
if($mode=="search"){
    if($search==""){
        $sql="select * from {$DB}.phomi_order " . $a; 										
    }
    elseif($search!="") { 
        // level 20이면 author_id 제한 추가
        if (isset($_SESSION['level']) && $_SESSION['level'] == 20 && isset($_SESSION['userid'])) {
            $author_id = addslashes($_SESSION['userid']);
            $sql ="select * from {$DB}.phomi_order where ((order_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signed_by like '%$search%') or (author like '%$search%') or (author_id like '%$search%') )  " . $Andis_deleted . " AND author_id = '{$author_id}' order by num desc  ";	
        } else {
            $sql ="select * from {$DB}.phomi_order where ((order_date like '%$search%')  or (recipient like '%$search%' ) ";
            $sql .="or (division like '%$search%') or (site_name like '%$search%') or (signed_by like '%$search%') or (author like '%$search%') or (author_id like '%$search%') )  " . $Andis_deleted . " order by num desc  ";	
        }
    }
}
if($mode=="") {
   $sql="select * from {$DB}.phomi_order " . $a; 						                         
}						            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;   
   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
?>

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search">  
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >							
<div class="container-fluid">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
					<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 			
					<button type="button" class="btn btn-primary btn-sm mx-1" onclick="location.href='list_estimate.php';" title="견적 관리">
						<i class="bi bi-file-earmark-text"></i> 견적서로 이동
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
						포미스톤 수주 관리 시스템입니다. 견적 -> 수주 -> 출고요청서 순으로 이동합니다.
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
        <th class="text-start"  style="width:100px;">수주일자</th>		
        <th class="text-start"  style="width:50px;">견적No</th>		
        <th class="text-center text-primary" scope="col" style="width:150px;">수신</th>		
        <th class="text-center text-success" scope="col" style="width:50px;">구분</th>		
        <th class="text-center w200px" > 현장명 </th>
        <th class="text-center w100px" > 작성자</th>
        <th class="text-center w120px" > 출고예정일</th>    
		<th class="text-center w120px" > 실제출고일</th>    
		<th class="text-end w120px" > 업체매출금액</th>    
        <th class="text-end w120px" > 합계(VAT별도)</th>    
		<th class="text-end w120px" >합계(VAT포함)</th>   		
		<th class="text-end w120px" >세금계산서 금액</th>   		
		<th class="text-center w120px" >입금 여부</th>   		
      </tr>
    </thead>	
    <tfoot>
      <tr class="table-info fw-bold">	
        <td colspan="9" class="text-end fw-medium">소계</td>
        <td class="text-end fw-bold" id="total-company-amount">0</td>
        <td class="text-end fw-bold" id="total-ex-vat">0</td>
        <td class="text-end fw-bold" id="total-inc-vat">0</td>
        <td class="text-end fw-bold" id="total-tax-invoice-amount">0</td>
        <td></td>
      </tr>
    </tfoot>
    <tbody>
      <?php      
			$start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
			$total_company_amount = 0; // 전체 업체매출금액 합계
			$total_sum_ex_vat = 0; // 전체 합계(부가세별도)
			$total_sum_inc_vat = 0; // 전체 합계(부가세포함)
			
			while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$num = $row['num'];
				$order_date = $row['order_date'];
				$recipient = $row['recipient'];
				$division = $row['division'];
				$site_name = $row['site_name'];
				$signed_by = $row['signed_by'];
				$delivery_due_date = $row['delivery_due_date'];
				$delivery_date = $row['delivery_date'];
				$company_amount = $row['company_amount'];
				$company_amount_ex_vat = $row['company_amount_ex_vat'];
				$company_amount_inc_vat = $row['company_amount_inc_vat'];
				$createdAt = $row['createdAt'];
				$updatedAt = $row['updatedAt'];
				$total_inc_vat = $row['total_inc_vat'];
				$total_ex_vat = $row['total_ex_vat'];
				$tax_invoice_amount = $row['tax_invoice_amount'];
				$deposit_status = $row['deposit_status'];
				$author = $row['author'];
				$author_id = $row['author_id'];
				$estimate_num = $row['estimate_num'];

				// 전체 합계에 추가
				$total_company_amount += $company_amount;
				$total_sum_ex_vat += $total_ex_vat;
				$total_sum_inc_vat += $total_inc_vat;
				
				// 금액 포맷팅
				$company_amount_formatted = $company_amount ? number_format($company_amount) : '-';
				$company_amount_ex_vat_formatted = $company_amount_ex_vat ? number_format($company_amount_ex_vat) : '-';
				$company_amount_inc_vat_formatted = $company_amount_inc_vat ? number_format($company_amount_inc_vat) : '-';
				$total_inc_vat_formatted = $total_inc_vat ? number_format($total_inc_vat) : '-';
				$total_ex_vat_formatted = $total_ex_vat ? number_format($total_ex_vat) : '-';
				
				// 날짜 포맷팅
				$delivery_due_date_formatted = $delivery_due_date ? $delivery_due_date : '-';
				$delivery_date_formatted = $delivery_date ? $delivery_date : '-';
					
				echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
				?>
					<td class="text-center"><?= $start_num ?></td>
					
					<td class="text-start" data-order="<?= $order_date ?>"> <?=$order_date?> </td>	  
					<td class="text-end" data-order="<?= $estimate_num ?>"> <?=$estimate_num?> </td>	  
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
					<td class="text-center"><?= $delivery_due_date_formatted == '0000-00-00' ? '' : $delivery_due_date_formatted ?></td>
					<td class="text-center"><?= $delivery_date_formatted == '0000-00-00' ? '' : $delivery_date_formatted ?></td>
					<td class="text-end"><?= $company_amount_formatted ?></td>
					<td class="text-end"><?= $total_ex_vat_formatted ?></td>
					<td class="text-end"><?= $total_inc_vat_formatted ?></td>
					<td class="text-end"><?= $tax_invoice_amount_formatted ?></td>
					<td class="text-center"><?= $deposit_status ?></td>
					</tr>
		<?php
			$start_num--;  
			 } 
			 
			 // 전체 합계 포맷팅
			 $total_company_amount_formatted = number_format($total_company_amount);
			 $total_sum_ex_vat_formatted = number_format($total_sum_ex_vat);
			 $total_sum_inc_vat_formatted = number_format($total_sum_inc_vat);
			 $total_tax_invoice_amount_formatted = number_format($total_tax_invoice_amount);
			 
		  } catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
		  }   
		?>
		
		<script>
		// PHP에서 계산된 합계를 JavaScript로 전달
		var totalCompanyAmount = <?= $total_company_amount ?? 0 ?>;
		var totalSumExVat = <?= $total_sum_ex_vat ?? 0 ?>;
		var totalSumIncVat = <?= $total_sum_inc_vat ?? 0 ?>;
		var totalTaxInvoiceAmount = <?= $total_tax_invoice_amount ?? 0 ?>;
		
		// 페이지 로드 시 소계 업데이트
		$(document).ready(function() {
			$('#total-company-amount').text(totalCompanyAmount.toLocaleString());
			$('#total-ex-vat').text(totalSumExVat.toLocaleString());
			$('#total-inc-vat').text(totalSumIncVat.toLocaleString());
			$('#total-tax-invoice-amount').text(totalTaxInvoiceAmount.toLocaleString());
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
		var url = "write_form.php?tablename=" + tablename ; 
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
	
    var url = "write_form.php?mode=view&num=" + num         
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
