<?php
/**
 * User class
 *
 * @author Paul Panaitescu
 * @version 1.0 25 NOV 2020:
 */
require_once("connection.php");

    class User extends DB {
        /**
         * Retrieves the users whose name includes a certain text
         *
         * @param   info upon which to execute the search
         * @return  an array with users
         */
        function search($info) {
            $query = <<<'SQL'
                SELECT FirstName, LastName, Email, Address, Password
                FROM customer
                WHERE Email = ? AND Password = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$info["Email"], $info["Password"]]);
            $this->disconnect();
            return $stmt->fetch();
        }

        /**
         * Inserts a new user
         *
         * @param   name of the new user
         * @return  the ID of the new user, or -1 if the user already exists
         */
        function add($info) {

            // Check if the user already exists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM user WHERE email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$info["email"]]);
            if ($stmt->fetch()['total'] > 0) { // if user already exists with this email
                return -1;
            }

            // Insert the person
            $query = <<<'SQL'
                INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?);
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$info["first_name"], $info["last_name"], $info["email"], $info["password"]]);

            $newID = $this->pdo->lastInsertId();
            $this->disconnect();

            return $newID;
        }

        /**
         * Updates a user
         *
         * @param   user info (first_name, last_name, email, password)
         * @return  true if success, -1 otherwise
         */
        function update($info) {

            try {
                $this->pdo->beginTransaction();

                $query = <<<'SQL'
                    UPDATE user
                    SET first_name = ?,
                        last_name = ?,
                        password = ?
                    WHERE email = ?
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['first_name'], $info['last_name'], $info['password'], $info['email']]);

                $this->pdo->commit();
                $return = true;

            } catch (Exception $e) {
                $return = -1;
                $this->pdo->rollBack();
                debug($e);
            }

            $this->disconnect();

            return $return;
        }

        /**
         * Deletes a user
         *
         * @param   ID of the user to delete
         * @return  true if the deletion was successful, and -1 if the user doens't exist
         */
        function delete($email) {

            // Check if the user exists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM user WHERE email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);
            if ($stmt->fetch()['total'] > 0) {
                return -1;
            }

            // Delete the user
            $query = <<<'SQL'
                DELETE FROM user WHERE email = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);

            $this->disconnect();

            return true;
        }
    }
?>
