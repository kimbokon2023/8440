
function setCookie (cookie_name, value, minutes) {
    const exdate = new Date();
    exdate.setMinutes(exdate.getMinutes() + minutes);
    // const cookie_value = escape(value) + ((minutes == null) ? '' : '; expires=' + exdate.toUTCString());
    const cookie_value = value + ((minutes == null) ? '' : '; expires=' + exdate.toUTCString()); // 암호화 끔
    // path=/ 추가하여 전체 사이트에서 쿠키 사용 가능하게 설정 (로컬/서버 환경 모두 지원)
    document.cookie = cookie_name + '=' + cookie_value + '; path=/';
}

function getCookie(cookie_name) {
    var x, y;
    var val = document.cookie.split(';');
  
    for (var i = 0; i < val.length; i++) {
      x = val[i].substr(0, val[i].indexOf('='));
      y = val[i].substr(val[i].indexOf('=') + 1);
      x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤의 공백 제거하기
      if (x == cookie_name) {
        // return unescape(y); // unescape로 디코딩 후 값 리턴
        return y; // 암호화 끔
      }
    }
  }