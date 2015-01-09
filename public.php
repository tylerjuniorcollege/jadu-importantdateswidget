<?php
	if (++$promisesWidget > 1) {
		exit;
	}
	
	include_once('websections/JaduHomepageWidgetSettings.php');
	include_once('ext/json.php');
	
	$allEvents = array();
	$widgetSettingsFind = array('_apos_','_amp_','_eq_','_hash_','_ques_','_perc_');
	$widgetSettingsReplace = array("'","&",'=','#','?','%');

	if (isset($_POST['preview'])) {
		$newSettings = array();
		$j = 0;
		
		if (!empty($settings)) {
			foreach ($settings as $name => $value) {
				$newSettings[$j] = new stdClass();
				$newSettings[$j]->name = $name;
				$newSettings[$j]->value = $value;
				$newSettings[$j]->value= str_replace($widgetSettingsFind, $widgetSettingsReplace, $newSettings[$j]->value);
				$j++;
			}
		}
		
		$settings = $newSettings;
	}
	else {
		if (isset($widget) && !is_array($widget)) {
			if (isset($_POST['homepageContent'])) {
				$settings = array();
				foreach ($widgetSettings[$widget->id] as $setting) {
					$newSetting = new WidgetSetting();
					$newSetting->name = $setting->name;
					$newSetting->value = $setting->value;
					$newSetting->value= str_replace($widgetSettingsFind, $widgetSettingsReplace, $newSetting->value);
					
					$settings[] = $newSetting;
				}
			}
			else if (isset($_POST['action']) && $_POST['action'] == 'getPreviews') {
				$settings = getAllSettingsForHomepageWidget($aWidget->id);
			}
			else {
				$settings = getAllSettingsForHomepageWidget($widget->id, true);
			}
		}
		else {
			if (isset($_POST['homepageContent'])) {
				$settings = array();
				foreach ($widgetSettings[$stack->id] as $setting) {
					$newSetting = new WidgetSetting();
					$newSetting->name = $setting->name;
					$newSetting->value = $setting->value;
					$settings[] = $newSetting;
				}
			}
			else if (isset($_POST['getPreviews'])) {
				$settings = getAllSettingsForHomepageWidget($aWidget->id);
			}
			else {
				$settings = getAllSettingsForHomepageWidget($stack->id, true);
			}
		}
	}
	
	$tempImageSrc = array();
	$tempLinks = array();
	$tempLinkTitles = array();
	$tempButtonTitles = array();
	$tempButtonSubTitles = array();
	
	if (!empty($settings)) {
		var_dump($settings);
		die();
		foreach ($settings as $value) {
			if (preg_match('/image([0-9]+)buttonTitle/i', $value->name, $matches)) {
				$tempButtonTitles[$matches[1]] = $value->value;
			}
			if (preg_match('/image([0-9]+)buttonSubTitle/i', $value->name, $matches)) {
				$tempButtonSubTitles[$matches[1]] = $value->value;
			}
			if (preg_match('/image([0-9]+)link$/i', $value->name, $matches)) {
				$tempLinks[$matches[1]] = $value->value;
			}
			if (preg_match('/image([0-9]+)link_title/i', $value->name, $matches)) {
				$tempLinkTitles[$matches[1]] = $value->value;
			}
			if (preg_match('/image([0-9]+)imageSrc/i', $value->name, $matches)) {
				$tempImageSrc[$matches[1]] = $value->value;
			}
			
			if ($value->name == 'image_carousel_timer') {
				$timer = $value->value;
			}
		}
	}

	foreach ($tempLinks as $index => $link) {
		$allEvents[] = array($tempButtonTitles[$index], $tempButtonSubTitles[$index], $tempLinks[$index], $tempLinkTitles[$index], $tempImageSrc[$index]);
	}

	if (!empty($allEvents)) {
?>
<div class="promisesWidget" id="promisesWidget">
	<ul class="promisesTabs">
<?php
		foreach ($allEvents as $index => &$promisesWidgetLink) {
?>
			<li id="promisesTab<?php print $index + 1;?>">
				<a href="#">
					<span class="h2"><?php print $promisesWidgetLink[0]; ?></span>
					<span class="p"><?php print $promisesWidgetLink[1]; ?></span>
				</a>
			</li>
<?php
		}
?>
	</ul>
	<ul class="promisesContent">
<?php
		foreach ($allEvents as $index => &$promisesWidgetLink) {
?>
			<li id="promisesContent<?php print $index + 1;?>" style="display:<?php ($index == 0) ? print 'block' : print 'none'; ?>;">
<!--[if lt IE 8]>
				<a href="<?php print encodeHtml($promisesWidgetLink[2]); ?>"><img class="lazy" src="mhtml:<?php print getCurrentProtocolSiteRootURL() . '/site/styles/generic/datasprites.mht!blank.gif';?>" alt="<?php print encodeHtml(METADATA_GENERIC_NAME);?> logo" /></a>
<![endif]-->
<!--[if !(IE)|(gt IE 7)]><!-->
				<a href="<?php print encodeHtml($promisesWidgetLink[2]); ?>"><img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="<?php print encodeHtml(METADATA_GENERIC_NAME);?> logo" /></a>
<!--<![endif]-->
				<noscript><div><img alt="" src="<?php print getSiteRootURL() . '/images/' . encodeHtml($promisesWidgetLink[4]);?>" width="733" height="426" /></div></noscript>
			</li>
<?php
		}
?>
	</ul>
</div>
<?php
		if (!isset($indexPage) || $indexPage == false) {
/* 
?>
<script type="text/javascript">
	/* <![CDATA[ 
	(function(w) {
		var src = '<?php print getStaticContentRootURL(); ?>/site/javascript/promisesFader.min.js',
			id = 'js-promises-fader',
			callback = function () {
				try {
					var promisesFader = new PromisesFader('promisesWidget', {interval: <?php print $timer; ?>, lazy: true, effectDuration: 1.0, effectFps: 35});
<?php
			foreach ($allEvents as $index => &$promisesWidgetLink) {
?>
					promisesFader.addImage('<?php print getCurrentProtocolSiteRootURL(); ?>/images/<?php print $promisesWidgetLink[4]; ?>');
<?php 
			}
?>
				} catch (e) {}
			};
		(function(d,t,cb,id){
			var s = d.createElement(t), o = d.getElementsByTagName(t), o = o[o.length - 1], r = 0, f = function() {
				var rs = s.readyState; if (!r && !(rs && rs != 'complete' && rs != 'loaded')) { cb(); r = !0; s.id = id + '-loaded' };
			};
			if (d.getElementById(id + '-loaded')) { cb(); return; }
			s.async = !0; s.type = 'text/javascript'; s.id = id + '-loading', s.src = src; s.onload = s.onreadystatechange = f;
			o.parentNode.insertBefore(s, o)
		}(document,'script',callback,id));
	})(window);
	/* ]]> 
</script>
<?php */
		}
	}
?>