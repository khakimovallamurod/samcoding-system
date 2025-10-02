<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title> 
    <link rel="stylesheet" href="../css/register_style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  </head>
<body>
  <div class="bg-img">
    <div class="content">
      <header>Login Form</header>
      <form action="login-check.php" method="post" id="logform">
        <div class="field">
          <span class="fa fa-envelope"></span>
          <input type="text" name="username" required>
          <label for="lgn">Enter your username</label>
        </div>
        
        <div class="field space">
          <span class="fa fa-lock"></span>
          <input type="password" name="password" id="pass" class="pass-key"required>
          <label for="pas1">Enter your password</label>
        </div>
        
        <div class="field">
          <input type="submit" value="LOGIN">
        </div>

      </form>
      <div class="signup">
        Don't have account?
        <a href="register.php">Signup Now</a>
      </div>
    </div>
  </div>
  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    $('#logform').submit(function(e){
      e.preventDefault();
      $.ajax({
        url:"login-check.php",
        method:"POST",
        data:$('#logform').serialize(),
        success:function(data){
          let obj = jQuery.parseJSON(data);
          const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
          if(obj.error == 0){
            Toast.fire({
                icon: 'success',
                title: obj.message
            });
            if (obj.rederict == 'admin'){
              setTimeout(function(){
                window.location.href = '../admin/index.php';
              }, 2000);
            }else{
              setTimeout(function(){
                window.location.href = '../index.php';
              }, 2000);
            }
            
          } else {
            $('#pass').val('');
            Toast.fire({
                icon: 'error', 
                title: obj.message
            });
          }
        },
        error:function(){
          alert("There is a problem with your internet connection. Please try again!");
        }
      });
    })
  </script>
</body>
</html>
