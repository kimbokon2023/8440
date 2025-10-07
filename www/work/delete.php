<?php   

if(!isset($_SESSION))      
	session_start(); 
if(isset($_SESSION["DB"]))
	$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문        
$num=$_REQUEST["num"]; 
$tablename=$_REQUEST["tablename"];    
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();    

include 'request.php';

$upload_dir = '../uploads/';    // 원본 파일 저장 위치
$trash_dir = './trash/';       // 휴지통 폴더 위치

if (!file_exists($trash_dir)) {
    mkdir($trash_dir, 0777, true);  // 휴지통 폴더가 없다면 생성
}

try {
    // 레코드 검색 및 추출
    $stmh = $pdo->prepare("SELECT * FROM mirae8440.work WHERE num = ?");
    $stmh->execute([$num]);
    $row = $stmh->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // JSON 형식으로 변환
        $jsonData = json_encode($row);

        // 파일로 저장 (파일 이름은 레코드 ID와 현재 날짜/시간을 사용)
        $backupFileName = $trash_dir . "trash_" . $num . "_" . date("Y-m-d_H-i-s") . ".json";
        file_put_contents($backupFileName, $jsonData);
    }
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}		

try {
    $sql = "select * from mirae8440.work where num = ?";
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1,$num,PDO::PARAM_STR); 
    $stmh->execute();

    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $copied_name[0] = $row['file_copied_0'];
    $copied_name[1] = $row['file_copied_1'];
    $copied_name[2] = $row['file_copied_2'];

    for ($i=0; $i<3; $i++) { 
        if ($copied_name[$i]) {
            $image_name = $upload_dir . $copied_name[$i];
            $new_location = $trash_dir . $copied_name[$i];
            rename($image_name, $new_location); // 파일을 휴지통으로 이동
        }
    }
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}

try {
    $pdo->beginTransaction();
    $sql = "delete from mirae8440.work where num = ?";  
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$num,PDO::PARAM_STR);      
    $stmh->execute();   
    $pdo->commit();

   
} catch (Exception $ex) {
    $pdo->rollBack();
    print "오류: ".$Exception->getMessage();
}

 
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));        
?>