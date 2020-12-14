<?php
// TODO: add try and catch block to remaining nodes, and code status 500 for server error
// TODO: Customer Password hashing for create, update

/**
 * Customer class
 *
 * @author Paul Panaitescu
 * @version 1.0 10 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    class Customer extends DB {

        /**
         * Retrieve all Customers 
         * 
         * @return  an array with all Customers and their information, 
         *          or -1 if there are no Customers
         */
        function getAll() {
            // Check the count of Customers
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();   

            if ($stmt->fetch()['total'] == 0) {
                // Customers not found
                http_response_code(404);
                return -1;
            }

            // Select all Customers
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password, Company, 
                        Address, City, State, Country, PostalCode, Phone, Fax, Email
                FROM customer;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll(); 
        }

        /**
         * Retrieve Customer by id 
         * 
         * @param   id of the Customer
         * @return  an Customer and their information, 
         *          or -1 if the Customer was not found
         */
        function get($id) {
            // Check the count of Customers
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer WHERE CustomerId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Customers not found
                http_response_code(404);
                return -1;
            }

            // Search Customers
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password, Company, 
                        Address, City, State, Country, PostalCode, Phone, Fax, Email
                FROM customer
                WHERE CustomerId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetch();
        }

        /**
         * Retrieve the Customers whose email includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Customers information, 
         *          or -1 if no Customers were found
         */
        function search($searchText) {
            // Check the count of Customers with this email
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer WHERE Email LIKE ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);   

            if ($stmt->fetch()['total'] == 0) {
                // Customers not found
                http_response_code(404);
                return -1;
            }

            // Search Customers
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password, Company, 
                        Address, City, State, Country, PostalCode, Phone, Fax, Email
                FROM customer
                WHERE Email LIKE ?
                ORDER BY Email;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll();                
        }
        
        /**
         * Insert a new Customer
         * 
         * @param   firstName, lastName, password, company, address, 
         *          city, state, country, postalCode, phone, fax, email
         * @return  the Id of the new Customer, 
         *          -1 if the FirstName is null
         *          -2 if the LastName is null
         *          -3 if the Password is null
         *          -4 if the Email is null
         *          -5 if the Customer email already exists
         *          -6 if the Customer could not be created
         */
        function create($firstName, $lastName, $password, $company, 
                        $address, $city, $state, $country, $postalCode, $phone, $fax, $email) {
            
            //  Check if FirstName, LastName, Password, Email are null
            if ($firstName == null) {
                http_response_code(409);
                return -1;
            } else if ($lastName == null) {
                http_response_code(409);
                return -2;
            } else if ($Password == null) {
                http_response_code(409);
                return -3;
            } else if ($Email == null) {
                http_response_code(409);
                return -4;
            }

            // Check the count of Customers with this Email
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM Customer WHERE Email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);   

            if ($stmt->fetch()['total'] > 0) {
                // Customer Email already exists
                http_response_code(409);
                return -5;
            }
            
            // Create Customer
            try {
                $query = <<<'SQL'
                    INSERT INTO track (FirstName, LastName, Password, Company, Address, 
                                    City, State, Country, PostalCode, Phone, Fax, Email) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
                    SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$firstName, $lastName, $password, $company, $address, 
                                $city, $state, $country, $postalCode, $phone, $fax, $email]);
                $newID = $this->pdo->lastInsertId();
                $return = $newID;

            } catch (Exception $e) {
                http_response_code(500);
                $return = -6;
                debug($e);
            }
            
            $this->disconnect();
            http_response_code(200);
            return $return;
        }
    }
?>