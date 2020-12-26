<div class="container mt-3">    
    <?php 
    	require_once "widgets/config.php";
        header("Content-Type: text/html;charset=UTF-8");
        error_reporting(E_ALL ^ E_DEPRECATED);
        $page="articles";
         
        // Apstrādāt iesniegto formu
        if($_SERVER["REQUEST_METHOD"] == "POST"){
                
            // Pārbaudīt vai nav tukši lauki
            if(empty(trim($_POST["title"]))){
                $title_err = "Lūdzu ievadiet nosaukumu.";
            }
            if(empty(trim($_POST["content"]))){
                $content_err = "Lūdzu ievadiet raksta saturu.";     
            }
            else{
                $title = trim($_POST["title"]);
                $content = trim($_POST["content"]);
            }
            
            // Pārbauda vai nav kļūdas, pirms pievieno datubāzei 
            mysql_query("set names 'utf8'");
            if(empty($title_err) && empty($content_err)){
                $sql = "INSERT INTO articles (title, content) VALUES (?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "ss", $title, $content);

                    if(mysqli_stmt_execute($stmt)){
                        header('location: index.php?page=articles');
                    } else{
                        echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            mysqli_close($link);
        }

        $title = $content = "";
        $title_err = $content_err = "";

        //iegūst info no datubāzes
        $db_link = mysql_connect("localhost","root","");
        if (!$db_link){
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("privatamajaslapa", $db_link);
        mysql_query("set names 'utf8'");

        $articles = array();
        $query = "SELECT id, title, DATE_FORMAT(create_date,'%e.%m.%Y') AS create_date, content
        FROM articles";
        $result = mysql_query($query,$db_link) or die('cannot get results!');
        while($row = mysql_fetch_assoc($result)) {
            $articles[$row['id']][] = $row;
        }
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

    <br>
    <h2>Pievienot jaunu rakstu: </h2>	
    <form action="index.php?page=articles" method="post">
        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
            <label>Nosaukums</label>
            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
            <span class="help-block"><?php echo $title_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
            <label>Saturs</label>
            <textarea rows="4" name="content" class="form-control" value="<?php echo $content; ?>"></textarea>
            <span class="help-block"><?php echo $content_err; ?></span>
        </div>   
        <input type="submit" class="btn btn-primary" value="Pievienot">
        <input type="reset" class="btn btn-secondary" value="Atjaunot">
    </form>
</div>