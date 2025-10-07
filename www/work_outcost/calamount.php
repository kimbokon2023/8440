<?php session_start();
isset($_REQUEST["item"]) ? $item=$_REQUEST["item"] : $item="";   
isset($_REQUEST["spec"]) ? $spec=$_REQUEST["spec"] : $spec=""; 
isset($_REQUEST["steelnum"]) ? $steelnum=$_REQUEST["steelnum"] : $steelnum=1; 

 // include "../subload_notice.php";  //공지사항 불러오기
include "_request.php";
 
 
$item_arr = array();
	
try {
    $current_month = date('Y-m');
    $start_month = date('Y-m', strtotime('-23 months'));

    $data = []; // 연관 배열을 저장할 변수

    for ($i = 23; $i >= 0; $i--) {
        $target_month = date('Y-m-01', strtotime("$start_month +$i months"));
        $next_month = date('Y-m-01', strtotime("$target_month +1 month"));

        $sqlsearch = "SELECT item, spec, steelnum, suppliercost FROM mirae8440.cost WHERE outdate >= :target_month AND outdate < :next_month";
        $stmt = $pdo->prepare($sqlsearch);
        $stmt->bindParam(':target_month', $target_month);
        $stmt->bindParam(':next_month', $next_month);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			
            $suppliercost = $row['suppliercost'];
			
			array_push($item_arr,$row['item']);
            
            $temp_arr = explode('*', $row['spec']);
			
			
            $saved_weight = ($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$row['steelnum']) / 1000000;
            $saved_weight = sprintf('%0.1f', $saved_weight);
            $number = (int)str_replace(',', '', $suppliercost);
            $unit_weight = $number > 0 ? floor($number / $saved_weight) : 0;

            $month = substr($target_month, 0, 7); // 월을 추출합니다.

            if (!isset($data[$row['item']])) {
                $data[$row['item']] = []; // 아이템에 대한 배열을 초기화합니다.
            }

            $data[$row['item']][$month] = $unit_weight; // 아이템의 해당 월에 단가를 저장합니다.
        }
    }
} catch (PDOException $Exception) {
    error_log("오류: " . $Exception->getMessage());
}	
	
$item_arr = array_unique($item_arr);
$item_arr = array_filter($item_arr, function($value) {
    return trim($value) !== '';
});


// 배열 인덱스를 리셋합니다.

$item_arr = array_values($item_arr);

$temptmp = explode('*', $spec);

// var_dump($temptmp);

$weight = (float)$temptmp[0] * (float)$temptmp[1] * (float)$temptmp[2] * 7.93 / 1000000 ;

// print_r($item);

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
  
<title> 원자재 금액 산출 </title>

</head>

<body>

<div class ="container">
<form id="board_form"  name="board_form"  method="post" enctype="multipart/form-data" >		
	<div class ="card">
	<div class ="card-body">
	
		    <div class="card-header justify-content-center"> 	
				<div class ="d-flex align-items-center justify-content-center fs-4 mt-3 mb-2">
					현시세 원자재 금액 산출 
				</div>
			</div>
	
	<div class ="d-flex align-items-center justify-content-center">
	 <div class="col-sm-7">	  		 
            <input type="hidden" id="SelectWork" name="SelectWork" > 
            <input type="hidden" id="vacancy" name="vacancy" > 
            <input type="hidden" id="num" name="num" value=<?=$num?> > 
            <input type="hidden" id="page" name="page" value=<?=$page?> > 
            <input type="hidden" id="calculate" name="calculate" value=<?=$calculate?> > 		
				
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
		    <div class="card-header"> 			                        	                         		
			  <div class="input-group p-2 mb-2 mt-3">
				  종 류 &nbsp; 
					<select name="item" id="item" >	
						<?php				  
							if($item==='')
								$item = '304 HL';
							for($i=0;$i<count($item_arr);$i++) {
								if($item==$item_arr[$i])
									print "<option selected value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   "</option>";
								else
									print "<option value='" . $item_arr[$i] . "'> " . $item_arr[$i] .   "</option>";
							}
							
						$firstMonth = array_key_first($data[$item]); // PHP 7.3.0 이상에서 사용 가능
						$unitprice = $data[$item][$firstMonth];				
							
						?>
					</select>		   			  		  
		  </div>			                        	                         								 
		  <div class="input-group p-2 mb-2 mt-3">
			  규 격 &nbsp; 
				<select name="spec" id="spec" >						  
								  
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
					 </div>			                        	                         	
						 
						 <div class="input-group p-2 mb-2 mt-2">							 						 
						  1매당 중량(Kg) &nbsp; <input name="weight" id="weight" class="text-end" value='<?=number_format($weight)?>' style="width:70px;">						  &nbsp;
						  

						 </div>	
						 <div class="input-group p-2 mb-2 mt-2">							 						 
						  Kg당 단가 &nbsp; <input name="unitprice" id="unitprice" class="text-end" value='<?=number_format($unitprice)?>' oninput="this.value = this.value.replace(/[^0-9]/g, '');" style="width:70px;">
						  &nbsp;
						  매수(SH) &nbsp; <input name="steelnum" id="steelnum" class="text-end changebtn" value='<?=$steelnum?>' oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" style="width:40px;">

						 </div>						 
						 <div class="input-group p-2 mb-2 mt-2">							 						 						 						
				
				<?php
				
						$temp_arr = explode('*', $spec);
						$saved_weight = ($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum) / 1000000;              
						$totalamount = $saved_weight * $unitprice;
					?>
				
						<span class="badge bg-danger fs-6">  예상금액 </span> &nbsp; <input name="totalamount" id="totalamount"   class="text-end"  value='<?=number_format($totalamount)?>' style="width:100px;" readonly >&nbsp; &nbsp; 						  
						 </div>
						<?php 
		     
			 ?>
		
              
  </div>
  
    </div>
  </div>	 
  </div>	 
  </div>
</form>		  
  </div>	 
  		
</body>
</html>

  
		  
 <script> 
 
 function numberWithCommas(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).ready(function(){
	
        $('#unitprice').on('input', function() {
            // 숫자만 허용
            this.value = this.value.replace(/[^0-9]/g, '');
            // 3자리마다 콤마 추가
            if (this.value != '') this.value = numberWithCommas(this.value.replace(/,/g , ''));
        });	

    $('#steelnum').change(function() {      
        document.getElementById('board_form').submit();
    });

    $('#item').change(function() {      
        document.getElementById('board_form').submit();
    });

    $('#spec').change(function() {      
        document.getElementById('board_form').submit();
    });
	

        $('.clickbtn').click(function() {
            // 여기서 해당 버튼의 onclick 이벤트가 호출됩니다.
            $('#board_form').submit(); // 양식을 제출합니다.
        });
				

				
	$("#calBtn").click(function(){    // 계산하기 수량
     var steelnum = $('#steelnum').val();
     var spec = $('#spec').val();
	 
	 var arr = spec.split('*');
	 var unit;
	 var weight;
	 
	 console.log(arr);
	 
	 unit = (Number(arr[0])*Number(arr[1])*Number(arr[2]) * 7.93) /1000000;
	  
	 sheet = parseInt(Number(steelnum) * 1000 / unit);
	 
	 $("#steelnum").val(sheet);
	 
	 console.log(unit);
	 
		// // 부모창에 적용해 주기 opener 활용기법
		// $("input[name=steelnum]", opener.document).val($('#steelnum').val());		
		// $("#item", opener.document).val($('#item').val() );
		// $("#spec", opener.document).val($('#spec').val());
		// $("#comment", opener.document).val($('#steelnum').val() + '톤 주문해 주세요');
		//	window.close();	 	


	 });	
	
	$("#returnBtn").click(function(){    // 부모창에 적용하기

		// 부모창에 적용해 주기 opener 활용기법
		$("input[name=steelnum]", opener.document).val($('#steelnum').val());		
		window.close();
	 });		
								
}); // end of ready document


  </script>
