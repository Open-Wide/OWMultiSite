OWMultiSite
===========

Provides multisite features for eZPublish >= 4.4

Features
-----------------
 - Multisite ezurl operator : {$node_id|owurl()}, {$node|owurl()}, {$node|owurl( 'my_siteaccess' )}, {$node|owurl( 'my_siteaccess', 'full|relative' )}
 - Multisite ezini operator : {owini( 'SiteSettings', 'SiteURL', 'site.ini', 'my_siteaccess' )}
 - Multisite ezini_hasvariable operator : {owini_hasvariable( 'SiteSettings', 'SiteURL', 'site.ini', 'my_siteaccess' )}
