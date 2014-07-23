<?php

class CompressingFileIterator extends ArrayIterator
{
    public function next()
    {
        $file_name = $this->current();
        $this->compress( $file_name );
        parent::next();
    }

    public function compress( $file_name )
    {
        $zip_file_name = "$file_name.gz";
        $file_contents = file_get_contents( $file_name );
        $zip_file = gzopen($zip_file_name, "w9");
        gzwrite( $zip_file, $file_contents );
        gzclose( $zip_file );
        unlink( $file_name );
    }
}
