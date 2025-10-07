<?php require_once(includePath('session.php'));   
   
if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
if($check==null)
		$check='1';

// 오늘 날짜를 YYYY-MM-DD 형식으로 구합니다.
$today = date("Y-m-d");

$title_message = '천장(ceiling) 스케줄';

// $today 변수를 JavaScript에 전달하기 위해 echo 문을 사용합니다.
echo "<script>var today = '$today';</script>";
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title> <?=$title_message?> </title>

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
	<h3 id="clickableText_ceiling">  <?=$title_message?>  </h3> 
	<button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  	 
</div>

<div id="holder_ceiling" class="d-flex p-2 justify-content-center" ></div>
 
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

var ajaxRequest1 = null;

    var $currentPopover = null;
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


//quicktmp1_ceiling is a simple template language I threw together a while ago; it is not remotely secure to xss and probably has plenty of bugs that I haven't considered, but it basically works
//the design is a function I read in a blog post by John Resig (http://ejohn.org/blog/javascript-micro-templating/) and it is intended to be loosely translateable to a more comprehensive template language like mustache easily
$.extend({
    quicktmp1_ceiling: function (template) {return new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('"+template.replace(/[\r\t\n]/g," ").split("{{").join("\t").replace(/((^|\}\})[^\t]*)'/g,"$1\r").replace(/\t:(.*?)\}\}/g,"',$1,'").split("\t").join("');").split("}}").join("p.push('").split("\r").join("\\'")+"');}return p.join('');")}
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
	

// 쿠키 불러옴
let getCal_ceiling = getCookie("calendar_ceiling");

	if(getCal_ceiling!=null)
	{	
		// console.log('자료있음');
		 
		var decodedString = decodeURIComponent(getCal_ceiling);
			
		Objectdate = JSON.parse(decodedString);

		let Cartcount = Object.keys(Objectdate).length;

		console.log('date : ' + Cartcount);
}
		

  //t here is a function which gets passed an options object and returns a string of html. I am using quicktmp1_ceiling to create it based on the template located over in the html block
  var t = $.quicktmp1_ceiling($('#tmp1_ceiling').get(0).innerHTML);
  
  function calendar_ceiling($el, options) {
    //actions aren't currently in the template, but could be added easily...
    $el.on('click', '.js-cal-prev', function () {
      switch(options.mode) {
      case 'year': options.date.setFullYear(options.date.getFullYear() - 1); break;
      case 'month': options.date.setMonth(options.date.getMonth() - 1); break;
      case 'week': options.date.setDate(options.date.getDate() - 7); break;
      case 'day':  options.date.setDate(options.date.getDate() - 1); break;
      }
      draw_ceiling();
    }).on('click', '.js-cal-next', function () {
      switch(options.mode) {
      case 'year': options.date.setFullYear(options.date.getFullYear() + 1); break;
      case 'month': options.date.setMonth(options.date.getMonth() + 1); break;
      case 'week': options.date.setDate(options.date.getDate() + 7); break;
      case 'day':  options.date.setDate(options.date.getDate() + 1); break;
      }
      draw_ceiling();
    }).on('click', '.js-cal-option', function () {
      var $t = $(this), o = $t.data();
      if (o.date) { o.date = new Date(o.date); }
      $.extend(options, o);
      draw_ceiling();
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
  
	  
    }).on('click', '.event_ceiling', function () {
		// 클릭했을때 이벤트
      var $t = $(this), 
          index = +($t.attr('data-index')), 
          haspop = $t.data('popover'),
          data, time;
	  
      if (haspop || isNaN(index)) { return true; }
      data = options.data[index];
      time = data.start.toTimeString();
      if (time && data.end) { time = time + ' - ' + data.end.toTimeString(); }
      $t.data('popover',true);
      // $t.popover({content: '<p><strong>' + time + '</strong></p>'+data.text, html: true, placement: 'auto left'}).popover('toggle');
	  
	   // console.log(data.id); // id 추출해서
	   popupCenter('../ceiling/view.php?menu=no&num=' + data.id   , '조명천장 수주내역', 1830, 900);		   
	  
      return false;
	  
    });
    function dayAddEvent_ceiling(index, event) {
      if (!!event.allDay) {
        monthAddEvent(index, event);
        return;
      }
	  // 일자별 화면에 버튼형식으로 만들어주는 부분
	  // 선택을 시공예정일과 시공완료일에 따른 배경색등 지정할때 유용한 것
      var $event = $('<div/>', {'class': 'event_ceiling', text: event.title, title: event.title, 'data-index': index}),
          start = event.start,
          end = event.end || start,
          time = event.start.toTimeString(),
          hour = start.getHours(),
          timeclass = '.time-22-0',
          startint = start.toDateInt(),
          dateint = options.date.toDateInt(),
          endint = end.toDateInt();
		  	  
		  
      if (startint > dateint || endint < dateint) { return; }
      
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
		// class가 쟘과 천장이 같아서 같이 나오는 현상 같다. 
      var $event = $('<div/>', {'class': 'event_ceiling', text: event.title, title: event.title, 'data-index': index}),
          e = new Date(event.start),
          dateclass = e.toDateCssClass(),
          day = $('.' + e.toDateCssClass()),
          empty = $('<div/>', {'class':'clear event', html:' '}), 
          numbevents = 0, 
          time = event.start.toTimeString(),
          endday = event.end && $('.' + event.end.toDateCssClass()).length > 0,
          checkanyway = new Date(e.getFullYear(), e.getMonth(), e.getDate()+40),
          existing,
          i;
	
   
	// Add 'text-primary' class if lc_su or bon_su is greater than 0 and assembly_date is "0000-00-00" or empty
	// 출고일이 없을 경우 , 결선완료가 아닐경우
	if (((event.lc_su > 0 && (!event.lcassembly_date || event.lcassembly_date === "0000-00-00") && !['011', '012', '013D', '025', '017', '014','037','038'].includes(event.type)) ||
		(event.bon_su > 0 && (!event.mainassembly_date || event.mainassembly_date === "0000-00-00")) ||
		(event.etc_su > 0 && (!event.etcassembly_date || event.etcassembly_date === "0000-00-00")))  && (!event.workday || event.workday === "0000-00-00")  && (event.cabledone !== "결선완료")  ) {
	  $event.addClass('text-primary');
	}
	  
	 // 결선완료인경우는 danger 표시
	else if (((event.lc_su > 0 && (!event.lcassembly_date || event.lcassembly_date === "0000-00-00") && !['011', '012', '013D', '025', '017', '014','037','038'].includes(event.type)) ||
		(event.bon_su > 0 && (!event.mainassembly_date || event.mainassembly_date === "0000-00-00")) ||
		(event.etc_su > 0 && (!event.etcassembly_date || event.etcassembly_date === "0000-00-00")))  && (!event.workday || event.workday === "0000-00-00")  && (event.cabledone === "결선완료")  ) {
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
        if(day.length) { 
          existing = day.find('.event_ceiling').length;
          numbevents = Math.max(numbevents, existing);
          for(i = 0; i < numbevents - existing; i++) {
            day.append(empty.clone());
          }
          day.append(
            $event.
            toggleClass('begin', dateclass === event.start.toDateCssClass()).
            toggleClass('end', dateclass === event.end.toDateCssClass())
          );
          $event = $event.clone();
          $event.html(' ');
        }
        e.setDate(e.getDate() + 1);
        dateclass = e.toDateCssClass();
        day = $('.' + dateclass);
      }
    }
    
	function yearAddEvents(events, year) {
      var counts = [0,0,0,0,0,0,0,0,0,0,0,0];
      $.each(events, function (i, v) {
        if (v.start.getFullYear() === year) {
            counts[v.start.getMonth()]++;
        }
      });
      $.each(counts, function (i, v) {
        if (v!==0) {
            $('.month-'+i).append('<span class="badge">'+v+'</span>');
        }
      });
    }
    
    function draw_ceiling() {
	  $("#table").show();
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
            $.each(options.data, dayAddEvent_ceiling); //day
        }
      }	  	  	  
    }    	
	draw_ceiling();         
  }
  
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
        todayname: "이번달", // 화면에 보여주는 문구
        thismonthcss: "current",
        lastmonthcss: "outside",
        nextmonthcss: "outside",
    mode: "month",
    data_ceiling: []
  }, jQuery, window, document);
    
})(jQuery);

var dataset_ceiling = [];
  
if (ajaxRequest1 !== null) {
	ajaxRequest1.abort();
}

	 // ajax 요청 생성
	 ajaxRequest1 = $.ajax({		
			url: "../ceiling/deadlinedata.php?" ,
    	  	type: "post",		
   			data: '',
   			dataType:"json",
		}).done(function(data_ceiling){
              console.log('data_ceiling' , data_ceiling);
             // console.log(Object.values(data_ceiling)[0].length);		// 12 데이터 숫자 나옴 반복								
	 //	console.log(Object.values(data_ceiling)[0][0]['address']);		// 참조하려면 좌측과 같이 3번의 첨자가 필요함 주의		
	var titlename ='';
	for(i = 0; i < Object.values(data_ceiling)[0].length ; i++) {
		
		// 시공팀에 따라 조회가 다름				
			 // 신규쟘일 경우 (신규)
		 // if(Object.values(data_ceiling)[0][i]['checkstep'] ==='신규')
					// titlename +=  '(신규)' ;
			titlename = Object.values(data_ceiling)[0][i]['workplacename'];
			// 한글 유니코드 범위를 확인하는 정규표현식
			const koreanRegex = /[\uAC00-\uD7A3]/;
			let className = "";

			if (koreanRegex.test(titlename)) {
			  // titlename에 한글이 포함되어 있으면
			  
						titlename = titlename.substring(0, 6);
						className = "brown-text"; // 클래스 이름 설정
			 }
			
				titlename += '[' ;

			  if(Object.values(data_ceiling)[0][i]['secondord'] !='')
					titlename +=  Object.values(data_ceiling)[0][i]['secondord'] + '] ' ;
															
				
				if(Number(Object.values(data_ceiling)[0][i]['bon_su']) > 0)
					titlename += '본' + Object.values(data_ceiling)[0][i]['bon_su']  ;
				if(Number(Object.values(data_ceiling)[0][i]['lc_su']) > 0)
					titlename += 'LC' + Object.values(data_ceiling)[0][i]['lc_su']  ;
				if(Number(Object.values(data_ceiling)[0][i]['etc_su']) > 0)
					titlename += '기타' + Object.values(data_ceiling)[0][i]['etc_su']  ;
				

				var date = new Date(Object.values(data_ceiling)[0][i]['deadline']);
				 d = date.getDate();				
				 m = date.getMonth();
				 y = date.getFullYear();	


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
				
		dataset_ceiling.push({ title: titlename , start: new Date(y, m, d), end: null , allDay: true , text: titlename , id: Object.values(data_ceiling)[0][i]['num'], className: className,
			  bon_su : bon_su, lc_su : lc_su, etc_su : etc_su, lcassembly_date : lcassembly_date, mainassembly_date : mainassembly_date, etcassembly_date : etcassembly_date, type : type, workday : workday , drawok : drawok, cabledone : cabledone
			});
		}		  
		dataset_ceiling.sort(function(a,b) { return (+a.start) - (+b.start); });			  
		//data must be sorted by start date
		
		//현재 설정 mode 쿠키에 저장함
		ObjectCal = new Array();
		var data_ceiling = new Object();					  
		data_ceiling.mode = '2022-01-01';						
		ObjectCal.push(data_ceiling);
		// console.log('ordercart 쿠키' + JSON.stringify(ObjectCal));			
		setCookie ('calendar_ceiling', JSON.stringify(ObjectCal), 3600);   // 쿠키에 저장함
					
		$('#holder_ceiling').calendar_ceiling({
		  data: dataset_ceiling
		});			
	});
</script>


</body>