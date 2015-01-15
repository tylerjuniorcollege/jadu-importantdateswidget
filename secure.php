<?php 
	// Template for Semester Types.
	$semesters = array('Fall',
					   'Winter',
					   'Spring',
					   'May',
					   'Summer');
	
	$terms = array(
		0 => '16 Week',
		1 => '12 Week',
		2 => '8 Week'
	);

	$year = date("Y");
	$years = array();
	for($i = 0; $i < 3; $i++) {
		$years[] = ($year + $i);
	}

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
?>
<table class="form_table" id="tbl_widget_content">
	<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />
	<tbody>
		<!--<tr>
			<td class="label_cell">Timer <em>(in seconds)</em></td>
			<td class="data_cell"><input type="text" value="6.5" id="image_carousel_timer" class="field" size="12"></td>
		</tr>-->
		<tr>
			<td class="label_cell"></td>
			<td class="data_cell"><input type="button" onclick="addWidgetEvent();" class="button" value="Add Event"></td>
		</tr>		
		<tr>
			<td colspan="2">
				<table id="date_widget_table" class="form_table">
					<tbody id="date_widget_dates" style="">
						<tr><td class="label_cell">Pick Year/Semester:</td>
							<td class="data_cell">
								<select id="picker_year"><?php foreach($years as $year): ?>
									<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
									<?php endforeach; ?>
								</select>
								<select id="picker_semester"><?php foreach($semesters as $id => $semester): ?>
									<option value="<?php echo strtolower($semester) ?>"><?php echo $semester ?></option>
									<?php endforeach; ?>
								</select>
								<input type="button" onclick="filterRows();" value="Show Events" class="button">
							</td>
						</tr>
						<?php foreach($years as $y) {
								foreach($semesters as $sem) {
									//foreach($terms as $i => $t) {
										printf('<tr id="%s_%s" class="filter_dates" style="display:none;"><td class="label_cell" colspan="2" style="text-align: center;">%s - %s</td></tr>', 
											   $y, strtolower($sem), $y, $sem);
									//}
								}
							} ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
   	<tfoot style="">
		<tr>
			<td class="label_cell">Event Start Month</td>
			<td class="data_cell">
				<select id="event_start_month">
					<?php foreach($months as $m => $month): ?>
					<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="label_cell">Event Start Day</td>
			<td class="data_cell">
				<input type="text" value="" id="event_start_day" class="field" size="3">
			</td>
		</tr>
		<tr>
			<td class="label_cell">Event End Month</td>
			<td class="data_cell">
				<select id="event_end_month">
					<?php foreach($months as $m => $month): ?>
					<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="label_cell">Event End Day</td>
			<td class="data_cell">
				<input type="text" value="" id="event_end_day" class="field" size="3">
			</td>
		</tr>
		<tr>
			<td class="label_cell">Event Name</td>
			<td class="data_cell"><input type="text" value="" id="event_name" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Semester</td>
			<td class="data_cell"><select id="event_semester">
				<?php foreach($semesters as $id => $semester): ?>
					<option value="<?php echo strtolower($semester); ?>"><?php echo $semester ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
		<tr>
			<td class="label_cell">Year</td>
			<td class="data_cell"><select id="event_year">
				<?php foreach($years as $year): ?>
					<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
		<tr>
			<td class="label_cell">Term</td>
			<td class="data_cell"><?php foreach($terms as $termid => $term): ?>
				<input type="checkbox" class="event_terms" id="term-<?php echo $termid; ?>" value="<?php echo $termid; ?>"><?php echo $term; ?> &nbsp;
			<?php endforeach; ?></td>
		</tr>
		<tr>
			<td class="label_cell">Highlight Event</td>
			<td class="data_cell"><input type="checkbox" id="event_highlight" value="1"> Add a special highlight to the event listing.</td>
		</tr>
		<tr>
			<td class="label_cell"><input type="button" onclick="deleteWidgetEvent()" value="Delete Event" id="widgetEventDelete" class="button" style="display: none;"></td>
			<td class="data_cell">
				<input type="button" onclick="saveWidgetEvent();" value="Save Event" class="button">
				<input type="button" onclick="closeEvent();" value="Close Event" class="button">
			</td>
		</tr>
	</tfoot>  	
</table>