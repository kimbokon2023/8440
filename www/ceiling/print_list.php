<?php
session_start();

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 현재 날짜
$today = date("Y-m-d");
$nowday = date("Y-m-d");
$todate = date("Y-m-d");

// SQL 설정
$common = " where (date(endworkday)>=date(now())) order by endworkday "; // 출고예정일이 현재일보다 클때 조건
$sql = "select * from mirae8440.work " . $common;

// 카운터 초기화
$counter = 1;

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/work.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/html2canvas.js"></script>
    
    <title>출고예정 리스트</title>
    
    <script>
    // 스크린샷 촬영 함수
    function partShot() {
        var d = new Date();
        var currentDate = (d.getMonth() + 1) + "-" + d.getDate() + "_";
        var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
        var result = 'jambschedule' + currentDate + currentTime + '__.jpg';
        
        // 특정 영역 스크린샷
        html2canvas(document.getElementById("print_area"))
            .then(function(canvas) {
                // jpg 결과값
                drawImg(canvas.toDataURL('image/jpeg'));
                // 이미지 저장
                saveAs(canvas.toDataURL(), result);
            })
            .catch(function(err) {
                console.log(err);
            });
    }
    
    // 이미지 그리기 함수
    function drawImg(imgData) {
        console.log(imgData);
        
        return new Promise(function resolve() {
            // canvas에 결과 값 그리기
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
            
            // canvas 초기화
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            var imageObj = new Image();
            imageObj.onload = function() {
                ctx.drawImage(imageObj, 10, 10);
            };
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
        $('#print_area').empty();
    }
    </script>
</head>

<body>

<div id="print_area">
    <div class="printlist_img">
        <div id="print_list">
            <div class="print1"> 출력일자 : <?=$nowday?> </div>
            <div class="clear"></div>
            <div class="up_space"></div>
            <div class="clear"></div>
            
            <?php
            // 데이터 조회 및 출력
            try {
                $stmh = $pdo->query($sql);
                $temp = $stmh->rowCount();
                $total_row = $temp; // 전체 글수
                
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    // 기본 정보
                    $checkstep = $row["checkstep"];
                    $workplacename = $row["workplacename"];
                    $address = $row["address"];
                    $firstord = $row["firstord"];
                    $firstordman = $row["firstordman"];
                    $firstordmantel = $row["firstordmantel"];
                    $secondord = $row["secondord"];
                    $secondordman = $row["secondordman"];
                    $secondordmantel = $row["secondordmantel"];
                    $chargedman = $row["chargedman"];
                    
                    // 날짜 정보
                    $orderday = $row["orderday"];
                    $measureday = $row["measureday"];
                    $drawday = $row["drawday"];
                    $deadline = $row["deadline"];
                    $workday = $row["workday"];
                    $worker = $row["worker"];
                    $endworkday = $row["endworkday"];
                    
                    // 재질 정보
                    $material1 = $row["material1"];
                    $material2 = $row["material2"];
                    $material3 = $row["material3"];
                    $material4 = $row["material4"];
                    $material5 = $row["material5"];
                    $material6 = $row["material6"];
                    
                    // 제품 정보
                    $widejamb = $row["widejamb"];
                    $normaljamb = $row["normaljamb"];
                    $smalljamb = $row["smalljamb"];
                    
                    // 기타 정보
                    $memo = $row["memo"];
                    $regist_day = $row["regist_day"];
                    $update_day = $row["update_day"];
                    $demand = $row["demand"]; 
                    
                    
                    // 요일 추가
                    if ($endworkday != "") {
                        $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
                        $endworkday = $endworkday . $week[date('w', strtotime($endworkday))];
                    }
                    
                    // 재질 문자열 생성
                    $sum_material = $material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6;
                    
                    // 수량 문자열 생성
                    $workitem = "";
                    if ($widejamb != "") {
                        $workitem = "막판" . $widejamb . " ";
                    }
                    if ($normaljamb != "") {
                        $workitem .= "막(無)" . $normaljamb . " ";
                    }
                    if ($smalljamb != "") {
                        $workitem .= "쪽쟘" . $smalljamb . " ";
                    }
                    ?>
                    
                    <div class="print2"> <?=$counter?> </div>
                    <div class="print3"> <?=iconv_substr($endworkday, 5, 8, "utf-8")?> </div>
                    <div class="print4"> <?=iconv_substr($workplacename, 0, 20, "utf-8")?> </div>
                    <div class="print5"> <?=iconv_substr($worker, 0, 6, "utf-8")?> </div>
                    <div class="print6"> <?=iconv_substr($sum_material, 0, 40, "utf-8")?> </div>
                    <div class="print7"> <?=iconv_substr($workitem, 0, 20, "utf-8")?> </div>
                    <div class="clear"></div>
                    
                    <?php
                    $counter++;
                } // end of while
            } catch (PDOException $Exception) {
                print "오류: " . $Exception->getMessage();
            }
            ?>
        </div> <!-- end of print_list -->
    </div>
</div> <!-- end of print_area -->

<?php
// 자동 스크린샷 실행
print "<script> partShot(); </script>";
?>

<canvas id="canvas" width="1300" height="1840" style="border:1px solid #d3d3d3; display:none;"></canvas>

</body>
</html>

