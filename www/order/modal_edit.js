/**
 * 모달 내 인라인 수정 기능
 */

// 전역 변수
var isEditMode = false;
var originalOrderData = null;

/**
 * 수정 모드 전환
 */
function toggleEditMode() {
    isEditMode = !isEditMode;

    var modal EditBtn = document.getElementById('modalEditBtn');
    var modalSaveBtn = document.getElementById('modalSaveBtn');
    var modalCancelBtn = document.getElementById('modalCancelBtn');

    if (isEditMode) {
        // 수정 모드로 전환
        modalEditBtn.style.display = 'none';
        modalSaveBtn.style.display = 'inline-flex';
        modalCancelBtn.style.display = 'inline-flex';

        // 입력 필드로 변환
        convertToEditMode();
    } else {
        // 보기 모드로 전환
        modalEditBtn.style.display = 'inline-flex';
        modalSaveBtn.style.display = 'none';
        modalCancelBtn.style.display = 'none';

        // 원래 데이터로 복원
        if (originalOrderData) {
            displayOrderDetail(originalOrderData);
        }
    }
}

/**
 * 보기 모드를 수정 모드로 전환
 */
function convertToEditMode() {
    // 기본 정보 섹션 찾기
    var detailItems = document.querySelectorAll('.detail-item');

    detailItems.forEach(function(item) {
        var label = item.querySelector('.detail-label');
        var value = item.querySelector('.detail-value');

        if (!label || !value) return;

        var labelText = label.textContent.trim();
        var valueText = value.textContent.trim();

        // 수정 가능한 필드만 변환
        if (labelText === '발행일') {
            value.innerHTML = '<input type="date" class="form-control form-control-sm edit-issue-date" value="' + valueText + '" style="width: 100%; padding: 5px; border: 1px solid #2196f3; border-radius: 4px;">';
        } else if (labelText === '공급업체') {
            value.innerHTML = '<input type="text" class="form-control form-control-sm edit-supplier-name" value="' + valueText + '" style="width: 100%; padding: 5px; border: 1px solid #2196f3; border-radius: 4px;">';
        } else if (labelText === '납기일자' && valueText !== '-') {
            value.innerHTML = '<input type="date" class="form-control form-control-sm edit-delivery-date" value="' + valueText + '" style="width: 100%; padding: 5px; border: 1px solid #2196f3; border-radius: 4px;">';
        } else if (labelText === '상태') {
            var currentStatus = originalOrderData.status || 'draft';
            value.innerHTML = '<select class="form-control form-control-sm edit-status" style="width: 100%; padding: 5px; border: 1px solid #2196f3; border-radius: 4px;">' +
                '<option value="draft"' + (currentStatus === 'draft' ? ' selected' : '') + '>임시저장</option>' +
                '<option value="sent"' + (currentStatus === 'sent' ? ' selected' : '') + '>발송완료</option>' +
                '<option value="completed"' + (currentStatus === 'completed' ? ' selected' : '') + '>완료</option>' +
                '</select>';
        }
    });

    // 비고 섹션 찾기
    var detailSections = document.querySelectorAll('.detail-section');
    detailSections.forEach(function(section) {
        var title = section.querySelector('.detail-section-title');
        if (title && title.textContent.includes('비고')) {
            var valueDiv = section.querySelector('.detail-value');
            if (valueDiv) {
                var noteText = valueDiv.textContent.trim();
                if (noteText === '-') noteText = '';
                valueDiv.innerHTML = '<textarea class="form-control form-control-sm edit-note" rows="3" style="width: 100%; padding: 8px; border: 1px solid #2196f3; border-radius: 4px;">' + noteText + '</textarea>';
            }
        }
    });
}

/**
 * 수정사항 저장
 */
function saveOrderChanges() {
    if (!currentOrderId) {
        alert('발주서 ID를 찾을 수 없습니다.');
        return;
    }

    // 입력 값 수집
    var issueDateInput = document.querySelector('.edit-issue-date');
    var supplierNameInput = document.querySelector('.edit-supplier-name');
    var deliveryDateInput = document.querySelector('.edit-delivery-date');
    var statusInput = document.querySelector('.edit-status');
    var noteInput = document.querySelector('.edit-note');

    var updateData = {
        id: currentOrderId
    };

    if (issueDateInput) updateData.issue_date = issueDateInput.value;
    if (supplierNameInput) updateData.supplier_name = supplierNameInput.value;
    if (deliveryDateInput) updateData.delivery_date = deliveryDateInput.value;
    if (statusInput) updateData.status = statusInput.value;
    if (noteInput) updateData.note = noteInput.value;

    // 저장 버튼 비활성화
    var saveBtn = document.getElementById('modalSaveBtn');
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 저장 중...';
    }

    // AJAX 요청
    fetch('update_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(updateData)
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            alert('발주서가 성공적으로 수정되었습니다.');

            // 수정 모드 종료
            isEditMode = false;

            // 버튼 복원
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save"></i> 저장';
            }

            // 데이터 다시 로드
            loadOrderDetail(currentOrderId);

            // 목록 새로고침 (부모 페이지)
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            alert('수정 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));

            // 버튼 복원
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save"></i> 저장';
            }
        }
    })
    .catch(function(error) {
        console.error('수정 오류:', error);
        alert('수정 중 오류가 발생했습니다: ' + error.message);

        // 버튼 복원
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i> 저장';
        }
    });
}

/**
 * 수정 취소
 */
function cancelEdit() {
    if (confirm('수정을 취소하시겠습니까? 변경사항이 저장되지 않습니다.')) {
        toggleEditMode();
    }
}
