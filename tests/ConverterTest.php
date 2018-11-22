<?php

namespace BenMorel\GsmCharsetConverter\Tests;

use BenMorel\GsmCharsetConverter\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @dataProvider providerConvertGsmToUtf8
     *
     * @param string $input  The GSM 03.38 input string.
     * @param string $output The expected UTF-8 output string.
     */
    public function testConvertGsmToUtf8(string $input, string $output) : void
    {
        $converter = new Converter();
        self::assertSame($output, $converter->convertGsmToUtf8($input));
    }

    public function providerConvertGsmToUtf8() : array
    {
        return [
            // empty string
            ['', ''],

            // main table
            ["\x00\x01\x02\x03\x04\x05\x06\x07", '@£$¥èéùì'],
            ["\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F", "òÇ\nØø\rÅå"],
            ["\x10\x11\x12\x13\x14\x15\x16\x17", 'Δ_ΦΓΛΩΠΨ'],
            ["\x18\x19\x1A\x1C\x1D\x1E\x1F",     "ΣΘΞÆæßÉ"],
            ["\x20\x21\x22\x23\x24\x25\x26\x27", ' !"#¤%&\''],
            ["\x28\x29\x2A\x2B\x2C\x2D\x2E\x2F", '()*+,-./'],
            ["\x30\x31\x32\x33\x34\x35\x36\x37", '01234567'],
            ["\x38\x39\x3A\x3B\x3C\x3D\x3E\x3F", '89:;<=>?'],
            ["\x40\x41\x42\x43\x44\x45\x46\x47", '¡ABCDEFG'],
            ["\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F", 'HIJKLMNO'],
            ["\x50\x51\x52\x53\x54\x55\x56\x57", 'PQRSTUVW'],
            ["\x58\x59\x5A\x5B\x5C\x5D\x5E\x5F", 'XYZÄÖÑÜ§'],
            ["\x60\x61\x62\x63\x64\x65\x66\x67", '¿abcdefg'],
            ["\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F", 'hijklmno'],
            ["\x70\x71\x72\x73\x74\x75\x76\x77", 'pqrstuvw'],
            ["\x78\x79\x7A\x7B\x7C\x7D\x7E\x7F", 'xyzäöñüà'],

            // extension table
            ["\x1B\x0A\x1B\x14\x1B\x28\x1B\x29", "\f^{}"],
            ["\x1B\x2F\x1B\x3C\x1B\x3D\x1B\x3E", '\\[~]'],
            ["\x1B\x40\x1B\x65",                 '|€'],

            // mix of both
            ["\x40\x20\x7D\x7C\x00\x1B\x65.\x7F!", '¡ ñö@€.à!']
        ];
    }

    /**
     * @dataProvider providerConvertGsmToUtf8WithInvalidString
     *
     * @param string $string          The string to test.
     * @param string $expectedMessage The expected exception message.
     */
    public function testConvertGsmToUtf8WithInvalidString(string $string, string $expectedMessage) : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $converter = new Converter();
        $converter->convertGsmToUtf8($string);
    }

    public function providerConvertGsmToUtf8WithInvalidString() : array
    {
        return [
            ["\x1B", 'contains an ESC char at the end of the string'],
            ["\x20\x1B", 'contains an ESC char at the end of the string'],
            ["\x1B\x00", 'char 1B00 is unknown'],
            ["\x1B\x01", 'char 1B01 is unknown'],
            ["\x1B\x27", 'char 1B27 is unknown'],
            ["\x1B\x2A", 'char 1B2A is unknown'],
            ["\x1B\xFF", 'char 1BFF is unknown'],
        ];
    }
}
