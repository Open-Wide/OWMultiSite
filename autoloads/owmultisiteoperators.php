<?php

class owMultisiteOperators
{
    /*!
     Constructor
    */
    function owMultisiteOperators()
    {
        $this->Operators = array( 
        							'owurl',
        							'owini',
									'owini_hasvariable'
        );
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list
    exists per operator type, this is needed for operator classes
    that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     Both operators have one parameter.
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {

		return array( 		
						'owurl' => array( ),
						'owini' => array( 	'section' => array( 'type' => 'string',
                                                              'required' => true,
                                                              'default' => '' ),
											'variable' => array( 'type' => 'string',
                                                              'required' => true,
                                                              'default' => '' ),
											'file' => array( 'type' => 'string',
                                                              'required' => false,
                                                              'default' => 'site.ini' ),
											'siteaccess' => array( 'type' => 'string',
                                                              'required' => false,
                                                              'default' => '' )
											
										),
						'owini_hasvariable' => array( 
											'section' => array( 'type' => 'string',
                                                              'required' => true,
                                                              'default' => '' ),
											'variable' => array( 'type' => 'string',
                                                              'required' => true,
                                                              'default' => '' ),
											'file' => array( 'type' => 'string',
                                                              'required' => false,
                                                              'default' => 'site.ini' ),
											'siteaccess' => array( 'type' => 'string',
                                                              'required' => false,
                                                              'default' => '' )
										)
				  );
    }

    /*!
     \Executes the needed operator(s).
     \Checks operator names, and calls the appropriate functions.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace,
                     &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {	
			case 'owurl':
				$operatorValue = $this->owurl( $operatorValue );
			break;
			case 'owini':
				$owini = new OWMultisiteIni();
				$operatorValue = $owini->variable( $namedParameters['section'], $namedParameters['variable'], $namedParameters['siteaccess'], $namedParameters['file']);
			break;
			case 'owini_hasvariable':
				$owini = new OWMultisiteIni();
				$operatorValue = $owini->hasVariable( $namedParameters['section'], $namedParameters['variable'], $namedParameters['siteaccess'], $namedParameters['file']);
			break;
    	}
    }
    

	protected function owurl( $node ) {

		try {
		    $owurl = new OWMultisiteURL( $node ); 
		} catch( Exception $e ) {
		    eZDebug::writeError( $e->getMessage() );
		    return false;
		}
		
		return $owurl->owurl();
	}

    /// \privatesection
    var $Operators;
}

?>