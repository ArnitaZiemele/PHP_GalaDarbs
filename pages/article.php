<div class="container mt-3">    
    <?php 
        header("Content-Type: text/html;charset=UTF-8");
        error_reporting(E_ALL ^ E_DEPRECATED);
        $page="article";
        $id = $_GET['id'];
        //iegūst raksta info
        $db_link = mysql_connect("localhost","root","");
        if (!$db_link){
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("privatamajaslapa", $db_link);
        mysql_query("set names 'utf8'");

        $query = "SELECT title, DATE_FORMAT(create_date,'%e.%m.%Y') AS create_date, content, view_count 
        FROM articles WHERE id=$id";
        $result = mysql_query($query,$db_link) or die('cannot get results!');

        $article = mysql_fetch_assoc($result);

        mysql_free_result($result);
    ?>
    <!-- raksts -->
    <h1><?php echo $article['title']; ?></h1>
    <p><a href="index.php?page=edit-article&id=<?php echo $id; ?>" class="btn btn-sm btn-primary">Labot rakstu</a></p>
    <p><?php echo $article['create_date']; ?></p>
    <p class="text-justify"><?php echo nl2br($article['content']); ?></p>

    
</div>