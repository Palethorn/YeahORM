<?php


function scan_dir($dir, $callback, $recursive = false) {
    $files = scandir($dir);
    
    foreach($files as $file) {
        if($file == '.' || $file == '..') {
            continue;
        }

        if(is_dir($dir . DIRECTORY_SEPARATOR . $file) && $recursive) {
            scan_dir($dir . DIRECTORY_SEPARATOR . $file, $callback, $recursive);
        }

        $callback($dir, $file);
    }
}

function camelize($string) {
    return preg_replace_callback('/(^|_)([a-z])/', function($matches) {
        return strtoupper($matches[2]);
    }, $string);
}