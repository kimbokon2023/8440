<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

$tablename = "eworks";
?>

<?php
include getDocumentRoot() . '/load_header.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$menu = $_REQUEST["menu"] ?? '';

// 권한 체크 (로컬과 서버 환경에 따라 다르게 처리)
if (!isset($_SESSION["level"])) {
    $isLocal = (
        isset($_SERVER['HTTP_HOST']) && 
        (
            $_SERVER['HTTP_HOST'] === 'localhost' ||
            $_SERVER['HTTP_HOST'] === '127.0.0.1'
        )
    );

    if ($isLocal) {
        // 로컬 개발환경: 로컬 로그인 페이지로 리디렉션
        $_SESSION["url"] = 'http://8440.local/absent/index.php?user_name=' . $user_name;
        sleep(1);
        header("Location:http://8440.local/login/login_form.php");
        exit;
    } else {
        // 서버 환경: 서버 사이트로 리디렉션
        $_SESSION["url"] = 'https://8440.co.kr/absent/index.php?user_name=' . $user_name;
        sleep(1);
        header("Location:https://8440.co.kr/login/logout.php");
        exit;
    }
}
?>

<title>공장 근태</title>
<body>

<?php if ($menu !== 'no') require_once(includePath('myheader.php')); ?>

<?php
// 요청 파라미터 수신
$num = $_REQUEST["num"] ?? '';
$year = $_REQUEST["year"] ?? date("Y");
$month = $_REQUEST["month"] ?? date("m");

// 검색 및 모드 관련 변수 초기화
$search = $_REQUEST["search"] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$page = $_REQUEST["page"] ?? 1;

// 모바일 체크 변수 초기화
$chkMobile = $_SESSION["chkMobile"] ?? false;

// 관리자 권한 체크
$admin = 0;
if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') {
    $admin = 1;
}

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 기본정보 불러옴
include "load_DB.php";

// 변수 초기화 (load_DB.php에서 정의되지 않은 경우 대비)
$basic_name_arr = $basic_name_arr ?? array();
$remainedAL = $remainedAL ?? array();

// json 연관배열을 만든 후 처리하기 위함
$basic_name_json = json_encode($basic_name_arr);
$remainedAL_json = json_encode($remainedAL);

$name_arr = array_unique($basic_name_arr);

// 현재 날짜를 가져옵니다
$today = date("Y-m-d");

// 제조파트에 해당되는 직원들의 근무를 파악하는 루틴
// 배열에 이름, 일자, 내용을 기록한다.
$view_name = array();
$view_date = array();
$view_item = array();
$view_contents = array();
$sum_overtime = array(); // 연장근로
$sum_holidaywork = array(); // 특근 합계
$sum_allovertime = array(); // 잔업 + 특근 합계

// rowDBask.php에서 사용될 변수 초기화
$name = '';
$askdatefrom = '';
$content = '';
$item = '';

try {
    $sql = "select * from mirae8440.absent";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
    $count = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include 'rowDBask.php';
        array_push($view_name, $name);
        array_push($view_date, $askdatefrom);
        array_push($view_contents, $content);
        array_push($view_item, $item);
    }
} catch (PDOException $ex) {
    error_log("absent 조회 오류: " . $ex->getMessage());
}
?>

<?php if ($chkMobile == false) { ?>
    <div class="container">
<?php } else { ?>
    <div class="container-fluid">
<?php } ?>
</div>

<form name="board_form" id="board_form" method="post" action="index.php">
    <div class="container-fluid">
        <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
            <h5>(현장 근태관리) -> 공장장</h5>
            <button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'><i class="bi bi-arrow-clockwise"></i></button>
        </div>

        <div class="row">
            <?php
            if ($chkMobile === true) {
                echo '<div class="col-sm-12">';
            } else {
                echo '<div class="col-sm-7">';
            }
            ?>

            <div class="card card-body">
                <div class="d-flex mt-3 mb-3 justify-content-center align-items-center">
                    <h5>년월 설정</h5>
                    <select name="year" id="year" class="form-select form-select-sm w-auto text-center mx-2">
                        <?php
                        $current_year = date("Y");
                        $year_arr = array();

                        for ($i = 0; $i < 3; $i++) {
                            $year_arr[] = $current_year - $i;
                        }

                        for ($i = 0; $i < count($year_arr); $i++) {
                            if ($year == $year_arr[$i]) {
                                echo "<option selected value='" . $year_arr[$i] . "'>" . $year_arr[$i] . "</option>";
                            } else {
                                echo "<option value='" . $year_arr[$i] . "'>" . $year_arr[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <select name="month" id="month" class="form-select form-select-sm w-auto text-center">
                        <?php
                        $month_arr = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
                        for ($i = 0; $i < count($month_arr); $i++) {
                            if ($month == $month_arr[$i]) {
                                echo "<option selected value='" . $month_arr[$i] . "'>" . $month_arr[$i] . "</option>";
                            } else {
                                echo "<option value='" . $month_arr[$i] . "'>" . $month_arr[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="table-responsive mt-3 mb-3 justify-content-center">
                    <table id="table_sum" class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <?php
                                // 직원 이름을 출력하는 열 출력
                                for ($i = 0; $i < count($name_arr) + 1; $i++) {
                                    if ($i === 0) {
                                        echo '<th scope="col" style="font-size:14px;">구분</th>';
                                    } else {
                                        echo '<th scope="col" style="font-size:14px;">' . $name_arr[$i - 1] . '</th>';
                                    }

                                    $sum_overtime[$i] = 0;
                                    $sum_holidaywork[$i] = 0;
                                    $sum_allovertime[$i] = 0;
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 현재 날짜를 가져옵니다
                            $today = date("Y-m-d");
                            // 요일을 출력합니다
                            $days = array("일", "월", "화", "수", "목", "금", "토");

                            // 해당 월의 마지막 날짜를 구합니다
                            $num_days = date("t", mktime(0, 0, 0, $month, 1, $year));

                            for ($i = 0; $i < $num_days + 5; $i++) {
                                echo '<tr class="text-center">';
                                if ($i === 0) {
                                    echo '<td class="text-primary">연장근로(시간) 합계</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td></td>';
                                    }
                                } else if ($i === 1) {
                                    echo '<td class="text-danger">특근(시간) 합계</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td></td>';
                                    }
                                } else if ($i === 2) {
                                    echo '<td class="text-dark">(잔업+특근) 합계</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td></td>';
                                    }
                                } else if ($i === 3) {
                                    echo '<td class="text-success">연차 잔여일수</td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td></td>';
                                    }
                                } else if ($i === 4) {
                                    echo '<td></td>';
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        echo '<td></td>';
                                    }
                                } else {
                                    $thisday = $days[date('w', strtotime($year . "-" . $month . "-" . ($i - 4)))];
                                    $pointday = date("Y-m-d", strtotime($year . "-" . $month . "-" . ($i - 4)));

                                    if ($thisday === '토' || $thisday === '일') {
                                        echo '<td class="text-danger">' . ($i - 4) . '(' . $thisday . ')</td>';
                                    } else {
                                        echo '<td>' . ($i - 4) . '(' . $thisday . ')</td>';
                                    }

                                    // 자료있는 숫자만큼 찾는다
                                    for ($j = 0; $j < count($name_arr); $j++) {
                                        // 연차 사용이력 추적
                                        $annualleave = '';

                                        // 연차등 사용한 것 있으면 화면에 보여준다
                                        try {
                                            $sql1 = "select * from mirae8440.eworks where is_deleted IS NULL and eworks_item='연차'";
                                            $stmh1 = $pdo->prepare($sql1);
                                            $stmh1->execute();

                                            while ($row = $stmh1->fetch(PDO::FETCH_ASSOC)) {
                                                $al_name = $row["author"];
                                                $al_item = $row["al_item"];
                                                $al_askdatefrom = $row["al_askdatefrom"];
                                                $al_askdateto = $row["al_askdateto"];

                                                if ($pointday >= $al_askdatefrom && $pointday <= $al_askdateto && $al_name === $name_arr[$j]) {
                                                    if ($thisday !== '토' && $thisday !== '일') {
                                                        $annualleave = $al_item;
                                                    }
                                                }
                                            }
                                        } catch (PDOException $ex) {
                                            error_log("연차 조회 오류: " . $ex->getMessage());
                                        }

                                        // 기본적으로 연차표시
                                        $printstr = '<td>' . $annualleave . '</td>';

                                        $addstr = '';
                                        for ($kk = 0; $kk < count($view_date); $kk++) {
                                            if ($view_name[$kk] === $name_arr[$j] && $view_date[$kk] === $pointday) {
                                                if ($view_contents[$kk] == '연장근로') {
                                                    $sum_overtime[$j] += (float)$view_item[$kk];
                                                    $sum_allovertime[$j] += (float)$view_item[$kk];
                                                    $addstr = '(야)';
                                                }
                                                if ($view_contents[$kk] == '특근') {
                                                    $sum_holidaywork[$j] += (float)$view_item[$kk];
                                                    $sum_allovertime[$j] += (float)$view_item[$kk];
                                                    $addstr = '(특)';
                                                }
                                                if ($view_contents[$kk] == '휴가') {
                                                    $addstr = '<span class="badge bg-danger">휴가</span>';
                                                }

                                                $printstr = '<td>' . $annualleave . $addstr . $view_item[$kk] . '</td>';
                                            }
                                        }

                                        echo $printstr;
                                    }
                                }

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <?php
        if ($chkMobile === true) {
            echo '<div class="col-sm-12">';
        } else {
            echo '<div class="col-sm-5">';
        }
        ?>

        <div class="card">
            <div class="d-flex mt-5 mb-3 justify-content-center align-items-center">
                <?php
                $scale = 35;       // 한 페이지에 보여질 게시글 수
                $page_scale = 15;   // 한 페이지당 표시될 페이지 수
                $first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

                if ($mode == "search" || $mode == "") {
                    if ($search == "") {
                        $sql = "select * from mirae8440.absent order by askdatefrom desc, name asc limit $first_num, $scale";
                        $sqlcon = "select * from mirae8440.absent order by askdatefrom desc";
                    } elseif ($search != "") {
                        $sql = "select * from mirae8440.absent where (name like '%$search%')";
                        $sql .= " order by askdatefrom desc limit $first_num, $scale";
                        $sqlcon = "select * from mirae8440.absent where (name like '%$search%')";
                        $sqlcon .= " order by askdatefrom desc";
                    }
                }

                try {
                    $stmh = $pdo->query($sql);
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        include "rowDBask.php";
                    }
                } catch (PDOException $ex) {
                    error_log("absent 조회 오류: " . $ex->getMessage());
                }

                try {
                    $allstmh = $pdo->query($sqlcon);
                    $temp2 = $allstmh->rowCount();
                    $stmh = $pdo->query($sql);
                    $temp1 = $stmh->rowCount();

                    $total_row = $temp2;
                    $total_page = ceil($total_row / $scale);
                    $current_page = ceil($page / $page_scale);
                    ?>

                    ▷ <?= $total_row ?> &nbsp;&nbsp;

                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form_ask.php', '등록/수정/삭제', 450, 520);return false;"><i class="bi bi-pencil"></i> 신규</button>
                    <input type="text" name="search" id="search" class="form-control me-1" style="width:150px;height:32px;" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off">
                    <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-1"><i class="bi bi-search"></i> 검색</button>
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('../annualleave/batchDB.php','직원 연차 list',1400,950);">직원 연차 list</button>
            </div>

            <div class="table-responsive mt-3 mb-3 justify-content-center">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">번호</th>
                            <th class="text-center">성명</th>
                            <th class="text-center">작업일</th>
                            <th class="text-center">근로형태</th>
                            <th class="text-center">작업시간</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($page <= 1) {
                            $start_num = $total_row;
                        } else {
                            $start_num = $total_row - ($page - 1) * $scale;
                        }

                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            include "rowDBask.php";
                            ?>
                            <tr onclick="popupCenter('write_form_ask.php?num=<?=$num?>', '연장근로 등록/수정/삭제', 450, 550);return false;">
                                <td class="text-center"><?=$start_num?></td>
                                <td class="text-center"><?=$name?></td>
                                <td class="text-center"><?=iconv_substr($askdatefrom, 5, 5, "utf-8")?></td>
                                <td class="text-center"><?=$content?></td>
                                <td class="text-center"><?=$item?></td>
                            </tr>
                            <?php
                            $start_num--;
                        }
                    } catch (PDOException $ex) {
                        error_log("absent 조회 오류: " . $ex->getMessage());
                    }

                    // 페이지 구분 블럭의 첫 페이지 수 계산
                    $start_page = ($current_page - 1) * $page_scale + 1;
                    // 페이지 구분 블럭의 마지막 페이지 수 계산
                    $end_page = $start_page + $page_scale - 1;
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex mt-2 mb-2 justify-content-center">
                <?php
                if ($page != 1 && $page > $page_scale) {
                    $prev_page = $page - $page_scale;
                    if ($prev_page <= 0) {
                        $prev_page = 1;
                    }
                    echo "<a href='index.php?page=$prev_page&mode=search&search=$search'>◀ </a>";
                }

                for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                    if ($page == $i) {
                        echo "<font color=red><b>[$i]</b></font>";
                    } else {
                        echo "<a href='index.php?page=$i&mode=search&search=$search'>[$i]</a>";
                    }
                }

                if ($page < $total_page) {
                    $next_page = $page + $page_scale;
                    if ($next_page > $total_page) {
                        $next_page = $total_page;
                    }
                    echo "<a href='index.php?page=$next_page&mode=search&search=$search'> ▶</a><p>";
                }
                ?>
            </div>
        </div>
    </div>

</div>
</div>
			

</form>
</div>

<script>
$(document).ready(function() {
    // PHP에서 JSON으로 변환된 배열
    var sum_overtime = <?php echo json_encode($sum_overtime); ?>;
    var sum_holidaywork = <?php echo json_encode($sum_holidaywork); ?>;
    var sum_allovertime = <?php echo json_encode($sum_allovertime); ?>;

    var basicNames = <?php echo $basic_name_json; ?>;
    var remainedAL_json = <?php echo $remainedAL_json; ?>;

    // 특정 테이블의 연장근로 상단에 표시하기
    sum_overtime.forEach(function(value, index) {
        if (value !== 0) {
            $("#table_sum tr:eq(1) td:eq(" + (index + 1) + ")").text(value);
        }
    });

    // 특정 테이블의 특근 상단에 표시하기
    sum_holidaywork.forEach(function(value, index) {
        if (value !== 0) {
            $("#table_sum tr:eq(2) td:eq(" + (index + 1) + ")").text(value);
        }
    });

    // 특정 테이블의 잔업 + 특근 상단에 표시하기
    sum_allovertime.forEach(function(value, index) {
        if (value !== 0) {
            $("#table_sum tr:eq(3) td:eq(" + (index + 1) + ")").text("(" + value + ")");
        }
    });

    // 특정 테이블의 잔여 연차일 상단에 표시하기
    basicNames.forEach(function(name, index) {
        var totalUsedDays = findTotalUsedDaysForName(name);
        if (totalUsedDays !== null) {
            $("#table_sum tr:eq(4) td:eq(" + (index + 1) + ")").text(totalUsedDays);
        }
    });

    // 이름에 해당하는 총 사용일 찾기
    function findTotalUsedDaysForName(name) {
        var index = basicNames.indexOf(name);
        if (index !== -1) {
            return remainedAL_json[index];
        }
        return null;
    }

    // 년도 선택 변경
    $('select[name="year"]').change(function() {
        document.getElementById('board_form').submit();
    });

    // 월 선택 변경
    $('select[name="month"]').change(function() {
        document.getElementById('board_form').submit();
    });

    // 모달 닫기
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    // 검색 버튼
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });

    // a 태그 전체 밑줄없앰
    $('a').children().css('textDecoration', 'none');
    $('a').parent().css('textDecoration', 'none');

    // 서버에 작업 기록
    saveLogData('공장 근태관리');
});

// 검색 엔터키 처리
function SearchEnter() {
    if (event.keyCode == 13) {
        document.getElementById('board_form').submit();
    }
}
</script>
</body>
</html>