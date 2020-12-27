<?php
    $id = $_GET['id'];
    
    $sql = "DELETE FROM articles WHERE id=$id";
    mysqli_query($link, $sql) or die('cannot get results!'); 
    header('location: ../index.php?page=articles');
?>