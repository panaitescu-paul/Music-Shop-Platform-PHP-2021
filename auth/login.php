<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

    $userValidation = false;

    // If the user has clicked on 'Logout', the session is destroyed
    if (isset($_POST['logout'])) {
        session_destroy();

    // If the user is already logged in, s/he is redirected to the search page
    } else if (isset($_SESSION['userID'])) {
        console.log("redirected to the search page");
        header('Location: ../admin/admin.php');
        console.log("redirected to the search page");


    // If the user has filled the login fields, the authentication process is launched
    } else if (isset($_POST['email'])) {

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

            console.log("you are a valid user");
            header('Location: ../admin/admin.php');
        }
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

<!-- Header -->
<?php
    include_once('../fragments/header-admin.php')
?>
<header>
    <h1>Films</h1>
</header>
<form id="frmLogin" action="../auth/login.php" method="POST">
    <fieldset>
    <legend>Login</legend>
        <label for="txtEmail">Email</label>
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


<!-- Footer -->
<?php
    include_once('../fragments/footer-admin.php')
?>
