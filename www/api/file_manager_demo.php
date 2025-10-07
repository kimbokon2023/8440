<?php
/**
 * Google Drive 파일 관리 API 사용 예제
 * 
 * 이 파일은 file_api.php와 file_manager.js를 사용하는 방법을 보여줍니다.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/load_GoogleDrive.php';
require_once __DIR__ . '/file_api.php';

$title_message = 'Google Drive 파일 관리 API 데모';
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/common.php' ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; ?>
<title><?=$title_message?></title>
</head>

<style>
.drag-over {
    border: 2px dashed #007bff !important;
    background-color: #f8f9fa !important;
}

.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.file-item {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 10px;
    margin: 5px 0;
    background-color: #fff;
}

.file-item:hover {
    background-color: #f8f9fa;
}
</style>

<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/modal.php"; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Google Drive 파일 관리 API 데모</h4>
                </div>
                <div class="card-body">
                    
                    <!-- 기본 사용법 예제 -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>1. 기본 사용법</h5>
                            <div class="alert alert-info">
                                <strong>PHP 백엔드:</strong><br>
                                <code>include_once 'file_api.php';</code><br>
                                <code>$fileManager = new GoogleDriveFileManager();</code><br>
                                <code>$result = $fileManager->uploadFiles($_FILES, $options);</code>
                            </div>
                        </div>
                    </div>

                    <!-- 파일 업로드 영역 -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>2. 파일 업로드</h5>
                            <div id="fileManager" class="file-upload-area">
                                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                <p class="mt-2">파일을 드래그하여 놓거나 클릭하여 선택하세요</p>
                                <input type="file" id="upfile" name="upfile[]" multiple style="display: none;">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('upfile').click()">
                                    파일 선택
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 파일 목록 표시 영역 -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>3. 첨부된 파일 목록</h5>
                            <div id="displayFile" class="border rounded p-3" style="min-height: 100px;">
                                <div class="text-center text-muted">파일을 업로드하면 여기에 표시됩니다.</div>
                            </div>
                        </div>
                    </div>

                    <!-- 고급 사용법 예제 -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>4. 고급 사용법 예제</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>PHP에서 직접 사용</h6>
                                    <pre class="bg-light p-3"><code>// 파일 업로드
$options = [
    'tablename' => 'my_table',
    'item' => 'attached',
    'parentnum' => '123',
    'folderPath' => 'MyProject/uploads',
    'compress' => true,
    'quality' => 80
];
$result = uploadFilesToGoogleDrive($_FILES, $options);

// 파일 목록 조회
$files = getFilesFromGoogleDrive($options);

// 파일 삭제
$result = deleteFileFromGoogleDrive($fileId, $options);</code></pre>
                                </div>
                                <div class="col-md-6">
                                    <h6>JavaScript에서 사용</h6>
                                    <pre class="bg-light p-3"><code>// 파일 매니저 초기화
const fileManager = new GoogleDriveFileManager({
    tablename: 'my_table',
    item: 'attached',
    parentnum: '123',
    folderPath: 'MyProject/uploads',
    onUploadSuccess: (response) => {
        console.log('업로드 완료:', response);
    },
    onDeleteSuccess: (response) => {
        console.log('삭제 완료:', response);
    }
});
fileManager.init();</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 설정 옵션 설명 -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>5. 설정 옵션</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>옵션</th>
                                            <th>타입</th>
                                            <th>기본값</th>
                                            <th>설명</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>tablename</td>
                                            <td>string</td>
                                            <td>''</td>
                                            <td>데이터베이스 테이블명</td>
                                        </tr>
                                        <tr>
                                            <td>item</td>
                                            <td>string</td>
                                            <td>'attached'</td>
                                            <td>파일 타입 (attached, image 등)</td>
                                        </tr>
                                        <tr>
                                            <td>parentnum</td>
                                            <td>string</td>
                                            <td>''</td>
                                            <td>부모 레코드 ID</td>
                                        </tr>
                                        <tr>
                                            <td>folderPath</td>
                                            <td>string</td>
                                            <td>'미래기업/uploads'</td>
                                            <td>Google Drive 폴더 경로</td>
                                        </tr>
                                        <tr>
                                            <td>compress</td>
                                            <td>boolean</td>
                                            <td>true</td>
                                            <td>이미지 압축 여부</td>
                                        </tr>
                                        <tr>
                                            <td>quality</td>
                                            <td>integer</td>
                                            <td>70</td>
                                            <td>이미지 압축 품질 (1-100)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 테스트 버튼들 -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>6. 테스트 기능</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="testUpload()">
                                    테스트 업로드
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="testLoad()">
                                    파일 목록 새로고침
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="testUpdateIds()">
                                    파일 ID 업데이트
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="showCurrentOptions()">
                                    현재 설정 보기
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript 파일 매니저 초기화 -->
    <script src="file_manager.js"></script>
<script>
$(document).ready(function() {
    // 파일 매니저 초기화
    const fileManager = new GoogleDriveFileManager({
        containerId: 'fileManager',
        displayContainerId: 'displayFile',
        uploadInputId: 'upfile',
        tablename: 'demo_table',
        item: 'attached',
        parentnum: '<?=date("YmdHis")?>', // 데모용 고유 ID
        folderPath: '미래기업/demo',
        DBtable: 'picuploads',
        showDeleteButton: true,
        showDownloadButton: true,
        allowMultiple: true,
        maxFileSize: 10 * 1024 * 1024, // 10MB
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
        onUploadSuccess: function(response) {
            console.log('업로드 성공:', response);
        },
        onUploadError: function(xhr, status, error) {
            console.error('업로드 실패:', error);
        },
        onDeleteSuccess: function(response, index) {
            console.log('삭제 성공:', response);
        },
        onDeleteError: function(xhr, status, error) {
            console.error('삭제 실패:', error);
        },
        onLoadSuccess: function(data) {
            console.log('파일 목록 로드 성공:', data);
        },
        onLoadError: function(xhr, status, error) {
            console.error('파일 목록 로드 실패:', error);
        }
    });
    
    fileManager.init();
    
    // 전역 변수로 설정 (테스트 함수에서 사용)
    window.demoFileManager = fileManager;
});

// 테스트 함수들
function testUpload() {
    // 가상의 파일 업로드 테스트
    const input = document.getElementById('upfile');
    input.click();
}

function testLoad() {
    if (window.demoFileManager) {
        window.demoFileManager.loadFiles();
    }
}

function testUpdateIds() {
    // PHP에서 파일 ID 업데이트 테스트
    $.ajax({
        url: 'file_api_test.php',
        type: 'POST',
        data: {
            action: 'updateIds',
            tablename: 'demo_table',
            item: 'attached',
            parentnum: '<?=date("YmdHis")?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(`파일 ID 업데이트 완료: ${response.updated}개 파일`);
                testLoad(); // 목록 새로고침
            } else {
                alert('파일 ID 업데이트 실패: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('파일 ID 업데이트 실패: ' + error);
        }
    });
}

function showCurrentOptions() {
    if (window.demoFileManager) {
        console.log('현재 설정:', window.demoFileManager.options);
        alert('현재 설정을 콘솔에서 확인하세요.');
    }
}
</script>

</body>
</html>
