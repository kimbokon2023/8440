/**
 * Google Drive 파일 관리 JavaScript 모듈
 * 
 * 사용법:
 * 1. <script src="js/file_manager.js"></script>
 * 2. const fileManager = new GoogleDriveFileManager(options);
 * 3. fileManager.init();
 */

class GoogleDriveFileManager {
    constructor(options = {}) {
        this.options = {
            // 기본 설정
            containerId: 'fileManager',
            displayContainerId: 'displayFile',
            uploadInputId: 'upfile',
            tablename: '',
            item: 'attached',
            parentnum: '',
            folderPath: '미래기업/uploads',
            DBtable: 'picuploads',
            
            // UI 설정
            showDeleteButton: true,
            showDownloadButton: true,
            allowMultiple: true,
            maxFileSize: 10 * 1024 * 1024, // 10MB
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
            
            // API 엔드포인트
            uploadUrl: '/filedrive/fileprocess.php',
            deleteUrl: '/filedrive/fileprocess.php',
            
            // 콜백 함수
            onUploadSuccess: null,
            onUploadError: null,
            onDeleteSuccess: null,
            onDeleteError: null,
            onLoadSuccess: null,
            onLoadError: null,
            
            // 기타
            autoLoad: true,
            showProgress: true,
            
            ...options
        };
        
        this.uploadRequest = null;
        this.isInitialized = false;
    }
    
    /**
     * 파일 매니저 초기화
     */
    init() {
        if (this.isInitialized) return;
        
        this.setupEventListeners();
        this.setupUploadInput();
        
        if (this.options.autoLoad) {
            this.loadFiles();
        }
        
        this.isInitialized = true;
    }
    
    /**
     * 이벤트 리스너 설정
     */
    setupEventListeners() {
        // 파일 업로드 이벤트
        const uploadInput = document.getElementById(this.options.uploadInputId);
        if (uploadInput) {
            uploadInput.addEventListener('change', (e) => this.handleFileUpload(e));
        }
        
        // 드래그 앤 드롭 이벤트
        const container = document.getElementById(this.options.containerId);
        if (container) {
            container.addEventListener('dragover', (e) => this.handleDragOver(e));
            container.addEventListener('drop', (e) => this.handleDrop(e));
        }
    }
    
    /**
     * 업로드 입력 필드 설정
     */
    setupUploadInput() {
        const uploadInput = document.getElementById(this.options.uploadInputId);
        if (uploadInput) {
            uploadInput.multiple = this.options.allowMultiple;
            uploadInput.accept = this.options.allowedTypes.map(type => `.${type}`).join(',');
        }
    }
    
    /**
     * 드래그 오버 처리
     */
    handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.add('drag-over');
    }
    
    /**
     * 드롭 처리
     */
    handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.remove('drag-over');
        
        const files = Array.from(e.dataTransfer.files);
        this.uploadFiles(files);
    }
    
    /**
     * 파일 업로드 처리
     */
    handleFileUpload(e) {
        const files = Array.from(e.target.files);
        this.uploadFiles(files);
    }
    
    /**
     * 파일 업로드
     */
    uploadFiles(files) {
        if (!files || files.length === 0) return;
        
        // 파일 검증
        const validFiles = this.validateFiles(files);
        if (validFiles.length === 0) return;
        
        // FormData 생성
        const formData = new FormData();
        
        validFiles.forEach((file, index) => {
            formData.append(`${this.options.uploadInputId}[]`, file);
        });
        
        // 옵션 추가
        formData.append('tablename', this.options.tablename);
        formData.append('item', this.options.item);
        formData.append('parentnum', this.options.parentnum);
        formData.append('folderPath', this.options.folderPath);
        formData.append('DBtable', this.options.DBtable);
        formData.append('upfilename', this.options.uploadInputId);
        
        // 진행률 표시
        if (this.options.showProgress) {
            this.showProgress();
        }
        
        // 업로드 요청
        this.uploadRequest = $.ajax({
            url: this.options.uploadUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: (response) => this.handleUploadSuccess(response),
            error: (xhr, status, error) => this.handleUploadError(xhr, status, error)
        });
    }
    
    /**
     * 파일 검증
     */
    validateFiles(files) {
        const validFiles = [];
        
        files.forEach(file => {
            // 파일 크기 검증
            if (file.size > this.options.maxFileSize) {
                this.showError(`파일 "${file.name}"이 너무 큽니다. 최대 ${this.formatFileSize(this.options.maxFileSize)}까지 업로드 가능합니다.`);
                return;
            }
            
            // 파일 타입 검증
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!this.options.allowedTypes.includes(fileExtension)) {
                this.showError(`파일 "${file.name}"은 지원하지 않는 형식입니다.`);
                return;
            }
            
            validFiles.push(file);
        });
        
        return validFiles;
    }
    
    /**
     * 업로드 성공 처리
     */
    handleUploadSuccess(response) {
        this.hideProgress();
        
        if (Array.isArray(response)) {
            response.forEach(result => {
                if (result.status === 'success') {
                    this.showSuccess(`파일 "${result.file}" 업로드 완료`);
                } else {
                    this.showError(`파일 "${result.file}" 업로드 실패: ${result.message}`);
                }
            });
        }
        
        // 파일 목록 새로고침
        this.loadFiles();
        
        // 콜백 실행
        if (this.options.onUploadSuccess) {
            this.options.onUploadSuccess(response);
        }
    }
    
    /**
     * 업로드 실패 처리
     */
    handleUploadError(xhr, status, error) {
        this.hideProgress();
        this.showError(`업로드 실패: ${error}`);
        
        // 콜백 실행
        if (this.options.onUploadError) {
            this.options.onUploadError(xhr, status, error);
        }
    }
    
    /**
     * 파일 목록 로드
     */
    loadFiles() {
        const params = {
            num: this.options.parentnum,
            tablename: this.options.tablename,
            item: this.options.item,
            folderPath: this.options.folderPath
        };
        
        $.ajax({
            url: this.options.uploadUrl,
            type: 'GET',
            data: params,
            dataType: 'json',
            success: (data) => this.handleLoadSuccess(data),
            error: (xhr, status, error) => this.handleLoadError(xhr, status, error)
        });
    }
    
    /**
     * 파일 로드 성공 처리
     */
    handleLoadSuccess(data) {
        this.displayFiles(data);
        
        // 콜백 실행
        if (this.options.onLoadSuccess) {
            this.options.onLoadSuccess(data);
        }
    }
    
    /**
     * 파일 로드 실패 처리
     */
    handleLoadError(xhr, status, error) {
        this.showError(`파일 목록 로드 실패: ${error}`);
        
        // 콜백 실행
        if (this.options.onLoadError) {
            this.options.onLoadError(xhr, status, error);
        }
    }
    
    /**
     * 파일 목록 표시
     */
    displayFiles(files) {
        const container = document.getElementById(this.options.displayContainerId);
        if (!container) return;
        
        container.innerHTML = '';
        
        if (!Array.isArray(files) || files.length === 0) {
            container.innerHTML = '<div class="text-center text-muted">첨부된 파일이 없습니다.</div>';
            return;
        }
        
        files.forEach((file, index) => {
            const fileElement = this.createFileElement(file, index);
            container.appendChild(fileElement);
        });
    }
    
    /**
     * 파일 요소 생성
     */
    createFileElement(file, index) {
        const div = document.createElement('div');
        div.className = 'row mt-1 mb-2';
        div.innerHTML = `
            <div class="d-flex align-items-center justify-content-center">
                <span id="file${index}">
                    <a href="#" onclick="popupCenter('${file.link}', 'filePopup', 800, 600); return false;">
                        ${file.realname}
                    </a>
                </span>
                ${this.options.showDeleteButton ? `
                    <button type="button" class="btn btn-danger btn-sm ms-2" 
                            onclick="fileManager.deleteFile('${index}', '${file.fileId}')">
                        <i class="bi bi-trash"></i>
                    </button>
                ` : ''}
            </div>
        `;
        
        return div;
    }
    
    /**
     * 파일 삭제
     */
    deleteFile(index, fileId) {
        if (!confirm('정말 삭제하시겠습니까?')) return;
        
        const data = {
            fileId: fileId,
            tablename: this.options.tablename,
            item: this.options.item,
            folderPath: this.options.folderPath,
            DBtable: this.options.DBtable
        };
        
        $.ajax({
            url: this.options.deleteUrl,
            type: 'DELETE',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: (response) => this.handleDeleteSuccess(response, index),
            error: (xhr, status, error) => this.handleDeleteError(xhr, status, error)
        });
    }
    
    /**
     * 삭제 성공 처리
     */
    handleDeleteSuccess(response, index) {
        if (response.status === 'success') {
            this.showSuccess('파일이 성공적으로 삭제되었습니다.');
            
            // UI에서 파일 제거
            const fileElement = document.getElementById(`file${index}`);
            const deleteButton = fileElement?.nextElementSibling;
            if (fileElement) fileElement.parentElement.parentElement.remove();
            
            // 콜백 실행
            if (this.options.onDeleteSuccess) {
                this.options.onDeleteSuccess(response, index);
            }
        } else {
            this.showError(`삭제 실패: ${response.message}`);
        }
    }
    
    /**
     * 삭제 실패 처리
     */
    handleDeleteError(xhr, status, error) {
        this.showError(`삭제 실패: ${error}`);
        
        // 콜백 실행
        if (this.options.onDeleteError) {
            this.options.onDeleteError(xhr, status, error);
        }
    }
    
    /**
     * 진행률 표시
     */
    showProgress() {
        const container = document.getElementById(this.options.displayContainerId);
        if (container) {
            container.innerHTML = '<div class="text-center"><i class="spinner-border" role="status"></i> 업로드 중...</div>';
        }
    }
    
    /**
     * 진행률 숨기기
     */
    hideProgress() {
        // 진행률 표시 제거는 loadFiles()에서 처리됨
    }
    
    /**
     * 성공 메시지 표시
     */
    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '성공',
                text: message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 2000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                }
            }).showToast();
        } else {
            alert(message);
        }
    }
    
    /**
     * 에러 메시지 표시
     */
    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '오류',
                text: message,
                icon: 'error',
                confirmButtonText: '확인'
            });
        } else if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #ff6b6b, #ee5a24)"
                }
            }).showToast();
        } else {
            alert(message);
        }
    }
    
    /**
     * 파일 크기 포맷팅
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    /**
     * 설정 업데이트
     */
    updateOptions(newOptions) {
        this.options = { ...this.options, ...newOptions };
    }
    
    /**
     * 파일 매니저 제거
     */
    destroy() {
        if (this.uploadRequest) {
            this.uploadRequest.abort();
        }
        
        // 이벤트 리스너 제거
        const uploadInput = document.getElementById(this.options.uploadInputId);
        if (uploadInput) {
            uploadInput.removeEventListener('change', this.handleFileUpload);
        }
        
        this.isInitialized = false;
    }
}

/**
 * 팝업 창 열기 함수 (기존 코드와 호환성 유지)
 */
function popupCenter(url, name, width, height) {
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    
    window.open(url, name, `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`);
}

/**
 * 전역 파일 매니저 인스턴스 (기존 코드와 호환성 유지)
 */
let fileManager = null;

/**
 * 파일 매니저 초기화 헬퍼 함수
 */
function initFileManager(options = {}) {
    fileManager = new GoogleDriveFileManager(options);
    fileManager.init();
    return fileManager;
}
