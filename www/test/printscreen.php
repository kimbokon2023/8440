<?php

if($_REQUEST['mode']=='paste'){

	if( isset( $_FILES['file'] ) ) {

		$file_contents = file_get_contents( $_FILES['file']['tmp_name'] );

		header("Content-Type: " . $_FILES['file']['type'] );

		die($file_contents);

	}

	else {

		header("HTTP/1.1 400 Bad Request");

	}

}

?>



<!DOCTYPE html>

<html>

<head>
<title> 윈도우 캡쳐후 이미지 붙여넣기 구현 </title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<style>

.output{float:left;padding:10px;width:49%;height:300px;text-align:center;overflow:hidden;border:1px solid #ccc;background-color:#EBFBFF;}

.output::before{content:"[base64] 화면 캡쳐후 붙여넣기 해주세요.(Print Screen -> Ctrl+V)";display:inline-block;margin-top:5%;}

.output.paste{height:auto;}

.output.paste::before{display:none}

.output img{max-width:100%;}



#output2.output{float:right;}

#output2.output::before{content:"[blob] 화면 캡쳐후 붙여넣기 해주세요.(Print Screen -> Ctrl+V)"}

</style>

<script>

$(function(){

	var $output=document.querySelector("#output")

		,$output2=document.querySelector("#output2");

	// base64 encode 되어 브라우저가 읽어 들임

	// https://stackoverflow.com/questions/6333814/how-does-the-paste-image-from-clipboard-functionality-work-in-gmail-and-google-c

	$output.onpaste = function(event){

		var items = (event.clipboardData || event.originalEvent.clipboardData).items;

		//console.log(JSON.stringify(items)); // will give you the mime types

		for (index in items) {

			var item = items[index];

			if (item.kind === 'file') {

				var blob = item.getAsFile();

				var reader = new FileReader();



				// data url!

				reader.readAsDataURL(blob);

				reader.onload = function(event){

					$output.className="output paste";					

					var img=new Image();

					img.src=event.target.result;

					$output.appendChild(img);

				}; 

			}

		}

	}





	// blob 타입 이미지로 서버에서 직접 읽어들임

	// https://stackoverflow.com/questions/18055422/how-to-receive-php-image-data-over-copy-n-paste-javascript-with-xmlhttprequest

	$output2.onpaste = function (e) {

		var items = e.clipboardData.items;

		var files = [];

		for( var i = 0, len = items.length; i < len; ++i ) {

			var item = items[i];

			if( item.kind === "file" ) {

				submitFileForm(item.getAsFile(), "paste");

			}

		}
		
	};


	function submitFileForm(file, type) {

		var extension = file.type.match(/\/([a-z0-9]+)/i)[1].toLowerCase();

		var formData = new FormData();

		formData.append('file', file, "image_file");

		formData.append('mode', 'paste' );

		formData.append('extension', extension );

		formData.append("mimetype", file.type );

		formData.append('submission-type', type);

		var xhr = new XMLHttpRequest();

		xhr.responseType = "blob";

		xhr.open('POST', '<?php echo basename(__FILE__); ?>');

		xhr.onload = function () {

			if (xhr.status == 200) {

				$output2.className="output paste";

				var img = new Image()

					,url=(window.URL || window.webkitURL);

				img.src = url.createObjectURL( xhr.response );				

				img.onload=function(e){

					url.revokeObjectURL(this.src);		// blob 메모리 해제

				};

				$output2.appendChild(img);

			}

		};

		xhr.send(formData);

	}











    

});

</script>
</head>

<body>
    <div class="container-fluid">
        <h1>Paste  Capture 'Image' </h1>
        <div id="output" class="output"></div>
        <div id="output2" class="output"></div>		
    </div>
</body>

</html>