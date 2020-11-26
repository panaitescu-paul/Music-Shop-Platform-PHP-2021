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
            // 
            // $this->lastName = $row['LastName'];
            $this->lastName = $row['Password'];
            // 
            $this->email = $email;

            console.log("password ", $password);
            console.log("row['Password'] ", $row['Password']);

            // Check the password
            return (password_verify($password, $row['Password']));
        }
    }

?>
