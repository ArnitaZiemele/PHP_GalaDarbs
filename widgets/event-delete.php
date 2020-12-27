<?php
    $id = $_GET['id'];
    
    //iegūst raksta info
    $sql = "DELETE FROM events WHERE id=$id";
    mysqli_query($link, $sql) or die('cannot get results!');
    header('location: ../index.php?page=events&month='.date(m).'&year='.date(Y));
?>