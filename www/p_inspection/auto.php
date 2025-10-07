<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$tablename = 'p_inspection';
$inspector = '조성원';
$cutoff_date = '2023-03-01';

try {
    // Fetch records with workday after 2023-03-01
    $sql = "SELECT * FROM mirae8440.work WHERE workday > ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $cutoff_date, PDO::PARAM_STR);
    $stmh->execute();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $parentID = $row['num'];
        $workday = $row['workday'];
        $workplacename = $row['workplacename'];

        // Insert into p_inspection table
        $sql_insert = "INSERT INTO mirae8440." . $tablename . " (parentID, writer, check0, check1, check2, check3, check4, check5, check6, check7, check8, check9, regist_day, subject) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh_insert = $pdo->prepare($sql_insert);
        
        $stmh_insert->bindValue(1, $parentID, PDO::PARAM_STR);
        $stmh_insert->bindValue(2, $inspector, PDO::PARAM_STR);
        for ($i = 3; $i <= 12; $i++) {
            $stmh_insert->bindValue($i, $workday, PDO::PARAM_STR);
        }
        $stmh_insert->bindValue(13, $workday, PDO::PARAM_STR);
        $stmh_insert->bindValue(14, $workplacename, PDO::PARAM_STR);
        
        $stmh_insert->execute();
    }
    
    echo "Data inserted successfully.";
} catch (PDOException $Exception) {
    print "Error: " . $Exception->getMessage();
}
?>