<?php

namespace basex;

class BinaryConverterTest extends \PHPUnit_Framework_TestCase
{

    const ALPHABET_BASE64    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    const ALPHABET_BASE64URL = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    const ALPHABET_BASE32    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    const ALPHABET_BASE32HEX = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
    const ALPHABET_BASE16    = '0123456789ABCDEF';

    public function testData()
    {
        // [[input, output, base, aplhabet, pad], ...]
        return array(
            // empty input
            array('', '', 64, self::ALPHABET_BASE64, '='),
            array('', '', 64, self::ALPHABET_BASE64, '='),
            array('', '', 32, self::ALPHABET_BASE64, '='),

            // http://tools.ietf.org/html/rfc4648#section-10
            // ... base64
            array('f', 'Zg==', 64, self::ALPHABET_BASE64, '='),
            array('fo', 'Zm8=', 64, self::ALPHABET_BASE64, '='),
            array('foo', 'Zm9v', 64, self::ALPHABET_BASE64, '='),
            array('foob', 'Zm9vYg==', 64, self::ALPHABET_BASE64, '='),
            array('fooba', 'Zm9vYmE=', 64, self::ALPHABET_BASE64, '='),
            array('foobar', 'Zm9vYmFy', 64, self::ALPHABET_BASE64, '='),

            // ... base32
            array('f', 'MY======', 32, self::ALPHABET_BASE32, '='),
            array('fo', 'MZXQ====', 32, self::ALPHABET_BASE32, '='),
            array('foo', 'MZXW6===', 32, self::ALPHABET_BASE32, '='),
            array('foob', 'MZXW6YQ=', 32, self::ALPHABET_BASE32, '='),
            array('fooba', 'MZXW6YTB', 32, self::ALPHABET_BASE32, '='),
            array('foobar', 'MZXW6YTBOI======', 32, self::ALPHABET_BASE32, '='),

            // ... base32-hex
            array('f', 'CO======', 32, self::ALPHABET_BASE32HEX, '='),
            array('fo', 'CPNG====', 32, self::ALPHABET_BASE32HEX, '='),
            array('foo', 'CPNMU===', 32, self::ALPHABET_BASE32HEX, '='),
            array('foob', 'CPNMUOG=', 32, self::ALPHABET_BASE32HEX, '='),
            array('fooba', 'CPNMUOJ1', 32, self::ALPHABET_BASE32HEX, '='),
            array('foobar', 'CPNMUOJ1E8======', 32, self::ALPHABET_BASE32HEX, '='),

            // ... base16
            array('f', '66', 16, self::ALPHABET_BASE16, '='),
            array('fo', '666F', 16, self::ALPHABET_BASE16, '='),
            array('foo', '666F6F', 16, self::ALPHABET_BASE16, '='),
            array('foob', '666F6F62', 16, self::ALPHABET_BASE16, '='),
            array('fooba', '666F6F6261', 16, self::ALPHABET_BASE16, '='),
            array('foobar', '666F6F626172', 16, self::ALPHABET_BASE16, '='),
        );
    }

    /**
     * @dataProvider testData
     * @param string $input
     * @param string $output
     * @param int    $base
     * @param string $alphabet
     * @param string $pad
     */
    public function testBase64Encode($input, $output, $base, $alphabet, $pad)
    {
        $converter = new BinaryConverter($base, $alphabet, $pad);
        $this->assertSame($output, $converter->encode($input));
    }
}

