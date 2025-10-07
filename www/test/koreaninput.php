<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>한글 입력 감지 (초성, 중성, 종성 포함) - 화면 출력</title>
</head>
<body>
  <input type="text" id="inputField" placeholder="여기에 입력하세요">
  <input type="text" id="outputField" readonly>
  <script>
    const inputField = document.getElementById('inputField');
    const outputField = document.getElementById('outputField');

    let compositionData = '';

    inputField.addEventListener('compositionstart', (event) => {
      compositionData = event.data;
	  outputField.value = `한글 입력 감지: ${compositionData}`;
    });

    inputField.addEventListener('compositionupdate', (event) => {
      compositionData = event.data;
      outputField.value = `한글 입력 감지: ${compositionData}`;
    });

    inputField.addEventListener('compositionend', (event) => {
      compositionData = event.data;
      outputField.value = `한글 입력 감지: ${compositionData}`;
    });

  </script>
</body>
</html>
