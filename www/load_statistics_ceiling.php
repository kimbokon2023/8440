<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

// 단가 정보 로드 (estimate.ini)
// (estimate.ini 파일에는 bon_unit_12, lc_unit_12, bon_unit_13to17, lc_unit_13to17 등의 키가 있어야 합니다)
$readIni = parse_ini_file("./ceiling/estimate.ini", false);

// DB 연결
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

// 날짜범위 설정: 오늘부터 12개월 전까지
$fromdate = date("Y-m-d", strtotime("-12 months"));
$todate   = date("Y-m-d");

// SQL 쿼리 실행 (ceiling 테이블)
$sql = "SELECT * FROM mirae8440.ceiling
        WHERE workday BETWEEN date('$fromdate') AND date('$todate')
        ORDER BY workday ASC";
try {
    $stmt = $pdo->query($sql);
} catch (PDOException $e) {
    die("오류: " . $e->getMessage()); 
}

$chartData = array(); 
$totalRevenue = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // 작업일 기준 월 추출 (예: 2024-05)
    $month = date("Y-m", strtotime($row["workday"]));

    // 천장 매출 계산을 위한 변수들
    $inseung   = intval($row["inseung"]);
    $bon_su    = intval($row["bon_su"]);  // 본천장 수량
    $lc_su     = intval($row["lc_su"]);   // L/C 수량

    // 인승에 따라 단가 선택 (13~17인승은 별도 단가, 그 외는 12인승 단가 사용)
    if ($inseung <= 12) {
        $bon_rate = intval($readIni["bon_unit_12"]);
        $lc_rate  = intval($readIni["lc_unit_12"]);
    } else if ($inseung >= 13 && $inseung <= 17) {
        $bon_rate = intval($readIni["bon_unit_13to17"]);
        $lc_rate  = intval($readIni["lc_unit_13to17"]);
    } else { // inseung >= 18
        $bon_rate = intval($readIni["bon_unit_12"]);
        $lc_rate  = intval($readIni["lc_unit_12"]);
    }

    // 한 행의 매출액 계산: 본천장 매출 + L/C 매출
    $rowSales = ($bon_su * $bon_rate) + ($lc_su * $lc_rate);
    $totalRevenue += $rowSales;

    // 월별 누적 매출액 합산
    if (!isset($chartData[$month])) {
        $chartData[$month] = 0;
    }
    $chartData[$month] += $rowSales;
}

// 최종 결과를 포맷팅하여 출력
$formatted_total_revenue = number_format($totalRevenue, 0, '.', ',');
$jsonChartData = json_encode($chartData);

// Compact Blue-themed UI Design for 3-Grid Column (Ceiling Statistics)
echo '<style>
/* Compact Blue Theme Variables for Ceiling */
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

/* Compact Stats Card for Ceiling */
.compact-ceiling-card {
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

.compact-ceiling-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
    border-radius: 16px 16px 0 0;
}

.compact-ceiling-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
}

/* Compact Badge for Ceiling */
.compact-ceiling-badge {
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

/* Compact Icon for Ceiling */
.compact-ceiling-icon {
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

.compact-ceiling-icon svg {
    width: 16px;
    height: 16px;
    color: white;
}

/* Compact Typography for Ceiling */
.compact-ceiling-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.compact-ceiling-value {
    font-size: 1.25rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.compact-ceiling-subtitle {
    color: var(--text-secondary);
    font-size: 0.625rem;
    font-weight: 500;
    line-height: 1.2;
}

/* Compact Chart Container for Ceiling */
.compact-ceiling-chart-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem;
    margin: 0.5rem 0;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
}

.compact-ceiling-chart-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.1);
}

.compact-ceiling-chart-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.compact-ceiling-chart-icon {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
}

.compact-ceiling-chart-icon svg {
    width: 12px;
    height: 12px;
    color: white;
}

.compact-ceiling-chart-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

/* Animation for Ceiling */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.compact-ceiling-card {
    animation: slideInUp 0.3s ease-out;
}
</style>';

// Compact Ceiling Statistics Card for 3-Grid Layout
echo '
<div class="compact-ceiling-card">
    <div class="compact-ceiling-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"/>
            <path d="M6 8a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zM6 11a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z"/>
        </svg>
    </div>
    <div class="compact-ceiling-title">12개월 천장 매출</div>
    <div class="compact-ceiling-value">' . $formatted_total_revenue . '<span style="font-size: 0.7em; color: var(--text-secondary);">천원</span></div>
    <div class="compact-ceiling-subtitle">본천장/L/C 매출 집계</div>    
</div>';
?>
<!-- Compact Chart Container for 3-Grid Layout -->
<div class="compact-ceiling-chart-container">
    <div class="compact-ceiling-chart-header">
        <div class="compact-ceiling-chart-icon">
            <svg fill="white" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
            </svg>
        </div>
        <h6 class="compact-ceiling-chart-title">월별 매출</h6>
    </div>
    <div id="salesChart_ceiling" style="width: 100%; height: 120px;"></div>
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
    
    // Compact Column Chart for 3-Grid Layout with Purple Theme
    Highcharts.chart('salesChart_ceiling', {
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
            color: '#2563eb' // Blue theme color
        }],
        credits: {
            enabled: false
        }
    });
});
</script>
