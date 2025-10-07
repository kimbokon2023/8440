<?php
$id=$row["id"];	
$parent_id=$row["parent_id"];	
$workplacename=$row["workplacename"];
$address=$row["address"];
$firstord=$row["firstord"];
$firstordman=$row["firstordman"];
$firstordmantel=$row["firstordmantel"];
$secondord=$row["secondord"];
$secondordman=$row["secondordman"];
$secondordmantel=$row["secondordmantel"];
$chargedman=$row["chargedman"];
$chargedmantel=$row["chargedmantel"];
$regist_day=$row["regist_day"];
$measureday=$row["measureday"];  
$workday=$row["workday"];
$worker=$row["worker"]; 
$doneday=$row["doneday"];  // 납기일  

$item_name=$row["item_name"];
$item_spec=$row["item_spec"];
$item_num=$row["item_num"];
$item_unit=$row["item_unit"];
$item_memo=$row["item_memo"];
$item_note=$row["item_note"];

$material7=$row["material7"];
$material8=$row["material8"];
$material9=$row["material9"];
$material10=$row["material10"];

$memo=$row["memo"];
$memo2=$row["memo2"];
$regist_day=$row["regist_day"];  
$update_log=$row["update_log"];

$demand=$row["demand"];   
$et_writeday=$row["et_writeday"];    // 견적서 작성일 
$et_wpname=$row["et_wpname"];   
if($et_wpname==null)
	$et_wpname=$address;   // 현장명이 없을때는 주소로

$et_schedule=$row["et_schedule"];
$et_person=$row["et_person"];
$et_content=$row["et_content"];	  
$et_note=$row["et_note"];	  

$send_company=$row["send_company"];	  
$send_chargedman=$row["send_chargedman"];	  
$send_tel=$row["send_tel"];	  
$send_date=$row["send_date"];	  
$send_deadline=$row["send_deadline"];	  

?>	  