<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$options = ["설계", "레이져", "V컷", "절곡", "운반중", "소장", "업체", "기타", "개발품"];

// 현재 연도 가져오기
$currentYear = date('Y');

try {
    foreach ($options as $bad_choice) {
        // 불량 유형에 해당하는 데이터만 가져오는 SQL 쿼리 (현재 연도에 해당하는 데이터만)
        $sql_bad_list = "
            SELECT 
                outdate,    
                outworkplace,    -- 현장명
                item,            -- 사용자재 재질
                spec,            -- 크기
                steelnum,        -- 수량
				model,      	 -- 쟘,천장 구분
                comment          -- 비고
            FROM mirae8440.steel
            WHERE which = '2' 
            AND bad_choice = :bad_choice
            AND YEAR(outdate) = :currentYear
            ORDER BY outdate DESC, num DESC
        ";

        $stmh_bad_list = $pdo->prepare($sql_bad_list);
        $stmh_bad_list->bindParam(':bad_choice', $bad_choice);
        $stmh_bad_list->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
        $stmh_bad_list->execute();

        $bad_list = $stmh_bad_list->fetchAll(PDO::FETCH_ASSOC);

        if (count($bad_list) > 0) {
            // 불량 유형에 해당하는 데이터가 있는 경우에만 테이블을 출력
            echo '<div class="card mt-3">';
            echo '<div class="card-header text-center text-danger fw-bold " >';
            echo '<h5 class="badge bg-danger fs-5">' . $bad_choice . '</h5>';
            echo '</div>';
            echo '<div class="card-body">';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-secondary">';
            echo '<tr>';
            echo '<th class="text-center">발생일</th>';
            echo '<th class="text-center">구분</th>';
            echo '<th class="text-center">현장명</th>';
            echo '<th class="text-center">사용자재 재질</th>';
            echo '<th class="text-center">크기</th>';
            echo '<th class="text-center">수량</th>';
            echo '<th class="text-center">비고</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($bad_list as $row) {
                echo '<tr>';
                echo '<td class="text-center">' . htmlspecialchars($row['outdate']) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($row['model']) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($row['outworkplace']) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($row['item']) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($row['spec']) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($row['steelnum']) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($row['comment']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>
