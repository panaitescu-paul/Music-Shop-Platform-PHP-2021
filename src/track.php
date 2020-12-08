<?php
// TODO: Add API documentation

/**
 * Track class
 *
 * @author Paul Panaitescu
 * @version 1.0 5 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    // TODO: Add Try and Catch blocks for every query
    class Track extends DB {

        /**
         * Retrieves all tracks 
         * 
         * @return  an array with all tracks and their information
         */
        
        function getAll() {
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();
            return $stmt->fetchAll(); 
        }

        /**
         * Retrieves track by id 
         * 
         * @param   id of the track
         * @return  a track and their information
         */
        
        function get($id) {
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track
                WHERE TrackId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();
            return $stmt->fetch();
        }

        /**
         * Retrieves the tracks whose title includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with track information
         */
        function search($searchText) {
            $query = <<<'SQL'
                SELECT TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice
                FROM track
                WHERE Name LIKE ?
                ORDER BY Name;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                
            $this->disconnect();
            return $stmt->fetchAll();                
        }

        /**
         * Inserts a new Track
         * 
         * @param   Track info
         * @return  the ID of the new Track
         */
        function create($info) {            
            $query = <<<'SQL'
                INSERT INTO track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?);
                SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$info['name'], $info['albumId'], $info['mediaTypeId'], $info['genreId'], 
                            $info['composer'], $info['milliseconds'], $info['bytes'], $info['unitPrice']]);
            $newID = $this->pdo->lastInsertId();
            
            $this->disconnect();
            return $newID;
        }

        /**
         * Updates an Track
         * 
         * @param   Track info
         * @return  true if success, -1 otherwise
         */
        function update($info) {
            try {
                $query = <<<'SQL'
                UPDATE track
                    SET Name = ?, AlbumId = ?, MediaTypeId = ?, GenreId = ?, Composer = ?, Milliseconds = ?, Bytes = ?, UnitPrice = ?
                    WHERE TrackId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['name'], $info['albumId'], $info['mediaTypeId'], $info['genreId'], 
                                $info['composer'], $info['milliseconds'], $info['bytes'], $info['unitPrice']]);
                $return = true;

            } catch (Exception $e) {
                $return = -1;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }


    }
?>