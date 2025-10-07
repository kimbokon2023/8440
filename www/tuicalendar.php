<!DOCTYPE html>
<html>
<head>

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- 반드시 순서 중요함 time-picker date-picker가 toastui-calendar.min.js -->
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.js"></script>
<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>

<link rel="stylesheet" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css"/>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css"/>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css"/>
<link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css"/>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>

<!-- 최초화면에서 보여주는 상단메뉴 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- CDN -->

<link rel="stylesheet" href="./css/style.css">

</head>
<body>

<!-- background: -webkit-linear-gradient(left, #33156d 0%,#f282bc 100%); /* Chrome10-25,Safari5.1-6 */  -->

<div class="container-fluid">  	 
 
<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 						
     
<br>
<div class=container>
<button class="btn btn-secondary btn-sm" id=monthlybtn > 월 </button>&nbsp;						
<button class="btn btn-secondary btn-sm"  id=weeklybtn > 주 </button>&nbsp;						
<button class="btn btn-danger btn-sm" id=dailybtn >  일 </button>&nbsp;						

<button class="btn btn-dark btn-sm"   id=twoweeklybtn  > 2주 </button>&nbsp;
<button class="btn btn-primary btn-sm" id=threeweeklybtn  > 3주 </button>&nbsp;
<button class="btn btn-success btn-sm" id=taskandschedulebtn > Task & Schedule </button>&nbsp;
  
  
	  <button class="btn btn-outline-secondary btn-sm"> Today</button>
	  <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-caret-left-square"></i> </button>
	  <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-caret-right-square"></i>	  </button>
	  
</div>	
  
		
<div id="calendar" style="height: 600px;"></div>
  
 </div> <!-- container-fulid end -->


  
</body>

<script>

$(document).ready(function(){	

const Calendar = tui.Calendar;

const container = document.getElementById('calendar');

const DatePicker = tui.DatePicker;

const options = {
  defaultView: 'month',
  timezone: {
    zones: [
      {
        timezoneName: 'Asia/Seoul',
        displayLabel: 'Seoul',
      },
      {
        timezoneName: 'Europe/London',
        displayLabel: 'London',
      },
    ],
  },
  calendars: [
    {
      id: 'cal1',
      name: '개인',
      backgroundColor: '#03bd9e',
    },
    {
      id: 'cal2',
      name: '직장',
      backgroundColor: '#00a9ff',
    },
    {
      id: 'cal3',
      name: '집',
      backgroundColor: '#00a9ff',
    },
  ],
};

const calendar = new Calendar(container, options);

calendar.createEvents([
  {
    id: 'event1',
    calendarId: 'cal1',
    title: '주간 회의1',
    start: '2022-12-10T09:00:00',
    end: '2022-12-10T10:00:00',
  },
  {
    id: 'event2',
    calendarId: 'cal2',
    title: '주간 회의',
    start: '2022-12-07T09:00:00',
    end: '2022-12-07T10:00:00',
  },
  {
    id: 'event3',
    calendarId: 'cal3',
    title: '김진억 회의',
    start: '2022-12-08T09:00:00',
    end: '2022-12-08T10:00:00',
  },
]);

calendar.setOptions({
  useFormPopup: true,
  useDetailPopup: true,
});


calendar.setTheme({
  common: {
    gridSelection: {
      backgroundColor: 'rgba(81, 230, 92, 0.05)',
      border: '1px dotted #515ce6',
    },
  },
});

// 일간 뷰
$("#dailybtn").on('click',function() {
   calendar.changeView('day', true);
});


// // 주간 뷰
$("#weeklybtn").on('click',function() {
   calendar.changeView('week', true);
});



// 월간 뷰
$("#monthlybtn").on('click',function() {
calendar.changeView('month', true);
});

// // 월간 2주 뷰
// calendar.setOptions({month: {visibleWeeksCount: 2}}, true);
// calendar.changeView('month', true);

function formatTime(time) {
  const hours = `${time.getHours()}`.padStart(2, '0');
  const minutes = `${time.getMinutes()}`.padStart(2, '0');

  return `${hours}:${minutes}`;
}

calendar.setOptions({
  useFormPopup: true,
  useDetailPopup: true,	
  template: {
    time(event) {
      const { start, end, title } = event;

      return `<span style="color: white;">${formatTime(start)}~${formatTime(end)} ${title}</span>`;
    },
    allday(event) {
      return `<span style="color: gray;">${event.title}</span>`;
    },
  },
});


      var cal = new Calendar('#calendar', {
        defaultView: 'month',
        calendars: MOCK_CALENDARS,
        useFormPopup: true,
        useDetailPopup: true,
        template: {
          popupIsAllday: function () {
            return 'All day?';
          },
          popupStateFree: function () {
            return '🏝️ Free';
          },
          popupStateBusy: function () {
            return '🔥 Busy';
          },
          titlePlaceholder: function () {
            return 'Enter title';
          },
          locationPlaceholder: function () {
            return 'Enter location';
          },
          startDatePlaceholder: function () {
            return 'Start date';
          },
          endDatePlaceholder: function () {
            return 'End date';
          },
          popupSave: function () {
            return 'Add Event';
          },
          popupUpdate: function () {
            return 'Update Event';
          },
          popupEdit: function () {
            return 'Modify';
          },
          popupDelete: function () {
            return 'Remove';
          },
          popupDetailTitle: function (data) {
            return 'Detail of ' + data.title;
          },
        },
      });
    

calendar.on('clickEvent', ({ event }) => {
  const el = document.getElementById('clicked-event');
  el.innerText = event.title;
  // alert('클릭');
  console.log('ddd');
});


/* 이동 및 뷰 타입 버튼 이벤트 핸들러 */
nextBtn.addEventListener('click', () => {
  calendar.next();                          // 현재 뷰 기준으로 다음 뷰로 이동
});

prevBtn.addEventListener('click', () => {
  calendar.prev();                          // 현재 뷰 기준으로 이전 뷰로 이동
});

dayViewBtn.addEventListener('click', () => {
  calendar.changeView('day', true);         // 일간 뷰 보기
});

weekViewBtn.addEventListener('click', () => {
  calendar.changeView('week', true);        // 주간 뷰 보기
});

monthViewBtn.addEventListener('click', () => {
  calendar.changeView('month', true);       // 월간 뷰 보기
});

calendar.on('beforeCreateSchedule', scheduleData => {
  const schedule = {
    calendarId: scheduleData.calendarId,
    id: String(Math.random() * 100000000000000000),
    title: scheduleData.title,
    isAllDay: scheduleData.isAllDay,
    start: scheduleData.start,
    end: scheduleData.end,
    category: scheduleData.isAllDay ? 'allday' : 'time',
    location: scheduleData.location             // 장소 정보도 입력할 수 있네요!
  };

  calendar.createSchedules([schedule]);

  alert('일정 생성 완료');
});



});
</script>


</html>

