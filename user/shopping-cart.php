<?php
    include_once('../fragments/header-user.php');
?>

<?php
    require_once('../src/functions.php');

    session_start();
    debug($_SESSION);

    // If the purchase was successful, then refresh the Shopping Cart (delete Tracks from it)
    if (isset($_POST['reset'])) {
        $_SESSION['ShoppingCart'] = [];
    }

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

    // If you removed a Track from the Shopping Cart
    if (isset($_POST['removeFromCart']) && isset($_POST['trackId'])) {
        $shoppingCart = $_SESSION['ShoppingCart'];
        $shoppingCart = array_diff($shoppingCart, array($_POST['trackId']));
        $_SESSION['ShoppingCart'] = $shoppingCart;
    }
?>

<!-- Start of Page content -->
<div class="container" id="page-tracks">

    <?php
        // print_r($_SESSION['ShoppingCart']);
    ?>
    <script type="text/javascript" nonce="EDNnf03nceIOfn39fn3e9h3sdf2">
        console.log("nonce-EDNnf03nceIOfn39fn3e9h3sdf2");
        // Send Session data to JS 
        var shoppingCartInfo = {
            tracks: <?php echo json_encode($_SESSION['ShoppingCart']); ?>,
            userID: <?php echo json_encode($_SESSION['userID']); ?>,
            firstName: <?php echo json_encode($_SESSION['firstName']); ?>,
            lastName: <?php echo json_encode($_SESSION['lastName']); ?>,
            email: <?php echo json_encode($_SESSION['email']); ?>
        }
    </script>

    <!-- <script>
        // Test Content-Security-Policy with a XSS Atack
        alert("Hello!");
    </script> -->

    <!-- Sidebar navigation -->
    <div class="sidebar">
        <button type="button" class="btn btn-primary scrollUp">Up</button>
        <button type="button" class="btn btn-primary scrollDown">Down</button>
    </div>

    <h1 class="main-title">Shopping Cart</h1>
    <div class="resultArea">

        <div class="controls">
            <p>
                <span>Total Price: </span>
                <span id="purchaseTotalPrice"></span>
            </p>
            <button type="button" class="btn btn-success mb-2 purchaseModal" id="btnPurchase" data-toggle='modal' data-target='#modal'>Purchase Tracks</button>
            <!-- Hidden form to reset the Session varaible (delete Tracks from Shopping Cart) -->
            <form hidden id="frmCreateArtist" action="../user/shopping-cart.php" method="POST">
                <input type='hidden' name='reset' value='reset'>
                <button type="submit" id="resetPurchaseCart">Confirm Purchase</button>
            </form>
        </div>
       
        <section id="results">
        </section>
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
