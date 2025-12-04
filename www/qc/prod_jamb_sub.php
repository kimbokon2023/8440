<?php require_once __DIR__ . '/../bootstrap.php';
$title_message = "실시간 JAMB 제조수량";

// 올해를 날자 기간으로 설정
$fromdate = date("Y") . "-01-01";
$todate = date("Y") . "-12-31";
$Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));

$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  

$sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$Transtodate')";

try {  
    $stmh = $pdo->query($sql);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {  
        $widejamb = $row["widejamb"];
        $normaljamb = $row["normaljamb"];
        $smalljamb = $row["smalljamb"];
        $workplacename = $row["workplacename"];
        
        // 불량이란 단어가 들어가 있는 수량은 제외
        if (stripos($workplacename, '불량') === false) {
            if ($widejamb) $sum1 += (int)$widejamb;
            if ($normaljamb) $sum2 += (int)$normaljamb;
            if ($smalljamb) $sum3 += (int)$smalljamb;
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$all_sum = $sum1 + $sum2 + $sum3 / 4;
$month_sum = array_fill(0, 12, 0);

for ($month = 1; $month <= 12; $month++) {
    $day = date("t", strtotime(date("Y") . "-$month-01"));
    $month_fromdate = sprintf("%04d-%02d-01", date("Y"), $month);
    $month_todate = sprintf("%04d-%02d-%02d", date("Y"), $month, $day);

    $sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$month_fromdate') AND date('$month_todate')";

    $sum1 = 0;
    $sum2 = 0;
    $sum3 = 0;

    try {
        $stmh = $pdo->query($sql);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $widejamb = $row["widejamb"];
            $normaljamb = $row["normaljamb"];
            $smalljamb = $row["smalljamb"];
            $workplacename = $row["workplacename"];

            if (stripos($workplacename, '불량') === false) {
                if ($widejamb) $sum1 += (int)$widejamb;
                if ($normaljamb) $sum2 += (int)$normaljamb;
                if ($smalljamb) $sum3 += (int)$smalljamb;
            }
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }

    $month_sum[$month - 1] = $sum1 + $sum2 + $sum3 / 4;
}
?>

<div class="container-fluid">  	
    <div class="card mt-2 mb-2">  
        <div class="card-body">  	
            <div class="card-header mt-1 mb-3 text-center" style="background-color:#cfe2ff;"> 		
                <span class="text-center fs-5"> <?=$title_message?> </span>
            </div>					

            <div class="row"> 	
                <div class="col-sm-8"> 	
                    <canvas id="myChart"  width="350" height="500"></canvas> 		   
                </div>	 
                <div class="col-sm-4">
                    <span class=''> (수량환산) 쪽쟘 4SET <br> 와이드 1SET로 환산함.</span> <br>

                    <table class='table table-bordered'>
                        <thead class="table-primary">
                            <tr>
                                <th class='text-center'>해당월</th>
                                <th class='text-center'>제작수량</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_monthly_production = 0;
                            for ($i = 0; $i < 12; $i++) {
                                $total_monthly_production += $month_sum[$i];
                                echo "<tr>";
                                echo "<td class='text-center'>" . ($i + 1) . "월</td>";
                                echo "<td class='text-end'>" . number_format($month_sum[$i]) . " (SET)</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                                <td class='text-center'><strong>합계</strong></td>
                                <td class='text-end'><strong><?=number_format($total_monthly_production)?> (SET)</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            datasets: [{
                label: '#월별 제작수량',
                data: <?php echo json_encode($month_sum); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
        }
    });
</script>
