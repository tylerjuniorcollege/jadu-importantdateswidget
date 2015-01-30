<?php
	$semesters = array('Spring',
					   'May',
					   'Summer',
					   'Fall',
					   'Winter');

	$display_semesters = array('fall' 	=> 'Fall', 
							   'winter' => 'Winter', 
							   'spring' => 'Spring', 
							   'may' 	=> 'May', 
							   'summer' => 'Summer');

	$old_terms = array(
		0 => 'term-16',
		1 => 'term-12',
		2 => 'term-8-1',
		3 => 'term-8-2'
	);

	$terms = array(
		'term-16'  => '16-Week',
		'term-12'  => '12-Week',
		'term-8-1' => '1st 8-Week',
		'term-8-2' => '2nd 8-Week',
		'summer-term-1' => 'Summer I',
		'summer-term-2' => 'Summer II',
		'summer-term-mid' => 'Mid-Summer',
		'summer-term-long' => 'Summer Long'
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

	// today.
	$current_date = time(); //mktime(00, 00, 00);

	$date_fmt = 'F j';

	// We need to get the settings.
	if(isset($_POST['preview'])) {
		$newSettings = array();
		foreach($settings as $setting) {
			$newSettings[$setting->name] = $setting->value;
		}

		$settings = $newSettings;
	} else {
		if(isset($widget) && !is_array($widget)) {
			if(isset($_POST['homepageContent'])) {
				$settings = $widgetSettings[$widget->id];
			} elseif(isset($_POST['action']) && $_POST['action'] == 'getPreviews') {
				$settings = getAllSettingsForHomepageWidget($aWidget->id);
			} else {
				$settings = getAllSettingsForHomepageWidget($widget->id, true);
			}
		} else {
			if(isset($_POST['homepageContent'])) {
				$settings = $widgetSettings[$stack->id];
			} elseif(isset($_POST['action']) && $_POST['action'] == 'getPreviews') {
				$settings = getAllSettingsForHomepageWidget($aWidget->id);
			} else {
				$settings = getAllSettingsForHomepageWidget($stack->id, true);
			}
		}

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
			$end_date = (strlen($settings['event_end_date-' . $eventId]) > 0 ? strtotime($settings['event_end_date-' . $eventId]) : null);

			// Checking to see if the "Event_URL" has been set or is null.
			$event_url = null;
			if(isset($settings['event_url-' . $eventId])) {
				$event_url = $settings['event_url-' . $eventId];
			}

			if(empty($settings['event_terms-' . $eventId])) {
				$term_arr = array();
			} else {
				$term_arr = explode(',', $settings['event_terms-' . $eventId]);
			}

			while(array_key_exists($start_date, $date_arrange[$val][$settings['event_semester-' . $eventId]])) {
				//if(strlen($date_arrange[$val][$settings['event_semester-' . $eventId]]['end_date']) > 1 && !is_null($end_date)) {
				//	$prev_end = strtotime($date_arrange[$val][$settings['event_semester-' . $eventId]]['end_date']);
				//
				//} else {
					$start_date += 1;
				//}
			}

			$date_arrange[$val][$settings['event_semester-' . $eventId]][$start_date] = array(
				'year' => $val,
				'start_date' => $settings['event_start_date-' . $eventId],
				'end_date' => $settings['event_end_date-' . $eventId],
				'name' => $settings['event_name-' . $eventId],
				'semester' => $settings['event_semester-' . $eventId],
				'terms' => $term_arr,
				'highlight' => $settings['event_highlight-' . $eventId],
				'url' => $event_url
			);
		} else
			continue;
	}

	$header_str = '<tr class="filter_dates %s header"><th colspan="2">%s Semester %s (<span class="term">All Terms</span>)</th></colspan>';
	$event_str = '<tr class="filter_dates %s event"><td><strong>%s</strong></td><td><strong>%s</strong> <span class="available_terms">%s</span></td></tr>';
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
			$prev_start = null;
			$prev_end = null;
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

				if($event['start_date'] == $prev_start && 
				   $event['end_date'] == $prev_end) { // Hide this because the previous date is already displayed from the previous event.
					$event_date = null;
				}

				// Add the terms as classes for javascript selection.
				$event_terms = array();
				foreach($event['terms'] as $term_id) {
					if (strlen($term_id) < 2) {
						$term_id = $old_terms[$term_id];
					}
					$classes[] = $term_id;
					$event_terms[] = $terms[$term_id];
				}

				if(!empty($event_terms)) {
					$event_terms = '(' . implode(', ', $event_terms) . ')';
				} else {
					$event_terms = null;
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

				$display_events[] = sprintf($event_str, implode(' ', $classes), $event_date, $event_name, $event_terms);

				$zebra++;
				$prev_start = $event['start_date'];
				$prev_end = $event['end_date'];

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

    .available_terms { font-style: italic; color: #999999; }
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
			<?php foreach($display_semesters as $semid => $sem) {
				printf($option_str, $semid, ($current_semester == strtolower($sem) ? ' selected' : ''), $sem);
			} ?>
		</select>
	</div>
	<div class="selection_dropdown">
		<label for="filterTerms">Terms: </label>
		<select class="filterSelect" id="filterFWTerms"<?php echo ($current_semester != 'summer' ? '' : ' style="display: none;"'); ?>>
			<option value="all" selected>All Terms</option>
			<?php foreach($terms as $tid => $term) {
				if(strpos($tid, 'summer') === FALSE) {
					printf($option_str, $tid, '', $term);
				}
			} ?>
		</select>
		<select class="filterSelect" id="filterSTerms"<?php echo ($current_semester == 'summer' ? '' : ' style="display:none;"'); ?>>
			<option value="all" selected>All Terms</option>
			<?php foreach($terms as $tid => $term) {
				if(strpos($tid, 'summer') !== FALSE) {
					printf($option_str, $tid, '', $term);
				}
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