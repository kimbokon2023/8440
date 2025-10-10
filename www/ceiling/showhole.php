<?php
/**
 * showhole.php
 * 조명천장 홀타공 도면 표시 페이지
 */

require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함

// REQUEST/POST 변수 안전하게 초기화
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '1');

// URL 설정
$URLsave = "https://8440.co.kr/ceiling/showhole.php";

// 페이지 타이틀
$title_message = '미래기업 조명천장 홀타공';

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

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
                <!-- 네비게이션 버튼 -->
                <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
                    <button type="button" class="btn btn-secondary" onclick="self.close(); return false;">닫기</button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('1')">011_012_013_017_N20</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('2')">034_026</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('3')">031</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('4')">032</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('5')">035</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('6')">036</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('7')">037</button>&nbsp;
                    <button type="button" class="btn btn-dark" onclick="fnMove('8')">038</button>&nbsp;
                    <button type="button" id="urlsave" class="btn btn-outline-primary mt-2 mb-2">URL Copy</button>&nbsp;
                    <input type="text" name="URL" id="URL" value="<?=$URLsave?>" style="width:10px;">
                </div>
                
                <!-- 페이지 타이틀 -->
                <div class="d-flex mb-3 mt-3 justify-content-center align-items-center">
                    <span class="text-center fs-1" style="color:grey;"><?=$title_message?></span>
                </div>
                
                <!-- PDF 홀타공 도면 목록 -->
                <?php
                // PDF 파일 목록
                $pdf_arr = array('011_012_013_017_N20', '034_026', '031', '032', '035', '036', '037_new', '038');
                
                // 각 PDF 홀타공 도면을 카드로 표시
                for ($i = 0; $i < count($pdf_arr); $i++) {
                    echo '<div class="d-flex mb-3 mt-3 justify-content-center align-items-center">';
                    echo '  <span class="text-center fs-3">(' . $pdf_arr[$i] . ') 모델 홀타공도</span>';
                    echo '</div>';
                    echo '<div class="d-flex mb-3 mt-3 justify-content-center align-items-center">';
                    echo '  <div id="div' . ($i + 1) . '" class="pdf-container">';
                    echo '    <embed src="./holedwg/' . $pdf_arr[$i] . '.pdf" type="application/pdf" class="pdf-embed">';
                    echo '  </div>';
                    echo '</div>';
                }
                ?>
            </div> <!-- end of card-body -->
        </div> <!-- end of card -->
    </div> <!-- end of container-fluid -->
    
    <script>
    // 특정 홀타공 도면으로 스크롤 이동
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
    });
    </script>

</body>
</html>