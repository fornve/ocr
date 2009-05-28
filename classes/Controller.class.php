<?php

class Controller
{
	function __construct()
	{
		Controller::Startup();

		$this->entity = new Entity;
		$this->smarty = new Smarty;
		$this->smarty->compile_dir = SMARTY_COMPILE_DIR;

		if( !file_exists( $this->smarty->compile_dir ) )
			mkdir( $this->smarty->compile_dir );
	}

	function dispatch( $default = null )
	{
		$input = explode( '/', $_SERVER['REQUEST_URI'] );

		if( isset( $input[ 1 ]) )
		{
			$controller_name = "{$input[1]}Controller";
		}
		else
		{
			$controller_name = 'IndexController';
		}

		if( class_exists( $controller_name ) )
		{
			$controller = new $controller_name;

			$method = $input[ 2 ];
			
			if( strlen( $method ) == 0 )
				$method = 'Index';

			if( method_exists( get_class( $controller ), $method ) ) // check if property exists
			{
				$controller->$method( $input[ 3 ], $input[ 4 ] );
			}
			else
			{
				$this->Index();
			}
		}
		else
		{
			$this->Page404();
		}
	}

	function assign( $variable, $value )
	{
		$this->smarty->assign( $variable, $value );
	}

	function fetch( $template, $dir = null )
	{
		if( !$dir ) $dir = SMARTY_TEMPLATES_DIR;
		$output = $this->smarty->fetch( $dir . $template );
		return $output;
	}

	function decorate( $template, $dir = null )
	{
		if( !$dir ) $dir = SMARTY_TEMPLATES_DIR;
		$this->assign( 'input', $this->input );
		$content = $this->smarty->fetch( $dir . $template );
		$this->assign( 'content', $content );
		$generated = floor ( 10000 * ( microtime( true ) - TIMER ) ) / 10000;
		$this->smarty->assign( 'generated', $generated );
		$this->smarty->assign( 'entity_query', $_SESSION[ 'entity_query' ] );
		unset( $_SESSION[ 'entity_query' ] );
		echo $this->smarty->fetch( 'decoration.tpl' );
	}

	static function Inputs( $array, $input_type = INPUT_GET )
	{
		$input = new stdClass;
		
		foreach ( $array as $key )
		{
			$input->$key = addslashes( filter_input( $input_type, $key ) );
		}

		return $input;
	}

	static function GetInput( $input_name, $input_type = INPUT_GET )
	{
		$input = Controller::Inputs( array( $input_name ), $input_type );
		if( $input->$input_name )
		{
			return $input->$input_name;
		}
	}

	static function Startup()
	{
	}

	// entity lib shortcuts
	function query( $query, $arguments = null ) { $this->entity->query( $query, $arguments ); }
	function Collection( $query, $arguments = null )
	{
		$this->entity->Collection( $query, $arguments );
		$this->result = $this->entity->result;
		return $this->entity->result;
	}

	function GetResult() { return $this->entity->result; }

	function GetFirstResult( $query, $arguments = null )
	{
		if( $query )
			$this->Collection( $query, $arguments );

		$this->result = $this->entity->result[ 0 ];
		return $this->entity->result[ 0 ];
	}
}
  
