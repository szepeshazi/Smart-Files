<?php

	global $CONFIG;

	$tabs = array('mime_types', 'embedded_files');
	$selected_tab = $vars['tab'];
		
?>
<div class="contentWrapper">
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<?php
				foreach ($tabs as $tab) { 
			?>
			<li<?php echo strcmp($tab, $selected_tab) == 0 ? ' class="selected"' : ''; ?>>
				<a href="<?php echo $CONFIG->wwwroot . 'mod/smartfiles/admin/menu.php?tab=' . $tab; ?>">
					<?php echo elgg_echo('smartfiles:admin:' . $tab); ?>
				</a>
			</li>
			<?php
				} 
			?>
		</ul>
	</div>
<?php
	echo elgg_view('smartfiles/admin/' . $selected_tab);
?>
</div>