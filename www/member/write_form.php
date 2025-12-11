<?php
/**
 * 회원 등록/수정 폼 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 쿠키 변수 초기화
$check = isset($_COOKIE['check']) ? $_COOKIE['check'] : 'false';
$lastdate = isset($_COOKIE['lastdate']) ? $_COOKIE['lastdate'] : 'false';

// 세션 변수
$user_name = $_SESSION["name"] ?? '';

// 요청 변수 초기화
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : '';

// 회원 정보 변수 초기화
$name = '';
$pass = '';
$hp = '';
$level = '8';
$part = '';
$position = '';
$numorder = '';
$eworks_level = '';
$division = '';
$mode = 'insert';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드 (기존 회원 정보 조회)
if ($id !== 'null' && !empty($id)) {
    try {
        $sql = "SELECT * FROM mirae8440.member WHERE id = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        
        if ($count > 0) {
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            include '_row.php';
            $mode = 'modify';
        } else {
            echo "<script>
                alert('회원 정보를 찾을 수 없습니다.');
                window.close();
            </script>";
            exit;
        }
        
    } catch (PDOException $ex) {
        error_log("회원 정보 조회 오류 (id: {$id}): " . $ex->getMessage());
        echo "<script>
            alert('회원 정보를 불러오는 중 오류가 발생했습니다.');
            window.close();
        </script>";
        exit;
    }
} else {
    // 신규 등록 모드
    $id = '';
    $level = '8';
    $mode = 'insert';
}

include getDocumentRoot() . '/load_header.php';
?>

<title>회원관리(등록)</title>

<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
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
	.container,
	.container-fluid {
		max-width: 100vw !important;
		padding: 10px !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
	}

	/* 카드 모바일 최적화 */
	.card {
		margin: 0.5rem auto !important;
		width: 100% !important;
		max-width: 100% !important;
		overflow-x: hidden !important;
		box-sizing: border-box !important;
		border-radius: 12px !important;
	}

	.card-body {
		padding: 0.75rem 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	.card-title {
		font-size: 1.1rem !important;
		margin-bottom: 0.75rem !important;
		padding: 0 0.5rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	/* 테이블 모바일 최적화 - 카드 레이아웃 */
	.table-responsive {
		overflow-x: visible !important;
	}

	.table,
	.table thead,
	.table tbody,
	.table tr,
	.table td {
		display: block !important;
		width: 100% !important;
	}

	.table thead {
		display: none !important;
	}

	.table tr {
		margin: 0 auto 10px auto !important;
		border: 1px solid #dee2e6 !important;
		border-radius: 10px !important;
		background: white !important;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
		padding: 12px !important;
		overflow: hidden !important;
		box-sizing: border-box !important;
		width: 100% !important;
		max-width: 100% !important;
	}

	/* 카드 내 필드 스타일 */
	.table td {
		text-align: left !important;
		padding: 12px !important;
		border: none !important;
		position: relative !important;
		padding-left: 0 !important;
		white-space: normal !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		min-height: 50px !important;
		font-size: 0.95rem !important;
		line-height: 1.6 !important;
		box-sizing: border-box !important;
		display: flex !important;
		flex-direction: column !important;
		gap: 0.5rem !important;
	}

	/* data-label이 있는 경우 원본 텍스트 숨기기 */
	.table td[data-label]:not([data-label=""]) {
		font-size: 0 !important;
		line-height: 0 !important;
	}

	.table td[data-label]:not([data-label=""]) * {
		font-size: 0.95rem !important;
		line-height: 1.6 !important;
	}

	/* 라벨 표시 */
	.table td:before {
		content: attr(data-label);
		position: static !important;
		width: 100% !important;
		padding-right: 0 !important;
		white-space: normal !important;
		overflow: visible !important;
		text-overflow: clip !important;
		font-weight: 600 !important;
		color: #495057 !important;
		font-size: 0.9rem !important;
		margin-bottom: 0.25rem !important;
		display: block !important;
		line-height: 1.4 !important;
	}

	.table td:after {
		display: none !important;
	}

	/* 입력 필드 모바일 최적화 */
	.table td input,
	.table td select {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		font-size: 0.95rem !important;
		padding: 0.6rem !important;
		border: 1px solid #ced4da !important;
		border-radius: 0.375rem !important;
	}

	/* 빈 셀 숨기기 */
	.table td[data-label=""] {
		display: none !important;
	}

	/* colspan이 있는 셀 처리 */
	.table td[colspan] {
		display: block !important;
	}

	/* 모든 텍스트와 버튼이 카드 내부에 머물도록 */
	.card *,
	.container *,
	.container-fluid *,
	.row *,
	.col-md-* {
		box-sizing: border-box !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	.card button,
	.card .btn,
	.card span,
	.card input,
	.card table,
	.card p,
	.card ul,
	.card li,
	.card strong,
	.card label,
	.card select {
		max-width: 100% !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		white-space: normal !important;
	}

	/* 버튼 모바일 최적화 */
	.btn-sm {
		font-size: 0.85rem !important;
		padding: 0.5rem 0.75rem !important;
		white-space: nowrap !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	/* 버튼 그룹 모바일 최적화 */
	.d-flex.justify-content-center {
		flex-wrap: wrap !important;
		gap: 0.5rem !important;
		justify-content: center !important;
	}

	/* 모달 모바일 최적화 */
	.modal-dialog {
		width: 95% !important;
		max-width: 95% !important;
		margin: 1rem auto !important;
	}

	.modal-content {
		width: 100% !important;
		max-width: 100% !important;
		border-radius: 12px !important;
	}

	.modal-header,
	.modal-body,
	.modal-footer {
		padding: 1rem !important;
	}

	.modal-title {
		font-size: 1.1rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	.modal-body {
		font-size: 0.95rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}
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
    
    <div class="container-fluid">
        <div class="card align-middle">
            <div class="card" style="padding:10px;margin:10px;">
                <h4 class="card-title text-center" style="color:#113366;">회원등록/수정</h4>
            </div>
            
            <div class="card-body text-center">
                <form id="board_form" name="board_form" class="form-signin" method="post">
                    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
                    
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="3" data-label="* 구분">* 구분(미래기업,협력사,작업소장,포미스톤)</td>
                            <td colspan="1" data-label="구분 선택">
                                <select id="division" name="division" class="form-select w-auto" style="font-size: 0.7rem; height:30px; width: 100%; max-width: 100%;">
                                    <option value="">-- 선택 --</option>
                                    <option value="미래기업" <?= ($division == "미래기업") ? "selected" : "" ?>>미래기업</option>
                                    <option value="협력사" <?= ($division == "협력사") ? "selected" : "" ?>>협력사</option>
                                    <option value="작업소장" <?= ($division == "작업소장") ? "selected" : "" ?>>작업소장</option>
                                    <option value="포미스톤" <?= ($division == "포미스톤") ? "selected" : "" ?>>포미스톤</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="* 성명">* 성명</td>
                            <td data-label="성명 입력">
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                            <td data-label="* ID">* ID</td>
                            <td data-label="ID 입력">
                                <input type="text" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                        </tr>
                        <tr>
                            <td data-label="* Password">* Password</td>
                            <td data-label="Password 입력">
                                <input type="text" id="pass" name="pass" value="<?= htmlspecialchars($pass, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                            <td data-label="연락처 (HP)">연락처 (HP)</td>
                            <td data-label="연락처 입력">
                                <input type="text" id="hp" name="hp" value="<?= htmlspecialchars($hp, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                        </tr>
                        <tr>
                            <td data-label="* 레벨">* 레벨<br><span class="text-muted" style="font-size: 0.8rem;">- 포미스톤은 20으로 설정</span></td>
                            <td class="align-middle" data-label="레벨 입력">
                                <input type="text" id="level" name="level" value="<?= htmlspecialchars($level, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                            <td class="align-middle" data-label="파트">파트</td>
                            <td class="align-middle" data-label="파트 입력">
                                <input type="text" id="part" name="part" value="<?= htmlspecialchars($part, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                        </tr>
                        <tr>
                            <td data-label="번호순서 (Numorder)">번호순서 (Numorder)</td>
                            <td data-label="번호순서 입력">
                                <input type="text" id="numorder" name="numorder" value="<?= htmlspecialchars($numorder, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                            <td data-label="직위 (Position)">직위 (Position)</td>
                            <td data-label="직위 입력">
                                <input type="text" id="position" name="position" value="<?= htmlspecialchars($position, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                        </tr>
                        <tr>
                            <td data-label="전자결재 레벨 (eworks_level)">전자결재 레벨 (eworks_level)</td>
                            <td colspan="1" data-label="전자결재 레벨 입력">
                                <input type="text" id="eworks_level" name="eworks_level" value="<?= htmlspecialchars($eworks_level, ENT_QUOTES, 'UTF-8') ?>" class="form-control text-center">
                            </td>
                            <td colspan="2" data-label=""></td>
                        </tr>
                    </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4 mb-2">
                        <?php if ($user_name === '김보곤'): ?>
                            <button id="saveBtn" class="btn btn-dark btn-sm me-2" type="button">
                                <i class="bi bi-floppy-fill"></i> 저장
                            </button>
                            <button id="delBtn" class="btn btn-danger btn-sm" type="button">
                                <i class="bi bi-trash"></i> 삭제
                            </button>
                            <button class="btn btn-outline-secondary btn-sm me-2 ms-5" type="button" onclick="self.close();">
                                <i class="bi bi-x-lg"></i> 닫기
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script type="text/javascript">
(function() {
    'use strict';
    
    var ajaxRequest = null;
    
    $(document).ready(function() {
        var state = $('#state').val();
        
        // 모달 닫기
        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });
        
        $("#closeBtn").click(function() {
            // 저장하고 창닫기
        });
        
        // 저장 버튼
        $("#saveBtn").click(function() {
            var id = $("#id").val();
            var part = $("#part").val();
            
            var form = $('#board_form')[0];
            var datasource = new FormData(form);
            
            if (ajaxRequest !== null) {
                ajaxRequest.abort();
            }
            
            ajaxRequest = $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "insert.php",
                type: "post",
                data: datasource,
                dataType: "json",
                success: function(data) {
                    if (typeof Toastify !== 'undefined') {
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
                    } else {
                        alert('저장되었습니다.');
                    }
                    
                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            window.opener.location.reload();
                            window.close();
                        }
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                    alert('저장 중 오류가 발생했습니다.');
                }
            });
        });
        
        // 삭제 버튼
        $("#delBtn").click(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '삭제하시겠습니까?',
                    text: "삭제하면 되돌릴 수 없습니다.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '삭제',
                    cancelButtonText: '취소'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $("#mode").val('delete');
                        
                        $.ajax({
                            url: "insert.php",
                            type: "post",
                            data: $("#board_form").serialize(),
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                Swal.fire({
                                    title: '삭제 완료',
                                    text: '데이터가 성공적으로 삭제되었습니다.',
                                    icon: 'success',
                                    confirmButtonText: '확인'
                                }).then(function() {
                                    if (window.opener && !window.opener.closed) {
                                        window.opener.location.reload();
                                    }
                                    window.close();
                                });
                            },
                            error: function(jqxhr, status, error) {
                                console.log(jqxhr, status, error);
                                Swal.fire({
                                    title: '오류 발생',
                                    text: '삭제 중 오류가 발생했습니다.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            } else {
                if (confirm('삭제하시겠습니까?\n삭제하면 되돌릴 수 없습니다.')) {
                    $("#mode").val('delete');
                    
                    $.ajax({
                        url: "insert.php",
                        type: "post",
                        data: $("#board_form").serialize(),
                        dataType: "json",
                        success: function(data) {
                            alert('삭제되었습니다.');
                            if (window.opener && !window.opener.closed) {
                                window.opener.location.reload();
                            }
                            window.close();
                        },
                        error: function(jqxhr, status, error) {
                            console.log(jqxhr, status, error);
                            alert('삭제 중 오류가 발생했습니다.');
                        }
                    });
                }
            }
        });
    });
    
    // 두 날짜 사이 일자 구하기
    window.getDateDiff = function(d1, d2) {
        var date1 = new Date(d1);
        var date2 = new Date(d2);
        var diffDate = date1.getTime() - date2.getTime();
        return Math.abs(diffDate / (1000 * 60 * 60 * 24));
    };
    
    window.updateCheck = function() {
        var isChecked = document.getElementById('check') ? document.getElementById('check').checked : false;
        document.cookie = "check=" + isChecked + ";path=/";
        
        var askdatefrom = $("#askdatefrom");
        if (askdatefrom.length) {
            document.cookie = "lastdate=" + askdatefrom.val() + ";path=/";
        }
    };
    
})();
</script>
</body>
</html>
