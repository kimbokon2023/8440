<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

// 단가 정보 로드 (estimate.ini)
$readIni = parse_ini_file("./work/estimate.ini", false);

// 날짜범위 설정: 오늘부터 12개월 전까지
$fromdate = date("Y-m-d", strtotime("-12 months"));
$todate = date("Y-m-d");

// SQL 쿼리 실행 (work 테이블)
$sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$todate') ORDER BY workday ASC";
try {
    $stmt = $pdo->query($sql);
} catch (PDOException $e) {
    die("오류: " . $e->getMessage());
}

$chartData = array();
$totalRevenue = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // 작업일을 기준으로 월 추출 (예: 2024-05)
    $month = date("Y-m", strtotime($row["workday"]));

    // 불량품 제외 조건 (work/output_statis.php와 동일한 로직)
    $workplacename = $row["workplacename"];
    $findstr = '불량';
    $pos = stripos($workplacename, $findstr);

    if($pos == 0) {  // output_statis.php와 동일한 로직: 불량품 제외
        // 재질 정보를 결합하여 'HL' 포함 여부 판별
        $combinedMaterials = $row["material1"] . $row["material2"] . $row["material3"] . $row["material4"] . $row["material5"] . $row["material6"];
        $isHL = (stripos($combinedMaterials, 'HL') !== false || stripos($combinedMaterials, 'H/L') !== false);

        // 수량 정보
        $widejamb = intval($row["widejamb"]);
        $normaljamb = intval($row["normaljamb"]);
        $smalljamb = intval($row["smalljamb"]);

        // 단가 적용: HL이면 해당 단가, 아니면 기본 단가
        $WJ_price = $isHL ? intval($readIni["WJ_HL"]) : intval($readIni["WJ"]);
        $NJ_price = $isHL ? intval($readIni["NJ_HL"]) : intval($readIni["NJ"]);
        $SJ_price = $isHL ? intval($readIni["SJ_HL"]) : intval($readIni["SJ"]);

        // 한 행의 매출액 계산
        $rowRevenue = ($widejamb * $WJ_price) + ($normaljamb * $NJ_price) + ($smalljamb * $SJ_price);
        $totalRevenue += $rowRevenue;

        // 월별 누적 매출액 합산
        if (!isset($chartData[$month])) {
            $chartData[$month] = 0;
        }
        $chartData[$month] += $rowRevenue;
    }
}

// 최종 결과를 포맷팅하여 출력
$formatted_total_revenue = number_format($totalRevenue, 0, '.', ',');
$jsonChartData = json_encode($chartData);

// Compact Blue-themed UI Design for 3-Grid Column (Jamb Statistics)
echo '<style>
/* Compact Blue Theme Variables for Jamb */
:root {
    --primary-green: #059669;
    --secondary-green: #10b981;
    --light-green: #34d399;
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow: 0 4px 16px rgba(5, 150, 105, 0.1);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
}

/* Compact Stats Card for Jamb */
.compact-jamb-card {
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

.compact-jamb-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
    border-radius: 16px 16px 0 0;
}

.compact-jamb-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(5, 150, 105, 0.15);
}

/* Compact Badge for Jamb */
.compact-jamb-badge {
    background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    box-shadow: 0 2px 8px rgba(5, 150, 105, 0.2);
    margin-top: 0.5rem;
}

/* Compact Icon for Jamb */
.compact-jamb-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--light-green), var(--secondary-green));
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    float: right;
}

.compact-jamb-icon svg {
    width: 16px;
    height: 16px;
    color: white;
}

/* Compact Typography for Jamb */
.compact-jamb-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.compact-jamb-value {
    font-size: 1.25rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.compact-jamb-subtitle {
    color: var(--text-secondary);
    font-size: 0.625rem;
    font-weight: 500;
    line-height: 1.2;
}

/* Compact Chart Container for Jamb */
.compact-jamb-chart-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem;
    margin: 0.5rem 0;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
}

.compact-jamb-chart-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(5, 150, 105, 0.1);
}

.compact-jamb-chart-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.compact-jamb-chart-icon {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
}

.compact-jamb-chart-icon svg {
    width: 12px;
    height: 12px;
    color: white;
}

.compact-jamb-chart-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

/* Animation for Jamb */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.compact-jamb-card {
    animation: slideInRight 0.3s ease-out;
}
</style>';

// Compact Jamb Statistics Card for 3-Grid Layout
echo '
<div class="compact-jamb-card">
    <div class="compact-jamb-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
        </svg>
    </div>
    <div class="compact-jamb-title">12개월 Jamb 매출</div>
    <div class="compact-jamb-value">' . $formatted_total_revenue . '<span style="font-size: 0.7em; color: var(--text-secondary);">천원</span></div>
    <div class="compact-jamb-subtitle">막판/막(無)/쪽쟘 매출 집계</div>    
</div>';
?>
<!-- Compact Chart Container for 3-Grid Layout -->
<div class="compact-jamb-chart-container">
    <div class="compact-jamb-chart-header">
        <div class="compact-jamb-chart-icon">
            <svg fill="white" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
            </svg>
        </div>
        <h6 class="compact-jamb-chart-title">월별 매출</h6>
    </div>
    <div id="salesChart_jamb" style="width: 100%; height: 120px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var salesData = <?= $jsonChartData ?>;
	// 월별 데이터를 날짜순으로 정렬
	var sortedMonths = Object.keys(salesData).sort(function(a, b) {
		return new Date(a + '-01') - new Date(b + '-01');
	});
	var sortedRevenue = sortedMonths.map(function(month) {
		return parseFloat(salesData[month]);
	});
	
	// Compact Column Chart for 3-Grid Layout with Green Theme
	Highcharts.chart('salesChart_jamb', {
		chart: {
			type: 'column',
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
			categories: sortedMonths,
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
			column: {
				pointPadding: 0.1,
				borderWidth: 0,
				borderRadius: 4
			}
		},
		legend: {
			enabled: false
		},
		series: [{
			name: '매출액',
			data: sortedRevenue,
			color: '#059669' // Green theme color
		}],
		credits: {
			enabled: false
		}
	});
});
</script>
 