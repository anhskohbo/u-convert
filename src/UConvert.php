<?php

namespace Anhskohbo\UConvert;

use Anhskohbo\UConvert\Exceptions\MapDataException;
use Anhskohbo\UConvert\Exceptions\DetectCharacterException;

class UConvert implements UConvertInterface {

    const UNICODE = 'UNICODE';
    const TCVN3   = 'TCVN3';
    const VNI     = 'VNI';
    const VIQR    = 'VIQR';

    /**
     * Text for transform.
     * 
     * @var string
     */
    protected $text;

    /**
     * Character of text.
     * 
     * @var string
     */
    protected $character;

    /**
     * Maps-data for transform.
     * 
     * @var array
     */
    protected $maps = array(
        "UNICODE" => array(
            "À", "Á", "Â", "Ã", "È", "É", "Ê", "Ì", "Í", "Ò",
            "Ó", "Ô", "Õ", "Ù", "Ú", "Ý", "à", "á", "â", "ã",
            "è", "é", "ê", "ì", "í", "ò", "ó", "ô", "õ", "ù",
            "ú", "ý", "Ă", "ă", "Đ", "đ", "Ĩ", "ĩ", "Ũ", "ũ",
            "Ơ", "ơ", "Ư", "ư", "Ạ", "ạ", "Ả", "ả", "Ấ", "ấ",
            "Ầ", "ầ", "Ẩ", "ẩ", "Ẫ", "ẫ", "Ậ", "ậ", "Ắ", "ắ",
            "Ằ", "ằ", "Ẳ", "ẳ", "Ẵ", "ẵ", "Ặ", "ặ", "Ẹ", "ẹ",
            "Ẻ", "ẻ", "Ẽ", "ẽ", "Ế", "ế", "Ề", "ề", "Ể", "ể",
            "Ễ", "ễ", "Ệ", "ệ", "Ỉ", "ỉ", "Ị", "ị", "Ọ", "ọ",
            "Ỏ", "ỏ", "Ố", "ố", "Ồ", "ồ", "Ổ", "ổ", "Ỗ", "ỗ",
            "Ộ", "ộ", "Ớ", "ớ", "Ờ", "ờ", "Ở", "ở", "Ỡ", "ỡ",
            "Ợ", "ợ", "Ụ", "ụ", "Ủ", "ủ", "Ứ", "ứ", "Ừ", "ừ",
            "Ử", "ử", "Ữ", "ữ", "Ự", "ự", "Ỳ", "ỳ", "Ỵ", "ỵ", 
            "Ỷ", "ỷ", "Ỹ", "ỹ"
        ),
        "VNI" => array(
            "AØ", "AÙ", "AÂ", "AÕ", "EØ", "EÙ", "EÂ", "Ì" , "Í" , "OØ",
            "OÙ", "OÂ", "OÕ", "UØ", "UÙ", "YÙ", "aø", "aù", "aâ", "aõ",
            "eø", "eù", "eâ", "ì" , "í" , "oø", "où", "oâ", "oõ", "uø",
            "uù", "yù", "AÊ", "aê", "Ñ" , "ñ" , "Ó" , "ó" , "UÕ", "uõ",
            "Ô" , "ô" , "Ö" , "ö" , "AÏ", "aï", "AÛ", "aû", "AÁ", "aá",
            "AÀ", "aà", "AÅ", "aå", "AÃ", "aã", "AÄ", "aä", "AÉ", "aé",
            "AÈ", "aè", "AÚ", "aú", "AÜ", "aü", "AË", "aë", "EÏ", "eï",
            "EÛ", "eû", "EÕ", "eõ", "EÁ", "eá", "EÀ", "eà", "EÅ", "eå",
            "EÃ", "eã", "EÄ", "eä", "Æ" , "æ" , "Ò" , "ò" , "OÏ", "oï",
            "OÛ", "oû", "OÁ", "oá", "OÀ", "oà", "OÅ", "oå", "OÃ", "oã",
            "OÄ", "oä", "ÔÙ", "ôù", "ÔØ", "ôø", "ÔÛ", "ôû", "ÔÕ", "ôõ",
            "ÔÏ", "ôï", "UÏ", "uï", "UÛ", "uû", "ÖÙ", "öù", "ÖØ", "öø",
            "ÖÛ", "öû", "ÖÕ", "öõ", "ÖÏ", "öï", "YØ", "yø", "Î" , "î" ,
            "YÛ", "yû", "YÕ", "yõ"
        ),
        "TCVN3" => array(
            "Aµ", "A¸", "¢" , "A·", "EÌ", "EÐ", "£" , "I×", "IÝ", "Oß",
            "Oã", "¤" , "Oâ", "Uï", "Uó", "Yý", "µ" , "¸" , "©" , "·" ,
            "Ì" , "Ð" , "ª" , "×" , "Ý" , "ß" , "ã" , "«" , "â" , "ï" ,
            "ó" , "ý" , "¡" , "¨" , "§" , "®" , "IÜ", "Ü" , "Uò", "ò" ,
            "¥" , "¬" , "¦" , "­"  , "A¹", "¹" , "A¶", "¶" , "¢Ê", "Ê" ,
            "¢Ç", "Ç" , "¢È", "È" , "¢É", "É" , "¢Ë", "Ë" , "¡¾", "¾" ,
            "¡»", "»" , "¡¼", "¼" , "¡½", "½" , "¡Æ", "Æ" , "EÑ", "Ñ" ,
            "EÎ", "Î" , "EÏ", "Ï" , "£Õ", "Õ" , "£Ò", "Ò" , "£Ó", "Ó" ,
            "£Ô", "Ô" , "£Ö", "Ö" , "IØ", "Ø" , "IÞ", "Þ" , "Oä", "ä" ,
            "Oá", "á" , "¤è", "è" , "¤å", "å" , "¤æ", "æ" , "¤ç", "ç" ,
            "¤é", "é" , "¥í", "í" , "¥ê", "ê" , "¥ë", "ë" , "¥ì", "ì" ,
            "¥î", "î" , "Uô", "ô" , "Uñ", "ñ" , "¦ø", "ø" , "¦õ", "õ" ,
            "¦ö", "ö" , "¦÷", "÷" , "¦ù", "ù" , "Yú", "ú" , "Yþ", "þ" ,
            "Yû", "û" , "Yü", "ü"
        ),
        "VIQR" => array(
            "A`" , "A'" , "A^" , "A~" , "E`" , "E'" , "E^" , "I`" , "I'" , "O`" ,
            "O'" , "O^" , "O~" , "U`" , "U'" , "Y'" , "a`" , "a'" , "a^" , "a~" ,
            "e`" , "e'" , "e^" , "i`" , "i'" , "o`" , "o'" , "o^" , "o~" , "u`" ,
            "u'" , "y'" , "A(" , "a(" , "DD" , "dd" , "I~" , "i~" , "U~" , "u~" ,
            "O+" , "o+" , "U+" , "u+" , "A." , "a." , "A?" , "a?" , "A^'", "a^'",
            "A^`", "a^`", "A^?", "a^?", "A^~", "a^~", "A^.", "a^.", "A('", "a('",
            "A(`", "a(`", "A(?", "a(?", "A(~", "a(~", "A(.", "a(.", "E." , "e." ,
            "E?" , "e?" , "E~" , "e~" , "E^'", "e^'", "E^`", "e^`", "E^?", "e^?",
            "E^~", "e^~", "E^.", "e^.", "I?" , "i?" , "I." , "i." , "O." , "o." ,
            "O?" , "o?" , "O^'", "o^'", "O^`", "o^`", "O^?", "o^?", "O^~", "o^~",
            "O^.", "o^.", "O+'", "o+'", "O+`", "o+`", "O+?", "o+?", "O+~", "o+~",
            "O+.", "o+.", "U." , "u." , "U?" , "u?" , "U+'", "u+'", "U+`", "u+`",
            "U+?", "u+?", "U+~", "u+~", "U+.", "u+.", "Y`" , "y`" , "Y." , "y." ,
            "Y?" , "y?" , "Y~" , "y~"
        ),
    );
    
    /**
     * Setup for new UConvert.
     * 
     * @param string $text
     * @param string $character
     */
    public function __construct($text, $character = null)
    {
        $this->setText($text);
        $this->setCharacter(is_null($character) ?
            $this->detectCharacter($text) :
            $character
        );
    }

    /**
     * Transform text to a character.
     * 
     * @param  string $toCharacter
     * @return string
     */
    public function transform($toCharacter)
    {
        $text = $this->getText();
        $toCharacter = strtoupper($toCharacter);
        $currentCharacter = $this->getCharacter();

        if ($toCharacter === $currentCharacter)
        {
            return $this->getText();
        }

        $from  = $this->getMap($currentCharacter);
        $to    = $this->getMap($toCharacter);
        $count = count($from) - 1;

        for ($i = $count; $i > 0; $i--)
        {
            $char = $this->getCharPosition($from, $i);
            $text = str_replace($char, '::'.$i.'::', $text);
        }

        for ($i = $count; $i > 0; $i--)
        {
            $char = $this->getCharPosition($to, $i);
            $text = str_replace('::'.$i.'::', $char, $text);
        }

        $this->setText($text);
        $this->setCharacter($toCharacter);

        return $text;
    }

    /**
     * Set text to transform.
     * 
     * @param string $text
     * @return UConvert
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text to transform.
     * 
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set character for text.
     * 
     * @param string $character
     * @return UConvert
     */
    public function setCharacter($character)
    {
        $this->character = strtoupper($character);
        return $this;
    }

    /**
     * Get current character of text.
     * 
     * @return string
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set custom map-data.
     * 
     * @param string $name
     * @param array  $array
     * @return UConvert
     */
    public function setMap($name, array $array)
    {
        $this->maps[strtoupper($name)] = $array;
        return $this;
    }

    /**
     * Get map-data by name.
     * 
     * @param  string $name
     * @throws InvalidArgumentException
     * @return array
     */
    public function getMap($name)
    {
        if (isset($this->maps[$name]))
        {
            return $this->maps[$name];
        }

        throw new MapDataException("Map-data [$name] is currently not registered.");
    }

    /**
     * Return maps-data.
     * 
     * @return array
     */
    public function getMaps()
    {
        return $this->maps;
    }

    /**
     * Get char-position from the map-data.
     * 
     * @param  string $map
     * @param  int    $index
     * @return string
     */
    protected function getCharPosition($map, $index)
    {
        return isset($map[$index]) ? $map[$index] : '';
    }

    /**
     * Auto detect character from text.
     * 
     * @param  string $text
     * @return string
     */
    protected function detectCharacter($text)
    {
        throw new DetectCharacterException("Cannot detect character.");
    }

    /**
     * Handle dynamic, static calls to the non-method.
     * 
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (count($args) === 2)
        {
            list($text, $character) = $args;
            $convert = new static($text, $character);
            
            $toCharacter = substr($method, 2);
            $allowMethod = array_map(function($string)
            {
                return ucfirst(strtolower($string));
            }, array_keys($convert->getMaps()));

            if (substr($method, 0, 2) === 'to' && in_array($toCharacter, $allowMethod))
            {
                return $convert->transform($toCharacter);
            }

            throw new \BadMethodCallException("Method [$method] not found in UConvert.");
        }

        throw new \InvalidArgumentException("Invalid arguments.");
    }

}
