<?php
/**
 * 전화번호 조회 팝업
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 변수 초기화 (?? '' 형태)
$data1 = $_REQUEST["data1"] ?? '';
$data2 = $_REQUEST["data2"] ?? '';
$data3 = $_REQUEST["data3"] ?? '';
$search = $_REQUEST["search"] ?? '';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 테이블명 화이트리스트 (SQL Injection 방지)
$allowed_tables = ['oem', 'work', 'member', 'ceiling', 'steel'];
if (!in_array($data1, $allowed_tables)) {
    error_log("허용되지 않은 테이블 접근 시도: {$data1}");
    die("오류: 허용되지 않은 테이블입니다.");
}

// 컬럼명 화이트리스트 (SQL Injection 방지)
$allowed_columns = [
    'chargedman', 'chargedmantel', 'firstordman', 'firstordmantel',
    'secondordman', 'secondordmantel', 'name', 'hp', 'delicompany',
    'workplacename', 'address', 'memo'
];
if (!in_array($data2, $allowed_columns)) {
    error_log("허용되지 않은 컬럼 접근 시도: {$data2}");
    die("오류: 허용되지 않은 컬럼입니다.");
}

if (!in_array($data3, $allowed_columns)) {
    error_log("허용되지 않은 컬럼 접근 시도: {$data3}");
    die("오류: 허용되지 않은 컬럼입니다.");
}

require_once("../lib/mydb.php");
$pdo = db_connect();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전번 조회</title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
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
        
        .tel-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .tel-item:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="row">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" class="btn btn-secondary btn-lg" value="닫기" onclick="self.close();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<br>

<input type="hidden" id="data1" name="data1" value="<?= htmlspecialchars($data1, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" id="data2" name="data2" value="<?= htmlspecialchars($data2, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" id="data3" name="data3" value="<?= htmlspecialchars($data3, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" id="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">

<div class="container">
    <div class="row">
        <h3 class="display-6 font-center text-left">전번 조회</h3>
    </div>
    
    <?php
    $tmp = [];
    $Searchcounter = 0;
    
    try {
        // Prepared Statement 사용 (SQL Injection 방지)
        // 테이블명과 컬럼명은 이미 화이트리스트로 검증됨
        $sql = "SELECT * FROM {$DB}.{$data1} WHERE {$data2} LIKE ? ORDER BY num DESC";
        $stmh = $pdo->prepare($sql);
        $searchTerm = "%{$search}%";
        $stmh->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmh->execute();
        
        echo '<div class="input-group p-1 mb-1">';
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $Searchcounter++;
            $tmpman = $row[$data2] ?? '';
            $tmptel = $row[$data3] ?? '';
            
            $tmpStr = $tmpman;
            
            if ($Searchcounter == 1) {
                array_push($tmp, $tmpStr);
            }
            
            $is_check = 0;
            foreach ($tmp as $value) {
                if ($value != $tmpStr) {
                    array_push($tmp, $tmpStr);
                    $is_check = 1;
                    break;
                }
            }
            
            if ($is_check == 1 || $Searchcounter == 1) {
                // XSS 방지를 위한 htmlspecialchars 적용
                $safe_tmpman = htmlspecialchars($tmpman, ENT_QUOTES, 'UTF-8');
                $safe_tmptel = htmlspecialchars($tmptel, ENT_QUOTES, 'UTF-8');
                
                // JavaScript에서 사용할 때를 위한 추가 이스케이핑
                $js_tmpman = addslashes($tmpman);
                $js_tmptel = addslashes($tmptel);
    ?>
                <a href="#" onclick="callFunction('<?= $js_tmpman ?>', '<?= $js_tmptel ?>'); return false;" class="tel-item">
                    <span class="input-group-text">
                        <?= $safe_tmpman ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $safe_tmptel ?>
                    </span>
                </a>
    <?php
            }
        }
        
        echo '</div>';
        
    } catch (PDOException $ex) {
        error_log("전번 조회 오류: " . $ex->getMessage());
        echo '<div class="alert alert-danger">오류: 데이터를 불러오는 중 문제가 발생했습니다.</div>';
    }
    ?>
</div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var imgObj = new Image();
    
    /**
     * 이미지 창 표시
     */
    window.showImgWin = function(imgName) {
        imgObj.src = imgName;
        setTimeout(function() {
            createImgWin(imgObj);
        }, 100);
    };
    
    /**
     * 이미지 창 생성
     */
    function createImgWin(imgObj) {
        if (!imgObj.complete) {
            setTimeout(function() {
                createImgWin(imgObj);
            }, 100);
            return;
        }
        var imageWin = window.open("", "imageWin", "width=" + imgObj.width + ",height=" + imgObj.height);
    }
    
    /**
     * 숫자 포맷팅
     */
    window.inputNumberFormat = function(obj) {
        if (obj && obj.value) {
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
     * 텍스트 입력
     */
    window.input_Text = function() {
        var testElement = document.getElementById("test");
        if (testElement) {
            testElement.value = comma(Math.floor(uncomma(testElement.value) * 1.1));
        }
    };
    
    /**
     * 아래로 복사
     */
    window.copy_below = function() {
        // 필요시 구현
    };
    
    /**
     * 폼 제출
     */
    window.pro_submit = function() {
        var progressbar = document.getElementById('progressbar');
        var progressbar1 = document.getElementById('progressbar1');
        var progressbar2 = document.getElementById('progressbar2');
        var boardForm = document.getElementById('board_form');
        
        if (progressbar) progressbar.style.display = 'block';
        if (progressbar1) progressbar1.style.display = 'block';
        if (progressbar2) progressbar2.style.display = 'block';
        if (boardForm) boardForm.submit();
    };
    
    /**
     * 전화번호 선택 시 부모 창에 값 전달
     */
    window.callFunction = function(name, tel) {
        var chargedman = <?php echo json_encode($data2, JSON_UNESCAPED_UNICODE); ?>;
        var chargedmantel = <?php echo json_encode($data3, JSON_UNESCAPED_UNICODE); ?>;
        
        if (opener && opener.document) {
            var nameElement = opener.document.getElementById(chargedman);
            var telElement = opener.document.getElementById(chargedmantel);
            
            if (nameElement) nameElement.value = name;
            if (telElement) telElement.value = tel;
        }
        
        self.close();
    };
    
})();
</script>

</body>
</html>
