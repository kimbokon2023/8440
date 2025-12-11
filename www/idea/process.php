<?php
/**
 * Idea 직원 제안제도 처리 폼 페이지
 * 제안사항을 승인하거나 처리합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$admin_name = $_SESSION["name"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

// 변수 초기화
$tablename = 'idea';
$errortype_arr = array();
$basic_name_arr = array();
$basic_part_arr = array();

// rowDB.php에서 사용될 변수들 사전 초기화
$place = '';
$occur = '';
$errortype = '';
$emember = '';
$content = '';
$method = '';
$filename = '';
$serverfilename = '';
$approve = '';
$payment = '';
$firstone = '';
$imgurl = '';
$reporter = '';
$part = '';
$occurconfirm = '';
$saveurl = '';
$involved = '';
$steelrequirement = '';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// errortype 배열 불러오기
try {
    $sql = "SELECT * FROM {$DB}.errortype";
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($errortype_arr, $row["errortype"] ?? '');
    }
    
    $errortype_arr = array_unique($errortype_arr);
    sort($errortype_arr);
    
} catch (PDOException $ex) {
    error_log("Errortype query error in idea/process.php: " . $ex->getMessage());
}

// 레코드 조회 (수정 모드)
if (!empty($num)) {
    try {
        $sql = "SELECT * FROM {$DB}.idea WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include 'rowDB.php';
            $imgurl = './img/' . $serverfilename;
        }
        
    } catch (PDOException $ex) {
        error_log("DB query error in idea/process.php: " . $ex->getMessage());
    }
}

// 지원부서 이름으로 불러오기 (기본정보)
include "../annualleave/load_DB.php";

// 신규 데이터인 경우
if (empty($num)) {
    $reporter = $user_name;
    $occur = date("Y-m-d", time());
    $occurconfirm = date("Y-m-d", time());
    $approve = '결재상신';
    $name = $user_name;
    
    // DB에서 part 찾기
    for ($i = 0; $i < count($basic_name_arr); $i++) {
        if (trim($basic_name_arr[$i]) == trim($name)) {
            $part = $basic_part_arr[$i] ?? '';
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
    <meta name="description" content="직원 제안제도 운영">
    <meta name="author" content="">
    
    <title>품질불량 원인분석 및 개선대책</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
    <!-- Common JS (동적 로드) -->
    <script>
        var baseUrl = '<?php echo addslashes(getBaseUrl()); ?>';
        var script = document.createElement('script');
        script.src = baseUrl + '/common.js';
        script.onerror = function() {
            console.error('common.js 로드 실패');
        };
        document.head.appendChild(script);
    </script>
    
    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-center">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title">알림</h4>
            </div>
            <div class="modal-body">
                <div id="alertmsg" class="fs-1 mb-5 justify-content-center">
                    결재가 진행중입니다. <br><br>
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
                        <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" size="5">
                        <input type="hidden" id="filedelete" name="filedelete">
                        <input type="hidden" id="filename" name="filename" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" id="serverfilename" name="serverfilename" value="<?php echo htmlspecialchars($serverfilename, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8'); ?>">
                        
                        <span class="form-control">
                            성명 &nbsp;&nbsp;
                            <input type="text" id="reporter" name="reporter" size="8" class="text-center" value="<?php echo htmlspecialchars($reporter, ENT_QUOTES, 'UTF-8'); ?>">
                            &nbsp;&nbsp; 부서 &nbsp;&nbsp;
                            <input type="text" id="part" name="part" size="8" class="text-center" value="<?php echo htmlspecialchars($part, ENT_QUOTES, 'UTF-8'); ?>">
                            &nbsp;&nbsp; 현장명 &nbsp;&nbsp;
                            <input type="text" name="place" id="place" size="50" class="text-left" value="<?php echo htmlspecialchars($place, ENT_QUOTES, 'UTF-8'); ?>" autofocus>
                        </span>
                        
                        <span class="form-control">
                            &nbsp;&nbsp; 부적합 유형 &nbsp;&nbsp;
                            <select name="errortype" id="errortype">
                                <?php
                                for ($i = 0; $i < count($errortype_arr); $i++) {
                                    $selected = ($errortype == $errortype_arr[$i]) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($errortype_arr[$i], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>';
                                    echo htmlspecialchars($errortype_arr[$i], ENT_QUOTES, 'UTF-8');
                                    echo '</option>';
                                }
                                ?>
                            </select>
                            
                            <button type="button" id="registerrortypeBtn" class="btn btn-outline-primary btn-sm">부적합유형 등록</button> &nbsp;
                            &nbsp;&nbsp; 발생일 &nbsp;&nbsp;
                            <input type="date" id="occur" name="occur" value="<?php echo htmlspecialchars($occur, ENT_QUOTES, 'UTF-8'); ?>">
                            
                            &nbsp;&nbsp; 불량확인일 &nbsp;&nbsp;
                            <input type="date" id="occurconfirm" name="occurconfirm" value="<?php echo htmlspecialchars($occurconfirm, ENT_QUOTES, 'UTF-8'); ?>">
                        </span>
                        
                        <span class="form-control">
                            <span style="color:gray">도면 저장위치</span>
                            <input type="text" id="saveurl" size="30" name="saveurl" class="text-left" value="<?php echo htmlspecialchars($saveurl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="nas2dual 도면 저장위치">
                            <span style="color:gray">관련직원</span>
                            <input type="text" id="involved" size="30" name="involved" class="text-left" value="<?php echo htmlspecialchars($involved, ENT_QUOTES, 'UTF-8'); ?>" placeholder="관련 직원">
                        </span>
                        
                        <span class="form-control">
                            <span style="color:green">첨부파일(이미지)</span>
                            <?php if (!empty($filename)) echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                            <input id="mainbefore" name="mainBefore" type="file" accept="image/*">
                        </span>
                        
                        <span class="form-control">
                            <h4>
                                <span style="color:blue">불량 발생 원인 및 분석</span>
                            </h4>
                        </span>
                        
                        <span class="form-control">
                            <textarea type="text" id="content" class="form-control" rows="5" name="content"><?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </span>
                        
                        <span class="form-control">
                            <h4>
                                <span style="color:red">처리방안 및 개선사항</span>
                            </h4>
                        </span>
                        
                        <span class="form-control">
                            <textarea type="text" id="method" class="form-control" rows="5" name="method"><?php echo htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </span>
                        
                        <span class="form-control">
                            <h4>
                                <span style="color:green">원자재 및 자재 소요량</span>
                            </h4>
                        </span>
                        
                        <span class="form-control">
                            <textarea type="text" id="steelrequirement" class="form-control" rows="1" name="steelrequirement"><?php echo htmlspecialchars($steelrequirement, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </span>
                        
                        <?php
                        if (!empty($filename)) {
                            echo '<span class="form-control">';
                            echo '<br>';
                            echo '<div class="imagediv">';
                            echo '<img class="before_work" src="' . htmlspecialchars($imgurl, ENT_QUOTES, 'UTF-8') . '" style="width:100%;height:100%">';
                            echo '</div>';
                            echo '</span>';
                            echo '<br>';
                        }
                        ?>
                        
                        <br>
                        <h5 class="form-signin-heading">결재 상태</h5>
                        <input type="text" id="approve" name="approve" class="form-control text-center" readonly value="<?php echo htmlspecialchars($approve, ENT_QUOTES, 'UTF-8'); ?>">
                        <br>
                        
                        <button id="saveBtn" class="btn btn-lg btn-secondary btn-block" type="button">
                            <?php echo ((int)$num > 0) ? '승인' : '결재상신(등록)'; ?>
                        </button>
                        
                        <?php if ((int)$num > 0): ?>
                        <button id="delBtn" class="btn btn-lg btn-danger btn-block" type="button">삭제</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    $(document).ready(function() {
        // 신청일 변경 시 종료일도 변경
        $('#occur').on('change', function() {
            $('#occurconfirm').val($('#occur').val());
        });
        
        // 에러타입 등록 수정 삭제
        $('#registerrortypeBtn').on('click', function() {
            var href = '../registerrortype.php';
            if (typeof popupCenter === 'function') {
                popupCenter(href, '부적합 유형 등록', 600, 600);
            } else {
                window.open(href, '부적합 유형 등록', 'width=600,height=600');
            }
        });
        
        // 사진 등록
        $('#regpicBtn').on('click', function() {
            var num = $('#num').val();
            window.open('reg_pic.php?num=' + encodeURIComponent(num), '사진등록', 'width=1200,height=700,top=0,left=0,scrollbars=no');
        });
        
        // 파일 변경 시
        $('#mainbefore').on('change', function(e) {
            var isfile = $('#filename').val();
            var changefile = $('#mainbefore').val();
            
            if (changefile !== '') {
                $('#filename').val('');
            }
        });
        
        // 모달 닫기
        $('#closeModalBtn').on('click', function() {
            $('#myModal').modal('hide');
        });
        
        // 창 닫기
        $('#closeBtn').on('click', function() {
            // 필요 시 처리
        });
        
        // 저장 버튼
        $('#saveBtn').on('click', function() {
            var num = $('#num').val();
            var part = $('#part').val();
            var approve = $('#approve').val();
            var user_name = $('#user_name').val();
            var reporter = $('#reporter').val();
            var admin_name = $('#admin_name').val();
            
            var resultOK = 0;
            
            // 2차 결재권자 (소현철, 김보곤) - 1차결재 → 처리완료
            if ((admin_name === '소현철' || admin_name === '김보곤') && approve === '1차결재') {
                $('#approve').val('처리완료');
                resultOK = 1;
            }
            
            // 1차 결재권자 (이경묵, 김선영, 김보곤) - 결재상신 → 1차결재
            if ((admin_name === '이경묵' || admin_name === '김선영' || admin_name === '김보곤') && approve === '결재상신') {
                $('#approve').val('1차결재');
                resultOK = 1;
            }
            
            console.log('변경후 approve: ' + approve);
            
            if (resultOK === 1) {
                $('#mode').val('modify');
                
                console.log($('#mode').val());
                
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
                    url: 'insert.php',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        
                        if (data.success) {
                            Toastify({
                                text: '저장완료',
                                duration: 2000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                style: {
                                    background: 'linear-gradient(to right, #00b09b, #96c93d)'
                                }
                            }).showToast();
                            
                            setTimeout(function() {
                                if (window.opener && !window.opener.closed) {
                                    window.opener.location.reload();
                                }
                                window.close();
                            }, 1000);
                        } else {
                            $('#myModal').modal('hide');
                            Toastify({
                                text: data.message || '저장 중 오류가 발생했습니다.',
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                        }
                    },
                    error: function(jqxhr, status, error) {
                        console.error(jqxhr, status, error);
                        $('#myModal').modal('hide');
                        Toastify({
                            text: '저장 중 오류가 발생했습니다.',
                            duration: 3000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#ff5f5f'
                        }).showToast();
                    }
                });
            } else {
                tmp = '결재권자만 승인이 가능합니다.';
                $('#alertmsg').html(tmp);
                $('#myModal').modal('show');
            }
        });
        
        // 삭제 버튼
        $('#delBtn').on('click', function() {
            var num = $('#num').val();
            var reporter = $('#reporter').val();
            var approve = $('#approve').val();
            var user_name = $('#user_name').val();
            
            // 결재상신이 아닌 경우 수정 안됨
            if ((reporter === user_name && approve === '결재상신') || user_name === '김보곤') {
                Swal.fire({
                    title: '자료 삭제',
                    text: '정말 삭제하시겠습니까?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '삭제',
                    cancelButtonText: '취소'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $('#mode').val('delete');
                        
                        $.ajax({
                            url: 'insert.php',
                            type: 'post',
                            data: $('#board_form').serialize(),
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                
                                if (data.success) {
                                    Toastify({
                                        text: '삭제 완료',
                                        duration: 2000,
                                        close: true,
                                        gravity: 'top',
                                        position: 'center',
                                        backgroundColor: '#ff5f5f'
                                    }).showToast();
                                    
                                    setTimeout(function() {
                                        if (window.opener && !window.opener.closed) {
                                            window.opener.location.reload();
                                        }
                                        window.close();
                                    }, 1000);
                                } else {
                                    Toastify({
                                        text: data.message || '삭제 중 오류가 발생했습니다.',
                                        duration: 3000,
                                        close: true,
                                        gravity: 'top',
                                        position: 'center',
                                        backgroundColor: '#ff5f5f'
                                    }).showToast();
                                }
                            },
                            error: function(jqxhr, status, error) {
                                console.error(jqxhr, status, error);
                                Toastify({
                                    text: '삭제 중 오류가 발생했습니다.',
                                    duration: 3000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    backgroundColor: '#ff5f5f'
                                }).showToast();
                            }
                        });
                    }
                });
            } else {
                var tmp = '보고자만 결재상신이 아닌 경우 삭제할 수 있습니다.';
                $('#alertmsg').html(tmp);
                $('#myModal').modal('show');
            }
        });
    });
    
    /**
     * 사진 표시 함수 (AJAX로 불러오기)
     */
    window.displayPicture = function() {
        $('#displayPicture').show();
        var params = $('#num').val();
        
        console.log($('#num').val());
        
        $.ajax({
            url: 'loadpic.php?num=' + encodeURIComponent(params),
            type: 'post',
            data: $('mainFrm').serialize(),
            dataType: 'json'
        }).done(function(data) {
            var recnum = data['recnum'];
            console.log(data);
            
            $('#displayPicture').html('');
            for (var i = 0; i < recnum; i++) {
                $('#displayPicture').append('<img id="pic' + i + '" src="img/' + data['img_arr'][i] + '">');
                $('#displayPicture').append('&nbsp;<button type="button" class="btn btn-secondary" id="delPic' + i + '" onclick="delPicFn(\'' + i + '\',\'' + data['img_arr'][i] + '\')"> 삭제 </button>&nbsp;');
            }
            $('#pInput').val('');
        });
    };
    
    /**
     * 기존 사진 로드
     */
    window.displayPictureLoad = function() {
        $('#displayPicture').show();
        var picNum = <?php echo json_encode($picNum ?? 0, JSON_UNESCAPED_UNICODE); ?>;
        var picData = <?php echo json_encode($picData ?? array(), JSON_UNESCAPED_UNICODE); ?>;
        
        for (var i = 0; i < picNum; i++) {
            $('#displayPicture').append('<img id="pic' + i + '" src="img/' + picData[i] + '">');
            $('#displayPicture').append('&nbsp;<button type="button" class="btn btn-secondary" id="delPic' + i + '" onclick="delPicFn(\'' + i + '\',\'' + picData[i] + '\')"> 삭제 </button>&nbsp;');
        }
        $('#pInput').val('');
    };
    
    /**
     * 사진 삭제 함수
     * @param {string} delChoice - 삭제할 위치
     */
    window.delPic = function(delChoice) {
        if (delChoice === 'before') {
            $('#filedelete').val('before');
        }
        if (delChoice === 'after') {
            $('#filedelete').val('after');
        }
        
        document.getElementById('board_form').submit();
    };
    
    /**
     * 사진 삭제 함수 (개별)
     * @param {string} divID - div ID
     * @param {string} delChoice - 삭제할 파일명
     */
    window.delPicFn = function(divID, delChoice) {
        console.log(divID, delChoice);
        
        $.ajax({
            url: 'delpic.php?picname=' + encodeURIComponent(delChoice),
            type: 'post',
            data: $('mainFrm').serialize(),
            dataType: 'json'
        }).done(function(data) {
            var picname = data['picname'];
            console.log(data);
            
            $('#pic' + divID).remove();
            $('#delPic' + divID).remove();
            $('#pInput').val('');
        });
    };
})();
</script>

</body>
</html>
