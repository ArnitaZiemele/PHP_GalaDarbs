<?php 
    $page="gallery";
    include 'widgets/photos.php'; 
?>

<?php if (isset($msg)){ // ši sekcija ir priekš ziņu izvades ?> 
    <p style="font-weight: bold;"><?php echo $msg?>
        <br>
        <a href="index.php?page=gallery">reload page</a>
    </p>
<?php } ?>

<div class="container mt-3">
    <h2>Uploaded images:</h2>
    <form action="widgets/photos.php" method="post">
    <?php
        $sql = "SELECT id, image_time, title FROM {$table} ORDER BY id DESC"; 
        $result = mysqli_query($link, $sql) or die('cannot get results!'); 
        if (@mysqli_num_rows($result) == 0)   // tabula ir tukša  
            echo '<ul><li>No images loaded</li></ul>';
        else {   
            echo '<ul>';    
            while(list($id, $image_time, $title) = mysqli_fetch_row($result)){ // izvadam sarakstu 
                ?>
                <li>
                    <?php if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
                        <input type="radio" name="del" value="<?php echo $id; ?>">
                    <?php } ?>
                    <a href="widgets/photos.php?show=<?php echo $id; ?>">
                        <img src="widgets/photos.php?show=<?php echo $id; ?>" title="<?php echo $title; ?>"/>    
                    </a>
                    <small><?php echo $image_time; ?></small>
                </li>
            <?php } ?>
            </ul>
            <?php if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
                <input type="submit" class="btn btn-danger" value="Delete selected">
            <?php } ?>
        <?php } ?>
    </form>
    <?php if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
        <br><hr/><br>
        <h2>Upload new image:</h2>
        <form action="index.php?page=gallery" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label><br><input type="text" name="title" id="title" size="64">
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label><br><input type="file" name="photo" id="photo">
            </div>
            <input type="submit" class="btn btn-primary" value="Augšupielādēt">
        </form>
    <?php } ?>
</div>  