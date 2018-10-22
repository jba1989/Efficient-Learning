<html>
   <head>
      <title>Laravel Ajax示例</title>
      
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>
      
      <script>

      function getMessage(classId){ 
            $.ajax({
                  type:'post',
                  url: '/api/message/show/' + classId,                  
                  data: "_token={{ csrf_token() }}",
                  success:function(data){
                        $('h1').html('success');
                  }                  
            });     
      }
      </script>
   </head>
   
   <body>
      <h1>这条消息将会使用Ajax来替换.
         点击下面的按钮来替换此消息.</h1>
      <table class="table table-striped table-bordered table-hover" id="datatable">
            <tr>
                  <th>AM IN</th>
                  <th>AM OUT</th>
                  <th>PM IN</th>
                  <th>PM OUT</th>
            </tr>
      </table>
         <input type="button" value="替换消息" onClick="getMessage('100S213')">
      
   </body>

</html>