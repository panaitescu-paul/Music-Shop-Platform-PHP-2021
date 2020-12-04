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
    $userID = $_SESSION['userID'];
    // $firstName = $_SESSION['firstName'];
    // $lastName = $_SESSION['lastName'];
    // $email = $_SESSION['email'];
?>

<!-- Start of Page content -->
<div class="container">
    <h1 class="main-title">Artists</h1>
    <div class="resultArea">
    
        <button type="button" class="btn btn-success mb-2 createArtistModal" 
                id="btnAdd" data-toggle='modal' data-target='#modal'>Create Artist</button>
        <button type="button" class="btn btn-success mb-2" id="btnShowArtists">Show All Artists</button>
        <label for="txtName" id="txtNameLabel">Artist Name</label>
        <input type="text" id="searchArtistName" name="Name" required>
        <button type="button" class="btn btn-success mb-2" id="btnSearchArtist">Search Artist</button>
        
        <section id="artistResults">
        </section>

        <!-- Modal-->
        <div class="modals">
            <!-- <div id="movieInfo"> -->
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
                                <div id="modalInfoContent2">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>

        <!-- <table class="table tableList">
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
                    <td>Paul Panaitescu</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">
                            <img src="../img/pencil-square.svg" class="icon-delete">
                        </button>
                        <button type="button" class="btn btn-warning btnUpdate">
                            <img src="../img/trash.svg" class="icon-update">
                        </button>
                        <button type="button" class="btn btn-success btnShow">
                            <img src="../img/card-text.svg" class="icon-show" >
                        </button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Delete</button>
                        <button type="button" class="btn btn-warning btnUpdate">Update</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Delete</button>
                        <button type="button" class="btn btn-warning btnUpdate">Update</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Delete</button>
                        <button type="button" class="btn btn-warning btnUpdate">Update</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Delete</button>
                        <button type="button" class="btn btn-warning btnUpdate">Update</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                    <td>
                        <button type="button" class="btn btn-danger btnDelete">Delete</button>
                        <button type="button" class="btn btn-warning btnUpdate">Update</button>
                    </td>
                </tr>
            </tbody>
        </table> -->
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
