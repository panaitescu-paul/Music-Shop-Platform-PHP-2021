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

    // Local version
    define('ENTITY', 2);
    define('ID', 3);
    define('MAX_PIECES', 4);

    // // AWS version
    // define('ENTITY', 3);
    // define('ID', 4);
    // define('MAX_PIECES', 5);

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

    // Local version
    if ($pieces == 2) {

    // // AWS version
    // if ($pieces == 3) {
        echo APIDescription();
    } else {
        // Local version
        if ($pieces > 4) {
            
        // // AWS version
        // if ($pieces > 5) {
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
                case 'tracks':
                    require_once('track2.php');
                    $track = new Track();
                    $verb = $_SERVER['REQUEST_METHOD'];
                    
                    switch ($verb) {
                        case 'GET':                             
                            // If the number of pieces is smaller than the maximum (4), 
                            // then get all Tracks, or search by name, otherwise search by id
                            if ($pieces < MAX_PIECES) {
                                if (!isset($_GET['name'])) {
                                    // Get All Tracks
                                    echo json_encode($track->getAll());
                                } else {
                                    // Search Track by name
                                    echo json_encode($track->search($_GET['name']));
                                }
                            } else {
                                // Get Track by ID
                                echo json_encode($track->get($urlPieces[ID]));
                            }
                            break;
                        case 'POST':                            
                            if (!isset($_POST['name'])) {
                                echo formatError();
                            } else if ($pieces < MAX_PIECES){
                                // Create Track
                                echo json_encode($track->create($_POST['name'], $_POST['albumId'], $_POST['mediaTypeId'], 
                                                                $_POST['genreId'], $_POST['composer'], $_POST['milliseconds'], 
                                                                $_POST['bytes'], $_POST['unitPrice']));
                            } else {
                                // Update Track
                                echo json_encode($track->update($urlPieces[ID], $_POST['name'], $_POST['albumId'], $_POST['mediaTypeId'], 
                                                                $_POST['genreId'], $_POST['composer'], $_POST['milliseconds'], 
                                                                $_POST['bytes'], $_POST['unitPrice']));
                            }                    
                            break;
                        case 'DELETE':
                            if ($pieces < MAX_PIECES) {
                                echo formatError();
                            } else {
                                // Delete Track
                                echo json_encode($track->delete($urlPieces[ID]));
                            }
                            break;                     
                    }
                    $track = null;
                    break;  
                case 'customers':
                    require_once('customer.php');
                    $customer = new Customer();
                    $verb = $_SERVER['REQUEST_METHOD'];
                    
                    switch ($verb) {
                        case 'GET':                             
                            // If the number of pieces is smaller than the maximum (4), 
                            // then get all Customers, or search by email, otherwise search by id
                            if ($pieces < MAX_PIECES) {
                                if (!isset($_GET['email'])) {
                                    // Get All Customers
                                    echo json_encode($customer->getAll());
                                } else {
                                    // Search Customer by email
                                    echo json_encode($customer->search($_GET['email']));
                                }
                            } else {
                                // Get Customer by ID
                                echo json_encode($customer->get($urlPieces[ID]));
                            }
                            break;
                        case 'POST':                            
                            if (!isset($_POST['email'])) {
                                echo formatError();
                            } else if ($pieces < MAX_PIECES){
                                // Create Customer
                                echo json_encode($customer->create($_POST['firstName'], $_POST['lastName'], $_POST['password'], 
                                                                $_POST['company'], $_POST['address'], $_POST['city'], 
                                                                $_POST['state'], $_POST['country'], $_POST['postalCode'], 
                                                                $_POST['phone'], $_POST['fax'], $_POST['email']));
                            } else {
                                // Update Customer
                                echo json_encode($customer->update($urlPieces[ID], 
                                                                $_POST['firstName'], $_POST['lastName'], $_POST['password'], 
                                                                $_POST['company'], $_POST['address'], $_POST['city'], 
                                                                $_POST['state'], $_POST['country'], $_POST['postalCode'], 
                                                                $_POST['phone'], $_POST['fax'], $_POST['email'], $_POST['newPassword']));
                            }                    
                            break;
                        case 'DELETE':
                            if ($pieces < MAX_PIECES) {
                                echo formatError();
                            } else {
                                // Delete Customer
                                echo json_encode($customer->delete($urlPieces[ID]));
                            }
                            break;                     
                    }
                    $customer = null;
                    break;
                
                // TODO: Delete after testing it with Postman
                case 'login':
                    require_once('customer.php');
                    $customer = new Customer();
                    $verb = $_SERVER['REQUEST_METHOD'];
                    
                    switch ($verb) {
                        case 'GET':                             
                            // If the number of pieces is smaller than the maximum (4), 
                            // then get all Customers, or search by email, otherwise search by id
                            if ($pieces < MAX_PIECES) {
                                if (!isset($_GET['email'])) {
                                    // Get All Customers
                                    echo json_encode($customer->getAll());
                                } else {
                                    // Search Customer by email
                                    echo json_encode($customer->login($_GET['email'], $_GET['password'], $_GET['isAdmin']));
                                }
                            } 
                            break;
                    }
                    $customer = null;
                    break;
                
                // TODO: Delete after testing it with Postman
                case 'purchase':
                    require_once('customer.php');
                    $customer = new Customer();
                    $verb = $_SERVER['REQUEST_METHOD'];
                    
                    switch ($verb) {
                        case 'POST':                             
                            // If the number of pieces is smaller than the maximum (4), 
                            // then get all Customers, or search by email, otherwise search by id
                            if ($pieces < MAX_PIECES) {
                                // Purchase
                                echo json_encode($customer->purchase($_POST['id'], $_POST['customBillingAddress'], $_POST['tracks']));
                            } 
                            break;
                    }
                    $customer = null;
                    break;
                default:
                    echo formatError();
            }
        }
    }
?>
