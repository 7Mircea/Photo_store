<?php require_once 'controller/authController.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-4 offset-md-4 form-div">
          <form action="signup.php" method="post">
            <h3 class="text-center">Register</h3>

            <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger">
                  <?php foreach ($errors as $error): ?>
                    <li> <?php echo $error; ?> </li>
                  <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" name="username" value="<?php echo $username; ?>" class="form-control form-control-lg">
            </div>
            <div class="form_group">
              <label for="email">Email</label>
              <input type="email" name="email" value="<?php echo $email; ?>" class="form-control form-control-lg">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" value="<?php echo $password; ?>" class="form-control form-control-lg">
            </div>
            <div class="form-group">
              <label for="passwordConf">Confirm password</label>
              <input type="password" name="passwordConf" class="form-control form-control-lg">
            </div>
            <div class="form-group">
              <button type="submit" name="signup-btn" class="btn btn-primary btn-block btn-lg">Sign-up</button>
            </div>
            <p class="text-center">Already a member <a href="login.php">Sign in</a></p>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
