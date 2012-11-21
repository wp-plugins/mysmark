<?php
	/*
	Plugin Name: MySmark
	Plugin URI: http://mysmark.com
	Description: A MySmark Plug-In for WordPress CMS
	Version: 1.0.6.1
	Author: M1rcu2
	Author URI: http://b-smark.com/mirco
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

	// Custom exception class for future implementations.
	class MySmarkException extends Exception
	{
		function __construct($msg)
		{
			parent::__construct('MySmarkException: '.$msg);
		}
	}

	class MySmark_Plugin
	{
		// It registers the plugin actions
		function __construct()
		{
			register_activation_hook( __FILE__, array(  &$this, 'OnActivate' ) );
			register_deactivation_hook( __FILE__, array(  &$this, 'OnDeactivate' ) );
			register_uninstall_hook(__FILE__, array(  &$this, 'OnUninstall' ));
			add_action('admin_menu', array(&$this, 'CreateMenu'));
			add_filter('the_content', array(&$this, 'AppendWidget'));
			add_action('publish_post', array(&$this, 'AddMySmReference'));
			add_action('after_delete_post', array(&$this, 'RemoveOption'));
			add_action('admin_notices', array(&$this, 'MySmWarning'));
			add_action('wp_enqueue_scripts', array(&$this, 'EnableJScript'));
		}
		
		
		function CreateMenu()
		{
			add_menu_page('MySmark Plugin', 'MySmark', 'administrator', 'mysmark/MySm_AdminPage.php');
			// to avoid duplicated links
			add_submenu_page('mysmark/MySm_AdminPage.php', 'MySmark Control Panel', 'Control Panel', 'administrator', 'mysmark/MySm_AdminPage.php');
			add_submenu_page('mysmark/MySm_AdminPage.php', 'MySmark Control Panel - Unlink posts', 'Unlink Posts', 'administrator', 'mysmark/MySm_LinkPage.php');
			add_action( 'admin_init', array(&$this, 'RegisterSettings'));
		}
		
		function RegisterSettings()
		{
			register_setting( 'mysm-settings-group', 'mysm-oauth-cli' );
			register_setting( 'mysm-settings-group', 'mysm-oauth-secr' );
			register_setting( 'mysm-settings-group', 'mysm-orientation' );
			register_setting( 'mysm-settings-group', 'mysm-width' );
//			register_setting( 'mysm-settings-group', 'mysm-singlevote' );
			register_setting( 'mysm-settings-group2', 'mysm-reset' );
		}
		
		function OnDeactivate()
		{
			// Nothing to do here
		}
		
		function OnUninstall()
		{
			delete_option('mysm-oauth-cli');
			delete_option('mysm-oauth-secr');
			delete_option('mysm-template');
			delete_option('mysm-multimedia');
			delete_option('mysm-share');
			UnlinkAllPosts();
		}
		
		function EnableJScript() {
			wp_register_script( 'mysmscript', plugins_url('/js/mysmscript.js', __FILE__));
			wp_enqueue_script( 'mysmscript' );
		}    
		
		function MySmWarning()
		{
			if (!get_option('mysm-oauth-cli') || !get_option('mysm-oauth-secr'))
				echo "<div id='mysm-warning' class='updated fade'><p><strong>".__('WARNING: ')."</strong> ".sprintf(__('You must <a href="%1$s">enter your MySmark API key</a> for it to work.'), "admin.php?page=mysmark/MySm_AdminPage.php")."</p></div>";
		}
		
		// Not yet needed
		function OnActivate()
		{
		}
		
		function MySmPOST($ID, $title, $url, $singleVote = false, $protected = false, $descr = 'WP-post', $category = 'WordPressSmark')
		{
			$client_id = get_option('mysm-oauth-cli');
			$client_secret = get_option('mysm-oauth-secr');
			
			if (!$client_id || !$client_secret)
				throw new MySmarkException("MySmark API must be set.");
			
			try
			{
				$sdk = new MysmSDK($client_id, $client_secret);
				
				$mySmResult = null;
				$mySmArr = array(
					"name" => $title,
					"description" => $descr,
					"url" => $url,
					"image" => null,
					"singleVote" => $singleVote,
					"protected" => $protected,
					"expires" => null,
					"category" => $category,
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
				try
				{
					if (empty($mysmID))
					{
						$mysmID = $this->MySmPOST($post->ID, $post->post_title, $post->guid);
						update_option("MySmPostID".$post->ID, $mysmID);
					}
					$orientation = 't';
					$width = '230';
					$height = '660';
					
					switch (get_option('mysm-orientation'))
					{
						case 0:
							{
								$orientation = 'l';
								if (get_option('mysm-width'))
									$width = get_option('mysm-width');
								else
									$width = 460;
								$height = '330';
								break;
							}
						case 1:
							{
								$orientation = 't';
								if (get_option('mysm-width'))
									$width = get_option('mysm-width');
								else
									$width = 230;
								$height = '330';
								break;
							}
						case 2:
							{
								$orientation = 'r';
								if (get_option('mysm-width'))
									$width = get_option('mysm-width');
								else
									$width = 460;
								$height = '330';
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
						$script = '<script type="text/javascript" src="https://www.mysmark.com/js/Embedder.js"></script>';
						$content .= '<p style="text-align:center;"><button id="MYSMARK_ID_'.$mysmID.'" class="mysmark_embed" title="Click here to share your opinion!" style="height: 48px; width: 134px; background-image: url(https://www.mysmark.com/embed-button.php?id='.$mysmID.'); background-color: transparent; border: 0px none; cursor: pointer; background-position: 0px 0px; background-repeat: no-repeat no-repeat;"></button></p>';
						$content = $script . $content;
					}
					else {
						$wpurl = (($_SERVER['HTTPS'] != "on") ? "http://" : "https://").$_SERVER['HTTP_HOST'];
						$content .= '<p style="text-align:center;"><iframe frameborder="0" style="overflow-x: hidden; overflow-y: hidden;" id="mySmarkFrame" src="'.$websrc.'embed.php?id='.$mysmID.'&comm=1&wh='.$width.'&pos='.$orientation.'&exturl='.$wpurl.'" height="'.($height+10).'" width="'.($width+5).'"></iframe></p>';
					}
				}
				catch (MySmarkException $e)
				{
					$content .= "<strong>".$e->getMessage()."</strong>";
				}
			}
			return $content;
		}
		
		function AddMySmReference($post_ID)
		{
			global $post;
			
			try
			{
				//$singlevote = get_option('mysm-singlevote');
				$refID = $this->MySmPOST($post->ID, $post->post_title, $post->guid, !empty($singlevote));
				add_option("MySmPostID".$post_ID, $refID);
			}
			catch (MySmarkException $e)
			{
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
	}
?>
