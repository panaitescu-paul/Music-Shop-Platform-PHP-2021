/* eslint-disable no-undef */
/**
 * JavaScript display functionality
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

// Artist Modal - show, create, update, delete
function showModal(action, artistId = 0, artistData = []) {
    // Empty the previous Results
    $("#modalInfoContent1").empty();
    $("#modalInfoContent2").empty();

    const elem = $("<div />");
    switch (action) {
        case 'showArtist': 
            $("#modalTitle").html("Artist Details");           
            elem.append($("<div />", { "class": "", "html": 
                `<p>
                    <span class="tag">Id</span>
                    <span class="tag-info"> ` + artistData["ArtistId"]+ ` </span>
                </p>
                <p>
                    <span class="tag">Name</span>
                    <span class="tag-info"> ` + artistData["Name"]+ ` </span>
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
                        <input data-id= ` + artistId + ` type="text" id="updateArtistName" name="text" required>
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
