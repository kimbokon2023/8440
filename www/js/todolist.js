var ajaxRequest5 = null;

$(document).ready(function() {
    let todo_currentMonth = new Date().getMonth();
    let todo_currentYear = new Date().getFullYear();

	function todo_fetchCalendarData(month, year, search = null) {
		var search = $('#searchTodo').val(); 
		// 선택된 라디오 버튼 정보를 배열로 변환 (_all 제외)
		var radioarray = [];
		$('.filter-radio').each(function(index) {
			// console.log(`index ${index}`); // 템플릿 리터럴 수정
			var id = $(this).attr('id');
			if (id) {
				var splitItem = id.split('_')[1]; // "_"로 분리하여 두 번째 요소 가져오기
				if (splitItem && splitItem !== 'all') { // 'all'은 제외
					radioarray.push(splitItem); // 배열에 추가
				}
			}
		});		
			
		// 필터에 따라 데이터 표시
		let selectedFilter = $('input[name="filter"]:checked').attr('id');	
		// let filteredData = filterDataByProcess(response.todo_data, selectedFilter);	

		// radioarray 결과 확인
		// console.log(radioarray);	
			
		 showWaitingModal();
		ajaxRequest5 = $.ajax({
            url: "/todo/fetch_todo.php",
            type: "post",
            data: { month: month + 1, year: year, selectedFilter : selectedFilter, search : search, radioarray : radioarray },
            dataType: "json",
            success: function(response) {                			
			    // console.log(response);
                let calendarHtml = todo_generateCalendarHtml(
                    response.leave_data,
                    response.holiday_data,
                    response.todo_data,
                    response.work_data,
                    response.jamb_data,
                    response.CL_data,
                    response.OEM_data,
                    selectedFilter  
                );				

                $('#todo-calendar-container').html(calendarHtml);					
				
					var showTodoView = getCookie("showTodoView");
					var todoCalendarContainer = $("#todo-list");
					if (showTodoView === "show") {
						todoCalendarContainer.css("display", "inline-block");
					} else {
						todoCalendarContainer.css("display", "none");
					}		
					
			// 검색 결과를 부트스트랩 테이블로 렌더링
			if (search && response.integratedData.length > 0) {
				$('#todosMain-list').empty();
				renderTodosMainTable(response.integratedData);
			} else if (search) {
				$('#todosMain-list').html('<p class="text-center">검색 결과가 없습니다.</p>');
			} else
			  {
				$('#todosMain-list').empty();  
			  }					

			ajaxRequest5 = null;
			hideSavingModal();					
				
            },
            error: function() {
                console.log('Failed to fetch data');
				ajaxRequest5 = null;
				hideSavingModal();
            }
        });
    }
	
// 테이블 생성 함수
function renderTodosMainTable(data) {
    let tableHtml = `
    <table class="modern-dashboard-table">
        <thead>
            <tr>
                <th class="text-center" style="width:100px;">구분</th>
                <th class="text-center" style="width:100px;">출고일</th>
                <th class="text-center" style="width:200px; color: #0288d1;">발주처</th>
                <th class="text-center">현장명</th>
                <th class="text-center">주소</th>
            </tr>
        </thead>
        <tbody>
    `;

    data.forEach(item => {
        // item.table 값에 따라 텍스트 변경
        let tableType = '-';
        if (item.table === 'work') {
            tableType = '<span class="modern-data-value" style="color: #059669; font-weight: 600;"> 쟘 </span>';
        } else if (item.table === 'ceiling') {
            tableType = '<span class="modern-data-value" style="color: #0288d1; font-weight: 600;"> 천장 </span>';
        } else if (item.table === 'outorder') {
            tableType = '<span class="modern-data-value" style="color: #0ea5e9; font-weight: 600;"> 외주 </span>';
        }

        tableHtml += `
        <tr data-num="${item.num}" data-table="${item.table}" class="todosMain-row" style="cursor:pointer;">
            <td class="text-center">${tableType}</td>
            <td class="text-center">${item.deadline || '-'}</td>
            <td class="text-center" style="color: #0288d1;">${item.secondord || '-'}</td>
            <td class="text-center">${item.workplacename || '-'}</td>
            <td class="text-center">${item.address || '-'}</td>
        </tr>
        `;
    });

    tableHtml += `</tbody></table>`;
    $('#todosMain-list').html(tableHtml);
}

// 테이블 행 클릭 시 모달 표시
$('#todosMain-list').on('click', '.todosMain-row', function () {
    let num = $(this).data('num');
    let table = $(this).data('table');

    if (num && table) {
        let url = '';
        let title = '';

        // 테이블에 따라 적절한 URL 및 타이틀 설정
        if (table === 'work') {
            url = '../work/view.php?menu=no&num=' + num;
            title = '잠 내역';
        } else if (table === 'ceiling') {
            url = '../ceiling/view.php?menu=no&num=' + num;
            title = '천장 내역';
        } else if (table === 'outorder') {
            url = '../outorder/view.php?menu=no&num=' + num;
            title = '외주 내역';
        }
		
        if (url) {
            popupCenter(url, title, 1800, 900);
        }
    }
});

function filterDataByProcess(data, selectedFilter) {
    if (selectedFilter === "filter_all") {
        return data; // 전체 데이터 반환
    }

	const processMapping = {
		filter_all: "전체",
		filter_al: "연차",
		filter_jamb: "쟘(jamb)",
		filter_CL: "천장(ceiling)",
		filter_jambCL: "잠+천장",
		filter_OEM: "외주",
		filter_etc: "기타"
	};

    const selectedProcess = processMapping[selectedFilter];
    if (!selectedProcess) return [];

    // return data.filter(workItem => {
        // const order_process = workItem.order_process.split(','); // order_process를 쉼표로 분리
        // return order_process.includes(selectedProcess); // 선택된 공정이 order_process에 포함되어 있는지 확인
    // });
}	

// ① 연차 HTML 생성 함수
function generateLeaveHtml(leaveDataForDay) {
  const maxDisplay = 1;                           // 화면에 바로 보여줄 개수
  const total = leaveDataForDay.length;           
  let html = `<div class="leave-container" style="position:relative;">`;
  
  // ② 앞쪽 maxDisplay개는 바로 출력
  leaveDataForDay.slice(0, maxDisplay).forEach(item => {
    html += `
      <div class="leave-info text-start" style="border:1px dashed brown; padding:2px 4px;">
        <span class="text-secondary">
          ${item.author} (<i class="bi bi-tree-fill"></i>${item.al_item})
        </span>
      </div>`;
  });

  // ③ 남는 항목이 있으면 축약 표시 + 숨겨진 목록 생성
  if (total > maxDisplay) {
    const remaining = total - maxDisplay;
    html += `
      <div class="leave-info leave-more text-danger fw-bold" 
           style="cursor:pointer; padding:2px 4px;">
        (연차) 위 신청외 ${remaining}건
      </div>
      <div class="leave-more-list" 
           style="display:none;position:absolute; top:100%; left:0; z-index:10;
                  background:#fff; border:1px solid #ccc; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    `;
    leaveDataForDay.slice(maxDisplay).forEach(item => {
      html += `
        <div class="leave-info text-start" style="border-bottom:1px dashed #eee; padding:2px 4px; width:150px; ">
          <span class="text-secondary">
            ${item.author} (<i class="bi bi-tree-fill"></i>${item.al_item})
          </span>
        </div>`;
    });
    html += `</div>`;
  }

  html += `</div>`;
  return html;
}

function todo_generateCalendarHtml(leaveData, holidayData, todoData, workData, jambData, CLData, OEMData, selectedFilter) {
    const daysOfWeek = ['일', '월', '화', '수', '목', '금', '토'];
    let date = new Date(todo_currentYear, todo_currentMonth, 1);
    let firstDay = date.getDay();
    let lastDate = new Date(todo_currentYear, todo_currentMonth + 1, 0).getDate();
    let today = new Date();

    let todayYear = today.getFullYear();
    let todayMonth = today.getMonth();
    let todayDate = today.getDate();

    function convertToKST(dateString) {
        const utcDate = new Date(dateString + 'T00:00:00Z');
        const kstDate = new Date(utcDate.getTime() + 9 * 60 * 60 * 1000);
        kstDate.setHours(0, 0, 0, 0);
        return kstDate;
    }

    function truncateWorkplaceName(name, maxLength = 15) {
        return name.length > maxLength ? name.substring(0, maxLength) + '...' : name;
    }

    let calendarHtml = '<style>';
    calendarHtml += `
        .modern-calendar-cell {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #e2e8f0;
            vertical-align: top;
            padding: 0.5rem;
            transition: all 0.2s ease;
            min-height: 100px;
        }


        .modern-calendar-cell.today-bg {
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
            border: 2px solid #0288d1;
            box-shadow: 0 2px 8px rgba(2, 136, 209, 0.1);
        }

        .modern-calendar-cell.red-day {
            background: rgba(255, 245, 245, 0.8);
        }

        .modern-calendar-cell .dayHead {
            color: #1e293b;
            font-weight: 600;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }


        .modern-calendar-cell .event {
            margin: 2px 0;
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 11px;
        }
    `;
    calendarHtml += '</style>';

    calendarHtml += '<table id="todo-list" class="modern-dashboard-table text-center">';
    calendarHtml += '<thead><tr>';
    daysOfWeek.forEach((day) => {
        let width = (day === '일' || day === '토') ? '5.8%' : '17.2%';
        let textClass = (day === '일' || day === '토') ? 'text-danger' : '';
        calendarHtml += `<th class="fs-6 text-start ${textClass}" style="width: ${width}; background: #f8fafc; color: #475569; font-weight: 600;">${day}</th>`;
    });
    calendarHtml += '</tr></thead><tbody>';

    let day = 1;
    for (let i = 0; i < 6; i++) {
        calendarHtml += '<tr>';
        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < firstDay || day > lastDate) {
                calendarHtml += '<td class="modern-calendar-cell text-start"></td>';
            } else {
                let currentDate = new Date(todo_currentYear, todo_currentMonth, day);
                currentDate.setHours(0, 0, 0, 0);

                let dayData = todoData.filter(item => new Date(item.orderdate).toDateString() === currentDate.toDateString());

                let leaveDataForDay = leaveData.filter(item => {
                    let leaveStart = convertToKST(item.al_askdatefrom);
                    let leaveEnd = convertToKST(item.al_askdateto);
                    return currentDate >= leaveStart && currentDate <= leaveEnd;
                });

                let holidayForDay = holidayData.filter(item => {
                    let startDate = convertToKST(item.startdate);
                    let endDate = item.enddate && item.enddate !== '0000-00-00' ? convertToKST(item.enddate) : startDate;
                    return currentDate >= startDate && currentDate <= endDate;
                });

                let jamdayData = jambData.filter(item => new Date(item.endworkday).toDateString() === currentDate.toDateString());
                let CLdayData = CLData.filter(item => new Date(item.deadline).toDateString() === currentDate.toDateString());
                let OEMdayData = OEMData.filter(item => new Date(item.deadline).toDateString() === currentDate.toDateString());

                let dayClass = 'modern-calendar-cell';
                if (j === 0 || j === 6) dayClass += ' red-day';
                if (currentDate.toDateString() === today.toDateString()) dayClass += ' today-bg';
                if (holidayForDay.length > 0) dayClass += ' text-danger';

                calendarHtml += `
                    <td class="${dayClass}">
                        <div class="d-flex align-items-center mb-2">
                        <div class="dayHead"
                             data-date='${formatLocalDate(currentDate)}'
                             data-todo='${JSON.stringify(dayData)}'
                             data-leave='${JSON.stringify(leaveDataForDay)}'
                             data-holiday='${JSON.stringify(holidayForDay)}'
                             data-jamb='${JSON.stringify(jamdayData)}'
                             data-cl='${JSON.stringify(CLdayData)}'
                             data-oem='${JSON.stringify(OEMdayData)}'>
                            ${day}
                        </div>
                        <button type="button" data-date='${formatLocalDate(currentDate)}' class="event btn btn-sm ms-auto" style="border:0px; background: #0288d1; color: white; border-radius: 50%; width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-plus" style="font-size: 12px;"></i></button>
                        </div>`;

                // 휴일 표시
                holidayForDay.forEach(item => {
                    calendarHtml += `<div class="mb-1"><span class='modern-data-value' style="color: #dc3545; font-weight: 600; background: rgba(220, 53, 69, 0.1); padding: 2px 6px; border-radius: 4px;">${item.comment}</span></div>`;
                });

				// todo 항목 정의
                dayData.forEach(item => {
                    let workStatus = item.work_status === '완료' ? '<span class="modern-data-value" style="color: #dc3545; font-weight: 600; background: rgba(220, 53, 69, 0.1); padding: 1px 4px; border-radius: 3px; font-size: 10px;">완료</span>' : '';
                    calendarHtml += `
                        <div class="todo-event event mb-1" data-id="${item.num}" style="padding: 3px; border-radius: 4px; background: rgba(2, 136, 209, 0.05);">
                            ${item.towhom ? `<span style="color: #475569; font-size: 10px;">${item.towhom}</span>` : ''}
                            ${item.title ? `<div style="color: #0288d1; font-weight: 600; font-size: 11px;"><i class="bi bi-megaphone"></i> ${item.title}</div>` : ''}
                            ${workStatus}
                        </div>`;
                });
				
				// 연차표시
				// leaveDataForDay.forEach(item => {
					// if(j != 0 && j != 6) { // 토요일, 일요일 제외
						// calendarHtml += `<div class="leave-info justify-content-start  text-start" style="border: 1px dashed brown;"><span class="text-secondary">${item.author} (<i class="bi bi-tree-fill"></i>${item.al_item})</span></div>`;
					// }
				// });
				 const leaveHtml = generateLeaveHtml(leaveDataForDay);
				calendarHtml += leaveHtml;
				
				jamdayData.forEach(item => {
					let title = '쟘';

					// 작업장 이름 축약 처리
					let workplacename = item.workplacename.length > 15
						? item.workplacename.substring(0, 15) + '...'
						: item.workplacename;

					// 기본 HTML 구조 시작
					calendarHtml += '<div style="font-size:11px; margin-bottom: 2px;" class="jamb-event" title="' + item.workplacename + '" data-id="' + item.num + '">';
					calendarHtml += '<span class="modern-data-value" style="color: #059669; font-weight: 600; background: rgba(5, 150, 105, 0.1); padding: 1px 4px; border-radius: 3px; font-size: 10px;">' + title + '</span> ';

					// 조건에 따른 텍스트 색상 및 스타일
					if (item.deadline !== null && item.deadline !== '' && item.deadline !== '0000-00-00') {
						calendarHtml += '<span style="color: #64748b; font-size: 10px;">' + workplacename + '</span>';
					} else {
						calendarHtml += '<span style="color: #059669; font-weight: 600; font-size: 10px;">' + workplacename + '</span>';
					}

					// 기본 HTML 구조 종료
					calendarHtml += '</div>';
				});

				CLdayData.forEach(item => {
					let title = '천장';

					// 작업장 이름 축약 처리
					let workplacename = '[' + item.type + '] ' + item.workplacename;

					if(workplacename.length > 15 )
						workplacename = workplacename.substring(0, 15) + '...';

					// 설계완료(음양모양) 처리 로직
					let drawok = ' <i class="bi bi-yin-yang"></i>';
					// 본천장 완료
					let main_draw_arr = "";
					if (item.main_draw && item.main_draw.substring(0, 2) === "20") {
						main_draw_arr = item.main_draw.substring(5, 10);
					} else if (item.bon_su < 1) {
						main_draw_arr = "X";
					}

					// LC 완료
					let lc_draw_arr = "";
					if (item.lc_draw && item.lc_draw.substring(0, 2) === "20") {
						lc_draw_arr = item.lc_draw.substring(5, 10);
					} else if (item.lc_su < 1) {
						lc_draw_arr = "X";
					}
					if (["011", "012", "013D", "025", "017", "014", "037", "038"].includes(item.type)) {
						lc_draw_arr = "X";
					}

					// 기타 완료
					let etc_draw_arr = "";
					if (item.etc_draw && item.etc_draw.substring(0, 2) === "20") {
						etc_draw_arr = item.etc_draw.substring(5, 10);
					} else if (item.etc_su < 1) {
						etc_draw_arr = "X";
					}

					let maincondition_draw = 1;
					let lccondition_draw = 1;
					let etccondition_draw = 1;

					if ((item.main_draw && item.main_draw !== "0000-00-00") || main_draw_arr === "X") {
						maincondition_draw = 0;
					}

					if ((item.lc_draw && item.lc_draw !== "0000-00-00") || lc_draw_arr === "X") {
						lccondition_draw = 0;
					}

					if ((item.etc_draw && item.etc_draw !== "0000-00-00") || etc_draw_arr === "X") {
						etccondition_draw = 0;
					}

					if (maincondition_draw || lccondition_draw || etccondition_draw) {
						drawok = '';
					}

					// HTML 구조 생성
					calendarHtml += '<div style="font-size:11px; margin-bottom: 2px;" class="CL-event" title="' + workplacename + '" data-id="' + item.num + '">';
					calendarHtml += '<span class="modern-data-value" style="color: #0288d1; font-weight: 600; background: rgba(2, 136, 209, 0.1); padding: 1px 4px; border-radius: 3px; font-size: 10px;">' + title + '</span> ';

					// 조립 미완료 항목 확인 (출고일 조건에 따른 색상 및 스타일 적용)
					let lcNotAssembled = item.lc_su > 0 && (!item.lcassembly_date || item.lcassembly_date === "0000-00-00") &&
						!['011', '012', '013D', '025', '017', '014', '037', '038'].includes(item.type);
					let mainNotAssembled = item.bon_su > 0 && (!item.mainassembly_date || item.mainassembly_date === "0000-00-00");
					let etcNotAssembled = item.etc_su > 0 && (!item.etcassembly_date || item.etcassembly_date === "0000-00-00");
					let noWorkday = !item.workday || item.workday === "0000-00-00";

					// 조립이 완료되지 않은 항목이 있고 작업일이 없는 경우 파란색으로 표시
					if ((lcNotAssembled || mainNotAssembled || etcNotAssembled) && noWorkday) {
						calendarHtml += '<span style="color: #0288d1; font-weight: 600; font-size: 10px;">' + drawok + ' ' + workplacename + '</span>';
					} else {
						calendarHtml += '<span style="color: #64748b; font-size: 10px;">' + drawok + ' ' + workplacename + '</span>';
					}

					calendarHtml += '</div>';
				});

				OEMdayData.forEach(item => {
					let title = '외주';

					// 작업장 이름 축약 처리
					let workplacename = item.workplacename.length > 11
						? item.workplacename.substring(0, 11) + '...'
						: item.workplacename;

					// HTML 생성
					calendarHtml += '<div style="font-size:11px; margin-bottom: 2px;" class="OEM-event" title="' + item.workplacename + '" data-id="' + item.num + '">';
					calendarHtml += '<span class="modern-data-value" style="color: #0ea5e9; font-weight: 600; background: rgba(14, 165, 233, 0.1); padding: 1px 4px; border-radius: 3px; font-size: 10px;">' + title + '</span> ';
					calendarHtml += '<span style="color: #475569; font-weight: 600; font-size: 10px;">[' + item.firstord + ']</span> ';

					// 작업일 유무에 따른 스타일 지정
					if (item.workday !== null && item.workday !== '' && item.workday !== '0000-00-00') {
						calendarHtml += '<span style="color: #64748b; font-size: 10px;">' + workplacename + '</span>';
					} else {
						calendarHtml += '<span style="color: #0ea5e9; font-weight: 600; font-size: 10px;">' + workplacename + '</span>';
					}

					calendarHtml += '</div>';
				});

                calendarHtml += '</td>';
                day++;
            }
        }
        calendarHtml += '</tr>';
    }

    calendarHtml += '</tbody></table>';

    $('#todo-current-period').text(`${todo_currentYear}/${('0' + (todo_currentMonth + 1)).slice(-2)}`);
    return calendarHtml;
}

// 로컬 날짜를 YYYY-MM-DD 형식으로 반환하는 함수
function formatLocalDate(date) {
    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2); // 월은 0부터 시작하므로 +1
    const day = ('0' + date.getDate()).slice(-2);
    return `${year}-${month}-${day}`;
}

// 클릭 이벤트와 모달 표시
$(document).on('click', '.dayHead', function () {
    const selectedDate = $(this).data('date');
    let todoData = $(this).data('todo') || [];
    const leaveData = $(this).data('leave') || [];
    const jambData = $(this).data('jamb') || [];
    const CLData = $(this).data('cl') || [];
    const OEMData = $(this).data('oem') || [];

    console.log(`선택된 날짜: ${selectedDate}`);
    console.log('Todo 데이터:', todoData);
    console.log('Leave 데이터:', leaveData);
    console.log('Jamb 데이터:', jambData);
    console.log('CL 데이터:', CLData);
    console.log('OEM 데이터:', OEMData);

    // JSON 파싱 처리
    try {
        todoData = typeof todoData === 'string' ? JSON.parse(todoData.replace(/[\r\n\t]/g, '')) : todoData;
    } catch (e) {
        console.error("todoData를 JSON으로 파싱하는 중 오류 발생:", e);
        todoData = []; // 파싱 실패 시 빈 배열로 설정
    }

    let modalContent = `<h4 class="fw-bold mb-4">${selectedDate} 일정</h4>`;
    if (todoData.length > 0 || leaveData.length > 0 || jambData.length > 0 || CLData.length > 0 || OEMData.length > 0) {
        modalContent += '<ul class="list-group">';

        if (todoData.length > 0) {
            modalContent += `<li class="list-group-item bg-dark text-light">해야할일</li>`;
            todoData.forEach(item => {
                modalContent += `
                    <li class="list-group-item todo-event" data-id="${item.num}" data-date="${selectedDate}">
                        <strong>${item.title || '제목 없음'}</strong><br>
                    </li>`;
            });
        }
        if (leaveData.length > 0) {
            modalContent += `<li class="list-group-item bg-secondary text-light">연차 사용</li>`;
            leaveData.forEach(item => {
                modalContent += `
                    <li class="list-group-item leave-event" data-id="${item.num}" data-date="${selectedDate}">
                        <strong>${item.author}</strong><br>
                    </li>`;
            });
        }
        if (jambData.length > 0) {
            modalContent += `<li class="list-group-item bg-success text-light">쟘출고</li>`;
            jambData.forEach(item => {
                modalContent += `
                    <li class="list-group-item jamb-event" data-id="${item.num}">
                        <strong>${item.workplacename}</strong><br>
                    </li>`;
            });
        }
        if (CLData.length > 0) {
            modalContent += `<li class="list-group-item bg-primary text-light">천장출고</li>`;
            CLData.forEach(item => {
                modalContent += `
                    <li class="list-group-item CL-event" data-id="${item.num}">
                        <strong> (${item.type}) ${item.workplacename}</strong><br>
                    </li>`;
            });
        }
        if (OEMData.length > 0) {
            modalContent += `<li class="list-group-item bg-info text-dark">외주 처리</li>`;
            OEMData.forEach(item => {
                modalContent += `
                    <li class="list-group-item OEM-event" data-id="${item.num}">
                        <strong>${item.workplacename}</strong><br>
                    </li>`;
            });
        }
        modalContent += '</ul>';
    } else {
        modalContent += '<p class="text-muted">이 날짜에 데이터가 없습니다.</p>';
    }
	
    $('#dayModal .modal-body').html(modalContent).css('font-size', '20px'); // 글자 크기 설정
    $('#dayModal').modal('show');
});

$('#dayModal').on('shown.bs.modal', function () {
    // hover 효과 제거됨 - 색상이 맞지 않아 제거
});

	
// 요소 클릭 이벤트 추가
$('#dayModal').on('click', '.todo-event', function () {
    let num = $(this).data('id');
    let date = $(this).data('date');
    loadForm(num, date);
});

$('#dayModal').on('click', '.jamb-event', function () {
    let num = $(this).data('id');
    popupCenter('../work/view.php?menu=no&num=' + num, '잠 내역', 1800, 900);
});

$('#dayModal').on('click', '.CL-event', function () {
    let num = $(this).data('id');
    popupCenter('../ceiling/view.php?menu=no&num=' + num, '천장 내역', 1800, 900);
});

$('#dayModal').on('click', '.OEM-event', function () {
    let num = $(this).data('id');
    popupCenter('../outorder/view.php?menu=no&num=' + num, '외주 내역', 1800, 900);
});


    $('#todo-calendar-container').on('click', '.event', function() {
        let num = $(this).data('id');
        let date = $(this).data('date');
        loadForm(num, date);
    });

    $('#todo-calendar-container').on('click', '.jamb-event', function() {
        let num = $(this).data('id');
	     popupCenter('../work/view.php?menu=no&num=' + num  , '잠 내역', 1800, 900);	
    });
	
    $('#todo-calendar-container').on('click', '.CL-event', function() {
        let num = $(this).data('id');
	     popupCenter('../ceiling/view.php?menu=no&num=' + num  , '천장 내역', 1800, 900);	
    });
	
    $('#todo-calendar-container').on('click', '.OEM-event', function() {
        let num = $(this).data('id');
	     popupCenter('../outorder/view.php?menu=no&num=' + num  , '외주 내역', 1800, 900);	
    });

    function loadForm(num, date) {
        let mode = num == 'undefined' ||  num ==  null ? 'insert' : 'update';
        // console.log(date);
         console.log(num);
         console.log(mode);
		 $("#mode").val(mode);
		 $("#num").val(num);
        $.ajax({
            type: "POST",
            url: "/todo/fetch_modal.php",
            data: { mode: mode, num: num, seldate : date },
            dataType: "html",
            success: function(response) {                
                document.querySelector(".modal-body .custom-card").innerHTML = response;
                $("#todoModal").show();		
				
                $(".todo-close").on("click", function() {
                    $("#todoModal").hide();
                });
				
                $("#closeBtn").on("click", function() {
                    $("#todoModal").hide();
                });
 
				// Log 파일보기
				$("#showlogBtn").click( function() {     	
					var num	= $("#num").val();
					// table 이름을 넣어야 함
					var workitem =  'todos' ;
					// 버튼 비활성화
					var btn = $(this);						
						popupCenter("/Showlog.php?num=" + num + "&workitem=" + workitem , '로그기록 보기', 500, 500);									 
					btn.prop('disabled', false);					 					 
				});	

				// 요청처리일을 입력하면 진행상태를 '완료'로 변경하고, 날짜를 지우면 '작성'으로 변경
				$('#deadline').change(function() {
					if ($(this).val()) {
						$('#work_status').val('완료');
					} else {
						$('#work_status').val('작성');
					}
				});

                // 저장 버튼
                $("#saveBtn").on("click", function() {
                    var formData = $("#board_form").serialize();

                    $.ajax({
                        url: "/todo/process.php",
                        type: "post",
                        data: formData,
                        success: function(response) {
							console.log(response);
                            Toastify({
                                text: "저장완료",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#4fbe87",
                            }).showToast();
                            $("#todoModal").hide();
                            todo_fetchCalendarData(todo_currentMonth, todo_currentYear); // 변경된 데이터만 다시 로드
                        },
                        error: function(jqxhr, status, error) {
                            console.log(jqxhr, status, error);
                        }
                    });
                });

                // 삭제 버튼
                $("#deleteBtn").on("click", function() {                    
                    var user_name = $("#user_name").val();
                    var first_writer = $("#first_writer").val();

                    if (user_name !== first_writer) {
                        Swal.fire({
                            title: '삭제불가',
                            text: "작성자만 삭제 가능합니다.",
                            icon: 'error',
                            confirmButtonText: '확인'
                        });
                        return;
                    }

                    Swal.fire({
                        title: '자료 삭제',
                        text: "삭제는 신중! 정말 삭제하시겠습니까?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '삭제',
                        cancelButtonText: '취소'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#mode").val('delete');
                            var formData = $("#board_form").serialize();

                            $.ajax({
                                url: "/todo/process.php",
                                type: "post",
                                data: formData,
                                success: function(response) {
                                    Toastify({
                                        text: "파일 삭제완료",
                                        duration: 2000,
                                        close: true,
                                        gravity: "top",
                                        position: "center",
                                        style: {
                                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                                        },
                                    }).showToast();

                                    $("#todoModal").hide();
                                    todo_fetchCalendarData(todo_currentMonth, todo_currentYear); // 변경된 데이터만 다시 로드
                                },
                                error: function(jqxhr, status, error) {
                                    console.log(jqxhr, status, error);
                                }
                            });
                        }
                    });
                });
            
				// 체크박스 클릭시 처리
				  function updateApproversInput() {
						let approvers = [];
						$('.approver-checkbox:checked').each(function() {
							approvers.push($(this).data('user-name'));
						});
						$('#towhom').val(approvers.join(', '));
					}

					$('.approver-checkbox').change(function() {
						updateApproversInput();
					});

					// 기존에 선택된 사용자를 반영합니다.
					let selectedApprovers = $('#towhom').val().split(', ');
					$('.approver-checkbox').each(function() {
						if (selectedApprovers.includes($(this).data('user-name'))) {
							$(this).prop('checked', true);
						}
					});				
			
			},
            error: function(jqxhr, status, error) {
                console.log("AJAX Error: ", status, error);
            }
        });
    }

    $('#todo-prev-month').off('click').on('click', function() {
        todo_currentMonth--;
        if (todo_currentMonth < 0) {
            todo_currentMonth = 11;
            todo_currentYear--;
        }
        todo_fetchCalendarData(todo_currentMonth, todo_currentYear);
    });

    $('#todo-next-month').off('click').on('click', function() {
        todo_currentMonth++;
        if (todo_currentMonth > 11) {
            todo_currentMonth = 0;
            todo_currentYear++;
        }
        todo_fetchCalendarData(todo_currentMonth, todo_currentYear);
    });

    $('#todo-current-month').off('click').on('click', function() {
        todo_currentMonth = new Date().getMonth();
        todo_currentYear = new Date().getFullYear();
        todo_fetchCalendarData(todo_currentMonth, todo_currentYear);
    });
	
   // 초기 라디오 버튼 상태 설정 및 필터 변경 이벤트
    function initializeRadioButtons() {
        let selectedFilter = getCookie("todoFilter") || 'filter_all';
        $('#' + selectedFilter).prop('checked', true);
        todo_fetchCalendarData(todo_currentMonth, todo_currentYear);
    }
	 
	// 일정검색시
	$('#searchTodoBtn').off('click').on('click', function () {
		var search = $('#searchTodo').val();		
		// console.log(search);
		todo_fetchCalendarData(todo_currentMonth, todo_currentYear, search);
	});	

	// 라디오 버튼 변경 이벤트 핸들러
	$('input[name="filter"]').on('change', function () {
		let selectedFilter = $('input[name="filter"]:checked').attr('id');
		setCookie("todoFilter", selectedFilter, 10);
		var search = $('#searchTodo').val();
		// console.log(search);
		todo_fetchCalendarData(todo_currentMonth, todo_currentYear, search);
	});	

// 팝업 창 열기 함수
function popupCenter(url, title, w, h) {
    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : screen.left;
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : screen.top;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const left = (width / 2) - (w / 2) + dualScreenLeft;
    const top = (height / 2) - (h / 2) + dualScreenTop;
    const newWindow = window.open(url, title, `scrollbars=yes, width=${w}, height=${h}, top=${top}, left=${left}`);

    if (window.focus) newWindow.focus();
}
    todo_fetchCalendarData(todo_currentMonth, todo_currentYear);	
    
});

// ⑤ 마우스 hover 이벤트 (jQuery) 연차리스트에 마우스를 올리면 나타나는 효과
$(document)
  .on('mouseenter', '.leave-container', function() {
    $(this).find('.leave-more-list')
           .stop(true,true)
           .slideDown(100);
  })
  .on('mouseleave', '.leave-container', function() {
    $(this).find('.leave-more-list')
           .stop(true,true)
           .slideUp(100);
  });
