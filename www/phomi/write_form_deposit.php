<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';

$pdo = db_connect();

$mode = $_REQUEST['mode'] ?? 'insert';
$num = $_REQUEST['num'] ?? '';
$tablename = $_REQUEST['tablename'] ?? 'phomi_deposit';

// 수정 모드일 때 기존 데이터 조회
$deposit_data = null;
if ($mode == 'view' || !empty($num)) {
    $sql = "SELECT * FROM {$DB}.phomi_deposit WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();
    $deposit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$title_message = $mode == 'view' ? '본사 예치금 상세보기' : '본사 예치금 등록';
?>

<title><?= $title_message ?></title>
    
    <style>
        .form-label {
            font-weight: bold;
        }
        .required {
            color: red;
        }
        .amount-input {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?= $title_message ?></h5>
                    <button type="button" class="btn-close" onclick="window.close()"></button>
                </div>
            </div>
            <div class="card-body">
                <form id="depositForm" method="post">
                     <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
                     <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
                     <input type="hidden" id="num" name="num" value="<?= $num ?>">
                     <input type="hidden" id="current_num" value="<?= $num ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="deposit_date" class="form-label">
                                    입금일 <span class="required">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="deposit_date" 
                                       name="deposit_date" 
                                       value="<?= $deposit_data['deposit_date'] ?? date('Y-m-d') ?>" 
                                       required
                                       <?= $mode == 'view' ? 'readonly' : '' ?>>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="deposit_amount" class="form-label">
                                    입금액 <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control amount-input" 
                                           id="deposit_amount" 
                                           name="deposit_amount" 
                                           value="<?= $deposit_data ? number_format($deposit_data['deposit_amount']) : '' ?>" 
                                           placeholder="0"
                                           required
                                           <?= $mode == 'view' ? 'readonly' : '' ?>>
                                    <span class="input-group-text">원</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">비고</label>
                        <textarea class="form-control" 
                                  id="note" 
                                  name="note" 
                                  rows="3" 
                                  placeholder="비고사항을 입력하세요"
                                  <?= $mode == 'view' ? 'readonly' : '' ?>><?= $deposit_data['note'] ?? '' ?></textarea>
                    </div>
                    
                    <?php if ($mode !== 'insert'): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">등록일</label>
                                    <input type="text" class="form-control" value="<?= $deposit_data ? date('Y-m-d H:i:s', strtotime($deposit_data['createdAt'])) : '' ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">수정일</label>
                                    <input type="text" class="form-control" value="<?= $deposit_data ? date('Y-m-d H:i:s', strtotime($deposit_data['updatedAt'])) : '' ?>" readonly>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-end gap-2">
                         <?php if ($mode == 'view'): ?>
                             <button type="button" class="btn btn-primary" onclick="editMode()">수정</button>
                             <button type="button" class="btn btn-danger" onclick="deleteDeposit()">삭제</button>
                             <button type="button" class="btn btn-secondary" onclick="window.close()">닫기</button>
                         <?php else: ?>
                             <button type="button" class="btn btn-primary" onclick="saveDeposit()">저장</button>
                             <button type="button" class="btn btn-secondary" onclick="window.close()">취소</button>
                         <?php endif; ?>
                     </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // 금액 입력 시 자동 콤마 추가
            $('#deposit_amount').on('input', function() {
                let value = $(this).val().replace(/[^\d]/g, '');
                if (value) {
                    $(this).val(parseInt(value).toLocaleString());
                }
            });
        });
        
        function saveDeposit() {
             // 클라이언트 측 유효성 검사
             // e.preventDefault();
             let depositDate = $('#deposit_date').val();
             let depositAmount = $('#deposit_amount').val().replace(/,/g, '');
             let note = $('#note').val();
             
             // 입금일 검사
             if (!depositDate) {
                 Swal.fire({
                     icon: 'error',
                     title: '입력 오류',
                     text: '입금일을 입력해주세요.',
                     confirmButtonText: '확인'
                 });
                 $('#deposit_date').focus();
                 return;
             }
             
             // 입금액 검사
             if (!depositAmount || depositAmount <= 0) {
                 Swal.fire({
                     icon: 'error',
                     title: '입력 오류',
                     text: '입금액을 입력해주세요.',
                     confirmButtonText: '확인'
                 });
                 $('#deposit_amount').focus();
                 return;
             }
             
             // 숫자만 입력되었는지 확인
             if (!/^\d+$/.test(depositAmount)) {
                 Swal.fire({
                     icon: 'error',
                     title: '입력 오류',
                     text: '입금액은 숫자만 입력 가능합니다.',
                     confirmButtonText: '확인'
                 });
                 $('#deposit_amount').focus();
                 return;
             }
             
             let formData = new FormData($('#depositForm')[0]);
             // 입금액 값을 콤마가 제거된 값으로 업데이트
             formData.set('deposit_amount', depositAmount);
             console.log('num', $('#num').val());
             console.log('mode', $('#mode').val());
            
            $.ajax({
                url: 'process_deposit.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('서버 응답:', response);
                    
                    // response가 이미 객체인 경우
                    let result;
                    if (typeof response === 'object') {
                        result = response;
                    } else {
                        // response가 문자열인 경우 JSON 파싱
                        try {
                            result = JSON.parse(response);
                                                 } catch (e) {
                             console.error('JSON 파싱 오류:', e);
                             console.error('서버 응답:', response);
                             Swal.fire({
                                 icon: 'error',
                                 title: '응답 처리 오류',
                                 text: '서버 응답을 처리할 수 없습니다.\n응답: ' + String(response).substring(0, 200),
                                 confirmButtonText: '확인'
                             });
                             return;
                         }
                    }
                    
                    if (result.success) {
                         Swal.fire({
                             icon: 'success',
                             title: '성공',
                             text: result.message,
                             confirmButtonText: '확인'
                         }).then((result) => {
                             if (window.opener && !window.opener.closed) {
                                 window.opener.location.reload();
                             }
                             window.close();
                         });
                     } else {
                         let errorMsg = result.message || '알 수 없는 오류가 발생했습니다.';
                         if (result.errors) {
                             errorMsg += '\n\n상세 오류:\n';
                             errorMsg += Object.entries(result.errors)
                                 .map(([field, msg]) => `${field}: ${msg}`)
                                 .join('\n');
                         }
                         Swal.fire({
                             icon: 'error',
                             title: '오류 발생',
                             text: errorMsg,
                             confirmButtonText: '확인'
                         });
                     }
                },
                error: function(xhr, status, error) {
                    let errorMsg = '서버 통신 중 오류가 발생했습니다.\n';
                    errorMsg += '상태 코드: ' + xhr.status + '\n';
                    errorMsg += '오류 메시지: ' + error + '\n';
                    
                    if (xhr.responseText) {
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg += '서버 메시지: ' + response.message;
                            }
                        } catch (e) {
                            errorMsg += '서버 응답: ' + xhr.responseText.substring(0, 100);
                        }
                    }
                    
                        console.error('Ajax 오류:', {
                         status: status,
                         error: error,
                         response: xhr.responseText
                     });
                     
                     Swal.fire({
                         icon: 'error',
                         title: '서버 통신 오류',
                         text: errorMsg,
                         confirmButtonText: '확인'
                     });
                }
            });
        }
        function editMode() {
             // 수정모드로 location.href 전달
             var url = "write_form_deposit.php?mode=modify&num=" + $('#current_num').val() + "&tablename=" + $('#tablename').val();
             location.href = url;
         }
        
        function deleteDeposit() {
             Swal.fire({
                 title: '삭제 확인',
                 text: '정말 삭제하시겠습니까?',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#d33',
                 cancelButtonColor: '#3085d6',
                 confirmButtonText: '삭제',
                 cancelButtonText: '취소'
             }).then((result) => {
                 if (result.isConfirmed) {
                     let formData = new FormData();
                     formData.append('mode', 'delete');
                     formData.append('num', $('#current_num').val());
                     
                     $.ajax({
                         url: 'process_deposit.php',
                         type: 'POST',
                         data: formData,
                         processData: false,
                         contentType: false,
                         success: function(response) {
                             console.log('삭제 응답:', response);
                             
                             // response가 이미 객체인 경우
                             let result;
                             if (typeof response === 'object') {
                                 result = response;
                             } else {
                                 // response가 문자열인 경우 JSON 파싱
                                 try {
                                     result = JSON.parse(response);
                                 } catch (e) {
                                     console.error('JSON 파싱 오류:', e);
                                     console.error('서버 응답:', response);
                                     Swal.fire({
                                         icon: 'error',
                                         title: '응답 처리 오류',
                                         text: '서버 응답을 처리할 수 없습니다.',
                                         confirmButtonText: '확인'
                                     });
                                     return;
                                 }
                             }
                             
                             if (result.success) {
                                 Swal.fire({
                                     icon: 'success',
                                     title: '성공',
                                     text: result.message,
                                     confirmButtonText: '확인'
                                 }).then((result) => {
                                     if (window.opener && !window.opener.closed) {
                                         window.opener.location.reload();
                                     }
                                     window.close();
                                 });
                             } else {
                                 Swal.fire({
                                     icon: 'error',
                                     title: '오류',
                                     text: '오류: ' + result.message,
                                     confirmButtonText: '확인'
                                 });
                             }
                         },
                         error: function(xhr, status, error) {
                             console.error('삭제 Ajax 오류:', {
                                 status: status,
                                 error: error,
                                 response: xhr.responseText
                             });
                             
                             Swal.fire({
                                 icon: 'error',
                                 title: '서버 통신 오류',
                                 text: '서버 통신 중 오류가 발생했습니다.',
                                 confirmButtonText: '확인'
                             });
                         }
                     });
                 }
             });
         }
    </script>
</body>
</html> 