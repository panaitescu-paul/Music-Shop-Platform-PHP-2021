<?php

/**
 * Album class
 *
 * @author Paul Panaitescu
 * @version 1.0 5 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    class Album extends DB {

        /**
         * Retrieve all Albums 
         * 
         * @return  an array with all Albums and their information, 
         *          or -1 if there are no Albums
         */
        function getAll() {
            // Check the count of Albums
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();   

            if ($stmt->fetch()['total'] == 0) {
                // Albums not found
                http_response_code(404);
                return -1;
            }

            // Select all Albums
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll(); 
        }
    }
?>