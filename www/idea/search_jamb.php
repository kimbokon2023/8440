<?php
/**
 * Idea 현장명 검색 팝업 페이지 (jamb)
 * work 테이블에서 현장명을 검색합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? '';
$page = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;
$mode = $_REQUEST["mode"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '';
$year = $_REQUEST["year"] ?? '';

// 변수 초기화
$scale = 100;
$page_scale = 10;
$first_num = ($page - 1) * $scale;
$total_row = 0;
$total_page = 0;
$current_page = 1;
$start_page = 1;
$end_page = 1;

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL Injection 방지를 위한 Prepared Statement
$searchParam = '%' . $search . '%';

$sql = "SELECT * FROM {$DB}.work 
        WHERE workplacename LIKE ? OR firstordman LIKE ? OR secondordman LIKE ? 
        OR chargedman LIKE ? OR delicompany LIKE ? OR hpi LIKE ? 
        OR firstord LIKE ? OR secondord LIKE ? OR worker LIKE ? OR memo LIKE ? 
        ORDER BY num DESC 
        LIMIT ?, ?";

$sqlcon = "SELECT * FROM {$DB}.work 
           WHERE workplacename LIKE ? OR firstordman LIKE ? OR secondordman LIKE ? 
           OR chargedman LIKE ? OR delicompany LIKE ? OR hpi LIKE ? 
           OR firstord LIKE ? OR secondord LIKE ? OR worker LIKE ? OR memo LIKE ? 
           ORDER BY num DESC";

try {
    // 전체 개수 조회
    $allstmh = $pdo->prepare($sqlcon);
    for ($i = 1; $i <= 10; $i++) {
        $allstmh->bindValue($i, $searchParam, PDO::PARAM_STR);
    }
    $allstmh->execute();
    $total_row = $allstmh->rowCount();
    
    // 페이징 데이터 조회
    $stmh = $pdo->prepare($sql);
    for ($i = 1; $i <= 10; $i++) {
        $stmh->bindValue($i, $searchParam, PDO::PARAM_STR);
    }
    $stmh->bindValue(11, $first_num, PDO::PARAM_INT);
    $stmh->bindValue(12, $scale, PDO::PARAM_INT);
    $stmh->execute();
    
    $temp1 = $stmh->rowCount();
    
    $total_page = ceil($total_row / $scale);
    $current_page = ceil($page / $page_scale);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>현장명 검색 (작업지시)</title>
    
    <style>
        body {
            font-family: "Malgun Gothic", sans-serif;
            padding: 20px;
        }
        
        .search-result {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .search-result:hover {
            background-color: #f8f9fa;
        }
        
        #page_button {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
        }
        
        #page_num a {
            padding: 5px 10px;
            margin: 0 2px;
            text-decoration: none;
            color: #007bff;
        }
        
        #page_num a:hover {
            background-color: #e9ecef;
            border-radius: 3px;
        }
        
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .button2 {
            background-color: #008CBA;
        }
    </style>
</head>
<body>

<div class="input-group p-2 mb-1">
    <span class="input-group-text">
        ▷ 총 <?php echo htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8'); ?> 개. &nbsp; &nbsp;
        검색어 : <?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>
    </span>
    
    <?php
    if ($total_row == 0) {
        echo '&nbsp; <button type="button" id="search_directinput" class="button button2"> 직접입력 </button>';
    }
    ?>
</div>

<div class="input-group p-1 mb-1">
    <span class="input-group-text align-items-center" style="width:50px;">번호</span>
    <span class="input-group-text align-text-center" style="width:300px;">현 장 명</span>
    <span class="input-group-text text-center" style="width:110px;">미래소장</span>
</div>

<?php
if ($page <= 1) {
    $start_num = $total_row;
} else {
    $start_num = $total_row - ($page - 1) * $scale;
}

while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
    $num = $row["num"];
    $workplacename = $row["workplacename"] ?? '';
    $worker = $row["worker"] ?? '';
?>
    <div class="input-group p-1 mb-0 search-result">
        <span class="input-group-text align-items-center" style="width:50px;"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="input-group-text align-text-center" style="width:300px;">
            <a href="#" onclick="javascript:intoval('<?php echo htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8'); ?>'); return false;" style="font-size=10px;">
                <?php 
                $displayName = mb_substr($workplacename, 0, 20, 'utf-8');
                echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); 
                ?>&nbsp;
            </a>
        </span>
        <span class="input-group-text text-center" style="width:110px;">
            <?php 
            $displayWorker = substr($worker, 0, 10);
            echo htmlspecialchars($displayWorker, ENT_QUOTES, 'UTF-8'); 
            ?>&nbsp;
        </span>
    </div>
<?php
    $start_num--;
}

} catch (PDOException $ex) {
    error_log("DB query error in idea/search_jamb.php: " . $ex->getMessage());
    ?>
    <div class="alert alert-danger" role="alert">
        검색 중 오류가 발생했습니다.
    </div>
    <?php
}

// 페이지 구분 블럭의 첫 페이지 수 계산
$start_page = ($current_page - 1) * $page_scale + 1;
// 페이지 구분 블럭의 마지막 페이지 수 계산
$end_page = $start_page + $page_scale - 1;
?>

<div id="page_button">
    <div id="page_num">
        <?php
        // 이전 페이지 블록
        if ($page != 1 && $page > $page_scale) {
            $prev_page = $page - $page_scale;
            if ($prev_page <= 0) {
                $prev_page = 1;
            }
            
            $prevUrl = 'search_jamb.php?' . http_build_query(array(
                'page' => $prev_page,
                'search' => $search,
                'find' => $find,
                'list' => 1,
                'process' => $process,
                'yearcheckbox' => $yearcheckbox,
                'year' => $year
            ));
            
            echo '<a href="' . htmlspecialchars($prevUrl, ENT_QUOTES, 'UTF-8') . '">◀</a> ';
        }
        
        // 페이지 번호 목록
        for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
            if ($page == $i) {
                echo '<font color="red"><b>[' . $i . ']</b></font> ';
            } else {
                $pageUrl = 'search_jamb.php?' . http_build_query(array(
                    'page' => $i,
                    'search' => $search,
                    'find' => $find,
                    'list' => 1,
                    'process' => $process,
                    'yearcheckbox' => $yearcheckbox,
                    'year' => $year
                ));
                
                echo '<a href="' . htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') . '">[' . $i . ']</a> ';
            }
        }
        
        // 다음 페이지 블록
        if ($page < $total_page) {
            $next_page = $page + $page_scale;
            if ($next_page > $total_page) {
                $next_page = $total_page;
            }
            
            $nextUrl = 'search_jamb.php?' . http_build_query(array(
                'page' => $next_page,
                'search' => $search,
                'find' => $find,
                'list' => 1,
                'process' => $process,
                'yearcheckbox' => $yearcheckbox,
                'year' => $year
            ));
            
            echo '<a href="' . htmlspecialchars($nextUrl, ENT_QUOTES, 'UTF-8') . '"> ▶</a><p>';
        }
        ?>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    /**
     * 현장명 선택 함수
     * @param {string} name - 선택한 현장명
     */
    window.intoval = function(name) {
        if (window.opener && window.opener.document) {
            var placeInput = window.opener.document.getElementById('place');
            if (placeInput) {
                placeInput.value = name;
            }
            window.close();
        } else {
            alert('부모 창을 찾을 수 없습니다.');
        }
    };
    
    /**
     * 직접입력 버튼 (있는 경우)
     */
    var directInputBtn = document.getElementById('search_directinput');
    if (directInputBtn) {
        directInputBtn.addEventListener('click', function() {
            var inputName = prompt('현장명을 직접 입력하세요:');
            if (inputName && inputName.trim() !== '') {
                intoval(inputName);
            }
        });
    }
})();
</script>

</body>
</html>
