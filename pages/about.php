<div class="container mt-3">    
    <?php 
        $page="about";

        //iegūst par mani info  
        $sql = "SELECT content, data, DATE_FORMAT(last_edited,'%e.%m.%Y %H:%s') AS last_edited
        FROM about_me WHERE id=1";
        $result = mysqli_query($link, $sql) or die('cannot get results!');
        $about = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    ?>
    
    <h1>Informācija par to, kas es esmu un ko daru</h1>
    <?php if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
        <p><a href="index.php?page=about-edit" class="btn btn-sm btn-primary">Labot rakstu</a></p>
    <?php } ?>
    <p class="text-justify"><?php echo nl2br($about['content']) ?></p>
    <p>Pēdējo reizi labots: <?php echo $about['last_edited']; ?></p>
</div>