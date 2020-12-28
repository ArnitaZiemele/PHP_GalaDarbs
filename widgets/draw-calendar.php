<?php
	function draw_calendar($month,$year,$events = array()){// Zīmē kalendāru kā tabulu
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
		//tabulas galvene
		$headings = array('Pirmdiena','Otrdiena','Trešdiena','Ceturtdiena','Piektdiena','Sestdiena', 'Svētdiena');
		$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
		//dienas un nedēļas
		$running_day = date('w',mktime(0,0,0,$month,0,$year));
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();
		//pirmā nedēļa
		$calendar.= '<tr class="calendar-row">';
		// tukšie lauciņi līdz mēneša pirmajai dienai
		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
			$days_in_this_week++;
		endfor;
		// turpina ar visām dienām
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			$calendar.= '<td class="calendar-day"><div style="position:relative;height:100px;">';
				$calendar.= '<div class="day-number">'.$list_day.'</div>';
				
				$event_day = $year.'-'.$month.'-'.$list_day;
				if(isset($events[$event_day])) {
					foreach($events[$event_day] as $event) {
						$calendar.= '<div class="event">'.$event['title'];
						if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin'){
							$calendar.= '<a href="widgets/event-delete.php?id='.$event['id'].'">X</a></div>';
						}
					}
				}
				else {
					$calendar.= str_repeat('<p>&nbsp;</p>',2);
				}
			$calendar.= '</div></td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;

		//pabeidz atlikušās nedēļas dienas
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
			endfor;
		endif;
		//pēdējā rinda
		$calendar.= '</tr>';
		//tabulas beigas
		$calendar.= '</table>';
		/** DEBUG **/
		$calendar = str_replace('</td>','</td>'."\n",$calendar);
		$calendar = str_replace('</tr>','</tr>'."\n",$calendar);
		
		return $calendar;
	}

	function random_number() {
		srand(time());
		return (rand() % 7);
	}

	/* date settings */
	if(isset($_GET['month']) && isset($_GET['year'])){
		$month = (int) ($_GET['month'] ? $_GET['month'] : date('m'));
		$year = (int)  ($_GET['year'] ? $_GET['year'] : date('Y'));
	}
	else{
		$month = date('m');
		$year = date('Y');
	}

	//nākošais mēnesis
	$next_month_link = '<a href="index.php?page=events&month='.($month != 12 ? $month + 1 : 1).'&year='.($month != 12 ? $year : $year + 1).'" class="control">Nākošais mēnesis &gt;&gt;</a>';

	//iepriekšējais mēnesis
	$previous_month_link = '<a href="index.php?page=events&month='.($month != 1 ? $month - 1 : 12).'&year='.($month != 1 ? $year : $year - 1).'" class="control">&lt;&lt; 	Iepriekšējais mēnesis</a>';

	//savieno kontrolierus
	$controls = '<form method="get">'.$previous_month_link.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$next_month_link.' </form>';

	//iegūst visus mēneša eventus
	$events = array();
	$sql = "SELECT id, title, DATE_FORMAT(event_date,'%Y-%m-%e') AS event_date FROM events WHERE event_date LIKE '$year-$month%'";
	$result = mysqli_query($link, $sql) or die('cannot get results!');
	while($row = mysqli_fetch_assoc($result)) {
		$events[$row['event_date']][] = $row;
	}
?>