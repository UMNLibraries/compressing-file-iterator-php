<?php

namespace UmnLib\Core;

class CompressingFileIterator extends \ArrayIterator
{
    public function next()
    {
        $fileName = $this->current();
        $this->compress( $fileName );
        parent::next();
    }

    public function compress( $fileName )
    {
        $zipFileName = "$fileName.gz";
        $fileContents = file_get_contents( $fileName );
        $zipFile = gzopen($zipFileName, "w9");
        gzwrite( $zipFile, $fileContents );
        gzclose( $zipFile );
        unlink( $fileName );
    }
}
