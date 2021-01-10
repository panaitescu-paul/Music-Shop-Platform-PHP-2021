<!-- Header -->
<?php
    include_once('../fragments/header-login.php')
?>

<?php
    require_once('../src/functions.php');
    
    session_start();
    debug($_SESSION);
    
    $userValidation = false; 
    
    // If the Login Token is not set, then create one and set it as a Session variable
    if (!isset($_SESSION['loginCSRFToken'])) {
        $randomToken = base64_encode(openssl_random_pseudo_bytes(32));
        $_SESSION['loginCSRFToken'] = $randomToken;
    }

    // If the user has clicked on 'Logout', the session is destroyed
    if (isset($_POST['logout'])) {
        session_destroy();

    // If the user is already logged in, s/he is redirected to the Admin page, or User page
    } else if (isset($_SESSION['userID'])) {
        if ($_SESSION['userID'] === '0') {
            header('Location: ../admin/artists.php');
        } else {
            header('Location: ../user/library-tracks.php');
        }

    // If the Customer has filled the login fields, the authentication process is launched
    } else if (isset($_POST['email']) && isset($_POST['password'])) { 

        // Check if the submitted form has the Login Token that matches the one from the Session variable
        if (isset($_POST['CSRFToken']) && $_POST['CSRFToken'] == $_SESSION['loginCSRFToken']) {
            $userValidation = true;
            require_once('../src/customer.php');

            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new Customer();
            $validUser = $user->login($email, $password);
            if ($validUser) {
                session_start();

                $_SESSION['userID'] = $user->userID;
                $_SESSION['firstName'] = $user->firstName;
                $_SESSION['lastName'] = $user->lastName;
                $_SESSION['email'] = $email;

                header('Location: ../user/library-tracks.php');
            }
        }
    
    // If the Admin has filled the password field, the authentication process is launched
    } else if (isset($_POST['password'])) { 

        // Check if the submitted form has the Login Token that matches the one from the Session variable
        if (isset($_POST['CSRFToken']) && $_POST['CSRFToken'] == $_SESSION['loginCSRFToken']) {
            $userValidation = true;
            require_once('../src/customer.php');

            $password = $_POST['password'];

            $user = new Customer();
            $validUser = $user->login('', $password, 1);

            if ($validUser) {
                session_start();

                $_SESSION['userID'] = $user->userID;
                $_SESSION['firstName'] = $user->firstName;
                $_SESSION['lastName'] = $user->lastName;
                $_SESSION['email'] = $email;

                header('Location: ../admin/artists.php');
            }
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

<!-- Start of Page content -->
<div class="container" id="page-login">

    <h1 class="main-title">Login</h1>
    <div class="resultArea" id="loginArea">
        <div class="btn-group btn-group-toggle buttons buttonsLogin" data-toggle="buttons">
            <button class="btn btn-primary active btnLibrary" id="loginUser" onclick="selectUserLogin()">
                <input type="radio" name="options" autocomplete="off" checked> User
            </button>
            <button class="btn btn-primary" id="loginAdmin" onclick="selectAdminLogin()">
                <input type="radio" name="options" autocomplete="off"> Admin
            </button>
        </div>

        <form id="frmLogin" action="../auth/login.php" method="POST">
            <div class="form-group row">
                <label for="txtEmail" class="col-sm-2 col-form-label" id="txtEmailLabel">Email</label>
                <div class="col-sm-10">
                    <!-- Email example to match pattern: email@gmail.com -->
                    <input type="email" id="txtEmail" name="email" class="form-control" placeholder="Email" title="Email should match the pattern: email@gmail.com !" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="txtPassword" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <!-- Password can only have letters and numbers -->
                    <input type="password" id="txtPassword" name="password"  class="form-control" placeholder="Password" pattern="^[a-zA-Z0-9]*$" title="Password can only have letters and numbers!">
                </div>
            </div>
            
            <button type="submit" class="btn btn-success" id="btnLogin">Login</button>
        </form>
        <div id="signup">
            If you do not have a user, you can <a href="signup.php">sign up</a> here!
        </div>
    </div>
</div>

<!-- Footer -->
<?php
    include_once('../fragments/footer.php')
?>
