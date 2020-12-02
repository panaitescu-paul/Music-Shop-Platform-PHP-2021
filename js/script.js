/**
 * API communication
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

$(document).ready(function() {

    // // Add a user - Sign Up
    // $("#frmAddUser").on("submit", function(e) {
    //     e.preventDefault();
    //     loadingStart();

    //     let info = {
    //         "first_name": $("#txtFirstName").val(),
    //         "last_name": $("#txtLastName").val(),
    //         "email": $("#txtEmail").val(),
    //         "password": $("#txtPassword").val()
    //     }

    //     if (info["first_name"] !== null && info["last_name"] !== null &&
    //         info["email"] !== null && info["password"] !== null) {
    //         $.ajax({
    //             url: "src/api.php",
    //             type: "POST",
    //             data: {
    //                 entity: "user",
    //                 action: "add",
    //                 info: info
    //             },
    //             success: function(data) {
    //                 parseInt(JSON.parse(data));
    //                 if (parseInt(JSON.parse(data)) === -1) {
    //                     alert("The user with email " + info["email"] + " cannot be added, because the email already exists the Database!");
    //                 } else {
    //                     alert("The user with email " + info["email"] + " was successfully created");
    //                     window.location.href = "http://localhost/php/php_mysql_films_auth/login.php";
    //                     // or die();
    //                     exit();
    //                 }
    //                 loadingEnd();
    //             }
    //         });
    //     }
    // });

    // Login as User
    $("#frmSearchUser").on("submit", function(e) {
        e.preventDefault();
        loadingStart();

        // Check if the User or Admin option was selected from the Radio buttons
        isUserLogin = document.getElementById('loginUser').parentElement.nodeName.classList.contains('active');
        isAdminLogin = document.getElementById('loginAdmin').parentElement.nodeName.classList.contains('active');
        console.log("isUserLogin ", isUserLogin);
        console.log("isAdminLogin ", isAdminLogin);

        if (isUserLogin) {
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
                            window.location.href = "http://localhost/php/WAD-MA2/user/user.php";
                            exit();
                        } else {
                            alert("Cound not find the User! Try again!");
                        }
                        loadingEnd();
                    }
                });
            }
        } else if (isAdminLogin) {
            let info = {
                "Password": $("#txtPassword").val()
            }
            console.log("info", info);
    
            if (info["Password"] !== null) {
                $.ajax({
                    url: "../src/api.php",
                    type: "POST",
                    data: {
                        entity: "admin",
                        action: "search",
                        info: info
                    },
                    success: function(data) {
                        // console.log(data);
    
                        let pData = JSON.parse(data);
                        console.log(pData);
    
                        if (pData !== false) {
                            alert("Welcome Admin !");
                            window.location.href = "http://localhost/php/WAD-MA2/admin/admin.php";
                            exit();
                        } else {
                            alert("Cound not find the Admin! Try again!");
                        }
                        loadingEnd();
                    }
                });
            }
        }
        
    });

    // Search artist
    $("#btnSearchArtist").on("click", function(e) {
        e.preventDefault();

        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "artist",
                action: "search",
                searchText: $("#searchArtistName").val()
            },
            success: function(data) {
                console.log(data);

                data = JSON.parse(data);
                console.log(data);

                // if (userAuthenticated(data)) {
                    displayArtists(data);
                // }
            }
        });
    });

    // Show Artist
    $(document).on("click", ".showArtist", function() {
        const action = 'show';
        // const action = ($(this)[0].className === "smallButton showMovie" ? 'show' : 'edit');
        const id = $(this).attr("data-id");

        console.log("action", action);
        console.log("id", id);

        // $.ajax({
        //     url: "src/api.php",
        //     type: "POST",
        //     data: {
        //         entity: "movie",
        //         action: "get",
        //         id: id
        //     },
        //     success: function(data) {
        //         const movieInfo = JSON.parse(data);

        //         if (userAuthenticated(movieInfo)) {

        //             $("#txtTitle").val(movieInfo["title"]);
        //             $("#txtOverview").val(movieInfo["overview"]);
        //             $("#txtReleaseDate").val(movieInfo["release_date"]);
        //             $("#txtRuntime").val(movieInfo["runtime"]);

        //             const lstDirectors = $("<select />");
        //             for (const director of movieInfo["directors"]) {
        //                 lstDirectors.append($("<option />", { "value": director["person_id"], "text": director["person_name"] }))
        //             }
        //             $("#lstDirector").html(lstDirectors.html());

        //             const lstActors = $("<select />");
        //             for (const actor of movieInfo["actors"]) {
        //                 lstActors.append($("<option />", { "value": actor["person_id"], "text": actor["person_name"] }))
        //             }
        //             $("#lstActor").html(lstActors.html());

        //             showMovieModal(action, id);
        //         }
        //     }
        // });
    });

    // Update Artist
    $(document).on("click", ".updateArtist", function() {
        const action = 'update';
        // const action = ($(this)[0].className === "smallButton showMovie" ? 'show' : 'edit');
        const id = $(this).attr("data-id");

        console.log("action", action);
        console.log("id", id);

    });

    // Delete Artist
    $(document).on("click", ".deleteArtist", function() {
        const action = 'delete';
        // const action = ($(this)[0].className === "smallButton showMovie" ? 'show' : 'edit');
        const id = $(this).attr("data-id");

        console.log("action", action);
        console.log("id", id);

    });
    

});
