<?php
    include_once('../fragments/header-user.php');
?>

<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

    if (!isset($_SESSION['userID'])) {
        header('Location: ../auth/login.php');
    }
    // if you are loged in as Admin, then you are redirected to an Admin page
    if (isset($_SESSION['userID']) && $_SESSION['userID'] == 0) {
        header('Location: ../admin/artists.php');
    }
    $userID = $_SESSION['userID'];
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $email = $_SESSION['email'];
?>


<!-- Start of Page content -->
<div class="container" id="page-artists">

    <!-- Sidebar navigation -->
    <div class="sidebar">
        <button type="button" class="btn btn-primary scrollUp">Up</button>
        <button type="button" class="btn btn-primary scrollDown">Down</button>
    </div>

    <h1 class="main-title">Library Artists</h1>
    <div class="resultArea">
       
        <div class="controls">
            <div class="buttons">
                <button type="button" id="btnArtistsTab" class="btn btn-primary mb-2 btnLibrary active">Artists</button>
                <button type="button" id="btnAlbumsTab" class="btn btn-primary mb-2 btnLibrary">Albums</button>
                <button type="button" id="btnTracksTab" class="btn btn-primary mb-2 btnLibrary">Tracks</button>
            </div>
            <label for="txtName" id="txtNameLabel">Artist Name</label>
            <input type="text" id="searchArtistName" name="Name" class="form-control" required>
            </br>
            <button type="button" class="btn btn-success mb-2" id="btnSearchArtist">Search Artist</button>
        </div>
        
        <section id="artistResults">
        </section>
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
