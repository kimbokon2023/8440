<?php
require_once __DIR__ . '/../bootstrap.php';
include includePath('load_header.php'); 
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <title>미래기업 코딩스쿨</title>
  <link rel="stylesheet" href="./css/lessons_style.css?ver=1">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
    }
    .sidebar {
      background-color: #f8f9fa;
      height: 100vh;
      border-right: 1px solid #ddd;
      padding: 20px;
      overflow-y: auto;
    }
    .sidebar h5 {
      margin-top: 20px;
      margin-bottom: 10px;
      color: #555;
      font-weight: bold;
    }
    .sidebar .nav-link {
      font-weight: 600;
      color: #333;
    }
    .sidebar .nav-link:hover {
      color: #007bff;
    }
    iframe {
      width: 100%;
      height: 100vh;
      border: none;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- 왼쪽 메뉴 -->
    <nav class="col-md-2 sidebar">
      <h5>📚 웹</h5>
      <ul class="nav flex-column mb-4">
        <li class="nav-item"><a class="nav-link" href="lessons/html.php" target="contentFrame">HTML 강좌</a></li>
        <li class="nav-item"><a class="nav-link" href="lessons/css.php" target="contentFrame">CSS 강좌</a></li>
        <li class="nav-item"><a class="nav-link" href="lessons/javascript.php" target="contentFrame">JavaScript 강좌</a></li>
        <li class="nav-item"><a class="nav-link" href="lessons/jquery.php" target="contentFrame">Jquery(제이쿼리) 강좌</a></li>
        <li class="nav-item"><a class="nav-link" href="lessons/php.php" target="contentFrame">PHP 강좌</a></li>
        <li class="nav-item"><a class="nav-link" href="lessons/sql.php" target="contentFrame">SQL 강좌</a></li>
      </ul>

      <h5>🛠 응용프로그램</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="lessons/python.php" target="contentFrame">Python 강좌</a></li>              
		<li class="nav-item">
		  <a class="nav-link mt-3" href="https://www.youtube.com/watch?v=GjawjeSDRM0&list=PLS4D8xUyesvcgvy6d9vFjJpRiUFZblaai" target="_blank">
			<h6> <i class="bi bi-youtube"></i>  운전하면 듣는 코딩 팟캐스트</h6> 
		  </a>
		</li>
		<li class="nav-item"><a class="nav-link" href="lessons/git.php" target="contentFrame">git clone</a></li>
		<li class="nav-item"><a class="nav-link" href="lessons/tabulator.php" target="contentFrame">tabulator가 Jquery Datatables 비교</a></li>
      </ul>
    </nav>  

    <!-- 오른쪽 본문 -->
    <main class="col-md-10 p-0">
      <iframe name="contentFrame" src="lessons/html.php"></iframe>
    </main>
  </div>
</div>

</body>
</html>
 