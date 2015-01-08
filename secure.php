<?php 
	// Template for Semester Types.
	$semesters = array(0 => 'Fall',
					   1 => 'Winter',
					   2 => 'Spring',
					   3 => 'May',
					   4 => 'Summer');
	
	$terms = array(
		0 => '16 Week',
		1 => '12 Week',
		3 => '8 Week'
	);

	$year = date("Y");
	$years = array();
	for($i = 0; $i < 3; $i++) {
		$years[] = ($year + $i);
	}
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
				<table class="form_table">
					<tbody id="date_widget_dates" style="display: none;">
					
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
   	<tfoot style="">
		<tr>
			<td class="label_cell">Event Date</td>
			<td class="data_cell"><input type="text" value="" id="event_date" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Event Name</td>
			<td class="data_cell"><input type="text" value="" id="event_name" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Semester</td>
			<td class="data_cell"><select id="event_semester">
				<?php foreach($semesters as $id => $semester): ?>
					<option value="<?=$id ?>"><?=$semester ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
		<tr>
			<td class="label_cell">Year</td>
			<td class="data_cell"><select id="event_year">
				<?php foreach($years as $year): ?>
					<option value="<?=$year; ?>"><?=$year; ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
		<tr>
			<td class="label_cell">Term</td>
			<td class="data_cell"><?php foreach($terms as $termid => $term): ?>
				<input type="checkbox" class="event_terms" id="term-<?=$termid; ?>" value="<?=$termid; ?>"><?=$term; ?> &nbsp;
			<?php endforeach; ?></td>
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