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
    $userID = $_SESSION['userID'];
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $email = $_SESSION['email'];
?>

<!-- Start of Page content -->
<div class="container">
    <h1 class="main-title">Library Tracks</h1>
    <div class="resultArea">
        <div class="buttons">
            <button type="button" class="btn btn-primary mb-2 btnLibrary">Artists</button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary">Albums</button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary">Tracks</button>
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
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
