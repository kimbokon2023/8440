<?php 
header('Content-Type: application/json');
require_once __DIR__ . '/../bootstrap.php';

$month = $_POST['month'];
$year = $_POST['year'];
$search = $_POST['search'] ?? null;
$selectedFilter = $_POST['selectedFilter'];

$todo_data = array();
$leave_data = array();
$holiday_data = array();
$work_data = array();
$jamb_data = array();
$CL_data = array();
$OEM_data = array();
$radioarray = $_POST['radioarray'] ?? [];

function applySearchCondition($query, $columns, $search) {
    if ($search) {
        $searchConditions = [];
        foreach ($columns as $column) {
            $placeholder = "%" . $search . "%";
            $searchConditions[] = "$column LIKE '$placeholder'";
        }
        $query .= " AND (" . implode(" OR ", $searchConditions) . ")";
    }
    return $query;
}

// 라디오버튼 기타선택
try {
    // todos 테이블
    if ($selectedFilter == 'filter_etc' || $selectedFilter == 'filter_all') {
        $query = "SELECT num, orderdate, towhom, reply, deadline, work_status, title, first_writer, update_log, searchtag 
                  FROM " . $DB . ".todos 
                  WHERE is_deleted IS NULL";
        
        // 검색어가 있는 경우 날짜 조건을 제외
        if (!$search) {
            $query .= " AND MONTH(orderdate) = $month AND YEAR(orderdate) = $year";
        }
        
        $query = applySearchCondition($query, ['towhom', 'reply', 'title'], $search);
        $query .= " ORDER BY num DESC";

        $stmh = $pdo->query($query);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($todo_data, $row);
        }
    }

    // eworks 테이블
    if ($selectedFilter == 'filter_al' || $selectedFilter == 'filter_all') {
        $query = "SELECT author, al_askdatefrom, al_askdateto, al_item, al_content  
                  FROM " . $DB . ".eworks 
                  WHERE is_deleted IS NULL";
        
        // 검색어가 있는 경우 날짜 조건을 제외
        if (!$search) {
            $query .= " AND ((MONTH(al_askdatefrom) = $month AND YEAR(al_askdatefrom) = $year) 
                      OR (MONTH(al_askdateto) = $month AND YEAR(al_askdateto) = $year))";
        }

        $query = applySearchCondition($query, ['author', 'al_item', 'al_content'], $search);
        $query .= " ORDER BY num DESC";

        $stmh = $pdo->query($query);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($leave_data, $row);
        }
    }

    // holiday 데이터 가져오기 (날짜 필터만 적용)
    if (!$search) {
        $stmh = $pdo->query("SELECT num, startdate, enddate, comment 
                             FROM " . $DB . ".holiday 
                             WHERE is_deleted IS NULL 
                             AND ((MONTH(startdate) = $month AND YEAR(startdate) = $year) 
                             OR (MONTH(enddate) = $month AND YEAR(enddate) = $year))");

        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($holiday_data, $row);
        }
    }

    // work 테이블
    if ($selectedFilter == 'filter_jamb' || $selectedFilter == 'filter_jambCL' || $selectedFilter == 'filter_all') {
        $query = "SELECT workplacename, firstord, endworkday, workday, secondord, num, worker, checkstep, widejamb, normaljamb, smalljamb, deadline, address 
                  FROM mirae8440.work 
                  WHERE 1=1";

        if (!$search) {
            $query .= " AND ((MONTH(endworkday) = $month AND YEAR(endworkday) = $year) 
                      OR (MONTH(deadline) = $month AND YEAR(deadline) = $year))";
        }

        $query = applySearchCondition($query, ['workplacename', 'secondord', 'firstord', 'worker', 'checkstep', 'address'], $search);
        $query .= " ORDER BY num DESC";

        $stmh = $pdo->query($query);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($jamb_data, $row);
        }
    }

    // ceiling 테이블
    if ($selectedFilter == 'filter_CL' || $selectedFilter == 'filter_jambCL' || $selectedFilter == 'filter_all') {
        $query = "SELECT workplacename, address, deadline, secondord, firstord, num, bon_su, lc_su, etc_su, lcassembly_date, mainassembly_date, etcassembly_date, type, workday, main_draw, lc_draw, etc_draw  
                  FROM mirae8440.ceiling 
                  WHERE 1=1";

        if (!$search) {
            $query .= " AND ((MONTH(deadline) = $month AND YEAR(deadline) = $year) 
                      OR (MONTH(workday) = $month AND YEAR(workday) = $year))";
        }

        $query = applySearchCondition($query, ['workplacename', 'type', 'main_draw', 'lc_draw', 'etc_draw', 'address',  'secondord', 'firstord' ], $search);
        $query .= " ORDER BY num DESC";

        $stmh = $pdo->query($query);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($CL_data, $row);
        }
    }

    // outorder 테이블
    if ($selectedFilter == 'filter_OEM' || $selectedFilter == 'filter_all') {
        $query = "SELECT workplacename, deadline, secondord, firstord, address, workday, num 
                  FROM mirae8440.outorder 
                  WHERE 1=1";

        if (!$search) {
            $query .= " AND ((MONTH(deadline) = $month AND YEAR(deadline) = $year) 
                      OR (MONTH(workday) = $month AND YEAR(workday) = $year))";
        }

        $query = applySearchCondition($query, ['workplacename', 'secondord', 'firstord', 'address'], $search);
        $query .= " ORDER BY num DESC";

        $stmh = $pdo->query($query);
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            array_push($OEM_data, $row);
        }
    }

	// 통합 데이터 생성
	$integratedData = [];

	// jamb_data에서 데이터 통합
	foreach ($jamb_data as $row) {
		$integratedData[] = [
			"table" => "work", // 테이블 이름
			"num" => $row['num'],
			"workplacename" => $row['workplacename'],
			"address" => $row['address'] ?? '',  // address가 없을 경우 빈 값 처리
			"secondord" => $row['secondord'] ?? '',
			"deadline" => $row['workday'] ?? ''
		];
	}

	// CL_data에서 데이터 통합
	foreach ($CL_data as $row) {
		$integratedData[] = [
			"table" => "ceiling", // 테이블 이름
			"num" => $row['num'],
			"workplacename" => $row['workplacename'],
			"address" => $row['address'] ?? '' , // address가 없을 경우 빈 값 처리
			"secondord" => $row['secondord'] ?? '',
			"deadline" => $row['deadline'] ?? ''
		];
	}

	// OEM_data에서 데이터 통합
	foreach ($OEM_data as $row) {
		$integratedData[] = [
			"table" => "outorder", // 테이블 이름
			"num" => $row['num'],
			"workplacename" => $row['workplacename'],
			"address" => $row['address'] ?? '',
			"secondord" => $row['secondord'] ?? '',
			"deadline" => $row['deadline'] ?? ''
		];
	}	

	// deadline 역순으로 정렬
	usort($integratedData, function ($a, $b) {
		return strcmp($b['deadline'], $a['deadline']); // 역순 정렬
	});

    // 응답 데이터 구성
    $response = array(
        "todo_data" => $todo_data,
        "leave_data" => $leave_data,
        "work_data" => $work_data,
        "holiday_data" => $holiday_data,
        "jamb_data" => $jamb_data,
        "CL_data" => $CL_data,
        "OEM_data" => $OEM_data,
        "search" => $search,
        "integratedData" => $integratedData,
    );
	
	

    echo(json_encode($response, JSON_UNESCAPED_UNICODE));
} catch (PDOException $Exception) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'error' => 'Database error',
        'message' => $Exception->getMessage()
    ]);
}
?>
