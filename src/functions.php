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
        // TODO: make the description for all nodes

        $apiBaseUrl = 'http://{server}/WAD-MA2';
        $entityArtists = '/artists';
        // $entityFilms = '/films';
        // $entityPersons = '/persons';

        $apiDescription['api-description'] = array('method' => 'GET', 'url' => $apiBaseUrl);
        $apiDescription['search-artists'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityArtists . '?name={artist-search-text}');
        $apiDescription['get-artist'] = array('method' => 'GET', 'url' => $apiBaseUrl . $entityArtists . '/{artist-id}');
        $apiDescription['add-artist'] = array('method' => 'POST', 'url' => $apiBaseUrl . $entityArtists, 'request-body' => array('name' => ''));
        $apiDescription['update-artist'] = array('method' => 'POST', 'url' => $apiBaseUrl . $entityArtists . '/{artist-id}', 'request-body' => array('name' => ''));
        $apiDescription['delete-artist'] = array('method' => 'DELETE', 'url' => $apiBaseUrl . $entityArtists . '/{artist-id}');

        return json_encode($apiDescription);
    }

    /**
     * Returns a format error
     */
    function formatError() {
        $output['error'] = 'Incorrect format';
        return json_encode($output);
    }
?>
