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
    }
?>