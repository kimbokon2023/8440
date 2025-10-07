<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>[JQuery] 모바일 카메라 찍기</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
    핸드폰에서 찍고 확인해야 함!!<br>
	   <div id="photozone">
		<img src="" id="photoImg" width="50%" style="display:none;">
		</div>
		<!-- 
				특정확장자만 선택시 : accept=".jpg,.jpeg,.png,.gif,.bmp" 
				input file 사용시 여러파일 동시에 선택하기 : <input type="file" multiple>  multiple 만 써 주면 된다.
			-->
		<input type="file" name="photoFile" id="photoFile" accept="image/*" capture="camera" style="display:none;" multiple >
		<br>
		<input type="button" value="카메라">
		<input type="text" id='display' size=50>
		<br>
		<div id="result"></div>
</body>
</html>
<script>
window.onload = function() {
    // 카메라 버튼
    $("input[type=button]").click(function() {
      $("#photoFile").click();
    });

    // 사진 선택 후
    $("#photoFile").on('change', function() {

      // 파일명만 추출
      if(window.FileReader){  // modern browser
        var filename = $(this)[0].files[0].name;
      } else {  // old IE
        var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
      }

      // var fileSize = document.getElementById("photoFile").files[0].size;
      // console.log( "파일사이즈 : " + $("#photoFile")[0].files[0].size );
      console.log( "파일사이즈 : " + $(this)[0].files[0].size );
      console.log( "파일명 : " + filename );

      LoadImg($("#photoFile")[0]);
	  
	  // $('#photozone').append($("#photoImg").html());
	  
    });
}

// 선택이미지 미리보기
function LoadImg(value) {
    if(value.files && value.files[0]) {

      var reader = new FileReader();

      reader.onload = function (e) {
       img = $('#photoImg').attr('src', e.target.result);
        $('#photoImg').show();
		$(img).appendTo("#photozone");
      }

      reader.readAsDataURL(value.files[0]);
    }
	
	$('#display').val(value.files[0]);
}
</script>