/**
 * API communication
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

$(document).ready(function() {

    // Add a user - Sign Up
    $("#frmAddUser").on("submit", function(e) {
        e.preventDefault();
        loadingStart();

        let info = {
            "first_name": $("#txtFirstName").val(),
            "last_name": $("#txtLastName").val(),
            "email": $("#txtEmail").val(),
            "password": $("#txtPassword").val()
        }

        if (info["first_name"] !== null && info["last_name"] !== null &&
            info["email"] !== null && info["password"] !== null) {
            $.ajax({
                url: "src/api.php",
                type: "POST",
                data: {
                    entity: "user",
                    action: "add",
                    info: info
                },
                success: function(data) {
                    parseInt(JSON.parse(data));
                    if (parseInt(JSON.parse(data)) === -1) {
                        alert("The user with email " + info["email"] + " cannot be added, because the email already exists the Database!");
                    } else {
                        alert("The user with email " + info["email"] + " was successfully created");
                        window.location.href = "http://localhost/php/php_mysql_films_auth/login.php";
                        // or die();
                        exit();
                    }
                    loadingEnd();
                }
            });
        }
    });

    // Search a User - Login
    $("#frmSearchUser").on("submit", function(e) {
        e.preventDefault();
        loadingStart();

        let info = {
            "Email": $("#txtEmail").val(),
            "Password": $("#txtPassword").val()
        }
        console.log("info", info);

        if (info["Email"] !== null && info["Password"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "user",
                    action: "search",
                    info: info
                },
                success: function(data) {
                    // console.log(data);

                    let pData = JSON.parse(data);
                    console.log(pData);

                    if (pData !== false) {
                        alert("Welcome " + pData.FirstName + ' ' + pData.LastName + ' !');
                        window.location.href = "http://localhost/php/WAD-MA2/admin/admin.php";
                        exit();
                    } else {
                        alert("Cound not find the user! Try again!");
                    }
                    loadingEnd();
                }
            });
        }
    });
});
