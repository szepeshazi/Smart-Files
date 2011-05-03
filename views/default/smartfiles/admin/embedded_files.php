<?php 

	$offset = (int)get_input('offset', 0);
	$limit = (int)get_input('offset', 0);
	
	$options = array(
		'type' => 'object',
		'subtype' => 'file',
		'metadata_name_value_pairs' => array(array('name' => 'embedit_id', 'value' => '%', 'operand' => 'LIKE'))
	);

	$count = elgg_get_entities_from_metadata(array_merge($options, array('count' => true)));
	$entities = elgg_get_entities_from_metadata(array_merge($options, array('limit' => $limit, 'offset' => $offset)));
	
    $nav = elgg_view('navigation/pagination',array(
		'baseurl' => $_SERVER['REQUEST_URI'],
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	));

	$ts = time ();
	$token = generate_action_token ( $ts );
	
	echo $nav;
?>

	<div id="mime_types_list"><br />
		<table id="mime_types_table" cellspacing="0">
			<tr>
				<th align="left"><b><?php echo elgg_echo('smartfiles:embedded_files:title'); ?></th>
				<th align="left"><b><?php echo elgg_echo('smartfiles:embedded_files:original'); ?></th>
				<th align="left"><b><?php echo elgg_echo('smartfiles:embedded_files:embedit_id'); ?></th>
				<th align="left"><b><?php echo elgg_echo('smartfiles:admin:action'); ?></th>
			</tr>
			<tr>
				<td colspan=4><hr></td>
			</tr>

<?php 
	$counter=0;
	foreach ($entities as $entity) {
		$cellClass = (($counter++%2) == 0) ? 'table-row-even' : 'table-row-odd';
		$delete_link = elgg_view("output/confirmlink", array(
			'href' => $vars['url'] . 'action/smartfiles/admin/embedded_files/remove?guid=' . $entity->guid . '&__elgg_token=' . $token . '&__elgg_ts=' . $ts,
			'text' => elgg_echo('smartfiles:admin:remove'),
			'confirm' => elgg_echo('smartfiles:admin:embedded_files:confirm')
		));
		
?>
		<tr>
			<td class="<?php echo $cellClass?>"><?php echo $entity->title; ?></td>
			<td class="<?php echo $cellClass?>"><?php echo $entity->originalfilename; ?></td>
			<td class="<?php echo $cellClass?>"><?php echo $entity->embedit_id; ?></td>
			<td class="<?php echo $cellClass?>">
				<?php echo $delete_link; ?>	
			</td>
		</tr>

<?php 
	}
?>
		</table>
	</div>
	
<?php 
	echo $nav;
?>