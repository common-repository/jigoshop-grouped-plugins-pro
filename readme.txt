=== Jigoshop Grouped Products Pro ===
Contributors: chriscct7
Requires at least: 3.4.2
Tested up to: 3.6 Beta 3
Contributors: chriscct7
Donate link: http://donate.chriscct7.com/
Tags: jigoshop, grouped products, jigoshop grouped products
Requires at least: 3.4.2
Tested up to: 3.6 Beta 3
Stable tag: 4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This plugin allows you to extend the abilities of the grouped product in Jigoshop

This was previously a premium extension, made free per [this blog post of mine](http://chriscct7.me/on-retiring-jigoshop-extensions/).

== Installation ==

1. Unzip the archive on your computer  
2. Upload `jigoshop-grouped-products-pro` directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Settings ==

Parent Product
There are 4 settings for the parent product:

1. Add a min number of child items per parent product. Use to require at least X number of items be added per "package". 0=no limit
2. Add a max number of child items per parent product. Can be used to limit number of items per "package". 0=no limit
3. Add a price for the parent. Used if you charge for the parent alone, or charge for the parent and then the child items are add-ons. 0=no price
4. Add a quantity to parent. Currently does not auto-deduct (will in next version). Useful, if you only wish to sell the product as a limited package (first 100 ordered). When combined with Jigoshop's "Remove item on out of stock", can be used to temp/perm remove item from shop.

Child Product
There are 2 settings for the child product:

1. Add a min number of child items. Used to require at least X number of child option X be added. Not enforced in this version completely. 0=no limit
2. Add a max number of child items. Used to limit the number to one child option to X amount. 0=no limit

== Screenshots ==

1. The settings panel

2. The new select2 dropdown when Enhanced Author Dropdown is on 

== Support And Contributing ==

All support for chriscct7 plugins are done via the forum on wordpress.org. 

If you'd like to help, feel free to contribute at the [GitHub Repo](https://github.com/chriscct7/Jigoshop-Grouped-Products-Pro) for this plugin.

== Frequently Asked Questions ==

1. How do I make grouped products:
Answer: See [this](http://forum.jigoshop.com/kb/creating-products/grouped-products) Jigoshop KB article.

== Changelog ==
= 4.0 = 
* Released on wordpress.org

= 3.0 branch =
* Updates to autoupdater

= 2.1.0 =
* Fixes Price of Parent Product Issue
* Rewords error if max=min

= 2.1.0 =
* Fixes Price of Parent Product Issue
* Rewords error if max=min

= 2.0.0 =
* Remove Freezone Feature
* Add price for parent product
* Add quantity for parent product
* Allow for limits on individual child items
* Update documentation
* Revamp backend
* Add validation of numbers

= 1.1.0 =
* Add Documentation
* Improved the backend

= 1.0.0 =
* Initial Release

== Upgrade Notice ==
= 4.0 = 
* Released on wordpress.org

= 3.0 branch =
* Updates to autoupdater

= 2.1.0 =
* Fixes Price of Parent Product Issue
* Rewords error if max=min

= 2.1.0 =
* Fixes Price of Parent Product Issue
* Rewords error if max=min

= 2.0.0 =
* Remove Freezone Feature
* Add price for parent product
* Add quantity for parent product
* Allow for limits on individual child items
* Update documentation
* Revamp backend
* Add validation of numbers

= 1.1.0 =
* Add Documentation
* Improved the backend

= 1.0.0 =
* Initial Release