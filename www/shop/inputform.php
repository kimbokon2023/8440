<!DOCTYPE html>
    <head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 
</head>
<body >
     
<div class="container">
             
<div class="row">
    <div class="col-md-12">
         
        <div class="page-header">
            <h1>컨텐츠 표시</h1>
            <h2 >form</h2>
        </div>
         
        <form>
            <div class="form-group">
            <label for="txt1">Text:</label>
                <input type="text" class="form-control" id="txt1">
            </div>
            <div class="form-group">
                <label for="pw1">Password:</label>
                <input type="password" class="form-control" id="pw1">
            </div>
            <div class="form-group">
                <label for="ta1">Text Area:</label>
                <textarea class="form-control" id="ta1" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="sl1">Password:</label>
                <select class="form-control" id="sl1">
                    <option>One</option>
                    <option>Two</option>
                    <option>Three</option>
                </select>
            </div>
            <div class="form-group">
                <input type="button" class="btn" value="Click">
            </div>
        </form>
    </div>
</div>
     
</div>
 
 
<div class="container">  
 <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>	
 
 
</body>
</html>