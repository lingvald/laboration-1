<?php

// Gör det möjligt att se tidigare skapade filer.

$generated_csvs = [];

function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');
    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
    }
    arsort($files);
    $files = array_keys($files);
    return ($files) ? $files : false;
}

$dest_dir_files = scan_dir('created_csv_files/');

if($dest_dir_files[0]){
    foreach($dest_dir_files as $files){
        if(strlen($files) > 1){
            $prefix = $files[0] . $files[1];
            if ($prefix[0] == mb_strtoupper($prefix[0]) && $prefix[0] !== '.') {
                $generated_csvs[] = $files;
            }
        }
    }
    include 'views/prev_files.php';  
}
?>

