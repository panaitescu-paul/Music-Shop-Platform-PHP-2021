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
    echo $userID . ' -  ';
    echo $firstName . ' -  ';
    echo $lastName . ' -  ';
    echo $email . ' -  ';
?>

<!-- Start of Page content -->
<div class="container">

    <h1 class="main-title">Library Albums</h1>
    <div class="resultArea">
        <div class="buttons">
            <button type="button" class="btn btn-primary mb-2 btnLibrary" 
                onclick="window.location.href='/WAD-MA2/user/library-artists.php'">Artists
            </button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary active" 
                onclick="window.location.href='/WAD-MA2/user/library-albums.php'">Albums
            </button>
            <button type="button" class="btn btn-primary mb-2 btnLibrary" 
                onclick="window.location.href='/WAD-MA2/user/library-tracks.php'">Tracks
            </button>
        </div>
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
                    <td>John Smith</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>Paul Panaitescu</td>
                </tr>
                <tr>
                    <th>1</th>
                    <td>John Smith</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- End of Page content -->

<?php
    include_once('../fragments/footer.php');
?>
