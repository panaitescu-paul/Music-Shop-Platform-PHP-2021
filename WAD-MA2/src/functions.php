<?php

    function leave() {
        header('Location: ../index.html');
    }

    // It debugs the received information to an HTML file
    function debug($info) {
        define('LOG_FILE_NAME', 'log.htm');

        $text = '';
        if (!file_exists(LOG_FILE_NAME)) {
            $text .= '<pre>';
        }
        $text .= '--- ' . date('Y-m-d h:i:s A', time()) . ' ---<br>';

        $logFile = fopen(LOG_FILE_NAME, 'a');

        if (gettype($info) === 'array') {
            $text .= print_r($info, true);
        } else {
            $text .= $info;
        }
        fwrite($logFile, $text);

        fclose($logFile);
    }

?>
