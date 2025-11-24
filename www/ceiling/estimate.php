<?php
require_once __DIR__ . '/../bootstrap.php';

// 기본 설정
$root_dir = getDocumentRoot();

ini_set('display_errors', '1');  // 화면에 warning 없애기

// 모바일 사용여부 확인하는 루틴
$mAgent = array("iPhone", "iPod", "Android", "Blackberry",
    "Opera Mini", "Windows ce", "Nokia", "sony");
$chkMobile = false;

for ($i = 0; $i < sizeof($mAgent); $i++) {
    if (stripos($_SERVER['HTTP_USER_AGENT'], $mAgent[$i])) {
        $chkMobile = true;
        break;
    }
}

// 세션 변수 초기화
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";

// 환경파일 불러오기
$readIni = array();
$readIni = parse_ini_file("./estimate.ini", false);

$init_read = array();
$init_read = parse_ini_file("./estimate.ini", false);

// 요청 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 모드 및 기본값 설정
$mode = "";
$isEditMode = false;
$registedate = date("Y-m-d");
$mcno = '';
$inputsum = '';
$outputsum = '';

if ($num == '') {
    // 신규 등록 모드
    $registedate = date("Y-m-d");
    $mcno = '';
    $inputsum = '';
    $outputsum = '';
} else {
    // 수정 모드
    $isEditMode = true;
    $mode = "modify";
}

?>  

<?php include getDocumentRoot() . '/load_header.php' ?>

<title> 천장 제품단가 </title>
</head>

<body>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-center">
        <!-- Modal content-->
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">알림</h4>
            </div>
            <div class="modal-body">
                <div id="alertmsg" class="fs-1 mb-5 justify-content-center">
                    결재가 진행중입니다. <br>
                    <br>
                    수정사항이 있으면 결재권자에게 말씀해 주세요.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<style>
    .fixed-table {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 1;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* 우측배너 제작 */
    .sideBanner {
        position: absolute;
        width: calc(100vw - 90vw);
        height: calc(100vh - 70vh);
        top: calc(100vh - 70vh);
        left: calc(100vw - 20vw);
    }

    /* 모바일 최적화 */
    @media (max-width: 768px) {
        /* body와 html의 width 제한 */
        html, body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            font-size: 16px !important;
        }

        /* 컨테이너 모바일 최적화 */
        .container-fluid {
            max-width: 100vw !important;
            padding: 10px !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* 행 레이아웃 모바일 최적화 */
        .row {
            margin: 0 !important;
            padding: 0 !important;
        }

        .row.justify-content-center.align-items-center.vh-100 {
            min-height: auto !important;
            height: auto !important;
            padding: 1rem 0 !important;
        }

        /* 컬럼 모바일 최적화 */
        .col-sm-7 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }

        /* 카드 모바일 최적화 */
        .card {
            margin: 0.5rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
            border-radius: 12px !important;
        }

        .card-body {
            padding: 1rem 0.75rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        .card-title {
            font-size: 1rem !important;
            margin-bottom: 1rem !important;
            padding: 0 0.5rem !important;
        }

        /* 테이블 모바일 최적화 */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .table {
            font-size: 0.8rem !important;
            width: 100% !important;
            min-width: 400px !important;
            margin-bottom: 0 !important;
        }

        .table th {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.4rem !important;
            white-space: nowrap !important;
            text-align: center !important;
        }

        .table td {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.4rem !important;
            word-wrap: break-word !important;
            text-align: center !important;
        }

        .table td:first-child {
            font-weight: 500 !important;
            text-align: left !important;
        }

        /* 입력 필드 모바일 최적화 */
        .form-control {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.5rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .form-control.w-75 {
            width: 90% !important;
            max-width: 150px !important;
        }

        /* 버튼 영역 모바일 최적화 - 하단 고정 */
        .sideBanner {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            height: auto !important;
            top: auto !important;
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1) !important;
            z-index: 1000 !important;
            padding: 0.75rem 1rem !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        .sideBanner > div {
            margin: 0 !important;
            flex: 0 0 auto !important;
        }

        .sideBanner .btn {
            font-size: 0.85rem !important;
            padding: 0.5rem 1.5rem !important;
            white-space: nowrap !important;
            min-width: 80px !important;
            box-sizing: border-box !important;
        }

        /* 모든 버튼과 텍스트가 카드 내부에 머물도록 */
        .card *,
        .container-fluid * {
            box-sizing: border-box !important;
        }

        .card button,
        .card .btn,
        .card span,
        .card input,
        .card table,
        .container-fluid button,
        .container-fluid .btn,
        .container-fluid span,
        .container-fluid input {
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 모달 모바일 최적화 */
        .modal-dialog.modal-lg {
            max-width: 95% !important;
            margin: 1rem auto !important;
        }

        .modal-content {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .modal-body {
            padding: 1rem 0.75rem !important;
            font-size: 0.9rem !important;
        }

        .modal-body .fs-1 {
            font-size: 1.1rem !important;
        }
    }
</style>	

<form id="board_form" name="board_form" class="form-signin" method="post">
    <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
    <input type="hidden" id="num" name="num" value="<?=$num?>">
    <input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>">
    
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-sm-7 text-center">
                <div class="card align-middle justify-content-center" style="border-radius: 20px;">
                    <div class="card-body mt-5 mb-5">
                        <div class="d-flex justify-content-center">
                            <span class="card-title fs-5 mb-1" style="color: #113366;">조명천장/본천장 제품 단가</span>
                        </div>
                        
                        <div class="table-responsive justify-content-center">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:50%;">인승</th>
                                        <th>조명천장</th>
                                        <th>본천장</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>12인승 이하</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control w-75 text-end" name="lc_unit_12" value="<?=$readIni['lc_unit_12']?>" data-separator="," />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control w-75 text-end" name="bon_unit_12" value="<?=$readIni['bon_unit_12']?>" data-separator="," />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>13인승 이상 17인승 이하</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control w-75 text-end" name="bon_unit_13to17" value="<?=$readIni['bon_unit_13to17']?>" data-separator="," />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control w-75 text-end" name="lc_unit_13to17" value="<?=$readIni['lc_unit_13to17']?>" data-separator="," />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>18인승 이상</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control w-75 text-end" name="lc_unit_18" value="<?=$readIni['lc_unit_18']?>" data-separator="," />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="text" class="form-control w-75 text-end" name="bon_unit_18" value="<?=$readIni['bon_unit_18']?>" data-separator="," />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sideBanner">
        <div class="mb-1 mt-1">
            <button type="button" class="btn btn-dark rounded-pill fs-6" id="saveButton">저장</button>
        </div>
        <div class="mb-1 mt-1">
            <button type="button" class="btn btn-secondary rounded-pill closeBtn fs-6">닫기</button>
        </div>
    </div>
</form>

<script>

// 전자결재를 위해 띄우는 창
// 기본 위치(top)값
var floatPosition = parseInt($(".sideBanner").css('top'));

// scroll 인식
$(window).scroll(function() {
    // 모바일에선 나타나지 않게 하기
    // 현재 스크롤 위치
    var currentTop = $(window).scrollTop();
    var bannerTop = currentTop + floatPosition + "px";
    
    // 이동 애니메이션
    $(".sideBanner").stop().animate({
        "top": bannerTop
    }, 500);
}).scroll();

$(document).ready(function() {
    
    // 숫자 포맷팅 (콤마 추가)
    $('.form-control').on('input', function() {
        var separator = $(this).data('separator');
        var value = $(this).val().replace(/\,/g, '');
        var parsedValue = parseInt(value);
        var formattedValue = isNaN(parsedValue) ? '' : parsedValue.toLocaleString();
        $(this).val(formattedValue);
    });
    
    var state = $('#state').val();
    // 처리완료인 경우는 수정하기 못하게 한다.
    
    // 모달 닫기
    $("#closeModalBtn").click(function() {
        $('#myModal').modal('hide');
    });
    
    // 창 닫기
    $(".closeBtn").click(function() {
        myalert("창 닫기!");
        opener.location.reload();
        window.close();
    });
    
    // 저장 버튼
    $('#saveButton').on('click', function() {
        $.ajax({
            url: "save_estimate.php",
            type: "post",
            data: $("#board_form").serialize(),
            success: function(data) {
                console.log(data);
                
                Toastify({
                    text: '저장되었습니다.',
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    });
    
}); // end of ready document

function myalert(str) {
    Toastify({
        text: str,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "center",
        backgroundColor: "#4fbe87",
        className: "toastify-content",
    }).showToast();
    
    setTimeout(function() {
        // 시간지연
    }, 1000);
}

</script>

</body>
</html>

