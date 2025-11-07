# Google Drive 저장 경로 설정 가이드

## 개요
각 모듈별로 Google Drive 저장 위치를 다르게 설정할 수 있습니다.

## 현재 설정된 경로

### 발주 관리 (다온)
**파일**: `www/on/order_form.php`
```javascript
folderPath: '다온/발주'
```

**Google Drive 구조**:
```
Google Drive 루트
└─ 다온/
   └─ 발주/
      ├─ 2025_11_02_06_18_28_045_0.jpg
      └─ ...
```

### 기존 미래기업 폴더
**기본 경로**: `미래기업/uploads`
```javascript
folderPath: '미래기업/uploads'
```

**Google Drive 구조**:
```
Google Drive 루트
└─ 미래기업/
   └─ uploads/
      └─ ...
```

## 새 모듈에서 사용하는 방법

### 1. HTML/JavaScript에서 설정

```javascript
// 파일 매니저 초기화
var myFileManager = new GoogleDriveFileManager({
    containerId: 'dropZone',
    displayContainerId: 'displayFile',
    uploadInputId: 'upfile',
    tablename: 'your_table',           // DB 테이블명
    item: 'attached',
    parentnum: recordId,                 // 레코드 ID
    folderPath: '원하는폴더명/하위폴더',  // ← 여기서 경로 설정
    DBtable: 'picuploads',
    // ... 기타 옵션
});
```

### 2. 경로 예시

#### 부서별 분리
```javascript
// QC 부서
folderPath: '미래기업/QC/검사보고서'

// R&D 부서
folderPath: '미래기업/RnD/연구자료'

// 지원팀
folderPath: '미래기업/지원파트/문서'
```

#### 프로젝트별 분리
```javascript
// 다온 프로젝트
folderPath: '다온/발주'
folderPath: '다온/거래처'
folderPath: '다온/제품'

// 다른 프로젝트
folderPath: '프로젝트명/카테고리'
```

#### 날짜별 분리
```javascript
const year = new Date().getFullYear();
const month = (new Date().getMonth() + 1).toString().padStart(2, '0');
folderPath: `다온/발주/${year}/${month}`
// 결과: 다온/발주/2025/11
```

## 경로 규칙

### ✅ 허용되는 문자
- 한글 폴더명 가능: `다온`, `미래기업`, `검사보고서`
- 영문 폴더명 가능: `Daon`, `Orders`, `Uploads`
- 숫자 사용 가능: `2025`, `11`
- 슬래시(`/`)로 하위 폴더 구분

### ❌ 주의사항
- 경로 맨 앞/뒤에 슬래시 넣지 않기: ~~`/다온/발주/`~~ → `다온/발주`
- 특수문자 피하기: `<`, `>`, `:`, `"`, `|`, `?`, `*`
- 연속 슬래시 사용 금지: ~~`다온//발주`~~

## 동적 경로 설정

### PHP에서 경로 전달
```php
// order_form.php
$module = 'daon';
$category = 'orders';
$drivePath = "{$module}/{$category}";
?>

<script>
var myFileManager = new GoogleDriveFileManager({
    // ...
    folderPath: '<?php echo $drivePath; ?>',
    // ...
});
</script>
```

### JavaScript 변수 사용
```javascript
// 설정 객체
const driveConfig = {
    daon: {
        orders: '다온/발주',
        customers: '다온/거래처',
        products: '다온/제품'
    },
    mirae: {
        qc: '미래기업/QC',
        rnd: '미래기업/RnD'
    }
};

// 사용
var myFileManager = new GoogleDriveFileManager({
    // ...
    folderPath: driveConfig.daon.orders,  // '다온/발주'
    // ...
});
```

## ⚠️ 중요: folderPath가 필요한 경우와 불필요한 경우

### ✅ folderPath가 필요한 경우 (업로드만)

| 작업 | 파일 | 필요 여부 | 이유 |
|------|------|-----------|------|
| **업로드** | `order_form.php` | ✅ **필수** | 어느 폴더에 저장할지 결정 |

**예시**: `order_form.php` 수정
```javascript
folderPath: '다온/발주'  // ← 이것만 수정하면 됨!
```

### ❌ folderPath가 불필요한 경우

| 작업 | 파일 | 필요 여부 | 이유 |
|------|------|-----------|------|
| **조회** | `order_view.php`, `load_filelist.php` | ❌ 불필요 | DB의 `fileId`로 직접 조회 |
| **삭제** | `file_manager.js`, `fileprocess.php` | ❌ 불필요 | `fileId`로 직접 삭제 |
| **다운로드** | `file_manager.js` | ❌ 불필요 | `fileId`로 직접 접근 |

**왜 불필요한가?**
- Google Drive API는 **파일 ID**로 직접 접근 가능
- 파일이 `다온/발주`에 있든 `미래기업/uploads`에 있든 상관없음
- DB에 저장된 `fileId`만 있으면 모든 작업 가능

### 파일 저장 → 조회/삭제 흐름

```
1. 업로드 (folderPath 사용)
   ↓
   order_form.php: folderPath: '다온/발주'
   ↓
   Google Drive: /다온/발주/파일명.jpg 저장
   ↓
   DB 저장: fileId='abc123xyz...' 

2. 조회/삭제/다운로드 (folderPath 불필요)
   ↓
   DB 조회: fileId='abc123xyz...'
   ↓
   Google Drive API: fileId로 직접 접근
   ↓
   성공! (어느 폴더에 있든 상관없음)
```

## 권한 설정

`www/filedrive/fileprocess.php`의 194번 줄에서 공유할 이메일 추가:
```php
$sharedEmails = [
    'your-email@gmail.com',  // ← 공유받을 이메일
];
```

## 문제 해결

### 파일이 Google Drive UI에서 안 보일 때
- 서비스 계정으로 업로드된 파일은 서비스 계정의 드라이브에 저장됨
- 해결: 위의 `$sharedEmails`에 이메일 추가하여 공유받기

### 한글 폴더명이 깨질 때
- Google Drive API는 UTF-8을 지원하므로 한글 사용 가능
- 파일 시스템이 아닌 API로 접근하므로 인코딩 문제 없음

### 폴더가 자동 생성 안 될 때
- `getOrCreateFolderByPath()` 함수가 자동으로 폴더 생성
- 로그 확인: `error_log("Google Drive 폴더 ID 획득 성공: ...")`

### 조회/삭제 화면은 수정 안 해도 되나요?
**정답: 안 해도 됩니다!**

- `order_view.php`: folderPath 사용 안 함 (fileId 기반)
- `file_manager.js`: folderPath를 전달하지만 실제로는 사용 안 함
- `fileprocess.php` DELETE: folderPath를 받지만 무시함

**왜?** Google Drive API는 파일 ID로 직접 접근하므로 폴더 경로가 필요 없습니다.

**따라서**: `order_form.php`의 업로드 부분만 수정하면 끝!

