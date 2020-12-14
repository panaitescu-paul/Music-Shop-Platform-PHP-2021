<?php
// TODO: add try and catch block to remaining nodes, and code status 500 for server error

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
         *          or -1 if there are no Tracks
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
                return -1;
            }

            // Select all Tracks
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll(); 
        }

        /**
         * Retrieve Track by id 
         * 
         * @param   id of the Track
         * @return  an Track and their information, 
         *          or -1 if the Track was not found
         */
        function get($id) {
            // Check the count of Tracks
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM Track WHERE TrackId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Tracks not found
                http_response_code(404);
                return -1;
            }

            // Search Tracks
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track
                WHERE TrackId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetch();
        }

        /**
         * Retrieve the Tracks whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Tracks information, 
         *          or -1 if no Tracks were found
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
                return -1;
            }

            // Search Tracks
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track
                WHERE Name LIKE ?
                ORDER BY Name;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll();                
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
         *          -1 if the Track name already exists
         *          -2 if the AlbumId doesn't exist, 
         *          -3 if the MediaTypeId doesn't exist, 
         *          -4 if the GenreId doesn't exist, 
         *          -5 if the Track could not be created
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
                return -1;
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
                return -2;
            }

            // Check if there is an MediaType with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM mediaType WHERE MediaTypeId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$mediaTypeId]);   

            if ($stmt->fetch()['total'] == 0) {
                // MediaType id doesn't exist
                http_response_code(404);
                return -3;
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
                return -4;
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
                $return = $newID;

            } catch (Exception $e) {
                http_response_code(500);
                $return = -5;
                debug($e);
            }
            
            $this->disconnect();
            http_response_code(200);
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
         * @return  true if success, 
         *          -1 if the Track id doesn't exists
         *          -2 if the Track name already exists
         *          -3 if the AlbumId doesn't exist, 
         *          -4 if the MediaTypeId doesn't exist, 
         *          -5 if the GenreId doesn't exist, 
         *          -6 if the Track could not be updated
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
                return -1;
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
                return -2;
            }
            
            // Check if there is an Album with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM Album WHERE AlbumId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$albumId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Album id doesn't exist
                http_response_code(404);
                return -3;
            }

            // Check if there is an MediaType with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM mediaType WHERE MediaTypeId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$mediaTypeId]);   

            if ($stmt->fetch()['total'] == 0) {
                // MediaType id doesn't exist
                http_response_code(404);
                return -4;
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
                return -5;
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
         * Deletes a Track
         * 
         * @param   Id of the Track to delete
         * @return  true if success, 
         *          -1 if Track with this id doesn't exist
         *          -2 if this Track has been Purchased (has an Invoiceline) - Referential Integrity problem
         *          -3 if the Track could not be deleted
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
                return -1;
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
                return -2;
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
    }
?>


<!-- 1 -->
<!-- 2 -->