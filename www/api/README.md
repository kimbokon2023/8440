# Google Drive 파일 관리 API

이 폴더는 Google Drive를 사용한 파일 업로드, 다운로드, 삭제 기능을 제공하는 재사용 가능한 API 모듈들을 포함합니다.

## 📁 파일 구조

```
api/
├── file_api.php              # PHP 백엔드 API 모듈 (핵심)
├── file_manager.js           # JavaScript 프론트엔드 모듈
├── file_api_test.php         # API 테스트 엔드포인트
├── api_test_simple.php       # 간단한 API 테스트 페이지
├── file_manager_demo.php     # 사용 예제 및 데모 페이지
├── test_api.html            # API 테스트 HTML 페이지
├── test_server.html         # 서버 테스트 HTML 페이지
├── FILE_API_GUIDE.md        # 상세 사용 가이드
└── README.md                # 이 파일
```

## 🚀 빠른 시작

### 1. PHP에서 사용
```php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/file_api.php';

$options = [
    'tablename' => 'my_table',
    'item' => 'attached',
    'parentnum' => '123',
    'folderPath' => 'MyProject/uploads'
];
$result = uploadFilesToGoogleDrive($_FILES, $options);
```

### 2. JavaScript에서 사용
```html
<script src="api/file_manager.js"></script>
<script>
const fileManager = new GoogleDriveFileManager({
    tablename: 'my_table',
    item: 'attached',
    parentnum: '123',
    folderPath: 'MyProject/uploads'
});
fileManager.init();
</script>
```

## 🧪 테스트

### 서버에서 테스트
1. **기본 연결 테스트**: `https://8440.co.kr/api/api_test_simple.php`
2. **상세 테스트 페이지**: `https://8440.co.kr/api/test_server.html`
3. **데모 페이지**: `https://8440.co.kr/api/file_manager_demo.php`

### 로컬에서 테스트
1. **API 테스트**: `https://8440.co.kr/api/file_api_test.php`
2. **테스트 페이지**: `https://8440.co.kr/api/test_api.html`

## 📋 주요 기능

- **파일 업로드**: Google Drive에 파일 업로드 및 데이터베이스 저장
- **파일 조회**: 업로드된 파일 목록 조회 및 표시
- **파일 삭제**: Google Drive 및 데이터베이스에서 파일 삭제
- **이미지 압축**: 자동 이미지 압축 및 품질 조절
- **드래그 앤 드롭**: 직관적인 파일 업로드 UI
- **실시간 피드백**: 업로드/삭제 상태 실시간 표시

## ⚙️ 설정 요구사항

1. **Google Drive API**: `tokens/mytoken.json` 파일 설정
2. **데이터베이스**: `picuploads` 테이블 존재
3. **PHP 확장**: GD, PDO, cURL
4. **Composer**: Google API 클라이언트 라이브러리

## 📖 상세 문서

자세한 사용법은 `FILE_API_GUIDE.md` 파일을 참조하세요.

## 🔧 문제 해결

문제가 발생하면 다음을 확인하세요:
1. Google Drive API 키 설정
2. 데이터베이스 연결 상태
3. 파일 권한 설정
4. PHP 에러 로그

## 📞 지원

추가 기능이나 수정이 필요한 경우 코드를 검토하고 필요한 수정을 진행하세요.
