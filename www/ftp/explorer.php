<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>파일 드래그 앤 드롭 업로드</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .drop-zone {
            width: 500px;
            height: 300px;
            background-color: #f0f8ff;
            border: 3px dashed #ccc;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .drop-zone-dragenter,
        .drop-zone-dragover {
            border-color: #007bff;
            background-color: #e7f3ff;
        }
        
        .drop-zone p {
            margin: 5px;
            padding: 5px 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        input[type="file"] {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>파일 드래그 앤 드롭 업로드</h2>
    
    <form>
        <input type="file" id="file" multiple>
        
        <div class="drop-zone">
            <p>또는 파일을 여기로 드래그하세요.</p>
        </div>
    </form>

    <script>
        (function() {
            'use strict';
            
            // DOM 요소 선택
            var $file = document.getElementById("file");
            var dropZone = document.querySelector(".drop-zone");
            
            // null 체크
            if (!$file || !dropZone) {
                console.error("필수 DOM 요소를 찾을 수 없습니다.");
                return;
            }
            
            /**
             * CSS 클래스 토글 함수
             * @param {string} className - 현재 이벤트 이름
             */
            var toggleClass = function(className) {
                console.log("current event: " + className);
                
                var list = ["dragenter", "dragleave", "dragover", "drop"];
                
                for (var i = 0; i < list.length; i++) {
                    if (className === list[i]) {
                        dropZone.classList.add("drop-zone-" + list[i]);
                    } else {
                        dropZone.classList.remove("drop-zone-" + list[i]);
                    }
                }
            };
            
            /**
             * 파일 목록 표시 함수
             * @param {FileList} files - 파일 목록
             */
            var showFiles = function(files) {
                if (!files || files.length === 0) {
                    dropZone.innerHTML = "<p>또는 파일을 여기로 드래그하세요.</p>";
                    return;
                }
                
                dropZone.innerHTML = "";
                
                for (var i = 0, len = files.length; i < len; i++) {
                    var fileName = files[i].name;
                    var fileSize = (files[i].size / 1024).toFixed(2) + " KB";
                    dropZone.innerHTML += "<p>" + fileName + " (" + fileSize + ")</p>";
                }
            };
            
            /**
             * 파일 선택 함수
             * @param {FileList} files - 선택된 파일 목록
             */
            var selectFile = function(files) {
                // input file 영역에 드랍된 파일들로 대체
                try {
                    $file.files = files;
                    showFiles($file.files);
                } catch (ex) {
                    console.error("파일 할당 실패:", ex);
                    // 일부 브라우저에서 files 할당이 안될 수 있음
                    showFiles(files);
                }
            };
            
            // 파일 input 변경 이벤트
            $file.addEventListener("change", function(e) {
                showFiles(e.target.files);
            });
            
            // 드래그한 파일이 최초로 진입했을 때
            dropZone.addEventListener("dragenter", function(e) {
                e.stopPropagation();
                e.preventDefault();
                toggleClass("dragenter");
            });
            
            // 드래그한 파일이 dropZone 영역을 벗어났을 때
            dropZone.addEventListener("dragleave", function(e) {
                e.stopPropagation();
                e.preventDefault();
                toggleClass("dragleave");
            });
            
            // 드래그한 파일이 dropZone 영역에 머물러 있을 때
            dropZone.addEventListener("dragover", function(e) {
                e.stopPropagation();
                e.preventDefault();
                toggleClass("dragover");
            });
            
            // 드래그한 파일이 드랍되었을 때
            dropZone.addEventListener("drop", function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                toggleClass("drop");
                
                var files = e.dataTransfer && e.dataTransfer.files;
                console.log(files);
                
                if (files && files.length > 0) {
                    selectFile(files);
                } else {
                    alert("파일을 드롭하는 중 오류가 발생했습니다.");
                }
            });
            
        })();
    </script>
</body>
</html>
