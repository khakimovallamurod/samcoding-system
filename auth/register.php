<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title> 
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="../style.css">
  </head>
<body>
  <div class="bg-img">
    <div class="content">
      <header>Registration</header>
      <form action="add_client.php" method="post" id="regform">

        <!-- Fullname -->
        <div class="field">
          <span class="fa fa-user"></span>
          <input type="text" name="fullname" id="fullname" required>
          <label for="fullname">Enter your fullname</label>
        </div>

        <!-- Username -->
        <div class="field">
          <span class="fa fa-user"></span>
          <input type="text" name="username" id="lgn" required>
          <label for="lgn">Enter your username</label>
        </div>
        <p class="usernamealert"  id="helpblock"></p>

        <!-- Phone -->
        <div class="field">
          <span class="fa fa-phone"></span>
          <input type="tel" name="phone" id="phone" required>
          <label for="phone">Enter your phone</label>
        </div>

        <!-- Email -->
        <div class="field">
          <span class="fa fa-envelope"></span>
          <input type="email" name="email" required>
          <label for="email">Enter your email</label>
        </div>

        <!-- Address -->
        <div class="field">
          <span class="fa fa-home"></span>
          <input type="text" name="address" id="address" required>
          <label for="address">Enter your address</label>
        </div>

        <!-- Select Role -->
        <div class="field select">
          <span class="fa fa-users"></span>
          <select name="role" id="role" required>
            <option value="">-- Select Role --</option>
            <option value="student">Student</option>
            <option value="parent">Parent</option>
          </select>
          <label for="role">Select your role</label>
        </div>

        <!-- Password -->
        <div class="field">
          <span class="fa fa-lock"></span>
          <input type="password" name="password" id="pas1" required>
          <label for="pas1">Create password</label>
        </div>

        <!-- Confirm Password -->
        <div class="field">
          <span class="fa fa-lock"></span>
          <input type="password" name="password2" id="pas2" required>
          <label for="pas2">Confirm password</label>
        </div>
        <p id="mesg" class="error-msg"></p>

        <!-- Submit -->
        <div class="field">
          <input type="submit" value="SIGN UP">
        </div>
      </form>

      <div class="signup">
        Already have account?
        <a href="../index.php">Login Now</a>
      </div>
    </div>
  </div>

  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/sweetalert.min.js"></script>
  <script type="text/javascript">
    // Handle floating labels for inputs
    function handleFloatingLabels() {
      $('input, select').each(function() {
        if ($(this).val()) {
          $(this).closest('.field').addClass('has-value');
        } else {
          $(this).closest('.field').removeClass('has-value');
        }
      });
    }

    // Check on page load
    $(document).ready(function() {
      handleFloatingLabels();
    });

    // Check on input change
    $('input, select').on('input change', function() {
      handleFloatingLabels();
    });

    // Username check
    $('#lgn').on("keyup", function(){
      let l = $(this).val();
      $.ajax({
        url:"check_username.php",
        method:"POST", 
        data:{ username:l },
        success:function(data){
          let obj = jQuery.parseJSON(data);          
          if(obj.error==0){
            $('#helpblock').css('display','block').html(obj.message);
          }else{
            $('#helpblock').css('display','none');
          }
        },
        error:function(){
          alert("There is a problem with your internet connection. Please try again!");
        }
      })
    });

    $('#regform').submit(function(e){
      e.preventDefault();
      let p1 = $('#pas1').val();
      let p2 = $('#pas2').val();
      if(p1 != p2){
        $('#mesg').html('The passwords did not match!').show();
        return false;
      }
      $.ajax({
        url: "add_client.php",
        method: "POST",
        data: $('#regform').serialize(),
        dataType: "json", 
        success: function(obj){   
            if(obj.error == 0){
                swal("Success!", obj.message, "success");
                setTimeout(function(){
                    window.location.href = "../index.php";
                }, 2000);
            } else {
                swal("Error!", obj.message, "error");
            }
        },
        error: function(xhr, status, error){
            console.log(xhr.responseText);
            alert("There is a problem. Please try again!");
        }
    });
    });
  </script>
</body>
</html>