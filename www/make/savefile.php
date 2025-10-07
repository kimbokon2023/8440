<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php"); 
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';

 $num=$_REQUEST["num"] ?? '';

 require_once("../lib/mydb.php");
 $pdo = db_connect(); 
 try{
     $sql = "select * from mirae8440.make where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
			  $num=$row["num"];
			  $orderdate=$row["orderdate"];
			  $indate=$row["indate"];
			  $company=$row["company"];
			  $text=$row["text"];  
	
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }


if($orderdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $orderdate = $orderdate . $week[ date('w',  strtotime($orderdate)  ) ] ;
}

 $arr = explode("|", $text);  
 
$original = $arr[0];

// 파일 이름에 부적합한 문자 제거
$sanitized = preg_replace('/[\/\\\:\*\?\"\<\>\|\#\,]/', '', $original);

 $totalrecnum=count($arr);
 for($i=0;$i<$totalrecnum;$i++)
 {
	 $tmp = explode(",", $arr[$i]);  
	if($tmp[3]=='')
	{
		  $recnum=$i;
		  break;
	}	
 } 
 
$tmp = explode(",", $arr[0]);  
include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';  
?>

<link rel="stylesheet" type="text/css" href="../css/common.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" media="print" href="../css/print2.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="../css/makeprint.css?v=<?php echo time(); ?>">
<title>발주서 출력</title>
</head>

<script> 
function partShot() {
    // 모든 이미지가 로드될 때까지 기다린 후 스크린샷 실행
    waitForImages().then(function() {
        // 추가 딜레이로 배경 이미지 렌더링 보장
        setTimeout(function() {
            takeScreenshot();
        }, 500);
    });
}

function waitForImages() {
    return new Promise(function(resolve) {
        var images = document.querySelectorAll('img');
        var loadedCount = 0;
        var totalImages = images.length;
        
        // CSS 배경 이미지도 확인
        var elementsWithBg = document.querySelectorAll('[style*="background"], [class*="bg"]');
        var totalElements = totalImages + elementsWithBg.length;
        
        // 이미지가 없으면 즉시 resolve
        if (totalElements === 0) {
            resolve();
            return;
        }
        
        // 각 이미지의 로드 상태 확인
        images.forEach(function(img) {
            if (img.complete) {
                loadedCount++;
                if (loadedCount === totalElements) {
                    resolve();
                }
            } else {
                img.addEventListener('load', function() {
                    loadedCount++;
                    if (loadedCount === totalElements) {
                        resolve();
                    }
                });
                img.addEventListener('error', function() {
                    loadedCount++;
                    if (loadedCount === totalElements) {
                        resolve();
                    }
                });
            }
        });
        
        // CSS 배경 이미지 요소들에 대해서는 짧은 딜레이 후 카운트
        elementsWithBg.forEach(function(element) {
            setTimeout(function() {
                loadedCount++;
                if (loadedCount === totalElements) {
                    resolve();
                }
            }, 100);
        });
    });
}

function takeScreenshot() {
    var d = new Date();
    var sanitized = '<?=$sanitized?>';
    var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
    var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
    var result = 'paint' + currentDate + currentTime + '__' + sanitized +'.jpg';		
    
    //특정부분 스크린샷
    html2canvas(document.getElementById("outlineprint"), {
        useCORS: true,
        allowTaint: true,
        backgroundColor: null,
        scale: 2, // 더 높은 해상도
        logging: false
    })
    //id outlineprint 부분만 스크린샷
    .then(function (canvas) {
        //jpg 결과값
        drawImg(canvas.toDataURL('image/jpeg'));
        //이미지 저장
        saveAs(canvas.toDataURL(), result);
    }).catch(function (err) {
        console.log(err);
    });
}

function drawImg(imgData) {
console.log(imgData);
//imgData의 결과값을 console 로그롤 보실 수 있습니다.
return new Promise(function reslove() {
//내가 결과 값을 그릴 canvas 부분 설정
var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');
//canvas의 뿌려진 부분 초기화
ctx.clearRect(0, 0, canvas.width, canvas.height);

var imageObj = new Image();
imageObj.onload = function () {
ctx.drawImage(imageObj, 10, 10);
//canvas img를 그리겠다.
};
imageObj.src = imgData;
//그릴 image데이터를 넣어준다.

}, function reject() { });

}
function saveAs(uri, filename) {
var link = document.createElement('a');
if (typeof link.download === 'string') {
link.href = uri;
link.download = filename;
document.body.appendChild(link);
link.click();
document.body.removeChild(link);
} else {
window.open(uri);
}
}
function cleardiv() {
	 $('#outlineprint').empty();
}

function load_data() {
 var recnum='<?php echo $recnum; ?>' ;	
 var text='<?php echo $text; ?>' ;	
 arr=text.split('|');
for(i=0;i<recnum;i++) {
	tmp=arr[i].split(',');
	
	$('#col1').text(tmp[0]);
	$('#col2').text(tmp[1]);
	$('#col3').text(tmp[2]);
	$('#col4').text(tmp[3]);
	$('#col5').text(tmp[4]);
	$('#col6').text(tmp[5]);
	$('#col7').text(tmp[6]);
	}
}

</script>	
 <style>  
  #specail{	  
	  font-size:25px;
	  margin-left:200px;
	  margin-top:110px;
	  margin-bottom:155px;
  }
  </style>

<body>

<div id="print">  
<div id="outlineprint">  
    <div class="img">      
	<div class="clear"> </div>

       <div id="row1">   <?=$company?> </div>

        <div id="row2"> 발주일: <?=$orderdate?>  </div>  <!-- end of row2-->
	<div class="clear"> </div>	
	
	<?php if($company=='진성케미칼')
	{
		echo '<div id="specail" > 경기도 김포시 양촌읍 삼도공단로 66-1(가동) <br> 노하늘과장 010-3167-1154 </div>';
	}
	else
	{
		echo '<div id="specail" > &nbsp; <br> &nbsp; </div>';
	}			
	?>
	
	<div class="clear"> </div>	
		
	<?php
	// 색상 빈도 계산을 위한 배열
	$colorFrequency = [];

	// 배열을 스캔하여 각 색상의 빈도 계산
	for ($i = 0; $i < $recnum; $i++) {
		$tmp = explode(",", $arr[$i]);

		$color = $tmp[6];
		// var_dump($color);
		
		if (isset($colorFrequency[$color])) {
			$colorFrequency[$color]++;
		} else {
			$colorFrequency[$color] = 1;
		}
	}

	// 가장 빈도가 높은 색상 찾기
	$maxColor = array_keys($colorFrequency, max($colorFrequency))[0];
	
		// var_dump($maxColor);

	for ($i = 0; $i < $recnum; $i++) {
		$tmp = explode(",", $arr[$i]);
		print "<div id='col1'> " . $tmp[0] . "</div>";
		print "<div id='col2'> " . $tmp[1] . "</div>";
		print "<div id='col3'> " . $tmp[2] . "</div>";
		print "<div id='col4'> " . $tmp[3] . "</div>";
		print "<div id='col5'> " . $tmp[4] . "</div>";

		// 색상 값을 읽어들여 가장 많은 색상이 아닌 경우 부트스트랩 배지로 출력
		$color = $tmp[6];
		if ($color != $maxColor) {
			print "<div id='col6'><span class='text-primary'>(" . $color . ")</span></div>";
		} else {
			print "<div id='col6'>" . $color . "</div>";
		}

		print "<div class='clear'></div>";
	}
	?>

	</div>  <!-- end of row2 -->
		<div class="clear"> </div> 


<div id="containers" >	
	<div id="display_result" >	

       <div class="clear"> </div>
	
 	
		   
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  

 </div>    <!-- end of outline --> 
</div>    <!-- end of print --> 
	<?php 
	   print "<script> 
	   // 페이지가 완전히 로드된 후 스크린샷 실행
	   if (document.readyState === 'complete') {
	       partShot();
	   } else {
	       window.addEventListener('load', function() {
	           partShot();
	       });
	   }
	   </script>"; 
	  
	?>
		<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3; display:none;"></canvas>	
</body>
<script>
$(document).ready(function () {
	showMsgModal(10) ; // 다운로드 후 ctrl+j 안내함
	setTimeout(function() {
		hideMsgModal();
	}, 2000);
});

</script>	

</html>
