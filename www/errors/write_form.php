<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 8;
$user_name = $_SESSION["name"] ?? '';
$admin_name = $_SESSION["name"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';
$admin = $_SESSION["admin"] ?? 0;

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 8) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 페이지 설정
$title_message = '부적합 보고 및 대책';
$option = $_REQUEST["option"] ?? '';
$num = $_REQUEST["num"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 부서 배열
$mypart_arr = array();
array_push($mypart_arr, "제조파트", "지원파트");

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// _row.php에서 사용되는 변수 초기화
$reporter = '';
$part = '';
$place = '';
$occur = '';
$occurconfirm = '';
$errortype = '';
$saveurl = '';
$content = '';
$method = '';
$involved = '';
$filename = '';
$serverfilename = '';
$approve = '';
$steelrequirement = '';
$materialRaw = '';
$materialFee = '';
$deliveryFee = '';
$workFee = '';
$etcFee = '';
$totalFee = '';
$imgurl = '';
$count = 0;

// 부적합 유형 배열 불러오기
$sql = "SELECT * FROM {$DB}.errortype";
$errortype_arr = array();

try {
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($errortype_arr, $row["errortype"]);
    }
    
    $errortype_arr = array_unique($errortype_arr);
    sort($errortype_arr);
} catch (PDOException $ex) {
    error_log("부적합 유형 조회 오류: " . $ex->getMessage());
}

// 기존 데이터 조회 (num이 있는 경우)
if ($num != '') {
    try {
        $sql = "SELECT * FROM {$DB}.error WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include '_row.php';
            $imgurl = './img/' . $serverfilename;
        }
    } catch (PDOException $ex) {
        error_log("데이터 조회 오류: " . $ex->getMessage());
    }
}

// 지원부서 이름으로 불러오기 (배열로 기본정보 불러옴)
$basic_name_arr = array();
$basic_mypart_arr = array();
include "../annualleave/load_DB.php";

// 신규 데이터인 경우
if ($num == '') {
    $reporter = $user_name;
    $occur = date("Y-m-d", time());
    $occurconfirm = date("Y-m-d", time());
    $approve = '결재상신';
    $name = $_SESSION["name"] ?? '';
    $part = '';
    
    // DB에서 part 찾아서 넣어주기
    for ($i = 0; $i < count($basic_name_arr); $i++) {
        if (trim($basic_name_arr[$i]) == trim($name)) {
            $part = $basic_mypart_arr[$i];
            break;
        }
    }
}

?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title><?= htmlspecialchars($title_message) ?></title>

</head>

<body>

<?php include getDocumentRoot() . '/common/modal.php'; ?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode) ?>">
    <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num) ?>">
    <input type="hidden" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name) ?>">
    <input type="hidden" id="filedelete" name="filedelete">
    <input type="hidden" id="filename" name="filename" value="<?= htmlspecialchars($filename) ?>">
    <input type="hidden" id="serverfilename" name="serverfilename" value="<?= htmlspecialchars($serverfilename) ?>">
    <input type="hidden" id="admin_name" name="admin_name" value="<?= htmlspecialchars($admin_name) ?>">
    
    <div class="container h-30">
        <div class="row d-flex justify-content-center align-items-center h-30">
            <div class="col-12 text-center">
                <div class="card align-middle" style="width:58rem; border-radius:20px;">                    
                        <h4 class="card-title text-danger text-center">품질불량(부적합) 원인분석 및 개선 대책 보고서</h4>
                    <div class="card-body">
                        <div class="row gy-3 gx-3">
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="reporter" class="form-label text-muted small mb-1">성명</label>
                                <input type="text" id="reporter" name="reporter" class="form-control text-center"
                                       value="<?= htmlspecialchars($reporter) ?>">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="part" class="form-label text-muted small mb-1">부서</label>
                                <select name="part" id="part" class="form-select form-select-sm">
                                    <?php
                                    for ($i = 0; $i < count($mypart_arr); $i++) {
                                        $selected = ($part == $mypart_arr[$i]) ? 'selected' : '';
                                        echo "<option {$selected} value='" . htmlspecialchars($mypart_arr[$i]) . "'>" .
                                             htmlspecialchars($mypart_arr[$i]) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-lg-8">
                                <label for="place" class="form-label text-muted small mb-1">현장명</label>
                                <div class="input-group">
                                    <input type="text" name="place" id="place" class="form-control"
                                           value="<?= htmlspecialchars($place) ?>" autofocus>
                                    <button type="button" id="searchPlaceBtn" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row gy-3 gx-3 mt-1">
                            <div class="col-12 col-md-4 col-lg-3">
                                <label for="errortype" class="form-label text-muted small mb-1">부적합 유형</label>
                                <div class="input-group input-group-sm">
                                    <select name="errortype" id="errortype" class="form-select">
                                        <?php
                                        for ($i = 0; $i < count($errortype_arr); $i++) {
                                            $selected = ($errortype == $errortype_arr[$i]) ? 'selected' : '';
                                            echo "<option {$selected} value='" . htmlspecialchars($errortype_arr[$i]) . "'>" .
                                                 htmlspecialchars($errortype_arr[$i]) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="button" id="registerrortypeBtn" class="btn btn-outline-primary">
                                        부적합유형 등록
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <label for="occur" class="form-label text-muted small mb-1">발생일</label>
                                <input type="date" id="occur" name="occur" value="<?= htmlspecialchars($occur) ?>"
                                       class="form-control">
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <label for="occurconfirm" class="form-label text-muted small mb-1">불량확인일</label>
                                <input type="date" id="occurconfirm" name="occurconfirm" value="<?= htmlspecialchars($occurconfirm) ?>"
                                       class="form-control">
                            </div>
                            <div class="col-12 col-lg-5">
                                <label for="saveurl" class="form-label text-muted small mb-1">도면 저장위치</label>
                                <input type="text" id="saveurl" class="form-control" name="saveurl"
                                       value="<?= htmlspecialchars($saveurl) ?>" placeholder="nas2dual 도면 저장위치">
                            </div>
                        </div>

                        <div class="row gy-3 gx-3 mt-1">
                            <div class="col-12 col-md-6">
                                <label for="involved" class="form-label text-muted small mb-1">관련 직원</label>
                                <input type="text" id="involved" name="involved" class="form-control"
                                       value="<?= htmlspecialchars($involved) ?>" placeholder="관련 직원">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-muted small mb-1">첨부파일(이미지)</label>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <?php if ($filename != null) : ?>
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($filename) ?></span>
                                    <?php endif; ?>
                                    <input id="mainbefore" name="mainBefore" type="file" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="text-primary fw-semibold text-center">불량 발생 원인 및 분석</h5>
                            <textarea id="content" class="form-control mt-2" rows="2" name="content"><?= htmlspecialchars($content) ?></textarea>
                        </div>

                        <div class="mt-4">
                            <h5 class="text-danger fw-semibold text-center">처리방안 및 개선사항</h5>
                            <textarea id="method" class="form-control mt-2" rows="2" name="method"><?= htmlspecialchars($method) ?></textarea>
                        </div>

                        <div class="mt-4">
                            <h5 class="text-success fw-semibold">원자재 및 자재 소요량 내역</h5>
                            <h6 class="text-danger" style="animation: blinkEffect 1s linear infinite;">
                                (원자재 불량시 반드시 아래의 자재 비용을 추가해 주세요)
                            </h6>
                            <style>
                            @keyframes blinkEffect {
                                0% { opacity: 1; }
                                50% { opacity: 0.2; }
                                100% { opacity: 1; }
                            }
                            </style>
                            <textarea id="steelrequirement" class="form-control mt-2" rows="2" name="steelrequirement"><?= htmlspecialchars($steelrequirement) ?></textarea>
                            <input type="text" id="materialRaw" name="materialRaw" class="form-control mt-2"
                                   value="<?= htmlspecialchars($materialRaw) ?>" readonly placeholder="자재내역 가져온 자료">
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered" id="myTable">
                                <thead class="table-secondary">
                                    <tr class="middle-align">
                                        <th class="text-center align-middle">자재 비용
                                            <button type="button" class="btn btn-primary btn-sm ms-1 searchmaterialFee" style="padding:3px;">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </th>
                                        <th class="text-center align-middle">운송 비용</th>
                                        <th class="text-center align-middle">시공 비용</th>
                                        <th class="text-center align-middle">기타 비용</th>
                                        <th class="text-center align-middle">비용 합계</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="materialFee" name="materialFee" class="form-control text-end number-input" value="<?= ($materialFee !== '') ? number_format($materialFee) : '' ?>"></td>
                                        <td><input type="text" id="deliveryFee" name="deliveryFee" class="form-control text-end number-input" value="<?= ($deliveryFee !== '') ? number_format($deliveryFee) : '' ?>"></td>
                                        <td><input type="text" id="workFee" name="workFee" class="form-control text-end number-input" value="<?= ($workFee !== '') ? number_format($workFee) : '' ?>"></td>
                                        <td><input type="text" id="etcFee" name="etcFee" class="form-control text-end number-input" value="<?= ($etcFee !== '') ? number_format($etcFee) : '' ?>"></td>
                                        <td><input type="text" id="totalFee" name="totalFee" class="form-control text-end" value="<?= ($totalFee !== '') ? number_format($totalFee) : '' ?>" readonly></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
	  <?php 
			if($filename!=null) {	
			  print " <span class='form-control'> ";
			  print '<br>';
			  print "<div class='imagediv' > ";
			  echo "<img class='before_work' src='". $imgurl  . "' style='width:100%;height:100%' >";			  			  
			  print "</div> </span> <br> ";
			  }
		?>				
		<h5 class="form-signin-heading mt-4 mb-1">결재 상태</h5>	
			 <div class="d-flex justify-content-center">				
				<input type="text"   id="approve" name="approve" style="width:150px;" class="form-control text-center bg-secondary text-white" readonly value="<?=$approve?>" >						
			 </div>
			 <div class="d-flex mt-4 mb-2 justify-content-center">		
             <?php if($option !=='approval' ) : ?>			 
				<button id="saveBtn" class="btn btn-dark btn-sm mx-2" type="button">
				<?php if((int)$num>0) print ' <i class="bi bi-pencil-square"></i>  결재상신(수정)';  else print '<i class="bi bi-floppy-fill"></i>  결재상신(등록)'; ?></button>
				<?php else : ?>
				<button id="approvalBtn" class="btn btn-dark btn-sm mx-2" type="button">
				<?php  print '<i class="bi bi-floppy-fill"></i>   승인';  ?></button>
				<?php endif; ?>				
				<?php if((intval($num)>0 or intval($level) === 1) and $option !=='approval' ) {  ?>				
				<button id="delBtn" class="btn btn-danger btn-sm" type="button"> <i class="bi bi-trash"></i>  삭제  </button>
				<?php } ?>	
				<button type="button" id="closeBtn"  class="btn btn-dark btn-sm ms-3" > &times; 닫기 </button>				 
			 </div>
		  
			        </div>
		</div>
		</div>			
	  </div>
	</div>	
	  </form>			  
<script>
// ES5 호환 JavaScript

// 전역 함수 정의
function formatNumberWithCommas(value) {
    value = value.replace(/,/g, "");
    if (!value) return "";
    return parseInt(value, 10).toLocaleString();
}

function getNumericValue(value) {
    return value.replace(/,/g, "");
}

function updateTotalFee() {
    var total = 0;
    var inputs = document.querySelectorAll(".number-input");
    
    for (var i = 0; i < inputs.length; i++) {
        total += parseInt(getNumericValue(inputs[i].value)) || 0;
    }
    
    var totalFeeInput = document.getElementById("totalFee");
    if (totalFeeInput) {
        totalFeeInput.value = formatNumberWithCommas(total.toString());
    }
}

// 초기화 함수
function initNumberInputFormatter() {
    var inputs = document.querySelectorAll(".number-input");
    
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("input", function() {
            this.value = formatNumberWithCommas(this.value);
            updateTotalFee();
        });
    }
    
    updateTotalFee();
}

// 전역으로 등록
window.formatNumberWithCommas = formatNumberWithCommas;
window.getNumericValue = getNumericValue;
window.updateTotalFee = updateTotalFee;
window.initNumberInputFormatter = initNumberInputFormatter;

// 초기화 실행
document.addEventListener("DOMContentLoaded", function() {
    initNumberInputFormatter();
});

function displayPicture() {
    $('#displayPicture').show();
    var params = $("#num").val();
    console.log($("#num").val());
    
    $.ajax({
        url: 'loadpic.php?num=' + params,
        type: 'post',
        data: $("mainFrm").serialize(),
        dataType: 'json'
    }).done(function(data) {
        var recnum = data["recnum"];
        console.log(data);
        $("#displayPicture").html('');
        
        for (var i = 0; i < recnum; i++) {
            $("#displayPicture").append("<img id='pic" + i + "' src='img/" + data["img_arr"][i] + "'>");
            $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick='delPicFn(\"" + i + "\",\"" + data["img_arr"][i] + "\")'>삭제</button>&nbsp;");
        }
        
        $("#pInput").val('');
    });
}

function displayPictureLoad() {
    $('#displayPicture').show();
    var picNum = "<?php echo $picNum ?? 0; ?>";
    var picData = <?php echo json_encode($picData ?? array(), JSON_UNESCAPED_UNICODE); ?>;
	   
    for (var i = 0; i < picNum; i++) {
        $("#displayPicture").append("<img id='pic" + i + "' src='img/" + picData[i] + "'>");
        $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick='delPicFn(\"" + i + "\",\"" + picData[i] + "\")'>삭제</button>&nbsp;");
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

$(document).ready(function() {
    // 신청일 변경시 종료일도 변경함
    $("#occur").change(function() {
        $('#occurconfirm').val($("#occur").val());
    });
    
    // 에러타입등록
    $("#registerrortypeBtn").click(function() {
        var href = '../registerrortype.php';
        popupCenter(href, '부적합 유형 등록', 600, 600);
    });
    
    // 현장 검색
    $("#searchPlaceBtn").click(function() {
        var num = $("#num").val();
        var href = 'search.php?num=' + num;
        popupCenter(href, '현장 검색', 600, 600);
    });
    
    // 사진등록
    $("#regpicBtn").click(function() {
        var num = $("#num").val();
        window.open('reg_pic.php?num=' + num, "사진등록", "width=1200, height=700, top=0,left=0,scrollbars=no");
    });
    
    $("#mainbefore").change(function(e) {
        var isfile = $("#filename").val();
        var changefile = $("#mainbefore").val();
        
        if (changefile != '') {
            $("#filename").val('');
        }
    });
    
    // 사진 삭제 함수
    window.delPicFn = function(divID, delChoice) {
        console.log(divID, delChoice);
        
        $.ajax({
            url: 'delpic.php?picname=' + delChoice,
            type: 'post',
            data: $("mainFrm").serialize(),
            dataType: 'json'
        }).done(function(data) {
            var picname = data["picname"];
            console.log(data);
            $("#pic" + divID).remove();
            $("#delPic" + divID).remove();
            $("#pInput").val('');
        });
    };
    
    var approve = $('#approve').val();
    
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    $("#closeBtn").click(function() {
        window.close();
    });	
				
    // DATA 저장버튼
    $("#saveBtn").click(function() {
        var num = $("#num").val();
        var part = $("#part").val();
        var approve = $("#approve").val();
        var user_name = $("#user_name").val();
        var reporter = $("#reporter").val();
	   	   
        // 권한 확인
        var allowed_users = ['김보곤', '소현철', '조경임', '최장중'];
        var is_authorized = (reporter == user_name && approve == '결재상신');
        
        for (var i = 0; i < allowed_users.length; i++) {
            if (user_name == allowed_users[i]) {
                is_authorized = true;
                break;
            }
        }
        
        if (is_authorized) {
            if (Number(num) > 0) {
                $("#mode").val('modify');
            } else {
                $("#mode").val('insert');
                if (user_name == '최장중') {
                    $("#approve").val('1차결재');
                }
            }
            
            console.log($("#mode").val());
            
            // FormData 생성
            var form = $('#board_form')[0];
            var data = new FormData(form);
            
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
                    console.log(data);
                    Toastify({
                        text: "파일 저장완료",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    
                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            opener.location.reload();
                        }
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        } else {
            var tmp = '보고자만 결재상신 상태가 아닌 경우 수정이 가능합니다.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
        }
    }); 
		
    // 승인버튼
    $("#approvalBtn").click(function() {
        var num = $("#num").val();
        var part = $("#part").val();
        var approve = $("#approve").val();
        var user_name = $("#user_name").val();
        var reporter = $("#reporter").val();
        var admin_name = $("#admin_name").val();
        var resultOK = 0;
        
        // 결재 권한 확인
        if ((admin_name == '소현철' || admin_name == '김보곤') && approve == '1차결재') {
            $("#approve").val('처리완료');
            resultOK = 1;
        }
        
        if ((admin_name == '이경묵' || admin_name == '최장중' || admin_name == '김보곤') && approve == '결재상신') {
            $("#approve").val('1차결재');
            resultOK = 1;
        }
        
        console.log('변경후 approve: ' + approve);
        
        if (resultOK == 1) {
            $("#mode").val('modify');
            console.log($("#mode").val());
            
            // FormData 생성
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
                    console.log(data);
                    opener.location.reload();
                    window.close();
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        } else {
            var tmp = '보고자만 결재가 가능합니다.';
            $('#alertmsg').html(tmp);
            $('#myModal').modal('show');
        }
    }); 

    
    // 삭제버튼
    $("#delBtn").click(function() {
        var num = $("#num").val();
        var reporter = $("#reporter").val();
        var approve = $("#approve").val();
        var admin = '<?php echo htmlspecialchars($admin); ?>';
        var user_name = $("#user_name").val();
        
        if ((reporter == user_name && approve == '결재상신') || (admin == '1') || user_name == '김보곤') {
            Swal.fire({
                title: '자료 삭제',
                text: "삭제는 신중! 정말 삭제하시겠습니까?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '삭제',
                cancelButtonText: '취소'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $("#mode").val('delete');
                    
                    $.ajax({
                        url: "insert.php",
                        type: "post",
                        data: $("#board_form").serialize(),
                        dataType: "text",
                        success: function(data) {
                            console.log(data);
                            Toastify({
                                text: "파일 삭제완료",
                                duration: 2000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                                }
                            }).showToast();
                            
                            setTimeout(function() {
                                if (window.opener && !window.opener.closed) {
                                    if (typeof window.opener.restorePageNumber === 'function') {
                                        window.opener.restorePageNumber();
                                    }
                                    window.opener.location.reload();
                                    window.close();
                                }
                            }, 1000);
                        },
                        error: function(jqxhr, status, error) {
                            console.log(jqxhr, status, error);
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: '삭제불가',
                text: "작성자와 관리자만 삭제가능합니다.",
                icon: 'error',
                confirmButtonText: '확인'
            });
        }
    });
    
    // 자재비용 검색
    $(".searchmaterialFee").click(function() {
        var option = 'parent';
        var materialRaw = $("#materialRaw").val();
        popupCenter('/cost/calamount.php?menu=no&option=' + option + '&materialRaw=' + materialRaw, '', 1000, 850);
    });
});
</script>

</body>
</html>
