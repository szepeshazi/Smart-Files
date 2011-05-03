<?php
	/**
	 * Elgg file browser.
	 * File renderer.
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	$file = $vars['entity'];
	
	$file_guid = $file->getGUID();
	$tags = $file->tags;
	$title = $file->title;
	$desc = $file->description;
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = friendly_time($vars['entity']->time_created);
	$mime = $file->mimetype;

	/* BEGIN - Original file changed to support smartfiles extension */ 
	$plugin = find_plugin_settings('smartfiles');
	$mime_types = unserialize($plugin->mime_types);
	$ts = time();
	$token = generate_action_token($ts);
	/* END - Original file changed to support smartfiles extension */ 
	
	
	
	if (!$title) {
		$title = elgg_echo('untitled');
	}
	
	if (get_context() == "search") { 	// Start search listing version 
		
		if (get_input('search_viewtype') == "gallery") {
			echo "<div class=\"filerepo_gallery_item\">";
			if ($vars['entity']->smallthumb) {
				echo "<p class=\"filerepo_title\">" . $file->title . "</p>";
				echo "<p><a href=\"{$file->getURL()}\"><img src=\"{$vars['url']}mod/file/thumbnail.php?size=medium&file_guid={$vars['entity']->getGUID()}\" border=\"0\" /></a></p>";
				echo "<p class=\"filerepo_timestamp\"><small><a href=\"{$vars['url']}pg/file/{$owner->username}\">{$owner->username}</a> {$friendlytime}</small></p>";

				//get the number of comments
				$numcomments = elgg_count_comments($vars['entity']);
				if ($numcomments)
					echo "<p class=\"filerepo_comments\"><a href=\"{$file->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a></p>";

				
				//if the user can edit, display edit and delete links
				if ($file->canEdit()) {
					echo "<div class=\"filerepo_controls\"><p>";
					echo "<a href=\"{$vars['url']}mod/file/edit.php?file_guid={$file->getGUID()}\">" . elgg_echo('edit') . "</a>&nbsp;";
					echo elgg_view('output/confirmlink',array(
						
							'href' => $vars['url'] . "action/file/delete?file=" . $file->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("file:delete:confirm"),
							'is_action' => true,
						
						));
					echo "</p></div>";
				}
					
			
			} else {
				echo "<p class=\"filerepo_title\">";
				/* BEGIN - Original file changed to support smartfiles extension */ 
				$use_embedit = (($plugin->gallery_thumbs == 'yes') && (array_key_exists($mime, $mime_types)));
				$use_embedit = ($use_embedit && (($file->access_id == ACCESS_PUBLIC) || ($plugin->ignore_access == 'yes')));
				if ($use_embedit && $file->canEdit()) {
					echo "<input type=\"checkbox\" name=\"file_guids[]\" value={$file->guid} />";
				}
				echo "{$title}</p>";
				$use_embedit = $use_embedit && !$file->disable_embed;
				if ($use_embedit && isset($plugin->force_thumbs) && $plugin->force_thumbs == 'yes') {
					get_embedit_id($file, true);
				}
				if ($use_embedit &&	isset($file->embedit_id)) {
					echo "<p><a href=\"{$file->getURL()}\"><img src=\"http://embedit.in/{$file->embedit_id}.png\" border=\"0\" /></a></p>";
				} else {
					echo "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file_guid, 'size' => 'large')) . "</a>";
				}
				/* END - Original file changed to support smartfiles extension */ 
				echo "<p class=\"filerepo_timestamp\"><small><a href=\"{$vars['url']}pg/file/{$owner->username}\">{$owner->name}</a> {$friendlytime}</small></p>";
				//get the number of comments
				$numcomments = elgg_count_comments($file);
				if ($numcomments)
					echo "<p class=\"filerepo_comments\"><a href=\"{$file->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a></p>";


			}
			echo "</div>";
			// echo elgg_view("search/gallery",array('info' => $info, 'icon' => $icon));
			
		} else {
		
			$info = "<p> <a href=\"{$file->getURL()}\">{$title}</a></p>";
			$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/file/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
			$numcomments = elgg_count_comments($file);
			if ($numcomments)
				$info .= ", <a href=\"{$file->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
			$info .= "</p>";
			
			// $icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'small'));
			$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file_guid, 'size' => 'small')) . "</a>";
			
			echo elgg_view_listing($icon, $info);
		
		}
		
	} else {							// Start main version
	
?>
	<div class="filerepo_file">
		<div class="filerepo_icon">
					<a href="<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php 
						
						echo elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file_guid)); 
						
					?></a>					
		</div>
		
		<div class="filerepo_title_owner_wrapper">
		<?php
			//get the user and a link to their gallery
			$user_gallery = $vars['url'] . "mod/file/search.php?md_type=simpletype&subtype=file&tag=image&owner_guid=" . $owner->guid . "&search_viewtype=gallery";
		?>
		<div class="filerepo_user_gallery_link"><a href="<?php echo $user_gallery; ?>"><?php echo sprintf(elgg_echo("file:user:gallery"),''); ?></a></div>
		<div class="filerepo_title"><h2><a href="<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo $title; ?></a></h2></div>
		<div class="filerepo_owner">
				<?php

					echo elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
				
				?>
				<p class="filerepo_owner_details"><b><a href="<?php echo $vars['url']; ?>pg/file/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a></b><br />
				<small><?php echo $friendlytime; ?></small></p>
		</div>
		</div>

		
		<div class="filerepo_maincontent">
		
				<div class="filerepo_description"><?php echo elgg_view('output/longtext', array('value' => $desc)); ?></div>
				<div class="filerepo_tags">
<?php

		if (!empty($tags)) {

?>
		<div class="object_tag_string"><?php

					echo elgg_view('output/tags',array('value' => $tags));
				
				?></div>
<?php
		}

		$categories = elgg_view('categories/view',$vars);
		if (!empty($categories)) {
?>
		<div class="filerepo_categories">
			<?php

				echo $categories;
			
			?>
		</div>
<?php
		}

?>
				</div>
		<?php 
			/* BEGIN - Original file changed to support smartfiles extension */ 
			$use_embedit = (array_key_exists($mime, $mime_types) && (($file->access_id == ACCESS_PUBLIC) || ($plugin->ignore_access == 'yes')));
			if ($use_embedit && !$file->disable_embed) {
				echo "<div class=\"filerepo_specialcontent\">".elgg_view('smartfiles/smartfiles-content', $vars)."</div>";
			} else if (elgg_view_exists('file/specialcontent/' . $mime)) {
				echo "<div class=\"filerepo_specialcontent\">".elgg_view('file/specialcontent/' . $mime, $vars)."</div>";
			} else if (elgg_view_exists("file/specialcontent/" . substr($mime,0,strpos($mime,'/')) . "/default")) {
				echo "<div class=\"filerepo_specialcontent\">".elgg_view("file/specialcontent/" . substr($mime,0,strpos($mime,'/')) . "/default", $vars)."</div>";
			}
		?>
		
		<?php 
			if ($use_embedit && $file->canEdit()) {
				$refresh_link = $vars['url'] . 'action/smartfiles/admin/embedded_files/refresh?file_guids[]=' . $file->guid . '&__elgg_token=' . $token . '&__elgg_ts=' . $ts;
				$disable_link = elgg_view("output/confirmlink", array(
					'href' => $vars['url'] . 'action/smartfiles/admin/embedded_files/disable?file_guids[]=' . $file->guid . '&__elgg_token=' . $token . '&__elgg_ts=' . $ts,
					'text' => elgg_echo('smartfiles:embed:disable'),
					'confirm' => elgg_echo('smartfiles:embed:disable:confirm')
				));
				$enable_link = $vars['url'] . 'action/smartfiles/admin/embedded_files/enable?file_guids[]=' . $file->guid . '&__elgg_token=' . $token . '&__elgg_ts=' . $ts;
				
				?>
			<div class="filerepo_download">
				<p>
					<?php if ($file->disable_embed) :?>
						<?php echo elgg_echo('smartfiles:embed:disabled:info'); ?>
						<a href="<?php echo $enable_link; ?>"><?php echo elgg_echo('smartfiles:embed:enable'); ?></a>
					<?php else : ?>
						<?php echo elgg_echo('smartfiles:embed:refresh:info'); ?>
						<a href="<?php echo $refresh_link; ?>"><?php echo elgg_echo('smartfiles:embed:refresh'); ?></a>
						&nbsp;or&nbsp;
						<?php echo $disable_link; ?>
					<?php endif; ?>
					
				</p>
			</div>
		<?php } /* END - Original file changed to support smartfiles extension */  ?>
		
		<div class="filerepo_download"><p><a href="<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo elgg_echo("file:download"); ?></a></p></div>
		
<?php

	if ($file->canEdit()) {
?>

	<div class="filerepo_controls">
				<p>
					<a href="<?php echo $vars['url']; ?>mod/file/edit.php?file_guid=<?php echo $file->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a>&nbsp; 
					<?php 
						echo elgg_view('output/confirmlink',array(
						
							'href' => $vars['url'] . "action/file/delete?file=" . $file->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("file:delete:confirm"),
							'is_action' => true,
						
						));  
					?>
				</p>
	</div>

<?php		
	}

?>
	</div>
</div>

<?php

	if ($vars['full']) {
		
		echo elgg_view_comments($file);
		
	}

?>

<?php

	}

?>