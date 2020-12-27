<?php
    $c_id = $_GET['id'];
    $a_id = $_GET['article-id'];
    require_once "config.php";
    
    $sql = "DELETE FROM comments WHERE id=$c_id";
    mysqli_query($link, $sql) or die('cannot get results!');
    header('location: ../index.php?page=article&id='.$a_id);
?>