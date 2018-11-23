<?php

declare(strict_types=1);

namespace BenMorel\GsmCharsetConverter;

class Packer
{
    /**
     * Packs a 7-bit string into a 8-bit string.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function pack(string $string) : string
    {
        $result = '';
        $length = strlen($string);

        // The number of bits we'll read from the left of the current septet.
        // We'll read (8 - $bits) bits from the right of the next septet.
        // We'll use put the latter and the former (in this order) together to form an octet.
        $bits = 7;

        // The masks to keep only the last n bits of a byte.
        $masks = [0x01, 0x03, 0x07, 0x0F, 0x1F, 0x3F, 0x7F];

        for ($i = 0; $i < $length; $i++) {
            $septet = ord($string[$i]);

            if (($septet & 0x80) !== 0) {
                throw new \InvalidArgumentException('Input must not contain 8-bit chars.');
            }

            if ($i + 1 === $length) {
                $nextSeptet = 0;
            } else {
                $nextSeptet = ord($string[$i + 1]);
            }

            $octet = ($septet >> (7 - $bits));
            $octet |= (($nextSeptet & $masks[7 - $bits]) << $bits);

            $result .= chr($octet);

            if (--$bits === 0) {
                $bits = 7;
                $i++;
            }
        }

        return $result;
    }
}
