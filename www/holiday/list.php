<?php
/**
 * Holiday 일정표 휴일 목록 페이지
 * 휴무일 목록을 표시하고 등록/수정/삭제 기능을 제공합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
require_once(includePath('session.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$header = $_REQUEST['header'] ?? '';
$search = $_REQUEST['search'] ?? '';
$mode = $_REQUEST["mode"] ?? '';

// 변수 초기화
$tablename = 'holiday';
$title_message = '일정표 휴일';
$total_row = 0;

// 헤더 포함
include getDocumentRoot() . '/load_header.php';
?>

<link href="css/style.css" rel="stylesheet">
<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

<style>
    /* 테이블 스타일 */
    #myTable,
    #myTable th,
    #myTable td {
        border: 1px solid #dee2e6;
        border-collapse: collapse;
    }
    
    #myTable th,
    #myTable td {
        padding: 8px;
        text-align: center;
    }
    
    #myTable tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    #myTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* 모달 스타일 */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border: 1px solid #888;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        padding: 15px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: bold;
    }
    
    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover,
    .close:focus {
        color: #000;
    }
</style>
</head>

<body>

<?php
// 헤더 포함 여부 확인
if ($header == 'header') {
    require_once(includePath('myheader.php'));
}

/**
 * null 체크 함수
 * @param string|null $strtmp 체크할 문자열
 * @return bool null이 아니고 빈 문자열이 아니면 true
 */
function checkNull($strtmp) {
    return ($strtmp !== null && trim($strtmp) !== '');
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성
$order = " ORDER BY registedate DESC, num DESC ";

if (checkNull($search)) {
    // SQL Injection 방지를 위한 Prepared Statement 사용
    $sql = "SELECT * FROM {$DB}.{$tablename} 
            WHERE searchtag LIKE ? AND is_deleted IS NULL " . $order;
    $searchParam = '%' . $search . '%';
} else {
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE is_deleted IS NULL " . $order;
}

try {
    if (checkNull($search)) {
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $searchParam, PDO::PARAM_STR);
        $stmh->execute();
    } else {
        $stmh = $pdo->query($sql);
    }
    
    $total_row = $stmh->rowCount();
?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="mode" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="num" name="num">
    <input type="hidden" id="tablename" name="tablename" value="<?php echo htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="header" name="header" value="<?php echo htmlspecialchars($header, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content" style="width:600px;">
            <div class="modal-header">
                <span class="modal-title">일정표 휴일</span>
                <span class="close closeBtn">&times;</span>
            </div>
            <div class="modal-body">
                <div class="custom-card"></div>
            </div>
        </div>
    </div>
    
    <div class="container" style="width:40%;">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-center align-items-center">
                    <span class="text-center fs-5 me-4"><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></span>
                    <button type="button" class="btn btn-dark btn-sm me-1" onclick='location.href="list.php?header=header"'>
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mt-1 mb-3">
                    ▷ <?php echo htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8'); ?> &nbsp;
                    <div class="inputWrap30">
                        <input type="text" id="search" class="form-control" style="width:150px;" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" onKeyPress="if (event.keyCode == 13) { this.form.submit(); }">
                        <button class="btnClear"></button>
                    </div>
                    &nbsp;&nbsp;
                    <button class="btn btn-outline-dark btn-sm" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button id="newBtn" type="button" class="btn btn-dark btn-sm me-2">
                        <i class="bi bi-pencil-square"></i> 신규
                    </button>
                    <?php if ($header !== 'header'): ?>
                    <button id="closeBtn" type="button" class="btn btn-outline-dark btn-sm">
                        <i class="bi bi-x-lg"></i> 창닫기
                    </button>
                    <?php endif; ?>
                </div>
                
                <div class="row d-flex">
                    <div class="table-responsive">
                        <table class="table table-hover" id="myTable">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center">번호</th>
                                    <th class="text-center">휴일시작</th>
                                    <th class="text-center">휴일종료</th>
                                    <th class="text-center">기간체크</th>
                                    <th class="text-center">내용</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start_num = $total_row;
                                
                                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                                    $num = $row['num'];
                                    $registedate = $row['registedate'] ?? '';
                                    $startdate = $row['startdate'] ?? '';
                                    $enddate = $row['enddate'] ?? '';
                                    $periodcheck = ($row['periodcheck'] ?? '0') ? '예' : '아니오';
                                    $comment = $row['comment'] ?? '';
                                    
                                    // 종료일 표시 처리
                                    $enddateDisplay = ($enddate === '0000-00-00' || empty($enddate)) ? '' : $enddate;
                                ?>
                                <tr onclick="loadForm('update', '<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>');">
                                    <td class="text-center"><?php echo htmlspecialchars($start_num, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($startdate, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($enddateDisplay, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($periodcheck, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-start"><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <?php
                                    $start_num--;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
} catch (PDOException $ex) {
    error_log("DB query error in holiday/list.php: " . $ex->getMessage());
    ?>
    <div class="container" style="width:40%;">
        <div class="alert alert-danger" role="alert">
            데이터 조회 중 오류가 발생했습니다.
        </div>
    </div>
    <?php
}
?>

<script>
(function() {
    'use strict';
    
    $(document).ready(function() {
        // Loader 숨기기
        var loader = document.getElementById('loadingOverlay');
        if (loader) {
            loader.style.display = 'none';
        }
        
        // Modal 닫기 기능
        var modal = document.getElementById('myModal');
        var closeButtons = document.getElementsByClassName('close');
        
        if (closeButtons.length > 0) {
            closeButtons[0].onclick = function() {
                if (modal) {
                    modal.style.display = 'none';
                }
            };
        }
        
        // 모달 외부 클릭 시 닫기
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
        
        // 신규 버튼 클릭 시
        $('#newBtn').on('click', function() {
            loadForm('insert');
        });
        
        // 검색 버튼 클릭 시
        $('#searchBtn').on('click', function() {
            $('#board_form').submit();
        });
        
        // 창닫기 버튼 (있는 경우)
        $('#closeBtn').on('click', function() {
            window.close();
        });
    });
    
    /**
     * 폼 로드 함수
     * @param {string} mode - 모드 (insert/update)
     * @param {string|null} num - 레코드 번호
     */
    window.loadForm = function(mode, num) {
        if (num == null) {
            $('#mode').val('insert');
        } else {
            $('#mode').val('update');
            $('#num').val(num);
        }
        
        $.ajax({
            type: 'POST',
            url: 'fetch_modal.php',
            data: { 
                mode: mode, 
                num: num 
            },
            dataType: 'html',
            success: function(response) {
                var modalBody = document.querySelector('.modal-body .custom-card');
                if (modalBody) {
                    modalBody.innerHTML = response;
                }
                
                var myModal = document.getElementById('myModal');
                if (myModal) {
                    myModal.style.display = 'block';
                }
                
                // 동적 기간체크 기능 추가
                $('#periodcheck').on('change', function() {
                    var enddateWrapper = $('#enddateWrapper');
                    var enddateInput = $('#enddate');
                    
                    if ($(this).is(':checked')) {
                        enddateWrapper.show();
                    } else {
                        enddateWrapper.hide();
                        enddateInput.val('');
                    }
                });
                
                // 초기 로드 시 체크박스 상태에 따른 필드 표시/숨김
                if ($('#periodcheck').is(':checked')) {
                    $('#enddateWrapper').show();
                } else {
                    $('#enddateWrapper').hide();
                }
                
                // 모달 닫기 버튼
                $('.closeBtn').on('click', function() {
                    $('#myModal').hide();
                });
                
                // 저장 버튼
                $('#saveBtn').on('click', function() {
                    var formData = $('#board_form').serialize();
                    
                    // 시작일 필수 확인
                    var startdate = $('#startdate').val();
                    if (!startdate) {
                        Toastify({
                            text: '시작일을 입력해주세요.',
                            duration: 2000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#ff5f5f'
                        }).showToast();
                        return;
                    }
                    
                    $.ajax({
                        url: 'process.php',
                        type: 'post',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Toastify({
                                    text: '저장완료',
                                    duration: 2000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    backgroundColor: '#4fbe87'
                                }).showToast();
                                
                                setTimeout(function() {
                                    $('#myModal').hide();
                                    location.reload();
                                }, 1000);
                            } else {
                                Toastify({
                                    text: response.message || '저장 중 오류가 발생했습니다.',
                                    duration: 3000,
                                    close: true,
                                    gravity: 'top',
                                    position: 'center',
                                    backgroundColor: '#ff5f5f'
                                }).showToast();
                            }
                        },
                        error: function(jqxhr, status, error) {
                            console.error('Save error:', jqxhr, status, error);
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
                });
                
                // 삭제 버튼
                $('#deleteBtn').on('click', function() {
                    deleteHoliday(num);
                });
            },
            error: function(jqxhr, status, error) {
                console.error('AJAX Error:', status, error);
                Toastify({
                    text: '폼 로드 중 오류가 발생했습니다.',
                    duration: 3000,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#ff5f5f'
                }).showToast();
            }
        });
    };
    
    /**
     * 휴일 삭제 함수
     * @param {string} num - 삭제할 레코드 번호
     */
    window.deleteHoliday = function(num) {
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
                $('#num').val(num);
                var formData = $('#board_form').serialize();
                
                $.ajax({
                    url: 'process.php',
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Toastify({
                                text: '삭제 완료',
                                duration: 2000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                            
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            Toastify({
                                text: response.message || '삭제 중 오류가 발생했습니다.',
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'center',
                                backgroundColor: '#ff5f5f'
                            }).showToast();
                        }
                    },
                    error: function(jqxhr, status, error) {
                        console.error('Delete error:', jqxhr, status, error);
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
    };
})();
</script>

</body>
</html>
