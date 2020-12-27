<?php>
    $page="article-search";        
    header("Content-Type: text/html;charset=UTF-8");
    error_reporting(E_ALL ^ E_DEPRECATED);

    //Iegūst atslēgvārdam atbilstošo info
    if (!empty($_REQUEST['svar'])) {
        $svar = mysqli_real_escape_string($link, $_REQUEST['svar']);     

        $sql = "SELECT * FROM articles WHERE title LIKE '%".$svar."%'"; 
        $result = mysqli_query($link, $sql) or die('cannot get results!'); 
        $articles = array();
        while ($row = mysqli_fetch_array($result)){  
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
