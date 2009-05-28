#!/bin/sh
convert -depth 8 -define quantum:polarity=min-is-white {$image} image.tif
tesseract image.tif textfile
