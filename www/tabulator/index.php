<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>직원 테이블</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabulator CSS -->
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h4>직원 테이블</h4>
        <button id="download-xlsx" class="btn btn-success mb-3">엑셀로 다운로드</button>
        <div id="example-table"></div>
    </div>

    <!-- Scripts loaded at the end of body -->
    <!-- Tabulator JS -->
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
    <!-- SheetJS (XLSX export에 필수) - 더 안정적인 CDN 사용 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // XLSX 라이브러리 로드 확인 및 대기
        function waitForXLSX(callback, maxAttempts = 10) {
            let attempts = 0;
            
            function checkXLSX() {
                attempts++;
                if (typeof XLSX !== 'undefined') {
                    console.log('XLSX library loaded successfully');
                    callback();
                } else if (attempts < maxAttempts) {
                    console.log(`Waiting for XLSX library... (attempt ${attempts}/${maxAttempts})`);
                    setTimeout(checkXLSX, 500);
                } else {
                    console.error('XLSX library failed to load after multiple attempts');
                    alert('XLSX 라이브러리 로드에 실패했습니다. 페이지를 새로고침해주세요.');
                }
            }
            
            checkXLSX();
        }

        // DOM이 로드된 후 실행
        document.addEventListener('DOMContentLoaded', function() {
            waitForXLSX(function() {
                // 샘플 데이터
                const tableData = [
                    {id:1, name:"홍길동", age:30, gender:"남"},
                    {id:2, name:"김영희", age:28, gender:"여"},
                    {id:3, name:"박철수", age:35, gender:"남"}
                ];

                // 테이블 생성
                const table = new Tabulator("#example-table", {
                    data: tableData,
                    layout: "fitColumns",
                    columns: [
                        {title: "ID", field: "id", width: 60},
                        {title: "이름", field: "name"},
                        {title: "나이", field: "age"},
                        {title: "성별", field: "gender"},
                    ],
                });

                // 엑셀 다운로드
                document.getElementById("download-xlsx").addEventListener("click", function () {
                    try {
                        if (typeof XLSX === 'undefined') {
                            alert('XLSX 라이브러리가 로드되지 않았습니다.');
                            return;
                        }
                        table.download("xlsx", "직원_목록.xlsx", {sheetName: "직원정보"});
                    } catch (error) {
                        console.error('Excel download error:', error);
                        alert('엑셀 다운로드 중 오류가 발생했습니다: ' + error.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
