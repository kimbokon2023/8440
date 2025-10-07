<!DOCTYPE html>
<html>
 <head>
  <title> Make Treeview using Bootstrap Treeview Ajax JQuery with PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>    
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" />  
  
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>  
  
  <!--  <script src="../../woosung/tree-list-bootstrap/dist/js/bstreeview.min.js"> </script> -->
  
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js"></script>   

 </head>
 <body>
  <br /><br />
  <div class="container" style="width:900px;">
   <h2 align="center">Make Treeview using Bootstrap Treeview Ajax JQuery with PHP</h2>
   <br /><br />
   <div id="tree"></div>
   
   <input type="button" value="Get Checked" id="btnGet" />
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
	$.ajax({ 
	url: "fetch.php",
	method:"POST",
	dataType: "json",       
	success: function(data)  
	{
		// $('#tree').treeview(
		 // {data: data}		 
		 // );
		 
		// Example: initializing the treeview
		// expanded to 5 levels
		// with a background color of green
		$('#tree').treeview({
		  data: data         // data is not optional
		 // levels: 5,   // levels에 숫자를 주면 펼쳐진 모습이 나온다.
		  // backColor: 'green'
		});		

		// $('#tree').treeview('checkAll', { silent: true });		
		// $('#tree').treeview('checkNode', [ nodeId, { silent: true } ]);
		 
		$('#tree').on('nodeSelected', function(event, data) {
			 // var nodeID = $('#tree').treeview('getSelected') ;
			 console.log(data.id);
			// Your logic goes here
		});
		
		// $('#treeview').treeview({
		  // // The naming convention for callback's is to prepend with `on`
		  // // and capitalize the first letter of the event name
		  // // e.g. nodeSelected -> onNodeSelected
		  // // onNodeSelected:  alert('dddd'); 
			  
			  
		   
			// // Your logic goes here
		  // });		
		
		
	}   
	});      
	
	$('#btnGet').on('click', function () {
		// $('#treeview').treeview('checkAll', { silent: true });
		var checked = $('#treeview').treeview('getChecked');
		var message = "Checked nodes are :\n";
		for (var i = 0; i <checked.length; i++) {
			message += checked[i].text + "\n";
		}
		alert(message);
	});
 
});
</script>