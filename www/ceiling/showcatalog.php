<?php
/**
 * showcatalog.php
 * 미래기업 조명천장 카탈로그 표시 페이지
 */

session_start();

require_once __DIR__ . '/../bootstrap.php';

// REQUEST/POST 변수 초기화 (PHP 7.3 호환)
$check = isset($_REQUEST['check']) ? $_REQUEST['check'] : (isset($_POST['check']) ? $_POST['check'] : '1');

// 로컬과 서버 환경에서 모두 동작하도록 동적 URL 생성
$URLsave = getBaseUrl() . "/ceiling/showcatalog.php";

// 페이지 타이틀
$title_message = '미래기업 조명천장 카다로그';

?>

<?php include includePath('load_header.php'); ?>

<title><?=$title_message?></title>
</head>

<style>
    .fill {
        object-fit: fill;
    }
    
    .contain {
        object-fit: contain;
    }
    
    .cover {
        width: auto;
        height: auto;
        object-fit: cover;
    }
    
    .img {
        width: auto;
        height: auto;
    }
    
    .pdf-container {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    
    .pdf-embed {
        width: 100%;
        height: 800px;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <!-- 페이지 타이틀 -->
                <div class="d-flex mb-4 mt-4 justify-content-center align-items-center">
                    <span class="text-center fs-2" style="color:grey;"><?=$title_message?></span>
                </div>
                
                <!-- 카탈로그 네비게이션 버튼 -->
                <div class="d-flex mb-4 mt-1 justify-content-center align-items-center">
                    <button type="button" class="btn btn-dark" onclick="fnMove('1')">011</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('2')">012</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('3')">013</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('4')">017</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('5')">026</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('6')">031</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('7')">032</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('8')">034</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('9')">035</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('10')">036</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('11')">050</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('12')">N20</button>&nbsp;
                    <button type="button" id="urlsave" class="btn btn-outline-primary mt-2 mb-2">URL Copy</button>&nbsp;
                    <input type="text" name="URL" id="URL" value="<?=$URLsave?>" style="width:10px;">
                    <button type="button" class="btn btn-secondary mx-3" onclick="self.close(); return false;">&times; 닫기</button>
                </div>
                
                <!-- PDF 카탈로그 목록 -->
                <?php
                // PDF 파일 목록
                $pdf_arr = array('011', '012', '013', '017', '026', '031', '032', '034', '035', '036', '050', 'N20');
                
                // 각 PDF 카탈로그를 카드로 표시
                for ($i = 0; $i < count($pdf_arr); $i++) {
                    echo '<div class="card mb-5 mt-5">';
                    echo '  <div class="card-header text-center">';
                    echo '    <h3>LC-' . $pdf_arr[$i] . ' 모델</h3>';
                    echo '  </div>';
                    echo '  <div class="card-body">';
                    echo '    <div id="div' . ($i + 1) . '" class="pdf-container text-center">';
                    echo '      <embed src="./catalog/' . $pdf_arr[$i] . '.pdf" type="application/pdf" class="pdf-embed">';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
                }
                ?>
            </div> <!-- end of card-body -->
        </div> <!-- end of card -->
    </div> <!-- end of container-fluid -->
    
    <script>
    // 특정 카탈로그로 스크롤 이동
    function fnMove(seq) {
        var offset = $("#div" + seq).offset();
        $('html, body').animate({
            scrollTop: offset.top
        }, 400);
    }
    
    // 문서 준비 완료 이벤트
    $(document).ready(function() {
        // URL 복사 버튼 클릭 이벤트
        $("#urlsave").click(function() {
            var content = document.getElementById('URL');
            content.select();
            
            document.execCommand('copy');
            
            // Toastify를 사용하여 토스트 메시지 표시
            Toastify({
                text: "URL이 복사되었습니다. 붙여넣기 하세요",
                duration: 3000,
                close: true,
                gravity: "top",
                position: 'right'
            }).showToast();
        });
        
        // 방문 기록 남김
        var title = '<?php echo $title_message; ?>';
        saveMenuLog(title);
    });
    </script>

</body>
</html>