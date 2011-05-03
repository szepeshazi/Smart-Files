<?php 

	// Load Elgg engine
	include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	$plugin = find_plugin_settings('smartfiles');

	// Check if the embedit.in API parameters have been set up
	$valid_setup = (isset($plugin->key) && isset($plugin->identifier));
	if (!$valid_setup) {
		register_error(elgg_echo('smartfiles:invalid_setup'));
		forward($_SERVER['HTTP_REFERER']);
	}
	error_log('key: ' . $plugin->key);
	error_log('email: ' . $plugin->email);
	error_log('indetifier: ' . $plugin->identifier);
	
	// Create post request to embedit.in site
	$signature = sha1($plugin->key . $plugin->identifier);
	$post_vars = array(
		"identifier" => $plugin->identifier, 
		"signature" => $signature, 
	);
	$curlobj = curl_init("http://embedit.in/api/v1/list/xml");
	curl_setopt($curlobj, CURLOPT_HEADER, 0);
	curl_setopt($curlobj, CURLOPT_POST, 1);
	curl_setopt($curlobj, CURLOPT_POSTFIELDS, $post_vars);
	curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($curlobj, CURLOPT_REFERER, $CONFIG->wwwroot);
	curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
	$embedit_reply = curl_exec($curlobj);
	curl_close($curlobj);
	
	echo $embedit_reply;
?>