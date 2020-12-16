<?php
    include_once('../fragments/header-user.php');
?>

<?php
    require_once('../src/functions.php');

    // session_start();
    // debug($_SESSION);

    // if (!isset($_SESSION['userID'])) {
    //     header('Location: ../auth/login.php');
    // }
    // $userID = $_SESSION['userID'];
    // $firstName = $_SESSION['firstName'];
    // $lastName = $_SESSION['lastName'];
    // $email = $_SESSION['email'];
    // echo $userID . ' -  ';
    // echo $firstName . ' -  ';
    // echo $lastName . ' -  ';
    // echo $email . ' -  ';
?>

<!-- Start of Page content -->
<div class="container">
    <h1 class="main-title">Sign Up</h1>
    <div class="resultArea">
        
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
