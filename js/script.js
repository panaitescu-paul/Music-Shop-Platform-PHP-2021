// TODO: make a call to purchase() without the API, so the API is Restful
// TODO: add if(id == null)..... to check if the parameters enterd from Front End are valid on each Ajax call

/**
 * Ajax calls that consume the RESTFUL API
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

$(document).ready(function() {
    // Local version
    URL = "http://localhost/WAD-MA2";
    // // AWS version
    // URL = "http://musicshop-env.eba-j5assrnn.us-east-1.elasticbeanstalk.com/src/api2.php";

    pageContent();

    // ******************************************************
    // ***                                                ***
    // ***   Page identification + Content assignment     ***
    // ***                                                ***
    // ******************************************************

    // Page identification
    function pageContent() {
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
    }

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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
                statusCode: {
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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

                        const msg = JSON.parse(data.responseText).Success;
                        alert(msg);
                    },
                    statusCode: {
                        404: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        },
                        409: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        },
                        500: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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
                    statusCode: {
                        404: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        },
                        409: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        },
                        500: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        }
                    }
                });
            }
        }
    });

    // ******************************************************
    // ***                                                ***
    // ***                TRACKS Functionality            ***
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
        });
    });

    // Open Modal - Show Track 
    $(document).on("click", ".showTrackModal", function() {
        const id = $(this).attr("data-id");
        console.log(id);
        $.ajax({
            url: URL + `/tracks/${id}`,
            type: "GET",
            success: function(data) {
                showModal('showTrack', id, data);
            },
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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
                    statusCode: {
                        404: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        },
                        409: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        },
                        500: function(data) {
                            const errorMsg = JSON.parse(data.responseText).Error;
                            alert(errorMsg);
                        }
                    }
                });
            }
        }
    });

    // ******************************************************
    // ***                                                ***
    // ***              Customer Functionality            ***
    // ***                                                ***
    // ******************************************************

    // Open Modal - Create Customer 
    $(document).on("click", ".createCustomerModal", function() {
        console.log(1);
        showModal('createCustomer');
    });

    // Open Modal - Update Customer
    $(document).on("click", ".updateCustomerModal", function() {
        const id = userId;
        console.log(id);
        $.ajax({
            url: URL + `/customers/${id}`,
            type: "GET",
            success: function(data) {
                showModal('updateCustomer', id, data);
            },
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
        });
    });

    // Create Customer
    $(document).on("click", ".createCustomer", function() {
        const firstName = $("#firstName").val(); 
        const lastName = $("#lastName").val(); 
        const password = $("#password").val(); 
        const company = $("#company").val(); 
        const address = $("#address").val(); 
        const city = $("#city").val(); 
        const state = $("#state").val(); 
        const country = $("#country").val();
        const postalCode = $("#postalCode").val();
        const phone = $("#phone").val();
        const fax = $("#fax").val();
        const email = $("#email").val();
           
        if (firstName !== null ) {
            $.ajax({
                url: URL + `/customers`,
                type: "POST",
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    password: password,
                    company: company,
                    address: address,
                    city: city,
                    state: state,
                    country: country,
                    postalCode: postalCode,
                    phone: phone,
                    fax: fax,
                    email: email
                },
                success: function(data) {
                    alert("Customer successfully created!");
                },
                statusCode: {
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    }
                }
            });
        }
    });

    // Update Customer
    $(document).on("click", ".updateCustomer", function(e) {
        const customerId = $("#customerId").val(); 
        const firstName = $("#firstName").val(); 
        const lastName = $("#lastName").val(); 
        const company = $("#company").val(); 
        const address = $("#address").val(); 
        const city = $("#city").val(); 
        const state = $("#state").val(); 
        const country = $("#country").val();
        const postalCode = $("#postalCode").val();
        const phone = $("#phone").val();
        const fax = $("#fax").val();
        const email = $("#email").val();
        const password = $("#password").val(); 
        const newPassword = $("#newPassword").val(); 

        if (newPassword) { // Without Password Reset
            $.ajax({
                url: URL + `/customers/${customerId}`,
                type: "POST",
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    password: password,
                    company: company,
                    address: address,
                    city: city,
                    state: state,
                    country: country,
                    postalCode: postalCode,
                    phone: phone,
                    fax: fax,
                    email: email,
                    newPassword: newPassword
                },
                success: function(data) {
                    alert("Customer successfully updated!");
                },
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    }
                }
            });
        } else { // With Password Reset
            $.ajax({
                url: URL + `/customers/${customerId}`,
                type: "POST",
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    password: password,
                    company: company,
                    address: address,
                    city: city,
                    state: state,
                    country: country,
                    postalCode: postalCode,
                    phone: phone,
                    fax: fax,
                    email: email,
                    newPassword: newPassword
                },
                success: function(data) {
                    alert("Customer successfully updated!");
                    alert("Password successfully changed!");
                },
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    409: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    }
                }
            });
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
        });
    });

    // Confirm Purchase
    $(document).on("click", ".confirmPurchase", function() {
        const customerId = $("#customerId").val(); 
        const billingAddress = $("#billingAddress").val(); 
        let tracks = shoppingCartInfo['tracks'];
       
        // TODO: make a call to purchase() without the API, so the API is Restful
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
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    },
                    // 409: function(data) {
                    //     const errorMsg = JSON.parse(data.responseText).Error;
                    //     alert(errorMsg);
                    // },
                    500: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
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
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
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
});


// 