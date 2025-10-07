<!-- 아래 코드에 모든 소스 코드가 담겨 있지 않으므로 일부 수정 및 추가가 필요합니다. -->
<!-- jQuery, Ajax를 활용함으로 jQuery 사용이 필요합니다. -->

<script language="javascript">
   function code_check(){
   	if(!checkInput_null('frm1','code1,code2,code3')){ frm1.overlap_code_ok.value = "";}
    else{
    	document.frm1.code.value = frm1.code1.value + frm1.code2.value + frm1.code3.value;
		var data = {
			"b_no":[document.frm1.code.value]	
		};   			
			$.ajax({
				url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=EFI7Fchltxh8LNyMu%2BUE9GSklj4ZsJqpL1UAYP6S0ci9D7fqJA98RRdxJos8KxwwEr6L9GAuAEB6E9IA1v1j2Q%3D%3D", //활용 신청 시 발급 되는 serviceKey값을 [서비스키]에 입력해준다.
				type: "POST",
				data: JSON.stringify(data), // json 을 string으로 변환하여 전송
				dataType: "JSON",
				traditional: true,
				contentType: "application/json; charset:UTF-8",
				accept: "application/json",
				success: function(result) {
					console.log(result);
					if(result.match_cnt == "1") {
						//성공
						console.log("success");
						document.frm1.submit();
					} else {
						//실패
						console.log("fail");
						alert(result.data[0]["tax_type"]);
					}
				},
				error: function(result) {
					console.log("error");
					console.log(result.responseText); //responseText의 에러메세지 확인
				}
			});
 		}
   }
</script>

<form name="frm1" method="post" action="..">
<table class="tb_board_1">
	<tr>
		<th scope="row">사업자등록번호</th>
		<td  class="left_5">
			<div>
                <input type="text" name="code1" value="" size="3" alt="사업자등록번호1" style="ime-mode:disabled;" maxlength="3">
                -<input type="text" name="code2" value="" size="2" alt="사업자등록번호2" style="ime-mode:disabled;" maxlength="2">
                -<input type="text" name="code3" value="" size="5" alt="사업자등록번호3" style="ime-mode:disabled;" maxlength="5">
            </div>
			<span style="cursor:hand" onclick="code_check();">확인</span>
		</td>
	</tr>
</table>
</form>