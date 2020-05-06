
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="SignUp.css">
  </head>
  <body>
    <div class="header">
      <h1>Task management application</h1>
    </div>
    <br>
    <br>
    <div class="wrap">
      <div class="modal-content-login">
        <form action="signup_controller.php" method="post">
          <div class="container">
            <h1>Login</h1>
            <hr>
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <div>
              <button type="submit" name="login">Login</button>
            </div>
          </div>
        </form>
      </div>

      <div class="or">
        <h1>or</h1>
      </div>

      <div class="modal-content-register">
        <form action="signup_controller.php" method="post" enctype="multipart/form-data">
          <div class="container">
            <h1>Sign Up</h1>
            <hr>
            <label for="user"><b>Username</b></label>
            <input type="text" placeholder="Enter Name" name="user" required>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <div>
              <button type="submit" name="register">Sign Up</button>
            </div>
          </div>
        </form>
      </div>

    </div>

  </body>

</html>
