<?php

declare(strict_types=1);

namespace BenMorel\GsmCharsetConverter;

/**
 * Converts GSM 03.38 strings to and from UTF-8.
 */
class Converter
{
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
}
