<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? "";

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 변수 초기화
$outdate = "";
$indate = "";
$outworkplace = "";
$item = "";
$spec = "";
$steelnum = "";
$company = "";
$comment = "";
$supplier = "";
$which = "";
$model = "";
$first_writer = "";
$update_log = "";
$testnum = 0;

try {
    $sql = "SELECT * FROM {$DB}.request WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($count < 1) {
        error_log("검색결과가 없습니다. num: " . $num);
    } else {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $indate = $row["indate"];
        $outworkplace = $row["outworkplace"];
        $item = $row["item"];
        $spec = $row["spec"];
        $steelnum = $row["steelnum"];
        $company = $row["company"];
        $comment = $row["comment"];
        $supplier = $row["supplier"];
        $which = $row["which"];
        $model = $row["model"];
        $first_writer = $row["first_writer"];
        $update_log = $row["update_log"];
        
        // 테스트 수량 계산
        if ((int)$steelnum > 3) {
            $testnum = 3;
        } else {
            $testnum = (int)$steelnum;
        }
        
        // 날짜 포맷팅
        if ($indate != "0000-00-00") {
            $indate = date("Y-m-d", strtotime($indate));
        } else {
            $indate = "";
        }
        
        if ($outdate != "0000-00-00") {
            $outdate = date("Y-m-d", strtotime($outdate));
        } else {
            $outdate = "";
        }
    }
} catch (PDOException $ex) {
    error_log("원자재 검사서 조회 오류: " . $ex->getMessage());
}

// 로컬/서버 환경 감지
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
$baseUrl = $isLocal ? 'http://' . $host : 'http://8440.co.kr';
?>

<!DOCTYPE HTML>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>원자재 수입 검사서</title>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
    <link rel="stylesheet" type="text/css" href="./css/input_inspection.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    
    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./js/jspdf.min.js"></script>
    <script src="../common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head> 

<body>
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
                <div id="row2"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="clear"></div>
                <div id="row3"></div> <!-- <img src='img/hansan_sign.png'> -->
                <div class="clear"></div>
                
                <div id="row4">
                    <?php
                    echo '<div id="col1">' . htmlspecialchars($indate, ENT_QUOTES, 'UTF-8') . '</div>';
                    echo '<div id="col2">' . htmlspecialchars($outworkplace, ENT_QUOTES, 'UTF-8') . '</div>';
                    echo '<div id="col3">' . htmlspecialchars($steelnum, ENT_QUOTES, 'UTF-8') . '</div>';
                    echo '<div id="col4">' . htmlspecialchars($testnum, ENT_QUOTES, 'UTF-8') . '</div>';
                    ?>
                </div>
                <div class="clear"></div>
                
                <div id="row5">
                    <?php
                    echo '<div id="col1">' . htmlspecialchars($indate, ENT_QUOTES, 'UTF-8') . '</div>';
                    echo '<div id="col2"></div>';
                    echo '<div id="col3"></div>';
                    echo '<div id="col4"></div>';
                    ?>
                </div>
                <div class="clear"></div>
                
                <div id="row6">
                    <?php
                    echo '<div id="col1">김영무</div>';
                    echo '<div id="col2">의장면, 비닐상태, 긁힘여부 등</div>';
                    ?>
                </div>
                <div class="clear"></div>
                
                <div id="row7">
                    <?php
                    echo '<div id="col1">' . htmlspecialchars($supplier, ENT_QUOTES, 'UTF-8') . '</div>';
                    ?>
                </div>



		</div>  <!-- end of row4-->	
        <div id="space1">     </div> 
	<div class="clear"> </div>	
    

	   

  </div>    <!-- end of outline --> 
  
  
  
</div>    <!-- end of print --> 

<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3;display:none"> </canvas>	
</body>

    <script>
        var baseUrl = '<?= $baseUrl ?>';
        
        function partShot() {
            var d = new Date();
            var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
            var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
            var result = 'inspection' + currentDate + currentTime + '.jpg';
            
            // 특정부분 스크린샷
            html2canvas(document.getElementById("outlineprint"))
                .then(function (canvas) {
                    // jpg 결과값
                    drawImg(canvas.toDataURL('image/jpeg'));
                    
                    var imgBase64 = canvas.toDataURL('image/jpeg', 'image/octet-stream');
                    var decodImg = atob(imgBase64.split(',')[1]);
                    
                    var array = [];
                    for (var i = 0; i < decodImg.length; i++) {
                        array.push(decodImg.charCodeAt(i));
                    }
                    
                    var file = new Blob([new Uint8Array(array)], { type: 'image/jpeg' });
                    var fileName = 'canvas_img_' + new Date().getMilliseconds() + '.jpg';
                    var formData = new FormData();
                    formData.append('file', file, fileName);
                    
                    $.ajax({
                        type: 'post',
                        url: baseUrl + '/request/imgupload.php',
                        cache: false,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (response) {
                            console.log('Uploaded !');
                            
                            var filename = '';
                            if (response.success && response.filename) {
                                filename = response.filename;
                            } else if (typeof response === 'string') {
                                // 기존 방식 호환 (문자열 응답)
                                filename = response.replace(/"/g, "");
                                filename = filename.replace(/\r/g, "");
                                filename = filename.replace(/\n/g, "");
                                filename = filename.replace(/ /g, '');
                            }
                            
                            $('#imageURL').val(filename);
                            console.log(filename);
                        },
                        error: function (xhr, status, error) {
                            console.error('업로드 실패:', error);
                        }
                    });
                });
        }  // end of function
  
        function drawImg(imgData) {
            // imgData의 결과값을 console 로그로 보실 수 있습니다.
            return new Promise(function (resolve, reject) {
                // 내가 결과 값을 그릴 canvas 부분 설정
                var canvas = document.getElementById('canvas');
                var ctx = canvas.getContext('2d');
                // canvas의 뿌려진 부분 초기화
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                var imageObj = new Image();
                imageObj.onload = function () {
                    ctx.drawImage(imageObj, 10, 10);
                    // canvas img를 그리겠다.
                    resolve();
                };
                imageObj.onerror = function () {
                    reject();
                };
                imageObj.src = imgData;
                // 그릴 image데이터를 넣어준다.
            });
        }
        
        function saveAs(uri, filename) {
            var link = document.createElement('a');
            if (typeof link.download === 'string') {
                // 서버를 활용해서
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
        
        $(document).ready(function () {
            
            $("#saveBtn").click(function () {
                // $("#board_form").submit();
                popupCenter('pdf1.php?imageURL=' + $('#imageURL').val(), 'PDF파일보기/저장', 1000, 800);
            });
            
            // 윈도우 창을 닫을때 jpg 파일 삭제함
            $(window).bind("beforeunload", function (e) {
                $('#row5').load('deljpg.php?imageURL=' + $('#imageURL').val());
            });
        });
        
        // 1초 후 스크린샷 자동 실행
        setTimeout(function () {
            partShot();
        }, 1000);
    </script>

</html>
