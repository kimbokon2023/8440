  <?php session_start();
  
  $e_num=$_REQUEST["e_num"]; 
  $page=$_REQUEST["page"];
  $tablename=$_REQUEST["tablename"];   //tablename 이름
  $ripple_content=$_REQUEST["ripple_content"];
  
  require_once("../lib/mydb.php");
  $pdo = db_connect();


    try{
    $pdo->beginTransaction();   
    $sql = "insert into mirae8440.eworks_ripple(parent, id, name, nick, content, regist_day) ";
    $sql.= "values(?, ?, ?, ?, ?,now())"; 
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $e_num, PDO::PARAM_STR);
    $stmh->bindValue(2, $_SESSION["userid"], PDO::PARAM_STR);  
    $stmh->bindValue(3, $_SESSION["name"], PDO::PARAM_STR);   
    $stmh->bindValue(4, $_SESSION["nick"], PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit(); 
       
    } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
    }
   ?>
