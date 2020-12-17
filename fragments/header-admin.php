<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);
    
    $userID = $_SESSION['userID'];
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Music Shop</title>
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
                <a class="nav-link" href="/WAD-MA2/admin/artists.php">Artists <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/WAD-MA2/admin/albums.php">Albums</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/WAD-MA2/admin/tracks.php">Tracks</a>
            </li>
        </div>
        <span class="navbar-text">
            <?php
                echo $firstName . ' ' . $lastName . ' ';
            ?>
        </span>
        <span class="navbar-text">
            <form id="frmLogout" action="../auth/login.php" method="POST">
                <input type="hidden" name="logout" value="logout">
                <input type="submit" id="btnLogOut" value="Log Out">&nbsp;
             </form>
        </span>
    </nav>
    <!-- End of Header
