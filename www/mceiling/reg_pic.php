<?php
/**
 * 사진 등록/수정 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$parent = $num;
$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '0');

// 미선언 변수 초기화
$childnum = '';
$mode = '';
$filename1 = '';
$imgurl1 = '';
$workplacename = '';

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 작업 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.ceiling WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $workplacename = $row["workplacename"] ?? '';
    }
    
} catch (PDOException $ex) {
    error_log("작업 정보 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "오류: 작업 정보를 불러오는 중 문제가 발생했습니다.";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사진등록/수정</title>
    
    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" 
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="../css/partner.css" type="text/css" />
    
    <style>
        .progress {
            margin: 10px;
            width: 700px;
        }
        
        .blink {
            -webkit-animation: blink 1.05s linear infinite;
            -moz-animation: blink 1.05s linear infinite;
            -ms-animation: blink 1.05s linear infinite;
            -o-animation: blink 1.05s linear infinite;
            animation: blink 1.05s linear infinite;
        }
        
        @-webkit-keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 1; }
            50.01% { opacity: 0; }
            100% { opacity: 0; }
        }
        
        @-moz-keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 1; }
            50.01% { opacity: 0; }
            100% { opacity: 0; }
        }
        
        @-ms-keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 1; }
            50.01% { opacity: 0; }
            100% { opacity: 0; }
        }
        
        @-o-keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 1; }
            50.01% { opacity: 0; }
            100% { opacity: 0; }
        }
        
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 1; }
            50.01% { opacity: 0; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div id="top-menu"></div>
    
    <br>
    <div class="row">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h1 class="display-1 text-left">
            <input type="button" class="btn btn-secondary btn-lg" value="닫기" onclick="self.close();">
        </h1>
    </div>
    
    <br><br>
    
    <form id="board_form" name="board_form" method="post" action="pic_insert.php" enctype="multipart/form-data">
        <input type="hidden" id="childnum" name="childnum" value="<?= htmlspecialchars($childnum, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="check" name="check" value="<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="parent" name="parent" value="<?= htmlspecialchars($parent, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="workplacename" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>">
        
        <div class="container">
            <div class="row">
                <h1 class="display-5 font-center text-center">사진 등록/수정</h1>
            </div>
            
            <div class="row">
                <div id="progressbar" class="blink" style="display:none;">
                    <div class="row"></div>
                    <br>
                    <h1 class="display-1 text-left">
                        사진등록을 서버에 저장중입니다.<br>(잠시만 기다려주세요.)
                    </h1>
                    <div class="progress">
                        <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" 
                             role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress"></span>
                        </div>
                    </div>
                    <div class="row"></div>
                    <br>
                </div>
            </div>
            
            <br>
            
            <div class="row">
                <h1 class="display-5 font-center text-left">
                    현장명: <?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>
                    <br><br>
                    
                    <?php if (!empty($filename1)): ?>
                        기존 업로드 파일 있음: <?= htmlspecialchars($filename1, ENT_QUOTES, 'UTF-8') ?>
                        <br>
                        <img src="<?= htmlspecialchars($imgurl1, ENT_QUOTES, 'UTF-8') ?>" alt="업로드된 이미지">
                        <br><br>
                    <?php endif; ?>
                    
                    <div class="row">
                        <input id="upfile" name="upfile[]" class="input" type="file" multiple>
                    </div>
                    
                    <br><br>
                    
                    <div class="row">
                        <h1 class="display-1 text-left">
                            <input type="button" class="btn btn-primary btn-lg" value="서버에 저장하기" onclick="pro_submit()">
                        </h1>
                    </div>
                </h1>
            </div>
        </div>
    </form>
</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    var imgObj = new Image();
    
    /**
     * 이미지 윈도우 표시
     */
    window.showImgWin = function(imgName) {
        imgObj.src = imgName;
        setTimeout(function() {
            createImgWin(imgObj);
        }, 100);
    };
    
    function createImgWin(imgObj) {
        if (!imgObj.complete) {
            setTimeout(function() {
                createImgWin(imgObj);
            }, 100);
            return;
        }
        window.open("", "imageWin", "width=" + imgObj.width + ",height=" + imgObj.height);
    }
    
    /**
     * 숫자 포맷팅
     */
    window.inputNumberFormat = function(obj) {
        if (obj) {
            obj.value = comma(uncomma(obj.value));
        }
    };
    
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
    
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    
    /**
     * 텍스트 입력 (10% 증가)
     */
    window.input_Text = function() {
        var testElem = document.getElementById("test");
        if (testElem) {
            testElem.value = comma(Math.floor(uncomma(testElem.value) * 1.1));
        }
    };
    
    /**
     * AS 이력 복사
     */
    window.copy_below = function() {
        // 필요시 구현
    };
    
    /**
     * 폼 제출
     */
    window.pro_submit = function() {
        if (typeof $ !== 'undefined') {
            $('#progressbar').show();
            $('#progressbar1').show();
            $('#progressbar2').show();
            $('#board_form').submit();
        } else {
            document.getElementById('progressbar').style.display = 'block';
            var progressbar1 = document.getElementById('progressbar1');
            var progressbar2 = document.getElementById('progressbar2');
            if (progressbar1) progressbar1.style.display = 'block';
            if (progressbar2) progressbar2.style.display = 'block';
            document.getElementById('board_form').submit();
        }
    };
    
})();
</script>
