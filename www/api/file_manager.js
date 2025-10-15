/**
 * Google Drive 파일 관리 JavaScript 모듈 (ES5 호환)
 * 
 * 사용법:
 * 1. <script src="js/file_manager.js"></script>
 * 2. var fileManager = new GoogleDriveFileManager(options);
 * 3. fileManager.init();
 */

/**
 * GoogleDriveFileManager 생성자
 * @param {Object} options - 설정 옵션
 */
function GoogleDriveFileManager(options) {
    options = options || {};
    
    // 기본 설정과 사용자 옵션 병합
    this.options = {
        // 기본 설정
        containerId: options.containerId || 'fileManager',
        displayContainerId: options.displayContainerId || 'displayFile',
        uploadInputId: options.uploadInputId || 'upfile',
        tablename: options.tablename || '',
        item: options.item || 'attached',
        parentnum: options.parentnum || '',
        folderPath: options.folderPath || '미래기업/uploads',
        DBtable: options.DBtable || 'picuploads',
        
        // UI 설정
        showDeleteButton: options.showDeleteButton !== undefined ? options.showDeleteButton : true,
        showDownloadButton: options.showDownloadButton !== undefined ? options.showDownloadButton : true,
        allowMultiple: options.allowMultiple !== undefined ? options.allowMultiple : true,
        maxFileSize: options.maxFileSize || 10 * 1024 * 1024, // 10MB
        allowedTypes: options.allowedTypes || ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
        
        // API 엔드포인트
        uploadUrl: options.uploadUrl || '/filedrive/fileprocess.php',
        deleteUrl: options.deleteUrl || '/filedrive/fileprocess.php',
        
        // 콜백 함수
        onUploadSuccess: options.onUploadSuccess || null,
        onUploadError: options.onUploadError || null,
        onDeleteSuccess: options.onDeleteSuccess || null,
        onDeleteError: options.onDeleteError || null,
        onLoadSuccess: options.onLoadSuccess || null,
        onLoadError: options.onLoadError || null,
        
        // 기타
        autoLoad: options.autoLoad !== undefined ? options.autoLoad : true,
        showProgress: options.showProgress !== undefined ? options.showProgress : true
    };
    
    this.uploadRequest = null;
    this.isInitialized = false;
}

/**
 * 파일 매니저 초기화
 */
GoogleDriveFileManager.prototype.init = function() {
    if (this.isInitialized) return;
    
    this.setupEventListeners();
    this.setupUploadInput();
    
    if (this.options.autoLoad) {
        this.loadFiles();
    }
    
    this.isInitialized = true;
};

/**
 * 이벤트 리스너 설정
 */
GoogleDriveFileManager.prototype.setupEventListeners = function() {
    var self = this;
    
    // 파일 업로드 이벤트
    var uploadInput = document.getElementById(this.options.uploadInputId);
    if (uploadInput) {
        uploadInput.addEventListener('change', function(e) {
            self.handleFileUpload(e);
        });
    }
    
    // 드래그 앤 드롭 이벤트
    var container = document.getElementById(this.options.containerId);
    if (container) {
        container.addEventListener('dragover', function(e) {
            self.handleDragOver(e);
        });
        container.addEventListener('drop', function(e) {
            self.handleDrop(e);
        });
        container.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');
        });
    }
};

/**
 * 업로드 입력 필드 설정
 */
GoogleDriveFileManager.prototype.setupUploadInput = function() {
    var uploadInput = document.getElementById(this.options.uploadInputId);
    if (uploadInput) {
        uploadInput.multiple = this.options.allowMultiple;
        
        // allowedTypes를 accept 속성으로 변환
        var acceptTypes = [];
        for (var i = 0; i < this.options.allowedTypes.length; i++) {
            acceptTypes.push('.' + this.options.allowedTypes[i]);
        }
        uploadInput.accept = acceptTypes.join(',');
    }
};

/**
 * 드래그 오버 처리
 */
GoogleDriveFileManager.prototype.handleDragOver = function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.add('drag-over');
};

/**
 * 드롭 처리
 */
GoogleDriveFileManager.prototype.handleDrop = function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.remove('drag-over');
    
    // FileList를 배열로 변환 (ES5 방식)
    var files = [];
    for (var i = 0; i < e.dataTransfer.files.length; i++) {
        files.push(e.dataTransfer.files[i]);
    }
    this.uploadFiles(files);
};

/**
 * 파일 업로드 처리
 */
GoogleDriveFileManager.prototype.handleFileUpload = function(e) {
    // FileList를 배열로 변환 (ES5 방식)
    var files = [];
    for (var i = 0; i < e.target.files.length; i++) {
        files.push(e.target.files[i]);
    }
    this.uploadFiles(files);
};

/**
 * 파일 업로드
 */
GoogleDriveFileManager.prototype.uploadFiles = function(files) {
    if (!files || files.length === 0) return;
    
    var self = this;
    
    // 파일 검증
    var validFiles = this.validateFiles(files);
    if (validFiles.length === 0) return;
    
    // FormData 생성
    var formData = new FormData();
    
    for (var i = 0; i < validFiles.length; i++) {
        formData.append(this.options.uploadInputId + '[]', validFiles[i]);
    }
    
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
        success: function(response) {
            self.handleUploadSuccess(response);
        },
        error: function(xhr, status, error) {
            self.handleUploadError(xhr, status, error);
        }
    });
};

/**
 * 파일 검증
 */
GoogleDriveFileManager.prototype.validateFiles = function(files) {
    var validFiles = [];
    
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        
        // 파일 크기 검증
        if (file.size > this.options.maxFileSize) {
            this.showError('파일 "' + file.name + '"이 너무 큽니다. 최대 ' + this.formatFileSize(this.options.maxFileSize) + '까지 업로드 가능합니다.');
            continue;
        }
        
        // 파일 타입 검증
        var fileExtension = file.name.split('.').pop().toLowerCase();
        var isAllowed = false;
        for (var j = 0; j < this.options.allowedTypes.length; j++) {
            if (this.options.allowedTypes[j] === fileExtension) {
                isAllowed = true;
                break;
            }
        }
        
        if (!isAllowed) {
            this.showError('파일 "' + file.name + '"은 지원하지 않는 형식입니다.');
            continue;
        }
        
        validFiles.push(file);
    }
    
    return validFiles;
};

/**
 * 업로드 성공 처리
 */
GoogleDriveFileManager.prototype.handleUploadSuccess = function(response) {
    this.hideProgress();
    
    if (Array.isArray(response)) {
        for (var i = 0; i < response.length; i++) {
            var result = response[i];
            if (result.status === 'success') {
                this.showSuccess('파일 "' + result.file + '" 업로드 완료');
            } else {
                this.showError('파일 "' + result.file + '" 업로드 실패: ' + result.message);
            }
        }
    }
    
    // 파일 목록 새로고침
    this.loadFiles();
    
    // 콜백 실행
    if (this.options.onUploadSuccess) {
        this.options.onUploadSuccess(response);
    }
};

/**
 * 업로드 실패 처리
 */
GoogleDriveFileManager.prototype.handleUploadError = function(xhr, status, error) {
    this.hideProgress();
    this.showError('업로드 실패: ' + error);
    
    // 콜백 실행
    if (this.options.onUploadError) {
        this.options.onUploadError(xhr, status, error);
    }
};

/**
 * 파일 목록 로드
 */
GoogleDriveFileManager.prototype.loadFiles = function() {
    var self = this;
    var params = {
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
        success: function(data) {
            self.handleLoadSuccess(data);
        },
        error: function(xhr, status, error) {
            self.handleLoadError(xhr, status, error);
        }
    });
};

/**
 * 파일 로드 성공 처리
 */
GoogleDriveFileManager.prototype.handleLoadSuccess = function(data) {
    this.displayFiles(data);
    
    // 콜백 실행
    if (this.options.onLoadSuccess) {
        this.options.onLoadSuccess(data);
    }
};

/**
 * 파일 로드 실패 처리
 */
GoogleDriveFileManager.prototype.handleLoadError = function(xhr, status, error) {
    this.showError('파일 목록 로드 실패: ' + error);
    
    // 콜백 실행
    if (this.options.onLoadError) {
        this.options.onLoadError(xhr, status, error);
    }
};

/**
 * 파일 목록 표시
 */
GoogleDriveFileManager.prototype.displayFiles = function(files) {
    var container = document.getElementById(this.options.displayContainerId);
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!Array.isArray(files) || files.length === 0) {
        container.innerHTML = '<div class="text-center text-muted">첨부된 파일이 없습니다.</div>';
        return;
    }
    
    for (var i = 0; i < files.length; i++) {
        var fileElement = this.createFileElement(files[i], i);
        container.appendChild(fileElement);
    }
};

/**
 * 파일 요소 생성
 */
GoogleDriveFileManager.prototype.createFileElement = function(file, index) {
    var div = document.createElement('div');
    div.className = 'row mt-1 mb-2';
    
    var html = '<div class="d-flex align-items-center justify-content-center">' +
        '<span id="file' + index + '">' +
        '<a href="#" onclick="popupCenter(\'' + file.link + '\', \'filePopup\', 800, 600); return false;">' +
        file.realname +
        '</a>' +
        '</span>';
    
    if (this.options.showDeleteButton) {
        html += '<button type="button" class="btn btn-danger btn-sm ms-2" ' +
            'onclick="window.fileManager.deleteFile(\'' + index + '\', \'' + file.fileId + '\')">' +
            '<i class="bi bi-trash"></i>' +
            '</button>';
    }
    
    html += '</div>';
    div.innerHTML = html;
    
    return div;
};

/**
 * 파일 삭제
 */
GoogleDriveFileManager.prototype.deleteFile = function(index, fileId) {
    if (!confirm('정말 삭제하시겠습니까?')) return;
    
    var self = this;
    var data = {
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
        success: function(response) {
            self.handleDeleteSuccess(response, index);
        },
        error: function(xhr, status, error) {
            self.handleDeleteError(xhr, status, error);
        }
    });
};

/**
 * 삭제 성공 처리
 */
GoogleDriveFileManager.prototype.handleDeleteSuccess = function(response, index) {
    if (response.status === 'success') {
        this.showSuccess('파일이 성공적으로 삭제되었습니다.');
        
        // UI에서 파일 제거
        var fileElement = document.getElementById('file' + index);
        if (fileElement && fileElement.parentElement && fileElement.parentElement.parentElement) {
            fileElement.parentElement.parentElement.remove();
        }
        
        // 콜백 실행
        if (this.options.onDeleteSuccess) {
            this.options.onDeleteSuccess(response, index);
        }
    } else {
        this.showError('삭제 실패: ' + response.message);
    }
};

/**
 * 삭제 실패 처리
 */
GoogleDriveFileManager.prototype.handleDeleteError = function(xhr, status, error) {
    this.showError('삭제 실패: ' + error);
    
    // 콜백 실행
    if (this.options.onDeleteError) {
        this.options.onDeleteError(xhr, status, error);
    }
};

/**
 * 진행률 표시
 */
GoogleDriveFileManager.prototype.showProgress = function() {
    var container = document.getElementById(this.options.displayContainerId);
    if (container) {
        container.innerHTML = '<div class="text-center"><i class="spinner-border" role="status"></i> 업로드 중...</div>';
    }
};

/**
 * 진행률 숨기기
 */
GoogleDriveFileManager.prototype.hideProgress = function() {
    // 진행률 표시 제거는 loadFiles()에서 처리됨
};

/**
 * 성공 메시지 표시
 */
GoogleDriveFileManager.prototype.showSuccess = function(message) {
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
};

/**
 * 에러 메시지 표시
 */
GoogleDriveFileManager.prototype.showError = function(message) {
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
};

/**
 * 파일 크기 포맷팅
 */
GoogleDriveFileManager.prototype.formatFileSize = function(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

/**
 * 설정 업데이트
 */
GoogleDriveFileManager.prototype.updateOptions = function(newOptions) {
    // 수동으로 옵션 병합 (spread operator 대신)
    for (var key in newOptions) {
        if (newOptions.hasOwnProperty(key)) {
            this.options[key] = newOptions[key];
        }
    }
};

/**
 * 파일 매니저 제거
 */
GoogleDriveFileManager.prototype.destroy = function() {
    if (this.uploadRequest) {
        this.uploadRequest.abort();
    }
    
    // 이벤트 리스너 제거
    var uploadInput = document.getElementById(this.options.uploadInputId);
    if (uploadInput) {
        var newInput = uploadInput.cloneNode(true);
        if (uploadInput.parentNode) {
            uploadInput.parentNode.replaceChild(newInput, uploadInput);
        }
    }
    
    this.isInitialized = false;
};

/**
 * 팝업 창 열기 함수 (기존 코드와 호환성 유지)
 */
function popupCenter(url, name, width, height) {
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    
    var params = 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',scrollbars=yes,resizable=yes';
    window.open(url, name, params);
}

/**
 * 전역 파일 매니저 인스턴스 (기존 코드와 호환성 유지)
 */
var fileManager = null;

/**
 * 파일 매니저 초기화 헬퍼 함수
 */
function initFileManager(options) {
    options = options || {};
    fileManager = new GoogleDriveFileManager(options);
    fileManager.init();
    return fileManager;
}
