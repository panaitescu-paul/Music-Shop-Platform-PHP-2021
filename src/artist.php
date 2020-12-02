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

    class Artist extends DB {
        // /**
        //  * Retrieves the movies whose title includes a certain text
        //  * 
        //  * @param   text upon which to execute the search
        //  * @return  an array with movie information
        //  */
        // function search($searchText) {
        //     $query = <<<'SQL'
        //         SELECT movie_id, title, release_date, runtime
        //         FROM movie
        //         WHERE title LIKE ?
        //         ORDER BY title;
        //     SQL;

        //     $stmt = $this->pdo->prepare($query);
        //     $stmt->execute(['%' . $searchText . '%']);                
            
        //     $results = $stmt->fetchAll();                
            
        //     $this->disconnect();

        //     return $results;                
        // }

        // /**
        //  * Inserts a new movie
        //  * 
        //  * @param   movie info
        //  * @return  the ID of the new movie
        //  */
        // function create($info) {            

        //     try {
        //         $this->pdo->beginTransaction();

        //         $query = <<<'SQL'
        //             INSERT INTO artist (title, overview, release_date, runtime) VALUES (?, ?, ?, ?);
        //         SQL;

        //         $stmt = $this->pdo->prepare($query);
        //         $stmt->execute([$info['title'], $info['overview'], $info['releaseDate'], $info['runtime']]);

        //         $newID = $this->pdo->lastInsertId();

        //         // Directors
        //         if (isset($info['directors'])) {
        //             foreach($info['directors'] as $director) {
        //                 $query = <<<'SQL'
        //                     INSERT INTO movie_director (movie_id, person_id) VALUES (?, ?);
        //                 SQL;
        //                 $stmt = $this->pdo->prepare($query);
        //                 $stmt->execute([$newID, $director]);
        //             }
        //         }

        //         // Actors
        //         if (isset($info['actors'])) {
        //             foreach($info['actors'] as $actor) {
        //                 $query = <<<'SQL'
        //                     INSERT INTO movie_cast (movie_id, person_id) VALUES (?, ?);
        //                 SQL;
        //                 $stmt = $this->pdo->prepare($query);
        //                 $stmt->execute([$newID, $actor]);
        //             }
        //         }

        //         $this->pdo->commit();
                
        //     } catch (Exception $e) {
        //         $newID = -1;
        //         $this->pdo->rollBack();
        //         debug($e);
        //     }

        //     $this->disconnect();

        //     return $newID;
        // }

        /**
         * Retrieves the artists whose name includes a certain text
         * 
         * @param   text upon which to execute the search
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
         * Retrieves all artists 
         * 
         * @return  an array with all artists and their information
         */
        
        function get() {
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
         * Updates a movie
         * 
         * @param   movie info
         * @return  true if success, -1 otherwise
         */
        function update($info) {

            try {
                $this->pdo->beginTransaction();

                $query = <<<'SQL'
                    UPDATE movie
                    SET title = ?,
                        overview = ?,
                        release_date = ?,
                        runtime = ?
                    WHERE movie_id = ?
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['title'], $info['overview'], $info['releaseDate'], $info['runtime'], $info['movieId']]);

                // Directors
                $query = <<<'SQL'
                    DELETE FROM movie_director
                    WHERE movie_id = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['movieId']]);

                if (isset($info['directors'])) {
                    foreach($info['directors'] as $director) {
                        $query = <<<'SQL'
                            INSERT INTO movie_director (movie_id, person_id) VALUES (?, ?);
                        SQL;                        
                        $stmt = $this->pdo->prepare($query);
                        $stmt->execute([$info['movieId'], $director]);
                    }
                }

                // Actors
                $query = <<<'SQL'
                    DELETE FROM movie_cast
                    WHERE movie_id = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$info['movieId']]);

                if (isset($info['actors'])) {
                    foreach($info['actors'] as $actor) {
                        $query = <<<'SQL'
                            INSERT INTO movie_cast (movie_id, person_id) VALUES (?, ?);
                        SQL;                        
                        $stmt = $this->pdo->prepare($query);
                        $stmt->execute([$info['movieId'], $actor]);
                    }
                }

                $this->pdo->commit();

                $return = true;

            } catch (Exception $e) {
                $return = -1;
                $this->pdo->rollBack();
                debug($e);
            }

            $this->disconnect();

            return $return;
        }

        /**
         * Deletes a movie
         * 
         * @param   ID of the movie to delete
         * @return  true if success, -1 otherwise
         */
        function delete($id) {            
            try {
                $this->pdo->beginTransaction();

                $query = <<<'SQL'
                    DELETE FROM movie_director WHERE movie_id = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                $query = <<<'SQL'
                    DELETE FROM movie_cast WHERE movie_id = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                $query = <<<'SQL'
                    DELETE FROM movie WHERE movie_id = ?;
                SQL;
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);

                $this->pdo->commit();

                $return = true;

            } catch (Exception $e) {
                $return = -1;
                $this->pdo->rollBack();
                debug($e);
            }

            $this->disconnect();

            return $return;
        }
    }
?>