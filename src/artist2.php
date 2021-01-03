<?php

/**
 * Artist class
 *
 * @author Paul Panaitescu
 * @version 1.0 1 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    class Artist extends DB {

        /**
         * Retrieve all artists 
         * 
         * @return  an array with all Artists and their information, 
         *          or -1 if There are no Artists in the DB!
         */
        function getAll() {
            // Check the count of Artists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();   

            if ($stmt->fetch()['total'] == 0) {
                // Artists not found
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'There are no Artists in the DB!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Select all Artists
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['Name'] = sanitize($result['Name']);
            }

            $this->disconnect();
            http_response_code(200);
            return $results; 
        }

        /**
         * Retrieve Artist by id 
         * 
         * @param   id of the Artist
         * @return  an Artist and their information, 
         *          or -1 if Artist with this ID was not found!
         */
        
        function get($id) {
            // Check the count of Artists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE ArtistId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Artists not found
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this ID was not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Artists
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist
                WHERE ArtistId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);     
            $result = $stmt->fetch();  
            
            // Sanitize the strings that come from the DB
            $result['Name'] = sanitize($result['Name']);

            $this->disconnect();
            http_response_code(200);
            return $result;
        }

        /**
         * Retrieve the Artists whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Artists information, 
         *          or -1 if Artists with this Name were not found!
         */
        function search($searchText) {
            // Check the count of Artists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE Name LIKE ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);   

            if ($stmt->fetch()['total'] == 0) {
                // Artists not found
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artists with this Name were not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Artists
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist
                WHERE Name LIKE ?
                ORDER BY ArtistId;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']); 
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['Name'] = sanitize($result['Name']);
            }

            $this->disconnect();
            http_response_code(200);
            return $results;                
        }
        
        /**
         * Insert a new Artist
         * 
         * @param   artist name
         * @return  the Id of the new artist, 
         *          or -1 if Artist with this Name already exists!
         */
        function create($name) {
            // Check the count of Artists with this name
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE Name = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);   

            if ($stmt->fetch()['total'] > 0) {
                // Artist name already exists
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this Name already exists!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Create Artist
            $query = <<<'SQL'
                INSERT INTO artist (Name) VALUES (?);
                SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);
            $newID = $this->pdo->lastInsertId();
            
            $this->disconnect();
            http_response_code(200);
            $returnMsg = array();
            $returnMsg['newID'] = $newID;
            return $returnMsg;
        }

        /**
         * Updates an Artist
         * 
         * @param   id - Artist id
         * @param   name - Artist name
         * @return  Success if Artist was successfully updated! 
         *          -1 if Artist with this ID does not exist! 
         *          -2 if Artist with this Name already exists!
         *          -3 if Artist could not be updated!
         */
        function update($id, $name) {
            // Check if there is an Artist with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE ArtistId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Artist id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check the count of Artists with this name
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE Name = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);   

            if ($stmt->fetch()['total'] > 0) {
                // Artist name already exists
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this Name already exists!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            }

             // Update Artist
            try {
                $query = <<<'SQL'
                UPDATE artist
                    SET Name = ?
                    WHERE ArtistId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name, $id]);

                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Artist was successfully updated!';
                $return = $returnMsg;
                
            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist could not be updated!';
                $returnMsg['Code'] = '-3';
                $return = $returnMsg;
                debug($e);
            }

            $this->disconnect();
            return $return;
        }

        /**
         * Deletes an Artist
         * 
         * @param   Id of the Artist to delete
         * @return  Success if Artist was successfully deleted! 
         *          -1 if Artist with this ID does not exist!
         *          -2 if Artist has one or more Albums! Can not delete! - Referential Integrity problem
         *          -3 if Artist could not be deleted!
         */
        function delete($id) {  
            // Check if there is an Artist with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE ArtistId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Artist id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check if this Artist has an Album
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE ArtistId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] > 0) {
                // This Artist has Albums
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist has one or more Albums! Can not delete!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            }
                  
            // Deletes Artist
            try {
                $query = <<<'SQL'
                    DELETE 
                    FROM artist 
                    WHERE ArtistId = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
                
                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Artist was successfully deleted!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist could not be deleted!';
                $returnMsg['Code'] = '-3';
                $return = $returnMsg;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }
    }
?>