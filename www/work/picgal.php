<meta charset="utf-8">
<!-- Light & Subtle Theme CSS -->
<link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />

<style>
/* Photo Gallery Specific Styles - Light & Subtle Theme */
.gallery-table {
    border-collapse: collapse;
    text-align: center;
    width: 100%;
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--dashboard-shadow);
}

.gallery-table th {
    background: var(--dashboard-secondary);
    color: var(--dashboard-text);
    height: 60px;
    border: none;
    padding: 1rem;
    font-weight: 500;
    font-size: 1.1rem;
}

.gallery-table td {
    border: none;
    border-bottom: 1px solid var(--dashboard-border);
    padding: 1.5rem;
    background: white;
}

.gallery-table tr:last-child td {
    border-bottom: none;
}

.gallery-badge {
    background: var(--gradient-accent);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 1rem;
    font-weight: 500;
    display: inline-block;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2);
}

.gallery-badge-before {
    background: var(--gradient-info);
    color: white;
}

.gallery-badge-after {
    background: var(--gradient-success);
    color: white;
}

.rotate-btn {
    background: var(--gradient-accent);
    color: white !important;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 0.8rem;
    margin-bottom: 2.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.15);
}

.rotate-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(100, 116, 139, 0.25);
    color: white !important;
    opacity: 0.9;
}

.rotate-btn i {
    font-size: 1rem;
}

.image-container {
    display: inline-block;
    margin-top: 1rem;
    margin-bottom: 2rem;
}

.image-container img {
    max-width: 400px;
    max-height: 300px;
    border-radius: 6px;
    transition: transform 0.3s ease;
}

.rotated {
    transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -webkit-transform: rotate(90deg);
    -o-transform: rotate(90deg);
}

.gallery-title {
    color: var(--dashboard-text);
    font-weight: 600;
    font-size: 1.5rem;
    margin-bottom: 2rem;
}

.workplace-header {
    background: var(--gradient-primary);
    color: var(--dashboard-text);
    font-size: 1.2rem;
    font-weight: 500;
    padding: 1rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .gallery-table {
        font-size: 0.9rem;
    }

    .gallery-table th,
    .gallery-table td {
        padding: 0.8rem;
    }

    .image-container img {
        max-width: 280px;
        max-height: 220px;
    }

    .gallery-title {
        font-size: 1.2rem;
    }
}
</style>

 <?php
 session_start(); 
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"]; 
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();	 
?>	 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> 미래기업 쟘 시공사진 모음 </title>
   
<?php include '../myheader.php'; ?>   

<div class="container"> 

<div class="modern-management-card">
  <div class="modern-dashboard-header">
    <h3 class="gallery-title text-center mb-0">Jamb 시공 Before & After 사진 모음 (최근100개 현장)</h3>
  </div>

  <div style="padding: 1.5rem;">
    <div class="d-flex justify-content-center"> 	     
        <input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	    <input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	                       

        <table class="gallery-table">
        <?php
            $img_arr = array();

            try{
                $sql = "select * from mirae8440.work order by filename1 desc";
                $stmh = $pdo->prepare($sql);  
                $stmh->bindValue(1, $num, PDO::PARAM_STR);      
                $stmh->execute();            
                $counter=0; 
                while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $workplacename=$row["workplacename"];   
                    $workday=$row["workday"];  
                    $worker=$row["worker"];

                    $filename1=$row["filename1"];
                    $filename2=$row["filename2"];
                    $imgurl1="../imgwork/" . $filename1;
                    $imgurl2="../imgwork/" . $filename2;		
					
					$wanttoshoepic = 100;

                    if($filename1!='' && $filename1!='pass' && $filename2!='' && $filename2!='pass' && $counter<$wanttoshoepic) {
                        array_push($img_arr, $imgurl1);
        ?>
                        <tr>
                            <th colspan="2" class="workplace-header">
                             현장명 : <?=$workplacename?> , 작업소장 : <?=$worker?>
                            </th>
                        </tr>
                        <tr>						 
                            <td>
                                <div style="text-align: center;">
                                    <div class="gallery-badge gallery-badge-before">시공 (전) Before 사진</div>
                                    <br>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="rotate-btn" id="rotate<?=$counter?>" onclick="rotateFn('before_work<?=$counter?>','<?=$imgurl1?>')">
                                            <i class="bi bi-arrow-clockwise"></i> 회전
                                        </button>
                                    </div>
                                    <div class="image-container">
                                    <?php
                                        if($filename1!="" && $filename1!="pass")
                                            print '<img id="before_work' . $counter . '" src="' . $imgurl1 . '" alt="시공 전 사진">';
                                    ?>
                                    </div>
                                </div>
                            </td>									
                            <td>
                                <div style="text-align: center;">
                                    <div class="gallery-badge gallery-badge-after">시공 (후) After 사진</div>
                                    <br>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="rotate-btn" id="rotate_after<?=$counter?>" onclick="rotateFn('after_work<?=$counter?>','<?=$imgurl2?>')">
                                            <i class="bi bi-arrow-clockwise"></i> 회전
                                        </button>
                                    </div>
                                    <div class="image-container">
                                    <?php
                                        if($filename2!="" && $filename2!="pass")
                                            print '<img id="after_work' . $counter . '" src="' . $imgurl2 . '" alt="시공 후 사진">';
                                    ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
        <?php
                        $counter++;		
		            }	     
	   	        }				
            }catch (PDOException $Exception) {
                print "오류: ".$Exception->getMessage();
            }   
        ?>
        </table>
        </div>
    </div>
</div>
</div>

 </body>
</html>    

 <script language="javascript">
 
var imgObj = new Image();
function showImgWin(imgName) {
imgObj.src = imgName;
setTimeout("createImgWin(imgObj)", 100);
}
function createImgWin(imgObj) {
if (! imgObj.complete) {
setTimeout("createImgWin(imgObj)", 100);
return;
}
imageWin = window.open("", "imageWin",
"width=" + imgObj.width + ",height=" + imgObj.height);
}

   function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function date_mask(formd, textid) {

/*
input onkeyup에서
formd == this.form.name
textid == this.name
*/

var form = eval("document."+formd);
var text = eval("form."+textid);

var textlength = text.value.length;

if (textlength == 4) {
text.value = text.value + "-";
} else if (textlength == 7) {
text.value = text.value + "-";
} else if (textlength > 9) {
//날짜 수동 입력 Validation 체크
var chk_date = checkdate(text);

if (chk_date == false) {
return;
}
}
}

function checkdate(input) {
   var validformat = /^\d{4}\-\d{2}\-\d{2}$/; //Basic check for format validity 
   var returnval = false;

   if (!validformat.test(input.value)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else { //Detailed check for valid date ranges 
    var yearfield = input.value.split("-")[0];
    var monthfield = input.value.split("-")[1];
    var dayfield = input.value.split("-")[2];
    var dayobj = new Date(yearfield, monthfield - 1, dayfield);
   }

   if ((dayobj.getMonth() + 1 != monthfield)
     || (dayobj.getDate() != dayfield)
     || (dayobj.getFullYear() != yearfield)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else {
    //alert ('Correct date'); 
    returnval = true;
   }
   if (returnval == false) {
    input.select();
   }
   return returnval;
  }
  
function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
}  

function copy_below(){	

  
}  

function del_below()
     {
  
}
     function del(href) 
     {

     }
	 
 function displayoutputlist(){
	 alert("dkdkdkd");
   $("#displayoutput").show(); 
   $("#displayoutput").load("./outputlist.php");	 	 
		 
	 }
 	 
// 사진 회전하기
function rotate_image()
{	
 var arr = <?php echo json_encode($img_arr);?> ;
 var box = $('.imagediv');
 var imgObj = new Image();
 
 box.css('width','800px');
 box.css('height','750px');
 box.css('margin-top','230px');
 box.css('margin-bottom','50px');
 
 for(i=0;i<=arr.length;i++)
	{
	imgObj.src = arr[i] ; 
	if( imgObj.width > imgObj.height)
	   {
			$('#before_work' + i).addClass('rotated');
			$('#after_work' + i).addClass('rotated');	
	   }
	
    }
}


window.rotateFn = function(uniqueId, url) {
    event.stopPropagation(); // Prevent event bubbling
    var imageElement = document.getElementById(uniqueId);

    // Retrieve current rotation, default to 0 if not set
    var currentRotation = parseInt(imageElement.dataset.rotation) || 0;
    var newRotation = (currentRotation + 90) % 360;
    
    imageElement.style.transform = 'rotate(' + newRotation + 'deg)';
    imageElement.dataset.rotation = newRotation; // Store new rotation

    // If the new rotation completes a full circle, reset the rotation
    if (newRotation === 0) {
        imageElement.style.transform = 'rotate(0deg)';
        imageElement.dataset.rotation = 0;
    }
}




function rotateImageAndUpload(imageElement, uploadUrl, originalFileName, uniqueId, itemType) {
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    var image = new Image();
    image.src = imageElement.src;
    image.onload = function() {
        // 이미지 요소의 현재 변환(transform) 상태에 따라 캔버스 크기를 설정
        var currentTransform = window.getComputedStyle(imageElement).transform;
        if (currentTransform !== 'none') {
            var values = currentTransform.split('(')[1].split(')')[0].split(',');
            var a = values[0];
            var b = values[1];

            // 회전 각도 계산
            var angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));

            // 회전에 따라 캔버스 크기 설정
            if (angle === 90 || angle === 270) {
                canvas.width = image.naturalHeight;
                canvas.height = image.naturalWidth;
            } else {
                canvas.width = image.naturalWidth;
                canvas.height = image.naturalHeight;
            }

            // 이미지 회전 및 그리기
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate(angle * Math.PI / 180);
            ctx.drawImage(image, -image.naturalWidth / 2, -image.naturalHeight / 2);
        } else {
            canvas.width = image.naturalWidth;
            canvas.height = image.naturalHeight;
            ctx.drawImage(image, 0, 0);
        }

        // 캔버스의 이미지를 Blob으로 변환
        canvas.toBlob(function(blob) {
            var formData = new FormData();
            formData.append('rotatedImage', blob, originalFileName);

            // Blob을 서버에 업로드
            fetch(uploadUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Upload successful:', data);		

				if (data.status === "success") {
					imageElement.src = data.targetFilePath; // 새 이미지 경로로 업데이트
					imageElement.style.transform = ''; // 회전 상태 초기화
					imageElement.dataset.rotation = 0; // 회전 데이터 초기화

					// 회전 및 삭제 버튼 업데이트
					updateButtonsAfterUpload(uniqueId, data.targetFilePath, itemType);
					
					toastAlert("이미지가 회전되었습니다.");
				}		
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }, 'image/jpeg');
    };
}

setTimeout(function() {
 // console.log('Works!');
 rotate_image();
}, 500);	


</script>
