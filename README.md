# GSM Charset Converter

A PHP library to convert GSM 03.38, the charset used for SMS messaging, to and from UTF-8.

[![Build Status](https://secure.travis-ci.org/BenMorel/GsmCharsetConverter.svg?branch=master)](http://travis-ci.org/BenMorel/GsmCharsetConverter)
[![Coverage Status](https://coveralls.io/repos/BenMorel/GsmCharsetConverter/badge.svg?branch=master)](https://coveralls.io/r/BenMorel/GsmCharsetConverter?branch=master)
[![Latest Stable Version](https://poser.pugx.org/benmorel/gsm-charset-converter/v/stable)](https://packagist.org/packages/benmorel/gsm-charset-converter)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](http://opensource.org/licenses/MIT)

This library is well tested. The character maps used have been cross-checked against multiple sources, and when in doubt, a test has been performed on a real SMS gateway.

The library offers optional transliteration: unsupported characters can be replaced with a close variant. For example, the `ë` character can be replaced with `e`.

Known limitations:

- Only the default alphabet and extension table are supported at the moment; this is the alphabet that must be supported by every device and network element according to the standard. [Other alphabets exist](https://en.wikipedia.org/wiki/GSM_03.38#National_language_shift_tables) but this project does not currently aim to support them.
- Transliteration is only available for the latin1 alphabet; it may not make sense to provide much more transliteration anyway, as the likelihood to find a close variant of a non-latin1 character in the GSM charset is quite small. If you feel like another UTF-8 character could be transliterated, please [open an issue](https://github.com/BenMorel/GsmCharsetConverter/issues)!

## Installation

This library is installable via [Composer](https://getcomposer.org/):

```bash
composer require benmorel/gsm-charset-converter
```

## Requirements

This library requires PHP >= 7.1, and the `mbstring` extension.

## Usage

### Converting GSM 03.38 strings to UTF-8

The `convertGsmToUtf8()` method takes no parameters:

```php
use BenMorel\GsmCharsetConverter\Converter;

$converter = new Converter();
$utf8 = $converter->convertGsmToUtf8('...');
```

The input string must be a valid GSM 03.38 string, or an `InvalidArgumentException` is thrown.
**The input string is expected to be unpacked**: 7-bit chars in 8-bit bytes with a leading zero bit, just like ASCII chars.

### Converting UTF-8 strings to GSM 03.38

The `convertUtf8ToGsm()` method accepts 3 parameters:

- a valid UTF-8 input string;
- whether or not to attempt to transliterate incompatible chars;
- an optional string to replace unknown characters with.

If the input string is not valid UTF-8, an `InvalidArgumentException` is thrown.

The output is an unpacked GSM 03.38 string.

#### Without transliteration

```php
$gsm = $converter->convertUtf8ToGsm('Helló', false, '?'); // Hell?
```

If the third parameter is not provided, and the string contains characters incompatible with GSM 03.38, an `InvalidArgumentException` is thrown.

#### With transliteration

```php
$gsm = $converter->convertUtf8ToGsm('Helló', true, '?'); // Hello
```

If the third parameter is not provided, and the string contains characters incompatible with GSM 03.38 and not transliterable, an `InvalidArgumentException` is thrown.

### Cleaning up UTF-8 strings to ensure that a message is sent in the GSM charset

Nowadays, most online SMS gateways accept UTF-8 as input; however, some of them do not provide a way to force a message to be sent in the GSM charset.

As a result, you may end up with extra charges caused by your SMS being sent in Unicode (UCS-2) format, causing the segmentation of messages in multiple parts, just because your SMS message contains an unforeseen accented character or emoji.

The library provides a method, `cleanUpUtf8String()`, that prevents these bad surprises, by returning a UTF-8 string that contains only characters that can be safely converted to the GSM charset.

This method accepts the same parameters as `convertUtf8ToGsm()`:

```php
$utf8 = $converter->cleanUpUtf8String('Helló', false, '?'); // Hell?
$utf8 = $converter->cleanUpUtf8String('Helló', true, '?'); // Hello
```

### Packing 7-bit strings into 8-bit binary strings

To fit 160 7-bit characters into a 140 bytes SMS, the characters have to be packed into a binary, 8-bit string.
The `Packer` class provides functionality to pack and unpack strings in this format:

```php
use BenMorel\GsmCharsetConverter\Packer;

$packer = new Packer();
$packed = $packer->pack('ABC'); // the binary string 41E110
$string = $packer->unpack("\x41\xE1\x10"); // ABC
```

Note that `pack()` throws an `InvalidArgumentException` if the input string contains 8-bit chars (i.e. chars with the leading bit set).

