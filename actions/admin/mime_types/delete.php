<?php

    admin_gatekeeper();

    $mime = get_input('mime', '');

	$plugin = find_plugin_settings('smartfiles');
	$mime_types = unserialize($plugin->mime_types);
	
	// Remove the passed mime type
	unset($mime_types[$mime]);
	
	set_plugin_setting('mime_types', serialize($mime_types), 'smartfiles');

    system_message(elgg_echo('smartfiles:admin:mime_types:delete:success'));
        
    forward($_SERVER['HTTP_REFERER']);

?>