$(document).ready(function(){
    // $("#myfiles").change(function(){
    // $("form").submit();
    // });	
});	

function getRealPath(obj){
 obj.select();

 // document.selection.createRange().text.toString(); 이게 실행이 안된다면....

 // document.selection.createRangeCollection()[0].text.toString(); 이걸로....
 $('#real_path').value = document.selection.createRangeCollection()[0].text.toString();
 // $('#real_path').value = document.selection.createRangeCollection()[0].text.toString();
 // $('#real_path').value = document.selection.createRangeCollection()[0].text.toString();

 console.log($('#real_path').value);

}


function fileTypeCheck(obj) {

	pathpoint = obj.value.lastIndexOf('.');

	filepoint = obj.value.substring(pathpoint+1,obj.length);

	filetype = filepoint.toLowerCase();

    var input = document.memberform.file;
    var fReader = new FileReader();
    fReader.readAsDataURL(input.files[0]);
    fReader.onloadend = function(event) {
        document.memberform.image.src = event.target.result;
    }

	if(filetype=='xml') {
        console.log(pathpoint);
        console.log(filepoint);
        console.log(obj.value);
        console.log(document.memberform.image.src);
		// 정상적인 이미지 확장자 파일인 경우

	} else {

		alert('Only xml file can be uploaded!');

		parentObj  = obj.parentNode

		node = parentObj.replaceChild(obj.cloneNode(true),obj);

		return false;

	}

}




// var pullfiles=function(){
//     // love the query selector
//     var fileInput = document.querySelector("#myfiles");
//     var files = fileInput.files;
//     const FileName = document.querySelector("#inputValue");
//     // cache files.length
//     var fl = files.length;
//     var i = 0;

//     while ( i < fl) {
//         // localize file var in the loop
//         var file = files[i];
//         FileName.value = file.name.replace('.xml','');
//         i++;
//     }
// }

// // set the input element onchange to call pullfiles
// document.querySelector("#myfiles").onchange=pullfiles;

//a.t
