
<?php
  
   
 // include $_SERVER['DOCUMENT_ROOT'] . "/load_company.php";
 
 // $companycount = count($suply_company_arr);   
 // // var_dump($suply_company_arr);
 // // 납품업체 숫자 넘겨줌 
 
// include $_SERVER['DOCUMENT_ROOT'] . '_request.php';
  
// $callback=$_REQUEST["callback"];  // 출고현황에서 체크번호
  
  // if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $mode=$_REQUEST["mode"];
  // else
   // $mode="";

  // if(isset($_REQUEST["which"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $which=$_REQUEST["which"];
  // else
   // $which="2";
  
  // if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $num=$_REQUEST["num"];
  // else
   // $num="";

   // if(isset($_REQUEST["page"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $page=$_REQUEST["page"];
  // else
   // $page=1;   

  // if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $search=$_REQUEST["search"];
  // else
   // $search="";
  
  // if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $find=$_REQUEST["find"];
  // else
   // $find="";

  // if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $process=$_REQUEST["process"];
  // else
   // $process="전체";

  // if(isset($_REQUEST["scale"]))  //수정 버튼을 클릭해서 호출했는지 체크
   // $scale=$_REQUEST["scale"];
  // else
   // $scale=50;

// $fromdate=$_REQUEST["fromdate"];	 
// $todate=$_REQUEST["todate"];


  // if(isset($_REQUEST["regist_state"]))  // 등록하면 1로 설정 접수상태
   // $regist_state=$_REQUEST["regist_state"];
  // else
   // $regist_state="1";

 // $year=$_REQUEST["year"];   // 년도 체크박스
 
 
// //  철판리스트 뽑기 
   
  // require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
  // $pdo = db_connect();	
  

// // 구매처 읽어오기  
     
// // 철판종류에 대한 추출부분
  
   // $sql="select * from mirae8440.steelsource order by sortorder asc, item desc "; 					

	 // try{  

   // $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   // $counter=0;
   // $item_counter=1;
   // $steelsource_num=array();
   // $steelsource_item=array();
   // $steelsource_spec=array();
   // $steelsource_take=array();
   // $steelsource_item_yes=array();
   // $steelsource_spec_yes=array();
   // $spec_arr=array();
   // $company_arr=array();
   // $title_arr=array();
   // $last_item="";
   // $last_spec="";
   // $pass='0';
   // while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

	   
 			  // $steelsource_num[$counter]=$row["num"];			  
 			  // $steelsource_item[$counter]=trim($row["item"]);
 			  // $steelsource_spec[$counter]=trim($row["spec"]);
		      // $steelsource_take[$counter]=trim($row["take"]);   
			  
			  // if($steelsource_item[$counter]!=$last_item)
			  // {
				 // $last_item= $steelsource_item[$counter];
			     // $steelsource_item_yes[$item_counter]=$last_item;
				 // $item_counter++;
			  // }
			 
			  // $counter++;
	 // } 	 
   // } catch (PDOException $Exception) {
    // print "오류: ".$Exception->getMessage();
// }    

// array_push($steelsource_item_yes," ");
// $steelsource_item_yes = array_unique($steelsource_item_yes);
// sort($steelsource_item_yes);

// $sumcount = count($steelsource_item_yes);
	
	
	
 // // 기간을 정하는 구간
// $fromdate=$_REQUEST["fromdate"];	 
// $todate=$_REQUEST["todate"];	 

// if($fromdate=="")
// {
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	// $fromdate=$fromdate . "-01-01";
// }
// if($todate=="")
// {
	// $todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	// $Transtodate=strtotime($todate.'+1 days');
	// $Transtodate=date("Y-m-d",$Transtodate);
// }
    // else
	// {
	// $Transtodate=strtotime($todate);
	// $Transtodate=date("Y-m-d",$Transtodate);
	// }
		  
   // if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 // $find=$_REQUEST["find"];
 
// $sql="select * from mirae8440.steelsource order by sortorder asc, item asc, spec asc"; 	// 정렬순서 정함.				

// $sum_title=array(); 
// $sum= array();

 // try{  
   // $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   // $rowNum = $stmh->rowCount();  
   // $counter=0;
   // $steelsource_num=array();
   // $steelsource_item=array();
   // $steelsource_spec=array();
   // $steelsource_take=array();   
   // while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
 			  // $steelsource_num[$counter]=$row["num"];			  
 			  // $steelsource_item[$counter]=trim($row["item"]);
 			  // $steelsource_spec[$counter]=trim($row["spec"]);
		      // $steelsource_take[$counter]=trim($row["take"]);  
              // array_push($sum_title, $steelsource_item[$counter] . $steelsource_spec[$counter]. $steelsource_take[$counter]) ;
              // array_push($company_arr, $steelsource_take[$counter]) ;
	   // $counter++;
	 // } 	 
   // } catch (PDOException $Exception) {
    // print "오류: ".$Exception->getMessage();
// }  

// $sum_title = array_unique($sum_title);  // 고유번호이름만 살리기
// sort($sum_title);  // 고유번호이름만 살리기

 // // 전체합계(입고부분)를 산출하는 부분 
// $sql="select * from mirae8440.steel order by outdate";
 
// $tmpsum = 0; 

 // try{  
// // 레코드 전체 sql 설정
   // $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   // while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			  // $outdate=$row["outdate"];			  
			  // $item=trim($row["item"]);			  
			  // $spec=trim($row["spec"]);
			  // $steelnum=$row["steelnum"];			  
			  // $company=$row["company"];
			  // $comment=$row["comment"];
			  // $which=$row["which"];	 	
			  
			  
        // // 일반매입처리
        // if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
            // $company = '';
        // }
			  
			  // $tmp=$item . $spec . $company;
	
        // for($i=0;$i<count($sum_title) ; $i++) {  	          
			  // if($which=='1' and $tmp==$sum_title[$i])
				     // $sum[$i]= $sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
			  // if($which=='2' and $tmp==$sum_title[$i])
				    // $sum[$i] =  $sum[$i] - (int)$steelnum;
		           // }
			// }		 
   // } catch (PDOException $Exception) {
    // print "오류: ".$Exception->getMessage();
// }  
      

// // 철판 종류 불러오기
// $sql="select * from mirae8440.steelitem"; 					

	 // try{  

   // $stmh = $pdo->query($sql);            
   // $rowNum = $stmh->rowCount();  
   // $counter=0;
   // $steelitem_arr=array();

   // while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
 			  // array_push($steelitem_arr,trim($row["item"]));
			 
			  // $counter++;
	 // } 	 
   // } catch (PDOException $Exception) {
    // print "오류: ".$Exception->getMessage();
// }    
 // array_push($steelitem_arr,'304 Mirror 1.2T');
   // $item_counter=count($steelitem_arr);
   // sort($steelitem_arr);  // 오름차순으로 배열 정렬   
    
   
// // 철판 규격 불러오기
// $sql="select * from mirae8440.steelspec"; 					

	 // try{  

   // $stmh = $pdo->query($sql);            
   // $rowNum = $stmh->rowCount();  
   // $counter=0;
   // $spec_arr=array();

   // while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
 			  // $spec_arr[$counter]=trim($row["spec"]);
			 
			  // $counter++;
	 // } 	 
   // } catch (PDOException $Exception) {
    // print "오류: ".$Exception->getMessage();
// }    

   // $spec_counter=count($spec_arr);
   // sort($spec_arr);  // 오름차순으로 배열 정렬
   
    
  // if ($mode=="modify"){
    // try{
      // $sql = "select * from mirae8440.eworks where num = ?  ";
      // $stmh = $pdo->prepare($sql); 

      // $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      // $stmh->execute();
      // $count = $stmh->rowCount();            
	  // $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    // if($count<1){  
      // print "결과가 없습니다.<br>";
     // }else{
		  // include '_row.php';		  
	  
			 // if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					// else $indate="";	 
			 // if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					// else $outdate="";	 					
			  
      // }
     // }catch (PDOException $Exception) {
       // print "오류: ".$Exception->getMessage();
     // }
  // }
    
  // if ($mode!="modify"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  // $outdate=date("Y-m-d");
			  // $requestdate=null;
			  // $indate=null;
			  // $outworkplace=$row["outworkplace"];
			  
			  // $model=null;			  
			  // $steel_item=null;			  
			  // $spec=null;
			  // $steelnum=null;			  
			  // $company="";
			  // $supplier="";
			  // $request_comment=null;
			  // $inventory=null;
			  // $which="1";	 			   // 요청 
  // } 

// // print '$item';
// // var_dump($item);
// // var_dump($steelitem_arr);

?> 
 



<!--전자결재 리스트창 -->
<!--Extra Full Modal -->
<div class="modal fade" id="request_form" tabindex="-99">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> 전자결재 </h4>
                <button type="button" class="btn btn-light-secondary" id="closeModalxBtn">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block"><i class="bi bi-x"></i></span>
                </button>
            </div>
            <div class="modal-body mb-1">
                <div class="card mb-2">
                    <div class="card-content">
                        <div class="card-body">
						<div id="eworksNavContainer">
						
						
						</div>
						<div class="d-flex mt-3 mb-3 justify-content-center" >								
							<button class="btn btn-dark btn-sm me-2" type="button" id="E_searchAllBtn" > 전체 </button>

							<input type="text" id="search" name="search" class=" me-2" value="<?=$search?>" onkeydown="if (event.keyCode === 13) enterkey()" >
							<button class="btn btn-dark btn-sm  me-2" type="button" onclick="enterkey(); " > <ion-icon name="search-outline"></ion-icon> </button> </span> 

							<button class="btn btn-dark btn-sm  me-2" type="button" onclick="viewEworks_detail('',1);" > <i class="bi bi-pencil-square"></i> 작성 </button>
						</div>						
                             

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end mt-3">			 
                <button type="button" id="closeEworksBtn" class="btn btn-outline-dark btn-sm">
                    <ion-icon name="close-outline"></ion-icon> 닫기
                </button>
            </div>
        </div>
    </div>
</div>
