<?php
session_start();

// REQUEST 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";
$inspectiondate = isset($_REQUEST["inspectiondate"]) ? $_REQUEST["inspectiondate"] : "";
$pagenum = isset($_REQUEST["pagenum"]) ? $_REQUEST["pagenum"] : $num;

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 변수 초기화
$item_file_0 = "";
$item_file_1 = "";
$copied_file_0 = "";
$copied_file_1 = "";
$checkstep = "";
$workplacename = "";
$secondord = "";
$address = "";
$worker = "";
$chargedman = "";
$chargedmantel = "";
$car_insize = "";
$type = "";
$inseung = "";
$su = 0;
$bon_su = 0;
$lc_su = 0;
$etc_su = 0;
$air_su = 0;
$text = '조명천장';

// 데이터 조회
try {
    $sql = "select * from mirae8440.ceiling where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        print "검색결과가 없습니다.<br>";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        // 파일 정보
        $item_file_0 = isset($row["file_name_0"]) ? $row["file_name_0"] : "";
        $item_file_1 = isset($row["file_name_1"]) ? $row["file_name_1"] : "";
        $copied_file_0 = "../uploads/" . (isset($row["file_copied_0"]) ? $row["file_copied_0"] : "");
        $copied_file_1 = "../uploads/" . (isset($row["file_copied_1"]) ? $row["file_copied_1"] : "");
        
        // 기본 정보
        $num = $row["num"];
        $checkstep = $row["checkstep"];
        $workplacename = $row["workplacename"];
        $secondord = $row["secondord"];
        $address = $row["address"];
        $worker = $row["worker"];
        $chargedman = $row["chargedman"];
        $chargedmantel = $row["chargedmantel"];
        $car_insize = $row["car_insize"];
        
        // 제품 정보
        $type = $row["type"];
        $inseung = $row["inseung"];
        $su = (int)$row["su"];
        $bon_su = (int)$row["bon_su"];
        $lc_su = (int)$row["lc_su"];
        $etc_su = (int)$row["etc_su"];
        $air_su = (int)$row["air_su"];
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="utf-8">
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
    <link rel="stylesheet" type="text/css" href="../css/inspection_LC.css">
    
    <title>본천장/조명천장 검사증 인쇄</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/html2canvas.js"></script>    
    <script>
    // 화면 캡처 및 저장 함수
    function partShot(number) {
        var d = new Date();
        var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
        var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
        var result = '조명천장 검사서' + currentDate + currentTime + '.jpg';
        
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
            <div id="row1">mirae-inspection-<?=$num?></div>
            <div class="clear"></div>
            
            <div id="row2"><?=$workplacename?></div>
            <div class="clear"></div>
            
            <div id="row3"><?=$secondord?></div>
            <div class="clear"></div>
            
            <div id="row4"><?=$text?></div>
            <div class="clear"></div>
            
            <div id="row5"><?=$su?></div>
            <div class="clear"></div>
            
            <div id="row6"><?=$inspectiondate?></div>
            <div class="clear"></div>
            
            <div id="row7">권영철 부장</div>
            <div class="clear"></div>
            
            <div id="row8"><?=$car_insize?></div>
            
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
