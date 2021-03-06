<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 14:50
 */

namespace test\edwrodrig\genbank_reader;

use edwrodrig\genbank_reader\HeaderFieldReader;
use edwrodrig\genbank_reader\StreamReader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class HeaderFieldReaderTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp() {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws \edwrodrig\genbank_reader\exception\InvalidHeaderFieldException
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testReadSingle() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
  ORGANISM  Saccharomyces cerevisiae
            Eukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;
            Saccharomycetales; Saccharomycetaceae; Saccharomyces.
EOF
        );
        $f = fopen($filename, 'r');


        $header = new HeaderFieldReader(new StreamReader($f));
        $this->assertEquals("ORGANISM", $header->getField());
        $this->assertEquals("Saccharomyces cerevisiae\nEukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;\nSaccharomycetales; Saccharomycetaceae; Saccharomyces.", $header->getContent());

    }

    /**
     * @throws \edwrodrig\genbank_reader\exception\InvalidHeaderFieldException
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testReadDouble() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, <<<EOF
  ORGANISM  Saccharomyces cerevisiae
            Eukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;
            Saccharomycetales; Saccharomycetaceae; Saccharomyces.
FEATURES
EOF
        );
        $f = fopen($filename, 'r');


        $header = new HeaderFieldReader(new StreamReader($f));
        $this->assertEquals("ORGANISM", $header->getField());
        $this->assertEquals("Saccharomyces cerevisiae\nEukaryota; Fungi; Ascomycota; Saccharomycotina; Saccharomycetes;\nSaccharomycetales; Saccharomycetaceae; Saccharomyces.", $header->getContent());

        $this->assertEquals("FEATURES", fgets($f));
    }

    /**
     * @testWith ["DEFINITION", "Some description",  "DEFINITION  Some description"]
     *           ["ACCESSION", "1.2.3",  "ACCESSION   1.2.3"]
     *           ["AUTHORS", "Edwin Rodriguez",  "  AUTHORS   Edwin Rodriguez"]
     * @param null|string $expectedField
     * @param string $expectedContent
     * @param string $line
     * @throws \edwrodrig\genbank_reader\exception\InvalidHeaderFieldException
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testLineRead(?string $expectedField, string $expectedContent, string $line) {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, $line);
        $f = fopen($filename, 'r');


        $header = new HeaderFieldReader(new StreamReader($f));
        $this->assertEquals($expectedField, $header->getField());
        $this->assertEquals($expectedContent, $header->getContent());

    }

    /**
     * @throws \edwrodrig\genbank_reader\exception\InvalidStreamException
     */
    public function testGetNextField() {
        $filename =  $this->root->url() . '/test';

        file_put_contents($filename, 'DEFINITION');
        $f = fopen($filename, 'r');

        $this->assertEquals('DEFINITION', HeaderFieldReader::getNextField(new StreamReader($f)));
    }
}
