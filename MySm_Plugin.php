<?php
	/*
	Plugin Name: MySmark
	Plugin URI: http://wordpress.org/plugins/mysmark/
	Description: A MySmark Plug-In for WordPress CMS
	Version: 1.0.9
	Author: M1rcu2, Giacomo Persichini
	Author URI: https://wordpress.org/plugins/mysmark/
	License: GPL2

	Copyright 2012  B-Sm@rk  (email : besmark@b-smark.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

	require_once "SDK/MySm_SDK.php";

	$mysm_plugin = new MySmark_Plugin;

	class MySmark_Plugin
	{
		// It registers the plugin actions
		function __construct()
		{
			register_uninstall_hook(__FILE__, array(  &$this, 'OnUninstall' ));
			add_action('admin_menu', array(&$this, 'CreateMenu'));
			add_filter('the_content', array(&$this, 'AppendWidget'));
			add_action('after_delete_post', array(&$this, 'RemoveOption'));
			add_action('admin_notices', array(&$this, 'MySmWarning'));
			add_action('wp_enqueue_scripts', array(&$this, 'EnableJScript'));
			add_action('add_meta_boxes', array(&$this, 'AddMetaBox'));
			add_action('save_post', array(&$this, 'MetaBoxSave'));
		}
		
		function CreateMenu()
		{
			add_menu_page('MySmark Plugin', 'MySmark', 'administrator', 'mysmark/MySm_AdminPage.php');
			add_submenu_page('mysmark/MySm_AdminPage.php', 'MySmark Control Panel', 'Control Panel', 'administrator', 'mysmark/MySm_AdminPage.php');
			add_submenu_page('mysmark/MySm_AdminPage.php', 'MySmark Control Panel - Unlink posts', 'Unlink Posts', 'administrator', 'mysmark/MySm_LinkPage.php');
			add_action('admin_init', array(&$this, 'RegisterSettings'));
		}
		
		function RegisterSettings()
		{
			register_setting('mysm-settings-group', 'mysm-oauth-cli');
			register_setting('mysm-settings-group', 'mysm-oauth-secr');
			register_setting('mysm-settings-group', 'mysm-orientation');
			register_setting('mysm-settings-group', 'mysm-width');
			register_setting('mysm-settings-group', 'mysm-height');
			register_setting('mysm-settings-group', 'mysm-alignment');
			register_setting('mysm-settings-group2', 'mysm-reset');
		}
		
		function OnUninstall()
		{
			delete_option('mysm-oauth-cli');
			delete_option('mysm-oauth-secr');
			delete_option('mysm-orientation');
			delete_option('mysm-width');
			delete_option('mysm-height');
			delete_option('mysm-alignment');
			UnlinkAllPosts();
		}
		
		function EnableJScript() {
			wp_register_script('iframe-resizer', plugins_url('/js/iframeResizer.min.js', __FILE__));
			wp_enqueue_script('iframe-resizer');
			wp_register_script('mysmscript', plugins_url('/js/mysmscript.js', __FILE__), array('iframe-resizer'));
			wp_enqueue_script('mysmscript');
			wp_register_style('mysmark-css', plugins_url('/css/mysmark.css', __FILE__));
			wp_enqueue_style('mysmark-css');
		}    
		
		function MySmWarning()
		{
			if (!get_option('mysm-oauth-cli') || !get_option('mysm-oauth-secr'))
				echo "<div id='mysm-warning' class='updated fade'><p><strong>".__('WARNING: ')."</strong> ".sprintf(__('You must <a href="%1$s">enter your MySmark API key</a> for it to work.'), "admin.php?page=mysmark/MySm_AdminPage.php")."</p></div>";
		}
		
		function MySmPOST($ID, $title, $url)
		{
			$client_id = get_option('mysm-oauth-cli');
			$client_secret = get_option('mysm-oauth-secr');
			
			if (!$client_id || !$client_secret)
				throw new Exception("MySmark API must be set.");
			
			try
			{
				$sdk = new MysmSDK($client_id, $client_secret);
				
				$mySmResult = null;
				$mySmArr = array(
					"name" => $title,
					"url" => $url,
					"image" => null,
					"singleVote" => false,
					"protected" => false,
					"expires" => null,
					"category" => "WordPressSmark",
					"external_ref" => "POST#".$ID,
					"json" => null,
					"location" => null,
				);
				$mySmResult = $sdk->api("me/objects", "POST", $mySmArr);
			}
			catch(Exception $e)
			{
				echo "MySmark Plugin Error<br />".$e->__toString()."<br />Check your OAuth credentials in the plugin control panel.";
			}
			return $mySmResult["id"];
		}
		
		function AppendWidget($content)
		{
			if (is_single())
			{
				global $post;
				global $websrc;
				
				$mysmID = get_option("MySmPostID".$post->ID);
				$checkDigit = strval($mysmID);
				
				// No widget for this post
				if ($checkDigit[0] == 'd')
					return $content;
				
				try
				{
					if (empty($mysmID))
					{
						$mysmID = $this->MySmPOST($post->ID, $post->post_title, $post->guid);
						update_option("MySmPostID".$post->ID, $mysmID);
					}
					
					$orientation = 't';
					$width = get_option('mysm-width');
					$height = get_option('mysm-height');
					$alignment = get_option('mysm-alignment');

					switch (get_option('mysm-orientation'))
					{
						case 0:
							{
								$orientation = 'l';
								break;
							}
						case 1:
							{
								$orientation = 't';

								if ($width < 230) {
									$width = 230;
									update_option('mysm-width', $width);
								}

								break;
							}
						case 2:
							{
								$orientation = 'r';

								if ($width < 690) {
									$width = 690;
								}
								
								break;
							}
						case 3:
							{
								$orientation = 'b';
								break;
							}
						default:
							break;
					}
					
					if (strcmp($orientation, 'b') == 0) {
						$script = '<script type="text/javascript" src="'.$websrc.'js/Embedder.js"></script>';
						$content .= '<p style="text-align:center;"><button id="MYSMARK_ID_'.$mysmID.'" class="mysmark_embed" title="Click here to share your opinion!" style="height: 48px; width: 134px; background-image: url('.$websrc.'embed-button.php?id='.$mysmID.'); background-color: transparent; border: 0px none; cursor: pointer; background-position: 0px 0px; background-repeat: no-repeat no-repeat;"></button></p>';
						$content = $script . $content;
					}
					else {
						$wpurl = (($_SERVER['HTTPS'] != "on") ? "http://" : "https://").$_SERVER['HTTP_HOST'];
						$content .= '<div class="mysmark-widget orientation-'.$orientation.' alignment-'.$alignment.'" style="'.($width ? 'max-width: ' . $width . 'px;' : '').' '.($height ? 'max-height: ' . $height . 'px;' : '').'">';
						$content .= '<iframe frameborder="0" id="mySmarkFrame" src="'.$websrc.'embed.php?id='.$mysmID.'&exturl='.$wpurl.'" onload="mySmarkEnableAutoresize(this);"></iframe>';
						$content .= '<iframe frameborder="0" scrolling="no" id="mySmarkCommentsFrame" src="'.$websrc.'embed.php?id='.$mysmID.'&comm=1&widget=0" width="100%" onload="mySmarkEnableAutoresize(this);"></iframe>';
						$content .= '</div><div style="clear: both;"></div>';
					}
				}
				catch (Exception $e)
				{
					$content .= "<strong>".$e->getMessage()."</strong>";
				}
			}
			return $content;
		}
		
		function AddMySmReference($post) {			
			try {
				$refID = $this->MySmPOST($post->ID, $post->post_title, $post->guid);
				add_option("MySmPostID".$post->ID, $refID);
			}
			catch (Exception $e) {
				echo "<strong>".$e->getMessage()."</strong>";
			}
		}
		
		function RemoveOption($postID)
		{
			delete_option("MySmPostID".$postID);
		}
		
		public function UnlinkAllPosts()
		{
			global $wpdb;
			
			$sql = $wpdb->prepare('DELETE FROM `wp_options` WHERE option_name LIKE "MySmPostID%%";');
			$wpdb->query($sql);
			$wpdb->flush();
		}
		
		// Add the Meta Box to the system
		function AddMetaBox() {
			add_meta_box( 
			'mysmark_metacheckbox',
			__('MySmark Plugin', 'MySmark_text'),
			array(&$this, 'MetaBoxContent'),
			'post',
			'side',
			'high'
			);
		}
		
		// Define the content of the Meta Box
		function MetaBoxContent() {
			global $post;
			
			// Verification
			wp_nonce_field( plugin_basename( __FILE__ ), 'mysmark_nonce');

			echo '<p>';
			_e('Do you want to add the MySmark Widget/Button to this post?', 'MySmark_text');
			echo '</p>';
			echo '<input type="hidden" name="mysmark_checkbox" value="0" />';
			
			// Initialize the checkbox
			$checked = "CHECKED";
			$choiceString = strval(get_option("MySmPostID".$post->ID));
			if (strcmp($choiceString[0], 'd') == 0)
				$checked = "";
			
			echo '<input type="checkbox" id="mysmark_checkbox" name="mysmark_checkbox" value="1" '.$checked.'/>';
			echo '&nbsp;<label for="mysmark_checkbox">';
			_e('MySmark', 'MySmark_text');
			echo '</label> ';
		}
		
		/*
		 * Save the user choice on the meta box
		 *
		 * Don't get confused by $post_id and post_ID,
		 * the first is the ID of the post revision, the
		 * second is the actual post ID.
		 */
		function MetaBoxSave($post_id) {
			// Don't execute this if the event is fired by the autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			return;

			// Verify nonce
			if (!wp_verify_nonce($_POST['mysmark_nonce'], plugin_basename( __FILE__ )))
			return;

			// Check permissions
			if ('post' == $_POST['post_type']) {
				if (!current_user_can('edit_post', $post_id))
				return;
			}
			
			global $post;
			$ID = $_POST['post_ID'];
			$choice = $_POST['mysmark_checkbox'];
			
			if (!get_option("MySmPostID".$ID))
				$this->AddMySmReference($post);
			
			$postOption = get_option("MySmPostID".$ID);
			$postOptionString = strval($postOption);
			
			// Activate / Deactivate the widget
			if ($choice == 1 && strcmp($postOptionString[0], 'd') == 0)
				update_option("MySmPostID".$ID, substr($postOptionString, 1));
			else if ($choice == 0 && strcmp($postOptionString[0], 'd') != 0)
				update_option("MySmPostID".$ID, 'd'.$postOptionString);
		}
	}
?>