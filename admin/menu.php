<?php

	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

	global $CONFIG;

	admin_gatekeeper();
	set_context('admin');
	set_page_owner($_SESSION['guid']);
	
	extend_view('metatags','smartfiles/admin/css');
	
	$tab = get_input('tab') ? get_input('tab') : 'mime_types';

	$body = elgg_view_title(elgg_echo('smartfiles:admin:title'));
	
	$body .= elgg_view("smartfiles/admin/index", array('tab' => $tab));
	
	page_draw(elgg_echo('smartfiles:admin:title'), elgg_view_layout("two_column_left_sidebar", '', $body));

?>
