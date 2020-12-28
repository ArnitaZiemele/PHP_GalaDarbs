<?php
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
    <form action="index.php?page=article-search" method="post" style="display:table; width:100%;">
        <div class="float-right d-flex align-items-center">
            <label class="m-0">Meklēt: </label>  
            <input class="form-control ml-2 mr-2" type="text" name="svar" style="width: 200px;" value="<?php echo $svar ?>"/>
            <input class="btn btn-primary" type="submit" value="Meklēt" />  
        </div>
    </form> 
    <br/>
    <div class="articles-list">
        <?php 
        if (!empty($_REQUEST['svar'])) {
            //izvada iegūto info
            foreach ($articles as $a) {
                foreach ($a as $article) {
                    ?> 
                    <div class="article">
                        <a href="index.php?page=article&id=<?php echo $article['id']; ?>"> 
                            <h1 class="crop-text-2"><?php echo $article['title']; ?></h1>
                            <p class="text-justify crop-text-4"><?php echo $article['content']; ?></p>
                        </a>
                </div>    
        <?php } 
        } 
    }   
    else {
        header('location: index.php?page=articles'); 
    } 
?>
