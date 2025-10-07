<?php

header("Content-Type: text/html; charset=UTF-8");

isset($_REQUEST["message"]) ? $question = $_REQUEST["message"] : $question = "";
isset($_REQUEST["email"]) ? $email = $_REQUEST["email"] : $email = "";
isset($_REQUEST["name"]) ? $name = $_REQUEST["name"] : $name = "";
isset($_REQUEST["phone"]) ? $phone = $_REQUEST["phone"] : $phone = "";

$to = "mirae@8440.co.kr"; // 받는사람
$title = "[홈페이지 견적/제작 문의] " . $name . " 님의 요청"; // 제목
$title_encode = "=?utf-8?B?" . base64_encode($title) . "?=\n";

// 첨부 파일 읽기
$fileAttached = false;
if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
    $filename = $_FILES["file"]["tmp_name"];
    $basename = $_FILES["file"]["name"];
    $basename_encoded = "=?utf-8?B?" . base64_encode($basename) . "?="; // 한글 파일명 인코딩
    $filetype = mime_content_type($filename);
    $filedata = chunk_split(base64_encode(file_get_contents($filename)));
    $fileAttached = true;
}

// 메일 본문 작성
$message = "
<html>
    <body>
        <h3>견적/제작 문의</h3>
        <p><strong>성함:</strong> $name</p>
        <p><strong>연락처:</strong> $phone</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>문의 내용:</strong><br>$question</p>
    </body>
</html>
";

// 이메일 헤더 작성
$boundary = md5(uniqid(time()));
$headers = "From: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

// 이메일 본문 작성
$body = "--$boundary\r\n";
$body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= chunk_split(base64_encode($message));

// 첨부 파일이 있는 경우 첨부
if ($fileAttached) {
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: $filetype; name=\"$basename_encoded\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$basename_encoded\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $filedata . "\r\n";
}

// 바운더리 종료
$body .= "--$boundary--";

// 메일 전송
$send_mail = mail($to, $title_encode, $body, $headers);

// 응답 반환
echo $send_mail ? "1" : "0";

?>
