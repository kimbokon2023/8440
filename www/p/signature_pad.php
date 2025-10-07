<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';

$tablename = 'work';

if (isset($_REQUEST["num"])) {
    $num = $_REQUEST["num"];
} else {
    $num = "";
}

require_once("../lib/mydb.php");
$pdo = db_connect();

$image_url = '';

try {
    $sql = "SELECT customer FROM $DB.$tablename WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $customerData = json_decode($row["customer"], true);
    $image_url = isset($customerData['image_url']) ? $customerData['image_url'] : '';
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
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
if ('<?php echo $image_url; ?>' !== '') {
    var img = new Image();
    img.src = '<?php echo $image_url; ?>';

    img.onload = function() {
        canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
        signaturePad.fromDataURL('<?php echo $image_url; ?>');
    };
}

function saveSignature() {
    var dataURL = signaturePad.toDataURL();
    sendDataToServer(dataURL);
	setTimeout(function(){
		if (window.opener && !window.opener.closed) {
			// 부모 창에 restorePageNumber 함수가 있는지 확인
			opener.location.reload();
			self.close();
		}							
	
	}, 2000);	

}

function clearSignature() {
    signaturePad.clear();
}

function sendDataToServer(dataURL) {
    var xhr = new XMLHttpRequest();
    var num = '<?php echo $num; ?>';

    xhr.open('POST', 'save_signature.php?num=' + num , true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            Toastify({
                text: "서명이 저장되었습니다.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: 'center',
            }).showToast();
        }
    };
    xhr.send('img=' + encodeURIComponent(dataURL));
}

$(document).ready(function(){
    $("#closeBtn").click(function(){
        opener.location.reload();
        self.close();
    });
});
</script>


</body>
</html>
