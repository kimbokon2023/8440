<?php
require_once __DIR__ . '/../common/functions.php';
?>
﻿<?php

require_once(includePath('session.php'));  
  
  $num=$_REQUEST["num"]; 
  $page=$_REQUEST["page"];
  $tablename=$_REQUEST["tablename"];   //tablename 이름
  $ripple_content=$_REQUEST["ripple_content"];
  
  require_once("../lib/mydb.php");
  $pdo = db_connect();


    try{
    $pdo->beginTransaction();   
    $sql = "insert into mirae8440.notice_ripple(parent, id, name, nick, content, regist_day) ";
    $sql.= "values(?, ?, ?, ?, ?,now())"; 
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->bindValue(2, $_SESSION["userid"], PDO::PARAM_STR);  
    $stmh->bindValue(3, $_SESSION["name"], PDO::PARAM_STR);   
    $stmh->bindValue(4, $_SESSION["nick"], PDO::PARAM_STR);
    $stmh->bindValue(5, $ripple_content, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit(); 
   
    header("Location:https://8440.co.kr/notice/view.php?tablename=$tablename&num=$num&page=$page");
    } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
    }
   ?>
