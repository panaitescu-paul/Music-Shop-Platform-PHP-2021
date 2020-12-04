<?php
// TODO: Add API documentation

/**
 * Artist class
 *
 * @author Paul Panaitescu
 * @version 1.0 1 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    // TODO: Add Try and Catch blocks for every query
    class Artist extends DB {

        /**
         * Retrieves all artists 
         * 
         * @return  an array with all artists and their information
         */
        
        function getAll() {
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $this->disconnect();
            return $stmt->fetchAll(); 
        }

        /**
         * Retrieves artist by id 
         * 
         * @param   id of the artist
         * @return  an artist and their information
         */
        
        function get($id) {
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist
                WHERE ArtistId = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);                
            $this->disconnect();
            return $stmt->fetch();
        }

        /**
         * Retrieves the artists whose name includes a certain text
         * 
         * @param   searchText upon which to execute the search
         * @return  an array with person information
         */
        function search($searchText) {
            $query = <<<'SQL'
                SELECT ArtistId, Name
                FROM artist
                WHERE Name LIKE ?
                ORDER BY Name;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                

            $this->disconnect();

            return $stmt->fetchAll();                
        }
        
        /**
         * Inserts a new Artist
         * 
         * @param   artist info
         * @return  the ID of the new artist
         */
        function create($info) {            
            $query = <<<'SQL'
                INSERT INTO artist (Name) VALUES (?);
                SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$info['name']]);
            $newID = $this->pdo->lastInsertId();
            
            $this->disconnect();
            return $newID;
        }

        /**
         * Updates an Artist
         * 
         * @param   artist info
         * @return  true if success, -1 otherwise
         */
        function update($info) {
            try {
                $query = <<<'SQL'
                UPDATE artist
                    SET Name = ?
                    WHERE ArtistId = ?
                SQL;

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['name'], $info['id']]);
                $return = true;

            } catch (Exception $e) {
                $return = -1;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }

        /**
         * Deletes an Artist
         * 
         * @param   ID of the artist to delete
         * @return  true if success, -1 otherwise
         */
        function delete($id) {            
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
                $return = -1;
                debug($e);
            }
            $this->disconnect();
            return $return;
        }
    }
?>