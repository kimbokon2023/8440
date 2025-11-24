<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';

ini_set('display_errors', '0');  // 화면에 warning 없애기

// 모바일 사용여부 확인하는 루틴
$mAgent = array("iPhone", "iPod", "Android", "Blackberry", 
    "Opera Mini", "Windows ce", "Nokia", "sony");
$chkMobile = false;
for ($i = 0; $i < sizeof($mAgent); $i++) {
    if (stripos($_SERVER['HTTP_USER_AGENT'], $mAgent[$i])) {
        $chkMobile = true;
        break;
    }
}

// 환경파일 불러오기
$readIni = array();
$readIni = parse_ini_file(includePath("work/estimate.ini"), false);

// 초기 서버를 이동중에 저정해야할 변수들을 저장하면서 작업한다. 자료를 추가 불러올때 카운터 숫자등..
$init_read = array();
$init_read = parse_ini_file(includePath("work/estimate.ini"), false);

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 변수 초기화
$registedate = '';
$mcno = '';
$inputsum = '';
$outputsum = '';
$isEditMode = false;
$mode = '';

// 배열 변수 초기화
$input_arr = array();
$output_arr1 = array();
$output_arr2 = array();
$output_arr3 = array();
$output_arr4 = array();
$text_arr1 = array();
$text_arr2 = array();
$text_arr3 = array();
$text_arr4 = array();
$text_arr5 = array();

if ($num == '') {
    $registedate = date("Y-m-d");
    $mcno = '';
    $inputsum = '';
    $outputsum = '';
} else {
    // 값이 존재하면 수정모드
    $isEditMode = true; // 수정 모드 여부
    
    // 배열 변수들 안전하게 초기화
    $input_arr = isset($input_arr) && is_string($input_arr) ? explode(',', $input_arr) : array();
    $output_arr1 = isset($output_arr1) && is_string($output_arr1) ? explode(',', $output_arr1) : array();
    $output_arr2 = isset($output_arr2) && is_string($output_arr2) ? explode(',', $output_arr2) : array();
    $output_arr3 = isset($output_arr3) && is_string($output_arr3) ? explode(',', $output_arr3) : array();
    $output_arr4 = isset($output_arr4) && is_string($output_arr4) ? explode(',', $output_arr4) : array();
    
    // 지출부분 읽기
    $text_arr1 = isset($text1) && is_string($text1) ? explode(',', $text1) : array();
    $text_arr2 = isset($text2) && is_string($text2) ? explode(',', $text2) : array();
    $text_arr3 = isset($text3) && is_string($text3) ? explode(',', $text3) : array();
    $text_arr4 = isset($text4) && is_string($text4) ? explode(',', $text4) : array();
    $text_arr5 = isset($text5) && is_string($text5) ? explode(',', $text5) : array();

    // 배열의 각 요소가 0인 경우 공백으로 변경
    $text_arr1 = array_map(function($value) {
        return ($value == 0) ? '' : $value;
    }, $text_arr1);

    $text_arr2 = array_map(function($value) {
        return ($value == 0) ? '' : $value;
    }, $text_arr2);

    $text_arr3 = array_map(function($value) {
        return ($value == 0) ? '' : $value;
    }, $text_arr3);

    $text_arr4 = array_map(function($value) {
        return ($value == 0) ? '' : $value;
    }, $text_arr4);

    $text_arr5 = array_map(function($value) {
        return ($value == 0) ? '' : $value;
    }, $text_arr5);

    $mode = "modify";
}


// 합계 계산 변수 초기화
$total0 = 0;
$total1 = 0;
$total2 = 0;
$total3 = 0;
$total4 = 0;
$total5 = 0;

// 배열 합계 계산 (안전하게 처리)
$total1 += isset($text1) ? array_sum($text1) : 0;
$total2 += isset($text2) ? array_sum($text2) : 0;
$total3 += isset($text3) ? array_sum($text3) : 0;
$total4 += isset($text4) ? array_sum($text4) : 0;
$total5 += isset($text5) ? array_sum($text5) : 0;

$total0 += $total1 + $total2 + $total3 + $total4 + $total5;
?>

<?php include includePath('load_header.php'); ?>
<title>JAMB 단가</title>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastify -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<!-- Dashboard CSS -->
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/dashboard-style.css" type="text/css" />

<style>
/* Light & Subtle Theme - Estimate Specific */
/* ========================================= */

body {
    background: var(--gradient-primary);
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}


/* Side Banner */
.sideBanner {
    position: fixed;
    top: 20vh;
    left:75vw;
    width: 30vw;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap:1rem;
}

/* Tables - Light & Subtle Theme */
.table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--dashboard-shadow);
    margin-bottom: 0;
} 

.table th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    border: 1px solid var(--dashboard-border) !important;
    padding: 0.75rem !important;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}

.table td {
    font-size: 0.9rem !important;
    padding: 0.75rem !important;
    border: 1px solid var(--dashboard-border) !important;
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--dashboard-text) !important;
    vertical-align: middle;
    text-align: center;
}

.table tbody tr:hover td {
    background-color: var(--dashboard-hover) !important;
    transition: background-color 0.2s ease;
}

/* Form Controls */
.form-control {
    border: 1px solid var(--dashboard-border);
    background: white;
    color: var(--dashboard-text);
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
    outline: none;
}

.form-control.text-end {
    text-align: right;
    padding-right: 0.75rem;
}

/* Buttons - Light & Subtle Theme */
.btn-dark {
    background: var(--dashboard-accent) !important;
    color: white !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2) !important;
    padding: 0.5rem 1.25rem !important;
}

.btn-dark:hover {
    background: var(--dashboard-accent-light) !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3) !important;
}

.btn-secondary {
    background: var(--dashboard-secondary) !important;
    color: var(--dashboard-text) !important;
    border: 1px solid var(--dashboard-border) !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    padding: 0.5rem 1.25rem !important;
}

.btn-secondary:hover {
    background: #c7f0ff !important;
    color: var(--dashboard-text) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.15) !important;
}

.btn.rounded-pill {
    border-radius: 25px !important;
}

/* Responsive Design */
@media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
		font-size: 16px !important;
	}

	/* 컨테이너 패딩 조정 */
	.container-fluid {
		max-width: 100vw !important;
		padding: 10px !important;
		overflow-x: hidden !important;
	}

	/* 카드 모바일 최적화 */
	.modern-management-card {
		margin: 0.5rem 0 !important;
		width: 100% !important;
	}

	.modern-dashboard-header {
		padding: 0.75rem !important;
	}

	.modern-dashboard-header h3 {
		font-size: 0.95rem !important;
		margin: 0 !important;
		padding: 0.5rem !important;
	}

	.card-body {
		padding: 0.75rem !important;
	}

	/* 컬럼 모바일 최적화 */
	.col-sm-6 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* 테이블 모바일 최적화 */
	.table-responsive {
		font-size: 0.85rem !important;
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
		width: 100% !important;
		margin-bottom: 1rem !important;
	}

	.table {
		font-size: 0.85rem !important;
		width: 100% !important;
		min-width: 100% !important;
	}

	.table th {
		font-size: 0.8rem !important;
		padding: 0.6rem 0.4rem !important;
		white-space: nowrap !important;
		text-align: center !important;
	}

	.table td {
		font-size: 0.85rem !important;
		padding: 0.6rem 0.4rem !important;
		text-align: center !important;
		word-wrap: break-word !important;
	}

	/* 입력 필드 모바일 최적화 */
	.form-control {
		font-size: 0.85rem !important;
		padding: 0.5rem 0.6rem !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	.form-control.text-end {
		text-align: right !important;
		padding-right: 0.6rem !important;
	}

	/* 버튼 모바일 최적화 */
	.btn-dark,
	.btn-secondary {
		font-size: 0.85rem !important;
		padding: 0.5rem 1rem !important;
		white-space: nowrap !important;
	}

	.btn.rounded-pill {
		border-radius: 25px !important;
		padding: 0.5rem 1.2rem !important;
	}

	/* 사이드 배너 모바일 최적화 */
	.sideBanner {
		position: fixed !important;
		bottom: 20px !important;
		right: 20px !important;
		left: auto !important;
		width: auto !important;
		flex-direction: column !important;
		justify-content: center !important;
		gap: 0.75rem !important;
		z-index: 1000 !important;
		top: auto !important;
	}

	.sideBanner .btn {
		min-width: 80px !important;
		font-size: 0.85rem !important;
		padding: 0.5rem 1rem !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.row > [class*="col-"] {
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* vh-60 클래스 모바일 최적화 */
	.vh-60 {
		min-height: auto !important;
		padding: 1rem 0 !important;
	}
}
</style>
</head>

<body>	
	
	
<form id="board_form" name="board_form" class="form-signin" method="post">                      
	<input type="hidden" id="mode" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
	<input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
	<input type="hidden" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>">

<div class="container-fluid">
	<div class="row justify-content-center align-items-center vh-60">
		<div class="col-sm-6">
			<div class="modern-management-card">
				<div class="modern-dashboard-header">
					<h3 style="color: #000; margin: 0; font-size: 1.1rem; font-weight: 600;">쟘 제품 단가</h3>
				</div>
				<div class="card-body" style="padding: 1.0rem;">
					<div class="table-responsive">
						<table id="table2" class="table table-bordered tabls-sm">
    <thead>
        <tr>
            <th rowspan="2">구분</th>
            <th colspan="2">재질</th>
        </tr>
        <tr>
            <th style="width:30%;">H/L</th>
            <th style="width:30%;">기타</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>막판유</td>
            <td>
                <input type="text" class="form-control text-end" name="WJ_HL" value="<?php echo htmlspecialchars($readIni['WJ_HL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-separator="," />
            </td>
            <td>
                <input type="text" class="form-control text-end" name="WJ" value="<?php echo htmlspecialchars($readIni['WJ'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-separator="," />
            </td>
        </tr>
        <tr>
            <td>막판무</td>
            <td>
                <input type="text" class="form-control text-end" name="NJ_HL" value="<?php echo htmlspecialchars($readIni['NJ_HL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-separator="," />
            </td>
            <td>
                <input type="text" class="form-control text-end" name="NJ" value="<?php echo htmlspecialchars($readIni['NJ'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-separator="," />
            </td>
        </tr>
        <tr>
            <td>쪽쟘</td>
            <td>
                <input type="text" class="form-control text-end" name="SJ_HL" value="<?php echo htmlspecialchars($readIni['SJ_HL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-separator="," />
            </td>
            <td>
                <input type="text" class="form-control text-end" name="SJ" value="<?php echo htmlspecialchars($readIni['SJ'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-separator="," />
            </td>
        </tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="sideBanner">
		<!-- <div class="mb-1 mt-1 fs-3">
			<button type="button" class="btn btn-dark rounded-pill saveBtn fs-1">저장</button>
		</div> -->
  
		<div class="mb-1 mt-1">
			<button type="button" class="btn btn-dark rounded-pill fs-6" id="saveButton">저장</button>
		</div>

		<div class="mb-1 mt-1">
			<button type="button" class="btn btn-secondary rounded-pill closeBtn fs-6">닫기</button>
		</div>
	</div>
</div>
</form>
<script> 

// 전자결재를 위해 띄우는 창
// 기본 위치(top)값
var floatPosition = parseInt($(".sideBanner").css('top'));

// scroll 인식
$(window).scroll(function() {
    // 모바일에선 나타나지 않게 하기  
    // 현재 스크롤 위치
    var currentTop = $(window).scrollTop();
    var bannerTop = currentTop + floatPosition + "px";

    // 이동 애니메이션
    $(".sideBanner").stop().animate({
        "top": bannerTop
    }, 400);
}).scroll();


$(document).ready(function() {
    $('.form-control').on('input', function() {
        var separator = $(this).data('separator');
        var value = $(this).val().replace(/\,/g, '');
        var parsedValue = parseInt(value);
        var formattedValue = isNaN(parsedValue) ? '' : parsedValue.toLocaleString();
        $(this).val(formattedValue);
    });

    var state = $('#state').val();
    // 처리완료인 경우는 수정하기 못하게 한다.

    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    $(".closeBtn").click(function() {
        // 저장하고 창닫기
        myalert("창 닫기!");
        opener.location.reload();
        window.close();
    });

    $('#saveButton').on('click', function() {
        $.ajax({
            url: "save_estimate.php",
            type: "post",
            data: $("#board_form").serialize(),
            success: function(data) {
                console.log(data);

                Toastify({
                    text: '저장되었습니다.',
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    });
}); // end of ready document


function myalert(str) {
    Toastify({
        text: str,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "center",
        backgroundColor: "#4fbe87",
        className: "toastify-content",
    }).showToast();

    setTimeout(function() {
        // 시간지연
    }, 1000);
}
</script>

</body>
</html>
