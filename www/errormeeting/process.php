<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$admin_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// rowDB.php에서 사용되는 변수 초기화
$reporter = '';
$part = '';
$place = '';
$errortype = '';
$occur = '';
$occurconfirm = '';
$saveurl = '';
$involved = '';
$filename = '';
$serverfilename = '';
$content = '';
$method = '';
$steelrequirement = '';
$approve = '';
$imgurl = '';

// 부적합 유형 배열 불러오기
$sql = "SELECT * FROM {$DB}.errortype";
$errortype_arr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($errortype_arr, $row["errortype"]);
    }
} catch (PDOException $ex) {
    error_log("부적합 유형 조회 오류: " . $ex->getMessage());
}

$errortype_arr = array_unique($errortype_arr);
sort($errortype_arr);

// 기존 데이터 조회
if ($num != '') {
    try {
        $sql = "SELECT * FROM {$DB}.emeeting WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include 'rowDB.php';
            $imgurl = './img/' . $serverfilename;
        }
    } catch (PDOException $ex) {
        error_log("회의록 조회 오류: " . $ex->getMessage());
    }
}

// 연차 관리 데이터 로드
$basic_name_arr = array();
$basic_part_arr = array();
include "../annualleave/load_DB.php";

// 신규 데이터인 경우
if ($num == '') {
    $reporter = $user_name;
    $occur = date("Y-m-d", time());
    $occurconfirm = date("Y-m-d", time());
    $approve = '결재상신';
    $name = $_SESSION["name"] ?? '';
    $part = '';
    
    // DB에서 part 찾기
    for ($i = 0; $i < count($basic_name_arr); $i++) {
        if (trim($basic_name_arr[$i]) == trim($name)) {
            $part = $basic_part_arr[$i];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="품질분임조 회의록 승인">
    <meta name="author" content="">
    
    <title>품질분임조 회의록 승인</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <?php
    // 로컬/서버 환경에 따른 동적 URL
    $baseUrl = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
        ? 'http://localhost/mirae8440'
        : 'http://8440.co.kr';
    ?>
    <script src="<?= $baseUrl ?>/common.js"></script>
    
    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>

<body>

    <!-- 알림 모달 -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-center">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h4 class="modal-title">알림</h4>
                </div>
                <div class="modal-body">
                    <div id="alertmsg" class="fs-1 mb-5 justify-content-center">
                        결재가 진행중입니다.<br><br>
                        수정사항이 있으면 결재권자에게 말씀해 주세요.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container h-30">
        <div class="row d-flex justify-content-center align-items-center h-30">
            <div class="col-12 text-center">
                <div class="card align-middle" style="width:58rem; border-radius:20px;">
                    <div class="card" style="padding:6px;margin:7px;">
                        <h3 class="card-title text-center" style="color:#113366;">품질불량(부적합) 원인분석 및 개선 대책 보고서</h3>
                    </div>
                    <div class="card-body text-center">
                        <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="mode" name="mode">
                            <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num) ?>">
                            <input type="hidden" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name) ?>">
                            <input type="hidden" id="filedelete" name="filedelete">
                            <input type="hidden" id="filename" name="filename" value="<?= htmlspecialchars($filename) ?>">
                            <input type="hidden" id="serverfilename" name="serverfilename" value="<?= htmlspecialchars($serverfilename) ?>">
                            <input type="hidden" id="admin_name" name="admin_name" value="<?= htmlspecialchars($admin_name) ?>">
                            
                            <span class="form-control">
                                성명 &nbsp;&nbsp;
                                <input type="text" id="reporter" name="reporter" size="8" class="text-center" value="<?= htmlspecialchars($reporter) ?>">
                                &nbsp;&nbsp; 부서 &nbsp;&nbsp;
                                <input type="text" id="part" name="part" size="8" class="text-center" value="<?= htmlspecialchars($part) ?>">
                                &nbsp;&nbsp; 현장명 &nbsp;&nbsp;
                                <input type="text" name="place" id="place" size="50" class="text-left" value="<?= htmlspecialchars($place) ?>" autofocus>
                            </span>
                            
                            <span class="form-control">
                                &nbsp;&nbsp; 부적합 유형 &nbsp;&nbsp;
                                <select name="errortype" id="errortype">
                                    <?php
                                    for ($i = 0; $i < count($errortype_arr); $i++) {
                                        $selected = ($errortype == $errortype_arr[$i]) ? 'selected' : '';
                                        echo '<option ' . $selected . ' value="' . htmlspecialchars($errortype_arr[$i]) . '">';
                                        echo htmlspecialchars($errortype_arr[$i]) . '</option>';
                                    }
                                    ?>
                                </select>
                                
                                <button type="button" id="registerrortypeBtn" class="btn btn-outline-primary btn-sm">부적합유형 등록</button> &nbsp;
                                &nbsp;&nbsp; 발생일 &nbsp;&nbsp;
                                <input type="date" id="occur" name="occur" value="<?= htmlspecialchars($occur) ?>">
                                
                                &nbsp;&nbsp; 불량확인일 &nbsp;&nbsp;
                                <input type="date" id="occurconfirm" name="occurconfirm" value="<?= htmlspecialchars($occurconfirm) ?>">
                            </span>
                            
                            <span class="form-control">
                                <span style="color:gray">도면 저장위치</span>
                                <input type="text" id="saveurl" size="30" name="saveurl" class="text-left" 
                                       value="<?= htmlspecialchars($saveurl) ?>" placeholder="nas2dual 도면 저장위치">
                                <span style="color:gray">관련직원</span>
                                <input type="text" id="involved" size="30" name="involved" class="text-left" 
                                       value="<?= htmlspecialchars($involved) ?>" placeholder="관련 직원">
                            </span>
                            
                            <span class="form-control">
                                <span style="color:green">첨부파일(이미지)</span>
                                <?php if ($filename != null) echo htmlspecialchars($filename); ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="mainbefore" name="mainBefore" type="file">
                            </span>
                            
                            <span class="form-control">
                                <h4><span style="color:blue">불량 발생 원인 및 분석</span></h4>
                            </span>
                            <span class="form-control">
                                <textarea id="content" class="form-control" rows="5" name="content"><?= htmlspecialchars($content) ?></textarea>
                            </span>
                            
                            <span class="form-control">
                                <h4><span style="color:red">처리방안 및 개선사항</span></h4>
                            </span>
                            <span class="form-control">
                                <textarea id="method" class="form-control" rows="5" name="method"><?= htmlspecialchars($method) ?></textarea>
                            </span>
                            
                            <span class="form-control">
                                <h4><span style="color:green">원자재 및 자재 소요량</span></h4>
                            </span>
                            <span class="form-control">
                                <textarea id="steelrequirement" class="form-control" rows="1" name="steelrequirement"><?= htmlspecialchars($steelrequirement) ?></textarea>
                            </span>
                            
                            <?php
                            if ($filename != null && $imgurl != '') {
                                echo '<span class="form-control">';
                                echo '<br>';
                                echo '<div class="imagediv">';
                                echo '<img class="before_work" src="' . htmlspecialchars($imgurl) . '" style="width:100%;height:100%" alt="첨부이미지">';
                                echo '</div></span><br>';
                            }
                            ?>
                            
                            <br>
                            <h5 class="form-signin-heading">결재 상태</h5>
                            <input type="text" id="approve" name="approve" class="form-control text-center" 
                                   readonly value="<?= htmlspecialchars($approve) ?>">
                            <br>
                            
                            <button id="saveBtn" class="btn btn-lg btn-secondary btn-block" type="button">
                                <?php echo ((int)$num > 0) ? '승인' : '결재상신(등록)'; ?>
                            </button>
                            
                            <?php if ((int)$num > 0) { ?>
                                <button id="delBtn" class="btn btn-lg btn-danger btn-block" type="button">삭제</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
// ES5 호환 JavaScript

function displayPicture() {
    $('#displayPicture').show();
    var params = $("#num").val();
    
    $.ajax({
        url: 'loadpic.php?num=' + params,
        type: 'post',
        data: $("mainFrm").serialize(),
        dataType: 'json'
    }).done(function(data) {
        var recnum = data["recnum"];
        $("#displayPicture").html('');
        
        for (var i = 0; i < recnum; i++) {
            $("#displayPicture").append("<img id='pic" + i + "' src='img/" + data["img_arr"][i] + "'>");
            $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + 
                "' onclick='delPicFn(\"" + i + "\",\"" + data["img_arr"][i] + "\")'>삭제</button>&nbsp;");
        }
        
        $("#pInput").val('');
    });
}

function displayPictureLoad() {
    $('#displayPicture').show();
    var picNum = <?php echo isset($picNum) ? (int)$picNum : 0; ?>;
    var picData = <?php echo isset($picData) ? json_encode($picData, JSON_UNESCAPED_UNICODE) : '[]'; ?>;
	   
    for (var i = 0; i < picNum; i++) {
        $("#displayPicture").append("<img id='pic" + i + "' src='img/" + picData[i] + "'>");
        $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + 
            "' onclick='delPicFn(\"" + i + "\",\"" + picData[i] + "\")'>삭제</button>&nbsp;");
    }
    
    $("#pInput").val('');
}

function delPic(delChoice) {
    if (delChoice == 'before') {
        $("#filedelete").val('before');
    }
    if (delChoice == 'after') {
        $("#filedelete").val('after');
    }
    
    document.getElementById('board_form').submit();
}

var delPicFn = function(divID, delChoice) {
    $.ajax({
        url: 'delpic.php?picname=' + delChoice,
        type: 'post',
        data: $("mainFrm").serialize(),
        dataType: 'json'
    }).done(function(data) {
        var picname = data["picname"];
        $("#pic" + divID).remove();
        $("#delPic" + divID).remove();
        $("#pInput").val('');
    });
};

$(document).ready(function() {
    // 발생일 변경시 불량확인일도 변경
    $("#occur").change(function() {
        $('#occurconfirm').val($("#occur").val());
    });
    
    // 부적합 유형 등록
    $("#registerrortypeBtn").click(function() {
        var href = '../registerrortype.php';
        popupCenter(href, '부적합 유형 등록', 600, 600);
    });
    
    // 사진 등록
    $("#regpicBtn").click(function() {
        var num = $("#num").val();
        window.open('reg_pic.php?num=' + num, "사진등록", "width=1200,height=700,top=0,left=0,scrollbars=no");
    });
    
    // 파일 변경
    $("#mainbefore").change(function(e) {
        var isfile = $("#filename").val();
        var changefile = $("#mainbefore").val();
        
        if (changefile != '') {
            $("#filename").val('');
        }
    });
    
    var approve = $('#approve').val();
    
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    $("#closeBtn").click(function() {
        // 저장하고 창닫기
    });
    
    // 저장 버튼
    $("#saveBtn").click(function() {
        var num = $("#num").val();
        var part = $("#part").val();
        var approve = $("#approve").val();
        var user_name = $("#user_name").val();
        var reporter = $("#reporter").val();
        var admin_name = $("#admin_name").val();
        var resultOK = 0;
        
        // 관리자 권한 체크
        var adminApprovers = ['소현철', '김보곤'];
        var firstApprovers = ['이경묵', '김선영', '김보곤'];
        
        if (adminApprovers.indexOf(admin_name) !== -1 && approve == '1차결재') {
            $("#approve").val('처리완료');
            resultOK = 1;
        }
        
        if (firstApprovers.indexOf(admin_name) !== -1 && approve == '결재상신') {
            $("#approve").val('1차결재');
            resultOK = 1;
        }
        
        if (resultOK == 1) {
            $("#mode").val('modify');
            
            var form = $('#board_form')[0];
            var data = new FormData(form);
            
            var tmp = '파일을 저장중입니다. 잠시만 기다려주세요.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
            
            $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "insert.php",
                type: "post",
                data: data,
                success: function(data) {
                    if (window.opener) {
                        window.opener.location.reload();
                    }
                    window.close();
                },
                error: function(jqxhr, status, error) {
                    console.error('저장 오류:', status, error);
                    alert('저장 중 오류가 발생했습니다.');
                }
            });
        } else {
            var tmp = '보고자만 결재가 가능합니다.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
        }
    });
    
    // 삭제 버튼
    $("#delBtn").click(function() {
        var num = $("#num").val();
        var reporter = $("#reporter").val();
        var approve = $("#approve").val();
        var user_name = $("#user_name").val();
        
        if ((reporter == user_name && approve == '결재상신') || user_name == '김보곤') {
            if (confirm("데이터 삭제.\n\n정말 지우시겠습니까?")) {
                $("#mode").val('delete');
                
                $.ajax({
                    url: "insert.php",
                    type: "post",
                    data: $("#board_form").serialize(),
                    dataType: "text",
                    success: function(data) {
                        if (window.opener) {
                            window.opener.location.reload();
                        }
                        window.close();
                    },
                    error: function(jqxhr, status, error) {
                        console.error('삭제 오류:', status, error);
                        alert('삭제 중 오류가 발생했습니다.');
                    }
                });
            }
        } else {
            var tmp = '보고자만 결재상신 상태에서 삭제할 수 있습니다.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
        }
    });
    
}); // end of ready

</script>