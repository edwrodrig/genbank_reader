<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-06-18
 * Time: 15:52
 */

namespace edwrodrig\genbank;


class HeaderReader
{

    /**
     * @var resource
     */
    private $stream;

    /**
     * HeaderParser constructor.
     * @param $stream
     * @throws exception\InvalidStreamException
     * @throws exception\InvalidHeaderLineFormatException
     */
    public function __construct($stream) {
        if ( !is_resource($stream) ) {
            throw new exception\InvalidStreamException;
        }
        $this->stream = $stream;
        $this->parse();
    }

    public function parse() {
        while ( $line = fgets($this->stream) ) {
            $line_reader = new HeaderLineReader($line);


        }
        first = line.mid ( 0 , 12 ).trimmed() ;
        if ( first == "LOCUS " ) tag = "LOCUS" ;
        else if ( first == "DEFINITION" ) tag = "DEFINITION" ;
        else if ( first == "ACCESSION" ) tag = "ACCESSION" ;
        else if ( first == "VERSION" ) tag = "VERSION" ;
        else if ( first == "ORGANISM" ) tag = "ORGANISM" ;
        else if ( first == "FEATURES" ) {
            state = FEATURES ;
            continue ;
        }
        else if ( first != "" ) tag = "" ;

        if ( tag != "" ) {
            if ( data.contains ( tag ) ) {
                data[tag] += ( QString (" ") + line.mid ( 12 ).trimmed() ) ;
            } else data[tag] = line.mid ( 12 ).trimmed() ;
            if ( tag == "ORGANISM" ) tag = "LINEAGE" ;
        }
    }
}