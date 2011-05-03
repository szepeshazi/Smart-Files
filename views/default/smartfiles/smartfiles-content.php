<?php

	if (get_context() == 'file') {
		$entity = $vars['entity'];
		
		if ($entity instanceof ElggFile) {
			
			$plugin = find_plugin_settings('smartfiles');

			get_embedit_id($entity, true);

		    $width = $plugin->default_width;
		    $height = $plugin->default_height;
			$resizeable = ((!isset($plugin->enable_resize)) || ($plugin->enable_resize == 'yes')); 
	
?>

<div id="embed_container" <?php if ($resizeable) { ?>class="ui-widget-content" <?php } ?>>
	<?php if ($resizeable) {?>
		<h3 id="embed_header" class="ui-widget-header" style="font-size: 0.9em; font-weight: bold;">
			<center>
				<?php echo elgg_echo('smartfiles:resize:info'); ?>
			</center>
		</h3>
	<?php } ?>
	<div id="embed_<?php echo $entity->embedit_id; ?>">
	</div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo $CONFIG->wwwroot; ?>mod/smartfiles/css/ui-lightness/jquery-ui-1.7.3.custom.css" />
<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>mod/smartfiles/js/jquery-ui-1.7.3.resizable.min.js"></script>
<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>mod/smartfiles/js/swfobject/swfobject.js"></script>
<script type="text/javascript">
	swfobject.embedSWF(
		"http://embedit.in/<?php echo $entity->embedit_id; ?>.swf", 
		"embed_<?php echo $entity->embedit_id; ?>", 
		"<?php echo $width; ?>", 
		"<?php echo $height; ?>", 
		"9.0.28", 
		false, 
		{}, 
		{allowFullScreen: true}
	);

	<?php if ($resizeable) {?>
		var corner_padding = 15;	// Number of pixels to leave free for the draggable lower-right corner
		$('#embed_container').resizable({
			<?php if (isset($plugin->max_width) && !empty($plugin->max_width)) { ?>maxWidth: <?php echo $plugin->max_width; ?>,<?php  } ?>
			<?php if (isset($plugin->max_height) && !empty($plugin->max_height)) { ?>maxHeight: <?php echo $plugin->max_height; ?>,<?php  } ?>
			<?php if (isset($plugin->min_width) && !empty($plugin->min_width)) { ?>minWidth: <?php echo $plugin->min_width; ?>,<?php  } ?>
			<?php if (isset($plugin->min_height) && !empty($plugin->min_height)) { ?>minHeight: <?php echo $plugin->min_height; ?>,<?php  } ?>
			resize: function(event, ui) {
				$('#embed_<?php echo $entity->embedit_id; ?>').css('width', (ui.size.width - corner_padding));
				$('#embed_<?php echo $entity->embedit_id; ?>').css('height', (ui.size.height - $('#embed_header').height() - corner_padding));
			},
			stop: function(event, ui) {
				$('#embed_<?php echo $entity->embedit_id; ?>').css('width', (ui.size.width - corner_padding));
				$('#embed_<?php echo $entity->embedit_id; ?>').css('height', (ui.size.height - $('#embed_header').height() - corner_padding));
			}
		});
		$('#embed_container').css('width', (<?php echo $width;?> + corner_padding));
		$('#embed_container').css('height', (<?php echo $height;?> + $('#embed_header').height() + corner_padding));
	<?php } ?>

</script> 
<?php 
		}
	}
?>
