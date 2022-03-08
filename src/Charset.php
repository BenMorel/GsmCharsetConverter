<?php

declare(strict_types=1);

namespace BenMorel\GsmCharsetConverter;

/**
 * GSM charset maps.
 */
class Charset
{
    /**
     * Maps the GSM 03.38 default alphabet and extension table to UTF-8.
     *
     * These mappings have been checked manually for consistency against the following sources:
     *
     *  https://en.wikipedia.org/wiki/GSM_03.38
     *  https://github.com/chadselph/smssplit/blob/master/js/gsm.js
     *  http://mobiletidings.com/2009/07/06/gsm-7-encoding-gnu-libiconv/
     *
     * The last link (mobiletidings.com) has a conflicting entry for 0x09, mapping it to LATIN SMALL LETTER C WITH
     * CEDILLA. It turns out to be wrong, it's LATIN CAPITAL LETTER C WITH CEDILLA instead, as mentioned in the other
     * links. Tested on Twilio: ç is sent as UCS2, while Ç is sent as GSM.
     */
    public const GSM_TO_UTF8 = [
        "\x00" => "\u{0040}", // COMMERCIAL AT
        "\x01" => "\u{00A3}", // POUND SIGN
        "\x02" => "\u{0024}", // DOLLAR SIGN
        "\x03" => "\u{00A5}", // YEN SIGN
        "\x04" => "\u{00E8}", // LATIN SMALL LETTER E WITH GRAVE
        "\x05" => "\u{00E9}", // LATIN SMALL LETTER E WITH ACUTE
        "\x06" => "\u{00F9}", // LATIN SMALL LETTER U WITH GRAVE
        "\x07" => "\u{00EC}", // LATIN SMALL LETTER I WITH GRAVE
        "\x08" => "\u{00F2}", // LATIN SMALL LETTER O WITH GRAVE
        "\x09" => "\u{00C7}", // LATIN CAPITAL LETTER C WITH CEDILLA
        "\x0A" => "\u{000A}", // LINE FEED
        "\x0B" => "\u{00D8}", // LATIN CAPITAL LETTER O WITH STROKE
        "\x0C" => "\u{00F8}", // LATIN SMALL LETTER O WITH STROKE
        "\x0D" => "\u{000D}", // CARRIAGE RETURN
        "\x0E" => "\u{00C5}", // LATIN CAPITAL LETTER A WITH RING ABOVE
        "\x0F" => "\u{00E5}", // LATIN SMALL LETTER A WITH RING ABOVE
        "\x10" => "\u{0394}", // GREEK CAPITAL LETTER DELTA
        "\x11" => "\u{005F}", // LOW LINE
        "\x12" => "\u{03A6}", // GREEK CAPITAL LETTER PHI
        "\x13" => "\u{0393}", // GREEK CAPITAL LETTER GAMMA
        "\x14" => "\u{039B}", // GREEK CAPITAL LETTER LAMDA
        "\x15" => "\u{03A9}", // GREEK CAPITAL LETTER OMEGA
        "\x16" => "\u{03A0}", // GREEK CAPITAL LETTER PI
        "\x17" => "\u{03A8}", // GREEK CAPITAL LETTER PSI
        "\x18" => "\u{03A3}", // GREEK CAPITAL LETTER SIGMA
        "\x19" => "\u{0398}", // GREEK CAPITAL LETTER THETA
        "\x1A" => "\u{039E}", // GREEK CAPITAL LETTER XI
        // \x1B = ESCAPE TO EXTENSION TABLE, see below
        "\x1C" => "\u{00C6}", // LATIN CAPITAL LETTER AE
        "\x1D" => "\u{00E6}", // LATIN SMALL LETTER AE
        "\x1E" => "\u{00DF}", // LATIN SMALL LETTER SHARP S (German)
        "\x1F" => "\u{00C9}", // LATIN CAPITAL LETTER E WITH ACUTE
        "\x20" => "\u{0020}", // SPACE
        "\x21" => "\u{0021}", // EXCLAMATION MARK
        "\x22" => "\u{0022}", // QUOTATION MARK
        "\x23" => "\u{0023}", // NUMBER SIGN
        "\x24" => "\u{00A4}", // CURRENCY SIGN
        "\x25" => "\u{0025}", // PERCENT SIGN
        "\x26" => "\u{0026}", // AMPERSAND
        "\x27" => "\u{0027}", // APOSTROPHE
        "\x28" => "\u{0028}", // LEFT PARENTHESIS
        "\x29" => "\u{0029}", // RIGHT PARENTHESIS
        "\x2A" => "\u{002A}", // ASTERISK
        "\x2B" => "\u{002B}", // PLUS SIGN
        "\x2C" => "\u{002C}", // COMMA
        "\x2D" => "\u{002D}", // HYPHEN-MINUS
        "\x2E" => "\u{002E}", // FULL STOP
        "\x2F" => "\u{002F}", // SOLIDUS
        "\x30" => "\u{0030}", // DIGIT ZERO
        "\x31" => "\u{0031}", // DIGIT ONE
        "\x32" => "\u{0032}", // DIGIT TWO
        "\x33" => "\u{0033}", // DIGIT THREE
        "\x34" => "\u{0034}", // DIGIT FOUR
        "\x35" => "\u{0035}", // DIGIT FIVE
        "\x36" => "\u{0036}", // DIGIT SIX
        "\x37" => "\u{0037}", // DIGIT SEVEN
        "\x38" => "\u{0038}", // DIGIT EIGHT
        "\x39" => "\u{0039}", // DIGIT NINE
        "\x3A" => "\u{003A}", // COLON
        "\x3B" => "\u{003B}", // SEMICOLON
        "\x3C" => "\u{003C}", // LESS-THAN SIGN
        "\x3D" => "\u{003D}", // EQUALS SIGN
        "\x3E" => "\u{003E}", // GREATER-THAN SIGN
        "\x3F" => "\u{003F}", // QUESTION MARK
        "\x40" => "\u{00A1}", // INVERTED EXCLAMATION MARK
        "\x41" => "\u{0041}", // LATIN CAPITAL LETTER A
        "\x42" => "\u{0042}", // LATIN CAPITAL LETTER B
        "\x43" => "\u{0043}", // LATIN CAPITAL LETTER C
        "\x44" => "\u{0044}", // LATIN CAPITAL LETTER D
        "\x45" => "\u{0045}", // LATIN CAPITAL LETTER E
        "\x46" => "\u{0046}", // LATIN CAPITAL LETTER F
        "\x47" => "\u{0047}", // LATIN CAPITAL LETTER G
        "\x48" => "\u{0048}", // LATIN CAPITAL LETTER H
        "\x49" => "\u{0049}", // LATIN CAPITAL LETTER I
        "\x4A" => "\u{004A}", // LATIN CAPITAL LETTER J
        "\x4B" => "\u{004B}", // LATIN CAPITAL LETTER K
        "\x4C" => "\u{004C}", // LATIN CAPITAL LETTER L
        "\x4D" => "\u{004D}", // LATIN CAPITAL LETTER M
        "\x4E" => "\u{004E}", // LATIN CAPITAL LETTER N
        "\x4F" => "\u{004F}", // LATIN CAPITAL LETTER O
        "\x50" => "\u{0050}", // LATIN CAPITAL LETTER P
        "\x51" => "\u{0051}", // LATIN CAPITAL LETTER Q
        "\x52" => "\u{0052}", // LATIN CAPITAL LETTER R
        "\x53" => "\u{0053}", // LATIN CAPITAL LETTER S
        "\x54" => "\u{0054}", // LATIN CAPITAL LETTER T
        "\x55" => "\u{0055}", // LATIN CAPITAL LETTER U
        "\x56" => "\u{0056}", // LATIN CAPITAL LETTER V
        "\x57" => "\u{0057}", // LATIN CAPITAL LETTER W
        "\x58" => "\u{0058}", // LATIN CAPITAL LETTER X
        "\x59" => "\u{0059}", // LATIN CAPITAL LETTER Y
        "\x5A" => "\u{005A}", // LATIN CAPITAL LETTER Z
        "\x5B" => "\u{00C4}", // LATIN CAPITAL LETTER A WITH DIAERESIS
        "\x5C" => "\u{00D6}", // LATIN CAPITAL LETTER O WITH DIAERESIS
        "\x5D" => "\u{00D1}", // LATIN CAPITAL LETTER N WITH TILDE
        "\x5E" => "\u{00DC}", // LATIN CAPITAL LETTER U WITH DIAERESIS
        "\x5F" => "\u{00A7}", // SECTION SIGN
        "\x60" => "\u{00BF}", // INVERTED QUESTION MARK
        "\x61" => "\u{0061}", // LATIN SMALL LETTER A
        "\x62" => "\u{0062}", // LATIN SMALL LETTER B
        "\x63" => "\u{0063}", // LATIN SMALL LETTER C
        "\x64" => "\u{0064}", // LATIN SMALL LETTER D
        "\x65" => "\u{0065}", // LATIN SMALL LETTER E
        "\x66" => "\u{0066}", // LATIN SMALL LETTER F
        "\x67" => "\u{0067}", // LATIN SMALL LETTER G
        "\x68" => "\u{0068}", // LATIN SMALL LETTER H
        "\x69" => "\u{0069}", // LATIN SMALL LETTER I
        "\x6A" => "\u{006A}", // LATIN SMALL LETTER J
        "\x6B" => "\u{006B}", // LATIN SMALL LETTER K
        "\x6C" => "\u{006C}", // LATIN SMALL LETTER L
        "\x6D" => "\u{006D}", // LATIN SMALL LETTER M
        "\x6E" => "\u{006E}", // LATIN SMALL LETTER N
        "\x6F" => "\u{006F}", // LATIN SMALL LETTER O
        "\x70" => "\u{0070}", // LATIN SMALL LETTER P
        "\x71" => "\u{0071}", // LATIN SMALL LETTER Q
        "\x72" => "\u{0072}", // LATIN SMALL LETTER R
        "\x73" => "\u{0073}", // LATIN SMALL LETTER S
        "\x74" => "\u{0074}", // LATIN SMALL LETTER T
        "\x75" => "\u{0075}", // LATIN SMALL LETTER U
        "\x76" => "\u{0076}", // LATIN SMALL LETTER V
        "\x77" => "\u{0077}", // LATIN SMALL LETTER W
        "\x78" => "\u{0078}", // LATIN SMALL LETTER X
        "\x79" => "\u{0079}", // LATIN SMALL LETTER Y
        "\x7A" => "\u{007A}", // LATIN SMALL LETTER Z
        "\x7B" => "\u{00E4}", // LATIN SMALL LETTER A WITH DIAERESIS
        "\x7C" => "\u{00F6}", // LATIN SMALL LETTER O WITH DIAERESIS
        "\x7D" => "\u{00F1}", // LATIN SMALL LETTER N WITH TILDE
        "\x7E" => "\u{00FC}", // LATIN SMALL LETTER U WITH DIAERESIS
        "\x7F" => "\u{00E0}", // LATIN SMALL LETTER A WITH GRAVE

        // Extension table

        "\x1B\x0A" => "\u{000C}", // FORM FEED
        // 1B0D = CR2 is a control char, not implemented here
        "\x1B\x14" => "\u{005E}", // CIRCUMFLEX ACCENT
        // 1B1B = SS2 is a control char reserved for future extensions, not implemented here
        "\x1B\x28" => "\u{007B}", // LEFT CURLY BRACKET
        "\x1B\x29" => "\u{007D}", // RIGHT CURLY BRACKET
        "\x1B\x2F" => "\u{005C}", // REVERSE SOLIDUS
        "\x1B\x3C" => "\u{005B}", // LEFT SQUARE BRACKET
        "\x1B\x3D" => "\u{007E}", // TILDE
        "\x1B\x3E" => "\u{005D}", // RIGHT SQUARE BRACKET
        "\x1B\x40" => "\u{007C}", // VERTICAL LINE
        "\x1B\x65" => "\u{20AC}", // EURO SIGN
    ];

    /**
     * Maps UTF-8 chars that are not present in the GSM charset to a close match in one or more GSM charset-compatible
     * UTF-8 chars.
     */
    public const TRANSLITERATE = [
        // Characters in the Unicode range 0000 - 00FF (latin1).
        // This list is hand-crafted and aims to cover the full latin1 range. Mappings marked with (*) are very
        // rough approximations that could be candidate for removal if full latin1 range is not a requirement anymore.
        "\u{0060}" => "\u{0027}", // GRAVE ACCENT => APOSTROPHE
        "\u{00A0}" => "\u{0020}", // NO-BREAK SPACE => SPACE
        "\u{00A2}" => "\u{0063}", // CENT SIGN => LATIN SMALL LETTER C
        "\u{00A6}" => "\u{007C}", // BROKEN BAR => VERTICAL LINE
        "\u{00A8}" => "\u{0022}", // DIAERESIS => QUOTATION MARK (*)
        "\u{00A9}" => "(c)"     , // COPYRIGHT SIGN
        "\u{00AA}" => "\u{0061}", // FEMININE ORDINAL INDICATOR => LATIN SMALL LETTER A
        "\u{00AB}" => "\u{0022}", // LEFT-POINTING DOUBLE ANGLE QUOTATION MARK => QUOTATION MARK
        "\u{00AC}" => "\u{002D}", // NOT SIGN => HYPHEN-MINUS (*)
        "\u{00AD}" => "\u{002D}", // SOFT HYPHEN => HYPHEN-MINUS
        "\u{00AE}" => "(r)",      // REGISTERED SIGN
        "\u{00AF}" => "\u{005F}", // MACRON => LOW LINE (*)
        "\u{00B0}" => "\u{006F}", // DEGREE SIGN => LATIN SMALL LETTER O (*)
        "\u{00B1}" => "+/-",      // PLUS-MINUS SIGN
        "\u{00B2}" => "\u{0032}", // SUPERSCRIPT TWO => DIGIT TWO
        "\u{00B3}" => "\u{0033}", // SUPERSCRIPT THREE => DIGIT THREE
        "\u{00B4}" => "\u{0027}", // ACUTE ACCENT => APOSTROPHE
        "\u{00B5}" => "\u{0075}", // MICRO SIGN => LATIN SMALL LETTER U
        "\u{00B6}" => "\u{00A7}", // PILCROW SIGN => SECTION SIGN (*)
        "\u{00B7}" => "\u{002E}", // MIDDLE DOT => FULL STOP
        "\u{00B8}" => "\u{002C}", // CEDILLA => COMMA (*)
        "\u{00B9}" => "\u{0031}", // SUPERSCRIPT ONE => DIGIT ONE
        "\u{00BA}" => "\u{006F}", // MASCULINE ORDINAL INDICATOR => LATIN SMALL LETTER O (*)
        "\u{00BB}" => "\u{0022}", // RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK => QUOTATION MARK
        "\u{00BC}" => "1/4",      // VULGAR FRACTION ONE QUARTER
        "\u{00BD}" => "1/2",      // VULGAR FRACTION ONE HALF
        "\u{00BE}" => "3/4",      // VULGAR FRACTION THREE QUARTERS
        "\u{00C0}" => "\u{0041}", // LATIN CAPITAL LETTER A WITH GRAVE => LATIN CAPITAL LETTER A
        "\u{00C1}" => "\u{0041}", // LATIN CAPITAL LETTER A WITH ACUTE => LATIN CAPITAL LETTER A
        "\u{00C2}" => "\u{0041}", // LATIN CAPITAL LETTER A WITH CIRCUMFLEX => LATIN CAPITAL LETTER A
        "\u{00C3}" => "\u{0041}", // LATIN CAPITAL LETTER A WITH TILDE => LATIN CAPITAL LETTER A
        "\u{00C8}" => "\u{0045}", // LATIN CAPITAL LETTER E WITH GRAVE => LATIN CAPITAL LETTER E
        "\u{00CA}" => "\u{0045}", // LATIN CAPITAL LETTER E WITH CIRCUMFLEX => LATIN CAPITAL LETTER E
        "\u{00CB}" => "\u{0045}", // LATIN CAPITAL LETTER E WITH DIAERESIS => LATIN CAPITAL LETTER E
        "\u{00CC}" => "\u{0049}", // LATIN CAPITAL LETTER I WITH GRAVE => LATIN CAPITAL LETTER I
        "\u{00CD}" => "\u{0049}", // LATIN CAPITAL LETTER I WITH ACUTE => LATIN CAPITAL LETTER I
        "\u{00CE}" => "\u{0049}", // LATIN CAPITAL LETTER I WITH CIRCUMFLEX => LATIN CAPITAL LETTER I
        "\u{00CF}" => "\u{0049}", // LATIN CAPITAL LETTER I WITH DIAERESIS => LATIN CAPITAL LETTER I
        "\u{00D0}" => "\u{0044}", // LATIN CAPITAL LETTER ETH => LATIN CAPITAL LETTER D
        "\u{00D2}" => "\u{004F}", // LATIN CAPITAL LETTER O WITH GRAVE => LATIN CAPITAL LETTER O
        "\u{00D3}" => "\u{004F}", // LATIN CAPITAL LETTER O WITH ACUTE => LATIN CAPITAL LETTER O
        "\u{00D4}" => "\u{004F}", // LATIN CAPITAL LETTER O WITH CIRCUMFLEX => LATIN CAPITAL LETTER O
        "\u{00D5}" => "\u{004F}", // LATIN CAPITAL LETTER O WITH TILDE => LATIN CAPITAL LETTER O
        "\u{00D7}" => "\u{0078}", // MULTIPLICATION SIGN => LATIN SMALL LETTER X
        "\u{00D9}" => "\u{0055}", // LATIN CAPITAL LETTER U WITH GRAVE => LATIN CAPITAL LETTER U
        "\u{00DA}" => "\u{0055}", // LATIN CAPITAL LETTER U WITH ACUTE => LATIN CAPITAL LETTER U
        "\u{00DB}" => "\u{0055}", // LATIN CAPITAL LETTER U WITH CIRCUMFLEX => LATIN CAPITAL LETTER U
        "\u{00DD}" => "\u{0059}", // LATIN CAPITAL LETTER Y WITH ACUTE => LATIN CAPITAL LETTER Y
        "\u{00DE}" => "TH",       // LATIN CAPITAL LETTER THORN
        "\u{00E1}" => "\u{0061}", // LATIN SMALL LETTER A WITH ACUTE => LATIN SMALL LETTER A
        "\u{00E2}" => "\u{0061}", // LATIN SMALL LETTER A WITH CIRCUMFLEX => LATIN SMALL LETTER A
        "\u{00E3}" => "\u{0061}", // LATIN SMALL LETTER A WITH TILDE => LATIN SMALL LETTER A
        "\u{00E7}" => "\u{0063}", // LATIN SMALL LETTER C WITH CEDILLA => LATIN SMALL LETTER C
        "\u{00EA}" => "\u{0065}", // LATIN SMALL LETTER E WITH CIRCUMFLEX => LATIN SMALL LETTER E
        "\u{00EB}" => "\u{0065}", // LATIN SMALL LETTER E WITH DIAERESIS => LATIN SMALL LETTER E
        "\u{00ED}" => "\u{0069}", // LATIN SMALL LETTER I WITH ACUTE => LATIN SMALL LETTER I
        "\u{00EE}" => "\u{0069}", // LATIN SMALL LETTER I WITH CIRCUMFLEX => LATIN SMALL LETTER I
        "\u{00EF}" => "\u{0069}", // LATIN SMALL LETTER I WITH DIAERESIS => LATIN SMALL LETTER I
        "\u{00F0}" => "\u{0064}", // LATIN SMALL LETTER ETH => LATIN SMALL LETTER D
        "\u{00F3}" => "\u{006F}", // LATIN SMALL LETTER O WITH ACUTE => LATIN SMALL LETTER O
        "\u{00F4}" => "\u{006F}", // LATIN SMALL LETTER O WITH CIRCUMFLEX => LATIN SMALL LETTER O
        "\u{00F5}" => "\u{006F}", // LATIN SMALL LETTER O WITH TILDE => LATIN SMALL LETTER O
        "\u{00F7}" => "\u{002F}", // DIVISION SIGN => SOLIDUS
        "\u{00FA}" => "\u{0075}", // LATIN SMALL LETTER U WITH ACUTE => LATIN SMALL LETTER U
        "\u{00FB}" => "\u{0075}", // LATIN SMALL LETTER U WITH CIRCUMFLEX => LATIN SMALL LETTER U
        "\u{00FD}" => "\u{0079}", // LATIN SMALL LETTER Y WITH ACUTE => LATIN SMALL LETTER Y
        "\u{00FE}" => "th",       // LATIN SMALL LETTER THORN
        "\u{00FF}" => "\u{0079}", // LATIN SMALL LETTER Y WITH DIAERESIS => LATIN SMALL LETTER Y

        "\u{FEFF}" => "",         // ZERO WIDTH NO-BREAK SPACE
        "\u{2019}" => "\u{0027}", // RIGHT SINGLE QUOTATION MARK => APOSTROPHE
        "\u{02BC}" => "\u{0027}", // MODIFIER LETTER APOSTROPHE => APOSTROPHE
        "\u{275C}" => "\u{0027}", // HEAVY SINGLE COMMA QUOTATION MARK ORNAMENT => APOSTROPHE

        // French chars.
        "\u{0152}" => "OE",       // LATIN CAPITAL LIGATURE OE
        "\u{0153}" => "oe",       // LATIN SMALL LIGATURE OE
        "\u{0178}" => "\u{0059}", // LATIN CAPITAL LETTER Y WITH DIAERESIS => LATIN CAPITAL LETTER Y

        // Polish chars.
        // See: https://github.com/BenMorel/GsmCharsetConverter/issues/1
        "\u{0105}" => "\u{0061}",
        "\u{0104}" => "\u{0041}",
        "\u{0107}" => "\u{0063}",
        "\u{0106}" => "\u{0043}",
        "\u{0119}" => "\u{0065}",
        "\u{0118}" => "\u{0045}",
        "\u{0142}" => "\u{006C}",
        "\u{0141}" => "\u{004C}",
        "\u{0144}" => "\u{006E}",
        "\u{0143}" => "\u{004E}",
        "\u{015b}" => "\u{0073}",
        "\u{015a}" => "\u{0053}",
        "\u{017a}" => "\u{007A}",
        "\u{0179}" => "\u{005A}",
        "\u{017c}" => "\u{007A}",
        "\u{017b}" => "\u{005A}",

        // Romanian chars.
        // See: https://github.com/BenMorel/GsmCharsetConverter/issues/3
        "\u{0218}" => "\u{0053}", // Ș => S
        "\u{0219}" => "\u{0073}", // ș => s
        "\u{021A}" => "\u{0054}", // Ț => T
        "\u{021B}" => "\u{0074}", // ț => t
        "\u{0102}" => "\u{0041}", // Ă => A
        "\u{0103}" => "\u{0061}", // ă => a

        // Greek chars.
        "\u{0391}" => "\u{0041}", // GREEK CAPITAL LETTER ALPHA (A)
        "\u{0386}" => "\u{0041}", // GREEK CAPITAL LETTER ALPHA TONOS Ά
        "\u{0392}" => "\u{0042}", // GREEK CAPITAL LETTER BETTA (Β)
        "\u{0395}" => "\u{0045}", // GREEK CAPITAL LETTER EPSILON TONOS (Ε)
        "\u{0388}" => "\u{0045}", // GREEK CAPITAL LETTER EPSILON TONOS (Έ)
        "\u{0396}" => "\u{005A}", // GREEK CAPITAL LETTER ZITA (Z)
        "\u{0397}" => "\u{0048}", // GREEK CAPITAL LETTER ITA (Η)
        "\u{0389}" => "\u{0048}", // GREEK CAPITAL LETTER ITA TONOS (Ή)
        "\u{0399}" => "\u{0049}", // GREEK CAPITAL LETTER YIOTA (Ι)
        "\u{038A}" => "\u{0049}", // GREEK CAPITAL LETTER YIOTA TONOS (Ί)
        "\u{039A}" => "\u{004B}", // GREEK CAPITAL LETTER KAPPA (Κ)
        "\u{039C}" => "\u{004D}", // GREEK CAPITAL LETTER MI (Μ)
        "\u{039D}" => "\u{004E}", // GREEK CAPITAL LETTER NI (Ν)
        "\u{039F}" => "\u{004F}", // GREEK CAPITAL LETTER OMIKRON (Ο)
        "\u{038C}" => "\u{004F}", // GREEK CAPITAL LETTER OMIKRON TONOS (Ό)
        "\u{03A1}" => "\u{0050}", // GREEK CAPITAL LETTER RO (Ρ)
        "\u{03A4}" => "\u{0054}", // GREEK CAPITAL LETTER TAF (Τ)
        "\u{03A5}" => "\u{0059}", // GREEK CAPITAL LETTER IPSILON (Υ)
        "\u{038E}" => "\u{0059}", // GREEK CAPITAL LETTER IPSILON TONOS (Ϋ)
        "\u{03A7}" => "\u{0058}", // GREEK CAPITAL LETTER CHI (Χ)
        "\u{038F}" => "\u{03A9}", // GREEK CAPITAL LETTER OMEGA TONOS (Ώ)

        // Czech and Slovak chars.
        "\u{010C}" => "\u{0043}", // Č => C
        "\u{010D}" => "\u{0063}", // č => c
        "\u{0160}" => "\u{0053}", // Š => S
        "\u{0161}" => "\u{0073}", // š => s
        "\u{0164}" => "\u{0054}", // Ť => T
        "\u{0165}" => "\u{0074}", // ť => t
        "\u{017D}" => "\u{005A}", // Ž => Z
        "\u{017E}" => "\u{007A}", // ž => z
        "\u{013D}" => "\u{004C}", // Ľ => L
        "\u{013E}" => "\u{006C}", // ľ => l
        "\u{0147}" => "\u{004E}", // Ň => N
        "\u{0148}" => "\u{006E}", // ň => n
        "\u{010E}" => "\u{0044}", // Ď => D
        "\u{010F}" => "\u{0064}", // ď => d
        "\u{0139}" => "\u{004C}", // Ĺ => L
        "\u{013A}" => "\u{006C}", // ĺ => l
        "\u{0155}" => "\u{0072}", // ŕ => r
        "\u{011A}" => "\u{0045}", // Ě => E
        "\u{011B}" => "\u{0065}", // ě => e
        "\u{0158}" => "\u{0052}", // Ř => R
        "\u{0159}" => "\u{0072}", // ř => r
        "\u{016E}" => "\u{0055}", // Ů => U
        "\u{016F}" => "\u{0075}", // ů => u
    ];
}
