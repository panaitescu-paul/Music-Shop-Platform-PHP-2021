<?php

/**
 * REST API
 * Refer to API documentation.md for API documentation
 * 
 * @author  Paul Panaitescu
 * @version 1.0 1 DEC 2020
 */

    require_once('functions.php');
    debug($_POST);

    define('ENTITY', 2);
    define('ID', 3);
    define('MAX_PIECES', 4);

    // Example of 2 pieces: http://localhost/WAD-MA2
    // Example of 3 pieces: http://localhost/WAD-MA2/artists
    // Example of 4 pieces: http://localhost/WAD-MA2/artists/1

    $url = strtok($_SERVER['REQUEST_URI'], "?");    // GET parameters are removed
    // If there is a trailing slash, it is removed, so that it is not taken into account by the explode function
    if (substr($url, strlen($url) - 1) == '/') {
        $url = substr($url, 0, strlen($url) - 1);
    }
    $urlPieces = explode('/', urldecode($url));

    header('Content-Type: application/json');
    header('Accept-version: v1');

    $pieces = count($urlPieces);

    // echo $pieces;
    // echo "Entities: " . $pieces . "\n";

    if ($pieces == 2) {
        echo APIDescription();
    } else {
        if ($pieces > 4) {
            echo formatError();
        } else {

            $entity = $urlPieces[ENTITY];
            switch ($entity) {
                case 'artists':
                    require_once('artist2.php');
                    $artist = new Artist();
                    $verb = $_SERVER['REQUEST_METHOD'];

                    switch ($verb) {
                        case 'GET':                             
                            // If the number of pieces is smaller than the maximum (4), 
                            // then get all artists, or search by name, otherwise search by id
                            if ($pieces < MAX_PIECES) {
                                if (!isset($_GET['name'])) {
                                    // Get All Artists
                                    echo json_encode($artist->getAll());
                                } else {
                                    // Search Artist by name
                                    echo json_encode($artist->search($_GET['name']));
                                }
                            } else {
                                // Get Artist by ID
                                echo json_encode($artist->get($urlPieces[ID]));
                            }
                            break;
                        case 'POST':                            
                            if (!isset($_POST['name'])) {
                                echo formatError();
                            } else if ($pieces < MAX_PIECES){
                                // Create artist
                                echo json_encode($artist->create($_POST['name']));
                            } else {
                                // Update artist
                                echo json_encode($artist->update($urlPieces[ID], $_POST['name']));
                            }                    
                            break;
                        case 'DELETE':
                            if ($pieces < MAX_PIECES) {
                                echo formatError();
                            } else {
                                // Delete artist
                                echo json_encode($artist->delete($urlPieces[ID]));
                            }
                            break;                     
                    }
                    $artist = null;
                    break;  
                case 'albums':
                    require_once('album2.php');
                    $album = new Album();
                    $verb = $_SERVER['REQUEST_METHOD'];
                    
                    switch ($verb) {
                        case 'GET':                             
                            // If the number of pieces is smaller than the maximum (4), 
                            // then get all Albums, or search by title, otherwise search by id
                            if ($pieces < MAX_PIECES) {
                                if (!isset($_GET['title'])) {
                                    // Get All Albums
                                    echo json_encode($album->getAll());
                                } else {
                                    // Search Album by title
                                    echo json_encode($album->search($_GET['title']));
                                }
                            } else {
                                // Get Album by ID
                                echo json_encode($album->get($urlPieces[ID]));
                            }
                            break;
                        case 'POST':                            
                            if (!isset($_POST['title'])) {
                                echo formatError();
                            } else if ($pieces < MAX_PIECES){
                                // Create Album
                                echo json_encode($album->create($_POST['artistId'], $_POST['title']));
                            } else {
                                // Update Album
                                echo json_encode($album->update($urlPieces[ID], $_POST['title'], $_POST['artistId']));
                            }                    
                            break;
                        case 'DELETE':
                            if ($pieces < MAX_PIECES) {
                                echo formatError();
                            } else {
                                // Delete Album
                                echo json_encode($album->delete($urlPieces[ID]));
                            }
                            break;                     
                    }
                    $album = null;
                    break;  
                // case 'films':
                //     require_once('src/movie.php');
                //     $movie = new Movie();

                //     $verb = $_SERVER['REQUEST_METHOD'];

                //     switch ($verb) {
                //         case 'GET':
                //             if ($pieces < MAX_PIECES) {                  // Search films
                //                 if (!isset($_GET['title'])) {
                //                     echo formatError();
                //                 } else {
                //                     echo json_encode($movie->search($_GET['title']));
                //                 }
                //             } else {                                    // Get film by ID
                //                 echo json_encode($movie->get($urlPieces[ID]));
                //             }
                //             break;
                //         case 'POST':                                    // Add new film
                //             if (!isset($_POST['title'])) {
                //                 echo formatError();
                //             } else {
                //                 echo json_encode($movie->add($_POST));
                //             }
                //             break;
                //         case 'PUT':                                     // Update film
                //             // Since PHP does not handle PUT parameters explicitly,
                //             // they must be read from the request body's raw data
                //             $movieData = (array) json_decode(file_get_contents('php://input'), TRUE);
                    
                //             if ($pieces < MAX_PIECES || !isset($movieData['title'])) {
                //                 echo formatError();
                //             } else {
                //                 echo json_decode($movie->update($urlPieces[ID], $movieData));
                //             }
                //             break;
                //         case 'DELETE':                                  // Delete film
                //             if ($pieces < MAX_PIECES) {
                //                 echo formatError();
                //             } else {
                //                 echo json_decode($movie->delete($urlPieces[ID]));
                //             }
                //             break;
                //     }
                //     $movie = null;
                //     break; 
                default:
                    echo formatError();
            }
        }
    }

    // session_start();
    // // if (!isset($_SESSION['userID'])) {
    // if (false) {
    //     debug('Session variable userID not set.<br>');
    //     leave('User not authenticated.');
    // } else {
    //     if (!isset($_POST['entity']) || !isset($_POST['action'])) {
    //         leave();
    //     } else {
    //         $entity = $_POST['entity'];
    //         $action = $_POST['action'];

    //         switch ($entity) {
    //             case 'artist':
    //                 require_once('artist.php');
    //                 $artist = new Artist;
    //                 switch ($action) {
    //                     case 'getAll':
    //                         echo json_encode($artist->getAll());
    //                         break;
    //                     case 'get':
    //                         if (!isset($_POST['id'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($artist->get($_POST['id']));
    //                         }
    //                         break;
    //                     case 'search':
    //                         if (!isset($_POST['searchText'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($artist->search($_POST['searchText']));
    //                         }
    //                         break;
    //                     case 'create':
    //                         if (!isset($_POST['info'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($artist->create($_POST['info']));
    //                         }
    //                         break;
    //                     case 'update':
    //                         if (!isset($_POST['info'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($artist->update($_POST['info']));
    //                         }
    //                         break;
    //                     case 'delete':
    //                         if (!isset($_POST['id'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($artist->delete($_POST['id']));
    //                         }
    //                         break;
    //                 }
    //                 break;
    //             case 'album':
    //                 require_once('album.php');
    //                 $album = new Album;
    //                 switch ($action) {
    //                     case 'getAll':
    //                         echo json_encode($album->getAll());
    //                         break;
    //                     case 'get':
    //                         if (!isset($_POST['id'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($album->get($_POST['id']));
    //                         }
    //                         break;
    //                     case 'search':
    //                         if (!isset($_POST['searchText'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($album->search($_POST['searchText']));
    //                         }
    //                         break;
    //                     case 'create':
    //                         if (!isset($_POST['info'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($album->create($_POST['info']));
    //                         }
    //                         break;
    //                     case 'update':
    //                         if (!isset($_POST['info'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($album->update($_POST['info']));
    //                         }
    //                         break;
    //                     case 'delete':
    //                         if (!isset($_POST['id'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($album->delete($_POST['id']));
    //                         }
    //                         break;
    //                 }
    //                 break;
    //             case 'track':
    //                 require_once('track.php');
    //                 $track = new Track;
    //                 switch ($action) {
    //                     case 'getAll':
    //                         echo json_encode($track->getAll());
    //                         break;
    //                     case 'get':
    //                         if (!isset($_POST['id'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($track->get($_POST['id']));
    //                         }
    //                         break;
    //                     case 'search':
    //                         if (!isset($_POST['searchText'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($track->search($_POST['searchText']));
    //                         }
    //                         break;
    //                     case 'create':
    //                         if (!isset($_POST['info'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($track->create($_POST['info']));
    //                         }
    //                         break;
    //                     case 'update':
    //                         if (!isset($_POST['info'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($track->update($_POST['info']));
    //                         }
    //                         break;
    //                     case 'delete':
    //                         if (!isset($_POST['id'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($track->delete($_POST['id']));
    //                         }
    //                         break;
    //                 }
    //                 break;
    //             case 'user':
    //                 require_once('user.php');
    //                 $user = new User;

    //                 switch ($action) {
    //                     case 'update':
    //                         if (!isset($_POST['email'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($user->update($_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['newPassword']));
    //                         }
    //                         break;
    //                 }
    //                 break;
    //             case 'admin':
    //                 require_once('admin.php');
    //                 $user = new Admin;

    //                 switch ($action) {
    //                     case 'update':
    //                         if (!isset($_POST['email'])) {
    //                             leave();
    //                         } else {
    //                             echo json_encode($user->update($_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['newPassword']));
    //                         }
    //                         break;
    //                 }
    //                 break;
    //             case 'session':
    //                 switch ($action) {
    //                     case 'destroy':
    //                         session_destroy();
    //                         leave('Session destroyed');
    //                         break;
    //                 }
    //         }
    //     }
    // }
?>
