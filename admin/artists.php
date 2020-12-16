<?php
    include_once('../fragments/header-admin.php');
?>

<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

    if (!isset($_SESSION['userID'])) {
        header('Location: ../auth/login.php');
    }
    // if you are loged in as Customer, then you are redirected to a Customer page
    if (isset($_SESSION['userID']) && $_SESSION['userID'] !== 0) {
        header('Location: ../user/library-tracks.php');
    }
    
    $userID = $_SESSION['userID'];
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $email = $_SESSION['email'];
?>

<!-- Start of Page content -->
<div class="container" id="page-artists">

<?php echo $userID . ' -  \n';
    echo $firstName . ' -  \n';
    echo $lastName . ' -  \n';
    echo $email . ' -  \n';
    ?>
    <form id="frmLogout" action="../auth/login.php" method="POST">
        <input type="hidden" name="logout" value="logout">
        <input type="submit" id="btnLogOut" value="Log Out">&nbsp;
        <input type="button" id="btnUserCancel" value="Cancel">
    </form>

    <!-- Sidebar navigation -->
    <div class="sidebar">
        <button type="button" class="btn btn-primary scrollUp">Up</button>
        <button type="button" class="btn btn-primary scrollDown">Down</button>
    </div>

    <h1 class="main-title">Artists</h1>
    <div class="resultArea">
        <button type="button" class="btn btn-success mb-2 createArtistModal" 
                id="btnAdd" data-toggle='modal' data-target='#modal'>Create Artist</button>
        <!-- <button type="button" class="btn btn-success mb-2" id="btnShowArtists">Show All Artists</button> -->
        <label for="txtName" id="txtNameLabel">Artist Name</label>
        <input type="text" id="searchArtistName" name="Name" required>
        <button type="button" class="btn btn-success mb-2" id="btnSearchArtist">Search Artist</button>
        <section id="artistResults">
        </section>

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
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
