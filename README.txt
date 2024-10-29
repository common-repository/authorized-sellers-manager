=== Ads.txt Publisher ===
Contributors: alexandrucbrightcom
Donate link: http://brightroll.com
Tags: ads.txt, adstxt, publisher, ads
Requires at least: 4.0
Requires PHP: 5.2.4
Tested up to: 4.9
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate, edit, validate and publish your ads.txt from WordPress, just like any other piece of content. Now anyone can easily edit their ads.txt file anytime

== Description ==

*ABOUT ADS.TXT*

The IAB Tech Lab recently introduced the Ads.txt initiative (official website) to increase transparency in the programmatic advertising ecosystem. The “Ads” in Ads.txt stands for Authorized Digital Sellers, and it is a relative simple method for publishers to declare who is authorized to sell their digital inventory. In short, the Ads.txt file contains the publisher’s out loud references for his authorized sellers.

The process is pretty straightforward. The publisher puts a text file in his web server on the root level of the domain (publishersdomain.com/ads.txt), it must be called ads.txt, and it must have its Read permissions set to “World”. The file must obviously follow the IAB format (see below).

This file should list all of the companies that are authorized to sell the publishers’ inventory. Similarly, programmatic platforms will integrate ads.txt files to confirm which publishers’ inventory they are authorized to sell. This will allows buyers to check the validity of the inventory they purchase.

*ABOUT ADS.TXT PUBLISHER*

Ads.tx Publisher was created by Brightcom, a leading media house and a strong supporter of the Ads.txt Initiative to allow publishers to easily edit, validate and upload their ads.txt files within WordPress without any need for engineering resources and with the same ease as any other media asset.


*TECHNICAL NOTES*

* Requires PHP 5.3+.
* Requires WordPress 4.9+. Older versions of WordPress will not display any syntax highlighting and may break JavaScript and/or be unable to localize the plugin.
* Rewrites need to be enabled. Without rewrites, WordPress cannot know to supply /ads.txt when requested.
* Your site URL must not contain a path (e.g. https://example.com/site/ or path-based multisite installs). While the plugin will appear to function in the admin, it will not display the contents at https://example.com/site/ads.txt. This is because the plugin follows the IAB spec, which requires that the ads.txt file be located at the root of a domain or subdomain.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `ads-txt-publisher.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0.12 =
* Our first version of plugin