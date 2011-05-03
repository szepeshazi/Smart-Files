<?php

    $guids = get_input('file_guids');
	if ((!is_array($guids)) || (empty($guids))) {
		register_error(elgg_echo('smartfiles:admin:embedded_files:remove:filenotfound'));
		forward($_SERVER['HTTP_REFERER']);
	}
	foreach ($guids as $guid) {
	    $file = get_entity($guid);
	    if ($file instanceof ElggFile) {
			remove_metadata($file->guid, 'disable_embed');
	    }
	}		

    system_message(elgg_echo('smartfiles:embed:enable:success'));
        
    forward($_SERVER['HTTP_REFERER']);

?>