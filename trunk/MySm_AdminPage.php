<div class="wrap">
	<h2>MySmark Plugin Control Panel</h2>
	<form method="post" action="options.php">
	<?php settings_fields( 'mysm-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top" style="text-align: center;">
				<th scope="row">OAuth Client ID*</th>
				<td><input type="text" name="mysm-oauth-cli" value="<?php echo get_option('mysm-oauth-cli'); ?>" /></td>
			</tr>
			<tr valign="top" style="text-align: center;">
				<th scope="row">OAuth Client Secret*</th>
				<td><input type="text" name="mysm-oauth-secr" value="<?php echo get_option('mysm-oauth-secr'); ?>" /></td>
			</tr>
			<tr valign="top" style="text-align: center;">
				<th scope="row">Width</th>
				<td><input type="text" name="mysm-width" size="5" value="<?php echo ((!get_option('mysm-width')) ? 400 : get_option('mysm-width')); ?>" />px</td>
			</tr>
			<tr valign="top" style="text-align: center;">
				<th scope="row">Template</th>
				<?php
					for ($i = 0; $i < 3; $i++)
					{
						echo "<td><input type=\"radio\" id=\"mysm-orientation$i\" name=\"mysm-orientation\" value=\"$i\" ";
						if ($i == get_option('mysm-orientation'))
							echo "checked=\"checked\" ";
						echo "/></td>";
					}
				?>
			</tr>
			<tr valign="top" style="text-align: center;">
				<th scope="row"></th>
				<td width="100"><label for="mysm-orientation0"><img src="<?php echo WP_PLUGIN_URL; ?>/mysmark/images/left.jpg" /></label></td>
				<td width="100"><label for="mysm-orientation1"><img src="<?php echo WP_PLUGIN_URL; ?>/mysmark/images/center.jpg" /></label></td>
				<td width="100"><label for="mysm-orientation2"><img src="<?php echo WP_PLUGIN_URL; ?>/mysmark/images/right.jpg" /></label></td>
			</tr>
			<!-- NOT YET IMPLEMENTED
			<tr valign="top" style="text-align: center;">
				<th scope="row">Template</th>
				<?php
					for ($i = 0; $i < 3; $i++)
					{
						echo "<td><input type=\"radio\" id=\"mysm-template$i\" name=\"mysm-template\" value=\"$i\" ";
						if ($i == get_option('mysm-template'))
							echo "checked=\"checked\" ";
						echo "/></td>";
					}
				?>
			</tr>
			<tr valign="top" style="text-align: center;">
				<th scope="row"></th>
				<td width="100"><label for="mysm-template0"><img src="<?php echo WP_PLUGIN_URL; ?>/mysmark/images/classic.jpg" /></label></td>
				<td width="100"><label for="mysm-template1"><img src="<?php echo WP_PLUGIN_URL; ?>/mysmark/images/compact.jpg" /></label></td>
				<td width="100"><label for="mysm-template2"><img src="<?php echo WP_PLUGIN_URL; ?>/mysmark/images/noshare.jpg" /></label></td>
			</tr>
			<tr valign="top">
				<th scope="row">Allow multimedia content</th>
				<td><input type="checkbox" name="mysm-multimedia" <?php checked(get_option('mysm-multimedia'),"on"); ?> /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Allow user to share</th>
				<td><input type="checkbox" name="mysm-share" <?php checked(get_option('mysm-share'),"on"); ?> /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Single vote for post</th>
				<td><input type="checkbox" name="mysm-singlevote" <?php checked(get_option('mysm-singlevote'),"on"); ?> /></td>
			</tr>-->
			</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
