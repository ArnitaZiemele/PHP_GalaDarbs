<div class="container mt-3">
    <?php 
        //pasākumi no datubāzes
        $events = array();
        $sql = "SELECT id, title, event_date as order_date, DATE_FORMAT(event_date,'%e.%m.%Y') AS event_date
        FROM events WHERE event_date >= CURRENT_DATE ORDER BY order_date ASC LIMIT 3";
        $result = mysqli_query($link, $sql) or die('cannot get results!'); 
        while($row = mysqli_fetch_assoc($result)) {
            $events[$row['id']][] = $row;
        }
        //raksti no datubāzes
        $articles = array();
        $sql = "SELECT id, title, create_date as order_date, DATE_FORMAT(create_date,'%e.%m.%Y') AS create_date, content
        FROM articles ORDER BY order_date DESC LIMIT 3";
        $result = mysqli_query($link, $sql) or die('cannot get results!'); 
        while($row = mysqli_fetch_assoc($result)) {
            $articles[$row['id']][] = $row;
        }        
    ?>
    <h3>Tuvākie pasākumi, kuros piedalīšos:</h3>
    <?php
        foreach ($events as $e) {
            foreach ($e as $event) {
                ?> 
                <article> 
                    <h1"><?php echo $event['title']; ?></h1>
                    <p><?php echo $event['event_date']; ?></p>
                </article>    
                <?php 
            }   
        }
    ?>
    <h3>Jaunākie raksti:</h3>
    <?php
        foreach ($articles as $a) {
            foreach ($a as $article) {
                ?> 
                <article>
                    <a href="index.php?page=article&id=<?php echo $article['id']; ?>"> 
                        <h1 class="crop-text-2"><?php echo $article['title']; ?></h1>
                        <p class="text-justify crop-text-4"><?php echo $article['content']; ?></p>
                    </a>
                </article>    
                <?php 
            }   
        }
    ?>

</div>