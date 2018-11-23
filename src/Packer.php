<?php

declare(strict_types=1);

namespace BenMorel\GsmCharsetConverter;

class Packer
{
    /**
     * The masks to keep only the first n bits of a byte, zeroing out the other bits.
     */
    const MASK_FIRST_N_BITS = [1 => 0x80, 0xC0, 0xE0, 0xF0, 0xF8, 0xFC, 0xFE];

    /**
     * The masks to keep only the last n bits of a byte, zeroing out the other bits.
     */
    const MASK_LAST_N_BITS = [1 => 0x01, 0x03, 0x07, 0x0F, 0x1F, 0x3F, 0x7F];

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
            $octet |= (($nextSeptet & self::MASK_LAST_N_BITS[8 - $bits]) << $bits);

            $result .= chr($octet);

            if (--$bits === 0) {
                $bits = 7;
                $i++;
            }
        }

        return $result;
    }

    /**
     * Unpacks an 8-bit string into a 7-bit string.
     *
     * Note: unpacking can be ambiguous when the length of the input string is a multiple of 7, and the last septet
     * is zero (i.e. when the last octet is 0x00 or 0x01). For example, both 0x7F7F7F7F7F7F7F and 0x7F7F7F7F7F7F7F00
     * pack to 0xFFFFFFFFFFFF01, so without context, we cannot know while unpacking if there is a trailing zero septet,
     * or if the zeros are just padding. This method always resolves to dropping the last zero in this special case:
     * 0xFFFFFFFFFFFF01 will unpack to F7F7F7F7F7F7F.
     *
     * @param string $string
     *
     * @return string
     */
    public function unpack(string $string) : string
    {
        $result = '';
        $length = strlen($string);

        // The number of bits we'll read from the right of the current octet.
        // We'll carry the remaining (8 - $bits) bits onto the next septet.
        // We'll use put the former and the carry (in this order) together to form a septet.
        $bits = 7;

        $carry = 0;

        for ($i = 0; $i < $length; $i++) {
            $octet = ord($string[$i]);

            $septet = ($octet & self::MASK_LAST_N_BITS[$bits]) << (7 - $bits);

            if ($bits !== 7) {
                $septet |= ($carry >> ($bits + 1));
            }

            $carry = ($octet & self::MASK_FIRST_N_BITS[8 - $bits]);

            $result .= chr($septet);

            if (--$bits === 0) {
                $bits = 7;

                if ($carry !== 0) {
                    $result .= chr($carry >> 1);
                }
            }
        }

        return $result;
    }
}
