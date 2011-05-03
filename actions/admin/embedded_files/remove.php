<?php

    admin_gatekeeper();

    $guid = get_input('guid');
    $file = get_entity($guid);

    if (!($file instanceof ElggFile)) {
    	register_error(elgg_echo('smartfiles:admin:embedded_files:remove:filenotfound'));
    	forward($_SERVER['HTTP_REFERER']);
	}
	
	$owner = get_entity($file->owner_guid);
	remove_embedit_object($file->embedit_id, $owner->email);
	remove_metadata($file->guid, 'embedit_id');

    system_message(elgg_echo('smartfiles:admin:embedded_files:remove:success'));
        
    forward($_SERVER['HTTP_REFERER']);

?>