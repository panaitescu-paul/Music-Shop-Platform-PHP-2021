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


    }
?>