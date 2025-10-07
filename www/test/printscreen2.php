<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<div id="capture" style="padding: 10px; background: #f5da55">
  <h4 style="color: #000; ">Hello world!</h4>
  <br/>
  서버내 이미지 : <img src="../ajax/11.jpg" width="300" height="200">
  서버외 이미지 : <img src="https://images.unsplash.com/photo-1605184020637-0046ba312ef8?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1074&q=80" width="300" height="200">
</div>
<button id="btn">이미지 저장</button>

<script>
  $("#btn").on("click", function() {
  
    html2canvas(document.getElementById('capture')).then(function(canvas) {

      var myImg = canvas.toDataURL("image/png");
      myImg = myImg.replace("data:image/png;base64,", "");

      $.ajax({
        type: "POST",
        data: {
          "imgSrc": myImg
        },
        dataType: "text",
        url: "save_process.php",
        success: function(data) {
          console.log(data);
          if (data == "SUCCESS") {
            alert("저장 성공");
          } else {
            alert("저장 실패");
          }
        },
        error: function(a, b, c) {
          alert("error");
        }
      });
    });

  });
</script>