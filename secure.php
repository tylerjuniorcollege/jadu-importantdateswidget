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
		2 => '1st 8 Week',
		3 => '2nd 8 Week'
	);

	$current_year = date("Y");
	$years = array();
	for($i = -1; $i < 3; $i++) {
		$years[] = ($current_year + $i);
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
									printf('<option value="%s"%s>%s</option>', $year, ($year === $current_year ? ' selected' : ''), $year);
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
					printf('<option value="%s"%s>%s</option>', $year, ($year === $current_year ? ' selected' : ''), $year);
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
		<tr>
			<td class="label_cell">Term <font color="red">*</font></td>
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