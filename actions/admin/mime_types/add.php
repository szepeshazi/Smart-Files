<?php

    admin_gatekeeper();

    $mime = get_input('mime', '');
    $description = get_input('description', '');
    
    if (empty($mime)) {
    	register_error(elgg_echo('smartfiles:admin:mime_types:add:empty'));
    	forward($_SERVER['HTTP_REFERER']);
    }

	$plugin = find_plugin_settings('smartfiles');
	$mime_types = unserialize($plugin->mime_types);
	
	if (array_key_exists($mime, $mime_types)) {
    	register_error(elgg_echo('smartfiles:admin:mime_types:add:already_exists'));
    	forward($_SERVER['HTTP_REFERER']);
	}
	
	// Add the new mime type
	$mime_types[$mime] = $description;

	set_plugin_setting('mime_types', serialize($mime_types), 'smartfiles');

    system_message(elgg_echo('smartfiles:admin:mime_types:add:success'));
        
    forward($_SERVER['HTTP_REFERER']);

?>