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
 
$title_message = '포미스톤 본사 예치금 및 지출 관리';      
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
/* 금액 관련 스타일 */
.income-amount {
    color: #007bff !important;
    font-weight: bold !important;
}
.expense-amount {
    color: #dc3545 !important;
    font-weight: bold !important;
}
.balance-amount {
    color: #6c757d !important;
    font-weight: bold !important;
}
.negative-balance {
    color: #dc3545 !important;
}
/* 테이블 내부 요소에 대한 추가 스타일 */
#myTable .income-amount,
#myTable td.income-amount {
    color: #007bff !important;
}

#myTable .expense-amount,
#myTable td.expense-amount {
    color: #dc3545 !important;
}

#myTable .balance-amount,
#myTable td.balance-amount {
    color: #6c757d !important;
}

#myTable .negative-balance,
#myTable td.negative-balance {
    color: #dc3545 !important;
}
</style>   
</head>		 
<body>

<?php
$tablename = 'phomi_deposit';
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
    $fromdate = date("Y-m-d", strtotime("-12 months", strtotime($currentDate))); // 12개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
$SettingDate = "deposit_date";

$Andis_deleted = " AND (is_deleted IS NULL or is_deleted='N') ";
$Whereis_deleted = " WHERE (is_deleted IS NULL or is_deleted='N') ";

$common = " WHERE " . $SettingDate . " BETWEEN '$fromdate' AND '$Transtodate' " . $Andis_deleted . " ORDER BY ";

$a = $common . " num DESC "; // 내림차순 전체

$sql="select * from ".$DB.".phomi_deposit " . $a; 	

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
if($mode=="search"){
	  if($search==""){
				$sql="select * from {$DB}.phomi_deposit " . $a; 										
			   }
		 elseif($search!="") { 			    
			  $sql ="select * from {$DB}.phomi_deposit where ((deposit_date like '%$search%')  or (note like '%$search%' ) ";
			  $sql .="or (deposit_amount like '%$search%') )  " . $Andis_deleted . " order by num desc  ";										 								
			}
	   }
if($mode=="") {
   $sql="select * from {$DB}.phomi_deposit " . $a; 						                         
}						            
$nowday=date("Y-m-d");   // 현재일자 변수지정   
$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;   
   
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();	
	
	// 지출 데이터 조회 (phomi_order 테이블에서 출고일이 있는 것들)
	// phomi_unitprice 테이블과 조인하여 공급가 단가를 가져옴
	$expense_sql = "SELECT o.delivery_date, o.items, o.other_costs, 
	                       GROUP_CONCAT(DISTINCT o.site_name SEPARATOR '; ') as site_names,
	                       GROUP_CONCAT(DISTINCT o.recipient SEPARATOR '; ') as recipients,
	                       GROUP_CONCAT(DISTINCT o.num SEPARATOR ',') as order_nums
	                FROM {$DB}.phomi_order o
	                WHERE o.delivery_date IS NOT NULL 
	                AND o.delivery_date != '0000-00-00' 
	                AND o.delivery_date BETWEEN '$fromdate' AND '$Transtodate'
	                AND (o.is_deleted IS NULL OR o.is_deleted = 'N')
	                GROUP BY o.delivery_date 
	                ORDER BY o.delivery_date DESC";
	
	$expense_stmh = $pdo->query($expense_sql);
	$expenses = [];
	$site_names = [];
	$recipients = [];
	$order_nums = [];
	while ($expense_row = $expense_stmh->fetch(PDO::FETCH_ASSOC)) {
	    $total_expense = 0;
	    
	    // items에서 공급가액과 세액 계산 (phomi_unitprice 테이블의 공급가 단가 사용)
	    if (!empty($expense_row['items'])) {
	        $items = json_decode($expense_row['items'], true);
	        if (is_array($items)) {
	            foreach ($items as $item) {
	                if (isset($item['area']) && isset($item['product_code'])) {
	                    $area = floatval(str_replace(',', '', $item['area']));
	                    $product_code = $item['product_code'];
	                    
	                    // phomi_unitprice 테이블에서 공급가 단가 조회
	                    $unit_price_sql = "SELECT price_per_m2 FROM {$DB}.phomi_unitprice WHERE prodcode = ?";
	                    $unit_price_stmh = $pdo->prepare($unit_price_sql);
	                    $unit_price_stmh->execute([$product_code]);
	                    $unit_price_row = $unit_price_stmh->fetch(PDO::FETCH_ASSOC);
	                    
						// 총판 공급가 price_per_m2 컬럼
	                    if ($unit_price_row && floatval(str_replace(',', '', $unit_price_row['price_per_m2'])) > 0) {
	                        // 공급가 단가로 계산
	                        $supply_amount = $area * $unit_price_row['price_per_m2'];
	                        $tax_amount = $supply_amount * 0.1; // 10% 부가세
	                        $total_expense += $supply_amount + $tax_amount;
							// echo '<br> 품목코드 : ' . $product_code . ' 총판공급가 : ' . $unit_price_row['price_per_m2'] . ' 면적: ' . $area . ' 공급가: ' . $supply_amount . ' 세액: ' . $tax_amount . ' 총계: ' . $total_expense . '<br>';
	                    } else {
	                        // 공급가 단가가 없으면 기존 단가 사용 (fallback)
	                        if (isset($item['unit_price'])) {
	                            $unit_price = floatval(str_replace(',', '', $item['unit_price']));
	                            $supply_amount = $area * $unit_price;
	                            $tax_amount = $supply_amount * 0.1; // 10% 부가세
	                            $total_expense += $supply_amount + $tax_amount;								
	                        }
	                    }
	                }
	            }
	        }
			// echo '<br>';
			// echo print_r($expense_row, true);
			// echo '<br>';
	    }


	    
	    // other_costs는 기존대로 계산 (공급가 단가가 별도로 저장되어 있지 않음)
	    if (!empty($expense_row['other_costs'])) {
	        $other_costs = json_decode($expense_row['other_costs'], true);
	        if (is_array($other_costs)) {
	            foreach ($other_costs as $cost) {
	                if (isset($cost['quantity']) && isset($cost['unit_price'])) {
	                    $quantity = floatval($cost['quantity']);
	                    $unit_price = floatval(str_replace(',', '', $cost['unit_price']));
	                    
	                    // 본드 항목에 대해 본사 협약가격 5000원 적용
	                    if (isset($cost['item']) && strpos($cost['item'], '본드') !== false) {
	                        $unit_price = 5000; // 본사 협약가격
	                    }
	                    
	                    $supply_amount = $quantity * $unit_price;
	                    $tax_amount = $supply_amount * 0.1; // 10% 부가세
	                    $total_expense += $supply_amount + $tax_amount;
	                }
	            }
	        }
	    }
	    
	    $expenses[$expense_row['delivery_date']] = $total_expense;
	    $site_names[$expense_row['delivery_date']] = $expense_row['site_names'];
	    $recipients[$expense_row['delivery_date']] = $expense_row['recipients'];
	    $order_nums[$expense_row['delivery_date']] = $expense_row['order_nums'];
	}
	
	// 입금 데이터 조회
	$deposits = [];
	$deposit_nums = []; // 입금 번호 저장용 배열
	while ($deposit_row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	    $date = $deposit_row['deposit_date'];
	    if (!isset($deposits[$date])) {
	        $deposits[$date] = 0;
	        $deposit_nums[$date] = [];
	    }
	    $deposits[$date] += $deposit_row['deposit_amount'];
	    $deposit_nums[$date][] = $deposit_row['num']; // 각 날짜별 입금 번호 저장
	}
	
	// 모든 날짜를 합쳐서 정렬
	$all_dates = array_unique(array_merge(array_keys($deposits), array_keys($expenses)));
	rsort($all_dates); // 최신 날짜부터 정렬
	
	// 누적 잔액 계산 - 시작 잔액을 0으로 설정
	$running_balance = 0;
	$balance_data = [];
	
	// 날짜순으로 정렬 (과거부터 현재까지)
	sort($all_dates);
	
	foreach ($all_dates as $date) {
	    $income = $deposits[$date] ?? 0;
	    $expense = $expenses[$date] ?? 0;
	    
	    // 입금액에서 지출액을 뺀 금액을 누적 잔액에 반영
	    $running_balance += $income - $expense;
	    
	    $balance_data[] = [
	        'date' => $date,
	        'income' => $income,
	        'expense' => $expense,
	        'balance' => $running_balance,
	        'site_names' => $site_names[$date] ?? '',
	        'recipients' => $recipients[$date] ?? '',
	        'order_nums' => $order_nums[$date] ?? '',
	        'deposit_nums' => $deposit_nums[$date] ?? []
	    ];
	}
	
	// 최종 결과를 최신 날짜부터 표시하기 위해 역순으로 정렬
	$balance_data = array_reverse($balance_data);
	
	// 입금 데이터를 다시 조회 (테이블 표시용)
	$stmh = $pdo->query($sql);
?>

<form name="board_form" id="board_form"  method="post" action="list_deposit.php?mode=search">  
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >							
<div class="container">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
					<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 			
				</div>	
				<div class="d-flex justify-content-center align-items-center"> 
					<div class="alert alert-primary p-2" role="alert">
						포미스톤 본사 예치금 및 지출 관리 시스템입니다. 총판 공급가 기준으로 예치금이 차감됩니다. 지출액은 VAT포함 금액입니다.
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
         <th class="text-start"  style="width:100px;">날짜</th>		
         <th class="text-end income-amount" scope="col" style="width:150px;">입금액</th>		
         <th class="text-end expense-amount" scope="col" style="width:150px;">지출액(VAT포함)</th>		
         <th class="text-end balance-amount" scope="col" style="width:150px;">잔액</th>		
         <th class="text-start" style="width:300px;">현장명</th>
         <th class="text-start" style="width:300px;">수신처</th>
         <th class="text-start w300px" > 비고 </th>
       </tr>
     </thead>	
    <tbody>
      <?php      
			$start_num = count($balance_data); // 페이지당 표시되는 첫번째 글순번      
			foreach ($balance_data as $index => $data) {
				$date = $data['date'];
				$income = $data['income'];
				$expense = $data['expense'];
				$balance = $data['balance'];
				$site_names = $data['site_names'];
				$recipients = $data['recipients'];
				$order_nums = $data['order_nums'];
				$deposit_nums = $data['deposit_nums'];
				
				// 금액 포맷팅
				$income_formatted = $income > 0 ? number_format($income) : '-';
				$expense_formatted = $expense > 0 ? number_format($expense) : '-';
				$balance_formatted = number_format($balance);
				
				// 비고 생성
				$note = '';
				if ($income > 0 && $expense > 0) {
					$note = '입금 및 지출';
				} elseif ($income > 0) {
					$note = '입금';
				} elseif ($expense > 0) {
					$note = '지출';
				}
				
				// 잔액이 음수인 경우 스타일 적용
				$balance_class = $balance < 0 ? 'negative-balance' : 'balance-amount';
				
				// 입금/지출 여부에 따라 다른 클릭 이벤트 설정
				$click_data = '';
				if ($income > 0 && $expense > 0) {
					// 입금 및 지출이 모두 있는 경우 - 입금 처리 우선
					$click_data = 'data-type="income" data-date="'.$date.'" data-deposit-nums="'.implode(',', $deposit_nums).'"';
				} elseif ($income > 0) {
					// 입금만 있는 경우
					$click_data = 'data-type="income" data-date="'.$date.'" data-deposit-nums="'.implode(',', $deposit_nums).'"';
				} elseif ($expense > 0) {
					// 지출만 있는 경우 - 수주 번호 조회 필요
					$click_data = 'data-type="expense" data-date="'.$date.'" data-order-nums="'.$order_nums.'"';
				}
				
				echo '<tr style="cursor:pointer;" '.$click_data.'>';
				?>
					<td class="text-center"><?= $start_num ?></td>
					<td class="text-start" data-order="<?= $date ?>"> <?=$date?> </td>	  
					<td class="text-end income-amount" data-order="<?= $income ?>">
						<?= $income_formatted ?>
					</td>  <!-- 입금액 -->
					<td class="text-end expense-amount" data-order="<?= $expense ?>">
						<?= $expense_formatted ?>
					</td>  <!-- 지출액 -->
					<td class="text-end <?= $balance_class ?>" data-order="<?= $balance ?>">
						<?= $balance_formatted ?>
					</td>  <!-- 잔액 -->
					<td class="text-start"> <?= $site_names ? $site_names : '-' ?> </td>  <!-- 현장명 -->
					<td class="text-start"> <?= $recipients ? $recipients : '-' ?> </td>  <!-- 수신처 -->
					<td class="text-start"> <?= $note ?> </td>          
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
        "pageLength": 200,
        "lengthMenu": [200, 500, 1000, 2000],
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
		var url = "write_form_deposit.php?tablename=" + tablename ; 
		customPopup(url, '등록', 800, 600); 		
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
	
    var url = "write_form_deposit.php?mode=view&num=" + num         
        + "&tablename=" + tablename;   
	customPopup(url, '', 800, 600); 			
}

function restorePageNumber() {    
    location.reload();
}

// 테이블 행 클릭 이벤트 처리
$(document).ready(function(){
	$('#myTable tbody').on('click', 'tr', function() {
		var type = $(this).data('type');
		var date = $(this).data('date');
		var orderNums = $(this).data('order-nums');
		var depositNums = $(this).data('deposit-nums');
		
		console.log('Click event - type:', type, 'date:', date, 'orderNums:', orderNums, 'depositNums:', depositNums);
		
		if (type === 'income') {
			// 입금인 경우 - 입금 번호를 사용하여 상세보기 또는 수정 팝업
			if (depositNums && depositNums !== '') {
				// 첫 번째 입금 번호 사용 (여러 개가 있을 경우)
				var firstDepositNum = depositNums.toString().split(',')[0];
				if (firstDepositNum && firstDepositNum !== '') {
					console.log('Opening deposit with num:', firstDepositNum);
					var tablename = $("#tablename").val();
					var url = "write_form_deposit.php?mode=view&num=" + firstDepositNum + "&tablename=" + tablename;
					customPopup(url, '입금 상세보기', 800, 600);
				} else {
					console.log('Invalid deposit number:', firstDepositNum);
				}
			} else {
				console.log('No deposit numbers found for date:', date);
			}
		} else if (type === 'expense') {
			// 지출인 경우 - 수주 상세보기 팝업 (num 고유키 사용)
			if (orderNums && orderNums !== '') {
				// 첫 번째 수주 번호 사용 (여러 개가 있을 경우)
				var firstOrderNum = orderNums.toString().split(',')[0];
				if (firstOrderNum && firstOrderNum !== '') {
					console.log('Opening order with num:', firstOrderNum);
					var url = "write_form.php?mode=view&num=" + firstOrderNum;
					customPopup(url, '수주 상세보기', 1200, 800);
				} else {
					console.log('Invalid order number:', firstOrderNum);
				}
			} else {
				console.log('No order numbers found for date:', date);
			}
		}
	});
});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('<?=$title_message?>'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 

</body>
</html>
