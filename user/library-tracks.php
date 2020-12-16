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

    if (!isset($_SESSION['ShoppingCart'])) {
        $_SESSION['ShoppingCart'] = array();
    }
    // alert("Track alreay in the Shopping Cart! Cannot add duplicates!");

    if (isset($_POST['addToCart'])) {
        // Check if the Track id is already saved in the Shopping Cart. Cannot add a track multiple times
        $newTrackId = $_POST['addToCart2'];
        $tracksList = $_SESSION['ShoppingCart'];
        if (!in_array($newTrackId, $tracksList)) {
            $shoppingCart = $_SESSION['ShoppingCart'];
            array_push($shoppingCart, $_POST['addToCart2']);
            $_SESSION['ShoppingCart'] = $shoppingCart;
        } 
    }
?>

<!-- Start of Page content -->
<div class="container" id="page-tracks">

    <?php
        print_r($_SESSION['ShoppingCart']);
    ?>

<!-- Start of Page content -->
<div class="container">
    <h1 class="main-title">Library Tracks</h1>
    <div class="resultArea">
        <div class="buttons">
            <button type="button" class="btn btn-primary mb-2 btnLibrary" 
                onclick="window.location.href='/WAD-MA2/user/library-artists.php'">Artists
            </button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary" 
                onclick="window.location.href='/WAD-MA2/user/library-albums.php'">Albums
            </button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary active" 
                onclick="window.location.href='/WAD-MA2/user/library-tracks.php'">Tracks
            </button>
        </div>
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
                        <button type="button" class="btn btn-success btnAddCart">Add to Cart</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                    <td>
                        <button type="button" class="btn btn-success btnAddCart">Add to Cart</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                    <td>
                        <button type="button" class="btn btn-success btnAddCart">Add to Cart</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                    <td>
                        <button type="button" class="btn btn-success btnAddCart">Add to Cart</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                    <td>
                        <button type="button" class="btn btn-success btnAddCart">Add to Cart</button>
                    </td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                    <td>
                        <button type="button" class="btn btn-success btnAddCart">Add to Cart</button>
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
