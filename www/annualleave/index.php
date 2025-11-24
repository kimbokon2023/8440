<?php
require_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? '';
$level = $_SESSION["level"] ?? 0;
$chkMobile = $_SESSION["chkMobile"] ?? false;

if (!isset($_SESSION["name"])) {
    $_SESSION["url"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://")
        . $_SERVER['HTTP_HOST']
        . '/annualleave/index.php?user_name=' . $user_name;
    sleep(1);
    header("Location:" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://")
        . $_SERVER['HTTP_HOST']
        . "/login/logout.php");
    exit;
}
$title_message = '직원 연차';
?>
<?php include getDocumentRoot() . '/load_header.php' ?>
<title> <?= $title_message ?> </title>
<style>
    /* 모바일 최적화 */
    @media (max-width: 768px) {
        /* body와 html의 width 제한 */
        html, body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            font-size: 16px !important;
        }

        /* 컨테이너 모바일 최적화 */
        .container,
        .container-fluid {
            max-width: 100vw !important;
            padding: 5px !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* 카드 모바일 최적화 */
        .card {
            margin: 0.25rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        .card-body {
            padding: 0.4rem 0.3rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        /* 제목 영역 모바일 최적화 */
        .fs-5 {
            font-size: 0.9rem !important;
            margin: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        h6 {
            font-size: 0.8rem !important;
            margin: 0.25rem 0 !important;
        }

        /* 버튼 영역 모바일 최적화 */
        .d-flex.justify-content-center,
        .d-flex.justify-content-end,
        .d-flex.justify-content-start {
            flex-wrap: wrap !important;
            gap: 0.2rem !important;
            padding: 0.3rem 0.25rem !important;
            margin: 0.25rem 0 !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.4rem !important;
            white-space: nowrap !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            flex: 0 0 auto !important;
            margin: 0.1rem !important;
        }

        .btn-sm {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.35rem !important;
        }

        /* 검색 영역 모바일 최적화 */
        #search {
            width: 100% !important;
            min-width: 120px !important;
            max-width: calc(100% - 100px) !important;
            font-size: 0.7rem !important;
            padding: 0.3rem 0.35rem !important;
            box-sizing: border-box !important;
            margin-right: 0.3rem !important;
        }

        #searchBtn,
        #writeBtn,
        #massBtn {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            min-width: 50px !important;
            max-width: 100% !important;
        }

        /* 연차 정보 테이블 모바일 최적화 */
        .table.table-bordered.text-center {
            font-size: 0.7rem !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .table.table-bordered.text-center td {
            padding: 0.3rem 0.4rem !important;
            font-size: 0.7rem !important;
        }

        .badge {
            font-size: 0.65rem !important;
            padding: 0.2rem 0.4rem !important;
        }

        /* 연도 선택 모바일 최적화 */
        #yearSelect {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.4rem !important;
            max-width: 100px !important;
            box-sizing: border-box !important;
        }

        /* 알림 배지 모바일 최적화 */
        .badge.bg-secondary,
        .text-secondary {
            font-size: 0.65rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 테이블 모바일 최적화 - 카드 형식으로 변환 */
        .table.table-bordered.table-hover {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }

        .table.table-bordered.table-hover tbody {
            display: block !important;
            width: 100% !important;
        }

        .table.table-bordered.table-hover tbody tr {
            display: block !important;
            width: 100% !important;
            margin-bottom: 0.4rem !important;
            background: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.075) !important;
            padding: 0.4rem !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
        }

        .table.table-bordered.table-hover tbody tr:hover {
            background: #f8f9fa !important;
            box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1) !important;
        }

        .table.table-bordered.table-hover tbody tr td {
            display: flex !important;
            width: 100% !important;
            padding: 0.3rem 0.4rem !important;
            text-align: left !important;
            border: none !important;
            border-bottom: 1px solid #f0f0f0 !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            flex-wrap: wrap !important;
        }

        .table.table-bordered.table-hover tbody tr td:last-child {
            border-bottom: none !important;
        }

        .table.table-bordered.table-hover tbody tr td {
            position: relative !important;
        }

        .table.table-bordered.table-hover tbody tr td::before {
            content: attr(data-label) ': ' !important;
            font-weight: bold !important;
            display: inline-block !important;
            min-width: 30% !important;
            margin-right: 0.5rem !important;
            color: #495057 !important;
            font-size: 0.75rem !important;
            flex-shrink: 0 !important;
        }

        /* 테이블 헤더 숨기기 (모바일) */
        .table.table-bordered.table-hover thead {
            display: none !important;
        }

        /* 페이지네이션 모바일 최적화 */
        .input-group {
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 0.25rem !important;
            font-size: 0.7rem !important;
        }

        .input-group a {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
            margin: 0.1rem !important;
            box-sizing: border-box !important;
        }

        /* 모든 요소가 카드 내부에 머물도록 */
        .card *,
        .container *,
        .container-fluid * {
            box-sizing: border-box !important;
            max-width: 100% !important;
        }

        .card button,
        .card .btn,
        .card span,
        .card input,
        .container button,
        .container .btn,
        .container span,
        .container input,
        .card-body *,
        .card-header * {
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }

        /* 카드 내부 모든 요소가 넘치지 않도록 */
        .card {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        .card-body {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        /* 폼 요소 모바일 최적화 */
        form {
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        form * {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 행 레이아웃 모바일 최적화 */
        .row {
            margin-left: -5px !important;
            margin-right: -5px !important;
        }

        .row > [class*="col-"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
    }
</style>
</head>

<body>
    <?php require_once(includePath('myheader.php')); ?>

    <?php
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 배열로 기본정보 불러옴
    include getDocumentRoot() . '/annualleave/load_DB.php';

    // load_DB.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
    $total = $total ?? 0;
    $thisyeartotalusedday = $thisyeartotalusedday ?? 0;
    $thisyeartotalremainday = $thisyeartotalremainday ?? 0;

    // 관리자 권한 확인
    $admin = 0;
    if ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵' || $user_name == '소민지') {
        $admin = 1;
    }
    // 요청 파라미터 초기화
    $search = $_REQUEST["search"] ?? '';
    $find = $_REQUEST["find"] ?? '';
    $year = $_REQUEST["year"] ?? date("Y");
    $fromdate = $_REQUEST["fromdate"] ?? '';
    $todate = $_REQUEST["todate"] ?? '';
    $up_fromdate = $_REQUEST["up_fromdate"] ?? '';
    $up_todate = $_REQUEST["up_todate"] ?? '';
    $separate_date = $_REQUEST["separate_date"] ?? '';

    // 기타 변수 초기화
    $voc_alert = '';
    $ma_alert = '';
    $order_alert = '';
    ?>

    <form name="board_form" id="board_form" method="post" action="index.php?mode=search&search=<?= $search ?>&find=<?= $find ?>&year=<?= $year ?>&search=<?= $search ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&up_fromdate=<?= $up_fromdate ?>&up_todate=<?= $up_todate ?>&separate_date=<?= $separate_date ?>">

        <input type="hidden" id="voc_alert" name="voc_alert" value="<?= $voc_alert ?>">
        <input type="hidden" id="ma_alert" name="ma_alert" value="<?= $ma_alert ?>">
        <input type="hidden" id="order_alert" name="order_alert" value="<?= $order_alert ?>">
        <input type="hidden" id="username" name="username" value="<?= $user_name ?>">

        <?php if ($chkMobile == false) { ?>
            <div class="container">
            <?php } else { ?>
                <div class="container-fluid">
                <?php } ?>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-2">
                            <span class="fs-5"> <?= $title_message ?> </span>
                            <button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'>  <i class="bi bi-arrow-clockwise"></i> </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if ($admin == 1) { ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="location.href='./admin.php'">
                                    <i class="bi bi-pencil-square"></i>
                                    관리자모드
                                </button>
                            <?php } ?>
                        </div>				
		<div class="d-flex justify-content-center align-items-center mt-3 mb-1">
			<h6 class="text-center mb-1"> 
				<div class="d-flex justify-content-center align-items-center mb-1">
					<select id="yearSelect" class="form-select form-select-sm d-inline w-auto">
						<?php
						$currentYear = date("Y");
						for ($year = $currentYear; $year >= $currentYear - 3; $year--) {
							echo "<option value='{$year}'" . ($year == $currentYear ? " selected" : "") . ">{$year}</option>";
						}
						?>
					</select>							
						년도 &nbsp; <?=$user_name?> 님							
				</div>
				<div class="d-flex justify-content-center align-items-center mb-1">				
					<table class="table table-bordered text-center">
						<tbody>
							<tr>
								<td class="table-success fs-6"><span class="badge bg-success">발생일수</span></td>
								<td class="table-primary fs-6"><span class="badge bg-primary">사용일수</span></td>
								<td class="table-secondary fs-6"><span class="badge bg-secondary">잔여일수</span></td>
							</tr>
							<tr>
								<td class="text-success fs-6" id="totalDays"><?=$total?></td>
								<td class="text-primary fs-6" id="usedDays"><?=$thisyeartotalusedday?></td>
								<td class="text-dark fs-6" id="remainingDays"><?=$thisyeartotalremainday?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</h6>
		</div>
	</div>
	<div class="d-flex justify-content-center mt-1 mb-1 " >
		<span class="badge bg-secondary fs-6 me-4"> 결재 진행중에는 수정, 삭제가 불가합니다. </span> <span class="text-secondary fs-6"> 진행순서 : 결재상신 -> 1차결재 -> 처리완료  </span>  	
	</div>    
<?php
 
                        $tablename = "eworks";

                        // 요청 파라미터 초기화 (이미 위에서 초기화했지만 여기서 다시 확인)
                        $mode = $_REQUEST["mode"] ?? '';
                        $list = $_REQUEST["list"] ?? 0;
                        $page = $_REQUEST["page"] ?? 1;

                        $scale = 50;
                        $page_scale = 15;
                        $first_num = ($page - 1) * $scale;

                        $AndisDeleted = " AND is_deleted IS NULL ";
                        $WhereisDeleted = " where is_deleted IS NULL ";

                        if ($mode == "search" || $mode == "") {
                            if ($search == "") {
                                if ($admin == 1) {
                                    $sql = "select * from " . $DB . "." . $tablename . $WhereisDeleted . " AND al_item IS NOT NULL AND al_item != '' order by al_askdatefrom desc, registdate desc limit $first_num, $scale";
                                    $sqlcon = "select * from " . $DB . "." . $tablename . $WhereisDeleted . " AND al_item IS NOT NULL AND al_item != '' order by al_askdatefrom desc, registdate desc";
                                } else {
                                    $sql = "select * from " . $DB . "." . $tablename . " where author like '%$user_name%' " . $AndisDeleted . " AND al_item IS NOT NULL AND al_item != '' order by al_askdatefrom desc, registdate desc limit $first_num, $scale";
                                    $sqlcon = "select * from " . $DB . "." . $tablename . " where author like '%$user_name%' " . $AndisDeleted . " AND al_item IS NOT NULL AND al_item != '' order by al_askdatefrom desc, registdate desc";
                                }
                            } elseif ($search != "") {
                                if ($admin == 1) {
                                    $sql = "select * from " . $DB . "." . $tablename . " where (author like '%$search%') " . $AndisDeleted . " AND al_item IS NOT NULL AND al_item != ''";
                                    $sql .= " order by al_askdatefrom desc, registdate desc limit $first_num, $scale";
                                    $sqlcon = "select * from " . $DB . "." . $tablename . " where (author like '%$search%') " . $AndisDeleted . " AND al_item IS NOT NULL AND al_item != ''";
                                    $sqlcon .= " order by al_askdatefrom desc, registdate desc";
                                } else {
                                    $sql = "select * from " . $DB . "." . $tablename . " where (author = '$user_name') and (author like '%$search%') " . $AndisDeleted . " AND al_item IS NOT NULL AND al_item != ''";
                                    $sql .= " order by al_askdatefrom desc, registdate desc limit $first_num, $scale";
                                    $sqlcon = "select * from " . $DB . "." . $tablename . " where (author = '$user_name') and (author like '%$search%') " . $AndisDeleted . " AND al_item IS NOT NULL AND al_item != ''";
                                    $sqlcon .= " order by al_askdatefrom desc, registdate desc";
                                }
                            }
                        }

                        // rowDBask.php에서 사용될 변수 초기화
                        $num = '';
                        $author_id = '';
                        $author = '';
                        $al_part = '';
                        $registdate = '';
                        $al_item = '';
                        $al_askdatefrom = '';
                        $al_askdateto = '';
                        $al_usedday = 0;
                        $al_content = '';
                        $status = '';

                        try {
                            $stmh = $pdo->query($sql);
                            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                include "rowDBask.php";
                            }
                        } catch (PDOException $ex) {
                            error_log("eworks 연차 조회 오류: " . $ex->getMessage());
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

                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="d-flex justify-content-end align-items-center mt-2 mb-2">
                                        &nbsp;&nbsp;&nbsp; ▷ <?= $total_row ?>  &nbsp;&nbsp;&nbsp;
                                        <input type="text" name="search" id="search" class="form-control me-1" style="width:180px;" value="<?= $search ?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" placeholder="검색어">
                                        <button type="button" id="searchBtn" class="btn btn-dark btn-sm ms-1 me-1">  <i class="bi bi-search"></i> 검색  </button>
                                        <button type="button" id="writeBtn" class="btn btn-dark btn-sm ms-1 me-1"> <i class="bi bi-pencil-square"></i> 신청 </button>
                                        <button type="button" id="massBtn" class="btn btn-sm btn-primary"> <i class="bi bi-cloud-arrow-up"></i> 대량등록</button>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="d-flex justify-content-end align-items-center mt-2 mb-2">
                                        <?php if ($level < 4) { ?>
                                            <button type="button" class="btn btn-dark btn-sm ms-3 me-1" onclick="popupCenter('batchDB.php','연차현황',1400,950);"> <i class="bi bi-grid-3x3"></i> 연차 현황 </button> &nbsp;
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">번호</th>
                                            <th class="text-center">접수일</th>
                                            <th class="text-center">시작일</th>
                                            <th class="text-center">종료일</th>
                                            <th class="text-center">구분</th>
                                            <th class="text-center">사용일수</th>
                                            <th class="text-center">성명</th>
                                            <th class="text-center">사유</th>
                                            <th class="text-center">결재상태</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($page <= 1)
                                            $start_num = $total_row;
                                        else
                                            $start_num = $total_row - ($page - 1) * $scale;

                                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                            include "rowDBask.php";

                                            switch ($status) {
                                                case 'send':
                                                    $statusstr = '결재상신';
                                                    break;
                                                case 'ing':
                                                    $statusstr = '결재중';
                                                    break;
                                                case 'end':
                                                    $statusstr = '결재완료';
                                                    break;
                                                default:
                                                    $statusstr = '';
                                                    break;
                                            }
                                        ?>
                                            <tr onclick="view('<?= $num ?>');">
                                                <td class="text-center" data-label="번호"><?= $start_num ?></td>
                                                <td class="text-center" data-label="접수일"><?= substr($registdate, 0, 10) ?></td>
                                                <td class="text-center" data-label="시작일"><?= $al_askdatefrom ?></td>
                                                <td class="text-center" data-label="종료일"><?= $al_askdateto ?></td>
                                                <td class="text-center" data-label="구분"><?= $al_item ?></td>
                                                <td class="text-center" data-label="사용일수"><?= $al_usedday ?></td>
                                                <td class="text-center" data-label="성명"><?= $author ?></td>
                                                <td class="text-center" data-label="사유"><?= $al_content ?></td>
                                                <td class="text-center" data-label="결재상태"><?= $statusstr ?></td>
                                            </tr>

                                        <?php
                                            $start_num--;
                                        }
                                    } catch (PDOException $ex) {
                                        error_log("eworks 연차 페이징 조회 오류: " . $ex->getMessage());
                                    }

                                    // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
                                    $start_page = ($current_page - 1) * $page_scale + 1;
                                    // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
                                    $end_page = $start_page + $page_scale - 1;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-5 mb-5">
                                <div class="input-group p-1 mb-2 mt-2 justify-content-center">
                                    <?php
                                    if ($page != 1 && $page > $page_scale) {
                                        $prev_page = $page - $page_scale;
                                        if ($prev_page <= 0)
                                            $prev_page = 1;
                                        print "<a href=index.php?page=$prev_page&mode=search&search=$search>◀ </a>";
                                    }
                                    for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                                        if ($page == $i)
                                            print "<font color=red><b>[$i]</b></font>";
                                        else
                                            print "<a href=index.php?page=$i&mode=search&search=$search>[$i]</a>";
                                    }
                                    if ($page < $total_page) {
                                        $next_page = $page + $page_scale;
                                        if ($next_page > $total_page)
                                            $next_page = $total_page;
                                        print "<a href=index.php?page=$next_page&mode=search&search=$search> ▶</a><p>";
                                    }
                                    ?>
                                </div>
                            </div>
                    </div>
                </div>
                </div>
    </form>
</body>


</html>

<script>
    function view(num) {
        popupCenter('write_form_ask.php?num=' + num + '&viewoption=1', '데이터등록', 650, 800);
    }

    $(document).ready(function() {
        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });

        $("#writeBtn").click(function() {
            popupCenter('write_form_ask.php', '등록/수정/삭제', 650, 600);
        });

        $("#massBtn").click(function() {
            popupCenter('write_form_mass.php', '대량등록', 650, 800);
        });

        $("#searchBtn").click(function() {
            document.getElementById('board_form').submit();
        });
    });

    function SearchEnter() {
        if (event.keyCode == 13) {
            document.getElementById('board_form').submit();
        }
    }

    $(document).ready(function() {
        $('#yearSelect').change(function() {
            var selectedYear = $(this).val();
            $.ajax({
                url: 'update_annual_leave.php',
                method: 'POST',
                data: {
                    year: selectedYear,
                    user_name: "<?= $user_name ?>"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#totalDays').text(response.total);
                        $('#usedDays').text(response.usedDays);
                        $('#remainingDays').text(response.remainingDays);
                    } else {
                        alert('데이터를 업데이트하는 동안 오류가 발생했습니다.');
                    }
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                    alert('서버 요청 중 오류가 발생했습니다.');
                }
            });
        });

        $('#yearSelect').trigger('change');
    });

    $(document).ready(function() {
        var title = '<?php echo $title_message; ?>';
        saveMenuLog(title);
    });
</script>

