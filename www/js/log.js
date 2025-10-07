var ajaxRequest_write = null;

function saveLogData(menuName) {        
    if (ajaxRequest_write !== null) {
        ajaxRequest_write.abort();
    }            
    
    var formData = new FormData();
    formData.append('menu', menuName);

    ajaxRequest_write = $.ajax({
        enctype: 'multipart/form-data', // file을 서버에 전송하려면 이렇게 해야 함 주의
        processData: false,    
        contentType: false,      
        cache: false,           
        timeout: 600000,             
        url: "/insert_logmenu.php",
        type: "post",        
        data: formData,          
        dataType: "json",     
        success: function(data){
            console.log(data);
        },
        error: function(jqxhr, status, error) {
            console.log(jqxhr, status, error);
            alert("An error occurred: " + error); // Display error message
        }                    
    });
}
