<?php
    $page="about-edit";

    $content_err = "";
    // Apstrādāt iesniegto formu
    if($_SERVER["REQUEST_METHOD"] == "POST"){
                
        // Pārbaudīt vai nav tukši lauki
        if(empty(trim($_POST["content"]))){
            $content_err = "Lūdzu ievadiet raksta saturu.";     
        }
        else{
            $content = trim($_POST["content"]);
        }
        // Pārbauda vai nav kļūdas, pirms pievieno datubāzei 
        if(empty($content_err)){
            $sql = "UPDATE about_me SET content = ? WHERE id = 1";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $content);

                if(mysqli_stmt_execute($stmt)){
                    header('location: index.php?page=about');
                } else{
                    echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    //iegūst par mani info  
    $sql = "SELECT content, data
    FROM about_me WHERE id=1";
    $result = mysqli_query($link, $sql) or die('cannot get results!');
    $about = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
?>

<h2>Labot par mani</h2>
<form action="index.php?page=about-edit" method="post" enctype='multipart/form-data'>  
    <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
        <label>Saturs</label>
        <textarea rows="15" name="content" class="form-control"><?php echo $about['content']; ?></textarea>
        <span class="help-block"><?php echo $content_err; ?></span>
    </div>   
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Mainīt">
        <a class="btn btn-secondary" href="index.php?page=about">Atcelt</a>
    </div>
</form>