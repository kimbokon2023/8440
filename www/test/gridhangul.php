<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grid Example</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }
  </style>
</head>
<body>
  <table id="grid">
    <thead>
      <tr>
        <th>Column 1</th>
        <th>Column 2</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
      </tr>
      <tr>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
      </tr>
    </tbody>
  </table>
  <script >
  
  const cells = document.querySelectorAll('td');

cells.forEach(cell => {
  cell.addEventListener('input', (event) => {
    const target = event.target;
    const temp = document.createElement('div');
    temp.innerHTML = target.innerHTML;

    // 한글이 완성되면 텍스트를 가져옵니다.
    const text = temp.textContent || temp.innerText;

    // 셀의 내용을 완성된 텍스트로 변경합니다.
    target.innerHTML = text;
  });
});

  
  
  </script>
</body>
</html>
