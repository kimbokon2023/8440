<?php
/**
 * Idea 현장 검색창 페이지
 * jamb(work) 또는 ceiling 테이블에서 현장명을 검색합니다.
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
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 파라미터 초기화
$outworkplace = $_REQUEST["outworkplace"] ?? '';
$num = $_REQUEST["num"] ?? '';
$filename = $_REQUEST["filename"] ?? '';
$serverfilename = $_REQUEST["serverfilename"] ?? '';

// 동적 common.js 로드를 위한 baseUrl
$baseUrl = getBaseUrl();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="현장 검색창">
    <meta name="author" content="">
    
    <title>현장 검색창</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
    <!-- Common JS (동적 로드) -->
    <script>
        var baseUrl = '<?php echo addslashes($baseUrl); ?>';
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
        
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .search-option {
            display: inline-block;
            margin: 0 10px;
        }
        
        #displaysearch {
            max-height: 500px;
            overflow-y: auto;
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
        <div class="card align-middle" style="width:38rem; border-radius:20px;">
            <div class="card" style="padding:6px;margin:7px;">
                <h5 class="card-title text-center" style="color:#113366;">
                    검색선택 : &nbsp;
                    <span class="search-option">
                        쟘(jamb) <input type="radio" checked name="search_opt" value="1">
                    </span>
                    &nbsp;
                    <span class="search-option">
                        천장 <input type="radio" name="search_opt" value="2">
                    </span>
                    &nbsp;
                    <br><br>
                </h5>
                
                <input type="text" id="outworkplace" name="outworkplace" onkeydown="JavaScript:Enter_Check();" value="<?php echo htmlspecialchars($outworkplace, ENT_QUOTES, 'UTF-8'); ?>" placeholder="현장명">
                <button type="button" class="btn btn-outline-dark btn-sm" onclick="Choice_search();">검색</button> &nbsp;
            </div>
            
            <div class="card-body text-center">
                <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="mode" name="mode">
                    <input type="hidden" id="num" name="num" value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" size="5">
                    <input type="hidden" id="filedelete" name="filedelete">
                    <input type="hidden" id="filename" name="filename" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" id="serverfilename" name="serverfilename" value="<?php echo htmlspecialchars($serverfilename, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <span class="form-control">
                        <div id="displaysearch"></div>
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    /**
     * Enter 키 캡처 함수 (textarea 제외)
     * @param {Event} e - 이벤트 객체
     * @return {boolean} false면 기본 동작 방지
     */
    window.captureReturnKey = function(e) {
        if (e.keyCode == 13 && e.srcElement.type != 'textarea') {
            return false;
        }
        return true;
    };
    
    /**
     * Enter 키로 검색 실행
     * @param {Event} e - 이벤트 객체
     */
    window.recaptureReturnKey = function(e) {
        if (e.keyCode == 13) {
            if (typeof exe_search === 'function') {
                exe_search();
            }
        }
    };
    
    /**
     * Enter 키 체크 및 검색 실행
     */
    window.Enter_Check = function() {
        if (window.event && window.event.keyCode === 13) {
            var tmp = $('input[name=search_opt]:checked').val();
            
            if (tmp == 1) {
                search_jamb(); // 잠 현장검색
            } else if (tmp == 2) {
                search_ceiling(); // 천장 현장 검색
            }
        }
    };
    
    /**
     * 작업지시(jamb) 현장 검색
     */
    window.search_jamb = function() {
        var text1 = document.getElementById('outworkplace').value;
        
        if (!text1 || text1.trim() === '') {
            alert('검색어를 입력해주세요.');
            return;
        }
        
        var postData = encodeURIComponent(text1);
        
        $('#displaysearch').show();
        $('#displaysearch').load('./search_jamb.php?mode=search&search=' + postData);
    };
    
    /**
     * 천장재(ceiling) 현장 검색
     */
    window.search_ceiling = function() {
        var text1 = document.getElementById('outworkplace').value;
        
        if (!text1 || text1.trim() === '') {
            alert('검색어를 입력해주세요.');
            return;
        }
        
        var postData = encodeURIComponent(text1);
        
        $('#displaysearch').show();
        $('#displaysearch').load('./search_ceiling.php?mode=search&search=' + postData);
    };
    
    /**
     * 선택된 검색 옵션에 따라 검색 실행
     */
    window.Choice_search = function() {
        var tmp = $('input[name=search_opt]:checked').val();
        
        if (tmp == '1') {
            search_jamb(); // 잠 현장검색
        } else if (tmp == '2') {
            search_ceiling(); // 천장 현장 검색
        } else {
            alert('검색 옵션을 선택해주세요.');
        }
    };
    
    $(document).ready(function() {
        // 모달 닫기
        $('#closeModalBtn').on('click', function() {
            $('#myModal').modal('hide');
        });
    });
})();
</script>

</body>
</html>
