let draggedItem = null;
let data = [];  // 전역 스코프에 data 변수 정의

let offsetX = 0;
let offsetY = 0;


// 권한에 따른 드래그 기능 활성화/비활성화
if (userLevel == 1) {
document.addEventListener('dragstart', function(e) {
    draggedItem = e.target;

    // 드래그 시작 시점의 오프셋 저장
    offsetX = e.offsetX;
    offsetY = e.offsetY;

    setTimeout(function() {
        draggedItem.style.display = 'none';
    }, 0);
});

document.addEventListener('dragend', function(e) {
    setTimeout(function() {
        draggedItem.style.display = '';
        draggedItem = null;
    }, 0);
});

document.getElementById('organizationChart').addEventListener('dragover', function(e) {
    e.preventDefault();
});
document.getElementById('organizationChart').addEventListener('drop', function(e) {
    e.preventDefault();
    this.append(draggedItem);
    // 저장된 오프셋을 사용하여 드롭 위치 조정
    const x = e.clientX - this.getBoundingClientRect().left - offsetX;
    const y = e.clientY - this.getBoundingClientRect().top - offsetY;



    draggedItem.style.left = x + 'px';
    draggedItem.style.top = y + 'px';
	
    console.log("draggedItem innerHTML:", draggedItem.innerHTML);  // draggedItem의 내용 확인

    // Update the JSON data
    const memberName = draggedItem.innerHTML.split("<br>")[1];
    
    console.log("Extracted member name:", memberName);  // 이름 추출 확인

    const member = data.find(m => m.name === memberName.trim()); // 이름 추출 시 공백 제거
    if (member) {
        member.x = x;
        member.y = y;
    }
    console.log("Updated data:", data);  // JSON 데이터 업데이트 확인

    // Save to storage
    updateOrganizationOnServer(data);
});

} else {
    // 권한이 없을 때 드래그 기능을 비활성화
    document.querySelectorAll('.member').forEach(function(member) {
        member.draggable = false;
    });
}


function renderOrganizationChart(data) {
    data.forEach(member => {
        const div = document.createElement('div');
        div.className = 'member';
        div.draggable = true;
        div.style.left = member.x + 'px';
        div.style.top = member.y + 'px';
        div.innerHTML = `<strong>${member.position}</strong><br>${member.name}`;
        document.getElementById('organizationChart').append(div);
    });
}


function updateOrganizationOnServer(data) {
	
    // 여기서 데이터를 콘솔에 출력
    console.log("Sending data:", data);	
	
    fetch('save_organization.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            console.log(data.message);
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function loadOrganizationFromServer() {
    fetch('get_organization.php')
    .then(response => response.json())
    .then(fetchedData => {
        data = fetchedData;  // 불러온 데이터를 data 변수에 할당
        renderOrganizationChart(data);
    })
    .catch(error => console.error('Error:', error));
}

// 페이지 로드 시 조직도 불러오기
window.onload = function() {
    loadOrganizationFromServer();
};