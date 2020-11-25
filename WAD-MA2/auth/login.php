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
        header('Location: ../index.php');
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
            header('Location: ../index.php');
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

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Music Marketplace</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/dom.js"></script>
    <script src="../js/functions.js"></script>
</head>
<body>
<h1>Music Marketplace</h1>
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
    include_once('../footer.php')
?>
