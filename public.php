<?php
	//var_dump($settings);
	
	$year = date("Y");
	$years = array();
	for($i = 0; $i < 3; $i++) {
		$years[] = ($year + $i);
	}

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
	
	// formatting the "settings" so we can display the dates properly.
	$dates = array();
	$date_counts = array(); // This will be a helper to determine how many events exist

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
				&& $end_date < time()) {
				// Skip this, as the event has already passed ...
				continue;
			}

			if($start_date )

			$dates[$eventId] = array(
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
		}
	}

	var_dump($dates);
?>