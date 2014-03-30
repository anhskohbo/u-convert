<?php

use Anhskohbo\UConvert\UConvert;

class UConvertTest extends PHPUnit_Framework_TestCase {

    /**
     * [$strings description]
     * 
     * @var array
     */
    protected $strings = array(
        'UNICODE' => 'Hành trình phá án: Bí ẩn xác chết bên triền đê',
        'VNI-WIN' => 'Haønh trình phaù aùn: Bí aån xaùc cheát beân trieàn ñeâ',
        'TCVN3'   => 'Hµnh tr×nh ph¸ ¸n: BÝ Èn x¸c chÕt bªn triÒn ®ª',
        'VIQR'    => "Ha`nh tri`nh pha' a'n: Bi' a^?n xa'c che^'t be^n trie^`n dde^",
        'VISCII'  => 'Hành trình phá án: Bí ¦n xác chªt bên tri«n ðê'
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
                $this->generateTransform($from, $to);
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
        $unicode = UConvert::toUnicode($this->strings['VNI-WIN'], UConvert::VNI_WIN);
        $this->assertEquals($unicode, $this->strings['UNICODE']);

        // Unicode to VNI
        $vni = UConvert::toVni($this->strings['UNICODE'], UConvert::UNICODE);
        $this->assertEquals($vni, $this->strings['VNI-WIN']);
            
        // VNI to TCVN3
        $tcvn3 = UConvert::toTcvn3($this->strings['VNI-WIN'], UConvert::VNI_WIN);
        $this->assertEquals($tcvn3, $this->strings['TCVN3']);

        // TCVN3 to VNQR
        $viqr = UConvert::toViqr($this->strings['TCVN3'], UConvert::TCVN3);
        $this->assertEquals($viqr, $this->strings['VIQR']);

        // UNICODE to VISCII
        $viscii = UConvert::toViscii($this->strings['UNICODE'], UConvert::UNICODE);
        $this->assertEquals($viscii, $this->strings['VISCII']);

        // ...
    }

    /**
     * [generateTransform description]
     * 
     * @param  [type] $from [description]
     * @param  [type] $to   [description]
     * @return [type]       [description]
     */
    protected function generateTransform($from, $to)
    {
        $convert = new UConvert($this->strings[$from], $from);
         
        $trans = $convert->transform($to);
        $this->assertEquals($trans, $this->strings[$to]);

        $rollback = $convert->transform($from);
        $this->assertEquals($rollback, $this->strings[$from]);
    }

}
