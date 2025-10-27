<!-- 일정관리 모달 -->
<div id="calendarModal" class="calendar-modal" style="display:none;">
    <div class="calendar-modal-content">
        <div class="calendar-modal-header">
            <h2>📅 납품 일정 관리</h2>
            <button class="calendar-close-btn" onclick="closeCalendar()">&times;</button>
        </div>
        <div class="calendar-modal-body">
            <div class="calendar-navigation">
                <button class="calendar-nav-btn" onclick="changeMonth(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h3 id="calendarTitle"></h3>
                <button class="calendar-nav-btn" onclick="changeMonth(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="calendar-legend">
                <span><span class="legend-dot" style="background:#ff9800;"></span> 대기중</span>
                <span><span class="legend-dot" style="background:#2196f3;"></span> 진행중</span>
                <span><span class="legend-dot" style="background:#4caf50;"></span> 완료</span>
            </div>
            <div id="calendarGrid" class="calendar-grid"></div>
            <div id="calendarDetails" class="calendar-details"></div>
        </div>
    </div>
</div>

<style>
.calendar-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    animation: fadeIn 0.3s ease;
}

[data-theme="dark"] .calendar-modal {
    background: rgba(0,0,0,0.85);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.calendar-modal-content {
    background: var(--bg-secondary);
    border-radius: 15px;
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 10px 40px var(--shadow);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.calendar-modal-header {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.calendar-modal-header h2 {
    margin: 0;
    font-size: 20px;
}

.calendar-close-btn {
    background: none;
    border: none;
    color: var(--text-white);
    font-size: 32px;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 32px;
    height: 32px;
}

.calendar-modal-body {
    padding: 20px;
    max-height: calc(90vh - 80px);
    overflow-y: auto;
    color: var(--text-primary);
}

.calendar-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.calendar-navigation h3 {
    margin: 0;
    font-size: 18px;
    color: var(--text-primary);
}

.calendar-nav-btn {
    background: #667eea;
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.calendar-nav-btn:hover {
    background: #764ba2;
    transform: scale(1.1);
}

.calendar-legend {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
    font-size: 13px;
    color: var(--text-secondary);
}

.legend-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    margin-bottom: 20px;
}

.calendar-day-header {
    text-align: center;
    font-weight: 600;
    padding: 10px 5px;
    background: var(--hover-bg);
    border-radius: 5px;
    font-size: 13px;
    color: var(--text-primary);
}

.calendar-day {
    min-height: 100px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    background: var(--bg-secondary);
    overflow: hidden;
}

.calendar-day:hover {
    background: var(--hover-bg);
    border-color: #667eea;
    transform: scale(1.05);
}

.calendar-day.empty {
    background: var(--bg-primary);
    cursor: default;
}

.calendar-day.empty:hover {
    background: var(--bg-primary);
    border-color: var(--border-color);
    transform: none;
}

.calendar-day.today {
    border: 2px solid #667eea;
    background: var(--hover-bg);
}

.calendar-day-number {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
    color: var(--text-primary);
}

.calendar-day-events {
    display: flex;
    flex-direction: column;
    gap: 3px;
    margin-top: 5px;
    font-size: 11px;
}

.calendar-event-item {
    background: #f0f4ff;
    padding: 4px 6px;
    border-radius: 4px;
    border-left: 3px solid #667eea;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 10px;
    line-height: 1.3;
    transition: all 0.2s ease;
}

.calendar-event-item:hover {
    background: #e3e8ff;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.calendar-event-item.status-pending {
    border-left-color: #ff9800;
    background: #fff3e0;
}

.calendar-event-item.status-processing {
    border-left-color: #2196f3;
    background: #e3f2fd;
}

.calendar-event-item.status-completed {
    border-left-color: #4caf50;
    background: #e8f5e9;
}

.calendar-event-item.status-cancelled {
    border-left-color: #f44336;
    background: #ffebee;
}

.calendar-event-more {
    font-size: 9px;
    color: #667eea;
    margin-top: 3px;
    text-align: center;
    font-weight: 600;
}

.calendar-event-tooltip {
    position: absolute;
    background: var(--bg-secondary);
    border: 1px solid #667eea;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 4px 12px var(--shadow);
    z-index: 1000;
    min-width: 250px;
    font-size: 12px;
    display: none;
    pointer-events: none;
}

.calendar-event-tooltip.show {
    display: block;
}

.tooltip-header {
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 5px;
}

.tooltip-row {
    margin: 5px 0;
    color: var(--text-secondary);
}

.tooltip-label {
    font-weight: 600;
    color: #667eea;
}

.calendar-event-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

.calendar-event-count {
    font-size: 10px;
    background: #667eea;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    display: inline-block;
    margin-top: 2px;
}

.calendar-details {
    background: var(--hover-bg);
    border-radius: 10px;
    padding: 15px;
    max-height: 300px;
    overflow-y: auto;
}

.calendar-details h4 {
    margin: 0 0 15px 0;
    color: #667eea;
    font-size: 16px;
}

[data-theme="dark"] .calendar-details h4 {
    color: #9ba3f5;
}

.calendar-detail-item {
    background: var(--bg-secondary);
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 4px solid #667eea;
    cursor: pointer;
    transition: all 0.2s ease;
}

.calendar-detail-item:hover {
    box-shadow: 0 2px 8px var(--shadow);
    transform: translateX(5px);
}

.calendar-detail-item .order-number {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.calendar-detail-item .customer-name {
    color: var(--text-secondary);
    font-size: 13px;
    margin-bottom: 5px;
}

.calendar-detail-item .product-info {
    color: var(--text-secondary);
    font-size: 12px;
}

.calendar-detail-item.status-pending {
    border-left-color: #ff9800;
}

.calendar-detail-item.status-processing {
    border-left-color: #2196f3;
}

.calendar-detail-item.status-completed {
    border-left-color: #4caf50;
}

.empty-calendar {
    text-align: center;
    color: var(--text-secondary);
    padding: 40px 20px;
}

@media (max-width: 768px) {
    .calendar-modal-content {
        max-width: 100%;
        margin: 0;
        border-radius: 0;
    }

    .calendar-modal-header h2 {
        font-size: 18px;
    }

    .calendar-modal-body {
        padding: 15px;
    }

    .calendar-day-header {
        font-size: 11px;
        padding: 8px 3px;
    }

    .calendar-day {
        padding: 5px;
        min-height: 80px;
    }

    .calendar-day-number {
        font-size: 12px;
    }

    .calendar-event-item {
        font-size: 9px;
        padding: 3px 4px;
    }

    .calendar-event-more {
        font-size: 8px;
    }

    .calendar-event-count {
        font-size: 9px;
        padding: 1px 4px;
    }

    .calendar-navigation h3 {
        font-size: 16px;
    }

    .calendar-legend {
        font-size: 11px;
        gap: 10px;
    }

    .calendar-event-tooltip {
        min-width: 200px;
        font-size: 11px;
    }
}

@media (max-width: 576px) {
    .calendar-grid {
        gap: 3px;
    }

    .calendar-day {
        min-height: 60px;
        padding: 3px;
    }

    .calendar-event-item {
        font-size: 8px;
        padding: 2px 3px;
    }

    .calendar-day.has-events {
        background: #fff3e0;
    }

    .calendar-event-tooltip {
        min-width: 180px;
        font-size: 10px;
        padding: 8px;
    }
}
</style>

<script>
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth();
let calendarData = {};

function openCalendar() {
    document.getElementById('calendarModal').style.display = 'flex';
    loadCalendar();
}

function closeCalendar() {
    document.getElementById('calendarModal').style.display = 'none';
}

function changeMonth(delta) {
    currentMonth += delta;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    loadCalendar();
}

function loadCalendar() {
    const yearMonth = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}`;

    // 타이틀 업데이트
    document.getElementById('calendarTitle').textContent = `${currentYear}년 ${currentMonth + 1}월`;

    // 데이터 로드
    fetch(`calendar_data.php?year=${currentYear}&month=${currentMonth + 1}`)
        .then(response => response.json())
        .then(data => {
            calendarData = data;
            renderCalendar();
        })
        .catch(error => {
            console.error('Error loading calendar:', error);
            renderCalendar();
        });
}

function renderCalendar() {
    const grid = document.getElementById('calendarGrid');
    grid.innerHTML = '';

    // 요일 헤더
    const dayHeaders = ['일', '월', '화', '수', '목', '금', '토'];
    dayHeaders.forEach(day => {
        const header = document.createElement('div');
        header.className = 'calendar-day-header';
        header.textContent = day;
        grid.appendChild(header);
    });

    // 첫날의 요일 계산
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const lastDate = new Date(currentYear, currentMonth + 1, 0).getDate();

    // 빈 칸 추가
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'calendar-day empty';
        grid.appendChild(emptyDay);
    }

    // 날짜 추가
    const today = new Date();
    for (let date = 1; date <= lastDate; date++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day';

        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

        // 오늘 표시
        if (today.getFullYear() === currentYear &&
            today.getMonth() === currentMonth &&
            today.getDate() === date) {
            dayDiv.classList.add('today');
        }

        const dayNumber = document.createElement('div');
        dayNumber.className = 'calendar-day-number';
        dayNumber.textContent = date;
        dayDiv.appendChild(dayNumber);

        // 해당 날짜의 일정 표시
        if (calendarData[dateStr]) {
            const events = calendarData[dateStr];
            dayDiv.classList.add('has-events');

            const eventsDiv = document.createElement('div');
            eventsDiv.className = 'calendar-day-events';

            // 최대 3개의 일정 표시
            const displayEvents = events.slice(0, 3);
            displayEvents.forEach((event, index) => {
                const eventItem = document.createElement('div');
                eventItem.className = `calendar-event-item status-${event.status}`;

                // 거래처명 - 제품명 형태로 표시 (null 체크)
                const customerName = event.customer_name || '거래처명 없음';
                const productName = event.product_name || '제품명 없음';
                eventItem.innerHTML = `${customerName} - ${productName}`;
                eventItem.title = `${event.order_number}\n${customerName}\n${productName}\n${event.quantity} ${event.unit} | ${new Intl.NumberFormat('ko-KR').format(event.total_price)}원`;

                // Hover 시 tooltip 표시
                eventItem.addEventListener('mouseenter', (e) => showTooltip(e, event));
                eventItem.addEventListener('mouseleave', hideTooltip);

                eventsDiv.appendChild(eventItem);
            });

            // 더 많은 일정이 있으면 표시
            if (events.length > 3) {
                const moreDiv = document.createElement('div');
                moreDiv.className = 'calendar-event-more';
                moreDiv.textContent = `+${events.length - 3}개 더보기`;
                eventsDiv.appendChild(moreDiv);
            }

            dayDiv.appendChild(eventsDiv);
        }

        dayDiv.onclick = () => showDayDetails(dateStr);
        grid.appendChild(dayDiv);
    }
}

let tooltipElement = null;

function showTooltip(e, event) {
    e.stopPropagation();

    if (!tooltipElement) {
        tooltipElement = document.createElement('div');
        tooltipElement.className = 'calendar-event-tooltip';
        document.body.appendChild(tooltipElement);
    }

    const statusText = {
        'pending': '대기중',
        'processing': '진행중',
        'completed': '완료',
        'cancelled': '취소'
    };

    tooltipElement.innerHTML = `
        <div class="tooltip-header">${event.order_number}</div>
        <div class="tooltip-row"><span class="tooltip-label">거래처:</span> ${event.customer_name}</div>
        <div class="tooltip-row"><span class="tooltip-label">제품:</span> ${event.product_name}</div>
        <div class="tooltip-row"><span class="tooltip-label">수량:</span> ${event.quantity} ${event.unit}</div>
        <div class="tooltip-row"><span class="tooltip-label">금액:</span> ${new Intl.NumberFormat('ko-KR').format(event.total_price)}원</div>
        <div class="tooltip-row"><span class="tooltip-label">상태:</span> ${statusText[event.status]}</div>
    `;

    const rect = e.target.getBoundingClientRect();
    tooltipElement.style.position = 'fixed';
    tooltipElement.style.left = rect.right + 10 + 'px';
    tooltipElement.style.top = rect.top + 'px';
    tooltipElement.classList.add('show');

    // 화면 밖으로 나가면 왼쪽에 표시
    setTimeout(() => {
        const tooltipRect = tooltipElement.getBoundingClientRect();
        if (tooltipRect.right > window.innerWidth) {
            tooltipElement.style.left = rect.left - tooltipRect.width - 10 + 'px';
        }
        if (tooltipRect.bottom > window.innerHeight) {
            tooltipElement.style.top = window.innerHeight - tooltipRect.height - 10 + 'px';
        }
    }, 0);
}

function hideTooltip() {
    if (tooltipElement) {
        tooltipElement.classList.remove('show');
    }
}

function showDayDetails(dateStr) {
    const detailsDiv = document.getElementById('calendarDetails');

    if (!calendarData[dateStr] || calendarData[dateStr].length === 0) {
        detailsDiv.innerHTML = '<div class="empty-calendar">이 날짜에는 납품 일정이 없습니다</div>';
        return;
    }

    const events = calendarData[dateStr];
    const formattedDate = new Date(dateStr).toLocaleDateString('ko-KR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    let html = `<h4>${formattedDate} 납품 일정 (${events.length}건)</h4>`;

    events.forEach(event => {
        html += `
        <div class="calendar-detail-item status-${event.status}" onclick="location.href='order_view.php?id=${event.id}'">
            <div class="order-number">${event.order_number}</div>
            <div class="customer-name">📍 ${event.customer_name}</div>
            <div class="product-info">📦 ${event.product_name} | ${event.quantity} ${event.unit} | ${new Intl.NumberFormat('ko-KR').format(event.total_price)}원</div>
        </div>`;
    });

    detailsDiv.innerHTML = html;
}

function getStatusColor(status) {
    const colors = {
        'pending': '#ff9800',
        'processing': '#2196f3',
        'completed': '#4caf50',
        'cancelled': '#f44336'
    };
    return colors[status] || '#999';
}

// ESC 키로 모달 닫기
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('calendarModal').style.display === 'flex') {
        closeCalendar();
    }
});

// 모달 배경 클릭시 닫기
document.addEventListener('click', function(e) {
    if (e.target.id === 'calendarModal') {
        closeCalendar();
    }
});
</script>
