<?php

include 'FtpClient/FtpClient.php';
include 'FtpClient/FtpException.php';
include 'FtpClient/FtpWrapper.php';

$host = gethostbyname('mirae8440.ipdisk.co.kr');
	
$login ="mirae8440";    //추가한 계정이름(사용자명)
$password ="mirae8441";     //비밀번호  

$ftp = new \FtpClient\FtpClient();
$ftp->connect($host, true, 7700);
$ftp->login($login, $password);

?>
