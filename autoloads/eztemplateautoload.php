<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
  	array( 
  		'script' => 'extension/owmultisite/autoloads/owmultisiteoperators.php',
        'class' => 'owMultisiteOperators',
        'operator_names' => array( 
  			'owurl',
        	'owini',
			'owini_hasvariable'
		),
	);


?>