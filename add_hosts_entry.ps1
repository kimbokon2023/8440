# hosts 파일에 8440.local 자동 추가 스크립트
# PowerShell을 관리자 권한으로 실행해야 합니다

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"

# 현재 hosts 파일 내용 읽기
$hostsContent = Get-Content $hostsPath -Raw

# 이미 8440.local이 있는지 확인
if ($hostsContent -match "8440\.local") {
    Write-Host "✅ 8440.local이 이미 hosts 파일에 있습니다!" -ForegroundColor Green
    exit 0
}

# 추가할 내용
$newEntry = @"

# 미래8440 로컬 개발 환경
127.0.0.1    8440.local
127.0.0.1    www.8440.local
"@

# hosts 파일에 추가
try {
    Add-Content -Path $hostsPath -Value $newEntry -Encoding UTF8
    Write-Host "✅ hosts 파일에 8440.local을 추가했습니다!" -ForegroundColor Green
    
    # DNS 캐시 초기화
    ipconfig /flushdns | Out-Null
    Write-Host "✅ DNS 캐시를 초기화했습니다!" -ForegroundColor Green
    
    # 연결 테스트
    Write-Host "`n🔍 연결 테스트 중..." -ForegroundColor Yellow
    $pingResult = Test-Connection -ComputerName "8440.local" -Count 1 -Quiet
    
    if ($pingResult) {
        Write-Host "✅ 8440.local 연결 성공!" -ForegroundColor Green
        Write-Host "`n🎉 설정 완료! 이제 http://8440.local 로 접속할 수 있습니다." -ForegroundColor Cyan
    } else {
        Write-Host "⚠️  연결 테스트 실패. 수동으로 확인이 필요합니다." -ForegroundColor Yellow
    }
    
} catch {
    Write-Host "❌ 오류: $_" -ForegroundColor Red
    Write-Host "💡 PowerShell을 관리자 권한으로 실행해주세요." -ForegroundColor Yellow
    exit 1
}

