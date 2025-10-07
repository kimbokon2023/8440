  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    
<script>
// Json을 이용한 음성파일 불러오기 구글사용

/*
$.get("URL", {
name: value
}).done(function(data){
console.log(data);
}).fail(function(data){
console.log("Fail to load\nError code: "+ data);
}); */
$.get("https://texttospeech.googleapis.com/v1beta1/text:synthesize", {
  "audioConfig": {
    "audioEncoding": "LINEAR16",
    "pitch": 0,
    "speakingRate": 1
  },
  "input": {
    "text": "Google Cloud Text-to-Speech enables developers to synthesize natural-sounding speech with 100+ voices, available in multiple languages and variants. It applies DeepMind’s groundbreaking research in WaveNet and Google’s powerful neural networks to deliver the highest fidelity possible. As an easy-to-use API, you can create lifelike interactions with your users, across many applications and devices."
  },
  "voice": {
    "languageCode": "ko-KR",
    "name": "ko-KR-Wavenet-D"
  }


}).done(function(data){
console.log(data);
}).fail(function(data){
console.log("Fail to load\nError code: "+ data);
}); 



</script>