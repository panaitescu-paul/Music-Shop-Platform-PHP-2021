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
<div class="container">
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
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
