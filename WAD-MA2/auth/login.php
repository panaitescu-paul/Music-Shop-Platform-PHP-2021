<!-- Header -->
<?php
    include('../fragments/header.php');
    // require_once('src/show_data.php');
    // showData('Computer Science');
?>
<div class="container">
    <form id="frmSearchUser" method="POST">
        <fieldset>
            <legend>Login</legend>
            <label for="txtFilm">Email</label>
            <input type="email" id="txtEmail" name="email" required>
            <label for="txtFilm">Password</label>
            <input type="password" id="txtPassword" name="password" required>
            <br>
            <button type="submit">Login</button>
        </fieldset>
    </form>

    <!-- <section id="filmResults">
    </section> -->
    <!-- Loading image -->
    <div id="loading">
        <img src="../img/loading.gif">
    </div>
</div>

    <!-- Footer -->
<?php
    include('../fragments/footer.php')
?>
