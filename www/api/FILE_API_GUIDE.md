# Google Drive 파일 관리 API 사용 가이드

이 문서는 Google Drive를 사용한 파일 업로드, 다운로드, 삭제 기능을 제공하는 재사용 가능한 API 모듈의 사용법을 설명합니다.

## 📁 파일 구성

- `file_api.php` - PHP 백엔드 API 모듈
- `js/file_manager.js` - JavaScript 프론트엔드 모듈
- `file_manager_demo.php` - 사용 예제 및 데모 페이지
- `file_api_test.php` - API 테스트 엔드포인트

## 🚀 빠른 시작

### 1. 기본 설정

```php
// PHP 파일 상단에 추가
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/api/file_api.php';
```

```html
<!-- HTML 파일에 추가 -->
<script src="js/file_manager.js"></script>
```

### 2. PHP에서 사용

```php
// 파일 업로드
$options = [
    'tablename' => 'my_table',
    'item' => 'attached',
    'parentnum' => '123',
    'folderPath' => 'MyProject/uploads'
];

try {
    $result = uploadFilesToGoogleDrive($_FILES, $options);
    if ($result) {
        // 업로드 성공 처리
        error_log("파일 업로드 성공: " . count($result) . "개");
    }
} catch (Exception $e) {
    error_log("파일 업로드 오류: " . $e->getMessage());
}

// 파일 목록 조회
try {
    $files = getFilesFromGoogleDrive($options);
} catch (Exception $e) {
    error_log("파일 목록 조회 오류: " . $e->getMessage());
    $files = [];
}

// 파일 삭제
$fileId = '파일ID';
try {
    $result = deleteFileFromGoogleDrive($fileId, $options);
} catch (Exception $e) {
    error_log("파일 삭제 오류: " . $e->getMessage());
}
```

### 3. JavaScript에서 사용

```javascript
// 파일 매니저 초기화 (ES5 호환)
var fileManager = new GoogleDriveFileManager({
    tablename: 'my_table',
    item: 'attached',
    parentnum: '123',
    folderPath: 'MyProject/uploads'
});
fileManager.init();
```

## 📋 상세 사용법

### PHP API 클래스

#### GoogleDriveFileManager 클래스

```php
$fileManager = new GoogleDriveFileManager();
```

#### 주요 메서드

##### uploadFiles($files, $options)
파일을 Google Drive에 업로드합니다.

**매개변수:**
- `$files`: $_FILES 배열
- `$options`: 설정 옵션 배열

**옵션:**
```php
$options = [
    'folderPath' => '미래기업/uploads',    // Google Drive 폴더 경로
    'tablename' => '',                     // 데이터베이스 테이블명
    'item' => 'attached',                  // 파일 타입
    'parentnum' => '',                     // 부모 레코드 ID
    'DBtable' => 'picuploads',             // 데이터베이스 테이블명
    'compress' => true,                    // 이미지 압축 여부
    'quality' => 70                        // 압축 품질 (1-100)
];
```

**반환값:**
```php
[
    [
        'file' => 'example.jpg',
        'status' => 'success',
        'new_name' => '2024_01_01_12_00_00_001_0.jpg',
        'fileId' => '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms',
        'realname' => 'example.jpg'
    ]
]
```

##### getFiles($options)
파일 목록을 조회합니다.

**반환값:**
```php
[
    [
        'fileId' => '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms',
        'thumbnail' => 'https://drive.google.com/thumbnail?id=...',
        'link' => 'https://drive.google.com/file/d/.../view',
        'realname' => 'example.jpg'
    ]
]
```

##### deleteFile($fileId, $options)
파일을 삭제합니다.

**반환값:**
```php
['status' => 'success', 'message' => '파일 삭제 완료']
```

### JavaScript API 클래스

#### GoogleDriveFileManager 클래스

```javascript
// ES5 호환 방식
var fileManager = new GoogleDriveFileManager(options);
```

#### 설정 옵션

```javascript
// ES5 호환 방식
var options = {
    // 기본 설정
    containerId: 'fileManager',           // 컨테이너 요소 ID
    displayContainerId: 'displayFile',    // 파일 목록 표시 영역 ID
    uploadInputId: 'upfile',              // 파일 입력 필드 ID
    tablename: '',                        // 데이터베이스 테이블명
    item: 'attached',                     // 파일 타입
    parentnum: '',                        // 부모 레코드 ID
    folderPath: '미래기업/uploads',       // Google Drive 폴더 경로
    DBtable: 'picuploads',                // 데이터베이스 테이블명
    
    // UI 설정
    showDeleteButton: true,               // 삭제 버튼 표시 여부
    showDownloadButton: true,             // 다운로드 버튼 표시 여부
    allowMultiple: true,                  // 다중 파일 선택 허용
    maxFileSize: 10 * 1024 * 1024,       // 최대 파일 크기 (10MB)
    allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf'], // 허용 파일 타입
    
    // API 엔드포인트
    uploadUrl: '/filedrive/fileprocess.php',
    deleteUrl: '/filedrive/fileprocess.php',
    
    // 콜백 함수
    onUploadSuccess: null,                // 업로드 성공 콜백
    onUploadError: null,                  // 업로드 실패 콜백
    onDeleteSuccess: null,                // 삭제 성공 콜백
    onDeleteError: null,                  // 삭제 실패 콜백
    onLoadSuccess: null,                  // 로드 성공 콜백
    onLoadError: null,                    // 로드 실패 콜백
    
    // 기타
    autoLoad: true,                       // 자동 로드 여부
    showProgress: true                    // 진행률 표시 여부
};
```

#### 주요 메서드

##### init()
파일 매니저를 초기화합니다.

```javascript
fileManager.init();
```

##### uploadFiles(files)
파일을 업로드합니다.

```javascript
var files = document.getElementById('fileInput').files;
fileManager.uploadFiles(files);
```

##### loadFiles()
파일 목록을 로드합니다.

```javascript
fileManager.loadFiles();
```

##### deleteFile(index, fileId)
파일을 삭제합니다.

```javascript
fileManager.deleteFile(0, 'fileId123');
```

##### updateOptions(newOptions)
설정을 업데이트합니다.

```javascript
fileManager.updateOptions({
    tablename: 'new_table',
    parentnum: '456'
});
```

##### destroy()
파일 매니저를 제거합니다.

```javascript
fileManager.destroy();
```

## 🔧 헬퍼 함수

### PHP 헬퍼 함수

```php
// 파일 업로드
$result = uploadFilesToGoogleDrive($_FILES, $options);

// 파일 목록 조회
$files = getFilesFromGoogleDrive($options);

// 파일 삭제
$result = deleteFileFromGoogleDrive($fileId, $options);

// 파일 ID 업데이트
$result = updateFileIdsInGoogleDrive($options);
```

### JavaScript 헬퍼 함수

```javascript
// 파일 매니저 초기화 (ES5 호환)
var fileManager = initFileManager(options);

// 팝업 창 열기
popupCenter(url, 'filePopup', 800, 600);
```

## 📝 사용 예제

### 1. 기본 파일 업로드 폼

```html
<!DOCTYPE html>
<html>
<head>
    <title>파일 업로드</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/file_manager.js"></script>
</head>
<body>
    <div id="fileManager">
        <input type="file" id="upfile" multiple>
        <button onclick="document.getElementById('upfile').click()">파일 선택</button>
    </div>
    <div id="displayFile"></div>
    
    <script>
        // ES5 호환 코드 (PHP 7.3 환경에서 권장)
        var fileManager = new GoogleDriveFileManager({
            tablename: 'my_table',
            item: 'attached',
            parentnum: '123',
            folderPath: 'MyProject/uploads'
        });
        fileManager.init();
    </script>
</body>
</html>
```

### 2. PHP에서 직접 파일 처리

```php
<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/api/file_api.php';

// 요청 메서드 확인
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    // POST 데이터 초기화
    $document_id = $_POST['document_id'] ?? '';
    $category = $_POST['category'] ?? '';
    
    if ($document_id && $category) {
        $options = [
            'tablename' => 'documents',
            'item' => 'attached',
            'parentnum' => $document_id,
            'folderPath' => 'Documents/' . $category
        ];
        
        try {
            $result = uploadFilesToGoogleDrive($_FILES, $options);
            
            if ($result) {
                echo "업로드 완료: " . count($result) . "개 파일";
            }
        } catch (Exception $e) {
            error_log("파일 업로드 오류: " . $e->getMessage());
            echo "업로드 실패: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="document_id" value="123">
    <input type="hidden" name="category" value="contracts">
    <input type="file" name="files[]" multiple>
    <button type="submit">업로드</button>
</form>
```

### 3. 고급 설정 예제

```javascript
// ES5 호환 코드 (PHP 7.3 환경에서 권장)
var fileManager = new GoogleDriveFileManager({
    tablename: 'projects',
    item: 'attached',
    parentnum: 'project_456',
    folderPath: 'Projects/2024/Q1',
    maxFileSize: 50 * 1024 * 1024, // 50MB
    allowedTypes: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png'],
    compress: true,
    quality: 80,
    onUploadSuccess: function(response) {
        console.log('업로드 성공:', response);
        // 추가 처리 로직
    },
    onUploadError: function(xhr, status, error) {
        console.error('업로드 실패:', error);
        // 에러 처리 로직
    },
    onDeleteSuccess: function(response, index) {
        console.log('삭제 성공:', response);
        // 삭제 후 처리 로직
    }
});
fileManager.init();
```

## 🛠️ 테스트

### API 테스트

`file_api_test.php` 또는 `api_test_simple.php`를 사용하여 API 기능을 테스트할 수 있습니다.

```javascript
// 연결 테스트 (ES5 호환)
$.ajax({
    url: 'api/api_test_simple.php',
    type: 'POST',
    data: { action: 'testConnection' },
    dataType: 'json',
    success: function(response) {
        console.log('연결 테스트:', response);
    },
    error: function(xhr, status, error) {
        console.error('연결 테스트 오류:', error);
    }
});

// 파일 업로드 테스트
var formData = new FormData();
formData.append('action', 'upload');
formData.append('tablename', 'test_table');
formData.append('files', fileInput.files[0]);

$.ajax({
    url: 'file_api_test.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function(response) {
        console.log('업로드 테스트:', response);
    },
    error: function(xhr, status, error) {
        console.error('업로드 테스트 오류:', error);
    }
});
```

### 데모 페이지

`file_manager_demo.php`를 브라우저에서 열어 전체 기능을 테스트할 수 있습니다.

## ⚠️ 주의사항

1. **Google Drive API 설정**: `tokens/mytoken.json` 파일이 올바르게 설정되어 있어야 합니다.

2. **데이터베이스 테이블**: `picuploads` 테이블이 존재해야 합니다.

3. **권한 설정**: Google Drive API에서 필요한 권한이 설정되어 있어야 합니다.

4. **파일 크기 제한**: 서버와 Google Drive의 파일 크기 제한을 확인하세요.

5. **에러 처리**: 모든 API 호출에 적절한 에러 처리를 구현하세요.

## 🔍 문제 해결

### 일반적인 문제

1. **Google Drive 연결 실패**
   - `tokens/mytoken.json` 파일 확인
   - API 키 및 서비스 계정 설정 확인

2. **파일 업로드 실패**
   - 파일 크기 제한 확인
   - 허용된 파일 타입 확인
   - 폴더 권한 확인

3. **파일 목록이 표시되지 않음**
   - 데이터베이스 연결 확인
   - 테이블명 및 컬럼명 확인

### 로그 확인

PHP 에러 로그와 브라우저 개발자 도구 콘솔에서 상세한 에러 정보를 확인할 수 있습니다.

## 📞 지원

문제가 발생하거나 추가 기능이 필요한 경우, 코드를 검토하고 필요한 수정을 진행하세요.
