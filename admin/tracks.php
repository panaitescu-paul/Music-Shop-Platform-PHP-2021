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

    <!-- Sidebar navigation -->
    <div class="sidebar">
        <button type="button" class="btn btn-primary scrollUp">Up</button>
        <button type="button" class="btn btn-primary scrollDown">Down</button>
    </div>

    <h1 class="main-title">Tracks</h1>
    <div class="resultArea">
        <div class="controls">
            <button type="button" class="btn btn-success mb-2 createTrackModal" 
                    id="btnAdd" data-toggle='modal' data-target='#modal'>Create Tracks</button>
            <label for="txtName" id="txtNameLabel">Track Name</label>
            <input type="text" id="searchTrackName" name="Name" class="form-control" required>
            </br>
            <button type="button" class="btn btn-success mb-2" id="btnSearchTrack">Search Track</button>
        </div>
        <section id="results">
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
