// TODO: add if(id == null)..... to check if the paramerters enters from front end are valid on each Ajax call

/**
 * Ajax calls that consume the RESTFUL API
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

// ******************************************************
// ***                                                ***
// ***                Login Functionality             ***
// ***                                                ***
// ******************************************************

$(document).ready(function() {
    URL = "http://localhost/WAD-MA2";

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

// ******************************************************
// ***                                                ***
// ***                Page Identification             ***
// ***                                                ***
// ******************************************************

    // Page identification
    // TODO: Remove docuemnt.ready. Use it as a simple funciton, the parent has docuemnt.ready
    $(document).ready(function () {
        // Get the current page 
        // Get the last part of the URL after the shash (/)
        let str = window.location.href;
        str = str.split("/"); 
        let page = str[str.length - 1]; 
        if ( page === "artists.php") {
            console.log("PAGE artists");
            ShowAllArtists();
        } else if ( page === "albums.php") {
            console.log("PAGE albums");
            ShowAllAlbums();
        } else if ( page === "tracks.php") {
            console.log("PAGE tracks");
            ShowAllTracks();
        } else {
            console.log("PAGE is NOT artists");
        }
    });

// ******************************************************
// ***                                                ***
// ***                ARTIST Functionality            ***
// ***                                                ***
// ******************************************************

    // Open Modal - Create Artist 
    $(document).on("click", ".createArtistModal", function() {
        showModal('createArtist');
    });

    // Open Modal - Update Artist
    $(document).on("click", ".updateArtistModal", function() {
        const id = $(this).attr("data-id");
        $.ajax({
            url: URL + `/artists/${id}`,
            type: "GET",
            success: function(data) {
                showModal('updateArtist', id, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Open Modal - Show Artist 
    $(document).on("click", ".showArtistModal", function() {
        const id = $(this).attr("data-id");
        $.ajax({
            url: URL + `/artists/${id}`,
            type: "GET",
            success: function(data) {
                showModal('showArtist', id, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Show all Artists in a List
    function ShowAllArtists() {
        $.ajax({
            url: URL + "/artists",
            type: "GET",
            success: function(data) {
                displayArtists(data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    }

    // Search Artists by name
    $("#btnSearchArtist").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: URL + `/artists`,
            type: "GET",
            data: {
                name: $("#searchArtistName").val()
            },
            success: function(data) {
                displayArtists(data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });
 
    // Create Artist
    $(document).on("click", ".createArtist", function() {
        const name = $("#createArtistName").val();
        if (name !== null) {
            $.ajax({
                url: URL + `/artists`,
                type: "POST",
                data: {
                    name: name
                },
                success: function(data) {
                    ShowAllArtists();
                    ScrollPage("bottomPage");
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    409: function() {
                        alert("Artist with this name already exists!");
                    }
                }
            });
        }
    });

    // Update Artist
    $(document).on("click", ".updateArtist", function(e) {
        const name = $("#updateArtistName").val();
        const id = $("#updateArtistName").attr("data-id");

        if (name !== null && id !== null) {
            $.ajax({
                url: URL + `/artists/${id}`,
                type: "POST",
                data: {
                    name: name
                },
                success: function(data) {
                    // Show the updated List of Artists
                    ShowAllArtists();

                    // Scroll to the updated Artist
                    ScrollPage(e.pageY);
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    404: function() {
                        alert("Artist with this id doesn't exist!");
                    },
                    409: function() {
                        alert("Artist with this name already exists!");
                    }
                }
            });
        }
    });

    // Delete Artist
    $(document).on("click", ".deleteArtist", function(e) {
        const action = 'delete';
        const id = $(this).attr("data-id");
        console.log("action", action);
        console.log("id", id);

        if (confirm("Are you sure that you want to delete this Artist?")) {
            if (id !== null) {
                $.ajax({
                    url: URL + `/artists/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        // Show the updated List of Artists
                        ShowAllArtists();
                        // Scroll to the updated Artist
                        ScrollPage(e.pageY);
                    },
                    error: function() { alert("An Error Ocured!"); },
                    statusCode: {
                        404: function() {
                            alert("Artist with this id doesn't exist!");
                        },
                        409: function() {
                            alert("Can't delete an Artist with Albums!");
                        }
                    }
                });
            }
        }
    });

// ******************************************************
// ***                                                ***
// ***                ALBUM Functionality             ***
// ***                                                ***
// ******************************************************

    // Open Modal - Create Album 
    $(document).on("click", ".createAlbumModal", function() {
        showModal('createAlbum');
    });

    // Open Modal - Update Album
    $(document).on("click", ".updateAlbumModal", function() {
        const id = $(this).attr("data-id");
        $.ajax({
            url: URL + `/albums/${id}`,
            type: "GET",
            success: function(data) {
                console.log(data);
                showModal('updateAlbum', id, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Open Modal - Show Album 
    $(document).on("click", ".showAlbumModal", function() {
        const id = $(this).attr("data-id");
        $.ajax({
            url: URL + `/albums/${id}`,
            type: "GET",
            success: function(data) {
                showModal('showAlbum', id, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Show All Albums in a List
    function ShowAllAlbums() {
        $.ajax({
            url: URL + `/albums`,
            type: "GET",
            success: function(data) {
                displayAlbums(data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    }

    // Search Albums by name
    $("#btnSearchAlbum").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "album",
                action: "search",
                searchText: $("#searchAlbumName").val()
            },
            success: function(data) {
                data = JSON.parse(data);
                console.log(data);
                // if (userAuthenticated(data)) {
                displayAlbums(data);
                // }
            }
        });
    });

    // Create Album
    $(document).on("click", ".createAlbum", function() {
        const action = 'create';
        console.log("action", action);
        let info = {
            "title": $("#createAlbumTitle").val(),
            "artistId": $("#createArtistId").val(),
        }
        console.log("info", info);

        if (info["title"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "album",
                    action: "create",
                    info: info
                },
                success: function(data) {
                    console.log(data);

                    data = JSON.parse(data);
                    console.log(data);
                    console.log("Album created");
                    ShowAllAlbums();
                    setTimeout(function (){
                        window.scrollTo(0, document.body.scrollHeight);
                    }, 700); // Delay in milliseconds
                    
                    // if (userAuthenticated(data)) {
                    // }
                    }
            });
        }
    });

    // Update Album
    $(document).on("click", ".updateAlbum", function(e) {
        const action = 'update';
        console.log("action", action);
        let info = {
            "title": $("#updateAlbumTitle").val(),
            "artistId": $("#updateArtistId").val(),
            "albumId": $("#updateAlbumTitle").attr("data-id")
        }
        console.log("info", info);

        if (info["title"] !== null && info["id"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "album",
                    action: "update",
                    info: info
                },
                success: function(data) {
                    // Show the updated List of Albums
                    ShowAllAlbums();
                    // Scroll to the updated Album
                    ScrollPage(e.pageY);
                    }
            });
        }
    });

    // Delete Album
    $(document).on("click", ".deleteAlbum", function(e) {
        const action = 'delete';
        const id = $(this).attr("data-id");
        console.log("action", action, " id", id);

        if (confirm("Are you sure that you want to delete this Album?")) {
            if (id !== null) {
                //  TODO: Check for Referential Integrity, chek if this Album ....???
                $.ajax({
                    url: "../src/api.php",
                    type: "POST",
                    data: {
                        entity: "album",
                        action: "delete",
                        id: id
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        console.log(data);

                        // Show the updated List of Albums
                        ShowAllAlbums();

                        // Scroll to the deleted Album
                        var position = e.pageY;
                        console.log("position", position);
                        document.body.scrollTop = position - 100; // For Safari
                        document.documentElement.scrollTop = position; // For Chrome, Firefox, IE and Opera
                        
                        // if (userAuthenticated(data)) {
                            if (data === true) {
                                console.log("Album deleted");
                                // showModal("artistDeleteSuccess");
                            } else {
                                console.log("Album not deleted");
                                // showModal("artistDeleteFailure");
                        }
                        // }
                    }
                });
            }
        }
    });

// ******************************************************
// ***                                                ***
// ***                TRACKS Functionality             ***
// ***                                                ***
// ******************************************************

    // Open Modal - Create Track 
    $(document).on("click", ".createTrackModal", function() {
        showModal('createTrack');
    });

    // Open Modal - Update Track
    $(document).on("click", ".updateTrackModal", function() {
        const id = $(this).attr("data-id");
        showModal('updateTrack', id);
        });

    // Open Modal - Show Track 
    $(document).on("click", ".showTrackModal", function() {
        const action = 'show';
        const id = $(this).attr("data-id");
        console.log("action", action, " id", id);
        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "track",
                action: "get",
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);
                console.log(data);
                // if (userAuthenticated(data)) {
                showModal('showTrack', id, data);
                // }
            }
        });
    });

    // Show All Tracks in a List
    function ShowAllTracks() {
        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "track",
                action: "getAll"
            },
            success: function(data) {
                data = JSON.parse(data);
                console.log(data);
                // if (userAuthenticated(data)) {
                displayTracks(data);
                // }
            }
        });
    }

    // Search Tracks by name
    $("#btnSearchTrack").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: "../src/api.php",
            type: "POST",
            data: {
                entity: "track",
                action: "search",
                searchText: $("#searchTrackName").val()
            },
            success: function(data) {
                data = JSON.parse(data);
                console.log(data);
                // if (userAuthenticated(data)) {
                displayTracks(data);
                // }
            }
        });
    });

    // Create Track
    $(document).on("click", ".createTrack", function() {
        const action = 'create';
        console.log("action", action);
        let info = {
            "title": $("#createTrackTitle").val(),
            "artistId": $("#createArtistId").val(),
        }
        console.log("info", info);
           
        if (info["title"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "track",
                    action: "create",
                    info: info
                },
                success: function(data) {
                    console.log(data);

                    data = JSON.parse(data);
                    console.log(data);
                    console.log("Track created");
                    ShowAllTracks();
                    setTimeout(function (){
                        window.scrollTo(0, document.body.scrollHeight);
                    }, 700); // Delay in milliseconds
                    
                    // if (userAuthenticated(data)) {
                    // }
                }
            });
        }
    });

    // Update Track
    $(document).on("click", ".updateTrack", function(e) {
        const action = 'update';
        console.log("action", action);
        let info = {
            "title": $("#updateTrackTitle").val(),
            "artistId": $("#updateArtistId").val(),
            "trackId": $("#updateTrackTitle").attr("data-id")
        }
        console.log("info", info);

        if (info["title"] !== null && info["id"] !== null) {
            $.ajax({
                url: "../src/api.php",
                type: "POST",
                data: {
                    entity: "track",
                    action: "update",
                    info: info
                },
                success: function(data) {
                    data = JSON.parse(data);
                    console.log(data);
                    console.log("Track updated");

                    // Show the updated List of Tracks
                    ShowAllTracks();

                    // Scroll to the updated Track
                    var position = e.pageY;
                    console.log("position", position);
                    document.body.scrollTop = position - 100; // For Safari
                    document.documentElement.scrollTop = position; // For Chrome, Firefox, IE and Opera

                    // if (userAuthenticated(data)) {
                    // }
                }
            });
        }
    });

    // Delete Track
    $(document).on("click", ".deleteTrack", function(e) {
        const action = 'delete';
        const id = $(this).attr("data-id");
        console.log("action", action, " id", id);

        if (confirm("Are you sure that you want to delete this Track?")) {
            if (id !== null) {
                //  TODO: Check for Referential Integrity, chek if this Track ....???
                $.ajax({
                    url: "../src/api.php",
                    type: "POST",
                    data: {
                        entity: "track",
                        action: "delete",
                        id: id
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        console.log(data);

                        // Show the updated List of Tracks
                        ShowAllTracks();

                        // Scroll to the deleted Track
                        var position = e.pageY;
                        console.log("position", position);
                        document.body.scrollTop = position - 100; // For Safari
                        document.documentElement.scrollTop = position; // For Chrome, Firefox, IE and Opera
                        
                        // if (userAuthenticated(data)) {
                            if (data === true) {
                                console.log("Track deleted");
                                // showModal("artistDeleteSuccess");
                            } else {
                                console.log("Track not deleted");
                                // showModal("artistDeleteFailure");
                        }
                        // }
                    }
                });
            }
        }
    });

// ******************************************************
// ***                                                ***
// ***                Scrolling Functionality         ***
// ***                                                ***
// ******************************************************

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

    // Scroll to page position
    function ScrollPage(position) {
        console.log(position);

        if (position == "bottomPage") {
            console.log(1);
            setTimeout(function (){
                window.scrollTo(0, document.body.scrollHeight);
            }, 700); // Delay in milliseconds
        } else {
            document.body.scrollTop = position - 100; // For Safari
            document.documentElement.scrollTop = position; // For Chrome, Firefox, IE and Opera
        }

        
    }

    

});
