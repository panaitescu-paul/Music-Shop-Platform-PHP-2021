<?php
    define('LOG_FILE_NAME', 'log.htm');

    function leave($message = 'There was an error.') {
        echo json_encode($message);
    }

    // It debugs the received information to an HTML file
    function debug($info) {

        $fileName = LOG_FILE_NAME;
        $path = getcwd();

        // If the invoking php file is in the src directory, the log file is set in the root
        if (substr($path, strlen($path) - 4, 4) === '\src') {
            $fileName = '../' . $fileName;
        }

        $text = '';
        if (!file_exists($fileName)) {
            $text .= '<pre>';
        }
        $text .= '--- ' . date('Y-m-d h:i:s A', time()) . ' ---<br>';

        $logFile = fopen($fileName, 'a');

        if (gettype($info) === 'array') {
            $text .= print_r($info, true);
        } else {
            $text .= $info . '<br>';
        }
        fwrite($logFile, $text);

        fclose($logFile);
    }

    /**
     * Returns the REST API description
     */
    function APIDescription() {
        $apiBaseUrl = 'http://localhost/WAD-MA2';
        $entityArtists = '/artists';
        $entityAlbums = '/albums';
        $entityTracks = '/tracks';
        $entityCustomers = '/customers';
        $entityCustomers = '/customers';

        // API Desccription
        $apiDescription['api-description'] = array('method' => 'GET', 'url' => $apiBaseUrl);
        
        // Artists
        $apiDescription['get-artists'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityArtists);
        $apiDescription['search-artists'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityArtists . '/?name={search-text}');
        $apiDescription['get-artist'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityArtists . '/{artist-id}');
        $apiDescription['add-artist'] = array('method' => 'POST', 'url' => $apiBaseUrl . $entityArtists, 'request-body' => array('name' => ''));
        $apiDescription['update-artist'] = array('method' => 'POST', 'url' => $apiBaseUrl . $entityArtists . '/{artist-id}', 'request-body' => array('name' => ''));
        $apiDescription['delete-artist'] = array('method' => 'DELETE', 'url' => $apiBaseUrl . $entityArtists . '/{artist-id}');
        
        // Albums
        $apiDescription['get-albums'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityAlbums);
        $apiDescription['search-albums'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityAlbums . '/?title={search-text}');
        $apiDescription['get-album'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityAlbums . '/{album-id}');
        $apiDescription['add-album'] = array('method' => 'POST', 'url' => $apiBaseUrl . $entityAlbums, 'request-body' => array( 'artistId' => '', 
                                                                                                                                'title' => ''
                                                                                                                            ));
        $apiDescription['update-album'] = array('method' => 'POST', 'url' => $apiBaseUrl . $entityAlbums . '/{album-id}', 'request-body' => array(  'title' => '', 
                                                                                                                                                    'artistId' => ''
                                                                                                                                                ));
        $apiDescription['delete-album'] = array('method' => 'DELETE', 'url' => $apiBaseUrl . $entityAlbums . '/{album-id}');
        
        http_response_code(200);
        return json_encode($apiDescription);
    }

    /**
     * Returns a format error
     */
    function formatError() {
        $output['Error'] = 'Incorrect URL format';
        http_response_code(400);
        return json_encode($output);
    }

    /**
     * Sanitizes a string
     */
    function sanitize($text) {
        return htmlspecialchars($text, ENT_COMPAT|ENT_HTML5, 'UTF-8', false); 
        // ENT_COMPAT converts double quotes to entities
        // ENT_HTML5 treats the output as HTML5
        // 'UTF-8' sets the encoding to UTF-8
        // false turns off the double encoding of HTML character entities
        //       e.g., &amp; will not become &amp;amp;
    }
    
?>
