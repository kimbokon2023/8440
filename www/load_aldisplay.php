<?php\nrequire_once __DIR__ . '/common/functions.php';
require_once(includePath('session.php'));

$tablename = "eworks";

  require_once("./lib/mydb.php");
  $pdo = db_connect();	
  include "./annualleave/load_DB.php";
 
  $page=1;	 
  $scale = 20;       // 한 페이지에 보여질 게시글 수
  $page_scale = 20;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
  $now = date("Y-m-d",time()) ;
  $sql = "SELECT * FROM " . $DB . "." . $tablename . "  WHERE (al_askdatefrom <= CURDATE() AND al_askdateto >= CURDATE()) AND is_deleted IS NULL ";

   try {  
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1=$stmh->rowCount();    
    $total_row=$temp1;	

// print $sql;	
?>

<style>
/* Modern Annual Leave Table Styling */
:root {
    --dashboard-primary: #e0f2fe;
    --dashboard-secondary: #b3e5fc;
    --dashboard-accent: #0288d1;
    --dashboard-text: #01579b;
    --dashboard-text-secondary: #0277bd;
    --dashboard-border: #b0e6f7;
    --dashboard-hover: #f1f8fe;
    --dashboard-shadow: 0 2px 12px rgba(2, 136, 209, 0.08);
}

.modern-al-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.modern-al-table th {
    background: var(--dashboard-secondary);
    color: var(--dashboard-text);
    padding: 0.6rem 0.4rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    border-bottom: 1px solid var(--dashboard-border);
}

.modern-al-table td {
    padding: 0.6rem 0.4rem;
    font-size: 0.75rem;
    text-align: center;
    border-bottom: 1px solid #f0f9ff;
    transition: background-color 0.2s ease;
}

.modern-al-table tr:hover td {
    background-color: var(--dashboard-hover);
    cursor: pointer;
}

.modern-al-table td:first-child {
    font-weight: 600;
    color: var(--dashboard-text);
}

.modern-al-table td:nth-child(2) {
    color: var(--dashboard-accent);
    font-weight: 500;
}
</style>

<table class="modern-al-table">
    <thead>
        <tr>
            <th scope="col">신청인</th>
            <th scope="col">구분</th>
            <th scope="col">일자</th>
            <th scope="col">기간</th>
            <th scope="col">사유</th>
        </tr>
    </thead>
    <tbody> 
        <?php  
        if ($page<=1)  
            $start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
        else 
            $start_num=$total_row-($page-1) * $scale;
    
        while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {		
            include "./annualleave/rowDBask.php";	   	
            $totalusedday = 0;
            $totalremainday = 0;		
            for($i=0;$i<count($totalname_arr);$i++)	 
                if($author== $totalname_arr[$i])
                {
                    $availableday  = $availableday_arr[$i];
                }	
            for($i=0;$i<count($totalname_arr);$i++)	 
                if($author== $totalname_arr[$i])
                {
                    $totalusedday = $totalused_arr[$i];
                    $totalremainday = $availableday - $totalusedday;	
                }	
					
	// 연도를 제거하고 나오게 하기				
	if ($al_askdatefrom !== $al_askdateto) {
		// 연도 부분 추출
		preg_match('/\d{4}-(\d{2}-\d{2})/', $al_askdatefrom, $matches_from);
		preg_match('/\d{4}-(\d{2}-\d{2})/', $al_askdateto, $matches_to);
		
		// 추출된 연도 부분 사용
		$datestr = $matches_from[1] . ' ~ <br>' . $matches_to[1];
	} else {
		// 연도 부분 추출
		preg_match('/\d{4}-(\d{2}-\d{2})/', $al_askdatefrom, $matches_from);
		
		// 추출된 연도 부분 사용
		$datestr = $matches_from[1];
	}

				   
				
        ?>	   
         <tr onclick="window.location.href='./annualleave/index.php'" style="cursor:pointer;">             
            <td><?=$author?></td>
            <td><?=$al_item?></td>
            <td><?=$datestr?></td>            
            <td><?=$al_usedday?></td>
            <td><?=$al_content?></td>
        </tr>
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

    </tbody>
</table>
