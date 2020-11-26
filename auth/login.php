<!-- Header -->
<?php
    include_once('../fragments/header-login.php')
?>

<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

    $userValidation = false;

    // If the user has clicked on 'Logout', the session is destroyed
    if (isset($_POST['logout'])) {
        session_destroy();

    // If the user is already logged in, s/he is redirected to the Admin page, or User page
    } else if (isset($_SESSION['userID'])) {
        if ($_SESSION['userID'] === '0') {
            console.log("redirected to the Admin page");
            header('Location: ../admin/admin.php');
        } else {
            console.log("redirected to the User page");
            header('Location: ../user/user.php');
        }

    // If the user has filled the login fields, the authentication process is launched
    } else if (isset($_POST['email'])) { // TODO: also check if the button was selected for User login

        $userValidation = true;
        require_once('../src/user.php');

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = new User();
        $validUser = $user->validate($email, $password);
        if ($validUser) {
            session_start();

            $_SESSION['userID'] = $user->userID;
            $_SESSION['firstName'] = $user->firstName;
            $_SESSION['lastName'] = $user->lastName;
            $_SESSION['email'] = $email;

            console.log("you are a valid User");
            header('Location: ../user/user.php');
        }
    
    // If the admin has filled the password field, the authentication process is launched
    } else if (isset($_POST['password'])) { // TODO: also check if the button was selected for admin login

        $userValidation = true;
        require_once('../src/admin.php');

        // $email = $_POST['email'];
        $password = $_POST['password'];

        echo("------- ");

        $user = new Admin();
        echo("------- ");
        $validUser = $user->validateAdmin($password);
        echo("------- ");
        if ($validUser) {
            echo("validUser ------- ");

            session_start();

            $_SESSION['userID'] = $user->userID;

            console.log("you are a valid Admin");
            header('Location: ../admin/artists.php');
        }
        echo("------- ");
    }
?>

<?php
    if ($userValidation && !$validUser) {
?>
<div class="errorMessage">
    Invalid email or password.
</div>
<?php
    }
?>

<!-- Login Page start -->

<div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn btn-primary active" id="loginUser" onclick="selectUserLogin()">
    <input type="radio" name="options" autocomplete="off" checked> User
  </label>
  <label class="btn btn-primary" id="loginAdmin" onclick="selectAdminLogin()">
    <input type="radio" name="options" autocomplete="off"> Admin
  </label>
</div>

<form id="frmLogin" action="../auth/login.php" method="POST">
    <fieldset>
    <legend>Login</legend>
        <label for="txtEmail" id="txtEmailLabel">Email</label>
        <input type="email" id="txtEmail" name="email" required>
        <br>
        <label for="txtPassword">Password</label>
        <input type="password" id="txtPassword" name="password" required>
        <br>
        <input type="submit" id="btnLogin" value="Login">
    </fieldset>
</form>
<div id="signup">
    If you do not have a user, you can <a href="signup.php">sign up</a> here!
</div>

<!-- Login Page end -->
<!-- Footer -->
<?php
    include_once('../fragments/footer.php')
?>
