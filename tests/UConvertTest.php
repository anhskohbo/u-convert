<?php

use Anhskohbo\UConvert\UConvert;

class UConvertTest extends PHPUnit_Framework_TestCase {

    /**
     * [$strings description]
     * 
     * @var array
     */
    protected $strings = array(
        "UNICODE" => "Hàn Quốc hôm nay bắn pháo đáp trả Triều Tiên, bởi cho rằng pháo của Triều Tiên bắn sang vùng biển của nước này",
        "VNI"     => "Haøn Quoác hoâm nay baén phaùo ñaùp traû Trieàu Tieân, bôûi cho raèng phaùo cuûa Trieàu Tieân baén sang vuøng bieån cuûa nöôùc naøy",
        "VIQR"    => "Ha`n Quo^'c ho^m nay ba('n pha'o dda'p tra? Trie^`u Tie^n, bo+?i cho ra(`ng pha'o cu?a Trie^`u Tie^n ba('n sang vu`ng bie^?n cu?a nu+o+'c na`y",
        "TCVN3"   => "Hµn Quèc h«m nay b¾n ph¸o ®¸p tr¶ TriÒu Tiªn, bëi cho r»ng ph¸o cña TriÒu Tiªn b¾n sang vïng biÓn cña n­íc nµy",
    );

    /**
     * [testTransforms description]
     * 
     * @return [type] [description]
     */
    public function testTransforms()
    {
        $characters = array_keys($this->strings);

        foreach ($characters as $from)
        {
            foreach ($characters as $to)
            {
                $convert = new UConvert($this->strings[$from], $from);
                
                // Convert $from to $to
                $trans = $convert->transform($to);
                $this->assertEquals($trans, $this->strings[$to]);

                // Convert $to to $from
                $roll = $convert->transform($from);
                $this->assertEquals($roll, $this->strings[$from]);
            }
        }
    }

    /**
     * [testStatic description]
     * 
     * @return [type] [description]
     */
    public function testStatic()
    {
        // VNI to Unicode
        $unicode = UConvert::toUnicode($this->strings['VNI'], UConvert::VNI);
        $this->assertEquals($unicode, $this->strings['UNICODE']);

        // Unicode to VNI
        $vni = UConvert::toVni($this->strings['UNICODE'], UConvert::UNICODE);
        $this->assertEquals($vni, $this->strings['VNI']);
        
        // VNI to TCVN3
        $tcvn3 = UConvert::toTcvn3($this->strings['VNI'], UConvert::VNI);
        $this->assertEquals($tcvn3, $this->strings['TCVN3']);

        // TCVN3 to VNQR
        $viqr = UConvert::toViqr($this->strings['TCVN3'], UConvert::TCVN3);
        $this->assertEquals($viqr, $this->strings['VIQR']);

        // ...
    }

}
