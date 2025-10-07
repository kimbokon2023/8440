<?php
// 날짜 공백이나 null 등 돌려주는 함수 
function NullCheckDate($requestdate) {
  if ($requestdate != "0000-00-00") {
    $request_year = date("Y", strtotime($requestdate));
    if ($request_year < 2010) {
      $requestdate = null;
    } else {
      $requestdate = date("Y-m-d", strtotime($requestdate));
    }
  } else {
    $requestdate = "";
  }
  return $requestdate;
}
// 날짜 공백이나 null 등 돌려주는 함수 
function isNotNull($datestr) {
  if ($datestr != "0000-00-00" && $datestr != "" && $datestr != null ) {
    $request_year = date("Y", strtotime($datestr));
    if ($request_year < 2010) {
      $datestr = null;
    } else {
      $datestr = date("Y-m-d", strtotime($datestr));
    }
  } else {
    $datestr = "";
  }
  return $datestr;
}

function is_string_valid($str) {
    if (is_null($str) || !isset($str) || trim($str) === '') {
        return false;
    } else {
        return true;
    }
}

 function echo_null($str) {	
	$strval = ($str == "") ? "&nbsp;&nbsp;&nbsp;" : $str ;
	return $strval;		
}

function trans_date($tdate) {
  if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
		else $tdate="";							
	return $tdate;	
}


function conv_num($num) {
$number = (float)str_replace(',', '', $num);
return $number;
}


?>