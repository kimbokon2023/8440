
function setCookie (cookie_name, value, minutes) {
    const exdate = new Date();
    exdate.setMinutes(exdate.getMinutes() + minutes);
    // const cookie_value = escape(value) + ((minutes == null) ? '' : '; expires=' + exdate.toUTCString());
    const cookie_value = value + ((minutes == null) ? '' : '; expires=' + exdate.toUTCString()); // 암호화 끔
    document.cookie = cookie_name + '=' + cookie_value;
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