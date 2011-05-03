<?php

	/**
	 * Smart Files plugin for Elgg
	 * Displays several file types embbeded in the page via integration with embedit.in API
	 * 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Andras Szepeshazi
	 * @copyright Andras Szepeshazi
	 * @link http://wamped.org
	 */


    global $CONFIG;

	/**
	 * Plugin initialisation
	 */
	function smartfiles_init() {
		
		register_page_handler('smartfiles', 'smartfiles_page_handler');

		register_elgg_event_handler('pagesetup','system','smartfiles_adminmenu');

		// When deleting a file, make sure to delete the associated embedit.in object as well
		register_elgg_event_handler('delete','object','smartfiles_delete_cleanup');
		
		// Make sure that plugin parameters were properly set up		
		smartfiles_require_setup();
		$plugin = find_plugin_settings('smartfiles');
		if (isset($plugin->auto_create) && $plugin->auto_create == 'yes') {
			// When creating a file, also create embedit.in object for the file, if the plugin settings require so
			register_elgg_event_handler('create','object','smartfiles_autocreate_embedit_id');
		}
		
		register_plugin_hook('list_view:toolbar:extras', 'object', 'smartfiles_toolbar_extras');

	}
	
	/**
     * Hook for extending the list view for objects, allowing the addition of extra elements to the top toolbar 
     *
     * @param unknown_type $hook
     * @param unknown_type $entity_type
     * @param unknown_type $returnvalue
     * @param unknown_type $params
     * @return unknown
     */
	function smartfiles_toolbar_extras($hook, $entity_type, $returnvalue, $params) {
		$viewtype = get_input('search_viewtype');
		if (($viewtype == 'gallery') && (is_array($params['entities'])) && (!empty($params['entities'])) && ($params['entities'][0] instanceof ElggFile)) {
			$add_view = false;
			foreach ($params['entities'] as $entity) {
				if ($entity->canEdit()) {
					$add_view = true;
					break;
				}
			}
			if ($add_view) {
				echo elgg_view('smartfiles/toolbar_extras');
			}
		}
	}
	
    /**
     * Sets up the admin menu.
     */ 
    function smartfiles_adminmenu() {
		global $CONFIG;
		if (get_context() == 'admin' && isadminloggedin()) {
            add_submenu_item(elgg_echo('smartfiles:admin:title'), $CONFIG->wwwroot . "mod/smartfiles/admin/menu.php");
		}
    }
	
	
    /**
     * Sets plugin parameters if run for the first time
     */
	function smartfiles_require_setup() {

		// When running for the first time, make sure some default settings will be applied to the plugin
		$plugin = find_plugin_settings('smartfiles');
		if (!isset($plugin->initialized)) {
			$default_mime_types = array(
				'application/pdf' => 'Portable Document Format',
				'image/tiff' => 'Tag Image File Format',
				'application/vnd.oasis.opendocument.text' => 'OpenDocument Text',
				'application/vnd.oasis.opendocument.spreadsheet' => 'OpenDocument Spreadsheet',
				'application/vnd.oasis.opendocument.presentation' => 'OpenDocument Presentation',
				'application/vnd.ms-excel' => 'Microsoft Excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Microsoft Excel 2007+',
				'application/powerpoint' => 'Microsoft Powerpoint',
				'application/vnd.ms-powerpoint' => 'Microsoft Powerpoint',
				'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'Microsoft Powerpoint 2007+',
				'application/msword' => 'Microsoft Word',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Microsoft Word 2007+',
				'image/photoshop' => 'Adobe Photoshop',
				'image/x-photoshop' => 'Adobe Photoshop',
				'image/psd' => 'Adobe Photoshop',
				'application/photoshop' => 'Adobe Photoshop',
				'application/psd' => 'Adobe Photoshop',
			);
			set_plugin_setting('mime_types', serialize($default_mime_types), 'smartfiles');
			set_plugin_setting('default_width', '480', 'smartfiles');
			set_plugin_setting('default_height', '400', 'smartfiles');
			set_plugin_setting('enable_resize', 'yes', 'smartfiles');
			set_plugin_setting('min_width', '320', 'smartfiles');
			set_plugin_setting('min_height', '240', 'smartfiles');
			set_plugin_setting('max_width', '695', 'smartfiles');
			set_plugin_setting('gallery_thumbs', 'yes', 'smartfiles');
			set_plugin_setting('force_thumbs', 'no', 'smartfiles');
			set_plugin_setting('auto_create', 'yes', 'smartfiles');
			set_plugin_setting('ignore_access', 'yes', 'smartfiles');
			set_plugin_setting('initialized', 1, 'smartfiles');
		}
	}

	/**
	 * Gets the embedit.in identifier of an Elgg file.
	 * This id can be used later to access the thumbnail or the flash content (that can be embedded in pages) of the given file.
	 * 
	 * @param ElggFile $file 		The file to get the embedit.in identifier of 
	 * @param boolean $doRequest	If set to true, and the file does not have an embedit.in id yet, 
	 * 								the function will upload the file to http://embedit.in and obtain an id for the uploaded file.
	 * 								If set to false, id will only be returned for the file if it already has been uploaded to embedit.in
	 * @return mixed				A 10 character embedit.in id for the file, 
	 * 								or false if the file has not been uploaded yet, and $doReuest was false
	 */
	function get_embedit_id($file, $doRequest = true) {
		
		global $CONFIG;
		
		$returnvalue = false;
		if ($file instanceof ElggFile) {
			$plugin = find_plugin_settings('smartfiles');
			if ((!isset($file->embedit_id)) && ($doRequest)) {
	
				// Check if the embedit.in API parameters have been set up
				$valid_setup = (isset($plugin->key) && isset($plugin->identifier));
				if (!$valid_setup) {
					register_error(elgg_echo('smartfiles:invalid_setup'));
					forward($_SERVER['HTTP_REFERER']);
				}
	
				// Create token protected link to serve the file for embedit.in
				$ts = time();
				$site_secret = get_site_secret();
				$embedit_token = sha1($site_secret . $plugin->key . $ts);
	    		$url = $CONFIG->wwwroot . 'pg/smartfiles/view/' . $file->guid . '/' . $ts . '/' . $embedit_token;
				$owner = get_entity($file->owner_guid);
				$signature = sha1($plugin->key . $owner->email . $plugin->identifier);
				
				// Create post request to embedit.in site
				$post_vars = array(
					"identifier" => $plugin->identifier, 
					"email" => $owner->email, 
					"signature" => $signature, 
					"url" => $url,
					"title" => $file->title,
					"allowed_url" => "/"	// Small hack suggested by Jeff Seibert Jr at embedit.in to disable public display
				);
				$curlobj = curl_init("http://embedit.in/api/v1/create/");
				curl_setopt($curlobj, CURLOPT_HEADER, 0);
				curl_setopt($curlobj, CURLOPT_POST, 1);
				curl_setopt($curlobj, CURLOPT_POSTFIELDS, $post_vars);
				curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($curlobj, CURLOPT_REFERER, $file->getUrl());
				curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
				$embedit_reply = curl_exec($curlobj);
				curl_close($curlobj);
				
				// Process
				$pattern = '/http:\/\/[^\/]+\/([^\.]+)\.swf/i';
				$found = preg_match($pattern, $embedit_reply, $matches);
				if (!$found) {
					error_log(sprintf(elgg_echo('smartfiles:badreply'), $embedit_reply));
				}
				$file->embedit_id = $matches[1];
				$file->save();
		    }
		    if (isset($file->embedit_id)) {
				$returnvalue =  $file->embedit_id;
		    }
		}
		return returnvalue;
	}
	
	function remove_embedit_object($embedit_id, $email) {

		$plugin = find_plugin_settings('smartfiles');
	
		// Check if the embedit.in API parameters have been set up
		$valid_setup = (isset($plugin->key) && isset($plugin->identifier));
		if (!$valid_setup) {
			register_error(elgg_echo('smartfiles:invalid_setup'));
			forward($_SERVER['HTTP_REFERER']);
		}
		
		// Create post request to embedit.in site
		$signature = sha1($plugin->key . $email . $plugin->identifier);
		$post_vars = array(
			"identifier" => $plugin->identifier, 
			"email" => $email, 
			"document_identifier" => $embedit_id, 
			"signature" => $signature, 
		);
		$curlobj = curl_init("http://embedit.in/api/v1/delete");
		curl_setopt($curlobj, CURLOPT_HEADER, 0);
		curl_setopt($curlobj, CURLOPT_POST, 1);
		curl_setopt($curlobj, CURLOPT_POSTFIELDS, $post_vars);
		curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($curlobj, CURLOPT_REFERER, $CONFIG->wwwroot);
		curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
		$embedit_reply = curl_exec($curlobj);
		curl_close($curlobj);

		// TODO: handle embedit response on delete request
	}
	
	function smartfiles_delete_cleanup($event, $object_type, $object) {
		// Make sure we execute cleanup only when necessary
		if ($object instanceof ElggFile && isset($object->embedit_id)) {
			$owner = get_entity($object->owner_guid);
			remove_embedit_object($object->embedit_id, $owner->email);
		}
	}
	
	function smartfiles_autocreate_embedit_id($event, $object_type, $object) {
		$plugin = find_plugin_settings('smartfiles');
		$mime_types = unserialize($plugin->mime_types);
		
		if ($object instanceof ElggFile) {
			$mime = $object->mimetype;
			$use_embedit = (array_key_exists($mime, $mime_types) && (($file->access_id == ACCESS_PUBLIC) || ($plugin->ignore_access == 'yes')));
			if ($use_embedit && ($plugin->auto_create == 'yes')) {
				get_embedit_id($object, true);
			}
		}
		return true;
	}

	/**
	 * smartfiles page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function smartfiles_page_handler($page) {
	
	    global $CONFIG;
	
        switch($page[0]) {
            case 'view':
                set_input('file_guid', $page[1]);
                set_input('__elgg_ts', $page[2]);
                set_input('__embedit_token', $page[3]);
                @include(dirname(__FILE__) . '/view.php');
				break;
			default:
            	break;                
        }
	    return true;
	}
	
	/**
	 * Provides special permissions on files when embedded by embedit.in API
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $parameters
	 */
	function smartfiles_permissions_check($hook_name, $entity_type, $return_value, $parameters) {
        if (get_context() == 'smartfiles_view') {
            return true;
        }
    }
    
    function validate_embedit_token() {
		$plugin = find_plugin_settings('smartfiles');
		$ts = get_input('__elgg_ts');
		$embedit_token = get_input('__embedit_token');
		$site_secret = get_site_secret();
		$generated_token = sha1($site_secret . $plugin->key . $ts);
		if ($embedit_token == $generated_token) {
			$now = time();
			$expire = 60; // request should be made within one minute
			if ($ts >= $now - $expire) {
				// $remote_address = $_SERVER['REMOTE_ADDR'];
				return true;
			}
		}
		return false;
    }
    
	
	// Initialise smartfiles plugin
	register_elgg_event_handler('init', 'system', 'smartfiles_init');
    
	// Register actions
	register_action("smartfiles/admin/mime_types/add", false, $CONFIG->pluginspath . "smartfiles/actions/admin/mime_types/add.php");
	register_action("smartfiles/admin/mime_types/delete", false, $CONFIG->pluginspath . "smartfiles/actions/admin/mime_types/delete.php");
	register_action("smartfiles/admin/embedded_files/remove", false, $CONFIG->pluginspath . "smartfiles/actions/admin/embedded_files/remove.php");
	register_action("smartfiles/admin/embedded_files/refresh", false, $CONFIG->pluginspath . "smartfiles/actions/admin/embedded_files/refresh.php");
	register_action("smartfiles/admin/embedded_files/disable", false, $CONFIG->pluginspath . "smartfiles/actions/admin/embedded_files/disable.php");
	register_action("smartfiles/admin/embedded_files/enable", false, $CONFIG->pluginspath . "smartfiles/actions/admin/embedded_files/enable.php");
	
?>
