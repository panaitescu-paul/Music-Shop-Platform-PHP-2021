<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

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
    <script src="../js/functions.js"></script>
    <!-- Policy against XSS Atacks -->
    <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'self'; object-src 'self';"> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; object-src 'self'; child-src 'none';"> -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'nonce-EDNnf03nceIOfn39fn3e9h3sdfa'"> -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; 
                                                        script-src 'nonce-EDNnf03nceIOfn39fn3e9h3sdf1' 'nonce-EDNnf03nceIOfn39fn3e9h3sdf2'; 
                                                        object-src 'self'; 
                                                        child-src 'none';">
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
                <a class="btn btn-primary btnNav" href="../user/library-tracks.php">Library <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary btnNav" href="../user/shopping-cart.php">Cart</a>
            </li>
                <button class="btn btn-primary mb-2 createCustomerModal btnNav" data-toggle='modal' data-target='#modal'>Sign Up</button>
                <button class="btn btn-primary mb-2 updateCustomerModal btnNav" data-toggle='modal' data-target='#modal'>Profile</button>
            </li>
        </div>
        <span class="navbar-text">
            <span class="navName"><?php echo $firstName . ' ' . $lastName . ' '; ?></span>
        </span>
        <span class="navbar-text">
            <form id="frmLogout" action="../auth/login.php" method="POST">
                <input type="hidden" name="logout" value="logout">
                <button type="submit" id="btnLogOut" class="btn btn-primary mb-2 btnNav" data-toggle='modal' data-target='#modal'>Log Out</button>
             </form>
        </span>
    </nav>

    <!-- End of Header
