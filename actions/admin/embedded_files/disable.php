<?php

    $guids = get_input('file_guids');
	if ((!is_array($guids)) || (empty($guids))) {
		register_error(elgg_echo('smartfiles:admin:embedded_files:remove:filenotfound'));
		forward($_SERVER['HTTP_REFERER']);
	}
	foreach ($guids as $guid) {
	    $file = get_entity($guid);
	    if ($file instanceof ElggFile) {
			if (!empty($file->embedit_id)) {
		    	$owner = get_entity($file->owner_guid);
				remove_embedit_object($file->embedit_id, $owner->email);
			}
	    	$file->disable_embed = 1;
			$file->save();
	    }
	}		

    system_message(elgg_echo('smartfiles:embed:disable:success'));
        
    forward($_SERVER['HTTP_REFERER']);

?>