<?php 
    $page="gallery";
    include 'widgets/photos.php'; 
?>

<?php if (isset($msg)){ // ši sekcija ir priekš ziņu izvades ?> 
    <p style="font-weight: bold;"><?php echo $msg?>
        <br>
        <a href="<?php echo $_SERVER['PHP_SELF']?>">reload page</a>
    </p>
<?php } ?>

<div class="container mt-3">
    <h2>Uploaded images:</h2>
    <form action="widgets/photos.php" method="post">
    <?php
        $result = mysql_query("SELECT id, image_time, title FROM {$table} ORDER BY id DESC");
        if (@mysql_num_rows($result) == 0)   // tabula ir tukša  
            echo '<ul><li>No images loaded</li></ul>';
        else {   
            echo '<ul>';    
            while(list($id, $image_time, $title) = mysql_fetch_row($result)){ // izvadam sarakstu 
                ?>
                <li>
                    <input type="radio" name="del" value="<?php echo $id; ?>">
                    <a href="widgets/photos.php?show=<?php echo $id; ?>">
                        <img src="widgets/photos.php?show=<?php echo $id; ?>" title="<?php echo $title; ?>"/>    
                    </a>
                    <small><?php echo $image_time; ?></small>
                </li>
            <?php } ?>
            </ul>
            <input type="submit" class="btn btn-danger" value="Delete selected">
        <?php } ?>
    </form>
    <br><hr/><br>
    <h2>Upload new image:</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label><br><input type="text" name="title" id="title" size="64">
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label><br><input type="file" name="photo" id="photo">
        </div>
        <input type="submit" class="btn btn-primary" value="upload">
    </form>
</div>  