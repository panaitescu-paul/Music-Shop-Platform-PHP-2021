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
                http_response_code(404);
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
        // /**
        //  * Inserts a new Artist
        //  * 
        //  * @param   artist info
        //  * @return  the ID of the new artist
        //  */
        // function create($info) {            
        //     $query = <<<'SQL'
        //         INSERT INTO artist (Name) VALUES (?);
        //         SQL;

        //     $stmt = $this->pdo->prepare($query);
        //     $stmt->execute([$info['name']]);
        //     $newID = $this->pdo->lastInsertId();

        //     $this->disconnect();
        //     return $newID;
        // }

        // /**
        //  * Updates an Artist
        //  * 
        //  * @param   artist info
        //  * @return  true if success, -1 otherwise
        //  */
        // function update($info) {
        //     try {
        //         $query = <<<'SQL'
        //         UPDATE artist
        //             SET Name = ?
        //             WHERE ArtistId = ?
        //         SQL;

        //         $stmt = $this->pdo->prepare($query);
        //         $stmt->execute([$info['name'], $info['id']]);
        //         $return = true;

        //     } catch (Exception $e) {
        //         $return = -1;
        //         debug($e);
        //     }
        //     $this->disconnect();
        //     return $return;
        // }
                  
        // /**
        //  * Deletes an Artist
        //  * 
        //  * @param   ID of the artist to delete
        //  * @return  true if success, -1 otherwise
        //  */
        // function delete($id) {            
        //     try {
        //         $query = <<<'SQL'
        //             DELETE 
        //             FROM artist 
        //             WHERE ArtistId = ?;
        //         SQL;
        //         $stmt = $this->pdo->prepare($query);
        //         $stmt->execute([$id]);
        //         $return = true;
        //     } catch (Exception $e) {
        //         $return = -1;
        //         debug($e);
        //     }
        //     $this->disconnect();
        //     return $return;
        // }
    }
?>