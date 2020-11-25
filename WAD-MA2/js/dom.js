// /* eslint-disable no-undef */
// /**
//  * DOM interaction
//  *
//  * @author  Paul Panaitescu
//  * @version 1.0 25 NOV 2020
//  */
// "use strict";
// $(document).ready(function() {
//
//     // Pressing the Escape key closes the current modal
//     $(document).on("keyup", function(e) {
//         e.preventDefault();
//
//         if (e.originalEvent.key === "Escape") {
//             if ($("#btnMessageCancel").is(":visible")) {
//                 $("#btnMessageCancel").click();
//             } else if ($("#btnPersonCancel").is(":visible")) {
//                 $("#btnPersonCancel").click();
//             } else if ($("#btnFilmCancel").is(":visible")) {
//                 $("#btnFilmCancel").click();
//             }
//         }
//     })
//
//     // New movie
//     $("button#btnNewMovie").on("click", function(e) {
//         e.preventDefault();
//         showMovieModal('add');
//     });
//
//     $("img#btnAssignDirector").on("click", function() { showPersonModal('director'); });    // Assign director
//     $("img#btnAssignActor").on("click", function() { showPersonModal('actor'); });          // Assign actor
//
//     // Deassign a director or actor
//     $("img#btnDeassignDirector, img#btnDeassignActor").on("click", function() {
//         let personType = $(this)[0].id.substring(11);  // Director or Actor
//         const personID = $("#lst" + personType + " > option:selected").val();
//
//         if (personID === undefined) {
//             personType = personType.charAt(0).toLowerCase() + personType.substring(1);
//             alert("Please select a " + personType + " to deassign");
//             return;
//         }
//         $("#lst" + personType + " > option:selected").remove();
//     });
//
//     $("button#btnFilmCancel").on("click", function() { hideModal("film"); });       // Cancel movie addition/edition
//     $("button#btnPersonCancel").on("click", function() { hideModal("person"); });   // Cancel persondisplay
//     $("button#btnMessageCancel").on("click", function() { hideModal("message"); }); // Cancel message display
//
//     // Assign a person to a movie
//     $("button#btnPersonOk").on("click", function() {
//         const person = $("#lstPerson > option:selected");
//         const personType = $("#headerPerson").text().substring(4).trim();   // Either "New director" or "New actor"
//
//         if (person.val() === undefined) {
//             alert("Please select a " + personType + " to assign");
//             return;
//         }
//         const personTypeCapitalise = personType.charAt(0).toUpperCase() + personType.substring(1);
//
//         // Check whether the person has already been assigned in this capacity (director or actor)
//         if ($("#lst" + personTypeCapitalise + " > option[value=" + person.val() + "]").val() === person.val()) {
//             alert("This " + personType + " has already been assigned to this film");
//             return;
//         }
//
//         $("#lst" + personTypeCapitalise).append($("<option />", { "value": person.val(), "text": person.text() }));
//         hideModal('person');
//     });
//     $("#lstPerson").on("dblclick", function() { $("button#btnPersonOk").click() });
// });
