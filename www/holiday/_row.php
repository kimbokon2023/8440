<?php
$num = isset($row['num']) ? $row['num'] : '';
$registedate = isset($row['registedate']) ? $row['registedate'] : '';
$comment = isset($row['comment']) ? $row['comment'] : '';
$is_deleted = isset($row['is_deleted']) ? $row['is_deleted'] : '';
$searchtag = isset($row['searchtag']) ? $row['searchtag'] : '';
$update_log = isset($row['update_log']) ? $row['update_log'] : '';

// 휴무일 관련 필드 추가
$startdate = isset($row['startdate']) ? $row['startdate'] : '';
$enddate = isset($row['enddate']) ? $row['enddate'] : '';
$periodcheck = isset($row['periodcheck']) ? $row['periodcheck'] : '0';
?>
