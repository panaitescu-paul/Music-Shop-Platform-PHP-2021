<?php
    require_once('src/functions.php');

    session_start();
    debug($_SESSION);

    if (!isset($_SESSION['userID'])) {
        header('Location: auth/login.php');
    }
    $userID = $_SESSION['userID'];
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $email = $_SESSION['email'];
?>

<!-- Start of Page content -->

<p>Index page!!! </p>

<!-- Start of Page content -->

<?php
    include_once('footer-admin.php');
?>
