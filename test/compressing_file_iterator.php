#!/usr/bin/php -q
<?php

require_once 'simpletest/autorun.php';
SimpleTest :: prefer(new TextReporter());
set_include_path('../php' . PATH_SEPARATOR . get_include_path());
require_once 'CompressingFileIterator.php';
require_once 'File/Find/Rule.php';

ini_set('memory_limit', '2G');

//error_reporting( E_STRICT );
error_reporting( E_ALL );

class CompressingFileIteratorTest extends UnitTestCase
{
    public function test_compress()
    {
        $f = new File_Find_Rule();
        $directory = getcwd();
        $file_names = $f->name('*.txt')->in( $directory );

        $cfi = new CompressingFileIterator( $file_names );
        $cfi->rewind();
        while ($cfi->valid()) {
            $cfi->next();
        }

        // cleanup:
        foreach ($file_names as $file_name)
        {
            $file = fopen($file_name, 'w');
            $gzip_file_name = $file_name . '.gz';

            $this->assertTrue( file_exists($gzip_file_name) );

            $lines = gzfile($gzip_file_name);
            foreach ($lines as $line) {
                fwrite($file, $line);
            }
            fclose($file);
            unlink($gzip_file_name);
        }
    }
}
