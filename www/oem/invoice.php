<!DOCTYPE html>
<?php
/**
 * 서한컴퍼니 발주서 인쇄
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 모든 변수를 ?? '' 형태로 초기화
$num = $_REQUEST["num"] ?? '';
$address = $_REQUEST["address"] ?? '';
$firstord = $_REQUEST["firstord"] ?? '';
$secondord = $_REQUEST["secondord"] ?? '';
$orderdate = $_REQUEST["outputdate"] ?? '';
$workplacename = $_REQUEST["workplacename"] ?? '';
$chargedman = $_REQUEST["chargedman"] ?? '';
$chargedmantel = $_REQUEST["chargedmantel"] ?? '';
$startday = $_REQUEST["startday"] ?? '';
$delitext = $_REQUEST["delitext"] ?? '';
$deadline = $_REQUEST["deadline"] ?? '';
$pagenum = $_REQUEST["pagenum"] ?? '1';

// 납기일에 요일 추가
if ($deadline != "") {
    $week = ["(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)"];
    $deadline = $deadline . $week[date('w', strtotime($deadline))];
}

// 배열 변수 초기화
$text = $_REQUEST["text"] ?? [];
$item = $_REQUEST["item"] ?? [];
$spec = $_REQUEST["spec"] ?? [];
$carsize = $_REQUEST["carsize"] ?? [];
$item_memo = $_REQUEST["item_memo"] ?? [];
$textnum = $_REQUEST["textnum"] ?? [];
$textset = $_REQUEST["textset"] ?? [];

$today = date("m/d", time());
?>

<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>서한컴퍼니 발주서 인쇄</title>
    
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
    <link rel="stylesheet" type="text/css" href="../css/outorder_invoice.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/html2canvas.js"></script>
</head>

<body>

<div id="print">
    <div id="outlineprint">
        <div class="img">
            <div class="clear"></div>
            
            <div id="row1">
                <?= htmlspecialchars($firstord, ENT_QUOTES, 'UTF-8') ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                발주일 : <?= htmlspecialchars($startday, ENT_QUOTES, 'UTF-8') ?>
            </div>
            
            <div class="clear"></div>
            
            <div id="row2">
                <?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') ?>
            </div>
            
            <input type="hidden" id="workplacename" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="clear"></div>
            
            <div id="row3">
                <?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>
            </div>
            
            <div class="clear"></div>
            <div class="clear"></div>
            
            <div id="row4">
                <?php
                for ($i = 0; $i <= 9; $i++) {
                    $current_textnum = isset($textnum[$i]) ? $textnum[$i] : 0;
                    
                    if ($current_textnum >= 1) {
                        $current_text = isset($text[$i]) ? htmlspecialchars($text[$i], ENT_QUOTES, 'UTF-8') : '';
                        $current_item = isset($item[$i]) ? htmlspecialchars($item[$i], ENT_QUOTES, 'UTF-8') : '';
                        $current_spec = isset($spec[$i]) ? htmlspecialchars($spec[$i], ENT_QUOTES, 'UTF-8') : '';
                        $current_textset = isset($textset[$i]) ? htmlspecialchars($textset[$i], ENT_QUOTES, 'UTF-8') : '';
                        $current_carsize = isset($carsize[$i]) ? htmlspecialchars($carsize[$i], ENT_QUOTES, 'UTF-8') : '';
                        $current_item_memo = isset($item_memo[$i]) ? htmlspecialchars($item_memo[$i], ENT_QUOTES, 'UTF-8') : '';
                        
                        echo '<div id="col1">' . ($i + 1) . '</div>';
                        echo '<div id="col2">&nbsp;' . $current_text . '</div>';
                        echo '<div id="col3">&nbsp;' . $current_item . '</div>';
                        echo '<div id="col4">&nbsp;' . $current_spec . '</div>';
                        echo '<div id="col5">&nbsp;' . $current_textnum . '</div>';
                        echo '<div id="col6">&nbsp;' . $current_textset . '</div>';
                        echo '<div id="col7">&nbsp;' . $current_carsize . '</div>';
                        echo '<div id="col8">&nbsp;' . $current_item_memo . '</div>';
                    }
                    echo '<div class="clear"></div>';
                }
                ?>
            </div>
            
            <div id="row5">
                <?php
                echo '<div id="col1">1. 납    기 : ' . htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') . '</div><div class="clear"></div>';
                echo '<div id="col1">2. 납 품 처 :<b> ' . htmlspecialchars($address, ENT_QUOTES, 'UTF-8') . ' </b></div><div class="clear"></div>';
                echo '<div id="col1">3. 담 당 자 : ' . htmlspecialchars($chargedman, ENT_QUOTES, 'UTF-8') . '  (tel) ' . htmlspecialchars($chargedmantel, ENT_QUOTES, 'UTF-8') . '</div><div class="clear"></div>';
                echo '<div id="col1">4. 운 반 비 : ' . htmlspecialchars($delitext, ENT_QUOTES, 'UTF-8') . '</div><div class="clear"></div>';
                echo '<div id="col1">5. 주의사항 : 박스에 현장명 표기요망</div><div class="clear"></div>';
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

<canvas id="canvas" width="1300" height="1840" style="border:1px solid #d3d3d3; display:none;"></canvas>

<script type="text/javascript">
(function() {
    'use strict';
    
    /**
     * 특정 부분 스크린샷 저장
     */
    window.partShot = function(number) {
        var workplaceInput = document.getElementById('workplacename');
        if (!workplaceInput) return;
        
        var tmp = workplaceInput.value;
        tmp = tmp.replace(',', '-');
        
        var d = new Date();
        var currentDate = '  ' + d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
        var currentTime = d.getHours() + '_' + d.getMinutes() + '_' + d.getSeconds();
        var result = tmp + currentDate + '.jpg';
        
        var targetElement = document.getElementById('outlineprint');
        if (!targetElement) return;
        
        // html2canvas로 스크린샷 생성
        if (typeof html2canvas !== 'undefined') {
            html2canvas(targetElement)
                .then(function(canvas) {
                    var imgData = canvas.toDataURL('image/jpeg');
                    drawImg(imgData);
                    saveAs(imgData, result);
                })
                .catch(function(err) {
                    console.error('스크린샷 생성 오류:', err);
                });
        }
    };
    
    /**
     * 이미지 그리기
     */
    function drawImg(imgData) {
        console.log('이미지 데이터:', imgData);
        
        return new Promise(function(resolve, reject) {
            var canvas = document.getElementById('canvas');
            if (!canvas) {
                reject('Canvas 요소를 찾을 수 없습니다.');
                return;
            }
            
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            var imageObj = new Image();
            imageObj.onload = function() {
                ctx.drawImage(imageObj, 10, 10);
                resolve();
            };
            imageObj.onerror = function() {
                reject('이미지 로드 실패');
            };
            imageObj.src = imgData;
        });
    }
    
    /**
     * 파일 저장
     */
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
    
    /**
     * div 내용 초기화
     */
    window.cleardiv = function() {
        if (typeof $ !== 'undefined') {
            $('#outlineprint').empty();
        } else {
            var outlineElement = document.getElementById('outlineprint');
            if (outlineElement) {
                outlineElement.innerHTML = '';
            }
        }
    };
    
    /**
     * 데이터 로드 (예약)
     */
    window.load_data = function() {
        // 필요시 구현
    };
    
    // 페이지 로드 후 스크린샷 생성
    setTimeout(function() {
        var pagenum = <?php echo json_encode($pagenum, JSON_UNESCAPED_UNICODE); ?>;
        partShot(pagenum);
    }, 500);
    
})();
</script>

</body>
</html>
