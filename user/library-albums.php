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

    <h1 class="main-title">Library Albums</h1>
    <div class="resultArea">
        
        <div class="controls">
            <div class="buttons">
                <button type="button" id="btnArtistsTab" class="btn btn-primary mb-2 btnLibrary">Artists</button>
                <button type="button" id="btnAlbumsTab" class="btn btn-primary mb-2 btnLibrary active">Albums</button>
                <button type="button" id="btnTracksTab" class="btn btn-primary mb-2 btnLibrary">Tracks</button>
            </div>
            <label for="txtName" id="txtNameLabel">Album Name</label>
            <input type="text" id="searchAlbumName" name="Name" class="form-control" required>
            </br>
            <button type="button" class="btn btn-success mb-2" id="btnSearchAlbum">Search Album</button>
        </div>
        
        <section id="results">
        </section>
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
