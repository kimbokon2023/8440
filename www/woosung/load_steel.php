<?

$item = array();
$spec = array();
$steelnum = array();
$unitprice = array();
$amount = array();
$tax = array();
$totalamount = array();
$comment = array();

$title =  explode( '|', $st_content );

if(count($title)>10)  // 자료수가 10개 넘어가면
	$countnum = count($title) ;
  else
   $countnum = 10 ;

// 자료가 있는 경우는 불러온다.
if($st_content==null)
{
	for($i=0;$i<$countnum;$i++)
	   {	  
		  array_push($item, "");   
		  array_push($spec, "");   
		  array_push($steelnum, "");   
		  array_push($unitprice, "");   
		  array_push($amount, "");   
		  array_push($tax, "");  
		  array_push($totalamount, "");  
		  array_push($comment, "");  
		 }
} 	// end of if
else
{
		
	$explode = explode( '|', $st_content );	
		
	$ColNum = 8;	
	$MinNum = $ColNum * 10 ;
	
    $steelnum_val = 0;	
    $unitprice_val = 0;	
	$tax_val = 0;
	$amount_val = 0;
	$totalamount_val = 0;
	
	$item_str='';
	$spec_str='';
	
	
	for($i=0;$i<$MinNum-1;$i++)  
	{
	   $mod = ($ColNum + $i) % $ColNum ;
		if(count($explode)<=$MinNum)  // 데이터가 범위내 있을 경우 실행
		  {
		   if($mod == 0 )
			  array_push($item, $explode[$i]);
		   if($mod == 1 )
			  array_push($spec, $explode[$i]);
		   if($mod == 2 )
			  array_push($steelnum, $explode[$i]);
		   if($mod == 3 )
			  array_push($unitprice, $explode[$i]);
		   if($mod == 4 )
			  array_push($amount, $explode[$i]);
		   if($mod == 5 )		   
			  array_push($tax, $explode[$i]);
		   if($mod == 6 )
			  array_push($totalamount, $explode[$i]);
		   if($mod == 7 )
			  array_push($comment, $explode[$i]);
		   }
		 else
		 {
		   if($mod == 0 )
			  array_push($item, ' ');
		   if($mod == 1 )
			  array_push($spec, ' ');
		   if($mod == 2 )
			  array_push($steelnum, ' ');
		   if($mod == 3 )
			  array_push($unitprice, ' ');
		   if($mod == 4 )
			  array_push($amount, ' ');
		   if($mod == 5 )
			  array_push($tax, ' ');
		   if($mod == 6 )
			  array_push($totalamount, ' ');
		   if($mod == 7 )
			  array_push($comment, ' ');		

		 }
	} // end of for

	
	for($i=0;$i<count($item);$i++)  
	{
		      $steelnum_val += (float)$steelnum[$i];
		      $unitprice_val += (float)$unitprice[$i];
		      $tax_val += (float)$tax[$i];
		      $amount_val += (float)$amount[$i];
		      $totalamount_val += ((float)$tax[$i] + (float)$amount[$i]) ;
			  
			  if($item[$i]!=null)
			  {
				$item_str .= $item[$i]  . '<br>' ;
				$spec_str .= $spec[$i]  . '<br>' ;
			  }
			  else
				  {
					$item_str = $item_str;
				  }			  
			  
			  
	}

// 평균단가 = 총금액 / 수량합  공식적용
$unitprice_val = $totalamount_val/ $steelnum_val ;

// 합 계산 
$totalamount_int = 0 ;
for($i=0 ; $i < count($amount); $i++) 	
	$totalamount_int += (int)str_replace(',', '', $amount[$i]);

$totalamount_str = number_format($totalamount_int);

}  // end of else


?>