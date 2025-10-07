<?php
// 초기화
$curl = curl_init();

// 로그인에 필요한 사용자 이름과 비밀번호 설정
$username = 'coolsalespro';
$password = 'rnstks100';
$loginUrl = 'https://nid.naver.com/nidlogin.login?mode=form&url=https://www.naver.com/'; // 로그인 URL

// cURL 옵션 설정
curl_setopt($curl, CURLOPT_URL, $loginUrl);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, "username=$username&password=$password");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt'); // 쿠키를 저장할 파일 지정
curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt'); // 쿠키 파일 사용

// 요청 실행 및 응답 받기
$response = curl_exec($curl);

// 오류 확인
if(curl_errno($curl)){
    echo 'Request Error:' . curl_error($curl);
}

// cURL 세션 종료
curl_close($curl);

// 응답 출력
echo $response;
?>

<script>
window.onload = function() {
    document.getElementById('id').value = 'coolsalespro';
    document.getElementById('pw').value = 'rnstks100';
	
	// 로그인유지
	var checkbox = document.getElementById('keep');
	checkbox.checked = !checkbox.checked; // 현재 상태를 반전시킵니다 (체크되어 있지 않으면 체크, 체크되어 있으면 해제)
	
	// 보안 IP off
	document.getElementById('switch').checked = true;

	
	document.getElementById('log.login').click();
	
};
</script>
