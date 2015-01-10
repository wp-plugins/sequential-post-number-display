=== Sequential Post Number Display ===
Contributors: The Grey Parrots
Donate link: http://thegreyparrots.com/
Tags: Sequential Post Number, Post Number, Display Post Number, Post sequence
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sequential Post Number Display allow you to assign a sequential number(in chronological order) to posts and you can display it with each post. 
== Description ==

This plugin enables you to assign a sequential number to posts. So if I go to index and have 200 posts published on the total site, with 50 posts per page, I should see a 200, 199, 198…151, Page 2 should show 150, 149…101, Page 3 should show 100,99…51 and so on.

You can configure it for Pages, Categories and Tags!!

==Special Features==
                                                                  
Sequential Post Number Display offers you with a handful of useful features :

* Both Short Code and PHP code Option.

==Installation==

1. Upload the entire `sequential-post-number` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enjoy Post Number display.
4. To display the number put any of this code in your template file
`<?php echo do_shortcode('[sqNumber]');?>`
or
`<?php echo get_post_meta(get_the_ID(),'incr_number',true); ?>`
5. Alternatvely you may use the shortcode [sqNumber]

==Frequently Asked Questions==

= Does this plugin work with newest WP version and also older versions? =
Yes, this plugin works really fine with WordPress 4.1!
It is also compatible for older Wordpress versions upto 3.0.1.

= Is the plugin compatible with my theme? =
Yes, the plugin is completely compatible with all the Wordpress themes!


==Screenshots==
1. 

==Changelog==

= 0.0.1 =
* Initial release
= 1.1.0 =
* Introduced enable * disable options for Pages, Categories and Tags

==Upgrade Notice==

= 0.0.1 =
* Just released into the wild!
= 1.1.0 =
* Introduced enable * disable options for Pages, Categories and Tags

==Feedback==
Please!
If you don't rate my plugin as 5/5 - please write why - and I will add or change options and fix bugs. It's very unpleasant to see silent low rates. For more information and instructions on this plugin please visit http://thegreyparrots.com/.