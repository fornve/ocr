<?php

	class Ocr
	{
		function Read( $file, $dir )
		{
			if( file_exists( $file ) )
			{
				$filename = explode( '.', basename( $file ) );
				$filename = $filename[ 0 ];
				$command = "convert -depth 8 -define quantum:polarity=min-is-white -normalize -blur 1 -threshold 50% '{$file}' '{$dir}{$filename}.tif'";
				`$command`;
//convert -normalize -blur 1 -threshold 50% -deskew orig.png ocr.bmp
				if( !file_exists( "{$dir}{$filename}.tif" ) )
					return "Error: problem converting into tiff file.";

				$command = "tesseract '{$dir}{$filename}.tif' '{$dir}{$filename}.txt'";
				`$command`;
				
				if( !file_exists( "{$dir}{$filename}.txt.txt" ) )
					return "Error: ocr'ing file {$dir}{$filename}.";

				return file_get_contents( "{$dir}{$filename}.txt.txt" );
			}
			else
				return "Error: file {$file} not found";
		}
	}
