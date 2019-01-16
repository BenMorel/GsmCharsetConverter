<?php

declare(strict_types=1);

namespace BenMorel\GsmCharsetConverter;

/**
 * Converts GSM 03.38 strings to and from UTF-8.
 */
class Converter
{
    /**
     * @var array
     */
    private $utf8ToGsm;

    /**
     * @var array
     */
    private $utf8ToGsmWithTranslit;

    /**
     * Converter constructor.
     */
    public function __construct()
    {
        // Flip the GSM to UTF-8 dictionary to create the UTF-8 to GSM dictionary;
        // Convert all values to strings as the array keys for digits are converted to int by PHP.
        $this->utf8ToGsm = array_map(static function($value) {
            return (string) $value;
        }, array_flip(Charset::GSM_TO_UTF8));

        // Create the base dictionary + transliteration
        $this->utf8ToGsmWithTranslit = $this->utf8ToGsm;

        foreach (Charset::TRANSLITERATE as $from => $to) {
            // Transliterate character by character, as the output string may contain several chars
            $to = $this->splitUtf8String($to);

            $to = array_map(function(string $char) : string {
                return $this->utf8ToGsm[$char];
            }, $to);

            $this->utf8ToGsmWithTranslit[$from] = implode('', $to);
        }
    }

    /**
     * Converts a GSM 03.38 string to UTF-8.
     *
     * The input string must be a valid, unpacked 7-bit GSM charset string: the leading bit must be zero in every byte.
     *
     * @param string $string The GSM charset string to convert. If the string is not a valid GSM charset string,
     *                       an exception is thrown.
     *
     * @return string
     *
     * @throws \InvalidArgumentException If an error occurs.
     */
    public function convertGsmToUtf8(string $string) : string
    {
        $result = '';
        $length = strlen($string);

        $dictionary = Charset::GSM_TO_UTF8;

        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];

            if ($char === "\x1B") {
                if ($i + 1 === $length) {
                    throw new \InvalidArgumentException(
                        'The input string is not valid GSM 03.38: ' .
                        'it contains an ESC char at the end of the string.'
                    );
                }

                $char .= $string[++$i];
            }

            if (! isset($dictionary[$char])) {
                throw new \InvalidArgumentException(
                    'The input string is not valid GSM 03.38: ' .
                    'char ' . strtoupper(bin2hex($char)) . ' is unknown.'
                );
            }

            $result .= $dictionary[$char];
        }

        return $result;
    }

    /**
     * Converts a UTF-8 string to GSM 03.38.
     *
     * The output is an unpacked 7-bit GSM charset string: the leading bit is zero in every byte.
     *
     * @param string      $string       The UTF-8 string to convert. If the string is not valid UTF-8, an exception
     *                                  is thrown.
     * @param bool        $translit     Whether to transliterate, i.e. replace incompatible characters with similar,
     *                                  compatible characters when possible.
     * @param string|null $replaceChars Zero or more UTF-8 characters to replace unknown chars with. You can typically
     *                                  use an empty string, a blank space or a question mark. The string must only
     *                                  contain characters compatible with the GSM charset, or an exception is thrown.
     *                                  If this parameter is omitted or null, and the string to convert contains any
     *                                  character that cannot be replaced, an exception is thrown.
     *
     * @return string
     *
     * @throws \InvalidArgumentException If an error occurs.
     */
    public function convertUtf8ToGsm(string $string, bool $translit, ?string $replaceChars = null) : string
    {
        $dictionary = $translit ? $this->utf8ToGsmWithTranslit : $this->utf8ToGsm;

        // Convert the replacement string to GSM 03.38
        if ($replaceChars !== null) {
            $chars = $this->splitUtf8String($replaceChars);
            $replaceChars = '';

            foreach ($chars as $char) {
                if (! isset($this->utf8ToGsm[$char])) {
                    throw new \InvalidArgumentException(
                        'Replacement string must contain only GSM 03.38 compatible chars.'
                    );
                }

                $replaceChars .= $this->utf8ToGsm[$char];
            }
        }

        $result = '';

        $chars = $this->splitUtf8String($string);

        foreach ($chars as $char) {
            if (isset($dictionary[$char])) {
                $result .= $dictionary[$char];
            } elseif ($replaceChars !== null) {
                $result .= $replaceChars;
            } else {
                throw new \InvalidArgumentException(
                    'UTF-8 character ' . strtoupper(bin2hex($char)) . ' cannot be converted, ' .
                    'and no replacement string has been provided.'
                );
            }
        }

        return $result;
    }

    /**
     * Cleans up the given UTF-8 string, to make it contain only chars compatible with the GSM 03.38 charset.
     *
     * This is useful if your SMS gateway accepts UTF-8, but provides no way to force the GSM charset, and you want to
     * avoid the extra charge of getting your SMS sent as UCS-2 and split into several parts.
     *
     * This is just a convenience method for convertUtf8ToGsm() -> convertGsmToUtf8().
     *
     * @param string      $string       The UTF-8 string to convert. If the string is not valid UTF-8, an exception
     *                                  is thrown.
     * @param bool        $translit     Whether to transliterate, i.e. replace incompatible characters with similar,
     *                                  compatible characters when possible.
     * @param string|null $replaceChars Zero or more UTF-8 characters to replace unknown chars with. You can typically
     *                                  use an empty string, a blank space or a question mark. The string must only
     *                                  contain characters compatible with the GSM charset, or an exception is thrown.
     *                                  If this parameter is omitted or null, and the string to convert contains any
     *                                  character that cannot be replaced, an exception is thrown.
     *
     * @return string
     *
     * @throws \InvalidArgumentException If an error occurs.
     */
    public function cleanUpUtf8String(string $string, bool $translit, ?string $replaceChars = null) : string
    {
        return $this->convertGsmToUtf8(
            $this->convertUtf8ToGsm($string, $translit, $replaceChars)
        );
    }

    /**
     * @param string $string
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function splitUtf8String(string $string) : array
    {
        if (! mb_check_encoding($string, 'UTF-8')) {
            throw new \InvalidArgumentException('The input string is not valid UTF-8.');
        }

        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
