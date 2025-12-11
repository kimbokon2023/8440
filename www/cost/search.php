<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>현장 검색</title>
</head>

<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$search = $_REQUEST["search"] ?? "";
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? "";
$find = $_REQUEST["find"] ?? "";
$process = $_REQUEST["process"] ?? "";
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? "";
$year = $_REQUEST["year"] ?? "";

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 페이지 설정
$scale = 1000;       // 한 페이지에 보여질 게시글 수
$page_scale = 10;    // 한 페이지당 표시될 페이지 수  10페이지
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

// SQL 쿼리 구성
$search_escaped = str_replace("'", "''", $search); // SQL Injection 기본 방어

$sql = "SELECT * FROM {$DB}.work 
        WHERE (workplacename LIKE '%{$search_escaped}%') 
           OR (firstordman LIKE '%{$search_escaped}%') 
           OR (secondordman LIKE '%{$search_escaped}%') 
           OR (chargedman LIKE '%{$search_escaped}%') 
           OR (delicompany LIKE '%{$search_escaped}%') 
           OR (hpi LIKE '%{$search_escaped}%') 
           OR (firstord LIKE '%{$search_escaped}%') 
           OR (secondord LIKE '%{$search_escaped}%') 
           OR (worker LIKE '%{$search_escaped}%') 
           OR (memo LIKE '%{$search_escaped}%') 
        ORDER BY num DESC 
        LIMIT {$first_num}, {$scale}";

$sqlcon = "SELECT * FROM {$DB}.work 
           WHERE (workplacename LIKE '%{$search_escaped}%') 
              OR (firstordman LIKE '%{$search_escaped}%') 
              OR (secondordman LIKE '%{$search_escaped}%') 
              OR (chargedman LIKE '%{$search_escaped}%') 
              OR (delicompany LIKE '%{$search_escaped}%') 
              OR (hpi LIKE '%{$search_escaped}%') 
              OR (firstord LIKE '%{$search_escaped}%') 
              OR (secondord LIKE '%{$search_escaped}%') 
              OR (worker LIKE '%{$search_escaped}%') 
              OR (memo LIKE '%{$search_escaped}%') 
           ORDER BY num DESC";
   
// 변수 초기화
$total_row = 0;
$total_page = 0;
$current_page = 1;
$start_num = 0;

try {
    $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
    $temp2 = $allstmh->rowCount();
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();
    
    $total_row = $temp2;     // 전체 글수
    
    $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
    $current_page = ceil($page / $page_scale); // 현재 페이지 블록 위치계산
    ?>
    
    <body onload="_no_Back();" onpageshow="if(event.persisted)_no_Back();">
        <div id="wrap">
            <div id="content">
                <form name="board_form" method="post" action="search.php?mode=search&search=<?= urlencode($search) ?>&find=<?= urlencode($find) ?>&process=<?= urlencode($process) ?>&yearcheckbox=<?= urlencode($yearcheckbox) ?>&year=<?= urlencode($year) ?>">
                    <div id="col2">
                        <div id="list_search">
                            <div id="list_search1" style="width:500px;">
                                ▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다. &nbsp; &nbsp;검색어 : <?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>
                                
                                <?php
                                if ($total_row == 0) {
                                    echo "<button type='button' id='search_directinput' class='button button2'>직접입력</button>";
                                }
                                ?>
                            </div>
                        </div> <!-- end of list_search -->

                        <br>
                        
                        <div class="limit">
                            <ul class="list-group">
                                <li class="list-row list-row--header">
                                    <div class="list-cell list-cell--50">번호</div>
                                    <div class="list-cell list-cell--250">현  장  명</div>
                                    <div class="list-cell list-cell--150">발 주 처</div>
                                    <div class="list-cell list-cell--100">발주처담당</div>
                                    <div class="list-cell list-cell--100">미래설치소장</div>
                                </li>
                            </ul>
                            
                            <br>
                            <div id="list_content">
                                <?php
                                if ($page <= 1) {
                                    $start_num = $total_row;    // 페이지당 표시되는 첫번째 글순번
                                } else {
                                    $start_num = $total_row - ($page - 1) * $scale;
                                }
                                
                                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                    $num = $row["num"];
                                    $checkstep = $row["checkstep"];
                                    $workplacename = $row["workplacename"];
                                    $address = $row["address"];
                                    $firstord = $row["firstord"];
                                    $firstordman = $row["firstordman"];
                                    $firstordmantel = $row["firstordmantel"];
                                    $secondord = $row["secondord"];
                                    $secondordman = $row["secondordman"];
                                    $secondordmantel = $row["secondordmantel"];
                                    $chargedman = $row["chargedman"];
                                    $orderday = $row["orderday"];
                                    $measureday = $row["measureday"];
                                    $drawday = $row["drawday"];
                                    $deadline = $row["deadline"];
                                    $delicompany = $row["delicompany"];
                                    $workday = $row["workday"];
                                    $startday = $row["startday"];
                                    $testday = $row["testday"];
                                    $worker = $row["worker"];
                                    $endworkday = $row["endworkday"];
                                    $material1 = $row["material1"];
                                    $material2 = $row["material2"];
                                    $material3 = $row["material3"];
                                    $material4 = $row["material4"];
                                    $material5 = $row["material5"];
                                    $material6 = $row["material6"];
                                    $widejamb = $row["widejamb"];
                                    $normaljamb = $row["normaljamb"];
                                    $smalljamb = $row["smalljamb"];
                                    $memo = $row["memo"];
                                    $regist_day = $row["regist_day"];
                                    $update_day = $row["update_day"];
                                    $demand = $row["demand"];
                                    ?>
                                    
                                    <li class="list-row">
                                        <a class="list-link" style="text-decoration:none;" href="#" onclick="intoval('<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>','<?= htmlspecialchars($worker, ENT_QUOTES, 'UTF-8') ?>'); return false;">
                                            <div class="list-cell list-cell--50"><?= $start_num ?></div>
                                            <div class="list-cell list-cell--250"><?= htmlspecialchars(substr($workplacename, 0, 80), ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="list-cell list-cell--150"><?= htmlspecialchars(substr($secondord, 0, 35), ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="list-cell list-cell--100"><?= htmlspecialchars(substr($secondordman, 0, 35), ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="list-cell list-cell--100"><?= htmlspecialchars(substr($worker, 0, 35), ENT_QUOTES, 'UTF-8') ?></div>
                                        </a>
                                    </li>
                                    
                                    <?php
                                    $start_num--;
                                }
} catch (PDOException $ex) {
    error_log("현장 검색 오류: " . $ex->getMessage());
}  
                                 // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
                                $start_page = ($current_page - 1) * $page_scale + 1;
                                // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
                                $end_page = $start_page + $page_scale - 1;
                                ?>
                                
                                <div id="page_button">
                                    <div id="page_num">
                                        <?php
                                        if ($page != 1 && $page > $page_scale) {
                                            $prev_page = $page - $page_scale;
                                            // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
                                            if ($prev_page <= 0) {
                                                $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
                                            }
                                            $prev_params = http_build_query(array('page' => $prev_page, 'search' => $search, 'find' => $find, 'list' => 1, 'process' => $process, 'yearcheckbox' => $yearcheckbox, 'year' => $year));
                                            echo "<a href='search.php?{$prev_params}'>◀ </a>";
                                        }
                                        
                                        for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                                            // [1][2][3] 페이지 번호 목록 출력
                                            if ($page == $i) {
                                                // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                                                echo "<font color='red'><b>[$i]</b></font>";
                                            } else {
                                                $page_params = http_build_query(array('page' => $i, 'search' => $search, 'find' => $find, 'list' => 1, 'process' => $process, 'yearcheckbox' => $yearcheckbox, 'year' => $year));
                                                echo "<a href='search.php?{$page_params}'>[$i]</a>";
                                            }
                                        }
                                        
                                        if ($page < $total_page) {
                                            $next_page = $page + $page_scale;
                                            if ($next_page > $total_page) {
                                                $next_page = $total_page;
                                            }
                                            // next_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
                                            $next_params = http_build_query(array('page' => $next_page, 'search' => $search, 'find' => $find, 'list' => 1, 'process' => $process, 'yearcheckbox' => $yearcheckbox, 'year' => $year));
                                            echo "<a href='search.php?{$next_params}'>▶</a><p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of col2 -->
                </form>
            </div> <!-- end of content -->
        </div> <!-- end of wrap -->
        
        <script>
            function check_level() {
                window.open("check_level.php?nick=" + document.member_form.nick.value, "NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
            }
            
            function intoval(name, worker) {
                $("#displaysearch").hide();
                document.getElementById("outworkplace").value = name;
                document.getElementById("model").value = '쟘';
                document.getElementById("comment").value = worker + ' 소장,';
                
                // $("#searchtel").trigger("click");    // 제이쿼리 클릭 이벤트 발생
            }
            
            $("#search_directinput").on("click", function () {
                $("#displaysearch").hide();
            });
        </script>
    </body>
</html>