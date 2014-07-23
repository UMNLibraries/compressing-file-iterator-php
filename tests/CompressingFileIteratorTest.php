<?php

namespace UmnLib\Core\Tests;

use UmnLib\Core\CompressingFileIterator;
use Symfony\Component\Finder\Finder;

class CompressingFileIteratorTest extends \PHPUnit_Framework_TestCase
{
  public function testCompress()
  {
    $fixturesDir = dirname(__FILE__) . '/fixtures';
    $finder = new Finder();
    $files = $finder->name('*.txt')->in($fixturesDir);
    $filesArray = array();
    foreach($files as $file) {
      $filesArray[] = $file->getRealPath();
    }

    $cfi = new CompressingFileIterator( $filesArray );
    $cfi->rewind();
    while ($cfi->valid()) {
      $cfi->next();
    }

    // cleanup:
    foreach ($filesArray as $filename)
    {
      $file = fopen($filename, 'w');
      $gzipFilename = $filename . '.gz';

      $this->assertTrue( file_exists($gzipFilename) );

      $lines = gzfile($gzipFilename);
      foreach ($lines as $line) {
        fwrite($file, $line);
      }
      fclose($file);
      unlink($gzipFilename);
    }
  }
}
