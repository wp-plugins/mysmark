<div class="wrap">
	<h2>MySmark Plugin - Unlink Posts</h2>
	<?php if (get_option('mysm-reset') == 'unlinkposts') :
		$mysm_plugin->UnlinkAllPosts();
		delete_option('mysm-reset');
	?>
		Posts unlinked from MySmark.

	<?php else : ?>
		<br />
		<strong>Unlinking posts from MySmark will delete every smark and remove any entry in the Wordpress database.</strong>
		<br />
		<form method="post" action="options.php">
		<?php settings_fields( 'mysm-settings-group2' ); ?>
			<input type="hidden" name="mysm-reset" value="unlinkposts">
			<input type="submit" class="button-primary" value="<?php _e('Unlink Posts') ?>" />
		</form>
	<?php endif; ?>
</div>