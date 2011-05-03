<?php

	global $CONFIG;

	if (!validate_embedit_token()) {
		die(elgg_echo('smartfiles:access_denied'));
	}
		
	elgg_set_ignore_access(true);
	
	$file_guid = get_input('file_guid');
	$file = get_entity($file_guid);
	
	if ($file instanceof ElggFile) {
	    	
	    	$mime = $file->getMimeType();
	        if (!$mime) {
	            $mime = 'application/octet-stream';
	        }
	
	        $filename = $file->originalfilename;
	
	        header("Content-type: $mime");
	        if (strpos($mime, "image/")!==false)
	            header("Content-Disposition: inline; filename=\"$filename\"");
	        else
	            header("Content-Disposition: attachment; filename=\"$filename\"");
	
	        header("Pragma: public", true);
	        header("Cache-Control: public", true);
	
	        $contents = $file->grabFile();
	        $splitString = str_split($contents, 8192);
	        foreach($splitString as $chunk) {
	            echo $chunk;
	        }
	}
	exit;
?>