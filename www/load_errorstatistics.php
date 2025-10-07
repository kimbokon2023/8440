<?php

$sum=array();

$fromdate=date("Y",time()) ;
$fromdate=$fromdate . "-01-01";
$Transtodate=strtotime($todate);
$Transtodate=date("Y-m-d",$Transtodate);

$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file(getDocumentRoot() . "/steel/settings.ini",false);

$PO = isset($readIni['PO']) ? (int)str_replace(',', '', $readIni['PO']) : 0;
$CR = isset($readIni['CR']) ? (int)str_replace(',', '', $readIni['CR']) : 0;
$EGI = isset($readIni['EGI']) ? (int)str_replace(',', '', $readIni['EGI']) : 0;
$HL304 = isset($readIni['HL304']) ? (int)str_replace(',', '', $readIni['HL304']) : 0;
$MR304 = isset($readIni['MR304']) ? (int)str_replace(',', '', $readIni['MR304']) : 0;
$HL201 = isset($readIni['HL201']) ? (int)str_replace(',', '', $readIni['HL201']) : 0;
$MR201 = isset($readIni['MR201']) ? (int)str_replace(',', '', $readIni['MR201']) : 0;
$etcsteel = isset($readIni['etcsteel']) ? (int)str_replace(',', '', $readIni['etcsteel']) : 0;
$I3HL304 = isset($readIni['I3HL304']) ? (int)str_replace(',', '', $readIni['I3HL304']) : 0;
$I3MR304 = isset($readIni['I3MR304']) ? (int)str_replace(',', '', $readIni['I3MR304']) : 0;

$price_per_kg = [
    'CR' => $CR,
    'PO' => $PO,
    'EGI' => $EGI,
    '304 HL' => $HL304,
    '201 2B MR' => $MR201,
    '201 MR' => $MR201,
    '201 HL' => $HL201,
    '304 MR' => $MR304,
    'etcsteel' => $etcsteel,
    'I3HL304' => $I3HL304,
    'I3MR304' => $I3MR304
];

// 소장/기타/소재/업체 체크 제외인 경우
if ($exclude_bad_choice !== 'false') {
    $sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재' AND bad_choice != '소장' AND bad_choice != '업체' AND bad_choice != '기타')
            AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
            ORDER BY year, month";
} else {
    $sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice
            FROM mirae8440.steel
            WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재')
            AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
            ORDER BY year, month";
}

$total_sum = 0;

try{
    $stmh = $pdo->query($sql);

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

		$total_price = ($weight * $price * $steelnum * 7.93)/1000;
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

// Compact Blue-themed UI Design for 3-Grid Column
echo '<style>
/* Compact Blue Theme Variables */
:root {
    --primary-blue: #2563eb;
    --secondary-blue: #3b82f6;
    --light-blue: #60a5fa;
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow: 0 4px 16px rgba(37, 99, 235, 0.1);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
}

/* Compact Stats Card for 3-Grid */
.compact-stats-card {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1rem;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.compact-stats-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
    border-radius: 16px 16px 0 0;
}

.compact-stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
}

/* Compact Badge */
.compact-badge {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
    margin-top: 0.5rem;
}

/* Compact Icon */
.compact-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--light-blue), var(--secondary-blue));
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    float: right;
}

.compact-icon svg {
    width: 16px;
    height: 16px;
    color: white;
}

/* Compact Typography */
.compact-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.compact-value {
    font-size: 1.25rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.compact-subtitle {
    color: var(--text-secondary);
    font-size: 0.625rem;
    font-weight: 500;
    line-height: 1.2;
}

/* Compact Chart Container */
.compact-chart-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem;
    margin: 0.5rem 0;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
}

.compact-chart-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.1);
}

.compact-chart-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.compact-chart-icon {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
}

.compact-chart-icon svg {
    width: 12px;
    height: 12px;
    color: white;
}

.compact-chart-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

/* Animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.compact-stats-card {
    animation: slideIn 0.3s ease-out;
}
</style>';

// Compact Statistics Card for 3-Grid Layout
echo '
<div class="compact-stats-card">
    <div class="compact-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="compact-title">' . date("Y") . '년 부적합 비용</div>
    <div class="compact-value">' . $formatted_total_sum . '<span style="font-size: 0.7em; color: var(--text-secondary);">원</span></div>
    <div class="compact-subtitle">원자재 부적합 집계 현황</div>    
</div>';

if ($Allmonth === 'true') {
    // 소장/업체 체크 제외인 경우
    if ($exclude_bad_choice === 'false') {
        $sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice
                FROM mirae8440.steel
                WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재')
                ORDER BY year, month";
    } else {
        $sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice
                FROM mirae8440.steel
                WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재' AND bad_choice != '소장' AND bad_choice != '업체' AND bad_choice != '기타')
                ORDER BY year, month";
    }
} else {
    if ($exclude_bad_choice === 'false') {
        $sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice
                FROM mirae8440.steel
                WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재')
                AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
                ORDER BY year, month";
    } else {
        $sql = "SELECT EXTRACT(YEAR FROM outdate) AS year, EXTRACT(MONTH FROM outdate) AS month, item, spec, steelnum, bad_choice
                FROM mirae8440.steel
                WHERE (bad_choice IS NOT NULL AND bad_choice != '' AND bad_choice != '해당없음' AND bad_choice != '소재' AND bad_choice != '소장' AND bad_choice != '업체' AND bad_choice != '기타')
                AND outdate BETWEEN date('$fromdate') AND date('$Transtodate')
                ORDER BY year, month";
    }
}

$monthly_totals = [];
$total_sum = 0;

try{
    $stmh = $pdo->query($sql);

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

		$total_price = ($weight * $price * $steelnum * 7.93)/1000;
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

<?php if($header == 'header') : // 초기화면에서 감춤 처리 ?>
<!-- Compact Chart Containers for 3-Grid Layout -->
<div class="compact-chart-container">
    <div class="compact-chart-header">
        <div class="compact-chart-icon">
            <svg fill="white" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
            </svg>
        </div>
        <h6 class="compact-chart-title">월별 추이</h6>
    </div>
    <div id="mychart" style="width: 100%; height: 120px;"></div>
</div>
<?php endif; ?>

<?php if($header == 'header') : // 초기화면에서 감춤 처리 ?>
<div class="compact-chart-container">
    <div class="compact-chart-header">
        <div class="compact-chart-icon">
            <svg fill="white" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h6 class="compact-chart-title">점유율</h6>
    </div>    
    <div id="myerrorchat" style="width: 100%; height: 120px;"></div>
</div>
<?php endif; ?>

<script>
$(document).ready(function(){

    // Modern Blue Color Palette for Charts
    const modernBlueColors = [
        '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd',
        '#c7d2fe', '#667eea', '#764ba2', '#1e40af'
    ];

    // PHP에서 계산된 월별/년도별 합계를 JavaScript로 전달
    const monthly_totals = <?php echo json_encode($monthly_totals); ?>;

    // x축 라벨 및 데이터 시리즈 준비
    const categories = Object.keys(monthly_totals);
    const data = Object.values(monthly_totals).map(total => parseFloat(total.toFixed(2)));

    // Ensure months are in order
    const sortedLabels = Object.keys(monthly_totals).sort((a, b) => new Date(a) - new Date(b));
    const sortedData = sortedLabels.map(label => monthly_totals[label]);

    // Compact Line Chart for 3-Grid Layout
    if (document.getElementById('mychart')) {
    Highcharts.chart('mychart', {
        chart: {
            type: 'line',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10],
            style: {
                fontFamily: '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif'
            }
        },
        title: {
            text: '',
            style: {
                display: 'none'
            }
        },
        xAxis: {
            categories: sortedLabels,
            labels: {
                formatter: function() {
                    const date = new Date(this.value + '-01');
                    return (date.getMonth() + 1) + '월';
                },
                style: {
                    color: '#64748b',
                    fontSize: '10px'
                }
            },
            lineColor: '#e2e8f0',
            tickColor: '#e2e8f0',
            gridLineWidth: 0
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            },
            labels: {
                style: {
                    color: '#64748b',
                    fontSize: '10px'
                },
                formatter: function() {
                    if (this.value >= 1000000) {
                        return Math.floor(this.value / 1000000) + 'M';
                    } else if (this.value >= 1000) {
                        return Math.floor(this.value / 1000) + 'K';
                    }
                    return this.value;
                }
            },
            gridLineColor: '#f1f5f9',
            gridLineWidth: 1
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e2e8f0',
            borderRadius: 8,
            shadow: false,
            style: {
                fontSize: '11px'
            },
            headerFormat: '<b>{point.key}</b><br/>',
            pointFormatter: function() {
                return Highcharts.numberFormat(this.y, 0, '.', ',') + '원';
            }
        },
        plotOptions: {
            line: {
                lineWidth: 2,
                marker: {
                    radius: 3,
                    fillColor: '#ffffff',
                    lineWidth: 2,
                    lineColor: '#2563eb',
                    symbol: 'circle'
                }
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: '부적합 비용',
            data: sortedData,
            color: '#2563eb'
        }],
        credits: {
            enabled: false
        }
    });
    }
});
</script>

<script>
$(document).ready(function(){

    // Modern Blue Color Palette for Pie Chart
    const modernBlueColors = [
        '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd',
        '#c7d2fe', '#667eea', '#764ba2', '#1e40af'
    ];

    // PHP에서 계산된 월별/년도별 합계를 JavaScript로 전달
    const monthly_totals = <?php echo json_encode($monthly_totals); ?>;

    // x축 라벨 및 데이터 시리즈 준비
    const data = Object.keys(monthly_totals).map((key, index) => {
        const date = new Date(key + '-01');
        const month = (date.getMonth() + 1) + '월';
        return {
            name: month,
            y: parseFloat(monthly_totals[key].toFixed(2)),
            color: modernBlueColors[index % modernBlueColors.length]
        };
    });

    // myerrorchat 요소가 존재할 때만 차트 실행
    if (document.getElementById('myerrorchat')) {
        Highcharts.chart('myerrorchat', {
            chart: {
                type: 'pie',
                backgroundColor: 'transparent',
                spacing: [5, 5, 5, 5],
                style: {
                    fontFamily: '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif'
                }
            },
            title: {
                text: '',
                style: {
                    display: 'none'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                borderColor: '#e2e8f0',
                borderRadius: 8,
                shadow: false,
                style: {
                    fontSize: '11px'
                },
                pointFormatter: function() {
                    return '<b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + '원</b><br/>' +
                        '점유율: ' + this.percentage.toFixed(1) + '%';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    size: '90%',
                    innerSize: '50%',
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        distance: 10,
                        style: {
                            fontWeight: '500',
                            fontSize: '9px',
                            color: '#1e293b',
                            textOutline: 'none'
                        },
                        format: '{point.name}<br>{point.percentage:.0f}%'
                    }
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '부적합 비용',
                data: data
            }],
            credits: {
                enabled: false
            }
        });
    }
});
</script> 