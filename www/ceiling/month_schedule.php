<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;

// REQUEST/POST 변수 초기화
if (isset($_REQUEST["check"])) {
    $check = $_REQUEST["check"];
} else if (isset($_POST["check"])) {
    $check = $_POST["check"];
} else {
    $check = '1';
}

// 오늘 날짜 (YYYY-MM-DD 형식)
$today = date("Y-m-d");

// 페이지 제목
$title_message = '천장(ceiling) 스케줄';

// JavaScript에 today 변수 전달
echo "<script>var today = '$today';</script>";
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?=$title_message?></title>

</head>
<body>
<? include '../myheader.php'; ?>   
<style>
.red-day {
    color: red !important;
}
.today {
    background-color: #ffe5e5 !important;
}
</style>

<div class="card mt-2 mb-2">
    <div class="d-flex p-2 mt-3 mb-1 justify-content-center">
        <h3 id="clickableText_ceiling"><?=$title_message?></h3>
        <button type="button" class="btn btn-dark btn-sm mx-3" onclick="location.reload();" title="새로고침">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>
    
    <div id="holder_ceiling" class="d-flex p-2 justify-content-center"></div>
 
<script type="text/tmp1_ceiling" id="tmp1_ceiling">
  {{ 
  var date = date || new Date(),
      month = date.getMonth(), 
      year = date.getFullYear(), 
      first = new Date(year, month, 1), 
      last = new Date(year, month + 1, 0),
      startingDay = first.getDay(), 
      thedate = new Date(year, month, 1 - startingDay),
      dayclass = lastmonthcss,
      today = new Date(),
      i, j; 
  if (mode === 'week') {
    thedate = new Date(date);
    thedate.setDate(date.getDate() - date.getDay());
    first = new Date(thedate);
    last = new Date(thedate);
    last.setDate(last.getDate()+6);
  } else if (mode === 'day') {
    thedate = new Date(date);
    first = new Date(thedate);
    last = new Date(thedate);
    last.setDate(thedate.getDate() + 1);
  }
  
  }}  
  
<div class="card">	
<div class="card-body">	
  <table id="table" class="calendar-table table table-condensed table-tight" style="table-layout:fixed; width: 95%;">
    <thead>
      <tr>
        <td colspan="7" style="text-align: center">
          <table style="white-space: nowrap; width: 100%">
      <tr>
        <td colspan="7" style="text-align: center">
          <table style="white-space: nowrap; width: 100%">
            <tr class="justify-content-center">
				<td style="text-align: center;">
					<div class="d-flex justify-content-center align-items-center">
						<span class="btn-group">
							<button class="js-cal-prev btn btn-primary btn-lg me-1"> <i class="bi bi-arrow-left"></i> </button>
							<button class="js-cal-next btn btn-primary btn-lg me-1">  <i class="bi bi-arrow-right"></i>  </button>
						</span>
						<button class="js-cal-option btn btn-default me-5 {{: first.toDateInt() <= today.toDateInt() && today.toDateInt() <= last.toDateInt() ? 'active':'' }}" data-date="{{: today.toISOString()}}" data-mode="month">{{: todayname }}</button>
						<span class="badge bg-dark fs-6 me-5">{{: year }}년 {{: shortMonths[first.getMonth()] }} {{: first.getDate() }}일 - {{: shortMonths[last.getMonth()] }} {{: last.getDate() }}일 </span>
						<span class="badge bg-danger fs-6 me-2"> 결선완료 : 적색 </span>
						<span class="badge bg-primary fs-6 me-2"> 포장 미완료 : 청색 </span>
						<span class="badge bg-secondary fs-6 me-2"> 설계완료 : (설완) </span>
					</div>
				</td>

              
            </tr>
          </table>
          
        </td>
      </tr>
          </table>          
        </td>
      </tr>
	  
    </thead>
    {{ if (mode ==='year') {
      month = 0;
    }}
    <tbody>
      {{ for (j = 0; j < 3; j++) { }}
      <tr>
        {{ for (i = 0; i < 4; i++) { }}
        <td class="calendar-month month-{{:month}} js-cal-option" data-date="{{: new Date(year, month, 1).toISOString() }}" data-mode="month">
          {{: months[month] }}
          {{ month++;}}
        </td>
        {{ } }}
      </tr>
      {{ } }}
    </tbody>
    {{ } }}
    {{ if (mode ==='month' || mode ==='week') { }}  
	  <thead class="table table-primary">
		<tr class="c-weeks">
		  {{ for (i = 0; i < 7; i++) { }}
		   <!-- 토요일, 일요일 확인 (적색 표기를 위한)-->
		  {{ let isWeekend = (i === 0) || (i === 6); }}

			<th class="c-name {{: isWeekend ? 'red-day':'' }}">
			 {{: days[i] }}
			</th>
		  {{ } }}
		</tr>
	  </thead>
	   <tbody> 
    {{ for (j = 0; j < 5 && (j < 1 || mode === 'month'); j++) { }}
    <tr>
      {{ for (i = 0; i < 7; i++) { }}
      {{ if (thedate > last) { dayclass = nextmonthcss; } else if (thedate >= first) { dayclass = thismonthcss; } }}
	  
	  <!-- 토요일, 일요일 확인 (적색 표기를 위한)-->
	  {{ let dayOfWeek = thedate.getDay(); }}
	  {{ let isWeekend = (dayOfWeek === 6) || (dayOfWeek === 0); }}
	 
	 {{ var specificDates = ["2023-05-29","2023-06-05","2023-06-06","2023-07-27","2023-07-28","2023-07-31","2023-08-01"]; }}
		
  	 {{ let currentDate = thedate.getFullYear() + '-' + String(thedate.getMonth() + 1).padStart(2, '0') + '-' + String(thedate.getDate()).padStart(2, '0'); }}

	 {{ let isHoliday = specificDates.includes(currentDate); }}
	    <!-- 이코드가 휴일색상 표시하는 부분임 {{: isWeekend ? 'red-day':'' }}  {{: isHoliday ? 'red-day':'' }} -->
       <td style="font-size:12px;" class="calendar-day {{: dayclass }}  {{: thedate.toDateCssClass() }} {{: date.toDateCssClass() === thedate.toDateCssClass() ? 'selected':'' }} {{: daycss[i] }} js-cal-option" data-date="{{: thedate.toISOString() }}">
        <div class="date {{: isWeekend ? 'red-day':'' }}  {{: isHoliday ? 'red-day':'' }} ">{{: thedate.getDate() }}</div>
        {{ thedate.setDate(thedate.getDate() + 1);}}
      </td>
      {{ } }}
    </tr> 
    {{ } }}
  </tbody>
  
    {{ } }}
    {{ if (mode ==='day') { }}
    <tbody>
      <tr>
        <td colspan="7">
          <table class="table table-striped table-condensed table-tight-vert" >
            <thead>
              <tr>
                <th> </th>
                <th style="text-align: center; width: 100%">{{: days[date.getDay()] }}</th>
              </tr>
            </thead>
            <tbody>
			
              <tr>
                <th class="timetitle" > 당일  </th>
                <td class="{{: date.toDateCssClass() }}">  </td>
              </tr>
			  
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
    {{ } }}
  </table>
 </div> 
 </div> 
 </div> 
</script>

<script>
// 전역 변수 선언
var ajaxRequest1 = null;
var $currentPopover = null;
var Objectdate = null;
var ObjectCal = null;

// Popover 이벤트 핸들러
$(document).on('shown.bs.popover', function (ev) {
    var $target = $(ev.target);
    if ($currentPopover && ($currentPopover.get(0) != $target.get(0))) {
        $currentPopover.popover('toggle');
    }
    $currentPopover = $target;
}).on('hidden.bs.popover', function (ev) {
    var $target = $(ev.target);
    if ($currentPopover && ($currentPopover.get(0) == $target.get(0))) {
        $currentPopover = null;
    }
});


// quicktmp1_ceiling: 간단한 템플릿 언어
// John Resig의 블로그 포스트(http://ejohn.org/blog/javascript-micro-templating/)를 기반으로 함
$.extend({
    quicktmp1_ceiling: function (template) {
        return new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('" + template.replace(/[\r\t\n]/g, " ").split("{{").join("\t").replace(/((^|\}\})[^\t]*)'/g, "$1\r").replace(/\t:(.*?)\}\}/g, "',$1,'").split("\t").join("');").split("}}").join("p.push('").split("\r").join("\\'") + "');}return p.join('');")
    }
});

// Date 프로토타입 확장
$.extend(Date.prototype, {
    // _year_month_day 형식의 CSS 클래스 문자열 제공
    toDateCssClass: function () {
        return '_' + this.getFullYear() + '_' + (this.getMonth() + 1) + '_' + this.getDate();
    },
    // 두 날짜 비교를 위한 숫자 생성
    toDateInt: function () {
        return ((this.getFullYear() * 12) + this.getMonth()) * 32 + this.getDate();
    },
    // 시간 문자열 변환
    toTimeString: function() {
        var hours = this.getHours(),
            minutes = this.getMinutes(),
            hour = (hours > 12) ? (hours - 12) : hours,
            ampm = (hours >= 12) ? ' pm' : ' am';
        if (hours === 0 && minutes === 0) {
            return '';
        }
        if (minutes > 0) {
            return hour + ':' + minutes + ampm;
        }
        return hour + ampm;
    }
});


(function ($) {
    // 쿠키에서 캘린더 설정 불러오기
    let getCal_ceiling = getCookie("calendar_ceiling");
    
    if (getCal_ceiling != null) {
        var decodedString = decodeURIComponent(getCal_ceiling);
        Objectdate = JSON.parse(decodedString);
        let Cartcount = Object.keys(Objectdate).length;
        console.log('date : ' + Cartcount);
    }
		

    // t는 옵션 객체를 받아 HTML 문자열을 반환하는 함수
    // 템플릿을 기반으로 quicktmp1_ceiling을 사용하여 생성
    var t = $.quicktmp1_ceiling($('#tmp1_ceiling').get(0).innerHTML);
    
    // 캘린더 함수
    function calendar_ceiling($el, options) {
        // 이전 버튼 클릭
        $el.on('click', '.js-cal-prev', function () {
            switch(options.mode) {
                case 'year': options.date.setFullYear(options.date.getFullYear() - 1); break;
                case 'month': options.date.setMonth(options.date.getMonth() - 1); break;
                case 'week': options.date.setDate(options.date.getDate() - 7); break;
                case 'day': options.date.setDate(options.date.getDate() - 1); break;
            }
            draw_ceiling();
        })
        // 다음 버튼 클릭
        .on('click', '.js-cal-next', function () {
            switch(options.mode) {
                case 'year': options.date.setFullYear(options.date.getFullYear() + 1); break;
                case 'month': options.date.setMonth(options.date.getMonth() + 1); break;
                case 'week': options.date.setDate(options.date.getDate() + 7); break;
                case 'day': options.date.setDate(options.date.getDate() + 1); break;
            }
            draw_ceiling();
        })
        // 옵션 클릭
        .on('click', '.js-cal-option', function () {
            var $t = $(this), o = $t.data();
            if (o.date) {
                o.date = new Date(o.date);
            }
            $.extend(options, o);
            draw_ceiling();
        })
        // 년도 선택 클릭
        .on('click', '.js-cal-years', function () {
            var $t = $(this),
                haspop = $t.data('popover'),
                s = '',
                y = options.date.getFullYear() - 2,
                l = y + 5;
            if (haspop) {
                return true;
            }
            for (; y < l; y++) {
                s += '<button type="button" class="btn btn-default btn-lg btn-block js-cal-option" data-date="' + (new Date(y, 1, 1)).toISOString() + '" data-mode="year">' + y + '</button>';
            }
            return false;
        })
        // 이벤트 클릭 (상세보기 팝업)
        .on('click', '.event_ceiling', function () {
            var $t = $(this),
                index = +($t.attr('data-index')),
                haspop = $t.data('popover'),
                data, time;
            
            if (haspop || isNaN(index)) {
                return true;
            }
            data = options.data[index];
            time = data.start.toTimeString();
            if (time && data.end) {
                time = time + ' - ' + data.end.toTimeString();
            }
            $t.data('popover', true);
            
            // 상세보기 팝업 열기
            popupCenter('../ceiling/view.php?menu=no&num=' + data.id, '조명천장 수주내역', 1830, 900);
            
            return false;
        });
        
        // 일자별 이벤트 추가 함수
        function dayAddEvent_ceiling(index, event) {
            if (!!event.allDay) {
                monthAddEvent(index, event);
                return;
            }
            
            // 일자별 화면에 버튼 형식으로 만들어주는 부분
            var $event = $('<div/>', {'class': 'event_ceiling', text: event.title, title: event.title, 'data-index': index}),
                start = event.start,
                end = event.end || start,
                time = event.start.toTimeString(),
                hour = start.getHours(),
                timeclass = '.time-22-0',
                startint = start.toDateInt(),
                dateint = options.date.toDateInt(),
                endint = end.toDateInt();
            
            if (startint > dateint || endint < dateint) {
                return;
            }
            
            if (!!time) {
                $event.html('<strong>' + time + '</strong> ' + $event.html());
            }
            $event.toggleClass('begin', startint === dateint);
            $event.toggleClass('end', endint === dateint);
            
            if (hour < 6) {
                timeclass = '.time-0-0';
            }
            if (hour < 22) {
                timeclass = '.time-' + hour + '-' + (start.getMinutes() < 30 ? '0' : '30');
            }
            $(timeclass).append($event);
        }
    
        
        // 월별 이벤트 추가 함수
        function monthAddEvent(index, event) {
            var $event = $('<div/>', {'class': 'event_ceiling', text: event.title, title: event.title, 'data-index': index}),
                e = new Date(event.start),
                dateclass = e.toDateCssClass(),
                day = $('.' + e.toDateCssClass()),
                empty = $('<div/>', {'class': 'clear event', html: ' '}),
                numbevents = 0,
                time = event.start.toTimeString(),
                endday = event.end && $('.' + event.end.toDateCssClass()).length > 0,
                checkanyway = new Date(e.getFullYear(), e.getMonth(), e.getDate() + 40),
                existing,
                i;
            
            // 출고일이 없고 결선완료가 아닌 경우 (포장 미완료 - 청색)
            if (((event.lc_su > 0 && (!event.lcassembly_date || event.lcassembly_date === "0000-00-00") && !['011', '012', '013D', '025', '017', '014', '037', '038'].includes(event.type)) ||
                (event.bon_su > 0 && (!event.mainassembly_date || event.mainassembly_date === "0000-00-00")) ||
                (event.etc_su > 0 && (!event.etcassembly_date || event.etcassembly_date === "0000-00-00"))) && (!event.workday || event.workday === "0000-00-00") && (event.cabledone !== "결선완료")) {
                $event.addClass('text-primary');
            }
            // 결선완료인 경우 (적색)
            else if (((event.lc_su > 0 && (!event.lcassembly_date || event.lcassembly_date === "0000-00-00") && !['011', '012', '013D', '025', '017', '014', '037', '038'].includes(event.type)) ||
                (event.bon_su > 0 && (!event.mainassembly_date || event.mainassembly_date === "0000-00-00")) ||
                (event.etc_su > 0 && (!event.etcassembly_date || event.etcassembly_date === "0000-00-00"))) && (!event.workday || event.workday === "0000-00-00") && (event.cabledone === "결선완료")) {
                $event.addClass('text-danger fw-bold');
            }
            
            $event.toggleClass('all-day', !!event.allDay);
            if (!!time) {
                $event.html('<strong>' + time + '</strong> ' + $event.html());
            }
            
            if (!event.end) {
                $event.addClass('begin end');
                $('.' + event.start.toDateCssClass()).append($event);
                return;
            }
            
            while (e <= event.end && (day.length || endday || options.date < checkanyway)) {
                if (day.length) {
                    existing = day.find('.event_ceiling').length;
                    numbevents = Math.max(numbevents, existing);
                    for (i = 0; i < numbevents - existing; i++) {
                        day.append(empty.clone());
                    }
                    day.append(
                        $event.
                        toggleClass('begin', dateclass === event.start.toDateCssClass()).
                        toggleClass('end', dateclass === event.end.toDateCssClass())
                    );
                    $event = $event.clone();
                    $event.html(' ');
                }
                e.setDate(e.getDate() + 1);
                dateclass = e.toDateCssClass();
                day = $('.' + dateclass);
            }
        }
        
        // 년도별 이벤트 추가 함수
        function yearAddEvents(events, year) {
            var counts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $.each(events, function (i, v) {
                if (v.start.getFullYear() === year) {
                    counts[v.start.getMonth()]++;
                }
            });
            $.each(counts, function (i, v) {
                if (v !== 0) {
                    $('.month-' + i).append('<span class="badge">' + v + '</span>');
                }
            });
        }
        
        // 캘린더 그리기 함수
        function draw_ceiling() {
            $("#table").show();
            
            // 최종 엘리먼트를 그려준다
            $el.html(t(options));
            
            // 오늘 날짜에 'today' 클래스 추가
            $('.' + (new Date()).toDateCssClass()).addClass('today');
            
            if (options.data && options.data.length) {
                if (options.mode === 'year') {
                    yearAddEvents(options.data, options.date.getFullYear());
                } else if (options.mode === 'month' || options.mode === 'week') {
                    $.each(options.data, monthAddEvent);
                } else {
                    $.each(options.data, dayAddEvent_ceiling); // day
                }
            }
        }
        
        draw_ceiling();
    }
  
    
    // jQuery 플러그인으로 캘린더 등록
    (function (defaults, $, window, document) {
        $.extend({
            calendar_ceiling: function (options) {
                return $.extend(defaults, options);
            }
        }).fn.extend({
            calendar_ceiling: function (options) {
                options = $.extend({}, defaults, options);
                return $(this).each(function () {
                    var $this = $(this);
                    calendar_ceiling($this, options);
                });
            }
        });
    })({
        days: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
        months: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        shortMonths: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        date: (new Date()),
        daycss: ["c-sunday", "", "", "", "", "", "c-saturday"],
        todayname: "이번달",  // 화면에 보여주는 문구
        thismonthcss: "current",
        lastmonthcss: "outside",
        nextmonthcss: "outside",
        mode: "month",
        data_ceiling: []
    }, jQuery, window, document);
    
})(jQuery);

// 데이터셋 초기화
var dataset_ceiling = [];

// 이전 AJAX 요청 취소
if (ajaxRequest1 !== null) {
    ajaxRequest1.abort();
}

// AJAX 요청으로 데이터 가져오기
ajaxRequest1 = $.ajax({
    url: "../ceiling/deadlinedata.php?",
    type: "post",
    data: '',
    dataType: "json"
}).done(function(data_ceiling) {
    console.log('data_ceiling', data_ceiling);
    
    var titlename = '';
    
    // 데이터 처리 루프
    for (var i = 0; i < Object.values(data_ceiling)[0].length; i++) {
        
        // 현장명 설정
        titlename = Object.values(data_ceiling)[0][i]['workplacename'];
        
        // 한글 유니코드 범위를 확인하는 정규표현식
        const koreanRegex = /[\uAC00-\uD7A3]/;
        let className = "";
        
        if (koreanRegex.test(titlename)) {
            // titlename에 한글이 포함되어 있으면 6글자로 자르기
            titlename = titlename.substring(0, 6);
            className = "brown-text";
        }
        
        titlename += '[';
        
        // 발주처 추가
        if (Object.values(data_ceiling)[0][i]['secondord'] != '') {
            titlename += Object.values(data_ceiling)[0][i]['secondord'] + '] ';
        }
        
        // 수량 추가
        if (Number(Object.values(data_ceiling)[0][i]['bon_su']) > 0) {
            titlename += '본' + Object.values(data_ceiling)[0][i]['bon_su'];
        }
        if (Number(Object.values(data_ceiling)[0][i]['lc_su']) > 0) {
            titlename += 'LC' + Object.values(data_ceiling)[0][i]['lc_su'];
        }
        if (Number(Object.values(data_ceiling)[0][i]['etc_su']) > 0) {
            titlename += '기타' + Object.values(data_ceiling)[0][i]['etc_su'];
        }
        
        // 날짜 변수 생성
        var date = new Date(Object.values(data_ceiling)[0][i]['deadline']);
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();	


        var bon_su = Object.values(data_ceiling)[0][i]['bon_su'];
        var lc_su = Object.values(data_ceiling)[0][i]['lc_su'];
        var etc_su = Object.values(data_ceiling)[0][i]['etc_su'];
        var lcassembly_date = Object.values(data_ceiling)[0][i]['lcassembly_date'];
        var mainassembly_date = Object.values(data_ceiling)[0][i]['mainassembly_date'];
        var etcassembly_date = Object.values(data_ceiling)[0][i]['etcassembly_date'];

        var type = Object.values(data_ceiling)[0][i]['type'];
        var workday = Object.values(data_ceiling)[0][i]['workday'];
        var main_draw = Object.values(data_ceiling)[0][i]['main_draw'];
        var lc_draw = Object.values(data_ceiling)[0][i]['lc_draw'];
        var cabledone = Object.values(data_ceiling)[0][i]['cabledone'];
        var etc_draw = Object.values(data_ceiling)[0][i]['etc_draw'];

        let drawok = '(설완)';
        let main_draw_arr = "";

        if (main_draw && main_draw.substring(0, 2) === "20") {
            main_draw_arr = main_draw.substring(5, 10);
        } else if (bon_su < 1) {
            main_draw_arr = "X";
        }

        let lc_draw_arr = "";
        if (lc_draw && lc_draw.substring(0, 2) === "20") {
            lc_draw_arr = lc_draw.substring(5, 10);
        } else if (lc_su < 1) {
            lc_draw_arr = "X";
        }
        if (["011", "012", "013D", "025", "017", "014", "037", "038"].includes(type)) {
            lc_draw_arr = "X";
        }

        let etc_draw_arr = "";
        if (etc_draw && etc_draw.substring(0, 2) === "20") {
            etc_draw_arr = etc_draw.substring(5, 10);
        } else if (etc_su < 1) {
            etc_draw_arr = "X";
        }

        let maincondition_draw = 1;
        let lccondition_draw = 1;
        let etccondition_draw = 1;

        if ((main_draw && main_draw !== "0000-00-00") || main_draw_arr === "X") {
            maincondition_draw = 0;
        }

        if ((lc_draw && lc_draw !== "0000-00-00") || lc_draw_arr === "X") {
            lccondition_draw = 0;
        }
        if ((etc_draw && etc_draw !== "0000-00-00") || etc_draw_arr === "X") {
            etccondition_draw = 0;
        }

        if (maincondition_draw || lccondition_draw || etccondition_draw) {
            drawok = '';
        }

        titlename += drawok;
        titlename = "[" + type + "]" + titlename;
        
        // 데이터셋에 추가
        dataset_ceiling.push({
            title: titlename,
            start: new Date(y, m, d),
            end: null,
            allDay: true,
            text: titlename,
            id: Object.values(data_ceiling)[0][i]['num'],
            className: className,
            bon_su: bon_su,
            lc_su: lc_su,
            etc_su: etc_su,
            lcassembly_date: lcassembly_date,
            mainassembly_date: mainassembly_date,
            etcassembly_date: etcassembly_date,
            type: type,
            workday: workday,
            drawok: drawok,
            cabledone: cabledone
        });
    }
    
    // 데이터를 시작 날짜 기준으로 정렬
    dataset_ceiling.sort(function(a, b) {
        return (+a.start) - (+b.start);
    });
    
    // 현재 설정을 쿠키에 저장
    ObjectCal = new Array();
    var data_ceiling_obj = new Object();
    data_ceiling_obj.mode = '2022-01-01';
    ObjectCal.push(data_ceiling_obj);
    setCookie('calendar_ceiling', JSON.stringify(ObjectCal), 3600);
    
    // 캘린더 렌더링
    $('#holder_ceiling').calendar_ceiling({
        data: dataset_ceiling
    });
});
</script>

</body>
</html>