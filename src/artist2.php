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
         * @return  an array with all artists and their information, 
         *          or -1 if there are no artists
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
                return -1;
            }

            // Select all Artists
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll(); 
        }

        /**
         * Retrieve artist by id 
         * 
         * @param   id of the artist
         * @return  an artist and their information, 
         *          or -1 if the artist was not found
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
                return -1;
            }

            // Search Artists
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist
                WHERE ArtistId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetch();
        }

        /**
         * Retrieve the artists whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with artists information, 
         *          or -1 if no artists were found
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
                return -1;
            }

            // Search Artists
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist
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
         * Insert a new Artist
         * 
         * @param   artist name
         * @return  the Id of the new artist, 
         *          or -1 if the artist name already exists
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
                return -1;
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
            return $newID;
        }

        /**
         * Updates an Artist
         * 
         * @param   id - artist id
         * @param   name - artist name
         * @return  true if success, 
         *          -1 if the artist id doesn't exist, 
         *          -2 if an artist with this name already exists
         *          -3 if the artist could not be updated
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
                return -1;
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
                return -2;
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
                $return = true;

            } catch (Exception $e) {
                $return = -3;
                debug($e);
            }

            $this->disconnect();
            http_response_code(200);
            return $return;
        }

        /**
         * Deletes an Artist
         * 
         * @param   Id of the artist to delete
         * @return  true if success, 
         *          -1 if artist with this id doesn't exist
         *          -2 if this artist has an Album - Referential Integrity problem
         *          -3 if the artist could not be deleted
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
                return -1;
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
                return -2;
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
                $return = true;
            } catch (Exception $e) {
                $return = -3;
                debug($e);
            }
            $this->disconnect();
            http_response_code(200);
            return $return;
        }
    }
?>