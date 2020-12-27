<?php
    $id = $_GET['id'];
    require_once "config.php";
    
    //iegūst raksta info
    $sql = "DELETE FROM events WHERE id=$id";
    mysqli_query($link, $sql) or die('cannot get results!');
    header('location: ../index.php?page=events&month='.date(m).'&year='.date(Y));
?>