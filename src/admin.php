<?php

/**
 * Admin class
 *
 * @author Paul Panaitescu
 * @version 1.0 26 NOV 2020:
 */

    require_once('connection.php');
    require_once('functions.php');

    class Admin extends DB {

        public int $userID;
        // public string $firstName = '';
        // public string $lastName = '';
        // public string $email = '';


        /**
         * Validates an Admin login
         *
         * @param   user's password
         * @return  true if the password is correct, false if it is not or if the Admin does not exist
         */
        function validateAdmin($password) {
            // 
            $this->userID = '0';

            return 1;
            // 
            debug('Password validation');

            echo('inside 1 ');

            // Get admin data
            $query = <<<'SQL'
                SELECT * FROM admin;
                -- SELECT Password FROM admin WHERE Password = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);

            echo('inside 2 ');

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            echo('inside 3 ');
            
            
            // $stmt->execute([$hashedPassword]);
            $stmt->execute();
            
            echo('inside 4 ');
            if ($stmt->rowCount() === 0) {
                echo('inside 4.5 ');

                return false;
            }
            echo('inside 5 ');


            $row = $stmt->fetch();

            echo('inside 4 ');
            $this->userID = '1.1';
            // $this->firstName = '';
            // // 
            // // $this->lastName = $row['LastName'];
            // $this->lastName = $row['Password'];
            // $this->lastName = $password;
            // // 
            // $this->email = '';
            

            echo('hashedPassword' +  $hashedPassword);
            echo('password' + $password);
            // Check the password
            return (password_verify($password, $row['Password']));
        }
    }
?>