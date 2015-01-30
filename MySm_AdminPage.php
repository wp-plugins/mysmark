<?php
	$orientation = get_option('mysm-orientation');

	if ($orientation == 0 || $orientation == 2) {
		if (get_option('mysm-width') < 710) {
			update_option('mysm-width', 710);
		}
	} else if ($orientation == 1) {
		if (get_option('mysm-width') < 250) {
			update_option('mysm-width', 250);
		}
	}
?>

<div class="wrap">
	<h2><?php _e('MySmark Plugin Control Panel', 'MySmark_text'); ?></h2>
	<form method="post" action="options.php">
	<?php settings_fields( 'mysm-settings-group' ); ?>
		<table class="form-table" style="width:40%;">
			<tr valign="top" style="text-align:center;">
				<th scope="row">OAuth Client ID*</th>
				<td><input type="text" name="mysm-oauth-cli" value="<?php echo get_option('mysm-oauth-cli'); ?>" /></td>
			</tr>
			<tr valign="top" style="text-align:center;">
				<th scope="row">OAuth Client Secret*</th>
				<td><input type="text" name="mysm-oauth-secr" value="<?php echo get_option('mysm-oauth-secr'); ?>" /></td>
			</tr>
			<tr valign="top">
				<td colspan="2" style="text-align: center;">
					<strong><?php _e('Container properties', 'MySmark_text'); ?></strong>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Max Width', 'MySmark_text'); ?></th>
				<td>
					<input type="text" name="mysm-width" size="5" value="<?php echo ((!get_option('mysm-width')) ? '' : get_option('mysm-width')); ?>" />px
					<p><?php _e('Max width of the plugin container, leave blank for full width. Min width for left or right template is 710px. Min width for top template is 250px.', 'MySmark_text'); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Max Height', 'MySmark_text'); ?></th>
				<td>
					<input type="text" name="mysm-height" size="5" value="<?php echo ((!get_option('mysm-height')) ? '' : get_option('mysm-height')); ?>" />px
					<p><?php _e('Max height of the plugin container, leave blank for automatic height', 'MySmark_text'); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Alignment', 'MySmark_text'); ?></th>
				<td>
					<label for="mysm-alignment-left"><input type="radio" id="mysm-alignment-left" name="mysm-alignment" value="left" <?php if (get_option('mysm-alignment') == 'left'): ?>checked="checked"<?php endif; ?>/> <?php _e('Left', 'MySmark_text'); ?></label>
					<label for="mysm-alignment-center"><input type="radio" id="mysm-alignment-center" name="mysm-alignment" value="center" <?php if (get_option('mysm-alignment') == 'center' || !get_option('mysm-alignment')): ?>checked="checked"<?php endif; ?> /> <?php _e('Center', 'MySmark_text'); ?></label>
					<label for="mysm-alignment-right"><input type="radio" id="mysm-alignment-right" name="mysm-alignment" value="right" <?php if (get_option('mysm-alignment') == 'right'): ?>checked="checked"<?php endif; ?>/> <?php _e('Right', 'MySmark_text'); ?></label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Template', 'MySmark_text'); ?></th>
				<td></td>
			</tr>
			<?php
			$array = array("left", "center", "right", "embed-button");
			for ($i = 0; $i < 4; $i++) {
			?>
			<tr valign="top" style="text-align:center;">
				<th scope="row"></th>
				<td width="100" style="border-top:1px solid black;"><input type="radio" id="mysm-orientation<?php echo $i;?>" name="mysm-orientation" value="<?php echo $i;?>" <?php echo ($i == get_option('mysm-orientation') ? "checked=\"checked\"" : null); ?>/></td>
			</tr>
			<tr valign="top" style="text-align:center;">
				<th scope="row"></th>
				<td width="100"><label for="mysm-orientation<?php echo $i;?>"><img src="<?php echo WP_PLUGIN_URL . "/mysmark/images/$array[$i].jpg";?>" /></label></td>
			</tr>
			<?php
			}
			?>
			</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
