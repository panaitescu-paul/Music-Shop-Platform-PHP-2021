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

        /**
         * Retrieve Album by id 
         * 
         * @param   id of the Album
         * @return  an Album and their information, 
         *          or -1 if the Album was not found
         */
        function get($id) {
            // Check the count of Albums
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE AlbumId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Albums not found
                http_response_code(404);
                return -1;
            }

            // Search Albums
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album
                WHERE AlbumId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetch();
        }

        /**
         * Retrieve the Albums whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Albums information, 
         *          or -1 if no Albums were found
         */
        function search($searchText) {
            // Check the count of Albums
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE Title LIKE ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);   

            if ($stmt->fetch()['total'] == 0) {
                // Albums not found
                http_response_code(404);
                return -1;
            }

            // Search Albums
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album
                WHERE Title LIKE ?
                ORDER BY Title;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                
            $this->disconnect();

            http_response_code(200);
            return $stmt->fetchAll();                
        }
        
        /**
         * Insert a new Album
         * 
         * @param   artistId - Artist Id
         * @param   title - Album title
         * @return  the Id of the new Album, 
         *          -1 if the ArtistId doesn't exist, 
         *          -2 if the Album title already exists
         */
        function create($artistId, $title) {
            // Check if there is an Artist with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE ArtistId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$artistId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Artist id doesn't exist
                http_response_code(404);
                return -1;
            }

            // Check the count of Albums with this title
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE Title = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$title]);   

            if ($stmt->fetch()['total'] > 0) {
                // Album title already exists
                http_response_code(409);
                return -2;
            }

            // Create Album
            $query = <<<'SQL'
                INSERT INTO album (Title, ArtistId) VALUES (?, ?);
                SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$title, $artistId]);
            $newID = $this->pdo->lastInsertId();
            
            $this->disconnect();
            http_response_code(200);
            return $newID;
        }
    }
?>