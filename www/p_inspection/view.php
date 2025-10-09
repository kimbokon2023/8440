<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$user_id = $_SESSION["userid"] ?? '';

// 첫 화면 표시 문구
$title_message = '출하검사서';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

include includePath('load_header.php');
?>

<title><?= htmlspecialchars($title_message, ENT_QUOTES) ?></title> 

<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
    }
</style>

</head>

<body>

<?php include includePath("common/modal.php"); ?>

<?php
// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? '';
$parentID = $_REQUEST["parentID"] ?? '';

$tablename = 'p_inspection';

// 데이터베이스 연결 확인
if (!isset($pdo)) {
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();
}

// work 테이블에서 현장명 가져오기
try {
    $sql = "SELECT * FROM mirae8440.work WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    
    if (!empty($parentID)) {
        $stmh->bindValue(1, $parentID, PDO::PARAM_STR);
    } else {
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
    }
    
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    $subject = $row["workplacename"] ?? '';
    
} catch (PDOException $Exception) {
    if (isLocal()) {
        echo "데이터베이스 오류: " . $Exception->getMessage();
    } else {
        error_log("DB Error in p_inspection/view.php: " . $Exception->getMessage());
        echo "데이터 로드 중 오류가 발생했습니다.";
    }
}

// p_inspection 테이블에서 검사 데이터 가져오기
try {
    $sql = "SELECT * FROM mirae8440." . $tablename . " WHERE parentID = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $parentID, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    
    if ($count < 1) {
        print "<p style='font-size:25px;'>아직 검사전 상태입니다.<br></p>";
        $mode = "insert";
        $num = $row["num"];
        $regist_day = "";
        $check0 = "";
        $check1 = "";
        $check2 = "";
        $check3 = "";
        $check4 = "";
        $check5 = "";
        $check6 = "";
        $check7 = "";
        $check8 = "";
        $check9 = "";
        $writer = "";
    } else {
        $mode = "modify";
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        include '_row.php';
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 초기 프로그램은 $num사용 이후 $id로 수정중임
$id = $num;

$todate = date("Y-m-d");   // 현재일자 변수지정
$nowday = date("Y-m-d");   // 현재일자 변수지정

$img_arr = array();
$questionstep_arr = array();
$check_arr = array();

// 체크리스트 배열 및 이미지 URL 생성 (로컬/서버 환경 자동 대응)
for ($i = 0; $i < 10; $i++) {
    $checktmp = 'check' . (string)($i);
    array_push($check_arr, $$checktmp);
    array_push($img_arr, $base_url . '/p_inspection/img/' . ($i + 1) . '.jpg');
}

if (!is_string_valid($writer))
    $writer = $user_name;

if (!NullCheckDate($regist_day))
    $regist_day = $todate;

?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">

<div class="container">
    <div class="card mt-2 mb-4">
        <div class="card-body">

            <div class="d-flex mt-3 mb-3 justify-content-center">
                <h4><?= $title_message ?></h4>
            </div>

            <!-- 전달함수 설정 input hidden -->
            <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <input type="hidden" id="num" name="num" value="<?= $num ?>">
            <input type="hidden" id="page" name="page" value="<?= $page ?>">
            <input type="hidden" id="parentID" name="parentID" value="<?= $parentID ?>">
            <input type="hidden" id="fileorimage" name="fileorimage" value="<?= $fileorimage ?>">
            <input type="hidden" id="item" name="item" value="<?= $item ?>">
            <input type="hidden" id="upfilename" name="upfilename" value="<?= $upfilename ?>">
            <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
            <input type="hidden" id="savetitle" name="savetitle" value="<?= $savetitle ?>">
            <input type="hidden" id="pInput" name="pInput" value="<?= $pInput ?>">
            <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
            <input type="hidden" id="timekey" name="timekey" value="<?= $timekey ?>">  <!-- 신규데이터 작성시 parentID key값으로 사용 -->

            <div class="row d-flex mt-3 mb-1 justify-content-center">
                <table class="table table-bordered" style="width:80%;">
                    <tbody>
                        <tr>
                            <td class="text-center align-middle">검사자</td>
                            <td class="text-center align-middle">
                                <input type="text" id="writer" class="form-control" name="writer" value="<?= $writer ?>">
                            </td>
                            <td class="text-center align-middle">검사일</td>
                            <td class="text-center align-middle">
                                <input type="date" id="regist_day" class="form-control" name="regist_day" value="<?= $regist_day ?>">
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex align-items-center">
                                    <span id="allcheck">10개 체크리스트 점검 여부 &nbsp;</span>
                                    <input id="done_check" class="form-control" name="done_check" type="text" style="width:35px;" value="<?= $done_check ?>">&nbsp;
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <span id="check_complete" style="font-size:12px;color:blue; display:none;">점검완료</span>
                            </td>
                            <td rowspan="2" class="text-center align-middle">
                                <button type="button" id="closeBtn" class="btn btn-dark btn-sm">&times; 닫기</button>&nbsp;
                                <button type="button" id="saveBtn" class="btn btn-dark btn-sm"><i class="bi bi-floppy-fill"></i> 저장</button>&nbsp;
                                <button type="button" class="btn btn-danger btn-sm" onclick="javascript:del('delete.php?num=<?= $num ?>&page=<?= $page ?>&tablename=<?= $tablename ?>')"><i class="bi bi-trash"></i> 삭제</button>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle">현장명</td>
                            <td colspan="5" class="text-center align-middle">
                                <input id="subject" class="form-control" name="subject" type="text" onkeypress="enterCheck(event);" size="50" value="<?= $subject ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row d-flex mt-3 mb-1 justify-content-center">
                <?php for ($i = 0; $i < 10; $i++): ?>
                    <?php $checktmp = 'check' . (string)($i); ?>
                    <div class="card mt-1 mb-1 justify-content-center">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <table class="table table-bordered" style="width:70%;">
                                    <tbody>
                                        <tr>
                                            <td class="text-center align-middle" style="width:40%;">
                                                <img id="ckname<?= $i ?>" src="<?= $img_arr[$i] ?>" style="width:70%;">
                                            </td>
                                            <td class="text-center align-middle" style="width:30%;">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center align-middle">
                                                                <input type="date" id="check<?= $i ?>" name="check<?= $i ?>" class="form-control">
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <span id="writer_text<?= $i ?>" class="form-control" style="display:none;">점검자 : <?= $writer ?></span>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button" id="ckbtn<?= $i ?>" class="btn btn-dark btn-sm">미점검</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- footer -->
            <div class="card-footer border-0 px-5 py-4" style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                <h2 class="d-flex align-items-center justify-content-center text-white mb-0">
                    안전을 최우선으로 생각하는 미래기업
                </h2>
                <h3 class="d-flex align-items-center justify-content-center text-center text-white mb-0">
                    고객만족 품질경영
                </h3>
            </div>
        </div>
    </div>
</div>
</form>
</body>
</html>

<script>
$(document).ready(function() {

    var check_arr = <?php echo json_encode($check_arr); ?>;

    // 기존 체크리스트 값 복원
    for (var i = 0; i < 10; i++) {
        if (check_arr[i] !== '' && check_arr[i] !== '0000-00-00') {
            $('#check' + i).val(check_arr[i]);
            $('#writer_text' + i).show();
            $('#ckbtn' + i).html('<ion-icon name="checkbox-outline"></ion-icon> 완료');
        }
    }

    // check0부터 check9까지의 버튼에 클릭 이벤트를 추가합니다.
    for (let i = 0; i < 10; i++) {
        $('#ckbtn' + i).click(function() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
            const day = ('0' + currentDate.getDate()).slice(-2);
            const formattedDate = `${year}-${month}-${day}`;

            const $checkInput = $('#check' + i);
            const $button = $(this);
            const isChecked = $checkInput.val() !== '';

            if (isChecked) {
                // 이미 점검된 상태일 경우, 날짜를 지우고 미점검 상태로 되돌립니다.
                $checkInput.val('');
                $button.html('점검실시');
                $('#writer_text' + i).hide();
            } else {
                // 미점검 상태일 경우, 현재 날짜를 설정하고 점검 상태로 바꿉니다.
                $checkInput.val(formattedDate);
                $button.html('<ion-icon name="checkbox-outline"></ion-icon> 완료');
                $('#writer_text' + i).show();
            }
        });
    }

    // 화면 로딩 후 체크된 날짜의 개수를 업데이트합니다.
    updateDoneCheck();

    // 날짜가 선택되면 updateDoneCheck 함수를 호출합니다.
    $('input[type="date"]').on('change', function() {
        updateDoneCheck();
    });

    // 점검/미점검 버튼을 클릭하면 updateDoneCheck 함수를 호출합니다.
    $('.btn').on('click', function() {
        updateDoneCheck();
    });

    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });

    // 하단복사 버튼
    $("#closeBtn1").click(function() {
        $("#closeBtn").click();
    });

    $("#closeBtn").click(function() {    // 저장하고 창닫기
        self.close();
    });

    // 목록
    $("#listBtn").click(function() {
        var page = $("#page").val();
        location.href = 'list.php?page=' + page;
    });

    // 자료의 삽입/수정하는 모듈
    $("#saveBtn").click(function() {    // 저장하고 창닫기
        console.log($("#mode").val());

        // 폼데이터 전송시 사용함 Get form
        var form = $('#board_form')[0];
        // Create an FormData object
        var data = new FormData(form);

        $.ajax({
            enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "../p_inspection/insert.php",
            type: "post",
            data: data,
            success: function(data) {
                console.log(data);
                $('#num').val(data["num"]);
                setTimeout(function() {
                    Toastify({
                        text: "파일 저장완료",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        },
                    }).showToast();

                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            opener.location.reload();
                        }
                    }, 1000);
                }, 1000);
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    });

    // 텍스트 문자를 클릭하면 전체 체크하도록 만든다.
    $("#allcheck").click(function() {
        for (var i = 0; i < 10; i++) {
            $("#check" + i).val(new Date().toISOString().substring(0, 10));
            $("#ckbtn" + i).text("점검완료");
            $("#writer_text" + i).show();
        }
        $("#check_complete").show();
        updateDoneCheck();
    });

}); // end of ready document

// 마지막 문자 제거하는 함수
function deleteLastchar(str) {
    return str = str.substr(0, str.length - 1);
}

function updateDoneCheck() {
    var doneCheck = 0;

    console.log('update');

    for (var i = 0; i < 10; i++) {
        var dateValue = $('#check' + i).val();
        console.log(dateValue);
        if (dateValue !== '' && dateValue !== '0000-00-00' && dateValue !== null) {
            doneCheck++;
        }
    }

    $('#done_check').val(doneCheck);
    if (doneCheck === 10) {
        $('#check_complete').show();
    } else {
        $('#check_complete').hide();
    }
}

function del(href) {
    var admin = '<?php echo $admin; ?>';
    if (admin !== '1') {
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
        Swal.fire({
            title: '자료 삭제',
            text: "삭제는 신중! 정말 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "delete.php",
                    type: "post",
                    data: $("#board_form").serialize(),
                    dataType: "json",
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
                            },
                        }).showToast();
                        setTimeout(function() {
                            if (window.opener && !window.opener.closed) {
                                window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
                                window.opener.location.reload(); // 부모 창 새로고침
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
    }
}
</script>
