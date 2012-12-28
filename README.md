OWMultiSite
===========

Provides multisite features for eZPublish >= 4.4

Features
-----------------
 - owurl : Multisite ezurl operator : {$node_id|owurl()}, {$node|owurl()}, {$node|owurl( 'my_siteaccess' )}, {$node|owurl( 'my_siteaccess', 'full|relative' )}
 - owini : Multisite ezini operator : {owini( 'SiteSettings', 'SiteURL', 'site.ini', 'my_siteaccess' )}
 - owini_hasvariable : Multisite ezini_hasvariable operator : {owini_hasvariable( 'SiteSettings', 'SiteURL', 'site.ini', 'my_siteaccess' )}

Details
-----------------
*owurl* operator will automatically find which siteaccess contains your node, and will generate an url according to this siteaccess.
You also can specify a siteaccess if you want to force this.
This operator supports host, PathPrefix and SiteURI settings.

*owini* allows you to read settings for a specific siteaccess, even if it is not current siteaccess.

A question ?
-------------------------
You can open a forum topic here : http://projects.ez.no/owmultisite/forum