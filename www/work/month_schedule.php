<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? 0;
$id_name = $_SESSION["name"] ?? 'Unknown';

// 요청 변수 안전하게 초기화
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '1';

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

// 오늘 날짜를 YYYY-MM-DD 형식으로 구합니다.
$today = date("Y-m-d");

// $today 변수를 JavaScript에 전달하기 위해 echo 문을 사용합니다.
echo "<script>var today = '$today';</script>";

include includePath('load_header.php');
?>

<title>미래기업 jamb 공사 스케줄</title>
   
</head>
 

<style>
.red-day {
    color: red !important;
}

.today {
    background-color: #ffe5e5 !important;
}
</style>

<body>

<?php include includePath('myheader.php'); ?>

<div class="card mt-2 mb-2">
    <div class="d-flex p-2 mt-3 mb-1 justify-content-center">
        <h4 id="clickableText">JAMB 시공 스케줄</h4>
        <button type="button" class="btn btn-dark btn-sm mx-3" onclick="location.reload();" title="새로고침"><i class="bi bi-arrow-clockwise"></i></button>
    </div>

    <div id="holder" class="d-flex p-2 justify-content-center"></div>


<script type="text/tmpl" id="tmpl">

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
  <table id="jamb_table"  class="table"  style="table-layout:fixed; width: 95%;">
    <thead >
      <tr>
        <td colspan="7" style="text-align: center">
          <table style="white-space: nowrap; width: 100%">
            <tr class="justify-content-center">
				<td style="text-align: center;">
					<div class="d-flex justify-content-center align-items-center">
						<span class="btn-group">
							<button class="js-cal-prev btn btn-primary btn-lg me-1">  <i class="bi bi-arrow-left"></i></button>
							<button class="js-cal-next btn btn-primary btn-lg me-1"> <i class="bi bi-arrow-right"></i>  </button>
						</span>
						<button class="js-cal-option btn btn-default me-5 {{: first.toDateInt() <= today.toDateInt() && today.toDateInt() <= last.toDateInt() ? 'active':'' }}" data-date="{{: today.toISOString()}}" data-mode="month">{{: todayname }}</button>
						<span class="badge bg-dark fs-6 me-5">{{: year }}년 {{: shortMonths[first.getMonth()] }} {{: first.getDate() }}일 - {{: shortMonths[last.getMonth()] }} {{: last.getDate() }}일 </span>
						<span class="badge bg-primary fs-6 me-5"> 생산 미완료 : 청색 </span>
					</div>
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
    <tr >
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


var ajaxRequest = null;


//quicktmpl is a simple template language I threw together a while ago; it is not remotely secure to xss and probably has plenty of bugs that I haven't considered, but it basically works
//the design is a function I read in a blog post by John Resig (http://ejohn.org/blog/javascript-micro-templating/) and it is intended to be loosely translateable to a more comprehensive template language like mustache easily
$.extend({
    quicktmpl: function (template) {return new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('"+template.replace(/[\r\t\n]/g," ").split("{{").join("\t").replace(/((^|\}\})[^\t]*)'/g,"$1\r").replace(/\t:(.*?)\}\}/g,"',$1,'").split("\t").join("');").split("}}").join("p.push('").split("\r").join("\\'")+"');}return p.join('');")}
});

$.extend(Date.prototype, {
  //provides a string that is _year_month_day, intended to be widely usable as a css class
  toDateCssClass:  function () { 
    return '_' + this.getFullYear() + '_' + (this.getMonth() + 1) + '_' + this.getDate(); 
  },
  //this generates a number useful for comparing two dates; 
  toDateInt: function () { 
    return ((this.getFullYear()*12) + this.getMonth())*32 + this.getDate(); 
  },
  toTimeString: function() {
    var hours = this.getHours(),
        minutes = this.getMinutes(),
        hour = (hours > 12) ? (hours - 12) : hours,
        ampm = (hours >= 12) ? ' pm' : ' am';
    if (hours === 0 && minutes===0) { return ''; }
    if (minutes > 0) {
      return hour + ':' + minutes + ampm;
    }
    return hour + ampm;
  }
});


(function ($) {
	
  //t here is a function which gets passed an options object and returns a string of html. I am using quicktmpl to create it based on the template located over in the html block
  var t = $.quicktmpl($('#tmpl').get(0).innerHTML);
  
  function calendar($el, options) {
    //actions aren't currently in the template, but could be added easily...
    $el.on('click', '.js-cal-prev', function () {
      switch(options.mode) {
      case 'year': options.date.setFullYear(options.date.getFullYear() - 1); break;
      case 'month': options.date.setMonth(options.date.getMonth() - 1); break;
      case 'week': options.date.setDate(options.date.getDate() - 7); break;
      case 'day':  options.date.setDate(options.date.getDate() - 1); break;
      }
      draw();
    }).on('click', '.js-cal-next', function () {
      switch(options.mode) {
      case 'year': options.date.setFullYear(options.date.getFullYear() + 1); break;
      case 'month': options.date.setMonth(options.date.getMonth() + 1); break;
      case 'week': options.date.setDate(options.date.getDate() + 7); break;
      case 'day':  options.date.setDate(options.date.getDate() + 1); break;
      }
      draw();
    }).on('click', '.js-cal-option', function () {
      var $t = $(this), o = $t.data();
      if (o.date) { o.date = new Date(o.date); }
      $.extend(options, o);
      draw();
    }).on('click', '.js-cal-years', function () {
      var $t = $(this), 
          haspop = $t.data('popover'),
          s = '', 
          y = options.date.getFullYear() - 2, 
          l = y + 5;
      if (haspop) { return true; }
      for (; y < l; y++) {
        s += '<button type="button" class="btn btn-default btn-lg btn-block js-cal-option" data-date="' + (new Date(y, 1, 1)).toISOString() + '" data-mode="year">'+y + '</button>';
      }
       // $t.popover({content: s, html: true, placement: 'auto top'}).popover('toggle');
	   
      return false;
  
	  
    }).on('click', '.event', function () {
		// 클릭했을때 이벤트
      var $t = $(this), 
          index = +($t.attr('data-index')), 
          haspop = $t.data('popover'),
          data, time;
	  
      if (haspop || isNaN(index)) { return true; }
      data = options.data[index];
      time = data.start.toTimeString();
      if (time && data.end) { time = time + ' - ' + data.end.toTimeString(); }
      $t.data('popover', true);
      // $t.popover({content: '<p><strong>' + time + '</strong></p>'+data.text, html: true, placement: 'auto left'}).popover('toggle');

      // console.log(data.id); // id 추출해서
      var base_url = '<?php echo $base_url; ?>';
      popupCenter(base_url + '/work/view.php?menu=no&num=' + data.id, '잠 내역', 1800, 900);

      return false;
	  

	  
    });

    function dayAddEvent(index, event) {
        if (!!event.allDay) {
            monthAddEvent(index, event);
            return;
        }

        // 일자별 화면에 버튼형식으로 만들어주는 부분
        // 선택을 시공예정일과 시공완료일에 따른 배경색등 지정할때 유용한 것
        var $event = $('<div/>', {
            'class': 'event',
            text: event.title,
            title: event.title,
            'data-index': index
        });

        // deadline 속성이 존재하면 'text-primary' 클래스를 추가합니다.
        if (event.deadline) {
            $event.addClass('text-primary');
        }

        var start = event.start;
        var end = event.end || start;
        var time = event.start.toTimeString();
        var hour = start.getHours();
        var timeclass = '.time-22-0';
        var startint = start.toDateInt();
        var dateint = options.date.toDateInt();
        var endint = end.toDateInt();

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

    function monthAddEvent(index, event) {
        var $event = $('<div/>', {
            'class': 'event',
            text: event.title,
            title: event.title,
            'data-index': index
        }),
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

        // Add 'text-primary' class if deadline is "0000-00-00" or empty
        if (!event.deadline || event.deadline === "0000-00-00") {
            $event.addClass('text-primary');
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
                existing = day.find('.event').length;
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

    function yearAddEvents(events, year) {
        var counts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.each(events, function(i, v) {
            if (v.start.getFullYear() === year) {
                counts[v.start.getMonth()]++;
            }
        });
        $.each(counts, function(i, v) {
            if (v !== 0) {
                $('.month-' + i).append('<span class="badge">' + v + '</span>');
            }
        });
    }

    function draw() {
        // 테이블을 보이게 하고
        $("#jamb_table").show();
        // 최종 엘리먼트를 그려준다.
        $el.html(t(options));
        //potential optimization (untested), this object could be keyed into a dictionary on the dateclass string; the object would need to be reset and the first entry would have to be made here
        $('.' + (new Date()).toDateCssClass()).addClass('today');
        if (options.data && options.data.length) {
            if (options.mode === 'year') {
                yearAddEvents(options.data, options.date.getFullYear());
            } else if (options.mode === 'month' || options.mode === 'week') {
                $.each(options.data, monthAddEvent);
            } else {
                $.each(options.data, dayAddEvent); //day
            }
        }
    }

    // 화면에 첫 띄워주기
    draw();

  }
  
  (function (defaults, $, window, document) {
    $.extend({
      calendar: function (options) {
        return $.extend(defaults, options);
      }
    }).fn.extend({
      calendar: function (options) {
        options = $.extend({}, defaults, options);
        return $(this).each(function () {
          var $this = $(this);
          calendar($this, options);
        });
      }
    });
  })({
    days: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
    months: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
    shortMonths: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
    date: (new Date()),
        daycss: ["c-sunday", "", "", "", "", "", "c-saturday"],
        todayname: "이번달", // 화면에 보여주는 문구
        thismonthcss: "current",
        lastmonthcss: "outside",
        nextmonthcss: "outside",
    mode: "month",
    data: []
  }, jQuery, window, document);
    
})(jQuery);

var dataset = [];

if (ajaxRequest !== null) {
    ajaxRequest.abort();
}

// ajax 요청 생성
ajaxRequest = $.ajax({
    url: "../work/deadlinedata.php?",
    type: "post",
    data: '',
    dataType: "json",
}).done(function(data) {
    console.log(data);
    console.log(Object.values(data)[0].length);
    
    var titlename = '';
    for (i = 0; i < Object.values(data)[0].length; i++) {
        titlename = Object.values(data)[0][i]['workplacename'];
        
        // 한글 유니코드 범위를 확인하는 정규표현식
        const koreanRegex = /[\uAC00-\uD7A3]/;

        if (koreanRegex.test(titlename)) {
            // titlename에 한글이 포함되어 있으면
            if (Object.values(data)[0][i]['checkstep'] !== '신규') {
                titlename = titlename.substring(0, 6);
            } else {
                titlename = '(신규)' + titlename.substring(0, 6);
            }
        }

        titlename += '[';

        if (Object.values(data)[0][i]['secondord'] != '') {
            titlename += Object.values(data)[0][i]['secondord'] + '] ';
        }

        if (Number(Object.values(data)[0][i]['widejamb']) > 0) {
            titlename += '막' + Object.values(data)[0][i]['widejamb'];
        }
        if (Number(Object.values(data)[0][i]['normaljamb']) > 0) {
            titlename += '멍' + Object.values(data)[0][i]['normaljamb'];
        }
        if (Number(Object.values(data)[0][i]['smalljamb']) > 0) {
            titlename += '쪽' + Object.values(data)[0][i]['smalljamb'];
        }

        if (Object.values(data)[0][i]['worker'] !== '') {
            titlename += ' ' + Object.values(data)[0][i]['worker'];
        }

        var date = new Date(Object.values(data)[0][i]['endworkday']);
        d = date.getDate();
        m = date.getMonth();
        y = date.getFullYear();

        var deadlinevar = Object.values(data)[0][i]['deadline'];

        dataset.push({
            title: titlename,
            start: new Date(y, m, d),
            end: null,
            allDay: true,
            text: titlename,
            id: Object.values(data)[0][i]['num'],
            deadline: deadlinevar
        });
    }

    dataset.sort(function(a, b) {
        return (+a.start) - (+b.start);
    });

    //data must be sorted by start date

    //Actually do everything

    //현재 설정 mode 쿠키에 저장함
    ObjectCal = new Array();
    var data = new Object();
    data.mode = '2023-01-01';
    ObjectCal.push(data);
    console.log('ordercart 쿠키' + JSON.stringify(ObjectCal));

    setCookie('calendar', JSON.stringify(ObjectCal), 3600);   // 쿠키에 저장함

    $('#holder').calendar({
        data: dataset
    });
});
</script>

<script>
$(document).ready(function() {
    saveLogData('Jamb 월간일정');
});
</script>

</body>
</html>