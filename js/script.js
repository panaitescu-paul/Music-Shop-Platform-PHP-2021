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
    // URL = "http://chinook-env.eba-a22nb3p8.us-east-1.elasticbeanstalk.com/src/api.php";

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
        if (page === "artists.php") {
            console.log("PAGE artists");
            ShowAllArtists();
        } else if (page === "library-artists.php") {
            console.log("PAGE library-artists");
            ShowAllArtists('customer');
        } else if (page === "albums.php") {
            console.log("PAGE albums");
            ShowAllAlbums();
        } else if (page === "library-albums.php") {
            console.log("PAGE library-albums");
            ShowAllAlbums('customer');
        } else if (page === "tracks.php") {
            console.log("PAGE tracks");
            ShowAllTracks('admin');
        } else if (page === "library-tracks.php") {
            console.log("PAGE library-tracks");
            ShowAllTracks('customer');
            GetPurchasePrice(shoppingCartInfo['tracks']);
        } else if (page === "shopping-cart.php") {
            console.log("PAGE shopping-cart");
            ShowAllTracks('customer', shoppingCartInfo['tracks']);
            GetPurchasePrice(shoppingCartInfo['tracks']);
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
    function ShowAllArtists(user = 'admin') {
        $.ajax({
            url: URL + "/artists",
            type: "GET",
            success: function(data) {
                console.log();
                if (user == 'customer') {
                    displayArtists(data, 'customer');
                } else {
                    displayArtists(data);
                }
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
    function ShowAllAlbums(user = 'admin') {
        $.ajax({
            url: URL + `/albums`,
            type: "GET",
            success: function(data) {
                if (user == 'customer') {
                    displayAlbums(data, 'customer');
                } else {
                    displayAlbums(data);
                }
            },
            error: function() { alert("An Error Ocured!"); }
        });
    }

    // Search Albums by name
    $("#btnSearchAlbum").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: URL + `/albums`,
            type: "GET",
            data: {
                title: $("#searchAlbumName").val()
            },
            success: function(data) {
                displayAlbums(data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Create Album
    $(document).on("click", ".createAlbum", function() {
        const title = $("#createAlbumTitle").val();
        const artistId = $("#createArtistId").val();
        if (title !== null) {
            $.ajax({
                url: URL + `/albums`,
                type: "POST",
                data: {
                    artistId: artistId,
                    title: title
                },
                success: function(data) {
                    ShowAllAlbums();
                    ScrollPage("bottomPage");
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    404: function() {
                        alert("Artist with this id doesn't exist!!");
                    },
                    409: function() {
                        alert("Album with this title already exists!");
                    }
                }
            });
        }
    });

    // Update Album
    $(document).on("click", ".updateAlbum", function(e) {
        const albumId = $("#updateAlbumId").attr("data-id");
        const title = $("#updateAlbumTitle").val();
        const artistId = $("#updateArtisId").val();

        if (albumId !== null && title !== null && artistId !== null) {
            $.ajax({
                url: URL + `/albums/${albumId}`,
                type: "POST",
                data: {
                    title: title,
                    artistId: artistId,
                },
                success: function(data) {
                    // Show the updated List of Albums
                    ShowAllAlbums();
                    // Scroll to the updated Album
                    ScrollPage(e.pageY);
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    404: function() {
                        alert("Album or Artist with this id doesn't exist!");
                    },
                    409: function() {
                        alert("Album with this title already exists!");
                    }
                }
            });
        }
    });

    // Delete Album
    $(document).on("click", ".deleteAlbum", function(e) {
        const id = $(this).attr("data-id");

        if (confirm("Are you sure that you want to delete this Album?")) {
            if (id !== null) {
                $.ajax({
                    url: URL + `/albums/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        // Show the updated List of Albums
                        ShowAllAlbums();
                        // Scroll to the deleted Album
                        ScrollPage(e.pageY);
                    },
                    error: function() { alert("An Error Ocured!"); },
                    statusCode: {
                        404: function() {
                            alert("Album with this id doesn't exist!");
                        },
                        409: function() {
                            alert("Can't delete an Album with Tracks!");
                        }
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
        $.ajax({
            url: URL + `/tracks/${id}`,
            type: "GET",
            success: function(data) {
                showModal('updateTrack', id, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
        // showModal('updateTrack', id);
    });

    // Open Modal - Show Track 
    $(document).on("click", ".showTrackModal", function() {
        const id = $(this).attr("data-id");
        $.ajax({
            url: URL + `/tracks/${id}`,
            type: "GET",
            success: function(data) {
                showModal('showTrack', id, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Show All Tracks in a List
    function ShowAllTracks(user, shoppingCart) {
        $.ajax({
            url: URL + `/tracks`,
            type: "GET",
            success: function(data) {
                if (user == 'admin') {
                    displayTracks(data, 1);
                } else {
                    if (shoppingCart) {
                        displayTracks(data, 0, shoppingCart);
                    } else {
                        displayTracks(data);
                    }
                }
            },
            error: function() { alert("An Error Ocured!"); }
        });
    }

    // Search Tracks by name
    $("#btnSearchTrack").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: URL + `/tracks`,
            type: "GET",
            data: {
                name: $("#searchTrackName").val()
            },
            success: function(data) {
                displayTracks(data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Create Track
    $(document).on("click", ".createTrack", function() {
        const name = $("#createTrackName").val(); 
        const albumId = $("#createAlbumId").val(); 
        const mediaTypeId = $("#createMediaTypeId").val(); 
        const genreId = $("#createGenreId").val(); 
        const composer = $("#createComposer").val(); 
        const milliseconds = $("#createMilliseconds").val(); 
        const bytes = $("#createBytes").val(); 
        const unitPrice = $("#createUnitPrice").val();
           
        if (name !== null ) {
            $.ajax({
                url: URL + `/tracks`,
                type: "POST",
                data: {
                    name: name,
                    albumId: albumId,
                    mediaTypeId: mediaTypeId,
                    genreId: genreId,
                    composer: composer,
                    milliseconds: milliseconds,
                    bytes: bytes,
                    unitPrice: unitPrice,
                },
                success: function(data) {
                    ShowAllTracks('admin');
                    ScrollPage("bottomPage");
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    404: function() {
                        alert("AlbumId, MediaTypeId, or GenreId doesn't exist");
                    },
                    409: function() {
                        alert("Track with this name already exists!");
                    }
                }
            });
        }
    });

    // Update Track
    $(document).on("click", ".updateTrack", function(e) {
        const trackId = $("#updateTrackId").attr("data-id"); 
        const name = $("#updateTrackName").val(); 
        const albumId = $("#updateAlbumId").val(); 
        const mediaTypeId = $("#updateMediaTypeId").val(); 
        const genreId = $("#updateGenreId").val(); 
        const composer = $("#updateComposer").val(); 
        const milliseconds = $("#updateMilliseconds").val(); 
        const bytes = $("#updateBytes").val(); 
        const unitPrice = $("#updateUnitPrice").val();

        if (trackId !== null && name !== null) {
            $.ajax({
                url: URL + `/tracks/${trackId}`,
                type: "POST",
                data: {
                    name: name,
                    albumId: albumId,
                    mediaTypeId: mediaTypeId,
                    genreId: genreId,
                    composer: composer,
                    milliseconds: milliseconds,
                    bytes: bytes,
                    unitPrice: unitPrice,
                },
                success: function(data) {
                    // Show the updated List of Tracks
                    ShowAllTracks('admin');
                    // Scroll to the updated Track
                    ScrollPage(e.pageY);
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    404: function() {
                        alert("TrackId, AlbumId, MediaTypeId, or GenreId doesn't exist!");
                    },
                    409: function() {
                        alert("Track with this name already exists!");
                    }
                }
            });
        }
    });

    // Delete Track
    $(document).on("click", ".deleteTrack", function(e) {
        const id = $(this).attr("data-id");

        if (confirm("Are you sure that you want to delete this Track?")) {
            if (id !== null) {
                //  TODO: Check for Referential Integrity, chek if this Track ....???
                $.ajax({
                    url: URL + `/tracks/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        // Show the updated List of Tracks
                        ShowAllTracks('admin');
                        // Scroll to the deleted Track
                        ScrollPage(e.pageY);
                    },
                    error: function() { alert("An Error Ocured!"); },
                    statusCode: {
                        404: function() {
                            alert("Track with this id doesn't exist!");
                        },
                        409: function() {
                            alert("Can't delete a Track that has been Purchased (has an Invoiceline)!");
                        }
                    }
                });
            }
        }
    });

    // ******************************************************
    // ***                                                ***
    // ***              Purchase Functionality            ***
    // ***                                                ***
    // ******************************************************

    // Show Purchase Modal
    $(document).on("click", ".purchaseModal", function(e) {
        e.preventDefault();
        const id = shoppingCartInfo['userID'];
        $.ajax({
            url: URL + `/customers/${id}`,
            type: "GET",
            success: function(data) {
                showModal('showPurchase', 0, data);
            },
            error: function() { alert("An Error Ocured!"); }
        });
    });

    // Confirm Purchase
    $(document).on("click", ".confirmPurchase", function() {
        const customerId = $("#customerId").val(); 
        const billingAddress = $("#billingAddress").val(); 
        let tracks = shoppingCartInfo['tracks'];
       
        // 
        // 
        // TODO: make a call to purchase() without the Ajax, so the API is Restful...
        // 
        // 
        if (customerId !== null ) {
            $.ajax({
                url: URL + `/purchase`,
                type: "POST",
                data: {
                    id: customerId,
                    customBillingAddress: billingAddress,
                    tracks: tracks
                },
                success: function(data) {
                    alert("Purchase Made!");
                    ResetShoppingCart();
                    ShowAllTracks('customer', shoppingCartInfo['tracks']);
                },
                error: function() { alert("An Error Ocured!"); },
                statusCode: {
                    404: function() {
                        alert("CustomerId or TracksId don't exist!");
                    },
                    409: function() {
                        alert("The Purchase could not be made!");
                    }
                }
            });
        }
    });

    // Get Purchase Price - sum of all Track Unit Prices
    function GetPurchasePrice(trackIds) {
        $.ajax({
            url: URL + `/tracks`,
            type: "GET",
            success: function(data) {
                let sum = 0;
                for (let i = 0; i < trackIds.length; i++) {
                    const id = trackIds[i];
                    data.forEach(track => {
                        if (track['TrackId'] == id) {
                            sum += parseFloat(track['UnitPrice']);
                        }
                    });
                }
                document.getElementById("purchaseTotalPrice").innerHTML = sum;
            },
            error: function() { alert("An Error Ocured!"); }
        });
    }

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
        if (position == "bottomPage") {
            setTimeout(function (){
                window.scrollTo(0, document.body.scrollHeight);
            }, 700); // Delay in milliseconds
        } else {
            document.body.scrollTop = position - 100; // For Safari
            document.documentElement.scrollTop = position; // For Chrome, Firefox, IE and Opera
        }
    }

    // Reset Shopping Cart - delete Tracks from it
    function ResetShoppingCart(position) {
        $("#resetPurchaseCart").click();
    }

    // // Refresh page after Adding Track  to Cart
    // $(document).on("click", ".btnAddToChart", function(e) {
    //     // e.preventDefault();
    //     location.reload();
    // });
});
