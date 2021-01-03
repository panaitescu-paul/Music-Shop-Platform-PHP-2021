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
         *          or -1 if There are no Albums in the DB!
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
                $returnMsg = array();
                $returnMsg['Error: -1'] = 'There are no Albums in the DB!';
                return $returnMsg;
            }

            // Select all Albums
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['Title'] = sanitize($result['Title']);
            }

            $this->disconnect();
            http_response_code(200);
            return $results; 
        }

        /**
         * Retrieve Album by id 
         * 
         * @param   id of the Album
         * @return  an Album and their information, 
         *          or -1 if Album with this ID was not found!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Album with this ID was not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Albums
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album
                WHERE AlbumId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);    
            $result = $stmt->fetch();  
            
            // Sanitize the strings that come from the DB
            $result['Title'] = sanitize($result['Title']);

            $this->disconnect();
            http_response_code(200);
            return $result;
        }

        /**
         * Retrieve the Albums whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with Albums information, 
         *          or -1 if Albums with this Title were not found!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Albums with this Title were not found!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Search Albums
            $query = <<<'SQL'
                SELECT AlbumId, Title, ArtistId
                FROM album
                WHERE Title LIKE ?
                ORDER BY AlbumId;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']); 
            $results = $stmt->fetchAll();  
            
            // Sanitize the strings that come from the DB
            foreach($results as &$result) {
                $result['Title'] = sanitize($result['Title']);
            }

            $this->disconnect();
            http_response_code(200);
            return $results;                
        }
        
        /**
         * Insert a new Album
         * 
         * @param   artistId - Artist Id
         * @param   title - Album title
         * @return  the Id of the new Album, 
         *          -1 if Artist with this ID does not exist!
         *          -2 if Album with this Title already exists!
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Album with this Title already exists!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
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
            $returnMsg = array();
            $returnMsg['newID'] = $newID;
            return $returnMsg;
        }

        /**
         * Updates an Album
         * 
         * @param   albumId - Album id
         * @param   title - Album title
         * @param   artistId - Artist id
         * @return  Success if Album was successfully updated! 
         *          -1 if Album with this ID does not exist! 
         *          -2 if Artist with this ID does not exist! 
         *          -3 if Album with this Title already exists!
         *          -4 if Album could not be updated!
         */
        function update($albumId, $title, $artistId) {
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
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check if there is an Artist with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM artist WHERE ArtistId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$artistId]);   

            if ($stmt->fetch()['total'] == 0) {
                // Artist id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Artist with this ID does not exist!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
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
                $returnMsg = array();
                $returnMsg['Error'] = 'Album with this Title already exists!';
                $returnMsg['Code'] = '-3';
                return $returnMsg;
            }

            // Update Album
            try {
                $query = <<<'SQL'
                UPDATE album
                    SET Title = ?, ArtistId = ?
                    WHERE AlbumId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$title, $artistId, $albumId]);

                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Album was successfully updated!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Album could not be updated!';
                $returnMsg['Code'] = '-4';
                $return = $returnMsg;
                debug($e);
            }

            $this->disconnect();
            return $return;
        }

        /**
         * Deletes an Album
         * 
         * @param   Id of the Album to delete
         * @return  Success if Album was successfully deleted! 
         *          -1 if Album with this ID does not exist!
         *          -2 if Album has one or more Tracks! Can not delete! - Referential Integrity problem
         *          -3 if Album could not be deleted!
         */
        function delete($id) {  
            // Check if there is an Album with this id
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM album WHERE AlbumId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] == 0) {
                // Album id doesn't exist
                http_response_code(404);
                $returnMsg = array();
                $returnMsg['Error'] = 'Album with this ID does not exist!';
                $returnMsg['Code'] = '-1';
                return $returnMsg;
            }

            // Check if this Album has a Track
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM track WHERE AlbumId = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);   

            if ($stmt->fetch()['total'] > 0) {
                // This Album has Tracks
                http_response_code(409);
                $returnMsg = array();
                $returnMsg['Error'] = 'Album has one or more Tracks! Can not delete!';
                $returnMsg['Code'] = '-2';
                return $returnMsg;
            }
                  
            // Deletes Album
            try {
                $query = <<<'SQL'
                    DELETE 
                    FROM album 
                    WHERE AlbumId = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                http_response_code(200);
                $returnMsg = array();
                $returnMsg['Success'] = 'Album was successfully deleted!';
                $return = $returnMsg;

            } catch (Exception $e) {
                http_response_code(500);
                $returnMsg = array();
                $returnMsg['Error'] = 'Album could not be deleted!';
                $returnMsg['Code'] = '-3';
                $return = $returnMsg;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }
    }
?>