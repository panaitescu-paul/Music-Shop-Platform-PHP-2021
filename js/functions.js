/* eslint-disable no-undef */
/**
 * JavaScript display functionality
 *
 * @author  Paul Panaitescu
 * @version 1.0 25 NOV 2020
 */
"use strict";

// // Creates a table where to display the movie data it receives as a parameter
// function displayMovies(movieData) {
//     if (movieData.length === 0) {
//         $("section#filmResults").html("There are no films matching the entered text.");
//     } else {
//         $("section#filmResults").empty();
//
//         const table = $("<table />");
//         const header = $("<thead />");
//         const headerRow = $("<tr />");
//         headerRow.
//         append($("<th />", { "text": "Title"})).
//         append($("<th />", { "text": "Release date"})).
//         append($("<th />", { "text": "Running time", "class": "number"})).
//         append($("<th />", { "class": "action"})).
//         append($("<th />", { "class": "action"})).
//         append($("<th />", { "class": "action"}))
//         header.append(headerRow);
//         table.append(header);
//
//         const tableBody = $("<tbody />");
//         for (const movie of movieData) {
//             const row = $("<tr />");
//             const movieID = movie["movie_id"];
//             row.
//             append($("<td />", { "text": movie["title"]})).
//             append($("<td />", { "text": movie["release_date"]})).
//             append($("<td />", { "text": movie["runtime"], "class": "number"})).
//             append($("<td />", { "html": "<img data-id='" + movieID + "' class='smallButton showMovie' src='img/show.png'>", "class": "action"})).
//             append($("<td />", { "html": "<img data-id='" + movieID + "' class='smallButton editMovie' src='img/edit.png'>", "class": "action"})).
//             append($("<td />", { "html": "<img data-id='" + movieID + "' class='smallButton deleteMovie' src='img/delete.png'>", "class": "action"}))
//             tableBody.append(row);
//         }
//         table.append(tableBody);
//
//         table.appendTo($("section#filmResults"));
//     }
// }
//
// // Loads person information in the person listBox
// function displayPersons(personData) {
//     $("lstPerson").empty();
//
//     const list = $("<select />");
//     for (const person of personData) {
//         list.append($("<option />", { "value": person["person_id"], "text": person["person_name"] }));
//     }
//     $("#lstPerson").html(list.html());
// }
//
// function enableMovieModal() {
//     $("div#modalFilm input, textarea, select").removeAttr("readonly");
//     $("img#btnAssignDirector, img#btnDeassignDirector").show();
//     $("img#btnAssignActor, img#btnDeassignActor").show();
//     $("button#btnFilmOk").show();
//     $("button#btnFilmCancel").text("Cancel");
// }
//
// // Shows the movie modal
// function showMovieModal(action, id = 0) {
//
//     hideBelowContent('film');
//     $("div#modalFilm").attr("data-id", id);
//
//     switch (action) {
//         case 'show':
//             $("h3#headerFilm").text("Film information");
//             $("div#modalFilm input, textarea, select").attr("readonly", "true");
//             $("img#btnAssignDirector, img#btnDeassignDirector").hide();
//             $("img#btnAssignActor, img#btnDeassignActor").hide();
//             $("button#btnFilmOk").hide();
//             $("button#btnFilmCancel").text("Ok");
//             break;
//         case 'add':
//             enableMovieModal();
//             $("h3#headerFilm").text("New film");
//             $("button#btnFilmOk").text("Add film");
//             break;
//         case 'edit':
//             enableMovieModal();
//             $("h3#headerFilm").text("Edit film");
//             $("button#btnFilmOk").text("Ok");
//             break;
//         default:
//             showBelowContent('film');
//             return;
//     }
//     $("div#modalFilm").show();
// }
//
// // Shows the person modal (director or actor)
// function showPersonModal(personType) {
//
//     hideBelowContent('person');
//
//     if (personType === 'director') {
//         $("h3#headerPerson").text("New director");
//         $("input#txtPerson").siblings("label").text("Search director");
//         $("select#lstPerson").siblings("label").text("Directors");
//         $("button#btnPersonOk").text("Assign director");
//     } else {
//         $("h3#headerPerson").text("New actor");
//         $("input#txtPerson").siblings("label").text("Search actor");
//         $("select#lstPerson").siblings("label").text("Actors");
//         $("button#btnPersonOk").text("Assign actor");
//     }
//
//     $("div#modalPerson").show();
// }
//
// // Shows a message for a specific entity, action, and operation status
// function showMessage(entity, action, success) {
//
//     showBelowContent('message');
//
//     let message;
//     if (success) {
//         if (action === "add") {
//             action += "e";
//         }
//         message = "The " + entity + " was " + action + "d successfully";
//     } else {
//         message = "There was an error while trying to " + action + " the " + entity;
//     }
//     $("div#modalMessage > main").text(message);
//     $("div#modalMessage").show();
// }
//
// // Hides the modal whose name it receives as a parameter
// function hideModal(modal) {
//     switch (modal) {
//         case "film":
//             $("div#modalFilm").hide();
//             $("#txtTitle").val("");
//             $("#txtOverview").val("");
//             $("#txtReleaseDate").val("");
//             $("#txtRuntime").val("");
//             $("#lstDirector").empty();
//             $("#lstActor").empty();
//             break;
//         case "person":
//             $("div#modalPerson").hide();
//             $("#txtPerson").val("");
//             $("#lstPerson").empty();
//             break;
//         case "message":
//             $("div#modalMessage").hide();
//             break;
//     }
//     showBelowContent(modal);
// }
//
// // It superposes a div on top of the page so that content cannot be interacted with while the modal is in display
// function hideBelowContent(modal) {
//
//     /*
//         For this scheme to work, it is necessary to establish different levels:
//         - Level 0:
//         --- Search page                 z-index: -1
//         - Level 1
//         --- Search page cover div       z-index: 0
//         --- Movie modal                 z-index: 1
//         - Level 2
//         --- Movie modal cover div       z-index: 2
//         --- Person or message modal     z-index: 3
//     */
//     let coverClass = "";
//     if (modal === "film") {
//         coverClass = "cover";
//     } else {
//         coverClass = "cover secondLevel";
//     }
//
//     const cover = $("<div />", { "id": modal, "class": coverClass});
//
//     $("body").append(cover);
// }
//
// // It removes the cover div
// function showBelowContent(modal) {
//     $("div#" + modal).remove();
// }

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
