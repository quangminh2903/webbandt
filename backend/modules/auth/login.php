<?php
  require_once '../../../common/configs/connect.php';
  require '../../../common/configs/constant.php';
  require_once '../../funcs/auth_funcs.php';
  require_once '../../common/validate.php';

  session_start();

  // Check session username
  if (!empty($_SESSION['username'])) {
    header('location: ../dashboard/index.php');
    exit();
  }

  // Check cookie username
  if (!empty($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['success'] = 'Auto login';

    header('Location: ../dashboard/index.php');
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get data form
    $username = !empty($_POST['username']) ? trim($_POST['username']) : '';
    $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
    $remember = !empty($_POST['remember']) ? trim($_POST['remember']) : '';

    // Check info username
    $checkUsername = checkInfo($connection, $username);

    // Validate username
    if (!checkRequire($username)) {
      $errors['username']['required'] = 'Please type username !';
    } else {
      if (empty($checkUsername)) {
        $errors['username']['not_exist'] = 'Not exist Username';
      } else {
        $checActive = $checkUsername->active;

        if ($checActive == '0') {
          $errors['username']['inactive'] = 'Account inactive';
        } elseif ($checActive == '2') {
          $errors['username']['delete'] = 'Account has been locked';
        }
      }
    }

    // Validate password
    if (!checkRequire($password)) {
      $errors['password']['required'] = 'Please type password !';
    }

    if ($username !== '' && $password !== '') {

      // Get info user apply check username and password
      $checkInfo = getUserInfo($connection, $username, $active);

      // Validate check username and password
      if (empty($checkInfo)) {
        $errors['wrong'] = 'Wrong password or username !';
      } else {
        $passInfo = $checkInfo->password;
        $checkPass = password_verify($password, $passInfo);

        // Check password
        if (!$checkPass) {
          $errors['wrong'] = 'Wrong password or username !';

          $_SESSION['error'] = 'Wrong password or username !';
        }
      }
    }

    // Login when not found any errors
    if (empty($errors)) {
      // Create cookie when isset $remember
      if (!empty($remember)) {
        setcookie('username', $username, time() + 86400);
        setcookie('password', $password, time() + 86400);
      }

      $_SESSION['username'] = $username;

      $_SESSION['success'] = 'Login success';

      header('location: ../dashboard/index.php');
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = 'Login';

require '../../common/header_lib.php';
?>

<body>
  <?php require '../../common/navbar.php'; ?>

  <div class="account-container">
    <div class="content clearfix">
      <form action="" method="post">
        <h1>Member Login</h1>

        <div class="login-fields">
          <p>Please provide your details</p>

          <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo !empty($username) ? $username : ''; ?>" placeholder="Username" class="login username-field" />

            <!-- Message error username -->
            <?php require_once '../../lib/error_username.php'; ?>
          </div>

          <!-- /field -->

          <div class="field">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="" placeholder="Password" class="login password-field" />

            <!-- Message error password -->
            <?php require_once '../../lib/error_password.php'; ?>
          </div>
          <!-- /password -->
        </div>
        <!-- /login-fields -->

        <div class="login-actions">
          <span class="login-checkbox">
            <input id="Field" name="remember" type="checkbox" class="field login-checkbox" value="1" tabindex="4" <?php echo !empty($remember) ? 'checked="checked"' : '' ?> />
            <label class="choice" for="Field">Keep me signed in</label>
          </span>

          <button class="button btn btn-success btn-large">Sign In</button>
        </div>
        <!-- .actions -->
      </form>
    </div>
    <!-- /content -->
  </div>
  <!-- /account-container -->

  <div class="login-extra">
    <a href="./forgot_password.php">Reset Password</a>
  </div>
  <!-- /login-extra -->

  <?php require_once '../../common/footer_lib.php'; ?>
</body>

</html>