<?php 

$sumstat=array(); 
	
$fromdate=date("Y",time()) ;
$fromdate=$fromdate . "-01-01";
$Transtodate=strtotime($todate);
$Transtodate=date("Y-m-d",$Transtodate);

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file(getDocumentRoot() . "/steel/settings.ini",false);	
			  					   
$PO=$readIni['PO'];
$CR=$readIni['CR'];
$EGI=$readIni['EGI'];
$HL304=$readIni['HL304'];
$MR304=$readIni['MR304'];
$etcsteel=$readIni['etcsteel'];

$price_per_kg = [
    'CR' => $CR,
    'PO' => $PO,
    'EGI' => $EGI,
    '304 HL' => $HL304,
    '201 2B MR' => '3.0',
    '201 MR' => '3.0',
    '201 HL' => '2.8',
    '304 MR' => $MR304,
    'etcsteel' => $etcsteel
];

// var_dump($exclude_bad_choice) ;

		 // 소장 체크 제외인경우
	if($exclude_bad_choice !=='false') 
		$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음'  AND bad_choice != '소장' AND bad_choice != '업체') AND outdate BETWEEN date('$fromdate') AND date('$Transtodate') ORDER BY year, month";
	else
		$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' ) AND outdate BETWEEN date('$fromdate') AND date('$Transtodate') ORDER BY year, month";	
 
$total_sum = 0;

try{  
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh

		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			
			
			$item = trim($row["item"]);
			$spec = trim($row["spec"]);
			$steelnum = $row["steelnum"];
			$bad_choice = $row["bad_choice"];

			$spec_parts = explode('*', $spec);
			$thickness = $spec_parts[0];
			$width = $spec_parts[1];
			$length = $spec_parts[2];

			$weight = $thickness * $width * $length/1000;
          if((int)$steelnum !== 0)  // 수량이 1이상일때 잔재는 제외함
		  {
			if (array_key_exists($item, $price_per_kg)) {
				$price = $price_per_kg[$item];
			} else {
				$price = $price_per_kg['etcsteel'];
			}

			$total_price = ($weight * $price * $steelnum * 7.93);
			$total_sum += $total_price;
		  }
		  else
		  {
			$total_price = 0 ;
			$total_sum += $total_price;			  
		  }
		}

} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}	

// 최종 결과를 포맷팅하여 출력
$formatted_total_sum = number_format($total_sum, 0, '.', ',');



if($Allmonth === 'true')
{
		 // 소장/업체 체크 제외인경우
	if($exclude_bad_choice ==='false') 
			$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음') ORDER BY year, month";	
	else
			$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소장'  AND bad_choice != '업체' ) ORDER BY year, month";	
		 // 소장/업체 체크 제외인경우
}
else
{
	if($exclude_bad_choice ==='false') 
		$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음') AND outdate BETWEEN date('$fromdate') AND date('$Transtodate') ORDER BY year, month";
	else
		$sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice FROM mirae8440.steel WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음'  AND bad_choice != '소장'  AND bad_choice != '업체'  ) AND outdate BETWEEN date('$fromdate') AND date('$Transtodate') ORDER BY year, month";
	
 }

//  다음 월별/년도별 합계를 계산하기 위해 다음과 같이 코드를 수정합니다.
$monthly_totals = [];
 
$total_sum = 0;

try{  
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh

while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	
	        $item = trim($row["item"]);
			$spec = trim($row["spec"]);
			$steelnum = $row["steelnum"];
			$bad_choice = $row["bad_choice"];

			$spec_parts = explode('*', $spec);
			$thickness = $spec_parts[0];
			$width = $spec_parts[1];
			$length = $spec_parts[2];

			$weight = $thickness * $width * $length/1000;
          if((int)$steelnum !== 0)  // 수량이 1이상일때 잔재는 제외함
		  {
			if (array_key_exists($item, $price_per_kg)) {
				$price = $price_per_kg[$item];
			} else {
				$price = $price_per_kg['etcsteel'];
			}

			$total_price = ($weight * $price * $steelnum * 7.93);
			$total_sum += $total_price;
		  }
		  else
		  {
			$total_price = 0 ;
			$total_sum += $total_price;			  
		  }	

    $year = $row["year"];
    $month = $row["month"];

    if (!isset($monthly_totals["$year-$month"])) {
        $monthly_totals["$year-$month"] = 0;
    }

    $monthly_totals["$year-$month"] += $total_price;
}

} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}	
  
?>
<div id="myerrorchat" style="width: 100%; height: 180px;"></div>
	

<script>
$(document).ready(function(){	

    // PHP에서 계산된 월별/년도별 합계를 JavaScript로 전달
    const monthly_totals = <?php echo json_encode($monthly_totals); ?>;

    // x축 라벨 및 데이터 시리즈 준비
    const data = Object.keys(monthly_totals).map(key => {
        const date = new Date(key + '-01'); // Convert to a Date object
        const month = (date.getMonth() + 1) + '월'; // Get the month in 'n월' format
        return {
            name: month,
            y: parseFloat(monthly_totals[key].toFixed(2))
        };
    });

    // 그래프 생성
    Highcharts.chart('myerrorchat', {
        chart: {
            type: 'pie'
        },
        title: {
            text: '월별 부적합 점유율'
        },
        tooltip: {
            pointFormatter: function() {
                return '<b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + ' 원</b>';
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: '부적합 비용',
            colorByPoint: true,
            data: data
        }]
    });
      
});
</script> 
