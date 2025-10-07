<?php
require_once(includePath('session.php')); 
$title_message = '2년간 원자재 단가 추이';
?> 
<?php include getDocumentRoot() . '/load_header.php' ?> 
<title> <?=$title_message?>  </title>  
</head> 
 <body>
 <?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$firstItem = $_REQUEST['firstItem'] ?? '';
$scale = $_REQUEST['scale'] ?? 20;
$page = $_REQUEST['page'] ?? 1;
							                	
include "_request.php";
 
$find=$_REQUEST["find"] ?? '전체';
$search=$_REQUEST["search"] ?? '';
$Bigsearch=$_REQUEST["Bigsearch"] ?? '304 HL';
$mode=$_REQUEST["mode"] ?? 'search';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

$default_condition = false;

// 기본 검색 조건 설정
if ($Bigsearch == '304 HL') {
    $find = '공급처';
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '현진스텐';
    $default_condition = true;
}
if ($Bigsearch == '201 2B MR') {
    $find = '공급처';
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '윤스틸';
    $default_condition = true;
}
if ($Bigsearch == '201 HL' || $Bigsearch == '201 VB' ) {
    $find = '공급처';
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '우성스틸';
    $default_condition = true;
}
if ($Bigsearch == '201 MR BEAD' || $Bigsearch == '2B VB'  || $Bigsearch == 'VB' ) {
    $find = '공급처';
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '한산엘테크';
    $default_condition = true;
}
$page_scale = 15;   // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

// 오늘 날짜 구하기
$today = date("Y-m-d");

// fromdate가 없을 경우: 2년 전 날짜로 설정
if ($fromdate === "") {
    $fromdate = date("Y-m-d", strtotime("-2 years"));
}

// todate가 없을 경우: 오늘 날짜로 설정
if ($todate === "") {
    $todate = $today;
}

// Transtodate는 todate의 다음날로 설정
$Transtodate = date("Y-m-d", strtotime($todate . ' +1 day'));

// 입출고일 기준
$SettingDate="outdate ";    

$common = $SettingDate . " between date('$fromdate') and date('$Transtodate') order by num desc ";

$Andis_deleted =  " AND (is_deleted IS NULL or is_deleted ='' ) AND eworks_item='원자재구매' AND " . $common ;
$Whereis_deleted =  " Where  (is_deleted IS NULL or is_deleted ='')  AND eworks_item='원자재구매' AND " . $common ; 
  
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum= array();
$yday_sum=array();
$ydaysaved_sum=array();

// 상세내역 전달을 위한 것
$title_arr=array(); 
$titleurl_arr=array(); 

$tablename='eworks';
 
// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search); 
 
if ($mode == "search") {
    if ($Bigsearch == '') {
        $sql = "SELECT * FROM {$DB}.{$tablename} " . $Whereis_deleted;
    } else {
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE (steel_item = '$Bigsearch') " ;

        // 특정 철판 종류는 규격 + 공급처 + 날짜조건 + 검색어 필터까지 적용
        if ($default_condition && $find == '공급처') {
            $sql .= " AND (" . $SettingDate . " BETWEEN date('$fromdate') AND date('$Transtodate')) 
                      AND ((outdate LIKE '%$search%') 
                        OR (REPLACE(outworkplace,' ','') LIKE '%$search%') 
                        OR (steel_item LIKE '%$search%') 
                        OR (company LIKE '%$search%')) 
                      AND (supplier LIKE '%$searchsupplier%') 
                      AND (spec LIKE '%$searchspec%') " . $Andis_deleted;
        } else {
            // 일반적인 조건: is_deleted, eworks_item, 날짜 조건 등
            $sql .= " " . $Andis_deleted;
        }
    }
}

if ($Bigsearch === '') {
    $Bigsearch = ' ';
}
  
$nowday=date("Y-m-d");   // 현재일자 변수지정   

$BigsearchTag = str_replace(' ','|',$Bigsearch);

$steel_items = [];

try {
    $sql_items = "SELECT DISTINCT steel_item 
                  FROM {$DB}.{$tablename} 
                  WHERE (is_deleted IS NULL OR is_deleted = '') 
                  AND eworks_item = '원자재구매' 
                  ORDER BY steel_item ASC";

    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->execute();

    while ($row = $stmt_items->fetch(PDO::FETCH_ASSOC)) {
        $steel_items[] = trim($row['steel_item']);
    }
} catch (PDOException $e) {
    error_log("steel_item 목록 조회 오류: " . $e->getMessage());
}

$item_arr = array();			
	try {
		$current_month = date('Y-m');
		$start_month = date('Y-m', strtotime('-23 months'));

		$data = []; // 연관 배열을 저장할 변수
		$stmt = $pdo->query($sql);            // 검색조건에 맞는글 stmh

		for ($i = 23; $i >= 0; $i--) {
			array_push($item_arr,' ');
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				include getDocumentRoot() . '/request/_row.php';				  
				
				array_push($item_arr,$steel_item);
				
				$temp_arr = explode('*', $spec);
				$saved_weight = ($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000;
				$saved_weight = sprintf('%0.1f', $saved_weight);
				$number = (int)str_replace(',', '', $suppliercost);
				$unit_weight = $number > 0 ? floor($number / $saved_weight) : 0;

				$month = substr($outdate, 0, 7); // 월을 추출합니다.
				
				// print $month;

				if (!isset($data[$steel_item])) {
					$data[$steel_item] = []; // 아이템에 대한 배열을 초기화합니다.
				}

				$data[$steel_item][$month] = $unit_weight; // 아이템의 해당 월에 단가를 저장합니다.
			}
		}
	} catch (PDOException $Exception) {
		error_log("오류: " . $Exception->getMessage());
	}	
				
		 // 배열에서 중복 값을 제거하고 빈 문자열을 필터링합니다.
		$item_arr = array_unique($item_arr);
		$item_arr = array_filter($item_arr, function($value) {
			return trim($value) !== '';
		});

	// 배열을 오름차순으로 정렬합니다.
	sort($item_arr);

	// 배열 인덱스를 리셋합니다.
	$item_arr = array_values($item_arr);
	
// print $sql;
	
// echo '<pre>';
// print_r($data);
// echo '</pre>';	
		
?>
 <form name="board_form" id="board_form"  method="post" action="list.php?mode=search">     		
	<input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>" size="5" > 						
	<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
    
 <div class="container-fluid"> 						
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-3 text-center" style="display:none"> 
		</div>     
	</div>	
	
	<div class="row justify-content-center align-items-center"> 
	<div class="card" > 
		<div class="card-body" > 		
		<div class="d-flex mt-4 mb-2 align-items-center justify-content-center">         
			<span class="text-center fs-5"> <?=$title_message?> </span>       
			<button type="button" class="btn btn-dark btn-sm mx-2"  onclick='location.reload();' > <i class="bi bi-arrow-clockwise"></i> </button>  	   		  								
		</div>  		
		<div class="alert alert-primary d-flex mt-4 mb-2 align-items-center justify-content-center" role="alert">       
			  304 HL 등 기본소재는 주거래처인 '현진스텐' 1219 * 2438 기준의 단가로 추정함
		</div>        
		<div class="d-flex mt-2 mb-3 align-items-center justify-content-center">         
			<i class="bi bi-caret-right"></i>  <span id="total_row" > </span>  건  	
				&nbsp; 	&nbsp; 	
					<!-- 기간설정 칸 -->
					 <?php include getDocumentRoot() . '/setdate.php' ?>			
					 
		</div>		
	
		<div class="d-flex justify-content-center align-items-center"> 	 	 
    
     	<button type="button" class="btn btn-dark  btn-sm me-2" id="writeBtn"> <ion-icon name="pencil-outline"></ion-icon> 등록  </button> 			     
	     
		 <button  type="button" id="calamountBtn"  class="btn btn-danger btn-sm" > 원자재 금액 산출 </button> &nbsp;
		 <button  type="button" id="rawmaterialBtn"  class="btn btn-outline-dark btn-sm" > 원자재 현황 </button> &nbsp;
		
		&nbsp;&nbsp;   
			<style>
			#Bigsearch {
				width: 220px;
			}
				#search {
				width: 150px;
			}
		</style>

		<select name="Bigsearch" id="Bigsearch" class="form-select d-block w-auto" style="font-size:0.8rem; height:32px;">
		<?php
			foreach ($steel_items as $item) {
				$selected = ($Bigsearch == $item) ? "selected" : "";
				echo "<option value='" . htmlspecialchars($item) . "' $selected>" . htmlspecialchars($item) . "</option>";
			}
		?>
		</select>
	   &nbsp;
</div>
</div>
</div>			 
</div>			 
 
 <div class="row">
 <div class="d-flex mt-2 mb-1 p-1 m-2 justify-content-center align-items-center" >
 <div class="col-sm-3">
<?php
echo '<div class="card">';
echo '<div class="card-body">';
echo '<table class="table table-hover table-border">';
echo '<thead class="table-primary">';
echo '<tr>';
echo '<th class="text-center">철판종류</th>';
echo '<th class="text-center">해당월</th>';
echo '<th class="text-center">Kg당 단가 (원)</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$item_name = $Bigsearch ;
if (isset($data[$item_name])) {
    foreach ($data[$item_name] as $month => $unit_weight) {
		if($unit_weight > 0) 
		{
        echo "<tr>";
        echo "<td class='text-center'>" . htmlspecialchars($item_name) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($month) . "</td>";
        echo "<td class='text-center text-success'>" . htmlspecialchars(number_format($unit_weight)) . "</td>";
        echo "</tr>";
		}
    }
} else {
    echo "<tr>";
    echo "<td colspan='3' class='text-center'>해당 아이템의 데이터가 없습니다.</td>";
    echo "</tr>";
}

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</div>';
echo '</div>';

?>  
<div class="col-sm-7">
<div class="card">
<div class="card-body">

	<!-- HTML Body의 적절한 위치에 차트를 그릴 canvas 요소를 추가합니다. -->
	<canvas id="myChart" width="800" height="300"></canvas>
	
</div>
</div>
</div>
</div>
</div>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var costpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('costpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var costpageNumber = dataTable.page.info().page + 1;
        setCookie('costpageNumber', costpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('costpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('costpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

// PHP로부터 데이터를 가져와서 JavaScript 변수에 할당합니다.
var Bigsearch = '<?php echo $Bigsearch; ?>';

var data = <?php 
    $item_name = $Bigsearch; 
    if (isset($data[$item_name])) {
        echo json_encode(array_reverse($data[$item_name], true)); // JSON 형식으로 변환
    } else {
        echo json_encode([]);
    }
?>;

// labels와 datasets 변수를 초기화합니다.
var labels = [];
var datasets = [];

// data 객체를 순회하며 labels와 datasets을 채웁니다.
for (var month in data) {
    if (data.hasOwnProperty(month) && data[month] > 0) {
        labels.push(month); // 월을 labels 배열에 추가
        datasets.push(data[month]); // 단가를 datasets 배열에 추가
    }
}

// Chart.js를 사용하여 차트를 생성합니다.
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line', // 라인 차트를 생성
    data: {
        labels: labels, // x축 레이블로 월을 설정
        datasets: [{
            label: Bigsearch +' Kg당 단가 (원)',
            data: datasets, // y축 데이터로 단가를 설정
            borderColor: 'rgba(75, 192, 192, 1)', // 선 색상
            borderWidth: 2 // 선 두께
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

  <div class="row mt-3 mb-1 p-1 m-2" >
      <table class="table table-hover table-border" id="myTable">
	   <thead class="table-primary">
	   <tr>
            <th class="text-center" >번호    </th>
            <th class="text-center" >입고일자  </th>            
            <th class="text-center" >현장명   </th>
            <th class="text-center" >모델    </th>
			<th class="text-center" >공급업체  </th>            
            <th class="text-center" >철판종류  </th>
            <th class="text-center" >규격     </th>
            <th class="text-center">구매수량    </th>
            <th class="text-center text-secondary"> 중량(kg)</th>
            <th class="text-end text-success">   Kg당 단가(원) </th>
            <th class="text-end text-danger" >   공급가액(원)</th>            
            <th class="text-center" >비고</th>
		  </tr>
		</thead>
	  <tbody>
<?php	   
try{
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount(); 
	  
		  $start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번		  
		  
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

             	include getDocumentRoot() . '/request/_row.php';				  
			  	
		    	$temp_arr = explode("*", $spec);
			
                $saved_weight = 0.0;					
				$val1 = (float) preg_replace('/[^0-9\.]/', '', $temp_arr[0]);
				$val2 = (float) preg_replace('/[^0-9\.]/', '', $temp_arr[1]);
				$val3 = (float) preg_replace('/[^0-9\.]/', '', $temp_arr[2]);

				$saved_weight += ($val1 * $val2 * $val3 * 7.93 * (int)$steelnum) / 1000000;
				
			    $saved_weight = sprintf('%0.1f', $saved_weight);  // 소수점 한자리 표현
				
				$number = (int)str_replace(',', '', $suppliercost);  // Convert the string to an integer after removing commas				
				$unit_weight = 0 ;
				if($number > 0 )
				{ 
				 $unit_weight = number_format(floor($number / $saved_weight)) ;				 
				}					   		
			?>
		    <tr onclick="JavaScript:Toview('<?=$num?>');">
			
            <td class="text-center" > <?=$start_num?>		</td>
            <td class="text-center" >	 <?=$indate?>		</td>						   
            <td                     > <?=$outworkplace?> 			</td>
            <td class="text-center" > <?=$model?>			</td>
            <td class="text-center " > <?=$supplier?>		</td>
            <td class="text-center text-primary" > <?=$steel_item?>			</td>
            <td class="text-center" > <?=$spec?>			</td>
            <td class="text-center" > <?=$steelnum?>		</td>
            <td class="text-center text-dark" > <?=$saved_weight ==0 ? '' : $saved_weight  ?>	   </td>						            
            <td class="text-end text-success" > <?=$unit_weight ==0 ? '' : $unit_weight  ?>			</td>						            
            <td class="text-end text-danger" >  <?=$suppliercost?>			</td>
            <td class="text-center"><?=mb_substr($request_comment, 0, 12) ?></td>					
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

<div class="container">
<? include '../footer_sub.php'; ?>
</div>

</form>
<script>
$(document).ready(function(){	
// 원자재 금액산출 클릭시
$("#calamountBtn").click(function(){         
	 popupCenter('calamount.php?menu=no'  , '원자재 금액 산출', 1000, 650);	
});

// 원자재현황 클릭시
$("#rawmaterialBtn").click(function(){ 
        
	 popupCenter('../steel/rawmaterial.php?menu=no'  , '원자재현황보기', 1050, 950);	
});

// 원자재 가격테이블 클릭시
$("#showmaterialfeeBtn").click(function(){ 
        
	 popupCenter('./settings.php'  , '원자재현황보기', 800, 500);	
});

$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});
	
$("#searchBtn").click(function(){ 
	var str = '<?php echo $BigsearchTag; ?>' ;		  
		 $("#BigsearchTag").val(str.replace(' ','|'));
		 $("#stable").val('1');
		 $("#page").val('1');
		 $("#list").val('1');
		 $("#board_form").submit();  		 
		document.getElementById('board_form').submit();   
	 });		
		
    $('#Bigsearch').change(function() {
        // $BigsearchTag 설정
        var str = $("#Bigsearch").val();
        
        $("#BigsearchTag").val(str.replace(' ','|'));
        $("#list").val('1');
        $("#board_form").submit();                
        document.getElementById('board_form').submit();
    });
	
	$("#total_row").text('<?= $total_row ?>');
});

function Toview(num) {
    var url = "../request/view.php?num=" + num ;
    
    console.log(url); // 오타 수정
	customPopup(url, '원자재 원가', 1400, 800);     
}
</script>  
</body>
</html>