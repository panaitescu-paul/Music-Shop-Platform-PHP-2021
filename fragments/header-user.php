<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

    // if (!isset($_SESSION['userID'])) {
    //     header('Location: ../auth/login.php');
    // }
    // // if you are loged in as Customer, then you are redirected to a Customer page
    // if (isset($_SESSION['userID']) && $_SESSION['userID'] !== 0) {
    //     header('Location: ../user/library-tracks.php');
    // }
    
    $userID = $_SESSION['userID'];
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $email = $_SESSION['email'];
?>

<script type="text/javascript">
    // Send Session data to JS 
    var userId = <?php echo json_encode($_SESSION['userID']); ?>
</script>

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
    <!-- -sm|-md|-lg|-xl -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Music Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="/WAD-MA2/user/library-tracks.php">Library <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/WAD-MA2/user/shopping-cart.php">Cart</a>
            </li>
            <li class="nav-item">
                <!-- <a class="nav-link" href="/WAD-MA2/user/sign-up.php">Sign Up</a> -->
                <button class="btn btn-success mb-2 createCustomerModal" data-toggle='modal' data-target='#modal'>Sign Up</button>
            </li>
            <li class="nav-item">
                <!-- <a class="nav-link" class="btn btn-success mb-2 updateCustomerModal" data-toggle='modal' data-target='#modal'>Profile</a> -->
                <button class="btn btn-success mb-2 updateCustomerModal" data-toggle='modal' data-target='#modal'>Profile</button>

            </li>
        </div>
        <span class="navbar-text">
            <?php echo $firstName . ' ' . $lastName . ' '; ?>
        </span>
        
        <span class="navbar-text">
            <form id="frmLogout" action="../auth/login.php" method="POST">
                <input type="hidden" name="logout" value="logout">
                <input type="submit" id="btnLogOut" value="Log Out">&nbsp;
             </form>
        </span>
    </nav>

    <!-- End of Header
