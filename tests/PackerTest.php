<?php

declare(strict_types=1);

namespace BenMorel\GsmCharsetConverter\Tests;

use BenMorel\GsmCharsetConverter\Packer;
use PHPUnit\Framework\TestCase;

class PackerTest extends TestCase
{
    /**
     * @dataProvider providerPack
     *
     * @param string $input     The 7-bit input string.
     * @param string $outputHex The expected output string, hex-encoded.
     */
    public function testPack(string $input, string $outputHex) : void
    {
        $packer = new Packer();
        $actualOutput = $packer->pack($input);
        $message = strtoupper(bin2hex($actualOutput)) . ' != ' . $outputHex;

        self::assertSame(hex2bin($outputHex), $actualOutput, $message);
    }

    public function providerPack() : array
    {
        return [
            ['', ''],
            ['e', '65'],
            ['z', '7A'],
            ['$?', 'A41F'],
            ['1q', 'B138'],
            ['p-e', 'F05619'],
            ['v7g', 'F6DB19'],
            ['}KxR', 'FD255E0A'],
            ['&)]9', 'A6543707'],
            ['()]!^', 'A85437E405'],
            ['8l)Hg', '38760A7906'],
            [']PqZuN', '5D685C5B7702'],
            ['j!qvnp', 'EA50DCEE8603'],
            ['x6:#}b_', '789B6ED4177F01'],
            ['"/=`}u]', 'A2570FDCAF7701'],
            ['s.P//]ii', '7317F4F5EAA6D3'],
            ['Lg_z]Zhf', 'CCF357DFD5A2CD'],
            ['12345678', '31D98C56B3DD70'],
            ['6BTct]rvP', '3621754CEFCAED50'],
            ['Bi}"-@qlz', 'C2745FD402C6D97A'],
            ['QRB&[uL1,?', '51A9D0B4AD3363AC1F'],
            ['2Qiy+;bXq2', 'B2683ABFDA89B17119'],
            ['*X nA);$%lr', '2A2CC81D4CED4825B61C'],
            ['QrL":+ykEs4', '513953A45BE5D7C5390D'],
            ['tyEZw7mtR[vF', 'F47C517BBFB5E9D2ADDD08'],
            [':6Q>{PuBYYxV', '3A5BD4B787D685D92CDE0A'],
            ['7$C_TQYh.cazO', '37D2F04B8D66D1AE7158FF04'],
            ['^K7NS;Ud4~^H%', 'DEE5CD39DD55C934BF175902'],
            ['62LC}td>`Hg+Qh', '361973D8A7937D60E479154503'],
            ['H!e7OqBEOOu(+$', 'C850F9F68C0B8BCF671DB52201'],
            ['FUUXnYpcV,gIA I', 'C66A15EBCEC2C756D63919042501'],
            ['5nH{YX@i=X+/Yq6', '3537729FC502D33DECEA958DDB00'],
            ['N?2lLW3#QX"iv6Ox', 'CE9F8CCDBCCE4651AC286DB73DF1'],
            ['/eC)G1Xqgm{ 9BE%', 'AFF230758C61E3E7F61E9413164B'],
            ['FK}lo3wRMHd,e_%C2', 'C6659FFD9EDDA54D249955FE968632'],
            ['evo?J5Xscw7)4kBHk', '65FBFBA7AC61E7E3FB2D455B0B916B'],
            ['S{3}uJ4!DT<UGu{i++', 'D3FDAC5F57D242442AAF7AACEFD3AB15'],
            ['HO)fa*+>FIn0ha;&Z=', 'C867CA1C56AD7CC6A41B860EEF4CDA1E'],
            ['@y-$LW>&,5[Y]7>?oag', 'C07C8BC4BCFA4CACDA36DBBDF97EEFF019'],
            ['4^%1]ci.]BjJ]aL&S7L', '346F29D61DA75D5DA15AD90D334DD31B13'],

            ['The quick brown fox jumps over the lazy dog', '54741914AFA7C76B9058FEBEBB41E6371EA4AEB7E173D0DB5E9683E8E832881DD6E741E4F719']
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPack8bitData() : void
    {
        $packer = new Packer();
        $packer->pack("\xAA");
    }
}
