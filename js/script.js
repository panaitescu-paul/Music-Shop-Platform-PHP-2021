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
    // Show all Artists
    $("#btnShowArtists").on("click", function(e) {
        e.preventDefault();

        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "artist",
                action: "getAll"
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

    // Open Modal - Create Artist 
    $(document).on("click", ".createArtistModal", function() {
        // e.preventDefault();

        showModal('createArtist');
    });
    // Open Modal - Update Artist
    $(document).on("click", ".updateArtistModal", function() {
        // e.preventDefault();
        const id = $(this).attr("data-id");
        showModal('updateArtist', id);
    });

    // Open Modal - Show Artist 
    $(document).on("click", ".showArtistModal", function() {
        // e.preventDefault();
        const action = 'show';
        const id = $(this).attr("data-id");
        console.log("action", action);
        console.log("id", id);

        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "artist",
                action: "get",
                id: id
            },
            success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                console.log(data);
                // if (userAuthenticated(data)) {
                    showModal('showArtist', id, data);
                // }
            }
        });
    });

    // Create Artist
    $(document).on("click", ".createArtist", function() {
        // e.preventDefault();

        const action = 'create';
        console.log("action", action);
        let info = {
            "name": $("#createArtistName").val()
        }
        if (info["name"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "artist",
                    action: "create",
                    info: info
                },
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    console.log(data);
                    console.log("Artist created");
                    // if (userAuthenticated(data)) {
                        // displayArtists(data);
                        // showArtistModal(data);
                    // }
                }
            });
        }
    });

    // Update Artist
    $(document).on("click", ".updateArtist", function() {
        // e.preventDefault();
        const action = 'update';
        console.log("action", action);
        console.log("data-id", $("#updateArtistName").attr("data-id"));
        let info = {
            "name": $("#updateArtistName").val(),
            "id": $("#updateArtistName").attr("data-id")
        }
        console.log("info", info);

        if (info["name"] !== null && info["id"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "artist",
                    action: "update",
                    info: info
                },
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    console.log(data);
                    console.log("Artist updated");
                    // if (userAuthenticated(data)) {
                        // displayArtists(data);
                        // showArtistModal(data);
                    // }
                }
            });
        }
    });

    // Delete Artist
    $(document).on("click", ".deleteArtist", function() {
        // e.preventDefault();
        const action = 'delete';
        const id = $(this).attr("data-id");
        console.log("action", action);
        console.log("id", id);

        if (confirm("Are you sure that you want to delete this Artist?")) {
            if (id !== null) {

                //  TODO: Check for Referential Integrity, chek if this artist has tracks???
                $.ajax({
                    url: "../src/api.php",
                    type: "POST",
                    data: {
                        entity: "artist",
                        action: "delete",
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        data = JSON.parse(data);
                        console.log(data);
                        
                        // if (userAuthenticated(data)) {
                            // displayArtists(data);
                            if (data === true) {
                                $("button[data-id=" + id + "]").parent().parent().remove();     // The table row is removed
                                console.log("Artist deleted");
                                showModal("artistDeleteSuccess");
                            } else {
                                console.log("Artist not deleted");
                                showModal("artistDeleteFailure");
                            }
                            
                        // }
                    }
                });
            }
        }
    });
    
    // Scroll Up
    $(document).on("click", ".scrollUp", function(e) {
        e.preventDefault();
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    });
    // Scroll Down
    $(document).on("click", ".scrollDown", function(e) {
        e.preventDefault();
        window.scrollTo(0, document.body.scrollHeight);
    });
    

    
});
