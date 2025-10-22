<?php
// 로컬/서버 환경 설정
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

include('PdfToText.phpclass');

$mydir = 'pdf';
$myfiles = scandir($mydir);
$total_pdf = count($myfiles);
$i = 2;

$pdf = null;

while ($i < $total_pdf) {
    $file = "pdf/" . $myfiles[$i];

    // $pdf = new PdfToText("$file");
    $pdf = new PdfToText("sample.pdf");

    $string = "computer";
    $data = isset($pdf->Text) ? $pdf->Text : '';

    if (strpos($data, $string) !== false) {
        echo "Found on:-<a target='_blank' href='$file'> $file</a>";
        echo "<br>";
    }

    $i++;
}

echo isset($pdf->Text) ? $pdf->Text : '';
?>