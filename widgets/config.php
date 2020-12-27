<?php
    header("Content-Type: text/html;charset=UTF-8");
    error_reporting(E_ALL ^ E_DEPRECATED);
    define('DB_SERVER', '127.0.0.1');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'privatamajaslapa');
    
    //veido savienojumu ar datubāzi
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // pārbauda savienojumu
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    mysqli_set_charset($link, "utf8");
?>