<?php

/**
 * User class
 *
 * @author Paul Panaitescu
 * @version 1.0 25 NOV 2020:
 */

    require_once('connection.php');
    require_once('functions.php');

    class User extends DB {

        public int $userID;
        public string $firstName;
        public string $lastName;
        public string $email;

        /**
         * Inserts a new user
         *
         * @param   first name of the new user
         * @param   last name of the new user
         * @param   email of the new user
         * @param   password of the new user
         * @return  true if the addition was correct, false if the email already exists
         */
        function create($firstName, $lastName, $email, $password) {

            // Check if the user already exists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM user WHERE email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);
            if ($stmt->fetch()['total'] > 0) {
                return false;
            }

            // Insert the user
            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = <<<'SQL'
                INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?);
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$firstName, $lastName, $email, $password]);

            $this->disconnect();

            return true;
        }

        /**
         * Updates a user
         *
         * @param   User email
         * @param   First name
         * @param   Last Name
         * @param   Password. If empty, it goes unchanged
         * @return  true if the update was correct, false if the password is incorrect
         */
        function update($email, $firstName, $lastName, $password, $newPassword) {

            $passwordChange = (trim($newPassword) !== '');

            $query = <<<'SQL'
                UPDATE user
                SET first_name = ?,
                    last_name = ?
            SQL;

            if ($passwordChange) {
                if ($this->validate($email, $password)) {
                    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $query .= ', password = ?';
                } else {
                    return false;
                }
            }
            $query .= ' WHERE email = ?;';

            debug($query);

            $stmt = $this->pdo->prepare($query);
            if ($passwordChange) {
                $stmt->execute([$firstName, $lastName, $newPassword, $email]);
            } else {
                $stmt->execute([$firstName, $lastName, $email]);
            }

            $this->disconnect();

            return true;
        }

        /**
         * Validates a user login
         *
         * @param   user's email
         * @param   user's password
         * @return  true if the password is correct, false if it is not or if the user does not exist
         */
        function validate($email, $password) {

            debug('Password validation');

            // Get user data
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password FROM customer WHERE Email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);
            if ($stmt->rowCount() === 0) {
                return false;
            }

            $row = $stmt->fetch();

            $this->userID = $row['CustomerId'];
            $this->firstName = $row['FirstName'];
            $this->lastName = $row['LastName'];
            $this->email = $email;

            // Check the password
            return (password_verify($password, $row['Password']));
        }
    }

?>


// <?php
// /**
//  * User class
//  *
//  * @author Paul Panaitescu
//  * @version 1.0 25 NOV 2020:
//  */
// require_once("connection.php");
//
//     class User extends DB {
//             /**
//              * Validates a user login
//              *
//              * @param   user's email
//              * @param   user's password
//              * @return  true if the password is correct, false if it is not or if the user does not exist
//              */
//             function validate($email, $password) {
//
//                 debug('Password validation');
//
//                 // Get user data
//                 $query = <<<'SQL'
//                     SELECT CustomerId, FirstName, LastName, Email, Address, Password FROM customer WHERE Email = ?;
//                 SQL;
//                 $stmt = $this->pdo->prepare($query);
//                 $stmt->execute([$email]);
//                 if ($stmt->rowCount() === 0) {
//                     return false;
//                 }
//
//                 $row = $stmt->fetch();
//
//                 $this->customerId = $row['CustomerId'];
//                 $this->firstName = $row['FirstName'];
//                 $this->lastName = $row['LastName'];
//                 $this->email = $Email;
//                 $this->address = $Address;
//
//                 // Check the password
//                 return (password_verify($password, $row['Password']));
//             }
//
// //         /**
// //          * Retrieves the users whose name includes a certain text
// //          *
// //          * @param   info upon which to execute the search
// //          * @return  an array with users
// //          */
// //         function search($info) {
// //             $query = <<<'SQL'
// //                 SELECT FirstName, LastName, Email, Address, Password
// //                 FROM customer
// //                 WHERE Email = ? AND Password = ?;
// //             SQL;
// //
// //             // Hash password
// //             $password_hash = password_hash($info["Password"], PASSWORD_DEFAULT);
// //             console.log("$password_hash", $password_hash);
// //
// //             $stmt = $this->pdo->prepare($query);
// //             $stmt->execute([$info["Email"], $password_hash]);
// //             $this->disconnect();
// //             return $stmt->fetch();
// //         }
//
//         /**
//          * Inserts a new user
//          *
//          * @param   name of the new user
//          * @return  the ID of the new user, or -1 if the user already exists
//          */
//         function add($info) {
//
//             // Check if the user already exists
//             $query = <<<'SQL'
//                 SELECT COUNT(*) AS total FROM user WHERE email = ?;
//             SQL;
//             $stmt = $this->pdo->prepare($query);
//             $stmt->execute([$info["email"]]);
//             if ($stmt->fetch()['total'] > 0) { // if user already exists with this email
//                 return -1;
//             }
//
//             // Insert the person
//             $query = <<<'SQL'
//                 INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?);
//             SQL;
//
//             // Hash password
//             $password_hash = password_hash($info["password"], PASSWORD_DEFAULT);
//
//             $stmt = $this->pdo->prepare($query);
//             $stmt->execute([$info["first_name"], $info["last_name"], $info["email"], $password_hash]);
//
//             $newID = $this->pdo->lastInsertId();
//             $this->disconnect();
//
//             return $newID;
//         }
//
//         /**
//          * Updates a user
//          *
//          * @param   user info (first_name, last_name, email, password)
//          * @return  true if success, -1 otherwise
//          */
//         function update($info) {
//
//             try {
//                 $this->pdo->beginTransaction();
//
//                 $query = <<<'SQL'
//                     UPDATE user
//                     SET first_name = ?,
//                         last_name = ?,
//                         password = ?
//                     WHERE email = ?
//                 SQL;
//
//                 // Hash password
//                 $password_hash = password_hash($info["password"], PASSWORD_DEFAULT);
//                 console.log("$password_hash", $password_hash);
//
//                 $stmt = $this->pdo->prepare($query);
//                 $stmt->execute([$info['first_name'], $info['last_name'], password_hash, $info['email']]);
//
//                 $this->pdo->commit();
//                 $return = true;
//
//             } catch (Exception $e) {
//                 $return = -1;
//                 $this->pdo->rollBack();
//                 debug($e);
//             }
//
//             $this->disconnect();
//
//             return $return;
//         }
//
//         /**
//          * Deletes a user
//          *
//          * @param   ID of the user to delete
//          * @return  true if the deletion was successful, and -1 if the user doens't exist
//          */
//         function delete($email) {
//
//             // Check if the user exists
//             $query = <<<'SQL'
//                 SELECT COUNT(*) AS total FROM user WHERE email = ?;
//             SQL;
//             $stmt = $this->pdo->prepare($query);
//             $stmt->execute([$email]);
//             if ($stmt->fetch()['total'] > 0) {
//                 return -1;
//             }
//
//             // Delete the user
//             $query = <<<'SQL'
//                 DELETE FROM user WHERE email = ?;
//             SQL;
//
//             $stmt = $this->pdo->prepare($query);
//             $stmt->execute([$email]);
//
//             $this->disconnect();
//
//             return true;
//         }
//     }
// ?>
