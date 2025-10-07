<style>
.spinner {
  display: inline-block;
  width: 40px;
  height: 40px;
  border: 4px solid rgba(0, 0, 0, 0.2);
  border-top-color: #333;
  border-radius: 50%;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

.spinner-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.5);
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
  display: none;
}

</style>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>PHP 페이지</title>
	<!-- CSS 파일 추가 -->
	<link rel="stylesheet" href="spinner.css">
	<!-- JavaScript 추가 -->
	<script type="text/javascript">
		// 페이지가 로딩될 때 실행되는 함수
		window.onload = function() {
			// 스피너를 감싸는 요소를 가져옵니다.
			var spinnerWrapper = document.getElementById('spinner-wrapper');
			// 스피너를 표시합니다.
			spinnerWrapper.style.display = 'flex';
			// 페이지 로딩이 완료되면 스피너를 숨깁니다.
			window.setTimeout(function() {
				spinnerWrapper.style.display = 'none';
			}, 1000); // 1초 후에 실행
		};
	</script>
</head>
<body>
	<!-- 스피너를 감싸는 요소 추가 -->
	<div id="spinner-wrapper">
		<div class="spinner"></div>
	</div>

	<!-- 페이지 내용 추가 -->
	<h1>PHP 페이지</h1>
	<p>내용입니다.</p>
</body>
</html>





