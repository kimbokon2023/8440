<?php
 session_start();

 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }
 
 // ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
require_once("../lib/mydb.php");
$pdo = db_connect();     

// 작품명 배열에 넣기

 				
$sql="select * from mirae8440.shopitem order by num desc" ; 					
$sqlcon = "select * from mirae8440.shopitem order by num desc" ;   // 전체 레코드수를 파악하기 위함.					

$workarr = array();
$filenamearr = array();

try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];			  		  			  
			  $item=$row["item"];			  
			  $filename1=$row["filename1"];	 	
           $workarr[$num] = $item ;		  
           $filenamearr[$num] = $filename1 ;		  
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  
  
 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}	 

   
?>   

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>미래기업 금속제작품 관리자화면</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css"> 
 <link href="./css/bootstrapmodal.css" rel="stylesheet" />		
 
   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>    
 .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
    
        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 50%; /* Could be more or less, depending on screen size */                          
        }
        /* The Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

		
 .input-form {
      max-width: 680px;

      margin-top: 15px;
      padding: 20px;

      background: #fff;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      border-radius: 10px;
      -webkit-box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15);
      -moz-box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15);
      box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15)
    }		
		
    </style>

</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="admin.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->
 
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4"  >
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="Mirae Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">금속제작품 관리</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
 
          </li>
          <li class="nav-item menu-open">
            <a href="cart.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                장바구니
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
 
          </li>
          <li class="nav-item menu-open">
            <a href="order.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                주문하기
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
 
          </li>
          <li class="nav-item menu-open">
            <a href="delivery.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                주문&배송
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
 
          </li>
          <li class="nav-item menu-open">
            <a href="deletebin.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                휴지통
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
 
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">발주 현황판</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">금속제작품</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <!--  <div class="card-header border-0">
               <div class="d-flex justify-content-between">
                   <h3 class="card-title">발주 리스트(order list)</h3>                  
                 </div>
              </div> -->
              <div class="card-body">
                <div class="d-flex justify-content-between">
                        <!-- <div class="limit"> -->
						<ul class="list-group">
						  <li class="list-row list-row--header">
							<div class="list-cell list-cell--70">순서</div>
							<div class="list-cell list-cell--80">접수일시</div>
							<div class="list-cell list-cell--60">진행<br> 상태</div>
							<div class="list-cell list-cell--100">결제<br> 금액</div>
							<div class="list-cell list-cell--70">배송비</div>
							<div class="list-cell list-cell--50">결재<br>여부</div>							
							<div class="list-cell list-cell--200">주문정보</div>
							<div class="list-cell list-cell--80">P/W</div>
							<div class="list-cell list-cell--80">주문이</div>
							<div class="list-cell list-cell--80">연락처</div>
							<div class="list-cell list-cell--80">받는이</div>
							<div class="list-cell list-cell--80">연락처</div>
							<div class="list-cell list-cell--100">주소1</div>
							<div class="list-cell list-cell--100">주소2</div>
							<div class="list-cell list-cell--100">요청사항</div>																					
							
							<div class="list-cell list-cell--100">추천코드</div>
							<div class="list-cell list-cell--50">&nbsp;</div>
						  </li>	                
                <!-- /.d-flex -->
				
<?
				
$sql="select * from mirae8440.shop where delvalue!=1 order by num desc" ; 					
$sqlcon = "select * from mirae8440.shop where delvalue!=1 order by num desc" ;   // 전체 레코드수를 파악하기 위함.					
		
   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 


		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			$num=$row["num"];
			$password = $row["password"];
			$state =$row["state"];
			$orderdate =$row["orderdate"];
			$orderlist = $row["orderlist"];   // orderlist 복호화 중요함 핵심기술
			$name= $row["name"]; 
			$tel= $row["tel"]; 
			$receivename= $row["receivename"]; 
			$receivetel= $row["receivetel"]; 
			$email= $row["email"]; 
			$address= $row["address"]; 
			$address2= $row["address2"]; 
			$request= $row["request"]; 
			$code= $row["code"];
			$deliveryfee= $row["deliveryfee"];  // 3000원으로 배송비 일단 세팅함	 		
			$payment= $row["payment"];    // 결재확인중, 결재완료   두가지 형태 결과

// orderlist를 파싱하는 구문 (중요함) 핵심기술
 

$arr = explode(',', $orderlist);  // comma로 구분된 자료 불러오기	

// var_dump($arr);	  	
  
$count =0 ;  
$idarr = array();
$quantityarr = array();
$salepricearr = array();

// print count($arr);

$orderliststr = "";
$totalsaleprice = 0;
 foreach($arr as $value) { 
 // id의 값을 추출해서 배열에 넣는다
	preg_match('/id(.*?)quantity/',$value, $match);  // 결과물 match에 저장됨
	$idarr[$count] = $match[1];	
 // quantity 수량의 값을 추출해서 배열에 넣는다	
	preg_match('/quantity(.*?)saleprice/',$value, $match);  // 결과물 match에 저장됨
	$quantityarr[$count] = $match[1];	
 // 개당 판매금액 saleprice 값을 추출해서 배열에 넣는다	
	preg_match('/saleprice(.*?) /',$value, $match);  // 결과물 match에 저장됨
	$salepricearr[$count] = $match[1];	
	
    $orderliststr .= '작품번호 : ' . $idarr[$count] .'작품명 : ' . $workarr[intval($idarr[$count])] . ', 수량 : ' . $quantityarr[$count] . ', 금액 : ' . number_format(intval($quantityarr[$count]) * intval($salepricearr[$count])) . ' <br><br> '; 		 		
	$totalsaleprice += intval($quantityarr[$count]) * intval($salepricearr[$count]);
	$count++;
 } 
 /*
 var_dump($value);
	print '밸류 하나찍고 ' ;
	var_dump($match[0]);
	print '-> match[0] 하나찍고 ' ;
	var_dump($match[1]);
	print '-> match[1] 하나찍고 ' ;
	*/
			 


				?>
		    <li class="list-row" id="list<?=$num?>" >						
			
		     <a id="updateitem" class="list-link" style="text-decoration:none;" a href="#" onclick="window.open('adminupdate.php?num=<?=$num?>', '자료수정','left=300,top=100, scrollbars=yes, toolbars=no,width=1400,height=800');" > 
             <div class="list-cell list-cell--70">   <?=$start_num?>				</div>
            <div class="list-cell list-cell--80">	 <?=$orderdate?>		</div>				
            <div class="list-cell list-cell--60">	 <?=$state?>		</div>				
            <div class="list-cell list-cell--100 color-red">	  	 <?=number_format($totalsaleprice+$deliveryfee)	?>	 	</div>
            <div class="list-cell list-cell--70 color-blue">	 <?=number_format($deliveryfee)?>	</div>                        			
            <div class="list-cell list-cell--50 <?$payment=='결재완료' ? print 'color-green' : print 'color-gray'?> " >	 <?=$payment?>	</div>                        			
            <div class="list-cell list-cell--200">	 <?=$orderliststr?>		</div>				
            <div class="list-cell list-cell--80">	 <?=$password?>		</div>				
            <div class="list-cell list-cell--80">	 <?=$name?> 			</div>
            <div class="list-cell list-cell--80">	 <?=$tel?> 			</div>
            <div class="list-cell list-cell--80">	 <?=$receivename?> 			</div>
            <div class="list-cell list-cell--80">	 <?=$receivetel?> 			</div>
            <div class="list-cell list-cell--100">	 <?=$address?> 			</div>
            <div class="list-cell list-cell--100">	 <?=$address2?> 			</div>			
            <div class="list-cell list-cell--100">	 <?=$request?> 			</div>            
            <div class="list-cell list-cell--100 ">	 <?=$code?>	</div>         </a>                           
            <div class="list-cell list-cell--50 ">	 <button type="button" id="delBtn"  onclick="delitem('<?=$num?>')" class="btn btn-outline-danger" > 삭제  </button>		</div>                                    
			
			
          </li>			
			    
				
			<?php
			$start_num--;  
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
       </ul>
	   </div>
 	<div style="float:left; width:100%;text-align : center; font-size:25px;"> 	 
         <?php
            if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-secondary" type="button" id=previousListBtn  onclick="movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  [' . $i . ']  </span>'; 
              else 
                   print '<button class="btn btn-secondary" type="button" id=moveListBtn onclick="movetoPage(' . $i . ')"> [' . $i . ']</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-secondary" type="button" id=nextListBtn onclick="movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
            }
            ?>              
    </div>				
			
			
	           
	          </div>	<!-- /.card body-->	
            <!-- /.card -->
           </div>
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Sales</h3>
                  <a href="javascript:void(0);">View Report</a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">$18,230.00</span>
                    <span>Sales Over Time</span>
                  </p>
                  <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 33.1%
                    </span>
                    <span class="text-muted">Since last month</span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="200"></canvas>
                </div>
              </div>
            </div>
            
                </div>
                <!-- /.d-flex -->
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2022 <a href="http://8440.co.kr"> &copy; 미래기업 </a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- AdminLTE -->
<script src="dist/js/adminlte.js"></script>

  <form id=Form1 name="Form1">
    <input type=hidden id="ordernum" name="ordernum" >
  </form> 

</body>
</html>

<script>

$(document).ready(function(){
	
	 
	
});

function updatemodalshow(num)
{
  $('#myModal').modal('show');
  
}


function delitem(num)
{   	
$('#ordernum').val(num);	
 $.ajax({
			url: "admindeleteshop.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
			console.log( data);
			
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });  
// 화면의 요소를 제거한다.
$('#list' + num).remove();		   
	  	    						    	
 }
  
 
</script>