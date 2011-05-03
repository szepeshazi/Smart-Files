
<p>
	<label><?php echo elgg_echo('smartfiles:key'); ?></label>
	<input type="text" name="params[key]" value="<?php echo $vars['entity']->key; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:identifier'); ?></label>
	<input type="text" name="params[identifier]" value="<?php echo $vars['entity']->identifier; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:width'); ?></label>
	<input type="text" name="params[default_width]" value="<?php echo $vars['entity']->default_width; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:height'); ?></label>
	<input type="text" name="params[default_height]" value="<?php echo $vars['entity']->default_height; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:resize:enable'); ?></label>
	<select name="params[enable_resize]">
		<option value="yes" <?php echo ($vars['entity']->enable_resize == 'yes' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:yes'); ?></option>
		<option value="no" <?php echo ($vars['entity']->enable_resize == 'no' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:no'); ?></option>
	</select>
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:min_width'); ?></label>
	<input type="text" name="params[min_width]" value="<?php echo $vars['entity']->min_width; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:min_height'); ?></label>
	<input type="text" name="params[min_height]" value="<?php echo $vars['entity']->min_height; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:max_width'); ?></label>
	<input type="text" name="params[max_width]" value="<?php echo $vars['entity']->max_width; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:max_height'); ?></label>
	<input type="text" name="params[max_height]" value="<?php echo $vars['entity']->max_height; ?>" />
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:gallery_thumbs:enable'); ?></label>
	<select name="params[gallery_thumbs]">
		<option value="yes" <?php echo ($vars['entity']->gallery_thumbs == 'yes' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:yes'); ?></option>
		<option value="no" <?php echo ($vars['entity']->gallery_thumbs == 'no' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:no'); ?></option>
	</select>
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:force_thumbs:enable'); ?></label>
	<select name="params[force_thumbs]">
		<option value="yes" <?php echo ($vars['entity']->force_thumbs == 'yes' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:yes'); ?></option>
		<option value="no" <?php echo ($vars['entity']->force_thumbs == 'no' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:no'); ?></option>
	</select>
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:auto_create:enable'); ?></label>
	<select name="params[auto_create]">
		<option value="yes" <?php echo ($vars['entity']->auto_create == 'yes' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:yes'); ?></option>
		<option value="no" <?php echo ($vars['entity']->auto_create == 'no' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:no'); ?></option>
	</select>
</p>
<p>
	<label><?php echo elgg_echo('smartfiles:ignore_access:enable'); ?></label>
	<select name="params[ignore_access]">
		<option value="yes" <?php echo ($vars['entity']->ignore_access == 'yes' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:yes'); ?></option>
		<option value="no" <?php echo ($vars['entity']->ignore_access == 'no' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('smartfiles:no'); ?></option>
	</select>
</p>
