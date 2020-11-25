<?php
    require_once('functions.php');

    debug($_POST);

    if (!isset($_POST['entity']) || !isset($_POST['action'])) {
        leave();
    } else {
        $entity = $_POST['entity'];
        $action = $_POST['action'];

        switch ($entity) {
            case 'user':
                require_once('user.php');
                $user = new User;

                switch ($action) {
                    case 'search':
                        if (!isset($_POST['info'])) {
                            leave();
                        } else {
                            echo json_encode($user->search($_POST['info']));
                        }
                        break;
                    case 'add':
                        if (!isset($_POST['info'])) {
                            leave();
                        } else {
                            echo json_encode($user->add($_POST['info']));
                        }
                        break;
                    // case 'delete':
                    //     if (!isset($_POST['personID'])) {
                    //         leave();
                    //     } else {
                    //         echo json_encode($person->delete($_POST['personID']));
                    //     }
                    //     break;
                }

                break;
        }
    }
?>
