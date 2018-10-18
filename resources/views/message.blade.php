<html>
   <head>
      <title>Laravel Ajax示例</title>
      
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>
      
      <script>
         function getMessage(){
            $.ajax({
               type:'get',
               url:'/getmsg',
               data:'_token = <?php echo csrf_token() ?>',
               success:function(data){
                  $("#msg").html(data.msg);
               }
            });
         }
      </script>
   </head>
   
   <body>
      <div id = 'msg'>这条消息将会使用Ajax来替换.
         点击下面的按钮来替换此消息.</div>
      
         <input type="button" value="替换消息" onClick="getMessage()">
      
   </body>

</html>