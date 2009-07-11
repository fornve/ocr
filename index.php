<?php

require_once( 'config/config.php' );

function __autoload( $name )
{
	$path_array = array( 'class/', 'entity/', 'controllers/', '/var/www/include/class/' );

	foreach( $path_array as $path )
	{
		if( file_exists( $path. $name .'.class.php' ) )
		{
			include_once( $path . $name .'.class.php' );
			return true;
		}
	}
}

session_start();
$www = new IndexController;
$www->Dispatch();

