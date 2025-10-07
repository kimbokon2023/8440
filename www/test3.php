<!DOCTYPE html>
<html>    
    <head>        
    <meta charset="UTF-8">        
    <title>Add More Elements</title>        
<script src="//code.jquery.com/jquery.min.js"></script>       
    <script>            
        $(document).ready (function () {                
            $('.btnAdd').click (function () {                                        
                $('.buttons').append (                        
                    '<input type="text" name="txt"> <input type="button" class="btnRemove" value="Remove"><br>'                    
                ); // end append                            
                $('.btnRemove').on('click', function () { 
                    $(this).prev().remove (); // remove the textbox
                    $(this).next ().remove (); // remove the <br>
                    $(this).remove (); // remove the button
                });
            }); // end click                                            
        }); // end ready        
    </script>    
    </head>    
    <body>        
        <div class="buttons">            
        <input type="text" name="txt"> <input type="button" class="btnAdd" value="Add"><br>        
        </div>    
    </body>
</html>