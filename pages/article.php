<div class="container mt-3">    
    <?php 
        $page="article";
        $id = $_GET['id'];
        // Apstrādāt iesniegto komentāra formu
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(empty(trim($_POST["content"]))){
                $content_err = "Lūdzu ievadiet raksta saturu.";     
            }
            else{
                $content = trim($_POST["content"]);
            }
            
            // Pārbauda vai nav kļūdas, pirms pievieno datubāzei
            if(empty($content_err)){
                $sql = "INSERT INTO comments (user_id, article_id , content) VALUES (?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "iis", $_SESSION["id"], $id, $content);

                    if(mysqli_stmt_execute($stmt)){
                        header('location: index.php?page=article&id='.$id);
                    } else{
                        echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
        $title = $content = "";
        $title_err = $content_err = "";

        //iegūst raksta info  
        mysqli_query($link, "UPDATE articles SET view_count = view_count+1 WHERE id=$id");//skaita cik reižu apskatīts raksts

        $sql = "SELECT title, DATE_FORMAT(create_date,'%e.%m.%Y') AS create_date, content, view_count 
        FROM articles WHERE id=$id";
        $result = mysqli_query($link, $sql) or die('cannot get results!');

        $article = mysqli_fetch_assoc($result);

        mysqli_free_result($result);

    ?>
    <!-- raksts -->
    <h1><?php echo $article['title']; ?></h1>
    <p>Skatīts <?php echo $article['view_count']; ?> reizes</p>
    <p><a href="index.php?page=article-edit&id=<?php echo $id; ?>" class="btn btn-sm btn-primary">Labot rakstu</a></p>
    <p><?php echo $article['create_date']; ?></p>
    <p class="text-justify"><?php echo nl2br($article['content']); ?></p>

    <!-- komentāri -->
    <h2>Komentāri</h2>
    <?php
        //iegūst raksta komentārus
        $sql = "SELECT c.id, u.username, DATE_FORMAT(c.created_at,'%e.%m.%Y %H:%s') AS created_at, c.content
        FROM comments c JOIN users u on c.user_id=u.id WHERE c.article_id=$id";
        $result = mysqli_query($link, $sql) or die('cannot get results!');
        $comments = array();
        while($row = mysqli_fetch_assoc($result)) {
            $comments[$row['id']][] = $row;
        }
        //izvada iegūto info
        foreach ($comments as $c) {
            foreach ($c as $comment) {
                ?> 
                <article>
                    <p><?php echo $comment['username']; ?></p>
                    <p><?php echo $comment['created_at']; ?></p>
                    <p><?php echo nl2br($comment['content']); ?></p>
                    <a class="btn btn-danger" href="widgets/comment-delete.php?id=<?php echo $id; ?>" onclick="return  confirm('Vēlaties dzēst šo komentāru?')">Dzēst</a>
                </article>    
            <?php }    
        }
        mysqli_free_result($result);
    ?>
    <!-- pievienot jaunu komentāru -->
    <br>
    <h2>Pievienot jaunu komentāru: </h2>	
    <form action="index.php?page=article&id=<?php echo $id; ?>" method="post">   
        <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
            <label>Komentārs</label>
            <textarea rows="4" name="content" class="form-control" value="<?php echo $content; ?>"></textarea>
            <span class="help-block"><?php echo $content_err; ?></span>
        </div>   
        <input type="submit" class="btn btn-primary" value="Pievienot">
    </form>
</div>