<?php
    $id = $_GET['id'];
    
    $sql = "DELETE FROM comments WHERE id=$id";
    mysqli_query($link, $sql) or die('cannot get results!');
    header('location: ../index.php?page=article&id='.$id);
?>