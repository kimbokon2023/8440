<?php
session_start();

// REQUEST 변수 초기화
$data1 = isset($_REQUEST["data1"]) ? $_REQUEST["data1"] : "";
$data2 = isset($_REQUEST["data2"]) ? $_REQUEST["data2"] : "";
$data3 = isset($_REQUEST["data3"]) ? $_REQUEST["data3"] : "";
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
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
            0% {
                opacity: 1;
            }

            50% {
                opacity: 1;
            }

            50.01% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @-moz-keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 1;
            }

            50.01% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @-ms-keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 1;
            }

            50.01% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @-o-keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 1;
            }

            50.01% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 1;
            }

            50.01% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }
    </style>

    <title>전번 조회</title>
</head>

<body>

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-body">
            <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" class="btn btn-secondary btn-lg" value="닫기" onclick="self.close();">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            
            <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                검색어 : <?= $search ?>
            </div>
            <br>
            
            <input type="hidden" id="data1" name="data1" value="<?=$data1?>">
            <input type="hidden" id="data2" name="data2" value="<?=$data2?>">
            <input type="hidden" id="data3" name="data3" value="<?=$data3?>">
            <input type="hidden" id="search" name="search" value="<?=$search?>">
            
            <div class="row">
                <h3 class="display-6 font-center text-left">전번 조회</h3>
            </div>
            
            <?php
            // 검색 변수 초기화
            $tmp = array();
            $Searchcounter = 0;
            $total = 0;
            $tmpman = "";
            $tmptel = "";
            
            // 전화번호 검색
            try {
                $sql = "SELECT * FROM mirae8440.$data1 WHERE $data2 LIKE '%$search%' OR $data3 LIKE '%$search%' ORDER BY num";
                $stmh = $pdo->prepare($sql);
                $stmh->execute();
                
                echo '<div class="input-group p-1 mb-1">';
                
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $Searchcounter++;
                    $tmpman = "";
                    $tmptel = "";
                    $tmpman = $row[$data2];  // chargedman
                    $tmptel = $row[$data3];
                    
                    $tmpStr = $tmpman;
                    $tmpTel = $tmptel;
                    
                    // 중복 제거
                    if ((!in_array($tmpStr, $tmp) && !in_array($tmpTel, $tmp)) || $Searchcounter == 1) {
                        array_push($tmp, $tmpStr);
                        array_push($tmp, $tmpTel);
                        $total++;
            ?>
                        <a href="#" onclick="javascript:callFunction('<?= $tmpman ?>','<?= $tmptel ?>'); return false;">
                            <span class="input-group-text">
                                <?= $tmpman ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $tmptel ?>
                            </span>
                        </a>
            <?php
                    }
                }
            } catch (PDOException $Exception) {
                print "오류: " . $Exception->getMessage();
            }
            ?>
            
            <input type="hidden" id="total" name="total" value="<?= $total ?>">
            <input type="hidden" id="tmpman" name="tmpman" value="<?= $tmpman ?>">
            <input type="hidden" id="tmptel" name="tmptel" value="<?= $tmptel ?>">
            
            <?php
            print ' &nbsp ' . $total . '개 검색됨.';
            ?>
            
            </div>
        </div> <!-- card-body -->
    </div> <!-- card -->
</div> <!-- container -->	

</body>

</html>

<script>
// 전역 변수
var imgObj = new Image();

// 이미지 창 표시 함수
function showImgWin(imgName) {
    imgObj.src = imgName;
    setTimeout("createImgWin(imgObj)", 100);
}

// 이미지 창 생성 함수
function createImgWin(imgObj) {
    if (!imgObj.complete) {
        setTimeout("createImgWin(imgObj)", 100);
        return;
    }
    var imageWin = window.open("", "imageWin",
        "width=" + imgObj.width + ",height=" + imgObj.height);
}

// 숫자 포맷팅 함수
function inputNumberFormat(obj) {
    obj.value = comma(uncomma(obj.value));
}

function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

// 텍스트 입력 함수 (10% 증가)
function input_Text() {
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value) * 1.1));
}

// 아래로 복사 함수
function copy_below() {
    // 필요시 구현
}

// 프로그레스바 표시 후 폼 제출
function pro_submit() {
    $('#progressbar').show();
    $('#progressbar1').show();
    $('#progressbar2').show();
    $('#board_form').submit();
}

// 부모 창에 값 전달 함수
function callFunction(name, tel) {
    const chargedman = '<?= $data2 ?>';
    const chargedmantel = '<?= $data3 ?>';
    opener.document.getElementById(chargedman).value = name;
    opener.document.getElementById(chargedmantel).value = tel;
    self.close();
}

// 문서 준비 이벤트
$(document).ready(function() {
    const total = '<?= $total ?>';
    const chargedman = '<?= $tmpman ?>';
    const chargedmantel = '<?= $tmptel ?>';
    
    // 검색 결과가 1개인 경우 자동으로 선택
    if (total == 1) {
        callFunction(chargedman, chargedmantel);
    }
});
</script>
