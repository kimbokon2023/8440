<?php
// test1.php
function php_func(){
echo "Hello, my best friend <br>";
print "Hello, my best friend";
}
?>

<button onclick="clickMe()"> Click Me </button>

<script>
function clickMe(){
var result ="<?php php_func(); ?>"
document.write(result);
}
</script>