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
        <table class="table tableList">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Artist Name</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>12 aehgrfawjrfh aweuf34567</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>12 aehgrfawjrfh aweuf34567</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>12 aehgrfawjrfh aweuf34567</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Start of Page content -->

<?php
    include_once('../fragments/footer-admin.php');
?>
