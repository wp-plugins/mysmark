=== MySmark Plugin ===
Tags:  mysmark, emotion, mood, sentiment, feedback, comment, rating, feelings
Contributors: m1rcu2
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.0.5.1
License: GPLv2 or later (http://www.gnu.org/licenses/gpl-2.0.html) 

Upgrade comments and sharing of your blog and engage your visitors with emotional tagging via MySmark.

== Description ==

MySmark plugin allows your visitors to make powerful comments to your content using emotional tags and sharing them directly in Facebook and Twitter.
 With MySmark each reader of your blog can post a comment, opinion  or recommendation, give an emotional feedback using a personalised 
 tool and a simple interaction. For more information: [MySmark.com](https://www.mysmark.com/ "MySmark")

== Installation ==

1. Move the files from this archive into
		YOUR_WORDPRESS_PATH/wp-content/plugins/mysmark
   if you've downloaded the .zip package, or install it directly from your WordPress page.
2. **Activate** the MySmark plugin.
2. **Sign up** with [MySmark.com](https://www.mysmark.com/ "MySmark"). It's fast, fun and free!
3. Log in into your MySmark account and find the **Tools** tab in the top right corner of the page,
4. Click the **API Access** link located in the left side of the page just under your username,
5. Enter your website page address (*for example: https://www.mysmark.com/wops/*) in the **OAuth redirect URL** field and click **Create/Update**, this should generate your personal OAuth client id and OAuth client secret.
6. In the WordPress Admin Panel locate **MySmark Plugin Control Panel**.
7. Copy-paste your **OAuth client id** and **OAuth client secret** into the blank spaces and click save.
8. That's it! Your WordPress posts are ready to be smarked.

Or watch a movie that explains step-by-step how to install the plugin: [MySmark Wordpress Plugin Installation Instructions](http://youtu.be/YzUAhBBT8mc "MySmark Wordpress Plugin Installation Instructions")

**Make your Smark now!**

**Note**: The plugin will add a widget to every single post you create. You can access the statistics for your posts through [MySmark homepage](https://www.mysmark.com/ "MySmark") in the Tools tab. You should see a list of objects with a reference tp *WP-post* and titles being active links to your posts. At the bottom of each item on the list you should see **Get stats!** link - this link should bring you to the page where you can see general statistics for that particular post.

== Frequently Asked Questions ==

= What is MySmark? =
MySmark is your personal tool to say how you feel about people, things and places. A Smark is a new way of tagging content emotionally. 
Comment and share your opinion, feelings and reactions with your friends! 
Make sure you visit our website [MySmark](https://www.mysmark.com/ "MySmark")

= Why are the comments not displayed properly ? =
If you see any problems with your comments, please double check if the width of the widget in the **MySmark Plugin Control Panel** is set properly. 
We **strongly** recommend that the width you set should be **larger than 460px**. Play with it to adjust to your needs. 

= How can I personalise the widget ? =
Every user/visitor has its own widget that can be personalised in the [MySmark.com](https://www.mysmark.com/ "MySmark") in the Settings tab. So every time users want to leave a comment, they call their own personalised widget leaving even richer feedback on your posts.

*If you have any other questions, suggestions or you just want to say hi, please contact us at besmark(at)b-smark.com*

== Screenshots ==
1. The plugin: E-rose and comment list.
2. Settings in the admin page.

== Changelog ==
= 1.0.5.1 =
* Edited the README.txt file.
= 1.0.5 =
* Fixed: User is able to see in the user mysmark.com account stats for every posts.
* Edited the README.txt file.

= 1.0.4.1 =
* Edited the README.txt file.

= 1.0.4 =
* Change: Minimum width for left/right template set to 460.
* Fixed: iframe border in IE.

= 1.0.3 =
* Fixed: Wrong height resizing.

= 1.0.2 =
* Fixed: the URL to mysmark.com instead of localhost for OAuth credentials.
* Fixed: when unlinking posts and then revisiting posts now it will update the 
entry already existing in the DB instead of getting error for invalid (null) ID.
* Added: Templates in the plug-in Control Panel. Widget on top, right or left.
* Removed (temporary): Single vote option. It prevents users to post more than 
one comment.
* Added: It's now possible to set the width of the comment-list( + widget) in 
the Control Panel.
* Minor bug-fixes.

= 1.0.1 =
* Fixed the path to CP pages.

= 1.0 =
* First public plugin release.

== Upgrade Notice ==