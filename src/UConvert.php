<?php

namespace Anhskohbo\UConvert;

use Anhskohbo\UConvert\Exceptions\MapDataException;
use Anhskohbo\UConvert\Exceptions\DetectCharacterException;

class UConvert implements UConvertInterface {

    const UNICODE = 'UNICODE';
    const TCVN3   = 'TCVN3';
    const VNI     = 'VNI-WIN';
    const VNI_WIN = 'VNI-WIN';
    const VIQR    = 'VIQR';
    const VISCII  = 'VISCII';

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
     * Data-maps for transform.
     * 
     * @var array
     */
    protected $maps = array(

        'UNICODE' => array(
            'a', 'â', 'ă', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y',
            'A', 'Â', 'Ă', 'E', 'Ê', 'I', 'O', 'Ô', 'Ơ', 'U', 'Ư', 'Y',
            'á', 'ấ', 'ắ', 'é', 'ế', 'í', 'ó', 'ố', 'ớ', 'ú', 'ứ', 'ý',
            'Á', 'Ấ', 'Ắ', 'É', 'Ế', 'Í', 'Ó', 'Ố', 'Ớ', 'Ú', 'Ứ', 'Ý',
            'à', 'ầ', 'ằ', 'è', 'ề', 'ì', 'ò', 'ồ', 'ờ', 'ù', 'ừ', 'ỳ',
            'À', 'Ầ', 'Ằ', 'È', 'Ề', 'Ì', 'Ò', 'Ồ', 'Ờ', 'Ù', 'Ừ', 'Ỳ',
            'ạ', 'ậ', 'ặ', 'ẹ', 'ệ', 'ị', 'ọ', 'ộ', 'ợ', 'ụ', 'ự', 'ỵ',
            'Ạ', 'Ậ', 'Ặ', 'Ẹ', 'Ệ', 'Ị', 'Ọ', 'Ộ', 'Ợ', 'Ụ', 'Ự', 'Ỵ',
            'ả', 'ẩ', 'ẳ', 'ẻ', 'ể', 'ỉ', 'ỏ', 'ổ', 'ở', 'ủ', 'ử', 'ỷ',
            'Ả', 'Ẩ', 'Ẳ', 'Ẻ', 'Ể', 'Ỉ', 'Ỏ', 'Ổ', 'Ở', 'Ủ', 'Ử', 'Ỷ',
            'ã', 'ẫ', 'ẵ', 'ẽ', 'ễ', 'ĩ', 'õ', 'ỗ', 'ỡ', 'ũ', 'ữ', 'ỹ',
            'Ã', 'Ẫ', 'Ẵ', 'Ẽ', 'Ễ', 'Ĩ', 'Õ', 'Ỗ', 'Ỡ', 'Ũ', 'Ữ', 'Ỹ',
            'd', 'đ', 'D', 'Đ'
        ),

        'TCVN3' => array(
            'a', '©', '¨', 'e', 'ª', 'i', 'o', '«', '¬', 'u', '­', 'y',
            'A', '¢', '¡', 'E', '£', 'I', 'O', '¤', '¥', 'U', '¦', 'Y',
            '¸', 'Ê', '¾', 'Ð', 'Õ', 'Ý', 'ã', 'è', 'í', 'ó', 'ø', 'ý',
            '', '', '', '', '', '', '', '', '', '', '', '',
            'µ', 'Ç', '»', 'Ì', 'Ò', '×', 'ß', 'å', 'ê', 'ï', 'õ', 'ú',
            '', '', '', '', '', '', '', '', '', '', '', '',
            '¹', 'Ë', 'Æ', 'Ñ', 'Ö', 'Þ', 'ä', 'é', 'î', 'ô', 'ù', 'þ',
            '', '', '', '', '', '', '', '', '', '', '', '',
            '¶', 'È', '¼', 'Î', 'Ó', 'Ø', 'á', 'æ', 'ë', 'ñ', 'ö', 'û',
            '', '', '', '', '', '', '', '', '', '', '', '',
            '·', 'É', '½', 'Ï', 'Ô', 'Ü', 'â', 'ç', 'ì', 'ò', '÷', 'ü',
            '', '', '', '', '', '', '', '', '', '', '', '',
            'd', '®', 'D', '§'
        ),

        'VNI-WIN' => array(
            'a', 'aâ', 'aê', 'e', 'eâ', 'i', 'o', 'oâ', 'ô', 'u', 'ö', 'y',
            'A', 'AÂ', 'AÊ', 'E', 'EÂ', 'I', 'O', 'OÂ', 'Ô', 'U', 'Ö', 'Y',
            'aù', 'aá', 'aé', 'eù', 'eá', 'í', 'où', 'oá', 'ôù', 'uù', 'öù', 'yù',
            'AÙ', 'AÁ', 'AÉ', 'EÙ', 'EÁ', 'Í', 'OÙ', 'OÁ', 'ÔÙ', 'UÙ', 'ÖÙ', 'YÙ',
            'aø', 'aà', 'aè', 'eø', 'eà', 'ì', 'oø', 'oà', 'ôø', 'uø', 'öø', 'yø',
            'AØ', 'AÀ', 'AÈ', 'EØ', 'EÀ', 'Ì', 'OØ', 'OÀ', 'ÔØ', 'UØ', 'ÖØ', 'YØ',
            'aï', 'aä', 'aë', 'eï', 'eä', 'ò', 'oï', 'oä', 'ôï', 'uï', 'öï', 'î',
            'AÏ', 'AÄ', 'AË', 'EÏ', 'EÄ', 'Ò', 'OÏ', 'OÄ', 'ÔÏ', 'UÏ', 'ÖÏ', 'Î',
            'aû', 'aå', 'aú', 'eû', 'eå', 'æ', 'oû', 'oå', 'ôû', 'uû', 'öû', 'yû',
            'AÛ', 'AÅ', 'AÚ', 'EÛ', 'EÅ', 'Æ', 'OÛ', 'OÅ', 'ÔÛ', 'UÛ', 'ÖÛ', 'YÛ',
            'aõ', 'aã', 'aü', 'eõ', 'eã', 'ó', 'oõ', 'oã', 'ôõ', 'uõ', 'öõ', 'yõ',
            'AÕ', 'AÃ', 'AÜ', 'EÕ', 'EÃ', 'Ó', 'OÕ', 'OÃ', 'ÔÕ', 'UÕ', 'ÖÕ', 'YÕ',
            'd', 'ñ', 'D', 'Ñ'
        ),

        "VIQR" => array(
            "a", "a^", "a(", "e", "e^", "i", "o", "o^", "o+", "u", "u+", "y",
            "A", "A^", "A(", "E", "E^", "I", "O", "O^", "O+", "U", "U+", "Y",
            "a'", "a^'", "a('", "e'", "e^'", "i'", "o'", "o^'", "o+'", "u'", "u+'", "y'",
            "A'", "A^'", "A('", "E'", "E^'", "I'", "O'", "O^'", "O+'", "U'", "U+'", "Y'",
            "a`", "a^`", "a(`", "e`", "e^`", "i`", "o`", "o^`", "o+`", "u`", "u+`", "y`",
            "A`", "A^`", "A(`", "E`", "E^`", "I`", "O`", "O^`", "O+`", "U`", "U+`", "Y`",
            "a.", "a^.", "a(.", "e.", "e^.", "i.", "o.", "o^.", "o+.", "u.", "u+.", "y.",
            "A.", "A^.", "A(.", "E.", "E^.", "I.", "O.", "O^.", "O+.", "U.", "U+.", "Y.",
            "a?", "a^?", "a(?", "e?", "e^?", "i?", "o?", "o^?", "o+?", "u?", "u+?", "y?",
            "A?", "A^?", "A(?", "E?", "E^?", "I?", "O?", "O^?", "O+?", "U?", "U+?", "Y?",
            "a~", "a^~", "a(~", "e~", "e^~", "i~", "o~", "o^~", "o+~", "u~", "u+~", "y~",
            "A~", "A^~", "A(~", "E~", "E^~", "I~", "O~", "O^~", "O+~", "U~", "U+~", "Y~",
            "d", "dd", "D", "DD"
        ),

        "VISCII" => array(
            "a", "â", "å", "e", "ê", "i", "o", "ô", "½", "u", "ß", "y",
            "A", "Â", "Å", "E", "Ê", "I", "O", "Ô", "´", "U", "¿", "Y",
            "á", "¤", "í", "é", "ª", "í", "ó", "¯", "¾", "ú", "Ñ", "ý",
            "Á", "„", "", "É", "Š", "Í", "Ó", "", "•", "Ú", "º", "Ý",
            "à", "¥", "¢", "è", "«", "ì", "ò", "°", "¶", "ù", "×", "Ï",
            "À", "…", "‚", "È", "‹", "Ì", "Ò", "", "–", "Ù", "»", "Ÿ",
            "Õ", "§", "£", "©", "®", "¸", "÷", "µ", "þ", "ø", "ñ", "Ü",
            "€", "‡", "ƒ", "‰", "Ž", "˜", "š", "“", "”", "ž", "¹", "",
            "ä", "¦", "Æ", "ë", "¬", "ï", "ö", "±", "·", "ü", "Ø", "Ö",
            "Ä", "†", "", "Ë", "Œ", "›", "™", "‘", "—", "œ", "¼", "",
            "ã", "ç", "Ç", "¨", "­", "î", "õ", "²", "Þ", "û", "æ", "Û",
            "Ã", "", "", "ˆ", "", "Î", "", "’", "³", "", "ÿ", "",
            "d", "ð", "D", "Ð"
        ),

    );
    
    /**
     * Converts source to Unicode. 
     * 
     * @param  string $text
     * @param  string $character
     * @return string
     */
    public static function toUnicode($text, $character = null)
    {
        $convert = new static($text, $character);
        return $convert->transform(static::UNICODE);
    }

    /**
     * Converts source to TCVN3.
     * 
     * @param  string $text
     * @param  string $character
     * @return string
     */
    public static function toTcvn3($text, $character = null)
    {
        $convert = new static($text, $character);
        return $convert->transform(static::TCVN3);
    }

    /**
     * Converts source to VNI. 
     * 
     * @param  string $text
     * @param  string $character
     * @return string
     */
    public static function toVni($text, $character = null)
    {
        $convert = new static($text, $character);
        return $convert->transform(static::VNI_WIN);
    }

    /**
     * Converts source to VIQR. 
     * 
     * @param  string $text
     * @param  string $character
     * @return string
     */
    public static function toViqr($text, $character = null)
    {
        $convert = new static($text, $character);
        return $convert->transform(static::VIQR);
    }

    /**
     * Converts source to VISCII. 
     * 
     * @param  string $text
     * @param  string $character
     * @return string
     */
    public static function toViscii($text, $character = null)
    {
        $convert = new static($text, $character);
        return $convert->transform(static::VISCII);
    }

    /**
     * Setup for new UConvert.
     * 
     * @param string $text
     * @param string $character
     */
    public function __construct($text, $character = null)
    {
        $this->setText($text);
        $this->setCharacter(is_null($character) ? $this->detectCharacter($text) : $character);
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
        $from = $this->getMap($this->getCharacter());
        $to   = $this->getMap($toCharacter);

        for ($i = count($from) - 1; $i > 0; $i--)
        {
            $char = $this->getCharPosition($from, $i);
            $text = str_replace($char, '::'.$i.'::', $text);
        }

        for ($i = count($from) - 1; $i > 0; $i--)
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
     * Get char-position from the map-data.
     * 
     * @param  string $map
     * @param  int $index
     * @return string
     */
    protected function getCharPosition($map, $index)
    {
        return isset($map[$index]) ? $map[$index] : '';
    }

    /**
     * Get map-data by name.
     * 
     * @param  string $name
     * @throws InvalidArgumentException
     * @return array
     */
    protected function getMap($name)
    {
        if (isset($this->maps[$name]))
        {
            return $this->maps[$name];
        }

        throw new MapDataException('Map-data [$name] Is Currently Not Registered.');
    }

    /**
     * Auto detect character from text.
     * 
     * @param  string $text
     * @return string
     */
    protected function detectCharacter($text)
    {
        throw new DetectCharacterException("Cannot Detect Character.");
    }

}
