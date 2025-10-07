
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,target-densitydpi=medium-dpi"/>

<?
 $s01 = $_REQUEST['s01'];//검색파일이름 지정
 $s02 = $_REQUEST['s02'];//검색파일이름 지정
?>

<form  action="_FileList.php" method="post">
<table>
<tr >
  <td>
   <input  type="text" name="s01" size="18" value="<?=$s01?>" />
   <input  type="text" name="s02" size="18" value="<?=$s02?>" />
  </td>
</tr>
<tr>
  <td>
   <input type="submit" value="검색" />
  </td>
</tr>
</table>

<?
$path = "./"; //검색할 폴더 지정





// 화면 메시지 변수
$msg = "";
// select 박스에 파일 목록 리스트
$list = [];
try {
	// ftp에 접속한다.
	  
	$ip = gethostbyname('mirae8440.ipdisk.co.kr');
	// echo $ip;
  
$ftp_server = $ip ;
$ftp = ftp_connect($ftp_server,7700) or die("Couldn't connect to $ftp_server"); 

$ftp_user ="mirae8440";    //추가한 계정이름(사용자명)
$ftp_pass ="mirae8441";     //비밀번호  
  
// 로그인을 한다.
if (ftp_login($ftp, $ftp_user, $ftp_pass)) {
	
  ftp_chdir($ftp, 'HDD1/test');	  
	  
    // Web 요청 method가 POST라면
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // FTP의 모든 파일 삭제 타입
      if($_POST["type"] === "all_delete") {
        // FTP의 모든 파일 삭제
        delete_all_ftp($ftp);
        // 완료 메시지 작성
        $msg = "All file was deleted.";
      // FTP 서버에 파일을 업로드 함.
      } else if($_POST["type"] === "upload") {
        // input type=file에 multiple를 추가하면 배열 형식으로 데이터가 온다.
        $count = count($_FILES["upload"]["name"]);
        // 배열의 개수 만큼 Iterate한다.
        for($i=0; $i<$count; $i++) {
          // 업로드를 호출한다. (파일 이름에 공백이 있으면 나중에 리스트 검색시 잘못된 데이터를 가져온다.
          // PHP는 업로드시 파일이 임시 폴더에 있다.
          upload_ftp($ftp, str_replace(' ','',$_FILES["upload"]["name"][$i]), $_FILES["upload"]["tmp_name"][$i]);
          // 업로드 성공 메시지 작성.
          $msg .= "The file was uploaded. - " . $_FILES["upload"]["name"][$i] . "<br />";
        }
      // FTP 서버에서 파일을 다운로드 함.
      } else if($_POST["type"] === "download") {
        download_ftp($ftp, $_POST["download"]);
      }// FTP 서버 디렉토리 읽기
        else if($_POST["type"] === "directory") {
			// $list = search_ftp($ftp);
		  listFolderFiles($ftp); 
      }
    }
	

$list = get_files($ftp);
sort($list); 	
	
	
    // POST, GET 상관없이 select 박스에 FTP 서버의 파일 리스트를 표시한다.
    $list = search_ftp($ftp);
	
	print '현재 Dir';
	print ftp_pwd($ftp);
	print '<br>';

  foreach ($list as $val) {
    // 디렉토리인지 확인
      print $val . '<br>';
	}	
	
  } else {
    // 로그인 실패하면 메시지를 표시한다.
    $msg = "The login was failed.";
  }
} finally {
  ftp_close($ftp);
}




function get_files($path, $s01="") 
{
 $invalid_char = array("\\", "*", "+", "/", "\"", "?", "\|");

 global $s01, $s02;
 $s01 = $_REQUEST['s01'];//검색파일이름 지정
 $s02 = $_REQUEST['s02'];//검색파일이름 지정

 if (strlen($s01) <= 2) {
  echo '검색 문자열이 너무 짧습니다. (01)<br>';
  exit; 
 }

 if (strlen($s01) >= 30) {
  echo '검색 문자열이 너무 깁니다. (01)<br>';
  exit; 
 }

 if (strlen($s02) > 0) {
  if (strlen($s02) < 2) {
   echo '검색 문자열이 너무 짧습니다. (02)<br>';
   exit; 
  }

  if (strlen($s02) >= 30) {
   echo '검색 문자열이 너무 깁니다. (02)<br>';
   exit; 
  }
 }


    static $list = array(); 
 // invalid_char에 해당 하는 검색어는 잘못된 파일 형식이므로 무시함.
 $s01 = str_replace($invalid_char, "", $s01);
 $s02 = str_replace($invalid_char, "", $s02);

 // 패턴 검사를 위해 test(0)[1].gif 등의 형식을 \( \[의 형식으로 변환함. 
 $escape = array("("=>"\\(", 
      ")"=>"\\)", 
      "["=>"\\[", 
      "]"=>"\\]" 
    );

    $dh = opendir($path); 
    while ( ($read=readdir($dh))!==false ) 
    { 
  if($read[0] == '.' || $read[0] == '..' ) continue; 
  
  if ( is_dir($path.$read.'/') ) get_files($path.$read.'/'); 
  else {

   // 대소문자 구분없이 파일명에 검색어가 있는지 비교함
   if(eregi(str_replace(array_keys($escape), array_values($escape), $s01), $read))
   {
     if (strlen($s02) > 0)
     {
      if(eregi(str_replace(array_keys($escape), array_values($escape), $s02), $read))
      {
       $ext = strtolower(substr($read, (strrpos($read, '.') + 1 )));
       $read = iconv('euckr', 'utf-8', $read);
       $list[] = $read . " : " . filesize($path.$read) ;
      }
     }
     else
     {
      $ext = strtolower(substr($read, (strrpos($read, '.') + 1 )));
      $read = iconv('euckr', 'utf-8', $read);
      $list[] = $read . " : " . filesize($path.$read) ;
     }
   }
  }
    } 
    closedir($dh); 
    return $list; 
} 

?>

<?=$s01?>, <?=$s02?>로 검색하여 총 <?=count($list)?>개를 찾았습니다.<br>

<?
 echo "<table border='1'>";
 foreach ($list as $value)
 {
  $temp = explode(':',$value); // |로 나누기 

  echo "<tr><td width='300'>$temp[0]</td><td width='100' align='right'>$temp[1]</td></tr>";
 }
 echo "</table>";
?>

