<?php


class OWMultisiteURL
{
	
	var $node;
	
	/**
	 * Initialize node
	 */
	public function __construct( $node )
    {
    	if ( is_integer( $node ) ) {
			$node = eZContentObjectTreeNode::fetch( $node );
		}
		if ( $node instanceof eZContentObjectTreeNode ) {
			$this->node = $node;
		} else {
            throw new Exception('[OWMultisite] : Node not found. Please verify your input type or your SiteLanguageList settings');
        }
    }
	
    /**
     * Returns multisite full url from a node
     */
	public function owurl() {
		return $this->buildNodeURL();
	}
	
	
	/**
	 * Check if the node is available in current siteaccess
	 */
	protected function isInternalNode( ) {
		
		$content_ini = eZIni::instance( 'content.ini' );
		$current_root_node = $content_ini->variable('NodeSettings', 'RootNode');
		
		// Check if current root node is included in node path array
		$path_array = $this->node->attribute( 'path_array' );
		if ( in_array( $current_root_node, $path_array ) ) {
			return true;
		} else {
			// Check if node is in a PathPrefixEclude subtree (like Media, Users...)
			$site_ini = eZIni::instance( 'site.ini' );
			$path_prefix_exclude = $site_ini->variable('SiteAccessSettings', 'PathPrefixExclude');
			$alias_array = explode( '/', $this->node->attribute( 'url_alias' ) );

			if ( in_array( $alias_array[0], $path_prefix_exclude ) ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Get siteaccess containing external node
	 */
	protected function getExternalNodeSiteaccess() {
		
		$ini = eZIni::instance( 'site.ini' );
		$related_siteaccess = $ini->variable('SiteAccessSettings', 'RelatedSiteAccessList');
		
		$path_array = $this->node->attribute( 'path_array' );

		$ow_multisite_ini = new OWMultisiteIni();
		foreach( $related_siteaccess as $siteaccess ) {
			$root_node = $ow_multisite_ini->variable( 'NodeSettings', 'RootNode', $siteaccess, 'content.ini' );
			if ( in_array( $root_node, $path_array ) ) {
				return $siteaccess;
			}
		}
		
		return false;
	}
	
	
	/**
	 * Build full URL from node
	 */
	protected function buildNodeURL( ) {
		
		if ( $this->isInternalNode() ) {
			return $this->buildInternalNodeURL();
		} else {
			return $this->buildExternalNodeURL();
		}
		
	}
	
	/**
	 * Build full URL from internal node
	 */
	protected function buildInternalNodeURL() {
		$url_alias = $this->node->attribute( 'url_alias' );
		eZURI::transformURI($url_alias, false, 'full');
		return $url_alias;
	}
	
	/**
	 * Build full URL from external node
	 */
	protected function buildExternalNodeURL() {
		
		$siteaccess = $this->getExternalNodeSiteaccess();
		if ( $siteaccess ) {
			
			$url_alias = $this->node->attribute( 'url_alias' );
			
			$ow_multisite_ini = new OWMultisiteIni();
			$site_ini = $ow_multisite_ini->getInstance( $siteaccess, 'site.ini' );
			$content_ini = $ow_multisite_ini->getInstance( $siteaccess, 'content.ini' );
			
			$domain = trim( $site_ini->variable('SiteSettings', 'SiteURL'), '/' );
	    	$path_prefix = $site_ini->variable('SiteAccessSettings', 'PathPrefix');
	    	$path_prefix_exclude = $site_ini->variable('SiteAccessSettings', 'PathPrefixExclude');
			
			$alias_array = explode('/', $url_alias);

			if ( $path_prefix && !in_array( $alias_array[0], $path_prefix_exclude ) ) {
				$url_alias = preg_replace('#^'.$path_prefix.'/#','',$url_alias);
			}
			
		    return 'http://'.$domain.'/'.$url_alias;
	    	
		} else {
			$this->error( 'No siteaccess found.' );
			return false;
		}
	    
	}
	

	/**
     * Display error message
     *
     * @param string $msg
     * @return boolean
     */
	protected function error ( $msg ) {
		
		if ( $msg ) {
			eZDebug::writeError( "[OWMultisite] : " . $msg );
			return true;
		} else {
			return false;
		}
		
	}
}
?>
