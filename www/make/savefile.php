<?php
/**
 * 발주서 파일 저장 및 출력
 * 로컬 및 서버 환경 모두 지원
 */

// 공통 함수 및 세션 로드
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 권한 체크
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $WebSite = "{$protocol}://{$host}/";
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 변수 초기화
$num = $_REQUEST["num"] ?? '';
$orderdate = '';
$indate = '';
$company = '';
$text = '';
$arr = array();
$recnum = 0;
$sanitized = '';

// 데이터베이스 연결 및 조회
require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $sql = "SELECT * FROM mirae8440.make WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $num = $row["num"] ?? '';
        $orderdate = $row["orderdate"] ?? '';
        $indate = $row["indate"] ?? '';
        $company = $row["company"] ?? '';
        $text = $row["text"] ?? '';
    }
    
} catch (PDOException $ex) {
    error_log("발주서 조회 오류 (num: {$num}): " . $ex->getMessage());
    die("오류: 발주서를 불러오는 중 문제가 발생했습니다.");
}

// 날짜에 요일 추가
if (!empty($orderdate)) {
    $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
    $orderdate = $orderdate . $week[date('w', strtotime($orderdate))];
}

// 텍스트 데이터 파싱
$arr = explode("|", $text);

// 파일명 생성을 위한 원본 데이터
$original = isset($arr[0]) ? $arr[0] : '';

// 파일 이름에 부적합한 문자 제거
$sanitized = preg_replace('/[\/\\\:\*\?\"\<\>\|\#\,]/', '', $original);

// 레코드 수 계산
$totalrecnum = count($arr);
$recnum = 0;

for ($i = 0; $i < $totalrecnum; $i++) {
    $tmp = explode(",", $arr[$i]);
    if (empty($tmp[3])) {
        $recnum = $i;
        break;
    }
}

// 레코드가 없을 경우 전체 개수 사용
if ($recnum == 0) {
    $recnum = $totalrecnum;
}

// HTML 헤더 로드
include getDocumentRoot() . '/load_header.php';
?>

<link rel="stylesheet" type="text/css" href="../css/common.css?v=<?=time()?>">
<link rel="stylesheet" type="text/css" media="print" href="../css/print2.css?v=<?=time()?>">
<link rel="stylesheet" type="text/css" href="../css/makeprint.css?v=<?=time()?>">
<title>발주서 출력</title>

<style>
#specail {
    font-size: 25px;
    margin-left: 200px;
    margin-top: 110px;
    margin-bottom: 155px;
}
</style>
</head>

<script>
'use strict'; 
function partShot() {
    // 모든 이미지가 로드될 때까지 기다린 후 스크린샷 실행
    waitForImages().then(function() {
        // 추가 딜레이로 배경 이미지 렌더링 보장
        setTimeout(function() {
            takeScreenshot();
        }, 500);
    });
}

function waitForImages() {
    return new Promise(function(resolve) {
        var images = document.querySelectorAll('img');
        var loadedCount = 0;
        var totalImages = images.length;
        
        // CSS 배경 이미지도 확인
        var elementsWithBg = document.querySelectorAll('[style*="background"], [class*="bg"]');
        var totalElements = totalImages + elementsWithBg.length;
        
        // 이미지가 없으면 즉시 resolve
        if (totalElements === 0) {
            resolve();
            return;
        }
        
        // 각 이미지의 로드 상태 확인
        images.forEach(function(img) {
            if (img.complete) {
                loadedCount++;
                if (loadedCount === totalElements) {
                    resolve();
                }
            } else {
                img.addEventListener('load', function() {
                    loadedCount++;
                    if (loadedCount === totalElements) {
                        resolve();
                    }
                });
                img.addEventListener('error', function() {
                    loadedCount++;
                    if (loadedCount === totalElements) {
                        resolve();
                    }
                });
            }
        });
        
        // CSS 배경 이미지 요소들에 대해서는 짧은 딜레이 후 카운트
        elementsWithBg.forEach(function(element) {
            setTimeout(function() {
                loadedCount++;
                if (loadedCount === totalElements) {
                    resolve();
                }
            }, 100);
        });
    });
}

function takeScreenshot() {
    var d = new Date();
    var sanitized = '<?=$sanitized?>';
    var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
    var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
    var result = 'paint' + currentDate + currentTime + '__' + sanitized +'.jpg';		
    
    //특정부분 스크린샷
    html2canvas(document.getElementById("outlineprint"), {
        useCORS: true,
        allowTaint: true,
        backgroundColor: null,
        scale: 2, // 더 높은 해상도
        logging: false
    })
    //id outlineprint 부분만 스크린샷
    .then(function (canvas) {
        //jpg 결과값
        drawImg(canvas.toDataURL('image/jpeg'));
        //이미지 저장
        saveAs(canvas.toDataURL(), result);
    }).catch(function (err) {
        console.log(err);
    });
}

function drawImg(imgData) {
console.log(imgData);
//imgData의 결과값을 console 로그롤 보실 수 있습니다.
return new Promise(function reslove() {
//내가 결과 값을 그릴 canvas 부분 설정
var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');
//canvas의 뿌려진 부분 초기화
ctx.clearRect(0, 0, canvas.width, canvas.height);

var imageObj = new Image();
imageObj.onload = function () {
ctx.drawImage(imageObj, 10, 10);
//canvas img를 그리겠다.
};
imageObj.src = imgData;
//그릴 image데이터를 넣어준다.

}, function reject() { });

}
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
function cleardiv() {
	 $('#outlineprint').empty();
}

// 데이터 로드 함수
function load_data() {
    var recnum = <?php echo json_encode((int)$recnum); ?>;
    var text = <?php echo json_encode($text); ?>;
    var arr = text.split('|');
    
    for (var i = 0; i < recnum; i++) {
        var tmp = arr[i].split(',');
        
        if (typeof $ !== 'undefined') {
            $('#col1').text(tmp[0] || '');
            $('#col2').text(tmp[1] || '');
            $('#col3').text(tmp[2] || '');
            $('#col4').text(tmp[3] || '');
            $('#col5').text(tmp[4] || '');
            $('#col6').text(tmp[5] || '');
            $('#col7').text(tmp[6] || '');
        }
    }
}
</script>

<body>

<div id="print">
    <div id="outlineprint">
        <div class="img">
            <div class="clear"></div>
            
            <div id="row1"><?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?></div>
            
            <div id="row2">발주일: <?=htmlspecialchars($orderdate, ENT_QUOTES, 'UTF-8')?></div>
            <div class="clear"></div>
            
            <?php if ($company == '진성케미칼'): ?>
                <div id="specail">
                    경기도 김포시 양촌읍 삼도공단로 66-1(가동)<br>
                    노하늘과장 010-3167-1154
                </div>
            <?php else: ?>
                <div id="specail">&nbsp;<br>&nbsp;</div>
            <?php endif; ?>
            
            <div class="clear"></div>	
		
            <?php
            // 색상 빈도 계산을 위한 배열
            $colorFrequency = [];
            
            // 배열을 스캔하여 각 색상의 빈도 계산
            for ($i = 0; $i < $recnum; $i++) {
                $tmp = explode(",", $arr[$i]);
                $color = isset($tmp[6]) ? $tmp[6] : '';
                
                if (isset($colorFrequency[$color])) {
                    $colorFrequency[$color]++;
                } else {
                    $colorFrequency[$color] = 1;
                }
            }
            
            // 가장 빈도가 높은 색상 찾기
            $maxColor = '';
            if (!empty($colorFrequency)) {
                $maxColor = array_keys($colorFrequency, max($colorFrequency))[0];
            }
            
            // 데이터 출력
            for ($i = 0; $i < $recnum; $i++) {
                $tmp = explode(",", $arr[$i]);
                
                echo "<div id='col1'>" . htmlspecialchars($tmp[0] ?? '', ENT_QUOTES, 'UTF-8') . "</div>";
                echo "<div id='col2'>" . htmlspecialchars($tmp[1] ?? '', ENT_QUOTES, 'UTF-8') . "</div>";
                echo "<div id='col3'>" . htmlspecialchars($tmp[2] ?? '', ENT_QUOTES, 'UTF-8') . "</div>";
                echo "<div id='col4'>" . htmlspecialchars($tmp[3] ?? '', ENT_QUOTES, 'UTF-8') . "</div>";
                echo "<div id='col5'>" . htmlspecialchars($tmp[4] ?? '', ENT_QUOTES, 'UTF-8') . "</div>";
                
                // 색상 값을 읽어들여 가장 많은 색상이 아닌 경우 강조 표시
                $color = isset($tmp[6]) ? $tmp[6] : '';
                if ($color != $maxColor && !empty($color)) {
                    echo "<div id='col6'><span class='text-primary'>(" . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . ")</span></div>";
                } else {
                    echo "<div id='col6'>" . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . "</div>";
                }
                
                echo "<div class='clear'></div>";
            }
            ?>
            
        </div> <!-- end of img -->
        <div class="clear"></div>
        
        <div id="containers">
            <div id="display_result">
                <div class="clear"></div>
            </div> <!-- end of display_result -->
        </div> <!-- end of containers -->
        
    </div> <!-- end of outlineprint -->
</div> <!-- end of print -->

<script>
// 페이지가 완전히 로드된 후 스크린샷 실행
if (document.readyState === 'complete') {
    partShot();
} else {
    window.addEventListener('load', function() {
        partShot();
    });
}
</script>

<canvas id="canvas" width="1300" height="1840" style="border:1px solid #d3d3d3; display:none;"></canvas>

<script>
$(document).ready(function() {
    if (typeof showMsgModal === 'function') {
        showMsgModal(10); // 다운로드 후 ctrl+j 안내
        setTimeout(function() {
            if (typeof hideMsgModal === 'function') {
                hideMsgModal();
            }
        }, 2000);
    }
});
</script>

</body>
</html>
