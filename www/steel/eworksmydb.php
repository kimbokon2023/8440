<?php
// 데이터베이스 연결 정보
$host = "localhost"; // 호스트 이름
$username = "mirae8440"; // MySQL 계정 아이디
$password = "dnjstksfl1!!"; // MySQL 계정 패스워드
$dbname = "mirae8440";  // 데이터베이스 이름

// MySQL 데이터베이스 연결
$conn = mysqli_connect($host, $username, $password, $dbname);

// MySQL 연결 오류 발생 시 스크립트 종료
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

?>