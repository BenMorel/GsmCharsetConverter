<?php

namespace BenMorel\GsmCharsetConverter\Tests;

use BenMorel\GsmCharsetConverter\Charset;
use PHPUnit\Framework\TestCase;

class CharsetTest extends TestCase
{
    public function testUtf8Encoding() : void
    {
        foreach (Charset::GSM_TO_UTF8 as $char) {
            self::assertTrue(mb_check_encoding($char, 'UTF-8'));
        }

        foreach (Charset::TRANSLITERATE as $from => $to) {
            self::assertTrue(mb_check_encoding($from, 'UTF-8'));
            self::assertTrue(mb_check_encoding($to, 'UTF-8'));
        }
    }

    public function testGsmCharsetKeys() : void
    {
        foreach (Charset::GSM_TO_UTF8 as $key => $value) {
            $key = (string) $key;

            switch (strlen($key)) {
                case 1:
                    self::assertLessThanOrEqual(0x7F, ord($key));
                    break;

                case 2:
                    self::assertSame("\x1B", $key[0]);
                    self::assertLessThanOrEqual(0x7F, ord($key[1]));
                    break;

                default:
                    self::fail('Invalid key length');
            }
        }
    }

    public function testGsmCharsetCoverage() : void
    {
        $esc = 0x1B;

        for ($i = 0; $i <= 0x7F; $i++) {
            if ($i !== $esc) {
                $char = chr($i);
                $message = 'Char 0x' . bin2hex($char) . ' is missing from GSM charset.';
                self::assertArrayHasKey($char, Charset::GSM_TO_UTF8, $message);
            }
        }

        $extensionTable = [
            "\x0A",
            "\x14",
            "\x28",
            "\x29",
            "\x2F",
            "\x3C",
            "\x3D",
            "\x3E",
            "\x40",
            "\x65"
        ];

        foreach ($extensionTable as $char) {
            $message = 'Char 0x' . bin2hex($char) . ' is missing from GSM charset extension table.';
            self::assertArrayHasKey(chr($esc) . $char, Charset::GSM_TO_UTF8, $message);
        }
    }

    public function testGsmCharsetValuesAreUnique() : void
    {
        $values = array_values(Charset::GSM_TO_UTF8);
        self::assertSame($values, array_unique($values));
    }

    public function testTransliterateMapsToExistingGsmChars()
    {
        $gsmChars = array_flip(Charset::GSM_TO_UTF8);

        foreach (Charset::TRANSLITERATE as $chars) {
            $length = mb_strlen($chars);

            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($chars, $i, 1);
                self::assertArrayHasKey($char, $gsmChars, 'TRANSLITERATE contains unknown target char ' . bin2hex($char));
            }
        }
    }

    public function testTransliterateDoesNotOverlapGsmCharset() : void
    {
        foreach (array_keys(Charset::TRANSLITERATE) as $char) {
            self::assertFalse(in_array($char, Charset::GSM_TO_UTF8, true));
        }
    }

    public function testCoverage() : void
    {
        $expectedChars = $this->getExpectedChars();

        $actualChars = array_merge(
            array_values(Charset::GSM_TO_UTF8),
            array_keys(Charset::TRANSLITERATE)
        );

        foreach ($expectedChars as $char) {
            $message = 'Char U+' . bin2hex(mb_convert_encoding($char, 'UCS-2BE', 'UTF-8')) . ' is missing.';
            self::assertTrue(in_array($char, $actualChars, true), $message);
        }
    }

    private function getExpectedChars() : array
    {
        // Control chars, covered by GSM charset

        $expectedCodepoints = [
            0x0A, // LF
            0x0C, // FF
            0x0D, // CR
        ];

        // Latin1, covered by GSM charset & TRANSLITERATE

        for ($i = 0x20; $i <= 0x7E; $i++) {
            $expectedCodepoints[] = $i;
        }

        for ($i = 0xA0; $i <= 0xFF; $i++) {
            $expectedCodepoints[] = $i;
        }

        // Greek letters, covered by GSM charset

        $expectedCodepoints[] = 0x0393;
        $expectedCodepoints[] = 0x0394;
        $expectedCodepoints[] = 0x0398;
        $expectedCodepoints[] = 0x039B;
        $expectedCodepoints[] = 0x039E;
        $expectedCodepoints[] = 0x03A0;
        $expectedCodepoints[] = 0x03A3;
        $expectedCodepoints[] = 0x03A6;
        $expectedCodepoints[] = 0x03A8;
        $expectedCodepoints[] = 0x03A9;

        // Euro sign, covered by GSM charset

        $expectedCodepoints[] = 0x20AC;

        return array_map(function(int $codepoint) : string {
            return eval('return "\u{' . sprintf('%04s', dechex($codepoint)) . '}";');
        }, $expectedCodepoints);
    }
}
