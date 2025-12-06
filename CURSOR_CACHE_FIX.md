# Cursor AI 캐시 문제 해결 가이드

## 문제 상황
- 폴더 이름을 `QC`에서 `qc`로 변경했지만 Cursor AI가 여전히 `QC` 폴더를 참조
- "Review next file" 버튼이 계속 나타나고 `QC/menu.php`로 이동하려고 함
- SFTP 업로드 시 `QC` 폴더에 저장됨

## 해결 방법

### 1. 코드 내 모든 `QC/` 참조를 `qc/`로 변경 (완료)
- ✅ `www/qc/goal.php` - 모든 `QC/` 경로를 `qc/`로 변경
- ✅ `www/qc/prod_jamb.php` - `QC/prod_jamb_sub.php`를 `qc/prod_jamb_sub.php`로 변경

### 2. Cursor 캐시 초기화

#### 방법 A: Cursor 재시작 및 캐시 삭제
1. Cursor 완전히 종료
2. Windows 탐색기에서 다음 경로로 이동:
   - `%APPDATA%\Cursor\User\workspaceStorage\`
   - 또는 `C:\Users\[사용자명]\AppData\Roaming\Cursor\User\workspaceStorage\`
3. 프로젝트 폴더명(`mirae8440`)과 관련된 폴더 찾기
4. 해당 폴더 삭제
5. Cursor 재시작

#### 방법 B: Cursor 설정에서 캐시 초기화
1. Cursor에서 `Ctrl+Shift+P` (또는 `Cmd+Shift+P` on Mac)
2. "Developer: Reload Window" 실행
3. 그래도 안되면 "Developer: Clear Editor History" 실행

#### 방법 C: 워크스페이스 재설정
1. Cursor에서 `File > Close Folder`
2. `File > Open Folder`로 프로젝트 다시 열기
3. Cursor가 인덱싱을 다시 시작할 때까지 대기

### 3. SFTP 설정 확인

`.vscode/sftp.json` 파일 확인:
- 현재 설정은 정상 (원격 경로가 `/`로 설정되어 있음)
- SFTP 확장 프로그램이 로컬 경로를 올바르게 매핑하는지 확인

### 4. Git 히스토리 확인 (대소문자 변경 추적)

Windows는 기본적으로 대소문자를 구분하지 않으므로, Git에서 폴더 이름 변경이 제대로 추적되지 않을 수 있습니다.

```bash
# Git에서 대소문자 변경 확인
git status
git ls-files | grep -i qc

# 필요시 Git 설정 변경
git config core.ignorecase false
```

### 5. 추가 확인 사항

#### A. 파일 시스템 확인
- 실제로 `www/qc` 폴더가 존재하는지 확인
- `www/QC` 폴더가 남아있지 않은지 확인

#### B. Cursor 인덱싱 강제 재시작
1. `Ctrl+Shift+P`
2. "TypeScript: Restart TS Server" 실행 (PHP 프로젝트이지만 인덱싱에 도움)
3. 또는 "Developer: Reload Window"

#### C. 프로젝트 루트의 `.cursor` 폴더 확인
- 프로젝트 루트에 `.cursor` 폴더가 있다면 삭제
- 이 폴더는 Cursor의 프로젝트별 캐시를 저장

### 6. 최종 확인

변경 사항이 반영되었는지 확인:
- ✅ `www/qc/goal.php` - 모든 경로가 `qc/`로 변경됨
- ✅ `www/qc/prod_jamb.php` - 경로가 `qc/`로 변경됨
- ⚠️ 다른 파일에서 `QC/` 참조가 있는지 확인 필요

### 7. 추가로 확인할 파일들

다음 파일들도 확인하여 `QC/` 참조가 있는지 확인:
- `www/index.php`
- `www/index3.php`
- `www/on/GOOGLE_DRIVE_PATH_CONFIG.md` (문서 파일이므로 선택적)
- `tools/refactor_bootstrap_include.php` (주석이므로 선택적)

## 예상 결과

위 단계를 완료하면:
1. Cursor가 `qc` 폴더를 올바르게 인식
2. "Review next file" 버튼이 사라지거나 올바른 파일로 이동
3. SFTP 업로드가 `qc` 폴더에 정상적으로 저장

## 문제가 지속되는 경우

1. Cursor 완전 재설치 고려
2. 프로젝트를 새 폴더에 복사하여 다시 열기
3. Cursor 지원팀에 문의
