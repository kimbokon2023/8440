<?php
// Initialize request variables with null safety
$search = $_REQUEST["search"] ?? '';
$Bigsearch = $_REQUEST["Bigsearch"] ?? '';
$BigsearchTag = $_REQUEST["BigsearchTag"] ?? '';
$bad_choice = $_REQUEST["bad_choice"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

require_once("../lib/mydb.php");
$pdo = db_connect();

?>