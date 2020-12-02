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
    
        <button type="button" class="btn btn-success mb-2" id="btnAdd">Add Artist</button>
        <button type="button" class="btn btn-success mb-2" id="btnSearchArtist">Get Artist</button>
        
        <label for="txtName" id="txtNameLabel">Artist Name</label>
        <input type="text" id="searchArtistName" name="Name" required>
        
        <section id="artistResults">
        </section>

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

                        <!-- <img src="../img/alarm-fill.svg" alt="" width="32" height="32" title="Bootstrap">
                        <img src="../img/card-list.svg" alt="" width="32" height="32" title="Bootstrap">
                        <img src="../img/cart.svg" alt="" width="32" height="32" title="Bootstrap">
                        <img src="../img/cart2.svg" alt="" width="32" height="32" title="Bootstrap">
                        <img src="../img/receipt.svg" alt="" width="32" height="32" title="Bootstrap">
                        <img src="../img/receipt-cutoff.svg" alt="" width="32" height="32" title="Bootstrap"> -->
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
        </table>
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
