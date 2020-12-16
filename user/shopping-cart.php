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
<div class="container" id="page-tracks">

    <?php
        print_r($_SESSION['ShoppingCart']);
    ?>
    <script type="text/javascript">
        var shoppingCart = <?php echo json_encode($_SESSION['ShoppingCart']); ?>;
        console.log(shoppingCart);
    </script>

    <!-- Sidebar navigation -->
    <div class="sidebar">
        <button type="button" class="btn btn-primary scrollUp">Up</button>
        <button type="button" class="btn btn-primary scrollDown">Down</button>
    </div>

    <h1 class="main-title">Shopping Cart</h1>
    <div class="resultArea">

        <!-- <div class="buttons">
            <button type="button" class="btn btn-primary mb-2 btnLibrary" 
                onclick="window.location.href='/WAD-MA2/user/library-artists.php'">Artists
            </button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary" 
                onclick="window.location.href='/WAD-MA2/user/library-albums.php'">Albums
            </button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary active" 
                onclick="window.location.href='/WAD-MA2/user/library-tracks.php'">Tracks
            </button>
        </div> -->

        <!-- <button type="button" class="btn btn-success mb-2 createTrackModal"  -->
                <!-- id="btnAdd" data-toggle='modal' data-target='#modal'>Create Tracks</button> -->
        <!-- <button type="button" class="btn btn-success mb-2" id="btnShowArtists">Show All Artists</button> -->
        <!-- <label for="txtName" id="txtNameLabel">Track Name</label>
        <input type="text" id="searchTrackName" name="Name" required>
        </br>
        <button type="button" class="btn btn-success mb-2" id="btnSearchTrack">Search Track</button> -->
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


<!-- Start of Page content -->
<!-- <div class="container">
    <h1 class="main-title">Shopping Cart</h1>
    <div class="resultArea">
        <table class="table tableList">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Artist Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>1</th>
                    <td>Track 1: Eminem - Rap God [Official]</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Remove</button>
                    </td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>Track 2: 50 Cent - In Da Club [Official]</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Remove</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Track 1: Eminem - Rap God [Official]</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Remove</button>
                    </td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>Track 2: 50 Cent - In Da Club [Official]</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Remove</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Track 1: Eminem - Rap God [Official]</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Remove</button>
                    </td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>Track 2: 50 Cent - In Da Club [Official]</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div> -->

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
