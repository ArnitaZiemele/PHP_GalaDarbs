<div class="container mt-3">   
    <?php
        $page="article-search";
        require_once "widgets/config.php";
        header("Content-Type: text/html;charset=UTF-8");
        error_reporting(E_ALL ^ E_DEPRECATED);

        //iegūst info no datubāzes
        $db_link = mysql_connect("localhost","root","");
        if (!$db_link){
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("privatamajaslapa", $db_link);
        mysql_query("set names 'utf8'");

        if (!empty($_REQUEST['svar'])) {

            $svar = mysql_real_escape_string($_REQUEST['svar']);     

            $sql = "SELECT * FROM articles WHERE title LIKE '%".$svar."%'"; 
            $result = mysql_query($sql); 
            $articles = array();
            while ($row = mysql_fetch_array($result)){  
                $articles[$row['id']][] = $row;
            }  
        }
        //meklēšanas forma
    ?>
        <form action="index.php?page=article-search" method="post">  
            Search: <input type="text" name="svar" /><br />  
            <input type="submit" value="Submit" />  
        </form> 
        <h3>Rezultāti vaicājumam "<?php echo $svar; ?>"</h3>
    <?php
        //izvada iegūto info
        foreach ($articles as $a) {
            foreach ($a as $article) {
                ?> 
                <article>
                    <a href="index.php?page=article&id=<?php echo $article['id']; ?>"> 
                        <h1 class="crop-text-2"><?php echo $article['title']; ?></h1>
                        <p class="text-justify crop-text-4"><?php echo $article['content']; ?></p>
                    </a>
                </article>    
            <?php }    
        }
    ?>
</div>