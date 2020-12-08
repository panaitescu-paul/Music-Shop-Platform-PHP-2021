<?php
    require_once('functions.php');
    debug($_POST);

    session_start();
    // if (!isset($_SESSION['userID'])) {
    if (false) {
        debug('Session variable userID not set.<br>');
        leave('User not authenticated.');
    } else {
        if (!isset($_POST['entity']) || !isset($_POST['action'])) {
            leave();
        } else {
            $entity = $_POST['entity'];
            $action = $_POST['action'];

            switch ($entity) {
                case 'artist':
                    require_once('artist.php');
                    $artist = new Artist;
                    switch ($action) {
                        case 'getAll':
                            echo json_encode($artist->getAll());
                            break;
                        case 'get':
                            if (!isset($_POST['id'])) {
                                leave();
                            } else {
                                echo json_encode($artist->get($_POST['id']));
                            }
                            break;
                        case 'search':
                            if (!isset($_POST['searchText'])) {
                                leave();
                            } else {
                                echo json_encode($artist->search($_POST['searchText']));
                            }
                            break;
                        case 'create':
                            if (!isset($_POST['info'])) {
                                leave();
                            } else {
                                echo json_encode($artist->create($_POST['info']));
                            }
                            break;
                        case 'update':
                            if (!isset($_POST['info'])) {
                                leave();
                            } else {
                                echo json_encode($artist->update($_POST['info']));
                            }
                            break;
                        case 'delete':
                            if (!isset($_POST['id'])) {
                                leave();
                            } else {
                                echo json_encode($artist->delete($_POST['id']));
                            }
                            break;
                    }
                    break;
                case 'album':
                    require_once('album.php');
                    $album = new Album;
                    switch ($action) {
                        case 'getAll':
                            echo json_encode($album->getAll());
                            break;
                        case 'get':
                            if (!isset($_POST['id'])) {
                                leave();
                            } else {
                                echo json_encode($album->get($_POST['id']));
                            }
                            break;
                        case 'search':
                            if (!isset($_POST['searchText'])) {
                                leave();
                            } else {
                                echo json_encode($album->search($_POST['searchText']));
                            }
                            break;
                        case 'create':
                            if (!isset($_POST['info'])) {
                                leave();
                            } else {
                                echo json_encode($album->create($_POST['info']));
                            }
                            break;
                        case 'update':
                            if (!isset($_POST['info'])) {
                                leave();
                            } else {
                                echo json_encode($album->update($_POST['info']));
                            }
                            break;
                        case 'delete':
                            if (!isset($_POST['id'])) {
                                leave();
                            } else {
                                echo json_encode($album->delete($_POST['id']));
                            }
                            break;
                    }
                    break;
                case 'track':
                    require_once('track.php');
                    $track = new Track;
                    switch ($action) {
                        case 'getAll':
                            echo json_encode($track->getAll());
                            break;
                        case 'get':
                            if (!isset($_POST['id'])) {
                                leave();
                            } else {
                                echo json_encode($track->get($_POST['id']));
                            }
                            break;
                        case 'search':
                            if (!isset($_POST['searchText'])) {
                                leave();
                            } else {
                                echo json_encode($track->search($_POST['searchText']));
                            }
                            break;
                        case 'create':
                            if (!isset($_POST['info'])) {
                                leave();
                            } else {
                                echo json_encode($track->create($_POST['info']));
                            }
                            break;
                        case 'update':
                            if (!isset($_POST['info'])) {
                                leave();
                            } else {
                                echo json_encode($track->update($_POST['info']));
                            }
                            break;
                        case 'delete':
                            if (!isset($_POST['id'])) {
                                leave();
                            } else {
                                echo json_encode($track->delete($_POST['id']));
                            }
                            break;
                    }
                    break;
                case 'user':
                    require_once('user.php');
                    $user = new User;

                    switch ($action) {
                        case 'update':
                            if (!isset($_POST['email'])) {
                                leave();
                            } else {
                                echo json_encode($user->update($_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['newPassword']));
                            }
                            break;
                    }
                    break;
                case 'admin':
                    require_once('admin.php');
                    $user = new Admin;

                    switch ($action) {
                        case 'update':
                            if (!isset($_POST['email'])) {
                                leave();
                            } else {
                                echo json_encode($user->update($_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['newPassword']));
                            }
                            break;
                    }
                    break;
                case 'session':
                    switch ($action) {
                        case 'destroy':
                            session_destroy();
                            leave('Session destroyed');
                            break;
                    }
            }
        }
    }
?>
