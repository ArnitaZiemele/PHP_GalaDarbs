<?php 
	require_once "widgets/draw-calendar.php";
	$page="events";

	if(isset($_GET['month']) && isset($_GET['year'])){
		$month = (int) ($_GET['month'] ? $_GET['month'] : date('m'));
		$year = (int)  ($_GET['year'] ? $_GET['year'] : date('Y'));
	}
	else{
		$month = date('m');
		$year = date('Y');
	}

	// Apstrādāt iesniegto formu
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
		// Pārbaudīt vai nav tukši lauki
		if(empty(trim($_POST["title"]))){
			$title_err = "Lūdzu ievadiet nosaukumu.";
		}
		if(empty(trim($_POST["event_date"]))){
			$date_err = "Lūdzu ievadiet datumu.";     
		}
		else{
			$title = trim($_POST["title"]);
			$event_date = trim($_POST["event_date"]);
		}
		
		// Pārbauda vai nav kļūdas, pirms pievieno datubāzei
		if(empty($title_err) && empty($date_err)){
			$sql = "INSERT INTO events (title, event_date) VALUES (?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "ss", $title, $event_date);

				if(mysqli_stmt_execute($stmt)){
					header('location: index.php?page=events&month='.date(m).'&year='.date(Y));
				} else{
					echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
				}
				mysqli_stmt_close($stmt);
			}
		}
	}

	//uzzīmē calendāru ar kontrolēm
	echo '<h2 style="float:left; padding-right:30px;">'.date('F',mktime(0,0,0,$month,1,$year)).' '.$year.'</h2>';
	echo '<div style="float:left;">'.$controls.'</div>';
	echo draw_calendar($month,$year,$events);
	echo '<br /><br />';

	$title = $event_date = "";
	$title_err = $date_err = "";

if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin') { ?>
	<h2>Pievienot jaunu pasākumu: </h2>	
	<form action="index.php?page=events&month=<?php echo $month ?>&year=<?php echo $year ?>" method="post">
		<div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
			<label>Nosaukums</label>
			<input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
			<span class="help-block"><?php echo $title_err; ?></span>
		</div>    
		<div class="form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
			<label>Datums</label>
			<input type="date" name="event_date" class="form-control" value="<?php echo $event_date; ?>">
			<span class="help-block"><?php echo $date_err; ?></span>
		</div> 
		<input type="submit" class="btn btn-primary" value="Pievienot">
		<input type="reset" class="btn btn-secondary" value="Atjaunot">
	</form>
<?php } ?>