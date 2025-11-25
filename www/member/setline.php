<?php
/**
 * 결재라인 지정 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . "/session.php";

// 세션 변수
$user_id = $_SESSION["userid"] ?? '';

// 요청 변수 초기화
$e_line_id = isset($_GET['e_line_id']) ? $_GET['e_line_id'] : '';
$SelectWork = isset($_REQUEST['SelectWork']) ? $_REQUEST['SelectWork'] : '';
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : '';
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$partsep = isset($_REQUEST['partsep']) ? $_REQUEST['partsep'] : '';
$workprocessval = isset($_REQUEST['workprocessval']) ? $_REQUEST['workprocessval'] : '';

require_once(includePath('lib/mydb.php'));

$_SESSION["partsep"] = '';
$pdo = db_connect();

$firstStep = array();
$secondStep = array();

// 결재 데이터 조회 함수
function getApprovalData($pdo) {
    $approvalData = ['firstStep' => [], 'secondStep' => []];
    
    try {
        $sql = "SELECT id, name, position, part, eworks_level 
                FROM mirae8440.member 
                WHERE part LIKE ? OR part LIKE ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, '%제조%', PDO::PARAM_STR);
        $stmh->bindValue(2, '%지원%', PDO::PARAM_STR);
        $stmh->execute();
        
        while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            if ($row["eworks_level"] == "2" || $row["eworks_level"] == "1") {
                $approvalData['firstStep'][] = $row;
            } elseif ($row["eworks_level"] == "1") {
                $approvalData['secondStep'][] = $row;
            }
        }
    } catch (PDOException $ex) {
        error_log("결재 데이터 조회 오류: " . $ex->getMessage());
        echo "오류: 결재 데이터를 불러오는 중 문제가 발생했습니다.";
    }
    
    return $approvalData;
}

$approvalData = getApprovalData($pdo);

$title_message = "결재라인 지정";

include getDocumentRoot() . '/load_header.php';

// JSON 파일 경로
$filePath = './Company_approvalLine_.json';
$selectOptions = "";

if (file_exists($filePath)) {
    $fileContent = file_get_contents($filePath);
    $data = json_decode($fileContent, true);
    
    if (is_array($data)) {
        foreach ($data as $approvalLine) {
            if (isset($approvalLine['savedName'])) {
                $savedName = htmlspecialchars($approvalLine['savedName'], ENT_QUOTES, 'UTF-8');
                $selectOptions .= "<option value='{$savedName}'>{$savedName}</option>";
            }
        }
        
        if (empty($selectOptions)) {
            $selectOptions = "<option></option>";
        }
    } else {
        $selectOptions = "<option>Invalid data format in file</option>";
        error_log("JSON 파일 형식 오류: {$filePath}");
    }
} else {
    $selectOptions = "<option></option>";
}
?>

<style>
    .ui-state-highlight {
        background-color: #f0f0f0;
        height: 1.8em;
        line-height: 1.5em;
    }
    
    #approvalOrder {
        min-height: 100px;
    }
    
    #approvalModal {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: none;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .modal-body {
        padding: 15px 0;
    }
    
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    
    .approval-line-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .delete-button {
        border: none;
        background: none;
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

	.card-header {
		font-size: 1.1rem !important;
		padding: 0.75rem 0.5rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
	}

	.card-body {
		padding: 0.75rem 0.5rem !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		overflow-x: hidden !important;
	}

	/* 테이블 모바일 최적화 - 카드 레이아웃 */
	.table-responsive {
		overflow-x: visible !important;
	}

	.table,
	.table thead,
	.table tbody,
	.table tr,
	.table td,
	.table th {
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
	.table td,
	.table th {
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
	.table td[data-label]:not([data-label=""]),
	.table th[data-label]:not([data-label=""]) {
		font-size: 0 !important;
		line-height: 0 !important;
	}

	.table td[data-label]:not([data-label=""]) *,
	.table th[data-label]:not([data-label=""]) * {
		font-size: 0.95rem !important;
		line-height: 1.6 !important;
	}

	/* 라벨 표시 */
	.table td:before,
	.table th:before {
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

	.table td:after,
	.table th:after {
		display: none !important;
	}

	/* 리스트 그룹 모바일 최적화 */
	.list-group {
		width: 100% !important;
		max-width: 100% !important;
	}

	.list-group-item {
		padding: 0.75rem !important;
		font-size: 0.95rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		white-space: normal !important;
	}

	/* 입력 필드 모바일 최적화 */
	.form-control,
	.form-select {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
		font-size: 0.95rem !important;
		padding: 0.6rem !important;
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
	.d-flex {
		flex-wrap: wrap !important;
		gap: 0.5rem !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin: 0 !important;
		padding: 0 !important;
	}

	.col-sm-7,
	.col-sm-5 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		padding: 0.5rem !important;
		margin-bottom: 0.5rem !important;
	}

	/* 모달 모바일 최적화 */
	#approvalModal {
		position: fixed !important;
		left: 0 !important;
		top: 0 !important;
		width: 100vw !important;
		height: 100vh !important;
		z-index: 99999 !important;
		display: none !important;
		overflow-y: auto !important;
		-webkit-overflow-scrolling: touch !important;
		background-color: rgba(0, 0, 0, 0.6) !important;
	}

	#approvalModal[style*="block"] {
		display: block !important;
	}

	.modal-content {
		width: 95% !important;
		max-width: 95% !important;
		margin: 2% auto !important;
		padding: 1rem !important;
		border-radius: 12px !important;
		max-height: 96vh !important;
		overflow-y: auto !important;
		position: relative !important;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
		-webkit-overflow-scrolling: touch !important;
	}

	.modal-header {
		padding: 0.75rem 0 !important;
		margin-bottom: 1rem !important;
		display: flex !important;
		justify-content: space-between !important;
		align-items: center !important;
		flex-wrap: wrap !important;
	}

	.modal-title {
		font-size: 1.1rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		margin: 0 !important;
		flex: 1 !important;
	}

	.modal-body {
		padding: 0.5rem 0 !important;
		font-size: 0.95rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		max-height: calc(96vh - 150px) !important;
		overflow-y: auto !important;
		-webkit-overflow-scrolling: touch !important;
	}

	.modal-footer {
		padding: 0.75rem 0 !important;
		margin-top: 1rem !important;
		display: flex !important;
		justify-content: flex-end !important;
		gap: 0.5rem !important;
		flex-wrap: wrap !important;
	}

	.modal-body table {
		width: 100% !important;
		max-width: 100% !important;
		font-size: 0.9rem !important;
		margin: 0 !important;
	}

	.modal-body table thead {
		display: table-header-group !important;
	}

	.modal-body table tbody {
		display: table-row-group !important;
	}

	.modal-body table tr {
		display: table-row !important;
	}

	.modal-body table td,
	.modal-body table th {
		display: table-cell !important;
		padding: 0.5rem !important;
		word-wrap: break-word !important;
		overflow-wrap: break-word !important;
		white-space: normal !important;
		text-align: left !important;
	}

	.modal-body .table td:before,
	.modal-body .table th:before {
		display: none !important;
	}

	.close {
		font-size: 2rem !important;
		line-height: 1 !important;
		padding: 0 !important;
		margin: 0 !important;
		width: 30px !important;
		height: 30px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		cursor: pointer !important;
		background: transparent !important;
		border: none !important;
	}

	/* 모든 텍스트와 버튼이 카드 내부에 머물도록 */
	.card *,
	.container *,
	.container-fluid *,
	.row *,
	.col-sm-* {
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
}
</style>

<title><?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <form id="mainFrm" method="post" enctype="multipart/form-data">
        <input type="hidden" id="SelectWork" name="SelectWork" value="<?= htmlspecialchars($SelectWork, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="vacancy" name="vacancy">
        <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="partsep" name="partsep" value="<?= htmlspecialchars($partsep, ENT_QUOTES, 'UTF-8') ?>">
        
        <div class="container">
            <!-- 모달 -->
            <div id="approvalModal" style="display:none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">결재라인 관리</h5>
                        <button type="button" class="close" onclick="closeModal();">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex mt-5 mb-5">
                            <ul id="approvalLineList"></ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" onclick="closeModal();">닫기</button>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header mt-3 fs-5">
                    결재라인 지정
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th data-label=""><h6>결재권자 목록</h6></th>
                                <th data-label=""><h6>결재 순서</h6></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col" style="width:50%;" data-label="결재권자 목록">
                                    <ul id="approverList" class="list-group">
                                        <?php foreach ($approvalData["firstStep"] as $approver): ?>
                                            <li class="list-group-item" data-user-id="<?= htmlspecialchars($approver['id'], ENT_QUOTES, 'UTF-8') ?>">
                                                <?= htmlspecialchars($approver['name'] . ' ' . $approver['position'], ENT_QUOTES, 'UTF-8') ?>
                                            </li>
                                        <?php endforeach; ?>
                                        <li class="list-group-item dummy" style="display: none;"></li>
                                    </ul>
                                </td>
                                <td class="col" style="width:50%;" data-label="결재 순서">
                                    <ul id="approvalOrder" class="list-group">
                                        <!-- 드래그 앤 드롭으로 이동된 결재권자가 여기에 표시됨 -->
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center p-2 text-left">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="newBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>&nbsp;
                                <span class="text-center me-1">Load</span>
                                <select name="savedApprovalLines" id="savedApprovalLines" class="form-select form-select-sm w-auto text-center ms-1">
                                    <?= $selectOptions ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="d-flex align-items-center p-2 text-left">
                                <input type="text" name="workprocessval" id="workprocessval" 
                                       value="<?= htmlspecialchars($workprocessval, ENT_QUOTES, 'UTF-8') ?>" 
                                       class="form-control" style="width:100%;">
                                <button type="button" class="btn btn-dark btn-sm" id="SavesettingsBtn">
                                    <i class="bi bi-search"></i>
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex p-2 text-left">
                        <button type="button" class="btn btn-outline-dark btn-sm" onclick="self.close();">&times; 닫기</button>&nbsp;
                        <button type="button" class="btn btn-primary btn-sm" id="openModalButton">
                            <i class="bi bi-tools"></i> 관리
                        </button>&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>

<script type="text/javascript">
(function() {
    'use strict';
    
    var e_line_id = <?= json_encode($e_line_id, JSON_UNESCAPED_UNICODE) ?>;
    var user_id = <?= json_encode($user_id, JSON_UNESCAPED_UNICODE) ?>;
    
    // 기존 호출에 전달자가 있는 경우
    document.addEventListener('DOMContentLoaded', function() {
        if (e_line_id && e_line_id !== "") {
            var ids = e_line_id.split("!");
            var approverList = document.getElementById("approverList");
            var approvalOrder = document.getElementById("approvalOrder");
            
            ids.forEach(function(id) {
                var element = approverList.querySelector('[data-user-id="' + id + '"]');
                if (element) {
                    approvalOrder.appendChild(element.cloneNode(true));
                    element.remove();
                }
            });
        }
    });
    
    $(document).ready(function() {
        // Select 옵션 변경 시 이벤트 핸들러
        $('#savedApprovalLines').change(function() {
            var selectedName = $(this).val();
            updateApprovalLine(selectedName);
            console.log(selectedName);
        });
        
        // 페이지 로드 시 첫 번째 옵션 선택
        if (!e_line_id) {
            $('#savedApprovalLines').prop('selectedIndex', 0).trigger('change');
        }
        
        function updateApprovalLine(savedName) {
            $.ajax({
                url: './getApprovalLine.php',
                type: 'POST',
                data: { savedName: savedName },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    updateApprovalOrderList(response.approvalOrder);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        
        function updateApprovalOrderList(approvalOrder) {
            var approvalOrderList = $('#approvalOrder');
            var approverList = $('#approverList');
            approvalOrderList.empty();
            
            approvalOrder.forEach(function(item) {
                var listItem = $('<li class="list-group-item"></li>')
                    .text(item.name)
                    .data('user-id', item['user-id']);
                approvalOrderList.append(listItem);
                
                approverList.find('li').not('.dummy').each(function() {
                    if ($(this).data('user-id') === item['user-id']) {
                        $(this).remove();
                    }
                });
            });
            
            if (!approverList.find('.dummy').length) {
                approverList.append('<li class="list-group-item dummy"></li>');
            }
        }
        
        // 드래그 앤 드롭
        $("#approverList, #approvalOrder").sortable({
            connectWith: ".list-group",
            placeholder: "ui-state-highlight",
            receive: function(event, ui) {
                checkDummy($(this));
            },
            over: function(event, ui) {
                checkDummy($(this));
            },
            out: function(event, ui) {
                checkDummy($(this));
            },
            stop: function(event, ui) {
                checkDummy($(this));
            }
        }).disableSelection();
        
        function checkDummy(list) {
            if (list.children(':not(.dummy)').length === 0) {
                list.children('.dummy').show();
            } else {
                list.children('.dummy').hide();
            }
        }
        
        $("#approverList, #approvalOrder").each(function() {
            checkDummy($(this));
        });
        
        // 모달창 닫기
        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });
        
        $("#closeBtn").click(function() {
            // 저장하고 창닫기
        });
        
        // 저장 버튼
        $("#SavesettingsBtn").click(function() {
            var inputName = $("#workprocessval").val().trim();
            if (!inputName) {
                alert('저장할 결재라인을 입력하세요');
                return;
            }
            
            var selectedApprovalLine = getCurrentApprovalLine();
            
            $.ajax({
                url: './saveApprovalLine.php',
                type: 'POST',
                data: JSON.stringify({
                    userId: user_id,
                    savedName: inputName,
                    approvalOrder: selectedApprovalLine
                }),
                contentType: "application/json; charset=utf-8",
                success: function(response) {
                    console.log(response);
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: "저장되었습니다.",
                            duration: 2000,
                            close: true,
                            gravity: "top",
                            position: 'center'
                        }).showToast();
                    } else {
                        alert('저장되었습니다.');
                    }
                    
                    $('#savedApprovalLines').append($('<option>', {
                        value: inputName,
                        text: inputName
                    })).val(inputName).trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('저장 중 오류가 발생했습니다.');
                }
            });
        });
        
        $("#refreshBtn").click(function() {
            location.reload();
        });
        
        // New 버튼
        $("#newBtn").click(function() {
            resetApprovalLists();
        });
        
        // 모달 열기
        $("#openModalButton").click(function() {
            fetchApprovalLines();
            openModal();
        });
        
        // 선택 버튼
        $("#adaptBtn").click(function() {
            var approvalOrderTexts = $("#approvalOrder li")
                .map(function() {
                    return $(this).text().trim();
                }).get()
                .join("!");
            
            var approvalOrderIDs = $("#approvalOrder li")
                .map(function() {
                    return $(this).data('user-id');
                }).get()
                .join("!");
            
            if (window.opener && !window.opener.closed) {
                if (window.opener.$("#e_line").length) {
                    window.opener.$("#e_line").val(approvalOrderTexts);
                }
                if (window.opener.$("#e_line_id").length) {
                    window.opener.$("#e_line_id").val(approvalOrderIDs);
                }
            }
            
            window.close();
        });
    });
    
    function getCurrentApprovalLine() {
        var approvalLine = [];
        $("#approvalOrder li:not(.dummy)").each(function(index) {
            var userId = $(this).data('user-id');
            var name = $(this).text();
            var position = $(this).data('position');
            var order = index + 1;
            if (userId) {
                approvalLine.push({
                    'order': order,
                    'user-id': userId,
                    'name': name + (position ? ' ' + position : '')
                });
            }
        });
        return approvalLine;
    }
    
    function resetApprovalLists() {
        var approverList = $('#approverList');
        var approvalOrderList = $('#approvalOrder');
        
        approverList.empty();
        <?php foreach ($approvalData["firstStep"] as $approver): ?>
            approverList.append('<li class="list-group-item" data-user-id="<?= htmlspecialchars($approver['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($approver['name'] . ' ' . $approver['position'], ENT_QUOTES, 'UTF-8') ?></li>');
        <?php endforeach; ?>
        approverList.append('<li class="list-group-item dummy" style="display: none;"></li>');
        
        approvalOrderList.empty();
    }
    
    // 서버에서 결재라인 목록 가져오기
    window.fetchApprovalLines = function() {
        $.ajax({
            url: './getApprovalLines.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                renderApprovalLines(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    };
    
    // 결재라인 목록 렌더링
    window.renderApprovalLines = function(data) {
        var approvalLines = Object.values(data);
        
        if (!Array.isArray(approvalLines)) {
            console.error("Invalid data type: ", approvalLines);
            return;
        }
        
        var listContainer = $('#approvalLineList');
        listContainer.empty();
        
        var table = $('<table><tbody></tbody></table>').addClass('table table-hover table-bordered table-sm');
        listContainer.append(table);
        
        approvalLines.forEach(function(line) {
            var row = $('<tr></tr>').addClass('approval-line-item');
            var nameCell = $('<td></td>').text(line.savedName);
            var deleteButtonCell = $('<td></td>').addClass('text-end');
            var deleteButton = $('<button></button>')
                .addClass('btn btn-danger btn-sm')
                .append('<i class="bi bi-trash"></i>')
                .click(function(event) {
                    event.preventDefault();
                    deleteApprovalLine(line.savedName, row);
                });
            
            deleteButtonCell.append(deleteButton);
            row.append(nameCell).append(deleteButtonCell);
            table.append(row);
        });
    };
    
    // 결재라인 삭제
    window.deleteApprovalLine = function(savedName, listItem) {
        $.ajax({
            url: './deleteApprovalLine.php',
            type: 'POST',
            data: { savedName: savedName },
            success: function(response) {
                listItem.remove();
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "삭제되었습니다.",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: 'center'
                    }).showToast();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    };
    
    window.openModal = function() {
        var modal = document.getElementById("approvalModal");
        if (modal) {
            modal.style.display = "block";
            // 모바일에서 body 스크롤 방지
            document.body.style.overflow = "hidden";
            document.body.style.position = "fixed";
            document.body.style.width = "100%";
        }
    };
    
    window.closeModal = function() {
        var modal = document.getElementById("approvalModal");
        if (modal) {
            modal.style.display = "none";
            // 모바일에서 body 스크롤 복원
            document.body.style.overflow = "";
            document.body.style.position = "";
            document.body.style.width = "";
        }
        location.reload();
    };
    
    // 모달 배경 클릭 시 닫기
    $(document).on('click', '#approvalModal', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // ESC 키로 모달 닫기
    $(document).on('keydown', function(e) {
        if (e.key === "Escape" || e.keyCode === 27) {
            var modal = document.getElementById("approvalModal");
            if (modal && modal.style.display === "block") {
                closeModal();
            }
        }
    });
    
})();
</script>
