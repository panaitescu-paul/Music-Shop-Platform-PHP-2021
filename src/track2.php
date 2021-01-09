<?php

/**
 * Track class
 *
 * @author Paul Panaitescu
 * @version 1.0 5 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    class Track extends DB {

        /**
         * Retrieve all Tracks 
         * 
         * @return  an array with all Tracks and their information, 
         *          or -1 if There are no Tracks in the DB!
         */
        function getAll() {
            // Check the count of Tracks
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();   

            if ($stmt->fetch()['total'] == 0) {
                // Tracks not found
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'There are no Tracks in the DB!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Select all Tracks
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['Name'] = sanitize($result['Name']);
                $result['Composer'] = sanitize($result['Composer']);
            }

            $this->disconnect();
            http_response_code(200);
            return $results; 
        }

        /**
         * Retrieve Track by id 
         * 
         * @param   id of the Track
         * @return  an Track and their information, 
         *          or -1 if Track with this ID was not found!
         */
        function get($id) {
            // Check the count of Tracks
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE TrackId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Tracks not found
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track with this ID was not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Tracks
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track
                WHERE TrackId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   
            $result = $stmt->fetch();  
            
            // Sanitize the strings that come from the DB
            $result['Name'] = sanitize($result['Name']);
            $result['Composer'] = sanitize($result['Composer']);
             
            $this->disconnect();
            http_response_code(200);
            return $result;
        }

        /**
         * Retrieve the Tracks whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Tracks information, 
         *          or -1 if Tracks with this Name were not found!
         */
        function search($searchText) {
            // Check the count of Tracks
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE Name LIKE ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);   

            if ($stmt->fetch()['total'] == 0) {
                // Tracks not found
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Tracks with this Name were not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Tracks
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track
                WHERE Name LIKE ?
                ORDER BY TrackId;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);        
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['Name'] = sanitize($result['Name']);
                $result['Composer'] = sanitize($result['Composer']);
            }        

            $this->disconnect();
            http_response_code(200);
            return $results;                
        }
        
        /**
         * Insert a new Track
         * 
         * @param   name,
         *          albumId, 
         *          mediaTypeId, 
         *          genreId, 
         *          composer, 
         *          milliseconds, 
         *          bytes, 
         *          unitPrice
         * @return  the Id of the new Track, 
         *          -1 if Track with this Name already exists!
         *          -2 if Album with this ID does not exist!
         *          -3 if MediaType with this ID does not exist!
         *          -4 if Genre with this ID does not exist! 
         *          -5 if Track could not be created!
         */
        function create($name, $albumId, $mediaTypeId, $genreId, $composer, $milliseconds, $bytes, $unitPrice) {
            // Check the count of Tracks with this name
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE Name = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);   

            if ($stmt->fetch()['total'] > 0) {
                // Track name already exists
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track with this Name already exists!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check if there is an Album with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE AlbumId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$albumId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Album id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Album with this ID does not exist!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            }

            // Check if there is an MediaType with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM mediatype WHERE MediaTypeId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$mediaTypeId]);   

            if ($stmt->fetch()['total'] == 0) {
                // MediaType id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'MediaType with this ID does not exist!';
                $returnMsg['Code'] = '-3';
                return $returnMsg;
            }

            // Check if there is an Genre with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM genre WHERE GenreId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$genreId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Genre id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Genre with this ID does not exist!';
                $returnMsg['Code'] = '-4';
                return $returnMsg;
            }

            // Create Track
            try {
                $query = <<<'SQL'
                    INSERT INTO track (Name, AlbumId, MediaTypeId, GenreId, Composer, 
                                    Milliseconds, Bytes, UnitPrice) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?);
                    SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name, $albumId, $mediaTypeId, $genreId, $composer, 
                                $milliseconds, $bytes, $unitPrice]);
                $newID = $this->pdo->lastInsertId();
                
                http_response_code(200);
                $returnMsg = array();
                $returnMsg['newID'] = $newID;
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track could not be created!';
                $returnMsg['Code'] = '-5';
                $return = $returnMsg;
                debug($e);
            }
            
            $this->disconnect();
            return $return;
        }

        /**
         * Updates a Track
         * 
          * @param  trackId,
         *          name,
         *          albumId, 
         *          mediaTypeId, 
         *          genreId, 
         *          composer, 
         *          milliseconds, 
         *          bytes, 
         *          unitPrice
         * @return  Success if Track was successfully updated! 
         *          -1 if Track with this ID does not exist!
         *          -2 if Track with this Name already exists!
         *          -3 if Album with this ID does not exist! 
         *          -4 if MediaType with this ID does not exist!
         *          -5 if Genre with this ID does not exist!
         *          -6 if Track could not be updated!
         */
        function update($trackId, $name, $albumId, $mediaTypeId, $genreId, $composer, $milliseconds, $bytes, $unitPrice) {
            // Check if there is a Track with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE TrackId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$trackId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Track id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check the count of Tracks with this name
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE Name = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);   

            if ($stmt->fetch()['total'] > 0) {
                // Track name already exists
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track with this Name already exists!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            }
            
            // Check if there is an Album with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE AlbumId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$albumId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Album id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Album with this ID does not exist!';
                $returnMsg['Code'] = '-3';
                return $returnMsg;
            }

            // Check if there is an MediaType with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM mediatype WHERE MediaTypeId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$mediaTypeId]);   

            if ($stmt->fetch()['total'] == 0) {
                // MediaType id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'MediaType with this ID does not exist!';
                $returnMsg['Code'] = '-4';
                return $returnMsg;
            }

            // Check if there is an Genre with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM genre WHERE GenreId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$genreId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Genre id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Genre with this ID does not exist!';
                $returnMsg['Code'] = '-5';
                return $returnMsg;
            }
           
            // Update Track
            try {
                $query = <<<'SQL'
                UPDATE track
                    SET Name = ?, AlbumId = ?, MediaTypeId = ?, GenreId = ?, Composer = ?, 
                        Milliseconds = ?, Bytes = ?, UnitPrice = ?
                    WHERE TrackId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$name, $albumId, $mediaTypeId, $genreId, $composer, 
                                $milliseconds, $bytes, $unitPrice, $trackId]);
                
                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Track was successfully updated!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track could not be updated!';
                $returnMsg['Code'] = '-6';
                $return = $returnMsg;
                debug($e);
            }

            $this->disconnect();
            return $return;
        }

        /**
         * Deletes a Track
         * 
         * @param   Id of the Track to delete
         * @return  Success if Track was successfully deleted! 
         *          -1 if Track with this ID does not exist!
         *          -2 if Track has been Purchased (has one or more Invoicelines)! Can not delete! - Referential Integrity problem
         *          -3 if Track could not be deleted!
         */
        function delete($id) {  
            // Check if there is an Track with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE TrackId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Track id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check if this Track has an Invoiceline - Referential Integrity problem
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM invoiceline WHERE TrackId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] > 0) {
                // This Track has Invoicelines
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track has been Purchased (has one or more Invoicelines)! Can not delete!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            }
                  
            // Deletes Track
            try {
                $query = <<<'SQL'
                    DELETE 
                    FROM track 
                    WHERE TrackId = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Track was successfully deleted!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Track could not be deleted!';
                $returnMsg['Code'] = '-3';
                $return = $returnMsg;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }
    }
?>