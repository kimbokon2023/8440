<!doctype html>

<?php
session_start();

// REQUEST 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";
$address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : "";
$orderdate = isset($_REQUEST["outputdate"]) ? $_REQUEST["outputdate"] : "";
$workday = isset($_REQUEST["workday"]) ? $_REQUEST["workday"] : "";
$workplacename = isset($_REQUEST["workplacename"]) ? $_REQUEST["workplacename"] : "";
$chargedman = isset($_REQUEST["chargedman"]) ? $_REQUEST["chargedman"] : "";
$chargedmantel = isset($_REQUEST["chargedmantel"]) ? $_REQUEST["chargedmantel"] : "";
$secondord = isset($_REQUEST["secondord"]) ? $_REQUEST["secondord"] : "";

// 배열 초기화
$text = array();
$textnum = array();
$textset = array();
$textmemo = array();

// REQUEST 배열 데이터 할당
$text = isset($_REQUEST["text"]) ? $_REQUEST["text"] : array();
$textnum = isset($_REQUEST["textnum"]) ? $_REQUEST["textnum"] : array();
$textset = isset($_REQUEST["textset"]) ? $_REQUEST["textset"] : array();
$textmemo = isset($_REQUEST["textmemo"]) ? $_REQUEST["textmemo"] : array();

// 날짜 포맷팅
$today = date('m/d', strtotime($workday));

// 담당자 정보 조합
$chargedman = $chargedman . " 님  " . $chargedmantel;

// 페이지 번호 초기화
$pagenum = isset($_REQUEST["pagenum"]) ? $_REQUEST["pagenum"] : $num;

?>


<html lang="ko">
<head>
    <meta charset="utf-8">
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
    <link rel="stylesheet" type="text/css" href="./css/print_invoice_sheet.css">
    
    <title>본천장/조명천장 거래명세서 인쇄</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/html2canvas.js"></script>    
    <script>
    // 화면 캡처 및 저장 함수
    function partShot(number) {
        var workplace = '<?php echo $workplacename; ?>';
        var d = new Date();
        var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
        var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
        var result = '조명천장거래명세서(' + workplace + ')' + currentDate + currentTime + '.jpg';
        
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
        console.log(imgData);
        
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
            <div id="row1"><?=$orderdate?></div>
            <div class="clear"></div>
            
            <div id="row2"><?=$workplacename?></div>
            <div class="clear"></div>
            
            <div id="row3"><?=$secondord?></div>
            <div class="clear"></div>
            
            <div id="row4"><?=$address?></div>
            <div class="clear"></div>
            
            <div id="row4"><?=$chargedman?></div>
            <div class="clear"></div>
            
            <div id="row5">
                <?php
                // 품목 리스트 출력
                for ($i = 0; $i <= 11; $i++) {
                    if (isset($textnum[$i]) && $textnum[$i] >= 1) {
                        print '<div id="col1">' . $today . '</div>';
                        print '<div id="col2">' . (isset($text[$i]) ? $text[$i] : '') . '</div>';
                        print '<div id="col3">' . (isset($textset[$i]) ? $textset[$i] : '') . '</div>';
                        print '<div id="col4">' . $textnum[$i] . '</div>';
                        print '<div id="col5">' . (isset($textmemo[$i]) ? $textmemo[$i] : '') . '</div>';
                    }
                    print '<div class="clear"></div>';
                }
                ?>
            </div>
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
