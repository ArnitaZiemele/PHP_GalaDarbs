<nav class="navbar">
    <ul class="nav">
        <li class="nav-item"><a class="nav-link <?php if(!isset($_SESSION['activePage'])) {echo "active";} ?>" href="index.php">Sākums</a></li>
        <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='gallery') {echo "active";} ?>" href="index.php?page=gallery">Galerija</a></li>
        <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='events') {echo "active";} ?>" href="index.php?page=events&month=<?php echo date('m') ?>&year=<?php echo date('Y') ?>">Pasākumi</a></li>
        <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='articles') {echo "active";} ?>" href="index.php?page=articles">Raksti</a></li>
        <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='about') {echo "active";} ?>" href="index.php?page=about">Par mani</a></li>
    </ul>
    <?php
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ ?>
        <ul class="nav navbar-right">
            <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='profile') {echo "active";} ?>" href="index.php?page=profile"><?php echo $_SESSION['username'] ?></a></li>
            <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='logout') {echo "active";} ?>" href="index.php?page=logout">Iziet</a></li>
        </ul>
    <?php } 
    if(!isset($_SESSION["loggedin"])){ ?>
        <ul class="nav navbar-right">
            <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='register') {echo "active";} ?>" href="index.php?page=register">Reģistrēties</a></li>
            <li class="nav-item"><a class="nav-link <?php if($_SESSION['activePage']=='login') {echo "active";} ?>" href="index.php?page=login"><span class="glyphicon glyphicon-log-in"></span>Pierakstīties</a></li>
        </ul>
    <?php } ?>
</nav>