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

?>
