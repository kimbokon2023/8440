<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
   
if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
if($check==null)
		$check='1';
	
$URLsave = "https://8440.co.kr/ceiling/showcatalog.php";	

$title_message ='미래기업 조명천장 카다로그';
	
 ?>
  
  <?php include getDocumentRoot() . '/load_header.php' ?>

<title> <?=$title_message?> </title>
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
        <div class="d-flex mb-4 mt-4 justify-content-center align-items-center ">
          <span class="text-center fs-2" style="color:grey;">  <?=$title_message?>  </span>
        </div>
        <div class="d-flex mb-4 mt-1 justify-content-center align-items-center ">          
          <button type="button" class="btn btn-dark " onclick="fnMove('1')">011</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('2')">012</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('3')">013</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('4')">017</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('5')">026</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('6')">031</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('7')">032</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('8')">034</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('9')">035</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('10')">036</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('11')">050</button>&nbsp;
          <button type="button" class="btn btn-dark " onclick="fnMove('12')">N20</button>&nbsp;
          <button type="button" id="urlsave" class="btn btn-outline-primary mt-2 mb-2">URL Copy</button>&nbsp;
          <input type="type" name="URL" id="URL" value="<?=$URLsave?>" style="width:10px;">          
		  <button type="button" class="btn btn-secondary mx-3" onclick="self.close();return false;"> &times 닫기</button>
        </div>
		<?php
		$pdf_arr = array('011', '012', '013', '017', '026', '031', '032', '034', '035', '036', '050', 'N20');
		
		for ($i = 0; $i < count($pdf_arr); $i++) {
			echo '<div class="card mb-5 mt-5">';
			echo '  <div class="card-header text-center">';
			echo '    <h3> LC-' . $pdf_arr[$i] . ' 모델</h3>';
			echo '  </div>';
			echo '  <div class="card-body">';
			echo '    <div id="div' . ($i + 1) . '" class="pdf-container text-center">';
			echo '      <embed src="./catalog/' . $pdf_arr[$i] . '.pdf" type="application/pdf" class="pdf-embed">';
			echo '    </div>';
			echo '  </div>';
			echo '</div>';
		}
		?>

      </div> 
      </div> <!-- end of card-body -->
    </div> <!-- end of card -->
  </div> <!-- end of container -->
  
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
  
  $(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   saveMenuLog(title);
});	

</script>



</body>
</html>