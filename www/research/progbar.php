<style>   
.progress {
    position:relative;
    width:400px;
    border: 1px solid #ddd;
    padding: 1px;
    border-radius: 3px; 
}   
.bar {
    background-color: #B4F5B4; 
    width:0%; 
    height:20px; 
    border-radius: 3px;
}   
.percent { 
    position:absolute; 
    display:inline-block; 
    top:3px; 
    left:48%; 
} 

  @keyframes go-left-right {        /* 애니메이션 이름 지정: "go-left-right" */
    from { left: 0px; }             /* left 0px 부터 애니메이션 시작 */
    to { left: calc(100% - 50px); } /* left 값이 100%-50px 될 때까지 애니메이션 적용 */
  }

  .progress {
    animation: go-left-right 2s infinite alternate;
    /* 해당 요소에 커스텀 애니메이션 'go-left-right' 적용
       지속 시간은 2초,
       반복 횟수는 무한(infinite)
       방향은 매번 바뀜
    */

    position: relative;
    border: 5px solid green;
    width: 100px;
    height: 20px;
    background: lime;
  }
</style>

<script src="http://malsup.github.com/jquery.form.js"></script> 

<script>
// $(function() {  
    // let bar = $('.bar');     
    // let percent = $('.percent');     
    // let status = $('#status');     
    // $('form').ajaxForm({         
        // beforeSend: function() {             
            // status.empty();             
            // let percentVal = '0%';             
            // bar.width(percentVal);             
            // percent.html(percentVal);         
        // },         
        // uploadProgress: function(event, position, total, percentComplete) {             
            // let percentVal = percentComplete + '%';             
            // bar.width(percentVal);             
            // percent.html(percentVal);         
        // },         
        // complete: function(xhr) {            
            // alert('성공');         
        // },        
        // error:function(e){           
            // alert('실패');        
        // }              
    // }); 
// });  

</script>

<form action="testp.php" enctype="multipart/form-data" method="POST">
    <input name="title" value="테스트1"/>      
    <input name="contents" value="테스트 자료입니다."/>  
    <input type="file" name="file"/>     
    <input type="submit" value="upload"> 
</form>   

<div class="progress">     
    <div class="bar"></div>     
    <div class="percent">서버 전송중</div> 
</div> 

<div id="status"></div> 

<div class="progress"></div>

