
<?php
  session_start(); 
  
  $tmp=$_SESSION["name"];
  print "<h2> " . $tmp . "님! 변경사항이 저장되었습니다. </h2>";
//sleep(2);
//print "<script> sleep(1000); </script>";

?>
<script>
setTimeout(function() {
  self.close();
}, 1500);

</script>