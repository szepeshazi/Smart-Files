<?php 

	$plugin = find_plugin_settings('smartfiles');
	$mime_types = unserialize($plugin->mime_types);

	$ts = time ();
	$token = generate_action_token ( $ts );

?>

	<div id="mime_types_list"><br />
		<form id="add_mime_type" name="create_category_type" action="<?php echo $CONFIG->wwwroot; ?>/action/smartfiles/admin/mime_types/add" method="post">
		<input type="hidden" name="__elgg_ts" value="<?php echo $ts; ?>"></input>
		<input type="hidden" name="__elgg_token" value="<?php echo $token; ?>"></input>
		<table id="mime_types_table" cellspacing="0">
			<tr>
				<th align="left" width="20%"><b><?php echo elgg_echo('smartfiles:mime_types:mime'); ?></th>
				<th align="left" width="60%"><b><?php echo elgg_echo('smartfiles:mime_types:description'); ?></th>
				<th align="left" width="20%"><b><?php echo elgg_echo('smartfiles:admin:action'); ?></th>
			</tr>
			<tr>
				<td colspan=3><hr></td>
			</tr>

<?php 
	$counter=0;
	foreach ($mime_types as $key => $value) {
		$cellClass = (($counter++%2) == 0) ? 'table-row-even' : 'table-row-odd';
		$delete_link = elgg_view("output/confirmlink", array(
			'href' => $vars['url'] . 'action/smartfiles/admin/mime_types/delete?mime=' . $key . '&__elgg_token=' . $token . '&__elgg_ts=' . $ts,
			'text' => elgg_echo('smartfiles:admin:delete'),
			'confirm' => elgg_echo('smartfiles:admin:mime_types:confirm')
		));
		
?>
		<tr>
			<td class="<?php echo $cellClass?>"><?php echo $key; ?></td>
			<td class="<?php echo $cellClass?>"><?php echo $value; ?></td>
			<td class="<?php echo $cellClass?>">
				<?php echo $delete_link; ?>	
			</td>
		</tr>

<?php 
	}
?>
		<tr class="mime_types_admin_row">
			<td class="table-row-even"><input type="text" name="mime"></input></td>
			<td class="table-row-even"><input type="text" name="description"></input></td>
			<td class="table-row-even"><input type="submit" value="<?php echo elgg_echo('smartfiles:admin:add');?>"></input></td>
		</tr>
		</table>
		</form>
	</div>

	
