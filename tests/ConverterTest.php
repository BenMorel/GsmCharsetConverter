<?php

declare(strict_types=1);

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
        $converter = new Converter();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

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
            ["\x80", 'char 80 is unknown'],
            ["\xFF", 'char FF is unknown'],
        ];
    }

    /**
     * @dataProvider providerConvertUtf8ToGsm
     *
     * @param string      $input           The UTF-8 input string.
     * @param bool        $translit        Whether to use transliteration.
     * @param string|null $replaceChars    The optional replacement string for unknown chars.
     * @param string      $output          The expected GSM 03.38 output string.
     */
    public function testConvertUtf8ToGsm(string $input, bool $translit, ?string $replaceChars, string $output) : void
    {
        $converter = new Converter();
        $actualOutput = $converter->convertUtf8ToGsm($input, $translit, $replaceChars);
        $message = strtoupper(bin2hex($actualOutput)) . ' != ' . strtoupper(bin2hex($output));

        self::assertSame($output, $actualOutput, $message);
    }

    public function providerConvertUtf8ToGsm() : iterable
    {
        // Fully GSM 03.38 compatible string tests;
        // Let's tests these with all parameter combinations, as the output should be the same.

        $tests = [
            // empty string
            ['', ''],

            // main table
            ['@£$¥èéùì',   "\x00\x01\x02\x03\x04\x05\x06\x07"],
            ["òÇ\nØø\rÅå", "\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F"],
            ['Δ_ΦΓΛΩΠΨ',   "\x10\x11\x12\x13\x14\x15\x16\x17"],
            ["ΣΘΞÆæßÉ",    "\x18\x19\x1A\x1C\x1D\x1E\x1F"],
            [' !"#¤%&\'',  "\x20\x21\x22\x23\x24\x25\x26\x27"],
            ['()*+,-./',   "\x28\x29\x2A\x2B\x2C\x2D\x2E\x2F"],
            ['01234567',   "\x30\x31\x32\x33\x34\x35\x36\x37"],
            ['89:;<=>?',   "\x38\x39\x3A\x3B\x3C\x3D\x3E\x3F"],
            ['¡ABCDEFG',   "\x40\x41\x42\x43\x44\x45\x46\x47"],
            ['HIJKLMNO',   "\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F"],
            ['PQRSTUVW',   "\x50\x51\x52\x53\x54\x55\x56\x57"],
            ['XYZÄÖÑÜ§',   "\x58\x59\x5A\x5B\x5C\x5D\x5E\x5F"],
            ['¿abcdefg',   "\x60\x61\x62\x63\x64\x65\x66\x67"],
            ['hijklmno',   "\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F"],
            ['pqrstuvw',   "\x70\x71\x72\x73\x74\x75\x76\x77"],
            ['xyzäöñüà',   "\x78\x79\x7A\x7B\x7C\x7D\x7E\x7F"],

            // extension table
            ["\f^{}", "\x1B\x0A\x1B\x14\x1B\x28\x1B\x29"],
            ['\\[~]', "\x1B\x2F\x1B\x3C\x1B\x3D\x1B\x3E"],
            ['|€',    "\x1B\x40\x1B\x65"],

            // mix
            ["¡Lörèm/[ìpsüm] dòlør_sit ämét!", "\x40\x4C\x7C\x72\x04\x6D\x2F\x1B\x3C\x07\x70\x73\x7E\x6D\x1B\x3E\x20\x64\x08\x6C\x0C\x72\x11\x73\x69\x74\x20\x7B\x6D\x05\x74\x21"],
            ["¿ñon s€mper {màùris} dåpibus?",  "\x60\x7D\x6F\x6E\x20\x73\x1B\x65\x6D\x70\x65\x72\x20\x1B\x28\x6D\x7F\x06\x72\x69\x73\x1B\x29\x20\x64\x0F\x70\x69\x62\x75\x73\x3F"]
        ];

        foreach ($tests as [$input, $output]) {
            yield [$input, false, null, $output];
            yield [$input, false, '?', $output];
            yield [$input, true, null, $output];
            yield [$input, true, '?', $output];
        }

        // Fully transliterable string tests;
        // Let's tests these with and without replacement string, as the output should be the same.

        $tests = [
            // full table to single chars
            ["` ¢¦¨", "\x27\x20\x63\x1B\x40\x22"],
            ["ª«¬­¯", "\x61\x22\x2D\x2D\x11"],
            ["°²³´µ", "\x6F\x32\x33\x27\x75"],
            ["¶·¸¹º", "\x5F\x2E\x2C\x31\x6F"],
            ["»ÀÁÂÃ", "\x22\x41\x41\x41\x41"],
            ["ÈÊËÌÍ", "\x45\x45\x45\x49\x49"],
            ["ÎÏÐÒÓ", "\x49\x49\x44\x4F\x4F"],
            ["ÔÕ×ÙÚ", "\x4F\x4F\x78\x55\x55"],
            ["ÛÝáâã", "\x55\x59\x61\x61\x61"],
            ["çêëíî", "\x63\x65\x65\x69\x69"],
            ["ïðóôõ", "\x69\x64\x6F\x6F\x6F"],
            ["÷úûýÿ", "\x2F\x75\x75\x79\x79"],

            // full table to multiple chars
            ["\u{00A9}\u{00AE}\u{00B1}\u{00BC}", "\x28\x63\x29\x28\x72\x29\x2B\x2F\x2D\x31\x2F\x34"],
            ["\u{00BD}\u{00BE}\u{00DE}\u{00FE}", "\x31\x2F\x32\x33\x2F\x34\x54\x48\x74\x68"],

            // mix of native and transliterable
            ['À NOËL', "\x41\x20\x4E\x4F\x45\x4C"],
            ['à noël', "\x7F\x20\x6E\x6F\x65\x6C"],
        ];

        foreach ($tests as [$input, $output]) {
            yield [$input, true, null, $output];
            yield [$input, true, '?', $output];
        }

        // Strings with unsupported characters, replacement only.

        yield ['À NOËL 🎁', false, '?', "\x3F\x20\x4E\x4F\x3F\x4C\x20\x3F"];
        yield ["à 🌲🎁 noël", false, '|', "\x7F\x20\x1B\x40\x1B\x40\x20\x6E\x6F\x1B\x40\x6C"];

        // Strings with unsupported characters, transliteration and replacement.

        yield ['À NOËL 🎁', true, '?', "\x41\x20\x4E\x4F\x45\x4C\x20\x3F"];
        yield ["à 🌲🎁 noël", true, '|', "\x7F\x20\x1B\x40\x1B\x40\x20\x6E\x6F\x65\x6C"];
    }

    /**
     * @dataProvider providerConvertUtf8ToGsmWithInvalidParams
     *
     * @param string      $string          The UTF-8 input string.
     * @param bool        $translit        Whether to use transliteration.
     * @param string|null $replaceChars    The optional replacement string for unknown chars.
     * @param string      $expectedMessage The expected exception message.
     */
    public function testConvertUtf8ToGsmWithInvalidParams(string $string, bool $translit, ?string $replaceChars, string $expectedMessage) : void
    {
        $converter = new Converter();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $converter->convertUtf8ToGsm($string, $translit, $replaceChars);
    }

    /**
     * Using the same provider as UTF-8 to GSM, the exceptions should be the same.
     *
     * @dataProvider providerConvertUtf8ToGsmWithInvalidParams
     *
     * @param string      $string          The UTF-8 input string.
     * @param bool        $translit        Whether to use transliteration.
     * @param string|null $replaceChars    The optional replacement string for unknown chars.
     * @param string      $expectedMessage The expected exception message.
     */
    public function testConvertUtf8ToUtf8WithInvalidParams(string $string, bool $translit, ?string $replaceChars, string $expectedMessage) : void
    {
        $converter = new Converter();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $converter->convertUtf8ToUtf8($string, $translit, $replaceChars);
    }

    public function providerConvertUtf8ToGsmWithInvalidParams() : array
    {
        return [
            // Invalid input string
            ["Hello \xFF world", false, null, 'The input string is not valid UTF-8'],
            ["Hello \xFF world", true, null, 'The input string is not valid UTF-8'],
            ["Hello \xFF world", false, '?', 'The input string is not valid UTF-8'],
            ["Hello \xFF world", true, '?', 'The input string is not valid UTF-8'],

            // Input string requiring transliteration or replacement
            ['À NOËL', false, null, 'UTF-8 character C380 cannot be converted'],

            // Input string requiring replacement
            ['汉字', false, null, 'UTF-8 character E6B189 cannot be converted'],
            ['汉字', true, null, 'UTF-8 character E6B189 cannot be converted'],
        ];
    }

    /**
     * @dataProvider providerConvertUtf8ToUtf8
     *
     * @param string      $input           The UTF-8 input string.
     * @param bool        $translit        Whether to use transliteration.
     * @param string|null $replaceChars    The optional replacement string for unknown chars.
     * @param string      $output          The expected UTF-8 output string.
     */
    public function testConvertUtf8ToUtf8(string $input, bool $translit, ?string $replaceChars, string $output) : void
    {
        $converter = new Converter();
        self::assertSame($output, $converter->convertUtf8ToUtf8($input, $translit, $replaceChars));
    }

    public function providerConvertUtf8ToUtf8() : iterable
    {
        // Fully GSM 03.38 compatible string tests;
        // Let's tests these with all parameter combinations, the output should always be the same as the input.

        $tests = [
            // empty string
            '',

            // main table
            '@£$¥èéùì',
            "òÇ\nØø\rÅå",
            'Δ_ΦΓΛΩΠΨ',
            "ΣΘΞÆæßÉ",
            ' !"#¤%&\'',
            '()*+,-./',
            '01234567',
            '89:;<=>?',
            '¡ABCDEFG',
            'HIJKLMNO',
            'PQRSTUVW',
            'XYZÄÖÑÜ§',
            '¿abcdefg',
            'hijklmno',
            'pqrstuvw',
            'xyzäöñüà',

            // extension table
            "\f^{}",
            '\\[~]',
            '|€',

            // mix
            "¡Lörèm/[ìpsüm] dòlør_sit ämét!",
            "¿ñon s€mper {màùris} dåpibus?",
        ];

        foreach ($tests as $string) {
            yield [$string, false, null, $string];
            yield [$string, false, '?', $string];
            yield [$string, true, null, $string];
            yield [$string, true, '?', $string];
        }

        // Fully transliterable string tests;
        // Let's tests these with and without replacement string, as the output should be the same.

        $tests = [
            // full table to single chars
            ["` ¢¦¨", "' c|\""],
            ["ª«¬­¯", "a\"--_"],
            ["°²³´µ", "o23'u"],
            ["¶·¸¹º", "§.,1o"],
            ["»ÀÁÂÃ", "\"AAAA"],
            ["ÈÊËÌÍ", "EEEII"],
            ["ÎÏÐÒÓ", "IIDOO"],
            ["ÔÕ×ÙÚ", "OOxUU"],
            ["ÛÝáâã", "UYaaa"],
            ["çêëíî", "ceeii"],
            ["ïðóôõ", "idooo"],
            ["÷úûýÿ", "/uuyy"],

            // full table to multiple chars
            ["©®±¼", '(c)(r)+/-1/4'],
            ["½¾Þþ", '1/23/4THth'],

            // mix of native and transliterable
            ['À NOËL', 'A NOEL'],
            ['à noël', 'à noel'],
        ];

        foreach ($tests as [$input, $output]) {
            yield [$input, true, null, $output];
            yield [$input, true, '?', $output];
        }

        // Strings with unsupported characters, replacement only.

        yield ['À NOËL 🎁', false, '?', '? NO?L ?'];
        yield ["à 🌲🎁 noël", false, '|', 'à || no|l'];

        // Strings with unsupported characters, transliteration and replacement.

        yield ['À NOËL 🎁', true, '?', 'A NOEL ?'];
        yield ["à 🌲🎁 noël", true, '|', 'à || noel'];
    }
}
