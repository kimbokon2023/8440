<?php
// 서명 패드 - 로컬/서버 환경 호환
require_once __DIR__ . '/../bootstrap.php';
include includePath('load_header.php');

$tablename = 'work';

// 입력값 검증 및 초기화
$num = $_REQUEST["num"] ?? '';

// 입력값 유효성 검사
if (empty($num) || !is_numeric($num)) {
    die("유효하지 않은 번호입니다.");
}

// 데이터베이스 연결
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        if (isLocal()) {
            die("데이터베이스 연결 실패: " . $e->getMessage());
        } else {
            error_log("Database connection failed in signature_pad.php: " . $e->getMessage());
            die("데이터베이스 연결에 실패했습니다. 관리자에게 문의하세요.");
        }
    }
}

$image_url = '';
$image_full_path = '';

try {
    $sql = "SELECT customer FROM $DB.$tablename WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $customerData = json_decode($row["customer"] ?? '{}', true);
        $image_url = $customerData['image_url'] ?? '';
        
        // 환경별 이미지 전체 경로 생성
        if (!empty($image_url)) {
            if (isLocal()) {
                $image_full_path = '../work/' . $image_url;
            } else {
                $image_full_path = asset('work/' . $image_url);
            }
        }
    }
} catch (PDOException $Exception) {
    if (isLocal()) {
        die("오류: " . $Exception->getMessage());
    } else {
        error_log("Database error in signature_pad.php: " . $Exception->getMessage());
        die("데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.");
    }
}
?>
    
    <title>시공완료 서명</title>
</head>
<body>

<div class="container-fluid mt-5 mb-5 justify-content-center">
    <div class="row mt-2 mb-2 justify-content-center">
        <span class="badge bg-primary fs-3 mt-2 mb-2"> 시공확인 서명 </span>
    </div>
    <div class="row justify-content-center">
	
	  
<?php if ($chkMobile): ?>
	<canvas id="signature-pad" width="400" height="1200" style="border: 1px solid black;"></canvas>
<?php else: ?>     
	<canvas id="signature-pad" width="400" height="400" style="border: 1px solid black;"></canvas>
<?php endif; ?>	
	
        
    </div>
    <div class="d-flex mt-5 mb-3 justify-content-center">
        <button type="button" class="btn btn-dark fs-1" onclick="saveSignature()"> 서명 저장 </button> &nbsp;&nbsp;
        <button type="button" class="btn btn-secondary fs-1" onclick="clearSignature()">서명 지우기</button> &nbsp;&nbsp;
        <button type="button" id="closeBtn" class="btn btn-secondary fs-1">닫기 </button> &nbsp;&nbsp;
    </div>
</div>

<script>
// 환경별 baseUrl 설정
window.baseUrl = '<?= getBaseUrl() ?>';

var canvas = document.getElementById('signature-pad');
var signaturePad = new SignaturePad(canvas, {
    minWidth: 5, // minimum line thickness
    maxWidth: 8  // maximum line thickness
});

function resizeCanvas() {
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePad.clear(); // 리셋 후 재설정
}

window.onresize = resizeCanvas;
resizeCanvas();

// 이미지 URL 불러와서 캔버스에 표시
var imageFullPath = '<?php echo htmlspecialchars($image_full_path, ENT_QUOTES); ?>';
if (imageFullPath !== '') {
    var img = new Image();
    img.src = imageFullPath;

    img.onload = function() {
        canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
        signaturePad.fromDataURL(imageFullPath);
    };
    
    img.onerror = function() {
        console.error('이미지 로드 실패:', imageFullPath);
    };
}

function saveSignature() {
    if (signaturePad.isEmpty()) {
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: "서명을 먼저 작성해주세요.",
                duration: 2000,
                close: true,
                gravity: "top",
                position: 'center',
                backgroundColor: "#f44336",
            }).showToast();
        } else {
            alert("서명을 먼저 작성해주세요.");
        }
        return;
    }
    
    var dataURL = signaturePad.toDataURL();
    sendDataToServer(dataURL);
}

function clearSignature() {
    signaturePad.clear();
}

function sendDataToServer(dataURL) {
    var xhr = new XMLHttpRequest();
    var num = '<?php echo htmlspecialchars($num, ENT_QUOTES); ?>';

    xhr.open('POST', window.baseUrl + '/p/save_signature.php?num=' + num, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: response.message || "서명이 저장되었습니다.",
                                duration: 2000,
                                close: true,
                                gravity: "top",
                                position: 'center',
                                backgroundColor: "#4fbe87",
                            }).showToast();
                        } else {
                            alert(response.message || "서명이 저장되었습니다.");
                        }
                        
                        // 부모 창 새로고침 및 창 닫기
                        setTimeout(function() {
                            if (window.opener && !window.opener.closed) {
                                opener.location.reload();
                            }
                            self.close();
                        }, 2000);
                    } else {
                        alert(response.message || "서명 저장에 실패했습니다.");
                    }
                } catch (e) {
                    console.error("응답 파싱 오류:", e);
                    alert("서명 저장 중 오류가 발생했습니다.");
                }
            } else {
                console.error("서버 오류:", xhr.status, xhr.statusText);
                alert("서명 저장 중 서버 오류가 발생했습니다.");
            }
        }
    };
    
    xhr.send('img=' + encodeURIComponent(dataURL));
}

$(document).ready(function(){
    $("#closeBtn").click(function(){
        if (window.opener && !window.opener.closed) {
            opener.location.reload();
        }
        self.close();
    });
});
</script>


</body>
</html>
