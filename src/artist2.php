<?php

/**
 * Artist class
 *
 * @author Paul Panaitescu
 * @version 1.0 1 DEC 2020:
 */
    require_once('connection.php');
    require_once('functions.php');

    // TODO: Add Try and Catch blocks for every query
    // TODO: Add Status codes
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
?>