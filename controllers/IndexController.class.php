<?php
	class IndexController extends Controller
	{
		function Index()
		{
			if( $_SERVER['REQUEST_METHOD'] == 'POST' )
			{
				$file = IndexController::UploadImage();
				$this->assign( 'file', basename( $file ) );
				$this->assign( 'text', Ocr::Read( $file, WORKING_DIR ) );
				echo $this->decorate( 'result.tpl' );
			}
			else
			{
				echo $this->decorate( 'index.tpl' );
			}
		}

		function Test()
		{
			var_dump( Ocr::Read( WORKING_DIR . 'Letter_to_all_gold_traders_Eng.jpg', WORKING_DIR ) );
		}

		function Image( $size = null, $file )
		{
			if( !$size )
			{
				$this->OriginalImage( WORKING_DIR . $file );
			}

			$size = explode( 'x', $size );

			$image = new ImageHandler( WORKING_DIR . $file, $size[ 0 ], $size[ 1 ] );
			$image->add_borders = true;
			$image->Output();
		}

		function OriginalImage( $file )
		{
			header( "Content-Type: image/jpeg" );
			readfile( $file );
		}
		
		function UploadImage( $id )
		{
			// upload file
            if( $_FILES[ "image" ][ 'name' ] )
            {
                $i = 1;
                do
                {
                    $filename = "{$prefix}". $_FILES[ 'image' ][ 'name' ];
                    $prefix = $i++ .'_';
                    $file = WORKING_DIR . $filename;
                }
                while( file_exists( $file ) );

                if( move_uploaded_file( $_FILES[ 'image' ][ 'tmp_name' ], $file ) )
                {
                    return $file;
                }
                else
                {
                    return 'Error uploading file.';
                }
            }
		}

	}

