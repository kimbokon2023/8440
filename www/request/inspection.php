<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * 원자재(철판) 검사서 출력
 * 
 * 입고 완료된 원자재의 검사서를 PDF로 생성하여 저장
 */

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 초기화
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$outdate = '';
$indate = '';
$outworkplace = '';
$steel_item = '';
$spec = '';
$steelnum = '';
$company = '';
$request_comment = '';
$supplier = '';
$which = '';
$model = '';
$first_writer = '';
$update_log = '';
$testnum = 0;

// 필수 데이터 체크
if (empty($num)) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";
    echo "<h3>검사서 번호가 지정되지 않았습니다.</h3>";
    echo "<button onclick='window.close();'>닫기</button>";
    echo "</body></html>";
    exit;
}

// 데이터 조회
try {
    $sql = "select * from " . $DB . ".eworks where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($count < 1) {
        echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";
        echo "<h3>검색 결과가 없습니다.</h3>";
        echo "<button onclick='window.close();'>닫기</button>";
        echo "</body></html>";
        exit;
    } else {
        // 데이터 추출
        $num = $row["num"] ?? '';
        $outdate = $row["outdate"] ?? '';
        $indate = $row["indate"] ?? '';
        $outworkplace = $row["outworkplace"] ?? '';
        $steel_item = $row["steel_item"] ?? '';
        $spec = $row["spec"] ?? '';
        $steelnum = $row["steelnum"] ?? '';
        $company = $row["company"] ?? '';
        $request_comment = $row["request_comment"] ?? '';
        $supplier = $row["supplier"] ?? '';
        $which = $row["which"] ?? '';
        $model = $row["model"] ?? '';
        $first_writer = $row["first_writer"] ?? '';
        $update_log = $row["update_log"] ?? '';

        // 시험 수량 계산
        if ((int)$steelnum > 3) {
            $testnum = 3;
        } else {
            $testnum = (int)$steelnum;
        }

        // 날짜 포맷 변환
        if ($indate != "0000-00-00" && $indate != "") {
            $indate = date("Y-m-d", strtotime($indate));
        } else {
            $indate = "";
        }
        
        if ($outdate != "0000-00-00" && $outdate != "") {
            $outdate = date("Y-m-d", strtotime($outdate));
        } else {
            $outdate = "";
        }
    }
} catch (PDOException $Exception) {
    error_log("검사서 데이터 조회 오류: " . $Exception->getMessage());
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";
    echo "<h3>데이터 조회 중 오류가 발생했습니다.</h3>";
    echo "<p>" . htmlspecialchars($Exception->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<button onclick='window.close();'>닫기</button>";
    echo "</body></html>";
    exit;
}
?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="utf-8">
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
    <link rel="stylesheet" type="text/css" href="./css/inspection.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <title>자재 검사서 출력</title>
</head>

<body>

<!-- JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="./js/jspdf.min.js"></script>
<script src="../common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<form id="board_form" name="board_form" method="post">
    <input type="hidden" id="imageURL" name="imageURL">


    <br>
    &nbsp; &nbsp; <button type="button" id="saveBtn" class="btn btn-secondary">PDF파일 저장</button>
</form>

<div id="print">
    <div id="outlineprint">
        <div class="img">
            <div class="clear"></div>
            <div id="row1"><?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="clear"></div>
            <div id="row2"><?= htmlspecialchars($steel_item, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="clear"></div>
            <div id="row3"><img src='img/hansan_sign.png'></div>
            <div class="clear"></div>
            
            <div id="row4">
                <div id="col1"><?= htmlspecialchars($indate, ENT_QUOTES, 'UTF-8') ?></div>
                <div id="col2"><?= htmlspecialchars($outworkplace, ENT_QUOTES, 'UTF-8') ?></div>
                <div id="col3"><?= htmlspecialchars($steelnum, ENT_QUOTES, 'UTF-8') ?></div>
                <div id="col4"><?= htmlspecialchars($testnum, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            
            <div class="clear"></div>
            
            <div id="row5">
                <div id="col1"><?= htmlspecialchars($indate, ENT_QUOTES, 'UTF-8') ?></div>
                <div id="col2"></div>
                <div id="col3"></div>
                <div id="col4"></div>
            </div>
            
            <div class="clear"></div>
            
            <div id="row6">
                <div id="col1">김동훈</div>
                <div id="col2">의장면</div>
            </div>
            
            <div class="clear"></div>
            
            <div id="row7">
                <div id="col1"><?= htmlspecialchars($supplier, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        </div>  <!-- end of img -->
        
        <div id="space1"></div>
        <div class="clear"></div>
    


    </div>  <!-- end of outlineprint -->
</div>  <!-- end of print -->

<canvas id="canvas" width="1300" height="1840" style="border:1px solid #d3d3d3;display:none"></canvas>

<script>
// 특정 영역 스크린샷 생성
function partShot() {
    var d = new Date();
    var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
    var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
    var result = 'inspection' + currentDate + currentTime + '.jpg';

    // 특정부분 스크린샷 (id: outlineprint 부분만)
    html2canvas(document.getElementById("outlineprint"))
    .then(function (canvas) {
        // jpg 결과값
        drawImg(canvas.toDataURL('image/jpeg'));

        const imgBase64 = canvas.toDataURL('image/jpeg', 'image/octet-stream');
        const decodImg = atob(imgBase64.split(',')[1]);

        let array = [];
        for (let i = 0; i < decodImg.length; i++) {
            array.push(decodImg.charCodeAt(i));
        }

        const file = new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
        const fileName = 'canvas_img_' + new Date().getMilliseconds() + '.jpg';
        let formData = new FormData();
        formData.append('file', file, fileName);

        $.ajax({
            type: 'post',
            url: '<?= getBaseUrl() ?>/request/imgupload.php',
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                console.log('Uploaded!');
                var cleanData = data.replaceAll("\"", "");
                cleanData = cleanData.replaceAll("\r", "");
                cleanData = cleanData.replaceAll("\n", "");
                cleanData = cleanData.replace(/ /g, '');
                $('#imageURL').val(cleanData);
                console.log(cleanData);
            },
            error: function(xhr, status, error) {
                console.error('업로드 오류:', error);
            }
        });
    });
}

// Canvas에 이미지 그리기
function drawImg(imgData) {
    return new Promise(function resolve() {
        // Canvas 부분 설정
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        
        // Canvas 초기화
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        var imageObj = new Image();
        imageObj.onload = function () {
            ctx.drawImage(imageObj, 10, 10);
        };
        imageObj.src = imgData;
    }, function reject() {});
}

// 파일 다운로드
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

// div 내용 비우기
function cleardiv() {
    $('#outlineprint').empty();
}

// 문서 준비 완료 시
$(document).ready(function() {
    // PDF 저장 버튼 클릭
    $("#saveBtn").click(function() {
        popupCenter('pdf1.php?imageURL=' + $('#imageURL').val(), 'PDF파일보기/저장', 1000, 800);
    });

    // 윈도우 창을 닫을 때 jpg 파일 삭제
    $(window).bind("beforeunload", function (e) {
        $('#row5').load('deljpg.php?imageURL=' + $('#imageURL').val());
    });
});

// 1초 후 스크린샷 생성
setTimeout(function() {
    partShot();
}, 1000);
</script>

</body>
</html>

