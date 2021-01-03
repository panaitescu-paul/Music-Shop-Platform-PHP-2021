<?php

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
         *          or -1 if There are no Customers in the DB!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'There are no Customers in the DB!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Select all Customers
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password, Company, 
                        Address, City, State, Country, PostalCode, Phone, Fax, Email
                FROM customer;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['FirstName'] = sanitize($result['FirstName']);
                $result['LastName'] = sanitize($result['LastName']);
                $result['Password'] = sanitize($result['Password']); // !!! Check for possible bugs !!!
                $result['Company'] = sanitize($result['Company']);
                $result['Address'] = sanitize($result['Address']);
                $result['City'] = sanitize($result['City']);
                $result['State'] = sanitize($result['State']);
                $result['Country'] = sanitize($result['Country']);
                $result['Email'] = sanitize($result['Email']);
            }

            $this->disconnect();
            http_response_code(200);
            return $results; 
        }

        /**
         * Retrieve Customer by id 
         * 
         * @param   id of the Customer
         * @return  an Customer and their information, 
         *          or -1 if Customer with this ID was not found!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer with this ID was not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
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
            $result = $stmt->fetch();  
            
            // Sanitize the strings that come from the DB
            $result['FirstName'] = sanitize($result['FirstName']);
            $result['LastName'] = sanitize($result['LastName']);
            $result['Password'] = sanitize($result['Password']); // !!! Check for possible bugs !!!
            $result['Company'] = sanitize($result['Company']);
            $result['Address'] = sanitize($result['Address']);
            $result['City'] = sanitize($result['City']);
            $result['State'] = sanitize($result['State']);
            $result['Country'] = sanitize($result['Country']);
            $result['Email'] = sanitize($result['Email']);
            
            $this->disconnect();
            http_response_code(200);
            return $result;
        }

        /**
         * Retrieve the Customers whose email includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Customers information, 
         *          or -1 if Customers with this Email were not found!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Customers with this Email were not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Customers
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password, Company, 
                        Address, City, State, Country, PostalCode, Phone, Fax, Email
                FROM customer
                WHERE Email LIKE ?
                ORDER BY CustomerId;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']); 
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['FirstName'] = sanitize($result['FirstName']);
                $result['LastName'] = sanitize($result['LastName']);
                $result['Password'] = sanitize($result['Password']); // !!! Check for possible bugs !!!
                $result['Company'] = sanitize($result['Company']);
                $result['Address'] = sanitize($result['Address']);
                $result['City'] = sanitize($result['City']);
                $result['State'] = sanitize($result['State']);
                $result['Country'] = sanitize($result['Country']);
                $result['Email'] = sanitize($result['Email']);
            }  

            $this->disconnect();
            http_response_code(200);
            return $results;                
        }
        
        /**
         * Insert a new Customer
         * 
         * @param   firstName, lastName, password, company, address, 
         *          city, state, country, postalCode, phone, fax, email
         * @return  the Id of the new Customer, 
         *          -1 if First Name can not be null!
         *          -2 if Last Name can not be null!
         *          -3 if Password can not be null!
         *          -4 if Email can not be null!
         *          -5 if Customer with this Email already exists!
         *          -6 if Customer could not be created!
         */
        function create($firstName, $lastName, $password, $company, 
                        $address, $city, $state, $country, $postalCode, $phone, $fax, $email) {
            
            //  Check if FirstName, LastName, Password, Email are null
            if ($firstName == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'First Name can not be null!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            } else if ($lastName == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Last Name can not be null!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            } else if ($password == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Password can not be null!';
                $returnMsg['Code'] = '-3';
                return $returnMsg;
            } else if ($email == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Email can not be null!';
                $returnMsg['Code'] = '-4';
                return $returnMsg;
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer with this Email already exists!';
                $returnMsg['Code'] = '-5';
                return $returnMsg;
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

                $this->disconnect();
                http_response_code(200);
                $returnMsg = array();
                $returnMsg['newID'] = $newID;
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer could not be created!';
                $returnMsg['Code'] = '-6';
                $return = $returnMsg;
                debug($e);
            }
            
            $this->disconnect();
            return $return;
            // http_response_code(200);
            // return true;
        }

        /**
         * Updates a Customer
         * 
         * @param   customerId, firstName, lastName, password, company, address, 
         *          city, state, country, postalCode, phone, fax, email, newPassword
         * @return  Success if Track was successfully updated! 
         *          -1 if First Name can not be null!
         *          -2 if Last Name can not be null!
         *          -3 if Password can not be null!
         *          -4 if Email can not be null!
         *          -5 if Customer with this ID does not exist!
         *          -6 if Customer could not be updated!
         */
        function update($customerId, $firstName, $lastName, $password, $company, $address, 
                        $city, $state, $country, $postalCode, $phone, $fax, $email, $newPassword) {
            
            //  Check if FirstName, LastName, Password, Email are null
            if ($firstName == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'First Name can not be null!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            } else if ($lastName == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Last Name can not be null!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            } else if ($password == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Password can not be null!';
                $returnMsg['Code'] = '-3';
                return $returnMsg;
            } else if ($email == null) {
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Email can not be null!';
                $returnMsg['Code'] = '-4';
                return $returnMsg;
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer with this ID does not exist!';
                $returnMsg['Code'] = '-5';
                return $returnMsg;
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

                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Customer was successfully updated!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer could not be updated!';
                $returnMsg['Code'] = '-6';
                $return = $returnMsg;
                debug($e);
            }

            $this->disconnect();
            return $return;
        }

        /**
         * Deletes a Customer
         * 
         * @param   Id of the Customer to delete
         * @return  Success if Customer was successfully deleted! 
         *          -1 if Customer with this ID does not exist!
         *          -2 if Customer has a Purchase (has one or more Invoices)! Can not delete! - Referential Integrity problem
         *          -3 if Customer could not be deleted!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer has a Purchase (has one or more Invoices)! Can not delete!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
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
                
                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Customer was successfully deleted!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Customer could not be deleted!';
                $returnMsg['Code'] = '-3';
                $return = $returnMsg;
                debug($e);
            }
            $this->disconnect();
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
            if ($isAdmin) { // Validation for Admin
                debug('Password validation for Admin');
    
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
                // 
                // Sanitize the strings that come from the DB
                $row['Password'] = sanitize($row['Password']); // !!! Check for possible bugs !!!
                // 

                $this->userID = '0';
                $this->firstName = 'Admin';
                $this->lastName = '';
                $this->email = '';
    
                // Check the password
                return (password_verify($password, $row['Password']));
            
            } else { // Validation for Customer
                debug('Password validation for Customer');
    
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
                // 
                // Sanitize the strings that come from the DB
                $row['FirstName'] = sanitize($row['FirstName']);
                $row['LastName'] = sanitize($row['LastName']);
                $row['Password'] = sanitize($row['Password']); // !!! Check for possible bugs !!!
                // 
    
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
         *          customBillingAddress - Customer's Billing Address (false by default)
         * @return  true if the Purchase was successful, 
         *          -1 if Customer with this id doesn't exist
         *          -2 if Tracks with this id do not exist
         *          -3 if the Purchase could not be made
         */
        function purchase($id, $customBillingAddress = false, $tracks) {
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

            // Check if there are Tracks with thise ids
            foreach($tracks as $trackId) {
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
            
            // Get Total Price from Purchased Tracks
            $totalPrice = 0;
            foreach($tracks as $trackId) {
                // Search Tracks
                $query = <<<'SQL'
                    SELECT UnitPrice
                    FROM track
                    WHERE TrackId = ?;
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$trackId]);                
                $track = $stmt->fetch();  
                $totalPrice += $track['UnitPrice'];
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
                $stmt->execute([$id]); 
                $customer = $stmt->fetch(); 
                
                // 
                // Sanitize the strings that come from the DB
                $customer['Address'] = sanitize($customer['Address']);
                $customer['City'] = sanitize($customer['City']);
                $customer['State'] = sanitize($customer['State']);
                $customer['Country'] = sanitize($customer['Country']);
                // 

                // Set Address
                if ($customBillingAddress) {
                    $billingAddress = $customBillingAddress;
                } else {
                    $billingAddress = $customer['Address'];
                }

                // Set Date
                $date = date('Y-m-d H:i:s');

                // Create the Invoice
                $query = <<<'SQL'
                    INSERT INTO invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, 
                                        BillingState, BillingCountry, BillingPostalCode, Total) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?);
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$customer['CustomerId'], $date, $billingAddress, $customer['City'], 
                                $customer['State'], $customer['Country'], $customer['PostalCode'], $totalPrice]);
                $invoiceID = $this->pdo->lastInsertId();


                // Create an Invoiceline for every Purchased Track
                foreach($tracks as $trackId) {
                    $query = <<<'SQL'
                        SELECT UnitPrice
                        FROM track
                        WHERE TrackId = ?;
                    SQL;
        
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute([$trackId]);  
                    $track = $stmt -> fetch();
                    
                    // For every Track, create an Invoiceline
                    $query = <<<'SQL'
                        INSERT INTO invoiceline (InvoiceId, TrackId, UnitPrice, Quantity) 
                        VALUES (?, ?, ?, ?);
                    SQL;
    
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute([$invoiceID, $trackId, $track['UnitPrice'], 1]);
                    $newID2 = $this->pdo->lastInsertId();
                }
                $this->pdo->commit();
                
             } catch (Exception $e) {
                $this->pdo->rollBack();
                debug($e);
                http_response_code(500);
                return -3;
            }

            $this->disconnect();
            http_response_code(200);
            return 200;
        }
    }
?>