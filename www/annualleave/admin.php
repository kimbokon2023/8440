<?php
require_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$menu = $_SESSION["menu"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? '';

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    // 로컬, 서버 환경 모두에서 동작하도록 document root 기반 경로 사용
    header("Location:" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://")
        . $_SERVER['HTTP_HOST']
        . "/login/logout.php");
    exit;
}
?>

<?php include getDocumentRoot() . '/load_header.php' ?>
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
        .fs-6 {
            font-size: 0.8rem !important;
            margin: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
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
        #backBtn,
        #csvDownload,
        #massBtn {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            min-width: 50px !important;
            max-width: 100% !important;
        }

        /* 연도 선택 및 체크박스 모바일 최적화 */
        #year,
        .form-select {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.4rem !important;
            max-width: 100px !important;
            box-sizing: border-box !important;
        }

        .form-check-label {
            font-size: 0.7rem !important;
        }

        .form-check-input {
            width: 1rem !important;
            height: 1rem !important;
        }

        /* 배지 모바일 최적화 */
        .badge {
            font-size: 0.65rem !important;
            padding: 0.2rem 0.4rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 테이블 모바일 최적화 - 카드 형식으로 변환 */
        .table.table-hover {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }

        .table.table-hover tbody {
            display: block !important;
            width: 100% !important;
        }

        .table.table-hover tbody tr {
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

        .table.table-hover tbody tr:hover {
            background: #f8f9fa !important;
            box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1) !important;
        }

        .table.table-hover tbody tr td {
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

        .table.table-hover tbody tr td:last-child {
            border-bottom: none !important;
        }

        .table.table-hover tbody tr td {
            position: relative !important;
        }

        .table.table-hover tbody tr td::before {
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
        .table.table-hover thead {
            display: none !important;
        }

        /* 페이지네이션 모바일 최적화 */
        .row.row-cols-auto {
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 0.25rem !important;
            font-size: 0.7rem !important;
        }

        .row.row-cols-auto button,
        .row.row-cols-auto span {
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

<?php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열로 기본정보 불러옴
include "load_DB.php";

// load_DB.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
$basic_name_arr = $basic_name_arr ?? array();
$totalname_arr = $totalname_arr ?? array();
$totalused_arr = $totalused_arr ?? array();
$totalusedYear_arr = $totalusedYear_arr ?? array();
?>

<title> 직원 연차 관리 </title>

<body>

    <?php if ($menu !== 'no') require_once(includePath('myheader.php')); ?>

    <?php
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 요청 파라미터 초기화
    $search = $_REQUEST["search"] ?? '';
    $mode = $_REQUEST["mode"] ?? '';
    $list = $_REQUEST["list"] ?? 0;
    $page = $_REQUEST["page"] ?? 1;
    $year = $_REQUEST["year"] ?? date("Y");
    $showRetired = $_POST['showRetired'] ?? 0;
    $find = $_REQUEST["find"] ?? '';
    $fromdate = $_REQUEST["fromdate"] ?? '';
    $todate = $_REQUEST["todate"] ?? '';
    $up_fromdate = $_REQUEST["up_fromdate"] ?? '';
    $up_todate = $_REQUEST["up_todate"] ?? '';
    $separate_date = $_REQUEST["separate_date"] ?? '';

    // 기타 변수 초기화
    $voc_alert = '';
    $ma_alert = '';
    $order_alert = '';

    $whereCondition = " WHERE referencedate = '$year' ";
    $andCondition = " AND referencedate = '$year' ";
    if (!$showRetired) {
        $whereCondition .= " AND (comment IS NULL or comment ='') ";
    } else {
        $whereCondition .= " AND comment = '퇴사' ";
    }

    $scale = 100;
    $page_scale = 15;
    $first_num = ($page - 1) * $scale;

    if ($mode == "search") {
        if (empty($search)) {
            $sql = "SELECT * FROM " . $DB . ".almember " . $whereCondition . " ORDER BY referencedate DESC, dateofentry ASC, num DESC LIMIT $first_num, $scale";
            $sqlcon = "SELECT * FROM " . $DB . ".almember " . $whereCondition . " ORDER BY referencedate DESC, dateofentry ASC, num DESC";
        } elseif (!empty($search)) {
            $sql = "SELECT * FROM " . $DB . ".almember WHERE (name LIKE '%$search%') OR (part LIKE '%$search%') OR (referencedate LIKE '%$search%')";
            $sql .= " " . $andCondition . " ORDER BY referencedate DESC, dateofentry ASC, num DESC LIMIT $first_num, $scale";
            $sqlcon = "SELECT * FROM " . $DB . ".almember WHERE (name LIKE '%$search%') OR (part LIKE '%$search%') OR (referencedate LIKE '%$search%') ";
            $sqlcon .= " " . $andCondition . " ORDER BY referencedate DESC, dateofentry ASC, num DESC";
        }
    } else {
        $sql = "SELECT * FROM " . $DB . ".almember " . $whereCondition . " ORDER BY referencedate DESC, dateofentry ASC, num DESC LIMIT $first_num, $scale";
        $sqlcon = "SELECT * FROM " . $DB . ".almember " . $whereCondition . " ORDER BY referencedate DESC, dateofentry ASC, num DESC";
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

        <form name="board_form" id="board_form" method="post" action="admin.php?mode=search&search=<?= $search ?>&find=<?= $find ?>&year=<?= $year ?>&search=<?= $search ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&up_fromdate=<?= $up_fromdate ?>&up_todate=<?= $up_todate ?>&separate_date=<?= $separate_date ?>">

            <div class="container">
                <div class="card mt-2 mb-4">
                    <div class="card-body">

                        <!-- Modal -->
                        <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog modal-lg modal-center">
                                <!-- Modal content-->
                                <div class="modal-content modal-lg">
                                    <div class="modal-header">
                                        <h4 class="modal-title">알림</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row gx-4 gx-lg-4 align-items-center">
                                            <br>
                                            <div id="alertmsg" class="fs-3"> </div> <br>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="closeModalBtn" type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                                    </div>
                                </div>
                            </div>
                        </div>         
   
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 					
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="5" > 		
    <input type="hidden" id="page" name="page" value="<?=$page?>"  > 
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>"  > 	
	

 <div class="d-flex mt-3 mb-3 justify-content-center align-items-center"> 	
	<span class="text-dark fs-6 me-1" > 년도별 연차 일자 입력/조회 </span>
	<span class="badge bg-secondary fs-6 me-2">  <i class="bi bi-layout-text-window"></i> 부서구분 : 제조파트, 지원파트 2개로 구분해서 사용함&nbsp;&nbsp;  </span>
	
<button type="button" id="backBtn" class="btn btn-outline-primary btn-sm me-2"  > <i class="bi bi-arrow-left"></i>  이전화면  </button> &nbsp;&nbsp;&nbsp;		 
 	
</div>
<div class="d-flex mt-3 mb-1 justify-content-center  align-items-center"> 	
    <!-- 퇴사자 체크박스 -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="showRetired" name="showRetired" value=1 onchange="filterRetired()" <?php echo isset($_POST['showRetired']) && $_POST['showRetired'] == 1 ? 'checked' : ''; ?>>
        <label class="form-check-label fs-6 me-2" for="showRetired">퇴사자 보기</label>
    </div>
    &nbsp;&nbsp;&nbsp;
			<select name="year" id="year"  class="form-select d-block w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			   <?php		    
				$current_year = date("Y"); // 현재 년도를 얻습니다.
				$year_arr = array(); // 빈 배열을 생성합니다.

				for ($i = 0; $i < 3; $i++) {
					$year_arr[] = $current_year - $i;
				}
			   for($i=0;$i<count($year_arr);$i++) {
					 if($year==$year_arr[$i])
								print "<option selected value='" . $year_arr[$i] . "'> " . $year_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $year_arr[$i] . "'> " . $year_arr[$i] .   "</option>";
			   } 		   
					?>	  
			</select> 					
			년도 &nbsp;

     &nbsp;&nbsp;&nbsp; <i class="bi bi-caret-right"></i> 총 <?= $total_row ?>개  &nbsp;&nbsp;&nbsp; 

	   <input type="text" name="search" id="search" class="form-control mx-1" style="font-size: 0.8rem; height: 32px; width:150px;" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" placeholder="검색어"> 	   
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm ms-1 me-1"  >  <i class="bi bi-search"></i> 검색 </button>	
		 <button type="button" class="btn btn-dark btn-sm ms-1 me-1" onclick="popupCenter('write_form.php', '신규', 600, 700);return false;" >  <i class="bi bi-pencil"></i>  신규 </button>
		 <button type="button" class="btn btn-dark btn-sm mx-1" id="csvDownload" >  <i class="bi bi-floppy-fill"></i> CSV  </button> 
		 <button  type="button" id="massBtn" class="btn btn-sm btn-primary mx-1"> <i class="bi bi-cloud-arrow-up"></i> 대량등록</button>	
			
		
	
</div>  
<div class="d-flex mt-3 mb-1 justify-content-center  align-items-center"> 	
    <table class="table table-hover" id="csvTable">
      <thead class="table-primary">
        <tr>
          <th class="text-center">번호</th>
          <th class="text-center">구분</th>
          <th class="text-center">직원이름</th>
          <th class="text-center">부서</th>
          <th class="text-center">입사일</th>
          <th class="text-center">해당연도</th>
          <th class="text-center">근속년</th>
          <th class="text-center">근속월</th>
          <th class="text-center">연차 발생일수</th>
          <th class="text-center">연차 사용일수</th>
          <th class="text-center">연차 잔여일수</th>
        </tr>
      </thead>
      <tbody>        
       
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
                        // rowDB.php에서 사용될 변수 초기화
                        $num = '';
                        $name = '';
                        $part = '';
                        $dateofentry = '';
                        $referencedate = '';
                        $availableday = 0;
                        $comment = '';

                        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                            include "rowDB.php";

                            $totalusedday = 0;
                            $totalremainday = $availableday;

                            for ($i = 0; $i < count($totalname_arr); $i++) {
                                // 해당년도가 같고 이름이 같으면 계산
                                if (isset($totalname_arr[$i]) && isset($totalusedYear_arr[$i]) &&
                                    trim($name) == trim($totalname_arr[$i]) && $referencedate == $totalusedYear_arr[$i]) {
                                    $totalusedday = isset($totalused_arr[$i]) ? $totalused_arr[$i] : 0;
                                    $totalremainday = floatval($availableday) - floatval($totalusedday);
                                }
                            }
		 			 
          
 
                        ?>

                            <tr onclick="popupCenter('write_form.php?num=<?= $num ?>', '데이터등록', 600, 700)">
                                <?php
                                // DateTime 객체로 변환
                                $entryDate = new DateTime($dateofentry);
                                $referenceDate = new DateTime($referencedate);

                                // 두 날짜 간의 차이 계산
                                $interval = $entryDate->diff($referenceDate);

                                // 총 년수 계산
                                $years = $interval->y;

                                // 총 월수 계산
                                $months = $interval->m;

                                // 근속년수 계산 (년 + (월 / 12)), 소수점 첫째 자리까지 반올림
                                $continueYear = round($years + ($months / 12), 1);

                                // 단순 월 계산
                                $continueMonth = intval($years) * 12 + $interval->m;
                                ?>
                                <td class="text-center" data-label="번호"><?= $start_num ?>	</td>
                                <td class="text-center" data-label="구분"><?= $comment ?>	</td>
                                <td class="text-center" data-label="직원이름"><?= $name ?>	    </td>
                                <td class="text-center" data-label="부서"><?= $part ?>   	</td>
                                <td class="text-center" data-label="입사일"><?= $dateofentry ?>	</td>
                                <td class="text-center" data-label="해당연도"><?= $referencedate ?>	</td>
                                <td class="text-center" data-label="근속년"><?= $continueYear ?>	</td>
                                <td class="text-center" data-label="근속월"><?= $continueMonth ?>	</td>
                                <td class="text-center text-primary" data-label="연차 발생일수"><b><?= $availableday ?></b>	</td>
                                <td class="text-center text-success" data-label="연차 사용일수"><b><?= $totalusedday ?></b>	</td>
                                <td class="text-center text-danger" data-label="연차 잔여일수"><b> <?= $totalremainday ?></b>	</td>
                            </tr>

                        <?php
                            $start_num--;
                        }
                    } catch (PDOException $ex) {
                        error_log("almember 조회 오류: " . $ex->getMessage());
                    }

                    // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
                    $start_page = ($current_page - 1) * $page_scale + 1;
                    // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
                    $end_page = $start_page + $page_scale - 1;  
                    ?>

                    </tbody>
                </table>
            </div>


            <div class="row row-cols-auto mt-4 justify-content-center align-items-center">
                <?php
                if ($page != 1 && $page > $page_scale) {
                    $prev_page = $page - $page_scale;
                    if ($prev_page <= 0)
                        $prev_page = 1;
                    print '<button class="btn btn-outline-secondary btn-sm" type="button" id=previousListBtn onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;';
                }
                for ($i = $start_page; $i <= $end_page && $i <= $total_page; $i++) {
                    if ($page == $i)
                        print '<span class="text-secondary">  ' . $i . '  </span>';
                    else
                        print '<button class="btn btn-outline-secondary btn-sm" type="button" id=moveListBtn onclick="javascript:movetoPage(' . $i . ')">' . $i . '</button> &nbsp;';
                }

                if ($page < $total_page) {
                    $next_page = $page + $page_scale;
                    if ($next_page > $total_page)
                        $next_page = $total_page;
                    print '<button class="btn btn-outline-secondary btn-sm" type="button" id=nextListBtn onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;';
                }
                ?>
            </div>


        </div>
    </div>
    </div>


        </form>

        <br>
        <br>
        <div class="container">
            <?php include '../footer_sub.php'; ?>
        </div>

</body>

</html>

<script>
    $(document).ready(function() {
        $('select[name="year"]').change(function() {
            var val = $('input[name="year"]:checked').val();
            document.getElementById('board_form').submit();
        });

        $("#massBtn").click(function() {
            popupCenter('write_form_init.php', '연초 대량등록', 420, 460);
        });

        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });

        $("#searchBtn").click(function() {
            document.getElementById('board_form').submit();
        });

        $("#backBtn").click(function() {
            location.href = '/annualleave/index.php';
        });

        $('a').children().css('textDecoration', 'none');
        $('a').parent().css('textDecoration', 'none');
    });

    function SearchEnter() {
        if (event.keyCode == 13) {
            document.getElementById('board_form').submit();
        }
    }

    function movetoPage(page) {
        $("#page").val(page);
        $("#board_form").submit();
    }

    document.getElementById("csvDownload").addEventListener("click", function() {
        var table = document.getElementById("csvTable");
        var theadRow = table.querySelector("thead tr");
        var rows = table.querySelectorAll("tbody tr");

        var csvRows = [];

        // Include the header row
        var headerData = [];
        theadRow.querySelectorAll("th").forEach(function(cell) {
            headerData.push(cell.textContent.trim());
        });
        csvRows.push(headerData.join(","));

        // Include the data rows
        rows.forEach(function(row) {
            var rowData = [];
            row.querySelectorAll("td").forEach(function(cell) {
                var cellValue = cell.textContent.trim();

                // 숫자인지 확인 (정수 또는 소수)
                if (/^\d+(\.\d+)?$/.test(cellValue)) {
                    cellValue = parseFloat(cellValue).toString();
                }

                rowData.push(cellValue);
            });
            csvRows.push(rowData.join(","));
        });

        var csvContent = csvRows.join("\n");
        var blob = new Blob(['\ufeff' + csvContent], {
            type: "text/csv;charset=utf-8;"
        });
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.setAttribute("download", "직원연차정보.csv");
        document.body.appendChild(link);
        link.click();
    });

    function filterRetired() {
        document.getElementById('board_form').submit();
    }
</script>