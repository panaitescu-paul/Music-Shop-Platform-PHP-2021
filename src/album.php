<?php
// TODO: Add API documentation

/**
 * Album class
 *
 * @author Paul Panaitescu
 * @version 1.0 5 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    // TODO: Add Try and Catch blocks for every query
    class Album extends DB {

        /**
         * Retrieves all albums 
         * 
         * @return  an array with all albums and their information
         */
        
        function getAll() {
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();
            return $stmt->fetchAll(); 
        }

        /**
         * Retrieves album by id 
         * 
         * @param   id of the album
         * @return  an album and their information
         */
        
        function get($id) {
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album
                WHERE AlbumId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();
            return $stmt->fetch();
        }

        /**
         * Retrieves the albums whose title includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with album information
         */
        function search($searchText) {
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album
                WHERE Title LIKE ?
                ORDER BY Title;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                
            $this->disconnect();
            return $stmt->fetchAll();                
        }
        
        /**
         * Inserts a new Album
         * 
         * @param   album info
         * @return  the ID of the new album
         */
        function create($info) {            
            $query = <<<'SQL'
                INSERT INTO album (Title, ArtistId) VALUES (?, ?);
                SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$info['title'], $info['artistId']]);
            $newID = $this->pdo->lastInsertId();
            
            $this->disconnect();
            return $newID;
        }

        /**
         * Updates an Album
         * 
         * @param   album info
         * @return  true if success, -1 otherwise
         */
        function update($info) {
            try {
                $query = <<<'SQL'
                UPDATE album
                    SET Title = ?, ArtistId = ?
                    WHERE AlbumId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['title'], $info['artistId'], $info['albumId']]);
                $return = true;

            } catch (Exception $e) {
                $return = -1;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }

        /**
         * Deletes an Album
         * 
         * @param   ID of the album to delete
         * @return  true if success, -1 otherwise
         */
        function delete($id) {            
            try {
                $query = <<<'SQL'
                    DELETE 
                    FROM album 
                    WHERE AlbumId = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
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