// TODO: add function to populate the dropdowns with: id selections, genre selection, mediatype selection

/**
 * JavaScript DOM Manipulation
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

// Loads Artist information in artistResults
function displayArtists(artistData, user = 'admin') {
    if (artistData.length === 0) {
        $("section#artistResults").html("There are no artists matching the entered text.");
    } else { // Display Artist information for Admin
        $("section#artistResults").empty();
        const table = $("<table />", {"class": "table tableList"});
        const header = $("<thead />");
        const headerRow = $("<tr />");
        headerRow.
            append($("<th />", { "text": "Id"})).
            append($("<th />", { "text": "Name", "class": "number"})).
            append($("<th />", { "text": "Actions", "class": "number"}))
        header.append(headerRow);
        table.append(header);
        const tableBody = $("<tbody />");
        if (user == 'admin') {
            for (const artist of artistData) {
                const row = $("<tr />");
                const artistID = artist["ArtistId"];
                row.
                    append($("<td />", { "text": artist["ArtistId"]})).
                    append($("<td />", { "text": artist["Name"]})).
                    append($("<td />", { "class": "table-actions", "html": 
                        "<button data-id='" + artistID + "' type='button' class='btn btn-danger btnDelete deleteArtist'>" +
                            "<img src='../img/trash.svg' class='icon-delete'>" +
                        "</button>" +
                        "<button data-id='" + artistID + "' type='button' class='btn btn-warning btnUpdate updateArtistModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/pencil-square.svg' class='icon-update'>" +
                        "</button>" +
                        "<button data-id='" + artistID + "' type='button' class='btn btn-success btnShow showArtistModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/card-text.svg' class='icon-show'>" +
                        "</button>"
                    }))
                tableBody.append(row);
            }
        } else { // Display Artist information for Customer
            for (const artist of artistData) {
                const row = $("<tr />");
                const artistID = artist["ArtistId"];
                row.
                    append($("<td />", { "text": artist["ArtistId"]})).
                    append($("<td />", { "text": artist["Name"]})).
                    append($("<td />", { "class": "table-actions", "html": 
                        "<button data-id='" + artistID + "' type='button' class='btn btn-success btnShow showArtistModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/card-text.svg' class='icon-show'>" +
                        "</button>"
                    }))
                tableBody.append(row);
            }
        }
        table.append(tableBody);
        table.appendTo($("section#artistResults"));
    }
}

// Loads Albums information in artistResults
function displayAlbums(albumData, user = 'admin') {
    if (albumData.length === 0) {
        $("section#results").html("There are no albums matching the entered text.");
    } else {
        $("section#results").empty();
        const table = $("<table />", {"class": "table tableList"});
        const header = $("<thead />");
        const headerRow = $("<tr />");
        headerRow.
            append($("<th />", { "text": "AlbumId"})).
            append($("<th />", { "text": "Title", "class": "number"})).
            append($("<th />", { "text": "ArtistId", "class": "number"})).
            append($("<th />", { "text": "Actions", "class": "number"}))
        header.append(headerRow);
        table.append(header);
        const tableBody = $("<tbody />");
        if (user == 'admin') { // Displays Albums information for Admin
            for (const album of albumData) {
                const row = $("<tr />");
                const albumID = album["AlbumId"];
                row.
                    append($("<td />", { "text": album["AlbumId"]})).
                    append($("<td />", { "text": album["Title"]})).
                    append($("<td />", { "text": album["ArtistId"]})).
                    append($("<td />", { "class": "table-actions", "html": 
                        "<button data-id='" + albumID + "' type='button' class='btn btn-danger btnDelete deleteAlbum'>" +
                            "<img src='../img/trash.svg' class='icon-delete'>" +
                        "</button>" +
                        "<button data-id='" + albumID + "' type='button' class='btn btn-warning btnUpdate updateAlbumModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/pencil-square.svg' class='icon-update'>" +
                        "</button>" +
                        "<button data-id='" + albumID + "' type='button' class='btn btn-success btnShow showAlbumModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/card-text.svg' class='icon-show'>" +
                        "</button>"
                    }))
                tableBody.append(row);
            }
        } else { // Displays Albums information for Customer
            for (const album of albumData) {
                const row = $("<tr />");
                const albumID = album["AlbumId"];
                row.
                    append($("<td />", { "text": album["AlbumId"]})).
                    append($("<td />", { "text": album["Title"]})).
                    append($("<td />", { "text": album["ArtistId"]})).
                    append($("<td />", { "class": "table-actions", "html": 
                        "<button data-id='" + albumID + "' type='button' class='btn btn-success btnShow showAlbumModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/card-text.svg' class='icon-show'>" +
                        "</button>"
                    }))
                tableBody.append(row);
            }
        }
        table.append(tableBody);
        table.appendTo($("section#results"));
    }
}

// Loads Tracks information in trackResults
function displayTracks(trackData, isAdmin = 0, shoppingCart = null) {
    if (trackData.length === 0) {
        $("section#results").html("There are no tracks matching the entered text.");
    } else {
        $("section#results").empty();
        const table = $("<table />", {"class": "table tableList"});
        const header = $("<thead />");
        const headerRow = $("<tr />");
        headerRow.
            append($("<th />", { "text": "TrackId"})).
            append($("<th />", { "text": "Name", "class": "number"})).
            append($("<th />", { "text": "AlbumId", "class": "number"})).
            append($("<th />", { "text": "Actions", "class": "number"}))
        header.append(headerRow);
        table.append(header);
        const tableBody = $("<tbody />");
        if (isAdmin == 1) { // Display tracks for Admin
            for (const track of trackData) {
                const row = $("<tr />");
                const trackID = track["TrackId"];
                row.
                    append($("<td />", { "text": track["TrackId"]})).
                    append($("<td />", { "text": track["Name"]})).
                    append($("<td />", { "text": track["AlbumId"]})).
                    append($("<td />", { "class": "table-actions", "html": 
                        "<button data-id='" + trackID + "' type='button' class='btn btn-danger btnDelete deleteTrack'>" +
                            "<img src='../img/trash.svg' class='icon-delete'>" +
                        "</button>" +
                        "<button data-id='" + trackID + "' type='button' class='btn btn-warning btnUpdate updateTrackModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/pencil-square.svg' class='icon-update'>" +
                        "</button>" +
                        "<button data-id='" + trackID + "' type='button' class='btn btn-success btnShow showTrackModal' data-toggle='modal' data-target='#modal'>" +
                            "<img src='../img/card-text.svg' class='icon-show'>" +
                        "</button>"
                    }))
                tableBody.append(row);
            }
        } else { // Display tracks for Customers
            if (shoppingCart) { // Display tracks for Customers in Shoppping Cart Page
                // Get only the unique Track ids, eliminate the duplicates
                var uniqueTracks = [];
                $.each(shoppingCart, function(i, el){
                    if($.inArray(el, uniqueTracks) === -1) uniqueTracks.push(el);
                });
                for (var i = 0; i <= uniqueTracks.length; i ++) {
                    for (const track of trackData) {
                        if (uniqueTracks[i] == track["TrackId"]) {
                            const row = $("<tr />");
                            const trackID = track["TrackId"];
                            row.
                                append($("<td />", { "text": track["TrackId"]})).
                                append($("<td />", { "text": track["Name"]})).
                                append($("<td />", { "text": track["AlbumId"]})).
                                append($("<td />", { "class": "table-actions", "html": 
                                    `<span>
                                        <form class='frmRemoveFromCart' action='../user/shopping-cart.php' method='POST'>
                                            <div class="form-group">
                                                <input type='hidden' name='removeFromCart' value='removeFromCart'>
                                                <input type='hidden' name='trackId' value='` + trackID + `' class="form-control" >
                                                <button data-id="` + trackID + `" type='submit' class='btn btn-danger btnDelete'>Remove</button>
                                            </div>
                                        </form>
                                        <button data-id='` + trackID + `' type='button' class='btn btn-success btnShow showTrackModal' data-toggle='modal' data-target='#modal'>
                                            <img src='../img/card-text.svg' class='icon-show'>
                                        </button>
                                    </span>`
                                }))
                            tableBody.append(row);
                        }
                    }
                }
            } else { // Display tracks for Customers in Library Tracks Page
                for (const track of trackData) {
                    const row = $("<tr />");
                    const trackID = track["TrackId"];
                    row.
                        append($("<td />", { "text": track["TrackId"]})).
                        append($("<td />", { "text": track["Name"]})).
                        append($("<td />", { "text": track["AlbumId"]})).
                        append($("<td />", { "class": "table-actions", "html": 
                            `<span>
                                <form class='frmAddToCart' action='../user/library-tracks.php' method='POST'>
                                    <div class="form-group">
                                        <input type='hidden' name='addToCart' value='addToCart'>
                                        <input type='hidden' name='addToCart2' value='` + trackID + `' class="form-control" > 
                                        <button data-id="` + trackID + `" type='submit' class='btn btn-warning btnAddToCart'>Add</button>
                                    </div>
                                </form>
                                <button data-id='` + trackID + `' type='button' class='btn btn-success btnShow showTrackModal' data-toggle='modal' data-target='#modal'>
                                    <img src='../img/card-text.svg' class='icon-show'>
                                </button>
                            </span>`
                        }))
                    tableBody.append(row);
                }
            }
        }
        table.append(tableBody);
        table.appendTo($("section#results"));
    }
}

// Artist Modal - show, create, update, delete
function showModal(action, itemId = 0, data = []) {
    // Empty the previous Results
    $("#modalInfoContent1").empty();
    $("#modalInfoContent2").empty();

    const elem = $("<div />");
    switch (action) {

        // ******************************************************
        // ***                ARTIST Functionality            ***
        // ******************************************************

        case 'showArtist': 
            $("#modalTitle").html("Artist Details");           
            elem.append($("<div />", { "class": "", "html": 
                `<p>
                    <span class="tag">Id</span>
                    <span class="tag-info"> ` + data["ArtistId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Name</span>
                    <span class="tag-info"> ` + data["Name"]+ ` </span>
                </p>`
                }))
            break;
        case 'createArtist': 
            $("#modalTitle").html("Create Artist");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmCreateArtist" method="POST">
                    <div class="form-group">
                        <label for="createArtistName" id="txtArtistLabel">Artist Name</label>
                        <input type="text" id="createArtistName" name="text" class="form-control" required>
                        <button type="button" class="btn btn-success mb-2 createArtist" id="btnCreateArtist" data-dismiss="modal">Create Artist</button>
                    </div>
                </form>`
            }))
            break;
        case 'updateArtist':
            $("#modalTitle").html("Update Artist");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmUpdateArtist" method="POST">
                    <div class="form-group">
                        <label for="updateArtistName" id="txtArtistLabel">New Name</label>
                        <input data-id= ` + itemId + ` type="text" id="updateArtistName" name="text" value="` + data['Name'] + `" class="form-control" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateArtist" id="btnUpdateArtist" data-dismiss="modal">Update Artist</button>
                    </div>
                </form>`
            }))
            break;

        // ******************************************************
        // ***                ALBUM Functionality             ***
        // ******************************************************

        case 'showAlbum': 
            $("#modalTitle").html("Album Details");           
            elem.append($("<div />", { "class": "", "html": 
                `<p>
                    <span class="tag">Album Id</span>
                    <span class="tag-info"> ` + data["AlbumId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Album Name</span>
                    <span class="tag-info"> ` + data["Title"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Artist Id</span>
                    <span class="tag-info"> ` + data["ArtistId"]+ ` </span>
                </p>`
                }))
            break;
        case 'createAlbum': 
            $("#modalTitle").html("Create Album");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmCreateArtist" method="POST">
                    <div class="form-group">
                        <label for="createAlbumTitle">Album Title</label>
                        <input type="text" id="createAlbumTitle" name="text" class="form-control" required>
                        </br>
                        <label for="createArtistId">Artist Id</label>
                        <input type="text" id="createArtistId" name="text" class="form-control" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 createAlbum" id="btnCreateAlbum" data-dismiss="modal">Create Album</button>
                    </div>
                </form>`
            }))
            break;
        case 'updateAlbum':
            $("#modalTitle").html("Update Album");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmUpdateAlbum" method="POST">
                    <div class="form-group">
                        <label for="updateAlbumId">Album Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateAlbumId" name="text" value="` + data['AlbumId'] + `" class="form-control" disabled>
                        </br>
                        <label for="updateAlbumTitle">Album Title</label>
                        <input data-id= ` + itemId + ` type="text" id="updateAlbumTitle" name="text" value="` + data['Title'] + `" class="form-control" required>
                        </br>
                        <label for="updateArtisId">Artist Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateArtisId" name="text" value="` + data['ArtistId'] + `" class="form-control" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateAlbum" id="btnUpdateAlbum" data-dismiss="modal">Update Album</button>
                    </div>
                </form>`
            }))
            break;
        
        // ******************************************************
        // ***                TRACK Functionality             ***
        // ******************************************************

        case 'showTrack': 
            $("#modalTitle").html("Track Details");           
            elem.append($("<div />", { "class": "", "html": 
                `<p>
                    <span class="tag">Track Id</span>
                    <span class="tag-info"> ` + data["TrackId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Track Name</span>
                    <span class="tag-info"> ` + data["Name"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Album Id</span>
                    <span class="tag-info"> ` + data["AlbumId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">MediaType Id</span>
                    <span class="tag-info"> ` + data["MediaTypeId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Genre Id</span>
                    <span class="tag-info"> ` + data["GenreId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Composer</span>
                    <span class="tag-info"> ` + data["Composer"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Milliseconds</span>
                    <span class="tag-info"> ` + data["Milliseconds"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Bytes</span>
                    <span class="tag-info"> ` + data["Bytes"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Unit Price</span>
                    <span class="tag-info"> ` + data["UnitPrice"]+ ` </span>
                </p>`
                }))
            break;
        case 'createTrack': 
            $("#modalTitle").html("Create Track");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmCreateArtist" method="POST">
                    <div class="form-group">
                        <label for="createTrackName">Track Name</label>
                        <input type="text" id="createTrackName" name="text" class="form-control" required>
                        </br>
                        <label for="createAlbumId">Album Id</label>
                        <input type="text" id="createAlbumId" name="text" class="form-control" required>
                        </br>
                        <label for="createMediaTypeId">MediaTypeId</label>
                        <input type="text" id="createMediaTypeId" name="text" class="form-control" required>
                        </br>
                        <label for="createGenreId">GenreId</label>
                        <input type="text" id="createGenreId" name="text" class="form-control" required>
                        </br>
                        <label for="createComposer">Composer</label>
                        <input type="text" id="createComposer" name="text" class="form-control" required>
                        </br>
                        <label for="createMilliseconds">Milliseconds</label>
                        <input type="text" id="createMilliseconds" name="text" class="form-control" required>
                        </br>
                        <label for="createBytes">Bytes</label>
                        <input type="text" id="createBytes" name="text" class="form-control" required>
                        </br>
                        <label for="createUnitPrice">UnitPrice</label>
                        <input type="text" id="createUnitPrice" name="text" class="form-control" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 createTrack" id="btnCreateTrack" data-dismiss="modal">Create Album</button>
                    </div>
                </form>`
            }))
            break;
        case 'updateTrack':
            $("#modalTitle").html("Update Track");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmUpdateTrack" method="POST">
                    <div class="form-group">
                        <label for="updateTrackId">Track Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateTrackId" name="text" value="` + data['AlbumId'] + `" class="form-control" disabled>
                        </br>
                        <label for="updateTrackName">Track Name</label>
                        <input data-id= ` + itemId + ` type="text" id="updateTrackName" name="text" value="` + data['Name'] + `" class="form-control" required>
                        </br>
                        <label for="updateAlbumId">Album Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateAlbumId" name="text" value="` + data['AlbumId'] + `" class="form-control" required>
                        </br>

                        <label for="updateMediaTypeId">Media Type</label>
                        <input data-id= ` + itemId + ` type="text" id="updateMediaTypeId" name="text" value="` + data['MediaTypeId'] + `" class="form-control" required>
                        </br>
                        <label for="updateGenreId">Genre Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateGenreId" name="text" value="` + data['GenreId'] + `" class="form-control" required>
                        </br>
                        <label for="updateComposer">Composer</label>
                        <input data-id= ` + itemId + ` type="text" id="updateComposer" name="text" value="` + data['Composer'] + `" class="form-control" required>
                        </br>

                        <label for="updateMilliseconds">Milliseconds</label>
                        <input data-id= ` + itemId + ` type="text" id="updateMilliseconds" name="text" value="` + data['Milliseconds'] + `" class="form-control" required>
                        </br>
                        <label for="updateBytes">Bytes</label>
                        <input data-id= ` + itemId + ` type="text" id="updateBytes" name="text" value="` + data['Bytes'] + `" class="form-control" required>
                        </br>
                        <label for="updateUnitPrice">UnitPrice</label>
                        <input data-id= ` + itemId + ` type="text" id="updateUnitPrice" name="text" value="` + data['UnitPrice'] + `" class="form-control" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateTrack" id="btnUpdateTrack" data-dismiss="modal">Update Artist</button>
                    </div>
                </form>`
            }))
            break;

        // ******************************************************
        // ***              CUSTOMER Functionality            ***
        // ******************************************************

        case 'createCustomer': 
            $("#modalTitle").html("Sign Up");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmCreateCustomer" method="POST">
                    <div class="form-group">
                        <label for="firstName" class="col-form-label">First Name</label>
                        <input type="text" id="firstName" name="text" class="form-control" required>
                        </br>
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="text" class="form-control" required>
                        </br>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="text" class="form-control" required>
                        </br>
                        <label for="company">Company</label>
                        <input type="text" id="company" name="text" class="form-control" required>
                        </br>

                        <label for="address">Address</label>
                        <input type="text" id="address" name="text" class="form-control" required>
                        </br>
                        <label for="city">City</label>
                        <input type="text" id="city" name="text" class="form-control" required>
                        </br>
                        <label for="state">State</label>
                        <input type="text" id="state" name="text" class="form-control" required>
                        </br>
                        <label for="country">Country</label>
                        <input type="text" id="country" name="text" class="form-control" required>
                        </br>

                        <label for="postalCode">Postal Code</label>
                        <input type="text" id="postalCode" name="text" class="form-control" required>
                        </br>
                        <label for="fax">Fax</label>
                        <input type="text" id="fax" name="text" class="form-control" required>
                        </br>
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="text" class="form-control" required>
                        </br>
                        <label for="email">Email</label>
                        <input type="text" id="email" name="text" class="form-control" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 createCustomer" id="btnCreateCustomer" data-dismiss="modal">Create Customer</button>
                    </div>
                </form>`
            }))
            break;
        case 'updateCustomer':
            $("#modalTitle").html("Update Customer");           
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmCreateCustomer" method="POST">
                    <div class="form-group">
                        <label for="customerId">Customer Id</label>
                        <input type="text" id="customerId" name="text" value="` + data['CustomerId'] + `" class="form-control" disabled>
                        </br>
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="text" value="` + data['FirstName'] + `" class="form-control" required>
                        </br>
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="text" value="` + data['LastName'] + `" class="form-control" required>
                        </br>

                        <label for="company">Company</label>
                        <input type="text" id="company" name="text" value="` + data['Company'] + `" class="form-control" required>
                        </br>

                        <label for="address">Address</label>
                        <input type="text" id="address" name="text" value="` + data['Address'] + `" class="form-control" required>
                        </br>
                        <label for="city">City</label>
                        <input type="text" id="city" name="text" value="` + data['City'] + `" class="form-control" required>
                        </br>
                        <label for="state">State</label>
                        <input type="text" id="state" name="text" value="` + data['State'] + `" class="form-control" required>
                        </br>
                        <label for="country">Country</label>
                        <input type="text" id="country" name="text" value="` + data['Country'] + `" class="form-control" required>
                        </br>

                        <label for="postalCode">Postal Code</label>
                        <input type="text" id="postalCode" name="text" value="` + data['PostalCode'] + `" class="form-control" required>
                        </br>
                        <label for="fax">Fax</label>
                        <input type="text" id="fax" name="text" value="` + data['Fax'] + `" class="form-control" required>
                        </br>
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="text" value="` + data['Phone'] + `" class="form-control" required>
                        </br>
                        <label for="email">Email</label>
                        <input type="text" id="email" name="text" value="` + data['Email'] + `" class="form-control" required>
                        </br>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="text" value="" class="form-control" >
                        </br>
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="text" value="" class="form-control" >
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateCustomer" id="btnUpdateCustomer" data-dismiss="modal">Update Customer</button>
                    </div>
                </form>`
            }))
            break;
    
        // ******************************************************
        // ***              PURCHASE Functionality            ***
        // ******************************************************

        case 'showPurchase':
            $("#modalTitle").html("Purchase Details");    
            elem.append($("<div />", { "class": "", "html": 
                `<form id="frmCreateArtist" method="POST">
                    <div class="form-group">
                        <input type='hidden' name='reset' value='reset'>
                        <label for="customerId">Customer Id</label>
                        <input type="text" id="customerId" name="text" value="` + data['CustomerId'] + `"  class="form-control" disabled>
                        </br>
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="text" value="` + data['FirstName'] + `"  class="form-control" disabled>
                        </br>
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="text" value="` + data['LastName'] + `"  class="form-control" disabled>
                        </br>
                        <label for="email">Email</label>
                        <input type="text" id="email" name="text" value="` + data['Email'] + `"  class="form-control" disabled>
                        </br>
                        <label for="billingAddress">Billing Address</label>
                        <input type="text" id="billingAddress" name="text" value="` + data['Address'] + `"  class="form-control" required>
                        </br>
                        <button type="submit" class="btn btn-success mb-2 confirmPurchase" id="btnConfirmPurchase" data-dismiss="modal">Confirm Purchase</button>
                    </div>
                </form>`
            }))
            break;
        default:
            elem.append($("<div />", { "class": "", "html": 
                    `<p>There was an error loading the content!</p>`
                }))
            return;
    }
    $("#modalInfoContent1").append(elem);
}

// Show Login for User
$(document).on("click", "#loginUser", function(e) {
    document.getElementById("txtEmail").disabled = false;
    document.getElementById("txtEmail").style.display = "inline-block";
    document.getElementById("txtEmailLabel").style.display = "inline-block";
});

// Show Login for Admin
$(document).on("click", "#loginAdmin", function(e) {
    document.getElementById("txtEmail").disabled = true;
    document.getElementById("txtEmail").style.display = "none";
    document.getElementById("txtEmailLabel").style.display = "none";
});

}