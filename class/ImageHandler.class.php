<?php

	/**
	 * @package framework
	 */
	class ImageHandler
	{
		public $cache_root = '/tmp';	// cache root directory
		public $cache_prefix = 'cache-';
		public $width = 0;
		public $height = 0;
		public $force_regeneration = false;
		public $add_borders = false;
		public $jpeg_quality = 100; // int {0-100} - 100 means 100% quality
		
		function __construct( $file, $width = 0, $height = 0 )
		{
			if( !file_exists( $file ) )
				return false;

			$this->file = $file;
			$this->width = $width;
			$this->height = $height;
			
			$uri = explode( '/', $file );
			$this->filename = $uri[ count( $uri ) - 1 ];
			unset( $uri[ count( $uri ) - 1 ] );
			$this->path = '/'. implode( '/', $uri );

			$this->image = $this->Load( $file );
			$this->GetSourceDimensions();
		}

		// image manipulation methods

		private function Load( $file )
		{
			return imagecreatefromstring( file_get_contents( $file ) );
		}

		private function GetImageDimensions( $file )
		{
			if( $this->file != $file )
				$image = $this->Load( $file );
			else
				$image = $this->image;

			$dimensions[ 'width' ] = imagesx ( $image );
			$dimensions[ 'height' ] = imagesy ( $image );
			$dimensions[ 'ratio' ] = $dimensions[ 'width' ] / $dimensions[ 'height' ];

			return $dimensions;
		}

		private function AddBorders()
		{
			$xpos = 0;
			$ypos = 0;
			$width = $this->width;
			$height = $this->height;

			if( $this->height / $this->width < $this->source_width / $this->source_height ) // fit to width
			{
				$resize_ratio = $this->width / $this->source_width;
				$ypos = (int)ceil( ( $this->height - $this->source_height * $resize_ratio ) / 2 );
				$height = (int)floor( $this->source_height * $resize_ratio );
			}
			else // fit to height
			{
				$resize_ratio = $this->height / $this->source_height;
				$xpos = (int)ceil( ( $this->width - $this->source_width * $resize_ratio ) / 2 );
				$width = (int)floor( $this->source_width * $resize_ratio );
			}

			$destination_image = imagecreatetruecolor( $this->width, $this->height );
			imagesavealpha( $destination_image, true );
			$background = imagecolorallocatealpha( $destination_image, 255, 255, 255, 0 );
			imagefill( $destination_image, 0, 0, $background );

			imagecopyresized( $destination_image, $this->image, $xpos, $ypos, 0, 0, $width, $height, $this->source_width, $this->source_height );

			return $destination_image;

		}

		private function Resize( $source_image )
		{
			// default - stretch image

			if( !$this->height ) // fit to width
			{
				$this->height = (int)ceil( $this->width / $this->source_ratio );
			}
			elseif( !$this->width ) // fit to height
			{
				$this->width = (int)ceil( $this->height * $this->source_ratio );
			}

			$destination_image = imagecreatetruecolor( $this->width, $this->height );
			$a = imagecopyresized( $destination_image, $source_image, 0, 0, 0, 0, $this->width, $this->height, $this->source_width, $this->source_height );
			
			return $destination_image;
		}

		// private methods

		private function GetCacheFilename()
		{
			$options = implode( '-'. $this->options ) .'-';
			$this->cache_filename = "{$this->cache_prefix}{$this->filename}";
			$this->cache_path = "{$this->width}x{$this->height}";
			$this->cache_file = "{$this->cache_root}/{$this->cache_path}/{$this->cache_filename}";

			if( !file_exists( $this->cache_root .'/'. $this->cache_path ) )
				mkdir( $this->cache_root .'/'. $this->cache_path );
		}

		private function GenerateCache()
		{
			if( $this->force_regeneration and file_exists( $this->cache_filename ) ) // force thumbnail regeneration
			{
				unlink( $cache_filename );
			}

			if( $this->add_borders )
				$image = $this->AddBorders();
			elseif( $this->width or $this->height )
				$image = $this->Resize( $this->image );
			else
				$image = $this->image;
				

			imagejpeg( $image, $this->cache_file, $this->jpeg_quality );
		}

		private function GetSourceDimensions()
		{
			$dimensions = $this->GetImageDimensions( $this->file );
			$this->source_width = $dimensions[ 'width' ];
			$this->source_height = $dimensions[ 'height' ];
			$this->source_ratio = $dimensions[ 'ratio' ];
		}

		private function Process()
		{
			$this->GetCacheFilename();

			if( !file_exists( $this->cache_file ) )
				$this->GenerateCache();
			else
				error_log( "Read from cache: {$this->filename}" );

		}

		// public methods

		public function GetUrl()
		{
			$this->Process();
			return "Cached/{$this->cache_path}/{$this->cache_dir}";
		}

		public function Output()
		{
			$this->Process();
			header( "Content-Type: image/jpeg" );
			header( "Pragma: hack" );
			header( "Expires: " . gmdate("D, d M Y H:i:s", strtotime( 'tommorow' ) ) . " GMT" );
			header( "Last-Modified: " . gmdate("D, d M Y H:i:s", strtotime( 'yesterday' ) ) . " GMT" );
			header( "Cache-Control: max-age=315360000"  );
			readfile( $this->cache_file );
		}
	}
