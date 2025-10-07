<?php

require_once("../lib/mydb.php");
$pdo = db_connect();

$data = array();

try {
    // Select specific columns from the 'work' table in the 'mirae8440' database
    $stmh = $pdo->query("SELECT workplacename, endworkday, secondord, num, worker, checkstep, widejamb, normaljamb, smalljamb, deadline FROM mirae8440.work");

    // Add each fetched row to the '$data' array
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, $row);
    }

    // Set up the array for JSON encoding
    $data = array(
        "data" => $data,
    );

    // JSON output
    echo(json_encode($data, JSON_UNESCAPED_UNICODE));
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>