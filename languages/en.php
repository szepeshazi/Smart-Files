<?php

	$english = array(

		'smartfiles:access_denied' => 'Access denied',
		'smartfiles:invalid_setup' => 'Smart Files: invalid plugin setup. Please make sure that you enter a valid API key and personal identifier in the configuration section of the plugin.',

		'smartfiles:key' => 'Your embedit.in API key',
		'smartfiles:identifier' => 'Your personal identifier',
		'smartfiles:badreply' => 'embedit.in did not provide a valid embed url. Response was: %s',
		'smartfiles:width' => 'The embedded document\'s default width',
		'smartfiles:height' => 'The embedded document\'s default height',
		'smartfiles:resize:info' => 'Resize document by dragging the lower-right corner of this window.',
		'smartfiles:resize:enable' => 'Enable resizing for the embedded document?',
		'smartfiles:min_width' => 'The embedded document\'s minimum width',
		'smartfiles:min_height' => 'The embedded document\'s minimum height',
		'smartfiles:max_width' => 'The embedded document\'s maximum width',
		'smartfiles:max_height' => 'The embedded document\'s maximum height',
		'smartfiles:gallery_thumbs:enable' => 'Use embedit.in generated icons in gallery view',
		'smartfiles:force_thumbs:enable' => 'Force thumbnail creation when viewing files in gallery view',
		'smartfiles:auto_create:enable' => 'Enable auto-create (file upload to site will also trigger embedit.in upload)',
		'smartfiles:ignore_access:enable' => 'Ignore Elgg access system when creating embeds',
		'smartfiles:yes' => 'Yes',
		'smartfiles:no' => 'No',
	
		'smartfiles:admin:title' =>	'Smart Files administration',
		'smartfiles:admin:mime_types' =>	'Mime types to be handled by embed.it API',
		'smartfiles:admin:action' =>	'Action',
		'smartfiles:admin:delete' =>	'Delete',
		'smartfiles:admin:mime_types:confirm' =>	'Are you sure you\'d like to remove this MIME type from the list of supported types?',
		'smartfiles:admin:add' =>	'Add MIME type',
	
		'smartfiles:mime_types:mime' => 'MIME type',
		'smartfiles:mime_types:description' => 'File description',
	
		'smartfiles:admin:embedded_files' =>	'Embedded files',
		'smartfiles:embedded_files:title' =>	'File title',
		'smartfiles:embedded_files:original' =>	'Original file name',
		'smartfiles:embedded_files:embedit_id' =>	'embedit.in identifier',
		'smartfiles:admin:embedded_files:confirm' =>	'Are you sure you\'d like to remove the embedded version of this file from http://embedit.in?',
		'smartfiles:admin:remove' =>	'Remove embed',
	
		'smartfiles:admin:mime_types:add:success' =>	'The new MIME type has been successfully added to the list of supported types',
		'smartfiles:admin:mime_types:delete:success' =>	'MIME type has been removed from the list of supported types',
		'smartfiles:admin:mime_types:add:empty' =>	'Please provide the MIME type to be added to the list of supported types',
		'smartfiles:admin:mime_types:add:already_exists' =>	'This MIME type already exists in the list of supported types',

		'smartfiles:admin:embedded_files:remove:filenotfound' =>	'Could not find file based on passed guid',
		'smartfiles:admin:embedded_files:remove:success' =>	'Embedded file was successfully removed from http://embedit.in',
	
	);
					
	add_translation("en", $english);

?>