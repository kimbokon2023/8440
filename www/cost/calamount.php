<?php require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php')); 
include getDocumentRoot() . '/load_header.php' ;

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';  
$option = $_REQUEST['option'] ?? '';
$materialRaw = $_REQUEST['materialRaw'] ?? '';

$item = $_REQUEST["item"] ?? "304 HL";   // steel_item
$spec = $_REQUEST["spec"] ?? "";
$steelnum = $_REQUEST["steelnum"] ?? 1;
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : 'search';
$fromdate = date("Y-m-d", strtotime("-2 years"));
$todate = date("Y-m-d");
$Transtodate = date("Y-m-d", strtotime($todate . ' +1 day'));

// Initialize variables to prevent undefined variable warnings
$num = $_REQUEST['num'] ?? '';
$page = $_REQUEST['page'] ?? '';
$calculate = $_REQUEST['calculate'] ?? '';

$default_condition = false;
$searchspec = '';
$searchsupplier = '';

$tablename ='eworks';

// 조건 분기: 단가 추정 기준 정의
if ($item == '304 HL') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '현진스텐';
    $default_condition = true;
} elseif ($item == '201 2B MR') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '윤스틸';
    $default_condition = true;
} elseif ($item == '201 HL' || $item == '201 VB') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '우성스틸';
    $default_condition = true;
} elseif ($item == '201 MR BEAD' || $item == '2B VB' || $item == 'VB') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '한산엘테크';
    $default_condition = true;
} elseif ($item == 'CR') {
    $searchspec = '1.2*1219*1950';
    $searchsupplier = '용민철강';
    $default_condition = true;
} elseif ($item == 'PO') {
    $searchspec = '1.2*1219*1950';
    $searchsupplier = '용민철강';
    $default_condition = true;
} elseif ($item == 'EGI') {
    $searchspec = '1.2*1219*2438';
    $searchsupplier = '용민철강';
    $default_condition = true;
}


$data = [];
$item_arr = [];

try {
    for ($i = 23; $i >= 0; $i--) {
        $target_month = date('Y-m-01', strtotime("-$i months"));
        $next_month = date('Y-m-01', strtotime("$target_month +1 month"));

        $sql = "SELECT steel_item, spec, supplier, suppliercost, steelnum 
                FROM {$DB}.eworks 
                WHERE outdate >= :target_month 
                  AND outdate < :next_month 
                  AND eworks_item = '원자재구매'
                  AND (is_deleted IS NULL OR is_deleted = '') ";

        if ($default_condition) {
            $sql .= " AND spec = :spec AND supplier LIKE :supplier";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':target_month', $target_month);
        $stmt->bindParam(':next_month', $next_month);
        if ($default_condition) {
            $stmt->bindValue(':spec', $searchspec);
            $stmt->bindValue(':supplier', '%' . $searchsupplier . '%');
        }

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $steel_item = trim($row['steel_item']);
            $suppliercost = (int)str_replace(',', '', $row['suppliercost']);
            $spec_parts = explode('*', $row['spec']);

            $val1 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[0] ?? 0);
            $val2 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[1] ?? 0);
            $val3 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[2] ?? 0);

            $weight = ($val1 * $val2 * $val3 * 7.93 * (int)$row['steelnum']) / 1000000;
            $weight = $weight > 0 ? $weight : 1;
            $unit_weight = floor($suppliercost / $weight);

            $month = substr($target_month, 0, 7);
            $data[$steel_item][$month] = $unit_weight;
            $item_arr[] = $steel_item;
        }
    }
} catch (PDOException $e) {
    error_log("단가 계산 오류: " . $e->getMessage());
}

$item_arr = array_unique($item_arr);
$item_arr = array_filter($item_arr, function($v) {
    return trim($v) !== '';
});
$item_arr = array_values($item_arr); // 인덱스 재정렬

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

// 현재 선택한 철판의 최신 단가 추정
$unitprice = 0;
$lastValidMonth = '';
if (isset($data[$item])) {
    $months = array_keys($data[$item]);
    rsort($months);
    foreach ($months as $m) {
        if ($data[$item][$m] > 0) {
            $unitprice = $data[$item][$m];
            $lastValidMonth = $m;
            break;
        }
    }
}

// 중량 계산
$spec_parts = explode('*', $spec);
$val1 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[0] ?? 0);
$val2 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[1] ?? 0);
$val3 = (float)preg_replace('/[^0-9.]/', '', $spec_parts[2] ?? 0);
$weight = ($val1 * $val2 * $val3 * 7.93) / 1000000;
$totalweight = number_format(intval($weight * $steelnum));
$totalamount = $weight * $steelnum * $unitprice;
?>

<title> 원자재 금액 산출 </title>
</head>
<body>

<div class ="container-fluid">
<form id="board_form"  name="board_form"  method="post" enctype="multipart/form-data" >		

  <input type="hidden" id="materialRawData" value="<?= htmlspecialchars($materialRaw) ?>">

	<div class ="card">
	<div class ="card-body">	
		<div class="card-header justify-content-center"> 	
			<div class ="d-flex align-items-center justify-content-center fs-5 mt-3 mb-2">
				현시세 원자재 금액 산출 
				<button type="button" class="btn btn-dark btn-sm mx-2"  onclick='location.reload();' > <i class="bi bi-arrow-clockwise"></i> </button>  	   		  								
				<button type="button" class="btn btn-outline-dark btn-sm mx-5"  onclick='window.close();' >  &times; 닫기 </button>  	   		  								
			</div>
		</div>
		<div class="alert alert-primary d-flex mt-4 mb-2 align-items-center justify-content-center" role="alert">       
			  304 HL 등 기본소재는 주거래처인 '현진스텐' 1219 * 2438 기준의 단가로 추정함. 기초단가는 2년간 거래기록을 이용한 참고자료입니다.
		</div>   
	
	<div class ="d-flex align-items-center justify-content-center">
	 <div class="col-sm-7">	  		 
           <input type="hidden" id="SelectWork" name="SelectWork" > 
           <input type="hidden" id="vacancy" name="vacancy" > 
           <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num ?? '', ENT_QUOTES, 'UTF-8') ?>" > 
           <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page ?? '', ENT_QUOTES, 'UTF-8') ?>" > 
           <input type="hidden" id="calculate" name="calculate" value="<?= htmlspecialchars($calculate ?? '', ENT_QUOTES, 'UTF-8') ?>" >
				
		      <div class="p-1 m-1" >
		     <button type="button" class="btn btn-primary btn-sm  clickbtn " onclick="HL304_click();" > 304 HL </button>	&nbsp;   
			 <button type="button" class="btn btn-success btn-sm clickbtn " onclick="MR304_click();" > 304 MR </button>	&nbsp;    			 
			 <button type="button" class="btn btn-secondary btn-sm clickbtn " onclick="VB_click();" > VB </button>	&nbsp;    
			 <button type="button" class="btn btn-warning btn-sm clickbtn " onclick="EGI_click();" > EGI </button>	&nbsp;    
			 <button type="button" class="btn btn-danger btn-sm clickbtn " onclick="PO_click();" > PO </button>	&nbsp;    
			 <button type="button" class="btn btn-dark btn-sm clickbtn " onclick="CR_click();" > CR </button>	&nbsp;  
			 <button type="button" class="btn btn-success btn-sm clickbtn " onclick="MR201_click();" > 201 2B MR </button>	&nbsp;  
			   </div>	
			  <div class="p-1 m-1" >
			  <span class="text-success "> <strong> 쟘 1.2T &nbsp; </strong> </span>	
			   <button type="button" class="btn btn-outline-success btn-sm clickbtn " onclick="size1000_2150_click();"> 1000x2150  </button> &nbsp;
				<button type="button"  class="btn btn-outline-success btn-sm clickbtn "   onclick="size42150_click();">  4'X2150 </button> &nbsp;
				<button type="button"  class="btn btn-outline-success btn-sm clickbtn "   onclick="size1000_8_click();"> 1000x8' </button> &nbsp; 
			  </div>	
			  <div class="p-1 m-1" >
				 &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <button type="button"   class="btn btn-outline-success btn-sm clickbtn "  onclick="size4_8_click();"> 4'x8' </button> &nbsp;
				  <button type="button"  class="btn btn-outline-success btn-sm clickbtn "  onclick="size1000_2700_click();"> 1000x2700 </button> &nbsp;
				   <button type="button" class="btn btn-outline-success btn-sm clickbtn "  onclick="size4_2700_click();"> 4'x2700 </button> &nbsp;
				   <button type="button" class="btn btn-outline-success btn-sm clickbtn "  onclick="size4_3200_click();"> 4'x3200  </button> &nbsp;
				   <button type="button" class="btn btn-outline-success btn-sm clickbtn "   onclick="size4_4000_click();"> 4'x4000 </button> &nbsp;	   			  
			  </div>			  
			  <div class="p-1 m-1" >
			  <span class="text-success "> <strong> 신규쟘 1.5T(HL) &nbsp; </strong> </span>	
			   <button type="button" class="btn btn-outline-success btn-sm clickbtn " onclick="size15_4_2150_click();"> 4'x2150 </button> &nbsp;				
			   <button type="button" class="btn btn-outline-success btn-sm clickbtn " onclick="size15_4_8_click();"> 4'x8' </button> &nbsp;				
			  </div>	
			  <div class="p-1 m-1" >
				  <span class="text-success "> <strong> 신규쟘 2.0T(EGI) &nbsp; </strong> </span>	
				   <button type="button" class="btn btn-outline-success btn-sm clickbtn " onclick="size20_4_8_click();"> 4'x8'  </button> &nbsp;				
			  </div>			  

			<div class=" p-1 m-1" >	   
			   천장 1.2T(CR)  </button> &nbsp; 
				  <button type="button"  class="btn btn-outline-danger btn-sm clickbtn " onclick="size12_4_1680_click();"> 4'x1680 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-danger btn-sm clickbtn " onclick="size12_4_1950_click();"> 4'x1950 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-danger btn-sm clickbtn "  onclick="size12_4_8_click();"> 4'x8' </button> &nbsp;
			  </div>			  
			  <div class=" p-1 m-1" >			  				   
				  천장 1.6T(CR)   &nbsp; 	  
				  <button type="button"  class="btn btn-outline-primary btn-sm clickbtn " onclick="size16_4_1680_click();"> 4'x1680 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-primary btn-sm clickbtn "  onclick="size16_4_1950_click();"> 4'x1950 </button> &nbsp;
				  <button type="button"  class="btn btn-outline-primary btn-sm clickbtn "  onclick="size16_4_8_click();"> 4'x8' </button> &nbsp;		   		   
			  </div>
			  <div class=" p-1 m-1" >	
				   천장 2.3T(PO)  &nbsp; 	  
				   <button type="button" class="btn btn-outline-secondary btn-sm clickbtn " onclick="size23_4_1680_click();"> 4'x1680 </button> &nbsp;
				   <button type="button" class="btn btn-outline-secondary btn-sm clickbtn "  onclick="size23_4_1950_click();"> 4'x1950 </button> &nbsp;
				   <button type="button" class="btn btn-outline-secondary btn-sm clickbtn "  onclick="size23_4_8_click();"> 4'x8'  </button> &nbsp;	
	          </div>
			  <div class=" p-1 m-1" >	
				   천장 3.2T(PO)  &nbsp; 	  
				   <button type="button" class="btn btn-outline-secondary btn-sm clickbtn " onclick="size32_4_1680_click();"> 4'x1680 </button> &nbsp;			   
	          </div>
		   
	  	 </div> 
		<div class="col-sm-5">	  			    
		    <div class="table-responsive"> 			                        	                         		
			  <table class="table table-bordered">
			     <tbody>
			      <tr>
				  <td class="text-center">
				      종 류   
					  </td>
				  <td class="text-center">
					<select name="item" id="item" class="form-select w-auto" style="font-size: 0.8rem; height: 32px;">
						<?php				  
							if($item==='')
								$item = '304 HL';
							for($i=0;$i<count($steel_items);$i++) {
								if($item==$steel_items[$i])
									print "<option selected value='" . $steel_items[$i] . "'> " . $steel_items[$i] .   "</option>";
								else
									print "<option value='" . $steel_items[$i] . "'> " . $steel_items[$i] .   "</option>";
							}	
							
						?>
					</select>		
                    </td>
					</tr>
				  <tr>
				<td class="text-center">
			    규 격
				</td>
				<td class="text-center">
				<select name="spec" id="spec" class="form-select w-auto" style="font-size: 0.8rem; height: 32px;">						  
											  
						<?php	

							// 철판 규격 불러오기
							$sql="select * from mirae8440.steelspec"; 					

								 try{  

							   $stmh = $pdo->query($sql);            
							   $rowNum = $stmh->rowCount();  
							   $counter=0;
							   $spec_arr=array();

							   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
								   
										  $spec_arr[$counter]=trim($row["spec"]);
										 
										  $counter++;
								 } 	 
							   } catch (PDOException $Exception) {
								print "오류: ".$Exception->getMessage();
							}    

							   $spec_counter=count($spec_arr);
							   sort($spec_arr);  // 오름차순으로 배열 정렬
							   
							   
							   if($spec==='')
									 $spec ="1.2*1219*2438";

					   for($i=0;$i<$spec_counter;$i++) {
							   if(trim($spec) == $spec_arr[$i])
									   print "<option selected value='" . $spec_arr[$i] . "'> " . $spec_arr[$i] .   "</option>";
								   else
										print "<option value='" . $spec_arr[$i] . "'> " . $spec_arr[$i] .   "</option>";
					   } 	
					?>	 				   							   
				   </select>	
                </td>
				</tr>
			    <tr>
				<td class="text-center">								 						 						 
					매수(SH) 
				</td>
				<td class="text-center">							  
					  <input name="steelnum" id="steelnum" class="text-end changebtn form-control w50px" value='<?=$steelnum?>' oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off">						 						  
				</td>
				</tr>
			     <tr>
				<td class="text-center">						 						 
				  1매당 중량(Kg)
				</td>
				<td class="text-center">				
						  <input name="weight" id="weight" class="form-control text-end bg-light w80px" readonly>			
                    </td>
					</tr>
				<tr>
				<td class="text-center">						 						 				
					Kg당 단가 
				</td>
				<td class="text-center">							  
					<input name="unitprice" id="unitprice" class="text-end form-control bg-light w80px" readonly value='<?=number_format($unitprice)?>' oninput="this.value = this.value.replace(/[^0-9]/g, '');"  >	
				</td>
				</tr>
			    <tr>
				<td class="text-center">							 						 						 
					총중량(KG) 
				</td>
				<td class="text-center">
					<input name="totalweight" id="totalweight" class="text-end changebtn form-control bg-light w80px" readonly value='<?=$totalweight?>' oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" >
                 </td>
				</tr>
				<tr>
				<td class="text-center">							 						 						 										
					<?php				
						$temp_arr = explode('*', $spec);
						$saved_weight = ($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000;              
						$totalamount = $saved_weight * $unitprice;
					?>				
					<span class="badge bg-primary "> <h6> 금액 </h6> </span> 
				</td>
				<td class="text-center">									
					<div class="d-flex justify-content-start align-items-center" >
						<input name="totalamount" id="totalamount"   class="text-end form-control bg-primary text-white w120px"  value='<?=number_format($totalamount)?>' readonly >
						원
				
						<!-- 금액 input 옆 + 버튼 -->				
						<label class="ms-5 me-2"> 행 </label>
						<button type="button" class="btn btn-dark btn-sm" id="addMaterialRow">+</button>
					</div>
				</td>											
				</tr>				
			  </tbody>
			  </table>
			</div>  
		</div>
	  </div>
	<div class="table-responsive"> 			                        	                         		
	  <table class="table table-bordered">
		 <tbody>  
			<tr>				
				<td class="text-center" colspan="2">									
					<!-- 동적 테이블 -->
					<div class="table-responsive">
						<table id="materialTable" class="table table-bordered text-center">
							<thead class="table-secondary">
								<tr>
									<th>종류</th>
									<th>규격</th>
									<th>매수</th>
									<th>금액</th>
									<th>삭제</th>
								</tr>
							</thead>
							<tbody>
								<!-- 동적으로 추가됨 -->
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3"><strong>총 합계</strong></td>
									<td id="materialTotal" class="text-end fw-bold" >0</td>
									<td></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</td>
				</tr>				
			  </tbody>
			  </table>
			</div>  
   			<?php if($option =='parent') : ?>
			<div class ="d-flex align-items-center justify-content-center mt-1 mb-2">		
				<button type="button" class="btn btn-dark btn-sm mx-5 parentadaptBtn"  >  <i class="bi bi-arrow-return-right"></i> 부모창 적용하기 </button>  	   		  								
			</div>
			<?php endif; ?>
  
  </div>	 
  </div>
</form>		  
</div>	 

<script>

// 원자재 단가 가져오기 (비동기 방식)
// 서버와 로컬 모두에서 동작하도록 절대경로 대신 상대경로 사용
function fetchUnitPrice(item) {
    return $.ajax({
        url: 'fetch_unitprice.php',
        type: 'POST',
        data: { item: item },
        dataType: 'json'
    });
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function calculateSteelWeightAndAmount() {
    let spec = document.getElementById('spec').value.trim();
    let steelnum = parseFloat(document.getElementById('steelnum').value.replace(/[^0-9.]/g, '')) || 0;
    let unitprice = parseFloat(document.getElementById('unitprice').value.replace(/[^0-9.]/g, '')) || 0;

    let parts = spec.split('*');
    let val1 = parseFloat(parts[0].replace(/[^0-9.]/g, '')) || 0;
    let val2 = parseFloat(parts[1].replace(/[^0-9.]/g, '')) || 0;
    let val3 = parseFloat(parts[2].replace(/[^0-9.]/g, '')) || 0;

    let weight = (val1 * val2 * val3 * 7.93) / 1000000;
    let totalweight = weight * steelnum;
    let totalamount = totalweight * unitprice;

    document.getElementById('weight').value = numberWithCommas(weight.toFixed(1));
    document.getElementById('totalweight').value = numberWithCommas(Math.floor(totalweight));
    document.getElementById('totalamount').value = numberWithCommas(Math.floor(totalamount));
}

$(document).ready(function () {

    $('#item').change(function () {
        const selectedItem = $(this).val();
        fetchUnitPrice(selectedItem).done(function (data) {
			console.log('data : ',data);
            if (data.unitprice !== undefined) {
                const formatted = numberWithCommas(data.unitprice);
                $('#unitprice').val(formatted);
                calculateSteelWeightAndAmount();
            } else {
                $('#unitprice').val('0');
                calculateSteelWeightAndAmount();
            }
        }).fail(function (xhr, status, error) {
            console.error('단가 가져오기 실패:', error);
        });
    });

    $('#unitprice').on('change', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value != '') this.value = numberWithCommas(this.value.replace(/,/g, ''));
    });

    $('.clickbtn').click(function () {
			const selectedItem = $("#item").val();
			fetchUnitPrice(selectedItem).done(function (data) {
				console.log('data : ',data);
				if (data.unitprice !== undefined) {
					const formatted = numberWithCommas(data.unitprice);
					$('#unitprice').val(formatted);
					calculateSteelWeightAndAmount();
				} else {
					$('#unitprice').val('0');
					calculateSteelWeightAndAmount();
				}
			}).fail(function (xhr, status, error) {
				console.error('단가 가져오기 실패:', error);
			});		
    });

    $("#calBtn").click(function () {
        var steelnum = $('#steelnum').val();
        var spec = $('#spec').val();
        var arr = spec.split('*');
        var unit = (Number(arr[0]) * Number(arr[1]) * Number(arr[2]) * 7.93) / 1000000;
        var sheet = parseInt(Number(steelnum) * 1000 / unit);
        $("#steelnum").val(sheet);
    });

    $("#returnBtn").click(function () {
        $("input[name=steelnum]", opener.document).val($('#steelnum').val());
        window.close();
    });

    $("#calBtn").trigger('click');

    calculateSteelWeightAndAmount();

    $('#spec, #steelnum, #unitprice').on('input change', function () {
        calculateSteelWeightAndAmount();
    });

    $('#addMaterialRow').on('click', function () {
        const item = $('#item').val();
        const spec = $('#spec').val();
        const count = $('#steelnum').val();
        const amount = $('#totalamount').val();

        let newRow = `
            <tr>
                <td><input type="text" name="mat_item[]" class="form-control text-center" value="${item}" readonly></td>
                <td><input type="text" name="mat_spec[]" class="form-control text-center" value="${spec}" readonly></td>
                <td><input type="text" name="mat_count[]" class="form-control text-center" value="${count}" readonly></td>
                <td><input type="text" name="mat_amount[]" class="form-control text-end amount-cell" value="${amount}" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">-</button></td>
            </tr>
        `;
        $('#materialTable tbody').append(newRow);
        updateMaterialTotal();
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        updateMaterialTotal();
    });

    function updateMaterialTotal() {
        let total = 0;
        $('.amount-cell').each(function () {
            let val = $(this).val().replace(/,/g, '') || '0';
            total += parseInt(val, 10);
        });
        $('#materialTotal').text(total.toLocaleString());
    }

    const rawData = $('#materialRawData').val();
    if (rawData && rawData.includes('{')) {
        const matches = rawData.match(/\{([^}]+)\}/g);
        if (matches) {
            matches.forEach(entry => {
                const clean = entry.replace(/[{}]/g, '');
                const [item, spec, count, amount] = clean.split('|');
                if (item && spec && count) {
                    let amountVal = amount || '0';
                    let newRow = `
                        <tr>
                            <td><input type="text" name="mat_item[]" class="form-control text-center" value="${item}" readonly></td>
                            <td><input type="text" name="mat_spec[]" class="form-control text-center" value="${spec}" readonly></td>
                            <td><input type="text" name="mat_count[]" class="form-control text-center" value="${count}" readonly></td>
                            <td><input type="text" name="mat_amount[]" class="form-control text-end amount-cell" value="${amountVal}" readonly></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">-</button></td>
                        </tr>
                    `;
                    $('#materialTable tbody').append(newRow);
                }
            });
        }
        updateMaterialTotal();
    }

    $('.parentadaptBtn').on('click', function () {
        var totalAmount = $('#materialTotal').text();
        window.opener.$('#materialFee').val(totalAmount);

        var encodedRows = [];
        $('#materialTable tbody tr').each(function () {
            var item = $(this).find('input[name="mat_item[]"]').val()?.trim() || '';
            var spec = $(this).find('input[name="mat_spec[]"]').val()?.trim() || '';
            var count = $(this).find('input[name="mat_count[]"]').val()?.trim() || '';
            var amount = $(this).find('input[name="mat_amount[]"]').val()?.trim().replace(/,/g, '') || '';

            if (item && spec && count) {
                encodedRows.push(`{${item}|${spec}|${count}|${amount}}`);
            }
        });

        var encodedString = encodedRows.join('');
        window.opener.$('#materialRaw').val(encodedString);

        if (window.opener && typeof window.opener.updateTotalFee === 'function') {
            window.opener.updateTotalFee();
        }

        window.close();
    });
});
</script>


</body>
</html>