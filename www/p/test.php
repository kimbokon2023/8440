<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Image Test</title>
</head>
<body>
    <img src="https://drive.google.com/uc?id=1WlRVq9foJ3Wtw0CCMALnXzRyhwRF51_6" alt="Google Drive Image" style="width:100%; height:100%;">
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Viewer</title>
</head>
<body>
    <iframe src="https://drive.google.com/file/d/1WlRVq9foJ3Wtw0CCMALnXzRyhwRF51_6/preview" style="width:100%; height:auto;" frameborder="0"></iframe>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Thumbnail</title>
    <style>
        .thumbnail {
            width: 150px; /* 원하는 크기로 조정 */
            height: auto;
            margin: 10px;
        }
        .container {
            display: flex;
            flex-wrap: wrap; /* 여러 이미지를 한 줄에 배치 */
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Google Drive 썸네일 URL 사용 -->
        <img src="https://drive.google.com/thumbnail?authuser=0&sz=w320&id=1WlRVq9foJ3Wtw0CCMALnXzRyhwRF51_6" class="thumbnail" alt="Thumbnail 1">
        <img src="https://drive.google.com/thumbnail?authuser=0&sz=w320&id=1WlRVq9foJ3Wtw0CCMALnXzRyhwRF51_6" class="thumbnail" alt="Thumbnail 2">
        <img src="https://drive.google.com/thumbnail?authuser=0&sz=w320&id=1WlRVq9foJ3Wtw0CCMALnXzRyhwRF51_6" class="thumbnail" alt="Thumbnail 3">
    </div>
</body>
</html>
