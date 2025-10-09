<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

include includePath('load_header.php');
  
// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$secondord = $_REQUEST["secondord"] ?? '';
$address = $_REQUEST["address"] ?? '';
$orderdate = $_REQUEST["outputdate"] ?? '';
$workplacename = $_REQUEST["workplacename"] ?? '';
$worker = $_REQUEST["worker"] ?? '';
$workertel = $_REQUEST["workertel"] ?? '';

// 배열 변수 초기화
$text = $_REQUEST["text"] ?? array();
$textnum1 = $_REQUEST["textnum1"] ?? array();
$textnum2 = $_REQUEST["textnum2"] ?? array();
$textnum3 = $_REQUEST["textnum3"] ?? array();
$textmemo = $_REQUEST["textmemo"] ?? array();

// 입력 검증
if (empty($num)) {
    die('필수 매개변수(num)가 누락되었습니다.');
}

// 오늘 날짜 포맷팅
$today = date("m/d", time());

// 담당자 정보 구성
$chargedman = '';
if ($worker != '') {
    $chargedman = $worker . " " . $workertel;
}
 

?>

<link rel="stylesheet" type="text/css" href="<?= asset('css/common.css') ?>?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" media="print" href="<?= asset('css/print2.css') ?>?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?= asset('css/print_invoice.css') ?>?v=<?php echo time(); ?>">

<title>쟘 출고증 인쇄</title>
</head>
<script src="<?= asset('js/html2canvas.js') ?>"></script>
<script>

// 스크린샷 생성 함수
function partShot(number) {
    var d = new Date();
    var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
    var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
    var result = 'invoice ' + currentDate + currentTime + '.jpg';
    
    // 모든 이미지가 로드될 때까지 대기
    waitForImages().then(function() {
        // 특정부분 스크린샷
        html2canvas(document.getElementById("outlineprint"), {
            allowTaint: true,
            useCORS: true,
            scale: 2, // 해상도 향상
            backgroundColor: '#ffffff',
            logging: false,
            imageTimeout: 15000 // 이미지 로딩 타임아웃 15초
        })
        // id outlineprint 부분만 스크린샷
        .then(function (canvas) {
            // jpg 결과값
            drawImg(canvas.toDataURL('image/jpeg'));
            // 이미지 저장
            saveAs(canvas.toDataURL(), result);
        }).catch(function (err) {
            console.log(err);
        });
    });
}

// 모든 이미지가 로드될 때까지 대기하는 함수
function waitForImages() {
    return new Promise(function(resolve) {
        var images = document.querySelectorAll('#outlineprint img');
        var totalImages = images.length;
        var loadedImages = 0;
        
        if (totalImages === 0) {
            // 이미지가 없으면 바로 resolve
            resolve();
            return;
        }
        
        function imageLoaded() {
            loadedImages++;
            if (loadedImages === totalImages) {
                resolve();
            }
        }
        
        images.forEach(function(img) {
            if (img.complete) {
                imageLoaded();
            } else {
                img.addEventListener('load', imageLoaded);
                img.addEventListener('error', imageLoaded); // 에러가 나도 진행
            }
        });
        
        // 5초 후 타임아웃
        setTimeout(function() {
            if (loadedImages < totalImages) {
                console.log('이미지 로딩 타임아웃, 진행합니다.');
                resolve();
            }
        }, 5000);
    });
}

// 이미지를 캔버스에 그리는 함수
function drawImg(imgData) {
    // console.log(imgData);
    // imgData의 결과값을 console 로그로 보실 수 있습니다.
    return new Promise(function resolve() {
        // 내가 결과 값을 그릴 canvas 부분 설정
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        // canvas의 뿌려진 부분 초기화
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        var imageObj = new Image();
        imageObj.onload = function () {
            ctx.drawImage(imageObj, 10, 10);
            // canvas img를 그리겠다.
        };
        imageObj.src = imgData;
        // 그릴 image데이터를 넣어준다.
    }, function reject() { });
}
// 파일 다운로드 함수
function saveAs(uri, filename) {
    var link = document.createElement('a');
    if (typeof link.download === 'string') {
        link.href = uri;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        window.open(uri);
    }
}

// div 내용 초기화 함수
function cleardiv() {
    $('#outlineprint').empty();
}

// 데이터 로드 함수 (현재 비어있음)
function load_data() {
    // 향후 데이터 로드 기능 구현 예정
}
</script>	 

<body>

<div id="print">  
<div id="outlineprint">  
    <div class="img">      
	<div class="clear"> </div>
    <div id="row1"><?= htmlspecialchars($orderdate ?? '', ENT_QUOTES) ?></div>
    <div class="clear"></div>			
    <div id="row2"><?= htmlspecialchars($workplacename ?? '', ENT_QUOTES) ?></div>
    <div class="clear"></div>		
	<div id="row3"><?= htmlspecialchars($secondord ?? '', ENT_QUOTES) ?></div>
    <div class="clear"></div>		
	<div id="row4"><?= htmlspecialchars($address ?? '', ENT_QUOTES) ?></div>
	<div class="clear"></div>	
    <div id="row4"><?= htmlspecialchars($chargedman ?? '', ENT_QUOTES) ?></div>	        
	<div class="clear"> </div>	
    <div id="row5"> 	
	
	<?php
	for ($i = 0; $i <= 9; $i++) {
        $textValue = $text[$i] ?? '';
        $textnum1Value = $textnum1[$i] ?? '';
        $textnum2Value = $textnum2[$i] ?? '';
        $textnum3Value = $textnum3[$i] ?? '';
        $textmemoValue = $textmemo[$i] ?? '';
        
        if ($textnum1Value >= 1 || $textnum2Value >= 1 || $textnum3Value >= 1 || trim($textValue) !== '') {
			echo '<div id="col1">' . htmlspecialchars($today, ENT_QUOTES) . '</div>';
			echo '<div id="col2">' . htmlspecialchars($textValue, ENT_QUOTES) . '</div>';			
			echo '<div id="col3">&nbsp;' . (empty(trim($textnum1Value)) ? '-' : htmlspecialchars($textnum1Value, ENT_QUOTES)) . '</div>';
			echo '<div id="col4">&nbsp;' . (empty(trim($textnum2Value)) ? '-' : htmlspecialchars($textnum2Value, ENT_QUOTES)) . '</div>';
			echo '<div id="col5">&nbsp;' . (empty(trim($textnum3Value)) ? '-' : htmlspecialchars($textnum3Value, ENT_QUOTES)) . '</div>';
			echo '<div id="col6">&nbsp;' . htmlspecialchars($textmemoValue, ENT_QUOTES) . '</div>';
		}
		echo '<div class="clear"></div>';
	}
	?>
	</div>

	</div>  <!-- end of row5 -->
	<div class="clear"></div> 

<div id="containers">	
	<div id="display_result">	
       <div class="clear"></div>
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  

 </div>    <!-- end of outline --> 
</div>    <!-- end of print --> 

<?php 
// 페이지 로드 완료 후 이미지 로딩 대기 후 스크린샷
echo "<script> 
// 페이지 로드 완료 후 이미지 로딩 대기 후 스크린샷
window.addEventListener('load', function() {
    setTimeout(function() {
        partShot(1); 
    }, 1000); // 1초 추가 대기
});
</script>"; 
?>

<canvas id="canvas" width="1300" height="1840" style="border:1px solid #d3d3d3; display:none;"></canvas>	
</body>
<script>
$(document).ready(function () {
    showMsgModal(10); // 다운로드 후 ctrl+j 안내함
    setTimeout(function() {
        hideMsgModal();
    }, 2000);
});

</script>	
</html>
