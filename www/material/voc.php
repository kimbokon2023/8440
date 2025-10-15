<?php
/**
 * VOC(협의사항) 등록/수정 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';
$parent = $num;

// 작업 관련 변수 초기화
$workplacename = '';
$content = '';
$childnum = 0;
$mode = 'insert';

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 작업 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.work WHERE num = ?";
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

// VOC 정보 조회
try {
    $sql = "SELECT * FROM mirae8440.voc WHERE parent = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $content = $row["content"] ?? '';
        $childnum = $row["num"] ?? 0;
        
        if ($childnum != 0) {
            $mode = "modify";
        } else {
            $mode = "insert";
        }
    }
    
} catch (PDOException $ex) {
    error_log("VOC 조회 오류 (parent: {$num}): " . $ex->getMessage());
    echo "오류: 협의사항을 불러오는 중 문제가 발생했습니다.";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쟘공사 현장 코멘트 등록하기</title>
    
    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
    <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" 
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="../css/partner.css" type="text/css" />
    
    <style>
        :root {
            --primary-blue: #0288d1;
            --secondary-blue: #0277bd;
            --light-blue: #b3e5fc;
            --glass-bg: rgba(224, 242, 254, 0.3);
            --glass-border: rgba(176, 230, 247, 0.5);
            --shadow: 0 2px 12px rgba(2, 136, 209, 0.08);
            --text-primary: #01579b;
            --text-secondary: #0277bd;
        }
        
        body {
            background: white;
            overflow-x: hidden;
        }
        
        .container-fluid {
            max-width: 100%;
            overflow-x: hidden;
            padding: 0.9rem;
        }
        
        .glass-container {
            background: linear-gradient(135deg, #e0f2fe 0%, #f1f8fe 100%);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .section-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1rem;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }
        
        .section-title {
            color: white;
            font-weight: 600;
            margin: 0;
        }
        
        .section-subtitle {
            color: var(--text-primary);
            font-weight: 500;
            margin: 1rem 0;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(2, 136, 209, 0.2);
        }
        
        .form-control-custom {
            border: 2px solid var(--glass-border);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(2, 136, 209, 0.25);
            background: white;
        }
        
        .user-info {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .glass-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="glass-container">
            <div class="section-header">
                <h3 class="section-title text-center">현장 협의사항 등록/수정</h3>
            </div>
            
            <div class="user-info">
                <?php if (!isset($_SESSION["userid"])): ?>
                    <div class="text-center">
                        <a href="../login/login_form.php" class="btn-custom">로그인</a>
                        <a href="../member/insertForm.php" class="btn-custom">회원가입</a>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <h5 class="section-subtitle">
                            <?= htmlspecialchars($_SESSION["name"] ?? '', ENT_QUOTES, 'UTF-8') ?> 님 환영합니다
                        </h5>
                        <a href="../login/logout.php" class="btn btn-outline-secondary btn-sm">로그아웃</a>
                        <a href="../member/updateForm.php?id=<?= htmlspecialchars($_SESSION["userid"] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                           class="btn btn-outline-secondary btn-sm">정보수정</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mb-3">
                <input type="button" class="btn btn-outline-secondary" value="이전화면으로 돌아가기" onclick="history.back(-1);">
            </div>
            
            <form id="board_form" name="board_form" method="post" action="voc_insert.php">
                <input type="hidden" id="childnum" name="childnum" value="<?= htmlspecialchars($childnum, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" id="parent" name="parent" value="<?= htmlspecialchars($parent, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" id="workplacename" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>">
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="section-subtitle">
                            현장명: <?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>
                        </h4>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="content" class="form-label section-subtitle">협의사항 등록/수정:</label>
                        <textarea
                            id="content"
                            name="content"
                            class="form-control form-control-custom"
                            rows="8"
                            placeholder="협의 사항 내용을 입력해주세요"
                        ><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 text-center">
                        <input type="button" class="btn-custom btn-lg" value="수정/저장하기" onclick="pro_submit()">
                    </div>
                </div>
            </form>
        </div>
    </div>
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
     * 날짜 입력 마스크
     */
    window.date_mask = function(formd, textid) {
        var form = document[formd];
        if (!form) return;
        
        var text = form[textid];
        if (!text) return;
        
        var textlength = text.value.length;
        
        if (textlength == 4) {
            text.value = text.value + "-";
        } else if (textlength == 7) {
            text.value = text.value + "-";
        } else if (textlength > 9) {
            checkdate(text);
        }
    };
    
    /**
     * 날짜 유효성 검사
     */
    function checkdate(input) {
        var validformat = /^\d{4}-\d{2}-\d{2}$/;
        var returnval = false;
        
        if (!validformat.test(input.value)) {
            alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            input.select();
            return false;
        }
        
        var parts = input.value.split("-");
        var yearfield = parts[0];
        var monthfield = parts[1];
        var dayfield = parts[2];
        var dayobj = new Date(yearfield, monthfield - 1, dayfield);
        
        if ((dayobj.getMonth() + 1 != monthfield) || 
            (dayobj.getDate() != dayfield) || 
            (dayobj.getFullYear() != yearfield)) {
            alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            input.select();
            return false;
        }
        
        return true;
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
        var elements = {
            ashistory: document.getElementById("ashistory"),
            asday: document.getElementById("asday"),
            aswriter: document.getElementById("aswriter"),
            asorderman: document.getElementById("asorderman"),
            asordermantel: document.getElementById("asordermantel"),
            asfee: document.getElementById("asfee"),
            asfee_estimate: document.getElementById("asfee_estimate"),
            aslist: document.getElementById("aslist"),
            as_refer: document.getElementById("as_refer"),
            asproday: document.getElementById("asproday"),
            setdate: document.getElementById("setdate"),
            asman: document.getElementById("asman"),
            asendday: document.getElementById("asendday"),
            asresult: document.getElementById("asresult")
        };
        
        if (!elements.ashistory) return;
        
        var park = document.getElementsByName("asfee");
        var feeType = (park[1] && park[1].checked) ? " 유상 " : " 무상 ";
        
        var history = elements.ashistory.value;
        history += (elements.asday ? elements.asday.value + " " : "");
        history += (elements.aswriter ? elements.aswriter.value + " " : "");
        history += (elements.asorderman ? elements.asorderman.value + " " : "");
        history += (elements.asordermantel ? elements.asordermantel.value + " " : "");
        history += feeType + (elements.asfee ? elements.asfee.value + " " : "");
        history += (elements.asfee_estimate ? elements.asfee_estimate.value + " " : "");
        history += (elements.aslist ? elements.aslist.value + " " : "");
        history += (elements.as_refer ? elements.as_refer.value + " " : "");
        history += (elements.asproday ? elements.asproday.value + " " : "");
        history += (elements.setdate ? elements.setdate.value + " " : "");
        history += (elements.asman ? elements.asman.value + " " : "");
        history += (elements.asendday ? elements.asendday.value + " " : "");
        history += (elements.asresult ? elements.asresult.value + "        " : "");
        
        elements.ashistory.value = history;
    };
    
    /**
     * 폼 제출
     */
    window.pro_submit = function() {
        if (typeof $ !== 'undefined') {
            $('#board_form').submit();
        } else {
            document.getElementById('board_form').submit();
        }
    };
    
})();
</script>
