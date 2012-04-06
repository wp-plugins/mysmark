<?php
	/*
	Plugin Name: MySmark
	Plugin URI: http://mysmark.com
	Description: A MySmark Plug-In for WordPress CMS
	Version: 0.5
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

	//Custom exception class for future implementations.
	class MySmarkException extends Exception
	{
		function __construct($msg)
		{
			parent::__construct('MySmarkException: '.$msg);
		}
	}

	class MySmark_Plugin
	{
       
    //It registers the plugin actions
		function __construct()
		{
			register_activation_hook( __FILE__, array(  &$this, 'OnActivate' ) );
			register_deactivation_hook( __FILE__, array(  &$this, 'OnDeactivate' ) );
			add_action('admin_menu', array(&$this, 'CreateMenu'));
			add_filter('the_content', array(&$this, 'AppendWidget'));
			add_action('publish_post', array(&$this, 'AddMySmReference'));
			add_action('after_delete_post', array(&$this, 'RemoveOption'));
			add_action('admin_notices', array(&$this, 'MySmWarning'));
		}
		
		
		function CreateMenu()
		{
			add_menu_page('MySmark Plugin', 'MySmark CP', 'administrator', '/MySmark/MySm_AdminPage.php');
			//to avoid duplicated links
			add_submenu_page('/MySmark/MySm_AdminPage.php', 'MySmark Control Panel', 'Control Panel', 'administrator', '/MySmark/MySm_AdminPage.php');
			add_submenu_page('/MySmark/MySm_AdminPage.php', 'MySmark Control Panel - Unlink posts', 'Unlink Posts', 'administrator', '/MySmark/MySm_LinkPage.php');
			add_action( 'admin_init', array(&$this, 'RegisterSettings'));
		}
		
		function RegisterSettings()
		{
			register_setting( 'mysm-settings-group', 'mysm-oauth-cli' );
			register_setting( 'mysm-settings-group', 'mysm-oauth-secr' );
//			register_setting( 'mysm-settings-group', 'mysm-template' );
//			register_setting( 'mysm-settings-group', 'mysm-multimedia' );
//			register_setting( 'mysm-settings-group', 'mysm-share' );
			register_setting( 'mysm-settings-group', 'mysm-singlevote' );
			register_setting( 'mysm-settings-group2', 'mysm-reset' );
		}
		
		function OnDeactivate()
		{
			delete_option('mysm-oauth-cli');
			delete_option('mysm-oauth-secr');
//			delete_option('mysm-template');
//			delete_option('mysm-multimedia');
//			delete_option('mysm-share');
		}
		
		function MySmWarning()
		{
			if (!get_option('mysm-oauth-cli') || !get_option('mysm-oauth-secr'))
				echo "<div id='mysm-warning' class='updated fade'><p><strong>".__('WARNING: ')."</strong> ".sprintf(__('You must <a href="%1$s">enter your MySmark API key</a> for it to work.'), "admin.php?page=MySmark/MySm_AdminPage.php")."</p></div>";
		}	
		
		//Not yet needed
		function OnActivate()
		{
		}
		
		function MySmPOST($ID, $title, $url, $singleVote = true, $protected = false, $descr = 'WP-post', $category = 'WordPressSmark')
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
						$singlevote = get_option('mysm-singlevote');
						$mysmID = $this->MySmPOST($post->ID, $post->post_title, $post->guid, !empty($singlevote));
						add_option("MySmPostID".$post->ID, $mysmID);
					}
					$content .= '<br/><br/><iframe id="mySmarkFrame" src="'.$websrc.'embed.php?id='.$mysmID.'&comm=1" height="340" width="320"></iframe>';
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
