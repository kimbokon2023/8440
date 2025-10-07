# 8440.local 로컬 개발 환경 설정 가이드

## ✅ 이미 완료된 작업

`environment.php`에 `8440.local`이 로컬 환경으로 자동 감지되도록 설정되어 있습니다!

---

## 🚀 빠른 설정 (3단계)

### 1️⃣ hosts 파일 수정

#### Windows의 경우:

**방법 A: 메모장으로 수동 수정 (권장)**

1. **메모장을 관리자 권한으로 실행**
   - 시작 메뉴 → "메모장" 검색
   - 우클릭 → **"관리자 권한으로 실행"**

2. **파일 열기**
   - 파일 → 열기
   - 경로: `C:\Windows\System32\drivers\etc\hosts`
   - **중요**: 파일 형식을 **"모든 파일(*.*)"**로 변경!

3. **파일 맨 아래에 추가**
   ```
   # 미래8440 로컬 개발 환경
   127.0.0.1    8440.local
   127.0.0.1    www.8440.local
   ```

4. **저장** (Ctrl + S)

---

**방법 B: PowerShell로 자동 추가**

PowerShell을 **관리자 권한**으로 실행 후:

```powershell
# hosts 파일에 자동 추가
Add-Content -Path "C:\Windows\System32\drivers\etc\hosts" -Value "`n# 미래8440 로컬 개발 환경`n127.0.0.1    8440.local`n127.0.0.1    www.8440.local"

# 확인
Get-Content "C:\Windows\System32\drivers\etc\hosts" | Select-Object -Last 5
```

---

### 2️⃣ DNS 캐시 초기화

PowerShell (관리자 권한):

```powershell
ipconfig /flushdns
```

✅ **"DNS 확인자 캐시를 플러시했습니다."** 메시지가 나오면 성공!

---

### 3️⃣ 연결 테스트

PowerShell:

```powershell
ping 8440.local
```

✅ **응답: 127.0.0.1**이 나오면 성공!

---

## 🎯 사용 방법

### PHP 내장 서버 실행

```powershell
cd C:\Project\mirae8440\www
php -S 127.0.0.1:80
```

### 브라우저 접속

```
http://8440.local
```

또는 테스트 페이지:

```
http://8440.local/test_environment.php
```

---

## 🔍 환경 구분

| 도메인 | 환경 | 설명 |
|--------|------|------|
| `8440.local` | 🖥️ 로컬 | 개발 환경 (자동 감지됨) |
| `localhost` | 🖥️ 로컬 | 개발 환경 (자동 감지됨) |
| `8440.co.kr` | 🌐 서버 | 운영 환경 (자동 감지됨) |
| `jtechel.local` | 🖥️ 로컬 | 다른 프로젝트 (충돌 없음) |

---

## 📁 프로젝트 구분

```
로컬 개발:
├── jtechel.local  → jtechel 프로젝트
└── 8440.local     → mirae8440 프로젝트 (이 프로젝트)

운영 서버:
├── j-techel.co.kr → jtechel 운영 서버
└── 8440.co.kr     → mirae8440 운영 서버
```

---

## ⚙️ 포트 설정 (선택사항)

### 포트 80 사용 (권장)

```powershell
php -S 127.0.0.1:80
```

접속: `http://8440.local`

### 다른 포트 사용 (포트 충돌 시)

```powershell
php -S 127.0.0.1:8000
```

접속: `http://8440.local:8000`

---

## 🛠️ 문제 해결

### ❌ "페이지를 찾을 수 없음" 오류

1. **hosts 파일 확인**
   ```powershell
   Get-Content "C:\Windows\System32\drivers\etc\hosts"
   ```
   
2. **DNS 캐시 초기화**
   ```powershell
   ipconfig /flushdns
   ```

3. **ping 테스트**
   ```powershell
   ping 8440.local
   ```

### ❌ PHP 서버 시작 오류

1. **포트 충돌 확인**
   ```powershell
   netstat -ano | findstr :80
   ```

2. **다른 포트 사용**
   ```powershell
   php -S 127.0.0.1:8000
   ```

### ❌ 환경이 잘못 감지됨

`test_environment.php` 접속해서 환경 확인:
```
http://8440.local/test_environment.php
```

**"🖥️ 로컬 개발 환경"**이 표시되어야 정상입니다.

---

## 🎉 완료!

이제 `8440.local`로 로컬 개발을 시작할 수 있습니다!

- ✅ 환경 자동 감지
- ✅ DB 설정 자동 적용
- ✅ URL 자동 생성
- ✅ jtechel.local과 충돌 없음

---

## 📚 다음 단계

1. **테스트**: http://8440.local/test_environment.php
2. **개발 시작**: 기존 PHP 파일들이 자동으로 로컬 환경 감지
3. **문서 확인**: www/config/README.md

