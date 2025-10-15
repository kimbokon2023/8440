<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>파일 체크 테스트</title>
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Bootstrap Table -->
    <link href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css" rel="stylesheet">
    <script src="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.0/dist/extensions/treegrid/bootstrap-table-treegrid.min.js"></script>
    
    <!-- jQuery TreeGrid -->
    <link rel="stylesheet" href="../js/jquery-treegrid-master/css/jquery.treegrid.css">
    <script src="../js/jquery-treegrid-master/js/jquery.treegrid.min.js"></script>
    
    <!-- Custom Style -->
    <link rel="stylesheet" href="css/style2.css">
    
    <!-- Common JS (로컬/서버 호환) -->
    <script>
        // 동적으로 common.js 로드
        (function() {
            var baseUrl = window.location.protocol + '//' + window.location.host;
            var script = document.createElement('script');
            script.src = baseUrl + '/common.js';
            script.onerror = function() {
                console.warn('common.js 로드 실패 (선택사항)');
            };
            document.head.appendChild(script);
        })();
    </script>
    
    <style>
        body {
            padding: 20px;
        }
        
        .file-preview {
            margin-top: 20px;
            max-width: 500px;
        }
        
        #productImg {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
    
    <script type='text/javascript'>
        $(document).ready(function() {
            'use strict';
            
            /**
             * 파일 정보 확인 함수
             */
            function fileCheck() {
                // input file 태그
                var file = document.getElementById('fileInput');
                
                if (!file || !file.files || file.files.length === 0) {
                    alert('파일을 선택해주세요.');
                    return;
                }
                
                // 파일 경로
                var filePath = file.value;
                
                // 전체경로를 \ 나눔
                var filePathSplit = filePath.split('\\');
                
                // 전체경로를 \로 나눈 길이
                var filePathLength = filePathSplit.length;
                
                // 마지막 경로를 .으로 나눔
                var fileNameSplit = filePathSplit[filePathLength - 1].split('.');
                
                // 파일명: .으로 나눈 앞부분
                var fileName = fileNameSplit[0];
                
                // 파일 확장자: .으로 나눈 뒷부분
                var fileExt = fileNameSplit[1] || '';
                
                // 파일 크기
                var fileSize = file.files[0].size;
                var fileSizeKB = (fileSize / 1024).toFixed(2);
                var fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
                
                // 최대 크기 체크 (2MB)
                var maxSize = 2048; // KB
                if (fileSize / 1024 > maxSize) {
                    alert('파일 크기가 ' + maxSize + 'KB(약 2MB)를 초과합니다.\n현재 크기: ' + fileSizeKB + 'KB (' + fileSizeMB + 'MB)');
                    return;
                }
                
                // 콘솔 출력
                console.log('파일 경로: ' + filePath);
                console.log('파일명: ' + fileName);
                console.log('파일 확장자: ' + fileExt);
                console.log('파일 크기: ' + fileSize + ' bytes (' + fileSizeKB + ' KB)');
                
                // 사용자에게 정보 표시
                alert('파일 정보:\n' +
                      '파일명: ' + fileName + '\n' +
                      '확장자: ' + fileExt + '\n' +
                      '크기: ' + fileSizeKB + ' KB (' + fileSizeMB + ' MB)');
            }
            
            /**
             * 이미지 미리보기 함수
             * @param {HTMLInputElement} input - 파일 input 요소
             */
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader(); // 파일을 읽기 위한 FileReader 객체 생성
                    
                    console.log('FileReader 생성:', reader);
                    
                    reader.onload = function(e) {
                        // 파일 읽어들이기를 성공했을때 호출되는 이벤트 핸들러
                        $('#productImg').attr('src', e.target.result);
                        // 이미지 Tag의 SRC속성에 읽어들인 File내용을 지정
                    };
                    
                    reader.onerror = function(e) {
                        console.error('파일 읽기 오류:', e);
                        alert('파일을 읽는 중 오류가 발생했습니다.');
                    };
                    
                    reader.readAsDataURL(input.files[0]);
                    // File내용을 읽어 dataURL형식의 문자열로 저장
                }
            }
            
            // userfile input 변경 이벤트
            $('#userfile').change(function() {
                readURL(this);
            });
            
            // fileCheck 함수를 전역으로 노출
            window.fileCheck = fileCheck;
        });
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2>파일 체크 테스트</h2>
        
        <div class="card mt-3">
            <div class="card-header">
                파일 정보 확인
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="fileInput">파일 선택 (크기 체크):</label>
                    <input type='file' id='fileInput' class="form-control-file">
                    <button type='button' class="btn btn-primary mt-2" onclick='fileCheck()'>파일 정보 확인</button>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                이미지 미리보기
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="userfile">이미지 선택 (미리보기):</label>
                    <input type="file" name="userfile" id="userfile" class="form-control-file" accept="image/*">
                </div>
                
                <div class="file-preview">
                    <img id="productImg" src="" alt="이미지 미리보기" style="display: none;">
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // 이미지 로드 시 표시
        $('#productImg').on('load', function() {
            if (this.src && this.src !== window.location.href) {
                $(this).show();
            }
        });
    </script>
</body>
</html>
