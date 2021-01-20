
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

    // 
    
    // const INVALID_TEXT = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    const INVALID_TEXT = /[`!@#$%^&*_+\=\[\]{};"\\|<>\/?~]/;
    const INVALID_EMAIL = /[`!#$%^&*+\=\[\]{};"\\|<>\/?~]/;
    const VALID_NUMBER = /^\d+$/;
        
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
        const artistName = $("#searchArtistName").val();

        if (artistName === null || artistName.length === 0) {
            alert("The field Artist Name can not be empty!");
        } else if (INVALID_TEXT.test(artistName)) {
            alert("The field Artist Name can not contain invalid characters!");
        } else {
            $.ajax({
                url: URL + `/artists`,
                type: "GET",
                data: {
                    name: artistName
                },
                success: function(data) {
                    displayArtists(data, 'customer');
                },
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    }
                }
            });
        }
    });
 
    // Create Artist
    $(document).on("click", ".createArtist", function() {
        const artistName = $("#createArtistName").val();
        
        if (artistName === null || artistName.length === 0) {
            alert("The field Artist Name can not be empty!");
        } else if (INVALID_TEXT.test(artistName)) {
            alert("The field Artist Name can not contain invalid characters!");
        } else {
            $.ajax({
                url: URL + `/artists`,
                type: "POST",
                data: {
                    name: artistName
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
        const artistName = $("#updateArtistName").val();
        const id = $("#updateArtistName").attr("data-id");

        if (artistName === null || artistName.length === 0) {
            alert("The field Artist Name can not be empty!");
        } else if (INVALID_TEXT.test(artistName)) {
            alert("The field Artist Name can not contain invalid characters!");
        } else {
            $.ajax({
                url: URL + `/artists/${id}`,
                type: "POST",
                data: {
                    name: artistName
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
        const id = $(this).attr("data-id");
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
        PopulateArtistsDropdown();
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
                PopulateArtistsDropdown();
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
        const albumTitle = $("#searchAlbumName").val();

        if (albumTitle === null || albumTitle.length === 0) {
            alert("The field Album Title can not be empty!");
        } else if (INVALID_TEXT.test(albumTitle)) {
            alert("The field Album Title can not contain invalid characters!");
        } else {
            $.ajax({
                url: URL + `/albums`,
                type: "GET",
                data: {
                    title: albumTitle
                },
                success: function(data) {
                    displayAlbums(data, 'customer');
                },
                statusCode: {
                    404: function(data) {
                        const errorMsg = JSON.parse(data.responseText).Error;
                        alert(errorMsg);
                    }
                }
            });
        }
    });

    // Create Album
    $(document).on("click", ".createAlbum", function() {
        const albumTitle = $("#createAlbumTitle").val();
        // const artistId = $("#createArtistId").val();
        const artistId = $("#artistList :selected").val();
        console.log(artistId);

        if (albumTitle === null || albumTitle.length === 0) {
            alert("The field Album Title can not be empty!");
        } else if (artistId === null || artistId.length === 0) {
            alert("The field Artist Id can not be empty!");
        } else if (INVALID_TEXT.test(albumTitle)) {
            alert("The field Album Title can not contain invalid characters!");
        } else if (!VALID_NUMBER.test(artistId)) {
            alert("The field Artist Id should be a number!");
        } else {
            $.ajax({
                url: URL + `/albums`,
                type: "POST",
                data: {
                    artistId: artistId,
                    title: albumTitle
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
        const albumTitle = $("#updateAlbumTitle").val();
        // const artistId = $("#updateArtisId").val();
        const artistId = $("#artistList :selected").val();
        console.log(artistId);

        if (albumTitle === null || albumTitle.length === 0) {
            alert("The field Album Title can not be empty!");
        } else if (artistId === null || artistId.length === 0) {
            alert("The field Artist Id can not be empty!");
        } else if (INVALID_TEXT.test(albumTitle)) {
            alert("The field Album Title can not contain invalid characters!");
        } else if (!VALID_NUMBER.test(artistId)) {
            alert("The field Artist Id should be a number!");
        } else {
            $.ajax({
                url: URL + `/albums/${albumId}`,
                type: "POST",
                data: {
                    title: albumTitle,
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
        PopulateAlbumsDropdown();
    });

    // Open Modal - Update Track
    $(document).on("click", ".updateTrackModal", function() {
        const id = $(this).attr("data-id");
        $.ajax({
            url: URL + `/tracks/${id}`,
            type: "GET",
            success: function(data) {
                showModal('updateTrack', id, data);
                PopulateAlbumsDropdown();
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
        const trackName = $("#searchTrackName").val();

        if (trackName === null || trackName.length === 0) {
            alert("The field Track Name can not be empty!");
        } else if (INVALID_TEXT.test(trackName)) {
            alert("The field Track Name can not contain invalid characters!");
        } else {
            $.ajax({
                url: URL + `/tracks`,
                type: "GET",
                data: {
                    name: trackName
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
        }
    });

    // Create Track
    $(document).on("click", ".createTrack", function() {
        const name = $("#createTrackName").val(); 
        // const albumId = $("#createAlbumId").val(); 
        const albumId = $("#albumList :selected").val();
        const mediaTypeId = $("#createMediaTypeId").val(); 
        const genreId = $("#createGenreId").val(); 
        const composer = $("#createComposer").val(); 
        const milliseconds = $("#createMilliseconds").val(); 
        const bytes = $("#createBytes").val(); 
        const unitPrice = $("#createUnitPrice").val();
        console.log(albumId);
           
        if (name === null || name.length === 0) {
            alert("The field Track Name can not be empty!");
        } else if (albumId === null || albumId.length === 0) {
            alert("The field Album Id can not be empty!");
        } else if (mediaTypeId === null || mediaTypeId.length === 0) {
            alert("The field Media Type Id can not be empty!");
        } else if (genreId === null || genreId.length === 0) {
            alert("The field Genre Id can not be empty!");
        } else if (composer === null || composer.length === 0) {
            alert("The field Composer can not be empty!");
        } else if (milliseconds === null || milliseconds.length === 0) {
            alert("The field Milliseconds can not be empty!");
        } else if (bytes === null || bytes.length === 0) {
            alert("The field Bytes can not be empty!");
        } else if (unitPrice === null || unitPrice.length === 0) {
            alert("The field Unit Price can not be empty!");
        
        } else if (INVALID_TEXT.test(name)) {
            alert("The field Track Name can not contain invalid characters!");
        } else if (INVALID_TEXT.test(albumId)) {
            alert("The field Album Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(mediaTypeId)) {
            alert("The field Media Type Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(genreId)) {
            alert("The field Genre Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(composer)) {
            alert("The field Composer can not contain invalid characters!");
        } else if (INVALID_TEXT.test(milliseconds)) {
            alert("The field Milliseconds can not contain invalid characters!");
        } else if (INVALID_TEXT.test(bytes)) {
            alert("The field Bytes can not contain invalid characters!");
        } else if (INVALID_TEXT.test(unitPrice)) {
            alert("The field Unit Price can not contain invalid characters!");
        } else {
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
        // const albumId = $("#updateAlbumId").val(); 
        const albumId = $("#albumList :selected").val();
        const mediaTypeId = $("#updateMediaTypeId").val(); 
        const genreId = $("#updateGenreId").val(); 
        const composer = $("#updateComposer").val(); 
        const milliseconds = $("#updateMilliseconds").val(); 
        const bytes = $("#updateBytes").val(); 
        const unitPrice = $("#updateUnitPrice").val();
        console.log(albumId);

        if (name === null || name.length === 0) {
            alert("The field Track Name can not be empty!");
        } else if (albumId === null || albumId.length === 0) {
            alert("The field Album Id can not be empty!");
        } else if (mediaTypeId === null || mediaTypeId.length === 0) {
            alert("The field Media Type Id can not be empty!");
        } else if (genreId === null || genreId.length === 0) {
            alert("The field Genre Id can not be empty!");
        } else if (composer === null || composer.length === 0) {
            alert("The field Composer can not be empty!");
        } else if (milliseconds === null || milliseconds.length === 0) {
            alert("The field Milliseconds can not be empty!");
        } else if (bytes === null || bytes.length === 0) {
            alert("The field Bytes can not be empty!");
        } else if (unitPrice === null || unitPrice.length === 0) {
            alert("The field Unit Price can not be empty!");
        
        } else if (INVALID_TEXT.test(name)) {
            alert("The field Track Name can not contain invalid characters!");
        } else if (INVALID_TEXT.test(albumId)) {
            alert("The field Album Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(mediaTypeId)) {
            alert("The field Media Type Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(genreId)) {
            alert("The field Genre Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(composer)) {
            alert("The field Composer can not contain invalid characters!");
        } else if (INVALID_TEXT.test(milliseconds)) {
            alert("The field Milliseconds can not contain invalid characters!");
        } else if (INVALID_TEXT.test(bytes)) {
            alert("The field Bytes can not contain invalid characters!");
        } else if (INVALID_TEXT.test(unitPrice)) {
            alert("The field Unit Price can not contain invalid characters!");
        } else {
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
           
        if (firstName === null || firstName.length === 0) {
            alert("The field First Name can not be empty!");
        } else if (lastName === null || lastName.length === 0) {
            alert("The field Last Name can not be empty!");
        } else if (password === null || password.length === 0) {
            alert("The field Password can not be empty!");
        } else if (email === null || email.length === 0) {
            alert("The field Email can not be empty!");
        
        } else if (INVALID_TEXT.test(firstName)) {
            alert("The field First Name can not contain invalid characters!");
        } else if (INVALID_TEXT.test(lastName)) {
            alert("The field Last Name can not contain invalid characters!");
        } else if (INVALID_TEXT.test(password)) {
            alert("The field Password can not contain invalid characters!");
        } else if (INVALID_TEXT.test(company)) {
            alert("The field Company can not contain invalid characters!");
        } else if (INVALID_TEXT.test(address)) {
            alert("The field Address can not contain invalid characters!");
        } else if (INVALID_TEXT.test(city)) {
            alert("The field City can not contain invalid characters!");
        } else if (INVALID_TEXT.test(state)) {
            alert("The field State can not contain invalid characters!");
        } else if (INVALID_TEXT.test(country)) {
            alert("The field Country can not contain invalid characters!");
        } else if (INVALID_TEXT.test(postalCode)) {
            alert("The field Postal Code can not contain invalid characters!");
        } else if (INVALID_TEXT.test(phone)) {
            alert("The field Phone can not contain invalid characters!");
        } else if (INVALID_TEXT.test(fax)) {
            alert("The field Fax can not contain invalid characters!");
        } else if (INVALID_EMAIL.test(email)) {
            alert("The field Email can not contain invalid characters!");
        } else {
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

        if (customerId === null || customerId.length === 0) {
            alert("The field Customer Id can not be empty!");
        } else if (firstName === null || firstName.length === 0) {
            alert("The field First Name can not be empty!");
        } else if (lastName === null || lastName.length === 0) {
            alert("The field Last Name can not be empty!");
        } else if (password === null || password.length === 0) {
            alert("The field Password can not be empty!");
        } else if (email === null || email.length === 0) {
            alert("The field Email can not be empty!");

        } else if (INVALID_TEXT.test(customerId)) {
            alert("The field Customer Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(firstName)) {
            alert("The field First Name can not contain invalid characters!");
        } else if (INVALID_TEXT.test(lastName)) {
            alert("The field Last Name can not contain invalid characters!");
        } else if (INVALID_TEXT.test(password)) {
            alert("The field Password can not contain invalid characters!");
        } else if (INVALID_TEXT.test(newPassword)) {
            alert("The field New Password can not contain invalid characters!");
        } else if (INVALID_TEXT.test(company)) {
            alert("The field Company can not contain invalid characters!");
        } else if (INVALID_TEXT.test(address)) {
            alert("The field Address can not contain invalid characters!");
        } else if (INVALID_TEXT.test(city)) {
            alert("The field City can not contain invalid characters!");
        } else if (INVALID_TEXT.test(state)) {
            alert("The field State can not contain invalid characters!");
        } else if (INVALID_TEXT.test(country)) {
            alert("The field Country can not contain invalid characters!");
        } else if (INVALID_TEXT.test(postalCode)) {
            alert("The field Postal Code can not contain invalid characters!");
        } else if (INVALID_TEXT.test(phone)) {
            alert("The field Phone can not contain invalid characters!");
        } else if (INVALID_TEXT.test(fax)) {
            alert("The field Fax can not contain invalid characters!");
        } else if (INVALID_EMAIL.test(email)) {
            alert("The field Email can not contain invalid characters!");
        } else {
            if (!newPassword) { // Without Password Reset
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
        if (customerId === null || customerId.length === 0) {
            alert("The field Customer Id can not be empty!");
        } else if (billingAddress === null || billingAddress.length === 0) {
            alert("The field Billing Address can not be empty!");

        } else if (INVALID_TEXT.test(customerId)) {
            alert("The field Customer Id can not contain invalid characters!");
        } else if (INVALID_TEXT.test(billingAddress)) {
            alert("The field Billing Address can not contain invalid characters!");
        } else {
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
    // ***                Populating Dropdowns            ***
    // ***                                                ***
    // ******************************************************

    // Show all Artists in a List
    function PopulateArtistsDropdown() {
        $.ajax({
            url: URL + "/artists",
            type: "GET",
            success: function(data) {
                data.forEach(artist => {
                    $("#artistList").append(
                        `<option value="${artist.ArtistId}" text="${artist.Name}" class="form-control">${artist.Name}</option>`
                    );
                });
            },
            statusCode: {
                404: function(data) {
                    const errorMsg = JSON.parse(data.responseText).Error;
                    alert(errorMsg);
                }
            }
        });
    }

    // Show all Albums in a List
    function PopulateAlbumsDropdown() {
        $.ajax({
            url: URL + "/albums",
            type: "GET",
            success: function(data) {
                data.forEach(album => {
                    $("#albumList").append(
                        `<option value="${album.ArtistId}" text="${album.Title}" class="form-control">${album.Title}</option>`
                    );
                });
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