<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
   
    if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
if($check==null)
		$check='1';
	
$URLsave = "https://8440.co.kr/ceiling/showhole.php";	
	
 ?>
  
  <?php include getDocumentRoot() . '/load_header.php' ?>

<title>미래기업 조명천장 홀타공</title>
</head>

<style>
  .fill {
    object-fit: fill;
  }

  .contain {
    object-fit: contain;
  }

  .cover {
    width: auto;
    height: auto;
    object-fit: cover;
  }

  .img {
    width: auto;
    height: auto;
  }
  
  .pdf-container {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
  }

  .pdf-embed {
    width: 100%;
    height: 800px; /* 원하는 높이로 조절 가능 */
  }
  
</style>

<body>
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <div class="d-flex mb-1 mt-1 justify-content-center align-items-center ">
          <button type="button" class="btn btn-secondary " onclick="self.close();return false;">닫기</button><br><br>
          &nbsp;&nbsp;&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('1')">011_012_013_017_N20</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('2')">034_026</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('3')">031</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('4')">032</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('5')">035</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('6')">036</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('7')">037</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('8')">038</button>&nbsp;
          <button type="button" id="urlsave" class="btn btn-outline-primary mt-2 mb-2">URL Copy</button>&nbsp;
          <input type="type" name="URL" id="URL" value="<?=$URLsave?>" style="width:10px;">
          &nbsp;
        </div>
        <div class="d-flex mb-3 mt-3 justify-content-center align-items-center ">
          <span class="text-center fs-1" style="color:grey;">조명천장 홀타공</span>
        </div>

		<?php
		  $pdf_arr = array('011_012_013_017_N20', '034_026', '031', '032', '035', '036', '037_new', '038');
		  for ($i = 0; $i < count($pdf_arr); $i++) {
			echo '<div class="d-flex mb-3 mt-3 justify-content-center align-items-center">';
			echo '<span class="text-center fs-3">(' . $pdf_arr[$i] . ') 모델 홀타공도</span>';
			echo '</div>';
			echo '<div class="d-flex mb-3 mt-3 justify-content-center align-items-center">';
			echo '<div id="div' . ($i + 1) . '" class="pdf-container">';
			echo '<embed src="./holedwg/' . $pdf_arr[$i] . '.pdf" type="application/pdf" class="pdf-embed">';
			echo '</div></div>';
		  }
		?>


      </div> <!-- end of card-body -->
    </div> <!-- end of card -->
  </div> <!-- end of container -->
</body>
</html>

<script>
  function fnMove(seq) {
    var offset = $("#div" + seq).offset();
    $('html, body').animate({
      scrollTop: offset.top
    }, 400);
  }

  $(document).ready(function () {

    $("#urlsave").click(function () {
      var content = document.getElementById('URL');

      content.select();
      console.log(document.execCommand('copy'));

      // Toastify를 사용하여 토스트 메시지 표시
      Toastify({
        text: "URL이 복사되었습니다. 붙여넣기 하세요",
        duration: 3000, // 토스트 메시지의 지속 시간 (3초)
        close: true,
        gravity: "top", // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`			
      }).showToast();
    });
  })
</script>
