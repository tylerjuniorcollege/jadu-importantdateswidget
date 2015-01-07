<?php 
	// Template for Semester Types.
	$semester_array = array(0 => 'Fall',
							1 => 'Winter',
							2 => 'Spring',
							3 => 'May',
							4 => 'Summer');

	
?>
<table class="form_table" id="tbl_widget_content">
	<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />
	<tbody>
		<tr>
			<td class="label_cell">Timer <em>(in seconds)</em></td>
			<td class="data_cell"><input type="text" value="6.5" id="image_carousel_timer" class="field" size="12"></td>
		</tr>	
		<tr>
			<td class="label_cell"></td>
			<td class="data_cell"><input type="button" onclick="addWidgetImage();" class="button" value="Add Slide"></td>
		</tr>		
		<tr>
			<td colspan="2">
				<table class="form_table">
					<tbody id="image_carousel_widget_images" style="display: none;">
					
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
   	<tfoot style="">
		<tr>
			<td class="label_cell">Image</td>
			<td class="data_cell">
				<input type="hidden" onchange="$('image_carousel_imagei').src = 'http://' + DOMAIN + '/images/' + this.value;" value="" id="image_carousel_image">
				<input type="button" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=image_carousel_imagei&imageFilenameID=image_carousel_image');" value="Image Library" class="button">
			</td>
		</tr>
		<tr>
			<td class="label_cell">Preview</td>
			<td class="data_cell"><img src="../images/no_image.gif" class="img_preview" id="image_carousel_imagei"></td>
		</tr>
		<tr>
			<td class="label_cell">Button Title*</td>
			<td class="data_cell"><input type="text" value="" id="image_carousel_button_title" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Button Subtitle*</td>
			<td class="data_cell"><input type="text" value="" id="image_carousel_button_subtitle" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Link*</td>
			<td class="data_cell"><input type="text" value="" id="image_carousel_link" class="field" size="12"></td>
		</tr>
		<tr>
			<td class="label_cell">Link Title*</td>
			<td class="data_cell"><input type="text" value="" id="image_carousel_link_title" class="field" size="12"></td>
		</tr>		
		<tr>
			<td class="label_cell"><input type="button" onclick="deleteWidgetImage()" value="Delete Slide" id="widgetImageDelete" class="button" style="display: none;"></td>
			<td class="data_cell">
				<input type="button" onclick="saveWidgetImage();" value="Save Slide" class="button">
				<input type="button" onclick="closeSlide();" value="Close Slide" class="button">
			</td>
		</tr>
	</tfoot>  	
</table>