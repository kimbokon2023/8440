<?php

require_once("../lib/mydb.php");
$pdo = db_connect();    

$data_ceiling = array();

try {  
    // Select specific columns from the 'work' table in the 'mirae8440' database
    $stmh = $pdo->query("SELECT workplacename, deadline, secondord, num, bon_su, lc_su, etc_su, lcassembly_date, mainassembly_date, etcassembly_date, type, workday, main_draw, lc_draw, etc_draw, cabledone  FROM mirae8440.ceiling");    

   // Add each fetched row to the '$data_ceiling' array
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {       
        array_push($data_ceiling, $row);     
   }

   // Set up the array for JSON encoding
   $data_ceiling = array(
       "data_ceiling" => $data_ceiling,
   );

   // JSON output
   echo(json_encode($data_ceiling, JSON_UNESCAPED_UNICODE));

} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

?>
