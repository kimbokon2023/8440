# 도움말 모달 구현 가이드 (Help Modal Implementation Guide)

이 문서는 웹사이트 내의 어느 페이지에서든 동일한 형태의 **도움말 모달**을 구현하기 위한 가이드입니다. 
아래의 코드 스니펫을 **복사(Copy)**하여 원하는 파일(php)의 적절한 위치에 **붙여넣기(Paste)**한 후, 내용을 수정하여 사용하세요.

---

### 1단계: 도움말 버튼 추가 (Add Help Button)
메뉴 바 또는 원하는 위치에 도움말 버튼을 추가합니다.

```html
<button type="button" class="btn btn-outline-info btn-sm me-2" onclick="openHelpModal()">
    <i class="bi bi-question-circle"></i> 도움말
</button>
```

---

### 2단계: 모달 HTML 추가 (Add Modal HTML)
파일의 하단 (`</body>` 태그 바로 위 등)에 모달 HTML을 추가합니다.  
`<!-- 내용 수정 영역 -->` 부분을 페이지에 맞게 수정하세요.

```html
<!-- 도움말 모달 -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white py-3">
                <h5 class="modal-title fs-5" id="helpModalLabel">
                    <!-- 페이지 제목에 맞게 수정 -->
                    <i class="bi bi-info-circle"></i> 도움말 제목
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 1.15rem;">
                <div class="p-2">
                    
                    <!-- [내용 수정 영역] 시작 -->
                    
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-check2-square"></i> 기능 1 제목</h6>
                    <p class="text-muted mb-4">
                        기능 1에 대한 설명을 작성하세요.<br>
                        강조할 내용은 <strong>굵게</strong> 표시하세요.
                    </p>

                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-gear"></i> 기능 2 제목</h6>
                    <p class="text-muted mb-4">
                        기능 2에 대한 설명을 작성하세요.
                    </p>

                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-qr-code"></i> 기능 3 제목</h6>
                    <p class="text-muted mb-4">
                        기능 3에 대한 설명을 작성하세요.
                    </p>
                    
                    <!-- [내용 수정 영역] 끝 -->

                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
```

---

### 3단계: 자바스크립트 추가 (Add Script)
파일의 하단 (`<script>` 영역)에 모달 실행 함수를 추가합니다.

```javascript
<script>
function openHelpModal() {
    var myModal = new bootstrap.Modal(document.getElementById('helpModal'), {
        keyboard: true
    });
    myModal.show();
}
</script>
```

---

### 참고 (Reference)
*   **Icon**: Bootstrap Icons (`bi-*`) 클래스를 사용하여 아이콘을 변경할 수 있습니다.
*   **Color**: `text-primary`, `text-success`, `text-info`, `text-danger` 등을 사용하여 색상을 변경할 수 있습니다.
*   **Size**: `modal-lg` 클래스를 제거하면 기본 크기의 모달이 됩니다.
