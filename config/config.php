<?php

error_reporting( E_ALL ^E_NOTICE ^E_WARNING );

define( 'TIMER', microtime( true ) );
define( 'PROJECT_PATH', substr( __file__, 0, strlen( __file__ ) - 18 ) );
define( 'SMARTY_COMPILE_DIR', '/tmp/ocr' );
define( 'WORKING_DIR', '/tmp/ocr_working/' );

if( !file_exists( WORKING_DIR ) )
	mkdir( WORKING_DIR );

define( 'SMARTY_TEMPLATES_DIR', PROJECT_PATH ."/templates/" );
define( 'PRODUCTION', false );

define( 'DB_TYPE', 'mysql' );
define( 'DB_NAME', '' );
define( 'DB_USERNAME', '' );
define( 'DB_PASSWORD', '' );

define( 'SMARTY_DIR', '/var/www/include/smarty/' );
require_once( '/var/www/include/smarty/Smarty.class.php' );
