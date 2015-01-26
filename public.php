<?php
	$semesters = array('Spring',
					   'May',
					   'Summer',
					   'Fall',
					   'Winter');

	$display_semesters = array('Fall', 'Winter', 'Spring', 'May', 'Summer');

	$terms = array(
		0 => '16 Week',
		1 => '12 Week',
		2 => '1st 8 Week',
		3 => '2nd 8 Week'
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

	$date_fmt = 'F j';

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
			$start_date = strtotime($settings['event_start_date-' . $eventId]);

			// Checking to see if the "Event_URL" has been set or is null.
			$event_url = null;
			if(isset($settings['event_url-' . $eventId])) {
				$event_url = $settings['event_url-' . $eventId];
			}

			$date_arrange[$val][$settings['event_semester-' . $eventId]][$start_date] = array(
				'year' => $val,
				'start_date' => $settings['event_start_date-' . $eventId],
				'end_date' => $settings['event_end_date-' . $eventId],
				'name' => $settings['event_name-' . $eventId],
				'semester' => $settings['event_semester-' . $eventId],
				'terms' => explode(',', $settings['event_terms-' . $eventId]),
				'highlight' => $settings['event_highlight-' . $eventId],
				'url' => $event_url
			);
		} else
			continue;
	}

	$header_str = '<tr class="filter_dates %s header"><th colspan="2">%s Semester %s (<span class="term">All Terms</span>)</th></colspan>';
	$event_str = '<tr class="filter_dates %s event"><td><strong>%s</strong></td><td>%s</td></tr>';
	$link_str = '<a href="%s" target="_blank">%s <img src="/images/newwindow.png" title="Open in a New Window" /></a>';

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
			$prev_date = null;
			foreach($events as $start => $event) {
				$classes = array($class);

				$event_date = date($date_fmt, $start);

				if(strlen($event['end_date']) > 2) {
					$end_date = strtotime($event['end_date']);
					$event_date .= ' - ';
					if(date('n', $start) === date('n', $end_date)) {
						$event_date .= date('j', $end_date);
					} else { // NOT THE SAME MONTH
						$event_date .= date($date_fmt, $end_date);
					}
				}

				if($event['start_date'] == $prev_date && strlen($event['end_date']) > 2) { // Hide this because the previous date is already displayed from the previous event.
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

				$event_name = $event['name'];
				if(strlen($event['url']) > 0) {
					$event_name = sprintf($link_str, $event['url'], $event['name']);
				}

				$display_events[] = sprintf($event_str, implode(' ', $classes), $event_date, $event_name);

				$zebra++;
				$prev_date = $event['start_date'];

				// Since the events are organized by year/semester and then date, this will present the first semester with the first event that occurs AFTER the current date.
				if(is_null($current_semester) &&
				   $current_date < $start) {
					$current_semester = $s;
				}
			}
		}
	}
?>

<style type="text/css">
    .selection_dropdown {
         margin-bottom: 5px;
     }
    .selection_dropdown label { 
         margin: 0 !important;
         text-align: left !important;
         width: 50px !important;
     }
     .filterSelect { 
         width: 150px; 
     }
     #filterForm {
         margin-bottom: 8px;
     }
	#importantDates td:first-child { 
         text-align: right; 
     }
</style>

<div id="filterForm">
	<div class="selection_dropdown">
		<label for="filterYear">Year: </label>
		<select class="filterSelect" id="filterYear">
			<?php foreach($years as $y) {
				printf($option_str, $y, ($y === $year ? ' selected' : ''), $y);
				} ?>
		</select>
	</div>
	<div class="selection_dropdown">
		<label for="filterSemester">Semester: </label>
		<select class="filterSelect" id="filterSemester">
			<?php foreach($display_semesters as $sem) {
				printf($option_str, strtolower($sem), ($current_semester == strtolower($sem) ? ' selected' : ''), $sem);
			} ?>
		</select>
	</div>
	<div class="selection_dropdown">
		<label for="filterTerms">Terms: </label>
		<select class="filterSelect" id="filterTerms">
			<option value="all" selected>All Terms</option>
			<?php foreach($terms as $tid => $term) {
				printf($option_str, $tid, '', $term);
			} ?>
		</select>
	</div>
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