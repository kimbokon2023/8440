<?php
session_start();

// GET 변수 초기화
$num = isset($_GET["num"]) ? $_GET["num"] : "";
$inspectiondate = isset($_GET["inspectiondate"]) ? $_GET["inspectiondate"] : "";
$workplacename = isset($_GET["workplacename"]) ? $_GET["workplacename"] : "";
$address = isset($_GET["address"]) ? $_GET["address"] : "";
$secondord = isset($_GET["secondord"]) ? $_GET["secondord"] : "";
$text = isset($_GET["text"]) ? $_GET["text"] : "";
$inseung = isset($_GET["inseung"]) ? $_GET["inseung"] : "";
$car_insize = isset($_GET["car_insize"]) ? $_GET["car_insize"] : "";

// 페이지 번호 초기화
$pagenum = isset($_GET["pagenum"]) ? $_GET["pagenum"] : $num;

// 랜덤 번호 생성 함수
function generateRandomNumbers($count, $min, $max)
{
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $count);
}

// 랜덤 이미지 생성
$imgCount = 2;  // 필요한 랜덤 이미지 개수
$randomNumbers = generateRandomNumbers($imgCount, 1, 15);

// 이미지 베이스 URL
$baseUrl = "http://8440.co.kr/ceiling/randomimg/";

// 이미지 URL 생성
$imgUrls = array_map(function ($number) use ($baseUrl) {
    return $baseUrl . $number . ".jpg";
}, $randomNumbers);

?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="utf-8">
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" media="print" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/print.css">
    
    <title>자체시험성적서 인쇄</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/html2canvas.js"></script>    
    <script>
    // 화면 캡처 및 저장 함수
    function partShot(number) {
        var workplacename = '<?php echo $workplacename; ?>';
        var d = new Date();
        var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
        var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
        var result = '자체시험성적서' + '(' + workplacename + ')' + '.jpg';
        
        // 특정 부분 스크린샷
        html2canvas(document.getElementById("outlineprint"))
            .then(function (canvas) {
                // jpg 결과값
                drawImg(canvas.toDataURL('image/jpeg'));
                // 이미지 저장
                saveAs(canvas.toDataURL(), result);
            })
            .catch(function (err) {
                console.log(err);
            });
    }
    
    // 이미지 그리기 함수
    function drawImg(imgData) {
        return new Promise(function resolve() {
            // 결과 값을 그릴 canvas 부분 설정
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
            
            // canvas의 뿌려진 부분 초기화
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            var imageObj = new Image();
            imageObj.onload = function () {
                // canvas img를 그리겠다
                ctx.drawImage(imageObj, 10, 10);
            };
            // 그릴 image 데이터를 넣어준다
            imageObj.src = imgData;
        }, function reject() {});
    }
    
    // 파일 저장 함수
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
    
    // div 초기화 함수
    function cleardiv() {
        $('#outlineprint').empty();
    }
    
    // 데이터 로드 함수
    function load_data() {
        // 필요시 구현
    }
    </script>	

</head>

<body>

<div id="print">
    <div id="outlineprint">
        <div class="img">
            <div class="clear"></div>
            <div id="row1"><?=$workplacename?></div>
            <div class="clear"></div>
            
            <div id="row2"><?=$inspectiondate?></div>
            <div class="clear"></div>
            
            <div id="row3"><?=$inseung?> 인승</div>
            <div class="clear"></div>
            
            <div id="row4"></div>
            <div class="clear"></div>
            
            <div id="row5">
                <img src="<?=$imgUrls[0]?>" style="width:425px;">
            </div>
            <div class="clear"></div>
            
            <div id="row6">
                <img src="<?=$imgUrls[1]?>" style="width:425px;">
            </div>
            <div class="clear"></div>
            
            <div id="row7"><?=$car_insize?></div>
            <div class="clear"></div>
            
            <div id="space1"></div>
            
            <div class="clear"></div>
        </div>
        
        <div class="clear"></div>
        
        <div id="containers">
            <div id="display_result">
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<?php
// 자동 스크린샷 실행
print "<script>partShot($pagenum);</script>";
?>

<canvas id="canvas" width="1300" height="1840" style="border:1px solid #d3d3d3; display:none;"></canvas>

</body>

<script>
setTimeout(function() {
    // load_data();
}, 500);
</script>

</html>
