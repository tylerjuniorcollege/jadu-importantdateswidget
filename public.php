<?php
	$semesters = array('Spring',
					   'May',
					   'Summer',
					   'Fall',
					   'Winter');

	$terms = array(
		0 => '16 Week',
		1 => '12 Week',
		2 => '1st 8 Week',
		3 => '2nd 8 Week'
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

	$date_arrange = array();

	// The logic behind this is that if the semester is still Winter (which ends around January 5th), then we still need to show the last year.
	if(date("n") == 1 && date("j") <= 5) {
		// Display the last year's dates.
		$year = ((date("Y")) - 1);
	} else {
		$year = date("Y");
	}
	$years = array();
	for($i = 0; $i < 3; $i++) {
		$y = ($year + $i);
		$years[] = $y;

		$date_arrange[$y] = array();
		foreach($semesters as $sem) {
			$date_arrange[$y][strtolower($sem)] = array();
		}
	}

	// Midnight the day AFTER today.
	$current_date = mktime(00, 00, 00, date("n"), (date("j") + 1));

	$date_fmt = '%s %s, %s';

	// We need to get the settings.
	if(!isset($_POST['preview'])) {
		$newSettings = array();
		foreach($settings as $setting) {
			$newSettings[$setting->name] = $setting->value;
		}

		$settings = $newSettings;
	}

	// Formatting the dates first ...
	foreach($settings as $i => $val) {
		// We need to grab the event_year first.
		if(stripos($i, 'event_year-') !== FALSE) {
			$eventId = substr($i, (strrpos($i, '-') + 1));

			// We need to check to see if the event is still active ...
			$start_date = sprintf($date_fmt, $settings['event_start_month-' . $eventId], $settings['event_start_day-' . $eventId], $val);
			$start_date = strtotime($start_date);

			$date_arrange[$val][$settings['event_semester-' . $eventId]][$start_date] = array(
				'year' => $val,
				'start_month' => $settings['event_start_month-' . $eventId],
				'start_day' => $settings['event_start_day-' . $eventId],
				'end_month' => $settings['event_end_month-' . $eventId],
				'end_day' => $settings['event_end_day-' . $eventId],
				'name' => $settings['event_name-' . $eventId],
				'semester' => $settings['event_semester-' . $eventId],
				'terms' => explode(',', $settings['event_terms-' . $eventId]),
				'highlight' => $settings['event_highlight-' . $eventId]
			);
		} else
			continue;
	}

	$header_str = '<tr class="filter_dates %s header"><th colspan="2">%s Semester %s (<span class="term">All Terms</span>)</th></colspan>';
	$event_str = '<tr class="filter_dates %s event"><td><strong>%s</strong></td><td>%s</td></tr>';

	$option_str = '<option value="%s"%s>%s</option>';

	// Now we need to organize the array based on the dates.
	$display_events = array();

	// This will let us set what the current semester for the thing to display.
	$current_semester = null;

	foreach($date_arrange as $y => $event_semesters) {
		foreach($event_semesters as $s => $events) {
			// Sort the events from the first start date to the last.
			ksort($events);

			// Setup the display_events array.
			$class = ($y . '_' . $s);

			if(count($events) < 1) {
				continue; // Skip this if there are no events.
			}

			// Zebra Counter.
			$zebra = 1;

			$display_events[] = sprintf($header_str, $class, ucfirst($s), $y);

			// We need to have something that will remove the date for an event that occurs on another previous date.
			$prev_month = null;
			$prev_day = null;
			foreach($events as $start => $event) {
				$classes = array($class);

				$event_date = $event['start_month'] . ' ' . $event['start_day'];

				if(strlen($event['end_month']) !== 0) { // This means that the event ends in the next month.
					$event_date .= ' - ' . $event['end_month'] . ' ' . $event['end_day'];
				} elseif(strlen($event['end_month']) === 0 && strlen($event['end_day']) < 0) {
					$event_date .= ' - ' . $event['end_day'];
				}

				if($event['start_month'] === $prev_month &&
				   $event['start_day'] === $prev_day &&
				   strlen($event['end_month']) === 0 &&
				   strlen($event['end_day']) === 0) {

				   // This should mean that the event occurs on the same date as the previous day and it isn't a series of days ...
					$event_date = null;
				}

				// Add the terms as classes for javascript selection.
				foreach($event['terms'] as $term_id) {
					$classes[] = 'term-'.$term_id;
				}

				if($event['highlight'] === '1') {
					$classes[] = 'highlightRow';
				}

				// Adding the zebra highlighting on all even rows.
				if($zebra %  2 === 0 && $event['highlight'] !== '1') {
					$classes[] = 'zebra';
				}

				$display_events[] = sprintf($event_str, implode(' ', $classes), $event_date, $event['name']);

				$zebra++;
				$prev_month = $event['start_month'];
				$prev_day = $event['start_day'];

				// Since the events are organized by year/semester and then date, this will present the first semester with the first event that occurs AFTER the current date.
				if(is_null($current_semester) &&
				   $current_date < $start) {
					$current_semester = $s;
				}
			}
		}
	}
?>
<div id="filterForm">
	<span class="selection_dropdown">
		<select class="filterSelect" id="filterYear">
			<?php foreach($years as $y) {
				printf($option_str, $y, ($y === $year ? ' selected' : ''), $y);
				} ?>
		</select>
	</span>
	<span class="selection_dropdown">
		<select class="filterSelect" id="filterSemester">
			<?php foreach($semesters as $sem) {
				printf($option_str, strtolower($sem), ($current_semester == strtolower($sem) ? ' selected' : ''), $sem);
			} ?>
		</select>
	</span>
	<span class="selection_dropdown">
		<select class="filterSelect" id="filterTerms">
			<option value="all" selected>All Terms</option>
			<?php foreach($terms as $tid => $term) {
				printf($option_str, $tid, '', $term);
			} ?>
		</select>
	</span>
</div>
<table id="importantDates">
	<colgroup>
		<col style="width: 25%;">
		<col style="width: 75%">
	</colgroup>
	<tbody>
		<?php print implode("\n", $display_events) ?>
	</tbody>
</table>