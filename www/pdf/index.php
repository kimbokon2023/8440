<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require('class.pdf2text.php');

// POST 변수 안전하게 초기화
$readpdf = $_POST['readpdf'] ?? '';

if (isset($readpdf) && !empty($readpdf)) {
    if (isset($_FILES['file']) && $_FILES['file']['type'] == "application/pdf") {
        $a = new PDF2Text();
        $a->setFilename($_FILES['file']['tmp_name']);
        $a->decodePDF();
        echo $a->output();
    } else {
        echo "<p style='color:red;text-align:center'>Wrong file format</p>";
    }
}
?>

<html>
<head>
    <title>Read pdf php</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        Choose Your File
        <input type="file" name="file" />
        <br>
        <input type="submit" value="Read PDF" name="readpdf" />
    </form>

<?php
// 디버그 정보 (개발 환경에서만 표시)
if ($is_local) {
    phpinfo();
}
?>

</body>
</html>
