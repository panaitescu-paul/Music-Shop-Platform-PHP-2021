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
    <!-- Policy against XSS Atacks -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; object-src 'self'; child-src 'none';">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/functions.js"></script>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png"/>
</head>
<body>
    <!-- -sm|-md|-lg|-xl -->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Music Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-primary mb-2 btnNav" href="../admin/artists.php">Artists <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary mb-2 btnNav" href="../admin/albums.php">Albums</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary mb-2 btnNav" href="../admin/tracks.php">Tracks</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text">
                        <form id="frmLogout" action="../auth/login.php" method="POST">
                            <input type="hidden" name="logout" value="logout">
                            <button type="submit" id="btnLogOut" class="btn btn-primary mb-2 btnNav" data-toggle='modal' data-target='#modal'>
                                Log Out from: <?php echo $firstName . ' ' . $lastName . ' '; ?> 
                            </button>
                        </form>
                    </span>
                </li>
            </ul>
        </div>
        <!-- <span class="navbar-text">
            <span class="navName">.....</span>
        </span> -->
    </nav>

    <!-- Modal-->
    <div class="modals">
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Information will be added here-->
                    <div class="modal-body">
                        <div id="modalInfoContent1">
                        </div>
                        <!-- TODO: Delete modalInfoContent2 -->
                        <div id="modalInfoContent2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End of Header
