<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php'; // 세션 파일 포함
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php');

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/tokens/mytoken.json';	

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
if (class_exists('Google_Service_Drive')) {
    $client->addScope(\Google_Service_Drive::DRIVE);

    // Google Drive 서비스 초기화
    $service = new \Google_Service_Drive($client);
} else {
    // Google_Service_Drive 클래스가 없을 때 경고 없이 처리
    $service = null;
}

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}
// 첫 화면 표시 문구
$title_message = 'EL Ceiling 수주'; 
 ?>

<?php 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
		  header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';   
 ?>
<title> <?=$title_message?> </title>
<body>		 
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   
<style>
@keyframes marquee {
  0% { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}
/* Light mode styles */
body {
  background-color: #ffffff;
  color: #000000;
}

/* Dark mode styles */
[data-theme="dark"] {
  background-color: #000000;
  color: #ffffff;
}

/* Toggle switch styles */
.toggle-switch {
  display: inline-block;
  position: relative;
  width: 60px;
  height: 34px;
}

.toggle-switch input[type="checkbox"] {
  display: none;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}

.toggle-slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  transition: .4s;
	  border-radius: 50%;
}

input[type="checkbox"]:checked + .toggle-slider {
  background-color: #2196F3;
}

input[type="checkbox"]:checked + .toggle-slider:before {
  transform: translateX(26px);
}

  .hidden-field {
	display: none; /* 체크박스 체크 시 숨겨진 필드를 숨김 */
  }
  .part-field {
	display: none; /* 체크박스 체크 시 숨겨진 필드를 숨김 */
  }
  
/* 기본적으로 숨김 처리 */

#deadline_laserContainer {
	cursor: pointer;
}

#autocomplete-list {
	border: 1px solid #d4d4d4;
	border-bottom: none;
	border-top: none;
	position: absolute;
	top: 93%;
	left: 50%;
	right: 30%;
	width : 10%;
	z-index: 999;
}
.autocomplete-item {
	padding: 10px;
	cursor: pointer;
	background-color: #fff;
	border-bottom: 1px solid #d4d4d4;
}
.autocomplete-item:hover {
	background-color: #e9e9e9;
}

.custom-tooltip {
    display: none;
    position: absolute;
    border: 1px solid #ddd;
    background-color: blue;
	color:white;
	font-size:25px;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    top : 100;
}

/* 툴팁 위치 조정 */
#denkriModel:hover + .custom-tooltip {
    display: block;
    left: 70%; /* 화면 가로축의 중앙에 위치 */
    top: 90px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
	
#showalign {
	display: inline-block;
	position: relative;
}
		
#showalignframe {
	display: none;
	position: absolute;
	width: 1000px;
	z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */    
    transform: translateX(-40%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */		
}
	
#showextract {
	display: inline-block;
	position: relative;
}

#showextractframe {
	display: none;
	position: absolute;
	width: 50%;
	z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */    
    transform: translateX(-40%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */		
}	

th, td {
    vertical-align: middle !important;
}
</style>

</head>
<?php
$tablename = 'ceiling'; 
include "_request.php";
// print 'search : ' . var_dump($search);
function check_in_range($start_date, $end_date, $user_date)
{  
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($user_date);
  
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}	  

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

// /////////////////////////첨부파일 있는 것 불러오기 
$savefilename_arr=array(); 
$realname_arr=array(); 
$attach_arr=array(); 
$tablename='ceiling';
$item = 'ceiling';

$sql = "SELECT * FROM {$DB}.picuploads WHERE tablename=? AND item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$tablename, $item, $num]);
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];
        $realname = $row["realname"];
        $realname_arr[] = $realname; // realname 배열에 추가

        if (preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            // Google Drive 파일 ID로 처리
            $fileId = $picname;

            try {
                // Google Drive 파일 정보 가져오기
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                $webViewLink = $file->webViewLink;
                $savefilename_arr[] = [
                    'thumbnail' => $thumbnailUrl,
                    'link' => $webViewLink,
                    'fileId' => $fileId,
                    'realname' => $realname // realname 포함
                ];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $savefilename_arr[] = [
                    'thumbnail' => "https://drive.google.com/uc?id=$fileId",
                    'link' => null,
                    'fileId' => $fileId,
                    'realname' => $realname // realname 포함
                ];
            }
        } else {
            // Google Drive에서 파일 이름으로 검색
            try {
                $query = sprintf("name='%s' and trashed=false", addslashes($picname)); // 파일 이름으로 검색
                $response = $service->files->listFiles([
                    'q' => $query,
                    'fields' => 'files(id, webViewLink, thumbnailLink)',
                    'pageSize' => 1
                ]);

                if (count($response->files) > 0) {
                    $file = $response->files[0];
                    $fileId = $file->id; // 검색된 파일의 ID
                    $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                    $webViewLink = $file->webViewLink;
                    $savefilename_arr[] = [
                        'thumbnail' => $thumbnailUrl,
                        'link' => $webViewLink,
                        'fileId' => $fileId,
                        'realname' => $realname // realname 포함
                    ];

                    // 데이터베이스 업데이트: 검색된 파일 ID 저장
                    $updateSql = "UPDATE {$DB}.picuploads SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                    $updateStmh = $pdo->prepare($updateSql);
                    $updateStmh->execute([$fileId, $item, $num, $picname]);
                } else {
                    error_log("Google Drive에서 파일을 찾을 수 없습니다: " . $picname);
                    $savefilename_arr[] = [
                        'thumbnail' => null,
                        'link' => null,
                        'fileId' => null,
                        'realname' => $realname // realname 포함
                    ];
                }
            } catch (Exception $e) {
                error_log("Google Drive 파일 검색 실패: " . $e->getMessage());
                $savefilename_arr[] = [
                    'thumbnail' => null,
                    'link' => null,
                    'fileId' => null,
                    'realname' => $realname // realname 포함
                ];
            }
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// var_dump($attach_arr);

$today = date('Y-m-d');

// 기본 D-15
$dDay = isset($_GET['dDay']) ? intval($_GET['dDay']) : 15;
$afterday = date("Y-m-d", strtotime("+$dDay day", strtotime($today)));
$end_date = $afterday;

$start_date = $today ;
//print check_in_range($start_date, $end_date, $user_date);
//print '오늘 날짜 ' . $today;
//print '오늘기준 6일전 날짜 ' . $beforefiveday;

// laser todolist 배열
$todolist=array();
$todolistlink=array();

// 설계 todolist 배열
$todolist_draw=array();
$todolistlink_draw=array();

if(isset($_REQUEST["fromdate"]))  //수정 버튼을 클릭해서 호출했는지 체크
$fromdate=$_REQUEST["fromdate"];

if(isset($_REQUEST["todate"]))  //수정 버튼을 클릭해서 호출했는지 체크
$todate=$_REQUEST["todate"];  

// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-1 months", strtotime($currentDate))); // 1개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
		
$sql="select * from mirae8440.ceiling " ;

try{  
		$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh    
		while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			
			  include '_rowDB.php';						  	
			  			  
			  $main_draw_arr="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,5,5,"utf-8");		    
			     elseif($bon_su<1) $main_draw_arr= "X";		    
   
   		        $lc_draw_arr="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,5,5,"utf-8") ;
			     elseif($lc_su<1) $lc_draw_arr = "X";	
			  if($type=='011' || $type=='012' || $type=='013D' || $type=='025' || $type=='017' || $type=='014' || $type=='037' || $type=='038')
				 $lc_draw_arr = "X";				  
			  
			  $maincondition = 1; // 조건충족 확인 본천장 설계
			  $lccondition = 1; // 조건충족 확인 LC 설계가 있다면 본천장 레이져 가공 조건충족여부
			  $maincondition_draw = 1; // 조건충족 확인 본천장 설계
			  $lccondition_draw = 1; // 조건충족 확인 LC 설계가 있다면 본천장 레이져 가공 조건충족여부
			  // laser 일정
			  // 은성본천장 외주인경우는 제외시킴
			  if ( (($main_draw!='' and $main_draw!='0000-00-00') and ( ($eunsung_laser_date!='' and $eunsung_laser_date!='0000-00-00') or ($eunsung_make_date!='' and $eunsung_make_date!='0000-00-00')  ) )  or  ($main_draw_arr== "X") )
				    $maincondition = 0; 
				
			// LC 도면설계와 LC레이져가공의 조건이 충족되면
			  if( (($lc_draw!='' and $lc_draw!='0000-00-00') and ($lclaser_date!='' and $lclaser_date!='0000-00-00')) or  ($lc_draw_arr== "X")  )
				    $lccondition = 0; 		
				
			  // 설계일정	
			  // 은성본천장 외주인경우는 제외시킴
			  if ( ($main_draw!='' and $main_draw!='0000-00-00') or  ($main_draw_arr== "X") )
				    $maincondition_draw = 0; 
				
			// LC 도면설계와 LC레이져가공의 조건이 충족되면
			  if( ($lc_draw!='' and $lc_draw!='0000-00-00') or  ($lc_draw_arr== "X")  )
				    $lccondition_draw = 0; 			
			  
			  $user_date = $deadline;			   
			  // laser 가공일정 체크
			  if(check_in_range($start_date, $end_date, $user_date) && ($maincondition || $lccondition) )
			    {
				  array_push($todolist,$workplacename . '(' . $secondord .')' );			  
				  array_push($todolistlink,$num);			  
				}		   
			  // 설계 일정 체크	
			  if(check_in_range($start_date, $end_date, $user_date) && ($maincondition_draw || $lccondition_draw) )
			    {
				  array_push($todolist_draw,$workplacename . '(' . $secondord .')' );			  
				  array_push($todolistlink_draw,$num);			  
				}
		
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

 $todolistCount = count($todolist);
 $todolistCount_draw = count($todolist_draw);
	   
  $sum=array();
  	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"] ?? "";
  else 
     $mode="";     
   
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"] ?? "";
  	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분

 $orderby="order by num desc ";

if ($check=='1')  // 미출고 리스트 클릭
		{
				$attached=" and ((workday='') or (workday='0000-00-00')) ";
				$whereattached=" where ( workday='' or (workday='0000-00-00') ) ";
		}
if ($check=='2')  // 제작완료 출고대기 클릭
		{				
				$whereattached=" where ((workday='') or (workday='0000-00-00')) and ( ((bon_su!='') and (mainassembly_date!='0000-00-00')) or  ((lc_su!='') and (lcassembly_date!='0000-00-00')) or ((etc_su!='') and (etcassembly_date!='0000-00-00')) )";
				$attached=" and  ( (workday='') or (workday='0000-00-00')) and ( ((bon_su!='') and (mainassembly_date!='0000-00-00')) or  ((lc_su!='') and (lcassembly_date!='0000-00-00')) or ((etc_su!='') and (etcassembly_date!='0000-00-00')) ) ";
                $orderby="order by deadline asc, orderday asc ";	
		}		
if ($check=='3')  // 출고완료 미청구 클릭
		{
				$attached=" and ((workday!='') and (workday!='0000-00-00')) ";
				$whereattached=" where workday!='' ";
		}
if ($check=='4')  // 미설계List
		{
				$attached=" and (((main_draw='') or (main_draw='0000-00-00')) and (bon_su>'0')) or (((lc_draw='') or (lc_draw='0000-00-00')) and (lc_su>'0') and type NOT IN ('011','012','013D','025','017','014','037','038') ) ";
				// $attached=" and (((main_draw='') or (main_draw='0000-00-00')) or (((lc_draw='')  or (lc_draw='0000-00-00')) and type NOT IN ('011','012','013D','025','017','014')))  ";
				$whereattached=" where (((main_draw='') or (main_draw='0000-00-00')) and (bon_su>'0')) or (((lc_draw='') or (lc_draw='0000-00-00')) and (lc_su>'0') and type NOT IN ('011','012','013D','025','017','014','037','038') ) ";				
		}		
if ($check=='6')  // 외주가공
		{
	        $attached = " and (outsourcing!='') ";
			$whereattached = " where outsourcing!='' ";
		}		
		
if ($check=='5')  // 설계완료 미발주 부품
		{
	$attached="  and NOT (
    ((main_draw = '' OR main_draw = '0000-00-00') AND bon_su > '0')
    OR
    ((lc_draw = '' OR lc_draw = '0000-00-00') AND lc_su > '0' AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))
	) 
   And 
	((date(workday)>=date(now())) or date(workday)='0000-00-00' ) and (((order_com1<>'') and (order_date1='0000-00-00')) or  ((order_com2<>'') and (order_date2='0000-00-00')) or ((order_com3<>'') and (order_date3='0000-00-00'))) ";
								
				$whereattached=" where  NOT (
				((main_draw = '' OR main_draw = '0000-00-00') AND bon_su > '0')
				OR
				((lc_draw = '' OR lc_draw = '0000-00-00') AND lc_su > '0' AND type NOT IN ('011', '012', '013D', '025', '017', '014', '037', '038'))
				) and ((date(workday)>=date(now())) or date(workday)='0000-00-00' ) and (((order_com1<>'') and (order_date1='0000-00-00')) or  ((order_com2<>'') and (order_date2='0000-00-00')) or ((order_com3<>'') and (order_date3='0000-00-00')))";				
		// print $attached;
		}			
		
if ($check=='12' )  // 출고완료 체크 그리고 미청구된 것만 보기
		{
				$attached=" and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))    ";
				$whereattached=" where workday!='' and demand='' ";
				
                $orderby="order by workday desc ";						
				
		}		
if ($check=='0' || $check==0)
	$whereattached = '';
		
// 완료일 기준
$SettingDate=" orderday ";

$Andis_deleted =  " AND is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;
$Whereis_deleted =  " Where is_deleted IS NULL AND eworks_item='원자재구매' " . $Andmywrite;	 
	 
$common= $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
		
$andPhrase= " and " . $common  . $orderby ;
$wherePhrase= " where " . $common  . $orderby ;

// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);  

if($search==""){
	               if($whereattached!=='')
						$sql="select * from mirae8440.ceiling " . $whereattached . $andPhrase; 					                 
					else
						$sql="select * from mirae8440.ceiling " . $wherePhrase ;					                 
			       }
			 elseif($search!="" && $find!="all")
			    {
         			$sql="select * from mirae8440.ceiling where ($find like '%$search%') " . $attached . $andPhrase;         			
                 }     				 
             elseif($search!="" && $find=="all") { // 필드별 
	              if($check!='1' && $check!='2') {		 
					  $sql ="select * from mirae8440.ceiling where ((replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )   or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' ) or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )   or (boxwrap like '%$search%' )  or (designer like '%$search%' )  )  " . $attached . $andPhrase; 					                       
				  }				  
				 if($check=='1') {			  
						  $sql ="select * from mirae8440.ceiling where ( (replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' )   or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )  or (boxwrap like '%$search%' )  or (designer like '%$search%' )  ) "  . $attached . $andPhrase;						  
					  }		
				 // 제작 완료인데 검색하는 조건
				 if($check=='2') {			  
						  $sql ="select * from mirae8440.ceiling where ( (replace(workplacename,' ','') like '%$search%' ) or (firstordman like '%$search%' )  or (order_com1 like '%$search%' )   or (order_com2 like '%$search%' )   or (order_com3 like '%$search%' )   or (order_com4 like '%$search%' )   or (order_com4 like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
						  $sql .="or (delicompany like '%$search%' ) or (type like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (car_insize like '%$search%' ) or (memo like '%$search%' )   or (memo2 like '%$search%' ) or (material1 like '%$search%' ) or (material2 like '%$search%' ) or (material3 like '%$search%' ) or (material4 like '%$search%' ) or (material5 like '%$search%' ) or (air_su like '%$search%' )  or (searchtag like '%$search%' )  or (boxwrap like '%$search%' )  or (designer like '%$search%' )  ) "  . $attached . $andPhrase;
					  }				  
		   
			        }    
// print $sql;  
$current_condition = $check;
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    		
			 
 ?>

<form id="board_form" name="board_form" method="post" action="list.php?mode=search">  
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 		
	<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
	<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
	<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
	<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
	<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 		
	<input type="hidden" id="list" name="list" value="<?=$list?>" > 		

<div class="container-fluid">  
	<div class="card mb-2 mt-2">  
	<div class="card-body">  	 
			 
	<div class="d-flex  p-0 m-1 mt-1 justify-content-center align-items-center "> 				
		<div class="alert alert-primary text-center mb-0" role="alert" style="font-size:1rem; padding:6px;">
			2025년 8월부터 037, 038 자체 생산으로 코드를 <b>N037, N038</b>로 입력바랍니다. &nbsp; &nbsp; 기존의 청디자인 모델과 구분하기 위함입니다.
		</div>
	</div>
	<div class="d-flex  p-1 m-1 mt-1 justify-content-center align-items-center "> 		
		  <a href="list.php">   <h5>  <?=$title_message?> </h5> </a>	 &nbsp;&nbsp;&nbsp;	&nbsp;	  	
		<!-- 화면 리로드 -->
		<button type="button" class="btn btn-dark btn-sm me-2" onclick="window.location.reload();">
			<i class="bi bi-arrow-clockwise"></i> 
		</button>
	   <span id="showalign" class="btn btn-dark btn-sm me-2" > <i class="bi bi-grid-3x3"></i> 정렬 </span>	
		<div id="showalignframe" class="card">
			<div class="card-header text-center " style="padding:2px;">
				화면정렬
			</div>					
				<div class="card-body">				
					<?php
					function printCheckbox($id, $value, $label, $checkedValue) {
						$isChecked = ($value == $checkedValue) ? "checked" : "";                                    
						echo "<input type='checkbox' class='search-condition' $isChecked id=$id value='$value'>&nbsp; <span class='badge bg-dark' style='font-size:13px;'> $label </span>  &nbsp;&nbsp;";
					}                                                        

					printCheckbox('all', '0', '전체', $current_condition);                                
					printCheckbox('without', '1', '미출고', $current_condition);
					printCheckbox('notdesigned', '4', '미설계', $current_condition);
					printCheckbox('notordered', '5', '(설계완료) 미발주 부품', $current_condition);
					printCheckbox('outsourcingcheck', '6', ' 외주가공', $current_condition);
					printCheckbox('output_complete', '3', '출고완료', $current_condition);
					printCheckbox('ready_to_ship', '2', '제작완료 출고대기', $current_condition);
					printCheckbox('not_claimed', '12', '출고완료 미청구', $current_condition);
					?>
				   
				</div>
			</div>				
	   <span id="showextract" class="btn btn-dark btn-sm me-2" > <i class="bi bi-building-add"></i> 부가기능 </span>
		<div id="showextractframe" class="card">
			<div class="card-header text-center " style="padding:2px;">
				부가기능
			</div>					
				<div class="card-body">
					<button type="button" id="reportBtn" class="btn btn-dark btn-sm"> <i class="bi bi-bar-chart"></i> 공정 작업시간 </button>
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('../mceiling/list.php','모바일현장용',1900,900);"> 모바일(현장) </button>
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('call_csv.php','CSV 파일추출',1600,500);"> 엑셀CSV</button>  
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('calcarsize.php','인승 산출 계산기',1860,800);"> 인승 산출 </button>					
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('batch.php','납기 일괄',1800,900);">  납기일괄 </button>  
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('delivery_fee.php','배송비추출',1500,800);" > 배송비 </button>
					<button type="button" class="btn btn-dark btn-sm " onclick="popupCenter('batchDB.php','청구 일괄',1800,900);"> 청구일괄 </button>
					<span id="denkriModel" class="btn btn-info btn-sm ">덴크리모델</span>
					<div id="customTooltip" class="custom-tooltip">
							(덴크리 외주 모델 : 011,012,013D,025,017,014)
					</div> 	
					<button type="button" id="showHolepunching" class="btn btn-primary btn-sm " > 홀타공도 </button> 
					<button type="button" id="catalog" class="btn btn-primary btn-sm " > 천장 카다로그 </button> 
						
				</div>				
			</div>					
													
			<button type="button" class="btn btn-dark btn-sm me-2" id ="plan_cutandbending" >  <ion-icon name="calendar-outline"></ion-icon> 가공스케줄 </button>    																
			<button type="button" class="btn btn-dark btn-sm me-2" onclick="window.open('plan_making.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','납품일정 List DB','left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900');">  <ion-icon name="calendar-outline"></ion-icon> 납품예정 </button>    	
		
			<!-- D- 날짜 선택 15일부터 25일까지 -->
			<select id="dDaySelect" class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
			  <option value="15">D-15</option>
			  <option value="16">D-16</option>
			  <option value="17">D-17</option>
			  <option value="18">D-18</option>
			  <option value="19">D-19</option>
			  <option value="20">D-20</option>
			  <option value="21">D-21</option>
			  <option value="22">D-22</option>
			  <option value="23">D-23</option>
			  <option value="24">D-24</option>
			  <option value="25">D-25</option>
			</select>			
			
			<button type="button" class="btn btn-danger btn-sm me-2">   <span id="deadline_laser" > </span>  	</button>  			  
			<button type="button" class="btn btn-warning btn-sm me-2">  <span id="notdrawing" > </span>  	</button>  			  
				
		</div>	
	 <div class="row d-flex justify-content-center  align-items-center " >  	
			
		<div class="col-sm-6">
		  <div id="display_list" class="card justify-content-center align-items-center">							
			<div class="card-body">
			  <table class="table table-hover">
				<tbody>
				  <tr>
					<td class="col">				 
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="laserBadge" class="badge bg-danger fs-6"> laser 미가공 List</span>                                                          
					  </div>			 
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="todolist" class="form-control">Todo List</span>                                                          
					  </div>
					</td>	
				  </tr>
				</tbody>
			  </table>
			</div>
		  </div>
		</div>
		<div class="col-sm-6">
		  <div id="display_list_draw" class="card justify-content-center align-items-center">							
			<div class="card-body">
			  <table class="table table-hover">
				<tbody>
				  <tr>
					<td class="col">	
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="drawBadge" class="badge bg-warning fs-6"> 미설계 List</span>                                                          
					  </div>	
					  <div class="d-flex justify-content-center align-items-center">	
						<span id="todolist_draw" class="form-control"></span>                                                          
					  </div>
					</td>	
				  </tr>
				</tbody>
			  </table>
			</div>
		  </div>
		</div>

	<div class="d-flex  p-1 m-1 mt-1 mb-1 justify-content-center align-items-center"> 	  
			총 <?= $total_row ?> 건 &nbsp;		&nbsp;

			<!-- 기간부터 검색까지 연결 묶음 start -->
				<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 

				<select name="dateRange" id="dateRange" class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
					<?php
					$dateRangeArray = array('최근3개월','최근6개월', '최근1년', '최근2년','직접설정','전체');
					$savedDateRange = $_COOKIE['dateRange'] ?? ''; // 쿠키에서 dateRange 값 읽기

					foreach ($dateRangeArray as $range) {
						$selected = ($savedDateRange == $range) ? 'selected' : '';
						echo "<option $selected value='$range'>$range</option>";
					}
					?>
				</select>
				
				<div id="showframe" class="card">
					<div class="card-header " style="padding:2px;">
						<div class="d-flex justify-content-center align-items-center">  
							기간 설정
						</div>
					</div>
					<div class="card-body">
						<div class="d-flex justify-content-center align-items-center">  	
							<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
							<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
							<button type="button" id="three_month" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='three_month_ago()' > M-3월 </button>
							<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='prepre_month()' > 전전월 </button>	
							<button type="button" id="premonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='pre_month()' > 전월 </button> 						
							<button type="button" class="btn btn-outline-danger btn-sm me-1  change_dateRange"  onclick='this_today()' > 오늘 </button>
							<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_month()' > 당월 </button>
							<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1  change_dateRange"  onclick='this_year()' > 당해년도 </button> 
						</div>
					</div>
				</div>		

			   <input type="date" id="fromdate" name="fromdate"   class="form-control p-1"   style="width:100px;" value="<?=$fromdate?>" >  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate"  class="form-control p-1"   style="width:100px;" value="<?=$todate?>" >  &nbsp;     </span> 
			   &nbsp;&nbsp;		 
				
		<select id="find" name="find" class="form-select w-auto mx-1" style="font-size:1em; height:30px;" >
			<?php
			$options = array(
				'all' => '전체',
				'workplacename' => '현장명',
				'firstord' => '원청',
				'secondord' => '발주처',
				'type' => '타입'
			);

			foreach ($options as $value => $label) {
				$selected = ($find == $value) ? 'selected' : '';
				echo "<option value='$value' $selected>$label</option>";
			}
			?>
		</select>
	
		<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off"  class="form-control" style="width:150px;" > &nbsp;			
				<button class="btnClear"></button>
		</div>				
		<div id="autocomplete-list">				
		</div>	
		  &nbsp;
		  <button id="searchBtn" type="button" class="btn btn-dark  btn-sm" > <i class="bi bi-search"></i> 검색 </button> 		  
		  &nbsp;&nbsp;&nbsp;		    

				 <button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	     
				 <button  type="button" id="rawmaterialBtn"  class="btn btn-dark btn-sm" > <i class="bi bi-list"></i> 재고 </button> &nbsp;	

         </div> 
	 
   </div> <!--card-body-->
   </div> <!--card -->   
</div> <!--card -->   
<div class="container-fluid">  
<!-- Column Visibility Controls -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">컬럼 표시 설정</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="num" id="col-num" checked>
                            <label class="form-check-label" for="col-num">번호</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="outsourcing" id="col-outsourcing" checked>
                            <label class="form-check-label" for="col-outsourcing">외주</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="orderday" id="col-orderday" checked>
                            <label class="form-check-label" for="col-orderday">접수</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="main_draw" id="col-main_draw" checked>
                            <label class="form-check-label" for="col-main_draw">본설계</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="lc_draw" id="col-lc_draw" checked>
                            <label class="form-check-label" for="col-lc_draw">L/C설계</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="etc_draw" id="col-etc_draw" checked>
                            <label class="form-check-label" for="col-etc_draw">기타설계</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="mainassembly" id="col-mainassembly" checked>
                            <label class="form-check-label" for="col-mainassembly">본제작</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="lcassembly" id="col-lcassembly" checked>
                            <label class="form-check-label" for="col-lcassembly">L/C제작</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="etcassembly" id="col-etcassembly" checked>
                            <label class="form-check-label" for="col-etcassembly">기타제작</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="deadline" id="col-deadline" checked>
                            <label class="form-check-label" for="col-deadline">납기</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="workday" id="col-workday" checked>
                            <label class="form-check-label" for="col-workday">출고</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="photo" id="col-photo" checked>
                            <label class="form-check-label" for="col-photo">사진</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="demand" id="col-demand" checked>
                            <label class="form-check-label" for="col-demand">청구</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="workplacename" id="col-workplacename" checked>
                            <label class="form-check-label" for="col-workplacename">현장명</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="secondord" id="col-secondord" checked>
                            <label class="form-check-label" for="col-secondord">발주처</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="type" id="col-type" checked>
                            <label class="form-check-label" for="col-type">타입</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="inseung" id="col-inseung" checked>
                            <label class="form-check-label" for="col-inseung">인승</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="car_insize" id="col-car_insize" checked>
                            <label class="form-check-label" for="col-car_insize">inside</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="bon_su" id="col-bon_su" checked>
                            <label class="form-check-label" for="col-bon_su">본</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="lc_su" id="col-lc_su" checked>
                            <label class="form-check-label" for="col-lc_su">L/C</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="etc_su" id="col-etc_su" checked>
                            <label class="form-check-label" for="col-etc_su">기타</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="air_su" id="col-air_su" checked>
                            <label class="form-check-label" for="col-air_su">공청</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="order_com1" id="col-order_com1" checked>
                            <label class="form-check-label" for="col-order_com1">납품1</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="order_date1" id="col-order_date1" checked>
                            <label class="form-check-label" for="col-order_date1">주문1</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="order_com2" id="col-order_com2" checked>
                            <label class="form-check-label" for="col-order_com2">납품2</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="order_date2" id="col-order_date2" checked>
                            <label class="form-check-label" for="col-order_date2">주문2</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="order_com3" id="col-order_com3" checked>
                            <label class="form-check-label" for="col-order_com3">납품3</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="order_date3" id="col-order_date3" checked>
                            <label class="form-check-label" for="col-order_date3">주문3</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input column-toggle" type="checkbox" value="memo" id="col-memo" checked>
                            <label class="form-check-label" for="col-memo">비고</label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary btn-sm" id="select-all-columns">전체 선택</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="deselect-all-columns">전체 해제</button>
                    <button type="button" class="btn btn-success btn-sm" id="save-column-settings">설정 저장</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center align-items-center"> 
    <div id="tabulator-table"></div>
</div>

<div style="display: none;">
    <table id="data-source">
        <tbody>
		<?php  		  
			$start_num=$total_row;   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			  
			 // var_dump($row);			  
			  include '_rowDB.php';
			  
			// 첨부파일이 있는경우 '(비규격)' 앞에 문구 넣어주는 루틴임
			for($i=0;$i<count($attach_arr);$i++)
	            if($attach_arr[$i] == $num)
					  $workplacename = '(비규격)' .  $workplacename;		  
			  
			  $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

		      $workday=trans_date($workday);
		      $demand=trans_date($demand);
		      $orderday=trans_date($orderday);
		      $deadline=trans_date($deadline);
		      $testday=trans_date($testday);
		      $lc_draw=trans_date($lc_draw);
		      $lclaser_date=trans_date($lclaser_date);
		      $lcbending_date=trans_date($lcbending_date);
		      $lclwelding_date=trans_date($lclwelding_date);
		      $lcwelding_date=trans_date($lcwelding_date);
		      $lcassembly_date=trans_date($lcassembly_date);
		      $main_draw=trans_date($main_draw);			
		      $eunsung_make_date=trans_date($eunsung_make_date);			
		      $eunsung_laser_date=trans_date($eunsung_laser_date);			
		      $mainbending_date=trans_date($mainbending_date);			
		      $mainwelding_date=trans_date($mainwelding_date);			
		      $mainpainting_date=trans_date($mainpainting_date);			
		      $mainassembly_date=trans_date($mainassembly_date);										

		      $order_date1=trans_date($order_date1);					   
		      $order_date2=trans_date($order_date2);					   
		      $order_date3=trans_date($order_date3);					   
		      $order_date4=trans_date($order_date4);					   
		      $order_input_date1=trans_date($order_input_date1);					   
		      $order_input_date2=trans_date($order_input_date2);					   
		      $order_input_date3=trans_date($order_input_date3);					   
		      $order_input_date4=trans_date($order_input_date4);				  
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
			  
			$main_draw_arr="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,5,5,"utf-8");		    
			     elseif($bon_su<1) $main_draw_arr= "X";		    
   
			$lc_draw_arr="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,5,5,"utf-8") ;
			     elseif($lc_su<1) $lc_draw_arr = "X";	
			  if($type=='011' || $type=='012' || $type=='013D'|| $type=='025'|| $type=='017'|| $type=='014' || $type=='037'  || $type=='038' )
			                         $lc_draw_arr = "X";	
			$etc_draw_arr="";			  
			  if(substr($etc_draw,0,2)=="20")  $etc_draw_arr= iconv_substr($etc_draw,5,5,"utf-8") ;
			     elseif($etc_su<1) $etc_draw_arr = "X";				  	 

			  $mainassembly_arr="";			  
			  if(substr($mainassembly_date,0,2)=="20")  
				      $mainassembly_arr= iconv_substr($mainassembly_date,5,5,"utf-8");		    
			     elseif($bon_su<1) 
				     $mainassembly_arr= "X";		    
   
			  $lcassembly_arr="";			  
			  if(substr($lcassembly_date,0,2)=="20")  
				  $lcassembly_arr= iconv_substr($lcassembly_date,5,5,"utf-8") ;
			     elseif($lc_su<1  || $type=='011' || $type=='012' ||  $type=='013D' || $type=='025' || $type=='017' || $type=='014'  || $type=='037'  || $type=='038' )
    				 $lcassembly_arr = "X";	
			  $etcassembly_arr="";			  
			  if(substr($etcassembly_date,0,2)=="20")  
				  $etcassembly_arr= iconv_substr($etcassembly_date,5,5,"utf-8") ;
			     elseif($etc_su<1) $etcassembly_arr = "X";				  	 

		  $sqltmp=" select * from ".$DB.".picuploads where parentnum ='$num' and item='ceilingwrap' ";	
		  $tmpmsg = "";
			 try{  
			// 레코드 전체 sql 설정
			   $stmhtmp = $pdo->query($sqltmp);    
			   
			   while($rowtmp = $stmhtmp->fetch(PDO::FETCH_ASSOC)) {
					$tmpmsg = "등록" ;
					}		 
			   } catch (PDOException $Exception) {
				print "오류: ".$Exception->getMessage();
			}  		
				 
				 
			 $workitem="";
				 
				 if($su!="")
					    $workitem= $su . " , "; 
				 if($bon_su!="")
					    $workitem .="본 " . $bon_su . ", "; 					
				 if($lc_su!="")
					    $workitem .="L/C " . $lc_su . ", "; 											
				 if($etc_su!="")
					    $workitem .="기타 "  . $etc_su . ", "; 																	
				 if($air_su!="")
					    $workitem .="공기청정기 "  . $air_su . " "; 																							
						
				 $part="";
				 if($order_com1!="")
					    $part= $order_com1 . "," ; 
				 if($order_com2!="")
					    $part .= $order_com2 . ", " ; 						
				 if($order_com3!="")
					    $part .= $order_com3 . ", " ; 												
				 if($order_com4!="")
					    $part .= $order_com4 . ", " ; 
						
                 $deli_text="";
				 if($delivery!="" || $delipay!=0)
				 		  $deli_text = $delivery . " " . $delipay ;  
           
						   
				// 초기화
				$title = '';

				// 조건에 따라 $title에 문자열 추가
				if (!empty($part1)) {
					$title .= '중국산휀 ' . $part1 . ', ';
				}
				if (!empty($part2)) {
					$title .= '일반휀 ' . $part2 . ', ';
				}
				if (!empty($part3)) {
					$title .= '휠터펜(LH용) ' . $part3 . ', ';
				}
				if (!empty($part4)) {
					$title .= '판넬고정구(금성아크릴) ' . $part4 . ', ';
				}
				if (!empty($part5)) {
					$title .= '비상구 스위치(건흥KH-9015) ' . $part5 . ', ';
				}
				if (!empty($part6)) {
					$title .= '비상등 ' . $part6 . ', ';
				}
				if (!empty($part7)) {
					$title .= '할로겐(7W-6500K) ' . $part7 . ', ';
				}
				if (!empty($part8)) {
					$title .= 'T5(일반) 5W(300) ' . $part8 . ', ';
				}
				if (!empty($part9)) {
					$title .= 'T5(일반) 11W(600) ' . $part9 . ', ';
				}
				if (!empty($part10)) {
					$title .= 'T5(일반) 15W(900) ' . $part10 . ', ';
				}
				if (!empty($part11)) {
					$title .= 'T5(일반) 20W(1200) ' . $part11 . ', ';
				}
				if (!empty($part12)) {
					$title .= 'T5(KS) 6W(300) ' . $part12 . ', ';
				}
				if (!empty($part13)) {
					$title .= 'T5(KS) 10W(600) ' . $part13 . ', ';
				}
				if (!empty($part14)) {
					$title .= 'T5(KS) 15W(900) ' . $part14 . ', ';
				}
				if (!empty($part15)) {
					$title .= 'T5(KS) 20W(1200) ' . $part15 . ', ';
				}
				if (!empty($part16)) {
					$title .= '직관등 600mm ' . $part16 . ', ';
				}
				if (!empty($part17)) {
					$title .= '직관등 800mm ' . $part17 . ', ';
				}
				if (!empty($part18)) {
					$title .= '직관등 1000mm ' . $part18 . ', ';
				}
				if (!empty($part19)) {
					$title .= '직관등 1200mm ' . $part19 . ', ';
				}
				
	// 마지막 쉼표와 공백 제거
	$title = rtrim($title, ', ');	
	//  var_dump($part7);						   
 ?>	 		
					
<tr onclick="redirectToView('<?=$num?>', '<?=$tablename?>')">       
    <td class="text-center" > <?php echo echo_null($start_num) ?></td>
	<td class="text-center"><span class="badge bg-success"><?=$outsourcing?></span></td>
    <td class="text-center" > <?php echo echo_null($orderday) ?></td>
    <td class="text-center"  data-order="<?= $main_draw_arr ?>" ><?php echo echo_null($main_draw_arr) ?></td>
    <td class="text-center"  data-order="<?= $lc_draw_arr ?>" ><?php echo echo_null($lc_draw_arr) ?></td>
    <td class="text-center"  data-order="<?= $etc_draw_arr ?>" ><?php echo echo_null($etc_draw_arr) ?></td>
    <td class="text-center"  data-order="<?= $mainassembly_arr ?>" ><?php echo echo_null($mainassembly_arr) ?></td>
    <td class="text-center"  data-order="<?= $lcassembly_arr ?>" ><?php echo echo_null($lcassembly_arr) ?></td>
    <td class="text-center"  data-order="<?= $etcassembly_arr ?>" ><?php echo echo_null($etcassembly_arr) ?></td>
    <td class="text-center"  data-order="<?= $deadline ?>" ><?php echo echo_null(iconv_substr($deadline, 5, 5, "utf-8")) ?></td>
    <td class="text-center"  data-order="<?= $workday ?>" ><?php echo echo_null(iconv_substr($workday, 5, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null($tmpmsg) ?></td>
    <td class="text-center"  data-order="<?= $demand ?>" ><?php echo echo_null(iconv_substr($demand, 5, 5, "utf-8")) ?></td>
    <td class="text-start "><?php echo echo_null($workplacename) ?></td>
    <td class="text-center"><?php echo echo_null($secondord) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($type, 0, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($inseung, 0, 2, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($car_insize, 0, 9, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null($bon_su) ?></td>
    <td class="text-center"><?php echo echo_null($lc_su) ?></td>
    <td class="text-center"><?php echo echo_null($etc_su) ?></td>
    <td class="text-center"><?php echo echo_null($air_su) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($order_com1, 0, 4, "utf-8")) ?></td>
    <td class="text-center" data-order="<?= $order_date1 ?>" ><?php echo echo_null(iconv_substr($order_date1, 5, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($order_com2, 0, 4, "utf-8")) ?></td>
    <td class="text-center" data-order="<?= $order_date2 ?>" ><?php echo echo_null(iconv_substr($order_date2, 5, 5, "utf-8")) ?></td>
    <td class="text-center"><?php echo echo_null(iconv_substr($order_com3, 0, 4, "utf-8")) ?></td>
    <td class="text-center" data-order="<?= $order_date3 ?>" ><?php echo echo_null(iconv_substr($order_date3, 5, 5, "utf-8")) ?></td>
	<td class="text-start "><?php echo echo_null(iconv_substr($memo, 0, 8, "utf-8")) ?></td>        
  </tr>
	<?php	
	  $start_num--;
		 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }    
 ?>
     <!-- Table body 부분은 아래에 추가 -->
    </tbody>  
    </table>  
</div>
      
   </div> <!--container-->
</form>	
	<div class="container-fluid mt-3 mb-3">
		<? include '../footer_sub.php'; ?>
	</div>
</body>
</html>
<script> 

var dataTable; // DataTables 인스턴스 전역 변수
var ceilingpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('ceilingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var ceilingpageNumber = dataTable.page.info().page + 1;
        setCookie('ceilingpageNumber', ceilingpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('ceilingpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('ceilingpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, tablename) {	
    var url = "view.php?num=" + num + "&tablename=" + tablename;          
	customPopup(url, '조명천장 수주내역', 1850, 900); 		    
}

$(document).ready(function(){	
	$("#writeBtn").click(function(){ 		
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '조명천장 수주내역', 1850, 950); 	
	 });			 
	 
	// 자재현황 클릭시
	$("#rawmaterialBtn").click(function(){ 			
		 popupCenter('/ceiling/list_part_table.php?menu=no'  , '부자재현황보기', 1050, 950);	
	});		 
			
	// 가공스케줄 클릭시
	$("#plan_cutandbending").click(function(){ 
		 popupCenter('/ceiling/plan_cutandbending.php?menu=no'  , '가공 스케줄', 1900, 950);	
	});		 
		
});	


$(document).ready(function() {	

    // 쿠키에서 dateRange 값을 읽어와 셀렉트 박스에 반영
    var savedDateRange = getCookie('dateRange');
    if (savedDateRange) {
        $('#dateRange').val(savedDateRange);
    }

    // dateRange 셀렉트 박스 변경 이벤트 처리
    $('#dateRange').on('change', function() {
        var selectedRange = $(this).val();
        var currentDate = new Date(); // 현재 날짜
        var fromDate, toDate;

        switch(selectedRange) {
            case '최근3개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 3));
                break;
            case '최근6개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 6));
                break;
            case '최근1년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;
            case '최근2년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 2));
                break;
            case '직접설정':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;   
            case '전체':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 20));
                break;            
            default:
                // 기본 값 또는 예외 처리
                break;
        }

        // 날짜 형식을 YYYY-MM-DD로 변환
        toDate = formatDate(new Date()); // 오늘 날짜
        fromDate = formatDate(fromDate); // 계산된 시작 날짜

        // input 필드 값 설정
        $('#fromdate').val(fromDate);
        $('#todate').val(toDate);		
		
		var selectedDateRange = $(this).val();
       // 쿠키에 저장된 값과 현재 선택된 값이 다른 경우에만 페이지 새로고침
        if (savedDateRange !== selectedDateRange) {
            setCookie('dateRange', selectedDateRange, 30); // 쿠키에 dateRange 저장
			document.getElementById('board_form').submit();      
        }		
		
    });
});

function SearchEnter(){
    if(event.keyCode == 13){		
		saveSearch();
    }
}

function saveSearch() {
    let searchInput = document.getElementById('search');
    let searchValue = searchInput.value;

    console.log('searchValue ' + searchValue);

    if (searchValue === "") {        
        document.getElementById('board_form').submit();
    } else {
        let now = new Date();
        let timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        let searches = getSearches();
        // 기존에 동일한 검색어가 있는 경우 제거
        searches = searches.filter(search => search.keyword !== searchValue);
        // 새로운 검색 정보 추가
        searches.unshift({ keyword: searchValue, time: timestamp });
        searches = searches.slice(0, 50);

        document.cookie = "searches=" + JSON.stringify(searches) + "; max-age=31536000";
		
		var ceilingpageNumber = 1;
		setCookie('ceilingpageNumber', ceilingpageNumber, 10); // 쿠키에 페이지 번호 저장		
		// Set dateRange to '전체' and trigger the change event
        $('#dateRange').val('전체').change();
        document.getElementById('board_form').submit();
    }
}

// 검색창에 쿠키를 이용해서 저장하고 화면에 보여주는 코드 묶음
	document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const autocompleteList = document.getElementById('autocomplete-list');  

    searchInput.addEventListener('input', function() {
	const val = this.value;
	let searches = getSearches();
	let matches = searches.filter(s => {
		if (typeof s.keyword === 'string') {
			return s.keyword.toLowerCase().includes(val.toLowerCase());
		}
		return false;
	});			
	   renderAutocomplete(matches);               
    });
	 
    
    searchInput.addEventListener('focus', function() {
        let searches = getSearches();
        renderAutocomplete(searches);   

        console.log(searches);				
    });
			
});

    var isMouseOverSearch = false;
    var isMouseOverAutocomplete = false;

    document.getElementById('search').addEventListener('focus', function() {
        isMouseOverSearch = true;
        showAutocomplete();
    });

	document.getElementById('search').addEventListener('blur', function() {        
		setTimeout(function() {
			if (!isMouseOverAutocomplete) {
				hideAutocomplete();
			}
		}, 100); // Delay of 100 milliseconds
	});


    function hideAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'none';
    }

    function showAutocomplete() {
        document.getElementById('autocomplete-list').style.display = 'block';
    }


function renderAutocomplete(matches) {
    const autocompleteList = document.getElementById('autocomplete-list');    

    // Remove all .autocomplete-item elements
    const items = autocompleteList.getElementsByClassName('autocomplete-item');
    while(items.length > 0){
        items[0].parentNode.removeChild(items[0]);
    }

    matches.forEach(function(match) {
        let div = document.createElement('div');
        div.className = 'autocomplete-item';
        div.innerHTML =  '<span class="text-primary">' + match.keyword + ' </span>';
        div.addEventListener('click', function() {
            document.getElementById('search').value = match.keyword;
            autocompleteList.innerHTML = '';            
			console.log(match.keyword);
            document.getElementById('board_form').submit();    
        });
        autocompleteList.appendChild(div);
    });
}


function getSearches() {
    let cookies = document.cookie.split('; ');
    for (let cookie of cookies) {
        if (cookie.startsWith('searches=')) {
            try {
                let searches = JSON.parse(cookie.substring(9));
                // 배열이 50개 이상의 요소를 포함하는 경우 처음 50개만 반환
                if (searches.length > 50) {
                    return searches.slice(0, 50);
                }
                return searches;
            } catch (e) {
                console.error('Error parsing JSON from cookies', e);
                return []; // 오류가 발생하면 빈 배열 반환
            }
        }
    }
    return []; // 'searches' 쿠키가 없는 경우 빈 배열 반환
}

   
$(document).ready(function(){	
	$("#denkriModel").hover(function(){
		$("#customTooltip").show();
	}, function(){
		$("#customTooltip").hide();
	});

	$("#searchBtn").click(function(){ 	
			saveSearch(); 
		});		

	$("#reportBtn").click(function(){ 	
		// 작업예측
		popupCenter('workreport.php','작업예측',1600,950); 
	 
	 });		
		
	$("#showHolepunching").click(function(){ 	
		// 팝업창으로 홀타공도 띄움     
		popupCenter('showhole.php','홀타공도',1400,900); 
	 
	 });	
	 
	$("#catalog").click(function(){ 	
		// 팝업창으로 카다로그 띄움     
		popupCenter('showcatalog.php','카다로그',1400,900); 
	 
	 });		
		
	var todolist = <?php echo json_encode($todolist); ?>;
	var link = <?php echo json_encode($todolistlink); ?>;

	var htmlstr = '';

	for(i=0;i<todolist.length;i++)
	{
		if(i%2==0)
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >   <span style="background-color:#46D2D2"> ' + todolist[i] + '</span> </a>  &nbsp; ';
		else
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >    ' + todolist[i] + ' </a> &nbsp;&nbsp;';
	}

	if(todolist.length === 0) {
		// $('#deadline_laser').html('<img src="../img/medal.jpg" style="width:7%;" alt="Medal" />  [레이져가공] 없음');
		$('#todolist').hide();
		$('#deadline_laser').text(' 레이져가공 리스트 없음 ');
		// $('#deadline_laser').addClass("marquee");
	} else {
		$('#todolist').html(htmlstr);    
		$('#todolist').show();
		$('#deadline_laser').text('  Laser 미가공(' + todolist.length + '건)  ');
		$('#deadline_laser').removeClass("marquee"); 
	}
				
	var todolist_draw = <?php echo json_encode($todolist_draw); ?>;
	var link_darw = <?php echo json_encode($todolistlink_draw); ?>;

	var htmlstr = '';

	for(i=0;i<todolist_draw.length;i++)
	{
		if(i%2==0)
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link_darw[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >   <span style="background-color:#46D2D2"> ' + todolist_draw[i] + '</span> </a>  &nbsp; ';
		else
			htmlstr += '<a style="font-size:14px;text-decoration:none;" href="#" onclick="window.open(\'view.php?num=' + link_darw[i] + '\',\'조회\',\'left=20,top=20, scrollbars=yes, toolbars=no,width=1800,height=900\')" >    ' + todolist_draw[i] + ' </a> &nbsp;&nbsp;';
	}

	if(todolist_draw.length === 0) {
		// $('#notdrawing').html('<img src="../img/medal.jpg" style="width:7%;" alt="Medal" />  [레이져가공] 없음');
		$('#todolist_draw').hide();
		$('#notdrawing').text(' 레이져가공 리스트 없음 ');
		// $('#notdrawing').addClass("marquee");
	} else {
		$('#todolist_draw').html(htmlstr);    
		$('#todolist_draw').show();
		$('#notdrawing').text('  미설계(' + todolist_draw.length + '건)  ');
		$('#notdrawing').removeClass("marquee"); 
	}
});

$(document).ready(function() {
    $('.search-condition').change(function() {
        // 모든 체크박스의 선택을 해제합니다.
        $('.search-condition').not(this).prop('checked', false);

        // 선택된 체크박스의 값으로 `check` 필드를 업데이트합니다.
        var condition = $(this).is(":checked") ? $(this).val() : '';
        $("#check").val(condition);

        // 검색 입력란을 비우고 폼을 제출합니다.
        // $("#search").val('');                                                      
        $('#board_form').submit();  
    });
});
</script> 

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // D- 날짜 선택 시 페이지 업데이트
    document.getElementById('dDaySelect').addEventListener('change', function() {
      var selectedDday = this.value;
      window.location.href = '?dDay=' + selectedDday;
    });

    // URL에서 dDay 값을 가져와서 선택 상태로 설정
    var urlParams = new URLSearchParams(window.location.search);
    var dDayParam = urlParams.get('dDay');
    if (dDayParam) {
      document.getElementById('dDaySelect').value = dDayParam;
      document.getElementById('laserBadge').innerText = 'D-' + dDayParam + ' laser 미가공 List';
      document.getElementById('drawBadge').innerText = 'D-' + dDayParam + ' 미설계 List';
    }

    // deadline_laser 클릭 이벤트 리스너 추가
    document.getElementById('deadline_laser').addEventListener('click', function() {
      var displayListElement = document.getElementById('display_list');
      if (displayListElement.style.display === 'none') {
        displayListElement.style.display = 'table-cell';
      } else {
        displayListElement.style.display = 'none';
      }
    });	       
		
    // notdrawing 클릭 이벤트 리스너 추가
    document.getElementById('notdrawing').addEventListener('click', function() {
      var displayListElement = document.getElementById('display_list');
      if (displayListElement.style.display === 'none') {
        displayListElement.style.display = 'table-cell';
      } else {
        displayListElement.style.display = 'none';
      }
    });
  });
  
$(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   // title = '품질방침/품질목표';
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	
  
  
</script>