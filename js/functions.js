// TODO: add function inside a select to get show the Id selections, genre selection, mediatype selection


/**
 * JavaScript DOM Manipulation
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

// Loads Artist information in artistResults
function displayArtists(artistData) {
    if (artistData.length === 0) {
        $("section#artistResults").html("There are no artists matching the entered text.");
    } else {
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
        table.append(tableBody);
        table.appendTo($("section#artistResults"));
    }
}

// Loads Albums information in artistResults
function displayAlbums(albumData) {
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
                                            <input type='hidden' name='removeFromCart' value='removeFromCart'>
                                            <input type='hidden' name='trackId' value='` + trackID + `'>
                                            <button data-id="` + trackID + `" type='submit' class='btn btn-danger btnDelete'>Remove</button>
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
                                    <input type='hidden' name='addToCart' value='addToCart'>
                                    <input type='hidden' name='addToCart2' value='` + trackID + `'> 
                                    <button data-id="` + trackID + `" type='submit' class='btn btn-warning btnAddToCart'>Add</button>
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
                        <label for="createArtistName" id="txtArtistLabel">Artist Name</label>
                        <input type="text" id="createArtistName" name="text" required>
                        <button type="button" class="btn btn-success mb-2 createArtist" id="btnCreateArtist" data-dismiss="modal">Create Artist</button>
                    </form>`
                }))
            break;
        case 'updateArtist':
            $("#modalTitle").html("Update Artist");           
            elem.append($("<div />", { "class": "", "html": 
                    `<form id="frmUpdateArtist" method="POST">
                        <label for="updateArtistName" id="txtArtistLabel">New Name</label>
                        <input data-id= ` + itemId + ` type="text" id="updateArtistName" name="text" value="` + data['Name'] + `" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateArtist" id="btnUpdateArtist" data-dismiss="modal">Update Artist</button>
                    </form>`
                }))
            break;
        case 'artistDeleteSuccess':
            $("#modalTitle").html("Delete Artist");           
            elem.append($("<div />", { "class": "", "html": 
                    `<p>Artist Successfuly deleted!</p>`
                }))
            break;
        case 'artistDeleteFailure':
            $("#modalTitle").html("Delete Artist");           
            elem.append($("<div />", { "class": "", "html": 
                    `<p>Artist not deleted!</p>`
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
                        <label for="createAlbumTitle" id="txtAlbumLabel">Album Title</label>
                        <input type="text" id="createAlbumTitle" name="text" required>
                        </br>
                        <label for="createArtistId" id="txtAlbumLabel">Artist Id</label>
                        <input type="text" id="createArtistId" name="text" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 createAlbum" id="btnCreateAlbum" data-dismiss="modal">Create Album</button>
                    </form>`
                }))
            break;
        case 'updateAlbum':
            $("#modalTitle").html("Update Album");           
            elem.append($("<div />", { "class": "", "html": 
                    `<form id="frmUpdateAlbum" method="POST">
                        <label for="updateAlbumId" id="txtAlbumLabel">Album Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateAlbumId" name="text" value="` + data['AlbumId'] + `" disabled>
                        </br>
                        <label for="updateAlbumTitle" id="txtAlbumLabel">Album Title</label>
                        <input data-id= ` + itemId + ` type="text" id="updateAlbumTitle" name="text" value="` + data['Title'] + `" required>
                        </br>
                        <label for="updateArtisId" id="txtAlbumLabel">Artist Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateArtisId" name="text" value="` + data['ArtistId'] + `" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateAlbum" id="btnUpdateAlbum" data-dismiss="modal">Update Album</button>
                    </form>`
                }))
            break;
        case 'albumDeleteSuccess':
            $("#modalTitle").html("Delete Album");           
            elem.append($("<div />", { "class": "", "html": 
                    `<p>Album Successfuly deleted!</p>`
                }))
            break;
        case 'albumDeleteFailure':
            $("#modalTitle").html("Delete Album");           
            elem.append($("<div />", { "class": "", "html": 
                    `<p>Album not deleted!</p>`
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
                        <label for="createTrackName" id="txtTrackLabel">Track Name</label>
                        <input type="text" id="createTrackName" name="text" required>
                        </br>
                        <label for="createAlbumId" id="txtTrackLabel">Album Id</label>
                        <input type="text" id="createAlbumId" name="text" required>
                        </br>
                        <label for="createMediaTypeId" id="txtTrackLabel">MediaTypeId</label>
                        <input type="text" id="createMediaTypeId" name="text" required>
                        </br>
                        <label for="createGenreId" id="txtTrackLabel">GenreId</label>
                        <input type="text" id="createGenreId" name="text" required>
                        </br>
                        <label for="createComposer" id="txtTrackLabel">Composer</label>
                        <input type="text" id="createComposer" name="text" required>
                        </br>
                        <label for="createMilliseconds" id="txtTrackLabel">Milliseconds</label>
                        <input type="text" id="createMilliseconds" name="text" required>
                        </br>
                        <label for="createBytes" id="txtTrackLabel">Bytes</label>
                        <input type="text" id="createBytes" name="text" required>
                        </br>
                        <label for="createUnitPrice" id="txtTrackLabel">UnitPrice</label>
                        <input type="text" id="createUnitPrice" name="text" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 createTrack" id="btnCreateTrack" data-dismiss="modal">Create Album</button>
                    </form>`
                }))
            break;
        case 'updateTrack':
            $("#modalTitle").html("Update Track");           
            elem.append($("<div />", { "class": "", "html": 
                    `<form id="frmUpdateTrack" method="POST">
                        <label for="updateTrackId" id="txtTrackLabel">Track Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateTrackId" name="text" value="` + data['AlbumId'] + `" disabled>
                        </br>
                        <label for="updateTrackName" id="txtTrackLabel">Track Name</label>
                        <input data-id= ` + itemId + ` type="text" id="updateTrackName" name="text" value="` + data['Name'] + `" required>
                        </br>
                        <label for="updateAlbumId" id="txtTrackLabel">Album Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateAlbumId" name="text" value="` + data['AlbumId'] + `" required>
                        </br>

                        <label for="updateMediaTypeId" id="txtTrackLabel">Media Type</label>
                        <input data-id= ` + itemId + ` type="text" id="updateMediaTypeId" name="text" value="` + data['MediaTypeId'] + `" required>
                        </br>
                        <label for="updateGenreId" id="txtTrackLabel">Genre Id</label>
                        <input data-id= ` + itemId + ` type="text" id="updateGenreId" name="text" value="` + data['GenreId'] + `" required>
                        </br>
                        <label for="updateComposer" id="txtTrackLabel">Composer</label>
                        <input data-id= ` + itemId + ` type="text" id="updateComposer" name="text" value="` + data['Composer'] + `" required>
                        </br>

                        <label for="updateMilliseconds" id="txtTrackLabel">Milliseconds</label>
                        <input data-id= ` + itemId + ` type="text" id="updateMilliseconds" name="text" value="` + data['Milliseconds'] + `" required>
                        </br>
                        <label for="updateBytes" id="txtTrackLabel">Bytes</label>
                        <input data-id= ` + itemId + ` type="text" id="updateBytes" name="text" value="` + data['Bytes'] + `" required>
                        </br>
                        <label for="updateUnitPrice" id="txtTrackLabel">UnitPrice</label>
                        <input data-id= ` + itemId + ` type="text" id="updateUnitPrice" name="text" value="` + data['UnitPrice'] + `" required>
                        </br>
                        <button type="button" class="btn btn-success mb-2 updateTrack" id="btnUpdateTrack" data-dismiss="modal">Update Artist</button>
                    </form>`
                }))
            break;
        case 'trackDeleteSuccess':
            $("#modalTitle").html("Delete Track");           
            elem.append($("<div />", { "class": "", "html": 
                    `<p>Track Successfuly deleted!</p>`
                }))
            break;
        case 'trackDeleteFailure':
            $("#modalTitle").html("Delete Track");           
            elem.append($("<div />", { "class": "", "html": 
                    `<p>Track not deleted!</p>`
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

// They show and hide the "loading" animated gif
function loadingStart() { $("#loading").show(); $("#filmResults").empty(); }
function loadingEnd() { $("#loading").hide(); }

function selectUserLogin() {
    console.log("loginUser clicked");
    document.getElementById("txtEmail").disabled = false;
    document.getElementById("txtEmail").style.display = "inline-block";
    document.getElementById("txtEmailLabel").style.display = "inline-block";

    // $('#loginUser').classList.add("active");
    // $('#loginAdmin').classList.remove("active");
}
function selectAdminLogin() {
    console.log("loginUser clicked");
    document.getElementById("txtEmail").disabled = true;
    document.getElementById("txtEmail").style.display = "none";
    document.getElementById("txtEmailLabel").style.display = "none";
    // $('#loginUser').classList.remove("active");
    // $('#loginAdmin').classList.add("active");
}
// $('#loginUser').click(function() {
//     console.log("loginUser clicked");
//     $('#loginUser').classList.add("active");
//     $('#loginAdmin').classList.remove("active");
// });
// $('#loginAdmin').click(function() {
//     console.log("loginUser clicked");
//     $('#loginUser').classList.remove("active");
//     $('#loginAdmin').classList.add("active");
// });
console.log("functions.js");
