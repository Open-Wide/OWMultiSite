<?php


class OWMultisiteURL
{
	
	var $node;
	var $serverURL;
	
	/**
	 * Initialize node
	 */
	public function __construct( $node, $serverURL = 'relative' )
    {
    	if ( is_numeric( $node ) ) {
			$node = eZContentObjectTreeNode::fetch( $node );
		}
		if ( $node instanceof eZContentObjectTreeNode ) {
			$this->node = $node;
			$this->serverURL = $serverURL;
		} else {
            throw new Exception('[OWMultisite] : Node not found. Please verify your input type or your SiteLanguageList settings');
        }
    }
	
    /**
     * Returns multisite full url from a node
     */
	public function owurl( $siteaccess = false ) {
		return $this->buildNodeURL( $siteaccess );
	}
	
	
	/**
	 * Check if the node is available in current siteaccess
	 */
	protected function isInternalNode( ) {
		return $this->isInSiteaccess( OWMultisiteIni::getCurrentSiteAccessName() );
	}
	
	/**
	 * Check if the node is available in $siteaccess
	 */
	protected function isInSiteaccess( $siteaccess=false ) {
		
		if ( $siteaccess ) {
			$ow_multisite_ini = new OWMultisiteIni( );

			// Check if node is in a PathPrefixEclude subtree (like Media, Users...)
			$path_prefix_exclude = $ow_multisite_ini->variable('SiteAccessSettings', 'PathPrefixExclude', $siteaccess, 'site.ini');
			$alias_array = explode( '/', $this->node->attribute( 'url_alias' ) );

			if ( in_array( $alias_array[0], $path_prefix_exclude ) ) {
				return true;
			}
			
			// Check if root node is included in node path array
			$path_array = $this->node->attribute( 'path_array' );
			$content_ini = $ow_multisite_ini->getInstance( $siteaccess, 'content.ini' );
			$root_node_array = array(
											'RootNode',
											'UserRootNode',
											'MediaRootNode',
											'SetupRootNode',
											'DesignRootNode'
			);
			foreach ( $root_node_array as $root_node_id ) {
				$root_node = $content_ini->variable('NodeSettings', $root_node_id);
				if ( in_array( $root_node, $path_array ) ) {
					return true;
				}
			}
			
		} else {
			return false;
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
		
		$this->error( 'No siteaccess found.' );
		return false;
	}
	
	
	/**
	 * Build full URL from node
	 */
	protected function buildNodeURL( $siteaccess=false ) {
		
		if ( !$siteaccess && $this->isInternalNode() ) {
			return $this->buildInternalNodeURL();
		} else {
			return $this->buildExternalNodeURL( $siteaccess );
		}
		
	}
	
	/**
	 * Build full URL from internal node
	 */
	protected function buildInternalNodeURL() {
		$url_alias = $this->node->attribute( 'url_alias' );
		eZURI::transformURI($url_alias, false, $this->serverURL);
		return $url_alias;
	}
	
	/**
	 * Build full URL from external node
	 */
	protected function buildExternalNodeURL( $siteaccess=false ) {
		
		if ( ! ( $siteaccess && $this->isInSiteaccess( $siteaccess ) ) ) {
			$siteaccess = $this->getExternalNodeSiteaccess();
		}
		if ( $siteaccess ) {
			
			$url_alias = $this->node->attribute( 'path_with_names' );
			
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

			return $this->buildInternalNodeURL();
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
