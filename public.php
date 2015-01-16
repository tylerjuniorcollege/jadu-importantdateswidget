<?php
	//var_dump($settings);
	$semesters = array(0 => 'Spring',
					   1 => 'May',
					   2 => 'Summer',
					   3 => 'Fall',
					   4 => 'Winter');

	$terms = array(
		0 => '16 Week',
		1 => '12 Week',
		2 => '8 Week'
	);

	$months = array(
		1 => 'January',
		2 => 'February',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'August',
		9 => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December'
	);

	$date_count = array();

	$year = date("Y");
	$years = array();
	for($i = -1; $i < 3; $i++) {
		$y = ($year + $i);
		$years[] = $y;

		$date_count[$y] = array();
		foreach($semester as $sem) {
			$date_count[$y][strtolower($sem)] = 0;
		}
	}

	// Midnight the day AFTER today.
	$current_date = mktime(00, 00, 00, date("n"), (date("j") + 1));
	
	// formatting the "settings" so we can display the dates properly.
	$dates = array();

	$date_fmt = '%s %s, %s';

	foreach($settings as $i => $val) {
		// We need to grab the event_year first.
		if(stripos($i, 'event_year-') !== FALSE) {
			$eventId = substr($i, (strrpos($i, '-') + 1));

			// We need to check to see if the event is still active ...
			$start_date = sprintf($date_fmt, $settings['event_start_month-' . $eventId], $settings['event_start_day-' . $eventId], $val);
			$start_date = strtotime($start_date);

			if(!empty($settings['event_end_month-' . $eventId])
				&& !empty($settings['event_end_day-' . $eventId])) {
				$end_date = sprintf($date_fmt, $settings['event_end_month-' . $eventId], $settings['event_end_day-' . $eventId], $val);
			} elseif(empty($settings['event_end_month-' . $eventId]) 
					  && !empty($settings['event_end_day-' . $eventId])) { // same month as the start.
				$end_date = sprintf($date_fmt, $settings['event_start_month-' . $eventId], $settings['event_end_day-' . $eventId], $val);
			} elseif(empty($settings['event_end_month-' . $eventId]) 
					  && empty($settings['event_end_day-' . $eventId])) {
				$end_date = NULL;
			}

			if(!is_null($end_date)) {
				$end_date = strtotime($end_date);
			}

			if(!is_null($end_date)
				&& $end_date < $current_date) {
				// Skip this, as the event has already passed ...
				continue;
			}

			if($start_date < $current_date
				&& is_null($end_date)) {
				// We need to skip this if this is the only date and it is passed.
				continue;
			}

			$dates[$start_date] = array(
				'event_year' => $val,
				'event_start_month' => $settings['event_start_month-' . $eventId],
				'event_start_day' => $settings['event_start_day-' . $eventId],
				'event_end_month' => $settings['event_end_month-' . $eventId],
				'event_end_day' => $settings['event_end_day-' . $eventId],
				'event_name' => $settings['event_name-' . $eventId],
				'event_semester' => $settings['event_semester-' . $eventId],
				'event_terms' => explode(',', $settings['event_terms-' . $eventId]),
				'event_highlight' => $settings['event_highlight-' . $eventId]
			);
			$date_count[$val][$settings['event_semester-' . $eventId]]++;

		} else
			continue;
	}

	// Now we need to organize the array based on the dates.
	ksort($dates);

	// Here we should filter down the date_count array to get valid years to display in the form.
	var_dump($date_count);

	// Next, we display the form and the event dates based on the semester and year.
	// Finally, for the filter form to work with terms, the dates will display 
?>
<div id="filterForm">

</div>
<table id="importantDates">
	<tbody>
		<tr class="header filter_dates 2015_fall "></tr>
	</tbody>
</table>