<?php
// TODO: add try and catch block to remaining nodes, and code status 500 for server error

/**
 * Customer class
 *
 * @author Paul Panaitescu
 * @version 1.0 10 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    class Customer extends DB {

        public int $userID;
        public string $firstName;
        public string $lastName;
        public string $email;

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
            } else if ($password == null) {
                http_response_code(409);
                return -3;
            } else if ($email == null) {
                http_response_code(409);
                return -4;
            }

            // Check the count of Customers with this Email
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer WHERE Email = ?;
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

                // Hash the Password
                $password = password_hash($password, PASSWORD_DEFAULT);

                $query = <<<'SQL'
                    INSERT INTO customer (FirstName, LastName, Password, Company, Address, 
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

        /**
         * Updates a Customer
         * 
         * @param   customerId, firstName, lastName, password, company, address, 
         *          city, state, country, postalCode, phone, fax, email, newPassword
         * @return  true if success, 
         *          -1 if the FirstName is null
         *          -2 if the LastName is null
         *          -3 if the Password is null
         *          -4 if the Email is null
         *          -5 if the Customer id doesn't exists
         *          -6 if the Customer could not be updated
         */
        function update($customerId, $firstName, $lastName, $password, $company, $address, 
                        $city, $state, $country, $postalCode, $phone, $fax, $email, $newPassword) {
            
            //  Check if FirstName, LastName, Password, Email are null
            if ($firstName == null) {
                http_response_code(409);
                return -1;
            } else if ($lastName == null) {
                http_response_code(409);
                return -2;
            } else if ($password == null) {
                http_response_code(409);
                return -3;
            } else if ($email == null) {
                http_response_code(409);
                return -4;
            }

            // Check if there is a Customer with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer WHERE CustomerId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$customerId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Customer id doesn't exist
                http_response_code(404);
                return -5;
            }

            // Update Customer
            try {

                $passwordChange = (trim($newPassword) !== '');

                $query = <<<'SQL'
                    UPDATE customer
                    -- SET FirstName = ?, LastName = ?, Password = ?, Company = ?, Address = ?, 
                    --     City = ?, State = ?, Country = ?, PostalCode = ?, Phone = ?, Fax = ?, Email = ?
                    SET FirstName = ?, LastName = ?, Company = ?, Address = ?, 
                        City = ?, State = ?, Country = ?, PostalCode = ?, Phone = ?, Fax = ?, Email = ?
                SQL;

                if ($passwordChange) {
                    if ($this->login($email, $password)) {    
                        // Hash the Password            
                        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $query .= ', Password = ?';
                    } else {
                        return false;
                    }
                }
                $query .= ' WHERE CustomerId = ?;';
                debug($query);

                $stmt = $this->pdo->prepare($query);

                if ($passwordChange) {
                    $stmt->execute([$firstName, $lastName, $company, $address, $city, 
                                    $state, $country, $postalCode, $phone, $fax, 
                                    $email, $newPassword, $customerId]);
                } else {
                    $stmt->execute([$firstName, $lastName, $company, $address, $city, 
                                    $state, $country, $postalCode, $phone, $fax, 
                                    $email, $customerId]);
                }

                // 
                // $stmt = $this->pdo->prepare($query);
                // $stmt->execute([$firstName, $lastName, $password, $company, $address, 
                //                 $city, $state, $country, $postalCode, $phone, $fax, $email, $customerId]);
                $return = true;

            } catch (Exception $e) {
                http_response_code(500);
                $return = -6;
                debug($e);
            }

            $this->disconnect();
            http_response_code(200);
            return $return;
        }

        /**
         * Deletes a Customer
         * 
         * @param   Id of the Customer to delete
         * @return  true if success, 
         *          -1 if Customer with this id doesn't exist
         *          -2 if this Customer has a Purchase (has an Invoice) - Referential Integrity problem
         *          -3 if the Customer could not be deleted
         */
        function delete($id) {  
            // Check if there is an Customer with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer WHERE CustomerId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Customer id doesn't exist
                http_response_code(404);
                return -1;
            }

            // Check if this Customer has an Invoice - Referential Integrity problem
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM invoice WHERE CustomerId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] > 0) {
                // This Customer has Invoice
                http_response_code(409);
                return -2;
            }
                  
            // Deletes Customer
            try {
                $query = <<<'SQL'
                    DELETE 
                    FROM customer 
                    WHERE CustomerId = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                $return = true;
            } catch (Exception $e) {
                http_response_code(500);
                $return = -3;
                debug($e);
            }
            $this->disconnect();
            http_response_code(200);
            return $return;
        }

        /**
         * Validates a user (Customer/Admin) Login
         * 
         * @param   user's email
         * @param   user's password
         * @return  true if the password is correct, 
         *          false if the password is not correct, or if the user (Customer/Admin) does not exist
         */
        function login($email, $password, $isAdmin = 0) {
            echo 'inside Login ';
            if ($isAdmin) { // Validation for Admin
                debug('Password validation for Admin');
                echo 'inside Login Admin ';

    
                // Get user data
                $query = <<<'SQL'
                    SELECT Password FROM admin;
                SQL;            
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
                if ($stmt->rowCount() === 0) {
                    return false;
                }
    
                $row = $stmt->fetch();
    
                $this->userID = '0';
                $this->firstName = 'Admin';
                $this->lastName = '';
                $this->email = '';
    
                // Check the password
                return (password_verify($password, $row['Password']));
            
            } else { // Validation for Customer
                debug('Password validation for Customer');
                echo 'inside Login Customer ';

    
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

        /**
         * Makes a Purchase
         * 
         * @param   id - Customer's Id
         *          tracks - the id of the purchased tracks
         *          billingAddress - Customer's Billing Address (false by default)
         * @return  true if the Purchase was successful, 
         *          -1 if Customer with this id doesn't exist
         *          -2 if Tracks with this id do not exist
         *          -3 if the Purchase could not be made
         */
        function purchase($id, $tracks, $customBillingAddress = false) {
            // Check if there is a Customer with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM customer WHERE CustomerId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Customer id doesn't exist
                http_response_code(404);
                return -1;
            }

            foreach($tracks as trackId) {
                echo 'trackId ';
                // Check if there is an Track with this id
                $query = <<<'SQL'
                    SELECT COUNT(*) AS total FROM track WHERE TrackId = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$trackId]);   
    
                if ($stmt->fetch()['total'] == 0) {
                    // Track id doesn't exist
                    http_response_code(404);
                    return -2;
                }
            }

            $totalPrice = 0;
            // Get Total Price from Purchased Tracks
            foreach($tracks as $trackId) {
                $query = <<<'SQL'
                    SELECT UnitPrice
                    FROM track
                    WHERE TrackId = ?;
                SQL;
    
                $stmt = $this->pdo->prepare($query);
                $track = $stmt->execute([$trackId]);   
                $totalPrice = $track['UnitPrice'];
            }

            // Create Invoice and InvoiceLines
            try {
                $this->pdo->beginTransaction();
                
                // Search Customer Info
                $query = <<<'SQL'
                    SELECT CustomerId, Address, City, State, Country, PostalCode
                    FROM customer
                    WHERE CustomerId = ?;
                SQL;

                $stmt = $this->pdo->prepare($query);
                $customer = $stmt->execute([$id]);                

                // Set Address
                if ($customBillingAddress) {
                    $billingAddress = $customBillingAddress;
                } else {
                    $billingAddress = $customer['Address'];
                }

                // Set Date
                $date = date("Y/m/d") . date("h:i:sa");
                echo ' ' . $date . ' ';

                // Create the Invoice
                $query = <<<'SQL'
                    INSERT INTO invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, 
                                        BillingState, BillingCountry, BillingPostalCode, Total) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?);
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$row['CustomerId'], $date, $billingAddress, $row['BillingCity'], 
                                $row['BillingState'], $row['BillingCountry'], $row['BillingPostalCode'], $total]);
                $newID = $this->pdo->lastInsertId();

                // Create an Invoiceline for every Purchased Track
                foreach($tracks as $trackId) {
                    echo ' newID ' . $newID;
                    $query = <<<'SQL'
                        SELECT UnitPrice
                        FROM track
                        WHERE TrackId = ?;
                    SQL;
        
                    $stmt = $this->pdo->prepare($query);
                    $track = $stmt->execute([$trackId]);  
                    
                    // For every Track, create an Invoiceline
                    $query = <<<'SQL'
                        INSERT INTO invoiceline (InvoiceId, TrackId, UnitPrice, Quantity) 
                        VALUES (?, ?, ?, ?);
                    SQL;
    
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute([$newID, $trackId, $track['UnitPrice'], 1]);
                }
             } catch (Exception $e) {
                $newID = -3;
                $this->pdo->rollBack();
                debug($e);
            }

            $this->disconnect();
            http_response_code(200);
            return $return;
        }
    }
?>