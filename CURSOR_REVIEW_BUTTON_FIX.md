# Cursor "Review next file" 버튼 문제 해결 가이드

## 문제 상황
- `qc/laser.php` 파일에서 "Review next file" 버튼이 계속 나타남
- 과거 `QC/laser.php`에서 `qc/laser.php`로 경로가 변경되었지만 Cursor가 이전 경로를 캐시하고 있음
- Windows에서는 대소문자 구분이 없어 두 경로가 같은 파일로 인식됨

## 해결 방법

### 방법 1: Cursor 워크스페이스 캐시 삭제 (권장)

1. **Cursor 완전히 종료**
   - 모든 Cursor 창 닫기
   - 작업 관리자에서 Cursor 프로세스가 완전히 종료되었는지 확인

2. **워크스페이스 캐시 삭제**
   - Windows 탐색기에서 다음 경로로 이동:
     ```
     %APPDATA%\Cursor\User\workspaceStorage\
     ```
   - 또는 직접 경로:
     ```
     C:\Users\[사용자명]\AppData\Roaming\Cursor\User\workspaceStorage\
     ```
   - `mirae8440` 관련 폴더 찾기 (UUID 형태의 폴더명)
   - 해당 폴더 내의 모든 파일 삭제 또는 폴더 전체 삭제

3. **Cursor 재시작**
   - Cursor를 다시 시작
   - 프로젝트 폴더를 다시 열기

### 방법 2: Cursor 개발자 명령어 사용

1. Cursor에서 `Ctrl+Shift+P` (또는 `Cmd+Shift+P` on Mac)
2. 다음 명령어들을 순서대로 실행:
   - `Developer: Reload Window` - 창 새로고침
   - `Developer: Clear Editor History` - 편집기 히스토리 삭제
   - `Developer: Reset Window Layout` - 창 레이아웃 리셋

### 방법 3: Git 인덱스 정리 (대소문자 변경 추적)

Windows에서는 Git이 대소문자 변경을 제대로 추적하지 못할 수 있습니다.

```bash
# Git에서 대소문자 변경 확인
git ls-files | grep -i "qc/laser.php"

# Git 설정 확인 (대소문자 구분 설정)
git config core.ignorecase

# 만약 true로 되어 있다면:
git config core.ignorecase false

# 파일 인덱스에서 제거 후 다시 추가
git rm --cached www/QC/laser.php 2>/dev/null || true
git add www/qc/laser.php
git commit -m "Fix: Update QC to qc path case sensitivity"
```

### 방법 4: 프로젝트 재설정

1. Cursor에서 `File > Close Folder`
2. `File > Open Folder`로 프로젝트 다시 열기
3. Cursor가 인덱싱을 완료할 때까지 대기 (하단 상태바 확인)

### 방법 5: Cursor 설정 확인

`.vscode/settings.json` 파일에 다음 설정이 있는지 확인:

```json
{
  "files.exclude": {
    "**/.cursor": true
  },
  "search.exclude": {
    "**/.cursor": true
  }
}
```

## 추가 확인 사항

1. **실제 파일 경로 확인**
   - 현재 `www/qc/laser.php` 파일이 존재하는지 확인
   - `www/QC/laser.php`는 Windows에서는 같은 파일이지만, Git에서는 다를 수 있음

2. **Cursor 확장 프로그램 확인**
   - 코드 리뷰 관련 확장 프로그램이 설치되어 있는지 확인
   - 필요시 비활성화 후 재시작

3. **프로젝트 루트의 숨김 파일 확인**
   - `.cursor/` 폴더가 있다면 삭제
   - `.vscode/` 폴더의 캐시 파일 확인

## 예상 결과

위 단계를 완료하면:
1. Cursor가 `qc/laser.php` 경로를 올바르게 인식
2. "Review next file" 버튼이 사라지거나 올바른 파일로 이동
3. 파일 변경사항이 정상적으로 추적됨

## 문제가 지속되는 경우

1. Cursor 완전 재설치 고려
2. 프로젝트를 새 폴더에 복사하여 다시 열기
3. Cursor 지원팀에 문의 (이전 경로 캐시 문제로 보고)

