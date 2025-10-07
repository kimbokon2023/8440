<html>
	<head>

  
<!-- tree grid로 upgrage -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/extensions/treegrid/bootstrap-table-treegrid.min.js"></script>

<link rel="stylesheet" href="../js/jquery-treegrid-master/css/jquery.treegrid.css">
<script src="../js/jquery-treegrid-master/js/jquery.treegrid.min.js"></script>

<link rel="stylesheet" href="css/style2.css">
  
<script src="http://8440.co.kr/common.js"></script>  	
	
</head>
	
		<title>File Test</title>
		<script type='text/javascript'>
			// //1MB(메가바이트)는 1024KB(킬로바이트)
			// var maxSize = 2048;
			
			// function fileCheck() {
				// //input file 태그.
				// var file = document.getElementById('fileInput');
				// //파일 경로.
				// var filePath = file.value;
				// var filePath2 = file[0].value;
				// //전체경로를 \ 나눔.
				// var filePathSplit = filePath.split('\\'); 
				// //전체경로를 \로 나눈 길이.
				// var filePathLength = filePathSplit.length;
				// //마지막 경로를 .으로 나눔.
				// var fileNameSplit = filePathSplit[filePathLength-1].split('.');
				// //파일명 : .으로 나눈 앞부분
				// var fileName = fileNameSplit[0];
				// //파일 확장자 : .으로 나눈 뒷부분
				// var fileExt = fileNameSplit[1];
				// //파일 크기
				// var fileSize = file.files[0].size;
				
				// console.log('파일 경로 : ' + filePath2);
				// console.log('파일명 : ' + fileName);
				// console.log('파일 확장자 : ' + fileExt);
				// console.log('파일 크기 : ' + fileSize);
			// }
		
$(document).ready(function(){
$('#userfile').change(function() {

// alert(this.value);
  readURL(this);
// $('#productImg').attr("src", $(this).val());

//   var file = document.getElementById('userfile');

//   var fileImg = file.getAttribute('value');

// alert(fileImg);

});
function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader(); //파일을 읽기 위한 FileReader객체 생성
					console.log(reader);
                    reader.onload = function (e) {
                    //파일 읽어들이기를 성공했을때 호출되는 이벤트 핸들러
                        $('#productImg').attr('src', e.target.result);
                        //이미지 Tag의 SRC속성에 읽어들인 File내용을 지정
                        //(아래 코드에서 읽어들인 dataURL형식)
                    }                   
                    reader.readAsDataURL(input.files[0]);
                    //File내용을 읽어 dataURL형식의 문자열로 저장
                }
            }//readURL()--
});			
			
</script>
	
	<body>
		<input type='file' id='fileInput'>
		<input type='button' value='확인' onclick='fileCheck()'>
		
예제소스
<input type="file"  name="userfile" id="userfile" style="width:210px;"/> 		
		
		
	</body>
</html>