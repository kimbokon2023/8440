<?php
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : '';
$registedate = isset($_REQUEST['registedate']) ? $_REQUEST['registedate'] : '';
$comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : '';
$is_deleted = isset($_REQUEST['is_deleted']) ? $_REQUEST['is_deleted'] : '';
$searchtag = isset($_REQUEST['searchtag']) ? $_REQUEST['searchtag'] : '';
$update_log = isset($_REQUEST['update_log']) ? $_REQUEST['update_log'] : '';

// 휴무일 관련 필드 추가
$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '';
$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';
$periodcheck = isset($_REQUEST['periodcheck']) ? $_REQUEST['periodcheck'] : '0';
?>
