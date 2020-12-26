<?php
    $id = $_GET['id'];
    
    $db_link = mysql_connect("localhost","root","");
    if (!$db_link){
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("privatamajaslapa", $db_link);
    mysql_query("set names 'utf8'");

    $query = "DELETE FROM comments WHERE id=$id";
    mysql_query($query,$db_link) or die('cannot get results!');
    header('location: ../index.php?page=article&id='.$id);
?>