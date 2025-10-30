<?php
require_once __DIR__ . '/../bootstrap.php';

// 캐시 제어 헤더
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// 에러 표시 설정
ini_set('display_errors', '0');

// 세션 변수 초기화
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "";

// REQUEST 변수 초기화
$SelectWork = isset($_REQUEST["SelectWork"]) ? $_REQUEST["SelectWork"] : '';
$searchOpt = isset($_REQUEST["searchOpt"]) ? $_REQUEST["searchOpt"] : '';
$partOpt = isset($_REQUEST["partOpt"]) ? $_REQUEST["partOpt"] : '1';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> 조명/천장 작업스케줄러(SCHEDULER) </title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/460/fabric.js"></script>
<style>
   @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
   
* {
    margin: 0;
    padding: 0
}

.custom_calendar_table td {
    text-align: center;
}

.custom_calendar_table thead.cal_date th {
    font-size: 2rem;
	text-align: center;
	height: 40px;
	margin-bottom: 15px;	
}

.custom_calendar_table thead.cal_date th button {
    font-size: 2rem;
    background: none;
    border: none;
}

.custom_calendar_table thead.cal_week th {
    background-color: #A0A0A0;
	font-size: 2.0rem;
	height: 60px;
	margin-bottom: 15px;
    color: #fff;
	text-align: center;
}
.custom_calendar_table tbody td:nth-child(1) {
    color: red;
	height: 100px;	
}

.custom_calendar_table tbody td:nth-child(7) {
    color: #288CFF;
	height: 700px;	
}

</style>

<?php
// 추가 REQUEST 변수 초기화
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : 0;
$scale = isset($_REQUEST["scale"]) ? $_REQUEST["scale"] : 10;
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : "";

// 기간 설정
$fromdate = isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : "";
$todate = isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : "";

if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

// 현재 날짜 및 시간
$now = date("Y-m-d");
$nowtime = date("H:i:s");

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 페이징 변수
$page_scale = 10;  // 한 페이지당 표시될 페이지 수
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번

// 기타 변수 초기화
$radiopart = "";  // 라디오 버튼 파트 옵션
$chkMobile = false;  // 모바일 체크 변수
?>



</head>

<body>

<? include '../myheader.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="container">
            <div class="d-flex align-items-center p-1 justify-content-center">
                <h1 class="h4 text-white">작업일정(생산예정일 기준 다음날)</h1>
            </div>
            
            <div class="d-flex align-items-center p-1 justify-content-center">
                <form name="Form">
                    <input type="hidden" id="fromdate" name="fromdate" value="2021-12-18">
                    <input type="hidden" id="todate" name="todate" value="2021-12-18">
                    <input type="hidden" id="weekend" name="weekend" value="">
                    <input type="hidden" id="partOpt" name="partOpt" value="<?=$partOpt?>">
                    <input type="hidden" id="weekcalandarVal" name="weekcalandarVal">
                </form>
                
                <span class="input-group-text bg-white">
                    <button type="button" class="button btn btn-secondary" onclick="week_calandar(-1)"> &lt; </button>
                    <button type="button" class="button btn btn-secondary" onclick="set_day()"> Today </button>
                    <button type="button" class="button btn btn-secondary" onclick="week_calandar(1)"> &gt; </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 업체별 출고량 주간 스케줄
                    <?php print $radiopart; ?>
                </span>
            </div>
            
            <div class="d-flex align-items-center p-1 justify-content-center">
                <div id="calandarTitle"></div>
                <div id="calwrap col1" style="float:left;width:50px;height:900px;margin-left:10px;"></div>
                <div id="calwrap col2" style="float:left;width:1750px;height:900px;">
                    <div id="calandar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<script>
// 전역 변수
let dayArray = new Array();
var nowDate = new Date();

// 캘린더 생성 함수
function calendarMaker(target, date) {
    if (date == null || date == undefined) {
        date = new Date();
    }
    nowDate = date;
    
    if ($(target).length > 0) {
        var year = nowDate.getFullYear();
        var month = nowDate.getMonth() + 1;
        var date = nowDate.getDate();
        var day = nowDate.getDay();
        $(target).empty().append(assembly(year, month, date));
    } else {
        console.error("custom_calendar Target is empty!!!");
        return;
    }
    
    var thisMonth = new Date(nowDate.getFullYear(), nowDate.getMonth(), 1);
    var thisLastDay = new Date(nowDate.getFullYear(), nowDate.getMonth() + 1, 0);
    
    var tag = "<tr>";
    var cnt = 0;
    
    // 주간 창 만들어주기 (7일간 캔버스 생성)
    for (var i = 0; i < 7; i++) {
        tag += "<td> <canvas id='myChart" + i + "' width='245' height='700'></canvas> </td>";
        cnt++;
    }
    
    $(target).find("#custom_set_date").append(tag);
    calMoveEvtFn();
    
    // 캘린더 HTML 조립 함수
    function assembly(year, month, date) {
        var calendar_html_code =
            "<table class='custom_calendar_table'>" +
            "<colgroup>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "</colgroup>" +
            "<thead class='cal_date'>" +
            "<th class='colTitle' colspan='7'> </th>" +
            "</thead>" +
            "<thead class='cal_week'>" +
            "<th>" + dayArray[0] + "(일)</th>" +
            "<th>" + dayArray[1] + "(월)</th>" +
            "<th>" + dayArray[2] + "(화)</th>" +
            "<th>" + dayArray[3] + "(수)</th>" +
            "<th>" + dayArray[4] + "(목)</th>" +
            "<th>" + dayArray[5] + "(금)</th>" +
            "<th>" + dayArray[6] + "(토)</th>" +
            "</thead>" +
            "<tbody id='custom_set_date'>" +
            "</tbody>" +
            "</table>";
        return calendar_html_code;
    }
    
    // 캘린더 이동 이벤트 함수
    function calMoveEvtFn() {
        // 전달 클릭
        $(".custom_calendar_table").on("click", ".prev", function () {
            nowDate = new Date(nowDate.getFullYear(), nowDate.getMonth() - 1, nowDate.getDate());
            calendarMaker($(target), nowDate);
        });
        
        // 다음달 클릭
        $(".custom_calendar_table").on("click", ".next", function () {
            nowDate = new Date(nowDate.getFullYear(), nowDate.getMonth() + 1, nowDate.getDate());
            calendarMaker($(target), nowDate);
        });
        
        // 일자 선택 클릭
        $(".custom_calendar_table").on("click", "td", function () {
            $(".custom_calendar_table .select_day").removeClass("select_day");
            $(this).removeClass("select_day").addClass("select_day");
        });
    }
}

// 주간 캘린더 함수
function week_calandar(week) {
    day.setDate(day.getDate() + week * 7);
    var title = day.getFullYear() + "-" + (day.getMonth() + 1) + "-" + day.getDate();
    $('#fromdate').val(title);
    
    var data = "";
    for (var i = 0; i < 7; i++) {
        data += day.getDate() + "|";
        dayArray[i] = day.getDate();
        var fromdate = day.getFullYear() + "-" + (day.getMonth() + 1) + "-" + dayArray[i];
        $('#fromdate').val(fromdate);
        $('#todate').val(fromdate);
        $('#weekend').val(i);  // 요일 정보 전달
        displayResult();  // PHP에 전송해서 결과값 받기
        day.setDate(day.getDate() + 1);
    }
    
    const tmp = day.getFullYear() + "-" + (day.getMonth() + 1) + "-" + (day.getDate() - 1);
    title += " ~ " + tmp;
    $('#todate').val(tmp);
    day.setDate(day.getDate() - 7);
    
    calendarMaker($("#calandar"), new Date());
    $('.colTitle').text(title);
    $('#weekcalandarVal').val(week);
}

// 오늘 날짜로 설정
function set_day() {
    day = new Date();
    day.setDate(day.getDate() - day.getDay());
    week_calandar(0);
}

// 데이터 표시 함수
function displayResult() {
    var phpSel = $('#partOpt').val();
    let urlSel;
    
    $.ajax({
        url: "workerdata.php",
        type: "post",
        data: $("Form").serialize(),
        dataType: "json"
    }).done(function(data) {
        console.log(data);
        const weekend = data["weekend"];
        drawGraph('all', weekend, data["date_arr"]);  // 배열로 전달
    });
}

// 문서 준비 이벤트
$(document).ready(function() {
    console.clear();
    
    // 라디오 버튼 변경 이벤트
    $('input[name="radiopart"]').change(function() {
        var temp = $(':radio[name="radiopart"]:checked').val();
        $("#partOpt").val(temp);
        week_calandar(0);
    });
    
    // tbody 내용 지우기
    $('#no2').click(function() {
        $("#input_data").empty();
    });
    
    // 오늘 날짜로 설정
    set_day();
    
    var day = new Date();
    day.setDate(day.getDate() - day.getDay());
    
    // 캔버스 엘리먼트 가져오기
    var ctx0 = document.getElementById('myChart0');
    var ctx1 = document.getElementById('myChart1');
    var ctx2 = document.getElementById('myChart2');
    var ctx3 = document.getElementById('myChart3');
    var ctx4 = document.getElementById('myChart4');
    var ctx5 = document.getElementById('myChart5');
    var ctx6 = document.getElementById('myChart6');
});

// 그래프 그리기 함수
function drawGraph(item, weekend, arr) {
    if (item == 'all') {
        console.log(arr);
        let canvas;
        canvas = new fabric.Canvas('myChart' + weekend);
        
        for (var i = 0; i < arr.length; i++) {
            if (i % 2 == 0) {
                var text1 = new fabric.Text(arr[i], { left: 50, top: 10 + i * 30, fontSize: 16 });
                text1.set({fill: '#0000FF'});
                canvas.add(text1);
            }
            if (i % 2 == 1) {
                var text1 = new fabric.Text(arr[i], { left: 50, top: 10 + i * 30, fontSize: 16 });
                text1.set({fill: '#000'});
                canvas.add(text1);
            }
        }
    }
}

// 오늘 날짜 가져오기 (YYYY-MM-DD 형태)
function getToday() {
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;  // 1월이 0으로 되기 때문에 +1
    var date = now.getDate();
    
    month = month >= 10 ? month : "0" + month;
    date = date >= 10 ? date : "0" + date;
    
    return today = "" + year + "-" + month + "-" + date;
}

// 현재 시간 가져오기 (HH:MM:SS 형태)
function getCurrentTime() {
    var today = new Date();
    
    var hours = ('0' + today.getHours()).slice(-2);
    var minutes = ('0' + today.getMinutes()).slice(-2);
    var seconds = ('0' + today.getSeconds()).slice(-2);
    
    var timeString = hours + ':' + minutes + ':' + seconds;
    return timeString;
}

// 밀리초를 시간으로 변환
function msToTime(duration) {
    var milliseconds = parseInt((duration % 1000) / 100),
        seconds = Math.floor((duration / 1000) % 60),
        minutes = Math.floor((duration / (1000 * 60)) % 60),
        hours = Math.floor((duration / (1000 * 60 * 60)) % 24);
    
    hours = (hours < 10) ? "0" + hours : hours;
    minutes = (minutes < 10) ? "0" + minutes : minutes;
    seconds = (seconds < 10) ? "0" + seconds : seconds;
    
    return hours + ":" + minutes + ":" + seconds + "." + milliseconds;
}

// 초를 시간으로 변환
function secToTime(duration) {
    var seconds = Math.floor(duration % 60),
        minutes = Math.floor((duration / 60) % 60),
        hours = Math.floor((duration / (60 * 60)) % 24);
    
    hours = (hours < 10) ? "0" + hours : hours;
    minutes = (minutes < 10) ? "0" + minutes : minutes;
    seconds = (seconds < 10) ? "0" + seconds : seconds;
    
    return hours + ":" + minutes + ":" + seconds;
}
</script>