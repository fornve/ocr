<?php

require_once( 'config/config.php' );

function __autoload( $name ) 
{
	if( file_exists( 'classes/'. $name .'.class.php' ) )
	{
		include_once( 'classes/'. $name .'.class.php' );
	}
    elseif( 'controllers/'. $name .'.class.php' )
    {
		include_once( 'controllers/'. $name .'.class.php' );
	}	
	elseif( 'entity/'. $name .'.class.php' )
	{
		include_once( 'entity/'. $name .'.class.php' );
	}
}

session_start();
$www = new IndexController;
$www->Dispatch();

