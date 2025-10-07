<!-- test2.php -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<button onclick="test()"> Click </button>
<div> </div>

<script>
function test(){
    $.ajax({url:"echo.php", success:function(result){
    $("div").text(result);}
})
} 
</script>