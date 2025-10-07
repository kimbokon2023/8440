  <?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 // 첫 화면 표시 문구
 $title_message = '(주) 미래기업 안전보건 ';
$menu=$_REQUEST["menu"];

?>

 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

 <title> <?=$title_message?> </title> 
 
</head>
 
 
 <body>  
 
 
 <?php
  
 $num=$_REQUEST["num"]; 
 $tablename='s_board';  
  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();  
 
 try{
     $sql = "select * from mirae8440." . $tablename . " where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];   
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $content = $row["content"];
     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date, 0, 10);   
     $item_hit     = $row["hit"];     
     $is_html      = $row["is_html"];
     $division      = $row["division"];
     $searchtext      = $row["searchtext"];	 
	 
	 
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	 
      
// 초기 프로그램은 $num사용 이후 $id로 수정중임  
$id=$num;   
  
 ?> 

 
      
<div class="container">  
 <div class="d-flex mt-3 mb-3 justify-content-center">  
	<h1> <?=$title_message?>  </h1>  
  </div>	 
 
<div class="d-flex  p-1 m-1 mt-2 mb-2 justify-content-left  align-items-center">  				
		
	
</div> 
 
  <div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white"> 		
    <h2>		  
		제목: &nbsp; <?= $item_subject ?>
		 <?= $noticecheck_memo ?> | 작성일 :  <?= $item_date ?> 
     </h2>  
	 </div>
   <div class="row d-flex  p-2 m-2 mt-1 mb-1 justify-content-left "> 	  
	  <?=$content ?>
    </div>
	  
	
   
		<div id ="displayimage" class="row d-flex mt-3 mb-1 justify-content-center" style="display:none;">  	 		 					 
		</div>		
			
		<div id ="displayfile" class="d-flex mt-3 mb-1 justify-content-left" style="display:none;">  	 		 					 
		</div>			
<div class="row d-flex  p-2 m-2 mt-5 mb-5 justify-content-left "> 	  
</div>
 	
 </div> 
 
 </body>
 </html>    
 
 
