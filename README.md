OWMultiSite
===========

*owmultisite* provides multisite features for **eZPublish >= 4.4**

#Features

 - **owurl** : Multisite ezurl operator : `{$node_id|owurl()}`, `{$node|owurl()}`, `{$node|owurl( 'my_siteaccess' )}`, `{$node|owurl( 'my_siteaccess', 'full|relative' )}`
 - **owini** : Multisite ezini operator : `{owini( 'SiteSettings', 'SiteURL', 'site.ini', 'my_siteaccess' )}`
 - **owini_hasvariable** : Multisite ezini_hasvariable operator : `{owini_hasvariable( 'SiteSettings', 'SiteURL', 'site.ini', 'my_siteaccess' )}`

#Usage

`owurl` operator will automatically find which siteaccess contains your node, and will generate an url according to this siteaccess.
You also can specify a siteaccess if you want to force this.
This operator supports host, PathPrefix and SiteURI settings.

`owini` allows you to read settings for a specific siteaccess, even if it is not current siteaccess.

#Installation

##Extension activation

1) Put content on extension in "extension/owmultisite" folder

2) Activate extension : Add the following to your `settings/override/site.ini.append.php` file:
```
[ExtensionSettings]
ActiveExtensions[]=owmultisite
```

3) **Regenerate autoloads**

4) Clear cache


###Settings for owurl
To use owurl operator, please check following settings :

 - in `site.ini` :
```
[SiteSettings]
SiteURL

[SiteAccessSettings]
RelatedSiteAccessList[]
(PathPrefixExclude)
(PathPrefix)

[RegionalSettings]
(SiteLanguageList[])
```

 - in `content.ini` :
```
[NodeSettings]
RootNode
MediaRootNode
SetupRootNode
DesignRootNode
UserRootNode
```
		
 - in `owmultisite.ini` to exclude a siteaccess (i.e to avoid links to admin siteaccess)
