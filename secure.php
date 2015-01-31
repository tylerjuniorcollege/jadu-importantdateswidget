<?php 
	// Template for Semester Types.
	$semesters = array('Fall',
					   'Winter',
					   'Spring',
					   'May',
					   'Summer');
	
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

	$current_year = date("Y");
	$years = array();
	for($i = -1; $i < 3; $i++) {
		$years[] = (string) ($current_year + $i);
	}
?>
<table class="form_table" id="tbl_widget_content">
	<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />
	<tbody>
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
								<select id="picker_year"><?php foreach($years as $year) {
									printf('<option value="%s"%s>%s</option>', $year, ($year == $current_year ? ' selected' : ''), $year);
									} ?>
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
			<td class="label_cell">Semester <font color="red">*</font></td>
			<td class="data_cell"><select id="event_semester">
				<?php foreach($semesters as $id => $semester): ?>
					<option value="<?php echo strtolower($semester); ?>"><?php echo $semester ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
   		<tr>
			<td class="label_cell">Semester Year <font color="red">*</font></td>
			<td class="data_cell"><select id="event_year">
				<?php foreach($years as $year) {
					printf('<option value="%s"%s>%s</option>', $year, ($year == $current_year ? ' selected' : ''), $year);
				} ?>
			</select></td>
		</tr>
		<tr>
			<td class="label_cell">Event Start Date <font color="red">*</font></td>
			<td class="data_cell">
				<input type="text" size="20" name="event_start_date" id="event_start_date" value="">
				<img src="../images/cal.gif" width="16" height="16" onclick="return loadLightbox('calendar', 'lb2', 'mode=lb2&target=event_start_date');">
			</td>
		</tr>
		<tr>
			<td class="label_cell">Event End Date</td>
			<td class="data_cell">
				<input type="text" size="20" name="event_end_date" id="event_end_date" value="">
				<img src="../images/cal.gif" width="16" height="16" onclick="return loadLightbox('calendar', 'lb2', 'mode=lb2&target=event_end_date');">
			</td>
		</tr>
		<tr>
			<td class="label_cell">Event Name <font color="red">*</font></td>
			<td class="data_cell"><input type="text" value="" id="event_name" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Event URL</td>
			<td class="data_cell"><input type="text" value="" id="event_url" class="field" size="12"></td>
		</tr>
		<tr id="term_row">
			<td class="label_cell">Term <font color="red">*</font></td>
			<td class="data_cell"><?php foreach($terms as $termid => $term) {
					$term_str = '<span class="term_display %s"><input type="checkbox" class="event_terms" id="%s" value="%s"> %s </span>';
					$term_class = 'regular';
					if(strpos($termid, 'summer') !== false) {
						$term_class = 'summer';
					}
					printf($term_str, $term_class, $termid, $termid, $term);
				} ?>
			</td>
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