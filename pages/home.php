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
<h2>Tuvākie pasākumi, kuros piedalīšos:</h2>
<div class="closest-events">
<?php
    foreach ($events as $e) {
        foreach ($e as $event) {
            ?> 
            <div class="event"> 
                <h3><?php echo $event['title']; ?></h3>
                <p><i><?php echo $event['event_date']; ?></i></p>
            </div>    
            <?php 
        }   
    }
?>
</div>
<br/>
<hr/>
<br/>
<h2>Jaunākie raksti:</h2>
<div class="articles-list">
<?php
    foreach ($articles as $a) {
        foreach ($a as $article) {
            ?> 
            <div class="article"> 
                <a href="index.php?page=article&id=<?php echo $article['id']; ?>"> 
                    <h3 class="crop-text-2"><?php echo $article['title']; ?></h3>
                    <p class="text-justify crop-text-4 m-0"><?php echo $article['content']; ?></p>
                </a>
            </div>    
            <?php 
        }   
    }
?>
</div>
