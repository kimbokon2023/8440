<?php
require_once getDocumentRoot() . '/session.php';
include getDocumentRoot() . '/load_header.php';

// REQUEST 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";
$address = isset($_REQUEST["address"]) ? $_REQUEST["address"] : "";
$orderdate = isset($_REQUEST["outputdate"]) ? $_REQUEST["outputdate"] : "";
$workday = isset($_REQUEST["workday"]) ? $_REQUEST["workday"] : "";
$workplacename = isset($_REQUEST["workplacename"]) ? $_REQUEST["workplacename"] : "";
$chargedman = isset($_REQUEST["chargedman"]) ? $_REQUEST["chargedman"] : "";
$chargedmantel = isset($_REQUEST["chargedmantel"]) ? $_REQUEST["chargedmantel"] : "";
$secondord = isset($_REQUEST["secondord"]) ? $_REQUEST["secondord"] : "";

// 배열 변수 초기화
$text = isset($_REQUEST["text"]) ? $_REQUEST["text"] : array();
$textnum = isset($_REQUEST["textnum"]) ? $_REQUEST["textnum"] : array();
$textset = isset($_REQUEST["textset"]) ? $_REQUEST["textset"] : array();
$textmemo = isset($_REQUEST["textmemo"]) ? $_REQUEST["textmemo"] : array();

// 날짜 포맷팅
$today = date('m/d', strtotime($workday));

// 담당자 정보 조합
$chargedman = $chargedman . " 님  " . $chargedmantel;

// 페이지 제목
$title_message = '본천장/조명천장 출고증 인쇄';
?>

<title><?= $title_message ?></title>
<link rel="stylesheet" type="text/css" href="./css/print_invoice.css?v=3">
    
</head>
<body>

<script>
// 전역 변수
let latestBlobUrl = null;

// 화면 캡처 및 저장 함수
function partShot() {
    var workplace = '<?= $workplacename ?>';
    var d = new Date();
    var result = '조명천장출고증(' + workplace + ')_' +
        (d.getMonth() + 1) + "-" + d.getDate() + "_" +
        d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds() + '.jpg';
    
    html2canvas(document.getElementById("outlineprint"), { useCORS: true })
        .then(function(canvas) {
            canvas.toBlob(function(blob) {
                // 이전 Blob URL 해제
                if (latestBlobUrl) {
                    URL.revokeObjectURL(latestBlobUrl);
                }
                latestBlobUrl = URL.createObjectURL(blob);
                
                // 자동 다운로드
                const downloadLink = document.createElement('a');
                downloadLink.href = latestBlobUrl;
                downloadLink.download = result;
                document.body.appendChild(downloadLink);
                downloadLink.click(); // 자동 저장 실행
                document.body.removeChild(downloadLink);
                
                // 자동 열기 (새 창에서 이미지 보기)
                window.open(latestBlobUrl, '_blank');
            }, 'image/jpeg');
        })
        .catch(function(err) {
            console.log('에러 발생:', err);
        });
}
</script>

<!-- 버튼 영역 -->
<div style="margin: 20px;">
    <button class="btn btn-primary" type="button" onclick="partShot()">출고증 이미지 다운로드 및 보기</button>
</div>

<a id="downloadLink" href="" download style="display:none;">다운로드한 파일 열기</a>

<!-- 출력 영역 -->
<div id="print">
    <div id="outlineprint">
        <div class="img">
            <div class="clear"></div>
            <div id="row1"><?= $orderdate ?></div>
            <div class="clear"></div>
            
            <div id="row2"><?= $workplacename ?></div>
            <div class="clear"></div>
            
            <div id="row3"><?= $secondord ?></div>
            <div class="clear"></div>
            
            <div id="row4"><?= $address ?></div>
            <div class="clear"></div>
            
            <div id="row4"><?= $chargedman ?></div>
            <div class="clear"></div>
            
            <div id="row5">
                <?php
                // 품목 리스트 출력
                for ($i = 0; $i <= 11; $i++) {
                    if (isset($textnum[$i]) && $textnum[$i] >= 1) {
                        echo '<div class="col1">' . $today . '</div>';
                        echo '<div class="col2">' . (isset($text[$i]) ? $text[$i] : '') . '</div>';
                        echo '<div class="col3">' . (isset($textset[$i]) ? $textset[$i] : '') . '</div>';
                        echo '<div class="col4">' . $textnum[$i] . '</div>';
                        echo '<div class="col5">' . (isset($textmemo[$i]) ? $textmemo[$i] : '') . '</div>';
                        echo '<div class="clear"></div>';
                    }
                }
                ?>
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div id="containers">
            <div id="display_result">
                <!-- 결과 출력이 필요하다면 여기에 -->
            </div>
        </div>
    </div>
</div>

<canvas id="canvas" width="1300" height="1840" style="display:none; border:1px solid #d3d3d3;"></canvas>

<script>
// 파일 다운로드 함수
function downloadFile(url, filename) {
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.style.display = 'none';
    document.body.appendChild(a);
    a.click();
    
    // 다운로드 완료 후 사용자에게 링크 제공
    setTimeout(() => {
        const link = document.getElementById('downloadLink');
        link.href = url;
        link.style.display = 'block';
        link.innerText = `${filename} 열기`;
    }, 1000);
}
</script>

</body>
</html>