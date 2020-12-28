<?php 
    $page="gallery";
    include 'widgets/photos.php'; 
?>

<script src="assets/js/lightgallery.min.js"></script>
<link rel="stylesheet" href="assets/css/lightgallery.css">

<?php if (isset($msg)){ // ši sekcija ir priekš ziņu izvades ?>
    <div class="alert alert-secondary" role="alert">
        <?php echo $msg?>
        <br>
        <a href="index.php?page=gallery">Pārlādēt lapu</a>
    </div>
<?php } ?>
<h1 class="pb-3">Galerija</h1>
<?php
    $sql = "SELECT id, image_time, title FROM {$table} ORDER BY id DESC"; 
    $result = mysqli_query($link, $sql) or die('cannot get results!'); 
    if (@mysqli_num_rows($result) == 0)   // tabula ir tukša  
        echo '<ul><li>No images loaded</li></ul>';
    else {   
        echo '<div id="lightgallery" class="mb-3">';    
        while(list($id, $image_time, $title) = mysqli_fetch_row($result)){ // izvadam sarakstu 
            ?>
            <div class="light_gallery_item">
                <?php if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
                    <input id="image_<?php echo $id; ?>" type="radio" name="del" value="<?php echo $id; ?>">
                    <label for="image_<?php echo $id; ?>"></label>
                <?php } ?>
                <a class="image_wrapper" href="widgets/photos.php?show=<?php echo $id; ?>"
                    style="background-image: url('widgets/photos.php?show=<?php echo $id; ?>');">
                    <!-- <img src="widgets/photos.php?show=<?php //echo $id; ?>" title="<?php //echo $title; ?>" alt="<?php //echo $image_time; ?>"/>     -->
                </a>
            </div>
        <?php } ?>
        </div>
        <?php if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
            <input type="submit" class="btn btn-danger" value="Dzēst izvēlēto">
        <?php } ?>
    <?php } ?>
</form>
<?php if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
    <br><hr/><br>
    <h2>Pievienot jaunu attēlu</h2>
    <form action="index.php?page=gallery" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Nosaukums:</label><br><input type="text" name="title" id="title" size="64">
        </div>
        <div class="form-group">
            <label for="photo">Attēls:</label><br><input type="file" name="photo" id="photo">
        </div>
        <input type="submit" class="btn btn-primary" value="Augšupielādēt">
    </form>
<?php } ?>

<script>
    lightGallery(document.getElementById('lightgallery'), {download: false, selector: '#lightgallery .image_wrapper'});
</script>