<?php
    $page="article-edit";

    $id = $_GET['id'];
    $title_err = "";
    $content_err = "";
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
        if(empty($title_err) && empty($content_err)){
            $sql = "UPDATE articles SET title = ?, content = ? WHERE id = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $id);

                if(mysqli_stmt_execute($stmt)){
                    header('location: index.php?page=article&id='.$id);
                } else{
                    echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    //iegūst raksta info
    $sql = "SELECT title, content 
    FROM articles WHERE id=$id";
    $result = mysqli_query($link,$sql) or die('cannot get results!');
    $article = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
?>

<div class="container mt-3">
    <h2>Labot rakstu</h2>
    <p>Lūdzu aizpildiet laukus, ko vēlaties labot.</p>
    <form action="index.php?page=article-edit&id=<?php echo $id; ?>" method="post"> 
    <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
        <label>Nosaukums</label>
        <input type="text" name="title" class="form-control" value="<?php echo $article['title']; ?>">
        <span class="help-block"><?php echo $title_err; ?></span>
    </div>    
    <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
        <label>Saturs</label>
        <textarea rows="15" name="content" class="form-control"><?php echo $article['content']; ?></textarea>
        <span class="help-block"><?php echo $content_err; ?></span>
    </div>   
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Mainīt">
        <a class="btn btn-secondary" href="index.php?page=article&id=<?php echo $id; ?>">Atcelt</a>
        <a class="btn btn-danger" href="widgets/article-delete.php?id=<?php echo $id; ?>" onclick="return  confirm('Vēlaties dzēst šo rakstu?')">Dzēst</a>
    </div>
    </form>
</div> 