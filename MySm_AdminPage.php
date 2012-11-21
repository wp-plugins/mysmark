<div class="wrap">
	<h2>MySmark Plugin Control Panel</h2>
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
			<tr valign="top" style="text-align:center;">
				<th scope="row">Width</th>
				<td><input type="text" name="mysm-width" size="5" value="<?php echo ((!get_option('mysm-width')) ? 460 : get_option('mysm-width')); ?>" />px</td>
			</tr>
			<tr valign="top" style="text-align:center;">
				<th scope="row">Template</th>
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
