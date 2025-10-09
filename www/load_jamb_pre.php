<?php
require_once __DIR__ . '/bootstrap.php';
// 전일접수된 jamb 수량 및 매출가져오기

// 현재 날짜에서 전날을 계산
$yesterday = date("Y-m-d", strtotime("-1 day"));

// 접수일자로 접수수량 체크
$a = "where orderday='$yesterday' order by num desc";
$sql = "select * from mirae8440.work " . $a;

try {
    $stmh = $pdo->query($sql);
    $temp1 = $stmh->rowCount();
    $total_row = $temp1;
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$jamb_registedate = $total_row;

$a = "where workday='$yesterday' order by num desc";
$sql = "select * from mirae8440.work " . $a;

try {
    $stmh = $pdo->query($sql);
    $temp1 = $stmh->rowCount();
    $total_row = $temp1;

    $jamb_duedate = $total_row;

    $sum = array_fill(0, 4, 0);
    $out_sum = array_fill(0, 4, 0);
    $check_outsourcing = 0;

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include './work/_row.php';

        if (empty($outsourcing)) {
            $sum[0] += (int)$widejamb;
            $sum[1] += (int)$normaljamb;
            $sum[2] += (int)$smalljamb;
            $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
        } else {
            $out_sum[0] += (int)$widejamb;
            $out_sum[1] += (int)$normaljamb;
            $out_sum[2] += (int)$smalljamb;
            $out_sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
            $check_outsourcing = 1;
        }

		$prejamblist = "";

		if ($check_outsourcing) {
			if (isset($out_sum[3]) && !empty($out_sum[3]) && $out_sum[3] > 0) {
				$prejamblist .= "외주 ☞ " . $out_sum[3];
			}
			if (isset($out_sum[0]) && !empty($out_sum[0]) && $out_sum[0] > 0) {
				$prejamblist .= (empty($prejamblist) ? "" : " , ") . "막판 : " . $out_sum[0];
			}
			if (isset($out_sum[1]) && !empty($out_sum[1]) && $out_sum[1] > 0) {
				$prejamblist .= (empty($prejamblist) ? "" : " , ") . "막판(無) : " . $out_sum[1];
			}
			if (isset($out_sum[2]) && !empty($out_sum[2]) && $out_sum[2] > 0) {
				$prejamblist .= (empty($prejamblist) ? "" : " , ") . "쪽쟘 : " . $out_sum[2];
			}
		} else {
			if (isset($sum[3]) && !empty($sum[3]) && $sum[3] > 0) {
				$prejamblist .= "출고 : " . $sum[3];
			}
			if (isset($sum[0]) && !empty($sum[0]) && $sum[0] > 0) {
				$prejamblist .= (empty($prejamblist) ? "" : " , ") . "막판 : " . $sum[0];
			}
			if (isset($sum[1]) && !empty($sum[1]) && $sum[1] > 0) {
				$prejamblist .= (empty($prejamblist) ? "" : " , ") . "막판(無) : " . $sum[1];
			}
			if (isset($sum[2]) && !empty($sum[2]) && $sum[2] > 0) {
				$prejamblist .= (empty($prejamblist) ? "" : " , ") . "쪽쟘 : " . $sum[2];
			}
		}

        $state_work = 0;
        if ($row["checkbox"] == 0) $state_work = 1;
        if (substr($row["workday"], 0, 2) == "20") $state_work = 2;
        if (substr($row["endworkday"], 0, 2) == "20") $state_work = 3;

        $draw_done = "";
        if (substr($row["drawday"], 0, 2) == "20") {
            $draw_done = "OK";
            if ($designer != '')
                $draw_done = $designer;
        }
        if ($workday != '') $workday = substr($row["workday"], 5, 5);
        $materials = $material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;

        $workitem = "";
        if ($widejamb != "")
            $workitem = "막판" . $widejamb . " ";
        if ($normaljamb != "")
            $workitem .= "막(無)" . $normaljamb . " ";
        if ($smalljamb != "")
            $workitem .= "쪽쟘" . $smalljamb . " ";

        if ($checkstep !== '신규' && $outsourcing === '')
            $checkstep = '';
        else if ($outsourcing !== null)
            $checkstep = $checkstep . '(' . $outsourcing . ')';
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 전날 접수된 수량파악
$a = "where orderday='$yesterday' order by num desc";
$sql = "select * from mirae8440.work " . $a;

try {
    $stmh = $pdo->query($sql);
    $temp1 = $stmh->rowCount();
    $total_row = $temp1;

    $jamb_duedate = $total_row;

    $sum = array_fill(0, 4, 0);
    $out_sum = array_fill(0, 4, 0);
    $check_outsourcing = 0;
    
    $beforedayjamblist = ""; // 변수 초기화
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include './work/_row.php';

        if (empty($outsourcing)) {
            $sum[0] += (int)$widejamb;
            $sum[1] += (int)$normaljamb;
            $sum[2] += (int)$smalljamb;
            $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
        } else {
            $out_sum[0] += (int)$widejamb;
            $out_sum[1] += (int)$normaljamb;
            $out_sum[2] += (int)$smalljamb;
            $out_sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
            $check_outsourcing = 1;
        }

		$beforedayjamblist = "";

			if (isset($sum[3]) && !empty($sum[3]) && $sum[3] > 0) {
				$beforedayjamblist .= "접수 : " . $sum[3];
			}
			if (isset($sum[0]) && !empty($sum[0]) && $sum[0] > 0) {
				$beforedayjamblist .= (empty($beforedayjamblist) ? "" : " , ") . "막판 : " . $sum[0];
			}
			if (isset($sum[1]) && !empty($sum[1]) && $sum[1] > 0) {
				$beforedayjamblist .= (empty($beforedayjamblist) ? "" : " , ") . "막판(無) : " . $sum[1];
			}
			if (isset($sum[2]) && !empty($sum[2]) && $sum[2] > 0) {
				$beforedayjamblist .= (empty($beforedayjamblist) ? "" : " , ") . "쪽쟘 : " . $sum[2];
			}
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}


?>
